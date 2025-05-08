<?php
namespace App\Http\Controllers\Lottery;

use App\Http\Controllers\Controller;

class LotteryController extends Controller
{
    public function index(){
        return view('lottery.index');
    }
}

