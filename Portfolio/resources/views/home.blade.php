@extends('welcome')
@section('home')
<div id="carouselExampleControls" class="carousel slide " data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="../resources/views/assets/images/1.jpg" alt="First slide" style="height: 575px !important;">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="../resources/views/assets/images/2.jpg" alt="Second slide" style="height: 575px !important;">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="../resources/views/assets/images/3.jpg" alt="Third slide" style="height: 575px !important;">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
@endsection