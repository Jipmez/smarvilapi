<?php
require('mbb.php');
header('Content-type:application/json;charset=utf-8');
$request=json_decode(file_get_contents('php://input'),true);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type,access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
$clas = new Reg('localhost','root','','smrvil');



     
   if($_POST){
    if($_POST['key']=='200'){
      
       $gb = $_FILES;
      
    
     $propID = $_POST['propID'];
     
     
     $token = $_POST['id'];

      $jay = $clas->PixUp($gb,$token,$propID);
    
      echo json_encode($jay);
    } 
   }

   if($_POST){
     if($_POST['key'] == 'pImage'){
       $token = $_POST['token'];
       $files = $_FILES;
      
       $jay = $clas->rommieImg($files,$token);
       
       echo json_encode($jay);

     }
   }
   if($_POST){
    if($_POST['key'] == 'proImage'){
      $token = $_POST['token'];
      $files = $_FILES;
     
      $jay = $clas->rommieProImg($files,$token);
      echo json_encode($jay);

    }
  }

   if($_POST){
    if($_POST['key'] == 'uPix'){
      $token = $_POST['token'];
      $files = $_FILES;
     
      $jay = $clas->uImg($files,$token);
      echo json_encode($jay);

    }
  }
  if($_POST){
    if($_POST['key']=='blogpix'){
   
  $file = $_FILES['image'];
  
   $blog_id = $_POST['blogid'];

  
  $jay = $clas->blogImg($file,$blog_id);

  echo json_encode($jay);
}
}

  if($request['key'] == '600'){
   
    $token = $request['id'];
  
    $jay = $clas->romateP($token);
    echo json_encode($jay);
  }

   if($request['key'] == 'pay'){
     $input = array('Address'=> $request['Address'],'category'=>$request['category'],'type'=>$request['type'],'subtype'=>$request['sub_type']);
     $token = $request['id'];
     
     $jay = $clas->Pupload($input,$token);

     echo json_encode($jay);
   }

   if($request['key'] == 'kent'){
     $token = $request['token'];
     
     $jay = $clas->MyProp($token);

     echo json_encode($jay);
   }
   if($request['key'] == 'getallprop'){
    $jay = $clas->getallProp();

    echo json_encode($jay);
   }

    if($request['key'] == 'me'){
      

      $jay = $clas->getProp();

      echo json_encode($jay);
    }

    if($request['key'] == 'logdes'){
      
      $jay = $clas->getlogdes();

      echo json_encode($jay);
    }

    if($request['key'] == 'students'){
      $jay = $clas->getStudentlogdes();

      echo json_encode($jay);
    }

    if($request['key'] == '0o7'){
     
     $property = $request['prop'];
     
      $jay = $clas->singleProp($property);

      echo json_encode($jay);
    }
    
    if($request['key']=='register'){

      $input = array('fullname' => $request['name'],'lastname'=>$request['lastname'] ,'username'=>$request['username'],'email'=>$request['email'], 'usertype'=>$request['usertype'],'password'=>$request['password']);

      
     
      $jay = $clas->RegisterUser($input);

      echo json_encode($jay);
    }
    if($request['key']=='ad_register'){
      
      $input = array('username'=>$request['username'],'email'=>$request['email'], 'group'=>$request['usertype'],'password'=>$request['password']);

      $jay =$clas->adsign($input);

      echo json_encode($jay);
    }

    if($request['key'] == 'login')
    {
       $input= array('username' => $request['username'],'password'=>$request['password']);
      
       $jay = $clas->LoginUser($input);
       echo json_encode($jay);
    }

    if($request['key'] == 'user')
    {
      $input = array('publish' => $request['publish'] ?? '','title'=>$request['title'] ?? '','status'=>$request['status']?? '','category'=>$request['category'] ?? '','subtype'=>$request['subtype'] ?? '','state'=>$request['state'] ?? '','locality'=>$request['locality'] ?? '','street'=>$request['street'] ?? '','price'=>$request['price'] ?? '','bedroom'=>$request['bedroom'] ?? '','toilets'=>$request['toilets'] ?? '','bathrooms'=>$request['bathrooms'] ?? '','parking'=>$request['parking'] ?? '','totalarea'=>$request['totalArea'] ?? '','roomsize'=>$request['roomsize'] ?? '','video'=>$request['video'] ?? '','discription'=>$request['discription'] ?? '','propid'=>$request['propid'] ?? '' );
      $amenities = $request['amenities'];
      $token = $request['id'];
      $jay = $clas->propUp($input,$token,$amenities);
      echo json_encode($jay);
    }

    if($request['key'] == 'med')
    {
      $token = $request['id'];
      $propID = $request['propId'];
      
      $jay = $clas->getDetail($propID,$token);
      echo json_encode($jay);

    }
   
    if($request['key'] == 'rev')
    {
      $token = $request['id'];
      $propID = $request['propID'];
      $input = array('review' =>$request['review'], 'name'=>$request['name'], 'reasons'=>$request['reasons']);
     
      $jay = $clas->adReview($propID,$token,$input);
      echo json_encode($jay);

    }

    if($request['key'] == 'getRev')
    {
    
      $propID = $request['propID'];
      $jay = $clas->gtReview($propID);
      echo json_encode($jay);
    }

    if($request['key'] == 'rommie')
    {
      $token =$request['id'];
      $input  = array('purpose'=>$request['purpose'], 'age'=>$request['age'], 'gender'=>$request['gender'], 'education'=>$request['education'], 'career'=>$request['career'],'hometown'=>$request['hometown'],'pets'=>$request['pets'],'budget'=>$request['budget'],'relation'=>$request['relation'],'about'=>$request['about'],'pstate'=>$request['pstate'],'plocal'=>$request['plocal'],'ptown'=>$request['ptown'],'lease'=>$request['lease'],'movein'=>$request['movein']);
       
      $jay = $clas->adRommie($token,$input);
      echo json_encode($jay);
    }
    
    if($request['key'] == 'Editrommie')
    {
      $token =$request['id'];
      $input  = array('pstate'=>$request['pstate'],'plocal'=>$request['plocal'],'ptown'=>$request['ptown'], 'age'=>$request['age'], 'gender'=>$request['gender'], 'education'=>$request['education'], 'career'=>$request['career'],'hometown'=>$request['hometown'],'pets'=>$request['pets'],'budget'=>$request['budget'],'relation'=>$request['relation'],'about'=>$request['about'],'lease'=>$request['lease'],'movin'=>$request['movein']);
     
      $jay = $clas->EditRommie($token,$input);
      echo json_encode($jay);
    }
    

    if($request['key'] == 'pInfo')
    {
      $token =$request['id'];
      $input = array('bed_time'=>$request['bed_time'],'cleanliness'=>$request['cleanliness'], 'cooking'=>$request['cooking'],'drinks'=>$request['drinks'], 'drugs'=>$request['drugs'],'privacy'=>$request['privacy'],'smokes'=>$request['smokes'], 'social'=>$request['social'], 'working_hours'=>$request['working_hours']);
     
      $jay = $clas->rommieInfo($token,$input);
      echo json_encode($jay);

    }

    if($request['key'] == 'filter')
    {
      $token =$request['id'];
      $input = array('cats' =>$request['cat'] ,'dogs' =>$request['dogs'],'max_budget' =>$request['Max_budget'],'min_budget' =>$request['Min_budget'], 'age_max' =>$request['age_max'],'age_min' =>$request['age_min'],'gender' =>$request['gender'],'no_pets'=>$request['no_pet'],'mutual_connections' =>$request['mutual_connections'],'pet_friendly' =>$request['pet_friendly'],'purpose' =>$request['purpose']);
     
      $jay = $clas->rommieFilter($token,$input);
      echo json_encode($jay);
    }

    if($request['key'] == 'gtR')
    {
      $token = $request['val'];
     
      $jay = $clas->rommieGet($token);
      echo json_encode($jay);
    }
   
    if($request['key'] == 'grtrP')
    {
      $token = $request['id'];
     
      $jay = $clas->rommieProfile($token);
      echo json_encode($jay);
    }
    if($request['key'] == 'geP')
    {
      $id = $request['id'];
      $clas = new Reg('localhost','root','','smrvil');
      $jay = $clas->getrProfile($id);
      echo json_encode($jay);
    }

    if($request['key'] == 'proF'){
      $input = array('Keyword'=>$request['keyword'],'Market'=>$request['Market'], 'Max'=>$request['Max'], 'Min'=>$request['Min'], 'Ref'=>$request['Ref'], 'State'=>$request['state'], 'category'=>$request['category'], 'status'=>$request['status'], 'time'=>$request['time'], 'locality'=>$request['locality'], 'bedroom'=>$request['bedroom']);
     
      $jay = $clas->filterProp($input);
      echo json_encode($jay);
    }
    if($request['key'] == 'filterUni'){
      $university = $request['uni'];
      $jay = $clas->filterUni($university);
      echo json_encode($jay);
    }

  if($request['key'] == 'proV'){
      $input = array('value'=>$request['value']);
     
      $jay = $clas->Vprop($input);
      echo json_encode($jay);
  }

  if($request['key'] == 'userP'){
    $input =array('Cname'=>$request['Cname'],'Street'=>$request['Street'],'Locality'=>$request['Locality'],'name'=>$request['Name'],'phone'=>$request['Phone'],'state'=>$request['State'],'website'=>$request['Website'],'whatsapp'=>$request['Whatsapp']);
    $token =$request['Id'];
   
    $jay = $clas->userP($input,$token);
     echo json_encode($jay);
  }
  if($request['key'] == 'getUp'){
    $propID = $request['propID'];
   
    $jay = $clas->getUp($propID);
     echo json_encode($jay);
  }
  if($request['key'] == 'payUp'){
    $token = $request['id'];
    $input = array('amount'=>$request['amount'],'listing'=>$request['listing'],'featured'=>$request['featured'],'boosts'=>$request['boosts'],'duration'=>$request['duration'],'months'=>$request['months'],'method'=>$request['method'],'plan'=>$request['plan'],'ref'=>$request['ref']);
    $jay = $clas->payUp($token,$input);
    echo json_encode($jay);
  }

  if($request['key']== 'ref1'){
    $input  = array('ref'=>$request['ref']);
    $token = $request['id'];
   
    $jay = $clas->getPay($token,$input);
    echo json_encode($jay);
   
  }
