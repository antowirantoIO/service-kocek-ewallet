<?php

namespace App\Jobs;

use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleSendOTPJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $countryCode;
    private string $numberPhone;
    private User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $countryCode,
        string $numberPhone,
        User   $user
    )
    {
        $this->countryCode = $countryCode;
        $this->numberPhone = $numberPhone;
        $this->user = $user;
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        $otp = CommonHelper::generateRandomString();

        $this->user->update([
            'otp_code' => $otp,
            'otp_expired_at' => now()->addMinutes(15),
        ]);

        $message = 'Halo! Kode OTP Anda adalah ' . $otp . '. Mohon gunakan kode ini untuk melanjutkan proses verifikasi akun Anda di ' . config('app.name') . '. Kode ini valid selama 15 menit.';

        NotificationHelper::sendMessageToWhatsApp(
            CommonHelper::normalizeIdNumberPhone($this->countryCode, $this->numberPhone),
            $message
        );
    }
}
