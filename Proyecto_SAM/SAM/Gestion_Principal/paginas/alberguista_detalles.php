		<?
			//Detalles de Alberguista
			//Detalles de Alberguista
			//Detalles de Alberguista


			//Consulta a la base de datos para recoger los datos del albeguista
			$sql_detall = "select * from cliente where DNI_Cl like '".$_GET['dni']."' and Tipo_documentacion like '".$_GET['tipo_doc']."'";
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
		<div id="detalles_alberguista" >	
		
			<table border='0'  cellspacing="0" cellpadding="0" style="width:380px;height:550px;padding:0px 0px 0px 0px;">
			<form name="formu_det_alberguista">
				<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
						<div style="height:27px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda"></div>
						<div style="width:320px;height:24px;text-align:center;float:left;" class="champi_centro">
						<div class="titulo" style="width:250px;text-align:center;">Detalles Alberguista</div>
						</div>
						<div style="height:27px;width:30px;float:left;" class="champi_derecha"></div>
					</td>
				</thead>
				<tr>
				<td colspan="9" style="padding:0px 0px 0px 0px;border-left:solid 1px #3F7BCC;border-bottom:solid 1px #3F7BCC;border-right:solid 1px #3F7BCC;" class='tabla_detalles'>
								
				<table border="0" cellspacing="0" style="margin-top:0px;" class='tabla_detalles'>
				<tr height='25'>
				<td>&nbsp;</td>
				<td colspan="4" align="left">
				<table><tr>
					<td align='left' class='label_formulario'>Tipo :</td>
					<td align='left'><input type='text' id="texto_detalles" size='1' maxlength='1' class='input_formulario' name='tipo_documentacion' style="border:none;" readonly value='<?echo $fila_detall['Tipo_documentacion'];?>'></td>
					<td class="label_formulario" style="padding-left:20px;">D.N.I. : </td>
					<td><input type='text' size='11' id="texto_detalles" maxlength='30' class='input_formulario' name='dni_peregrino' style="border:none;" readonly value='<?echo $fila_detall['DNI_Cl'];?>'></td>
					</tr></table></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario' >Nombre:</td>
					<td align='left'>
					<!--<input type='text' id="texto_detalles" name='nombre_alberguista' class='input_formulario' value='<?echo $fila_detall['Nombre_Cl'];?>' style="border:none;" readonly>-->
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
					<!--<input type='text' id="texto_detalles" name='apellido1_alberguista' class='input_formulario' value='<?echo $fila_detall['Apellido1_Cl'];?>' id="texto_detalles" style="border:none;" readonly>-->
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
					<!--<input type='text' id="texto_detalles" name='apellido2_alberguista' class='input_formulario' value='<?echo $fila_detall['Apellido2_Cl'];?>' style="border:none;" readonly>-->
					</td>
				</tr>
				

				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>País:</td>
						<td align='left'><input type='text' id="texto_detalles" name='pais_alberguista' class='input_formulario' value='<?echo $paises[$fila_detall['Id_Pais']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Provincia:</td>
						<td align='left'><input type='text' id="texto_detalles" name='provincia_alberguista' class='input_formulario' value='<?echo $provincias[$fila_detall['Id_Pro']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Localidad:</td>
					<td align='left'><input type='text' id="texto_detalles" name='localidad_alberguista' class='input_formulario' value='<?echo $fila_detall['Localidad_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align="left" class='label_formulario'>Dirección:</td>
					<td align='left'><input type='text' id="texto_detalles" name='direccion_alberguista' class='input_formulario' value='<?echo $fila_detall['Direccion_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Código Postal:</td>
					<td align='left'><input type='text' id="texto_detalles" name='cp_alberguista' class='input_formulario' value='<?echo $fila_detall['CP_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Nacimiento:</td>
						<td align='left'><input type='text' id="texto_detalles" name='fecha_nac_alberguista' class='input_formulario' value='<?echo $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Sexo:						
						<td align='left'><input type='text' id="texto_detalles" name='sexo_alberguista' class='input_formulario' value='<?
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
					<td align='left' class='label_formulario'><input type='text' id="texto_detalles" size='20' name='email_alberguista' class='input_formulario' style="border:none;" readonly value="<?echo $fila_detall['Email_Cl'];?>"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Observaciones:</td>
					<td align='left'><textarea name="observaciones_alberguista" id="texto_detalles" class='text_area_formulario' style="height:80px;width:180px;" readonly><?echo $fila_detall['Observaciones_Cl'];?></textarea></td>
				</tr>	
			
				<tr>
					<td colspan="3" align="center" style="padding-top:10px;">
					<input type="hidden" name="busqueda" value="<?
							if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
								echo $_GET['busqueda'];
							}
						?>" >
						<?
							if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
							?>
							<a href="?pag=listado_criterio_alberguistas.php"><img src="../imagenes/botones-texto/aceptar.jpg" alt="Volver a la Página de Búsquedas" border="0"/></a>	
							<?
							}else{
							?>
							<a href="?pag=alberguistas.php"><img src="../imagenes/botones-texto/aceptar.jpg" border="0" alt="Volver a la Página Principal de Alberguistas"/></a>	
							<?
							}	
							?>
					</td>
				</tr>			
			</table>	
			</td>
			</tr>
			</table>
		</div>
<?
	}else{
		echo "<script>alert('El alberguista solicitado no existe en el sistema.');";
		if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
			echo "location.href='index.php?pag=listado_criterio_alberguistas.php'";
		}else{
			echo "location.href='?pag=alberguistas.php'";
		}
		echo "</script>";

	}
}else{
	echo "<script>alert('El alberguista solicitado no existe en el sistema.');";
	if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
		echo "location.href='index.php?pag=listado_criterio_alberguistas.php'";
	}else{
		echo "location.href='?pag=alberguistas.php'";
	}
	echo "</script>";

}

//FIN DETALLES ALBERGUISTA
//FIN DETALLES ALBERGUISTA
//FIN DETALLES ALBERGUISTA
?>