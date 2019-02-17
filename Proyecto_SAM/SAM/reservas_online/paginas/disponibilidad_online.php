<?php
	session_start();
	if(!isset($_SESSION['conexion'])){//si no esta creada aun la session de pag_hab
		include('../config.inc.php');	//incluimos el contenido de la pagina config.inc.php
	}
	/**** VARIABLES DE CONFIGURACION ****/
	$maxNumAnyos=4; // Fija los años que se mostraran en el <SELECT> de los años. Mostrara los que aqui se pongan mas el año actual 
	$tiposHabAMostrar=2; // Fija los tipos de habitaciones que se mostraran en cada pagina
	/************************************/
	if(isset($_GET['fecha_cal']))
		$hoy=split("-",$_GET['fecha_cal']); //Parto la fecha por dia, mes y año

	if($hoy[2]<intval(date('Y')) || $hoy[2]>(intval(date('Y'))+$maxNumAnyos)) // Si el año que le pasamos como parametro es inferior al actual o '$maxNumAnyos+1' años mayor que el año actual, entonces fijo como fecha la fecha actual
		{
		$hoy=array();
		$hoy=split("-",date('d-m-Y')); //Parto la fecha actual por dia, mes y año
		$_GET['fecha_cal']=date('d-m-Y');
		}
	
	if($hoy[1]=='2') // Si el mes es Febrero
		{
		if($hoy[0]>28) // Si el dia es mayor de 28
			if(($hoy[0]==29 && date("L",mktime(0, 0, 0,$hoy[1],$hoy[0],$hoy[2]))==0) || $hoy[0]>29) // Si es 29-Feb y noes bisiesto, o el dia es mayor de 29, fijo la fecha a 28-Feb 
				{
				$hoy[0]=28;
				$_GET['fecha_cal']=$hoy[0]."-".$hoy[1]."-".$hoy[2];				
				}
		}
	else
		{
		if(($hoy[1]=='4' || $hoy[1]=='6' || $hoy[1]=='9' || $hoy[1]=='11') && $hoy[0]>30) // Si el mes es Abril, Junio, Septiem. o Noviemb. y el dia es mayor de 30, fijo la fecha a 30 del mes elegido
			{
			$hoy[0]=30;
			$_GET['fecha_cal']=$hoy[0]."-".$hoy[1]."-".$hoy[2];				
			}
		}

	$mktimeSistema=mktime(0,0,0);
	$mktimeElegida=mktime(0,0,0,$hoy[1],$hoy[0],$hoy[2]);
	if($mktimeElegida<$mktimeSistema)
		{
	  	$hoy=array();
		$hoy=split("-",date('d-m-Y')); //Parto la fecha actual por dia, mes y año
		$_GET['fecha_cal']=date('d-m-Y');
		}
	
	if(isset($_GET['limit']))
		$elLimit=$_GET['limit'];
	else
		$elLimit="0";

?>


<html><!-- InstanceBegin template="/Templates/plantilla_php.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
	<link rel="stylesheet" href="../css/estilos.css">
	<link rel="stylesheet" href="../css/disponibilidad_online.css">
	<title>::ALBERGUE MUNICIPAL DE LEÓN</title>
	<!-- InstanceBeginEditable name="head" -->
	<link rel="stylesheet" href="../css/estilo_online.css">
	<LINK rel="stylesheet" type="text/css" href="../css/habitaciones.css">
	<!-- InstanceEndEditable -->
	</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td valign="top">
<table width="950" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="tablaGeneralDisponibilidad">
        <tr> 
          	<td height="120" colspan="2" align="left" valign="top" bgcolor="#3333FF"> 
			<div><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100%" height="100%">
                    <param name="movie" value="../swf/superior.swf">
                    <param name=quality value=high>
                    <embed src="../swf/superior.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="100%"></embed> 
                  </object>
			</div>
			</td>
        </tr>
        <tr> 
          <td align="left" valign="top" width="200px" rowspan="2"> 
				<table width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#67720C">
					<tr> 
						<td align="center" valign="top" height="300px">
<?php
							$nom = split("/",$PHP_SELF);
?>
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="100%" height="300">
								<param name="movie" value="../swf/menu.swf">
								<param name=quality value=high>
								<PARAM NAME=FlashVars VALUE="pagina=<?=$nom[count($nom)-1]?>">
								<embed src="../swf/menu.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="300"></embed>
							</object>
						</td>
					</tr>
					<tr> 
						<td align="center" valign="top">
<div>     
		<!--  ********* Calendario de Distribucion de Habitaciones ****************-->
<?php
		/*************************************** MI CALENDARIO ***********************************************************/

	echo "<table border='1' bordercolor='rgb(103,114,12)' cellpadding='0' cellspacing='1' class='tablaCalendario'><tr><td>";
	echo "<select id='selectM' name='selectM'  onChange='location.href=\"?fecha_cal=".$hoy[0]."-\"+this.value+\"-".$hoy[2]."\";'>";
	$meses=array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	for($i=0;$i<12;$i++)
		{
		echo "<option value='".($i+1)."'";
		if($hoy[1]-$i==1)
			echo " selected='true'";
		echo ">".$meses[$i]."</option>";
		}
	echo "</select>";
echo "</td><td>";
	echo "<select id='selectA' name='selectA' onChange='location.href=\"?fecha_cal=".$hoy[0]."-".$hoy[1]."-\"+this.value;'>";
	$anyo=intval(date('Y'));
	if(isset($hoy[2]) && ($anyo<=$hoy[2] && $hoy[2]<=($anyo+$maxNumAnyos)))
		$anyoElegido=$hoy[2];
	else
		$anyoElegido=$anyo;
	for($i=$anyo;$i<=($anyo+$maxNumAnyos);$i++)
		{
		echo "<option value=".$i;
		if($i==$anyoElegido)
			echo " selected='true'";
		echo ">".$i."</option>";
		}
	echo "</select>";
