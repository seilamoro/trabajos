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
	include ("libreria_jpg/jpgraph_line.php");

	$graph = new Graph(1050,690);	
	$graph->SetScale("textint","auto","auto");

	//--CREAR LINEAS
	$colores = array("FF0000","00FF00","0000FF","9933CC","336666","000000");
	$lineplot = array();
	$lineas = $_SESSION['lineas_grafico'];
	$horas = $_SESSION['horas_grafico'];

	for($i = 0; $i < count($lineas); $i++){
		$lineplot[$i] = new LinePlot($lineas[$i]);
		$lineplot[$i]->SetColor("#".$colores[$i]);
		$_SESSION['leyenda_colores_grafico'][$i] = $colores[$i];
		$lineplot[$i]->SetWeight(1);
		$graph->Add($lineplot[$i]);
	}
	//--FIN CREAR LINEAS

	$graph->img->SetMargin(50,190,35,50);	//margenes de la gráfica con el borde de la imagen
	$graph->SetFrame(true,'#000000',2);		//define un borde alrededor de la imagen, su color y su ancho
	
	$graph->title->Set("Energía Solar: Temperatura Sondas");	//titulo de la gráfica
	$graph->title->SetFont(FF_COURIER,FS_BOLD,12);				//fuente del titulo de la gráfica
	$graph->title->SetMargin(10);								//margen del titulo de la gráfica con el borde superior de la imagen
	$graph->xaxis->SetTitlemargin(10);							//margen del titulo de las X con el borde inferior de la imagen
	$graph->xaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);			//fuente del titulo de las X
	$graph->yaxis->SetTitlemargin(30);							//margen del titulo de las Y con el borde derecho de la imagen
	$graph->yaxis->title->Set("Temperaturas");					//titulo de las Y
	$graph->yaxis->title->SetFont(FF_COURIER,FS_NORMAL,12);			//fuente del titulo de las Y
	
	if($rango == 1){
		$graph->xaxis->SetTextLabelInterval(2);
		$graph->xaxis->title->Set("Horas");							//titulo de las X
	}else if($rango == 2){
		$graph->xaxis->SetTextLabelInterval(3);
		$graph->xaxis->title->Set("Días/Horas");					//titulo de las X
	}else if($rango == 3){
		$graph->xaxis->SetTextLabelInterval(3);
		$graph->xaxis->title->Set("Días");							//titulo de las X
	}else if($rango == 4){
		$graph->xaxis->SetTextTickInterval(2);
		$graph->xaxis->title->Set("Días");							//titulo de las X
	}else if($rango == 5 || $rango == 6 || $rango == 7 || $rango == 8){
		$horas1 = array();
		$posiciones = array();
		$cont = 1;

		$posiciones[0] = 0;
		for($h = 1; $h < count($horas); $h++){
			if($rango == 5 && (substr($horas[$h],0,2) == "01" || substr($horas[$h],0,2) == "10" || substr($horas[$h],0,2) == "20" || substr($horas[$h],1,1) == "5")){
				$horas[$cont] = $horas[$h];
				$posiciones[$cont] = $h;
				$cont++;
			}else if($rango == 6 && (substr($horas[$h],0,2) == "01" || substr($horas[$h],0,2) == "10" || substr($horas[$h],0,2) == "20")){
				$horas[$cont] = $horas[$h];
				$posiciones[$cont] = $h;
				$cont++;
			}else if($rango == 7 && (substr($horas[$h],0,2) == "01" || substr($horas[$h],0,2) == "15")){
				$horas[$cont] = $horas[$h];
				$posiciones[$cont] = $h;
				$cont++;
			}else if($rango == 8 && substr($horas[$h],0,2) == "01"){
				$horas[$cont] = $horas[$h];
				$posiciones[$cont] = $h;
				$cont++;
			}
		}

		if($horas[count($posiciones)-1] != $horas[count($horas)-1]){
			$posiciones[count($posiciones)] = count($horas)-1;
			$horas[count($posiciones)-1] = $horas[count($horas)-1];
		}
		$graph->xaxis->SetTickPositions($posiciones);

		$dia_1 = mktime(0, 0, 0, substr($horas[0],3,2), substr($horas[0],0,2), substr($horas[0],6,4));
		$dia_2 = mktime(0, 0, 0, substr($horas[1],3,2), substr($horas[1],0,2), substr($horas[1],6,4));
		$resta = ($dia_2 - $dia_1)/86400;
		if($rango == 5 && $resta < 2){
			$horas[1] = "";
		}else if($rango == 6 && $resta < 6){
			$horas[1] = "";
		}else if($rango == 7 && $resta < 12){
			$horas[1] = "";
		}else if($rango == 8 && $resta < 22){
			$horas[1] = "";
		}

		$dia_1 = mktime(0, 0, 0, substr($horas[count($posiciones)-2],3,2), substr($horas[count($posiciones)-2],0,2), substr($horas[count($posiciones)-2],6,4));
		$dia_2 = mktime(0, 0, 0, substr($horas[count($posiciones)-1],3,2), substr($horas[count($posiciones)-1],0,2), substr($horas[count($posiciones)-1],6,4));
		$resta = ($dia_2 - $dia_1)/86400;
		if($rango == 5 && $resta < 2){
			$horas[count($posiciones)-2] = "";
		}else if($rango == 6 && $resta < 6){
			$horas[count($posiciones)-2] = "";
		}else if($rango == 7 && $resta < 12){
			$horas[count($posiciones)-2] = "";
		}else if($rango == 8 && $resta < 22){
			$horas[count($posiciones)-2] = "";
		}
		$graph->xaxis->title->Set("Días");							//titulo de las X
	}
	$horas_tem = array();
	for($h = 0; $h < count($horas); $h++){
		if(substr($horas[$h],2,1) == "/"){
			$horas_tem[$h] = substr($horas[$h],0,6).substr($horas[$h],8,2);
		}else{
			$horas_tem[$h] = $horas[$h];
		}
	}

	$graph->xaxis->SetTickLabels($horas);
	$graph->xaxis->SetColor("blue");
	$graph->yaxis->SetColor("red");
	$graph->yaxis->SetWeight(1);
	$graph->xgrid->SetColor('#999999');		//color de las lineas horizontales de separación
	$graph->xgrid->SetLineStyle('dotted'); 
	$graph->xgrid->Show();
	$graph->SetShadow();

	if($_SESSION['grafico_fondo'] != "")
		$graph->SetBackgroundImage($_SESSION['grafico_fondo'],BGIMG_FILLPLOT);
	// Display the graph   
	$graph->Stroke("../imagenes/graficos/imagen.png");
}

?>