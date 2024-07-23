<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobSeeker;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    //This Function is Used To Edit Job Seeker Profile:

    public function edit_job_seeker_profile(Request $request){

        //Validating Request Information Before Editing Profile
        $request->validate([
            'first_name'=> ['required','max:20'],
            'last_name'=> ['required','max:20'],
            'profile_photo' => ['file','image','mimes:jpg,png,jpeg','max:6000'],
            'date_of_birth' => ['required','date'],
            'city' => ['required'],
            'search_for' => ['required','array'],
            'additional_information' => ['string'],
            'cv' => ['file','mimes:pdf,doc,docx','max:10000'],
        ]);

        //Getting User ID
        $auth_user = auth() -> user();
        $user_id = $auth_user -> id;

        //Getting Job Seeker ID From User ID
        $job_seeker_id = JobSeeker::where('user_id',$user_id) -> first();
        $user = User::where('id',$user_id)->first();
        $profile_photo = null;
        $cv = null;

        //if The Request Contain Profile_Photo => Encode Profile_Photo Name And Store it in Public Disk
        if($request->hasfile('profile_photo')) {
            $profile_photo = $request->profile_photo;
            $profile_photo = Storage::disk('public')->putFileAs('/profile_photos', $profile_photo, str()->uuid() . '.' . $profile_photo->extension());
        }

        //if The Request Dosen't Contain Profile_Photo => Select One Of The Default Profile_Photos Depending on User's Gender
        else {
            if ($request->gender == "MALE") {
                $profile_photo = public_path('defaults/male_default.png');
            } else if ($request->gender == "FEMALE") {
                $profile_photo = public_path('defaults/female_default.png');
            }   
        }    
                
        //if The Request Contain CV File => Encode CV File Name And Store it in Local Disk
        if($request->hasfile('cv')) {
            $cv = $request->file('cv');
            $cv = Storage::disk('local')->putFileAs('/CVs',$cv,str()->uuid() . '.' . $cv->extension());
        }

        //Editing Profile Information
        $user -> update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        $job_seeker_id -> update([
            'profile_photo' => $profile_photo,
            'date_of_birth' => $request->date_of_birth,
            'city' => $request->city,
            'search_for' => $request->search_for,
            'additional_information' => $request->additional_information,
            'cv' => $cv,
        ]);

        //Returning Success Message to Indicate That A Profile is Updated
        return response()->json([
            'status' => "1",
            'message' => "Profile Updated Successfully!",
        ],200);
    }

    //This Function Shows Job Seeker Information For The Job Seeker

    public function show_job_seeker_profile()  {

        //Getting User Object From Database
        $user = Auth() -> user();

        //Getting User ID
        $user_id = $user -> id;

        //Getting General User Information
        $general_user_information = [
            'full_name' => ucfirst($user->first_name) . ' ' . ucfirst($user->last_name),
            'email' => $user->email
        ];

        //Getting Job Seeker Object
        $job_seeker = JobSeeker::where('user_id',$user_id) -> first();

        //Getting Detailed User Information
        $detailed_user_information = [
            'profile_photo' => $job_seeker->profile_photo,
            'date_of_birth' => $job_seeker->date_of_birth,
            'city' => $job_seeker->city,
            'search_for' => $job_seeker->search_for,
            'additional_information' => $job_seeker->additional_information,
            'cv' => $job_seeker->cv,
        ];

        //Merging 2 Arrays Together
        $result = array_merge($general_user_information,$detailed_user_information);

        //Returning Success Message
        return response()->json([
            'status' => "1",
            'message' => "Profile Information:",
            'profile_information' => $result,
        ],200);
    }

    //This Function Gets A Profile For A User 
    public function show_job_seeker_profile_for_web(Request $request)  {

        //Validating Request Information Before Editing Profile
        $request->validate([
            "user_id" => ['required','exists:users,id']
        ]);

        //Getting General User Information
        $user = User::where('id',$request->user_id)->first();

        //Getting General User Information
        $general_user_information = [
            'full_name' => ucfirst($user->first_name) . ' ' . ucfirst($user->last_name),
            'email' => $user->email
        ];


        //Getting Job Seeker Object
        $job_seeker = JobSeeker::where('user_id',$user->id) -> first();

        //Getting Detailed User Information
        $detailed_user_information = [
            'profile_photo' => $job_seeker->profile_photo,
            'date_of_birth' => $job_seeker->date_of_birth,
            'city' => $job_seeker->city,
            'search_for' => $job_seeker->search_for,
            'additional_information' => $job_seeker->additional_information,
        ];

        //Merging 2 Arrays Together
        $result = array_merge($general_user_information,$detailed_user_information);

        //Returning Success Message
        return response()->json([
            'status' => "1",
            'message' => "Profile Information:",
            'profile_information' => $result,
        ],200);
    }

    public function delete_profile_picture() {
        
        $user = auth()->user()->id;
        $job_seeker = JobSeeker::where("user_id",$user)->first();
        $profile_photo = $job_seeker -> profile_photo;

        if ($job_seeker->gender == "MALE") {
            $profile_photo = public_path('defaults/male_default.png');
        } else if ($job_seeker->gender == "FEMALE") {
            $profile_photo = public_path('defaults/female_default.png');
        }

        $job_seeker -> update([
            "profile_photo" => $profile_photo
        ]);

        return response()->json([
            "status" => "1",
            "message" => "Profile Photo Has Been Deleted!"
        ],200);

    }

    public function delete_cv() {
        
        $user = auth()->user()->id;
        $job_seeker = JobSeeker::where("user_id",$user)->first();
        $cv = $job_seeker -> cv;

        if($cv === null) {
            return response()->json([
                "status" => "0",
                "message" => "CV is not Uploaded To Be Deleted"
            ],400);
        }

        $job_seeker-> update([
            "cv" => null
        ]);

        return response()->json([
            "status" => "1",
            "message" => "CV Has Been Deleted!"
        ],200);
    }
}
