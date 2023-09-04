<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\PlaytomicHttpService;
use Gate;
use Illuminate\Http\Response;

class ClubController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.club.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.club.create');
    }

    public function edit(Club $club)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.club.edit', compact('club'));
    }

    public function show(Club $club)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $club->load('resources');
        return view('playtomic.club.show', compact('club'));
    }


    public function availability(Club $club)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.club.availability', compact('club'));
    }

    public function getInfo(Club $club)
    {
        $info = (new PlaytomicHttpService)->getInformationClub($club);
        return response()->json($info);
    }
}