echo "</td></tr><tr><td colspan='2' align='center'>";
	$dia=intval(date("d",mktime(0, 0, 0,$hoy[1],$hoy[0],$hoy[2])));
	$diaActual=$dia;
	
	$indiceDia=intval(date("w",mktime(0, 0, 0,$hoy[1],$hoy[0],$hoy[2])));

	if($indiceDia>0)
		$indiceDia--;
	else
		$indiceDia=6;

	$numDias=intval(date("t",mktime(0, 0, 0,$hoy[1],$hoy[0],$hoy[2])));

	while($dia>1)
		{
		$dia--;
		if($indiceDia>0)
			$indiceDia--;
		else
			$indiceDia=6;
		}
		
	
	echo "<span class='calendario_dias'>
			<table cellspacing='0' cellpadding='0'>
				<tr>
					<td style='cursor:default;color:rgb(0,0,0);'>L</td>
					<td style='cursor:default;color:rgb(0,0,0);'>M</td>
					<td style='cursor:default;color:rgb(0,0,0);'>X</td>
					<td style='cursor:default;color:rgb(0,0,0);'>J</td>
					<td style='cursor:default;color:rgb(0,0,0);'>V</td>
					<td style='cursor:default;color:rgb(196,0,0);'>S</td>
					<td style='cursor:default;color:rgb(196,0,0);'>D</td>																		
				</tr>";
	for($i=0;$i<$indiceDia;$i++);
	if($i!=0)
		echo "<tr>
				<td colspan=".$i." style='cursor:default;'>&nbsp;</td>";
	//if($indiceDia==6)
	//	echo "</tr>";
	for($i=1;$i<=$numDias;$i++)
		{
		if($indiceDia==0)
			echo "<tr>";
		echo "<td ";
		if($mktimeSistema<=mktime(0,0,0,$hoy[1],$i,$hoy[2]))
			echo "onclick='location.href=\"?fecha_cal=".$i."-".$hoy[1]."-".$hoy[2]."\";' style='cursor:pointer;";
		else
			echo "style='cursor:default;filter:alpha(opacity=45);-moz-opacity:0.45;";
		if($indiceDia>4 && $i!=$diaActual) // Si es Sabado o Domingo y no es el dia seleccionado
			echo "color:rgb(169,71,61);'";
		else
			if($indiceDia>4 && $i==$diaActual) // Si es Sabado o Domingo y es el dia seleccionado
				echo "color:rgb(196,0,86);' id='dia_selected'";
			else
				if($i==$diaActual) // Si no es Sabado o Domingo y es el dia seleccionado
					echo "' id='dia_selected'";
				else // Si es un dia corriente no seleccionado
					echo "'";

		echo ">".$i."</td>";
		if($indiceDia==6)
			echo "</tr>";
		if($indiceDia<6)
			$indiceDia++;
		else
			$indiceDia=0;
		}
	if($indiceDia!=0)
		echo "<td colspan=".(7-$indiceDia)." style='cursor:default;'>&nbsp;</td>
			</tr>";
	echo "</table>
	</span>";

echo "</td></tr></table><br>";
/******************** FIN DE MI CALENDARIO *********************/
?>
</div>
						</td>
					</tr>
					<tr>
					<td height="65">
							<table width="95%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								
                      			<td width="61"><img src="../imagenes/logo_peque.gif" width="61" height="50"></td> 
								<td>
									<font size="2" color="#FFFFFF"> 
<?php
										@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'],  $_SESSION['conexion']['pass']);
										if (!$db){
											echo "Error : No se ha podido conectar a la base de datos";
											exit;
										}
										mysql_select_db($_SESSION['conexion']['db']);

										$sql = "select *, pais.nombre_pais as Pais_Alb, provincia.nombre_pro as Provincia_Alb  from albergue inner join pais on ";
										$sql = $sql . "(albergue.id_pais = pais.id_pais) inner join provincia on (albergue.id_pro = provincia.id_pro)";
										$res = mysql_query($sql);
										$datos = mysql_fetch_array($res);
										echo $datos['Direccion_Alb'] . "<br>";
										echo "C.P.: " . $datos['CP_Alb'] . " (" . $datos['Localidad_Alb'] . ")<br>";
										echo "Tlf.: " . $datos['Tfno1_Alb'] . "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $datos['Tfno2_Alb'];
?>
									</font>
								</td>
							</tr>
							</table>
					</td>
				</tr>
				</table>
			</td> 
          	<td align="left" valign="top">
				<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td width="31" height="35" valign="top"><img src="../imagenes/sep.gif" width="31" height="31"> 
                </td>
                <td width="719" align="center" valign="middle"><u><!-- InstanceBeginEditable name="titulo" -->
				<b><font color="#C40056" size="5">Disponibilidad de Habitaciones</font></b>
				<!-- InstanceEndEditable --></u> <br>
                  <br></td>
              </tr>

			  <TR>
			   <TD COLSPAN="2">
			  
              </TD>
 		  </TR>
              <tr> 
                <td colspan="2" align="center" valign="top"> <!-- InstanceBeginEditable name="contenido" -->







<?


