@php
    use Illuminate\Support\Str;
@endphp

@if($task)
<div class="task-info">
    <div class="font-medium text-gray-900 mb-1">
        <span class="inline-flex items-center">
            {{ Str::limit($task->name, 30) }}
            @if($task->type === 'job')
                <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">Job</span>
            @elseif($task->type === 'application')
                <span class="ml-2 px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Application</span>
            @endif
        </span>
    </div>
    
    <div class="text-sm text-gray-600 space-y-1">
        @if($task->user)
            <div>
                <i class="fas fa-user mr-1 text-xs"></i>
                {{ $task->user->name ?? 'N/A' }}
            </div>
        @endif
        
        @if($task->company)
            <div>
                <i class="fas fa-building mr-1 text-xs"></i>
                {{ $task->company->name ?? 'N/A' }}
            </div>
        @endif
        
        @if($task->price)
            <div>
                <i class="fas fa-money-bill mr-1 text-xs"></i>
                {{ number_format($task->price, 2) }} kr
            </div>
        @endif
        
        @if($task->status)
            <div>
                <i class="fas fa-info-circle mr-1 text-xs"></i>
                {{ ucfirst($task->status) }}
            </div>
        @endif
    </div>
    
    <button type="button" 
            class="mt-2 text-xs text-blue-600 hover:text-blue-800"
            data-toggle="modal" 
            data-target="#taskModal{{ $type }}{{ $task->id }}">
        <i class="fas fa-eye mr-1"></i>Vis detaljer
    </button>
</div>

<!-- Modal for Task Details -->
<div class="modal fade" id="taskModal{{ $type }}{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel{{ $type }}{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel{{ $type }}{{ $task->id }}">
                    {{ $type === 'task' ? 'Task' : 'Match' }} Details - {{ $task->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Basic Information</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td class="border-0 p-1">ID:</td>
                                    <td class="border-0 p-1 font-weight-bold">{{ $task->id }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Type:</td>
                                    <td class="border-0 p-1">
                                        <span class="badge {{ $task->type === 'job' ? 'badge-primary' : 'badge-success' }}">
                                            {{ ucfirst($task->type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Name:</td>
                                    <td class="border-0 p-1">{{ $task->name }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Case Number:</td>
                                    <td class="border-0 p-1">{{ $task->case_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Status:</td>
                                    <td class="border-0 p-1">{{ ucfirst($task->status) }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Price:</td>
                                    <td class="border-0 p-1">{{ number_format($task->price, 2) }} kr</td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Amount:</td>
                                    <td class="border-0 p-1">{{ $task->amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Relations</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td class="border-0 p-1">User:</td>
                                    <td class="border-0 p-1">
                                        @if($task->user)
                                            {{ $task->user->name }} (ID: {{ $task->user->id }})
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Company:</td>
                                    <td class="border-0 p-1">
                                        @if($task->company)
                                            {{ $task->company->name }} (ID: {{ $task->company->id }})
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Matches Count:</td>
                                    <td class="border-0 p-1">{{ $task->matches_count ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 p-1">Hires Count:</td>
                                    <td class="border-0 p-1">{{ $task->hires_count ?? 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="font-weight-bold">Content</h6>
                        <div class="border rounded p-3">
                            <strong>Resume:</strong>
                            <p class="mb-2">{{ $task->resume ?? 'No resume' }}</p>
                            
                            <strong>Description:</strong>
                            <p>{{ $task->description ?? 'No description' }}</p>
                        </div>
                    </div>
                </div>
                
                @if($task->addresses && $task->addresses->count() > 0)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="font-weight-bold">Address Information</h6>
                        @foreach($task->addresses as $address)
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div><strong>Street:</strong> {{ $address->street ?? 'N/A' }}</div>
                                        <div><strong>Street 2:</strong> {{ $address->street_2 ?? 'N/A' }}</div>
                                        <div><strong>City:</strong> {{ $address->city ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Postal Code:</strong> {{ $address->postal_code ?? 'N/A' }}</div>
                                        <div><strong>State:</strong> {{ $address->state ?? 'N/A' }}</div>
                                        <div><strong>Country:</strong> {{ $address->country ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($task->skills && $task->skills->count() > 0)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="font-weight-bold">Skills/Categories</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($task->skills as $skill)
                            <span class="badge badge-secondary">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Luk</button>
                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary">
                    <i class="fas fa-external-link-alt mr-1"></i>Gå til Task
                </a>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-muted">N/A</div>
@endif

<style>
    .task-info {
        min-width: 200px;
    }
    .modal-body .table-sm td {
        padding: 0.25rem 0.5rem;
    }
</style>