<?php

namespace App\Http\Controllers\Playtomic;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Playtomic\PlaytomicHttpService;

class PlaytomicController extends Controller
{

    public function login(User $user){
        $service = (new PlaytomicHttpService($user->id));
        $response = $service->login();

        if(isset($response['access_token']) && $response['access_token'] != $user->playtomic_token) {
            $user->playtomic_token = $response['access_token'];
            $user->playtomic_refresh_token = $response['playtomic_refresh_token'] ?: null;
            $user->save();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'fail']);
    }
}
