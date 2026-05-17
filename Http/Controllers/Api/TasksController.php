<?php

namespace Modules\Tasks\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Tasks\Services\TaskService;
use Modules\Tasks\Http\Requests\TaskRequest;
use Illuminate\Http\JsonResponse;
use Modules\Tasks\Models\Tasks;
use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;
use Modules\Tasks\Models\Matches;
use Musonza\Chat\Models\Conversation;
use Musonza\Chat\Models\Participation;
use Musonza\Chat\Models\Message;
use Modules\Chat\Models\User as ChatUser;
use Modules\Tasks\Models\User as TaskUser;
use Modules\Tasks\Models\TaskRatingsAvg;
use Modules\Tasks\Http\Resources\TaskResource;
use Modules\Tasks\Services\MatchService;
use Modules\Tasks\Http\Repositories\TaskRepository;
use Modules\Media\Services\MediaLibraryService;
use Modules\Tasks\Events\TaskCreated;
use Carbon\Carbon;


class TasksController extends OrionBaseController
{

    protected $model = Tasks::class;

    protected $request = TaskRequest::class;

    // protected $resource = TaskResource::class;


    /**
     * Store a newly created task
     */
    public function store(Request $request): JsonResponse
    {

        
        $user_id = $request->input('user_id', auth()->id());
           
        $save_as_draft = $request->boolean("save_as_draft");

        $status = $save_as_draft ? "draft" : $request->status;




        // Create the task with required data
        $task = Tasks::create(
            array_merge(
                $request->only([
                    'name',
                    'description',
                    'company_id',
                    'type',
                    'amount',
                    'price'
                ]), 
                [
                    "status" => $status ?? "draft"
                ]
            )
        )
        ->set_user($user_id)
        ->set_available([
            "from" => Carbon::parse( $request->input('available.from',now()) )->startOfDay(),
            "to" => Carbon::parse( $request->input('available.to',now()) )->endOfDay()
        ])
        ->set_company($request->input('company_id'))
        ->setCategories($request->input('categories', []));


        // Set optional tags
        if ($request->filled('tags')) {
            $task->syncTags($request->input('tags'));
        }


        // Set optional address
        if ($request->filled('address')) {
            $task->syncAddress($request->address);
        }


        // Set optional contact info
        if ($request->filled('contact')) {
            $task->setContact($request->input('contact'));
        }


        $mls = new MediaLibraryService();
        $collection = "uploads";


        // Upload new files if present
        if ($request->has('uploads')) {

            $uploadedFiles = $mls->uploadFiles($task, $request->uploads,$collection);

        }


        // Add existing media IDs and merge with uploaded ones
        if ($request->has('attatchments')) {

            $mediaIds = $request->input('attatchments', []);

            $addedFiles = $mls->addFiles($task, $mediaIds,$collection);

        }


        
        event(new TaskCreated($task));


        return response()->json($task, 201);

    }



    /**
     * Update an existing task
     */
    public function update(Request $request, ...$args): JsonResponse
    {


        $task = Tasks::findOrFail($args[0]);

        $save_as_draft = $request->boolean("save_as_draft");

        $status = $save_as_draft ? "draft" : $request->status;

      



        // Update categories if provided
        if ($request->filled('categories')) {

            $task->setCategories($request->input('categories'));

        }


        // Update company if changed
        if ($request->filled('company_id')) {

            $task->set_company($request->input('company_id'));

        }


        // Update available dates
        if ($request->filled('available.from') && $request->filled('available.to')) {

            $task->set_available([
                "from" => Carbon::parse( $request->input('available.from',now()) )->startOfDay(),
                "to" => Carbon::parse( $request->input('available.to',now()) )->endOfDay()
            ]);

        }


        // Update tags
        if ($request->filled('tags')) {

            $task->syncTags($request->input('tags'));

        }



        $mls = new MediaLibraryService();

        $collection = "uploads";


        // Upload new files if present
        if ($request->has('uploads')) {

            $uploadedFiles = $mls->uploadFiles($task, $request->uploads,$collection);

        }


        // Add existing media IDs and merge with uploaded ones
        if ($request->has('attatchments')) {

            $mediaIds = $request->input('attatchments', []);

            $addedFiles = $mls->addFiles($task, $mediaIds,$collection);

        }


        // Optionally sync media: remove all others except the ones just handled
        if ($request->boolean('sync_media')) {
            
            $keepMediaIds = array_merge(
                $uploadedFiles ?? [],
                $addedFiles ?? []
            );
            
    
            $task->clearMediaCollectionExcept($collection, $keepMediaIds);

        }


        // Update user assignment if changed
        if ($request->filled('user_id')) {

            $task->set_user($request->input('user_id'));

        }


        // Update address
        if ($request->filled('address')) {
          
            $task->syncAddress($request->address);

        }


        // Update contact information
        if ($request->filled('contact')) {

            $task->setContact($request->input('contact'));

        }


        // Final task data update
        $task->update(
            array_merge(
                $request->only([
                    'name',
                    'description',
                    'company_id',
                    'amount',
                    'price'
                ]),[
                    'status' => $status ?? "draft"
                ]
            )
        );

        /*
        ->load(
            "available",
            "categories",
            "address",
            "contact",
            "tags",
            "company",
            "user",
            "media"
        )
        */

        // Return the updated task with related models loaded
        return response()->json($task->refresh());

    }


