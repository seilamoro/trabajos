<?
			$sql_baja = "select * from cliente where DNI_Cl like '".$_GET['dni']."' and Tipo_documentacion like '".trim($_GET['tipo_doc'])."' and DNI_Cl in (select DNI_Cl from pernocta_p)";
			if($result_baja = mysql_query($sql_baja)){
				if(mysql_num_rows($result_baja) > 0){
					$fila_baja = mysql_fetch_array($result_baja);
					$sql_pernocta_p = "select * from pernocta_p where DNI_Cl like  '".trim($_GET['dni'])."'"; 
					$result_pernocta_p = mysql_query($sql_pernocta_p);
					$fila_pernocta_p = mysql_fetch_array($result_pernocta_p);
					if(mysql_num_rows($result_pernocta_p) == 1 && trim($fila_pernocta_p['Fecha_Llegada']) == date("Y-m-d")){
						//echo "<script>alert('Se dispone a eliminar un peregrino dado de alta en el día de hoy. ');</script>";
					}else{
						if(mysql_num_rows($result_pernocta_p) > 1 || trim($fila_pernocta_p['Fecha_Llegada']) != date("Y-m-d")){
							echo "<script>alert('El peregrino ha realizado estancias anteriormente, no puede ser eliminado. ');";
							if(isset($_GET['busqueda']) && $_GET['busqueda'] == "si")
							{
								echo "location.href='index.php?pag=listado_criterio_peregrinos.php'";	
							}
							else
							{
								echo "location.href='?pag=peregrinos.php';</script>";
							}
							echo "</script>";
						}
					}
					$fecha_nacimiento = split("-", $fila_baja['Fecha_Nacimiento_Cl']);
		// Variables para los textArea			
		$cols_nom = 30;
	    $div_nom = 22;
	    $cols_ape = 30;
	    $div_ape = 22;	
?>
		<!--- BAJA peregrino -->
		<!--- BAJA peregrino -->
		<div id="baja_peregrino" style="display:block;">
			<table border='0' cellspacing="0" cellpadding="0">
			<form name="formu_baja_peregrino" action="?pag=peregrinos.php"  method="POST">
			<input type="hidden" name="accion" value="baja">
				<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
						<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
						<div style="width:290px;height:25px;text-align:center;float:left;" class="champi_centro">
						<div class="titulo" style="width:250px;text-align:center;">Eliminar Peregrino</div>
						</div>
						<div style="height:25px;width:30px;float:left;" class="champi_derecha">&nbsp;</div>
					</td>
				</thead>
				<tr>
				<td colspan="9" style="border-left:solid 1px #3F7BCC;border-bottom:solid 1px #3F7BCC;border-right:solid 1px #3F7BCC;background-color:#F4FCFF;">
				<table class='tabla_detalles'>
				<tr height='25'>
					<td>&nbsp;</td>
					<td align="left" colspan="3">
					<table><tr>
					<td align='left' class='label_formulario'>Tipo :</td>
					<td align='left'><input type='text' size='1' id="texto_detalles" maxlength='1' class='input_formulario' name='tipo_documentacion' style="border:none;" readonly value='<?echo $fila_baja['Tipo_documentacion'];?>'></td><td class="label_formulario" style="padding-left:35px;">D.N.I. : </td><td><input type='text' id="texto_detalles" size='11' maxlength='30' class='input_formulario' name='dni_peregrino' style="border:none;" value='<?echo $fila_baja['DNI_Cl'];?>' readonly ></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre:</td>
					<td align='left'>					
					<?php		
						$num_nom = intval(strlen($fila_baja['Nombre_Cl'])/$div_nom) + 1;									
						echo "<TEXTAREA class='areatexto2' Name=\"Nombre_C1".$i."\" readonly
						rows=\"".$num_nom."\" cols=\"".$cols_nom."\"  >".$fila_baja['Nombre_Cl']." </TEXTAREA>";
					?>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Primer Apellido:</td>
					<td align='left'>					
					<?php
						$num_ape1 = intval(strlen($fila_baja['Apellido1_Cl'])/$div_ape) + 1;						
						echo "<TEXTAREA class='areatexto2' Name=\"Apellido1_C1".$i."\" readonly
						rows=\"".$num_ape1."\" cols=\"".$cols_ape."'\" >".$fila_baja['Apellido1_Cl']."</TEXTAREA>";
					?>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Segundo Apellido:</td>
					<td align='left'>
					<?php
						$num_ape1 = intval(strlen($fila_baja['Apellido2_Cl'])/$div_ape) + 1;						
						echo "<TEXTAREA class='areatexto2' Name=\"Apellido2_C1".$i."\" readonly
						rows=\"".$num_ape1."\" cols=\"".$cols_ape."'\" >".$fila_baja['Apellido2_Cl']."</TEXTAREA>";
					?>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>País:</td>
						<td align='left'><input type='text' id="texto_detalles" name='pais_peregrino' class='input_formulario' value='<?echo $paises[$fila_baja['Id_Pais']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Provincia:</td>
						<td align='left'><input type='text' id="texto_detalles" name='provincia_peregrino' class='input_formulario' value='<?echo $provincias[$fila_baja['Id_Pro']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Localidad:</td>
					<td align='left'><input type='text' id="texto_detalles" name='localidad_peregrino' class='input_formulario' value='<?echo $fila_baja['Localidad_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td class='label_formulario' align="left">Dirección:</td>
					<td align='left'><input type='text' id="texto_detalles" name='direccion_peregrino' class='input_formulario' value='<?echo $fila_baja['direccion_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Código Postal:</td>
					<td align='left'><input type='text' id="texto_detalles" name='cp_peregrino' class='input_formulario' value='<?echo $fila_baja['CP_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Nacimiento:</td>
						<td align='left'><input type='text' id="texto_detalles" name='fecha_nac_peregrino' class='input_formulario' value='<?echo $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];?>'  style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Sexo:						
						<td align='left'><input type='text' id="texto_detalles" name='sexo_peregrino' class='input_formulario' value='<?
						if(trim(strtoupper($fila_baja['Sexo_Cl'])) == "M"){
							echo "Hombre";
						}else if(strtoupper($fila_baja['Sexo_Cl']) == "F"){
							echo "Mujer";
						}
						
						?>'  style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:</td>
						<td><input type='text' size='21' id="texto_detalles" name='telefono_peregrino' class='input_formulario' value="<?echo $fila_baja['Tfno_Cl'];?>" style="border:none;" readonly></td>											
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Email:</td>
					<td align='left' class='label_formulario'><input type='text' id="texto_detalles" size='20' name='email_peregrino' value="<?echo $fila_baja['Email_Cl'];?>" class='input_formulario' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Observaciones:</td>
					<td align='left'><textarea name="observaciones_peregrino" class='text_area_formulario' value='<?echo $fila_baja['Observaciones_Cl'];?>' readonly></textarea></td>
				</tr>
				<tr height="15">
					<td colspan="3" class="label_formulario" align="center">
						¿Está seguro de que desea eliminar este peregrino?
						<input type="hidden" name="busqueda" value="<?
							if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
								echo $_GET['busqueda'];
							}
						?>" >
					</td>					
				</tr>						
				<tr>
					<td colspan="3" align="center">
						<a onClick="document.forms[0].submit();"><img src="../imagenes/botones-texto/eliminar.jpg" alt="Realizar Baja de Peregrino" style="cursor:pointer;border:none;" /></a>
						&nbsp;
						<?
							if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
							?>
							<a href="?pag=listado_criterio_peregrinos.php"><img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar Baja de Peregrino" border="0"/></a>	
							<?
							}else{
							?>
							<a href="?pag=peregrinos.php"><img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar Baja de Peregrino"  style="border:none;" /></a>
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