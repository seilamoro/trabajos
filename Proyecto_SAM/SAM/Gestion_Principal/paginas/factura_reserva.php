<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$sql = "SELECT * FROM pra LEFT JOIN detalles ON pra.DNI_PRA=detalles.DNI_PRA WHERE detalles.DNI_PRA LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND detalles.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
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
					Factura de una Reserva
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
		
		$sql = "SELECT * FROM reserva WHERE DNI_PRA LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";	
		$result = MYSQL_QUERY($sql);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		 	$_SESSION['genera_reserva'][$i]['fila'] = MYSQL_FETCH_ARRAY($result);
		}		
		
?>
				<tr>
					<td rowspan="10" width="5px">
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
						<input type="text" name="dni_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['DNI_PRA']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Nombre:
					</td>
					<td align="left">
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Nombre_PRA']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						1<sup>er</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Apellido1_PRA']; ?>">
					</td>						
					<td class="texto_detalles" align="left">
						2<sup>o</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $fila['Apellido2_PRA']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Salida:
					</td>
					<td align="left">
						<input type="text" name="fechallegada_factura" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Llegada']); ?>">
					</td>
					<td class="texto_detalles" align="left">
						Ingreso:
					</td>
					<td align="left">
						<input type="text" name="importepagado_factura" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Ingreso']." €"; ?>">
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
									<img src="./../imagenes/botones-texto/facturar.jpg" class="imagen-boton" title="Crear Nueva Factura" value="Facturar" onclick="comprueba_dni('R');">
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
