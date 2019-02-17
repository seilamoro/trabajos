<script language="JavaScript">

	// Cuando seleccionamos el número de exentos en una factura, nos cambia el campo de facturados
	function cambiar_factura(clave,fecha) {
	  	formulario.gdh_dis.value = 'FD';
	  	formulario.gdh_fac.value = 'G' + '*' + clave + '*' + fecha;
	  	formulario.submit();
	}

</script>

<?PHP

	$tipo_persona = 'grupo';
	
	include('paginas/factura_crear_desglosada.php');

	@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

	if (!$db) {	  
?>
	<table><tr><td class="nom_hab">ERROR DE CONEXION A MYSQL</td></tr></table>
<?PHP
	}
	else {	  
	  	MYSQL_SELECT_DB($_SESSION['conexion']['db']);
	  	$sql = "SELECT * FROM grupo LEFT JOIN estancia_gr ON grupo.Nombre_Gr=estancia_gr.Nombre_Gr WHERE estancia_gr.Nombre_Gr LIKE '".$_SESSION['gdh']['detalles']['de_clave']."' AND estancia_gr.Fecha_Llegada LIKE '".$_SESSION['gdh']['detalles']['de_fecha_llegada']."';";
	  	//echo $sql;
		$result = MYSQL_QUERY($sql);
		$fila = MYSQL_FETCH_ARRAY($result);
?>

<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
	<tr id="titulo_tablas">
		<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
			<div class="champi_izquierda">
				&nbsp;
			</div>
			<div class="champi_centro" style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_ancho']; ?>;">
					Factura de Grupo Desglosada.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Facturas Individuales:			
					<select name="desglosada_factura" class="detalles_select" onchange="cambiar_factura('<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>');" style="position:relative;margin-left:40px;">
<?PHP

	for ($i = 0; $i < $_SESSION['factura_grupo_desglosada']['Num_Facturas']; $i++) {
	  	if ($i == $_SESSION['factura_grupo_desglosada']['factura_actual']) {
	  		echo '<option value="'.$i.'" selected>Factura Nº '.($i + 1);
	  	}
	  	else {
	  		echo '<option value="'.$i.'">Factura Nº '.($i + 1);
		}
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
			<table border="0" height="<?PHP echo $_SESSION['gdh']['tamanios']['gdh_distribucion_alto_facturas']; ?>" width="100%" style="border: 1px solid #3F7BCC;">
				<tr>
					<td rowspan="11" width="5px">
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						NºFactura:
					</td>
					<td align="left">
						<table border="0">
							<tr>
								<td class="texto_detalles">
									<input type="text" name="numero_factura" size=4 maxlength=5 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['num_factura']; ?>">
								</td>
								<td class="texto_detalles"><div class="input_text">-</div>
								</td>
								<td class="texto_detalles">
									<input type="text" name="anio_factura" size=2 maxlength=4 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['anio_factura']; ?>">
								</td>
							</tr>
						</table>
					</td>
					<td rowspan="9" valign="top" align="left">
						<table border="0">
							<tr>
								<td class="texto_detalles" align="left" colspan="3">
									Pernocta para la factura <?PHP echo $_SESSION['factura_grupo_desglosada']['factura_actual'] + 1; ?>:
								</td>
							</tr>
							<tr>
								<td>	
									<div class="tableContainer" style="height:<?PHP echo $_SESSION['gdh']['tamanios']['factura_agrupada']; ?>;">
										<table border="0" cellpadding="0" cellspacing="0" class="scrollTable">
											<thead class="fixedHeader">
												<tr>
													<th align="left">
														Habitación
													</th>
													<th align="left">
														Edad
													</th>
													<th align="left">
														Nº Personas
													</th>
													<th align="left">
														Facturados
													</th>
													<th align="left">
														Facturar
													</th>
													<th align="left">
														Exento
													</th>
												</tr>
											</thead>
											<tbody class="scrollContent">
<?PHP
		  	for ($i = 0; $i < COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']); $i++) {	  	  	
?>
		    									<tr class="texto_listados" height="<?PHP echo $_SESSION['gdh']['tamanios']['td_factura_agrupada']; ?>">
		  											<td align="right">
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Id_Hab'];
?>
											  		</td>
											  		<td align="left">	  		
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Nombre_Edad'];
?>
											  		</td>
											  		<td align="right">	  		
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Num_Personas'];
?>
											  		</td>
											  		<td align="right">
<?PHP
				echo $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_facturados'] + $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_exentos'];
				   //+ $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_facturados'] + $_SESSION['factura_grupo_desglosada']['Factura_Resto']['Generas']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['num_exentos'];
?>
											  		</td>
											  		<td align="center">
		  												<input type=radio name="facturado_exento" onclick="cambiar_factura('<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>');" value="f-<?PHP echo $_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab']; ?>-<?PHP echo $_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']; ?>" 
<?PHP
				$pulsado = false;
				
				if (($_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'] == $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_id_hab']) 
				  &&($_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad'] == $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_id_edad'])
				  &&(($_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_num_facturados'] == '1')
				   ||($_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_num_exentos'] == '1'))) {
				     $pulsado = true;
				}
				
				if (($_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'] == $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_id_hab']) 
				  &&($_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad'] == $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_id_edad'])) {
	  				if ($_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_num_facturados'] == '1') {
					    echo ' checked>';
					}
					else {
					  	if (($_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Num_Personas'] <= $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_facturados'] + $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_exentos'])&&($pulsado == false)) {
						    echo ' disabled>';
						}
						else {
						  	echo ' >';
						}
					}
				}
				else {
				  	if (($_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Num_Personas'] <= $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_facturados'] + $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_exentos'])&&($pulsado == false)) {
						echo ' disabled>';
					}
					else {
					  	echo ' >';
					}
				}
?>
											  		</td>
											  		<td align="center">
		  												<input type="radio" name="facturado_exento" onclick="cambiar_factura('<?PHP echo $_SESSION['factura_grupo_desglosada']['Nombre_Gr']; ?>','<?PHP echo $_SESSION['factura_grupo_desglosada']['Fecha_Llegada']; ?>');" value="e-<?PHP echo $_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab']; ?>-<?PHP echo $_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']; ?>"
<?PHP 		
				if (($_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'] == $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_id_hab']) 
				  &&($_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad'] == $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_id_edad'])) {
	  				if ($_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['genera_num_exentos'] == '1') {
					    echo ' checked>';
					}
					else {
					  	if (($_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Num_Personas'] <= $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_facturados'] + $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_exentos'])&&($pulsado == false)) {
						    echo ' disabled>';
						}
						else {
						  	echo ' >';
						}
					}
				}
				else {
				  	if (($_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['Num_Personas'] <= $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_facturados'] + $_SESSION['factura_grupo_desglosada']['Estancias']['Datos'][$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Hab'].'-'.$_SESSION['factura_grupo_desglosada']['Estancias']['Lista'][$i]['Id_Edad']]['numero_exentos'])&&($pulsado == false)) {
						echo ' disabled>';
					}
					else {
					  	echo ' >';
					}
				}
