<?php

namespace App\Http\Controllers\Record;

use Ramsey\Uuid\Uuid;
use App\Http\Controllers\Controller;
use App\Imports\Record\RecordImport;
use App\Http\Requests\Record\ImportRecord;

class ImportController extends Controller
{
    /**
     * Страница импорта records
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('records.import.index');
    }

    /**
     * Запуск импорта records
     *
     * @param \App\Http\Requests\Record\ImportRecord $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ImportRecord $request)
    {
        $importUuid = Uuid::uuid4()->toString();
        session(['records_import' => $importUuid]);

        (new RecordImport($importUuid))->import($request->file('file'));

        return redirect()->route('records.index')
            ->withSuccess(__('records.messages.importing'));
    }
}
