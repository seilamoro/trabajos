<script language="javascript">

	//Función que cambia el valor del resto de la factura en caso de que
	//el alberguista sea exento de pago.
	function modificar_resto_factura_alberguista_exento(resto,pagado,total,plus,desperfecto) {
	  	if (formulario.exento_mismo_alberguista.value == 'S') {
		    resto.value = (-1 * pagado + plus) + ' €';
		}
		else {
		  	resto.value = total - pagado + ' €';
		}
		if (desperfecto.value == '') {
		  	if (formulario.exento_mismo_alberguista.value == 'S') {
			    resto.value = (-1 * pagado + plus) + ' €';
			}
			else {
			  	resto.value = total - pagado + ' €';
			}
		}
		else if (!isNaN(desperfecto.value)) {
		  	resto.value = (parseFloat(resto.value) + parseFloat(desperfecto.value)) + ' €';
		}
	}


</script>

<?PHP

$tipo_persona = 'alberguista';
$_SESSION['estancias_agrupar'] = array();

@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

if (!$db) {

?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
}
else {
	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
    for ($i = 0; $i < COUNT($_POST['estancias_mismo']); $i++) {
		//echo $_POST['estancias_mismo'][$i].'<br>';
		$estancia_agrupar = array();
		$estancia_agrupar = SPLIT('\*',$_POST['estancias_mismo'][$i]);
		$sql = "SELECT * FROM pernocta LEFT JOIN cliente ON pernocta.DNI_Cl=cliente.DNI_Cl WHERE pernocta.DNI_Cl LIKE '".$estancia_agrupar[1]."' AND pernocta.Fecha_Llegada LIKE '".$estancia_agrupar[2]."';";
		$_SESSION['estancias_agrupar'][$i]['fila'] = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
		//echo $sql;
	}

	echo '<script language="javascript">';

	echo 'function recargar_datos() {';
		echo 'formulario.nombre_factura.value = "'.$_SESSION['estancias_agrupar'][0]['fila']['Nombre_Cl'].'";';
		echo 'formulario.apellido1_factura.value = "'.$_SESSION['estancias_agrupar'][0]['fila']['Apellido1_Cl'].'";';
		echo 'formulario.apellido2_factura.value = "'.$_SESSION['estancias_agrupar'][0]['fila']['Apellido2_Cl'].'";';
	echo '}';
	
	echo '</script>';
?>

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
					Factura Agrupada de Estancias de un Mismo Alberguista
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
					<td class="texto_detalles" align="left">
						NºFactura:
					</td>
					<td align="left">
						<table border="0">
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
					<td rowspan="9" valign="top" align="left">
						<table border="0">
							<tr>
								<td class="texto_detalles" align="left" colspan="3">
									Datos de las estancias del Alberguista:
								</td>
							</tr>
							<tr>
								<td>	
									<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['factura_agrupada']; ?>;">
										<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
											<thead class="fixedHeader">
												<tr>
													<th align="left">
														Hab.
													</th>
													<th align="left">
														D.N.I.
													</th>
													<th align="left">
														Nombre
													</th>
													<th align="left">
														Apellidos
													</th>
													<th align="left">
														Exento
													</th>
												</tr>
											</thead>
											<tbody class="scrollContent">
<?PHP	
	$resto = 0;
	$total = 0;
	$pagado = 0;
	$plus = 0;
	for ($i = 0; $i < COUNT($_SESSION['estancias_agrupar']); $i++) {
		$sql = "SELECT * FROM tarifa WHERE Id_Edad IN (SELECT Id_Edad FROM edad WHERE Edad_Min <= ".edad($_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Nacimiento_Cl'],$_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Llegada'])." AND Edad_Max >= ".edad($_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Nacimiento_Cl'],$_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Llegada']).") AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Llegada']."' AND Id_Hab = '".$_SESSION['estancias_agrupar'][$i]['fila']['Id_Hab']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
		//echo $sql;
		$result = MYSQL_QUERY($sql);
		$_SESSION['estancias_agrupar'][$i]['tarifa'] = MYSQL_FETCH_ARRAY($result);		
		
		// Mirar si tiene servicios adicionales (Media Pensión, etc...)
		if (isset($_SESSION['estancias_agrupar'][$i]['fila']['Id_Servicios'])) {  
		  	$sql = "SELECT * FROM servicios WHERE Id_Servicios LIKE '".$_SESSION['estancias_agrupar'][$i]['fila']['Id_Servicios']."' AND Id_Edad LIKE '".$_SESSION['estancias_agrupar'][$i]['tarifa']['Id_Edad']."';";
			$servicio = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
		  	$plus_i = $servicio['Precio'];
		  	$plus += $servicio['Precio'] * $_SESSION['estancias_agrupar'][$i]['fila']['PerNocta'];
		  	//echo $plus_i.' - '.$plus;
		  	$_SESSION['estancias_agrupar'][$i]['fila']['precio_servicio'] = $plus_i;
		}
		else {
		  	$plus_i = 0;
		}
		
		$_SESSION['estancias_agrupar'][$i]['resto'] = FLOATVAL(($_SESSION['estancias_agrupar'][$i]['tarifa']['Tarifa'] + $plus_i) * $_SESSION['estancias_agrupar'][$i]['fila']['PerNocta'] - $_SESSION['estancias_agrupar'][$i]['fila']['Ingreso']);
		$resto += $_SESSION['estancias_agrupar'][$i]['resto'];
		$total += FLOATVAL($_SESSION['estancias_agrupar'][$i]['fila']['PerNocta'] * ($_SESSION['estancias_agrupar'][$i]['tarifa']['Tarifa'] + $plus_i));
		$pagado += $_SESSION['estancias_agrupar'][$i]['fila']['Ingreso'];
	}
	
	$exento_todo = true;
	
	for ($i = 0; $i < COUNT($_SESSION['estancias_agrupar']); $i++) {	  	  	
?>
		    									<tr class="texto_listados" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>">
		  											<td align="right">
<?PHP
		echo $_SESSION['estancias_agrupar'][$i]['fila']['Id_Hab'];
?>
											  		</td>
											  		<td align="left" width="90px">	  		
<?PHP
		echo $_SESSION['estancias_agrupar'][$i]['fila']['DNI_Cl'];
?>													
													</td>
											  		<td align="left" width="100px">	  		
<?PHP
		echo $_SESSION['estancias_agrupar'][$i]['fila']['Nombre_Cl'];
?>
											  		</td>
											  		<td align="left" width="180px">	  		
<?PHP 
		echo $_SESSION['estancias_agrupar'][$i]['fila']['Apellido1_Cl'].' '.$_SESSION['estancias_agrupar'][$i]['fila']['Apellido2_Cl'];;
?>
											  		</td>
											  		<td align="right">
<?PHP
		if ($exento_todo) {
?>
		  												<select class="detalles_select" name="exento_mismo_alberguista" onchange="modificar_resto_factura_alberguista_exento(resto_factura,<?PHP echo $pagado; ?>,<?PHP echo $total; ?>,<?PHP echo $plus; ?>,cuantia_desperfecto_factura);">
														  	<option value="N" selected>NO
														  	<option value="S">SI
		  												</select>	
<?PHP
			$exento_todo = false;
		}
		else {
		  	echo '&nbsp';
		}
?>		  			
											  		</td>
											    </tr>
<?PHP
	}
		
	if (COUNT($_SESSION['estancias_agrupar']) < $_SESSION['gdh']['factura']['configuracion_numero_filas']) {
  		for ($i = COUNT($_SESSION['estancias_agrupar']); $i < $_SESSION['gdh']['factura']['configuracion_numero_filas']; $i++) {
?>
												<tr class="texto_listados" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>">
													<td>&nbsp;
													</td>
													<td>&nbsp;
													</td>
													<td>&nbsp;
													</td>
													<td>&nbsp;
													</td>
													<td>&nbsp;
													</td>
												</tr>
<?PHP
		}
	}
