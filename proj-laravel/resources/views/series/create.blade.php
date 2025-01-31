@extends('app')

@section('content')
    <h1>Cadastrar Série</h1>

    <div class="d-flex justify-content-end mb-2">
        <a class="btn btn-danger" href="{{ route('series.index') }}">Cancelar e voltar</a>
    </div>

    <form action="{{ route('series.store') }}" method="POST">
        @method('POST')
        @csrf

        <div class="card card-body">
            <div class="row">
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
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Dt. lançamento *</label>
                        <input class="form-control" type="date" name="launch_date" required>
                        @error('launch_date')
                            <span class="text-danger">
                                {{ $errors->first('launch_date') }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Descrição</label>
                <textarea class="form-control" name="description"></textarea>
                @error('description')
                    <span class="text-danger">
                        {{ $errors->first('description') }}
                    </span>
                @enderror
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </form>
@endsection
