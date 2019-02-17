<?PHP
	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
		$sql = "SELECT Num_Factura FROM factura WHERE SUBSTRING(Num_Factura,-4) LIKE '".DATE("Y")."';";
		$result = MYSQL_QUERY($sql);
		
		//echo $sql;
	
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
	
		if (isset($_SESSION['factura_grupo_desglosada'])) {
		  	if (($_SESSION['factura_grupo_desglosada']['Nombre_Gr'] != $_SESSION['gdh']['detalles']['de_clave'])||($_SESSION['factura_grupo_desglosada']['Fecha_Llegada'] != $_SESSION['gdh']['detalles']['de_fecha_llegada'])) {
			    unset($_SESSION['factura_grupo_desglosada']);
			  	$sql = "SELECT * FROM pernocta_gr,edad WHERE edad.Id_Edad LIKE pernocta_gr.Id_Edad AND pernocta_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND pernocta_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' ORDER BY pernocta_gr.Id_Hab,pernocta_gr.Id_Edad;";
				//echo $sql;
				$pernocta_ver_numero_personas = MYSQL_QUERY($sql);
			    inicializar_desglosada($factura_siguiente,$pernocta_ver_numero_personas);
			}
		}
		else {
		  	$sql = "SELECT * FROM pernocta_gr,edad WHERE edad.Id_Edad LIKE pernocta_gr.Id_Edad AND pernocta_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND pernocta_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' ORDER BY pernocta_gr.Id_Hab,pernocta_gr.Id_Edad;";
			//echo $sql;
			$pernocta_ver_numero_personas = MYSQL_QUERY($sql);
		  	inicializar_desglosada($factura_siguiente,$pernocta_ver_numero_personas);
		}
		
		if (isset($_POST['desglosada_factura'])) {
		  	$_SESSION['factura_grupo_desglosada']['factura_actual'] = $_POST['desglosada_factura'];
		}
		MYSQL_CLOSE($db);
	}
?>
