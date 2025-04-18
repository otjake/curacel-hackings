<x-mail::message>
Dear {{$user->name}},<br>

You just received {{ number_format($payment->amount, 2) }} {{ $payment->currency  }} from
{{ $payment->customer->name  }} ({{ $payment->customer->email }}).

The details are shown below:

<x-mail::panel>

**Reference:** {{ $payment->reference }}<br><br>
**Amount:** {{ number_format($payment->amount, 2) }} {{ $payment->currency  }}<br><br>
**Narration:** {{ $payment->description }}<br><br>
**Status:** {{ $payment->status }}<br><br>

</x-mail::panel>


</x-mail::message>
