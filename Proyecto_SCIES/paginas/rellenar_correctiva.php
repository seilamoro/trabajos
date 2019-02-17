<script language='javascript'>

	function abrir_ventana(modo, ids) {
		window.open( "./paginas/seleccion_operacion?modo="+modo+"&ids="+ids, "_blank", "height=170,width=600,resizable=yes,scrollbars=yes,location=no,status=no,menubar=no");
	}
	
	//Posiciona el foco de la aplicación en el campo "número".
	function foco() {
		document.getElementById("numero").focus();
	}

	//Comprueba que se ha escrito un número de hoja. Luego, abre la ventana de impresión de hoja.
	//En ese momento se guardan los datos provisionales en la base de datos.
	function impr() {
		var cen = document.getElementById('centro_').value;
		var fech = document.getElementById('fecha_').value;
		var op = document.getElementById('op_co').value;
		var h = document.getElementById('h').value;
		var o = document.getElementById('o').value;
		var emp = document.getElementById('emp').value;
		var enviado = document.getElementById('enviado').value;
		var unido = document.getElementById('ob_uni').value;
		var o_gen = document.getElementById('o_gen').value;
		var c_uni = document.getElementById('cor_uni').value;
		
		
		window.open( "./paginas/ventana_impresion_correctiva?id_cen="+cen+"&fecha="+fech+"&op_sel="+op+"&hoja="+h+"&operario="+o+"&empresa="+emp+"&enviado="+enviado+"&obs="+unido+"&cor_uni="+c_uni+"&o_generales="+o_gen, "_blank", "resizable=yes,scrollbars=yes,location=no,status=no,menubar=no");
		
	}
	
	//Función que elimina la hoja correctiva si estamos en modo "ver".
	function eliminar2() {
		var decision = confirm("¿Está seguro que desea eliminar la hoja " + document.insertar_hoja2.numero.value + "?");
		if(decision == false) {
			return 0;
		} else {
			//document.insertar_hoja2.eliminar.value = 'si';
			document.getElementById('eliminar').value = 'si';
			document.insertar_hoja2.enviado.value = 'si';
			document.insertar_hoja2.submit();
		}
	}

	function volver_(h, c, f, p, camp) {
		document.getElementById('num_hoja').value = h;
		document.getElementById('centro').value = c;
		document.getElementById('fecha').value = f;
		document.getElementById('n_pagina').value = p;
		document.getElementById('campo_actual').value = camp;
		document.v_listado.submit();
	}

	//Función que elimina la hoja correctiva si estamos en modo "modificar".
	function eliminar_() {
		if(document.insertar_hoja.numero.value.length == 0) {
			alert("Tiene que escribir un número de hoja");
			document.insertar_hoja.numero.focus();
			return 0;
		}
		var decision = confirm("¿Está seguro que desea eliminar la hoja " + document.insertar_hoja.numero.value + "?");
		if(decision == false) {
			return 0;
		} else {
			//document.insertar_hoja.eliminar.value = 'si':
			document.getElementById('eliminar').value = 'si';
			document.insertar_hoja.enviado.value = 'si';
			document.insertar_hoja.submit();
		}
	}

	//Cambia de correcto a incorrecto el campo OK de una línea de la hoja correctiva y viceversa.
	function cambiar(i) {
		var urlAceptar = new String();
		var urlCancelar = new String();
		var urlNoImagen = new String();
		urlAceptar = "./imagenes/iconos/aceptar3.gif";
		urlCancelar = "./imagenes/iconos/cancelar3.gif";
		urlNoImagen = "./imagenes/iconos/no-imagen.gif";
		if(document.getElementById("casilla"+i).value == 1) {
			document.getElementById("imagen"+i).setAttribute("src", urlCancelar);
			document.getElementById("casilla"+i).value = 0;
		} else if(document.getElementById("casilla"+i).value == 0) {
			document.getElementById("imagen"+i).setAttribute("src", urlNoImagen);
			document.getElementById("casilla"+i).value = -1;
		} else {
			document.getElementById("imagen"+i).setAttribute("src", urlAceptar);
			document.getElementById("casilla"+i).value = 1;
		}
	}

	//Comprueba que se han rellenado todos los campos obligatorios de la hoja en el modo "modificar".
	function comprobar(num_correcto) {
		var num = document.getElementById("numero").value;
		if(document.insertar_hoja.numero.value.length == 0) {
			alert("Tiene que escribir un número de hoja");
			document.insertar_hoja.numero.focus();
			return 0;
		}
		if(document.insertar_hoja.nom.value.length == 0) {
			alert("Tiene que escribir un nombre de centro");
			document.insertar_hoja.nom.focus();
			return 0;
		}
		if(document.insertar_hoja.di.value.length == 0) {
			alert("Tiene que escribir una dirección");
			document.insertar_hoja.di.focus();
			return 0;
		}
		if(document.insertar_hoja.ciu.value.length == 0) {
			alert("Tiene que escribir una ciudad");
			document.insertar_hoja.ciu.focus();
			return 0;
		}
		if(document.insertar_hoja.fech.value.length == 0) {
			alert("Tiene que escribir una fecha");
			document.insertar_hoja.fech.focus();
			return 0;
		}
		if(document.insertar_hoja.operario.value.length == 0) {
			alert("Tiene que escribir un nombre de operario");
			document.insertar_hoja.operario.focus();
			return 0;
		}
		if(document.insertar_hoja.tlfo.value.length == 0) {
			alert("Tiene que escribir un número de teléfono");
			document.insertar_hoja.tlfo.focus();
			return 0;
		}
		if(num != num_correcto) {
			var decision = confirm("El número de hoja ha cambiado. En caso de continuar los datos se sobrescribirán en la base de datos y podría borrarse una hoja de mantenimiento existente. ¿Desea continuar?");
			if(decision == false) {
				return 0;
			}
		}
		document.insertar_hoja.enviado.value = 'si';
		document.insertar_hoja.submit();
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



	$number = "";

	if($_POST['viene_de_listado']) {
		$link = "principal.php?pag=listado_hojas.php";
	} else {
		$link = "'principal.php?pag=mtto_correctivo.php'";
	}


	if($_POST['opera_']) {
		//echo "WOOOLAAAAA<br>";
		//echo $_POST['opera_']."<br>";
		//echo $_POST['n_']."<br>";
		$sq = "DELETE FROM lineas_hoja WHERE Id_operacion_corr = '".$_POST['opera_']."' AND Num_Hoja = '".$_POST['n_']."'";
		$result = mysql_query($sq);
		$number1 = $_POST['n_'];
	} 


	if($_POST['opera2_']) {
		//echo $_POST['opera2_']."<br>";
		//echo $_POST['n2_']."<br>";
		$sql = "INSERT INTO lineas_hoja(Num_Hoja, Id_operacion_corr, Correcto, Observaciones) VALUES ('".$_POST['n2_']."', '".$_POST['opera2_']."', -1, '')"; 
		$result = mysql_query($sql);
		$number2 = $_POST['n2_'];
	} 


	if( ($_POST['n_hoja']) || ($_POST['cen']) || ($_POST['fech']) || ($_POST['n_pagina']) ) {
		$n_hoja = "'".$_POST['n_hoja']."'";
		$cen = $_POST['cen'];
		$cen = "'".$cen."'";
		$fech = "'".$_POST['fech']."'";
		$pagina = "'".$_POST['n_pagina']."'";
		$camp = "'".$_POST['camp']."'";
	} else {
		$n_hoja = "''";
		$cen = "''";
		$fech = "''";
		$pagina = "''";
		$camp = "''";
	}


	//Operaciones correctivas y sus Ids.
	$operacion_id = array();
	$operacion_des = array();
	$sql = "SELECT * FROM operacion_correctiva";
	$result = mysql_query($sql);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
		$operacion_id[] = $fila['Id_operacion_corr'];
		$operacion_des[] = $fila['Descripcion'];
	}


	//Si viene de correctivo.php comprueba que el número de hoja existe.
	if($_POST['viene_de_correctivo'] == 'si') {
		$sql = "SELECT * FROM hoja_correctiva WHERE Num_Hoja='".$_POST['num_hoja']."'";
		$result = mysql_query($sql);
		$n = mysql_num_rows($result);
		if($n == 0) {
			?>
			<script>
				alert("Ese número de hoja no existe");
				location.href = 'principal.php?pag=mtto_correctivo.php';
			</script>
			<?php
		} else {
			$num_hoja = $_POST['num_hoja'];
		}
	}
	
	
	//Recogemos el número de hoja.
	$num_hoja = $_POST['num_hoja'];

	$tipo = $_POST['tipo'];
	
	if($_GET['ho']) {
		$num_hoja = $_GET['ho'];
		$tipo = "mod";
	}


	if( ($_POST['tipo_ver']) && ($_POST['numero_hoja']) ) {
		$num_hoja = $_POST['numero_hoja'];
		$tipo = $_POST['tipo_ver'];
	}


	if($number1 != "") {
		$num_hoja = $number1;
		$tipo = "mod";
	}

	if($number2 != "") {
		$num_hoja = $number2;
		$tipo = "mod";
	}



	$sql2 = "SELECT * FROM hoja_correctiva WHERE Num_Hoja = '".$num_hoja."'";
	$result2 = mysql_query($sql2);
	for ($m=0;$m<mysql_num_rows($result2);$m++) {
		$fila2 = mysql_fetch_array($result2);
	
		$f = split("-", $fila2['fecha']);
		$fecha = $f[1]."-".$f[0];

		$empresa = $fila2['Empresa'];
		$operario = $fila2['Operario'];
		$id_cen = $fila2['Id_Centro'];			
		$o_generales = $fila2['Observaciones_Generales'];
		$sql3 = "SELECT * FROM centro WHERE Id_Centro = '".$fila2['Id_Centro']."'";
		$result3 = mysql_query($sql3);
		for ($k=0;$k<mysql_num_rows($result3);$k++) {
			$fila3 = mysql_fetch_array($result3);

			$nombre = $fila3['Nombre'];
			$dir = $fila3['Direccion'];
			$ciudad = $fila3['Localidad'];
			$tlfo = $fila3['Telefono'];
		}
	}


	//Cogemos las líneas de la hoja.
	$ids_op_corr = array();
	$correctos = array();
	$observa = array();
	$sql = "SELECT * FROM lineas_hoja WHERE Num_Hoja = '".$num_hoja."'";
	$result = mysql_query($sql);
	for ($i=0;$i<mysql_num_rows($result);$i++) {
		$fila = mysql_fetch_array($result);
		$ids_op_corr[] = $fila['Id_operacion_corr'];
		if($fila['Correcto'] == "") {
			$correctos[] = -1;
		} else {
			$correctos[] = $fila['Correcto'];
		}
		$observa[] = $fila['Observaciones'];
	}


	//Une todas las observaciones en una variable para pasar a la impresión.
	$observa_unido = "";
	for($i=0; $i<count($observa); $i++) {
		$observa_unido = $observa_unido.$observa[$i]."-";
	}


	$ope_cor = "";
	for($j=0; $j<count($ids_op_corr); $j++) {
		$s = "SELECT * FROM operacion_correctiva WHERE Id_operacion_corr='".$ids_op_corr[$j]."'";
		$r = mysql_query($s);
		for ($k=0;$k<mysql_num_rows($r);$k++) {
			$fila = mysql_fetch_array($r);
			$ope_cor = $ope_cor.$fila['Descripcion']."-";
		}
	}

	
	//Operaciones correctivas con sus descripciones.
	$num_operaciones = count($ids_op_corr);
	$des_op_corr = array();
	for($i=0;$i<count($ids_op_corr);$i++) {
		for($j=0;$j<count($operacion_id);$j++) {
			if($ids_op_corr[$i] == $operacion_id[$j]) {
				$des_op_corr[] = $operacion_des[$j];
			}
		}
	}


	$correctos_unido = "";
	for($i=0; $i<count($correctos); $i++) {
		$correctos_unido = $correctos_unido.$correctos[$i]."X";
	}


	$hoja_correcta = $num_hoja;

