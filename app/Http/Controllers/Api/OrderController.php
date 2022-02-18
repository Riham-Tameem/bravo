<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrderEloquent;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(OrderEloquent  $orderEloquent)
    {
        $this->order= $orderEloquent;
    }
    public function index(Request $request){
        return $this->order->index($request->all());
    }
    public function add(Request $request){
        return $this->order->add($request->all());
    }
}
