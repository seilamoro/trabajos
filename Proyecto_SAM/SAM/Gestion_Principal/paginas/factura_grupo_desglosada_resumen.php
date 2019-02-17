<script language="JavaScript">

	// Nos lleva a la factura que queremos modificar.
	function modificar_factura(valor,clave,fecha) {
	  	formulario.desglosada_factura.value = valor;
	  	cambiar_factura(clave,fecha);
	}

	// Cuando seleccionamos el número de exentos en una factura, nos cambia el campo de facturados
	function cambiar_factura(clave,fecha) {
	  	formulario.gdh_dis.value = 'FD';
	  	formulario.gdh_fac.value = 'G' + '*' + clave + '*' + fecha;
	  	formulario.submit();
	}

</script>

<?PHP

	// SI SE MODIFICA ESTO HAY QUE MODIFICAR LO MISMO EN factura_grupo_desglosada_resto.php
	
	if (isset($_POST['modificar_factura_resto'])) {
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['num_factura'] = $_POST['numero_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['anio_factura'] = $_POST['anio_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['fecha_factura'] = $_POST['fecha_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['dni_factura'] = $_POST['dni_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['nombre_factura'] = $_POST['nombre_factura'];
		$_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido1_factura'] = $_POST['apellido1_factura'];
		$_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido2_factura'] = $_POST['apellido2_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['desperfecto_factura'] = $_POST['desperfecto_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura'] = $_POST['cuantia_desperfecto_factura'];
	  	//echo '<br>'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura'].'<br>';
	  	//$_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'] = $_POST['resto_factura'];
	}
		
	// FIN SI SE MODIFICA ...

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {	  	
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$tipo_persona = "Grupo";
	  		  	
	  	$total_factura = 0;
	  	$pagado_factura = 0;
	  	$resto_factura = 0;
	  	
	  	/* Comprobamos si tenían ingreso anterior de una reserva, pero no figuran noches pagadas */	  	
	  	$sql = "SELECT Ingreso,Noches_Pagadas FROM estancia_gr WHERE Nombre_Gr LIKE '".$_SESSION['factura_grupo_desglosada']['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_SESSION['factura_grupo_desglosada']['Fecha_Llegada']."';";
	  	$comprobacion_ingreso = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
	  	
	  	if (($comprobacion_ingreso['Noches_Pagadas'] == 0)&&($comprobacion_ingreso['Ingreso'] > 0)) {
		    $pagado_factura += $comprobacion_ingreso['Ingreso'];
		    $resto_factura -= $pagado_factura;
		}  	
	  	/* Fin Comprobamos si ... */	  	
?>
	    
<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
					Resumen de la Factura Desglosada:
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<table border="0" height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_alto_facturas']; ?>" width="100%" style="border: 1px solid #3F7BCC;">
				<tr>
					<td rowspan="5" width="5px">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Resumen de las facturas individuales y del resto:
					</td>
				</tr>
				<tr>
					<td valign="top">	
						<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_resumen_desglosada']; ?>;">
							<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
								<thead class="fixedHeader">
									<tr>
										<th align="right" width="87px">
											Nº Factura
										</th>
										<th align="left" width="87px">
											D.N.I.
										</th>
										<th align="center">
											Exento
										</th>
										<th align="right" width="30px">
											Hab.
										</th>
										<th align="left" width="103px">
											Edad
										</th>
										<th align="right" width="55px">
											Noches Pagadas
										</th>
										<th align="right" width="60px">
											Tarifa
										</th>
										<th align="right" width="60px">
											Total
										</th>
										<th align="right" width="60px">
											Pagado
										</th>
										<th align="right" width="66x">
											Resto
										</th>
									</tr>
								</thead>
								<tbody class="scrollContent">
									<input type="hidden" name="desglosada_factura" value=""/>								
<?PHP
		for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Factura']); $i++) {
?>
									<tr class="texto_listados" title="Modificar Factura" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
										<td align="right" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			echo $_SESSION['factura_grupo_desglosada']['Factura'][$i]['num_factura'].'-'.$_SESSION['factura_grupo_desglosada']['Factura'][$i]['anio_factura'];
?>
								  		</td>
								  		<td align="left" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">	
<?PHP
			if ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['dni_factura'] != "") {
				echo $_SESSION['factura_grupo_desglosada']['Factura'][$i]['dni_factura'];
			}
			else {
			  	echo '- - - - - - - -';
			}
?>
								  		</td>
								  		<td align="center" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			if ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_exentos'] > 0) {
			  	$es_exento = true;
			  	echo 'SI';
			}
			else { 
			  	$es_exento = false;
			  	echo 'NO';		
			}
?>
								  		</td>											  		
								  		<td align="right" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			echo $_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_hab'];
?>
								  		</td>
								  		<td align="left" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			$sql = "SELECT Nombre_Edad FROM edad WHERE Id_Edad LIKE '".$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_edad']."'";
			$edad_result = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
			echo $edad_result['Nombre_Edad'];
?>	
								  		</td>
								  		<td align="right" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			echo $_SESSION['factura_grupo_desglosada']['Noches_Pagadas'].' / '.$_SESSION['factura_grupo_desglosada']['PerNocta'];
?>
								  		</td>
								  		<td align="right" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			$sql_tarifa = "SELECT * FROM tarifa WHERE Id_Edad LIKE '".$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_edad']."' AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' AND Id_Hab = '".$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_hab']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
			$tarifa_result = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_tarifa));
			$_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] = $tarifa_result['Tarifa'];
			if (isset($_SESSION['factura_grupo_desglosada']['regimen'])) {
			  	$sql = "SELECT * FROM servicios WHERE Id_Servicios LIKE '".$_SESSION['factura_grupo_desglosada']['regimen']."' AND Id_Edad LIKE '".$tarifa_result['Id_Edad']."';";
			  	//echo $sql;
				$servicio = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
			  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'] = $servicio['Precio'];
			}
			else {
			  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'] = 0;
			}
			if ($es_exento) {
			  	echo $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'].' €';
			}
			else {
			  	echo ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']).' €';
			}			
