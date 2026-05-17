<?php

namespace Modules\Tasks\Http\Controllers\Api;

use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;
use Modules\Tasks\Models\Calendar;
use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Services\MatchCalendarService;

class TasksCalendarController extends OrionBaseController
{

    protected $model = Calendar::class;


    public function store(Request $request)
    {
        $task = Tasks::findOrFail($request->input('task_id'));
        $matchId = $request->input('match_id');
        $data = $request->input('data', []);

        $calendar = app(MatchCalendarService::class)->create($task, $data, $matchId);

        return response()->json($calendar, 201);
    }


    public function update(Request $request, ...$args)
    {
        $task = Tasks::findOrFail($request->input('task_id'));
        $matchId = $request->input('match_id');
        $data = $request->input('data', []);

        // Hvis du vil gen-implementere update i din MatchCalendarService:
        // $calendar = app(MatchCalendarService::class)->update($id, $task, $matchId, $data);

        // Midlertidigt fallback hvis update ikke er implementeret
        return response()->json(['message' => 'Update is not implemented.'], 501);
    }


    public function destroy(Request $request, ...$args)
    {
        $task = Tasks::findOrFail($request->input('task_id'));
        $matchId = $request->input('match_id');

        $success = app(MatchCalendarService::class)->delete($task, $matchId);

        return response()->json(['deleted' => $success]);
    }


    protected $alwaysIncludes = [
        "available"
    ];

    protected $includes = [
        'task',
        'task.children',
        'task.hires',
        'task.hires.user',
        'task.hires.hire',
        'task.hires.user.contact',
        'task.hires.user.address',
        'task.hires.category',
        'task.hires.categories',
        'task.hires.available',
        'task.hiredBy',
        'task.hiredBy.user',
        'task.hiredBy.company',
        'task.user',
        'task.user.favorites',
        'task.user.address',
        'task.user.contact',
        'task.user.ratings',
        'task.user.raters',
        'task.user.ratingsByMe',
        'task.user.company',
        'task.user.avgRating',
        'task.userAvgRating',
        'task.files',
        'task.matches',
        'task.matches.user',
        'task.matches.user.company',
        'task.matches.available',
        'task.matches.company',
        'task.matches.address',
        'task.matches.contact',
        'task.matches.user.ratings',
        'task.matches.user.address',
        'task.matches.user.contact',
        'task.matches.category',
        'task.matches.categories',
        'task.matches.task',
        'task.match',
        'task.myMatch',
        'task.myMatch.user',
        'task.myMatch.available',
        'task.myMatch.company',
        'task.myMatch.user.ratings',
        'task.myMatch.category',
        'task.myMatch.categories',
        'task.myMatch.task',
        'task.myMatches',
        'task.myMatches.user',
        'task.myMatches.available',
        'task.myMatches.company',
        'task.myMatches.user.ratings',
        'task.myMatches.category',
        'task.myMatches.categories',
        'task.myMatches.task',
        'task.interactions',
        'task.interactions.user',
        'task.interactions.available',
        'task.interactions.company',
        'task.interactions.user.ratings',
        'task.interactions.category',
        'task.interactions.categories',
        'task.interactions.task',
        'task.interaction',
        'task.likes',
        'task.likes.user',
        'task.likes.available',
        'task.likes.company',
        'task.likes.companies',
        'task.likes.user.ratings',
        'task.likes.category',
        'task.likes.categories',
        'task.likes.task',
        'task.likedBy',
        'task.likedBy.user',
        'task.likedBy.available',
        'task.likedBy.company',
        'task.likedBy.user.ratings',
        'task.likedBy.category',
        'task.likedBy.categories',
        'task.likedBy.task',
        'task.likedBy.hires',
        'task.likedBy.hiredBy',
        'task.ratings',
        'task.applicant.user.ratings',
        'task.matches.chats',
        'task.matches.chats.participants',
        'task.matches.chats.participant',
        'task.matches.chats.participant.messageable',
        'task.matches.chat',
        'task.matches.chat.participants',
        'task.matches.chat.participant',
        'task.matches.chat.participant.messageable',
        'task.chats',
        'task.chats.participant',
        'task.chats.participant.messageable',
        'task.other',
        'task.company',
        'task.company.contact',
        'task.company.proff',
        'task.companies',
        'task.companies.contact',
        'task.companies.proff',
        'task.counters',
       // 'task.companyRating',
        'task.user_rating',
        'task.company.avgRating',
        'task.user.avgRating',
        'task.avgRating',
        'task.address',
        'task.addresses',
        'task.contact',
        'task.contacts',
        'task.available'
    ];

}
