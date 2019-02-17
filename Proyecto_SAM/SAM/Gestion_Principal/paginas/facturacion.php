<?PHP
	if (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion'] == true) {

		@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
		
		// Si la factura es desglosada tenemos que calcular el ingreso total para meterlo en estancia_gr.
		$ingreso_total_desglosada = 0;
		
		if (!$db) {	  
			echo "ERROR de conexion a la base de datos.";
		}
		else {
		  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		  	if (isset($_SESSION['nueva_factura'])) {
			  	
				$sql_a['genera'] = array();
			  	$sql_a['pernocta'] = array();		  	
		  	  	
				for ($i = 0; $i < COUNT($_SESSION['nueva_factura']); $i++) {
				  	if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "R") { 
		  	  	  		$sql = "INSERT INTO factura(Num_Factura,DNI,Fecha_Factura,Nombre,Apellido1,Apellido2,Ingreso) VALUES('".$_SESSION['nueva_factura'][$i]['factura']['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['factura']['DNI']."','".$_SESSION['nueva_factura'][$i]['factura']['Fecha_Factura']."','".$_SESSION['nueva_factura'][$i]['factura']['Nombre']."','".$_SESSION['nueva_factura'][$i]['factura']['Apellido1']."',";
						if ($_SESSION['nueva_factura'][$i]['factura']['Apellido2'] == '') {
						  	$sql = $sql.'NULL,';
						}
						else {
						  	$sql = $sql."'".$_SESSION['nueva_factura'][$i]['factura']['Apellido2']."',";
						}
						$sql = $sql."'".$_SESSION['nueva_factura'][$i]['factura']['Ingreso']."');";
					}
					else { 
		  	  	  		$sql = "INSERT INTO factura(Num_Factura,DNI,Fecha_Factura,Desperfecto,Cuantia_Desperfecto,Nombre,Apellido1,Apellido2,Ingreso) VALUES('".$_SESSION['nueva_factura'][$i]['factura']['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['factura']['DNI']."','".$_SESSION['nueva_factura'][$i]['factura']['Fecha_Factura']."',";
						if ($_SESSION['nueva_factura'][$i]['factura']['Desperfecto'] == '') {
						  	$sql = $sql.'NULL,';
						}
						else {
						  	$sql = $sql."'".$_SESSION['nueva_factura'][$i]['factura']['Desperfecto']."',";
						}
						if (($_SESSION['nueva_factura'][$i]['factura']['Cuantia_Desperfecto'] == '')||($_SESSION['nueva_factura'][$i]['factura']['Cuantia_Desperfecto'] == NULL)) {
							$sql = $sql."NULL,'";
						}
						else {
						  	$sql = $sql.$_SESSION['nueva_factura'][$i]['factura']['Cuantia_Desperfecto'].",'";
						}
						$sql = $sql.$_SESSION['nueva_factura'][$i]['factura']['Nombre']."','".$_SESSION['nueva_factura'][$i]['factura']['Apellido1']."',";
						if ($_SESSION['nueva_factura'][$i]['factura']['Apellido2'] == '') {
						  	$sql = $sql.'NULL,';
						}
						else {
						  	$sql = $sql."'".$_SESSION['nueva_factura'][$i]['factura']['Apellido2']."',";
						}
						$sql = $sql."'".$_SESSION['nueva_factura'][$i]['factura']['Ingreso']."');";
					}
					
					// Si la factura es desglosada tenemos que calcular el ingreso total para meterlo en estancia_gr.
					$ingreso_total_desglosada += $_SESSION['nueva_factura'][$i]['factura']['Ingreso'];
					
					for ($j = 0; $j < COUNT($_SESSION['nueva_factura'][$i]['genera']); $j++) {
						
						//Para un alberguista individual:
						if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "A") {
							$sql_a['genera'][$j] = "INSERT INTO genera(DNI_Cl,Id_Hab,Fecha_Llegada,Num_Factura,Exento) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Exento']."');"; 					
							if ($_SESSION['nueva_factura'][$i]['genera'][$j]['Exento'] == "S") {
								$sql_a['pernocta'][$j] = "UPDATE pernocta SET Noches_Pagadas='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."',Ingreso='0.0' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
							}
							else {
								$sql_a['pernocta'][$j] = "UPDATE pernocta SET Noches_Pagadas='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."',Ingreso='".$_SESSION['nueva_factura'][$i]['factura']['Ingreso']."' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
							}
						}
						//Fin alberguista.
						
						//Para un peregrino individual:
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "P") {
							$sql_a['genera'][$j] = "INSERT INTO genera_p(DNI_Cl,Id_Hab,Fecha_Llegada,Num_Factura) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."');"; 					
							$sql_a['pernocta'][$j] = "UPDATE pernocta_p SET Noches_Pagadas='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
						}
						//Fin peregrino.
						
						//Para un grupo, factura normal:
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "FG") {
							$sql_a['genera'][$j] = "INSERT INTO genera_gr(Nombre_Gr,Fecha_Llegada,Id_Hab,Num_Factura,Id_Edad,Num_Facturados,Num_Exentos) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Edad']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos']."');"; 					
							$sql_a['pernocta'][$j] = "UPDATE estancia_gr SET Noches_Pagadas='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."',Ingreso='".(FLOATVAL($_SESSION['nueva_factura'][$i]['genera'][$j]['resto']) + FLOATVAL($_SESSION['nueva_factura'][$i]['genera'][$j]['ingreso']))."' WHERE Nombre_Gr LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
						}
						//Fin normal grupo.
						
						//Para un grupo, factura de varias noches:
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "FGN") {
							$sql_a['genera'][$j] = "INSERT INTO genera_gr(Nombre_Gr,Fecha_Llegada,Id_Hab,Num_Factura,Id_Edad,Num_Facturados,Num_Exentos) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Edad']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos']."');"; 					
							$sql_a['pernocta'][$j] = "UPDATE estancia_gr SET Ingreso='".(FLOATVAL($_SESSION['nueva_factura'][$i]['genera'][$j]['resto']))."', Pernocta='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."', Fecha_Salida='".$_SESSION['nueva_factura'][$i]['genera'][$j]['cambio']."' WHERE Nombre_Gr LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
							$sql_a['insert_pernocta'][$j] = "INSERT INTO pernocta_gr(Nombre_Gr,Id_Hab,Id_Edad,Fecha_Llegada,Num_Personas) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Edad']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['cambio']."',".($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'] + $_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos']).");";
							$sql_grupo = "SELECT Nombre_Gr,Fecha_Llegada,Fecha_Salida,DNI_Repres,Nombre_Repres,Apellido1_Repres,Apellido2_Repres,Tfno_Repres,Ingreso,Noches_Pagadas,Num_Personas,Num_Mujeres,Num_Hombres,PerNocta,Llegada_Tarde,Gr0_9,Gr10_19,Gr20_25,Gr26_29,Gr30_39,Gr40_49,Gr50_59,Gr60_69,GrOtras,Id_Color,Tipo_documentacion,Fecha_Expedicion,Fecha_nacimiento,Id_Pais_nacionalidad FROM estancia_gr WHERE Nombre_Gr LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';";
							$fila_grupo = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_grupo));
							$sql_a['insert_estancia'][0] = "INSERT INTO estancia_gr(Nombre_Gr,Fecha_Llegada,Fecha_Salida,DNI_Repres,Nombre_Repres,Apellido1_Repres,Apellido2_Repres,Tfno_Repres,Ingreso,Noches_Pagadas,Num_Personas,Num_Mujeres,Num_Hombres,PerNocta,Llegada_Tarde,Gr0_9,Gr10_19,Gr20_25,Gr26_29,Gr30_39,Gr40_49,Gr50_59,Gr60_69,GrOtras,Id_Color,Tipo_documentacion,Fecha_Expedicion,Fecha_nacimiento,Id_Pais_nacionalidad) VALUES('".$fila_grupo['Nombre_Gr']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['cambio']."','".$fila_grupo['Fecha_Salida']."','".$fila_grupo['DNI_Repres']."','".$fila_grupo['Nombre_Repres']."','".$fila_grupo['Apellido1_Repres']."','".$fila_grupo['Apellido2_Repres']."','".$fila_grupo['Tfno_Repres']."',0,0,".$fila_grupo['Num_Personas'].",".$fila_grupo['Num_Mujeres'].",".$fila_grupo['Num_Hombres'].",".($fila_grupo['PerNocta'] - $fila_grupo['Noches_Pagadas']).",'".$fila_grupo['Llegada_Tarde']."',".$fila_grupo['Gr0_9'].",".$fila_grupo['Gr10_19'].",".$fila_grupo['Gr20_25'].",".$fila_grupo['Gr26_29'].",".$fila_grupo['Gr30_39'].",".$fila_grupo['Gr40_49'].",".$fila_grupo['Gr50_59'].",".$fila_grupo['Gr60_69'].",".$fila_grupo['GrOtras'].",".$fila_grupo['Id_Color'].",'".$fila_grupo['Tipo_documentacion']."','".$fila_grupo['Fecha_Expedicion']."','".$fila_grupo['Fecha_nacimiento']."','".$fila_grupo['Id_Pais_nacionalidad']."');";
							/*$sql_componentes = "SELECT * FROM componentes_grupo WHERE Nombre_Gr LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';";
							$result_componentes = MYSQL_QUERY($sql_componentes);
							$m = MYSQL_NUM_ROWS($result_componentes);
							if ($m > 0) {
								for ($n = 0; $n < $m; $n++) {
									$componente = MYSQL_FETCH_ARRAY($result_componentes);
									$sql_a['componentes'][$n] = "INSERT INTO componentes_grupo(DNI,Nombre_Gr,Fecha_Llegada,Tipo_documentacion,Fecha_Expedicion,Nombre,Apellido1,Apellido2,Sexo,Fecha_nacimiento,Id_Pais_nacionalidad) VALUES('".$componente['DNI']."','".$componente['Nombre_Gr']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['cambio']."','".$componente['Tipo_documentacion']."','".$componente['Fecha_Expedicion']."','".$componente['Nombre']."','".$componente['Apellido1']."','".$componente['Apellido2']."','".$componente['Sexo']."','".$componente['Fecha_nacimiento']."','".$componente['Id_Pais_nacionalidad']."');";
								}
							}*/
						}
						//Fin normal grupo.
	
						//Para un alberguista que paga parte de las noches:
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "FN") {
							$sql_a['genera'][$j] = "INSERT INTO genera(DNI_Cl,Id_Hab,Fecha_Llegada,Num_Factura,Exento) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Exento']."');"; 					
							if ($_SESSION['nueva_factura'][$i]['genera'][$j]['Exento'] == "S") {
								$sql_a['pernocta'][$j] = "UPDATE pernocta SET Fecha_Salida='".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Cambio']."',PerNocta='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."',Ingreso='0.0' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
								$sql_a['pernocta'][$j + 1] = "INSERT INTO pernocta(DNI_Cl,Id_Hab,Fecha_Llegada,Fecha_Salida,PerNocta,Noches_Pagadas,Ingreso) VALUES('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Cambio']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Salida']."',".($_SESSION['nueva_factura'][0]['genera'][0]['total_noches'] - $_SESSION['nueva_factura'][0]['genera'][0]['noches_pagadas']).",0,0);"; 
							}
							else {
								$sql_a['pernocta'][$j] = "UPDATE pernocta SET Fecha_Salida='".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Cambio']."',PerNocta='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."',Ingreso='".$_SESSION['nueva_factura'][$i]['factura']['Ingreso']."' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
								$sql_a['pernocta'][$j + 1] = "INSERT INTO pernocta(DNI_Cl,Id_Hab,Fecha_Llegada,Fecha_Salida,PerNocta,Noches_Pagadas,Ingreso) VALUES('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Cambio']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Salida']."',".($_SESSION['nueva_factura'][0]['genera'][0]['total_noches'] - $_SESSION['nueva_factura'][0]['genera'][0]['noches_pagadas']).",0,0);"; 
							}
						}
						//Fin alberguista parte noches.
						
						//Para una factura agrupada de varios alberguistas que no forman grupo:
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "FAA") {
							$sql_a['genera'][$j] = "INSERT INTO genera(DNI_Cl,Id_Hab,Fecha_Llegada,Num_Factura,Exento) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Exento']."');"; 					
							if ($_SESSION['nueva_factura'][$i]['genera'][$j]['Exento'] == "S") {
								$sql_a['pernocta'][$j] = "UPDATE pernocta SET Noches_Pagadas='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."',Ingreso='".$_SESSION['nueva_factura'][$i]['genera'][$j]['servicios']."' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
							}
							else {
								$sql_a['pernocta'][$j] = "UPDATE pernocta SET Noches_Pagadas='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."',Ingreso='".($_SESSION['nueva_factura'][$i]['genera'][$j]['ingreso'] + $_SESSION['nueva_factura'][$i]['genera'][$j]['servicios'])."' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
							}
						}
						//Fin agrupada alberguistas.
	
						//Para una factura agrupada de varios peregrinos:
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "FAP") {
							$sql_a['genera'][$j] = "INSERT INTO genera_p(DNI_Cl,Id_Hab,Fecha_Llegada,Num_Factura) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."');"; 					
							$sql_a['pernocta'][$j] = "UPDATE pernocta_p SET Noches_Pagadas='".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas']."' WHERE DNI_Cl LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_Cl']."' AND Id_Hab LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
						}
						//Fin agrupada peregrinos.
	
						//Para una factura de una reserva que no ha sido validada, es decir, que no se han presentado.
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "R") {
							$sql_a['genera'][$j] = "INSERT INTO genera_reserva(DNI_PRA,Id_Hab,Fecha_Llegada,Num_Factura,Presentado) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_PRA']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Presentado']."');"; 					
							$sql_a['pernocta'][$j] = "UPDATE detalles SET Fecha_Salida='".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."', Llegada_Tarde='NO PRESENTADO' WHERE DNI_PRA LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['DNI_PRA']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
						}
						//Fin factura de reserva.
	
						//Para una factura desglosada de un grupo.
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "FD") {
						  	//echo $_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos'].'<br>';
						  	//echo $_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'].'<br>';
							$sql_a['genera'][$j] = "INSERT INTO genera_gr(Nombre_Gr,Fecha_Llegada,Id_Hab,Num_Factura,Id_Edad,Num_Facturados,Num_Exentos) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Edad']."',";
							if (($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'] == "")||($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'] == NULL)) {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].'0,';
							}
							else {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'].',';
							}
							if (($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos'] == "")||($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos'] == NULL)) {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].'0);';
							}
							else {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos'].');';
							}
						}
						//Fin factura desglosada.
	
						//Para el resto de una factura desglosada de un grupo.
						else if ($_SESSION['nueva_factura'][$i]['factura']['tipo'] == "FDR") {
						  	
							$sql_a['genera'][$j] = "INSERT INTO genera_gr(Nombre_Gr,Fecha_Llegada,Id_Hab,Num_Factura,Id_Edad,Num_Facturados,Num_Exentos) VALUES ('".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Hab']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Factura']."','".$_SESSION['nueva_factura'][$i]['genera'][$j]['Id_Edad']."',";
							if (($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'] == "")||($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'] == NULL)) {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].'0,';
							}
							else {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Facturados'].',';
							}
							if (($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos'] == "")||($_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos'] == NULL)) {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].'0);';
							}
							else {
							  	$sql_a['genera'][$j] = $sql_a['genera'][$j].$_SESSION['nueva_factura'][$i]['genera'][$j]['Num_Exentos'].');';
							}
							$sql_a['pernocta'][$j] = $sql_a['pernocta'][$j] = "UPDATE estancia_gr SET Noches_Pagadas=".$_SESSION['nueva_factura'][$i]['genera'][$j]['noches_pagadas'].",Ingreso='".$ingreso_total_desglosada."' WHERE Nombre_Gr LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_SESSION['nueva_factura'][$i]['genera'][$j]['Fecha_Llegada']."';"; 
						}
						//Fin resto desglosada ...
					}
	
					//echo $sql.'<br>';
					$resultado = MYSQL_QUERY($sql);
					if ($resultado == 1) {
						for ($k = 0; $k < COUNT($sql_a['genera']); $k++) {
						  	//echo $sql_a['genera'][$k].'<BR>';
							MYSQL_QUERY($sql_a['genera'][$k]);
						}
						for ($k = 0; $k < COUNT($sql_a['pernocta']); $k++) {
							//echo $sql_a['pernocta'][$k].'<BR>';
							MYSQL_QUERY($sql_a['pernocta'][$k]);
						}
						if (isset($sql_a['insert_estancia'])) {
							for ($k = 0; $k < COUNT($sql_a['insert_estancia']); $k++) {
							  	//echo $sql_a['insert_estancia'][$k].'<BR>';
								MYSQL_QUERY($sql_a['insert_estancia'][$k]);
							}
						}
						if (isset($sql_a['insert_pernocta'])) {
							for ($k = 0; $k < COUNT($sql_a['insert_pernocta']); $k++) {
							  	//echo $sql_a['insert_pernocta'][$k].'<BR>';
								MYSQL_QUERY($sql_a['insert_pernocta'][$k]);
							}
						}
						/*if (isset($sql_a['componentes'])) {
							for ($k = 0; $k < COUNT($sql_a['componentes']); $k++) {
							  	//echo $sql_a['insert_pernocta'][$k].'<BR>';
								MYSQL_QUERY($sql_a['componentes'][$k]);
							}
						}*/
					}
					unset($sql_a['genera']);
					unset($sql_a['pernocta']);
				}
			}
			MYSQL_CLOSE($db);
		}
	}
?>