//Recoger fecha del calendario

  	//creo todas la fechas que se usaran mas adelante para hacer comparaciones
			if (isset($_GET['fecha_cal']))
				{
              	//si se esta moviendo el calendario la fecha a ver en 'distribución de habitaciones' es la seleccionada
              	$fecha_alreves=$_GET['fecha_cal'];
		        $array_fecha=split("-",$fecha_alreves);
				$fecha_dia = $array_fecha[0];
				$fecha_mes = $array_fecha[1];
				$fecha_anio = $array_fecha[2];
				}
			else
				{
				//si no hay fechas, se coge la fecha de hoy
				$fecha_dia = strftime("%d",time());
				$fecha_mes = strftime("%m",time());
				$fecha_anio = strftime("%Y",time());
				}
			$fecha_sel = mktime(0, 0, 0, $fecha_mes, $fecha_dia, $fecha_anio);	//se compone la fecha seleccionada, la de salida y la de llegada
			$fecha_sal = mktime(0, 0, 0, $fecha_mes, $fecha_dia+1, $fecha_anio);
			$fecha_lle = mktime(0, 0, 0, $fecha_mes, $fecha_dia, $fecha_anio);
			if($fila['llega'] == "")
				{
				//si no hay fecha de llegada, no hay reserva, por lo que la fecha de llegada es hoy y la de salida mañana
				$fecha_lle = mktime(0, 0, 0, $fecha_mes, $fecha_dia, $fecha_anio);
				$fecha_sal = mktime(0, 0, 0, $fecha_mes, $fecha_dia+1, $fecha_anio);
				}
			$fec_lle_val = strftime("%d/%m/%Y",$fecha_lle);
			$fec_sal_val = strftime("%d/%m/%Y",$fecha_sal);
	




//fechas


//Div que contiene el calendario


?>















                  



<!--------------------------------------------Disponibilidad Habitaciones-------------------------------------------------------------------------->







		
				
					<table border="5" width="98%" class="tabla_habitaciones" bordercolor="#FFFFFF" rules="none" cellpadding="0" cellspacing="1">
<?php

	//Se Crean y Ejecutan las consultas para mostrar el lisado
	$reservas_dia=array(); //este array contiene todas la pernoctas y reservas que están en alguna de las fechas de la reserva on-line
	$pn = $fila['pn'];
	if($pn == "")	//si pn no tiene valor, no hay reserva on-line, por lo que vale 1
		$pn = 1;
	$f_sel = strftime("%Y-%m-%d",$fecha_sel);	//se ponen las fecha en formato aaaa-mm-dd para compararlas con las fechas de las pernoctas y reservas existentes

	$f_lle = strftime("%Y-%m-%d",$fecha_lle);
	$f_sal = strftime("%Y-%m-%d",$fecha_sal);

	/*Compruebo si hay reservas entre la fecha de llegada y salida (menos las reservas on-line)*/
	$qry_sel_res = "SELECT *, detalles.fecha_llegada as fecha, detalles.fecha_salida as fecha_s ";
	$qry_sel_res = $qry_sel_res . "FROM detalles INNER JOIN reserva ON ";
	$qry_sel_res = $qry_sel_res . "(detalles.dni_pra = reserva.dni_pra AND detalles.fecha_llegada = reserva.fecha_llegada) ";
	$qry_sel_res = $qry_sel_res . "WHERE id_hab != 'PRA'";
	$res_sel_res=mysql_query($qry_sel_res);

	$ini = 0; //esta variable indica la posición en la que se pone un elemento en el array reservas_dia
	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res)+$con;$i++)
		{
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s']) //si la fecha de llegada y salida de la reserva no son iguales
			{
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));	//creo las fechas de llegada y salida de la reserva
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++)	//recorro todos los dias de la reserva on-line, hasta que acabe o se ponga en el array
				{
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t)	//si las fechas de la reserva actual está entre las de la reserva on-line
					{
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];			//se pone en el array de reservas_dia
					$reservas_dia[$ini]['camas']=$tupla_res['Num_Camas'];
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$ini++;
					$puesto = 1;	//y se indica que ya se ha puesto en el array
					}
				}
			}
		}
	//el funcionamiento en los 3 buques siguientes es igual a este
	$ini = count($reservas_dia);

	/*Compruebo si hay estancias de grupos entre la fecha de llegada y salida*/
	$qry_sel_res = "SELECT *, pernocta_gr.num_personas as num_per, estancia_gr.fecha_llegada as fecha, estancia_gr.fecha_salida as fecha_s ";
	$qry_sel_res = $qry_sel_res . "FROM pernocta_gr INNER JOIN estancia_gr ON ";
	$qry_sel_res = $qry_sel_res . "(pernocta_gr.nombre_gr = estancia_gr.nombre_gr AND '". $f_sel . "' >= estancia_gr.fecha_llegada AND ";
	$qry_sel_res = $qry_sel_res . "pernocta_gr.fecha_llegada = estancia_gr.fecha_llegada)";
	$res_sel_res=mysql_query($qry_sel_res);

	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++)
		{
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s'])
			{
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++)
				{
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t)
					{
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=$tupla_res['num_per'];
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$ini++;
					$puesto = 1;
					}
				}
			}
		}
	$ini = count($reservas_dia);
	
	/*Compruebo si hay estancias de peregrinos entre la fecha de llegada y salida*/
	$qry_sel_res = "SELECT *, fecha_llegada as fecha, fecha_salida as fecha_s FROM pernocta_p";
	$res_sel_res=mysql_query($qry_sel_res);

	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++)
		{
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s'])
			{
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++)
				{
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t)
					{
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=1;
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$ini++;
					$puesto = 1;
					}
				}
			}
		}
	$ini = count($reservas_dia);

	/*Compruebo si hay estancias de alberguista entre la fecha de llegada y salida*/
	$qry_sel_res = "SELECT *, fecha_llegada as fecha, fecha_salida as fecha_s FROM pernocta";
	$res_sel_res=mysql_query($qry_sel_res);

	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++)
		{
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s'])
			{
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++)
				{
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t)
					{
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=1;
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$ini++;
					$puesto = 1;
					}
				}
			}
		}

	if(count($reservas_dia) != 0)
		{ //si hay alguna reserva
		for ($a = 0;$a < count($reservas_dia); $a++)
			$fec[$a] = $reservas_dia[$a]['fecha'];
		array_multisort ($fec, SORT_ASC, $reservas_dia);//las ordeno por la fecha de llegada
		}



