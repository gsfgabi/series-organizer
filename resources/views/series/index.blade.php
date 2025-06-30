@extends('app')

@section('title', 'Listagem de Séries')

@section('content')
    <div class="text-center mb-4">
        <h1>📺 Séries Cadastradas</h1>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fs-5">Total de séries: {{ $series->count() }}</span>
        <a href="{{ route('series.create') }}" class="btn btn-success">➕ Cadastrar Nova Série</a>
    </div>

    <div class="table-responsive">
        <table class="table table-light table-hover text-center">
            <thead class="table-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Dt. Lançamento</th>
                    <th>Dt. Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($series as $serie)
                    <tr>
                        <td>{{ $serie->id }}</td>
                        <td>{{ $serie->title }}</td>
                        <td>{{ $serie->description }}</td>
                        <td>{{ $serie->launch_date->format('d/m/Y') }}</td>
                        <td>{{ $serie->created_at->format('d/m/Y H:i') }}</td>
                        <td class="d-flex justify-content-center gap-2">
                            <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>

                            <form action="{{ route('series.destroy', $serie->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja apagar esta série?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Apagar</button>
                            </form>

                            <a href="{{ route('series.seasons.index', $serie->id) }}" class="btn btn-info btn-sm">📅 Temporadas</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
