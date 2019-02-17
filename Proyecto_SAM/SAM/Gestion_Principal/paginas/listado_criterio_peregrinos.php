<?php
	if(isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']=true) //Comprobando que se tiene permiso para realizar busquedas de Peregrinos
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
	//conecto con  la base de datos
	
		  $sqlcri="";
		  @$db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);
		//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	if($_GET['orpere']=="Nombre_Cl"||$_GET['orpere']=="DNI_Cl"||$_GET['orpere']=="Apellido1_Cl"||$_GET['orpere']=="Localidad_Cl"||$_GET['orpere']=="Id_Pais"){
	 	if(!$_SESSION['orpere']){$_SESSION['orpere']=1;}
		if($_SESSION['orpere']==1){
		$ordenpere=" order by cliente.".$_GET['orpere']." DESC";
		$_SESSION['orpere']=2;
		}else{
		$ordenpere=" order by cliente.".$_GET['orpere']." ASC";
		$_SESSION['orpere']=1;
	}
	
	}
	$_SESSION['retorno']=3;
		  ?>
		 
		  <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > 
			<div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:94%;'>
						Listado de Peregrinos
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Peregrinos</div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table border="0" cellpadding="0" cellspacing="0" width="98%" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="left"> 
			<th align="center" style="padding:0px 0px 0px 0px;">