//******************************************<<<DISTRIBUCIÓN DE HABITACIONES>>>*****************************************
//Se Almacenan en un array los datos de todas las habitaciones existentes;
$habita=array();
$cont=0;
$tipo_Hab=array();
$cont=0;
$max_camas = 8;	//numero maximo de camas por columna
$contador = 0;
//Consulta para obtener los datos de las habitaciones

if(isset($_GET['fecha_cal']))
	{
	$fech=split("-",$_GET['fecha_cal']);
	$fechaDeCambio="'".$fech[2]."-".$fech[1]."-".$fech[0]."'";
	}
else	
	$fechaDeCambio="curdate()";
$cons=mysql_query("
				SELECT 
					tipo_habitacion.Id_Tipo_Hab
				FROM
					tipo_habitacion
				WHERE
					Nombre_Tipo_Hab<>'Aula' and tipo_habitacion.Id_Tipo_Hab IN (SELECT
														Id_Tipo_Hab
													FROM	
														(SELECT
															DISTINCT(Id_Hab),
															MAX(Fecha),
															Id_Tipo_Hab
														FROM
															cambio_tipo_habitacion 
														WHERE
															Fecha<=".$fechaDeCambio."
														GROUP BY (Id_Hab)
														) AS consulta
													) 
				ORDER BY
					(tipo_habitacion.Reservable = 'S') DESC,
					(tipo_habitacion.Id_Tipo_Hab) ASC
				LIMIT ".$elLimit.",".$tiposHabAMostrar);

if(mysql_num_rows($cons)>0)
	{
	$condicion="(";
	for($ccc=0;$ccc<mysql_num_rows($cons);$ccc++)
		{
		$filaCons=mysql_fetch_row($cons);
		if($ccc==0)
			$condicion.="tipo_habitacion.id_tipo_hab='".$filaCons[0]."'";
		else
			$condicion.=" or tipo_habitacion.id_tipo_hab='".$filaCons[0]."'";
		}
	$condicion.=")"	;
	}

$qry_dist="(
			SELECT
				habitacion.*,
				tipo_Habitacion.*	
			FROM
				(
				SELECT
					DISTINCT(Id_Hab),
					MAX(Fecha),
					Id_Tipo_Hab
				FROM
					cambio_tipo_habitacion
				WHERE
					Fecha<=".$fechaDeCambio."
				GROUP BY
					Id_Hab
				)AS consulta1,
				habitacion,
				tipo_habitacion
			WHERE
				consulta1.Id_Hab=habitacion.Id_Hab
				AND
				tipo_habitacion.Id_Tipo_Hab=consulta1.Id_Tipo_Hab
				AND
				habitacion.camas_hab <> -1
				AND
				".$condicion."
			ORDER BY
				(tipo_habitacion.Reservable='S') DESC,
				tipo_habitacion.Id_Tipo_Hab ASC
			)";	

