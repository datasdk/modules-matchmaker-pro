<?php

namespace Modules\Tasks\Http\Controllers;

use Modules\Tasks\Models\Tasks;
use Modules\Tasks\Http\Requests\TaskRequest;
use Modules\Tasks\Tables\TaskTable;
use App\Http\Controllers\OrionBaseController;
use Orion\Http\Requests\Request;


class TasksController extends OrionBaseController
{

    protected $model = Tasks::class;
    protected $request = TaskRequest::class;

    /**
     * List view
     */
    public function index(Request $req)
    {

        return view('tasks::tasks.index');
    }

    /**
     * Show create form
     */
    public function create(Request $req)
    {
        return view('tasks::tasks.create');
    }

    /**
     * Show item
     */
    public function show(Request $req, ...$args)
    {
        $task = Tasks::findOrFail($args[0]);
        return view('tasks::tasks.show', compact('task'));
    }

    /**
     * Show edit form
     */
    public function edit(Request $req, ...$args)
    {

        $task = Tasks::findOrFail($args[0]);
        return view('tasks::tasks.edit', compact('task'));
    }

    /**
     * Store task (API logic reused)
     */
    public function store(Request $request)
    {
        
        try {

            $user_id = $request->input('user_id', auth()->id());
            $save_as_draft = $request->boolean("save_as_draft");
            $status = $save_as_draft ? "draft" : $request->status;

            // Create task
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
                "from" => \Carbon\Carbon::parse($request->input('available.from', now()))->startOfDay(),
                "to"   => \Carbon\Carbon::parse($request->input('available.to',   now()))->endOfDay()
            ])
            ->set_company($request->input('company_id'))
            ->setCategories($request->input('categories', []));

            // Optional tags
            if ($request->filled('tags')) {
                $task->syncTags($request->input('tags'));
            }

            // Optional address
            if ($request->filled('address')) {
                $task->syncAddress($request->address);
            }

            // Optional contact
            if ($request->filled('contact')) {
                $task->setContact($request->input('contact'));
            }

            // Media
            $mls = new \Modules\Media\Services\MediaLibraryService();
            $collection = "uploads";

            if ($request->has('uploads')) {
                $uploadedFiles = $mls->uploadFiles($task, $request->uploads, $collection);
            }

            if ($request->has('attatchments')) {
                $mediaIds = $request->input('attatchments', []);
                $addedFiles = $mls->addFiles($task, $mediaIds, $collection);
            }

            event(new \Modules\Tasks\Events\TaskCreated($task));

            return redirect()
                ->route('promatch.tasks.show', $task->id)
                ->with('success', 'Opgaven blev oprettet.');

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }



    public function update(Request $request, ...$args)
    {
        try {

            $task = Tasks::findOrFail($args[0]);

            $save_as_draft = $request->boolean("save_as_draft");
            $status = $save_as_draft ? "draft" : $request->status;

            // Categories
            if ($request->filled('categories')) {
                $task->setCategories($request->input('categories'));
            }

            // Company
            if ($request->filled('company_id')) {
                $task->set_company($request->input('company_id'));
            }

            // Available dates
            if ($request->filled('available.from') && $request->filled('available.to')) {
                $task->set_available([
                    "from" => \Carbon\Carbon::parse($request->input('available.from', now()))->startOfDay(),
                    "to"   => \Carbon\Carbon::parse($request->input('available.to',   now()))->endOfDay()
                ]);
            }

            // Tags
            if ($request->filled('tags')) {
                $task->syncTags($request->input('tags'));
            }

            // Media
            $mls = new \Modules\Media\Services\MediaLibraryService();
            $collection = "uploads";

            if ($request->has('uploads')) {
                $uploadedFiles = $mls->uploadFiles($task, $request->uploads, $collection);
            }

            if ($request->has('attatchments')) {
                $mediaIds = $request->input('attatchments', []);
                $addedFiles = $mls->addFiles($task, $mediaIds, $collection);
            }

            // sync_media = remove everything except what user kept
            if ($request->boolean('sync_media')) {
                $task->clearMediaCollectionExcept(
                    $collection,
                    array_merge($uploadedFiles ?? [], $addedFiles ?? [])
                );
            }

            // User assignment
            if ($request->filled('user_id')) {
                $task->set_user($request->input('user_id'));
            }

            // Address
            if ($request->filled('address')) {
                $task->syncAddress($request->address);
            }

            // Contact
            if ($request->filled('contact')) {
                $task->setContact($request->input('contact'));
            }

            // Final update
            $task->update(
                array_merge(
                    $request->only([
                        'name',
                        'description',
                        'company_id',
                        'amount',
                        'price'
                    ]),
                    [
                        'status' => $status ?? "draft"
                    ]
                )
            );

            return redirect()
                ->route('promatch.tasks.show', $task->id)
                ->with('success', 'Opgaven blev opdateret.');

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }


}
