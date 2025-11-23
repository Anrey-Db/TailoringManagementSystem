<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$orders = Order::with(['measurement.items','payments'])->get();
foreach($orders as $order){
    $totalAmount = $order->calculateTotalAmount();
    $totalPaid = $order->payments()->sum('amount_paid');
    $balance = $totalAmount - $totalPaid;
    $paymentStatus = 'Unpaid';
    if ($totalPaid > 0) {
        $paymentStatus = $balance > 0 ? 'Partial' : 'Paid';
    }

    $order->update([
        'total_amount' => $totalAmount,
        'amount_paid' => $totalPaid,
        'balance' => max(0, $balance),
        'payment_status' => $paymentStatus,
    ]);

    echo "Updated Order {$order->id}: total_amount={$totalAmount}, amount_paid={$totalPaid}, balance={$order->balance}, status={$paymentStatus}\n";
}