    /**
     * Delete a task by ID
     */
    public function destroy(Request $request, ...$args): JsonResponse
    {

        $task = Tasks::findOrFail($args[0]);

        try {
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to delete task: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete task.'], 500);
        }

    }


    /**
     * Get task overview with match, chat, and rating stats
     */
    public function taskInformationOverview(Request $req)
    {

        $user = $req->user();
        $tasks = Tasks::where("user_id", $user->id)->get();

        $potentialMatches = 0;
        $chats = 0;
        $unread_chats = 0;
        $matches = 0;

        $taskUser = TaskUser::find($user->id);


        // Determine rating context
        if ($taskUser->company) {
            $avgRating = TaskRatingsAvg::findAvgRating($taskUser->company);
        } else {
            $avgRating = TaskRatingsAvg::findAvgRating($taskUser);
        }


        foreach ($tasks as $task) {
            $potentialMatches += app(MatchService::class)->getPotentialMatches($task);
            $participation = ChatUser::find($user->id);
            $matches += $task->matches->count();

            foreach ($task->chats as $conversation) {
                $unread_chats += $conversation->unReadNotifications($participation)->count();
                $chats++;
            }
        }


        // Return the overview data using TaskResource
        return new $this->resource([
            "tasks" => $tasks->count(),
            "chats" => $chats,
            "unread_chats" => $unread_chats,
            "matches" => $matches,
            "rating" => $avgRating,
            "all_potential_matches" => $potentialMatches
        ]);

    }


    // Orion configuration for filters, includes, search, etc.

    protected $attributes = ['id', 'name'];

    protected $alwaysIncludes = [];

    protected $searchableBy = [
        "name", "description", "amount", "status", "active",
        "company.name", "company.vat",
        "address.street", "address.city", "address.post_code",
        "contact.email", "contact.phone",
        "user.username", "user.first_name", "user.middle_name", "user.last_name", "user.email", "user.type",
        "tags.name", "tags.slug"
    ];

    protected $includes = [
        // Long list of nested includes for eager loading relationships (matches, chats, ratings, etc.)
        // Used heavily by Orion or for building mobile views/API
        "children", 

        "hires", "hires.user", "hires.hire", "hires.user.contact", "hires.user.address",
        "hires.category", "hires.categories", "hires.addresses", "hires.available", "hires.address", 
        
        
        "hiredBy", "hiredBy.user", "hiredBy.company",

        "user", "user.favorites", "user.address", "user.contact", "user.ratings", "user.raters",
        "user.ratingsByMe", "user.company", "user.avgRating", "userAvgRating", 
        
        "files", 
        "media",

        "matches", "matches.user", "matches.user.company", "matches.available", "matches.company",
        "matches.address", "matches.contact", "matches.user.ratings", "matches.user.address",
        "matches.user.contact", "matches.category", "matches.categories", "matches.task",
        "match", 
        
        "myMatch", "myMatch.user", "myMatch.available", "myMatch.company",
        "myMatch.user.ratings", "myMatch.category", "myMatch.categories", "myMatch.task",
        "myMatches", "myMatches.user", "myMatches.available", "myMatches.company",
        "myMatches.user.ratings", "myMatches.category", "myMatches.categories", "myMatches.task",
        "interactions", "interactions.user", "interactions.available", "interactions.company",
        "interactions.user.ratings", "interactions.category", "interactions.categories",
        "interactions.task", "interaction", "likes", "likes.user", "likes.available", "likes.company",
        "likes.companies", "likes.user.ratings", "likes.category", "likes.categories", "likes.task",

        "likedBy", "likedBy.user", "likedBy.available", "likedBy.company", "likedBy.user.ratings",
        "likedBy.category", "likedBy.categories", "likedBy.address", "likedBy.addresses", "likedBy.task", "likedBy.hires", "likedBy.hiredBy",

        
        'companyRating',
    
        'userRating',
   

        "ratings", "applicant.user.ratings",
        "matches.chats", "matches.chats.participants", "matches.chats.participant",
        "matches.chats.participant.messageable", "matches.chat", "matches.chat.participants",
        "matches.chat.participant", "matches.chat.participant.messageable", "chats",
        "chats.participant", "chats.participant.messageable", "other", "company", "company.contact",
        "company.proff", "companies", "companies.contact", "companies.proff", "counters",
        //"companyRating", 
        "user_rating", "company.avgRating", "user.avgRating", "avgRating","user.avg","user.avgRating",
        "calendar", "ratings", "scheduledRatings"
    ];

    protected $filterableBy = [
        'status'
    ];

    protected $sortableBy = [
        'companyRating', 'companyRating.rating', 'company.avgRating',
        'company.avgRating.rating', 'userRating.rating', 'company_rating.rating',
        'company->avgRating.rating',

        'companyRating.rating',
        'userRating.rating',
      
    ];

    protected $exposedScopes = [
        'OrderByFavorites', 'OnlyHired', 'HideRejected', 'WithInteractionFrom',
        "IsLikedBy", "HasLiked", "Amount", 'WithoutInteractionFrom', 'WithMatchesFromUser',
        "WithoutSplittedTasks", "WithoutHired", "CalendarTasks", "HasMatches",
        "OrderByAvgRating", "MyMatches", "MyNestedMatches", "FromCompany",
        "OnlyCompanyWithSubsidiaries",  'IsOverlapping'
    ];

    protected $aggregates = [
        "ratings.relation_value", "user.ratings.relation_value", "ratings.id"
    ];
}
