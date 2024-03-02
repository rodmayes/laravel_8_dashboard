<?php

namespace App\Http\Controllers\LineUps;

use App\Http\Controllers\Controller;
use App\Models\LineUps\Team;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('line-ups.team_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.team.index');
    }

    public function create()
    {
        abort_if(Gate::denies('line-ups.team_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.team.create');
    }

    public function edit(Team $team)
    {
        abort_if(Gate::denies('line-ups.team_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.team.edit', compact('team'));
    }

    public function show(Team $team)
    {
        abort_if(Gate::denies('line-ups.team_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.team.show', compact('team'));
    }

    public function getList()
    {
        return response()->json(Team::all());
    }
}