?>
								  		</td>
								  		<td align="right" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			if ($es_exento) {
				echo ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'] * $_SESSION['factura_grupo_desglosada']['PerNocta']).' €';
				$_SESSION['factura_grupo_desglosada']['Factura'][$i]['ingreso_factura'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'] * $_SESSION['factura_grupo_desglosada']['PerNocta'];
				$total_factura += $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'] * $_SESSION['factura_grupo_desglosada']['PerNocta'];
			}
			else {
				echo (($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['PerNocta']).' €';
				$_SESSION['factura_grupo_desglosada']['Factura'][$i]['ingreso_factura'] = ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['PerNocta'];
				$total_factura += ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['PerNocta'];
			}
?>
								  		</td>
								  		<td align="right" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP
			echo (($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['Noches_Pagadas']).' €';
			$pagado_factura += ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['Noches_Pagadas'];
?>
								  		</td>
								  		<td align="right" onclick="modificar_factura(<?PHP echo $i; ?>,'<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>')">
<?PHP		if ($es_exento) {
				echo (($_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'] * $_SESSION['factura_grupo_desglosada']['PerNocta']) - ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['Noches_Pagadas'] + FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura'][$i]['cuantia_factura'])).' €';
				$resto_factura += (($_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura'] * $_SESSION['factura_grupo_desglosada']['PerNocta']) - ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['Noches_Pagadas']+ FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura'][$i]['cuantia_factura']));
			}
			else {
				echo (($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['PerNocta'] - ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['Noches_Pagadas'] + FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura'][$i]['cuantia_factura'])).' €';
				$resto_factura += (($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['PerNocta'] - ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] + $_SESSION['factura_grupo_desglosada']['Factura'][$i]['plus_tarifa_factura']) * $_SESSION['factura_grupo_desglosada']['Noches_Pagadas'] + FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura'][$i]['cuantia_factura']));		
			}
?>
								  		</td>
								    </tr>
<?PHP
		}
?>

<?PHP // Factura del resto.  ?>
									<tr class="texto_listados" title="Modificar Factura" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
										<td align="right" onclick="tipo_factura('FDR');">
<?PHP
			echo '(R)'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['num_factura'].'-'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['anio_factura'];
?>
								  		</td>
								  		<td align="left" onclick="tipo_factura('FDR');">	  		
<?PHP
			if ($_SESSION['factura_grupo_desglosada']['Factura_Resto']['dni_factura'] != "") {
				echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['dni_factura'];
			}
			else {
			  	echo '- - - - - - - -';
			}
?>
								  		</td>
								  		<td align="center" onclick="tipo_factura('FDR');">	  		
<?PHP
			$num_exentos = 0;
			$num_facturados = 0;
			for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista']); $i++) {
			  	$num_exentos += $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_hab'].'-'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_edad']]['num_exentos'];
				$num_facturados += $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_hab'].'-'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_edad']]['total'];	
			}
			
			if ($num_exentos == 0) {
			  	echo 'NO';
			}
			else { 
			  	echo $num_exentos.' / '.$num_facturados;		
			}
