<div>
    @if (! $isBulkPayout)
        <x-mail::table>
            |               |          |
            | ------------- | --------:|
            | Narration    | **{{$payout->narration ?? 'N/A' }}**      |
            | Reference      | **{{ $payout->reference }}**     |
            | Amount    | **@currency($payout->amount, $payout->currency ?? 'NGN')**      |
            | Fees    | **@currency($payout->fee, $payout->currency ?? 'NGN')**      |
            | Total    | **@currency($payout->total_amount, $payout->currency ?? 'NGN')**      |
            | Beneficiary    | **{{ $payout->beneficiary->name . ' (' . $payout->beneficiary->bank_name . ')' }}**      |
            | Last Reviewer    | **{{ $payout->lastReviewer?->name ?? 'N/A' }}**      |
            @if ($payout->initiated_at)
            | Date Initiated    | **{{ $payout->initiated_at->timezone('Africa/Lagos')->format('F jS, Y. h:ia (T)') }}**      |
            @endif
        </x-mail::table>
    @else
        <x-mail::table>
            |               |          |
            | ------------- | --------:|
            | Batch Name      | **{{ $payout->batch_name ?? 'N/A' }}**     |
            | Reference      | **{{ $payout->reference }}**     |
            | Amount    | **@currency($payout->amount, $payout->currency ?? 'NGN')**      |
            | Fees    | **@currency($payout->fee, $payout->currency ?? 'NGN')**      |
            | Total    | **@currency($payout->total_amount, $payout->currency ?? 'NGN')**      |
            | Beneficiary Count    | **{{ $payout->beneficiary_count }}**      |
            | Last Reviewer    | **{{ $payout->lastReviewer?->name ?? 'N/A' }}**      |
            @if ($payout->initiated_at)
            | Date Initiated    | **{{ $payout->initiated_at->timezone('Africa/Lagos')->format('F jS, Y. h:ia (T)') }}**      |
            @endif
        </x-mail::table>
    @endif
</div>
