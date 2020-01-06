<?php


use Firebase\JWT\JWT;
use Mailgun\Mailgun;
require('mysqllab.php');
require('jwt/JWT.php');
require "vendor/autoload.php";
require "config-cloud.php";
require('mail.php');
define('secret_key','getoffyourhighhorse');



  
class Reg extends HandleSql{



    public function __construct($host,$username,$password,$db_name){
       parent::__construct($host,$username,$password,$db_name);
    }



    public function enc($issuer,$audience,$user_id)
    {
       $token = array(
           "iss" => $issuer,
           "aud" => $audience ,
           "id"=> $user_id,
           "iat" => time(),
           "nbf" => time()
       );
   
   
       $jwt=new JWT;
       //$jwt=JWT::encode($token, $key);
       $call=$jwt::encode($token,secret_key);
       return $call;
   
   }


   public function getid($token){
    $jwt= new JWT;
    $call=$jwt::decode($token,secret_key, array('HS256'));
    $decoded_array= (array) $call;
    $decoded=$decoded_array['id'];
    return $decoded;
    
  }

public function Pupload($input,$token)
{
  $clean = $this::clean($input);
  $id_no = $this::getid($token);
  $propertyID =  rand(1000000, 10000000);
  $reg_id = $id_no[1];
  $Address = $clean[0];
  $category = $clean[1];
  $type = $clean[2];
  $subtype =$clean[3];
   $this::selectQuery('properties','address',"where address = '$Address'");
   if($this::checkrow()< 1){
     $this::selectQuery('membership_users','usertype,listing',"where reg_id ='$reg_id'");
     $f = $this::fetchQuery();
      $usertype = $f[0]['usertype'];$listing = $f[0]['listing'];
    
      if($usertype == '3' || $usertype=='4' && $listing > 0){
    $insert =   $this::insertQuery('properties','reg_id,propertyID,address,category,type,Subtype',"'$reg_id','$propertyID','$Address','$category','$type','$subtype'");
    if($insert){
     /*  $this::insertQuery('propertypix','reg_id,propertyID',"'$reg_id','$propertyID'"); */
      $listing = $listing - 1;
      $this::update('membership_users',"listing = '$listing'","where reg_id = '$reg_id'");

      return  array('code' => 1,'message'=>'success', 'propid'=>$propertyID);
    }
  }else{
    return array('code'=> 3, 'message'=>'you dont have any listing available');
  }
   } else {
    return  array('code' => 2,'message'=>'Address already exist');
   }

}

public function propUp($input,$token,$amenities)
{
   $clean = $this::neat($input);
  
   $id_no = $this::getid($token);
   $reg_id = $id_no[1];
   $pub = $clean['publish'];
   $title = $clean['title'];
   $status =$clean['status'];
   $category = $clean['category'];
   $subtype = $clean['subtype'];
   $state = $clean['state'];
   $locality = $clean['locality'];
   $street = $clean['street'];
   $cost =$clean['price'];
   $bedroom =$clean['bedroom'];
   $toilets = $clean['toilets'];
   $bathrooms =$clean['bathrooms'];
   $parking =$clean['parking'];
   $totalArea = $clean['totalarea'];
   $roomsize =$clean['roomsize'];
   $video =$clean['video'];
   $discription =$clean['discription'];
   $propID =$clean['propid'];
   $time = time();
   $amenities = implode(",",$amenities);
   $this::selectQuery('properties','reg_id,propertyID'," where reg_id = '$reg_id' and propertyID = '$propID'");
   if($this::checkrow() == 1){

      $this::update('properties',"title='$title', state ='$state', locality = '$locality' , street= '$street', bedroom ='$bedroom' , bathroom = '$bathrooms', rmsize ='$roomsize', toilets= '$toilets',Parking ='$parking', TotalArea = '$totalArea', Amenities='$amenities',uploadT ='$time', Subtype = '$subtype', marketS ='$status', videoL = '$video', Description = '$discription' , cost = '$cost' , publish = '$pub'", "where reg_id ='$reg_id' and propertyID = '$propID'");

      return ['code'=>1];
   } 
    /* $pi1 = $this::PrepareUploadedFile($gb[0],"510000");
    $piz2 = $this::PrepareUploadedFile($gb[1],"510000"); */

     
    
  }

  public function PixUp($gb,$token,$propID)
  {
    $id_no = $this::getid($token);
    $reg_id = $id_no[1]; 

      $f= $this::PrepareUploadedFile($gb['image'],"510000");
      if($f){
        $p= $f['secure_url'];
        $pix_id = $this::random_alphanumeric(6);
        $this::insertQuery('propertypix','reg_id,propertyID,pix_id,pix',"'$reg_id','$propID','$pix_id','$p'");
      }else{
        return array('code'=>'2');
      }
  
  return array('code'=>1,'message'=>$p);
  }

  public function rommieImg($files,$token)
  {
    $id_no = $this::getid($token);
    $reg_id = $id_no[1];
    $pi1 = $this::PrepareUploadedFile($files['image'],"510000");
    if($pi1){
      $this::selectQuery('membership_rommie','Reg_id',"where Reg_id ='$reg_id'");
      if($this::checkrow()==1){
        $fpix = $pi1['secure_url'];
        $update = $this::update('membership_rommie',"ProPix = '$fpix'","where Reg_id ='$reg_id'");
        if($update){
          return array('code'=>1,'message'=>$fpix);
        }
  
      }
    }
  }

 public function rommieProImg($files,$token){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $pi1 = $this::PrepareUploadedFile($files['image'],"510000");
  if($pi1==true){
    $this::selectQuery('roomie_pro','reg_id',"where reg_id ='$reg_id'");
    if($this::checkrow()==1){
      $fpix = $pi1['secure_url'];
      $update = $this::update('roomie_pro',"pix = '$fpix'","where reg_id ='$reg_id'");
      if($update){
        return array('code'=>1,'message'=>$pi1);
      }

    }
  }
  }

