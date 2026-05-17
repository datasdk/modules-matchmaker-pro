<?php

namespace Modules\Tasks\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Modules\Tasks\Http\Requests\TaskSettingsRequest;
use Settings;

class TaskSettingsController extends Controller
{
    public function index()
    {
        $taskSettings = [
            'admin_notification_email' => config(
                'tasks.admin_notification_email',
                config('mail.from.address')
            ),
        ];

        return view('tasks::settings.tasks', compact('taskSettings'));
    }

    public function store(TaskSettingsRequest $request)
    {
        Settings::set('tasks.admin_notification_email', $request->input('admin_notification_email'));

        return redirect()->route('promatch.settings.index')
            ->with('success', 'Admin notification email updated successfully.');
    }
}
