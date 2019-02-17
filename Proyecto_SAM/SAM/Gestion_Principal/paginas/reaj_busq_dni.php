<? SESSION_START();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

<?php

     //Pone el cursos al final de la linea tras introducir una nueva letra en algún campo de texto.
	if($_POST['campo_actual']) {
		$campo = $_POST['campo_actual'];
	}
	else {
		$campo = "dni";
	}

	$cadena = "javascript:setSelectionRange(document.forms[0].criterio_".$campo.", document.forms[0].criterio_".$campo.".value.length, document.forms[0].criterio_".$campo.".value.length);";
?>



<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">


<?php

	function parteCadenas($cadena, $num) {
	$nom = $cadena;
	$nom_partido = "";
	
	//Separa las palabras distintas de la línea
	$array_nom = split(" ", $nom);
	for($u=0; $u<count($array_nom); $u++) {
		if(strlen($array_nom[$u]) > $num) {
			$palabras_largas = 'si';
		} else {
			$palabras_largas = 'no';
		}
	}

	if($palabras_largas == 'si') {
	for($b=0; $b<count($array_nom); $b++) {
		$cont_act = 0;
		//Separa por guiones
		$array_nom_i = split("-", $array_nom[$b]);
		if(count($array_nom_i) > 1) {
			$guiones = 'si';
		} else {
			$guiones = 'no';
		}
		for($j=0; $j<count($array_nom_i); $j++) {
			$cont_act = $cont_act +1;
					
			$resto = strlen($array_nom_i[$j]) % $num;
			$cociente = (strlen($array_nom_i[$j]) - $resto) / $num;
			
			
			if($guiones == 'no') {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					$nom_partido = $nom_partido. " ".$array_nom_i[$j];
				} 

			} else {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					if($nom_partido == "") {
						$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
					} else {
						if($cont_act == count($array_nom_i)) {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j];
						} else {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
						}
					}
				} 
			}
		}
	} 
	return $nom_partido;
	} else {
		return $cadena;
	}
} 

?>

<script language="Javascript">

//submit del formulario actual
function enviar(evt){						
	document.forms[0].submit();
}

//pone los focos en criterio_dni o criterio_nombre
function check_inicio(){
	document.forms[0].criterio_dni.focus();
}

function foco_nom() {
	document.forms[0].criterio_nombre.focus();
}


//Calcula lo que hay escrito en cada campo de texto para luego poder posicionar el cursor.

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
range.select();}
}


// Función que pasa los datos de la página de búsqueda de clientes al formulario de venta de carnets reaj
function pasa_datos(){
	var num;
	for(i=0;i<document.getElementsByName('select_cliente').length;i++){
		if(document.getElementsByName('select_cliente')[i].checked){
			num = document.getElementsByName('select_cliente')[i].value;
			break;
		}
	}	
	
							
	window.opener.document.ventas.DNI.value = document.getElementsByName("dni"+String(num))[0].value;

	var nombre = document.getElementsByName("nombre"+String(num))[0].value;
	window.opener.document.ventas.Nombre.value = nombre;
	var apellido1 = document.getElementsByName("apellido1"+String(num))[0].value;
	window.opener.document.ventas.Apellido1.value = apellido1;
	var apellido2 = document.getElementsByName("apellido2"+num)[0].value;
	window.opener.document.ventas.Apellido2.value = apellido2;
	
					window.close();
				}	

