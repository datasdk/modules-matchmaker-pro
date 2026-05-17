<section>
    @if($loading)
        <div>Henter tags...</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Specifikationer (tags)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedTags as $groupName => $tags)
                    <tr>
                        <td width="150">{{ $groupName }}</td>
                        <td>
                            @foreach($tags as $item)
                                <div class="w-25 float-left">
                                    <label>
                                        <input type="checkbox" value="{{ $item['slug'] }}" wire:model="input">
                                        {{ $item['name'] }}
                                    </label>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</section>
