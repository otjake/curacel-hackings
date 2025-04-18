<?php

namespace App\Imports;

use App\Actions\GetBanks;
use App\Actions\Integrations\Anchor\BulkVerifyAccount;
use App\Exceptions\ActionCouldNotBeProcessed;
use App\Exceptions\CustomerImportException;
use App\Exceptions\PayoutImportException;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements SkipsEmptyRows, ToCollection, WithHeadingRow, WithMultipleSheets, WithValidation
{
    use Importable;

    private array $uniqueEmails = [];

    public array $validatedData = [];

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    /**
     * @param Collection $collection
     *
     * @return PayoutImportException|void
     *
     * @throws ActionCouldNotBeProcessed|CustomerImportException
     */
    public function collection(Collection $collection)
    {
        if ($collection->isEmpty()) {
            throw new CustomerImportException('Sheet is empty');
        }

        $banks = collect(GetBanks::run());

        $accountsToVerify = [];

        foreach ($collection as $index => $customer) {

            if ($this->isDuplicateCustomer($customer)) {
                return $this->sendException($index + 2, 'Duplicate customer found');
            }

            if (! empty($customer['account_number']) && ! empty($customer['bank_code'])) {

                $bank = $banks
                    ->firstWhere('name', str($customer['bank_code'])->replace('_', ' ')->upper());

                $accountsToVerify[$this->getAccountIdentifier($customer['account_number'], $bank['code'])] = [
                    'index' => $index,
                    'account_number' => $customer['account_number'],
                    'bank_code' => $bank['code'],
                ];
            } else {
                $this->validatedData[] = [
                    'email' => $customer['email'],
                    'name' => $customer['name'],
                    'phone_number' => ! empty($customer['phone_number']) ? $customer['phone_number'] : null,
                ];
            }

            if (! empty($accountsToVerify) && (count($accountsToVerify) >= 10 || $index === $collection->count() - 1)) {
                logger()->info('Verifying account information in bulk...', array_values($accountsToVerify));

                $bulkVerificationResult = BulkVerifyAccount::make()->handle(array_values($accountsToVerify));

                foreach ($bulkVerificationResult as $result) {
                    if (! data_get($result, 'account_name')) {
                        $accountNumber = data_get($result, 'account_number');

                        throw new CustomerImportException("Account number {$accountNumber} could not be validated for the selected bank.");
                    }

                    $accountVerified = $accountsToVerify[$this->getAccountIdentifier($result['account_number'], $result['bank_code'])];
                    $correspondingCustomer = $collection[$accountVerified['index']];

                    $this->validatedData[] = [
                        'email' => $correspondingCustomer['email'],
                        'name' => $correspondingCustomer['name'],
                        'account_number' => $result['account_number'],
                        'bank_code' => $accountVerified['bank_code'],
                        'account_name' => $result['account_name'],
                        'phone_number' => ! empty($correspondingCustomer['phone_number']) ? $correspondingCustomer['phone_number'] : null,
                    ];
                }

                $accountsToVerify = [];
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('customers')->where('payer_id', request()->payer()->id),
            ],
            '*.name' => ['required', 'string', 'min:3'],
            '*.account_number' => [
                'nullable', 'string', 'size:10',
                Rule::unique('customers')->where('payer_id', request()->payer()->id),
            ],
            '*.bank_code' => ['nullable', 'required_with:account_number', 'string'],
            '*.account_name' => ['nullable', 'required_with:account_number', 'string', 'min:3'],
            '*.phone_number' => ['nullable', 'phone'],
        ];
    }

    private function isDuplicateCustomer(array|Collection $customer): bool
    {
        if (in_array($customer['email'], $this->uniqueEmails)) {
            return true;
        }
        $this->uniqueEmails[] = $customer['email'];

        return false;
    }

    private function getAccountIdentifier($accountNumber, $bankCode): string
    {
        return "{$accountNumber}-{$bankCode}";
    }

    /**
     * @throws CustomerImportException
     */
    private function sendException(int $rowNumber, string $message): PayoutImportException
    {
        throw new CustomerImportException("There was an error on row {$rowNumber}: {$message}");
    }
}
