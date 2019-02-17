<?PHP

	// Comentario

	// Numero de página que corresponde a las variables de sesión que se han creado para dar el orden
	// de las habitaciones.

	$habitaciones_orden = array();
	$numero_paginas = array();

	for ($i = 0; $i < COUNT($_SESSION['pag_hab']); $i++) {
	  	//echo $_SESSION['pag_hab'][$i]['pagina'].'-'.$_SESSION['pag_hab'][$i]['orden'].'-'.$_SESSION['pag_hab'][$i]['Id_Tipo_Hab'].'  ';
	  	if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$numero_paginas)) {
		    $numero_paginas[] = $_SESSION['pag_hab'][$i]['pagina'];
		}
	  	if ($_SESSION['pag_hab'][$i]['pagina'] == $_SESSION['gdh']['dis_hab']['num_pag']) {
	  	  	$cont = COUNT($habitaciones_orden);
		    $habitaciones_orden[$cont]['orden'] = $_SESSION['pag_hab'][$i]['orden'];
		    $habitaciones_orden[$cont]['Id_Tipo_Hab'] = $_SESSION['pag_hab'][$i]['Id_Tipo_Hab'];
		}
	}
	
	if ($_SESSION['gdh']['dis_hab']['num_pag'] > COUNT($numero_paginas)) {
		$_SESSION['gdh']['dis_hab']['num_pag'] = 1;
	}

	foreach ($habitaciones_orden as $llave => $fila) {
	   $orden[$llave]  = $fila['orden'];
	}
	
	if (COUNT($habitaciones_orden) > 0) {
		@ ARRAY_MULTISORT($orden, SORT_DESC, $habitaciones_orden);
	}	

?>
<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
				Distribución de Habitaciones
				<select name="num_pag" class="detalles_select" onchange="cambiar_pagina_dis(num_pag.value);" style="position:relative;margin-left:40px;">
<?PHP

	for ($i = 0; $i < COUNT($numero_paginas); $i++) {
	  	if ($numero_paginas[$i] == $_SESSION['gdh']['dis_hab']['num_pag']) {
	  		echo '<option value="'.$numero_paginas[$i].'" selected>Ventana '.$numero_paginas[$i];
	  	}
	  	else {
	  		echo '<option value="'.$numero_paginas[$i].'">Ventana '.$numero_paginas[$i];
		}
	}

?>
				</select>
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<table border="0" width="100%" style="border: 1px solid #3F7BCC;">				
				<tr>
					<td height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_alto']; ?>">
						<table width="100%">
							<tr>
								<td align="center">
									<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1">
										<tbody>
