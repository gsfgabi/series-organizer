@extends('app')

@section('content')
    <h1>Listagem de Séries</h1>

    <div class="d-flex justify-content-end">
        <a href="{{ route('series.create') }}" class="btn btn-success">Cadastrar nova série</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Dt. lançamento</th>
                <th>Dt. cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($series as $serie)
                <tr>
                    <td>{{ $serie->id }}</td>
                    <td>{{ $serie->title }}</td>
                    <td>{{ $serie->description }}</td>
                    <td>{{ $serie->launch_date->format('d/m/Y') }}</td>
                    <td>{{ $serie->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('series.edit', $serie->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('series.destroy', $serie->id) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger">Apagar</button>
                        </form>
                        <a href="{{ route('seasons.index', $serie->id) }}" class="btn btn-info">Temporadas</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection