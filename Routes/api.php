<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;


Route::group([
    'as' => 'api.tasks.',
    'prefix' => "tasks",
    'middleware' => ['auth:api'] // Middleware som klasse
], function () {

    // Tasks resource
    Orion::resource('tasks', 'Api\TasksController')->middleware('addStatusGroupsHeader');

    // Matches resource
    Orion::resource('matches', 'Api\MatchController');

    // Match actions
    Orion::resource('vote', 'Api\TasksVoteController')->except(['update']);

     
    Orion::resource('users', 'Api\UserController');

    Orion::resource('calendar', 'Api\TasksCalendarController');

    // Additional Match actions
    Route::get('show/info', 'Api\TasksController@taskInformationOverview')->name('task.info');

    // Ratings actions
    Orion::resource('ratings', 'Api\TaskRatingController');

    Orion::resource('ratings_schedules', 'Api\TaskRatingSchedulesController');

   
    Orion::resource('favorite', 'Api\TaskFavoriteController');

    Orion::resource('chats', 'Api\TaskChatController');

    
    Orion::resource('categories', 'Api\CategoriesController', ['only' => [ 'show', 'search']]);

 
    Route::post('tasks/{id}/reset/votes', 'Api\MatchController@resetVotes')->name('reset.votes');

    
    // Match hire/reject actions
   // Route::match(['post', 'patch'], '{taskId}/hire/{hireTaskId}', 'Api\MatchController@hire')->name('matches.hire');
   // Route::match(['post', 'patch'], '{taskId}/reject/{rejectTaskId}', 'Api\MatchController@reject')->name('matches.reject');

    // PDF generation
    // Route::get('pdf/generate', 'Api\PDFController@generate')->name('pdf.generate');
});