//Conserva los datos en los campos de texto mientras se escribe.
				function caja(valor){
					document.getElementsByName('criterio_dni')[0].value = document.getElementsByName('criterio_dni')[0].value;
					document.getElementsByName('campo_actual')[0].value = valor;

				}

			</script>
	

		<TITLE> Búsqueda de Clientes </TITLE>
		
		<?	
		 @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']); //Conexión a la base de datos
			if($db) {
		?>		
	</HEAD>

<BODY onload="<?php echo $cadena ?>" style="background-image:url('../../imagenes/fondo_busqueda_grande.jpg');">
		<center>
			<div id="criterios"><br><br>
			<form action="reaj_busq_dni.php" method="POST">
				<input type="hidden" name="form_destino" value="<?
				if(isset($_GET['form']) && $_GET['form']!=""){
					echo $_GET['form'];
				}else{
					if(isset($_POST['form_destino']) && $_POST['form_destino']!=""){ 
						echo $_POST['form_destino'];
					}
				}				
				?>">		
				
				<br><br><br>
			</div>
			<?
			 mysql_select_db($_SESSION['conexion']['db']);
				
				//crear las select según sea el criterio de búsqueda
				
				if(isset($_POST['criterio_dni']) && $_POST['criterio_dni'] != ""){
					$sql = "select * from cliente where DNI_Cl like '".$_POST['criterio_dni']."%' and Nombre_Cl like '".$_POST['criterio_nombre']."%' and Apellido1_Cl like '".$_POST['criterio_ape1']."%'  and Apellido2_Cl like '".$_POST['criterio_ape2']."%'"; 
					//and DNI_Cl IN (SELECT DNI_Cl FROM pernocta WHERE fecha_Salida <=  '".date("Y-m-d")."' UNION SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida <=  '".date("Y-m-d")."') AND DNI_Cl NOT IN (SELECT DNI_Cl FROM pernocta WHERE fecha_Salida >  '".date("Y-m-d")."' UNION SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida >  '".date("Y-m-d")."')";
					$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
				}else{
					$sql = "SELECT * FROM cliente WHERE Nombre_Cl like '".$_POST['criterio_nombre']."%' and Apellido1_Cl like '".$_POST['criterio_ape1']."%'  and Apellido2_Cl like '".$_POST['criterio_ape2']."%'"; 
					//and DNI_Cl IN (SELECT DNI_Cl FROM pernocta WHERE fecha_Salida <=  '".date("Y-m-d")."' UNION SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida <=  '".date("Y-m-d")."') AND DNI_Cl NOT IN (SELECT DNI_Cl FROM pernocta WHERE fecha_Salida >  '".date("Y-m-d")."' UNION SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida >  '".date("Y-m-d")."' )";
					$sql_incidencias = "select  DNI_Inc from incidencias";
				}		
		
				/*echo $sql_incidencias."<br>";
				echo $sql."<BR>";*/
				if($result = mysql_query($sql)){

                 //Listado de Clientes
			?>
<div id='listado'>
<table  border='0'  style="width:620px;" cellspacing='0' cellpadding='0'>
		<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td align='left'>D.N.I.</td>
						<td align='left'>Nombre</td>
						<td align='left'>Primer Apellido</td>
						<td align='left'>Segundo Apellido</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						
					</tr>			
<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td align='left' width='21%'>
							<input type="text" size="15" style="border:solid 1px;" name="criterio_dni" onKeyUp="enviar();" onFocus="caja('dni');" value="<?
								if(isset($_POST['criterio_dni'])){
									echo $_POST['criterio_dni'];
								}?>">			
						</td>
						<td align='left' width='22%'>
							<input type="text" size="16" style="border:solid 1px;" name="criterio_nombre" onKeyUp="enviar();" onFocus="caja('nombre');" value="<?
								if(isset($_POST['criterio_nombre'])){
									echo $_POST['criterio_nombre'];
								}?>">
						</td>
						<td align='left' width='22%'>
							<input type="text" size="16" style="border:solid 1px;" name="criterio_ape1" onKeyUp="enviar();" onFocus="caja('ape1');" value="<?
								if(isset($_POST['criterio_ape1'])){
									echo $_POST['criterio_ape1'];
								}?>">
						</td>
						<td align='left' width='22%'>
							<input type="text" size="16" style="border:solid 1px;" name="criterio_ape2" onKeyUp="enviar();" onFocus="caja('ape2');" value="<?
							if(isset($_POST['criterio_ape2'])){
								echo $_POST['criterio_ape2'];
							}?>">
						</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<br><br>
						</td>
					</tr>
									
				    <tr>
						<td colspan=6>
				
				<style>			
				 
					textarea.noborde
					{
					    background-color: transparent;
						scrollbar-face-color: transparent;
						scrollbar-highlight-color: transparent;
						scrollbar-3dlight-color: transparent;
						scrollbar-darkshadow-color: transparent;
						scrollbar-shadow-color: transparent;
						scrollbar-arrow-color: transparent;
						scrollbar-track-color: transparent;
						border:0px;
						overflow: hidden;
				    }
				</style>
					
					<div id="listado_inc" class="tableContainer" style="height:400px;width:620px;">
					<table border='0' class="scrollTable" width='100%'>
					<tbody style="overflow:auto;">
			
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
												  
										// Si tiene incidencias, lo marcámos.
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
							
						?>
					
						</table>
						</div>
						</td>
					</tr>
				</table>

				<img src="../../imagenes/botones-texto/aceptar.jpg" style="cursor:hand;display:none;" name="boton_aceptar" value="aceptar" onClick="pasa_datos();">
			</div>
	
		</form>
		  <!-- <script>check_inicio();</script>  -->
	
	
	<?
				}else{
					echo "No se ha podido conectar a la base de datos.<br><br>";
					echo "<a href=\"#\" onClick=\"window.close();\" style=\"font-weight:bold;text-decoration:underline;color:#064C87;\">Cerrar</a>";
				}
			}else{
					echo "No se ha podido conectar a la base de datos.<br><br>";
					echo "<a href=\"#\" onClick=\"window.close();\" style=\"font-weight:bold;text-decoration:underline;color:#064C87;\">Cerrar</a>";
				}
	?>
</HTML>
<?	
	if(!isset($_POST['criterio_dni'])){
		echo "<script>document.forms[0].criterio_dni.value ='';
						document.forms[0].criterio_nombre.value ='';
						document.forms[0].criterio_ape1.value ='';
						document.forms[0].criterio_ape2.value ='';
						document.forms[0].criterio_dni.value = window.opener.document.forms[0].DNI.value;
						document.forms[0].criterio_nombre.value = window.opener.document.forms[0].Nombre.value;
						document.forms[0].criterio_ape1.value = window.opener.document.forms[0].Apellido1.value;
						document.forms[0].criterio_ape2.value = window.opener.document.forms[0].Apellido2.value;
						document.forms[0].submit();
					</script>";
	}

?>
