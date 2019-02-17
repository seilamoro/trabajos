
		<link  rel="StyleSheet" href="../css/principal.css" type="text/css">
		<link rel="StyleSheet" href="../css/listados.css" type="text/css">
		<link rel="stylesheet" type="../text/css" href="css/menu.css">
		<link rel="StyleSheet" href="../css/pestañas.css" type="text/css">
		<STYLE type="text/css">
A:link {text-decoration:none;color:#ACA867;}
A:visited {text-decoration:none;color:#F3F3F3;}
A:active {text-decoration:none;color:#F3F3F3;}
A:hover {text-decoration:none;color:#F3F3F3;}
</STYLE>

		
		
<script language="javascript">


function volver(dia,mes,ano,ins){


if(ins==1){
window.opener.document.formulario.fechainicio.value=dia+"-"+mes+"-"+ano; 
}
if(ins==2){
  window.opener.document.formulario.fechafin.value=dia+"-"+mes+"-"+ano; 
  }
window.close();
}

</script>

<?
function calcula_numero_dia_semana($dia,$mes,$ano){
	$numerodiasemana = date('w', mktime(0,0,0,$mes,$dia,$ano));
	if ($numerodiasemana == 0) 
		$numerodiasemana = 6;
	else
		$numerodiasemana--;
	return $numerodiasemana;
}

//funcion que devuelve el último día de un mes y año dados
function ultimoDia($mes,$ano){ 
    $ultimo_dia=28; 
    while (checkdate($mes,$ultimo_dia + 1,$ano)){ 
       $ultimo_dia++; 
    } 
    return $ultimo_dia; 
} 

function dame_nombre_mes($mes){
	 switch ($mes){
	 	case 1:
			$nombre_mes="Enero";
			break;
	 	case 2:
			$nombre_mes="Febrero";
			break;
	 	case 3:
			$nombre_mes="Marzo";
			break;
	 	case 4:
			$nombre_mes="Abril";
			break;
	 	case 5:
			$nombre_mes="Mayo";
			break;
	 	case 6:
			$nombre_mes="Junio";
			break;
	 	case 7:
			$nombre_mes="Julio";
			break;
	 	case 8:
			$nombre_mes="Agosto";
			break;
	 	case 9:
			$nombre_mes="Septiembre";
			break;
	 	case 10:
			$nombre_mes="Octubre";
			break;
	 	case 11:
			$nombre_mes="Noviembre";
			break;
	 	case 12:
			$nombre_mes="Diciembre";
			break;
	}
	return $nombre_mes;
}

function mostrar_calendario($dia,$mes,$ano,$ins){
$mes_hoy=date("m");
$ano_hoy=date("Y");
if (($mes_hoy <> $mes) || ($ano_hoy <> $ano))
{
	$hoy=0;
}
else
{
	$hoy=date("d");
}
	//tomo el nombre del mes que hay que imprimir
	$nombre_mes = dame_nombre_mes($mes);
	
	//construyo la cabecera de la tabla
	echo "<div class='titulo1' width='100%' style='align:center'>";
	echo "<table width=100 height=100 cellspacing=3 cellpadding=2 style='font-size:9pt;' border=0><tr><td colspan=7 align=center>";
	echo "<table width=100% cellspacing=2 cellpadding=2 border=0><tr>
	<td style=font-size:12pt;font-weight:bold;color:white>";
	//calculo el mes y ano del mes anterior
	$mes_anterior = $mes - 1;
	$ano_anterior = $ano;
	if ($mes_anterior==0){
		$ano_anterior--;
		$mes_anterior=12;
	}
	echo "<a style=color:white;text-decoration:none href=calendarios.php?dia=1&mes=$mes_anterior&ano=$ano_anterior&ins=$ins>&lt;&lt;</a></td>";
	   echo "<td align=center style='color:#666666;font-size:10pt;font-weight:bold;'>$nombre_mes </td>";
	   echo "<td align=right style=font-size:12pt;font-weight:bold;color:white>";
	//calculo el mes y ano del mes siguiente
	$mes_siguiente = $mes + 1;
	$ano_siguiente = $ano;
	if ($mes_siguiente==13){
		$ano_siguiente++;
		$mes_siguiente=1;
	}
	echo "<a style=color:white;text-decoration:none href=calendarios.php?dia=1&mes=$mes_siguiente&ano=$ano_siguiente&ins=$ins>&gt;&gt;</a></td>";
	
	$mes_anterior2 = $mes ;
	$ano_anterior2 = $ano-1;
	$mes_siguiente2 = $mes;
	$ano_siguiente2 = $ano+1;
	

	
	echo "<td style=font-size:12pt;font-weight:bold;color:white><a style=color:white;text-decoration:none href=calendarios.php?dia=1&mes=$mes_anterior2&ano=$ano_anterior2&ins=$ins>&lt;&lt;</a></td>";
	echo "<td align=center style='color:#666666;font-size:10pt;font-weight:bold;'>$ano </td>";
	echo "<td align=right style=font-size:12pt;font-weight:bold;color:white>";
	echo "<a style=color:white;text-decoration:none href=calendarios.php?dia=1&mes=$mes_siguiente2&ano=$ano_siguiente2&ins=$ins>&gt;&gt;</a></td>";
	
	
	echo "</tr></table></td></tr>";
	
	echo "	<tr>
			    <td  align=center style='font-size:8pt;color:#ffffff;background-color:#666666;'>Lu</td>
			    <td  align=center style='font-size:8pt;color:#ffffff;background-color:#666666;'>Ma</td>
			    <td  align=center style='font-size:8pt;color:#ffffff;background-color:#666666;'>Mi</td>
			    <td  align=center style='font-size:8pt;color:#ffffff;background-color:#666666;'>Ju</td>
			    <td  align=center style='font-size:8pt;color:#ffffff;background-color:#666666;'>Vi</td>
			    <td  align=center style='font-size:8pt;color:#ffffff;background-color:#666666;'>Sa</td>
			    <td  align=center style='font-size:8pt;color:#ffffff;background-color:#666666;'>Do</td>
			</tr>";
	
	//Variable para llevar la cuenta del dia actual
	$dia_actual = 1;
	
	//calculo el numero del dia de la semana del primer dia
	$numero_dia = calcula_numero_dia_semana(1,$mes,$ano);
	//echo "Numero del dia de demana del primer: $numero_dia <br>";
	
	//calculo el último dia del mes
	$ultimo_dia = ultimoDia($mes,$ano);
	
//escribo la primera fila de la semana
	echo "<tr>";
	for ($i=0;$i<7;$i++){
		if ($i < $numero_dia){
			//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo nada en la celda
			echo "<td></td>";
		} else {
		if (($i == 5) || ($i == 6))
		{
				if ($dia_actual == $hoy)
				{
					echo "<td class=da><a style='color:#ffffff;background-color:#666666;' href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
				else
				{
				  
					echo "<td class=fs><a href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
		}
		else
		{			
				if ($dia_actual == $hoy)
				{
					echo "<td class=da><a style='color:#ffffff;background-color:#666666;' href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
				else
				{
					echo "<td align=center><a href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
		}
			$dia_actual++;
		}
	}
	echo "</tr>";
	
	//recorro todos los demás días hasta el final del mes
	$numero_dia = 0;
	while ($dia_actual <= $ultimo_dia){
		//si estamos a principio de la semana escribo el <TR>
		if ($numero_dia == 0)
			echo "<tr>";
		//si es el uñtimo de la semana, me pongo al principio de la semana y escribo el </tr>

			if (($numero_dia == 5) || ($numero_dia == 6))
			{
				if ($dia_actual == $hoy)
				{
					echo "<td class=da><a style='color:#ffffff;background-color:#666666;' href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
				else
				{
					echo "<td class=fs ><a href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
			}
			else
			{		
				if ($dia_actual == $hoy)
				{
					echo "<td class=da><a style='color:#ffffff;background-color:#ACA867;' href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
				else
				{
					echo "<td align=center><a href='javascript:volver($dia_actual,$mes,$ano,$ins)'>$dia_actual</a></td>";
				}
			}

			$dia_actual++;
			$numero_dia++;
			if ($numero_dia == 7)
			{
				$numero_dia = 0;
				echo "</tr>";
			}

	}
	
	//compruebo que celdas me faltan por escribir vacias de la última semana del mes
	for ($i=$numero_dia;$i<7;$i++){
		echo "<td></td>";
	}
	
	echo "</tr>";
	echo "</table>";
	echo "</div>";
}	

if($_GET['dia']=="0"){
  
  
  	
	  
	$tiempo_actual = time();
	$_GET['mes'] = date("n", $tiempo_actual);
	$_GET['ano'] = date("Y", $tiempo_actual);
	$_GET['dia'] = date("d");
	

  
}

mostrar_calendario($_GET['dia'],$_GET['mes'],$_GET['ano'],$_GET['ins']);
//print($_GET['dia']);