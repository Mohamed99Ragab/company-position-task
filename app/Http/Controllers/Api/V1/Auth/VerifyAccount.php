<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\Otp;
use App\Models\User;
use App\services\TwilioService;
use Illuminate\Http\Request;

class VerifyAccount extends Controller
{

    public function createOtp($phone_number)
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Otp::updateOrCreate(['phone_number'=>$phone_number],[
            'code'=>$code
        ]);

        // Use the Twilio service to send the OTP
//        $twilioService = new TwilioService();
//        $response = $twilioService->sendOTP($phone_number, $code);

        return $this->successResponse('Verification Code sent successfully!',[
            'code' => $code,
        ]);
    }

    public function verifyAccount(VerifyRequest $request)
    {
        $user = User::where('phone_number',$request->phone_number)->first();

        $otp = Otp::where('phone_number',$request->phone_number)
                    ->where('code',$request->code)->first();
        if ($otp){
            $user->update([
                'is_verified'=>1
            ]);
            $otp->delete();

            return $this->successResponse('account is verified successfully');
        }

        return $this->errorResponse('otp not correct');



    }


    public function resendOtp(ResendOtpRequest $request)
    {
       return $this->createOtp($request->phone_number);

    }
}
