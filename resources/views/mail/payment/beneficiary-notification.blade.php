<x-mail::message>
Dear {{$beneficiary->name}},<br>

We are pleased to inform you that a payment of <strong>@currency($payment->amount, $payment->currency)</strong> is on its way to your {{$beneficiary->bank_name}} account. The details are shown below:

       
<x-mail::panel>

**Sender:** {{ $payment->payer->name }}<br><br>
**Amount:** @currency($payment->amount, $payment->currency)<br><br>
**Narration:** {{ $payment->narration ?? 'N/A' }}<br><br>
**Reference:** {{ $payment->reference }}<br><br>
**Date:** {{ \Carbon\Carbon::parse($payment->initiated_at)->timezone('GMT+1')->format('F jS, Y. h:ia (T)') }}

</x-mail::panel>

</x-mail::message>
