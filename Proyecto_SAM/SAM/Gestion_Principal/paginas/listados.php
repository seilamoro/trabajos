<?php
	if(isset($_GET['imprimir']) && $_GET['imprimir']==true) // Si estamos en la version imprimible de la pagina, cargo la funcion para imprimir y la hoja de estilos propia de la pagina
		{

		session_start();
		echo "<html>
			<head>
				<title>Listado de Pernoctas y Reservas (Vista Previa)</title>
				<link rel='stylesheet' type='text/css' href='../css/listado_actual.css'>
				<script>
					/**
					* Función para que salga la opción de imprimir
					*/
				  	function imprimir()
						{
						version = parseInt(navigator.appVersion);
						if (version >= 4)
							window.print();
					  	}
				</script>
			</head>
			<body>";
			}
	else
		echo "<link rel='stylesheet' type='text/css' href='css/listado_actual.css'>";

	// Conexion a la Base de Datos
	@$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }
    mysql_select_db($_SESSION['conexion']['db']);

	/**
    *  dateAdd(int)
    *  Función que calcula una fecha futura según la cantidad de días que se le proporcionen como argumento
	*/
	function dateAdd($dias)
		{
		$mes = date("m");
		$anio = date("Y");
		$dia = date("d");
		$ultimo_dia = date( "d", mktime(0, 0, 0, $mes + 1, 0, $anio) ) ;
		$dias_adelanto = $dias;
		$siguiente = $dia + $dias_adelanto;
		if($ultimo_dia < $siguiente)
			{
			$dia_final = $siguiente - $ultimo_dia;
			$mes++;
			if ($mes == '13')
				{
				$anio++;
				$mes = '01';
				}
			return date("Y-m-d",mktime(0,0,0,$mes,$dia_final,$anio));
			}
		else
			return date("Y-m-d",mktime(0,0,0,$mes,$siguiente,$anio));    
		}

	/**
	* Esta funcion recibe una fecha en el formato AAAA-MM-DD y la devuelve en el formato DD-MM-AAAA
	*/
	function formatearFecha($fechaOriginal)
		{
		$fechaNueva=split("-",$fechaOriginal);
		$fechaNueva=$fechaNueva[2]."-".$fechaNueva[1]."-".$fechaNueva[0];
		return $fechaNueva;
		}
		
	if(isset($_GET['siguienteDia']) && $_GET['siguienteDia']=true) // Si se ha pulsado en el boton de Datos de Mañana
		$fecha_hoy = dateAdd(1); // Obtengo la fecha de mañana
	else
		$fecha_hoy = dateAdd(0); // Obtengo la fecha de hoy

	if(!isset($_GET['imprimir']) && $_GET['imprimir']!=true) // Si estamos en la version NO IMPRIMIBLE de la pagina muestro el gorro con la fecha del dia elegido
		echo "<div class='champi_izquierda'>&nbsp;</div>
			<div class='champi_centro' id='divTituloTabla' style='width:1130px;'>
				<div class='titulo' style='text-align: right'>Fecha ".formatearFecha($fecha_hoy)."</div>
			</div>
			<div class='champi_derecha'>&nbsp;</div>
			<div id='divGeneralListado' class='tabla_detalles'>";
	else // Si estamos en la version imprimible de la pagina, solo pongo la fecha sin estilo ninguno
		echo "<div align='right'>Fecha ".formatearFecha($fecha_hoy)."</div>";
?> 
<div id="divFieldset">
			<fieldset>
				<legend>LISTADO DE ALBERGUISTAS</legend>
				<center>
<?php						
/***************************************** DATOS DE LOS ALBERGUISTAS ************************************************/

