<?session_start();?>	
<script language="JavaScript">

// Esta funcion cambia si se ha seleccionado correctivo u mantenimiento
function cambiar(n){
	if(n==1){
		document.getElementById('ope2').checked =false;
		document.getElementById('ope1').checked =true;
	}else{
		document.getElementById('ope2').checked =true;
		document.getElementById('ope1').checked =false;
	}
}
//Esta funcion abre una pagina u otra dependiendo si elige correctivo o mantenimiento

function IrOperaciones (id,m,a){
	var i;
	var fech;
	if(document.getElementById('ope1').checked ==true){
		window.location.href="principal.php?pag=mtto_correctivo.php&num="+id+"&instal=si";
	}else{	
		if(m<10){
			fech="0"+m+"-"+a;
		}else{
			fech=m+"-"+a;
		}
		window.location.href="principal.php?pag=mtto_preventivo_centro.php&centro="+id+"&fecha="+fech;
	}
}


//Abre la pagina de datos del centro en tiempo real
function abrirDatos(m,m2,d,d2,id){
	var meses=new Array(" ","Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	 if(m==0){
		window.location.href="principal.php?pag=esquema.php&id_centro="+id;
		//window.location.href="index.php?pag=instalaciones.php&id="+id;
   }else{	   
	   
		alert("No hay datos. Puesto que la operatividad de este centro comienza el dia "+d+" de "+meses[m]+ " y termina el dia " +d2+ " de "+meses[m2]);
	}
}

//Esta funcion evalua, si el centro no tiene componentes muestra un mensaje, si tiene componentes recarga la pagina con los
//componentes que tiene el centro
function evaluar(centro,comp){
	if(comp>0){
		window.location.href="index.php?pag=instalaciones.php&num="+centro;
	}
	else{
		alert('No tiene ningun componente');
	}

}


//-->

		
</script>	

<!--Conexión a BD-->
<?php
	
@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
mysql_select_db("scies");
//Para saber el navegador utilizado
$nav = $_SERVER['HTTP_USER_AGENT'];
$nav_enc = strstr ($nav, 'MSIE');?>


<div class="pagina" style='top:200;' >

