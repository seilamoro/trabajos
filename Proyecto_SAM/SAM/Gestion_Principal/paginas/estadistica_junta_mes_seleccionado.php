<?php
/*
A PARTIR DE AQUI ES DONDE SE HACEN LAS SELECT PARA RELLENAR LOS CAMPOS DE LA COLUMNA MESUAL
*/

	//$finsql_pe="";
	
	$finsql_gr=" ";
	$finsql_al="";
		
	/* aqui se construyen dos partes de las consultas que son las referentes a las fechas sobre las que se van a hacer 
	todas las consultas a la base de datos para sacar la información pertinente*/
	
		$finsql_gr="(
			(substring(estancia_gr.Fecha_Llegada,1,7)= '".$anio."-".$mes."') or 
			(substring(estancia_gr.Fecha_Salida,1,7)= '".$anio."-".$mes."') or 
			(substring(estancia_gr.Fecha_Llegada,1,7)< '".$anio."-".$mes."') and (substring(estancia_gr.Fecha_Salida,1,7)> '".$anio."-".$mes."')
			);";
	
		$finsql_al="(
			(substring(pernocta.Fecha_Llegada,1,7)='".$anio."-".$mes."') or 
			(substring(pernocta.Fecha_Salida,1,7)='".$anio."-".$mes."') or 
			(substring(pernocta.Fecha_Llegada,1,7)<'".$anio."-".$mes."') and (substring(pernocta.Fecha_Salida,1,7)> '".$anio."-".$mes."')
			);";		
	
	/*$finsql_pe="(
	(substring(pernocta_p.Fecha_Llegada,1,7)='".$anio."-".$mes."') or 
	(substring(pernocta_p.Fecha_Salida,1,7)='".$anio."-".$mes."') or 
	(substring(pernocta_p.Fecha_Llegada,1,7)<'".$anio."-".$mes."') and (substring(pernocta_p.Fecha_Salida,1,7)> '".$anio."-".$mes."')
	);";*/

//------------------------------------------------PARA SACAR EL NUMERO DE PERNOCTAS QUE SE HAN PRODUCIDO A LO LARGO DEL MES------------
//$pernoctas_pere=0;

$pernoctas_alber=0;
$pernoctas_mes_gru=0;
$pernoctas_grupo=0;

// CON ESTO SE SACA LA INFORMACION PARA SABER EL NUMERO DE PERNOCTAS QU SE HAN HECHO A LO LARGO DEL MES SELECCIONADO


	/* para grupos: se hace una consulta a la base de datos para sacar el número componentes del grupo asi como la fecha de llegada y de 
	salida de dicho grupo de los grupos que hayan pernoctado en el albergue en el mes del que se requiere la información tanto en el 
	caso de que la fecha de llegada y de salida este dentro del mes seleccionado o que sea un grupo que ya se encuentre pernoctando en el 
	albergue en el momento del inicio del mes */
	
	$pernoctas_gru="select estancia_gr.Num_Personas,estancia_gr.Nombre_Gr,estancia_gr.Fecha_Llegada,estancia_gr.Fecha_Salida  from estancia_gr,grupo where estancia_gr.Nombre_Gr=grupo.Nombre_Gr and ".$finsql_gr;
	
		$res_pernoctas_gru=mysql_query($pernoctas_gru);
		
		$n_res_pernoctas_gru=mysql_num_rows($res_pernoctas_gru);
		
			for ($i=0;$i < $n_res_pernoctas_gru; $i++)
			{
				$fila_res_gru=mysql_fetch_array($res_pernoctas_gru);
				
				// se saca el número de pernoctas del grupo en total
				$dias_pernoctados_gru=dias_transcurridos($fila_res_gru['Fecha_Llegada'],$fila_res_gru['Fecha_Salida']);
				
				// se cogen el número de pernoctas que el grupo ha pernoctado dentro del mes seleccionado
				$dias_pernoctados_grupo_en_mes=numero_pernoctas($fila_res_gru['Fecha_Llegada'],$fila_res_gru['Fecha_Salida'],$dias_mes_elegido,$dias_pernoctados_gru,$mes);
				
				// se saca el total de pernoctas de cada grupo 
				$pernoctas_grupo=$dias_pernoctados_grupo_en_mes*($fila_res_gru['Num_Personas']);
				
				$pernoctas_mes_gru=$pernoctas_mes_gru+$pernoctas_grupo;
			}