$res_dist=mysql_query($qry_dist); // Hago la consulta y guardo los resultados en '$res_dist'
for ($i=0;$i<mysql_num_rows($res_dist);$i++)
	{
	$tupla_dist=mysql_fetch_array($res_dist);
	//if ($tupla_dist['Camas_Hab']>0){//con este if se quitan las habitaciones que no tiene camas
	$habita[$contador]['orden']=intval($tupla_dist['Id_Hab']); 
	$habita[$contador]['id']=$tupla_dist['Id_Hab']; // El ID de la habitacion
	$habita[$contador]['tipo']=$tupla_dist['Id_Tipo_Hab'];
	$habita[$contador]['nombre_tipo']=$tupla_dist['Nombre_Tipo_Hab'];
	$habita[$contador]['puede_reservar']=$tupla_dist['Reservable'];
	$habita[$contador]['compartida']=$tupla_dist['Compartida'];
	$habita[$contador]['camas']=$tupla_dist['Camas_Hab'];
	$habita[$contador]['total_camas']=$tupla_dist['Camas_Hab'];
	$habita[$contador]['ocupadas']=array();
	$habita[$contador]['ocupadas']['c'] = 0;
	$habita[$contador]['columnas'] = 1;
	$habita[$contador]['pagina'] = $valor;
	
	//Utilizo el array reservas_dia (donde almaceno las reservas para el dia deseado) para ver que camas tengo ocupadas
	for ($j=0;$j<count($reservas_dia);$j++)
		{
		if($habita[$contador]['id']==$reservas_dia[$j]['hab'])
			{
			$camas_ant = $habita[$contador]['ocupadas']['c'];
			if($camas_ant == "")
				$camas_ant = 0;
			$camas_aho = $camas_ant + $reservas_dia[$j]['camas'];
			$habita[$contador]['ocupadas']['hab']=$reservas_dia[$j]['hab'];
			for($a = $camas_ant; $a <= $camas_aho; $a++)	//para cada cama de esta estancia/reserva le pongo la fecha de llegada y salida correspondiente
				{
				$habita[$contador]['ocupadas']['fecha'][$a]=$reservas_dia[$j]['fecha'];
				$habita[$contador]['ocupadas']['fecha_s'][$a]=$reservas_dia[$j]['fecha_s'];
				}
			$habita[$contador]['ocupadas']['c']+=$reservas_dia[$j]['camas']; //y aumento el numero de camas ocupadas de la habitacion
			}
		}
	if($habita[$contador]['camas'] > $max_camas)	//si la habitacion tiene mas camas del maximo
		{
		//$num_camas = $habita[$actual]['camas'];
		$actual = $contador;
		$columnas = 1;
		while($habita[$actual]['camas'] > $max_camas) //creo tantas habitaciones extra como sean necesarias, pero con el mismo id
			{
			$contador++;
			$habita[$contador]['orden']=intval($tupla_dist['Id_Hab']);
			$habita[$contador]['id']=$tupla_dist['Id_Hab'];
			$habita[$contador]['tipo']=$tupla_dist['Id_Tipo_Hab'];
			$habita[$contador]['nombre_tipo']=$tupla_dist['Nombre_Tipo_Hab'];
			$habita[$contador]['puede_reservar']=$tupla_dist['Reservable'];
			$habita[$contador]['compartida']=$tupla_dist['Compartida'];
			$habita[$contador]['camas']=$max_camas;
			$habita[$contador]['total_camas']=$tupla_dist['Camas_Hab'];
			$habita[$contador]['ocupadas']=array();
			$habita[$contador]['ocupadas']['c'] = 0;
			$habita[$contador]['columnas'] = 1;
			$columnas++;
			$habita[$actual]['camas']-=$max_camas;
			}
		$ult = $contador;
		for($busq = $actual; $busq <= $contador; $busq++)
			$temp[$busq]['camas'] = $habita[$busq]['camas'];

		for($busq = $actual; $busq <= $contador; $busq++)
			{
			$habita[$busq]['camas'] = $temp[$ult]['camas'];
			$ult--;
			}
		for($b = 0; $b <= $a; $b++)	//guardo las fechas en un array temporal
			{
			$temporal['fecha'][$b] = $habita[$actual]['ocupadas']['fecha'][$b];
			$temporal['fecha_s'][$b] = $habita[$actual]['ocupadas']['fecha_s'][$b];
			}
		$reservas_dia[$actual]['fecha'] = array();
		$reservas_dia[$actual]['fecha_s'] = array();
		$actual_camas = $habita[$actual]['ocupadas']['c'];
		$habita[$actual]['ocupadas']['c']=0;
		$puestas = 0;	//pongo las camas ocupadas de columna en columna, para que queden de izq a der
		while($actual_camas > 0)	//mientras queden camas por poner
			{
			for($busq = $actual; $busq <= $contador && $actual_camas != 0; $busq++)	//recorro de la 1º a la ultima columna de la habitacion
				{
				if($habita[$busq]['camas'] != $habita[$busq]['ocupadas']['c'])	//si no se ha llegado al maximo de camas de la columna
					{
					$habita[$busq]['ocupadas']['fecha'][$habita[$busq]['ocupadas']['c']] = $temporal['fecha'][$puestas];
					$habita[$busq]['ocupadas']['fecha_s'][$habita[$busq]['ocupadas']['c']] = $temporal['fecha_s'][$puestas];
					$habita[$busq]['ocupadas']['c']++;	//le pongo una cama mas
					$puestas++;
					$actual_camas--;
					}
				}
			};
			
		//le pongo el numero de columnas a todas las columnas de la habitacion
		for($busq = $actual; $busq <= $contador; $busq++)
			{
			$habita[$busq]['columnas'] = $columnas;
			}
		}
	for ($s=0;$s<count($tipo_Hab);$s)
		{
		if($tupla_dist['Id_Tipo_Hab']!=$tipo_Hab[$s])
			{
			$tipo_Hab[$cont]=$tupla_dist['Id_Tipo_Hab'];
			$cont++;
			}
		}
	$cont++;
	//}
	$contador++;
	}

for($a = 1; $a < count($habita); $a++)
	{
	if($habita[$a-1]['id'] == $habita[$a]['id'])
		$habita[$a-1]['columnas']=$habita[$a]['columnas'];
	}

if(mysql_num_rows($res_dist)!= 0) //comprueba que existen habitaciones;
	{
	$con_cols = 0;
	//busco todos los tipos de habitacion y cuento cuantas habitaciones de cada tipo hay
	
	// SACO LOS ID Y LOS NOMBRES DE LOS TIPOS DE HABITACION
	$sql="(
			SELECT
				tipo_habitacion.Reservable,
				tipo_habitacion.Nombre_Tipo_Hab AS tipo,
				tipo_habitacion.Id_Tipo_Hab AS id
			FROM
				habitacion,
				tipo_habitacion,
				(
					SELECT
						DISTINCT(Id_Hab),
						MAX(Fecha),
						Id_Tipo_Hab
					FROM
						cambio_tipo_habitacion
					WHERE
						Fecha<=".$fechaDeCambio."
					GROUP BY(Id_Hab)
					ORDER BY(Id_Hab)
				)as consulta
			WHERE
				habitacion.Camas_Hab <> -1
				AND
				Nombre_Tipo_Hab<>'Aula'
				AND
				habitacion.Id_Hab =consulta.Id_Hab
				AND
				tipo_habitacion.Id_Tipo_Hab=consulta.Id_Tipo_Hab
			GROUP BY
				tipo_habitacion.Nombre_Tipo_Hab
			ORDER BY 
				(tipo_habitacion.Reservable='S') DESC,
				tipo_habitacion.Id_Tipo_Hab ASC
			LIMIT ".$elLimit.",".$tiposHabAMostrar."
			)";

	$res_tipo=mysql_query($sql);
	$num = mysql_num_rows($res_tipo);

	$tipo_habitacion = array();
	for($d = 0; $d < $num; $d++)	//lo paso a un array para poder ordenarlo
		{
		$fila_res_tipo=mysql_fetch_array($res_tipo);
		$tipo_habitacion[$d]['tipo'] = $fila_res_tipo['tipo'];
		$tipo_habitacion[$d]['id'] = $fila_res_tipo['id'];
		$tipo_habitacion[$d]['reservable']=$fila_res_tipo['Reservable'];
/** ESTO ME PARECE QUE NO VALE PA NADA
		for($h = 0; $h < count($habitaciones_orden); $h++) 	//busco la pagina que le corresponde al tipo de habitacion
			{
			//if($fila_res_tipo['id'] == $habitaciones_orden[$h]['Id_Tipo_Hab']){
			$tipo_habitacion[$d]['pagina'] = $habitaciones_orden[$h]['pagina'];
			//}
			}
*/		
		}

	echo "<tr>";	//la 1º fila es el nombre de los tipos de habitación
	echo "<td rowspan='" . ($max_camas+3) . "'><font size='1'>&nbsp;</font></td>";	//pongo una columna vacia al principio
	$grupos_col = array(); //grupos_col es un array que contiene cuantas columnas tiene cada tipo de habitación
	
