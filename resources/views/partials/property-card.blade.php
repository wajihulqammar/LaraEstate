 
{{-- resources/views/partials/property-card.blade.php --}}
<div class="card h-100 shadow-sm">
    @if($property->featured_image || $property->images->first())
        <div class="position-relative">
            <img src="{{ asset('storage/' . ($property->featured_image ?: $property->images->first()->image_path)) }}" 
                 class="card-img-top" alt="{{ $property->title }}" style="height: 200px; object-fit: cover;">
            @if($property->is_featured)
                <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2">
                    <i class="bi bi-star-fill"></i> Featured
                </span>
            @endif
            <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                {{ ucfirst($property->property_type) }}
            </span>
        </div>
    @else
        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <i class="bi bi-house display-4 text-muted"></i>
        </div>
    @endif
    
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ \Str::limit($property->title, 40) }}</h5>
        <p class="card-text text-muted small mb-2">
            <i class="bi bi-geo-alt"></i> {{ $property->city }}
        </p>
        <p class="card-text flex-grow-1">{{ \Str::limit($property->description, 80) }}</p>
        
        <div class="row text-muted small mb-3">
            @if($property->bedrooms)
                <div class="col-auto">
                    <i class="bi bi-bed"></i> {{ $property->bedrooms }} bed
                </div>
            @endif
            @if($property->bathrooms)
                <div class="col-auto">
                    <i class="bi bi-droplet"></i> {{ $property->bathrooms }} bath
                </div>
            @endif
            @if($property->area_size)
                <div class="col-auto">
                    <i class="bi bi-rulers"></i> {{ $property->area_size }} {{ $property->area_unit }}
                </div>
            @endif
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-auto">
            <h5 class="text-primary mb-0">{{ $property->formatted_price }}</h5>
            <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary btn-sm">
                View Details
            </a>
        </div>
    </div>
</div>