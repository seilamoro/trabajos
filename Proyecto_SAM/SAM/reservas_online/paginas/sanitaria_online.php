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
	<LINK rel='stylesheet' href='../css/sanitaria.css'>
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
					<b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056"> Información Sanitaria</font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<table border="0">

  <tr>
    <td height="409" width="80">&nbsp;</td>
    <td valign="top">
    <table border="1px" width="100%" cellpadding='4'>
        
                <TR class='titulo'>
					<TD class='titulo' width='150'> Localidad </TD>
					<TD class='titulo' width='200'> Consulta Médica </TD>
					<TD class='titulo' width='200'> Consulta Enfermería </TD>
					<TD class='titulo' width='190'> Centro de Referencia <BR> Urgencias / 24H </TD>
				</TR>

<?php
	if(!isset($_GET['verpag']) || $_GET['verpag']==1)
		{
?>
				<TR class='fila'>
					<TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Molinaseca </TD>
					<TD> 09:00 a 14:00 (lun., mar., mièr. y vier.) <BR> 12:30 a 14:00 (juev.)</TD>
					<TD> 10:00 a 13:30 (lun., mar., mièr. y vier.) <BR> 11:30 a 13:30 (juev.)</TD>
					<TD rowspan='2'> C. Salud Ponferrada II<BR> C/ Pico Tuerto s/n <BR> Tlf: 987 410 250 </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Ponferrada </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.) </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.)  </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG src='../imagenes/cruz.gif' width="45" height="45" class='cruz'> 
                              El Burgo Ranero </TD>
					<TD> 12:30 (lun., miér. y juev.)<BR> 13:00 (mar. y vier.) </TD>
					<TD> 13:00 (lun. y juev.) <BR> 13:30 (mar. y vier.) <BR> 12:00 (miér.) </TD>
					<TD rowspan='3'> C. Salud de Mansilla de las Mulas <BR> Avda. Villa del Lil s/n. <BR> Tlf: 987 311 175 </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG src='../imagenes/cruz.gif' width="45" height="45" class='cruz'> 
                              Mansilla de las Mulas </TD>
					<TD> 09:00 a 15:00<BR> (lun. a vier.) </TD>
					<TD> 11:00 a 15:00<BR> (lun. a vier.)</TD>
				</TR>
				<TR class='fila'>
					<TD> Mansilla Mayor </TD>
					<TD> 13:00 a 14:00<BR> (mier. y juev.) </TD>
					<TD> 09:00 a 10:30<BR> (lun. y miér.)</TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG src='../imagenes/cruz.gif' width="45" height="45" class='cruz'> 
                              León </TD>
					<TD> 08:30 a 21:00 <BR> (lun. a vier.) </TD>
					<TD> 08:30 a 21:00 <BR> (lun. a vier.)  </TD>
					<TD> C. Salud José Aguado<BR> C/ José Aguado s/n. <BR> Tlf: 987 211 311 </TD>
				</TR>
				<TR class='fila'>
					<TD> Trobajo del Camino </TD>
					<TD> 08:30 a 14:00 <BR> (lun. a vier.) </TD>
					<TD> 08:30 a 14:00 <BR> (lun. a vier.)  </TD>
					<TD rowspan='2'> C. Salud San Andrés del Rabanedo<BR> C/ Burbia 23 <BR> Tlf: 987 228 024 </TD>
				</TR>
				<TR class='fila'>
					<TD> La Virgen del Camino </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.) </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.)  </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG src='../imagenes/cruz.gif' width="45" height="45" class='cruz'> 
                              Villadangos del Páramo </TD>
					<TD> 12:30 a 15:00 <BR> (lun. a vier.) </TD>
					<TD> 12:30 a 15:00 <BR> (lun. a vier.)  </TD>
					<TD rowspan='2'> C. Salud de Benavides de Órbigo<BR> C/ Magisterio Nacional s/n <BR> Tlf: 987 370 154 </TD>
				</TR>
				<TR class='fila'>
					        <TD><img src='../imagenes/cruz.gif' width="45" height="45" class='cruz'> 
                              Hospital de Órbigo </TD>
					<TD> 10:00 a 13:00 <BR> (lun. a vier.) </TD>
					<TD> 10:00 a 13:00 <BR> (lun. a vier.)  </TD>
				</TR>

