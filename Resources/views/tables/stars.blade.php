@php
    $activeStars = $rating;
@endphp

<div>
    @for($i = 1; $i <= 5; $i++)
        <i class="fa fa-star {{ $i <= $activeStars ? 'text-warning' : 'text-secondary' }}"></i>
    @endfor
</div>