<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);	
	
	if (!$db) {	  
?>
	<tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></thead></tr>
<?PHP
		exit();
	}
	else {
		MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	
		$habitaciones = array();
		
		for ($j = 0; $j < COUNT($habitaciones_orden); $j++) {
		  		      		  
			$sql = "SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab WHERE Id_Tipo_Hab LIKE '".$habitaciones_orden[$j]['Id_Tipo_Hab']."' AND Camas_Hab > 0;";
			
			$result = MYSQL_QUERY($sql);
			
			$sql = "SELECT * FROM tipo_habitacion WHERE Id_Tipo_Hab LIKE '".$habitaciones_orden[$j]['Id_Tipo_Hab']."';";
			//echo '<tr><td>'.$sql.'</td></tr>';
			
			$result2 = MYSQL_QUERY($sql);
			
			$nombre_id_tipo_hab = MYSQL_FETCH_ARRAY($result2);
			
			for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
			  	$fila = MYSQL_FETCH_ARRAY($result);
			  	if ($fila['Camas_Hab'] >= 0) {
			  	  	$lenght = count($habitaciones);
			        $habitaciones[$lenght]['Orden'] = INTVAL($fila['Id_Hab']);
					$habitaciones[$lenght]['Id_Hab'] = $fila['Id_Hab'];
					$habitaciones[$lenght]['Id_Tipo_Hab'] = $fila['Id_Tipo_Hab'];
			  	  	$habitaciones[$lenght]['Nombre_Id_Tipo_Hab'] = $nombre_id_tipo_hab['Nombre_Tipo_Hab'];
			  	  	$habitaciones[$lenght]['Num_Id_Tipo_Hab'] = MYSQL_NUM_ROWS($result);
					$habitaciones[$lenght]['Orden_Tipo_Hab'] = $habitaciones_orden[$j]['orden'];
					$habitaciones[$lenght]['Camas_Hab'] = $fila['Camas_Hab'];
					if ($fila['Camas_Hab'] > $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']) {
						$resto = $fila['Camas_Hab'] % $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];
					  	$habitaciones[$lenght]['Columnas_Necesarias'] = INTVAL($fila['Camas_Hab'] / $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']);
					  	if ($resto > 0) {
						    $habitaciones[$lenght]['Columnas_Necesarias']++;
						}
					}
					else {
					  	$habitaciones[$lenght]['Columnas_Necesarias'] = 1;
					}
				}
			}
		}
		
		foreach ($habitaciones as $llave => $fila) {
		   $Orden[$llave]  = $fila['Orden'];
		   $Orden_Tipo_Hab[$llave] = $fila['Orden_Tipo_Hab'];
		}
		
		if (COUNT($habitaciones) > 0) {		
			@ ARRAY_MULTISORT($Orden_Tipo_Hab, SORT_ASC, $Orden, SORT_ASC, $habitaciones);
		}
?>
											<tr align="center" valign="middle">
												<td width="1px"></td>
<?PHP
		$separar = $habitaciones[0]['Id_Tipo_Hab'];
		
		for ($i = 0; $i < count($habitaciones); $i++) {
			if ($separar != $habitaciones[$i]['Id_Tipo_Hab']) {
			  	$separar = $habitaciones[$i]['Id_Tipo_Hab'];			  	
?>
												<td width="1px" rowspan="<?PHP echo $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] + 3; ?>"></td>
												<td width="2px" rowspan="<?PHP echo $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] + 3; ?>" align=center class="separar_hab"></td>
												<td width="1px" rowspan="<?PHP echo $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] + 3; ?>"></td>
<?PHP
			}
			$colspan = 0;
			for ($p = 0; $p < COUNT($habitaciones); $p++) {
			  	if ($habitaciones[$p]['Id_Tipo_Hab'] == $separar) {
				    $colspan = $colspan + $habitaciones[$p]['Columnas_Necesarias'];
				}
			}
?>	  
												<td class="nom_tipo_hab" colspan="<?PHP echo $colspan; ?>" onclick="cambiar_habitacion('<?PHP echo $habitaciones[$i]['Id_Tipo_Hab']; ?>');"><?PHP echo $habitaciones[$i]['Nombre_Id_Tipo_Hab']; ?></td>
<?PHP
												$i = $i + $habitaciones[$i]['Num_Id_Tipo_Hab'] - 1;
		}	
?>
												<td width="1px"></td>
											</tr>
											<tr align="center" valign="middle">
												<td></td>
<?PHP
		for ($i = 0; $i < count($habitaciones); $i++) {
?>
	  
												<td class="<?PHP if ($habitaciones[$i]['Id_Hab'] == $_SESSION['gdh']['dis_hab']['hab_seleccionada']) {echo 'nom_hab_seleccion';} else {echo 'nom_hab_online';}?>" colspan="<?PHP echo $habitaciones[$i ]['Columnas_Necesarias'];?>" onclick="seleccionar_habitacion('<?PHP echo $habitaciones[$i]['Id_Hab']; ?>');"><?PHP echo $habitaciones[$i]['Id_Hab']; ?></td>
<?PHP
		}	
?>
												<td></td>
											</tr>
