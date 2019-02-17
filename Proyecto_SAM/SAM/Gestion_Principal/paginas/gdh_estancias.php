<script language="javascript">

	// Esta funcion comprueba que tipo de checkbox de facturar
	// hemos pulsado para llamar a la siguiente funcion, que
	// deshabilita los check que no son necesarios en ese caso.
	function comprobar_grupo_factura(check,tipo) {
	  	if (tipo == 'estancias') {
	  	  	cambiar_check('estancias[]','estancias_p[]','estancias_gr[]');
	  	}
	  	else if (tipo == 'estancias_p') {
	  	  	cambiar_check('estancias_p[]','estancias[]','estancias_gr[]');
		}
	  	else if (tipo == 'estancias_gr') {
	  	  	cambiar_check_grupo('estancias_gr[]','estancias[]','estancias_p[]');
		}
	}
	
	// Si se ha pulsado un check de un tipo de persona, excepto de un grupo,
	// el resto del otro tipo de personas se desactivan.
	function cambiar_check(pulsado,cambio1,cambio2) {
	  	var checkeado = 0;
	  	
	    for (var i = 0; i < document.getElementsByName(pulsado).length; i++) {
	      	if (document.getElementsByName(pulsado)[i].checked == true) {
			    checkeado = 1;
			}
		}
		
		if (checkeado == 1) {
		  	for (var i = 0; i < document.getElementsByName(cambio1).length; i++) {
		      	document.getElementsByName(cambio1)[i].checked = false;
		      	document.getElementsByName(cambio1)[i].disabled = true;
		      	
			}
		  	for (var i = 0; i < document.getElementsByName(cambio2).length; i++) {
		      	document.getElementsByName(cambio2)[i].checked = false;
		      	document.getElementsByName(cambio2)[i].disabled = true;
			}
		}
		else {
		  	for (var i = 0; i < document.getElementsByName(cambio1).length; i++) {
		      	document.getElementsByName(cambio1)[i].disabled = false;
		      	
			}
		  	for (var i = 0; i < document.getElementsByName(cambio2).length; i++) {
		      	document.getElementsByName(cambio2)[i].disabled = false;
			}
		}
	}
	
	// Si se ha pulsado un check de un grupo, el resto se
	// desactivan.
	function cambiar_check_grupo(pulsado,cambio1,cambio2) {
	  	var checkeado = 0;
	  	
	    for (var i = 0; i < document.getElementsByName(pulsado).length; i++) {
	      	if (document.getElementsByName(pulsado)[i].checked == true) {
			    checkeado = 1;
			}
		}
		
		if (checkeado == 1) {
		  	for (var i = 0; i < document.getElementsByName(pulsado).length; i++) {
		  	  	if (document.getElementsByName(pulsado)[i].checked == true) {
			    }
			    else {
			      	document.getElementsByName(pulsado)[i].disabled = true;				  
				}
		      	
			}
		  	for (var i = 0; i < document.getElementsByName(cambio1).length; i++) {
		      	document.getElementsByName(cambio1)[i].checked = false;
		      	document.getElementsByName(cambio1)[i].disabled = true;
		      	
			}
		  	for (var i = 0; i < document.getElementsByName(cambio2).length; i++) {
		      	document.getElementsByName(cambio2)[i].checked = false;
		      	document.getElementsByName(cambio2)[i].disabled = true;
			}
		}
		else {
		  	for (var i = 0; i < document.getElementsByName(pulsado).length; i++) {
		      	document.getElementsByName(pulsado)[i].disabled = false;
		      	
			}
		  	for (var i = 0; i < document.getElementsByName(cambio1).length; i++) {
		      	document.getElementsByName(cambio1)[i].disabled = false;
		      	
			}
		  	for (var i = 0; i < document.getElementsByName(cambio2).length; i++) {
		      	document.getElementsByName(cambio2)[i].disabled = false;
			}
		}
	}

</script>

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_estancias_ancho']; ?>;">
					Estancias en el Albergue
					<select name="estancias_mostrar" class="detalles_select" style="position:relative;margin-left:40px;" onchange="mostrar_estancias();">
