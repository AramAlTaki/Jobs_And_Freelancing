<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\FreelancePostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\RefreshTokenMiddleware;
use Illuminate\Support\Facades\Redis;

                    //Register And Login Endpoints (Web/Mobile)
Route::post('/register',[RegisterController::class,'register']);
Route::post('/login',[LoginController::class,'login']);

    /**
     *              (( EmailVerification Controller Methods (Web/Mobile) ))
     * 
     *            verify => Check if The User Has Verified Email or Not (Web/Mobile)
     *          resend => Resend Email Verification Message To User's Email (Web/Mobile)
     * 
     */
Route::get('email/verify/{id}',[EmailVerificationController::class,'verify'])->name("verification.verify")->middleware("signed");
Route::get('email/resend',[EmailVerificationController::class,'resend'])->name("verification.resend")->middleware("throttle:6,1");
    

Route::middleware('auth:api')->group(function() {

                    //Information Controller Methods (Web/Mobile)
    Route::post('/information/user',[InformationController::class,'get_job_seeker_information']);
    Route::post('/information/company',[InformationController::class,'get_company_information']);
    Route::post('/information/freelancing_owner',[InformationController::class,'get_freelancing_owner_information']);

                    //Company Post Controller Methods (Web)
    Route::post('posts/create',[PostController::class,'create_post']);
    Route::put('posts/edit',[PostController::class,'edit_post']);
    Route::delete('posts/delete',[PostController::class,'delete_post']);

                    //Company Post Controller Methods (Mobile)
    Route::get('posts/index',[PostController::class,'get_posts']);
    Route::get('posts/suggested',[PostController::class,'get_suggested_posts']);
    Route::get('posts/details',[PostController::class,'show_post']);

                    //Freelancing Post Controller Methods (Web)
    Route::post('freelancing_posts/create',[FreelancePostController::class,'create_freelance_post']);
    Route::put('freelancing_posts/edit',[FreelancePostController::class,'edit_freelance_post']);
    Route::delete('freelancing_posts/delete',[FreelancePostController::class,'delete_freelance_post']);

                    //Freelance Post Controller Methods (Mobile)
    Route::get('freelancing_posts/index',[FreelancePostController::class,'get_freelance_posts']);
    Route::get('freelancing_posts/suggested',[FreelancePostController::class,'get_suggested_freelance_posts']);
    Route::get('freelancing_posts/details',[FreelancePostController::class,'show_freelance_post']);

    /**
     *                 (( Chat Controller Methods (Web/Mobile) ))
     * 
     *                 Store => Store Chat in Database (Web/Mobile)
     *                  Index => Show User All Chats (Web/Mobile)
     *             Show => Show Last Message For a Chat with a User (Web/Mobile)
     * 
     */
    Route::apiResource('chat',ChatController::class)->only(['store','index','show']);

    /**
     *                 (( Message Controller Methods (Web/Mobile) ))
     * 
     *                 Store => Store Message in Database (Web/Mobile)
     *             Index => Show Collection of Messages With a User (Web/Mobile)
     *            Show => Show All Users Except The Authenticated User (Web/Mobile)
     * 
     */
    Route::apiResource('message',MessageController::class)->only(['store','index','show']);

    
    /**
     *                 (( Wishlist Controller Methods (Mobile) ))
     * 
     *                     Get => Get 10 Posts from Wishlist (Mobile)
     *                   Add_Post => Add Post To User Wishlist (Mobile)
     *           Add_Freelancing_Post => Add Freelancing Post To User Wishlist (Mobile)
     *              Remove_Post => Remove Post From User Wishlist (Mobile)
     *        Remove_Freelancing_Post => Remove Freelancing Post From User Wishlist (Mobile)
     */
    Route::get('wishlist/get_posts',[WishlistController::class,'get_posts']);
    Route::post('wishlist/add_post',[WishlistController::class,'add_post_to_wishlist']);
    Route::post('wishlist/add_freelance_post',[WishlistController::class,'add_freelance_post_to_wishlist']);
    Route::delete('wishlist/remove_post',[WishlistController::class,'remove_post_from_wishlist']);
    Route::delete('wishlist/remove_freelance_post',[WishlistController::class,'remove_freelance_post_from_wishlist']);

    /**
     *                 (( Search Controller Methods (Mobile) ))
     * 
     *         Search => Search Job Posts By Job Title And Specialization And Enrollment Status With One String (Mobile)
     *         Filtered Search => Search Job Posts By Job Title And Specialization And Enrollment Status Filters (Mobile)
     * 
     */
    Route::get('search',[SearchController::class,'search']);
    Route::get('filtered_search',[SearchController::class,'filtered_search']);

    /**
     *                 (( Profile Controller Methods (Mobile/Web) ))
     * 
     *                    Update => Edit Job Seeker Profile (Mobile)
     *                 Show => Show Job Seeker Profile For Himself (Mobile)
     *           User => Show Job Seeker Profile For Companies And Job Owners (Web)
     *             Delete Avatar => Remove Profile Photo For Job Seeker (Mobile)
     *                  Delete CV => Remove CV For Job Seeker (Mobile)
     * 
     */
    Route::post('profile/update',[ProfileController::class,'edit_job_seeker_profile']);
    Route::get('profile/show',[ProfileController::class,'show_job_seeker_profile']);
    Route::get('profile/user',[ProfileController::class,'show_job_seeker_profile_for_web']);
    Route::get('profile/avatar_delete',[ProfileController::class,'delete_profile_picture']);
    Route::put('profile/cv_delete',[ProfileController::class,'delete_cv']);

        /**
     *                 (( Application Controller Methods (Mobile/Web) ))
     * 
     *                    Create => New Post Application Submitted (Mobile)
     *            Create_Freelance => New Freelance Post Application Submitted (Mobile)
     *                    CV_Download => Download CV For Job Seeker (Web)
     *                  Get => Get All Applications For A Job Seeker (Mobile)
     *                    Get_Post => Get All Applications For A Post (Web)
     *                       Accept => Accept Application For A Post (Web)
     *                       Reject => Reject Application For A Post (Web)
     * 
     */
    Route::post('applications/create',[ApplicationController::class,'create_post_application']);
    Route::post('applications/create_freelance',[ApplicationController::class,'create_freelance_post_application']);
    Route::get('cv/download',[ApplicationController::class,'download_cv']);
    Route::get('applications/get',[ApplicationController::class,'get_applications_for_job_seeker']);
    Route::get('applications/get_post',[ApplicationController::class,'get_applications_for_post']);
    Route::get('applications/get_freelance_post',[ApplicationController::class,'get_applications_for_freelance_post']);
    Route::put('applications/accept',[ApplicationController::class,'accept_application']);
    Route::put('applications/reject',[ApplicationController::class,'reject_application']);

});
