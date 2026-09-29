@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color); flex-wrap: wrap; gap: 1rem;">
        <div>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> results
            </p>
        </div>

        <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="opacity: 0.5; cursor: not-allowed; padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.85rem; background: rgba(30, 41, 59, 0.4); border: 1px solid var(--border-color); color: var(--text-dim);">
                    &laquo; Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-secondary btn-sm" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                    &laquo; Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="padding: 0.4rem 0.6rem; font-size: 0.85rem; color: var(--text-dim);">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 700; background: var(--primary); color: #000000;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="btn btn-secondary btn-sm" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-secondary btn-sm" style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                    Next &raquo;
                </a>
            @else
                <span style="opacity: 0.5; cursor: not-allowed; padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.85rem; background: rgba(30, 41, 59, 0.4); border: 1px solid var(--border-color); color: var(--text-dim);">
                    Next &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif
