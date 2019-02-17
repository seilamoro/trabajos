<?php
	session_start();
?>
<head>
	<link type='text/css' rel='stylesheet' href='./css/estadisticas.css'>
<style>body{
	font-family: Arial, Helvetica, sans-serif;
}</style>
</head>
<?php
	// Aqui se hace la conexion con la base de datos
	@ $db =	MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	
	$tipo = $_SESSION['tiempo'];
	$mes_inicio = substr($_SESSION['mes_inicio'],-2);
	$anio_inicio = $_SESSION['anio_inicio'];


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

	$edad_0_9_m = 0;
	$edad_10_19_m = 0;
	$edad_20_25_m = 0;
	$edad_26_29_m = 0;
	$edad_30_39_m = 0;
	$edad_40_49_m = 0;
	$edad_50_59_m = 0;
	$edad_60_69_m = 0;
	$edad_70_m = 0;

	$edad_0_9_f = 0;
	$edad_10_19_f = 0;
	$edad_20_25_f = 0;
	$edad_26_29_f = 0;
	$edad_30_39_f = 0;
	$edad_40_49_f = 0;
	$edad_50_59_f = 0;
	$edad_60_69_f = 0;
	$edad_70_f = 0;
	

	//Obtenemos la fecha
	$fecha = date("d-m-Y");
	$fecha_partida = split("-", $fecha);
	$fecha_dia = $fecha_partida[0];
	$fecha_mes = $fecha_partida[1];
	$fecha_anyo = $fecha_partida[2];
	$fecha_anyo_sig = $anio_inicio + 1;
	$fecha_anyo_ant = $anio_inicio - 1;

	
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es mensual : se selecionan las pernoctas o estancias de grupo que se encuentren dentro de el mes seleccionado
	o que esten pernoctando en el albergue durante el mes seleccionado*/
	
	if($tipo == 2) {
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==2){
			$sql = "SELECT * FROM pernocta WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and Fecha_Salida <> '".$anio_inicio."-".$mes_inicio."-01';"; 
			$sql_gr = "SELECT * FROM estancia_gr WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and Fecha_Salida <> '".$anio_inicio."-".$mes_inicio."-01';"; 
			$sql_gr2 ="SELECT * FROM estancia_gr INNER JOIN componentes_grupo ON estancia_gr.Nombre_Gr = componentes_grupo.Nombre_Gr and estancia_gr.Fecha_Llegada = componentes_grupo.Fecha_Llegada WHERE substring(estancia_gr.Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and substring(estancia_gr.Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and Fecha_Salida <> '".$anio_inicio."-".$mes_inicio."-01';";
		}
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==3){
			$sql_p = "SELECT * FROM pernocta_p WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and Fecha_Salida <> '".$anio_inicio."-".$mes_inicio."-01';"; 
		}
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	//Estadística del año en curso
	
	//Si la estadística es anual: se seleccionan las pernoctas y estancias de grupo que se encuentren dentro del año que se ha seleccionado
	
	if($tipo == 5) {
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==2){
			$sql="Select * from pernocta where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
			$sql_gr="Select * from estancia_gr where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
			$sql_gr2 ="SELECT * FROM estancia_gr INNER JOIN componentes_grupo ON estancia_gr.Nombre_Gr = componentes_grupo.Nombre_Gr and estancia_gr.Fecha_Llegada = componentes_grupo.Fecha_Llegada where SUBSTRING( estancia_gr.Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
		}
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==3){
			$sql_p="Select * from pernocta_p where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
		}
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
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==2){
			$sql = "SELECT * FROM pernocta WHERE (SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."') and Fecha_Salida <> '".$fecha_inicio."-01'";
			$sql_gr = "SELECT * FROM estancia_gr WHERE (SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."') and Fecha_Salida <> '".$fecha_inicio."-01'";
			$sql_gr2 ="SELECT * FROM estancia_gr INNER JOIN componentes_grupo ON estancia_gr.Nombre_Gr = componentes_grupo.Nombre_Gr and estancia_gr.Fecha_Llegada = componentes_grupo.Fecha_Llegada WHERE (SUBSTRING(estancia_gr.Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(estancia_gr.Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(estancia_gr.Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(estancia_gr.Fecha_Salida, 1, 7) <= '".$fecha_fin."') and estancia_gr.Fecha_Salida <> '".$fecha_inicio."-01'";
		}
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==3){
			$sql_p = "SELECT * FROM pernocta_p WHERE (SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."') and Fecha_Salida <> '".$fecha_inicio."-01'"; 
		}
	}

	//--------------------------------------------------------------------------------------------------------------
	/*Diaria : se selecionan las pernoctas o estancias de grupo que se encuentren dentro del dia seleccionado
	o que esten pernoctando en el albergue durante ese dia*/
	
	if($tipo == 1) {
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==2){
			$sql = "Select * from pernocta where (pernocta.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) and pernocta.Fecha_Salida <> '".date("Y")."-".date("m")."-01';";
			$sql_gr = "Select * from estancia_gr where (estancia_gr.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (estancia_gr.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and estancia_gr.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) and pernocta.Fecha_Salida <> '".date("Y")."-".date("m")."-01';";
			$sql_gr2 ="SELECT * FROM estancia_gr INNER JOIN componentes_grupo ON estancia_gr.Nombre_Gr = componentes_grupo.Nombre_Gr and estancia_gr.Fecha_Llegada = componentes_grupo.Fecha_Llegada where (estancia_gr.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (estancia_gr.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and estancia_gr.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) and pernocta.Fecha_Salida <> '".date("Y")."-".date("m")."-01';";
		}
		if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==3){
			$sql_p = "Select * from pernocta_p where (pernocta_p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta_p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta_p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) and pernocta.Fecha_Salida <> '".date("Y")."-".date("m")."-01';";
		}
	}

