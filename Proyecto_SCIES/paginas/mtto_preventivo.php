<script language="javascript">

	//Envía el formulario relativo a la fecha elegida. 
	function enviar_fecha() {
		document.fecha.submit();
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



	//Obtiene la fecha actual.
	$dia_actual = date(d);
	$mes_actual = date(m);
	$anio_actual = date(Y);
	$fecha_actual = $dia_actual."-".$mes_actual."-".$anio_actual;


	//Obtiene la fecha que se ha seleccionado
	if($_GET['fecha']) {
		$mes = substr($_GET['fecha'],0,2);
		$anio = substr($_GET['fecha'],3,4);
	} else if( ($_POST['mes']) && ($_POST['anio']) ) {
		$mes = $_POST['mes'];
		$anio = $_POST['anio'];
	} else {
		$mes = $mes_actual;
		$anio = $anio_actual;
	}
	$fech = $mes."-".$anio;


	//Calcula los próximos años que aparecerán en el desplegable.
	$anios = array();
	$anio_inicio = 2006;
	$anio_fin = $anio_actual + 5;
	for($i=$anio_inicio-1; $i<=$anio_fin; $i++) {
		$anios[] = $i+1;
	}

?>



<!-- Formulario que envía el centro seleccionado. -->
<form name="datos_centro" action="principal.php?pag=mtto_preventivo_centro.php" method="POST">
	<input type="hidden" name="centro" id="centro">
	<input type="hidden" name="menu_valor" id="centro" value="">
	<input type="hidden" name="menu_fecha" id="centro" value="">
</form>



<div class="pagina">
<div class="listado1">
	
<div class="listado" width="100%">	
<div class="titulo1">Mantenimiento Preventivo

<table border="0" class="opcion_triple">		
	<thead>
	<tr>						
		<th class="titulo2" colspan="3">Criterios de Búsqueda</th>
	</tr>
	</thead>
	<thead>						
	<tr>
		<form name='fecha' action='principal.php?pag=mtto_preventivo.php' method='POST'>
		<th class="titulo3" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select style="cursor:hand;" name="mes">
			<option value="01"<? if('01'==$mes){print("selected");}?>><?print("Enero");?>
			<option value="02"<? if('02'==$mes){print("selected");}?>><?print("Febrero");?>
			<option value="03"<? if('03'==$mes){print("selected");}?>><?print("Marzo");?>
			<option value="04"<? if('04'==$mes){print("selected");}?>><?print("Abril");?>
			<option value="05"<? if('05'==$mes){print("selected");}?>><?print("Mayo");?>
			<option value="06"<? if('06'==$mes){print("selected");}?>><?print("Junio");?>
			<option value="07"<? if('07'==$mes){print("selected");}?>><?print("Julio");?>
			<option value="08"<? if('08'==$mes){print("selected");}?>><?print("Agosto");?>
			<option value="09"<? if('09'==$mes){print("selected");}?>><?print("Septiembre");?>
			<option value="10"<? if('10'==$mes){print("selected");}?>><?print("Octubre");?>
			<option value="11"<? if('11'==$mes){print("selected");}?>><?print("Noviembre");?>
			<option value="12"<? if('12'==$mes){print("selected");}?>><?print("Diciembre");?>
		</select>
		&nbsp;&nbsp;<select style="cursor:hand;" name="anio">
		<?php

			for($i=0; $i<count($anios); $i++) {
				
		?>
				<option value="<? echo $anios[$i]; ?>" <? if($anios[$i] == $anio) {print("selected");}?>><?print($anios[$i]); ?>
		<?php
			
			}
		
		?>

		</select>&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="button" NAME="seleccionar" VALUE="Seleccionar" class="boton_big" onClick="enviar_fecha();" style="cursor:hand;" title="Ver revisiones en la fecha seleccionada">	
		</th>
		</form>
	</tr>
	</thead>
</table>
</div>			
		


<?php

	//Consulta todas las revisiones del mes. Se trata de consultas sql anidadas. Desde la tabla   "centro", se accede a la información de la tabla "centro_comp", de ésta a "comp_subcomp", "operaciones" y "operacion_prev". Es en esta tabla donde esta el atributo periodicidad que necesitamos para calcular las revisiones mensuales.
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

	
	
	//Agrupa todas las revisiones por centros. El array bidimensional "a_revisar2" contiene que
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


	//Si el número de pestañas es cero, no hay que revisar nada. Se muestra el mensaje correspondiente.
	$n_pest = count($a_revisar2);
	if($n_pest == 0) {

?>



<table border="0" class="opcion_triple">		
	<thead>
	<tr>	
	
		<th class="titulo2" colspan="3">
		
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
				echo "Revisiones Correspondientes a ".$mes_des." de ".$anio."";
			}

		?>
		
		</th>

	</tr>
	</thead>
</table>



<table border="0" class="opcion_triple">			
	<thead>
	<tr>
		<th class="opcion_fondo">NO HAY REVISIONES PENDIENTES ESTE MES</th>
	</tr>
	</thead>
</table>		

	
</div>	
</div>
</div>

</body>


<?php

	} else {

?>


<table border="0" class="opcion_triple">		
	<thead>
	<tr>	
	
		<th class="titulo2" colspan="3">
		
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
				echo "Revisiones Correspondientes a ".$mes_des." de ".$anio."";
			}

		?>
		
		</th>

	</tr>
	</thead>
