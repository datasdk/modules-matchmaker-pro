<?php

namespace Modules\Tasks\Http\Livewire;

use Livewire\Component;
use Modules\Tasks\Models\Tag;
use Illuminate\Support\Collection;

class TaskSpecifications extends Component
{
    public array $input = [];
    public array $groupedTags = [];
    public bool $loading = true;

    public function mount(array $value = [])
    {
        $this->input = $value;

        $this->loadTags();
    }

    public function updatedInput()
    {
        $this->emit('input', $this->input);
    }

    public function loadTags()
    {
        $tags = Tag::all(); // Hent alle tags

        // Sorter efter name og grupper efter type
        $this->groupedTags = $tags->sortBy('name')
            ->groupBy('type')
            ->map(fn($group) => $group->toArray())
            ->toArray();

        $this->loading = false;
    }

    public function render()
    {
        return view('tasks::livewire.task-specifications');
    }
}
