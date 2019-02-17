<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
					Componentes del Grupo
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_componentes']; ?>;">
				<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
					<thead class="fixedHeader">
						<tr height="24px">
							<th width="100px" align="left">D.N.I.</th>
							<th width="120px" align="left">Nombre</th>
							<th width="232px" align="left">Apellidos</th>
							<th width="50px" align="left">Sexo</th>
							<th width="90px" align="left">Fecha Nac.</th>
							<th width="125px" align="left">País</th>
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
	  	$sql = "SELECT * FROM estancia_gr,componentes_grupo,pais WHERE estancia_gr.Nombre_Gr LIKE componentes_grupo.Nombre_Gr AND estancia_gr.Fecha_Llegada LIKE componentes_grupo.Fecha_Llegada AND estancia_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND estancia_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' AND pais.Id_Pais LIKE componentes_grupo.Id_Pais_nacionalidad ORDER BY Apellido1;";
		$result = MYSQL_QUERY($sql);
		
		for ($i = 0; $i < MYSQL_NUM_ROWS($result); $i++) {
		  	$fila = MYSQL_FETCH_ARRAY($result);	  
?>
						<tr class="texto_listados" height="22px">
							<td align="left"><?PHP echo $fila['DNI']; ?>
							</td>
							<td align="left"><?PHP echo $fila['Nombre']; ?>
							</td>
							<td align="left"><?PHP echo $fila['Apellido1'].' '.$fila['Apellido2']; ?>
							</td>
							<td align="left"><?PHP echo $fila['Sexo']; ?>
							</td>
							<td align="left"><?PHP echo fecha_ordenada($fila['Fecha_nacimiento']); ?>
							</td>
							<td align="left"><?PHP echo $fila['Nombre_Pais']; ?>
							</td>
						</tr>
<?PHP
	
		}
		
		if (MYSQL_NUM_ROWS($result) < $_SESSION['gdh']['componentes']['configuracion_numero_filas']) {
		  	for ($i = 0; $i < ($_SESSION['gdh']['componentes']['configuracion_numero_filas'] - MYSQL_NUM_ROWS($result)); $i++) {
?>
						<tr class="texto_listados" height="22px">
							<td align="left">&nbsp;
							</td>
							<td align="left">&nbsp;
							</td>
							<td align="left">&nbsp;
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
		
		MYSQL_CLOSE($db);			
	}
	
?>
					</tbody>
				</table>
			</div>							
		</td>
	</tr>	
</table>