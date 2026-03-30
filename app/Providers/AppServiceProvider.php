<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Transaction;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            $pendingCount = 0;

            // Hindari query berat di halaman guest (contoh: login).
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                $view->with('pendingCount', $pendingCount);
                return;
            }

            try {
                $pendingCount = Transaction::where('status', 'pending')
                    ->whereNotNull('transaction_code')
                    ->where('transaction_code', '!=', '')
                    ->distinct('transaction_code')
                    ->count('transaction_code');
            } catch (\Throwable $e) {
                // Jika koneksi DB bermasalah, jangan bikin seluruh halaman error.
                $pendingCount = 0;
            }

            $view->with('pendingCount', $pendingCount);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
