@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Opret Opgave</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('promatch.tasks.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Opgave info -->
        <div class="card mb-3">
            <div class="card-header">Opgave detaljer</div>
            <div class="card-body">

                <div class="mb-3">
                    <label for="name" class="form-label">Overskrift</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Indhold</label>
                    <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Pris</label>
                    <div class="col-sm-4">
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}">
                    </div>
                    <label class="col-sm-2 col-form-label">DKK</label>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Antal</label>
                    <div class="col-sm-4">
                        <input type="number" name="amount" class="form-control" value="{{ old('amount') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @foreach ([
                            'live' => 'Offentligt',
                            'pending' => 'Afventer opstart',
                            'ongoing' => 'Under udførelse',
                            'closed' => 'Færdigmeldt',
                            'hold' => 'Paused',
                            'cancelled' => 'Annulleret',
                            'draft' => 'Kladde'
                        ] as $value => $text)
                            <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>{{ $text }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="job" {{ old('type') == 'job' ? 'checked' : '' }}>
                        <label class="form-check-label">Projekt-opslag - Opslag som andre brugere kan sende ansøgninger til.</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="application" {{ old('type') == 'application' ? 'checked' : '' }}>
                        <label class="form-check-label">Mandskab - Opslag til ansøgninger eller udlejning af mandskab.</label>
                    </div>
                </div>

            </div>
        </div>

        <!-- Firma info -->
        <div class="card mb-3">
            <div class="card-header">Firma</div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Firma</label>
                    <select name="company_id" class="form-select">
                        <option value="">Vælg firma</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kontaktperson</label>
                    <select name="user_id" class="form-select">
                        <option value="">Vælg kontaktperson</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- Adresse -->
        <div class="card mb-3">
            <div class="card-header">Adresse</div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Gade</label>
                    <input type="text" class="form-control" name="address[street]" value="{{ old('address.street') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Post nr</label>
                    <input type="text" class="form-control" name="address[post_code]" value="{{ old('address.post_code') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">By</label>
                    <input type="text" class="form-control" name="address[city]" value="{{ old('address.city') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Land</label>
                    <select name="address[country]" class="form-select">
                        <option value="dk" {{ old('address.country') == 'dk' ? 'selected' : '' }}>DK</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Datoer -->
        <div class="card mb-3">
            <div class="card-header">Datoer</div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Startdato</label>
                    <input type="date" class="form-control" name="available[from]" value="{{ old('available.from') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Slutdato</label>
                    <input type="date" class="form-control" name="available[to]" value="{{ old('available.to') }}">
                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Opret opgave</button>
        <a href="{{ route('promatch.tasks.index') }}" class="btn btn-secondary">Tilbage</a>

    </form>
</div>
@endsection
