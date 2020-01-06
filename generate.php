<?php
require('mbb.php');
header('Content-type:application/json;charset=utf-8');
$request=json_decode(file_get_contents('php://input'),true);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type,access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
    require 'vendor/autoload.php';

    use Coinbase\Wallet\Client;
    use Coinbase\Wallet\Configuration;
    use Coinbase\Wallet\Resource\Address;
    $clas= new Reg('localhost','root','','smrvil');
    $mysqli = new mysqli("localhost", "root", "", "smrvil");
    //Post Data
    if($_POST){
        if($_POST['key'] == 'Sub'){
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 2717171771;
            $amount = isset($_POST['amount']) ? $_POST['amount'] : 1000;
            $txref = $_POST['txref'];
            $duration = $_POST['duration'];
            $plan = $_POST['plan'];
    
   

        //API Configuration
        /* $api_key = "JNdbPPuFsSWmkkwQ";
        $api_secret = "pmQtWQoQibIbYfCpKTT4rLq2vMdbGmoF"; */
        $api_key = "ZDR09y0uGs8Zx4RA";
        $api_secret = "YpY9X2kIDE490ssEK8dxsCSrnN0pF709";
        $configuration = Configuration::apiKey($api_key, $api_secret);
        $client = Client::create($configuration);

        //Set The Bitcoin Price To The Minimum
        $buy_price = $client->getBuyPrice('BTC-USD')->getAmount();
        $sell_price = $client->getSellPrice('BTC-USD')->getAmount();
        $price = ( $buy_price <= $sell_price ) ? $buy_price : $sell_price;
        $account = $client->getAccount("447ef7e7-ab8b-5edf-83a0-ce3551e5febc");
        /*  $account = $client->getAccount("ffa13fa5-a47a-5ca3-9704-027a00644500"); */

        //Errors

        //Create Bitcoin Address
        $address = new Address([
            'name' =>$user_id
        ]);
        $new_address = $client->createAccountAddress($account, $address);
        $btc_address = $new_address->getAddress();
        if ($mysqli->connect_errno) {
            $error_array = array('error' => 'Connection Issue');
            $error = json_encode($error_array);
            echo $error;
            exit();
        }
        if (!($stmt = $mysqli->prepare("UPDATE subscription SET amount_btc=?, address_btc=? WHERE payment_id=?"))) {
            $error_array = array('error' => $stmt->error);
            $error = json_encode($error_array);
            echo $error;
            exit();
        }

        $amount_btc = $amount/$price;
        if (!$stmt->bind_param("dsd",$amount_btc, $btc_address,$txref)) {
            $error_array = array('error' => $stmt->error);
            $error = json_encode($error_array);
            echo $error;
            exit();
        }

        if (!$stmt->execute()) {
            $error_array = array('error' => $stmt->error);
            $error = json_encode($error_array);
            echo $error;
            exit();
        }

        $stmt->close();

        $result = array(
            'code'=> 1,
            'amount_btc' => $amount_btc,
            'address' => $btc_address,
            'transaction_id' => $mysqli->insert_id
        );
        echo json_encode($result);
  }
}



if($_POST){
    if($_POST['key'] == 'coin'){
        $user_id = isset($_POST['rommie_id']) ? $_POST['rommie_id'] : 2717171771;
        $amount = isset($_POST['price']) ? $_POST['price'] : 1000;
        $token = isset($_POST['token']);
        $txref = $_POST['refrence'];
        $duration = $_POST['duration'];
       


    //API Configuration
    /* $api_key = "JNdbPPuFsSWmkkwQ";
    $api_secret = "pmQtWQoQibIbYfCpKTT4rLq2vMdbGmoF"; */
    $api_key = "ZDR09y0uGs8Zx4RA";
    $api_secret = "YpY9X2kIDE490ssEK8dxsCSrnN0pF709";
    $configuration = Configuration::apiKey($api_key, $api_secret);
    $client = Client::create($configuration);

    //Set The Bitcoin Price To The Minimum
    $buy_price = $client->getBuyPrice('BTC-USD')->getAmount();
    $sell_price = $client->getSellPrice('BTC-USD')->getAmount();
    $price = ( $buy_price <= $sell_price ) ? $buy_price : $sell_price;
   FIXME:   $account = $client->getAccount("447ef7e7-ab8b-5edf-83a0-ce3551e5febc");// change the get account on coinbase  with the api key 
    /*  $account = $client->getAccount("ffa13fa5-a47a-5ca3-9704-027a00644500"); */

    //Errors

    //Create Bitcoin Address
    $address = new Address([
        'name' =>$user_id
    ]);
    $new_address = $client->createAccountAddress($account, $address);
    $btc_address = $new_address->getAddress();
    if ($mysqli->connect_errno) {
        $error_array = array('error' => 'Connection Issue');
        $error = json_encode($error_array);
        echo $error;
        exit();
    }

    $id = $clas->getid($token);
    $reg_id = $id[1];
   $clas->selectQuery('roomie_sub','*',"where reg_id ='$reg_id' and status ='0'");
   if($clas->checkrow() == 1){
    $time  = time();
    $date = date('Y-m-d');
    $final_time = 86400 *($duration);
    if (!($stmt = $mysqli->prepare("UPDATE roomie_sub SET reg_id=?, payment_id=?,plan=?,payment_date=?, payment_time=?,final_time=? ,amount=?,amount_btc=?, address_btc=?,stat='0', WHERE reg_id=?"))) {
        $error_array = array('error' => $stmt->error);
        $error = json_encode($error_array);
        echo $error;
        exit();
    }

    $amount_btc = $amount/$price;
    if (!$stmt->bind_param("sssbiiidss",$reg_id, $txref,$plan,$date,$time,$final_time,$amount,$amount_btc,$btc_address,$reg_id)) {
        $error_array = array('error' => $stmt->error);
        $error = json_encode($error_array);
        echo $error;
        exit();
    }

    if (!$stmt->execute()) {
        $error_array = array('error' => $stmt->error);
        $error = json_encode($error_array);
        echo $error;
        exit();
    }

    $stmt->close();

    $result = array(
        'code'=> 1,
        'amount_btc' => $amount_btc,
        'address' => $btc_address,
        'transaction_id' => $mysqli->insert_id
    );
    echo json_encode($result);

    }else if($clas->checkrow() < 1 || $clas->checkUserDeposit($token)){

        $time  = time();
        $date = date('Y-m-d');
        $final_time = 86400 *($duration);
        if (!($stmt = $mysqli->prepare("INSERT INTO roomie_sub(reg_id,payment_id,subscription_duration,payment_date,plan,amount,amount_btc,address_btc,payment_time,final_time,stat) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, 0)"))) {
            $error_array = array('error' => $stmt->error);
            $error = json_encode($error_array);
            echo $error;
            exit();
        }
        
        $amount_btc = $amount/$price;
        if (!$stmt->bind_param("sssbiiidss",$reg_id, $txref,$plan,$date,$plan,$amount,$amount_btc,$btc_address,$time,$final_time)) {
            $error_array = array('error' => $stmt->error);
            $error = json_encode($error_array);
            echo $error;
            exit();
        }
    
        if (!$stmt->execute()) {
            $error_array = array('error' => $stmt->error);
            $error = json_encode($error_array);
            echo $error;
            exit();
        }
    
        $stmt->close();
    
        $result = array(
            'code'=> 1,
            'amount_btc' => $amount_btc,
            'address' => $btc_address,
            'transaction_id' => $mysqli->insert_id
        );
        echo json_encode($result);

    }else{
        return array('code'=>2);
    }


  }
}