?>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>	
					<td class="texto_detalles" align="left">
						Fecha Factura:
					</td>
					<td align="left">
<?PHP
	if (isset($_POST['fecha_factura'])) {
?>
						<input type="text" name="fecha_factura" size=9 maxlength=10 contenteditable=true class="input_text_vacio" value="<?PHP echo $_POST['fecha_factura']; ?>">
<?PHP
	}
	else {
?>
						<input type="text" name="fecha_factura" size=9 maxlength=10 contenteditable=true class="input_text_vacio" value="<?PHP echo date('d-m-Y'); ?>">
<?PHP
	}
?>
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						D.N.I.:
					</td>
					<td align="left">
						<input type="radio" name="escoger_dni" value="1" checked onclick="recargar_datos();">
						<input type="text" name="dni_factura_select" value="<?PHP echo $_SESSION['estancias_agrupar'][0]['fila']['DNI_Cl']?>" size=15 maxlength=30 contenteditable=false class="input_text">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
					</td>
					<td align="left">
						<input type="radio" name="escoger_dni" value="0">
						<input type="text" name="dni_factura_text" size=15 maxlength=30 contenteditable=true class="input_text_vacio" value="">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre:
					</td>
					<td align="left">
<?PHP
	if (isset($_POST['nombre_factura'])) {
?>
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP //echo $_POST['nombre_factura']; ?>">
<?PHP
	}
	else {
?>
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['estancias_agrupar'][0]['fila']['Nombre_Cl']; ?>">
<?PHP
	}