/* para peregrinos: 

$pernoctas_pe="select cliente.DNI_Cl,pernocta_p.Fecha_Llegada,pernocta_p.Fecha_Salida  from cliente,pernocta_p where cliente.DNI_Cl=pernocta_p.DNI_Cl and (substring(pernocta_p.Fecha_Llegada,1,7)= '".$anio."-".$mes."' or substring(pernocta_p.Fecha_Salida,1,7)='".$anio."-".$mes."');";

$res_pernoctas_pe=mysql_query($pernoctas_pe);

$n_res_pernoctas_pe=mysql_num_rows($res_pernoctas_pe);

for ($i=0;$i < $n_res_pernoctas_pe; $i++)
{
$fila_res_pe=mysql_fetch_array($res_pernoctas_pe);

$dias_pernoctados_pere=dias_transcurridos($fila_res_pe['Fecha_Llegada'],$fila_res_pe['Fecha_Salida']);

$dias_pernoctados_pere_en_mes=numero_pernoctas($fila_res_pe['Fecha_Llegada'],$fila_res_pe['Fecha_Salida'],$dias_mes_elegido,$dias_pernoctados_pere,$mes);

$pernoctas_mes_pere=$pernoctas_mes_pere+$dias_pernoctados_pere_en_mes;
}*/

	/* para alberguistas: se hace una consulta a la base de datos en la cual se saca alberguista que ha pernoctado asi como su fecha de llegada
	y de salida del albergue */


	$pernoctas_al="select cliente.DNI_Cl,pernocta.Fecha_Llegada,pernocta.Fecha_Salida  from cliente,pernocta where cliente.DNI_Cl=pernocta.DNI_Cl and ".$finsql_al;
	
		$res_pernoctas_al=mysql_query($pernoctas_al);
		
		$n_res_pernoctas_al=mysql_num_rows($res_pernoctas_al);
		
			for ($i=0;$i < $n_res_pernoctas_al; $i++)
			{
				$fila_res_al=mysql_fetch_array($res_pernoctas_al);
				
				// se saca el número de pernoctas del alberguista en total
				$dias_pernoctados_alber=dias_transcurridos($fila_res_al['Fecha_Llegada'],$fila_res_al['Fecha_Salida']);
				
				// se cogen el número de pernoctas que el alberguista ha pernoctado dentro del mes seleccionado
				$dias_pernoctados_alber_en_mes=numero_pernoctas($fila_res_al['Fecha_Llegada'],$fila_res_al['Fecha_Salida'],$dias_mes_elegido,$dias_pernoctados_alber,$mes);
				
				// se saca el total de pernoctas de cada albeguista
				$pernoctas_mes_alber=$pernoctas_mes_alber+$dias_pernoctados_alber_en_mes;
			}
// es el total de pernoctas de todos los usuarios del albergue en el mes
$pernoctas_usuarios_mes=$pernoctas_mes_gru+$pernoctas_mes_alber;

//---------------------------------------------FINAL DE LAS PERNOCTAS---------



// INFORMACION DEL NÚMERO DE USUARIOS DEL MES

	/*para grupos: se hace una consulta a la base de datos donde se sacan los grupos  que han pasado por el 
	alberque en el mes seleccionado asi como el número de integrantes del mismo para conocer el número de usuarios de los grupos*/


	$sqlgrupo_usuarios="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where ".$finsql_gr;
	
		$resultgrupo_usuarios = mysql_query($sqlgrupo_usuarios);
		
			for ($j=0;$j<mysql_num_rows($resultgrupo_usuarios);$j++)
				{
					$filagrupo_usuarios = mysql_fetch_array($resultgrupo_usuarios);
					
					$contar_usuarios=$contar_usuarios+$filagrupo_usuarios['personas'];
				}

	/*para alberguistas: se hace una consulta a la base de datos donde se quiere saber cuantos alberguistas han pernoctado en el 
	albergue en el mes seleccionado*/

	$sqlalber_usuarios="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where ".$finsql_al;
	
		$resultalber_usuarios = mysql_query($sqlalber_usuarios);
		
		$filaalber_usuarios = mysql_fetch_array($resultalber_usuarios);
		
		$contar_usuarios=$contar_usuarios+$filaalber_usuarios['personas'];

