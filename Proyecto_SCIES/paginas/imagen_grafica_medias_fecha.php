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
//
	$graph->Stroke("../imagenes/graficos/imagen.png");


/***************************** FIN NO HAY DATOS *********************************/


}else{

	include ("libreria_jpg/jpgraph.php");
	include ("libreria_jpg/jpgraph_line.php");
	include ("libreria_jpg/jpgraph_error.php");

	$graph = new Graph(1050,690);	
	$graph->SetScale("textint","auto","auto");

	$colores = array("FF0000","00FF00","0000FF","9933CC","336666","000000");
	$errplot = array();
	$lineas = $_SESSION['lineas_grafico'];
	$leyenda = $_SESSION['leyenda_grafico'];
	$horas = $_SESSION['horas_grafico'];
	$rango = $_SESSION['rango_grafico'];

	$cont = 0;
	$c = count($lineas)+1;
	$_SESSION['leyenda_colores_grafico'] = array();
	for($i = 0; $i < count($lineas); $i++){
		if(count($lineas[$i]) != 0){
			$errplot[$i] = new ErrorLinePlot($lineas[$i]);
			$errplot[$i]->SetColor("#".$colores[$cont]);
			$_SESSION['leyenda_colores_grafico'][$i][0] = $colores[$cont];
			$cont++;
			$errplot[$i]->SetCenter();
			$errplot[$i]->SetWeight($c);
			$c--;
			$errplot[$i]->line->SetColor("#".$colores[$cont]);
			$_SESSION['leyenda_colores_grafico'][$i][1] = $colores[$cont];
			$cont++;
			$graph->Add($errplot[$i]);
		}
	}

	$graph->img->SetMargin(50,190,35,50);	//margenes de la gráfica con el borde de la imagen
	$graph->SetFrame(true,'#000000',2);		//define un borde alrededor de la imagen, su color y su ancho
	
	if($rango == 1){
		$graph->xaxis->title->Set("Horas");							//titulo de las X
	}else if($rango == 2){
		$graph->xaxis->title->Set("Días/Horas");					//titulo de las X
	}else{
		$graph->xaxis->title->Set("Días");							//titulo de las X
	}

	$graph->title->Set("Energía Solar: Temperatura Sondas");	//titulo de la gráfica
	$graph->title->SetFont(FF_COURIER,FS_BOLD,12);				//fuente del titulo de la gráfica
	$graph->title->SetMargin(10);								//margen del titulo de la gráfica con el borde superior de la imagen
	$graph->xaxis->SetTitlemargin(10);							//margen del titulo de las X con el borde inferior de la imagen
	$graph->xaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);		//fuente del titulo de las X
	$graph->yaxis->SetTitlemargin(30);							//margen del titulo de las Y con el borde derecho de la imagen
	$graph->yaxis->title->Set("Temperaturas");					//titulo de las Y
	$graph->yaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);			//fuente del titulo de las Y

	$graph->xaxis->SetTickLabels($horas);
	
	$graph->xaxis->SetColor("blue");
	$graph->yaxis->SetColor("red");
	$graph->yaxis->SetWeight(1);
	$graph->xgrid->SetColor('#999999');		//color de las lineas horizontales de separación
	$graph->xgrid->SetLineStyle('dotted');	//si no se quieren las lineas horizontales poner 'none'
	$graph->xgrid->Show();
	$graph->SetShadow();

	if($_SESSION['grafico_fondo'] != "")
		$graph->SetBackgroundImage($_SESSION['grafico_fondo'],BGIMG_FILLPLOT);
	// Display the graph   
	$graph->Stroke("../imagenes/graficos/imagen.png");

}
?>