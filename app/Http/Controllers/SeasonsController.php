<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Series;
use Illuminate\Http\Request;

class SeasonsController extends Controller
{
    public function index($serie_id)
    {
        $serie = Series::findOrFail($serie_id);
        $seasons = $serie->seasons()->orderBy('order')->get();
        return view("seasons.index", compact('serie', 'seasons'));
    }

    public function create($serie_id)
    {
        $serie = Series::findOrFail($serie_id);
        return view('seasons.create', compact('serie'));
    }

    public function store(Request $request, $serie_id)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'order' => ['required', 'integer', 'min:1']
        ]);

        $validated['series_id'] = $serie_id;

        Season::create($validated);

        return redirect()->route('series.seasons.index', $serie_id)->with('success', 'Temporada criada com sucesso!');
    }

    public function edit($serie_id, $id)
    {
        $serie = Series::findOrFail($serie_id);
        $season = Season::where('series_id', $serie_id)->findOrFail($id);
        return view('seasons.edit', compact('serie', 'season'));
    }

    public function update(Request $request, $serie_id, $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'order' => ['required', 'integer', 'min:1']
        ]);

        $season = Season::where('series_id', $serie_id)->findOrFail($id);
        $season->update($validated);

        return redirect()->route('series.seasons.index', $serie_id)->with('success', 'Temporada atualizada com sucesso!');
    }

    public function destroy($serie_id, $id)
    {
        $season = Season::where('series_id', $serie_id)->findOrFail($id);
        $season->delete();

        return redirect()->route('series.seasons.index', $serie_id)->with('success', 'Temporada excluída com sucesso!');
    }
}
