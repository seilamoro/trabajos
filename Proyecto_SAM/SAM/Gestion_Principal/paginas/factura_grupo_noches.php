<script language="JavaScript">

	// Cuando seleccionamos el número de exentos en una factura, nos cambia el campo de facturados
	function exentos_facturados(num_personas,campo_exentos,campo_facturados,clave,fecha) {
	  	campo_facturados.value = num_personas - campo_exentos.value;
	  	formulario.gdh_dis.value = 'FGN';
	  	formulario.gdh_fac.value = 'G' + '*' + clave + '*' + fecha;
	  	formulario.submit();
	}
	
	//Funcion que cambia la cantidad al modificar el dinero de desperfectos
	function modificar_total_factura(pago,campo_total,desperfecto) {
		//alert(pago);
	  	if (desperfecto.value == '') {
		  	campo_total.value = parseFloat(pago) + ' €';
		}
		else if (!isNaN(parseFloat(desperfecto.value))) {
		  	campo_total.value = (parseFloat(pago) + parseFloat(desperfecto.value)) + ' €';
		}
	}

</script>

<?PHP

	$tipo_persona = 'Grupo';
	
	if (isset($_SESSION['factura_grupo'])) {
	  	if (($_SESSION['factura_grupo']['Nombre_Gr'] != $_SESSION['gdh']['detalles']['de_clave'])||($_SESSION['factura_grupo']['Fecha_Llegada'] != $_SESSION['gdh']['detalles']['de_fecha_llegada'])) {
		    unset($_SESSION['factura_grupo']);
		}
	}
	
	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
	  	//echo $_SESSION['gdh']['detalles']['de_clave'].'<br>'.$_SESSION['gdh']['detalles']['de_fecha_llegada'].'<br>';  	
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$sql = "SELECT * FROM grupo LEFT JOIN estancia_gr ON grupo.Nombre_Gr=estancia_gr.Nombre_Gr WHERE estancia_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND estancia_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
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
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
					Factura de Grupo
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
			
			@ ARRAY_MULTISORT($buscar_ultima_factura, SORT_DESC, $last);
			
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
					<td rowspan="11" width="5px">
					</td>
				</tr>
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
									Pernoctas del grupo:
								</td>
							</tr>
							<tr>
								<td>	
									<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['factura_agrupada']; ?>;">
										<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
											<thead class="fixedHeader">
												<tr>
													<th align="left">
														Habitación
													</th>
													<th align="left">
														Edad
													</th>
													<th align="left">
														Nº Personas
													</th>
													<th align="left">
														Nº Exentos
													</th>
													<th align="left">
														Pagado
													</th>
												</tr>
											</thead>
											<tbody class="scrollContent">
									
								
