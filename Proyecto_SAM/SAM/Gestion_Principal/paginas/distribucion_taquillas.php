<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
				Distribución de Taquillas
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
						<table border=0 width="100%">
							<tr>
								<td width="50px" rowspan=2>
								</td>
								<td align="left" width="350px" rowspan=2>
								<div class="tableContainer" style="overflow:scroll;width:339px;height:280px;">
									<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1">
<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
	if (!$db) {	  
?>
	<tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr>
<?PHP
		exit();
	}
	else {
		MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		$sql = "SELECT DISTINCT(Id_Hab) AS Id_Hab FROM taquilla;";
		$result = MYSQL_QUERY($sql);
?>

										<thead class="fixedHeader">
											<tr>
												<td colspan="3">
												</td>
												<td class="nom_tipo_hab" colspan="<?echo MYSQL_NUM_ROWS($result);?>">
													Habitaciones
												<td>
											</tr>
											<tr align="center" valign="middle"> 
												<td width="2px"></td>
												<td align="center" class="nom_taq">Nº</td>
												<td width="2px"></td>
<?PHP
		$hab_taquillas = array();
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$fila = MYSQL_FETCH_ARRAY($result);
			$hab_taquillas[$i]['Orden'] = INTVAL($fila['Id_Hab']);
			$hab_taquillas[$i]['Id_Hab'] = $fila['Id_Hab'];
		}
		
		foreach ($hab_taquillas as $llave => $fila) {
		   $Orden[$llave]  = $fila['Orden'];
		}	
		
		if (COUNT($hab_taquillas) > 0) {		
			ARRAY_MULTISORT($Orden, SORT_ASC, $hab_taquillas);
		}
		
		for ($i = 0; $i < count($hab_taquillas); $i++) {
?>
	  
												<td class="nom_taq"><?PHP echo $hab_taquillas[$i]['Id_Hab']; ?></td>
<?PHP
		}					
?>
												<td width="2px"></td>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<td height="5px"></td>
											</tr>
										</tfoot>
										<tbody>
