<?PHP

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	
	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else { 
		MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$sql = "SELECT * FROM detalles WHERE DNI_PRA LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
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
				Modificar Ingreso
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
					<td rowspan="9" width="5px">
					</td>
				</tr>
<?PHP
		$sql = "SELECT *,CONCAT(Nombre_PRA,' ',Apellido1_PRA,' ',Apellido2_PRA) AS Name FROM pra WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."';";
		$result = MYSQL_QUERY($sql);
		$fila_pra = MYSQL_FETCH_ARRAY($result);	  	
?>
				<tr>
					<td class="texto_detalles" align="left">
						D.N.I.:
					</td>
					<td align="left">
						<input type="text" name="dni" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila['DNI_PRA']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Teléfono:
					</td>
					<td align="left">
						<input type="text" name="telefono" size=9 contenteditable=false class="input_text" value="<?PHP echo $fila_pra['Tfno_PRA']; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre:
					</td>
					<td colspan="3" align="left">
						<input type="text" name="nombre" size=40 contenteditable=false class="input_text" value="<?PHP echo $fila_pra['Name']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Email:
					</td>
					<td colspan="3" align="left">
						<input type="text" name="email"  size=40 contenteditable=false class="input_text" value="<?PHP echo $fila_pra['Email_PRA']; ?>">
					</td>				
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Llegada:
					</td>
					<td align="left">
						<input type="text" name="llegada" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Llegada']); ?>">
					</td>		
					<td class="texto_detalles" align="left">
						Salida:
					</td>
					<td align="left">
						<input type="text" name="llegada" size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Salida']); ?>">
					</td>			
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Hab.:
					</td>
					<td align="left">
<?PHP
		$habitaciones = "";
		$camas = 0;
		$sql = "SELECT DISTINCT(Id_Hab) AS Id_Hab,Num_Camas FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
  		$result2 = MYSQL_QUERY($sql);
  		for ($j = 0; $j < MYSQL_NUM_ROWS($result2); $j++) {
  			$fila2 = MYSQL_FETCH_ARRAY($result2);
  			$habitaciones = $habitaciones.$fila2['Id_Hab'].", ";
  			$camas = $camas + $fila2['Num_Camas'];
  		}
  		$habitaciones = SUBSTR($habitaciones,0,-2);
?>
						<input type="text" name="habitaciones" size=9 contenteditable=false class="input_text" value="<?PHP echo $habitaciones; ?>">
					</td>			
					<td class="texto_detalles" align="left">
						Camas:
					</td>
					<td align="left">
						<input type="text" name="camas" size=9 contenteditable=false class="input_text" value="<?PHP echo $camas; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Pernocta:
					</td>
					<td align="left">
						<input type="text" name="pernocta" size=3 contenteditable=false class="input_text" value="<?PHP echo $fila['PerNocta']; ?>">
					</td>
					<td class="texto_detalles" align="left">
						Ingreso:
					</td>
					<td align="left">
						<input type="text" name="ingreso" size=9 contenteditable=false class="input_text" value="<?PHP echo ($fila['Ingreso'] * 1)." €"; ?>">
					</td>
				</tr>
				<tr height="10px">
					<td>
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Ingreso:
					</td>
					<td align="left">
						<input type="text" name="nuevo_ingreso" size=6 class="input_text_vacio_numerico" value="" maxlength="7">
					</td>
					<td colspan="2" align="center" valign="center">
						<img src="./../imagenes/botones-texto/aceptar.jpg" title="Actualizar el Ingreso de la Reserva" class="imagen-boton" onclick="detalles_pagar('ZR',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>',formulario.nuevo_ingreso.value,'');">
					</td>
				</tr>
				<tr>
					<td colspan="5" height="64px" valign="bottom">
						<table width="100%" border="0">
							<tr>
								<td align="center">
									<img src="./../imagenes/botones/volver.gif" class="imagen-boton" title="Volver a Detalles Estancia" onclick="detalles('RD',<?PHP echo '\''.$_SESSION['gdh']['detalles']['de_clave'].'\',\''.$_SESSION['gdh']['detalles']['de_fecha_llegada'];?>');">
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