?>
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						1<sup>er</sup> Apellido:
					</td>
					<td align="left">
<?PHP
	if (isset($_POST['apellido1_factura'])) {
?>
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP //echo $_POST['apellido1_factura']; ?>">
<?PHP
	}
	else {
?>
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['estancias_agrupar'][0]['fila']['Apellido1_Cl']; ?>">
<?PHP
	}
?>
					</td>
				</tr>
				<tr>					
					<td class="texto_detalles" align="left">
						2<sup>o</sup> Apellido:
					</td>
					<td align="left">
<?PHP
	if (isset($_POST['apellido2_factura'])) {
?>
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP //echo $_POST['apellido2_factura']; ?>">
<?PHP
	}
	else {
?>
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['estancias_agrupar'][0]['fila']['Apellido2_Cl']; ?>">
<?PHP
	}
?>						
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Desperfecto:
					</td>
					<td align="left">
<?PHP
	if (isset($_POST['desperfecto_factura'])) {
?>
						<input type="text" name="desperfecto_factura" size=20 maxlength=255 contenteditable=true class="input_text_vacio" value="<?PHP //echo $_POST['desperfecto_factura']; ?>">
<?PHP
	}
	else {
?>
						<input type="text" name="desperfecto_factura" size=20 maxlength=255 contenteditable=true class="input_text_vacio" value="">
<?PHP
	}
?>	
					</td>	
				</tr>
				<tr>												
					<td class="texto_detalles" align="left">
						Cuantía:
					</td>
					<td align="left">
<?PHP
	if (isset($_POST['cuantia_desperfecto_factura'])) {
?>
						<input type="text" name="cuantia_desperfecto_factura" size=6 maxlength=7 contenteditable=true class="input_text_vacio_numerico" value="<?PHP //echo $_POST['cuantia_desperfecto_factura']; ?>" onkeyup="modificar_resto_factura_alberguista_exento(resto_factura,<?PHP echo $pagado; ?>,<?PHP echo $total; ?>,<?PHP echo $plus; ?>,cuantia_desperfecto_factura);">
<?PHP
	}
	else {
?>
						<input type="text" name="cuantia_desperfecto_factura" size=6 maxlength=7 contenteditable=true class="input_text_vacio_numerico" value="0.0" onkeyup="modificar_resto_factura_alberguista_exento(resto_factura,<?PHP echo $pagado; ?>,<?PHP echo $total; ?>,<?PHP echo $plus; ?>,cuantia_desperfecto_factura);">
<?PHP
	}
?>
					</td>												
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Total:
					</td>
					<td align="left">
						<input type="text" name="resto_factura" size=9 contenteditable=false style="border-style:2px solid;border-color:#064C87;background-color:<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;color:#064C87;font-size:15px;font-weight:bold;text-align:right;" value="<?PHP echo $resto.' €'; ?>">
					</td>
					<td align="center">
						<table>
							<tr>
								<td class="texto_detalles">							
									<img src="./../imagenes/botones-texto/cancelar.jpg" class="imagen-boton" title="Cancelar" value="Cancelar" onclick="recargar_gdh_dis('H');">
								</td>
								<td width="50px">
								</td>
								<td class="texto_detalles">		
									<img src="./../imagenes/botones-texto/facturar.jpg" class="imagen-boton" title="Crear Nueva Factura" value="Facturar" onclick="comprueba_dni('FAA');">
<script language="javascript">

	// Función que comprueba que el DNI es válido
	function comprueba_dni(tipo) {
		var aux = formulario.dni_factura_text.value;
		formulario.dni_factura_text.value = aux.toUpperCase();
		if (formulario.escoger_dni[1].checked == true) {
			var resultado = dni_valido(formulario.dni_factura_text);
			if (resultado == true) {
				tipo_factura(tipo);				
			}
			else {
				alert("El D.N.I. no es válido.");
			}
		}
		else {
			tipo_factura(tipo);
		}
	}

</script>
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