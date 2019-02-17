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
	<link rel="stylesheet" type="text/css" href="../css/estilo_online.css">	
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
					<b><font face="Arial, Helvetica, sans-serif" size="5" color="#C40056">Tarifas <?php echo date("Y"); //PONE EL AÑO EN EL QUE SE UTILIZO ESTAS TARIFAS?></font></b>
					<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->

<table border="0">
 
  <tr>
    <td >&nbsp;</td>
    <td valign="top">

                <?php
								
										/*PARA SACAR EL NOMBRE POR EL ID DE HABITACIÓN*/
											$tip_habit="Select * from Tipo_habitacion;";
											$comp=mysql_query($tip_habit);
											@$cont=mysql_num_rows($comp);
												for($x=0;$x<$cont;$x++)
													{
														$otra=mysql_fetch_array($comp);
														$id_tipo_hab=$otra['Id_Tipo_Hab'];
														$tipo_hab[$id_tipo_hab]=$otra['Nombre_Tipo_Hab'];
													}
											/*PARA SACAR EL NOMBRE POR EL ID DE PERSONA*/
											$tip_pers="Select *from Tipo_Persona;";
											$componer=mysql_query($tip_pers);
											@$cuenta=mysql_num_rows($componer);
												for($d=0;$d<$cuenta;$d++)
													{
														$nuevo=mysql_fetch_array($componer);
														$id_tipo_person=$nuevo['Id_Tipo_Persona'];
														$tipo_persona[$id_tipo_person]=$nuevo['Nombre_Tipo_Persona'];
													}
											/*PARA SACAR EL NOMBRE POR EL ID DE EDAD*/
											$tip_edad='Select * from edad';
											$comprobar=mysql_query($tip_edad);
											@$con=mysql_num_rows($comprobar);
												for($r=0;$r<$con;$r++)
													{
														$year=mysql_fetch_array($comprobar);
														$id_eda=$year['Id_Edad'];
														$tipo_edad[$id_eda]=$year['Nombre_Edad'];
													}
											/*AKI AKABA LO DE SACAR LOS NOMBRES POR EL ID*/

								?>
              <table border='0' width='100%'>
                <tr class=fondo_celda_tarifa> 
                    <th>Edades</th>
                	<?php

                                    // obtenemos los tipos de persona
                                    $query="SELECT Id_Tipo_Persona,Nombre_Tipo_Persona FROM tipo_persona ORDER BY Id_Tipo_Persona ASC";
                                    @$res = mysql_query($query);
									@$num_tipo_persona = mysql_num_rows($res);

				
                                    for($i=0;$i<$num_tipo_persona;$i++)
										{
                                            $fila=mysql_fetch_array($res);
                                            echo "<th>".$fila['Nombre_Tipo_Persona']."</th>";
											$cont_tipo_per[$i]=$fila['Id_Tipo_Persona'];
                                        }
									
                                    // obtenemos las habitaciones que sean Reservables pero no compartidas, como la triple
                                    $query="SELECT Nombre_Tipo_Hab FROM tipo_habitacion WHERE Reservable='S' AND Compartida='N';";
                                    @$res = mysql_query($query);
									@$num = mysql_num_rows($res);

                                    for($i=0;$i<$num;$i++)
										{
                                            $fila=mysql_fetch_array($res);
                                            echo "<th>".$fila['Nombre_Tipo_Hab']."</th>";
                                        }
				   ?>
				</tr>
                <?php												
				                    $query="SELECT Nombre_Edad,Id_Edad from edad";
                                    @$res_edad = mysql_query($query);
									@$num_edad = mysql_num_rows($res_edad);
									for($i=0;$i<$num_edad;$i++)
											{
                                            echo "<tr >";
											echo "<td class=fondo_celda_tarifa>";
											$fila_edad=mysql_fetch_array($res_edad);
											echo $fila_edad['Nombre_Edad']."</td>";
											for($j=0;$j<$num_tipo_persona;$j++){	
											$query="SELECT DISTINCT Tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona
                                                    FROM tarifa
                                                   INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                         AND NOT (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
                                                    WHERE Id_Edad=".$fila_edad['Id_Edad']." and tarifa.Id_Tipo_Persona=".$cont_tipo_per[$j]." ORDER BY tarifa.Id_Tipo_Persona ASC";

                                            @$res = mysql_query($query);
									        @$num = mysql_num_rows($res);
											$fila=mysql_fetch_array($res);
                                               if ($num==0){
												   $precio='0';
											   }
                                               else{
												   $precio = $fila['Tarifa'];
											   }
											 
												echo "<td bgcolor='#F4EFB' align='center'>".$precio."</td>";											  
											 
											  
                                            }
									        //echo "<tr>";
										   
                                            $query="SELECT DISTINCT Tarifa,tarifa.Id_Edad
                                                    FROM tarifa
                                                    INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                          AND (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
                                                    WHERE Id_Edad=".$fila_edad['Id_Edad'].";";
                                            @$res = mysql_query($query);
									        @$num = mysql_num_rows($res);



											 for($j=0;$j<@$num;$j++)
											{	
												$fila=mysql_fetch_array($res);
												if ($num==0) $precio='0';
												else $precio = $fila['Tarifa'];
												 echo "<td bgcolor='#F4EFB' align='center' >".$precio."</td>";
											}
											if(@$num==0){
												$precio='0';
												echo "<td bgcolor='#F4EFB' align='center' id='texto_listados'>".$precio."</td>";
											}
											echo "</tr>";									
           								}
				?>
              </table>
     
	</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>
	<br>
		<TABLE class='letra_asterisco' align='left' border="0" width="100%">
		
		<TR>
			<TD>* <FONT size="3"> <b>Grupo</b></FONT> &nbsp;&nbsp;<b><FONT color="#00000" size="3">Mínimo diez personas.</TD>
		</TR>
		<TR>
			<TD>* <FONT size="3"><b>Triple</b></FONT> &nbsp;&nbsp;<b><FONT color="#00000" size="3">Habitaciones individuales de dos o tres camas.<b></FONT></TD>
		</TR>
		<TR>
			
			<TD align='center'><br><a href='#' onclick='window.open("../paginas/imprimir_tarifa_online.php", "Tarifas",
				"menubar=no,location=no,resizable=yes,scrollbars=yes,status=no");' ><img src='../imagenes/botones/imprimir.gif'  border=0 height='43px'></img></a></TD>
		</TR>
		</TABLE>
	</td>
	<td>&nbsp;</td></tr>
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
