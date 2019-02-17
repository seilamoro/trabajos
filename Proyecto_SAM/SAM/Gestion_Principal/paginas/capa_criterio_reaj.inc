<div id="reaj" style="position:absolute; top:0px; left:0px; visibility:hidden; "> 
<?php
	if(isset($_SESSION['permisoREAJ']) && $_SESSION['permisoREAJ']=true) //Comprobando que se tiene permiso para realizar busquedas de REAJ
		{
?>
          <form action=".?pag=listado_criterio_reaj.php&mi_formulario=true"  method="post" name="formreaj">
             <table border='0' cellpadding="0px" cellspacing="0px">
              <tr>
              <td align='center' colspan="3" style="padding:0px 0px 0px 0px;"> 
	      <div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:335px;'>
						Criterios Reaj
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
                <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
                  <div class="titulo" style="text-align:center;width:335px;">Criterios 
                   Reaj</div>
                </div>
                <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
				</tr>
				<tr>
              <td align="left" style="padding:0px 0px 0px 0px;" class='tabla_detalles'> <table border="0" width="100%" style="border: 1px solid #3F7BCC;">
                  <tr> 
                    <td> <table width="100%" border="0">
                          <tr align="left"> 
                            <td width="10" rowspan="8">&nbsp;</td>
                            <td> <label class="label_formulario">Tipo:</label> 
                            </td>
                            <td> <select class="select_formulario" name="tipo_reaj">
                                <OPTION selected class='select_formulario' value="">&nbsp;&nbsp;</OPTION>
                                <?PHP
					$reaj=mysql_query("select * from reaj order by Nombre_Carnet");
														if(mysql_num_rows($reaj)>0)
    													{
		    											
    													for($i=0;$i<mysql_num_rows($reaj);$i++)
        													{
        													$fila=mysql_fetch_array($reaj);
        													if($_SESSION['bus']['tipo_reaj']==$fila['Id_Carnet']){
        														echo "<OPTION value='".$fila['Id_Carnet']."' class='select_formulario' selected>".$fila['Nombre_Carnet']."</OPTION>";
        													}else{
																echo "<OPTION value='".$fila['Id_Carnet']."' class='select_formulario'>".$fila['Nombre_Carnet']."</OPTION>";
															}
    													}
														}
														?>
                              </select> </td>
                          </tr>
                          <tr align="left"> 
                            <td width="84"> <label class="label_formulario">D.N.I.:</label> 
                            </td>
                            <td width="159"><input type="text" name="dni_reaj" class="input_formulario" value=<?php echo $_SESSION['bus']['dni_reaj']; ?>> 
                            </td>
                          </tr>
                          <tr align="left"> 
                            <td><label class="label_formulario">Nombre:</label> 
                            </td>
                            <td><input type="text" name="nombre_reaj" class="input_formulario" value=<?php echo $_SESSION['bus']['nombre_reaj']; ?>> 
                            </td>
                          </tr>
                          <tr align="left"> 
                            <td><label class="label_formulario">Primer Apellido:</label> 
                            </td>
                            <td><input type="text" name="apellido1_reaj" class="input_formulario" value=<?php echo $_SESSION['bus']['apellido1_reaj']; ?>> 
                            </td>
                          </tr>
                          <tr align="left"> 
                            <td><label class="label_formulario">Segundo Apellido:</label> 
                            </td>
                            <td><input type="text" name="apellido2_reaj" class="input_formulario" value=<?php echo $_SESSION['bus']['apellido2_reaj']; ?>> 
                            </td>
                          </tr>
                          <tr align="left"> 
                            <td><label class="label_formulario">Fecha:</label> 
                            </td>
                            <td> <SELECT name="dia_reaj" id="dia_reaj" class="select_formulario">
                                <OPTION selected class='select_formulario'  value="">&nbsp;&nbsp;</OPTION>
                                <?php
                        for($i=1;$i<=31;$i++){
							if($i<10){
								$i1="0".$i;
							}else{
								$i1=$i;
							}
                           if($_SESSION['bus']['dia_reaj']==$i){
								echo "<option value='".$i1."' class='select_formulario' selected>".$i1."</option>";
							}else{
								echo "<option value='".$i1."' class='select_formulario'>".$i1."</option>";
							}
                          }
                    ?>
                              </SELECT> <SELECT name="mes_reaj" id="mes_reaj" class="select_formulario" onchange="asignaDias('dia_reaj','mes_reaj','anyo_reaj')">
                                <OPTION selected class='select_formulario'  value="">&nbsp;&nbsp;</OPTION>
                                <?php 
							  if($_SESSION['bus']['mes_reaj']==1){
								  echo "<option value='01' class='select_formulario' selected>Enero</option>";
							  }else{
								  echo "<option value='01' class='select_formulario'>Enero</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==2){
								  echo "<option value='02' class='select_formulario' selected>Febrero</option>";
							  }else{
								  echo "<option value='02' class='select_formulario'>Febrero</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==3){
								  echo "<option value='03' class='select_formulario' selected>Marzo</option>";
							  }else{
								  echo "<option value='03' class='select_formulario'>Marzo</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==4){
								  echo "<option value='04' class='select_formulario' selected>Abril</option>";
							  }else{
								  echo "<option value='04' class='select_formulario'>Abril</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==5){
								  echo "<option value='05' class='select_formulario' selected>Mayo</option>";
							  }else{
								  echo "<option value='05' class='select_formulario'>Mayo</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==6){
								  echo "<option value='06' class='select_formulario' selected>Junio</option>";
							  }else{
								  echo "<option value='06' class='select_formulario'>Junio</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==7){
								  echo "<option value='07' class='select_formulario' selected>Julio</option>";
							  }else{
								  echo "<option value='07' class='select_formulario'>Julio</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==8){
								  echo "<option value='08' class='select_formulario' selected>Agosto</option>";
							  }else{
								  echo "<option value='08' class='select_formulario'>Agosto</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==9){
								  echo "<option value='09' class='select_formulario' selected>Septiembre</option>";
							  }else{
								  echo "<option value='09' class='select_formulario'>Septiembre</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==10){
								  echo "<option value='10' class='select_formulario' selected>Octubre</option>";
							  }else{
								  echo "<option value='10' class='select_formulario'>Octubre</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==11){
								  echo "<option value='11' class='select_formulario' selected>Noviembre</option>";
							  }else{
								  echo "<option value='11' class='select_formulario'>Noviembre</option>";
							  }
							  if($_SESSION['bus']['mes_reaj']==12){
								  echo "<option value='12' class='select_formulario' selected>Diciembre</option>";
							  }else{
								  echo "<option value='12' class='select_formulario'>Diciembre</option>";
							  }
                              ?>
                              </SELECT> <SELECT name="anyo_reaj" id="anyo_reaj" class="select_formulario" onchange="asignaDias('dia_reaj','mes_reaj','anyo_reaj')">
                                <OPTION selected class='select_formulario'  value="">&nbsp;&nbsp;</OPTION>
                                <?php
								$sql_anio="SELECT DISTINCT ( SUBSTRING( Fecha, 1, 4)) as anio
											FROM solicitante";
								$result = mysql_query($sql_anio);
								for ($i=0;$i<mysql_num_rows($result);$i++){
									$fila_anio = mysql_fetch_array($result);
									if($_SESSION['bus']['anyo_reaj']==$fila_anio['anio']){
										echo "<option value='".$fila_anio['anio']."' class='select_formulario' selected>".$fila_anio['anio']."</option>";
									}else{
										echo "<option value='".$fila_anio['anio']."' class='select_formulario'>".$fila_anio['anio']."</option>";
									}
								}
                          
							  ?>
                              </SELECT> </td>
                          </tr>
                          <tr > 
                            <td  colspan="2" height="35px"> </td>
                          </tr>
                          <tr > 
                            <td align="center" colspan="2" ><a onClick="javascript:document.formreaj.submit()"><img src="../imagenes/botones-texto/aceptar.jpg" border="0"></a> 
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a  onclick="location.href='?pag=busq.php&rese=true&limpiar=8'"><img src="../imagenes/botones-texto/limpiar.jpg" border="0"></a> 
                          </tr>
                       </table></td>
                  </tr>
                </table>
				</td>
				</tr>
            </table>
          </form>
<?php
		}
	
?>          
        </div>