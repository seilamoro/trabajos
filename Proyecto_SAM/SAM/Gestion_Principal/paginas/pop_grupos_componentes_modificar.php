<?PHP session_start();?>
<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">
<body background="../../imagenes/fondo_busqueda_grande.jpg">
<?
if($_POST['campo_actual']!="")
		$cadena = "javascript:setSelectionRange(document.formu.criterio_".$_POST['campo_actual'].", document.formu.criterio_".$_POST['campo_actual'].".value.length, document.formu.criterio_".$_POST['campo_actual'].".value.length);";

?>
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
		
				function setSelectionRange(input, selectionStart, selectionEnd){
					if (input.setSelectionRange){
						input.focus();
						input.setSelectionRange(selectionStart, selectionEnd);
					}
					else if (input.createTextRange){
						var range = input.createTextRange();
						range.collapse(true);
						range.moveEnd('character', selectionEnd);
						range.moveStart('character', selectionStart);
						range.select();
					}
				}
				function enviar(){
										
					document.getElementById("formu").submit();
				}
				function caja(valor){
					
					document.getElementsByName('campo_actual')[0].value = valor;
				}
				
	function llevar() {
		var i;
		
		for (i=0;i<document.getElementsByName('op').length;i++){
			if (document.getElementsByName('op')[i].checked == true)  {
				var cadena = document.getElementsByName('op')[i].value;
				var array_cadena = cadena.split("#");
				
				
				
				window.opener.document.modificar.dni_componente.value = array_cadena[0];
				
				window.opener.document.modificar.tipo_doc_componente.value = array_cadena[1];

				//window.opener.document.dar_alta.nacionalidad.options[array_cadena[2]-1].selected = true;
					var nacionalidad = array_cadena[2]-1;
					
					for(j=0;j<window.opener.document.modificar.nacionalidad_componente.length;j++)	{
						if(j == nacionalidad)	{
							window.opener.document.modificar.nacionalidad_componente.options[j].selected = true;
							break;
						}
						
					}
					
					for(j=0;j<window.opener.document.dar_alta.nacionalidad_componente.length;j++)	{
						if(j == nacionalidad)	{
							window.opener.document.dar_alta.nacionalidad_componente.options[j].selected = true;
							break;
						}
						
					}
					var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
					window.opener.diaEx_componente.setComboText(array_cadena[5]);
					window.opener.mesEx_componente.setComboText(meses[parseInt(array_cadena[4])]);
					window.opener.annoEx_componente.setComboText(array_cadena[3]);
					window.opener.diaNa_componente.setComboText(array_cadena[8]);
					window.opener.mesNa_componente.setComboText(meses[parseInt(array_cadena[7])]);
					window.opener.annoNa_componente.setComboText(array_cadena[6]);
			/*window.opener.document.modificar.diaEx_componente.options[parseFloat(array_cadena[5])].selected = true;
                  window.opener.document.modificar.mesEx_componente.options[parseFloat(array_cadena[4])].selected = true;
				//window.opener.document.modificar.annoEx_componente.options[array_cadena[3]-1900].selected = true;
				 for(j=0;j<window.opener.document.modificar.annoEx_componente.length;j++)	{
						if(window.opener.document.modificar.annoEx_componente.options[j].value == array_cadena[3])	{
							window.opener.document.modificar.annoEx_componente.options[j].selected = true;
							break;
						}
						
					}
			
		
				window.opener.document.modificar.diaNa_componente.options[parseFloat(array_cadena[8])].selected = true;
				//window.opener.document.modificar.annoNa_componente.options[array_cadena[6]-1900].selected = true;
				for(j=0;j<window.opener.document.modificar.annoNa_componente.length;j++)	{
						if(window.opener.document.modificar.annoNa_componente.options[j].value == array_cadena[6])	{
							window.opener.document.modificar.annoNa_componente.options[j].selected = true;
							break;
						}
						
					}
				window.opener.document.modificar.mesNa_componente.options[parseFloat(array_cadena[7])].selected = true;*/
				window.opener.document.modificar.nombre_componente.value = array_cadena[9];
				window.opener.document.modificar.ape1_componente.value = array_cadena[10];
				window.opener.document.modificar.ape2_componente.value = array_cadena[11];
				
				if(array_cadena[12]=="F"){
				window.opener.document.modificar.sexo[0].checked=true ;
				}else if(array_cadena[12]=="M"){
				window.opener.document.modificar.sexo[1].checked=true ;
				}
				
				window.close();
			}
		}
	}
