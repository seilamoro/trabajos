<?PHP
session_start();

if(isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{

?>
<!--Este estilos es para que resalte el enlace en los listados al darles para que ordenen y hace que el puntero del ratón se convierta en una mano-->
<style type="text/css"> 
a.enlace { 
color: white; 
width: 100%; 
height: 100%; 
text-decoration: none; 
} 
a.enlace:hover { 
text-decoration: none; 
} 
</style>
<script language="javascript">
//para resaltar la fila del listado sobre la que está el cursor mediante cambiar el fondo y el color de la fuente de dicha línea así como hacer que el puntero del ratón se convierta en una mano 
	function resaltar_seleccion(tr) 
		{
			tr.style.backgroundColor = '#3F7BCC';
			tr.style.color = '#F4FCFF';
			tr.style.cursor = 'pointer';
		}
// Esta función anula la función anterior
	function desresaltar_seleccion(tr) 
		{
			tr.style.backgroundColor = '#F4FCFF';
			tr.style.color = '#3F7BCC';
		}

//Función para mostrar Nuevo Grupo de Edad(es la opción 1), Modificar Grupo de Edad(es la opción 2) o Eliminar Grupo de Edad(es la opción 3) sin quitar de la pantalla en ningún momento el listado de Grupo de Edades
		function cambiar_opcion(op,id_e,nombre_e,e_min,e_max)
			{
				if (op==1)
					{
						document.getElementById('nuevo_grupo').style.display='block';
						document.getElementById('modificar_grupo').style.display='none';
						document.getElementById('eliminar_grupo').style.display='none';
					}
				if (op==2)
					{
						document.getElementById('nuevo_grupo').style.display='none';
						document.getElementById('modificar_grupo').style.display='block';
						document.getElementById('eliminar_grupo').style.display='none';	
						
						//Esto es para mandar los datos al formulario cuando se llame a la opción de modificar
						document.forms["modifica_grupo"].id_mod.value = id_e;
						document.forms["modifica_grupo"].nombre_mod.value = nombre_e;
						document.forms["modifica_grupo"].edad_min_mod.value = e_min;
						document.forms["modifica_grupo"].edad_max_mod.value = e_max;
					}
				if (op==3)
					{
						document.getElementById('nuevo_grupo').style.display='none';
						document.getElementById('modificar_grupo').style.display='none';
						document.getElementById('eliminar_grupo').style.display='block';

						//Esto es para mandar los datos al formulario cuando se llame a la opción de eliminar
						document.forms["elimina_grupo"].id_eli.value = id_e;
						document.forms["elimina_grupo"].nombre_eli.value = nombre_e;
						document.forms["elimina_grupo"].edad_min_eli.value = e_min;
						document.forms["elimina_grupo"].edad_max_eli.value = e_max;
					}
			}
//Esta función comprueba si hay algún campo vacio, en ese caso mostrará una alerta comunicando que hay que rellenarlo para seguir. 
/*function vacia(algo)
   {
		if (algo=="")
			{
				alert("Tiene que rellenar el Nombre del Grupo de Edad.");
			}
	}
function vacia2(algo)
	{
		if (algo=="")
			{
				alert("Tiene que rellenar la Edad Mínima.");
			}
	}
function vacia3(algo)
	{
		if (algo=="")
			{
				alert("Tiene que rellenar la Edad Máxima.");
			}
	}*/
	
function vacios(name,emin,emax){
  if(name=="" || emin=="" || emax==""){
    alert("Debe rellenar todos los campos");
  }
  else{
    document.forms["nuevo_grupo"].submit();
  }
}

//Terminan las funciones
</script>
<?php
// Devuelve un booleano indicando si existe la tabla <<$table_name>> en la base de datos <<$base_datos>>.
function table_exists($table_name,$base_datos)
        {
            $Table = mysql_query("show tables like '" .$table_name . "'",$base_datos);
            if(mysql_fetch_row($Table) == false)
                return(false);
            else
                return(true);
         }

@$db  = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
@mysql_select_db($_SESSION['conexion']['db']);

if (!$db)
	{
		echo "Error, no se puede conectar a la base de datos";
		exit;
	}
						
// Variable booleana que almacena si la tabla servicios existe
$servicios_activados = table_exists('servicios',$db);

?>
<br><br>
<p style='margin-left:750px;margin-right:100px;'>

<table border='0' width='350'>
 <tr>
  <td align="right">
	  <table>
		  <tr>
			  <td>
					<a href='principal_gi.php?pag=gi_servicios.php'><img src='../imagenes/botones-texto/servicios.jpg' border=0 ></a>
			  </td>		  
			 <td>
				 <a href='principal_gi.php?pag=gi_tarifas.php'><img src='../imagenes/botones-texto/tarifas.jpg' border=0></a>	
			</td>
		</tr>
	 </table>
</td>
</tr>
</table>      
</p>
<div id="caja_superior" style="width:100%;height:auto;">
    <div id="caja_superior_izquierda" style='margin-left:30px;float:left;margin-bottom:50px;margin-top:0px;width:350px; padding:0px 0px 0px 0px;'>	
		<!-- EMPIEZA CREAR UN NUEVO GRUPO DE EDAD -->
        <div id='nuevo_grupo' style='margin-left:0px;display:block;'>
            <table border='0' id='tabla_detalles' cellspacing='0' cellpadding='0'>
				<form name= "nuevo_grupo" action='#' method='POST'>						
					<thead>
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'></div>
							<div class='champi_centro' style="width:250px;">
								<div class="titulo" style="width:280px;text-align:center;">Nuevo Grupo de Edad</div>
							</div>
							<div class='champi_derecha'>
                            </div>
						</td>
					</thead>
					<?PHP					
						//$id=$_POST['id'];
						$nombre=$_POST['nombre'];
						$edad_min=$_POST['edad_min'];
						$edad_max=$_POST['edad_max'];
						
			//*** COMPROBAR QUE LA EDAD MINIMA Y MÁXIMA ESTÁ POR ENCIMA DE 0 Y LA EDAD MINIMA ES INFERIOR A LA MAXIMA***//
					if($edad_min != "" && $edad_max != ""){
						if($edad_min<0 || $edad_max<0 || $edad_min>$edad_max){
                            echo "<script>alert('Las edades introducidas no son correctas.');</script>";
                          }
                        //Comprobar que el grupo de edad no existe
						else{
                            $consulta=mysql_query("select Edad_Max, Edad_Min from edad");
                            $posible=1;
                            for($i=0;$i<mysql_num_rows($consulta);$i++)
                			{
                    			$fila=mysql_fetch_array($consulta);
                                $min=$fila['Edad_Min'];
                                $max=$fila['Edad_Max'];

                                if (!(($edad_min > $min) && ($edad_min > $max)))
                                {
                                   if (!($edad_max < $min)){
                                        $posible=0;
                                        break;
                                   }
                                }
                            }
                            if($posible==0){
                                 echo "<script>alert('El grupo de edad ya existe en el sistema.');</script>";
                            }
                            else{
                               $query="INSERT INTO edad (nombre_edad, edad_min, edad_max)
                               VALUES ('".trim($nombre)."',".trim($edad_min).",".trim($edad_max).");";
                            }
                        }//fin else
                   }
  			//*** FIN DE LA COMPROBACIÓN***//
  						
  						
  						
  						
  						
  						
                        if (isset($_POST['nombre']) && ($_POST['nombre']!=""))
                        {
							if(mysql_query($query))
								{
                                    
                                    // Si existe la tabla servicios, debemos actualizarla con la nueva edad
                                    if ($servicios_activados)
                                    {
										// Llamada al procedimiento que añade las nuevas edades en la tabla servicios
                                        $query = 'call annadir_edad_servicios();';
                                        @$res = mysql_query($query);
                                    }
                                    
									//es para que salga la cabecera de los textos al hacer la inserción de un grupo de edad
									?>
									<script>
                                        document.getElementById('nuevo_grupo').style.display='block';
                                        document.getElementById('modificar_grupo').style.display='none';
                                        document.getElementById('eliminar_grupo').style.display='none';
                                    </script>
									<tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="98%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
									    <tr>
                                            <td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label_formulario" align="center">
                                                Se ha añadido el nuevo grupo de edad a la base de datos.
									           <br>
                                            <? if ($servicios_activados) echo '<br>Recuerde que tiene que actualizar los servicios <br><br>'?>
									        </td>
                                        </tr>
									    <tr>
                                            <td align='center'>
                                               <br>
                                               <? if ($servicios_activados) echo "<a href='?pag=gi_servicios.php'>";
                                                  else echo "<a href='?pag=gi_grupo_edades.php'>";
                                               ?>
									           <img src='../imagenes\botones-texto\aceptar.jpg' border=0></a>
									           <br><br>
                                            </td>
                                        </tr>
                                    </table>
									</td>
									</tr>
									<?php
								}
                            else
                            {
	                               ?>
									<script>
                                        document.getElementById('nuevo_grupo').style.display='block';
                                        document.getElementById('modificar_grupo').style.display='none';
                                        document.getElementById('eliminar_grupo').style.display='none';
                                    </script>
									<tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="98%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
									    <tr>
                                            <td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label_formulario" align="center">
                                              No puede añadir el nuevo grupo de edad, inténtelo más tarde.<br>

									        </td>
                                        </tr>
									    <tr>
                                            <td align='center'>
                                               <br>
                                                 <a href='?pag=gi_grupo_edades.php'><img src='../imagenes\botones-texto\aceptar.jpg' border=0></a>
									           <br><br>
                                            </td>
                                        </tr>
                                    </table>
									</td>
									</tr>
									<?php
                            }
                          } 
							else{
						?>
					<tr style='padding-top:0px;'>
						<td>
							<table border='0' width='100%' style='border: 1px solid #3F7BCC;'>
								<tr height='25'>
									<td>
                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Nombre:
                                    </td>
									<td align='left'>
                                        <input type='text' size='30' maxlength='30' class='input_formulario' name='nombre'>
                                    </td>
								</tr>
								<tr height='25'>
									<td>

                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Edad Mínima:
                                    </td>
									<td align='left'>
                                        <input type='text' size='6' maxlength='3' name='edad_min' class='input_formulario'>
                                    </td>
								</tr>
								<tr height='25'>
									<td>

                                    </td>
                                    <td align='left' class='label_formulario'>
                                        Edad Máxima:
                                    </td>
									<td align='left'>
                                        <input type='text' size='6' maxlength='3' name='edad_max' class='input_formulario'>
                                    </td>
								</tr>
								<tr>
									<td>

                                    </td>
                                    <td colspan='9' align='center' height='65'>
									   <a href='#'>
                                        <img name='aceptar' src='..\imagenes\botones-texto\aceptar.jpg' border=0
                                             onclick="vacios(nombre.value,edad_min.value,edad_max.value)"
                                             onblur="vacia(id.value)" onblur="vacia(nombre.value)"
                                             onblur="vacia(edad_min.value)" onblur="vacia(edad_max.value)" alt='Dar de alta nuevo Grupo de Edades'>
                                       </a>
                                    </td>
								</tr>
							</table>
						</td>
					</tr>
					<?
							}
					?>
				</form>
            </table>			
        </div>
		<!--  ACABA LA CREACIÓN DE UN NUEVO GRUPO DE EDAD-->
		
		
		
		
		
		
		
		
		

		<!-- EMPIEZA MODIFICAR UN GRUPO DE EDAD -->
        <div id='modificar_grupo' style='display:none'>			
            <table border='0' id='tabla_detalles' cellspacing='0' cellpadding='0'>
				<form name= "modifica_grupo" action='#' method='POST'>		
					<thead>
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'>
                                 
                             </div>
							<div class='champi_centro' style="width:200px;">
								<div class="titulo" style="width:280px;text-align:center;">Modificar Grupo de Edad</div>
							</div>
							<div class='champi_derecha'></div>
						</td>
					</thead>
					<?php
						if(isset($_POST['id_mod']) && $_POST['id_mod']!="")
							{								
								$id_e_mod=$_REQUEST['id_mod'];
								$nom_mod=trim($_REQUEST['nombre_mod']);
								$ed_min_mod=$_REQUEST['edad_min_mod'];
								$ed_max_mod=$_REQUEST['edad_max_mod'];
								
								$query="UPDATE edad set  Nombre_Edad=' ".$nom_mod." ', Edad_Min=".$ed_min_mod.", Edad_Max=".$ed_max_mod."
                                        WHERE Id_Edad=".$id_e_mod.";";
								
								$res = mysql_query($query);
								if($res)
									{					
										//es para que salga la cabecera de los textos al hacer la modificación de un grupo de edad
                                    ?>
										<script>
                                            document.getElementById('nuevo_grupo').style.display='none';
                                        	document.getElementById('modificar_grupo').style.display='block';
                                        	document.getElementById('eliminar_grupo').style.display='none';
                                        </script>
										<tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="98%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
										  <tr>
                                            <td>
                                                
                                            </td>
                                          </tr>
										  <tr>
                                            <td class='label_formulario' align='center'>
                                                Se ha modificado el grupo de edad en la base de datos.
                                                <br>
										    </td>
                                           </tr>
										  <tr>
                                            <td align='center'>
                                               <br>
                                               <a href='?pag=gi_grupo_edades.php'><img src='../imagenes\botones-texto\aceptar.jpg' border=0></a>
										       <br><br>
                                            </td>
                                           </tr>
                                        </table>
										</td>
										</tr>
                                      <?php
									}
								else
									{
									?>
										<script>
                                            document.getElementById('nuevo_grupo').style.display='none';
                                        	document.getElementById('modificar_grupo').style.display='block';
                                        	document.getElementById('eliminar_grupo').style.display='none';
                                        </script>
										<tr style='padding-top:0px;'>
                                        <td>
										<table border="0" width="98%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
										  <tr>
                                            <td>
                                                
                                            </td>
                                          </tr>
										  <tr>
                                            <td class='label_formulario' align='center'>
                                                No se ha podido modificar el grupo de edad, inténtelo más tarde.
                                                <br>
										    </td>
                                           </tr>
										  <tr>
                                            <td align='center'>
                                               <br>
                                               <a href='?pag=gi_grupo_edades.php'><img src='../imagenes\botones-texto\aceptar.jpg' border=0></a>
										       <br><br>
                                            </td>
                                           </tr>
                                        </table>
										</td>
										</tr>
                                      <?php

									}
							}	else{													
					?>
					<tr  style='padding-top:0px;'>
						<td>
							<table border="0" width="100%" style="border:1px solid #3F7BCC;">						
								<tr height='25'>
									<td align='left'>
                                        <span style='margin-left:6px;' class='label_formulario'>
                                            Nombre:
                                        </span>
                                    </td>
									<td align='left'><input type='text' size='25' maxlength='25' class='input_formulario' name='nombre_mod'>
                                        <input type="hidden" name="id_mod" class='input_formulario' style="border:none; margin-left:6px;" readonly>
                                    </td>
								</tr>
								<tr height='25'>
									<td align='left'>
                                        <span style='margin-left:6px;' class='label_formulario'>
                                            Edad Mínima:
                                        </span>
                                    </td>
									<td align='left'>
                                        <input type='text'  class='input_formulario' size='6' maxlength='6' name='edad_min_mod'>
                                    </td>
								</tr>
								<tr height='25'>
									<td align='left'>
                                        <span style='margin-left:6px;' class='label_formulario'>
                                            Edad Máxima:
                                        </span>
                                    </td>
									<td align='left'><input type='text'  class='input_formulario' size='6' maxlength='6' name='edad_max_mod'></td>
								</tr>			
								<tr height='65'>
									<td align='center' height='50'>
    									<a href='#'>
                                            <img name='aceptar' src='../imagenes\botones-texto\aceptar.jpg' border=0
                                                 onclick='document.forms["modifica_grupo"].submit();' alt='Modificar Grupo de Edades'>
                                        </a>
                                    </td>
									<td align='center' height='50'>
                                        <a href='?pag=gi_grupo_edades.php'>
                                            <img src='../imagenes\botones-texto\cancelar.jpg' border=0>
                                        </a>
                                    </td>
								</tr>
								<tr>
                                   <td>
                                   </td>
                                </tr>
							</table>
						</td>
					</tr>
					<?
							}
					?>
			</form>			
          </table>
        </div>	
		<!-- ACABA LO DE MODIFICAR GRUPO DE EDAD -->
		
		
		
		
		
		
		
		

		<!--  EMPIEZA ELIMINAR UN GRUPO DE EDAD-->        
		<div id='eliminar_grupo' style='display:none;'>
			<table border='0' id='tabla_detalles' style='border:1px solid#3F7BCC;' cellspacing='0' cellpadding='0'>
				<form name="elimina_grupo" action="#" method="POST">
				<thead>
                    <td colspan='9' align='center' style='padding-bottom:0px'>
                        <div class='champi_izquierda'>
                        </div>
                        <div class='champi_centro' style="width:81%;">
                            <div class="titulo" style="width:287px;text-align:center;">
                                Eliminar Grupo de Edad
                            </div>
                        </div>
                        <div class='champi_derecha'>
                        </div>
                    </td>
                </thead>	
					<?php
                    	// Si ha dado al botón aceptar, recibimos en el formulario 'id_eli' distinto de nulo.
						if(isset($_POST['id_eli']) && $_POST['id_eli']!=""){
							$id_e_eli=$_REQUEST['id_eli'];
							
							// Comprobamos si hay dependencias con tarifas
                            $query='SELECT * FROM tarifa WHERE Id_Edad='.$id_e_eli.';';
                            $res = mysql_query($query);
                            $dependencias_tarifas = mysql_num_rows($res);

                            $res = 0;
                            // si no hay dependencias...
                            if (!$dependencias_tarifas)
                            {
                                // si los servicios están activados...
								if ($servicios_activados) 
								{
                                     // Comprobamos el número de servicios existentes
                                     $query='SELECT * FROM servicios';
                                     $res = mysql_query($query);
                                     $num_servicios = mysql_num_rows($res);
                                     
                                     // Si no existe un sólo servicio...
                                     if ($num_servicios!=1)
                                     {
                                        
                                        //borramos el servicio
                                        $query='DELETE FROM servicios WHERE Id_Edad='.$id_e_eli.';';
                                        $res = mysql_query($query);
                                        // Borramos la edad
                                        $query='DELETE FROM edad WHERE Id_Edad='.$id_e_eli.';';
                                        $res = mysql_query($query);
                                     }
                                     else
                                     {
                                       // para mostrar la ventana de error
                                       $res=0;
                                     }
                                }
                                else
                                {
                                    // Borramos la edad
                                    $query='DELETE FROM edad WHERE Id_Edad='.$id_e_eli.';';
                                    $res = mysql_query($query);
                                }
                                
                              }
							if($res)
										{
                                        ?>
											<!--es para que salga la cabecera de los textos al hacer la eliminación de un grupo de edad-->
											<script>
                                                document.getElementById('nuevo_grupo').style.display='none';
                                                document.getElementById('modificar_grupo').style.display='none';
                                                document.getElementById('eliminar_grupo').style.display='block';
                                            </script>
										    <tr style='padding-top:0px;'>
                                            <td>
										    <table border="0" width="100%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
                                                <tr>
                                                    <td>
                                                        
                                                    </td>
                                                </tr>
                                                <tr class="label_formulario">
                                                    <td align="center">
											         Se ha borrado el grupo de edad de la base de datos.
											         <br>
											        </td>
                                                </tr>
											    <tr>
                                                    <td align='center'>
                                                        <br>
											            <a href='?pag=gi_grupo_edades.php'>
                                                        <img src='../imagenes\botones-texto\aceptar.jpg' border=0></a>
											            <br><br>
                                                    </td>
                                                </tr>
                                            </table>
                                            </td>
                                            </tr>
                                         <?php
										}
									else
										{
											?>
											<!--es para que salga la cabecera de los textos al hacer la eliminación de un grupo de edad-->
											<script>
                                                document.getElementById('nuevo_grupo').style.display='none';
                                                document.getElementById('modificar_grupo').style.display='none';
                                                document.getElementById('eliminar_grupo').style.display='block';
                                            </script>
                                            <tr style='padding-top:0px;'>
                                            <td>
										    <table border="0" width="100%" cellspacing='0' cellpadding='0'
                                               style="border: 1px solid #3F7BCC;" class="label_formulario">
                                                <tr>
                                                    <td>
                                                        
                                                    </td>
                                                </tr>
                                                <tr class="label_formulario">
                                                    <td align="center">
											          No se puede borrar el grupo de edad, debe tener tarifas o servicios asignados.
											         <br>
											        </td>
                                                </tr>
											    <tr>
                                                    <td align='center'>
                                                        <br>
											            <a href='?pag=gi_grupo_edades.php'>
                                                        <img src='../imagenes\botones-texto\aceptar.jpg' border=0></a>
											            <br><br>
                                                    </td>
                                                </tr>
                                            </table>
                                            </td>
                                            </tr>
                                         <?php

										} 
							}else{
					?>
                			
				<tr  style='padding-top:0px;'>
					<td>
						<table border="0" width="100%" style="border: 1px solid #3F7BCC;">
							<tr height='25'>
								<td align='left'>
                                    <span style='margin-left:6px;' class='label_formulario'>
                                        Nombre:
                                    </span>
                                </td>
								<td align='left'>
                                    <input type='text' size='25' maxlength='25' class='input_formulario' name='nombre_eli' id='texto_detalles'
                                           style="border:none;" readonly>
                                    <input type='hidden' class='input_formulario' name='id_eli' id='texto_detalles'style="border:none;" >
                                </td>
							</tr>
							<tr height='25'>
								<td align='left'>
                                    <span style='margin-left:6px;' class='label_formulario'>
                                        Edad Mínima:
                                    </span>
                                </td>
								<td align='left'>
                                    <input type='text' class='input_formulario' name='edad_min_eli' id='texto_detalles' style="border:none;" readonly>
                                </td>
							</tr>
							<tr height='25'>
								<td align='left'>
                                    <span style='margin-left:6px;' class='label_formulario'>
                                        Edad Máxima:
                                    </span>
                                </td>
								<td align='left'>
                                    <input type='text' class='input_formulario' name='edad_max_eli' id='texto_detalles' style="border:none;" readonly>
                                </td>
							</tr>
							<tr height='65'>
								<td colspan='2' class='label_formulario' style='text-align:center;font-size:13px;margin-left:2px;'>
                                    ¿Está seguro de que desea eliminar el grupo?
                                </td>
							</tr>
							<tr height='45px'>
								<td align='center'>
								    <a href='#'>
                                    <img  onClick='document.forms["elimina_grupo"].submit();' name="aceptar" src='../imagenes\botones-texto\aceptar.jpg'
                                          border=0 alt='Eliminar Grupo de Edades' >
                                </td>
								<td align='center'>
                                    <a href='?pag=gi_grupo_edades.php'>
                                        <img  name="cancelar" src='../imagenes\botones-texto\cancelar.jpg' border=0>
                                    </a>
                                </td>
							</tr>
						</form>
						<?
							}	
						?>
					</table>	
				</td>
			</tr>
		</table>
	</div>		
		<!-- ACABA LA ELIMINACIÓN DE UN GRUPO DE EDAD -->

		<!--  EMPIEZA EL LISTADO DE LOS GRUPO DE EDADES-->
    </div>
    <div id="caja_superior_derecha_b" style='border:float:right;margin-bottom:50px;margin-top:0px;width:300px;padding:0px 0px 0px 0px;'>
        <div id='listado_grupo_edades' style='display:block;'>
            <table border="0" id="tabla_detalles" align="center" cellpadding='0' cellspacing='0'>
                <thead>
                    <td colspan="9" align="center" style='padding-bottom:0px'>
                        <div class='champi_izquierda'></div>
                        <div class='champi_centro'>
                            <div class="titulo" style="width:530px;text-align:center;">Listado de Grupo de Edades</div>
                        </div>
					    <div class='champi_derecha'>
                        </div>
                    </td>
                </thead>
                <tr>
                    <td style='padding-top:0px;'>
						<div  class="tableContainer" align="center" style="width:590px;height:200px;padding-top:0px;padding-top:0px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="scrollTable" style="width:570px;"
                                  name='formulario'>
								<thead class="fixedHeader">
										<?php											
											$id=$_POST['id'];
											$nombre=$_POST['nombre'];
											$edad_min=$_POST['edad_min'];
											$edad_max=$_POST['edad_max'];

									echo "<input type='hidden' name='criterio' value=".$_GET['criterio']." >";	

						//recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es ascendente o descendente.
							
											
											if($_GET['ordenado']=='Nombre_Edad')
												{
													$query='Select * from edad order by Nombre_Edad';

													if($_GET['criterio']==0)
														{
															$query=$query.' ASC;';
														}
													else
														{
															$query=$query.' DESC';
														}
												}
											elseif($_GET['ordenado']=='Edad_Min')
												{
													$query='Select * from edad order by Edad_Min';

													if($_GET['criterio']==0)
														{
															$query=$query.' ASC;';
														}
													else
														{
															$query=$query.' DESC';
														}
												}
											elseif($_GET['ordenado']=='Edad_Max')
												{
													$query='Select * from edad order by Edad_Max';

													if($_GET['criterio']==0)
														{
															$query=$query.' ASC;';
														}
													else
														{
															$query=$query.' DESC';
														}
												}
											else
											{
												$query='Select * from edad';
											}

									$result=mysql_query($query);		
									if($_GET['criterio']==1)
										{
										?>
										<tr  id='titulo_listado' style='padding-top:0px;'>
											<th align='center'>
                                                <a class='enlace' href="?pag=gi_grupo_edades.php&ordenado=Nombre_Edad&criterio=0">
                                                    Nombre
                                                </a>
                                            </th>
											<th align='center'>
                                                <a class='enlace' href="?pag=gi_grupo_edades.php&ordenado=Edad_Min&criterio=0">
                                                    Edad Mínima
                                                </a>
                                            </th>
											<th align='center'>
                                                <a class='enlace' href="?pag=gi_grupo_edades.php&ordenado=Edad_Max&criterio=0">
                                                    Edad Maxima
                                                </a>
                                            </th>										
											<th align='center'>
                                                Modificar
                                            </th>
											<th align='center'>
                                                Eliminar
                                            </th>
										</tr>
										<?php
										}
									else
									   {
											?>
											<tr  id='titulo_listado' style='padding-top:0px;'>
											<th align='center'>
                                                <a class='enlace' href="?pag=gi_grupo_edades.php&ordenado=Nombre_Edad&criterio=1">
                                                    Nombre
                                                </a>
                                            </th>
											<th align='center'>
                                                <a class='enlace' href="?pag=gi_grupo_edades.php&ordenado=Edad_Min&criterio=1">
                                                    Edad Mínima
                                                </a>
                                            </th>
											<th align='center'>
                                                <a class='enlace' href="?pag=gi_grupo_edades.php&ordenado=Edad_Max&criterio=1">
                                                    Edad Máxima
                                                </a>
                                            </th>										
											<th align='center'>
                                                Modificar
                                            </th>
											<th align='center'>
                                                Eliminar
                                            </th>
										</tr>								 
										<?php
									  }			
											?>
											<tbody class="scrollContent">
											<?php

												@$contador=mysql_num_rows($result);
												for ($i=0;$i<$contador;$i++)
													{
														$fila = mysql_fetch_array($result);
														$nombre_edad = trim($fila['Nombre_Edad']);
														$edad_min = $fila['Edad_Min'];
														$edad_max = $fila['Edad_Max'];
                                                        $id_edad = $fila['Id_Edad'];
                                                        
                                                        ?>
														<tr id='texto_listados' onmouseover='resaltar_seleccion(this);'
                                                            onmouseout='desresaltar_seleccion(this);'>
                                                        <td align='center'>
                                                        <? echo $nombre_edad; ?>
                                                        </td>
                                                        <td align='center'>
                                                        <? echo $edad_min; ?>
                                                        </td>
                                                        <td align='center'>
                                                        <? echo $edad_max; ?>
                                                        </td>
														<!-- modificar-->
														<td align='center'>
                                                            <a href='#'
                                                      <?
                                                      echo "onClick='cambiar_opcion(2,".$id_edad.",\"".$nombre_edad."\",".$edad_min.",".$edad_max.")'";
                                                      ?>
                                                            >
                                                                <IMG name='modificar' alt='Modificar Grupo de Edades'
                                                                     src='../imagenes/botones/modificar.gif' border=0>
                                                            </a>
                                                        </td>
														<!-- eliminar -->
														<td align='center'>
                                                            <a href='#'
                                                      <?
                                                      echo "onClick='cambiar_opcion(3,".$id_edad.",\"".$nombre_edad."\",".$edad_min.",".$edad_max.");'";
                                                      ?>
                                                            >
                                                                <IMG name='eliminar' alt='Eliminar Grupo de Edades'
                                                                     src='../imagenes/botones/eliminar.gif' border=0>
                                                            </a>
                                                        </td>
													 </tr>
													 <?php
													}
										?>
									</tbody>
								</thead>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="caja_inferior">
</div>
<?php
@mysql_close($db);
	} //Fin del IF de comprobacion de acceso a la pagina
	else	//Muestro una ventana de error de permisos de acceso a la pagina
		echo "<div class='error'>
		        <br><br><br><br>
					<div style='color:red;'>
							NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
					</div>
			</div>";
?>
