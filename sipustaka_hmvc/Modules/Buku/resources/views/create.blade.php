@extends('layouts.app')
@section('title', 'Tambah Buku')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-header">Tambah Buku</div>
    <div class="card-body">
        <form method="POST" action="{{ route('buku.store') }}">
            @csrf
            @include('buku::_form', ['buku' => null])
        </form>
    </div>
</div>
@endsection
