<?php
require('mail.php');
require('mbb.php');
$clas= new coin('localhost','root','','tcb');
$mg = Mailgun::create(MAILGUN_KEY, 'https://api.mailgun.net');

function regmail(){
    $me  =  $mg->messages()->send(MAILGUN_DOMAIN,[]);
}

  
?>