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
					<b><font color="#C40056" size="5" face="Arial, Helvetica, sans-serif">Anulación de Reservas On-Line</font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<table height="100%" width="80%" border="0" align="center">
  <tr>
    <td>&nbsp;</td>
    <td valign="top"><br><br>
<?

	$sql = "SELECT email_pra FROM pra WHERE dni_pra = '" . trim($dni) . "'"; //busco el email del cliente para enviarle un correo
	$res = mysql_query($sql);
	$fila = mysql_fetch_array($res);
	$email = $fila['email_pra'];
	$sql = "DELETE FROM detalles WHERE localizador_reserva='" . $_POST['localizador'] . "' and dni_pra = '" . $_POST['dni'] . "'";
	mysql_query($sql);
	$sql = "select count(dni_pra) as num from reserva where dni_pra ='" . trim($dni) . "'";
	$res = mysql_query($sql);
	$fila = mysql_fetch_array($res);
	if($fila['num'] == 0){
		$sql = "DELETE FROM pra WHERE dni_pra = '" . trim($dni) . "'";
		mysql_query($sql);
	}
	// ***** ENVIAR EMAIL ***** 
	$ruta_tem = split("\/",$HTTP_REFERER);
	$ruta = $ruta_tem[0] . "//";
	for($i = 2; $i < count($ruta_tem)-2; $i++)
		$ruta = $ruta . $ruta_tem[$i] . "/";
	//creo el cuerpo del email

	$cuerpo = '<body><table width="100%" border="0" align="center" bgcolor="#FFFFCC" cellpadding="5" cellspacing="5"><tr><td width="80%" align="center"><b>' . $_GET['nom'] . ' ' . $_GET['ape1'] . ' ' . $_GET['ape2'] .  '</b></td>';
	$cuerpo = $cuerpo . '<td align="center"><img src="' . $ruta . 'imagenes/logo.gif" width="79" height="75"></td></tr><tr><td colspan="2">Se le informa que ';
	$cuerpo = $cuerpo . 'su solicitud de reserva ha sido anulada correctamente.</td></tr><tr><td colspan="2">En caso de haber realizado un ingreso para esta';
	$cuerpo = $cuerpo . ' reserva On-Line o cualquier otra duda o consulta p&oacute;ngase en contacto con nosotros mediante correo electr&oacute;nico a la';
	$cuerpo = $cuerpo . ' direcci&oacute;n ' . $datos['Email_Alb'] . ' o llamando ';
	$cuerpo = $cuerpo . 'al tel&eacute;fono ' . $datos['Tfno1_Alb'] . ' &oacute; al ' . $datos['Tfno2_Alb'] . '.</td></tr><tr><td colspan="2"><blockquote><blockquote><p><br>Muchas gracias ';
	$cuerpo = $cuerpo . 'por su confianza</p><p>Albergue Municipal de Le&oacute;n&nbsp;</p></blockquote></blockquote></td></tr></table></body>';

	//para el envío en formato HTML 
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
	
	$headers .= "From: Albergue Municipal de Leon <alberguedeleon@hotmail.com>\r\n"; //dirección del remitente 

	mail($email,"Confirmaci&oacute;n de anulaci&oacute;n de reserva on-line",$cuerpo,$headers);
?>
<TABLE border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
        <TR height="40">
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba1_a.gif"></TD>
            <TD>
                <TABLE width="100%" height="100%" cellpadding="0" cellspacing="0">
                  <TR>
                    <TD width="340">
                        <FONT color="#F79239" size="4" face="Arial, Helvetica, sans-serif"><B>&nbsp;Finalizada la Anulaci&oacute;n de la Reserva<B></FONT>
                    </TD>
                    <TD background="../imagenes/img_cuadro_a/cuadro_L_arr_a.gif">&nbsp;</TD>
              </TR></TABLE>
            </TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba2_a.gif"></TD>
        </TR>
        <TR>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_izq_a.gif">&nbsp;</TD>
            <TD>
                <center>
                <table>
                <tr><td>
                <font face="Arial, Helvetica, sans-serif" size="3">Su reserva ha sido anulada.</font></td>
                </tr>

                <tr>
                <td><font face="Arial, Helvetica, sans-serif" size="3">Si ya había realizado un Ingreso, recibirá un e-mail para indicarle dónde debe acudir para que se le devuelva el dinero.</font></td>
                <tr height="20"><td>&nbsp;</td></tr>
                </table>
            </TD>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_der_a.gif">&nbsp;</TD>
  </TR>
        <TR>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo1_a.gif"></TD>
            <TD background="../imagenes/img_cuadro_a/cuadro_L_aba_a.gif">&nbsp;</TD>
            <TD width="20"><img src="../imagenes/img_cuadro_a/cuadro_abajo2_a.gif"></TD>
        </TR>
		
</TABLE><br><br><br><br>
	
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
