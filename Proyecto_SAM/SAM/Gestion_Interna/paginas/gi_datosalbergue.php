<script>
function cambiar_provincias(){
var indice_naci = document.datos.pais.selectedIndex;
var nacionalidad = document.datos.pais[indice_naci].value;
		if(nacionalidad!="ES"){
			document.datos.provincia.disabled=true;
			document.datos.selectedIndex="";
		}else
			document.datos.provincia.disabled=false;
	}
	
	
function valida_texto(){
   var cif = document.datos.cif.value;
   var numcuenta = document.datos.numcuenta.value;
   var telef1 = document.datos.telef1.value;
   var telef2 = document.datos.telef2.value;
	var CP = document.datos.CP.value;
   var fax = document.datos.fax.value;
   var email = document.datos.email.value;
   var fianza = document.datos.fianza.value;
   
   
   
			
						    
						    // El CIF tiene que ser una letra seguido de 8 números
							if ( !isNaN(cif.substring(0)) || cif.length != 9  || isNaN(cif.substring(1,9)))
							{
								alert("Debe rellenar el campo CIF correctamente. Una letra seguido de 8 números.");	
								document.datos.cif.focus();
								return false;
							}			
											 
					   
							// Los teléfonos  deben estar formados por 9 números
						 if (telef1.length != 9 || isNaN(telef1))
							{
						    
						 	
								alert("Debe rellenar correctamente el campo Teléfono");					
								document.datos.telef1.focus();
								return false;
							}
							
						if(telef2 != ""){	
							if (telef2.length != 9 || isNaN(telef2))
							{
						    
						 	
								alert("Debe rellenar correctamente el campo Teléfono");					
								document.datos.telef2.focus();
								return false;
							}}
							
							if (isNaN(numcuenta)|| numcuenta.length!=20)
							{
						    
						 	
								alert("Debe rellenar correctamente el campo  Número Cuenta");					
								document.datos.numcuenta.focus();
								return false;
							}
							
								if (isNaN(CP) || CP.length!=5)
							{
						    
						 	
								alert("Debe rellenar correctamente el campo  Código Postal");					
								document.datos.CP.focus();
								return false;
							}
							
							
							if(fax != ""){
							if (isNaN(fax)|| fax.length != 9)
							{
						    
						 	
								alert("Debe rellenar correctamente el campo  fax");					
								document.datos.fax.focus();
								return false;
							}}
							
							if (isNaN(fianza) || fianza.length==0)
							{
						    
						 	
								alert("Debe rellenar correctamente el campo  fianza");					
								document.datos.fianza.focus();
								return false;
							}
							
							if(email != ""){
							  
								if(email.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
								alert("Introduzca el E-mail con el el formato correcto ");
								document.datos.email.focus();
								return false;
									}
							}
						document.datos.submit();
  
				}
				function validar()
            	{
            	   var i
            	   var valor
            	  var anio = document.eliminar.anyo_factura_eliminar[document.eliminar.anyo_factura_eliminar.selectedIndex].value; 
            	  if(anio.length==0)
				  {
				    	alert("Debe seleccionar un año.");
						return false;
					}
            	   if(document.eliminar.seleccionar[1].checked == false && document.eliminar.seleccionar[0].checked ==false && document.eliminar.seleccionar[2].checked ==false){
						alert("Debe seleccionar una opción a eliminar.");
						return false;
					} else {
            	  
    			for (i=0;i<document.eliminar.seleccionar.length;i++){ 
       				if (document.eliminar.seleccionar[i].checked)
				    	{	valor=document.eliminar.seleccionar[i].value;
				    
						break; 
   				 }}
				if (valor =="f") 
   				 {
   				 	var entrar = confirm("¿Está seguro que desea eliminar las facturas del año "+anio+"?");
					if ( !entrar ) 
					{return false;}
					else {document.eliminar.submit();}

    				}

            		if (valor =="c") 
   				 {
   				 	var entrar = confirm("¿Está seguro que desea eliminar los componentes de grupo del año "+anio+"?");
					if ( !entrar ) 
					{return false;}
					else {document.eliminar.submit();}

   				}
					if (valor =="r") 
   				 {
   				 	var entrar = confirm("¿Está seguro que desea eliminar los carnets REAJ del año "+anio+"?");
					if ( !entrar ) 
					{return false;}
					else {document.eliminar.submit();}

    				}  
            	 
            	
				
				}
            	  
			}		
					

