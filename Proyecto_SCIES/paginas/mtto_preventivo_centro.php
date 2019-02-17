<?php
	
	//Conexión a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");


	//Recogemos el id del centro
	$id_cen = $_GET['centro'];

	
	//Recogemos los datos del centro.
	$sql = "SELECT * FROM centro WHERE Id_Centro = '".$id_cen."'";
	$result = mysql_query($sql);
	for ($i=0;$i<mysql_num_rows($result);$i++){
		$fila = mysql_fetch_array($result);

		$nombre_centro = $fila['Nombre'];
		$direccion_centro = $fila['Direccion'];
		$tlfo_centro = $fila['Telefono'];
		$localidad_centro = $fila['Localidad'];
	}

	
	//Recogemos la fecha.
	$fecha = $_GET['fecha'];
	$fecha_partida = split("-", $fecha);
	$mes = $fecha_partida[0];
	$anio = $fecha_partida[1];
	$fecha_completa = $anio."-".$mes."-01";


	//Función que recibe como parámetros una fecha de instalación de un componente, la fecha actual
	//y la periodicidad de revisiones de sus subcomponentes, y devuelve un array con las fechas de
	//las próximas cien revisiones.
	function comprueba_rev($fecha_pasada, $fecha_instal, $periodicidad) {
		$anio_instal = substr($fecha_instal,0,4);
		$mes_instal = substr($fecha_instal,5,2);
		$fechas_rev = array();
		for($i=0; $i<100; $i++) {
			$mes_instal = $mes_instal + $periodicidad;
			if($mes_instal > 12) {
				while($mes_instal > 12) {
					$mes_instal = $mes_instal - 12;
					$anio_instal = $anio_instal + 1;
				}
			}
			$fecha_rev = $anio_instal."-".$mes_instal;
			$fechas_rev[] = $fecha_rev;
		}
		return $fechas_rev;
	}


	//Función a la que se le pasa una fecha, y pone un cero antes del mes en caso de que
	//sea menor que 10.
	function ponerCeros($fechas) {
		for($i=0;$i<count($fechas);$i++) {
			if(substr($fechas[$i],5,2) < 10) {
				$fechas[$i] = substr($fechas[$i],0,4)."-0".substr($fechas[$i],5,1);
			}
		}
		return $fechas;
	}

?>



<div class="pagina">
<div class="listado1">
			
<div class="listado" width="100%">	
<div class="titulo1"><? echo $nombre_centro; ?></div>					
				
<table border="0" class="opcion_triple" style="text-indent:0px;">		
	<thead>						
	<tr>						
				
		<th class="titulo2" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Datos de la Instalación</th>													
	</tr>
	</thead>
	
	<thead>						
	<tr>
			
		<!--<?php

			if($id_cen<10) {
				$num = "0".$id_cen;
			} else {
				$num = $id_cen;
			}

		?>

		<th class='titulo3' width='40%'><img src='./imagenes/centros/centro<?php echo $num; ?>.jpg' height='76px' width='130px' /></th> -->



		<?php
			if(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.gif")) {		
		?>		
				<th class='titulo3' width='40%'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.gif" WIDTH="130" HEIGHT="76"></th>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.bmp")) {
		?>
				<th class='titulo3' width='40%'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.bmp" WIDTH="130" HEIGHT="76"></th>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.jpeg")) {
		?>
				<th class='titulo3' width='40%'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.jpeg" WIDTH="130" HEIGHT="76"></th>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.jpg")) {
		?>
				<th class='titulo3' width='40%'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.jpg" WIDTH="130" HEIGHT="76"></th>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.png")) {
		?>
				<th class='titulo3' width='40%'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.png" WIDTH="130" HEIGHT="76"></th>
		<?
			} else {
		?>
				<th class='titulo3' width='40%'><IMG SRC="imagenes/fotos/NULL.jpg" WIDTH="130" HEIGHT="76"></th>
		<?
			}
		?>    



		<th class='titulo3_centro' width='60%'>
			Dirección:  <font size='1' type='Georgia'><? echo $direccion_centro; ?></font><br>
			Teléfono: <font size='1' type='Georgia'><?echo $tlfo_centro; ?></font><br>
			Localidad: <font size='1' type='Georgia'><? echo $localidad_centro; ?></font>
		</th>
	
	</tr>
	</thead>
</table>
</div>

	
			
