@extends('layouts.app')

@section('content')
<div>
    <div class="content-header mb-3">
        <h1>Opret Scheduled Rating</h1>
    </div>

    <form method="POST" action="{{ route('promatch.ratings-scheduled.store') }}">
        @csrf

        <table class="table table-bordered">
            <thead>
                <tr><th colspan="2">Scheduled Rating Information</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td width="150">UID</td>
                    <td>
                        <input type="text" 
                               class="form-control @error('uid') is-invalid @enderror" 
                               name="uid" 
                               value="{{ old('uid', $taskRatingScheduled->uid ?? '') }}"
                               required>
                        @error('uid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Stars</td>
                    <td>
                        <select name="stars" class="form-control @error('stars') is-invalid @enderror" required>
                            <option value="">Select Stars</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('stars', $taskRatingScheduled->stars ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ str_repeat('★', $i) }}
                                </option>
                            @endfor
                        </select>
                        @error('stars')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>
                        <input type="text" 
                               class="form-control @error('type') is-invalid @enderror" 
                               name="type" 
                               value="{{ old('type', $taskRatingScheduled->type ?? '') }}"
                               required>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Task ID</td>
                    <td>
                        <input type="number" 
                               class="form-control @error('task_id') is-invalid @enderror" 
                               name="task_id" 
                               value="{{ old('task_id', $taskRatingScheduled->task_id ?? '') }}"
                               required>
                        @error('task_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>User ID</td>
                    <td>
                        <input type="number" 
                               class="form-control @error('user_id') is-invalid @enderror" 
                               name="user_id" 
                               value="{{ old('user_id', $taskRatingScheduled->user_id ?? '') }}"
                               required>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Rater ID</td>
                    <td>
                        <input type="number" 
                               class="form-control @error('rater_id') is-invalid @enderror" 
                               name="rater_id" 
                               value="{{ old('rater_id', $taskRatingScheduled->rater_id ?? '') }}"
                               required>
                        @error('rater_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Rater Type</td>
                    <td>
                        <input type="text" 
                               class="form-control @error('rater_type') is-invalid @enderror" 
                               name="rater_type" 
                               value="{{ old('rater_type', $taskRatingScheduled->rater_type ?? '') }}"
                               required>
                        @error('rater_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Target ID</td>
                    <td>
                        <input type="number" 
                               class="form-control @error('target_id') is-invalid @enderror" 
                               name="target_id" 
                               value="{{ old('target_id', $taskRatingScheduled->target_id ?? '') }}"
                               required>
                        @error('target_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Target Type</td>
                    <td>
                        <input type="text" 
                               class="form-control @error('target_type') is-invalid @enderror" 
                               name="target_type" 
                               value="{{ old('target_type', $taskRatingScheduled->target_type ?? '') }}"
                               required>
                        @error('target_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Scheduled At</td>
                    <td>
                        <input type="datetime-local" 
                               class="form-control @error('scheduled_at') is-invalid @enderror" 
                               name="scheduled_at" 
                               value="{{ old('scheduled_at', $taskRatingScheduled->scheduled_at ?? '') }}"
                               required>
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            <option value="pending" {{ old('status', $taskRatingScheduled->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status', $taskRatingScheduled->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $taskRatingScheduled->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Opret
                        </button>
                        <a href="{{ route('promatch.ratings-scheduled.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Annuller
                        </a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
@endsection