<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class SocialSharebuttonsController extends Controller
{
    public function incrementShareCount(Request $request)
    {
        $postId = $request->input('postId');

        // Fetch the post by ID
        $post = Post::find($postId);

        if ($post) {
            // Increment the total share count
            $post->shares = $post->shares + 1;
            $post->save();

            // Return the updated share count
            return response()->json([
                'success' => true,
                'shareCount' => $post->shares,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Post not found'], 404);
    }
}
