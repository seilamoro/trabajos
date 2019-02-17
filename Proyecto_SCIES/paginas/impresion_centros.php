<head>

<link rel="StyleSheet" type="text/css" href="../css/css_principal.css"> 


<!-- Título de la página. -->
<title>IMPRIMIR REVISIONES DE CENTRO SELECCIONADO</title>
</head>



<!-- Función JavaScript para imprimir la tabla. -->
<script language = "JavaScript">

	//Función para que salga automáticamente por pantalla la opción de imprimir.
	function imprimir() {
		version = parseInt(navigator.appVersion);
		if (version >= 4) {
			window.print();
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
	$sql = "SELECT * FROM centro WHERE Id_Centro='".$id_cen."'";
	$result = mysql_query($sql);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
		$nombre = $fila['Nombre'];
		$ubicacion = $fila['Direccion'];
		$localidad = $fila['Localidad'];
		$tlfo = $fila['Telefono'];
	}
	
	$f = split("X", $_GET['fechas']);
	$fechas = array();
	for($i=0; $i<count($f)-1; $i++) {
		$fechas[$i] = $f[$i];
	}


	$h = split("X", $_GET['hojas']);
	$hojas = array();
	for($i=0; $i<count($h)-1; $i++) {
		$hojas[$i] = $h[$i];
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


<?php

	if($_GET['elegir']) {
		$mens = "Revisiones Seleccionadas";
	} else {
		$mens = "Últimas Seis Revisiones";
	}

?>



<table width='100%' class='listado1' border="1" style="margin-top:60px; width:100%; margin-bottom:20px;" align="center">


<tr bgcolor='#FFFF99'>
	<th colspan='9' width='100%' style='text-align:center;' class='titulo2'><? echo $nombre; ?></th>
</tr>
<tr>
	<td colspan='9' width='100%' style='text-align:center;' class='titulo3'><? echo $mens; ?></td>
</tr>
<tr>
	<td width='15%' colspan='2'>DIRECCIÓN</td>
	<td width='25%'><? echo $ubicacion; ?></td>
	<th width='60%' rowspan='4' colspan='6' >DATOS DE LA HOJA DE<br>MANTENIMIENTO</th>
</tr>

<tr>
	<td width='15%' colspan='2'>TELÉFONO</td>
	<td width='25%'><? echo $tlfo; ?></td>
</tr>

<tr>
	<td width='15%' colspan='2'>LOCALIDAD</td>
	<td width='25%'><? echo $localidad; ?></td>
</tr>

<tr>
	<td width='15%' colspan='2'>Nº HOJAS</td>
	<td width='25%'><? echo $num_hojas; ?></td>
</tr>

<tr>
	<td width='15%' colspan='2'>ÚLT.REVISIÓN</td>
	<td width='25%'><? echo $fecha_mayor; ?></td>
	<th width='25%' colspan='6'>FECHA</th>
</tr>

<?php

	if(!$_GET['elegir']) {

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

<tr bgcolor='#FFCCCC'> 
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
						echo "<th style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;' width='10%'><img src='../imagenes/iconos/aceptar3.gif'></th>";
					} else if($fila_ok['Correcto'] == 0) {
						echo "<th style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;' width='10%'><img src='../imagenes/iconos/cancelar3.gif'></th>";
					} else {
						echo "<th style='text-indent:0px; font-size:10; text-align:center; letter-spacing:0px;' width='10%'><img src='../imagenes/iconos/no-imagen.gif'></th>";
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



<!-- Se llama a la función imprimir. Abre automáticamente el cuadro de impresión. -->
<script>
	 imprimir();
</script>





<?php

	//Cerramos la base de datos.
	mysql_close();

?>