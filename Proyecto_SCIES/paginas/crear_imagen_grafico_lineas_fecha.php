<?
	session_start();

	if(isset($_POST['rango'])){
		if($_POST['dia_inicio'] == "")
			$_POST['dia_inicio'] = "01";
		if($_POST['mes_inicio'] == "")
			$_POST['mes_inicio'] = "01";
		$fecha = $_POST['anio_inicio'] . "-" . $_POST['mes_inicio'] . "-" . $_POST['dia_inicio'];
		$rango =  $_POST['rango'];
		$grafico =  $_POST['grafico'];
		$instalaciones = $_POST['ins_seleccionadas'];
		$parametros = $_POST['para_seleccionadas'];
		$fondo = $_POST['fondo_img'];
		$_SESSION['instalaciones_selec_grafico'] = array();
		$_SESSION['parametros_selec_grafico'] = array();
		$_SESSION['instalaciones_selec_grafico'] = $instalaciones;
		$_SESSION['parametros_selec_grafico'] = $parametros;
		$_SESSION['fecha_grafico'] = $fecha;
		$_SESSION['grafico_grafico'] = $grafico;
		$_SESSION['grafico_fondo'] = $fondo;
	}
	else{
		$rango = $_GET['rango'];
		$instalaciones = $_SESSION['instalaciones_selec_grafico'];
		$parametros = $_SESSION['parametros_selec_grafico'];
		$fecha = $_SESSION['fecha_grafico'];
		$grafico = $_SESSION['grafico_grafico'];
		$fondo = $_SESSION['grafico_fondo'];
	}

	$separador_valores = "-";
	if($grafico == 3){
		$separador_valores = "";
	}
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("SCIES");
	
	$Finicio = $fecha;	//la fecha seleccionada es la 1º fecha
	$fecha_inicio = mktime(0, 0, 0, substr($fecha,5,2), substr($fecha,8,2), substr($fecha,0,4));
	if($rango == 1){	//la fecha de inicio depende del rango que seleccione el usuario
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio), strftime("%d",$fecha_inicio), strftime("%Y",$fecha_inicio));
	}else if($rango == 2){
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio), (strftime("%d",$fecha_inicio)+2), strftime("%Y",$fecha_inicio));
	}else if($rango == 3){
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio), strftime("%d",$fecha_inicio)+6, strftime("%Y",$fecha_inicio));
	}else if($rango == 4){
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio), strftime("%d",$fecha_inicio)+13, strftime("%Y",$fecha_inicio));
	}else if($rango == 5){
		$dias_mes = date("t", $fecha_inicio);
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio), $dias_mes, strftime("%Y",$fecha_inicio));
	}else if($rango == 6){
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio)+2, 1, strftime("%Y",$fecha_inicio));
		$dias_mes = date("t", $fin);
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio)+2, $dias_mes, strftime("%Y",$fecha_inicio));
	}else if($rango == 7){
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio)+5, 1, strftime("%Y",$fecha_inicio));
		$dias_mes = date("t", $fin);
		$fin = mktime(0, 0, 0, strftime("%m",$fecha_inicio)+5, $dias_mes, strftime("%Y",$fecha_inicio));
	}else if($rango == 8){
		$fecha_inicio = mktime(0, 0, 0, 1, 1, strftime("%Y",$fecha_inicio));
		$Finicio = strftime("%Y-%m-%d",$fecha_inicio);
		$hoy = mktime(0, 0, 0, date("m"), date("d"), date("Y"));	
		$fin = mktime(0, 0, 0, 12, 31, strftime("%Y",$fecha_inicio));
		if($hoy < $fin)
			$fin = $hoy;
	}
	$Ffin = strftime("%Y-%m-%d",$fin);

	$lineas = array(count($instalaciones) * count($parametros));	//este array almacena todas las lecturas de los centros y los parametros seleccinados, cada linea corresponde a un centro y a un parametro
	$con = 0;
	for($i = 0; $i < count($instalaciones); $i++){
		for($p = 0; $p < count($parametros); $p++){
			$lineas[$con] = array($num);
			$sql = "select * from lectura where Id_Centro = '" . $instalaciones[$i] . "' and Id_Parametro = '" . $parametros[$p] . "'  and (Fecha >= '" . $Finicio . "' and Fecha <= '" . $Ffin . "') ORDER BY Id_Centro, Id_Parametro, Fecha, Hora";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
			for ($c = 0; $c < $num; $c++){
				$fila = mysql_fetch_array($res);
				$lineas[$con][$c] = array();
				$lineas[$con][$c]['valor'] = $fila['Valor'];
				$lineas[$con][$c]['hora'] = $fila['Hora'];
				$lineas[$con][$c]['dia'] = $fila['Fecha'];
			}
			$con++;
		}
	}
	
	function crear_array_horas($aumentar, $Finicio, $todos){
		$array_horas = array();
		if($todos == 1){
			$inicio = mktime(0, 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));	//creo la hora de inicio
			$fin = mktime(23, 59, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));		//y la hora de fin
			$con = 0;
			do{
				$array_horas[$con] = strftime("%H:%M:00",$inicio);
				$con ++;
				$inicio =  mktime(strftime("%H",$inicio)+$aumentar, strftime("%M",$inicio), 0, strftime("%m",$inicio), strftime("%d",$inicio), strftime("%Y",$inicio));
			}while($inicio <= $fin);
		}else{
			$array_horas[0] = "00:00:00";
			$array_horas[1] = "23:00:00";
		}
		return $array_horas;
	}

	function crear_array_dias($aumentar, $Finicio, $Ffin, $r){
		//relleno el array de dias
		$array_dias = array();
		$inicio = mktime(0, 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));	//creo la fecha de inicio
		$fin = mktime(0, 0, 0, substr($Ffin,5,2), substr($Ffin,8,2), substr($Ffin,0,4));		//y la fecha de fin
		$con = 0;
		do{
			$cambio = 0;
			$mes_act =  strftime("%m",$inicio);
			$fecha_ant = mktime("0","0","0",substr($array_dias[$con-1],5,2), substr($array_dias[$con-1],8,2), substr($array_dias[$con-1],0,4));
			if($con != 0 && strftime("%d",$inicio) > 1 && $mes_act != strftime("%m",$fecha_ant)){
				$inicio =  mktime(strftime("%H",$inicio), strftime("%M",$inicio), 0, strftime("%m",$inicio), 1, strftime("%Y",$inicio));
				$con--;
				$cambio = 1;
			}else if($con != 0 && $r > 5 && date("t", $inicio) == strftime("%d",$inicio)){
				$dif_dias = ($inicio - $fecha_ant)/86400; //me da la diferencia en segundos, por lo que lo divido entre los segundos de un día
				if($dif_dias != $aumentar){
					$inicio =  mktime(strftime("%H",$inicio), strftime("%M",$inicio), 0, strftime("%m",$inicio), strftime("%d",$inicio)+1, strftime("%Y",$inicio));
					$cambio = 1;
				}
			}
			if(!in_array(strftime("%Y-%m-%d",$inicio),$array_dias)){	//si el dia no esta en el array, se pone
				$array_dias[$con] = strftime("%Y-%m-%d",$inicio);
				$con++;
			}
			if($cambio == 0)
				$inicio =  mktime(strftime("%H",$inicio), strftime("%M",$inicio), 0, strftime("%m",$inicio), strftime("%d",$inicio)+$aumentar, strftime("%Y",$inicio));
		}while($inicio <= $fin);	//mientras no llege al fin

		$fecha_ant = mktime("0","0","0",substr($array_dias[$con-1],5,2), substr($array_dias[$con-1],8,2), substr($array_dias[$con-1],0,4));
		if(!in_array(strftime("%Y-%m-%d",$fin),$array_dias)){
			$dif_dias = ($fin - $fecha_ant)/86400; //me da la diferencia en segundos, por lo que lo divido entre los segundos de un día
			if($dif_dias+1 < $aumentar/2){
				$array_dias[$con-1] = strftime("%Y",$fin) . "-" . strftime("%m",$fin) . "-" . strftime("%d",$fin);
			}
			else{
				$array_dias[$con] = strftime("%Y",$fin) . "-" . strftime("%m",$fin) . "-" . strftime("%d",$fin);
			}

		}
		return $array_dias;
	}

	function crear_array_meses($Finicio, $Ffin){
		$array_dias = array();
		$inicio = mktime(0, 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));	//creo la fecha de inicio
		$fin = mktime(0, 0, 0, substr($Ffin,5,2), substr($Ffin,8,2), substr($Ffin,0,4));		//y la fecha de fin
		$con = 0;
		if(!in_array(strftime("%Y-%m-%d",$inicio),$array_dias)){	//si el dia no esta en el array, se pone
			$array_dias[$con] = strftime("%Y-%m-%d",$inicio);
			$con++;
		}
		$inicio = mktime(0, 0, 0, substr($Finicio,5,2)+1, 1, substr($Finicio,0,4));
		do{
			if(!in_array(strftime("%Y-%m-%d",$inicio),$array_dias)){	//si el dia no esta en el array, se pone
				$array_dias[$con] = strftime("%Y-%m-%d",$inicio);
				$con++;
			}
			$inicio =  mktime(strftime("%H",$inicio), strftime("%M",$inicio), 0, strftime("%m",$inicio)+1, strftime("%d",$inicio), strftime("%Y",$inicio));
		}while($inicio <= $fin);	//mientras no llege al fin
		if(!in_array(strftime("%Y-%m-%d",$fin),$array_dias)){	//si el dia no esta en el array, se pone
			$array_dias[$con] = strftime("%Y-%m-%d",$fin);
			$con++;
		}
		return $array_dias;
	}

	$Horas = array();	//array que contiene todas las horas del dia
	$Dias = array();	//array que contiene todos los dias que estan dentro del rango seleccionado

	$otro_dia = 0;	//cuando se va de más de 1 día se indica aqui el salto
	$restar = 0;	//si solo se usan las 0 y las 23 se pone a 1 en el caso correspondiente, para que no repita las 23 al recorrer las horas
	$restar_mes = 0;
	$quitar_ultimo = 0;
	$Dias = crear_array_dias(1, $Finicio, $Ffin, 0);
	if($grafico == 1){
		if($rango == 1){
			$Horas = crear_array_horas(1, $Finicio, 1);
		}else if($rango == 2){
			$Horas = crear_array_horas(4, $Finicio, 1);
		}else if($rango == 3){
			$Horas = crear_array_horas(8, $Finicio, 1);
		}else if($rango == 4){
			$Horas = crear_array_horas(12, $Finicio, 1);
		}else if($rango == 5){
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
		}else if($rango == 6){
			$Horas = crear_array_horas(0, $Finicio, 0);
			$Dias = crear_array_dias(3, $Finicio, $Ffin, 6);
			$restar = 1;
			$restar_mes = 1;
			$otro_dia = 2;
		}else if($rango == 7){
			$Dias = crear_array_dias(6, $Finicio, $Ffin, 7);
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
			$restar_mes = 1;
			$otro_dia = 5;
		}else if($rango == 8){
			$Horas = crear_array_horas(0, $Finicio, 0, 8);
			$Dias = crear_array_dias(10, $Finicio, $Ffin,8);
			$restar = 1;
			$restar_mes = 1;
			$otro_dia = 9;
		}
	}else if($grafico == 2){
		if($rango == 1){
			$Horas = crear_array_horas(1, $Finicio, 1);
		}else if($rango == 2){
			$Horas = crear_array_horas(12, $Finicio, 1);
		}else if($rango == 3){
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
		}else if($rango == 4){
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
		}else if($rango == 5){
			$Dias = crear_array_dias(3, $Finicio, $Ffin, 5);
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
			$restar_mes = 1;
			$otro_dia = 2;
		}else if($rango == 6){
			$Dias = crear_array_dias(10, $Finicio, $Ffin, 6);
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
			$restar_mes = 1;
			$otro_dia = 9;
		}else if($rango == 7){
			$Dias = crear_array_meses($Finicio, $Ffin);
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
			$restar_mes = 1;
			$otro_mes = 1;
			$otro_dia = 31;
		}else if($rango == 8){
			$Dias = crear_array_meses($Finicio, $Ffin);
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
			$restar_mes = 1;
			$otro_mes = 1;
			$otro_dia = 31;
		}
	}else if($grafico == 3){
		if($rango == 1){
			$Horas = crear_array_horas(1, $Finicio, 1);
		}else if($rango >= 2 && $rango <= 5){
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
		}else if($rango >= 6 && $rango <= 8){
			$Dias = crear_array_meses($Finicio, $Ffin);
			$Horas = crear_array_horas(0, $Finicio, 0);
			$restar = 1;
			$restar_mes = 1;
			$otro_mes = 1;
			$otro_dia = 31;
			$quitar_ultimo = 1;
		}
	}
	
	for($i = 0; $i < count($lineas); $i++){
		$lineas_m[$i] = array();
		$contador = 0;

		for($d = 0; $d < count($Dias)-$restar_mes; $d++){
			for($h = 0; $h < count($Horas)-$restar; $h++){
				$acu = 0;
				$minimo = 1000;
				$maximo = -1000;
				$cont = 0;
				$hora_con_act = mktime(substr($Horas[$h],0,2), substr($Horas[$h],3,2), 0, substr($Dias[$d],5,2), substr($Dias[$d],8,2), substr($Dias[$d],0,4));
				if($h == (count($Horas)-1-$restar)){
					$poner_h = "24:00:00";
				}else
					$poner_h = $Horas[$h+1];
				if($otro_dia != 0){
					$hora_con_sig = mktime(substr($poner_h,0,2), substr($poner_h,3,2), 0, substr($Dias[$d+1],5,2), substr($Dias[$d+1],8,2)-1, substr($Dias[$d],0,4));	
					if(strftime("%m-%d",$hora_con_sig) == substr($Dias[$d+1],5,5) && $d < count($Dias)-$otro_dia-1){
						$hora_con_sig = mktime(substr($poner_h,0,2), substr($poner_h,3,2), 0, strftime("%m",$hora_con_sig), strftime("%d",$hora_con_sig)-1, strftime("%Y",$hora_con_sig));	
					}
				}else
					$hora_con_sig = mktime(substr($poner_h,0,2), substr($poner_h,3,2), 0, substr($Dias[$d],5,2), substr($Dias[$d],8,2), substr($Dias[$d],0,4));	
				for($p = 0; $p < count($lineas[$i]) && isset($lineas[$i][$p]['hora']); $p++){
					$hora_l =  mktime(substr($lineas[$i][$p]['hora'],0,2), substr($lineas[$i][$p]['hora'],3,2), 0, substr($lineas[$i][$p]['dia'],6,2), substr($lineas[$i][$p]['dia'],8,2), substr($lineas[$i][$p]['dia'],0,4));
					if($hora_l >= $hora_con_act && $hora_l < $hora_con_sig){
						$acu += $lineas[$i][$p]['valor'];
						$cont ++;
						if($lineas[$i][$p]['valor'] < $minimo)
							$minimo = $lineas[$i][$p]['valor'];
						if($lineas[$i][$p]['valor'] > $maximo)
							$maximo = $lineas[$i][$p]['valor'];
					}
				}
				if($cont != 0){
					$lineas_m[$i][$contador]['hora'] = strftime("%H:%M",$hora_con_act).":00";
					$lineas_m[$i][$contador]['dia'] = strftime("%Y-%m-%d",$hora_con_act);
					$lineas_m[$i][$contador]['valor'] = $acu / $cont;
					$lineas_m[$i][$contador]['minimo'] = $minimo;
					$lineas_m[$i][$contador]['maximo'] = $maximo;
					$contador ++;
				}

			}
		}
	}

	if(($grafico == 1 && $rango > 4)){	//se vuelve a crear el array de dias, porque ya se acumuló por meses pero se cogen dias, para que en el grafico salgan bien los intervalos
		$Dias = crear_array_dias(1, $Finicio, $Ffin, 0);
	}
	
	$lineas = array();
	$lineas = $lineas_m;

	for($i = 0; $i < count($lineas); $i++){		//si a cualquiera de las lineas le falta una hora se le pone, al final, y se le da un valor
		for($d = 0; $d < count($Dias)-$restar_mes; $d++){
			for($h = 0; $h < count($Horas)-$restar; $h++){
				$enc = 0;
				for($p = 0; $p < count($lineas[$i]); $p++){
					if($Dias[$d] == $lineas[$i][$p]['dia'] && $Horas[$h] == $lineas[$i][$p]['hora']){
						$enc = 1;
					}
				}
				if($enc == 0){
					$lineas[$i][$p]['hora'] = $Horas[$h];
					$lineas[$i][$p]['dia'] = $Dias[$d];
					$lineas[$i][$p]['valor'] = $separador_valores;
					$lineas[$i][$p]['maximo'] = $separador_valores;
					$lineas[$i][$p]['minimo'] = $separador_valores;
				}
			}
		}
		
	}

	for($i = 0; $i < count($lineas); $i++){		//ordeno las lineas por horas
		$orden = array();
		$orden1 = array();
		foreach ($lineas[$i] as $llave => $fila) {
		   $orden[$llave]  = $fila['dia'];
		   $orden1[$llave]  = $fila['hora'];
		}
		array_multisort($orden, SORT_ASC, $orden1, SORT_ASC, $lineas[$i]);
	}

	$lineas1 = array();
	for($i = 0; $i < count($lineas); $i++){
		$lineas1[$i] = array();
		$puestos = 0;
		for($p = 0; $p < count($lineas[$i]); $p++){
			if($grafico == 1 || $grafico == 3){
				$lineas1[$i][$puestos] = $lineas[$i][$p]['valor'];
				$puestos ++;
			}else if($grafico == 2){
				$lineas1[$i][$puestos] = $lineas[$i][$p]['maximo']; 
				$puestos ++;
				$lineas1[$i][$puestos] = $lineas[$i][$p]['minimo']; 
				$puestos ++;
			}
		}
	}

	$horas = array();
	$cont = 0;														
	if(($grafico == 1 && $rango < 5) || ($grafico == 2 && $rango < 3) || ($grafico == 3 && $rango < 2)){
		for($d = 0; $d < count($Dias); $d++){
			for($h = 0; $h < count($Horas); $h++){
				$horas[$cont] = substr($Horas[$h],0,5);
				if($rango != 1 && $horas[$cont] == "00:00"){
					$horas[$cont] = substr($Dias[$d],8,2) . "/" . substr($Dias[$d],5,2) . "/" . substr($Dias[$d],2,2);
				}
				$cont++;
			}
		}
		if($grafico == 1){
			if($rango != 1){
				$fin = mktime(0, 0, 0, substr($Ffin,5,2), substr($Ffin,8,2)+1, substr($Ffin,0,4));
				$horas[count($horas)] = strftime("%d/%m/%y",$fin);
			}else
				$horas[count($horas)] = "24:00";
			$lineas1[0][count($lineas1[0])] = $separador_valores;
		}
	}else{
		for($d = 0; $d < count($Dias); $d++){
			$horas[$cont] = substr($Dias[$d],8,2) . "/" . substr($Dias[$d],5,2) . "/" . substr($Dias[$d],2,2);
			$cont++;
		}
	}

	$enc = 0;
	$poner = 0;
	for($i = 0; $i < count($lineas1); $i++){
		if(($grafico == 1 || $grafico == 3) && count($horas) != count($lineas1[$i])){
			$lineas1[$i][count($lineas1[$i])] = $separador_valores;
		}else if($grafico == 2 && (count($horas)*2) != count($lineas1[$i])){
			$lineas1[$i][count($lineas1[$i])] = $separador_valores;
			$lineas1[$i][count($lineas1[$i])] = $separador_valores;
		}
		$valores = 0;
		for($p = 0; $p < count($lineas1[$i]); $p++){
			if($lineas1[$i][$p] != $separador_valores){
				$valores++;
			}
		}
		if((($grafico == 1) && $valores > 1) || ($grafico == 2 && $valores > 2) || ($grafico == 3 && $valores > 0))
			$enc = 1;
	}

	if($max_cont > count($horas)){
		for($i = 0; $i < count($lineas1); $i++){
			$lineas_tem = array();
			for($p = 0; $p < count($lineas1[$i]); $p++){
				if($p < count($horas))
					$lineas_tem[$p] = $lineas1[$i][$p];
			}
			$lineas1[$i] = $lineas_tem;
		}
	}

	if($quitar_ultimo == 1){
		$lineas_tem = array();
		for($i = 0; $i < count($lineas1); $i++){
			$lineas_tem[$i] = array();
			for($p = 0; $p < count($lineas1[$i])-1; $p++){
				$lineas_tem[$i][$p] = $lineas1[$i][$p];
			}
			$lineas1[$i] = $lineas_tem[$i];
		}
	}

	$_SESSION['valores_grafico'] = $enc;
	$_SESSION['lineas_grafico'] = $lineas1;
	$_SESSION['horas_grafico'] = $horas;
	$_SESSION['rango_grafico'] = $rango;
	
	if(isset($_SESSION['id_foto_grafico_lineal_fechas'])){
		$_SESSION['id_foto_grafico_lineal_fechas'] ++;
	}else{
		$_SESSION['id_foto_grafico_lineal_fechas'] = 1;
	}

?>
<html>
<body>
<br><br><br>
<?
if($grafico == 1){
?>
	<img src="./paginas/imagen_grafica_lineas_fecha.php?d=<?=$_SESSION['id_foto_grafico_lineal_fechas']?>">
<?
}else if($grafico == 2){
?>
	<img src="./paginas/imagen_grafica_medias_fecha.php?d=<?=$_SESSION['id_foto_grafico_lineal_fechas']?>">
<?
}else if($grafico == 3){
?>
	<img src="./paginas/imagen_grafica_barras_fecha.php?d=<?=$_SESSION['id_foto_grafico_lineal_fechas']?>">
<?
}	
?>
<script>
	window.location.href="principal.php?pag=grafico_lineal_fechas.php&rango=<?=$rango?>";
</script>
</body>
</html>