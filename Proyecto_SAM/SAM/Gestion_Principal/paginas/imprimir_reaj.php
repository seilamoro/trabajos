<? SESSION_START();
?>
<html>
	<head>
		<SCRIPT LANGUAGE="JavaScript">
			  function imprimir() // Función que imprime la página que llama a la función
				  {
					   version = parseInt(navigator.appVersion);
					   if (version >= 4)
						 window.print();
				  }
		</SCRIPT>
		
		
		
		
		
		
		
        <!------------Estilo para que los links no estén subrayados ni cambien de color--------->
        <STYLE type="text/css">
        A:link {text-decoration:none;color:#F3F3F3;}
        A:visited {text-decoration:none;color:#F3F3F3;}
        A:active {text-decoration:none;color:#F3F3F3;}
        A:hover {text-decoration:none;color:#F3F3F3;}
        </STYLE>



    </head>
    
    
    
    
<body>
        <!------------links a los css--------->
		<link rel="stylesheet" type="text/css" href="../css/hoja_formularios.css">
		<link rel="stylesheet" type="text/css" href="../css/habitaciones.css">
		<link rel="stylesheet" type="text/css" href="../css/estilos_tablas.css">
	    <link rel="stylesheet" type="text/css" href="css/hoja_principal.css">



          <?   @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
   			 if(!$db)
			{
    			echo "Error : No se ha podido conectar a la base de datos";
    			exit;
	    	}

     		mysql_select_db($_SESSION['conexion']['db']);
    		 ?>
           
           <!----------------------Tabla que muestra el listado de Tipos Reaj------------------------->
           
	<table border="0"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="390" align="center">
		<tr id="titulo_tablas">
			<td colspan="2" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
				<div style="height:25px;width:30px;background-image:url('../../imagenes/img_tablas/esquina_arriba_izquierda.jpg');background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
				<div style="height:25px;width:300px;text-align:center;background-image:url('../../imagenes/img_tablas/linea_horizontal.jpg');background-repeat:repeat-x;float:left;">
				<div class="titulo" style="width:450px;text-align:center;text-align:center;font-family:Verdana;font-size:18px; font-weight:bold;/*	background-color:#467BAF;*/color:#F3F3F3;max-height:30px;">Listado de Tipos Reaj</div>
				</div>
				<div style="height:25px;width:30px;background-image:url('../../imagenes/img_tablas/esquina_arriba_derecha.jpg');float:left;">&nbsp;</div>
			</td>
		</tr>
		<tr>
		<td align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                   <div  style='width: 400px;height=400px;'>
                   <table style="border: 1px solid #3F7BCC;" id="tabla_detalles" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" width="100%" class="scrollTable" style='width: 510px;' height="">

                   	<thead id="titulos" class="fixedHeader" style='position:relative;'>





                   	 <? //Seleccionar parámetros para crear la query ordenando según los campos  del listado  ascendente o descendentemente


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




                   //Crear los links

                      ?>
					<tr >
						<Th align="center">
                        <A href=<?print('imprimir_reaj.php?short=type'.$lista);?>>
                        Tipo</A>
                        </Th>
						<Th align="center" width="250">
                         <A href=<?print('imprimir_reaj.php?short=nombre'.$lista);?>>
                        Nombre</A> </Th>
                        	<Th align="center">
                        <A href=<?print('imprimir_reaj.php?short=precio'.$lista);?>>
                        Precio<br>
                        (en euros)</A>
                        </Th>
						
                         
					
					</tr>
					</thead>
					<tbody style="border: 1px solid #3F7BCC;"  style='padding-left: 5px;padding-right: 5px; '>

				<?
				
                  // Mostrar los datos
                 $res=mysql_query($qry);
                  if ($res){$filas=mysql_num_rows($res);}
                  for ($i=0;$i<$filas;$i++){
	              $fila=mysql_fetch_array($res);





                ?>





					<tr id="texto_listados"  >
						<TD align='Center'height="30">
                            <?print ($fila['Id_Carnet']);?></td>
     	                 <td align="center" width="200">
                           <?print ($fila['Nombre_Carnet']);?>
                        </td>
						<td align="center">
                             <?print ($fila['Precio_Carnet']);?>
                            </td>
						
					
					</tr>

					<?}?>
					</tbody>

				</table>
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
	</body>
	</html>
