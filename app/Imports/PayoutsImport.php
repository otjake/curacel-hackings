<?php

namespace App\Imports;

use App\Actions\Integrations\Anchor\BulkVerifyAccount;
use App\Actions\Payment\CalculatePayoutFee;
use App\DTOs\Payment\PayoutImportData;
use App\Enums\TransactionChannel;
use App\Exceptions\PayoutImportException;
use App\Models\Beneficiary;
use App\Models\Payer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class PayoutsImport implements SkipsEmptyRows, ToCollection, WithHeadingRow, WithMultipleSheets, WithValidation
{
    use Importable;

    public const MAX_BULK_PAYOUT_SIZE = 60;

    public array $validatedData = [];

    public float $totalAmount = 0;

    public float $totalFees = 0;

    private array $existingCombinations = [];

    private Collection $banks;

    public function __construct(public ?Payer $payer = null) {}

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $payouts)
    {
        if ($payouts->isEmpty()) {
            throw new PayoutImportException('Sheet is empty');
        }

        if ($payouts->count() > self::MAX_BULK_PAYOUT_SIZE) {
            throw new PayoutImportException('Row size cannot exceed '.self::MAX_BULK_PAYOUT_SIZE);
        }

        Validator::make($payouts->toArray(), $this->rules())->validate();

        $accountsToVerify = [];

        $this->banks = collect(fetch_banks($this->payer));

        $validatedData = [];
        $totalAmount = 0;
        $totalFees = 0;

        foreach ($payouts as $index => $payout) {
            if ($this->isDuplicateBeneficiary($payout)) {
                return $this->sendException($index + 2, 'Duplicate recipient found');
            }

            $selectedBank = $this->banks
                ->firstWhere('name', str($payout['bank_code'])->replace('_', ' ')->upper());

            if (! $selectedBank) {
                return $this->sendException($index + 2, 'Invalid bank code');
            }

            $payout['fee'] = CalculatePayoutFee::make()->handle($this->payer, $payout['amount']);

            $existingBeneficiary = Beneficiary::where('account_number', $payout['account_number'])
                ->where('bank_code', $selectedBank['code'])
                ->first();

            if ($existingBeneficiary) {
                $validatedData[] = $this->generateRowImportData($payout, $existingBeneficiary);

                $totalAmount += $payout['amount'];
                $totalFees += $payout['fee'];
            } else {
                $accountNumberBankCombo = "{$payout['account_number']}-{$selectedBank['code']}";

                $accountsToVerify[$accountNumberBankCombo] = [
                    'account_number' => $payout['account_number'],
                    'bank_code' => $selectedBank['code'],
                    'index' => $index,
                ];

                $accountsToVerify = match ($this->payer->transaction_channel) {
                    TransactionChannel::MOBILE_MONEY->value => $this->getMobileMoneyAddedData($selectedBank['name'], $accountsToVerify, $accountNumberBankCombo, $payout['name']),
                    default => $accountsToVerify,
                };
            }

            if (! empty($accountsToVerify) && (count($accountsToVerify) >= 10 || $index === $payouts->count() - 1)) {
                try {
                    logger()->info('Verifying account information in bulk...', array_values($accountsToVerify));

                    $bulkVerificationResult = match ($this->payer->transaction_channel) {
                        TransactionChannel::MOBILE_MONEY->value => array_values($accountsToVerify),
                        TransactionChannel::BANK_TRANSFER->value => BulkVerifyAccount::make()->handle(array_values($accountsToVerify)),
                        default => []
                    };

                    if (empty($bulkVerificationResult)) {
                        throw new PayoutImportException(' Unable to fetch verification results');
                    }

                    foreach ($bulkVerificationResult as $result) {
                        if (! data_get($result, 'account_name')) {
                            $accountNumber = data_get($result, 'account_number');

                            throw new PayoutImportException("Account number {$accountNumber} could not be validated for the selected bank.");
                        }

                        $accountNumberBankCombo = "{$result['account_number']}-{$result['bank_code']}";
                        $correspondingIndex = $accountsToVerify[$accountNumberBankCombo]['index'];
                        $correspondingPayout = $payouts[$correspondingIndex];

                        $validatedData[] = $this->generateRowImportData($correspondingPayout, $result);

                        $totalAmount += $payouts[$correspondingIndex]['amount'];
                        $totalFees += $payouts[$correspondingIndex]['fee'];
                    }

                    $accountsToVerify = [];
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
        }

        $this->validatedData = $validatedData;
        $this->totalAmount = $totalAmount;
        $this->totalFees = $totalFees;
    }

    public function rules(): array
    {
        $nullOrRequired = match ($this->payer->transaction_channel) {
            TransactionChannel::MOBILE_MONEY->value => 'required',
            default => 'nullable',
        };

        $accountNumberValidation = match ($this->payer->transaction_channel) {
            TransactionChannel::MOBILE_MONEY->value => 'min:10',
            default => 'size:10',
        };

        return [
            '*.amount' => ['required', 'numeric', 'min:1'],
            '*.account_number' => ['required', $accountNumberValidation],
            '*.bank_code' => ['required', 'string'],
            '*.narration' => ['nullable'],
            '*.name' => [$nullOrRequired],
        ];
    }

    private function isDuplicateBeneficiary(array|Collection $payout): bool
    {
        $uniqueCombination = "{$payout['account_number']}-{$payout['bank_code']}}";

        if (in_array($uniqueCombination, $this->existingCombinations)) {
            return true;
        }

        $this->existingCombinations[] = $uniqueCombination;

        return false;
    }

    private function sendException(int $rowNumber, string $message): PayoutImportException
    {
        throw new PayoutImportException("There was an error on row {$rowNumber}: {$message}");
    }

    private function generateRowImportData(array|Collection $payout, array|Beneficiary $recipientDetails): array
    {
        if ($recipientDetails instanceof Beneficiary) {
            $recipientDetails = [
                'account_number' => $recipientDetails->account_number,
                'bank_code' => $recipientDetails->bank_code,
                'account_name' => $recipientDetails->name,
                'bank_name' => $recipientDetails->bank_name,
            ];
        }

        $data = PayoutImportData::from([
            'account_number' => $recipientDetails['account_number'],
            'bank_code' => $recipientDetails['bank_code'],
            'account_name' => $recipientDetails['account_name'],
            'bank_name' => $recipientDetails['bank_name'],
            'amount' => $payout['amount'],
            'narration' => data_get($payout, 'narration'),
            'fee' => $payout['fee'],
        ])->toArray();

        logger()->info('Payout import data', $data);

        return $data;
    }

    /**
     * @param $bankName
     * @param array $accountsToVerify
     * @param string $accountNumberBankCombo
     * @param $payoutName
     *
     * @return array
     */
    public function getMobileMoneyAddedData($bankName, array $accountsToVerify, string $accountNumberBankCombo, $payoutName): array
    {
        $accountsToVerify[$accountNumberBankCombo]['bank_name'] = $bankName;
        $accountsToVerify[$accountNumberBankCombo]['account_name'] = $payoutName;

        return $accountsToVerify;
    }
}
