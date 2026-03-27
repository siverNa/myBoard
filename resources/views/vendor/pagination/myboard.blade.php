@if ($paginator->hasPages())
    <nav class="my-pagination" role="navigation" aria-label="Pagination Navigation">
        <div class="my-pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="my-pagination-item disabled">이전</span>
            @else
                <a class="my-pagination-item" href="{{ $paginator->previousPageUrl() }}" rel="prev">이전</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="my-pagination-item dots">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="my-pagination-item active">{{ $page }}</span>
                        @else
                            <a class="my-pagination-item" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="my-pagination-item" href="{{ $paginator->nextPageUrl() }}" rel="next">다음</a>
            @else
                <span class="my-pagination-item disabled">다음</span>
            @endif
        </div>
    </nav>
@endif