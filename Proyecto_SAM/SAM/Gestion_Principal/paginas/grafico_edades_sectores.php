<?php
	session_start();

	/* Esta página esta incluida dentro de la página de tabla de estadísticas y
	la de tabla de estadísticas incluida a su vez dentro de la pagina de estadisticas 
	y todas las variables de sesion que se usan en esta página se obtienen de la página de valores de estadística
	y otras de las otras páginas que estan incluidas dentro de la página estadisticas.php */
	
	include ("src/jpgraph.php");
	include ("src/jpgraph_pie.php");
	include ("src/jpgraph_pie3d.php");
	include ("src/jpgraph_canvas.php");



	//Tipo estadística: 1(diaria), 2(mensual), 3(trimestral), 4(semestral), 5(anual)
	$tipo = $_SESSION['tiempo'];
	$mes_inicio = $_SESSION['mes_est'];
	$anio_inicio = $_SESSION['anio_est'];

	//Inicializamos contadores
	$edad_0_9 = 0;
	$edad_10_19 = 0;
	$edad_20_25 = 0;
	$edad_26_29 = 0;
	$edad_30_39 = 0;
	$edad_40_49 = 0;
	$edad_50_59 = 0;
	$edad_60_69 = 0;
	$edad_70 = 0;
	

	//Obtenemos la fecha
	$fecha = date("d-m-Y");
	$fecha_partida = split("-", $fecha);
	$fecha_dia = $fecha_partida[0];
	$fecha_mes = $fecha_partida[1];
	$fecha_anyo = $fecha_partida[2];
	$fecha_anyo_sig = $anio_inicio + 1;
	$fecha_anyo_ant = $anio_inicio - 1;
	
	//Conexión a la base de datos
	$db=MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if(!$db) 
	{ 
		echo("ERROR DURANTE LA CONEXIÓN A LA BASE DE DATOS");
	} 

	mysql_select_db($_SESSION['conexion']['db']);



	
	
		
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es mensual : se selecionan las pernoctas o estancias de grupo que se encuentren dentro de el mes seleccionado
	o que esten pernoctando en el albergue durante el mes seleccionado*/
	if($tipo == 2) {
		$sql = "SELECT * FROM pernocta WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and Fecha_Salida <> '".$anio_inicio."-".$mes_inicio."-01';"; 
		$sql_gr = "SELECT * FROM estancia_gr WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and Fecha_Salida <> '".$anio_inicio."-".$mes_inicio."-01';"; 
		$sql_p = "SELECT * FROM pernocta_p WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and Fecha_Salida <> '".$anio_inicio."-".$mes_inicio."-01';"; 
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	//Estadística del año en curso
	
	//Si la estadística es anual: se seleccionan las pernoctas y estancias de grupo que se encuentren dentro del año que se ha seleccionado
	
	if($tipo == 5) {
		$sql="Select * from pernocta where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
		$sql_p="Select * from pernocta_p where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
		$sql_gr="Select * from estancia_gr where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es trimestral o semestral :se selecionan las pernoctas o estancias de grupo que se encuentren dentro de el trimestre
	o del semestre seleccionado	o que esten pernoctando en el albergue durante el estos*/
	
	if(($tipo == 3) || ($tipo == 4)) {
		
		
		if($tipo == 3) {
			$sem_trim = $mes_inicio + 2;
		} else {
			$sem_trim = $mes_inicio + 5;
		}

		$fecha_inicio = date("Y-m", mktime(0, 0, 0, $mes_inicio, 1, $anio_inicio));
		$fecha_fin = date("Y-m", mktime(0, 0, 0, $sem_trim, 1, $anio_inicio));

		$sql = "SELECT * FROM pernocta WHERE (SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."') and Fecha_Salida <> '".$fecha_inicio."-01'";
		$sql_gr = "SELECT * FROM estancia_gr WHERE (SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."') and Fecha_Salida <> '".$fecha_inicio."-01'";
		$sql_p = "SELECT * FROM pernocta_p WHERE (SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."') and Fecha_Salida <> '".$fecha_inicio."-01'";
	}

	//--------------------------------------------------------------------------------------------------------------
	/*Diaria : se selecionan las pernoctas o estancias de grupo que se encuentren dentro del dia seleccionado
	o que esten pernoctando en el albergue durante ese dia*/
	
	if($tipo == 1) {
		$$sql = "Select * from pernocta where (pernocta.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) and pernocta.Fecha_Salida <> '".date("Y")."-".date("m")."-01';";
		$sql_gr = "Select * from estancia_gr where (estancia_gr.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (estancia_gr.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and estancia_gr.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) and pernocta.Fecha_Salida <> '".date("Y")."-".date("m")."-01';";
		$sql_p = "Select * from pernocta_p where (pernocta_p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta_p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta_p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) and pernocta.Fecha_Salida <> '".date("Y")."-".date("m")."-01';";
	}

//Creamos el título de la gráfica

$meses_est=array(" ", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$mes_elegido=" ";
$mes_trimestre=" ";
$mes_semestre=" ";

for ($i=1; $i<=12; $i++) {
	if($_SESSION['mes_est'] == 0) {
		$mes_elegido = $meses_est[1];
		$mes_trimestre = $meses_est[3];
	}
	if($i == $_SESSION['mes_est']) {
		$mes_elegido = $meses_est[$i];	
	}
}

$titulo=" ";

if($sem_trim > 12) {
	$sem_trim = $sem_trim - 12;
	$anio = $_SESSION['anio_est'];
	$anio = $anio + 1;
} else {
	$anio = $_SESSION['anio_est'];
}

if(isset($_SESSION['mes_est'])) {
	if($_SESSION['tiempo'] == 2) {
		$titulo=$mes_elegido." ".$_SESSION['anio_est'];
	}
	if($_SESSION['tiempo'] == 3) {
		$titulo=$mes_elegido." - ".$meses_est[$sem_trim]." ".$anio;
	}
	if($_SESSION['tiempo'] == 4) {
		$titulo=$mes_elegido." - ".$meses_est[$sem_trim]." ".$anio;
	}
}

if($_SESSION['tiempo'] == 1) {
	$titulo=date('d-m-Y');
}
if($_SESSION['tiempo'] == 5) {
	$titulo=$_SESSION['anio_est'];
}
//Fin del título



	/*Consulta de alberguistas se hace una seleccion de los alberguistas que hayan pernoctado en el albergue
	durante el periodo de tiempo que el usuario seleccione en la gráfica*/
	
	$result = mysql_query($sql);
	
	for ($i=0;$i<mysql_num_rows($result);$i++){
		$fila = mysql_fetch_array($result);
		$dni = $fila['DNI_Cl'];
		$sql2 = "SELECT * FROM cliente WHERE DNI_Cl = '".$dni."'";
		$result2 = mysql_query($sql2);
			for ($j=0;$j<mysql_num_rows($result2);$j++){
				$fila2 = mysql_fetch_array($result2);
			}
			$fecha_nac = $fila2['Fecha_Nacimiento_Cl'];

			//Obtenemos la fecha de nacimiento partida
			$fecha_nac_partida = split("-", $fecha_nac);
			$fecha_nac_dia = $fecha_nac_partida[2];
			$fecha_nac_mes = $fecha_nac_partida[1];
			$fecha_nac_anyo = $fecha_nac_partida[0];
			
			$anyo_resta = $anio_inicio - $fecha_nac_anyo;

			if($fecha_mes > $fecha_nac_mes) {
				$edad = $anyo_resta;
			}

			if($fecha_mes < $fecha_nac_mes) {
				$edad = $anyo_resta -1;
			}

			if($fecha_mes == $fecha_nac_mes) {
				
				if($fecha_dia > $fecha_nac_dia) {
					$edad = $anyo_resta;
				}

				if($fecha_dia < $fecha_nac_dia) {
					$edad = $anyo_resta -1;
				}

				if($fecha_dia == $fecha_nac_dia) {
					$edad = $anyo_resta;
				}
			}



		//Calculamos a que intervalo de edad pertenece
		if($edad < 10) {
			$edad_0_9 = $edad_0_9 +1;
		}

		if( ($edad > 9) && ($edad < 20) ) {
			$edad_10_19 = $edad_10_19 +1;
		}
		
		if( ($edad > 19) && ($edad < 26) ) {
			$edad_20_25 = $edad_20_25 +1;
		}
		
		if( ($edad > 25) && ($edad < 30) ) {
			$edad_26_29 = $edad_26_29 +1;
		}

		if( ($edad > 29) && ($edad < 40) ) {
			$edad_30_39 = $edad_30_39 +1;
		}

		if( ($edad > 39) && ($edad < 50) ) {
			$edad_40_49 = $edad_40_49 +1;
		}

		if( ($edad > 49) && ($edad < 60) ) {
			$edad_50_59 = $edad_50_59 +1;
		}

		if( ($edad > 59) && ($edad < 70) ) {
			$edad_60_69 = $edad_60_69 +1;
		}
	
		if($edad > 69) {
			$edad_70 = $edad_70 +1;
		}
	}







	/*Consulta de peregrinos : se hace una seleccion de los peregrinos que hayan pernoctado en el albergue
	durante el periodo de tiempo que el usuario seleccione en la gráfica*/
	
	$result_p = mysql_query($sql_p);
	
	for ($i=0;$i<mysql_num_rows($result_p);$i++){
		$fila_p = mysql_fetch_array($result_p);
		$dni_p = $fila_p['DNI_Cl'];
		$sql2_p = "SELECT * FROM cliente WHERE DNI_Cl = '".$dni_p."'";
		$result2_p = mysql_query($sql2_p);
			for ($j=0;$j<mysql_num_rows($result2_p);$j++){
				$fila2_p = mysql_fetch_array($result2_p);
			}
			$fecha_nac = $fila2_p['Fecha_Nacimiento_Cl'];

			//Obtenemos la fecha de nacimiento partida
			$fecha_nac_partida = split("-", $fecha_nac);
			$fecha_nac_dia = $fecha_nac_partida[2];
			$fecha_nac_mes = $fecha_nac_partida[1];
			$fecha_nac_anyo = $fecha_nac_partida[0];
			
			$anyo_resta = $anio_inicio - $fecha_nac_anyo;

			if($fecha_mes > $fecha_nac_mes) {
				$edad = $anyo_resta;
			}

			if($fecha_mes < $fecha_nac_mes) {
				$edad = $anyo_resta -1;
			}

			if($fecha_mes == $fecha_nac_mes) {
				
				if($fecha_dia > $fecha_nac_dia) {
					$edad = $anyo_resta;
				}

				if($fecha_dia < $fecha_nac_dia) {
					$edad = $anyo_resta -1;
				}

				if($fecha_dia == $fecha_nac_dia) {
					$edad = $anyo_resta;
				}
			}



		//Calculamos a que intervalo de edad pertenece
		if($edad < 10) {
			$edad_0_9 = $edad_0_9 +1;
		}

		if( ($edad > 9) && ($edad < 20) ) {
			$edad_10_19 = $edad_10_19 +1;
		}
		
		if( ($edad > 19) && ($edad < 26) ) {
			$edad_20_25 = $edad_20_25 +1;
		}
		
		if( ($edad > 25) && ($edad < 30) ) {
			$edad_26_29 = $edad_26_29 +1;
		}

		if( ($edad > 29) && ($edad < 40) ) {
			$edad_30_39 = $edad_30_39 +1;
		}

		if( ($edad > 39) && ($edad < 50) ) {
			$edad_40_49 = $edad_40_49 +1;
		}

		if( ($edad > 49) && ($edad < 60) ) {
			$edad_50_59 = $edad_50_59 +1;
		}

		if( ($edad > 59) && ($edad < 70) ) {
			$edad_60_69 = $edad_60_69 +1;
		}
	
		if($edad > 69) {
			$edad_70 = $edad_70 +1;
		}
	}






	/*Consulta de grupos : se realiza una seleccion de las estancias de los grupos teniendo en cuenta que la fecha de llegada se encuentre
	dentro del rango temporal que se seleccionara anteriormente*/
	
	$result_gr = mysql_query($sql_gr);

	for ($i=0;$i<mysql_num_rows($result_gr);$i++) {
		$fila_gr = mysql_fetch_array($result_gr);
		$nombre_gr = $fila_gr['Nombre_Gr'];
		$fecha_gr = $fila_gr['Fecha_Llegada'];
		$sql2_gr = "Select * from estancia_gr where Nombre_Gr = '".$nombre_gr."' and Fecha_Llegada = '".$fecha_gr."'";
		$result2_gr = mysql_query($sql2_gr);
		for ($j=0; $j<mysql_num_rows($result2_gr); $j++) {
			$fila2_gr = mysql_fetch_array($result2_gr);
		}
		
		//Variables donde se almacena el resultado de los grupos
		
		$num_0_9 = $fila2_gr['HGr0_9'] + $fila2_gr['FGr0_9'];
		$num_10_19 = $fila2_gr['HGr10_19'] + $fila2_gr['FGr10_19'];
		$num_20_25 = $fila2_gr['HGr20_25'] + $fila2_gr['FGr20_25'];
		$num_26_29 = $fila2_gr['HGr26_29'] + $fila2_gr['FGr26_29'];
		$num_30_39 = $fila2_gr['HGr30_39'] + $fila2_gr['FGr30_39'];
		$num_40_49 = $fila2_gr['HGr40_49'] + $fila2_gr['FGr40_49'];
		$num_50_59 = $fila2_gr['HGr50_59'] + $fila2_gr['FGr50_59'];
		$num_60_69 = $fila2_gr['HGr60_69'] + $fila2_gr['FGr60_69'];
		$num_70 = $fila2_gr['HGrOtras'] + $fila2_gr['FGrOtras'];

		//resultados totales de la gente que hay en cada rango de edad

		$edad_0_9 = $edad_0_9 + $num_0_9;
		$edad_10_19 = $edad_10_19 + $num_10_19;
		$edad_20_25 = $edad_20_25 + $num_20_25;
		$edad_26_29 = $edad_26_29 + $num_26_29;
		$edad_30_39 = $edad_30_39 + $num_30_39;
		$edad_40_49 = $edad_40_49 + $num_40_49;
		$edad_50_59 = $edad_50_59 + $num_50_59;
		$edad_60_69 = $edad_60_69 + $num_60_69;
		$edad_70 = $edad_70 + $num_70;
	}


	//Array con los resultados de cada grupo de edad

	$data = array($edad_0_9,$edad_10_19,$edad_20_25,$edad_26_29,$edad_30_39,$edad_40_49,$edad_50_59,$edad_60_69,$edad_70);
	$datay2 = array(0,0,0,0,0,0,0,0,0);

	if ($data == $datay2){
	/***************************** INICIO NO HAY DATOS *********************************/
	$graph = new CanvasGraph(700,560,'auto');
	$t11 = new Text("No Existen Datos");
	$t11->Pos(0.35,0.45);
	$t11->SetOrientation("h");
	$t11->SetFont(FF_FONT2,FS_BOLD);
	$t11->SetBox("white","black","black");
	$t11->SetColor("red");
	$graph->AddText($t11);
	}else{

					
	$graph = new PieGraph(700,560,"auto");
	
	$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
	$graph->SetMarginColor('white');
	
	$graph->SetScale("textint");
	$graph->SetShadow();
	
	$graph->title->Set("Grupos de Edades (".$titulo.")");
	$graph->title->SetFont(FF_FONT2,FS_BOLD);
	$graph->title->SetColor("#000000");
	
	$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
	$graph->SetColor("#f4fcff");
	
	
	$p1 = new PiePlot3D($data);
	$p1->SetSize(0.45);
	$p1->SetCenter(0.41, 0.5);
	
	$entrada = array("0-9", "10-19", "20-25", "26-29", "30-39", "40-49", "50-59", "60-69", "+70");
	$graph->legend->Pos(0.05,0.13);
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightgray@0.3');
	$p1->SetLegends($entrada);
	
	$p1->SetValueType(PIE_VALUE_ABS);
	
	$p1->value->SetFormat("%d");
	$p1->value->Show();
	
	$p1->value->SetFont(FF_ARIAL, FS_NORMAL);
	$p1->value->SetAngle(0);
	
	$p1->value->SetColor("black","darkred");
	$graph->Add($p1);

	}
	$graph->Stroke();
	
	
	mysql_close ($db);
?>

