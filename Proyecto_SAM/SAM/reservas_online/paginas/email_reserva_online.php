<?
	session_start();
?>
<html><!-- InstanceBegin template="/Templates/plantilla_php.dwt.php" codeOutsideHTMLIsLocked="false" -->
<?
	if(!isset($_SESSION['conexion'])){//si no esta creada aun la session de pag_hab
		include('../config.inc.php');	//incluimos el contenido de la pagina file_conf_skins.php
	}
?>
<head>
	<link rel="stylesheet" href="../css/estilos.css">
	<title>::ALBERGUE MUNICIPAL DE LEÓN</title>
	<!-- InstanceBeginEditable name="head" -->
	
	<!-- InstanceEndEditable -->
	</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td valign="top">
<table width="950" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F0FAD3">
        <tr> 
          	<td height="120" colspan="2" align="left" valign="top" bgcolor="#3333FF"> 
			<div><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100%" height="100%">
                    <param name="movie" value="../swf/superior.swf">
                    <param name=quality value=high>
                    <embed src="../swf/superior.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="100%"></embed> 
                  </object>
			</div>
			</td>
        </tr>
        <tr> 
          <td align="left" valign="top" width="200px" rowspan="2"> 
				<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#67720C">
					<tr> 
						<td align="center" valign="top" height="365">
						<?
							$nom = split("/",$PHP_SELF);
						?>
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100%" height="300">
								<param name="movie" value="../swf/menu.swf">
								<param name=quality value=high>
								<PARAM NAME=FlashVars VALUE="pagina=<?=$nom[count($nom)-1]?>">
								<embed src="../swf/menu.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="300"></embed> 
							</object>
						</td>
					</tr>
					<tr> 
						<td align="center">&nbsp;
							
						</td>
					</tr>
					<tr> 
					<td height="65">
							<table width="95%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								
                      			<td width="61"><img src="../imagenes/logo_peque.gif" width="61" height="50"></td> 
								<td>
									<font size="2" color="#FFFFFF"> 
									<?
										@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'],  $_SESSION['conexion']['pass']);
										if (!$db){
											echo "Error : No se ha podido conectar a la base de datos";
											exit;
										}
										mysql_select_db($_SESSION['conexion']['db']);

										$sql = "select *, pais.nombre_pais as Pais_Alb, provincia.nombre_pro as Provincia_Alb  from albergue inner join pais on ";
										$sql = $sql . "(albergue.id_pais = pais.id_pais) inner join provincia on (albergue.id_pro = provincia.id_pro)";
										$res = mysql_query($sql);
										$datos = mysql_fetch_array($res);
										echo $datos['Direccion_Alb'] . "<br>";
										echo "C.P.: " . $datos['CP_Alb'] . " (" . $datos['Localidad_Alb'] . ")<br>";
										echo "Tlf.: " . $datos['Tfno1_Alb'] . "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $datos['Tfno2_Alb'];
									?>
									</font>
								</td>
							</tr>
							</table>
					</td>
				</tr>
				</table>
			</td> 
          	<td align="left" valign="top">
				<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="31" height="35" valign="top"><img src="../imagenes/sep.gif" width="31" height="31"> 
                </td>
                <td width="719" align="center" valign="middle"><u><!-- InstanceBeginEditable name="titulo" -->
					<b><font color="#C40056" size="5" face="Arial, Helvetica, sans-serif">Solicitud de Reservas On-Line</font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<?
	$dni = $_POST['dni'];
	$nom = $_POST['nombre'];
	$ape1 = $_POST['apellido1'];
	$ape2 = $_POST['apellido2'];
	$tel = $_POST['telefono'];
	$email = $_POST['email'];
	$lleg = $_POST['llegada'];
	$dias = $_POST['dias'];
	//calculo la fecha de salida, en formato de fecha
	
	$sal = mktime(0, 0, 0, substr($lleg,3,2), substr($lleg,0,2) + $dias, substr($lleg,6,4));
	//pongo las fecha en el formato correcto para introducirlo en mysql
	$lleg_b =  substr($lleg,6,4) . "/" . substr($lleg,3,2). "/" . substr($lleg,0,2);
	$sal_b = strftime("%Y/%m/%d",$sal);
	$cam = $_POST['camas'];
	$comp = $_POST['compartidas'];
	$indi = $_POST['individuales'];
	$tarde = $_POST['tarde'];
	
	$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$ruta_tem = split("\/",$HTTP_REFERER);
	$ruta = $ruta_tem[0] . "//";
	for($i = 2; $i < count($ruta_tem)-2; $i++)
		$ruta = $ruta . $ruta_tem[$i] . "/";
	// ***** ENVIAR EMAIL ***** 
	
	include('../config.inc.php');
	require("./class.phpmailer.php");	//esta clase permite enviar emails con archivos adjuntos
	$mail = new PHPMailer();//instanciamos un objeto de la clase phpmailer 
	$mail->Mailer = "smtp";
	$mail->SMTPAuth = true;

	//Indicamos cual es nuestra dirección de correo y el nombre que queremos que vea el usuario que lee nuestro correo
	$mail->From = "alberguedeleon@hotmail.com";
	$mail->FromName = "Albergue Municipal de Leon";
	$mail->Username = $_SESSION['email_online']['usuario'];
	$mail->Password = $_SESSION['email_online']['password'];
	$mail->Host = $_SESSION['email_online']['servidor'];

	$mail->Timeout=30; //el valor por defecto 10 de Timeout es un poco escaso, por tanto lo pongo a 30
	
	$mail->AddEmbeddedImage('../imagenes/logo.gif', 'logo', 'logo.gif');
	
	$enlace_conf = $ruta . 'paginas/aceptar_reserva_online.php?dni=' . urlencode ($dni) . '&nom=' . urlencode ($nom) . '&ape1=' . urlencode ($ape1) . '&ape2=' . urlencode ($ape2) . '&tel=' . urlencode ($tel) . '&email=' . urlencode ($email) . '&lleg=' . $lleg_b . '&dias=' . $dias . '&sal=' . $sal_b . '&cam=' . $cam . '&comp=' . $comp . '&indi=' . $indi . '&tarde=' . urlencode ($tarde);


	$cuerpo = '<html><body><table width="100%" border="0" align="center" bgcolor="#FFFFCC" cellpadding="5" cellspacing="5"><tr><td width="80%" align="center">Bienvenido <b>' . $nom . ' ' . $ape1 . ' ' . $ape2 .  '</b></td>';
	$cuerpo = $cuerpo . '<td align="center"><img src="cid:logo" width="79" height="75"></td></tr>';
	$cuerpo = $cuerpo . '<tr><td>Verifique los datos de la Solicitud de Reserva On-Line:</td></tr>';
	$cuerpo = $cuerpo . '<tr><td colspan="2"><table width="95%" border="0" align="center"><tr> ';
	$cuerpo = $cuerpo . '<td><b>D.N.I.:</b></td><td>' . $dni . '</td>';
	$cuerpo = $cuerpo . '</tr><tr> <td><b>Nombre:</b></td><td>' . $nom . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><b>Apellidos:</b></td><td>' . $ape1 . ' ' . $ape2 . '</td></tr><tr>';
	$cuerpo = $cuerpo . '<td><b>Tel&eacute;fono:</b></td><td>' . $tel . '</td>';
	$cuerpo = $cuerpo . '</tr><tr><td><b>E-mail:</b></td><td>' . $email . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><b>Fecha Llegada:</b></td><td>' . $lleg . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><b>Fecha Salida:</b></td><td>' . strftime("%d/%m/%Y",$sal) . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><b>N&ordm;. de Personas:</b></td><td>' . $cam . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><b>Camas Individuales:</b></td><td>' . $indi . '</td></tr>';
	$cuerpo = $cuerpo . '<tr><td><b>Camas Compartidas:</b></td><td>' . $comp . '</td></tr></table></td></tr>';
	$cuerpo = $cuerpo . '<tr><td colspan="2">Pulse sobre el enlace para finalizar el proceso de Solicitud de Reserva On-Line:</td>';
	$cuerpo = $cuerpo . '</tr><tr><td colspan="2"><a href="' . $enlace_conf . '" target="_blank">Confirmar Solicitud de Reserva On-Line</a>.</td></tr><tr><td colspan="2">Para cualquier duda o ';
	$cuerpo = $cuerpo . 'consulta no dude en ponerse en contacto con nosotros mediante correo electr&oacute;nico a la direcci&oacute;n alberguedeleon@hotmail.com o llamando ';
	$cuerpo = $cuerpo . 'a tel&eacute;fono 987 08 18 32 o al 987 08 18 33.</td></tr><tr><td colspan="2"><blockquote><blockquote><p><br>Muchas gracias ';
	$cuerpo = $cuerpo . 'por su confianza</p><p>Albergue Municipal de Le&oacute;n&nbsp;</p><p>LE&Oacute;N, a ' . strftime("%d",time()) . ' de ' . $meses[strftime("%m",time())] . ' de ' . strftime("%Y",time()) . '.</p></blockquote></blockquote></td></tr></table></body></html>';
	
	$mail->AddAddress($email); //Indicamos cual es la dirección de destino del correo
	$mail->Subject = "Confirmaci&oacute;n de reserva on-line"; //Asignamos asunto y cuerpo del mensaje. el cuerpo del mensaje lo ponemos en formato html
	$mail->Body = $cuerpo;
	$mail->AltBody = $cuerpo; //Definimos AltBody por si el destinatario del correo no admite email con formato html 

	$mail->Send(); //se envia el mensaje

	
