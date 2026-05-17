@php
    $badgeClass = 'badge-secondary';
    $status = $rating->status ?? 'pending';
    
    switch ($status) {
        case 'pending':
            $badgeClass = 'badge-warning';
            break;
        case 'completed':
            $badgeClass = 'badge-success';
            break;
        case 'cancelled':
            $badgeClass = 'badge-danger';
            break;
    }
@endphp

<span class="badge {{ $badgeClass }} badge-pill px-3">
    @if($status === 'pending')
        <i class="fas fa-clock mr-1"></i>
    @elseif($status === 'completed')
        <i class="fas fa-check-circle mr-1"></i>
    @elseif($status === 'cancelled')
        <i class="fas fa-ban mr-1"></i>
    @endif
    {{ ucfirst($status) }}
</span>