<x-mail::message>
Dear {{$user->name}},<br>

A payout of <strong>{{ number_format($payment->amount, 2) }} {{$payment->currency}}</strong> has been initiated from your organization. The details are shown below:

<x-payout-details-table :payout="$payment" />

<x-mail::button color="payblue" :url="$paymentUrl">
View Payout
</x-mail::button>
</x-mail::message>
