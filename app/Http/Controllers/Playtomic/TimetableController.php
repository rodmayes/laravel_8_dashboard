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
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.timetable.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.timetable.create');
    }

    public function edit(Timetable $timetable)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('playtomic.timetable.edit', compact('timetable'));
    }

    public function show(Timetable $timetable)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $timetable->load('resources');
        return view('playtomic.timetable.show', compact('timetable'));
    }

    public function getList()
    {
        return response()->json(Timetable::all());
    }
}
