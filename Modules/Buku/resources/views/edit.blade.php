@extends('layouts.app')
@section('title', 'Edit Buku')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-header">Edit Buku</div>
    <div class="card-body">
        <form method="POST" action="{{ route('buku.update', $buku) }}">
            @csrf @method('PUT')
            @include('buku::_form', ['buku' => $buku])
        </form>
    </div>
</div>
@endsection
