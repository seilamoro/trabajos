<?PHP
	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else { 	
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		$sql = "SELECT * FROM pernocta LEFT JOIN cliente ON pernocta.DNI_Cl=cliente.DNI_Cl WHERE pernocta.DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND pernocta.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
	  	$result = MYSQL_QUERY($sql);
		$fila = MYSQL_FETCH_ARRAY($result);
		
		$fecha_llegada = $fila['Fecha_Llegada'];
		$fecha_salida = $fila['Fecha_Salida'];
		
		// Si la estancia en esta habitación está facturada, no se busca más, pero si no esta facturada, buscamos
		// el resto de estancias (si las hubiese) en otras habitaciones en días consecutivos:
		
		$sql = "SELECT * FROM genera WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$auxiliar2 = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($auxiliar2) != 0) {
			$factura_actual = $fila['Num_Factura'];
		}
		else {
			// Está en varias habitaciónes días consecutivos:
			$multi_estancia = false;
			$fechas_entrada = array();
			$fechas_entrada[] = $fila['Fecha_Llegada'];
			
			// Buscamos estancias consecutivas anteriores en otras habitaciones, que no estén facturadas:
			$dia_entrada = $fila['Fecha_Llegada'];
			$final = true;
			while ($final) {
			  	$sql = "SELECT * FROM pernocta WHERE DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Salida LIKE '".$dia_entrada."';";
			  	$busqueda_entrada = MYSQL_QUERY($sql);
			  	if (MYSQL_NUM_ROWS($busqueda_entrada) == 0) {
			  	  	$fila['Fecha_Llegada'] = $dia_entrada;
				    $final = false;
				}
				else {
				  	$auxiliar = MYSQL_FETCH_ARRAY($busqueda_entrada);
				  	$sql = "SELECT * FROM genera WHERE DNI_Cl LIKE '".$auxiliar['DNI_Cl']."' AND Fecha_Llegada LIKE '".$auxiliar['Fecha_Llegada']."';";
					$auxiliar2 = MYSQL_QUERY($sql);
					if (MYSQL_NUM_ROWS($auxiliar2) > 0) {
						$aux2 = MYSQL_FETCH_ARRAY($auxiliar2);
					}
				  	if ($aux2['Num_Factura'] == $factura_actual) {
				  	  	$fila['Id_Hab'] = $auxiliar['Id_Hab'].','.$fila['Id_Hab'];
					  	$dia_entrada = $auxiliar['Fecha_Llegada'];
					  	$fechas_entrada[] = $auxiliar['Fecha_Llegada'];
					  	$fila['PerNocta'] = $fila['PerNocta'] + $auxiliar['PerNocta'];
					  	$fila['Noches_Pagadas'] += $auxiliar['Noches_Pagadas'];
					  	$fila['Ingreso'] += $auxiliar['Ingreso'];
					  	$multi_estancia = true;
					}
					else {
					  	$fila['Fecha_Llegada'] = $dia_entrada;
				    	$final = false;
					}
				}
			}
			// Fin dia que entro.
			//echo $dia_entrada;
			
			if (isset($aux2)) {
				unset($aux2);
			}
		
			// Buscamos el dia que sale del albergue, aunque pernocte en otras habitaciones.
			$dia_salida = $fila['Fecha_Salida'];
			$final = true;
			while ($final) {
			  	$sql = "SELECT * FROM pernocta WHERE DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$dia_salida."';";
				$busqueda_salida = MYSQL_QUERY($sql);
			  	if (MYSQL_NUM_ROWS($busqueda_salida) == 0) {
			  	  	$fila['Fecha_Salida'] = $dia_salida;
				    $final = false;
				}
				else {
				  	$auxiliar = MYSQL_FETCH_ARRAY($busqueda_salida);
				  	$sql = "SELECT * FROM genera WHERE DNI_Cl LIKE '".$auxiliar['DNI_Cl']."' AND Fecha_Llegada LIKE '".$auxiliar['Fecha_Llegada']."';";
					$auxiliar2 = MYSQL_QUERY($sql);
					if (MYSQL_NUM_ROWS($auxiliar2) > 0) {
						$aux2 = MYSQL_FETCH_ARRAY($auxiliar2);
					}
				  	if ($aux2['Num_Factura'] == $factura_actual) {
				  	  	$fila['Id_Hab'] = $fila['Id_Hab'].','.$auxiliar['Id_Hab'];
					  	$dia_salida = $auxiliar['Fecha_Salida'];
					  	$fechas_entrada[] = $auxiliar['Fecha_Llegada'];
					  	$fila['PerNocta'] += $auxiliar['PerNocta'];
					  	$fila['Noches_Pagadas'] += $auxiliar['Noches_Pagadas'];
					  	$fila['Ingreso'] += $auxiliar['Ingreso'];
					  	$multi_estancia = true;
					}
					else {
					  	$fila['Fecha_Salida'] = $dia_salida;
				    	$final = false;
					}
				}
			}		
			// Fin dia que sale.
			//echo $dia_salida;
			
			// Comprobamos a ver si es un malandrín:
			$sql_maloso = "SELECT * FROM incidencias LEFT JOIN pernocta ON incidencias.DNI_Inc=pernocta.DNI_Cl WHERE DNI_Inc LIKE '".$_SESSION['gdh']['detalles']['de_clave']."';";
			//echo $sql_maloso;
			$result_maloso = MYSQL_QUERY($sql_maloso);
			if (MYSQL_NUM_ROWS($result_maloso) > 0) {
				$incidencias = true;
			}
			else {
				$incidencias = false;
			}
		}
