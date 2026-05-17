@extends('layouts.app')



@section('content')
    <livewire:table 
        :config="Modules\Tasks\Tables\TaskRatingsScheduledTable::class" 
    />
@endsection