<?PHP
		$sql = "SELECT DISTINCT(Id_Taquilla) AS Id_Taquilla FROM taquilla;";
		$result = MYSQL_QUERY($sql);
		
		$taquillas = array();
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$fila = MYSQL_FETCH_ARRAY($result);
		  	$taquillas[$i] = $fila['Id_Taquilla'];
		}
		
		SORT($taquillas);
		
		$sql_taquilla = "SELECT * FROM taquilla WHERE Id_Taquilla LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_num']."' AND Id_Hab LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_hab']."';";
		$fila_taquilla = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_taquilla));
		
		if ($_SESSION['gdh']['gdh_dis']['taquilla_pulsada'] == false) {
		  	$sql_ver_taquilla = "SELECT * FROM taquilla WHERE DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' OR Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."';";
			//echo $sql_ver_taquilla;
			$result_ver_taquilla = MYSQL_QUERY($sql_ver_taquilla);
			if (MYSQL_NUM_ROWS($result_ver_taquilla) > 0) {
			  	$ver_taquilla = MYSQL_FETCH_ARRAY($result_ver_taquilla);
			  	$_SESSION['gdh']['gdh_dis']['taquilla_num'] = $ver_taquilla['Id_Taquilla'];
				$_SESSION['gdh']['gdh_dis']['taquilla_hab'] = $ver_taquilla['Id_Hab'];
			}
			else {
			  	$_SESSION['gdh']['gdh_dis']['taquilla_num'] = NULL;
				$_SESSION['gdh']['gdh_dis']['taquilla_hab'] = NULL;
			}
		}
		
		
		if (($fila_taquilla['DNI_Cl'] != NULL)&&($_SESSION['gdh']['gdh_dis']['taquilla_pulsada'] == true)) {
	  	  	$sql_estancia = "SELECT * FROM pernocta WHERE DNI_Cl LIKE '".$fila_taquilla['DNI_Cl']."' AND Fecha_Llegada LIKE (SELECT MAX(Fecha_Llegada) AS Fecha_Llegada FROM pernocta WHERE DNI_Cl LIKE '".$fila_taquilla['DNI_Cl']."');";
		    $result_estancia = MYSQL_QUERY($sql_estancia);
		    if (MYSQL_NUM_ROWS($result_estancia) == 0) {
			  	$sql_estancia = "SELECT * FROM pernocta_p WHERE DNI_Cl LIKE '".$fila_taquilla['DNI_Cl']."' AND Fecha_Llegada LIKE (SELECT MAX(Fecha_Llegada) AS Fecha_Llegada FROM pernocta_p WHERE DNI_Cl LIKE '".$fila_taquilla['DNI_Cl']."');";
				$result_estancia = MYSQL_QUERY($sql_estancia);
		    	if (MYSQL_NUM_ROWS($result_estancia) == 0) {
				  	$_SESSION['gdh']['detalles']['de_tipo'] = NULL;
					$_SESSION['gdh']['detalles']['de_clave'] = NULL;
					$_SESSION['gdh']['detalles']['de_fecha_llegada'] = NULL;
					$_SESSION['gdh']['detalles']['de_pagina'] = NULL;
				}
				else {
				  	// Detalles peregrino.
				  	$_SESSION['gdh']['detalles']['de_tipo'] = "P";
				  	$_SESSION['gdh']['detalles']['de_pagina'] = "D";
				  	$fila_estancia = MYSQL_FETCH_ARRAY($result_estancia);
					$_SESSION['gdh']['detalles']['de_clave'] = $fila_estancia['DNI_Cl'];
					$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $fila_estancia['Fecha_Llegada'];
				}
			}
			else {
			  	// Detalles alberguista.
			  	$_SESSION['gdh']['detalles']['de_tipo'] = "A";
			  	$_SESSION['gdh']['detalles']['de_pagina'] = "D";
			  	$fila_estancia = MYSQL_FETCH_ARRAY($result_estancia);
				$_SESSION['gdh']['detalles']['de_clave'] = $fila_estancia['DNI_Cl'];
				$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $fila_estancia['Fecha_Llegada'];
			}
			$_SESSION['gdh']['gdh_dis']['taquilla_pulsada'] = false;
		}
	  	else if (($fila_taquilla['Nombre_Gr'] != NULL)&&($_SESSION['gdh']['gdh_dis']['taquilla_pulsada'] == true)) {
	  	  	$sql_estancia = "SELECT * FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila_taquilla['Nombre_Gr']."' AND Fecha_Llegada LIKE (SELECT MAX(Fecha_Llegada) AS Fecha_Llegada FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila_taquilla['Nombre_Gr']."');";
		    $result_estancia = MYSQL_QUERY($sql_estancia);
		    if (MYSQL_NUM_ROWS($result_estancia) == 0) {
			  	$_SESSION['gdh']['detalles']['de_tipo'] = NULL;
				$_SESSION['gdh']['detalles']['de_clave'] = NULL;
				$_SESSION['gdh']['detalles']['de_fecha_llegada'] = NULL;
				$_SESSION['gdh']['detalles']['de_pagina'] = NULL;
			}
			else {
			  	// Detalles peregrino.
			  	$_SESSION['gdh']['detalles']['de_tipo'] = "G";
			  	$_SESSION['gdh']['detalles']['de_pagina'] = "D";
			  	$fila_estancia = MYSQL_FETCH_ARRAY($result_estancia);
				$_SESSION['gdh']['detalles']['de_clave'] = $fila_estancia['Nombre_Gr'];
				$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $fila_estancia['Fecha_Llegada'];
			}
			$_SESSION['gdh']['gdh_dis']['taquilla_pulsada'] = false;
		}
		/*else {
		  	$_SESSION['gdh']['detalles']['de_tipo'] = NULL;
			$_SESSION['gdh']['detalles']['de_clave'] = NULL;
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = NULL;
			$_SESSION['gdh']['detalles']['de_pagina'] = NULL;
		}*/
		
		for ($i = 0; $i < count($taquillas); $i++) {
?>
											<tr>
												<td width="2px"></td>
												<td align="center" class="nom_taq"><?PHP echo $taquillas[$i]; ?></td>
												<td width="2px"></td>
<?PHP
			for ($j = 0; $j < count($hab_taquillas); $j++) {
			  	$sql = "SELECT * FROM taquilla WHERE Id_Taquilla LIKE '".$taquillas[$i]."' AND Id_Hab LIKE '".$hab_taquillas[$j]['Id_Hab']."';";
			  	$result = MYSQL_QUERY($sql);
			  	if (MYSQL_NUM_ROWS($result) == 0) {			  
?>
												<td class="no_taquilla">&nbsp;</td>
<?PHP
				}
				else {
				  	$fila = MYSQL_FETCH_ARRAY($result);
					if ($fila['Estado_Taq'] == 'H') {
				  		if (($fila['DNI_Cl'] == NULL) && ($fila['Nombre_Gr'] == NULL)) {
?>
												<td class="taquilla_libre" onclick="detalles_taquilla('<?PHP echo $hab_taquillas[$j]['Id_Hab']."*".$taquillas[$i]; ?>');">&nbsp;</td>
<?PHP
						}
						else {					  
						  	if ((($fila['DNI_Cl'] != NULL) && ($fila['DNI_Cl'] == $_SESSION['gdh']['detalles']['de_clave'])) || (($fila['Nombre_Gr'] != NULL) && ($fila['Nombre_Gr'] == $_SESSION['gdh']['detalles']['de_clave']))) {
?>
												<td class="taquilla_ocupada_persona" onclick="detalles_taquilla('<?PHP echo $hab_taquillas[$j]['Id_Hab']."*".$taquillas[$i]; ?>');">&nbsp;</td>
<?PHP
							}
							else {
?>
												<td class="taquilla_ocupada" onclick="detalles_taquilla('<?PHP echo $hab_taquillas[$j]['Id_Hab']."*".$taquillas[$i]; ?>');">&nbsp;</td>
<?PHP						  
							}
						}
					}
					else {
?>
												<td class="taquilla_deshabilitada" onclick="detalles_taquilla('<?PHP echo $hab_taquillas[$j]['Id_Hab']."*".$taquillas[$i]; ?>');">&nbsp;</td>
<?PHP
					}
				}
			}
		}
