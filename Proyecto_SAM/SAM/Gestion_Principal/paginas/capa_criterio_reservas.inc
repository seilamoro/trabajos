<div id="reservas" style="position:absolute; top:0px; left:0px; visibility:hidden; "> 
<?php
	if(isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']=true) //Comprobando que se tiene permiso para realizar busquedas de Reservas
		{
?>
          <form action=".?pag=listado_criterio_reservas.php&mi_formulario=true" method="post" name="formreservas">
           <table border='0' cellpadding="0px" cellspacing="0px">
              <tr>
              <td align='center' colspan="3" style="padding:0px 0px 0px 0px;"> 
	      <div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:335px;'>
						Criterios Reservas
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
                <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
                  <div class="titulo" style="text-align:center;width:335px;">Criterios 
                   Reservas</div>
                </div>
                <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
				</tr>
				<tr>
              <td align="left" style="padding:0px 0px 0px 0px;"  class='tabla_detalles'> <table border="0" width="100%" style="border: 1px solid #3F7BCC;">
                  <tr> 
                    <td> <table width="100%" border="0">
                        <tr align="left"> 
                          <td width="10" rowspan="8">&nbsp;</td>
                          <td > <label class="label_formulario">D.N.I.:</label> 
                          </td>
                          <td > <input type="text" name="dni_reservas" class="input_formulario" value=<?php echo $_SESSION['bus']['dni_reservas']; ?>> 
                          </td>
                        </tr>
                        <tr align="left"> 
                          <td > <label class="label_formulario">Nombre Cliente:</label> 
                          </td>
                          <td > <input type="text" name="nombre_reservas" class="input_formulario" value=<?php echo $_SESSION['bus']['nombre_reservas']; ?>> 
                          </td>
                        </tr>
                        <tr align="left"> 
                          <td > <label class="label_formulario">Primer Apellido:</label> 
                          </td>
                          <td > <input type="text" name="apellido1_reservas" class="input_formulario" value=<?php echo $_SESSION['bus']['apellido1_reservas']; ?>> 
                          </td>
                        </tr>
                        <tr align="left"> 
                          <td > <label class="label_formulario">Segundo Apellido:</label> 
                          </td>
                          <td > <input type="text" name="apellido2_reservas" class="input_formulario" value=<?php echo $_SESSION['bus']['apellido2_reservas']; ?>> 
                          </td>
                        </tr>
                        <tr align="left"> 
                          <td ><label class="label_formulario">Llegada:</label> 
                          </td>
                          <td > <SELECT name="dia_reservas" id="dia_reservas" class="select_formulario">
                              <OPTION selected class='select_formulario'  value="">&nbsp;&nbsp;</OPTION>
                              <?php
                              
                              
                        for($i=1;$i<=31;$i++){
							if($i<10){
								$i1="0".$i;
							}else{
								$i1=$i;
							}
                          if($_SESSION['bus']['dia_reservas']==$i){
								echo "<option value='".$i1."' class='select_formulario' selected>".$i1."</option>";
							}else{
								echo "<option value='".$i1."' class='select_formulario'>".$i1."</option>";
							}
                          }
                    ?>
                            </SELECT> <SELECT name="mes_reservas" id="mes_reservas" class="select_formulario" onchange="asignaDias('dia_reservas','mes_reservas','anyo_reservas')">
                              <OPTION selected class='select_formulario'  value="">&nbsp;&nbsp;</OPTION>
                              <?php 
							  if($_SESSION['bus']['mes_reservas']==1){
								  echo "<option value='01' class='select_formulario' selected>Enero</option>";
							  }else{
								  echo "<option value='01' class='select_formulario'>Enero</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==2){
								  echo "<option value='02' class='select_formulario' selected>Febrero</option>";
							  }else{
								  echo "<option value='02' class='select_formulario'>Febrero</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==3){
								  echo "<option value='03' class='select_formulario' selected>Marzo</option>";
							  }else{
								  echo "<option value='03' class='select_formulario'>Marzo</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==4){
								  echo "<option value='04' class='select_formulario' selected>Abril</option>";
							  }else{
								  echo "<option value='04' class='select_formulario'>Abril</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==5){
								  echo "<option value='05' class='select_formulario' selected>Mayo</option>";
							  }else{
								  echo "<option value='05' class='select_formulario'>Mayo</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==6){
								  echo "<option value='06' class='select_formulario' selected>Junio</option>";
							  }else{
								  echo "<option value='06' class='select_formulario'>Junio</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==7){
								  echo "<option value='07' class='select_formulario' selected>Julio</option>";
							  }else{
								  echo "<option value='07' class='select_formulario'>Julio</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==8){
								  echo "<option value='08' class='select_formulario' selected>Agosto</option>";
							  }else{
								  echo "<option value='08' class='select_formulario'>Agosto</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==9){
								  echo "<option value='09' class='select_formulario' selected>Septiembre</option>";
							  }else{
								  echo "<option value='09' class='select_formulario'>Septiembre</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==10){
								  echo "<option value='10' class='select_formulario' selected>Octubre</option>";
							  }else{
								  echo "<option value='10' class='select_formulario'>Octubre</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==11){
								  echo "<option value='11' class='select_formulario' selected>Noviembre</option>";
							  }else{
								  echo "<option value='11' class='select_formulario'>Noviembre</option>";
							  }
							  if($_SESSION['bus']['mes_reservas']==12){
								  echo "<option value='12' class='select_formulario' selected>Diciembre</option>";
							  }else{
								  echo "<option value='12' class='select_formulario'>Diciembre</option>";
							  }
                              ?>
                            </SELECT> <SELECT name="anyo_reservas" id="anyo_reservas" class="select_formulario" onchange="asignaDias('dia_reservas','mes_reservas','anyo_reservas')">
                              <OPTION selected class='select_formulario'  value="">&nbsp;&nbsp;</OPTION>
                              <?php
								$sql_anio="SELECT DISTINCT ( SUBSTRING( Fecha_Llegada, 1, 4)) as anio
											FROM reserva";
								$result = mysql_query($sql_anio);
								for ($i=0;$i<mysql_num_rows($result);$i++){
									$fila_anio = mysql_fetch_array($result);
									if($_SESSION['bus']['anyo_reservas']==$fila_anio['anio']){
										echo "<option value='".$fila_anio['anio']."' class='select_formulario' selected>".$fila_anio['anio']."</option>";
									}else{
										echo "<option value='".$fila_anio['anio']."' class='select_formulario'>".$fila_anio['anio']."</option>";
									}
								}
                          
							  ?>
                            </SELECT> </td>
                        </tr>
                        <tr align="left"> 
                          <td > <label class="label_formulario">Nombre Empleado:</label> 
                          </td>
                          <td > <input type="text" name="nombre_empleado_reservas" class="input_formulario" value=<?php echo $_SESSION['bus']['nombre_empleado_reservas']; ?>> 
                          </td>
                        </tr>
                        <tr > 
                          <td  colspan="3" height="35px"> </td>
                        </tr>
                        <tr > 
                          <td align="center" colspan="2" ><a onClick="javascript:document.formreservas.submit()"><img src="../imagenes/botones-texto/aceptar.jpg" border="0"></a> 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a  onclick="location.href='?pag=busq.php&rese=true&limpiar=7'"><img src="../imagenes/botones-texto/limpiar.jpg" border="0"></a> 
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