<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function delete(Post $post) {
        // check the permission
        if(auth()->user()->cannot('delete', $post)) { // PostPolicy
          return 'You cannot delete that';
        }
        $post->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success', 'Post succesffully deleted');
    }

    public function viewSinglePost(Post $post) {
        $post["body"] = strip_tags(
            Str::markdown($post->body), 
            '<p><ul><ol><li><strong><h3><br>' // allowed elements
        ); 
        return view('single-post', ['post'=> $post]);        
    }
    
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title'=>'required',
            'body'=>'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")->with("success", "New post succesfully created");
    }

    public function showCreateForm() {
        return view('create-post');
    }

    
}
