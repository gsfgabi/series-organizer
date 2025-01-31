<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Series;

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
        $series = Series::all();

        return view('series.index', [
            'series' => $series
        ]);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'description' => ['nullable', 'min:3', 'max:1000'],
            'launch_date' => ['required', 'date'],
        ]);

        $series = Series::create($validated);

        return redirect()->route('series.index');
    }

    public function edit($id)
    {
        $series = Series::findOrFail($id);

        return view('series.edit', compact('series'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'description' => ['nullable', 'min:3', 'max:1000'],
            'launch_date' => ['required', 'date'],
        ]);

        $series = Series::findOrFail($id);

        $series->update($validated);

        return redirect()->route('series.edit', $id);
    }

    public function destroy($id)
    {
        $series = Series::findOrFail($id);
        $series->delete();
        return redirect()->route('series.index');
    }
}
