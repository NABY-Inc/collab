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

// ADMIN ROUTES
Route::prefix('admin')->middleware(['auth'])->group(function(){
    Route::get('/', 'adminController@index')->name('admin.index');
    Route::resource('systemUsers', 'systemUsersController');
    Route::get('systemUser/toggleActivate/{id}', 'systemUsersController@toggleActive')->name('toggleActive');
    Route::post('systemUser/updateNew', 'systemUsersController@updateNew')->name('systemUser.updateNew');
    Route::resource('project', 'projectController');
    Route::post('project/{id}', 'projectController@update');
    Route::post('allmembers', 'projectController@allMembers'); // Fetching all members
    Route::get('project/{id}/newMembers', 'projectController@nonSelectedMembers'); // Fetching new members
    Route::post('joinProject', 'projectController@joinProject')->name('joinProject'); // Joining Project
    Route::get('project/removeMember/{id}', 'projectController@removeMember')->name('removeMember'); // Fetching new members
    Route::post('project/{id}/post','projectPostController@postDriver'); // Creating and updating Project Post
    Route::post('project/{id}/deletePost','projectPostController@deletePost'); // Deleting Project Post
    Route::get('project/{id}/allPosts','projectPostController@allPosts'); // Getting All Project Post
    Route::get('project/{id}/userPosts','projectPostController@userPosts'); // Getting User Project Post

    // ============ | Downloading post files | ===================
    Route::get('project/{id}/post-downloadFile/{file}', function($id,$file){
        $ext = explode('.',$file);
        $headers = [ 'content-Type' => 'application/'.$ext[1] ];
        return response()->download(public_path('uploads/post_resource/'.$file)); // Downloading Post file
    });
    // ============= | Downloading comment files | =================
    Route::get('project/{id}/comment-downloadFile/{file}', function($id,$file){ 
        $ext = explode('.',$file);
        $headers = [ 'content-Type' => 'application/'.$ext[1] ];
        return response()->download(public_path('uploads/comment_resource/'.$file)); // Downloading comment file
     });
     
    Route::post('project/{id}/deleteFile','projectPostController@deleteResource'); // Deleting Post Resource
    Route::post('project/{id}/deleteCommentFile','commentController@deleteResource'); // Deleting comment Resource
    Route::post('project/{id}/createComment','commentController@addComment'); // Adding comment
    Route::post('project/{id}/deleteComment','commentController@deleteComment'); // Delete comment
    Route::resource('task', 'taskController');
});

// USER ROUTES
Route::prefix('user')->middleware(['auth'])->group(function(){
    Route::get('/', 'userController@index')->name('user.index');
    Route::resource('userProject', 'projectController');
    Route::post('userProject/{id}', 'projectController@update');
    Route::post('allmembers', 'projectController@allMembers'); // Fetching all members
    Route::get('userProject/{id}/newMembers', 'projectController@nonSelectedMembers'); // Fetching new members
    Route::get('userProject/removeMember/{id}', 'projectController@removeMember')->name('removeMember'); // Fetching new members
    Route::post('joinProject', 'projectController@joinProject')->name('joinProject'); // Joining Project
    Route::post('userProject/{id}/post','projectPostController@postDriver'); // Creating and updating Project Post
    Route::post('userProject/{id}/deletePost','projectPostController@deletePost'); // Deleting Project Post
    Route::get('userProject/{id}/allPosts','projectPostController@allPosts'); // Getting All Project Post
    Route::get('userProject/{id}/userPosts','projectPostController@userPosts'); // Getting User Project Post
    Route::post('userProject/{id}/downloadFile', function(){
        if (request()->data == 'comment') {
            return response()->download(public_path('uploads/comment_resource/'.request()->url)); // Downloading comment file
        }else{
            return response()->download(public_path('uploads/post_resource/'.request()->url)); // Downloading Post file
        }
    });
    Route::post('userProject/{id}/deleteFile','projectPostController@deleteResource'); // Deleting Post Resource
    Route::post('userProject/{id}/deleteCommentFile','commentController@deleteResource'); // Deleting comment Resource
    Route::post('userProject/{id}/createComment','commentController@addComment'); // Adding comment
    Route::post('userProject/{id}/deleteComment','commentController@deleteComment'); // Delete comment
    Route::resource('task', 'taskController');
    // Route::resource('userProject', 'userProjectController');
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

// The code to allow permission on location upload
// sudo chmod 777 /Applications/XAMPP/Xamppfiles/htdocs/collab/public/uploads/folder_name
