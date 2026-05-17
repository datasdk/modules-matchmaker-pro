@isset($task)
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->id }}">
        {{ $task->title ?? 'Task #' . $task->id }}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="taskModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $task->title ?? 'Task #' . $task->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Beskrivelse:</strong> {{ $task->description ?? '-' }}</p>
                    <p><strong>Status:</strong> {{ $task->status ?? '-' }}</p>
                    <p><strong>Type:</strong> {{ $task->type ?? '-' }}</p>
                    <p><strong>Pris:</strong> {{ $task->price ?? '-' }} DKK</p>
                    <p><strong>Antal:</strong> {{ $task->amount ?? '-' }}</p>

                    <h6>Adresse</h6>
                    <p>
                        {{ $task->address['street'] ?? '' }}<br>
                        {{ $task->address['post_code'] ?? '' }} {{ $task->address['city'] ?? '' }}<br>
                        {{ strtoupper($task->address['country'] ?? '') }}
                    </p>

                    <h6>Datoer</h6>
                    <p>
                        Start: {{ $task->available['from'] ?? '-' }}<br>
                        Slut: {{ $task->available['to'] ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endisset
