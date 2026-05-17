@extends('layouts.app')

@section('content')
<div class="container">

    <div class="content-header mb-3">
        <h1>Vis Opgave</h1>
        <a href="{{ route('promatch.tasks.index') }}" class="btn btn-secondary">Tilbage</a>
    </div>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="200">Overskrift</th>
                <td>{{ $task->name }}</td>
            </tr>
            <tr>
                <th>Beskrivelse</th>
                <td>{{ $task->description }}</td>
            </tr>
            <tr>
                <th>Pris</th>
                <td>{{ $task->price }} DKK</td>
            </tr>
            <tr>
                <th>Antal</th>
                <td>{{ $task->amount }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $task->status }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $task->type }}</td>
            </tr>
            <tr>
                <th>Firma</th>
                <td>{{ $task->company->name ?? '' }}</td>
            </tr>
            <tr>
                <th>Kontaktperson</th>
                <td>{{ $task->user->name ?? '' }}</td>
            </tr>
            <tr>
                <th>Adresse</th>
                <td>
                    <div>{{ $task->address->street ?? '' }}, </div>
                    <div>{{ $task->address->post_code ?? '' }} {{ $task->address->city ?? '' }},</div>
                    <div>{{ $task->address->country->name ?? '' }}</div>
                </td>
            </tr>
            <tr>
                <th>Startdato</th>
                <td>{{ $task->available_from?->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th>Slutdato</th>
                <td>{{ $task->available_to?->format('d-m-Y') }}</td>
            </tr>
            
            {{-- Specifikationer (tags) --}}
            @if($task->tags->isNotEmpty())
                <tr>
                    <th>Specifikationer</th>
                    <td>
                        {{-- Først grupper efter group_name --}}
                        @foreach($task->tags->groupBy('group_name') as $groupName => $tagsInGroup)
                            {{-- Derefter grupper efter type inden i hver group_name --}}
                            @foreach($tagsInGroup->groupBy('type') as $type => $tags)
                                <div class="mb-3">
                                    <h6 class="fw-bold mb-1">
                                        {{ $groupName ?? 'Uden gruppe' }} 
                                        <span class="text-muted">›</span> 
                                        {{ $type ?? 'Uden type' }}
                                    </h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($tags as $tag)
                                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</div>
@endsection