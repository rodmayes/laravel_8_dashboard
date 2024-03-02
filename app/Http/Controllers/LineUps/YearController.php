<?php

namespace App\Http\Controllers\LineUps;

use App\Http\Controllers\Controller;
use App\Models\LineUps\Year;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class YearController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('line-ups.year_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.year.index');
    }

    public function create()
    {
        abort_if(Gate::denies('line-ups.year_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.year.create');
    }

    public function edit(Year $year)
    {
        abort_if(Gate::denies('line-ups.year_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.year.edit', compact('year'));
    }

    public function show(Year $year)
    {
        abort_if(Gate::denies('line-ups.year_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.year.show', compact('year'));
    }

    public function getList()
    {
        return response()->json(Year::all());
    }
}
