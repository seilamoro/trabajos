<?
	session_start();
	$_SESSION['reserva_s']['dni'] = $_POST['dni'];
	$_SESSION['reserva_s']['nombre'] = strtoupper($_POST['nombre']);
	$_SESSION['reserva_s']['apellido1'] = strtoupper($_POST['apellido1']);
	$_SESSION['reserva_s']['apellido2'] = strtoupper($_POST['apellido2']);
	$_SESSION['reserva_s']['telefono'] = $_POST['telefono'];
	$_SESSION['reserva_s']['email'] = $_POST['email'];
	$_SESSION['reserva_s']['seleccionaDia'] = $_POST['seleccionaDia'];
	$_SESSION['reserva_s']['seleccionaMes'] = $_POST['seleccionaMes'];
	$_SESSION['reserva_s']['seleccionaAnyo'] = $_POST['seleccionaAnyo'];
	$_SESSION['reserva_s']['dias'] = $_POST['dias'];
	$_SESSION['reserva_s']['camas'] = $_POST['camas'];
	$_SESSION['reserva_s']['compartidas'] = $_POST['compartidas'];
	$_SESSION['reserva_s']['individuales'] = $_POST['individuales'];
	$_SESSION['reserva_s']['check_tarde'] = $_POST['check_tarde'];
	$_SESSION['reserva_s']['tarde'] = $_POST['tarde'];
?>
<html><!-- InstanceBegin template="/Templates/plantilla_php.dwt.php" codeOutsideHTMLIsLocked="false" -->
<?
	
	if(!isset($_SESSION['conexion'])){//si no esta creada aun la session de pag_hab
		include('../config.inc.php');	//incluimos el contenido de la pagina file_conf_skins.php
	}
?>
<?php
if($_POST['seleccionaDia']<10)
	$_POST['seleccionaDia']="0".$_POST['seleccionaDia'];
if($_POST['seleccionaMes']<10)
	$_POST['seleccionaMes']="0".$_POST['seleccionaMes'];
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
					<b><font color="#C40056" size="5">Solicitud de Reservas On-Line </font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<?
	if (strtolower($_POST['codigo']) != $_SESSION['codigo']){
		echo "<script>";
			echo "alert('El codigo no coincide con el codigo de la imagen');";
			echo "location.href='reserva_online.php?recargar=si';";
		echo "</script>";
	}