<?PHP
		for ($j = 0; $j < COUNT($habitaciones['Id_Hab']); $j++) {
		  	$sql = "SELECT Camas_Hab FROM habitacion WHERE Id_Hab LIKE '".$habitaciones[$j]['Id_Hab']."';";
		  	$result = MYSQL_QUERY($sql);
		  	$fila = MYSQL_FETCH_ARRAY($result);
		  	$habitaciones['Camas_Hab'][$j] = INTVAL($fila['Camas_Hab']);
		}
		
		$pernocta = array();
		
		// Buscar pernoctas de alberguistas
		$sql = "SELECT * FROM cliente INNER JOIN (SELECT DNI_Cl, Id_Hab, Fecha_Llegada FROM pernocta WHERE Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' ORDER BY Fecha_Llegada) AS consulta ON cliente.DNI_Cl=consulta.DNI_Cl;";
		//echo $sql;
		$result = MYSQL_QUERY($sql);
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$fila = MYSQL_FETCH_ARRAY($result);
		  	if (!isset($pernocta[$fila['Id_Hab']])) {
			    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = 1;
			    //echo '<tr><td>'.$fila['Id_Hab'].'-'.$fila['DNI_Cl'].'</td></tr>';
			    $pernocta[$fila['Id_Hab']][1]['Clave'] = $fila['DNI_Cl'];
			    $pernocta[$fila['Id_Hab']][1]['Title'] = 'Alberguista: '.$fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
				$pernocta[$fila['Id_Hab']][1]['Id_Hab'] = $fila['Id_Hab'];
			    $_SESSION['gdh']['habitaciones'][''.$fila['Id_Hab']] = true;  // Utilizo esta variable para indicar que la habitacion tiene estancias y que no se puede cambiar de tipo de habitación.
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
		$sql = "SELECT * FROM cliente INNER JOIN (SELECT DNI_Cl, Id_Hab, Fecha_Llegada FROM pernocta_p WHERE Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' ORDER BY Fecha_Llegada) AS consulta ON consulta.DNI_Cl=cliente.DNI_Cl";
		$result = MYSQL_QUERY($sql);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$fila = MYSQL_FETCH_ARRAY($result);
		  	if (!isset($pernocta[$fila['Id_Hab']])) {
			    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = 1;
			    $pernocta[$fila['Id_Hab']][1]['Clave'] = $fila['DNI_Cl'];
			    $pernocta[$fila['Id_Hab']][1]['Title'] = 'Peregrino: '.$fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
				$pernocta[$fila['Id_Hab']][1]['Id_Hab'] = $fila['Id_Hab'];
			    $_SESSION['gdh']['habitaciones'][''.$fila['Id_Hab']] = true;  // Utilizo esta variable para indicar que la habitacion tiene estancias y que no se puede cambiar de tipo de habitación.
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
		$sql = "SELECT * FROM (SELECT pernocta_gr.Nombre_Gr,pernocta_gr.Id_Hab,pernocta_gr.Fecha_Llegada,pernocta_gr.Num_Personas AS Num_Personas,estancia_gr.Fecha_Salida,estancia_gr.Id_Color FROM pernocta_gr LEFT JOIN estancia_gr ON pernocta_gr.Fecha_Llegada=estancia_gr.Fecha_Llegada AND pernocta_gr.Nombre_Gr=estancia_gr.Nombre_Gr WHERE estancia_gr.Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND estancia_gr.Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' ORDER BY estancia_gr.Fecha_Llegada) AS consulta INNER JOIN colores ON consulta.Id_Color=colores.Id_Color;";
		//echo $sql;
		$result = MYSQL_QUERY($sql);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$fila = MYSQL_FETCH_ARRAY($result);
		  	if (!isset($pernocta[$fila['Id_Hab']])) {
		  	  	for ($k = 1; $k <= $fila['Num_Personas']; $k++) {
				    $pernocta[$fila['Id_Hab']][$k]['Clave'] = $fila['Nombre_Gr'];
				    $pernocta[$fila['Id_Hab']][$k]['Title'] = 'Grupo: '.$fila['Nombre_Gr'];
				    $pernocta[$fila['Id_Hab']][$k]['Id_Hab'] = $fila['Id_Hab'];
				    $_SESSION['gdh']['habitaciones'][''.$fila['Id_Hab']] = true;  // Utilizo esta variable para indicar que la habitacion tiene estancias y que no se puede cambiar de tipo de habitación.
			    	$pernocta[$fila['Id_Hab']][$k]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
				    $pernocta[$fila['Id_Hab']][$k]['Tipo_Cliente'] = "G";
				    $pernocta[$fila['Id_Hab']][$k]['Color'] = $fila['Color'];
				}
			    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = $pernocta[$fila['Id_Hab']]['n_pernoctas'] + $fila['Num_Personas'];
			}
			else {
			  	for ($k = ($pernocta[$fila['Id_Hab']]['n_pernoctas'] + 1); $k <= ($pernocta[$fila['Id_Hab']]['n_pernoctas'] + $fila['Num_Personas']); $k++) {
				    $pernocta[$fila['Id_Hab']][$k]['Clave'] = $fila['Nombre_Gr'];
				    $pernocta[$fila['Id_Hab']][$k]['Title'] = 'Grupo: '.$fila['Nombre_Gr'];
				    $pernocta[$fila['Id_Hab']][$k]['Id_Hab'] = $fila['Id_Hab'];
				    $pernocta[$fila['Id_Hab']][$k]['Fecha_Llegada'] = $fila['Fecha_Llegada'];
				    $pernocta[$fila['Id_Hab']][$k]['Tipo_Cliente'] = "G";
				    $pernocta[$fila['Id_Hab']][$k]['Color'] = $fila['Color'];
				}
			    $pernocta[$fila['Id_Hab']]['n_pernoctas'] = $pernocta[$fila['Id_Hab']]['n_pernoctas'] + $fila['Num_Personas'];
			}
		}
		
		// Buscar reservas
		$sql = "SELECT DNI_PRA, Fecha_Llegada FROM detalles WHERE Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' ORDER BY Fecha_Llegada;";
		$result = MYSQL_QUERY($sql);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$fila = MYSQL_FETCH_ARRAY($result);
		  	
		  	$sql = "SELECT * FROM pra INNER JOIN (SELECT DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."') AS consulta ON pra.DNI_PRA=consulta.DNI_PRA;";
		  	//echo $sql;
			$result2 = MYSQL_QUERY($sql);
		  	
		  	for ($j = 0; $j < MYSQL_NUM_ROWS($result2); $j++) {
		  		$fila2 = MYSQL_FETCH_ARRAY($result2);		  	
			  	if (!isset($pernocta[$fila2['Id_Hab']])) {
			  	  	for ($k = 1; $k <= $fila2['Num_Camas']; $k++) {
					    $pernocta[$fila2['Id_Hab']][$k]['Clave'] = $fila2['DNI_PRA'];
					    $pernocta[$fila2['Id_Hab']][$k]['Title'] = 'Reserva: '.$fila2['Nombre_PRA'].' '.$fila2['Apellido1_PRA'].' '.$fila2['Apellido2_PRA'];
					    $pernocta[$fila2['Id_Hab']][$k]['Id_Hab'] = $fila2['Id_Hab'];
					    $_SESSION['gdh']['habitaciones'][''.$fila['Id_Hab']] = true;  // Utilizo esta variable para indicar que la habitacion tiene estancias y que no se puede cambiar de tipo de habitación.
			    		$pernocta[$fila2['Id_Hab']][$k]['Fecha_Llegada'] = $fila2['Fecha_Llegada'];
					    $pernocta[$fila2['Id_Hab']][$k]['Tipo_Cliente'] = "R";
					}
				    $pernocta[$fila2['Id_Hab']]['n_pernoctas'] = $pernocta[$fila2['Id_Hab']]['n_pernoctas'] + $fila2['Num_Camas'];
				}
				else {
				  	for ($k = ($pernocta[$fila2['Id_Hab']]['n_pernoctas'] + 1); $k <= ($pernocta[$fila2['Id_Hab']]['n_pernoctas'] + $fila2['Num_Camas']); $k++) {
					    $pernocta[$fila2['Id_Hab']][$k]['Clave'] = $fila2['DNI_PRA'];
					    $pernocta[$fila2['Id_Hab']][$k]['Title'] = 'Reserva: '.$fila2['Nombre_PRA'].' '.$fila2['Apellido1_PRA'].' '.$fila2['Apellido2_PRA'];
					    $pernocta[$fila2['Id_Hab']][$k]['Id_Hab'] = $fila2['Id_Hab'];
					    $pernocta[$fila2['Id_Hab']][$k]['Fecha_Llegada'] = $fila2['Fecha_Llegada'];
					    $pernocta[$fila2['Id_Hab']][$k]['Tipo_Cliente'] = "R";
					}
				    $pernocta[$fila2['Id_Hab']]['n_pernoctas'] = $pernocta[$fila2['Id_Hab']]['n_pernoctas'] + $fila2['Num_Camas'];
				}
			}
		}		
		
		for ($i = 1; $i <= $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']; $i++) {
?>											<tr>
												<td></td>
<?PHP
			for ($j = 0; $j < COUNT($habitaciones); $j++) {
				for ($k = 1; $k <= $habitaciones[$j]['Columnas_Necesarias']; $k++) {
				  	$cuadro_resto = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] - ($_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] * $habitaciones[$j]['Columnas_Necesarias'] - $habitaciones[$j]['Camas_Hab']);
					if ($i <= $cuadro_resto) {
					    $cama_siguiente = ($i - 1) *  $habitaciones[$j]['Columnas_Necesarias'] + $k;
					}
					else if ($k < $habitaciones[$j]['Columnas_Necesarias']) {					  
						$cama_siguiente = ($cuadro_resto * $habitaciones[$j]['Columnas_Necesarias']) + ((($i - 1) - $cuadro_resto) * ($habitaciones[$j]['Columnas_Necesarias'] - 1)) + $k;
					}
					else {
					  	$cama_siguiente = $habitaciones[$j]['Camas_Hab'] + 1;
					}
					if ($cama_siguiente <= $habitaciones[$j]['Camas_Hab']) {
					  	if (isset($pernocta[$habitaciones[$j]['Id_Hab']])) {
					  	  	if ($cama_siguiente <= $pernocta[$habitaciones[$j]['Id_Hab']]['n_pernoctas']) {
					  	  	  	if ($pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Tipo_Cliente'] == "R") {
?>
												<td class="cama_reservada" title="<?PHP echo $pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Title']; ?>" onclick="detalles('<?PHP echo $pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Tipo_Cliente'].'D\',\''.$pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Clave'].'\',\''.$pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Fecha_Llegada'];?>');">&nbsp;</td>
<?PHP
								}
								else {
?>
												<td class="cama_ocupada" title="<?PHP echo $pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Title']; ?>" onclick="detalles('<?PHP echo $pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Tipo_Cliente'].'D\',\''.$pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Clave'].'\',\''.$pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Fecha_Llegada'];?>');" <?PHP if ($pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Tipo_Cliente'] == "G") echo 'style="background-color:#'.$pernocta[$habitaciones[$j]['Id_Hab']][$cama_siguiente]['Color'].';"'; ?>>&nbsp;</td>
<?PHP
								}		  	  	  
					  	 	}
					  	 	else {
?>
												<td class="cama_libre" style="font-size:10px;">&nbsp;</td>
<?PHP
							}						
						}
						else {
?>
												<td class="cama_libre" style="font-size:10px;">&nbsp;</td>
<?PHP
						}
					}
					else {
?>
												<td class="no_cama">&nbsp;</td>
<?PHP
					}
				}				
			}
?>
												<td></td>
											</tr>
<?PHP
		}		
?>
												
											<tr height="5px">
											</tr>
										</tbody>
<?PHP
	}
?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?PHP
	MYSQL_CLOSE($db);
?>
