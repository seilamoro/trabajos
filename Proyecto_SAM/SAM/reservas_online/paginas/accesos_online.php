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
					<b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Accesos</font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
							
<table border="0" width="90%">
	<tr>
	<td align="left" valign="top" bgcolor="F9AF6D">
		<table width="100%"  border="0">
                          <tr>
				<td> 
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="400" height="400">
                                <param name="movie" value="../swf/accesos.swf">
						<param name="quality" value="high">
						<embed src="../swf/accesos.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="400" height="400"></embed>
					</object>
				</td>
				<td width="40%" valign="top"><table width="100%"  border="0" cellpadding="5">
                                <tr> 
                                  <td height="31" colspan="2">&nbsp;</td>
                                </tr>
                                <tr> 
                                  <td colspan="2"><font color="#CC0066" face="Arial, Helvetica, sans-serif" size="4"><b>Puntos 
                                    de Informaci&oacute;n y Atenci&oacute;n al 
                                    Peregrino</b></font></td>
                                </tr>
                                <tr> 
                                  <td colspan="2"><font size="2" face="Arial, Helvetica, sans-serif">Situado 
                                    en el Cruce de Puente Castro, entre las avenidas 
                                    Alcalde Miguel Casta&ntilde;o y Fern&aacute;ndez 
                                    Ladreda.</font> </td>
                                </tr>
                                <tr> 
                                  <td height="21" colspan="2">&nbsp;</td>
                                </tr>
                                <tr> 
                                  <td colspan="2"><font color="#CC0066" face="Arial, Helvetica, sans-serif" size="4"><b>C&oacute;mo 
                                    Llegar</b></font></td>
                                </tr>
                                <tr> 
                                  <td width="10%" valign="top"><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
                                  <td width="90%"><font face="Arial, Helvetica, sans-serif" size="2">Aeropuerto 
                                    de Le&oacute;n (5 Km).</font></td>
                                </tr>
                                <tr> 
                                  <td valign="top"><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
                                  <td><font face="Arial, Helvetica, sans-serif" size="2">Estaci&oacute;n 
                                    de Autobuses (20 minutos a pie).</font></td>
                                </tr>
                                <tr> 
                                  <td valign="top"><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
                                  <td><font face="Arial, Helvetica, sans-serif" size="2">Estaci&oacute;n 
                                    de RENFE (25 minutos a pie).</font></td>
                                </tr>
                                <tr> 
                                  <td valign="top"><img src="../imagenes/albergue/flecha.gif" width="20" height="13"></td>
                                  <td><font face="Arial, Helvetica, sans-serif" size="2">Estaci&oacute;n 
                                    FEVE (40 minutos a pie).</font></td>
                                </tr>
                              </table>
				</td>
			</tr>
		</table>
	</td>

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
