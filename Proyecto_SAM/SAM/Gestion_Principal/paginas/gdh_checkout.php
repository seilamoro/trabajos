<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_checks_ancho']; ?>;">
					CHECK-OUT
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_checks_alto']; ?>;">
				<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
					<thead class="fixedHeader">
						<tr>
							<th width="30px" align="center"><div title="Ordenar por tipo" onclick="ordenar_estancias('checkout','T','<?PHP if ($_SESSION['gdh']['checkout']['orden'] == "T") { if ($_SESSION['gdh']['checkout']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Tipo</div></th>
							<th width="225px" align="left"><div title="Ordenar por nombre y apellidos" onclick="ordenar_estancias('checkout','N','<?PHP if ($_SESSION['gdh']['checkout']['orden'] == "N") { if ($_SESSION['gdh']['checkout']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Nombre y Apellidos</div></th>
							<th width="25px" align="left"><div title="Ordenar por Habitaciones" onclick="ordenar_estancias('checkout','H','<?PHP if ($_SESSION['gdh']['checkout']['orden'] == "H") { if ($_SESSION['gdh']['checkout']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Hab</div></th>
							<th width="30px" align="center"><div title="Ordenar por Facturación" onclick="ordenar_estancias('checkout','F','<?PHP if ($_SESSION['gdh']['checkout']['orden'] == "F") { if ($_SESSION['gdh']['checkout']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Fac</div></th>
						</tr>
					</thead>
					<tbody class="scrollContent">
