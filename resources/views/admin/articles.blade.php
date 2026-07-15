@extends('layouts.master')

@section('title', 'Kelola Artikel Analisis')

@section('content')
<div class="container-fluid">
    <h2 class="fw-bold mb-4">📰 Kelola Artikel Analisis</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow border-0 rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0">Daftar Artikel Risiko Logistik</h5>
            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="openAddArticleModal()">
                <i class="fa-solid fa-plus"></i> Tambah Artikel
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Judul Artikel</th>
                        <th>Penulis</th>
                        <th>Tanggal Rilis</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td><b>{{ $article->title }}</b></td>
                            <td>{{ $article->author->name ?? 'Admin GSC' }}</td>
                            <td>{{ $article->published_at ? $article->published_at->format('d M Y') : '-' }}</td>
                            <td class="text-center" style="white-space: nowrap;">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2"
                                        onclick="openEditArticleModal({{ $article->id }}, '{{ addslashes($article->title) }}', '{{ addslashes(str_replace(["\r", "\n"], [' ', '\n'], $article->content)) }}')">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                
                                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada artikel yang dipublikasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Artikel -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="articleModalLabel">📰 Artikel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="articleForm" method="POST" action="">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label for="article_title" class="form-label small fw-semibold text-muted">Judul Artikel</label>
                        <input type="text" class="form-control rounded-3" id="article_title" name="title" required placeholder="Tuliskan judul analisis...">
                    </div>
                    <div class="mb-3">
                        <label for="article_content" class="form-label small fw-semibold text-muted">Konten Analisis</label>
                        <textarea class="form-control rounded-3" id="article_content" name="content" rows="8" required placeholder="Tuliskan konten artikel analisis secara mendalam..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const articleModalEl = document.getElementById('articleModal');
    const articleModal = new bootstrap.Modal(articleModalEl);
    const form = document.getElementById('articleForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('articleModalLabel');

    function openAddArticleModal() {
        form.reset();
        form.action = "{{ route('admin.articles.store') }}";
        methodContainer.innerHTML = '';
        modalTitle.textContent = '➕ Tambah Artikel Analisis';
        
        articleModal.show();
    }

    function openEditArticleModal(id, title, content) {
        form.reset();
        form.action = `/admin/articles/${id}`;
        methodContainer.innerHTML = '@method("PUT")';
        modalTitle.textContent = '✏️ Edit Artikel Analisis';

        document.getElementById('article_title').value = title;
        document.getElementById('article_content').value = content.replace(/\\n/g, '\n');

        articleModal.show();
    }
</script>
@endsection
