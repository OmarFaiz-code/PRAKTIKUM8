@extends('layouts.app')
@section('title', 'Data Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="m-0">Daftar Buku</h4>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('buku.create') }}" class="btn btn-primary">+ Tambah Buku</a>
    @endif
</div>

{{-- Form pencarian (GET) --}}
<form method="GET" action="{{ route('buku.index') }}" class="input-group mb-3">
    <input type="text" name="q" value="{{ $q }}" class="form-control"
           placeholder="Cari judul, penulis, atau penerbit...">
    <button class="btn btn-outline-primary">Cari</button>
    @if($q)
        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Judul</th><th>Penulis</th><th>Penerbit</th>
                    <th>Tahun</th><th>Stok</th>
                    @if(auth()->user()->isAdmin()) <th style="width:150px">Aksi</th> @endif
                </tr>
            </thead>
            <tbody>
            @forelse($buku as $b)
                <tr>
                    {{-- firstItem() + index agar nomor lanjut antar halaman --}}
                    <td>{{ $buku->firstItem() + $loop->index }}</td>
                    <td>{{ $b->judul }}</td>
                    <td>{{ $b->penulis }}</td>
                    <td>{{ $b->penerbit }}</td>
                    <td>{{ $b->tahun_terbit }}</td>
                    <td>{{ $b->stok }}</td>
                    @if(auth()->user()->isAdmin())
                        <td>
                            <a href="{{ route('buku.edit', $b) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('buku.destroy', $b) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus buku ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Data buku tidak ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $buku->links() }}</div>
@endsection
