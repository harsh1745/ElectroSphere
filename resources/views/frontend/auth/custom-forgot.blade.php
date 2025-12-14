@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <h2>Verify Your Identity</h2>

    <form method="POST" action="{{ route('custom.forgot.check') }}">
        @csrf

        <div>
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mt-3">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control" required>
        </div>

        <button class="btn btn-danger mt-4">Continue</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/sweetalert.js') }}"></script>
@if(session('error'))
<script>
Swal.fire({
    icon: "error",
    title: "Oops...",
    text: "{{ session('error') }}",
});
</script>
@endif

@if($errors->any())
<script>
Swal.fire({
    icon: "error",
    title: "Validation Error",
    html: "{!! implode('<br>', $errors->all()) !!}"
});
</script>
@endif

@if(session('success'))
<script>
Swal.fire({
    icon: "success",
    title: "Success",
    text: "{{ session('success') }}",
});
</script>
@endif
@endpush