//Creamos el título de la gráfica

$meses_est=array(" ", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$mes_elegido=" ";
$mes_trimestre=" ";
$mes_semestre=" ";

for ($i=1; $i<=12; $i++) 
{
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
	if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==2){
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
				if($fila2['Sexo_Cl'] == "M"){
					$edad_0_9_m = $edad_0_9_m + 1;
				}else{
					$edad_0_9_f = $edad_0_9_f + 1;
				}
			}
	
			if( ($edad > 9) && ($edad < 20) ) {
				$edad_10_19 = $edad_10_19 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_10_19_m = $edad_10_19_m + 1;
				}else{
					$edad_10_19_f = $edad_10_19_f + 1;
				}
			}
		
			if( ($edad > 19) && ($edad < 26) ) {
				$edad_20_25 = $edad_20_25 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_20_25_m = $edad_20_25_m + 1;
				}else{
					$edad_20_25_f = $edad_20_25_f + 1;
				}
			}
		
			if( ($edad > 25) && ($edad < 30) ) {
				$edad_26_29 = $edad_26_29 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_26_29_m = $edad_26_29_m + 1;
				}else{
					$edad_26_29_f = $edad_26_29_f + 1;
				}
			}

			if( ($edad > 29) && ($edad < 40) ) {
				$edad_30_39 = $edad_30_39 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_30_39_m = $edad_30_39_m + 1;
				}else{
					$edad_30_39_f = $edad_30_39_f + 1;
				}
			}

			if( ($edad > 39) && ($edad < 50) ) {	
				$edad_40_49 = $edad_40_49 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_40_49_m = $edad_40_49_m + 1;
				}else{
					$edad_40_49_f = $edad_40_49_f + 1;
				}
			}

			if( ($edad > 49) && ($edad < 60) ) {
				$edad_50_59 = $edad_50_59 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_50_59_m = $edad_50_59_m + 1;
				}else{
					$edad_50_59_f = $edad_50_59_f + 1;
				}
			}

			if( ($edad > 59) && ($edad < 70) ) {
				$edad_60_69 = $edad_60_69 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_60_69_m = $edad_60_69_m + 1;
				}else{
					$edad_60_69_f = $edad_60_69_f + 1;
				}
			}
	
			if($edad > 69) {
				$edad_70 = $edad_70 +1;
				if($fila2['Sexo_Cl'] == "M"){
					$edad_70_m = $edad_70_m + 1;
				}else{
					$edad_70_f = $edad_70_f + 1;
				}
			}
		}
	}



	/*Consulta de peregrinos : se hace una seleccion de los peregrinos que hayan pernoctado en el albergue
	durante el periodo de tiempo que el usuario seleccione en la gráfica*/
	if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==3){
		$result_p = mysql_query($sql_p);
	
		for ($i=0;$i<@mysql_num_rows($result_p);$i++){
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
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_0_9_m = $edad_0_9_m + 1;
				}else{
					$edad_0_9_f = $edad_0_9_f + 1;
				}
			}
	
			if( ($edad > 9) && ($edad < 20) ) {
				$edad_10_19 = $edad_10_19 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_10_19_m = $edad_10_19_m + 1;
				}else{
					$edad_10_19_f = $edad_10_19_f + 1;
				}
			}
		
			if( ($edad > 19) && ($edad < 26) ) {
				$edad_20_25 = $edad_20_25 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_20_25_m = $edad_20_25_m + 1;
				}else{
					$edad_20_25_f = $edad_20_25_f + 1;
				}
			}
		
			if( ($edad > 25) && ($edad < 30) ) {
				$edad_26_29 = $edad_26_29 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_26_29_m = $edad_26_29_m + 1;
				}else{
					$edad_26_29_f = $edad_26_29_f + 1;
				}
			}

			if( ($edad > 29) && ($edad < 40) ) {
				$edad_30_39 = $edad_30_39 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_30_39_m = $edad_30_39_m + 1;
				}else{
					$edad_30_39_f = $edad_30_39_f + 1;
				}
			}

			if( ($edad > 39) && ($edad < 50) ) {	
				$edad_40_49 = $edad_40_49 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_40_49_m = $edad_40_49_m + 1;
				}else{
					$edad_40_49_f = $edad_40_49_f + 1;
				}
			}

			if( ($edad > 49) && ($edad < 60) ) {
				$edad_50_59 = $edad_50_59 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_50_59_m = $edad_50_59_m + 1;
				}else{
					$edad_50_59_f = $edad_50_59_f + 1;
				}
			}

			if( ($edad > 59) && ($edad < 70) ) {
				$edad_60_69 = $edad_60_69 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_60_69_m = $edad_60_69_m + 1;
				}else{
					$edad_60_69_f = $edad_60_69_f + 1;
				}
			}
	
			if($edad > 69) {
				$edad_70 = $edad_70 +1;
				if($fila2_p['Sexo_Cl'] == "M"){
					$edad_70_m = $edad_70_m + 1;
				}else{
					$edad_70_f = $edad_70_f + 1;
				}
			}
		}
	}



	/*Consulta de grupos : se realiza una seleccion de las estancias de los grupos teniendo en cuenta que la fecha de llegada se encuentre
	dentro del rango temporal que se seleccionara anteriormente*/
	if($_SESSION['tipo_cliente']==1 || $_SESSION['tipo_cliente']==2){
		$result_gr = mysql_query($sql_gr);

		for ($i=0;$i<@mysql_num_rows($result_gr);$i++) {
			$fila_gr = mysql_fetch_array($result_gr);
			$nombre_gr = $fila_gr['Nombre_Gr'];
			$fecha_gr = $fila_gr['Fecha_Llegada'];
			$sql2_gr = "Select * from estancia_gr where Nombre_Gr = '".$nombre_gr."' and Fecha_Llegada = '".$fecha_gr."'";
			$result2_gr = mysql_query($sql2_gr);
			for ($j=0; $j<mysql_num_rows($result2_gr); $j++) {
				$fila2_gr = mysql_fetch_array($result2_gr);
			}

			//Variables donde se almacena el resultado de los grupos

			

			$edad_0_9_m = $edad_0_9_m + $fila2_gr['HGr0_9'];
			$edad_10_19_m = $edad_10_19_m + $fila2_gr['HGr10_19'];
			$edad_20_25_m = $edad_20_25_m + $fila2_gr['HGr20_25'];
			$edad_26_29_m = $edad_26_29_m + $fila2_gr['HGr26_29'];
			$edad_30_39_m = $edad_30_39_m + $fila2_gr['HGr30_39'];
			$edad_40_49_m = $edad_40_49_m + $fila2_gr['HGr40_49'];
			$edad_50_59_m = $edad_50_59_m + $fila2_gr['HGr50_59'];
			$edad_60_69_m = $edad_60_69_m + $fila2_gr['HGr60_69'];
			$edad_70_m = $edad_70_m + $fila2_gr['HGrOtras'];

			$edad_0_9_f = $edad_0_9_f + $fila2_gr['FGr0_9'];
			$edad_10_19_f = $edad_10_19_f + $fila2_gr['FGr10_19'];
			$edad_20_25_f = $edad_20_25_f + $fila2_gr['FGr20_25'];
			$edad_26_29_f = $edad_26_29_f + $fila2_gr['FGr26_29'];
			$edad_30_39_f = $edad_30_39_f + $fila2_gr['FGr30_39'];
			$edad_40_49_f = $edad_40_49_f + $fila2_gr['FGr40_49'];
			$edad_50_59_f = $edad_50_59_f + $fila2_gr['FGr50_59'];
			$edad_60_69_f = $edad_60_69_f + $fila2_gr['FGr60_69'];
			$edad_70_f = $edad_70_f + $fila2_gr['FGrOtras'];
	
			/*$num_0_9 = $fila2_gr['Gr0_9'];
			$num_10_19 = $fila2_gr['Gr10_19'];
			$num_20_25 = $fila2_gr['Gr20_25'];
			$num_26_29 = $fila2_gr['Gr26_29'];
			$num_30_39 = $fila2_gr['Gr30_39'];
			$num_40_49 = $fila2_gr['Gr40_49'];
			$num_50_59 = $fila2_gr['Gr50_59'];
			$num_60_69 = $fila2_gr['Gr60_69'];
			$num_70 = $fila2_gr['GrOtras'];*/
			$num_m = $fila2_gr['Num_Hombres'];
			$num_f = $fila2_gr['Num_Mujeres'];

	
			//resultados totales de la gente que hay en cada rango de edad
	
			$edad_0_9 =+ $edad_0_9_f + $edad_0_9_m;
			$edad_10_19 =+ $edad_10_19_f + $edad_10_19_m;
			$edad_20_25 =+ $edad_20_25_f + $edad_20_25_m;
			$edad_26_29 =+ $edad_26_29_f + $edad_26_29_m;
			$edad_30_39 =+ $edad_30_39_f + $edad_30_39_m;
			$edad_40_49 =+ $edad_40_49_f + $edad_40_49_m;
			$edad_50_59 =+ $edad_50_59_f + $edad_50_59_m;
			$edad_60_69 =+ $edad_60_69_f + $edad_60_69_m;
			$edad_70 =+ $edad_70_f + $edad_70_m;
			$edad_num_m = $edad_num_m + $num_m;
			$edad_num_f = $edad_num_f + $num_f;
		}
	}

	
			//////////////////////////////////////////////////////////////////////////
			
			
			//////////////////////////////////////////////////////////////////////////
		
		
	$total_edad=$edad_0_9 + $edad_10_19 + $edad_20_25 + $edad_26_29 + $edad_30_39 + $edad_40_49 + $edad_50_59 + $edad_60_69 + $edad_70;
	$total_edad_m=$edad_0_9_m + $edad_10_19_m + $edad_20_25_m + $edad_26_29_m + $edad_30_39_m + $edad_40_49_m + $edad_50_59_m + $edad_60_69_m + $edad_70_m;
	$total_edad_f=$edad_0_9_f + $edad_10_19_f + $edad_20_25_f + $edad_26_29_f + $edad_30_39_f + $edad_40_49_f + $edad_50_59_f + $edad_60_69_f + $edad_70_f;
	
	$total_m = $edad_num_m + $total_edad_m;
	$total_f = $edad_num_f + $total_edad_f;

	

