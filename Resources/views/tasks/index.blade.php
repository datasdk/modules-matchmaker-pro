@extends('layouts.app')

@section('actions')

<!--
    <a href="{{ route('promatch.tasks.create') }}" class="btn btn-primary">Opret opgave</a>
-->

@endsection

@section('content')

    <livewire:table 
        :config="Modules\Tasks\Tables\TaskTable::class" 
    />

@endsection
