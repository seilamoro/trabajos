<link rel="StyleSheet" type="text/css" href="../css/css_principal.css"> 


<script language = "JavaScript">

	//Función para que salga automáticamente por pantalla la opción de imprimir.
	function imprimir() {
		version = parseInt(navigator.appVersion);
		if (version >= 4) {
			window.print();
		}
	}

</script>





<head>

<!-- Título de la página. -->
<title>HOJA DE OPERACIONES CORRECTIVAS</title>
</head>



<?php
	
	//Conexión a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");
	
	
	
	//Recogemos la fecha y los datos del centro.
	$id_cen = $_GET['id_cen'];
	
	$fecha = $_GET['fecha'];
	$fecha_par = split("-", $fecha);
	$fecha_insert = $fecha_par[1]."-".$fecha_par[0]."-01";
	$num_hoja = $_GET['hoja'];
	$operario = $_GET['operario'];
	$empresa = $_GET['empresa'];
	$o_generales = $_GET['o_generales'];


	$unido = $_GET['obs'];
	$u = split("-", $unido);
	$obs_unidas = array();
	for($i=0;$i<count($u)-1;$i++) {
		$obs_unidas[$i] = $u[$i];
	}
	

	//Recogemos las operaciones correctivas.
	$op_corr = $_GET['op_sel'];
	$o_correctivas = split("-", $op_corr);
	$operaciones = array();
	for($i=0; $i<count($o_correctivas)-1; $i++) {
		$operaciones[$i] = $o_correctivas[$i];
	}

	
	//Obtenemos los datos del centro.
	$sql = "SELECT * FROM centro WHERE Id_Centro = '".$id_cen."'";
	$result = mysql_query($sql);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
	}
	
	$nombre = $fila['Nombre'];
	$dir = $fila['Direccion'];
	$ciudad = $fila['Localidad'];
	$tlfo = $fila['Telefono'];

	
	$op_id = array();
	$op_des = array();
	$sql = "SELECT * FROM operacion_correctiva";
	$result = mysql_query($sql);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
		$op_id[$m] = $fila['Id_operacion_corr'];
		$op_des[$m] = $fila['Descripcion'];
	}

	
	//Inserta en la base de datos una hoja provisional, para poder recuperarla después.
	if( ($id_cen != "") && ($fecha != "") && ($num_hoja != "") && ($_GET['enviado'] != "no") ) {
		$sql = "DELETE FROM hoja_correctiva WHERE Num_Hoja = '".$num_hoja."'";
		$result = mysql_query($sql);

		$sql = "DELETE FROM lineas_hoja WHERE Num_Hoja = '".$num_hoja."'";
		$result = mysql_query($sql);

		$sql = "INSERT INTO hoja_correctiva (Num_Hoja, Id_Centro, Telefono, fecha) VALUES ('$num_hoja', '$id_cen', '$tlfo', '$fecha_insert')";
		$result = mysql_query($sql);

		for($z=0; $z<count($operaciones); $z++) {
			for($x=0; $x<count($op_des); $x++) {
				if($operaciones[$z] == $op_des[$x]) {
					$sql2 = "INSERT INTO lineas_hoja (Num_Hoja, Id_operacion_corr, Correcto) VALUES ('$num_hoja', '$op_id[$x]', '-1')";
					$result2 = mysql_query($sql2);
				}
			}
		}
	}


	//Viene de la página "rellenar_correctiva.php".
	if($_GET['cor_uni']) {
		$cor_uni = $_GET['cor_uni'];
		$cu = split("X", $cor_uni);
		$correcto = array();
		for($i=0; $i<count($cu)-1; $i++) {
			$correcto[$i] = $cu[$i];
		}
	} else {
		for($i=0; $i<count($operaciones); $i++) {
			$correcto[$i] = -1;
		}
	}

?>



<!-- Tabla resumen con los datos del centro y las operaciones correctivas que se han seleccionado
	en "op_correctivas.php". Esta tabla es la que el operario llevará impresa al centro al que
	le toca revisión. -->