/*para peregrinos
$sqlpere_usuarios="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where ".$finsql_pe;

$resultpere_usuarios = mysql_query($sqlpere_usuarios);

$filapere_usuarios = mysql_fetch_array($resultpere_usuarios);

$contar_usuarios=$contar_usuarios+$filapere_usuarios['personas'];*/


//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE HOMBRES

	//para grupos: aqui se saca el número de hombres que formando parte de un grupo, han pernoctado en el albergue en el mes seleccionado

	$sqlgrupo_hombres="SELECT (estancia_gr.Num_Hombres) as n_hombres  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where ".$finsql_gr;
	
		$resultgrupo_hombres = mysql_query($sqlgrupo_hombres);
			for ($j=0;$j<mysql_num_rows($resultgrupo_hombres);$j++)
				{
					$filagrupo_hombres = mysql_fetch_array($resultgrupo_hombres);
					
					$contar_hombres=$contar_hombres+$filagrupo_hombres['n_hombres'];
				}
				
	//para alberguistas: se saca el número de alberguistas hombres que han pernoctado en el albergue en el mes seleccionado

	$sqlalber_hombres="SELECT count(cliente.DNI_Cl) as n_hombres FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Sexo_Cl='M' and ".$finsql_al;
	
		$resultalber_hombres = mysql_query($sqlalber_hombres);
		
		$filaalber_hombres = mysql_fetch_array($resultalber_hombres);
		
		$contar_hombres=$contar_hombres+$filaalber_hombres['n_hombres'];
		

/*para peregrinos
$sqlpere_hombres="SELECT count(cliente.DNI_Cl) as n_hombres FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Sexo_Cl='M' and ".$finsql_pe;

$resultpere_hombres = mysql_query($sqlpere_hombres);

$filapere_hombres = mysql_fetch_array($resultpere_hombres);

$contar_hombres=$contar_hombres+$filapere_hombres['n_hombres'];*/


//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE MUJERES

//para grupos: aqui se saca el número de mujeres que formando parte de un grupo, han pernoctado en el albergue en el mes seleccionado

	$sqlgrupo_mujeres="SELECT (estancia_gr.Num_Mujeres) as n_mujeres  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where ".$finsql_gr;
	
		$resultgrupo_mujeres = mysql_query($sqlgrupo_mujeres);
		
			for ($j=0;$j<mysql_num_rows($resultgrupo_mujeres);$j++)
				{
					$filagrupo_mujeres = mysql_fetch_array($resultgrupo_mujeres);
					
					$contar_mujeres=$contar_mujeres+$filagrupo_mujeres['n_mujeres'];
				}

	//para alberguistas: se saca el número de alberguistas mujeres que han pernoctado en el albergue en el mes seleccionado

	$sqlalber_mujeres="SELECT count(cliente.DNI_Cl) as n_mujeres FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Sexo_Cl='F' and ".$finsql_al;
	
		$resultalber_mujeres = mysql_query($sqlalber_mujeres);
		
		$filaalber_mujeres = mysql_fetch_array($resultalber_mujeres);
		
		$contar_mujeres=$contar_mujeres+$filaalber_mujeres['n_mujeres'];


/*para peregrinos
$sqlpere_mujeres="SELECT count(cliente.DNI_Cl) as n_mujeres FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Sexo_Cl='F' and ".$finsql_pe;

$resultpere_mujeres = mysql_query($sqlpere_mujeres);

$filapere_mujeres = mysql_fetch_array($resultpere_mujeres);

$contar_mujeres=$contar_mujeres+$filapere_mujeres['n_mujeres'];*/

//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE MENORES DE 30 AÑOS

	/*para grupo: se hace una consulta a la base de datos en la cual de los grupos que han pernoctado en el alberqueen el mes seleccionado,
	se cogen el número de personas cuya edad es inferior a 30 años de edad*/

