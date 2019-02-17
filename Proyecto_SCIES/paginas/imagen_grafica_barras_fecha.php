<?php
session_start();
$_SESSION['leyenda_colores_grafico'] = array();

//si no hay datos para generar la grafica me sale un mensaje
if($_SESSION['valores_grafico'] == 0){
	include ("libreria_jpg/jpgraph.php");
	include ("libreria_jpg/jpgraph_canvas.php");
/***************************** INICIO NO HAY DATOS *********************************/
	$graph = new CanvasGraph(380,30);
	
	$t11 = new Text("No existen datos para generar la gráfica");
	$t11->Pos(0.015,0.18);
	$t11->SetOrientation("h");
	$t11->SetFont(FF_FONT2,FS_BOLD);
	
	$t11->SetBox("white","black","black");
	$t11->SetColor("red");
	$graph->AddText($t11);

	$graph->Stroke("../imagenes/graficos/imagen.png");
/***************************** FIN NO HAY DATOS *********************************/
}else{
	$rango = $_SESSION['rango_grafico'];

	include ("libreria_jpg/jpgraph.php");
	include ("libreria_jpg/jpgraph_bar.php");

	$graph = new Graph(1050,690);	
	$graph->SetScale("textint","auto","auto");

	$colores = array("FF0000","00FF00","0000FF","9933CC","336666","000000");
	$plot = array();
	$lineas = $_SESSION['lineas_grafico'];
	$horas = $_SESSION['horas_grafico'];
	$total=array();

	for($i = 0; $i < count($lineas); $i++){
		$plot[$i] = new BarPlot($lineas[$i]);
		$plot[$i]->SetFillGradient("#000000@0.15","#".$colores[$i].'@0.15',GRAD_LEFT_REFLECTION);
		$_SESSION['leyenda_colores_grafico'][$i] = $colores[$i];
	}

	for($i = 0; $i < count($plot); $i++){
		$total[$i]=$plot[$i];
	}
	$gbplot = new GroupBarPlot($total);
	$gbplot->SetWidth(0.8);
	$graph->Add($gbplot);
	
	if($rango == 1){
		$graph->xaxis->title->Set("Horas");							//titulo de las X
		$horas_cortadas = array();
		for($d = 0; $d < count($horas); $d++){
			$horas_cortadas[$d] = substr($horas[$d],0,2);
		}
		$horas = $horas_cortadas;
	}else if($rango == 5){
		$horas_cortadas = array();
		for($d = 0; $d < count($horas); $d++){
			$horas_cortadas[$d] = substr($horas[$d],0,5);
		}
		$horas = $horas_cortadas;
		$graph->xaxis->title->Set("Días");							//titulo de las X
	}else if($rango >= 6 && $rango <= 8){
		$meses = array("", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
		$meses_c = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$horas_cortadas = array();
		for($d = 0; $d < count($horas); $d++){
			if($rango != 8)
				$horas_cortadas[$d] = $meses_c[substr($horas[$d],3,2)/1];
			else
				$horas_cortadas[$d] = $meses[substr($horas[$d],3,2)/1];
		}
		$horas = $horas_cortadas;
		$graph->xaxis->title->Set("Meses");							//titulo de las X
	}else{
		$graph->xaxis->title->Set("Días");							//titulo de las X
	}

	$graph->xaxis->SetTickLabels($horas);
	$graph->img->SetMargin(50,190,35,50);	//margenes de la gráfica con el borde de la imagen
	$graph->SetFrame(true,'#000000',2);		//define un borde alrededor de la imagen, su color y su ancho
	$graph->title->Set("Energía Solar: Temperatura Sondas");	//titulo de la gráfica
	$graph->title->SetFont(FF_COURIER,FS_BOLD,12);				//fuente del titulo de la gráfica
	$graph->title->SetMargin(10);								//margen del titulo de la gráfica con el borde superior de la imagen
	$graph->xaxis->SetTitlemargin(10);							//margen del titulo de las X con el borde inferior de la imagen
	$graph->xaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);		//fuente del titulo de las X
	$graph->yaxis->SetTitlemargin(30);							//margen del titulo de las Y con el borde derecho de la imagen
	$graph->yaxis->title->Set("Temperaturas");					//titulo de las Y
	$graph->yaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);			//fuente del titulo de las Y
	
	if($rango == 5){
		$graph->xaxis->SetLabelAngle(60);
		$graph->xaxis->SetFont(FF_COURIER,FS_BOLD,8);
		$graph->img->SetMargin(50,190,35,65);
		$graph->xaxis->SetTitlemargin(35);
	}

	$graph->xaxis->SetColor("blue");
	$graph->yaxis->SetColor("red");
	$graph->yaxis->SetWeight(1);
	$graph->xgrid->SetColor('#999999');		//color de las lineas horizontales de separación
	$graph->xgrid->SetLineStyle('none'); 
	$graph->xgrid->Show();
	$graph->SetShadow();

	if($_SESSION['grafico_fondo'] != "")
		$graph->SetBackgroundImage($_SESSION['grafico_fondo'],BGIMG_FILLPLOT);
	// Display the graph   
	$graph->Stroke("../imagenes/graficos/imagen.png");
}

?>