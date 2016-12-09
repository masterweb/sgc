<?



require_once 'Conexion.php';



//Function to generate a new random number



function generate_session($strlen){



    return substr(md5(uniqid(rand(),1)),1,$strlen);



}











//Function to check for valid email



function is_valid_email($string) {



	return preg_match('/^[.\w-]+@([\w-]+\.)+[a-zA-Z]{2,6}$/', $string);



}







//If submitting a request



$message = null;



if($_POST['user_email'] != NULL){



	if(is_valid_email($_POST['user_email']) == false){



		$message = 'Email inv&aacute;lido.';



	} else {



		if($SYSTEM_TYPE == 1){



			$objConexion= new Conexion();



            $conexion=$objConexion->conectar();



			$sql = "SELECT COUNT(*) as cuenta FROM `emaillist` WHERE `email`='$_POST[user_email]'";

			$get = $conexion->query($sql);



			$check=$get->fetch_assoc();



			if($check[cuenta] > 0){



				echo "<Script Languaje=\"JavaScript\"> alert('Su correo ya consta en nuestra Base de Datos.');</Script>";

                                echo "<Script Languaje=\"JavaScript\"> history.go(-1);</Script>";

                                exit;

                            //$message = 'Su correo ya consta en nuestra Base.';



			}



		} else {



			$file = fopen($FILE_LOCATION,'r');



			while($read = fread($file,1024657)){



				$emailist .= $read;



			}



			fclose($file);



			$lines = explode("\n",$emailist);



			foreach($lines as $line){



				$record = explode('|++|',$line);



				if($record[0] == $_POST['user_email']){



					$message = 'Su correo ya consta en nuestra Base.';



				}



			}



		}



	}



	if($message == NULL){



		$user_email = $_POST['user_email'];



		$delete_code = generate_session(8);



		$status = generate_session(10);



		if($REQUIRE_CONFIRM == 1){



			$email_status = '0|'.$status;



			$conf = 'Revise su correo para confirmaci&oacute;n.';



			$email_conf = 'Por favor para la confirmaci&oacute;n de su email de click en el siguiente link: <br> <a href="'.$EMAIL_OPTIONS['URL'].'boletin_confirmacion.php?act_id='.$status.'">'.$EMAIL_OPTIONS['URL'].'boletin_confirmacion.php?act_id='.$status.'</a><br/>



			<br>Si usted no hizo la suscripci&oacute;n, haga caso omiso de este email.';



		} else {



			$email_status = '1';



			$email_conf = 'Our site does not require email confirmation, your email was added';



		}



		if($SYSTEM_TYPE == 1){
			
			$hoy = date("Y-m-d");
			$insert = "INSERT INTO `emaillist` (`email`,`status`,`delete_code`,fecha,nombre,apellido,provincia,empresa,cargo,telefono) VALUES ('$user_email','$email_status','$delete_code','$hoy','','','0','','0','')";

			$insertget = $conexion->query($insert);



			if($insertget){

                            

                            $message = 'Hemos ingresado su e-mail con &eacute;xito. '.$conf;   

                            echo("<script language=\"javascript\">");

                            echo("top.location.href = \"suscribe_form.php?Email=".$user_email."&mess=1\";");

                            echo("</script>");

                            /*header('location: ./suscribe_form.php?mess=1&email=$user_email');

                            return;*/

			}

		}



		if($message != NULL){



			//Setup subject template



			$user_message = str_replace('$+confirm_require+$',$email_conf,$REQUIRE_TEMPLATE);



			//echo $user_message;



		



			//Setup headers



			//$user_header = "Return-Path: ".$EMAIL_OPTIONS['TITLE']." <".$EMAIL_OPTIONS['FROM'].">\r\n"; 



			$user_header = "From: ".$EMAIL_OPTIONS['TITLE']." <".$EMAIL_OPTIONS['FROM'].">\r\n";



			$user_header .= "Content-Type: text/html; charset=utf-8\n";// Mime type 



			$user_header .= "X-Mailer: PHP\n"; // mailer



			$user_header .= "X-Priority: 1\n"; // Urgent message!







			//send email



			mail ($user_email,'Boletines Kia Motors Ecuador',$user_message,$user_header);



		}



	}



//	if($SYSTEM_TYPE == 1){disconnect_me();}		



}