<?PHP
	  	$_SESSION['factura_grupo']['Nombre_Gr'] = $_SESSION['gdh']['detalles']['de_clave'];
	  	$_SESSION['factura_grupo']['Fecha_Llegada'] = $_SESSION['gdh']['detalles']['de_fecha_llegada'];

		$sql = "SELECT * FROM pernocta_gr,edad WHERE edad.Id_Edad LIKE pernocta_gr.Id_Edad AND pernocta_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND pernocta_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' ORDER BY pernocta_gr.Id_Hab,pernocta_gr.Id_Edad;";
	  	$pernocta_gr = MYSQL_QUERY($sql);
	  	//echo $sql;
		
		// Mirar si tiene servicios adicionales (Media Pensión, etc...)		
		$plus = 0;
		
		// Mirar si hay exentos y cuanto tiene que devolver el albergue al grupo
		$pagado = 0;
		$nuevo_pago = 0;
				
	  	for ($i = 0; $i < MYSQL_NUM_ROWS($pernocta_gr); $i++) {
	  	  	$pernocta_gr_individual = MYSQL_FETCH_ARRAY($pernocta_gr);	  	  	
?>
		    									<tr class="texto_listados" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>">
		  											<td align="right">
<?PHP
			echo $pernocta_gr_individual['Id_Hab'];
			$_SESSION['factura_grupo']['Id_Hab'][$i] = $pernocta_gr_individual['Id_Hab'];
?>
											  		</td>
											  		<td align="left">	  		
<?PHP
			echo $pernocta_gr_individual['Nombre_Edad'];
			$_SESSION['factura_grupo']['Id_Edad'][$i] = $pernocta_gr_individual['Id_Edad'];
			
			// Mirar si tiene servicios adicionales (Media Pensión, etc...)
			if (isset($fila['Id_Servicios'])) {
			  	$sql = "SELECT * FROM servicios WHERE Id_Servicios LIKE '".$fila['Id_Servicios']."' AND Id_Edad LIKE '".$pernocta_gr_individual['Id_Edad']."';";
			  	$servicio = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
			  	$plus += $servicio['Precio'] * $pernocta_gr_individual['Num_Personas'];
			}	
?>
											  		</td>
											  		<td align="right">	  		
<?PHP 
			echo $pernocta_gr_individual['Num_Personas'];
			if (isset($_POST['facturados_'.$_SESSION['factura_grupo']['Id_Hab'][$i].'_'.$_SESSION['factura_grupo']['Id_Edad'][$i]])) {
			  	$_SESSION['factura_grupo']['Num_Facturados'][$i] = $_POST['facturados_'.$_SESSION['factura_grupo']['Id_Hab'][$i].'_'.$_SESSION['factura_grupo']['Id_Edad'][$i]];
			}
			else {
				$_SESSION['factura_grupo']['Num_Facturados'][$i] = $pernocta_gr_individual['Num_Personas'];		  
			}
			$_SESSION['factura_grupo']['Num_Personas'][$i] = $pernocta_gr_individual['Num_Personas'];
?>
											  		</td>
											  		<td align="right">
		  												<select class="detalles_select" name="exentos_<?PHP echo $pernocta_gr_individual['Id_Hab'].'_'.$pernocta_gr_individual['Nombre_Edad']; ?>" onchange="exentos_facturados(<?PHP echo $pernocta_gr_individual['Num_Personas']; ?>,this,facturados_<?PHP echo $pernocta_gr_individual['Id_Hab'].'_'.$pernocta_gr_individual['Id_Edad']; ?>,'<?PHP echo $fila['Nombre_Gr']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
<?PHP
			for ($j = 0; $j <= $pernocta_gr_individual['Num_Personas']; $j++) {
			  	if ($j == $_SESSION['factura_grupo']['Num_Personas'][$i] - $_SESSION['factura_grupo']['Num_Facturados'][$i]) {
?>
						    								<option value="<?PHP echo $j;?>" selected><?PHP echo $j;?>
<?PHP
				}
				else {
?>
						    								<option value="<?PHP echo $j;?>"><?PHP echo $j;?>
<?PHP				  
				}
			}
?>			
		  												</select>	  			
											  		</td>
<?PHP
			$sql_tar = "SELECT Tarifa FROM tarifa WHERE Id_Edad LIKE '".$pernocta_gr_individual['Id_Edad']."' AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fila['Fecha_Llegada']."' AND Id_Hab = '".$pernocta_gr_individual['Id_Hab']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
			$result_tar = MYSQL_QUERY($sql_tar);
			$tarifa_tar = MYSQL_FETCH_ARRAY($result_tar);
			
			$pagado += $_SESSION['factura_grupo']['Num_Personas'][$i] * ($tarifa_tar['Tarifa'] + $plus) * $fila['Noches_Pagadas'];
			$nuevo_pago += $_SESSION['factura_grupo']['Num_Facturados'][$i] * ($tarifa_tar['Tarifa'] + $plus) * $fila['Noches_Pagadas'];
?>
											  		<td align="right">		  		
											  			<input type=hidden name="facturados_<?PHP echo $pernocta_gr_individual['Id_Hab'].'_'.$pernocta_gr_individual['Id_Edad']; ?>" value="<?PHP echo $_SESSION['factura_grupo']['Num_Facturados'][$i]; ?>">
											  			<input type=text size=2 class="input_text_numerico" name="pagado_<?PHP echo $pernocta_gr_individual['Id_Hab'].'_'.$pernocta_gr_individual['Id_Edad']; ?>" value="<?PHP echo $_SESSION['factura_grupo']['Num_Personas'][$i] * ($tarifa_tar['Tarifa'] + $plus) * $fila['Noches_Pagadas']; ?>">
											  		</td>
											    </tr>
<?PHP
		}
		
		if (MYSQL_NUM_ROWS($pernocta_gr) < $_SESSION['gdh']['factura']['configuracion_numero_filas']) {
	  		for ($i = MYSQL_NUM_ROWS($pernocta_gr); $i < $_SESSION['gdh']['factura']['configuracion_numero_filas']; $i++) {
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
						<table border="0" width="90%">
							<tr>
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
								<td align="right">
									<IMG src='../imagenes/botones/lupa.png' border=0 width='20px' style='position:relative;top:3px;cursor:pointer;' onclick='window.open("paginas/gdh_busq_componentes_grupo.php?Nombre_Gr=<?PHP echo $_SESSION['gdh']['detalles']['de_clave']; ?>&Fecha_Llegada=<?PHP echo $_SESSION['gdh']['detalles']['de_fecha_llegada']; ?>","_blank", "width=550px,height=550px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");'>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						D.N.I.:
					</td>
					<td align="left">
<?PHP
		if (isset($_POST['dni_factura'])) {
?>
						<input type="text" name="dni_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_POST['dni_factura']; ?>">
<?PHP
		}
		else {
?>
						<input type="text" name="dni_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['DNI_Repres']; ?>">
<?PHP
		}
?>
						
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
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP echo $_POST['nombre_factura']; ?>">
<?PHP
		}
		else {
?>
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Nombre_Repres']; ?>">
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
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_POST['apellido1_factura']; ?>">
<?PHP
		}
		else {
?>
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Apellido1_Repres']; ?>">
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
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_POST['apellido2_factura']; ?>">
<?PHP
		}
		else {
?>
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Apellido2_Repres']; ?>">
<?PHP
		}
?>						
					</td>						
				</tr>
<?PHP
		$sql = "SELECT * FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		//echo $sql;
		$total_estancias = MYSQL_QUERY($sql);
		$dinero_noche = 0;
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($total_estancias); $i++) {
		  	$estancia = MYSQL_FETCH_ARRAY($total_estancias);
			$sql = "SELECT Tarifa FROM tarifa WHERE Id_Edad LIKE '".$_SESSION['factura_grupo']['Id_Edad'][$i]."' AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fila['Fecha_Llegada']."' AND Id_Hab = '".$_SESSION['factura_grupo']['Id_Hab'][$i]."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
			//echo $sql;
			$result = MYSQL_QUERY($sql);
			$tarifa = MYSQL_FETCH_ARRAY($result);
			$dinero_noche += $tarifa['Tarifa'] * $_SESSION['factura_grupo']['Num_Facturados'][$i];
		}
