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

	$dni = htmlspecialchars(urldecode($_GET['dni']));
	$nom = htmlspecialchars(urldecode($_GET['nom']));
	$ape1 = htmlspecialchars(urldecode($_GET['ape1']));
	$ape2 = htmlspecialchars(urldecode($_GET['ape2']));
	$tel = htmlspecialchars(urldecode($_GET['tel']));
	$email = htmlspecialchars(urldecode($_GET['email']));
	$dias = $_GET['dias'];
	$lleg =  $_GET['lleg'];
	$sal = $_GET['sal'];
	$dia_res = $_GET['dia_res'];
	$cam = $_GET['cam'];
	$comp = $_GET['comp'];
	$indi = $_GET['indi'];
	$tarde = htmlspecialchars(urldecode($_GET['tarde']));

	$error = 0;
	$lle_com = mktime(0, 0, 0, substr($lleg,5,2), substr($lleg,8,2) + $dias, substr($lleg,0,4));
	$hoy_com = time();
	
	$hoy = mktime(0, 0, 0, strftime("%m",time()), strftime("%d",time()), strftime("%Y",time()));
	$lle_com = mktime(0, 0, 0, substr($lleg_b,5,2), substr($lleg_b,8,2), substr($lleg_b,0,4));
	$segundos = $lle_com - $hoy;
	$dif = intval($segundos/86400);
	
	if($lle_com >= $hoy_com){
		$error = 1;
	}
	
	$dia_res = strftime("%Y/%m/%d",$hoy_com);
	
	$sql = "INSERT INTO PRA (DNI_PRA, Nombre_PRA, Apellido1_PRA, Apellido2_PRA, Tfno_PRA, Email_PRA) VALUES ";
	$sql = $sql . "('" . trim($dni) . "','" . trim(strtoupper($nom)) . "','" . trim(strtoupper($ape1)) . "','" . trim(strtoupper($ape2)) . "','" . trim($tel) . "','" . trim($email) . "')";
	
	mysql_query($sql);
	
	$sql = "SELECT * FROM RESERVA WHERE DNI_PRA = '" . trim($dni) . "' AND FECHA_LLEGADA = '" . trim($lleg) . "'";
	$res = mysql_query($sql);
	$num = mysql_num_rows($res);
	if($num == 0 && error == 0){
		$sql = "INSERT INTO DETALLES (DNI_PRA, Fecha_Llegada, Fecha_Salida, PerNocta, Llegada_Tarde, Ingreso, Nombre_Empleado, ";
		$sql = $sql . "Fecha_Reserva, Localizador_Reserva) VALUES ('" . trim($dni) . "','" . trim($lleg) . "','" . trim($sal) . "',";
		$sql = $sql . "'" . trim($dias) . "','" . trim($tarde) . "',0,'','" . trim($dia_res) . "','')";
		mysql_query($sql);	
		$sql = "INSERT INTO RESERVA (DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas, Num_Camas_Indiv) VALUES ";
		$sql = $sql . "('" . trim($dni) . "','" . trim($lleg) . "','PRA','" . trim($cam) . "','" . trim($indi) . "')";
		mysql_query($sql);
	}	
?>
<table border="0" height="100%" width="75%" align="center">
  <tr>
    <td height="280">&nbsp;</td>
    <td valign="top"><br><br>
<TABLE border="0" align="center" width="100%" cellspacing="0" cellpadding="0">

        <TR height="40">
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba1_a.gif"></TD>
            <TD>
                <TABLE width="100%" height="100%" cellpadding="0" cellspacing="0">
                  <TR>
                    <TD width="310">
                        <FONT color="#F79239" size="4" face="Arial, Helvetica, sans-serif"><B>&nbsp;Finalizada la Solicitud de Reserva<B></FONT>
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
				if($num == 0 && error == 0){ ?>
					<table align="center">
					<tr><td>
					<font face="Arial, Helvetica, sans-serif" size="3">Sus datos se han guardado correctamente.</font></td>
					</tr>
					<tr>
					<td><font face="Arial, Helvetica, sans-serif" size="3">
					Su solicitud ser&aacute; tratada en breve y recibir&aacute; por email la confirmaci&oacute;n o no de su reserva.<br>
					Para cualquier duda o consulta no dude en ponerse en contacto con nosotros mediante correo electr&oacute;nico a la 
					direcci&oacute;n alberguedeleon@hotmail.com o llamando al tel&eacute;fono <?=$datos['Tfno1_Alb']?> o al <?=$datos['Tfno2_Alb']?>.
					</font></td>
					<tr><td align="center"></td></tr>
					</table>
				
				<? }else if($error == 1){ ?>
					<FONT  color="#000000" size="3" face="Arial, Helvetica, sans-serif">
					La fecha de llegada es inferior a la fecha actual, no se ha realizado ninguna Solicitud de Reserva On-Line.
					</font>
					<center>
					<br><a href="#"><IMG src="../imagenes/botones/volver.gif" border="0" onclick="location.href='reserva_online.php'"/></a>
					<br></center>
				
				<? }else{ ?>
					<FONT  color="#000000" size="3" face="Arial, Helvetica, sans-serif">
					El D.N.I. introducido <b><?=$dni?></b> ya tiene una reserva realizada para el día 
					<b><?=substr($lleg,8,2) . '/' . substr($lleg,5,2) . '/' . substr($lleg,0,4)?></b> en la base de datos del Albergue Municipal 
					de León. <br>Si tiene alguna duda llame al teléfono <?=$datos['Tfno1_Alb']?> ó al <?=$datos['Tfno2_Alb']?>. <br>
					</font>
					<center>
					<br><a href="#"><IMG src="../imagenes/botones/volver.gif" border="0" onclick="location.href='reserva_online.php'"/></a><br></center>
				
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
	<br><br><br><br>
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
