<x-mail::message>
# Action Required: Insufficient Balance for Scheduled Payout

Dear {{$user->name}},<br>

This is a reminder that you have one or more payouts scheduled to be processed in {{$hourDifference}} from now. However, our records indicate that your wallet balance is currently insufficient to process these payouts.<br>

To ensure that your payouts are processed as scheduled, we kindly request that you have your wallet funded in due time.<br>

If you have already taken action to address this matter, please disregard this message.<br>

Thank you for your prompt attention to this matter.

<x-mail::button color="payblue" :url="route('dashboard')">
Go to Dashboard
</x-mail::button>
</x-mail::message>