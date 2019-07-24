@extends('welcome')
@section('register')
@if(session('success'))
<div class="col-md-12 bg-success">
    <h1>{{session('success')}}</h1>
</div>
@endif

<div class="container register-form">

<form action="{{ url('/register') }}" method="post" enctype="multipart/form-data">
{{ csrf_field() }}
  <div class="row">
    <div class="col-md-6">
    <label for="inputfirstname">First Name</label>
      <input type="text" class="form-control" name="first_name" id="inputfirstname" placeholder="Enter First Name Here" value="{{ old('first_name') }}">
     @if($errors->first('first_name'))
        <span class="text-danger">{{ $errors->first('first_name') }}</span>
      @endif
    </div>
    <div class="col-md-6">
    <label for="inputlastname">Last Name</label>
      <input type="text" class="form-control" name="last_name" id="inputlastname" placeholder="Enter Last Name Here" value="{{ old('last_name') }}">
      @if($errors->first('last_name'))
       <span class="text-danger">{{ $errors->first('last_name') }}</span>
      @endif
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Email</label>
      <input type="email" class="form-control" name="email" id="inputEmail4" placeholder="Enter Your Valid Email Address Here" value="{{ old('email') }}">
      @if($errors->first('email'))
        <span class="text-danger">{{ $errors->first('email') }}</span>
      @endif
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Password</label>
      <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="Enter Password Here" value="{{ old('password') }}">
      @if($errors->first('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label for="inputAddress">Address</label>
    <input type="text" class="form-control" name="address" id="inputAddress" placeholder="Enter Your Street Address Here" value="{{ old('address') }}">
    @if($errors->first('address'))
       <span class="text-danger">{{ $errors->first('address') }}</span>
    @endif
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">City</label>
      <input type="text" class="form-control" name="city" id="inputCity" placeholder="Enter Your City Name Here" value="{{ old('city') }}">
      @if($errors->first('city'))
         <span class="text-danger">{{ $errors->first('city') }}</span>
      @endif
    </div>
    <div class="form-group col-md-4">
      <label for="inputState">State</label>
      <select id="inputState" class="form-control" name="state" value="{{ old('satate') }}">
        <option selected>Choose...</option>
        <option name="state" value="chandigarh">Chandigarh</option>
        <option name="state" value="panjab">Panjab</option>
      </select>
    </div>
    <div class="form-group col-md-2">
      <label for="inputZip">Zip</label>
      <input type="text" class="form-control" name="zip" id="inputZip" placeholder="e.g. 12345" value="{{ old('zip') }}">
      @if($errors->first('zip'))
           <span class="text-danger">{{ $errors->first('zip') }}</span>
      @endif
    </div>
    <div class="form-group col-md-12">
      <label for="inputimage">Upload Image</label>
      <input type="file" class="form-control" name="image" id="inputimage" value="{{ old('image') }}">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Sign in</button>
</form>
</div>


@endsection