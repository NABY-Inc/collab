<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Auth::routes(['register' => false]); // Excluding self registration

// Rideirect user based on role
Route::get('/home', function (){
    switch (auth()->user()->role){
        case 1:
            return redirect(route('admin.index'));
        break;
        case 0:
            return redirect(route('user.index'));
        break;
        default:
            return abort(404);
        break;
    }
})->name('home');

// The code to allow permission on location upload
// sudo chmod 777 /Applications/XAMPP/Xamppfiles/htdocs/collab_new/public/uploads/folder_name
