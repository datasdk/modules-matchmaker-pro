@extends('layouts.app')

@section('actions')
<a href="{{ route('promatch.rating.create') }}" class="btn btn-primary">Opret Rating</a>
@endsection

@section('content')
<livewire:table :config="Modules\Tasks\Tables\TaskRatingTable::class" />
@endsection
