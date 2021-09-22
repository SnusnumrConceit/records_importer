@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $message)
            <div class="text-danger">{{ $message }}</div>
        @endforeach
    </div>
@endif
