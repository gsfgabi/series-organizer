<div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-primary-200 p-6 mb-6">
    <div class="mb-5">
        <h3 class="text-lg font-bold text-primary-800 mb-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            Método 1: Iframe Dinâmico
        </h3>
        <p class="text-sm text-primary-600">Visualize Google Drive, Docs, Sheets, PDFs e qualquer página web diretamente nesta página</p>
    </div>

    <div class="mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <input 
                type="url" 
                wire:model="url" 
                placeholder="Cole a URL do Google Docs ou Drive aqui" 
                class="flex-1 px-4 py-3 border-2 border-primary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-primary-900 placeholder-primary-400 transition-all"
            >
            <button 
                wire:click="loadUrl" 
                wire:loading.attr="disabled"
                class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="loadUrl">Carregar</span>
                <span wire:loading wire:target="loadUrl">Carregando...</span>
            </button>
            @if($url)
                <button 
                    wire:click="clear" 
                    class="px-4 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all font-semibold shadow-lg"
                >
                    Limpar
                </button>
            @endif
        </div>
        @error('url') 
            <p class="text-red-600 text-sm mt-3 font-medium">{{ $message }}</p>
        @enderror
        @if($error)
            <p class="text-red-600 text-sm mt-3 font-medium">{{ $error }}</p>
        @endif
    </div>

    @if($fileInfo)
        <div class="mb-3 p-4 bg-primary-50 border-2 border-primary-200 rounded-xl">
            <div class="flex items-center gap-3">
                <span class="text-2xl">{{ $fileInfo['icon'] }}</span>
                <div>
                    <p class="font-semibold text-primary-800">{{ $fileInfo['name'] }}</p>
                    <p class="text-xs text-primary-600">Arquivo do Google detectado e convertido para visualização</p>
                </div>
            </div>
        </div>
    @endif

    @if($embedUrl && !$error)
        <div class="border-2 border-primary-200 rounded-xl overflow-hidden">
            <div class="bg-primary-100 px-4 py-3 flex items-center justify-between border-b-2 border-primary-200">
                <span class="text-sm font-medium text-primary-700 truncate flex-1 mr-2">{{ $url }}</span>
                <button 
                    wire:click="clear" 
                    class="text-primary-600 hover:text-primary-800 hover:bg-primary-200 p-2 rounded-lg transition-colors flex-shrink-0"
                    title="Fechar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            @if($fileInfo && $fileInfo['type'] === 'pdf')
                <div class="pdf-viewer-container bg-white p-4 max-h-[800px] overflow-y-auto" 
                     wire:key="pdf-{{ md5($embedUrl) }}"
                     data-pdf-url="{{ $embedUrl }}">
                    <div class="p-4 text-center">
                        <p class="text-primary-600 font-medium">Carregando PDF...</p>
                    </div>
                </div>
                <script>
                    (function() {
                        const container = document.querySelector('[data-pdf-url="{{ $embedUrl }}"]');
                        if (container && typeof renderPDFAsImages !== 'undefined') {
                            const pdfUrl = container.getAttribute('data-pdf-url');
                            renderPDFAsImages(pdfUrl, container);
                        }
                    })();
                </script>
            @else
                <iframe 
                    src="{{ $embedUrl }}" 
                    class="w-full h-[600px] border-0 bg-white"
                    frameborder="0"
                    allowfullscreen
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals"
                    referrerpolicy="no-referrer"
                    loading="lazy"
                    wire:key="iframe-{{ md5($embedUrl) }}"
                ></iframe>
            @endif
        </div>
    @endif
</div>
