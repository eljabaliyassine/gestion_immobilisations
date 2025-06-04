    <!-- User Settings - Fixed at bottom INSIDE sidebar -->
    <div class="shrink-0 p-4">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center justify-between w-full text-left text-gray-light hover:text-white py-2 px-3 rounded hover:bg-gray-700 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute bottom-full left-0 w-full bg-gray-800 rounded-lg shadow-xl mb-2 border border-gray-700">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-gray-light hover:bg-gray-700 hover:text-white transition-colors rounded-t-lg">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ __('Profile') }}</span>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-3 text-gray-light hover:bg-gray-700 hover:text-white transition-colors rounded-b-lg">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>{{ __('Log Out') }}</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
