<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
        
        <!-- Desktop Navigation -->
        <div class="hidden sm:flex sm:items-center space-x-6">
            @auth
                <x-nav-link :href="Auth::user()->role === 'admin' ? route('admin.dashboard') : route('player.dashboard')" :active="request()->routeIs('admin.dashboard') || request()->routeIs('player.dashboard')">
                    Dashboard
                </x-nav-link>
            @endauth

            <!-- User Dropdown -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center text-white text-sm font-medium">
                        {{ Auth::user()->name }}
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path d="M5 7l5 5 5-5" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        Profile
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="open = !open" class="sm:hidden text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                <path x-show="open" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="sm:hidden">
        @auth
            <x-responsive-nav-link :href="Auth::user()->role === 'admin' ? route('admin.dashboard') : route('player.dashboard')" :active="request()->routeIs('admin.dashboard') || request()->routeIs('player.dashboard')">
                Dashboard
            </x-responsive-nav-link>
        @endauth

        <div class="border-t border-gray-800">
            <div class="px-4 py-3 text-white">{{ Auth::user()->name }}</div>
            <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    Log Out
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>