  public function uImg($files,$token){
    $id_no = $this::getid($token);
    $reg_id = $id_no[1];
    $pi1 = $this::PrepareUploadedFile($files['image'],"510000");
    if($pi1==true){
      $this::selectQuery('membership_users','reg_id',"where reg_id ='$reg_id'");
      if($this::checkrow()==1){
        $fpix = $pi1['secure_url'];
        $update = $this::update('membership_users',"pix = '$fpix'","where reg_id ='$reg_id'");
        if($update){
          return array('code'=>1,'message'=>$fpix);
        }
  
      }
    }
  }

public function MyProp($token)
{
  $prop=[];
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
 /*  $this::naturalJoin('properties','*','propertypix',"properties.propertyID = propertypix.propertyID","where properties.reg_id='$reg_id' and propertypix.reg_id ='$reg_id'",'properties.sn DESC',12); */
 $this::selectQueryLM('properties','*',"where reg_id='$reg_id'",'boosted DESC,Featured DESC,sn DESC','12');
  $f =$this::fetchQuery();
 
  foreach ($f as $key => $value) {
    $f[$key]['cost'] = number_format( $f[$key]['cost']);
  }
 
  foreach($f as $f) {
    $propID = $f['propertyID'];
      $this::selectQueryL('propertypix','pix,pix_id',"where propertyID='$propID' and reg_id='$reg_id'",'sn',1);
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

public function getallProp(){
  $prop=[];

 /*  $this::naturalJoin('properties','*','propertypix',"properties.propertyID = propertypix.propertyID","where properties.reg_id='$reg_id' and propertypix.reg_id ='$reg_id'",'properties.sn DESC',12); */
//  $this::selectQueryLM('properties','*','','boosted DESC,Featured DESC,sn DESC','*');
 $this::selectQuery('properties','*','');
  $f =$this::fetchQuery();
 
  foreach ($f as $key => $value) {
    $f[$key]['cost'] = number_format( $f[$key]['cost']);
  }
 
  foreach($f as $f) {
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
public function getProp()
{

$prop =[];
  $this::selectQueryLM('properties','*',"where publish='1'",'boosted DESC,Featured DESC,sn DESC','7');
  $f =$this::fetchQuery();
  foreach ($f as $key => $value) {
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




 public function getlogdes()
{
  $prop =[];
 /*  $this::naturalJoin('properties','state,category,properties.propertyID,uploadT,cost,pix1,boosted','propertypix',"properties.propertyID = propertypix.propertyID and properties.publish='1'","",'properties.boosted DESC,properties.Featured DESC,properties.sn DESC','10'); */
   $this::selectOrder('properties','state,category,properties.propertyID,uploadT,cost,boosted,Featured',"where publish ='1' and isApproved ='0'",'boosted DESC,Featured DESC,sn DESC');
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


public function filterUni($university){
  $prop =[];
  /*  $this::naturalJoin('properties','state,category,properties.propertyID,uploadT,cost,pix1,boosted','propertypix',"properties.propertyID = propertypix.propertyID and properties.publish='1'","",'properties.boosted DESC,properties.Featured DESC,properties.sn DESC','10'); */
    $this::selectOrder('properties','state,category,properties.propertyID,Affiliated_university,title,uploadT,cost,boosted,Featured',"where publish ='1' and isApproved ='0' and Type='2' and Affiliated_university='$university'",'boosted DESC,Featured DESC,sn DESC');
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


public function getStudentlogdes(){
  $prop =[];
  /*  $this::naturalJoin('properties','state,category,properties.propertyID,uploadT,cost,pix1,boosted','propertypix',"properties.propertyID = propertypix.propertyID and properties.publish='1'","",'properties.boosted DESC,properties.Featured DESC,properties.sn DESC','10'); */
    $this::selectOrder('properties','state,category,properties.propertyID,Affiliated_university,title,uploadT,cost,boosted,Featured,Amenities',"where publish ='1' and isApproved ='0' and Type='2'",'boosted DESC,Featured DESC,sn DESC');
   $f =$this::fetchQuery();
  
   $amenities = explode(",",$f[0]['Amenities']);
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
       $f['Amenities']  = explode(",",$f['Amenities']);
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


public function getTime($arr){
    
  foreach ($arr as $key => $value) {
   if( $get_time= $arr[$key]['uploadT']){;
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
  }
   return $lseen;
}

public function singleProp($property)
{
  $prop =[];
  $this::selectQuery('properties','*',"where propertyID = '$property'");
 $fect = $this::fetchQuery();
 $state = $fect[0]['state'];
 $bedroom =$fect[0]['bedroom'];
 $locality =$fect[0]['locality'];
 $amenities = explode(",",$fect[0]['Amenities']);
 foreach ($fect as $key => $value) {
  $fect[$key]['cost'] = number_format( $fect[$key]['cost']);
  $fect[$key]['uploadT'] = $this::getTime($fect);
}
foreach($fect as $fect){
  $propID = $fect['propertyID'];
  $this::selectQuery('propertypix','pix,pix_id',"where propertyID='$propID'");
  $count = count($this::fetchQuery());
  $this::selectQueryL('propertypix','pix,pix_id',"where propertyID='$propID'",'sn',1);
    $p =$this::fetchQuery();
   if($this::checkrow()  == 1){
     foreach($p as $p){
      $fect['pix'] = $p['pix'];
     }
   }else{
    $fect['pix'] = '';
   }
   $fect['pixcount'] = $count;

  array_push($prop,$fect); 
}




// average country 
$this::selectQuery('properties','cost',"where bedroom ='$bedroom'");
$c = $this::fetchQuery();
 if(count($c)){
  $a = array_filter($c);
  $sum = 0;
  foreach ($a as $item) {
      $sum += $item['cost'];
  }
  $averageCountry =number_format($sum/count($c));
 
 }

// average state 
$this::selectQuery('properties','cost',"where bedroom ='$bedroom' and state ='$state'");
$s = $this::fetchQuery();
 if(count($s)){
  $a = array_filter($s);
  $sumS = 0;
  foreach ($a as $item) {
      $sumS += $item['cost'];
  }
  $averageState =number_format($sumS/count($s));
 }


// average local 
$this::selectQuery('properties','cost',"where bedroom ='$bedroom' and locality ='$locality'");
$l = $this::fetchQuery();

 if(count($l)){
  $a = array_filter($l);
  $suml = 0;
  foreach ($a as $item) {
      $suml += $item['cost'];
  }
  $averageLocal =number_format($suml/count($l));
 }

 $this::selectQuery('properties','Views',"where propertyID ='$property'");
 if($this::checkrow() == 1){
  $f =  $this::fetchQuery(); $views = $f[0]['Views'] + 1;
  $this::update('properties',"Views = '$views'","where propertyID ='$property'");
 }

 return array('code'=>'1','message'=>$prop,'countryA'=>$averageCountry,'stateA'=>$averageState,'localA'=>$averageLocal,'amens'=>$amenities);
}

public function RegisterUser($input)
{
  $clean =  $this::clean($input);
  $name =  $clean[0]; $lastname = $clean[1]; $username = $clean[2];$email = $clean[3];$usertype = $clean[4];$password = $clean[5];
  $time = time();
  $rand = $this::random_alphanumeric(8);
  $date = date('Y-m-d');
  $e = $this::ValidateEmail($email);
  $fn = $this::pregmatch($username);
  $hash = openssl_random_pseudo_bytes(32);
  $hash = bin2hex($hash);

  if($e && $fn == true)
  {
    if($usertype == 2 || $usertype == 3 || $usertype == 4){
      $this::selectQuery('membership_users','email,username',"where email = '$e' && username = '$fn'");
      if($this::checkrow()<1)
      {
        $insert = $this::insertQuery('membership_users','reg_id,email,username,pass,hash,name,lastname,usertype,register_date',"'$rand','$e','$fn','$password','$hash','$name','$lastname','$usertype','$date'");
        if($insert)
        {
          
         /*  $link = "http://www.yourwebsite.com/verify.php?email='.$email.'&hash='.$hash.'"; */
         $message = 'Thank you for signing up! <br> 
         Your Account have been created, We may need to send you critical information about our service and it is important that we have an accurate email address. 
         Please click on the url below';
          $link ="http://localhost:4200/#/verify/{$email}/{$hash}";
         
           $me = mail::passmail('noreply@smartvil.com','mezj972@gmail.com','Signup | Verification',$message,$link);
          
           if($me = true){  return array('code'=>'1','message'=>'account created');}
         
        }
      }else{
        return array('code'=>'2');
      }
    }else if($usertype == 1){
      $this::selectQuery('membership_users','email,username',"where email = '$e' && username = '$fn'");
      if($this::checkrow()<1)
      {
        $insert = $this::insertQuery('membership_users','reg_id,email,username,pass,hash,name,lastname,usertype,register_date',"'$rand','$e','$fn','$password','$hash','$name','$lastname','$usertype','$date'");
        if($insert)
        {
          $this::insertQuery('membership_rommie','Email,Reg_id,Name,LastName,Status',"'$e','$rand','$name','$lastname','1'");

          $message = 'Thank you for signing up! <br> 
         Your Account have been created, We may need to send you critical information about our service and it is important that we have an accurate email address. 
         Please click on the url below';
          $link ="http://localhost:4200/#/verify/{$email}/{$hash}";

          $me = mail::passmail('noreply@smartvil.com','mezj972@gmail.com','Signup | Verification','welcome to roomates.com',$link);
          if($me = true){ return array('code'=>'1','message'=>'account created');}
        }
      }else{
        return array('code'=>'2');
    }
  }else{
    return array('code'=>'2');;
  }
}
}

public function LoginUser($input)
{
        $eliminate = $this::clean($input);
        
        $username = $eliminate[0];
        $pass = $eliminate[1];
        $password = $input['password'];
        $time = date('h:i:s');
        $Un = $this::pregmatch($username);
       
        if($Un)
        {
         
          $this::selectQuery('membership_users','reg_id,username,pass,usertype,status',"where username = '$Un'");
          if($this::checkrow() == 1){
            $f = $this::fetchQuery();
           
            if($f[0]['username'] == $Un && password_verify($password,$f[0]['pass'])== true && $f[0]['status'] == 1 )
            {
             
              $usertype = $f[0]['usertype'];
             
              if($usertype == '2' || $usertype == '3' || $usertype == '4')
              {
                $issuer="http://localhost:4200";
                $audience= "http://localhost:/dashboard";
                $user_id = [$usertype,$f[0]['reg_id']];
                $token=$this->enc($issuer,$audience,$user_id);
                $this::update('membership_users',"last_login = '$time'","where username = '$username'");
   
              return array('code'=>1,'message'=>$token,'time'=>$time);

              }else if($usertype == '1'){
                
                $issuer="http://localhost:4200";
                $audience= "http://localhost:/dash";
                $user_id = [$usertype,$f[0]['reg_id']];
                $token=$this->enc($issuer,$audience,$user_id);
                $this::update('membership_users',"last_login = '$time'","where username = '$username'");

              return array('code'=>2,'message'=>$token,'time'=>$time);
              }
            }else{
              return ['code'=>6];
            }
          }

        }else{
          return ['code'=>3];
        }
}
public function adLog($input){
  $neat = $this::neat($input);
  $pass =$input['password'];
  $time = date("h:i:s");
  $un = strtolower($this::pregmatch($neat['username'])); 
 
  if($un){
    $this::selectQuery('admin_users',"userName,passMD5,groupID,isBanned","where userName = '$un'"); 
    if($this::checkrow() == 1){
      $f = $this::fetchQuery();
      if($f[0]['userName'] == $un && password_verify($pass,$f[0]['passMD5']) == true && $f[0]['isBanned'] == 0){
        $issuer="http://localhost:4200";
        $audience= "http://localhost:/admindash";
        $user_id = [$un,$f[0]['groupID']];
        $token=$this->enc($issuer,$audience,$user_id);
        return array('code'=>1,'message'=>$token,'time'=>$time);
      }else{
        return ['code'=>2];
      }
    }
  }
}

/** 
*   Sign up for admin
*/
public function adsign($input){
   $user = array(
     '1'=>'admin',
     '2'=>'admin_prop',
     '3'=>'admin_room',
     '4'=>'editor'
   );
$neat = self::neat($input);
 $username = strtolower($this::pregmatch($neat['username'])); 
 $email = $this::ValidateEmail($neat['email']);
 $password = $neat['password'];
 $group = $neat['group'];
 $time = time();
 $rand = $this::random_alphanumeric(8);
 $date = date('Y-m-d');
 if($username && $email == true){
    $memberID = $user[$neat['group']];
  $this::selectQuery('admin_users','userName,email',"where email ='$email' and userName = '$username'");
  if($this::checkrow() < 1){
  $insert =   $this::insertQuery('admin_users','memberID,passMD5,userName,email,signupDate,groupID,isBanned',"'$memberID','$password','$username','$email','$date','$group','0'") ?? false;
   
  return ['code'=>1,'message'=>'inserted'];
  }
 }
}


public function getDetail($propID,$token)
{
  $prop=[];
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $this::selectQuery('properties','*',"where propertyID='$propID'");
  $f = $this::fetchQuery();
  $this::selectQueryL('propertypix','pix,pix_id',"where propertyID='$propID' and reg_id='$reg_id'",'sn',1);
  $p =$this::fetchQuery();
  $pix = $p[0]['pix'] ?? '';
  foreach ($f as $key => $value) {
    $f[$key]['cost'] = number_format( $f[$key]['cost']);
  }
  foreach($f as $f) {
    foreach ($f as $key => $value) {
      $f['pix'] = $pix;
    }
    array_push($prop,$f);
 }

 
  return array('code'=>'1','message'=>$prop);  
}
   
public function adReview($propID,$token,$input)
{
  $clean = $this::clean($input);
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];

  $review = $clean[0];
  $name = $clean[1];
  $reason = $clean[2];

  $this::selectQuery('membership_users','email',"where reg_id = '$reg_id'");
  if($this::checkrow() == 1){
    $f = $this::fetchQuery();
    $email =$f[0]['email'];
   $insert =  $this::insertQuery('propreviews','propID,email,name,reasons,review',"'$propID','$email','$name','$reason','$review'");
   if($insert)
   {
     return  array('code' => 1, 'message'=>'success');
   }
  };
}

public function gtReview($propID)
{
  $this::selectQuery('propreviews','name,reasons,review'," where propID = '$propID'");
  $f = $this::fetchQuery();
  $count = count($f);
 return array('code' => 1,'message'=>$f , 'sum'=>$count );
 
}

public function adRommie($token,$input)
{
    $id_no = $this::getid($token);
    $reg_id = $id_no[1];
    $reg_e =$id_no[0];
    $clean = $this::clean($input);
    $purpose = $clean[0];
    $age = $clean[1];
    $gender =$clean[2];
    $education =$clean[3];
    $career =$clean[4];
    $hometown =$clean[5];
    $pets =$clean[6];
    $budget = $clean[7];
    $pstate =$clean[10];
    $plocal =$clean[11];
    $ptown =$clean[12];
    $this::selectQuery('states','name',"where state_id ='$pstate'");
    $f = $this::fetchQuery();
    $pstate = $f[0]['name'];
    
        /*  $budget = number_format($budget);
        print_r($budget);exit; */
    $relation =$clean[8];
    $about = $clean[9];
    $lease =$clean[13];
   
    $movein = $clean[14];
    $this::selectQuery('membership_rommie','email',"where reg_id ='$reg_id'");
    if($this::checkrow() == 1){
      $update =  $this::update('membership_rommie',"Gender = '$gender', Age = '$age', Purpose = '$purpose',Education ='$education' , Career = '$career',HomeTown = '$hometown', Budget = '$budget', Pets = '$pets',Relationship ='$relation',About='$about',Pstate='$pstate',Ptown='$plocal',Plocal='$ptown',Lease='$lease',Movin='$movein'","where Reg_id = '$reg_id'");
      if($update){
        return array('code'=>1,'purpose'=>$purpose);
      }
  }
  
}

public function EditRommie($token,$input){
  $id_no = $this::getid($token);
    $reg_id = $id_no[1];
    $reg_e =$id_no[0];
    $clean = $this::neat($input);
   
    $this::selectQuery('membership_rommie','*',"where reg_id ='$reg_id'");
    if($this::checkrow() == 1){
      $f = $this::fetchQuery();
    
      $gender =$clean['gender'] ?? $f[0]['Gender'];
      $age =$clean['age'] ?? $f[0]['Age'];
      $hometown =$clean['hometown'] ?? $f[0]['HomeTown'];
      $education =$clean['education'] ?? $f[0]['Education'];
      $career =$clean['career'] ?? $f[0]['Career'];
      $budget =$clean['budget'] ?? $f[0]['Budget'];
      $pets =$clean['pets'] ?? $f[0]['Pets'];
      $relation =$clean['relation'] ?? $f[0]['Relationship'];
      $about =$clean['about'] ?? $f[0]['About'];
      $this::selectQuery('states','name',"where state_id ='$pstate'");
      $f = $this::fetchQuery();
      $pstate =$clean['pstate'] ?? $f[0]['name'];
      $plocal =$clean['plocal'] ?? $f[0]['Plocal'];
      $ptown =$clean['ptown'] ?? $f[0]['Ptown'];
      $movin =$clean['movin'] ?? $f[0]['Movin'];
      $lease =$clean['lease'] ?? $f[0]['Lease'];
    
      $update =  $this::update('membership_rommie',"Gender = '$gender', Age = '$age',Education ='$education' , Career = '$career',HomeTown = '$hometown', Budget = '$budget', Pets = '$pets',Relationship ='$relation',About='$about',Pstate='$pstate',Ptown='$plocal',Plocal='$ptown',Movin ='$movin',Lease='$lease'","where Reg_id = '$reg_id'");
      if($update){
        return array('code'=>1);
      }
  }
   
}

public function rommieInfo($token,$input)
{
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $clean = $this::clean($input);

  $bed_time = $clean[0];
  if($bed_time == 1){$bed_time = 0;}; if($bed_time == 2){$bed_time = 25;};  if($bed_time == 3){$bed_time = 50;};  if($bed_time == 4){$bed_time = 75;};  if($bed_time == 5){$bed_time = 96;}
  $cleanliness =$clean[1];
  if($cleanliness == 1){$cleanliness = 0;}; if($cleanliness == 2){$cleanliness = 25;};  if($cleanliness == 3){$cleanliness = 50;};  if($cleanliness== 4){$cleanliness = 75;};  if($cleanliness == 5){$cleanliness= 96;}
  $cooking =$clean[2];
  if($cooking == 1){$cooking = 0;}; if($cooking == 2){$cooking = 25;}  if($cooking == 3){$cooking = 50;};  if($cooking == 4){$cooking = 75;};  if($cooking == 5){$cooking = 96;}
  
  $drinks =$clean[3];
  if($drinks == 1){$drinks = 0;}; if($drinks == 2){$drinks = 25;}  if($drinks == 3){$drinks = 50;};  if($drinks == 4){$drinks = 75;};  if($drinks == 5){$drinks = 96;}
  $drugs =$clean[4];
  if($drugs  == 1){$drugs  = 0;}; if($drugs  == 2){$drugs  = 25;};  if($drugs  == 3){$drugs  = 50;};  if($drugs  == 4){$drugs  = 75;};  if($drugs  == 5){$drugs  = 96;}
  $privacy =$clean[5];
  if($privacy == 1){$privacy = 0;}; if($privacy == 2){$privacy = 25;};  if($privacy == 3){$privacy = 50;};  if($privacy == 4){$privacy = 75;};  if($privacy == 5){$privacy = 96;}
  $smokes = $clean[6];
  if($smokes  == 1){$smokes  = 0;}; if($smokes == 2){$smokes  = 25;};  if($smokes  == 3){$smokes  = 50;};  if($smokes  == 4){$smokes  = 75;};  if($smokes  == 5){$smokes  = 96;}
  $social =$clean[7];
  if($social == 1){$social = 0;}; if($social == 2){$social = 25;};  if($social == 3){$social = 50;};  if($social == 4){$social = 75;};  if($social == 5){$social = 96;}
  $working_hours =$clean[8]; 
  if($working_hours == 1){$working_hours = 0;}; if($working_hours == 2){$working_hours = 25;};  if($working_hours == 3){$working_hours = 50;};  if($working_hours == 4){$working_hours = 75;};  if($working_hours == 5){$working_hours = 96;}
  
  $this::selectQuery('membership_rommie','Reg_id',"where Reg_id ='$reg_id'");
  if($this::checkrow() == 1){
   $update =  $this::update('membership_rommie',"Bed_time = '$bed_time', Cleanliness = '$cleanliness', Cooking = '$cooking', Drinks='$drinks' ,Drugs ='$drugs' , Privacy = '$privacy', Smokes = '$smokes', Social = '$social', Working_hours = '$working_hours'","where Reg_id = '$reg_id'");
   if($update){
     return array('code' =>1 , 'message'=>'success');
   }
  }
}
 public function moveTodash($token){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $update = $this::update('membership_rommie',"Status ='2'","where Reg_id ='$reg_id'");
  if($update){
    return array('code'=>1);
  }
 }

public function rommieFilter($token,$input)
{
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $clean = $this::clean($input);
  
  $cats = $clean[0];if($cats == 1){$cats = 'Cats';}
  $dogs = $clean[1];if($dogs == 1){$dogs = 'Dogs';};
  $max_budget = $clean[2];
  $min_budget = $clean[3];
  $age_max=$clean[4];
  $age_min=$clean[5];
  $gender=$clean[6];
  
  $no_pets=$clean[7];
  $mutual=$clean[8];
  $pet_friendly=$clean[9];
  $purpose=$clean[10];
   if($mutual == true){ 
    $this::selectQuery('membership_rommie','State,Town,Education',"where Reg_id='$reg_id'");
    $f = $this::fetchQuery();
    $state = $f[0]['State'];
    $town = $f[0]['Town'];
    $education = $f[0]['Education'];
    
    //FIXME:  add pets to this line of code
   $this::between('*','membership_rommie',"where (Gender='$gender' OR '$gender' = '') and Budget between '$min_budget' and '$max_budget'  and (Purpose ='$purpose' OR '$purpose' ='') and (State='$state' OR Town='$town' OR Education='$education') and Age between '$age_min' and '$age_max' and Reg_id !='$reg_id' and Active = 1");
   $c= $this::fetchQuery();
  
  return array('code'=>1, 'message'=>$c);
   }else{
    $this::between('*','membership_rommie',"where (Gender='$gender' OR '$gender' = '') and Budget between '$min_budget' and '$max_budget'  and (Purpose ='$purpose' OR '$purpose' ='') and Age between '$age_min' and '$age_max' and Reg_id !='$reg_id' and Active = 1");
    $c= $this::fetchQuery();
    return array('code'=>1, 'message'=>$c);
  } 
}
//FIXME:
public function rommieGet($token)
{
  $id_no = $this::getid($token);$reg_id = $id_no[1]; 
   $this::selectQuery('membership_rommie','Age,Pstate,Ptown,Plocal,Pets,HomeTown,Education',"where Reg_id='$reg_id'");
   $p = $this::fetchQuery();
   $Age = $p[0]['Age'];
   $Pstate = $p[0]['Pstate'];
   $Plocal = $p[0]['Plocal'];
   $Ptown = $p[0]['Ptown'];
  $HomeTown = $p[0]['HomeTown'];
  $education = $p[0]['Education'];
  $AgeUp = $Age + 5;
  $this::selectQuery('membership_rommie','*',"where  HomeTown='$HomeTown' and (Pstate='$Pstate' or Plocal='$Pstate' or Ptown='$Pstate') and Age <= '$AgeUp' and Reg_id !='$reg_id'");
  if($this::checkrow()>0){
    $f = $this::fetchQuery();
    return array('code'=>1, 'message'=>$f);
  }else{
    return array('code'=>2);
    }
}


public function rommieProfile($token)
{
  $id_no = $this::getid($token);$reg_id = $id_no[1];$this::selectQuery('membership_rommie','*',"where Reg_id='$reg_id'");
  if($this::checkrow() == 1){
   $f = $this::fetchQuery();
   foreach ($f as $key => $value) {
    $f[$key]['Budget'] = number_format( $f[$key]['Budget']);
  }
  if($f[0]['Purpose'] == 2){
    $this::selectQuery("roomie_pro","*","where reg_id ='$reg_id'");
    if($this::checkrow()== 1){
      $p = $this::fetchQuery();
      $amenities = explode(",",$p[0]['amenities']);
      foreach ($p as $key => $value) {
        $p[$key]['rent'] = number_format( $p[$key]['rent']);
      }
    
    }
    return array('code'=>1, 'message'=>$f,'pro'=>$p,'amen'=>$amenities);
  }else{
    return array('code'=>1, 'message'=>$f);
  }

  }else{
    echo 'u cant assess this page';
  }
}
public function getrProfile($id)
{
  $this::selectQuery('membership_rommie','*',"where Reg_id='$id'");
  if($this::checkrow() == 1){
   $f = $this::fetchQuery();
   foreach ($f as $key => $value) {
    $f[$key]['Budget'] = number_format( $f[$key]['Budget']);
  }
  if($f[0]['Purpose'] == 2){
    $this::selectQuery("roomie_pro","*","where reg_id ='$id'");
    if($this::checkrow()== 1){
      $p = $this::fetchQuery();
      print_r($p);exit;
      $amenities = explode(",",$p[0]['amenities']);
      foreach ($p as $key => $value) {
        $p[$key]['rent'] = number_format( $p[$key]['rent']);
      }
      return array('code'=>1, 'message'=>$f,'pro'=>$p,'amen'=>$amenities);
    }else{
      return array('code'=>1, 'message'=>$f);
    }
   
  }else{
    return array('code'=>1, 'message'=>$f);
  }
 
  }else{
    echo 'u cant assess this page';
  }
}

public function filterProp($input)
{
  $prop=[];
  $neat = $this::neat($input);
 /*  print_r($neat);exit;  */
  $category =$neat['category'];
  $state = $neat['State'];
  $locality=$neat['locality'];
  $bedroom =$neat['bedroom'];
  $type =$neat['Market'];
  $ref = $neat['Ref'];
  $status =$neat['status'];
  $min =$neat['Min'];
  $max=$neat['Max'];
  $key =$neat['Keyword'];

  
  if( empty($min)  && empty($max) ){
   //FIXME:  add time to this line of code
   $this::selectOrder('properties',"*","where (category='$category' OR '$category'='') and (state='$state' OR '$state'='') and (locality='$locality' OR  '$locality'='') and (bedroom >= '$bedroom' OR '$bedroom' = '') and (Type='$type' OR '$type'='') and (propertyID='$ref' OR '$ref'='') and (marketS='$status' or '$status'='')  and publish =1","boosted DESC,Featured DESC,sn DESC");
   $f =$this::fetchQuery(); 
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
  } else{
    $this::selectOrder('properties',"*","where (category='$category' OR '$category'='') and (state='$state' OR '$state'='') and (locality='$locality' OR  '$locality'='') and (bedroom >= '$bedroom' OR '$bedroom' = '') and (Type='$type' OR '$type'='') and (propertyID='$ref' OR '$ref'='') and (marketS='$status' or '$status'='') and (cost between '$min' and '$max')  and publish = 1",'boosted DESC,Featured DESC,sn DESC'); 
    $f =$this::fetchQuery(); 
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
  }
 return array('code'=>1, 'message'=>$prop);
}

public function Vprop($x)
{
  $prop=[];
  $neat =$this::neat($x);
  $value =$neat['value'];
  $this::selectOrder('properties',"*","where Type ='$value'","boosted DESC,Featured DESC,sn DESC");
  $f =$this::fetchQuery(); 
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
  return array('code'=>1, 'message'=>$prop);
}

public function userP($input,$token)
{
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $neat = $this::neat($input);
  $cName = $neat['Cname'];
  $street =$neat['Street'];
  $locality =$neat['Locality'];
  $name =$neat['name'];
  $state =$neat['state'];
  $phone =$neat['phone'];
  $website =$neat['website'];
  $whatsapp =$neat['whatsapp'];
  $this::selectQuery('membership_users','name,companyName,state,locality,phonenumber,whatsapp,website',"where reg_id ='$reg_id'");
  $f=$this::fetchQuery();
  $fname = $f[0]['name'];
  $fstate =$f[0]['state'];
  $fcName = $f[0]['companyName'];
  $fclocal = $f[0]['locality'];
  $fcphone = $f[0]['phonenumber'];
  $fcwhatsapp = $f[0]['whatsapp'];
  $fcweb = $f[0]['website'];
  $fclocal = $f[0]['locality'];
  if(empty($cName)){$cName = $fcName; }if(empty($state)){$state = $fstate; }if(empty($name)){$name = $fname; }if(empty($locality)){$locality = $fclocal; }if(empty($phone)){$phone = $fcphone; }if(empty($whatsapp)){$whatsapp = $fcwhatsapp; }if(empty($website)){$website = $fcweb; }
  $update =  $this::update('membership_users',"name = '$name', companyName = '$cName', state = '$state', locality = '$locality', phonenumber='$phone', whatsapp='$whatsapp', website='$website'","where Reg_id = '$reg_id'");
if($update){
  return array('code'=>1, 'message'=>'sucess');
}
  
}

public function getUp($propID)
{
  $this::selectQuery('properties','reg_id',"where propertyID='$propID'");
  $fect =$this::fetchQuery();
  $reg_id =$fect[0]['reg_id'];
  $this::selectQuery('membership_users','reg_id,name,companyName,phonenumber,whatsapp,website,pix',"where reg_id='$reg_id'");
  $f =$this::fetchQuery();
  
  return array('code'=>1, 'message'=>$f);
}


public function payUp($token,$input){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $neat =$this::neat($input);
  $ref = $neat['ref'];
  $amount =$neat['amount'];
  $listing =$neat['listing'];
  $featured =$neat['featured'];
  $boosts =$neat['boosts'];
  $duration =$neat['duration'];
  $months =$neat['months'];
  $method = $neat['method'];
  $plan =$neat['plan'];
  $time = time();
  $this::selectQuery('subscription','payment_id',"where payment_id = '$ref'");
  if($this::checkrow() < 1){
    $this::insertQuery('subscription','reg_id,payment_id,subscription_period,subscription_duration,payment_method,plan,amount,payment_time,max_listing,max_feature,max_boost',"'$reg_id','$ref','$duration','$months','$method','$plan','$amount','$time','$listing','$featured','$boosts'");  
  }
  return array('code'=>'1','message'=>$ref);

}


public function getPay($token,$input){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $neat = $this::neat($input);
  $ref = $neat['ref'];
$this::selectQuery('subscription','*',"where reg_id='$reg_id' and payment_id='$ref'");
$f =$this::fetchQuery();
$this::selectQuery('membership_users','email,name',"where reg_id='$reg_id'");
$fe = $this::fetchQuery();

return array('code'=>'1','msg'=>$f,'msgg'=>$fe);
}

public function agentP($agent){
  $prop=[];
  $this::selectQuery('membership_users','*',"where reg_id ='$agent'");
  $f = $this::fetchQuery();
  $this::selectQuery('properties','cost',"where reg_id ='$agent'");
  $p = $this::fetchQuery();
  $this::selectQuery('agentreview','author_name,review_date,average_rating,review_content,author_relationship',"where agent_id='$agent'");
  $r =$this::fetchQuery();
  $this::selectOrder('properties','state,title,category,properties.propertyID,uploadT,cost,boosted',"where reg_id = '$agent' and publish = '1'","sn DESC");
  $h =$this::fetchQuery();
  foreach ($h as $key => $value) {
    $h[$key]['cost'] = number_format( $h[$key]['cost']);
  }
  foreach($h as $h){
    $propID = $h['propertyID'];
    $this::selectQueryL('propertypix','pix,pix_id',"where propertyID='$propID'",'sn',1);
      $Q =$this::fetchQuery();
     if($this::checkrow()  == 1){
       foreach($Q as $Q){
        $h['pix'] = $Q['pix'];
       }
     }else{
      $h['pix'] = '';
     }
    array_push($prop,$h); 
  }
 $max = max($p);$max =number_format($max['cost']);
 $min =min($p);$min = number_format($min['cost']);
 if(count($p)){
  $a = array_filter($p);
  $sum = 0;
  foreach ($p as $item) {
      $sum += $item['cost'];
  }
  $average =number_format($sum/count($p));
}
 return array('code'=>1,'info'=>$f,'average'=>$average,'reviews'=>$r,'properties'=>$prop,'max'=>$max,'min'=>$min);
}

public function agentRev($input,$agent_id){
  $neat = $this::neat($input);
  $agent_id =$neat['agent_id']; $author_name=$neat['name']; $relationship =$neat['relationship']; $address= $neat['address'];$content=$neat['content'];$response=$neat['response'];
  $negotiate =$neat['negotiate'];$knowledge=$neat['knowledge'];$expertise=$neat['expertise'];
  $review_date =  date('d/m/Y');
  $average_rate = (($response + $negotiate + $knowledge + $expertise)/20) * 5;
  $this::selectQuery('properties','propertyID,address',"where propertyID ='$address' || address ='$address'  and reg_id='$agent_id'");
  if($this::checkrow() == 1){
    $insert = $this::insertQuery('agentreview','agent_id,author_name,author_relationship,house_address,review_content,review_date,responsiveness,negotiation,knowledge,expertise,average_rating',"'$agent_id','$author_name','$relationship','$address','$content','$review_date','$response',$negotiate,'$knowledge','$expertise','$average_rate'");
  if($insert){
    return array('code'=>1,'message'=>'sucess');
  } 
  } else{
   return array('code'=>2,'message'=>'Address not found in our records');
  }
   
 
}

public function RevCalc($id){
  
  $this::selectQuery('agentreview','average_rating',"where agent_id = '$id'");
 $f =  $this::fetchQuery();
 $total_count = count($f);
 $sum = 0;
 foreach ($f as $item) {
     $sum += $item['average_rating'];
 }
$agent_rating = round((($sum)/($total_count*5))*5,2);  // agentrating

$this::update('membership_users',"rating ='$agent_rating'","where reg_id = '$id'");
 // calculate percentage Good
$this::selectQuery('agentreview','average_rating'," where agent_id='$id' and average_rating > 3");
 $fgood =$this::fetchQuery();
  $fgoodCount =count($fgood);
  $GoodPer = round(($fgoodCount/$total_count)*100,2); // percentage Good

   // calculate percentage medium
$this::selectQuery('agentreview','average_rating'," where agent_id='$id' and average_rating >= 2 and average_rating <= 3");
$fmedium =$this::fetchQuery();
 $fmediumCount =count($fmedium);
 $mediumPer = round(($fmediumCount/$total_count)*100,2); // percentage Medium

 // calculate percentage critical
$this::selectQuery('agentreview','average_rating'," where agent_id='$id' and average_rating <= 1 and average_rating < 2");
$fcritical =$this::fetchQuery();
 $fcriticalCount =count($fcritical);
 $criticalPer = round(($fcriticalCount/$total_count)*100,2); // percentage Medium
 
 //calculate responsiveness rating
 $this::selectQuery('agentreview','responsiveness',"where agent_id = '$id'");
 $fres =  $this::fetchQuery();
 $total_res = count($f);
 $resSum = 0;
 foreach ($fres as $item) {
     $resSum += $item['responsiveness'];
 }
$agent_res = round((($resSum)/($total_res*5))*5,2);// agent responsiveness

 //calculate negotiation rating
 $this::selectQuery('agentreview','negotiation',"where agent_id = '$id'");
 $fneg =  $this::fetchQuery();
 $total_neg = count($fneg);
 $negSum = 0;
 foreach ($fneg as $item) {
     $negSum += $item['negotiation'];
 }
$agent_neg = round((($negSum)/($total_res*5))*5,2); // agent negotiation


//calculate local knowledge rating
$this::selectQuery('agentreview','knowledge',"where agent_id = '$id'");
$fknow =  $this::fetchQuery();
$total_know = count($fknow);
$knowSum = 0;
foreach ($fknow as $item) {
    $knowSum += $item['knowledge'];
}
$agent_know = round((($knowSum)/($total_res*5))*5,2); // agent local knowlegede


//calculate expertise rating
$this::selectQuery('agentreview','expertise',"where agent_id = '$id'");
$fexp =  $this::fetchQuery();
$total_exp = count($fexp);
$expSum = 0;
foreach ($fexp as $item) {
    $expSum += $item['expertise'];
}
$agent_exp = round((($expSum)/($total_res*5))*5,2); // agent expertise

return array('code'=>1,'rating'=>$agent_rating,'Good'=>$GoodPer,'medium'=>$mediumPer,'critical'=>$criticalPer,'response'=>$agent_res,'negotiate'=>$agent_neg,'knowledge'=>$agent_know,'expertise'=>$agent_exp);
}
public function romateP($token){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $this::selectQuery('membership_rommie','Name,ProPix,Status',"where Reg_id ='$reg_id'");
  $f= $this::fetchQuery();
  return array('code'=>1, 'message'=>$f);
}

public function gtRblog(){
  $this::lastDsc('blogs','*',"where category ='2' and posted = 'publish'",'id');
  $f = $this::fetchQuery();
  $this::firstAscc('blogs','title,content,photo,date',"where category ='2' and posted ='publish'",'id',4);
  $p = $this::fetchQuery();

  return array('code'=>1,'blogs'=>$f,'olderposts'=>$p);
}
public function gtPblog(){
  $this::lastDsc('blogs','*',"where category ='1' and posted = 'publish'",'id');
  $f = $this::fetchQuery();
  $this::firstAscc('blogs','title,content,photo,date',"where category ='1' and posted ='publish'",'id',3);
  $p = $this::fetchQuery();

  return array('code'=>1,'blogs'=>$f,'olderposts'=>$p);
}
public function Postblog($input,$token){
  $neat = $this::neat($input);
  $id_no = $this::getid($token);
  $author = $id_no[0];
  $date = date('Y-m-d');
  $title = $neat['title'];
  $category = $neat['category'];
  $tags = $neat['tags'];
  $content =$neat['content'];
  $posted = $neat['posted'];
  $blog_id =$neat['blogid'];
  $this::selectQuery('blogs','*',"where blog_id='$blog_id'");
  $f = $this::fetchQuery();
  $ftitle = $f[0]['title'];
  $fcategory = $f[0]['category'];
  $ftags =$f[0]['tags'];
  $fcontent =$f[0]['content'];
  $fposted =$f[0]['posted'];
  if(empty($title)){$title =$ftitle;}if(empty($category)){$category = $fcategory;}if(empty($tags)){$tags =$ftags;}if(empty($content)){$content = $fcontent;}if(empty($posted)){$posted =$fposted;}
  $update =$this::update('blogs',"title = '$title',category = '$category', tags ='$tags', content ='$content', posted='$posted', date = '$date'","where blog_id = '$blog_id'");
  if($update){
    return array('code'=>1, 'message'=>'saved');
  }else{
    return 'failed';
  }
}

public function Addblog($input,$token){
  $neat =$this::neat($input);
   $id_no = $this::getid($token);
   $author = $id_no[0];
  $title =$neat['title'];
  $category = $neat['category'];
  $date =date('Y-m-d');
  $blog_id = $this::random_alphanumeric(4);
  $this::selectQuery('blogs','blog_id,title',"where blog_id ='$blog_id' or title = '$title'");
  if($this::checkrow() < 1){
   $insert =  $this::insertQuery('blogs','blog_id,title,category,author',"'$blog_id','$title',$category,'$author'");
   if($insert){
     return array('code' => 1, 'message'=>$blog_id);
    
   }
  }
}
public function blogImg($file,$blog_id){
   $pi1 = $this::PrepareUploadedFile($file,"510000");
   if($pi1==true){
     $this::selectQuery('blogs','blog_id',"where blog_id ='$blog_id'");
     if($this::checkrow()==1){
       $fpix = $pi1['secure_url'];
       $update = $this::update('blogs',"photo= '$pi1'","where blog_id ='$blog_id'");
       if($update){
         return array('code'=>1,'message'=>$pi1);
       }
 
     }
   }
}
public function getAuthor($blog_id,$token){
  $id_no = $this::getid($token);
  $author = $id_no[0];
  $date =date('Y-M-D');
  $this::selectQuery('blogs','*',"where blog_id = '$blog_id' and author = '$author'");
  $f = $this::fetchQuery();
  return array('code'=>1, 'message'=>$f,'date'=>$date);
}
public function getPermitU($token){
  $id_no = $this::getid($token);
  $permit = $id_no[0];

  return array('code'=>1, 'message'=>$permit);
}
public function getPermitA($token){
  $id_no = $this::getid($token);
  $permit = $id_no[1];

  return array('code'=>1, 'message'=>$permit);
}
public function getUID($token){
  $id_no = $this::getid($token);
  $permit = $id_no[1];
  $reg = $id_no[0];
  return array('code'=>1, 'message'=>$permit,'reg'=>$reg);
}
//TODO:  incomplete like function(not working properly)
 public function likeP($token,$prop){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $acc = $id_no[0];
  $this::selectQuery('property_likes','reg_id,property_id,status',"where reg_id = '$reg_id' and property_id='$prop'");
 $f= $this::fetchQuery();
 $status = $f[0]['status'];
  if($acc <> 1){
    $this::selectQuery('property_likes','reg_id,property_id,status',"where reg_id = '$reg_id' and property_id='$prop'");
    if($this::checkrow() == 0){
      $this::insertQuery('property_likes','reg_id,property_id,status',"'$reg_id','$prop','1'");
      return array('code'=>1);
    }else if($this::checkrow() == 1){
      if($status == 0){
        $this::update('property_likes',"status = '1'","where reg_id = '$reg_id' and property_id='$prop'");
        return array('code'=>3);
      }else{
        $this::update('property_likes',"status = '0'","where reg_id = '$reg_id' and property_id='$prop'");
        return array('code'=>2);
      }
     
    } 
  }else{
    return array('code'=>4);
  }
}
public function SendM($token,$input){
  $neat=$this::neat($input);
  $message = $neat['message'];
  $reciever = $neat['reciever'];

  $id_no = $this::getid($token);
  $pchat = $this::random_alphanumeric(5);
  $reg_id = $id_no[1];
  $time = date("h:i:s");
  $date =date('Y-m-d');
  $this::selectQuery('membership_rommie','Name',"where Reg_id ='$reg_id'");
  if($this::checkrow() == 1){
    $f =$this::fetchQuery();
    $name = $f[0]['Name'];
    $this::selectQuery('membership_rommie','Name',"where Reg_id = '$reciever'");
    if($this::checkrow() == 1){
      $R_fect = $this::fetchQuery();
      $R_name =$R_fect[0]['Name'];
     $this::selectQuery('roomie_msg',"chat_id","where reciever_id='$reg_id' and reg_id='$reciever'  or reg_id='$reg_id' and reciever_id='$reciever'");
      if($this::checkrow() > 0){
          $fect = $this::fetchQuery();
           $id = $fect[0]['chat_id'];
           $this::insertQuery('roomie_msg','reg_id,name,reciever_id,reciever_name,chat_id,pchat_id,message,time,date',"'$reg_id','$name','$reciever','$R_name','$id','$pchat','$message','$time','$date'");
           return array('code'=>1);
        }else{
       $chat_id = $this::random_alphanumeric(20);
       $this::insertQuery('roomie_msg','reg_id,name,reciever_id,reciever_name,chat_id,pchat_id,message,time,date',"'$reg_id','$name','$reciever','$R_name','$chat_id','$pchat','$message','$time','$date'");
       $this::insertQuery('roomie_msg_id','reg_id,reciever_id,chat_id',"'$reg_id','$reciever','$chat_id'");
       return array('code'=>2);
      }
    }else{
      return 'you hav eto chh';
    }

  }
}
public function getM($token){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $this::selectQuery('roomie_msg','chat_id',"where reciever_id='$reg_id' and seen ='0'");
  $f = $this::fetchQuery();
  $count = count($f);
 return array('code'=>1,'unread'=>$count);
}

public function getU($token){
  $m =[];
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $this::selectQuery('roomie_msg_id','chat_id',"where reg_id='$reg_id' or reciever_id ='$reg_id'");
  $f = $this::fetchQuery();
  foreach ( $f as $f ) {
    foreach ( $f as $key => $value ) {
      $chat_id = $f[$key];
      $this::selectQueryl('roomie_msg',"*","where chat_id='$chat_id'",'sn',1);
      $fect = $this::fetchQuery();
     foreach ($fect as $fect) {
      array_push($m,$fect);
     }
    }
  }
 return array('code'=>1,'unread'=>$m);
}
public function getIDmsg($token,$input){
  $neat =$this::neat($input);
  $chat_id = $neat['chat_id'];
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $this::selectQuery('roomie_msg','reg_id,name,reciever_id,reciever_name,message,time,date',"where chat_id = '$chat_id'");
  $f = $this::fetchQuery();
  $this::selectQueryl('roomie_msg_id',"reciever_id","where chat_id='$chat_id' and reg_id='$reg_id'",'sn',1);
  $fect = $this::fetchQuery();
  if($this::checkrow() == 0){
    $this::selectQueryl('roomie_msg_id',"reg_id","where chat_id='$chat_id' and reciever_id='$reg_id'",'sn',1);
    $fect = $this::fetchQuery();
    $reciever = $fect[0]['reg_id'];
  }else{
    $reciever = $fect[0]['reciever_id'];
  }
  $this::selectQuery('roomie_msg',"pchat_id","where reciever_id='$reg_id' and seen ='0'");
  if($this::checkrow()>0){
    $ft =$this::fetchQuery();
    foreach ( $ft as $ft ) {
      foreach ( $ft as $key => $value ) {
        $chat_id = $value;
        $this::update('roomie_msg',"seen = '1'","where pchat_id = '$value' and seen='0'");
      }
    }
  }
  return array('code'=>1,'msg'=>$f,'reciever'=>$reciever);
}

public function getState(){
  $this::selectQuery('states','state_id,name','');
  $f =$this::fetchQuery();
  return array('code'=>1,'states'=>$f);
}
public function getLocals($state){
  $this::selectQuery('locals','local_id,local_name',"where state_id='$state'");
  $f =$this::fetchQuery();
  return array('code'=>1,'locals'=>$f);
}
public function getPropT(){
  $this::selectQuery('property_type','type_id,type_name','');
  $f =$this::fetchQuery();
  return array('code'=>1,'property'=>$f);
}
public function getSubT($subtype){
  $this::selectQuery('sub_type','property_type,sub_name',"where property_type = '$subtype'");
  $f =$this::fetchQuery();
  return array('code'=>1,'subtype'=>$f);
}
public function getFavs($token){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $prop =[];
  $this::selectQuery('property_likes','property_id',"where reg_id ='$reg_id' and status = '1'");
  $f = $this::fetchQuery();
  foreach ( $f as $f ) {
    $property_id = $f['property_id'];
    $this::selectQuery('properties','*',"where propertyID='$property_id'");
    $ft = $this::fetchQuery();
    foreach ($ft as $key => $value) {
      $ft[$key]['cost'] = number_format( $ft[$key]['cost']);
    }
    foreach($ft as $ft){
      $propID = $ft['propertyID'];
        $this::selectQueryL('propertypix','pix,pix_id',"where propertyID='$propID'",'sn',1);
        $p =$this::fetchQuery();
       if($this::checkrow()  == 1){
         foreach($p as $p){
          $ft['pix'] = $p['pix'];
         }
       }else{
         $ft['pix'] = ''; 
       } 
    }
    array_push($prop,$ft); 
  }
  return array('code'=>1,'favorite'=>$prop);
}
public function Paid($token,$input){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $neat = $this::neat($input);
  $payment_id = $neat['payment_id'];
  $time  = time();
  $date = date('Y-m-d');
  $txref =$neat['trxref'];
  $this->selectQuery('subscription','max_listing,max_feature,max_boost,subscription_duration',"where payment_id = '$payment_id' and reg_id ='$reg_id'");
  if($this::checkrow()  == 1){
    $f = $this::fetchQuery();
    $list = $f[0]['max_listing'];
    $feature =$f[0]['max_feature'];
    $boosts = $f[0]['max_boost'];
    $final_time = 86400 *(30 * ($f[0]['subscription_duration']));
    $this::update('subscription',"payment_date='$date',payment_time='$time',final_time = '$final_time',status='1'","where payment_id = '$payment_id' and reg_id ='$reg_id'");
    $up = $this::update('membership_users',"listing='$list',feature='$feature',boosts='$boosts'","where reg_id = '$reg_id'");
   if($up){return array('code'=>'1');}
  }

}

public function paidBoost($token,$input){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $neat = $this::neat($input);
  $payment_id = $neat['payment_id'];
  $status =$neat['status'];
  if($status == 'success'){
    $this->selectQuery('membership_users','boosts'," where reg_id='$reg_id'");
  $f = $this::fetchQuery();
  $boosts = ($f[0]['boosts'] + 100);
  $up = $this::update('membership_users',"boosts='$boosts'","where reg_id='$reg_id'");
  if($up){return ['code'=>'1'];}
  }
 

}
public function Sub($token){
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $this::selectQuery('subscription',"*","where reg_id='$reg_id'");
  $f = $this::fetchQuery();
  foreach ($f as $key => $value) {
    $f[$key]['amount'] = number_format( $f[$key]['amount']);
  }
  return ['code'=>'1','Subs'=>$f];
}
public function Desk($input){
  $neat = $this::neat($input);
  $request_id = $neat['request_id'];
  $email = $neat['email'];
  $subject =$neat['subject'];
  $description =$neat['description'];
  $purpose =$neat['purpose'];
  $listing_address = $neat['listing_address'];
  $email_alt =$neat['email_alt'];
  $property_type =$neat['property_type'];
  $id_req =$this::random_alphanumeric(9);
  $date = date('Y-m-d');
  $time = date("h:i:s");
  $in = $this::insertQuery('official_desk','request_id,private_id,email,subject,description,purpose,listing_address,property_type,email_alt,date,time',"'$request_id','$id_req','$email','$subject','$description','$purpose','$listing_address','$property_type','$email_alt','$date','$time'");
   if($in){return array('code'=>'1');}
}

public function getUpro($token,$user){
  
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
 if($user == 1){
   $this::selectQuery('membership_users','reg_id,email,username,name,lastname,companyName,state,locality,rating,listing,feature,boosts',"where reg_id ='$reg_id'");
   $f =$this::fetchQuery();
   return array('code'=>'1','message'=>$f);
 }
 if($user == 2){
  $this::selectQuery('membership_rommie','*',"where Reg_id ='$reg_id'");
  $f =$this::fetchQuery();
  return array('code'=>'1','message'=>$f);
 }
 if($user == 3){
   $reg_id = $id_no[0];
  $this::selectQuery('admin_users','*',"where userName ='$reg_id'");
  $f =$this::fetchQuery();
  return array('code'=>'1','message'=>$f);
 }
}

public function getPix($propID){
  $this::selectQuery('propertypix','pix',"where propertyID = '$propID'");
  $f = $this::fetchQuery();
  return  array('code'=>1,'pix'=>$f);
}

public function Agentfind($input){
  $agent = [];
  $neat = $this::neat($input);
  $name =$neat['name'];
 
  $city =$neat['city'];
  if(empty($name)){
    $this::selectQuery('membership_users','reg_id,name,companyName,phonenumber,whatsapp,pix,rating',"where ((state) like '%{$city}') and usertype = '3'");
    $f = $this::fetchQuery();
  }else if(empty($city)){
    $this::selectQuery('membership_users','reg_id,name,companyName,phonenumber,whatsapp,pix,rating',"where ((name) like '%{$name}') and usertype ='3'");
    $f = $this::fetchQuery();
   }
 if(!empty($name) && !empty($city)){
    $this::selectQuery('membership_users','reg_id,name,companyName,phonenumber,whatsapp,pix,rating',"where ((name) like '%{$name}' && (state) like '%{$city}') and usertype ='3'");
    $f = $this::fetchQuery();
   }else if( empty($name) && empty($city)){
    return "nill";
  }
  foreach($f as $f){
      $reg_id = $f['reg_id'];
      $this::selectQueryL('agentreview','review_content,review_date',"where agent_id='$reg_id'",'sn',1);
      $rev = $this::fetchQuery();
      if($this::checkrow()>0){
      foreach($rev as $rev){
        $f += ['review_content' => $rev['review_content'] ?? '','review_date' => $rev['review_date'] ?? ''];
       }
      }else{
        $f +=  ['review_content' =>  '','review_date' =>''];
      }
      $this::selectQuery('agentreview','*',"where agent_id='$reg_id'");
      $rev_count = count($this::fetchQuery());
      $this::selectQuery('properties','*',"where reg_id='$reg_id'");
      $listing = count($this::fetchQuery());
       $f += ['review_count'=>$rev_count,'listing_count'=>$listing]; 
       array_push($agent,$f);
  }

  return array('code'=>'1','agent'=>$agent);
 
 }
 public function FrontSearch($input){
  $neat = $this::neat($input);
  $sale = $neat['sale'];
  $search =$neat['search'];
  $this::selectOrder('properties','*',"where ((address) like '%{$search}' || (state) like '%{$search}' || (locality) like '%{$search}') and category='$sale' and publish ='1'","boosted DESC,Featured DESC,sn DESC");
  $f = $this::fetchQuery(); 
  if($this::checkrow() > 0){
    return array('code'=>1,'message'=>$f);
  }else{
    return array('code'=>2);
  }
 }

 public function roomiePro($input,$token,$amenities){
  $neat = $this::neat($input);
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
  $rent = $neat['rent'];
  $description =$neat['description'];
  $furnished =$neat['furnished'];
  $lease =$neat['lease'];
  $location =$neat['location'];
  $movin =$neat['movin'];
  $pets= $neat['pets'];
  $apt_type =$neat['apt_type'];
  $bedroom =$neat['bedroom'];
  $bathroom =$neat['bathroom'];
  $amenities = implode(",",$amenities);
   $this::selectQuery('roomie_pro','*',"where reg_id = '$reg_id'");
  $f = $this::fetchQuery();
   if($this::checkrow() == 0){
  $insert =   $this::insertQuery('roomie_pro','reg_id,rent,apt_type,movin,lease_duration,bedroom,bathroom,pets,furnished,amenities,description',"'$reg_id','$rent','$apt_type','$movin','$lease','$bedroom','$bathroom','$pets','$furnished','$amenities','$description'");
      if($insert){
        $this::update("membership_rommie","Purpose='2'","where Reg_id='$reg_id'");
        return array('code'=>1);
      }
   }else{
    $rent = $rent ?? $f[0]['rent'];
    $description = $description ?? $f[0]['neat'];
    $furnished = $furnished ?? $f[0]['furnished'];
    $lease = $lease ?? $f[0]['lease_duration'];
    $location = $location ?? $f[0]['location'];
    $movin =$movin ?? $f[0]['movin'];
    $pets = $pets ?? $f[0]['pets'];
    $bedroom = $bedroom ?? $f[0]['bedroom'];
    $bathroom =$bathroom ?? $f[0]['bathroom'];
    $apt_type =$apt_type ?? $f[0]['apt_type'];
    $amenities = $amenities ?? $f[0]['amenities'];
    $description =$description ?? $f[0]['description'];
     $update =   $this::update("roomie_pro","rent = '$rent', apt_type = '$apt_type',movin='$movin',lease_duration='$lease',bedroom='$bedroom',bathroom ='$bathroom',pets ='$pets',furnished='$furnished',amenities='$amenities',description='$description'","where reg_id = '$reg_id'");
   if($update){
      return array('code'=>2);
   }
   }
 }


 
public function checkUserDeposit($token)
{
  $id_no = $this::getid($token);
  $reg_id = $id_no[1];
    $this::selectQuery('deposits','stat',"where reg_id = '$reg_id' and stat='1'");
    if($this::checkrow()  < 1)
    {
     return true;
    }
    else
    {
     return false;
    }
}

public function verifyEmail($input){
 $neat =  $this::neat($input);
  $email = $neat['email'];
  $hash = $neat['hash'];
  $this::selectQuery('membership_users',"email,hash,status","where email='$email' and hash='$hash' and status='0'");
  if($this::checkrow() == 1){
    $this::update("membership_users","status='1'","where email='$email' and hash='$hash'");

    return array('code'=>1);
  }else{
    return array('code'=>2);
  }
}

public function forgotPass($email){
  if($this::ValidateEmail($email)){
    $this::selectQuery('membership_users',"reg_id","where email = '$email'");
    if($this::checkrow() == 1){
      $f = $this::fetchQuery();
      $reg_id = $f[0]['reg_id'];
    
      $token = openssl_random_pseudo_bytes(32);
      $token = bin2hex($token);

      $endTime = strtotime("+15 minutes");
    $insert = $this::replace("access_tokens","user_id, token, date_expires","'$reg_id','$token','$endTime'");
  if($insert){
    $link ="http://localhost:4200/#/reset_pass/{$token}";
    $message="This email is in response to a forgotten password reset request at SmartVille. If you did make this request,click the  link below to be able to access your account 
     $link 
    For security purposes, you have 15 minutes to do this. If you do not click this link within 15 minutes, you’ll need to request a password reset again.
    If you have not forgotten your password, you can safely ignore this message and you will still be able to login with your existing password.
    ";
  
    $me = mail::passmail('noreply@smartvil.com','mezj972@gmail.com','Signup | Verification',$message,$link);

    if($me = true){return array('code'=>1);}
  }
 }else{
      return 'error';
    }
  }
}
public function tokenVerify($token){
  $time = time();
  $this::selectQuery('access_tokens',"user_id","where token = '$token' and date_expires > '$time'");
  if($this::checkrow() === 1){
    $f = $this::fetchQuery();
    $reg_id = $f[0]['user_id'];

    $issuer="http://localhost:4200";
    $audience= "http://localhost:/dashboard";
    $user_id = [$f[0]['user_id']];

    $tok=$this->enc($issuer,$audience,$user_id);
   
 $this::delete("access_tokens","where token = '$token'");
  return array('code'=>1,'message'=>$tok);
  }else{
    return array('code'=>2);
  }
}

  public function changePass($password,$token){
    $neat =  $this::neat($password);
    $id_no = $this::getid($token);
  
    $reg_id = $id_no[0];
    $pass = $neat['password'];
   $update =  $this::update("membership_users","pass='$pass'","where reg_id = '$reg_id'");
   if($update){
     return array('code'=>1);
   }
  }

  public function sellOffers($inputs){
    $neat = $this::neat($inputs);
    $owner =$neat['owner'];
    $bedroom = $neat['bedroom'];
    $full_bath  = $neat['full_bath'];
    $thre_qurtz = $neat['thre_qurtz'];
    $qurtz = $neat['qurtz_bath'];
    $half_bath = $neat['half_bath'];
    $sqft = $neat['total_sq'];
    $yearBuilt = $neat['yearbuilt'];
    $yearBougth = $neat['yearbought'];
    $applieshome = $neat['applieshome'];
    $appliesyou =$neat['appliesyou'];
    $renovations = $neat['renovations'];
    $floring = $neat['floortype'];
    $special_feature = $neat['specialfeature'];
    $documents = $neat['documents'];
    $when_to_sel = $neat['sell'];
    $wish_to_buy =$neat['buyhome'];
    
   $id = $this::random_alphanumeric(20);
   $this::selectQuery('sell_offers','offer_id',"where offer_id = '$id'");
   if($this::checkrow() == 0){
   $insert =  $this::insertQuery('sell_offers',"offer_id,owner,bedroom,full_bath,threequrtz_bath,half_bath,qurtz_bath,total_sqft,year_built,year_bought,applies_home,renovation,floor_type,special_feature,applies_you,documents,when_sell,wish_to_buy","'$id','$owner','$bedroom','$full_bath','$thre_qurtz','$half_bath','$qurtz','$sqft','$yearBuilt','$yearBougth','$applieshome','$renovations','$floring','$special_feature','$appliesyou','$documents','$when_to_sel','$wish_to_buy'");
  if($insert){
    return array('code'=>1);
  }   

   }
  }

  public function getblog($blog_id){
    $neat = $this::neat($blog_id);
    $blog_id = $neat['blog'];
    $this::selectQuery('blogs',"*","where blog_id ='$blog_id' and posted = 'publish' and category = '2'");
    $f = $this::fetchQuery();
    return array('code'=>1, 'message'=>$f);
  }

  public function featureProp($propid,$token){
    $id_no = $this::getid($token);
    $reg_id = $id_no[1];
  $this::selectQuery("membership_users",'feature',"where reg_id='$reg_id'");
  $f =  $this::fetchQuery();
   $feature = $f[0]['feature'];
   if($feature > 0 ){
    $this::selectQuery("properties",'Featured',"where propertyID = '$propid' and reg_id = '$reg_id' and publish='1'");
    if($this::checkrow() == 1){
      $fetch = $this::fetchQuery();
      $feat = $fetch[0]['Featured'];
      if($feat == 0){
      $update =   $this::update('properties',"Featured='1'","where propertyID = '$propid'");
         if($update){
            if($feature >= 1){$featFinal = $feature - 1;}
            if($featFinal < 0){$featFinal = 0;}
            $this::update('membership_users',"feature = '$featFinal'","where reg_id = '$reg_id'");
           return array('code'=>1,'message'=>'Featured');
         }
         
      }else{
        return array('code'=>2);
      }
    
    }
   }else{
     return array('code'=>3);
   }
  }

  public function boostProp($propid,$token){
    $id_no = $this::getid($token);
    $reg_id = $id_no[1];
  $this::selectQuery("membership_users",'boosts',"where reg_id='$reg_id'");
  $f =  $this::fetchQuery();
   $AvailBoost = $f[0]['boosts'];
   if($AvailBoost > 0){
      $AvailBoost = $AvailBoost - 1;
    $this::selectQuery("properties",'boosted',"where propertyID = '$propid' and reg_id = '$reg_id' and publish='1'");
    if($this::checkrow() == 1){
      $fetch = $this::fetchQuery();
      $boost = $fetch[0]['boosted'] + 1;
      $boostProp = $this::update("properties","boosted='$boost'","where propertyID='$propid' and reg_id='$reg_id'");
      if($boostProp){
        $update = $this::update("membership_users","boosts='$AvailBoost'","where reg_id='$reg_id'");
        if($update){
          return array('code'=>1);
        }
      }
    }
   }else{
    return array('code'=>2);
   }
  }

public function allBlog($token){
  $id =   $this::getid($token);
   $id = $id[0];
   self::selectQuery("blogs",'*',"where author = '$id'");
   if($this::checkrow() > 0){
    $f = self::fetchQuery();
    return ['code'=>1,'message'=>$f];
   }else{
    return ['code'=>2];
   }
  
  }

  public function updateAdmin($token,$input){
    $id =   $this::getid($token);
    $neat = $this::neat($input);
    $pass = $neat['password'];
    $this::selectQuery("admin_users",'passMD5',"where userName = '$id[0]'");
    $f  = $this::fetchQuery();
    if(password_verify($neat['cpass'],$f[0]['passMD5'])){
      if($this::update("admin_users","passMD5='$pass'","where userName = '$id[0]'")){
        return ['code'=>1];
      }
    }else{
      return ['code'=>2];
    }
  }

  public function propAccess($input){
    $neat = $this::neat($input);
    $prop = $neat['prop'];
    if($neat['permit'] == 1){
      if($this::update("properties","isApproved='1'","where propertyID = '$prop'")){
        return ['code'=>1];
      }
    }

    if($neat['permit'] == 2){
      if($this::update("properties","isApproved='0'","where propertyID = '$prop'")){
        return ['code'=>2];
      }
    }
  }
}




    ?>