// Este for escribe el tipo de Habitacion y pone los separadores	
	for($d = 0; $d < count($tipo_habitacion); $d++)	//pongo la cabezera de la tabla
		{
		if($pagina == $tipo_habitacion[$d]['pagina'])	//si el tipo de habitación esta en la ventana
			{
			$columnas = 0;
			for ($cama=0;$cama<count($habita);$cama++)
				{
				if($habita[$cama]['tipo'] == $tipo_habitacion[$d]['id'])	//cuento cuantas habitaciones hay de este tipo
					{
					$columnas++;
					}
				}
			$grupos_col[$tipo_habitacion[$d]['tipo']] = $columnas; // lo que es el número de columnas de este tipo de habitación
			$con_cols++;
			//pongo una celda con el nombre del tipo de habitación
			echo "<td colspan='" . ($columnas) . "' align='center' style='width:".($columnas*22)."px;'>
					<font class='nom_tipo_hab' style='cursor:default;'>" .$tipo_habitacion[$d]['tipo'];
			if($tipo_habitacion[$d]['reservable']=='N')
				echo " (*)";
			echo "</font></td>";	
			if($d != $num-1)	//y una celda con la linea blanca, si no es el ultimo tipo de habitación a poner
				echo "<td width='22px' class='linea_blanca' rowspan='" . ($max_camas+3) . "' background='../imagenes/img_tablas/linea1.gif'>&nbsp;</td>";
			}
		}

	echo "<td rowspan='" . ($max_camas+3) . "'>
				<font size='1'>&nbsp;</font>
			</td>"; //pongo una columna vacia al final
	echo "</tr>";
	$con_cols ++;

	foreach($habita as $key => $row)	//ordeno el array por tipo de habitacion, por el id, por el número de camas, el el número de camas ocupadas
		{
		$reservable[$key]=$row['puede_reservar'];
		$elId[$key]=$row['id'];
		$orden1[$key]  = $row['orden'];
		$tipo[$key] = $row['tipo'];
		$camas1[$key] = $row['camas'];
		$ocupadas[$key] = $row['ocupadas']['c'];
		}
//	array_multisort ($tipo, SORT_ASC, $orden1, SORT_ASC, $camas1, SORT_DESC, $ocupadas, SORT_DESC, $habita);
//	array_multisort ($tipo, SORT_ASC,$elId, SORT_ASC,$orden1, SORT_ASC, $camas1, SORT_DESC, $ocupadas, SORT_DESC, $habita);
	array_multisort ($reservable,SORT_DESC,$tipo, SORT_ASC,$orden1, SORT_ASC,$elId, SORT_ASC, $camas1, SORT_DESC, $ocupadas, SORT_DESC, $habita);

	$nombres_habitacion = array(); //guardo los nombres de las habitaciones que se van poniendo
	$num_text = 0;
	$contadores = array(count($habita));

	for ($cama = 0; $cama < count($habita) && $habita[$cama]['id'] != ""; $cama++) //creo un contador para cada habitacion
		$contadores[$habita[$cama]['id']]=1;
	//Recorremos cada una de las filas posibles
	for ($fila=0;$fila<=$max_camas;$fila++)
		{echo "<tr>";
		//Recorremos las camas de cada fila
		for ($cama=0;$cama<count($habita) && $habita[$cama]['id'] != "";$cama++)
			{
			for($h = 0; $h < count($tipo_habitacion); $h++) 	//busco la pagina que le corresponde al tipo de cama
				{
				if($tipo_habitacion[$h]['id'] == $habita[$cama]['tipo'])
					$pagina_cama = $tipo_habitacion[$h]['pagina'];
				}
			if($pagina == $pagina_cama) //si se quiere mostrar todas las habitaciones quitar la 2º condición
				{
				if($fila==0)//Para la primera fila, metemos en Id_Hab
					{
					if(!in_array($habita[$cama]['id'],$nombres_habitacion)) //si no se ha puesto todavia el nombre de la habitacion
						{
						$nombres_habitacion[count($nombres_habitacion)] = $habita[$cama]['id'];
						echo ('<td class="nom_hab" align="center" colspan="'.$habita[$cama]['columnas'].'">'.$habita[$cama]['id'].'</td>');
						}
					}
				else
					{//Para el resto de filas, metemos un estilo dependiendo del estado de la habitación
					$contador=$contadores[$habita[$cama]['id']];
					$contadores[$habita[$cama]['id']]++;
					if ($habita[$cama]['ocupadas']['c']>0)
						{
	                    echo "<td class=\"cama_ocupada\">&nbsp;";
    	                echo "</td>";
	                    $habita[$cama]['ocupadas']['c']--;
						$habita[$cama]['camas']--;
						}
					else
						{
						if ($habita[$cama]['camas']>0) //la cama esta libre todos los días
							{
							echo "<td class='cama_libre'>&nbsp;";
							echo "</td>";
							$habita[$cama]['camas']--;
							}
						else							
							echo "<td class=\"no_cama\">&nbsp;</td>";
						}
					}
				}
			}
		echo "</TR>";
		}

	echo "<tr>";
	foreach ($grupos_col as $key => $row)
		echo ("<td colspan='" . $row . "'><font size='1'>&nbsp;</font></td>"); //pongo una fila vacia al final
	echo "</tr>";
	}
