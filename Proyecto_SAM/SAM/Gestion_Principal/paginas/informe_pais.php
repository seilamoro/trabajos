<?php
	session_start();
?>
<head>
<style>body{
	font-family: Arial, Helvetica, sans-serif;
}</style>
<link type="text/css" rel="stylesheet" href="./css/estadisticas.css">
</head>



<?PHP
$mes=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
if($_SESSION['periodicidad']=="1"){
  $fecha=substr(date(c),0,-15);
  $titulo=$fecha;
}

if($_SESSION['periodicidad']=="2"){
  $nombre_mes=$mes[$_SESSION['mes_inicio']-1];
  $titulo=$nombre_mes." ".$_SESSION['anio_inicio'];
}

if($_SESSION['periodicidad']=="3"){
  if($_SESSION['mes_inicio']>=11){
    $_SESSION['anio_inicio']=$_SESSION['anio_inicio']+1;
    $nombre_mes1=$mes[$_SESSION['mes_inicio']-1];
    $nombre_mes2=$mes[substr($_SESSION['mes_inicio'],-1,1)-1];
    $titulo=$nombre_mes1."-"."$nombre_mes2"." ".$_SESSION['anio_inicio'];
  }
  else{
    $nombre_mes1=$mes[$_SESSION['mes_inicio']-1];
    $nombre_mes2=$mes[$_SESSION['mes_inicio']+1];
    $titulo=$nombre_mes1."-"."$nombre_mes2"." ".$_SESSION['anio_inicio'];
  }
}

if($_SESSION['periodicidad']=="4"){
  if($_SESSION['mes_inicio']>="08"){
    $_SESSION['anio_inicio']=$_SESSION['anio_inicio']+1;
    $nombre_mes1=$mes[$_SESSION['mes_inicio']-1];
    $nombre_mes2=$mes[$_SESSION['mes_inicio']-8];
    $titulo=$nombre_mes1."-"."$nombre_mes2"." ".$_SESSION['anio_inicio'];
  }
  else{
    $nombre_mes1=$mes[$_SESSION['mes_inicio']-1];
    $nombre_mes2=$mes[$_SESSION['mes_inicio']+4];
    $titulo=$nombre_mes1."-"."$nombre_mes2"." ".$_SESSION['anio_inicio'];
  }
}

if($_SESSION['periodicidad']=="5"){
  $fecha=substr(date(c),0,-21);
  $titulo=$fecha;
}

if($_SESSION['tipo_cliente']=="2"){
  $coletilla=" (Alberguistas)";
}
elseif($_SESSION['tipo_cliente']=="3"){
  $coletilla=" (Peregrinos)";
}
else{$coletilla="";}

?>


<table width="450px"  style="font-size:14px;" border="1" bordercolor="#000000" >
	<tr>
		<td>
			<table width="400px" border="0" align="center" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td colspan="5"><strong><font size="4">INFORME POR PAÍSES<?PHP echo $coletilla; ?></font></strong></td>
				</tr>

			</table>
			
			<table width="400px" border="1" align="center" bordercolor="white" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td colspan="5" ><strong><font size="4"><?PHP echo $titulo; ?><br><br></font></strong></td>
				</tr>
				<tr>
					<td bordercolor="#FFFFFF" width='40%'>&nbsp;</td>
					<td width="15%" align="center"><strong>H</strong></td>
					<td width="15%" align="center"><strong>M</strong></td>
					<td width="15%" align="center"><strong>%</strong></td>
					<td width="15%" align="center"><strong>Total</strong></td>


				</tr>
				
<?php

$finsql="";
$finsql_gr="";
$finsql_al="";
$finsql_pe="";


//Diario...
		if($_SESSION['periodicidad']=="1")
		{
			$finsql_gr="(estancia_gr.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (estancia_gr.Fecha_Llegada < '".date("Y")."-".date("m")."-".date("d")."' and estancia_gr.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'))";
			$finsql_al="(pernocta.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta.Fecha_Llegada < '".date("Y")."-".date("m")."-".date("d")."' and pernocta.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'))";
    		$finsql_pe="(pernocta_p.Fecha_Llegada ='".date("Y")."-".date("m")."-".date("d")."' or (pernocta_p.Fecha_Llegada < '".date("Y")."-".date("m")."-".date("d")."' and pernocta_p.Fecha_Salida > '".date("Y")."-".date("m")."-".date("d")."'))";
		}

