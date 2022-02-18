<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CategoryEloquent;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(CategoryEloquent $categoryEloquent)
    {
        $this->category= $categoryEloquent;
    }
    public function index(){
        return $this->category->index();
    }
    public function store(Request $request){
        return $this->category->store($request->all());
    }
}
