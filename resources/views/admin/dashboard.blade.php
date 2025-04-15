@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

        @if (session('success'))
            <div class="text-green-500 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Admin?</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->id }}</td>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                        <td class="border px-4 py-2">
                            <form method="POST" action="{{ route('admin.users.delete', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
