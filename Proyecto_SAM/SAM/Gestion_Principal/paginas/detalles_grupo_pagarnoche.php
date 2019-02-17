<?PHP
	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
		
		$tipo_persona = 'Grupo';
		
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$sql = "SELECT * FROM estancia_gr WHERE Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
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
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre Gr:
					</td>
					<td colspan="3" align="left">
						<input type="text" name="nombregrupo" size=35 contenteditable=false class="input_text" value="<?PHP echo $fila['Nombre_Gr']; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre Rep:
					</td>
					<td colspan="3" align="left">
						<input type="text" name="representante" size=35 contenteditable=false class="input_text" value="<?PHP echo $fila['Nombre_Repres'].' '.$fila['Apellido1_Repres'].' '.$fila['Apellido2_Repres']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Habitación:
					</td>
					<td align="left">
<?PHP
		$habitaciones = "";
		$sql = "SELECT DISTINCT(Id_Hab) AS Id_Hab FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
  		$result2 = MYSQL_QUERY($sql);
  		for ($j = 0; $j < MYSQL_NUM_ROWS($result2); $j++) {
  			$fila2 = MYSQL_FETCH_ARRAY($result2);
  			$habitaciones = $habitaciones.$fila2['Id_Hab'].", ";
  		}
  		$habitaciones = SUBSTR($habitaciones,0,-2);
?>
						<input type="text" name="habitacion" size=9 contenteditable=false class="input_text" value="<?PHP echo $habitaciones; ?>">
					</td>						
					<td class="texto_detalles" align="left">
						Camas:
					</td>
					<td align="left">
						<input type="text" name="camas" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Num_Personas']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Pagadas:
					</td>
					<td align="left">
<?PHP
		if ($fila['Noches_Pagadas'] == NULL) {
		  	$pagadas = "0";
		}
		else {
		  	$pagadas = $fila['Noches_Pagadas'];
		}

		if ($fila['Noches_Pagadas'] > 0) {
			$factura_partida = true;
		}
		else {
			$factura_partida = false;
		}
?>
						<input type="text" name="nochespagadas" size=9 contenteditable=false class="input_text" value="<?PHP echo $pagadas.' / '.$fila['PerNocta']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Pagado:
					</td>
					<td align="left">
						<input type="text" name="importepagado" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Ingreso']." €"; ?>">
					</td>
				</tr>
<?PHP
		$sql = "SELECT DISTINCT(Num_Factura) AS Num_Factura FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$facturas = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($facturas) == 0) {
		  	$facturado = false;
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
<?PHP
			$sql = "SELECT * FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
			//echo $sql;
			$total_estancias = MYSQL_QUERY($sql);
			$dinero_noche = 0;
			$plus = 0;
			
			for ($i = 0; $i < MYSQL_NUM_ROWS($total_estancias); $i++) {
			  	$estancia = MYSQL_FETCH_ARRAY($total_estancias);
				$sql = "SELECT * FROM tarifa WHERE Id_Edad LIKE '".$estancia['Id_Edad']."' AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fila['Fecha_Llegada']."' AND Id_Hab = '".$estancia['Id_Hab']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
				//echo $sql;
				$result = MYSQL_QUERY($sql);
				$tarifa = MYSQL_FETCH_ARRAY($result);
				$dinero_noche += $tarifa['Tarifa']*$estancia['Num_Personas'];
				
				if (isset($fila['Id_Servicios'])) {
				  	$sql = "SELECT * FROM servicios WHERE Id_Servicios LIKE '".$fila['Id_Servicios']."' AND Id_Edad LIKE '".$estancia['Id_Edad']."';";
					$servicio = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
				  	$plus += $estancia['Num_Personas'] * $servicio['Precio'];
				}
				else {
				  	$plus = 0;
				}	
			}
?>
						<input type="text" name="precionoche" size=9 contenteditable=false class="input_text" value="<?PHP echo ($dinero_noche + $plus)." €"; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Noches:
					</td>
					<td class="texto_detalles" align="left">
						<select name="numeronoches" class="seleccion" onchange="cambiar_importe(formulario.numeronoches,formulario.importe,'<?PHP echo ($dinero_noche + $plus); ?>',<?PHP echo $pagadas; ?>,<?PHP echo $fila['Ingreso']; ?>);">
							<option value="NULL">
<?PHP
			for ($i = 1; $i <= ($fila['PerNocta'] - $fila['Noches_Pagadas']); $i++) {
			  	echo "<option value='".$i."'>".$i;
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
						<img src="./../imagenes/botones-texto/pagar.jpg" class="imagen-boton" title="Pagar Importe" class="boton" onclick="detalles_pagar('ZG',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>',formulario.numeronoches.value,formulario.importe.value);">
					</td>
				</tr>
<?PHP
		}
		else {
		  	$facturado = true;
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
									<img src="./../imagenes/botones/volver.gif" class="imagen-boton" title="Volver a Detalles Estancia" onclick="detalles('GD',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
								</td>
<?PHP
		if (isset($_SESSION['permisoFacturacion']) && ($_SESSION['permisoFacturacion'] == true) && ($factura_partida == true)) {
?>
								<td align="center">
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Facturar" onclick="facturar('GN','<?PHP echo $fila['Nombre_Gr']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
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