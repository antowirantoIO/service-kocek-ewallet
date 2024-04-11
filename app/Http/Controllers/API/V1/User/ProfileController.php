<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function me()
    {
        $user = auth()->user();

        return $this->success(
            true,
            'Data profile user',
            200,
            $user->toArray()
        );
    }
}
