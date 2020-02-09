@extends('layout.app')

@section('content')
<game-component 
	:errors="errors"
	:input="input" 
	:cardlist="cardlist" 
	:cardfunc="cardfunc"
	:checkform="checkForm"
	:machinecardlist="machineCardList"
	:score="score"
	:init="init"
 ></game-component>

<leader-list-component :leader="leader" ></leader-list-component>
@endsection


@push('script')
<script src="{{ asset('js/main.js') }}"></script>
@endpush