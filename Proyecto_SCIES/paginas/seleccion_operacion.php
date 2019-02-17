<link rel="stylesheet" type="text/css" href="../css/css_principal.css">
<link rel="stylesheet" type="text/css" href="../css/principal.css">
<body style ="background-image:url(../imagenes/fondos/fondo_paginas.jpg);" >



<script>

	function enviar1() {
		index = document.getElementById("operaciones").selectedIndex;
		
		window.opener.document.getElementById("opera_").value = document.getElementById("operaciones").options[index].value;
		
		window.opener.document.viene_de_ventana.submit();
		window.close();
	}

	function env2() {
		index = document.getElementById("operaciones2").selectedIndex;
		
		window.opener.document.getElementById("opera2_").value = document.getElementById("operaciones2").options[index].value;
		
		window.opener.document.viene_de_ventana2.submit();
		window.close();
	}

</script>



<?php

	//Conexión a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");
	


	if($_GET['modo'] == 'quitar') {

		$ids_array = array();
		$ids_partidos = split("X", $_GET['ids']);
		for($i=0; $i<count($ids_partidos)-1; $i++) {
			$ids_array[] = $ids_partidos[$i];
		}

		$ids_des = array();
		for($i=0; $i<count($ids_array); $i++) {
			$sql = "SELECT * FROM operacion_correctiva WHERE Id_operacion_corr = '".$ids_array[$i]."'";
			$result = mysql_query($sql);
			for ($m=0;$m<mysql_num_rows($result);$m++){
				$fila = mysql_fetch_array($result);
				$ids_des[] = $fila['Descripcion'];
			}
		}

	?>
	
	<table width='100%'>
	<form name='enviar' action='principal.php?pag=rellenar_correctiva.php' method='POST'>

	<tr align='center'>
		<td colspan='2'>
			<select name='operaciones'>
		
	<?

				for($t=0; $t<count($ids_des); $t++) {
					$id_d = substr($ids_des[$t],0,80);
					echo "<option value='".$ids_array[$t]."'>".$id_d."";
				}

	?>
			</select>
		</td>
	</tr>
	
	<tr align='center'>
		<td>
			<br><br><br>
			<INPUT TYPE="button" VALUE="Quitar" class="boton_big" onClick="enviar1();" style='cursor:hand;' title='Quitar operación correctiva de la hoja'>
		</td>
		<td>
			<br><br><br>
			<INPUT TYPE="button" VALUE="Cerrar" class="boton_big" onClick="window.close();" style='cursor:hand;' title='Cerrar la ventana'>
		</td>
	</tr>

</form>
</table>

<?

	} else {

		$ids_array = array();
		$ids_partidos = split("X", $_GET['ids']);
		for($i=0; $i<count($ids_partidos)-1; $i++) {
			$ids_array[] = $ids_partidos[$i];
		}

		$array_op = array();
		$sql = "SELECT * FROM operacion_correctiva";
		$result = mysql_query($sql);
		for ($m=0;$m<mysql_num_rows($result);$m++){
			$fila = mysql_fetch_array($result);
			$id_op = $fila['Id_operacion_corr'];
			$esta = "no";
			for($v=0; $v<count($ids_array); $v++) {
				if($id_op == $ids_array[$v]) {
					$esta = "si";
				}
			}
			if($esta == "no") {
				$array_op[] = $id_op;
			}
		}

		
		$array_op_des = array();
		for($i=0; $i<count($array_op); $i++) {
			$sql = "SELECT * FROM operacion_correctiva WHERE Id_operacion_corr = '".$array_op[$i]."'";
			$result = mysql_query($sql);
			for ($m=0;$m<mysql_num_rows($result);$m++){
				$fila = mysql_fetch_array($result);
				$array_op_des[] = $fila['Descripcion'];
			}
		}

?>



<table width='100%'>
<form name='enviar2' action='principal.php?pag=rellenar_correctiva.php' method='POST'>

	<tr align='center'>
		<td colspan='2'>
			<select name='operaciones2'>
		<?

				for($t=0; $t<count($array_op_des); $t++) {
					$id_d = substr($array_op_des[$t],0,80);
					echo "<option value='".$array_op[$t]."'>".$id_d."";
				}

		?>	
			</select>
		</td>
	</tr>

	
	<tr align='center'>
		<td>
			<br><br><br>
			<INPUT TYPE="button" VALUE="Añadir" class="boton_big" onClick="env2();" style='cursor:hand;' title='Añadir operación correctiva a la hoja'>
		</td>
		<td>
			<br><br><br>
			<INPUT TYPE="button" VALUE="Cerrar" class="boton_big" onClick="window.close();" style='cursor:hand;' title='Cerrar la ventana'>
		</td>
	</tr>

</form>
</table>

<?

	}

?>





<?php

	//Cerramos la base de datos.
	mysql_close();

?>

</body>