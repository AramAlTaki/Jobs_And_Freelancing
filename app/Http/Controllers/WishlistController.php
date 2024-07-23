<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FreelancePost;
use App\Models\Post;
use Exception;

class WishlistController extends Controller
{
    
    /**
     * Get 10 wishes for the user.
     */
    public function get_posts() {

        $user = User::find(auth()->user()->id);

        try {
            $wihslist_items = $user -> wishlists();

            if($wihslist_items === null) {
                return response()->json([
                    "status" => "0",
                    "wishlist_posts" => "The Wishlist is Empty"
                ],200);
            }

            else if($wihslist_items !== null) {
                return response()->json([
                    "status" => "1",
                    "wishlist_posts" => $wihslist_items
                ],200);
            }
        }
        catch(Exception $e) {
            return response()->json([
                "status" => "0",
                "message" => "Something Went Wrong!"
            ],400);
        }
    }

    /**
     * Add Post to User Wishlist.
     */
    public function add_post_to_wishlist(Request $request) {

        $request->validate([
            'post_id' => ['required','exists:posts,id']
        ]);

        $post = Post::where("id",$request->post_id)->first();
        $user = User::where("id",auth()->user()->id)->first();

        $wishlisted_post = $user -> wish($post);

        if($post === null) {
            return response()->json([
                "status" => "0",
                "message" => "Post is Already Added To Wishlist"
            ],200);
        }

        if($post !== null) {
            return response()->json([
                "status" => "1",
                "message" => $wishlisted_post
            ],200);
        }
    }

    /**
     * Add Freelance Post to User Wishlist.
     */
    public function add_freelance_post_to_wishlist(Request $request) {

        $request->validate([
            'freelance_post_id' => ['required','exists:freelance_posts,id']
        ]);

        $freelance_post = FreelancePost::where("id",$request->freelance_post_id)->first();
        $user = User::where("id",auth()->user()->id)->first();
        
        $wishlisted_post = $user -> wish($freelance_post);

        if($freelance_post === null) {
            return response()->json([
                "status" => "0",
                "message" => "Freelance Post is Already Added To Wishlist"
            ],200);
        }

        if($freelance_post !== null) {
            return response()->json([
                "status" => "1",
                "message" => $wishlisted_post
            ],200);
        }
    }

    /**
     * Remove Post from User Wishlist.
     */
    public function remove_post_from_wishlist(Request $request) {
        
        $request->validate([
            'post_id' => ['required','exists:posts','exists:wishlists,model_id']
        ]);

        $post = Post::where("id",$request->post_id)->first();
        $user = User::where("id",auth()->user()->id)->first();
        
        $unwishlisted_post = $user -> unwish($post);

        if($post === null) {
            return response()->json([
                "status" => "0",
                "message" => "Post is Not in Wishlist"
            ],200);
        }

        else if($post !== null) {
            return response()->json([
                "status" => "1",
                "message" => $unwishlisted_post
            ],200);
        }
    }

    /**
     * Remove Freelance Post from User Wishlist.
     */
    public function remove_freelance_post_from_wishlist(Request $request) {

        $request->validate([
            'post_id' => ['required','exists:freelance_posts','exists:wishlists,model_id']
        ]);

        $freelance_post = FreelancePost::where("id",$request->freelance_post_id)->first();
        $user = User::where("id",auth()->user()->id)->first();
        
        $unwishlisted_post = $user -> unwish($freelance_post);

        if($unwishlisted_post === null) {
            return response()->json([
                "status" => "0",
                "message" => "Freelance Post is Not in Wishlist"
            ],200);
        }

        else if($unwishlisted_post !== null) {
            return response()->json([
                "status" => "1",
                "message" => $unwishlisted_post
            ],200);
        }
    }

}
