<x-mail::message>
<x-slot:header>
    Invoice Cancellation Notice
</x-slot:header>

<table class="header" width="100%" style="border-bottom: 1px solid #e8e5ef; width: 100%;">
    <tr>
        <td style="text-align: left;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 600; color: dark;">{{ $invoice->payer->name }}</h1>
        </td>
        <td style="text-align: right;">
            <span style="font-size: 16px;">Invoice No. {{ $invoice->invoice_number }}</span>
        </td>
    </tr>
</table>

Dear {{ $invoice->customer->name }},

This is to inform you that the invoice #{{ $invoice->invoice_number }} issued to you has been cancelled. 

Please find the attached PDF document with the detailed invoice information for your reference.

If you have any questions or need further clarification regarding this cancellation, please do not hesitate to reach out to us via {{ $invoice->payer->email }} or contact our support team.

Thank you for your understanding.

<x-mail::small-button color="payblue" :url="$invoiceUrl">
View Invoice Details
</x-mail::small-button>

<x-slot:subcopy>
    We appreciate your business and look forward to serving you again in the future.
</x-slot:subcopy>
</x-mail::message>