$con_menores_gr=0;

	$sql_menor_gr="SELECT HGr0_9,HGr10_19,HGr20_25,HGr26_29,FGr0_9,FGr10_19,FGr20_25,FGr26_29 FROM estancia_gr inner join grupo on estancia_gr.Nombre_Gr=grupo.nombre_gr where ".$finsql_gr;
	
		$resgru = mysql_query($sql_menor_gr);
			for ($j=0;$j<mysql_num_rows($resgru);$j++)
				{
					$filagru = mysql_fetch_array($resgru);
					
					$con_menores_gr=$con_menores_gr+($filagru['HGr0_9']+$filagru['HGr10_19']+$filagru['HGr20_25']+$filagru['HGr26_29']+$filagru['FGr0_9']+$filagru['FGr10_19']+$filagru['FGr20_25']+$filagru['FGr26_29']);
				}

	/*para alberguistas: se hace una constulta a la base de datos mirando la edad de los alberguistas que han pernoctado en el albergue 
	en el mes seleccionado y se cogen aquellos cuya edad no supere los 30 años de edad y se suman y tambien los de 30 años o mas y estos 
	se suman en otra variable*/

$con_menores_al=0;
$con_mayores_al=0;
	
	$sql_menores_al="SELECT Fecha_Nacimiento_Cl,Fecha_Llegada FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where ".$finsql_al;
	
		$res_al = mysql_query($sql_menores_al);
			for ($j=0;$j<mysql_num_rows($res_al);$j++)
				{
					$fila_al = mysql_fetch_array($res_al);
					$comprobar=edad ( $fila_al['Fecha_Nacimiento_Cl'],$fila_al['Fecha_Llegada'] ); 
					
					if($comprobar < 30)
						{
							$con_menores_al=$con_menores_al+1;
						}
					else
						{
							$con_mayores_al=$con_mayores_al+1;
						}
			}

/*para peregrinos

$con_menores_pe=0;
$con_mayores_pe=0;

$sql_menores_pe="SELECT Fecha_Nacimiento_Cl,Fecha_Llegada FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where ".$finsql_pe;

$res_pe = mysql_query($sql_menores_pe);

for ($j=0;$j<mysql_num_rows($res_pe);$j++)
{
$fila_pe = mysql_fetch_array($res_pe);
$comprobar=edad ( $fila_pe['Fecha_Nacimiento_Cl'],$fila_pe['Fecha_Llegada'] ); 
if($comprobar < 30)
{
	$con_menores_pe=$con_menores_pe+1;
}
else
{
	$con_mayores_pe=$con_mayores_pe+1;
}
}*/

//AQUI SE OBTIENE LA INFORMACION SOBRE EL NÚMERO DE MAYORES DE 30 AÑOS

	/*para grupo: se hace una consulta a la base de datos en la cual de los grupos que han pernoctado en el alberqueen el mes seleccionado,
	se cogen el número de personas cuya edad es mayor o igual a 30 años de edad*/

$con_mayores_gr=0;

	$sql_mayor_gr="SELECT HGr30_39,HGr40_49,HGr50_59,HGr60_69,HGrOtras,FGr30_39,FGr40_49,FGr50_59,FGr60_69,FGrOtras FROM estancia_gr inner join grupo on estancia_gr.Nombre_Gr=grupo.nombre_gr where ".$finsql_gr;
	
		$resgru = mysql_query($sql_mayor_gr);
		
			for ($j=0;$j<mysql_num_rows($resgru);$j++)
				{
					$filagru = mysql_fetch_array($resgru);
					
					$con_mayores_gr=$con_mayores_gr+($filagru['HGr30_39']+$filagru['HGr40_49']+$filagru['HGr50_59']+$filagru['HGr60_69']+$filagru['HGrOtras']+$filagru['FGr30_39']+$filagru['FGr40_49']+$filagru['FGr50_59']+$filagru['FGr60_69']+$filagru['FGrOtras']);
				}

$con_menores_30=$con_menores_gr+$con_menores_al;		
$con_mayores_30=$con_mayores_gr+$con_mayores_al;			

//	PARA SACAR EL NUMERO DE PERSONAS DE CASTILLA Y LEON

