<?
	session_start();
	@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'],  $_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	$sql = "select *  from albergue";
	$res = mysql_query($sql);
	$datos = mysql_fetch_array($res);
	

	require("../class.phpmailer.php");	//esta clase permite enviar emails con archivos adjuntos
	$mail = new PHPMailer();//instanciamos un objeto de la clase phpmailer 
	$mail->Mailer = "smtp";
	$mail->SMTPAuth = true;

	//Indicamos cual es nuestra dirección de correo y el nombre que queremos que vea el usuario que lee nuestro correo
	$mail->From = $datos[Email_Alb];
	$mail->FromName = $datos[Nombre_Alb];
	$mail->Username = $_SESSION['email_online']['usuario'];
	$mail->Password = $_SESSION['email_online']['password'];
	$mail->Host = $_SESSION['email_online']['servidor'];

	$mail->Timeout=30; //el valor por defecto 10 de Timeout es un poco escaso, por tanto lo pongo a 30
	
	$mail->AddEmbeddedImage('../../../imagenes/logo.gif', 'logo', 'logo.gif');
	
	//creo el cuerpo del email
	$cuerpo = '<body><table width="100%" border="0" align="center" bgcolor="#FFFFCC" cellpadding="5" cellspacing="5"><tr><td width="80%" align="center"><b>' . $_GET['nom'] . ' ' . $_GET['ape1'] . ' ' . $_GET['ape2'] .  '</b></td>';
	$cuerpo = $cuerpo . '<td align="center"><img src="cid:logo" width="79" height="75"></td></tr><tr><td colspan="2">Se le informa que ';
	$cuerpo = $cuerpo . 'su solicitud de reserva no se ha podido llevar a cabo en relaci&oacute;n a la disponibilidad de camas para los d&iacute;as';
	$cuerpo = $cuerpo . ' solicitados.</td></tr><tr><td colspan="2">En caso de haber realizado un ingreso para esta';
	$cuerpo = $cuerpo . ' reserva On-Line o cualquier otra duda o consulta p&oacute;ngase en contacto con nosotros mediante correo electr&oacute;nico a la'; 
	$cuerpo = $cuerpo . ' direcci&oacute;n ' . $datos['Email_Alb'] . ' o llamando ';
	$cuerpo = $cuerpo . 'al tel&eacute;fono ' . $datos['Tfno1_Alb'] . ' &oacute; al ' . $datos['Tfno2_Alb'] . '.</td></tr><tr><td colspan="2"><blockquote><blockquote><p><br>Muchas gracias ';
	$cuerpo = $cuerpo . 'por su confianza</p><p>Albergue Municipal de Le&oacute;n&nbsp;</p></blockquote></blockquote></td></tr></table></body>';

	$mail->AddAddress($_GET['email']); //Indicamos cual es la dirección de destino del correo
	
	$mail->Subject = "Solicitud de reserva rechazada"; //Asignamos asunto y cuerpo del mensaje. el cuerpo del mensaje lo ponemos en formato html
	$mail->Body = $cuerpo;
	
	$mail->AltBody = $cuerpo; //Definimos AltBody por si el destinatario del correo no admite email con formato html 

	$mail->Send(); //se envia el mensaje
	mysql_close($db);
	echo "<script>window.close();</script>";
?>
