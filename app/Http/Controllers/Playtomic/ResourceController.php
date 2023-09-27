<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Resource;
use Gate;
use Illuminate\Http\Response;

class ResourceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('playtomic.resource.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('playtomic.resource.create');
    }

    public function edit(Resource $resource)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('playtomic.resource.edit', compact('resource'));
    }

    public function show(Resource $resource)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resource->load('club');

        return view('playtomic.resource.show', compact('resource'));
    }

    public function getList(Club $club = null)
    {
        $resources = Resource::byClub($club->id)->get();
        return response()->json($resources);
    }
}