//if removing oneself from mailing list



if($_GET['rmid'] != NULL && $ALLOW_REMOVAL == 1){



	$delete_code = $_GET['rmid'];



	if($SYSTEM_TYPE == 1){



		$objConexion= new Conexion();



        $conexion=$objConexion->conectar();



		$sql="SELECT `email` FROM `emaillist` WHERE `delete_code`='$delete_code'";



		$get = $conexion->query($sql);



		$check=$get->fetch_assoc();



		if($check[email] != NULL){



			$sqlremove = "DELETE FROM `emaillist` WHERE `delete_code`='$delete_code'";	



			$remove = $conexion->query($sqlremove);



			if($remove){ 



				$message = 'Su email se ha quitado de nuestra base.';



				$the_email = $check[email];



			}



		}



		



		//$check = mysql_fetch_row(mysql_query("SELECT `email` FROM `emaillist` WHERE `delete_code`='$delete_code'"));



		/*if($check[0] != NULL){



			$remove = mysql_query("DELETE FROM `emaillist` WHERE `delete_code`='$delete_code'");



			if($remove){ 



				$message = 'Su email se ha quitado de nuestra base.';



				$the_email = $check[0];



			}



		}*/



		//disconnect_me();



	}



	if($message == NULL){



		$message = 'Email no encontrado.';



	} else {



		//Setup subject template



		$user_message = str_replace('$+email_address+$',$the_email,$REMOVE_TEMPLATE);



		//Setup headers



		$user_header = "Return-Path: ".$EMAIL_OPTIONS['TITLE']." <".$EMAIL_OPTIONS['FROM'].">\r\n"; 



		$user_header .= "From: ".$EMAIL_OPTIONS['TITLE']." <".$EMAIL_OPTIONS['FROM'].">\r\n";



		$user_header .= "Content-Type: ".$EMAIL_OPTIONS['TYPE']."; charset=".$EMAIL_OPTIONS['CHARSET'].";\n\n\r\n"; 



		//send email



		mail ($the_email,'Remover del Boletin',$user_message,$user_header);			



	}



}







//If confirming email



if($_GET['act_id'] != NULL){



	$status_code = '0|'.$_GET['act_id'];



	if($SYSTEM_TYPE == 1){



		$objConexion= new Conexion();



        $conexion=$objConexion->conectar();



		$sql="SELECT `email` FROM `emaillist` WHERE `status`='$status_code'";



		$get = $conexion->query($sql);



		$check=$get->fetch_assoc();



		if($check[email] != NULL){



			$sqlremove = "UPDATE `emaillist` SET `status`='1' WHERE `status`='$status_code'";	



			$remove = $conexion->query($sqlremove);



			if($remove){ 



				$message = 'Su email ha sido Confirmado.';



		     }



		}



		//$check = mysql_fetch_row(mysql_query("SELECT `email` FROM `emaillist` WHERE `status`='$status_code'"));



		/*if($check[0] != NULL){



			$remove = mysql_query("UPDATE `emaillist` SET `status`='1' WHERE `status`='$status_code'");



			if($remove){ 



				$message = 'Your email has been confirmed.';



			}



		}*/



		//$objConexion->desconectar();      OJO



	} else {



		$file = fopen($FILE_LOCATION,'r');



		while($read = fread($file,1024657)){



			$emailist .= $read;



		}



		fclose($file);



		$lines = explode("\n",$emailist);



		foreach($lines as $line){



			$record = explode('|++|',$line);



			if($record[1] != $status_code){



				$new_list .= $email."\n";



			} else {



				$new_list .= $record[0].'|++|1|++|'.$record[2]."\n";;



				$message = 'Su email ha sido Confirmado.';



			}



		}



		$file_w = fopen($FILE_LOCATION,'w');



		fwrite($file_w,$new_list);



		fclose($file_w);



	}



}



?>