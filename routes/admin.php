<?php

Route::get('/', 'dashboardController@index')->name('admin.index');

Route::resource('users', 'systemUsersController');
Route::post('users/{id}', 'systemUsersController@update');
Route::post('users/toggleActivate/{id}', 'systemUsersController@toggleActive');

Route::resource('admin-project', 'projectController');
Route::post('admin-project/update/{id}', 'projectController@update');
Route::post('allmembers', 'projectController@allMembers'); // Fetching all members
Route::post('admin-project/join', 'projectController@joinProject'); // Joining Project
Route::post('admin-project/toggle-freeze', 'projectController@toggleFreeze'); // Freezing Project
Route::get('admin-project/{id}/preview-project', 'projectController@showUpcoming')->name('admin-upcoming'); // Upcoming Project Pag

Route::post('admin-project/{id}/get-announcements', 'projectAnnouncementController@index'); // Getting Project Announcement
Route::post('admin-project/createAnnouncement', 'projectAnnouncementController@store'); // Saving Project Announcement
Route::post('admin-project/updateAnnouncement', 'projectAnnouncementController@update'); // Updating Project Announcement
Route::post('admin-project/deleteAnnouncement', 'projectAnnouncementController@delete'); // Deleting Project Announcement
Route::post('admin-project/saveAnnouncementComment', 'projectAnnouncementController@storeComment'); // Adding Project Announcement Comment
Route::post('admin-project/deleteAnnouncementComment', 'projectAnnouncementController@deleteComment'); // Deleting Project Announcement Comment

Route::post('admin-project/{id}/get-notes', 'projectNoteController@index'); // Getting Project Notes
Route::post('admin-project/create-note', 'projectNoteController@store'); // Saving Project Notes
Route::post('admin-project/add-note-members', 'projectNoteController@storeMembers'); // Saving Project Notes Members

Route::post('admin-project/{id}/get-tasks', 'projectTaskController@index'); // Getting Project Tasks
Route::post('admin-project/create-task', 'projectTaskController@store'); // Saving Project Task
Route::post('admin-project/add-task-members', 'projectTaskController@storeMembers'); // Saving Project Task Members
Route::post('admin-project/complete-task', 'projectTaskController@toggleComplete'); // Setting Project Task Complete status
Route::post('admin-project/update-task', 'projectTaskController@update'); // Updating Project Task
Route::post('admin-project/delete-task', 'projectTaskController@delete'); // Deleting Project Task

Route::post('admin-project/{id}/get-discussions', 'projectDiscussionController@index'); // Getting Project Discussions
Route::post('admin-project/create-discussion', 'projectDiscussionController@store'); // Saving Project Discussion
Route::post('admin-project/delete-discussion', 'projectDiscussionController@delete'); // Deleting Project Discussion

// ============ | Downloading post files | ===================
Route::get('admin-project/{id}/downloadFile/{file}', function($id,$file){
    $ext = explode('.',$file);
    $headers = [ 'content-Type' => 'application/'.$ext[1] ];
    return response()->download(public_path('uploads/project_files/'.$file)); // Downloading Post file
});

Route::post('admin-project/{id}/get-files', 'projectFileController@index'); // Getting Project Files
Route::post('admin-project/upload-file', 'projectFileController@upload'); // Uploading Project Files
Route::post('admin-project/delete-file', 'projectFileController@delete'); // Deleting Project Files