<script language="JavaScript">

	// Cuando seleccionamos el número de exentos en una factura, nos cambia el campo de facturados
	function cambiar_factura_atras(clave,fecha) {
	  	formulario.gdh_dis.value = 'FD';
	  	formulario.gdh_fac.value = 'G' + '*' + clave + '*' + fecha;
	  	formulario.submit();
	}
	
	// Cuando seleccionamos el número de exentos en una factura, nos cambia el campo de facturados
	function cambiar_factura(clave,fecha) {
	  	formulario.gdh_dis.value = 'FDR';
	  	formulario.gdh_fac.value = 'G' + '*' + clave + '*' + fecha;
	  	formulario.submit();
	}

</script>

<?PHP

	$tipo_persona = 'grupo';
	
	if (isset($_POST['modificar_factura_actual'])) { // Si cambiamos la vista de la factura, actualizamos todos los campos modificados de la
													 // que teniamos en pantalla.
		
		//IMPORTANTE///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//IMPORTANTE: SI HACEMOS UN CAMBIO DENTRO DE ESTE IF, EL CAMBIO DEBE HACERSE EN EL MISMO CODIGO DE LA PÁGINA gdh_session.php
		//IMPORTANTE///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  	/*echo $_POST['modificar_factura_actual'].'<br>';
	  	echo $_SESSION['factura_grupo_desglosada']['Num_Facturas'].'<br>';
	  	echo $_POST['numero_factura'].'<br>';
	  	echo $_POST['anio_factura'].'<br>';
	  	echo $_POST['fecha_factura'].'<br>';
	  	echo $_POST['dni_factura'].'<br>';
	  	echo $_POST['nombre_factura'].'<br>';
	  	echo $_POST['apellido1_factura'].'<br>';
	  	echo $_POST['apellido2_factura'].'<br>';
	  	echo $_POST['desperfecto_factura'].'<br>';
	  	echo $_POST['cuantia_desperfecto_factura'].'<br>';
	  	echo $_POST['resto_factura'].'<br>';*/
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['num_factura'] = $_POST['numero_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['anio_factura'] = $_POST['anio_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['fecha_factura'] = $_POST['fecha_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['dni_factura'] = $_POST['dni_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['nombre_factura'] = $_POST['nombre_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['apellido1_factura'] = $_POST['apellido1_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['apellido2_factura'] = $_POST['apellido2_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['desperfecto_factura'] = $_POST['desperfecto_factura'];
	  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['cuantia_factura'] = $_POST['cuantia_desperfecto_factura'];
	  	
		if (isset($_POST['facturado_exento'])) {
			
	  	  	//echo $_POST['facturado_exento'].'<br>';
	  	  	$aux = SPLIT('-',$_POST['facturado_exento']);
	  	  	//echo $aux[0].'<br>';
	  	  	//echo $aux[1].'<br>';
	  	  	//echo $aux[2].'<br>';
	  	  			  	
	  	  	if (($_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_id_hab'] == $aux[1])&&
	  	  		($_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_id_edad'] == $aux[2])&&
	  	  		((($_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_facturados'] > 0)&&
				  ($aux[0] == 'f'))
				 ||
				 (($_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_exentos'] > 0)&&
				  ($aux[0] == 'e'))
				)) {
				//echo 'no hay cambios';
			}
			else {				  		  	  	
		  	  	$antigua_id_hab = $_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_id_hab'];
		  	  	$antigua_id_edad = $_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_id_edad'];
		  	  	if ($_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_facturados'] > 0) {
					$antigua_seleccion = 'f';
				}
				else if ($_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_exentos'] > 0) {
					$antigua_seleccion = 'e';
				}
				else {
				  	$antigua_seleccion = 'null';
				}
				
				@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

				if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
				}
				else {
				  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
				  	$sql = "SELECT Tarifa FROM tarifa WHERE Id_Edad LIKE '".$aux[2]."' AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' AND Id_Hab = '".$aux[1]."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
					//echo $sql;
					$result = MYSQL_QUERY($sql);
					$tarifa = MYSQL_FETCH_ARRAY($result);
					$dinero_noche += $tarifa['Tarifa'];	
					MYSQL_CLOSE($db);
				}
				
				$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_id_hab'] = $aux[1];
			  	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_id_edad'] = $aux[2];

		  	  	if ($aux[0] == 'f') {
					$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['ingreso_factura'] = ($_SESSION['factura_grupo_desglosada']['PerNocta'] * $dinero_noche).' €';
	  	  	  		$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_facturados'] = 1;
		  			$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_exentos'] = 0;
		  			$_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$aux[1].'-'.$aux[2]]['numero_facturados']++;
		  			if ($antigua_seleccion == 'f') {
					    $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$antigua_id_hab.'-'.$antigua_id_edad]['numero_facturados']--;
		  			}
					else if ($antigua_seleccion == 'e') {
					    $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$antigua_id_hab.'-'.$antigua_id_edad]['numero_exentos']--;
		  			}  		
			  	}
			  	else if ($aux[0] == 'e') {
			  		$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['ingreso_factura'] = '0 €';
			    	$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_exentos'] = 1;
			  		$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['genera_num_facturados'] = 0;
		  			$_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$aux[1].'-'.$aux[2]]['numero_exentos']++;
		  			if ($antigua_seleccion == 'f') {
					    $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$antigua_id_hab.'-'.$antigua_id_edad]['numero_facturados']--;
		  			}
					else if ($antigua_seleccion == 'e') {
					    $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$antigua_id_hab.'-'.$antigua_id_edad]['numero_exentos']--;
		  			}
				}
			}
		}
		//IMPORTANTE///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//IMPORTANTE: SI HACEMOS UN CAMBIO DENTRO DE ESTE IF, EL CAMBIO DEBE HACERSE EN EL MISMO CODIGO DE LA PÁGINA gdh_session.php
		//IMPORTANTE///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	
	/* CREAMOS LA FACTURA DEL RESTO A PARTIR DE LO QUE HEMOS SELECCIONADO EN LAS FACTURAS INDIVIDUALES.*/
		
	// SI SE MODIFICA ESTO HAY QUE MODIFICAR LO MISMO EN factura_grupo_desglosada_resumen.php
	
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
	
	if (isset($_POST['volver_factura_resto'])) {
		//vengo de la pagina del resumen, por lo que no debo modificar los exentos.
	}	
	else {
		for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']); $i++) {
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total'] = $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Num_Personas'] - ($_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_facturados'] + $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_exentos']);
			if (isset($_POST['factura_resto_exentos_'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']])) {
			    $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_exentos'] = $_POST['factura_resto_exentos_'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']];
			}
		  	else {
			    $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_exentos'] = 0;
			}
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_facturados'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total'] - $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_exentos'];
			//echo 'Habitacion '.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].' y edad '.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad'].':<br>';
			//echo 'Total: '.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total'].'<br>';
			//echo 'Facturados: '.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_facturados'].'<br>';
			//echo 'Exentos: '.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_exentos'].'<br>';
		}
	}
	
	// FIN SI SE MODIFICA ...
		
	/* FIN CREAMOS LA FACTURA...*/

	
	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
		MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  
	  	/* Calculamos el ingreso que se debe realizar para la factura del resto del grupo */
  	
	  	$ingreso_resto = 0;
	  	$dinero_pagado = 0;
	  	
	  	for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']); $i++) {
			$sql = "SELECT * FROM tarifa WHERE Id_Edad LIKE '".$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']."' AND Id_Tipo_Hab IN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' AND Id_Hab = '".$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE '".$tipo_persona."');";
			$tarifa_result = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
			$tarifa = $tarifa_result['Tarifa'];
			//echo $sql.'<br>';
			if (isset($_SESSION['factura_grupo_desglosada']['regimen'])) {
			  	$sql = "SELECT * FROM servicios WHERE Id_Servicios LIKE '".$_SESSION['factura_grupo_desglosada']['regimen']."' AND Id_Edad LIKE '".$tarifa_result['Id_Edad']."';";
			  	$servicio = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
			  	$plus = $servicio['Precio'];
			}
			else {
			  	$plus = 0;
			}		
			$ingreso_resto += $tarifa * $_SESSION['factura_grupo_desglosada']['PerNocta'] * ($_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total'] - $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_exentos']) + $plus * $_SESSION['factura_grupo_desglosada']['PerNocta'] * $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total'];
			$dinero_pagado += ($tarifa + $plus) * $_SESSION['factura_grupo_desglosada']['Noches_Pagadas'] * $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total'];
		}	
	  	
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'] = $ingreso_resto;
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['pagado_factura'] = $dinero_pagado;
	  	
	  	/* Fin calculamos el ingreso... */
	  
	  	//echo $_SESSION['gdh']['detalles']['de_clave'].'<br>'.$_SESSION['gdh']['detalles']['de_fecha_llegada'].'<br>';
	  	$sql = "SELECT * FROM grupo LEFT JOIN estancia_gr ON grupo.Nombre_Gr=estancia_gr.Nombre_Gr WHERE estancia_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND estancia_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
	  	//echo $sql;
		$result = MYSQL_QUERY($sql);
		$fila = MYSQL_FETCH_ARRAY($result);
		//echo $_POST['desglosada_n_facturas'];
