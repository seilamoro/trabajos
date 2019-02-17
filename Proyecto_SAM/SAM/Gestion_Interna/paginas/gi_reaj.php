<?php

	if(isset($_SESSION['permisoInternaREAJ']) && $_SESSION['permisoInternaREAJ']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
?>



<body>
<STYLE type="text/css">
A:link {text-decoration:none;color:#F3F3F3;}
A:visited {text-decoration:none;color:#F3F3F3;}
A:active {text-decoration:none;color:#F3F3F3;}
A:hover {text-decoration:none;color:#F3F3F3;}
</STYLE>

<script language="javascript">
        //resalta la selección en el listado
		function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#569CD7';
	  	tr.style.color = '#F4FCFF';
	  	tr.style.cursor = 'pointer';
		}
        //Dejar de resaltar la selección en el listado
		function desresaltar_seleccion(tr) {
	  		tr.style.backgroundColor = '#F4FCFF';
	  		tr.style.color = '#3F7BCC';
		}
			function desresaltar_seleccionrojo(tr) {
	  		tr.style.backgroundColor = '#EEC1C1';
	  		tr.style.color = '#970000';
		}
       function resaltar_seleccionrojo(tr) {
	  	tr.style.backgroundColor = '#AE4F4F';
	  	tr.style.color = '#FFD1D1';
	  	tr.style.cursor = 'pointer';
		}

	    // Variación del STOCK al dar de alta un nuevo tipo reaj sumando o restando 1 según indique la variable cambio
        function cambio_stockv(cambio){	
            var res;
    		var operacion='res=parseInt(document.nuevo.stock.value)'+cambio;
    		eval (operacion);
            if (res>=0){document.nuevo.stock.value=res;}
        }
        
        
        // Variación del STOCK al modificar un  tipo reaj sumando o restando 1 según indique la variable cambio
        function cambio_stockm(cambio){	
            var res;
    		var operacion='res=parseInt(document.modificar.stock.value)'+cambio;
    		eval (operacion);
            if (res>=0){document.modificar.stock.value=res;}
        }
        
      
        // Valida el formulario para un nuevo tipo reaj
        function validatexto(){


            if (document.nuevo.id.value.length==0){
            alert("Escriba el tipo de carnet");
            document.nuevo.id.focus();
            }else{if(document.nuevo.nombre.value.length==0)
                {
                alert("Debe indtroducir un nombre");
                document.nuevo.nombre.focus();
                }
                  else{if((document.nuevo.precio.value.length==0)&& (isNaN(document.nuevo.precio.value))){
                     alert("Debe introducir el precio del carnet");
                     document.nuevo.precio.focus();}
                     else { document.nuevo.submit();}
                    }
                  }


           }





             

        //valida el formulario para modificar un tipo reaj

        function validatexto2(){


         if (document.modificar.tipom.value.length==0){
         alert("Escriba el tipo de carnet");
         document.modificar.tipom.focus();
         }else{if(document.modificar.nombrem.value.length==0)
                {
                 alert("Debe indtroducir un nombre");
                 document.modificar.nombrem.focus();
               }
               else{if(document.modificar.preciom.value.length==0){
                     alert("Debe introducir el precio del carnet");
                     document.modificar.preciom.focus();}
                     else { document.modificar.submit();}


                 }
             }


        }
        	


    
		
		
</script>







<?  @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }

     mysql_select_db($_SESSION['conexion']['db']);


  // Comprobación para poner el foco en el primer campo del formulario. Si no existe la variable cuad1, será cuando queramos dar de alta un nuevo carnet reaj y se pondrá el foco en id, si cuad1=2 estaremos en el caso de modificar un carnet, pondremos el foco en tipom.
  
       if(!$_GET['cuad1']){

        ?>
          <body onload='document.nuevo.id.focus();'>
        <? } else {if($_GET['cuad1']=="2"){
                ?>
                   <body onload='document.modificar.tipom.focus();'>
                <? }}

                 ?>

           

        <br><br><br>



<!---------------------------tabla general------------------------>


<table cellpadding="10" border=0>
	<tr>
		<td valign="top" >
	
		 <?

         //Si no existe cuad1 mostraremos la pantalla de nuevo tipo reaj
         
         if(!isset($_REQUEST['cuad1']))
							{
           
     

        
        
        



       //Tabla nuevo tipo Reaj
		 
         ?>
      <TABLE BORDER="0" align="center"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="260">
		<tr id="titulo_tablas">
			<td colspan="4" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
				<div class='champi_izquierda'>&nbsp;</div>
				
				<div class='champi_centro' style="width:360px;">Nuevo Tipo Reaj</div>
				</div>
				<div class='champi_derecha'>&nbsp;</div>
			</td>
		</tr >
		<tr><td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
		
		
	 <table border="0" id="tabla_detalles" style="border: 1px solid #3F7BCC;" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" width="420" height="290">
	
        <tr height="45">
        <td>&nbsp;&nbsp;</td>
            <Form name='nuevo' action='#' METHOD="POST">
			<td><label class="label_formulario">Tipo:</label></td>
			<td><input class="input_formulario" type="text" name="id" size="4" maxlength="3"></td>
		</tr>
		<tr height="45">
             <td></td>
			<td><label class="label_formulario">Nombre Carnet:</label></td>
			<td><input class="input_formulario" type="text" name="nombre" size="35" maxlength="20"></td>
		</tr>
		<tr height="45">
		  <td>&nbsp;</td>
			<td><label class="label_formulario">Precio Carnet:</label></td>
			<td ><input class="input_formulario" type="text" name="precio" size="6"><label class="label_formulario">&nbsp;&nbsp(en euros)</label></td>
		</tr>
		
		
	
		<tr height="45">
		  <td></td>
			<TD><label class="label_formulario">Stock:</label></TD>
			<TD>
				<TABLE cellspacing="0" cellpadding="0">
   					<TR>
    					<TD><input class="input_formulario" type="text" size="2" name="stock" value="0"></TD>
    					<TD><IMG src="../imagenes/botones/flecha.jpg" height="22" align="absmiddle" usemap="#cambio_stockv" border="0" name="img1"></TD>
    					<MAP name='cambio_stockv'>
	                    <AREA SHAPE='rect' coords='0,0,12,11' onclick="cambio_stockv('+1')" alt='Subir Stock'>
	                    <AREA SHAPE='rect' coords='0,12,12,22' onclick="cambio_stockv('-1')" alt='Bajar Stock'>
                         </MAP>

    					
    					
    					
					</tr>
				</table>
			</TD>
        </TR>
		<tr height="45">
            <td align="center" colspan=3><a href="#" onClick="validatexto();"> <img src="../imagenes/botones-texto/aceptar.jpg" alt="Nuevo Tipo Reaj"border=0></a></td>
            </form>
         </tr>
            <?    
                 //Si existe $_POST['nombre'] estaremos en el caso en el que queremos dar de alta un nuevo carnet, recogemos todas las variables y                                                                                                  realizamos un insert.
					if($_POST['nombre']){


                     $Nombre=$_POST['nombre'];
                     $Precio=$_POST['precio'];
                     $Id=$_POST['id'];
                     $Stock=$_POST['stock'];
                     $Descripcion=$_POST['descripcion'];
             
                     $sql2 = "insert into reaj (Id_Carnet,Nombre_Carnet,Precio_Carnet,Stock_Carnet) values ('".$Id. "','".$Nombre. "','". $Precio. "','". $Stock.   "')";
               
                     $resul2=mysql_query($sql2);
              
                     if($resul2 != 1){
                        echo "Error";

                        }}
             
            

                // Si existe $_GET['ide'] estaremos en el caso de eliminar el tipo carnet seleccionado por Id_Carnet
                     if($_GET['ide'])
                    {
                    $sql="DELETE FROM reaj where Id_Carnet = '".$_GET['ide']. "'";
                    $resul = mysql_query($sql);

                    if($resul != 1){
                    echo "Error";


                      }}


                // Si existe $_POST['idem'] estaremos en el caso de modificar carnet, Tipov sera el tipo de carnet antes de modificar y Tipom el modificado

                if($_POST['idem']){

                  $Nombre=$_POST['nombrem'];
                  $Precio=$_POST['preciom'];
                  $Tipov=$_POST['idem'];
                  $Tipom=$_POST['tipom'];
                  $Stock=$_POST['stock'];

                 $sql="UPDATE reaj SET Precio_Carnet = '".$Precio. "',Id_Carnet = '".$Tipom. "',Nombre_Carnet = '".$Nombre. "',
            `Stock_Carnet` = '".$Stock. "' WHERE `Id_Carnet` = '".$Tipov. "'";

                  $resul = mysql_query($sql);
                }



                    ?>
              
           </table>
    </td></tr></table>

   </TD>
		




		  
		  <?}else{
		switch($_REQUEST['cuad1'])
			{
				case 2:

               //Si cuad1=2 nos mostrará la pantalla de modificar tipo reaj, para ello haremos una select para mostrar los datos a modificar del carnet seleccionado.



                if($_GET['ide']){

               $sql="SELECT * FROM reaj Where Id_Carnet like '".$_GET['ide']. "'";
               

               $resul = mysql_query($sql);
               $fila = mysql_fetch_array($resul);
               $Tipom=$fila['Id_Carnet'];
               $Preciom=$fila['Precio_Carnet'];
               $Nombrem=$fila['Nombre_Carnet'];
               $Stock=$fila['Stock_Carnet'];
              
             
               $idem=$_GET['ide'];
              


                }



              //Tabla Modificar Tipo Reaj
                ?>

		<Form name='modificar' action='principal_gi.php?pag=gi_reaj.php' METHOD="POST">
				<TABLE BORDER="0" align="center"  height="290" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
					<tr id="titulo_tablas">
						<td colspan="4" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
							<div class='champi_izquierda'>&nbsp;</div>
						
							<div class='champi_centro' style="width:360px;">Modificar Tipo Reaj</div>
							</div>
							<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>
					<tr>
						<td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                          
                           
                             <input type="hidden" name="idem" value="<?print($idem);?>">
							<table BORDER="0" id="tabla_detalles" align="center"  cellspacing="3" cellpadding="3" style="border: 1px solid #3F7BCC;" cellspacing="0" width="420" height="290">
								<tr height="50">
								  <td>&nbsp;&nbsp;</td>
									<td><label class="label_formulario">Tipo:</label></td>
									<td ><input class="input_formulario" type="text" name="tipom" value="<?print($Tipom);?>" size="4"></td>
								</tr>
								  
									<tr height="50">
									  <td>&nbsp;&nbsp;</td>
									<td><label class="label_formulario">Nombre:</label></td>
									<td ><input class="input_formulario" size="35" maxlength="20" type="text" name="nombrem" value="<?print($Nombrem);?>" ></td>
								</tr>
							
							
								<tr height="50">
								  <td>&nbsp;&nbsp;</td>
									<td><label class="label_formulario">Precio Carnet:</label></td>
									<td ><input class="input_formulario" type="text" name="preciom" value="<?print($Preciom);?>" size="6"><label class="label_formulario">&nbsp;&nbsp(en euros)</label></td>
								</tr>
								<tr height="50">
								  <td>&nbsp;&nbsp;</td>
			                     <TD><label class="label_formulario">Stock:</label></TD>
			                      <TD>
				                   <TABLE cellspacing="0" cellpadding="0">
   					                 <TR>
    					             <TD><input class="input_formulario" type="text" size="2" name="stock" value="<?print($Stock);?>"></TD>
    					             <TD><IMG src="../imagenes/botones/flecha.jpg" height="22" align="absmiddle" usemap="#cambio_stockm" border="0" name="img1"></TD>
    					             <MAP name='cambio_stockm'>
	                                 <AREA SHAPE='rect' coords='0,0,12,11' onclick="cambio_stockm('+1')" alt='Subir Stock'>
	                                  <AREA SHAPE='rect' coords='0,12,12,22' onclick="cambio_stockm('-1')" alt='Bajar Stock'>
                                    </MAP>




					              </tr>
				                  </table>
			                   </TD>
                              </TR>
								
								
								
								
								<tr>
									<td align="center" colspan=3>
                                      
										<a href="#" onClick="validatexto2();"><img src="../imagenes/botones-texto/modificar.jpg" alt="Modificar Tipo Carnet" border=0></a>  &nbsp;&nbsp;
										
                                        
										<a href="principal_gi.php?pag=gi_reaj.php"><img src="../imagenes/botones-texto/cancelar.jpg" alt="Volver a Nuevo Tipo Reaj" border=0></a>
											</form>
									</td>
								</tr>
							</table>
						</td>
					</tr>






				</table>
			</td>
		




		 
		  <?break;
				case 3:?>
				<?
				
				 //Si cuad1=3 nos mostrará la pantalla de eliminar tipo reaj, para ello haremos una select para mostrar los datos a eliminar del carnet seleccionado.
				
				
				if($_GET['ide']){
				$sql="SELECT * FROM reaj Where Id_Carnet like '".$_GET['ide']. "'";

               $resul = mysql_query($sql);
               $fila = mysql_fetch_array($resul);
               $Nombree=$fila['Nombre_Carnet'];
               $Precioe=$fila['Precio_Carnet'];
               $Ide=$fila['Id_Carnet'];
               $Stocke=$fila['Stock_Carnet'];
              }



               //Tabla Eliminar Tipo Reaj
               ?>
			
				<TABLE BORDER="0" align="center"  height="250" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
					<tr id="titulo_tablas">
						<td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
							<div class='champi_izquierda'>&nbsp;</div>
							
							<div class='champi_centro' style="width:360px;">Eliminar Tipo Reaj</div>
							</div>
							<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>
					<tr>
					
						<td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
							<table BORDER="0" align="center" id="tabla_detalles"  style="border: 1px solid #3F7BCC;" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" width="420" height="290">
								<tr height="50">
                                    <td>&nbsp;&nbsp;</td>
									<td><label class="label_formulario">Nombre Carnet:</label></td>
									<td><input class="input_formulario" size="40" style="border:0px;" type="text" name="precio" value="<?print($Nombree);?>" contenteditable=false id="texto_detalles"></td>
								</tr>
								<tr height="50">
                                    <td>&nbsp;&nbsp;</td>
									<td><label class="label_formulario">Precio Carnet:</label></td>
									<td ><input class="input_formulario" style="border:0px;" type="text" name="precio" value="<?print($Precioe.' €');?>" contenteditable=false id="texto_detalles"></td>
								</tr>
								<tr height="50">
                                    <td>&nbsp;&nbsp;</td>
									<TD><label class="label_formulario">Stock:</label></TD>
									<TD><input class="input_formulario" style="border:0px;" type="text" name="precio" size=3 value="<?print($Stocke);?>" contenteditable=false id="texto_detalles"></TD>
								</TR>
								<tr>
									<td align="center"  colspan=3><label class="label_formulario">Se eliminarán  todos los carnets Reaj vendidos <br> de este tipo.<br>¿Está seguro de que desea eliminar este<br> Tipo de Carnet?</label></td>
								</tr>
								<tr>
									<td align="center" colspan=3>
                                        <?$hrefe="principal_gi.php?pag=gi_reaj.php&ide=".$Ide;?>
										<a href="<?print($hrefe)?>" ><img src="../imagenes/botones-texto/aceptar.jpg" alt="Eliminar Tipo Carnet" border=0></a>
										&nbsp;&nbsp;
										<input type="image" src="../imagenes/botones-texto/cancelar.jpg" alt="Volver a Nuevo Tipo Reaj" onclick="location.href='?pag=gi_reaj.php'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
	
		
		
		<?break;
			}
		}


              //Listado de Tipos Reaj

        ?>
	
	
	<td valign="top">
			
				
	<table border="0"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" >
		<tr id="titulo_tablas">
			<td colspan="2" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
				<div class='champi_izquierda'>&nbsp;</div>
				
				<div class='champi_centro' style="width:580px;">Listado de Tipos Reaj</div>
				</div>
				<div class='champi_derecha'>&nbsp;</div>
			</td>
		</tr>
		<tr>
		<td align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                   <div id="tableContainer" class="tableContainer" style='height=290px;background-color:#f4fcff;'>
                   <table border="0" id="tabla_detalles"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;background-color:#f4fcff;" width="100%" class="scrollTable" style='width:620px;' >
		
                   	<thead id="titulos" class="fixedHeader" style='position:relative;'>
                   	
                   	 <?

                           //Seleccionar parámetros para crear la query ordenando según los campos  del listado  ascendente o descendentemente



                  if ($_GET['ord']=='des'){
	                     $ordena=' DESC';
	                     $lista='&ord=asc';

                     }
                     else{
	                 $lista='&ord=des';
                   }




          $qry="SELECT * FROM reaj ";
          if($_GET['short']){
	         if($_GET['short']=='nombre'){
	         $qry=$qry." order by Nombre_Carnet".$ordena;
	         }
	         if($_GET['short']=='precio'){
             $qry=$qry." order by Precio_Carnet".$ordena;
	         }
	         if($_GET['short']=='stock'){
		     $qry=$qry." order by Stock_Carnet".$ordena;
	         }
	        
	         if($_GET['short']=='type'){
		     $qry=$qry." order by Id_Carnet".$ordena;
	         }
	        
            }

               

                    //Crear los links para los títulos de las tablas
                    ?>

			
					<tr >
						<Th align="center">
                        <A href=<?print('principal_gi.php?pag=gi_reaj.php&short=type'.$lista);?>>
                        Tipo</A>
                        </Th>
						<Th align="center" width="250">
                         <A href=<?print('principal_gi.php?pag=gi_reaj.php&short=nombre'.$lista);?>>
                        Nombre </Th>
						<Th align="center">
                         <A href=<?print('principal_gi.php?pag=gi_reaj.php&short=precio'.$lista);?>>
                        Precio<br>
                        (en euros)</Th>
						<Th align="center">
                        <A href=<?print('principal_gi.php?pag=gi_reaj.php&short=stock'.$lista);?>>
                        Stock</Th>
						<Th align="center" width"40">M</Th>
						<Th align="center" width"40">E</Th>
					</tr>
					</thead>
					<tbody class="scrollContentr" style='border-top:1px solid #CCC;border-bottom:1px solid #CCC;padding-left: 5px;padding-right: 5px; '>
					
				<?
                 $res=mysql_query($qry);
                if ($res){$filas=mysql_num_rows($res);}
                for ($i=0;$i<$filas;$i++){
	            $fila=mysql_fetch_array($res);


                //Mostrar el listado
                //Se crean los links de los botones modificar y eliminar pasando las variables cuad1, que valdrá 2 si se quiere modificar o 2 si se quiere eliminar, y la variable ide que nos identificará que carnet queremos modificar o eliminar.


                ?>
                     
					
					<?if($fila['Stock_Carnet']<6){?>
                           <tr id="texto_listadosrojo" onmouseover="resaltar_seleccionrojo(this);" onmouseout="desresaltar_seleccionrojo(this);" >
                            <?}else {?>

                            <tr id="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);" >
                           <? }?>
					
					
					
						<TD align='Center'>
                            <?print ($fila['Id_Carnet']);?></td>
     	                 <td align="center" width="250">
                           <?print ($fila['Nombre_Carnet']);?>
                        </td>
						<td align="center">
                             <?print ($fila['Precio_Carnet']);?>
                            </td>
						<td align="center"> <?print ($fila['Stock_Carnet']);?>
                        
                        </td>
					
						<?$hrefm="principal_gi.php?pag=gi_reaj.php&cuad1=2&ide=".$fila['Id_Carnet'];?>
						<td align="center"><a href="<?print($hrefm)?>" ><img src='../imagenes/botones/modificar.gif' border=0 alt="Modificar"> </td>
						<?$hrefe="principal_gi.php?pag=gi_reaj.php&cuad1=3&ide=".$fila['Id_Carnet'];?>
						<td align="center"><a href="<?print($hrefe)?>" ><img src='../imagenes/botones/eliminar.gif' border="0" alt="Eliminar" ></a></td>
					</tr>
					
					<?}?>
					</tbody>
					
				</table>
			</td>
			
		</tr>
		<tr>
		<td align="center" style="background-color:#f8f8f8">
		<br><br>
        <!---Al pulsar el botón imprimir se abrirá la página de imprimir reaj que nos mostrará el listado de tipos de reaj para poder imprimirlo-->
        <a href='#' onclick='window.open("paginas/imprimir_reaj.php", "Reaj",
        "width=700px,height=500px,menubar=no,location=no,resizable=yes,scrollbars=yes,status=no");'><img src='../imagenes/botones-texto/imprimir.jpg' border=0 alt="Imprimir Listado Tipo Reaj"></a>	</td></tr>
	</table>
				
		</td>

		
	</tr>		
</table>
<!--</td>-->
</body>
<?php
MYSQL_CLOSE($db);  //Cierre base de datos
		} //Fin del IF de comprobacion de acceso a la pagina
	else
		 echo "<div class='error'>
			NO TIENES PERMISOS PARA ACCEDER A ESTA PÁGINA
					
			</div>";
?>
