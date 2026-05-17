@extends('layouts.app')

@section('content')
<div x-data="{ rating: {{ old('rating', 0) }} }">

    <h2>Opret Rating</h2>

    <form method="POST" action="{{ route('promatch.rating.store') }}">
        @csrf

        <table class="table">

            <tr>
                <th colspan="2">Vælg opgave</th>
            </tr>

            <tbody>
                {{-- Task som giver rating --}}
                <tr>
                    <td width="150"><label class="form-label">Vælg opgave</label></td>
                    <td>
                        @livewire('tasks::select-task', ['type' => 'job', 'name' => 'task_id'])
                        @error('task_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>

                {{-- Kontaktperson --}}
                <tr>
                    <td><label class="form-label">Kontaktperson</label></td>
                    <td>
                        @livewire('select-user', ['name' => 'user_id'])
                        @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>

            </tbody>
        </table>

        <table class="table">

            <tr>
                <th colspan="2">Opgave som skal vurderes</th>
            </tr>

            <tbody>
                {{-- Task for rating --}}
                <tr>
                    <td width="150"><label class="form-label">Opgave til rating</label></td>
                    <td>
                        @livewire('tasks::select-task', ['type' => 'application', 'name' => 'task_for_rate_id'])
                        @error('task_for_rate_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>

                  {{-- Type --}}
                <tr>
                    <td><label for="type" class="form-label">Kvalitetstype</label></td>
                    <td>
                        <select name="type" id="type" class="form-control">
                            @foreach($ratingTypes as $key => $label)
                                <option value="{{ $key }}" @selected(old('type') == $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>

                {{-- Rating --}}
                <tr>
                    <td><label class="form-label">Rating</label></td>
                    <td>
                        <div class="d-flex flex-column">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="form-check mb-2">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                        name="rating" 
                                        id="rating{{ $i }}" 
                                        value="{{ $i }}" 
                                        @checked(old('rating', $rating->rating ?? 0) == $i)>
                                    <label class="form-check-label" for="rating{{ $i }}">
                                        {{ $i }} {{ $i === 1 ? 'stjerne' : 'stjerner' }}
                                    </label>
                                </div>
                            @endfor
                        </div>
                        @error('rating') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>


              

                {{-- Submit --}}
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary">Opret Rating</button>
                        <a href="{{ route('promatch.rating.index') }}" class="btn btn-secondary">Tilbage</a>
                    </td>
                </tr>
            </tbody>
        </table>

    </form>
</div>
@endsection