// Consulta que selecciona datos de pernoctas de alberguistas hechas en cada habitacion con fecha anterior a la de hoy

		$query_pernoctas="(select DNI_Cl,Fecha_Llegada,Fecha_Salida,Noches_Pagadas,Id_Hab from pernocta where fecha_llegada<='".$fecha_hoy."' and Fecha_Salida>='".$fecha_hoy."') order by Id_Hab ASC,Fecha_Salida ASC;";
		$result_pernoctas = mysql_query($query_pernoctas); 	    	
		$num_results_pernoctas=mysql_num_rows($result_pernoctas);
		if($num_results_pernoctas>0)
			{
			for($j=0;$j<$num_results_pernoctas;$j++)
				{
				$fila_pernoctas=mysql_fetch_array($result_pernoctas);
				$dni_cl=$fila_pernoctas['DNI_Cl'];
				// Consulta que selecciona todos los datos de la persona que ha pernoctado cuyo dni es dni_cl
				$query_cliente="select * from cliente where DNI_Cl='".$dni_cl."';";
				$result_cliente = mysql_query($query_cliente); 	    	
    			$num_results_cliente=mysql_num_rows($result_cliente);												
				$fila_cliente=mysql_fetch_array($result_cliente);
				// Consulta para obtener el Ingreso que ha realizado un alberguista
				$query_ingreso="select Ingreso from pernocta where DNI_Cl='".$fila_pernoctas['DNI_Cl']."' and Fecha_Llegada='".$fila_pernoctas['Fecha_Llegada']."'";
				$result_ingreso = mysql_query($query_ingreso); 	    	
    			$num_results_ingreso=mysql_num_rows($result_ingreso);
				$fila_ingreso=mysql_fetch_array($result_ingreso);
					
?>
					<table id="tablaClientes" cellspacing="0">
						<tr>
							<td id="nombreCliente"><?php echo  $fila_cliente['Nombre_Cl']." ".$fila_cliente['Apellido1_Cl']." ".$fila_cliente['Apellido2_Cl']; ?></td>
						</tr>
					</table>
					<table id="tablaClientes" cellspacing="0">
						<tr>
							<td>
								<table id="tablaDetallesCliente" cellspacing="0">
									<tr>
										<td colspan="2">&nbsp;Habitación : <?php echo $fila_pernoctas['Id_Hab'];?></td>
									</tr>
									<tr>																					
										<td>&nbsp;<?php echo "Fecha Llegada : ".formatearFecha($fila_pernoctas['Fecha_Llegada']); ?>&nbsp;</td>
										<td>&nbsp;<?php echo "Fecha Salida : ".formatearFecha($fila_pernoctas['Fecha_Salida']); ?>&nbsp;</td>
									</tr>
									<tr>									
										<td>&nbsp;<?php echo "Noches Pagadas : ".$fila_pernoctas['Noches_Pagadas']; ?>&nbsp;</td>
										<td>&nbsp;<?php if($num_results_ingreso>0){echo "Ingreso : ".$fila_ingreso['Ingreso'];}else{echo "0.00";}?>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;Edad : <?php echo $fecha_hoy-$fila_cliente['Fecha_Nacimiento_Cl'];?>&nbsp;</td>
										<td>&nbsp;Fecha Nacimiento : <?php echo formatearFecha($fila_cliente['Fecha_Nacimiento_Cl']);?>&nbsp;</td>
									</tr>
<?php
				// Consulta para obtener las taquillas ocupadas por el alberguista
				$datos_taquillas=mysql_query("select Id_Taquilla,Id_Hab from taquilla where DNI_Cl='".$fila_pernoctas['DNI_Cl']."'");
				if(mysql_num_rows($datos_taquillas)>0)
					{
									echo "<tr style='background-color:rgb(240,240,240);'>
											<td colspan='2' style='font-weight:bold;'> Taquillas Ocupadas </td>
										</tr>
										<tr style='background-color:rgb(240,240,240);'>
											<td id='titulosCeldasTablaTaquilla' style='border-right:1px solid rgb(140,140,140);'> Habitación </td>
											<td id='titulosCeldasTablaTaquilla'> Nº Taquilla </td>
										</tr>";
					for($t=0;$t<mysql_num_rows($datos_taquillas);$t++)
						{
						$fila_datos_taquilla=mysql_fetch_array($datos_taquillas);														
									echo "<tr>
											<td style='text-align:center;border-right:1px solid rgb(140,140,140);'>".$fila_datos_taquilla['Id_Hab']."</td>
											<td style='text-align:center;'>".$fila_datos_taquilla['Id_Taquilla']."</td>
										</tr>";
						}
					}
?>
								</table>
							</td>
						</tr>
					</table>
<?php
				} // Cierra el for($j=0;$j<$num_results_pernoctas;$j++)
			} // Final del if($num_results_pernoctas>0)
		else
			echo "NO HAY PERNOCTAS DE ALBERGUISTAS";
?>
			</center>
		</fieldset>
		
		<fieldset>
				<legend>LISTADO DE PEREGRINOS</legend>
				<center>		

<?php
/******************************************************************************************************************/

/**************************************** DATOS DE LOS PEREGRINOS *************************************************/

