<nav class="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="navbar-brand">
                        ✨ Series Organizer
                    </a>
                </div>
                <div class="hidden sm:ml-8 sm:flex sm:space-x-6">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        🏠 Início
                    </a>
                    <a href="{{ route('series.index') }}" 
                       class="nav-link {{ request()->routeIs('series.*') ? 'active' : '' }}">
                        📺 Séries
                    </a>
                    <button id="openCreateSeriesModal" type="button" class="btn">
                        ✨ Nova Série
                    </button>
                </div>
            </div>
            
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">🎬</span>
                </div>
            </div>
        </div>
    </div>
</nav>

@include('series.create-modal')
