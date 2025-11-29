@php
    // Required parameters
    $editRoute = $editRoute ?? null;
    $deleteRoute = $deleteRoute ?? null;
    $indexRoute = $indexRoute ?? null;
    $itemId = $itemId ?? null;
    
    // Optional parameters
    $title = $title ?? 'Actions';
    $entityName = $entityName ?? 'item';
    $itemData = $itemData ?? [];
    $layout = $layout ?? 'flex'; // 'flex' or 'grid'
    $editButtonText = $editButtonText ?? 'Edit ' . ucfirst($entityName);
    $deleteButtonText = $deleteButtonText ?? 'Delete ' . ucfirst($entityName);
    $backButtonText = $backButtonText ?? 'View All ' . ucfirst(str_replace('_', ' ', $entityName)) . 's';
    $showEdit = $showEdit ?? true;
    $showDelete = $showDelete ?? true;
    $showBack = $showBack ?? true;
    $modalId = $modalId ?? 'delete' . ucfirst(str_replace(['-', '_'], '', $entityName)) . 'Modal';
@endphp

<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">{{ $title }}</h5>
        <div class="{{ $layout === 'grid' ? 'd-grid gap-2' : 'd-flex flex-wrap gap-2' }}">
            @if($showEdit && $editRoute && $itemId)
                <a href="{{ route($editRoute, $itemId) }}" class="btn btn-primary">
                    <i class="ri-pencil-line align-middle me-1"></i> {{ $editButtonText }}
                </a>
            @endif
            
            @if($showDelete && $deleteRoute && $itemId)
                <button type="button" 
                        class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#{{ $modalId }}">
                    <i class="ri-delete-bin-line align-middle me-1"></i> {{ $deleteButtonText }}
                </button>
                @include('admin.components.deleteModal', [
                    'modalId' => $modalId,
                    'route' => $deleteRoute,
                    'itemId' => $itemId,
                    'entityName' => $entityName,
                    'itemData' => $itemData,
                    'showButton' => false
                ])
            @endif
            
            @if($showBack && $indexRoute)
                <a href="{{ route($indexRoute) }}" class="btn btn-secondary">
                    <i class="ri-list-check align-middle me-1"></i> {{ $backButtonText }}
                </a>
            @endif
        </div>
    </div>
</div>

