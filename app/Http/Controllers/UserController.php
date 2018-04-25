<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function sendPasswordResetMail(Request $request)
    {
        $userModel = new User();
        if (isset($request->mail)) {
            $result = $userModel->where('email', $request->mail);
            if ($result->count() > 0) {
                $user = $result->first();
                $otp = mt_rand(100000, 999999);
                $user->otp = $otp;
                $user->save();
                Mail::to($user)->send(new PasswordReset($otp, $user->name));
                return response()->json(['data' => 'Success'], 200);
            } else {
                return response()->json(['data' => 'Invalid Email'], 406);
            }
        } else {
            return response()->json(['data' => 'Incomplete data'], 406);
        }
    }

    public function resetPassword(Request $request)
    {
        $userModel = new User();
        if (!empty($request->email) AND !empty($request->otp) AND !empty($request->password)) {
            $result = $userModel->where('email', $request->email);
            if ($result->count() > 0) {
                $user = $result->first();
                if ($request->otp == $user->otp) {
                    $user->password = Hash::make($request->password);
                    $user->api_token = null;
                    $user->save();
                    return response()->json(['data' => 'Success'], 200);
                } else {
                    return response()->json(['data' => 'Wrong OTP'], 400);
                }
            } else {
                return response()->json(['data' => 'Invalid Email'], 406);
            }
        } else {
            return response()->json(['data' => 'Incomplete data'], 406);
        }
    }
}
