<x-mail::message>
<x-slot:header>
</x-slot:header>
# Update to Invoice #{{ $invoice->invoice_number }}

Dear {{$invoice->customer->name}},<br>

A recent update was made to your invoice. Please find the updated details below:<br>

Invoice No: {{ $invoice->invoice_number }}<br>

<x-invoice-details-table :invoice="$invoice" />

<x-mail::button color="payblue" :url="$invoiceUrl">
View Updated Invoice
</x-mail::button>
</x-mail::message>
