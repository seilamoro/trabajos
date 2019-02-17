<?php
	if(isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']=true) //Comprobando que se tiene permiso para realizar busquedas de Reservas
		{
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
	  	//tr.style.cursor = 'pointer';
	}
</script>
<?php
session_start();
		  	//conecto con la base de datos
		  @ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);
		//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	if($_GET['orreser']=="dni"||$_GET['orreser']=="nombre"||$_GET['orreser']=="apellido"||$_GET['orreser']=="llegada"||$_GET['orreser']=="reserva"||$_GET['orreser']=="camas"||$_GET['orreser']=="habitacion"){
	 	if(!$_SESSION['orreser']){$_SESSION['orreser']=1;$_SESSION['campo_ordenar']=$_GET['orreser'];}
		if($_SESSION['orreser']==1){
		$orden="SORT_DESC";
		$_SESSION['campo_ordenar']=$_GET['orreser'];
		$_SESSION['orreser']=2;
		}else{
		$orden="SORT_ASC";
		$_SESSION['campo_ordenar']=$_GET['orreser'];
		$_SESSION['orreser']=1;
	}}
	$_SESSION['retorno']=7;
		  ?>
		   <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > 
			<div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:94%;'>
						Listado de Reservas
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Reservas</div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table border="0" cellpadding="0" cellspacing="0" width="98%" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="center"> 
			<th style="padding:0px 0px 0px 0px;">
			<a class="enlace" href="?pag=listado_criterio_reservas.php&orreser=dni">D.N.I.</a></Th>
                                              <Th align="center"><a class="enlace" href="?pag=listado_criterio_reservas.php&orreser=nombre">Nombre</a></Th>
                                              <Th align="center"><a class="enlace" href="?pag=listado_criterio_reservas.php&orreser=apellido">Apellidos</a></Th>
                                              <Th align="center"><a class="enlace" href="?pag=listado_criterio_reservas.php&orreser=llegada">Llegada</a></Th>
                                              <Th align="center"><a class="enlace" href="?pag=listado_criterio_reservas.php&orreser=reserva">Reserva</a></Th>
                                              <Th width="50px" align="center"><a class="enlace" href="?pag=listado_criterio_reservas.php&orreser=camas">Camas</a></Th>
                                              <Th  width="50px" align="center"><a class="enlace" href="?pag=listado_criterio_reservas.php&orreser=habitacion">Habitación</a></Th>
                                              <th width="50px" align="center">Detalles</th>
                                              <th width="50px" align="center">Modificar</th>
                                              <th width="50px" align="center">Eliminar</th>
											
										</tr>
									</thead>
									<tbody class="scrollContent">
									<?PHP
									$sqlselect="Select DISTINCT (reserva.DNI_PRA),reserva.Fecha_Llegada";
									
									$sql=" From detalles,pra,reserva Where detalles.DNI_PRA  = pra.DNI_PRA  and detalles.DNI_PRA = reserva.DNI_PRA and reserva.Id_Hab not like 'PRA%'";
										 //recojo las variables de la pagina de busqueda y las meto en una session
									if($mi_formulario){
										 $_SESSION['bus']['dni_reservas']=$_POST['dni_reservas'];
										 $_SESSION['bus']['nombre_reservas']=$_POST['nombre_reservas'];
										 $_SESSION['bus']['apellido1_reservas']=$_POST['apellido1_reservas'];
										 $_SESSION['bus']['apellido2_reservas']=$_POST['apellido2_reservas'];
										 $_SESSION['bus']['nombre_empleado_reservas']=$_POST['nombre_empleado_reservas'];
										 $_SESSION['bus']['dia_reservas']=$_POST['dia_reservas'];
										 $_SESSION['bus']['mes_reservas']=$_POST['mes_reservas'];
										 $_SESSION['bus']['anyo_reservas']=$_POST['anyo_reservas'];
									}
										
		 												
										 //creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta
										if($_SESSION['bus']['dni_reservas'] || $_SESSION['bus']['dni_reservas']=="0"){
											$sql=$sql." and reserva.DNI_PRA like '".$_SESSION['bus']['dni_reservas']."%'";
											}
											if($_SESSION['bus']['nombre_reservas']){
											$sql=$sql." and pra.Nombre_PRA like '".$_SESSION['bus']['nombre_reservas']."%'";
											}
											if($_SESSION['bus']['apellido1_reservas']){
											$sql=$sql." and pra.Apellido1_PRA like '".$_SESSION['bus']['apellido1_reservas']."%'";
											}
											if($_SESSION['bus']['apellido2_reservas']){
											$sql=$sql." and pra.Apellido2_PRA like '".$_SESSION['bus']['apellido2_reservas']."%'";
											}
											if($_SESSION['bus']['nombre_empleado_reservas']){
											$sql=$sql." and detalles.Nombre_empleado like '".$_SESSION['bus']['nombre_empleado_reservas']."%'";
											}
											 if($_SESSION['bus']['anyo_reservas']){
												  $sql=$sql." and SUBSTRING(reserva.Fecha_Llegada,1,4) = '".$_SESSION['bus']['anyo_reservas']."'";
											 }
											 if($_SESSION['bus']['mes_reservas']){
												  $sql=$sql." and SUBSTRING(reserva.Fecha_Llegada,6,7) = '".$_SESSION['bus']['mes_reservas']."'";
											 }
											 if($_SESSION['bus']['dia_reservas']){
												  $sql=$sql." and SUBSTRING(reserva.Fecha_Llegada,9,10) = '".$_SESSION['bus']['dia_reservas']."'";
											 }
										
										
										/*if($_SESSION['bus']['fecha_llegada_reservas']){
				$sql=$sql." and reserva.Fecha_Llegada  ='".$_SESSION['bus']['fecha_llegada_reservas']."'";
													}*/
						$sqldef=$sqlselect.$sql.$ordenreser;
						//echo $sqldef;
								$sqlselect2="Select * ";
				
									$result = mysql_query($sqldef);
				                  for ($i=0;$i<mysql_num_rows($result);$i++){
										
									//<tr align='left' class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
												
			  
												$fila = mysql_fetch_array($result);
												$sqlfin2=" and reserva.DNI_PRA='".$fila['DNI_PRA']."' and reserva.Fecha_Llegada ='".$fila['Fecha_Llegada']."'";
												$sqldef2=$sqlselect2.$sql.$sqlfin2;
												
												$result2 = mysql_query($sqldef2);
												
												
												$camas="";
												$habitaciones="";
												//saco el numero de camas y las habitaciones
												for ($j=0;$j<mysql_num_rows($result2);$j++){
												$fila2 = mysql_fetch_array($result2);
												
												
												
													if(mysql_num_rows($result2)==1){
													$camas=$fila2['Num_Camas'];
													$habitaciones=$fila2['Id_Hab'];
														
													}
													else{
													$camas=$camas+$fila2['Num_Camas'];
															if($habitaciones==""){
															$habitaciones=$fila2['Id_Hab'];
															}
															else{
															
															$habitaciones=$habitaciones.", ".$fila2['Id_Hab'];
															}
													}
													
													
												
												$array_a_ordenar[$i]['dni']=$fila2['DNI_PRA'];
												$array_a_ordenar[$i]['nombre']=$fila2['Nombre_PRA'];
												$array_a_ordenar[$i]['apellido']=$fila2['Apellido1_PRA']." ".$fila2['Apellido2_PRA'];
												$array_a_ordenar[$i]['llegada']=$fila2['Fecha_Llegada'];
												$array_a_ordenar[$i]['reserva']=$fila2['Fecha_Reserva'];
												$array_a_ordenar[$i]['camas']=$camas;
												$array_a_ordenar[$i]['habitacion']=$habitaciones;
												
											}
											}
												
												 //esta funcion me ordena el array_a_ordenar segun a opcion q le halla dicho
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
			 if(!$_SESSION['orreser']){
			 $array_ordenadito2 = ordenar_array($array_a_ordenar, 'nombre', SORT_ASC);
			 }else{
			
		 if( $orden=="SORT_ASC"){
			  $array_ordenadito2 = ordenar_array($array_a_ordenar, $_SESSION['campo_ordenar'],  SORT_ASC); }
			else{
			$array_ordenadito2 = ordenar_array($array_a_ordenar, $_SESSION['campo_ordenar'],  SORT_DESC);
			}
			 			 
			 }
			 
                        
					for($i=0;$i<count($array_a_ordenar);$i++){
					echo "<tr align='left' class='texto_listados' onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'>";
					echo "<td align='center'>";
					if($array_ordenadito2[$i]['dni']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$i]['dni'];
					}
					echo "</td><td>";
					if($array_ordenadito2[$i]['nombre']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$i]['nombre'];
					}
					echo "</td><td>";
					if($array_ordenadito2[$i]['apellido']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$i]['apellido'];
					}
					echo "</td><td align='center'>";
					if($array_ordenadito2[$i]['llegada']==''){
						echo "&nbsp;";
					}else{
						echo substr($array_ordenadito2[$i]['llegada'],8,2)."/".substr($array_ordenadito2[$i]['llegada'],5,2)."/".substr($array_ordenadito2[$i]['llegada'],0,4);
					}
					echo "</td><td align='center'>";
					if($array_ordenadito2[$i]['reserva']==''){
						echo "&nbsp;";
					}else{
						echo substr($array_ordenadito2[$i]['reserva'],8,2)."/".substr($array_ordenadito2[$i]['reserva'],5,2)."/".substr($array_ordenadito2[$i]['reserva'],0,4);
					}
					echo "</td><td align='center'>";
					if($array_ordenadito2[$i]['camas']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$i]['camas'];
					}
					echo "</td><td align='right'>";
					if($array_ordenadito2[$i]['habitacion']==''){
						echo "&nbsp;";
					}else{
						echo $array_ordenadito2[$i]['habitacion'];
					}
					$f_llegada=$array_ordenadito2[$i]['llegada'];
					$dni=$array_ordenadito2[$i]['dni'];
					echo "</td><td align='center'>";
					echo "<a href='?pag=reservas.php&dia=$f_llegada&dni=$dni&busqueda=si&detalles=si '><img src='../imagenes/botones/detalles.gif' width='17' height='20' alt='Detalles' border='0'></a>";
					echo "</td><td align='center'>";
					echo "<a href='?pag=reservas.php&dia=$f_llegada&dni=$dni&busqueda=si&modificar=si '><img src='../imagenes/botones/modificar.gif' width='20' height='24' alt='Modificar' border='0'></a>";
					echo "</td><td align='center'>";
					echo "<a href='?pag=reservas.php&dia=$f_llegada&dni=$dni&busqueda=si&eliminar=si '><img src='../imagenes/botones/eliminar.gif' width='15' height='16' alt='Eliminar' border='0'></a>";
					echo "</td>";
					echo "</tr>";
					}
												
												?>
												                                            
                    
      

									
										
									</tbody>
								</table>
							</div>							
						</td>
					</tr>
					<tr>
						<td align="left">
							<a href=".?pag=busq.php"><img src="../imagenes/botones/volver.gif" border="0" alt="Volver"></a>
						</td>
					</tr>	
				</table>
<?php
		}else{	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA</div>";
}
?>