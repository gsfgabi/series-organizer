<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Series;
use Illuminate\Http\Request;

class SeasonsController extends Controller
{
    public function index($serie_id)
    {
        $serie = Series::findOrFail($serie_id);  // Busca a série pelo ID
        return view("seasons.index", compact('serie'));  // Passa a série para a view
    }

    public function create($serie_id)
    {
        $serie = Series::findOrFail($serie_id);  // Busca a série pelo ID
        return view('seasons.create', compact('serie'));  // Passa a série para a view de criação
    }

    public function store(Request $request, $serie_id)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'order' => ['required', 'integer']
        ]);

        $validated['series_id'] = $serie_id;  // Adiciona o ID da série aos dados validados

        Season::create($validated);  // Cria a nova temporada

        return redirect()->route('seasons.index', $serie_id)->with('success', 'Temporada criada com sucesso!');  // Redireciona após a criação
    }
}
