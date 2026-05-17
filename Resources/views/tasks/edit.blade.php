@extends('layouts.app')

@section('content')


    <h2>Rediger Opgave</h2>


    <form method="POST" action="{{ route('promatch.tasks.update', $task->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Opgave information --}}
        <table class="table">
            <tr>
                <th colspan="2">Opgave</th>
            </tr>
            <tr>
                <td width="150">Overskrift</td>
                <td>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $task->name) }}">
                </td>
            </tr>
            <tr>
                <td>Indhold</td>
                <td>
                    <textarea name="description" class="form-control" rows="5">{{ old('description', $task->description) }}</textarea>
                </td>
            </tr>
            <tr>
                <td>Vælg pris</td>
                <td class="d-flex">
                    <input type="number" name="price" class="form-control me-2" value="{{ old('price', $task->price) }}">
                    <span class="align-self-center">DKK</span>
                </td>
            </tr>
            <tr>
                <td>Antal</td>
                <td>
                    <input type="number" name="amount" class="form-control" value="{{ old('amount', $task->amount) }}">
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <select name="status" class="form-control">
                        @foreach([
                            'live' => 'Offentligt',
                            'pending' => 'Afventer opstart',
                            'ongoing' => 'Under udførelse',
                            'closed' => 'Færdigmeldt',
                            'hold' => 'Paused',
                            'cancelled' => 'Annulleret',
                            'draft' => 'Kladde'
                        ] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $task->status) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Type</td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="job" @checked(old('type', $task->type) == 'job')>
                        <label class="form-check-label"><strong>Projekt-opslag</strong> - Opslag som andre brugere kan sende ansøgninger til.</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="application" @checked(old('type', $task->type) == 'application')>
                        <label class="form-check-label"><strong>Mandskab</strong> - Opslag til ansøgninger eller udlejning af mandskab.</label>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Firma og kontakt --}}
        {{-- Firma og kontakt --}}
        <table class="table">
            <tr>
                <th colspan="2">Firma</th>
            </tr>
            <tr>
                <td width="150">Firma</td>
                <td>
                    @livewire('companies::select-company', [
                        'searchSubsidiary' => old('company_id', $task->company_id),
                    ])
                </td>
            </tr>
            <tr>
                <td>Kontaktperson</td>
                <td>
                    @livewire('select-user', [
                        'value' => old('user_id', $task->user_id),
                        'disabled' => false,
                        'name' => 'user_id'
                    ])
                </td>
            </tr>

        </table>


        {{-- Billede --}}
        <table class="table">
            <tr>
                <th colspan="2">Billede</th>
            </tr>
            <tr>
                <td width="150">Billede</td>
                <td>
                    <input type="file" name="image" class="form-control">
                    @if($task->image)
                        <img src="{{ asset('storage/' . $task->image) }}" alt="Billede" class="img-thumbnail mt-2" width="150">
                    @endif
                </td>
            </tr>
        </table>

        {{-- Datoer --}}
        <table class="table">
            <tr>
                <th colspan="2">Datoer</th>
            </tr>
            <tr>
                <td width="150">Startdato</td>
                <td><input type="date" name="available_from" class="form-control" value="{{ old('available_from', $task->available_from?->format('Y-m-d')) }}"></td>
            </tr>
            <tr>
                <td>Slutdato</td>
                <td><input type="date" name="available_to" class="form-control" value="{{ old('available_to', $task->available_to?->format('Y-m-d')) }}"></td>
            </tr>
        </table>

        {{-- Adresse --}}
        <table class="table">
            <tr>
                <th colspan="2">Adresse</th>
            </tr>
            <tr>
                <td width="150">Gade</td>
                <td><input type="text" name="street" class="form-control" value="{{ old('street', $task->address['street'] ?? '') }}"></td>
            </tr>
            <tr>
                <td>Post nr</td>
                <td><input type="text" name="post_code" class="form-control" value="{{ old('post_code', $task->address['post_code'] ?? '') }}"></td>
            </tr>
            <tr>
                <td>By</td>
                <td><input type="text" name="city" class="form-control" value="{{ old('city', $task->address['city'] ?? '') }}"></td>
            </tr>
            <tr>
                <td>Land</td>
                <td>
                    <select name="country" class="form-control">
                        <option value="dk" @selected(old('country', $task->address['country'] ?? 'dk') == 'dk')>DK</option>
                    </select>
                </td>
            </tr>
        </table>

        {{-- Specifikationer --}}
        {{-- Specifikationer --}}
        <div class="mb-3">
            <label>Fagrupper</label>
            @livewire('select-categories', [
                'selected' => old('tags', $task->tags->pluck('id')->toArray() ?? []),
                'type' => 'tasks'
            ])
        </div>


        

    

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">Gem opgave</button>
        <a href="{{ route('promatch.tasks.index') }}" class="btn btn-secondary">Tilbage</a>

    </form>

@endsection
