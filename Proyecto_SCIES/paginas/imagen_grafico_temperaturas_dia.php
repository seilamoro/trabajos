<?php
session_start();
$_SESSION['leyenda_colores_grafico'] = array();
//si no hay datos para generar la grafica me sale un mensaje
if($_SESSION['valores_grafico'] <= 1){


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



include ("libreria_jpg/jpgraph.php");
include ("libreria_jpg/jpgraph_line.php");

//header("Content-type: image/png"); 
// Create the graph. These two calls are always required
$graph = new Graph(1050,690);	
$graph->SetScale("textlin","auto","auto");
//$graph->SetScale('datlin',0,15);
// Create the linear plot

//--CREAR LINEAS

$colores = array("FF0000","00FF00","0000FF","9933CC","336666","000000");
$lineplot = array();
$lineas = $_SESSION['lineas_grafico'];
//$leyenda = $_SESSION['leyenda_grafico'];
$horas = $_SESSION['horas_grafico'];
$dif = $_SESSION['diferencia_grafico'];
$_SESSION['diferencia_grafico'] = array();
$_SESSION['lineas_grafico'] = array();
//$_SESSION['leyenda_grafico'] = array();
$_SESSION['horas_grafico'] = array();

for($i = 0; $i < count($lineas); $i++){
	$lineplot[$i] = new LinePlot($lineas[$i]);
	$graph -> Add($lineplot[$i]);
	$_SESSION['leyenda_colores_grafico'][$i] = $colores[$i];
	$lineplot[$i] -> SetColor("#".$colores[$i]);
	$lineplot[$i] -> SetWeight(1);
	/*$lineplot[$i] -> SetLegend("Inst. 0".$leyenda[$i][0].", parámetro 0".$leyenda[$i][1]); */
	
}
//--FIN CREAR LINEAS

$graph->img->SetMargin(50,190,35,50);
$graph->SetFrame(true,'#000000',2);
 
//$graph->img->SetTransparent("white"); 



$graph->xaxis->title->Set("Horas");

$graph->title->Set("Energía Solar: Temperatura Sondas");	//titulo de la gráfica
	$graph->title->SetFont(FF_COURIER,FS_BOLD,12);				//fuente del titulo de la gráfica
	$graph->title->SetMargin(10);								//margen del titulo de la gráfica con el borde superior de la imagen
	$graph->xaxis->SetTitlemargin(10);							//margen del titulo de las X con el borde inferior de la imagen
	$graph->xaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);			//fuente del titulo de las X
	$graph->yaxis->SetTitlemargin(35);							//margen del titulo de las Y con el borde derecho de la imagen
	$graph->yaxis->title->Set("Temperaturas");					//titulo de las Y
	$graph->yaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);			//fuente del titulo de las Y

if($dif <= 1){
	$graph->xaxis->SetTextTickInterval(15);
}
else if($dif <= 3){
	$graph->xaxis->SetTextTickInterval(30);
}
else if($dif <= 8){
	$graph->xaxis->SetTextTickInterval(60);
}
else{
	$graph->xaxis->SetTextTickInterval(120);
}

$graph->xaxis->SetTickLabels($horas);
//$graph->legend->Pos(0.01,0.037);
//$graph->xaxis->SetPos('min'); 
$graph->xaxis->SetColor("blue");
	$graph->yaxis->SetColor("red");
	$graph->yaxis->SetWeight(1);
	$graph->xgrid->SetColor('#999999');		//color de las lineas horizontales de separación
	$graph->xgrid->SetLineStyle('dotted'); 
	if($_SESSION['fondo']==1)
		$graph->SetBackgroundImage($_SESSION['grafico_fondo'],BGIMG_FILLPLOT);
	$graph->xgrid->Show();
	$graph->SetShadow();
// Display the graph
$graph->Stroke("../imagenes/graficos/imagen.png");





}

?>
