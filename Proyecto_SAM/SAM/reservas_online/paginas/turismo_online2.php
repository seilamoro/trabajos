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
	<title>::ALBERGUE MUNICIPAL DE LE�N</title>
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
                <td width="719" align="center" valign="middle"><u><!-- InstanceBeginEditable name="titulo" --> <b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Informaci&oacute;n 
								Tur&iacute;stica</font></b> <!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
									<table width='90%'>
										<tr>
											
														<td>
														<!---------------------------------------------------------------------------------------->
											
																
<tr>
																	<td align="center"><b><font color="#C40056" size="5" face="Arial, Helvetica, sans-serif">Informaci&oacute;n Tur&iacute;stica Regional </font></b></td>
																</tr>
																<tr>
																	<td>
																		<table border="0" width="100%" height="100%">
																			<tr>
																				<td width="25%" valign="top">
																					<table width="100%" height="100%" border="0" bgcolor="FFDF7F">
																						<tr>
																							<td width="17%"><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">Nombre:</font></b></td>
																							<td width="83%"><b><font face="Arial, Helvetica, sans-serif" size="2" color='#C60052'><b><i>Informaci�n Tur�stica de Castilla y Le�n</i></b></font></b></td>
																						</tr>
																						<tr>
																							<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">Tel&eacute;fonos:</font></b></td>
																							<td>902 20 30 30 </td>
																						</tr>
																						<tr>
																							<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">P&aacute;gina web</font><font color="#FF9239" size="2" face="Arial, Helvetica, sans-serif">:</font></b></td>
																							<td><font face="Arial, Helvetica, sans-serif" size="2" >www.turismocastillayleon.com</font></td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																			<tr>
																				<td width="25%" valign="top">
																					<table width="100%" height="100%" border="0" bgcolor="F9AF6D">
																						<tr>
																							<td><b><font face="Arial, Helvetica, sans-serif" size="2" aling="top" color="#DD6400">Localidad:</font></b></td>
																							<td><font face="Arial, Helvetica, sans-serif" size="2" ></font><b>Le&oacute;n </b></td>
																						</tr>
																						<tr>
																							<td width="17%"><b><font face="Arial, Helvetica, sans-serif" size="2" color="#DD6400">Nombre:</font></b></td>
																							<td width="83%"><b><font face="Arial, Helvetica, sans-serif" size="2" color='#C60052'><B><I>Oficina de Informacion Turistica de Le&oacute;n</I></B></font></b></td>
																						</tr>
																						<tr>
																							<td valign="top"><b><font face="Arial, Helvetica, sans-serif" size="2"  color="#DD6400">Direcci&oacute;n:</font></b></td>
																							<td><font face="Arial, Helvetica, sans-serif" size="2" ></font>Plaza de la Regla, 4 </td>
																						</tr>
																					<tr>
																						<td><b><font face="Arial, Helvetica, sans-serif" size="2" aling="top" color="#DD6400">C&oacute;digo 
																						Postal:</font></b></td>
																						<td><font face="Arial, Helvetica, sans-serif" size="2" >24003</font></td>
																					</tr>
																					<tr>
																						<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#DD6400">Tel&eacute;fonos:</font></b></td>
																						<td><font face="Arial, Helvetica, sans-serif" size="2" >987 23 70 82 </font></td>
																					</tr>
																					<tr>
																						<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#DD6400">Fax:</font></b></td>
																						<td><font face="Arial, Helvetica, sans-serif" size="2" >987 27 33 31 </font></td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
																	<tr>
																			<td height='30'>
																				&nbsp;
																			</td>
																	</tr>
														<!------------------------------------------------------------------------------------->
													
																	<tr>
																			<td width="25%" valign="top">
																				<table width="100%"  border="0">
																					<tr>
																						<td align="center"><b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Informaci&oacute;n Tur&iacute;stica Local</font></b></td>
																					</tr>
																					<tr>
																						<td>
																							<table border="0" width="100%" height="100%">
																								<tr>
																									<td width="25%" valign="top"><table width="100%" height="100%" border="0" bgcolor="FFDF7F">
																										<tr>
																											<td><b><font face="Arial, Helvetica, sans-serif" size="2" aling="top" color="#FF9239">Localidad:</font></b></td>
																											<td><font face="Arial, Helvetica, sans-serif" size="2"></font><b>Le&oacute;n</b> </td>
																										</tr>
																										<tr>
																											<td width="17%"><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">Nombre:</font></b></td>
																											<td><b><font face="Arial, Helvetica, sans-serif" size="2" color='#C60052'><B><I>Ayuntamiento de Le&oacute;n</I></B></font></b></td>
																										</tr>
																										
																										<tr>
																											<td valign="top"><b><font face="Arial, Helvetica, sans-serif" size="2"  color="#FF9239">Direcci&oacute;n:</font></b></td>
																											<td><font face="Arial, Helvetica, sans-serif" size="2" ></font>Avda. Ordo&ntilde;o II, 10 </td>
																										</tr>
																										<tr>
																											<td><b><font face="Arial, Helvetica, sans-serif" size="2" aling="top" color="#FF9239">C&oacute;digo Postal</font></b></td>
																											<td><font face="Arial, Helvetica, sans-serif" size="2" ></font>24001</td>
																										</tr>
																										<tr>
																											<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">Tel&eacute;fonos:</font></b></td>
																											<td><font face="Arial, Helvetica, sans-serif" size="2" >987 89 55 67 - 987 89 54 91 </font></td>
																										</tr>
																										<tr>
																											<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">Fax:</font></b></td>
																											<td><font face="Arial, Helvetica, sans-serif" size="2" >987 89 55 42 </font></td>
																										</tr>
																										<tr>
																											<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">E-mail:</font></b></td>
																											<td><font face="Arial, Helvetica, sans-serif" size="2" >ayturismo@argored.com </font></td>
																										</tr>
																										<tr>
																											<td><b><font face="Arial, Helvetica, sans-serif" size="2" color="#FF9239">P&aacute;gina web:</font></b></td>
																											<td><font face="Arial, Helvetica, sans-serif" size="2" >www.aytoleon.com </font></td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table> 
																		</td>
																	</tr>
																	<tr>
																			<td height='30'>
																				&nbsp;
																			</td>
																	</tr>
																			
														
														
														
														<!------------------------------------------------------------------------------------->


														</td>
													
														<td align='center'>
															<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
															<tr>
																<td width="33%"><br><a href="turismo_online.php"><img src="../imagenes/botones/anterior.gif" border="0"></td>
																<td align="center"><br><a href="info_pere_online.php"><img src="../imagenes/botones/atras.gif" width="65" height="30" border="0"></a></td>
																<td align="right" width="33%"><br><a href="turismo_online3.php"><img src="../imagenes/botones/siguiente.gif" border="0"></a>
																</td>
															</tr>
															</table>
														</td>
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
