@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 p-6 bg-gray-800 text-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-center mb-4">⚙️ Profile Settings</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white rounded-md text-center">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-300">New Username</label>
            <input type="text" name="username" id="username" value="{{ auth()->user()->username }}"
                   class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300">New Password</label>
            <input type="password" name="password" id="password" 
                   class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="block w-full mt-1 p-2 bg-gray-700 border border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center mt-6">
            <button type="submit" 
                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg transition">
                💾 Save Changes
            </button>
        </div>
    </form>
</div>
@endsection