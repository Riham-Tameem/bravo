<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;

class OrderEloquent extends BaseController
{
    private $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }
    public function index(array $data){
        $user=auth()->user()->id;
        if(isset($data['quantity'])){
            $sub_total=0;
            $result=0;
            $product=Cart::where('user_id',$user)
                ->where('product_id',$data['product_id'])->first();
            $product->quantity=$data['quantity'];
            $product->update();
          /*  $orderProduct=OrderProduct::where('order_id',$data['order_id'])->where('product_id',$data['product_id'])->first();
            $orderProduct->quantity=$data['quantity'];
            $orderProduct->update();
            //dd($orderProduct);
            $order=Order::where('id',$data['order_id'])->first();

            $result=$sub_total + $order->delivery;
            $order->update([
                'result' => $result,
                'sub_total'=>$sub_total,
            ]);*/
        }
        $order=Order::where('id',$data['order_id'])->get();
        //$order = Order::where('user_id',$user)->get();
        return $this->sendResponse('all product', OrderResource::collection($order));
    }
    public function add(array $data){
        DB::beginTransaction();
        try {
            $user=auth()->user()->id;
            $carts=Cart::where('user_id',$user)->get();
            $sub_total=0;
            $result=0;
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
            $order=Order::create([
                'delivery' => 5,
                'address'=>$data['address'],
                'latitude'=>$data['latitude'],
                'longitude'=>$data['longitude'],
                'user_id' =>$user
            ]);
            //dd($order);
            foreach ($carts as $cart){
               // $orderProduct= new OrderProduct();
               $orderProduct= OrderProduct::create([
                    'product_id'=> $cart->product->id,
                    'quantity'=>$cart->quantity,
                    'cost' =>$cart->cost,
                    'order_id' =>$order->id,
                ]);
                $sub_total+=$orderProduct->quantity * $orderProduct->cost;
            }
          //  dd($sub_total);
            $result=$sub_total + $order->delivery;
         // dd($result);
            //dd($order);
            $order->update([
                'sub_total'=>$sub_total,
                'result' => $result,
            ]);
         //dd($order);
            DB::commit();
            return $this->sendResponse('add order successfully', new OrderResource($order));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage());
        }
    }


}