$con_cyl=0;

	/*para grupos: se hace una consulta a la base de datos para conocer los grupos que han pernoctado en el albergue y si su provincia de 
	procedencia es alguna de las que componen la comunidad autónoma de castilla y león*/


	$sqlgrupo_cyl="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where (grupo.Id_Pais='ES' and 
	(grupo.Id_Pro ='LE' or grupo.Id_Pro ='ZA' or grupo.Id_Pro ='SA' or grupo.Id_Pro ='VA' or grupo.Id_Pro ='BU' or grupo.Id_Pro ='SO' or grupo.Id_Pro ='SG' or grupo.Id_Pro ='AV' or grupo.Id_Pro ='PA')) and ".$finsql_gr;
	
		$resgru_cyl = mysql_query($sqlgrupo_cyl);
		
			for ($j=0;$j<mysql_num_rows($resgru_cyl);$j++)
			{
				$filgru_cyl = mysql_fetch_array($resgru_cyl);
				
				$con_cyl=$con_cyl+$filgru_cyl['personas'];
			}

	/*para alberguistas: se hace una consulta a la base de datos para conocer los alberguistas que han pernoctado en el albergue y si su 
	provincia de procedencia es alguna de las que componen la comunidad autónoma de castilla y león*/

	$sqlalber_cyl="select count(cliente.DNI_Cl) as personas from cliente,pernocta where cliente.DNI_Cl =pernocta.DNI_Cl and (cliente.Id_Pais='ES' and 
	(cliente.Id_Pro ='LE' OR cliente.Id_Pro ='ZA' OR cliente.Id_Pro ='SA' OR cliente.Id_Pro ='VA' OR cliente.Id_Pro ='PA' OR cliente.Id_Pro ='BU' OR cliente.Id_Pro ='SO' OR cliente.Id_Pro ='SG' OR cliente.Id_Pro ='AV')) and ".$finsql_al;
	
		$resalb_cyl = mysql_query($sqlalber_cyl);
		
		$filalb_cyl = mysql_fetch_array($resalb_cyl);
		
		$con_cyl=$con_cyl+$filalb_cyl['personas'];

/*para peregrinos

$sqlpere_cyl="select count(cliente.DNI_Cl) as personas from cliente,pernocta_p where cliente.DNI_Cl =pernocta_p.DNI_Cl and (cliente.Id_Pais='ES' and 
(cliente.Id_Pro ='LE' OR cliente.Id_Pro ='ZA' OR cliente.Id_Pro ='SA' OR cliente.Id_Pro ='VA' OR cliente.Id_Pro ='PA' OR cliente.Id_Pro ='BU' OR cliente.Id_Pro ='SO' OR cliente.Id_Pro ='SG' OR cliente.Id_Pro ='AV')) and ".$finsql_pe;

$respe_cyl = mysql_query($sqlpere_cyl);

$filper_cyl= mysql_fetch_array($respe_cyl);

$con_cyl=$con_cyl+$filper_cyl['personas'];*/


//PARA SACAR EL NUMERO DE PERSONAS DE OTRAS COMUNIDADES AUTONOMAS

