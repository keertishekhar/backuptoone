@extends('employeeprofile')
@section('ajaxdata')
hi this is trait_exists
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ajax Data</h4>
      </div>
      <div class="modal-body">
        <p id="get">Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script src="http://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script> 
<script>
$(document).ready(function(){
    var $listgroup = $(".list-groups");
		$(".button1").on('click',function(){
		$.ajax({
			url: "http://127.0.0.1:8000/ajaxdata1",
			method:"GET",
			datatype: "JSON",
			success: function(response){
			var data = $.parseJSON(response);
			var data1 = "Name->" +data['name'] + "   ";
			var data2 = "Email Address->" + data['email'];
			var data = data1.concat(data2);
					$("#get").html(data);
			},
			error: function(err){
			
			}
		
		});
	});
});
</script>

@endsection
   
    