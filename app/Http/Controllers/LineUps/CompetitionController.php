<?php

namespace App\Http\Controllers\LineUps;

use App\Http\Controllers\Controller;
use App\Models\LineUps\Competition;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class CompetitionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('line-ups.competition_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.competition.index');
    }

    public function create()
    {
        abort_if(Gate::denies('line-ups.competition_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.competition.create');
    }

    public function edit(Competition $competition)
    {
        abort_if(Gate::denies('line-ups.competition_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.competition.edit', compact('competition'));
    }

    public function show(Competition $competition)
    {
        abort_if(Gate::denies('line-ups.competition_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.competition.show', compact('competition'));
    }

    public function getList()
    {
        return response()->json(Competition::all());
    }
}
