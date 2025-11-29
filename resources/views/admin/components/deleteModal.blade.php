@php
    $modalId = $modalId ?? 'deleteModal';
    $entityName = $entityName ?? 'item';
    $itemData = $itemData ?? [];
    $title = $title ?? "Confirm Delete";
    $message = $message ?? "Are you sure you want to delete this {$entityName}?";
    $deleteButtonText = $deleteButtonText ?? "Delete " . ucfirst($entityName);
    $showButton = $showButton ?? true;
    $buttonClass = $buttonClass ?? 'btn btn-sm btn-danger';
    $buttonIcon = $buttonIcon ?? 'ri-delete-bin-line';
@endphp

@if($showButton)
<button type="button" 
        class="{{ $buttonClass }}" 
        data-bs-toggle="modal" 
        data-bs-target="#{{ $modalId }}">
    <i class="{{ $buttonIcon }}"></i>
</button>
@endif

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ $message }}</p>
                @if(!empty($itemData))
                    <div class="alert alert-warning mb-0">
                        @foreach($itemData as $label => $value)
                            <strong>{{ $label }}:</strong> {{ $value }}@if(!$loop->last)<br>@endif
                        @endforeach
                    </div>
                @endif
                <p class="mt-3 mb-0 text-danger">
                    <i class="ri-error-warning-line me-1"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route($route, $itemId) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-delete-bin-line align-middle me-1"></i> {{ $deleteButtonText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>