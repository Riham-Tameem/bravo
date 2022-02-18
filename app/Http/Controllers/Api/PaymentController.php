<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PaymentEloquent;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(PaymentEloquent  $paymentEloquent)
    {
        $this->payment= $paymentEloquent;
    }
    public function payWithpaypal(Request $request)
    {
        return $this->payment->payWithpaypal($request->all());
    }
    public function getPaymentStatus(Request $request)
    {
        return $this->payment->getPaymentStatus($request->all());
    }
}
