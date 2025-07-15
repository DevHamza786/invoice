<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice PDF</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="invoice-box"
        style="background: #fff; border-radius: 12px; padding: 0; max-width: 100%; margin: auto;">
        <!-- Header -->
        <div style="background: #1976d2; color: #fff; padding: 32px 32px 24px 32px; width: 100%;">
    <table width="100%" cellpadding="0" cellspacing="0" style="border: none;">
        <tr>
            <td style="vertical-align: top; width: 55%;">
                <div style="font-size: 2.2rem; font-weight: bold; letter-spacing: 2px; margin-bottom: 8px;">INVOICE</div>
                <div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 6px;">{{ html_entity_decode(getAppName()) }}</div>
                <div style="font-size: 1rem; margin-bottom: 2px;">{{ $setting['company_address'] }}</div>
                @if ($setting['show_additional_address_in_invoice'])
                    <div style="font-size: 1rem; margin-bottom: 2px;">
                        {{ $setting['country'] }}, {{ $setting['state'] }}, {{ $setting['city'] }}, {{ $setting['zipcode'] }}
                    </div>
                @endif
                <div style="font-size: 1rem; margin-bottom: 2px;">Mobile: {{ $setting['company_phone'] }}</div>
                <div style="font-size: 1rem; margin-bottom: 2px;">Email: {{ $setting['company_email'] ?? '' }}</div>
            </td>
            <td style="vertical-align: top; text-align: right; width: 45%; padding:20px;">
                <img src="{{ getLogoUrl() }}" alt="Logo" style="max-height: 100px;">
            </td>
        </tr>
    </table>
</div>

        <!-- Bill To & Invoice Details -->
        <div style="padding: 20px 20px 10px 20px; border-bottom: 2px solid #e3e3e3;">
    <table width="100%" cellpadding="0" cellspacing="0" style="border: none;">
        <tr>
            <td style="vertical-align: top; width: 50%;">
                <div style="color: #1976d2; font-weight: bold;">Bill To</div>
                <div style="font-weight: bold;">{{ $client->user->full_name }}</div>
                <div>{{ $client->address ?? '' }}</div>
                <div>{{ $client->user->email }}</div>
            </td>
            <td style="vertical-align: top; width: 50%; text-align: right;">
                <div style="font-weight: bold;">Invoice No : <span
                        style="font-weight: normal;">{{ $invoice->invoice_id }}</span></div>
                <div>Invoice Date : <span
                        style="font-weight: normal;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</span>
                </div>
                <div>Due Date : <span
                        style="font-weight: normal;">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span>
                </div>
            </td>
        </tr>
    </table>
</div>

        <!-- Items Table -->
        <div style="padding: 0 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px; border-collapse: collapse;">
                <thead>
                    <tr style="background: #1976d2; color: #fff;">
                        <th style="padding: 8px; text-align: left;">Sl.</th>
                        <th style="padding: 8px; text-align: left;">Description</th>
                        <th style="padding: 8px; text-align: right;">Qty</th>
                        <th style="padding: 8px; text-align: right;">Rate</th>
                        <th style="padding: 8px; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->invoiceItems as $key => $item)
                        <tr style="background: {{ $key % 2 == 0 ? '#f6f9fc' : '#fff' }};">
                            <td style="padding: 8px;">{{ $key + 1 }}</td>
                            <td style="padding: 8px;">{{ $item->product->name ?? $item->product_name }}</td>
                            <td style="padding: 8px; text-align: right;">{{ number_format($item->quantity, 2) }}</td>
                            <td style="padding: 8px; text-align: right;">
                                {{ getInvoiceCurrencyAmount($item->price, $invoice->currency_id, true) }}</td>
                            <td style="padding: 8px; text-align: right;">
                                {{ getInvoiceCurrencyAmount($item->total, $invoice->currency_id, true) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Payment Instructions & Summary -->
        <div style="padding: 20px 20px 0 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border: none;">
                <tr>
                    <td style="vertical-align: top; width: 50%;">
                        <div style="color: #1976d2; font-weight: bold; margin-bottom: 5px;">Payment Instructions</div>
                        <div style="color: #888;">Pay Cheque to</div>
                        <div style="font-weight: bold;">{{ $setting['company_name'] ?? getAppName() }}</div>
                    </td>
                    <td style="vertical-align: top; width: 50%;">
                        <table width="100%" style="font-size: 1rem;">
                            <tr>
                                <td style="padding: 4px 0;">Subtotal</td>
                                <td style="text-align: right; padding: 4px 0;">{{ getInvoiceCurrencyAmount($invoice->amount, $invoice->currency_id, true) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0;">Total</td>
                                <td style="text-align: right; padding: 4px 0; font-weight: bold;">{{ getInvoiceCurrencyAmount($invoice->final_amount, $invoice->currency_id, true) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0;">Paid</td>
                                <td style="text-align: right; padding: 4px 0;">{{ getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id), $invoice->currency_id, true) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; color: #1976d2; font-weight: bold;">Balance Due</td>
                                <td style="text-align: right; padding: 4px 0; color: #1976d2; font-weight: bold;">{{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id), $invoice->currency_id, true) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Signature -->
        <div style="padding: 20px 20px 0 20px;">
            <div style="margin-bottom: 20px;">
                <div style="font-weight: bold; color: #1976d2; margin-bottom: 5px;">Note:</div>
                <div style="color: #444;">{!! nl2br($invoice->note ?? 'This is a default note.') !!}</div>
            </div>
            <div>
                <div style="font-weight: bold; color: #1976d2; margin-bottom: 5px;">Terms:</div>
                <div style="color: #444;">{!! nl2br($invoice->term ?? 'These are default terms and conditions.') !!}</div>
            </div>
        </div>
    </div>
</body>

</html>