else
	{	//si no se encuentran habitaciones se muestra un mensaje al usuario
	echo "<tr><td align='left><font class='nom_hab'>No se puede mostrar el mapa de habitaciones, compruebe que hay habitaciones introducidas en el sistema.</font></td></tr>";
	}
?>

		<input name="num_text" id="num_text" type="hidden" value="<?=$num_text?>">		<!-- guarda el numero de camas que se pueden asignar -->
		<input name="hab_selec" id="hab_selec" type="hidden" value="<?=$_POST['hab_selec']?>">	<!-- guarda las camas seleccionadas de los días que se han visto -->
		<input name="fec_selec" id="fec_selec" type="hidden" value="<?=$_POST['fec_selec']?>">	<!-- guarda los días que se han visto -->
		<input name="es_inc_i" id="es_inc_i" type="hidden" value="0">			<!-- guarda el numero de camas individuales seleccionadas que son incompletas -->
		<input name="es_inc_c" id="es_inc_c" type="hidden" value="0">			<!-- guarda el numero de camas compartidas seleccionadas que son incompletas -->
		<input name="ir_a_dia" id="ir_a_dia" type="hidden" value="<?=$_POST['ir_a_dia']?>">	<!-- guarda las fechas de las camas incompletas, para ir a ellas automaticamente -->
		<script>
			var fal_in = 0; //indica el número de camas individuales asignadas anteriormente y que ahora no se pueden asignar
			var fal_co = 0; //indica el número de camas compartidas asignadas anteriormente y que ahora no se pueden asignar
			var cambi = 0; //indica si alguno de los dos valores anteriores cambia de valor
			cambiar_camas = 0;
		</script>
<?php
		$fechas = split("\+",$_POST['fec_selec']); //array con todas las fechas que se han visto
		$habita = split("\*",$_POST['hab_selec']); //array con todos las camas que se han asignado
		$tem = strftime("%d/%m/%Y",$fecha_sel);
		$enc = -1;
		$pos = -1;

		$ult = $fechas[count($fechas)-2];
		for($i = 0; $i <= count($fechas)-1; $i++) //recorro el array de fechas mirando si la ultima fecha esta repetida
			{
			if($fechas[$i] == $ult)
				{
				$enc ++;
				if($pos == -1)
					$pos = $i;
				}
			}
		if($enc == 1) //si la ultima fecha esta repetida
			{
			$cad = "";
						//recorro los arrays de fechas y habitaciones dejando la cadena de forma que las demas fechas quedan igual,
						// pero la que se repite se pone la ultima posicion, y esta se quita
			for($i = 0; $i < count($habita)-2; $i++)
				{
				if($i != $pos)
					$cad = $cad . $habita[$i];
				else
					$cad = $cad . $habita[count($habita)-2];
				$cad = $cad . "*";
				}
			echo "<script>document.getElementById('hab_selec').value='" . $cad . "'</script>";
			$cad="";
			for($i = 0; $i < count($fechas)-2; $i++)
				{
				if($i != $pos)
					$cad = $cad . $fechas[$i];
				else
					$cad = $cad . $fechas[count($fechas)-2];
				$cad = $cad . "+";
				}
			echo "<script>document.getElementById('fec_selec').value='" . $cad . "'</script>";
			}
		//--esto sirve para mantener las camas seleccionadas anteriormente--
		$enc = -1;		//compruebo si la fecha que se esta viendo esta en el array de fechas
		for($i = 0; $i < count($fechas); $i++)
			{
			if($fechas[$i] == $tem)
				$enc = $i;
			}
		if($enc != -1) // si se encuentra se toma su posicion
			$ele = $enc;
		else
			{
			$ele = count($fechas); // si no se toma la posicion del ultimo elemento
			if($habita[$ele] == "")
				$ele -= 1;
			}

		if($ele >= 0)
			{
			$c_ante = split("-",$habita[$ele]);	//array que contiene todas las camas seleccionadas de la posicion
			for($i = 0; $i < count($c_ante)-1; $i++)
				{
				$nom = split("&",$c_ante[$i]); //corto el nombre de la cama
?>
				<script language="JavaScript">
					cambi = 1; //si llega aqui es que las variables de camas incompletas asignadas anteriormente cambia
					maxi = <?=$max_camas;?>; //maximo de camas
					habit = '<?=$nom[0]?>';
					cama = <?=$nom[1]?>;
					var exi = (document.getElementById("td"+habit+"_"+cama)) ? true:false; //dice si existe o no un elemento
					if(exi)//si existe la celda correspondiente a la cama
						{
						enc = 0;
						puesto = 1; //se indica que se va a poner
						i = 0;
						for(i; i < habit_sel.length && enc == 0; i++) //recorro el array de habitaciones y su tipo
							{
							if(habit_sel[i][0] == habit)
								{
								enc = 1;
								if(habit_sel[i][2] == "N") //si el tipo en individual se aumenta el uno el contador de camas indidivuales incompletas asignadas
									document.getElementById('es_inc_i').value = eval(document.getElementById('es_inc_i').value) + 1;
								else //si no se aumenta el uno el contador de camas compartidas incompletas asignadas
									document.getElementById('es_inc_c').value = eval(document.getElementById('es_inc_c').value) + 1;
								}
							}
						document.getElementById("td"+habit+"_"+cama).className = "cama_asignada"; //se le pone que color verde
						document.getElementById(habit+"-"+cama).value='1';	//y se pone su hidden a 1 para indicar que se ha seleccionado
						}
					else
						{ //si todas las camas de la habitación estan ocupada o ya están en verde se indica que falta asignar una cama
						enc = 0;
						i = 0;
						//echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
						var exi = (document.getElementById(habit+"-"+cama)) ? true:false; //dice si existe o no un elemento
						if(exi && document.getElementById(habit+"-"+cama).value == "ocupada")
							{
							for(i; i < habit_sel.length && enc == 0; i++)//recorro el array de habitaciones y su tipo
								{
								if(habit_sel[i][0] == '<?=$nom[0]?>')
									{
									enc = 1;
									if(habit_sel[i][2] == "N") //si es de tipo individual se indica que falta una cama individual mas por asignar
										fal_in ++;
									else	//si no se indica que falta una cama compartidas mas por asignar
										fal_co ++;
									cambiar_camas = 1;
									}
								}
							}
						}
				</script>
<?php
				}
			}
		//--aqui acaba el codigo para mantener las camas seleccionadas
