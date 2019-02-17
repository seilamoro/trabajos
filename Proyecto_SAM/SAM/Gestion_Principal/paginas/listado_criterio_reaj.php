<?php
	if(isset($_SESSION['permisoREAJ']) && $_SESSION['permisoREAJ']=true) //Comprobando que se tiene permiso para realizar busquedas de REAJ
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
		 //conecto con la base de datos
		  @ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);
	//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	if($_GET['orreaj']=="Nombre_Solic"||$_GET['orreaj']=="Apellido1_Solic"||$_GET['orreaj']=="DNI_Solic"||$_GET['orreaj']=="Nombre_Carnet"||$_GET['orreaj']=="Fecha"){
		
		if(!$_SESSION['orreaj']){$_SESSION['orreaj']=1;}
		
		if($_SESSION['orreaj']==1){
		$ordenreaj=" order by ".$_GET['orreaj']." DESC";
		$_SESSION['orreaj']=2;
		}else{
		$ordenreaj=" order by ".$_GET['orreaj']." ASC";
		$_SESSION['orreaj']=1;
	}
	
	}
	$_SESSION['retorno']=8;
		  ?>
		   <table border="0" id="tabla_detalles" align="center" width="85%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > 
			<div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:94%;'>
						Listado de Carnets REAJ
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
              <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
              <div class="titulo" style="width:96.9%;text-align:center;">Listado de Carnets REAJ</div>
              </div>
              <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:555px;background-color: #F4FCFF;" id="tabla_listado">
               <table width="98%" border="0" cellpadding="0" cellspacing="0" class="scrollTable">
                <thead class="fixedHeader">
	
	
            <tr align="left"> 
			<th width='100' align="center" style="padding:0px 0px 0px 0px;">
			<a class="enlace" href="?pag=listado_criterio_reaj.php&orreaj=DNI_Solic">D.N.I. </a></TH>
              <TH align="center"><a class="enlace" href="?pag=listado_criterio_reaj.php&orreaj=Nombre_Solic">Nombre</a></TH>
              <TH align="center"><a class="enlace" href="?pag=listado_criterio_reaj.php&orreaj=Apellido1_Solic">Apellidos</a></TH>
              <TH width="100" align="center"><a class="enlace" href="?pag=listado_criterio_reaj.php&orreaj=Nombre_Carnet">Tipo</a></th>
              <TH width="100" align="center"><a class="enlace" href="?pag=listado_criterio_reaj.php&orreaj=Fecha">Fecha</a></th>
              <TH width="50" align="center">Modificar</th>
              <TH width="50" align="center">Eliminar</th>
            </tr>
          </thead>
          <tbody class="scrollContent">
		  <?PHP
		  //recojo las variables de la pagina de busqueda y las meto en una session
		 		  $sql="Select * From solicitante,reaj Where solicitante.Id_Carnet = reaj.Id_Carnet ";
			if($mi_formulario){
				  $_SESSION['bus']['tipo_reaj']=$_POST['tipo_reaj'];
				  $_SESSION['bus']['dni_reaj']=$_POST['dni_reaj'];
				  $_SESSION['bus']['nombre_reaj']=$_POST['nombre_reaj'];
				  $_SESSION['bus']['apellido1_reaj']=$_POST['apellido1_reaj'];
				  $_SESSION['bus']['apellido2_reaj']=$_POST['apellido2_reaj'];
				  $_SESSION['bus']['dia_reaj']=$_POST['dia_reaj'];
				  $_SESSION['bus']['mes_reaj']=$_POST['mes_reaj'];
				  $_SESSION['bus']['anyo_reaj']=$_POST['anyo_reaj'];
			}
				  
		  
				  
				   //creo la sql y si tengo opciones de busqueda pongo condiciones a la consulta
				  		if($_SESSION['bus']['tipo_reaj']){
				$sql=$sql." and reaj.Id_Carnet like '".$_SESSION['bus']['tipo_reaj']."%'";
				}
		  		if($_SESSION['bus']['dni_reaj'] || $_SESSION['bus']['dni_reaj']=="0"){
				$sql=$sql." and solicitante.DNI_Solic like '".$_SESSION['bus']['dni_reaj']."%'";
				}
				if($_SESSION['bus']['nombre_reaj']){
				$sql=$sql." and solicitante.Nombre_Solic like '".$_SESSION['bus']['nombre_reaj']."%'";
				}
				if($_SESSION['bus']['apellido1_reaj']){
				$sql=$sql." and solicitante.Apellido1_Solic like '".$_SESSION['bus']['apellido1_reaj']."%'";
				}
				if($_SESSION['bus']['apellido2_reaj']){
				$sql=$sql." and solicitante.Apellido2_Solic like '".$_SESSION['bus']['apellido2_reaj']."%'";
				}
				 if($_SESSION['bus']['anyo_reaj']){
					$sql=$sql." and SUBSTRING(solicitante.Fecha,1,4) = '".$_SESSION['bus']['anyo_reaj']."'";
				}
				if($_SESSION['bus']['mes_reaj']){
					$sql=$sql." and SUBSTRING(solicitante.Fecha,6,7) = '".$_SESSION['bus']['mes_reaj']."'";
				}
				if($_SESSION['bus']['dia_reaj']){
					$sql=$sql." and SUBSTRING(solicitante.Fecha,9,10) = '".$_SESSION['bus']['dia_reaj']."'";
				}
				
				$sqldef=$sql.$ordenreaj;
				//echo $sqldef;
				$result = mysql_query($sqldef);
				for ($i=0;$i<mysql_num_rows($result);$i++){
		  ?>
            <tr align='left' class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);"> 
             <?PHP 
			 $fila = mysql_fetch_array($result);
		
		echo "<td>";
		if($fila['DNI_Solic']==''){
			echo "&nbsp;";
		}else{
			echo $fila['DNI_Solic'];
		}
		echo "</td><td>";
		if($fila['Nombre_Solic']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Nombre_Solic'];
		}
		echo "</td><td>";
		if($fila['Apellido1_Solic']=='' && $fila['Apellido2_Solic']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Apellido1_Solic']." ".$fila['Apellido2_Solic'];
		}
		echo "</td>";
		echo "<td>";
		if($fila['Nombre_Carnet']==''){
			echo "&nbsp;";
		}else{
			echo $fila['Nombre_Carnet'];
		}
		echo "</td>";
		echo "<td align='center'>";
		if($fila['Fecha']==''){
			echo "&nbsp;";
		}else{
			echo substr($fila['Fecha'],8,2)."/".substr($fila['Fecha'],5,2)."/".substr($fila['Fecha'],0,4);
		}
		echo "</td>";
		?>
			 
			  
              <TD align='center'><?PHP echo "<a href='?pag=reaj.php&busqueda=si&modificar=si&dnir=".$fila['DNI_Solic']." '>"; ?><img src="../imagenes/botones/modificar.gif" width="20" height="24" alt='Modificar' border="0"></a></td>
              <TD align='center'><?PHP echo "<a href='?pag=reaj.php&busqueda=si&eliminar=si&dnir=".$fila['DNI_Solic']." '>"; ?><img src="../imagenes/botones/eliminar.gif" width="15" height="16" alt='Eliminar' border="0"></a></td>
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
   			 <td align="left"><a href=".?pag=busq.php"><img src="../imagenes/botones/volver.gif" width="30" height="30" border="0" alt="Volver"></a></td>
  		</tr>	
	</table>
<?php
		}else{	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA</div>";
}
?>