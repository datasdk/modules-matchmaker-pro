@if($rating->stars)
<div class="rating-stars">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $rating->stars)
            <i class="fas fa-star text-warning mr-1"></i>
        @else
            <i class="far fa-star text-muted mr-1"></i>
        @endif
    @endfor
    <span class="badge badge-light ml-1">{{ $rating->stars }}/5</span>
</div>
@else
<div class="text-muted">N/A</div>
@endif