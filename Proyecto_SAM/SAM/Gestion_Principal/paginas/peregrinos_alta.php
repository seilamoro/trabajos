<?php
 // Devuelve el servicio por defecto
 function servicio_defecto($db2)
            {
                $query_pernocta = "SELECT column_default FROM information_schema.columns
                                   WHERE table_schema='".$_SESSION['conexion']['db']."'
                                   AND table_name='pernocta'
                                   AND column_name='Id_Servicios';";
                @$res_default = mysql_query($query_pernocta,$db2);
                if (!$res_default) echo mysql_errno()." Error: ".mysql_error().$query_pernocta."<BR>";
                @$fila_default = mysql_fetch_array($res_default);
                return $fila_default['column_default'];
            }
            
?>		<div id="alta_peregrino" style="display:block;">
		 <div class="curved" style="display:block;">
		 <form name="formu_alta_peregrino" action="?pag=peregrinos.php" method="POST">
		 
		 <input type="hidden" name="accion" value="alta">
		 <input type="hidden" name="fecha_cal" value="<?
				 //preparación de la fecha de calendario
				 if(isset($_GET['fecha_cal']) && $_GET['fecha_cal']!=""){					 
					$cal_split = split("-",trim($fecha_calendario));
					 echo trim($cal_split[2])."-".trim($cal_split[1])."-".trim($cal_split[0]);
				}else if(isset($_POST['fecha_cal']) && $_POST['fecha_cal']!=""){
					$cal_split = split("-",trim($fecha_calendario));
					 echo trim($cal_split[2])."-".trim($cal_split[1])."-".trim($cal_split[0]);
				 }else{
					 echo date("d-m-Y");
				}				
				 ?>">
		 <input type="hidden" name="test_dni" value="<?
				if(isset($_POST['test_dni']) && $_POST['test_dni'] !=""){
					echo strtoupper($_POST['test_dni']);
				}else{
					echo "0";
				}

		?>">
		 <input type="hidden" name="dni_ant_peregrino" value="<?echo strtoupper($_POST['dni_ant_peregrino']);?>">
		 <input type="hidden" name="existe"
		 <?
		if(isset($_POST['existe']) && $_POST['existe'] !="")	 {
				echo "value=".$_POST['existe'];	
		 }else{
				echo "value=\"false\"";	
		 }
		 ?>
		 >
		 <input type="hidden" name="sub" 
		 <?
			if(isset($_POST['sub']) && $_POST['sub'] == "true"){
				echo "value=\"false\"";
			}else{
				echo "value=\"\"";
			}
		 ?>>			
		<b class="b1 c1"></b>
		 <b class="b2 c2"></b>
		 <b class="b3 c3"></b>
		 <b class="b4 c4"></b>
		  <div class="boxcontent">
		   <ul id="menu">
			<li>
			 <a href="#" onclick="abrir(1,'')">
			  <b class="h2"><font color="#064C87" face="verdana" size="3"><b>Datos Personales del Peregrino</b></font></b><br />
			  </a>
			  
			  <span id="lis1" style="display:block">
			   
			   <table border="0" align="center" width="100%">
			   <tr height='25'>
			   <td>&nbsp;</td><td colspan="3" align="left">
				<table>
					<tr>
					<td align='left' class='label_formulario'>Tipo : </td>
					<td>
					<div>
					<!-- <select name="tipo_documentacion" style="float:left;" class='select_formulario' onChange="documentacion(0);if(document.forms[0].dni_peregrino.value != ''){dni_change();}" title="tipo documentacion"> -->
					    	<select name="tipo_documentacion" style="float:left;" class='select_formulario' onChange="documentacion(0);" title="tipo documentacion">
						<option value="D" alt="DNI" title="DNI"
						<?
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="D")	{
								echo " selected";
							}
						?>
						>D
						<option value="P" alt="Pasaporte" title="Pasaporte"
						<?
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="P")	{
								echo " selected";
							}
						?>
						>P
						<option value="C" alt="Permiso de Conducir" title="Permiso de Conducir"
						<?
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="C")	{
								echo " selected";
							}
						?>
						>C
						<option value="I" alt="Carta o Documento de Identidad" title="Carta o Documento de Identidad"
						<?
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="I")	{
								echo " selected";
							}
						?>
						>I
						<option value="N" alt="Permiso de residencia Español" title="Permiso de residencia Español"
						<?
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="N")	{
								echo " selected";
							}
						?>
						>N
						<option value="X" alt="Permiso de residencia UE" title="Permiso de residencia UE"
						<?
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="X")	{
								echo " selected";
							}
						?>
						>X
					</select>		
					</td><td class="label_formulario" style="padding-left:45px;">	D.N.I. :
								
					</td>
					<td>					
					<input type='text'	
					<?php
					//Si el tipo de documentación es 'DNI' , limitará la longitud del campo a 9 caracteres. De lo contrario tendrá 
					//una longitud de 30 caracteres.
					if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] == "D"){
						echo " maxlength='9' ";
					}else{
						echo " maxlength='30' ";
					}
					if (isset($_GET['accion']) AND $_SESSION['reserva']['mostrar']){ echo(" value='".$_SESSION['reserva']['dni']."' ");}
					?>					
					size="19" name='dni_peregrino' class='input_formulario' id="alta_dni_peregrino" style="float:left;" onChange="dni_change();" value='<?php echo $_POST['dni_peregrino'];?>' onBlur="this.value=this.value.toUpperCase();" >
					
					</td>
					<!--<input type='text' maxlength='30' class='input_formulario' size="16 " name='dni_peregrino' style="float:left;" value='<?echo strtoupper($_POST['dni_peregrino']);?>'  onChange="dni_change();" maxlength="30" ></td>-->
					<td width="30"><a href="#" id="lupa_alta" onClick="abrir_busqueda();">	
					<img src="../imagenes/botones/lupa.png"  style="border:none;width:25px;height:25px;"  alt="Búsqueda de clientes" /></a></div></td>
				</tr>
				</table>
				</td></tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Expedición:</td>
					<td align="left" colspan="2">
					<table> 
						<tr>
							<td>
						<SELECT class='select_formulario'  name="dia_fecha_expedicion">
							<?
							echo "<option value='' selected </option>";
							for($i=1;$i<=31;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == $_POST['dia_fecha_expedicion']){
									echo " selected";
								}
								echo ">".$i."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>
						
						<SELECT class='select_formulario'  name="mes_fecha_expedicion">
							<?
							echo "<option value='' selected </option>";
							for($i=1;$i<=12;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == intval($_POST['mes_fecha_expedicion'])){
									echo " selected";
								}
								echo ">".$meses[intval($i-1)]."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>
						<SELECT class='select_formulario'  name="anyo_fecha_expedicion">
							<?
							echo "<option value='' selected </option>";
							for($i=date("Y");$i>=1900;$i--){
								echo "<option value=\"".($i)."\"";
								if($i == $_POST['anyo_fecha_expedicion']){
									echo " selected";
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
					<td colspan="2" align='left'><input type='text' name='nombre_peregrino' class='input_formulario' value='<?echo $_POST['nombre_peregrino'];?>' maxlength="20" onBlur="this.value=this.value.toUpperCase()"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Primer Apellido:</td>
					<td colspan="2" align='left'><input type='text' name='apellido1_peregrino' class='input_formulario' value='<?echo $_POST['apellido1_peregrino'];?>' maxlength="30" onBlur="this.value=this.value.toUpperCase()"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Segundo Apellido:</td>
					<td colspan="2" align='left'><input type='text' name='apellido2_peregrino' class='input_formulario' value='<?echo $_POST['apellido2_peregrino'];?>' maxlength="30" onBlur="this.value=this.value.toUpperCase()"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>País:</td>
					<td align="left" colspan="2">
						<!--
						<SELECT onChange="paises(0);" class='select_formulario' name="pais_peregrino">	
						<?php // PAISES 
							$result_paises = mysql_query("select * from pais order by Nombre_Pais asc");
							while($fila_paises = mysql_fetch_array($result_paises)){
								echo "<option value=".$fila_paises['Id_Pais']." alt=".$fila_paises['Nombre_Pais']." title=".$fila_paises['Nombre_Pais'];
								if(isset($_POST['pais_peregrinos']) && $_POST['pais_peregrinos'] != ""){ 
									if( $fila_paises['Id_Pais'] == $_POST['pais_peregrinos']){
										echo " selected";	
									}
								}else{
									if( $fila_paises['Id_Pais'] == "ES"){
										echo " selected";	
									}
								}
								echo ">";
								if(strlen($fila_paises['Nombre_Pais'])<25){	
									echo $fila_paises['Nombre_Pais'];
								}else{
									echo substr($fila_paises['Nombre_Pais'],0,22)."...";
								}
							}						
						?>
						</SELECT>
						-->
						<SELECT onChange="paises(0);" class='select_formulario' name="pais_peregrino">	
						
						</SELECT>
						
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Provincia:</td>
					<td align="left" colspan="2">
						<?php
						//ATIENDE
							?>
						<SELECT class='select_formulario' name="provincia_peregrino">
								<? //provincias 
								$result_provincias = mysql_query($sql_provincias);
							echo "<OPTION value=''>(Sólo España)</OPTION>";
							while($fila_provincias = mysql_fetch_array($result_provincias)){								
								echo "<option value=\"".$fila_provincias['Id_Pro']."\" ";
								///atiende
								//if(isset($_POST['provincia_peregrino']) && $_POST['provincia_peregrino'] != ""){ 		
									//echo "FILA = ".$fila_provincias['Id_Pro']." ANTERIOR = ".$_POST['provincia_peregrino'];
									if($fila_provincias['Id_Pro'] == $_POST['provincia_peregrino']){
										echo " selected";
									}
								//}else{
									
								//}
								echo ">".$fila_provincias['Nombre_Pro'];
							}
							?>									
						</SELECT>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Localidad:</td>
					<td align='left' colspan="2"><input type='text' name='localidad_peregrino' class='input_formulario' value='<?echo $_POST['localidad_peregrino'];?>' maxlength="150"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align="left" class='label_formulario'>Dirección:</td>
					<td align='left' colspan="2"><input type='text' name='direccion_peregrino' class='input_formulario' value='<?echo $_POST['direccion_peregrino'];?>' maxlength="150"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Código Postal:</td>
					<td align='left' colspan="2"><input type='text' name='cp_peregrino' class='input_formulario' value='<?echo $_POST['cp_peregrino'];?>' maxlength="5"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Nacimiento:</td>
					<td align="left" style="padding:0px 0px 0px 0px;" colspan="2">
					<table>
						<tr>
							<td>
						<SELECT class='select_formulario' style="margin-left:-3px;" name="dia_fecha_peregrino">
							<?
							echo "<option value='0' selected </option>";
							for($i=1;$i<=31;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == $_POST['dia_fecha_peregrino']){
									echo " selected";
								}
								echo ">".$i."</option>";
							}
							?>
						</SELECT>
							</td>
							<td>
						<SELECT class='select_formulario' style="margin-left:-3px;" name="mes_fecha_peregrino">
							<?
							echo "<option value='0' selected </option>";
							for($i=1;$i<=12;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == intval($_POST['mes_fecha_peregrino'])){
									echo " selected";
								}
								echo ">".$meses[$i-1]."</option>";
							}
							?>
						</SELECT>
							</td>
							<td>
						<SELECT class='select_formulario' style="margin-left:-3px;" name="anyo_fecha_peregrino">
							<?
							echo "<option value='' selected </option>";
							for($i=date("Y");$i>=1900;$i--){
								echo "<option value=".($i);
								if($i == $_POST['anyo_fecha_peregrino']){
									echo " selected";
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
					<td>&nbsp;</td><td align='left'  class='label_formulario'>Sexo:						
						<span style='position:relative;top:5px;'>&nbsp;</span>
					</td>
					<td colspan="2"><input type='radio' name='sexo_peregrino' value="M"
					<?
					if($_POST['sexo_peregrino'] == "M"){
						echo " checked";
					}
					?>
					><label class="label_formulario">Hombre</label>
						<input type='radio' name='sexo_peregrino' value="F"
						<?
						if($_POST['sexo_peregrino'] == "F"){
							echo " checked";
						}
						?>
						><label class="label_formulario">Mujer</label></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:</td>
						<td colspan="2"><input type='text' size='21' name='telefono_peregrino' class='input_formulario' value="<?echo $_POST['telefono_peregrino'];?>" maxlength="13"></td>	
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Email:</td>
					<td align='left' class='label_formulario' colspan="2"><input type='text' size='20' style="font-weight:normal;" name='email_peregrino' class='input_formulario' value="<?echo $_POST['email_peregrino'];?>" maxlength="100"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario' valign="top" style="padding-top:5px;">Observaciones:</td>
					<td align='left' colspan="2" valign="top"><textarea name="observaciones_peregrino" rows="5" class='text_area_formulario' value= maxlength="256" style="height:80px;width:180px;"><?echo $_POST['observaciones_peregrino'];?></textarea></td>
				</tr>
				</table>
			  </span>			  
			</li>
			<li>
			 
			  <b class="b1"></b>
			  <b class="b2"></b>
			  <b class="b3"></b>
			  <b class="b4"></b>
			  <!-- Llamamos a documentación -->
			<script>documentacion(0);</script>			  
			  <a href="#" onclick="abrir(2,0);"><b class="h2"><font color="#064C87" face="verdana" size="3"><b>Datos de Estancia</b></font></b><br /></a>
			  
			  <span id="lis2" style="display:none">
			   <br>

			   <table border="0" align="center">
			   <tr>
					<td colspan="2"><input type="hidden" readonly class='input_formulario' size="2" name="id_habitacion"></td>
				</tr>
				<tr>
				<?$fecha_entrada = date("Y-m-d");
					$split = split("-",$fecha_entrada);			   
			   ?>
			   <td><label class='label_formulario' >Fecha llegada :</label></td>
			   <td>
			   <?php
				//Correccion para que solo se muestren en el select los dias del mes seleccionado
				if (isset($_POST['mes_fecha_llegada_peregrino']) && $_POST['mes_fecha_llegada_peregrino'] != ""){
					$fecha_al = mktime(0,0,0,$_POST['mes_fecha_llegada_peregrino']+1,0,$_POST['anyo_fecha_llegada_peregrino']);
					$dia_limite_al = strftime('%d',$fecha_al);
				}else{
					$mes_act = date("m");
					$fecha_al = mktime(0,0,0,$mes_act+1,0,0);
					$dia_limite_al = strftime('%d',$fecha_al);
				}
				//Fin Correccion
			   ?>
				<input type="hidden" name="ant_dia_fecha_llegada" value="<?
					if(isset($_POST['dia_fecha_llegada_peregrino']) && $_POST['dia_fecha_llegada_peregrino'] != ""){
						echo $_POST['dia_fecha_llegada_peregrino'];
					}
				?>">
				<input type="hidden" name="ant_mes_fecha_llegada" value="<?
					if(isset($_POST['mes_fecha_llegada_peregrino']) && $_POST['mes_fecha_llegada_peregrino'] != ""){
						echo $_POST['mes_fecha_llegada_peregrino'];
					}
				?>">
				<input type="hidden" name="ant_anyo_fecha_llegada" value="<?
					if(isset($_POST['anyo_fecha_llegada_peregrino']) && $_POST['anyo_fecha_llegada_peregrino'] != ""){
						echo $_POST['anyo_fecha_llegada_peregrino'];
					}
				?>">
			   <table>
					<tr>
						<td>
				<SELECT class='select_formulario' style="margin-left:-3px;" name="dia_fecha_llegada_peregrino" onChange="document.forms[0].sub.value='false';cambiar_dia(document.forms[0].dia_fecha_llegada_peregrino.value,document.forms[0].mes_fecha_llegada_peregrino.value,document.forms[0].anyo_fecha_llegada_peregrino.value);">
							<?
							for($i=1;$i<=$dia_limite_al;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=\"".$i."\"";
								if(isset($_POST['dia_fecha_llegada_peregrino']) && $_POST['dia_fecha_llegada_peregrino']!=""){
									if($i == $_POST['dia_fecha_llegada_peregrino']){
										echo " selected";
									}
								}else{
									if($i == $split[2]){
										echo " selected";
									}
								}
								echo ">".$i."</option>";
							}
							?>
						</SELECT>
							</td>
							<td>
						<SELECT class='select_formulario' style="margin-left:-3px;" name="mes_fecha_llegada_peregrino" onChange="cambio_botones_habitaciones();">
							<?
							for($i=1;$i<=12;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=\"".$i."\"";
								if(isset($_POST['mes_fecha_llegada_peregrino']) && $_POST['mes_fecha_llegada_peregrino']!=""){
									if($i == $_POST['mes_fecha_llegada_peregrino']){
										echo " selected";
									}
								}else{
									if($i == $split[1]){
										echo " selected";
									}
								}
								echo ">".$meses[$i-1]."</option>";
							}
							?>
						</SELECT>
							</td>
							<td>
						<SELECT class='select_formulario' style="margin-left:-3px;" name="anyo_fecha_llegada_peregrino" onChange="cambio_botones_habitaciones();">
							<?
							for($i=date("Y");$i<=intval(date("Y"))+1;$i++){
								echo "<option value=\"".$i."\"";
								if(isset($_POST['anyo_fecha_llegada_peregrino']) && $_POST['anyo_fecha_llegada_peregrino']!=""){
									if($i == $_POST['anyo_fecha_llegada_peregrino']){
										echo " selected";
									}
								}else{
									if($i == $split[0]){
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
				<tr id="pernocta">
					<td><input type="hidden" name="pernocta_peregrino" value="1"></td>
				</tr>
				<tr id="noches_pagadas">
					<td colspan="2"><input type="hidden" class='input_formulario' name="noches_pagadas" value="0"></td>
				</tr>				
				<tr>
					<td align="left"><label class='label_formulario' >Modo de peregrinación : </label></td>
					<td>
						<select class='select_formulario' name="modo_peregrinacion">
								<option value="P" <?php if($_POST["modo_peregrinacion"]=="P"){echo "selected";}?>>A Pie
								<option value="C" <?php if($_POST["modo_peregrinacion"]=="C"){echo "selected";}?>>A Caballo
								<option value="B" <?php if($_POST["modo_peregrinacion"]=="B"){echo "selected";}?>>En bicicleta
						</select>
					</td>
				</tr>

				<tr>
					<td><label class='label_formulario' >Lesionado : </label></td>
					<td><input type="checkbox" name="lesionado" value="S" 
					<?
							if(isset($_POST['lesionado']) && $_POST['lesionado'] == "S"){
								echo "checked";
							}
						?>
					>	
					</td>
				</tr>
				<?
				$servicios_activados = table_exists(servicios,$db);
				if($servicios_activados){
					$sql_servicios = "select Id_Servicios , Descripcion from Tipo_Servicios";
					$result_servicios = mysql_query($sql_servicios);
				?>
				<tr>		
					<td><label class='label_formulario'>Servicio : </label></td>
					<td>
						<select name="servicio_peregrino" ><?
							while($fila_servicios = mysql_fetch_array($result_servicios))
							{
								echo "<option value=\"".$fila_servicios['Id_Servicios']."\"";
								if(isset($_POST['servicio_peregrino']) && trim($_POST['servicio_peregrino']) != ""){
									if(trim($fila_servicios['Id_Servicios']) == trim($_POST['servicio_peregrino'])){
										echo " selected ";
									}
								}else
								{
									if(servicio_defecto($db2)==$fila_servicios['Id_Servicios'])
									{
										echo " selected ";
									}
								}
								echo ">".trim($fila_servicios['Descripcion'])."</option>";
							}
				?>
				</select>
				</td>
				</tr>	
				<?}?>
				<input type="hidden" name="id_cama">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>	 
				</tr>
				</table>				
					<div style="text-align:right;height:35px;;width:120px;margin-left:33%;">
							<a href="#"><IMG id="boton_nuevo_habitacion2" src="../imagenes/botones-texto/aceptar_fondo_azul.jpg"   border="0" style="display:none;border:none;" onclick="evalua_habitacion();" /></a>
							<a href="#"><img id="boton_ver_habitaciones" src="../imagenes/botones-texto/verhabitaciones_fondo_azul.jpg" style="border:none;" onClick="document.forms[0].sub.value='true';document.forms[0].submit();" /></a>
						</div>
				<br>
				<input type="hidden" name="asignada" value="0">
			  </span>
			 
			</li>
			</ul>
		  
		  </div>
		 <b class="b4 c4"></b>
		 <b class="b3 c3"></b>
		 <b class="b2 c2"></b>
		 <b class="b1 c1"></b>
		</div>			
		</div>