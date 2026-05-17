<section>
    @if($loading)
        <div>Henter kontaktpersoner...</div>
    @elseif($users)
        @if(count($users))
            <select wire:model="input" id="user-select" class="form-control">
                <option value="0">Vælg kontaktperson</option>
                @foreach($users as $user)
                    <option value="{{ $user['id'] }}">{{ $user['first_name'] }} {{ $user['last_name'] }}</option>
                @endforeach
            </select>
        @endif
    @endif
</section>
