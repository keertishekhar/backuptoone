<?php

include_once('untitled.php');



if(isset($_GET['ym'])){
$ym = $_GET['ym'];
}else{
    $ym = date('Y-m');
}
//check format
$timestamp = strtotime($ym,'-01');
if($timestamp === false){
    $timestamp = time();
}
//Today
$today = date('Y-m-d', time());

//for H3 title
$html_title = date('Y/m',$timestamp);

//pre and next month links

$pre = date('Y-m', mktime(0,0,0, date('m', $timestamp)-1,1,date('Y',$timestamp)));
$next = date('Y-m', mktime(0,0,0, date('m', $timestamp)+1,1,date('Y',$timestamp)));

// number of days in month 
$day_count = date('t',$timestamp);

$str = date('w', mktime(0,0,0, date('m', $timestamp),1,date('Y',$timestamp)));

$weeks = array();
$week = '';

$week .= str_repeat('<td></td>',$str);
$i = 0;
for($day=1;$day<=$day_count;$day++,$str++){
   
   if($day>9){
    $date = $ym.'-'.$day;
   }else{
       $date = $ym.'-0'.$day;
   }
   if($i >= count($results)){
       $i = count($results)-1;
   }
    if($results[$i] == $date){
        if($attends[$i]== 1){
        $week .= '<td class="today1">'.$day;
        }elseif($attends[$i]==2){
            $week .= '<td class="today2">'.$day;
        }
        $i++;
    }
    elseif($today ==  $date){
        $week .= '<td class="today">'.$day;
    }  
      else{
        $week .= '<td>'.$day;
    }


    $week .= '</td>';


//End of the week and End of the month

if($str % 7 ==6 || $day == $day_count){
    if($day == $day_count){
        $week .= str_repeat('<td></td>', 6- ($str % 7));
    }
    $weeks[] = '<tr class="table-danger">'.$week.'</tr>';

    $week = '';
}
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
    <style>
    .today{
        background-color: orange !important;
    }
    .today1{
        background-color: green !important;
    }
    .today2{
        background-color: red !important;
    }
    body{
        color: #001dff;
    }
    body tr{
        font-weight:400;
        font-size:30px;
        text-align:center;
        font-style:italic;
    }
    </style>
  </head>
  <body>
        <div class="container">
            <h3><a href="?ym=<?php echo $pre ;?>">&lt;</a><a><?php echo $html_title ;?></a> <a href="?ym=<?php echo $next ;?>">&gt;</a></h3>
            <br>
            <table class="table table table-striped table-bordered">
                <tr class="bg-danger">
                    <th>S</th>
                    <th>M</th>
                    <th>T</th>
                    <th>W</th>
                    <th>T</th>
                    <th>F</th>
                    <th>S</th>
                </tr>
                <?php 
                    foreach($weeks as $week ){
                        echo $week;
                    }
               ?>
            </table>
        </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>