<?php
session_start();
?>
<?php

	/* Esta página esta incluida dentro de la página de estadísticas y esta es una ayuda para el gráfico de paises, con lo cual solo 
	se muestra por pantalla cuando alguna de las gráficas de paises se esta representando en el subsistema de estadísticas
	y las variables de sesion que se usan en esta página se obtienen de la página de valores de estadística y las demás variables 
	de las otras páginas que estan incluidas dentro de la página estadisticas.php */

// Aqui se hace la conexion con la base de datos

  @ $db =MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db)
	{
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	
	
if($_SESSION['tipo_estadisticas']=="pais")
{
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
		
		if($_SESSION['tiempo']=="1")
		{
			$finsql_gr="(estancia_gr.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (estancia_gr.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and estancia_gr.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
			$finsql_al="(pernocta.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
			$finsql_pe="(pernocta_p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta_p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and pernocta_p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'));";
		}
		
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es mensual : Aqui se construye la parte de la sentencia  donde se elige el mes del cual se desea la estadística
	 y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese mes, y esta parte luego va a 
	 formar parte de otra sentencia donde ya se elegira las tablas y campos de consulta  */
		
		if($_SESSION['tiempo']=="2")
		{ 
			//saco la fecha final del mes para la comparacion de fechas
			$finsql_gr="(
							(substring(estancia_gr.Fecha_Llegada,1,7)='".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') or 
							(substring(estancia_gr.Fecha_Salida,1,7)='".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') or 
							(substring(estancia_gr.Fecha_Llegada,1,7)<'".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."')
						);";
		
			$finsql_al="(
							(substring(pernocta.Fecha_Llegada,1,7)='".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') or 
							(substring(pernocta.Fecha_Salida,1,7)='".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') or 
							(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."')
						);";		
			
			$finsql_pe="(
							(substring(pernocta_p.Fecha_Llegada,1,7)='".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') or 
							(substring(pernocta_p.Fecha_Salida,1,7)='".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') or 
							(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."')
						);";
		}
		
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es trimestral : Aqui se construye la parte de la sentencia  donde se elige el trimestre del cual se desea la 
	estadística y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese trimestre, y esta parte 
	luego va a formar parte	de otra sentencia donde ya se elegira las tablas y campos de consulta  */
		
		if($_SESSION['tiempo']=="3")
		{
		
			//saco la fecha final sumandole 3 meses mas 
			$fech_trim_sem=date("Y-m-d",mktime(0,0,0,$_SESSION['mes_est']+2,7,$_SESSION['anio_est']));
			$tr_sem=substr($fech_trim_sem,0,7);
							
				$finsql_gr="(
							(substring(estancia_gr.Fecha_Llegada,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."')or 
							(substring(estancia_gr.Fecha_Salida,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
							(substring(estancia_gr.Fecha_Llegada,1,7)< '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$tr_sem."')
						);";
				$finsql_al="(
								(substring(pernocta.Fecha_Llegada,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta.Fecha_Salida,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$tr_sem."')
							);";
				$finsql_pe="(
								(substring(pernocta_p.Fecha_Llegada,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta_p.Fecha_Salida,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$tr_sem."')
							);";
		}
		
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es semestral : Aqui se construye la parte de la sentencia  donde se elige el semestre del cual se desea la 
	estadística y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese semestre, y esta parte 
	luego va a formar parte	de otra sentencia donde ya se elegira las tablas y campos de consulta  */
		
		if($_SESSION['tiempo']=="4")
		{
			//saco la fecha final sumandole 6 meses mas 
			$fech_trim_sem=date("Y-m-d",mktime(0,0,0,$_SESSION['mes_est']+5,7,$_SESSION['anio_est']));
			$tr_sem=substr($fech_trim_sem,0,7);
							
				$finsql_gr="(
							(substring(estancia_gr.Fecha_Llegada,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."')or 
							(substring(estancia_gr.Fecha_Salida,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
							(substring(estancia_gr.Fecha_Llegada,1,7)< '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$tr_sem."')
						);";
				$finsql_al="(
								(substring(pernocta.Fecha_Llegada,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta.Fecha_Salida,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$tr_sem."')
							);";
				$finsql_pe="(
								(substring(pernocta_p.Fecha_Llegada,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta_p.Fecha_Salida,1,7) between '".$_SESSION['anio_est']."-".$_SESSION['mes_est']."' and '".$tr_sem."') or 
								(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_est']."-".$_SESSION['mes_est']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$tr_sem."')
							);";

		}
		
	//--------------------------------------------------------------------------------------------------------------
	/*Si la estadística es anual : Aqui se construye la parte de la sentencia  donde se elige el año del cual se desea la estadística
	 y seleccionará las pernoctas y las estancias de los grupos que hayan pernoctado en el albergue ese año, y esta parte luego va a 
	 formar parte de otra sentencia donde ya se elegira las tablas y campos de consulta  */
		
		if($_SESSION['tiempo']=="5")
		{
			$finsql_gr="(estancia_gr.Fecha_Llegada  between '".$_SESSION['anio_est']."-01-01' and '".$_SESSION['anio_est']."-12-31' )";
			$finsql_al="(pernocta.Fecha_Llegada between '".$_SESSION['anio_est']."-01-01' and '".$_SESSION['anio_est']."-12-31' )";
			$finsql_pe="(pernocta_p.Fecha_Llegada between '".$_SESSION['anio_est']."-01-01' and '".$_SESSION['anio_est']."-12-31' )";
		}
		
		
		/*genero una sentencia que elige todos los paises que hay en la base de datos con lo que se va a trabajar en las sentencias que se van a 
generar mas tarde */

// se inicia un contador 
$posicion=0;

$sql="select * from pais";
$result = mysql_query($sql);
						
			for ($i=0;$i<mysql_num_rows($result);$i++)
				{
					$fila = mysql_fetch_array($result);
					
					// se inicia otro contador que va a servir para acumular las personos que hay en total en cada pais
					$contar=0;
					
					/*para grupos: se selecciona el numero de personas que hay en cada grupo asi como el pais al que pertenece 
					y se va acumulando la suma del numero de personas de cada gurpo que son del mismo pais de procedencia y que esten dentro
					del periodo de tiempo que el usuario de la aplicación haya seleccionado para la periodicidad del gráfico
					el grupo y luego esto se va sumando en el contador que se ha inicializado anteriormente*/
					
					$sqlgrupo="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where grupo.Id_Pais='".$fila['Id_Pais']."' and".$finsql_gr;
					
					$resultgrupo = mysql_query($sqlgrupo);
					
					for ($j=0;$j<mysql_num_rows($resultgrupo);$j++)
						{
							$filagrupo = mysql_fetch_array($resultgrupo);
							$contar=$contar+$filagrupo['personas'];
						}
					/*para alberguistas: se seleccionan los alberguistas asi como el pais al que pertenecen
					y se va acumulando en un contador los alberguistas que son del mismo pais de procedencia y que esten dentro
					del periodo de tiempo que el usuario de la aplicación haya seleccionado para la periodicidad del gráfico
					y luego esto se va sumando en el contador que se ha inicializado anteriormente*/
					
					$sqlalber="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Id_Pais='".$fila['Id_Pais']."' and ".$finsql_al;
					
					$resultalber = mysql_query($sqlalber);
					
					$filaalber = mysql_fetch_array($resultalber);
					
					$contar=$contar+$filaalber['personas'];
	
					
					/*para peregrinos: se seleccionan los peregrinos asi como el pais al que pertenecen 
					y se va acumulando en un contador los peregrinos que son del mismo pais de procedencia y que esten dentro
					del periodo de tiempo que el usuario de la aplicación haya seleccionado para la periodicidad del gráfico
					y luego esto se va sumando en el contador que se ha inicializado anteriormente*/
						
					$sqlpere="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Id_Pais='".$fila['Id_Pais']."' and ".$finsql_pe;
					
					$resultpere = mysql_query($sqlpere);
					
					$filapere = mysql_fetch_array($resultpere);
					
					$contar=$contar+$filapere['personas'];
	
					//se mete en un array la suma de las personas de ese pais
					
					if($contar!=0)
						{
							$array[$posicion]['pais']=$fila['Id_Pais'];
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
							$arraypais[$i]=$array[$i]['pais'];
							$arraypersonas[$i]=$array[$i]['personas'];
						}
		
		// se lanza esta select para sacar el nombre del pais junto con su id para mostrarlo en la tabla que se crea debajo
		$nombre_pais="select Nombre_Pais, Id_Pais from pais where Id_Pais=";
		$cadena_paises="";
			for($i=0;$i<count($arraypais);$i++)
				{
					$sql_nombre_pais=$nombre_pais." '".$arraypais[$i]."';";
					$resul_sql=mysql_query($sql_nombre_pais);
					$res_sql=mysql_num_rows($resul_sql);
						if($res_sql=1)
							{
								$fila=mysql_fetch_array($resul_sql);
								$cada_pais=" ".$fila['Id_Pais']."      ".substr($fila['Nombre_Pais'],0,23)."\n";
								$cadena_paises=$cadena_paises.$cada_pais;
							}
				}
			
					
	
			include ("src/jpgraph.php");
			include ("src/jpgraph_canvas.php");
			

		
			/*if( empty($_GET['a']) ) {
				$angle=40;
			}
			else {
				$angle=$_GET['a'];
			}*/
			
			
			$txt=" País \n ";
			
			
			// Initial width and height since we need a "dummy" canvas to
			// calculate the height of the text strings
			$w=250;$h=1200;
			$xm=90;$ym=30;
			
			$graph = new CanvasGraph($w,$h,"auto");
			
			$aImg = $graph->img;
			
			$aImg->SetFont(FF_ARIAL,FS_NORMAL,16);
			$tw=$aImg->GetBBoxWidth($txt,90);
			$th=$aImg->GetBBoxHeight($txt,90);
			
			$aImg->SetFont(FF_ARIAL,FS_NORMAL,11);
			$ch=$aImg->GetBBoxHeight($cadena_paises);
			
			// Calculate needed height for the image
			$h = 1*$th+1*$ym + $ch;
			$graph = new CanvasGraph($w,$h);
			$aImg = $graph->img;
			
			
			
			for( $i=0,$r=0,$c=0; $i < 1; ++$i ) {
				$x = $c*($tw+$xm)+$xm;
				$y = $r*($th+$ym)+$ym;
				            
				$aImg->SetFont(FF_ARIAL,FS_NORMAL);
				$aImg->StrokeText(0,0,$txt,0,"left");
			
				$aImg->SetColor('black');
				$aImg->SetFont(FF_ARIAL,FS_BOLD,12);
				$aImg->SetTextAlign('center','center');            
				$align="".$txt."Cód.    Nombre \n\n\n\n";
				$aImg->StrokeText($c*($tw/2+$xm)+$xm/2+$tw/2,$r*($th/2+$ym)+$th+$ym/2-4,$align);
				$c++;
				if( $c==3 ) 
				{
					$c=0;$r++;
				}
			}
			$t1 = new Text($cadena_paises);
			$t1->Pos(0.04,65);
			$t1->SetFont(FF_FONT1,FS_NORMAL);
			$t1->SetBox("lightblue@0.9","orange");
			$t1->ParagraphAlign("left");
			$t1->SetColor("black");
			
			//$aImg->SetTextAlign('center','bottom');            
			//$aImg->SetFont(FF_ARIAL,FS_ITALIC);
			//$aImg->StrokeText($w/2,$h-10,$cadena_paises,0,'left');
			
			$aImg->SetColor('navy');
			$aImg->Rectangle(0,0,0,0);
			
			$graph->AddText($t1);
			
			$graph->Stroke();
	
}

mysql_close($db);
?>