?>

<table border="0" id="tabla_detalles" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
					Factura de Grupo Desglosada. Factura del resto de la estancia:
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<table border="0" height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_alto_facturas']; ?>" width="100%" style="border: 1px solid #3F7BCC;">
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
									<input type="text" name="numero_factura" size=4 maxlength=5 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['num_factura']; ?>">
								</td>
								<td class="texto_detalles"><div class="input_text">-</div>
								</td>
								<td class="texto_detalles">
									<input type="text" name="anio_factura" size=2 maxlength=4 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['anio_factura']; ?>">
								</td>
							</tr>
						</table>
					</td>
					<td rowspan="9" valign="top" align="left">
						<table border="0">
							<tr>
								<td class="texto_detalles" align="left" colspan="3">
									Pernocta para la factura del resto de la estancia de grupo:
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
														Facturados
													</th>
													<th align="left">
														Facturar
													</th>
													<th align="left">
														Exento
													</th>
												</tr>
											</thead>
											<tbody class="scrollContent">								
<?PHP
		  	for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']); $i++) {	  	  	
?>
		    									<tr class="texto_listados" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>">
		  											<td align="right">
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Id_Hab'];
?>
											  		</td>
											  		<td align="left">	  		
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Nombre_Edad'];
?>
											  		</td>
											  		<td align="right">	  		
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Num_Personas'];
?>
											  		</td>											  		
											  		<td align="right">
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_facturados'] + $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_exentos'];
?>
											  		</td>
											  		<td align="right">
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total'];				
?>	
											  		</td>
											  		<td align="center">
		  												<select onchange="cambiar_factura('<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>');" name="factura_resto_exentos_<?PHP echo $_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab']?>-<?PHP echo $_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']?>" class="detalles_select">
