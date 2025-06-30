<div x-data="{ open: false }" x-show="open" id="createSeriesModal" class="modal-overlay" style="display: none;">
    <div @click.away="open = false" class="modal-content">
        <button @click="open = false" class="absolute top-4 right-4 text-primary-400 hover:text-primary-600 transition-colors duration-300">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center">
                <span class="text-white text-2xl">✨</span>
            </div>
            <h2 class="text-3xl font-bold text-primary-800">Nova Série</h2>
            <p class="text-primary-600 mt-2">Adicione uma nova série à sua coleção</p>
        </div>
        
        <form id="createSeriesForm" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label for="title" class="form-label">🎬 Título da Série</label>
                <input id="title" name="title" type="text" required class="form-input" placeholder="Digite o título da série">
                <p class="form-error" id="error-title"></p>
            </div>
            
            <div>
                <label for="description" class="form-label">📝 Descrição</label>
                <textarea id="description" name="description" class="form-input" rows="3" placeholder="Descreva a série..."></textarea>
                <p class="form-error" id="error-description"></p>
            </div>
            
            <div>
                <label for="launch_date" class="form-label">📅 Data de Lançamento</label>
                <input id="launch_date" name="launch_date" type="date" required class="form-input">
                <p class="form-error" id="error-launch_date"></p>
            </div>
            
            <div>
                <label for="image" class="form-label">🖼️ Imagem de Capa</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-primary-200 border-dashed rounded-xl hover:border-primary-400 transition-colors duration-300">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-primary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-primary-600">
                            <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                <span>Selecionar arquivo</span>
                            </label>
                            <p class="pl-1">ou arraste e solte</p>
                        </div>
                        <p class="text-xs text-primary-500">PNG, JPG, SVG até 2MB</p>
                    </div>
                </div>
                <p class="form-error" id="error-image"></p>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6">
                <button type="button" @click="open = false" class="btn-secondary">
                    ❌ Cancelar
                </button>
                <button type="submit" class="btn">
                    ✨ Criar Série
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('createSeriesModal');
    const openBtn = document.getElementById('openCreateSeriesModal');
    
    if (openBtn) {
        openBtn.addEventListener('click', function () {
            modal.__x.$data.open = true;
        });
    }

    // AJAX submit
    const form = document.getElementById('createSeriesForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Limpa erros
            ['title','description','launch_date','image'].forEach(f => {
                document.getElementById('error-' + f).textContent = '';
            });
            
            const formData = new FormData(form);
            
            fetch("{{ route('series.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(async response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            document.getElementById('error-' + field).textContent = messages[0];
                        });
                    }
                }
            })
            .catch(() => {
                alert('Erro ao cadastrar série.');
            });
        });
    }
});
</script> 