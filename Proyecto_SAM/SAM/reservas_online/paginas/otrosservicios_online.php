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
					<b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Servicios</font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<table border="0" width="80%">
 <tr>
	<td height="33">&nbsp;</td>
	<td><table width="100%"  border="0" class="celda_ser">
	  <tr>
		<td width="4%"><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td width="96%">Sal&oacute;n de TV y biblioteca: dotada de TV por cable, sala prensa.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Sal&oacute;n de actos para 69 personas, pantalla de proyecci&oacute;n, retroproyector, ca&ntilde;&oacute;n de proyecci&oacute;n.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Aula para 40 personas.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Sala de reuniones para 10 personas.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Comedor y sala de estar.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Vigilancia mediante circuito cerrado de TV.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Consignas para equipaje y peque&ntilde;os objetos.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Servicio de lavadoras y secadoras.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>1 cabina de tel&eacute;fono.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Amplia zona de duchas de agua caliente (17 duchas, 7 WC, 18 lavaderos).</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Hilo musical.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Botiqu&iacute;n.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>M&aacute;quinas de bebidas calientes, fr&iacute;as y snacks.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Parking veh&iacute;culos y bus.</td>
	  </tr>
	  <tr>
		<td><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
		<td>Parking bibicletas.</td>
	  </tr>

	</table></td>
	<td>&nbsp;</td>
  
  <tr>
	<td height="33">&nbsp;</td>
	<td align="center"><br><a href="servicios_online_2.php"><img src="../imagenes/botones/anterior.gif" border="0"></a></td>
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
