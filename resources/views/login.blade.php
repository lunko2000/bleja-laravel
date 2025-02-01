<x-layout title="Home Page" header="Dashboard">

    <form action="/login" method="POST" class="w-1/2 mx-auto bg-white p-6 rounded shadow mt-6">
        @csrf
        <input type="email" name="email" class="w-full border p-2 rounded mb-4" placeholder="Email" >
        <input type="text" name="password" class="w-full border p-2 rounded mb-4" placeholder="Password" >

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 hover:bg-blue-600">Login</button>
    </form>
</x-layout>
