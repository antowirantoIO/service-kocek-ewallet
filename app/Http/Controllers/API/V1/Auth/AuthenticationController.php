<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Jobs\HandleSendOTPJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function requestGrantToken(AuthRequest $request): JsonResponse
    {
        $user = User::firstOrCreate([
            'country_code' => $request->country_code,
            'number_phone' => $request->number_phone,
        ]);

        HandleSendOTPJob::dispatch($request->country_code, $request->number_phone, $user);

        return $this->success(
            true,
            'OTP has been sent to your phone number',
            200,
            $user->only(['id', 'country_code', 'number_phone'])
        );
    }

    public function verifyAndGrandToken(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'otp_code' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);

        if (Carbon::parse($user->otp_expired_at)->isPast()) {
            return $this->error(
                false,
                'OTP code is expired',
            );
        }

        if ($user->otp_code !== $request->otp_code) {
            return $this->error(
                false,
                'OTP code is invalid',
            );
        }

        $user->update([
            'otp_code' => null,
            'otp_expired_at' => null,
            'otp_verified_at' => now(),
        ]);

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(
            true,
            'Token granted successfully',
            200,
            [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]
        );
    }

}
