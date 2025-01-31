@extends('app')

@section('content')
    <div class="text-center mb-4">
        <h1>📺 Cadastrar Nova Série</h1>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <span class="fs-5">Informe os dados da nova série</span>
        <a class="btn btn-danger" href="{{ route('series.index') }}">❌ Cancelar e Voltar</a>
    </div>

    <div class="card card-body shadow">
        <form action="{{ route('series.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <!-- Título -->
                <div class="col-md-8">
                    <label class="form-label fw-bold">📺 Título *</label>
                    <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Data de Lançamento -->
                <div class="col-md-4">
                    <label class="form-label fw-bold">📅 Data de Lançamento *</label>
                    <input type="date" class="form-control" name="launch_date" value="{{ old('launch_date') }}" required>
                    @error('launch_date')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Descrição -->
            <div class="mt-3">
                <label class="form-label fw-bold">📝 Descrição</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
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
