<?PHP SESSION_START(); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<script language="Javascript">
				function check_inicio(){
					//document.formulario.criterio_dni.focus();				
				}
				
				// Función que pasa los datos seleccionados a los campos de texto de la ventana origen
				function pasa_datos(){
					var num;
					for(i=0;i<document.getElementsByName('select_cliente').length;i++){
						if(document.getElementsByName('select_cliente')[i].checked){
							num = document.getElementsByName('select_cliente')[i].value;
							break;
						}
					}					
					//window.opener.document.getElementById('asignacion').style.visibility="visible";	
					window.opener.document.formulario.dni_factura.value = document.getElementsByName("dni"+String(num))[0].value;
					var nombre = document.getElementsByName("nombre"+String(num))[0].value;
					window.opener.document.formulario.nombre_factura.value = nombre;
					var apellido1 = document.getElementsByName("apellido1"+String(num))[0].value;
					window.opener.document.formulario.apellido1_factura.value = apellido1;
					var apellido2 = document.getElementsByName("apellido2"+num)[0].value;
					window.opener.document.formulario.apellido2_factura.value = apellido2;
					
					window.close();
				}
					
				// Función que conserva los datos en los campos de texto mientras se escribe
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
		
		<style type="text/css">
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
			@ $db = MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
		
	
			if($db){
				
		?>		
	</HEAD>

<BODY style="background-image:url('../../imagenes/fondo_busqueda_grande.jpg');">
		<center><br><br>
			<div id="criterios">
			<form name='form_busq' action="per_busq_dni.php" method="POST">
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
				MYSQL_SELECT_DB($_SESSION['conexion']['db']);

				if(isset($_POST['criterio_text'])){
					$sql = "SELECT * FROM componentes_grupo WHERE Nombre_Gr LIKE '".$_GET['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_GET['Fecha_Llegada']."' AND DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%'  and Apellido2 like '".$_POST['criterio_ape2']."%';";
					$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
				}else{
					$sql = "SELECT * FROM componentes_grupo WHERE Nombre_Gr LIKE '".$_GET['Nombre_Gr']."' AND Fecha_Llegada LIKE '".$_GET['Fecha_Llegada']."';";
					$sql_incidencias = "select DNI_Inc from incidencias";
				}
				
				if($result = mysql_query($sql)){
				//echo $sql."<br>";

			?>

			<div>	
				<table border=0>
					<thead>
					<tr>
						<th width='5%'>&nbsp;</th>
						<th style='text-align:left;padding:0px;width:20%;'>D.N.I.</th>
						<th style='text-align:left;padding:0px;width:24%;'>Nombre</th>
						<th style='text-align:left;padding:0px;width:27%;'>1<sup>er</sup> Apellido</th>
						<th style='text-align:left;padding:0px;width:28%;'>2<sup>o</sup> Apellido</th>
						<th width='5%'>&nbsp;</th>
					</tr>
					<tr>
						<th>&nbsp;</th>
						<th style="text-align:left;">
							<input type="text" size="10" style="border:solid 1px;" name="criterio_dni" onKeyUp="buscar_todo(this.form)" value="<?
								if(isset($_POST['criterio_dni'])){
									echo $_POST['criterio_dni'];
								}?>">			
						</th>
						<th style="text-align:left;">
							<input type="text" size="10" style="border:solid 1px;" name="criterio_nombre" onKeyUp="buscar_todo(this.form)" value="<?
								if(isset($_POST['criterio_nombre'])){
									echo $_POST['criterio_nombre'];
								}?>">
						</th>
						<th style="text-align:left;">
							<input type="text" size="14" style="border:solid 1px;" name="criterio_ape1" onKeyUp="buscar_todo(this.form)" value="<?
								if(isset($_POST['criterio_ape1'])){
									echo $_POST['criterio_ape1'];
								}?>">
						</th>
						<th style="text-align:left;">
							<input type="text" size="14" style="border:solid 1px;" name="criterio_ape2" onKeyUp="buscar_todo(this.form)" value="<?
							if(isset($_POST['criterio_ape2'])){
								echo $_POST['criterio_ape2'];
							}?>">
						</th>
						<th width='6%'>&nbsp;</th>
					</tr>
					</thead>
					<tbody style="overflow:auto;">
						
<?
				if (MYSQL_NUM_ROWS($result) == 0) {
?>

				<tr><td></td><td colspan="5" align="center">No existen datos de componentes para esta estancia de grupo.</td></tr>
<?PHP
				}
?>
						<?
							$filas=0;
							for($i=0;$i<mysql_num_rows($result);$i++){
								$fila = mysql_fetch_array($result);										
									echo "<tr id ='".$i."'>";
									echo "<td><input value=".$i." style='width:15px;text-align:right;' type=\"radio\" name=\"select_cliente\" onClick=\"document.getElementsByName('boton_aceptar')[0].style.display='block';\"></td>";
									echo "<td><input  style='width:75px;' type=text disabled size=\"12\" name=\"dni".$i."\" class=\"discrete_text\" value=\"".$fila['DNI']."\"></td>";
									echo "<td><input style='width:75px;' size=\"12\" type=text disabled name=\"nombre".$i."\" class=\"discrete_text\" value=\"".$fila['Nombre']."\"></td>";
									echo "<td><input type=text size=\"12\" disabled name=\"apellido1".$i."\" class=\"discrete_text\" value=\"".$fila['Apellido1']."\"></td>";
									echo "<td><input type=text size=\"12\" disabled name=\"apellido2".$i."\" class=\"discrete_text\" value=\"".$fila['Apellido2']."\"></td>";

									$result_inci = mysql_query($sql_incidencias);
									while($fila_inci = mysql_fetch_array($result_inci)){
										if($fila_inci['DNI_Inc'] == $fila['DNI_Cl']){
											echo "<td><img src=\"../../imagenes/botones/alerta.gif\" alt=\"Ha provocado alguna incidencia con anterioridad.\" title=\"Ha provocado alguna incidencia con anterioridad.\" height=\"25px\" width=\"25px;\" /></td>";
											break;
										}
									}
									echo "</tr>";
									$filas++;
							}
						?>
					</tbody>
				</table>
				<br><br><center>
				<img src="../../imagenes/botones-texto/aceptar.jpg" style="cursor:none;display:none;" name="boton_aceptar" value="aceptar" onClick="pasa_datos();"></center>
			</div>
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

<?
echo "<script> var max_filas=".$filas."; </script>";
?>