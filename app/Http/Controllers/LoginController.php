<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class LoginController extends Controller
{
    public function login(Request $request){

        //Defining Validation Rules
        $rules = [
            'email'=>['required','email','exists:users'],
            'password'=>['required'],
        ];

        //Defining Error Messages
        $messages = [
            'email.exists' => 'The Email Address Does\'nt Exist',
            'email.required' => 'The Email Field is Required',
            'email.email' => 'The Email Field must Be a Valid Email Address',
            'password.required' => 'The Password Field is Required',
        ];

        //Creating A Validator Instance With Rules and Error Messages
        $validator = Validator::make($request->all(),$rules,$messages);

        //Checking Validation Correctness :
        if($validator->fails()) {
            return response()->json([
                'status' => "0",
                'errors' => $validator->errors(),
            ],422);
        }

        //Checking User Information
        if(Auth::attempt([
            'email' => $request -> email,
            'password' => $request -> password
        ])){
            $request = Request::create('oauth/token', 'POST', [
                'grant_type' => 'password',
                'client_id' => env("CLIENT_ID"),
                'client_secret' => env("CLIENT_SECRET"),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]);

            $user = Auth::user();
            if($user -> tokens -> count() >= 3 ) {
                $tokenRepository = app(TokenRepository::class);
                $refreshTokenRepository = app(RefreshTokenRepository::class);

                $non_revoked_tokens = $user -> tokens -> reject(function ($token) {
                    return $token -> revoked;
                });
                $oldest_token = $non_revoked_tokens -> sortBy('created_at') -> first();

                $tokenRepository -> revokeAccessToken($oldest_token->id);
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($oldest_token->id);
            }
            
            $result = app()->handle($request);
            $response = json_decode($result->getContent(), true);
            return response()->json([
                'status' => "1",
                'user_id' => (string) auth()->user()->id,
                'data' => $response,
            ],200);
        }
        else{
            return response()->json([
                'state' => 0,
                'errors' => 'Incorrect Information!'
            ],422);
        }
    }
}
