<?PHP session_start();?>
<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">
<?
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
		var i
		//alert(document.formu.op.value);
		
		for (i=0;i<document.getElementsByName('op').length;i++){
			if (document.getElementsByName('op')[i].checked == true)  {
				var cadena = document.getElementsByName('op')[i].value;
				var array_cadena = cadena.split("#");
				
				
				
				window.opener.document.modificar.dni_modificar.value = array_cadena[0];
				
				window.opener.document.modificar.tipo_doc_modificar.value = array_cadena[1];

				//window.opener.document.dar_alta.nacionalidad.options[array_cadena[2]-1].selected = true;
				
				
					var nacionalidad = array_cadena[2];
					for(j=1;j<window.opener.document.modificar.nacionalidad_modificar.length;j++)	{
						if(j == nacionalidad)	{
							window.opener.document.modificar.nacionalidad_modificar.options[j-1].selected = true;
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
					window.opener.diaEx.setComboText(array_cadena[5]);
					window.opener.mesEx.setComboText(meses[parseInt(array_cadena[4])]);
					window.opener.annoEx.setComboText(array_cadena[3]);
					window.opener.diaNa.setComboText(array_cadena[8]);
					window.opener.mesNa.setComboText(meses[parseInt(array_cadena[7])]);
					window.opener.annoNa.setComboText(array_cadena[6]);
		/*			
			window.opener.document.modificar.diaEx.options[parseFloat(array_cadena[5])].selected = true;
                   window.opener.document.modificar.mesEx.options[parseFloat(array_cadena[4])].selected = true;
				//window.opener.document.modificar.annoEx.options[array_cadena[3]-1900].selected = true;
				 for(j=0;j<window.opener.document.modificar.annoEx.length;j++)	{
						if(window.opener.document.modificar.annoEx.options[j].value == array_cadena[3])	{
							window.opener.document.modificar.annoEx.options[j].selected = true;
							break;
						}
						
					}
			
				
				window.opener.document.modificar.diaNa.options[parseFloat(array_cadena[8])].selected = true;
				//window.opener.document.modificar.annoNa.options[array_cadena[6]-1900].selected = true;
				for(j=0;j<window.opener.document.modificar.annoNa.length;j++)	{
						if(window.opener.document.modificar.annoNa.options[j].value == array_cadena[6])	{
							window.opener.document.modificar.annoNa.options[j].selected = true;
							break;
						}
						
					}
				window.opener.document.modificar.mesNa.options[parseFloat(array_cadena[7])].selected = true;*/
				
				window.opener.document.modificar.nombreR.value = array_cadena[9];
				window.opener.document.modificar.ape1.value = array_cadena[10];
				window.opener.document.modificar.ape2.value = array_cadena[11];
				window.opener.document.modificar.telefono.value = array_cadena[12];
				if(array_cadena[13]=="F")
					window.opener.document.modificar.sexo_r[0].checked =true;
				else if(array_cadena[13]=="M")
					window.opener.document.modificar.sexo_r[1].checked =true;
				
				window.close();
			}
		}
	}
</script>
<body onload="<?php echo $cadena ?>" background="../../imagenes/fondo_busqueda_grande.jpg">
		
<center>
<br><br><br>
<form name="formu" id="formu" action="pop_grupos.php" method="POST">
	<input type="hidden" name="campo_actual" id="campo_actual" value="<? echo $_POST['campo_actual']; ?>">
	
	<table border="0" width="97%" align="center">
		<tr align="left" >
			<td width="4%"></td>
			<td width="15%">D.N.I</td>
			<td width="19%">Nombre </td>
			<td width="19%">Primer Apellido</td>
						
						<td align='left' width="25%">Segundo Apellido</td>
		</tr>
		<tr align="left" >
			<td></td>
			<td><input type="text" name="criterio_dni" id="criterio_dni" size="10"   maxlength="30" style="border:solid 1px;" onKeyUp="enviar();" onFocus="caja('dni');" value="<?
								if(isset($_POST['criterio_dni'])){
									echo $_POST['criterio_dni'];
								}?>"></td>
			<td><input type="text" name="criterio_nombre" id="criterio_nombre" size="11" maxlength="20" style="border:solid 1px;" onKeyUp="enviar();" onFocus="caja('nombre');" value="<?
								if(isset($_POST['criterio_nombre'])){
									echo $_POST['criterio_nombre'];
								}?>"></td>
			<td><input type="text" name="criterio_ape1" id="criterio_ape1"  size="15" maxlength="30" style="border:solid 1px;" onKeyUp="enviar();" onFocus="caja('ape1');" value="<?
								if(isset($_POST['criterio_ape1'])){
									echo $_POST['criterio_ape1'];
								}?>"></td>
			<td><input type="text" name="criterio_ape2" id="criterio_ape2" size="17" maxlength="30" style="border:solid 1px;" onKeyUp="enviar();" onFocus="caja('ape2');" value="<?
								if(isset($_POST['criterio_ape2'])){
									echo $_POST['criterio_ape2'];
								}?>"></td>
								</tr>
	</table>
	
	
	

	<?
	@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

			mysql_select_db($_SESSION['conexion']['db']);

			
			$where = " WHERE DNI_Repres LIKE '".$_POST['criterio_dni']."%' and Nombre_Repres  LIKE '".$_POST['criterio_nombre']."%'  and Apellido1_Repres LIKE '".$_POST['criterio_ape1']."%' and Apellido2_Repres LIKE '".$_POST['criterio_ape2']."%'";
			
			$result=mysql_query("select DISTINCT * from estancia_gr $where GROUP BY dni_repres ");
			
			$where2 = " WHERE DNI_CL LIKE '".$_POST['criterio_dni']."%' and Nombre_CL LIKE '".$_POST['criterio_nombre']."%'  and Apellido1_CL LIKE '".$_POST['criterio_ape1']."%' and Apellido2_CL LIKE '".$_POST['criterio_ape2']."%'";
			
			$result2=mysql_query("select DISTINCT * from cliente $where2 GROUP BY dni_cl ");

			
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
			?>
			<div class="tableContainer" style="height:310px;width:97%;">
			<table border="0" width="100%" class="scrollTable"> 
			
			<?
			$contador=0;
			for ($i=0;$i<mysql_num_rows($result);$i++){
				$fila=mysql_fetch_array($result);
				$array_a_ordenar[$i]['tipo']=$fila['Tipo_documentacion'];
				$array_a_ordenar[$i]['dni']=$fila['DNI_Repres'];
				$array_a_ordenar[$i]['nacionalidad']=$fila['Id_Pais_nacionalidad'];
				$array_a_ordenar[$i]['f_naci']=$fila['Fecha_nacimiento'];
				$array_a_ordenar[$i]['f_expe']=$fila['Fecha_Expedicion'];
				$array_a_ordenar[$i]['nombre']=$fila['Nombre_Repres'];
				$array_a_ordenar[$i]['ape1']=$fila['Apellido1_Repres'];
				$array_a_ordenar[$i]['ape2']=$fila['Apellido2_Repres'];
				$array_a_ordenar[$i]['tf']=$fila['Tfno_Repres'];
				$array_a_ordenar[$i]['sexo']="";
				$contador=$contador+1;
			}
			$existe="no";
			$result=mysql_query("select DISTINCT * from estancia_gr $where GROUP BY dni_repres ");
			for ($i=$contador;$i<mysql_num_rows($result2)+$contador;$i++){
				$fila=mysql_fetch_array($result2);
				
				for ($j=0;$j<mysql_num_rows($result);$j++){
				
				    $fila3=mysql_fetch_array($result);
					
					if($fila3['DNI_Repres']==$fila['DNI_Cl'])
						$existe="si";
						
				}
				if($existe=="no"){
				$array_a_ordenar[$i]['tipo']=$fila['Tipo_documentacion'];
				$array_a_ordenar[$i]['dni']=$fila['DNI_Cl'];
				$array_a_ordenar[$i]['nacionalidad']=$fila['Id_Pais'];
				$array_a_ordenar[$i]['f_naci']=$fila['Fecha_Nacimiento_Cl'];
				$array_a_ordenar[$i]['f_expe']=$fila['Fecha_Expedicion'];
				$array_a_ordenar[$i]['nombre']=$fila['Nombre_Cl'];
				$array_a_ordenar[$i]['ape1']=$fila['Apellido1_Cl'];
				$array_a_ordenar[$i]['ape2']=$fila['Apellido2_Cl'];
				$array_a_ordenar[$i]['tf']=$fila['Tfno_Cl'];
				$array_a_ordenar[$i]['sexo']=$fila['Sexo_Cl'];
				}
				$existe="no";
			}
			
			//esta funcion me ordena el array_a_ordenar segun a opcion q le halla dicho
			 function ordenar_array() { 
  $n_parametros = func_num_args(); // Obenemos el número de parámetros 
  if ($n_parametros<3 || $n_parametros%2!=1) { // Si tenemos el número de parametro mal... 
    return false; 
  } else { // Hasta aquí todo correcto...veamos si los parámetros tienen lo que debe ser... 
    $arg_list = func_get_args(); 

    if (!(is_array($arg_list[0]) && is_array(current($arg_list[0])))) { 
      return false; // Si el primero no es un array...MALO! 
    } 
    for ($i = 1; $i<$n_parametros; $i++) { // Miramos que el resto de parámetros tb estén bien... 
      if ($i%2!=0) {// Parámetro impar...tiene que ser un campo del array... 
        if (!array_key_exists($arg_list[$i], current($arg_list[0]))) { 
          return false; 
        } 
      } else { // Par, no falla...si no es SORT_ASC o SORT_DESC...a la calle! 
        if ($arg_list[$i]!=SORT_ASC && $arg_list[$i]!=SORT_DESC) { 
          return false; 
        } 
      } 
    } 
    $array_salida = $arg_list[0]; 

    // Una vez los parámetros se que están bien, procederé a ordenar... 
    $a_evaluar = "foreach (\$array_salida as \$fila){\n"; 
    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada columna... 
      $a_evaluar .= "  \$campo{$i}[] = \$fila['$arg_list[$i]'];\n"; 
    } 
    $a_evaluar .= "}\n"; 
    $a_evaluar .= "array_multisort(\n"; 
    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada elemento... 
      $a_evaluar .= "  \$campo{$i}, SORT_REGULAR, \$arg_list[".($i+1)."],\n"; 
    } 
    $a_evaluar .= "  \$array_salida);"; 
   

    eval($a_evaluar); 
    return $array_salida; 
  } 
} 
$array_ordenadito2 = ordenar_array($array_a_ordenar, dni,  SORT_ASC);


