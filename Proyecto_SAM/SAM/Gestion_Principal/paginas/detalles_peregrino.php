<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);	
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$sql = "SELECT * FROM pernocta_p LEFT JOIN cliente ON pernocta_p.DNI_Cl=cliente.DNI_Cl WHERE pernocta_p.DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND pernocta_p.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
	  	$result = MYSQL_QUERY($sql);
		$fila = MYSQL_FETCH_ARRAY($result);
		
		// Comprobamos a ver si es un malandrín:
		$sql_maloso = "SELECT * FROM incidencias LEFT JOIN pernocta_p ON incidencias.DNI_Inc=pernocta_p.DNI_Cl WHERE DNI_Inc LIKE '".$_SESSION['gdh']['detalles']['de_clave']."';";
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

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
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
					<td rowspan="8" width="5px">
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
						<input type="text" name="tipocliente" size=9 contenteditable=false class="input_text" value="Peregrino">
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
						Facturado:
					</td>
					<td align="left">
<?PHP
		$sql = "SELECT Num_Factura FROM genera_p WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
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
<?PHP
		$sql = "SELECT Tarifa FROM tarifa WHERE Id_Edad IN (SELECT Id_Edad FROM edad WHERE Edad_Min < ".edad($fila['Fecha_Nacimiento_Cl'],$fila['Fecha_Llegada'])." AND Edad_Max >= ".edad($fila['Fecha_Nacimiento_Cl'],$fila['Fecha_Llegada']).") AND Id_Tipo_Hab IN (SELECT Id_Tipo_Hab FROM habitacion WHERE Id_Hab LIKE '".$fila['Id_Hab']."') AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE 'Peregrino');";
		$result = MYSQL_QUERY($sql);
		$tarifa = MYSQL_FETCH_ARRAY($result);
		$dinero_pagado = $fila['Noches_Pagadas']*$tarifa['Tarifa'];
?>
						<input type="text" name="importepagado" size=9 contenteditable=false class="input_text" value="<?PHP echo $dinero_pagado." €"; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						M.P.:
					</td>
					<td align="left">
						<input type="text" name="modoperegrinacion" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['M_P']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Lesión:
					</td>
					<td align="left">
						<input type="text" name="lesion" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Lesion']; ?>">
					</td>
				</tr>
				<tr>
<?PHP
		if (isset($fila['Id_Servicios'])) {
?>
					<td class="texto_detalles" align="left">
						Servicio:
					</td>
					<td align="left">
						<input type="text" name="servicio" size=3 contenteditable=false class="input_text" value="<?PHP echo STRTOUPPER($fila['Id_Servicios']); ?>">
					</td>
<?PHP
		}
?>
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
		if ($incidencias == true) {
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
?>
								<td align="center">
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Ver Factura <?PHP echo $factura; ?>" onclick="ver_factura('<?PHP echo $factura; ?>');">
								</td>
								<!--<td align="center">
									<img src="./../imagenes/botones/modificarestancia.gif" class="imagen-boton" title="Modificar Estancia" onclick="window.location.href('?pag=modificacion_estancia.php&tipo=p&dni=<?PHP //echo $_SESSION['gdh']['detalles']['de_clave']; ?>&fecha_llegada=<?PHP //echo $_SESSION['gdh']['detalles']['de_fecha_llegada']; ?>&fecha_salida=<?PHP //echo $fila['Fecha_Salida']; ?>');">
								</td>-->
<?PHP
			if (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias'] == true) {
?>
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
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Facturar" onclick="facturar('P','<?PHP echo $fila['DNI_Cl']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
								</td>
<?PHP
				}
?>
								<td align="center">
									<img src="./../imagenes/botones/modificarestancia.gif" class="imagen-boton" title="Modificar Estancia" onclick="window.location.href('?pag=modificacion_estancia.php&tipo=p&dni=<?PHP echo $_SESSION['gdh']['detalles']['de_clave']; ?>&fecha_llegada=<?PHP echo $_SESSION['gdh']['detalles']['de_fecha_llegada']; ?>&fecha_salida=<?PHP echo $fila['Fecha_Salida']; ?>');">
								</td>
<?PHP
				if (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias'] == true) {
?>
								<td align="center">
									<img src="./../imagenes/botones/incidencias.gif" class="imagen-boton" title="Incidencia" onclick="window.location.href('?pag=incidencias.php&gdh=si&dni=<?PHP echo $fila['DNI_Cl']; ?>')">
								</td>
<?PHP
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