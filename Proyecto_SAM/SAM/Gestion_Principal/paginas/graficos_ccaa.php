<?
session_start();


	$CCAA=array();


	$a_ini=$_SESSION['anio_inicio'];
	$m_ini=$_SESSION['mes_inicio'];
	@ $db =MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	if ($_SESSION['periodicidad']==1){//DEL DIA DEL SISTEMA
		$c=0;
		$query_ccaa1="SELECT cc.Nombre_CCAA,count( cl.Sexo_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (p.Fecha_Llegada = '".date("Y")."-".date("m")."-".date("d")."' OR (p.Fecha_Llegada <='".date("Y")."-".date("m")."-".date("d")."'	AND p.Fecha_Salida >'".date("Y")."-".date("m")."-".date("d")."'))AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA  and (cl.Sexo_Cl='M' or cl.Sexo_Cl='F')GROUP BY cc.Nombre_CCAA;";
		$query_ccaa2="SELECT cc.Nombre_CCAA,count( cl.Sexo_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (p.Fecha_Llegada = '".date("Y")."-".date("m")."-".date("d")."' OR (p.Fecha_Llegada <='".date("Y")."-".date("m")."-".date("d")."'	AND p.Fecha_Salida >'".date("Y")."-".date("m")."-".date("d")."'))AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA  and (cl.Sexo_Cl='M' or cl.Sexo_Cl='F')GROUP BY cc.Nombre_CCAA;";
		$query_ccaa3="SELECT cc.Nombre_CCAA, Num_Personas AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE  (est.Fecha_Llegada = '".date("Y")."-".date("m")."-".date("d")."' OR (est.Fecha_Llegada <='".date("Y")."-".date("m")."-".date("d")."' AND est.Fecha_Salida >'".date("Y")."-".date("m")."-".date("d")."'))AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA GROUP BY Nombre_CCAA;";

	}else if ($_SESSION['periodicidad']==2){//MENSUAL		
		$query_ccaa1="SELECT cc.Nombre_CCAA,count( cl.Sexo_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE ((substring( p.Fecha_Llegada, 1, 7 ) < '".$a_ini."-".$m_ini."' AND p.Fecha_Salida >= '".$a_ini."-".$m_ini."-".$d."')OR (substring( p.Fecha_Llegada, 1, 7 ) ='".$a_ini."-".$m_ini."'  AND substring( p.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."'))AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND (cl.Sexo_Cl='M' or cl.Sexo_Cl='F')GROUP BY cc.Nombre_CCAA;";
		
		$query_ccaa2="SELECT cc.Nombre_CCAA,count( cl.Sexo_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE ((substring( p.Fecha_Llegada, 1, 7 ) < '".$a_ini."-".$m_ini."' AND p.Fecha_Salida >= '".$a_ini."-".$m_ini."-".$d."')OR (substring( p.Fecha_Llegada, 1, 7 ) ='".$a_ini."-".$m_ini."'  AND substring( p.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."'))AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND (cl.Sexo_Cl='M' or cl.Sexo_Cl='F')GROUP BY cc.Nombre_CCAA;";
								
		//Ahora busco hombres que esten en grupos
		$query_ccaa3="SELECT cc.Nombre_CCAA,Num_Personas AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE ((substring( est.Fecha_Llegada, 1, 7 ) < '".$a_ini."-".$m_ini."' AND est.Fecha_Salida >= '".$a_ini."-".$m_ini."-".$d."')OR (substring( est.Fecha_Llegada, 1, 7 ) ='".$a_ini."-".$m_ini."'  AND substring( est.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."')) AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA GROUP BY cc.Nombre_CCAA;";
									
						
	}elseif($_SESSION['periodicidad']==3 or $_SESSION['periodicidad']==4){//Trimestral
		
		if($_SESSION['periodicidad']==3){
			if($m_ini<=10){
				$m_fin=$m_ini+3;										
				$a_fin=$a_ini;
											
			}else{
				$m_fin=substr($m_ini,-1);											
				$a_fin=$a_ini+1;
			}
		}else{
			if($m_ini<=7){
				$m_fin=$m_ini+6;										
				$a_fin=$a_ini;
											
			}else{
				$m_fin=$m_ini+3;
				$m_fin='0'.substr($m_fin,-1);										
				$a_fin=$a_ini+1;
			}
		}
			$fecha_ini = $a_ini.'-'.$m_ini.'-01';
			$fecha_fin = $a_fin.'-'.$m_fin.'-01';
																		
									
			//busco hombres alberguistas
			$query_ccaa1="SELECT cc.Nombre_CCAA,count( cl.Sexo_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND (cl.Sexo_Cl='M' or cl.Sexo_Cl='F') GROUP BY cc.Nombre_CCAA;";
									
									
			$query_ccaa2="SELECT cc.Nombre_CCAA,count( cl.Sexo_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND (cl.Sexo_Cl='M' or cl.Sexo_Cl='F') GROUP BY cc.Nombre_CCAA;";
									

			$query_ccaa3="SELECT cc.Nombre_CCAA, Num_Personas AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE((est.Fecha_Llegada <= '".$fecha_ini."' AND  est.Fecha_Salida >= '".$fecha_fin."')or  (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Salida <= '".$fecha_fin."')or (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Llegada <= '".$fecha_fin."' and est.Fecha_Salida >= '".$fecha_fin."'))AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA GROUP BY cc.Nombre_CCAA;";
									



	}else {
		if($_SESSION['periodicidad']==5){
									
			$query_ccaa1="SELECT CC.Nombre_CCAA, count( cl.Sexo_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND (cl.Sexo_Cl='M' or cl.Sexo_Cl='F') GROUP BY cc.Nombre_CCAA;";
									
			$query_ccaa2="SELECT CC.Nombre_CCAA, count( cl.Sexo_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND (cl.Sexo_Cl='M' or cl.Sexo_Cl='F') GROUP BY cc.Nombre_CCAA;";
									


			//Ahora busco hombres que esten en grupos
			$query_ccaa3="SELECT CC.Nombre_CCAA, Num_Personas AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE (substring( est.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA GROUP BY cc.Nombre_CCAA;";
									
		}
									
	}

	if ($_SESSION['tipo_cliente']==1){//TODOS
		$c=0;
		$result_ccaa1=mysql_query($query_ccaa1);
		for($v=0;$v<mysql_num_rows($result_ccaa1);$v++){	
			$fila1=mysql_fetch_array($result_ccaa1);
			$esta=in_array($fila1['Nombre_CCAA'],$CCAA);
					
			if(!$esta){		
				$CCAA[$c]=$fila1['Nombre_CCAA'];	
				
				$c=$c+1;
			}
		}							
		$result_ccaa2=mysql_query($query_ccaa2);								
		for($v=0;$v<mysql_num_rows($result_ccaa2);$v++){	
			$fila2=mysql_fetch_array($result_ccaa2);							
			$esta=in_array($fila2['Nombre_CCAA'],$CCAA);
			
			if(!$esta){
				$CCAA[$c]=$fila2['Nombre_CCAA'];				
				$c=$c+1;
			}
		}							
							
		$result_ccaa3=mysql_query($query_ccaa3);
		for($v=0;$v<mysql_num_rows($result_ccaa3);$v++){	
			$fila3=mysql_fetch_array($result_ccaa3);								
			$esta=in_array($fila3['Nombre_CCAA'],$CCAA);			
			if(!$esta){
				$CCAA[$c]=$fila3['Nombre_CCAA'];
				$c=$c+1;
			}
		}
									
	}
	$total_h_global=0;
	$total_m_global=0;
	$total_todos_global=0;
	for($y=0;$y<sizeof($CCAA);$y++){
		$total_comu[$y]=0;
		$h=0;
		$m=0;

		//LAS BUSQUEDAS SELECCIONADAS
		
		//en el dia actual
		if ($_SESSION['periodicidad']==1){
			//busco hombres alberguistas
			$sql_h=	"SELECT count( cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M' ;";
			

			//Ahora busco hombres que sean peregrinos
			$sql_h1="SELECT count(cl.DNI_Cl) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M' ;";
			
									
			//Ahora busco hombres que esten en grupos
			$sql_h2="SELECT SUM(Num_Hombres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE (est.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (est.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and est.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."';";

			//busco mujeres alberguistas
			$sql_m=	"SELECT count( cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F';";
			
			//Ahora busco mujeres que sean peregrinos
			$sql_m1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (p.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F';";
		
											
			//Ahora busco mujeres que esten en grupos
			$sql_m2="SELECT SUM(Num_Mujeres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE (est.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (est.Fecha_Llegada <= '".date("Y")."-".date("m")."-".date("d")."' and est.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."')) AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' ;";


			
		//Busqueda en el mes seleccionado
		}elseif ($_SESSION['periodicidad']==2){

			//busco hombres alberguistas
			$sql_h=	"SELECT count( cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 7 ) <= '".$a_ini."-".$m_ini."' AND substring( p.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M' ;";
				
			
			//Ahora busco hombres que sean peregrinos
			$sql_h1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 7 ) <= '".$a_ini."-".$m_ini."' AND substring( p.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M' ;";
			
			//Ahora busco hombres que esten en grupos
			$sql_h2="SELECT SUM(Num_Hombres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE (substring( est.Fecha_Llegada, 1, 7 ) <= '".$a_ini."-".$m_ini."' AND substring( est.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."') AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' ;";
			
			//busco mujeres alberguistas
			$sql_m=	"SELECT count( cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 7 ) <= '".$a_ini."-".$m_ini."' AND substring( p.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F' ;";

			//Ahora busco mujeres que sean peregrinos
			$sql_m1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 7 ) <= '".$a_ini."-".$m_ini."' AND substring( p.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F' ;";

			//Ahora busco mujeres que esten en grupos
			$sql_m2="SELECT SUM(Num_Mujeres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE (substring( est.Fecha_Llegada, 1, 7 ) <= '".$a_ini."-".$m_ini."' AND substring( est.Fecha_Salida, 1, 7 ) >= '".$a_ini."-".$m_ini."') AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' ;";
								
								
		//Busqueda Trimestral o semestral
		}elseif($_SESSION['periodicidad']==3 or $_SESSION['periodicidad']==4){//Trimestral
		
			if($_SESSION['periodicidad']==3){
				if($m_ini<=10){
					$m_fin=$m_ini+3;										
					$a_fin=$a_ini;
											
				}else{
					$m_fin='0'.substr($m_ini,-1);											
					$a_fin=$a_ini+1;
				}
			}else{
				if($m_ini<=7){
					$m_fin=$m_ini+6;										
					$a_fin=$a_ini;
				}else{
					$m_fin=$m_ini+3;
					$m_fin='0'.substr($m_fin,-1);										
					$a_fin=$a_ini+1;
				}
			}
			$fecha_ini = $a_ini.'-'.$m_ini.'-01';
			$fecha_fin = $a_fin.'-'.$m_fin.'-01';
										
			//busco hombres alberguistas
			$sql_h="SELECT count( cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M';";
			
									
			$sql_h1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M' ;";
			


			$sql_h2="SELECT SUM(Num_Hombres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE((est.Fecha_Llegada <= '".$fecha_ini."' AND  est.Fecha_Salida >= '".$fecha_fin."')or  (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Salida <= '".$fecha_fin."')or (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Llegada <= '".$fecha_fin."' and est.Fecha_Salida >= '".$fecha_fin."'))AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."';";
			
			//MUJERES
			$sql_m="SELECT count(cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F' ;";
										
			$sql_m1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F' ;";
										

			$sql_m2="SELECT SUM(Num_Mujeres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE((est.Fecha_Llegada <= '".$fecha_ini."' AND  est.Fecha_Salida >= '".$fecha_fin."')or  (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Salida <= '".$fecha_fin."')or (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Llegada <= '".$fecha_fin."' and est.Fecha_Salida >= '".$fecha_fin."'))AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."';";
			

			//Busqueda anual
									
		}else{
			if($_SESSION['periodicidad']==5){

				//busco hombres alberguistas
				$sql_h=	"SELECT count( cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M' ;";
				

				//busco hombres peregrinos
				$sql_h1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M' ;";
				


				//Ahora busco hombres que esten en grupos
				$sql_h2="SELECT SUM(Num_Hombres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE (substring( est.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."';";

				//busco mujeres alberguistas
				$sql_m="SELECT count( cl.DNI_Cl) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F';";
			

				//busco mujeres peregrinos
				$sql_m1="SELECT count( cl.DNI_Cl) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE (substring( p.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F';";
			
				//Ahora busco mujeres que esten en grupos
				$sql_m2="SELECT SUM(Num_Mujeres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE (substring( est.Fecha_Llegada, 1, 4 ) = '".$a_ini."') AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."';";
				
			}
									
		}

		//En el grafico se muestran todos(no se fracciona por alberguistas o peregrinos)
		if($_SESSION['tipo_cliente']==1)	{	//Todos	
			//Hombres
			$result_h=mysql_query($sql_h);								
			$filah=mysql_fetch_array($result_h);
			$result_h1=mysql_query($sql_h1);								
			$filah1=mysql_fetch_array($result_h1);
			$result_h2=mysql_query($sql_h2);								
			$filah2=mysql_fetch_array($result_h2);
			
			$h= $filah['num']+$filah1['num']+$filah2['num'];
			//Mujeres
			$result_m=mysql_query($sql_m);								
			$filam=mysql_fetch_array($result_m);
			$result_m1=mysql_query($sql_m1);								
			$filam1=mysql_fetch_array($result_m1);
			$result_m2=mysql_query($sql_m2);								
			$filam2=mysql_fetch_array($result_m2);

			$m=$m+ $filam['num']+ $filam1['num']+ $filam2['num'];
		}
		$hombres[$y]=$h;	
		$mujeres[$y]=$m;	
		$total_comu[$y]=$hombres[$y]+$mujeres[$y];	
		$totales_personas[$y]=$total_comu[$y];//Guardo en el array la suma de hombres y mujeres
		$total_h_global=$total_h_global+$hombres[$y];//Total hombres pa esa ccaa
		$total_m_global=$total_m_global+$mujeres[$y];//Total mujeres pa esa ccaa
		$total_todos_global=$total_todos_global+$total_comu[$y];
		$titulo="YO";
}//Cierre for de paises
				
				

$meses=array(" ", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
if($m_ini<10){
	$m_ini1=substr($m_ini,-1);
}else{
	$m_ini1=substr($m_ini,-2);
}
if($m_fin<10){
	$m_fin1=substr($m_fin,-1);
}else{
	$m_fin1=substr($m_fin,-2);
}	
if($_SESSION['periodicidad']==1){
		$titulo="Dia ".date("d")."-".date("m")."-".date("Y");
}else if($_SESSION['periodicidad']==2){
	$titulo="Mes de ".$meses[$m_ini]." ".$a_ini;
}else if($_SESSION['periodicidad']==3 || $_SESSION['periodicidad']==4){
		$titulo=$meses[$m_ini1]." - ".$meses[$m_fin1]." ".$a_ini;

}else{
	
	if($_SESSION['periodicidad']==5){
		$titulo="".$a_ini;
	}
}

if(sizeof($CCAA)<=0){
		include("src/jpgraph.php");
		include("src/jpgraph_canvas.php");
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

}else{				//echo "totales".$CCAA[0].' '.$CCAA[1].' '.$CCAA[1].' '.$CCAA[2].
	if($_SESSION['grafico']==1){

		include ("src/jpgraph.php");
		include ("src/jpgraph_pie.php");
		include ("src/jpgraph_pie3d.php");
		$data = $totales_personas;
		$graph = new PieGraph(700,560,"auto");		
		$graph->title->Set("Comunidades Autónomas de Procedencia ( ".$titulo." )");
		$graph->title->SetFont(FF_FONT2,FS_BOLD, 15);
		$graph->title->SetColor("#000000");
		$graph->SetShadow();
		$graph->SetFrame(true,'black','white@0.4','white@0.4',2);	
		$graph->SetColor("#f4fcff");	
		$p1 = new PiePlot3D($data);
		$p1->SetSize(0.45);
		$p1->SetCenter(0.41, 0.5);	
		$p1->SetLegends($CCAA);	
		$p1->SetValueType(PIE_VALUE_ABS);
		$p1->value->SetFormat("%d");
		$p1->value->SetFont(FF_ARIAL, FS_NORMAL);
		$p1->value->SetColor("#000000");
		$graph->legend->SetPos(0.05,0.09);
		$graph->legend->SetFont(FF_FONT2,FF_BOLD);
		$graph->Add($p1);
		$graph->Stroke();
		
	}else if($_SESSION['grafico']==2){
		include ("src/jpgraph.php"); 
		include ("src/jpgraph_bar.php"); 
		include ("src/jpgraph_line.php"); 

		
		//Nombre de los campos del eje X
		$nombres_x = $CCAA;
		
		// We need some data 
		$datay1 = $totales_personas;
		
		
		// Setup the graph.  
		$graph = new Graph(700,560,"auto");
		$graph->img->SetMargin(40,40,45,75); 
		$graph->SetScale("textlin"); 
		$graph->SetMarginColor("white");
		$graph->SetShadow();
	 
		$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
		
		$graph->SetMarginColor("#f4fcff");
		// Create the bar pot 
		$bplot1 = new BarPlot($datay1); 

		$bplot1->value->Show();	
		$bplot1->value->SetFormat('%d');
		$bplot1->value->SetColor("black");
		$bplot1->value->SetFont(FF_FONT2,FS_BOLD);
		$bplot1->SetWidth(0.5);
		$bplot1->SetShadow('darkgray@0.5');
		
		$gbarplot = $bplot1;
		$gbarplot->SetWidth(0.2);
		$graph->Add($gbarplot);
		
		// Set up the title for the graph 
		$graph->title->Set("Comunidades Autónomas de Procedencia ( " .$titulo." )");
		$graph->title->SetFont(FF_FONT2,FS_BOLD);
		$graph->title->SetColor("black");
		
		$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
		$graph->SetColor("#f4fcff");
		
		// Setup color for axis and labels 
		$graph->xaxis->SetColor("black","black");
		$graph->xaxis->SetTickLabels($CCAA);
		
		//para el angulo de las paises
		$graph->xaxis->SetLabelAngle(30);

		$graph->yaxis->SetColor("black","black"); 
		
		// Setup font for axis
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,9); 
		$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,10); 
		
		// Setup X-axis title (color & font)
		$graph->xaxis->title->SetMargin(5,0,0,0); 
		$graph->xaxis->title->SetColor("#ffffff"); 
		$graph->xaxis->title->SetFont(FF_VERDANA,FS_BOLD,10); 
		
		// Setup Y-axis title (color & font) 
		$graph->yaxis->title->SetMargin(15,0,0,0); 
		$graph->yaxis->title->SetColor("#ffffff"); 
		$graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,10); 

		
		// Finally send the graph to the browser 
		$graph->Stroke(); 
	}
	else if($_SESSION['grafico']==3){
		
		
		include ("src/jpgraph.php");
		include ("src/jpgraph_radar.php");
		if(sizeof($totales_personas)==1)
				{
					$totales_personas[1]=0;
					$CCAA[1]=" ";
					$totales_personas[2]=0;
					$CCAA[2]=" ";
				}
			if(sizeof($totales_personas)==2)
				{
					$totales_personas[2]=0;
					$CCAA[2]=" ";
				}
		$data = $totales_personas;
			// Create the graph and the plot
			$graph = new RadarGraph(700,560,"auto");
			$graph->SetShadow();
			$graph->title->Set("Comunidades Autónomas de Procedencia ( ".$titulo." )");
			$graph->title->SetFont(FF_FONT2,FS_BOLD);
			$graph->title->SetColor("black");
			
			$graph->SetFrame(true,'black','white@0.4','white@0.4',2);
			$graph->SetColor("#f4fcff");
			
			$entrada =$CCAA;
			$graph->SetTitles($entrada);
			$graph->axis->title->SetColor("black");
			$graph->axis->title->SetFont(FF_ARIAL,FS_NORMAL,10);
			$graph->axis->SetColor("black");
			
			
			$plot = new RadarPlot($data);
			$plot->SetFillColor('lightblue');
			$plot->SetColor('red','lightblue');
			$plot->SetLineWeight(2);
			$graph->SetSize(0.55);
			$graph->SetPos(0.5,0.5);
			// Add the plot and display the graph
			$graph->Add($plot);
		
			
		}$graph->Stroke();
}
mysql_close($db); ?>
