<nav class="navbar border-b-2 border-primary-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="navbar-brand text-2xl font-bold">
                        <span class="inline-block mr-2">🧪</span>
                        <span class="bg-gradient-to-r from-primary-700 to-primary-900 bg-clip-text text-transparent">TestLab</span>
                    </a>
                </div>
                <div class="hidden sm:ml-8 sm:flex sm:space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Início
                    </a>
                </div>
            </div>
            
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center shadow-lg ring-2 ring-primary-300">
                    <span class="text-white font-bold text-lg">🧪</span>
                </div>
            </div>
        </div>
    </div>
</nav>

