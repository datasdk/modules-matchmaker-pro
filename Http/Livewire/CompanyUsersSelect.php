<?php

namespace Modules\Tasks\Http\Livewire;

use Livewire\Component;
use Modules\Crm\Models\Company;

class TaskCompanyUsersSelect extends Component
{
    public ?int $companyId = null;
    public ?int $input = null;
    public array $users = [];
    public bool $loading = true;

    protected $listeners = [
        'companyUpdated' => 'updatedCompanyId', // hvis companyId ændres udefra
    ];

    public function mount($companyId = null, $value = null)
    {
        $this->companyId = $companyId;
        $this->input = $value;

        if ($this->companyId) {
            $this->fetchUsers();
        }
    }

    public function updatedInput()
    {
        $this->emit('input', $this->input);
    }

    public function updatedCompanyId()
    {
        $this->fetchUsers();
    }

    public function fetchUsers()
    {
        $this->loading = true;

        if ($this->companyId) {
            $company = Company::with('owners')->find($this->companyId);
            $this->users = $company ? $company->owners->toArray() : [];

            // Hvis kun én ejer, vælg automatisk
            if (count($this->users) === 1) {
                $this->input = $this->users[0]['id'];
                $this->emit('input', $this->input);
            }
        } else {
            $this->users = [];
            $this->input = null;
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('tasks::livewire.task-company-users-select');
    }
}