<?PHP

	$longitud_nombre = 27;

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
	
	if (!$db) {
	  
?>
	<tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr>
<?PHP

	}
	else {
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$checkout = array();
	  	
		$total_check = 0;
		  
		// Estancias de alberguistas
		$sql = "SELECT * FROM pernocta LEFT JOIN cliente ON pernocta.DNI_Cl=cliente.DNI_Cl WHERE pernocta.Fecha_Salida = '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
		$result = MYSQL_QUERY($sql);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
	  		
	  		// Comprobamos que no va a pernoctar el día siguiente en otra habitación;
	  		$sql = "SELECT * FROM pernocta WHERE DNI_Cl = '".$fila['DNI_Cl']."' AND Fecha_Llegada = '".$fila['Fecha_Salida']."';";
	  		$comprobar_pernocta = MYSQL_QUERY($sql);
	  		if (MYSQL_NUM_ROWS($comprobar_pernocta) == 0) {
	  		  	$total_check++;
		  		$contador = COUNT($checkout);
		  		$checkout[$contador]['es_tipo'] = "A";
		  		$checkout[$contador]['es_name'] = $fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
		  		$checkout[$contador]['es_habitacion'] = $fila['Id_Hab'];
		  		$checkout[$contador]['es_clave'] = $fila['DNI_Cl'];
		  		$checkout[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
		  		$sql = "SELECT * FROM genera WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		  		$factura = MYSQL_QUERY($sql);
		  		if (MYSQL_NUM_ROWS($factura) > 0) {
				    $checkout[$contador]['es_factura'] = true;
				}
				else {
				  	$checkout[$contador]['es_factura'] = false;
				}
			}
	  	}
	  	
		// Estancias de peregrinos
		$sql = "SELECT * FROM pernocta_p LEFT JOIN cliente ON pernocta_p.DNI_Cl=cliente.DNI_Cl WHERE pernocta_p.Fecha_Salida = '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
		$result = MYSQL_QUERY($sql);		
		$total_check += MYSQL_NUM_ROWS($result);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
	  		$contador = COUNT($checkout);
	  		$checkout[$contador]['es_tipo'] = "P";
	  		$checkout[$contador]['es_name'] = $fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
	  		$checkout[$contador]['es_habitacion'] = $fila['Id_Hab'];
	  		$checkout[$contador]['es_clave'] = $fila['DNI_Cl'];
	  		$checkout[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
	  		$sql = "SELECT * FROM genera_p WHERE DNI_Cl LIKE '".$fila['DNI_Cl']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
	  		$factura = MYSQL_QUERY($sql);
	  		if (MYSQL_NUM_ROWS($factura) > 0) {
			    $checkout[$contador]['es_factura'] = true;
			}
			else {
			  	$checkout[$contador]['es_factura'] = false;
			}
	  	}		  
		
		// Estancias de grupos
		$sql = "SELECT Nombre_Gr,Fecha_Llegada FROM estancia_gr WHERE Fecha_Salida = '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
		$result = MYSQL_QUERY($sql);		
		$total_check += MYSQL_NUM_ROWS($result);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
	  		$contador = COUNT($checkout);
	  		$checkout[$contador]['es_tipo'] = "G";
	  		$checkout[$contador]['es_name'] = $fila['Nombre_Gr'];
	  		$checkout[$contador]['es_clave'] = $fila['Nombre_Gr'];
	  		$checkout[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
	  		$sql = "SELECT COUNT(Id_Hab) AS Id_Hab FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."' GROUP BY Id_Hab;";
			$aux = MYSQL_QUERY($sql);
			//echo $sql;
			$total_habitaciones_grupo = 0;
			for ($j = 0; $j < MYSQL_NUM_ROWS($aux); $j++) {
				$aux2 = MYSQL_FETCH_ARRAY($aux);
				$total_habitaciones_grupo++;
			}
	  		$checkout[$contador]['es_habitacion'] = "*".$total_habitaciones_grupo;	  
	  		$sql = "SELECT * FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
	  		$factura = MYSQL_QUERY($sql);
	  		if (MYSQL_NUM_ROWS($factura) > 0) {
			    $checkout[$contador]['es_factura'] = true;
			}
			else {
			  	$checkout[$contador]['es_factura'] = false;
			}
	  	}		  
		
		// Reservas no validadas, es decir, que no ha venido la o las personas que han reservado.
		$sql = "SELECT detalles.Observaciones_PRA,detalles.DNI_PRA,detalles.Fecha_Llegada,CONCAT(pra.Nombre_PRA,' ',pra.Apellido1_PRA,' ',pra.Apellido2_PRA) AS Name FROM detalles LEFT JOIN pra ON detalles.DNI_PRA=pra.DNI_PRA WHERE detalles.Fecha_Llegada < '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."' AND detalles.Fecha_Llegada != detalles.Fecha_Salida;";
		//echo $sql;
		$result = MYSQL_QUERY($sql);		
		$total_check += MYSQL_NUM_ROWS($result);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
	  		$contador = COUNT($checkout);
	  		$checkout[$contador]['es_tipo'] = "R";
			$checkout[$contador]['es_name'] = $fila['Name'];
			$checkout[$contador]['es_clave'] = $fila['DNI_PRA'];
			$checkout[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
			if (($fila['Observaciones_PRA'] != NULL) && ($fila['Observaciones_PRA'] != '')) {
				$checkout[$contador]['es_reserva_obs'] = true;
			}
			else {
				$checkout[$contador]['es_reserva_obs'] = false;
			}
	  		
			$hab = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(Id_Hab) AS Id_Hab FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';"));
	  		if ($hab['Id_Hab'] == "1") {
			    $hab = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT Id_Hab FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';"));
				$checkout[$contador]['es_habitacion'] = $hab['Id_Hab'];
			}
			else {
			  	$checkout[$contador]['es_habitacion'] = "*".$hab['Id_Hab'];
			}
	  	}

		// Ordenamos
		
		if (COUNT($checkout) > 1) {
		
			if ($_SESSION['gdh']['checkout']['orden'] == "T") {		  
	
				foreach ($checkout AS $llave => $fila) {
				   $es[$llave] = $fila['es_tipo'];
				}
				
				if ($_SESSION['gdh']['checkout']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, SORT_STRING, $checkout);			  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, SORT_STRING, $checkout);			  
				}
			}
			else if ($_SESSION['gdh']['checkout']['orden'] == "N") {		  
	
				foreach ($checkout AS $llave => $fila) {
				   $es[$llave] = $fila['es_name'];
				}
				
				if ($_SESSION['gdh']['checkout']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, SORT_STRING, $checkout);	  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, SORT_STRING, $checkout);		  
				}					
			}
			else if ($_SESSION['gdh']['checkout']['orden'] == "H") {		  
	
				foreach ($checkout AS $llave => $fila) {
				   $es[$llave] = $fila['es_habitacion'];
				}
				
				if ($_SESSION['gdh']['checkout']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, $checkout);	  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, $checkout);		  
				}					
			}
			else if ($_SESSION['gdh']['checkout']['orden'] == "F") {		  
	
				foreach ($checkout AS $llave => $fila) {
				   $es[$llave] = $fila['es_factura'];
				}
				
				if ($_SESSION['gdh']['checkout']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, SORT_STRING, $checkout);	  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, SORT_STRING, $checkout);		  
				}					
			}
		}
		
		for ($i = 0; $i < COUNT($checkout); $i++) {
?>
						<tr class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);" onclick="detalles('<?PHP echo $checkout[$i]['es_tipo'].'D\',\''.$checkout[$i]['es_clave'].'\',\''.$checkout[$i]['es_fecha'];?>');">
<?PHP
								if ($checkout[$i]['es_tipo'] == "R") {
?>
							<td align="center" class="no">
<?PHP
									if ($checkout[$i]['es_reserva_obs'] == true) {
								  		echo "Res*";
								  	}
								  	else {
								  		echo "Res";
									}
								}
								else {
?>
							<td align="center">
<?PHP
								  	echo $checkout[$i]['es_tipo'];
								}
?>
							</td>
							<td align="left">
<?PHP
								if (STRLEN($checkout[$i]['es_name']) <= $longitud_nombre) {
									echo $checkout[$i]['es_name'];
								}
								else {
									echo SUBSTR($checkout[$i]['es_name'],0,$longitud_nombre);
								}
?>
							</td>
							<td align="right">
<?PHP
								echo $checkout[$i]['es_habitacion'];
?>
							</td>
							<td align="center">
<?PHP
			if ($checkout[$i]['es_factura']) {
?>
								<div class="ok">OK</div>
<?PHP
			}
			else {
?>
								<div class="no">NO</div>
<?PHP
			}
?>								
							</td>
						</tr>
<?PHP
		}

		MYSQL_CLOSE($db);
	}
	
	if ($total_check < $_SESSION['gdh']['check']['configuracion_numero_filas']) {
	  	for ($i = $total_check; $i < $_SESSION['gdh']['check']['configuracion_numero_filas']; $i++) {
?>
						<tr class="texto_listados">
							<td align="center">&nbsp;
							</td>
							<td align="left">&nbsp;
							</td>
							<td align="left">&nbsp;
							</td>
							<td align="right">&nbsp;
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