?>
<table border="0" height="100%" width="80%" align="center" valign="top">
  <tr>
    <td height="280">&nbsp;</td>
    <td valign="top"><br><br>
<TABLE border="0"  align="center"   width="100%" cellspacing="0" cellpadding="0">

        <TR height="40">
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba1_a.gif"></TD>
            <TD>
                <TABLE width="100%" height="100%" cellpadding="0" cellspacing="0">
                  <TR>
                    <TD width="210">
                        <FONT color="#F79239" size="4" face="Arial, Helvetica, sans-serif"><B>&nbsp;Verificaci&oacute;n del E-Mail<B></FONT>
                    </TD>
                    <TD background="../imagenes/img_cuadro_a/cuadro_L_arr_a.gif">&nbsp;</TD>
              </TR></TABLE>
            </TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba2_a.gif"></TD>
        </TR>
        <TR>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_izq_a.gif">&nbsp;</TD>
            <TD>
				<?
				if($num == 0){ ?>
                <table align="center">
                <tr><td>
                <font face="Arial, Helvetica, sans-serif" size="3">
					Se le ha enviado un e-mail a la dirección <b><?=$email?>,</b>
					pulse sobre el enlace que aparece en el e-mail para finalizar el proceso de Solicitud de Reserva On-Line.
				</font></td>

                <tr><td align="center"></td></tr>
                </table>
				<? }else{ ?>
					<FONT  color="#000000" size="3" face="Arial, Helvetica, sans-serif">
					El D.N.I. introducido <b><?=$dni?></b> ya tiene una reserva realizada para el día <b><?=$lleg?></b> en la base de datos del Albergue Municipal de León. <br>
					Si tiene alguna duda llame al teléfono 987081832 ó al 987081833. <br>
					</font>
					<center>
					<br><a href="#"><IMG src="../imagenes/botones/volver.gif" border="0" onclick="history.back();"/></a><INPUT type="button"  value="Volver"><br></center>
				
				<? } ?>
            </TD>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_der_a.gif">&nbsp;</TD>
  </TR>
        <TR>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo1_a.gif"></TD>
            <TD background="../imagenes/img_cuadro_a/cuadro_L_aba_a.gif">&nbsp;</TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo2_a.gif"></TD>
        </TR>
		
</TABLE>
	<br><br><br>
	</td>
    <td>&nbsp;</td>
  </tr>

</table>
							<!-- InstanceEndEditable --> 
                  </td>
              </tr>
              <tr> 
                <td height="35" colspan="2" align="center"> <font size="-1"> [:: 
                  <a href="../index.html">Inicio</a> ::] [:: Informaci&oacute;n 
                  [: <a href="info_juvenil_online.php">Albergue Juvenil</a> 
                  :] - [: <a href="info_pere_online.php">Albergue Peregrino</a> 
                  :] - [: <a href="servicios_online.php">Servicios</a> :] - [: 
                  <a href="accesos_online.php">Accesos</a> :] ::] <br>
                  [:: <a href="disponibilidad_online.php">Disponibilidad</a> ::] 
                  [:: Reservas On-Line [: <a href="reserva_online.php">Nueva</a> 
                  :] - [: <a href="anular_reserva_online.php">Eliminar</a> :] 
                  ::] [:: <a href="tarifa_listado_online.php">Tarifas</a> ::] 
                  </font> </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
</td></tr></table>
<?
mysql_close($db);
?>
</body>
<!-- InstanceEnd --></html>
