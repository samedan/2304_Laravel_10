<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Mail\NewPostEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendNewPostEmail;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    // SEARCH post
    public function search($term) {
        $posts = Post::search($term)->get(); // 'use Searchable' on the Post model
        // complete returned user data, not only 'id'
        $posts->load('user:id,username,avatar');
        return $posts;
    }

    // UPDATE Post
    public function actuallyUpdate(Post $post, Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $post->update($incomingFields);
        return back()->with('success', 'Post succesfully updated');
    }

    // show edit form
    public function showEditForm(Post $post) {
        return view('edit-post', ['post' => $post]);
    }

    // DELETE Post
    public function delete(Post $post) {
        // check the permission
        
        $post->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success', 'Post succesffully deleted');
    }

    // GET Post
    public function viewSinglePost(Post $post) {
        $post["body"] = strip_tags(
            Str::markdown($post->body), 
            '<p><ul><ol><li><strong><h3><br>' // allowed elements
        ); 
        return view('single-post', ['post'=> $post]);        
    }
    
    // POST Post & Send Email
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title'=>'required',
            'body'=>'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        // send email
        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'name' => auth()->user()->username,
            'title' => $newPost->title
        ]));
       
        return redirect("/post/{$newPost->id}")->with("success", "New post succesfully created");
    }

    // show post form
    public function showCreateForm() {
        return view('create-post');
    }

    /// API ////////////////////////////////
    // LOGIn API /api/login
    // POST Post & Send Email /api/create-post
    public function storeNewPostApi(Request $request) {
        $incomingFields = $request->validate([
            'title'=>'required',
            'body'=>'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        // send email
        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'name' => auth()->user()->username,
            'title' => $newPost->title
        ]));
       
        return $newPost->id;
    }

      // DELETE API Post /api/delete-post/xx
      public function deleteApi(Post $post) {
        // check the permission
        $post->delete();
        return "post deleted";
    }
    
    
}
