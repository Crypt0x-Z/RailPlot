@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Panel</h1>
    <h3>All Registered Users</h3>

    <ul>
        @foreach ($users as $user)
            <li>{{ $user->name }} ({{ $user->email }})</li>
        @endforeach
    </ul>
</div>
@endsection