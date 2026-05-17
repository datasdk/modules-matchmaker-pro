@if($user)
<div class="user-link">
    <a href="#" 
       class="text-success hover:underline"
       data-toggle="modal" 
       data-target="#userModal{{ $user->id }}">
        <i class="fas fa-user mr-1"></i> 
        {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
        @if($user->email)
            <small class="text-muted ml-1">({{ $user->email }})</small>
        @endif
    </a>
    
    <!-- User Modal -->
    <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fas fa-user mr-2"></i>User Details - ID: {{ $user->id }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold border-bottom pb-2 mb-3">Personal Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="p-1" width="40%"><strong>ID:</strong></td>
                                    <td class="p-1">{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td class="p-1"><strong>First Name:</strong></td>
                                    <td class="p-1">{{ $user->first_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-1"><strong>Last Name:</strong></td>
                                    <td class="p-1">{{ $user->last_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-1"><strong>Email:</strong></td>
                                    <td class="p-1">{{ $user->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-1"><strong>Created:</strong></td>
                                    <td class="p-1">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="font-weight-bold border-bottom pb-2 mb-3">Contact Information</h6>
                            @if($user->contact)
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="p-1" width="40%"><strong>Phone:</strong></td>
                                    <td class="p-1">{{ $user->contact->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-1"><strong>Address:</strong></td>
                                    <td class="p-1">
                                        @if($user->contact->address)
                                            {{ $user->contact->address->street ?? '' }}
                                            {{ $user->contact->address->city ?? '' }}
                                            {{ $user->contact->address->postal_code ?? '' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @else
                            <div class="alert alert-info py-2">
                                <i class="fas fa-info-circle mr-1"></i> No contact information available
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($user->tasks && $user->tasks->count() > 0)
                    <div class="mt-4">
                        <h6 class="font-weight-bold border-bottom pb-2 mb-3">Related Tasks</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->tasks->take(5) as $task)
                                    <tr>
                                        <td>{{ $task->id }}</td>
                                        <td>{{ $task->name }}</td>
                                        <td>
                                            <span class="badge {{ $task->type === 'job' ? 'badge-primary' : 'badge-success' }}">
                                                {{ ucfirst($task->type) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($task->status) }}</td>
                                        <td>{{ $task->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($user->tasks->count() > 5)
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Showing 5 of {{ $user->tasks->count() }} tasks
                            </small>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Close
                    </button>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary">
                        <i class="fas fa-external-link-alt mr-1"></i> View Full Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-muted">No {{ strtolower($label) }}</div>
@endif