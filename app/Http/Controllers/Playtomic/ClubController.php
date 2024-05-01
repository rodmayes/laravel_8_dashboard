<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\PlaytomicHttpService;
use Gate;
use Illuminate\Http\Response;

class ClubController extends Controller
{
    public function __construct(){
        abort_if(Gate::allows('hasRole', ['admin','playtomic']), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

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
