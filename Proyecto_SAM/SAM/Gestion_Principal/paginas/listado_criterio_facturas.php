<?php
	if(isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion']=true) //Comprobando que se tiene permiso para realizar busquedas de Facturas
		{
		$_GET['num_fct'];
?>

<style type="text/css"> 
a.enlace { 
color: white; 
width: 100%; 
height: 100%; 
text-decoration: none; 
} 

</style>
<script language="JavaScript">
	function desresaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#F4FCFF';
	  	tr.style.color = '#3F7BCC';
	}
	function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#3F7BCC';
	  	tr.style.color = '#F4FCFF';
	  //	tr.style.cursor = 'pointer';
	}


				function eliminar_factura(id_factura) {
					var response = window.confirm('¿Desea eliminar la factura '+id_factura+'?');
					if (response) {	
						window.location='?pag=listado_criterio_facturas.php&num_fct='+id_factura+'';
					}else{
						// window.alert('Ha cancelado la operación');
					}
				}
				

				
				

</SCRIPT>

<?php
		  //conecto con  la base de datos
		  @ $$db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);


//eliminar facturas en Base de Datos(BD)
				if($num_fct){
					$suprimir="DELETE FROM factura WHERE Num_factura='".$num_fct."';";
					//echo $suprimir;
					mysql_query($suprimir);
				}





	//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	
	if($_GET['orfacturas']=="num"){
		if(!$_SESSION['orfacturas']){
			$_SESSION['orfacturas']=1;
			$_SESSION['campo_ordenar']=$_GET['orfacturas'];
			
		}
		if($_SESSION['orfacturas']==1){
			$_SESSION['campo_ordenar']=$_GET['orfacturas'];
			$_SESSION['orfacturas']=2;
			$orden="SORT_DESC";
			
		}else{
			$_SESSION['campo_ordenar']=$_GET['orfacturas'];
			$_SESSION['orfacturas']=1;
			$orden="SORT_ASC";
			
		}
	}
	
	if($_GET['orfacturas']=="fecha"||$_GET['orfacturas']=="tipo"||$_GET['orfacturas']=="dni"||$_GET['orfacturas']=="nombre"||$_GET['orfacturas']=="importe"){
	 	if(!$_SESSION['orfacturas']){$_SESSION['orfacturas']=1;$_SESSION['campo_ordenar']=$_GET['orfacturas'];}
		if($_SESSION['orfacturas']==1){
		$_SESSION['campo_ordenar']=$_GET['orfacturas'];
		$_SESSION['orfacturas']=2;
		$orden="SORT_DESC";
		}else{
		$_SESSION['campo_ordenar']=$_GET['orfacturas'];
		$_SESSION['orfacturas']=1;
		$orden="SORT_ASC";
	}
	
	}
	$_SESSION['retorno']=2;
	
  //recojo las variables de la pagina de busqueda y las meto en una session
