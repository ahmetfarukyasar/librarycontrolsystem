<div class="row g-4"> <!-- Bootstrap grid system for responsive layout -->
    @forelse($copies as $copy)
    <div class="col-md-6 col-lg-4">
        <div class="book-card" style="background-image: url('{{ $copy->book->book_cover ?? 'https://via.placeholder.com/400x600?text=No+Cover' }}');">
            <a href="{{ url('/books/'.$copy->id) }}" class="book-link">
                <div class="status-badge 
                    @if($copy->status == 'available') status-available
                    @elseif($copy->status == 'borrowed') status-borrowed
                    @elseif($copy->status == 'reserved') status-reserved
                    @endif">
                    @if($copy->status == 'available')
                        <i class="fas fa-check-circle"></i> Müsait
                    @elseif($copy->status == 'borrowed')
                        <i class="fas fa-clock"></i> Ödünç Alındı
                    @elseif($copy->status == 'reserved')
                        <i class="fas fa-bookmark"></i> Rezerve
                    @endif
                </div>

                <div class="card-body">
                    
                        <h5 class="book-title">{{ $copy->book->book_name }}</h5>
                        <p class="book-author">{{ $copy->book->author->name }}</p>
                    
                    
                    <br><br>
                    <div class="book-info">
                        <div class="info-row" style="--index: 1">
                            <span class="info-label">ISBN:</span>
                            <span class="info-value">{{ $copy->book->isbn }}</span>
                        </div>
                        <div class="info-row" style="--index: 2">
                            <span class="info-label">Kategori:</span>
                            <span class="info-value">{{ $copy->book->category->category_name }}</span>
                        </div>
                        <div class="info-row" style="--index: 3">
                            <span class="info-label">Sayfa:</span>
                            <span class="info-value">{{ $copy->book->page_count }}</span>
                        </div>
                        <div class="info-row" style="--index: 4">
                            <span class="info-label">Yayınevi:</span>
                            <span class="info-value">{{ $copy->book->publisher->name }}</span>
                        </div>
                        <div class="info-row" style="--index: 5">
                            <span class="info-label">Yayın Yılı:</span>
                            <span class="info-value">{{ $copy->book->publish_year }}</span>
                        </div>
                        <div class="info-row" style="--index: 6">
                            <span class="info-label">Raf:</span>
                            <span class="info-value">{{ $copy->shelf_location ?? 'Belirtilmemiş' }}</span>
                        </div>
                    </div>
                    
                </div>
            </a>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-warning text-center">Sonuç bulunamadı.</div>
    </div>
    @endforelse
</div>
@if($copies->total() > 0)
<div class="custom-pagination">
    <div class="pagination-info">
        Toplam {{ $copies->total() }} kitap içinden {{ ($copies->currentPage()-1) * $copies->perPage() + 1 }} - 
        {{ min($copies->currentPage() * $copies->perPage(), $copies->total()) }} arası gösteriliyor
    </div>
    <div class="pagination-controls">
        <a href="javascript:void(0)" 
           class="page-btn {{ $copies->currentPage() <= 1 ? 'disabled' : '' }}" 
           onclick="{{ $copies->currentPage() > 1 ? 'changePage('.($copies->currentPage() - 1).')' : 'void(0)' }}">
            <i class="fas fa-chevron-left"></i>
        </a>

        @php
            $start = max($copies->currentPage() - 2, 1);
            $end = min($start + 4, $copies->lastPage());
            $start = max(min($end - 4, $start), 1);
        @endphp

        @if($start > 1)
            <a href="javascript:void(0)" class="page-btn" onclick="changePage(1)">1</a>
            @if($start > 2)
                <span class="page-dots">...</span>
            @endif
        @endif

        @for($i = $start; $i <= $end; $i++)
            <a href="javascript:void(0)" 
               class="page-btn {{ $i == $copies->currentPage() ? 'active' : '' }}"
               onclick="changePage({{ $i }})">
               {{ $i }}
            </a>
        @endfor

        @if($end < $copies->lastPage())
            @if($end < $copies->lastPage() - 1)
                <span class="page-dots">...</span>
            @endif
            <a href="javascript:void(0)" class="page-btn" onclick="changePage({{ $copies->lastPage() }})">
                {{ $copies->lastPage() }}
            </a>
        @endif

        <a href="javascript:void(0)" 
           class="page-btn {{ $copies->currentPage() >= $copies->lastPage() ? 'disabled' : '' }}"
           onclick="{{ $copies->currentPage() < $copies->lastPage() ? 'changePage('.($copies->currentPage() + 1).')' : 'void(0)' }}">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
</div>
@endif
