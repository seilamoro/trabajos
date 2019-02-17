<?php
	SESSION_START();
?>
	
<!-- SAM. Búsqueda de clientes para incidencias.	
	 Autor. José Luis Gutiérrez Fernández.
	 Fecha. Octubre 2006. -->


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<link rel="stylesheet" type="text/css" href="../css/hoja_principal_azul.css">
<link rel="stylesheet" type="text/css" href="../css/estilos_tablas.css">
<link rel="stylesheet" type="text/css" href="../css/habitaciones.css">
<link rel="stylesheet" type="text/css" href="../css/hoja_formularios.css">
<link rel="stylesheet" type="text/css" href="../css/estructura_alb_per.css">
<link rel="stylesheet" type="text/css" href="../css/habitacionesColores.css">
<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">



<?php

	function parteCadenas($cadena, $num) {
	$nom = $cadena;
	$nom_partido = "";
	
	//Separa las palabras distintas de la línea
	$array_nom = split(" ", $nom);
	for($u=0; $u<count($array_nom); $u++) {
		if(strlen($array_nom[$u]) > $num) {
			$palabras_largas = 'si';
		} else {
			$palabras_largas = 'no';
		}
	}

	if($palabras_largas == 'si') {
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
					
			$resto = strlen($array_nom_i[$j]) % $num;
			$cociente = (strlen($array_nom_i[$j]) - $resto) / $num;
			
			
			if($guiones == 'no') {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					$nom_partido = $nom_partido. " ".$array_nom_i[$j];
				} 

			} else {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
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
	} else {
		return $cadena;
	}
} 

?>




<?php
	//Cogemos los valores del nombre del grupo y la fecha de llegada, y los almacenamos en 
	//variables de sesión, ya que al meter una letra en un campo de texto los valores GET
	//se pierden.  $_SESSION['ff'] tiene la fecha de llegada y $_SESSION['gg'] tiene el nombre
	//del grupo. Ambos valores constituyen la clave primaria de componentes_grupo.
	if( ($_GET['f']) && ($_GET['g']) ) {
		$_SESSION['ff'] = $_GET['f'];
		$_SESSION['gg'] = $_GET['g'];
		if( ($_SESSION['ff'] == 'no') && ($_SESSION['gg'] == 'no') ) {
			$_SESSION['normal'] = 'si';
		} else {
			$_SESSION['normal'] = 'no';
		}
	} else {
		if($_SESSION['normal'] == 'si') {
			$_SESSION['normal'] = 'si';
		} else {
			$_SESSION['normal'] = 'no';
		}
	}
	
	//Código para posicionar el cursor tras introducir una nueva letra en algún campo de texto.
	$control = 0;
	
	if($_POST['campo_actual']) {
		$campo = $_POST['campo_actual'];
	} else {
		$campo = "dni";
	}

	$cadena = "javascript:setSelectionRange(document.forms[0].criterio_".$campo.", document.forms[0].criterio_".$campo.".value.length, document.forms[0].criterio_".$campo.".value.length);";

?>



<!-- Estilos necesarios para el cuadro de búsqueda. -->
<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">




<script language="Javascript">

//Envía el formulario actual.
function enviar(evt){						
	document.forms[0].submit();
}
				

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


//Pasa los datos desde los campos de texto.
function pasa_datos(){
	var num;
	for(i=0;i<document.getElementsByName('select_cliente').length;i++){
		if(document.getElementsByName('select_cliente')[i].checked){
			num = document.getElementsByName('select_cliente')[i].value;
			break;
		}
	}	
							
	window.opener.document.nueva.dni_nueva.value = document.getElementsByName("dni"+String(num))[0].value;
	
	var nombre = document.getElementsByName("nombre"+String(num))[0].value;
	window.opener.document.nueva.nombre_nueva.value = nombre;
	var apellido1 = document.getElementsByName("apellido1"+String(num))[0].value;
	window.opener.document.nueva.apellido1_nueva.value = apellido1;
	var apellido2 = document.getElementsByName("apellido2"+num)[0].value;
	window.opener.document.nueva.apellido2_nueva.value = apellido2;
	window.close();
}	


