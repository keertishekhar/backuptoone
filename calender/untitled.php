<?php 
  $con = mysqli_connect('localhost','root','','events');

  $select = "SELECT `date`, present FROM datatable where user_id = 1";
  $result = mysqli_query($con, $select);
 $results = array();
 $attends = array();
 if (mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_assoc($result)){
       
        $res = array_push($results,$row['date']);
        $att = array_push($attends,$row['present']);

 }
}
?>