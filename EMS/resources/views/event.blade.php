
 <?php 
 $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
                        $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
                        $currentYear=0;
                         
                        $currentMonth=0;
                         
                        $currentDay=0;
                         
                        $currentDate=null;
                         
                        $daysInMonth=0;
                         
                        $naviHref= null;

                        $year  = null;
         
                        $month = null;
                         
                        if(null==$year&&isset($_GET['year'])){
                 
                            $year = $_GET['year'];
                         
                        }else if(null==$year){
                 
                            $year = date("Y",time());  
                         
                        }          
                         
                        if(null==$month&&isset($_GET['month'])){
                 
                            $month = $_GET['month'];
                         
                        }else if(null==$month){
                 
                            $month = date("m",time());
                         
                        }                  
                         
                        $this->currentYear=$year;
                         
                        $this->currentMonth=$month;
                        if(null==($year))
                        $year =  date("Y",time()); 
             
                    if(null==($month))
                        $month = date("m",time());
                        $this->daysInMonth= date('t',strtotime($year.'-'.$month.'-01'));

                        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
         
                        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
                         
                        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
                         
                        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
                       
                        //askjdkl;fjas;dkjfal;ksdjf;asjdf;ljasd;lkfjasl;kdjfal;skdjfl;aksdjfkl;asjd;lfkjas

                        $year  = null;
         
        $month = null;
         
        if(null==$year&&isset($_GET['year'])){
 
            $year = $_GET['year'];
         
        }else if(null==$year){
 
            $year = date("Y",time());  
         
        }          
         
        if(null==$month&&isset($_GET['month'])){
 
            $month = $_GET['month'];
         
        }else if(null==$month){
 
            $month = date("m",time());
         
        }                  
         
        $this->currentYear=$year;
         
        $this->currentMonth=$month;



                ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<div class="container">
    <div class="row">
            <div class="panel panel-default">
                <div class="panel-head">
               
                                <a class="prev" href="<?php echo $this->naviHref ;?>?month=<?php echo sprintf('%02d',$preMonth); ?>&year=<?php echo $preYear ;?>">Prev</a>
                                    <span class="title"><?php echo date('Y M ',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')); ?></span>
                                <a class="next" href="<?php echo $this->naviHref ;?>?month=<?php echo sprintf("%02d", $nextMonth); ?>&year=<?php echo $nextYear ;?>">Next</a> 
                </div>
                <div class="panel-body">
                <a id="check"><li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).
                ($cellContent==null?'mask':'').'">'.$cellContent.'</li></a>';
                </div>
            </div>
    </div>
</div>


