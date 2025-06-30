@extends('app')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-primary-800 mb-2">Editar Temporada</h1>
            <a class="btn-secondary" href="{{ route('series.seasons.index', $serie->id) }}">❌ Cancelar e voltar</a>
        </div>

        <div class="card p-8">
            <form action="{{ route('series.seasons.update', [$serie->id, $season->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="title" class="form-label">Título da Temporada</label>
                    <input type="text" id="title" name="title" required class="form-input" 
                           value="{{ old('title', $season->title) }}" placeholder="Ex: Temporada 1">
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="order" class="form-label">Ordem</label>
                    <input type="number" id="order" name="order" required class="form-input" 
                           value="{{ old('order', $season->order) }}" placeholder="1" min="1">
                    @error('order')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('series.seasons.index', $serie->id) }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn">Atualizar Temporada</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 