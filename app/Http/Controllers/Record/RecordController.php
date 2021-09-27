<?php

namespace App\Http\Controllers\Record;

use App\Record;
use Illuminate\Support\Facades\DB;
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

        $records->when($request->name, function ($query, $name) {
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

        $groups = $records->select(DB::raw('Date(date) as date'), DB::raw('COUNT(*) as count_records'))
            ->latest('date')
            ->groupBy('date')
            ->paginate();

        return view('records.index', compact('groups'));
    }
}
