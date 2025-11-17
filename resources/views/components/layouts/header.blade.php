<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <button id="menu-toggle"
                    class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    <span id="current-date"></span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="text-gray-600 text-sm font-medium">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <span id="operator-name" class="text-sm text-gray-700"> {{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
