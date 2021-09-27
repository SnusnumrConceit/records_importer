<?php

namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Translators\ImportStatusTranslator;

class ViewComposerServiceProdiver extends ServiceProvider
{
    public function boot()
    {
        View::composer('records.import.index', function ($view) {
            $importUuid = session('records_import');

            if (! ($importUuid && ($data = cache()->tags('records_import')->get($importUuid)))) {
                return $view->with(['import_status' => []]);
            }

            return $view->with(['import_status' => ImportStatusTranslator::translate($data)]);
        });
    }
}
