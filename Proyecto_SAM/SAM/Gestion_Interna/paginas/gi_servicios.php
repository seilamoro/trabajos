<?PHP
session_start();

if (isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
?>

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
// función que manda como POST activar_servicio por el formulario form_activar para activar los servicios
function activarServicio()
    {
        document.forms["form_activar"].activar_servicio.value = 1;
        document.forms["form_activar"].submit();
    }

// función que pide confirmación antes de desactivar los servicios
function confirmar()
    {
            if ( confirm("¿Está seguro que desea eliminar todos los servicios ?"))
            {
                // manda como POST desactivar_servicio para desactivar los servicios
                document.forms['form_desactivar'].desactivar_servicio.value = 1;
                document.forms['form_desactivar'].submit();
                return true;

            }
            else
            {
                return false;
            }

    }
// función que avisa de que el servicio no se puede eliminar por ser defecto.
    function defecto_ele()
    {
   	    alert("No se puede eliminar el servicio por defecto");
    }

// Esta función resalta en el listado el servicio que queremos ver.
	function resaltar_seleccion(tr) 
		{
			tr.style.backgroundColor = '#3F7BCC';
			tr.style.color = '#F4FCFF';
			tr.style.cursor = 'pointer';
		}
// Esta función anula la función anterior
	function igualar_seleccion(tr)
		{
			tr.style.backgroundColor = '#F4FCFF';
			tr.style.color = '#3F7BCC';
		}
		
// Esta función muestra una caja y oculta al resto según la el valor de la variable de entrada caja.
    function visualizar_caja(caja)
    {
        if (caja==1)
        {
           document.getElementById('nuevo_servicio').style.display='block';
   	       document.getElementById('modificar_servicio').style.display='none';
           document.getElementById('eliminar_servicio').style.display='none';
        }        
        if (caja==2)
        {
            document.getElementById('nuevo_servicio').style.display='none';
   	        document.getElementById('modificar_servicio').style.display='block';
            document.getElementById('eliminar_servicio').style.display='none';
        }        
        if (caja==3)
        {
            document.getElementById('nuevo_servicio').style.display='none';
   	        document.getElementById('modificar_servicio').style.display='none';
            document.getElementById('eliminar_servicio').style.display='block';
        }      
    }
   
// Función para mostrar nuevo servicio(1), modificar servicio(2) o eliminar servicio(3) sin quitar el listado de servicios de la pantalla
	function cambiar_opcion(op,id_se,des,id_ed,ed,pre,def)
		{
			if (op==1)
				{
					visualizar_caja(1);
				}
			if (op==2)
				{
					visualizar_caja(2);

                    //Mandamos los datos en el formulario.
					document.forms["modifica_servicio"].id_servicios_mod.value = id_se.toUpperCase();
					document.forms["modifica_servicio"].edad_mod.value = ed;
					document.forms["modifica_servicio"].id_edad_mod.value = id_ed;
					
					document.forms["modifica_servicio"].precio_mod_antigua.value = pre;
					document.forms["modifica_servicio"].defecto_mod_antigua.checked = def;
					document.forms["modifica_servicio"].descripcion_mod_antigua.value = des;
					
					document.forms["modifica_servicio"].precio_mod.value = pre;
					document.forms["modifica_servicio"].defecto_mod.checked = def;
					document.forms["modifica_servicio"].descripcion_mod.value = des;
/*
					alert("id ant "+document.forms["modifica_servicio"].id_servicios_mod.value);
					alert("edad ant "+document.forms["modifica_servicio"].edad_mod.value);
					alert("edad ant "+document.forms["modifica_servicio"].id_edad_mod.value);
					alert("precio ant "+document.forms["modifica_servicio"].precio_mod_antigua.value);
					alert("defecto ant "+document.forms["modifica_servicio"].defecto_mod_antigua.checked);
					alert("decripcion ant "+document.forms["modifica_servicio"].descripcion_mod_antigua.value);
*/
					

					/*alert("id nue "+document.forms["modifica_servicio"].id_mod.value);
					alert("edad nue "+document.forms["modifica_servicio"].edad_mod.value);
					alert("precio nue "+document.forms["modifica_servicio"].precio_mod.value);
					alert("defecto nue "+document.forms["modifica_servicio"].defecto_mod.checked);
					alert("decripcion nue "+document.forms["modifica_servicio"].descripcion_mod.value);*/
				}
			if (op==3)
				{
					visualizar_caja(3);
					
					//Mandamos los datos en el formulario.
					document.forms["elimina_servicio"].id_eli.value = id_se.toUpperCase();
					document.forms["elimina_servicio"].descripcion_eli.value = des;
					document.forms["elimina_servicio"].precio_eli.value = pre;				
				}
			}
//terminan las funciones.
</script>
<br><br>
<?php
        // Devuelve el servicio por defecto de las tablas pernocta,pernocta_p y estancia_gr.
        function servicio_defecto($db2)
            {
                $query_pernocta = "SELECT column_default FROM information_schema.columns
                                   WHERE table_schema='sam'
                                   AND table_name='pernocta'
                                   AND column_name='Id_Servicios';";
                @$res_default = mysql_query($query_pernocta,$db2);
                if (!$res_default) echo mysql_errno()." Error: ".mysql_error().$query_pernocta."<BR>";
                @$fila_default = mysql_fetch_array($res_default);
                return $fila_default['column_default'];
            }

        // Devuelve un booleano indicando si existe la tabla <<$table_name>> en la base de datos <<$base_datos>>.
        function table_exists($table_name,$base_datos)
        {
            $Table = mysql_query("show tables like '" .$table_name . "'",$base_datos);
            if(mysql_fetch_row($Table) == false)
            return(false);
            else
            return(true);
         }
        // Cambia el valor de las tablas pernocta, pernocta_p y estancia_gr por el valor de nuevo_default.
        function cambiar_default($nuevo_default,$db)
            {

                $query = "BEGIN";
                    $res = mysql_query($query,$db);
                $query = " ALTER TABLE pernocta MODIFY Id_Servicios varchar(2) default '".$nuevo_default."' ;";
                    $res = mysql_query($query,$db);
                if (!$res) echo mysql_errno()." Error: ".mysql_error().$query."<BR>";

                $query = " ALTER TABLE pernocta_p MODIFY Id_Servicios varchar(2) default '".$nuevo_default."' ;";
                    $res = mysql_query($query,$db);
                if (!$res) echo mysql_errno()." Error: ".mysql_error().$query."<BR>";

                $query = " ALTER TABLE estancia_gr MODIFY Id_Servicios varchar(2) default '".$nuevo_default."' ;";
                    $res = mysql_query($query,$db);
                if (!$res) echo mysql_errno()." Error: ".mysql_error().$query."<BR>";
                $query = "COMMIT";
                    $res = mysql_query($query,$db);

            }
// Cuidado con el orden!!.
@$db2 = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
@$db  = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);

