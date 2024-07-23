<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobSeeker;
use App\Models\Company;
use App\Models\FreelancingOwner;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    public function get_job_seeker_information(Request $request){

        $user_id = auth()->user()->id;

        //Validating User's Information
        $request->validate([
            'profile_photo' => ['file','image','mimes:jpg,png,jpeg','max:6000'],
            'gender' => ['required'],
            'date_of_birth' => ['required','date'],
            'city' => ['required'],
            'languages' => ['string'],
            'search_for' => ['required','array'],
            'additional_information' => ['string'],
            'cv' => ['file','mimes:pdf,doc,docx','max:10000'],
        ]);

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

        //Creating An Object In The JobSeeker Database
        $job_seeker = JobSeeker::create([
            'user_id' => $user_id,
            'profile_photo' => $profile_photo ,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'city' => $request->city,
            'languages' => isset($request->languages) ? $request->languages : null,
            'search_for' => $request->search_for,
            'additional_information' => isset($request->additional_information) ? $request->additional_information : null,
            'cv' => isset($cv) ? $cv : null,
        ]);

        return response()->json([
            'status' => "1",
            'message' => "Information Stored Successfully",
        ],201);
    }

    public function get_company_information(Request $request) {

        $user_id = auth()->user()->id;

        //Validating Company's Information
        $request->validate([
            'company_name' => ['required'],
            'company_email' => ['required','email','unique:companies'],
            'company_website' => ['unique:companies'],
            'company_logo' => ['file','image','mimes:jpg,png,jpeg','max:6000'],
            'company_location' => ['required'],
            'company_industry' => ['required'],
            'company_size' => ['string'],
            'description' => ['string'],
            'founding_year' => ['required'],
            'social_media_links' => ['required','array'],
        ]);

        //if The Request Contain Company_Logo => Encode Company_Logo Name And Store it in Public Disk
        if($request->hasfile('company_logo')) {
            $company_logo = $request->company_logo;
            $company_logo = Storage::disk('public')->putFileAs('/company_logos', $company_logo, str()->uuid() . '.' . $company_logo->extension());
        }

        //if The Request Dosen't Contain Company_Logo => Select The Default Company_Logo
        else {
            $company_logo = public_path('defaults/company_default.png');
        }

        //Creating An Object In The Company Database
        $company = Company::create([
            'user_id' => $user_id,
            'company_name' => $request->company_name,
            'company_email' => $request->company_email ,
            'company_website' => isset($request->company_website) ? $request->company_website : null,
            'company_logo' => $company_logo,
            'company_location' => $request->company_location,
            'company_industry' => $request->company_industry,
            'company_size' => isset($request->company_size) ? $request->company_size : null,
            'description' => isset($request->description) ? $request->description : null,
            'founding_year' =>  $request->founding_year,
            'social_media_links' => $request->social_media_links,
        ]);

        return response()->json([
            'status' => "1",
            'message' => "Information Stored Successfully",
        ],201);
    }

    public function get_freelancing_owner_information(Request $request) {

        $user_id = auth()->user()->id;

        //Validating Freelancing Owner's Information
        $request->validate([
            'profile_photo' => ['file','image','mimes:jpg,png,jpeg','max:6000'],
            'location' => ['required'],
            'gender' => ['required'],
            'phone_number' => ['string','max:10','min:10'],
            'bio' => ['string'],
            'languages' => ['string'],
        ]);

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

        //Creating An Object In The Freelancing Owner Database
        $freelancing_owner = FreelancingOwner::create([
            'user_id' => $user_id,
            'profile_photo' => $profile_photo,
            'location' => isset($request->location) ? $request->location : null,
            'gender' => $request->gender,
            'phone_number' => isset($request->phone_number) ? $request->phone_number : null,
            'bio' => isset($request->bio) ? $request->bio : null,
            'languages' => isset($request->languages) ? $request->languages : null,
        ]);

        return response()->json([
            'status' => "1",
            'message' => "Information Stored Successfully",
        ],201);
    }
}
