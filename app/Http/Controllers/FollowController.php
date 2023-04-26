<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $userVictim) {
        // you cannot follow yourself 
        if($userVictim->id == auth()->user()->id) {
            return back()->with('failure', 'You cannot follow yourself');
        }

        // you cannot follow an already followed person 
        // check for the combination (creep, victim) in the Follow table, if you find, return 1, count(1) = True
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $userVictim->id]])->count();
        if($existCheck) {
            return back()->with('failure', 'You are already following that user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id; // the Creep
        $newFollow->followeduser = $userVictim->id; // Victim
        $newFollow->save();
        return back()->with('success', 'You are now following '.$userVictim->username);
    }

    public function removeFollow() {
        return "call";
    }
}
