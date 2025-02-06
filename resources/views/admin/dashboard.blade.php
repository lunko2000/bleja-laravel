@extends('layouts.app')

@section('content')
<div class="container mx-auto text-center mt-10">
    <h2 class="text-2xl font-bold">Admin Dashboard</h2>
    <p>Welcome, Admin! Manage users and matches from here.</p>

    <!-- Button to Create a New User -->
    <div class="mt-6">
        <a href="{{ route('admin.users.create') }}" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            ➕ Add New User
        </a>
    </div>
</div>
@endsection
