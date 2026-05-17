@if($task)
<div class="task-link">
    <button type="button" 
            class="btn btn-sm btn-primary"
            data-toggle="modal" 
            data-target="#taskModal{{ $task->id }}">
        <i class="fas fa-tasks mr-1"></i> {{ $label }} #{{ $task->id }}
    </button>
    
    <!-- Task Modal -->
    <div class="modal fade" id="taskModal{{ $task->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Task Details - {{ $task->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="150">ID:</th>
                            <td>{{ $task->id }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $task->name }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td>
                                <span class="badge {{ $task->type === 'job' ? 'badge-primary' : 'badge-success' }}">
                                    {{ ucfirst($task->type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>{{ ucfirst($task->status) }}</td>
                        </tr>
                        <tr>
                            <th>Price:</th>
                            <td>{{ number_format($task->price, 2) }} kr</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>{{ $task->amount }}</td>
                        </tr>
                        @if($task->user)
                        <tr>
                            <th>User:</th>
                            <td>{{ $task->user->name }}</td>
                        </tr>
                        @endif
                        @if($task->company)
                        <tr>
                            <th>Company:</th>
                            <td>{{ $task->company->name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Active:</th>
                            <td>{{ $task->active ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $task->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ route('promatch.tasks.show', $task->id) }}" class="btn btn-primary">
                        <i class="fas fa-external-link-alt mr-1"></i> View Full Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-muted">No {{ strtolower($label) }}</div>
@endif