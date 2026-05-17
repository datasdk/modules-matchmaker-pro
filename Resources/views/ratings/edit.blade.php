@extends('layouts.app')

@section('content')
<div x-data="{ rating: {{ old('rating', $rating->stars ?? 0) }} }">

    <h2>Rediger Rating</h2>

    <form method="POST" action="{{ route('promatch.rating.update', $rating->id) }}">
        @csrf
        @method('PUT')

        <!-- Vælg opgave + Kontaktperson -->
        <table class="table">
            <tr>
                <th colspan="2">Vælg opgave</th>
            </tr>
            <tbody>
                 <tr>
                    <td width="150"><label class="form-label">Vælg opgave</label></td>
                    <td>
                   
                        @livewire('tasks::select-task', [ 'name' => 'task_id', 'value' => $rating->rater_id ])
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

        <!-- Opgave som skal vurderes + Type + Rating -->
        <table class="table">
            <tr>
                <th colspan="2">Opgave som skal vurderes</th>
            </tr>
            <tbody>
                {{-- Task for rating --}}
                <tr>
                    <td width="150"><label class="form-label">Opgave til rating</label></td>
                    <td>
                        @livewire('tasks::select-task', [
                            'type' => 'application',
                            'name' => 'task_for_rate_id',
                            'value' => old('task_for_rate_id', $rating->target_id)
                        ])
                        @error('task_for_rate_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>

                {{-- Type --}}
                <tr>
                    <td><label for="type" class="form-label">Kvalitetstype</label></td>
                    <td>
                        <select name="type" id="type" class="form-control">
                            @foreach($ratingTypes as $key => $label)
                                <option value="{{ $key }}" @selected(old('type', $rating->type) == $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>

                {{-- Rating --}}
                <tr>
                    <td><label class="form-label">Rating</label></td>
                    <td>

                        @for($i = 1; $i <= 5; $i++)

                            <div>


                                <label class="me-3" style="cursor:pointer;">
                                        
                                    <input 
                                        type="radio" name="rating" value="{{ $i }}" class=""
                                        @if(old('rating', $rating->stars) == $i) checked @endif
                                    >

                                    {{ $i }} stjerne{{ $i > 1 ? 'r' : '' }}

                                </label>

                            </div>

                        @endfor

                     

                        @error('rating') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>

     
            </tbody>
        </table>


           <button type="submit" class="btn btn-primary">Opdater Rating</button>
                        <a href="{{ route('promatch.rating.index') }}" class="btn btn-secondary">Tilbage</a>


    </form>
</div>
@endsection
