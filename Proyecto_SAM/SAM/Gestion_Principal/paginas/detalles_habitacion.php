<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
		MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		$sql = "SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= LAST_DAY('".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-01') GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab WHERE consulta2.Id_Hab LIKE '".$_SESSION['gdh']['dis_hab']['hab_seleccionada']."';";
		//echo $sql;
		$result = MYSQL_QUERY($sql);
		$fila = MYSQL_FETCH_ARRAY($result);
?>		

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_detalles_ancho']; ?>;">
				Detalles Habitación
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="center" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<table border="0" width="100%" height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_detalles_alto']; ?>" style="border: 1px solid #3F7BCC;">
				<tr>
					<td align="left">
						<table>
							<tr>
								<td class="texto_detalles" align="left">
									Nombre:
								</td>
								<td align="left">
									<input type="text" size=3 contenteditable=false class="input_text" value="<?PHP echo $fila['Id_Hab']; ?>">
								</td>
								<td class="texto_detalles" align="left" colspan="3">
									Detalles de
								</td>
								<td align="left">
									<input type="text" size=20 contenteditable=false class="input_text" value="<?PHP echo $_SESSION['gdh']['meses'][$_SESSION['gdh']['mes'] - 1].' de '.$_SESSION['gdh']['anio']; ?>">
								</td>
							</tr>
						</table>
					</td>				
				</tr>
				<tr>
					<td align="left">
						<table>
							<tr>
								<td class="texto_detalles" align="left" colspan="3">
									Reservas:
								</td>
								<td align="left">
<?PHP

		$sql_res = "SELECT * FROM detalles INNER JOIN reserva ON detalles.DNI_PRA = reserva.DNI_PRA AND detalles.Fecha_Llegada = reserva.Fecha_Llegada WHERE detalles.Fecha_Llegada >= '".DATE("Y-m-d")."' AND reserva.Id_Hab LIKE '".$_SESSION['gdh']['dis_hab']['hab_seleccionada']."' ORDER BY detalles.Fecha_Llegada;";
		//echo $sql_res;
		$result_res = MYSQL_QUERY($sql_res);
		
		if (MYSQL_NUM_ROWS($result_res) > 0) {
			$fila_res = MYSQL_FETCH_ARRAY($result_res);
			$_SESSION['gdh']['habitaciones'][''.$_SESSION['gdh']['dis_hab']['hab_seleccionada']] = true;  // Utilizo esta variable para indicar que la habitacion tiene estancias y que no se puede cambiar de tipo de habitación.
?>
									<input type="text" size=20 contenteditable=false class="input_text" style="color:red;" value="SI, el <?PHP echo fecha_ordenada($fila_res['Fecha_Llegada']); ?>">
<?PHP
		}
		else {
?>
									<input type="text" size=20 contenteditable=false class="input_text" value="NO">
<?PHP
		}

?>
								</td>
							</tr>
						</table>
					</td>				
				</tr>
					<td>
						<div class="div_tabla_habitacion_mes">
							<table border="0" cellpadding="0" cellspacing="1">
