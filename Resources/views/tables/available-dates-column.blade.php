@if($available)

<div class="available-dates">

    <div class="d-flex flex-column">

        @if($available->from)
            <div class="text-sm">
       
                <span class="text-muted">{{ $available->from->format('d/m/Y H:i') }}</span>
            </div>
        @endif
      
      
    </div>

</div>

@else
<div class="text-muted">N/A</div>
@endif