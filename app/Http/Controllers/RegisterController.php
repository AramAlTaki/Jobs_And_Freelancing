<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class RegisterController extends Controller{

    public function register(Request $request){

        //Defining Validation Rules
        $rules = [
            'first_name'=> ['required','max:20'],
            'last_name'=> ['required','max:20'],
            'email'=>['required','email','unique:users'],
            'password'=>['required','confirmed',Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
            'role'=>['required']
        ];

        //Defining Error Messages
        $messages = [
            'first_name.required' => 'The First Name Field is Required',
            'first_name.max' => 'The First Name Shouldn\'t Be more than 20 Characters',
            'last_name.required' => 'The Last Name Field is Required',
            'last_name.max' => 'The Last Name Shouldn\'t Be more than 20 Characters',
            'email.required' => 'The Email Field is Required',
            'email.email' => 'The Email Field must Be a Valid Email Address',
            'email.unique' => 'The Email Address Already Exists',
            'password.required' => 'The Password Field is Required',
            'password.confirmed' => 'The Password Confirmation doesn\'t Match The Password',
            'password.min' => 'The Password must Contain at Least 8 Characters',
            'password.letters' => 'The Password must Contain at Least one Letter',
            'password.numbers' => 'The Password must Contain at Least one Number',
            'password.mixedCase' => 'The Password must Contain at Least one Small Letter and one Capital Letter',
            'password.symbols' => 'The Password must Contain at Least one Symbol',
            'role.required' => "The Role Field is Required"
        ];

        //Creating A Validator Instance With Rules and Error Messages
        $validator = Validator::make($request->all(),$rules,$messages);

        /*
         *          Checking Validation Correctness :
         *
         * if false => return JSON Response with The Error Message
         * if true => Create User Entity in Database And return JSON Response With The User Information
        */

        if($validator->fails()) {
            return response()->json([
                'status' => "0",
                'errors' => $validator->errors(),
            ],422);
        }

        //Validation of Role
        if($request->role != "USER" && $request->role != "COMPANY" && $request->role != "FREELANCING_OWNER") {
            return response()->json([
                'state' => 0,
                'errors' => "Role is Invalid"
            ],422);
        }

        //Storing User Information In Database
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        //Display Registered Event With listener sending Email Verification Message To User
        //event(new Registered($user));

        return response()->json([
            'status' => "1",
            'message' => 'Information Stored Successfully Please Verify Your Email',
            'user_id' => (string) $user->id
        ],201);
    }
}

