<x-mail::message>

Your payment of {{ number_format($payment->amount, 2) }} {{ $payment->currency  }} to {{ $payer->name  }} was successful.
The details are shown below:

<x-mail::panel>

**Reference:** {{ $payment->reference }}<br><br>
**Amount:** {{ number_format($payment->amount, 2) }} {{ $payment->currency  }} <br><br>
**Narration:** {{ $payment->description }}<br><br>
**Status:** {{ $payment->status }}<br><br>

</x-mail::panel>

</x-mail::message>
