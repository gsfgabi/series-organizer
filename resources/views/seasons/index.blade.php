@extends('app')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header com informações da série -->
        <div class="mb-8 animate-fade-in">
            <div class="flex items-center space-x-4 mb-6">
                <a href="{{ route('series.show', $serie->id) }}" class="btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar para Série
                </a>
                <div class="flex-1">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                        📅 Temporadas
                    </h1>
                    <p class="text-primary-600 mt-2">Série: <span class="font-semibold">{{ $serie->title }}</span></p>
                </div>
                <a href="{{ route('series.seasons.create', $serie->id) }}" class="btn animate-bounce-in">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    ✨ Nova Temporada
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-slide-up">
                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-primary-600">Total de Temporadas</p>
                            <p class="text-2xl font-bold text-primary-800">{{ $seasons->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-primary-600">Assistidas</p>
                            <p class="text-2xl font-bold text-primary-800">0</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-primary-600">Em Andamento</p>
                            <p class="text-2xl font-bold text-primary-800">0</p>
                        </div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-primary-600">Total de Episódios</p>
                            <p class="text-2xl font-bold text-primary-800">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seasons List -->
        @if($seasons->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in">
                @foreach ($seasons as $season)
                    <div class="series-card season-card-hover group relative overflow-hidden">
                        <div class="relative overflow-hidden">
                            <!-- Background com gradiente -->
                            <div class="w-full h-32 bg-gradient-to-br from-primary-100 via-primary-200 to-primary-300 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center mb-2">
                                        <span class="text-2xl font-bold text-primary-600">{{ $season->order }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-primary-800">{{ $season->title }}</h3>
                                </div>
                            </div>
                            
                            <!-- Overlay com botões de ação -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500">
                                <div class="absolute bottom-4 left-4 right-4 flex justify-center space-x-3">
                                    <a href="#" class="action-button" title="Ver Episódios">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('series.seasons.edit', [$serie->id, $season->id]) }}" 
                                       class="action-button" title="Editar">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('series.seasons.destroy', [$serie->id, $season->id]) }}" 
                                          method="POST" 
                                          onsubmit="return confirmDelete('Tem certeza que deseja excluir esta temporada?')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-button" title="Excluir">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-primary-100 text-primary-700 text-sm font-medium rounded-full">
                                        #{{ $season->order }}
                                    </span>
                                    <span class="text-sm text-primary-600">{{ $season->title }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <span class="status-indicator status-watching"></span>
                                    <span class="text-xs text-primary-600">Em andamento</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-primary-600">Episódios:</span>
                                    <span class="font-medium text-primary-800">0 episódios</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-primary-600">Assistidos:</span>
                                    <span class="font-medium text-primary-800">0 episódios</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-primary-600">Progresso:</span>
                                    <span class="font-medium text-primary-800">0%</span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="progress-bar mb-4">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="#" class="btn-secondary flex-1 text-center text-sm">
                                    📺 Ver Episódios
                                </a>
                                <a href="{{ route('series.seasons.edit', [$serie->id, $season->id]) }}" 
                                   class="btn-secondary flex-1 text-center text-sm">
                                    ✏️ Editar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 animate-bounce-in">
                <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-primary-800 mb-4">Nenhuma temporada cadastrada</h3>
                <p class="text-primary-600 mb-8 text-lg">Comece adicionando a primeira temporada para organizar os episódios!</p>
                <a href="{{ route('series.seasons.create', $serie->id) }}" class="btn">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    ✨ Adicionar Primeira Temporada
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function confirmDelete(message) {
    return confirm(message);
}

// Animar progress bars quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});
</script>
@endsection