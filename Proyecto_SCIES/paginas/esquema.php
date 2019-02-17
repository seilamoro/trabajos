
<?php 

$db = mysql_pconnect("localhost" , "root" , "");
mysql_select_db("SCIES");

//Creo un array para guardar los datos de la DB antes de enviarselo a flash
$parametro = Array();

$centro = $_GET['id_centro'];


// Consulta para el centro

$qry_centro = "SELECT * FROM centro where id_centro = '".$centro."'";

$res_centro = mysql_query($qry_centro);

if ($fila_centro = mysql_fetch_array($res_centro)){
	$centro_nombre = $fila_centro['Nombre'];
}

$centro_nombre = str_replace(" ", "_", $centro_nombre);



// Consulta para la fecha

$qry_fecha = "SELECT MAX(Fecha) AS fecha_max, MAX(Hora) AS hora_max FROM lectura WHERE (Id_Centro = '".$id_centro."')";

$res_fecha = mysql_query($qry_fecha);

if ($fila_fecha = mysql_fetch_array($res_fecha)){
	$fecha_max = $fila_fecha['fecha_max'];
	$hora_max = $fila_fecha['hora_max'];
	$fecha_split = split("-", $fecha_max);
	$fecha_max = $fecha_split[2]."-".$fecha_split[1]."-".$fecha_split[0];
}

$fecha_def = $fecha_max.",_(".$hora_max.")";

//Consulta para el listado de centros

$sql_centros = "SELECT centro.Id_Centro, centro.Nombre FROM centro WHERE Id_Centro IN (SELECT Id_Centro FROM sonda)";
$res_centros = mysql_query($sql_centros);
$cont_centros = 1;
$lista_centos = "";
for ($i=0;$i<mysql_num_rows($res_centros);$i++){
	$fila_centros = mysql_fetch_array($res_centros);
	$lista_centros = $lista_centros."&ID".$cont_centros."=".$fila_centros['Id_Centro']."&C".$cont_centros."=".str_replace(" ","_",$fila_centros['Nombre']);
	$cont_centros++;
}

//Consulta para los parametros

$qry_para = "SELECT * FROM parametro";

$res_para = mysql_query($qry_para);

$cont = 0;
$cont_para = 1;
$lista_valores=''; //En esta  variable voy a guardar el string con las variables que le voy a enviar a flash
while ($fila_para = mysql_fetch_array($res_para)){
	$para = $fila_para['Id_Parametro'];
	$qry_dato = "SELECT * FROM lectura WHERE (Id_Centro = '".$centro."' AND Id_Parametro = '".$para."' AND Fecha = (SELECT MAX(Fecha) FROM lectura WHERE Id_Centro = '".$centro."' AND Id_Parametro = '".$para."') AND Hora = (SELECT MAX(Hora) FROM lectura WHERE Id_Centro = '".$centro."' AND Id_Parametro = '".$para."' AND Fecha = (SELECT MAX(Fecha) FROM lectura WHERE Id_Centro = '".$centro."' AND Id_Parametro = '".$para."')))";
	$res_dato = mysql_query($qry_dato);
	if (mysql_num_rows($res_dato)>0){
		$fila_dato = mysql_fetch_array($res_dato);
		$parametro[$cont]['id'] = $para;
		$parametro[$cont]['nombre'] = $fila_para['Nombre_Param'];
		$parametro[$cont]['descripcion'] = $fila_para['Descripcion_Param'];
		$parametro[$cont]['valor'] = $fila_dato['Valor'];
		if ($fila_para['Nombre_Param'] == 'Bomba'){
			$lista_valores = $lista_valores."valor_bomba=";
		}else{
			if ($fila_para['Nombre_Param'] == 'Paneles1'){
				$lista_valores = $lista_valores."paneles_1=";
			}else{
				if ($fila_para['Nombre_Param'] == 'Paneles2'){
					$lista_valores = $lista_valores."paneles_2=";
				}else{
					if ($fila_para['Nombre_Param'] == 'Sup Acumulador'){
						$lista_valores = $lista_valores."acu_sup=";
					}else{
						if ($fila_para['Nombre_Param'] == 'Inf Acumulador'){
							$lista_valores = $lista_valores."acu_inf=";
						}else{
							if ($fila_para['Nombre_Param'] == 'Agua Sanitaria'){
								$lista_valores = $lista_valores."agua_sanit=";
							}else{
								if ($fila_para['Nombre_Param'] == 'Agua Red'){
									$lista_valores = $lista_valores."agua_red=";
								}else{
									$lista_valores = $lista_valores."N".$cont_para."=".str_replace(" ","_",$fila_para['Descripcion_Param'])."&V".$cont_para."=";
									$cont_para++;
								}
							}
						}
					}
				}
			}
		}
		$lista_valores = $lista_valores.$fila_dato['Valor']."&";
		$cont++;
	}
}
$lista_valores = $lista_valores."centro_nombre=".$centro_nombre."&fecha_max=".$fecha_def.$lista_centros;


?>
<form name='formu_esquema'>
<div style="float:left;position:relative;left:2	%;">
	<img onClick='mostrar_conexiones();' src='imagenes\botones\actualizar_conexion.gif'>
</div>
<div id='conexiones' style="border:1;display:none;position:absolute;float:left;top:5%;left:0%;border:solid 2px #88855B;padding:10px;">
	Conexiones:<br>
	<select name='conesiones'>
<?php 
	  	
  	$resultado = exec("C:\\SCIES\\ras_dos.exe /l");
  	
  	$conexiones = Array();
  	
  	$conexiones = split(",",$resultado);
	for ($i=0;$i<count($conexiones);$i++){
		echo ("<option value='".$conexiones[$i]."'>".$conexiones[$i]."</option>");
	}

?>
	</select>
	<br>
	<Input type='button' onClick="abrir_actualizar();" name='actualizar' value='Actualizar' style='margin-top:10px;border:solid 3px #88855B;background-color:#DAD8C0;'>
</div>
 <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="900" height="475" id="esquema" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="movie" value="paginas/esquema_instalacion.swf" />
	<param name="quality" value="high" />
	<PARAM NAME="FlashVars" VALUE="<?php
	echo $lista_valores;
/*
for ($i=0;$i<count($parametro);$i++){
	echo "P".$parametro[$i]['id']."=".$parametro[$i]['valor'];
	if ($i != (count($parametro) -1)){echo "&";}
}

echo "&centro_nombre=".$centro_nombre."&fecha_max=".$fecha_def;
*/

?>" />
	<embed src="paginas/esquema_instalacion.swf" quality="high" width="900" height="470" name="Esquema Gráfico de la Instalación" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
</object>
</form>
<script language='JavaScript'>

function abrir_actualizar(){
	window.open("paginas/actualizar_conexion.php?id_centro=<?php echo $centro;?>&conexion="+document.formu_esquema.conesiones[document.formu_esquema.conesiones.selectedIndex].value, "_blank", "width=650px,height=150px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");
}

function mostrar_conexiones(){
	if (document.getElementById("conexiones").style.display == 'none'){
		document.getElementById("conexiones").style.display = 'block';
	}else{
		document.getElementById("conexiones").style.display = 'none';
	}
}

</script>

