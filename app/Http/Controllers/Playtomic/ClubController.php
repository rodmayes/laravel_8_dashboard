<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\Playtomic\PlaytomicHttpService;
use Gate;

class ClubController extends Controller
{
    public function index()
    {
        return view('playtomic.club.index');
    }

    public function create()
    {
        return view('playtomic.club.create');
    }

    public function edit(Club $club)
    {
        return view('playtomic.club.edit', compact('club'));
    }

    public function show(Club $club)
    {
        $club->load('resources');
        return view('playtomic.club.show', compact('club'));
    }


    public function availability(Club $club)
    {
        return view('playtomic.club.availability', compact('club'));
    }

    public function getInfo(Club $club)
    {
        $info = (new PlaytomicHttpService)->getInformationClub($club);
        return response()->json($info);
    }

    public function getList()
    {
        $clubs = Club::all();
        return response()->json($clubs);
    }
}