<div class="listado" width="100%">
	<table border="0" class="opcion_triple">		
	<thead>						
		<tr>	
		
		<?php

			if( ($mes != "") && ($anio != "") ) {
				if($mes == "01") { $mes_des = "Enero"; }
				if($mes == "02") { $mes_des = "Febrero"; }
				if($mes == "03") { $mes_des = "Marzo"; }
				if($mes == "04") { $mes_des = "Abril"; }
				if($mes == "05") { $mes_des = "Mayo"; }
				if($mes == "06") { $mes_des = "Junio"; }
				if($mes == "07") { $mes_des = "Julio"; }
				if($mes == "08") { $mes_des = "Agosto"; }
				if($mes == "09") { $mes_des = "Septiembre"; }
				if($mes == "10") { $mes_des = "Octubre"; }
				if($mes == "11") { $mes_des = "Noviembre"; }
				if($mes == "12") { $mes_des = "Diciembre"; }
				$mensaje = "Revisión Correspondiente a ".$mes_des." de ".$anio."";
			}

		?>

			<th class="titulo2" width='30%'><? echo $mensaje; ?></th>
		</tr>
	</thead>	



<?php
//Consulta todas las revisiones del mes. Se trata de consultas sql anidadas. Desde la tabla   "centro". Se accede a la información de la tabla "centro_comp", de ésta a "comp_subcomp", "operaciones" y "operacion_prev". Es en esta tabla donde esta el atributo periodicidad que necesitamos para calcular las revisiones mensuales.
	$a_revisar = array();
	$cont = 0;
	
	$sql_centro = "SELECT * FROM centro";
	$result_centro = mysql_query($sql_centro);
	for ($i=0;$i<mysql_num_rows($result_centro);$i++){
		$fila_centro = mysql_fetch_array($result_centro);


		$sql_centro_comp = "SELECT * FROM centro_comp WHERE Id_Centro = '".$fila_centro['Id_Centro']."'";
		$result_centro_comp = mysql_query($sql_centro_comp);
		for ($j=0;$j<mysql_num_rows($result_centro_comp);$j++){
			$fila_centro_comp = mysql_fetch_array($result_centro_comp);


			$sql_comp_subcomp = "SELECT * FROM comp_subcomp WHERE Id_Componente = '".$fila_centro_comp['Id_Componente']."'";
			$result_comp_subcomp = mysql_query($sql_comp_subcomp);
			for ($k=0;$k<mysql_num_rows($result_comp_subcomp);$k++){
				$fila_comp_subcomp = mysql_fetch_array($result_comp_subcomp);
				

				$sql_operaciones = "SELECT * FROM operaciones WHERE Id_Subcomponente = '".$fila_comp_subcomp['Id_Subcomponente']."'";
				$result_operaciones = mysql_query($sql_operaciones);
				for ($l=0;$l<mysql_num_rows($result_operaciones);$l++){
					$fila_operaciones = mysql_fetch_array($result_operaciones);
					

					$sql_operacion_prev = "SELECT * FROM operacion_prev WHERE Id_operacion_prev = '".$fila_operaciones['Id_operacion_prev']."'";
					$result_operacion_prev = mysql_query($sql_operacion_prev);
					for ($m=0;$m<mysql_num_rows($result_operacion_prev);$m++){
						$fila_operacion_prev = mysql_fetch_array($result_operacion_prev);
						$fecha_subcomp = substr($fila_centro_comp['Fecha_Instalacion'],0,7);
						$fecha_elegida = $anio."-".$mes;
						$periodicidad = $fila_operacion_prev['Periodicidad'];
						$fechas = comprueba_rev($fecha_elegida, $fecha_subcomp, $periodicidad);
						$fechas2 = ponerCeros($fechas);
						$fechas = $fechas2;
						for($n=0;$n<count($fechas);$n++) {
							if($fechas[$n] == $fecha_elegida) {
								$cont2 = 0;
								$a_revisar[$cont][$cont2] = $fila_centro['Id_Centro']; $cont2++;
								$a_revisar[$cont][$cont2] = $fila_centro_comp['Id_Componente']; $cont2++;
								$a_revisar[$cont][$cont2] = $fila_comp_subcomp['Id_Subcomponente']; $cont2++;
								$a_revisar[$cont][$cont2] = $fila_operaciones['Id_operacion_prev']; $cont2++;
								$cont = $cont + 1;
							}
						}
					}
				}
			}
		}
	}



	$c = $_GET['centro'];
	$vacio = 'si';
	for($i=0; $i<count($a_revisar); $i++) {
		if($a_revisar[$i][0] == $c) {
			$vacio = 'no';
		}
	}
	if($vacio == 'si') {

	?>
		
		<table border="0" class="opcion_triple">			
			<thead>
			<tr>
				<th class="opcion_fondo">NO HAY REVISIONES PENDIENTES ESTE MES</th>
			</tr>
			</thead>

			
<thead>
	<tr>
		<td colspan='3'>
			<div class="opcion_boton">		
			<table width="100%" border='0'>
				<tr>
					<td width='50%' align='center'>
						<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal.php?pag=instalaciones.php&id=<? echo $id_cen; ?>'" style='cursor:hand;' title='Volver atrás'>
					</td>
				</tr>
			</table>	
			</div>
		</td>
	</tr>
	</thead>


		</table> <?
	} else {





	//Agrupa todas las revisiones por centros. El array "a_revisar2" contiene que
	//componentes hay que revisar para cada centro.
	$num_centros = 0;
	$a_revisar2 = array();
	for($i=0; $i<count($a_revisar); $i++) {
		$esta = "no";
		$centro = -1;
		for($j=0; $j<count($a_revisar2); $j++) {
			if($a_revisar[$i][0] == $a_revisar2[$j]) {
				$esta = "si";
			}
		}
		if($esta == "no") {
			$a_revisar2[] = $a_revisar[$i][0];
		}
	}

	?>



<!-- Tabla donde se muestran los detalles de la revisión. -->
<table border="1" class="opcion_triple" style="text-indent:0px;">			
	<thead>						
	<tr>
		<th class="titulo3" width='30%' style="text-align: left;">Componente</th>
		<th class="titulo3" width='30%' style="text-align: left;">Subcomponente</th>
		<th class="titulo3" width='40%' style="text-align: left;">Operación Preventiva</th>
	</tr>
	</thead>

<thead>

<?php

	//Código que calcula las operaciones preventivas que toca realizar y genera
	//la tabla visualizada.
						
	$componentes = array(); 
	for($j=0; $j<count($a_revisar); $j++) {
		$esta = false;
		for($u=0; $u<count($componentes); $u++) {
			if($a_revisar[$j][1] == $componentes[$u]) {
				$esta = true;
			}
		}
		if($esta == false) {
			if($id_cen == $a_revisar[$j][0]) {
				$componentes[] = $a_revisar[$j][1];
			}
		}
	}
	//for($i=0; $i<count($componentes); $i++) {
		//echo $componentes[$i]."<br>";
	//}
						
	$comp = array();
	$id_comp = array();
	for($t=0; $t<count($componentes); $t++) {
		$sql = "SELECT * FROM componente WHERE Id_Componente = '".$componentes[$t]."'";
		$result = mysql_query($sql);
							
		for ($m=0;$m<mysql_num_rows($result);$m++){
			$fila = mysql_fetch_array($result);	
			$comp[$t][0] = $fila['Nombre'];
			$id_comp[$t][0] = $fila['Id_Componente'];
		}
	}
	//echo "ESTAMOS EN EL CENTRO: ".$id_cen."<br>";
	//echo "PARA EL CENTRO ".$id_cen." TOCA REVISION A LOS COMPONENTES: ";
	//for($i=0; $i<count($componentes); $i++) {
		//echo $componentes[$i].",";
	//}
				
	for($i=0; $i<count($componentes); $i++) {
		//echo "<br>LOS SUBCOMPONENTES DEL COMPONENTE ".$componentes[$i]." SON: ";
		$s = "SELECT * FROM comp_subcomp WHERE Id_Componente ='".$componentes[$i]."'";
		$r = mysql_query($s);
		for ($m=0;$m<mysql_num_rows($r);$m++){
			$f = mysql_fetch_array($r);	
			//echo $f['Id_Subcomponente'].",";
		}
	}

						
	$filas = array();
	$op_preventivas = array();
	$subcompo = array();
	//echo "<br>";
	//Bucle de cada comp. que le toque revisión
	for($i=0; $i<count($componentes); $i++) {
		$filas[$i][0] = $componentes[$i];
		$s = "SELECT * FROM comp_subcomp WHERE Id_Componente ='".$componentes[$i]."'";
		$r = mysql_query($s);
											
		//Bucle que selecciona los subcomp. asociados al comp. en curso
		for ($m=0;$m<mysql_num_rows($r);$m++){
			$f = mysql_fetch_array($r);	
								
			//Bucle que recorre todo lo que haya que revisar ese mes
			for($h=0; $h<count($a_revisar); $h++) {
				
				if( ($a_revisar[$h][0] == $id_cen) && ($a_revisar[$h][1] == $componentes[$i]) && ($a_revisar[$h][2] == $f['Id_Subcomponente']) ) {
					//echo "<br>REVISION PARA CENTRO ".$id_cen."-COMP: ".$componentes[$i]."-SUB: ".$f['Id_Subcomponente']."-Y OP.PREVE: ".$a_revisar[$h][3]."<br>";
					$filas[$i][] = $a_revisar[$h][3];
					$op_preventivas[] = $a_revisar[$h][3];
					$subcompo[] = $f['Id_Subcomponente'];
				}
			}
		}
	}

	//echo $filas[1][0]."<br>";
	for($i=0; $i<count($filas); $i++) {
		$num = count($filas[$i]) -1;
		echo "<tr>";
		$s = "SELECT * from componente WHERE Id_Componente='".$filas[$i][0]."'";
		$r = mysql_query($s);
		for ($m=0;$m<mysql_num_rows($r);$m++){
			$f = mysql_fetch_array($r);
			$nombre_comp = $f['Nombre'];
		}
		echo "<td width='30%' class='texto2' rowspan='".$num."'>".$nombre_comp."</td>";
		

		$s = "SELECT * from operaciones WHERE Id_operacion_prev='".$filas[$i][1]."'";
		$r = mysql_query($s);
		for ($m=0;$m<mysql_num_rows($r);$m++){
			$f = mysql_fetch_array($r);
			$id_subcomp = $f['Id_Subcomponente'];
		}
		$s = "SELECT * from subcomponente WHERE Id_Subcomponente='".$id_subcomp."'";
		$r = mysql_query($s);
		for ($m=0;$m<mysql_num_rows($r);$m++){
			$f = mysql_fetch_array($r);
			$nombre_subcomp = $f['Nombre'];
		}
		echo "<td class='texto2' width='30%'>".$nombre_subcomp."</td>";

		$s = "SELECT * FROM operacion_prev WHERE Id_operacion_prev = '".$filas[$i][1]."'";
		//echo $s."<br>";
		$r = mysql_query($s);
		for ($m=0;$m<mysql_num_rows($r);$m++){
			$f = mysql_fetch_array($r);
			$nombre_prev = $f['Nombre'];
		}
		echo "<td class='texto2' width='40%'>".$nombre_prev."</td>";
		echo "</tr>";

		
		for($j=2; $j<count($filas[$i]); $j++) {
			echo "<tr>";
			$s = "SELECT * from operaciones WHERE Id_operacion_prev='".$filas[$i][$j]."'";
			$r = mysql_query($s);
			for ($m=0;$m<mysql_num_rows($r);$m++){
				$f = mysql_fetch_array($r);
				$id_subcomp = $f['Id_Subcomponente'];
			}
			$s = "SELECT * from subcomponente WHERE Id_Subcomponente='".$id_subcomp."'";
			$r = mysql_query($s);
			for ($m=0;$m<mysql_num_rows($r);$m++){
				$f = mysql_fetch_array($r);
				$nombre_subcomp = $f['Nombre'];
			}
			echo "<td class='texto2' width='30%'>".$nombre_subcomp."</td>";

			$s = "SELECT * FROM operacion_prev WHERE Id_operacion_prev = '".$filas[$i][$j]."'";
			//echo $s."<br>";
			$r = mysql_query($s);
			for ($m=0;$m<mysql_num_rows($r);$m++){
				$f = mysql_fetch_array($r);
				$nombre_prev = $f['Nombre'];
			}
			echo "<td class='texto2' width='40%'>".$nombre_prev."</td>";
			echo "</tr>";
		}
	}
								
?>


<tr>
	<td colspan='3'>
	
		<div class="opcion_boton">		
		<table width="100%" border='0'>
		
			<tr>
				<td width='50%' align='center'>
				<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal.php?pag=mtto_preventivo.php&fecha=<? echo $fecha; ?>'" style='cursor:hand;' title='Volver atrás'>
				</td>
				
				<td width='50%' align='center'>
				<INPUT TYPE="button" NAME="continuar" VALUE="Continuar" class="boton_big" onClick=" location.href='principal?pag=op_correctivas.php&id_cen=<? echo $id_cen; ?>&fecha=<? echo $fecha; ?>&ids_prev=<? for($i=0;$i<count($op_preventivas);$i++) { echo $op_preventivas[$i]."-"; } ?>&ids_comp=<? for($i=0;$i<count($filas);$i++) { echo $filas[$i][0]."-"; } ?>&ids_subcomp=<? for($i=0;$i<count($subcompo);$i++) { echo $subcompo[$i]."-"; } ?>';" style='cursor:hand;' title='Seleccionar operaciones correctivas'>	
				</td>	
				
			</tr>
	
		</table>	
		</div>
		
	</td>
</tr>

</thead>

</table>

		<? } ?>			
								
</table>
			
</div>	
</div>
</div>
</div>



<?php

	//Cerramos la base de datos.
	mysql_close();

?>