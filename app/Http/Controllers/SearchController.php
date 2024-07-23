<?php

namespace App\Http\Controllers;

use App\Models\Filters\JobTitleFilter;
use App\Models\FreelancePost;
use Illuminate\Http\Request;
use App\Models\Post;

class SearchController extends Controller
{
    public function search(Request $request) {

        //Apply Active Local Scope To Filter Search Results To Active Ones

        $posts = Post::search($request->query('search'))->query(function($query) {
            return $query->active();
        })->paginate(5);

        $freelance_posts = FreelancePost::search($request->query('search'))->query(function($query) {
            return $query->active();
        })->paginate(5);

        //Return Successful Response With Post/FreelancePost Results

        return response()->json([
            "status" => "1",
            "posts" => $posts,
            "freelance_posts" => $freelance_posts
        ],200);
    }
    /*
     *   Filtered Search Depends On The Main 3 Filters (JobTitle-Specialization-EnrollmentStatus)
     * 
    */
    public function filtered_search(Request $request) {

        //Validating Request Contents With Required Job Title Filter
        $request->validate([
            "job_title" => ['required','string'],
            "specialization" => ['nullable','string'],
            "enrollment_status" => ['nullable','string'],
        ]);

        //Checking Availability Of Other Filters (Specialization-EnrollmentStatus)
        $job_title = $request->job_title;
        $request->specialization ? $specialization = $request -> specialization : $specialization = null ;
        $request->enrollment_status ? $enrollment_status = $request -> enrollment_status : $enrollment_status = null ;

        //Handling First Case (The User Selected Any As A Job Title => Get 10 + 10 Posts from all Posts)
        if($job_title === "Any") {
            $filtered_posts_result = Post::active()->paginate(10);
            $filtered_freelance_posts_result = FreelancePost::active()->paginate(10);
        }

        //If Both Filters Aren't Choosen => Get 10 + 10 of The Posts Where The First Filter Is Applied
        else if(!$specialization && !$enrollment_status) {

            $filtered_posts_result = Post::active()->filter([
                'job_title_filter' => $request -> job_title,
            ])->paginate(10);

            $filtered_freelance_posts_result = FreelancePost::active()->filter([
                'job_title_filter' => $request -> job_title,
            ])->paginate(10);

        }

        //Job Title + Specialization Filters => get 10 + 10 Where Both Filters Applied
        else if($specialization && !$enrollment_status) {

            $filtered_posts_result = Post::active()->filter([
                'job_title_filter' => $request -> job_title,
                'specialization' => $request -> specialization,
            ])->paginate(10);

           $filtered_freelance_posts_result = FreelancePost::active()->filter([
                'job_title_filter' => $request -> job_title,
                'specialization' => $request -> specialization,
            ])->paginate(10);
        }

        //Job Title + EnrollmentStatus Filters => get 10 + 10 Where Both Filters Applied
        else if(!$specialization && $enrollment_status) {

            $filtered_posts_result = Post::active()->filter([
                'job_title_filter' => $request -> job_title,
                'enrollment_status' => $request -> enrollment_status
            ])->paginate(10);

            $filtered_freelance_posts_result = FreelancePost::active()->filter([
                'job_title_filter' => $request -> job_title,
            ])->paginate(10);

        }

        //Job Title + Specialization + EnrollmentStatus Filters => get 10 + 10 Where All 3 Filters Applied
        else {

            $filtered_posts_result = Post::active()->filter([
                'job_title_filter' => $request -> job_title,
                'specialization' => $request -> specialization,
                'enrollment_status' => $request -> enrollment_status
            ])->paginate(10);

            $filtered_freelance_posts_result = FreelancePost::active()->filter([
                'job_title_filter' => $request -> job_title,
                'specialization' => $request -> specialization,
            ])->paginate(10);

        }

        return response()->json([
            "status" => "1",
            "message" => "Filtered Search Results:",
            "posts" => $filtered_posts_result,
            "freelancing_posts" => $filtered_freelance_posts_result,
        ],200);
    }
}
