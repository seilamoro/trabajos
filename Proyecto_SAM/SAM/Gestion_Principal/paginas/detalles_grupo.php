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
<?PHP
		if (isset($fila['Id_Servicios'])) {
?>
					<td rowspan="11" width="5px">
					<td>
<?PHP
		}
		else {
?>
					<td rowspan="10" width="5px">
					<td>
<?PHP
		}
?>
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
						<input type="text" name="telefono" size=11 contenteditable=false class="input_text" value="<?PHP echo $fila['Tfno_Repres']; ?>">
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
					<td colspan="3" align="left">			
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
						<input type="text" name="habitacion" size=35 contenteditable=false class="input_text" value="<?PHP echo $habitaciones; ?>">
					</td>					
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Llegada:
					</td>
					<td align="left">
						<input type="text" name="fechallegada" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Llegada']); ?>">
					</td>						
					<td class="texto_detalles" align="left">
						Salida:
					</td>
					<td align="left">
						<input type="text" name="fechasalida" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Salida']); ?>">
					</td>						
				</tr>
				<tr>						
					<td class="texto_detalles" align="left">
						Camas:
					</td>
					<td align="left">
						<input type="text" name="camas" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Num_Personas']; ?>">
					</td>	
					<td class="texto_detalles" align="left">
						Noches P.:
					</td>
					<td align="left">
<?PHP
		if ($fila['Noches_Pagadas'] == NULL) {
		  	$pagadas = "0";
		}
		else {
		  	$pagadas = $fila['Noches_Pagadas'];
		}
?>
						<input type="text" name="nochespagadas" size=9 contenteditable=false class="input_text" value="<?PHP echo $pagadas.' / '.$fila['PerNocta']; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Facturado:
					</td>
					<td align="left">
<?PHP	
		$sql = "SELECT DISTINCT(Num_Factura) AS NumFactura FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$facturas = MYSQL_QUERY($sql);
		if (MYSQL_NUM_ROWS($facturas) == 0) {
		  	$factura = NULL;
		}
		else {
		  	$factura = "SI";
		  	if (MYSQL_NUM_ROWS($facturas) > 1) {
			  	$facturas = true;
			}
			else {
			  	$facturas = false;			  
			}
		  	$sql_factura = "SELECT Num_Factura FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		  	$result_factura = MYSQL_QUERY($sql_factura);
		  	if (MYSQL_NUM_ROWS($result_factura) == 1) {
			    $fila_factura = MYSQL_FETCH_ARRAY($result_factura);
			}
			else { //esto hay que cambiarlo porque es la desglosada.
			  	$fila_factura = MYSQL_FETCH_ARRAY($result_factura);
			}
		}
?>
						<input type="text" name="facturado" size=3 contenteditable=false class="input_text" value="<?PHP if ($factura == NULL) {echo "NO";} else {echo "SI";} ?>">
					</td>
					<td class="texto_detalles" align="left">
						Pagado:
					</td>
					<td align="left">
						<input type="text" name="importepagado" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['Ingreso']." €"; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Exento:
					</td>
					<td align="left">
<?PHP
		$sql = "SELECT SUM(Num_Exentos) AS Exentos,SUM(Num_Facturados) AS Facturados FROM genera_gr WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$exentos = MYSQL_QUERY($sql);		
		$exento = MYSQL_FETCH_ARRAY($exentos);
		if ($exento['Facturados'] == NULL) {
		  	$num_exentos = "NS";
		}
		else {
			if ($exento['Exentos'] == NULL) {
			  	$num_exentos = 0;
			}
			else {
			  	$num_exentos = $exento['Exentos'];		  	
			}
		}
?>
						<input type="text" name="exento" size=3 contenteditable=false class="input_text" value="<?PHP echo $num_exentos; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Taquillas:
					</td>
					<td align="left">
<?PHP
	  	$sql = "SELECT COUNT(Id_Taquilla) AS Taquillas FROM taquilla WHERE Nombre_Gr LIKE '".$fila['Nombre_Gr']."';";
		$result = MYSQL_QUERY($sql);
		$taquillas = MYSQL_FETCH_ARRAY($result);
?>
						<input type="text" name="taquillas" size=3 contenteditable=false class="input_text" value="<?PHP echo $taquillas['Taquillas']; ?>">
					</td>
				</tr>
