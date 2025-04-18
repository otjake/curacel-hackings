<x-mail::message>
<x-slot:header>
</x-slot:header>
# Over Due Fee Charged For Invoice #{{ $invoice->invoice_number }}

Dear {{$invoice->customer->name}},<br>

We hope this email finds you well. We are writing to inform you that an overdue fee has been added to your invoice #{{  $invoice->invoice_number }} due to its overdue status.<br>

Please note that the overdue fee is calculated at a rate of {{$invoice->overdue_fee}}% at 30 day interval(s), on the outstanding balance.<br>

To avoid any further fees, we kindly ask that you settle this invoice as soon as possible.<br>

<x-mail::small-button color="payblue" :url="$invoiceDownloadUrl">
More Details.
</x-mail::small-button>

<x-slot:subcopy>
    Thank you once again for choosing {{$invoice->payer->name}}. We look forward to serving you again soon!
</x-slot:subcopy>
</x-mail::message>
