// Função para converter PDF em imagem (canvas único)
async function renderPDFAsImages(pdfUrl, container) {
    try {
        // Configura PDF.js worker
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        } else {
            throw new Error('PDF.js não está carregado');
        }

        // Limpa container e mostra loading
        container.innerHTML = '<div class="p-4 text-center"><p class="text-primary-600">Convertendo PDF em imagem...</p></div>';

        // Carrega o PDF
        const loadingTask = pdfjsLib.getDocument(pdfUrl);
        const pdf = await loadingTask.promise;

        // Limpa container
        container.innerHTML = '';

        // Cria um único canvas para todas as páginas
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        // Calcula altura total de todas as páginas
        let totalHeight = 0;
        let maxWidth = 0;
        const pageViewports = [];

        // Primeiro, calcula dimensões de todas as páginas
        for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
            const page = await pdf.getPage(pageNumber);
            const viewport = page.getViewport({ scale: 1.5 });
            
            // Escala automática baseada na tela
            const desiredWidth = container.clientWidth - 32; // -32 para padding
            const scale = desiredWidth / viewport.width;
            const scaledViewport = page.getViewport({ scale });
            
            pageViewports.push({ page, viewport: scaledViewport });
            maxWidth = Math.max(maxWidth, scaledViewport.width);
            totalHeight += scaledViewport.height + 20; // +20 para espaçamento entre páginas
        }

        // Define tamanho do canvas (todas as páginas em uma imagem)
        canvas.width = maxWidth;
        canvas.height = totalHeight;

        // Aplica estilos direto no canvas
        canvas.style.maxWidth = "100%";
        canvas.style.height = "auto";
        canvas.style.display = "block";
        canvas.style.margin = "0 auto";
        canvas.style.border = "1px solid #e5e7eb";
        canvas.style.borderRadius = "8px";
        canvas.style.boxShadow = "0 2px 8px rgba(0,0,0,0.1)";
        canvas.style.background = "#ffffff";

        // Renderiza todas as páginas no canvas
        let currentY = 0;
        for (const { page, viewport } of pageViewports) {
            // Renderiza página no canvas
            await page.render({
                canvasContext: context,
                viewport: viewport,
                transform: [1, 0, 0, 1, 0, currentY]
            }).promise;

            currentY += viewport.height + 20; // Move para próxima página
        }

        // Adiciona canvas ao container
        container.appendChild(canvas);

        return true;
    } catch (error) {
        console.error('Erro ao converter PDF em imagem:', error);
        container.innerHTML = `<div class="p-4 text-center text-red-500"><p>Erro ao converter PDF: ${error.message}</p></div>`;
        return false;
    }
}

// Função para verificar se é PDF
function isPDF(url) {
    return /\.pdf(\?.*)?$/i.test(url) || url.includes('pdf');
}

// Solução 3: Proxy Server com Fetch API
class ProxyEmbedSolution {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.urlInput = this.container.querySelector('.url-input');
        this.loadBtn = this.container.querySelector('.load-btn');
        this.clearBtn = this.container.querySelector('.clear-btn');
        this.errorMsg = this.container.querySelector('.error-msg');
        this.iframeContainer = this.container.querySelector('.iframe-container');
        this.fileInfoContainer = this.container.querySelector('.file-info');
        
