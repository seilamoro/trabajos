<?
if (isset($_GET['imprimir'])){
SESSION_START();
}
?>
<SCRIPT LANGUAGE="JavaScript">
			  function imprimir() // Funci�n que imprime la p�gina que llama a la funci�n
				  {
					   version = parseInt(navigator.appVersion);
					   if (version >= 4)
						 window.print();
				  }
</SCRIPT>



<?
	 @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']); 
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }
   		 mysql_select_db($_SESSION['conexion']['db']);

	/**
    *  dateAdd(int)
    *  Funci�n que calcula una fecha futura seg�n la cantidad de d�as que se le proporcionen como argumento
	*/
	function restardia()
		{
		$mes = date("m");
		$anio = date("Y");
		$dia = date("d");
		$ultimo_dia = date( "d", mktime(0, 0, 0, $mes, 0, $anio) ) ;
		//print("ein".$ultimo_dia);
		$dias_resto = 1;
		$ayer = $dia - $dias_resto;
		if($ayer == 0)
			{
			$dia_ayer = $ultimo_dia;
			$mes--;
			if ($mes == 0)
				{
				$dia_ayer=date( "d", mktime(0, 0, 0, $mes - 1, 0, $anio-1) ) ;
				//print("diaayer".$dia_ayer);
				$anio--;
				$mes = '12';
				}
			return date("Y-m-d",mktime(0,0,0,$mes,$dia_ayer,$anio));
			}
		else
			return date("Y-m-d",mktime(0,0,0,$mes,$ayer,$anio));    
		}
		
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

	/**
	* Esta funcion recibe una fecha en el formato AAAA-MM-DD y la devuelve en el formato DD-MM-AAAA
	*/
	function formatearFecha($fechaOriginal)
		{
		$fechaNueva=split("-",$fechaOriginal);
		$fechaNueva=$fechaNueva[2]."-".$fechaNueva[1]."-".$fechaNueva[0];
		return $fechaNueva;
		}
		
	
		$fecha_ayer = restardia(); // Obtengo la fecha de ayer

?>					


				
					