if($request['key'] =='agent'){
    $agent = $request['agent'];
   
    $jay = $clas->agentP($agent);
    echo json_encode($jay);
}
if($request['key'] == 'agentRev'){
  $input = array('name'=>$request['name'],'agent_id'=>$request['agent_id'],'address'=>$request['address'],'relationship'=>$request['relationship'],'content'=>$request['content'],'response'=>$request['response'],'knowledge'=>$request['knowledge'],'negotiate'=>$request['negotiate'],'expertise'=>$request['expertise']);
  $agent_id = $request['agent_id'];
 
  $jay =$clas->agentRev($input,$agent_id);
  echo json_encode($jay);
}
if($request['key'] == 'reviewCalc'){
  $id =$request['agent'];
 
  $jay =$clas->RevCalc($id);
  echo json_encode($jay);
}
if($request['key']=="adSign"){
 
  $input = array('username'=>$request['name'],'password'=>$request['pass']);
 
  $jay =$clas->adLog($input);
  echo json_encode($jay);
}
if($request['key'] == 'roomblog'){
 
  $jay =$clas->gtRblog();
  echo json_encode($jay);
}
if($request['key'] == 'propblog'){
 
  $jay =$clas->gtPblog();
  echo json_encode($jay);
}
if($request['key'] == 'postblog'){
  $input = array('title'=>$request['title'],'category'=>$request['category'],'tags'=>$request['tags'],'content'=>$request['content'],'posted'=>$request['posted'],'blogid'=>$request['blogid']);
  $token= $request['id'];
 
  $jay =$clas->Postblog($input,$token);
  echo json_encode($jay);
}  

