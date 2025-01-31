@extends('app')

@section('content')
    <h1>Listagem de Temporadas da Série: {{ $serie->title }}</h1>

    <div class="d-flex justify-content-end">
        <div class="btn-group">
            <a href="{{ route('series.index') }}" class="btn btn-danger">Voltar</a>
            <a href="{{ route('seasons.create', $serie->id) }}" class="btn btn-success">Cadastrar nova temporada</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Ordem</th>
                <th>Título</th>
                <th>Dt. cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($serie->seasons as $season)
                <tr>
                    <td>{{ $season->order }}</td>
                    <td>{{ $season->title }}</td>
                    <td>{{ $season->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        {{-- <a href="{{ route('seasons.edit', $season->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('seasons.destroy', $season->id) }}" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger">Apagar</button>
                        </form>
                        <button class="btn btn-info">Ver episódios</button> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection