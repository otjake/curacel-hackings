<x-mail::message>
<x-slot:header>
</x-slot:header>
@if ($invoice->is_due)
  # Your Invoice is Due
@else
  # Your Invoice Will Be Due In {{ $invoice->due_date->fromNow() }}
@endif

Dear {{$invoice->customer->name}},<br>

@if($invoice->is_due)
  This is to inform you that the invoice <strong>#{{ $invoice->invoice_number }}</strong> is already due for payment.
@else
  This is a reminder for invoice <strong>#{{ $invoice->invoice_number }}</strong> from <strong>{{ $invoice->payer->name}}</strong>.
  The invoice will be due in {{ $invoice->due_date->fromNow() }}
@endif

**The details are shown below:** <br>
Invoice No: {{ $invoice->invoice_number }}<br>

<x-invoice-details-table :invoice="$invoice" />
@if ($invoice->payment_mode != "offline")
    <x-mail::button color="payblue" :url="$invoiceUrl">
    View Invoice
    </x-mail::button>
@endif

<x-slot:subcopy>
    Thanks, <br>
    {{$invoice->payer->name}}.
</x-slot:subcopy>
</x-mail::message>
