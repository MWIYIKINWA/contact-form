
<!--ProsperCode-->
<?php 

   


if(isset($_POST['name']) && isset($_POST['email'])){
	$email = &_POST['email'];
	$subject = &_POST['subject'];
	$body = &_POST['body'];

	require_once 'includes/PHPMailer.php';
	require_once 'includes/SMTP.php';
	require_once 'includes/Exception.php';

	
	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

	
	$mail = new PHPMailer();

	//smtp

	&mail->isSMTP();
	&mail->Host = 'smtp.gmail.com';
	&mail->SMTPAuth = true;
	&mail->Username = "isaacmwiyikinwa08@gmail.com";
	&mail->Password = '0756151375';
	&mail->Port = 587;
	&mail->SMTPSecure = "tls";

	//email...

	$mail->isHTML(true);
	&mail->setFrom($email, $name);
	$mail->addAddress("isaacmwiyikinwa08@gmail.com");
	$mail->subject = ("$email, ($subject)");
	$mail->Body = $body;

	if ($mail->sent()) {
		$status = "success";
		$response = "email is sent";
			}
			else
			{
				$status = "failed";
				$response = "oops! something went wrong : <br>".$mail->Errorinfo;
				}
				exit(json_encode(array("status" => $status, "response" => $response)));



      

	
}
}

?>
