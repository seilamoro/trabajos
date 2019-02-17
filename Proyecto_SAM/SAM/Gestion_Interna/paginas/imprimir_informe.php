<?PHP session_start();?>
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
	
    </head>
 
    
<body>           
	<table border="0"  cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="390" align="center">
		<tr>
		<td align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                   <div id="tableContainer" class="tableContainer" style='width: 390px;height=400px;'>
                   <table border="0" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" width="100%" class="scrollTable" style='width: 490px;' height="">

                   	<thead id="titulos" class="fixedHeader" style='position:relative;'></thead>
					<tbody class="scrollContentr" id="tabla_detalles" style='border-top:1px solid #CCC;border-bottom:1px solid #CCC;padding-left: 5px;padding-right: 5px; '>

					<tr id="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);" >
					<?	
						//Inicio CONEXION con la BD
						@$db  = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
						if(!$db){
							echo "Error : No se ha podido conectar a la base de datos";
					    	exit;
					    }
					    mysql_select_db($_SESSION['conexion']['db'],$db);	
						/*Inicio SQL consulta cantidad de Registros*/ 	
					
						$sql_albergue="SELECT * FROM albergue";
						$resul_alb = mysql_query($sql_albergue);
					    $fila_albergue = mysql_fetch_array($resul_alb);
						$tipo_registro_alb=substr(trim($fila_albergue['Tipo_registro_Alb']),0, 1);//tipo de registro de los datos del albergue
						$nombre_establecimiento=substr(trim($fila_albergue['Nombre_Alb']),0, 40);
						$codigo_establecimiento=substr(trim($fila_albergue['Cod_Establecimiento']),0, 10);
						/*Inicio Abrir directorio*/
						
						//$chunksize = 1*(1024*1024); // how many bytes per chunk
						$chunksize = 1024; // how many bytes per chunk
						//$dir_generados="C:\\AppServ\\www\\SAM\\Gestion_Interna\\paginas\\";//Direcctorio que se quiere abrir
						//$dir_generados="C:\\SAM\Informes_Policiales\\";
						//$directorio=opendir($dir_generados);//se abre el direcctorio						
						
						//$dir_generados="C:\\SAM\\Informes_Policiales\\";//Direcctorio que se quiere abrir
						$dir_generados="C:\\AppServ\\www\\SAM\\Gestion_Interna\\paginas\\";//Direcctorio que se quiere abrir
						$directorio=opendir($dir_generados);//se abre el direcctorio
						$cont=0;
						while (false !==($archivo = readdir($directorio))){  
							if((!is_dir($archivo))&&( ereg($codigo_establecimiento,$archivo ))){	
							  	
								$handle = fopen($archivo, 'rb');
								while (!feof($handle))
							   	{
							   	  	$cont=$cont+1;
							   	  	//echo "contandor".$cont;
							    	//$buffer = fread($handle, $chunksize);	
							    	$buffer = fgets($handle,$chunksize);								    	
									//guardar entero en la variable $buffer y tratar la variable con el tamaño que se necesite por linea.											    	
									if($cont==1){$longitud=strlen($buffer);	}
							     	//echo"Frase:".$buffer."<br>";//escribir lo q contiene el documento							    
									if($longitud>=73){	
							     		$primero=substr($buffer,0, 73);//se escriben datos del fichero por pantalla hasta 73 bytes en la primera linea 
							     		$segundo=substr($buffer,74,$longitud);//se escriben el resto de datos por pantalla desde 74 hasta que fin de linea leida
							     		echo $primero."<br>";
							     		echo $segundo."<br>";
							     	}else{
										echo$buffer;									   
									}
							     	//echo "<br>longitud:".$longitud."<br>";
							     	//echo chr(13).chr(10);//inserta salto de linea
							     	
							     	flush();							     
							   	}
							   fclose($handle);													
							}
							else{
								$siguiente_secuencia="001";//primer documento que se crea			  
							}	
						}	
						/*Fin Abrir directorio*/
						?>					
									
					</tr>

					</tbody>

				</table>
			</td>
		</tr>
	</table>
	
	<script>
	//Llamada a la función imprimir que imprimirá la página
	 imprimir();
    </script>
	</body>
	</html>
