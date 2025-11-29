@php
    $modalId = $modalId ?? 'restoreModal';
    $entityName = $entityName ?? 'item';
    $itemData = $itemData ?? [];
    $title = $title ?? "Confirm Restore";
    $message = $message ?? "Are you sure you want to restore this {$entityName}?";
    $restoreButtonText = $restoreButtonText ?? "Restore " . ucfirst($entityName);
    $showButton = $showButton ?? true;
    $buttonClass = $buttonClass ?? 'btn btn-sm btn-success';
    $buttonIcon = $buttonIcon ?? 'ri-restart-line';
@endphp

@if($showButton)
<button type="button" 
        class="{{ $buttonClass }}" 
        data-bs-toggle="modal" 
        data-bs-target="#{{ $modalId }}"
        title="Restore">
    <i class="{{ $buttonIcon }}"></i>
</button>
@endif

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    <i class="ri-restart-line me-2"></i>{{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ $message }}</p>
                @if(!empty($itemData))
                    <div class="alert alert-info mb-0">
                        @foreach($itemData as $label => $value)
                            <strong>{{ $label }}:</strong> {{ $value }}@if(!$loop->last)<br>@endif
                        @endforeach
                    </div>
                @endif
                <p class="mt-3 mb-0 text-success">
                    <i class="ri-information-line me-1"></i>
                    <strong>Note:</strong> This item will be restored and become active again.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route($route, $itemId) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="ri-restart-line align-middle me-1"></i> {{ $restoreButtonText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

