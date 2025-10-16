<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 14px;
            color: #666;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section {
            flex: 1;
        }
        .info-label {
            font-weight: bold;
            color: #333;
        }
        .info-value {
            margin-bottom: 10px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .units-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 12px;
        }
        .units-table th,
        .units-table td {
            border: 1px solid #eee;
            padding: 4px;
            text-align: left;
        }
        .units-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .total-section {
            margin-top: 30px;
            text-align: right;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .total-label {
            font-weight: bold;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-in-progress { background-color: #d1ecf1; color: #0c5460; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #cce5ff; color: #004085; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">TAILORING MANAGEMENT SYSTEM</div>
        <div class="company-tagline">Professional Tailoring Services</div>
    </div>

    <div class="receipt-title">ORDER RECEIPT</div>

    <div class="order-info">
        <div class="info-section">
            <div class="info-label">Order Number:</div>
            <div class="info-value">{{ $order->order_number }}</div>
            
            <div class="info-label">Customer:</div>
            <div class="info-value">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</div>
            
            @if($order->customer->contact_number)
            <div class="info-label">Contact:</div>
            <div class="info-value">{{ $order->customer->contact_number }}</div>
            @endif
            
            @if($order->customer->address)
            <div class="info-label">Address:</div>
            <div class="info-value">{{ $order->customer->address }}</div>
            @endif
        </div>
        
        <div class="info-section">
            <div class="info-label">Order Date:</div>
            <div class="info-value">{{ $order->order_date->format('F d, Y') }}</div>
            
            @if($order->due_date)
            <div class="info-label">Due Date:</div>
            <div class="info-value">{{ $order->due_date->format('F d, Y') }}</div>
            @endif
            
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $order->status)) }}">
                    {{ $order->status }}
                </span>
            </div>
            
            <div class="info-label">Payment Status:</div>
            <div class="info-value">{{ $order->payment_status }}</div>
        </div>
    </div>

    @if($order->measurement && $order->measurement->items->count() > 0)
    <table class="items-table">
        <thead>
            <tr>
                <th>Item Type</th>
                <th>Quantity</th>
                <th>Price per Item</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->measurement->items as $item)
            <tr>
                <td>{{ $item->item_type }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₱{{ number_format($item->price_per_item, 2) }}</td>
                <td>₱{{ number_format($item->total_price, 2) }}</td>
            </tr>
            
            @if($item->units->count() > 0)
            <tr>
                <td colspan="4" style="padding: 0;">
                    <table class="units-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Size</th>
                                <th>Chest</th>
                                <th>Waist</th>
                                <th>Hip</th>
                                <th>Length</th>
                                <th>Unit Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->units as $i => $unit)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $unit->person_name }}</td>
                                <td>{{ $unit->size_label }}</td>
                                <td>{{ $unit->chest }}</td>
                                <td>{{ $unit->waist }}</td>
                                <td>{{ $unit->hip }}</td>
                                <td>{{ $unit->length }}</td>
                                <td>₱{{ number_format($unit->unit_price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span>₱{{ number_format($order->total_amount, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Amount Paid:</span>
            <span>₱{{ number_format($order->amount_paid, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Balance:</span>
            <span class="total-amount">₱{{ number_format($order->balance, 2) }}</span>
        </div>
    </div>

    @if($order->payments->count() > 0)
    <div style="margin-top: 30px;">
        <h4>Payment History</h4>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
                    <td>{{ $payment->reference_number ?: '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
        <p class="no-print">
            <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Print Receipt
            </button>
        </p>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