?>

<table border="0" id="tabla_detalles" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_detalles_ancho']; ?>;">
				Detalles Estancia
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<table border="0" width="100%" height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_detalles_alto']; ?>" style="border: 1px solid #3F7BCC;">
				<tr>
<?PHP
		if (isset($fila['Id_Servicios'])) {
?>
					<td rowspan="9" width="5px">
					<td>
<?PHP
		}
		else {
?>
					<td rowspan="8" width="5px">
					<td>
<?PHP
		}
?>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						D.N.I.:
					</td>
					<td align="left">
						<input type="text" name="dni" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['DNI_Cl']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Edad:
					</td>
					<td align="left">
						<input type="text" name="edad" size=3 contenteditable=false class="input_text" value="<?PHP echo edad($fila['Fecha_Nacimiento_Cl'],$fila['Fecha_Llegada']); ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre:
					</td>
					<td colspan="3" align="left">
						<input type="text" name="nombre" size=40 contenteditable=false class="input_text" value="<?PHP echo $fila['Nombre_Cl']." ".$fila['Apellido1_Cl']." ".$fila['Apellido2_Cl']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Habitación:
					</td>
					<td align="left">
						<input type="text" name="habitacion" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Id_Hab']; ?>">
					</td>						
					<td class="texto_detalles" align="left">
						Cliente:
					</td>
					<td align="left">
						<input type="text" name="tipocliente" size=9 contenteditable=false class="input_text" value="Alberguista">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Llegada:
					</td>
					<td align="left">
						<input type="text" name="fechallegada" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Llegada']); ?>">
					</td>						
					<td class="texto_detalles" align="left">
						Salida:
					</td>
					<td align="left">
						<input type="text" name="fechasalida" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Salida']); ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Pernocta:
					</td>
					<td align="left">
						<input type="text" name="pernocta" size=3 contenteditable=false class="input_text" value="<?PHP echo $fila['PerNocta']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Pagadas:
					</td>
					<td align="left">
						<input type="text" name="nochespagadas" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Noches_Pagadas']." / ".$fila['PerNocta']; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Facturado:
					</td>
					<td align="left">
<?PHP
		$sql = "SELECT Num_Factura FROM genera WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$result = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($result) == 0) {
		  	$facturado = "NO";
		  	$factura = NULL;
		}
		else {
		  	$fila_factura = MYSQL_FETCH_ARRAY($result);
			$factura = $fila_factura['Num_Factura'];
		  	$facturado = "SI";		  	
		}
?>
						<input type="text" name="facturado" size=3 contenteditable=false class="input_text" value="<?PHP echo $facturado; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Pagado:
					</td>
					<td align="left">
						<input type="text" name="importepagado" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Ingreso']." €"; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Exento:
					</td>
					<td align="left">
<?PHP
		if ($factura == "NULL") {
		  	$exento = "NS";
		}
		else {
		  	$sql = "SELECT Exento FROM genera WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."' AND Num_Factura LIKE '".$factura."';";
			$result = MYSQL_QUERY($sql);
			$aux = MYSQL_FETCH_ARRAY($result);
			if (($aux['Exento'] == NULL)||($aux['Exento'] == 'N')) {
			  	$exento = "NO";
			}
			else if ($aux['Exento'] == 'S') {
				$exento = 'SI';
			}
			else {
				$exento = $aux['Exento'];
			}
		}
?>
						<input type="text" name="exento" size=3 contenteditable=false class="input_text" value="<?PHP echo $exento; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Taquillas:
					</td>
					<td align="left">