// Consulta que selecciona datos de pernoctas de peregrinos hechas en cada habitacion con fecha anterior a la de hoy
		$query_pernoctas="(select DNI_Cl,Fecha_Llegada,Fecha_Salida,Noches_Pagadas,Id_Hab  from pernocta_p where  fecha_llegada<='".$fecha_hoy."' and Fecha_Salida>'".$fecha_hoy."') order by Id_Hab ASC,Fecha_Salida ASC;";
		$result_pernoctas = mysql_query($query_pernoctas); 	    	
		$num_results_pernoctas_pereg=mysql_num_rows($result_pernoctas);
		if($num_results_pernoctas_pereg>0)
			{
			for($j=0;$j<$num_results_pernoctas_pereg;$j++)
				{
				$fila_pernoctas=mysql_fetch_array($result_pernoctas);
				$dni_cl=$fila_pernoctas['DNI_Cl'];
				// Consulta que selecciona todos los datos de la persona que ha pernoctado en una habitacion cuyo dni es dni_cl
				$query_cliente="select * from cliente where DNI_Cl='".$dni_cl."';";
				$result_cliente = mysql_query($query_cliente); 	    	
    			$num_results_cliente=mysql_num_rows($result_cliente);												
				$fila_cliente=mysql_fetch_array($result_cliente);
?>
					<table id="tablaClientes" cellspacing="0">
						<tr>
							<td id="nombreCliente"><?php echo  $fila_cliente['Nombre_Cl']." ".$fila_cliente['Apellido1_Cl']." ".$fila_cliente['Apellido2_Cl']; ?></td>
						</tr>
					</table>
					<table id="tablaClientes" cellspacing="0">
						<tr>
							<td>
								<table id="tablaDetallesCliente" cellspacing="0">
									<tr>
										<td align="left">&nbsp;Habitación : <?php echo $fila_pernoctas['Id_Hab'];?></td>
										<td align="left">&nbsp;Fecha Llegada : <?php echo formatearFecha($fila_pernoctas['Fecha_Llegada']);?>&nbsp;</td>
									</tr>
									<tr>
										<td align="left">&nbsp;Edad : <?php echo $fecha_hoy-$fila_cliente['Fecha_Nacimiento_Cl'];?>&nbsp;</td>
										<td align="left">&nbsp;Fecha Nacimiento : <?php echo formatearFecha($fila_cliente['Fecha_Nacimiento_Cl']);?>&nbsp;</td>
									</tr>
						
<?php
				// Consulta para obtener las taquillas ocupadas por el peregrino
				$datos_taquillas=mysql_query("select Id_Taquilla,Id_Hab from taquilla where DNI_Cl='".$fila_pernoctas['DNI_Cl']."'");
				if(mysql_num_rows($datos_taquillas)>0)
					{
									echo "<tr style='background-color:rgb(240,240,240);'>
											<td align='left' style='font-weight:bold;'> Taquillas Ocupadas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td></td>
										</tr>
										<tr style='background-color:rgb(240,240,240);'>
											<td id='titulosCeldasTablaTaquilla' style='border-right:1px solid rgb(140,140,140);'> Habitación </td>
											<td id='titulosCeldasTablaTaquilla'> Nº Taquilla </td>
										</tr>";					
					for($t=0;$t<mysql_num_rows($datos_taquillas);$t++)
						{
						$fila_datos_taquilla=mysql_fetch_array($datos_taquillas);
									echo "<tr>
											<td style='text-align:center;border-right:1px solid rgb(140,140,140);'>".$fila_datos_taquilla['Id_Hab']."</td>
											<td style='text-align:center;'>".$fila_datos_taquilla['Id_Taquilla']."</td>
										</tr>";
						}
					}
?>
								</table>
							</td>
						</tr>
					</table>
<?php
				} // Cierra el for($j=0;$j<$num_results_pernoctas_pereg;$j++)
			} // Final del if($num_results_pernoctas_pereg>0)
		else
			echo "NO HAY PERNOCTAS DE PEREGRINOS";
/*********************************************************************************************************************/			
		
?>

			</center>
			</fieldset>
		</div>
		<div id="divFieldset">


<?php

