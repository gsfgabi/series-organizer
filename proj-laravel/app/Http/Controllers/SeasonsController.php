<?php

namespace App\Http\Controllers;
use App\Models\Season;
use App\Models\Series;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class SeasonsController extends Controller
{
    public function index($serie_id)
    {
        $serie = Series::findOrFail($serie_id);

        return view("seasons.index", compact('serie'));
    }

    public function create($serie_id)
    {
        $serie = Series::findOrFail($serie_id);
        return view('seasons.create', compact('serie'));
    }

    public function store (Request $request, $serie_id){

        $serie = Series::findOrFail($serie_id);

        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:200'],
            'order' => ['required', 'integer'],
        ]);


       Season::create([
        'title' => $validated['title'],
        'order' => $validated['order'],
        'series_id' => $serie->id,
       ]);

        return redirect()->route('seasons.index', $serie_id);
    }

}