<?PHP
				for ($j = 0; $j <= $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['total']; $j++) {
				  	if ($j == $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_exentos']) {
?>
															<option value="<?PHP echo $j; ?>" selected><?PHP echo $j; ?>
<?PHP
					}
					else {
?>
															<option value="<?PHP echo $j; ?>"><?PHP echo $j; ?>
<?PHP
					}
				}
?>	
		  												</select>
											  		</td>
											    </tr>
<?PHP
			}		
			if (COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']) < $_SESSION['gdh']['factura']['configuracion_numero_filas']) {
		  		for ($i = COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']); $i < $_SESSION['gdh']['factura']['configuracion_numero_filas']; $i++) {
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
									<input type="text" name="fecha_factura" size=9 maxlength=10 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['fecha_factura']; ?>">
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
						<input type="text" name="dni_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['dni_factura']; ?>">						
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre:
					</td>
					<td align="left">
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['nombre_factura']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						1<sup>er</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido1_factura']; ?>">
					</td>
				</tr>
				<tr>					
					<td class="texto_detalles" align="left">
						2<sup>o</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido2_factura']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Desperfecto:
					</td>
					<td align="left">
						<input type="text" name="desperfecto_factura" size=20 maxlength=255 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['desperfecto_factura']; ?>">
					</td>	
				</tr>
				<tr>												
					<td class="texto_detalles" align="left">
						Cuantía:
					</td>
					<td align="left">
						<input type="text" name="cuantia_desperfecto_factura" size=6 maxlength=7 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura']; ?>">
					</td>												
				</tr>
				<tr>
					<td class="texto_detalles" align="center" colspan="4">
						<img src="./../imagenes/botones-texto/volver.jpg" class="imagen-boton" onclick="cambiar_factura_atras('<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>');">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="./../imagenes/botones-texto/continuar.jpg" class="imagen-boton" onclick="tipo_factura('FDRM');">
						<input type="hidden" name="modificar_factura_resto" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['factura_actual']; ?>">
						<input type="hidden" name="resto_factura" class="input_text" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'].' €'; ?>">
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
