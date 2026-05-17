<?php

namespace Modules\Tasks\Console\Commands;

use Illuminate\Console\Command;
use Modules\Tasks\Models\Tasks;
use Illuminate\Support\Facades\DB;

class ClearDemoData extends Command
{
    protected $signature = 'tasks:clear-demo-data';

    protected $description = 'Reset task status, delete test tasks, and truncate related tables.';

    public function handle()
    {
        $this->info("Updating task statuses...");
        Tasks::query()->update(['status' => 'live']);

        $this->info("Deleting tasks with ID >= 733...");
        Tasks::where('id', '>=', 733)->forceDelete();

        $this->info("Truncating related tables...");
        DB::table('tasks_hires')->truncate();
        DB::table('tasks_interactions')->truncate();
        DB::table('task_matches')->truncate();

        $this->info("✅ Demo data cleared successfully.");
        return 0;
    }
}