        this.init();
    }

    init() {
        this.loadBtn.addEventListener('click', () => this.loadUrl());
        this.clearBtn.addEventListener('click', () => this.clear());
        this.urlInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.loadUrl();
        });
    }

    async loadUrl() {
        const url = this.urlInput.value.trim();
        if (!url) {
            this.showError('Por favor, insira uma URL válida.');
            return;
        }

        this.setLoading(true);
        this.hideError();
        this.hideIframe();

        try {
            const response = await fetch('/api/embed/proxy', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ url })
            });

            const data = await response.json();

            if (data.success) {
                this.showFileInfo(data.fileInfo);
                // Verifica se é PDF para renderizar como canvas
                if (isPDF(url) || (data.fileInfo && data.fileInfo.type === 'pdf')) {
                    this.showPDF(data.embedUrl, url);
                } else {
                    this.showIframe(data.embedUrl, url);
                }
            } else {
                this.showError(data.error || 'Erro ao carregar URL');
            }
        } catch (error) {
            this.showError('Erro ao processar requisição: ' + error.message);
        } finally {
            this.setLoading(false);
        }
    }

    showFileInfo(fileInfo) {
        if (!fileInfo) {
            this.fileInfoContainer.style.display = 'none';
            return;
        }

        this.fileInfoContainer.innerHTML = `
            <div class="flex items-center gap-2">
                <span class="text-2xl">${fileInfo.icon}</span>
                <div>
                    <p class="font-semibold text-primary-800">${fileInfo.name}</p>
                    <p class="text-xs text-primary-600">Arquivo do Google detectado e convertido para visualização</p>
                </div>
            </div>
        `;
        this.fileInfoContainer.style.display = 'block';
    }

    showPDF(pdfUrl, originalUrl) {
        this.iframeContainer.innerHTML = `
            <div class="border-2 border-primary-200 rounded-lg overflow-hidden">
                <div class="bg-primary-100 px-4 py-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-primary-700 truncate flex-1 mr-2">${originalUrl}</span>
                    <button onclick="this.closest('.iframe-container').innerHTML=''" class="text-primary-600 hover:text-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="pdf-viewer-container bg-white p-4 max-h-[800px] overflow-y-auto"></div>
            </div>
        `;
        this.iframeContainer.style.display = 'block';
        
        const pdfContainer = this.iframeContainer.querySelector('.pdf-viewer-container');
        renderPDFAsImages(pdfUrl, pdfContainer);
    }

    showIframe(embedUrl, originalUrl) {
        this.iframeContainer.innerHTML = `
            <div class="border-2 border-primary-200 rounded-lg overflow-hidden">
                <div class="bg-primary-100 px-4 py-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-primary-700 truncate flex-1 mr-2">${originalUrl}</span>
                    <button onclick="this.closest('.iframe-container').innerHTML=''" class="text-primary-600 hover:text-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <iframe 
                    src="${embedUrl}" 
                    class="w-full h-[600px] border-0"
                    frameborder="0"
                    allowfullscreen
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals"
                    referrerpolicy="no-referrer"
                    loading="lazy"
                ></iframe>
            </div>
        `;
        this.iframeContainer.style.display = 'block';
    }

    hideIframe() {
        this.iframeContainer.style.display = 'none';
        this.iframeContainer.innerHTML = '';
    }

    showError(message) {
        this.errorMsg.textContent = message;
        this.errorMsg.style.display = 'block';
    }

    hideError() {
        this.errorMsg.style.display = 'none';
    }

    setLoading(loading) {
        this.loadBtn.disabled = loading;
        this.loadBtn.innerHTML = loading 
            ? '<span>Carregando...</span>' 
            : '<span>Carregar</span>';
    }

    clear() {
        this.urlInput.value = '';
        this.hideIframe();
        this.hideError();
        this.fileInfoContainer.style.display = 'none';
    }
}

// Solução 4: Metadata Preview com Fetch
class MetadataPreviewSolution {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.urlInput = this.container.querySelector('.url-input');
        this.loadBtn = this.container.querySelector('.load-btn');
        this.clearBtn = this.container.querySelector('.clear-btn');
        this.errorMsg = this.container.querySelector('.error-msg');
        this.previewContainer = this.container.querySelector('.preview-container');
        this.embedContainer = this.container.querySelector('.embed-container');
        
