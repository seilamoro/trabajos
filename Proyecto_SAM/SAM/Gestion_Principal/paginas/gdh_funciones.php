<?PHP
/* Ya no se usa, se usa la fecha del calendario global.

	function gdh_cambio_fecha($dia,$mes,$anio) {
		$fecha = MKTIME(0,0,1,$_SESSION['gdh']['mes'] + $mes,$_SESSION['gdh']['dia'] + $dia,$_SESSION['gdh']['anio'] + $anio);		
		$_SESSION['gdh']['dia'] = STRFTIME("%d", $fecha);
		$_SESSION['gdh']['mes'] = STRFTIME("%m", $fecha);
		$_SESSION['gdh']['anio'] = STRFTIME("%Y", $fecha);
	}
	
	function gdh_cambiar_domingo_calendario($dia) {
	  	if ($dia == 0) {
		    return 7;
		}
		else {
            return $dia;
        }
	}
*///Fin Ya no se usa...

	// Nos devuelve la edad de una persona sabiendo la fecha referencia y la fecha de nacimiento.	
	function edad($f_nacimiento,$f_llegada) {
	  	$fecha_nacimiento = SPLIT("-",$f_nacimiento);
	  	$fecha_llegada = SPLIT("-",$f_llegada);
	  	$edad = INTVAL($fecha_llegada[0]) - INTVAL($fecha_nacimiento[0]);
	  	if (INTVAL($fecha_llegada[1]) < INTVAL($fecha_nacimiento[1])) {
		    $edad--;
		}
		else if (INTVAL($fecha_llegada[1]) == INTVAL($fecha_nacimiento[1])) {
		  	if (INTVAL($fecha_llegada[2]) < INTVAL($fecha_nacimiento[2])) {
			    $edad--;
			}
		}
		return $edad;
	}
	
	// Invierte el orden de la fecha año-mes-dia <-> dia-mes-año y viceversa.
	function fecha_ordenada($f) {
	  	$fecha = SPLIT("-",$f);
		return $fecha[2]."-".$fecha[1]."-".$fecha[0];
	}
	
	// Función que actualiza el campo de ingreso y de noches pagadas de un alberguista.
	// Devuelve -1 si no se actualiza, 0 si se actualiza pero quedan noches sin pagar o 1 si se han pagado ya todas las noches.	
	function pagar_noche_alberguista($dni,$fecha,$noches,$importe) {
	  
	  	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
		if (!$db) {	  
			echo '<div>ERROR DE CONEXION A MYSQL</div>';
		}
		else {/*
		  	if (SUBSTR($importe,0,-2) == " €") {
			    $importe = SUBSTR($importe,0,STRLEN($importe) - 2);
			}*/
			$importe = FLOATVAL($importe);
		  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		  	$sql = "SELECT * FROM pernocta WHERE DNI_Cl LIKE '".$dni."' AND Fecha_Llegada LIKE '".$fecha."';";
		  	$result = MYSQL_QUERY($sql);
			$fila = MYSQL_FETCH_ARRAY($result);
			$sql = "UPDATE pernocta SET Noches_Pagadas='".(INTVAL($fila['Noches_Pagadas']) + $noches)."', Ingreso='".(FLOATVAL($fila['Ingreso']) + $importe)."' WHERE DNI_Cl LIKE '".$dni."' AND Fecha_Llegada LIKE '".$fecha."';";
			$result = MYSQL_QUERY($sql);
			MYSQL_CLOSE($db);
		}		
		if ($result == 1) {
		 	if ((INTVAL($fila['Noches_Pagadas']) + $noches) == INTVAL($fila['PerNocta'])) {
			 	return 1;  	
			}
			else {
			  	return 0;
			}
		}
		else {
		  	return -1;
		}
	}
	
	// Función que actualiza el campo de ingreso y de noches pagadas de un grupo.
	// Devuelve -1 si no se actualiza, 0 si se actualiza pero quedan noches sin pagar o 1 si se han pagado ya todas las noches.	
	function pagar_noche_grupo($nombre,$fecha,$noches,$importe) {
	  
	  	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
	
		if (!$db) {	  
			echo '<div>ERROR DE CONEXION A MYSQL</div>';
		}
		else {
		  	if (SUBSTR($importe,0,-2) == " €") {
			    $importe = SUBSTR($importe,0,STRLEN($importe) - 2);
			}
		  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		  	$sql = "SELECT * FROM estancia_gr WHERE Nombre_Gr LIKE '".$nombre."' AND Fecha_Llegada LIKE '".$fecha."';";
		  	$result = MYSQL_QUERY($sql);
			$fila = MYSQL_FETCH_ARRAY($result);
			$sql = "UPDATE estancia_gr SET Noches_Pagadas='".(INTVAL($fila['Noches_Pagadas']) + $noches)."', Ingreso='".(FLOATVAL($fila['Ingreso']) + $importe)."' WHERE Nombre_Gr LIKE '".$nombre."' AND Fecha_Llegada LIKE '".$fecha."';";
			$result = MYSQL_QUERY($sql);
			MYSQL_CLOSE($db);
		}
		if ($result == 1) {
		 	if ((INTVAL($fila['Noches_Pagadas']) + $noches) == INTVAL($fila['PerNocta'])) {
			 	return 1;  	
			}
			else {
			  	return 0;
			}
		}
		else {
		  	return -1;
		}
	}
	
	// Modifica el ingreso de una reserva.
	function modificar_ingreso_reserva($dni,$fecha,$ingreso) {
	  
	  	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
		if (!$db) {	  
			echo '<div>ERROR DE CONEXION A MYSQL</div>';
		}
		else {
		  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
			$sql = "UPDATE detalles SET Ingreso='".$ingreso."' WHERE DNI_PRA LIKE '".$dni."' AND Fecha_Llegada LIKE '".$fecha."';";
			$result = MYSQL_QUERY($sql);
			MYSQL_CLOSE($db);
		}
	}
	
	// Añade un cero a un valor que le pasemos para conseguir 01 en vez de 1, hasta el 10.
	function aniadir_cero($valor) {
	  	if ($valor < 10) {
	    	return "0".$valor;
		}
		else {
		  	return $valor;
		}
	}
	
	function inicializar_desglosada($fac,$datos) {	  	
		$_SESSION['factura_grupo_desglosada']['Nombre_Gr'] = $_SESSION['gdh']['detalles']['de_clave'];
		$_SESSION['factura_grupo_desglosada']['Fecha_Llegada'] = $_SESSION['gdh']['detalles']['de_fecha_llegada'];
		$_SESSION['factura_grupo_desglosada']['Num_Facturas'] = $_POST['desglosada_n_facturas'];
		$_SESSION['factura_grupo_desglosada']['factura_actual'] = 0;//Nuevo
		
		@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
		if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
		}
		else {
		  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		  	$sql = "SELECT * FROM estancia_gr WHERE Nombre_Gr LIKE '".$_SESSION['factura_grupo_desglosada']['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_SESSION['factura_grupo_desglosada']['Fecha_Llegada']."';";
			//echo $sql;
			$result_pernocta = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql));
			$_SESSION['factura_grupo_desglosada']['PerNocta'] = $result_pernocta['PerNocta'];
			$_SESSION['factura_grupo_desglosada']['Noches_Pagadas'] = $result_pernocta['Noches_Pagadas'];
			$_SESSION['factura_grupo_desglosada']['Ingreso'] = $result_pernocta['Ingreso'];
			if (isset($result_pernocta['Id_Servicios'])) {
			  	$_SESSION['factura_grupo_desglosada']['regimen'] = $result_pernocta['Id_Servicios'];
			}
			MYSQL_CLOSE($db);
		}

		$_SESSION['factura_grupo_desglosada']['factura_actual'] = 0;
		//echo MYSQL_NUM_ROWS($datos);
		for ($i = 0; $i < $_SESSION['factura_grupo_desglosada']['Num_Facturas']; $i++) {
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['validar'] = false;
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['num_factura'] = $fac[0];
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['anio_factura'] = $fac[1];
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['fecha_factura'] = date('d-m-Y');
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['dni_factura'] = "";
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['nombre_factura'] = "";
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['apellido1_factura'] = "";
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['apellido2_factura'] = "";
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['desperfecto_factura'] = "";
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['cuantia_factura'] = 0.0;
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['ingreso_factura'] = '0 €';
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['tarifa_factura'] = NULL;
		  	// Como en una factura desglosada, cada factura individual solo corresponde a la pernocta de una persona (requisito del cliente):
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_nombre_gr'] = $_SESSION['gdh']['detalles']['de_clave'];
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_fecha_llegada'] = $_SESSION['gdh']['detalles']['de_fecha_llegada'];
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_hab'] = NULL;
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_id_edad'] = NULL;
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_facturados'] = 0;
		  	$_SESSION['factura_grupo_desglosada']['Factura'][$i]['genera_num_exentos'] = 0;
		  	// Fin como en una factura desglosada...
		  	$fac[0]++;
			//echo $_SESSION['factura_grupo_desglosada']['Factura'][$i]['num_factura'].'<br>';
		  	//echo $_SESSION['factura_grupo_desglosada']['Factura'][$i]['anio_factura'].'<br>';
		  	//echo $_SESSION['factura_grupo_desglosada']['Factura'][$i]['fecha_factura'].'<br>';
		}
		//Relleno los datos de la ultima factura, la del resto.
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['num_factura'] = $fac[0];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['anio_factura'] = $fac[1];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['fecha_factura'] = date('d-m-Y');
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['dni_factura'] = $result_pernocta['DNI_Repres'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['nombre_factura'] = $result_pernocta['Nombre_Repres'];
		$_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido1_factura'] = $result_pernocta['Apellido1_Repres'];
		$_SESSION['factura_grupo_desglosada']['Factura_Resto']['apellido2_factura'] = $result_pernocta['Apellido2_Repres'];
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['desperfecto_factura'] = "";
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['cuantia_factura'] = 0.0;
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['ingreso_factura'] = 0;
	  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['pagado_factura'] = 0;
	  	
		for ($i = 0; $i < MYSQL_NUM_ROWS($datos); $i++) {
		  	$aux = MYSQL_FETCH_ARRAY($datos);
		  	$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'] = $aux['Id_Hab'];
		  	$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad'] = $aux['Id_Edad'];
		  	$_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['Id_Hab'] = $aux['Id_Hab'];
		  	$_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['Nombre_Edad'] = $aux['Nombre_Edad'];
		  	$_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['Num_Personas'] = $aux['Num_Personas'];
		  	$_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['numero_facturados'] = 0;
		  	$_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['numero_exentos'] = 0;	
			// Inicializamos el resto de la factura
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['nombre_gr'] = $_SESSION['gdh']['detalles']['de_clave'];
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['fecha_llegada'] = $_SESSION['gdh']['detalles']['de_fecha_llegada'];
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_hab'] = $aux['Id_Hab'];
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Lista'][$i]['id_edad'] = $aux['Id_Edad'];
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['total'] = NULL;
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['num_facturados'] = NULL;
		  	$_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$aux['Id_Hab'].'-'.$aux['Id_Edad']]['num_exentos'] = NULL;
		}			
	}
	
	function comprobar_existe_estancia($tipo,$clave,$fecha) {
		if ($tipo == 'A') {
			$sql = 'SELECT * FROM pernocta WHERE DNI_Cl LIKE "'.$clave.'" AND Fecha_Llegada LIKE "'.$fecha.'";';
		}
		if ($tipo == 'P') {
			$sql = 'SELECT * FROM pernocta_p WHERE DNI_Cl LIKE "'.$clave.'" AND Fecha_Llegada LIKE "'.$fecha.'";';
		}
		if ($tipo == 'G') {
			$sql = 'SELECT * FROM pernocta_gr WHERE Nombre_Gr LIKE "'.$clave.'" AND Fecha_Llegada LIKE "'.$fecha.'";';
		}
		if ($tipo == 'R') {
			$sql = 'SELECT * FROM detalles WHERE DNI_PRA LIKE "'.$clave.'" AND Fecha_Llegada LIKE "'.$fecha.'";';
		}
		@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
	
		if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
			return false;
		}
		else {
		  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		  	$result = MYSQL_QUERY($sql);
			//echo $sql;
			MYSQL_CLOSE($db);
			if (MYSQL_NUM_ROWS($result) > 0) {
			  	return true;
			}
			else {
				return false;
			}
		}
	}
	
?>
