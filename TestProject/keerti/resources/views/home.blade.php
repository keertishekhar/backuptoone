@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img  class="img-circle" src="/storage/avatars/{{ Auth::user()->avatar }}" width="50px" height="50px">
                        <h4>{{ Auth::user()->first_name }}&nbsp;&nbsp;{{ Auth::user()->last_name }}</h4>
                    </div>
                    <div class="panel-body">
                        <a href="{{ url('/profile') }}">Update Profile picture </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Publish Your Post</h3>
                    <form action="{{ url('/posts') }}" method="post"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <textarea rows="4" cols="100" name="post" class="form-control">Enter text here...</textarea>
                        </div>
                        <div class="form-group">    
                            <input type="file" name="postpic" class="form-control-file">
                        </div>
                        <div class="form-group col-md-offset-5">
                            <input type="submit" class="btn btn-primary btn-center">
                        </div>
                    </form>                
                </div>
                <div class="panel-body">
                   @foreach($posts as $post)
                    <div class="col-md-12">
                        <div class="col-md-1 text-center">
                            <div class="row">
                                <img src="/storage/avatars/{{ $post->user->avatar }}" class="img-circle" width="30px" height="30px">
                            </div>
                        </div> 
                        <div class="col-md-1">  
                            <div class="row">
                                <h4 class="cs-m">{{ $post->user->first_name }}</h4>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <h4>{{ $post->post}}</h4>
                    </div>
                    <div class="col-md-12">
                        <img  class="img-fluid" src="/storage/postpics/{{ $post->postpic }}" width="400px" height="200px">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
