<? SESSION_START();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
<?php
	
	//Cogemos los valores del nombre del grupo y la fecha de llegada, y los almacenamos en 
	//variables de sesi�n, ya que al meter una letra en un campo de texto los valores GET
	//se pierden.  $_SESSION['ff'] tiene la fecha de llegada y $_SESSION['gg'] tiene el nombre
	//del grupo. Ambos valores constituyen la clave primaria de componentes_grupo.
	if( ($_GET['f']) && ($_GET['g']) ) {
		$_SESSION['ff'] = $_GET['f'];
		$_SESSION['gg'] = $_GET['g'];
		if( ($_SESSION['ff'] == 'no') && ($_SESSION['gg'] == 'no') ) {
			$_SESSION['normal'] = 'si';
		} else {
			$_SESSION['normal'] = 'no';
		}
	} else {
		if($_SESSION['normal'] == 'si') {
			$_SESSION['normal'] = 'si';
		} else {
			$_SESSION['normal'] = 'no';
		}
	}	

	if($_POST['campo_actual']) {
		$campo = $_POST['campo_actual'];
	}
	else {
		$campo = "dni";
	}

	$cadena = "javascript:setSelectionRange(document.forms[0].criterio_".$campo.", document.forms[0].criterio_".$campo.".value.length, document.forms[0].criterio_".$campo.".value.length);";
?>

