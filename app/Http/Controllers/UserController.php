<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // AVATAR View Form
    public function showAvatarForm() {
        return view('avatar-form');
    }

    // AVATAR Store Form
    public function storeAvatar(Request $request) {
        // $request->file('avatar')->store("test123"); // /storage/app/test123
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        $user = auth()->user();
        // create unique image name
        $filename = $user->id.'-'.uniqid().'.jpg' ;
        // resize image to 120x120 pixels
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/'.$filename, $imgData);
        // OLD AVATAR
        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg") {
            // /storage/avatars/X.jpg -> public/avatars/X.jpg
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }
        return back()->with('success', 'Avatar succesfully updated.');
    }

    // Profile/Following/Followers double data
    private function getSharedData($user) {
        $currentlyFollowing = 0;
        if(auth()->check()) {
            $currentlyFollowing = Follow::where([
                ['user_id','=', auth()->user()->id], 
                ['followeduser','=', $user->id]])
                ->count();
        }
        View::share("sharedData", [ // sharedData is used as 'prop' by profile-posts.blade.php
            'currentlyFollowing' => $currentlyFollowing,
            'avatar' => $user->avatar,
            'username' => $user->username, 
            'isAdmin' => $user->isAdmin,
            'postCount' => $user->posts()->count(),
            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->followingTheseUsers()->count(),
        ]);
    }

    // PROFILE
    public function profile(User $user) {
        $this->getSharedData($user); // common part of the page
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }
    // PROFILE Raw
    public function profileRaw(User $user) {
        return response()->json([
            'theHTML'=> view('profile-posts-only', ['posts' => $user->posts()->latest()->get()] )->render(), 
            'docTitle' => $user->username."'s Profile"]);
    }

    // FOLLOWERS
    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }
    // PROFILE FOLLOWERS Raw
    public function profileFollowersRaw(User $user) {
        return response()->json([
            'theHTML'=> view('profile-followers-only', ['followers' => $user->followers()->latest()->get()] )->render(), 
            'docTitle' => $user->username."'s Followers"]);
    }

    // PROFILE FOLLOWINGS
    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }
    // PROFILE FOLLOWINGS Raw
    public function profileFollowingRaw(User $user) {
        return response()->json([
            'theHTML'=> view('profile-following-only', ['following' => $user->followingTheseUsers()->latest()->get()] )->render(), 
            'docTitle' => "Who ".$user->username." follows"]);
    }

    // ShowCorrectHomepage & CACHE
    public function showCorrectHomepage() {
        if (auth()->check()) { 
            // if logged in
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
        } else {
            $postCount = Cache::remember(
                "postCount", // what to remember
                120, // for how many seconds
                function() {    // what to do if data doesn't exist in cache
                    return Post::count();
                }   
            );
            return view('homepage', ['postCount' => $postCount]);
        }
    }

    // REGISTER
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields); // return the registered user
        auth()->login($user); // login the registered user with a cookie
        return redirect('/')->with('success', 'Thank you for creating your account');
    }

    // LOGIN
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginusername' => ['required'],
            'loginpassword' => ['required']
        ]);
        if(auth()->attempt([
            'username' => $incomingFields['loginusername'],
            'password' => $incomingFields['loginpassword']
            ])) {
                $request->session()->regenerate(); // COOKIE
                event(new OurExampleEvent([
                    "username" => auth()->user()->username, 
                    'action' => 'login']));
                return redirect('/')->with('success', 'You have successfully logged in.'); // redirect with a message named 'success'
        } else {
            return redirect('/')->with('failure', 'Invalid login');
        }
        User::create($incomingFields);
        return 'test';
    }
    //LOGOUT
    public function logout() {
        event(new OurExampleEvent([
            "username" => auth()->user()->username, 
            'action' => 'logout']));
        auth()->logout();        
        return redirect("/")->with('success', 'You are now logged out.');;
    }


    /// API ////////////////////////////////
    // LOGIn API /api/login
    public function loginApi(Request $request) {
        $incomingFields = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (auth()->attempt($incomingFields)) {
            $user = User::where('username', $incomingFields['username'])->first(); // 'first' get the first result of the results array
            $token = $user->createToken('ourapptoken')->plainTextToken;
            return $token;
        }
        return 'Not the correct user/pass';
    }
    
}
