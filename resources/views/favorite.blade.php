@extends('layouts.app')

@section('content')
<!-- <div class="container">  -->
<div class="row justify-content-center">
    <div id="side" class="col-md-3">
        <calendar></calendar>
    </div>
    <div id="main" class="col-md-9">
        <favorite ymd="{{$selected_date}}"></favorite>
    </div>
</div>
<!-- </div> -->
@endsection