</script>

		
<center>
<br><br><br>
<form name="formu" id="formu" action="pop_grupos_componentes_modificar.php" method="POST">
<input type="hidden" name="campo_actual" id="campo_actual" >
	<?PHP 
	
	
	

	function parteCadenas($cadena, $num) {
	$cociente = strlen($cadena) / $num;
    $resto = strlen($cadena) % $num;
	
	$nom = $cadena;
	$nom_partido = "";
	
	//Separa las palabras distintas de la línea
	$array_nom = split(" ", $nom);
	for($b=0; $b<count($array_nom); $b++) {
		$cont_act = 0;
		//Separa por guiones
		$array_nom_i = split("-", $array_nom[$b]);
		if(count($array_nom_i) > 1) {
			$guiones = 'si';
		} else {
			$guiones = 'no';
		}
		for($j=0; $j<count($array_nom_i); $j++) {
			$cont_act = $cont_act +1;
					
			if($guiones == 'no') {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=(strlen($array_nom_i[$j]) / $num); $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, strlen($array_nom_i[$j]) % $num);
				} else {
					$nom_partido = $nom_partido. " ".$array_nom_i[$j];
				} 

			} else {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=(strlen($array_nom_i[$j]) / $num); $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, strlen($array_nom_i[$j]) % $num);
				} else {
					if($nom_partido == "") {
						$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
					} else {
						if($cont_act == count($array_nom_i)) {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j];
						} else {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
						}
					}
				} 
			}
		}
	} 
	return $nom_partido;
}
	
if($_GET['grupo_mo']){
	$nombre_grupo=$_GET['grupo_mo'];
	
	
	$fecha_llegada = $_GET['fecha_mo'];
	}else{
	
	$nombre_grupo=$_POST['nombre_grupo'];
	$fecha_llegada = $_POST['fecha_llegada'];
	}
	
	
	?>
	
	<table border="0" width="98%" align="center">
		<tr align="left" >
			<td width="6%"></td>
			<td  width="17%">D.N.I</td>
			<td  width="23%">Nombre </td>
			<td  width="22%">Primer Apellido</td>
						
						<td align='left'>Segundo Apellido</td>
		</tr>
		<tr align="left" >
			<td><input type="hidden" name="nombre_grupo"  id="nombre_grupo" value="<? echo $nombre_grupo; ?>">
			
			
			<input type="hidden" name="fecha_llegada"  id="fecha_llegada" value="<? echo $fecha_llegada; ?>">
			</td>
			<td><input type="text" name="criterio_dni" size="10" style="border:solid 1px;" onFocus="caja('dni');" onkeyup="submit();" value=<?echo $texto?>></td>
			<td><input type="text" name="criterio_nombre" size="11" style="border:solid 1px;" onFocus="caja('nombre');" onkeyup="submit()" value=<?echo $texto_nombre?>></td>
			<td><input type="text" name="criterio_ape1" size="15" style="border:solid 1px;" onFocus="caja('ape1');" onkeyup="submit()" value=<?echo $texto_ape1?>></td>
			<td><input type="text" name="criterio_ape2" size="17" style="border:solid 1px;" onFocus="caja('ape2');" onkeyup="submit()" value=<?echo $texto_ape2?>></td>
		</tr>
	</table>
	<br>

	
	

	<?
		@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

			mysql_select_db($_SESSION['conexion']['db']);

			
			$where = " WHERE DNI_CL LIKE '".$texto."%' and Nombre_CL LIKE '".$texto_nombre."%'  and Apellido1_CL LIKE '".$texto_ape1."%' and Apellido2_CL LIKE '".$texto_ape2."%'";
			
			$result=mysql_query("select DISTINCT * from cliente $where GROUP BY dni_cl ");
			$sql="select DISTINCT * from componentes_grupo where Nombre_Gr ='".$nombre_grupo."' and Fecha_Llegada ='".$fecha_llegada."' GROUP BY dni ";
			
		//echo "select DISTINCT * from cliente $where GROUP BY dni_cl ";
			?>
			<div class="tableContainer" style="height:410px;width:585px;">
			
			<table border="0" width="100%" class="scrollTable"> 
			<tbody style="overflow:auto;font-weight: normal;">
			<?
			
