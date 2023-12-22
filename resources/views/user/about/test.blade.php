@extends('layouts.user')
@section('head')
    
@endsection
@section('content')
    <form method="POST" action="{{url('/test')}}">
        @csrf
        <input type="text" name="from" id="form">
        <input type="text" name="to" id="to">
        <input type="submit">
    </form>
@endsection
@section('script')
    <script>
        function cities(){
            var options = {
                types: ['(cities)']
            };
            var fromInput = $('#from');
            var formAutoComplete = new google.maps.places.Autocomplete(fromInput,options);

            var toInput = $('#to');
            var toAutoComplete = new google.maps.places.Autocomplete(toInput,options);
        }
    </script>
@endsection