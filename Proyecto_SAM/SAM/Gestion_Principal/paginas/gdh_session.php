<?PHP

	if (!isset($_SESSION['gdh'])) {
	  
	  	//Colores del resaltado de listados:
	  	$_SESSION['gdh']['colores']['resaltado'] = '#569CD7';
	  	$_SESSION['gdh']['colores']['letra_resaltado'] = '#F4FCFF';
	  	$_SESSION['gdh']['colores']['normal'] = '#F4FCFF';
	  	$_SESSION['gdh']['colores']['letra_normal'] = '#3F7BCC';
	  	$_SESSION['gdh']['colores']['alerta_resaltado'] = '#AE4F4F';
	  	$_SESSION['gdh']['colores']['letra_alerta_resaltado'] = '#FFD1D1'; 
	  	$_SESSION['gdh']['colores']['alerta'] = '#EEC1C1';
	  	$_SESSION['gdh']['colores']['letra_alerta'] = '#970000';  	 	
	  	
	  	//Tamaños de las subventanas de gdh
	  	$_SESSION['gdh']['tamanios']['gdh_distribucion_ancho'] = '749px';
	  	$_SESSION['gdh']['tamanios']['gdh_distribucion_alto'] = '308px';
	  	$_SESSION['gdh']['tamanios']['gdh_distribucion_componentes'] = '314px';
	  	$_SESSION['gdh']['tamanios']['gdh_distribucion_alto_facturas'] = '316px';
	  	$_SESSION['gdh']['tamanios']['gdh_detalles_ancho'] = '314px';
	  	$_SESSION['gdh']['tamanios']['gdh_detalles_alto'] = $_SESSION['gdh']['tamanios']['gdh_distribucion_alto_facturas'];	  	
	  	$_SESSION['gdh']['tamanios']['gdh_estancias_ancho'] = $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho'];
	  	$_SESSION['gdh']['tamanios']['gdh_estancias_alto'] = '247px';
	  	$_SESSION['gdh']['tamanios']['gdh_checks_ancho'] = $_SESSION['gdh']['tamanios']['gdh_detalles_ancho'];
	  	$_SESSION['gdh']['tamanios']['gdh_checks_alto'] = '102px';	  	
		$_SESSION['gdh']['tamanios']['facturas'] = '253px';	  	
		$_SESSION['gdh']['tamanios']['factura_agrupada'] = '225px';
		$_SESSION['gdh']['tamanios']['td_factura_agrupada'] = '22px';
		$_SESSION['gdh']['tamanios']['gdh_izquierda'] = '809px'; 	
		$_SESSION['gdh']['tamanios']['gdh_derecha'] = '374px';
	  	$_SESSION['gdh']['tamanios']['gdh_resumen_desglosada'] = '186px';
	  	
	  	//Fin Tamaños
		$_SESSION['gdh']['gdh_dis']['tipo'] = "H";
		$_SESSION['gdh']['gdh_dis']['taquilla_pulsada'] = false;
		$_SESSION['gdh']['dia'] = date("d");
		$_SESSION['gdh']['mes'] = date("m");
		$_SESSION['gdh']['anio'] = date("Y");
		
		// Datos de la zona de Distribucion de Habitaciones
		$_SESSION['gdh']['dis_hab']['hab_seleccionada'] = "";
		$_SESSION['gdh']['dis_hab']['num_pag'] = 1;
		
		//Número máximo de camas por habitación en cada columna de la tabla.
		$_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] = 8;
		
		// Datos de los campos de texto de las estancias en el albergue en el día seleccionado
		$_SESSION['gdh']['estancias']['es_tipo'] = "";
		$_SESSION['gdh']['estancias']['es_dni'] = "";
		$_SESSION['gdh']['estancias']['es_name'] = "";
		$_SESSION['gdh']['estancias']['es_habitacion'] = "";
		$_SESSION['gdh']['estancias']['orden'] = "T";
		$_SESSION['gdh']['estancias']['modo'] = "A";
		
		// Mostrar estancias: D para estancias en el día o F para estancias sin facturar.
		$_SESSION['gdh']['estancias']['mostrar'] = "D";
		
		// Datos para el manejo de los detalles de la estancia
		$_SESSION['gdh']['detalles']['de_tipo'] = NULL;
		$_SESSION['gdh']['detalles']['de_clave'] = NULL;
		$_SESSION['gdh']['detalles']['de_fecha_llegada'] = NULL;
		$_SESSION['gdh']['detalles']['de_pagina'] = NULL;
		
		// Ordenacion por defecto del checkin y del checkout
		$_SESSION['gdh']['checkin']['orden'] = "E";
		$_SESSION['gdh']['checkin']['modo'] = "D";
		$_SESSION['gdh']['checkout']['orden'] = "F";
		$_SESSION['gdh']['checkout']['modo'] = "A";
		
		// Variables para el fichero de configuración.
		// Estas variables sirven para visualizar las habitaciones en gdh. Se crean en gestion interna.

		/*$_SESSION['pag_hab'][0]['pagina'] = 1;
		$_SESSION['pag_hab'][0]['orden'] = 3;
		$_SESSION['pag_hab'][0]['Id_Tipo_Hab'] = 1;		
		$_SESSION['pag_hab'][1]['pagina'] = 1;
		$_SESSION['pag_hab'][1]['orden'] = 2;
		$_SESSION['pag_hab'][1]['Id_Tipo_Hab'] = 2;
		$_SESSION['pag_hab'][2]['pagina'] = 1;
		$_SESSION['pag_hab'][2]['orden'] = 1;
		$_SESSION['pag_hab'][2]['Id_Tipo_Hab'] = 3;
		$_SESSION['pag_hab'][3]['pagina'] = 2;
		$_SESSION['pag_hab'][3]['orden'] = 1;
		$_SESSION['pag_hab'][3]['Id_Tipo_Hab'] = 4;*/
		
		// Variables del nombre de los meses.
		$_SESSION['gdh']['meses'] = array();
		$_SESSION['gdh']['meses'][0] = "Enero";
		$_SESSION['gdh']['meses'][1] = "Febrero";
		$_SESSION['gdh']['meses'][2] = "Marzo";
		$_SESSION['gdh']['meses'][3] = "Abril";
		$_SESSION['gdh']['meses'][4] = "Mayo";
		$_SESSION['gdh']['meses'][5] = "Junio";
		$_SESSION['gdh']['meses'][6] = "Julio";
		$_SESSION['gdh']['meses'][7] = "Agosto";
		$_SESSION['gdh']['meses'][8] = "Septiembre";
		$_SESSION['gdh']['meses'][9] = "Octubre";
		$_SESSION['gdh']['meses'][10] = "Noviembre";
		$_SESSION['gdh']['meses'][11] = "Diciembre";
		
		$_SESSION['gdh']['estancias']['configuracion_numero_filas'] = 8; //Total de las entradas minimas de la parte Estancias en el Albergue para mostrar bien la tabla.
		$_SESSION['gdh']['check']['configuracion_numero_filas'] = 5; //Total de las entradas minimas de la parte Check In para mostrar bien la tabla.
		$_SESSION['gdh']['factura']['configuracion_numero_filas'] = 8; //Total de las entradas minimas de la parte Check In para mostrar bien la tabla.
		$_SESSION['gdh']['resumen_desglosada']['configuracion_numero_filas'] = 6; //Total de las entradas minimas de la parte Check In para mostrar bien la tabla.
		$_SESSION['gdh']['componentes']['configuracion_numero_filas'] = 11; //Total de las entradas minimas de la parte Check In para mostrar bien la tabla.
		// Fin Variables para el fichero de configuración.
	}
	
	//echo '*'.$_POST['dis_cambio_hab'].'*<br>';
	
	if (isset($_POST['dis_cambio_hab_fin'])) {
	  	if ($_POST['dis_cambio_hab_fin'] != "") {
	  	  	if (!$_SESSION['gdh']['habitaciones'][''.$_SESSION['gdh']['dis_hab']['hab_seleccionada']]) {
			  	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);	
					
				if (!$db) {
					exit();
				}
				else {
	  			MYSQL_SELECT_DB($_SESSION['conexion']['db']);
					
					$sql = "INSERT into cambio_tipo_habitacion(Id_Hab, Id_Tipo_Hab, Fecha) VALUES('".$_SESSION['gdh']['dis_hab']['hab_seleccionada']."','".$_POST['dis_cambio_hab_fin']."','".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."');";
					//echo '<script>alert("'.$sql.'")</script>';	
					$result = MYSQL_QUERY($sql);
					
					if ($result != '1') {
						$sql = "UPDATE cambio_tipo_habitacion SET Id_Tipo_Hab='".$_POST['dis_cambio_hab_fin']."',Fecha='".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' WHERE Id_Hab='".$_SESSION['gdh']['dis_hab']['hab_seleccionada']."' AND Fecha='".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
						//echo '<script>alert("'.$sql.'")</script>';	
						$result = MYSQL_QUERY($sql);
					}
					
					$sql = "SELECT * FROM cambio_tipo_habitacion WHERE Id_Hab='".$_SESSION['gdh']['dis_hab']['hab_seleccionada']."' AND Fecha>'".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' ORDER BY Fecha ASC LIMIT 0,1;";
					//echo $sql;
					$result = MYSQL_QUERY($sql);
					
					if (MYSQL_NUM_ROWS($result) > 0) {
						$fila = MYSQL_FETCH_ARRAY($result);
						if ($fila['Id_Tipo_Hab'] == $_POST['dis_cambio_hab_fin']) {
							$sql = "DELETE FROM cambio_tipo_habitacion WHERE Id_Tipo_Hab='".$fila['Id_Tipo_Hab']."' AND Id_Hab='".$fila['Id_Hab']."' AND Fecha='".$fila['Fecha']."';";
							$result = MYSQL_QUERY($sql);
							//echo '<script>alert("'.$result.'")</script>';				
						}
					}
					
					$sql = "SELECT * FROM cambio_tipo_habitacion WHERE Id_Hab='".$_SESSION['gdh']['dis_hab']['hab_seleccionada']."' AND Fecha<'".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' ORDER BY Fecha DESC LIMIT 0,1;";
					//echo $sql;
					$result = MYSQL_QUERY($sql);
					
					if (MYSQL_NUM_ROWS($result) > 0) {
						$fila = MYSQL_FETCH_ARRAY($result);
						if ($fila['Id_Tipo_Hab'] == $_POST['dis_cambio_hab_fin']) {
							$sql = "DELETE FROM cambio_tipo_habitacion WHERE Id_Tipo_Hab='".$fila['Id_Tipo_Hab']."' AND Id_Hab='".$fila['Id_Hab']."' AND Fecha='".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
							$result = MYSQL_QUERY($sql);
							//echo '<script>alert("'.$result.'")</script>';				
						}
					}
					
					MYSQL_CLOSE($db);
				}
			}
			else {
			  	echo '<script>alert("La habitacion '.$_SESSION['gdh']['dis_hab']['hab_seleccionada'].' tiene estancias o reservas, no puede cambiar su tipo de habitación.");</script>';
			}
		}
	}
	
	if (isset($_POST['dis_cambio_hab'])) {
	  	if ($_POST['dis_cambio_hab'] != "") {
		  	if ($_POST['dis_cambio_hab'] == $_SESSION['gdh']['dis_hab']['hab_seleccionada']) {
			    $_SESSION['gdh']['dis_hab']['hab_seleccionada'] = "";
			}
			else {
			  	$_SESSION['gdh']['dis_hab']['hab_seleccionada'] = $_POST['dis_cambio_hab'];
			}
		}
	}

	if (isset($_POST['dis_num_pag'])) {
	  	if ($_POST['dis_num_pag'] != "") {
		  	$_SESSION['gdh']['dis_hab']['num_pag'] = $_POST['dis_num_pag'];
		}
	}
	
	if (isset($_POST['fecha_cal'])) {
	  	if ($_POST['fecha_cal'] != "") {
		    $fecha_calendario = array();
		    $fecha_calendario = SPLIT('-',$_POST['fecha_cal']);
			//echo '<script>alert("'.$fecha_calendario[0].'");</script>';
			//echo '<script>alert("'.$fecha_calendario[1].'");</script>';
			//echo '<script>alert("'.$fecha_calendario[2].'");</script>';
		    $_SESSION['gdh']['dia'] = $fecha_calendario[0];
		    $_SESSION['gdh']['mes'] = $fecha_calendario[1];
		    $_SESSION['gdh']['anio'] = $fecha_calendario[2];
		}
	}	
	if (isset($_GET['fecha_cal'])) {
	  	if ($_GET['fecha_cal'] != "") {
		    $fecha_calendario = array();
		    $fecha_calendario = SPLIT('-',$_GET['fecha_cal']);
			//echo '<script>alert("'.$fecha_calendario[0].'");</script>';
			//echo '<script>alert("'.$fecha_calendario[1].'");</script>';
			//echo '<script>alert("'.$fecha_calendario[2].'");</script>';
		    $_SESSION['gdh']['dia'] = $fecha_calendario[0];
		    $_SESSION['gdh']['mes'] = $fecha_calendario[1];
		    $_SESSION['gdh']['anio'] = $fecha_calendario[2];
		}
	}
	
  	if (isset($_POST['gdh_dis'])) {

		if ($_POST['gdh_dis'] == "T") {
		  	$_SESSION['gdh']['gdh_dis']['tipo'] = "T";
		  	if ($_POST['gdh_taq'] != "") {
		  	  	$datos = array();
	      		$datos = SPLIT('\*',$_POST['gdh_taq']);
			    $_SESSION['gdh']['gdh_dis']['taquilla_hab'] = $datos[0];
			    $_SESSION['gdh']['gdh_dis']['taquilla_num'] = $datos[1];
			    $_SESSION['gdh']['gdh_dis']['taquilla_pulsada'] = true;			    
			}
		}
		else if ($_POST['gdh_dis'] == "H") {
		  	$_SESSION['gdh']['gdh_dis']['tipo'] = "H";
		}
		else if ($_POST['gdh_dis'] == "des_taq") {
			@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
			
			if (!$db) {
				exit();
			}
			else {
				MYSQL_SELECT_DB($_SESSION['conexion']['db']);
				  
				$sql = "UPDATE taquilla SET DNI_Cl=NULL,Nombre_Gr=NULL WHERE Id_Hab LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_hab']."' AND Id_Taquilla LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_num']."';";
				$result = MYSQL_QUERY($sql);
				
				MYSQL_CLOSE($db);
			}	  	
		  	$_SESSION['gdh']['gdh_dis']['tipo'] = "T";
		}
		else if ($_POST['gdh_dis'] == "oc_taq") {
			@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
			if (!$db) {
				exit();
			}
			else {
			  	if ($_SESSION['gdh']['detalles']['de_tipo'] == "G") {
			  		
			  		$sql = "SELECT curdate() >= fecha_llegada as inicio,curdate()< fecha_salida as fin FROM estancia_gr WHERE Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
					$result = MYSQL_QUERY($sql);
					$fila = MYSQL_FETCH_ARRAY($result);
					
					if (($fila['inicio'] == '1') && ($fila['fin'] == '1')) {					
						$sql = "UPDATE taquilla SET Nombre_Gr='".$_SESSION['gdh']['detalles']['de_clave']."' WHERE Id_Hab LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_hab']."' AND Id_Taquilla LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_num']."';";
						$result = MYSQL_QUERY($sql);					
					}
					else {
						echo '<script>alert("No puede asignar una taquilla a un cliente que no está en el albergue el día de hoy.");</script>';
					}
					
					MYSQL_CLOSE($db);
				}
				else {
					MYSQL_SELECT_DB($_SESSION['conexion']['db']);
					
					if 	($_SESSION['gdh']['detalles']['de_tipo'] == "A") {
						$sql = "SELECT curdate() >= fecha_llegada as inicio,curdate()< fecha_salida as fin FROM pernocta WHERE DNI_Cl  LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
						$result = MYSQL_QUERY($sql);
						$fila = MYSQL_FETCH_ARRAY($result);					
					}
					else if ($_SESSION['gdh']['detalles']['de_tipo'] == "P") {
						$sql = "SELECT curdate() >= fecha_llegada as inicio,curdate()< fecha_salida as fin FROM pernocta_p WHERE DNI_Cl  LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
						$result = MYSQL_QUERY($sql);
						$fila = MYSQL_FETCH_ARRAY($result);					
					}
					
					if (($fila['inicio'] == '1') && ($fila['fin'] == '1')) {					
						$sql = "UPDATE taquilla SET DNI_Cl='".$_SESSION['gdh']['detalles']['de_clave']."' WHERE Id_Hab LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_hab']."' AND Id_Taquilla LIKE '".$_SESSION['gdh']['gdh_dis']['taquilla_num']."';";
						$result = MYSQL_QUERY($sql);
						
						if (($result == 1)&&($_SESSION['gdh']['detalles']['de_clave'] != "")) {
						  	$sql_a = "SELECT * FROM pernocta WHERE DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
						  	$sql_p = "SELECT * FROM pernocta_p WHERE DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
						  	$sql_gr = "SELECT * FROM pernocta_gr WHERE Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
	
							$result_a = MYSQL_QUERY($sql_a);
						  	$result_p = MYSQL_QUERY($sql_p);
						  	$result_gr = MYSQL_QUERY($sql_gr);
						  	
						  	//echo $sql_a;
						  	//echo $sql_p;
						  	//echo $sql_gr;
						  						  	
						  	if (MYSQL_NUM_ROWS($result_a) > 0) {
							    $fila = MYSQL_FETCH_ARRAY($result_a);
							    if ($fila['Id_Hab'] != $_SESSION['gdh']['gdh_dis']['taquilla_hab']) {
	?>
	
	<SCRIPT language="JavaScript">
	
		alert('La taquilla ha sido asignada, pero el alberguista está alojado en otra habitación.');
	
	</SCRIPT>
	
	<?PHP
								}
							}
							else if (MYSQL_NUM_ROWS($result_p) > 0) {
							    $fila = MYSQL_FETCH_ARRAY($result_p);
							    if ($fila['Id_Hab'] != $_SESSION['gdh']['gdh_dis']['taquilla_hab']) {
	?>
	
	<SCRIPT language="JavaScript">
	
		alert('La taquilla ha sido asignada, pero el peregrino está alojado en otra habitación.');
	
	</SCRIPT>
	
	<?PHP
								}						    
							}
							else if (MYSQL_NUM_ROWS($result_gr) > 0) {
							  	$estan_en_habitacion = false;
							  	for ($p = 0; $p < MYSQL_NUM_ROWS($result_gr); $p++) {
							    	$fila = MYSQL_FETCH_ARRAY($result_b);
							    	if ($fila['Id_Hab'] == $_SESSION['gdh']['gdh_dis']['taquilla_hab']) {
										$estan_en_habitacion = true;
									}			    
								}
								if ($estan_en_habitacion == false) {
	?>
	
	<SCRIPT language="JavaScript">
	
		alert('La taquilla ha sido asignada, pero el grupo está alojado en otras habitaciónes.');
	
	</SCRIPT>
	
	<?PHP
								}
							}
													    
						}				
					}
					else {
						echo '<script>alert("No puede asignar una taquilla a un cliente que no está en el albergue el día de hoy.");</script>';
					}				
					MYSQL_CLOSE($db);
				}			  	
			}	  	
		  	$_SESSION['gdh']['gdh_dis']['tipo'] = "T";
		}
		else if ($_POST['gdh_dis'] == "F") {
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			$datos_gdh_fac = SPLIT("\*",$_POST['gdh_fac']);
			//echo $datos_gdh_fac[0].'<br>';
			//echo $datos_gdh_fac[1].'<br>';
			//echo $datos_gdh_fac[2].'<br>';
			$_SESSION['gdh']['detalles']['de_clave'] = $datos_gdh_fac[1];
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos_gdh_fac[2];
			$_SESSION['gdh']['detalles']['de_tipo'] = $datos_gdh_fac[0];
			$_SESSION['gdh']['detalles']['de_pagina'] = "D";			
		}
		else if ($_POST['gdh_dis'] == "FG") {
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			$datos_gdh_fac = SPLIT("\*",$_POST['gdh_fac']);
			$_SESSION['gdh']['detalles']['de_clave'] = $datos_gdh_fac[1];
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos_gdh_fac[2];
			$_SESSION['gdh']['detalles']['de_tipo'] = $datos_gdh_fac[0];
			$_SESSION['gdh']['detalles']['de_pagina'] = "D";
			
		}
		else if ($_POST['gdh_dis'] == "FD") {  // Factura Desglosada
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			$datos_gdh_fac = SPLIT("\*",$_POST['gdh_fac']);
			//echo $datos_gdh_fac[0].'<br>';
			//echo $datos_gdh_fac[1].'<br>';
			//echo $datos_gdh_fac[2].'<br>';
			$_SESSION['gdh']['detalles']['de_clave'] = $datos_gdh_fac[1];
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos_gdh_fac[2];
			$_SESSION['gdh']['detalles']['de_tipo'] = $datos_gdh_fac[0];
			$_SESSION['gdh']['detalles']['de_pagina'] = "D";
			// Eliminar la configuracion de la factura desglosada si la habia;
			if ((isset($_SESSION['factura_grupo_desglosada']))&&(isset($_POST['tipo_factura']))) {
			  	unset($_SESSION['factura_grupo_desglosada']);
			}
			// Fin Eliminar...			
		}
		else if ($_POST['gdh_dis'] == "FDR") {  // Factura Desglosada - Resto de la factura
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			$datos_gdh_fac = SPLIT("\*",$_POST['gdh_fac']);
			//echo $datos_gdh_fac[0].'<br>';
			//echo $datos_gdh_fac[1].'<br>';
			//echo $datos_gdh_fac[2].'<br>';
			$_SESSION['gdh']['detalles']['de_clave'] = $datos_gdh_fac[1];
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos_gdh_fac[2];
			$_SESSION['gdh']['detalles']['de_tipo'] = $datos_gdh_fac[0];
			$_SESSION['gdh']['detalles']['de_pagina'] = "D";
			// Eliminar la configuracion de la factura desglosada si la habia;
			if ((isset($_SESSION['factura_grupo_desglosada']))&&(isset($_POST['tipo_factura']))) {
			  	unset($_SESSION['factura_grupo_desglosada']);
			}
			// Fin Eliminar...			
		}
		else if ($_POST['gdh_dis'] == "FDRM") {  // Resumen de la factura desglosada.
			echo '<script>alert();</script>';
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			$datos_gdh_fac = SPLIT("\*",$_POST['gdh_fac']);
			//echo $datos_gdh_fac[0].'<br>';
			//echo $datos_gdh_fac[1].'<br>';
			//echo $datos_gdh_fac[2].'<br>';
			$_SESSION['gdh']['detalles']['de_clave'] = $datos_gdh_fac[1];
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos_gdh_fac[2];
			$_SESSION['gdh']['detalles']['de_tipo'] = $datos_gdh_fac[0];
			$_SESSION['gdh']['detalles']['de_pagina'] = "D";
			// Eliminar la configuracion de la factura desglosada si la habia;
			if ((isset($_SESSION['factura_grupo_desglosada']))&&(isset($_POST['tipo_factura']))) {
			  	unset($_SESSION['factura_grupo_desglosada']);
			}
			// Fin Eliminar...			
		}
		else if ($_POST['gdh_dis'] == "FF") {
		  	$estancias_a_facturar = false;
		  	if (isset($_POST['estancias'])) {
		  	 	if ($_POST['estancias'] > 0) {
					$estancias_a_facturar = true;
				}
		  	}
		  	if (isset($_POST['estancias_p'])) {
		  	 	if ($_POST['estancias_p'] > 0) {
					$estancias_a_facturar = true;
				}
		  	}
		  	if (isset($_POST['estancias_gr'])) {
		  	 	if ($_POST['estancias_gr'] > 0) {
					$estancias_a_facturar = true;
				}
		  	}
		  	if (isset($_POST['estancias_mismo'])) {
		  	 	if ($_POST['estancias_mismo'] > 0) {
					$estancias_a_facturar = true;
				}
		  	}
		  	
		  	if ($estancias_a_facturar == true) {
				$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];			    
			}
			else {
			  	$_SESSION['gdh']['gdh_dis']['tipo'] = 'H';
			}
			//echo $_SESSION['gdh']['gdh_dis']['tipo'];	
		}
		else if ($_POST['gdh_dis'] == "FFM") { // Factura de estancias de la misma persona varios días consecutivos en varias habitaciones.
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			//echo $_SESSION['gdh']['gdh_dis']['tipo'];	
		}
		else if ($_POST['gdh_dis'] == "FN") { // Factura de parte de las noches de una estancia de un alberguista.
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			$datos_gdh_fac = SPLIT("\*",$_POST['gdh_fac']);
			//echo $datos_gdh_fac[0].'<br>';
			//echo $datos_gdh_fac[1].'<br>';
			//echo $datos_gdh_fac[2].'<br>';
			$_SESSION['gdh']['detalles']['de_clave'] = $datos_gdh_fac[1];
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos_gdh_fac[2];
			$_SESSION['gdh']['detalles']['de_tipo'] = $datos_gdh_fac[0];
			$_SESSION['gdh']['detalles']['de_pagina'] = "D";
		}
		else if ($_POST['gdh_dis'] == "FGN") { // Factura de parte de las noches de una estancia de un grupo.
			$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_dis'];
			$datos_gdh_fac = SPLIT("\*",$_POST['gdh_fac']);
			//echo $datos_gdh_fac[0].'<br>';
			//echo $datos_gdh_fac[1].'<br>';
			//echo $datos_gdh_fac[2].'<br>';
			$_SESSION['gdh']['detalles']['de_clave'] = $datos_gdh_fac[1];
			$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos_gdh_fac[2];
			$_SESSION['gdh']['detalles']['de_tipo'] = $datos_gdh_fac[0];
			$_SESSION['gdh']['detalles']['de_pagina'] = "D";
		}
	}
	
	// gdh_det: 'H' para detalles habitación, 'P*' para detalles peregrino, 'A*' para alberguista, 'G*' para grupo y 'Z*' para pagar noches de estancia.
	
	if (isset($_POST['gdh_det'])) {
	  	if ($_POST['gdh_det'] == 'H') {
		    $_SESSION['gdh']['detalles']['de_tipo'] = 'H';
			$_SESSION['gdh']['detalles']['de_pagina'] = $_POST['gdh_dat'];
		}  	
	  	if ($_POST['gdh_det'] == 'FGD') {
		    $_SESSION['gdh']['detalles']['de_tipo'] = 'G';
			$_SESSION['gdh']['detalles']['de_pagina'] = $_POST['gdh_det'];
		}  	
	  	else if ($_POST['gdh_det'] != "") {
	    	$datos = array();
	    	$pagos = array();
	      	$datos = SPLIT('\*',$_POST['gdh_dat']);
	      	$pagos = SPLIT('\*',$_POST['gdh_pag']);
	      	$_SESSION['gdh']['detalles']['de_clave'] = $datos[0];
	      	$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $datos[1];
	      	if (SUBSTR($_POST['gdh_det'],0,1) == "Z") {
		      	$_SESSION['gdh']['detalles']['de_tipo'] = SUBSTR($_POST['gdh_det'],1,1);
				$_SESSION['gdh']['detalles']['de_pagina'] = SUBSTR($_POST['gdh_det'],0,1);
				if (SUBSTR($_POST['gdh_det'],1,1) == "A") {
					$pago = pagar_noche_alberguista($_SESSION['gdh']['detalles']['de_clave'],$_SESSION['gdh']['detalles']['de_fecha_llegada'],$pagos[0],$pagos[1]);
					if ($pago == 1) {
					  	$_SESSION['gdh']['gdh_dis']['tipo'] = "F";
					  	$_SESSION['gdh']['detalles']['de_tipo'] = "A";
					  	$_SESSION['gdh']['detalles']['de_pagina'] = "D";  
					}
					else {
						$_SESSION['gdh']['detalles']['de_tipo'] = "A";
						$_SESSION['gdh']['detalles']['de_pagina'] = "P";
					}
				}
				else if (SUBSTR($_POST['gdh_det'],1,1) == "G") {				  
					$pago = pagar_noche_grupo($_SESSION['gdh']['detalles']['de_clave'],$_SESSION['gdh']['detalles']['de_fecha_llegada'],$pagos[0],$pagos[1]);
					if ($pago == 1) {
					  	$_SESSION['gdh']['gdh_dis']['tipo'] = "H";
					  	$_SESSION['gdh']['detalles']['de_tipo'] = "G";
					  	$_SESSION['gdh']['detalles']['de_pagina'] = "F";
					}
					else {
						$_SESSION['gdh']['detalles']['de_tipo'] = "G";
						$_SESSION['gdh']['detalles']['de_pagina'] = "P";
					}
				}
				else if (SUBSTR($_POST['gdh_det'],1,1) == "R") {
					if ($pagos[0] < 0) {
						echo '<script>alert("Una reserva no puede tener un ingreso negativo.");</script>';
						$_SESSION['gdh']['detalles']['de_pagina'] = "I";
					}
					else {
						modificar_ingreso_reserva($_SESSION['gdh']['detalles']['de_clave'],$_SESSION['gdh']['detalles']['de_fecha_llegada'],$pagos[0]);
						$_SESSION['gdh']['detalles']['de_pagina'] = "D";
					}
					$_SESSION['gdh']['detalles']['de_tipo'] = "R";					
				}
			}
			else {
		      	$_SESSION['gdh']['detalles']['de_tipo'] = SUBSTR($_POST['gdh_det'],0,1);
				$_SESSION['gdh']['detalles']['de_pagina'] = SUBSTR($_POST['gdh_det'],1,1);
			}
		}
	}
	
	// Recuperar el foco en los campos de texto de filtrado de estancias
	if ((isset($_POST['es_tipo']))||($_POST['es_dni'])||($_POST['es_name'])||($_POST['es_habitacion'])) {
		if ($_POST['es_tipo'] != $_SESSION['gdh']['estancias']['es_tipo']) {
	  	  	$_SESSION['gdh']['estancias']['es_tipo'] = STRTOUPPER($_POST['es_tipo']);
	  	  	$_SESSION['gdh']['foco'] = "es_tipo";
		}
		else if ($_POST['es_dni'] != $_SESSION['gdh']['estancias']['es_dni']) {
			$_SESSION['gdh']['estancias']['es_dni'] = $_POST['es_dni'];
	  	  	$_SESSION['gdh']['foco'] = "es_dni";
		}
		else if ($_POST['es_name'] != $_SESSION['gdh']['estancias']['es_name']) {
			$_SESSION['gdh']['estancias']['es_name'] = $_POST['es_name'];
	  	  	$_SESSION['gdh']['foco'] = "es_name";
		}
		else if ($_POST['es_habitacion'] != $_SESSION['gdh']['estancias']['es_habitacion']) {
			$_SESSION['gdh']['estancias']['es_habitacion'] = $_POST['es_habitacion'];
	  	  	$_SESSION['gdh']['foco'] = "es_habitacion";
		}
	}
	
	// Ordenar las zona de estancias en el albergue	
	if (isset($_POST['gdh_est'])) {
		if ($_POST['gdh_est'] != "") {
		  	$orden = array();
	      	$orden = SPLIT('\*',$_POST['gdh_est']);
		  	$_SESSION['gdh']['estancias']['orden'] = $orden[0];
		  	$_SESSION['gdh']['estancias']['modo'] = $orden[1];
		}
	}
	
	// Ordenar las zona de estancias en el albergue	
	if (isset($_POST['gdh_cki'])) {
		if ($_POST['gdh_cki'] != "") {
		  	$orden = array();
	      	$orden = SPLIT('\*',$_POST['gdh_cki']);
		  	$_SESSION['gdh']['checkin']['orden'] = $orden[0];
		  	$_SESSION['gdh']['checkin']['modo'] = $orden[1];
		}
	}
	
	// Ordenar las zona de estancias en el albergue	
	if (isset($_POST['gdh_cko'])) {
		if ($_POST['gdh_cko'] != "") {
		  	$orden = array();
	      	$orden = SPLIT('\*',$_POST['gdh_cko']);
		  	$_SESSION['gdh']['checkout']['orden'] = $orden[0];
		  	$_SESSION['gdh']['checkout']['modo'] = $orden[1];
		}
	}
	
	// Mostrar en estancias las estancias del día o las estancias sin facturar;
	if (isset($_POST['estancias_mostrar'])) {
	  	$_SESSION['gdh']['estancias']['mostrar'] = $_POST['estancias_mostrar'];
	}
	// Si pulsamos el boton Facturar de alertas tenemos que mostrar las estancias sin facturar
	if (isset($_POST['gdh_estancias_sin_facturar'])) {
	  	if ($_POST['gdh_estancias_sin_facturar'] == 'F') {
	  		$_SESSION['gdh']['estancias']['mostrar'] = $_POST['gdh_estancias_sin_facturar'];
	  	}
	}
	
	
	if (isset($_POST['modificar_factura_actual'])) { // Si cambiamos la vista de la factura, actualizamos todos los campos modificados de la
													 // que teniamos en pantalla.
		
		//IMPORTANTE///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//IMPORTANTE: SI HACEMOS UN CAMBIO DENTRO DE ESTE IF, EL CAMBIO DEBE HACERSE EN EL MISMO CODIGO DE LA PÁGINA factura_grupo_desglosada_resto.php
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
				
				$_SESSION['factura_grupo_desglosada']['Factura'][$_POST['modificar_factura_actual']]['tarifa_factura'] = $tarifa['Tarifa'];
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
	}
	/////////////////////////////////////////////
	// Hemos pulsado el boton de nueva factura //
	/////////////////////////////////////////////
	
	if (isset($_POST['gdh_tipo_factura'])) { 
		if ($_POST['gdh_tipo_factura'] != "") {
		  	if (isset($_SESSION['nueva_factura'])) {
				unset($_SESSION['nueva_factura']);
			}
			if ($_POST['gdh_tipo_factura'] == "FDR") { // Resto de la Factura Desglosada
				$continuar_factura = true;			
				for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Factura']); $i++) {
			  	  	if (($_SESSION['factura_grupo_desglosada']['Factura'][$i]['num_factura'] != "")
					  &&($_SESSION['factura_grupo_desglosada']['Factura'][$i]['anio_factura'] != "")
					  &&($_SESSION['factura_grupo_desglosada']['Factura'][$i]['fecha_factura'] != "")
					  &&($_SESSION['factura_grupo_desglosada']['Factura'][$i]['dni_factura'] != "")
					  &&($_SESSION['factura_grupo_desglosada']['Factura'][$i]['nombre_factura'] != "")
					  &&($_SESSION['factura_grupo_desglosada']['Factura'][$i]['apellido1_factura'] != "")
					  &&($_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_hab'] != NULL)
					  &&($_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_edad'] != NULL)
					  && (comprobar_dni($_SESSION['factura_grupo_desglosada']['Factura'][$i]['dni_factura']) == true)) {
					    $_SESSION['factura_grupo_desglosada']['Factura'][$i]['validar'] = true;
					}
					else {
					  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['validar'] =  false;
					  	$continuar_factura = false;
					}
			  	}
			  	if ($continuar_factura == true) {
			  		$_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_tipo_factura'];				    
				}
				else {
?>
					<script language="JavaScript">
						alert('Todas las facturas deben de tener D.N.I. válido, Nombre, 1er Apellido y el tipo de pernocta seleccionado.');
					</script>
<?PHP
				  	$_SESSION['gdh']['gdh_dis']['tipo'] = 'FD';
				}
			}
			else if ($_POST['gdh_tipo_factura'] == 'FDRM') { // Resumen de la Factura Desglosada
			  	if (($_SESSION['factura_grupo_desglosada']['Factura_Resto']['num_factura'] != "")
				  &&($_SESSION['factura_grupo_desglosada']['Factura_Resto']['anio_factura'] != "")
				  &&($_SESSION['factura_grupo_desglosada']['Factura_Resto']['fecha_factura'] != "")
				  &&($_SESSION['factura_grupo_desglosada']['Factura_Resto']['dni_factura'] != "")
				  &&($_SESSION['factura_grupo_desglosada']['Factura_Resto']['nombre_factura'] != "")
				  &&($_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido1_factura'] != "")) {
				    $_SESSION['gdh']['gdh_dis']['tipo'] = $_POST['gdh_tipo_factura'];
				}
				else {
?>
					<script language="JavaScript">
						alert('La factura debe de tener D.N.I., Nombre y 1er Apellido.');
					</script>
<?PHP
				  	$_SESSION['gdh']['gdh_dis']['tipo'] = 'FDR';
				}			  	
			}
			else if ($_POST['gdh_tipo_factura'] == "A") { // Factura de Alberguista
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "A";
			  	$_SESSION['nueva_factura'][0]['genera'][0]['Exento'] = $_POST['exento_factura'];
			  	if ($_POST['exento_factura'] == "S") {
				    $_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL(0.0);				
				}	
				else {
				  	$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL($_POST['importepagado_factura']) + FLOATVAL($_POST['resto_factura']);
				}
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Desperfecto'] = $_POST['desperfecto_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_POST['cuantia_desperfecto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['DNI_Cl'] = $_SESSION['gdh']['detalles']['de_clave'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Id_Hab'] = $_POST['habitacion_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Fecha_Llegada'] = $_SESSION['gdh']['detalles']['de_fecha_llegada'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['noches_pagadas'] = $_POST['nochespagadas_factura'];
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "P") { // Factura de Peregrino
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "P";
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL($_POST['importepagado_factura']) + FLOATVAL($_POST['resto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Desperfecto'] = $_POST['desperfecto_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_POST['cuantia_desperfecto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['DNI_Cl'] = $_SESSION['gdh']['detalles']['de_clave'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Id_Hab'] = $_POST['habitacion_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Fecha_Llegada'] = $_SESSION['gdh']['detalles']['de_fecha_llegada'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['noches_pagadas'] = $_POST['nochespagadas_factura'];
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "FG") { // Factura de Grupo
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "FG";
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL($_POST['ingreso_factura']) + FLOATVAL($_POST['resto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Desperfecto'] = $_POST['desperfecto_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_POST['cuantia_desperfecto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];
				for ($i = 0; $i < COUNT($_SESSION['factura_grupo']['Id_Hab']); $i++) {
					$_SESSION['nueva_factura'][0]['genera'][$i]['Nombre_Gr'] = $_SESSION['factura_grupo']['Nombre_Gr'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Fecha_Llegada'] = $_SESSION['factura_grupo']['Fecha_Llegada'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Id_Hab'] = $_SESSION['factura_grupo']['Id_Hab'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Id_Edad'] = $_SESSION['factura_grupo']['Id_Edad'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Facturados'] = $_SESSION['factura_grupo']['Num_Facturados'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Exentos'] = $_SESSION['factura_grupo']['Num_Personas'][$i] - $_SESSION['factura_grupo']['Num_Facturados'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['noches_pagadas'] = $_POST['nochespagadas_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['resto'] = $_POST['resto_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['ingreso'] = $_POST['ingreso_factura'];
				}
				unset($_SESSION['factura_grupo']);
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "FGN") { // Factura de Grupo
			
				@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);	

				if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
				}
				else {
				  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
				  	$sql = "SELECT * FROM estancia_gr INNER JOIN (SELECT ADDDATE('".$_SESSION['gdh']['detalles']['de_fecha_llegada']."',".$_POST['nochespagadas_factura'].") AS Cambio) AS consulta ON 1 WHERE estancia_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND estancia_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
					//echo $sql;
					$result = MYSQL_QUERY($sql);
					$cambio = MYSQL_FETCH_ARRAY($result);
					MYSQL_CLOSE($db);
				}			
			
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "FGN";
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL($_POST['resto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Desperfecto'] = $_POST['desperfecto_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_POST['cuantia_desperfecto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];
				for ($i = 0; $i < COUNT($_SESSION['factura_grupo']['Id_Hab']); $i++) {
					$_SESSION['nueva_factura'][0]['genera'][$i]['Nombre_Gr'] = $_SESSION['factura_grupo']['Nombre_Gr'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Fecha_Llegada'] = $_SESSION['factura_grupo']['Fecha_Llegada'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Id_Hab'] = $_SESSION['factura_grupo']['Id_Hab'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Id_Edad'] = $_SESSION['factura_grupo']['Id_Edad'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Facturados'] = $_SESSION['factura_grupo']['Num_Facturados'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Exentos'] = $_SESSION['factura_grupo']['Num_Personas'][$i] - $_SESSION['factura_grupo']['Num_Facturados'][$i];
					$_SESSION['nueva_factura'][0]['genera'][$i]['noches_pagadas'] = $_POST['nochespagadas_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['resto'] = $_POST['resto_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['cambio'] = $cambio['Cambio'];
				}
				unset($_SESSION['factura_grupo']);
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "AN") { // Factura de Parte de la estancia de alberguista (cuando paga pate de las noches)
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "FN";
			  	$_SESSION['nueva_factura'][0]['genera'][0]['Exento'] = $_POST['exento_factura'];
			  	if ($_POST['exento_factura'] == "S") {
				    $_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL(0.0);				
				}	
				else {
				  	$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL($_POST['importepagado_factura']) + FLOATVAL($_POST['resto_factura']);
				}
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Desperfecto'] = $_POST['desperfecto_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_POST['cuantia_desperfecto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['DNI_Cl'] = $_SESSION['gdh']['detalles']['de_clave'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Id_Hab'] = $_POST['habitacion_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Fecha_Llegada'] = $_SESSION['gdh']['detalles']['de_fecha_llegada'];
				
				@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);	

				if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
				}
				else {
				  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
				  	$sql = "SELECT * FROM pernocta INNER JOIN (SELECT ADDDATE('".$_SESSION['gdh']['detalles']['de_fecha_llegada']."',".$_POST['nochespagadas_factura'].") AS Cambio) AS consulta ON 1 WHERE pernocta.DNI_Cl LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND pernocta.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
					//echo $sql;
					$result = MYSQL_QUERY($sql);
					$cambio = MYSQL_FETCH_ARRAY($result);
					MYSQL_CLOSE($db);
				}
				
				$_SESSION['nueva_factura'][0]['genera'][0]['Fecha_Cambio'] = $cambio['Cambio'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Fecha_Salida'] = $cambio['Fecha_Salida'];
				$_SESSION['nueva_factura'][0]['genera'][0]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['genera'][0]['noches_pagadas'] = $cambio['Noches_Pagadas'];
				$_SESSION['nueva_factura'][0]['genera'][0]['total_noches'] = $cambio['PerNocta'];
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "FAA") { // Factura de Agrupada de Alberguistas
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "FAA";
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = 0;				
				for ($i = 0; $i < COUNT($_SESSION['estancias_agrupar']); $i++) {
					$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = $_SESSION['nueva_factura'][0]['factura']['Ingreso'] + $_SESSION['estancias_agrupar'][$i]['fila']['Ingreso'];
				}				
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = $_SESSION['nueva_factura'][0]['factura']['Ingreso'] + FLOATVAL($_POST['resto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Desperfecto'] = $_POST['desperfecto_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_POST['cuantia_desperfecto_factura']);				
				if ($_POST['escoger_dni'][0] == '0') {
				  	$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura_text'];
				}
				else {
				  	$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura_select'];
				}				
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];				
				for ($i = 0; $i < COUNT($_SESSION['estancias_agrupar']); $i++) {
					$_SESSION['nueva_factura'][0]['genera'][$i]['DNI_Cl'] = $_SESSION['estancias_agrupar'][$i]['fila']['DNI_Cl'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Id_Hab'] = $_SESSION['estancias_agrupar'][$i]['fila']['Id_Hab'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Fecha_Llegada'] = $_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Llegada'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['noches_pagadas'] = $_SESSION['estancias_agrupar'][$i]['fila']['PerNocta'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['ingreso'] = INTVAL($_SESSION['estancias_agrupar'][$i]['fila']['PerNocta']) * FLOATVAL($_SESSION['estancias_agrupar'][$i]['tarifa']['Tarifa']);
					$_SESSION['nueva_factura'][0]['genera'][$i]['servicios'] = INTVAL($_SESSION['estancias_agrupar'][$i]['fila']['PerNocta']) * FLOATVAL($_SESSION['estancias_agrupar'][$i]['fila']['precio_servicio']);
					if (isset($_POST['exento_mismo_alberguista'])) { //Si se factura al mismo alberguista varias estancias.
					  	$_SESSION['nueva_factura'][0]['genera'][$i]['Exento'] = $_POST['exento_mismo_alberguista'];
					}
					else {
						$_SESSION['nueva_factura'][0]['genera'][$i]['Exento'] = $_POST['exento__'.$_SESSION['estancias_agrupar'][$i]['fila']['DNI_Cl'].'__'.STR_REPLACE('-','_',$_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Llegada'])];
					}
				}
				unset($_SESSION['estancias_agrupar']);
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "FAP") { // Factura de Agrupada de Peregrinos		
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "FAP";
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = 0;				
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL($_POST['resto_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Desperfecto'] = $_POST['desperfecto_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_POST['cuantia_desperfecto_factura']);				
				if ($_POST['escoger_dni'][0] == '0') {
				  	$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura_text'];
				}
				else {
				  	$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura_select'];
				}				
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];				
				for ($i = 0; $i < COUNT($_SESSION['estancias_agrupar']); $i++) {
					$_SESSION['nueva_factura'][0]['genera'][$i]['DNI_Cl'] = $_SESSION['estancias_agrupar'][$i]['fila']['DNI_Cl'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Id_Hab'] = $_SESSION['estancias_agrupar'][$i]['fila']['Id_Hab'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Fecha_Llegada'] = $_SESSION['estancias_agrupar'][$i]['fila']['Fecha_Llegada'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['noches_pagadas'] = $_SESSION['estancias_agrupar'][$i]['fila']['PerNocta'];
				}
				unset($_SESSION['estancias_agrupar']);
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "R") { // Factura de una Reserva
				$_SESSION['nueva_factura'][0]['factura']['tipo'] = "R";
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = 0;				
				$_SESSION['nueva_factura'][0]['factura']['Ingreso'] = FLOATVAL($_POST['importepagado_factura']);
				$_SESSION['nueva_factura'][0]['factura']['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Fecha_Factura'] = fecha_ordenada($_POST['fecha_factura']);
				$_SESSION['nueva_factura'][0]['factura']['DNI'] = $_POST['dni_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Nombre'] = $_POST['nombre_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido1'] = $_POST['apellido1_factura'];
				$_SESSION['nueva_factura'][0]['factura']['Apellido2'] = $_POST['apellido2_factura'];
				for ($i = 0; $i < COUNT($_SESSION['genera_reserva']); $i++) {
					$_SESSION['nueva_factura'][0]['genera'][$i]['DNI_PRA'] = $_SESSION['genera_reserva'][$i]['fila']['DNI_PRA'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Id_Hab'] = $_SESSION['genera_reserva'][$i]['fila']['Id_Hab'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Fecha_Llegada'] = $_SESSION['genera_reserva'][$i]['fila']['Fecha_Llegada'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Num_Factura'] = $_POST['numero_factura'].'-'.$_POST['anio_factura'];
					$_SESSION['nueva_factura'][0]['genera'][$i]['Presentado'] = 'N';
				}
				unset($_SESSION['genera_reserva']);
				include("./paginas/facturacion.php");
				$nueva_factura = $_SESSION['nueva_factura'][0]['factura']['Num_Factura'];
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "D";
			    // Para mostrar la factura directamente cuando se carga la página: (Se muestra en gdh.php)
			    $_SESSION['gdh']['mostrar_factura'] = $nueva_factura;
			    // Para...
			}
			else if ($_POST['gdh_tipo_factura'] == "FDFinal") { // Final de una Factura de Grupo Desglosada
				/* CREAR LAS NUEVAS FACTURAS Y LOS 'GENERAS' DE LA FACTURA DESGLOSADA.*/
				
				for ($i = 0; $i < $_SESSION['factura_grupo_desglosada']['Num_Facturas']; $i++) {	
					$_SESSION['nueva_factura'][$i]['factura']['tipo'] = "FD";
					$_SESSION['nueva_factura'][$i]['factura']['Num_Factura'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['num_factura'].'-'.$_SESSION['factura_grupo_desglosada']['Factura'][$i]['anio_factura'];
					$_SESSION['nueva_factura'][$i]['factura']['Fecha_Factura'] = fecha_ordenada($_SESSION['factura_grupo_desglosada']['Factura'][$i]['fecha_factura']);
					$_SESSION['nueva_factura'][$i]['factura']['DNI'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['dni_factura'];
					$_SESSION['nueva_factura'][$i]['factura']['Nombre'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['nombre_factura'];
					$_SESSION['nueva_factura'][$i]['factura']['Apellido1'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['apellido1_factura'];
					$_SESSION['nueva_factura'][$i]['factura']['Apellido2'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['apellido2_factura'];
					$_SESSION['nueva_factura'][$i]['factura']['Desperfecto'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['desperfecto_factura'];
				  	if (FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura'][$i]['cuantia_factura']) == 0) {
					  	$_SESSION['nueva_factura'][$i]['factura']['Cuantia_Desperfecto'] = NULL;
					}
					else { 
						$_SESSION['nueva_factura'][$i]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura'][$i]['cuantia_factura']);
				  	}
					$_SESSION['nueva_factura'][$i]['factura']['Ingreso'] = 0;				
					$_SESSION['nueva_factura'][$i]['factura']['Ingreso'] = FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura'][$i]['ingreso_factura']);
					
				  	
					$_SESSION['nueva_factura'][$i]['genera'][0]['Nombre_Gr'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_nombre_gr'];
					$_SESSION['nueva_factura'][$i]['genera'][0]['Fecha_Llegada'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_fecha_llegada'];
					$_SESSION['nueva_factura'][$i]['genera'][0]['Id_Hab'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_hab'];
					$_SESSION['nueva_factura'][$i]['genera'][0]['Id_Edad'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_edad'];
					$_SESSION['nueva_factura'][$i]['genera'][0]['Num_Factura'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['num_factura'].'-'.$_SESSION['factura_grupo_desglosada']['Factura'][$i]['anio_factura'];

					//echo 'exentos    :*'.$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_exentos'].'*<br>';
					//echo 'facturados :*'.$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_facturados'].'*<br>';

					if ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_facturados'] > 0) {
						$_SESSION['nueva_factura'][$i]['genera'][0]['Num_Facturados'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_facturados'];
					}
					else {
					  	$_SESSION['nueva_factura'][$i]['genera'][0]['Num_Facturados'] = NULL;
					}
					if ($_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_exentos'] > 0) {
						$_SESSION['nueva_factura'][$i]['genera'][0]['Num_Exentos'] = $_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_exentos'];
					}
					else {
					  	$_SESSION['nueva_factura'][$i]['genera'][0]['Num_Exentos'] = NULL;
					}
					$numero_resto_desglosada = $i + 1;
				}				
				
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['tipo'] = "FDR";
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Num_Factura'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['num_factura'].'-'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['anio_factura'];
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Fecha_Factura'] = fecha_ordenada($_SESSION['factura_grupo_desglosada']['Factura_Resto']['fecha_factura']);
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['DNI'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['dni_factura'];
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Nombre'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['nombre_factura'];
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Apellido1'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido1_factura'];
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Apellido2'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido2_factura'];
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Desperfecto'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['desperfecto_factura'];
				
				if (FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura']) == 0) {
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Cuantia_Desperfecto'] = NULL;
				}
				else { 
					$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Cuantia_Desperfecto'] = FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura']);
			  	}
				  	
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Ingreso'] = 0;				
				$_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Ingreso'] = FLOATVAL($_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura']);				

				for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista']); $i++) {
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['Nombre_Gr'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['nombre_gr'];
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['Fecha_Llegada'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['fecha_llegada'];
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['Id_Hab'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_hab'];
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['Id_Edad'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_edad'];
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['Num_Factura'] = $_SESSION['nueva_factura'][$numero_resto_desglosada]['factura']['Num_Factura'];
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['Num_Facturados'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_hab'].'-'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_edad']]['num_facturados'];
				  	$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['Num_Exentos'] = $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_hab'].'-'.$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_edad']]['num_exentos'];
					$_SESSION['nueva_factura'][$numero_resto_desglosada]['genera'][$i]['noches_pagadas'] = $_SESSION['factura_grupo_desglosada']['PerNocta'];
				}
				/* FIN CREAR LAS NUEVAS... */
				include("./paginas/facturacion.php");
			    unset($_SESSION['factura_grupo_desglosada']);
			    unset($_SESSION['nueva_factura']);
			    $_SESSION['gdh']['gdh_dis']['tipo'] = "H";
			    $_SESSION['gdh']['detalles']['de_pagina'] = "FGD"; //Ver lista de facturas desglosadas para poder elegir la que queremos mostrar en pantalla.
			}
		}
	}
?>