<a class="enlace" href="?pag=listado_criterio_peregrinos.php&orpere=DNI_Cl">D.N.I.</a></Th>
											<TH align="center"><a class="enlace" href="?pag=listado_criterio_peregrinos.php&orpere=Nombre_Cl">Nombre</a></Th>
											<TH align="center"><a class="enlace" href="?pag=listado_criterio_peregrinos.php&orpere=Apellido1_Cl">Apellidos</a> </Th>
											<TH align="center"><a class="enlace" href="?pag=listado_criterio_peregrinos.php&orpere=Localidad_Cl">Localidad</a></Th>
											<TH align="center"><a class="enlace" href="?pag=listado_criterio_peregrinos.php&orpere=Id_Pais">País</a></Th>
											<TH width="50px" align="center">Detalles</th>
											<TH width="50px" align="center">Modificar</th>
											<TH width="50px" align="center">Eliminar</th>
											
										</tr>
									</thead>
									<tbody class="scrollContent">
									 <?PHP
									  //recojo las variables de la pagina de busqueda y las meto en una session
								if($mi_formulario){
									  $_SESSION['bus']['dni_pere']=$_POST['dni_pere'];
									  $_SESSION['bus']['nombre_pere']=$_POST['nombre_pere'];
									  $_SESSION['bus']['apellido1_pere']=$_POST['apellido1_pere'];
									  $_SESSION['bus']['apellido2_pere']=$_POST['apellido2_pere'];
									  $_SESSION['bus']['localidad_pere']=$_POST['localidad_pere'];
									  $_SESSION['bus']['provincia_pere']=$_POST['provincia_pere'];
									  $_SESSION['bus']['pais_pere']=$_POST['pais_pere'];
									  $_SESSION['bus']['mp_pere']=$_POST['mp_pere'];
									  $_SESSION['bus']['dia_pere']=$_POST['dia_pere'];
									  $_SESSION['bus']['mes_pere']=$_POST['mes_pere'];
									  $_SESSION['bus']['anyo_pere']=$_POST['anyo_pere'];
								}

									 
		 
		 
		  //Select * From cliente,pernocta Where   pernocta.DNI_Cl = cliente.DNI_Cl And cliente.Nombre_Cl like '%manuel%'
		 //creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta
		 $fec_llegada='';
		  $and=1;


				if($_SESSION['bus']['anyo_pere']){
					 if($and==1){
				  $fec_llegada.=" where ";
				  $and=2;
				  }else{
				  $fec_llegada.=" and ";
				  }
			  $fec_llegada=$fec_llegada."SUBSTRING(pernocta_p.Fecha_Llegada,1,4) = '".$_SESSION['bus']['anyo_pere']."'";
				}
				if($_SESSION['bus']['mes_pere']){
					 if($and==1){
				  $fec_llegada.=" where ";
				  $and=2;
				  }else{
				  $fec_llegada.=" and ";
				  }
			  $fec_llegada=$fec_llegada."SUBSTRING(pernocta_p.Fecha_Llegada,6,2) = '".$_SESSION['bus']['mes_pere']."'";
				}
				if($_SESSION['bus']['dia_pere']){
					 if($and==1){
				  $fec_llegada.=" where ";
				  $and=2;
				  }else{
				  $fec_llegada.=" and ";
				  }
			  $fec_llegada=$fec_llegada."SUBSTRING(pernocta_p.Fecha_Llegada,9,2) = '".$_SESSION['bus']['dia_pere']."'";
				}
				if($_SESSION['bus']['mp_pere']){
				 if($and==1){
				  $fec_llegada.=" where ";
				  $and=2;
				  }else{
				  $fec_llegada.=" and ";
				  }
			  $fec_llegada=$fec_llegada."pernocta_p.M_P = '".$_SESSION['bus']['mp_pere']."'";
				}


		  $sql="Select * From cliente where dni_cl in( select dni_cl from pernocta_p ".$fec_llegada.")";
		  		if($_SESSION['bus']['dni_pere'] || $_SESSION['bus']['dni_pere']=="0"){
				$sql=$sql." and cliente.DNI_Cl like '".$_SESSION['bus']['dni_pere']."%'";
				}
				if($_SESSION['bus']['nombre_pere']){
				$sql=$sql." and cliente.Nombre_Cl like '".$_SESSION['bus']['nombre_pere']."%'";
				}
				if($_SESSION['bus']['apellido1_pere']){
				$sql=$sql." and cliente.Apellido1_Cl like '".$_SESSION['bus']['apellido1_pere']."%'";
				}
				if($_SESSION['bus']['apellido2_pere']){
				$sql=$sql." and cliente.Apellido2_Cl like '".$_SESSION['bus']['apellido2_pere']."%'";
				}
				if($_SESSION['bus']['localidad_pere']){
				$sql=$sql." and cliente.Localidad_Cl like '".$_SESSION['bus']['localidad_pere']."%'";
				}
				if($_SESSION['bus']['provincia_pere']!=""){
				$sql=$sql." and cliente.Id_Pro = '".$_SESSION['bus']['provincia_pere']."'";
				}
				if($_SESSION['bus']['pais_pere']){
				$sql=$sql." and cliente.Id_Pais = '".$_SESSION['bus']['pais_pere']."'";
				}
				
				/*if($_SESSION['bus']['fecha_pere']){
				$sql=$sql." and pernocta_p.Fecha_Llegada  like '".$_SESSION['bus']['fecha_pere']."%'";
				}*/
				
				
				$sqldef=$sql.$ordenpere;
				//echo $sqldef;
				$result = mysql_query($sqldef);
				for ($i=0;$i<mysql_num_rows($result);$i++){
		  ?>
										<tr align='left' class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
											<?PHP
			  
										$fila = mysql_fetch_array($result);
										
										echo "<td>";
										if($fila['DNI_Cl']==''){
											echo "&nbsp;";
										}else{
											echo $fila['DNI_Cl'];
										}
										echo "</td><td>";
										if($fila['Nombre_Cl']==''){
											echo "&nbsp;";
										}else{
											echo $fila['Nombre_Cl'];
										}
										echo "</td><td>";
										if($fila['Apellido1_Cl']=='' && $fila['Apellido2_Cl']==''){
											echo "&nbsp;";
										}else{
											echo $fila['Apellido1_Cl']." ".$fila['Apellido2_Cl'];
										}
										echo "</td><td>";
										if($fila['Localidad_Cl']==''){
											echo "&nbsp;";
										}else{
											echo $fila['Localidad_Cl'];
										}
										echo "</td>";
										$sqlpais="select * from pais where Id_Pais ='".$fila['Id_Pais']."'";
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
												
              <TD align='center'><?PHP echo "<a href='?pag=peregrinos.php&busqueda=si&detalles=si&dni=".$fila['DNI_Cl']."&tipo_doc=".$fila['Tipo_documentacion']." '>"; ?><img src="../imagenes/botones/detalles.gif" width="17" height="20" alt='Detalles' border="0"></a></td>
												
              <TD align='center'><?PHP echo "<a href='?pag=peregrinos.php&busqueda=si&modificar=si&dni=".$fila['DNI_Cl']."&tipo_doc=".$fila['Tipo_documentacion']." '>"; ?><img src="../imagenes/botones/modificar.gif" width="20" height="24" alt='Modificar' border="0"></a></td>
												
              <TD align='center'><?PHP echo "<a href='?pag=peregrinos.php&busqueda=si&eliminar=si&dni=".$fila['DNI_Cl']."&tipo_doc=".$fila['Tipo_documentacion']." '>"; ?><img src="../imagenes/botones/eliminar.gif" width="15" height="16" alt='Eliminar' border="0"></a></td>
												
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