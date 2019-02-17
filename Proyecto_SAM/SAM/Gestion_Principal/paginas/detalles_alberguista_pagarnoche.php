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
		
		$tipo_persona = 'Alberguista';
		
?>

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_detalles_ancho']; ?>;">
					Pagar Noches
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
					<td rowspan="8" width="5px">
					<td>
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
						Pagadas:
					</td>
					<td align="left">
						<input type="text" name="nochespagadas" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Noches_Pagadas']." / ".$fila['PerNocta']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Pagado:
					</td>
					<td align="left">
<?PHP
		$sql = "SELECT Num_Factura FROM genera WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$result = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($result) == 0) {
		  	$facturado = "NO";
		  	$factura = "NULL";
		}
		else {
		  	$fila_factura = MYSQL_FETCH_ARRAY($result);
			$factura = $fila_factura['Num_Factura'];
		  	$facturado = "SI";
		}
		$sql = "SELECT * FROM tarifa WHERE Id_Edad IN (SELECT Id_Edad FROM edad WHERE Edad_Min <= ".edad($fila['Fecha_Nacimiento_Cl'],$fila['Fecha_Llegada'])." AND Edad_Max >= ".edad($fila['Fecha_Nacimiento_Cl'],$fila['Fecha_Llegada']).") AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fila['Fecha_Llegada']."' AND Id_Hab = '".$fila['Id_Hab']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
		$result = MYSQL_QUERY($sql);
		$tarifa = MYSQL_FETCH_ARRAY($result);
		//echo $sql;
		
		if (isset($fila['Id_Servicios'])) {
		  	$sql = "SELECT * FROM servicios WHERE Id_Servicios LIKE '".$fila['Id_Servicios']."' AND Id_Edad LIKE '".$tarifa['Id_Edad']."';";
		  	$servicio = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
		  	$plus = $servicio['Precio'];
		}
		else {
		  	$plus = 0;
		}
?>
						<input type="text" name="importepagado" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Ingreso']." €"; ?>">
					</td>
				</tr>				
<?PHP
		if ($facturado == "NO") {
?>
				<tr>
					<td colspan="2" class="texto_detalles" height="25" valign="center" align="left">
						Detalles Pago Noches:
					</td>
					<td colspan="2" class="texto_detalles" valign="center" align="left">
						<div class="no">NO FACTURADO</div>
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						€ / noche:
					</td>
					<td align="left">
						<input type="text" name="precionoche" size=9 contenteditable=false class="input_text" value="<?PHP echo ($tarifa['Tarifa'] + $plus)." €"; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Noches:
					</td>
					<td class="texto_detalles" align="left">
						<select name="numeronoches" class="seleccion" onchange="cambiar_importe(formulario.numeronoches,formulario.importe,'<?PHP echo ($tarifa['Tarifa'] + $plus); ?>',<?PHP echo $fila['Noches_Pagadas']; ?>,<?PHP echo $fila['Ingreso']; ?>);">
							<option value="NULL">
<?PHP
			for ($i = 1; $i <= ($fila['PerNocta'] - $fila['Noches_Pagadas']); $i++) {
			  	echo "<option value='".$i."'>".$i;
			}

			if ($fila['Noches_Pagadas'] > 0) {
				$factura_partida = true;
			}
			else {
				$factura_partida = false;
			}
?>
						</select>
					</td>
				</tr>
				<tr height="50">
					<td class="texto_detalles" align="left">
						Importe:
					</td>
					<td align="left">
						<input type="text" name="importe" size=9 contenteditable=false class="input_text">
					</td>
					<td align="left" colspan="2">
						<img src="./../imagenes/botones-texto/pagar.jpg" class="imagen-boton" onclick="detalles_pagar('ZA',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>',formulario.numeronoches.value,formulario.importe.value);">
					</td>
				</tr>
<?PHP
		}
		else {
?>
				<tr>
					<td colspan="2" class="texto_detalles" height="25" valign="center" align="left">
						Detalles Pago Noches:
					</td>
					<td colspan="2" class="texto_detalles" valign="center" align="left">
						<div class="ok">FACTURADO</div>
					</td>
				</tr>
<?PHP
		}
?>			
				<tr>
					<td colspan="5" height="43px" valign="bottom">
						<table width="100%" border="0">
							<tr>
								<td align="center">
									<img src="./../imagenes/botones/volver.gif" class="imagen-boton" title="Volver a Detalles Estancia" onclick="detalles('AD',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
								</td>
<?PHP
		if (isset($_SESSION['permisoFacturacion']) && ($_SESSION['permisoFacturacion'] == true) && ($factura_partida == true)) {
?>
								<td align="center">
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Facturar" onclick="facturar('AN','<?PHP echo $fila['DNI_Cl']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
								</td>
<?PHP
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