<div class="flex-1">
    <div class="max-w-7xl mx-auto mt-5 py-12 px-4 bg-white dark:bg-gray-800 sm:px-6 lg:px-8 rounded-2xl">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 place-items-center">
            @foreach ($series as $serie)
                <a href="{{ route('series.show', $serie->id) }}" class="block w-52 h-60 hover:no-underline focus:outline-none">
                    <div class="overflow-hidden shadow-sm rounded-lg transition duration-500 ease-in-out transform hover:shadow-md hover:scale-105">
                        <div>
                            @if ($serie->file)
                                <img class="max-w-full transition-all duration-300 cursor-pointer filter grayscale hover:grayscale-0 object-cover w-full h-36 bg-center bg-cover bg-no-repeat opacity-70"
                                    src="{{ asset('storage/series/' . $serie->file->name) }}" alt="{{ $serie->file->name }}" />
                            @else
                                <div class="max-w-lg w-full h-36 rounded-lg bg-gray-300"></div>
                            @endif
                        </div>

                        <div class="px-4 py-2 sm:p-2 bg-green-900">
                            <dl>
                                <dt class="text-sm text-center text-white truncate font-bold">
                                    {{ $serie->title }}  <!-- Exibe o título da série -->
                                </dt>
                            </dl>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>