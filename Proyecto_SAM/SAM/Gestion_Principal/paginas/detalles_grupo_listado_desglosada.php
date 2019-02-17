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
					Listado de Facturas
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
					<td rowspan="8" width="5px">
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
					<td class="texto_detalles" colspan="4" align="left">
						Listado de Facturas del Grupo:
					</td>					
				</tr>
<?PHP
		$sql = "SELECT DISTINCT(Num_Factura) AS Num_Factura FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."' ORDER BY Num_Factura;";
		//echo $sql;
		$result3 = MYSQL_QUERY($sql);
?>
				<tr>
					<td align="left" style="padding:0px 0px 0px 0px;" width="100%" colspan="4">
						<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_resumen_desglosada']; ?>;">
							<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
								<thead class="fixedHeader">
									<tr>
										<th align="right" width="87px">
											Nº Factura
										</th>
										<th align="left" width="130px">
											D.N.I.
										</th>
										<th align="right" width="60px">
											Pagado
										</th>
										<th align="center" width="20px">
											Ver
										</th>
									</tr>
								</thead>
								<tbody class="scrollContent">
<?PHP
  		for ($j = 0; $j < MYSQL_NUM_ROWS($result3); $j++) {
  			$fila3 = MYSQL_FETCH_ARRAY($result3);
  			$sql_factura = "SELECT * FROM factura WHERE Num_Factura LIKE '".$fila3['Num_Factura']."';";
  			$fila_factura = MYSQL_FETCH_ARRAY(MYSQL_QUERY($sql_factura));
?>							
									<tr class="texto_listados" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>">
										<td align="right">
											<?PHP echo $fila_factura['Num_Factura']; ?>
								  		</td>
										<td align="left">
											<?PHP echo $fila_factura['DNI']; ?>&nbsp;
								  		</td>
										<td align="right">
											<?PHP echo $fila_factura['Ingreso'] + FLOATVAL($fila_factura['Cuantia_Desperfecto']); ?>
								  		</td>
										<td align="center">
											<img title='Ver Factura' src='../imagenes/botones/detalles.gif' style="cursor:pointer;" onclick="window.location.href = 'paginas/factura.php?numf=<?PHP echo $fila_factura['Num_Factura'];?>';"/>
										</td>
								  	</tr>
<?PHP
		}	
		if (MYSQL_NUM_ROWS($result3) < $_SESSION['gdh']['factura']['configuracion_numero_filas']) {
	  		for ($i = MYSQL_NUM_ROWS($result3); $i <= $_SESSION['gdh']['resumen_desglosada']['configuracion_numero_filas']; $i++) {
?>
									<tr class="texto_listados" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>">
										<td>&nbsp;
										</td>
										<td>&nbsp;
										</td>
										<td>&nbsp;
										</td>
										<td>&nbsp;
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
				<tr>
					<td colspan="5" height="43px" valign="bottom">
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