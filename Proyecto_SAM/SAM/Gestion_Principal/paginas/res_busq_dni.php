<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">

		<script language="Javascript">
				function check_inicio(){
					//document.form_alta.criterio_dni.focus();				
				}
				function pasa_datos(){
					var num;
					for(i=0;i<document.getElementsByName('select_cliente').length;i++){
						if(document.getElementsByName('select_cliente')[i].checked){
							num = document.getElementsByName('select_cliente')[i].value;
							break;
						}
					}					
					window.opener.document.getElementById('asignacion').style.visibility="visible";	
					if (window.opener.document.form_dist.camas_restantes.value>=0){
					}else{
						window.opener.document.form_dist.camas_restantes.value=1;
					}
					window.opener.document.form_alta.dni_new_reserva.value = document.getElementsByName("dni"+String(num))[0].value;
					var nombre = document.getElementsByName("nombre"+String(num))[0].value;
					window.opener.document.form_alta.nombre_new_reserva.value = nombre;
					var apellido1 = document.getElementsByName("apellido1"+String(num))[0].value;
					window.opener.document.form_alta.apellido1_new_reserva.value = apellido1;
					var apellido2 = document.getElementsByName("apellido2"+num)[0].value;
					window.opener.document.form_alta.apellido2_new_reserva.value = apellido2;
					var telefono = document.getElementsByName("telefono"+num)[0].value;
					window.opener.document.form_alta.telefono_new_reserva.value = telefono;
					var email = document.getElementsByName("email"+num)[0].value;
					window.opener.document.form_alta.email_new_reserva.value = email;
					var obs = document.getElementsByName("observaciones"+num)[0].value;
					
					window.close();
				}	

				function buscar_todo(formu){
					var bien;
					var element;
					var oper;
					var dni=document.form_busq.criterio_dni.value.toLowerCase();
					var nombre=document.form_busq.criterio_nombre.value.toLowerCase();
					var apellido1=document.form_busq.criterio_ape1.value.toLowerCase();
					var apellido2=document.form_busq.criterio_ape2.value.toLowerCase();
					for (var i=0;i<max_filas;i++){
						bien=true;
						oper="element=document.form_busq.dni"+i+".value";
						eval (oper);
						element=element.toLowerCase();
						for (var j=0;j<dni.length;j++){
							if (element.charAt(j)!=dni.charAt(j)){
								bien=false;
							}
						}
						oper="element=document.form_busq.nombre"+i+".value";
						eval (oper);
						element=element.toLowerCase();
						for (var j=0;j<nombre.length;j++){
							if (element.charAt(j)!=nombre.charAt(j)){
								bien=false;
							}
						}
						oper="element=document.form_busq.apellido1"+i+".value";
						eval (oper);
						element=element.toLowerCase();
						for (var j=0;j<apellido1.length;j++){
							if (element.charAt(j)!=apellido1.charAt(j)){
								bien=false;
							}
						}
						oper="element=document.form_busq.apellido2"+i+".value";
						eval (oper);
						element=element.toLowerCase();
						for (var j=0;j<apellido2.length;j++){
							if (element.charAt(j)!=apellido2.charAt(j)){
								bien=false;
							}
						}
						if (!bien){
							if(document.getElementsByName('select_cliente')[i].checked){
								document.getElementsByName('select_cliente')[i].checked=false;
							}
							document.getElementsByName('boton_aceptar')[0].style.display='none';
							document.getElementById(i).style.display='none';
						}else{
							document.getElementById(i).style.display='block';
						}
					}
				}
		</script>
		<style>
			body{
				font-family:Verdana;
				color:#064C87;
				font-size:12px;
				font-weight:bold;
			}
			table{
				font-family:Verdana;
				color:#064C87;
				font-size:12px;
				font-weight:bold;
			}
			table input{
				border:none;
				background:transparent;
				font-family:Verdana;
				color:#064C87;
				font-size:12px;
			}

