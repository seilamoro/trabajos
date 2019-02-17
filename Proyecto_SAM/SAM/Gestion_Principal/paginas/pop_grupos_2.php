<?PHP session_start();?>
<head>
<link rel="stylesheet" type="text/css" href="../css/busq_lupa.css">
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
		var i
		for (i=0;i<document.getElementsByName('op').length;i++){
			if (document.getElementsByName('op')[i].checked == true) {
				var cadena = document.getElementsByName('op')[i].value;
				var array_cadena = cadena.split("#");
				if(array_cadena[7]=="xxx"){
				provincia="";
				}else{
				provincia = array_cadena[4];
				}
				window.opener.document.dar_alta.cif.value = array_cadena[8];
				
				window.opener.document.dar_alta.nombre.value = array_cadena[0];
				window.opener.document.dar_alta.dir.value = array_cadena[1];
				
				window.opener.document.dar_alta.localidad.value = array_cadena[2];
		
				
				//window.opener.document.dar_alta.pais.options[array_cadena[3]-1].selected  = true;
				//window.opener.document.dar_alta.prov.options[array_cadena[4]].selected = true;
				
				//var provincia = array_cadena[4];
					for(j=0;j<window.opener.document.dar_alta.prov.length;j++)	{
						if(j == provincia)	{
							window.opener.document.dar_alta.prov.options[j].selected = true;
							break;
						}
						
					}
					if(array_cadena[7]=="xxx"){
				window.opener.document.dar_alta.prov.disabled=true;
				}
					var pais = array_cadena[3];
					for(j=1;j<window.opener.document.dar_alta.pais.length;j++)	{
						if(j == pais)	{
							window.opener.document.dar_alta.pais.options[j-1].selected = true;
							break;
						}
						
					}
					window.opener.document.dar_alta.email.value = array_cadena[6];
				
				
				window.opener.document.dar_alta.con_popup.value = 1;
				
				window.close();		
				 
			}
		} 
	}
</script>


</head>
<body onload="<?php echo $cadena ?>" background="../../imagenes/fondo_busqueda_grande.jpg">
<center>
<br><br><br>

