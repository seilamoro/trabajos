<?
if (isset($_GET['imprimir'])){
SESSION_START();
}
?>
<SCRIPT LANGUAGE="JavaScript">
			  function imprimir() // Función que imprime la página que llama a la función
				  {
					   version = parseInt(navigator.appVersion);
					   if (version >= 4)
						 window.print();
				  }
</SCRIPT>
<?

		function formatearFecha($fechaOriginal)
		{
		$fechaNueva=split("-",$fechaOriginal);
		$fechaNueva=$fechaNueva[2]."-".$fechaNueva[1]."-".$fechaNueva[0];
		return $fechaNueva;
		}

	@$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }
    mysql_select_db($_SESSION['conexion']['db']);
    
    $fecha_hoy=date("Y-m-d");
    //print($fecha_hoy);
  ?> <center><label style='color:#477CB0;text-align:center;font-family:Verdana;font-size:18px;font-weight:bold;'> Distribución Habitaciones \ Fecha: <?echo formatearFecha($fecha_hoy)?></label></center>
   <?
   $query_habitaciones="select habitacion.*,cambio_tipo_habitacion.* from habitacion inner join cambio_tipo_habitacion on habitacion.Id_Hab=cambio_tipo_habitacion.Id_Hab where Camas_Hab>0 and Id_Tipo_Hab<>4 order by Id_Tipo_Hab";
   //print($query_habitaciones);
   $result_habitaciones= mysql_query($query_habitaciones);
   $num_results_habitaciones=mysql_num_rows($result_habitaciones);
   if($num_results_habitaciones>0){
     
     		//Para Cada habitación hacer las consultas
     		for($j=0;$j<$num_results_habitaciones;$j++)
     		{
			   $fila_habitaciones=mysql_fetch_array($result_habitaciones);
			   $habitaciones[$j]['Id_Hab']=$fila_habitaciones['Id_Hab'];
			   $habitaciones[$j]['camas']=$fila_habitaciones['Camas_Hab'];
			   $habitaciones[$j]['Id_Tipo_Hab']=$fila_habitaciones['Id_Tipo_Hab'];
			}
			
			foreach ($habitaciones as $llave => $fila) {
    				$id[$llave]  = $fila['Id_Hab'];
    				$tipo[$llave] = $fila['Id_Tipo_Hab'];
    			
					}

			
			if (COUNT($habitaciones) > 0) 
			{		
				@ ARRAY_MULTISORT($tipo, SORT_ASC, $id, SORT_ASC, $habitaciones);		
			}
			$tipoactual=$habitaciones[0]['Id_Tipo_Hab'];
			for($j=0;$j<count($habitaciones);$j++)
			{  
			   
			   $query_peregrino="select DNI_Cl,Fecha_Llegada,Fecha_Salida,Noches_Pagadas,Id_Hab  from pernocta_p where  fecha_llegada<='".$fecha_hoy."' and Fecha_Salida>'".$fecha_hoy."' and Id_Hab='".$habitaciones[$j]['Id_Hab']."' order by Id_Hab ASC,Fecha_Salida ASC;";
			   //print($query_peregrino);
    			$result_peregrino = mysql_query($query_peregrino); 	    	
				$num_results_pernoctas_pereg=mysql_num_rows($result_peregrino);
				
				
				$query_alberguista="select * from pernocta where Fecha_Llegada<='".$fecha_hoy."' and Fecha_Salida>'".$fecha_hoy."' and Id_Hab='".$habitaciones[$j]['Id_Hab']."'";
				$result_alberguistas = mysql_query($query_alberguista); 	    	
				$num_results_pernoctas_alb=mysql_num_rows($result_alberguistas);
				//print($query_alberguista);
				//print($num_results_pernoctas_alb);
				
								
				$query_grupo="select pernocta_gr.Id_Hab,pernocta_gr.Num_Personas,estancia_gr.PerNocta,estancia_gr.Id_color,estancia_gr.Fecha_Llegada,estancia_gr.Fecha_Salida,estancia_gr.Ingreso,estancia_gr.Noches_Pagadas,estancia_gr.Nombre_Gr from pernocta_gr inner join estancia_gr on pernocta_gr.Nombre_Gr=estancia_gr.Nombre_Gr where estancia_gr.Fecha_Llegada<='".$fecha_hoy."' and estancia_gr.Fecha_Salida>'".$fecha_hoy."' and pernocta_gr.Id_Hab='".$habitaciones[$j]['Id_Hab']."' and estancia_gr.Fecha_llegada=pernocta_gr.Fecha_Llegada order by estancia_gr.Fecha_Salida ASC ";
				$result_grupo=mysql_query($query_grupo);
				$num_results_pernoctas_grupo=mysql_num_rows($result_grupo);
				//print($query_grupo);
				//print($num_results_pernoctas_grupo);
				
				
				$query_reserva1="select reserva.*,detalles.* from reserva INNER JOIN detalles  ON reserva.DNI_PRA=detalles.DNI_PRA where detalles.Fecha_Llegada<='".$fecha_hoy."' and detalles.Fecha_Salida>'".$fecha_hoy."' and reserva.Id_Hab='".$habitaciones[$j]['Id_Hab']."'";
		
				$result_reserva1=mysql_query($query_reserva1);
				$num_results_pernoctas_reserva1=mysql_num_rows($result_reserva1);
				//print($num_results_pernoctas_reserva1);
				if(	$num_results_pernoctas_reserva1>0)
				{
				$fila_reserva1=mysql_fetch_array($result_reserva1);
				$query_reserva2="select * from pra where DNI_PRA='".$fila_reserva1['DNI_PRA']."'";
				//print($query_reserva2);
		
				$result_reserva2=mysql_query($query_reserva2);
				$num_results_pernoctas_reserva2=mysql_num_rows($result_reserva2);
				//print($num_results_pernoctas_reserva2);
				
				}
			   		   
			   
			  $i=1; 
			   
			   
			   if (isset($_GET['imprimir'])){
			   
			   ?>
			   
			   	<link rel="stylesheet" type="text/css" href="../css/estilos_tablas.css">
				<link rel="stylesheet" type="text/css" href="../css/habitaciones.css">
				<link rel="stylesheet" type="text/css" href="../css/hoja_formularios.css">
				<link rel="stylesheet" type="text/css" href="../css/estructura_alb_per.css">
				<link rel="stylesheet" type="text/css" href="../css/habitacionesColores.css">
				
				<STYLE type="text/css">				
					body{
	
					background-color:#ffffff;	
				
					}
				</style>			   
				<?}
				
				//print("tipoant:".$tipoactual." tipoac:".$habitaciones[$j]['Id_Tipo_Hab']);
				if(!($tipoactual==$habitaciones[$j]['Id_Tipo_Hab']))
					{
					  $tipoactual=$habitaciones[$j]['Id_Tipo_Hab'];
					  print("<HR>");
					}?>
			   <center>
			   
			<table border=0  class="tabla_detalles" style="width:900px;border:3px solid #3F7BCC;font-family:Verdana;font-size:11;text-align:center;">
				<tr>
					<td width="20px" height="20px" style='color:#477CB0;text-align:center;font-family:Verdana;font-size:12px;font-weight:bold;' rowspan="<? print ($habitaciones[$j]['camas']); ?>"><?print($habitaciones[$j]['Id_Hab'])?>
					</td>
				</tr>
				<tr>
			   		<td>
			   <table border=1 width="100%;" height="100%" >	   		
			   				
			   				<?
							   //Mostrar Peregrinos
							   if($num_results_pernoctas_pereg>0)
							   {
							     
							     for($k=0;$k<$num_results_pernoctas_pereg;$k++)
									{
	 									$fila_pernoctas=mysql_fetch_array($result_peregrino);
										$dni_cl=$fila_pernoctas['DNI_Cl'];
										$query_cliente="select * from cliente where DNI_Cl='".$dni_cl."';";
										$result_cliente = mysql_query($query_cliente); 	    	
    									$num_results_cliente=mysql_num_rows($result_cliente);												
										$fila_cliente=mysql_fetch_array($result_cliente);
										if(isset($dni_cl))
										{
										  ?>
										
								 			
			   								<td width="800px;" height="px" style="font-family:Verdana;font-size:9px;">
			   									<table border=0 width="100%" style="font-family:Verdana;font-size:9px;">
								 
								 <?	
										print("<tr><td  align='left' width='250px'><b>".$fila_cliente['Nombre_Cl']." ".$fila_cliente['Apellido1_Cl']." ".$fila_cliente['Apellido2_Cl']."</b></td>");
										print("<td align='left' width='75px'>".formatearFecha($fila_pernoctas['Fecha_Llegada'])." </td><td align='left' width='75px'> ".formatearFecha($fila_pernoctas['Fecha_Salida'])."</td></tr></table></td>");
										$i++;
										}										
	  								}							     
								}
								//Mostrar Grupos
								if($num_results_pernoctas_grupo>0)
								{
									for($m=0;$m<$num_results_pernoctas_grupo;$m++)
										{
											$fila_grupos=mysql_fetch_array($result_grupo);
											if(isset($fila_grupos['Nombre_Gr']))
											{
											  for($g=0;$g<$fila_grupos['Num_Personas'];$g++){
											    
											  ?>
			   									<td width="800px;" height="px" style="font-family:Verdana;font-size:9px;">
			   										<table border=0 width="100%" style="font-family:Verdana;font-size:9px;">
								 								 
								 <?
											  
											print("<tr><td  align='left' width='250' style='color:".$fila_grupos['Id_color'].";'><b>".$fila_grupos['Nombre_Gr'])."</b></td>";
											print("<td align='left' width='75px' >".formatearFecha($fila_grupos['Fecha_Llegada'])."</td><td align='left' width='75px'>".formatearFecha($fila_grupos['Fecha_Salida'])."</td>");
											if($fila_grupos['Ingreso']==0)
												{
											print("<td align='left' width='60px'></td> &nbsp<td align='left' width='60px'> ".$fila_grupos['Noches_Pagadas']."/".$fila_grupos['PerNocta']."</td></tr></table></td>");
											}else {
											print("<td align='left' width='60px'>".$fila_grupos['Ingreso']."€</td><td align='left' width='60px'> ".$fila_grupos['Noches_Pagadas']."/".$fila_grupos['PerNocta']."</td></tr></table></td>");  
											}
											$i++;
											}
										}
								}
							
							}	
								//Mostrar Alberguistas
								if($num_results_pernoctas_alb>0)
									{
									  
										for($l=0;$l<$num_results_pernoctas_alb;$l++)
										{
										$fila_pernoctas=mysql_fetch_array($result_alberguistas);
										$dni_cl=$fila_pernoctas['DNI_Cl'];
										
									
										$query_cliente="select * from cliente where DNI_Cl='".$dni_cl."'";
										$result_cliente = mysql_query($query_cliente); 
										$num_results_cliente=mysql_num_rows($result_cliente);												
										$fila_cliente=mysql_fetch_array($result_cliente);
										
										if(isset($dni_cl))
											{
											  ?>										 
										  								 			
			   								<td width="800px;" height="px" style="font-family:Verdana;font-size:9px;">
			   									<table border=0 width="100%" style="font-family:Verdana;font-size:9px;">
			   									<?										
												print("<tr><td  align='left' width='250px' >jjjj<b> ".$fila_cliente['Nombre_Cl']." ".$fila_cliente['Apellido1_Cl']." ".$fila_cliente['Apellido2_Cl'])."</b></td>";
												print("<td align='left' width='75px' > ".formatearFecha($fila_pernoctas['Fecha_Llegada'])."</td><td align='left' width='75px'>".formatearFecha($fila_pernoctas['Fecha_Salida'])."</td>");
												if($fila_pernoctas['Ingreso']==0)
												{
												print("<td align='left' width='60px'> &nbsp</td><td align='left' width='60px'>".$fila_pernoctas['Noches_Pagadas']."/".$fila_pernoctas['PerNocta']."</td></tr></table></td>");
												}else {
												  print("<td align='left' width='60px'> ".$fila_pernoctas['Ingreso']."€</td><td align='left' width='60px'>".$fila_pernoctas['Noches_Pagadas']."/".$fila_pernoctas['PerNocta']."</td></tr></table></td>");
												}
												$i++;
											}	    	
										}
								}
								
								//Mostrar Reservas
								if($num_results_pernoctas_reserva2>0)
									{
										for($n=0;$n<$num_results_pernoctas_reserva2;$n++)
										{
										$fila_reserva2=mysql_fetch_array($result_reserva2);
										
										if(isset($fila_reserva2['Nombre_PRA']))
											{
											  for($g=0;$g<$fila_reserva1['Num_Camas'];$g++)
											  {
											  ?>
										  <tr >
								
			   						<td width="800px;" height="px" style="Verdana;font-size:9px;">
			   					<table border=0 width="100%" style="font-family:Verdana;font-size:9px;">
			   							<?
					
										print("<tr><td width='250px' align='left'style='font-family:Verdana;font-size:9px;'><b> ".$fila_reserva2['Nombre_PRA']." ".$fila_reserva2['Apellido1_PRA']." ".$fila_reserva2['Apellido2_PRA'])."</b></td>";
											print("<td align='left' width='75px' >".formatearFecha($fila_reserva1['Fecha_Llegada'])."</td><td align='left' width='75px'>".formatearFecha($fila_reserva1['Fecha_Salida'])."</td>");
											print("<td align='left' width='60px'>".$fila_reserva1['Ingreso']."€</td><td align='left' width='75px'>Teléfono: ".$fila_reserva2['Tfno_PRA']."</td></tr></table></td></tr>");
									$i++;
										}}	    	
								}	
								
								}
							
							//print($i);print($habitaciones[$j]['camas']);
							if($i<=$habitaciones[$j]['camas']){
							  /*($habitaciones[$j]['camas']>3) &&*/
								if( ($i>3)){?>
								  <tr><?
								  	for($p=$i;$p<=$habitaciones[$j]['camas'];$p++)
								  	{	
								    ?>	
								    
										<td width="800px;" height="25px" style="font-family:Verdana;font-size:9px;"> 
											<?echo $habitaciones[$j]['camas'];?>
											&nbsp;
										</td>																   
								  <?  
									}?>
								</tr>
								<?								
								}
								else{
								?>
									<td width="800px;" height="25px" style="font-family:Verdana;font-size:9px;"> 
										<?echo $habitaciones[$j]['camas'];?>
										&nbsp;
									</td> 
								<?	
								}
							}
							?>
							</table>
							 </td>
			   			</tr>
					</table>
			   			</td>
			   			
			   		</tr>			   		
			   		<?}?>
			   </table>
			   </center>
			   
			   
			   <?
		   
			}
  
if (isset($_GET['imprimir'])){?>
	<script>
	//Llamada a la función imprimir que imprimirá la página
	 imprimir();
    </script>

<?}
mysql_close($db);
?>    
	
   
  