?>
											  		</td>
											    </tr>
<?PHP
			}		
			if (COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']) < $_SESSION['gdh']['factura']['configuracion_numero_filas']) {
		  		for ($i = COUNT($_SESSION['factura_grupo_desglosada']['Estancias']['Lista']); $i < $_SESSION['gdh']['factura']['configuracion_numero_filas']; $i++) {
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
						</table>
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Fecha Factura:
					</td>
					<td align="left">
						<table border="0" width="90%">
							<tr>
								<td align="left">
									<input type="text" name="fecha_factura" size=9 maxlength=10 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['fecha_factura']; ?>">
								</td>
								<td align="right">
									<IMG src='../imagenes/botones/lupa.png' border=0 width='20px' style='position:relative;top:3px;cursor:pointer;' onclick='window.open("paginas/gdh_busq_componentes_grupo.php?Nombre_Gr=<?PHP echo $_SESSION['gdh']['detalles']['de_clave']; ?>&Fecha_Llegada=<?PHP echo $_SESSION['gdh']['detalles']['de_fecha_llegada']; ?>","_blank", "width=650px,height=650px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");'>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						D.N.I.:
					</td>
					<td align="left">
						<input type="text" name="dni_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['dni_factura']; ?>">						
					</td>
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						Nombre:
					</td>
					<td align="left">
						<input type="text" name="nombre_factura" size=20 maxlength=20 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['nombre_factura']; ?>">
					</td>						
				</tr>
				<tr>
					<td class="texto_detalles" align="left">
						1<sup>er</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido1_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['apellido1_factura']; ?>">
					</td>
				</tr>
				<tr>					
					<td class="texto_detalles" align="left">
						2<sup>o</sup> Apellido:
					</td>
					<td align="left">
						<input type="text" name="apellido2_factura" size=20 maxlength=30 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['apellido2_factura']; ?>">
					</td>						
				</tr> 
				<tr>
					<td class="texto_detalles" align="left">
						Desperfecto:
					</td>
					<td align="left">
						<input type="text" name="desperfecto_factura" size=20 maxlength=255 contenteditable=true class="input_text_vacio" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['desperfecto_factura']; ?>">
					</td>	
				</tr>
				<tr>												
					<td class="texto_detalles" align="left">
						Cuantía:
					</td>
					<td align="left">
						<input type="text" name="cuantia_desperfecto_factura" size=6 maxlength=7 contenteditable=true class="input_text_vacio_numerico" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['cuantia_factura']; ?>">
					</td>
				</tr>
				<tr>											
					<td class="texto_detalles" align="center" colspan="4">
						<img src="./../imagenes/botones-texto/continuar.jpg" class="imagen-boton" title="Continuar a la factura del resto de la estancia." name="facturar" value="Facturar" class="boton" onclick="tipo_factura('FDR');">
						<input type="hidden" name="resto_factura" class="input_text" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['Factura'][$_SESSION['factura_grupo_desglosada']['factura_actual']]['ingreso_factura']; ?>">
						<input type="hidden" name="modificar_factura_actual" value="<?PHP echo $_SESSION['factura_grupo_desglosada']['factura_actual']; ?>">
<?PHP
	if ((isset($_POST['volver_factura_resto']))||(isset($_POST['modificar_factura_resto']))) {
?>
						<input type="hidden" name="volver_factura_resto" value="true"/>
<?PHP
	}
?>
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