/************************************************ DATOS DE LOS GRUPOS ************************************************/
echo "<fieldset>
		<legend>LISTADO DE GRUPOS</legend>
		<center>";
			// Consulta para obtener los datos de los grupos que estan actualmente en el albergue
			$sqlGrupos="select distinct(pernocta_gr.Nombre_Gr),estancia_gr.Fecha_Llegada,estancia_gr.Fecha_Salida,estancia_gr.Ingreso,estancia_gr.Noches_Pagadas from pernocta_gr,estancia_gr where estancia_gr.fecha_llegada<='".$fecha_hoy."' and pernocta_gr.Nombre_Gr=estancia_gr.Nombre_Gr and estancia_gr.Fecha_Salida>='".$fecha_hoy."' order by estancia_gr.Fecha_Salida ASC";
			$result_pernoctas_grupos=mysql_query($sqlGrupos);
			if(mysql_num_rows($result_pernoctas_grupos)>0)
				{
				for($j=0;$j<mysql_num_rows($result_pernoctas_grupos);$j++)
					{
					$fila_grupos=mysql_fetch_array($result_pernoctas_grupos);
					echo "<table>
							<tr>
								<td id='nombreCliente'>".$fila_grupos['Nombre_Gr']."</td>
							</tr>
						</table>
						<table id='tablaClientes' cellspacing='0'>
<tr><td><table style='border:1px solid rgb(24,24,24);' cellpadding='0' cellspacing='0'>
							<tr>
								<td align='left'>&nbsp;Fecha Llegada : ".formatearFecha($fila_grupos['Fecha_Llegada'])."</td>
								<td>&nbsp;&nbsp;</td>
								<td>Fecha Salida : ".formatearFecha($fila_grupos['Fecha_Salida'])."&nbsp;</td>
							</tr>
							<tr>
								<td align='left'>&nbsp;Ingreso : ".$fila_grupos['Ingreso']."</td>
								<td>&nbsp;&nbsp;</td>
								<td>Noches Pagadas : ".$fila_grupos['Noches_Pagadas']."&nbsp;</td>
							</tr>
							<tr>
								<td colspan='3'>
									<table id='tablaEdadesHabitaciones' cellspacing='0' align='left' width='100%'>
										<tr style='background-color:rgb(240,240,240);'>
											<td id='celdaTitulosEdadesHabitaciones' style='border-right:1px solid rgb(140,140,140);'>Habitación</td>
											<td id='celdaTitulosEdadesHabitaciones' style='border-right:1px solid rgb(140,140,140);'>Edades</td>
											<td id='celdaTitulosEdadesHabitaciones'>Nº Personas</td>
										</tr>";
					// Consulta para obtener los diferentes rangos de edades de un grupo que hay en cada habitacion
					$datos_grupos=mysql_query("select edad.Nombre_Edad,pernocta_gr.Id_Hab,pernocta_gr.Num_Personas from pernocta_gr,edad where pernocta_gr.Nombre_Gr='".$fila_grupos['Nombre_Gr']."' and pernocta_gr.Fecha_Llegada='".$fila_grupos['Fecha_Llegada']."' and edad.Id_Edad=pernocta_gr.Id_Edad order by pernocta_gr.Id_Hab");
					for($a=0;$a<mysql_num_rows($datos_grupos);$a++)
						{
						$fila_datos_grupos=mysql_fetch_array($datos_grupos);
									echo "<tr>
											<td style='text-align:center;border-right:1px solid rgb(140,140,140);'>".$fila_datos_grupos['Id_Hab']."</td>
											<td style='text-align:center;border-right:1px solid rgb(140,140,140);'>".$fila_datos_grupos['Nombre_Edad']."</td>
											<td style='text-align:center;'>".$fila_datos_grupos['Num_Personas']."</td>
										</tr>";
						}
					// Consulta para obtener las taquillas ocupadas por un grupo
					$datos_taquillas=mysql_query("select Id_Taquilla,Id_Hab from taquilla where Nombre_Gr='".$fila_grupos['Nombre_Gr']."'");
					if(mysql_num_rows($datos_taquillas)>0)
						{
									echo "<tr style='background-color:rgb(240,240,240);'>
											<td colspan='3' align='left' style='font-weight:bold;'>Taquillas Ocupadas</td>
										</tr>
										<tr style='padding:0px 0px 0px 0px;'>
											<td colspan='3' style='padding:0px 0px 0px 0px;'>
												<table style='width:100%;padding:0px 0px 0px 0px;' cellspacing='0'>
													<tr style='background-color:rgb(240,240,240);'>
														<td id='titulosCeldasTablaTaquilla' style='width:50%;border-right:1px solid rgb(140,140,140);'>Habitación</td>
														<td id='titulosCeldasTablaTaquilla' style='width:50%;'>Nº Taquilla</td>
													</tr>";


						for($b=0;$b<mysql_num_rows($datos_taquillas);$b++)
							{
							$fila_datos_taquillas=mysql_fetch_array($datos_taquillas);
											echo "<tr>
														<td style='width:50%;text-align:center;border-right:1px solid rgb(140,140,140);'>".$fila_datos_taquillas['Id_Hab']."</td>
														<td style='width:50%;text-align:center;'>".$fila_datos_taquillas['Id_Taquilla']."</td>
													</tr>";
							}
										echo "</table>
											</td>
										</tr>";
						}
								echo "</table>
									</td>
								</tr>
</table></td></tr>
							</table>";
					}
				}
			else
				echo "NO HAY PERNOCTAS DE GRUPOS";
	echo "</center>
	</fieldset>";
