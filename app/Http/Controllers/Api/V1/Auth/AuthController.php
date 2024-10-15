<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'is_verified'=>false
        ]);


        $token = $user->createToken($user->phone_number.'-AuthToken')->plainTextToken;

        return $this->successResponse('User Created Successfully',[
            'access_token' => $token,
            'user'=>$user
        ]);

    }

    public function login(LoginRequest $request){

        $user = User::where('phone_number',$request->phone_number)->first();

        if(!auth()->attempt($request->validated())){
            return  $this->errorResponse('Invalid Credentials',401);

        }

        if ($user->is_verified){
            $token = $user->createToken($user->phone_number.'-AuthToken')->plainTextToken;
           return $this->successResponse('login success',[
               'access_token' => $token,
               'user'=>$user
           ]);
        }
        // send otp to verify user account
        return  (new VerifyAccount)->createOtp($user->phone_number);





    }


    public function logout()
    {
        $user = auth()->user();

        $user->tokens()->delete();
        return $this->successResponse('Successfully logged out');
    }



}