?>
			</table>
 <div align='right' style='margin-top:30px;margin-right:150px;'>
						<?php
						if(isset($_GET['fecha_cal']))
							$laFecha="&fecha_cal=".$_GET['fecha_cal'];
						else
							$laFecha="";
						if($elLimit>=$tiposHabAMostrar)
							{
						?>
							<img src='../imagenes/botones/anterior.gif' onclick='location.href="disponibilidad_online.php?limit=<?php echo ($elLimit-$tiposHabAMostrar).$laFecha;?>";' style='cursor:pointer' title='Anterior' />
							<?php
							}
						else
							{
					?>
							<img src='../imagenes/botones/anterior.gif' style='filter:alpha(opacity=30);-moz-opacity:0.3;cursor:not-allowed;' />
					<?php
							}
						$cons=mysql_query("
select 
	count(distinct(tipo_habitacion.id_tipo_hab))
from
	tipo_habitacion,
	(
	select
		distinct(Id_Hab),
		max(Fecha),
		Id_Tipo_Hab									
	from
		cambio_tipo_habitacion
	where
		fecha<=".$fechaDeCambio."
	group by
		Id_Hab
	)as consulta
where
	consulta.id_tipo_hab=tipo_habitacion.id_tipo_hab");
						$filaCons=mysql_fetch_row($cons);
						if($filaCons[0]>$elLimit+$tiposHabAMostrar)
							{
					?>
							<img src='../imagenes/botones/siguiente.gif' onclick='location.href="disponibilidad_online.php?limit=<?php echo ($elLimit+$tiposHabAMostrar).$laFecha;?>";' style='cursor:pointer' title='Siguiente' />
					<?php
							}
					else
							{
					?>
							<img src='../imagenes/botones/siguiente.gif' style='filter:alpha(opacity=30);-moz-opacity:0.3;cursor:not-allowed;' />
					<?php
							}
					?>
					</div>


<!----------------------------------------------Fin Disponibilidad Habitaciones-------------------------------------------------------------->
					
	<div style='display:inline;'>			
	<div style='display:inline;float:left;width:58%;margin-top:-50px'>				
		<fieldset style="width:350px;text-align:center;border-color:#E2A539;">
          <legend align="left"><font color="#C40056"><b>LEYENDA:</b></font></legend>
          <table align="center"  >
            <!--Tabla para leyenda-->
            <tr>
	              <td bgcolor="#C40056" width='20' height='20'></td>
    	          <td><b><font size="3">&nbsp;&nbsp;Ocupado</font></b></td>
        	      <td></td>
            	  <td bgcolor="#FFFFFF" width="20" height="20"></td>
	              <td><b><font size="3">&nbsp;&nbsp;Libre</font></b></td>
	              <td></td>
            	  <td bgcolor="#67720C" width="20" height="20" style="color:rgb(255,255,255);font-weight:bold;">(*)</td>
	              <td><b><font size="3">&nbsp;&nbsp;No Reservable</font></b></td>
            </tr>
          </table>
        </fieldset>
    </div>
    <div class='cale_container' id='cale_container' style='display:inline;padding:0px;float:right;width:38%;'>
                    <SCRIPT type="text/javascript" src="capas.js"></script>




                    
                  </div>
    </div>
    
	
				</FORM>

	

				
			</div>


                <!-- InstanceEndEditable --> 
                  </td>
              </tr>
              <tr> 
                <td height="35" colspan="2" align="center"> <font size="-1"> [:: 
                  <a href="../index.html">Inicio</a> ::] [:: Informaci&oacute;n 
                  [: <a href="info_juvenil_online.php">Alberguista Juvenil</a> 
                  :] - [: <a href="info_pere_online.php">Alberguista Peregrino</a> 
                  :] - [: <a href="servicios_online.php">Servicios</a> :] - [: 
                  <a href="accesos_online.php">Accesos</a> :] ::] <br>
                  [:: <a href="disponibilidad_online.php">Disponibilidad</a> ::] 
                  [:: Reservas On-Line [: <a href="reserva_online.php">Nueva</a> 
                  :] - [: <a href="anular_reserva_online.php">Eliminar</a> :] 
                  ::] [:: <a href="tarifa_listado_online.php">Tarifas</a> ::] 
                  </font> </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
</td></tr></table>
<?
mysql_close($db);
?>
</body>
<!-- InstanceEnd --></html>
