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
            @forelse($groups as $group)
                <div class="card mb-4">
                    <div class="card-body row justify-content-between px-5">
                        <div>{{ $group->date->format(__('formats.date')) }}</div>
                        <div><span class="badge badge-primary p-2">{{ $group->count_records }}</span></div>
                    </div>
                </div>
            @empty
                {{ __('records.no_records') }}
            @endforelse
        </div>
    </div>

    @if($groups->isNotEmpty())
        {{ $groups->links() }}
    @endif
@endsection
