<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function verify(Request $request) {
        $user = User::findOrFail($request->id);
        if($user->hasVerifiedEmail()) {
            return response()->json([
                'User Already Have Verified Email'
            ],422);
        }
        $user->markEmailAsVerified();
        return view("email_verified");
    }

    public function resend(Request $request) {
        $user = $request->user();
        if($user->hasVerifiedEmail()) {
            return response()->json([
                'User Already Have A Verified Email!'
            ],422);
        }
        $user->sendEmailVerificationNotification();
        return response()->json([
            'The Notification Has Been Resubmitted'
        ],200);
    }
}
