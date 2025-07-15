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
    <div class="invoice-box">
        <div class="header">
            <div class="logo">
                <img src="{{ getLogoUrl() }}" alt="Logo">
            </div>
            <div class="company-details">
                <div class="invoice-title">INVOICE</div>
                <div>Invoice #: <span class="badge">#{{ $invoice->invoice_id }}</span></div>
                <div>Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</div>
                <div>Due: <span style="color:#e53e3e;">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span></div>
            </div>
        </div>
        <!-- ... rest of your invoice content ... -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Tax (%)</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->invoiceItems as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->product->name ?? $item->product_name }}</td>
                    <td>{{ number_format($item->quantity, 2) }}</td>
                    <td>{{ getInvoiceCurrencyAmount($item->price, $invoice->currency_id, true) }}</td>
                    <td>
                        @foreach ($item->invoiceItemTax as $tax)
                            {{ $tax->tax ?? 'N/A' }}@if (!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ getInvoiceCurrencyAmount($item->total, $invoice->currency_id, true) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- ... totals, notes, and footer ... -->
        <div class="footer">
            Thank you for your business! | {{ getAppName() }}
        </div>
    </div>
</body>
</html>
