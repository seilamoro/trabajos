<?
	session_start();
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("SCIES");
	$grafico=$_POST['grafico'];
	
	$Finicio = $_POST['anio_inicio'] . "-" . $_POST['mes_inicio'] . "-" . $_POST['dia_inicio'];
	$_SESSION['fecha_grafico']=$Finicio;
	$_SESSION['grafico_fondo']=$_POST['fondo_img'];
	
	$_SESSION['fondo']=$_POST['fondo'];
	//$_SESSION['fecha_grafico1']=$Finicio1;
	$Hinicio=$_POST['Hinicio'];
	$_SESSION['hora_ini_grafico']=$Hinicio;
	$Hfin=$_POST['Hfin'];
	$_SESSION['hora_fin_grafico']=$Hfin;
	$instalaciones = $_POST['ins_seleccionadas'];
		$parametros = $_POST['para_seleccionadas'];
		$_SESSION['instalaciones_selec_grafico'] = array();
		$_SESSION['parametros_selec_grafico'] = array();
		$_SESSION['instalaciones_selec_grafico'] = $instalaciones;
		$_SESSION['parametros_selec_grafico'] = $parametros;
	
	//$fin = $fecha_sel = mktime(substr($Hfin,0,2), substr($Hfin,3,2), 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));	
 // para el grafico 1
 if($Hfin=="24:00"){
 			$Hfin="23:59";
			$_SESSION['hora_fin_grafico']=$Hfin;
 }

 if($grafico==1||$grafico==4){
//$Hfin=  substr($Hfin,0,2)+1 .":00";
//$Hinicio=substr($Hinicio,0,2) .":00";
	$lineas = array(count($instalaciones) * count($parametros));
	$leyenda = array(count($instalaciones) * count($parametros));
	$con = 0;
	for($i = 0; $i < count($instalaciones); $i++){
		for($p = 0; $p < count($parametros); $p++){
			$lineas[$con] = array($num);
				if($Hfin=="23:59"){
 
  			$sql = "select * from lectura where Id_Centro = '" . $instalaciones[$i] . "' and Id_Parametro = '" . $parametros[$p] . "' and Fecha = '" . $Finicio . "' and (Hora >= '" . $Hinicio . "' and Hora <= '" . $Hfin . "')";

 }else{
 			$sql = "select * from lectura where Id_Centro = '" . $instalaciones[$i] . "' and Id_Parametro = '" . $parametros[$p] . "' and Fecha = '" . $Finicio . "' and (Hora >= '" . $Hinicio . "' and Hora < '" . $Hfin . "')";
 }
			//$sql = "select * from lectura where Id_Centro = '" . $instalaciones[$i] . "' and Id_Parametro = '" . $parametros[$p] . "' and Fecha = '" . $Finicio . "' and (Hora >= '" . $Hinicio . "' and Hora < '" . $Hfin . "')";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
		
			for ($c = 0; $c < $num; $c++){
				$fila = mysql_fetch_array($res);
				$lineas[$con][$c] = array();
				$lineas[$con][$c]['valor'] = $fila['Valor'];
				$lineas_g4[$con][$c]['valor'] = $fila['Valor'];
				$lineas[$con][$c]['hora'] = $fila['Hora'];
				$con1++;
			}
			$leyenda[$con] = array();
			$leyenda[$con][0] = $instalaciones[$i];
			$leyenda[$con][1] = $parametros[$p];
				
			$con++;
		}
	}	
	
	$Horas = array();
	$con = 1;

	$inicio =  mktime(substr($Hinicio,0,2), 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	if(substr($Hfin,0,2) == "23" || (substr($Hfin,0,2) == "22" && substr($Hfin,3,2) != "00"))
		$fin =  mktime(23, 59, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	else if(substr($Hfin,3,2) == "00")
		$fin =  mktime(substr($Hfin,0,2), 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));	
	else
		$fin =  mktime(substr($Hfin,0,2)+1, 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	
	$dif = $fin - $inicio;
	$diferencia = strftime("%H",$dif)-1;
	if($diferencia < 0)
		$diferencia = 23;
	do{
		$Horas[$con] = strftime("%H:%M:00",$inicio);
		$con ++;
		$inicio =  mktime(strftime("%H",$inicio), strftime("%M",$inicio)+1, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	}while($inicio <= $fin);
	
	$orden = array();
	for($h = 0; $h < count($Horas); $h++){	//ordeno las horas
		$orden[$h] = $Horas[$h];
	}
	array_multisort($orden, SORT_ASC, $Horas);
	
	for($i = 0; $i < count($lineas); $i++){		//si a cualquiera de las lineas le falta una hora se le pone, al final, y se le da un valor
		for($h = 0; $h < count($Horas); $h++){
			$enc = 0;
			for($p = 0; $p < count($lineas[$i]); $p++){
				if($Horas[$h] == $lineas[$i][$p]['hora']){
					$enc = 1;
				}
			}
			if($enc == 0){
				$lineas[$i][$p]['hora'] = $Horas[$h];
					$lineas[$i][$p]['valor'] = "-";
					
			}
		}
	}
	
	for($i = 0; $i < count($lineas); $i++){		//ordeno las lineas por horas
		$orden = array();
		foreach ($lineas[$i] as $llave => $fila) {
		   $orden[$llave]  = $fila['hora'];
		}
		array_multisort($orden, SORT_ASC, $lineas[$i]);
	}
	if($grafico==4){
			for($i = 0; $i < count($lineas); $i++){		//ordeno las lineas por horas
				$orden = array();
				foreach ($lineas_g4[$i] as $llave => $fila) {
				   $orden[$llave]  = $fila['hora'];
				}
				array_multisort($orden, SORT_ASC, $lineas_g4[$i]);
			}
	}

	$lineas1 = array();
	for($i = 0; $i < count($lineas); $i++){
		$lineas1[$i] = array();
		for($p = 0; $p < count($lineas[$i]); $p++){
			$lineas1[$i][$p] = $lineas[$i][$p]['valor']; 
		}
	}
	
	
	$horas = array();
	for($h = 0; $h < count($Horas); $h++){
		$horas[$h] = substr($Horas[$h],0,5);
		
	}

	$enc = 0;
	for($i = 0; $i < count($lineas1); $i++){
		for($p = 1; $p < count($lineas1[$i]); $p++){
			if($lineas1[$i][$p] != "-")
				$enc += 1;
		}
	}

	$_SESSION['grafico_grafico']=1;
	$_SESSION['valores_grafico'] = $enc;
	$_SESSION['lineas_grafico'] = $lineas1;
	//$_SESSION['lineas_grafico_g4'] = $lineas1_g4;
	//$_SESSION['leyenda_grafico'] = $leyenda;
	$_SESSION['horas_grafico'] = $horas;
	$_SESSION['diferencia_grafico'] = $diferencia;
	}else{
	//echo $Finicio;
	//grafico2
	$lineas = array(count($instalaciones) * count($parametros));
	$leyenda = array(count($instalaciones) * count($parametros));
	
	$con = 0;
	for($i = 0; $i < count($instalaciones); $i++){
		for($p = 0; $p < count($parametros); $p++){
			$lineas[$con] = array($num);
			if($Hfin=="23:59"){
  				$sql = "select * from lectura where Id_Centro = '" . $instalaciones[$i] . "' and Id_Parametro = '" . $parametros[$p] . "' and Fecha = '" . $Finicio . "' and (Hora >= '" . $Hinicio . "' and Hora <= '" . $Hfin . "')";
 			}else{
 			$sql = "select * from lectura where Id_Centro = '" . $instalaciones[$i] . "' and Id_Parametro = '" . $parametros[$p] . "' and Fecha = '" . $Finicio . "' and (Hora >= '" . $Hinicio . "' and Hora < '" . $Hfin . "')";
 			}
			
			
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
		
			for ($c = 0; $c < $num; $c++){
				$fila = mysql_fetch_array($res);
				$lineas[$con][$c] = array();
				$lineas[$con][$c]['valor'] = $fila['Valor'];
				$lineas[$con][$c]['hora'] = $fila['Hora'];
				
				$con1++;
			}
			$leyenda[$con] = array();
			$leyenda[$con][0] = $instalaciones[$i];
			$leyenda[$con][1] = $parametros[$p];
				
			$con++;
		}
	}
	
	$inicio =  mktime(substr($Hinicio,0,2), 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	if(substr($Hfin,0,2) == "23" || (substr($Hfin,0,2) == "22" && substr($Hfin,3,2) != "00"))
		$fin =  mktime(23, 59, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	else if(substr($Hfin,3,2) == "00")
		$fin =  mktime(substr($Hfin,0,2), 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));	
	else
		$fin =  mktime(substr($Hfin,0,2)+1, 0, 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	
	$dif = $fin - $inicio;
	$diferencia = strftime("%H",$dif)-1;
	if($diferencia < 0)
		$diferencia = 23;
	do{
		$Horas[$con] = strftime("%H:%M:00",$inicio);
		$con ++;
		$inicio =  mktime(strftime("%H",$inicio)+1, strftime("%M",$inicio), 0, substr($Finicio,5,2), substr($Finicio,8,2), substr($Finicio,0,4));
	}while($inicio <= $fin);
	
	$orden = array();
	for($h = 0; $h < count($Horas); $h++){	//ordeno las horas
		$orden[$h] = $Horas[$h];
		
	}
	array_multisort($orden, SORT_ASC, $Horas);
	
	
	for($i = 0; $i < count($lineas); $i++){
			$lineas_m[$i] = array();
			$contador = 0;
			for($h = 0; $h < count($Horas)-1; $h++){
					$acu = 0;
					$cont = 0;
					$min= 9999;
					$max= -9999;
					$hora_con_act = mktime(substr($Horas[$h],0,2), substr($Horas[$h],3,2), 0, substr($Finicio,6,2), substr($Finicio,8,2), substr($Finicio,0,4));
					if($h == (count($Horas)-1)){
						$poner_h = "23:59:00";
					}else
						$poner_h = $Horas[$h+1];
					$hora_con_sig = mktime(substr($poner_h,0,2), substr($poner_h,3,2), 0, substr($Finicio,6,2), substr($Finicio,8,2), substr($Finicio,0,4));		
					
					for($p = 0; $p < count($lineas[$i]); $p++){
					if($lineas[$i][$p]['valor']!=""){
						$hora_l =  mktime(substr($lineas[$i][$p]['hora'],0,2), substr($lineas[$i][$p]['hora'],3,2), 0, substr($Finicio,6,2), substr($Finicio,8,2), substr($Finicio,0,4));
						if($hora_l >= $hora_con_act && $hora_l < $hora_con_sig){
							$acu += $lineas[$i][$p]['valor'];
							if($lineas[$i][$p]['valor']>$max)
								$max=$lineas[$i][$p]['valor'];
								
							if($lineas[$i][$p]['valor']<$min){
									$min=$lineas[$i][$p]['valor'];
									
									}
							
							$cont ++;
						}
						}
						
					}
					
					
					if($cont != 0){
						
						$lineas_m[$i][$contador]['media'] = $acu / $cont;
						$lineas_m[$i][$contador]['hora'] = substr($Horas[$h],0,2);//.":00:00"
						$lineas_m[$i][$contador]['max'] = $max;
						
						/*if($min=="")
							$min=$max;*/
						
								$lineas_m[$i][$contador]['min'] = $min;
							
						$contador ++;
					}

				}
		}
		
		if($Hfin=="23:59"){
			$contador_horas=count($Horas);
		}else
			$contador_horas=count($Horas)-1;
			
		for($i = 0; $i < count($lineas_m); $i++){		//si a cualquiera de las lineas le falta una hora se le pone, al final, y se le da un valor
		for($h = 0; $h < $contador_horas; $h++){///
			$enc = 0;
			for($p = 0; $p < count($lineas_m[$i]); $p++){
				if(substr($Horas[$h],0,2) == $lineas_m[$i][$p]['hora']){
					$enc = 1;
				}
			}
			if($enc == 0){
				$lineas_m[$i][$p]['hora'] = $Horas[$h];
				$lineas_m[$i][$p]['valor'] = 0;
				$lineas_m[$i][$p]['max'] = 0;
				$lineas_m[$i][$p]['min'] = 0;
				$lineas_m[$i][$p]['media'] = 0;
			}
		}
	}
		for($i = 0; $i < count($lineas_m); $i++){		//ordeno las lineas por horas
		$orden = array();
		
		foreach ($lineas_m[$i] as $llave => $fila) {
		   
		   $orden[$llave]  = $fila['hora'];
		}
		array_multisort($orden, SORT_ASC,  $lineas_m[$i]);
	}
	$cont=0;
	if($grafico==2){
	$enc=0;
	for($i = 0; $i < count($lineas_m); $i++){//creo el array para el grafico2
	$cont=0;
		for($p = 0; $p < count($lineas_m[$i]); $p++){
		
	if($lineas_m[$i][$p]['max']!=0||$lineas_m[$i][$p]['min']!=0)
		$enc+=1;
			for($x=0;$x<2;$x++){
			
					if($x==0){
									if($cont==0){
										$datos_grafico1[$i][$cont]=$lineas_m[$i][$p]['max'];
										$cont=$cont+1;
										}
									else{
									$datos_grafico1[$i][$cont]=$lineas_m[$i][$p]['max'];
										$cont=$cont+1;
									}
						
						}else if($x==1){
								$datos_grafico1[$i][$cont]=$lineas_m[$i][$p]['min'];
								$cont=$cont+1;
						}
			
			}
			
		}
	}
	

	
	
	$_SESSION['valores_grafico'] = $enc;
	$_SESSION['grafico_grafico']=2;
	$_SESSION['leyenda_grafico'] = $leyenda;
	$_SESSION['valores'] = $datos_grafico1;
	$_SESSION['horas_grafico'] = $Horas;
	$_SESSION['lineas_grafico'] = $lineas_m;
	}
		if($grafico==3){
		$enc=0;
			$cont=0;
			for($i = 0; $i < count($lineas_m); $i++){
				if($cont<count($lineas_m[$i]))
					$cont=count($lineas_m[$i]);
			}
			for($i = 0; $i < count($lineas_m); $i++){
			$contador=0;
				for($p = 0; $p < $cont; $p++){
					if($lineas_m[$i][$p]['media']){
						$datos_grafico1[$i][$contador]=$lineas_m[$i][$p]['media'];
						$enc=$enc+1;
					}else
						$datos_grafico1[$i][$contador]=0;
					$contador ++;
					}
				}
				
				
				  for($d=0;$d < count($Horas)-1;$d++){
$horas_cortadas[$d]=$Horas[$d];
}	
	

	 				$_SESSION['grafico_grafico']=3;
					$_SESSION['leyenda_grafico'] = $leyenda;
					$_SESSION['valores_grafico'] = $enc;
					$_SESSION['valores'] = $datos_grafico1;
					$_SESSION['horas_grafico'] = $Horas;
					$_SESSION['lineas_grafico'] = $lineas_m;
			
			}
	}
		if(isset($_SESSION['id_foto_grafico_lineal_fechas'])){
		$_SESSION['id_foto_grafico_lineal_fechas'] ++;
	}else{
		$_SESSION['id_foto_grafico_lineal_fechas'] = 1;
	}
	
?>
<html>
<body>


<?
if($grafico==1){
?>
<img src="./paginas/imagen_grafico_temperaturas_dia.php?d=<?=$_SESSION['id_foto_grafico_lineal_fechas']?>">
<?
}else if($grafico==2){
?>
<img src="./paginas/imagen_grafico_temperaturas_dia_2.php?d=<?=$_SESSION['id_foto_grafico_lineal_fechas']?>">
<?
}else if($grafico==3){
?>
<img src="./paginas/imagen_grafico_temperaturas_dia_3.php?d=<?=$_SESSION['id_foto_grafico_lineal_fechas']?>">
<?
}
?>

<script>
window.location.href="principal.php?pag=grafico_temperaturas_dia.php";
</script>
</body>
</html>