<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetupProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SetupProfileController extends Controller
{
    public function initiateDataProfileUser(SetupProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->only(['name', 'security_code']);

        if (isset($data['security_code'])) {
            $data['security_code'] = Hash::make($data['security_code']);
        }

        $user->update($data);

        return $this->success(
            true,
            'Profile has been updated'
        );
    }

}
