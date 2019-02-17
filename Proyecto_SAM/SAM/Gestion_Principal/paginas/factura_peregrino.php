<script language="javascript">

	// Función que cambia el valor del total de la factura que queda por pagar.
	function cambiar_resto_factura(campo_total,resto,desperfecto) {
  	  	if (isNaN(parseFloat(desperfecto.value))) {
			campo_total.value = resto + ' €';
		}
		else {	
	    	campo_total.value = (resto + parseFloat(desperfecto.value)) + ' €';			  
		}
	}

</script>

<?PHP

	$tipo_persona = 'peregrino';

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
?>

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
					Factura de Peregrino
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<table border="0" height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_alto_facturas']; ?>" width="100%" style="border: 1px solid #3F7BCC;">
<?PHP
		$sql = "SELECT Num_Factura FROM factura WHERE SUBSTRING(Num_Factura,-4) LIKE '".DATE("Y")."';";
		$result = MYSQL_QUERY($sql);

		$buscar_ultima_factura = array();
			
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$facturacion = MYSQL_FETCH_ARRAY($result);
		  	$cont = COUNT($buscar_ultima_factura);
		  	$buscar_ultima_factura[$cont]['orden'] = INTVAL(SUBSTR($facturacion['Num_Factura'],(-1 * STRLEN($facturacion['Num_Factura'])),-5));
		  	//echo '<tr><td>'.INTVAL(SUBSTR($facturacion['Num_Factura'],(-1 * STRLEN($facturacion['Num_Factura'])),-5)).'</td></tr>';
		  	$buscar_ultima_factura[$cont]['Num_Factura'] = $facturacion['Num_Factura'];
		}
		
		if (COUNT($buscar_ultima_factura) >= 1) {
		
			foreach ($buscar_ultima_factura AS $llave => $fila_2) {
			   $last[$llave] = $fila_2['orden'];
			}
			
			ARRAY_MULTISORT($buscar_ultima_factura, SORT_DESC, $last);
			
			$facturacion = $buscar_ultima_factura[0];

			$factura = array();
			$factura = SPLIT("-",$facturacion['Num_Factura']);
			$factura_siguiente = $factura;
			$factura_siguiente[0]++;
		}
		else {
		  	$factura_siguiente = SPLIT("-",'1-'.DATE("Y"));
		}
?>
				<tr>
					<td rowspan="9" width="5px">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						NºFactura:
					</td>
					<td align="left">
						<table>
							<tr>
								<td class="texto_detalles">
									<input type="text" name="numero_factura" size=4 maxlength=5 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $factura_siguiente[0]; ?>">
								</td>
								<td class="texto_detalles"><div class="input_text">-</div>
								</td>
								<td class="texto_detalles">
									<input type="text" name="anio_factura" size=2 maxlength=4 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $factura_siguiente[1]; ?>">
								</td>
							</tr>
						</table>
						</td>
					<td class="texto_detalles" align="left">
						Fecha Factura:
					</td>
					<td align="left">
						<input type="text" name="fecha_factura" size=9 maxlength=10 contenteditable=true class="input_text_vacio" value="<?PHP echo date('d-m-Y'); ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						D.N.I.:
					</td>
					<td align="left">
						<input type="text" name="dni_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['DNI_Cl']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Nombre:
					</td>
					<td align="left">
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Nombre_Cl']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						1<sup>er</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Apellido1_Cl']; ?>">
					</td>						
					<td class="texto_detalles" align="left">
						2<sup>o</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Apellido2_Cl']; ?>">
					</td>						
				</tr>
