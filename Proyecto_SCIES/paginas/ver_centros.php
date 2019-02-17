<?php

	//Función que posiciona el cursor tras introducir o borrar una letra en un
	//campo determinado.
	if($_POST['campo_actual']) {
		$campo = $_POST['campo_actual'];
	} else {
		$campo = "nombre";
	}
	
	$cadena = "javascript:setSelectionRange(document.enviar_busq.".$campo.", document.enviar_busq.".$campo.".value.length, document.enviar_busq.".$campo.".value.length);";

?>



<head>
<!-- Importamos las hojas de estilos. -->
<!--<link rel="stylesheet" href="../css/listado.css">
<link rel="stylesheet" type="text/css" href="../css/comun.css">
<link rel="stylesheet" type="text/css" href="../css/listados.css">-->


<!-- Título de la página. -->
<title>LISTADO DE HOJAS CORRECTIVAS</title>
</head>



<script>

	//Calcula lo que hay escrito en cada campo de texto para luego poder posicionar el cursor.
	function setSelectionRange(input, selectionStart, selectionEnd){
		if (input.setSelectionRange){
			input.focus();
			input.setSelectionRange(selectionStart, selectionEnd);
		} else if (input.createTextRange){
			var range = input.createTextRange();
			range.collapse(true);
			range.moveEnd('character', selectionEnd);
			range.moveStart('character', selectionStart);
			range.select();
		}
	}

	//Siempre que se introduce algun caracter nuevo en un campo, se envía el formulario y la
	//página de inicio es 1.
	function enviar() {
		document.getElementById('pagina').value = 1;
		document.enviar_busq.submit();
	}

	//Conserva el campo actual del cursor.
	function caja(valor) {
		document.getElementById('campo_actual').value = valor;
	}

	//Retrocede una página en el listado.
	function restar_pagina(p) {
		document.getElementById('pagina').value = parseInt(p) - 1;
		document.enviar_busq.submit();
	}


	//Avanza una página en el listado.
	function sumar_pagina(p) {
		document.getElementById('pagina').value = parseInt(p) + 1;
		document.enviar_busq.submit();
	}

	//Función que va a una determinada página del listado, manteniendo los parámetros actuales
	//de la búsqueda.
	function ir_pagina_(p) {
		document.getElementById('pagina').value = p;
		document.enviar_busq.submit();
	}

	//Función para ver los detalles de la hoja correctiva seleccionada.
	function comprobar_ver(h, ho, c, f, p, camp) {
		document.getElementById('tipo_ver').value = "ver";
		document.getElementById('numero_hoja').value = h;
		document.getElementById('viene_de_listado').value = 'si';
		document.getElementById('n_hoja').value = ho;
		document.getElementById('cen').value = c;
		document.getElementById('fech').value = f;
		document.getElementById('n_pagina').value = p;
		document.getElementById('camp').value = camp;
		document.ver_det.submit();
	}

	function limpiar() {
		document.getElementById("nombre").value = "";
		document.getElementById("localidad").value = "";
		document.getElementById("tlfo").value = "";
		window.location.href = window.location.href;
	}

</script>



<!-- Cada vez que se cargue la página, se mantienen los parámetros de búsqueda y la posición
	actual del cursor. -->
<BODY onload="<?php echo $cadena ?>">	



<?php

	//Conexión a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");

	//Recogemos el número de página al que debemos redireccionarnos
	if($_POST['n_pagina']) {
		$pagina = $_POST['n_pagina'];
	} else {
		if($_POST['pagina']) {
			$pagina = $_POST['pagina'];
		} else {
			$pagina = 1;
		}
	}

	
	if($_POST['num_hoja']) {$ho = $_POST['num_hoja'];} else {$ho = "''";}
	if($_POST['centro']) {$ce = "'".$_POST['centro']."'";} else {$ce = "''";}
	if($_POST['fecha']) {$fe = $_POST['fecha'];} else {$fe = "''";}


	//Cálculo de la página
	$sq = "SELECT * FROM centro WHERE Nombre like '".$_POST['nombre']."%' AND Localidad like '".$_POST['localidad']."%' AND Telefono like '".$_POST['tlfo']."%'";

	$result = mysql_query($sq);

	//Número de registros totales
	$num = mysql_num_rows($result);

	//Número de páginas totales completas
	$cociente = $num / 10;


	//Número de registros en la última página
	$resto = $num % 10;


	//Calculamos el número de páginas totales
	$num_paginas = 0;
	for($i=1; $i<=$cociente; $i++) {
		$num_paginas++;
	}
	if($resto != 0) {
		$num_paginas++;
	}
	
	//Array que guarda los registros por página
	$array_paginas = array();
	for($i=1; $i<=$cociente; $i++) {
		$array_paginas[$i] = 10;
		$aux = $i;
	}
	$array_paginas[$aux+1] = $resto;

?>

<form name='elimi' id='elimi_id' action='principal?pag=mtto_correctivo.php' method='POST'>
	<input type='hidden' name='enviado' id='enviado'>
	<input type='hidden' name='eliminar' id='eliminar'>
	<input type='hidden' name='numero' id='numero'>