</script>


<?
if((isset($_SESSION['permisoOnLine']) && $_SESSION['permisoOnLine']==true) && (isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']==true) && (isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']==true) && (isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']==true) && (isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']==true) && (isset($_SESSION['permisoFacturacion']) && $_SESSION['permisoFacturacion']==true) && (isset($_SESSION['permisoIncidencias']) && $_SESSION['permisoIncidencias']==true) && (isset($_SESSION['permisoTarifas']) && $_SESSION['permisoTarifas']==true) && (isset($_SESSION['permisoEstadisticas']) && $_SESSION['permisoEstadisticas']==true) && (isset($_SESSION['permisoInternaREAJ']) && $_SESSION['permisoInternaREAJ']==true) && (isset($_SESSION['permisoInfPolicial']) && $_SESSION['permisoInfPolicial']==true) && (isset($_SESSION['permisoREAJ']) && $_SESSION['permisoREAJ']==true)  && (isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) && (isset($_SESSION['permisoTaquillas']) && $_SESSION['permisoTaquillas']==true) && (isset($_SESSION['permisoInternaTaquillas']) && $_SESSION['permisoInternaTaquillas']==true) && (isset($_SESSION['permisoHabitaciones']) && $_SESSION['permisoHabitaciones']==true) && (isset($_SESSION['permisoInternaHabitaciones']) && $_SESSION['permisoInternaHabitaciones']==true))//Compruebo que se tienen todos los permisos si es asi, muestro el boton de acceso a Gestion de Datos del Albergue, sino no lo muestro
						{








  @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }

     mysql_select_db($_SESSION['conexion']['db']);

 $sql="SELECT * FROM albergue ";

               $resul = mysql_query($sql);
               $fila = mysql_fetch_array($resul);
               $idpais=$fila['Id_Pais'];
               $idpro=$fila['Id_Pro'];
          

if (isset($_POST['cif'])){

       $sqlmod="UPDATE albergue SET CIF = '".$_POST['cif']. "',
            Nombre_Alb = '".$_POST['nombreal']. "',
            Num_Cuenta = '".$_POST['numcuenta']. "',
            Entidad = '".$_POST['entidad']. "',
            Win = '".$_POST['win']. "',
             Iban = '".$_POST['iban']. "',
            Direccion_Alb = '".$_POST['dire']. "',
            CP_Alb = '".$_POST['CP']. "',
       Localidad_Alb = '".$_POST['localidad']. "',
             Id_Pro = '".$_POST['provincia']. "',
            Id_Pais = '".$_POST['pais']. "',
            Tfno1_Alb = '".$_POST['telef1']. "',
            Tfno2_Alb = '".$_POST['telef2']. "',
            Fax_Alb = '".$_POST['fax']. "',
            Email_Alb = '".$_POST['email']. "',
             Fianza = '".$_POST['fianza']. "' WHERE `CIF` = '".$_POST['cifmod']. "'";
  
             $resulmod = mysql_query($sqlmod);
             $sql="SELECT * FROM albergue ";

               $resul = mysql_query($sql);
               $fila = mysql_fetch_array($resul);
               $idpais=$fila['Id_Pais'];
               $idpro=$fila['Id_Pro'];



}

if(isset($_POST['seleccionar']))
{
  
  	if($_POST['seleccionar']=="f")
  	{
	$suprimir="DELETE FROM factura WHERE SUBSTRING(Fecha_Factura,1,4)='".$_POST['anyo_factura_eliminar']."'";
		//echo $suprimir;
	mysql_query($suprimir);
	}
	


	
	if($_POST['seleccionar']=="c")
  	{
	$supcomponentes="DELETE FROM componentes_grupo WHERE SUBSTRING(Fecha_Llegada,1,4)='".$_POST['anyo_factura_eliminar']."'";
	mysql_query($supcomponentes);
	}
	
	if($_POST['seleccionar']=="r")
  	{
	$reaj="DELETE FROM solicitante WHERE SUBSTRING(Fecha,1,4)='".$_POST['anyo_factura_eliminar']."'";
	mysql_query($reaj);
	}


}