?>


		
<?php
/********************************************* DATOS PARA LAS RESERVAS ************************************************/

echo "<fieldset>
		<legend>LISTADO DE RESERVAS</legend>
		<center>";
		// Obtengo los datos de la persona que realizo una reserva
$consulta=mysql_query("select * from pra where DNI_PRA in (select DNI_PRA from detalles where Fecha_Llegada='".$fecha_hoy."')");
$consulta1=mysql_query("select * from detalles where Fecha_Llegada='".$fecha_hoy."' and DNI_PRA in  (select DNI_PRA from pra)");
if(mysql_num_rows($consulta1)>0){
	$fila1=mysql_fetch_array($consulta1);
}
if(mysql_num_rows($consulta)>0){
	for($a=0;$a<mysql_num_rows($consulta);$a++)
		{
		$fila=mysql_fetch_array($consulta);
		echo "<table>
				<tr>
					<td id='nombreCliente'>".$fila['Nombre_PRA']." ".$fila['Apellido1_PRA']." ".$fila['Apellido2_PRA']."</td>				
				</tr>
			</table>";	
			
		echo "<table cellspacing='0' cellpadding='0' id='tablaDetallesCliente'>
				<tr>					
					<td>&nbsp;Fecha Reserva: ".formatearFecha($fila1['Fecha_Reserva'])."&nbsp;</td>					
					<td>&nbsp;Tfno: ".$fila['Tfno_PRA']."&nbsp;</td>";	
		
		
			echo "<tr>
					<td>&nbsp;Email: ".$fila['Email_PRA']."&nbsp;</td>					
				</tr>";
			if($fila1['Observaciones_PRA']){				
			echo "<tr>					
					<td>&nbsp;Observaciones: ".$fila1['Observaciones_PRA']."&nbsp;</td>				
					
				</tr>";
			
			}
		// Consulta para obtener detalles acerca de la reserva (Fecha de Salida, Fecha de la Reserva, Hora de Llegada, etc)			
		$consulta2=mysql_query("select * from detalles where DNI_PRA='".$fila['DNI_PRA']."' and Fecha_Llegada='".$fecha_hoy."' order by Fecha_Salida ASC");
		if(mysql_num_rows($consulta2)>0)
			{
			$fila2=mysql_fetch_array($consulta2);
			echo "<tr>
					<td>&nbsp;Fecha Llegada: ".formatearFecha($fila2['Fecha_Llegada'])."&nbsp;</td>
					<td>&nbsp;Fecha Salida: ".formatearFecha($fila2['Fecha_Salida'])."&nbsp;</td>

					
				</tr>";
			$ponerTR=true;
			if($fila2['Llegada_Tarde']!=null && $fila2['Llegada_Tarde']!="")
				{
				echo "<tr>
						<td>&nbsp;Hora Llegada: ".$fila2['Llegada_Tarde']."&nbsp;</td>";
				$ponerTR=!$ponerTR;
				}
			if($fila2['Ingreso']!=null && $fila2['Ingreso']!="")
				{
				if($ponerTR)
					echo "<tr>";
				echo "<td>&nbsp;Ingreso:".$fila2['Ingreso']."&nbsp;</td>";
				if(!$ponerTR)
					echo "</tr>";
				$ponerTR=!$ponerTR;					
				}
			if($fila2['Nombre_Empleado']!=null && $fila2['Nombre_Empleado']!="")
				{
				if($ponerTR)
					echo "<tr>";
				echo "<td>&nbsp;Empleado: ".$fila2['Nombre_Empleado']."&nbsp;</td>";
				if(!$ponerTR)
					echo "</tr>";
				$ponerTR=!$ponerTR;
				}
			if($fila2['Localizador_Reserva']!=null && $fila2['Localizador_Reserva']!="")
				{
				if($ponerTR)
					echo "<tr>
							<td colspan='2' align='left'>";
				else
					echo "<td>";
				echo "&nbsp;Localizador: ".$fila2['Localizador_Reserva']."&nbsp;</td>";
				if(!$ponerTR)
					echo "</tr>";
				$ponerTR=!$ponerTR;
				}
			}
		// Consulta para obtener las camas que ha reservado en cada habitacion			
		$consulta3=mysql_query("select DNI_PRA,Id_Hab,Num_Camas,Num_Camas_Indiv from reserva where Fecha_Llegada in (select Fecha_Llegada from detalles) and DNI_PRA in (select DNI_PRA from detalles) and Fecha_Llegada='".$fecha_hoy."' and DNI_PRA='".$fila['DNI_PRA']."'");
		if(mysql_num_rows($consulta3)>0)
			{
				echo "<tr>
						<td colspan='2'>
							<table id='tablaHabitacionesReserva' cellspacing='0'>
								<tr style='background-color:rgb(240,240,240);'>
									<td id='titulosCeldasTablaReservasHabitaciones' style='border-right:1px solid rgb(140,140,140);'>Habitación</td>
									<td id='titulosCeldasTablaReservasHabitaciones'>Nº Camas</td>
								</tr>";
			for($i=0;$i<mysql_num_rows($consulta3);$i++)
				{
				$fila3=mysql_fetch_array($consulta3);
					if($fila3['Id_Hab']=='PRA'){
						$lis_id_hab = 'Reserva On Line';
					}else{
						$lis_id_hab = $fila3['Id_Hab'];
					}
							echo "<tr>
									<td style='text-align:center;border-right:1px solid rgb(140,140,140);'>".$lis_id_hab."</td>
									<td style='text-align:center;'>".$fila3['Num_Camas']."</td>
								</tr>";
				}
						echo "</table>
						</td>
					</tr>";
			}
		}
echo "</table>";
	}
