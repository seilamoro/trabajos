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
					Tipo de Factura de Grupo
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
						Escoger tipo de factura de grupo:
					</td>					
				</tr>
				<tr>
					<td colspan="5" align="left">
						<table border="0">
							<tr>
								<td class="texto_detalles">
									<input type=radio name="tipo_factura" value="FG" checked onclick="formulario.desglosada_n_facturas.disabled=true;">
								</td>
								<td class="texto_detalles" colspan="2">
									Factura
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr height="20px">
					<td>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="left">
						<table border="0">
							<tr>
								<td class="texto_detalles">
									<input type=radio name="tipo_factura" value="FD" onclick="formulario.desglosada_n_facturas.disabled=false;">
								</td>
								<td class="texto_detalles" colspan="2">
									Factura Desglosada
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="left">
						<table border="0">
							<tr>
								<td>
								</td>
								<td class="texto_detalles">
									Número de facturas INDIVIDUALES:
								</td>
								<td class="texto_detalles">
									<select class="detalles_select" name="desglosada_n_facturas" disabled>
<?PHP
		for ($i = 1; $i <= $fila['Num_Personas']; $i++) {
?>
										<option value="<?PHP echo $i;?>"><?PHP echo $i;?>
<?PHP
		}
?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="5" height="47px" valign="bottom">
						<table width="100%" border="0">
							<tr>
								<td align="center">
									<img src="./../imagenes/botones-texto/continuar.jpg" class="imagen-boton" title="Facturar" onclick="facturar('F','<?PHP echo $fila['Nombre_Gr']; ?>','<?PHP echo $fila['Fecha_Llegada']; ?>');">
								</td>
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