<table width='100%' class='listado1' border="1" style="margin-top:60px; width:100%; margin-bottom:20px;" align="center">

	<tr bgcolor='#FFFF99'>
		<td width="70%" colspan='7' align="left"><b>HOJA DE MANTENIMIENTO</b></td>
		<td width="10%" align="left">Nº</td>
		<td width="20%" colspan='2' align="left"><? echo $num_hoja; ?></td>
	</tr>

	<tr>
		<td colspan="3" rowspan="3" width="30%" align="left" valign="center"><b>INSTALACIÓN</b></td>
		<td colspan="3" width="30%" align="left">NOMBRE</td>
		<td colspan="4" width="40%" align="left"><? if($nombre == "") { echo '&nbsp'; } else { echo $nombre; } ?></td>
	</tr>

	<tr>
		<td colspan="3" width="30%" align="left">UBICACIÓN</td>
		<td colspan="4" width="40%" align="left"><? if($dir == "") { echo '&nbsp'; } else { echo $dir; } ?></td>
	</tr>

	<tr>
		<td colspan="3" width="30%" align="left">LOCALIDAD</td>
		<td colspan="4" width="40%" align="left"><? if($ciudad == "") { echo '&nbsp'; } else { echo $ciudad; } ?></td>
	</tr>

	<tr>
		<td width="20%" colspan='2' align="left">FECHA</td>
		<td width="30%" align="left" colspan='3'><? if($fecha == "") { echo '&nbsp'; } else { echo $fecha; } ?></td>
		<td width="20%" colspan='2' align="left" >OPERARIO</td>
		<td width="30%" align="left" colspan='3'><?php if($operario == "") { echo '&nbsp'; } else {
			echo $operario; } ?></td>
	</tr>

	<tr>
		<td width="20%" colspan='2' align="left">EMPRESA</td>
		<td width="30%" align="left" colspan='3'><?php if($empresa == "") { echo '&nbsp'; } else {
			echo $empresa; } ?></td>
		<td width="20%" colspan='2' align="left" >TELÉFONO</td>
		<td width="30%" align="left" colspan='3'><? if($tlfo == "") { echo '&nbsp'; } else { echo $tlfo; } ?></td>
	</tr>

	<tr bgcolor='#FFCCCC'>
		<td width="10%" class='titulo2' align="left"><b>Nº</b></td>
		<td width="40%" class='titulo2' align="left" colspan='4'><b>OPERACIÓN CORRECTIVA</b></td>
		<td width="10%" class='titulo2' align='center' style="text-align:center;"><b>OK</b></td>
		<td width="40%" class='titulo2' colspan='4' align="left" ><b>OBSERVACIONES</b></td>
	</tr> 

	<?php
		
		for($i=1; $i<count($operaciones)+1; $i++) {
			echo "<tr>";
			echo "<td width='10%' align='left'>".$i."</td>";
			echo "<td width='40%' align='left' colspan='4'>".$operaciones[$i-1]."</td>";
				if($correcto[$i-1] == -1) {
					echo "<td width='10%' align='center'><img id='imagen".$i."' src='../imagenes/iconos/no-imagen.gif' /></td>";
				} else if($correcto[$i-1] == 1) {
					
					echo "<td width='10%' align='center'><img id='imagen".$i."' src='../imagenes/iconos/aceptar3.gif' /></td>";
				} else {
					echo "<td width='10%' align='center'><img id='imagen".$i."' src='../imagenes/iconos/cancelar3.gif' /></td>";
				}
			echo "<td width='40%' colspan='4' align='left'>"; if($obs_unidas[$i-1] == "") { echo '&nbsp'; } else {
			echo $obs_unidas[$i-1]; } echo "</td>";
			echo "</tr>";
		}
	
	?>

	</table>	


<table width="100%" align='center' border='1' class='listado1' style="width:100%;">
	<tr height='70'>
		<td width='10%' align='left'>OBSERVACIONES GENERALES</td>
		<td width='90%' colspan='9' align='left'><? if ($o_generales == "") { echo '&nbsp'; } else { echo $o_generales; } ?></td>
	</tr>
</table>



<!-- Se llama a la función imprimir. Abre automáticamente el cuadro de impresión. -->
<script>
	 imprimir();
</script>



<?php

	//Cerramos la base de datos.
	mysql_close();

?>