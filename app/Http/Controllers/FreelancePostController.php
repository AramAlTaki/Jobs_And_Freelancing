<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\FreelancingOwner;
use App\Models\JobSeeker;
use App\Models\FreelancePost;

class FreelancePostController extends Controller
{
    //This Function is Used To Publish A Freelance Post:

    public function create_freelance_post(Request $request){

        //Validating Request Information Before Posting
        $request->validate([
            'general_job_title' => ['required','string'],
            'specialization' => ['string'],
            'earnings' => ['string'],
            'job_information' => ['string'],
            'requirements' => ['string'],
            'application_deadline' => ['required','date'],
        ]);

        //Getting Freelancer Information By ID From Database
        $user = auth()->user();
        $freelancer = FreelancingOwner::where('user_id', $user->id)->first();
        $profile_photo = $freelancer->profile_photo;
        $phone_number = $freelancer->phone_number;
        $location = $freelancer->location;

        //Creating Post Object in Database
        $freelance_post = FreelancePost::create([
            'freelancer_id' => $freelancer->id,
            'profile_photo' => $profile_photo,
            'phone_number' => $phone_number,
            'location' => $location,
            'general_job_title' => $request->general_job_title,
            'specialization' => $request->specialization,
            'earnings' => $request->earnings,
            'job_information' => $request->job_information,
            'requirements' => $request->requirements,
            'application_deadline' => $request->application_deadline,
        ]);

        //Returning Success Message to Indicate That An Object is Created
        return response()->json([
            'status' => "1",
            'message' => "Post Added Successfully!",
        ],201);
    }

    //This Function is Used To Edit Published Freelancing Post:

    public function edit_freelance_post(Request $request)
    {
        //Validating Request Information Before Editing Freelance Post
        $request->validate([
            'freelance_post_id' => ['required','exists:freelance_posts,id'],
            'general_job_title' => ['required','string'],
            'specialization' => ['string'],
            'earnings' => ['string'],
            'job_information' => ['string'],
            'requirements' => ['string'],
            'application_deadline' => ['required','date'],
            'is_taken' => ['required','boolean'],
        ]);

        //Getting Freelance Post Object From Database
        $freelance_post = FreelancePost::where('id',$request->freelance_post_id)->first();

        //Editing Freelance Post Object in Database
        $freelance_post->update([
            'general_job_title' => $request->general_job_title,
            'specialization' => $request->specialization,
            'earnings' => $request->earnings,
            'job_information' => $request->job_information,
            'requirements' => $request->requirements,
            'application_deadline' => $request->application_deadline,
            'is_taken' => $request->is_taken,
        ]);

        //Returning Success Message to Indicate That An Object is Updated
        return response()->json([
            'status' => "1",
            'message' => "Post Updated Successfully!",
        ],200);

    }

    //This Function is Used To Show Details of a Freelance Post:

    public function show_freelance_post(Request $request)
    {
        //Validating Freelance Post ID
        $request->validate([
            'freelance_post_id' => ['required','exists:freelance_posts,id'],
        ]);

        //Getting Freelance Post Object From Database
        $freelance_post = FreelancePost::where('id',$request->freelance_post_id)->first();

        //Returning Success Message
        return response()->json([
            'status' => "1",
            'message' => " Freelance Post Information:",
            'freelance_post' => $freelance_post,
        ],200);
    }

    //This Function is Used To Delete a Freelance Post:

    public function delete_freelance_post(Request $request)
    {
        //Validating Freelance Post ID
        $request->validate([
            'freelance_post_id' => ['required','exists:freelance_posts,id'],
        ]);

        //Getting Freelance Post Object From Database
        $freelance_post = FreelancePost::where('id',$request->freelance_post_id) -> first();
        $freelance_post -> delete();

        //Returning Success Message
        return response()->json([
            'status' => "1",
            'message' => "Freelance Post Deleted Successfully",
        ],200);
    }

    //This Function Is Used To Get Few Freelance Posts And Show Them In Home

    public function get_freelance_posts() {

        /*
         *
         * Lets Check That All The Posts Didn't Pass the Appplication Deadline and All Of Them Aren't Taken Yet 
         * We Did It Using local Scope (Active) in User Model 
         * 
        */
        $posts = FreelancePost::active()->paginate(5);

        return response()->json([
            'status' => "1",
            'message' => "Posts:",
            'posts' => $posts
        ],200);
    }

    //This Function is Used To Suggest Few Freelance Posts For User And Show Them in Home
    
    public function get_suggested_freelance_posts() {

        $user = auth() -> user();

        $user_jobs = JobSeeker::where('user_id',$user->id)->first()->search_for;

        //First Check That A User Has Added Professions To The Interests

        if($user_jobs[0] == "None") {
            return response()->json([
                'status' => "1",
                'message' => "There Are No Suggested Freelance Job Posts Currently",
            ],200);
        }

        /*
         *
         * Lets Check That All The Posts Didn't Pass the Appplication Deadline and All Of Them Aren't Taken Yet 
         * We Did It Using local Scope (Active) in User Model 
         * Get The Posts With The Same Interests As the User And Suggest Them 
        */
        $posts = FreelancePost::active()->where(function($query) use ($user_jobs) {
            $query -> whereIn('general_job_title',$user_jobs);
        })->paginate(5);

        return response()->json([
            'status' => "1",
            'message' => "Posts:",
            'suggested_posts' => $posts,
        ],200);
    }
}
