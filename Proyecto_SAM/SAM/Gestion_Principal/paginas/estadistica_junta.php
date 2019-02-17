<?php

	session_start();
?>
<head>
<style>body{
	font-family: Arial, Helvetica, sans-serif;
}</style>
</head>
<?php
	/*if(!isset($_SESSION['anio_inicio']))
	{
		$_SESSION['anio_inicio']=date("Y");
	}*/

	/* Esta página esta incluida dentro de la página de tabla de estadísticas y
	la de tabla de estadísticas incluida a su vez dentro de la pagina de estadisticas 
	y todas las variables de sesion que se usan en esta página se obtienen de la página de valores de estadística
	y otras de las otras páginas que estan incluidas dentro de la página estadisticas.php */

	$meses['01']="Enero";$meses['02']="Febrero";$meses['03']="Marzo";$meses['04']="Abril";$meses['05']="Mayo";$meses['06']="Junio";$meses['07']="Julio";$meses['08']="Agosto";
	$meses['09']="Septiembre";$meses['10']="Octubre";$meses['11']="Noviembre";$meses['12']="Diciembre";
// esta funcion es para calcular la edad de la gente que ha pasado o esta alojada en el albergue

	function edad($f_nacimiento,$f_llegada) {
			$fecha_nacimiento = SPLIT("-",$f_nacimiento);
			$fecha_llegada = SPLIT("-",$f_llegada);
			$edad = INTVAL($fecha_llegada[0]) - INTVAL($fecha_nacimiento[0]);
			if (INTVAL($fecha_llegada[1]) < INTVAL($fecha_nacimiento[1])) {
				$edad--;
			}
			else if (INTVAL($fecha_llegada[1]) == INTVAL($fecha_nacimiento[1])) {
				if (INTVAL($fecha_llegada[2]) < INTVAL($fecha_nacimiento[2])) {
					$edad--;
				}
			}
			return $edad;
		}
	
//esta funcion la uso para sacar cuantos dias han transcurrido en el año 
	
	function dias_transcurridos($dia_inicial,$dia_final) 
		{ 
			$dias_pasados=round((strtotime($dia_final)-strtotime($dia_inicial))/(86400),0); 
			
			return $dias_pasados;
		} 

//esta funcion la uso para saber los dias que los usuarios pernoctan en el albergue en un mes concreto

	function numero_pernoctas($dia_inicial,$dia_final,$dias_mes,$diferencia_dias,$mes_estadistica)
		{
	
	
			if($dias_mes > $diferencia_dias)
			{
				if (substr($dia_inicial,5,2) == substr($dia_final,5,2))
				{ 
					$dias_pernoctados=$diferencia_dias;
				}
				else if ((substr($dia_inicial,5,2) < (substr($dia_final,5,2)) and (substr($dia_inicial,5,2) == $mes_estadistica)))
				{
					$dias_pernoctados=$dias_mes - substr($dia_inicial,8,2);
				} 
				else if ((substr($dia_final,5,2) > (substr($dia_inicial,5,2)) and (substr($dia_final,5,2) == $mes_estadistica)))
				{
					$dias_pernoctados= substr($dia_final,8,2);
				} 
			}
				else 
				{
					$dias_pernoctados=$dias_mes;
				}
		
				return $dias_pernoctados;
		}
	
//Con esta funcion lo que se hace es sacar el número de pernoctas que ha tenido el albergue hasta el mes seleccionado
	
	function pernoctas_meses_anteriores($fecha_inicio_pernoctas,$fecha_fin_pernoctas,$mes_estadistica,$anio_estadistica)
		{
			$fecha_inicio_mes=$anio_estadistica."-".$mes_estadistica."-00";
			
			if((substr($fecha_inicio_pernoctas,5,2) < $mes_estadistica ) and (substr($fecha_fin_pernoctas,5,2) < $mes_estadistica) )
			{
				$dias_pernoctados_anteriormente=round((strtotime($fecha_fin_pernoctas)-strtotime($fecha_inicio_pernoctas))/(86400),0); 
			}
			else if((substr($fecha_inicio_pernoctas,5,2) < $mes_estadistica ) and (substr($fecha_fin_pernoctas,5,2)  >= $mes_estadistica))
			{
				$dias_pernoctados_anteriormente=$dias_pernoctados_anteriormente=round((strtotime($fecha_inicio_mes)-strtotime($fecha_inicio_pernoctas))/(86400),0); 
			}
			return $dias_pernoctados_anteriormente;
		}

