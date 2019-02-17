<script language="javascript">

	//Envía el centro seleccionado.
	function envia_centro() {
		document.getElementById("hojas").options[0].selected = true;
		document.enviar_centro.submit();
	}

	//Coloca el foco en el campo para introducir el número de hoja.
	function foco() {
		document.getElementById("ir_hoja").focus();
	}
	
	//Visualiza la página 1 del listado. Se ejecuta la primera vez que se entra a la página.
	function listado_pag1() {
		document.getElementById('pagina').value = 1;
		document.listado.submit();
	}

	//Envía el número de página.
	function envio(t) {
		if(document.getElementById("ir_hoja").value.length == 0) {
			alert("Debe introducir un número de hoja");
			return 0;
		}
		if (!/^([0-9])*$/.test(document.getElementById('ir_hoja').value)) {
			alert("El valor " + document.getElementById('ir_hoja').value + " no es un número");
			return 0;
		}
		if(t == "mod") {
			document.getElementById("tipo").value = "mod";
		} else {
			document.getElementById("tipo").value = "ver";
		}
		document.getElementById("num_hoja").value = document.getElementById("ir_hoja").value;
		document.getElementById("viene_de_correctivo").value = "si";
		document.valor_hoja.submit();
	}

	//Envía el número de página teniendo en cuenta si se quiere ver o modificar la hoja.
	function envio2(valor, t) {
		if(t == "mod") {
			document.getElementById("tipo").value = "mod";
		} else {
			document.getElementById("tipo").value = "ver";
		}
		document.getElementById("num_hoja").value = valor;
		document.valor_hoja.submit();
	}
	
</script>



<head>
	<!-- Título de la página. -->
	<title>OPERACIONES CORRECTIVAS</title>
</head>



<?php

	//Conexión a la base de datos 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");


	
	if($_GET['instal'] == 'si') {
		$viene_de_instalacion = 'si';
	}
	
	
	if($_POST['enviado'] == 'si') {
		
		if($_POST['eliminar'] == 'si') {
			$s = "SELECT * FROM hoja_correctiva WHERE Num_Hoja='".$_POST['numero']."'";
			$r = mysql_query($s);
			$num = mysql_num_rows($r);
			if($num == 0) {

?>

				<script>
					alert("No se puede eliminar la hoja porque no existe");
				</script>  

<?php

			} else {
			
				$sql = "DELETE FROM hoja_correctiva WHERE Num_Hoja='".$_POST['numero']."'";
				$result = mysql_query($sql);

				$sql2 = "DELETE FROM lineas_hoja WHERE Num_Hoja='".$_POST['numero']."'";
				$result2 = mysql_query($sql2); 
			
?>
			
				<script>
					alert("Hoja eliminada con éxito");
				</script>  

<?php

			}

		} else {
			$num_hoja = $_POST['numero'];
			$id_centro = $_POST['centro_'];
			$empresa = $_POST['empresa'];
			$operario = $_POST['operario'];
			$obs_generales = $_POST['obs_generales'];
			$tlfo = $_POST['tlfo'];
			$fecha_provisional = split("-", $_POST['fech']);
			$fecha = $fecha_provisional[1]."-".$fecha_provisional[0]."-01";
			
			$sql = "DELETE FROM hoja_correctiva WHERE Num_Hoja = '".$num_hoja."'";
			$result = mysql_query($sql);

			$sql = "DELETE FROM lineas_hoja WHERE Num_Hoja = '".$num_hoja."'";
			$result = mysql_query($sql);
			
			$sql = "INSERT INTO hoja_correctiva(Num_Hoja, Id_Centro, Empresa, Operario, Observaciones_generales, Telefono, fecha) VALUES ('$num_hoja', '$id_centro', '$empresa', '$operario', '$obs_generales', '$tlfo', '$fecha')";
			$result = mysql_query($sql);	
			
			
			$num_operaciones = $_POST['n_op'];
			for($i=1;$i<$num_operaciones+1;$i++) {
				$casilla = "casilla".$i;
				$correcto = $_POST[$casilla];
				$observaciones = "observaciones".$i;
				$obs = $_POST[$observaciones];
				$operacion = "id_op".$i;
				$op = $_POST[$operacion];

				
				$sql = "INSERT INTO lineas_hoja(Num_Hoja, Id_operacion_corr, Correcto, Observaciones) VALUES ('$num_hoja', '$op', '$correcto', '$obs')";
				$result = mysql_query($sql);
			} 
					
?>

		<script>
			alert("Hoja actualizada con éxito");
		</script> 

<?php

		}
	} else {

		if($_POST['centro']) {
			$cen =  $_POST['centro'];
		} else {
			$cen = -1;
		}
		if($_POST['hojas']) {
			$hoj =  $_POST['hojas'];
			$distancia = 30;
		} else {
			$hoj = -1;
			$distancia = 158;
		}
	}



	if($_GET['num']) {
		$cen = $_GET['num']; 
	}

	if( ($distancia != 30) && ($distancia != 158) ) {
		$distancia = 158;
	}
	
