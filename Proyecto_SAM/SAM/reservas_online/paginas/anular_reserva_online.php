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
					<B><FONT color="#C40056" size="5">Anulaci�n de Reservas On-Line</FONT></B>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->
<table width="100%" height="100%" border="0"><tr valign="top"><td>
<table border="0" width="90%" align="center">
  <tr>
    <td>&nbsp;</td>
    <td valign="top"><br>
    <FORM name="form1" id="form1" action="confirmar_anulacion_reserva_online.php" method="POST">
    <TABLE border="0" align="center" width="100%" cellspacing="0" cellpadding="0">

        <TR height="40">
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba1_a.gif"></TD>
            <TD>
                <TABLE height="100%" width="100%" cellspacing="0" cellpadding="0"><TR>
                    <TD width="315">
                        <FONT color="#3366FF" size="4"><B>&nbsp;<font color="#F79239">Formulario de Anulaci�n de Reserva<B></font></FONT>
                    </TD>
                    <TD background="../imagenes/img_cuadro_a/cuadro_L_arr_a.gif">&nbsp;</TD>
                </TR>
                </TABLE>
            </TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_arriba2_a.gif"></TD>
        </TR>
       
        <TR>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_izq_a.gif">&nbsp;</TD>
            <TD>
                <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
                <TR><TD>
                    <font face="Arial, Helvetica, sans-serif" size="3">Introduzca el Localizador de la Reserva y el D.N.I. de la persona que hizo la reserva que desea anular.</font>
                </TD></TR>
                <TABLE border="0" align="center" width="90%" cellspacing="0" cellpadding="0">
                    <TR height="40">
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_arriba1.gif"></TD>
                        <TD>
                            <TABLE height="100%" width="100%" cellspacing="0" cellpadding="0"><TR>
                                <TD width="155"><B>&nbsp;Datos de la Reserva<B></TD>
                                <TD background="../imagenes/img_cuadro/cuadro_L_arr.gif">&nbsp;</TD>
                            </TR></TABLE>
                        </TD>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_arriba2.gif"></TD>
                    </TR>
                    <TR>
                    <TD width="20" background="../imagenes/img_cuadro/cuadro_L_izq.gif">&nbsp;</TD>
                    <TD>
                        <TABLE width="100%">
                            <TR>
                                <TD width="240" align="right">Localizador de la Reserva:</TD>
                                <TD><INPUT type="text" size="30" name="localizador"></TD>
                            </TR>
							<TR>
                                <TD width="240" align="right">D.N.I.:</TD>
                                <TD><INPUT type="text" size="30" name="dni"></TD>
                            </TR>
							<TR>
                                <TD width="50%" align="center" height="50"><a href="javascript:document.getElementById('form1').submit()"><img src='../imagenes/botones/buscar.gif'  border=0 height="43"/></a></TD>
                                <TD align="center"><a href="javascript:document.getElementById('form1').reset()"><img src='../imagenes/botones/limpiar.gif' border=0 height='43'/></a></TD>
                            </TR>
                        </TABLE>
                    </TD>
                    <TD width="20" background="../imagenes/img_cuadro/cuadro_L_der.gif">&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_abajo1.gif"></TD>
                        <TD background="../imagenes/img_cuadro/cuadro_L_aba.gif">&nbsp;</TD>
                        <TD width="20"><IMG src="../imagenes/img_cuadro/cuadro_abajo2.gif"></TD>
                    </TR>
                </TABLE>
            <TD width="20" background="../imagenes/img_cuadro_a/cuadro_L_der_a.gif">&nbsp;</TD>
        </TR>
        <TR>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo1_a.gif"></TD>
            <TD background="../imagenes/img_cuadro_a/cuadro_L_aba_a.gif">&nbsp;</TD>
            <TD width="20"><IMG src="../imagenes/img_cuadro_a/cuadro_abajo2_a.gif"></TD>
        </TR>
    </TABLE>
    </FORM>
    

</td>
    <td>&nbsp;</td>
  </tr>

</table><br><br>
</td></tr></table>
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
