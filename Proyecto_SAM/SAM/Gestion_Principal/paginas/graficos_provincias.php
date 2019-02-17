<?PHP
session_start();

	/* Esta página esta incluida dentro de la página de tabla de estadísticas y
	la de tabla de estadísticas incluida a su vez dentro de la pagina de estadisticas 
	y todas las variables de sesion que se usan en esta página se obtienen de la página de valores de estadística
	y otras de las otras páginas que estan incluidas dentro de la página estadisticas.php */

	

// Aqui se hace la conexion con la base de datos
  @ $db =MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	
// Variables que van a contener el periodo de tiempo del cual se desea el gráfico
	$finsql="";
	$finsql_gr="";
	$finsql_al="";
	$finsql_pe="";

/* Las variables de session 'tiempo' marcan el periodo de tiempo que abarca la estadistica 
(Diaria-1), (Mensual-2),(Trimestral-3), (Semestral-4),(Anual-5)*/
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es diaria : Aqui se construye la parte de la sentencia  donde se muestra el dia actual y seleccionará 
	las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese dia, y esta parte luego va a formar parte de
	otra sentencia donde ya se elegira las tablas y campos de consulta  */

	if($_SESSION['periodicidad']=="1")
		{
			$finsql_gr="(estancia_gr.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (estancia_gr.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and estancia_gr.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
			$finsql_al="(pernocta.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
			$finsql_pe="(pernocta_p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta_p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta_p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
		}
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es mensual : Aqui se construye la parte de la sentencia  donde se elige el mes del cual se desea la estadística
	 y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese mes, y esta parte luego va a 
	 formar parte de otra sentencia donde ya se elegira las tablas y campos de consulta  */
	
	if($_SESSION['periodicidad']=="2")
		{ 							
			$finsql_gr="(
					(substring(estancia_gr.Fecha_Llegada,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') or 
					(substring(estancia_gr.Fecha_Salida,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (estancia_gr.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01') or 
					(substring(estancia_gr.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') 
					);";
					
			$finsql_al="(
					(substring(pernocta.Fecha_Llegada,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') or 
					((substring(pernocta.Fecha_Salida,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (pernocta.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01') or 
					(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') )
					);";		
			
			$finsql_pe="(
					(substring(pernocta_p.Fecha_Llegada,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') or 
					(substring(pernocta_p.Fecha_Salida,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (pernocta_p.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01') or 
					(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."')
					);";
		}
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es trimestral : Aqui se construye la parte de la sentencia  donde se elige el trimestre del cual se desea la 
	estadística y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese trimestre, y esta parte 
	luego va a formar parte	de otra sentencia donde ya se elegira las tablas y campos de consulta  */
	
	if($_SESSION['periodicidad']=="3")
		{
		//saco la fecha final sumandole 3 meses mas
		 
			$fech_trim_sem=date("Y-m-d",mktime(0,0,0,$_SESSION['mes_inicio']+2,7,$_SESSION['anio_inicio']));
				$tr_sem=substr($fech_trim_sem,0,7);
								
					$finsql_gr="(
							(substring(estancia_gr.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."')or 
							((substring(estancia_gr.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and (estancia_gr.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01')) or 
							(substring(estancia_gr.Fecha_Llegada,1,7)< '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$tr_sem."')
							);";
					$finsql_al="(
							(substring(pernocta.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or 
							((substring(pernocta.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and (pernocta.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01')) or 
							(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$tr_sem."')
							);";
					$finsql_pe="(
							(substring(pernocta_p.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or 
							((substring(pernocta_p.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and (pernocta_p.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01')) or 
							(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$tr_sem."')
							);";
								
		}
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es semestral : Aqui se construye la parte de la sentencia  donde se elige el semestre del cual se desea la 
	estadística y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese semestre, y esta parte 
	luego va a formar parte	de otra sentencia donde ya se elegira las tablas y campos de consulta  */
	
	if($_SESSION['periodicidad']=="4")
	{
	//saco la fecha final sumandole 6 meses mas 
	
		$fech_trim_sem=date("Y-m-d",mktime(0,0,0,$_SESSION['mes_inicio']+5,7,$_SESSION['anio_inicio']));
			$tr_sem=substr($fech_trim_sem,0,7);
							
				$finsql_gr="(
						(substring(estancia_gr.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."')or 
						((substring(estancia_gr.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and (estancia_gr.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01')) or 
						(substring(estancia_gr.Fecha_Llegada,1,7)< '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$tr_sem."')
						);";
				$finsql_al="(
						(substring(pernocta.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or 
						((substring(pernocta.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and (pernocta.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01')) or 
						(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$tr_sem."')
						);";
				$finsql_pe="(
						(substring(pernocta_p.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or 
						((substring(pernocta_p.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and (pernocta_p.Fecha_Salida != '" .$_SESSION['anio_inicio'] ."-" .$_SESSION['mes_inicio'] ."-01')) or 
						(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$tr_sem."')
						);";
							
	}
	
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es anual: Aqui se construye la parte de la sentencia  donde se elige el año del cual se desea la estadística
	 y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese año, y esta parte luego va a 
	 formar parte de otra sentencia donde ya se elegira las tablas y campos de consulta  */
	
	if($_SESSION['periodicidad']=="5")
		{	
			$finsql_gr="(estancia_gr.Fecha_Llegada  between '".$_SESSION['anio_inicio']."-01-01' and '".$_SESSION['anio_inicio']."-12-31' )";
			$finsql_al="(pernocta.Fecha_Llegada between '".$_SESSION['anio_inicio']."-01-01' and '".$_SESSION['anio_inicio']."-12-31' )";
			$finsql_pe="(pernocta_p.Fecha_Llegada between '".$_SESSION['anio_inicio']."-01-01' and '".$_SESSION['anio_inicio']."-12-31' )";
		}

/*genero una sentencia que elige todos las provincias que hay en la base de datos para su posterior uso en las consultas que se van a hacer 
mas tarde*/

// se inicia un contador 
$posicion=0;
$sql="select * from provincia";
$result = mysql_query($sql);
				
				for ($i=0;$i<mysql_num_rows($result);$i++)
					{
						$fila = mysql_fetch_array($result);
						
						// se inicia otro contador que va a servir para acumular las personos que hay en total en cada provincia
						
						$contar=0;
						
						/*para grupos: se selecciona el numero de personas que hay en cada grupo asi como la provincia a la que pertenecen 
						y se va acumulando la suma del numero de personas de cada gurpo que son de la misma provincia de procedencia y que esten dentro
						del periodo de tiempo que el usuario de la aplicación haya seleccionado para la periodicidad del gráfico
						el grupo y luego esto se va sumando en el contador que se ha inicializado anteriormente*/
								
						$sqlgrupo="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where grupo.Id_Pais='ES' and grupo.Id_Pro ='".$fila['Id_Pro']."' and ".$finsql_gr;
					
						
						$resultgrupo = mysql_query($sqlgrupo);
						for ($j=0;$j<mysql_num_rows($resultgrupo);$j++)
							{
								$filagrupo = mysql_fetch_array($resultgrupo);
								$contar=$contar+$filagrupo['personas'];
							}
						
						/*para alberguistas: se seleccionan los alberguistas asi como la provincia a la que pertenecen
						y se va acumulando en un contador los alberguistas que son del mismo pais de procedencia y que esten dentro
						del periodo de tiempo que el usuario de la aplicación haya seleccionado para la periodicidad del gráfico
						y luego esto se va sumando en el contador que se ha inicializado anteriormente*/
						
						$sqlalber="select count(cliente.DNI_Cl) as personas from cliente,pernocta where cliente.DNI_Cl =pernocta.DNI_Cl and cliente.Id_Pais='ES' and cliente.Id_Pro ='".$fila['Id_Pro']."' and ".$finsql_al;
						
						$resultalber = mysql_query($sqlalber);
						
						$filaalber = mysql_fetch_array($resultalber);
						
						$contar=$contar+$filaalber['personas'];
		
						
						/*para peregrinos: se seleccionan los peregrinos asi como la provincia al que pertenecen 
						y se va acumulando en un contador los peregrinos que son de la misma provincia de procedencia y que esten dentro
						del periodo de tiempo que el usuario de la aplicación haya seleccionado para la periodicidad del gráfico
						y luego esto se va sumando en el contador que se ha inicializado anteriormente*/
								
						$sqlpere="select count(cliente.DNI_Cl) as personas from cliente,pernocta_p where cliente.DNI_Cl =pernocta_p.DNI_Cl and cliente.Id_Pais='ES' and cliente.Id_Pro ='".$fila['Id_Pro']."' and ".$finsql_pe;
						
						$resultpere = mysql_query($sqlpere);
						
						$filapere = mysql_fetch_array($resultpere);
						
						$contar=$contar+$filapere['personas'];
		
						//meterlo en un array la suma de las personas de esa provincia
						
						if($contar!=0)
							{
								$array[$posicion]['provincia']=$fila['Nombre_Pro'];
								$array[$posicion]['personas']=$contar;
								$posicion=$posicion+1;
							}					 
					}
	
	
				//contar todas las personas para sacar el porcentaje
				
				$personastotal=0;
				for($i=0;$i<count($array);$i++)
					{
						$personastotal=$personastotal+$array[$i]['personas']; 
					}

				for($i=0;$i<count($array);$i++)
					{
						$porcentaje=($array[$i]['personas']*100)/$personastotal;
						$arrayprovincia[$i]=$array[$i]['provincia'];
						$arraypersonas[$i]=$array[$i]['personas'];
					}

//Creamos el título de la gráfica
$sem_trim=" ";
if(($_SESSION['periodicidad']==3) || ($_SESSION['periodicidad']==4))
	{
		if($_SESSION['periodicidad']==3)
			{
				$sem_trim=$_SESSION['mes_inicio'] + 2;
			}
		if($_SESSION['periodicidad']==4)
			{
				$sem_trim=$_SESSION['mes_inicio'] + 5;
			}
	}
	
$meses_est=array(" ", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$mes_elegido=" ";
$mes_trim=" ";
$mes_sem=" ";

	for ($i=1; $i<=12; $i++) 
	{
		if($_SESSION['mes_inicio'] == 0) 
			{
				$mes_elegido = $meses_est[1];
				$mes_trimestre = $meses_est[3];
			}
		if($i == $_SESSION['mes_inicio']) 
			{
				$mes_elegido = $meses_est[$i];	
			}
	}

$titulo=" ";

	if($sem_trim > 12) 
		{	
			$sem_trim= $sem_trim - 12;
			$anio = $_SESSION['anio_inicio'];
			$anio = $anio + 1;
		} 
		else 
			{
				$anio = $_SESSION['anio_inicio'];
			}
			
	if($_SESSION['periodicidad'] == 1) 
		{
			$titulo=date('d-m-Y');
		}
	if($_SESSION['periodicidad'] == 2) 
		{
			$titulo=$mes_elegido." ".$_SESSION['anio_inicio'];
		}
	if($_SESSION['periodicidad'] == 3) 
		{
			$titulo=$mes_elegido." - ".$meses_est[$sem_trim]." ".$anio;
		}
	if($_SESSION['periodicidad'] == 4) 
		{
			$titulo=$mes_elegido." - ".$meses_est[$sem_trim]." ".$anio;
		}
	if($_SESSION['periodicidad'] == 5) 
		{
			$titulo=$_SESSION['anio_inicio'];
		}
		
//Fin del título


//si no hay datos para generar la grafica me sale un mensaje
	if(count($array)==0)
	{

	include ("src/jpgraph.php");
	include ("src/jpgraph_canvas.php");

	/***************************** INICIO NO HAY DATOS *********************************/
		$graph = new CanvasGraph(700,560,'auto');
		$t11 = new Text("No Existen Datos");
		$t11->Pos(0.35,0.45);
		$t11->SetOrientation("h");
		$t11->SetFont(FF_FONT2,FS_BOLD);
		$t11->SetBox("white","black","black");
		$t11->SetColor("red");
		$graph->AddText($t11);
	/***************************** FIN NO HAY DATOS *********************************/
	
	$graph->Stroke();
	
	
	}
	else
	{
	
	/***************************** GRÁFICO DE SECTORES *********************************/
		if($_SESSION['grafico']==1)
		{
			include ("src/jpgraph.php");
			include ("src/jpgraph_pie.php");
			include ("src/jpgraph_pie3d.php");
		
		
			$data = $arraypersonas;
			$graph = new PieGraph(700,560,"auto");
			
			$graph->title->Set("Provincias de Procedencia (".$titulo." )");
			$graph->title->SetFont(FF_FONT2,FS_BOLD);
			$graph->title->SetColor("#000000");
			
			$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
			$graph->SetShadow();
			$graph->SetColor("#f4fcff");
			
			$p1 = new PiePlot3D($data);
			$p1->SetSize(0.45);
			$p1->SetCenter(0.41, 0.5);
			
			
			$p1->SetLegends($arrayprovincia);
			
			$p1->SetValueType(PIE_VALUE_ABS);
			$p1->value->SetFormat("%d");
			$p1->value->SetFont(FF_ARIAL, FS_NORMAL, 10);
			$p1->value->SetColor("black");
			$graph->legend->SetPos(0.05,0.2,'right','center');
			$graph->Add($p1);
			$graph->Stroke();
		}
	/***************************** FIN DEL GRÁFICO DE SECTORES *********************************/
	
	
	/***************************** GRÁFICO DE BARRAS *********************************/
	
		if($_SESSION['grafico']==2)
		{
		
			include ("src/jpgraph.php"); 
			include ("src/jpgraph_bar.php"); 
			include ("src/jpgraph_line.php"); 
		
			//Nombre de los campos del eje X
			$nombres_x = $arrayprovincia;
			// We need some data 
			$datay1 = $arraypersonas;
			// Setup the graph.  
			$graph = new Graph(700,560,"auto");
			$graph->img->SetMargin(40,40,45,75); 
			$graph->SetScale("textlin"); 
			$graph->SetMarginColor("#f4fcff"); 
			$graph->SetShadow();
			
			// Create the bar pot 
			$bplot1 = new BarPlot($datay1); 
			
			$bplot1->value->Show();	
			$bplot1->value->SetFormat('%d');
			$bplot1->value->SetColor("black");
			$bplot1->value->SetFont(FF_FONT2,FS_BOLD);
			$bplot1->SetWidth(0.5);
			$bplot1->SetShadow('darkgray@0.5');
			
			
			$gbarplot = $bplot1;
			$gbarplot->SetWidth(0.3);
			$graph->Add($gbarplot);
			
			// Set up the title for the graph 
			$graph->title->Set("Provincias de Procedencia (".$titulo." )");
			$graph->title->SetFont(FF_FONT2,FS_BOLD);
			$graph->title->SetColor("black");
			$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
			$graph->SetColor("#f4fcff");
			
			// Setup color for axis and labels 
			$graph->xaxis->SetColor("black","black");
			$graph->xaxis->SetTickLabels($nombres_x);
			
			//para el angulo de las provincias
			$graph->xaxis->SetLabelAngle(30);
		
			$graph->yaxis->SetColor("black","black"); 
			
			// Setup font for axis
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL); 
			$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL); 
			
			// Setup X-axis title (color & font)
			$graph->xaxis->title->SetMargin(5,0,0,0); 
			$graph->xaxis->title->SetColor("#ffffff"); 
			$graph->xaxis->title->SetFont(FF_VERDANA,FS_BOLD); 
			
			
			// Setup Y-axis title (color & font) 
			
			$graph->yaxis->title->SetMargin(15,0,0,0); 
			$graph->yaxis->title->SetColor("#ffffff"); 
			$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD); 
		
			
			// Finally send the graph to the browser 
			$graph->Stroke(); 
		}
		
		/***************************** GRÁFICO DE BARRAS *********************************/
		
		/***************************** GRÁFICO DE POLIGONO *********************************/
		if($_SESSION['grafico']==3)
		{
			include ("src/jpgraph.php");
			include ("src/jpgraph_radar.php");
	
			if(count($arraypersonas)==1)
			{
				$arraypersonas[1]=0;
				$arrayprovincia[1]="";
				$arraypersonas[2]=0;
				$arrayprovincia[2]="";
			}
			if(count($arraypersonas)==2)
			{
				$arraypersonas[2]=0;
				$arrayprovincia[2]="";
			}
			if(count($arrayprovincia)<=2)
				{
					$graph = new CanvasGraph(700,560,'auto');
						$t11 = new Text("No existen datos suficientes");
						$t11->Pos(0.35,0.45);
						$t11->SetOrientation("h");
						$t11->SetFont(FF_FONT2,FS_BOLD);
						$t11->SetBox("white","black","black");
						$t11->SetColor("red");
						$graph->AddText($t11);
						$graph->Stroke();
				}
				else
					{
					$data = $arraypersonas;
					
				// Create the graph and the plot
				$graph = new RadarGraph(700,560,"auto");
				
				$graph->title->Set("Provincias de Procedencia (".$titulo." )");
				$graph->title->SetFont(FF_FONT2,FS_BOLD);
				$graph->title->SetColor("black");
				
				$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
				$graph->SetColor("#f4fcff");
				$graph->SetShadow();
				$entrada = $arrayprovincia;
				$graph->SetTitles($entrada);
				$graph->axis->title->SetColor("black");
				$graph->axis->title->SetFont(FF_ARIAL,FS_NORMAL);
				$graph->axis->SetColor("black");
				
				
				$plot = new RadarPlot($data);
				
				$plot->SetFillColor('lightblue');
				$plot->SetColor('red','lightblue');
				$plot->SetLineWeight(2);
				$graph->SetSize(0.55);
				$graph->SetPos(0.5,0.5);
				// Add the plot and display the graph
				$graph->Add($plot);
				$graph->Stroke();
				}
		}
	/***************************** FIN DEL GRÁFICO DE POLIGONO *********************************/
	}
mysql_close($db);
?>