div.tableContainer {
	clear: both;

	overflow-y: scroll;
}

div.tableContainer table {
	float: left;
}

thead.fixedHeader th {
  	font-size: 11px;
	background-color: #064C87;
	border-left: 1px solid #F4FCFF;
	border-right: 1px solid #F4FCFF;
	padding-left: 0px;
	padding-right: 0px;
	font-weight: bold;
	color: #FFFFFF;
}

thead.fixedHeader th div{
  	cursor: pointer;
}

thead.fixedHeader tr {
	position: relative;
}

tbody.scrollContent {
	background-color: #F4FCFF;
}

tbody.scrollContent td, tbody.scrollContent tr.normalRow td {
	border-right: 1px solid #CCC;
	border-left: 1px solid #CCC;
	padding-left: 0px;
	padding-right: 0px;
}

		</style>
		<?
		//Comprobamos que se tienen los permisos necesarios para acceder a la pagina
@ SESSION_START();

function truncar($texto, $long){
	$orig=0;
	$trozos = array();
	if (strlen($texto) > $long){
		$cachos = intval(strlen($texto)/$long);
		if ((strlen($texto)%$long) > 0){$cachos++;}
		
		for ($i=1;$i<$cachos+1;$i++){
			$trozos[$i] = substr($texto, $orig, $long);
			$orig = $orig + $long;
		}
		$resultado = '';
		for ($i=1;$i<count($trozos);$i++){
			$resultado = $resultado.$trozos[$i]."-";
		}
		$resultado = $resultado.$trozos[$i];
		return $resultado;
	}else{
		return $texto;
	}
}

if ($_SESSION['permisoReservas'] != true){
	echo "
		<div style='text-align:center;font-size:20px;color:red;margin:100px;'>
			No tiene permisos para acceder a esta página
		</div>
	";
	exit();
}
			@$db= mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
			if($db){
				
		?>		
	</HEAD>

<BODY style="background-image:url('../../imagenes/fondo_busqueda_grande.jpg');">
		<center><br><br>
			<div id="criterios">
			<form name='form_busq' action="per_busq_dni.php" method="POST">
			<div style='position:absolute;left:270px;top:580px;'>
				<img src="../../imagenes/botones-texto/aceptar.jpg" style="cursor:none;display:none;" name="boton_aceptar" value="aceptar" onClick="pasa_datos();">
				</div>
			<input type="hidden" name="form_destino" value=
				<?
				if(isset($_GET['form']) && $_GET['form']!=""){
					echo $_GET['form'];
				}else{
					if(isset($_POST['form_destino']) && $_POST['form_destino']!=""){ 
						echo $_POST['form_destino'];
					}else{
						
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
					$sql = "select * from cliente where DNI_Cl like '".$_POST['criterio_dni']."%' and Nombre_Cl like '".$_POST['criterio_nombre']."%' and Apellido1_Cl like '".$_POST['criterio_ape1']."%'  and Apellido2_Cl like '".$_POST['criterio_ape2']."%' and DNI_Cl IN (SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida <=  '".date("Y-m-d")."' ) AND DNI_Cl NOT IN (SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida >  '".date("Y-m-d")."' )";
					$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
				}else{
					$sql = "SELECT  * FROM cliente ";
					$sql_incidencias = "select DNI_Inc from incidencias";
				}
				//echo $sql;
				if($result = mysql_query($sql)){
				//echo $sql."<br>";
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
						</td>
					</tbody>
				</table>
				<br><br>
		</center>
		</form>
		<script>check_inicio();</script>
	</BODY>
	
	<?
				}else{
					echo "No se ha podido conectar a la base de datos.<br><br>";
					echo "<a href=\"#\" onClick=\"window.close();\" style=\"font-weight:bold;text-decoration:underline;color:#064C87;\">Cerrar</a>";
				}
			}
	?>
</HTML>
