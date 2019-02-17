<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_checks_ancho']; ?>;">
					CHECK-IN
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
							<th width="30px" align="center"><div title="Ordenar por tipo" onclick="ordenar_estancias('checkin','T','<?PHP if ($_SESSION['gdh']['checkin']['orden'] == "T") { if ($_SESSION['gdh']['checkin']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Tipo</div></th>
							<th width="225px" align="left"><div title="Ordenar por nombre y apellidos" onclick="ordenar_estancias('checkin','N','<?PHP if ($_SESSION['gdh']['checkin']['orden'] == "N") { if ($_SESSION['gdh']['checkin']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Nombre y Apellidos</div></th>
							<th width="25px" align="left"><div title="Ordenar por Habitaciones" onclick="ordenar_estancias('checkin','H','<?PHP if ($_SESSION['gdh']['checkin']['orden'] == "H") { if ($_SESSION['gdh']['checkin']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">Hab</div></th>
							<th width="30px" align="center"><div title="Ordenar por Entrada" onclick="ordenar_estancias('checkin','E','<?PHP if ($_SESSION['gdh']['checkin']['orden'] == "E") { if ($_SESSION['gdh']['checkin']['modo'] == "A") {echo 'D';} else {echo 'A';}} else {echo 'A';} ?>');">IN</div></th>
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
	  	
	  	$checkin = array();
	  	
		$total_check = 0;
		  
		// Estancias de alberguistas
		$sql = "SELECT * FROM pernocta LEFT JOIN cliente ON pernocta.DNI_Cl=cliente.DNI_Cl WHERE pernocta.Fecha_Llegada = '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
		$result = MYSQL_QUERY($sql);		
		$total_check += MYSQL_NUM_ROWS($result);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
			$contador = COUNT($checkin);
		  	$checkin[$contador]['es_tipo'] = "A";
		  	$checkin[$contador]['es_name'] = $fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
		  	$checkin[$contador]['es_habitacion'] = $fila['Id_Hab'];
		  	$checkin[$contador]['es_clave'] = $fila['DNI_Cl'];
		  	$checkin[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
			
			// Comprobamos que no ha pernoctado el dia anterior en otra habitación;
	  		$sql = "SELECT * FROM pernocta WHERE DNI_Cl = '".$fila['DNI_Cl']."' AND Fecha_Salida = '".$fila['Fecha_Llegada']."';";
	  		$comprobar_pernocta = MYSQL_QUERY($sql);
	  		
	  		if (MYSQL_NUM_ROWS($comprobar_pernocta) == 0) {
	  		  	$checkin[$contador]['in'] = true;
		  	}
		  	else {
			    $checkin[$contador]['in'] = false;
			}
	  	}
	  	
		// Estancias de peregrinos
		$sql = "SELECT * FROM pernocta_p LEFT JOIN cliente ON pernocta_p.DNI_Cl=cliente.DNI_Cl WHERE pernocta_p.Fecha_Llegada = '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
		$result = MYSQL_QUERY($sql);		
		$total_check += MYSQL_NUM_ROWS($result);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
	  		$contador = COUNT($checkin);
	  		$checkin[$contador]['es_tipo'] = "P";
	  		$checkin[$contador]['in'] = true;
	  		$checkin[$contador]['es_name'] = $fila['Nombre_Cl'].' '.$fila['Apellido1_Cl'].' '.$fila['Apellido2_Cl'];
	  		$checkin[$contador]['es_habitacion'] = $fila['Id_Hab'];
	  		$checkin[$contador]['es_clave'] = $fila['DNI_Cl'];
	  		$checkin[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
	  	}		  
		
		// Estancias de grupos
		$sql = "SELECT Nombre_Gr,Fecha_Llegada FROM estancia_gr WHERE Fecha_Llegada = '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
		$result = MYSQL_QUERY($sql);		
		$total_check += MYSQL_NUM_ROWS($result);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
	  		$contador = COUNT($checkin);
	  		$checkin[$contador]['es_tipo'] = "G";
	  		$checkin[$contador]['in'] = true;
	  		$checkin[$contador]['es_name'] = $fila['Nombre_Gr'];
	  		$checkin[$contador]['es_clave'] = $fila['Nombre_Gr'];
	  		$checkin[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
	  		$sql = "SELECT COUNT(Id_Hab) AS Id_Hab FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."' GROUP BY Id_Hab;";
			$aux = MYSQL_QUERY($sql);
			//echo $sql;
			$total_habitaciones_grupo = 0;
			for ($j = 0; $j < MYSQL_NUM_ROWS($aux); $j++) {
				$aux2 = MYSQL_FETCH_ARRAY($aux);
				$total_habitaciones_grupo++;
			}
	  		$checkin[$contador]['es_habitacion'] = "*".$total_habitaciones_grupo;	  		
	  	}			  
		
		// Reservas para hoy
		$sql = "SELECT detalles.Observaciones_PRA,detalles.DNI_PRA,detalles.Fecha_Llegada,detalles.Fecha_Salida,CONCAT(pra.Nombre_PRA,' ',pra.Apellido1_PRA,' ',pra.Apellido2_PRA) AS Name FROM detalles LEFT JOIN pra ON detalles.DNI_PRA=pra.DNI_PRA WHERE Fecha_Llegada = '".$_SESSION['gdh']['anio']."-".$_SESSION['gdh']['mes']."-".$_SESSION['gdh']['dia']."';";
		$result = MYSQL_QUERY($sql);		
		$total_check += MYSQL_NUM_ROWS($result);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
	  		$fila = MYSQL_FETCH_ARRAY($result);
	  		$contador = COUNT($checkin);
	  		if ($fila['Fecha_Llegada'] == $fila['Fecha_Salida']) { // R1 es para reservas facturadas por 'NO PRESENTADO' y R2 para reservas normales
	  			$checkin[$contador]['es_tipo'] = "R1";	    
			}
			else {
	  			$checkin[$contador]['es_tipo'] = "R2";			  
			 
			}
	  		$checkin[$contador]['es_clave'] = $fila['DNI_PRA'];
			$checkin[$contador]['es_name'] = $fila['Name'];
			$checkin[$contador]['es_fecha'] = $fila['Fecha_Llegada'];
			$checkin[$contador]['es_fecha_salida'] = $fila['Fecha_Salida'];
	  		if (($fila['Observaciones_PRA'] != NULL) && ($fila['Observaciones_PRA'] != '')) {
				$checkin[$contador]['es_reserva_obs'] = true;
			}
			else {
				$checkin[$contador]['es_reserva_obs'] = false;
			}		
	  		$hab = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT COUNT(Id_Hab) AS Id_Hab FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';"));
	  		if ($hab['Id_Hab'] == "1") {
			    $hab = MYSQL_FETCH_ARRAY(MYSQL_QUERY("SELECT Id_Hab FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';"));
				$checkin[$contador]['es_habitacion'] = $hab['Id_Hab'];
			}
			else {
			  	$checkin[$contador]['es_habitacion'] = "*".$hab['Id_Hab'];
			}
	  	}

		// Ordenamos
		
		if (COUNT($checkin) > 1) {
		
			if ($_SESSION['gdh']['checkin']['orden'] == "T") {		  
	
				foreach ($checkin AS $llave => $fila) {
				   $es[$llave] = $fila['es_tipo'];
				}
				
				if ($_SESSION['gdh']['checkin']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, SORT_STRING, $checkin);			  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, SORT_STRING, $checkin);			  
				}
			}
			else if ($_SESSION['gdh']['checkin']['orden'] == "N") {		  
	
				foreach ($checkin AS $llave => $fila) {
				   $es[$llave] = $fila['es_name'];
				}
				
				if ($_SESSION['gdh']['checkin']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, SORT_STRING, $checkin);	  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, SORT_STRING, $checkin);		  
				}					
			}
			else if ($_SESSION['gdh']['checkin']['orden'] == "H") {		  
	
				foreach ($checkin AS $llave => $fila) {
				   $es[$llave] = $fila['es_habitacion'];
				}
				
				if ($_SESSION['gdh']['checkin']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, $checkin);	  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, $checkin);		  
				}					
			}
			else if ($_SESSION['gdh']['checkin']['orden'] == "E") {		  
	
				foreach ($checkin AS $llave => $fila) {
				   $es[$llave] = $fila['es_tipo'];
				}
				
				if ($_SESSION['gdh']['checkin']['modo'] == "A") {	
					@ ARRAY_MULTISORT($es, SORT_ASC, $checkin);	  
				}
				else {
					@ ARRAY_MULTISORT($es, SORT_DESC, $checkin);		  
				}					
			}
		}
		
		for ($i = 0; $i < COUNT($checkin); $i++) {
?>
						<tr class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);" onclick="detalles('<?PHP echo SUBSTR($checkin[$i]['es_tipo'],0,1).'D\',\''.$checkin[$i]['es_clave'].'\',\''.$checkin[$i]['es_fecha'];?>');">
							<td align="center">
<?PHP
								if (($checkin[$i]['es_tipo'] == "R1")||($checkin[$i]['es_tipo'] == "R2")) {
									if ($checkin[$i]['es_reserva_obs'] == true) {
								  		echo "Res*";
								  	}
								  	else {
								  		echo "Res";
									}
								}
								else {
								  	echo $checkin[$i]['es_tipo'];
								}
?>
							</td>
							<td align="left">
<?PHP
								if (STRLEN($checkin[$i]['es_name']) <= $longitud_nombre) {
									echo $checkin[$i]['es_name'];
								}
								else {
									echo SUBSTR($checkin[$i]['es_name'],0,$longitud_nombre );
								}
?>
							</td>
							<td align="right">
<?PHP
								echo $checkin[$i]['es_habitacion'];
?>
							</td>
							<td align="center"> 
<?PHP 
			if ($checkin[$i]['es_tipo'] == "R2") {			  				
?>
								<div class="no">NO</div>
<?PHP
			}
			else if ($checkin[$i]['es_tipo'] == "R1") {			  				
?>
								<div>NP</div>
<?PHP 
			}
			else { 
			  	if ($checkin[$i]['in']) {
?>
								<div class="ok">OK</div>
<?PHP
				}
				else {
?>
								<div>CH</div>
<?PHP
				}
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