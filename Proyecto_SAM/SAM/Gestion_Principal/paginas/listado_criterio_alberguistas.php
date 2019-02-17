<?php
	if(isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']=true) //Comprobando que se tiene permiso para realizar busquedas de Alberguistas
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

//session_start();
		//conecto con la base de datos
		  @ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);
	//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	if($_GET['oralber']=="Nombre_Cl"||$_GET['oralber']=="DNI_Cl"||$_GET['oralber']=="Apellido1_Cl"||$_GET['oralber']=="Localidad_Cl"||$_GET['oralber']=="Id_Pais"){
	 	if(!$_SESSION['oralber']){$_SESSION['oralber']=1;}
		if($_SESSION['oralber']==1){
		$ordenalber=" order by cliente.".$_GET['oralber']." DESC";
		$_SESSION['oralber']=2;
		}else{
		$ordenalber=" order by cliente.".$_GET['oralber']." ASC";
		$_SESSION['oralber']=1;
	}
	
	}
	$_SESSION['retorno']=1;
		  ?>
		  
		  
		
		  <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" >  <div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:94%;'>
						Listado de Alberguistas
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Alberguistas</div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table border="0" cellpadding="0" cellspacing="0" width="98%" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="center"> 
			<th align="center" style="padding:0px 0px 0px 0px;">
			<a class="enlace" href="?pag=listado_criterio_alberguistas.php&oralber=DNI_Cl">D.N.I.</a></th>
              <th align="center" ><a class="enlace" href="?pag=listado_criterio_alberguistas.php&oralber=Nombre_Cl">Nombre</a></th>
              <th align="center"  ><a class="enlace" href="?pag=listado_criterio_alberguistas.php&oralber=Apellido1_Cl">Apellidos</a></th>
              <th align="center"  ><a class="enlace" href="?pag=listado_criterio_alberguistas.php&oralber=Localidad_Cl">Localidad</a></th>
              <th align="center"  ><a class="enlace" href="?pag=listado_criterio_alberguistas.php&oralber=Id_Pais">País</a></th>
              <th width="50px" align="center">Detalles</th>
              <th width="50px" align="center">Modificar</th>
              <th width="50px" align="center">Eliminar</th>
            </tr>
          </thead>
          <tbody class="scrollContent" >
		  <?PHP
		  //recojo las variables de la pagina de busqueda y las meto en una session
		  if($mi_formulario){
		 
			$_SESSION['bus']['dni_alber']=$_POST['dni_alber'];
			$_SESSION['bus']['nombre_alber']=$_POST['nombre_alber'];
			$_SESSION['bus']['apellido1_alber']=$_POST['apellido1_alber'];
			$_SESSION['bus']['apellido2_alber']=$_POST['apellido2_alber'];
			$_SESSION['bus']['localidad_alber']=$_POST['localidad_alber'];
			$_SESSION['bus']['provincia_alber']=$_POST['provincia_alber'];
			$_SESSION['bus']['pais_alber']=$_POST['pais_alber'];
		
			$_SESSION['bus']['anyo_alber']=$_POST['anyo_alber'];
			$_SESSION['bus']['mes_alber']=$_POST['mes_alber'];
			$_SESSION['bus']['dia_alber']=$_POST['dia_alber'];
		  }
			
		 
		 //creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta
		 
			$fec_llegada='';
		  $and=1;

			// echo $_SESSION['bus']['anyo_alber']."#";
		  if($_SESSION['bus']['anyo_alber']){
			  if($and==1){
				  $fec_llegada.=" where ";
				  $and=2;
				  }else{
				  $fec_llegada.=" and ";
				  }
			  $fec_llegada=$fec_llegada."SUBSTRING(pernocta.Fecha_Llegada,1,4) = '".$_SESSION['bus']['anyo_alber']."'";
		  }
		  //echo $_SESSION['bus']['mes_alber']."#";
		  if($_SESSION['bus']['mes_alber']){
			   if($and==1){
				  $fec_llegada=$fec_llegada." where ";
				  $and=2;
				  }else{
				  $fec_llegada=$fec_llegada." and ";
				  }
			  $fec_llegada=$fec_llegada."SUBSTRING(pernocta.Fecha_Llegada,6,2) = '".$_SESSION['bus']['mes_alber']."'";
		  }
		  //echo $_SESSION['bus']['dia_alber']."#";
		   if($_SESSION['bus']['dia_alber']){
			   if($and==1){
				  $fec_llegada.=" where ";
				  $and=2;
				  }else{
				  $fec_llegada.=" and ";
				  }
			  $fec_llegada.="SUBSTRING(pernocta.Fecha_Llegada,9,2) = '".$_SESSION['bus']['dia_alber']."'";
		  }

		  $sql="Select * From cliente where dni_cl in (select dni_cl from pernocta ".$fec_llegada.")";

		  if($_SESSION['bus']['dni_alber'] || $_SESSION['bus']['dni_alber']=="0"){
		  $sql=$sql." and cliente.DNI_Cl like '".$_SESSION['bus']['dni_alber']."%'";
		  }
		   if($_SESSION['bus']['nombre_alber']){
		 $sql=$sql." and cliente.Nombre_Cl like '".$_SESSION['bus']['nombre_alber']."%'";
		  }
		   if($_SESSION['bus']['apellido1_alber']){
		  $sql=$sql." and cliente.Apellido1_Cl like '".$_SESSION['bus']['apellido1_alber']."%'";
		  }
		   if($_SESSION['bus']['apellido2_alber']){
		  $sql=$sql." and cliente.Apellido2_Cl like '".$_SESSION['bus']['apellido2_alber']."%'";
		  }
		   if($_SESSION['bus']['localidad_alber']){
		  $sql=$sql." and cliente.Localidad_Cl like '".$_SESSION['bus']['localidad_alber']."%'";
		  }
		   if($_SESSION['bus']['provincia_alber']){
		  $sql=$sql." and cliente.Id_Pro ='".$_SESSION['bus']['provincia_alber']."%'";
		  }
		  if($_SESSION['bus']['pais_alber']){
		  $sql=$sql." and cliente.Id_Pais = '".$_SESSION['bus']['pais_alber']."%'";
		  }
		  /*if($_SESSION['bus']['fecha_alber']){
		  $sql=$sql." and pernocta.Fecha_Llegada like '".$_SESSION['bus']['fecha_alber']."%'";
		  }*/
		 
				
				
				$sqldef=$sql.$ordenalber;
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
			<TD align='center'><?PHP echo "<a href='?pag=alberguistas.php&busqueda=si&detalles=si&dni=".$fila['DNI_Cl']."&tipo_doc=".$fila['Tipo_documentacion']." '>"; ?><img src="../imagenes/botones/detalles.gif" width="17" height="20" alt='Detalles' border="0"></a></td>
              <TD align='center'><?PHP echo "<a href='?pag=alberguistas.php&busqueda=si&modificar=si&dni=".$fila['DNI_Cl']."&tipo_doc=".$fila['Tipo_documentacion']." '>"; ?><img src="../imagenes/botones/modificar.gif" width="20" height="24" alt='Modificar' border="0"></a></td>
              <TD align='center'><?PHP echo "<a href='?pag=alberguistas.php&busqueda=si&eliminar=si&dni=".$fila['DNI_Cl']."&tipo_doc=".$fila['Tipo_documentacion']." '>"; ?><img src="../imagenes/botones/eliminar.gif" width="15" height="16" alt='Eliminar' border="0"></a></td>
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
							<a href=".?pag=busq.php" name="Volver"><img src="../imagenes/botones/volver.gif" border="0" alt="Volver"></a>
						</td>
					</tr>	
				</table>
<?php
		}else{	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA</div>";
}
?>