//Conserva los datos en los campos de texto mientras se escribe.
function caja(valor){
	document.getElementsByName('criterio_dni')[0].value = document.getElementsByName('criterio_dni')[0].value;
	document.getElementsByName('campo_actual')[0].value = valor;
}

</script>
		


<TITLE> Búsqueda de Clientes </TITLE>
		
<?
	//Conecta a la base de datos.
	//@$db = mysql_pconnect("localhost","root","");
	@$db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
	if($db) {
?>		
</HEAD>

<BODY onload="<?php echo $cadena ?>" style="background-image:url('../../imagenes/fondo_busqueda_grande.jpg');">	

<center>

<div id='criterios'><br><br>
<form action='inc_busq_dni.php' method='POST'>
<input type='hidden' name='form_destino' value='<?
	if(isset($_GET['form']) && $_GET['form'] != "") {
		echo $_GET['form'];
	} else {
		if(isset($_POST['form_destino']) && $_POST['form_destino'] != ""){ 
			echo $_POST['form_destino'];
		}
	}				
?>'>		
<br><br><br>
</div>


<?

	//Si es una incidencia que no sea de un grupo
	if($_SESSION['normal'] == 'si') {
		//mysql_select_db("sam");
		mysql_select_db($_SESSION['conexion']['db']);
		
		//Si se ha introducido algún apellido los grupos ya no se comprueban.
		if($_POST['criterio_ape2'] || $_POST['criterio_ape1']) {
			if($_POST['criterio_ape2']) {
				$sql = "(select DNI_Cl,Nombre_Cl,Apellido1_Cl,Apellido2_Cl from cliente where DNI_Cl like '".$_POST['criterio_dni']."%' and Nombre_Cl like '".$_POST['criterio_nombre']."%' and Apellido1_Cl like '".$_POST['criterio_ape1']."%'  and Apellido2_Cl like '".$_POST['criterio_ape2']."%') UNION (select DNI,Nombre,Apellido1,Apellido2 from componentes_grupo where DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%'  and Apellido2 like '".$_POST['criterio_ape2']."%')"; 		
				$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
			} else {
				$sql = "(SELECT DNI_Cl,Nombre_Cl,Apellido1_Cl,Apellido2_Cl FROM cliente WHERE DNI_Cl like '".$_POST['criterio_dni']."%' and Nombre_Cl like '".$_POST['criterio_nombre']."%' and Apellido1_Cl like '".$_POST['criterio_ape1']."%') UNION (SELECT DNI,Nombre,Apellido1,Apellido2 FROM componentes_grupo WHERE DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%')"; 
				$sql_incidencias = "select DNI_Inc from incidencias where DNI_Inc like '".$_POST['criterio_dni']."%'";
			}
		
		//Si no se ha introducido ningún apellido los grupos cuentan.
		} else {
			if($_POST['criterio_ape2']) {
				$sql = "(select DNI_Cl,Nombre_Cl,Apellido1_Cl,Apellido2_Cl from cliente where DNI_Cl like '".$_POST['criterio_dni']."%' and Nombre_Cl like '".$_POST['criterio_nombre']."%' and Apellido1_Cl like '".$_POST['criterio_ape1']."%'  and Apellido2_Cl like '".$_POST['criterio_ape2']."%') UNION (select DNI,Nombre,Apellido1,Apellido2 from componentes_grupo where DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%'  and Apellido2 like '".$_POST['criterio_ape2']."%') UNION (SELECT CIF, Nombre_Gr, NULL FROM grupo WHERE CIF LIKE '".$_POST['criterio_dni']."%' AND Nombre_Gr LIKE '".$_POST['criterio_nombre']."%')";	
				$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
			} else {
				$sql = "(SELECT DNI_Cl,Nombre_Cl,Apellido1_Cl,Apellido2_Cl FROM cliente WHERE DNI_Cl like '".$_POST['criterio_dni']."%' and Nombre_Cl like '".$_POST['criterio_nombre']."%' and Apellido1_Cl like '".$_POST['criterio_ape1']."%') UNION (SELECT DNI,Nombre,Apellido1,Apellido2 FROM componentes_grupo WHERE DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%') UNION (SELECT CIF, Nombre_Gr, NULL , NULL FROM grupo WHERE CIF LIKE '".$_POST['criterio_dni']."%' AND Nombre_Gr LIKE '".$_POST['criterio_nombre']."%')";
				$sql_incidencias = "select DNI_Inc from incidencias where DNI_Inc like '".$_POST['criterio_dni']."%'";
			}
		}
	
	//Si la incidencia proviene de un grupo
	} else {
		//mysql_select_db("sam");
		mysql_select_db($_SESSION['conexion']['db']);
		
		if($_POST['criterio_ape2'] || $_POST['criterio_ape1']) {
			if($_POST['criterio_ape2']) {
				$sql = "select * from componentes_grupo where DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%' and Apellido2 like '".$_POST['criterio_ape2']."%' and Fecha_Llegada like '".$_SESSION['ff']."' and Nombre_Gr like '".$_SESSION['gg']."'"; 	
				$sql_incidencias = "select  DNI_Inc from incidencias where DNI_Inc like '".$_POST['criterio_dni']."%'";
			} else {
				$sql = "select * from componentes_grupo where DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%'  and Fecha_Llegada like '".$_SESSION['ff']."' and Nombre_Gr like '".$_SESSION['gg']."'"; 	
				$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
			}
		
		} else {
			if($_POST['criterio_ape2']) {
				$sql = "(select * from componentes_grupo where DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%' and Apellido2 like '".$_POST['criterio_ape2']."%' and Fecha_Llegada like '".$_SESSION['ff']."' and Nombre_Gr like '".$_SESSION['gg']."') UNION (select CIF,Nombre_Gr,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL from grupo where Nombre_Gr like '".$_SESSION['gg']."' and Nombre_Gr like '".$_POST['criterio_nombre']."%' and CIF like '".$_POST['criterio_dni']."%')"; 	
				$sql_incidencias = "select  DNI_Inc from incidencias where DNI_Inc like '".$_POST['criterio_dni']."%'";
			} else {
				$sql = "(SELECT DNI,Nombre,Apellido1,Apellido2,Fecha_Llegada,Nombre_Gr FROM componentes_grupo WHERE DNI like '".$_POST['criterio_dni']."%' and Nombre like '".$_POST['criterio_nombre']."%' and Apellido1 like '".$_POST['criterio_ape1']."%'  and Fecha_Llegada like '".$_SESSION['ff']."' and Nombre_Gr like '".$_SESSION['gg']."') UNION (select CIF,Nombre_Gr,NULL,NULL,NULL,NULL from grupo where Nombre_Gr like '".$_SESSION['gg']."' and Nombre_Gr like '".$_POST['criterio_nombre']."%' and CIF like '".$_POST['criterio_dni']."%')"; 	
				$sql_incidencias = "select  DNI_Inc from incidencias where	DNI_Inc like '".$_POST['criterio_dni']."%'";
	
			}
			
		}
	}		

			
	if($result = mysql_query($sql)) {

?>
<div id='listado'>
<table align='left' border='0' width='590px' cellspacing="0" cellpadding="0" style="width:604px; margin-left:10px;">
<tr>
<th style="padding:0px 0px 0px 0px;" width='36px'>&nbsp;</td>
	<th width='108px' align='left'>D.N.I. / C.I.F.</th>
	<th width='131px' align='left'>Nombre</th>
	<th width='138px' align='left'>Primer Apellido</th>
	<th width='133px' align='left'>Segundo Apellido</th>
	<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
</tr>
<tr class='texto_listados'>
	<td>&nbsp;</td>
	<td align='left'>
	<input type="text" size="13" style="border:solid 1px;" name="criterio_dni" onKeyUp="enviar();" onFocus="caja('dni');" value="<?
		if(isset($_POST['criterio_dni'])){
			echo $_POST['criterio_dni'];
		}?>"></td>
	<td align='left'>
		<input type="text" size="15" style="border:solid 1px;" name="criterio_nombre"  onKeyUp="enviar();" OnFocus="caja('nombre');" value="<?
		if(isset($_POST['criterio_nombre'])){
			echo $_POST['criterio_nombre'];
		}?>"></td>
	<td align='left'>
		<input type="text" size="17" style="border:solid 1px;" name="criterio_ape1" onKeyUp="enviar();" onFocus="caja('ape1');" value="<?
			if(isset($_POST['criterio_ape1'])){
				echo $_POST['criterio_ape1'];
			}?>"></td>
	<td align='left'>
		<input type="text" size="17" style="border:solid 1px;" name="criterio_ape2" onKeyUp="enviar();" onFocus="caja('ape2');" value="<?
			if(isset($_POST['criterio_ape2'])){
				echo $_POST['criterio_ape2'];
			}?>"></td>
	<input type="hidden" name="campo_actual"> 
	<td>&nbsp;</td>
</tr>
</table>

<div style="height:420px; width:590px; overflow-y:scroll; margin-top:70px;" id='listado_inc'>
<table align='left' border="0" width='590px' cellspacing="0" cellpadding="0" class='scrollTable'> 
<tr>




<?

	if($_SESSION['normal'] == 'si') {		
		for($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);										
			echo "<tr>";
			echo "<td valign='top' align=\"left\"><input value=".$i." type=\"radio\" name=\"select_cliente\" onClick=\"document.getElementsByName('boton_aceptar')[0].style.display='block';\"></td>";
			
			$dni = $fila['DNI_Cl'];
			$dni_partido = parteCadenas($dni, 12);
			echo "<td valign='top' width='20%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$dni_partido."</td>";
			echo "<input type=\"hidden\"  name=\"dni".$i."\" value=\"".$fila['DNI_Cl']."\">";
								
			//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
			//adecuadamente en el listado.
			$nom = $fila['Nombre_Cl'];
			$nom_partido = parteCadenas($nom, 10);
			echo "<td class='body' valign='top' width='24%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$nom_partido."</td>";
			echo "<input type=\"hidden\"  name=\"nombre".$i."\" value=\"".$fila['Nombre_Cl']."\">";
			

			//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
			//adecuadamente en el listado.
			$ap1 = $fila['Apellido1_Cl'];
			$ap1_partido = parteCadenas($ap1, 11);
			echo "<td valign='top' width='25%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$ap1_partido."</td>";
			echo "<input type=\"hidden\"  name=\"apellido1".$i."\" value=\"".$fila['Apellido1_Cl']."\">";


			//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
			//adecuadamente en el listado.
			$ap2 = $fila['Apellido2_Cl'];
			$ap2_partido = parteCadenas($ap2, 11);
			echo "<td valign='top' width='25%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$ap2_partido."</td>";
			echo "<input type=\"hidden\"  name=\"apellido2".$i."\" value=\"".$fila['Apellido2_Cl']."\">";

			$result_inci = mysql_query($sql_incidencias);
			echo "<td valign='top' align='left'>";
			while($fila_inci = mysql_fetch_array($result_inci)){
				if($fila_inci['DNI_Inc'] == $fila['DNI_Cl']){
					echo "<img src=\"../../imagenes/botones/alerta.gif\" alt=\"Ha provocado alguna incidencia con anterioridad\" title=\"Ha provocado alguna incidencia con anterioridad\" height=\"25px\" width=\"25px;\" />";
					$control = 1;
					break;
				} else {
					$control = 2;
				}
			}
			if($control != 1) {
				echo "<img src=\"../../imagenes/botones/No_alerta.gif\" height=\"25px\" width=\"25px;\" />";
			}
			echo "</td>";
		}
	} else {		
		for($i=0;$i<mysql_num_rows($result);$i++){
			$fila = mysql_fetch_array($result);										
			echo "<tr>";
			echo "<td valign='top' align=\"left\"><input value=".$i." type=\"radio\" name=\"select_cliente\" onClick=\"document.getElementsByName('boton_aceptar')[0].style.display='block';\"></td>";
			
			$dni = $fila['DNI'];
			$dni_partido = parteCadenas($dni, 12);
			echo "<td valign='top' width='20%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$dni_partido."</td>";
			echo "<input type=\"hidden\"  name=\"dni".$i."\" value=\"".$fila['DNI']."\">";
								
			//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
			//adecuadamente en el listado.
			$nom = $fila['Nombre'];
			$nom_partido = parteCadenas($nom, 10);
			echo "<td valign='top' width='24%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$nom_partido."</td>";
			echo "<input type=\"hidden\"  name=\"nombre".$i."\" value=\"".$fila['Nombre']."\">";
			

			//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
			//adecuadamente en el listado.
			$ap1 = $fila['Apellido1'];
			$ap1_partido = parteCadenas($ap1, 11);
			echo "<td valign='top' width='25%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$ap1_partido."</td>";
			echo "<input type=\"hidden\"  name=\"apellido1".$i."\" value=\"".$fila['Apellido1']."\">";



			//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
			//adecuadamente en el listado.
			$ap2 = $fila['Apellido2'];
			$ap2_partido = parteCadenas($ap2, 11);
			echo "<td valign='top' width='25%' align='left' style='font-family:verdana; font-size:11px; font-weight:bold; color: #B3AFA0; background-color: transparent;'>".$ap2_partido."</td>";
			echo "<input type=\"hidden\"  name=\"apellido2".$i."\" value=\"".$fila['Apellido2']."\">";

			$result_inci = mysql_query($sql_incidencias);
			echo "<td valign='top' align='left'>";
			while($fila_inci = mysql_fetch_array($result_inci)){
				if ($fila_inci['DNI_Inc'] == $fila['DNI']) {
					echo "<img src=\"../../imagenes/botones/alerta.gif\" alt=\"Ha provocado alguna incidencia con anterioridad\" title=\"Ha provocado alguna incidencia con anterioridad\" height=\"25px\" width=\"25px;\" />";
					$control = 1;
					break;
				} else {
					$control = 2;
				}
			}
			if($control != 1) {
				echo "<img src=\"../../imagenes/botones/No_alerta.gif\" height=\"25px\" width=\"25px;\" />";
			}
			echo "</td>";
		}
	}


?>
</tr>
</table>
</div>


<br><br>
<img src="../../imagenes/botones-texto/aceptar.jpg" style="cursor:hand;display:none;" name='boton_aceptar' id='boton_aceptar' alt="Seleccionar cliente" value="aceptar" onClick="pasa_datos();">
</div>
</center>
</form>

</BODY>
	
	<?
	} else {
		echo "No se ha podido conectar a la base de datos.<br><br>";
		echo "<a href=\"#\" onClick=\"window.close();\" style=\"font-weight:bold;text-decoration:underline;color:#064C87;\">Cerrar</a>";
	}
}
?>
</HTML>
<!--
<?	
	if(!isset($_POST['criterio_dni'])){
		echo "<script>document.forms[0].criterio_dni.value ='';
						document.forms[0].criterio_nombre.value ='';
						document.forms[0].criterio_ape1.value ='';
						document.forms[0].criterio_ape2.value ='';
						document.forms[0].criterio_dni.value = window.opener.document.forms[0].dni_alberguista.value;
						document.forms[0].criterio_nombre.value = window.opener.document.forms[0].nombre_alberguista.value;
						document.forms[0].criterio_ape1.value = window.opener.document.forms[0].apellido1_alberguista.value;
						document.forms[0].criterio_ape2.value = window.opener.document.forms[0].apellido2_alberguista.value;
						document.forms[0].submit();
					</script>";
	}

?> 
-->
<?php
	MYSQL_CLOSE($db);
?>