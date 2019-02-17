<script language='javascript'>

	//Calcula la altura del segundo bloque para que, sea cual sea la altura del primer bloque,
	//la distancia entre ambos se mantenga siempre constante.
	function altura_div() {
		var altura = document.getElementById('primer_div').offsetHeight;
		document.getElementById('segundo_div').style.top = altura + 330;
	}

	//Función que selecciona todas las operaciones que estén en el cuadro "disponibles".
	function seleccionar_todo() {
		var cont = 0;
		for(var i=0; i<document.getElementById("op_seleccionadas").length; i++) {
			cont++;
		}
		
		for(i=document.getElementById("op_disponibles").length -1; i>-1; i--) {
			document.getElementById("op_seleccionadas").options[cont] = new 		  	Option(document.getElementById("op_disponibles").options[i].value, document.getElementById("op_disponibles").options[i].value);
			document.getElementById("op_seleccionadas").options[cont].title = document.getElementById("op_disponibles").options[i].value;
			document.getElementById("op_disponibles").options[i] = null;
			cont++;
		}
	}

	//Función que deselecciona todas las operaciones que estén en el cuadro "seleccionadas".
	function deseleccionar_todo() {
		var cont = 0;
		for(var i=0; i<document.getElementById("op_disponibles").length; i++) {
			cont++;
		}
		
		for(i=document.getElementById("op_seleccionadas").length -1; i>-1; i--) {
			document.getElementById("op_disponibles").options[cont] = new 		  	Option(document.getElementById("op_seleccionadas").options[i].value, document.getElementById("op_seleccionadas").options[i].value);
			document.getElementById("op_disponibles").options[cont].title = document.getElementById("op_seleccionadas").options[i].value;
			document.getElementById("op_seleccionadas").options[i] = null;
			cont++;
		}
	}
	
	//Función que selecciona una o varias operaciones (si se ha usado la tecla "Control") y las
	//añade al cuadro "seleccionadas".
	function seleccionar() {
		var cont = 0;
		var sel = 0;
		//Tiene los índices de las operaciones seleccionadas.
		var arr = new Array();

		for(var i=0; i<document.getElementById("op_seleccionadas").length; i++) {
			cont++;
		}
		
		for(var i=0; i<document.getElementById("op_disponibles").length; i++) {
			if(document.getElementById("op_disponibles").options[i].selected == true) {
				arr[sel] = i;
				sel++;
			}
		}
		if(sel == 0) {
			alert("No ha seleccionado ninguna operación correctiva");
		}
		
		for(var i=sel-1; i>-1; i--) {
			var ind = arr[i];
			document.getElementById("op_seleccionadas").options[cont] = new 		  	Option(document.getElementById("op_disponibles").options[ind].value, document.getElementById("op_disponibles").options[ind].value);
			document.getElementById("op_seleccionadas").options[cont].title = document.getElementById("op_disponibles").options[ind].value;
			document.getElementById("op_disponibles").options[ind] = null;
			cont++; 
		}
	}

	//Función que deselecciona una o varias operaciones (si se ha usado la tecla "Control") y las
	//añade al cuadro "dsiponibles".
	function deseleccionar() {
		var cont = 0;
		var sel = 0;
		//Tiene los índices de las operaciones seleccionadas.
		var arr = new Array();

		for(var i=0; i<document.getElementById("op_disponibles").length; i++) {
			cont++;
		}
		
		for(var i=0; i<document.getElementById("op_seleccionadas").length; i++) {
			if(document.getElementById("op_seleccionadas").options[i].selected == true) {
				arr[sel] = i;
				sel++;
			}
		}
		if(sel == 0) {
			alert("No ha seleccionado ninguna operación correctiva");
		}
		
		for(var i=sel-1; i>-1; i--) {
			var ind = arr[i];
			document.getElementById("op_disponibles").options[cont] = new 		  	Option(document.getElementById("op_seleccionadas").options[ind].value, document.getElementById("op_seleccionadas").options[ind].value);
			document.getElementById("op_disponibles").options[cont].title = document.getElementById("op_seleccionadas").options[ind].value;
			document.getElementById("op_seleccionadas").options[ind] = null;
			cont++; 
		}
	}

	//Función que envía el formulario junto con todas las operaciones que hay en ese
	//momento en el cuadro "seleccionadas".
	function comprobar() {
		if(document.getElementById("op_seleccionadas").length == 0) {
			alert("No ha seleccionado ninguna operación correctiva");
		} else {
			for(var i=0; i<document.getElementById("op_seleccionadas").length; i++) {
				document.getElementById("op_seleccionadas").options[i].selected = true;
			}
			document.opera_corr.submit();
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


	$subcomp_total = 0;
	$preven_total = 0;
	
	$id_cen = $_GET['id_cen'];
	

	$fecha = $_GET['fecha'];
	$fecha_partida = split("-", $fecha);
	$mes = $fecha_partida[0];
	$anio = $fecha_partida[1];
	//$fecha tiene la fecha enviada.
	$fecha = $mes."-".$anio;
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


	//Recogemos los componentes que toca revisar.
	$ids_comp = $_GET['ids_comp'];
	$ids_comp_partida = split("-", $ids_comp);
	//$operaciones tiene los id de las operaciones preventivas enviadas.
	$compo = array();
	for($i=0;$i<count($ids_comp_partida)-1;$i++) {
		$compo[$i] = $ids_comp_partida[$i];
	}


	//Recogemos los subcomponentes que toca revisar.
	$ids_subcomp = $_GET['ids_subcomp'];
	$ids_subcomp_partida = split("-", $ids_subcomp);
	//$operaciones tiene los id de las operaciones preventivas enviadas.
	$subcompo = array();
	for($i=0;$i<count($ids_subcomp_partida)-1;$i++) {
		$subcompo[$i] = $ids_subcomp_partida[$i];
	}
	/*for($i=0;$i<count($subcompo);$i++) {
		echo $subcompo[$i]."<br>";
	}*/
	
	
	
	//Recogemos las operaciones preventivas enviadas, las metemos en un array, y construimos
	//un array paralelo con la descripción de las mismas ('$operaciones' y '$operaciones_des').
	$ids_prev = $_GET['ids_prev'];
	$ids_prev_partida = split("-", $ids_prev);
	//$operaciones tiene los id de las operaciones preventivas enviadas.
	$operaciones = array();
	for($i=0;$i<count($ids_prev_partida)-1;$i++) {
		$operaciones[$i] = $ids_prev_partida[$i];
	}
	//$operaciones_des tiene la descripcion de las operaciones preventivas enviadas.
	$operaciones_des = array();
	for($i=0;$i<count($operaciones);$i++) {
		$operaciones_des[$i] = "";
	}
	for($i=0;$i<count($operaciones);$i++) {
		$sql = "SELECT * FROM operacion_prev WHERE Id_operacion_prev = '".$operaciones[$i]."'";
		$result = mysql_query($sql);
		for ($m=0;$m<mysql_num_rows($result);$m++){
			$fila = mysql_fetch_array($result);
		}
		$operaciones_des[$i] = $fila['Nombre'];
	}


	//Obtenemos los datos del centro.
	$sql = "SELECT * FROM centro WHERE Id_Centro = '".$id_cen."'";
	$result = mysql_query($sql);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
	}
	$nombre = $fila['Nombre'];
	$ubicacion = $fila['Direccion'];
	$localidad = $fila['Localidad'];
	$tlfo = $fila['Telefono'];

	//Guarda los id de las operaciones correctivas.
	$op_correctivas = array();
	$sql = "SELECT * FROM operacion_correctiva";
	$result = mysql_query($sql);
	for ($m=0; $m<mysql_num_rows($result); $m++){
		$fila = mysql_fetch_array($result);
		$op_correctivas[] = $fila['Id_operacion_corr'];
	}

	//Guarda la descripción de las operaciones correctivas.
	$op_correctivas_des = array();
	for($i=0; $i<count($op_correctivas); $i++) {
		$sql = "SELECT * FROM operacion_correctiva WHERE Id_operacion_corr = '".$op_correctivas[$i]."'";
		$result = mysql_query($sql);
		for ($m=0; $m<mysql_num_rows($result); $m++){
			$fila = mysql_fetch_array($result);
			$op_correctivas_des[$i] = $fila['Descripcion'];
		}
	}


	//Operaciones disponibles (lado izquierdo).
	$op_correctivas_dis = array();
	$op_correctivas_dis_des = array();
	//Operaciones seleccionadas (lado derecho).
	$op_correctivas_sel = array();
	$op_correctivas_sel_des = array();
	//Inicialmente
	$op_correctivas_dis = $op_correctivas;
	$op_correctivas_dis_des = $op_correctivas_des;

?>



<div class="pagina">
<div class="listado1" id="primer_div">
			
<div class="listado" width="100%">	
<div class="titulo1"><?echo $nombre; ?></div>					
				
			
<table border="0" class="opcion_triple">		
	<thead>						
	<tr>	
		<th class="titulo2" width='30%'><? echo $mensaje; ?></th>
	</tr>
	</thead>	
</table>



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

	for($i=0; $i<count($compo); $i++) {
		$cont = 0;
		for($j=0; $j<count($subcompo); $j++) {
			$sql = "SELECT * FROM comp_subcomp WHERE Id_Componente = '".$compo[$i]."' AND Id_Subcomponente = '".$subcompo[$j]."'";
			$result = mysql_query($sql);
			if(mysql_num_rows($result) != 0) {
				$cont++;
			}
		}
		echo "<tr>";
		$sq = "SELECT * FROM componente WHERE Id_Componente = '".$compo[$i]."'";
		$rs = mysql_query($sq);
		for ($m=0;$m<mysql_num_rows($rs);$m++){
			$f = mysql_fetch_array($rs);
			$nombre_comp = $f['Nombre'];
		}
		echo "<td width='30%' class='texto2' rowspan='".$cont."'>".$nombre_comp."</td>";
		$sq = "SELECT * FROM subcomponente WHERE Id_Subcomponente = '".$subcompo[$subcomp_total]."'";
		$rs = mysql_query($sq);
		for ($m=0;$m<mysql_num_rows($rs);$m++){
			$f = mysql_fetch_array($rs);
			$nombre_subcomp = $f['Nombre'];
		}
		echo "<td class='texto2' width='30%'>".$nombre_subcomp."</td>";
		$sq = "SELECT * FROM operacion_prev WHERE Id_operacion_prev = '".$operaciones[$preven_total]."'";
		$rs = mysql_query($sq);
		for ($m=0;$m<mysql_num_rows($rs);$m++){
			$f = mysql_fetch_array($rs);
			$op_p = $f['Nombre'];
		}
		echo "<td class='texto2' width='40%'>".$op_p."</td>";
		$subcomp_total++;
		$preven_total++;
		echo "</tr>";

		for($m=0; $m<$cont-1; $m++) {
			echo "<tr>";
			$sq = "SELECT * FROM subcomponente WHERE Id_Subcomponente = '".$subcompo[$subcomp_total]."'";
			$rs = mysql_query($sq);
			for ($d=0;$d<mysql_num_rows($rs);$d++){
				$f = mysql_fetch_array($rs);
				$nombre_subcomp = $f['Nombre'];
			}
			echo "<td class='texto2' width='30%'>".$nombre_subcomp."</td>";
			$sq = "SELECT * FROM operacion_prev WHERE Id_operacion_prev = '".$operaciones[$preven_total]."'";
			$rs = mysql_query($sq);
			for ($t=0;$t<mysql_num_rows($rs);$t++){
				$f = mysql_fetch_array($rs);
				$op_p = $f['Nombre'];
			}	
			echo "<td class='texto2' width='40%'>".$op_p."</td>";
			$subcomp_total++;
			$preven_total++;
			echo "</tr>";
		}
	}

?>


</thead>
</table>
					
</div>
</div>
</div>





<!-- Segundo bloque. -->
<div class="pagina" style="margin-top:50px;">
<div class="listado1" id='segundo_div'>
	
<div class="listado" width="100%">	
<div class="titulo1">Seleccionar Operaciones Correctivas</div>					
			
<table border="0" class="opcion_triple">		
	<thead>						
	<tr>						
		<th class="titulo2" colspan="2">Operaciones</th>											
	</tr>
	</thead>				
</table>					
	
			

<!-- Formulario que envía las operaciones correctivas seleccionadas a "impresion_correctiva.php". -->
<table border="1" class="opcion_triple" style="text-indent:0px;">	
<form name="opera_corr" action='principal.php?pag=impresion_correctiva.php' method='POST'>
<input type="hidden" name="centro" value='<?php echo $id_cen ?>'>
<input type="hidden" name="fecha" value='<?php echo $fecha ?>'>
<input type="hidden" name="ids_p" value='<? echo $ids_prev; ?>'>
<input type="hidden" name="ids_comp" value='<? echo $ids_comp; ?>'>
<input type="hidden" name="ids_subcomp" value='<? echo $ids_subcomp; ?>'>
	<thead>						
	<tr>
		<th class="titulo3">Disponibles</th>
		<th class="titulo3"></th>
		<th class="titulo3">Seleccionadas</th>
	</tr>
	</thead>

	<thead>	
	<tr>
		<th class="opcion_fondo">
			<select multiple size="10" style="width:270px" name="op_disponibles[]" id="op_disponibles">
    				
			<?php
						
				for ($m=0; $m<count($op_correctivas_dis_des); $m++){
					$descripcion = $op_correctivas_dis_des[$m];
					echo "<option value='".$descripcion."' title='".$descripcion."'>".$descripcion."</option>";
				}

			?>	
    				
			</select>
		</th>
		
		<th>
			<br><input style="cursor:hand;" title="Añadir todas las operaciones a seleccionadas" class='boton_small' type="button" value="  >>  " onClick='seleccionar_todo();' name="B1">
			<br><input style="cursor:hand;" title="Añadir operación a seleccionadas" class='boton_small' type="button" value="   >   " onClick='seleccionar();' name="B1">
			<br><input style="cursor:hand;" title="Quitar operación de seleccionadas" class='boton_small' type="button" value="   <   " onClick='deseleccionar();' name="B2">
			<br><input style="cursor:hand;" title="Quitar todas las operaciones de seleccionadas" class='boton_small' type="button" value="  <<  " onClick='deseleccionar_todo();' name="B2">
		</th>
		
		<th>
			<select multiple size="10" style="width:270px" name="op_seleccionadas[]" id="op_seleccionadas">
			</select>
		</th>
	</tr>
	</thead>
	
	<thead>
	<tr>
		<td colspan='3'>
			<div class="opcion_boton">		
			<table width="100%" border='0'>
				<tr>
					<td width='50%' align='center'>
						<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal?pag=mtto_preventivo.php&fecha=<? echo $fecha; ?>'" style='cursor:hand;' title='Volver atrás'>
					</td>
				
					<td width='50%' align='center'>
						<INPUT TYPE="button" NAME="continuar" VALUE="Continuar" class="boton_big" onClick="comprobar();" style='cursor:hand;' title='Ver hoja de revisión'>	
					</td>	
				</tr>
			</table>	
			</div>
		</td>
	</tr>
	</thead>

</form>
</table>	
						
</div>
</div>
</div>



<script>

	altura_div();

</script>


											
<?php

	//Cerramos la base de datos.
	mysql_close();

?>