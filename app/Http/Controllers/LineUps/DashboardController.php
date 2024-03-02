<?php

namespace App\Http\Controllers\LineUps;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class DashboardController extends \App\Http\Controllers\Controller
{
    public function index(){
        abort_if(Gate::denies('line-ups.dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.dashboard.index');
    }

    public function calendar(){
        abort_if(Gate::denies('line-ups.calendar_index'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('line-ups.dashboard.calendar');
    }

}
