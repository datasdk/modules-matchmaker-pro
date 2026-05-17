@extends('layouts.app')

@section('content')
<div class="">
    <div class="content-header mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Scheduled Rating Details</h1>
            <div>
         
                <a href="{{ route('promatch.ratings-scheduled.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Tilbage
                </a>
            </div>
        </div>
    </div>

    <!-- Rating Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-light">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Rating Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">ID</th>
                <td>{{ $taskRatingScheduled->id }}</td>
            </tr>
            <tr>
                <th>UID</th>
                <td>{{ $taskRatingScheduled->uid }}</td>
            </tr>
            <tr>
                <th>Stars</th>
                <td>
                    @include('tasks::tables.rating-stars-column', ['rating' => $taskRatingScheduled])
                </td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $taskRatingScheduled->type }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @include('tasks::tables.rating-status-column', ['rating' => $taskRatingScheduled])
                </td>
            </tr>
         
        
            <tr>
                <th>Created</th>
                <td>{{ $taskRatingScheduled->created_at->format('d/m/Y H:i') }}</td>
            </tr>
         
        </tbody>
    </table>

    <!-- Match Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-info text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-handshake mr-2"></i>Match Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">Match</th>
                <td>
                    @include('tasks::tables.match-link-column', [
                        'match' => $taskRatingScheduled->match,
                        'label' => 'Match'
                    ])
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Task Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-primary text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-tasks mr-2"></i>Task Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">Task</th>
                <td>
                    @include('tasks::tables.task-link-column', [
                        'task' => $taskRatingScheduled->task,
                        'label' => 'Task'
                    ])
                </td>
            </tr>
            <tr>
                <th>Available Dates</th>
                <td>
                    @if($taskRatingScheduled->task && $taskRatingScheduled->task->available)
                        @include('tasks::tables.available-dates-column', [
                            'available' => $taskRatingScheduled->task->available
                        ])
                    @else
                        <div class="text-muted">N/A</div>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Task For Rate Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-success text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-star mr-2"></i>Task For Rate Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">Task For Rate</th>
                <td>
                    @include('tasks::tables.task-link-column', [
                        'task' => $taskRatingScheduled->taskForRate,
                        'label' => 'Task For Rate'
                    ])
                </td>
            </tr>
            @if($taskRatingScheduled->taskForRate && $taskRatingScheduled->taskForRate->available)
            <tr>
                <th>Available Dates</th>
                <td>
                    @include('tasks::tables.available-dates-column', [
                        'available' => $taskRatingScheduled->taskForRate->available
                    ])
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- User Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-secondary text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-user mr-2"></i>User Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">User</th>
                <td>
                    @include('tasks::tables.user-link-column', [
                        'user' => $taskRatingScheduled->user,
                        'label' => 'User'
                    ])
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Rater & Target Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-warning text-dark">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-exchange-alt mr-2"></i>Rater & Target Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">Rater ID</th>
                <td>{{ $taskRatingScheduled->rater_id }}</td>
            </tr>
            <tr>
                <th>Rater Type</th>
                <td>{{ $taskRatingScheduled->rater_type }}</td>
            </tr>
            <tr>
                <th>Target ID</th>
                <td>{{ $taskRatingScheduled->target_id }}</td>
            </tr>
            <tr>
                <th>Target Type</th>
                <td>{{ $taskRatingScheduled->target_type }}</td>
            </tr>
        </tbody>
    </table>


</div>
@endsection


