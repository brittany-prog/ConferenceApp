<?php

namespace App\Providers;

use App\View\Composers\AppShellComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('*', AppShellComposer::class);
    }
}
