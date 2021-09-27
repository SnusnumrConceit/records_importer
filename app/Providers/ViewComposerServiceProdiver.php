<?php

namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProdiver extends ServiceProvider
{
    public function boot()
    {
        View::composer('records.import.index', function ($view) {
            // добавить перевод статуса импорта
        });
    }
}
