<div class="task-details">
    <h5 class="font-weight-bold mb-3">{{ $task->name }}</h5>
    
    <!-- Basic Info -->
    <div class="mb-3">
        <h6 class="font-weight-bold border-bottom pb-1">Basic Information</h6>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="p-1"><strong>ID:</strong></td>
                        <td class="p-1">{{ $task->id }}</td>
                    </tr>
                    <tr>
                        <td class="p-1"><strong>Type:</strong></td>
                        <td class="p-1">
                            <span class="badge {{ $task->type === 'job' ? 'badge-primary' : 'badge-success' }}">
                                {{ ucfirst($task->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1"><strong>Case #:</strong></td>
                        <td class="p-1">{{ $task->case_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="p-1"><strong>Status:</strong></td>
                        <td class="p-1">{{ ucfirst($task->status) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="p-1"><strong>Price:</strong></td>
                        <td class="p-1">{{ number_format($task->price, 2) }} kr</td>
                    </tr>
                    <tr>
                        <td class="p-1"><strong>Amount:</strong></td>
                        <td class="p-1">{{ $task->amount }}</td>
                    </tr>
                    <tr>
                        <td class="p-1"><strong>Active:</strong></td>
                        <td class="p-1">
                            @if($task->active)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-danger">No</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Addresses -->
    @if($task->addresses && $task->addresses->count() > 0)
    <div class="mb-3">
        <h6 class="font-weight-bold border-bottom pb-1">Addresses</h6>
        @foreach($task->addresses as $address)
        <div class="card mb-2">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-1"><strong>Street:</strong> {{ $address->street ?? 'N/A' }}</div>
                        @if($address->street_2)
                            <div class="mb-1"><strong>Street 2:</strong> {{ $address->street_2 }}</div>
                        @endif
                        <div class="mb-1"><strong>City:</strong> {{ $address->city ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-1"><strong>Postal Code:</strong> {{ $address->postal_code ?? 'N/A' }}</div>
                        <div class="mb-1"><strong>State:</strong> {{ $address->state ?? 'N/A' }}</div>
                        <div class="mb-1"><strong>Country:</strong> {{ $address->country ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- Contacts -->
    @if($task->contacts && $task->contacts->count() > 0)
    <div class="mb-3">
        <h6 class="font-weight-bold border-bottom pb-1">Contacts</h6>
        @foreach($task->contacts as $contact)
        <div class="card mb-2">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-1"><strong>Name:</strong> {{ $contact->name ?? 'N/A' }}</div>
                        <div class="mb-1"><strong>Email:</strong> {{ $contact->email ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-1"><strong>Phone:</strong> {{ $contact->phone ?? 'N/A' }}</div>
                        <div class="mb-1"><strong>Type:</strong> {{ $contact->type ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- Skills -->
    @if($task->skills && $task->skills->count() > 0)
    <div class="mb-3">
        <h6 class="font-weight-bold border-bottom pb-1">Skills/Categories</h6>
        <div class="d-flex flex-wrap gap-1">
            @foreach($task->skills as $skill)
            <span class="badge badge-secondary">{{ $skill->name }}</span>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Content -->
    <div class="mb-3">
        <h6 class="font-weight-bold border-bottom pb-1">Content</h6>
        <div class="border rounded p-2">
            <strong>Resume:</strong>
            <p class="mb-2">{{ $task->resume ?? 'No resume' }}</p>
            
            <strong>Description:</strong>
            <p>{{ $task->description ?? 'No description' }}</p>
        </div>
    </div>
</div>

<style>
    .task-details table.table-sm td {
        padding: 0.2rem 0.5rem;
    }
    .task-details .card {
        border: 1px solid #e0e0e0;
    }
</style>