if($request['key'] == 'blogadd'){
  $input = array('title'=>$request['title'],'category'=>$request['category']);
  $token = $request['id'];
 
  $jay =$clas->Addblog($input,$token);
  echo json_encode($jay);
}
if($request['key'] == 'author'){
  $token = $request['id'];
  $blog_id =$request['blogid'];
 
  $jay =$clas->getAuthor($blog_id,$token);
  echo json_encode($jay);
}
if($request['key'] == 'getpermit'){
  $token = $request['permit'];
 
  $jay =$clas->getPermitU($token);
  echo json_encode($jay);
}
if($request['key'] == 'getpermitA'){
  $token = $request['permit'];
 
  $jay =$clas->getPermitA($token);
  echo json_encode($jay);
}
if($request['key'] == 'getID'){
  $token = $request['permit'];
  
  $jay =$clas->getUID($token);
  echo json_encode($jay);
}
if($request['key'] == 'like'){
  $token = $request['id'];
  $prop = $request['prop'];
 
  $jay =$clas->likeP($token,$prop);
  echo json_encode($jay);
}
if($request['key'] == 'intMsg'){
  $input =array('message'=>$request['message'],'reciever'=>$request['send_to']);
  $token = $request['id'];
  
  $jay =$clas->SendM($token,$input);
  echo json_encode($jay);
}
if($request['key'] == 'getM'){
  $token = $request['id'];
 
  $jay =$clas->getM($token);
  echo json_encode($jay);
}
if($request['key'] == 'unread'){
  $token = $request['id'];
  $jay =$clas->getU($token);
  echo json_encode($jay);
}
if($request['key'] == 'gtIDmsg'){
  $token = $request['id'];
  $input = array('chat_id'=>$request['chat_id']);
  $jay =$clas->getIDmsg($token,$input);
  echo json_encode($jay);
}
if($request['key'] == 'getState'){
  $jay =$clas->getState();
  echo json_encode($jay);
}
if($request['key'] == 'getLocal'){
  $state = $request['state_id'];
  $jay =$clas->getLocals($state);
  echo json_encode($jay);
}
if($request['key'] == 'getProtype'){
  $jay =$clas->getPropT();
  echo json_encode($jay);
}
if($request['key'] == 'getSubtype'){
  $subtype = $request['sub_id'];
  $jay =$clas->getSubT($subtype);
  echo json_encode($jay);
}
if($request['key'] == 'getFav'){
  $token = $request['id'];
  $jay =$clas->getFavs($token);
  echo json_encode($jay);
}
if($request['key'] == 'payed'){
  $input = array('trxref'=>$request['txref'],'payment_id'=>$request['payment_id']);
  $token = $request['id'];
  $jay =$clas->Paid($token,$input);
  echo json_encode($jay);
}

