<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

$orders = Order::with(['measurement.items','payments'])->get();
foreach($orders as $o){
    echo 'Order: '.$o->id.' '.$o->order_number.PHP_EOL;
    echo ' total_amount field: '.($o->total_amount ?? 'null').PHP_EOL;
    echo ' calc items sum: '.$o->measurement->items->sum('total_price').PHP_EOL;
    echo ' amount_paid field: '.($o->amount_paid ?? 'null').PHP_EOL;
    echo ' payments sum: '.$o->payments->sum('amount_paid').PHP_EOL;
    echo ' balance field: '.($o->balance ?? 'null').PHP_EOL;
    echo ' payment_status: '.($o->payment_status ?? 'null').PHP_EOL;
    echo '---'.PHP_EOL;
}