<?PHP
	  	if ($_SESSION['gdh']['estancias']['mostrar'] == 'D') {
	  		echo '<option value="D" selected>Ver Estancias del Día';
	  		echo '<option value="F">Ver Estancias sin Facturar';
	  	}
	  	else {
	  		echo '<option value="D">Ver Estancias del Día';
	  		echo '<option value="F" selected>Ver Estancias sin Facturar';
		}
?>
					</select>
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_estancias_alto']; ?>;">
				<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
					<thead class="fixedHeader">
						<tr>
							<th width="40px" align="center">Factura</th>
							<th width="40px" align="center"><div title="Ordenar por tipo" onclick="ordenar_estancias('estancias','T','<?PHP if ($_SESSION['gdh']['estancias']['orden'] == "T") { if ($_SESSION['gdh']['estancias']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Tipo</div></th>
							<th width="150px" align="left"><div title="Ordenar por D.N.I." onclick="ordenar_estancias('estancias','D','<?PHP if ($_SESSION['gdh']['estancias']['orden'] == "D") { if ($_SESSION['gdh']['estancias']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">D.N.I.</div></th>
							<th width="384px" align="left"><div title="Ordenar por nombre y apellidos" onclick="ordenar_estancias('estancias','N','<?PHP if ($_SESSION['gdh']['estancias']['orden'] == "N") { if ($_SESSION['gdh']['estancias']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Nombre y Apellidos</div></th>
							<th width="120px" align="left"><div title="Ordenar por Habitaciones" onclick="ordenar_estancias('estancias','H','<?PHP if ($_SESSION['gdh']['estancias']['orden'] == "H") { if ($_SESSION['gdh']['estancias']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Habitación</div></th>
						</tr>
						<tr>
							<th align="center">
<?PHP
		if (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion'] == true) {
?>
								<img src="./../imagenes/botones/facturar2.gif" class="imagen-boton" title="Facturar" onclick="agrupar_estancias_factura();">
<?PHP
		}
		else {
?>
								<img src="./../imagenes/botones/facturar2.gif" class="imagen-boton" title="No tiene permisos de facturación.">
<?PHP
		}
?>
							</th>
							<th align="center">
								<input type="text" name="es_tipo" size="2" class="input_text_buscar" maxlength="1" style="text-align:center;" onkeyup="cargar();" value="<?php echo $_SESSION['gdh']['estancias']['es_tipo']; ?>">
							</th>
							<th align="left">
								<input type="text" name="es_dni" size="20" class="input_text_buscar" maxlength="30" style="text-align:left;" onkeyup="cargar();" value="<?php echo $_SESSION['gdh']['estancias']['es_dni']; ?>">											
							</th>
							<th align="left">
								<input type="text" name="es_name" size="60" class="input_text_buscar" maxlength="60" style="text-align:left;" onkeyup="cargar();" value="<?php echo $_SESSION['gdh']['estancias']['es_name']; ?>">										
							</th>
							<th align="left">
								<input type="text" name="es_habitacion" size="12" class="input_text_buscar" maxlength="10" style="text-align:left;" onkeyup="cargar();" value="<?php echo $_SESSION['gdh']['estancias']['es_habitacion']; ?>">
							</th>
						</tr>
					</thead>
					<tbody class="scrollContent">