</form>


<form name='ver_det' action='principal?pag=rellenar_correctiva.php' method='POST'>
	<input type='hidden' name='tipo_ver' id='tipo_ver'>
	<input type='hidden' name='numero_hoja' id='numero_hoja'>
	<input type='hidden' name='viene_de_listado' id='viene_de_listado'>
	<input type='hidden' name='n_hoja' id='n_hoja'>
	<input type='hidden' name='cen' id='cer'>
	<input type='hidden' name='fech' id='fech'>
	<input type='hidden' name='n_pagina' id='n_pagina'>
	<input type='hidden' name='camp' id='camp'>
</form>



<table border='0' width='70%' align='center' class='listados'>
<form name='enviar_busq' action='principal?pag=ver_centros.php' method='POST'>
<input type='hidden' id='pagina' name='pagina'>
<input type='hidden' name='centro_' id='centro_' value="">
<thead>
<tr>
	<th width='10%' id="borde_db" class='titulo'><div class="borde"></div>
		<div class="centro"><div>FOTO</div></th>



	<th width='35%' id="borde_db" class='titulo'><div class="borde"></div>
		<div class="centro"><div>INSTALACIÓN</div>
			
			<div class="intexto"><input type='text' size='30' name='nombre' id='nombre' onKeyUp='enviar();' onFocus="caja('nombre');" value="<?
		if(isset($_POST['nombre'])){
			echo $_POST['nombre'];
		}?>"></div></div></th>


	<th width='21%' id="borde_db" class='titulo'><div class="borde"></div>
		<div class="centro"><div>LOCALIDAD</div>
			
			<div class="intexto"><input type='text' size='10' name='localidad' id='localidad' onKeyUp='enviar();' onFocus="caja('localidad');" value="<?
		if(isset($_POST['localidad'])){
			echo $_POST['localidad'];
		}?>"></div></div></th>
	

	<th width='19%' id="borde_db" class='titulo'><div class="borde"></div>
		<div class="centro"><div>TELÉFONO</div><div class="intexto"><input type='text' size='10' name='tlfo' id='tlfo' onKeyUp='enviar();' onFocus="caja('tlfo');" value="<?
		if(isset($_POST['tlfo'])){
			echo $_POST['tlfo'];
		}?>"></div></div></th>

	<th width='15%' id="borde_db" class='titulo'><div class="borde"></div>
			<div class="centro"><div><a title='Limpiar los campos de búsqueda' style='cursor:hand;' onClick='limpiar();'>LIMPIAR CAMPOS</a></div></th>
</tr>

	<input type='hidden' name='campo_actual' id='campo_actual'>

</thead>
<tbody>

<?php

	$limite = ($pagina * 10) - 10;

	$sq = "SELECT * FROM centro WHERE Nombre like '".$_POST['nombre']."%' AND Localidad like '".$_POST['localidad']."%'  AND Telefono like '".$_POST['tlfo']."%' ORDER BY Id_Centro ASC LIMIT ".$limite.",10";
	$resul = mysql_query($sq);

	for ($m=0;$m<mysql_num_rows($resul);$m++){
		$camp = "'".$campo."'";
		$fila = mysql_fetch_array($resul);
		$hoja = $fila['Num_Hoja']; 
		$id_cen = $fila['Id_Centro'];
		$n_cen = $fila['Id_Centro'];
		//if($n_cen < 10) {
			//$n_cen = "0".$n_cen;
		//} ?>
		<a href="#">
		<tr class='accion' oncontextmenu="mostrarMenu(<? echo $id_cen; ?>);">
	

		<?php
			if(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.gif")) {		
		?>		
				<td class='accion' id='borde_i'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.gif" WIDTH="26" HEIGHT="15" style='cursor:hand;' title='Ver datos del centro' onClick="abrir_ampliacion(<? echo $id_cen; ?>);"></td>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.bmp")) {
		?>
				<td class='accion' id='borde_i'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.bmp" WIDTH="26" HEIGHT="15" style='cursor:hand;' title='Ver datos del centro' onClick="abrir_ampliacion(<? echo $id_cen; ?>);"></td>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.jpeg")) {
		?>
				<td class='accion' id='borde_i'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.jpeg" WIDTH="26" HEIGHT="15" style='cursor:hand;' title='Ver datos del centro' onClick="abrir_ampliacion(<? echo $id_cen; ?>);"></td>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.jpg")) {
		?>
				<td class='accion' id='borde_i'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.jpg" WIDTH="26" HEIGHT="15" style='cursor:hand;' title='Ver datos del centro' onClick="abrir_ampliacion(<? echo $id_cen; ?>);"></td>
		<?
			} elseif(file_exists("imagenes/fotos/Centro".$id_cen."/Centro". $id_cen."_1.png")) {
		?>
				<td class='accion' id='borde_i'><IMG SRC="imagenes/fotos/Centro<? echo $id_cen; ?>/Centro<? echo $id_cen; ?>_1.png" WIDTH="26" HEIGHT="15" style='cursor:hand;' title='Ver datos del centro' onClick="abrir_ampliacion(<? echo $id_cen; ?>);"></td>
		<?
			} else {
		?>
				<td class='accion' id='borde_i'><IMG SRC="imagenes/fotos/NULL.jpg" WIDTH="26" HEIGHT="15" style='cursor:hand;' title='Ver datos del centro' onClick="abrir_ampliacion(<? echo $id_cen; ?>);"></td>
		<?
			}  
		
		
		//echo "<td class='accion' id='borde_i'><img src='./imagenes/centros/centro".$n_cen.".jpg' height='15px' width='26px' style='cursor:hand;' title='Ver datos del centro' onClick='abrir_ampliacion(".$id_cen.");' ></td>";
		
		
		
		
		$nombre = substr($fila['Nombre'],0,28);
		echo "<td class='accion' title='".$fila['Nombre']."'>".$nombre."</td>";
		$loc = substr($fila['Localidad'],0,15);
		echo "<td class='accion' title='".$fila['Localidad']."'>".$loc."</td>";
		echo "<td class='accion'>".$fila['Telefono']."</td>";
		echo "<td class='accion' id='borde_d'>&nbsp;</td>";

		echo "</tr>";
		echo "</a>";
	}

	echo "</tbody>";
	
	?>


	<tfoot>

		<tr>
			<th colspan="5" class="paginas" id="borde_idb">
			<div id="fleft">

			<?php
		
			if($num_paginas == 0) {
				echo "<br><br><br>";
				echo "No hay registros para estos parámetros de búsqueda";
			} else {
				echo "<br><br><br>";
				echo "Página&nbsp";
				for ($i=1; $i<=$num_paginas; $i++) {
					if(($i % 17) == 0) {
						echo "<br>";
					}
					if($pagina == $i) {
						echo "<a class='seleccionada' style='cursor:hand;'>&nbsp".$i."</a>";
					} else {
						echo "<a class='noseleccionada' style='cursor:hand;' onClick='ir_pagina_(".$i.");'>&nbsp".$i."</a>";
					}
				}
			}

			if (($ventana_paginas > 1) && ($conf['resultado_sql']['mostrar_ventana_paginas'] < $ventana_paginas)) {

			?>

				<a class="noseleccionada" href="#" onclick="cambiar_ventana_paginas(<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas'] + 1; ?>);">...></a>

			<?php
		
			}
	
			?>

			</div>
			</th>
			
		</tr>
		
	</tfoot>

