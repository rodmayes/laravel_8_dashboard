<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function __construct(){
        abort_if(Gate::allows('hasRole', ['admin']), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function index()
    {
        return view('admin.permission.index');
    }

    public function create()
    {
        return view('admin.permission.create');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permission.edit', compact('permission'));
    }

    public function show(Permission $permission)
    {
        return view('admin.permission.show', compact('permission'));
    }
}
