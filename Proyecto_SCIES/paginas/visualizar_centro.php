<script language = "JavaScript">

	//Comprueba que se ha escrito un número de hoja. Luego, abre la ventana de impresión de hoja.
	//En ese momento se guardan los datos provisionales en la base de datos.
	function imprimir(normal) {
		var cen = document.getElementById('centro').value; 
		var fech = document.getElementById('fechas').value; 
		var hoj = document.getElementById('hojas').value; 
		if(normal == 'si') {
			window.open("./paginas/impresion_centros.php?centro="+cen+"&fechas="+fech+"&hojas="+hoj, "_blank", "resizable=yes,scrollbars=yes,location=no,status=no,menubar=no");
		} else {						window.open("./paginas/impresion_centros.php?centro="+cen+"&fechas="+fech+"&hojas="+hoj+"&elegir=si", "_blank", "resizable=yes,scrollbars=yes,location=no,status=no,menubar=no");
		}
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


	$id_cen = $_GET['centro'];


	$sql = "SELECT * FROM centro WHERE Id_Centro = '".$id_cen."'";
	$result = mysql_query($sql);
	for ($m=0; $m<mysql_num_rows($result); $m++){
		$fila = mysql_fetch_array($result);
		$nombre = $fila['Nombre'];
		$direccion = $fila['Direccion'];
		$tlfo = $fila['Telefono'];
		$localidad = $fila['Localidad'];
	}

	$fecha_mayor = "0000-00-00";
	$sql = "SELECT * FROM hoja_correctiva WHERE Id_Centro = '".$id_cen."'";
	$result = mysql_query($sql);
	for ($m=0; $m<mysql_num_rows($result); $m++){
		$fila = mysql_fetch_array($result);
		if($fila['fecha'] > $fecha_mayor) {
			$fecha_mayor = $fila['fecha'];
		}
	}
	$num_hojas = mysql_num_rows($result);
	if($fecha_mayor == "0000-00-00") {
		$fecha_mayor = "Sin revisiones";
	} else {
		$fecha_partida = split("-", $fecha_mayor);
		$fecha_mayor = $fecha_partida[1]."-".$fecha_partida[0];
	}

?>

<div class="pagina">
<div class="listado1" >
			
<div class="listado" width="100%">	
<div class="titulo1"><?php echo $nombre; ?></div>					
	
				
<?php

	if($_GET['elegir']) {
		$mens = "Revisiones Seleccionadas";
	} else {
		$mens = "Últimas Seis Revisiones";
	}

?>


<table border='0' width='100%' align='center' class='opcion_triple'>
<thead>
<tr>
	<th class='titulo2' colspan='9' width='100%'><? echo $mens; ?></th>
</tr>
</thead>


<thead>
<table border='1' class='opcion_triple'>

<tr>
	<th class='titulo3_centro' width='15%' style="text-indent:0px; font-size:10; letter-spacing:0px;" colspan='2'>DIRECCIÓN</th>
	<th class='titulo3_centro' width='25%' style="text-indent:0px; font-size:10; letter-spacing:0px;"><? echo $direccion; ?></th>
	<th class='titulo3_centro' width='60%' rowspan='4' colspan='6' style='text-indent:0px; text-align:center;'>DATOS DE LA HOJA DE<br>MANTENIMIENTO</th>
</tr>

<tr>
	<th class='titulo3_centro' width='15%' style="text-indent:0px; font-size:10; letter-spacing:0px;" colspan='2'>TELÉFONO</th>
	<th class='titulo3_centro' width='25%' style="text-indent:0px; font-size:10; letter-spacing:0px;"><? echo $tlfo; ?></th>
</tr>

<tr>
	<th class='titulo3_centro' width='15%' style="text-indent:0px; font-size:10; letter-spacing:0px;" colspan='2'>LOCALIDAD</th>
	<th class='titulo3_centro' width='25%' style="text-indent:0px; font-size:10; letter-spacing:0px;"><? echo $localidad; ?></th>
</tr>

<tr>
	<th class='titulo3_centro' width='15%' style="text-indent:0px; font-size:10; letter-spacing:0px;" colspan='2'>NÚMERO DE HOJAS</th>
	<th class='titulo3_centro' width='25%' style="text-indent:0px; font-size:10; letter-spacing:0px;"><? echo $num_hojas; ?></th>
</tr>

<tr>
	<th class='titulo3_centro' width='15%' style="text-indent:0px; font-size:10; letter-spacing:0px;" colspan='2'>ÚLTIMA REVISIÓN</th>
	<th class='titulo3_centro' width='25%' style="text-indent:0px; font-size:10; letter-spacing:0px;"><? echo $fecha_mayor; ?></th>
	<th class='titulo3_centro' width='25%' style='text-indent:0px; text-align:center;' colspan='6'>FECHA</th>
</tr>

</thead>
</table>



<table border='0' class='opcion_triple'>
<thead>

<?php

	if($_GET['elegir']) {
		$normal = 'no';
		$hojas_sel = array();

		for($i=0; $i<count($_POST['op_seleccionadas']); $i++) {
			$hojas_sel[] = $_POST['op_seleccionadas'][$i];
		}

		$fechas = array();
		$hojas = array();

		//echo "HOJAS PARAM:".count($hojas_sel)."<br>";
		for($r=0; $r<count($hojas_sel); $r++) {
			$hojas[] = $hojas_sel[$r];

			$s = "SELECT * FROM hoja_correctiva WHERE Num_Hoja = '".$hojas_sel[$r]."'";
			$res = mysql_query($s);
			for($f=0; $f<mysql_num_rows($res); $f++) {
				$fila = mysql_fetch_array($res);
				$fecha = $fila['fecha'];
				$fecha_par = split("-", $fecha);
				$fe = $fecha_par[1]."-".$fecha_par[0];
			}
			$fechas[] = $fe;
		}


	} else {
		$normal = 'si';
	
		$s = "SELECT * FROM hoja_correctiva WHERE Id_Centro ='".$id_cen."' ORDER BY fecha";
		$r = mysql_query($s);
		$num = mysql_num_rows($r);
		$reg = $num - 6;
		if($reg < 0) {
			$reg = 0;
		}

		$s = "SELECT * FROM hoja_correctiva WHERE Id_Centro ='".$id_cen."' ORDER BY fecha LIMIT ".$reg.",6";
		$r = mysql_query($s);
		$fechas = array();
		$hojas = array();
		for ($i=0; $i<mysql_num_rows($r); $i++){
			$f = mysql_fetch_array($r);
			$fechas[$i] = $f['fecha'];
			$hojas[$i] = $f['Num_Hoja'];
		}
	}
		
	$fechas_seguido = "";
	for($i=0; $i<count($fechas); $i++) {
		$fechas_seguido = $fechas_seguido.$fechas[$i]."X";
	}
	$hojas_seguido = "";
	for($i=0; $i<count($hojas); $i++) {
		$hojas_seguido = $hojas_seguido.$hojas[$i]."X";
	}
	
?>

<tr> 
	<th class='titulo2' width='5%' style="text-indent:0px; font-size:10; letter-spacing:0px; text-align:center">Nº</th>
	<th class='titulo2' width='35%' colspan='2' style="text-indent:0px; font-size:10; letter-spacing:0px;">OPERACIÓN</th>

<?php

	$columnas_quedan = 6 - count($fechas);
	for($n=0; $n<count($fechas); $n++) {
		$fecha = split("-", $fechas[$n]);
		$fecha_ver = $fecha[1]."-".$fecha[0];
		echo "<th class='titulo2' width='10%' style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;'>".$fecha_ver."<br>Hoja ".$hojas[$n]."</th>";
	}
	for($i=0; $i<$columnas_quedan; $i++) {
		echo "<th class='titulo2' width='10%' style='text-indent:0px; font-size:10; letter-spacing:0px; text-align:center'>&nbsp;</th>";
	}

?>

</tr>

</thead>
</table>



<table border='1' class='opcion_triple'>
<thead>

<?php

	$sql = "SELECT * FROM operacion_correctiva";
	$result = mysql_query($sql);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
		echo "<tr>";
		echo "<th width='5%' style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;'>".$fila['Id_operacion_corr']."</th>";
		echo "<th width='35%' colspan='2' style='text-indent:0px; font-size:10; text-align:left; letter-spacing:0px;'>".$fila['Descripcion']."</th>";

		for($n=0; $n<count($fechas); $n++) {
			$sql_ok = "SELECT * FROM lineas_hoja WHERE Id_operacion_corr = '".$fila['Id_operacion_corr']."' AND Num_Hoja = '".$hojas[$n]."'";
			$result_ok = mysql_query($sql_ok);
			if(mysql_num_rows($result_ok) != 0) {
				for ($z=0; $z<mysql_num_rows($result_ok); $z++){
					$fila_ok = mysql_fetch_array($result_ok);
					if($fila_ok['Correcto'] == 1) {
						echo "<th style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;' width='10%'><img src='./imagenes/iconos/aceptar3.gif'></th>";
					} else if($fila_ok['Correcto'] == 0) {
						echo "<th style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;' width='10%'><img src='./imagenes/iconos/cancelar3.gif'></th>";
					} else {
						echo "<th style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;' width='10%'><img src='./imagenes/iconos/no-imagen.gif'></th>";
					}
				}
			} else {
				echo "<th style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;' width='10%'>NO</th>";
			}
		}

		for($i=0; $i<$columnas_quedan; $i++) {
			echo "<td width='10%'>&nbsp;</td>";
		}
			
		echo "</tr>";
	}

