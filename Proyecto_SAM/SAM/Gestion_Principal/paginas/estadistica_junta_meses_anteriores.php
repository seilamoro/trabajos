<?php
/*
A PARTIR DE AQUI ES DONDE SE HACEN LAS SELECT PARA RELLENAR LOS MESES ANTERIORES
*/

//$finsql_pe_ant="";

$finsql_gr_ant=" ";
$finsql_al_ant="";

	/* aqui se construyen dos partes de las consultas que son las referentes a las fechas sobre las que se van a hacer 
	todas las consultas a la base de datos para sacar la información pertinente*/
	if($mes==01)
	{
	$finsql_gr_ant="(
		((substring(estancia_gr.Fecha_Llegada,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."') or 
		(substring(estancia_gr.Fecha_Salida,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."')) and
		((substring(estancia_gr.Fecha_Llegada,1,7) < '".$anio."-".$mes."') or 
		(substring(estancia_gr.Fecha_Salida,1,7) < '".$anio."-".$mes."'))
		);";
	
	$finsql_al_ant="(
		((substring(pernocta.Fecha_Llegada,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."') or 
		(substring(pernocta.Fecha_Salida,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."')) and
		((substring(pernocta.Fecha_Llegada,1,7) < '".$anio."-".$m_anterior."') or 
		(substring(pernocta.Fecha_Salida,1,7) < '".$anio."-".$m_anterior."'))
		);";
	}
	else
	{
	$finsql_gr_ant="(
		(substring(estancia_gr.Fecha_Llegada,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."') or 
		(substring(estancia_gr.Fecha_Salida,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."')
		);";
	
	$finsql_al_ant="(
		(substring(pernocta.Fecha_Llegada,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."') or 
		(substring(pernocta.Fecha_Salida,1,7) BETWEEN '".$anio."-01' AND '".$anio."-".$m_anterior."')
		);";		
	}

/*$finsql_pe_ant="(
(substring(pernocta_p.Fecha_Llegada,1,7)< '".$anio."-".$mes."') or 
(substring(pernocta_p.Fecha_Salida,1,7)< '".$anio."-".$mes."') or 
(substring(pernocta_p.Fecha_Llegada,1,7)< '".$anio."-".$mes."') and (substring(pernocta_p.Fecha_Salida,1,7)= '".$anio."-".$mes."')
);";*/


//------------------------------------------------PARA SACAR EL NUMERO DE PERNOCTAS QUE SE HAN PRODUCIDO A LO LARGO DEL MES------------
//$pernoctas_pere_ant=0;

$pernoctas_alber_ant=0;
$pernoctas_mes_gru_ant=0;
$pernoctas_grupo_ant=0;


// CON ESTO SE SACA LA INFORMACION PARA SABER EL NUMERO DE PERNOCTAS QU SE HAN HECHO A LO LARGO DEL MES SELECCIONADO


	/* para grupos: se hace una consulta a la base de datos para sacar el número componentes del grupo asi como la fecha de llegada y de 
	salida de dicho grupo de los grupos que hayan pernoctado en el albergue en los meses anteriores del que se requiere la información*/ 

$pernoctas_gru_ant="select estancia_gr.Num_Personas,estancia_gr.Nombre_Gr,estancia_gr.Fecha_Llegada,estancia_gr.Fecha_Salida  from estancia_gr,grupo where estancia_gr.Nombre_Gr=grupo.Nombre_Gr and ".$finsql_gr_ant;

	$res_pernoctas_gru_ant=mysql_query($pernoctas_gru_ant);
	
	$n_res_pernoctas_gru_ant=mysql_num_rows($res_pernoctas_gru_ant);
	
		for ($i=0;$i < $n_res_pernoctas_gru_ant; $i++)
			{
				$fila_res_gru_ant=mysql_fetch_array($res_pernoctas_gru_ant);
				
				// se saca el número de pernoctas del grupo en total
				$dias_pernoctados_grupo_mes_ant=pernoctas_meses_anteriores($fila_res_gru_ant['Fecha_Llegada'],$fila_res_gru_ant['Fecha_Salida'],$mes,$anio);
				
				// se cogen el número de pernoctas que el grupo ha pernoctado dentro de los meses anteriores al seleccionado
				$pernoctas_grupo_ant=$dias_pernoctados_grupo_mes_ant*($fila_res_gru_ant['Num_Personas']);
				
				// se saca el total de pernoctas de cada grupo 
				$pernoctas_mes_gru_ant=$pernoctas_mes_gru_ant+$pernoctas_grupo_ant;
			}
			

/* para peregrinos

$pernoctas_pe_ant="select cliente.DNI_Cl,pernocta_p.Fecha_Llegada,pernocta_p.Fecha_Salida  from cliente,pernocta_p where cliente.DNI_Cl=pernocta_p.DNI_Cl and (substring(pernocta_p.Fecha_Llegada,1,7)= '".$anio."-".$mes."' or substring(pernocta_p.Fecha_Salida,1,4)='".$anio."');";

$res_pernoctas_pe_ant=mysql_query($pernoctas_pe_ant);

$n_res_pernoctas_pe_ant=mysql_num_rows($res_pernoctas_pe_ant);

for ($i=0;$i < $n_res_pernoctas_pe_ant; $i++)
{
$fila_res_pe_ant=mysql_fetch_array($res_pernoctas_pe_ant);

$dias_pernoctados_pere_mes_ant=pernoctas_meses_anteriores($fila_res_pe_ant['Fecha_Llegada'],$fila_res_pe_ant['Fecha_Salida'],$mes,$anio);

$pernoctas_mes_pere_ant=$pernoctas_mes_pere_ant+$dias_pernoctados_pere_mes_ant;
}
*/

	/* para alberguistas: se hace una consulta a la base de datos en la cual se saca alberguista que ha pernoctado asi como su fecha de llegada
	y de salida del albergue  en los meses anteriores al mes requerido*/

	$pernoctas_al_ant="select cliente.DNI_Cl,pernocta.Fecha_Llegada,pernocta.Fecha_Salida  from cliente,pernocta where cliente.DNI_Cl=pernocta.DNI_Cl and ".$finsql_al_ant;
	
		$res_pernoctas_al_ant=mysql_query($pernoctas_al_ant);
		
		$n_res_pernoctas_al_ant=mysql_num_rows($res_pernoctas_al_ant);
		
			for ($i=0;$i < $n_res_pernoctas_al_ant; $i++)
			{
				$fila_res_al_ant=mysql_fetch_array($res_pernoctas_al_ant);
				
				// se saca el número de pernoctas del alberguista en total en los meses anteriores al seleccionado
				$dias_pernoctados_alber_mes_ant=pernoctas_meses_anteriores($fila_res_al_ant['Fecha_Llegada'],$fila_res_al_ant['Fecha_Salida'],$mes,$anio);
				
				
				$pernoctas_mes_alber_ant=$pernoctas_mes_alber_ant+$dias_pernoctados_alber_mes_ant;
			}
// es el total de pernoctas de todos los usuarios del albergue en los meses anteriores
$pernoctas_usuarios_mes_ant=$pernoctas_mes_gru_ant+$pernoctas_mes_alber_ant;
	

//---------------------------------------------FINAL DE LAS PERNOCTAS---------





// INFORMACION DEL NÚMERO DE USUARIOS DEL MES

	/*para grupos: se hace una consulta a la base de datos donde se sacan los grupos  que han pasado por el 
	alberque en los meses anteriores al seleccionado asi como el número de integrantes del mismo para conocer el número de usuarios 
	de los grupos*/

	$sqlgrupo_usuarios_ant="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where ".$finsql_gr_ant;
	
		$resultgrupo_usuarios_ant = mysql_query($sqlgrupo_usuarios_ant);
		
			for ($j=0;$j<mysql_num_rows($resultgrupo_usuarios_ant);$j++)
				{
					$filagrupo_usuarios_ant = mysql_fetch_array($resultgrupo_usuarios_ant);
					
					$contar_usuarios_ant=$contar_usuarios_ant+$filagrupo_usuarios_ant['personas'];
				}

	/*para alberguistas: se hace una consulta a la base de datos donde se quiere saber cuantos alberguistas han pernoctado en el 
	albergue en los meses anteriores al seleccionado*/
	
	$sqlalber_usuarios_ant="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where ".$finsql_al_ant;
	
		$resultalber_usuarios_ant = mysql_query($sqlalber_usuarios_ant);
		
		$filaalber_usuarios_ant = mysql_fetch_array($resultalber_usuarios_ant);
		
		$contar_usuarios_ant=$contar_usuarios_ant+$filaalber_usuarios_ant['personas'];

/*para peregrinos
$sqlpere_usuarios_ant="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where ".$finsql_pe_ant;

$resultpere_usuarios_ant = mysql_query($sqlpere_usuarios_ant);

$filapere_usuarios_ant = mysql_fetch_array($resultpere_usuarios_ant);

$contar_usuarios_ant=$contar_usuarios_ant+$filapere_usuarios_ant['personas'];*/



//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE HOMBRES

	/*para grupos: aqui se saca el número de hombres que formando parte de un grupo, han pernoctado en el albergue en loa meses anteriores
	al seleccionado*/

	$sqlgrupo_hombres_ant="SELECT (estancia_gr.Num_Hombres) as n_hombres  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where ".$finsql_gr_ant;
	
		$resultgrupo_hombres_ant = mysql_query($sqlgrupo_hombres_ant);
		
			for ($j=0;$j<mysql_num_rows($resultgrupo_hombres_ant);$j++)
				{
					$filagrupo_hombres_ant = mysql_fetch_array($resultgrupo_hombres_ant);
					
					$contar_hombres_ant=$contar_hombres_ant+$filagrupo_hombres_ant['n_hombres'];
				}

	//para alberguistas: se saca el número de alberguistas hombres que han pernoctado en el albergue en los meses anteriores al seleccionado

	$sqlalber_hombres_ant="SELECT count(cliente.DNI_Cl) as n_hombres FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Sexo_Cl='M' and ".$finsql_al_ant;
	
		$resultalber_hombres_ant = mysql_query($sqlalber_hombres_ant);
		
		$filaalber_hombres_ant = mysql_fetch_array($resultalber_hombres_ant);
		
		$contar_hombres_ant=$contar_hombres_ant+$filaalber_hombres_ant['n_hombres'];

/*para peregrinos
$sqlpere_hombres_ant="SELECT count(cliente.DNI_Cl) as n_hombres FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Sexo_Cl='M' and ".$finsql_pe_ant;

$resultpere_hombres_ant = mysql_query($sqlpere_hombres_ant);

$filapere_hombres_ant = mysql_fetch_array($resultpere_hombres_ant);

$contar_hombres_ant=$contar_hombres_ant+$filapere_hombres_ant['n_hombres'];*/

//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE MUJERES

/*para grupos: aqui se saca el número de mujeres que formando parte de un grupo, han pernoctado en el albergue en loa meses anteriores
	al seleccionado*/

	$sqlgrupo_mujeres_ant="SELECT (estancia_gr.Num_Mujeres) as n_mujeres  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where ".$finsql_gr_ant;
	
		$resultgrupo_mujeres_ant = mysql_query($sqlgrupo_mujeres_ant);
		
			for ($j=0;$j<mysql_num_rows($resultgrupo_mujeres_ant);$j++)
				{
					$filagrupo_mujeres_ant = mysql_fetch_array($resultgrupo_mujeres_ant);
					
					$contar_mujeres_ant=$contar_mujeres_ant+$filagrupo_mujeres_ant['n_mujeres'];
				}

	//para alberguistas: se saca el número de alberguistas mujeres que han pernoctado en el albergue en los meses anteriores al seleccionado

	$sqlalber_mujeres_ant="SELECT count(cliente.DNI_Cl) as n_mujeres FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Sexo_Cl='F' and ".$finsql_al_ant;
	
		$resultalber_mujeres_ant = mysql_query($sqlalber_mujeres_ant);
		
		$filaalber_mujeres_ant = mysql_fetch_array($resultalber_mujeres_ant);
		
		$contar_mujeres_ant=$contar_mujeres_ant+$filaalber_mujeres_ant['n_mujeres'];

/*para peregrinos
$sqlpere_mujeres_ant="SELECT count(cliente.DNI_Cl) as n_mujeres FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Sexo_Cl='F' and ".$finsql_pe_ant;

$resultpere_mujeres_ant = mysql_query($sqlpere_mujeres_ant);

$filapere_mujeres_ant = mysql_fetch_array($resultpere_mujeres_ant);

$contar_mujeres_ant=$contar_mujeres_ant+$filapere_mujeres_ant['n_mujeres'];*/

//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE MENORES DE 30 AÑOS

	/*para grupo: se hace una consulta a la base de datos en la cual de los grupos que han pernoctado en el alberqueen los meses anteriores
	al seleccionado, se cogen el número de personas cuya edad es inferior a 30 años de edad*/

$con_menores_gr_ant=0;

	$sql_menor_gr_ant="SELECT HGr0_9,HGr10_19,HGr20_25,HGr26_29,FGr0_9,FGr10_19,FGr20_25,FGr26_29 FROM estancia_gr inner join grupo on estancia_gr.Nombre_Gr=grupo.nombre_gr where ".$finsql_gr_ant;
	
		$resgru_ant = mysql_query($sql_menor_gr_ant);
		
			for ($j=0;$j<mysql_num_rows($resgru_ant);$j++)
				{
					$filagru_ant = mysql_fetch_array($resgru_ant);
					
					$con_menores_gr_ant=$con_menores_gr_ant+($filagru['HGr0_9']+$filagru['HGr10_19']+$filagru['HGr20_25']+$filagru['HGr26_29']+$filagru['FGr0_9']+$filagru['FGr10_19']+$filagru['FGr20_25']+$filagru['FGr26_29']);
				}

	/*para alberguistas: se hace una constulta a la base de datos mirando la edad de los alberguistas que han pernoctado en el albergue 
	en los meses anteriores al seleccionado y se cogen aquellos cuya edad no supere los 30 años de edad y se suman y tambien los de 30 
	años o mas y estos se suman en otra variable*/

$con_menores_al_ant=0;
$con_mayores_al_ant=0;

	$sql_menores_al_ant="SELECT Fecha_Nacimiento_Cl,Fecha_Llegada FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where ".$finsql_al_ant;
	
		$res_al_ant = mysql_query($sql_menores_al_ant);
		
			for ($j=0;$j<mysql_num_rows($res_al_ant);$j++)
				{
					$fila_al_ant = mysql_fetch_array($res_al_ant);
					$comprobar_ant=edad ( $fila_al_ant['Fecha_Nacimiento_Cl'],$fila_al_ant['Fecha_Llegada'] ); 
					
						if($comprobar_ant < 30)
							{
								$con_menores_al_ant=$con_menores_al_ant+1;
							}
							else
								{
									$con_mayores_al_ant=$con_mayores_al_ant+1;
								}
				}

/*para peregrinos

$con_menores_pe_ant=0;
$con_mayores_pe_ant=0;
$sql_menores_pe_ant="SELECT Fecha_Nacimiento_Cl,Fecha_Llegada FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where ".$finsql_pe_ant;

$res_pe_ant = mysql_query($sql_menores_pe_ant);

for ($j=0;$j<mysql_num_rows($res_pe_ant);$j++)
{
$fila_pe_ant = mysql_fetch_array($res_pe_ant);
$comprobar_ant=edad ( $fila_pe_ant['Fecha_Nacimiento_Cl'],$fila_pe_ant['Fecha_Llegada'] ); 
if($comprobar_ant < 30)
{
$con_menores_pe_ant=$con_menores_pe_ant+1;
}
else
{
$con_mayores_pe_ant=$con_mayores_pe_ant+1;
}
}*/


//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE MAYORES DE 30 AÑOS

	/*para grupo: se hace una consulta a la base de datos en la cual de los grupos que han pernoctado en el alberqueen el los meses anteriores
	al seleccionado, se cogen el número de personas cuya edad es mayor o igual a 30 años de edad*/

$con_mayores_gr_ant=0;

	$sql_mayor_gr_ant="SELECT HGr30_39,HGr40_49,HGr50_59,HGr60_69,HGrOtras,FGr30_39,FGr40_49,FGr50_59,FGr60_69,FGrOtras FROM estancia_gr inner join grupo on estancia_gr.Nombre_Gr=grupo.nombre_gr where ".$finsql_gr_ant;
	
		$resgru_ant = mysql_query($sql_mayor_gr_ant);
		
			for ($j=0;$j<mysql_num_rows($resgru_ant);$j++)
				{
					$filagru_ant = mysql_fetch_array($resgru_ant);
					
					$con_mayores_gr_ant=$con_mayores_gr_ant+($filagru['HGr30_39']+$filagru['HGr40_49']+$filagru['HGr50_59']+$filagru['HGr60_69']+$filagru['HGrOtras']+$filagru['FGr30_39']+$filagru['FGr40_49']+$filagru['FGr50_59']+$filagru['FGr60_69']+$filagru['FGrOtras']);
				}
	
$con_menores_30_ant=$con_menores_gr_ant+$con_menores_al_ant;		
$con_mayores_30_ant=$con_mayores_gr_ant+$con_mayores_al_ant;


//	PARA SACAR EL NUMERO DE PERSONAS DE CASTILLA Y LEON

	$con_cyl_ant=0;

	/*para grupos: se hace una consulta a la base de datos para conocer los grupos que han pernoctado en el albergue n los meses
	anteriores al seleccionado y si su provincia de procedencia es alguna de las que componen la comunidad autónoma de castilla y león*/

	$sqlgrupo_cyl_ant="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where (grupo.Id_Pais='ES' and 
	(grupo.Id_Pro ='LE' or grupo.Id_Pro ='ZA' or grupo.Id_Pro ='SA' or grupo.Id_Pro ='VA' or grupo.Id_Pro ='BU' or grupo.Id_Pro ='SO' or grupo.Id_Pro ='SG' or grupo.Id_Pro ='AV' or grupo.Id_Pro ='PA')) and ".$finsql_gr_ant;
	
		$resgru_cyl_ant =mysql_query($sqlgrupo_cyl_ant);
		
			for ($j=0;$j< mysql_num_rows($resgru_cyl_ant);$j++)
				{
					$filgru_cyl_ant = mysql_fetch_array($resgru_cyl_ant);
					
					$con_cyl_ant=$con_cyl_ant+$filgru_cyl_ant['personas'];
				}

	/*para alberguistas: se hace una consulta a la base de datos para conocer los alberguistas que han pernoctado en el albergue en los meses
	anteriores al seleccionado y si su provincia de procedencia es alguna de las que componen la comunidad autónoma de castilla y león*/

	$sqlalber_cyl_ant="select count(cliente.DNI_Cl) as personas from cliente,pernocta where cliente.DNI_Cl =pernocta.DNI_Cl and (cliente.Id_Pais='ES' and 
	(cliente.Id_Pro ='LE' OR cliente.Id_Pro ='ZA' OR cliente.Id_Pro ='SA' OR cliente.Id_Pro ='VA' OR cliente.Id_Pro ='PA' OR cliente.Id_Pro ='BU' OR cliente.Id_Pro ='SO' OR cliente.Id_Pro ='SG' OR cliente.Id_Pro ='AV')) and ".$finsql_al_ant;
		
		$resalb_cyl_ant = mysql_query($sqlalber_cyl_ant);
		
		$filalb_cyl_ant = mysql_fetch_array($resalb_cyl_ant);
		
		$con_cyl_ant=$con_cyl_ant+$filalb_cyl_ant['personas'];

/*para peregrinos

$sqlpere_cyl_ant="select count(cliente.DNI_Cl) as personas from cliente,pernocta_p where cliente.DNI_Cl =pernocta_p.DNI_Cl and (cliente.Id_Pais='ES' and 
(cliente.Id_Pro ='LE' OR cliente.Id_Pro ='ZA' OR cliente.Id_Pro ='SA' OR cliente.Id_Pro ='VA' OR cliente.Id_Pro ='PA' OR cliente.Id_Pro ='BU' OR cliente.Id_Pro ='SO' OR cliente.Id_Pro ='SG' OR cliente.Id_Pro ='AV')) and ".$finsql_pe_ant;

$respe_cyl_ant = mysql_query($sqlpere_cyl_ant);

$filper_cyl_ant= mysql_fetch_array($respe_cyl_ant);

$con_cyl_ant=$con_cyl_ant+$filper_cyl_ant['personas'];*/

//PARA SACAR EL NUMERO DE PERSONAS DE OTRAS COMUNIDADES AUTONOMAS

	$con_nocyl_ant=0;

	/*para grupos: se hace una consulta a la base de datos para conocer los grupos que han pernoctado en el albergue en los meses anteriores
	al seleccionado y su provincia de procedencia no es alguna de las que componen la comunidad autónoma de castilla y león*/
	
	$sqlgrupo_nocyl_ant="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where (grupo.Id_Pais='ES' and 
	(grupo.Id_Pro !='LE' AND grupo.Id_Pro !='ZA' AND grupo.Id_Pro !='SA' AND grupo.Id_Pro !='VA' AND grupo.Id_Pro !='BU' AND grupo.Id_Pro !='SO' AND grupo.Id_Pro !='SG' AND grupo.Id_Pro !='AV' AND grupo.Id_Pro !='PA')) and ".$finsql_gr_ant;
	
		$resgru_nocyl_ant = mysql_query($sqlgrupo_nocyl_ant);
		for ($j=0;$j<mysql_num_rows($resgru_nocyl_ant);$j++)
		{
			$filgru_nocyl_ant = mysql_fetch_array($resgru_nocyl_ant);
			
			$con_nocyl_ant=$con_nocyl_ant+$filgru_nocyl_ant['personas'];
		}

	/*para alberguistas: se hace una consulta a la base de datos para conocer los alberguistas que han pernoctado en el albergue en 
	los meses anteriores al seleccionado y su provincia no de procedencia es alguna de las que componen la comunidad autónoma de
	castilla y león*/

	$sqlalber_nocyl_ant="select count(cliente.DNI_Cl) as personas from cliente,pernocta where cliente.DNI_Cl =pernocta.DNI_Cl and (cliente.Id_Pais='ES' and 
	(cliente.Id_Pro !='LE' AND cliente.Id_Pro !='ZA' AND cliente.Id_Pro !='SA' AND cliente.Id_Pro !='VA' AND cliente.Id_Pro !='PA' AND cliente.Id_Pro !='BU' AND cliente.Id_Pro !='SO' AND cliente.Id_Pro !='SG' AND cliente.Id_Pro !='AV')) and ".$finsql_al_ant;
	
		$resalb_nocyl_ant = mysql_query($sqlalber_nocyl_ant);
		
		$filalb_nocyl_ant = mysql_fetch_array($resalb_nocyl_ant);
		
		$con_nocyl_ant=$con_nocyl_ant+$filalb_nocyl_ant['personas'];

/*para peregrinos

$sqlpere_nocyl_ant="select count(cliente.DNI_Cl) as personas from cliente,pernocta_p where cliente.DNI_Cl =pernocta_p.DNI_Cl and (cliente.Id_Pais='ES' and 
(cliente.Id_Pro !='LE' AND cliente.Id_Pro !='ZA' AND cliente.Id_Pro !='SA' AND cliente.Id_Pro !='VA' AND cliente.Id_Pro !='PA' AND cliente.Id_Pro !='BU' AND cliente.Id_Pro !='SO' AND cliente.Id_Pro !='SG' AND cliente.Id_Pro !='AV')) and ".$finsql_pe_ant;

$respe_nocyl_ant = mysql_query($sqlpere_nocyl_ant);

$filper_nocyl_ant= mysql_fetch_array($respe_nocyl_ant);

$con_nocyl_ant=$con_nocyl_ant+$filper_nocyl_ant['personas'];*/

//	PARA SACAR EL NUMERO DE PERSONAS EXTRANJERAS

	$contador_extranjeros_ant=0;

	/*para grupos: se hace una consulta a la base de datos para conocer los grupos que han pernoctado en el albergue en los meses anteriores al 
	seleccionado y su pais de procedencia no es España*/
		
	$sqlgrupo_ex_ant="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where grupo.Id_Pais !='ES' and".$finsql_gr_ant;				
	
		$resgrup_ex_ant = mysql_query($sqlgrupo_ex_ant);
		
			for ($j=0;$j<mysql_num_rows($resgrup_ex_ant);$j++)
				{
					$filagrupo_ex_ant = mysql_fetch_array($resgrup_ex_ant);
					
					$contador_extranjeros_ant=$contador_extranjeros_ant+$filagrupo_ex_ant['personas'];
				}

	/*para alberguistas: se hace una consulta a la base de datos para conocer los alberguistas que han pernoctado en el albergue en 
	en los meses anteriores al seleccionado y su pais de procedencia no es España*/

	$sqlalber_ex_ant="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Id_Pais !='ES' and ".$finsql_al_ant;				
	
		$resalb_ex_ant = mysql_query($sqlalber_ex_ant);
		
		$filaalber_ex_ant = mysql_fetch_array($resalb_ex_ant);
		
		$contador_extranjeros_ant=$contador_extranjeros_ant+$filaalber_ex_ant['personas'];

/*para peregrinos
$sqlpere_ex_ant="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Id_Pais !='ES' and ".$finsql_pe_ant;

$resper_ex_ant = mysql_query($sqlpere_ex_ant);

$filapere_ex_ant = mysql_fetch_array($resper_ex_ant);

$contador_extranjeros_ant=$contador_extranjeros_ant+$filapere_ex_ant['personas'];*/				

/*
AQUI SE TERMINA LA OBTENCION DE LOS DATOS DE LOS MESES ANTERIORES 
*/
?>