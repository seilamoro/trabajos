<?
SESSION_START();
 @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }

     mysql_select_db($_SESSION['conexion']['db']);
?>

<SCRIPT LANGUAGE="JavaScript">
			  function imprimir() // Función que imprime la página que llama a la función
				  {
					   version = parseInt(navigator.appVersion);
					   if (version >= 4)
						 window.print();
				  }
		</SCRIPT>
		
		
	<link rel="stylesheet" type="text/css" href="../css/hoja_principal_azul.css">
	<link rel="stylesheet" type="text/css" href="../css/estilos_tablas.css">
	<link rel="stylesheet" type="text/css" href="../css/habitaciones.css">
	<link rel="stylesheet" type="text/css" href="../css/hoja_formularios.css">
	<link rel="stylesheet" type="text/css" href="../css/estructura_alb_per.css">
<STYLE type="text/css">
body{
background-color:'white';
}
</STYLE>

        <?//Listado de Ventas de Carnet Reaj
        


         
         



          $qry="SELECT * FROM solicitante WHERE SUBSTRING(Fecha,1,4)='".$_GET['anio']. "'";



                 // Tabla de Listado de Carnets


 ?>				<br><br>
             <table align="center" border="0"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
					<tr id="titulo_tablas">
						<td colspan="7" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
								<div class='champi_izquierda'>&nbsp;</div>
								
								<div class='champi_centro' style="width:670px;"> Listado de Carnets</div>
								</div>
								<div class='champi_derecha'>&nbsp;</div>
						</td>
					</tr>


                    <tr>
                        <td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                           <div id="tableContainer"  style='width: 700px;'>
                                 <table border="0" class="tabla_detalles" style="border: 1px solid #3F7BCC;" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" class="scrollTable" style='width: 730px;'>
									<thead id="titulos" class="fixedHeader" style='position:relative;'>


						            <th align="center" width="11">
                                        
							            Tipo
				                    </th>
						            <th width="100">
                                        
							             Numero
						            </th>
						            <th width="130">
                                        
							            Nombre
						            </th>
						            <th width="190">
                                        
							            Apellidos
						            </th>
						            <th width="85">
                                        
                                        Fecha
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

					            
					                  <tr class="texto_listados" >
						                  <td align="center">
                                              <?print ($tupla['Id_Carnet']);?>
					                      </td>
						                  <td width="100">
							                  <?print ($tupla['Numero']);?>
						                  </td>
		                                  <td width="130" align="left">
				                              <?print ($tupla['Nombre_Solic']);?>
						                  </td>
						                  <td width="190" align="left">
							                  <?print ($tupla['Apellido1_Solic']." ".$tupla['Apellido2_Solic']);?>
						                  </td>
						                  <td align="center">
                                              <?print ($fecha);?>
						                  </td>


						                  
					                  </tr>
				            	<?}?>
                                 </form>

                                    </tbody>
								    </table>
                           </div>




                        </td>
                    </tr>
             </table>
             
             <?
	MYSQL_CLOSE($db); //Cierre base de datos
	
    ?>
	
	<script>
	//Llamada a la función imprimir que imprimirá la página
	 imprimir();
	   </script>