<?php

	echo "</table>";

?>



<table border='0' style='margin-top:20px;' width='72%'>
	<tr>
		<td width='50%' align='right'>
		<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal?pag=mtto_correctivo.php'" style='cursor:hand;' title='Volver a mantenimiento correctivo'>
		</td>	
	</tr>
</table>	



<div id="menu" style="position:absolute;visibility:hidden;padding:0 0 0 0;margin:0px;">
	<div class="item_esquina" style="background:url('./imagenes/botones/menu_esquina.gif') no-repeat;"></div>
	
	<table cellpadding=0 cellspacing=0>
	<tr><td style="padding: 0 0 0 0;">
	<div class="item_izq" style="background:url(./imagenes/botones/menu_borde_izq.gif) no-repeat;"></div>
	<div class="item" align="center" style="width:100px; background:url(./imagenes/botones/menu_fondo.gif) repeat-x;" onclick="ver_detalles();">Ver</div>
	<div class="item_der" style="background:url(./imagenes/botones/menu_borde_der.gif) no-repeat;"></div>
	</td></tr>
	</table>

</div>





<script>

	//Abre una ventana pequeña y muestra información del centro seleccionado.
	function abrir_ampliacion(cen) {
		window.open( "./paginas/ampliacion_centro?id_cen="+cen, "_blank", "width=450px,height=500px,resizable=yes,scrollbars=no,location=no,status=no,menubar=no");
	}

	//Función para eliminar una hoja correctiva desde el listado.
	function eliminar_() {
		var decision = confirm("¿Está seguro que desea eliminar la hoja " + document.getElementById('numero').value + "?");
		if(decision == false) {
			return 0;
		} else {
			document.getElementById('enviado').value = "si";
			document.getElementById('eliminar').value = "si";
			//document.getElementById('numero').value = v;
			document.getElementById('elimi_id').submit();
		}
	}
	
	//Pasa a la página donde se muestran las últimas seis revisiones del centro actual
	function ver_detalles(centro) {
		location.href = 'principal?pag=visualizar_centro.php&centro=' + document.getElementById('centro_').value;
	}

	function modificar_detalles() {
		document.getElementById('tipo_ver').value = "mod";
		document.ver_det.submit();
	}

	function mostrarMenu(ce) {
		var rightedge = document.body.clientWidth - event.clientX;
		//alert(rightedge);
		var bottomedge = document.body.clientHeight - event.clientY;
		//alert(bottomedge);
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
		document.getElementById('centro_').value = ce;
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
	setSelectionRange(document.enviar_busq.<? echo $campo; ?>, document.enviar_busq.<? echo $campo; ?>.value.length, document.enviar_busq.<? echo $campo; ?>.value.length);

</script>





<?php

	//Cerramos la base de datos.
	mysql_close();

?>

</BODY>