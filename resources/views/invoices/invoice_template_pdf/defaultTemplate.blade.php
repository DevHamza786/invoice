<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice PDF</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; background: #f8f9fa; }
        .invoice-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 40px 30px;
            margin: 30px auto;
            max-width: 800px;
        }
        .header {
            background: #2d3748;
            color: #fff;
            padding: 30px 30px 20px 30px;
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header .logo img { max-height: 60px; }
        .header .company-details { text-align: right; }
        .invoice-title { font-size: 2.5rem; font-weight: bold; letter-spacing: 2px; }
        .badge { background: #38a169; color: #fff; padding: 5px 15px; border-radius: 20px; font-size: 1rem; }
        .table th, .table td { vertical-align: middle !important; }
        .table th { background: #f1f5f9; }
        .table-striped tbody tr:nth-of-type(odd) { background: #f9fafb; }
        .totals-row td { font-weight: bold; }
        .totals-row .amount { color: #2d3748; font-size: 1.2rem; }
        .footer { margin-top: 40px; text-align: center; color: #a0aec0; font-size: 0.95rem; }
    </style>
</head>
<body>
    <div class="invoice-box" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 0; max-width: 800px; margin: 30px auto;">
        <!-- Header -->
        <div style="background: #1976d2; color: #fff; border-radius: 12px 12px 0 0; padding: 30px 30px 20px 30px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 2.2rem; font-weight: bold; letter-spacing: 2px;">INVOICE</div>
                <div style="font-size: 1.1rem; font-weight: bold; margin-top: 10px;">{{ html_entity_decode(getAppName()) }}</div>
                <div style="font-size: 0.95rem; margin-top: 5px;">
                    {{ $setting['company_address'] }}<br>
                    @if ($setting['show_additional_address_in_invoice'])
                        {{ $setting['country'] }}, {{ $setting['state'] }}, {{ $setting['city'] }}, {{ $setting['zipcode'] }}<br>
                    @endif
                    Mobile: {{ $setting['company_phone'] }}<br>
                    Email: {{ $setting['company_email'] ?? '' }}
                </div>
            </div>
            <div>
                <img src="{{ getLogoUrl() }}" alt="Logo" style="max-height: 60px;">
            </div>
        </div>

        <!-- Bill To & Invoice Details -->
        <div style="padding: 30px 30px 10px 30px; display: flex; justify-content: space-between; border-bottom: 2px solid #e3e3e3;">
            <div style="width: 48%;">
                <div style="color: #1976d2; font-weight: bold;">Bill To</div>
                <div style="font-weight: bold;">{{ $client->user->full_name }}</div>
                <div>{{ $client->address ?? '' }}</div>
                <div>{{ $client->user->email }}</div>
            </div>
            <div style="width: 48%;">
                <div style="font-weight: bold;">Invoice No : <span style="font-weight: normal;">{{ $invoice->invoice_id }}</span></div>
                <div>Invoice Date : <span style="font-weight: normal;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</span></div>
                <div>Due Date : <span style="font-weight: normal;">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span></div>
            </div>
        </div>

        <!-- Items Table -->
        <div style="padding: 0 30px;">
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
                        <td style="padding: 8px; text-align: right;">{{ getInvoiceCurrencyAmount($item->price, $invoice->currency_id, true) }}</td>
                        <td style="padding: 8px; text-align: right;">{{ getInvoiceCurrencyAmount($item->total, $invoice->currency_id, true) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Payment Instructions & Summary -->
        <div style="padding: 20px 30px 0 30px; display: flex; justify-content: space-between;">
            <div style="width: 48%;">
                <div style="color: #1976d2; font-weight: bold; margin-bottom: 5px;">Payment Instructions</div>
                <div style="color: #888;">Pay Cheque to</div>
                <div style="font-weight: bold;">{{ $setting['company_name'] ?? getAppName() }}</div>
            </div>
            <div style="width: 48%;">
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
            </div>
        </div>

        <!-- Signature -->
        <div style="padding: 40px 30px 20px 30px; display: flex; flex-direction: column; align-items: flex-end;">
            <div style="margin-bottom: 40px;">
                <img src="{{ asset('images/signature.png') }}" alt="Signature" style="height: 40px;">
            </div>
            <div style="font-weight: bold; color: #444;">Authorized Signatory</div>
        </div>
    </div>
</body>
</html>