?> 

<table width="450px"  style="font-size:14px;" border="1" bordercolor="#000000" >
	<tr> 
		<td>
			<table width="410px" align="center" border='10' bordercolor='white' cellspacing='0' cellpadding='0'>
				
			
				<tr align="center"> 
					<td colspan="5"><strong><font size="4">INFORME POR EDADES
					<?php 
						if($_SESSION['tipo_cliente']==2){
							echo "(Alberguistas)";
						}
						if($_SESSION['tipo_cliente']==3){
							echo "(Peregrinos)";
						}
					?>
					<? echo "<br>".$titulo; ?>
					</font></strong></td>
				</tr>
				
					
				<tr> 
					<td width='40%'>&nbsp;</td>
					<td width="15%" align="center"><strong>H</strong></td>
					<td width="15%" align="center"><strong>M</strong></td>
					<td width="15%" align="center"><strong>%</strong></td>
					<td width="15%" align="center"><strong>Total</strong></td>
				</tr>
				<?php
					if($edad_0_9!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>0-9</strong></td>
					<td align='center' class='rb'><?php echo $edad_0_9_m?></td>
					<td align='center' class='rb'><?php echo $edad_0_9_f?></td>
					<td align='center' class='rb'><?php echo round(($edad_0_9/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_0_9;?></td>
				</tr>
				<?php
					}
					if($edad_10_19!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>10-19</strong></td>
					<td align='center' class='rb'><?php echo $edad_10_19_m;?></td>
					<td align='center' class='rb'><?php echo $edad_10_19_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_10_19/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_10_19;?></td>
				</tr>
				<?php
					}
					if($edad_20_25!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>20-25</strong></td>
					<td align='center' class='rb'><?php echo $edad_20_25_m;?></td>
					<td align='center' class='rb'><?php echo $edad_20_25_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_20_25/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_20_25;?></td>
				</tr>
				<?php
					}
					if($edad_26_29!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>26-29</strong></td>
					<td align='center' class='rb'><?php echo $edad_26_29_m;?></td>
					<td align='center' class='rb'><?php echo $edad_26_29_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_26_29/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_26_29;?></td>
				</tr>
				<?php
					}
					if($edad_30_39!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>30-39</strong></td>
					<td align='center' class='rb'><?php echo $edad_30_39_m;?></td>
					<td align='center' class='rb'><?php echo $edad_30_39_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_30_39/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_30_39;?></td>
				</tr>
				<?php
					}
					if($edad_40_49!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>40-49</strong></td>
					<td align='center' class='rb'><?php echo $edad_40_49_m;?></td>
					<td align='center' class='rb'><?php echo $edad_40_49_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_40_49/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_40_49;?></td>
				</tr>
				<?php
					}
					if($edad_50_59!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>50-59</strong></td>
					<td align='center' class='rb'><?php echo $edad_50_59_m;?></td>
					<td align='center' class='rb'><?php echo $edad_50_59_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_50_59/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_50_59;?></td>
				</tr>
				<?php
					}
					if($edad_60_69!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>60-69</strong></td>
					<td align='center' class='rb'><?php echo $edad_60_69_m;?></td>
					<td align='center' class='rb'><?php echo $edad_60_69_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_60_69/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_60_69;?></td>
				</tr>
				<?php
					}
					if($edad_70!=0){
				?>
				<tr>
					<td align='center' width='25%' class='rb'><strong>70</strong></td>
					<td align='center' class='rb'><?php echo $edad_70_m;?></td>
					<td align='center' class='rb'><?php echo $edad_70_f;?></td>
					<td align='center' class='rb'><?php echo round(($edad_70/$total_edad*100)*10)/10; ?></td>
					<td align='center' class='rb'><?php echo $edad_70;?></td>
				</tr>

				<?php }
						
					?>
			
				<tr>
					<td width="40%" align="center" class='rt'><strong>TOTALES</strong></td>
					<td width="15%" align="center" class='rt'><strong><?php echo $total_edad_m; ?></strong></td>
					<td width="15%" align="center" class='rt'><strong><?php echo $total_edad_f; ?></strong></td>
					<td width="15%" align="center" class='rt'><strong><?php 
						if($total_edad==0){
							echo "0%";
						}else{
							echo "100%";
						}?></strong></td>
					<td width="15%" align="center" class='rt'><strong><?php echo $total_edad; ?></strong></td>
				</tr>
			</table>
		<br>
</table>
<br>
<table>
	<tr>
		<td align="left">
			<strong style="font-size:12px;">H*: Hombres pertenecientes a grupo no dados de alta.</strong>
		</td>
	</tr>
	<tr>
		<td align="left">
			<strong style="font-size:12px;">M*: Mujeres pertenecientes a grupo no dadas de alta.</strong>
		</td>
	</tr>
</table>
<br>


<?php mysql_close($db); 
//echo $_SESSION['tipo_cliente']."<br>";
//echo $_SESSION['mes_inicio']."<br>";
//echo $_SESSION['anio_inicio']."<br>";
//echo $_SESSION['periocidad']."<br>";
?>
</body>