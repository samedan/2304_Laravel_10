<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    // PROFILE
    public function profile(User $user) {
        // return $user->posts()->get(); // posts() is defined in User.php
        return view('profile-posts', [
            'avatar' => $user->avatar,
            'username' => $user->username, 
            'isAdmin' => $user->isAdmin,
            'posts' => $user->posts()->latest()->get(),
            'postCount' => $user->posts()->count()
        ]);
    }

    // ShowCorrectHomepage
    public function showCorrectHomepage() {
        if (auth()->check()) {
            return view('homepage-feed');
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
                return redirect('/')->with('success', 'You have successfully logged in.'); // redirect with a message named 'success'
        } else {
            return redirect('/')->with('failure', 'Invalid login');
        }
        User::create($incomingFields);
        return 'test';
    }
    //LOGOUT
    public function logout() {
        auth()->logout();
        return redirect("/")->with('success', 'You are now logged out.');;
    }
    
}
