<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\ReservationApproved;
use App\Events\ReservationRejected;
use App\Listeners\SendApprovalNotification;
use App\Listeners\SendRejectionNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    protected $listen = [
        // 2. DAFTARKAN PASANGAN APPROVED
        ReservationApproved::class => [
            SendApprovalNotification::class,
        ],

        // 3. DAFTARKAN PASANGAN REJECTED
        ReservationRejected::class => [
            SendRejectionNotification::class,
        ],
    ];
    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