<?PHP
	  	$sql = "SELECT COUNT(Id_Taquilla) AS Taquillas FROM taquilla WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."';";
		$result = MYSQL_QUERY($sql);
		$taquillas = MYSQL_FETCH_ARRAY($result);
?>
						<input type="text" name="taquillas" size=3 contenteditable=false class="input_text" value="<?PHP echo $taquillas['Taquillas']; ?>">
					</td>
				</tr>
<?PHP
		if (isset($fila['Id_Servicios'])) {
?>
				<tr>
					<td class="texto_detalles" align="left">
						Servicio:
					</td>
					<td align="left">
						<input type="text" name="servicio" size=3 contenteditable=false class="input_text" value="<?PHP echo STRTOUPPER($fila['Id_Servicios']); ?>">
					</td>
<?PHP
			if ($incidencias == true) {
?>
					<td class="texto_detalles" align="left" colspan="2">
						<img src="./../imagenes/botones/alerta.gif" title="Tiene Incidencias" style="width:20px;"/>
					</td>
<?PHP
			}
?>
				</tr>
<?PHP
		}
		else if ($incidencias == true) {
?>
				<tr>
					<td></td>
					<td class="texto_detalles" align="left" colspan="2">
						<img src="./../imagenes/botones/alerta.gif" title="Tiene Incidencias" style="width:20px;"/>
					</td>
				</tr>
<?PHP
		}
?>
				<tr>
					<td colspan="5" valign="bottom" height="76px">
						<table width="100%" border="0">
							<tr>
<?PHP
		if ($factura != NULL) {
		  	if (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion'] == true) {
?>
								<td align="center">
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Ver Factura <?PHP echo $factura; ?>" onclick="ver_factura('<?PHP echo $factura; ?>');">
								</td>
<?PHP
			}
			if (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias'] == true) {
?>
								<!--<td align="center">
									<img src="./../imagenes/botones/modificarestancia.gif" class="imagen-boton" title="Modificar Estancia" onclick="window.location.href('?pag=modificacion_estancia.php&tipo=a&dni=<?PHP //echo $_SESSION['gdh']['detalles']['de_clave']; ?>&fecha_llegada=<?PHP //echo $fila['Fecha_Llegada']; ?>&fecha_salida=<?PHP //echo $fila['Fecha_Salida']; ?>');">
								</td>-->
								<td align="center">
									<img src="./../imagenes/botones/incidencias.gif" class="imagen-boton" title="Incidencia" onclick="window.location.href('?pag=incidencias.php&gdh=si&dni=<?PHP echo $fila['DNI_Cl']; ?>')">
								</td>
<?PHP
			}
		}
		else {
			if (SUBSTR($_SESSION['gdh']['gdh_dis']['tipo'],0,1) == 'F') {
?>
								<td align="center">
									<img src="./../imagenes/botones/volver.gif" class="imagen-boton" title="Volver a Detalles Estancia" onclick="recargar_gdh_dis('H');">
								</td>
<?PHP
			}
			else {
			  	if (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion'] == true) {
?>
								<td align="center">
<?PHP
					if ($multi_estancia == true) {
					  	for ($k = 0; $k < COUNT($fechas_entrada); $k++) {
?>
									<input type="hidden" name="estancias_mismo[]" value="<?PHP echo 'A*'.$_SESSION['gdh']['detalles']['de_clave'].'*'.$fechas_entrada[$k]; ?>">						
<?PHP
						}
?>					
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Facturar" onclick="facturar('AM','<?PHP echo $fila['DNI_Cl']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
<?PHP
					}
					else {
?>
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Facturar" onclick="facturar('A','<?PHP echo $fila['DNI_Cl']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
<?PHP
					}
				}
?>
								<td align="center">
									<img src="./../imagenes/botones/modificarestancia.gif" class="imagen-boton" title="Modificar Estancia" onclick="window.location.href('?pag=modificacion_estancia.php&tipo=a&dni=<?PHP echo $_SESSION['gdh']['detalles']['de_clave']; ?>&fecha_llegada=<?PHP echo $fecha_llegada; ?>&fecha_salida=<?PHP echo $fecha_salida; ?>');">
								</td>
								<td align="center">
									<img src="./../imagenes/botones/pagarnoches.gif" class="imagen-boton" title="Pagar Noches" onclick="detalles('AP',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
								</td>
<?PHP
				if (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias'] == true) {
?>
								<td align="center">
									<img src="./../imagenes/botones/incidencias.gif" class="imagen-boton" title="Incidencia" onclick="window.location.href('?pag=incidencias.php&gdh=si&dni=<?PHP echo $fila['DNI_Cl']; ?>')">
								</td>
<?
				}
			}
		}
?>
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
