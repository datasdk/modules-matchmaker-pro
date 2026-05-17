<?php

namespace Modules\Tasks\Http\Livewire;

use Livewire\Component;
use Modules\Tasks\Models\Tasks;

class SelectTask extends Component
{
    public $value = null;
    public $disabled = false;

    public $task = null; // Det valgte task objekt
    public $tasks = []; // Søgeresultater
    public $searchtext = '';
    public $minChars = 1;

    public $type = null; // "job" eller "application"
    public $name = 'task_id'; // dynamisk name property

    public $main_loading = false;
    public $loading = false;
    public $notFound = false;

    protected $listeners = ['clearSelectTask' => 'remove'];

    public function mount($value = null, $disabled = false, $type = null, $name = null)
    {
        $this->value = $value;
        $this->disabled = $disabled;
        $this->type = $type;

        if ($name) {
            $this->name = $name;
        }

        if ($this->value) {
            $this->main_loading = true;
            $task = Tasks::find($this->value);
      
            if ($task) {
                $this->task = $task;
            } else {
                $this->notFound = true;
                $this->value = null;
            }
            $this->main_loading = false;
        }
    }

    public function updatedSearchtext()
    {
        if (strlen($this->searchtext) < $this->minChars) {
            $this->tasks = [];
            $this->loading = false;
            return;
        }

        $this->loading = true;
        $this->searchTasks();
    }

    protected function searchTasks()
    {
        $search = '%' . $this->searchtext . '%';
        $query = Tasks::query()
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('resume', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });

        if ($this->type) {
            $query->where('type', $this->type);
        }

        $this->tasks = $query->limit(10)->get();

        $this->loading = false;
    }

    public function choose($taskId)
    {
        $task = Tasks::find($taskId);
        if (!$task) return;

        $this->task = $task;
        $this->tasks = [];
        $this->searchtext = '';
        $this->value = $task->id;

        $this->emitUp($this->name, $task);
        $this->emitUp($this->name . 'Id', $task->id);
    }

    public function remove()
    {
        $this->task = null;
        $this->value = null;
        $this->searchtext = '';
        $this->tasks = [];
        $this->emitUp($this->name, null);
        $this->emitUp($this->name . 'Id', null);
    }

    public function render()
    {
        return view('tasks::livewire.select-task');
    }
}
