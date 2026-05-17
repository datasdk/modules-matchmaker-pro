@extends('layouts.app')

@section('content')
<div>
    <div class="content-header mb-3">
        <h1>Opret Match</h1>
    </div>

    <form method="POST" action="{{ route('matches.store') }}">
        @csrf

        <table class="table table-bordered">
            <thead>
                <tr><th colspan="2">Match Information</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td width="150">UID</td>
                    <td>
                        <input type="text" 
                               class="form-control @error('uid') is-invalid @enderror" 
                               name="uid" 
                               value="{{ old('uid') }}"
                               required>
                        @error('uid')
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
                               value="{{ old('task_id') }}"
                               required>
                        @error('task_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Match Task ID</td>
                    <td>
                        <input type="number" 
                               class="form-control @error('match_with_task_id') is-invalid @enderror" 
                               name="match_with_task_id" 
                               value="{{ old('match_with_task_id') }}"
                               required>
                        @error('match_with_task_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Resume</td>
                    <td>
                        <textarea class="form-control @error('resume') is-invalid @enderror" 
                                  name="resume" 
                                  rows="3">{{ old('resume') }}</textarea>
                        @error('resume')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
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
                        <a href="{{ route('promatch.matches.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Annuller
                        </a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
@endsection