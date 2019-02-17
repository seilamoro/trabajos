<?
session_start();

?>
<head>
<style>body{
	font-family: Arial, Helvetica, sans-serif;
}</style> 
<link type='text/css' rel='stylesheet' href='./css/estadisticas.css'>
</head>
<script language="javascript">	
 var CCAA = new Array();
 var esta;
 var x;


function Escribir(elem,valor,total){
	
	var elemento;
	elemento="porcent"+elem;
	x=Math.round(1000/total*valor)/10;	
	document.getElementById(elemento).value=x;
		
		
		
		
	
}

</script>

<?
	@ $db =MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	$d='02';
	$m_ini=$_SESSION['mes_inicio'];
	$a_ini=$_SESSION['anio_inicio'];
	$a_final=$a_ini+1;
	$CCAA=array();
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
				$m_fin='0'.$m_fin;
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
									
	}else if($_SESSION['tipo_cliente']==2){//ALBERGUISTA
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
		$result_ccaa3=mysql_query($query_ccaa3);								
		for($v=0;$v<mysql_num_rows($result_ccaa3);$v++){	
			$fila3=mysql_fetch_array($result_ccaa3);							
			$esta=in_array($fila3['Nombre_CCAA'],$CCAA);
			
			if(!$esta){
				$CCAA[$c]=$fila3['Nombre_CCAA'];				
				$c=$c+1;
			}
		}				
	}else if($_SESSION['tipo_cliente']==3){//PEREGRINOS
		$c=0;
		$result_ccaa2=mysql_query($query_ccaa2);								
		for($v=0;$v<mysql_num_rows($result_ccaa2);$v++){	
			$fila2=mysql_fetch_array($result_ccaa2);							
			$esta=in_array($fila2['Nombre_CCAA'],$CCAA);
			
			if(!$esta){
				$CCAA[$c]=$fila2['Nombre_CCAA'];				
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
		//Busqueda en el dia actual
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
					$m_fin='0'.$m_fin;
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
			
			
									
			$sql_h1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='M';";
			


			$sql_h2="SELECT SUM(Num_Hombres) AS num FROM estancia_gr est, provincia pro, ccaa cc, grupo gr WHERE((est.Fecha_Llegada <= '".$fecha_ini."' AND  est.Fecha_Salida >= '".$fecha_fin."')or  (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Salida <= '".$fecha_fin."')or (est.Fecha_Llegada >= '".$fecha_ini."' AND  est.Fecha_Llegada <= '".$fecha_fin."' and est.Fecha_Salida >= '".$fecha_fin."'))AND gr.Nombre_Gr = est.Nombre_Gr AND gr.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."';";
			
			
			//MUJERES
			$sql_m="SELECT count(cl.DNI_Cl ) AS num FROM pernocta p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F';";
										
			$sql_m1="SELECT count( cl.DNI_Cl ) AS num FROM pernocta_p p, provincia pro, ccaa cc, cliente cl WHERE  ((p.Fecha_Llegada <= '".$fecha_ini."' AND  p.Fecha_Salida >= '".$fecha_fin."')or  (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Salida <= '".$fecha_fin."')or (p.Fecha_Llegada >= '".$fecha_ini."' AND  p.Fecha_Llegada <= '".$fecha_fin."' and p.Fecha_Salida >= '".$fecha_fin."')) AND cl.DNI_Cl = p.DNI_Cl AND cl.Id_pro = pro.Id_Pro AND pro.Id_CCAA = cc.Id_CCAA AND cc.Nombre_CCAA ='".$CCAA[$y]."' and cl.Sexo_Cl='F';";
										

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
			

		}else if($_SESSION['tipo_cliente']==2){//Alberguista
			$result_h=mysql_query($sql_h);								
			$filah=mysql_fetch_array($result_h);
			$result_h2=mysql_query($sql_h2);								
			$filah2=mysql_fetch_array($result_h2);
			$h= $filah['num']+$filah2['num'];	
						
			//Mujeres
			$result_m=mysql_query($sql_m);								
			$filam=mysql_fetch_array($result_m);
			$result_m2=mysql_query($sql_m2);								
			$filam2=mysql_fetch_array($result_m2);
			$m=$m+ $filam['num']+ $filam2['num'];
										
		}else if($_SESSION['tipo_cliente']==3){//Peregrino
			$result_h1=mysql_query($sql_h1);								
			$filah1=mysql_fetch_array($result_h1);
			$h= $filah1['num'];

			//mujeres
			$result_m1=mysql_query($sql_m1);								
			$filam1=mysql_fetch_array($result_m1);
			$m=$m+ $filam1['num'];
			
		}
		$hombres[$y]=$h;	
		$mujeres[$y]=$m;	
		$total_comu[$y]=$hombres[$y]+$mujeres[$y];	
		$totales_personas[$y]=$total_comu[$y];//Guardo en el array la suma de hombres y mujeres
		$total_h_global=$total_h_global+$hombres[$y];//Total hombres pa esa ccaa
		$total_m_global=$total_m_global+$mujeres[$y];//Total mujeres pa esa ccaa
		$total_todos_global=$total_todos_global+$total_comu[$y];?>		
<?}//Cierre for de paises
		
								
			
	if($m_ini<10){
			$m_ini1=substr($m_ini,-1);
	}else{
			$m_ini1=substr($m_ini,-2);
	}
 
	//Construimos la fecha del titulo
	$meses=array(" ", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	if($_SESSION['periodicidad']==1){
			$fecha=date("d")."-".date("m")."-".date("Y");
	}else if($_SESSION['periodicidad']==2){						
			$fecha= $meses[$m_ini1]." ".$a_ini;
	}else if($_SESSION['periodicidad']==3 || ($_SESSION['periodicidad']==4 )){
							
			if($_SESSION['periodicidad']==3){
								if($m_ini<=10){
									$m_fin=$m_ini+2;										
									$a_fin=$a_ini;
								}else{
									$m_fin='0'.substr($m_ini,-1);											
									$a_fin=$a_ini+1;
								}
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
								
								
								$fecha=" ".$meses[$m_ini1]." - ".$meses[$m_fin1]." ".$a_fin;

							}else{
								if($m_ini<=7){
									$m_fin=$m_ini+5;										
									$a_fin=$a_ini;
													
								}else{
									$m_fin=$m_ini+3;
									$m_fin='0'.substr($m_fin,-1);										
									$a_fin=$a_ini+1;
								}
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
								$fecha= " ".$meses[$m_ini1]."-".$meses[$m_fin1]." ".$a_fin;
							}
						
					}else if($_SESSION['periodicidad']==5){
						$fecha==$a_ini;
					}	
				
	?>
	<table width="450px"  style="font-size:14px;" border="1" bordercolor="#000000" >
	<tr> 
		<td>
			
			<table width="400px" border="1" align="center" cellspacing="0" bordercolor="white">
				<tr align="center"> 
					<td colspan="5"><strong><font size="4">INFORME POR CCAA
					<?if($_SESSION['tipo_cliente']==2){
						echo " ("."Alberguistas".")";
					}else if ($_SESSION['tipo_cliente']==3){
						echo " ("."Peregrinos".")";
					}?>
					
					</font></strong><br><center><strong><font size="4"><?echo $fecha;?></font></strong></center><br></td>
				</tr>
				<tr> 
					<td width='40%'>&nbsp;</td>
					<td width="15%" align="center"><strong>H</strong></td>
					<td width="15%" align="center"><strong>M</strong></td>
					<td width="15%" align="center"><strong>%</strong></td>
					<td width="15%" align="center"><strong>Total</strong></td>
				</tr>

			<?for($y=0;$y<sizeof($CCAA);$y++){?>
			<tr>

				<td class='rb'align='left' width='25%'><strong><? echo $CCAA[$y] ?></strong></td>				
				<td class='rb' align='center'>	<? echo $hombres[$y] ?>	</td>				
				<td class='rb' align='center'>	<? echo $mujeres[$y] ?>	</td>				
				<td class='rb' align='right'>
						<input type='text' id='porcent<?echo $y?>' name='por' style='border:0;align:center;' size='3' value=''>
				</td>
				<td class='rb'>
				<?echo $total_comu[$y];?>					
				</td>
			</tr>
		<?}?>
					
			
			<br>
			<? for($b=0;$b<$y;$b++){
				$suma_totales=$suma_totales+$totales_personas[$b];
			}
			 for($b=0;$b<$y;$b++){?>
				<script>Escribir(<?echo $b?>,<?echo $totales_personas[$b]?>,<?echo $suma_totales?>);</script>
			<?}?>
			
				<tr>
					<td class='rt' width="40%" align="left"><strong>TOTALES</strong></td>
					<td class='rt' width="15%" align="center"><strong><?echo $total_h_global?></strong></td>
					<td class='rt' width="15%" align="center"><strong><?echo $total_m_global?></strong></td>
					<?if ($total_todos_global>0){ ?>
						<td class='rt' width="15%" align="center"><strong>100</strong></td>
					<?}else{?>
						<td class='rt' width="15%" align="center"><strong>0</strong></td>
					<?}?>
					<td class='rt' width="15%" align="center"><strong><?echo $total_todos_global?></strong></td>
				</tr>
			</table>
		<br>
		</form>
	
</table>


	
<? mysql_close($db); ?>
