<?
	//Se realizan las consultas necesarias para para comprobar que existe el cliente y tomar sus datos, y comprobar que ha realizado alguna estancia en el albergue como alberguista
		$sql_baja = "select * from cliente where DNI_Cl like '".trim($_GET['dni'])."' and Tipo_Documentacion like '".trim($_GET['tipo_doc'])."'";
		if($result_baja = mysql_query($sql_baja)){
			if(mysql_num_rows($result_baja) > 0){

				$fila_baja = mysql_fetch_array($result_baja);
				$fecha_nacimiento = split("-", $fila_baja['Fecha_Nacimiento_Cl']);
				$sql_pernocta = "select * from pernocta where DNI_Cl like  '".trim($_GET['dni'])."' order by Fecha_Llegada"; 
				if($result_pernocta = mysql_query($sql_pernocta)){
					$fila_pernocta = mysql_fetch_array($result_pernocta);
					if(mysql_num_rows($result_pernocta) == 1 && trim($fila_pernocta['Fecha_Llegada']) == date("Y-m-d")){
					//	echo "<script>alert('Se dispone a eliminar un alberguista dado de alta en el día de hoy. ');</script>";
					}else{
						//En caso dehaber realizado más de 1 estancia, o que la estancia sea anterior al día de hoy, no podrá ser eliminada, y se volverá a la página principal de alberguistas
						if(mysql_num_rows($result_pernocta) > 1 || trim($fila_pernocta['Fecha_Llegada']) != date("Y-m-d")){
							echo "<script>alert('El alberguista ha realizado estancias anteriormente, no puede ser eliminado. ');";
							if(isset($_GET['busqueda']) && $_GET['busqueda'] == "si")
							{							  
								echo "location.href='index.php?pag=listado_criterio_alberguistas.php'";								
							}
							else
							{							  
								echo "location.href='?pag=alberguistas.php'";							
							}
							echo "</script>";
						}
					}
				}
				
		// Variables para los textArea			
		$cols_nom = 30;
	    $div_nom = 22;
	    $cols_ape = 30;
	    $div_ape = 22;		
?>
		<!--- BAJA ALBERGUISTA -->
		<!--- BAJA ALBERGUISTA -->
		<div id="baja_alberguista" >
			<table border='0' cellspacing="0" cellpadding="0">
			<form name="formu_baja_alberguista" method="POST" action="?pag=alberguistas.php">
				<input type="hidden" name="accion" value="baja">
				<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
						<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
						<div style="width:290px;height:25px;text-align:center;float:left;" class="champi_centro">
						<div class="titulo" style="width:250px;text-align:center;" >Eliminar Alberguista</div>
						</div>
						<div style="height:25px;width:30px;float:left;" class="champi_derecha">&nbsp;</div>
					</td>
				</thead>
				<tr>
				<td colspan="9" style="border-left:solid 1px #3F7BCC;border-bottom:solid 1px #3F7BCC;border-right:solid 1px #3F7BCC;background-color:#F4FCFF;">
				<table class='tabla_detalles'>
				<tr height='25'>
					<td>&nbsp;</td>
					<td colspan="3" align="left">
						<table>
							<tr>
							<td align='left' class='label_formulario'>Tipo :</td>
							<td align='left'><input type='text' size='1' id="texto_detalles" maxlength='1' class='input_formulario' name='tipo_documentacion' style="border:none;" readonly value='<?echo $fila_baja['Tipo_documentacion'];?>'></td>
							<td class="label_formulario" style="padding-left:5px;">D.N.I. : </td>
							<td><input type='text' size='11' maxlength='30' id="texto_detalles" class='input_formulario' name='dni_alberguista' style="border:none;" readonly value='<?echo $fila_baja['DNI_Cl'];?>'></td>
						</tr>
						</table>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre:</td>
					<td align='left'>					
					<?php		
						$num_nom = intval(strlen($fila_baja['Nombre_Cl'])/$div_nom) + 1;									
						echo "<TEXTAREA class='areatexto2' Name=\"Nombre_C1".$i."\" 
						rows=\"".$num_nom."\" cols=\"".$cols_nom."\"  >".$fila_baja['Nombre_Cl']."</TEXTAREA>";
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
						<td align='left'><input type='text' id="texto_detalles" name='pais_alberguista' class='input_formulario' value='<?echo $paises[$fila_baja['Id_Pais']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Provincia:</td>
						<td align='left'><input type='text' id="texto_detalles" name='provincia_alberguista' class='input_formulario' value='<?echo $provincias[$fila_baja['Id_Pro']];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Localidad:</td>
					<td align='left'><input type='text' id="texto_detalles" name='localidad_alberguista' class='input_formulario' value='<?echo $fila_baja['Localidad_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td class='label_formulario' align="left">Dirección:</td>
					<td align='left'><input type='text' id="texto_detalles" name='direccion_alberguista' class='input_formulario' value='<?echo $fila_baja['direccion_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Código Postal:</td>
					<td align='left'><input type='text' id="texto_detalles" name='cp_alberguista' class='input_formulario' value='<?echo $fila_baja['CP_Cl'];?>'  style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Nacimiento:</td>
						<td align='left'><input type='text' id="texto_detalles" name='fecha_nac_alberguista' class='input_formulario' value='<?echo $fecha_nacimiento[2]."-".$fecha_nacimiento[1]."-".$fecha_nacimiento[0];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Sexo:						
						<td align='left'><input type='text' id="texto_detalles" name='sexo_alberguista' class='input_formulario' value='<?echo $fila_baja['sexo_Cl'];?>' style="border:none;" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:</td>
						<td align='left'><input type='text' size='21' id="texto_detalles" name='telefono_alberguista' class='input_formulario' style="border:none;" value="<?echo $fila_baja['Tfno_Cl'];?>" readonly></td>											
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Email:</td>
					<td align='left' class='label_formulario'><input type='text' id="texto_detalles" size='20' name='email_alberguista' class='input_formulario' style="border:none;" value="<?echo $fila_baja['Email_Cl'];?>" readonly></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Observaciones:</td>
					<td align='left'><textarea name="observaciones_alberguista" id="texto_detalles" class='text_area_formulario' style="height:80px;width:180px;"  value='<?echo $fila_baja['Observaciones_Cl'];?>' readonly></textarea></td>
				</tr>
				<tr height="15">
					<td colspan="3" class="label_formulario" align="center">
						¿Está seguro de que desea eliminar este alberguista?
					</td>					
				</tr>		
				<tr>
					<td colspan="3" align="center">
					<input type="hidden" name="busqueda" value="<?
							if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
								echo $_GET['busqueda'];
							}
						?>" >
						<a href="#" onClick="document.forms['0'].submit();"><img src="../imagenes/botones-texto/eliminar.jpg" alt="Realizar Baja de  Alberguista" style="border:none;" /></a>
						&nbsp;
						<?
							if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
							?>
							<a href="?pag=listado_criterio_alberguistas.php"><img alt="Cancelar Baja de Alberguista" src="../imagenes/botones-texto/cancelar.jpg" border="0"/></a>	
							<?
							}else{
							?>
							<a href="?pag=alberguistas.php"><img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar Baja de Alberguista"  border="0"/></a>	
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
		<!--- FIN BAJA ALBERGUISTA -->
<?
	}else{ //En caso de que la consulta a la tabla pernocta_p ($result_baja)  no devuelva resultados
		echo "<script>alert('El alberguista solicitado no existe en el sistema.');";
		if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
			echo "location.href='index.php?pag=listado_criterio_alberguistas.php'";
		}else{
			echo "location.href='?pag=alberguistas.php'";
		}
		echo "</script>";
	}
}else{ //En caso de que la consulta a la tabla pernocta_p ($result_baja) devuelva false
	echo "<script>alert('El alberguista solicitado no existe en el sistema.');";
	if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
		echo "location.href='index.php?pag=listado_criterio_alberguistas.php'";
	}else{
		echo "location.href='?pag=alberguistas.php'";
	}
	echo "</script>";
}
?>
		