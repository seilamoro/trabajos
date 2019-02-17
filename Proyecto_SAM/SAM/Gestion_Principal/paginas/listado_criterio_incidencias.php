<?php
	if(isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias']=true) //Comprobando que se tiene permiso para realizar busquedas de Incidencias
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
	if($_GET['orincidencia']=="DNI_Inc"||$_GET['orincidencia']=="Nombre_Inc"||$_GET['orincidencia']=="Apellido1_Inc"||$_GET['orincidencia']=="Fecha_Inc"||$_GET['orincidencia']=="Nombre_Resp"){
	 	if(!$_SESSION['orincidencia']){$_SESSION['orincidencia']=1;}
		if($_SESSION['orincidencia']==1){
		$ordenincidencia=" order by ".$_GET['orincidencia']." DESC";
		$_SESSION['orincidencia']=2;
		}else{
		$ordenincidencia=" order by ".$_GET['orincidencia']." ASC";
		$_SESSION['orincidencia']=1;
	}}
	$_SESSION['retorno']=6;
		  ?>
		   <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > 
			<div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:94%;'>
						Listado de Incidencias
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Incidencias</div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table width="98%" border="0" cellpadding="0" cellspacing="0" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="left"> 
			<th width='75' align="center" style="padding:0px 0px 0px 0px;">
			<a class="enlace" href="?pag=listado_criterio_incidencias.php&orincidencia=DNI_Inc">D.N.I. </a></TH>
              <TH align="center"><a class="enlace" href="?pag=listado_criterio_incidencias.php&orincidencia=Nombre_Inc">Nombre</a></TH>
              <TH align="center"><a class="enlace" href="?pag=listado_criterio_incidencias.php&orincidencia=Apellido1_Inc">Apellidos</a></TH>
              <TH width='50' align="center"><a class="enlace" href="?pag=listado_criterio_incidencias.php&orincidencia=Fecha_Inc">Fecha</a></TH>
              <TH width="150" align="center"><a class="enlace" href="?pag=listado_criterio_incidencias.php&orincidencia=Nombre_Resp">Empleado</A></TH>
              <TH width="50" align="center">Detalles</th>
              <TH width="50" align="center">Modificar</th>
              <TH width="50" align="center">Eliminar</th>
            </tr>
          </thead>
          <tbody class="scrollContent">
		   <?PHP
		 
		 
		  $sql="Select * From incidencias ";
		  // $and es para controlar la sql
		    $and=1;
			  //recojo las variables de la pagina de busqueda y las meto en una session
		if($mi_formulario){
			  $_SESSION['bus']['dni_incidencia']=$_POST['dni_incidencia'];
			  $_SESSION['bus']['nombre_incidencia']=$_POST['nombre_incidencia'];
			  $_SESSION['bus']['apellido1_incidencia']=$_POST['apellido1_incidencia'];
			  $_SESSION['bus']['apellido2_incidencia']=$_POST['apellido2_incidencia'];
			  $_SESSION['bus']['empleado_incidencia']=$_POST['empleado_incidencia'];
			  $_SESSION['bus']['dia_incidencia']=$_POST['dia_incidencia'];
			  $_SESSION['bus']['mes_incidencia']=$_POST['mes_incidencia'];
			  $_SESSION['bus']['anyo_incidencia']=$_POST['anyo_incidencia'];
		}

			 
	/*
		 if($_POST['dia_incidencia']!=""&&$_POST['mes_incidencia']!=""&&$_POST['anyo_incidencia']!=""){
		 	if(!$_SESSION['bus']['fecha_incidencia']){$_SESSION['bus']['fecha_incidencia']=$_POST['anyo_incidencia']."-".$_POST['mes_incidencia']."-".substr($_POST['dia_incidencia'],1,2);}
			else{$_SESSION['bus']['fecha_incidencia']=$_POST['anyo_incidencia']."-".$_POST['mes_incidencia']."-".substr($_POST['dia_incidencia'],1,2);}
		 												}*/
														
				//creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta
		  		if($_SESSION['bus']['dni_incidencia']!="" || $_SESSION['bus']['dni_incidencia']=="0"){
				
				  if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				$sql=$sql." incidencias.DNI_Inc like '".$_SESSION['bus']['dni_incidencia']."%'";
				}
						
						if($_SESSION['bus']['nombre_incidencia']!=""){
				
				  if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				$sql=$sql." incidencias.Nombre_Inc like '".$_SESSION['bus']['nombre_incidencia']."%'";
				}
				if($_SESSION['bus']['apellido1_incidencia']!=""){
				
				  if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				$sql=$sql." incidencias.Apellido1_Inc like '".$_SESSION['bus']['apellido1_incidencia']."%'";
				}
					if($_SESSION['bus']['apellido2_incidencia']!=""){
				
				  if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				$sql=$sql." incidencias.Apellido2_Inc like '".$_SESSION['bus']['apellido2_incidencia']."%'";
				}
				
				/*if($_SESSION['bus']['fecha_incidencia']!=""){
				if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				
				$sql=$sql." and incidencias.Fecha_Inc  ='".$_SESSION['bus']['fecha_incidencia']."'";
				}*/
				if($_SESSION['bus']['dia_incidencia']!=""){
				if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				$sql=$sql." SUBSTRING(incidencias.Fecha_Inc,9,10) = '".$_SESSION['bus']['dia_incidencia']."'";
				}

				if($_SESSION['bus']['mes_incidencia']!=""){
				if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				
				$sql=$sql." SUBSTRING(incidencias.Fecha_Inc,6,7) = '".$_SESSION['bus']['mes_incidencia']."'";
				}
				if($_SESSION['bus']['anyo_incidencia']!=""){
				if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				
				$sql=$sql." SUBSTRING(incidencias.Fecha_Inc,1,4) = '".$_SESSION['bus']['anyo_incidencia']."'";
				}
				if($_SESSION['bus']['empleado_incidencia']!=""){
				
				  if($and==1){
				  $sql=$sql." where ";
				  $and=2;
				  }else{
				  $sql=$sql." and ";
				  }
				$sql=$sql." incidencias.Nombre_Resp = '".$_POST['empleado_incidencia']."'";
				}
				$sqldef=$sql.$ordenincidencia;
				//echo $sqldef;
				$result = mysql_query($sqldef);
				for ($i=0;$i<mysql_num_rows($result);$i++){
		  ?>
            <tr align='left' class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);"> 
              <?PHP
			  
		$fila = mysql_fetch_array($result);
		
		echo "<td>";
		if($fila['DNI_Inc']==''){
			echo "&nbsp;";
		}else{
			echo $fila['DNI_Inc'];
		}
		echo "</td><td>";
		if($fila['Nombre_Inc']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Nombre_Inc'];
		}
		echo "</td><td>";
		if($fila['Apellido1_Inc']=='' && $fila['Apellido2_Inc']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Apellido1_Inc']." ".$fila['Apellido2_Inc'];
		}
		echo "</td>";
		
		
		echo "<td align='center'>";
		if($fila['Fecha_Inc']==''){
			echo "&nbsp;";
		}else{
			echo substr($fila['Fecha_Inc'],8,2)."/".substr($fila['Fecha_Inc'],5,2)."/".substr($fila['Fecha_Inc'],0,4);
		}
		
		echo "</td>";
		echo "<td>";
		if($fila['Nombre_Resp']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Nombre_Resp'];
		}
		echo "</td>";
		?>
              <TD align='center'><?PHP echo "<a href='?pag=incidencias.php&dni=".$fila['DNI_Inc']."&busqueda=si&detalles=si&fecha_inc=".$fila['Fecha_Inc']." '>"; ?><img src="../imagenes/botones/detalles.gif" width="17" height="20" alt='Detalles' border='0'></a></td>
              <TD align='center'><?PHP echo "<a href='?pag=incidencias.php&dni=".$fila['DNI_Inc']."&busqueda=si&modificar=si&fecha_inc=".$fila['Fecha_Inc']." '>"; ?><img src="../imagenes/botones/modificar.gif" width="20" height="24" alt='Modificar' border='0'></a></td>
              <TD align='center'><?PHP echo "<a href='?pag=incidencias.php&dni=".$fila['DNI_Inc']."&busqueda=si&eliminar=si&fecha_inc=".$fila['Fecha_Inc']." '>"; ?><img src="../imagenes/botones/eliminar.gif" width="15" height="16" alt='Eliminar' border='0'></a></td>
            </tr>
			<?PHP
			}
			?>
            
          </tbody>
        </table>
      </div></td>
	  </tr>
	  <tr>
		<td align="left"><a href=".?pag=busq.php"><img src="../imagenes/botones/volver.gif" width="30" height="30" border="0" alt="Volver"></a></td>
	  </tr>
</table>
<?php
		}else{	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA</div>";
}
?>