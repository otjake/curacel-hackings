<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice </title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        .--text{
            font-family: 'Outfit', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
        }

        .field-header {
            color: #5e626a;
        }

        .field-value {
            color: #030124;
        }

        .mt-4 {
            margin-top: 0.875rem;
        }

        .mt-10 {
            margin-top: 1.125rem;
        }

        .border-b {
            border-bottom: 1px solid #e5e5e5;
        }

        .flex {
            display: flex;
        }

        .flex-row {
            flex-direction: row;
        }

        .gap-4 {
            gap: 0.875rem;
        }

        .space-x-2 {
            margin-left: 0.375rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-lg {
            font-size: 1rem;
        }

        .text-sm {
            font-size: 0.75rem;
        }

        .text-xl {
            font-size: 1.125rem;
        }

        .block {
            display: block;
        }

        .border-t {
            border-top: 1px solid #e5e5e5;
        }

        .table-auto {
            width: 100%;
            border-collapse: collapse;
        }

        .table-auto td {
            padding: 1px;
            line-height: 3;
            font-size: 12px;
            color: #6B7280;
        }

        .table-auto th {
            text-align: left;
            background-color: #f2f2f2;
            font-size: 10px;
            font-weight: 500;
            padding: 6px;
            line-height: 3;
        }
        .logo {
            background-color: #8a42ff3b;
            color: #8a42ff;
            border: 1px solid #8a42ff;
            border-radius: 50%;
            width: 2em;
            height: 2em;
            text-align: center;
            font-weight: bold;
            font-size: 1.375em;
            vertical-align: middle;
            line-height: 2em;
            margin-top: 1em;
        }

        @media (min-width: 768px) {
        .flex-col.md\:flex-row {
            display: flex;
            flex-direction: row;
        }

        .md\:mt-0 {
            margin-top: 0;
        }

        .md\:w-1\/2 {
            width: 50%;
            line-height: 2;
        }

        .flex-col {
            flex-direction: column;
        }
        .md-items-end {
            align-items: flex-end;
        }
        .md-w-full {
            width: 100%;
        }
        .mt-6 {
            margin-top: 1.375rem;
        }

        .pb-8 {
            padding-bottom: 1.875rem;
        }
        .justify-between {
            justify-content: space-between;
        }
        .justify-end {
            justify-content: flex-end;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            padding: 3px 8px;
        }
        .w-1-2 {
            width: 50%;
        }
        .w-full {
            width: 100%;
        }

        .mb-4 {
            margin-bottom: 0.875rem;
        }

        @media print {
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
        }
    }
    </style>
    </head>
    <body class="--text" style="position:relative">
            <div style="background-color: #fff;border-radius: 10px; border: 1px solid #eef1f8; padding: 15px; margin-bottom: 16px;">
                <div style="display: flex; flex-direction: row; justify-content: space-between;">
                    <div>
                        @if ($invoice->payer->logo)
                            <div style="text-align: left">
                                <img src="{{$invoice->payer->logo_url}}" alt="{{ $invoice->payer->name }}" style="max-width: 200px; max-height: 100px; object-fit: contain; display: block; margin:auto; margin-top: 0.625em;">
                            </div>
                        @else
                            <div class="logo">
                                {{ substr($invoice->payer->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="field-value font-medium text-lg mb-4">
                            {{ $invoice->payer->name }}
                        </h3>
                        <div class="field-value" style="color: #030124; font-weight: 400; font-size: 13px; text-align: left;">
                            @if($invoice->payer->kycInformation?->address_line_one)
                                {{ $invoice->payer->kycInformation->address_line_one }} <br>
                            @endif
                            @if($invoice->payer->kycInformation?->address_line_two)
                                {{ $invoice->payer->kycInformation->address_line_two }} <br>
                            @endif
                            @if($invoice->payer->kycInformation?->city)
                                {{ $invoice->payer->kycInformation->city }},
                            @endif
                            @if($invoice->payer->kycInformation?->state?->name)
                                {{ $invoice->payer->kycInformation->state->name }} <br>
                            @endif
                            @if($invoice->payer->kycInformation?->country?->name)
                                {{ $invoice->payer->kycInformation->country->name }} <br>
                            @endif
                            @if($invoice->payer->kycInformation?->phone_number)
                                {{ $invoice->payer->kycInformation->phone_number }} <br>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="margin-top: 36px; display: flex; flex-direction: row; justify-content: space-between; font-size: 14px;">
                    <div>
                        <span style="display: block; color:#5e626a;">Bill To</span>
                        <span style="display: block; margin-top: 14px;">{{ $invoice->customer->name }}</span>
                        <span style="display: block; margin-top: 8px;">{{ $invoice->customer->email }}</span>
                        @if($invoice->customer->phone_number)
                            <span style="display: block; margin-top: 8px;">{{ $invoice->customer->phone_number }}</span>
                        @endif
                    </div>
                    <div>
                        <div style="display: flex; gap: 14px; justify-content: right; margin-bottom: 8px;">
                            @if ($invoice->status->value === 'paid')
                                <span style="color: #00B372; border: solid 1px #00B372; border-radius: 8px; padding: 6px 8px 6px 8px; background-color: #D4EDDA;">
                                    <span style="width: 5px; height: 5px; background-color: #00B372; border-radius: 50%; display: inline-block; margin: 0px 1.5px 1.5px 0px"></span>
                                    {{ ucfirst($invoice->status->value) }}
                                </span>
                            @elseif ($invoice->status->value === 'overdue' || $invoice->status->value === 'cancelled')
                                <span style="color: #DC3545; border: solid 1px #c76a74; border-radius: 8px; padding: 6px 8px 6px 8px; background-color: #F8D7DA;">
                                    <span style="width: 5px; height: 5px; background-color: #DC3545; border-radius: 50%; display: inline-block; margin: 0px 1.5px 1.5px 0px"></span>
                                    {{ ucfirst($invoice->status->value) }}
                                </span>
                            @else {{-- pending --}}
                                <span style="color:#FA8C2A; border: solid 1px #FFDBBB; border-radius: 8px; padding: 6px 8px 6px 8px; background-color: #f9f1Eb;">
                                    <span style="width: 5px; height: 5px; background-color: #FA8C2A; border-radius: 50%; display: inline-block; margin: 0px 1.5px 1.5px 0px"></span>
                                    {{ ucfirst($invoice->status->value) }}
                                </span>
                            @endif
                        </div>
                        <div style="display: flex; gap: 14px;">
                            <span style="color:#5e626a;">Invoice#</span>
                            <span>{{ $invoice->invoice_number ?? 'N/A' }}</span>
                        </div>
                        <div style="display: flex; gap: 14px; margin-top: 6px;">
                            <span style="color:#5e626a;">Issued On</span>
                            <span>{{ $invoice->is_draft ? 'N/A' : ($invoice->issued_at ?? $invoice->created_at ?? now())->format('D, M d, Y') }}</span>
                        </div>
                        <div style="display: flex; gap: 14px; margin-top: 6px;">
                            <span style="color:#5e626a;">Due Date</span>
                            <span> {{ \Carbon\Carbon::parse($invoice->due_date)->format('D, M d, Y') }}</span>
                        </div>
                        <div style="display: flex; gap: 14px; margin-top: 6px;">
                            <span style="color:#5e626a;">Amount Due</span>
                            <span> {{ $invoice->currency ?? 'NGN'}} {{ number_format($invoice->amount_due) }}</span>
                        </div>
                    </div>
                </div>


                <div class="mt-10 border-b">
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th style="text-align: left;">ITEM DESCRIPTION</th>
                                <th style="text-align: center;">QTY</th>
                                <th style="text-align: right;">PRICE ({{ $invoice->currency ?? 'NGN' }})</th>
                                <th style="text-align: right;">AMOUNT ({{ $invoice->currency ?? 'NGN' }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td style="text-align: left;">{{ $item->description }}</td>
                                    <td style="text-align: center;">{{ $item->qty }}</td>
                                    <td style="text-align: right;">{{ number_format($item->price, 2) }}</td>
                                    <td style="text-align: right;">{{ number_format($item->total ?? ($item->qty * $item->price), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div style="width: 300px; margin-left: auto; padding-top: 8px; border-top: 1px solid #e0e0e0; line-height: 1.8; font-size: 14px;">
                    <div style="display: flex; justify-content: space-between;">
                        <div style="text-align: left; color:#5e626a;">Sub-total</div>
                        <div style="text-align: right;">{{ $invoice->currency ?? 'NGN' }} {{ number_format($invoice->sub_total) }}</div>
                    </div>

                    @if($invoice->discount_value > 0)
                        <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                            <div style="text-align: left;">Discount {{ $invoice->discount_type !== 'fixed' ? "({$invoice->discount_value}%)" : '' }}</div>
                            <div style="text-align: right;">{{ $invoice->currency ?? 'NGN' }} {{ number_format($invoice->discount) }}</div>
                        </div>
                    @endif

                    @if($invoice->vat_percent > 0)
                        <div style="display: flex; justify-content: space-between; margin-top: 5px; border-bottom: 1px solid #e2e8f0;">
                            <div style="text-align: left;">VAT {{ $invoice->vat_percent > 0 ? "({$invoice->vat_percent}%)" : '' }}</div>
                            <div style="text-align: right;">{{ $invoice->currency ?? 'NGN' }} {{ number_format($invoice->vat, 2) }}</div>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                        <div style="text-align: left; color:#5e626a;">Total</div>
                        <div style="text-align: right; font-size: 18px !important; font-weight: 500">{{ $invoice->currency ?? 'NGN' }} {{ number_format($invoice->total) }}</div>
                    </div>
                    @if($invoice->overdue_fee > 0)
                        <div style="display: flex; justify-content: space-between; margin-top: 5px; border-bottom: 1px solid #e2e8f0;">
                            <div style="text-align: left;">Default Interest (%)</div>
                            <div style="text-align: right;">{{ $invoice->overdue_fee }}</div>
                        </div>
                    @endif
                </div>

                <div class="border-t mt-2 py-3">
                    <div style="display: flex; justify-content: space-between; margin: 4px 0px;">
                        <div style="text-align: left; color:#5e626a;font-size: 14px !important;">In words</div>
                        <div style="text-align: right; font-size: 14px !important; font-weight: 500">
                            {{ number_spell($invoice->total, $invoice->currency ?? 'NGN') }}
                        </div>
                    </div>
                </div>

                @if($invoice->additional_info)
                    <div class="border-t mt-2">
                        <div class="mt-4">
                            <span style="color:#5e626a; font-size: 14px; display: block" >Payment Terms/Notes</span>
                            <p style="white-space: pre-line; margin-top: 2px; font-size: 14px;">
                            {{ $invoice->additional_info }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>


            <div style="width: 100%; text-align: center; position: fixed; bottom: 0; left: 0;">
                <span style="color: grey;">© {{ date('Y') }}. @lang('Powered by ')
                    <a href="https://www.curacel.co/"><img width="auto" height="13" src="https://res.cloudinary.com/ddble5id6/image/upload/q_auto/v1/pay/curacel-small" alt="curacel_logo"></a>
                </span>
            </div>
    </body>
    </html>
