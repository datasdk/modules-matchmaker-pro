@extends('layouts.app')

@section('content')
<div>
    <div class="content-header mb-3">
        <h1>Rediger Match</h1>
    </div>

    <form method="POST" action="{{ route('promatch.matches.update', $match->id) }}">
        @csrf
        @method('PUT')

        <table class="table table-bordered">
            <thead>
                <tr><th colspan="2">Match Information</th></tr>
            </thead>
            <tbody>
        
                <tr>
                    <td width="150">Post</td>
                    <td>
                        @livewire('tasks::select-task', [
                            'value' => old('task_id', $match->task_id),
                            'name' => 'task_id',
                            'type' => null // eller 'job'/'application' hvis du vil filtrere
                        ])
                        @error('task_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <input type="hidden" name="task_id" value="{{ old('task_id', $match->task_id) }}" id="task_id_input">
                    </td>
                </tr>
                <tr>
                    <td>Matches with</td>
                    <td>
                        @livewire('tasks::select-task', [
                            'value' => old('match_with_task_id', $match->match_with_task_id),
                            'name' => 'match_with_task_id',
                            'type' => null // eller 'job'/'application' hvis du vil filtrere
                        ])
                        @error('match_with_task_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <input type="hidden" name="match_with_task_id" value="{{ old('match_with_task_id', $match->match_with_task_id) }}" id="match_with_task_id_input">
                    </td>
                </tr>
               
            </tbody>
           
        </table>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Gem ændringer
        </button>
        
        <a href="{{ route('promatch.matches.index') }}" class="btn btn-secondary">
            <i class="fas fa-times mr-2"></i>Annuller
        </a>


    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lyt efter events fra Livewire komponenter
        Livewire.on('task_id', function(task) {
            document.getElementById('task_id_input').value = task ? task.id : '';
        });
        
        Livewire.on('match_with_task_id', function(task) {
            document.getElementById('match_with_task_id_input').value = task ? task.id : '';
        });
    });
</script>
@endpush