<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Company;
use App\Models\JobSeeker;
use Illuminate\Support\Carbon;

class PostController extends Controller
{
    //This Function is Used To Publish A Post:

    public function create_post(Request $request){
        //Validating Request Information Before Posting
        $request->validate([
            'general_job_title' => ['required','string'],
            'specialization' => ['string'],
            'enrollment_status' => ['required','string'],
            'prefered_gender' => ['required'],
            'prefered_experience' => ['string'],
            'detailed_location' => ['required','string'],
            'requirements' => ['string'],
            'promises' => ['string'],
            'job_information' => ['string'],
            'application_deadline' => ['required','date'],
            'expected_salary' => ['string'],
        ]);

        //Getting Company Information By ID From Database
        $user = auth()->user();
        $company = Company::where('user_id', $user->id)->first();
        $company_name = $company->company_name;
        $company_logo = $company->company_logo;
        $company_location = $company->company_location;

        //Creating Post Object in Database
        $post = Post::create([
            'company_id' => $company->id,
            'company_name' => $company_name,
            'company_logo' => $company_logo,
            'company_location' => $company_location,
            'general_job_title' => $request->general_job_title,
            'specialization' => $request->specialization,
            'enrollment_status' => $request->enrollment_status,
            'prefered_gender' => $request->prefered_gender,
            'prefered_experience' => $request->prefered_experience,
            'detailed_location' => $request->detailed_location,
            'requirements' => $request->requirements,
            'promises' => $request->promises,
            'job_information' => $request->job_information,
            'application_deadline' => $request->application_deadline,
            'expected_salary' => $request->expected_salary,
        ]);

        //Returning Success Message to Indicate That An Object is Created
        return response()->json([
            'status' => "1",
            'message' => "Post Added Successfully!",
        ],201);
    }

    //This Function is Used To Edit Published Post:

    public function edit_post(Request $request) {
        //Validating Request Information Before Editing Post
        $request->validate([
            'post_id' => ['required','exists:posts,id'],
            'general_job_title' => ['required','string'],
            'specialization' => ['string'],
            'enrollment_status' => ['required','string'],
            'prefered_gender' => ['required'],
            'prefered_experience' => ['string'],
            'detailed_location' => ['required','string'],
            'requirements' => ['string'],
            'promises' => ['string'],
            'job_information' => ['string'],
            'application_deadline' => ['required','date'],
            'expected_salary' => ['string'],
            'is_taken' => ['required','boolean']
        ]);

        //Getting Post Object From Database
        $post = Post::where('id',$request->post_id)->first();

        //Editing Post Object in Database
        $post->update([
            'general_job_title' => $request->general_job_title,
            'specialization' => $request->specialization,
            'enrollment_status' => $request->enrollment_status,
            'prefered_gender' => $request->prefered_gender,
            'detailed_location' => $request->detailed_location,
            'requirements' => $request->requirements,
            'promises' => $request->promises,
            'job_information' => $request->job_information,
            'application_deadline' => $request->application_deadline,
            'expected_salary' => $request->expected_salary,
            'is_taken' => $request->is_taken,
        ]);

        //Returning Success Message to Indicate That An Object is Updated
        return response()->json([
            'status' => "1",
            'message' => "Post Updated Successfully!",
        ],201);
    }

    //This Function is Used To Show Details of a Post:

    public function show_post(Request $request) {
        //Validating Post ID
        $request->validate([
            'post_id' => ['required','exists:posts,id'],
        ]);

        //Getting Post Object From Database
        $post = Post::where('id',$request->post_id)->first();

        //Returning Success Message
        return response()->json([
            'status' => "1",
            'message' => "Post Information:",
            'post' => $post,
        ],200);
    }

    //This Function is Used To Delete a Post:

    public function delete_post(Request $request) {
        //Validating Post ID
        $request->validate([
            'post_id' => ['required','exists:posts,id'],
        ]);

        //Getting Post Object From Database
        $post = Post::where('id',$request->post_id) -> first();
        $post -> delete();

        //Returning Success Message
        return response()->json([
            'status' => "1",
            'message' => "Post Deleted Successfully",
        ],200);
    }

    //This Function is Used To Get Few Posts To Show Them in Home:

    public function get_posts() {

        /*
         *
         * Lets Check That All The Posts Didn't Pass the Appplication Deadline and All Of Them Aren't Taken Yet 
         * We Did It Using local Scope (Active) in Post Model 
         * 
        */
        $posts = Post::active()->paginate(5);

        return response()->json([
            'status' => "1",
            'message' => "Posts:",
            'posts' => $posts
        ],200);
    }

    //This Function is Used To Get Few Suggested Posts (From The Same Interests of the User) To Show Them in Home:

    public function get_suggested_posts() {

        $user = auth() -> user();

        $user_jobs = JobSeeker::where('user_id',$user->id)->first()->search_for;

        //First Check That A User Has Added Professions To The Interests

        if($user_jobs[0] == "None") {
            return response()->json([
                'status' => "1",
                'message' => "There Are No Suggested Job Posts Currently",
            ],200);
        }

        /*
         *
         * Lets Check That All The Posts Didn't Pass the Appplication Deadline and All Of Them Aren't Taken Yet 
         * We Did It Using local Scope (Active) in User Model 
         * Get The Posts With The Same Interests As the User And Suggest Them  
        */

        $posts = Post::active()->where(function($query) use ($user_jobs) {
            $query -> whereIn('general_job_title',$user_jobs);
        })->paginate(5);

        return response()->json([
            'status' => "1",
            'message' => "Posts:",
            'suggested_posts' => $posts,
        ],200);
    }
}
