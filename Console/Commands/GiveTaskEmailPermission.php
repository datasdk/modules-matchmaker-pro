<?php

namespace Modules\Tasks\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class GiveTaskEmailPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:give-task-email-permission {email : The email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives the "recieve-emails-when-task-created" permission to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '$email' not found.");
            return 1;
        }

        $permissionName = 'recieve-emails-when-task-created';

        // Sørg for at permission findes
        $permission = Permission::firstOrCreate(['name' => $permissionName]);

        $user->givePermissionTo($permission);

        $this->info("Permission '$permissionName' successfully assigned to $email.");

        return 0;
    }
}
