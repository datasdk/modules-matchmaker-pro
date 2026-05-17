@extends('layouts.app')



@section('content')
    <livewire:table 
        :config="Modules\Tasks\Tables\MatchTable::class" 
    />
@endsection