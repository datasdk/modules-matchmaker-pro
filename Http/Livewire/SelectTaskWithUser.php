<?php

namespace Modules\Tasks\Http\Livewire;

use Livewire\Component;
use Modules\Tasks\Models\Tasks;
use Modules\Crm\Models\Company;

class SelectTaskWithUser extends Component
{
    public ?int $taskId = null;
    public ?int $companyId = null;
    public ?int $userId = null;

    public $users = [];
    public bool $loadingUsers = false;

    protected $listeners = [];

    public function mount($taskId = null, $userId = null)
    {
        $this->taskId = $taskId;
        $this->userId = $userId;

        if ($this->taskId) {
            $this->updateCompanyAndUsers();
        }
    }

    public function updatedTaskId($taskId)
    {
        $this->updateCompanyAndUsers();
    }

    protected function updateCompanyAndUsers()
    {
        $task = Tasks::with('company')->find($this->taskId);

        if ($task && $task->company_id) {
            $this->companyId = $task->company_id;
            $this->loadingUsers = true;
            $company = Company::with('owners')->find($this->companyId);
            $this->users = $company ? $company->owners->toArray() : [];
            $this->loadingUsers = false;

            // Hvis kun én ejer, vælg automatisk
            if (count($this->users) === 1) {
                $this->userId = $this->users[0]['id'];
            }
        } else {
            $this->companyId = null;
            $this->users = [];
            $this->userId = null;
        }

        $this->emitUp('taskUpdated', $this->taskId);
        $this->emitUp('userUpdated', $this->userId);
    }

    public function updatedUserId($userId)
    {
        $this->emitUp('userUpdated', $userId);
    }

    public function render()
    {
        return view('tasks::livewire.select-task-with-user');
    }
}
