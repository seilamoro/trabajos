<?PHP

	// En esta página recibimos variables de otras páginas 
	
	// Cuando damos de alta a un peregrino, tenemos que ir directamente a facturar su estancia.
	if (isset($_GET['tipo_distribucion'])) {
		$_SESSION['gdh']['gdh_dis']['tipo'] = $_GET['tipo_distribucion'];
	}

	if (isset($_GET['facturar'])) {
		if ($_GET['facturar'] == "true") {
		  	if ((isset($_GET['dni']))&&(isset($_GET['fecha_llegada']))) {
			    $_SESSION['gdh']['detalles']['de_tipo'] = 'P';
				$_SESSION['gdh']['detalles']['de_clave'] = $_GET['dni'];
				$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $_GET['fecha_llegada'];
				$_SESSION['gdh']['detalles']['de_pagina'] = 'D';
				$_SESSION['gdh']['gdh_dis']['tipo'] = 'F';
				$_SESSION['gdh']['detalles']['de_tipo'] = 'P';
			}
		}
	}
	
	// Cuando se pulsa el boton rojo de facturas en la parte de alertas tenemos que mostrar las estancias sin facturar
	if (isset($_GET['no_facturas'])) {
		if ($_GET['no_facturas'] == "true") {
		  	$_SESSION['gdh']['estancias']['mostrar'] = 'F';
		}
	}

?>
