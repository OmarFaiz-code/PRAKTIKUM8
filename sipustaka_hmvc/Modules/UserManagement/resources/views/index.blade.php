@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="m-0">Kelola User</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th style="width:150px">Aksi</th></tr>
            </thead>
            <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $users->firstItem() + $loop->index }}</td>
                    <td>{{ $u->name }} @if($u->id === auth()->id()) <span class="text-muted">(Anda)</span> @endif</td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">{{ $u->role }}</span></td>
                    <td>
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning">Edit</a>
                        {{-- Tombol hapus disembunyikan untuk akun sendiri --}}
                        @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