//Mensual...
		elseif($_SESSION['periodicidad']=="2"){


			$finsql_gr="(
						(substring(estancia_gr.Fecha_Llegada,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') or
						((substring(estancia_gr.Fecha_Salida,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and estancia_gr.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
						(substring(estancia_gr.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."')
					)";
					
            		
					
			$finsql_al="(
							(substring(pernocta.Fecha_Llegada,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') or
							((substring(pernocta.Fecha_Salida,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and pernocta.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
                            (substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."')
						)";


			$finsql_pe="(
							(substring(pernocta_p.Fecha_Llegada,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') or
							((substring(pernocta_p.Fecha_Salida,1,7)='".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and pernocta_p.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
							(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."')
						)";
        }
        
//Trimestral...
		elseif($_SESSION['periodicidad']=="3")
		{
			$fech_trim_sem=date("Y-m-d",mktime(0,0,0,$_SESSION['mes_inicio']+2,7,$_SESSION['anio_inicio']));
			$tr_sem=substr($fech_trim_sem,0,7);

				$finsql_gr="(
							(substring(estancia_gr.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."')or
							((substring(estancia_gr.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and estancia_gr.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
							(substring(estancia_gr.Fecha_Llegada,1,7)< '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$tr_sem."')
						)";
				$finsql_al="(
								(substring(pernocta.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or
								((substring(pernocta.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and pernocta.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
								(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$tr_sem."')
							)";
				$finsql_pe="(
								(substring(pernocta_p.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or
								((substring(pernocta_p.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and pernocta_p.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
								(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$tr_sem."')
							)";
		}

//Semestral...
		elseif($_SESSION['periodicidad']=="4")
		{
			$fech_trim_sem=date("Y-m-d",mktime(0,0,0,$_SESSION['mes_inicio']+5,7,$_SESSION['anio_inicio']));
			$tr_sem=substr($fech_trim_sem,0,7);

				$finsql_gr="(
							(substring(estancia_gr.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."')or
							((substring(estancia_gr.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and estancia_gr.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
							(substring(estancia_gr.Fecha_Llegada,1,7)< '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$tr_sem."')
						)";
				$finsql_al="(
								(substring(pernocta.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or
								((substring(pernocta.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."')and pernocta.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
								(substring(pernocta.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta.Fecha_Salida,1,7)> '".$tr_sem."')
							)";
				$finsql_pe="(
								(substring(pernocta_p.Fecha_Llegada,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') or
								((substring(pernocta_p.Fecha_Salida,1,7) between '".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."' and '".$tr_sem."') and pernocta_p.Fecha_Salida <>'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."-01' ) or
								(substring(pernocta_p.Fecha_Llegada,1,7)<'".$_SESSION['anio_inicio']."-".$_SESSION['mes_inicio']."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$tr_sem."')
							)";

		}

//Anual...
		elseif($_SESSION['periodicidad']=="5")
		{
			$finsql_gr="(estancia_gr.Fecha_Llegada between '".$_SESSION['anio_inicio']."-01-01' and '".$_SESSION['anio_inicio']."-12-31' )";
			$finsql_al="(pernocta.Fecha_Llegada between '".$_SESSION['anio_inicio']."-01-01' and '".$_SESSION['anio_inicio']."-12-31' )";
			$finsql_pe="(pernocta_p.Fecha_Llegada between '".$_SESSION['anio_inicio']."-01-01' and '".$_SESSION['anio_inicio']."-12-31' )";
		}
		


		
//*********************************************************//



$paises=mysql_query("SELECT * from pais");

$posicion=0;
$sexoh=0;
$sexom=0;


if($_SESSION['tipo_cliente']=="1"){//Todos
			for ($i=0;$i<mysql_num_rows($paises);$i++)
			{
					$fila = mysql_fetch_array($paises);
                    
					// se inicia otro contador que va a servir para acumular las personas que hay en total en cada pais
					$contar=0;

					//para grupos:
                    $sqlgrupo="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where grupo.Id_Pais='".$fila['Id_Pais']."' and ".$finsql_gr;
					$resultgrupo = mysql_query($sqlgrupo);
					for ($j=0;$j<mysql_num_rows($resultgrupo);$j++)
						{
							$filagrupo = mysql_fetch_array($resultgrupo);
							$contar=$contar+$filagrupo['personas'];
						}

                    //para alberguistas:
                    $sqlalber="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Id_Pais='".$fila['Id_Pais']."' and ".$finsql_al;
                    $resultalber = mysql_query($sqlalber);
					$filaalber = mysql_fetch_array($resultalber);
					$contar=$contar+$filaalber['personas'];
					

					//para peregrinos:
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
}



  
if($_SESSION['tipo_cliente']=="2"){//Alberguistas
      for ($i=0;$i<mysql_num_rows($paises);$i++)
	  {
	    $fila = mysql_fetch_array($paises);
    	$contar=0;
	    $sqlalber="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Id_Pais='".$fila['Id_Pais']."' and ".$finsql_al;
        $resultalber = mysql_query($sqlalber);
    	$filaalber = mysql_fetch_array($resultalber);
   	    $sqlgrupo="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where grupo.Id_Pais='".$fila['Id_Pais']."' and ".$finsql_gr;
		$resultgrupo = mysql_query($sqlgrupo);
		for ($j=0;$j<mysql_num_rows($resultgrupo);$j++)
		{
			$filagrupo = mysql_fetch_array($resultgrupo);
			$contar=$contar+$filagrupo['personas'];
		}

        $contar=$contar+$filaalber['personas'];
        if($contar!=0){
    		$array[$posicion]['pais']=$fila['Id_Pais'];
    		$array[$posicion]['personas']=$contar;
    		$posicion=$posicion+1;
    	}
      }
}
  
  
  

if($_SESSION['tipo_cliente']=="3"){//Peregrinos
        for ($i=0;$i<mysql_num_rows($paises);$i++)
    	{
	    $fila = mysql_fetch_array($paises);
    	$contar=0;
		$sqlpere="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Id_Pais='".$fila['Id_Pais']."' and ".$finsql_pe;
		$resultpere = mysql_query($sqlpere);
		$filapere = mysql_fetch_array($resultpere);
		$contar=$contar+$filapere['personas'];
		if($contar!=0)
		{
    		$array[$posicion]['pais']=$fila['Id_Pais'];
			$array[$posicion]['personas']=$contar;
			$posicion=$posicion+1;
		}
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
		$arraypais[$i]=$array[$i]['pais'];
		$arraypersonas[$i]=$array[$i]['personas'];
	}


$num_hombres=0;
$num_mujeres=0;


for($p=0;$p<count($arraypais);$p++){

	$sql_nombre_pais="select Nombre_Pais,Id_Pais from pais where Id_Pais= '".$arraypais[$p]."'";
	$resul_sql=mysql_query($sql_nombre_pais);
	$res_sql=mysql_num_rows($resul_sql);


    if($res_sql=1){
        $fila_paises=mysql_fetch_array($resul_sql);
            if($_SESSION['tipo_cliente']==1){

                $num_hombres_pere=mysql_query("SELECT count( cliente.sexo_cl ) AS num_hombres, cliente.id_pais FROM cliente INNER JOIN pernocta_p ON pernocta_p.dni_cl = cliente.dni_cl WHERE cliente.id_pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_pe." AND sexo_cl ='M' GROUP BY sexo_cl");
				
                $num_mujeres_pere=mysql_query("SELECT count( cliente.sexo_cl ) AS num_mujeres, cliente.id_pais FROM cliente INNER JOIN pernocta_p ON pernocta_p.dni_cl = cliente.dni_cl WHERE cliente.id_pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_pe." AND sexo_cl='F' GROUP BY sexo_cl");
                $nhombres_per=mysql_fetch_array($num_hombres_pere);				
                $nmujeres_per=mysql_fetch_array($num_mujeres_pere);
				
                $num_hombres_alber=mysql_query("SELECT count( cliente.sexo_cl ) AS num_hombres, cliente.id_pais FROM cliente INNER JOIN pernocta ON cliente.DNI_Cl = pernocta.DNI_Cl WHERE cliente.Id_Pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_al." and sexo_cl='M' GROUP BY sexo_cl");
                $num_mujeres_alber=mysql_query("SELECT count( cliente.sexo_cl ) AS num_mujeres, cliente.id_pais FROM cliente INNER JOIN pernocta ON cliente.DNI_Cl = pernocta.DNI_Cl WHERE cliente.Id_Pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_al." and sexo_cl='F' GROUP BY sexo_cl");
				
                 $num_grupo_h=mysql_query("SELECT sum(num_hombres)as  num_hombres FROM estancia_gr, grupo,pais WHERE id_pais_nacionalidad = '".$fila_paises['Id_Pais']."' and grupo.Nombre_Gr=estancia_gr.Nombre_Gr and grupo.Id_Pais=pais.Id_pais and ".$finsql_gr."");
				 $num_grupo_m=mysql_query("SELECT sum(num_mujeres)as  num_mujeres FROM estancia_gr, grupo,pais WHERE id_pais_nacionalidad = '".$fila_paises['Id_Pais']."' and grupo.Nombre_Gr=estancia_gr.Nombre_Gr and grupo.Id_Pais=pais.Id_pais and ".$finsql_gr."");
				
                
                $nhombres_alb=mysql_fetch_array($num_hombres_alber);
                $nmujeres_alb=mysql_fetch_array($num_mujeres_alber);
			
				$grupo_h=mysql_fetch_array($num_grupo_h);
				$grupo_m=mysql_fetch_array($num_grupo_m);		
               
                
                $sexoh=($nhombres_alb['num_hombres']+$grupo_h['num_hombres']+$nhombres_per['num_hombres']);
                $sexom=($nmujeres_alb['num_mujeres']+$grupo_m['num_mujeres']+$nmujeres_per['num_mujeres']);
				

            }
            if($_SESSION['tipo_cliente']==3){
                $num_hombres_pere=mysql_query("SELECT count( cliente.sexo_cl ) AS num_hombres, cliente.id_pais FROM cliente INNER JOIN pernocta_p ON pernocta_p.dni_cl = cliente.dni_cl WHERE cliente.id_pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_pe." AND sexo_cl = 'M' GROUP BY sexo_cl");
                $num_mujeres_pere=mysql_query("SELECT count( cliente.sexo_cl ) AS num_mujeres, cliente.id_pais FROM cliente INNER JOIN pernocta_p ON pernocta_p.dni_cl = cliente.dni_cl WHERE cliente.id_pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_pe." AND sexo_cl = 'F' GROUP BY sexo_cl");
                $nhombres_per=mysql_fetch_array($num_hombres_pere);
                $nmujeres_per=mysql_fetch_array($num_mujeres_pere);
                $sexoh=$nhombres_per['num_hombres'];
                $sexom=$nmujeres_per['num_mujeres'];
            }
            if($_SESSION['tipo_cliente']==2){
                $num_hombres_alber=mysql_query("SELECT count( cliente.sexo_cl ) AS num_hombres, cliente.id_pais FROM cliente INNER JOIN pernocta ON cliente.DNI_Cl = pernocta.DNI_Cl WHERE cliente.Id_Pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_al." and sexo_cl='M' GROUP BY sexo_cl");
                $num_mujeres_alber=mysql_query("SELECT count( cliente.sexo_cl ) AS num_mujeres, cliente.id_pais FROM cliente INNER JOIN pernocta ON cliente.DNI_Cl = pernocta.DNI_Cl WHERE cliente.Id_Pais = '".$fila_paises['Id_Pais']."' AND ".$finsql_al." and sexo_cl='F' GROUP BY sexo_cl");
                $num_grupo_h=mysql_query("SELECT sum(num_hombres)as  num_hombres FROM estancia_gr, grupo,pais WHERE id_pais_nacionalidad = '".$fila_paises['Id_Pais']."' and grupo.Nombre_Gr=estancia_gr.Nombre_Gr and grupo.Id_Pais=pais.Id_pais and ".$finsql_gr."");
				 $num_grupo_m=mysql_query("SELECT sum(num_mujeres)as  num_mujeres FROM estancia_gr, grupo,pais WHERE id_pais_nacionalidad = '".$fila_paises['Id_Pais']."' and grupo.Nombre_Gr=estancia_gr.Nombre_Gr and grupo.Id_Pais=pais.Id_pais and ".$finsql_gr."");
				
				$nhombres_alb=mysql_fetch_array($num_hombres_alber);
                $nmujeres_alb=mysql_fetch_array($num_mujeres_alber);				
				$grupo_h=mysql_fetch_array($num_grupo_h);
				$grupo_m=mysql_fetch_array($num_grupo_m);		
                $sexoh=($nhombres_alb['num_hombres']+$grupo_h['num_hombres']);
                $sexom=($nmujeres_alb['num_mujeres']+$grupo_m['num_mujeres']);
            }

			
            if(!$sexom ){$sexom=0;}
            if(!$sexoh ){$sexoh=0;}

            
     }
    
	$arraypersonas[$p]=$sexom +$sexoh;
    $tanto_porciento=round(((100*$arraypersonas[$p])/$personastotal),1);
             echo"
            	<tr>
			     <td class='rb' align='center' width='25%'><strong>".$fila_paises['Nombre_Pais']."</strong></td>
                 <td class='rb' align='center'>".$sexoh."</td>
				 <td class='rb' align='center'>".$sexom."</td>
				 <td class='rb' align='center'>".$tanto_porciento."</td>
				 <td class='rb' align='center'>".$arraypersonas[$p]."</td>
    </tr>";
    
    $num_hombres+=$sexoh;
    $num_mujeres+=$sexom;
    $p_total1+=$tanto_porciento;


}//Fin for
$personastotal=$num_hombres+$num_mujeres;
if(!$p_total1){$p_total=0;}
else{$p_total=round($p_total1);}

?>
					

	
				<tr cellspacing='0' cellpadding='0'>
					<td class='rt' width="40%" align="center"><strong>TOTALES</strong></td>
					<td class='rt' width="15%" align="center"><strong><?PHP echo $num_hombres; ?></strong></td>
					<td class='rt' width="15%" align="center"><strong><?PHP echo $num_mujeres; ?></strong></td>
					<td class='rt' width="15%" align="center"><strong><?PHP echo $p_total; ?></strong></td>
					<td class='rt' width="15%" align="center"><strong><?PHP echo $personastotal; ?></strong></td>
                   
				</tr>
			</table>
			
  <br>
		</form>
	
</table>
<?php mysql_close($db); ?>
</body>
