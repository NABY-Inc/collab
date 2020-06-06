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
