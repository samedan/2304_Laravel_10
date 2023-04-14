<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    
    public function homepage () {
        // simulate DBB
        $ourName = "Brad";
        $animals = ["Mitza", 'Art', "Purrsloud"];
        return view('homepage', ["allAnimals" => $animals, 'name' => $ourName, 'catname' => "mitza"]); // app/resources/views/homepage.blade.php
    }
    public function aboutPage () {
        return '<h1>About page</h1><a href="/">Home page</a>';
    }
}
