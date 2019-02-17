<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);	
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$sql = "SELECT * FROM estancia_gr WHERE Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
		$result = MYSQL_QUERY($sql);
		$fila = MYSQL_FETCH_ARRAY($result);
?>

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_detalles_ancho']; ?>;">
					Detalles Estancia
			</div>
			<div class="champi_derecha">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" style="padding:0px 0px 0px 0px; background-color: <?PHP echo $_SESSION['gdh']['colores']['normal']; ?>;" width="100%">
			<table border="0" width="100%" height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_detalles_alto']; ?>" style="border: 1px solid #3F7BCC;">
				<tr>
					<td rowspan="7" width="5px">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre Gr:
					</td>
					<td colspan="3" align="left">
						<input type="text" name="nombregrupo" size=35 contenteditable=false class="input_text" value="<?PHP echo $fila['Nombre_Gr']; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						D.N.I. Rep:
					</td>
					<td align="left">
						<input type="text" name="dni" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['DNI_Repres']; ?>">
					</td>						
					<td class="texto_detalles" align="left">
						Teléfono:
					</td>
					<td align="left">
						<input type="text" name="telefono" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Tfno_Repres']; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre Rep:
					</td>
					<td colspan="3" align="left">
						<input type="text" name="representante" size=35 contenteditable=false class="input_text" value="<?PHP echo $fila['Nombre_Repres'].' '.$fila['Apellido1_Repres'].' '.$fila['Apellido2_Repres']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Habitación:
					</td>
					<td align="left">					
<?PHP
		$habitaciones = "";
		$sql = "SELECT DISTINCT(Id_Hab) AS Id_Hab FROM pernocta_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$result2 = MYSQL_QUERY($sql);
  		for ($j = 0; $j < MYSQL_NUM_ROWS($result2); $j++) {
  			$fila2 = MYSQL_FETCH_ARRAY($result2);
  			$habitaciones = $habitaciones.$fila2['Id_Hab'].", ";
  		}
  		$habitaciones = SUBSTR($habitaciones,0,-2);
?>
						<input type="text" name="habitacion" size=9 contenteditable=false class="input_text" value="<?PHP echo $habitaciones; ?>">
					</td>						
					<td class="texto_detalles" align="left">
						Camas:
					</td>
					<td align="left">
						<input type="text" name="camas" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Num_Personas']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" colspan="5" align="center">
						<table border="2" cellspacing="0" bordercolor="#3F7BCC" height="100">
							<tr>
								<td colspan="2" align="center" class="titulo1_tabla_grupos" width="110px">
									Grupo Edad
								</td>
<?PHP
  		$edades = MYSQL_QUERY("SELECT * FROM edad;");
  		$tipo_habitaciones = MYSQL_QUERY("SELECT * FROM tipo_habitacion ORDER BY Id_Tipo_Hab;");  		
?>
								<td colspan="<?PHP echo MYSQL_NUM_ROWS($tipo_habitaciones); ?>" align="center" class="titulo1_tabla_grupos">
									Tipo Habitación
								</td>
							</tr>
							<tr>
								<td align="center" class="titulo2_tabla_grupos" width="55px">
									Mín
								</td>
								<td align="center" class="titulo2_tabla_grupos" width="55px">
									Máx
								</td>
<?PHP  		
  		for ($i = 0; $i < MYSQL_NUM_ROWS($tipo_habitaciones); $i++) {
  		  	$fila3 = MYSQL_FETCH_ARRAY($tipo_habitaciones);
  		  	
			echo '<td align="center" class="titulo2_tabla_grupos" width="35px">';
  			echo SUBSTR($fila3['Nombre_Tipo_Hab'],0,3).".";
			echo '</td>	';
  		}
  		
  		
  		
  		for ($i = 0; $i < MYSQL_NUM_ROWS($edades); $i++) {
  		  	$edad = MYSQL_FETCH_ARRAY($edades);
?>
							<tr>
								<td align="right" class="edades_tabla_grupos">
<?PHP
			echo $edad['Edad_Min'].'&nbsp;';
?>
								</td>
								<td align="right" class="edades_tabla_grupos">
<?PHP
			echo $edad['Edad_Max'].'&nbsp;';
?>
								</td>
<?PHP
  			$tipo_habitaciones = MYSQL_QUERY("SELECT * FROM tipo_habitacion ORDER BY Id_Tipo_Hab;");			
			for ($j = 0; $j < MYSQL_NUM_ROWS($tipo_habitaciones); $j++) {
			  	$fila4 = MYSQL_FETCH_ARRAY($tipo_habitaciones);
			  	
			  	$sql_tarifa = "SELECT * FROM tarifa WHERE Id_Edad LIKE '".$edad['Id_Edad']."' AND Id_Tipo_Hab LIKE '".$fila4['Id_Tipo_Hab']."' AND Id_Tipo_Persona IN (SELECT Id_Tipo_Persona FROM tipo_persona WHERE Nombre_Tipo_Persona LIKE 'Grupo');";
			  	$result_tarifa = MYSQL_QUERY($sql_tarifa);
			  	if (MYSQL_NUM_ROWS($result_tarifa) > 0) {
					$fila5 = MYSQL_FETCH_ARRAY($result_tarifa);
					$title = $fila5['Tarifa'].' € / noche y persona';
				}
				else {
					$title = 'No hay Tarifa de Grupo para este caso';
				}
?>
								<td class="td_tabla_grupos" align="right" style="cursor:help;" title="<?PHP echo $title; ?>">
<?PHP 			
				$sql_hab = "SELECT SUM(Num_Personas) AS Num_Personas FROM pernocta_gr WHERE Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."' AND Id_Edad LIKE '".$edad['Id_Edad']."' AND Id_Hab IN (SELECT consulta.Id_Hab FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fila['Fecha_Llegada']."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab WHERE cambio_tipo_habitacion.Id_Tipo_Hab='".$fila4['Id_Tipo_Hab']."');";
				//echo $sql_hab;
				$numero = MYSQL_QUERY($sql_hab);
				$numero_personas = MYSQL_FETCH_ARRAY($numero);
				
				if ($numero_personas['Num_Personas'] == NULL) {
					$numero_p = "0";
				}
				else {
					$numero_p = $numero_personas['Num_Personas'];
				}
				
				echo $numero_p;
?>
									&nbsp;
								</td>
<?PHP
	  		}
?>
							</tr>
<?PHP
  		}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="5" height="47px" valign="bottom">
						<table width="100%" border="0">
							<tr>
								<td align="center">
				  					<img src="./../imagenes/botones/volver.gif" class="imagen-boton" title="Volver a Detalles Estancia" onclick="detalles('GD',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
								</td>
							</tr>
						</table>
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