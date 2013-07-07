<?php

	/*Variables*/
		$email  = $_POST['email'];
		$url	= $_POST['url'];
		$valid 	= validateEmail($email);
	
	/*Email Variables*/
		$subject 	= 'You have been invited to video chat'; 
		$message	= "
		
You have been invited to video chat.
Click on the following URL to view the conversation.

$url

Cheers,
The Vidtok Team

If you have any questions please contact support@vidtok.co with questions.		
		
		";
		$headers = 'From: The Vidtok Team <messages@vidtok.co>' . "\r\n" .
				   'Reply-To: The Vidtok Team <messages@vidtok.co>' . "\r\n";
				   
	/*Mail*/
		if($valid == 0){
			
			
		}else{
			
			mail($email, $subject, $message, $headers);
			
		}
		

/*  Validate Email Address
/*---------------------------*/
/*  */	
	
	function validateEmail($email){
		return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email);
	}


		