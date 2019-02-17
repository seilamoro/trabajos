<link rel="stylesheet" type="text/css" href="../css/css_principal.css">
<link rel="stylesheet" type="text/css" href="../css/principal.css">
<body style ="background-image:url(../imagenes/fondos/fondo_paginas.jpg);" >





<?php

	//Conexión a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");
	
	
	$n_cen = $_GET['id_cen'];
	$id_cen = $n_cen;
	//if($n_cen < 10) {
	//	$n_cen = "0".$n_cen;
	//}


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



<table width='100%' style='border:1px solid #ACA867;'>

	<!--<tr>
		<td width='100%' style='border:1px solid #ACA867;'align='center' colspan='2'><img src="../imagenes/centros/centro<? echo $n_cen; ?>.jpg" height='200px' width='342px' style='cursor:hand;' title='Ver datos del centro' onClick='abrir_ampliacion(".$id_cen.");'></td>
	</tr>-->


	<tr>
	<?php
			if(file_exists("../imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.gif")) {		
		?>		
				<td width='100%' style='border:1px solid #ACA867;'align='center' colspan='2'><IMG SRC="../imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.gif" height='200px' width='342px'></td>
		<?
			} elseif(file_exists("../imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.bmp")) {
		?>
				<td width='100%' style='border:1px solid #ACA867;'align='center' colspan='2'><IMG SRC="../imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.bmp" height='200px' width='342px'></td>
		<?
			} elseif(file_exists("../imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.jpeg")) {
		?>
				<td width='100%' style='border:1px solid #ACA867;'align='center' colspan='2'><IMG SRC="../imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.jpeg" height='200px' width='342px'></td>
		<?
			} elseif(file_exists("../imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.jpg")) {
		?>
				<td width='100%' style='border:1px solid #ACA867;'align='center' colspan='2'><IMG SRC="../imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.jpg" height='200px' width='342px'></td>
		<?
			} elseif(file_exists("../imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.png")) {
		?>
				<td width='100%' style='border:1px solid #ACA867;'align='center' colspan='2'><IMG SRC="../imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.png" height='200px' width='342px'></td>
		<?
			} else {
		?>
				<td width='100%' style='border:1px solid #ACA867;'align='center' colspan='2'><IMG SRC="../imagenes/fotos/NULL.jpg"height='200px' width='342px'></td>
		<?
			}  
		?>
		</tr>



	<tr>
		<td width='40%' style='border:1px solid #ACA867;' align='right'>NOMBRE</td>
		<td width='60%' style='border:1px solid #ACA867;' align='left'><font face='Courier'><? if($nombre != "") {echo $nombre;} else { echo '&nbsp';} ?></font></td>
	</tr>
	<tr>
		<td width='40%' style='border:1px solid #ACA867;' align='right'>DIRECCIÓN</td>
		<td width='60%' style='border:1px solid #ACA867;' align='left'><font face='Courier'><? if($direccion != "") {echo $direccion;} else { echo '&nbsp';} ?></font></td>
	</tr>
	<tr>
		<td width='40%' style='border:1px solid #ACA867;' align='right'>TELÉFONO</td>
		<td width='60%' style='border:1px solid #ACA867;' align='left'><font face='Courier'><? if($tlfo != "") {echo $tlfo;} else { echo '&nbsp';} ?></font></td>
	</tr>
	<tr>
		<td width='40%' style='border:1px solid #ACA867;' align='right'>LOCALIDAD</td>
		<td width='60%' style='border:1px solid #ACA867;' align='left'><font face='Courier'><? if($localidad != "") {echo $localidad;} else { echo '&nbsp';} ?></font></td>
	</tr>
	<tr>
		<td width='40%' style='border:1px solid #ACA867;' align='right'>NÚMERO DE HOJAS</td>
		<td width='60%' style='border:1px solid #ACA867;' align='left'><font face='Courier'><? echo $num_hojas; ?></font></td>
	</tr>
	<tr>
		<td width='40%' style='border:1px solid #ACA867;' align='right'>ÚLTIMA REVISIÓN</td>
		<td width='60%' style='border:1px solid #ACA867;' align='left'><font face='Courier'><? echo $fecha_mayor; ?></font></td>
	</tr>
	<tr>
		<td width='100%' style='border:1px solid #ACA867;' colspan='2' align='right'>
		<INPUT TYPE="button" NAME="cerrar" VALUE="Cerrar" class="boton_big" onClick="window.close();" style='cursor:hand;' title='Volver atrás'>
		</td>	
	</tr>

</table>





<?php

	//Cerramos la base de datos.
	mysql_close();

?>

</body>