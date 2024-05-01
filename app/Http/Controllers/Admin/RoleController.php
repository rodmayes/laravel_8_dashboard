<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function __construct(){
        abort_if(Gate::allows('hasRole', ['admin']), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function index()
    {
       return view('admin.role.index');
    }

    public function create()
    {
       return view('admin.role.create');
    }

    public function edit(Role $role)
    {
        return view('admin.role.edit', compact('role'));
    }

    public function show(Role $role)
    {
       $role->load('permissions');

        return view('admin.role.show', compact('role'));
    }
}
