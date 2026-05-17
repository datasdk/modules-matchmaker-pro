<div>
    @if($task->company)
        <div><strong>{{ $task->company->name }}</strong></div>
        <div>Cvr: {{ $task->company->vat }}</div>
    @endif
    @if($task->user)
        <div>Oprettet af: {{ $task->user->first_name }} {{ $task->user->last_name }}</div>
    @endif
</div>