<?PHP	
		$sql = "SELECT * FROM tarifa WHERE Id_Edad IN (SELECT Id_Edad FROM edad WHERE Edad_Min <= ".edad($fila['Fecha_Nacimiento_Cl'],$fila['Fecha_Llegada'])." AND Edad_Max >= ".edad($fila['Fecha_Nacimiento_Cl'],$fila['Fecha_Llegada']).") AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fila['Fecha_Llegada']."' AND Id_Hab = '".$fila['Id_Hab']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
		$result = MYSQL_QUERY($sql);
		$tarifa = MYSQL_FETCH_ARRAY($result);
		//echo $tarifa['Tarifa'];
		//echo $fila['Noches_Pagadas'];
		
		// Mirar si tiene servicios adicionales (Media Pensión, etc...)
		if (isset($fila['Id_Servicios'])) {
		  	$sql = "SELECT * FROM servicios WHERE Id_Servicios LIKE '".$fila['Id_Servicios']."' AND Id_Edad LIKE '".$tarifa['Id_Edad']."';";
		  	$servicio = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
		  	$plus = $servicio['Precio'];
		}
		else {
		  	$plus = 0;
		}		

		$resto = FLOATVAL($fila['PerNocta'] * ($tarifa['Tarifa'] + $plus) - ($tarifa['Tarifa'] + $plus) * $fila['Noches_Pagadas']);
		
?>
				<tr>
					<td class="texto_detalles" align="left">
						Desperfecto:
					</td>
					<td align="left">
						<input type="text" name="desperfecto_factura" size=20 maxlength=255 contenteditable=true class="input_text_vacio" value="">
					</td>												
					<td class="texto_detalles" align="left">
						Cuantía:
					</td>
					<td align="left">
						<input type="text" name="cuantia_desperfecto_factura" size=6 maxlength=7 contenteditable=true class="input_text_vacio_numerico" value="0.0" onkeyup="cambiar_resto_factura(total_importe_factura,<?PHP echo $resto; ?>,cuantia_desperfecto_factura);">
					</td>												
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Llegada:
					</td>
					<td align="left">
						<input type="text" name="fechallegada_factura" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Llegada']); ?>">
					</td>
					<td class="texto_detalles" align="left">
						Habitación:
					</td>
					<td align="left">
						<input type="text" name="habitacion_factura" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Id_Hab']; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Pagadas:
					</td>
					<td align="left">
						<input type="text" name="pagadas_factura" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Noches_Pagadas']." / ".$fila['PerNocta']; ?>">
						<input type="hidden" name="nochespagadas_factura" value="<?PHP echo $fila['PerNocta']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Pagado:
					</td>
					<td align="left">
						<input type="text" name="importepagado_factura" size=9 contenteditable=false class="input_text" value="<?PHP echo FLOATVAL($tarifa['Tarifa'] * $fila['Noches_Pagadas'])." €"; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Resto:
					</td>
					<td align="left">
						<input type="text" name="resto_factura" size=9 contenteditable=false class="input_text" value="<?PHP echo $resto.' €'; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Total:
					</td>
					<td align="left">
						<input type="text" name="total_importe_factura" size=9 contenteditable=false style="border-style:2px solid;border-color:#064C87;background-color:<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;color:#064C87;font-size:15px;font-weight:bold;text-align:right;" value="<?PHP echo $resto.' €'; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<table>
							<tr>
								<td class="texto_detalles">							
									<img src="./../imagenes/botones-texto/cancelar.jpg" class="imagen-boton" title="Cancelar" value="Cancelar" onclick="recargar_gdh_dis('H');">
								</td>
								<td width="50px">
								</td>
								<td class="texto_detalles">
									<img src="./../imagenes/botones-texto/facturar.jpg" class="imagen-boton" title="Crear Nueva Factura" value="Facturar" onclick="comprueba_dni('P');">
<script language="javascript">

	// Función que comprueba que el DNI es válido
	function comprueba_dni(tipo) {
		var aux = formulario.dni_factura.value;
		formulario.dni_factura.value = aux.toUpperCase();
		var resultado = dni_valido(formulario.dni_factura);
		if (resultado == true) {
			tipo_factura(tipo);	
		}
		else {
			alert("El D.N.I. no es válido.");
		}
	}

</script>
								</td>
							</td>
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
