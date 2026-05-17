@if($type === 'hired')
    @if($match->hired)
        <span class="badge badge-success badge-pill px-3 py-1">
            <i class="fas fa-check-circle mr-1"></i>Hired
        </span>
    @else
        <span class="badge badge-secondary badge-pill px-3 py-1">
            Not Hired
        </span>
    @endif
@elseif($type === 'rejected')
    @if($match->rejected)
        <span class="badge badge-danger badge-pill px-3 py-1">
            <i class="fas fa-ban mr-1"></i>Rejected
        </span>
    @else
        <span class="badge badge-secondary badge-pill px-3 py-1">
            Not Rejected
        </span>
    @endif
@endif