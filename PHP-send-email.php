//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE-EMAIL-LLLLLLLLLLLLLLLLLLLLLLLL 
			if($applicant_email!="" && $applicant_email!=" " && $applicant_email!=null){
			ini_set("SMTP", "aspmx.l.google.com");
			ini_set("sendmail_from", "admin@aismac.net");
			//ccccc
			function preparehtmlmail($html) {
				  preg_match_all('~<img.*?src=.([\/.a-z0-9:_-]+).*?>~si',$html,$matches);
				  $i = 0;
				  $paths = array();

				  foreach ($matches[1] as $img) {
				    $img_old = $img;

				    if(strpos($img, "http://") == false) {
				      $uri = parse_url($img);
				      $paths[$i]['path'] = $_SERVER['DOCUMENT_ROOT'].$uri['path'];
				      $content_id = md5($img);
				      $html = str_replace($img_old,'cid:'.$content_id,$html);
				      $paths[$i++]['cid'] = $content_id;
				    }
				  }

				  $boundary = "--".md5(uniqid(time()));
				  $headers .= "MIME-Version: 1.0\n";
				  $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
				  $headers .= "From: ".DEFCALLBACKMAIL."\r\n";
				  $multipart = '';
				  $multipart .= "--$boundary\n";
				  $kod = 'utf-8';
				  $multipart .= "Content-Type: text/html; charset=$kod\n";
				  $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
				  $multipart .= "$html\n\n";

				  foreach ($paths as $path) {
				    if(file_exists($path['path']))
				      $fp = fopen($path['path'],"r");
				      if (!$fp)  {
				        return false;
				      }

				    $imagetype = substr(strrchr($path['path'], '.' ),1);
				    $file = fread($fp, filesize($path['path']));
				    fclose($fp);

				    $message_part = "";

				    switch ($imagetype) {
				      case 'png':
				      case 'PNG':
				            $message_part .= "Content-Type: image/png";
				            break;
				      case 'jpg':
				      case 'jpeg':
				      case 'JPG':
				      case 'JPEG':
				            $message_part .= "Content-Type: image/jpeg";
				            break;
				      case 'gif':
				      case 'GIF':
				            $message_part .= "Content-Type: image/gif";
				            break;
				    }

				    $message_part .= "; file_name = \"$path\"\n";
				    $message_part .= 'Content-ID: <'.$path['cid'].">\n";
				    $message_part .= "Content-Transfer-Encoding: base64\n";
				    $message_part .= "Content-Disposition: inline; filename = \"".basename($path['path'])."\"\n\n";
				    $message_part .= chunk_split(base64_encode($file))."\n";
				    $multipart .= "--$boundary\n".$message_part."\n";

				  }

				  $multipart .= "--$boundary--\n";
				  return array('multipart' => $multipart, 'headers' => $headers);  
				}
			//ccccc  
				$subject = "Your are admitted to AISMAC. Welcome! ";
				$message = $custom_admission_letter; 
				$to_email_address = $applicant_email; 
				//$headers = "From: info@aismac.net\r\n";
				//$headers .= "Reply-To: byaruhangadb@gmail.com\r\n";
				//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
				//$message = "Some testing ocntent here";   
				//$headers = "From: admin@aismac.net";
				define("DEFCALLBACKMAIL", "admin@aismac.net"); // WIll be shown as "from".
				$final_msg = preparehtmlmail($message);
				mail($to_email_address, $subject, $final_msg['multipart'], $final_msg['headers']); 
				// send email with all images from html attached to letter
				//mail($to_email_address, $subject, $message, $headers); 
			}
		//EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE-EMAIL-LLLLLLLLLLLLLLLLLLLLLLLL
		echo 'Last admitted : '.$re;
	 }else{  echo "!! Failed to admit the applicant. Please try again.";
			 printf("Error: %s\n", mysqli_error($con));
	 } 
	 
   }//==================================================================================================