if($mi_formulario){
  $_SESSION['bus']['opc2']=$_POST['opc2'];
  $_SESSION['bus']['factura']=$_POST['factura'];
  $_SESSION['bus']['nom_grup_factura']=$_POST['nom_grup_factura'];
  $_SESSION['bus']['dni_factura']=$_POST['dni_factura'];
  $_SESSION['bus']['nombre_factura']=$_POST['nombre_factura'];
  $_SESSION['bus']['apellido1_factura']=$_POST['apellido1_factura'];
  $_SESSION['bus']['apellido2_factura']=$_POST['apellido2_factura'];
  $_SESSION['bus']['dia_factura']=$_POST['dia_factura'];
  $_SESSION['bus']['mes_factura']=$_POST['mes_factura'];
  $_SESSION['bus']['anyo_factura']=$_POST['anyo_factura'];
}
	                            
			
	
	
	
	
		  ?>
		   <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > 
			<div class='champi_izquierda'>&nbsp;</div>
			<div class='champi_centro'  style='width:94%;'>
						Listado de Facturas  <?PHP if($_SESSION['bus']['opc2']=="2")
												{echo " de Alberguistas";}
												if($_SESSION['bus']['opc2']=="3")
												{echo " de Peregrinos";}
												if($_SESSION['bus']['opc2']=="4")
												{echo " de Grupos";}
												if($_SESSION['bus']['opc2']=="5")
												{echo " de Reservas";}
												
							
							
							?>
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Facturas  <?PHP if($_SESSION['bus']['opc2']=="2")
												{echo " de Alberguistas";}
												if($_SESSION['bus']['opc2']=="3")
												{echo " de Peregrinos";}
												if($_SESSION['bus']['opc2']=="4")
												{echo " de Grupos";}
												if($_SESSION['bus']['opc2']=="5")
												{echo " de Reservas";}
												
							
							
							?></div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table border="0" cellpadding="0" cellspacing="0" width="98%" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="left"> 
			<th align="center" style="padding:0px 0px 0px 0px;">
				<a class="enlace" href="?pag=listado_criterio_facturas.php&orfacturas=num">Factura</a></Th>
										
												<Th align="center"><a class="enlace" href="?pag=listado_criterio_facturas.php&orfacturas=dni">
												<?PHP
												if($_SESSION['bus']['opc2']=="4"){
												echo "D.N.I. Representante";
												}else
												{
												echo "D.N.I.";
												}
												?></a>
												</Th>
                                              <Th align="center"><a class="enlace" href="?pag=listado_criterio_facturas.php&orfacturas=nombre">
											  <?PHP
												if($_POST['tipo_factura']=="grupo"){
												echo "Nombre Grupo";
												}else
												{
												echo "Nombre";
												}
												?></a>
											  </Th>
                                             
                                              <Th align="center"><a class="enlace" href="?pag=listado_criterio_facturas.php&orfacturas=fecha">Fecha</a></Th>
                                              
                                              <Th width="50px" align="center"><a class="enlace" href="?pag=listado_criterio_facturas.php&orfacturas=importe">Importe</a></Th>
                                              
                                              <th width="50px" align="center">Detalles</th>
                                              <th width="50px" align="center">Eliminar</th>
                                              
											
										</tr>
									</thead>
									<tbody class="scrollContent">
									<?PHP 
									
				
				  	 //creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta dependiendo que opcion me haya escogido en la pag de busqueda     
				//para todas las facturas
				$num_ordenar="0";
				$and=1;
				if($_SESSION['bus']['opc2']=="1"){
				$sqltodofactura="select * from factura";
				        if($_SESSION['bus']['Num_Factura']){
							if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
					    $sqltodofactura=$sqltodofactura."factura.Num_Factura like '".$_SESSION['bus']['factura']."%'";
					   }
					   if($_SESSION['bus']['dni_factura'] || $_SESSION['bus']['dni_factura']=="0"){
						   if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
					    $sqltodofactura=$sqltodofactura."factura.DNI like '".$_SESSION['bus']['dni_factura']."%'";
					   }
					   if($_SESSION['bus']['nombre_factura']){
						   if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
					    $sqltodofactura=$sqltodofactura."factura.Nombre like \'".$_SESSION['bus']['nombre_factura']."%\'";
					   }
					   if($_SESSION['bus']['apellido1_factura']){
						   if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
					    $sqltodofactura=$sqltodofactura."factura.Apellido1 like '".$_SESSION['bus']['apellido1_factura']."%'";
					   }
					   if($_SESSION['bus']['apellido2_factura']){
						   if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
					    $sqltodofactura=$sqltodofactura."factura.Apellido2 like '".$_SESSION['bus']['apellido2_factura']."%'";
					   }
				      
					    if($_SESSION['bus']['anyo_factura']){
							if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
							 $sqltodofactura=$sqltodofactura."SUBSTRING(factura.Fecha_Factura,1,4) = '".$_SESSION['bus']['anyo_factura']."'";
						}
						if($_SESSION['bus']['mes_factura']){
							if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
							$sqltodofactura=$sqltodofactura."SUBSTRING(factura.Fecha_Factura,6,2) = '".$_SESSION['bus']['mes_factura']."'";
						}
						if($_SESSION['bus']['dia_factura']){
							if($and==1){
								$sqltodofactura.=" where ";
								$and=2;
							}else{
								$sqltodofactura.=" and ";
							}
							$sqltodofactura=$sqltodofactura."SUBSTRING(factura.Fecha_Factura,9,2) = '".$_SESSION['bus']['dia_factura']."'";
						}
					    
				//echo $sqltodofactura;
				    $resultodo = mysql_query($sqltodofactura);
				
				for ($j=$num_ordenar;$j<mysql_num_rows($resultodo);$j++){
				
				          $filatodo = mysql_fetch_array($resultodo);
						  
						  $mm=split('-',$filatodo['Num_Factura']);
						  $array_a_ordenar[$j]['num']=intval($mm[0]);
						  $array_a_ordenar[$j]['dni']=$filatodo['DNI'];
						  $array_a_ordenar[$j]['nombre']=$filatodo['Nombre']." ".$filatodo['Apellido1']." ".$filatodo['Apellido2'];
						  $array_a_ordenar[$j]['fecha']=$filatodo['Fecha_Factura'];
						  $array_a_ordenar[$j]['importe']=$filatodo['Ingreso'];	     
												
												
												
				
				     
					 $num_ordenar=count($array_a_ordenar);
					 $total=0;	
				}//cierro el for de todos
			}



				//para alberguistas
				
				$and=1;
				if($_SESSION['bus']['opc2']=="2"){
				$sqlalberfactura="Select * from factura where Num_factura in(Select DISTINCT(Num_factura) from genera)";
				        if($_SESSION['bus']['Num_Factura']){
					    $sqlalberfactura=$sqlalberfactura." and factura.Num_Factura like '".$_SESSION['bus']['factura']."%'";
					   }
					   if($_SESSION['bus']['dni_factura'] || $_SESSION['bus']['dni_factura']=="0"){
					    $sqlalberfactura=$sqlalberfactura." and factura.DNI like '".$_SESSION['bus']['dni_factura']."%'";
					   }
					   if($_SESSION['bus']['nombre_factura']){
					    $sqlalberfactura=$sqlalberfactura." and factura.Nombre like '".$_SESSION['bus']['nombre_factura']."%'";
					   }
					   if($_SESSION['bus']['apellido1_factura']){
					    $sqlalberfactura=$sqlalberfactura." and factura.Apellido1 like '".$_SESSION['bus']['apellido1_factura']."%'";
					   }
					   if($_SESSION['bus']['apellido2_factura']){
					    $sqlalberfactura=$sqlalberfactura." and factura.Apellido2 like '".$_SESSION['bus']['apellido2_factura']."%'";
					   }
				       /* if($_SESSION['bus']['fecha_factura']){
					    $sqlalberfactura=$sqlalberfactura." and factura.Fecha_Factura like '".$_SESSION['bus']['fecha_factura']."%'";
					   }*/
					    if($_SESSION['bus']['anyo_factura']){
							 $sqlalberfactura=$sqlalberfactura." and SUBSTRING(factura.Fecha_Factura,1,4) = '".$_SESSION['bus']['anyo_factura']."'";
						}
						if($_SESSION['bus']['mes_factura']){
							$sqlalberfactura=$sqlalberfactura." and SUBSTRING(factura.Fecha_Factura,6,2) = '".$_SESSION['bus']['mes_factura']."'";
						}
						if($_SESSION['bus']['dia_factura']){
							$sqlalberfactura=$sqlalberfactura." and SUBSTRING(factura.Fecha_Factura,9,2) = '".$_SESSION['bus']['dia_factura']."'";
						}
					    
				//echo $sqlalberfactura;
				
				    $resultalber = mysql_query($sqlalberfactura);
				
				for ($j=$num_ordenar;$j<mysql_num_rows($resultalber);$j++){
				
				          $filaalber = mysql_fetch_array($resultalber);
						  
						 $mm=split('-',$filaalber['Num_Factura']);
						  $array_a_ordenar[$j]['num']=intval($mm[0]);
						  $array_a_ordenar[$j]['dni']=$filaalber['DNI'];
						  $array_a_ordenar[$j]['nombre']=$filaalber['Nombre']." ".$filaalber['Apellido1']." ".$filaalber['Apellido2'];
						  $array_a_ordenar[$j]['fecha']=$filaalber['Fecha_Factura'];
						  $array_a_ordenar[$j]['importe']=$filaalber['Ingreso'];	     
												
												
												
				
				     
					 $num_ordenar=count($array_a_ordenar);
					 $total=0;	
				}//cierro el for de alberguista
			}
				//para pregrinos
				
				if($_SESSION['bus']['opc2']=="3"){
				$sqlperefactura="Select * from factura where Num_factura in ( Select DISTINCT ( Num_factura ) from genera_p )";
				        if($_SESSION['bus']['anyo_factura']){
							 $sqlperefactura=$sqlperefactura." and SUBSTRING(factura.Fecha_Factura,1,4) = '".$_SESSION['bus']['anyo_factura']."'";
						}
						if($_SESSION['bus']['mes_factura']){
							$sqlperefactura=$sqlperefactura." and SUBSTRING(factura.Fecha_Factura,6,2) = '".$_SESSION['bus']['mes_factura']."'";
						}
						if($_SESSION['bus']['dia_factura'] || $_SESSION['bus']['dni_factura']=="0"){
							$sqlperefactura=$sqlperefactura." and SUBSTRING(factura.Fecha_Factura,9,2) = '".$_SESSION['bus']['dia_factura']."'";
						}
					    if($_SESSION['bus']['Num_Factura']){
					    $sqlperefactura=$sqlperefactura." and factura.Num_Factura='".$_SESSION['bus']['factura']."'";
					   }
					   if($_SESSION['bus']['dni_factura']){
					    $sqlperefactura=$sqlperefactura." and factura.DNI='".$_SESSION['bus']['dni_factura']."'";
					   }
					   if($_SESSION['bus']['nombre_factura']){
					    $sqlperefactura=$sqlperefactura." and factura.Nombre='".$_SESSION['bus']['nombre_factura']."'";
					   }
					   if($_SESSION['bus']['apellido1_factura']){
					    $sqlperefactura=$sqlperefactura." and factura.Apellido1='".$_SESSION['bus']['apellido1_factura']."'";
					   }
					   if($_SESSION['bus']['apellido2_factura']){
					    $sqlperefactura=$sqlperefactura." and factura.Apellido2='".$_SESSION['bus']['apellido2_factura']."'";
					   }
				
					   
				 //echo $sqlperefactura;
				 
				    $resultpere = mysql_query($sqlperefactura);
				
				for ($j=$num_ordenar;$j<mysql_num_rows($resultpere);$j++){
				
				          $filapere = mysql_fetch_array($resultpere);
						  
						  $mm=split('-',$filapere['Num_Factura']);
						  $array_a_ordenar[$j]['num']=intval($mm[0]);
						  $array_a_ordenar[$j]['dni']=$filapere['DNI'];
						  $array_a_ordenar[$j]['nombre']=$filapere['Nombre']." ".$filapere['Apellido1']." ".$filapere['Apellido2'];
						  $array_a_ordenar[$j]['fecha']=$filapere['Fecha_Factura'];
						  $array_a_ordenar[$j]['importe']=$filapere['Ingreso'];
												
					  $num_ordenar=count($array_a_ordenar);
					  $total=0;							
				}//cierro el for de peregrino
				//para grupos
				}
				$and=1;
				if($_SESSION['bus']['opc2']=="4"){
				//$sqlgrupofactura="Select * from factura where Num_factura in(Select DISTINCT(Num_factura) from genera_gr)";
				$sqlgrupofactura="SELECT DISTINCT(factura.Num_Factura), factura.DNI, genera_gr.Nombre_Gr, factura.Fecha_Factura, factura.Ingreso
					FROM factura
					INNER  JOIN genera_gr ON factura.Num_Factura=genera_gr.Num_Factura";
				        if($_SESSION['bus']['anyo_factura']){
							if($and==1){
								$sqlgrupofactura.=" where ";
								$and=2;
							}else{
								$sqlgrupofactura.=" and ";
							}
							 $sqlgrupofactura=$sqlgrupofactura."SUBSTRING(factura.Fecha_Factura,1,4) = '".$_SESSION['bus']['anyo_factura']."'";
						}
						if($_SESSION['bus']['mes_factura']){
							if($and==1){
								$sqlgrupofactura.=" where ";
								$and=2;
							}else{
								$sqlgrupofactura.=" and ";
							}
							$sqlgrupofactura=$sqlgrupofactura."SUBSTRING(factura.Fecha_Factura,6,2) = '".$_SESSION['bus']['mes_factura']."'";
						}
						if($_SESSION['bus']['dia_factura']){
							if($and==1){
								$sqlgrupofactura.=" where ";
								$and=2;
							}else{
								$sqlgrupofactura.=" and ";
							}
							$sqlgrupofactura=$sqlgrupofactura."SUBSTRING(factura.Fecha_Factura,9,2) = '".$_SESSION['bus']['dia_factura']."'";
						}

						
					    if($_SESSION['bus']['Num_Factura']){
							if($and==1){
								$sqlgrupofactura.=" where ";
								$and=2;
							}else{
								$sqlgrupofactura.=" and ";
							}
					    $sqlgrupofactura=$sqlgrupofactura."factura.Num_Factura like '".$_SESSION['bus']['factura']."%'";
					   }
					   if($_SESSION['bus']['dni_factura'] || $_SESSION['bus']['dni_factura']=="0"){
						   if($and==1){
								$sqlgrupofactura.=" where ";
								$and=2;
							}else{
								$sqlgrupofactura.=" and ";
							}
					    $sqlgrupofactura=$sqlgrupofactura."factura.DNI like '".$_SESSION['bus']['dni_factura']."%'";
					   }
					  
					   if($_SESSION['bus']['nom_grup_factura']){
						   if($and==1){
								$sqlgrupofactura.=" where ";
								$and=2;
							}else{
								$sqlgrupofactura.=" and ";
							}
					    $sqlgrupofactura=$sqlgrupofactura."genera_gr.Nombre_Gr like '".$_SESSION['bus']['nom_grup_factura']."%'";
					   }
				
			  //echo $sqlgrupofactura;
				    $resultgrupo = mysql_query($sqlgrupofactura);
				
				for ($j=$num_ordenar;$j<mysql_num_rows($resultgrupo);$j++){
				
				          $filagrupo = mysql_fetch_array($resultgrupo);
						
						  $mm=split('-',$filagrupo['Num_Factura']);
						  $array_a_ordenar[$j]['num']=intval($mm[0]);
						  $array_a_ordenar[$j]['dni']=$filagrupo['DNI'];
						  $array_a_ordenar[$j]['nombre']=$filagrupo['Nombre_Gr'];
						  $array_a_ordenar[$j]['fecha']=$filagrupo['Fecha_Factura'];
						  $array_a_ordenar[$j]['importe']=$filagrupo['Ingreso'];
												
												
												
				
				     }//cierro el for de grupo
					 $num_ordenar=count($array_a_ordenar);
				}//cierro el if de grupo
			
				//para reservas
				if($_SESSION['bus']['opc2']=="5"){
				$sqlreserfactura="Select * from factura where Num_factura in(Select DISTINCT(Num_factura) from genera_reserva)";
				        if($_SESSION['bus']['anyo_factura']){
							 $sqlreserfactura=$sqlreserfactura." and SUBSTRING(factura.Fecha_Factura,1,4) = '".$_SESSION['bus']['anyo_factura']."'";
						}
						if($_SESSION['bus']['mes_factura']){
							$sqlreserfactura=$sqlreserfactura." and SUBSTRING(factura.Fecha_Factura,6,2) = '".$_SESSION['bus']['mes_factura']."'";
						}
						if($_SESSION['bus']['dia_factura']){
							$sqlreserfactura=$sqlreserfactura." and SUBSTRING(factura.Fecha_Factura,9,2) = '".$_SESSION['bus']['dia_factura']."'";
						}
						
						/*if($_SESSION['bus']['fecha_factura']){
					    $sqlreserfactura=$sqlreserfactura." and factura.Fecha_Factura ='".$_SESSION['bus']['fecha_factura']."'";
					   }*/
					    if($_SESSION['bus']['Num_Factura']){
					    $sqlreserfactura=$sqlreserfactura." and factura.Num_Factura='".$_SESSION['bus']['factura']."'";
					   }
					   if($_SESSION['bus']['dni_factura'] || $_SESSION['bus']['dni_factura']=="0"){
					    $sqlreserfactura=$sqlreserfactura." and factura.DNI='".$_SESSION['bus']['dni_factura']."'";
					   }
					   if($_SESSION['bus']['nombre_factura']){
					    $sqlreserfactura=$sqlreserfactura." and factura.Nombre ='".$_SESSION['bus']['nombre_factura']."'";
					   }
					   if($_SESSION['bus']['apellido1_factura']){
					    $sqlreserfactura=$sqlreserfactura." and factura.Apellido1 ='".$_SESSION['bus']['apellido1_factura']."'";
					   }
					   if($_SESSION['bus']['apellido2_factura']){
					    $sqlreserfactura=$sqlreserfactura." and factura.Apellido2 ='".$_SESSION['bus']['apellido2_factura']."'";
					   }
				   
				   //echo $sqlreserfactura;
				    $resultreser = mysql_query($sqlreserfactura);
				
				for ($j=$num_ordenar;$j<mysql_num_rows($resultreser);$j++){
				
				          $filareser = mysql_fetch_array($resultreser);
						 
						  $mm=split('-',$filareser['Num_Factura']);
						  $array_a_ordenar[$j]['num']=intval($mm[0]);
						  $array_a_ordenar[$j]['dni']=$filareser['DNI'];
						  $array_a_ordenar[$j]['nombre']=$filareser['Nombre']." ".$filareser['Apellido1']." ".$filareser['Apellido2'];
						  $array_a_ordenar[$j]['fecha']=$filareser['Fecha_Factura'];
						  $array_a_ordenar[$j]['importe']= $filareser['Ingreso'];

				     }//cierro el for de reservas
					  $num_ordenar=count($array_a_ordenar);
				}//cierro el if de reservas//echo "<tr align='left' id='texto_listados' onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'>";
				//echo "<TD align='center' ><img src='./imagenes/botones/detalles.gif' width='17' height='20'></td> ";     
              //echo "<TD align='center' ><img src='./imagenes/botones/modificar.gif' width='20' height='24'></td>";
									 //esta funcion me ordena el array segun a opcion q le halla dicho
			
			
			 function ordenar_array() { 
				$n_parametros = func_num_args(); // Obenemos el número de parámetros 
				if ($n_parametros<3 || $n_parametros%2!=1) { // Si tenemos el número de parametro mal... 
					return false; 
				} else { // Hasta aquí todo correcto...veamos si los parámetros tienen lo que debe ser... 
					$arg_list = func_get_args(); 

					if (!(is_array($arg_list[0]) && is_array(current($arg_list[0])))) { 
						return false; // Si el primero no es un array...MALO! 
					} 
					for ($i = 1; $i<$n_parametros; $i++) { // Miramos que el resto de parámetros tb estén bien... 
						if ($i%2!=0) {// Parámetro impar...tiene que ser un campo del array... 
							if (!array_key_exists($arg_list[$i], current($arg_list[0]))) { 
								return false; 
							} 
						} else { // Par, no falla...si no es SORT_ASC o SORT_DESC...a la calle! 
							if ($arg_list[$i]!=SORT_ASC && $arg_list[$i]!=SORT_DESC) { 
								return false; 
							} 
						} 
					} 
					$array_salida = $arg_list[0]; 

					// Una vez los parámetros se que están bien, procederé a ordenar... 
					$a_evaluar = "foreach (\$array_salida as \$fila){\n"; 
					for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada columna... 
						$a_evaluar .= "  \$campo{$i}[] = \$fila['$arg_list[$i]'];\n"; 
					} 
					$a_evaluar .= "}\n"; 
					$a_evaluar .= "array_multisort(\n"; 
					for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada elemento... 
						$a_evaluar .= "  \$campo{$i}, SORT_REGULAR, \$arg_list[".($i+1)."],\n"; 
					} 
					$a_evaluar .= "  \$array_salida);"; 
   

					eval($a_evaluar); 
					return $array_salida; 
				} 
			} 
				 //recorro el array $array_a_ordenar para mostrarlo
				 if(!$_SESSION['orfacturas']){
					 $array_ordenadito2 = ordenar_array($array_a_ordenar, 'nombre', SORT_ASC);
					 
				 }else{
			
					 if( $orden=="SORT_ASC"){
						  $array_ordenadito2 = ordenar_array($array_a_ordenar, $_SESSION['campo_ordenar'],  SORT_ASC); 
					 }
					else{
						$array_ordenadito2 = ordenar_array($array_a_ordenar, $_SESSION['campo_ordenar'],  SORT_DESC);
						
					}
			 			 
				 }
				 
                        
					for($f=0;$f<count($array_a_ordenar);$f++){
						$id_fact=$array_ordenadito2[$f]['num']."-".substr($array_ordenadito2[$f]['fecha'],0,4);
					
					echo "<tr align='left' class='texto_listados' onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'>";
					echo "<td >";
					if($array_ordenadito2[$f]['num']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$f]['num']."-".substr($array_ordenadito2[$f]['fecha'],0,4);
					}
					echo "</td><td>";
					if($array_ordenadito2[$f]['dni']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$f]['dni'];
					}
					echo "</td><td>";
					if($array_ordenadito2[$f]['nombre']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$f]['nombre'];
					}
					
					echo "</td><td>";
					if($array_ordenadito2[$f]['fecha']==''){
						echo "&nbsp;";
					}else{
						echo substr($array_ordenadito2[$f]['fecha'],8,2)."/".substr($array_ordenadito2[$f]['fecha'],5,2)."/".substr($array_ordenadito2[$f]['fecha'],0,4);
					}
					
					 
									
					echo "</td><td align='center'>";
					if($array_ordenadito2[$f]['importe']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$f]['importe'];
					}
					echo "</td><td align='center'>";
					echo "<a href='paginas/factura.php?numf=".$id_fact." '>"; 
					echo "<img src='../imagenes/botones/detalles.gif' width='17' height='20' alt='Detalles' border='0'></a>";
					echo "</td><td align='center'>";
					
					echo "<img src='../imagenes/botones/eliminar.gif' width='15' height='16' alt='eliminar ".$id_fact."' border='0' onclick='eliminar_factura(\"".$id_fact."\")' style='cursor:pointer;'>";
					
					echo "</td>";
					echo "</tr>";
					}
									

					//elimina facturas
				
									
									
									
	?>
						
									
									
									
									
										
									</tbody>
								</table>
							</div>							
						</td>
					</tr>
					<tr>
						<td align="left">
							<a href=".?pag=busq.php" onclick="history.back()"><img src="../imagenes/botones/volver.gif" border="0" alt="Volver"></a>
						</td>
					</tr>	
				</table>
<?php
		}else{	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA</div>";
}
?>