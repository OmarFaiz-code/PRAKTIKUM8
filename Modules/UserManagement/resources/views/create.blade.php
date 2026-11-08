@extends('layouts.app')
@section('title', 'Tambah User')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-header">Tambah User</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('usermanagement::_form', ['user' => null])
        </form>
    </div>
</div>
@endsection
