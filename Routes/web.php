<?php

use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\MatchController;
use Modules\Tasks\Http\Controllers\TaskRatingSchedulesController;

// Hoved Tasks resource

// Subresources under /tasks
Route::prefix('promatch')->name('promatch.')->group(function () {

    Route::resource('tasks', 'TasksController');

    Route::resource('rating', 'TaskRatingController');

    Route::resource('settings', 'Settings\TaskSettingsController');


    Route::resource('matches', MatchController::class);
    
    
    // Additional routes for specific actions
    Route::post('matches/{taskId}/hire/{hireTaskId}', [MatchController::class, 'hire'])->name('matches.hire');

    Route::post('matches/{taskId}/reject/{rejectTaskId}', [MatchController::class, 'reject'])->name('matches.reject');

    Route::post('matches/{id}/vote', [MatchController::class, 'vote'])->name('matches.vote');

    Route::post('matches/{id}/reset-votes', [MatchController::class, 'resetVotes'])->name('matches.reset-votes');



    Route::resource('ratings-scheduled', TaskRatingSchedulesController::class);

});
