@extends('layout.app')

@section('content')
<game-component ></game-component>
<leader-list-component ></leader-list-component>
@endsection


@push('script')
<script src="{{ asset('js/main.js') }}"></script>
@endpush