?>
												<td width="2px"></td>
											</tr>											
										</tbody>
									</table>
									</div>
								</td>
								<td width="50px">
								</td>
<?PHP		
		$sql = "SELECT * FROM taquilla WHERE Id_Taquilla LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_num']."' AND Id_Hab LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_hab']."';";
		$result = MYSQL_QUERY($sql);
		$fila = MYSQL_FETCH_ARRAY($result);
		
		if ($fila['Estado_Taq'] != 'H') {
?>
								
								<td valign="center">
									<table border="0" class="tabla_detalles" align="left">
										<tr height="15px">
											<td class="no" colspan="2">
												Taquilla Deshabilitada
											<td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" colspan="2">
												&nbsp;
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" width="70px">
												Habitación: 
											</td>
											<td align="left">
												<input type="text" name="taqhabitacion" value="<?PHP echo $_SESSION['gdh']['gdh_dis']['taquilla_hab']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												Taquilla: 
											</td>
											<td align="left">
												<input type="text" name="taqtaquilla" value="<?PHP echo $_SESSION['gdh']['gdh_dis']['taquilla_num']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
									</table>
								</td>
<?PHP
		}
		else if (($fila['DNI_Cl'] == NULL)&&($fila['Nombre_Gr'] == NULL)) {
?>
								<td valign="center">
									<table border="0" class="tabla_detalles" align="left">
										<tr height="15px">
											<td class="ok" colspan="2">
												Taquilla Libre
											<td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" colspan="2">
												&nbsp;
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" width="70px">
												Habitación: 
											</td>
											<td align="left">
												<input type="text" name="taqhabitacion" value="<?PHP echo $_SESSION['gdh']['gdh_dis']['taquilla_hab']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												Taquilla: 
											</td>
											<td align="left">
												<input type="text" name="taqtaquilla" value="<?PHP echo $_SESSION['gdh']['gdh_dis']['taquilla_num']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
										<tr class="texto_detalles" align="center" height="50">
											<td colspan="2" valign="bottom">
												<img src="../imagenes/botones-texto/ocupar.jpg" title="Ocupar la taquilla" onclick="ocupar_taquilla();" style="cursor:pointer;">
											</td>
										</tr>
									</table>
								</td>
<?PHP
		}
		else {
		  	if ($fila['DNI_Cl'] != NULL) {
		  	  	$sql = "SELECT DNI_Cl,Nombre_Cl,Apellido1_Cl,Apellido2_Cl FROM cliente WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."';";
		  	  	$result = MYSQL_QUERY($sql);
		  	  	$taquilla = MYSQL_FETCH_ARRAY($result);
?>
								<td valign="center">
									<table border="0" class="tabla_detalles" align="left">
										<tr height="15px">
											<td class="no" colspan="2">
												Taquilla Ocupada
											<td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" colspan="2">
												&nbsp;
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" width="70px">
												Habitación: 
											</td>
											<td align="left">
												<input type="text" name="taqhabitacion" value="<?PHP echo $fila['Id_Hab']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												Taquilla: 
											</td>
											<td align="left">
												<input type="text" name="taqtaquilla" value="<?PHP echo $fila['Id_Taquilla']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												D.N.I.:
											</td>
											<td align="left">
												<input type="text" name="taqdni" size=10 contenteditable=false class="input_text" value="<?PHP echo $taquilla['DNI_Cl']; ?>">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												Nombre:
											</td>
											<td align="left">
												<input type="text" name="taqnombre" size=10 contenteditable=false class="input_text" value="<?PHP echo $taquilla['Nombre_Cl']; ?>">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												1<sup>er</sup> Apellido:
											</td>
											<td align="left">
												<input type="text" name="taqap1" size=10 contenteditable=false class="input_text" value="<?PHP echo $taquilla['Apellido1_Cl']; ?>">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												2<sup>o</sup> Apellido:
											</td>
											<td align="left">
												<input type="text" name="taqap2" size=10 contenteditable=false class="input_text" value="<?PHP echo $taquilla['Apellido2_Cl']; ?>">
											</td>
										</tr>
										<tr class="texto_detalles" align="center" height="50">
											<td colspan="2" valign="bottom">
												<img src="../imagenes/botones-texto/desocupar.jpg" title="Desocupar la taquilla" onclick="desocupar_taquilla();" style="cursor:pointer;">
											</td>
										</tr>
									</table>
								</td>
<?PHP
		 	}
			else {
?>												
								<td valign="center">
									<table border="0" class="tabla_detalles" align="left">
										<tr height="15px">
											<td class="no" colspan="2">
												Taquilla Ocupada
											<td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" colspan="2">
												&nbsp;
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" width="70px">
												Habitación: 
											</td>
											<td align="left">
												<input type="text" name="taqhabitacion" value="<?PHP echo $fila['Id_Hab']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												Taquilla: 
											</td>
											<td align="left">
												<input type="text" name="taqtaquilla" value="<?PHP echo $fila['Id_Taquilla']; ?>" size=10 contenteditable=false class="input_text">
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left" colspan="2">
												GRUPO
											</td>
										</tr>
										<tr class="texto_detalles">
											<td align="left">
												Nombre:
											</td>
											<td align="left">
												<input type="text" name="taqdni" size=10 contenteditable=false class="input_text" value="<?PHP echo $fila['Nombre_Gr']; ?>">
											</td>
										</tr>
										<tr class="texto_detalles" align="center" height="50">
											<td colspan="2" valign="bottom">
												<img src="../imagenes/botones-texto/desocupar.jpg" title="Desocupar la taquilla" onclick="desocupar_taquilla();" style="cursor:pointer;">
											</td>
										</tr>
									</table>
								</td>
<?PHP
			}
		}
