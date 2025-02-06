@extends('layouts.app')

@section('content')
<div class="container mx-auto text-center mt-10">
    <h2 class="text-2xl font-bold mb-4">Select Your Role</h2>
    
    <form method="POST" action="{{ route('admin.choose_role') }}">
        @csrf
        <button type="submit" name="role" value="admin" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4">
            Login as Admin
        </button>

        <button type="submit" name="role" value="player" 
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Login as Player
        </button>
    </form>
</div>
@endsection