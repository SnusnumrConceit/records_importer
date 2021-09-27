@extends('layouts.app')

@section('content')
    <div class="card">
        @if($import_status)
            <div class="alert alert-info">
                {{ $import_status }}
            </div>
        @endif
        <div class="card-body ml-4">
            <form
                action="{{ route('records.import.store') }}"
                method="POST"
                class="form-check-inline"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="form-group row">
                    <label for="file" class="col-form-label">{{ __('records.import.file') }}</label>
                    <input type="file" class="form-control-file @error('file') is-invalid @enderror" name="file" accept="{{ $extensions = implode(', ', \App\Record::getAvailableExtensions()) }}">
                    <small class="text-muted d-block">
                        {{ __('records.import.hint', ['extensions' => $extensions, 'max_size' => \App\Record::getMaxFileSize() / 1024]) }}
                    </small>
                    @error('file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-outline-success mx-0">
                    {{ __('records.import.import') }}
                </button>
            </form>
        </div>
    </div>
@endsection