?>
							</tr>
							<tr>
								<td align="left" valign="bottom" colspan=2>
									<table border=0 width="100%">
										<tr>
											<td>
												<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1">
													<tr>
														<td class="taquilla_libre" style="width:15px;height:15px;">
														</td>
													</tr>
												</table>
											</td>
											<td class="texto_detalles" style="background-color:<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;border:0px 0px 0px 0px none;" width="0px">	
												Libre
											</td>
											<td>
												<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1">
													<tr>				
														<td class="taquilla_ocupada" style="width:15px;height:15px;">
														</td>
													</tr>
												</table>
											</td>
											<td class="texto_detalles" style="background-color:<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;border:0px 0px 0px 0px none;" width="0px">	
												Ocupada
											</td>
											<td>
												<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1">
													<tr>
														<td class="taquilla_deshabilitada" style="width:15px;height:15px;">
														</td>
													</tr>
												</table>
											</td>
											<td class="texto_detalles" style="background-color:<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;border:0px 0px 0px 0px none;" width="0px">	
												Deshabilitada
											</td>
											<td>
												<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1">
													<tr>				
														<td class="taquilla_ocupada_persona" style="width:15px;height:15px;">
														</td>
													</tr>
												</table>
											</td>
											<td class="texto_detalles" style="background-color:<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;border:0px 0px 0px 0px none;" width="0px">	
												Estancia
											</td>
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
</table>
<?PHP
		MYSQL_CLOSE($db);
	}
?>
