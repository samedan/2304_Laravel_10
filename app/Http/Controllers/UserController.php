<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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
