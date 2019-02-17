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
// Página incluida en alberguistas.php

//Contiene el formulario de alta de alberguista

?>


<div id="alta_alberguista" style="display:block;">
				 <div class="curved" style="display:block;">
		 <form id="formu_alta_alberguista" name="formu_alta_alberguista" action="?pag=alberguistas.php<?php 
		 if (isset($_GET['accion']) && ($_GET['accion'] == 'reserva' || $_GET['accion'] == 'continuar_reserva')){echo "&accion=continuar_reserva";}
		 ?>" method="POST">
		 <input type="hidden" name="sub" 
		 <?php
			if($_POST['sub'] == "true"){ //Si el sub enviado es verdadero (Mostrar distribución de habitaciones)
				echo "value=\"false\"";  //Toma el valor false (Dar de alta en la base de datos)
			}else{
				if(isset($_SESSION['reserva']['sw_reserva']) && $_SESSION['reserva']['sw_reserva'] == "true"){
					echo "value=\"false\"";// alberto estubo aqui
				}else{
					echo "value=\"\"";  //Sino, toma valor vacío
				}
			}

		 ?>>	
		 		 <input type="hidden" name="fecha_cal" value="<?php
				 //preparación de la fecha de calendario
				 if(isset($_GET['fecha_cal']) && $_GET['fecha_cal']!=""){					 
					$cal_split = split("-",trim($fecha_calendario));
					 echo trim($cal_split[2])."-".trim($cal_split[1])."-".trim($cal_split[0]);
				}else if(isset($_POST['fecha_cal']) && $_POST['fecha_cal']!=""){
					$cal_split = split("-",trim($fecha_calendario));
					 echo trim($cal_split[2])."-".trim($cal_split[1])."-".trim($cal_split[0]);
				 }else{
					 if ($_SESSION['reserva']['sw_reserva'] == true){
						$cal_split = split("-",trim($_SESSION['reserva']['fecha_llegada']));
						echo trim($cal_split[2])."-".trim($cal_split[1])."-".trim($cal_split[0]);
					 }else{
						 echo date("d-m-Y");
					 }
				}				
				 ?>">				
		 <input type="hidden" name="estancia_parcial" value="<?php
		 //Rellena el valor del campo estancia parcial con el valor enviado (En caso de que exista.) de lo contrario toma el valor 0
			if(isset($_GET['estancia_parcial']) && $_GET['estancia_parcial']!= ""){
				echo trim($_GET['estancia_parcial']);
			}else{
				echo 0;
			}
		 ?>">
		 <input type="hidden" name="accion" value="<?php
		 if(isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva'])== "true"){
			echo "reserva";
		}else{
			echo "alta";
		}
		 ?>">
		 <input type="hidden" name="test_dni" id="test_dni" value="<?php
			//Campo utilizado a la hora de realizar la búsqueda de clientes para rellenar los campos automáticamente. (funcion javascript dni_change())
				if(isset($_POST['test_dni']) && $_POST['test_dni'] != ""){
					echo "0";
				}else{
					echo "";
				}
		 ?>">
		<input type="hidden" name="sub_habitaciones" 
		 <?php
			if(isset($_POST['sub_habitaciones']) && $_POST['sub_habitaciones'] == "true"){
				echo "value=\"true\"";
			}else{
				echo "";
			}
		 ?>>		
		 <input type="hidden" name="existe"
		 <?php
		 //Indica si el cliente existía previamente en la base de datos o si es un cliente nuevo
		if(isset($_POST['existe']) && $_POST['existe'] !="")	 {
				echo "value=".$_POST['existe'];	
		 }else{
				echo "value=\"false\"";	
		 }
		 ?>
		 >
		<b class="b1 c1"></b>
		 <b class="b2 c2"></b>
		 <b class="b3 c3"></b>
		 <b class="b4 c4"></b>
		  <div class="boxcontent">
		   <ul id="menu">
			<li>
			 <a href="#" onclick="abrir(1,'')">
			  <b class="h2"><font color="#064C87" face="verdana" size="3"><b>Datos Personales del Alberguista</b></font></b><br />
			  </a>
			  
			  <span id="lis1" style="display:block">
			   
			   <table border="0" align="center">
			   <tr height='25'>
					<td>&nbsp;</td>
					<td colspan="3">
						<table border="0">
							<tr>
								<td align='left' class='label_formulario'>Tipo : </td>	
								<td>
					<!--
					<select name="tipo_documentacion" style="float:left;" class='select_formulario' onChange="documentacion(0);if(document.forms[0].dni_alberguista.value != ''){dni_change();}" title="tipo documentacion">
					-->
					<select name="tipo_documentacion" style="float:left;" class='select_formulario' onChange="documentacion(0);" title="tipo documentacion">
					<?php	 //Al cambiar el valor, si el campo dni no está vacio, comprobará si existe el cliente en la base de datos , y de ser así rellenará automáticamente los campos del formulario?>
						<option value="D" title="DNI"
						<?php
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="D")	{
								echo " selected";
							}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['tipo_documentacion'] == "D"){
									echo " selected "; 
								}
							}
						?>
						>D</option>						
						<option value="P" title="Pasaporte"
						<?php
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="P")	{
								echo " selected";
							}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['tipo_documentacion'] == "P"){
									echo " selected "; 
								}
							}
						?>
						>P</option>
						<option value="C" title="Permiso de Conducir"
						<?php
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="C")	{
								echo " selected";
							}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['tipo_documentacion'] == "C"){
									echo " selected "; 
								}
							}
						?>
						>C</option>
						<option value="I" title="Carta o Documento de Identidad"
						<?php
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="I")	{
								echo " selected";
							}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['tipo_documentacion'] == "I"){
									echo " selected "; 
								}
							}
						?>
						>I</option>
						<option value="N" title="Permiso de residencia Español"
						<?php
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="N")	{
								echo " selected";
							}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['tipo_documentacion'] == "N"){
									echo " selected "; 
								}
							}
						?>
						>N</option>
						<option value="X" title="Permiso de residencia UE"
						<?php
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] =="X")	{
								echo " selected";
							}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['tipo_documentacion'] == "X"){
									echo " selected "; 
								}
							}
						?>
						>X</option>
					</select>
					</td>
					<td>
						<span class="label_formulario" style="padding-left:10px;">D.N.I.  : </span>
						</td>
						<td>
						<?php	 //Al cambiar el valor del DNI, comprobará si existe el cliente en la base de datos , y de ser así rellenará automáticamente los campos del formulario?>
						<input type='text'
						<?php
							//Si el tipo de documentación es 'DNI' , limitará la longitud del campo a 9 caracteres. De lo contrario tendrá una longitud de 30 caracteres.
							if(isset($_POST['tipo_documentacion']) && $_POST['tipo_documentacion'] == "D"){
								echo " maxlength='9' ";
							}else{
								echo " maxlength='30' ";
							}
							if (isset($_POST['dni_alberguista']) && $_POST['dni_alberguista'] != ""){echo $_POST['dni_alberguista'];}
							else{if (isset($_GET['accion']) AND $_SESSION['reserva']['mostrar']){ echo(" value='".$_SESSION['reserva']['dni']."' ");}}
						?>
					 size="19" name='dni_alberguista' class='input_formulario' id="alta_dni_alberguista" style="float:left;" onChange="this.value=this.value.toUpperCase();dni_change();" value='<?php echo $_POST['dni_alberguista'];?>' maxlength="30" onBlur=""></td>	
					 <?php //Botón lupa que muestra la búsqueda de clientes?>
					<td  width="30"><a href="#" id="lupa_alta" onClick="abrir_busqueda();">	
					<img src="../imagenes/botones/lupa.png" style="border:none;width:25px;height:25px;"  alt="Búsqueda de clientes" /></a></div></td>
					</tr>
				</table>
				</td>
				</tr>
				<?php //Campo utilizado en modificaciones, que guarda el DNI anterior?>
				<input type="hidden" name="dni_ant" value="<?php echo $_POST['dni_ant']?>">
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Expedición:</td>
					<td align="left" colspan="2">
					<table> 
						<tr>
							<td>
						<SELECT class='select_formulario'  name="dia_fecha_expedicion" >
							<?php 
							echo "<option value='' selected </option>";
							for($i=1;$i<=31;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == $_POST['dia_fecha_expedicion']){
									echo " selected";
								}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['dia_fecha_expedicion'] == $i){
									echo " selected "; 
								}
							}
								echo ">".$i."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>
						<SELECT class='select_formulario'  name="mes_fecha_expedicion">
							<?php 
							echo "<option value='' selected </option>";
							for($i=1;$i<=12;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == intval($_POST['mes_fecha_expedicion'])){
									echo " selected";
								}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['mes_fecha_expedicion'] == $i){
									echo " selected "; 
								}
							}
								echo ">".$meses[intval($i-1)]."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>
						<SELECT class='select_formulario'  name="anyo_fecha_expedicion">
							<?php 
							echo "<option value='' selected </option>";
							for($i=date("Y");$i>=1900;$i--){
								echo "<option value=\"".($i)."\"";
								if($i == $_POST['anyo_fecha_expedicion']){
									echo " selected";
								}else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['anyo_fecha_expedicion'] == $i){
									echo " selected "; 
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
					<td align='left' colspan="2"><input type='text' name='nombre_alberguista' class='input_formulario' value='<?php 
						if (isset($_POST['nombre_alberguista']) && $_POST['nombre_alberguista'] != ""){ echo $_POST['nombre_alberguista'];}
						else{if (isset($_GET['accion']) AND $_SESSION['reserva']['mostrar']){ echo($_SESSION['reserva']['nombre']);}}
					?>' maxlength="20" onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Primer Apellido:</td>
					<td align='left' colspan="2"><input type='text' name='apellido1_alberguista' class='input_formulario' value='<?php 
						if (isset($_POST['apellido1_alberguista']) AND $_POST['apellido1_alberguista'] != ""){ echo $_POST['apellido1_alberguista'];}
						else{if (isset($_GET['accion']) AND $_SESSION['reserva']['mostrar']){ echo($_SESSION['reserva']['apellido1']);}}
					?>' maxlength="30" onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Segundo Apellido:</td>
					<td align='left' colspan="2"><input type='text' name='apellido2_alberguista' class='input_formulario' value='<?php 
						if (isset($_POST['apellido2_alberguista']) AND $_POST['apellido2_alberguista'] != ""){echo $_POST['apellido2_alberguista'];}
						else{if (isset($_GET['accion']) AND $_SESSION['reserva']['mostrar']){ echo($_SESSION['reserva']['apellido2']);}}
					?>' maxlength="30" onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>País:</td>
					<td align="left" colspan="2">	
					<!-- Creamos un select simple solo con españa, en caso de necesitar más paises la función documentación los creará -->			
						<!--
						<SELECT onChange="paises(0);" class='select_formulario' name="pais_alberguista">	
						<?php // PAISES 
							$result_paises = mysql_query("select * from pais order by Nombre_Pais asc");
							while($fila_paises = mysql_fetch_array($result_paises)){
								echo "<option value=".$fila_paises['Id_Pais']." alt=".$fila_paises['Nombre_Pais']." title=".$fila_paises['Nombre_Pais'];
								if(isset($_POST['pais_alberguista']) && $_POST['pais_alberguista'] != ""){ 
									if( $fila_paises['Id_Pais'] == $_POST['pais_alberguista']){
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
						<SELECT onChange="paises(0);" class='select_formulario' name="pais_alberguista">	
						
						</SELECT>
						<!-- Llamamos a documentación -->
						
						
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Provincia:</td>
					<td align="left" colspan="2">
						<SELECT class='select_formulario' name="provincia_alberguista" 
								<?php  //provincias 
								if (isset($_POST['pais_alberguista']) && $_POST['pais_alberguista']!= "ES"){
									echo " disabled";
								}
								echo ">";

								$result_provincias = mysql_query($sql_provincias);
								echo "<OPTION value=''>(Sólo España)</OPTION>";
						while($fila_provincias = mysql_fetch_array($result_provincias)){
								echo "<option value=".$fila_provincias['Id_Pro'];
								if(isset($_POST['provincia_alberguista']) && $_POST['provincia_alberguista']!=""){
									if($fila_provincias['Id_Pro'] == $_POST['provincia_alberguista']){
										echo " selected ";
									}
								}else{
									if($fila_provincias['Id_Pro'] == $_POST['provincia_alberguista']){
										echo " selected ";
									}else{
										if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['provincia_alberguista'] == $fila_provincias['Id_Pro']){
											echo " selected "; 
										}
									}
								}
								echo ">".$fila_provincias['Nombre_Pro']."</option>";
							}
							?>									
						</SELECT>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Localidad:</td>
					<td align='left' colspan="2"><input type='text' name='localidad_alberguista' class='input_formulario' value='<?php 
						if (isset($_POST['localidad_alberguista']) && $_POST['localidad_alberguista'] != ""){echo $_POST['localidad_alberguista'];}
							else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['localidad_alberguista'] != ""){
									echo $_SESSION['reserva']['localidad_alberguista']; 
								}
							}
					?>' maxlength="150"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align="left" class='label_formulario'>Dirección:</td>
					<td align='left' colspan="2"><input type='text' name='direccion_alberguista' class='input_formulario' value='<?php 
						if (isset($_POST['direccion_alberguista']) && $_POST['direccion_alberguista'] != ""){echo $_POST['direccion_alberguista'];}
							else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['direccion_alberguista'] != ""){
									echo $_SESSION['reserva']['direccion_alberguista']; 
								}
							}
					?>' maxlength="150"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Código Postal:</td>
					<td align='left' colspan="2"><input type='text' name='cp_alberguista' class='input_formulario' value='<?php 
						if (isset($_POST['cp_alberguista']) && $_POST['cp_alberguista'] != ""){echo $_POST['cp_alberguista'];}
							else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['cp_alberguista'] != ""){
									echo $_SESSION['reserva']['cp_alberguista']; 
								}
							}
					?>' maxlength="5"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Nacimiento:</td>
					<td align="left" colspan="2">
					<table> 
						<tr>
							<td>
						<SELECT class='select_formulario'  name="dia_fecha_alberguista">
							<?php 
							echo "<option value='0' selected </option>";
							for($i=1;$i<=31;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == $_POST['dia_fecha_alberguista']){
									echo " selected";
								}else{
										if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['dia_fecha_alberguista'] == $i){
											echo " selected "; 
										}
									}
								echo ">".$i."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>
						<SELECT class='select_formulario'  name="mes_fecha_alberguista">
							<?php 
							echo "<option value='0' selected </option>";
							for($i=1;$i<=12;$i++){
								if($i<10){
									$i = "0".$i;
								}
								echo "<option value=".$i;
								if($i == intval($_POST['mes_fecha_alberguista'])){
									echo " selected";
								}else{
										if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['mes_fecha_alberguista'] == $i){
											echo " selected "; 
										}
									}
								echo ">".$meses[intval($i-1)]."</option>";
							}
							?>
						</SELECT>
						</td>
						<td>						
						<SELECT class='select_formulario'  name="anyo_fecha_alberguista">
							<?php 
							echo "<option value='' selected </option>";
							for($i=date("Y");$i>=1900;$i--){
								echo "<option value=\"".($i)."\"";
								if($i == $_POST['anyo_fecha_alberguista']){
									echo " selected";
								}else{
										if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['anyo_fecha_alberguista'] == $i){
											echo " selected "; 
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
					<td>&nbsp;</td><td align='left' class='label_formulario'>Sexo:						
						<span style='position:relative;top:5px;'>&nbsp;</span>
					</td>
					<td colspan="2"><input type='radio' name='sexo_alberguista' value='M'
					<?php 
					if($_POST['sexo_alberguista'] == 'M'){
						echo " checked";
					}else{
						if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['sexo_alberguista']  == "M"){
							echo " checked "; 
						}
					}

					?>
					><label class="label_formulario">Hombre</label>
						<input type='radio' name='sexo_alberguista' value='F'
						<?php 
						if($_POST['sexo_alberguista'] == 'F'){
							echo " checked";
						}else{
							if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['sexo_alberguista']  == "F"){
								echo " checked "; 
							}
						}
						?>
						><label class="label_formulario">Mujer</label></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:</td>
						<td colspan="2"><input type='text' size='21' name='telefono_alberguista' class='input_formulario' value="<?php 
							if (isset($_POST['telefono_alberguista']) AND $_POST['telefono_alberguista'] != ""){ echo $_POST['telefono_alberguista'];}
							else{if (isset($_GET['accion']) AND $_SESSION['reserva']['mostrar']){ echo($_SESSION['reserva']['telefono']);}}
						?>" maxlength="12"></td>	
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Email:</td>
					<td align='left' class='label_formulario' colspan="2"><input type='text' size='20' name='email_alberguista' class='input_formulario' style="font-weight:normal;" value="<?php 
							if (isset($_POST['email_alberguista']) AND $_POST['email_alberguista'] != ""){ echo $_POST['email_alberguista'];}
							else{if (isset($_GET['accion']) AND $_SESSION['reserva']['mostrar']){ echo($_SESSION['reserva']['email']);}}
						?>" maxlength="100"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' valign="top" class='label_formulario'>Observaciones:</td>
					<td align='left' colspan="2"><textarea name="observaciones_alberguista" class='text_area_formulario' style="height:80px;width:180px;" maxlength="256"><?php 
						if (isset($_POST['observaciones_alberguista']) && $_POST['observaciones_alberguista'] != ""){echo $_POST['observaciones_alberguista'];}
							else{
								if ($_SESSION['reserva']['sw_reserva'] == true && $_SESSION['reserva']['observaciones_alberguista'] != ""){
									echo $_SESSION['reserva']['observaciones_alberguista']; 
								}
							}
					?></textarea></td>
				</tr>
				</table>
			  </span>			  
			</li>
			<li>
			  <b class="b1"></b>
			  <b class="b2"></b>
			  <b class="b3"></b>
			  <b class="b4"></b>
			  <a href="#" <?php 
				if(isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva']) == "true"){
					echo "onclick=\"abrir(2,0);\"";
				}else{
					echo "onclick=\"abrir(2,0);\"";
				}
			  ?>><b class="h2"><font color="#064C87" face="verdana" size="3"><b>Datos de Estancia</b></font></b><br /></a>
			  
			  <span id="lis2" style="display:none">
			   <br><table border="0" align="center">
			   
			   <tr id="tr_id_hab" style="display:none;">
					<td><label class='label_formulario' >Id Habitacion</label></td><td><input type="hidden" readonly class='input_formulario' size="2" name="id_habitacion" value="">
					<?php //Campo oculto que guarda la fecha de calendario anterior. Se utiliza en estancias de varios días en distintas habitaciones?>
					<input type="hidden" name="ant_fecha_calendario" value="
					<?php 
						if(isset($fecha_calendario) && $fecha_calendario != ""){
							echo trim($fecha_calendario);
						}else{
							echo trim(date("Y-m-d"));
							}								   
					?>">
					</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td align="left"><label class='label_formulario' >Fecha Llegada :</label></td>
				<?php 

					//Correccion para que solo se muestren en el select los dias del mes seleccionado

					if (isset($_POST['mes_fecha_llegada_alberguista']) && $_POST['mes_fecha_llegada_alberguista'] != ""){
						$fecha_al = mktime(0,0,0,$_POST['mes_fecha_llegada_alberguista']+1,0,$_POST['anyo_fecha_llegada_alberguista']);
						$dia_limite_al = strftime('%d',$fecha_al);
					}else{
						
						$mes_act = date("m");
						$fecha_al = mktime(0,0,0,$mes_act+1,0,0);
						$dia_limite_al = strftime('%d',$fecha_al);
					}

					//Fin Correccion

					if($_SESSION['reserva']['sw_reserva'] == "true"){
						$fecha_entrada = trim($_SESSION['reserva']['fecha_llegada']);
					}else{
						$fecha_entrada = date("Y-m-d");
					}
					$split = split("-",$fecha_entrada);					
			   ?>
					<input type="hidden" name="ant_dia_fecha_llegada" value="<?php 
						if(isset($_POST['dia_fecha_llegada_alberguista']) && $_POST['dia_fecha_llegada_alberguista'] !=""){
							echo $_POST['dia_fecha_llegada_alberguista'];
						}else{
							echo $split[2];
						}
					?>">
					<input type="hidden" name="ant_mes_fecha_llegada" value="<?php 
						if(isset($_POST['mes_fecha_llegada_alberguista']) && $_POST['mes_fecha_llegada_alberguista'] !=""){
							echo $_POST['mes_fecha_llegada_alberguista'];
						}else{
							echo $split[1];
						}
					?>">
					<input type="hidden" name="ant_anyo_fecha_llegada" value="<?php 
						if(isset($_POST['anyo_fecha_llegada_alberguista']) && $_POST['anyo_fecha_llegada_alberguista'] !=""){
							echo $_POST['anyo_fecha_llegada_alberguista'];
						}else{
							echo $split[0];
						}
					?>">
					
					<td align="left" colspan="2">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="left">
						<SELECT class='select_formulario'  name="dia_fecha_llegada_alberguista"
						<?php if(!isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva']) != "true"){?>	onChange="document.forms[0].sub.value='false';cambiar_dia(document.forms[0].dia_fecha_llegada_alberguista.value,document.forms[0].mes_fecha_llegada_alberguista.value,document.forms[0].anyo_fecha_llegada_alberguista.value,1);"
						<?php }?>
						>
							<?php 
							if(!isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva']) != "true"){
								$sw = "true";
								for($i=1;$i<=$dia_limite_al;$i++){
									if($i<10){
										$i = "0".$i;
									}
									echo "<option value=".$i." ";
									if($i == $_POST['dia_fecha_llegada_alberguista'] && $sw){
										echo " selected";
										$sw = false;
									}else{
										if($i == $split[2] && $sw){
											echo " selected";
										}
									}
									echo ">".$i."</option>";
								}
							}else{
								echo "<option value=\"".$split_fecha_reserva[2]."\" >".$split_fecha_reserva[2];
							}
							?>
						</SELECT>
						</td>
						<td align="left">
						<SELECT class='select_formulario' onChange="document.forms[0].sub.value='false';cambiar_dia(document.forms[0].dia_fecha_llegada_alberguista.value,document.forms[0].mes_fecha_llegada_alberguista.value,document.forms[0].anyo_fecha_llegada_alberguista.value);"  name="mes_fecha_llegada_alberguista">
							<?php 
							if(!	isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva']) != "true"){
								$sw = "true";
								for($i=1;$i<=12;$i++){
									if($i<10){
										$i = "0".$i;
									}
									echo "<option value=".$i;
									if($i == ($_POST['mes_fecha_llegada_alberguista']) && $sw){
										echo " selected";
										$sw = false;
									}else{
										if($i == $split[1] && $sw){
											echo " selected";
										}
									}
									echo ">".$meses[$i-1]."</option>";
								}
							}else{
								echo "<option value=\"".$split_fecha_reserva[1]."\" >".$meses[$split_fecha_reserva[1]-1];
							}
							?>
						</SELECT>
						</td>
						<td align="left">
						<SELECT class='select_formulario' onChange="document.forms[0].sub.value='false';cambiar_dia(document.forms[0].dia_fecha_llegada_alberguista.value,document.forms[0].mes_fecha_llegada_alberguista.value,document.forms[0].anyo_fecha_llegada_alberguista.value);"  name="anyo_fecha_llegada_alberguista">
							<?php 
							if(!	isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva']) != "true"){
								$sw = "true";
								for($i=date("Y");$i<=intval(date("Y"))+1;$i++){
									echo "<option value=\"".($i)."\"";
									if($i == $_POST['anyo_fecha_llegada_alberguista'] && $sw){
										echo " selected";
										$sw = false;
									}else{
										if($i == $split[0] && $sw){
											echo " selected";
										}
									}
									echo ">".$i."</option>";
								}	
							}else{
								echo "<option value=\"".$split_fecha_reserva[0]."\" >".$split_fecha_reserva[0];
							}
							?>
						</SELECT>
						</td>						
						</tr>
					</table>
					</td>
				</tr>
				<tr>
				<td>&nbsp;</td>
					<td align="left">
						<label class='label_formulario' >Pernoctas: </label>
					</td>
					<td align="left" valign="top" style="padding:0px 0px 0px 0px;">
					<table  border="0" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
						<tr>
							<td align="left" style="padding:0px 0px 0px 0px;" colspan="2" class='label_formulario'>
								<?php if(isset($_SESSION['reserva']['sw_reserva']) && $_SESSION['reserva']['sw_reserva'] == "true"){
										echo " ".$_SESSION['reserva']['pernocta_total']." &nbsp <INPUT type='hidden' name='pernocta_alberguista' value='".$_SESSION['reserva']['pernocta_total']."'>";
									}else{
								?>
								<input type="text" align="left" class='input_formulario' onChange="cambio_botones_habitaciones();" name="pernocta_alberguista" style="padding:0px 0px 0px 0px;" size="6" <?php 
									if(isset($_POST['pernocta_alberguista']) && $_POST['pernocta_alberguista']!=""){
										echo "value='".$_POST['pernocta_alberguista']."' ";
									}else{
										 
											echo " value=1 ";
										
									}
									
								?>>
								</td>
								<td align="left">
								<?php //Botones para aumentar o disminuir el número de noches que durará la estancia?>
								<ul style="list-style-type:none;list-style-position:inside;margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;">
									<li style="padding:0px 0px 0px 0px;height:12px;margin:0px 0px 0px 0px;"><img src="../imagenes/botones/flecha_up.jpg" style="border:none;" alt="Aumentar el número de pernoctas en 1" onclick="cambio_camas('+1');" /></li>
									<li style="padding:0px 0px 0px 0px;height:12px;margin:0px 0px 0px 0px;"><img src="../imagenes/botones/flecha_down.jpg" style="border:none;margin:0px 0px 0px 0px;" alt="Disminuir el número de pernoctas en 1" onclick="cambio_camas('-1');" /></li>
								</ul>		
								<?php 	
									}	
								?>
							</td>
						 </tr>
					</table>
					</td>
				</tr>
				<input type='hidden' name='noches_pagadas' value='0'>
				<?php //Solo se muestra el ingreso si es una validacion de reserva
				if(isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva']) != ""){
				?>
				<tr>		
					<td>&nbsp;</td>
						<td><label class='label_formulario'>Ingreso : </label></td>
						<td class='label_formulario'><input type='hidden' size='6' name='ingreso_alberguista' value='<?php echo $_SESSION['reserva']['ingreso'];?>' class='input_formulario'><?php echo $_SESSION['reserva']['ingreso'];?> &nbsp; euros</td>
					</tr>
				<?php }else{ ?><input type='hidden' name='ingreso_alberguista' value='0'> <?php } ?>
					<?php 
					//En caso de que estén activados los servicios, mostrará las diferentes opciones de servicios disponibles
					$servicios_activados = table_exists(servicios,$db);
					if($servicios_activados){
						$sql_servicios = "select Id_Servicios , Descripcion from Tipo_Servicios";
						$result_servicios = mysql_query($sql_servicios);
					?>
					<tr>		
					<td>&nbsp;</td>
						<td><label class='label_formulario'>Servicio : </label></td>
						<td>
							<select name="servicio_alberguista" class="select_formulario"><?php 
						
							while($fila_servicios = mysql_fetch_array($result_servicios))
							{
								echo "<option value=\"".$fila_servicios['Id_Servicios']."\"";
								if(isset($_POST['servicio_alberguista']) && trim($_POST['servicio_alberguista']) != ""){
									if(trim($fila_servicios['Id_Servicios']) == trim($_POST['servicio_alberguista'])){
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
					<?php }?>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;<input type="hidden" name="id_cama"></td>	 					
				</tr>
			</table>
						<div style="text-align:right;height:35px;;width:120px;margin-left:33%;">
							<a href="#"><IMG id="boton_nuevo_habitacion2" alt="Realizar Alta de Alberguista" src="../imagenes/botones-texto/aceptar_fondo_azul.jpg"  align="right" border="0" style="<?php 
								if(!isset($_SESSION['reserva']['sw_reserva']) || trim($_SESSION['reserva']['sw_reserva']) == ""){
									echo "display:none;";
								}else{
									echo "display:block;";
								}
							?>border:none;" onclick="evalua_habitacion();" /></a>
							<?php if(!isset($_SESSION['reserva']['sw_reserva']) || trim($_SESSION['reserva']['sw_reserva']) == ""){?>
								<a href="#"><img id="boton_ver_habitaciones" alt="Mostrar Distribución de Habitaciones" src="../imagenes/botones-texto/verhabitaciones_fondo_azul.jpg" align="right"  style="border:none;" onClick="document.forms[0].sub.value='true';evalua_estancia();" /></a>
							<?php }?>
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
<script>documentacion(0);</script>
		