<div class="listado1" style='align:center' >
				
	
	
      <TR>             
              <? 	

				$sql="select * from centro where Id_Centro='".$_GET['id']."';";	
				$result=mysql_query($sql);			
				$cont=0;
			
				if(mysql_num_rows($result)>0){
					$conf['menu_contextual'][0]['texto'] = 'Ver';
					$conf['menu_contextual'][0]['accion'] = 'Show';
					$conf['general']['nombre_formulario'] = 'formulario_listado';

					if(!$nav_enc){?>
						<table class='listado' style='align:center;padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;' >
	
					<?}else{?>
		
						<table  cellspacing='0' cellpadding='0'style="align:center;padding: 0px 0px 0px 0px;">
							
					<?}?>

				<?for($i=0;$i<mysql_num_rows($result);$i++){					
				 $fila=mysql_fetch_array($result);
				 $cont=$cont+1;
                 $fila['Nombre']=substr($fila['Nombre'],0,50);?>
				 <div class="titulo1" ><b><?echo strtoupper($fila['Nombre']);?></b>	
				 <div class="titulo2" ><b><?echo "Datos Instalación";?></b></div></div>
				 <TD cellspacing='0' cellpadding='0' style="padding: 0px 0px 0px 0px;"> 
				  <TABLE   style="padding: 0px 0px 0px 0px;" cellspacing='0' cellpadding='0' > 
					<thead>						
						<tr>
							<th class="titulo3" width='250'   style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;" >Localización</th>	
							<th class="titulo3" width='250' style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;" ></th>
							<th class="titulo3" width='500' style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;"  >Detalles Sondas</th>
						</tr>
						</thead>
					 <tr>
					
                       <TD  valign='top' style='padding: 0px 0px 0px 0px;' style='font-size: 16px;font-family: Georgia;letter-spacing: 2px;'>
                          </font><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					 
					<?if(file_exists("imagenes/fotos/Centro". $fila['Id_Centro']."/Centro". $_GET['id']."_1.gif")){?>
						
							<IMG SRC="imagenes/fotos/Centro<?echo $fila['Id_Centro']?>/Centro<?echo $_GET['id']?>_1.gif" WIDTH="110" HEIGHT="60"><br><br>
					<?}elseif(file_exists("imagenes/fotos/Centro".$fila['Id_Centro']."/Centro".$_GET['id']."_1.bmp")){?>
						
							<IMG SRC="imagenes/fotos/Centro<?echo $fila['Id_Centro']?>/Centro<?echo $_GET['id']?>_1.bmp" WIDTH="110" HEIGHT="60"><br><br>	
					<?}elseif(file_exists("imagenes/fotos/Centro".$fila['Id_Centro']."/Centro".$_GET['id']."_1.jpg")){?>
							<IMG SRC="imagenes/fotos/Centro<?echo $fila['Id_Centro']?>/Centro<?echo $_GET['id']?>_1.jpg" WIDTH="160" HEIGHT="120"><br><br>
					<?}else{?>
							<IMG SRC="imagenes/fotos/NULL.jpg" WIDTH="160" HEIGHT="120"><br><br>
							
							
						<?}?>          
					                            
                       </TD>
					   <td valign='top' style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;">
					   <!--Tabla con los datos principales del centro-->
						<table valign='top' width='300' >
						<tr>
							<td class="titulo" height='20'></td>
							<td class="titulo"></td>
						  </tr>
						  <tr>
							<td class="titulo"><B>Dirección</B></td>
							<td class="titulo" ><?echo $fila['Direccion']?></td>
						  </tr>
						    <tr>
							<td class="titulo"><B>Teléfono</B></td>
							<td class="titulo"><?echo $fila['Telefono']?></td>
						  </tr>
						   <tr>
							<td class="titulo"><B>Localidad</B></td>
							<td class="titulo"><?echo $fila['Localidad']?></td>
						  </tr>
						  </table></td>
					 
                       <!---->
					   <TD  valign='top' align='center'  colspan='2'> <br>                         
                         
                         
						
						  <!--Tabla parametros-->							
                           <?$sql3="select * from parametro inner join sonda where parametro.Id_Parametro= sonda.Id_Parametro and sonda.Id_Centro='".$_GET['id']."' order by sonda.Descripcion_Sonda ;";
							 $result3=mysql_query($sql3);
							  
							 if(mysql_num_rows($result3)){?>
								
								
								
								<?if(mysql_num_rows($result3)>=3){?>
								<TABLE class='listados' style='width:320;' cellspacing='0' cellpadding='0' valign='top' align='center'><!--tabla con los parametros del centro y las sondas que contiene el centro-->
								
								<thead >
									
									<tr>
									<th class="titulo" id="borde_idb" width="240"><div class="borde" style='height:20px;'></div><div class="centro">
										<a class="nombre" href="#">	<div>Parámetros</div></a></div></th>	
									<th class="titulo" id="borde_idb"><div class="borde" style='height:20px;'></div><div class="centro">
										<a class="nombre" href="#">	<div>Sonda</div></a></div></th>								
									</tr>
								</thead>
								
								</table>
									<div align='center' class="tableContainer"  style="background-color:#e6e3111; border: 0px solid #000000; height:93;width:294;overflow-y:auto;">
									<table class='listado_inst'  style='padding:0px 0px 0px 0px;width:300;' align='center' cellspacing='0'  border='1'>
								<?}else{?>
								<TABLE class='listados' style='width:320;' cellspacing='0' cellpadding='0' valign='top' align='center'><!--tabla con los parametros del centro y las sondas que contiene el centro-->
								
								<thead >
									
									<tr>
									<th class="titulo" id="borde_idb" width="240"><div class="borde" style='height:20px;'></div><div class="centro">
										<a class="nombre" href="#">	<div>Parámetros</div></a></div></th>	
									<th class="titulo" id="borde_idb"><div class="borde" style='height:20px;'></div><div class="centro">
										<a class="nombre" href="#">	<div>Sonda</div></a></div></th>								
									</tr>
								</thead>
								
								</table>
									<div align='center' class="tableContainer"  style="background-color:#e6e3111; border: 0px solid #000000;width:300; height:93;overflow-y:auto;">
									<table class='listado_inst'  style='padding:0px 0px 0px 0px;width:320;' align='center' cellspacing='0'  border='1'>
									
								<?}?>
								<tbody>
								<?for($i3=0;$i3<mysql_num_rows($result3);$i3++){?>
									
								<a href="#"><tr> 
								  <?$fila3=mysql_fetch_array($result3);?>										
								  <td title='<?echo $fila3['Nombre_Param'];?>' width="227" >
								   <?echo $fila3['Nombre_Param'];?></td>
								  <td title='<?echo $fila3['Descripcion_Sonda'];?>'align='center'>
								   <?echo $fila3['Descripcion_Sonda'];?></td>
								   <?$numero_sonda=$i3?>
								</tr></a>				
									<?}?>
								</tbody>
								</table></div>
								
							 <?}?>
									   
								  

							
							     
								 <?}?>
	                     
				      
						</TD>
                    </TR>
                    
					
					
					 
					<? //Compruebo si tiene componentes para enviarselo a la funcion java
						$sql_pest="select * from `centro_comp` cen inner join componente comp where cen.Id_Centro ='".$fila['Id_Centro']."' and cen.Id_Componente = comp.Id_Componente;";
						$result_pest=mysql_query($sql_pest);
						$cont_result_pest=mysql_num_rows($result_pest);


					?>				
					 
			
				
					<TR>
					<TD colspan='4' style='padding:0px 0px 0px 0px;cellpadding:0;cellspacing:0;'><!-- nuevo codigo-->

						<div class='titulo2' align='top' width='100%'><B>Componentes</B></div>	<br>
					
						<!--Ahora la tabla de los datos de cada componente-->
						<?if (!$nav_enc){?>
							<table cellspacing='0' cellpadding='0' > 
							<?}else{?>						
							<table cellspacing='0' cellpadding='0'  > 
								
							<?}?>
							
							<? if($_GET['ti']==''){		
								$_GET['ti']=$tipo1; //si es la 1º vez que entra y no hay nada seleccionado es el tipo 1?> 		
								
							<?}?>
								 <!--Datos del componente-->
								
								 <tr>
								 <td valign='top' align='left' colspan='2' style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;">
								 <!--Extraigo los componentes una pestaña para cada uno-->	
								      
									   <?$sql_pest="select * from `centro_comp` cen inner join componente comp where cen.Id_Centro ='".$_GET['id']."' and cen.Id_Componente = comp.Id_Componente order by comp.Nombre;";
										$result_pest=mysql_query($sql_pest);?>
										<!--Si hay datos correspondientes a los componentes del centro-->
										<table  style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;" valign='top' align='left'  width='950' > 
										<tr  >
										<td  cellpadding='0' style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;"><div  id="cabecera" >
										<?$hast=-1;
										if (mysql_num_rows($result_pest)>8 &&  mysql_num_rows($result_pest)<16){
											$hast=(mysql_num_rows($result_pest))-8;
											$hast=$hast-1;
											
										
										}?>
										<?for($c=0;$c<mysql_num_rows($result_pest);$c++){
											$fila2=mysql_fetch_array($result_pest);
											$lon=strlen($fila2['Nombre']);//Si el nombre del componenete es excesivamente largo lo corto			
											
											if($c==0){
												$tipo1=$fila2['Nombre'];
												$id_tip1=$fila2['Id_Componente'];
											

											}
											$tip=$fila2['Nombre'];
											if ($lon>19){				
												$fila2['Nombre']=substr($fila2['Nombre'],0,18);
											}
											if ($_GET['activo']==$fila2['Nombre']){//si esta seleccionada la pestaña con ese nombre entonces aplico el estilo?>
												<a class='#'  style='background-position:-0% -42px;color: #747450;' href="principal.php?pag=instalaciones.php&ti=<?echo $tip?>&activo=<?echo $fila2['Nombre'];?>&id=<?echo $fila['Id_Centro'];?>&id_tip=<?echo $fila2['Id_Componente'];?>"><span style='background-position:100% -42px;color:#434235;'><B><?echo $fila2['Nombre']?> </B></span></a>
												<?$n=$fila['Id_Centro']?>	
											<?}else{
												
												if(($c==0 ) && (!$_GET['id_tip']) ){?>
															<a class='#'  style='background-position:-0% -42px;color: #747450;' href="principal.php?pag=instalaciones.php&ti=<?echo $tip?>&activo=<?echo $fila2['Nombre'];?>&id=<?echo $fila['Id_Centro'];?>&id_tip=<?echo $fila2['Id_Componente'];?>"><span style='background-position:100% -42px;color:#434235;'><B><?echo $fila2['Nombre']?> </B></span></a>
												

												<?}else{?>
													
													<a class='#' style='color: #747450;'  href="principal.php?pag=instalaciones.php&ti=<?echo $tip?>&id=<?echo $fila['Id_Centro'];?>&id_tip=<?echo $fila2['Id_Componente'];?>&activo=<?echo $fila2['Nombre'];?>"><span ><?echo $fila2['Nombre']?></span></a>
													<?}?>
												
											<?}
											if($c==$hast){?>
												<br><br>
											<?}
											
									   }?>
										</div></td></tr>
										</table>
								</td>
								
							
								<tr>
								<td style="padding: 0px 10px 10px 5px;cellpadding:0;cellspacing:0;" >
								<?	
							
								 if($_GET['ti']==''){		
								$_GET['ti']=$tipo1; //si es la 1º vez que entra y no hay nada seleccionado es el tipo 1 	
								$_GET['id_tip']=$id_tip1;?>
								<?}
								$sql3="select  * from  componente comp inner  join `centro_comp` cen where  cen.Id_Centro ='". $_GET['id'] ."' and comp.nombre='".$_GET['ti']."' and comp.Id_Componente='".$_GET['id_tip']."' and cen.Id_Componente='".$_GET['id_tip']."';";
								$result3=mysql_query($sql3);
								$fila3=mysql_fetch_array($result3);
				
				//Tabla con los componentes y sus operaciones?>
				
			
				 <? if($c>8 ){
					 $c=950;
				 }
				 else if($c==8 ){
					 $c=900;
				 }
					else if($c>=6 ){
					 $c=780;
				 }else{
					 $c=500;
				 }

				  
					
				$numero_sonda=$numero_sonda+1;
					//  Tabla con los datos del componente seleccionado del centro
					?>

					 <TABLE  style='border:1;-moz-border-radius:15px;border-left:solid ;border-right:solid ;border-bottom:solid  ;border-top:solid ;width:<?echo $c?>;padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;' >				
					<form name='datos_componente' action="index.php?pag=instalaciones.php&ti=<?echo $fila2['Nombre']?>&num=<?echo $fila['Id_Centro'];?>&modifi='1';" method='post'>
				<?	if(mysql_num_rows($result3)<=0){?>
					<tr height='10'style='border-top:none;border-bottom:none;border-left:none;border-rigth:none;'><td  style='border-top:none;border-bottom:none;border-left:none;border-rigth:none;'rowspan='2' align='center'>
						<B><?echo "Este centro no tiene ningún componente";?></B></td>
					</tr>
					<tr height='30'>
					<td></td>
					</tr>
					</table>

				<?}else{?>						
					

					<tr>
					<td style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;"><input type='hidden' name='componente' id='componente' value='<?echo $fila3['Id_Componente'];?>'></td><td><input type='hidden' name='centro'  value='<?echo $fila['Id_Centro'];?>'> </td><td></td>
					</tr>

					<tr height='30'> 
					<td >&nbsp&nbsp&nbsp;</td>
					<td cellspacing='0' cellpadding='0' align='left'>Fecha </td><td align='left' title='<?echo $fila3['Fecha_Instalacion'];?>'><input type='text' name='fecha' id='fecha' style='background-color:white;border-right:none 0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:#003366;' disabled value='<?echo $fila3['Fecha_Instalacion'];?>'></td>					 
					<td align='left'>Marca</td>
					<td align='left' title='<?echo $fila3['Marca'];?>'>	<input type='text'  id='marca' name='marca' style='background-color:white;border-right:none 0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:#003366;' disabled value='<?echo $fila3['Marca'];?>'></td>
					<td align='left'>Modelo</td>
					<td align='left' title='<?echo $fila3['Modelo'];?>'><input type='text'  id='modelo' name='modelo' style='background-color:white;border-right:none 0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:#003366;' disabled value='<?echo $fila3['Modelo'];?>'></td>			
					</td>	</tr>

					 <?if ($_GET['ti']==''){//Si es la primera vez el tipo es el primero que se cargo
						 $_GET['ti']=$tipo1;
					 }?>
					<tr height='30'>
					<td >&nbsp&nbsp&nbsp; </td>						
					<td align='left' >Proveedor</td><td align='left' title='<?echo $fila3['Proveedor'];?>'><input type='text'  id='proveedor' name='proveedor' style='background-color:white;border-right:none 0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:#003366;' disabled value='<?echo $fila3['Proveedor'];?>'></td>				
					<td  align='left' >Contacto</td><td align='left' title='<?echo $fila3['Contacto'];?>'><input type='text'  id='contacto' disabled name='contacto' style='background-color:white;border-right:none 0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:#003366;'value='<?echo $fila3['Contacto'];?>'></td>
					<td  align='left'>Cantidad</td><td align='left' cellspacing='10' title='<?echo $fila3['Cantidad'];?>'><input type='text' name='cantidad' id='cantidad' style='background-color:white;border-right:none 0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:#003366;align:center;'disabled value='<?echo $fila3['Cantidad'];?>'>	 
					<?$comp=$fila2['Id_Componente'];?>	
					<td  align='left'>Tfno</td><td align='left' title='<?echo $fila3['Telefono'];?>'><input type='text' id='telefono'name='telefono'style='background-color:white;border-right:none 0px;border-bottom: none 0px;border-top: none 0px;border-left:none 0px;color:#003366;' disabled value='<?echo $fila3['Telefono'];?>'></td>
			
				

					
								
					</form>
					</tr>		 
			  
			  <!--DATOS DE LOS SUBCOMPONENTES-->
			  <tr ><td width='10'>&nbsp;&nbsp;</td><td  valign='top' colspan='6'>
				
			  <? 
				  $sql_subcomp="SELECT Id_Componente FROM componente where Nombre='".$_GET['ti']."' ";				  
				  $result_subcomp=mysql_query($sql_subcomp);
				  $fila_subcomp=mysql_fetch_array($result_subcomp);
				  $sql2="select sub.Id_Subcomponente, sub.Nombre,comp.Cantidad from subcomponente sub inner join comp_subcomp comp where sub.Id_Subcomponente = comp.Id_Subcomponente and comp.Id_Componente ='".$fila_subcomp['Id_Componente']."' order by sub.Nombre;";
				
					$result_sub=mysql_query($sql2); ?>
					<?$cont_sub=mysql_num_rows($result_sub);?>
					  <table align='center'><!--Esta tabla es el contenedor del div de mantenimiento y de los subcomponentes-->						 <tr>
						  <td align='center'>
					<?if ($cont_sub>0){?>
								<!-- Tabla de los subcomponentes de los componentes de la instalacion-->
								<TABLE class='listados' style='width:550;' cellspacing='0' cellpadding='0' valign='top' align='center'>
								<thead >
									
									<tr>
									<th class="titulo" id="borde_idb" width='420' ><div class="borde" style='height:20px;'></div><div class="centro">
										<a class="nombre" href="#">	<div>Subcomponente</div></a></div></th>	
									<th class="titulo" id="borde_idb" ><div class="borde" style='height:20px;'></div><div class="centro">
										<a class="nombre" href="#" ><div>Unidades</div></a></div></th>								
									</tr>
								</thead>
								
								</table>
							
							
							<div align='center' class="tableContainer"  style="background-color:#e6e3111;   border: 0px solid #000000;width:450; height: 100px;overflow-y:auto;">						
							<?if($cont_sub>=4){?>
								<table class='listado_inst' align='left' style='padding:0px 0px 0px 0px;width:532;' cellspacing='0'  border='1'>

							<?}else{?>	
								<table class='listado_inst' align='left' style='padding:0px 0px 0px 0px;width:550;' cellspacing='0'  border='1'>
							<?}?>
							<tbody>
								<?$sub=0;
								for($c1=0;$c1<mysql_num_rows($result_sub);$c1++){
							    $fila2=mysql_fetch_array($result_sub);
								$sql_sub1="select * from  subcomponente where Id_Subcomponente ='".$fila2['Id_Subcomponente']."';";
									$result_sub1=mysql_query($sql_sub1);
									$fila_sub1=mysql_fetch_array($result_sub1);?>
									<?$sql_op="select * from operaciones where Id_Subcomponente ='".$fila2['Id_Subcomponente']."';";
								$result_op=mysql_query($sql_op);
								$cont_op=mysql_num_rows($result_op);?>
									
								 <a href="#">								   
									<tr oncontextmenu="mostrarMenu(<?echo $fila2['Id_Subcomponente']?>,<?echo $cont_op?>);">
										<td title='<?echo $fila_sub1['Nombre'];?>' valign='middle' align='left' width='410'><?echo $fila_sub1['Nombre'];?></td>
										<td  valign='middle' align='center' title='<?echo $fila2['Cantidad'];?>' ><?echo $fila2['Cantidad'];?></td>			
										<?$subcomponentes[$sub]= $fila_sub1['Id_Subcomponente'];
										$sub++;?>
									</tr> </a>
								<?}?>


					<!-- ESTE CODIGO JAVASCRIPT es para mostrar y controlar el menu de las filas de las tabla de subcomponenetes -->	
					<script>
						var Subcomp;// variable para guardar el id del subcomponente
						var Total;//variable con el total de registros de operaciones
						var Operacion;//Para indicar si es correctivo o preventivo

						//Función utilizada para mostrar la posicion y el menu contextual
						function mostrarMenu(sub,tot) {
							Subcomp=sub;
							Total=tot;
							
							var rightedge = document.body.clientWidth - event.clientX;
							var bottomedge = document.body.clientHeight - event.clientY;
							
							if (rightedge < menu.offsetWidth){
								document.getElementById('menu').style.left = document.body.scrollLeft + event.clientX - menu.offsetWidth;
								
							}
							else{
								
								document.getElementById('menu').style.left = document.body.scrollLeft + event.clientX;
							}
								
							if (bottomedge < menu.offsetHeight){
								document.getElementById('menu').style.top = document.body.scrollTop + event.clientY - menu.offsetHeight;
							}
							else{
								document.getElementById('menu').style.top = document.body.scrollTop + event.clientY;
								
								
							}
								document.body.oncontextmenu = mostrarMenuNormal;
								document.getElementById('menu').style.visibility = "visible";
						}

						//Funcion que es utilizada atraves del menu contextual, muestro si existe operaciones de mantenimiento para ese subcomponente
						function enviarAccion() {
						 document.body.onclick = ocultarMenu;
						 if(Total<=0){
								alert('Este subcomponente no tiene operaciones de mantenimiento');
						   }else{	
							   
									window.open("./paginas/operaciones_sub.php?num="+Subcomp,"ventana2","width=700,height=150, scrollbars=yes, menubar=no, location=no, resizable=no, top=300");
							  
							}
						}

						//Función para no mostrar o mostrar el menu
						function mostrarMenuNormal() {
							if (document.getElementById('menu').style.visibility == "visible") {
								return false;
							}
							else {
								return true;
							}
						}

						function ocultarMenu() {
							document.getElementById('menu').style.visibility = "hidden";
							
						}
						document.body.oncontextmenu = mostrarMenuNormal;
						document.body.onclick = ocultarMenu;
					
						</script>
					<!-- FIN CODIGO DE MENU-->
					</tbody>
						
					</table><!--Fin tabla subcomponentes--></div>
					<!--Esto es para el menu contextual-->
					<div id="menu" style="position:absolute;visibility:hidden;padding:0 0 0 0;margin:0px;">
					<div class="item_esquina"></div>
					<table cellpadding=0 cellspacing=0>			
						<tr><td style="padding: 0 0 0 0;" title='Ver operaciones de mantenimiento'>
							<div class="item_izq"></div>
							<div class="item" align="center" style="width:100px;" onclick="enviarAccion();" ><?PHP echo $conf['menu_contextual'][0]['texto']; ?></div>
							<div class="item_der"></div>
						</td></tr>
					</table>
</div>
				<?}//Si el centro tiene componentes?>
					
			 
			
			
			</td>
			
		</table><!--Table contenedor de los subcomponentes-->
		</td>
	</table></div>
   </TD> 
   
   </TR></table><!--Fin tabla de componentes--> </td></tr>
	
		<?$num_reg=$i;
					
        }//cierro el for de centros
		
		
		if(isset($_SESSION['var_imagenes'])){//Es para sacar el nº para nombrar el xml y que no quede cargado en la cache
			$_SESSION['var_imagenes']++;
		}
		else{
			$_SESSION['var_imagenes']=1;
		}
	
		$direcc="imagenes/swf/";
		$dir = opendir($direcc);//abrimos el directorio
		while($elemento =readdir($dir)){//recogemos cada uno de las imagenes que hay en esa carpeta	
				$resto=substr($elemento,-4);		
					if($resto==".xml"){
						unlink("imagenes/swf/".$elemento);
					}
				}
			
			if(($DescriptorFichero = fopen("imagenes\swf\imagenes".$_SESSION['var_imagenes'].".xml","w"))){
				$d=$_GET['id'];	
				$direcc="imagenes/fotos/Centro".$d."/";
				$frase="<imagenes>";
				$frase=fputs($DescriptorFichero,$frase);
				
				$dir = opendir($direcc);//abrimos el directorio
				$directorios = array();
				$z=0;
				while($elemento =readdir($dir)){//recogemos cada uno de las imagenes que hay en esa carpeta	
					$directorios[]=$elemento;
					$z=$z+1;
				
				}
				$cont=0;
				//Con esto escribo en el fichero imagenes.xml la ruta de las imagenes que existe en la carpeta de ese centro. EL xml se guarda en la carpeta de swf
				for ($b=0;$b<$z;$b++) {
				$str ="imagenes/fotos/Centro".$d."/".$directorios[$b];
				$resto=substr($str,-4);				
					if ($resto==".jpg" || $resto==".JPG" ){
							$frase= "<imagen>".$str."</imagen>";
							fputs($DescriptorFichero,$frase);
							$cont++;
					}
					if ($resto==".gif" || $resto==".GIF"){
							$frase= "<imagen>".$str."</imagen>";
							fputs($DescriptorFichero,$frase);
							$cont++;
							
					}
					if ($resto==".png" || $resto==".PNG"){
							$frase= "<imagen>".$str."</imagen>";
							fputs($DescriptorFichero,$frase);
							$cont++;
					}
					
					
					fputs($DescriptorFichero,"\r\n");
				}
					closedir($dir); //Cerramos el directorio
					$frase="</imagenes>";
					fputs($DescriptorFichero,$frase );
					fclose($DescriptorFichero);	
					
				$imagenes=$cont;

			}
				
			
			?>		
				
			
			
	    </TR>
		<tr cellspacing='0' cellpadding='0' style='padding: 0px 0px 0px 0px;'>
		<td colspan='10' cellspacing='0' cellpadding='0' style='padding: 0px 0px 0px 0px;'>
		<TABLE   style="padding: 0px 0px 0px 0px;" cellspacing='0' cellpadding='0' bordercolor='#ACA867' border='1' > 
			<thead>						
				<tr>
					<th class="titulo2"    style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:2;border-left:none;border-right:none;border-bottom:none;border-top:none;" >Galería Imágenes</th>
					<th class="titulo2"    style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:2border-left:none;border-right:none;border-bottom:none;border-top:none;" >&nbsp;&nbsp;</th>	
					<th class="titulo2"    style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0border-left:none;border-right:none;border-bottom:none;border-top:none;" >Opciones</th> 
					
				</tr>
			</thead>
			<tr cellspacing='0' cellpadding='0'  style='padding: 0px 0px 0px 0px;'>
				
				 <? // Si hay imagenes muestro el flash si no hay imagenes no lo muestro
					 if ($imagenes>0){?>
				 <td cellpadding=0 cellspacing=0   style='padding: 0px 0px 0px 0px;border-left:none;border-right:none;border-bottom:none;border-top:none;' >
					<div align='center'>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="530" height="150">
					  <param name="movie" value="./imagenes/swf/Imagenes.swf?<?echo rand(0,1000);?>"/>
					  <param name="quality" value="high">
					  <param name="wmode" value="transparent">
					   <PARAM NAME="FlashVars" VALUE="variables=<?=$_SESSION['var_imagenes']?>&nom_serv=<?=$_SERVER['SERVER_NAME']?>&id_centro=<?=$_GET['id']?>" />
					  <embed src="./imagenes/swf/Imagenes.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="530" height="150"></embed></object></div>
				</td>
				<? if($_SESSION['ult_centro']!=$d){?>
				<script> retornar('<? echo $d?>');
				 function retornar(centro){	
						window.location.href='?pag=instalaciones.php&id='+centro;
						
				}
				 </script>
					<?}
					$_SESSION['ult_centro']=$d; ?>
				
				
				<?}else{?>
					<td cellpadding=0 cellspacing=0  style='padding: 0px 0px 0px 0px;cellspacing:0;cellpadding:0;border-left:none;border-right:none;border-bottom:none;border-top:none;' width="530" height="150" >&nbsp;</TD>
				<?}?>
				
				<td cellpadding=0 cellspacing=0   style='padding:0px 0px 0px 0px;border-left:none;border-right:solid;border-bottom:none;border-top:none;'>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				 <td  cellpadding=0 cellspacing=0  style='padding:0px 0px 0px 0px;border:0;width:1000;background-color: #e6e3111;'>
				 <div style="background-color: #e6e3b3;	font-size:14px;font-family: Georgia;letter-spacing: 2px;text-align: left;" ><B>Mantenimiento</B></div>
					<?$sql_hoj="select * from hoja_correctiva where Id_Centro='".$_GET['id']."';";	
						$result_hoj=mysql_query($sql_hoj);
						$cont_hoj=mysql_num_rows($result_hoj);
						$a_actu=date('Y');
						$m_actu=date('m');?>
						<table bordercolor='#ACA867' border='1' cellpadding=0 cellspacing=0 
						style='padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;border-left:none;border-right:none;border-bottom:none;border-top:none;width:100%;'>
						<div height='40'>&nbsp;</div>
						
												
						<tr>
							<form id='ope'>	
							
								<td  valign='top' style='border-left:none;border-right:none;border-bottom:none;border-top:none;'>
									<input type="radio" id="ope1" name="ope1" value="1"  checked onclick='javascript:cambiar(<?echo 1?>);'>Correctivo&nbsp;&nbsp;</td>
								<td valign='top' style='border-left:none;border-right:none;border-bottom:none;border-top:none'> <input type="radio" id="ope2" name="ope2" value="2" onclick='javascript:cambiar(<?echo 2?>);'>Preventivo&nbsp;&nbsp;</td>
								<td valign='top' style='border-left:none;border-right:none;border-bottom:none;border-top:none'><input TYPE="button" NAME="button" VALUE="MOSTRAR" align='center' valign='bottom' class="boton_big" onClick="javascript:IrOperaciones(<?echo $_GET['id']?>,<?echo $m_actu?>,<?echo $a_actu?>);"></td>
							
						</tr>
						<tr><td><div width='100%' height='100%'></div></td></tr>
						<tr>
							<? //Ahora selecciono las fechas en las que el centro esta operativo
							$sql_fecha="SELECT Fecha_Inicio, Fecha_Fin FROM Centro where Id_Centro='".$_GET['id']."' ";		  
							$result_fecha=mysql_query($sql_fecha);
							$fila_fecha=mysql_fetch_array($result_fecha);
							$mes_a=date('m');//mes actual
							$dia_a=date('d');
							$fech_ini=$fila_fecha['Fecha_Inicio'];
							$fech_fin=$fila_fecha['Fecha_Fin'];										
							list($y_i,$m_i,$d_i) = split( '-',$fech_ini);
							list($y_f,$m_f,$d_f) = split( '-',$fech_fin);	
							
							
							if(($m_i<$mes_a && $m_f>$mes_a)||($m_f==$mes_a && $d_f>=$dia_a )||($m_i==$mes_a && $d_i<=$dia_a )){
							
							  $m1=0;
							  $m2=0;
							  $d1=0;
							  $d2=0;
								
																		
							 }else{	
							  $m1=$m_i;											
							  $m2=$m_f;											   
							  $d1=$d_i;
							  $d2=$d_f;								
												  
							}?>
							<?$sql_hay="SELECT * FROM sonda WHERE Id_Centro='".$_GET['id']."';";
							 $result_hay=mysql_query($sql_hay);
							  
							 if(mysql_num_rows($result_hay)>0){?>						
								<td colspan='3' cellpadding=0 cellspacing=0 style='cellpadding:0;cellspaccing:0;padding: 0px 0px 0px 0px;border-left:none;border-right:none;border-bottom:none;border-top:solid;'>
									<div  align='left' class='titulo3' style="padding: 0px 0px 0px 0px;cellpadding:0;cellspacing:0;border-left:none;border-right:none;border-bottom:none;border-top:none;" ><div align='left'><B>Comunicación</B></div></div>
									<div style='background-color:#e6e3111;border: none;font: 10px Georgia;float:left;text-align: center;align:center;	width:100%;	height:50px;padding: 0 0 0px 0px;' align='center'>
										
											<table>
												<thead>	
													<tr style='background-color:#e6e3111;'>											
													<th align='center'>
														<INPUT TYPE="button" NAME="GSM" VALUE="GSM" align='center' class="boton_big" onClick="javascript:abrirDatos(<?echo $m1?>,<?echo $m2?>,<?echo $d1?>,<?echo $d2?>,<?echo $_GET['id']?>);">
														</th>
													</tr>
												</thead>
											</table>
									</div>
								</td>
							<?}else{?>
								<tr><td colspan='3' cellpadding=0 cellspacing=0 style='cellpadding:0;cellspaccing:0;padding: 0px 0px 0px 0px;border-left:none;border-right:none;border-bottom:none;border-top:none;'><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div></td></tr>

							<?}?></tr>
							</form>
							</table>
							
				
				</td>
			
			</tr>
		</table>
	</td></tr>
  
		
	 </table>
	<? if(isset($_GET['indice'])){?>	
	<script>  
		
		 abrirGaleria("<?echo $_GET['indice']?>");
		
	 function abrirGaleria(g){		 
		window.open("/scies/paginas/galeria.php?indice="+g);
	}
	 </script>
		 
<?}?>


<?}else{?>
	<table   class='contenedor' align='center' valign='middle'>
		 <tr height='100'>
		 <td valign='middle' align='middle'></td>
		 </tr>
		 <tr>
		 <td valign='middle' background="../imagenes/fondos/fondo_paginas.jpg" align='middle'>NO EXISTEN DATOS DE NINGUNA INSTALACIÓN</td>
		 </tr>
		</TABLE>
	 <?}
		
?>

 </div>
 </div>
