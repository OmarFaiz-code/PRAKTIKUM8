@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="card shadow-sm" style="max-width:600px">
    <div class="card-header">Edit User</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')
            @include('usermanagement::_form', ['user' => $user])
        </form>
    </div>
</div>
@endsection
