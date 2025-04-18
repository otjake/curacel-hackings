<x-mail::message>
Dear {{$user->name}},<br>

We are pleased to inform you that a payout of <strong>{{ number_format($payment->amount, 2) }} {{$payment->currency}}</strong> has successfully been disbursed into the beneficiary's bank account. The details are shown below:

<x-payout-details-table :payout="$payment" />

<x-mail::button color="payblue" :url="$paymentUrl">
View Payout
</x-mail::button>
</x-mail::message>