if($request['key'] == 'payboosts'){
  $input = array('status'=>$request['status'],'payment_id'=>$request['payment_id']);
  $token = $request['id'];
  $jay =$clas->paidBoost($token,$input);
  echo json_encode($jay);
}
if($request['key'] == 'getsub'){
  $token =$request['id'];
  $jay = $clas->Sub($token);
  echo json_encode($jay);
}
if($request['key']=='desk'){
  $input = array('request_id'=>$request['request_id'],'email'=>$request['email'],'subject'=>$request['subject'],'description'=>$request['description'],'purpose'=>$request['purpose'],'listing_address'=>$request['listing_address'] ?? '','email_alt'=>$request['email_alt'] ?? '','property_type'=>$request['property_type'] ?? '');
  $jay =$clas->Desk($input);
  echo json_encode($jay);
}
if($request['key'] == 'getUpro'){
  $token = $request['id'];
  $user =$request['user'];
 
  $jay = $clas->getUpro($token,$user);
  echo json_encode($jay);
}
if($request['key'] == 'getProP'){
  $propID = $request['propID'];
  $jay =$clas->getPix($propID);
  echo json_encode($jay);
}
if($request['key'] == 'agentfind'){
  $input = array('name'=>$request['name'],'city'=>$request['city']);
  $jay =$clas->Agentfind($input);
  echo json_encode($jay);
}
if($request['key'] == 'frontsearch'){
  $input = array('sale'=>$request['sale'],'search'=>$request['search']);
  $jay =$clas->FrontSearch($input);
  echo json_encode($jay);
}
if($request['key'] == 'moveTodash'){
  $token =$request['id'];
  $jay =$clas->moveTodash($token);
  echo json_encode($jay);
}
if($request['key'] == 'moveTodash'){
  $token =$request['id'];
  $jay =$clas->moveTodash($token);
  echo json_encode($jay);
}
if($request['key'] == 'roomie_pro'){
  
  $token =$request['id'];
  $amenities = $request['amenities'];
  $input = array('rent'=>$request['rent'],'description'=>$request['description'],'furnished'=>$request['furnished'],'lease'=>$request['lease_duration'],'location'=>$request['location'],'movin'=>$request['movin'],'pets'=>$request['pets'],'apt_type'=>$request['apt_type'],'bedroom'=>$request['bedroom'],'bathroom'=>$request['bathroom']);
  $jay =$clas->roomiePro($input,$token,$amenities);
  echo json_encode($jay);
}
if($request['key'] == 'verify'){
  $input = array('email' =>$request['email'], 'hash'=>  $request['hash']);
   $jay = $clas->verifyEmail($input);
   echo json_encode($jay);
}
if($request['key'] == 'forgot'){
  $email = $request['email'];
  $jay = $clas->forgotPass($email);
  echo json_encode($jay);
}
if($request['key'] == 'tokenVerify'){
  $token = $request['token'];
  $jay = $clas->tokenVerify($token);
  echo json_encode($jay);
}
if($request['key'] == 'changePass'){
  $password =array('password'=>$request['pass']);
  $token = $request['token'];

  $jay = $clas->changePass($password,$token);
  echo json_encode($jay);
}
if($request['key'] == 'offers'){
  $applies_home = implode(",", $request['Applies']);
  $applies_you = implode(",",$request['AppliesToYou']);
  $floor_type = implode(",",$request['Flooring']);
  $documents = implode(",",$request['documents']);
  $renovations = implode(",",$request['renovate']);
  $special_feature = implode(",",$request['special']);
  $input =   array('owner'=>$request['owner'],'bedroom'=>$request['bedroom'],'full_bath'=>$request['b_full'],'half_bath'=>$request['b_half'],'thre_qurtz'=>$request['b_threqurtz'],'qurtz_bath'=>$request['b_qurtz'],'sell'=>$request['sell'],'total_sq'=>$request['sqft'],'yearbuilt'=>$request['yearbuilt'],'yearbought'=>$request['yearpurch'],'documents'=>$documents,'applieshome'=>$applies_home,'appliesyou'=>$applies_you,'renovations'=>$renovations,'floortype'=>$floor_type,'specialfeature'=>$special_feature,'buyhome'=>$request['buyhome']);
  $jay = $clas->sellOffers($input);
  echo json_encode($jay);

}

if($request['key'] == 'getblog'){ 
  $blog_id =  array('blog'=>$request['id']);
  $jay = $clas->getblog($blog_id);
  echo json_encode($jay);
}
if($request['key'] == 'feature'){
  $propid = $request['propid'];
  $token = $request['id'];
  $jay = $clas->featureProp($propid,$token);
  echo json_encode($jay);
}
if($request['key'] == 'boost'){
  $propid = $request['propid'];
  $token = $request['id'];
  $jay = $clas->boostProp($propid,$token);
  echo json_encode($jay);
}
if($request['key'] == 'allblog'){
  $token = $request['id'];
  $jay = $clas->allBlog($token);
  echo json_encode($jay);
}
if($request['key'] == 'updateadmin'){
  $token = $request['id'];
  $input = ['cpass'=>$request['cpass'],'password'=>$request['newpass']];
  $jay = $clas->updateAdmin($token,$input);
  echo json_encode($jay);
}

if($request['key'] == 'propaccess'){
  $input  = ['permit'=>$request['perm'],'prop'=>$request['prop']];
  $jay = $clas->propAccess($input);
  echo json_encode($jay);
}
