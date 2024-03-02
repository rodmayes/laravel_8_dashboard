<?php

namespace App\Http\Controllers\LineUps;

use App\Http\Controllers\Controller;
use App\Models\LineUps\Round;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class RoundController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('line-ups.round_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.round.index');
    }

    public function create()
    {
        abort_if(Gate::denies('line-ups.round_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.round.create');
    }

    public function edit(Round $round)
    {
        abort_if(Gate::denies('line-ups.round_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.round.edit', compact('round'));
    }

    public function show(Round $round)
    {
        abort_if(Gate::denies('line-ups.round_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.round.show', compact('round'));
    }

    public function getList()
    {
        return response()->json(Round::all());
    }
}