<form name="formu" action="pop_grupos_2.php" method="POST">
	<input type="hidden" name="campo_actual" >
	<div id='listado'>
	<table border="0" width="575px" align="center">
		<tr align="left" >
			<td></td>
			<td >CIF:</td>
			<td>Nombre: </td>
			<td>País:</td>
			<td>Localidad:</td>
		</tr>
		<tr align="left" >
			
			<td width=""></td>
			<td width=""><input type="text" name="criterio_cif" style="border:solid 1px;" size="14"  onKeyUp="enviar();" onFocus="caja('cif');" value="<?
								if(isset($_POST['criterio_cif'])){
									echo $_POST['criterio_cif'];
								}?>"></td>
			<td width=""><input type="text" name="criterio_nombre" style="border:solid 1px;" size="14"  onKeyUp="enviar();" onFocus="caja('nombre');" value="<?
								if(isset($_POST['criterio_nombre'])){
									echo $_POST['criterio_nombre'];
								}?>"></td>
			<td width=""><input type="text" name="criterio_pais"  style="border:solid 1px;" size="14" onKeyUp="enviar();" onFocus="caja('pais');" value="<?
								if(isset($_POST['criterio_pais'])){
									echo $_POST['criterio_pais'];
								}?>"></td>
			<td><input type="text" name="criterio_localidad" size="14" style="border:solid 1px;"onKeyUp="enviar();" onFocus="caja('localidad');" value="<?
								if(isset($_POST['criterio_localidad'])){
									echo $_POST['criterio_localidad'];
								}?>"></td>
		</tr>
	</table>
	

	

	<?PHP
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
		
		<?
		@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

			mysql_select_db($_SESSION['conexion']['db']);
			
			
			//echo "select DISTINCT * from grupo,estancia_gr $where GROUP BY grupo.Nombre_Gr";
		$result2=mysql_query("select DISTINCT * from grupo,pais where grupo.Id_Pais=pais.Id_Pais and grupo.Nombre_Gr LIKE '".$_POST['criterio_nombre']."%' and pais.Nombre_Pais LIKE '".$_POST['criterio_pais']."%' and grupo.Localidad_Gr LIKE '".$_POST['criterio_localidad']."%' and grupo.CIF LIKE '".$_POST['criterio_cif']."%'");
		?>
			
			<div class="tableContainer" style="height:410px;width:610px;">
			<br>
			<table border="0" width="100%" class="scrollTable"> 
			
			<?
			while ($row=mysql_fetch_array($result2)) {
			
				$result_pais=mysql_query("select * from pais ORDER BY Nombre_Pais");
				$i=0;
				while ($row_pais=mysql_fetch_array($result_pais)) {
					$i++;
					if ($row_pais['Id_Pais'] == $row['Id_Pais'])
						$offset = $i;
				}
				$result_pro=mysql_query("select * from provincia ORDER BY Id_Pro");
				$j=0;
				while ($row_pro=mysql_fetch_array($result_pro)) {
					$j++;
					if ($row_pro['Id_Pro'] == $row['Id_Pro'])
						$offset_pro = $j;
						
				}
				
			
			$sql="select DISTINCT * from grupo,estancia_gr where grupo.Nombre_Gr=estancia_gr.Nombre_Gr  and grupo.Nombre_Gr='".$row["Nombre_Gr"]."' and Fecha_Salida>='".date("Y-m-d")."'";
                     $result=mysql_query($sql);
			?>
			
			<tbody style="overflow:auto;font-weight: normal;">
			<?  if(mysql_num_rows($result)==0){ 
			$result_incidencia=mysql_query("select * from incidencias where DNI_Inc='".$row["CIF"]."'");
			?>
			<tr align="left"><td width="3%">
			<?
			
			if($row['Id_Pro']!=""){
			?>
			<input type="radio" name="op" value="<?echo $row["Nombre_Gr"]?>#<?echo $row["Direccion_Gr"]?>#<?echo $row["Localidad_Gr"]?>#<?echo $offset?>#<?echo $offset_pro?>#<?echo $row["Id_Pais"]#<?echo $row["Id_Pro"]?>#<?echo $row["Email_Gr"]?>#fffff#<?echo $row["CIF"]?>">
			<?
			}else{
			?>
			<input type="radio" name="op" value="<?echo $row["Nombre_Gr"]?>#<?echo $row["Direccion_Gr"]?>#<?echo $row["Localidad_Gr"]?>#<?echo $offset?>#<?echo $offset_pro?>#<?echo $row["Id_Pais"]#<?echo $row["Id_Pro"]?>#<?echo $row["Email_Gr"]?>#xxx#<?echo $row["CIF"]?>">
			<? }
			?>
			</td>
			<?
			echo "<td valign='top' width='24%' align='left' class='discrete_text'><font color='gray'>".$row["CIF"]."</font></td>";
			$nombre_gr = $row["Nombre_Gr"];
			$nombre_gr_partido = parteCadenas($nombre_gr, 15);
			echo "<td valign='top' width='24%' align='left' class='discrete_text'><font color='gray'>".$nombre_gr_partido."</font></td>";
			?>
			
           <?PHP
			$result_pais=mysql_query("select * from pais where Id_Pais  ='".$row["Id_Pais"]."'");
			$row_pais=mysql_fetch_array($result_pais);
						$pais = $row_pais['Nombre_Pais'];
			$pais_partido = parteCadenas($pais, 17);
			echo "<td valign='top' width='24%' align='left' class='discrete_text'><font color='gray'>".$pais_partido."</font></td>";

			?>
			<?PHP
			$result_provin=mysql_query("select * from provincia where id_Pro ='".$row["Localidad_Gr"]."'");
			
			$prov_partido = parteCadenas($row["Localidad_Gr"], 17);
			echo "<td valign='top' width='20%' align='left' class='discrete_text'><font color='gray'>".$prov_partido."</font></td>";
	
		
				if (mysql_num_rows($result_incidencia) != 0) {
					?>
					<td width=""><img src="../../imagenes/botones/alerta.gif" title="Ha provocado alguna incidencia con anterioridad." height="25px;" width="25px;"  /></td>
					<? 
				} else {
					?>
					<td width="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<? 
				}
			?>
			</tr>
			<? 
			}}
			?>
			
			</tbody></table></div><br>
    <a href="#"> <img src="../../imagenes/botones-texto/aceptar.jpg"  name="boton_aceptar" width="110" height="30" border="0" onClick="llevar();" value="aceptar" title="Cargar el grupo"> 
    </a> </div>
			</form>
			<?
	
		
	?><?PHP
    mysql_close($db);
?>
