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
					<B><FONT color="#C40056" size="5">Anulación de Reservas On-Line</FONT></B>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<table border="0" height="100%" width="90%" align="center" cellspacing="0" cellpadding="0">

  <tr>
    <td>&nbsp;</td>
    <td valign="top"><br><br>
	<FORM name="form" action="aceptar_anulacion_reserva_online.php" method="POST">
	<?
	
	$loc = $_POST['localizador'];
	$dni = $_POST['dni'];
	$sql = "select detalles.localizador_reserva as loca, reserva.dni_pra as dni, reserva.fecha_llegada as llega, ";
	$sql = $sql . "detalles.pernocta as pn, pra.nombre_pra as nom, pra.apellido1_pra as ape1, pra.apellido2_pra as ape2, ";
	$sql = $sql . "pra.tfno_pra as tele, email_pra as email, reserva.num_camas as camas, reserva.num_camas_indiv as indi, ";
	$sql = $sql . "detalles.llegada_tarde as tarde, detalles.nombre_empleado as empleado from reserva INNER JOIN detalles ON ";
	$sql = $sql . "(reserva.dni_pra = detalles.dni_pra and reserva.fecha_llegada = detalles.fecha_llegada) ";
	$sql = $sql . "INNER JOIN pra ON (reserva.dni_pra = pra.dni_pra) WHERE ";
	$sql = $sql . "localizador_reserva = '" . $loc . "' and reserva.dni_pra = '" . $dni . "'";
	$res = mysql_query($sql);
	$fila = mysql_fetch_array($res);
	$num = mysql_num_rows($res);
	?>

	<TABLE border="0" align="center" width="100%" cellspacing="0" cellpadding="0">

        <TR height="40">
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba1_a.gif"></TD>
            <TD>
                <TABLE height="100%" width="100%" cellspacing="0" cellpadding="0"><TR>
                    <TD width="260">
                        <FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><B><font color="#F79239">&nbsp;Datos de la Solicitud de Reserva</font><B></FONT>
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
				<? if($num != 0){ ?>
            	<TABLE border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
                     <TR><TD>
                         <FONT  color="#000000" size="2" face="Arial, Helvetica, sans-serif">¿Está seguro que desea anular esta reserva?</FONT>
                  </TR></TD>
				   <tr><td><FONT size="-1">&nbsp;</FONT></td></tr>
            	</table>
            	</center>
            
            <table border="0" width="90%" align="center">
            <tr>
            	<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>DNI</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif">
				<?=$fila['dni']?><input name="localizador" type="hidden" value="<?=$loc?>" class="input_formulario">
				<input name="dni" type="hidden" value="<?=$fila['dni']?>" class="input_formulario">
				</font></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Nombre</b></font></td>
            	<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$fila['nom']?></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Primer Apellido</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$fila['ape1']?></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Segundo Apellido</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$fila['ape2']?></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Teléfono</b></font></td>
            	<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$fila['tele']?></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>E-mail</b></font></td>
            	<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$fila['email']?></td>
            </tr>
            <tr>
            	<td><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Fecha Llegada</b></font></td>
            	<td><FONT  size="3" face="Arial, Helvetica, sans-serif">
					<? $fecha = substr($fila['llega'],8,2) . "/" . substr($fila['llega'],5,2) . "/" . substr($fila['llega'],0,4); ?>
					<?=$fecha?>
				</td>
            </tr>
			<tr>
				<td colspan="2">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Número días</b></font></td>
							<td><FONT size="3" face="Arial, Helvetica, sans-serif"><?=$fila['pn']?></td>
							<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Número de personas</b></font></td>
							<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$fila['camas']?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Nº de camas compartidas</b></font></td>
							<td><FONT  size="3" face="Arial, Helvetica, sans-serif">
								<? $comp = $fila['camas'] - $fila['indi'] ?>
								<?=$comp?>
							</td>
							<td width="40%"><FONT color="#3366FF" size="3" face="Arial, Helvetica, sans-serif"><b>Nº de camas individuales</b></font></td>
							<td><FONT  size="3" face="Arial, Helvetica, sans-serif"><?=$fila['indi']?></td>
						</tr>
					</table>
				</td>
			</tr>
            </table>
             <TABLE border="0" align="center" width="90%" cellspacing="2" cellpadding="2">
            	<TR><TD>
                    <TABLE width="100%">
            		    <TR>
            			    <TD width="50%" align="center" > <img src='../imagenes/botones/aceptar.gif'  onclick="document.forms[0].submit()" border=0 style="cursor:pointer;"></a></TD>
            			    <TD align="center"><img src='../imagenes/botones/cancelar.gif' onclick=location.href="anular_reserva_online.php" border=0 style="cursor:pointer;"></TD>
                        </TR>
            </TABLE>
            </td></tr></table>
			<? }else{ ?>
			<TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
				<TR><TD align="center">
					<FONT  color="#000000" size="3" face="Arial, Helvetica, sans-serif">
					<br>No se ha encontrado ninguna reserva con el localizador <b><?=$loc?></b> y el D.N.I. introducidos<b><?=$dni?></b><br><br>
					<a href='#'><img src='../imagenes/botones/volver.gif'  onclick=location.href="anular_reserva_online.php" border=0/></a> <br><br>
					</FONT>
				</TD></TR>
			</TABLE>
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
	
	<br><br>
	
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