?>

<!-- Bloque superior. -->
<form name='enviar_centro' method='POST' action='principal.php?pag=mtto_correctivo.php'>

<div class="pagina" id="primer_div">
<div class="listado1" >

<div class="listado" width="100%">	
<div class="titulo1">Búsqueda por Centro y Hoja</div>	

<table border="0" class='opcion_triple' width='60%' align="center">
<thead>
<tr>
	<th width='50%' class="titulo2" style="text-align:center;text-indent:0px;">Centro</th>	
	<th width='50%' class="titulo2" style="text-align:center;text-indent:0px;">Hojas</th>
</tr>
</thead>

<thead>
<tr>
	<td width='50%' class='titulo3'>
		<input type='hidden' id='centro' value='<? echo $cen; ?>'>
		<select name='centro' onChange='envia_centro();'>
		<option value='0' selected></option>

		<?php

			$sql = "SELECT * FROM centro";
			$result = mysql_query($sql);
			for ($l=1;$l<mysql_num_rows($result)+1;$l++){
				$fila = mysql_fetch_array($result); ?>
				<option value='<?echo $fila['Id_Centro'];?>'<?if($fila['Id_Centro'] == $cen) { print('selected'); }?>><?echo $fila['Nombre'];?><?
			}
			echo "</select>";

			if( ($cen != -1) && ($cen != "") ) {
				echo "<td width='50%' class='titulo3'>";
				echo "<select name='hojas' id='hojas' onChange='submit();'>";
				echo "<option value='0' selected>";
				$sql = "SELECT * FROM hoja_correctiva WHERE Id_Centro = '".$cen."' ORDER BY Num_Hoja";
				$result = mysql_query($sql);
				for ($l=1;$l<mysql_num_rows($result)+1;$l++){
					$fila = mysql_fetch_array($result); 
					
		?>

					<option value='<? echo $fila['Num_Hoja']; ?>'<?if($hoj == $fila['Num_Hoja']) { print('selected'); }?>><? echo $fila['Num_Hoja']; ?>&nbsp;&nbsp;
			
		<?  
				
				} echo "</select></td>";

				if( ($hoj != -1) && ($hoj != "") ) {
					$sql2 = "SELECT * FROM hoja_correctiva WHERE Num_Hoja = '".$hoj."'";
					$result2 = mysql_query($sql2);
					for ($m=1;$m<mysql_num_rows($result2)+1;$m++) {
						$fila2 = mysql_fetch_array($result2);
					}
					echo "<tr>";
					echo "<th class='opcion_triple' colspan='2' style='text-indent:0px; font:14px Georgia; letter-spacing:2px;'>";
					//echo "<font size='4' type='Georgia' letter-spacing='2px'>";
					echo "Número de hoja: <font size='1' type='Georgia'>".$hoj."</font><br>";
					
					$sql3 = "SELECT * FROM centro WHERE Id_Centro = '".$fila2['Id_Centro']."'";
					$result3 = mysql_query($sql3);
					for ($k=1;$k<mysql_num_rows($result3)+1;$k++) {
						$fila3 = mysql_fetch_array($result3);
					}
					echo "Centro: <font size='1' type='Georgia'>".$fila3['Nombre']."</font><br>";

					echo "Teléfono: <font size='1' type='Georgia'>".$fila2['Telefono']."</font><br>";
					
					$f = split("-", $fila2['fecha']);
					echo "Fecha: <font size='1' type='Georgia'>".$f[1]."-".$f[0]."</font><br>";
					echo "</th>";
					echo "</tr>";


					echo "<tr>";
					echo "<td colspan='2'>";
					
		?>
					
					<div class="opcion_boton">		
					<table width="100%" border='0'>
						<input type='hidden' id='hoja' name='hoja'>
						<input type='hidden' id='id_cen' value='<? echo $id_cen; ?>'>
						<input type='hidden' id='fecha' value='<? echo $fecha; ?>'>
						<input type='hidden' id='op_sel' value='<? for ($i=0;$i<count($op_seleccionadas);$i++) {
						echo $op_seleccionadas[$i]."-"; } ?>'>
		
						<tr>
							<td width='50%' align='center'>
							<INPUT TYPE="button" NAME="modificar" VALUE="Modificar" class="boton_big" onClick="envio2('<? echo $hoj; ?>', 'mod');" style='cursor:hand;' title='Modificar la hoja seleccionada'>
							</td>
				
							<td width='50%' align='center'>
							<INPUT TYPE="button" NAME="ver" VALUE="Ver" class="boton_big" onClick="envio2('<? echo $hoj; ?>', 'ver');" style='cursor:hand;' title='Ver detalles de la hoja seleccionada'>	
							</td>	
				
						</tr>
	
					</table>	
					</div>

		<?php
						
					echo "</td>";
					echo "</tr>";
				} 
				
			} else {
				echo "<td width='50%' class='titulo3'>";
				echo "<select name='hojas' id='hojas' onChange='submit();'>";
				echo "<option value='0' selected>";
				echo "</td>";
			}

		?>

	</td>
