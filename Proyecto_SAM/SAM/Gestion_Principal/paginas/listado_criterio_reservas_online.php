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
	  //	tr.style.cursor = 'pointer';
	}
</script>
<?php
session_start();
		 //conecto con  la base de datos
		  @ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);
	//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	if($_GET['orreservas_on']=="Localizador_Reserva"||$_GET['orreservas_on']=="Nombre_PRA"||$_GET['orreservas_on']=="Apellido1_PRA"||$_GET['orreservas_on']=="Fecha_Reserva"||$_GET['orreservas_on']=="PerNocta"||$_GET['orreservas_on']=="Num_Camas"){
	 	if(!$_SESSION['orreservas_on']){$_SESSION['orreservas_on']=1;}
		if($_SESSION['orreservas_on']==1){
		$ordenreservas_on=" order by ".$_GET['orreservas_on']." DESC";
		$_SESSION['orreservas_on']=2;
		}else{
		$ordenreservas_on=" order by ".$_GET['orreservas_on']." ASC";
		$_SESSION['orreservas_on']=1;
	}
	
	}
	$_SESSION['retorno']=9;
		  ?>
		   <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > 
			<div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:94%;'>
						Listado de Reservas Online
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Reservas Online</div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table border="0" cellpadding="0" cellspacing="0" width="98%" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="left"> 
			
                        <Th width="150" align="center"><a class="enlace" href="?pag=listado_criterio_reservas_online.php&orreservas_on=Nombre_PRA">Nombre</a></Th>
                        <Th width="250" align="center"><a class="enlace" href="?pag=listado_criterio_reservas_online.php&orreservas_on=Apellido1_PRA">Apellidos</a></Th>
                        <Th width="100" align="center"><a class="enlace" href="?pag=listado_criterio_reservas_online.php&orreservas_on=Fecha_Reserva">Fecha Reserva</a></Th>
                        <Th width="50" align="center"><a class="enlace" href="?pag=listado_criterio_reservas_online.php&orreservas_on=PerNocta">Días</a></Th>
                        <Th width="60" align="center"><a class="enlace" href="?pag=listado_criterio_reservas_online.php&orreservas_on=Num_Camas">Personas</a></Th>
                        <th width="50px" align="center">Detalles</th>
                                              <th width="50px" align="center">Modificar</th>
												
											
										</tr>
									</thead>
									<tbody class="scrollContent">
									<?PHP
									$sql = "select * from reserva INNER JOIN detalles ON (reserva.dni_pra = detalles.dni_pra and reserva.fecha_llegada = detalles.fecha_llegada) INNER JOIN pra ON (reserva.dni_pra = pra.dni_pra) where reserva.id_hab = 'PRA' ";
									 //recojo las variables de la pagina de busqueda y las meto en una session
								if($mi_formulario){
									 $_SESSION['bus']['localizador_reservas_on']=$_POST['localizador_reservas_on'];
									 $_SESSION['bus']['dni_reservas_on']=$_POST['dni_reservas_on'];
									 $_SESSION['bus']['nombre_reservas_on']=$_POST['nombre_reservas_on'];
									 $_SESSION['bus']['apellido1_reservas_on']=$_POST['apellido1_reservas_on'];
									 $_SESSION['bus']['apellido2_reservas_on']=$_POST['apellido2_reservas_on'];
									 $_SESSION['bus']['dia_reservas_on']=$_POST['dia_reservas_on'];
									 $_SESSION['bus']['mes_reservas_on']=$_POST['mes_reservas_on'];
									 $_SESSION['bus']['anyo_reservas_on']=$_POST['anyo_reservas_on'];
								}

									
									
									
							 //creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta		
									if($_SESSION['bus']['dni_reservas_on'] || $_SESSION['bus']['dni_reservas_on']=="0"){
											$sql=$sql." and reserva.DNI_PRA like '".$_SESSION['bus']['dni_reservas_on']."%'";
											}
											if($_SESSION['bus']['nombre_reservas_on']){
											$sql=$sql." and pra.Nombre_PRA like'".$_SESSION['bus']['nombre_reservas_on']."%'";
											}
											if($_SESSION['bus']['localizador_reservas_on']){
											$sql=$sql." and detalles.Localizador_Reserva  like '".$_SESSION['bus']['dni_reservas_on']."%'";
											}
											if($_SESSION['bus']['apellido1_reservas_on']){
											$sql=$sql." and pra.Apellido1_PRA like '".$_SESSION['bus']['apellido1_reservas_on']."%'";
											}
											if($_SESSION['bus']['apellido2_reservas_on']){
											$sql=$sql." and pra.Apellido2_PRA like '".$_SESSION['bus']['apellido2_reservas_on']."%'";
											}
											if($_SESSION['bus']['anyo_reservas_on']){
												$sql=$sql." and SUBSTRING(reserva.Fecha_Llegada,1,4) = '".$_SESSION['bus']['anyo_reservas_on']."'";
											}
											if($_SESSION['bus']['mes_reservas_on']){
												$sql=$sql." and SUBSTRING(reserva.Fecha_Llegada,6,7) = '".$_SESSION['bus']['mes_reservas_on']."'";
											}
											if($_SESSION['bus']['dia_reservas_on']){
												$sql=$sql." and SUBSTRING(reserva.Fecha_Llegada,9,10) = '".$_SESSION['bus']['dia_reservas_on']."'";
											}
											if($_SESSION['bus']['fecha_llegada_reservas_on']){
												$sql=$sql." and reserva.Fecha_Llegada like '".$_SESSION['bus']['fecha_llegada_reservas_on']."%'";
											}
				
				$sqldef=$sql.$ordenreservas_on;
				//echo $sqldef;
				
									$result = mysql_query($sqldef);
				                  for ($i=0;$i<mysql_num_rows($result);$i++){
									?>
										<tr align='left' class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
											<?PHP
			  
		$fila = mysql_fetch_array($result);
		
		
		echo "<td>";
		if($fila['Nombre_PRA']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Nombre_PRA'];
		}
		echo "</td><td>";
		if($fila['Apellido1_PRA']=='' && $fila['Apellido2_PRA']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Apellido1_PRA']." ".$fila['Apellido2_PRA'];
		}
		echo "</td>";

		echo "<td align='center'>";
		if($fila['Fecha_Reserva']==''){
			echo "&nbsp;";
		}else{
			echo substr($fila['Fecha_Reserva'],8,2)."/".substr($fila['Fecha_Reserva'],5,2)."/".substr($fila['Fecha_Reserva'],0,4);
		}
		echo "</td>";
		echo "<td align='center'>";
		if($fila['PerNocta']==''){
			echo "&nbsp;";
		}else{
			echo $fila['PerNocta'];
		}
		echo "</td>";
		
		echo "<td align='center'>";
		if($fila['Num_Camas']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Num_Camas'];
		}
		echo "</td>";
		?>	
                       
                        <TD align='center'><?PHP echo "<a href='?pag=reservas_online.php&ver=".$fila['Localizador_Reserva']."&busqueda=si '>"; ?><img src="../imagenes/botones/detalles.gif" width="17" height="20" alt='Detalles' border='0'></a></td>
                                                  
              <td align="center"><?PHP echo "<a href='?pag=reservas_online.php&ver=".$fila['Localizador_Reserva']."&mod=si&busqueda=si '>"; ?><img src="../imagenes/botones/modificar.gif" width="20" height="24" alt='Modificar' border='0'></a></td>
             

										</tr>
										
                        <?PHP
					
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