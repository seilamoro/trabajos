<?php
	if(isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']=true) //Comprobando que se tiene permiso para realizar busquedas de Grupos
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
		  //conecto con la base de datos
		 
		  @$db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);
	//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	if($_GET['orgrupo']=="Nombre_Cl"||$_GET['orgrupo']=="DNI_Repres"||$_GET['orgrupo']=="Apellido1_Cl"||$_GET['orgrupo']=="Localidad_Cl"||$_GET['orgrupo']=="Id_Pais"){
	 	if(!$_SESSION['orgrupo']){$_SESSION['orgrupo']=1;}
		if($_SESSION['orgrupo']==1){
		$ordengrupo=" order by ".$_GET['orgrupo']." DESC";
		$_SESSION['orgrupo']=2;
		}else{
		$ordengrupo=" order by ".$_GET['orgrupo']." ASC";
		$_SESSION['orgrupo']=1;
	}
	
	}
	$_SESSION['retorno']=5;
		  ?>
		
		   <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > 
			<div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:94%;'>
						Listado de Grupos
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Grupos</div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table border="0" cellpadding="0" cellspacing="0" width="98%" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="center"> 
			<th style="padding:0px 0px 0px 0px;">
			<a class="enlace" href="?pag=listado_criterio_grupos.php&orgrupo=Nombre_Gr">Nombre</a></TH>
											<TH align="center"><a class="enlace" href="?pag=listado_criterio_grupos.php&orgrupo=Direccion_Gr">Dirección</a> </TH>
											<TH align="center"><a class="enlace" href="?pag=listado_criterio_grupos.php&orgrupo=Localidad_Gr">Localidad</a></TH>
											<TH align="center"><a class="enlace" href="?pag=listado_criterio_grupos.php&orgrupo=Id_Pais">País</a></TH>
											<TH width="50" align="center">Detalles</th>
											<TH width="50" align="center">Modificar</th>
											<TH width="50" align="center">Estancias</th>
											
										</tr>
									</thead>
									
									<tbody class="scrollContent">
									<?PHP
									 //recojo las variables de la pagina de busqueda y las meto en una session
	if($mi_formulario){							
		 $_SESSION['bus']['nombre_grupo']=$_POST['nombre_grupo'];
		 $_SESSION['bus']['dni_representante_grupo']=$_POST['dni_representante_grupo'];
		 $_SESSION['bus']['nombre_representante_grupo']=$_POST['nombre_representante_grupo'];
		 $_SESSION['bus']['apellido1_representante_grupo']=$_POST['apellido1_representante_grupo'];
		 $_SESSION['bus']['apellido2_representante_grupo']=$_POST['apellido2_representante_grupo'];
		 $_SESSION['bus']['localidad_grupo']=$_POST['localidad_grupo'];
		 $_SESSION['bus']['provincia_grupo']=$_POST['provincia_grupo'];
		 $_SESSION['bus']['pais_grupo']=$_POST['pais_grupo'];
		 $_SESSION['bus']['dia_grupo']=$_POST['dia_grupo'];
		 $_SESSION['bus']['mes_grupo']=$_POST['mes_grupo'];
		 $_SESSION['bus']['anyo_grupo']=$_POST['anyo_grupo'];
	}

				 //creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta
									//$sql="Select DISTINCT (pernocta_gr.Nombre_Gr),grupo.Direccion_Gr,grupo.Localidad_Gr ,grupo.Id_Pais,estancia_gr.DNI_Repres  From grupo,pernocta_gr,estancia_gr Where pernocta_gr.Nombre_Gr = grupo.Nombre_Gr and pernocta_gr.Nombre_Gr = estancia_gr.Nombre_Gr";
									 $fec_llegada='';
										$and=1;


									

									//$sql="Select * from grupo where Nombre_Gr in(Select Nombre_Gr from estancia_gr ".$fec_llegada.")";
									$sql="select distinct(estancia_gr.Nombre_Gr),grupo.Localidad_Gr, grupo.Direccion_Gr,estancia_gr.Id_Pais_Nacionalidad from estancia_gr inner join grupo on estancia_gr.Nombre_Gr=grupo.Nombre_Gr";
											if($_SESSION['bus']['nombre_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
											$sql=$sql."estancia_gr.Nombre_Gr like '".$_SESSION['bus']['nombre_grupo']."%'";
											}
											
											if($_SESSION['bus']['apellido1_representante_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
											$sql=$sql."estancia_gr.Apellido1_Repres like '".$_SESSION['bus']['apellido1_representante_grupo']."%'";
											}
											if($_SESSION['bus']['apellido2_representante_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
											$sql=$sql."estancia_gr.Apellido2_Repres like '".$_SESSION['bus']['apellido2_representante_grupo']."%'";
											}
											if($_SESSION['bus']['localidad_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
											$sql=$sql."grupo.Localidad_Gr like '".$_SESSION['bus']['localidad_grupo']."%'";
											}
											if($_SESSION['bus']['provincia_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
											$sql=$sql."grupo.Id_Pro = '".$_SESSION['bus']['provincia_grupo']."%'";
											}
											if($_SESSION['bus']['pais_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
											$sql=$sql."estancia_gr.Id_Pais_Nacionalidad = '".$_SESSION['bus']['pais_grupo']."%'";
											}
											if($_SESSION['bus']['dni_representante_grupo'] || $_SESSION['bus']['dni_representante_grupo']=="0"){
												if($and==1){
													  $sql.=" where ";
														$and=2;
												}else{
													 $sql.=" and ";
												}
												$sql=$sql."estancia_gr.DNI_Repres  like '".$_SESSION['bus']['dni_representante_grupo']."%'";
											}
											if($_SESSION['bus']['nombre_representante_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
												$sql=$sql."estancia_gr.Nombre_Repres like '".$_SESSION['bus']['nombre_representante_grupo']."%'";
											}
											if($_SESSION['bus']['anyo_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
												$sql=$sql."SUBSTRING(estancia_gr.Fecha_Llegada,1,4) = '".$_SESSION['bus']['anyo_grupo']."'";
											}
											if($_SESSION['bus']['mes_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
												$sql=$sql."SUBSTRING(estancia_gr.Fecha_Llegada,6,7) = '".$_SESSION['bus']['mes_grupo']."'";
											}
											if($_SESSION['bus']['dia_grupo']){
												if($and==1){
													$sql.=" where ";
													$and=2;
												}else{
													$sql.=" and ";
												}
												$sql=$sql."SUBSTRING(estancia_gr.Fecha_Llegada,9,10) = '".$_SESSION['bus']['dia_grupo']."'";
											}
											
									$sqldef=$sql.$ordengrupo;
									//echo $sqldef;
									$result = mysql_query($sqldef);
				                  for ($i=0;$i<mysql_num_rows($result);$i++){
									?>
										<tr align='left' class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
										 <?PHP
			  
									$fila = mysql_fetch_array($result);
									
									echo "<td>";
									if($fila['Nombre_Gr']==''){
										echo "&nbsp;";
									}else{
										echo $fila['Nombre_Gr'];
									}
									echo "</td><td>";
									if($fila['Direccion_Gr']==''){
										echo "&nbsp;";
									}else{
										echo $fila['Direccion_Gr'];
									}
									
									echo "</td><td>";
									if($fila['Localidad_Gr']==''){
										echo "&nbsp;";
									}else{
										echo $fila['Localidad_Gr'];
									}
									
									echo "</td>";
									$sqlpais="select * from pais where Id_Pais ='".$fila['Id_Pais_Nacionalidad']."'";
									$resultpais = mysql_query($sqlpais);
									$filapais = mysql_fetch_array($resultpais);
									echo "<td>";
									if($filapais['Nombre_Pais']==''){
										echo "&nbsp;";
									}else{
										echo $filapais['Nombre_Pais'];
									}
									echo "</td>";
									?>		
												
              <TD align='center'><?PHP echo "<a href='?pag=grupos.php&busqueda=si&ed=".$fila['Nombre_Gr']." '>"; ?><img src="../imagenes/botones/detalles.gif" width="17" height="20" alt='Detalles' border="0"></a></td>
											
              <TD align='center'><?PHP echo "<a href='?pag=grupos.php&busqueda=si&modif=".$fila['Nombre_Gr']." '>"; ?><img src="../imagenes/botones/modificar.gif" width="20" height="24" alt='Modificar' border="0"></a></td>

			  <TD align='center'><?PHP echo "<a href='?pag=grupos.php&busqueda=si&dl=".$fila['Nombre_Gr']." '>"; ?><img src="../imagenes/botones/estancia.gif" width="20" height="24" alt='Estancias' border="0"></a></td>
											
												
												
										</tr>
											<?PHP
					
											}
											mysql_close();
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