<!-- Estilos necesarios para el cuadro de b�squeda. -->
<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">

		<script language="Javascript">							

				//Calcula lo que hay escrito en cada campo de texto para luego poder posicionar el cursor.
				function setSelectionRange(input, selectionStart, selectionEnd){
					if (input.setSelectionRange){
						input.focus();
						input.setSelectionRange(selectionStart, selectionEnd);
					} else if (input.createTextRange){
						var range = input.createTextRange();
						range.collapse(true);
						range.moveEnd('character', selectionEnd);
						range.moveStart('character', selectionStart);
						range.select();
					}
				}
		
				function setSelectionRange(input, selectionStart, selectionEnd){
					if (input.setSelectionRange){
						input.focus();
						input.setSelectionRange(selectionStart, selectionEnd);
					}
					else if (input.createTextRange){
						var range = input.createTextRange();
						range.collapse(true);
						range.moveEnd('character', selectionEnd);
						range.moveStart('character', selectionStart);
						range.select();
					}
				}

				function enviar(evt){						
					document.forms[0].submit();
				}
				
				function pasa_datos(){
					var num;
					for(i=0;i<document.getElementsByName('select_cliente').length;i++){
						if(document.getElementsByName('select_cliente')[i].checked){
							num = document.getElementsByName('select_cliente')[i].value;
							break;
						}
					}					
							
					window.opener.document.forms[0].dni_peregrino.value = document.getElementsByName("dni"+String(num))[0].value;
					window.opener.document.forms[0].dni_ant_peregrino.value = document.getElementsByName("dni"+String(num))[0].value;
					window.opener.document.forms[0].test_dni.value = 1;
					var td= 0;
					for(td=0;td< window.opener.document.forms[0].tipo_documentacion.length;td++){
						if(window.opener.document.forms[0].tipo_documentacion.options[td].value == document.getElementsByName("tipo_doc"+String(num))[0].value){
							window.opener.document.forms[0].tipo_documentacion.options[td].selected = true;
							break;
						}
					}
					var fecha_expedicion = document.getElementsByName("fecha_exp"+num)[0].value;
					fecha_expedicion =fecha_expedicion.split("-");
					for(j=0;j<window.opener.document.forms[0].anyo_fecha_expedicion.length;j++)	{
						if(window.opener.document.forms[0].anyo_fecha_expedicion.options[j].value == fecha_expedicion[0])	{
							window.opener.document.forms[0].anyo_fecha_expedicion.options[j].selected = true;
							break;
						}
						
					}
					for(j=0;j<window.opener.document.forms[0].mes_fecha_expedicion.length;j++)	{
						if(window.opener.document.forms[0].mes_fecha_expedicion.options[j].value == fecha_expedicion[1])	{
							window.opener.document.forms[0].mes_fecha_expedicion.options[j].selected = true;
							break;
						}
						
					}
					for(j=0;j<window.opener.document.forms[0].dia_fecha_expedicion.length;j++)	{
						if(window.opener.document.forms[0].dia_fecha_expedicion.options[j].value == fecha_expedicion[2])	{
							window.opener.document.forms[0].dia_fecha_expedicion.options[j].selected = true;
							break;
						}
						
					}
					var nombre = document.getElementsByName("nombre"+String(num))[0].value;
					window.opener.document.forms[0].nombre_peregrino.value = nombre;
					var apellido1 = document.getElementsByName("apellido1"+String(num))[0].value;
					window.opener.document.forms[0].apellido1_peregrino.value = apellido1;
					var apellido2 = document.getElementsByName("apellido2"+num)[0].value;
					window.opener.document.forms[0].apellido2_peregrino.value = apellido2;
					var direccion = document.getElementsByName("direccion"+num)[0].value;
					window.opener.document.forms[0].direccion_peregrino.value = direccion;
					var cp = document.getElementsByName("cp"+num)[0].value;
					window.opener.document.forms[0].cp_peregrino.value = cp;			
					var localidad = document.getElementsByName("localidad"+num)[0].value;				
					window.opener.document.forms[0].localidad_peregrino.value = localidad;
					var telefono = document.getElementsByName("telefono"+num)[0].value;
					window.opener.document.forms[0].telefono_peregrino.value = telefono;
					var email = document.getElementsByName("email"+num)[0].value;
					window.opener.document.forms[0].email_peregrino.value = email;
					var obs = document.getElementsByName("observaciones"+num)[0].value;
					window.opener.document.forms[0].observaciones_peregrino.value = obs;
					var pais = document.getElementsByName("pais"+num)[0].value;				
					for(j=0;j<window.opener.document.forms[0].pais_peregrino.length;j++)	{
						if(window.opener.document.forms[0].pais_peregrino.options[j].value.toUpperCase() == pais.toUpperCase())	{
							window.opener.document.forms[0].pais_peregrino.options[j].selected = true;
							break;
						}
						
					}
					var provincia = document.getElementsByName("provincia"+num)[0].value;
					for(j=0;j<window.opener.document.forms[0].provincia_peregrino.length;j++)	{
						if(window.opener.document.forms[0].provincia_peregrino.options[j].value.toUpperCase() == provincia.toUpperCase())	{
							window.opener.document.forms[0].provincia_peregrino.options[j].selected = true;
							break;
						}
						
					}

					var fecha_inicial = document.getElementsByName("fecha_nac"+num)[0].value;
					var fecha_array = fecha_inicial.split("-");
					for(j=0;j<window.opener.document.forms[0].anyo_fecha_peregrino.length;j++)	{
						if(window.opener.document.forms[0].anyo_fecha_peregrino.options[j].value == fecha_array[0])	{
							window.opener.document.forms[0].anyo_fecha_peregrino.options[j].selected = true;
							break;
						}
						
					}
					for(j=0;j<window.opener.document.forms[0].mes_fecha_peregrino.length;j++)	{
						if(window.opener.document.forms[0].mes_fecha_peregrino.options[j].value == fecha_array[1])	{
							window.opener.document.forms[0].mes_fecha_peregrino.options[j].selected = true;
							break;
						}
						
					}
					for(j=0;j<window.opener.document.forms[0].dia_fecha_peregrino.length;j++)	{
						if(window.opener.document.forms[0].dia_fecha_peregrino.options[j].value == fecha_array[2])	{
							window.opener.document.forms[0].dia_fecha_peregrino.options[j].selected = true;
							break;
						}
						
					}
					var sexo = document.getElementsByName("sexo"+num)[0].value;
					for(j=0;j<window.opener.document.forms[0].sexo_peregrino.length;j++)	
					{
						if(window.opener.document.forms[0].sexo_peregrino[j].value == sexo)
						{
						 	window.opener.document.forms[0].sexo_peregrino[j].checked = true;
							break;
						}
						
					}
					window.opener.document.forms[0].sexo_peregrino.value = sexo;
					window.opener.document.forms[0].existe.value = "true";
					// al parecer esto hacia desaparecer el calendario.
					//window.opener.document.forms[0].submit();
					window.close();
				}					
				function caja(valor){
					document.getElementsByName('criterio_dni')[0].value = document.getElementsByName('criterio_dni')[0].value;
					document.getElementsByName('campo_actual')[0].value = valor;
				}
				
		</script>
	
		<?
		@$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']); //Conexi�n a la base de datos
			if($db){
				
		?>		
	</HEAD>

<BODY onload="<?php echo $cadena ?>" style="background-image:url('../../imagenes/fondo_busqueda_grande.jpg');">	
<center><br><br>
			<div id="criterios">
			<form action="per_busq_dni.php" method="POST">			
			<input type="hidden" name="campo_actual" >
			<input type="hidden" name="form_destino" value=
				<?
				if(isset($_GET['form']) && $_GET['form']!=""){
					echo $_GET['form'];
				}else{
					if(isset($_POST['form_destino']) && $_POST['form_destino']!=""){ 
						echo $_POST['form_destino'];
					}
				}				
				?>>				
				<br><br>				
				<?
				if(isset($_GET['form']) && $_GET['form']!=""){
				?>
				
				<?
				}
				?>
			<br>
			
			</div>
			<?
				 mysql_select_db($_SESSION['conexion']['db']);
				if(isset($_POST['criterio_text'])){
					$sql = "SELECT  * FROM cliente where cliente.DNI_Cl like '".trim($_POST['criterio_dni'])."%' and Nombre_Cl like '".trim($_POST['criterio_nombre'])."%' and Apellido1_Cl like '".trim($_POST['criterio_ape1'])."%'  and Apellido2_Cl like '".trim($_POST['criterio_ape2'])."%'";					
					//$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
				}else{
					$sql = "SELECT  * FROM cliente WHERE cliente.DNI_Cl like '".trim($_POST['criterio_dni'])."%' and Nombre_Cl like '".trim($_POST['criterio_nombre'])."%' and Apellido1_Cl like '".trim($_POST['criterio_ape1'])."%'  and Apellido2_Cl like '".trim($_POST['criterio_ape2'])."%'";
					//$sql_incidencias = "select  DNI_Inc from incidencias";
				}
				//echo "<br>".$sql."<br>";
				if($result = mysql_query($sql)){				
			?>

			<div style='padding-left:5px;'>
				<table border="0" style="width:620px;" cellspacing='0' cellpadding='0'>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td align='left'>D.N.I.</td>
						<td align='left'>Nombre</td>
						<td align='left'>Primer Apellido</td>
						<td align='left'>Segundo Apellido</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td style='padding-left:0px;'>
							<input type="text" size="13" style="border:solid 1px;padding:0px;margin:0px;" name="criterio_dni" onKeyUp="buscar_todo(this.form)" value="<?
								if(isset($_POST['criterio_dni'])){
									echo $_POST['criterio_dni'];
								}?>">			
						</td>
						<td style='padding-left:0px;'>
							<input type="text" size="15" style="border:solid 1px;padding:0px;margin:0px;" name="criterio_nombre" onKeyUp="buscar_todo(this.form)" value="<?
								if(isset($_POST['criterio_nombre'])){
									echo $_POST['criterio_nombre'];
								}?>">
						</td>
						<td style='padding-left:0px;'>
							<input type="text" size="16" style="border:solid 1px;padding:0px;margin:0px;" name="criterio_ape1" onKeyUp="buscar_todo(this.form)" value="<?
								if(isset($_POST['criterio_ape1'])){
									echo $_POST['criterio_ape1'];
								}?>">
						</td>
						<td style='padding-left:0px;'>
							<input type="text" size="16" style="border:solid 1px;padding:0px;margin:0px;" name="criterio_ape2" onKeyUp="buscar_todo(this.form)" value="<?
							if(isset($_POST['criterio_ape2'])){
								echo $_POST['criterio_ape2'];
							}?>">
						</td>
						<td>&nbsp;</td>
					</tr>
					<tbody style="overflow:auto;">
						<td colspan="6">
							<div class="tableContainer" style="height:400px;width:612px;" >
							<table width='100%' border=0>
						<?
							
							for($i=0;$i<mysql_num_rows($result);$i++){
									$fila = mysql_fetch_array($result);
										echo "<tr>";										    
											echo "<td valign='top'>
													<input value=".$i." type=\"radio\" name=\"select_cliente\" 
														onClick=\"document.getElementsByName('boton_aceptar')[0].style.display='block';\">
													</td>";
											$num_dni = intval(strlen($fila['DNI_Cl'])/15) + 1;		
											echo "<td valign='top'>												
													<TEXTAREA class='noborde' Name=\"dni".$i."\" 
													rows=\"".$num_dni."\" cols=\"13\" disabled >".$fila['DNI_Cl']."</TEXTAREA>											
												  </td>";
											$num_nom = intval(strlen($fila['Nombre_Cl'])/15) + 1;											
											echo "<td valign='top'>
													<TEXTAREA class='noborde' Name=\"nombre".$i."\" 
													rows=\"".$num_nom."\" cols=\"13\" disabled >".$fila['Nombre_Cl']."</TEXTAREA>											
												  </td>";
											$num_ape1 = intval(strlen($fila['Apellido1_Cl'])/15) + 1;
											echo "<td valign='top'>
													<TEXTAREA class='noborde' Name=\"apellido1".$i."\" 
													rows=\"".$num_ape1."\" cols=\"13\" disabled >".$fila['Apellido1_Cl']."</TEXTAREA>";																						
											echo "</td>";
											$num_ape2 = intval(strlen($fila['Apellido2_Cl'])/15) + 1;
											echo "<td valign='top'>
												<TEXTAREA class='noborde' Name=\"apellido2".$i."\" 
													rows=\"".$num_ape2."\" cols=\"13\" disabled >".$fila['Apellido2_Cl']."</TEXTAREA>											
												  </td>";
												  
										// Si tiene incidencias, lo marc�mos.
										$sql_incidencias = "select  DNI_Inc from incidencias where DNI_Inc='".$fila['DNI_Cl']."';";
										
										$res = mysql_query($sql_incidencias);																		
									
										if( (mysql_num_rows($res))!=0 )
											{
												echo "<td>
														<img src=\"../../imagenes/botones/alerta.gif\" 
															alt=\"Ha provocado alguna incidencia con anterioridad.\" 
															title=\"Ha provocado alguna incidencia con anterioridad.\" height=\"25px\" width=\"25px;\" />
													  </td>";									
											}
										else 
											{
												echo"<td>
														<img src=\"../../imagenes/botones/No_alerta.gif\" height=\"25px\" width=\"25px;\" />
													 </td>";
											}
											
									
										echo "</tr>";
										echo "<input type=\"hidden\" name=\"direccion".$i."\" value=\"".$fila['Direccion_Cl']."\">";
										echo "<input type=\"hidden\" name=\"cp".$i."\" value=\"".$fila['CP_Cl']."\">";
										echo "<input type=\"hidden\" name=\"localidad".$i."\" value=\"".$fila['Localidad_Cl']."\">";
										echo "<input type=\"hidden\" name=\"provincia".$i."\" value=\"".$fila['Id_Pro']."\">";
										echo "<input type=\"hidden\" name=\"pais".$i."\" value=\"".$fila['Id_Pais']."\">";								
										echo "<input type=\"hidden\" name=\"fecha_nac".$i."\" value=\"".$fila['Fecha_Nacimiento_Cl']."\">";
										echo "<input type=\"hidden\" name=\"sexo".$i."\" value=\"".$fila['Sexo_Cl']."\">";
										echo "<input type=\"hidden\" name=\"telefono".$i."\" value=\"".$fila['Tfno_Cl']."\">";
										echo "<input type=\"hidden\" name=\"email".$i."\" value=\"".$fila['Email_Cl']."\">";
										echo "<input type=\"hidden\" name=\"observaciones".$i."\" value=\"".$fila['Observaciones_Cl']."\">";
										echo "<input type=\"hidden\" name=\"tipo_doc".$i."\" value=\"".$fila['Tipo_documentacion']."\">";
										echo "<input type=\"hidden\" name=\"fecha_exp".$i."\" value=\"".$fila['Fecha_Expedicion']."\">";
								}
								mysql_close($db);
							?>
						</table>
						</div>
						</td>
					</tr>
				</table>
					
						
				<br>
					<img src="../../imagenes/botones-texto/aceptar.jpg" style="cursor:pointer;display:none;" title="Seleccionar cliente"
				     name="boton_aceptar" value="aceptar" onClick="pasa_datos();">
			</div>
		</center>
		</form>
	</BODY>
	
	<?
				}else{
				    //echo "<br>".$sql."<br>";
					echo "No se ha podido conectar a la base de datos.<br><br>";
					echo "<a href=\"#\" onClick=\"window.close();\" style=\"font-weight:bold;text-decoration:underline;color:#064C87;\">Cerrar</a>";
				}
			}
	?>
</HTML>
<?	
	/*
	if(!isset($_POST['criterio_dni'])){
		echo "<script>document.forms[0].criterio_dni.value ='';
						document.forms[0].criterio_nombre.value ='';
						document.forms[0].criterio_ape1.value ='';
						document.forms[0].criterio_ape2.value ='';
						document.forms[0].criterio_dni.value = window.opener.document.forms[0].dni_peregrino.value;
						document.forms[0].criterio_nombre.value = window.opener.document.forms[0].nombre_peregrino.value;
						document.forms[0].criterio_ape1.value = window.opener.document.forms[0].apellido1_peregrino.value;
						document.forms[0].criterio_ape2.value = window.opener.document.forms[0].apellido2_peregrino.value;
						document.forms[0].submit();
					
					</script>";
	}
	*/

?>