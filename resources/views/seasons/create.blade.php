@extends('app')

@section('content')
    <h1>Cadastrar Temporada da série {{ $serie->title }}</h1>

    <div class="d-flex justify-content-end mb-2">
        <a class="btn btn-danger" href="{{ route('seasons.index', $serie->id) }}">Cancelar e voltar</a>
    </div>

    <form action="{{ route('seasons.store', $serie->id) }}" method="POST">
        @method('POST')
        @csrf

        <div class="card card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Ordem</label>
                        <input type="number" class="form-control" name="order" required>
                        @error('order')
                            <span class="text-danger">
                                {{ $errors->first('order') }}
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label>Título *</label>
                        <input class="form-control" type="text" name="title" required>
                        @error('title')
                            <span class="text-danger">
                                {{ $errors->first('title') }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </form>
@endsection
