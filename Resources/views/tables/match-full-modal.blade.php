<div class="modal fade" id="matchModal{{ $match->id }}" tabindex="-1" role="dialog" aria-labelledby="matchModalLabel{{ $match->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="matchModalLabel{{ $match->id }}">
                    <i class="fas fa-handshake mr-2"></i>Match Details - ID: {{ $match->id }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Task Column -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-tasks mr-2"></i>Task</h6>
                            </div>
                            <div class="card-body">
                                @if($match->task)
                                    @include('tasks::tables.match-task-details', ['task' => $match->task])
                                @else
                                    <div class="alert alert-warning">No task found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Matched Task Column -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-handshake mr-2"></i>Matched Task</h6>
                            </div>
                            <div class="card-body">
                                @if($match->matchedTask)
                                    @include('tasks::tables.match-task-details', ['task' => $match->matchedTask])
                                @else
                                    <div class="alert alert-warning">No matched task found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Match Status Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Match Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <div class="mb-2">
                                            @if($match->hired)
                                                <i class="fas fa-check-circle fa-2x text-success"></i>
                                            @else
                                                <i class="fas fa-times-circle fa-2x text-muted"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>Hired:</strong><br>
                                            {{ $match->hired ? 'Yes' : 'No' }}
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-2">
                                            @if($match->rejected)
                                                <i class="fas fa-ban fa-2x text-danger"></i>
                                            @else
                                                <i class="fas fa-check fa-2x text-muted"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>Rejected:</strong><br>
                                            {{ $match->rejected ? 'Yes' : 'No' }}
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-2">
                                            <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>Created:</strong><br>
                                            {{ $match->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="mb-2">
                                            <i class="fas fa-clock fa-2x text-warning"></i>
                                        </div>
                                        <div>
                                            <strong>Updated:</strong><br>
                                            {{ $match->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions Section -->
                @if(auth()->check())
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h6 class="mb-0"><i class="fas fa-cogs mr-2"></i>Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="btn-group" role="group">
                                    @if($match->task && $match->matchedTask)
                                        <form action="{{ route('promatch.matches.hire', [$match->task->id, $match->matchedTask->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" {{ $match->hired ? 'disabled' : '' }}>
                                                <i class="fas fa-user-check mr-1"></i>Hire
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('promatch.matches.reject', [$match->task->id, $match->matchedTask->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" {{ $match->rejected ? 'disabled' : '' }}>
                                                <i class="fas fa-user-times mr-1"></i>Reject
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('promatch.matches.edit', $match->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    
                                    <a href="{{ route('promatch.matches.show', $match->id) }}" class="btn btn-info">
                                        <i class="fas fa-external-link-alt mr-1"></i>Full View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>