</tr>
</thead>

</table>
</div>

</div>
</div>

</form>




<!-- Bloque inferior. Tiene position absolute. -->
<div class="pagina" style="margin-top:<? echo $distancia; ?>px; display:block" id="segundo_div">
<div class="listado1" >

<div class="listado" width="100%">	
<div class="titulo1">Búsqueda por Número de Hoja</div>	

<table border="0" class='opcion_triple' width='30%' align="left">

<thead>
<tr>
	<th align='right' class='titulo2' colspan='4'>Número de Hoja</th>
</thead>
</tr>

<thead>			
<tr>
	<th class="titulo3" colspan='4' width='50%'>Introduzca el número de hoja&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' size='10' id='ir_hoja'></th>
</tr>
</thead>

<thead>
<tr>
	<td colspan='3'>
	<div class="opcion_boton" style="display:block">		
	<table width="100%" border='0'>
		<tr>
			<td width='50%' align='center'>
			<INPUT TYPE="button" NAME="modificar" VALUE="Modificar" class="boton_big" onClick='envio("mod");' style='cursor:hand;' title='Modificar la hoja seleccionada'>
			</td>
				
			<td width='50%' align='center'>
			<INPUT TYPE="button" NAME="ver" VALUE="Ver" class="boton_big" onClick='envio("ver");' style='cursor:hand;' title='Ver detalles de la hoja seleccionada'>	
			</td>	
		</tr>
	</table>	
	</div>
	</td>
</tr>
</thead>

</table>
</div>

</div>
</div>













<form name='valor_hoja' action='principal.php?pag=rellenar_correctiva.php' method='POST'>
	<input type='hidden' id='num_hoja' name='num_hoja'>
	<input type='hidden' name='tipo' id='tipo'>
	<input type='hidden' name='viene_de_correctivo' id='viene_de_correctivo'>
</form>


<form name='listado' method='POST' action='?pag=listado_hojas.php'>
	<input type='hidden' id='pagina' name='pagina'>
</form>




<?php

	if($viene_de_instalacion == 'si') {
	
?>

<table width="73%" border='0' style='margin-top:10px; display:block;'>
	<tr>
		<td width='85%'>&nbsp;</td>
		<td width='5%' align='center'>
		<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal?pag=instalaciones.php&id=<? echo $cen; ?>'" style='cursor:hand;' title='Volver atrás'>
		</td>
		<td width='5%' align='center'>
		<INPUT TYPE="button" NAME="ver_centros" VALUE="Ver Centros" class="boton_big" onClick="location.href='principal?pag=ver_centros.php'" style='cursor:hand;' title='Ver todos los centros'>
		</td>
				
		<td width='5%' align='center'>
		<INPUT TYPE="button" NAME="ver_hojas" VALUE="Ver Hojas" class="boton_big" onClick="location.href='principal?pag=listado_hojas.php';" style='cursor:hand;' title='Ver todas las hojas'>	
		</td>	
	</tr>
</table>	


<?php

	} else {

?>

<table width="73%" border='0' style='margin-top:5px; display:block;' align='center'>
	<tr>
		<td width='90%'>&nbsp;</td>
		<td width='5%' align='center'>
		<INPUT TYPE="button" NAME="ver_centros" VALUE="Ver Centros" class="boton_big" onClick="location.href='principal?pag=ver_centros.php'" style='cursor:hand;' title='Ver todos los centros'>
		</td>
				
		<td width='5%' align='center'>
		<INPUT TYPE="button" NAME="ver_hojas" VALUE="Ver Hojas" class="boton_big" onClick="location.href='principal?pag=listado_hojas.php';" style='cursor:hand;' title='Ver todas las hojas'>	
		</td>	
	</tr>
</table>	

<?php

	}

?>



<script>
	foco();
</script>


<?php

	//Cerramos la base de datos.
	mysql_close();

?>