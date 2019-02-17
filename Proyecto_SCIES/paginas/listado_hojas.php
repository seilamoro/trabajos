<?php

	//Funci�n que posiciona el cursor tras introducir o borrar una letra en un
	//campo determinado.
	if($_POST['campo_actual']) {
		$campo = $_POST['campo_actual'];
	} else {
		$campo = "num_hoja";
	}
	
	$cadena = "javascript:setSelectionRange(document.enviar_busq.".$campo.", document.enviar_busq.".$campo.".value.length, document.enviar_busq.".$campo.".value.length);";

?>



<head>
<!-- Importamos las hojas de estilos. -->
<link rel="stylesheet" href="../css/listado.css">
<link rel="stylesheet" type="text/css" href="../css/comun.css">
<link rel="stylesheet" type="text/css" href="../css/listados.css">


<!-- T�tulo de la p�gina. -->
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

	//Siempre que se introduce algun caracter nuevo en un campo, se env�a el formulario y la
	//p�gina de inicio es 1.
	function enviar() {
		document.getElementById('pagina').value = 1;
		document.enviar_busq.submit();
	}

	//Conserva el campo actual del cursor.
	function caja(valor) {
		document.getElementById('campo_actual').value = valor;
	}

	//Retrocede una p�gina en el listado.
	function restar_pagina(p) {
		document.getElementById('pagina').value = parseInt(p) - 1;
		document.enviar_busq.submit();
	}


	//Avanza una p�gina en el listado.
	function sumar_pagina(p) {
		document.getElementById('pagina').value = parseInt(p) + 1;
		document.enviar_busq.submit();
	}

	//Funci�n que va a una determinada p�gina del listado, manteniendo los par�metros actuales
	//de la b�squeda.
	function ir_pagina_(p) {
		document.getElementById('pagina').value = p;
		document.enviar_busq.submit();
	}

	//Funci�n para ver los detalles de la hoja correctiva seleccionada.
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
		document.getElementById("centro").value = "";
		document.getElementById("num_hoja").value = "";
		document.getElementById("fecha").value = "";
		window.location.href = window.location.href;
	}

</script>



<!-- Cada vez que se cargue la p�gina, se mantienen los par�metros de b�squeda y la posici�n
	actual del cursor. -->
<BODY onload="<?php echo $cadena ?>">	



<?php

	//Conexi�n a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");

	//Recogemos el n�mero de p�gina al que debemos redireccionarnos
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


	//C�lculo de la p�gina
	$sql = "SELECT hoja_correctiva.*, centro.nombre FROM hoja_correctiva INNER JOIN centro on hoja_correctiva.Id_Centro = centro.Id_Centro WHERE hoja_correctiva.Num_Hoja like '".$_POST['num_hoja']."%' AND hoja_correctiva.Telefono like '".$_POST['tlfo']."%' AND hoja_correctiva.fecha like '".$_POST['fecha']."%' AND centro.nombre like '".$_POST['centro']."%'";
	$result = mysql_query($sql);

	//N�mero de registros totales
	$num = mysql_num_rows($result);

	//N�mero de p�ginas totales completas
	$cociente = $num / 10;

	//N�mero de registros en la �ltima p�gina
	$resto = $num % 10;

	//Calculamos el n�mero de p�ginas totales
	$num_paginas = 0;
	for($i=1; $i<=$cociente; $i++) {
		$num_paginas++;
	}
	if($resto != 0) {
		$num_paginas++;
	}
	
	//Array que guarda los registros por p�gina
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
<form name='enviar_busq' action='principal?pag=listado_hojas.php' method='POST'>
<input type='hidden' id='pagina' name='pagina'>
<thead>
<tr>
	<th width='15%' id="borde_db" class='titulo'><div class="borde"></div>
			<div class="centro"><div>N� HOJA</div>
			
			<div class="intexto"><input type='text' size='5' name='num_hoja' id='num_hoja' onKeyUp='enviar();' onFocus="caja('num_hoja');" value="<?
		if(isset($_POST['num_hoja'])){
			echo $_POST['num_hoja'];
		}?>"></div></div></th>



	<th width='50%' id="borde_db" class='titulo'><div class="borde"></div>
			<div class="centro"><div>INSTALACI�N</div>
			
			<div class="intexto"><input type='text' size='30' name='centro' id='centro' onKeyUp='enviar();' onFocus="caja('centro');" value="<?
		if(isset($_POST['centro'])){
			echo $_POST['centro'];
		}?>"></div></div></th>


	<th width='20%' id="borde_db" class='titulo'><div class="borde"></div>
			<div class="centro"><div>FECHA</div>
			
			<div class="intexto"><input type='text' size='10' name='fecha' id='fecha' onKeyUp='enviar();' onFocus="caja('fecha');" value="<?
		if(isset($_POST['fecha'])){
			echo $_POST['fecha'];
		}?>"></div></div></th>
	

	<th width='15%' id="borde_db" class='titulo'><div class="borde"></div>
			<div class="centro"><div><a title='Limpiar los campos de b�squeda' style='cursor:hand;' onClick='limpiar();'>LIMPIAR CAMPOS</a></div></th>
</tr>

	<input type='hidden' name='campo_actual' id='campo_actual'>

