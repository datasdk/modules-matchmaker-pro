@if($task->tags->isNotEmpty())
    {{-- Knap til at åbne modal --}}
    <button type="button" 
            class="btn btn-outline-primary btn-sm"
            data-bs-toggle="modal" 
            data-bs-target="#specificationsModal-{{ $task->id }}">
        Vis specifikationer
    </button>

    {{-- Bootstrap Modal --}}
    <div class="modal fade" id="specificationsModal-{{ $task->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Specifikationer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Først grupper efter group_name --}}
                    @php
                        $groupedByGroupName = $task->tags->groupBy('group_name');
                    @endphp

                    @foreach($groupedByGroupName as $groupName => $tagsInGroup)
                        {{-- Derefter grupper efter type inden i hver group_name --}}
                        @php
                            $groupedByType = $tagsInGroup->groupBy('type');
                        @endphp

                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">{{ $groupName ?? '-' }}</h6>
                            </div>
                            <div class="card-body">
                                @foreach($groupedByType as $type => $tags)
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">
                                            {{ $type ?? '-' }}
                                            <span class="badge bg-secondary ms-2">{{ $tags->count() }}</span>
                                        </h6>
                                        <div class="row">
                                            @foreach($tags as $tag)
                                                <div class="col-md-6 mb-3">
                                                    <div class="border rounded p-3 h-100">
                                                        <div class="fw-bold mb-1">{{ $tag->name ?? '' }}</div>
                                                        @if(!empty($tag->description))
                                                            <div class="text-muted small mb-2">{{ $tag->description }}</div>
                                                        @endif
                                                     
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Luk</button>
                </div>
            </div>
        </div>
    </div>
@endif