<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::get('/', 'adminController@index')->name('admin.index');
    Route::resource('systemUsers', 'systemUsersController');
    Route::get('systemUser/toggleActivate/{id}', 'systemUsersController@toggleActive')->name('toggleActive');
    Route::get('systemUser/updateNew', 'systemUsersController@updateNew')->name('systemUser.updateNew');
    Route::resource('project', 'projectController');
    Route::post('project/allmembers', 'projectController@allMembers'); // Fetching all members
    Route::get('project/{id}/newMembers', 'projectController@nonSelectedMembers'); // Fetching new members
    Route::get('project/removeMember/{id}', 'projectController@removeMember')->name('removeMember'); // Fetching new members
    Route::post('project/{id}/post','projectPostController@createPost'); // Creating Project Post
    Route::post('project/{id}/deletePost','projectPostController@deletePost'); // Deleting Project Post
    Route::get('project/{id}/allPosts','projectPostController@allPosts'); // Getting All Project Post
    Route::get('project/{id}/userPosts','projectPostController@userPosts'); // Getting User Project Post
    Route::post('project/{id}/createComment','commentController@addComment'); // Adding comment
    Route::post('project/{id}/deleteComment','commentController@deleteComment'); // Delete comment
    Route::resource('task', 'taskController');
});

Route::get('/home', function (){
    switch (auth()->user()->role){
        case 1:
            return redirect(route('admin.index'));
        break;
        case 2:
            return redirect(route('user.index'));
        break;
        default:
            return abort(404);
            breeak;
    }
})->name('home');
