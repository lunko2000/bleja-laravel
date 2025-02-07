@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-900">
    <div class="w-full max-w-md p-8 bg-gray-800 shadow-lg rounded-lg">
        <h2 class="text-3xl font-bold text-white text-center mb-6">Select Your Role</h2>

        <form method="POST" action="{{ route('admin.choose_role') }}" class="space-y-4">
            @csrf
            <button type="submit" name="role" value="admin"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition">
                Login as Admin
            </button>

            <button type="submit" name="role" value="player"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition">
                Login as Player
            </button>
        </form>
    </div>
</div>
@endsection