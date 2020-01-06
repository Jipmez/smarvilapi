<?php
require('mysqllab.php');
require('jwt/JWT.php');

class Search extends HandleSql{
    public function __construct($host,$username,$password,$db_name){
        parent::__construct($host,$username,$password,$db_name);
     }


     public function getlogdes()
     {
        $prop =[];
        /*  $this::naturalJoin('properties','state,category,properties.propertyID,uploadT,cost,pix1,boosted','propertypix',"properties.propertyID = propertypix.propertyID and properties.publish='1'","",'properties.boosted DESC,properties.Featured DESC,properties.sn DESC','10'); */
          $this::selectOrder('properties','state,category,properties.propertyID,Affiliated_university,title,uploadT,cost',"where publish ='1' and isApproved ='0' and Type='2'",'boosted DESC,Featured DESC,sn DESC');
         $f =$this::fetchQuery();
         $time =time();
         $count=0;
         foreach ($f as $key => $value) {
             if( $get_time= $f[$key]['uploadT']){
                $time = time();
               $diff= $time - $get_time;		
               switch(1)
                          {
                   case ($diff < 60):       //calculate seconds
                     $count=$diff;
                     if($count==0)
                     $count="a moment";
                   elseif($count==1)
                       $suffix="second";
                   else
                     $suffix="seconds";
                   break;
                   
                   case ($diff > 60 && $diff < 3600): //calculate minutes
                     $count=floor($diff/60);
                     if($count==1)
                     $suffix="minute";
                   else
                       $suffix="minutes";
                   break;
                   
                   case ($diff > 3600 && $diff < 86400):   //calculate hours
                     $count=floor($diff/3600 );
                     if($count==1)
                     $suffix="hour";
                   else
                       $suffix="hours";
                   break;
                   
                   case ($diff>86400 && $diff < 604800): //calculate days
                     $count=floor($diff/86400);
                     if($count==1)
                     $suffix="day";
                   else
                       $suffix="days";
                   break;
                   
                   case ($diff>604800 && $diff < 2628002.88): //calculate weeks
                     $count=floor($diff/604800);
                     if($count==1)
                     $suffix="week";
                   else
                       $suffix="weeks";
                   break;
                     
                   case ($diff>2628002.88 && $diff < 31536000): //calculate months
                     $count=floor($diff/2628002.88);
                     if($count==1)
                     $suffix="month";
                   else
                       $suffix="months";
                   break;
                   
                   case ($diff>31536000): //calculate years
                     $count=floor($diff/31536000);
                     if($count==1)
                     $suffix="year";
                   else
                       $suffix="years";
                   break;
                          }
                          if ($get_time==0){$lseen="Acc unused";}
                          
                          elseif(!isset($suffix)) { $lseen=$count." ago ";}
                                                            else{
                                                                 $lseen=$count." ".$suffix;
                                                                 }  
             }
             $f[$key]['uploadT'] = $lseen;
             $f[$key]['cost'] = number_format( $f[$key]['cost']);
            }
       
            foreach($f as $f){
             $propID = $f['propertyID'];
             $this::selectQueryL('propertypix','pix,pix_id',"where propertyID='$propID'",'sn',1);
               $p =$this::fetchQuery();
              if($this::checkrow()  == 1){
                foreach($p as $p){
                 $f['pix'] = $p['pix'];
                }
              }else{
               $f['pix'] = '';
              }
             array_push($prop,$f); 
           }
         return  array('code' =>'1' ,'message'=>$prop);
     } 


    public function getRoom(){
        $this::selectQuery('membership_rommie','Name,LastName,Gender,Age,Budget,Propix',"where Status = '2' and Active = '1'");
        if( $f = $this::fetchQuery()){
            return $f;
        };
    }

}
?> 