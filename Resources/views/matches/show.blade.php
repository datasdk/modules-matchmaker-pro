@extends('layouts.app')

@section('content')
<div class="">
    <div class="content-header mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Match Details</h1>
            <div>
                <a href="{{ route('promatch.matches.edit', $match->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i> Rediger
                </a>
                <a href="{{ route('promatch.matches.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Tilbage
                </a>
            </div>
        </div>
    </div>

    <!-- Match Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-light">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Match Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">ID</th>
                <td>{{ $match->id }}</td>
            </tr>
            <tr>
                <th>UID</th>
                <td>{{ $match->uid }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($match->hired)
                        <span class="badge badge-success badge-pill px-3">
                            <i class="fas fa-check-circle mr-1"></i>Hired
                        </span>
                    @elseif($match->rejected)
                        <span class="badge badge-danger badge-pill px-3">
                            <i class="fas fa-ban mr-1"></i>Rejected
                        </span>
                    @else
                        <span class="badge badge-info badge-pill px-3">
                            <i class="fas fa-clock mr-1"></i>Active
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Navn</th>
                <td>{{ $match->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Resume</th>
                <td>{{ $match->resume ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Beskrivelse</th>
                <td>{{ $match->description ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Oprettet</th>
                <td>{{ $match->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Sidst opdateret</th>
                <td>{{ $match->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Task Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-primary text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-tasks mr-2"></i>Task Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            @if($match->task)
                <tr>
                    <th width="200">ID</th>
                    <td>{{ $match->task->id }}</td>
                </tr>
                <tr>
                    <th>Navn</th>
                    <td>{{ $match->task->name }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>
                        <span class="badge {{ $match->task->type === 'job' ? 'badge-primary' : 'badge-success' }}">
                            {{ ucfirst($match->task->type) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Sagsnummer</th>
                    <td>{{ $match->task->case_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Resume</th>
                    <td>{{ $match->task->resume ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Beskrivelse</th>
                    <td>{{ $match->task->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Pris</th>
                    <td>{{ number_format($match->task->price, 2) }} kr</td>
                </tr>
                <tr>
                    <th>Antal</th>
                    <td>{{ $match->task->amount }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($match->task->status) }}</td>
                </tr>
                <tr>
                    <th>Aktiv</th>
                    <td>
                        @if($match->task->active)
                            <span class="badge badge-success">Ja</span>
                        @else
                            <span class="badge badge-danger">Nej</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Bruger</th>
                    <td>{{ $match->task->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Firma</th>
                    <td>{{ $match->task->company->name ?? 'N/A' }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="2" class="text-center text-muted">Ingen task fundet</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Matched Task Information -->
    <table class="table table-bordered mb-4">
        <thead class="bg-success text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-handshake mr-2"></i>Matched Task Information</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            @if($match->matchedTask)
                <tr>
                    <th width="200">ID</th>
                    <td>{{ $match->matchedTask->id }}</td>
                </tr>
                <tr>
                    <th>Navn</th>
                    <td>{{ $match->matchedTask->name }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>
                        <span class="badge {{ $match->matchedTask->type === 'job' ? 'badge-primary' : 'badge-success' }}">
                            {{ ucfirst($match->matchedTask->type) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Sagsnummer</th>
                    <td>{{ $match->matchedTask->case_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Resume</th>
                    <td>{{ $match->matchedTask->resume ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Beskrivelse</th>
                    <td>{{ $match->matchedTask->description ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Pris</th>
                    <td>{{ number_format($match->matchedTask->price, 2) }} kr</td>
                </tr>
                <tr>
                    <th>Antal</th>
                    <td>{{ $match->matchedTask->amount }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($match->matchedTask->status) }}</td>
                </tr>
                <tr>
                    <th>Aktiv</th>
                    <td>
                        @if($match->matchedTask->active)
                            <span class="badge badge-success">Ja</span>
                        @else
                            <span class="badge badge-danger">Nej</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Bruger</th>
                    <td>{{ $match->matchedTask->user->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Firma</th>
                    <td>{{ $match->matchedTask->company->name ?? 'N/A' }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="2" class="text-center text-muted">Ingen matched task fundet</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Task Addresses -->
    @if($match->task && $match->task->addresses && $match->task->addresses->count() > 0)
    <table class="table table-bordered mb-4">
        <thead class="bg-warning text-dark">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Task Addresser</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($match->task->addresses as $index => $address)
                @if($index > 0)
                    <tr><td colspan="2"><hr></td></tr>
                @endif
                <tr>
                    <th width="200">Gade</th>
                    <td>{{ $address->street ?? 'N/A' }}</td>
                </tr>
                @if($address->street_2)
                <tr>
                    <th>Gade 2</th>
                    <td>{{ $address->street_2 }}</td>
                </tr>
                @endif
                <tr>
                    <th>By</th>
                    <td>{{ $address->city ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Postnummer</th>
                    <td>{{ $address->postal_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Region</th>
                    <td>{{ $address->state ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Land</th>
                    <td>{{ $address->country ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Matched Task Addresses -->
    @if($match->matchedTask && $match->matchedTask->addresses && $match->matchedTask->addresses->count() > 0)
    <table class="table table-bordered mb-4">
        <thead class="bg-info text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Matched Task Addresser</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($match->matchedTask->addresses as $index => $address)
                @if($index > 0)
                    <tr><td colspan="2"><hr></td></tr>
                @endif
                <tr>
                    <th width="200">Gade</th>
                    <td>{{ $address->street ?? 'N/A' }}</td>
                </tr>
                @if($address->street_2)
                <tr>
                    <th>Gade 2</th>
                    <td>{{ $address->street_2 }}</td>
                </tr>
                @endif
                <tr>
                    <th>By</th>
                    <td>{{ $address->city ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Postnummer</th>
                    <td>{{ $address->postal_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Region</th>
                    <td>{{ $address->state ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Land</th>
                    <td>{{ $address->country ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Task Contacts -->
    @if($match->task && $match->task->contacts && $match->task->contacts->count() > 0)
    <table class="table table-bordered mb-4">
        <thead class="bg-secondary text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-address-book mr-2"></i>Task Kontakter</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($match->task->contacts as $index => $contact)
                @if($index > 0)
                    <tr><td colspan="2"><hr></td></tr>
                @endif
                <tr>
                    <th width="200">Navn</th>
                    <td>{{ $contact->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $contact->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Telefon</th>
                    <td>{{ $contact->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $contact->type ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Matched Task Contacts -->
    @if($match->matchedTask && $match->matchedTask->contacts && $match->matchedTask->contacts->count() > 0)
    <table class="table table-bordered mb-4">
        <thead class="bg-dark text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-address-book mr-2"></i>Matched Task Kontakter</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($match->matchedTask->contacts as $index => $contact)
                @if($index > 0)
                    <tr><td colspan="2"><hr></td></tr>
                @endif
                <tr>
                    <th width="200">Navn</th>
                    <td>{{ $contact->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $contact->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Telefon</th>
                    <td>{{ $contact->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $contact->type ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Task Skills -->
    @if($match->task && $match->task->skills && $match->task->skills->count() > 0)
    <table class="table table-bordered mb-4">
        <thead class="bg-primary text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-tags mr-2"></i>Task Færdigheder</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">Skills</th>
                <td>
                    @foreach($match->task->skills as $skill)
                        <span class="badge badge-secondary mr-1 mb-1">{{ $skill->name }}</span>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Matched Task Skills -->
    @if($match->matchedTask && $match->matchedTask->skills && $match->matchedTask->skills->count() > 0)
    <table class="table table-bordered mb-4">
        <thead class="bg-success text-white">
            <tr>
                <th colspan="2">
                    <h5 class="mb-0"><i class="fas fa-tags mr-2"></i>Matched Task Færdigheder</h5>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="200">Skills</th>
                <td>
                    @foreach($match->matchedTask->skills as $skill)
                        <span class="badge badge-secondary mr-1 mb-1">{{ $skill->name }}</span>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
    @endif

    
    <a href="{{ route('promatch.matches.edit', $match->id) }}" class="btn btn-primary">
        <i class="fas fa-edit mr-1"></i> Rediger
    </a>

    <a href="{{ route('promatch.matches.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Tilbage
    </a>

</div>
@endsection