        this.init();
    }

    init() {
        this.loadBtn.addEventListener('click', () => this.loadPreview());
        this.clearBtn.addEventListener('click', () => this.clear());
        this.urlInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.loadPreview();
        });
    }

    async loadPreview() {
        const url = this.urlInput.value.trim();
        if (!url) {
            this.showError('Por favor, insira uma URL válida.');
            return;
        }

        this.setLoading(true);
        this.hideError();
        this.hidePreview();
        this.hideEmbed();

        try {
            const response = await fetch('/api/embed/metadata', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ url })
            });

            const data = await response.json();

            if (data.success) {
                this.showPreview(data.metadata, url);
            } else {
                this.showError(data.error || 'Erro ao carregar preview');
            }
        } catch (error) {
            this.showError('Erro ao processar requisição: ' + error.message);
        } finally {
            this.setLoading(false);
        }
    }

    showPreview(metadata, originalUrl) {
        let previewHTML = '';

        if (metadata.icon) {
            // Preview para arquivos do Google
            previewHTML = `
                <div class="mb-4 p-4 bg-primary-50 rounded-lg border border-primary-200">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">${metadata.icon}</span>
                        <div>
                            <h4 class="font-bold text-primary-800">${metadata.title || 'Arquivo'}</h4>
                            <p class="text-xs text-primary-600 uppercase">${metadata.type || ''}</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // Preview padrão com metadados
            previewHTML = `
                <div class="mb-4 p-4 bg-primary-50 rounded-lg border border-primary-200">
                    ${metadata.image ? `<img src="${metadata.image}" alt="Preview" class="w-full h-48 object-cover rounded-lg mb-3" onerror="this.style.display='none'">` : ''}
                    ${metadata.title ? `<h4 class="font-bold text-primary-800 mb-1">${metadata.title}</h4>` : ''}
                    ${metadata.description ? `<p class="text-sm text-primary-600">${metadata.description}</p>` : ''}
                </div>
            `;
        }

        this.previewContainer.innerHTML = previewHTML;
        this.previewContainer.style.display = 'block';

        // Se tiver embedUrl, mostra o embed
        const embedUrl = metadata.embedUrl || originalUrl;
        // Verifica se é PDF para renderizar como canvas
        if (isPDF(originalUrl) || (metadata.type === 'pdf')) {
            this.showPDFEmbed(embedUrl, originalUrl);
        } else {
            this.showEmbed(embedUrl, originalUrl);
        }
    }

    showEmbed(embedUrl, originalUrl) {
        this.embedContainer.innerHTML = `
            <div class="border-2 border-primary-200 rounded-lg overflow-hidden">
                <div class="bg-primary-100 px-4 py-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-primary-700">Conteúdo Embed</span>
                    <button onclick="this.closest('.embed-container').innerHTML=''" class="text-primary-600 hover:text-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <iframe 
                    src="${embedUrl}" 
                    class="w-full h-[600px] border-0"
                    frameborder="0"
                    allowfullscreen
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals"
                    referrerpolicy="no-referrer"
                    loading="lazy"
                ></iframe>
            </div>
        `;
        this.embedContainer.style.display = 'block';
    }

    hidePreview() {
        this.previewContainer.style.display = 'none';
        this.previewContainer.innerHTML = '';
    }

    showPDFEmbed(pdfUrl, originalUrl) {
        this.embedContainer.innerHTML = `
            <div class="border-2 border-primary-200 rounded-lg overflow-hidden">
                <div class="bg-primary-100 px-4 py-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-primary-700">Conteúdo PDF</span>
                    <button onclick="this.closest('.embed-container').innerHTML=''" class="text-primary-600 hover:text-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="pdf-viewer-container bg-white p-4 max-h-[800px] overflow-y-auto"></div>
            </div>
        `;
        this.embedContainer.style.display = 'block';
        
        const pdfContainer = this.embedContainer.querySelector('.pdf-viewer-container');
        renderPDFAsImages(pdfUrl, pdfContainer);
    }

    hideEmbed() {
        this.embedContainer.style.display = 'none';
        this.embedContainer.innerHTML = '';
    }

    showError(message) {
        this.errorMsg.textContent = message;
        this.errorMsg.style.display = 'block';
    }

    hideError() {
        this.errorMsg.style.display = 'none';
    }

    setLoading(loading) {
        this.loadBtn.disabled = loading;
        this.loadBtn.innerHTML = loading 
            ? '<span>Carregando...</span>' 
            : '<span>Preview</span>';
    }

    clear() {
        this.urlInput.value = '';
        this.hidePreview();
        this.hideEmbed();
        this.hideError();
    }
}

// Solução 5: Validação e Conversão com Async/Await
class ValidationEmbedSolution {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.urlInput = this.container.querySelector('.url-input');
        this.loadBtn = this.container.querySelector('.load-btn');
        this.clearBtn = this.container.querySelector('.clear-btn');
        this.errorMsg = this.container.querySelector('.error-msg');
        this.statusContainer = this.container.querySelector('.status-container');
        this.iframeContainer = this.container.querySelector('.iframe-container');
        
        this.init();
    }

    init() {
        this.loadBtn.addEventListener('click', () => this.loadUrl());
        this.clearBtn.addEventListener('click', () => this.clear());
        this.urlInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.loadUrl();
        });
        
        // Validação em tempo real
        this.urlInput.addEventListener('input', () => this.validateInput());
    }

    validateInput() {
        const url = this.urlInput.value.trim();
        if (url && this.isValidUrl(url)) {
            this.urlInput.classList.remove('border-red-300');
            this.urlInput.classList.add('border-green-300');
        } else if (url) {
            this.urlInput.classList.remove('border-green-300');
            this.urlInput.classList.add('border-red-300');
        } else {
            this.urlInput.classList.remove('border-red-300', 'border-green-300');
        }
    }

    isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    async loadUrl() {
        const url = this.urlInput.value.trim();
        if (!url) {
            this.showError('Por favor, insira uma URL válida.');
            return;
        }

        if (!this.isValidUrl(url)) {
            this.showError('URL inválida. Por favor, verifique o formato.');
            return;
        }

        this.setLoading(true);
        this.hideError();
        this.hideIframe();
        this.showStatus('Validando URL...');

        try {
            const response = await fetch('/api/embed/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ url })
            });

            const data = await response.json();

            if (data.success) {
                this.showStatus(`✅ URL validada - Tipo: ${data.type}`);
                if (data.fileInfo) {
                    this.showFileInfo(data.fileInfo);
                }
                setTimeout(() => {
                    // Verifica se é PDF para renderizar como canvas
                    if (isPDF(data.originalUrl) || (data.fileInfo && data.fileInfo.type === 'pdf')) {
                        this.showPDF(data.embedUrl, data.originalUrl);
                    } else {
                        this.showIframe(data.embedUrl, data.originalUrl);
                    }
                    this.hideStatus();
                }, 500);
            } else {
                this.showError(data.error || 'Erro ao validar URL');
                this.hideStatus();
            }
        } catch (error) {
            this.showError('Erro ao processar requisição: ' + error.message);
            this.hideStatus();
        } finally {
            this.setLoading(false);
        }
    }

    showFileInfo(fileInfo) {
        this.statusContainer.innerHTML += `
            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded">
                <div class="flex items-center gap-2">
                    <span class="text-xl">${fileInfo.icon}</span>
                    <span class="text-sm font-medium text-primary-800">${fileInfo.name}</span>
                </div>
            </div>
        `;
    }

    showStatus(message) {
        this.statusContainer.innerHTML = `<p class="text-sm text-primary-600">${message}</p>`;
        this.statusContainer.style.display = 'block';
    }

    hideStatus() {
        this.statusContainer.style.display = 'none';
        this.statusContainer.innerHTML = '';
    }

    showPDF(pdfUrl, originalUrl) {
        this.iframeContainer.innerHTML = `
            <div class="border-2 border-primary-200 rounded-lg overflow-hidden mt-4">
                <div class="bg-primary-100 px-4 py-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-primary-700 truncate flex-1 mr-2">${originalUrl}</span>
                    <button onclick="this.closest('.iframe-container').innerHTML=''" class="text-primary-600 hover:text-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="pdf-viewer-container bg-white p-4 max-h-[800px] overflow-y-auto"></div>
            </div>
        `;
        this.iframeContainer.style.display = 'block';
        
        const pdfContainer = this.iframeContainer.querySelector('.pdf-viewer-container');
        renderPDFAsImages(pdfUrl, pdfContainer);
    }

    showIframe(embedUrl, originalUrl) {
        this.iframeContainer.innerHTML = `
            <div class="border-2 border-primary-200 rounded-lg overflow-hidden mt-4">
                <div class="bg-primary-100 px-4 py-2 flex items-center justify-between">
                    <span class="text-sm font-medium text-primary-700 truncate flex-1 mr-2">${originalUrl}</span>
                    <button onclick="this.closest('.iframe-container').innerHTML=''" class="text-primary-600 hover:text-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <iframe 
                    src="${embedUrl}" 
                    class="w-full h-[600px] border-0"
                    frameborder="0"
                    allowfullscreen
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals"
                    referrerpolicy="no-referrer"
                    loading="lazy"
                ></iframe>
            </div>
        `;
        this.iframeContainer.style.display = 'block';
    }

    hideIframe() {
        this.iframeContainer.style.display = 'none';
        this.iframeContainer.innerHTML = '';
    }

    showError(message) {
        this.errorMsg.textContent = message;
        this.errorMsg.style.display = 'block';
    }

    hideError() {
        this.errorMsg.style.display = 'none';
    }

    setLoading(loading) {
        this.loadBtn.disabled = loading;
        this.loadBtn.innerHTML = loading 
            ? '<span>Processando...</span>' 
            : '<span>Validar e Carregar</span>';
    }

    clear() {
        this.urlInput.value = '';
        this.hideIframe();
        this.hideError();
        this.hideStatus();
        this.urlInput.classList.remove('border-red-300', 'border-green-300');
    }
}

// Função para bloquear abertura em nova aba
function blockNewWindowOpens() {
    // Bloqueia window.open() globalmente
    const originalWindowOpen = window.open;
    window.open = function(url, name, features) {
        // Bloqueia todas as tentativas de abrir nova janela
        console.log('Tentativa de abrir nova janela bloqueada:', url);
        return null;
    };

    // Intercepta cliques em links com target="_blank" ou que tentem abrir nova aba
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link) {
            // Bloqueia se tiver target="_blank" ou target="_new"
            if (link.target === '_blank' || link.target === '_new' || link.target === '_parent') {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Link com target bloqueado:', link.href);
                return false;
            }
            
            // Bloqueia se tiver rel="noopener" ou "noreferrer" (geralmente usado com _blank)
            if (link.rel && (link.rel.includes('noopener') || link.rel.includes('noreferrer'))) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Link com rel bloqueado:', link.href);
                return false;
            }
        }
    }, true); // Use capture phase para interceptar antes

    // Bloqueia middle-click (botão do meio do mouse) e Ctrl+Click em links
    document.addEventListener('auxclick', function(e) {
        if (e.button === 1) { // Middle mouse button
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                return false;
            }
        }
    }, true);

    // Bloqueia Ctrl+Click (Cmd+Click no Mac) que abre em nova aba
    document.addEventListener('click', function(e) {
        if (e.ctrlKey || e.metaKey) {
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Ctrl/Cmd+Click bloqueado:', link.href);
                return false;
            }
        }
    }, true);

    // Bloqueia Shift+Click que abre em nova janela
    document.addEventListener('click', function(e) {
        if (e.shiftKey) {
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Shift+Click bloqueado:', link.href);
                return false;
            }
        }
    }, true);

    // Intercepta mensagens postMessage de iframes tentando abrir nova aba
    window.addEventListener('message', function(e) {
        // Bloqueia mensagens que possam tentar abrir nova janela
        if (e.data && typeof e.data === 'string' && e.data.includes('open')) {
            console.log('Mensagem postMessage bloqueada:', e.data);
            e.stopPropagation();
        }
    }, true);
}

// Sistema de Links Salvos
class SavedLinksManager {
    constructor() {
        this.container = document.getElementById('saved-links-container');
        this.viewer = document.getElementById('saved-link-viewer');
        this.contentArea = document.getElementById('saved-link-content');
        this.saveBtn = document.getElementById('save-link-btn');
        this.urlInput = document.getElementById('new-link-url');
        this.titleInput = document.getElementById('new-link-title');
        this.errorMsg = document.getElementById('save-link-error');
        this.closeBtn = document.getElementById('close-viewer-btn');
        
        this.init();
    }

    init() {
        this.loadLinks();
        
        this.saveBtn.addEventListener('click', () => this.saveLink());
        this.urlInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.saveLink();
        });
        this.closeBtn.addEventListener('click', () => this.closeViewer());
    }

    async loadLinks() {
        try {
            const response = await fetch('/api/saved-links');
            const links = await response.json();
            this.renderLinks(links);
        } catch (error) {
            console.error('Erro ao carregar links:', error);
        }
    }

    renderLinks(links) {
        if (links.length === 0) {
            this.container.innerHTML = '<p class="col-span-full text-center text-primary-600 py-4">Nenhum link salvo ainda. Adicione um link acima!</p>';
            return;
        }

        this.container.innerHTML = links.map(link => `
            <div class="relative group">
                <button 
                    class="saved-link-btn w-full p-4 bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg border-2 border-primary-200 hover:border-primary-400 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg text-left"
                    data-link-id="${link.id}"
                >
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">${link.icon || '🔗'}</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-primary-800 truncate">${link.title || 'Link Salvo'}</p>
                            <p class="text-xs text-primary-600 mt-1">Clique para abrir</p>
                        </div>
                    </div>
                </button>
                <button 
                    class="delete-link-btn absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity p-1 text-red-500 hover:text-red-700 rounded"
                    data-link-id="${link.id}"
                    title="Remover link"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `).join('');

        // Adiciona event listeners aos botões
        this.container.querySelectorAll('.saved-link-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const linkId = btn.getAttribute('data-link-id');
                if (linkId) {
                    this.openLink(parseInt(linkId));
                }
            });
        });

        this.container.querySelectorAll('.delete-link-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const linkId = btn.getAttribute('data-link-id');
                if (linkId) {
                    this.deleteLink(parseInt(linkId));
                }
            });
        });
    }

    async saveLink() {
        const url = this.urlInput.value.trim();
        const title = this.titleInput.value.trim();

        if (!url) {
            this.showError('Por favor, insira uma URL válida.');
            return;
        }

        this.saveBtn.disabled = true;
        this.saveBtn.textContent = 'Salvando...';
        this.hideError();

        try {
            const response = await fetch('/api/saved-links', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ url, title: title || null })
            });

            const data = await response.json();

            if (data.success) {
                this.urlInput.value = '';
                this.titleInput.value = '';
                this.loadLinks();
            } else {
                this.showError('Erro ao salvar link');
            }
        } catch (error) {
            this.showError('Erro ao processar requisição: ' + error.message);
        } finally {
            this.saveBtn.disabled = false;
            this.saveBtn.textContent = 'Salvar Link';
        }
    }

    async openLink(id) {
        // console.log('Abrindo link ID:', id);
        
        // Mostra loading
        this.contentArea.innerHTML = '<div class="p-4 text-center"><p class="text-primary-600">Carregando conteúdo...</p></div>';
        this.viewer.style.display = 'block';
        this.viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });

        try {
            const response = await fetch(`/api/saved-links/${id}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            // console.log('Dados recebidos:', data);

            if (data.success) {
                this.showContent(data);
            } else {
                // throw new Error(data.error || 'Erro ao carregar link');
            }
        } catch (error) {
            // console.error('Erro ao abrir link:', error);
            // this.contentArea.innerHTML = `<div class="p-4 text-center text-red-500"><p>Erro ao carregar conteúdo: ${error.message}</p></div>`;
        }
    }

    showContent(data) {
        this.contentArea.innerHTML = '';
        
        // Mostra informações do arquivo se disponível
        if (data.fileInfo) {
            const infoDiv = document.createElement('div');
            infoDiv.className = 'mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg';
            infoDiv.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="text-2xl">${data.fileInfo.icon}</span>
                    <div>
                        <p class="font-semibold text-primary-800">${data.fileInfo.name}</p>
                        <p class="text-xs text-primary-600">Arquivo detectado e convertido para visualização</p>
                    </div>
                </div>
            `;
            this.contentArea.appendChild(infoDiv);
        }

        // Verifica se é PDF para renderizar como canvas
        if (isPDF(data.url) || (data.fileInfo && data.fileInfo.type === 'pdf')) {
            const pdfContainer = document.createElement('div');
            pdfContainer.className = 'pdf-viewer-container bg-white p-4 max-h-[800px] overflow-y-auto';
            this.contentArea.appendChild(pdfContainer);
            renderPDFAsImages(data.embedUrl, pdfContainer);
        } else {
            // Usa iframe para outros tipos
            const iframeContainer = document.createElement('div');
            iframeContainer.className = 'border-2 border-primary-200 rounded-lg overflow-hidden';
            iframeContainer.innerHTML = `
                <iframe 
                    src="${data.embedUrl}" 
                    class="w-full h-[600px] border-0"
                    frameborder="0"
                    allowfullscreen
                    sandbox="allow-same-origin allow-scripts allow-forms allow-modals"
                    referrerpolicy="no-referrer"
                    loading="lazy"
                ></iframe>
            `;
            this.contentArea.appendChild(iframeContainer);
        }

        this.viewer.style.display = 'block';
        this.viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    closeViewer() {
        this.viewer.style.display = 'none';
        this.contentArea.innerHTML = '';
    }

    async deleteLink(id) {
        if (!confirm('Tem certeza que deseja remover este link?')) {
            return;
        }

        try {
            const response = await fetch(`/api/saved-links/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                this.loadLinks();
            }
        } catch (error) {
            console.error('Erro ao remover link:', error);
        }
    }

    showError(message) {
        this.errorMsg.textContent = message;
        this.errorMsg.style.display = 'block';
    }

    hideError() {
        this.errorMsg.style.display = 'none';
    }
}

// Variável global para acesso
let savedLinksManager;

// Inicialização quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Bloqueia abertura em nova aba
    blockNewWindowOpens();
    
    // Inicializa sistema de links salvos
    if (document.getElementById('saved-links-container')) {
        savedLinksManager = new SavedLinksManager();
    }
    
    if (document.getElementById('embed-solution-3')) {
        new ProxyEmbedSolution('embed-solution-3');
    }
    if (document.getElementById('embed-solution-4')) {
        new MetadataPreviewSolution('embed-solution-4');
    }
    if (document.getElementById('embed-solution-5')) {
        new ValidationEmbedSolution('embed-solution-5');
    }
});


