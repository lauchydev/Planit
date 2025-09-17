<?php

use Illuminate\Support\Facades\Route;

// // Task 1
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('greeting-form');
});


Route::post('greeting', function () {
    $name = request("name");
    $age = request("age");
    return view('greeting')->with('name', $name)->with('age', $age+1); 
});