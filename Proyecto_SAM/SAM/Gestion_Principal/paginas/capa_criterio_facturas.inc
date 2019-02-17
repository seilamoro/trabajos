<div id="facturas" style="position:absolute; top:0px; left:0px; visibility:hidden; "> 
<?php
	if(isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion']=true) //Comprobando que se tiene permiso para realizar busquedas de Facturas
		{
?>
          <form name="formfacturas" action=".?pag=listado_criterio_facturas.php&mi_formulario=true" method="post">
             <table border='0' cellpadding="0px" cellspacing="0px">
              <tr>
              <td align='center' colspan="3" style="padding:0px 0px 0px 0px;"> 
	      <div class='champi_izquierda'>&nbsp;</div>
			  <div class='champi_centro'  style='width:335px;'>
						Criterios Facturas
						</div>
						<div class='champi_derecha'>&nbsp;</div>
						<!--<div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
                <div style="height:25px;text-align:center;background-image:url('../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;"> 
                  <div class="titulo" style="text-align:center;width:335px;">Criterios 
                   Facturas</div>
                </div>
                <div style="height:25px;width:30px;background-image:url('../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>--></td>
				</tr>
				<tr>
              <td align="left" style="padding:0px 0px 0px 0px;"  class='tabla_detalles'> <table border="0" width="100%" style="border: 1px solid #3F7BCC;">
                  <tr> 
                    <td> <table width="100%" border="0">
                        <tr> 
                          <td width="10" rowspan="2">&nbsp;</td>
                          <td align="left" class='label_formulario'>Tipo Cliente:</td>
                          <td align="left"> <select name="opc2" onChange="cambiar_tipo_facturas(formfacturas.opc2.value);" class="select_formulario">
                              <OPTION value='1' selected class="select_formulario"> 
                              </OPTION>
                              <?php 
							  if($_SESSION['bus']['opc2']=='3'){
								  echo "<option value='3' class='select_formulario' selected>Peregrino</option>";
							  }else{
								  echo "<option value='3' class='select_formulario'>Peregrino</option>";
							  }
							   if($_SESSION['bus']['opc2']=='2'){
								  echo "<option value='2' class='select_formulario' selected>Alberguista</option>";
							  }else{
								  echo "<option value='2' class='select_formulario'>Alberguista</option>";
							  }
							  if($_SESSION['bus']['opc2']=='4'){
								  echo "<option value='4' class='select_formulario' selected>Grupo</option>";
							  }else{
								  echo "<option value='4' class='select_formulario'>Grupo</option>";
							  }
							  if($_SESSION['bus']['opc2']=='5'){
								  echo "<option value='5' class='select_formulario' selected>Reserva</option>";
							  }else{
								  echo "<option value='5' class='select_formulario'>Reserva</option>";
							  }
                              
							  ?>
                            </select> </td>
                        </tr>
                        <tr> 
                          <td colspan="2" valign="top"> <div id="tipo_factura"> 
                              <?php
                              //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
							  if(!$_SESSION['bus']['opc2']){
								  $_SESSION['bus']['opc2']=1;
							  }
							  echo "<table width='100%' height='180px' border='0'>";
			echo "<tr align='left'>";
			echo "<td width='50%' colspan='2'> <label class='label_formulario'>Nº Factura:</label></td>";
			echo "<td  width='50%'><input type='text' name='factura' class='input_formulario' value=".$_SESSION['bus']['factura']."></td>";
			echo " </tr>";
			if($_SESSION['bus']['opc2']=="4"){
			
				echo "<tr align='left'>";                    
		        echo "<td colspan='2'> <label class='label_formulario'>Nombre Grupo:</label> </td>";
				echo "<td ><input type='text' name='nom_grup_factura' class='input_formulario' value=".$_SESSION['bus']['nom_grup_factura']."></td> ";
				echo "</tr> ";
			}
         
			echo "<tr align='left'>";                    
            echo "<td colspan='2'> <label class='label_formulario'>";
			if($_SESSION['bus']['opc2']!="4"){
					echo "D.N.I.:";
				}else{
					echo "D.N.I. Representante:";
				}
				echo "</label> </td>";
			echo "<td ><input type='text' name='dni_factura' class='input_formulario' value=".$_SESSION['bus']['dni_factura']."></td> ";
			echo "</tr> "; 
			if($_SESSION['bus']['opc2']!="4"){
			echo " <tr >"; 
			echo "<td colspan='2'><label class='label_formulario'>";
				if($_SESSION['bus']['opc2']=="1"){
					echo "Nombre:";
				}else{
					echo "Nombre:";
				}
			
			
			echo "</label> </td>";
			echo "<td><input type='text' name='nombre_factura' class='input_formulario' value=".$_SESSION['bus']['nombre_factura']."></td>";
			echo "</tr><tr>";
			echo "<td colspan='2'><label class='label_formulario'>Primer Apellido:</label></td>";
			echo "<td><input type='text' name='apellido1_factura' class='input_formulario' value=".$_SESSION['bus']['apellido1_factura']."></td>";
			echo " </tr><tr> ";
			echo "<td colspan='2'><label class='label_formulario'>Segundo Apellido:</label></td>";
			echo "<td><input type='text' name='apellido2_factura' class='input_formulario' value=".$_SESSION['bus']['apellido2_factura']."></td>";
			echo "</tr>";
		 }
			echo "<tr>";
			echo "<td width='35%'><label class='label_formulario'>Fecha Factura:</label> </td>";
			echo "<td colspan='2' width='70%'> <SELECT name='dia_factura'  id='dia_factura' class='select_formulario'>";
			echo "<OPTION selected class='select_formulario' value=''>&nbsp;&nbsp;</OPTION>";
                        for($i=1;$i<=31;$i++){
							if($i<10){
								$i1="0".$i;
							}else{
								$i1=$i;
							}
                          if($_SESSION['bus']['dia_factura']==$i){
								echo "<option value='".$i1."' class='select_formulario' selected>".$i1."</option>";
							}else{
								echo "<option value='".$i1."' class='select_formulario'>".$i1."</option>";
							}
                          }
                    
                      echo "</SELECT> <SELECT name='mes_factura' id='mes_factura' class='select_formulario' onchange=asignaDias('dia_factura','mes_factura','anyo_factura')>";
                            echo "<OPTION selected class='select_formulario'  value=''>&nbsp;&nbsp;</OPTION>";
                            if($_SESSION['bus']['mes_factura']==1){
								  echo "<option value='01' class='select_formulario' selected>Enero</option>";
							  }else{
								  echo "<option value='01' class='select_formulario'>Enero</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==2){
								  echo "<option value='02' class='select_formulario' selected>Febrero</option>";
							  }else{
								  echo "<option value='02' class='select_formulario'>Febrero</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==3){
								  echo "<option value='03' class='select_formulario' selected>Marzo</option>";
							  }else{
								  echo "<option value='03' class='select_formulario'>Marzo</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==4){
								  echo "<option value='04' class='select_formulario' selected>Abril</option>";
							  }else{
								  echo "<option value='04' class='select_formulario'>Abril</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==5){
								  echo "<option value='05' class='select_formulario' selected>Mayo</option>";
							  }else{
								  echo "<option value='05' class='select_formulario'>Mayo</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==6){
								  echo "<option value='06' class='select_formulario' selected>Junio</option>";
							  }else{
								  echo "<option value='06' class='select_formulario'>Junio</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==7){
								  echo "<option value='07' class='select_formulario' selected>Julio</option>";
							  }else{
								  echo "<option value='07' class='select_formulario'>Julio</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==8){
								  echo "<option value='08' class='select_formulario' selected>Agosto</option>";
							  }else{
								  echo "<option value='08' class='select_formulario'>Agosto</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==9){
								  echo "<option value='09' class='select_formulario' selected>Septiembre</option>";
							  }else{
								  echo "<option value='09' class='select_formulario'>Septiembre</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==10){
								  echo "<option value='10' class='select_formulario' selected>Octubre</option>";
							  }else{
								  echo "<option value='10' class='select_formulario'>Octubre</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==11){
								  echo "<option value='11' class='select_formulario' selected>Noviembre</option>";
							  }else{
								  echo "<option value='11' class='select_formulario'>Noviembre</option>";
							  }
							  if($_SESSION['bus']['mes_factura']==12){
								  echo "<option value='12' class='select_formulario' selected>Diciembre</option>";
							  }else{
								  echo "<option value='12' class='select_formulario'>Diciembre</option>";
							  }
                          echo "</SELECT> <SELECT name='anyo_factura' id='anyo_factura' class='select_formulario' onchange=asignaDias('dia_factura','mes_factura','anyo_factura')>";
                            echo "<OPTION selected class='select_formulario'  value=''>&nbsp;&nbsp;</OPTION>";
                       
								$sql_anio="SELECT DISTINCT ( SUBSTRING( Fecha_Factura, 1, 4)) as anio
											FROM factura";
								$result = mysql_query($sql_anio);
								for ($i=0;$i<mysql_num_rows($result);$i++){
									$fila_anio = mysql_fetch_array($result);
									if($_SESSION['bus']['anyo_factura']==$fila_anio['anio']){
										echo "<option value='".$fila_anio['anio']."' class='select_formulario' selected>".$fila_anio['anio']."</option>";
									}else{
										echo "<option value='".$fila_anio['anio']."' class='select_formulario'>".$fila_anio['anio']."</option>";
									}
								}
                          
							  
                          echo "</SELECT> </td>";
			echo "</tr>";
			echo "</table>";
							  //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
							  ?>
                            </div></td>
                        </tr>
                        <tr > 
                          <td  colspan="3" height="35px"> </td>
                        </tr>
                        <tr > 
                          <td align="center" colspan="3" ><a onClick="javascript:document.formfacturas.submit()"><img src="../imagenes/botones-texto/aceptar.jpg" border="0"></a> 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a  onclick="location.href='?pag=busq.php&rese=true&limpiar=2'"><img src="../imagenes/botones-texto/limpiar.jpg" border="0"></a> 
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