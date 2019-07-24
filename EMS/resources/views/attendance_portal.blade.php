<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  
  <body>
        <div class="container">
            <div class="row">
                <div class="card card-primary offset-md-3 mt-5">
                    <div class="card-header">
                        <h1>Attendace Portal For Employee</h1>
                    </div>
                    <div class="cord-body">
                        <div class="col-md-12 mt-5 mb-5">
                            <form  class="form-inline" action="{{ url('/attendance_portal') }}" method="post">
                            {{ csrf_field() }}
                                <div class="form-group mb-2">
                                    <label for="date" class="sr-only">Date</label>
                                    <input type="date" name="date" class="form-control-plaintext" id="date" placeholder="Enter Today's Date" >
                                </div>
                                <div class="form-group mb-2">
                                    <label for="user_id" class="sr-only">Email</label>
                                    <input type="text" name="user_id" readonly class="form-control-plaintext" id="user_id" value="{{ session('id') }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="present" class="">Present</label>
                                    <input type="radio" name="present" value="1" class="form-control-plaintext" id="present">
                                </div>
                                <div class="form-group mb-2 ml-2 mr-2">
                                    <label for="absent" class="">Absent</label>
                                    <input type="radio"  name="present" value="2" class="form-control-plaintext" id="absent">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>