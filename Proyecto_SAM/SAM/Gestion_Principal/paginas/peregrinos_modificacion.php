<?
	$sql_mod = "select * from cliente where DNI_Cl like '".$_GET['dni']."' and Tipo_documentacion like '".$_GET['tipo_doc']."'";	
	
	if($result_mod = mysql_query($sql_mod)){
		if(mysql_num_rows($result_mod) > 0){
		$fila_mod=  mysql_fetch_array($result_mod);
		$fecha_nacimiento = split("-",$fila_mod['Fecha_Nacimiento_Cl']);
		$dni = $fila_mod['DNI_Cl'];					
		?>
		<!--- MODIFICACIÓN peregrino -->
		<!--- MODIFICACIÓN peregrino -->
		<!--- MODIFICACIÓN peregrino -->
		<div id="modificacion_peregrino" style="display:block;">
			<table border='0'  cellspacing="0" cellpadding="0">
			<form name="formu_mod_peregrino" action="?pag=peregrinos.php" method="POST">
			<input type="hidden" name="accion" value="mod">
				<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
						<div style="height:25px;width:30px;float:left;" class="champi_izquierda">&nbsp;</div>
						<div style="width:290px;height:25px;text-align:center;float:left;" class="champi_centro">
						<div class="titulo" style="text-align:center;">Modificar Peregrino</div>
						</div>
						<div style="height:25px;width:30px;float:left;" class="champi_derecha">&nbsp;</div>
					</td>
				</thead>
				<tr>
				<td colspan="9" style="border-left:solid 1px #3F7BCC;border-bottom:solid 1px #3F7BCC;border-right:solid 1px #3F7BCC;">
				<table class='tabla_detalles'>
					<tr>					
					<td align="left" colspan="3">
						<table border='0'  cellspacing="0" cellpadding="0"><tr>
					<td align='left' class='label_formulario'>Tipo :</td>
					<input type="hidden" name="dni_ant_peregrino" value="<?echo $fila_mod['DNI_Cl'];?>">
					<td align='left'>					
					<select name="tipo_documentacion" style="float:left;" class='select_formulario'  onChange="documentacion(0);">
						<option value="D" alt="DNI" title="DNI"
						<?
							if($fila_mod['Tipo_documentacion'] =="D")	{
								echo " selected";
							}
						?>
						>D
						<option value="P" alt="Pasaporte" title="Pasaporte"
						<?
							if($fila_mod['Tipo_documentacion'] =="P")	{
								echo " selected";
							}
						?>
						>P
						<option value="C" alt="Permiso de Conducir" title="Permiso de Conducir"
						<?
							if($fila_mod['Tipo_documentacion'] =="C")	{
								echo " selected";
							}
						?>
						>C
						<option value="I" alt="Carta o Documento de Identidad" title="Carta o Documento de Identidad"
						<?
							if($fila_mod['Tipo_documentacion'] =="I")	{
								echo " selected";
							}
						?>
						>I
						<option value="N" alt="Permiso de residencia Español" title="Permiso de residencia Español"
						<?
							if($fila_mod['Tipo_documentacion'] =="N")	{
								echo " selected";
							}
						?>
						>N
						<option value="X" alt="Permiso de residencia UE" title="Permiso de residencia UE"
						<?
							if($fila_mod['Tipo_documentacion']=="X")	{
								echo " selected";
							}
						?>
						>X
					</select>	
					</td>
					<td class="label_formulario" style="padding-left:30px;">D.N.I. : </td>
					<td><input type='text' size='16' maxlength='30' class='input_formulario' name='dni_peregrino' value='<?echo $dni;?>' maxlength="20" ></td>
					</tr>
					</table>
				</td>
				</tr>
				<tr>
				<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Expedición:</td>
				<td align="left" colspan="2">
					<table> 
						<tr>
							<td>						
						<SELECT class='select_formulario'  name="dia_fecha_expedicion">
							<?
							///atiende
							//Guardo en $fecha_mod la fecha descuartizada
							$fecha_mod=split("-",$fila_mod['Fecha_Expedicion']);
							for($i=1;$i<=31;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == $_POST['dia_fecha_expedicion']){
									echo " selected";
								}else{
									if (intval($fecha_mod[2])==$i){
										echo " selected";
									}
								}
								echo ">".$i."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>
						
						<SELECT class='select_formulario'  name="mes_fecha_expedicion">
							<?
							for($i=1;$i<=12;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == intval($_POST['mes_fecha_expedicion'])){
									echo " selected";
								}else{
									if (intval($fecha_mod[1])==$i){
										echo " selected";
									}
								}
								echo ">".$meses[intval($i-1)]."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>
						<SELECT class='select_formulario'  name="anyo_fecha_expedicion">
							<?
							for($i=date("Y");$i>=1900;$i--){
								echo "<option value=\"".($i)."\"";
								if($i == $_POST['anyo_fecha_
								expedicion']){
									echo " selected";
								}else{
									if (intval($fecha_mod[0])==$i){
										echo " selected";
									}
								}
								echo ">".$i."</option>";
							}	
							?>
						</SELECT>
						</td>
						</tr>
					</table>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre:</td>
					<td align='left'><input type='text' name='nombre_peregrino' class='input_formulario' value='<?echo $fila_mod['Nombre_Cl'];?>' maxlength="20" onBlur="this.value=this.value.toUpperCase()"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Primer Apellido:</td>
					<td align='left'><input type='text' name='apellido1_peregrino' class='input_formulario' value='<?echo $fila_mod['Apellido1_Cl'];?>' maxlength="30" onBlur="this.value=this.value.toUpperCase()"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Segundo Apellido:</td>
					<td align='left'><input type='text' name='apellido2_peregrino' class='input_formulario' value='<?echo $fila_mod['Apellido2_Cl'];?>' maxlength="30" onBlur="this.value=this.value.toUpperCase()"></td>
				</tr>
				
							
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>País:</td>
					<td align="left">
						<SELECT class='select_formulario' name="pais_peregrino" onChange="paises(0);">							
							<?// PAISES 
							$result_paises = mysql_query($sql_paises);
							while($fila_paises = mysql_fetch_array($result_paises)){
								echo "<option value=".$fila_paises['Id_Pais']." alt=".$fila_paises['Nombre_Pais'];
								if($fila_paises['Id_Pais'] == $fila_mod['Id_Pais'])	{
									echo " selected ";
								}
								echo "title=".$fila_paises['Nombre_Pais']." >";
								if(strlen($fila_paises['Nombre_Pais'])<25){	
									echo $fila_paises['Nombre_Pais'];
								}else{
									echo substr($fila_paises['Nombre_Pais'],0,22)."...";
								}
							}						
						?>
						</SELECT>
					</td>
				</tr>
			
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Provincia:</td>
					<td align='left'>
						
						<SELECT class='select_formulario' name="provincia_peregrino">
							<? //provincias 
								$result_provincias = mysql_query($sql_provincias);
							while($fila_provincias = mysql_fetch_array($result_provincias)){								
								echo "<option value=\"".$fila_provincias['Id_Pro']."\"";
								
								
								if($fila_provincias['Id_Pro'] == $fila_mod['Id_Pro']){
									echo " selected ";
								}
								echo ">".$fila_provincias['Nombre_Pro'];
							}
							?>
						</SELECT>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Localidad:</td>
					<td align='left'><input type='text' name='localidad_peregrino' class='input_formulario' value='<? echo $fila_mod['Localidad_Cl'];?>' maxlength="150"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align="left" class='label_formulario'>Dirección:</td>
					<td align='left'><input type='text' name='direccion_peregrino' class='input_formulario' value='<? echo $fila_mod['Direccion_Cl'];?>' maxlength="150"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Código Postal:</td>
					<td align='left'><input type='text' name='cp_peregrino' class='input_formulario' value='<?echo $fila_mod['CP_Cl'];?>' maxlength="5"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Nacimiento:</td>
					<td>
					<table border="0">
						<tr>
							<td>
						<SELECT class='select_formulario' name="dia_fecha_peregrino">
							<option value='0' selected </option>
							<?
							for($i=1;$i<=31;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=\"".$i."\" ";
								if($i == $fecha_nacimiento[2]){
									echo "selected ";
								}
								echo ">".$i."</option>";
							}
							?>
						</SELECT>
							</td>
							<td>
						<SELECT class='select_formulario' name="mes_fecha_peregrino">
							<option value='0' selected </option>
							<?
							for($j=1;$j<=12;$j++){
								if($j<10){
									$j = "0".$j;
								}
								echo "<option value=\"".$j."\" ";
								if($j == intval($fecha_nacimiento[1])){
									echo " selected ";
								}
								echo ">".$meses[$j-1]."</option>";
							}
							?>
						</SELECT>
							</td>
							<td>
						<SELECT class='select_formulario' name="anyo_fecha_peregrino">
							<?
							for($y=date("Y");$y>=1900;$y--){
								echo "<option value=\"".$y."\" ";
								if($y == $fecha_nacimiento[0]){
									echo " selected ";
								}
								echo ">".$y."</option>";
							}	
							?>
						</SELECT>
							</td>
						</tr>
					</table>
					</td>
				</tr>
					<script>paises(0);</script>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Sexo:						
						<span style='position:relative;top:5px;'>&nbsp;</span>
					</td>
					<td align='left'><input type='radio' name='sexo_peregrino' value="M" 
					<?
							if($fila_mod['Sexo_Cl'] == "M"){
								echo " checked";
							}
					?>					
					><label class="label_formulario">Hombre</label>
						<input type='radio' name='sexo_peregrino' value="F" 
						<?
							if($fila_mod['Sexo_Cl'] == "F"){
								echo " checked";
							}
						?>
						><label class="label_formulario">Mujer</label></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td colspan="2" align="left"><input type='text' size='21' name='telefono_peregrino' class='input_formulario'  value="<?echo $fila_mod['Tfno_Cl']?>" maxlength="12"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Email:</td>
					<td align='left' class='label_formulario'><input type='text' size='20' style="font-weight:normal;" name='email_peregrino' class='input_formulario' value="<?echo $fila_mod['Email_Cl']?>" maxlength="100"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Observaciones:</td>
					<td align='left'><textarea name="observaciones_peregrino" class='text_area_formulario' maxlength="256" style="height:80px;width:160px;"><?echo $fila_mod['Observaciones_Cl']?></textarea></td>
				</tr>
			<tr>
			<td colspan="4" align="center">
			<div>
			<input type="hidden" name="busqueda" value="<?
				if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
					echo $_GET['busqueda'];
				}
			?>" >
				<a href="#" onClick="if(valida_campos(0)){document.forms['0'].submit();}"><img src="../imagenes/botones-texto/modificar.jpg" alt="Realizar Modificación de Peregrino"  border="0"/></a>	&nbsp;&nbsp;
				<?
				if(isset($_GET['busqueda']) && $_GET['busqueda']=="si"){
				?>
				<a href="?pag=listado_criterio_peregrinos.php"><img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar Modificación de Peregrino" border="0"/></a>	
				<?
				}else{
				?>
				<a href="?pag=peregrinos.php"><img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar Modificación de Peregrino" style="border:none;" /></a>
				<?
				}	
				?>
			</div>
			</td>
			</tr>
			</table>
			</tr>
			</td>
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