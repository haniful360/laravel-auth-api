<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'tc' => 'required'
        ]);

        $user = new User;
        if($user::where('email', $request->email)->first()){
          return response()->json([
             'message' => 'Email already exits',
             'status' => 'failed'
          ],200);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->tc = json_decode($request->tc);
        $user->remember_token = Str::random(40);
        $user->save();


        Mail::to($user->email)->send(new EmailVerification($user));

        return response()->json([

            'message' => 'Registration Success',
            'status' => 'success'
         ],201);

    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('email',$request->email)->first();

        if($user && Hash::check($request->password, $user->password)){

            $token = $user->createToken("authToken")->plainTextToken;
            return response()->json([
                'token' => $token,
                'message' => 'Login Success',
                'status' => 'success'
             ],201);
        }

        return response()->json([
            'message' => 'Provided Credentials are incorrect',
            'status' => 'failed'
         ],200);
    }

    // public function sendVerifyEmail(Request $request){

    //     $user = User::where('remember_token', $request->token)->first();

    //     if(!$user){
    //         return response()->json([
    //             'message' =>"Invalid token",
    //             'status' => 'failed'
    //         ],200);
    //     }

    //     $user->email_verified_at = Carbon::now();
    //     $user->remember_token = null;
    //     $user->save();
    // }


    public function logout(Request $request)
    {
    $user = $request->user();
    if ($user) {
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logout Success',
            'status' => 'success'
        ], 200);
    }

    }

    public function loggedUser(){

        $loggedUser = auth()->user();

        return response()->json([
            'user' => $loggedUser,
            'message' => 'Loged user data',
            'status' => 'success'
        ], 200);
    }


    public function change_password(Request $request) {

        $request->validate([
            'password' => 'required|confirmed'
        ]);

        $loggedUser = $request->user();
        $loggedUser->password =  Hash::make($request->password);
        $loggedUser->save();

        return response()->json([
            'message' => 'Password changed successfully',
            'status' => 'success'
        ], 200);

    }



}
