<?php

namespace App\Http\Controllers\LineUps;

use App\Http\Controllers\Controller;
use App\Models\LineUps\Configuration;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

class ConfigurationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('line-ups.configuration_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.configuration.index');
    }

    public function create()
    {
        abort_if(Gate::denies('line-ups.configuration_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.configuration.create');
    }

    public function edit(Configuration $configuration)
    {
        abort_if(Gate::denies('line-ups.configuration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.configuration.edit', compact('configuration'));
    }

    public function show(Configuration $configuration)
    {
        abort_if(Gate::denies('line-ups.configuration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.configuration.show', compact('configuration'));
    }

    public function getList()
    {
        return response()->json(Configuration::all());
    }
}