for($i=0;$i<mysql_num_rows($result);$i++) {
		$row2=mysql_fetch_array($result);
$existe="no";
$result2=mysql_query($sql);
		while ($fila2=mysql_fetch_array($result2)) {
					
						if($fila2['DNI']==$row2['DNI_Cl'])
							$existe="si";
							
					}

				$partes = explode("-", $row2['Fecha_Expedicion']);
				$partes2 = explode("-", $row2['Fecha_Nacimiento_Cl']);
				$result2=mysql_query("select * from incidencias WHERE DNI_Inc='".$row2["DNI_CL"]."'");
				$result_pais=mysql_query("select * from pais ORDER BY Nombre_Pais");
				$j=0;
				
				while ($row_pais=mysql_fetch_array($result_pais)) {
					$j++;
					if ($row_pais['Id_Pais'] == $row2['Id_Pais']) {
						
						$offset = $j;
					}
				}
				$result_alber=mysql_query("select * from pernocta where DNI_Cl='".$row2['DNI_Cl']."' and Fecha_Llegada<='".$fecha_llegada."' and Fecha_Salida>'".$fecha_llegada."'");
				$result_pere=mysql_query("select * from pernocta_p where DNI_Cl='".$row2['DNI_Cl']."' and Fecha_Llegada<='".$fecha_llegada."' and Fecha_Salida>'".$fecha_llegada."'");
				$result_grupo=mysql_query("select * from estancia_gr,componentes_grupo where estancia_gr.Fecha_Llegada=componentes_grupo.Fecha_Llegada and estancia_gr.Nombre_Gr=componentes_grupo.Nombre_Gr and componentes_grupo.DNI='".$row2['DNI_Cl']."' and componentes_grupo.Fecha_Llegada<='".$fecha_llegada."' and Fecha_Salida>'".$fecha_llegada."'");
				//compruebo q no este pernoctando si esta no lo muestro
				$pernocta="no";
				if(mysql_num_rows($result_pere)!=0){
					$pernocta="si";
				}else if(mysql_num_rows($result_alber)!=0){
					$pernocta="si";
				}else if(mysql_num_rows($result_grupo)!=0){
					$pernocta="si";
				}
				if($existe=="no"&&$pernocta=="no" && $row2['DNI_Cl']!=""){
			?>
			
			<tr align="left" style="font-weight: normal;">
				
					<td width="2%">
					
					<input type="radio"  name="op" value="<?echo $row2["DNI_Cl"]?>#<?echo $row2["Tipo_documentacion"]?>#<?echo $offset?>#<?echo $partes[0]?>#<?echo $partes[1]?>#<?echo $partes[2]?>#<?echo $partes2[0]?>#<?echo $partes2[1]?>#<?echo $partes2[2]?>#<?echo $row2["Nombre_Cl"]?>#<?echo $row2["Apellido1_Cl"]?>#<?echo $row2["Apellido2_Cl"]?>#<?echo $row2["Sexo_Cl"]?>" ></td>
				
					
				<?	$dni = $row2['DNI_Cl'];
			$dni_partido = parteCadenas($dni, 12);
			echo "<td valign='top' width='19%' align='left' class='discrete_text'><font color='gray'>".$dni_partido."</font></td>";
			$nombre = $row2['Nombre_Cl'];
			$nombre_partido = parteCadenas($nombre, 20);
			echo "<td valign='top'  width='25%' align='left' class='discrete_text'><font color='gray'>".$nombre_partido."</font></td>";
			$ape1 = $row2['Apellido1_Cl'];
			$ape1_partido = parteCadenas($ape1, 20);
			echo "<td valign='top' width='25%' align='left' class='discrete_text'><font color='gray'>".$ape1_partido."</font></td>";
			$ape2 = $row2['Apellido2_Cl'];
			$ape2_partido = parteCadenas($ape2, 20);
			echo "<td valign='top' align='left' class='discrete_text'><font color='gray'>".$ape2_partido."</font></td>";
			?>
					
					
				<?php
				$result_incidencia=mysql_query("select * from incidencias where DNI_Inc='".$row2['DNI_Cl']."'");
				if (mysql_num_rows($result_incidencia) != 0) {
					?>
					<td><img src="../imagenes/botones/alerta.gif" alt="Ha provocado alguna incidencia con anterioridad" title="Ha provocado alguna incidencia con anterioridad." height="25px;" width="25px;" /></td>
					<? 
				} else {
					?>
					<td width="24">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<? 
				}
				echo "</tr></tbody>";
				}
				
				
				}
			
			?>
			</table>
			
			</div>
			<br>
			
	        <a href="#">
			<img src="../../imagenes/botones-texto/aceptar.jpg"  name="boton_aceptar" value="aceptar" onClick="llevar();" border="0" title="Cargar el componente">
			</a>
		</form>
	<?PHP
    mysql_close($db);
?>
