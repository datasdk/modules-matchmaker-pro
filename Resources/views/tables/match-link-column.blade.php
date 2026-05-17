@if($match)
<div class="match-link">
    <button type="button" 
            class="btn btn-sm btn-info"
            data-toggle="modal" 
            data-target="#matchModal{{ $match->id }}">
        <i class="fas fa-handshake mr-1"></i> {{ $label }} #{{ $match->id }}
    </button>
    
    <!-- Match Modal -->
    <div class="modal fade" id="matchModal{{ $match->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Match Details - ID: {{ $match->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="150">ID:</th>
                            <td>{{ $match->id }}</td>
                        </tr>
                        <tr>
                            <th>UID:</th>
                            <td>{{ $match->uid }}</td>
                        </tr>
                        <tr>
                            <th>Task ID:</th>
                            <td>{{ $match->task_id }}</td>
                        </tr>
                        <tr>
                            <th>Match Task ID:</th>
                            <td>{{ $match->match_with_task_id }}</td>
                        </tr>
                        <tr>
                            <th>Hired:</th>
                            <td>{{ $match->hired ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th>Rejected:</th>
                            <td>{{ $match->rejected ? 'Yes' : 'No' }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $match->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ route('promatch.matches.show', $match->id) }}" class="btn btn-primary">
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