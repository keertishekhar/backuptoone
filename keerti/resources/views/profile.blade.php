@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="col-md-4 col-md-offset-4">
            <div class="row">
                
                @if ($message = Session::get('success'))

                    <div class="alert alert-success alert-block">

                        <button type="button" class="close" data-dismiss="alert">×</button>

                        <strong>{{ $message }}</strong>

                    </div>

                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="row justify-content-center">

                <div class="profile-header-container">
                    <div class="profile-header-img">
                        <img class="img-circle" src="/storage/avatars/{{ Auth::user()->avatar }}" width="156px" height="156px"/>
                        <!-- badge -->
                    </div>
                </div>

            </div>
            <div class="row justify-content-center">
                <form action="{{url('/profile')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="form-group">
                        <label for="upload-photo"><span><i class="fa fa-camera" aria-hidden="true"></i></span></label>
                        <input type="file" class="form-control-file" name="avatar" id="upload-photo" aria-describedby="fileHelp"><br>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Profile Pic</button>
                </form>
            </div>
        </div>
    </div>
@endsection