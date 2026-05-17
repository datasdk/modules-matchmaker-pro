@isset($user)
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
        {{ $user->first_name }} {{ $user->last_name }}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                    <p><strong>Telefon:</strong> {{ $user->phone ?? '-' }}</p>
                    <p><strong>Firma:</strong> {{ $user->company->name ?? '-' }}</p>
                    <p><strong>Rolle:</strong> {{ implode(', ', $user->roles->pluck('name')->toArray()) ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endisset