?>
<body>
<?

	// Aqui se hace la conexion con la base de datos
	@ $db =	MYSQL_PCONNECT($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	
	// si el mes esta vacio
	if( !isset ( $_SESSION['mes_inicio'] )  )
		{
			if(isset($_POST['mes_ini_est']))
				$_SESSION['mes_inicio']=$_POST['mes_ini_est'];
			else
				$_SESSION['mes_inicio']=date("m");

			$m_anterior=$_SESSION['mes_inicio']-1;
			if($m_anterior < 10)
			{
				$m_anterior="0".$m_anterior;
			}
		}
		else
		{
			if(isset($_POST['mes_ini_est']))
			{
				$m_anterior=$_SESSION['mes_inicio']-1;
				$_SESSION['mes_inicio']=$_POST['mes_ini_est'];
			}
			if($m_anterior < 10)
			{
				$m_anterior="0".$m_anterior;
			}
		}
	// si el valor del mes es 0
	if($_SESSION['mes_inicio']==0)
		{
			$_SESSION['mes_inicio']=date("m");
			$mes = $_SESSION['mes_inicio'];
			$m_anterior=$mes-1;
			if($m_anterior < 10)
			{
				$m_anterior="0".$m_anterior;
			}
			$mes_titulo_est=substr($_SESSION['mes_inicio'],1,1);
			
		}
		else
		{
		$mes = $_SESSION['mes_inicio'];	
		$_SESSION['mes_inicio']=$_POST['mes_ini_est'];
		$m_anterior=$mes-1;
			if($m_anterior < 10)
				{
					$m_anterior="0".$m_anterior;
				}
			else
				{
					$m_anterior=$m_anterior;
				}
			/*if($mes < 10)
				{
					$mes="0".$mes;
					
				}
				else 
				{
					$mes=$mes;
					
				}*/
		$mes_titulo_est=$_SESSION['mes_inicio'];
				
		}
	//echo "<br>DOLAR m_anterior:" .$m_anterior;
	$anio = $_SESSION['anio_inicio'];

	$dia = mktime("0","0","0",$mes,1,$anio);
	$dias_mes = strftime("%t",$dia);
	
	
	if( (!isset($_SESSION['mes_inicio'])) || ($_SESSION['mes_inicio'] == "") )
	{
		$_SESSION['mes_inicio']=date("m");
	}
	

	/*Aqui se hace una consulta a la base de datos para conocer el número de camas que hay en el albergue, que son aquellas habitaciones 
	de la base de datos que no son compartidas*/
	
	$num_camas=0;
	$sql_camas="select SUM(habitacion.Camas_Hab) as camas 
		from habitacion INNER JOIN 
		(SELECT cambio_tipo_habitacion.Id_Hab,cambio_tipo_habitacion.Id_Tipo_Hab 
		FROM cambio_tipo_habitacion 
		INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = cambio_tipo_habitacion.Id_Tipo_Hab
		where (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')) as cons ON cons.Id_Hab=habitacion.Id_hab AND habitacion.Camas_Hab <> -1;";
				
		$res_camas = mysql_query($sql_camas);
		
		$n_res_cam=@ mysql_num_rows($res_camas);
		
		$num_camas=@ mysql_fetch_array($res_camas);
		
		//Aqui se guarda la suma de todas las camas que hay en este tipo de habitacines
		$numero_camas=$num_camas['camas'];
		


	/*Aqui se hace una consulta a la base de datos para conocer el número de literas que hay en el albergue, pero solo en las habitaciones 
	que son usadas para que pernocten alberguistas,que son aquellas habitaciones de la base de datos que no son compartidas y a su vez son 
	reservables*/
	
	$num_literas=0;
	$sql_literas = "select SUM(habitacion.Camas_Hab) as camas 
		from habitacion INNER JOIN 
		(SELECT cambio_tipo_habitacion.Id_Hab,cambio_tipo_habitacion.Id_Tipo_Hab 
		FROM cambio_tipo_habitacion 
		INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = cambio_tipo_habitacion.Id_Tipo_Hab
		where (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='S')) as cons ON cons.Id_Hab=habitacion.Id_hab ;";
	//echo "<br>" .$sql_literas;
				
		$res_literas = mysql_query($sql_literas);
		
		$n_res_literas=@ mysql_num_rows($res_literas);
		
		$num_literas=@ mysql_fetch_array($res_literas);
		
		//Aqui se guarda la suma de todas las literas que hay en este tipo de habitacines
		$numero_literas=$num_literas['camas'];
		//echo "<br>" .$numero_literas;

	/*
		PARA SACAR EL NUMERO DE DIAS QUE LLEVA EL ALBERGUE CON OCUPACIÓN
	*/
	
	//en esta variable se guarda el 1 de enero del año seleccionado
	$dia_inicial_cuenta=$anio."-01-01";
	
	//Aqui se guarda el dia 1 del mes y año seleccionado
	$dia_final_cuenta=$anio."-".$mes."-01";
//echo "<br>DOLAR DIA INICIAL CUENTA:" .$dia_inicial_cuenta;	
//echo "<br>DOLAR DIA FINAL CUENTA:" .$dia_final_cuenta;	
	

	//Aqui se guarda el último dia del mes y año que se haya seleccionado
	$mes_siguiente=$mes+1;
	if($mes_siguiente < 10)
	{
		$mes_siguiente="0".$mes_siguiente;
	}
//echo "<BR>DOLAR MES SIGUIENTE:" .$mes_siguiente;
	$a=mktime(0,0,0,$mes_siguiente,"01",$anio);
//echo "<br>DOLAR A:" .$a;
	$dia_final_cuenta2=strftime("%Y-%m-%d",$a);
//echo "<br>DOLAR DIA FINAL CUENTA 2:" .$dia_final_cuenta2;		
	/*Aqui se aplica la función de dias transcurridos para saber el número de dias que ha pasado desde el 1 de enero del año seleccionado
	hasta el dia 1 del mes y año que esten seleccionados*/
	$dias_pasados_mes=dias_transcurridos($dia_inicial_cuenta,$dia_final_cuenta);
	
	//Aqui se aplica la funcion de dias transcurridos pero para conocer el número de dias del mes y año que se han seleccionado
	$dias_mes_elegido=dias_transcurridos($dia_final_cuenta,$dia_final_cuenta2);
	
	
	
	//$imprimir=$_GET['pulsado'];
	
	
	/* En esta parte se realizan la inclusión de dos páginas, la de estadistica_junta_mes_seleccionado.php, que contiene las consultas 
	que se han realizado a la base de datos para conocer los valores que se han dado a lo largo del mes del cual se desea la estadística,
	y la página de estadística_junta_meses_anteriores.php, de donde se saca la información de los valores alcanzados en el albergue en 
	en los meses anteriores al mes del cual se pide la estadística*/
	
	/*Aqui se mira si se ha pulsado el boton de imprimir la estadística por que la ruta de los includes varía si se ha pulsado el botón
	de imprimir o no se ha pulsado*/
	
	if(!empty($pulsado_imprimir))
		{
			include('../paginas/estadistica_junta_mes_seleccionado.php');
			
			include('../paginas/estadistica_junta_meses_anteriores.php');
		}
		else
		{
			include('./paginas/estadistica_junta_mes_seleccionado.php');
			
			include('./paginas/estadistica_junta_meses_anteriores.php');
		}
	/*
	A PARTIR DE AQUI SE SACAN LOS RESULTADOS TOTALES QUE SE LLEVAN REGISTRADOS EN EL AÑO
	*/
	
	/*En estas variables lo que se guarda son los resultados totales de los meses tarncurridos hasta el mes del cual se saca la etadística
	incluido en este valor los datos del mes del cual se muestra la estadística*/
		$dias_ocupados_totales=$dias_mes_elegido+$dias_pasados_mes;
		
		$num_pernoctas_totales=$pernoctas_usuarios_mes+$pernoctas_usuarios_mes_ant;
		
		$num_total_usuarios=$contar_usuarios+$contar_usuarios_ant;
			
		$num_total_hombres=$contar_hombres+$contar_hombres_ant;
			
		$num_total_mujeres=$contar_mujeres+$contar_mujeres_ant;
			
		$num_total_mayores_30=$con_mayores_30+$con_mayores_30_ant;
		
		$num_total_menores_30=$con_menores_30+$con_menores_30_ant;
			 
		$num_total_cyl=$con_cyl+$con_cyl_ant;
		
		$num_total_otras_ccaa=$con_nocyl+$con_nocyl_ant;
			
		$num_total_extranjeros=$contador_extranjeros+$contador_extranjeros_ant;
		 
	/*
	AQUI TERMINA LA INFORMACIÓN SOBRE LOS RESULTADOS TOTALES DEL AÑO
	*/
	
?> 

<!-- En esta tabla es donde se va a insertar los valores de todas las consultas que se han realizado con anterioridad 
en la página para mostrar la información que la junta de castilla y león pide al albergue del mes vencido -->

<table width="750px"  style="font-size:14px;" border="1" bordercolor="#000000" >
  <tr> 
    <td><table width="700px" border="0" align="center" cellspacing="5">
        <tr align="center"> 
          <td colspan="5" ><strong><font size="5">Mes de <?echo $meses[$_SESSION['mes_inicio']];?> <?=$anio?></font></strong></td>
        </tr>
        <tr> 
          <td><strong>Capacidad:</strong></td>
          <td align="left"> 
           <?=$numero_literas?></td> 
		  <td>plazas en literas</td>
          <td><strong>MODALIDAD:</strong></td>
          <td>Permanente</td>
        </tr>
        <tr>
			<td></td>
			<td align="left"><?=$numero_camas?></td>
			<td>plazas en camas</td>
		</tr>
      </table>
     <table width="700px" border="1" align="center" cellspacing="5" bordercolor="#000000">
        <tr align="center"> 
          <td colspan="4"><strong><font size="4">ESTADÍSTICA DE OCUPACI&Oacute;N</font></strong></td>
        </tr>
        <tr> 
          <td bordercolor="#FFFFFF">&nbsp;</td>
          <td width="20%" align="center"><strong>Mes Actual</strong></td>
          <td width="25%" align="center"><strong>Meses Anteriores</strong></td>
          <td width="20%" align="center"><strong>Total Acumulado</strong></td>
        </tr>
        <tr> 
          <td align="center"><strong>D&Iacute;AS OCUPADOS</strong></td>
         <td align="right">
            <?=$dias_mes_elegido?>
          </td>
          <td align="right">
            <?=$dias_pasados_mes?>
          </td>
          <td align="right">
            <?=$dias_ocupados_totales?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>N&ordm; PERNOCTAS</strong></td>
          <td align="right">
            <?=$pernoctas_usuarios_mes?>
          </td>
          <td align="right">
            <?=$pernoctas_usuarios_mes_ant?>
          </td>
          <td align="right">
            <?=$num_pernoctas_totales?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>N&ordm; USUARIOS</strong></td>
          <td align="right">
            <?=$contar_usuarios?>
          </td>
          <td align="right">
            <?=$contar_usuarios_ant?>
          </td>
          <td align="right">
            <?=$num_total_usuarios?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>N&ordm; VARONES</strong></td>
          <td align="right">
            <?=$contar_hombres?>
          </td>
          <td align="right">
            <?=$contar_hombres_ant?>
          </td>
          <td align="right">
            <?=$num_total_hombres?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>N&ordm; MUJERES</strong></td>
          <td align="right">
            <?=$contar_mujeres?>
          </td>
          <td align="right">
            <?=$contar_mujeres_ant?>
          </td>
          <td align="right">
            <?=$num_total_mujeres?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>MENORES DE 30 A&Ntilde;OS</strong></td>
          <td align="right">
            <?=$con_menores_30?>
          </td>
          <td align="right">
            <?=$con_menores_30_ant?>
          </td>
          <td align="right">
            <?=$num_total_menores_30?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>MAYORES 30 A&Ntilde;OS</strong></td>
          <td align="right">
            <?=$con_mayores_30?>
          </td>
          <td align="right">
            <?=$con_mayores_30_ant?>
          </td>
          <td align="right">
            <?=$num_total_mayores_30?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>PROCEDENTES CyL</strong></td>
          <td align="right">
            <?=$con_cyl?>
          </td>
          <td align="right">
            <?=$con_cyl_ant?>
          </td>
          <td align="right">
            <?=$num_total_cyl ?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>OTRAS CC.AA.</strong></td>
          <td align="right">
            <?=$con_nocyl?>
          </td>
          <td align="right">
            <?=$con_nocyl_ant?>
          </td>
          <td align="right">
            <?=$num_total_otras_ccaa ?>
          </td>
        </tr>
        <tr> 
          <td align="center"><strong>EXTRANJEROS</strong></td>
          <td align="right">
            <?=$contador_extranjeros?>
          </td>
          <td align="right">
            <?=$contador_extranjeros_ant?>
          </td>
          <td align="right">
            <?=$num_total_extranjeros?>
          </td>
        </tr>
      </table>
      
	  <table width="700px" border="0" align="left" cellspacing="2">
	  	<tr>
			<td align="left" colspan="2">* Los grupos est&aacute;n incluidos en las cifras anteriores </td>
		</tr>
        <tr>
          <td colspan="2" align="left"><strong>CARNETS REAJ VENDIDOS EN EL ALBERGUE</strong></td>
        </tr>
		<?
		/* Aqui se hace una consulta a la base de datos para mostrar el número de carnets reaj que se han vendido en el albergue 
		durante el mes del que se pide la estadística de la junta*/
		
			$sql = "select * from reaj";
			$res = mysql_query($sql);
			$num = mysql_num_rows($res);
			for ($i = 0; $i < $num; $i++){
				$fila = mysql_fetch_array($res);
				$sql = "select * from solicitante inner join reaj on (solicitante.id_carnet = reaj.id_carnet) where reaj.nombre_carnet='" . $fila['Nombre_Carnet'] . "' and solicitante.fecha between '".$anio."-".$mes."-01' and '".$anio."-".$mes."-31'";
				$res1 = mysql_query($sql);
				echo "<tr><td align='left' width='300px'><b>" . $fila['Nombre_Carnet'] . "</b></td><td align='left' width='200px'>" . mysql_num_rows($res1) . "</td></tr>";
			}
		?>
		</table>
	</td>
  </tr>
</table>
<?php
	//echo "mes:" .$_SESSION['mes_inicio'];
	//echo "<br>año:" .$_SESSION['anio_inicio'];
?>
<?php mysql_close($db); ?>
</body>