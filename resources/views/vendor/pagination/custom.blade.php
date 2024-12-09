@if ($paginator->hasPages())
    <nav class="pagination-wrapper d-flex justify-content-between justify-items-center flex-wrap gap-2" aria-label="Page navigation">
        <div class="metadata-wrapper">
            <div class="metadata">
                <span class="metadata-item text-muted">Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results</span>
            </div>
        </div>
        <div class="pagination-wrapper">
            <ul class="pagination">
                {{-- Tombol Previous --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a></li>
                @endif

                {{-- Tombol Halaman --}}
                @foreach ($elements as $element)
                    {{-- Tanda Separator "..." --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Link ke Halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                @endif
            </ul>
        </div>
    </nav>
@endif
