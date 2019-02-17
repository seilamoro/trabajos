<?php
	if(isset($_SESSION['permisoInternaTaquillas']) && $_SESSION['permisoInternaTaquillas']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
?>
<body>
	
	<head>
		
		
		<?php
			$db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
			if (!$db){
				echo ("Error al acceder a la base de datos, intentelo mas tarde");
				exit();
			}
			mysql_select_db($_SESSION['conexion']['db']);

			$listado_taq=$_GET['listado_taq'];
			$listado_hab=$_GET['listado_hab'];
			$eliminar=$_GET['eliminar'];
			$deshabilitar=$_GET['deshabilitar'];

			$restaurar_taq=$_GET['restaurar_taq'];
			$restaurar_hab=$_GET['restaurar_hab'];

			$restaurar=$_GET['restaurar'];


			/*************************-- SENTENCIA PARA HABILITAR TAQUILLAS --***************************/

			$restaur="UPDATE taquilla SET Estado_Taq='H' WHERE Id_taquilla=".$restaurar_taq." and Id_Hab='".$restaurar_hab."'";

			/*************************-- FIN SENTENCIA PARA HABILITAR TAQUILLAS --***************************/


			/*************************-- SENTENCIA PARA RECOJER LAS HABITACIONES EXISTENTES --***************************/

			$sql="Select Id_Hab
				  from habitacion;";

			/*************************-- FIN SENTENCIA PARA RECOJER LAS HABITACIONES EXISTENTES --***************************/

			$resultado=mysql_query($sql);
			$nume=mysql_num_rows($resultado);



      /*************************-- ORDENA LA LISTA DE LAS HABITACIONES --***************************/
	  /**-- SE UTILIZA LA SENTENCIA ANTERIOR PARA RECOJER LAS HABITACIONES EXISTENTES --**/

			for ($i = 0; $i < MYSQL_NUM_ROWS($resultado); $i++) {
			  	$fila = MYSQL_FETCH_ARRAY($resultado);
				if($fila['Id_Hab']!=PRA){
					$hab_taquillas[$i]['Orden'] = INTVAL($fila['Id_Hab']);
					$hab_taquillas[$i]['Id_Hab'] = $fila['Id_Hab'];
					
				}
			}

			if($nume > 0){
				foreach ($hab_taquillas as $llave => $fila) {
					$Orden[$llave]  = $fila['Orden'];
				}

				array_multisort($Orden, SORT_ASC, $hab_taquillas);
			}

	/*************************-- FIN ORDENA LA LISTA DE LAS HABITACIONES --***************************/




			/***********-- INSERTAR TAQUILLA PASILLO --**********/

			if($situacion==pasillo){
				$ta="select Id_Taquilla
					 from taquilla
					 where Id_Hab='PAS';";
				$re=mysql_query($ta);
				$num=mysql_num_rows($re);
				
				for ($fi=0;$fi<$num;$fi++) {
					
					$fil = MYSQL_FETCH_ARRAY($re);
					if($fil['Id_Taquilla'] == $num){
							
								$taquilla=$num+1;
							
						}
					if(($fi+1) != $fil['Id_Taquilla']){
							
								$taquilla=$fi+1;
								$num=-1;
							
						}
					
					
				}
				if($num==0){
					$taquilla=1;
				}
				
				$q="INSERT INTO taquilla( `Id_Taquilla` , `Id_Hab` , `DNI_Cl` , `Estado_Taq` , `Nombre_Gr` ) VALUES (".$taquilla.", 'PAS', NULL, 'H', NULL);";
				mysql_query($q);
				
			}
			/***********-- FIN INSERTAR TAQUILLA PASILLO --**********/


			/***********-- INSERTAR TAQUILLA HABITACION --**********/
			if($situacion==habitacion && $numero_hab!='null'){
				$ta="select Id_Taquilla
					 from taquilla
					 where Id_Hab='".$numero_hab."'";
				$re=mysql_query($ta);
				$num=mysql_num_rows($re);

				for ($fi=0;$fi<$num;$fi++) {
					
					$fil = MYSQL_FETCH_ARRAY($re);
					if($fil['Id_Taquilla'] == $num){
							
								$taquilla=$num+1;
							
						}
					if(($fi+1) != $fil['Id_Taquilla']){
							
								$taquilla=$fi+1;
								$num=-1;
							
						}
					
					
				}
				if($num==0){
					$taquilla=1;
				}

				$q="INSERT INTO taquilla( `Id_Taquilla` , `Id_Hab` , `DNI_Cl` , `Estado_Taq` , `Nombre_Gr` ) VALUES (".$taquilla.", '".$numero_hab."', NULL,'H', NULL);";
				mysql_query($q);
			}
			/***********-- FIN INSERTAR TAQUILLA HABITACION --**********/


			/***********-- RESTAURAR TAQUILLA HABITACION --**********/
			
			?>

			<SCRIPT LANGUAGE='JavaScript'>
				function Confirmrestaurar(habitacion, taquilla) {
					var response = window.confirm('¿Desea restaurar la taquilla?');
					if (response) {	
						window.location='principal_gi.php?pag=gi_taquillas.php&restaurar=true&restaurar_hab='+habitacion+'&restaurar_taq='+taquilla+'';
					}else{
						window.alert('Ha cancelado la operación');
					}
				}

			</SCRIPT>
		<?php
			if($restaurar==true){
				mysql_query($restaur);
			}				

			/***********-- FIN RESTAURAR TAQUILLA HABITACION --**********/
			

			/***********-- ELIMINAR TAQUILLA --**********/
			if(isset($eliminar)){
				$comp="Select * from taquilla where Id_taquilla=".$eliminar;
				$comp_taq=mysql_query($comp);
				$f = MYSQL_FETCH_ARRAY($comp_taq);
				if($f['DNI_Cl']!=null || $f['Nombre_Gr']!=null){?>
					<script>
						window.alert('OCUPADA: No se puede eliminar');
					</script>
				<?
				}else{
					$suprimir="DELETE FROM taquilla WHERE Id_taquilla=".$eliminar." and Id_Hab='".$listado_hab."';";
					mysql_query($suprimir);
				}
			}
			/***********-- FIN ELIMINAR TAQUILLA --**********/

			/***********-- DESHABILITAR TAQUILLA --**********/
			if(isset($deshabilitar)){
				$comp="Select * from taquilla where Id_taquilla=".$deshabilitar;
				$comp_taq=mysql_query($comp);
				$f = MYSQL_FETCH_ARRAY($comp_taq);
				if($f['DNI_Cl']!=null || $f['Nombre_Gr']!=null){?>
					<script>
						window.alert('OCUPADA: No se puede deshabilitar');
					</script>
				<?
				}else{
					$deshabi="UPDATE taquilla SET Estado_Taq='D' WHERE Id_taquilla=".$deshabilitar." and Id_Hab='".$listado_hab."'";
					mysql_query($deshabi);
				}
			}
			/***********-- FIN DESHABILITAR TAQUILLA --**********/


			/***********-- TABLA(ARRAY) DE CONTROL DE TAQUILLAS --**********/

			$pos=array();	// -- ARRAY CON LAS HABITACIONES Y SUS RESPECTIVAS TAQUILLAS
			
			$sss=0;			// -- NUMERO MAXIMO DE TAQUILAS EN UNA HABITACION

			for ($fi=0;$fi<count($hab_taquillas);$fi++) {
				$sql_taquillas="Select * 
							    from taquilla
							    where Id_Hab='".$hab_taquillas[$fi]['Id_Hab']."';";
				
				$resultado_taquillas=mysql_query($sql_taquillas);
				
				if( MYSQL_NUM_ROWS($resultado_taquillas)!=0){
					
					$pos[$fi][0]=$hab_taquillas[$fi]['Id_Hab'];
						
					for ($fr=0;$fr<MYSQL_NUM_ROWS($resultado_taquillas);$fr++) {

						$fila2 = MYSQL_FETCH_ARRAY($resultado_taquillas);
						

						$pasito=$fr;
						if($fr+1 == $fila2['Id_Taquilla']){
							if($fila2['Estado_Taq']=='D'){
								$pos[$fi][$fr+1]='X';
							}else{
								$pos[$fi][$fr+1]=$fila2['Id_Taquilla'];
							}
							
						}else{
							for($fl=$fr;$fl<$fila2['Id_Taquilla'];$fl++){

								if(($fl+1)<$fila2['Id_Taquilla']){
	
									if($pos[$fi][$fl+1]==null){
										$pos[$fi][$fl+1]=null;	
									}

								}else{
									if($fila2['Estado_Taq']=='D'){
											$pos[$fi][$fl+1]='X';
									}else{
										$pos[$fi][$fl+1]=$fila2['Id_Taquilla'];
									}
								}
							}
						}
					}
				}
			}
			$sql_taq="Select max(Id_Taquilla) as p
							    from taquilla";
				
			$resultado_taq=mysql_query($sql_taq);
			$fill= MYSQL_FETCH_ARRAY($resultado_taq);
			$sss = $fill['p'];

			

			/***********-- FIN DE TABLA(ARRAY) DE CONTROL DE TAQUILLAS --**********/

			


		?>
	</head>


<table cellpadding="10">
	<tr>
		<td valign=top>
			<table id="tabla_detalles" cellpadding="0px" cellspacing="0px" >
				<tr>
					<td colspan="2" align="center" style="padding:0px 0px 0px 0px;">
					<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:188px;'>
						Nueva Taquilla
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
						<div style="height:25px;width;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;">
						<div class="titulo" id="titulo_lis_taq" style="width:188px;min-width:185px;text-align:center;">Nueva Taquilla</div>
						</div>
						<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>-->
					</td>
				</tr>
				<tr>
					<td style="padding:0px 0px 0px 0px;">
						<!--*******************-- NUEVA TAQUILLA --********************-->
						<table style='border:solid 1px;border-color:#3F7BCC;'><tr><td>
							
							<div align="center">
								<form method="POST" action="principal_gi.php?pag=gi_taquillas.php">
									<table border="0" cellpadding=8px width=240 >				
										
										<?php

										/***********-- COMPRUEBA EN LA BASE DE DATOS SI EXISTE PAS --**********/

												$sql_pasillo="Select Id_Hab 
															    from habitacion
															    where Id_Hab='PAS';";
												$resultado_pasillo=mysql_query($sql_pasillo);
												$fill= MYSQL_FETCH_ARRAY($resultado_pasillo);

										/***********-- FIN COMPRUEBA EN LA BASE DE DATOS SI EXISTE PAS --**********/


												if($fill){
										?>
										<tr>
											<td colspan=2>
											
												<input type="radio" name=situacion value=pasillo onclick=document.getElementById("n_habitacion").style.visibility="hidden";>
											
												<label class="label_formulario">Pasillo</label>
											</td>
										</tr>
										<?php
												}

												if(!$fill){
											?>
										<tr>
											<td>				
												<input type="radio" name=situacion value=habitacion checked >
											
												<label class="label_formulario">Habitación</label>
											</td>
										
											
											<td>
												<select class="select_formulario" name=numero_hab>
													<option class=select_formulario value="">
													</option>
													<?php
														
														for ($fi=0;$fi<count($hab_taquillas);$fi++) {
															if($hab_taquillas[$fi]['Id_Hab']!='PAS'){
																echo "<option class=select_formulario value=".$hab_taquillas[$fi]['Id_Hab'].">".$hab_taquillas[$fi]['Id_Hab']."</option>";
															}
														}
													?>
												</select>
											</td>
										</tr>



										<?php
												}else{
											?>
										<tr>
											<td colspan=2>				
												<input type="radio" name=situacion value=habitacion onclick=document.getElementById("n_habitacion").style.visibility="visible";>
											
												<label class="label_formulario">Habitación</label>
											</td>
										</tr>
										<tr id="n_habitacion" style="visibility:hidden;">
											<td width=120>
											
												<label class="label_formulario">Nº de Habitación: </label>
											</td>
											<td>
												<select class="select_formulario" name=numero_hab>
													<option class=select_formulario value="">
													</option>
													<?php
														
														for ($fi=0;$fi<count($hab_taquillas);$fi++) {
															if($hab_taquillas[$fi]['Id_Hab']!='PAS'){
																echo "<option class=select_formulario value=".$hab_taquillas[$fi]['Id_Hab'].">".$hab_taquillas[$fi]['Id_Hab']."</option>";
															}
														}
													?>
												</select>
											</td>
										</tr>
										<?php 
											}
										?>
										<tr>
											<td colspan="2" align="center">
												<input type="image" src="../imagenes/botones-texto/aceptar.jpg">
											</td>
										</tr>
									</table>
								</form>
							</div>
						</td></tr></table>
						<!--*******************-- FIN NUEVA TAQUILLA --********************-->
					</td>
				</tr>
			</table>
		</td>
		<td>
				
			<table border="0" cellpadding="0" cellspacing="0"  id="tabla_detalles">
				<tr>
					<td colspan="2" align="center" style="padding:0px 0px 0px 0px;">
					<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:650px;'>
						Listado de Taquillas
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
						<div style="height:25px;width:650px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;">
						<div class="titulo" id="titulo_lis_taq" style="width:440px;text-align:center;">Listado de Taquillas</div>
						</div>
						<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>-->
					</td>
				</tr>
				<tr>
					<td align="center" style="padding:0px 0px 0px 0px;border:solid 1px;border-color:#3F7BCC;">
						<!--*******************-- LISTADO DE TAQUILLAS --********************-->
							<table>
								<tr>
									<td align="center">
									<div style='height:450px;border:none;overflow:auto;width:700px;' align=center>
										<table id=tabla_habitaciones >
											<tr>
											<? if($sss>0){ ?>
 												<td align=center id=nom_taq_list valign=middle>Nº</td>
												<td id=nom_taquilla valign=middle>&nbsp;</td>
												<?php }
													for ($fi=0;$fi<$sss;$fi++) {
														echo "<td id='nom_taq_list' valign=middle>".($fi+1)."</td>";
													}
												?>
											</tr>
											
											<?php 
												for ($fi=0;$fi<$nume;$fi++) {
														
													if ($pos[$fi][0]!=null){
														echo "<tr align=center>";
														
															echo "<td valign=middle><div id='nom_taq_list'>".$pos[$fi][0]."</div></td>";
														
														echo "<td valign=middle><div id='no_taquilla'>&nbsp;</div></td>";
										
														for ($fr=0;$fr<$sss;$fr++) {
															if($pos[$fi][$fr+1]!=null){
																if($pos[$fi][$fr+1]==X){
																	echo "<td valign=middle><div id='nom_taq_list'><a id='nom_taq_list' style='text-decoration:none;' href='#' onClick='Confirmrestaurar(\"".$pos[$fi][0]."\",".($fr+1).")';>".$pos[$fi][$fr+1]."</a></div></td>";
																}else{
																	if($pos[$fi][0]=='PAS'){
																		echo "<td valign=middle onclick=location.href='principal_gi.php?pag=gi_taquillas.php&listado_hab=".$pos[$fi][0]."&listado_taq=".$pos[$fi][$fr+1]."'><div id='taquilla_libre_list'>".$pos[$fi][$fr+1]."</div></td>";
																	}else{
																		echo "<td valign=middle onclick=location.href='principal_gi.php?pag=gi_taquillas.php&listado_hab=".$pos[$fi][0]."&listado_taq=".$pos[$fi][$fr+1]."'><div id='taquilla_libre_list'>".$pos[$fi][0]."-".$pos[$fi][$fr+1]."</div></td>";
																	}
																	
																}
															}else{
																echo "<td valign=middle><div id='nom_taq_list'>&nbsp;</div></td>";
															}
														}
														echo "</tr>";
													}
												}
											?>
											
										</table>
										
									</td>
									</div>
								</tr>
							</table>
							<table>
									<?php
										if($listado_taq!=null){
									?>
								
										<?php 
										if($listado_hab=='PAS'){?>
										<tr>
											<td align="center"> 
											<label style='font-family:Verdana;font-weight:bold;'><label style='color:#467DB5;'>Taquilla: </label><?php echo $listado_taq;?><label style='color:#467DB5;'> Pasillo</label></label>
											</td>
										</tr>
										<?php
										}else{?>
										<tr>
											<td align="center"> 
											<label style='font-family:Verdana;font-weight:bold;'><label style='color:#467DB5;'>Taquilla: </label><?php echo $listado_taq;?><label style='color:#467DB5;'> Habitación: </label><?php echo $listado_hab;?></label>
											</td>
										</tr>
											<?php
										}?>
									
									<?php
										}
									?>
									<?php
										if($listado_taq!=null){
									?>
								<tr>
									<td align="center">
										<input type=image src='../imagenes/botones-texto/eliminar.jpg' onclick=location.href='principal_gi.php?pag=gi_taquillas.php&eliminar=<? echo $listado_taq;?>&listado_hab=<?php echo $listado_hab;?>';>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type=image src='../imagenes/botones-texto/deshabilitar.jpg' onclick=location.href='principal_gi.php?pag=gi_taquillas.php&deshabilitar=<? echo $listado_taq;?>&listado_hab=<?php echo $listado_hab;?>';>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type=image src='../imagenes/botones-texto/cancelar.jpg' onclick=location.href='principal_gi.php?pag=gi_taquillas.php';>
									</td>
								</tr>
								<?php
										}
										mysql_close($db);
									?>
								
							</table>
							
						<!--*******************-- FIN LISTADO DE TAQUILLAS --********************-->
					</td>
				</tr>
		
			</table>

		</td>
	</tr>
		
</table>


</body>
<? }
else{	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA</div>";
}
?>
