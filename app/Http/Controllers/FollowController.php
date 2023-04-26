<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user) {
        // you cannot follow yourself 
        if($user->id == auth()->user()->id) {
            return back()->with('failure', 'You cannot follow yourself');
        }

        // you cannot follow an already followed person 
        // check for the combination (creep, victim) in the Follow table, if you find, return 1, count(1) = True
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if($existCheck) {
            return back()->with('failure', 'You are already following that user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id; // the Creep
        $newFollow->followeduser = $user->id; // Victim
        $newFollow->save();
        return back()->with('success', 'You are now following '.$user->username);
    }

    public function removeFollow(User $user) {
        Follow::where([
            ['user_id','=',auth()->user()->id],
            ['followeduser', '=', $user->id]
        ])->delete();
        return back()->with('success', 'User successfully unfollowed');
    }
}