// Base de datos con metadata de todas las bases de datos, de aqui obtendremos el default de algunas tablas.
@mysql_select_db("information_schema",$db2);

@mysql_select_db($_SESSION['conexion']['db']);

//@mysql_query("SET NAMES utf8",$db);
//@mysql_query('SET CHARACTER_SET utf8');

// Comprueba que si se ha conectado correctamente con las bases de datos.
if ((!$db)||(!$db2))
{
    echo '<div class="label_formulario"><br><br><br>Error. No se puede conectar a la base de datos</div>';
    exit;
}
else
{
// si está definido activar_servicio, activamos los servicios, ejecutando los procedures almacenados en la base datos
if (isset($_POST['activar_servicio']))
                         {
                           $query = "BEGIN;";
                           @$res = mysql_query($query,$db);
                           $query="call add_table_tipo_servicios();";
                           @$res = mysql_query($query,$db);
                           $query="call add_table_servicios();";
                           @$res = mysql_query($query,$db);
                           // por problemas con utf8 con mysql debemos introducir los acentos
                           $query="update tipo_servicios set Descripcion='Sólo alojamiento' where Id_Servicios='sa';";
                           @$res = mysql_query($query,$db);
                           $query="call add_columns();";
                           @$res = mysql_query($query,$db);
                           //$query="call add_indexs();";
                           //@$res = mysql_query($query,$db);
                           $query="call add_columns();";
                           @$res = mysql_query($query,$db);
                           $query="call add_constraints();";
                           /*
                           $query = "ALTER TABLE pernocta ADD CONSTRAINT pernocta_s FOREIGN KEY (Id_Servicios)
                                   REFERENCES tipo_servicios(Id_Servicios) ON DELETE SET NULL ON UPDATE CASCADE;";
                           @$res = mysql_query($query,$db);
                           $query = "ALTER TABLE pernocta_p ADD CONSTRAINT pernocta_p_s FOREIGN KEY (Id_Servicios)
                                   REFERENCES tipo_servicios(Id_Servicios) ON DELETE SET NULL ON UPDATE CASCADE;";
                           @$res = mysql_query($query,$db);
                           $query = "ALTER TABLE estancia_gr ADD CONSTRAINT estancia_gr_s FOREIGN KEY (Id_Servicios)
                                   REFERENCES tipo_servicios(Id_Servicios) ON DELETE SET NULL ON UPDATE CASCADE;";
                           */
                           @$res = mysql_query($query,$db);
                           $query = "COMMIT;";
                           @$res = mysql_query($query,$db);

                           if (!$res) echo mysql_errno()." Error: ".mysql_error().$query_pernocta."<BR>";

                        }
                        
// Si está definido desactivar_servicio, activamos los servicios, ejecutando los procedures almacenados en la base datos
if (isset($_POST['desactivar_servicio']))
                         {
                           // comienzo de la transacción

                           $query="BEGIN;";
                           @$res = mysql_query($query,$db);

                           $query="call drop_foreings();";
                           @$res = mysql_query($query,$db);

                           //$query="call drop_indexs();";
                           //@$res = mysql_query($query,$db);
                           
                           $query="call drop_columns();";
                           @$res = mysql_query($query,$db);
                           
                           $query ='DROP TABLE servicios;';
                           @$res = mysql_query($query,$db);
                           
                           $query ='DROP TABLE tipo_servicios;';
                           @$res = mysql_query($query,$db);
                           
                           //if (!$res) echo mysql_errno()." Error: ".mysql_error().$query_pernocta."<BR>";

                           $query="COMMIT;";
                           @$res = mysql_query($query,$db);
                        }
                        
// Variable booleana que almacena si la tabla servicios existe
$servicios_activados = table_exists(servicios,$db);

// Si no existe la tabla servicios...
if (!$servicios_activados)
        {
          ?>
          <form action='#' method='POST' name='form_activar'>
          <p style='margin-left:750px;margin-right:50px;'>
          <table>
          <tr>
            <td> <!-- Botón Edades -->
                <a href='principal_gi.php?pag=gi_grupo_edades.php'>
                <img src='../imagenes/botones-texto/edades.jpg' border='0' alt='Abre la página de grupo edades'></a>
            </td>
            <td> <!-- Botón Tarifas -->
                <a href='principal_gi.php?pag=gi_tarifas.php'>
                <img src='../imagenes/botones-texto/tarifas.jpg' border='0' alt='Abre la página de tarifas'></a>
            </td>
            <td > <!-- Botón Activar Servicios -->
                 <input type='hidden' name='activar_servicio'>
                 <a href='#'>
                 <img src='../imagenes/botones-texto/activar_servicios.jpg' border='0' alt='Activa los servicios' onClick='activarServicio();'>
                 </a>
            </td>
          </tr>
            
          </table>
          </p>
          
          <br><br>
          <p class="label_formulario">No hay servicios activados</p>
          </form>
          
      
          <?php
          
        }
else
// Si existe la tabla servicios...
{

     ?>

   
<form action='#' method="POST" name="form_desactivar">
<p style='margin-left:750px;margin-right:50px;'>
<table  >
    <tr>
        <td> <!-- Botón Edades -->
            <a href='principal_gi.php?pag=gi_grupo_edades.php'>
            <img src='../imagenes/botones-texto/edades.jpg' border='0' alt='Abre la página de grupo edades'></a>
        </td>
        <td> <!-- Botón Tarifas -->
            <a href='principal_gi.php?pag=gi_tarifas.php'>
            <img src='../imagenes/botones-texto/tarifas.jpg' border='0' alt='Abre la página de tarifas'></a>
        </td>
        <td> <!-- Botón Activar Servicios -->
               <input type='hidden' name='desactivar_servicio'>
               <a href='#'>
               <img src='../imagenes/botones-texto/desactivar_servicios.jpg' border='0' alt='Borra todos los servicios' onClick='confirmar();'> </a>
        </td>
        
    </tr>
</table>
</p>
 </form>



<div id="caja_superior" style="width:100%;height:auto;">
<!-- Caja mostrada por defecto-->
    <div id="caja_superior_izquierda" style='margin-left:30px;float:left;margin-bottom:50px;margin-top:0px;width:350px;padding:0px 0px 0px 0px;'>

	<!-- Empieza crear un nuevo servicio -->
        <div id='nuevo_servicio' style='margin-left:0px;display:block;'>
            <table border='0' id='tabla_detalles' width='100%' cellspacing='0' cellpadding='0' style='padding 0px 0px 0px 0px;'>
                <!--  Formulario nuevo_servicio   -->
				<form name= "nuevo_servicio" action='#' method='POST'>
					<thead >
                        <!-- Sombrero nuevo servicio -->
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'>
                            </div>
							<div class='champi_centro' style="width:284px;">
							     <div class="titulo" style="width:270px;text-align:center;">
                                    Nuevo Servicio
                                </div>
							</div>
							<div class='champi_derecha'>
                            </div>
						</td>
					</thead>
					<?php

						$id_servicio = strtolower(trim($_POST['id_servicio']));
						$descripcion = trim($_POST['descripcion']);
						$defecto = $_POST['defecto'];

						$num_precio = $_POST['num_precios'];					
						$numero_edades=$_POST['num_edades'];
						
						// Variable para detectar errores en la consultas a la base de datos.
						$error = false;
						
						// Comprobamos que el precio es un valor númerico, si no ha sido recibido aún el formulario, aquí no entrará.
						if ( is_numeric($num_precio) AND  $id_servicio!="" )
						{
                         
	                        @$res = mysql_query("BEGIN",$db);
                            // insertarmos primero el tipo de servicios
                            $query="INSERT INTO tipo_servicios (Id_Servicios,Descripcion) VALUES ('".$id_servicio."','".$descripcion."' );";
	                        @$res = mysql_query($query,$db);
	                        
	                        $query = "SELECT * from edad";
	                        @$res = mysql_query($query,$db);
	                        $num_precios = mysql_num_rows($res);
	                        
				            for($i=0;$i<$num_precios;$i++)
                               {
	                                // insertamos el servicio
									$query="INSERT INTO servicios (Id_Servicios, Id_Edad,Precio)
                                            VALUES ('".$id_servicio."','".$_POST['edades'.$i]."','".$_POST['precio'.$i]."' );";
									@$res = mysql_query($query,$db);
									//if (!$res) echo $query;
								}
                            
                            // Si está seleccionado el checkbox modificamos el servicio como defecto.
                            if ($defecto=='on')
                                    cambiar_default($id_servicio,$db);
                                    
    						@$res = mysql_query("COMMIT",$db);
    						// En caso de no haber errores en la consulta al crear el servicio...
   							if($res)
								{
                                ?>
    <!-- Se añade correctamente el nuevo servicio-->
    <script>
        visualizar_caja(1);
    </script>

	<tr style='padding-top:0px;'>
	   <td>
	   <table border='0'  height='120' width='99.5%' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;padding 0px 0px 0px 0px;'>
            <tr style='padding-top:0px;'>
                <td align='center' class='label_formulario'>
                <br>
                    Se ha añadido el nuevo servicio a la base de datos.
                <br>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    <a href='?pag=gi_servicios.php'><img src='../imagenes/botones-texto/aceptar.jpg' border=0></a>
                    <br>
                </td>
            </tr>
        </table>
        </td>
    </tr>	
                                <?php
								}
								// en caso de haber errores en la consulta al crear servicio...
 								else
									{
                               ?>
     <!-- ha ocurrido un error al crear el servicio -->
	 <script>
        visualizar_caja(1);
    </script>
    <tr style='padding-top:0px;'>
	   <td style='padding 0px 0px 0px 0px;'>
	   <table border='0'  height='120' width='99.5%' cellspacing='0' cellpadding='0' style="padding 0px 0px 0px 0px;border: 1px solid #3F7BCC;">
            <tr style='padding-top:0px;'>
                <td align='center' class="label_formulario">
                <br>
                    No se ha podido crear el servicio, inténtelo más tarde.
                <br>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    <a href='?pag=gi_servicios.php'><img src='../imagenes/botones-texto/aceptar.jpg' border=0></a>
                <br>
                </td>
            </tr>
        </table>
        </td>
    </tr>
							<?php
									}
                         }
                        // En caso de no haber introducido aún el nuevo servicio...
                        else
                         {
					?>	
    <!-- Se recogen los datos del nuevo servicio para mandarlos en el formulario -->
					<tr style='padding-top:0px;' >
						<td>
							<table border='0' width='99.5%' height='210px' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;'>
								<tr height='25'>
									<td>&nbsp;</td><td align='left' class='label_formulario'>Servicio:</td>
									<td align='left'>
                                        <input type=text  name='id_servicio' class='input_formulario' MAXLENGTH=2 size=2
                                            onBlur="this.value=this.value.toUpperCase()" >
									</td>
								</tr>
								<tr height='25'>
									<td>&nbsp;</td><td align='left' class='label_formulario'>Descripción:</td>
									<td>
                                         <input type=text name='descripcion' class='input_formulario'>
									</td>
								</tr>								
								<tr height='25' >
								<td></td>
									<td colspan='2' class='label_formulario'>Precio por Edad:
										<table border='0' width='95%' bordercolor='#3F7BCC'>
           									<tr id='texto listado' align='left'>
													<?php
														$sql = "SELECT * FROM edad;";
														$result = MYSQL_QUERY($sql);
														$contador=mysql_num_rows($result);								
														for ($i=0;$i<$contador;$i++)
															{
																$columna=mysql_fetch_array($result);
																echo "<td>&nbsp;&nbsp;</td><td align='left' class ='label_formulario'><input type='hidden' name='edades".$i."' value=".$columna['Id_Edad'].">".$columna['Nombre_Edad']."</td>";
																echo "<td class ='label_formulario'><input type='text' name='precio".$i."' class='input_formulario' size=3> (en Euros)</td></tr>";
															}
													?>	
													<input type='hidden' name='num_precios' value="<?php echo $i;?>">	
													<input type='hidden' name='num_edades' value="<?php echo $contador;?>">
												</td>
											</tr>
										</table>
									</td>									
								</tr>
								<tr height='25'>
									<td>&nbsp;</td><td align='left' class='label_formulario'>Defecto:</td>
									<td>
                                        <input type='checkbox' name='defecto'>
                                    </td>
								</tr>
								<tr>
									<td colspan='9' align='center' height='65'>
									<a href='#'>
                                    <img name='aceptar' src='../imagenes/botones-texto/aceptar.jpg' border='0'
                                         onclick='document.forms["nuevo_servicio"].submit();' alt='Dar de alta el nuevo servicio'>
                                    </td>
								</tr>
							</table>
					</tr>
					<?php
						}
					?>
				</form> <!-- Fin formulario nuevo_servicio-->
            </table>			
        </div>
		<!--  Acaba la creación de un nuevo servicio-->

		<!-- Comienza modificar servicio -->
		<div id='modificar_servicio' style='display:none;'>
			<table border='0' id='tabla_detalles' width='100%' cellspacing='0' cellpadding='0' style='padding 0px 0px 0px 0px;'>
             <!--  Formulario modifica_servicio -->
				<form name= "modifica_servicio" action='#' method='POST'>
					<thead >
                        <!-- Sombrero nuevo servicio -->
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'>
                            </div>
							<div class='champi_centro' style="width:284px;">
							     <div class="titulo" style="width:280px;text-align:center;">
                                    Modificar Servicio
                                </div>
							</div>
							<div class='champi_derecha'>
                            </div>
						</td>
					</thead>
					<?php
								 //Son los nuevos datos
								 $precio_mod_nueva=$_REQUEST['precio_mod'];
								 $descripcion_mod_nueva=$_REQUEST['descripcion_mod'];
								 $defecto_mod_nueva=$_REQUEST['defecto_mod'];							 

								 //Son los hidden
								 // el defecto para la BBDD debe estar en minusculas.
								 $id_servicios_mod=strtolower(trim($_REQUEST['id_servicios_mod'])); 
								 $precio_mod_antigua=$_REQUEST['precio_mod_antigua'];
								 $descripcion_mod_antigua=$_REQUEST['descripcion_mod_antigua'];
								 $edad_mod=$_REQUEST['edad_mod'];
								 $id_edad_mod=$_REQUEST['id_edad_mod'];
								 $defecto_mod=$_REQUEST['defecto_mod_antigua'];

						         // En caso de estar definido id_mod y tener un id válido.
                                 if (isset($_POST['id_servicios_mod']) && $_POST['id_servicios_mod']!="")
			                     {
                              
                                 //Son los input hidden q llevan los datos antiguos.
             					 if (is_numeric($precio_mod_nueva) )
                                 {

                                   @$res = mysql_query("BEGIN",$db);
                                   // modififación de tipo_servicios
                                   $query = "UPDATE tipo_servicios SET descripcion ='".$descripcion_mod_nueva."'
                                                  WHERE Id_Servicios = '".$id_servicios_mod."';";
                                   @$res = mysql_query($query,$db);
                               
                                   $query = "UPDATE servicios SET Precio = ".$precio_mod_nueva.", Id_Edad=".$id_edad_mod."
                                             WHERE Id_Servicios = '".$id_servicios_mod."' AND Id_Edad=".$id_edad_mod.";";
                                   @$res = mysql_query($query,$db);
                                   

                                    // Comprobamos si ha cambiado el servicio por defecto, en caso afirmativo lo cambiamos.
                                    if ( ($defecto_mod_nueva=='on') and ($defecto_mod_antigua!='on') )
                                        cambiar_default($id_servicios_mod,$db);
                                        
                                    @$res = mysql_query("COMMIT",$db);
                                  }
                              // Si no ha habido errores en la consulta de moficiar servicio...
                              if ($res)
									{
                                      
                                ?>
    <!-- Se ha modificado con exito -->
    <script>
        visualizar_caja(2);
    </script>
    <tr style='padding-top:0px;'>
        <td>
            <table border='0' width='99.5%' height='120px' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;'>
            <tr>
                <td align='center'class="label_formulario">
                <br>
                    Se ha modificado el servicio en la base de datos
                <br>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    <a href='?pag=gi_servicios.php'><img src='../imagenes/botones-texto/aceptar.jpg' border=0></a>
                    <br>
                </td>
            </tr>
            </table>
        </td>
    </tr>
	                           <?php
									}	
                                // Si ha habido errores en la consulta al modificar el servicio...
								else
									{
                               ?>
     <!-- ha ocurrido un error al modificar el servicio -->
	 <script>
     visualizar_caja(2);
    </script>
    <tr style='padding-top:0px;'>
        <td>
	    <table border='0' width='99.5%' height='120px' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;'>
            <tr>
                <td align='center'class="label_formulario">
                    <br>
                        No se ha podido modificar el servicio, inténtelo más tarde.
                    <br>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    <a href='?pag=gi_servicios.php'><img src='../imagenes/botones-texto/aceptar.jpg' border=0></a>
                <br>
                </td>
            </tr>
        </table>
        </td>
    </tr>
                               <?php
									}
							}
      	                    // En caso de no haber introducido aún la modificación del servicio...
                            else
                            {
                              
					?>
	<!-- Recogemos las modificaciones -->
					<tr style='padding-top:0px;'>
						<td>
							<table border='0' width='99.5%' height='210px' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;'>
								<tr height='25px'>
									<td>&nbsp;</td>
                                    <td align='left' class='label_formulario'>Servicio:</td>
									<td>
										 <input type=text  name='id_servicios_mod' class='input_formulario' size=2 readonly >
									</td>
								</tr> 
								<tr height='25px'>
									<td>&nbsp;</td>
                                    <td align='left' class='label_formulario'>Descripción</td>
									<td>
									    <input type=text name='descripcion_mod' class='input_formulario'>
                                        <input type='hidden' name='descripcion_mod_antigua'>
									</td>
								</tr>
								<tr height='25'>
									<td>&nbsp;</td><td align='left' class='label_formulario'>Edad:</td>
									<td>
									    <input type=text name='edad_mod' class='input_formulario' readonly>
       			                        <input type='hidden' name='id_edad_mod'>
									</td>
								</tr>
								<tr height='25px'>
									<td>&nbsp;</td>
                                    <td align='left' class='label_formulario'>Precio</td>
									<td class='label_formulario'>
								  	    <input type='text' name='precio_mod' class='input_formulario' size=3 >
								  	    (en Euros)
                                        <input type='hidden' name='precio_mod_antigua'>
                                    </td>
									</td>
								</tr>
								<tr height='25px'>
										<td>&nbsp;</td>
                                        <td align='left' class='label_formulario'>Defecto</td>
										<td align='left'>
                                            <input type='checkbox' name='defecto_mod' <?echo $defecto_mod?> >
                                            <input type='hidden' name='defecto_mod_antigua' <?echo $defecto_mod?> >
                                        </td>
								</tr>							       	
								<tr height='65px'>
									<td>&nbsp;</td>
									<td align='center' height='50px'>
									    <a href='#'>
                                        <img name='aceptar' src='../imagenes/botones-texto/aceptar.jpg' border=0
                                             onclick='document.forms["modifica_servicio"].submit();' alt='Modificar el servicio'>
                                    </td>
									<td align='center' height='50'>
                                        <a href='principal_gi.php?pag=gi_servicios.php'>
                                        <img name='cancelar'
                                        src='../imagenes/botones-texto/cancelar.jpg' border=0 >
                                    </td>
								</tr>
							</table>
						</td>
					</tr>		
					<?php
							}
					?>
				</form><!-- Fin formulario modifica_servicio-->
			</table>
		</div>	
		<!-- Acaba modificar servicio-->

		<!--  Empieza eleminar servicio-->
		<div id='eliminar_servicio' style='display:none;'>
			<table border='0' id='tabla_detalles' width='100%' cellspacing='0' y cellpadding='0' style='padding 0px 0px 0px 0px;' >
				<form name="elimina_servicio" action="#" method="POST">
					<thead>
                        <!--Sombrero eliminar servicio-->
						<td colspan='9' align='center' style='padding-bottom:0px'>
							<div class='champi_izquierda'>
                            </div>
							<div class='champi_centro' style="width:284px;">
								  <div class="titulo" style="width:280px;text-align:center;">
                                      Eliminar Servicio
                                  </div>
							 </div>
							<div class='champi_derecha'>
                             </div>
						 </td>
					</thead>
					<?php 
					// En caso de estar definido id_eli y tener un id válido.
					if(isset($_POST['id_eli']) && $_POST['id_eli']!="")
						{
							$id_servicios=$_POST['id_eli'];
							$edad_eli=$_POST['edad_eli'];

                            $query="delete from servicios where Id_Servicios='".$id_servicios."';";
							
                            @$res = mysql_query($query,$db);
                            
                            // Si la consulta eliminar servicio se ha realizado con exito...
                            if($res)
        	                {
                                   ?>
    <!-- Se ha borrado con exito -->
    <script>
       visualizar_caja(3);
    </script>
 
    <tr style='padding-top:0px;'>
        <td>
        <table border='0' height='120' width='101%' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;' >
        <tr align='center' class="label_formulario">
            <td>
                <br>
                    Se ha borrado el servicio de la base de datos.
                <br>
            </td>
        </tr>
    	<tr class='label_formulario'>
            <td align='center'><a href='?pag=gi_servicios.php'>
                <img src='../imagenes/botones-texto/aceptar.jpg' border=0></a>
            <br>
            </td>
        </tr>
        </table>
        <td>
    </tr>
    
					                <?php
								}
								// Si ha ocurrido un error en la consulta eliminar servicio...
								else
									{
                                    ?>
    <!-- ha ocurrido un error al borrar el servicio -->
    <script>
      visualizar_caja(3);
    </script>
    
    <tr style='padding-top:0px;'>
        <td>
	    <table border='0' height='120' width='99.5%' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;' >
            <tr align='center' class="label_formulario">
                <td>
                <br>
                    No se ha podido borrar el servicio, inténtelo más tarde.
                <br>
                </td>
            </tr>
    	    <tr class='label_formulario'>
                <td align='center'><a href='?pag=gi_servicios.php'>
                <img src='../imagenes/botones-texto/aceptar.jpg' border=0></a>
                <br>
                </td>
            </tr>
        </table>
        </td>
    </tr>
			
                                    <?php
									} 
							}
							// En caso de no haber introducido aún la modificación del servicio...
                            else
                            {
					?>
   <!-- Esperamos que confirme la eliminación del servicio -->
			         <tr style='padding-top:0px;'>
						<td>	 
							<table border='0' width='99.5%' height='210px' cellspacing='0' cellpadding='0' style='border: 1px solid #3F7BCC;'>
								<tr height='25px'>
                                    <td>&nbsp;</td>
									<td align='left' class='label_formulario'>Servicio:</td>
									<td align='left'>
                                    <input type='text'  name='id_eli' class='input_formulario' id='texto_detalles' style="border:none;" readonly>
                                    </td>
								</tr> 								
								<tr height='25px'>
                                    <td>&nbsp;</td>
									<td align='left' class='label_formulario'>Descripción:</td>
									<td align='left'>
                                    <input type='text' class='input_formulario' name='descripcion_eli' id='texto_detalles'
                                        style="border:none;" readonly>
                                    </td>
								</tr>									
								<tr height='25px'>
									<td>&nbsp;</td>
								</tr>
								<tr height='25px'>
									<td colspan='9' class='label_formulario' style='text-align:center;font-size:13px;margin-left:2px;'> ¿Está seguro de que desea eliminar el servicio?</td>
								<tr height='25px'>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr height='45px'>
								    <td>&nbsp;</td>
									<td align='center'>
									<a href='#'>
                                        <img name='aceptar' src='../imagenes/botones-texto/aceptar.jpg'
                                            onClick='document.forms["elimina_servicio"].submit();' border='0' alt='Eliminar el servicio seleccionado'>
                                    </td>
									<td align='center' height='50'>
                                    <a href='principal_gi.php?pag=gi_servicios.php'><img  src='../imagenes/botones-texto/cancelar.jpg' border='0' name ='cancelar' >
                                    </td>
                            	</tr>
							</table>
						</td>
					</tr>
			</form>
			         <?php
			             	}
			         ?>
		</table>		
	</div> <!-- eliminar -->
	
		<!-- Acaba la eliminación de un servicio -->
		
</div> <!-- Fin Caja_superior_izquierda- -->

		<!--  Empieza el listado de los servicios-->
    <div id="caja_superior_derecha_b" style='border:float:right;margin-bottom:50px;margin-top:0px;width:400px;padding:0px 0px 0px 0px;'>
	<div id='listado_servicios' style='display:block;'>
		<table border="0" id="tabla_detalles" align="center" cellspacing=0 y cellpadding=0 style='padding 0px 0px 0px 0px;'>
			<thead>
				<td colspan="9" align="center">
				    
					<div class='champi_izquierda'>&nbsp;</div>
					<div class='champi_centro'>
                        <!-- Sombrero listado servicios  -->
						<div class="titulo" style="width:651px;text-align:center;">Listado de Servicios</div>
					</div>
					<div class='champi_derecha'>
					&nbsp;</div>
				</td>
			</thead>
			<tr>
				<td>
					<div id="tableContainer" class="tableContainer" style='width:710px;top:-4px;position:relative;height:370px;'>
						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable"
                               style='padding 0px 0px 0px 0px;width:690px;' height="">

                            <thead class="fixedHeader" cellspacing="0" width="100%"  class="scrollTable">
									<?php
										$Id_Servicios=$_POST['Id_Servicios'];
										$Descripcion=$_POST['Descripcion'];
										$Precio=$_POST['Precio'];
										$Defecto=$_POST['Defecto'];

										
										// Variable booleana que almacena el servicio por defecto.
										$servicio_defecto = servicio_defecto($db2);
										
										// Recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc.
										if($_GET['ordena']=="Id_Servicios"||$_GET['ordena']=="Descripcion"||$_GET['ordena']=="Id_Edad"||$_GET['ordena']=="Precio")
                                        {
										    if(!$_SESSION['ordena'])
                                            {
                                                  $_SESSION['ordena']=1;
											}
											if($_SESSION['ordena']==1)
											{
						                          $ordenado=" order by ".$_GET['ordena']." DESC";
						                          $_SESSION['ordena']=2;
											}
											else
											{
											     $ordenado=" order by ".$_GET['ordena']." ASC";
											     $_SESSION['ordena']=1;
											}
										}
										// Caso especial servicio por defecto.
                                        if ($_GET['ordena']=="Defecto")
                                        {
                                            
                                            if(!$_SESSION['ordena'])
                                            {
                                                $_SESSION['ordena']=1;
				                            }
										    if($_SESSION['ordena']==1)
										    {
						                      $ordenado=" order by servicios.Id_Servicios='".$servicio_defecto."' DESC";
						                      $_SESSION['ordena']=2;
										    }
										    else
										    {
											 $ordenado=" order by servicios.Id_Servicios='".$servicio_defecto."' ASC";
                                             $_SESSION['ordena']=1;
										    }

                                        }?>
									<tr  id='titulo_listado' class="fixedHeader" style='position:relative;'>
                                       <!-- Formulario de listar servicios modifica_default -->
                                       <form action="principal_gi.php?pag=gi_servicios.php" name="modifica_default" method="post" >
    									
										<th align='center'>
                                            <a class='enlace' href="?pag=gi_servicios.php&ordena=Id_Servicios">Servicios</a>
                                        </th>
										<th align='center'>
                                            <a class='enlace' href="?pag=gi_servicios.php&ordena=Descripcion">Descripción</a>
                                        </th>
										<th>
											<a class='enlace' href='?pag=gi_servicios.php&ordena=Id_Edad'>Edades</a>
										</th>
										<th align='center'>
                                            <a class='enlace' href="?pag=gi_servicios.php&ordena=Precio">Precio (en Euros)</a>
                                        </th>
										<th align='center'>
                                            <a class='enlace' href="?pag=gi_servicios.php&ordena=Defecto">Defecto</a>
                                        </th>
										<th align='center'>
                                            Modificar
                                        </th>
										<th align='center'>
                                            Eliminar
                                        </th>
                                    </tr>
                         </thead>
                         <tbody class="scrollContent"
                                style='border-top:1px solid #CCC;border-bottom:1px solid #CCC;padding-left: 5px;padding-right: 5px; '>
										<?php
                                        // Consulta a la base de datos para listar la tabla servicios ordenado según el criterio seleccionado por el usuario en la sesión.
										$query  = "select servicios.Id_Servicios, Descripcion, Precio , Nombre_Edad , servicios.Id_Edad from servicios
										          INNER JOIN tipo_servicios ON tipo_servicios.Id_Servicios = servicios.Id_Servicios
										          INNER JOIN edad ON edad.Id_Edad = servicios.Id_Edad ".$ordenado.";";
										$result = mysql_query($query,$db);
										if (!$result) echo mysql_errno()." Error: ".mysql_error().$query."<BR>";
										@$contador = mysql_num_rows($result);
											for ($i=0;$i<$contador;$i++)
												{
													$columna = mysql_fetch_array($result);
													$Id_Servicios = trim($columna['Id_Servicios']);
													$Id_Servicios2=strtoupper($Id_Servicios);
													$Precio = trim($columna['Precio']);
													$Descripcion = trim($columna['Descripcion']);
													$Edad = $columna['Nombre_Edad'];
													$Id_Edad = $columna['Id_Edad'];
													if  ($servicio_defecto==$Id_Servicios) $servicio_def='on';
                                                    else $servicio_def='';
                                                    
													
													?>
                         <tr id='texto_listados' onmouseover='resaltar_seleccion(this);' onmouseout='igualar_seleccion(this);'>
                            <td align='center' name='Id_Servicios'><?echo $Id_Servicios2;?>
                            </td>
                            <td align='center' name='Descripcion'><?echo $Descripcion;?>
                            </td>
							<td align='center' name='Edades'><? echo $Edad;?>
							</td>
                            <td align='center' name='Precio'><? echo $Precio;?>
                            </td>
                            <td align='center' name='default'>
                                <input type='hidden' name='Defecto_id' value='0' >
                                <input type='checkbox' name='Defecto' <?if ($servicio_def=='on') echo 'checked';?> disabled >
                            </td>
                            <td align='center'>
                                <a href='#' onClick='cambiar_opcion(<?echo "2,\"".$Id_Servicios."\",\"".$Descripcion."\",\"".$Id_Edad."\",\"".$Edad."\",".$Precio.",\"".$servicio_def."\"";?>);'>
                                <IMG name='modificar' alt='Modificar el servicio' src='../imagenes/botones/modificar.gif' border=0>
                                </a>
                            </td>
                            <td align='center'>
                                <a href='#' <?php
                                    if (!$servicio_def)
                                      echo "onClick='cambiar_opcion(3,\"".$Id_Servicios."\",\"".$Descripcion."\",\"".$Id_Edad."\",\"".$Edad."\",".$Precio.",\"".$servicio_def."\");'";
                                    else
                                      echo "onClick='defecto_ele();'";?>
                                >
                                    <IMG name='eliminar' alt='Eliminar el servicio' src='../imagenes/botones/eliminar.gif' border=0>
                                </a>
                            </td>
                        </tr>
				<?php
												}										
									?>
									</form> <!-- Fin del formulario del listado, modifica_default -->
				             </tbody>
						</table>						
					</div> <!-- Fin tableContainer -->
				</td>
			</tr>
			</table>		
        </div> <!-- Fin listado_grupo_edades -->        
    </div> <!--Fin caja_superior_derecha_b-->
</div>
<div id="caja_inferior">
</div>
<?php
} // fin del else en caso de existir servicios
} // fin del else en caso de fallo al conectar con una de las bases de datos

// Cerramos las conexiones con las bases de datos.
@mysql_close($db2);
@mysql_close($db);

 }//Fin del IF de comprobacion de acceso a la pagina
	else	//Muestro una ventana de error de permisos de acceso a la pagina
		 echo "<div class='error'>
		        <br><br><br><br>
					<div style='color:red;'>
							NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
					</div>
			</div>";

?>
