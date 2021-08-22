<?php

Route::get('/', 'dashboardController@index')->name('user.index');

Route::resource('project', 'projectController');
Route::post('project/update/{id}', 'projectController@update');
Route::post('allmembers', 'projectController@allMembers'); // Fetching all members
Route::post('project/join', 'projectController@joinProject'); // Joining Project
Route::post('project/toggle-freeze', 'projectController@toggleFreeze'); // Freezing Project
Route::get('project/{id}/preview-project', 'projectController@showUpcoming')->name('upcoming'); // Upcoming Project Pag

Route::post('project/{id}/get-announcements', 'projectAnnouncementController@index'); // Getting Project Announcement
Route::post('project/createAnnouncement', 'projectAnnouncementController@store'); // Saving Project Announcement
Route::post('project/updateAnnouncement', 'projectAnnouncementController@update'); // Updating Project Announcement
Route::post('project/deleteAnnouncement', 'projectAnnouncementController@delete'); // Deleting Project Announcement
Route::post('project/saveAnnouncementComment', 'projectAnnouncementController@storeComment'); // Adding Project Announcement Comment
Route::post('project/deleteAnnouncementComment', 'projectAnnouncementController@deleteComment'); // Deleting Project Announcement Comment

Route::post('project/{id}/get-notes', 'projectNoteController@index'); // Getting Project Notes
Route::post('project/create-note', 'projectNoteController@store'); // Saving Project Notes
Route::post('project/add-note-members', 'projectNoteController@storeMembers'); // Saving Project Notes Members

Route::post('project/{id}/get-tasks', 'projectTaskController@index'); // Getting Project Tasks
Route::post('project/create-task', 'projectTaskController@store'); // Saving Project Task
Route::post('project/add-task-members', 'projectTaskController@storeMembers'); // Saving Project Task Members
Route::post('project/complete-task', 'projectTaskController@toggleComplete'); // Setting Project Task Complete status
Route::post('project/update-task', 'projectTaskController@update'); // Updating Project Task
Route::post('project/delete-task', 'projectTaskController@delete'); // Deleting Project Task

Route::post('project/{id}/get-discussions', 'projectDiscussionController@index'); // Getting Project Discussions
Route::post('project/create-discussion', 'projectDiscussionController@store'); // Saving Project Discussion
Route::post('project/delete-discussion', 'projectDiscussionController@delete'); // Deleting Project Discussion

// ============ | Downloading post files | ===================
Route::get('project/{id}/downloadFile/{file}', function($id,$file){
    $ext = explode('.',$file);
    $headers = [ 'content-Type' => 'application/'.$ext[1] ];
    return response()->download(public_path('uploads/project_files/'.$file)); // Downloading Post file
});

Route::post('project/{id}/get-files', 'projectFileController@index'); // Getting Project Files
Route::post('project/upload-file', 'projectFileController@upload'); // Uploading Project Files
Route::post('project/delete-file', 'projectFileController@delete'); // Deleting Project Files
