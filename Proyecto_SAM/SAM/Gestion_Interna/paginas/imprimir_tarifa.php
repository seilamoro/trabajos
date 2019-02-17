<?php
session_start();
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

.champi_izquierda{
	height:30px;
	width:30px;
	background-image:url('../../imagenes/img_tablas/champi_izq_azul.gif');
	background-repeat:no-repeat;
	float:left;
}

.champi_centro{
	height:30px;
	text-align:center;
	color:#FFFFFF;
	background-image:url('../../imagenes/img_tablas/champi_cen_azul.gif');
	background-repeat:repeat-x;
	text-align:center;
	font-family:Verdana;
	font-size:18px;
	font-weight:bold;
	max-height:30px;
	float:left;
}

.champi_derecha{
	height:30px;
	width:30px;
	background-image:url('../../imagenes/img_tablas/champi_der_azul.gif');
	float:left;
}

</style>

<SCRIPT LANGUAGE="JavaScript">
		//Función para que salga automáticamente por pantalla la opción de imprimir
			  function imprimir()
				  {
					   version = parseInt(navigator.appVersion);
					   if (version >= 4)
						 window.print();
				  }
</script>

<link rel="stylesheet" type="text/css" href="../css/hoja_formularios.css">
<link rel="stylesheet" type="text/css" href="../css/habitaciones.css">
<link rel="stylesheet" type="text/css" href="../css/estilos_tablas.css">

<?php
//session_start();

if (isset($_SESSION['permisoUsuarios']) && $_SESSION['permisoUsuarios']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
@ $db=mysql_pconnect("localhost", "root", "");
	if (!$db)
	{
    	echo 'Error. No se puede conectar a la base de datos';
		exit;
	}
mysql_select_db("sam");

?>
<html>
<head>
</head>
<BODY>
<div id="caja_superior">
		<table border="0" align="center">
			<thead>
				<td colspan="9" align="center">
					<div class='champi_izquierda'>&nbsp;</div>
					<div class='champi_centro'>
						<div class="titulo" style="width:510px;text-align:center;font-family:Verdana;font-size:18px;font-weight:bold;color:#F3F3F3;
						max-height:30px;">
						    <!--Ponemos el año actual en el que se utiliza o se visualiza el listado de las tarifas-->
                            Listado de Tarifas <?php echo date("Y");?>
                        </div>
					</div>
					<div class='champi_derecha'>&nbsp;</div>
				</td>
			</thead>
			<tr>
				<td>
					<div style='width:570px;top:-4px;position:relative;'>		
						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable" style='width:570px;'>
							<thead class="fixedHeader" cellspacing="0" width="100%"  class="scrollTable">
								<tbody class="scrollContent">									
									<?php

                                    // obtenemos los tipos de persona
                                    $query="SELECT Id_Tipo_Persona,Nombre_Tipo_Persona FROM tipo_persona ORDER BY Id_Tipo_Persona ASC";
                                    @$res = mysql_query($query);
									@$num_tipo_persona = mysql_num_rows($res);
									
									?>
									<table border='1' width='100%'>
										<tr id='titulo_listado'>
										<th>Edades</th>
										<?
										
                                        for($i=0;$i<$num_tipo_persona;$i++)
										{
                                            
                                            $fila=mysql_fetch_array($res);
                                            if($fila['Nombre_Tipo_Persona']=="Alberguista"){
                                              $fila['Nombre_Tipo_Persona']="Múltiple";
                                            }
											//Creo un array con los id de tipos de persona, para poder colocar las tarifas en donde corresponden
											$cont_tipo_per[$i]=$fila['Id_Tipo_Persona'];	
											
                                            echo "<th>".$fila['Nombre_Tipo_Persona']."</th>";
											
											
                                        }
										
                                        // obtenemos las habitaciones que sean Reservables pero no compartidas, como la triple
                                        $query="SELECT Nombre_Tipo_Hab,Id_Tipo_Hab FROM tipo_habitacion WHERE Reservable='S' AND Compartida='N';";
                                        
                                        @$res = mysql_query($query);
									    @$num_tipo_hab = mysql_num_rows($res);
									
                                        for($i=0;$i<$num_tipo_hab;$i++)
										{
                                            $fila=mysql_fetch_array($res);
                                            echo "<th>".$fila['Nombre_Tipo_Hab']."</th>";									
													
                                        }
										
									   ?>
										</tr>			
										<?php
                                        $query="SELECT Nombre_Edad,Id_Edad from edad";
                                        @$res_edad = mysql_query($query);
									    @$num_edad = mysql_num_rows($res_edad);
										for($i=0;$i<$num_edad;$i++)
											{
                                            echo "<tr>";
											echo "<td align='center' id='texto_listados' >";
											$fila_edad=mysql_fetch_array($res_edad);
											echo $fila_edad['Nombre_Edad']."</td>";
											
											
									       
									        //echo "<tr>";
										    for($j=0;$j<$num_tipo_persona;$j++)
										    {	
												
												$query="SELECT DISTINCT Tarifa,tarifa.Id_Edad,tarifa.Id_Tipo_Persona
                                                    FROM tarifa
                                                   INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                         AND NOT (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
                                                    WHERE Id_Edad=".$fila_edad['Id_Edad']."  and tarifa.Id_Tipo_Persona=".$cont_tipo_per[$j]." ORDER BY tarifa.Id_Tipo_Persona ASC";
           
                                            @$res = mysql_query($query);
									        @$num = mysql_num_rows($res);
												
                                               $fila=mysql_fetch_array($res);
                                               if ($num==0){
												   $precio='0';
											   }
                                               else{
												   $precio = $fila['Tarifa'];
											   }
											 
												echo "<td align='center'id='texto_listados'>".$precio."</td>";											  
											 
											  
                                            }
                                            $query="SELECT DISTINCT Tarifa,tarifa.Id_Edad
                                                    FROM tarifa
                                                    INNER JOIN tipo_habitacion ON tipo_habitacion.Id_Tipo_Hab = tarifa.Id_Tipo_Hab
                                                          AND (tipo_habitacion.Reservable='S' AND tipo_habitacion.Compartida='N')
                                                    WHERE Id_Edad=".$fila_edad['Id_Edad'].";";
                                            @$res = mysql_query($query);
									        @$num = mysql_num_rows($res);
           									for($j=0;$j<$num_tipo_hab;$j++)
											{
											   $fila=mysql_fetch_array($res);
											   if ($num==0) $precio='0';
                                               else $precio = $fila['Tarifa'];
	                                           echo "<td align='center' id='texto_listados'>".$precio."</td>";
                                            }
                                             echo "</tr>";
											}
															
										
											?>												
									</table>
								</tbody>
							</thead>
						</table>
					</div>
				</td>
			</tr>			
		</table>		
	</div>
<script>
	 imprimir();
</script>
</body>
<?php
mysql_close();
	} //Fin del IF de comprobacion de acceso a la pagina
	else	//Muestro una ventana de error de permisos de acceso a la pagina
		  echo "<div class='error'>NO TIENE PERMISO PARA ACCEDER A ESTA PÁGINA</div>";  
?>
</html>