<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
	
	if (!$db) {	  
?>
	<tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr>
<?PHP
	}
	else {
		MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	
	  	$estancias = array();
	  			  
		// Estancias de alberguistas
		if (($_SESSION['gdh']['estancias']['es_tipo'] == "") || ($_SESSION['gdh']['estancias']['es_tipo'] == 'A')) {
		  	if ($_SESSION['gdh']['estancias']['mostrar'] == 'D') {
				$sql = "SELECT pernocta.DNI_Cl,pernocta.Fecha_Llegada,pernocta.Fecha_Salida,pernocta.Id_Hab,CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) AS Name FROM pernocta LEFT JOIN cliente ON pernocta.DNI_Cl=cliente.DNI_Cl WHERE pernocta.Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND pernocta.Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND pernocta.DNI_Cl LIKE '".$_SESSION['gdh']['estancias']['es_dni']."%' AND CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) LIKE '".$_SESSION['gdh']['estancias']['es_name']."%' ";
			}
			else {			  	
				$sql = "SELECT pernocta.DNI_Cl,pernocta.Fecha_Llegada,pernocta.Fecha_Salida,pernocta.Id_Hab,CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) AS Name FROM pernocta LEFT JOIN cliente ON pernocta.DNI_Cl=cliente.DNI_Cl WHERE pernocta.DNI_Cl LIKE '".$_SESSION['gdh']['estancias']['es_dni']."%' AND CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) LIKE '".$_SESSION['gdh']['estancias']['es_name']."%' ";
			}
			if ($_SESSION['gdh']['estancias']['es_habitacion'] != "") {		
				$sql = $sql."AND pernocta.Id_Hab LIKE '".$_SESSION['gdh']['estancias']['es_habitacion']."' ORDER BY pernocta.Fecha_Llegada;";
			}
			else {	
				$sql = $sql."ORDER BY pernocta.Fecha_Llegada;";			  
			}
			$result = MYSQL_QUERY($sql);
			//echo $sql;
			
			for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  		$fila = MYSQL_FETCH_ARRAY($result);
		  		$sql_factura = "SELECT Num_Factura FROM genera WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
			  	$result_factura = MYSQL_QUERY($sql_factura);
			  	
			  	if (($_SESSION['gdh']['estancias']['mostrar'] == 'D') || (($_SESSION['gdh']['estancias']['mostrar'] == 'F')&&(MYSQL_NUM_ROWS($result_factura) == 0))) {
				  	$contador = COUNT($estancias);
					$estancias[$contador]['es_tipo'] = "A";
				  	$estancias[$contador]['es_dni'] = $fila['DNI_Cl'];
				  	$estancias[$contador]['es_clave'] = $fila['DNI_Cl'];
				  	$estancias[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
				  	$estancias[$contador]['es_name'] = $fila['Name'];
				  	$estancias[$contador]['es_habitacion'] = $fila['Id_Hab'];
				  	
				  	if ($_SESSION['gdh']['estancias']['mostrar'] == 'F') {
				  	  	$sql_dias = "SELECT DATEDIFF('".$fila['Fecha_Salida']."',(SELECT CURDATE())) AS dias;";
						$result_dias = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_dias));
					}
					
					if ($result_dias['dias'] < 0) {
					  	$estancias[$contador]['es_fuera'] = true;
					}
					else {
					  	$estancias[$contador]['es_fuera'] = false;
					}
				  	
					if (MYSQL_NUM_ROWS($result_factura) > 0) {
					    $fila_factura = MYSQL_FETCH_ARRAY($result_factura);
				  		$estancias[$contador]['es_tiene_factura'] = true;
						$estancias[$contador]['es_factura'] = $fila_factura['Num_Factura'];
					}
					else {
				  		$estancias[$contador]['es_tiene_factura'] = false;
						$estancias[$contador]['es_factura'] = 'A*'.$fila['DNI_Cl'].'*'.$fila['Fecha_Llegada'];
					}
			  	}
		  	}
		}
		  
		// Estancias de peregrinos
		if (($_SESSION['gdh']['estancias']['es_tipo'] == "") || ($_SESSION['gdh']['estancias']['es_tipo'] == 'P')) {
			if ($_SESSION['gdh']['estancias']['mostrar'] == 'D') {
				$sql = "SELECT pernocta_p.DNI_Cl,pernocta_p.Fecha_Llegada,pernocta_p.Fecha_Salida,pernocta_p.Id_Hab,CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) AS Name FROM pernocta_p LEFT JOIN cliente ON pernocta_p.DNI_Cl=cliente.DNI_Cl WHERE pernocta_p.Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND pernocta_p.Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND pernocta_p.DNI_Cl LIKE '".$_SESSION['gdh']['estancias']['es_dni']."%' AND CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) LIKE '".$_SESSION['gdh']['estancias']['es_name']."%' ";
			}
			else {			  	
				$sql = "SELECT pernocta_p.DNI_Cl,pernocta_p.Fecha_Llegada,pernocta_p.Fecha_Salida,pernocta_p.Id_Hab,CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) AS Name FROM pernocta_p LEFT JOIN cliente ON pernocta_p.DNI_Cl=cliente.DNI_Cl WHERE pernocta_p.DNI_Cl LIKE '".$_SESSION['gdh']['estancias']['es_dni']."%' AND CONCAT(cliente.Nombre_Cl,' ',cliente.Apellido1_Cl,' ',cliente.Apellido2_Cl) LIKE '".$_SESSION['gdh']['estancias']['es_name']."%' ";
			}			
			if ($_SESSION['gdh']['estancias']['es_habitacion'] != "") {		
				$sql = $sql."AND pernocta_p.Id_Hab LIKE '".$_SESSION['gdh']['estancias']['es_habitacion']."' ORDER BY pernocta_p.Fecha_Llegada;";
			}
			else {	
				$sql = $sql."ORDER BY pernocta_p.Fecha_Llegada;";			  
			}
			$result = MYSQL_QUERY($sql);		
			
			for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  		$fila = MYSQL_FETCH_ARRAY($result);
		  		$sql_factura = "SELECT Num_Factura FROM genera_p WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
			  	$result_factura = MYSQL_QUERY($sql_factura);
			  	
			  	if (($_SESSION['gdh']['estancias']['mostrar'] == 'D') || (($_SESSION['gdh']['estancias']['mostrar'] == 'F')&&(MYSQL_NUM_ROWS($result_factura) == 0))) {
					$contador = COUNT($estancias);
				  	$estancias[$contador]['es_tipo'] = "P";
				  	$estancias[$contador]['es_dni'] = $fila['DNI_Cl'];
				  	$estancias[$contador]['es_clave'] = $fila['DNI_Cl'];
				  	$estancias[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
				  	$estancias[$contador]['es_name'] = $fila['Name'];
				  	$estancias[$contador]['es_habitacion'] = $fila['Id_Hab'];
				  	
				  	if ($_SESSION['gdh']['estancias']['mostrar'] == 'F') {
				  	  	$sql_dias = "SELECT DATEDIFF('".$fila['Fecha_Salida']."',(SELECT CURDATE())) AS dias;";
						$result_dias = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_dias));
					}
					
					if ($result_dias['dias'] < 0) {
					  	$estancias[$contador]['es_fuera'] = true;
					}
					else {
					  	$estancias[$contador]['es_fuera'] = false;
					}
					
					if (MYSQL_NUM_ROWS($result_factura) > 0) {
						$fila_factura = MYSQL_FETCH_ARRAY($result_factura);		
				  		$estancias[$contador]['es_tiene_factura'] = true;
						$estancias[$contador]['es_factura'] = $fila_factura['Num_Factura'];
					}
					else {
				  		$estancias[$contador]['es_tiene_factura'] = false;
						$estancias[$contador]['es_factura'] = 'P*'.$fila['DNI_Cl'].'*'.$fila['Fecha_Llegada'];
					}
			  	}
		  	}
		}
		  
		// Estancias de grupos
		if ((($_SESSION['gdh']['estancias']['es_tipo'] == "") || ($_SESSION['gdh']['estancias']['es_tipo'] == 'G'))&&($_SESSION['gdh']['estancias']['es_dni'] == '')) {
			if ($_SESSION['gdh']['estancias']['mostrar'] == 'D') {
				$sql = "SELECT * FROM colores INNER JOIN (SELECT Nombre_Gr,DNI_Repres,Fecha_Llegada,Fecha_Salida,Id_Color FROM estancia_gr WHERE Fecha_Llegada <= '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND Fecha_Salida > '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."'AND Nombre_Gr LIKE '".$_SESSION['gdh']['estancias']['es_name']."%') AS consulta ON consulta.Id_Color=colores.Id_Color;";
			}
			else {			  	
				$sql = "SELECT * FROM colores INNER JOIN (SELECT Nombre_Gr,DNI_Repres,Fecha_Llegada,Fecha_Salida,Id_Color FROM estancia_gr WHERE Nombre_Gr LIKE '".$_SESSION['gdh']['estancias']['es_name']."%') AS consulta ON consulta.Id_Color=colores.Id_Color;";
			}
			$result = MYSQL_QUERY($sql);
			
			if ($_SESSION['gdh']['estancias']['es_habitacion'] == "") {
			  	for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
			  		$fila = MYSQL_FETCH_ARRAY($result);
			  		$sql_factura = "SELECT Num_Factura FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
				  	$result_factura = MYSQL_QUERY($sql_factura);
				  	if (($_SESSION['gdh']['estancias']['mostrar'] == 'D') || (($_SESSION['gdh']['estancias']['mostrar'] == 'F')&&(MYSQL_NUM_ROWS($result_factura) == 0))) {
						$contador = COUNT($estancias);
				  		$estancias[$contador]['es_tipo'] = "G";
				  		$estancias[$contador]['es_dni'] = "Color Grupo";
				  		$estancias[$contador]['es_clave'] = $fila['Nombre_Gr'];
				  		$estancias[$contador]['es_color'] = $fila['Color'];
				  		$estancias[$contador]['es_name'] = $fila['Nombre_Gr'];
				  		$estancias[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
				  		$estancias[$contador]['es_habitacion'] = "";					    
						$sql = "SELECT DISTINCT(Id_Hab) AS Id_Hab FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
						$result2 = MYSQL_QUERY($sql);
				  		for ($j = 0; $j < MYSQL_NUM_ROWS($result2); $j++) {
				  			$fila2 = MYSQL_FETCH_ARRAY($result2);
				  			$estancias[$contador]['es_habitacion'] = $estancias[$contador]['es_habitacion'].$fila2['Id_Hab'].", ";
				  		}
				  		$estancias[$contador]['es_habitacion'] = SUBSTR($estancias[$contador]['es_habitacion'],0,-2);
				  	
					  	if ($_SESSION['gdh']['estancias']['mostrar'] == 'F') {
					  	  	$sql_dias = "SELECT DATEDIFF('".$fila['Fecha_Salida']."',(SELECT CURDATE())) AS dias;";
							$result_dias = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_dias));
						}
						
						if ($result_dias['dias'] < 0) {
						  	$estancias[$contador]['es_fuera'] = true;
						}
						else {
						  	$estancias[$contador]['es_fuera'] = false;
						}
				  		
						if (MYSQL_NUM_ROWS($result_factura) > 0) {
							$fila_factura = MYSQL_FETCH_ARRAY($result_factura);		
					  		$estancias[$contador]['es_tiene_factura'] = true;
							$estancias[$contador]['es_factura'] = $fila_factura['Num_Factura'];// Hay que cambiar esto en caso de factura desglosada.
						}
						else {
					  		$estancias[$contador]['es_tiene_factura'] = false;
							$estancias[$contador]['es_factura'] = 'G*'.$fila['Nombre_Gr'].'*'.$fila['Fecha_Llegada'];
						}
						
					}
				}
			}
			else {
			  	for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
			  		$fila = MYSQL_FETCH_ARRAY($result);
			  		$sql_factura = "SELECT Num_Factura FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
				  	$result_factura = MYSQL_QUERY($sql_factura);
				  	if (($_SESSION['gdh']['estancias']['mostrar'] == 'D') || (($_SESSION['gdh']['estancias']['mostrar'] == 'F')&&(MYSQL_NUM_ROWS($result_factura) == 0))) {
						$sql2 = "SELECT DISTINCT(Id_Hab) AS Id_Hab FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND ".$_SESSION['gdh']['estancias']['es_habitacion']." IN (SELECT DISTINCT(Id_Hab) AS Id_Hab FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."');";
						$result2 = MYSQL_QUERY($sql2);
						if (MYSQL_NUM_ROWS($result2) > 0) {							
							$contador = COUNT($estancias);
					  		$estancias[$contador]['es_tipo'] = "G";
					  		$estancias[$contador]['es_dni'] = "Color Grupo";
					  		$estancias[$contador]['es_clave'] = $fila['Nombre_Gr'];
					  		$estancias[$contador]['es_color'] = $fila['Color'];
					  		$estancias[$contador]['es_name'] = $fila['Nombre_Gr'];
					  		$estancias[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
					  		$estancias[$contador]['es_habitacion'] = "";
					  		for ($j = 0; $j < MYSQL_NUM_ROWS($result2); $j++) {
					  			$fila2 = MYSQL_FETCH_ARRAY($result2);
					  			$estancias[$contador]['es_habitacion'] = $estancias[$contador]['es_habitacion'].$fila2['Id_Hab'].", ";
					  		}
					  		$estancias[$contador]['es_habitacion'] = SUBSTR($estancias[$contador]['es_habitacion'],0,-2);
					  		
							if ($_SESSION['gdh']['estancias']['mostrar'] == 'F') {
						  	  	$sql_dias = "SELECT DATEDIFF('".$fila['Fecha_Salida']."',(SELECT CURDATE())) AS dias;";
								$result_dias = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_dias));
							}
							
							if ($result_dias['dias'] < 0) {
							  	$estancias[$contador]['es_fuera'] = true;
							}
							else {
							  	$estancias[$contador]['es_fuera'] = false;
							}
							
							if (MYSQL_NUM_ROWS($result_factura) > 0) {
								$fila_factura = MYSQL_FETCH_ARRAY($result_factura);		
						  		$estancias[$contador]['es_tiene_factura'] = true;
								$estancias[$contador]['es_factura'] = $fila_factura['Num_Factura'];// Hay que cambiar esto en caso de factura desglosada.
							}
							else {
						  		$estancias[$contador]['es_tiene_factura'] = false;
								$estancias[$contador]['es_factura'] = 'G*'.$fila['Nombre_Gr'].'*'.$fila['Fecha_Llegada'];
							}		
						}
					}
				}
		  	}
		}

		// Ordenamos si se ha pulsado algun criterio de orden:
		
		if (COUNT($estancias) > 1) {
			
			if ($_SESSION['gdh']['estancias']['orden'] == "T") {		  
	
				foreach ($estancias AS $llave => $fila) {
				   $es_tipo[$llave] = $fila['es_tipo'];
				   $es_habitacion[$llave]  = $fila['es_habitacion'];
				}
				
				if ($_SESSION['gdh']['estancias']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es_tipo, SORT_ASC, SORT_STRING, $es_habitacion, SORT_ASC, SORT_NUMERIC, $estancias);			  
				}
				else {
					@ ARRAY_MULTISORT($es_tipo, SORT_DESC, SORT_STRING, $estancias);			  
				}
			}
			else if ($_SESSION['gdh']['estancias']['orden'] == "D") {		  
	
				foreach ($estancias AS $llave => $fila) {
				   $es_clave[$llave] = $fila['es_clave'];
				}
		
				if ($_SESSION['gdh']['estancias']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es_clave, SORT_ASC, SORT_NUMERIC, $estancias);
				}
				else {
					@ ARRAY_MULTISORT($es_clave, SORT_DESC, SORT_NUMERIC, $estancias);
				}			
			}
			else if ($_SESSION['gdh']['estancias']['orden'] == "N") {		  
	
				foreach ($estancias AS $llave => $fila) {
				   $es_name[$llave] = $fila['es_name'];
				}
				
				if ($_SESSION['gdh']['estancias']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es_name, SORT_ASC, SORT_STRING, $estancias);	  
				}
				else {
					@ ARRAY_MULTISORT($es_name, SORT_DESC, SORT_STRING, $estancias);		  
				}			
			}
			else if (($_SESSION['gdh']['estancias']['orden'] == "H")&&($_SESSION['gdh']['estancias']['es_habitacion'] == "")) {		  
	
				foreach ($estancias AS $llave => $fila) {
				   $es_habitacion[$llave] = $fila['es_habitacion'];
				}
				
				if ($_SESSION['gdh']['estancias']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es_habitacion, SORT_ASC, SORT_NUMERIC, $estancias);
				}
				else {
					@ ARRAY_MULTISORT($es_habitacion, SORT_DESC, SORT_NUMERIC, $estancias);  
				}
			}
			else if ($_SESSION['gdh']['estancias']['es_habitacion'] == "") {	  
	
				foreach ($estancias AS $llave => $fila) {
				   $es_habitacion[$llave]  = $fila['es_habitacion'];
				}
				
				@ ARRAY_MULTISORT($es_habitacion, SORT_ASC, SORT_NUMERIC, $estancias);		  	
			}
		}

		for ($i = 0; $i < COUNT($estancias); $i++) {
		  	if ($estancias[$i]['es_fuera'] == false) {
?>
						<tr class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);" height="21px">
							<td align="center" height="21px">
<?PHP
			}
			else {
?>
						<tr class="texto_listados_alerta" onmouseover="resaltar_alerta(this);" onmouseout="desresaltar_alerta(this);" height="21px">
							<td align="center" height="21px">
<?PHP			  
			}
			if ($estancias[$i]['es_tipo'] == "G") {
			  	$sql_factura = "SELECT Num_Factura FROM genera_gr WHERE Nombre_Gr LIKE '".$estancias[$i]['es_clave']."' AND Fecha_Llegada LIKE '".$estancias[$i]['es_fecha']."';";
			  	$result_factura = MYSQL_QUERY($sql_factura);
			  	if (MYSQL_NUM_ROWS($result_factura) > 0) {
				    $fila_factura = MYSQL_FETCH_ARRAY($result_factura);			  	
?>
								<div class="ok" onclick="ver_factura('<?PHP echo $fila_factura['Num_Factura']; ?>');" title="Ver Factura <?PHP echo $fila_factura['es_factura']; ?>">OK</div>
<?PHP
				}
				else {
?>
								<input type="checkbox" name="estancias_gr[]" <?PHP echo 'value="'.$estancias[$i]['es_tipo'].'*'.$estancias[$i]['es_clave'].'*'.$estancias[$i]['es_fecha'].'"';?> onclick="comprobar_grupo_factura(this,'estancias_gr');">
<?PHP
				}
			}
			else if ($estancias[$i]['es_tipo'] == "A") {
				if ($estancias[$i]['es_tiene_factura'] == true)	{
?>
								<div class="ok" onclick="ver_factura('<?PHP echo $estancias[$i]['es_factura']; ?>');" title="Ver Factura <?PHP echo $estancias[$i]['es_factura']; ?>">OK</div>
<?PHP
				}
				else {
?>
								<input type="checkbox" name="estancias[]" value="<?PHP echo $estancias[$i]['es_factura']; ?>" onclick="comprobar_grupo_factura(this,'estancias');">
<?PHP
				}
			}
			else if ($estancias[$i]['es_tipo'] == "P") {
			  	if ($estancias[$i]['es_tiene_factura'] == true)	{	  	
?>
								<div class="ok" onclick="ver_factura('<?PHP echo $estancias[$i]['es_factura']; ?>');" title="Ver Factura <?PHP echo $estancias[$i]['es_factura']; ?>">OK</div>
<?PHP
				}
				else {
?>
								<input type="checkbox" name="estancias_p[]" value="<?PHP echo $estancias[$i]['es_factura']; ?>" onclick="comprobar_grupo_factura(this,'estancias_p');">
<?PHP
				}
			}