<?PHP

		@ $db = MYSQL_PCONNECT("localhost","root","");
	
		if (!$db) {	  
?>
	<tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></tr>
<?PHP
			exit();
		}
		else {
		  	MYSQL_SELECT_DB("SAM");
			  
			$mes_habitacion = $_SESSION['gdh']['mes'];
			$anio_habitacion = $_SESSION['gdh']['anio'];
			
			$fecha = MKTIME(0, 0, 0, $_SESSION['gdh']['mes'] + 1, 0, $_SESSION['gdh']['anio']);
			$ultimodia = STRFTIME("%d",$fecha);
			
			if (isset($habitacion)) {
			  	unset($habitacion);
			}
		
		  	if ($fila['Camas_Hab'] >= 0) {
				$habitacion['Id_Hab'] = $fila['Id_Hab'];
				$habitacion['Id_Tipo_Hab'] = $fila['Id_Tipo_Hab'];
		  	  	$habitacion['Nombre_Id_Tipo_Hab'] = $fila['Nombre_Tipo_Hab'];
				$habitacion['Camas_Hab'] = $fila['Camas_Hab'];
				if ($fila['Camas_Hab'] > $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']) {
					$resto = $fila['Camas_Hab'] % $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];
				  	$habitacion['Columnas_Necesarias'] = INTVAL($fila['Camas_Hab'] / $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']);
				  	if ($resto > 0) {
					    $habitacion['Columnas_Necesarias']++;
					}
				}
				else {
				  	$habitacion['Columnas_Necesarias'] = 1;
				}
			}
?>
								<tr align="center" valign="middle">
<?PHP
			for ($i = 1; $i <= $ultimodia; $i++) {
?>	  
									<td class="nom_hab_mini" colspan="<?PHP echo $habitacion['Columnas_Necesarias'];?>" style="cursor:pointer;" title="Mostrar <?PHP if ($i < 10) {echo '0'.$i;}else{echo $i;}; echo '-'; if ($_SESSION['gdh']['mes'] < 10) {echo '0'.$_SESSION['gdh']['mes'];}else{echo $_SESSION['gdh']['mes'];}; echo '-'.$_SESSION['gdh']['anio']; ?>" onclick="cambiar_dia_detalles(<?PHP echo $i; ?>,<?PHP echo $_SESSION['gdh']['mes']; ?>,<?PHP echo $_SESSION['gdh']['anio']; ?>,'<?PHP echo $_SESSION['gdh']['dis_hab']['hab_seleccionada']; ?>');"><?PHP if ($i < 10) {echo '0'.$i;} else {echo $i;}; ?></td>
<?PHP
			}
?>
	  							</tr>
	  							<tr>
<?PHP
			for ($i = 1; $i <= $ultimodia; $i++) {
				$sql_tipo_hab = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$i."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab WHERE consulta2.Id_Hab LIKE '".$_SESSION['gdh']['dis_hab']['hab_seleccionada']."') AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab;";
				//echo $sql_tipo_hab;
				$result_tipo_hab = MYSQL_QUERY($sql_tipo_hab);
				$fila_tipo_hab = MYSQL_FETCH_ARRAY($result_tipo_hab);
				if (SUBSTR($fila_tipo_hab['Nombre_Tipo_Hab'],0,1) != '') {
?>	  
									<td class="nom_hab_mini" title="<?PHP echo $fila_tipo_hab['Nombre_Tipo_Hab'].' desde el '.fecha_ordenada($fila_tipo_hab['Fecha']); ?>" colspan="<?PHP echo $habitacion['Columnas_Necesarias'];?>">
<?PHP
					echo SUBSTR($fila_tipo_hab['Nombre_Tipo_Hab'],0,1);
?>
									</td>
<?PHP
				}
				else {
?>	  
									<td class="nom_hab_mini" title="<?PHP echo 'No hay datos de la habitacion para este día.'; ?>" colspan="<?PHP echo $habitacion['Columnas_Necesarias'];?>">
<?PHP
					echo SUBSTR($fila_tipo_hab['Nombre_Tipo_Hab'],0,1);
?>
									</td>
<?PHP
				}
			}	
?>
								</tr>
<?PHP
			for ($i = 1; $i <= $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']; $i++) {
?>											
								<tr>
<?PHP
				for ($j = 0; $j < $ultimodia; $j++) {
					
					if (isset($pernocta)) {
					  	unset($pernocta);
					}
					
					$pernocta = array();
					
					// Buscar pernoctas de alberguistas
					$sql = "SELECT * FROM cliente INNER JOIN (SELECT DNI_Cl, Id_Hab, Fecha_Llegada FROM pernocta WHERE Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' AND Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' ORDER BY Fecha_Llegada) AS consulta ON consulta.DNI_Cl=cliente.DNI_Cl;";
					$result = MYSQL_QUERY($sql);
					for ($i2 = 0; $i2 < MYSQL_NUM_ROWS($result); $i2++) {
					  	$fila = MYSQL_FETCH_ARRAY($result);
					  	if (!isset($pernocta[$fila['Id_Hab']])) {
						    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = 1;
						    //echo '<tr><td>'.$fila['Id_Hab'].'-'.$fila['DNI_Cl'].'</td></tr>';
						    $pernocta[$fila['Id_Hab']][1]['Clave'] = $fila['DNI_Cl'];
						    $pernocta[$fila['Id_Hab']][1]['Title'] = 'Alberguista: '.$fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
						    $pernocta[$fila['Id_Hab']][1]['Id_Hab'] = $fila['Id_Hab'];
						    $pernocta[$fila['Id_Hab']][1]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
						    $pernocta[$fila['Id_Hab']][1]['Tipo_Cliente'] = "A";
						}
						else {
						  	$pernocta[$fila['Id_Hab']]['n_pernoctas']++;
						    //echo '<tr><td>'.$fila['Id_Hab'].'-'.$fila['DNI_Cl'].'</td></tr>';
						  	$aux = $pernocta[$fila['Id_Hab']]['n_pernoctas'];
						    $pernocta[$fila['Id_Hab']][$aux]['Clave'] = $fila['DNI_Cl'];
						    $pernocta[$fila['Id_Hab']][$aux]['Title'] = 'Alberguista: '.$fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
						    $pernocta[$fila['Id_Hab']][$aux]['Id_Hab'] = $fila['Id_Hab'];
						    $pernocta[$fila['Id_Hab']][$aux]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
						    $pernocta[$fila['Id_Hab']][$aux]['Tipo_Cliente'] = "A";
						}
					}
					
					// Buscar pernoctas de peregrinos
					$sql = "SELECT * FROM cliente INNER JOIN (SELECT DNI_Cl, Id_Hab, Fecha_Llegada FROM pernocta_p WHERE Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' AND Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' ORDER BY Fecha_Llegada) AS consulta ON consulta.DNI_Cl=cliente.DNI_Cl;";
					$result = MYSQL_QUERY($sql);
					
					for ($i2 = 0; $i2 < MYSQL_NUM_ROWS($result); $i2++) {
					  	$fila = MYSQL_FETCH_ARRAY($result);
					  	if (!isset($pernocta[$fila['Id_Hab']])) {
						    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = 1;
						    $pernocta[$fila['Id_Hab']][1]['Clave'] = $fila['DNI_Cl'];
						    $pernocta[$fila['Id_Hab']][1]['Title'] = 'Peregrino: '.$fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
						    $pernocta[$fila['Id_Hab']][1]['Id_Hab'] = $fila['Id_Hab'];
						    $pernocta[$fila['Id_Hab']][1]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
						    $pernocta[$fila['Id_Hab']][1]['Tipo_Cliente'] = "P";
						}
						else {
						  	$pernocta[$fila['Id_Hab']]['n_pernoctas']++;
						  	$aux = $pernocta[$fila['Id_Hab']]['n_pernoctas'];
						    $pernocta[$fila['Id_Hab']][$aux]['Clave'] = $fila['DNI_Cl'];
						    $pernocta[$fila['Id_Hab']][$aux]['Title'] = 'Peregrino: '.$fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
						    $pernocta[$fila['Id_Hab']][$aux]['Id_Hab'] = $fila['Id_Hab'];
						    $pernocta[$fila['Id_Hab']][$aux]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
						    $pernocta[$fila['Id_Hab']][$aux]['Tipo_Cliente'] = "P";
						}
					}
					
					// Buscar pernoctas de grupos
					$sql = "SELECT * FROM colores INNER JOIN (SELECT pernocta_gr.Nombre_Gr,pernocta_gr.Id_Hab,pernocta_gr.Fecha_Llegada,pernocta_gr.Num_Personas AS Num_Personas,estancia_gr.Fecha_Salida,estancia_gr.Id_Color FROM pernocta_gr LEFT JOIN estancia_gr ON pernocta_gr.Fecha_Llegada=estancia_gr.Fecha_Llegada AND pernocta_gr.Nombre_Gr=estancia_gr.Nombre_Gr WHERE estancia_gr.Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' AND estancia_gr.Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' ORDER BY estancia_gr.Fecha_Llegada) AS consulta ON consulta.Id_Color=colores.Id_Color;";
					$result = MYSQL_QUERY($sql);
					
					for ($i2 = 0; $i2 < MYSQL_NUM_ROWS($result); $i2++) {
					  	$fila = MYSQL_FETCH_ARRAY($result);
					  	if (!isset($pernocta[$fila['Id_Hab']])) {
					  	  	for ($k2 = 1; $k2 <= $fila['Num_Personas']['n_pernoctas']; $k2++) {
							    $pernocta[$fila['Id_Hab']][$k2]['Clave'] = $fila['Nombre_Gr'];
							    $pernocta[$fila['Id_Hab']][$k2]['Title'] = $fila['Nombre_Gr'];
							    $pernocta[$fila['Id_Hab']][$k2]['Id_Hab'] = $fila['Id_Hab'];
							    $pernocta[$fila['Id_Hab']][$k2]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
							    $pernocta[$fila['Id_Hab']][$k2]['Tipo_Cliente'] = "G";
							    $pernocta[$fila['Id_Hab']][$k2]['Color'] = $fila['Color'];
							}
						    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = $pernocta[$fila['Id_Hab']]['n_pernoctas'] + $fila['Num_Personas'];
						}
						else {
						  	for ($k2 = ($pernocta[$fila['Id_Hab']]['n_pernoctas'] + 1); $k2 <= ($pernocta[$fila['Id_Hab']]['n_pernoctas'] + $fila['Num_Personas']); $k2++) {
							    $pernocta[$fila['Id_Hab']][$k2]['Clave'] = $fila['Nombre_Gr'];
							    $pernocta[$fila['Id_Hab']][$k2]['Title'] = $fila['Nombre_Gr'];
							    $pernocta[$fila['Id_Hab']][$k2]['Id_Hab'] = $fila['Id_Hab'];
							    $pernocta[$fila['Id_Hab']][$k2]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
							    $pernocta[$fila['Id_Hab']][$k2]['Tipo_Cliente'] = "G";
							    $pernocta[$fila['Id_Hab']][$k2]['Color'] = $fila['Color'];
							}
						    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = $pernocta[$fila['Id_Hab']]['n_pernoctas'] + $fila['Num_Personas'];
						}
					}
					
					// Buscar reservas
					$sql = "SELECT DNI_PRA, Fecha_Llegada FROM detalles WHERE Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' AND Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".($j + 1)."' ORDER BY Fecha_Llegada;";
					$result = MYSQL_QUERY($sql);
					
					for ($i2 = 0; $i2 < MYSQL_NUM_ROWS($result); $i2++) {
					  	$fila = MYSQL_FETCH_ARRAY($result);
					  	
					  	$sql = "SELECT * FROM pra INNER JOIN (SELECT DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."') AS consulta ON pra.DNI_PRA=consulta.DNI_PRA;";
					  	$result2 = MYSQL_QUERY($sql);
					  	
					  	for ($j2= 0; $j2 < MYSQL_NUM_ROWS($result2); $j2++) {
					  		$fila2 = MYSQL_FETCH_ARRAY($result2);		  	
						  	if (!isset($pernocta[$fila2['Id_Hab']])) {
						  	  	for ($k2 = 1; $k2 <= $fila2['Num_Camas']; $k2++) {
								    $pernocta[$fila2['Id_Hab']][$k2]['Clave'] = $fila2['DNI_PRA'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Title'] = 'Reserva: '.$fila2['Nombre_PRA'].' '.$fila2['Apellido1_PRA'].' '.$fila2['Apellido2_PRA'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Id_Hab'] = $fila2['Id_Hab'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Fecha_Llegada'] = $fila2['Fecha_Llegada'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Tipo_Cliente'] = "R";
								}
							    $pernocta[$fila2['Id_Hab']]['n_pernoctas'] = $pernocta[$fila2['Id_Hab']]['n_pernoctas'] + $fila2['Num_Camas'];
							}
							else {
							  	for ($k2 = ($pernocta[$fila2['Id_Hab']]['n_pernoctas'] + 1); $k2 <= ($pernocta[$fila2['Id_Hab']]['n_pernoctas'] + $fila2['Num_Camas']); $k2++) {
								    $pernocta[$fila2['Id_Hab']][$k2]['Clave'] = $fila2['DNI_PRA'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Title'] = 'Reserva: '.$fila2['Nombre_PRA'].' '.$fila2['Apellido1_PRA'].' '.$fila2['Apellido2_PRA'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Id_Hab'] = $fila2['Id_Hab'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Fecha_Llegada'] = $fila2['Fecha_Llegada'];
								    $pernocta[$fila2['Id_Hab']][$k2]['Tipo_Cliente'] = "R";
								}
							    $pernocta[$fila2['Id_Hab']]['n_pernoctas'] = $pernocta[$fila2['Id_Hab']]['n_pernoctas'] + $fila2['Num_Camas'];
							}
						}
					}

					for ($k = 1; $k <= $habitacion['Columnas_Necesarias']; $k++) {
					  	$cuadro_resto = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] - ($_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] * $habitacion['Columnas_Necesarias'] - $habitacion['Camas_Hab']);
						if ($i <= $cuadro_resto) {
						    $cama_siguiente = ($i - 1) *  $habitacion['Columnas_Necesarias'] + $k;
						}
						else if ($k < $habitacion['Columnas_Necesarias']) {					  
							$cama_siguiente = ($cuadro_resto * $habitacion['Columnas_Necesarias']) + ((($i - 1) - $cuadro_resto) * ($habitacion['Columnas_Necesarias'] - 1)) + $k;
						}
						else {
						  	$cama_siguiente = $habitacion['Camas_Hab'] + 1;
						}
						if ($cama_siguiente <= $habitacion['Camas_Hab']) {
						  	if (isset($pernocta[$habitacion['Id_Hab']])) {
						  	  	if ($cama_siguiente <= $pernocta[$habitacion['Id_Hab']]['n_pernoctas']) {
						  	  		if ($pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Tipo_Cliente'] == "R") {
?>
									<td class="cama_reservada_mini" title ="<?PHP echo $pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Title']; ?>" onclick="detalles('<?PHP echo $pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Tipo_Cliente'].'D\',\''.$pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Clave'].'\',\''.$pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Fecha_Llegada']; ?>');">&nbsp;</td>
<?PHP
									}
									else {
?>
									<td class="cama_ocupada_mini" title ="<?PHP echo $pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Title']; ?>" onclick="detalles('<?PHP echo $pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Tipo_Cliente'].'D\',\''.$pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Clave'].'\',\''.$pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Fecha_Llegada'];?>');" <?PHP if ($pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Tipo_Cliente'] == "G") echo 'style="background-color:#'.$pernocta[$habitacion['Id_Hab']][$cama_siguiente]['Color'].';"'; ?>>&nbsp;</td>
<?PHP
									}		  	  	  
						  	 	}
						  	 	else {
?>
									<td class="cama_libre_mini">&nbsp;</td>
<?PHP
								}
							}
							else {
?>
									<td class="cama_libre_mini">&nbsp;</td>
<?PHP
							}
						}
						else {
?>
									<td class="no_cama_mini">&nbsp;</td>
<?PHP
						}
					}
				}
			}
?>
								</tr>
<?PHP
		}
?>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?PHP
		MYSQL_CLOSE($db);
	}
?>