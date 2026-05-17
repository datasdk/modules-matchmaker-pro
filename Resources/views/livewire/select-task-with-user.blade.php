<div>
    <label class="form-label">Vælg opgave</label>
    @livewire('tasks::select-task', ['type' => 'job', 'name' => 'taskId', 'value' => $taskId])

    <label class="form-label mt-2">Kontaktperson</label>
    <select class="form-control" wire:model="userId" :disabled="$loadingUsers || count($users) === 0">
        <option value="">Vælg kontaktperson</option>
        @foreach($users as $user)
            <option value="{{ $user['id'] }}">{{ $user['first_name'] }} {{ $user['last_name'] }}</option>
        @endforeach
    </select>
</div>
