<?php
session_start();
$_SESSION['leyenda_colores_grafico'] = array();
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

include ("libreria_jpg/jpgraph.php");
include ("libreria_jpg/jpgraph_line.php");
include ("libreria_jpg/jpgraph_error.php");
$colores = array("FF0000","00FF00","0000FF","9933CC","336666","000000");

$horas = $_SESSION['horas_grafico'];

$horas = $_SESSION['horas_grafico'];
$datos_grafico1=$_SESSION['valores'] ;

$_SESSION['valores'] = array();
$_SESSION['lineas_grafico'] = array();

$_SESSION['horas_grafico'] = array();

// Create the graph. These two calls are always required
$graph = new Graph(1050,690);	
$graph->SetScale("textlin","auto","auto");

$graph->img->SetMargin(50,190,35,50);	//margenes de la gráfica con el borde de la imagen
	$graph->SetFrame(true,'#000000',2);		//define un borde alrededor de la imagen, su color y su ancho
//$graph->SetShadow();

// Create the linear plot
$cont=0;
$errplot=array();
$c=count($datos_grafico1)+1;
for($i = 0; $i < count($datos_grafico1); $i++){
	if($datos_grafico1[$i]==0)
		$datos_grafico1[$i]="x";
	$errplot[$i]=new ErrorLinePlot($datos_grafico1[$i]);
	
	$errplot[$i] -> SetColor("#".$colores[$cont]);
	$_SESSION['leyenda_colores_grafico'][$i][0] = $colores[$cont];
	$errplot[$i] -> SetWeight($c);
	
	$cont=$cont+1;
	$errplot[$i]->SetCenter();
	$errplot[$i]->SetWeight($c);
	$errplot[$i]->line->SetColor("#".$colores[$cont]);
	$_SESSION['leyenda_colores_grafico'][$i][1] = $colores[$cont];
	$cont=$cont+1;
	$c=$c-1;
	//$errplot[$i]->SetLegend("Min/Max");
	//$errplot[$i]->line->SetLegend("Inst. 0".$leyenda[$i][0].", parámetro 0".$leyenda[$i][1]);
	$graph->Add($errplot[$i]);
	
}
$graph->xaxis->title->Set("Horas");

$graph->title->Set("Energía Solar: Temperatura Sondas");	//titulo de la gráfica
	$graph->title->SetFont(FF_COURIER,FS_BOLD,12);				//fuente del titulo de la gráfica
	$graph->title->SetMargin(10);								//margen del titulo de la gráfica con el borde superior de la imagen
	$graph->xaxis->SetTitlemargin(10);							//margen del titulo de las X con el borde inferior de la imagen
	$graph->xaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);		//fuente del titulo de las X
	$graph->yaxis->SetTitlemargin(35);							//margen del titulo de las Y con el borde derecho de la imagen
	$graph->yaxis->title->Set("Temperaturas");					//titulo de las Y
	$graph->yaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);			//fuente del titulo de las Y
$horas_cortadas=array();
for($d=0;$d < count($horas);$d++){
$horas_cortadas[$d]=substr($horas[$d],0,2);
}
$graph->xaxis->SetTickLabels($horas_cortadas);


	$graph->xaxis->SetColor("blue");
	$graph->yaxis->SetColor("red");
	$graph->yaxis->SetWeight(1);
	$graph->xgrid->SetColor('#999999');		//color de las lineas horizontales de separación
	$graph->xgrid->SetLineStyle('dotted');	//si no se quieren las lineas horizontales poner 'none'
	
	if($_SESSION['fondo']==1)
		$graph->SetBackgroundImage($_SESSION['grafico_fondo'],BGIMG_FILLPLOT);
 $graph->xgrid->Show();
	$graph->SetShadow();
// Display the graph
$graph->Stroke("../imagenes/graficos/imagen.png");

}
?> 