for($i=0;$i<count($array_ordenadito2);$i++){

				$partes = explode("-", $array_ordenadito2[$i]['f_expe']);
				$partes2 = explode("-", $array_ordenadito2[$i]['f_naci']);
				$result2=mysql_query("select * from incidencias WHERE DNI_Inc='".$array_ordenadito2[$i]['dni']."'");
				
				$result_pais=mysql_query("select * from pais ORDER BY Nombre_Pais");
				$j=0;
				
				while ($row_pais=mysql_fetch_array($result_pais)) {
					$j++;
					if ($row_pais['Id_Pais'] == $array_ordenadito2[$i]['nacionalidad']) {
						
						$offset = $j;
					}
				}
				$result_alber=mysql_query("select * from pernocta where DNI_Cl='".$array_ordenadito2[$i]['dni']."' and Fecha_Llegada<='".date("Y-m-d")."' and Fecha_Salida>'".date("Y-m-d")."'");
				$result_pere=mysql_query("select * from pernocta_p where DNI_Cl='".$array_ordenadito2[$i]['dni']."' and Fecha_Llegada<='".date("Y-m-d")."' and Fecha_Salida>'".date("Y-m-d")."'");
				$result_grupo=mysql_query("select * from estancia_gr,componentes_grupo where estancia_gr.Fecha_Llegada=componentes_grupo.Fecha_Llegada and estancia_gr.Nombre_Gr=componentes_grupo.Nombre_Gr and componentes_grupo.DNI='".$array_ordenadito2[$i]['dni']."' and componentes_grupo.Fecha_Llegada<='".date("Y-m-d")."' and Fecha_Salida>'".date("Y-m-d")."'");
				//compruebo q no este pernoctando si esta no lo muestro
				$pernocta="no";
				if(mysql_num_rows($result_pere)!=0){
					$pernocta="si";
				}else if(mysql_num_rows($result_alber)!=0){
					$pernocta="si";
				}else if(mysql_num_rows($result_grupo)!=0){
					$pernocta="si";
				}
				?>
				<tbody style="overflow:auto;font-weight: normal;">
				<?
				if($pernocta=="no"&&$array_ordenadito2[$i]['dni']!=""){
				
				?>
				
				<tr align="left">
				
					<td>
					<input type="radio" name="op" value="<?echo $array_ordenadito2[$i]['dni']?>#<?echo $array_ordenadito2[$i]['tipo']?>#<?echo $offset?>#<?echo $partes[0]?>#<?echo $partes[1]?>#<?echo $partes[2]?>#<?echo $partes2[0]?>#<?echo $partes2[1]?>#<?echo $partes2[2]?>#<?echo $array_ordenadito2[$i]['nombre']?>#<?echo $array_ordenadito2[$i]['ape1']?>#<?echo $array_ordenadito2[$i]['ape2']?>#<?echo $array_ordenadito2[$i]['tf']?>#<?echo $array_a_ordenar[$i]['sexo']?>" ></td>
					<?
					$dni = $array_ordenadito2[$i]['dni'];
			$dni_partido = parteCadenas($dni, 12);
			echo "<td valign='top' width='20%' align='left' class='discrete_text'><font color='gray'>".$dni_partido."</font></td>";
			$nom = $array_ordenadito2[$i]['nombre'];
			$nom_partido = parteCadenas($nom, 10);
			echo "<td valign='top' width='24%' align='left' class='discrete_text'><font color='gray'>".$nom_partido."</font></td>";
			$ap1 = $array_ordenadito2[$i]['ape1'];
			$ap1_partido = parteCadenas($ap1, 11);
			echo "<td valign='top' width='25%' align='left' class='discrete_text'><font color='gray'>".$ap1_partido."</font></td>";

			$ap2 = $array_ordenadito2[$i]['ape2'];
			$ap2_partido = parteCadenas($ap2, 11);
			echo "<td valign='top' width='25%' align='left' class='discrete_text'><font color='gray'>".$ap2_partido."</font></td>";
				$result_incidencia=mysql_query("select * from incidencias where DNI_Inc='".$array_ordenadito2[$i]['dni']."'");
				if (mysql_num_rows($result_incidencia) != 0) {
					?>
					<td><img src="../../imagenes/botones/alerta.gif" alt="Ha provocado alguna incidencia con anterioridad" title="Ha provocado alguna incidencia con anterioridad." height="25px;" width="25px;" /></td>
					<? 
				} else {
					?>
				<td width="24">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<? 
				}
				echo "</tr>";
			}
			}

			?>
			</tbody>
			</table>
			</div>
			<br>
			
	        <a href="#">
			<img src="../../imagenes/botones-texto/aceptar.jpg"  name="boton_aceptar" value="aceptar" onClick="llevar();" border="0" title="Cargar el representante">
			</a>
		</form>
		</center>
		</body>
	
<?PHP
    mysql_close($db);
?>