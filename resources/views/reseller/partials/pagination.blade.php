@if ($paginator->hasPages())
    <div class="admin-pagination">

        {{-- Previous Page --}}
        @if ($paginator->onFirstPage())
            <span class="admin-page disabled">« Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="admin-page">
                « Previous
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" --}}
            @if (is_string($element))
                <span class="admin-page dots">{{ $element }}</span>
            @endif

            {{-- Page Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="admin-page active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="admin-page">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="admin-page">
                Next »
            </a>
        @else
            <span class="admin-page disabled">Next »</span>
        @endif

    </div>
@endif
