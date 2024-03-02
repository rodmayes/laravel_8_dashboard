<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_management.permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permission.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_management.permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permission.create');
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('user_management.permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permission.edit', compact('permission'));
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('user_management.permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permission.show', compact('permission'));
    }
}
