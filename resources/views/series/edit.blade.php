@extends('app')

@section('title', 'Editar Série')

@section('content')
    <div class="text-center mb-4">
        <h1>✏️ Editar Série</h1>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <span class="fs-5">📌 Você está editando: <strong>{{ $series->title }}</strong></span>
        <a class="btn btn-danger" href="{{ route('series.index') }}">❌ Cancelar e voltar</a>
    </div>

    <div class="card card-body shadow">
        <form action="{{ route('series.update', $series->id) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="row g-3">
                <!-- Título -->
                <div class="col-md-8">
                    <label class="form-label fw-bold">📺 Título *</label>
                    <input type="text" class="form-control" name="title" value="{{ old('title', $series->title) }}"
                        required>
                    @error('title')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Data de Lançamento -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">📅 Data de Lançamento *</label>
                    <input type="date" class="form-control" name="launch_date"
                        value="{{ old('launch_date', $series->launch_date->format('Y-m-d')) }}" required>
                    @error('launch_date')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Descrição -->
            <div class="mt-3">
                <label class="form-label fw-bold">📝 Descrição</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $series->description) }}</textarea>
                @error('description')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botão de Salvar -->
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success btn-lg">💾 Salvar</button>
            </div>
        </form>
    </div>
@endsection
