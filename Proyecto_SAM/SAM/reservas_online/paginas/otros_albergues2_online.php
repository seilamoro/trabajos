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
                <td width="719" align="center" valign="middle"><u><!-- InstanceBeginEditable name="titulo" -->
					<b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Otros Albergues</font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<table>
  <tr>
    <td height="409">&nbsp;</td>
    <td valign="top">
    <table border="0" width="100%" height="100%">
        <tr bgcolor="#C24A87">
            <td width="25%" height="25%" valign="top">
                <table width="100%" height="100%" border="0" bgcolor="#C64984">
                    <tr>
                        <td class="letras_morado" width="10%">Localidad:</td>
                        <td class="letra"><b>Villar de Mazarife</b> </td>
                    </tr>
                    <tr>
                        <td class="letras_morado">Nombre:</td>
                        <td class="letra">Albergue Privado </td>
                    </tr>
                    <tr>
                        <td class="letras_morado">Direcci�n:</td>
                        <td class="letra">Corujo s/n </td>
                    </tr>
                    <tr>
                        <td class="letras_morado">N� Plazas:</td>
                        <td class="letra">66</td>
                    </tr>
                    <tr>
                        <td class="letras_morado">Tel�fono:</td>
                        <td class="letra">  987 39 06 97</td>
                    </tr>
                    <tr>
                        <td class="letras_morado">Apertura:</td>
                        <td class="letra"> Todo el A�o </td>
                    </tr>
          </table>          </td>
            <td width="25%" valign="top">
			<table width="100%" height="100%" border="0" bgcolor="#C64984">
              <tr>
                <td class="letras_morado" width="10%">Localidad:</td>
                <td class="letra"><b>Villadangos del Paramo</b> </td>
              </tr>
              <tr>
                <td class="letras_morado">Nombre:</td>
                <td class="letra">Albergue Municipal </td>
              </tr>
              <tr>
                <td class="letras_morado">Direcci&oacute;n:</td>
                <td class="letra">Ctra Villadangos  s/n</td>
              </tr>
              <tr>
                <td class="letras_morado">N&ordm; Plazas:</td>
                <td class="letra">80</td>
              </tr>
              <tr>
                <td class="letras_morado">Tel&eacute;fono:</td>
                <td class="letra">987 39 04 84  </td>
              </tr>
              <tr>
               <td class="letras_morado">Apertura:</td>
                <td class="letra"> Todo el A&ntilde;o </td>
              </tr>
          </table></td>
          <td width="25%" height="25%" valign="top"><table width="100%" height="100%" border="0" bgcolor="#C64984">
            <tr>
              <td class="letras_morado" width="10%">Localidad:</td>
              <td class="letra"><b>San Mart&iacute;n del Camino </b></td>
            </tr>
            <tr>
              <td class="letras_morado">Nombre:</td>
              <td class="letra">Albergue de la Junta Vecinal </td>
            </tr>
            <tr>
              <td class="letras_morado">Direcci&oacute;n:</td>
              <td class="letra">Junto al Deposito de Agua </td>
            </tr>
            <tr>
              <td class="letras_morado">N&ordm; Plazas:</td>
              <td class="letra">60</td>
            </tr>
            <tr>
              <td class="letras_morado">Tel&eacute;fono:</td>
              <td class="letra">656 54 45 55 </td>
            </tr>
            <tr>
             <td class="letras_morado">Apertura:</td>
              <td class="letra"> Todo el A&ntilde;o </td>
            </tr>
          </table></td>
          
        </tr>
        <tr>
          <td valign="top" colspan="3">
		  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="3">
                <table width="100%"  border="0" bgcolor="#FFAE6B" >
                  <tr>
                    <td class="letras_naranja" width="7%">Localidad:</td>
                   <td class="letra"><b>Hospital de &Oacute;rbigo </b></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td width="34%">
                <table width="100%" height="100%" border="0" bgcolor="#FFAE6B" >
                                      <tr> 
                                        <td class="letras_naranja" width="10%">Nombre:</td>
                                       <td class="letra">Albergue Municipal </td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">Direcci&oacute;n:</td>
                                        <td class="letra">La Vega s/n </td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">N&ordm; Plazas:</td>
                                        <td class="letra">28</td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">Tel&eacute;fono:</td>
                                        <td class="letra">987 38 82 06 </td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">Apertura:</td>
                                        <td class="letra">Todo el A&ntilde;o </td>
                                      </tr>
                                    </table></td>
             						 <td width="33%">
               						 <table width="100%"  border="0" bgcolor="#FFAE6B">
                                      <tr> 
                                        <td class="letras_naranja" width="10%">Nombre:</td>
                                        <td class="letra">Albergue Parroquial </td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">Direcci&oacute;n:</td>
                                        <td class="letra">Alvarez Vega </td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">N&ordm; Plazas:</td>
                                        <td class="letra">32</td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">Tel&eacute;fono:</td>
                                        <td class="letra">987 37 76 98 </td>
                                      </tr>
                                      <tr> 
                                        <td class="letras_naranja">Apertura:</td>
                                        <td class="letra">Todo el A&ntilde;o </td>
                                      </tr>
                                    </table></td>
									<td width="40%" bgcolor="#FFAE6B"></td>
            </tr>
          </table></td>
          
        </tr>
        <tr bgcolor="#F7FBD6">
            <td width="25%" valign="top"><table width="100%" height="100%" border="0" bgcolor="#FFDF7B" >
              <tr>
                <td class="letras_naranja">Localidad:</td>
                <td class="letra"><b>Santib&aacute;&ntilde;ez de Valdeiglesias</b> </td>
              </tr>
              <tr>
                <td class="letras_naranja">Nombre:</td>
                <td class="letra">Albergue Parroquial </td>
              </tr>
              <tr>
                <td class="letras_naranja">Direcci&oacute;n:</td>
                <td class="letra">Carramonte s/n </td>
              </tr>
              <tr>
                <td class="letras_naranja">N&ordm; Plazas:</td>
                <td class="letra">60</td>
              </tr>
              <tr>
                <td class="letras_naranja">Tel&eacute;fono:</td>
                <td class="letra"><p>987 37 76 98 </td>
              </tr>
              <tr>
                <td class="letras_naranja">Apertura:</td>
                <td class="letra">De Abril a Octubre </td>
              </tr>
            </table></td>
            <td width="25%" colspan="2" valign="top"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="2" >
                  <table width="100%"  border="0" bgcolor="#FFDF7B" >
                    <tr>
                      <td class="letras_naranja" width="6%">Localidad:</td>
                      <td class="letra"><b>Astorga</b></td>
                    </tr>
                </table></td>
				</tr>
              <tr>
                <td width="50%" valign="top">
                  <table width="100%" height="100%" border="0" bgcolor="#FFDF7B">
                    <tr>
                      <td class="letras_naranja">Nombre:</td>
                      <td class="letra">Albergue Municipal</td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">Direcci&oacute;n:</td>
                      <td class="letra">Pza. Marques de Astorga 13 </td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">N&ordm; Plazas:</td>
                      <td class="letra">238</td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">Tel&eacute;fono:</td>
                      <td class="letra"> 987 61 60 34 <br>660 54 77 51 </td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">Apertura:</td>
                      <td class="letra">Todo el A&ntilde;o </td>
                    </tr>
                </table></td>
                <td width="50%" valign="top">
                  <table width="100%" height="100%" border="0" bgcolor="#FFDF7B">
                    <tr>
                      <td class="letras_naranja">Nombre:</td>
                      <td class="letra">Albergue Privado San Javier </td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">Direcci&oacute;n:</td>
                      <td class="letra">Porteria 6 </td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">N&ordm; Plazas:</td>
                      <td class="letra">110</td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">Tel&eacute;fono:</td>
                      <td class="letra">987 61 85 32</td>
                    </tr>
                    <tr>
                      <td class="letras_naranja">Apertura:</td>
                      <td class="letra">Todo el A&ntilde;o </td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
        </tr>       
    </table>



    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="33">&nbsp;</td>
    <td align="center">
			<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="33%"><br><a href="otros_albergues_online.php"><img src="../imagenes/botones/anterior.gif" border="0"></a></td>
				<td align="center"><br><a href="info_pere_online.php"><img src="../imagenes/botones/atras.gif" width="65" height="30" border="0"></a></td>
				<td align="right" width="33%"><br><a href="otros_albergues3_online.php"><img src="../imagenes/botones/siguiente.gif" border="0"></a></td>
			</tr>
		</table>
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
