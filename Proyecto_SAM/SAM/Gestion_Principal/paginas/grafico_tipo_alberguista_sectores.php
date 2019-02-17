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


	//Tipo estadística: 1(diaria), 2(mensual), 3(trimestral), 4(semestral), 5(anual).
	$tipo = $_SESSION['tiempo'];
	$mes_inicio = $_SESSION['mes_est'];
	$anio_inicio = $_SESSION['anio_est'];


	//Inicializamos contadores.
	$numero_grupos = 0;
	$personas_grupos = 0;
	$alberguistas = 0;

	//Obtenemos la fecha actual.
	$fecha = date("d-m-Y");
	$fecha_partida = split("-", $fecha);
	$fecha_dia = $fecha_partida[0];
	$fecha_mes = $fecha_partida[1];
	$fecha_anyo = $fecha_partida[2];
	$fecha_anyo_sig = $anio_inicio + 1;
	$fecha_anyo_ant = $anio_inicio - 1;



	//Conexión a la base de datos.
	$db=MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);;
	if(!$db) { 
		echo("ERROR DURANTE LA CONEXIÓN A LA BASE DE DATOS");
	} 

	mysql_select_db($_SESSION['conexion']['db']);
	
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es mensual: se seleccionan las pernoctas y estancias de grupo que esten pernoctando en el albergue durante el mes
	seleccionado por el usuario*/
	
	if($tipo == 2) {
		$sql = "SELECT * FROM pernocta WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and ( substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and pernocta.Fecha_Salida != '" .$anio_inicio ."-" .$mes_inicio ."-01');"; 
		$sql_gr = "SELECT * FROM estancia_gr WHERE substring(Fecha_Llegada, 1, 7) <= '".$anio_inicio."-".$mes_inicio."' and ( substring(Fecha_Salida, 1, 7) >= '".$anio_inicio."-".$mes_inicio."' and estancia_gr.Fecha_Salida != '" .$anio_inicio ."-" .$mes_inicio ."-01');";
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	//Estadística del año en curso
	//Si la estadística es anual: se seleccionan las pernoctas y estancias que se hayan producido durante el año que el usuario haya seleccionado
	
	if($tipo == 5) {
		$sql="Select * from pernocta where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
		$sql_gr="Select * from estancia_gr where SUBSTRING( Fecha_Llegada, 1, 4  ) =".$anio_inicio.";";
	}
	
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es trimestral o semestral: se seleccionan las pernoctas y estancias de grupo que esten pernoctando en el albergue 
	durante el trimestre o el semestre seleccionado por el usuario*/
	
	if(($tipo == 3) || ($tipo == 4)) {
		
		
		if($tipo == 3) {
			$sem_trim = $mes_inicio + 2;
		} else {
			$sem_trim = $mes_inicio + 5;
		}

		$fecha_inicio = date("Y-m", mktime(0, 0, 0, $mes_inicio, 1, $anio_inicio));
		$fecha_fin = date("Y-m", mktime(0, 0, 0, $sem_trim, 1, $anio_inicio));

		$sql = "SELECT * FROM pernocta WHERE ((SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."')) and (Fecha_Salida != '" .$anio_inicio ."-" .$mes_inicio ."-01')"; 

		$sql_gr = "SELECT * FROM estancia_gr WHERE ((SUBSTRING(Fecha_Salida, 1, 7) > '".$fecha_fin."' AND SUBSTRING(Fecha_Llegada, 1, 7) <= '".$fecha_fin."') || (SUBSTRING(Fecha_Salida, 1, 7) >= '".$fecha_inicio."' AND SUBSTRING(Fecha_Salida, 1, 7) <= '".$fecha_fin."')) and (Fecha_Salida != '" .$anio_inicio ."-" .$mes_inicio ."-01')";
	}

	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es diaria: se seleccionan las pernoctas y estancias de grupo que esten pernoctando en el albergue 
	durante el dia seleccionado por el usuario*/
	
	if($tipo == 1) {
		$sql = "Select * from pernocta where (pernocta.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
		$sql_gr = "Select * from estancia_gr where (estancia_gr.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (estancia_gr.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and estancia_gr.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
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


	/*Consulta de alberguistas se hace una seleccion de los alberguistas, que hayan pernoctado en el albergue durante el periodo
	 de tiempo que el usuario seleccione en la gráfica*/
	 
	$result = mysql_query($sql);
	
	for ($i=0;$i<mysql_num_rows($result);$i++){
		$fila = mysql_fetch_array($result);
		$dni = $fila['DNI_Cl'];
		$sql2 = "SELECT * FROM cliente WHERE DNI_Cl = '".$dni."'";
		$result2 = mysql_query($sql2);
			for ($j=0;$j<mysql_num_rows($result2);$j++){
				$alberguistas = $alberguistas + 1;
			}
	}
	
	
	
	/*Consulta de grupos: se hace una seleccion de los grupos de alberguistas y se coge el numero de personas que componen cada grupo,
	que hayan pernoctado en el albergue	durante el periodo de tiempo que el usuario seleccione en la gráfica*/
	
	$result_gr = mysql_query($sql_gr);

	for ($i=0;$i<mysql_num_rows($result_gr);$i++) {
		$numero_grupos = $numero_grupos + 1;
		$fila_gr = mysql_fetch_array($result_gr);
		$nombre_gr = $fila_gr['Nombre_Gr'];
		$fecha_gr = $fila_gr['Fecha_Llegada'];
		$sql2_gr = "Select * from estancia_gr where Nombre_Gr = '".$nombre_gr."' and Fecha_Llegada = '".$fecha_gr."'";
		$result2_gr = mysql_query($sql2_gr);
		for ($j=0; $j<mysql_num_rows($result2_gr); $j++) {
			$fila2_gr = mysql_fetch_array($result2_gr);
			$personas_grupos = $personas_grupos + $fila2_gr['Num_Personas'];
		}
	}

	$_SESSION['alberguistas'] = $alberguistas;

	$data = array($alberguistas, $personas_grupos);
	$datay2 = array(0,0);
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
	
	$graph->title->Set("Tipo de Alberguista ( ".$titulo." )");
	$graph->title->SetFont(FF_FONT2,FS_BOLD);
	$graph->title->SetColor("black");
	
	$graph->SetFrame(true,'black','white@0.4','white@0.4',4,2);
	$graph->SetColor("#f4fcff");
	$graph->SetShadow();
	$p1 = new PiePlot3D($data);
	$p1->SetSize(0.45);
	$p1->SetCenter(0.41, 0.5);
	
	$entrada = array("Individual", "Grupos (".$numero_grupos.")");
	$p1->SetLegends($entrada);
	
	$p1->SetValueType(PIE_VALUE_ABS);
	$p1->value->SetFormat("%d");
	$p1->value->SetFont(FF_FONT2, FS_NORMAL);
	$p1->value->SetColor("black");
	$graph->Add($p1);
	}
	$graph->Stroke();

	mysql_close ($db);
?>