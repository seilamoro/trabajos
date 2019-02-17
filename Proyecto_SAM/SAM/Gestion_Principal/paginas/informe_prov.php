<?php
	session_start();
?>
<head>
<style>body{
	font-family: Arial, Helvetica, sans-serif;
}</style> 
<link type='text/css' rel='stylesheet' href='./css/estadisticas.css'>
</head>
<?php
	// Aqui se hace la conexion con la base de datos
	@ $db =	MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	@ mysql_select_db($_SESSION['conexion']['db']);
		
	//se crea un array con todas las provincias de este nuestro país
	$query_prov="Select Id_Pro, Nombre_Pro from provincia";
	@ $resul_prov=mysql_query($query_prov);
	for($i=0;$i< @ mysql_num_rows($resul_prov);$i++)
	{
		@ $fila=mysql_fetch_array($resul_prov);
		$prov[$fila['Id_Pro']]['nombre']=$fila['Nombre_Pro'];
		$prov[$fila['Id_Pro']]['M']=0;
		$prov[$fila['Id_Pro']]['F']=0;
	}
?> 
<table width="450px"  style="font-size:14px;" border="1" bordercolor="#000000">
	<tr> 
		<td>
				<table width="400px" align="center" border='10' bordercolor='white' cellspacing='0' cellpadding='0'>
				<tr> 
					<td colspan="6"><strong><font size="4">INFORME POR PROVINCIAS&nbsp;
					<?php
						switch ($_SESSION['tipo_cliente'])
						{
							case 2:
								echo "(Alberguistas)";
								break;
							case 3:
								echo "(Peregrinos)";
								break;
						}
					?>
					</font>
					</strong>
					</td>
				</tr>
				<tr>
					<td colspan="5"><strong><font size="4">
						<?php
							switch($_SESSION['periodicidad'])
							{
								case 1:
									date ('d-m-Y');
									break;
								case 2:
									switch($_SESSION['mes_inicio'])
									{
										case '01':
										echo "Mes de Enero " .$_SESSION['anio_inicio'];
										break;
										case '02':
										echo "Mes de Febrero " .$_SESSION['anio_inicio'];
										break;
										case '03':
										echo "Mes de Marzo " .$_SESSION['anio_inicio'];
										break;
										case '04':
										echo "Mes de Abril " .$_SESSION['anio_inicio'];
										break;
										case '05':
										echo "Mes de Mayo " .$_SESSION['anio_inicio'];
										break;
										case '06':
										echo "Mes de Junio " .$_SESSION['anio_inicio'];
										break;
										case '07':
										echo "Mes de Julio " .$_SESSION['anio_inicio'];
										break;
										case '08':
										echo "Mes de Agosto " .$_SESSION['anio_inicio'];
										break;
										case '09':
										echo "Mes de Septiembre " .$_SESSION['anio_inicio'];
										break;
										case '10':
										echo "Mes de Octubre " .$_SESSION['anio_inicio'];
										break;
										case '11':
										echo "Mes de Noviembre " .$_SESSION['anio_inicio'];
										break;
										case '12':
										echo "Mes de Diciembre " .$_SESSION['anio_inicio'];
										break;
									}
									break;
								case 3:
									switch($_SESSION['mes_inicio'])
									{
										case '01':
										echo "Enero - Marzo " .$_SESSION['anio_inicio'];
										break;
										case '02':
										echo "Febrero - Abril " .$_SESSION['anio_inicio'];
										break;
										case '03':
										echo "Marzo - Mayo " .$_SESSION['anio_inicio'];
										break;
										case '04':
										echo "Abril - Junio " .$_SESSION['anio_inicio'];
										break;
										case '05':
										echo "Mayo - Julio " .$_SESSION['anio_inicio'];
										break;
										case '06':
										echo "Junio - Agosto " .$_SESSION['anio_inicio'];
										break;
										case '07':
										echo "Julio - Septiembre " .$_SESSION['anio_inicio'];
										break;
										case '08':
										echo "Agosto - Octubre " .$_SESSION['anio_inicio'];
										break;
										case '09':
										echo "Septiembre - Noviembre " .$_SESSION['anio_inicio'];
										break;
										case '10':
										echo "Octubre - Diciembre " .$_SESSION['anio_inicio'];
										break;
										case '11':
										echo "Noviembre - Enero de ";
										echo $_SESSION['anio_inicio']+1;
										break;
										case '12':
										echo "Diciembre - Febrero de ";
										echo $_SESSION['anio_inicio']+1;
										break;
									}
									break;
								case 4:
									switch($_SESSION['mes_inicio'])
									{
										case '01':
										echo "Enero - Junio " .$_SESSION['anio_inicio'];
										break;
										case '02':
										echo "Febrero - Julio " .$_SESSION['anio_inicio'];
										break;
										case '03':
										echo "Marzo - Agosto " .$_SESSION['anio_inicio'];
										break;
										case '04':
										echo "Abril - Septiembre " .$_SESSION['anio_inicio'];
										break;
										case '05':
										echo "Mayo - Octubre " .$_SESSION['anio_inicio'];
										break;
										case '06':
										echo "Junio - Noviembre " .$_SESSION['anio_inicio'];
										break;
										case '07':
										echo "Julio - Diciembre " .$_SESSION['anio_inicio'];
										break;
										case '08':
										echo "Agosto - Enero ";
										echo $_SESSION['anio_inicio']+1;
										break;
										case '09':
										echo "Septiembre - Febrero ";
										echo $_SESSION['anio_inicio']+1;
										break;
										case '10':
										echo "Octubre - Marzo " ;
										echo $_SESSION['anio_inicio']+1;
										break;
										case '11':
										echo "Noviembre - Abril ";
										echo $_SESSION['anio_inicio']+1;
										break;
										case '12':
										echo "Diciembre - Mayo ";
										echo $_SESSION['anio_inicio']+1;
										break;
									}
									break;
								case 5:
									echo $_SESSION['anio_inicio'];
									break;
							}
						?>
					</font>
					</strong>
					</td>
				</tr>
				<tr> 
					<td width='40%' >&nbsp;</td>
					<td width="15%" align="center"><strong>H</strong></td>
					<td width="15%" align="center"><strong>M</strong></td>
					<td width="15%" align="center" ><strong>%</strong></td>
					<td width="15%" align="center"><strong>Total</strong></td>
				</tr>
				<?php
						if($_SESSION['periodicidad']==1)
							$fecha = date('Y-m-d');
						if($_SESSION['periodicidad']==2)
						{
							$mes = $_SESSION['mes_inicio'];
							$anio= $_SESSION['anio_inicio'];
						}
						if($_SESSION['periodicidad']==3)
						{
							$mes = $_SESSION['mes_inicio'];
							$anio= $_SESSION['anio_inicio'];
						}
						if($_SESSION['periodicidad']==4)
						{
							$mes = $_SESSION['mes_inicio'];
							$anio= $_SESSION['anio_inicio'];
						}
						if($_SESSION['periodicidad']==5)
						{
							$anio= $_SESSION['anio_inicio'];
						}
						
						switch ($_SESSION['periodicidad'])
						{
							case 1:
									if($_SESSION['tipo_cliente']==1) //todos los clientes y día actual
									{
										$query_cliente ="Select DNI_Cl from pernocta where fecha_llegada <='" .$fecha ."' AND fecha_salida > '" .$fecha ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
										//para peregrino y día actual
										$query_cliente ="Select DNI_Cl from pernocta_p where fecha_llegada <='" .$fecha ."' AND fecha_salida > '" .$fecha ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
										//para grupos y día actual
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres from estancia_gr where fecha_llegada <='" .$fecha ."' AND fecha_salida > '" .$fecha ."'";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
												$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];	
											}
										}
									}
									if($_SESSION['tipo_cliente']==2) //alberguistas (incluye grupos)y día actual
									{
										//para alberguistas
										$query_cliente ="Select DNI_Cl from pernocta where fecha_llegada <='" .$fecha ."' AND fecha_salida > '" .$fecha ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres from estancia_gr where fecha_llegada <='" .$fecha ."' AND fecha_salida > '" .$fecha ."'";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
												$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];	
											}
										}
									}
									if($_SESSION['tipo_cliente']==3) //peregrinos y día actual
									{
										//para peregrino
										$query_cliente ="Select DNI_Cl from pernocta_p where fecha_llegada <='" .$fecha ."' AND fecha_salida > '" .$fecha ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
									}
							break;
							case 2:
								if($_SESSION['tipo_cliente']==1) //todos los clientes y mensual
									{
										$mes_ini=$_SESSION['mes_inicio'] ."";
										$query_cliente ="Select DNI_Cl,Fecha_Salida from pernocta where SUBSTRING(fecha_llegada,1,7) <='" .$_SESSION['anio_inicio'] ."-" .$mes_ini ."' AND SUBSTRING(fecha_salida,1,7) >= '" .$_SESSION['anio_inicio'] ."-" .$mes_ini ."'" ;
										//echo "<br>" .$query_cliente;
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											//echo $query_prov;
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && (SUBSTR($fila_cliente['Fecha_Salida'],5,2) == $_SESSION['mes_inicio']) )
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
													{
														$prov[$fila2['Id_Pro']]['M']++;
													}
													if($fila2['Sexo_Cl']=="F")
													{
														$prov[$fila2['Id_Pro']]['F']++;
													}
												}
											}
										}
										//echo SUBSTR($fila_cliente['Fecha_Salida'],8,2);
										
										//para peregrino
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta_p where SUBSTRING(fecha_llegada,6,2) <='" .$_SESSION['mes_inicio'] ."' AND SUBSTRING(fecha_salida,6,2) >= '" .$_SESSION['mes_inicio'] ."' AND (SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."')";
										//echo "<br>" .$query_cliente;
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && (SUBSTR($fila_cliente['Fecha_Salida'],5,2) == $_SESSION['mes_inicio']) )
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres, Fecha_Salida from estancia_gr where SUBSTRING(fecha_llegada,6,2) <='" .$_SESSION['mes_inicio'] ."' AND SUBSTRING(fecha_salida,6,2) >= '" .$_SESSION['mes_inicio'] ."' AND (SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."')";
										//echo "query grupo: " .$query_grupo;
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												if( (SUBSTR($fila['Fecha_Salida'],8,2)=='01') && (SUBSTR($fila['Fecha_Salida'],5,2) == $_SESSION['mes_inicio']) )
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
													$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];
												}
											}
										}
									}
									if($_SESSION['tipo_cliente']==2) //alberguistas (incluye grupos)y mensual
									{
										//para alberguistas
										$query_cliente ="Select DNI_Cl from pernocta where SUBSTRING(fecha_llegada,6,2) <='" .$_SESSION['mes_inicio'] ."' AND SUBSTRING(fecha_salida,6,2) >= '" .$_SESSION['mes_inicio'] ."' AND (SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."')";
										//echo "<br>" .$query_cliente;
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											$fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && (SUBSTR($fila_cliente['Fecha_Salida'],5,2) == $_SESSION['mes_inicio']) )
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres from estancia_gr where SUBSTRING(fecha_llegada,6,2) <='" .$_SESSION['mes_inicio'] ."' AND SUBSTRING(fecha_salida,6,2) >= '" .$_SESSION['mes_inicio'] ."' AND (SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."')";
										//echo "<br>" .$query_grupo;
										$resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											//echo "EEE" .$query2;
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												if( (SUBSTR($fila['Fecha_Salida'],8,2)=='01') && (SUBSTR($fila['Fecha_Salida'],5,2) == $_SESSION['mes_inicio']) )
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
													$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];	
												}
											}
										}
									}
									if($_SESSION['tipo_cliente']==3) //peregrinos y mensual
									{
										//para peregrino
										$query_cliente ="Select DNI_Cl from pernocta_p where SUBSTRING(fecha_llegada,6,2) <='" .$_SESSION['mes_inicio'] ."' AND SUBSTRING(fecha_salida,6,2) >= '" .$_SESSION['mes_inicio'] ."' AND (SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."')";
										//echo "<BR>peregrinos: " .$query_cliente;
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && (SUBSTR($fila_cliente['Fecha_Salida'],5,2) == $_SESSION['mes_inicio']) )
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
									}
							break;

							case 5:
									if($_SESSION['tipo_cliente']==1) //todos los clientes y anual
									{
										$query_cliente ="Select DNI_Cl from pernocta where SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
										//para peregrino
										$query_cliente ="Select DNI_Cl from pernocta_p where SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres from estancia_gr where SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."'";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
												$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];	
											}
										}
									}
									if($_SESSION['tipo_cliente']==2) //alberguistas (incluye grupos) y anual
									{
										//para alberguistas
										$query_cliente ="Select DNI_Cl from pernocta where SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres from estancia_gr where SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."'";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
												$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];	
											}
										}
									}
									if($_SESSION['tipo_cliente']==3) //peregrinos y anual
									{
										//para peregrino
										$query_cliente ="Select DNI_Cl from pernocta_p where SUBSTRING(fecha_llegada,1,4) ='" .$_SESSION['anio_inicio'] ."' OR SUBSTRING(fecha_salida,1,4) = '" .$_SESSION['anio_inicio'] ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if($fila2['Sexo_Cl']=="M")
													$prov[$fila2['Id_Pro']]['M']++;
												if($fila2['Sexo_Cl']=="F")
													$prov[$fila2['Id_Pro']]['F']++;
											}
										}
									}
							break;

							case 3:
									$mes2=$_SESSION['mes_inicio']+1;
										if($mes2 < 10)
											$mes2 = "0" .$mes2;
										$mes3=$_SESSION['mes_inicio']+2;
										if($mes3 < 10)
											$mes3 = "0" .$mes3;
									//echo "EE:" .$_SESSION['mes_inicio'] ."<br>";
									//echo $mes2 ."<br>";
									//echo $mes3 ."<br>";
									if($_SESSION['tipo_cliente']==1) //todos los clientes y trimestral
									{
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."'";
										//echo $query_cliente ."<br>";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio']) ))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
											//echo "ddd<BR>" .SUBSTR($fila_cliente['Fecha_Salida'],8,2);
											//echo "ddd<BR>" .SUBSTR($fila_cliente['Fecha_Salida'],8,2);
										}
										//para peregrino
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta_p where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."'";
										//echo $query_cliente ."<br>";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres, Fecha_Salida from estancia_gr where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."'";
										//echo $query_grupo ."<br>";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												if( (SUBSTR($fila['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])) )
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
													$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];
												}
											}
										}
									}
									if($_SESSION['tipo_cliente']==2) //alberguistas (incluye grupos)y trimestral
									{
										//para alberguistas
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres, Fecha_Salida from estancia_gr where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."'";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@$fila2=mysql_fetch_array($resul2);
												if( (SUBSTR($fila['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
													$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];
												}
											}
										}
									}
									if($_SESSION['tipo_cliente']==3) //peregrinos y trimestral
									{
										//para peregrino
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta_p where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
									}
							break;

							case 4:
									$mes2=$_SESSION['mes_inicio']+1;
									if($mes2 < 10)
										$mes2 = "0" .$mes2;
									$mes3=$_SESSION['mes_inicio']+2;
									if($mes3 < 10)
										$mes3 = "0" .$mes3;
									$mes4=$_SESSION['mes_inicio']+3;
									if($mes4 < 10)
										$mes4 = "0" .$mes4;
									$mes5=$_SESSION['mes_inicio']+4;
									if($mes4 < 10)
										$mes4 = "0" .$mes4;
									$mes6=$_SESSION['mes_inicio']+5;
									if($mes6 < 10)
										$mes6 = "0" .$mes6;
									if($_SESSION['tipo_cliente']==1) //todos los clientes y semestral
									{
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_llegada,6,2) = '".$mes6 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes6 ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
										//para peregrino
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta_p where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_llegada,6,2) = '".$mes6 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes6 ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres, Fecha_Salida from estancia_gr where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_llegada,6,2) = '".$mes6 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes6 ."'";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												if( (SUBSTR($fila['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
													$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];	
												}
											}
										}
									}
									if($_SESSION['tipo_cliente']==2) //alberguistas (incluye grupos)y semestral
									{
										//para alberguistas
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_llegada,6,2) = '".$mes6 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes6 ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
										//para grupos
										$query_grupo ="Select Nombre_Gr, Num_Mujeres, Num_Hombres, Fecha_Salida from estancia_gr where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_llegada,6,2) = '".$mes6 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes6 ."'";
										@ $resul_grupo=mysql_query($query_grupo);
										for($i=0;$i< @ mysql_num_rows($resul_grupo);$i++)
										{
											@ $fila=mysql_fetch_array($resul_grupo);
											$query2="Select Id_Pro from grupo where Nombre_Gr ='" .$fila['Nombre_Gr'] ."'";
											@ $resul2=mysql_query($query2);
											for($j=0;$j< @ mysql_num_rows($resul2);$j++)
											{
												@ $fila2=mysql_fetch_array($resul2);
												if( (SUBSTR($fila['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													$prov[$fila2['Id_Pro']]['F'] += $fila['Num_Mujeres'];	
													$prov[$fila2['Id_Pro']]['M'] += $fila['Num_Hombres'];	
												}
											}
										}
									}
									if($_SESSION['tipo_cliente']==3) //peregrinos y semestral
									{
										//para peregrino
										$query_cliente ="Select DNI_Cl, Fecha_Salida from pernocta_p where SUBSTRING(fecha_llegada,6,2) ='" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_llegada,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_llegada,6,2) = '".$mes6 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$_SESSION['mes_inicio'] ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes2 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes3 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes4 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes5 ."' OR SUBSTRING(fecha_salida,6,2) = '" .$mes6 ."'";
										@ $resul_cliente=mysql_query($query_cliente);
										for($i=0;$i< @ mysql_num_rows($resul_cliente);$i++)
										{
											@ $fila_cliente=mysql_fetch_array($resul_cliente);
											$query_prov="Select Id_Pro,Sexo_Cl from cliente where DNI_Cl = '" .$fila_cliente['DNI_Cl'] ."'";
											@ $resul_prov=mysql_query($query_prov);
											for($j=0;$j< @ mysql_num_rows($resul_prov);$j++)
											{
												@ $fila2=mysql_fetch_array($resul_prov);
												if( (SUBSTR($fila_cliente['Fecha_Salida'],8,2)=='01') && ( (SUBSTR($fila_cliente['Fecha_Salida'],5,2)==$_SESSION['mes_inicio'])))
												{
													//en este caso no sumo porque se trata de un cliente que se fue el día 1 del mes para el que stamos calculando el informe, por tanto el cliente no debe contabilizarse en el informe de dicho mes
												}
												else
												{
													if($fila2['Sexo_Cl']=="M")
														$prov[$fila2['Id_Pro']]['M']++;
													if($fila2['Sexo_Cl']=="F")
														$prov[$fila2['Id_Pro']]['F']++;
												}
											}
										}
									}
							break;


			
			
						}
						$total_hombres=0;
						$total_mujeres=0;
						foreach($prov as $key => $value)
						{
							$total_hombres +=$prov[$key]['M'];
							$total_mujeres +=$prov[$key]['F'];
						}
						$total=$total_mujeres + $total_hombres;
						foreach($prov as $key => $value)
						{ 	
								if( ($prov[$key]['M'] != 0) || ($prov[$key]['F'] != 0) )
								{
									$total_prov=$prov[$key]['M'] + $prov[$key]['F'];
									$porcentaje_prov=($total_prov / $total) * 100;
									//limitar el porcentaje a dos decimales
									echo "
									<tr>
									<td class='rb' align='left' width='28%'><strong>" .$prov[$key]['nombre']  ."</strong></td>
									<td class='rb' align='center' width='18%'>" .$prov[$key]['M']  ."</td>
									<td class='rb' align='center' width='18%'>" .$prov[$key]['F'] ."</td>
									<td class='rb' align='center' width='18%'>" .ROUND($porcentaje_prov,1) ."</td>
									<td class='rb' align='center' width='18%'>" .$total_prov ."</td>
									</tr>";
								}
						}
						?>
						<tr style='font-weight:bold;'>
							<td width="28%" align="left" class='rt'>TOTALES</td>
							<td width="18%" align="center" class='rt'><?php echo $total_hombres; ?></td>
							<td width="18%" align="center" class='rt'><?php echo $total_mujeres; ?></td>
							<td width="18%" align="center" class='rt'>100</td>
							<td width="18%" align="center" class='rt'><?php echo $total;  ?></td>
						</tr>
			</table>			
</table>
<?php mysql_close($db); ?>
</body>