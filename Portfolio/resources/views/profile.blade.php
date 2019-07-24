@extends('welcome')
@section('profile')

<div class="container-fluid " >
  <div class="row" style="height:100%;">
    <div class="col-md-3 bg-light pl-5 ">
        <div class="row ">
            <div class="col-md-12 ">
                <div class="row pt-5">
                    <img src="{{ asset( 'Images/'.$users->image .'')}}" width="100px" height="100px" >
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                  <h1> {{ $users->first_name }}  {{ $users->last_name  }}</h1>
                </div>
            </div>
            <div class="col-md-12" style="position:relative;">
                <div class="row">
                  <h6> {{ $users->email }}</h6>
                </div>
            </div>
            <div class="col-md-12" style="position:relative;">
                <div class="row">
                    <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Address</button>
                </div>
            </div> 
            <div class="col-md-12" style="position:relative;">
                <div class="row">
                    <div id="demo" class="collapse">
                        <p>{{ $users->address }}</p>
                        <p>{{ $users->city }}</p>
                        <p>{{ $users->state }}</p>
                        <p>{{ $users->zip }}</p>
                    </div>
                </div>
            </div>                                    
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
        </div>
    </div>
    <div class="col-md-3 bg-light">
        <div class="row">
        </div>
    </div>        
  </div>
</div>



@endsection





</div>