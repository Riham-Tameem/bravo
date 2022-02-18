<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class UserEloquent extends BaseController
{

    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function register(array $data)
    {
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

      //  \request()->request->add(['username'=>$data['phone']]);
        $user->verification_code=registerCode();
       // dd($user->verification_code);
        // $user->verification_code=1234;
        return $this->sendResponse('successfully Register the user and send phone Verification', new UserResource($user));
      // return $this->login();
    }

    public function phoneVerification(array $data){
        $user=User::where('phone',$data['phone'])->first();
        if($data['verification_code'] == $user->verification_code){
          //  \request()->request->add(['username'=>$data['phone']]);
            return $this->login();
        }else{
            return $this->sendError('enter valid Verification code');
        }

    }


    public function login()
    {
        $proxy = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($proxy);
      //dd($proxy);
        $statusCode = $response->getStatusCode();
      //  dd($statusCode);
        $response = json_decode($response->getContent());
        //dd($response);
        if ($statusCode != 200)
            return $this->sendError($response->message);
        $response_token = $response;
        $token = $response->access_token;
        \request()->headers->set('Authorization', 'Bearer ' . $token);
        $proxy = Request::create('api/auth', 'GET');
        $response = Route::dispatch($proxy);
        // dd($response);
        $statusCode = $response->getStatusCode();
        //dd(json_decode($response->getContent()));
       // dd($response->getContent());
        $user = json_decode($response->getContent())->item;

        return $this->sendResponse('Successfully Login', ['token' => $response_token, 'user' => $user]);
    }
     public function getAuthUser()
     {
         $user = auth()->user();
         return $this->sendResponse('user info', new UserResource($user));
     }

   /* public function getAuthUser()
    {

//        $redis = Redis::connection();

        if (!Redis::get('auth')) {
            $user = auth()->user();
            Redis::set('auth', json_encode($user));
        }

        return json_decode(Redis::get('auth'));
//        return $this->sendResponse('user info', $user);

    }*/

    public function sendForgetCode(array $data){

        $user=User::where('phone',$data['phone'])->first();
        if($user){
            $user->forget_code=123456;
            return $this->sendResponse('we send forget code',$user);
        }
        else{
            return $this->sendError('enter valid phone number');
        }
    }

     public function ForgetCode(array $data){
        $user=User::where('phone',$data['phone'])->first();
        if($data['Forget_code'] == $user->forget_code){
            return response([
                'status'     => true,
                'statusCode' => 200,
                'message'    => 'your Forget code is valid ',
            ]);
        }else{
            return $this->sendError('enter a valid Forget code');
        }

      }

      public function resetPassword(array $data){
          $validator = Validator::make($data, [
              'new_password' => 'required|min:6|confirmed',
              'new_password_confirmation' => 'required',
          ]);
          if ($validator->fails()) {
              return response(['errors' => $validator->errors()->all()], 422);
          }
          //User::where('id', $user_id)->update(['password' => Hash::make($input['new_password'])]);
          $user=User::where('phone', $data['phone'])->update(['password' => bcrypt($data['new_password'])]);
          $message = "Success change password";
          return $this->sendResponse($message, new UserResource($user));
      }
}