?>
				<tr>
					<td class="texto_detalles" align="left">
						Desperfecto:
					</td>
					<td align="left">
<?PHP
		if (isset($_POST['desperfecto_factura'])) {
?>
						<input type="text" name="desperfecto_factura" size=20 maxlength=255 contenteditable=true class="input_text_vacio" value="<?PHP echo $_POST['desperfecto_factura']; ?>">
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
		  	$desperfecto = $_POST['cuantia_desperfecto_factura'];
?>
						<input type="text" name="cuantia_desperfecto_factura" size=6 maxlength=7 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $_POST['cuantia_desperfecto_factura']; ?>" onkeyup="modificar_total_factura('<?PHP echo $nuevo_pago - $pagado; ?>',total_importe_factura,cuantia_desperfecto_factura);">
<?PHP
		}
		else {
		  	$desperfecto = 0;
?>
						<input type="text" name="cuantia_desperfecto_factura" size=6 maxlength=7 contenteditable=true class="input_text_vacio_numerico" value="0.0" onkeyup="modificar_total_factura('<?PHP echo $nuevo_pago - $pagado; ?>',total_importe_factura,cuantia_desperfecto_factura);">
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
						<input type="hidden" name="resto_factura" value="<?PHP echo $nuevo_pago; ?>">
						<input type="text" name="total_importe_factura" size=9 contenteditable=false style="border-style:2px solid;border-color:#064C87;background-color:<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;color:#064C87;font-size:15px;font-weight:bold;text-align:right;" value="<?PHP echo ($nuevo_pago - $pagado).' €'; ?>">
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
									<img src="./../imagenes/botones-texto/facturar.jpg" class="imagen-boton" title="Crear Nueva Factura" value="Facturar" onclick="comprueba_dni('FGN');">
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
									<input type="hidden" name="habitacion_factura" value="<?PHP echo $habitaciones; ?>">
									<input type="hidden" name="nochespagadas_factura" value="<?PHP echo $fila['Noches_Pagadas']; ?>">
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