$con_nocyl=0;

	/*para grupos: se hace una consulta a la base de datos para conocer los grupos que han pernoctado en el albergue en el mes seleccionado
	y su provincia de procedencia no es alguna de las que componen la comunidad autónoma de castilla y león*/
	
	$sqlgrupo_nocyl="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where (grupo.Id_Pais='ES' and 
	(grupo.Id_Pro !='LE' AND grupo.Id_Pro !='ZA' AND grupo.Id_Pro !='SA' AND grupo.Id_Pro !='VA' AND grupo.Id_Pro !='BU' AND grupo.Id_Pro !='SO' AND grupo.Id_Pro !='SG' AND grupo.Id_Pro !='AV' AND grupo.Id_Pro !='PA')) and ".$finsql_gr;
	
		$resgru_nocyl = mysql_query($sqlgrupo_nocyl);
			for ($j=0;$j<mysql_num_rows($resgru_nocyl);$j++)
				{
					$filgru_nocyl = mysql_fetch_array($resgru_nocyl);
					
					$con_nocyl=$con_nocyl+$filgru_nocyl['personas'];
				}

	/*para alberguistas: se hace una consulta a la base de datos para conocer los alberguistas que han pernoctado en el albergue en 
	el mes seleccionado y su provincia no de procedencia es alguna de las que componen la comunidad autónoma de castilla y león*/

	$sqlalber_nocyl="select count(cliente.DNI_Cl) as personas from cliente,pernocta where cliente.DNI_Cl =pernocta.DNI_Cl and (cliente.Id_Pais='ES' and 
	(cliente.Id_Pro !='LE' AND cliente.Id_Pro !='ZA' AND cliente.Id_Pro !='SA' AND cliente.Id_Pro !='VA' AND cliente.Id_Pro !='PA' AND cliente.Id_Pro !='BU' AND cliente.Id_Pro !='SO' AND cliente.Id_Pro !='SG' AND cliente.Id_Pro !='AV')) and ".$finsql_al;
	
		$resalb_nocyl = mysql_query($sqlalber_nocyl);
		
		$filalb_nocyl = mysql_fetch_array($resalb_nocyl);
		
		$con_nocyl=$con_nocyl+$filalb_nocyl['personas'];

/*para peregrinos

$sqlpere_nocyl="select count(cliente.DNI_Cl) as personas from cliente,pernocta_p where cliente.DNI_Cl =pernocta_p.DNI_Cl and (cliente.Id_Pais='ES' and 
(cliente.Id_Pro !='LE' AND cliente.Id_Pro !='ZA' AND cliente.Id_Pro !='SA' AND cliente.Id_Pro !='VA' AND cliente.Id_Pro !='PA' AND cliente.Id_Pro !='BU' AND cliente.Id_Pro !='SO' AND cliente.Id_Pro !='SG' AND cliente.Id_Pro !='AV')) and ".$finsql_pe;

$respe_nocyl = mysql_query($sqlpere_nocyl);

$filper_nocyl= mysql_fetch_array($respe_nocyl);

$con_nocyl=$con_nocyl+$filper_nocyl['personas'];*/

//	PARA SACAR EL NUMERO DE PERSONAS EXTRANJERAS
			
$contador_extranjeros=0;

	/*para grupos: se hace una consulta a la base de datos para conocer los grupos que han pernoctado en el albergue en el mes seleccionado
	y su pais de procedencia no es España*/

	$sqlgrupo_ex="SELECT SUM(estancia_gr.Num_Personas) as personas  FROM `grupo` inner join estancia_gr on grupo.nombre_gr=estancia_gr.nombre_gr where grupo.Id_Pais !='ES' and".$finsql_gr;				
	
		$resgrup_ex = mysql_query($sqlgrupo_ex);
		
			for ($j=0;$j<mysql_num_rows($resgrup_ex);$j++)
				{
					$filagrupo_ex = mysql_fetch_array($resgrup_ex);
					
					$contador_extranjeros=$contador_extranjeros+$filagrupo_ex['personas'];
				}

	/*para alberguistas: se hace una consulta a la base de datos para conocer los alberguistas que han pernoctado en el albergue en 
	el mes seleccionado y su pais de procedencia no es España*/

	$sqlalber_ex="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta on cliente.DNI_Cl=pernocta.DNI_Cl where cliente.Id_Pais !='ES' and ".$finsql_al;				
	
		$resalb_ex = mysql_query($sqlalber_ex);
		
		$filaalber_ex = mysql_fetch_array($resalb_ex);
		
		$contador_extranjeros=$contador_extranjeros+$filaalber_ex['personas'];


/*para peregrinos

	$sqlpere_ex="SELECT count(cliente.DNI_Cl) as personas FROM `cliente` inner join pernocta_p on cliente.DNI_Cl=pernocta_p.DNI_Cl where cliente.Id_Pais !='ES' and ".$finsql_pe;
	
	$resper_ex = mysql_query($sqlpere_ex);
	
	$filapere_ex = mysql_fetch_array($resper_ex);
	
	$contador_extranjeros=$contador_extranjeros+$filapere_ex['personas'];				
				
/*
 AQUI SE TERMINA LA OBTENCION DE LOS DATOS DEL MES ELEGIDO 
*/
?>