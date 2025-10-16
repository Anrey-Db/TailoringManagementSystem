<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->reference_number ?: 'PAY-' . $payment->id }}</title>
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
        .payment-info {
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
        .amount-section {
            margin-top: 30px;
            text-align: center;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .amount-label {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        .amount-value {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
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
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-refunded { background-color: #f8d7da; color: #721c24; }
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

    <div class="receipt-title">PAYMENT RECEIPT</div>

    <div class="payment-info">
        <div class="info-section">
            <div class="info-label">Payment Reference:</div>
            <div class="info-value">{{ $payment->reference_number ?: 'PAY-' . $payment->id }}</div>
            
            <div class="info-label">Customer:</div>
            <div class="info-value">{{ $payment->order->customer->first_name }} {{ $payment->order->customer->last_name }}</div>
            
            @if($payment->order->customer->contact_number)
            <div class="info-label">Contact:</div>
            <div class="info-value">{{ $payment->order->customer->contact_number }}</div>
            @endif
            
            @if($payment->order->customer->address)
            <div class="info-label">Address:</div>
            <div class="info-value">{{ $payment->order->customer->address }}</div>
            @endif
        </div>
        
        <div class="info-section">
            <div class="info-label">Order Number:</div>
            <div class="info-value">{{ $payment->order->order_number }}</div>
            
            <div class="info-label">Payment Date:</div>
            <div class="info-value">{{ $payment->payment_date->format('F d, Y') }}</div>
            
            <div class="info-label">Payment Method:</div>
            <div class="info-value">{{ $payment->payment_method }}</div>
            
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-{{ strtolower($payment->status ?? 'completed') }}">
                    {{ $payment->status ?? 'Completed' }}
                </span>
            </div>
        </div>
    </div>

    <div class="amount-section">
        <div class="amount-label">Amount Paid</div>
        <div class="amount-value">₱{{ number_format($payment->amount_paid, 2) }}</div>
    </div>

    @if($payment->order)
    <div style="margin-top: 30px;">
        <h4>Order Details</h4>
        <div class="row">
            <div class="col-6">
                <strong>Order Total:</strong> ₱{{ number_format($payment->order->total_amount, 2) }}
            </div>
            <div class="col-6">
                <strong>Amount Paid:</strong> ₱{{ number_format($payment->order->amount_paid, 2) }}
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <strong>Outstanding Balance:</strong> ₱{{ number_format($payment->order->balance, 2) }}
            </div>
            <div class="col-6">
                <strong>Payment Status:</strong> {{ $payment->order->payment_status }}
            </div>
        </div>
    </div>
    @endif

    @if($payment->order && $payment->order->measurement && $payment->order->measurement->items->count() > 0)
    <div style="margin-top: 30px;">
        <h4>Order Items</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f5f5f5;">
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Item Type</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quantity</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Price per Item</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->order->measurement->items as $item)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->item_type }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->quantity }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">₱{{ number_format($item->price_per_item, 2) }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">₱{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your payment!</p>
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
