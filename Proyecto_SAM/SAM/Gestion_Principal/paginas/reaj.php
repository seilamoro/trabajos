<?if(isset($_SESSION['permisoREAJ']) && ($_SESSION['permisoREAJ']==true)) //Comprobando que se tiene permiso para acceder a la pagina
		{?>


	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


	<script language="javascript">


    //resalta la selección en el listado, también cambia el cursor a una mano
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

	
	
	//Abrir página para buscar cliente
	
	function abrir_busqueda(){
	window.open( "paginas/reaj_busq_dni.php?form=0", "_blank", "width=650px,height=650px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");
}


	function llamar()
            	{
            	   	var anio = document.imprimir.anio[document.imprimir.anio.selectedIndex].value; 
            	  
            	  	
					window.open( "paginas/imprimir_reaj.php?anio="+anio);
						 
						
					
				
				}
            	  

	// Calcula la letra del dni.
		function calcletra(dni)
		{
			var JuegoCaracteres="TRWAGMYFPDXBNJZSQVHLCKET";
			var Posicion= dni % 23;
			var Letra = JuegoCaracteres.charAt(Posicion);
			return Letra;
		}
    


	






    //Valida los campos para dar de alta un carnet, si algún campo no es correcto se mostrará un aviso:
    //Si en la selección del tipo de DNI hay una d, se comprobará que el carnet está formado por 8 dígitos más una letra mediante una expresión regular.
    //Se comprobará que la fecha de alta es una fecha válida y no es menor que la actual.
    //Se comprobará que los campos obligatorios están introducidos.
     function validatexto(){


      if(document.ventas.sdni.value=="d"){

     var dni=document.ventas.DNI.value;
     var numero=document.ventas.Numero.value;
     var diar=document.ventas.dia.value;
	 var mesr=document.ventas.mes.value;
	 var anior=document.ventas.anio.value;
	 var g=new Date(anior+"/"+ mesr +"/"+ diar);
     var f=(diar+"/"+ mesr +"/"+ anior);


     var dias=document.ventas.dias.value;
	 var mess=document.ventas.meses.value;
	 var anios=document.ventas.anios.value;
	 var h=new Date(anios+"/"+ mess +"/"+ dias);

     var exp= /^[Xx]?\d{8}[a-zA-Z]$/;
     var d = new Date();

	 d.setFullYear(f.substring(6,10),
		f.substring(3,5)-1,
			f.substring(0,2))



	
					if(dni.length == 7)
						{						 	
							document.ventas.DNI.value = "0"+dni+calcletra(dni);						
							
						} 			 
						
						// Si es igual a ocho...
						else if (dni.length == 8)
						{
						    //alert(dni.substring(0,7));
						    // El penúltimo tiene que ser un número y el último  carácter solo puede ser un número o una letra permitida.
							if ( ( (isNaN(dni.substring(7,8))) && (calcletra(dni.substring(0,7))!=dni.substring(7,8)) ) || 
							     ( (isNaN(dni.substring(8,9))) && (calcletra(dni.substring(0,8))!=dni.substring(8,9)) )    
								) 
							{
								alert("Debe rellenar correctamente la letra del DNI.");	
								document.ventas.DNI.focus();
								return false;
							}			
							// Le añadinos la letra si el último caracter es un número
							if (!isNaN(dni.substring(7,8))) document.ventas.DNI.value = dni+calcletra(dni);						 
						 }   
							// Si es igual a nueve..
						else if (dni.length == 9)
						{
						    
						 	// El penúltimo tiene que ser un número y el último carácter solo puede ser una letra permitida
							if ( (isNaN(dni.substring(7,8)) ) || (calcletra(dni.substring(0,8))!=dni.substring(8,9)) ) 
							{
								alert("Debe rellenar correctamente la letra  del DNI.");					
								document.ventas.DNI.focus();
								return false;
							}

						}
					
					if(numero.length == 0 || isNaN(numero))
					{
					  	alert("Debe rellenar correctamente el campo Número.");					
						document.ventas.numero.focus();
						return false;
					}


       if (document.ventas.Nombre.value.length==0){
       alert("Debe rellenar el Nombre.");
       document.ventas.Nombre.focus();
        return 0;}
        else{if(document.ventas.Apellido1.value.length==0){
                alert("Debe rellenar el primer Apellido.");
                document.ventas.Apellido1.focus();}

                     else {if(d.getMonth() != f.substring(3,5)-1
		|| d.getDate() != f.substring(0,2))
	{
		alert("Fecha no válida.")
		return
	}else {
           if(g<h){alert("Fecha no válida,menor que la actual.");}
           else{
           document.ventas.submit();}




                       }
            }
        }




     }
      else{

     var diar=document.ventas.dia.value;
	 var mesr=document.ventas.mes.value;
	 var anior=document.ventas.anio.value;
	 var g=new Date(anior+"/"+ mesr +"/"+ diar);
     var f=(diar+"/"+ mesr +"/"+ anior);


     var dias=document.ventas.dias.value;
	 var mess=document.ventas.meses.value;
	 var anios=document.ventas.anios.value;
	 var h=new Date(anios+"/"+ mess +"/"+ dias);


     var d = new Date();

	 d.setFullYear(f.substring(6,10),
		f.substring(3,5)-1,
			f.substring(0,2))



       if (document.ventas.Nombre.value.length==0){
       alert("Debe rellenar el Nombre.");
       document.ventas.Nombre.focus();
        return 0;}
        else{if(document.ventas.Apellido1.value.length==0){
                alert("Debe rellenar el primer Apellido.");
                document.ventas.Apellido1.focus();}

                     else {if(d.getMonth() != f.substring(3,5)-1
		|| d.getDate() != f.substring(0,2))
	{
		alert("Fecha no válida.")
		return
	}else {
           if(g<h){alert("Fecha no válida,menor que la actual.");}
           else{
           document.ventas.submit();}




                       }
            }
        }




     }





   }
    
    		




    //Valida los campos para modificar un carnet, si algún campo no es correcto se mostrará un aviso:
    //Si en la selección del tipo de DNI hay una d, se comprobará que el carnet está formado por 8 dígitos más una letra mediante una expresión regular.
    //Se comprobará que la fecha de alta es una fecha válida y no es menor que la actual.
    //Se comprobará que los campos obligatorios están introducidos.
function validatexto2(){

     var dni=document.modifica.DNIm.value;
     var diar=document.modifica.diam.value;
	 var mesr=document.modifica.mesm.value;
	 var anior=document.modifica.aniom.value;
	 var g=new Date(anior+"/"+ mesr +"/"+ diar);
     var f=(diar+"/"+ mesr +"/"+ anior);


     var dias=document.modifica.dias.value;
	 var mess=document.modifica.meses.value;
	 var anios=document.modifica.anios.value;
	 var h=new Date(anios+"/"+ mess +"/"+ dias);


     var d = new Date();

	 d.setFullYear(f.substring(6,10),
		f.substring(3,5)-1,
			f.substring(0,2))


     var exp= /^(X|\d{1})\d{7}[a-zA-Z]$/;

			if(dni.length == 7)
						{						 	
							document.modifica.DNIm.value = "0"+dni+calcletra(dni);						
							
						} 			 
						
						// Si es igual a ocho...
						else if (dni.length == 8)
						{
						    //alert(dni.substring(0,7));
						    // El penúltimo tiene que ser un número y el último  carácter solo puede ser un número o una letra permitida.
							if ( ( (isNaN(dni.substring(7,8))) && (calcletra(dni.substring(0,7))!=dni.substring(7,8)) ) || 
							     ( (isNaN(dni.substring(8,9))) && (calcletra(dni.substring(0,8))!=dni.substring(8,9)) )    
								) 
							{
								alert("Debe rellenar correctamente la letra  del DNI.");	
								document.modifica.DNIm.focus();
								return false;
							}			
							// Le añadinos la letra si el último caracter es un número
							if (!isNaN(dni.substring(7,8))) document.modifica.DNIm.value = dni+calcletra(dni);						 
						 }   
							// Si es igual a nueve..
						else if (dni.length == 9)
						{
						    
						 	// El penúltimo tiene que ser un número y el último carácter solo puede ser una letra permitida
							if ( (isNaN(dni.substring(7,8)) ) || (calcletra(dni.substring(0,8))!=dni.substring(8,9)) ) 
							{
								alert("Debe rellenar correctamente la letra  del DNI.");					
								document.modifica.DNIm.focus();
								return false;
							}

						}





       if (document.modifica.Nombrem.value.length==0){
       alert("Debe rellenar el Nombre.");
       document.modifica.Nombre.focus();
        return 0;}
        else{if(document.modifica.Apellido1m.value.length==0){
                alert("Debe rellenar el primer Apellido.");
                document.modifica.Apellido1m.focus();}

                     else {if(d.getMonth() != f.substring(3,5)-1
		|| d.getDate() != f.substring(0,2))
	{
		alert("Fecha no válida.")
		return
	}else {
           if(g<h){alert("Fecha no válida,menor que la actual.");}
           else{
           document.modifica.submit();}
            }
        }




     }

}

      //Función que cambia el foco al primer elemento del formulario, el DNI, ya sea cuando se  muestra la pantalla de venta o la de modificar.
        function foco() {
        	if (document.getElementById("venta").style.display=="block") {
        		document.ventas.DNI.focus();
        	}
        	if (document.getElementById("modificar").style.display=="block") {
        		document.modifica.DNIm.focus();
        	}
        }





	</script>
  







<STYLE type="text/css">
A:link {text-decoration:none;color:#F3F3F3;}
A:visited {text-decoration:none;color:#F3F3F3;}
A:active {text-decoration:none;color:#F3F3F3;}
A:hover {text-decoration:none;color:#F3F3F3;}
</STYLE>



<?







    // Conexión a la base de datos
    @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }

     mysql_select_db($_SESSION['conexion']['db']);



    //Recogemos el valor de la variable acción para saber que pantalla mostrar: venta, modificar o eliminar. Si no existe la variable mostrará la pantalla de venta.
        if($_GET['accion']){
          $accion=$_GET['accion'];

        }else {$accion="venta";
             }

        $block="style='display:block'";
        $none="style='display:none'";





      //Se llama a la función foco para situar el foco en el primer elemento del formulario
?>

<BODY onLoad="foco();">





    <div id="izquierda" style='float:left;margin-left:0px;padding-left:14px;padding-right:px;'>

         <div id='venta'
         <?if(($accion=='venta')and($_GET['modificar']!="si")and($_GET['eliminar']!="si")){print($block);}else{print($none);}?>>
         
         
         <?
         
             //Entro en el div Venta Carnet Reaj si la accion es igual a venta, también se hace la comprobación de $_GET['modificar'] y $_GET['eliminar']!="si" en el caso de que venga de búsqueda.

              $resulerror=1;  //variable para controlar un error en sql cuando ya exista un carnet vendido a ese carnet.
              
              if(isset($_GET['anio'])){
			  
			 $sql="delete FROM solicitante WHERE SUBSTRING(Fecha,1,4)='".$_GET['anio']. "'";
			 //print($sql);
				$resul = mysql_query($sql);
	  
			}
              
              

            //Si vengo de eliminar $_GET['borrar'] será igual a si. Eliminará el carnet seleccionado
            //Si $_GET['busqueda']=="si" vendré de la página Búsquedas y mediante window.location.href vuelvo a la búsqueda con los mismos criterios
			

            if($_GET['borrar']==si){
              	
				  
				$sql3="SELECT * FROM solicitante Where DNI_Solic like '".$_GET['dnir']. "'";
				//print($sql3);

               $resul3 = mysql_query($sql3);
               $fila3 = mysql_fetch_array($resul3);
               $Tipo =$fila3['Id_Carnet'];
           
              
              	
                 $sql="DELETE FROM solicitante where DNI_Solic = '".$_GET['dnir']. "'";
                 $resul = mysql_query($sql);
                 
                 $sql2="SELECT * FROM reaj Where Id_Carnet like '".$Tipo. "'";

               $resul2 = mysql_query($sql2);
               $fila2 = mysql_fetch_array($resul2);
               $Stock=$fila2['Stock_Carnet']+1;

               $sql4="UPDATE reaj SET Stock_Carnet = '".$Stock. "' WHERE `Id_Carnet` = '".$Tipo. "'";
               $resul4 = mysql_query($sql4);
               

               if($_GET['busqueda']=="si"){
               echo "
                <script>
                window.location.href='.?pag=listado_criterio_reaj.php';

                </script>";

                } }



            //Si existe $_POST['DNIm'] estoy en el caso de modificar para ello recojo todos los datos a modificar y hago el update


            if($_POST['DNIm']){

             $DNIm=$_POST['DNIm'];
             $Nombrem=$_POST['Nombrem'];
             $Apellido1m=$_POST['Apellido1m'];
             $Apellido2m=$_POST['Apellido2m'];
             $Numerom=$_POST['Numerom'];
             $Tipov=$_POST['tipov'];
             $Tipom=$_POST['tipom'];
           
             $DNIr=$POST['DNImod'];
             $aniom=$_POST['aniom'];
             $mesm=$_POST['mesm'];
             $diam=$_POST['diam'];
             $Fecham=$aniom."-".$mesm."-".$diam;





            $sql="UPDATE solicitante SET DNI_Solic = '".$DNIm. "',
            `Nombre_Solic` = '".$Nombrem. "',
            `Apellido1_Solic` = '".$Apellido1m. "',
            `Apellido2_Solic` = '".$Apellido2m. "',
            `Numero` = '".$Numerom. "',
            `Fecha` = '".$Fecham. "',
            Id_Carnet ='".$tipom. "' WHERE `DNI_Solic` = '".$DNImod. "'";

             $resul = mysql_query($sql);
          if($Tipom){

              //Si también he cambiado el tipo de carnet habrá que  modificar el stock, sumandole uno al viejo y restando uno al nuevo.
             if($Tipov!=$Tipom){


               $sql2="SELECT * FROM reaj Where Id_Carnet like '".$Tipov. "'";

               $resul2 = mysql_query($sql2);
               $fila2 = mysql_fetch_array($resul2);
               $Stock=$fila2['Stock_Carnet']+1;

               $sql3="UPDATE reaj SET Stock_Carnet = '".$Stock. "' WHERE `Id_Carnet` = '".$Tipov. "'";


               $resul = mysql_query($sql3);

               $sql2="SELECT * FROM reaj Where Id_Carnet like '".$Tipom. "'";
             

               $resul2 = mysql_query($sql2);
               $fila2 = mysql_fetch_array($resul2);
            
               $Stock=$fila2['Stock_Carnet']-1;
                
               $sql3="UPDATE reaj SET Stock_Carnet = '".$Stock. "' WHERE `Id_Carnet` = '".$Tipom. "'";

              
               $resul = mysql_query($sql3);
               
                 }}
                 
                 
                 // Si vengo de la página de búsquedas después de hacer el update se volverá a ella, sino se volverá a la página de reaj.
                 if($_GET['busqueda']=="si"){
               echo "
                <script>
                window.location.href='.?pag=listado_criterio_reaj.php';

                </script>";
                
                } else {
                   echo "
                <script>
                window.location.href='.?pag=reaj.php';

                </script>";                }
             }














            //Si existe $_POST['DNI'] estamos en el caso de venta de un carnet para ello recogemos todos los datos y hacemos el insert.


             if($_POST['DNI']){


             $DNI=$_POST['DNI'];
             $Nombre=$_POST['Nombre'];
             $Apellido1=$_POST['Apellido1'];
             $Apellido2=$_POST['Apellido2'];
             $numero=$_POST['Numero'];
             $Id=$_POST['id'];
             $anio=$_POST['anio'];
             $mes=$_POST['mes'];
             $dia=$_POST['dia'];
             $Fecha=$anio."-".$mes."-".$dia;





             $sql = "insert into solicitante (DNI_Solic,Nombre_Solic,Apellido1_Solic,Apellido2_Solic,Fecha,Id_Carnet,Numero) values ('" . $DNI . "','" . $Nombre . "','" . $Apellido1 . "','" . $Apellido2. "','" .$Fecha."','" .$Id. "','" .$numero. "')";
             //print($sql);

             $resulerror = mysql_query($sql);
          
  
             //Comprobación de si la query ha dado error, llamando al a función repetido
              if($resulerror != 1){

               echo "<script>alert('Ya existe un carnet vendido para ese DNI');</script>";
				}

			
				
				else {
             $sql2="SELECT * FROM reaj Where Id_Carnet like '".$Id. "'";

             $resul2 = mysql_query($sql2);
             $fila2 = mysql_fetch_array($resul2);
             $Stock=$fila2['Stock_Carnet']-1;

              //Se resta 1 al stock del tipo de carnet que se ha realizado la venta
             $sql3="UPDATE reaj SET Stock_Carnet = '".$Stock. "' WHERE `Id_Carnet` = '".$Id. "'";


             $resul = mysql_query($sql3);

				}

                echo "
                <script>
                window.location.href='.?pag=reaj.php';
                
                </script>";
			
              }



                  //Tabla Venta Carnet
              ?>
    
        <table  border=0 cellspacing="0" cellpadding="0" style="padding 0px 0px 0px 0px;">

              <Form name='ventas' action='.?pag=reaj.php' METHOD="POST">
            
              <?$actuald=date(d);
              $actualm=date(m);
              $actualy=date(Y);?>


               <input type="hidden"  name="dias" value="<?print($actuald);?>">
               <input type="hidden"  name="meses" value="<?print($actualm);?>">
               <input type="hidden"  name="anios" value="<?print($actualy);?>">
              
               <thead>
                  <tr id="titulo_tablas">
						<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:341px;">Venta Carnet</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
						</td>
			   </thead>


                

               <tr>
			       <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">

                      <table border="0" class="tabla_detalles" style="border: 1px solid #3F7BCC;" height="400" width="400" cellspacing="0" cellpadding="0" >
                        <tr>
                           <td>&nbsp;</td>
                           <td  align="left" height="40">
			                  <br><label class="label_formulario">D.N.I.:</label></td>
                                 
                           <td align="left">
                               <SELECT class='select_formulario'name="sdni">
                               <option value="d" alt="DNI" title="DNI">D
                               <option value="p" alt="Pasaporte" title="Pasaporte">P
                               <option value="c" alt="Permiso de Conducir" title="Permiso de Conducir">C
                               <option value="i" alt="Carta o Documento de Identidad" title="Carta o Documento de Identidad">I
                               <option value="n" alt="Permiso de residencia Español" title="Permiso de residencia Español">N
                               <option value="x" alt="Permiso de residencia UE" title="Permiso de residencia UE">X
                               </SELECT>
                               &nbsp&nbsp<input class="input_formulario" type="text"  name="DNI" size="15" onBlur="this.value=this.value.toUpperCase();">&nbsp&nbsp
                               <!--se llama a la función abrir búsqueda que nos abrirá la página de búsqueda de clientes para poder seleccionar uno y que nos rellene automáticamente los datos ene l formulario -->
                               <a href="#" ><img src='../imagenes/botones/lupa.png' border="0" alt="Buscar"  onclick="abrir_busqueda();"></a>
                           </td>
                        </tr>

                        <tr height="39" >
                            <td>&nbsp;</td> 
                            <td align="left"><label class="label_formulario">Nombre:</label></td>
                            <td align="left"><input class="input_formulario" type="text"  name="Nombre" size="31" maxlength="20" onBlur="this.value=this.value.toUpperCase();"></td>
                        </tr>
                        <tr height="39">
                            <td>&nbsp;</td> 
                            <td align="left"><label class="label_formulario">Primer Apellido:</label></td>
                            <td align="left"><input class="input_formulario" type="text"  name="Apellido1" size="31" maxlength="30" onBlur="this.value=this.value.toUpperCase();"></td>
                        </tr>
            
                        <tr height="39">
                            <td>&nbsp;</td> 
                            <td align="left"><label class="label_formulario">Segundo Apellido:</label></td>
                            <td align="left"><input class="input_formulario" type="text"  name="Apellido2" size="31" maxlength="30" onBlur="this.value=this.value.toUpperCase();"></td>
                        </tr>
                        <tr height="39">
                            <td>&nbsp;</td> 
                            <td align="left"><label class="label_formulario">Número:</label></td>
                            <td align="left"><input class="input_formulario" type="text"  name="Numero" size="31" maxlength="8" onBlur="this.value=this.value.toUpperCase();"></td>
                        </tr>
                        <tr height="39">
                            <td>&nbsp;</td> 
                            <td  align="left">
                             <label class="label_formulario">Fecha:</label></td>


                            <td align="left"><SELECT class='select_formulario'name="dia">
                                     <?$actuald=date(d);
                                        for($i=01;$i<32;$i++){?>
                                        <option value="<?if($i<10){print("0".$i);}else{print($i);}?>"<? if($i==$actuald){print("selected");}?>><?print($i);?>
                                           <?}?>

                             </SELECT>
          
           
                             <SELECT class='select_formulario'name="mes">
                                    <?$m=date(m);?>
                                    <option value=01 <?if($m==1){print("selected");}?> >Enero
                                    <option value=02 <?if($m==2){print("selected");}?> >Febrero
                                    <option value=03 <?if($m==3){print("selected");}?> >Marzo
                                    <option value=04 <?if($m==4){print("selected");}?> >Abril
                                    <option value=05 <?if($m==5){print("selected");}?> >Mayo
                                    <option value=06 <?if($m==6){print("selected");}?> >Junio
                                    <option value=07 <?if($m==7){print("selected");}?> >Julio
                                    <option value=09 <?if($m==8){print("selected");}?> >Agosto
                                    <option value=09 <?if($m==9){print("selected");}?> >Septiembre
                                    <option value=10 <?if($m==10){print("selected");}?> >Octubre
                                    <option value=11 <?if($m==11){print("selected");}?> >Noviembre
                                    <option value=12 <?if($m==12){print("selected");}?> >Diciembre
                              </SELECT>
             

                              <SELECT class='select_formulario' name="anio">
                                      <?$actual=date(Y);
                                        for($i=$actual;$i<($actual+3);$i++){?>
                                        <option value="<?print($i);?>"<? if($i==$actual){print("selected");}?>><?print($i);?>
                                        <?}?>
                              </SELECT>
                                        
                        
                            </td>
                        </tr>
                        <tr height="39">
                            <td>&nbsp;</td> 
                            <td align="left"><label class="label_formulario">Tipo :</label></td>
                            <td align="left">
							   <SELECT class="select_formulario" name="id" onChange="preciocarnet(this.value);">
                                      <?
                                        //Mediante la función preciocarnet se mostrará en todo momento el precio del carnet seleccionado
                                        //Si el stock de un tipo de carnet es 0, no se mostrará en el select para realizar la venta
                                        $qry="SELECT * FROM reaj ";
                                        $res=mysql_query($qry);
                                        $filas=mysql_num_rows($res);
                                        for ($i=0;$i<$filas;$i++){
	                                    $fila=mysql_fetch_array($res);
	                  
                                        if($fila['Stock_Carnet']>0){

                                      ?>
                                        <option value="<?print($fila['Id_Carnet']);?>"><?print($fila['Nombre_Carnet']);}}?>
                               </SELECT>
                         
                            </td>
                        
   			
				
                
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td align="left">                                   
								<input class="label_formulario" size ="15" style="border:0px;background-color:#F4FCFF;" type="text" name="precio_carnet" value="" contenteditable=false >
                            </td>
                        </tr>
           
                        <tr>
                           <td colspan='3' align='center'>
                           <br><br><a href="#" onClick="validatexto();">  <img src='../imagenes\botones-texto\aceptar.jpg' alt="Realizar Venta de Carnet" border="0"><a>
						   </td>
					   </tr>
            	</form>
            	
            	
            	
                      </table>
			
			       </td>
				  </tr>
			     </table>

         

            


            
    
      
      
      
      <br>
      
      
      
      <div id="imprimir">
      
        <table  border=0 cellspacing="0" cellpadding="0" style="padding 0px 0px 0px 0px;">

              <Form name='imprimir' action='/imprimir_reaj.php' METHOD="POST">
            
              
              
               <thead>
                  <tr id="titulo_tablas">
						<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:341px;">Imprimir Carnet</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
						</td>
			   </thead>


                

               <tr>
			       <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">

                      <table border="0" class="tabla_detalles" style="border: 1px solid #3F7BCC;" height="100" width="400" cellspacing="0" cellpadding="0" >
                        <tr height="35">
                            <td >&nbsp;</td> 
                            <td  align="right" width="170px">
                             <label class="label_formulario">Año:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>


                            <td align="left" >&nbsp;
          
           
                            

                              <SELECT class='select_formulario' name="anio">
                                      
										<?php
											$sql_anio="SELECT DISTINCT (SUBSTRING( Fecha, 1, 4)) as anio from solicitante";
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
                           <td colspan='3' align='center'>
                           <br>
                           <a href="#" onClick="llamar();">  <img src='../imagenes\botones-texto\aceptar.jpg' alt="Imprimir o Eliminar Carnets del año seleccionado" border="0"><a>
						   </td>
					   </tr>
            	</form>
            	
            	
            	
            	
            
				 
            	
            	
            	
                      </table>
			
			       </td>
				  </tr>
			     </table>

         

            


            
      </div>
      </div>




    

      <div id='modificar' <?
      // Tabla Modificar Carnet. Se mostrará cuando la accion sea igual a modificar o se venga de una búsqueda, que será el caso en que modificar sea igual a si
      
      if(($accion=='modificar')or($_GET['modificar']=='si')){print($block);}else{print($none);}?>>

            <table  border=0 cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
            <form action='.?pag=reaj.php' method="POST" name="modifica">
            <thead>

					<tr id="titulo_tablas">
						<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro'  style="width:341px;">Modificar Carnet</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>	
			 </thead>


                      
             <tr>
                 <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                    <table class="tabla_detalles" style="border: 1px solid #3F7BCC;" border="0" width="400" height="550" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">



            <?


             /*Se hará un select para  mostrar los datos a modificar*/

            if($_GET['dnir']){

               $sql="SELECT solicitante.*,reaj.*  FROM solicitante INNER JOIN reaj ON solicitante.Id_Carnet=reaj.Id_Carnet Where DNI_Solic like '".$_GET['dnir']. "'";

               $resul = mysql_query($sql);
               $fila = mysql_fetch_array($resul);
               $DNIr=$fila['DNI_Solic'];
               $Nombrer=$fila['Nombre_Solic'];
               $Nombrec=$fila['Nombre_Carnet'];
            
               
               $Apellido1r=$fila['Apellido1_Solic'];
               $Apellido2r=$fila['Apellido2_Solic'];
               $Tipo=$fila['Id_Carnet'];
               $Numeror=$fila['Numero'];
               $Fecha=$fila['Fecha'];
               $fecha_des=split("-",$fila['Fecha']);

               $diar=$fecha_des[2];
               $mesr=$fecha_des[1];
               $anior=$fecha_des[0];


               } else{
                   $DNIr="";
               $Nombrer="";
               $Apellido1r="";
               $Apellido2r="";
               $Numeror="";




               }

              $actuald=date(d);
              $actualm=date(m);
              $actualy=date(Y);?>


             
              
               		<input type="hidden"  name="dias" value="<?print($actuald);?>">
               		<input type="hidden"  name="meses" value="<?print($actualm);?>">
               		<input type="hidden"  name="anios" value="<?print($actualy);?>">

                    <input type="hidden" name="DNImod" value="<?print($DNIr);?>">
                    <input type="hidden" name="tipov"  value="<?print($Tipo)?>">
                    <tr height="45">
                        <td>&nbsp;</td>
                        <td align="left">
			            <br><label class="label_formulario">D.N.I.:</label></td>
                        <td align="left"><br><input class="input_formulario" type="text"  name="DNIm" value="<?print($DNIr);?>" size="31" maxlength="20"></td>
                    </tr>
                    <tr height="45">
                        <td>&nbsp;</td>
                        <td align="left">
	                    <label class="label_formulario">Nombre:</label></td>
                        <td align="left"><input class="input_formulario" type="text"  name="Nombrem" value="<?print($Nombrer);?>" size="31" maxlength="30" onBlur="this.value=this.value.toUpperCase();"></td>
                     </tr>
                     
                     <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Primer Apellido:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="Apellido1m" value="<?print($Apellido1r);?>" size="31" maxlength="30" onBlur="this.value=this.value.toUpperCase();"></td>
                     </tr>
                     <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Segundo Apellido:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="Apellido2m" value="<?print($Apellido2r);?>" size="31" maxlength="30" onBlur="this.value=this.value.toUpperCase();"></td>
                     </tr>
                     <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Número:</label></td>
                         <td align="left"><input class="input_formulario" type="text"  name="Numerom" value="<?print($Numeror);?>" size="31" maxlength="8" onBlur="this.value=this.value.toUpperCase();"></td>
                     </tr>
                     <tr height="45">
                         <td>&nbsp;</td>
                         <td  align="left">
                         <label class="label_formulario">Fecha:</label>
                         </td>
                         <td align="left"><SELECT class='select_formulario'name="diam">

                         <?for($i=1;$i<32;$i++)
                              {?>
                               <option value="<?print($i);?>"<? if($i==$diar){print("selected");}?>><?print($i);?>
                              <?}?>

                         </SELECT>



                         <SELECT class='select_formulario'name="mesm">
                    
                             <option value=01 <?if($mesr==1){print("selected");}?> >Enero
                             <option value=02 <?if($mesr==2){print("selected");}?> >Febrero
                             <option value=03 <?if($mesr==3){print("selected");}?> >Marzo
                             <option value=04 <?if($mesr==4){print("selected");}?> >Abril
                             <option value=05 <?if($mesr==5){print("selected");}?> >Mayo
                             <option value=06 <?if($mesr==6){print("selected");}?> >Junio
                             <option value=07 <?if($mesr==7){print("selected");}?> >Julio
                             <option value=09 <?if($mesr==8){print("selected");}?> >Agosto
                             <option value=09 <?if($mesr==9){print("selected");}?> >Septiembre
                             <option value=10 <?if($mesr==10){print("selected");}?> >Octubre
                             <option value=11 <?if($mesr==11){print("selected");}?> >Noviembre
                             <option value=12 <?if($mesr==12){print("selected");}?> >Diciembre


                        </SELECT>


                        <SELECT class='select_formulario' name="aniom">
                             <?$actual=date(Y);
                              for($i=$actual;$i<($actual+4);$i++)
                                 {?>
                                <option value="<?print($i);?>"<? if($i==$anior){print("selected");}?>><?print($i);?>
                                <?}?>

                         </td>
                     </tr>
                     <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Tipo:</label></td>
                         <td align="left">
                           <SELECT class="select_formulario" name="tipom" onChange="preciocarnet2(this.value);">
            
                          <?//Rellenamos un array para saber el id carnet y el precio de todos los carnets
                            $pecrioselect=array();
				            $qry="SELECT * FROM reaj ";
                            $res=mysql_query($qry);
                            $filas=mysql_num_rows($res);
                            for ($i=0;$i<$filas;$i++){
	                             $fila=mysql_fetch_array($res);
                                 $precioselect[$i]['Id_Carnet']=$fila['Id_Carnet'];
                                 $precioselect[$i]['Precio']=$fila['Precio_Carnet'];

	                               if($fila['Stock_Carnet']>0){

                          //Se mostrará seleccionado el carnet a modificar
                          ?>



                           <option value="<?print($fila['Id_Carnet']);?>"<?if($fila['Nombre_Carnet']==$Nombrec){print("selected");}?>><?print($fila['Nombre_Carnet']);}}?>
				           </SELECT>
             
                         </td>
                     </tr>
                     <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td align="left">
                            <input class="label_formulario" size ="15" style="border:0px;background-color:#F4FCFF;" type="text" name="precio_carnet" value="" contenteditable=false >
                            </td>
                        </tr>
                     <tr>
                         <td colspan='3' align='center'>
                        <?
                        // Si pulso modificar  y vengo de búsqueda creo los links para ir al div de venta y realizar el update. Se validará antes todoslos campos del formulario.
                        //  Si pulso cancelar vuelvo al criterio de búsqueda
                        if($_GET['busqueda']=="si"){

                           $hrefmodificar=".?pag=reaj.php&accion=venta&modificar=si&busq=si&dnimod=".$DNIm;
                           ?>
                           <br><br><a href="#" onclick='validatexto2();'> <img src='../imagenes/botones-texto/modificar.jpg' alt="Modificar Carnet"border="0"></a>
                           &nbsp&nbsp<a href=".?pag=listado_criterio_reaj.php" > <img src='../imagenes/botones-texto/cancelar.jpg' alt="Volver a Búsqueda Carnet" border="0"></a></td>


                      <?  }
                        else {
                        

                          //Si pulso modificar iremos al div de venta carnet para realizar el update
                          //Si pulso cancelar volveremos a la página principal
                         
                           $hrefmodificar=".?pag=reaj.php&accion=venta&modificar=si&dnimod=".$DNIm;?>
                           <br><br><a href="#" onclick='validatexto2();'> <img src='../imagenes/botones-texto/modificar.jpg' alt="Modificar Carnet" border="0"></a>
                           &nbsp&nbsp<a href=".?pag=reaj.php" > <img src='../imagenes/botones-texto/cancelar.jpg' alt="Volver a Venta Carnet" border="0"></a></td>
                           
                          <? }?>
                     </tr>

            </form>
			</table>
			
                 </td></table>

             </tr>






       </div>







      <div id='eliminar' <?
        //Tabla eliminar carnet
      if(($accion=='eliminar')||($_GET['eliminar']=='si')){print($block);}else{print($none);}?>>

            <table  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;"><form>
            <thead>

                  <tr id="titulo_tablas">
						<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:341px;">Eliminar Carnet</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
						</td>


                  </tr>
            </thead>
            <tr>
               <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                   <table border="0" class="tabla_detalles" style="border: 1px solid #3F7BCC;" width="400" height="550">



              <?
              // Se hace un select para mostrar los datos del carnet a eliminar.

              if($_GET['dnir']){

               $sql="SELECT * FROM solicitante Where DNI_Solic like '".$_GET['dnir']. "'";

               $resul = mysql_query($sql);
               $fila = mysql_fetch_array($resul);
               $DNIr=$fila['DNI_Solic'];
               $Nombrer=$fila['Nombre_Solic'];
               $Apellido1r=$fila['Apellido1_Solic'];
               $Apellido2r=$fila['Apellido2_Solic'];
               $Numeror=$fila['Numero'];
               $id=$fila['Id_Carnet'];
               $Tipo=$fila['Nombre_Carnet'];
               $fecha_des=split("-",$fila['Fecha']);
               $Fechar=$fecha_des[2]."-".$fecha_des[1]."-".$fecha_des[0];
               
               $sql2="SELECT * FROM reaj Where Id_Carnet like '".$id. "'";
               $resul2 = mysql_query($sql2);
               $fila = mysql_fetch_array($resul2);
               $Nombrec=$fila['Nombre_Carnet'];
                }
               ?>
              






            



                      <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><br><label class="label_formulario">D.N.I.:</label></td>
                         <td class="texto_detalles" align="left"><br><?print($DNIr);?></td>
                      </tr>

                      <tr height="45">
                          <td>&nbsp;</td>
                          <td align="left"><label class="label_formulario">Nombre:</label></td>
                          <td class="texto_detalles" align="left"><?print($Nombrer);?></td>
                      </tr>
                      <tr height="45">
                          <td>&nbsp;</td>
                          <td align="left"><label class="label_formulario">Primer Apellido:</label></td>
                          <td class="texto_detalles" align="left"><?print($Apellido1r);?></td>
                      </tr>
                      <tr height="45">
                          <td>&nbsp;</td>
                          <td align="left"><label class="label_formulario">Segundo Apellido:</label></td>
                          <td class="texto_detalles" align="left"><?print($Apellido2r);?></td>
                      </tr>
                      <tr height="45">
                          <td>&nbsp;</td>
                          <td align="left"><label class="label_formulario">Número:</label></td>
                          <td class="texto_detalles" align="left"><?print($Numeror);?></td>
                      </tr>
                      <tr height="45">
                         <td>&nbsp;</td>
                         <td align="left"><label class="label_formulario">Fecha:</label></td>
                         <td class="texto_detalles" align="left"><?print($Fechar);?></td>
                      </tr>
                      <tr height="45">
                          <td>&nbsp;</td>
                          <td align="left"><label class="label_formulario">Tipo:</label></td>
                          <td class="texto_detalles" align="left"><?print($Nombrec);?></td>
                      </tr>
                      <tr>
                          <td colspan='3' align='center'>
                          <br><label class="label_formulario">¿Está seguro de que desea eliminar</label><br>
                          <label class="label_formulario">la venta del carnet?</label><br>
                          <?
                           //Si vengo de una búsqueda y pulso aceptar creo el link para ir al div venta y le paso el parámetro búsqueda igual a si para poder volver luego a los criterios de búsqueda.
                           //Si pulso cancelar vuelvo al listado criterio reaj
                           $hrefaceptare=".?pag=reaj.php&accion=venta&borrar=si&dnir=".$DNIr;
                           if($_GET['busqueda']=='si'){
                                       $hrefaceptare=".?pag=reaj.php&accion=venta&busqueda=si&borrar=si&dnir=".$DNIr;?>
                             <br><a href="<?print($hrefaceptare)?>" > <img src='../imagenes/botones-texto/aceptar.jpg' alt="Eliminar Carnet" border="0"></a>
                           &nbsp&nbsp<a href=".?pag=listado_criterio_reaj.php" > <img src='../imagenes/botones-texto/cancelar.jpg' alt="Volver a Búsqueda de Carnets" border="0"></a><td>

                           <? }else{
                             //Si pulso aceptar voy al div venta para realizar el delete.
                             //Si pulso cancelar se irá a la página de reaj
                             ?>


                          <br><a href="<?print($hrefaceptare)?>" > <img src='../imagenes/botones-texto/aceptar.jpg' alt="Eliminar Carnet" border="0"></a>
                          

                           &nbsp&nbsp<a href=".?pag=reaj.php" > <img src='../imagenes/botones-texto/cancelar.jpg' alt="Volver a Venta Carnet" border="0"></a><td>
                          <? }?>
                      </tr>

                     </form>
                   </table>
               </tr>
            </td>
			
            </table>



          </div>







     </div>

     <div id="listado" style="display:block;" valign="top">
    




        <?//Listado de Ventas de Carnet Reaj
        


         
             //Seleccionar parámetros para crear la query ordenando según los campos  del listado  ascendente o descendentemente

          if ($_GET['ord']=='des'){
	          $ordena=' DESC';
	          $lista='&ord=asc';

             }
           else{
	       $lista='&ord=des';
           }




          $qry="SELECT * FROM solicitante ";
          if($_GET['short']){
	         if($_GET['short']=='fecha'){
	         $qry=$qry." order by solicitante.Fecha".$ordena;
	         }
	         if($_GET['short']=='name'){
             $qry=$qry." order by solicitante.Nombre_Solic".$ordena;
	         }
	         if($_GET['short']=='ap1'){
		     $qry=$qry." order by solicitante.Apellido1_Solic".$ordena;
	         }
	         if($_GET['short']=='ap2'){
		     $qry=$qry." order by solicitante.Apellido2_Solic".$ordena;
	         }
	         if($_GET['short']=='type'){
		     $qry=$qry." order by solicitante.Id_Carnet".$ordena;
	         }
	         if($_GET['short']=='num'){
		     $qry=$qry." order by solicitante.Numero".$ordena;
	         }
            }

       







		


                 // Tabla de Listado de Carnets


 ?>
             <table border="0"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
					<tr id="titulo_tablas">
						<td colspan="7" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:690px;"> Listado de Carnets</div>
					
								<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>


                    <tr>
                        <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                           <div id="tableContainer" class="tableContainer" style='height:548px;width: 747px;background-color:#f4fcff'>
                                 <table border="0" class="tabla_detalles" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" width="100%" class="scrollTable" style='width: 730px;'>
									<thead id="titulos" class="fixedHeader" style='position:relative;'>


						            <th align="center" width="11">
                                        <A href=<?print('.?pag=reaj.php&short=type'.$lista);?>>
							            Tipo</A>
				                    </th>
						            <th width="100">
                                        <A href=<?print('.?pag=reaj.php&short=num'.$lista);?>>
							             Número</A>
						            </th>
						            <th width="130">
                                        <A href=<?print('.?pag=reaj.php&short=name'.$lista);?>>
							            Nombre</A>
						            </th>
						            <th width="190">
                                        <A href=<?print('.?pag=reaj.php&short=ap1'.$lista);?>>
							            Apellidos</A>
						            </th>
						            <th width="85">
                                        <A href=<?print('.?pag=reaj.php&short=fecha'.$lista);?>>
                                        Fecha</A>
						            </th>
						            <th width="20">
                                        M
					            	</th>
						            <th width="20">
                                        E
						            </th>
					    
				                    </thead>
                                    <tbody class="scrollContent" >

                                    <?
                                    //Ordenar la fecha
                                    $res=mysql_query($qry);
                                    if ($res){$filas=mysql_num_rows($res);}
                                    for ($i=0;$i<$filas;$i++){
	                                 $tupla=mysql_fetch_array($res);
                                     $fecha_des=split("-",$tupla['Fecha']);
                                     $fecha=$fecha_des[2]."-".$fecha_des[1]."-".$fecha_des[0];
								
                                    ?>


                                 <form name="modelim"  METHOD="GET">

					             <?$hrefelim=".?pag=reaj.php&accion=eliminar&borrar=no&dnir=".$tupla['DNI_Solic'];
                                   $hrefmod=".?pag=reaj.php&accion=modificar&dnir=".$tupla['DNI_Solic'];?>
					                  <tr class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);"  >
						                  <td align="center">
                                              <?print ($tupla['Id_Carnet']);?>
					                      </td>
						                  <td width="90">
							                  <?print ($tupla['Numero']);?>
						                  </td>
		                                  <td width="130" align="left">
				                              <?print ($tupla['Nombre_Solic']);?>
						                  </td>
						                  <td width="200" align="left">
							                  <?print ($tupla['Apellido1_Solic']." ".$tupla['Apellido2_Solic']);?>
						                  </td>
						                  <td align="center">
                                              <?print ($fecha);?>
						                  </td>


						                  <td><a href="<?print($hrefmod)?>" ><img src='../imagenes/botones/modificar.gif' border="0" alt="Modificar"></a></td>
						                  <td><a href="<?print($hrefelim)?>" ><img src='../imagenes/botones/eliminar.gif' border="0" alt="Eliminar" ></a></td>
					                  </tr>
				            	<?}?>
                                 </form>

                                    </tbody>
								    </table>
                           </div>




                        </td>
                    </tr>
             </table>
     </div>
     
     
     
     
     
     
<script language="javascript">



var bar=new Array();



     //funcion que nos avisa cuando ya existe un carnet vendido para ese DNI
	function repetido(){
                      alert("Ya existe un carnet vendido para ese DNI.");
                 }




//Array para pasar el array en php del precio de los carnet a javascript.
<?php
   for($i=0;$i<count($precioselect);$i++){
     echo "bar[".$i."]=new Array();";
     echo "bar[".$i."][0]='".$precioselect[$i]['Id_Carnet']."';";
     echo "bar[".$i."][1]='".$precioselect[$i]['Precio']."';";
    }
    
   ?>
   
   //funcion para saber el precio de un carnet en el formulario de venta carnet
 function preciocarnet(valor){

      for(var i=0;i < bar.length;i++){

               if( bar[i][0]==valor){
                 
                   var precio="("+ bar[i][1] + " euros)";

                   document.ventas.precio_carnet.value=precio;


               }

      }
     }
   //funcion para saber el precio de un carnet en el formulario de modificar carnet
  preciocarnet(document.ventas.id.value);
  
  
   function preciocarnet2(valor){

      for(var i=0;i < bar.length;i++){

               if( bar[i][0]==valor){

                   var precio="("+ bar[i][1] + " euros)";

                   document.modifica.precio_carnet.value=precio;


               }

      }
     }
     
   preciocarnet2(document.modifica.tipom.value); //llamada a la función
   
  <?
  
  //Comprobación de si la query ha dado error, llamando al a función repetido
  if($resulerror != 1){

              ?>
               repetido();



<?}?>

</script>

<?php 	
  MYSQL_CLOSE($db); //Cierre de la base de datos


		} //Fin del IF de comprobacion de acceso a la pagina
	else{
		 echo "<div class='error'>
		 				
		 	
		 			
				
						NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
						
			</div>";
			}
?>

	
	
