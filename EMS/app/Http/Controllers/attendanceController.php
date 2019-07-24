<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class attendanceController extends Controller
{
    public function index(){
        if(Session::has('user')){
        $user = session('user');
        }else{
            $user->id = 0;
        }
        $attendance_data = DB::table('datatable')->where('user_id', $user->id)->get()->first();     
        $rows = DB::select("select * from datatable where user_id= ?",[$user->id]);
        $results = array();
        $attends = array();
     foreach ($rows as $row){
        $res = array_push($results,$row->date);
        $att = array_push($attends,$row->present);
     }
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
            if($rows != null && $user->id == $attendance_data->user_id){
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
        }else{
            $i = 0;
            for($day=1;$day<=$day_count;$day++,$str++){
                $date = $ym.'-'.$day;
                if($today ==  $date){
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
        }
            return view('/attendance',compact('weeks','pre','next','html_title'));
    }
    public function insert(Request $request){
            $date = $request->date;
            $user_id = $request->user_id;
            $present = $request->present;
            $attendance = DB::table('datatable')->insert(['date' => $date, 'user_id' => $user_id, 'present' => $present]);

        return redirect()->back()->with('message','Successfully Attended');
        }
}
