@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3 bg-primary">
					<div class="row">
						<div class="panel panel-success">
							<div class="panel-heading">{{  session('name')  }}</div>
							<div class="panel-body bg-danger">
								<a href="#demo" class="btn btn-primary btn-block" data-toggle="collapse">Forms</a>
								<div id="demo" class="collapse">
									  <ul class="list-group">
										<a href="#" class="list-group-item list-group-item-info ">Add Employee</a>
										<a href="#" class="list-group-item list-group-item-info">CSS</a>
										<a href="#" class="list-group-item list-group-item-info">JavaScript</a>
										<a href="#" class="list-group-item list-group-item-info">About Us</a>
								</div>
								<a href="#demo1" class="btn btn-primary btn-block" data-toggle="collapse" style="margin-top:10px;">Informations</a>
								<div id="demo1" class="collapse">
									  <ul class="list-group">
										<a class="list-group-item list-group-item-info button1" data-toggle="modal" data-target="#myModal">Ajax Data</a>
										<a href="#" class="list-group-item list-group-item-info">CSS</a>
										<a href="#" class="list-group-item list-group-item-info">JavaScript</a>
										<a href="#" class="list-group-item list-group-item-info">About Us</a>
								</div>
								<div class="" style="margin-top:10px">
									<a href="{{ url('/attendance') }}" class="btn btn-primary btn-block">Attendance</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
				@yield('ajaxdata')	
				@yield('addempoyee')
				</div>	
		</div>
	</div>
</div>

@endsection