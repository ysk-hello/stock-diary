@extends('layouts.app')

@section('content')
<!-- <div class="container">  -->
<div class="row justify-content-center">
    <div id="side" class="col-md-3">
        <calendar></calendar>
    </div>
    <div id="main" class="col-md-9">
        <companydiary code={{$company_code}} ymd={{$selected_date}}></companydiary>
    </div>
</div>
<!-- </div> -->
@endsection
