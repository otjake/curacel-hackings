<x-mail::message>
<x-slot:header>
</x-slot:header>
# Payment Received For Invoice #{{ $invoice->invoice_number }}

Dear {{$invoice->customer->name}},<br>

This is to confirm receipt of your payment for invoice #{{ $invoice->invoice_number }}.<br>

<x-mail::small-button color="payblue" :url="$invoiceDownloadUrl">
Download Receipt
</x-mail::small-button>

<x-slot:subcopy>
    Thank you once again for choosing {{$invoice->payer->name}}. We look forward to serving you again soon!
</x-slot:subcopy>
</x-mail::message>