else
	echo "NO SE HAN HECHO RESERVAS PARA HOY";
	echo "</center>";
echo "</fieldset>";

echo "</div>";

if(!isset($_GET['imprimir']) && $_GET['imprimir']!=true) // Si no estamos en la version imprimible de la pagina, muestro los botones de imprimir y de cambiar de dia
	{
	echo "</div>
		<div id='divBotonesAbajo'>
			<br>";
	if(isset($_GET['siguienteDia']) && $_GET['siguienteDia']=true)
		echo "<img src='../imagenes/botones-texto/hoy.jpg' border=0  onclick='location.href=\"?pag=listados.php\";' title='Ver Datos de Hoy' style='cursor:pointer' />";
	else
		echo "<td>&nbsp;&nbsp;</td><img src='../imagenes/botones-texto/albayer.jpg' border=0 alt='Ver datos de Alberguistas de ayer' style='cursor:pointer' onclick='location.href=\"?pag=alberguista_ayer.php\"';><td>&nbsp;&nbsp;</td><img src='../imagenes/botones-texto/peregayer.jpg' border=0 style='cursor:pointer' alt='Ver datos de Peregrino de ayer' onclick='location.href=\"?pag=peregrino_ayer.php\"';><td>&nbsp;&nbsp;</td><img src='../imagenes/botones-texto/manana.jpg' border=0  onclick='location.href=\"?pag=listados.php&siguienteDia=true\";' title='Ver Datos de Mañana' style='cursor:pointer' />
		<td>&nbsp;&nbsp;</td><img src='../imagenes/botones-texto/imprimir.jpg' border=0 onclick='window.open(\"paginas/imprimir_estado.php?imprimir=true","\", \"Listado\",\"left=0,top=0,height=1024,width=800,menubar=no,location=no,resizable=yes,scrollbars=yes,status=no\");' style='cursor:pointer' />";
	echo "</div>";
	}
else
	{
	// Llamo a la funcion de imprimir transcurrido 1 milisegundo, porque sino en Mozilla Firefox no se ven los datos del listado
	echo "<script>
			  setTimeout('imprimir()',1);
		</script>
	</body>
	</html>";
	}
	
mysql_close($db);
?>
