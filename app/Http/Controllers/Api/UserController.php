<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Repositories\UserEloquent;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserEloquent $userEloquent)
    {
        $this->user= $userEloquent;
    }
    public function register(RegisterRequest $request)
    {
        return $this->user->register($request->all());
    }
    public function phoneVerification(Request $request)
    {
        return $this->user->phoneVerification($request->all());
    }
    public function login()
    {
        return $this->user->login();
    }

    public function getAuthUser()
    {
        return $this->user->getAuthUser();

    }
    public function sendForgetCode(Request $request){
        return $this->user->sendForgetCode($request->all());

    }
    public function ForgetCode(Request $request){
        return $this->user->ForgetCode($request->all());

    }
    public function resetPassword(Request $request){
        return $this->user->resetPassword($request->all());

    }


}
