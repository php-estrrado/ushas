<?php
   
        $message        =   "";
     //  	$message        =   getMessage('Merlin','merlinsundarsingh','123456',1);
	$message         =   'Test mail';
        $to_email       =   'merlinsundarsingh.s@gmail.com';
        $subject        =   " Test Mail Estrrado";
        $headers        =   'MIME-Version: 1.0' . "\r\n";
        $headers        .=  'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers        .=  'From: Admin<admin@autochain.co.nz>' . "\r\n";
    if(mail($to_email, $subject, $message, $headers)){ echo 'Mail sent'; }else{ echo 'Mail not sent'; }
   
