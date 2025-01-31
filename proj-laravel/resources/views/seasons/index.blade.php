@extends('app')

@section('content')
    <h1>Listagem de Temporadas da Série: {{ $serie->title }}</h1>

    <div class="d-flex justify-content-end">
        <a href="{{ route('seasons.create', $serie->id) }}" class="btn btn-outline-success">Cadastrar nova temporada</a>
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
                    <td class="btn-toolbar">
                        <div>
                            <a href="#" class="btn btn-outline-warning" title="Editar"><i class="fa fa-edit"></i></a>
                        <form action="#" method="POST">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-outline-danger" title="Apagar"><i class="fa fa-trash"></i></button>
                        </form>
                        <button class="btn btn-outline-info" title="Ver episódios"><i class="fa fa-eye"></i></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <a href="{{ route('series.index')}}" class="btn btn-outline-danger">Voltar</a>
    </div>
@endsection