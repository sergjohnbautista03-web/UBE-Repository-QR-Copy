<div class="research-card">
    <div class="card-type-badge type-{{ $research->type }}">{{ $research->getTypeLabel() }}</div>
    <h4 class="card-title">
        <a href="{{ route('research.show', $research) }}">{{ Str::limit($research->title, 80) }}</a>
    </h4>
    <p class="card-author"> {{ $research->author_name }}</p>
    <p class="card-dept"> {{ Str::limit($research->department, 45) }}</p>
    <p class="card-abstract">{{ Str::limit($research->abstract, 120) }}</p>
    <div class="card-footer">
        <span class="card-year"> {{ $research->year_published }}</span>
        <div class="card-stats">
            <span title="Views">Views {{ number_format($research->view_count) }}</span>
            <span title="Downloads">Downloads {{ number_format($research->download_count) }}</span>
        </div>
        @auth
        @unless(auth()->user()->isAdmin())
        @php
    $isPinned = auth()->check() ? auth()->user()->pinnedResearches->contains($research->id) : false;
@endphp
<button 
    class="pin-btn {{ $isPinned ? 'pinned' : '' }}"
    onclick="togglePin({{ $research->id }}, this)"
    data-pinned="{{ $isPinned ? 'true' : 'false' }}">
    {{ $isPinned ? 'Pinned' : 'Pin' }}
</button>
        @endunless
        @endauth
    </div>
</div>