</thead>
<tbody>

<?php

	$limite = ($pagina * 10) - 10;

	$sq = "(SELECT hoja_correctiva.*, centro.nombre FROM hoja_correctiva INNER JOIN centro on hoja_correctiva.Id_Centro = centro.Id_Centro WHERE hoja_correctiva.Num_Hoja like '".$_POST['num_hoja']."%' AND hoja_correctiva.Telefono like '".$_POST['tlfo']."%'  AND hoja_correctiva.fecha like '".$_POST['fecha']."%' AND centro.nombre like '".$_POST['centro']."%') ORDER BY hoja_correctiva.fecha DESC LIMIT ".$limite.",10";
	$resul = mysql_query($sq);
	for ($m=0;$m<mysql_num_rows($resul);$m++){
		$camp = "'".$campo."'";
		$fila = mysql_fetch_array($resul);
		$hoja = $fila['Num_Hoja']; ?>
		<a href="#">
		<tr class='accion' oncontextmenu="mostrarMenu(<? echo $hoja; ?>,<? echo $ho; ?>,<? echo $ce; ?>,<? echo $fe; ?>,<? echo $pagina; ?>, <? echo $camp; ?>);">
		<?
		echo "<td class='accion' id='borde_i'>"; if($fila['Num_Hoja'] == "") { echo '&nbsp'; } else { echo $fila['Num_Hoja']; } echo "</td>";
		
		$s = "SELECT * FROM centro WHERE Id_Centro='".$fila['Id_Centro']."'";
		$r = mysql_query($s);
		for ($k=0;$k<mysql_num_rows($r);$k++){
			$f = mysql_fetch_array($r);
			$nom_centro = $f['Nombre'];	
		} 
		
		?>
	
		<td class='accion'>
		
		<?
		
		if($nom_centro == "") { echo '&nbsp'; } else { echo $nom_centro; } echo "</td>";

		echo "<td class='accion'>"; if($fila['fecha'] == "") { echo '&nbsp'; } else { echo $fila['fecha']; } echo "</td>";
		
		echo "<td class='accion' id='borde_d'>&nbsp;</td>";
		echo "</tr>";
		echo "</a>";
	
	}

	echo "</tbody>";
	
	?>

	
	<tfoot>
		<tr>
			<th colspan="4" class="paginas" id="borde_idb">
			<div id="fleft">

		<?php
		
			if($num_paginas == 0) {
				echo "<br><br><br>";
				echo "No hay registros para estos par�metros de b�squeda";
			} else {
				echo "<br><br><br>";
				echo "P�gina&nbsp";
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

	<?

	echo "</table>";

	?>



<table border='0' style='margin-top:20px;' width='72%'>
	<tr>
		<td width='50%' align='right'>
		<INPUT TYPE="button" NAME="atras" VALUE="Atr�s" class="boton_big" onClick="location.href='principal?pag=mtto_correctivo.php'" style='cursor:hand;' title='Volver a mantenimiento correctivo'>
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

	<tr><td style="padding: 0 0 0 0;">
	<div class="item_izq" style="background:url(./imagenes/botones/menu_borde_izq.gif) no-repeat;"></div>
	<div class="item" align="center" style="width:100px; background:url(./imagenes/botones/menu_fondo.gif) repeat-x;" onclick="modificar_detalles();">Modificar</div>
	<div class="item_der" style="background:url(./imagenes/botones/menu_borde_der.gif) no-repeat;"></div>
	</td></tr>

	<tr><td style="padding: 0 0 0 0;">
	<div class="item_izq" style="background:url(./imagenes/botones/menu_borde_izq.gif) no-repeat;"></div>
	<div class="item" align="center" style="width:100px; background:url(./imagenes/botones/menu_fondo.gif) repeat-x;" onclick="eliminar_();">Eliminar</div>
	<div class="item_der" style="background:url(./imagenes/botones/menu_borde_der.gif) no-repeat;"></div>
	</td></tr>
	</table>

</div>



<script>

	//Funci�n para eliminar una hoja correctiva desde el listado.
	function eliminar_() {
		var decision = confirm("�Est� seguro que desea eliminar la hoja " + document.getElementById('numero').value + "?");
		if(decision == false) {
			return 0;
		} else {
			document.getElementById('enviado').value = "si";
			document.getElementById('eliminar').value = "si";
			//document.getElementById('numero').value = v;
			document.getElementById('elimi_id').submit();
		}
	}
	
	
	function ver_detalles() {
		document.getElementById('tipo_ver').value = "ver";
		document.ver_det.submit();
	}

	function modificar_detalles() {
		document.getElementById('tipo_ver').value = "mod";
		document.ver_det.submit();
	}

	function mostrarMenu(hoja,ho,ce,fe,pagina,camp) {
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
		
		document.getElementById('numero_hoja').value = hoja;
		document.getElementById('numero').value = hoja;
		document.getElementById('viene_de_listado').value = 'si';
		document.getElementById('n_hoja').value = ho;
		document.getElementById('cen').value = ce;
		document.getElementById('fech').value = fe;
		document.getElementById('n_pagina').value = pagina;
		document.getElementById('camp').value = camp;
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