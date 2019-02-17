		<?	
			$sql_detall = "select * from cliente where DNI_Cl like '".$_GET['dni']."' and Tipo_documentacion like '".$_GET['tipo_doc']."' and DNI_Cl in (select DNI_Cl from pernocta_p)";
			if($result_detall = mysql_query($sql_detall)){
				if(mysql_num_rows($result_detall) > 0){
					$fila_detall = mysql_fetch_array($result_detall);
					$fecha_nacimiento = split("-",$fila_detall['Fecha_Nacimiento_Cl']);
					
		// Variables para los textArea			
		$cols_nom = 30;
	    $div_nom = 22;
	    $cols_ape = 30;
	    $div_ape = 22;	
		?>


		<!--- DETALLES peregrino -->
		<!--- DETALLES peregrino -->
		<!--- DETALLES peregrino -->
		<div id="detalles_peregrino" style="display:block;">
			<table border='0'  cellspacing="0" cellpadding="0">
			<form name="formu_det_peregrino">
				<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
						<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
						<div style="width:290px;height:25px;text-align:center;float:left;" class="champi_centro">
						<div class="titulo" style="width:250px;text-align:center;">Detalles Peregrino</div>
						</div>
						<div style="height:25px;width:30px;float:left;" class="champi_derecha">&nbsp;</div>
					</td>
				</thead>
				<tr>
				<td colspan="9" style="border-left:solid 1px #3F7BCC;border-bottom:solid 1px #3F7BCC;border-right:solid 1px #3F7BCC;background-color:#F4FCFF;" class='tabla_detalles'>
				<table class='tabla_detalles'>
				<tr height='25'>
					<td>&nbsp;</td>
					<td align="left" colspan="4">
					<table><tr>
					<td align='left' class='label_formulario'>Tipo :</td>
					<td align='left'><input type='text' size='1' id="texto_detalles" maxlength='1' class='input_formulario' name='tipo_documentacion' style="border:none;" readonly value='<?echo $fila_detall['Tipo_documentacion'];?>'></td><td class="label_formulario" style="padding-left:20px;">D.N.I. :</td><td><input type='text' size='11' id="texto_detalles" maxlength='30' class='input_formulario' name='dni_peregrino' style="border:none;" readonly value='<?echo $fila_detall['DNI_Cl'];?>'></td>
					</tr></table>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre:</td>
					<td align='left'>
					<?php
						$num_nom = intval(strlen($fila_listado['Nombre_Cl'])/$div_nom) + 1;									
						echo "<TEXTAREA class='areatexto2' Name=\"Nombre_C1".$i."\" readonly
						rows=\"".$num_nom."\" cols=\"".$cols_nom."\"  >".$fila_detall['Nombre_Cl']."</TEXTAREA>";
						
					?>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Primer Apellido:</td>
					<td align='left'>
					<?php
						$num_ape1 = intval(strlen($fila_detall['Apellido1_Cl'])/$div_ape) + 1;						
						echo "<TEXTAREA class='areatexto2' Name=\"Apellido1_C1".$i."\" readonly
						rows=\"".$num_ape1."\" cols=\"".$cols_ape."'\" >".$fila_detall['Apellido1_Cl']."</TEXTAREA>";
					?>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Segundo Apellido:</td>
					<td align='left'>
					<?php
					    $num_ape2 = intval(strlen($fila_detall['Apellido2_Cl'])/$div_ape) + 1;					
						echo "<TEXTAREA class='areatexto2' Name=\"Apellido2_C1".$i."\" readonly
						rows=\"".$num_ape2."\" cols=\"".$cols_ape."'\" >".$fila_detall['Apellido2_Cl']."</TEXTAREA>";
					?>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>País:</td>
						<td align='left'><input type='text' id="texto_detalles" name='pais_peregrino' class='input_formulario' value='<?echo $paises[$fila_detall['Id_Pais']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Provincia:</td>
						<td align='left'><input type='text' id="texto_detalles" name='provincia_peregrino' class='input_formulario'  value='<?echo $provincias[$fila_detall['Id_Pro']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Localidad:</td>
					<td align='left'><input type='text' id="texto_detalles" name='localidad_peregrino' class='input_formulario' value='<?echo $fila_detall['Localidad_Cl'];?>'  style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align="left" class='label_formulario'>Dirección:</td>
					<td align='left'><input type='text' id="texto_detalles" name='direccion_peregrino' class='input_formulario' value='<?echo $fila_detall['Direccion_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Código Postal:</td>
					<td align='left'><input type='text' id="texto_detalles" name='cp_peregrino' class='input_formulario' value='<?echo $fila_detall['CP_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Nacimiento:</td>
						<td align='left'><input type='text' id="texto_detalles" name='fecha_nac_peregrino' class='input_formulario' value='<?echo $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];?>'  style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Sexo:						
						<td align='left'><input type='text' id="texto_detalles" name='sexo_peregrino' class='input_formulario' value='<?
						if(trim(strtoupper($fila_detall['Sexo_Cl'])) == "M"){
							echo "Hombre";
						}else if(strtoupper($fila_detall['Sexo_Cl']) == "F"){
							echo "Mujer";
						}
						
						?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:</td>
						<td align='left'><input type='text' size='21' id="texto_detalles" name='telefono_alberguista' class='input_formulario' style="border:none;" readonly value="<?echo $fila_detall['Tfno_Cl'];?>"></td>											
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Email:</td>
					<td align='left' class='label_formulario'><input type='text' id="texto_detalles" size='20' name='email_peregrino' class='input_formulario' style="border:none;" value="<?echo $fila_detall['Email_Cl'];?>" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Observaciones:</td>
					<td align='left'><textarea name="observaciones_peregrino" class='text_area_formulario' maxlength="256" style="height:80px;width:160px;" readonly><?echo $fila_detall['Observaciones_Cl'];?></textarea></td>
				</tr>	
			</table>	
			<div style="margin-top:5px;">
				<? if(isset($_GET['busqueda']) && $_GET['busqueda'] == "si"){
				?>
				<a href="?pag=listado_criterio_peregrinos.php"><img src="../imagenes/botones-texto/aceptar.jpg" alt="Volver a la página de Búsquedas" style="border:none;" /></a>
				<?
				}else{
				?>
				<a href="?pag=peregrinos.php"><img src="../imagenes/botones-texto/aceptar.jpg" alt="Volver a la Página Principal de Peregrinos" style="border:none;" /></a>
				<?
				}
				?>				
			</div>
			</td>
			</tr>
			</table>

		</div>
<?
	}else{ //En caso de que la consulta a la tabla pernocta ($result_baja)  no devuelva resultados
		echo "<script>alert('El peregrino solicitado no existe en el sistema.');";
		if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
			echo "location.href='index.php?pag=listado_criterio_peregrinos.php'";
		}else{
			echo "location.href='?pag=peregrinos.php'";
		}
		echo "</script>";
		}
}else{ //En caso de que la consulta a la tabla pernocta ($result_baja) devuelva false
	echo "<script>alert('El peregrino solicitado no existe en el sistema.');";
	if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
		echo "location.href='index.php?pag=listado_criterio_peregrinos.php'";
	}else{
		echo "location.href='?pag=peregrinos.php'";
	}
	echo "</script>";
}
?>