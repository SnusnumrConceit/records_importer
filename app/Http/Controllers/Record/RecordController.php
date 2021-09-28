<?php

namespace App\Http\Controllers\Record;

use App\Record;
use App\Http\Controllers\Controller;
use App\Http\Requests\Record\IndexRecord;

class RecordController extends Controller
{
    /**
     * Список records и поиск по ним
     *
     * @param \App\Http\Requests\Record\IndexRecord $request
     * @return \Illuminate\View\View
     */
    public function index(IndexRecord $request)
    {
        $records = Record::query();

        $records->selectRaw('Date(date) as date')
            ->selectRaw('COUNT(*) as count_records');

        $records->when($request->keyword, function ($query, $name) {
           return $query->where('name', 'LIKE', $name . '%');
        });

        $records->when($request->anyFilled(['date_start', 'date_end']), function ($query) {
            if (request()->has('date_start')) {
                $query->where('date', '>=', sprintf('%s %s', request('date_start'), '00:00:00'));
            }

            if (request()->has('date_end')) {
                $query->where('date', '<=', sprintf('%s %s', request('date_end'), '23:59:59'));
            }

            return $query;
        });

        $groups = $records
            ->latest('date')
            ->groupBy('date')
            ->paginate();

        return view('records.index', compact('groups'));
    }
}
