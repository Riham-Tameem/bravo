<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CartEloquent;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(CartEloquent  $cartEloquent)
    {
        $this->cart= $cartEloquent;
    }
    public function index(Request $request){
        return $this->cart->index($request->all());

    }
    public function add(Request $request){
        return $this->cart->add($request->all());

    }

    public function delete(Request $request){
        return $this->cart->delete($request->all());

    }
}