?>



<head>

<!-- Título de la página. -->
<title>HOJA DE OPERACIONES CORRECTIVAS</title>
</head>

<form name='v_listado' action=<? echo $link; ?> method='POST'>
	<input type='hidden' name='num_hoja' id='num_hoja'>
	<input type='hidden' name='centro' id='centro'>
	<input type='hidden' name='fecha' id='fecha'>
	<input type='hidden' name='n_pagina' id='n_pagina'>
	<input type='hidden' name='campo_actual' id='campo_actual'>
</form>

<?php

	//Si se quiere modificar la hoja actual.
	if($tipo == "mod") {

?>



<form name='insertar_hoja' action='principal.php?pag=mtto_correctivo.php' method='POST'>
<input type='hidden' id='centro_' name='centro_' value='<? echo $id_cen; ?>'>
<input type='hidden' id='fecha_' name='fecha_' value='<? echo $fecha; ?>'>
<input type="hidden" name="o_corr" value='<? echo $op_corr; ?>'>
<input type='hidden' name='enviado' id='enviado' value='no'>
<input type='hidden' name='op_co' id='op_co' value='<? echo $ope_cor; ?>'>
<input type='hidden' name='h' id='h' value='<? echo $num_hoja; ?>'>
<input type='hidden' name='o' id='o' value='<? echo $operario; ?>'>
<input type='hidden' name='emp' id='emp' value='<? echo $empresa; ?>'>
<input type='hidden' name='ob_uni' id='ob_uni' value='<? echo $observa_unido; ?>'>
<input type='hidden' name='o_gen' id='o_gen' value='<? echo $o_generales; ?>'>
<input type='hidden' name='cor_uni' id='cor_uni' value='<? echo $correctos_unido; ?>'>
<input type='hidden' name='eliminar' id='eliminar'>
<div class="pagina">
<div class="listado1" >
			
<div class="listado" width="100%">	
<div class="titulo1">Hoja de Mantenimiento</div>					
	
				
<table border="0" class="opcion_triple">		
	
	<thead>						
	<tr>						
		<th class="titulo2" colspan="10">Datos de la Hoja</th>									
	</tr>
	</thead>				
					
	<thead>		
	<table border='1' class="opcion_triple">
	<tr>
		<th class='titulo3_centro' width="80%" colspan='8' style="text-align:right;">Nº</th>
		<th class='titulo3_centro' width="20%" colspan='2' align="left"><input type='text' id='numero' name='numero' size='10' style="background:transparent;" value='<? echo $num_hoja; ?>'></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan='3' rowspan="3" width="30%" style="text-align:left; text-indent:0px;" valign="center">INSTALACIÓN</th>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px;" class='titulo3_centro'>NOMBRE</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><input type='text' id='nom' name='nom' size='33' style="background:transparent;" value='<? echo $nombre; ?>'></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px">UBICACIÓN</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><input size='33' type='text' id='di' name='di' style="background:transparent;" value='<? echo $dir; ?>'></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px">LOCALIDAD</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><input size='33' type='text' id='ciu' name='ciu' style="background:transparent;" value='<? echo $ciudad; ?>'></th>
	</tr>

	<tr>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">FECHA</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><input size='25' type='text' id='fech' name='fech' style="background:transparent;" value='<? echo $fecha; ?>'></th>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">OPERARIO</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><input size='25' type='text' id='operario' name='operario' style="background:transparent;" value='<? echo $operario; ?>'></th>
	</tr>

	<tr>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">EMPRESA</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><input size='25' type='text' id='empresa' name='empresa' style="background:transparent;" value='<? echo $empresa; ?>'></th>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">TELÉFONO</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><font size='1' type='Georgia'><input size='25' type='text' id='tlfo' name='tlfo' style="background:transparent;" value='<? echo $tlfo; ?>'></font></th>
	</tr>
	</table>
	</thead>
				
</table>					
</div>		
			
			
			
<div class="listado" width="100%">
<table border="0" class="opcion_triple">		
					
	<thead>						
	<tr>						
		<th class="titulo2" >Operaciones Correctivas</th>
	</tr>
	</thead>
</table>

<table border="1" class="opcion_triple" style="text-indent:0px;">			
	<thead>						
	<tr>
		<th class="titulo3" width='10%' style="text-align: left;">Nº</th>
		<th class="titulo3" width='40%' style="text-align: left;">Operación correctiva</th>
		<th class="titulo3" width='10%' style="text-align: center;">OK</th>
		<th class="titulo3" width='40%' style="text-align: left;">Observaciones</th>
	</tr>
	</thead>

	<thead>

	<?php

		$ids_op_corr_unidos = "";
		for($h=0; $h<count($ids_op_corr); $h++) {
			$ids_op_corr_unidos = $ids_op_corr_unidos.$ids_op_corr[$h]."X";
		}
		
		
		for($i=1; $i<count($des_op_corr)+1; $i++) {
			echo "<tr>";
			echo "<td width='10%' style='text-align:left;'>".$i."</td>";
			echo "<td width='40%' style='text-align:left;'>".$des_op_corr[$i-1]."</td>";
			echo "<input type='hidden' name='id_op".$i."' value='".$ids_op_corr[$i-1]."'>";
			
			if($correctos[$i-1] == -1) {
				$valor_casilla = -1;
				echo "<td width='10%' align='center'><img id='imagen".$i."' src='./imagenes/iconos/no-imagen.gif' style='cursor:pointer;' onClick='cambiar(".$i.");' /></td>";
			} else if($correctos[$i-1] == 1) {
				$valor_casilla = 1;
				echo "<td width='10%' align='center'><img id='imagen".$i."' src='./imagenes/iconos/aceptar3.gif' style='cursor:pointer;' onClick='cambiar(".$i.");' /></td>";
			} else {
				$valor_casilla = 0;
				echo "<td width='10%' align='center'><img id='imagen".$i."' src='./imagenes/iconos/cancelar3.gif' style='cursor:pointer;' onClick='cambiar(".$i.");' /></td>";
			}
			echo "<input type='hidden' name='casilla".$i."' id='casilla".$i."' value='".$valor_casilla."'>";

			echo "<td width='40%' align='left' colspan='4'><input type='text' name='observaciones".$i."' value='".$observa[$i-1]."' style='background:transparent;' size='35'></td>";
			echo "</tr>";
		}
	?>
	<input type='hidden' name='n_op' value='<? echo $num_operaciones; ?>'>

	<tr>
		<td colspan='4'>
		<INPUT TYPE="button" NAME="nueva" VALUE="Añadir" class="boton_mediun" onClick="abrir_ventana('anadir', '<? echo $ids_op_corr_unidos; ?>');" style='cursor:hand;' title='Añadir operación correctiva a la hoja'>
		&nbsp;&nbsp;&nbsp;
		<INPUT TYPE="button" NAME="nueva" VALUE="Quitar" class="boton_mediun" onClick="abrir_ventana('quitar', '<? echo $ids_op_corr_unidos; ?>');" style='cursor:hand;' title='Quitar operación correctiva de la hoja'>
		</td>
	</tr>
	
	
	<tr>
		<td colspan='4'>

			<input type="hidden" name="o_corr" value='<? echo $op_corr; ?>'>
			<table border="0" class="opcion_triple" style="text-indent:0px;">	
			<tr height='70'>
				<td width='10%' align='left'><font size='3' type='Georgia'>OBSERVACIONES GENERALES</font></td>
				<td width='90%' colspan='9' align='left'><textarea name='obs_generales' id='obs_generales' style='background:transparent;' rows='4' cols='60' maxlength='255'><? if($o_generales == "") { echo '&nbsp'; } else { echo $o_generales; } ?></textarea></td>
			</tr>
			</table>

		</td>
	</tr>
	<tr>
		<td colspan='4'>
			
			<div class="opcion_boton">		
			<table width="100%" border='0'>
			<input type='hidden' id='hoja' name='hoja'>
			<input type='hidden' id='id_cen' value='<? echo $id_cen; ?>'>
			<input type='hidden' id='fecha' value='<? echo $fecha; ?>'>
			<input type='hidden' id='op_sel' value='<? for ($i=0;$i<count($op_seleccionadas);$i++) {
				echo $op_seleccionadas[$i]."-"; } ?>'>
		
				<tr>
					<td width='33%' align='center'>
					<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="volver_(<? echo $n_hoja; ?>,<? echo $cen; ?>,<? echo $fech; ?>, <? echo $pagina; ?>, <? echo $camp; ?>);" style='cursor:hand;' title='Volver atrás'>
					</td>
				
					<td width='34%' align='center'>
					<INPUT TYPE="button" NAME="aceptar" VALUE="Aceptar" class="boton_big" onClick="comprobar(<? echo $hoja_correcta; ?>);" style='cursor:hand;' title='Guardar cambios en la hoja'>	
					</td>	

					<td width='33%' align='center'>
					<INPUT TYPE="button" NAME="eliminar" VALUE="Eliminar" class="boton_big" onClick='eliminar_();' style='cursor:hand;' title='Eliminar hoja actual'>	
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
</form>