<div>
       
	
		<link rel="stylesheet" type="text/css" href="../css/estilos_tablas.css">
		<link rel="stylesheet" type="text/css" href="../css/habitaciones.css">
		<link rel="stylesheet" type="text/css" href="../css/hoja_formularios.css">
		<link rel="stylesheet" type="text/css" href="../css/estructura_alb_per.css">
		<link rel="stylesheet" type="text/css" href="../css/habitacionesColores.css">


	<center>
				
					<table border="0"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
					<tr id="titulo_tablas">
						<td colspan="7" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
						
								<?if (isset($_GET['imprimir'])){?>
								
									<BR>
									<div  style="width:1100px;color:#477CB0;text-align:center;font-family:Verdana;font-size:18px;font-weight:bold;"> Listado de Peregrinos <?print(formatearFecha($fecha_ayer));?></div>
									</div>
									<BR>
								
                        			
                        		<?} else {?>
                           		<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:1100px;"> Listado de Peregrinos <?print(formatearFecha($fecha_ayer));?></div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
                           		<?}?>
								
						</td>
					</tr>

					<tr>
                        <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                           	<?if (isset($_GET['imprimir'])){?>
                        	<div id="tableContainer"  style='border: 1px solid #3F7BCC;'>
                        	<?} else {?>
                           <div id="tableContainer" class="tableContainer" style='height:500px;width: 1160px;background-color:#f4fcff'>
                           <?}?>
                                 <table border="0" id="tabla_detalles" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" class="scrollTable" style='width:1143px;'>
									<thead id="titulos" class="fixedHeader" style='position:relative;'>

									<tr>
						            <th align="center" >
                                        
							            Nombre y Apellidos
				                    </th>
						            <th width="">
                                        
							             Localidad
						            </th>
						            <th width="">
                                        Provincia
						            </th>
						            <th width="">
                                        Nacionalidad
						            </th>
						            <th width="">
                                        Edad
						            </th>
						            <th width="">
                                       Fecha Salida
					            	</th>
						            <th width="">
                                       Obs.
						            </th>
						            <th width="">
                                       DNI
						            </th>
						            <th width="">
                                      Sexo
						            </th>
						            <th width="">
                                      Hab.
						            </th>
						            </tr>
					    
				                    </thead>
                                    <tbody class="scrollContent" >

                                
					                 
					                  
	<?				                  // Consulta que selecciona datos de pernoctas de alberguistas hechas en cada habitacion con fecha anterior a la de hoy

		$query_pernoctas="(select DNI_Cl,Fecha_Llegada,Fecha_Salida,M_P,Id_Hab  from pernocta_p where  fecha_llegada<='".$fecha_ayer."' and Fecha_Salida>'".$fecha_ayer."') order by Id_Hab ASC,Fecha_Salida ASC;";
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
				// Consulta que selecciona todos los datos de la persona que ha pernoctado cuyo dni es dni_cl
				$query_pernocta="select * from pernocta_p where DNI_Cl='".$dni_cl."';";
				$result_pernocta = mysql_query($query_pernocta); 	    	
				$fila_pernocta=mysql_fetch_array($result_pernocta);
				$sql="SELECT * FROM pais WHERE Id_Pais like '".$fila_cliente['Id_Pais']. "'";
               	$resul= mysql_query($sql);
               	$fila=mysql_fetch_array($resul);
               	$pais=$fila['Nombre_Pais'];
               	$sql="SELECT * FROM provincia WHERE Id_Pro like '".$fila_cliente['Id_Pro']. "'";
               	$resul= mysql_query($sql);
               	$fila=mysql_fetch_array($resul);
               	$provincia=$fila['Nombre_Pro'];
					
				?>
				
				
				
				
											 <tr class="texto_listados" >
						                  <td align="center">
                                           	<?print($fila_cliente['Nombre_Cl']." ".$fila_cliente['Apellido1_Cl']." ".$fila_cliente['Apellido2_Cl']);  ?>
					                      </td>
						                  <td width="" align="center">
							                 <?print($fila_cliente['Localidad_Cl']);?>
						                  </td>
		                                  <td width="" align="center">
		                                  	<?print($provincia);?>
				                             
						                  </td>
						                  <td width="" align="center">
							                  <?print($pais);?>
						                  </td>
						                  <td align="center">
                                             <?print(edad($fila_cliente['Fecha_Nacimiento_Cl'],$fecha_ayer));?>
						                  </td>
						                  
						                  <td align="center">
                                             <?print(formatearFecha($fila_pernocta['Fecha_Salida']));?>
						                  </td>
						                  <td align="center">
                                             <?print($fila_pernocta['M_P']);?>
						                  </td>
						                   <td align="center">
                                             <?print($dni_cl);?>
						                  </td>
						                   
											<td align="center">
                                             <?print($fila_cliente['Sexo_Cl']);?>
						                  </td>
						                  <td align="center">
                                             <?print($fila_pernocta['Id_Hab']);?>
						                  </td>

						                  
					                  </tr>
<?				         } // Cierra el for Alberguistas($j=0;$j<$num_results_pernoctas;$j++)
			} // Final del if Alberguistas ($num_results_pernoctas>0)
		
		

		
		
?>
		
		
		
		
                                

                                    </tbody>
								    </table>
                      




                        </td>
                    </tr>
             </table>
             <?if (!isset($_GET['imprimir'])){
             
             ?>
             <div>
             <br>
  				<img src='../imagenes/botones-texto/imprimir.jpg' border=0 title='Imprimir el Listado' onclick='window.open("paginas/peregrino_ayer.php?imprimir=true","Listado","left=0,top=0,height=1024,width=1280,menubar=no,location=no,resizable=yes,scrollbars=yes,status=no");' style='cursor:pointer' ><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				  <img src="../imagenes/botones-texto/cancelar.jpg" border=0 alt='Volver al listado' style='cursor:pointer' onclick="location.href='?pag=listados.php'";>  
  			</div>
  			<?}?>

     </center>
</div>
<?if (isset($_GET['imprimir'])){?>
	<script>
	//Llamada a la funci�n imprimir que imprimir� la p�gina
	 imprimir();
    </script>

<?}
mysql_close($db);
?>
