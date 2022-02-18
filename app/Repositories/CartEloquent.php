<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\CartResource;
use App\Models\Cart;

class CartEloquent extends BaseController
{

    private $model;

    public function __construct(Cart $cart)
    {
        $this->model = $cart;
    }
    public function index(array $data){
        $user=auth()->user()->id;
        if(isset($data['quantity'])){
            if( $data['quantity'] < 1) {
                $product=Cart::where('user_id',$user)
                    ->where('product_id',$data['product_id'])->first();
                //dd($product);
                $product->delete();
            } else{
                $product=Cart::where('user_id',$user)
                    ->where('product_id',$data['product_id'])->first();
                $product->quantity=$data['quantity'];
                $product->update();
            }
        }
        $cart = Cart::where('user_id',$user)->get();
        return $this->sendResponse('all product', CartResource::collection($cart));

    }

    public function add(array $data){
     $user=auth()->user()->id;
     $is_cart=Cart::where('user_id',$user)
         ->where('product_id',$data['product_id'])->first();
     if(!$is_cart) {
         $cart=Cart::create([
             'product_id' =>$data['product_id'],
             'user_id' => $user
         ]);
         return $this->sendResponse('add product to cart', new CartResource($cart));
     }
    }

  /*  public function update(array $data){
        $user=auth()->user()->id;
        $product=Cart::where('user_id',$user)
            ->where('product_id',$data['product_id'])->first();
        if($product){
            $product->quantity=$data['quantity'];
            $product->update();
        }
        return $this->sendResponse('add product to cart', new CartResource($product));

    }*/

    public function delete(array $data){
        $user=auth()->user()->id;
        $product=Cart::where('user_id',$user)
            ->where('product_id',$data['product_id'])->first();
        if($product){
            $product->delete();
        }
        return $this->sendResponse('delete product successfully', new CartResource($product));

    }
}
