<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressEloquent extends BaseController
{
    private $model;

    public function __construct(Address $address)
    {
        $this->model = $address;
    }
    public function view(){
        $user = Auth::user()->id;
        $address=Address::where('user_id',$user)->get();
        return $this->sendResponse('all Address', AddressResource::collection($address));
    }
    public function save(array $data){
        $user = Auth::user()->id;
        $address= Address::create([
            'type'=>$data['type'],
            'address'=>$data['address'],
            'latitude'=>$data['latitude'],
            'longitude'=>$data['longitude'],
            'user_id' => $user
        ]);
        return $this->sendResponse('add Address successfully', new AddressResource($address));


    }
}