?>

							</td>
							<td align="center" onclick="detalles('<?PHP echo $estancias[$i]['es_tipo'].'D\',\''.$estancias[$i]['es_clave'].'\',\''.$estancias[$i]['es_fecha'];?>');">
<?PHP
								echo $estancias[$i]['es_tipo'];
?>
							</td>
							<td align="left" <?PHP if ($estancias[$i]['es_tipo'] == "G") echo 'style="color:#'.$estancias[$i]['es_color'].';"'; ?> onclick="detalles('<?PHP echo $estancias[$i]['es_tipo'].'D\',\''.$estancias[$i]['es_clave'].'\',\''.$estancias[$i]['es_fecha'];?>');">
<?PHP
								if (STRLEN($estancias[$i]['es_dni']) > 15) {
									echo SUBSTR($estancias[$i]['es_dni'],0,15);
								}
								else {
									echo $estancias[$i]['es_dni'];
								}
?>
							</td>
							<td align="left" onclick="detalles('<?PHP echo $estancias[$i]['es_tipo'].'D\',\''.$estancias[$i]['es_clave'].'\',\''.$estancias[$i]['es_fecha'];?>');">
<?PHP
								echo $estancias[$i]['es_name'];
?>
							</td>
							<td align="left" onclick="detalles('<?PHP echo $estancias[$i]['es_tipo'].'D\',\''.$estancias[$i]['es_clave'].'\',\''.$estancias[$i]['es_fecha'];?>');">
<?PHP
								echo $estancias[$i]['es_habitacion'];
?>
							</td>
						</tr>
<?PHP
		}
		MYSQL_CLOSE($db);
	}
	
	if (COUNT($estancias) < $_SESSION['gdh']['estancias']['configuracion_numero_filas']) {
	  	for ($i = COUNT($estancias); $i < $_SESSION['gdh']['estancias']['configuracion_numero_filas']; $i++) {
?>
						<tr class="texto_listados" height="21px">
							<td align="center">&nbsp;
							</td>
							<td align="center">&nbsp;
							</td>
							<td align="left">&nbsp;
							</td>
							<td align="left">&nbsp;
							</td>
							<td align="left">&nbsp;
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