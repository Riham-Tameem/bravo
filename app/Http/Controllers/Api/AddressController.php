<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressRequest;
use App\Repositories\AddressEloquent;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(AddressEloquent $addressEloquent)
    {
        $this->address= $addressEloquent;
    }
    public function view()
    {
        return $this->address->view();
    }
    public function save(AddressRequest $request)
    {
        return $this->address->save($request->all());
    }
}
