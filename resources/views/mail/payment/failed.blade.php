<x-mail::message>
Dear {{$user->name}},<br>

{{$message}}. The details are shown below:

<x-payout-details-table :payout="$payment" />

<x-mail::button color="payblue" :url="$paymentUrl">
View Payout
</x-mail::button>
</x-mail::message>
