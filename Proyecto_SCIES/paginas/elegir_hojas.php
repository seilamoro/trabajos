<script language='javascript'>


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
			alert("No ha seleccionado ninguna hoja");
			return 0;
		}

		if((sel + document.getElementById("op_seleccionadas").length) > 6) {
			alert("No es posible seleccionar más de seis hojas");
			return 0;
		}
		
		for(var i=sel-1; i>-1; i--) {
			var ind = arr[i];
			document.getElementById("op_seleccionadas").options[cont] = new 		  	Option(document.getElementById("op_disponibles").options[ind].title, document.getElementById("op_disponibles").options[ind].value);
			
			document.getElementById("op_seleccionadas").options[cont].title = document.getElementById("op_disponibles").options[ind].title;
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
			alert("No ha seleccionado ninguna hoja");
		}
		
		for(var i=sel-1; i>-1; i--) {
			var ind = arr[i];
			document.getElementById("op_disponibles").options[cont] = new 		  	Option(document.getElementById("op_seleccionadas").options[ind].title, document.getElementById("op_seleccionadas").options[ind].value);
			
			document.getElementById("op_disponibles").options[cont].title = document.getElementById("op_seleccionadas").options[ind].title;
			document.getElementById("op_seleccionadas").options[ind] = null;
			cont++; 
		}
	}

	//Función que envía el formulario junto con todas las operaciones que hay en ese
	//momento en el cuadro "seleccionadas".
	function comprobar(num) {
		if(document.getElementById("op_seleccionadas").length == 0) {
			alert("No ha seleccionado ninguna hoja");
		} else {
			for(var i=0; i<document.getElementById("op_seleccionadas").length; i++) {
				document.getElementById("op_seleccionadas").options[i].selected = true;
			}
			document.getElementById("centro").value = num;
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

	$sq = "SELECT * from centro WHERE Id_Centro = '".$_GET['centro']."'";
	$result = mysql_query($sq);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
		$nombr_cen = $fila['Nombre'];
	}

	$hojas_numero = array();
	$hojas_fecha = array();
	$sq = "SELECT * FROM hoja_correctiva WHERE Id_Centro = '".$_GET['centro']."' ORDER BY fecha DESC";
	$result = mysql_query($sq);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
		$hojas_numero[] = $fila['Num_Hoja'];
		$hojas_fecha[] = $fila['fecha'];
	}


	$hojas_fechas = array();
	for($n=0; $n<count($hojas_fecha); $n++) {
		$f = split("-", $hojas_fecha[$n]);
		$f_nueva = $f[1]."-".$f[0];
		$hojas_fechas[] = $f_nueva;
	}
	$hojas_fecha = $hojas_fechas;

?>



<!-- Segundo bloque. -->
<div class="pagina" style="margin-top:50px;">
<div class="listado1" id='segundo_div'>
	
<div class="listado" width="100%">	
<div class="titulo1"><? echo $nombr_cen; ?></div>					
			
<table border="0" class="opcion_triple">		
	<thead>						
	<tr>						
		<th class="titulo2" colspan="2">Seleccionar Hojas de la Instalación</th>											
	</tr>
	</thead>				
</table>					
	
			

<!-- Formulario que envía las operaciones correctivas seleccionadas a "impresion_correctiva.php". -->
<table border="1" class="opcion_triple" style="text-indent:0px;">	
<form name="opera_corr" action='principal.php?pag=visualizar_centro.php&centro=<? echo $_GET['centro']; ?>&elegir=si' method='POST'>
<input type="hidden" name="centro" id="centro" value=''>
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
						
				for ($m=0; $m<count($hojas_numero); $m++){
					$descripcion = "Hoja ".$hojas_numero[$m].", del ".$hojas_fecha[$m];
					echo "<option value='".$hojas_numero[$m]."' title='".$descripcion."'>".$descripcion."</option>";
				}

			?>	
    				
			</select>
		</th>
		
		<th>
			<br><input title="Añadir hoja a seleccionadas" style="cursor:hand;" class='boton_small' type="button" value="   >   " onClick='seleccionar();' name="B1">
			<br>
			<br>
			<br><input title="Quitar hoja de seleccionadas" style="cursor:hand;" class='boton_small' type="button" value="   <   " onClick='deseleccionar();' name="B2">
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
						<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal?pag=visualizar_centro.php&centro=<? echo $_GET['centro']; ?>'" style='cursor:hand;' title='Volver atrás'>
					</td>
				
					<td width='50%' align='center'>
						<INPUT TYPE="button" NAME="seleccionar_" VALUE="Seleccionar" class="boton_big" onClick="comprobar(<? echo $_GET['centro']; ?>);" style='cursor:hand;' title='Seleccionar hojas'>	
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




											
<?php

	//Cerramos la base de datos.
	mysql_close();

?>