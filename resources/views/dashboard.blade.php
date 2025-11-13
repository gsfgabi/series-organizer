@extends('app')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-block mb-4">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center shadow-xl ring-4 ring-primary-200 mx-auto">
                    <span class="text-4xl">🧪</span>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-primary-700 to-primary-900 bg-clip-text text-transparent mb-3">
                TestLab
            </h1>
            <p class="text-lg text-primary-700 font-medium">Plataforma de Testes de Funcionalidades</p>
            <div class="mt-4 flex items-center justify-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800 border border-primary-300">
                    <span class="w-2 h-2 bg-primary-500 rounded-full mr-2 animate-pulse"></span>
                    Sistema Ativo
                </span>
            </div>
        </div>

        <!-- Links Salvos -->
        <div class="mb-10">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-primary-200 p-8 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-primary-800 mb-1 flex items-center gap-2">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Gerenciador de Links
                        </h2>
                        <p class="text-sm text-primary-600">Adicione e organize seus links de teste</p>
                    </div>
                </div>

                <div class="mb-6 p-5 bg-primary-50 rounded-xl border border-primary-200">
                    <h3 class="text-lg font-semibold text-primary-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Adicionar Novo Link
                    </h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input 
                            type="url" 
                            id="new-link-url"
                            class="flex-1 px-4 py-3 border-2 border-primary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-primary-900 placeholder-primary-400 transition-all"
                            placeholder="Cole a URL aqui (Google Docs, Drive, etc.)"
                        >
                        <input 
                            type="text" 
                            id="new-link-title"
                            class="w-full sm:w-56 px-4 py-3 border-2 border-primary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-primary-900 placeholder-primary-400 transition-all"
                            placeholder="Título (opcional)"
                        >
                        <button 
                            id="save-link-btn"
                            class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all font-semibold shadow-lg hover:shadow-xl transform hover:scale-105"
                        >
                            Salvar
                        </button>
                    </div>
                    <p id="save-link-error" class="text-red-600 text-sm mt-3 font-medium" style="display: none;"></p>
                </div>

                <div id="saved-links-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Links serão carregados aqui -->
                </div>
            </div>

            <!-- Área de visualização do conteúdo -->
            <div id="saved-link-viewer" class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-primary-200 p-6" style="display: none;">
                <div class="mb-4 flex items-center justify-between pb-4 border-b-2 border-primary-200">
                    <h3 class="text-xl font-bold text-primary-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Visualização do Conteúdo
                    </h3>
                    <button 
                        id="close-viewer-btn"
                        class="text-primary-600 hover:text-primary-800 hover:bg-primary-100 p-2 rounded-lg transition-colors"
                        title="Fechar"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="saved-link-content" class="min-h-[500px] rounded-lg overflow-hidden border-2 border-primary-200">
                    <!-- Conteúdo será carregado aqui -->
                </div>
            </div>
        </div>

        <!-- Link Embed Solutions -->
        <div class="mb-10">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-primary-800 mb-2 flex items-center justify-center gap-2">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Visualizador de Links
                </h2>
                <p class="text-sm text-primary-600">Teste diferentes métodos de incorporação de conteúdo</p>
            </div>
            
            <div class="space-y-6">
                @livewire('iframe-embed')
                
                <!-- Solução 3: Proxy Server com Fetch API -->
                <div id="embed-solution-3" class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-primary-200 p-6">
                    <div class="mb-5">
                        <h3 class="text-lg font-bold text-primary-800 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Método 2: Proxy Server com Fetch API
                        </h3>
                        <p class="text-sm text-primary-600">Carregue documentos através de proxy server</p>
                    </div>

                    <div class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input 
                                type="url" 
                                class="url-input flex-1 px-4 py-3 border-2 border-primary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-primary-900 placeholder-primary-400 transition-all"
                                placeholder="Cole a URL do Google Docs ou Drive aqui"
                            >
                            <button class="load-btn px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                <span>Carregar</span>
                            </button>
                            <button class="clear-btn px-4 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold shadow-lg">
                                Limpar
                            </button>
                        </div>
                        <p class="error-msg text-red-600 text-sm mt-3 font-medium" style="display: none;"></p>
                    </div>

                    <div class="file-info mb-3 p-4 bg-primary-50 border-2 border-primary-200 rounded-xl text-sm text-primary-800" style="display: none;"></div>
                    <div class="iframe-container rounded-xl overflow-hidden border-2 border-primary-200" style="display: none;"></div>
                </div>

                <!-- Solução 4: Metadata Preview -->
                <!-- <div id="embed-solution-4" class="bg-white rounded-lg shadow-lg p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-primary-800 mb-2 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Solução 4: Metadata Preview com Fetch
                        </h3>
                    </div>

                    <div class="mb-4">
                        <div class="flex gap-2">
                            <input 
                                type="url" 
                                class="url-input flex-1 px-4 py-2 border border-primary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="https://docs.google.com/document/d/... ou qualquer URL"
                            >
                            <button class="load-btn px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors disabled:opacity-50">
                                <span>Preview</span>
                            </button>
                            <button class="clear-btn px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                Limpar
                            </button>
                        </div>
                        <p class="error-msg text-red-500 text-sm mt-2" style="display: none;"></p>
                    </div>

                    <div class="preview-container" style="display: none;"></div>
                    <div class="embed-container" style="display: none;"></div>
                </div> -->

                <!-- Solução 5: Validação e Conversão -->
                <div id="embed-solution-5" class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-primary-200 p-6">
                    <div class="mb-5">
                        <h3 class="text-lg font-bold text-primary-800 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Método 3: Validação e Conversão Assíncrona
                        </h3>
                        <p class="text-sm text-primary-600">Valide URLs e converta automaticamente para formato embedável</p>
                    </div>

                    <div class="mb-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input 
                                type="url" 
                                class="url-input flex-1 px-4 py-3 border-2 border-primary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-primary-900 placeholder-primary-400 transition-all"
                                placeholder="Cole qualquer URL para validar e converter"
                            >
                            <button class="load-btn px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                <span>Validar e Carregar</span>
                            </button>
                            <button class="clear-btn px-4 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold shadow-lg">
                                Limpar
                            </button>
                        </div>
                        <p class="error-msg text-red-600 text-sm mt-3 font-medium" style="display: none;"></p>
                        <div class="status-container mt-3 p-3 bg-primary-50 border border-primary-200 rounded-xl text-sm text-primary-800" style="display: none;"></div>
                    </div>

                    <div class="iframe-container rounded-xl overflow-hidden border-2 border-primary-200" style="display: none;"></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection