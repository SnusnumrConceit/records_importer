@extends('layouts.app')

@section('content')
    @include('alerts.alerts', ['errors' => $errors ?? null])

    <div class="d-flex">
        <h2 class="page-title ml-2 mb-0">
            {{ __('records.records') }}
        </h2>
        <a href="{{ route('records.import.index') }}" class="btn btn-small btn-outline-success ml-2 mt-auto">
            {{ __('records.import.import') }}
        </a>
    </div>
    <div class="card mt-2">
        <div class="card-body">
            @forelse($records as $record)
                <div>{{ $record->name }}</div>
            @empty
                {{ __('records.no_records') }}
            @endforelse
        </div>
    </div>
@endsection