?>

</thead>
</table>

</thead>
</table>

</div>

</div>
</div>



<table width="73%" border='0' style='margin-top:20px;'>
	<tr>
		<td width='90%' align='right'>
		<INPUT TYPE="button" NAME="imprimir" VALUE="Imprimir" class="boton_big" onClick="imprimir('<? echo $normal; ?>');" style='cursor:hand;' title='Ver todos los centros'>
		</td>	

		<td width='10%' align='right'>
		<INPUT TYPE="button" NAME="seleccionar" VALUE="Seleccionar" class="boton_big" onClick="location.href='principal?pag=elegir_hojas.php&centro=<? echo $_GET['centro']; ?>';" style='cursor:hand;' title='Seleccionar otras hojas'>	
		</td>	

		<td width='10%' align='right'>
		<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal?pag=ver_centros.php';" style='cursor:hand;' title='Ver todas las hojas'>	
		</td>
	</tr>
</table>	
		


<input type='hidden' name='centro' id='centro' value='<? echo $id_cen; ?>'>
<input type='hidden' name='fechas' id='fechas' value='<? echo $fechas_seguido; ?>'>
<input type='hidden' name='hojas' id='hojas' value='<? echo $hojas_seguido; ?>'>





<?php

	//Cerramos la base de datos.
	mysql_close();

?>