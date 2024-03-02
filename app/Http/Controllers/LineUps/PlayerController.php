<?php

namespace App\Http\Controllers\LineUps;

use App\Http\Controllers\Controller;
use App\Models\LineUps\Player;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class PlayerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('line-ups.player_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.player.index');
    }

    public function create()
    {
        abort_if(Gate::denies('line-ups.player_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.player.create');
    }

    public function edit(Player $player)
    {
        abort_if(Gate::denies('line-ups.player_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.player.edit', compact('player'));
    }

    public function show(Player $player)
    {
        abort_if(Gate::denies('line-ups.player_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.player.show', compact('player'));
    }

    public function getList()
    {
        return response()->json(Player::all());
    }
}
