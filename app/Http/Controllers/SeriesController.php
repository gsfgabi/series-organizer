<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Series;
use Illuminate\Support\Facades\Storage;

class SeriesController extends Controller
{
    // index // listar series (front)
    // create // página de criar série (front)
    // store // salva a série nova no banco (backend)
    // edit // página de editar série (front)
    // update // salvar a edição da série no banco (backend)
    // delete // front end de apagar
    // destroy // função que apaga a série (backend)

    public function index()
    {
        $series = Series::all();  // Obtém todas as séries
        return view('series.index', compact('series'));  // Passa a coleção para a view
    }

    public function create()
    {
        return view('series.create');  // Retorna a view para criar uma nova série
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'description' => ['nullable', 'min:3', 'max:1000'],
            'launch_date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'], // Alterado para 'image'
        ]);

        $filename = null; // Inicializa a variável filename
        $extension = null;

        if ($request->hasFile('image')) { // Verifica se a imagem foi enviada
            $requestFile = $request->file('image');
            $extension = $requestFile->extension();
            $filename = time() . '.' . $extension;
            $path = $requestFile->storeAs('series', $filename, 'public');
        }

        $series = Series::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'launch_date' => $validated['launch_date'],
            'image_id' => null, // Inicializa como null
        ]);

        if ($filename) { // Verifica se o filename foi definido
            $file = \App\Models\File::create([
                'name' => $filename,
                'path' => 'series/' . $filename,
                'type' => 'image',
                'extension' => $extension,
                'fileable_type' => Series::class,
                'fileable_id' => $series->id,
            ]);

            $series->update(['image_id' => $file->id]); // Atualiza a série com o ID do arquivo
        }

        return redirect()->route('series.index')->with('success', 'Série criada com sucesso!');  // Redireciona após a criação
    }

    public function edit($id)
    {
        $series = Series::findOrFail($id);  // Busca a série pelo ID
        return view('series.edit', compact('series'));  // Passa a série para a view de edição
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'description' => ['nullable', 'min:3', 'max:1000'],
            'launch_date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'], // Alterado para 'image'
        ]);

        $series = Series::findOrFail($id);
        $series->update($validated);

        if ($request->hasFile('image')) { // Verifica se a imagem foi enviada
            $requestFile = $request->file('image');
            $extension = $requestFile->extension();
            $filename = time() . '.' . $extension;
            $path = $requestFile->storeAs('series', $filename, 'public');

            // Atualiza ou cria o arquivo
            if ($series->image_id) {
                $file = \App\Models\File::findOrFail($series->image_id);
                $file->update([
                    'name' => $filename,
                    'path' => 'series/' . $filename,
                    'type' => 'image',
                    'extension' => $extension,
                    'fileable_type' => Series::class,
                    'fileable_id' => $series->id,
                ]);
            } else {
                $file = \App\Models\File::create([
                    'name' => $filename,
                    'path' => 'series/' . $filename,
                    'type' => 'image',
                    'extension' => $extension,
                    'fileable_type' => Series::class,
                    'fileable_id' => $series->id,
                ]);
                $series->update(['image_id' => $file->id]);
            }
        }

        return redirect()->route('series.index')->with('success', 'Série editada com sucesso!');  // Redireciona após a edição
    }

    public function destroy($id)
    {
        $series = Series::findOrFail($id);  // Busca a série pelo ID
        $series->delete();  // Deleta a série
        return redirect()->route('series.index')->with('success', 'Série deletada com sucesso!');  // Redireciona após a deleção
    }

    public function show($id)
    {
        $serie = Series::findOrFail($id);  // Busca a série pelo ID
        return view('series.show', compact('serie'));  // Passa a série para a view
    }
}