<script>
	foco();
</script>





<?php

	//Si la opción seleccionada fue "ver".
	} else {

?>

<form name='insertar_hoja2' action='principal?pag=mtto_correctivo.php' method='POST'>
<input type='hidden' name='enviado' id='enviado' value='no'>
<input type='hidden' id='centro_' name='centro_' value='<? echo $id_cen; ?>'>
<input type='hidden' name='numero' id='numero' value='<? echo $num_hoja; ?>'>
<input type='hidden' id='fecha_' name='fecha_' value='<? echo $fecha; ?>'>
<input type='hidden' name='op_co' id='op_co' value='<? echo $ope_cor; ?>'>
<input type='hidden' name='h' id='h' value='<? echo $num_hoja; ?>'>
<input type='hidden' name='o' id='o' value='<? echo $operario; ?>'>
<input type='hidden' name='emp' id='emp' value='<? echo $empresa; ?>'>
<input type='hidden' name='ob_uni' id='ob_uni' value='<? echo $observa_unido; ?>'>
<input type='hidden' name='o_gen' id='o_gen' value='<? echo $o_generales; ?>'>
<input type='hidden' name='cor_uni' id='cor_uni' value='<? echo $correctos_unido; ?>'>
<input type='hidden' name='eliminar' id='eliminar'>
<div class="pagina">
<div class="listado1" >
			
<div class="listado" width="100%">	
<div class="titulo1">Hoja de Mantenimiento</div>					
	
			
<table border="0" class="opcion_triple">		
	
	<thead>						
	<tr>						
		<th class="titulo2" colspan="10">Datos de la Hoja</th>									
	</tr>
	</thead>				
					
	<thead>		
	<table border='1' class="opcion_triple">
	<tr>
		<th class='titulo3_centro' width="80%" colspan='8' style="text-align:right;">Nº</th>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px;"><font size='1' type='Georgia'><? echo $num_hoja; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan='3' rowspan="3" width="30%" style="text-align:left; text-indent:0px;" valign="center">INSTALACIÓN</th>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px;" class='titulo3_centro'>NOMBRE</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><font size='1' type='Georgia'><? echo $nombre; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px">UBICACIÓN</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><font size='1' type='Georgia'><? echo $dir; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px">LOCALIDAD</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><font size='1' type='Georgia'><? echo $ciudad; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">FECHA</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><font size='1' type='Georgia'><? echo $fecha; ?></font></th>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">OPERARIO</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><font size='1' type='Georgia'><? echo $operario; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">EMPRESA</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><font size='1' type='Georgia'><? echo $empresa; ?></font></th>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">TELÉFONO</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><font size='1' type='Georgia'><? echo $tlfo; ?></font></th>
	</tr>
	</table>
	</thead>
				
</table>					
</div>		
			
			
			
<div class="listado" width="100%">
<table border="0" class="opcion_triple">		
					
	<thead>						
	<tr>						
		<th class="titulo2" >Operaciones Correctivas</th>
	</tr>
	</thead>
</table>

<table border="1" class="opcion_triple" style="text-indent:0px;">			
	<thead>						
	<tr>
		<th class="titulo3" width='10%' style="text-align: left;">Nº</th>
		<th class="titulo3" width='40%' style="text-align: left;">Operación correctiva</th>
		<th class="titulo3" width='10%' style="text-align: center;">OK</th>
		<th class="titulo3" width='40%' style="text-align: left;">Observaciones</th>
	</tr>
	</thead>

	<thead>

	<?php

		for($i=1; $i<count($des_op_corr)+1; $i++) {
			echo "<tr>";
			echo "<td width='10%' style='text-align:left;'>".$i."</td>";
			echo "<td width='40%' style='text-align:left;'>".$des_op_corr[$i-1]."</td>";
			echo "<input type='hidden' name='id_op".$i."' value='".$ids_op_corr[$i-1]."'>";
			
			if($correctos[$i-1] == -1) {
				$valor_casilla = -1;
				echo "<td width='10%' align='center'><img id='imagen".$i."' src='./imagenes/iconos/no-imagen.gif'/></td>";
			} else if($correctos[$i-1] == 1) {
				$valor_casilla = 1;
				echo "<td width='10%' align='center'><img id='imagen".$i."' src='./imagenes/iconos/aceptar3.gif'/></td>";
			} else {
				$valor_casilla = 0;
				echo "<td width='10%' align='center'><img id='imagen".$i."' src='./imagenes/iconos/cancelar3.gif'/></td>";
			}
			echo "<input type='hidden' name='casilla".$i."' id='casilla".$i."' value='".$valor_casilla."'>";

			echo "<td width='40%' align='left' colspan='4'><font size='1' type='Georgia'>".$observa[$i-1]."</font></td>";
			echo "</tr>";
		}
	?>
	<input type='hidden' name='n_op' value='<? echo $num_operaciones; ?>'>

	<tr>
		<td colspan='4'>

			<input type="hidden" name="o_corr" value='<? echo $op_corr; ?>'>
			<table border="0" class="opcion_triple" style="text-indent:0px;">	
			<tr height='70'>
				<td width='10%' align='left'><font size='3' type='Georgia'>OBSERVACIONES GENERALES</font></td>
				<td width='90%' colspan='9' align='left'><? if($o_generales == "") { echo '&nbsp'; } else { echo $o_generales; } ?></td>
			</tr>
			</table>

		</td>
	</tr>
	<tr>
		<td colspan='4'>
			
			<div class="opcion_boton">		
			<table width="100%" border='0'>
			<input type='hidden' id='hoja' name='hoja'>
			<input type='hidden' id='id_cen' value='<? echo $id_cen; ?>'>
			<input type='hidden' id='fecha' value='<? echo $fecha; ?>'>
			<input type='hidden' id='op_sel' value='<? for ($i=0;$i<count($op_seleccionadas);$i++) {
				echo $op_seleccionadas[$i]."-"; } ?>'>
		
				<tr>
					<td width='33%' align='center'>
					
					<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="volver_(<? echo $n_hoja; ?>,<? echo $cen; ?>,<? echo $fech; ?>, <? echo $pagina; ?>, <? echo $camp; ?>);" style='cursor:hand;' title='Volver atrás'>
					</td>
				
					<td width='34%' align='center'>
					<INPUT TYPE="button" NAME="imprimir" VALUE="Imprimir" class="boton_big" onClick="impr();" style='cursor:hand;' title='Imprimir hoja actual'>	
					</td>	

					<td width='33%' align='center'>
					<INPUT TYPE="button" NAME="eliminar" VALUE="Eliminar" class="boton_big" onClick='eliminar2();' style='cursor:hand;' title='Eliminar hoja actual'>	
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
</form>



<?php

	}

?>





<form name='viene_de_ventana' method='POST' action='principal?pag=rellenar_correctiva.php'>
	<input type='hidden' name='opera_' id='opera_' value=''>
	<input type='hidden' name='n_' id='n_' value='<? echo $num_hoja; ?>'>
</form>

<form name='viene_de_ventana2' method='POST' action='principal?pag=rellenar_correctiva.php'>
	<input type='hidden' name='opera2_' id='opera2_' value=''>
	<input type='hidden' name='n2_' id='n2_' value='<? echo $num_hoja; ?>'>
</form>





<?php

	//Cerramos la base de datos.
	mysql_close();

?>