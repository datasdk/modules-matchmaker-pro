<div x-data="{ searching: false }" 
     x-init="Livewire.on('searching', value => searching = value)">

    @if($main_loading)
        <div></div>
    @else

        @if($task)
            <div class="choosen-task mb-2">
                <strong>{{ $task->name }}</strong>
                <span class="float-right text-muted pr-3">ID: {{ $task->id }}</span>
                
                @if(!$disabled)
                    <span class="close" wire:click="remove">X Fjern task</span>
                @endif

                <input type="hidden" name="{{ $name }}" value="{{ $task->id }}">
            </div>
        @else
            <input type="text" 
                   class="form-control mb-2" 
                   placeholder="Søg efter task..."
                   wire:model.debounce.500ms="searchtext"
                   @input="searching = true; clearTimeout(window.searchTimeout); window.searchTimeout = setTimeout(() => searching = false, 500)"
                   wire:loading.attr="disabled">

            <div x-show="searching" class="mb-2">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden"></span>
                </div>
                Søger tasks...
            </div>

            @if(strlen($searchtext) >= $minChars && count($tasks) === 0 && !$loading)
                <div class="alert alert-info">Ingen tasks fundet</div>
            @else
                @if(count($tasks) > 0)
                    <ul class="select-task-container list-unstyled mt-2">
                        @foreach($tasks as $taskItem)
                            <li class="task pl-3 pr-3 py-1" wire:click="choose({{ $taskItem->id }})">
                                <span>{{ $taskItem->name }}</span>
                                <span class="float-right text-muted">ID: {{ $taskItem->id }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif

            @if($notFound)
                <div class="text-danger mt-2">
                    Tasken blev ikke fundet eller er muligvis slettet!
                </div>
            @endif
        @endif

    @endif
</div>

<style>
.choosen-task,
.task {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background: #f5f5f5;
    margin-bottom: 5px;
    border-radius: 4px;
}
.task:hover {
    background: #fff;
    cursor: pointer;
}
.close {
    float: right;
    padding: 2px 6px;
    font-size: 12px;
    cursor: pointer;
}
.select-task-container {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
}
</style>