<?php
		}
	else
		if($_GET['verpag']==2)
			{
?>
				<TR class='fila'>
					<TD><IMG src='../imagenes/cruz.gif' width="45" height="45" class='cruz'> 
                              Sahagún </TD>
					<TD> 9:00 a 15:00 <BR> (lun. - vier.) </TD>
					<TD> 9:00 a 15:00 <BR> (lun. - vier.) </TD>
					<TD> C. Salud de Sahagún <BR> C/ Costitución s/n <BR> Tlf: 947 781 291 </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG src='../imagenes/cruz.gif' width="45" height="45" class='cruz'> 
                              San Justo de la Vega </TD>
					<TD> 10:00 a 13:30 <BR> (lun. a vier.) </TD>
					<TD> 09:00 a 13:00 <BR> (lun. a vier.)  </TD>
					<TD rowspan='4'> C. Salud de Astorga<BR> C/ Alcalde Carro Verdejo 11 <BR> Tlf: 987 617 810 </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Astorga </TD>
					<TD> 09:00 a 15:00 <BR> (lun. a vier.) </TD>
					<TD> 09:00 a 15:00 <BR> (lun. a vier.)  </TD>
				</TR>
				<TR class='fila'>
					<TD> Castrillo de Polvazares </TD>
					<TD> 10:00 a 11:00 <BR> (lun. y vier.) </TD>
					<TD> 10:00 a 11:00 <BR> (lun. y vier.)  </TD>
				</TR>
				<TR class='fila'>
					<TD> Rabanal del Camino </TD>
					<TD> 10:00 a 11:00 <BR> (lun. a vier.) </TD>
					<TD> 12:30 a 13:30 <BR> (lun. a vier.)  </TD>
				</TR>
				
				<TR class='fila'>
					        <TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Camponaraya </TD>
					<TD> 09:00 a 14:00 (lun., mar., mièr. y vier.) <BR> 12:30 a 14:00 (juev.)</TD>
					<TD> 10:00 a 13:30 (lun., mar., mièr. y vier.) <BR> 11:30 a 13:30 (juev.)</TD>
					<TD rowspan='2'> C. Salud de Cacabelos<BR> C/ Doctor Santos Rubio 11 <BR> Tlf: 987 549 262 </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Cacabelos</TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.) </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.)  </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Villafranca </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.) </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.)  </TD>
					<TD rowspan='3'> C. Salud de Villafranca<BR> C/ Díaz Ovelar s/n <BR> Tlf: 987 542 510 </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Trabadelo</TD>
					<TD> 10:30 a 13:00 <BR> (lun. a vier.) </TD>
					<TD> 10:30 a 14:00 (lun., miér., juev. y vier.) <BR> 08:30 a 14:00 (mar.) </TD>
				</TR>
				<TR class='fila'>
					        <TD><IMG class='cruz' src='../imagenes/cruz.gif'> 
                              Vega de Valcarce </TD>
					<TD> 10:30 a 14:00 <BR> (lun. a vier.) </TD>
					<TD> 09:00 a 14:00 <BR> (lun. a vier.)  </TD>
				</TR>
<?php
			}
?>				

	</TABLE>
	</TD>
	<td width="80">&nbsp;</td>
</TR>
<tr>
<?php
	if(!isset($_GET['verpag']) || $_GET['verpag']==1)
		{
?>
    	<td>&nbsp;</td>
		<td align="center">
			<span style="width:10%;"></span>
			<span style="width:78%;align:center;">
				<img style="cursor:pointer;" height="30px" src="../imagenes/botones/atras.gif" onclick="location.href='info_pere_online.php';" title="Volver a Información de Albergue de Peregrinos" />
			</span>
			<span style="width:10%;">
				<img style="cursor:pointer;" align="right" src="../imagenes/botones/siguiente.gif" onclick="location.href='?verpag=2';" title="Siguiente" />
			</span>
		</td>
	    <td>&nbsp;</td>
<?php
		}
	else
		if($_GET['verpag']==2)
			{
?>
			<td>&nbsp;</td>
			<td align="center">
				<span style="width:10%;">
					<img style="cursor:pointer;" align="left" src="../imagenes/botones/anterior.gif" onclick="location.href='?verpag=1';" title="Anterior" />
				</span>
				<span style="width:78%;align:center;">
					<img style="cursor:pointer;" height="30px" src="../imagenes/botones/atras.gif" onclick="location.href='info_pere_online.php';" title="Volver a Información de Albergue de Peregrinos" />
				</span>
				<span style="width:10%;"></span>
			</td>
		    <td>&nbsp;</td>
<?php
			}
?>		    
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