</table>



<table class="listados" width='100%'>
	<thead>
	<tr>

		<th width='55%' id='borde_db' class="titulo">
		<div style="background: #e6e3b6 url(./imagenes/botones/thead_fondo_borde.gif) no-repeat left top; width:8px; height:30px; float:left;"></div>
		<div class="centro">
			INSTALACIÓN
		</div>
		</th>

		<th width='45%%' id="borde_db" class="titulo">
		<div style="background: #e6e3b6 url(./imagenes/botones/thead_fondo_borde.gif) no-repeat left top; width:8px; height:30px; float:left;"></div>
		<div class="centro">
			LOCALIDAD
		</div>
		</th>

	</tr>
	</thead>
		


	<tbody>
	<tr class="blanco"></tr>

	<?php

		$fech = "'".$fech."'";
		for($k=0; $k<count($a_revisar2); $k++) {
			$sql = "SELECT * FROM centro WHERE Id_Centro = '".$a_revisar2[$k]."'";
			$result = mysql_query($sql);
			for ($i=0;$i<mysql_num_rows($result);$i++){
				$fila = mysql_fetch_array($result);
				?>
				
				<a href="#">
				<tr class="accion" oncontextmenu="mostrarMenu(<? echo $fila['Id_Centro']; ?>, <? echo $fech; ?>);">
				<?
				echo "<td class='accion'>".$fila['Nombre']."</td>";
				echo "<td class='accion'>".$fila['Localidad']."</td>";
				echo "</tr></a>";
			}
		}

	?>


</tbody>
</table>


</div>
</div>
</div>



<?php
			
	}
?>



<div id="menu" style="position:absolute;visibility:hidden;padding:0 0 0 0;margin:0px;">
	
	<div class="item_esquina" style="float:left; width:6px; height:5px; background:url(./imagenes/botones/menu_esquina.gif) no-repeat;">
	</div>
	
	<table cellpadding='0' cellspacing='0'>
	<tr>
		<td style="padding: 0 0 0 0;">
			<div style="float:left; width:4px; height:26px; background:url('./imagenes/botones/menu_borde_izq.gif') no-repeat;">
			</div>
			
			<div class="item" align="center" style="width:100px; float:left; height:26px; background:url('./imagenes/botones/menu_fondo.gif') repeat-x; cursor:pointer; font-size:20px; font-weight: bold;" onclick="cambiarPagina();">Ver
			</div>
			
			<div style="float:left; width:4px; height:26px; background:url('./imagenes/botones/menu_borde_der.gif') no-repeat;">
			</div>
		</td>
	</tr>
	</table>

</div>




<script>

	function cambiarPagina() {
		location.href = 'principal?pag=mtto_preventivo_centro.php&centro=' + datos_centro.menu_valor.value + '&fecha=' + datos_centro.menu_fecha.value;
	}
	
	function mostrarMenu(valor,fecha) {
		var rightedge = document.body.clientWidth - event.clientX;
		var bottomedge = document.body.clientHeight - event.clientY;
		if (rightedge < menu.offsetWidth) {
			menu.style.left = document.body.scrollLeft + event.clientX - menu.offsetWidth;
		} else {
			menu.style.left = document.body.scrollLeft + event.clientX;
		}
		
		if (bottomedge < menu.offsetHeight) {
			menu.style.top = document.body.scrollTop + event.clientY - menu.offsetHeight;
		} else {
			menu.style.top = document.body.scrollTop + event.clientY;	
		}
		document.datos_centro.menu_valor.value = valor;
		document.datos_centro.menu_fecha.value = fecha;
		menu.style.visibility = "visible";
	}

	function mostrarMenuNormal() {
		if (menu.style.visibility == "visible") {
			return false;
		} else {
			return true;
		}
	}

	function ocultarMenu() {
		menu.style.visibility = "hidden";
	}

	document.body.oncontextmenu = mostrarMenuNormal;
	document.body.onclick = ocultarMenu;

</script>



<?

//Cerramos la base de datos.
mysql_close();

?>