?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50">&nbsp;</td>
    <td valign="top">
	
	<FORM name="form1" id="form1" action="email_reserva_online.php" method="POST">
	
	<TABLE border="0" align="center" width="100%" cellspacing="0" cellpadding="0">

        <TR height="40">
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba1_a.gif"></TD>
            <TD>
                <TABLE height="100%" width="100%" cellspacing="0" cellpadding="0"><TR>
                    <TD width="240">
                        <FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><B><font color="#F79239">&nbsp;Datos de Solicitud de Reserva</font><B></FONT>
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
				$sql = "select nombre_pra as nom, apellido1_pra as ape1, apellido2_pra as ape2, tfno_pra as tele, email_pra as email from pra where dni_pra = '" . $_POST['dni'] . "'";
				$res = mysql_query($sql);
				$num = mysql_num_rows($res);
				$fila = mysql_fetch_array($res);
				//Si ya existe un PRA con ese dni, se comprueba que coinciden los datos introduccidos con los de la base de datos.
				//Si no existe o los datos coinciden se continua con el alta, si no se muestra un error.
				if($num == 0 || ($fila['nom'] == $_POST['nombre'] && $fila['ape1'] == $_POST['apellido1'] && $fila['ape2'] == $_POST['apellido2'] && $fila['tele'] == $_POST['telefono'] && $fila['email'] == $_POST['email'])){
				?>
            	<TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
                     <TR><TD>
                         <FONT  color="#000000" size="2" face="Arial, Helvetica, sans-serif">Compruebe si sus datos son correctos. En caso afirmativo, pulse el botón Aceptar. En caso de que quiera realizar algún cambio, pulse el botón Modificar.</FONT>
                  </TR></TD>
				  <tr><td><FONT size="-1">&nbsp;</FONT></td></tr>
            	</table>
            
            <table border="0" align="center" width="90%">
            <tr>
            	<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>DNI</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['dni']?><input type="hidden" name="dni" value="<?=$_POST['dni']?>"></font></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Nombre</b></font></td>
            	<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['nombre']?><input type="hidden" name="nombre" value="<?=strtoupper($_POST['nombre'])?>"></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Primer Apellido</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['apellido1']?><input type="hidden" name="apellido1" value="<?=strtoupper($_POST['apellido1'])?>"></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Segundo Apellido</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['apellido2']?><input type="hidden" name="apellido2" value="<?=strtoupper($_POST['apellido2'])?>"></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Teléfono</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['telefono']?><input type="hidden" name="telefono" value="<?=$_POST['telefono']?>"></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>E-mail</b></font></td>
            	<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['email']?><input type="hidden" name="email" value="<?=$_POST['email']?>"></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Fecha Llegada</b></font></td>
				<? $dia = $_POST['seleccionaDia'] . "/" . $_POST['seleccionaMes'] . "/" . $_POST['seleccionaAnyo']; ?>
            	<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$dia?><input type="hidden" name="llegada" value="<?=$dia?>"></td>
            </tr>
            <tr>
				<td colspan="2">
					<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
						<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Número días</b></font></td>
						<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['dias']?><input type="hidden" name="dias" value="<?=$_POST['dias']?>"></td>
						<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Número de personas</b></font></td>
						<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['camas']?><input type="hidden" name="camas" value="<?=$_POST['camas']?>"></td>
            		</tr></table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>
						<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Nº de camas compartidas</b></font></td>
						<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['compartidas']?><input type="hidden" name="compartidas" value="<?=$_POST['compartidas']?>"></td>
						<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Nº de camas individuales</b></font></td>
						<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$_POST['individuales']?><input type="hidden" name="individuales" value="<?=$_POST['individuales']?>"></td>
		            </tr></table>
				</td>
			<tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Llega tarde</b></font></td>
            	<td><FONT  size="3" face="Arial, Helvetica, sans-serif">
					<? 
						if($_POST['check_tarde'] == "on"){ 
							echo $_POST['tarde'];
						}else{
							echo "No";
						}
					?>
					<input type="hidden" name="tarde" value="<?=$_POST['tarde']?>">
					
				</td>
            </tr>
            </table>
			<br>
             <TABLE border="0" align="center" width="90%" cellspacing="1" cellpadding="1">
				<TR>
					<TD width="50%" align="center">
						<a href="javascript:location.href='reserva_online.php?recargar=si';"><IMG src="../imagenes/botones/modificar.gif" border="0"></a>
					</TD>
					<TD align="center">
						<a href="javascript:document.getElementById('form1').submit();"><IMG src="../imagenes/botones/aceptar.gif" border="0"></a>
            		</td>
				</tr>
			 </table>
			<? }else{ ?>
				<TABLE border="0" align="center" width="90%" cellspacing="2" cellpadding="2">
					<tr>
						<td><br>
							<FONT  color="#000000" size="3" face="Arial, Helvetica, sans-serif">
							El D.N.I. introducido <b><?=$_POST['dni']?></b> no coincide con los datos existentes en la base de datos del Albergue Municipal de León. <br>
							Si desea modificar alguno de sus datos personales llame al teléfono 987081832 ó al 987081833. <br>
							</font>
						</td>
					</tr>
					<tr>
						<td align="center">
						<br><a href="#"><IMG src="../imagenes/botones/volver.gif" border="0" onclick="location.href='reserva_online.php?recargar=si''"/></a><br>
						</td>
					</tr>
				</table>
			<? 
				} 
			?>
        </TD>
        <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_der_a.gif">&nbsp;</TD>
    </TR>
    <TR>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo1_a.gif"></TD>
            <TD background="../imagenes/img_cuadro_a/cuadro_L_aba_a.gif">&nbsp;</TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo2_a.gif"></TD>
      </TR>
    </TABLE>

	</td>
	<td width="40">&nbsp;</td>
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