?>



 <div id='datos' style='margin-top:20px;margin-left:-300px;'>


            <table class="tabla_detalles" border=0 cellspacing="0" background-color:'#f4fcff'>
            <form action='#' method="POST" name="datos">
            <thead>

					<tr id="titulo_tablas">
						<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:690px;">Datos del Albergue</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>
			 </thead>



             <tr>
                 <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                    <table style="border: 1px solid #3F7BCC;" border="0" width="750" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="500" style='background-color:#f4fcff'>
                    <input type="hidden" value="<?print($fila['CIF']);?>" name="cifmod">
                    <tr height="45">
                        <td>&nbsp;</td>
                        <td align="left">
			            <br><label class="label_formulario">CIF:</label></td>
                        <td align="left"><br><input class="input_formulario" type="text"  name="cif" value="<?print($fila['CIF']);?>" size="30"></td>
                       
                        <td>&nbsp;</td>
	                    <td align="left"><br><label class="label_formulario">Nombre Albergue:</label></td>
                        <td align="left"><br><input class="input_formulario" type="text"  name="nombreal" value="<?print($fila['Nombre_Alb']);?>" size="30"></td>
                    </tr>
                  

                     <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Número Cuenta:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="numcuenta" value="<?print($fila['Num_Cuenta']);?>" size="30"></td>
                          <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Entidad:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="entidad" value="<?print($fila['Entidad']);?>" size="30"></td>
                     </tr>
                     
                     
                      <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">WIN:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="win" value="<?print($fila['Win']);?>" size="30"></td>
                          <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">IBAN:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="iban" value="<?print($fila['Iban']);?>" size="30"></td>
                     </tr>
                     
                     
                     
                     
                     
                     <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Dirección Albergue:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="dire" value="<?print($fila['Direccion_Alb']);?>" size="30"></td>
                          <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Código Postal:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="CP" value="<?print($fila['CP_Alb']);?>" size="30"></td>
                     </tr>

                     
                     
                     
                     <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Localidad:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="localidad" value="<?print($fila['Localidad_Alb']);?>" size="30"></td>
                         <td>&nbsp;</td>
                         <td  align="left">
                         <label class="label_formulario">País:</label>
                         </td>
                         <td align="left">
                            <SELECT class='select_formulario' name="pais" onChange="cambiar_provincias();">

                         <?
                            $sqlp="SELECT * FROM pais ORDER BY Nombre_Pais";
                            $resp=mysql_query($sqlp);
                                    if ($resp){$filasp=mysql_num_rows($resp);}
                                    for ($i=0;$i<$filasp;$i++){
	                                 $filap=mysql_fetch_array($resp);
                                	if (strlen($filap['Nombre_Pais'])>30){

                              ?>
                               <option value="<?print($filap['Id_Pais']);?>"<? if($idpais==$filap['Id_Pais']){print("selected");}?>><?print(substr($filap['Nombre_Pais'],0,22)."...");}else{?>
                               
                               <option value="<?print($filap['Id_Pais']);?>"<? if($idpais==$filap['Id_Pais']){print("selected");}?>><?print($filap['Nombre_Pais']);
                               }
                              }?>

                            </SELECT>



                         </td>
                       </tr>

                        <tr height="45">
                         <td>&nbsp;</td>
                         <td  align="left">
                         <label class="label_formulario">Provincia:</label>
                         </td>
                         <td align="left">
                            <SELECT class='select_formulario'name="provincia">

                         <?
                            $sqlpro="SELECT * FROM provincia ORDER BY Nombre_Pro";
                            $respro=mysql_query($sqlpro);
                                    if ($respro){$filaspro=mysql_num_rows($respro);}
                                    for ($i=0;$i<$filaspro;$i++){
	                                 $filapro=mysql_fetch_array($respro);


                              ?>
                               <option value="<?print($filapro['Id_Pro']);?>"<? if($idpro==$filapro['Id_Pro']){print("selected");}?>><?print($filapro['Nombre_Pro']);?>
                              <?}?>

                            </SELECT>



                         </td>
                      
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Teléfono1:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="telef1" value="<?print($fila['Tfno1_Alb']);?>" size="30"></td>
                     </tr>


                       <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Teléfono2:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="telef2" value="<?print($fila['Tfno2_Alb']);?>" size="30"></td>
                       
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Fax:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="fax" value="<?print($fila['Fax_Alb']);?>" size="30"></td>
                    
                         
                     </tr>

                       
                        <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Email:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="email" value="<?print($fila['Email_Alb']);?>" size="30"></td>
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Fianza:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="fianza" value="<?print($fila['Fianza']);?>" size="30"></td>
                     </tr>
                       
                       
                       
                       


                       
                    
                   
                     <tr>
                         <td colspan='6' align='center'>
                       
                           <br><br><a href="#" onClick='valida_texto();'> <img src='../imagenes/botones-texto/modificar.jpg' border="0"></a>
                          </td>


                      
                     </tr>

            </form>
			</table>

                 </td></tr></table>

             






       </div>
       <div id="eliminar" style="margin-top:-527px;margin-right:-800px;">
       
       		<table class="tabla_detalles" border=0 cellspacing="0" background-color:'#f4fcff'>
            <form action='#' method="POST" name="eliminar">
            <thead>

					<tr id="titulo_tablas">
						<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:180px;">Eliminar</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>
			 </thead>
			 <tr>
                 <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                    <table style="border: 1px solid #3F7BCC;" border="0" width="240px" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="200" style='background-color:#f4fcff'>
                    
                    <tr height="45">
                    	
                        <td colspan="3" align="center">
                        	<label class="label_formulario">Año:</label>
						
                        	<SELECT name='anyo_factura_eliminar' class='select_formulario')>
									<OPTION selected class='select_formulario'  value=''>&nbsp;&nbsp;</OPTION>
										<?php
											$sql_anio="SELECT DISTINCT (SUBSTRING( Fecha_Factura, 1, 4)) as anio from factura
														union SELECT DISTINCT (SUBSTRING( Fecha, 1, 4)) as anio FROM solicitante
														union SELECT DISTINCT (SUBSTRING( Fecha_Llegada, 1, 4)) as anio FROM componentes_grupo as anio";
											$result = mysql_query($sql_anio);
											
											for ($i=0;$i<mysql_num_rows($result);$i++){
												$fila_anio = mysql_fetch_array($result);
											
													echo "<option value='".$fila_anio['anio']."' class='select_formulario'>".$fila_anio['anio']."</option>";
												
												}
											
										?>
								</SELECT>
						</td>
                    </tr>
                    <tr>
                           <td>&nbsp;</td>
                           <td  align="right" width="65px">
			                 	<input type='radio' name='seleccionar' value="f">
							</td>
                                 
                           <td align="left">
                            	<label class="label_formulario">Facturas</label>
                               
                           </td>
                    </tr>
                    <tr>
                           <td>&nbsp;</td>
                           
                                 
                           <td align="right">
                            <input type='radio' name='seleccionar' value="c">
							</td>
							<td	align="left">
								<label class="label_formulario">Componentes</label>	
							</td>
                        </tr>
                        
                    <tr>
                           <td>&nbsp;</td>
                           
                                 
                           <td align="right">
                            <input type='radio' name='seleccionar' value="r">
							</td>
							<td	align="left">
								<label class="label_formulario">Carnet Reaj</label>	
							</td>
                        </tr>
                    <tr>
                    
                    	<td colspan="3" align="center"><a href="#" onclick="validar();"><img src='../imagenes/botones-texto/eliminar.jpg' border="0" alt="Eliminar"></a></td>
                    </tr>
                    </table>
                </td>
            </tr>
            </form>
            </table>
       
       		
       </div>
       
 <?MYSQL_CLOSE($db)?>      

<?}?>
