<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use Gate;
use Illuminate\Http\Response;

class TimetableController extends Controller
{
    public function index()
    {
        return view('playtomic.timetable.index');
    }

    public function create()
    {
        return view('playtomic.timetable.create');
    }

    public function edit(Timetable $timetable)
    {
        return view('playtomic.timetable.edit', compact('timetable'));
    }

    public function show(Timetable $timetable)
    {
        $timetable->load('resources');
        return view('playtomic.timetable.show', compact('timetable'));
    }

    public function getList()
    {
        return response()->json(Timetable::all());
    }
}
