<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
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
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }

    // FOLLOWERS
    public function profileFollowers(User $user) {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    // FOLLOWINGS
    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }

    // ShowCorrectHomepage
    public function showCorrectHomepage() {
        if (auth()->check()) { 
            // if logged in
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
        } else {
            return view('homepage');
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
    
}
