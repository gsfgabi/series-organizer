@extends('app')

@section('content')
    <div class="max-w-7xl mx-auto mt-5 py-12 px-4 bg-white dark:bg-gray-800 sm:px-6 lg:px-8 rounded-2xl">
        <div class="flex justify-center">
            <div class="w-full sm:w-3/4 md:w-1/2 bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-3xl font-bold text-center mb-4">{{ $serie->title }}</h1>

                @if ($serie->file)
                    <img src="{{ asset('storage/series/' . $serie->file->name) }}" alt="{{ $serie->file->name }}"
                        class="w-full rounded-lg">
                @else
                    <div class="bg-gray-300 w-full h-64 rounded-lg"></div>
                @endif

                <div class="mt-4">
                    <h2 class="text-xl font-semibold">Descrição</h2>
                    <p>{{ $serie->description }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
