<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
    public function send_password_reset(Request $request) {
        $request->validate([
            'email' => 'required'
        ]);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        if(!$user){
            return response()->json([
                'message' => 'Email doesnt Exist',
                'status' =>'failed'
            ],404);
        }

        // generate Token
        $token = Str::random(60);

        $data = new PasswordReset();
        $data->email = $request->email;
        $data->token =  $token;
        $data->created_at = Carbon::now();
        $data->save();

        // dump("http://127.0.0.1:3000/api/reset/" . $token);

        Mail::send('reset', ['token' => $token], function (Message $message) use ($email) {
            $message->subject('Reset your password');
            $message->to($email);
        });


        return response()->json([
            'message' => 'Password reset email sent...check your email',
            'status' => 'success'
        ], 200);



    }

    public function reset(Request $request, $token){

        $request->validate([
            'password' => 'required|confirmed'
        ]);

        $passwordreset = PasswordReset::where('token', $token)->first();

        if(!$passwordreset){
            return response()->json([
                'message'=>'Token is invalid'
            ],400);
        }

        $user = User::where('email', $passwordreset->email)->first();

        $user->password= Hash::make($request->password);
        $user->save();

        PasswordReset::where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password reset success',
            'status' => 'success'
        ], 200);

    }

}
