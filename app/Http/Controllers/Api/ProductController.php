<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductEloquent;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(ProductEloquent  $productEloquent)
    {
        $this->product= $productEloquent;
    }
    public function index(Request $request){
        return $this->product->index($request->all());
    }
    public function store(Request $request){
        return $this->product->store($request->all());
    }
    public function show(Request $request){
        return $this->product->show($request->all());
    }
    public function favourite(Request $request)
    {
        return $this->product->favourite($request->all());
    }
    public function rate(Request $request)
    {
        return $this->product->rate($request->all());
    }

    }
