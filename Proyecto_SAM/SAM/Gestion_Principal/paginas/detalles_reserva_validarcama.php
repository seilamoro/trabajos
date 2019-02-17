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
					Validar Cama Reservada
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
					<td rowspan="11" width="5px">
					<td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Llegada:
					</td>
					<td align="left">
						<input type="text" name="llegada"  size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Llegada']); ?>">
					</td>		
					<td class="texto_detalles" align="left">
						Salida:
					</td>
					<td align="left">
						<input type="text" name="llegada"  size=9 contenteditable=false class="input_text" value="<?PHP echo fecha_ordenada($fila['Fecha_Salida']); ?>">
					</td>			
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Hab.:
					</td>
					<td align="left">
						<select name="habitacion" class="detalles_select">
<?PHP
		$camas = 0;
		$sql = "SELECT DISTINCT(Id_Hab) AS Id_Hab,Num_Camas FROM reserva WHERE DNI_PRA LIKE '".$fila['DNI_PRA']."' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
  		$result2 = MYSQL_QUERY($sql);
  		for ($j = 0; $j < MYSQL_NUM_ROWS($result2); $j++) {
  			$fila2 = MYSQL_FETCH_ARRAY($result2);
  			$camas = $camas + $fila2['Num_Camas'];
  			echo '<option value="'.$fila2['Id_Hab'].'">'.$fila2['Id_Hab'];
  		}
?>
						</select>
					</td>			
					<td class="texto_detalles" align="left">
						Pernocta:
					</td>
					<td align="left">
						<input type="text" name="pernocta"  size=3 contenteditable=false class="input_text" value="<?PHP echo $fila['PerNocta']; ?>">
					</td>						
				</tr>
				<tr height="20px">
					<td>
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left" colspan="4">
						Ingreso total de la reserva:
						<input type="text" name="ingreso_reserva"  size=9 contenteditable=false class="input_text" value="<?PHP echo ($fila['Ingreso'] * 1)." €"; ?>">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left" colspan="4">
						Ingreso individual sobre el total:
						<input type="text" name="ingreso" size=3 <?PHP if ($camas == 1) echo ' contenteditable="false" '; ?> class="input_text_vacio_numerico" value="<?PHP echo ROUND((($fila['Ingreso'] * 1) / $camas),2); ?>" maxlength="5" onkeyup="comprobar_ingreso(formulario.ingreso,<?PHP echo ($fila['Ingreso'] * 1); ?>);">
						<input type="text" name="rango_ingreso" size=7 contenteditable=false class="input_text" value=" de <?PHP echo ($fila['Ingreso'] * 1); ?>€">
					</td>
				</tr>
				<tr height="20px"></tr>
				<tr>
<?PHP
		$sql_res = "SELECT * FROM pernocta WHERE DNI_Cl LIKE '".$fila['DNI_PRA']."%' AND Fecha_Llegada LIKE '".$fila['Fecha_Llegada']."';";
		$result_res = MYSQL_QUERY($sql_res);
		if (MYSQL_NUM_ROWS($result_res) > 0) {
?>
					<td class="texto_detalles" align="right">
						<input type="checkbox" name="datos_reserva" disabled>
					</td>
					<td class="texto_detalles" align="left" colspan="3">
						Usar datos de la reserva.
					</td>					
<?PHP
		}
		else {
?>
					<td class="texto_detalles" align="right">
						<input type="checkbox" name="datos_reserva">
					</td>
					<td class="texto_detalles" align="left" colspan="3">
						Usar datos de la reserva.
					</td>
<?PHP
		}
?>
				</tr>
				<tr height="20px">
					
				</tr>
				<tr>
					<td colspan="4" align=center>
						<img src="./../imagenes/botones-texto/continuar.jpg" value="Aceptar Alberguista" name="aceptar" class="imagen-boton" onclick="window.location.href='?pag=alberguistas.php&accion=reserva&DNI_PRA=<?PHP echo $fila['DNI_PRA']; ?>&Fecha_Llegada=<?PHP echo $fila['Fecha_Llegada']; ?>&Id_Hab=' + formulario.habitacion.value + '&Ingreso=' + formulario.ingreso.value + '&Datos_Reserva=' + formulario.datos_reserva.checked">
					</td>
				</tr>
				<tr>
					<td colspan="5" height="73px" valign="bottom">
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