<?PHP
		if (isset($fila['Id_Servicios'])) {
?>
				<tr>
					<td class="texto_detalles" align="left">
						Servicio:
					</td>
					<td align="left">
						<input type="text" name="servicio" size=3 contenteditable=false class="input_text" value="<?PHP echo STRTOUPPER($fila['Id_Servicios']); ?>">
					</td>
				</tr>
<?PHP
		}
?>
				<tr>
					<td colspan="5" height="51px" valign="bottom">
						<table width="100%" border="0">
							<tr>
<?PHP
		if ($factura != NULL) {		  	
?>								
								<td align="center">
									<img src="./../imagenes/botones/edadeshabitaciones.gif" class="imagen-boton" title="Edades, Tarifas, Habitaciones y Componentes" onclick="detalles('GE',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
								</td>
<?PHP
			if ($facturas == true) {
?>
								<td align="center">
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Ver Facturas" onclick="ver_factura_desglosada();">
								</td>
<?PHP
			}
			else {
?>
								<td align="center">
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Ver Factura <?PHP echo $fila_factura['Num_Factura']; ?>" onclick="ver_factura('<?PHP echo $fila_factura['Num_Factura']; ?>');">
								</td>
<?PHP
			}
?>
								<!--<td align="center">
									<img src="./../imagenes/botones/modificarestancia.gif" class="imagen-boton" title="Modificar Estancia" onclick="window.location.href('?pag=grupos_modificar.php&nombre=<?PHP //echo $_SESSION['gdh']['detalles']['de_clave']?>&fecha=<?PHP //echo $_SESSION['gdh']['detalles']['de_fecha_llegada']?>')">
								</td>-->
<?PHP
			if (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias'] == true) {
?>
								<td align="center">
									<img src="./../imagenes/botones/incidencias.gif" class="imagen-boton" title="Incidencia" onclick="window.location.href('?pag=incidencias.php&gdh=si&nombre_gr=<?PHP echo $_SESSION['gdh']['detalles']['de_clave']; ?>&fecha_llegada=<?PHP echo $_SESSION['gdh']['detalles']['de_fecha_llegada']; ?>')">
								</td>
<?PHP
			}
		}
		else {
			if (($_SESSION['gdh']['gdh_dis']['tipo'] == 'FG')||($_SESSION['gdh']['gdh_dis']['tipo'] == 'FD')||($_SESSION['gdh']['gdh_dis']['tipo'] == 'FDR')||($_SESSION['gdh']['gdh_dis']['tipo'] == 'FDRM')) {
?>
								<td align="center">
									<img src="./../imagenes/botones/volver.gif" class="imagen-boton" title="Volver a Detalles Estancia" onclick="recargar_gdh_dis('H');">
								</td>
<?PHP
			}
			else {
?>
								<td align="center">
									<img src="./../imagenes/botones/edadeshabitaciones.gif" class="imagen-boton" title="Edades, Tarifas, Habitaciones y Componentes" onclick="detalles('GE',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
								</td>
<?PHP
			  	if (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion'] == true) {
?>
								<td align="center">
									<img src="./../imagenes/botones/facturar.gif" class="imagen-boton" title="Facturar" onclick="facturar('G','<?PHP echo $fila['Nombre_Gr']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
								</td>
<?PHP
				}
?>
								<td align="center">
									<img src="./../imagenes/botones/modificarestancia.gif" class="imagen-boton" title="Modificar Estancia" onclick="window.location.href('?pag=grupos_modificar.php&nombre=<?PHP echo $_SESSION['gdh']['detalles']['de_clave']?>&fecha=<?PHP echo $_SESSION['gdh']['detalles']['de_fecha_llegada']?>')">
								</td>
								<td align="center">
									<img src="./../imagenes/botones/pagarnoches.gif" class="imagen-boton" title="Pagar Noches" onclick="detalles('GP',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
								</td>
<?PHP
				if (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias'] == true) {
?>
								<td align="center">
									<img src="./../imagenes/botones/incidencias.gif" class="imagen-boton" title="Incidencia" onclick="window.location.href('?pag=incidencias.php&gdh=si&nombre_gr=<?PHP echo $_SESSION['gdh']['detalles']['de_clave']; ?>&fecha_llegada=<?PHP echo $_SESSION['gdh']['detalles']['de_fecha_llegada']; ?>')">
								</td>
<?
				}
			}
		}
?>
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