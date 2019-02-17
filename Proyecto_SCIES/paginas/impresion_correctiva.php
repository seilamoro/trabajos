<script language='javascript'>

	//Sitúa el foco de la aplicación en el campo número.
	function foco() {
		document.getElementById("num").focus();
	}

	//Manda los parámtros a la siguiente página (ventala_impresion_correctiva.php) y abre
	//el cuadro de impresión.
	function comprobar(valor, num) {
		if(document.getElementById("num").value.length == 0) {
			alert("Tiene que escribir el número de hoja");
			document.getElementById("num").focus();
			return 0;
		} else {	
			if(valor != num) {
				var decision = confirm("El número de hoja asignado por el sistema ha cambiado. En caso de continuar los datos se sobrescribirán en la base de datos y podría borrarse una hoja de mantenimiento existente. ¿Desea continuar?");
				if(decision == false) {
					return 0;
				} else {
					var cen = document.getElementById('id_cen').value;
					var fech = document.getElementById('fecha').value;
					var op = document.getElementById('op_sel').value;
					var h = valor;
				
					window.open( "./paginas/ventana_impresion_correctiva?id_cen="+cen+"&fecha="+fech+"&op_sel="+op+"&hoja="+h, "_blank", "resizable=yes,scrollbars=yes,location=no,status=no,menubar=no");
				}
			} else {
				var decision = confirm("Se guardará la hoja en la base de datos. ¿Desea continuar?");
				if(decision == false) {
					return 0;
				} else {
					var cen = document.getElementById('id_cen').value;
					var fech = document.getElementById('fecha').value;
					var op = document.getElementById('op_sel').value;
					var h = valor;
				
					window.open( "./paginas/ventana_impresion_correctiva?id_cen="+cen+"&fecha="+fech+"&op_sel="+op+"&hoja="+h, "_blank", "resizable=yes,scrollbars=yes,location=no,status=no,menubar=no");
				}
			}
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


	$id_cen = $_POST['centro'];
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


	//Recogemos la fecha.
	$fecha = $_POST['fecha'];

	$op_seleccionadas = $_POST['op_seleccionadas'];
	/*for($i=1; $i<count($op_seleccionadas)+1; $i++) {
		echo $op_seleccionadas[$i-1]."<br>";
	}*/

	$ids_prev = $_POST['ids_p'];
	$ids_comp = $_POST['ids_comp'];
	$ids_subcomp = $_POST['ids_subcomp'];

	//Recogemos el número de hoja.
	if($_POST['hoja']) {
		$num_hoja = $_POST['hoja'];
	} else {
		//Conexión a la base de datos. 
		@ $db2 = mysql_pconnect("localhost" , "root" , "");
		if (!$db2){
			echo "Error: No se ha podido conectar a la base de datos";
			exit;
		}
		
		//Calcula el número de hoja que corresponde.
		mysql_select_db("information_schema");
		$sql2 = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_NAME = 'hoja_correctiva'";
		$result = mysql_query($sql2);
		for ($m=0;$m<mysql_num_rows($result);$m++){
			$fila = mysql_fetch_array($result);
			$num_hoja = $fila['AUTO_INCREMENT'];
			$num_correcto = $num_hoja;
		}
	}

?>

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
		<th class='titulo3_centro' width="20%" colspan='2' align="left"><input type='text' id='num' size='10' style="background:transparent;" value='<? echo $num_hoja; ?>'></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan='3' rowspan="3" width="30%" style="text-align:left; text-indent:0px;" valign="center">INSTALACIÓN</th>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px;" class='titulo3_centro'>NOMBRE</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><font size='1' type='Georgia'><? echo $nombre; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px">UBICACIÓN</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><font size='1' type='Georgia'><? echo $ubicacion; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' colspan="3" width="30%" style="text-align:left; text-indent:0px">LOCALIDAD</th>
		<th class='titulo3_centro' colspan="4" width="40%" style="text-align:left; text-indent:0px"><font size='1' type='Georgia'><? echo $localidad; ?></font></th>
	</tr>

	<tr>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">FECHA</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'><font size='1' type='Georgia'><? echo $fecha; ?></font></th>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">OPERARIO</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'>&nbsp;</th>
	</tr>

	<tr>
		<th class='titulo3_centro' width="20%" colspan='2' style="text-align:left; text-indent:0px">EMPRESA</th>
		<th class='titulo3_centro' width="30%" style="text-align:left; text-indent:0px" colspan='3'>&nbsp;</th>
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

		for($i=1; $i<count($op_seleccionadas)+1; $i++) {
			echo "<tr>";
			echo "<td width='10%' style='text-align:left;'>".$i."</td>";
			echo "<td width='40%' style='text-align:left;'>".$op_seleccionadas[$i-1]."</td>";
			echo "<td width='10%' aligne='center'>&nbsp</td>";
			echo "<td width='40%' colspan='4' align='left'>&nbsp;</td>";
			echo "</tr>";
		}

	?>

	<tr>
		<td colspan='4'>

			<table border="0" class="opcion_triple" style="text-indent:0px;">	
			<tr height='70'>
				<td width='10%' align='left'><font size='3' type='Georgia'>OBSERVACIONES GENERALES</font></td>
				<td width='90%' colspan='9' align='left'>&nbsp;</td>
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
					<td width='50%' align='center'>
					<INPUT TYPE="button" NAME="atras" VALUE="Atrás" class="boton_big" onClick="location.href='principal.php?pag=op_correctivas.php&fecha=<? echo $fecha; ?>&id_cen=<? echo $id_cen; ?>&ids_prev=<? echo $ids_prev; ?>&ids_comp=<? echo $ids_comp; ?>&ids_subcomp=<? echo $ids_subcomp; ?>';" style='cursor:hand;' title='Volver atrás'>
					</td>
				
					<td width='50%' align='center'>
					<INPUT TYPE="button" NAME="imprimir" VALUE="Imprimir" class="boton_big" onClick="comprobar(document.getElementById('num').value,<? echo $num_correcto; ?>);" style='cursor:hand;' title='Imprimir hoja de revisión'>	
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





<script>
	foco();
</script>


<?php

	//Cerramos la base de datos.
	mysql_close();

?>