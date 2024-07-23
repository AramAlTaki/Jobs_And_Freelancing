<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FreelancePost;
use App\Models\JobSeeker;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    public function create_post_application(Request $request) {

        $request->validate([
            "post_id" => ["required","exists:posts,id"]
        ]);

        $auth_user = auth()->user();

        $job_seeker = JobSeeker::where("user_id",$auth_user->id)->first();

        $user = User::where("id",$auth_user->id)->first();

        $post = Post::where("id",$request->post_id)->first();

        $cv_url = isset($job_seeker->cv) ? $cv_url = "http://127.0.0.1:8000/cv/download/?key=" . $job_seeker->cv : $cv_url = null;

        $user_full_name = ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);

        Application::create([
            "job_seeker_id" => $job_seeker -> id,
            "user_full_name" => $user_full_name,
            "model_type" => get_class($post),
            "model_id" => $post -> id,
            "job_title" => $post->specialization,
            "cv_url" => $cv_url,
        ]);

        return response()->json([
            "status" => "1",
            "message" => "Job Application Has Been Submitted"
        ],200);
    }

    public function accept_application(Request $request) {

        $request->validate([
            "application_id" => ['required','exists:applications,id']
        ]);

        $application = Application::where('id',$request->application_id)->first();

        if($application->status === "Pending") {
            $application->update([
                "status" => "Accepted"
            ]);

            return response()->json([
                "status" => "1",
                "message" => "Application Accepted Successfully"
            ],200);
        }
        else {
            return response()->json([
                "status" => "0",
                "message" => "Not Possible To Change Status of Accepted And Rejected Applications"
            ],422);
        }
    }

    public function reject_application(Request $request) {

        $request->validate([
            "application_id" => ['required','exists:applications,id']
        ]);

        $application = Application::where('id',$request->application_id)->first();

        if($application->status === "Pending") {
            $application->update([
                "status" => "Rejected"
            ]);

            return response()->json([
                "status" => "1",
                "message" => "Application Rejected Successfully",
            ],200);
        }
        else {
            return response()->json([
                "status" => "0",
                "message" => "Not Possible To Change Status of Accepted And Rejected Applications"
            ],422);
        }
    }

    public function create_freelance_post_application(Request $request) {

        $request->validate([
            "freelance_post_id" => ["required","exists:freelance_posts,id"]
        ]);

        $auth_user = auth()->user();

        $job_seeker = JobSeeker::where("user_id",$auth_user->id)->first();

        $user = User::where("id",$auth_user->id)->first();

        $post = FreelancePost::where("id",$request->freelance_post_id)->first();

        $cv_url = isset($job_seeker->cv) ? $cv_url = "http://127.0.0.1:8000/cv/download/?key=" . $job_seeker->cv : $cv_url = null;

        $user_full_name = ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);

        Application::create([
            "job_seeker_id" => $job_seeker -> id,
            "user_full_name" => $user_full_name,
            "model_type" => get_class($post),
            "model_id" => $post -> id,
            "job_title" => $post -> specialization,
            "cv_url" => $cv_url,
        ]);

        return response()->json([
            "status" => "1",
            "message" => "Job Application Has Been Submitted"
        ],200);
    }

    public function download_cv() {
        
       $file_path = storage_path("app\\CVs\\" . request('key'));

        if(file_exists($file_path)) {

            return response()->download($file_path);
        }
        else {
           return response()->json([
               "status" => "0",
               "message" => "File Not Found",
               "path" => $file_path
           ],404);
        }
    }

    public function get_applications_for_job_seeker() {

        $user = auth()->user();

        $job_seeker = JobSeeker::where("user_id",$user->id)->first();

        $applications = Application::where("job_seeker_id",$job_seeker -> id)->get();

        return response()->json([
            "status" => "1",
            "message" => "Applications For User:",
            "applications" => $applications
        ],200);
    }

    public function get_applications_for_post(Request $request) {

        $request->validate([
            "post_id" => ["required","exists:posts,id"]
        ]);

        $post = Post::where("id",$request->post_id)->first();

        $applications = Application::where("model_type",get_class($post))->where("model_id",$post->id)->get();

        return response()->json([
            "status" => "1",
            "message" => "Applications For Post ",
            "applications" => $applications
        ],200);
    }

    public function get_applications_for_freelance_post(Request $request) {

        $request->validate([
            "freelance_post_id" => ["required","exists:freelance_posts,id"]
        ]);

        $post = FreelancePost::where("id",$request->freelance_post_id)->first();

        $applications = Application::where("model_id",$post->id)->where("model_type",get_class($post))->get();

        return response()->json([
            "status" => "1",
            "message" => "Applications For Post ",
            "applications" => $applications
        ],200);
    }

}
