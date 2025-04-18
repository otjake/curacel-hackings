<x-mail::message>
<x-slot:header>
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

<table width="100%" style="background: #f0f4f8; margin-top: 20px; border-radius: 10px; text-align: center; margin-bottom: 20px">
    <tr>
        <td>
            <h3 style="font-size: 25px; text-align: center; margin-top: 30px;">@currency($invoice->total, $invoice->currency)</h3>
        </td>
    </tr>
    @if ($invoice->payment_mode != "offline")
    <tr>
        <td>
            <x-mail::small-button color="payblue" :url="$invoiceUrl">
                Review and Pay
            </x-mail::small-button>
        </td>
    </tr>
    @endif
    <tr>
        <td>
            @lang('Powered by ')
            <a href="https://www.curacel.co/">
                <img width="auto" height="13" src="https://res.cloudinary.com/ddble5id6/image/upload/q_auto/v1/pay/curacel-small" alt="curacel_logo">
            </a>
        </td>
    </tr>
</table>

<p>Dear {{$invoice->customer->name}},</p>
<p>This email confirms your recent invoice from {{ $invoice->payer->name }}. Please find the attached PDF document with the detailed invoice information for your reference.</p>

<x-slot:subcopy>
    Thanks, <br>
    {{$invoice->payer->name}}.
</x-slot:subcopy>
</x-mail::message>