?>
								  		</td>											  		
								  		<td align="right" onclick="tipo_factura('FDR');">
<?PHP
			echo '*';
?>
								  		</td>
								  		<td align="left" onclick="tipo_factura('FDR');">
<?PHP
			echo '*';
?>	
								  		</td>
								  		<td align="right" onclick="tipo_factura('FDR');">
<?PHP
			echo $_SESSION['factura_grupo_desglosada']['Noches_Pagadas'].' / '.$_SESSION['factura_grupo_desglosada']['PerNocta'];
?>
								  		</td>
								  		<td align="right" onclick="tipo_factura('FDR');">
<?PHP
			echo '*';
?>
								  		</td>
								  		<td align="right" onclick="tipo_factura('FDR');">
<?PHP
			echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'].' €';
			$total_factura += $_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'];
?>
								  		</td>
								  		<td align="right" onclick="tipo_factura('FDR');">
<?PHP
			echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['pagado_factura'].' €';
			$pagado_factura += $_SESSION['factura_grupo_desglosada']['Factura_Resto']['pagado_factura'];
?>
								  		</td>
								  		<td align="right" onclick="tipo_factura('FDR');">
<?PHP
			echo ($_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'] - $_SESSION['factura_grupo_desglosada']['Factura_Resto']['pagado_factura'] + FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura'])).' €';
			$resto_factura += $_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'] - $_SESSION['factura_grupo_desglosada']['Factura_Resto']['pagado_factura'] + FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura']);
?>
								  		</td>
								    </tr>
<?PHP // Fin Factura del resto.  ?>

<?PHP		
		if (COUNT($_SESSION['factura_grupo_desglosada']['Factura']) + 1 < $_SESSION['gdh']['factura']['configuracion_numero_filas']) {
	  		for ($i = COUNT($_SESSION['factura_grupo_desglosada']['Factura']) + 1; $i < $_SESSION['gdh']['resumen_desglosada']['configuracion_numero_filas']; $i++) {
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
		MYSQL_CLOSE($db);
	}
?>
								</tbody>
							</table>							
						</div>
					</td>
				</tr>
				<tr height="50px">
					<td valign="top">	
						<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
							<tr class="texto_listados" valign="center">
								<td valign="center">Importe de la Factura:&nbsp;
								</td>									
								<td valign="center" width="140px"><?PHP echo $total_factura.' €'; ?>
								</td>
								<td valign="center">Importe Pagado:&nbsp;
								</td>
								<td valign="center" width="170px"><?PHP echo $pagado_factura.' €'; ?>
								</td>
								<td valign="center">Total:&nbsp;
								</td>
								<td valign="center">
									<div style="color:#064C87;font-size:15px;border:2px solid;border-color:#064C87;">&nbsp;<?PHP echo $resto_factura.' €'; ?>&nbsp;</div>
								</td>
							</tr>
						</table>							
					</td>
				</tr>			
				<tr>
					<td align="center">
						<table>
							<tr>
								<td class="texto_detalles">							
									<img src="./../imagenes/botones-texto/cancelar.jpg" class="imagen-boton" title="Cancelar" value="Cancelar" onclick="recargar_gdh_dis('H');">
								</td>
								<td width="50px">
								</td>
								<td class="texto_detalles">
									<img src="./../imagenes/botones-texto/volver.jpg" class="imagen-boton" onclick="tipo_factura('FDR');">
									<input type="hidden" name="volver_factura_resto" value="true"/>
								</td>
								<td width="50px">
								</td>
								<td>
									<img src="./../imagenes/botones-texto/facturar.jpg" class="imagen-boton" onclick="tipo_factura('FDFinal');">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>	
</table>