<?php
//funcion que calcula cual es la siguiente secuencia del nombre del documento que se va a generar
	function digitos($ceros,$secuencia){			
		if($secuencia=="999"){//si se llega a 999 el siguiente debe de ser 001
		  $resultado="001";	
		}else{ //sino se genera el siguiente con los ceros que sean necesarios delante 
			$resultado = "".intval($secuencia) + 1;//se pasa la secuencia a entero, para poder sumarle uno
			$longitud=strlen($resultado); 	//se calcula la longitud del del resultado obtenido, para luego calcularle los ceros que necesita.
			while ($longitud<$ceros){//si tiene menos longitud de la q la D.G.T nos pide seguimos añadiendole ceros al resultado.			 
				$resultado="0".$resultado;
				$longitud=strlen($resultado); 							
			}
		}
		return $resultado;	//devuelve la nueva secuencia		  
	}
	function limpiar_acentos($s)
	{
		$s = ereg_replace("[äáàâãª]","a",$s);
		$s = ereg_replace("[ÄÁÀÂÃ]","A",$s);
		$s = ereg_replace("[ÏÍÌÎ]","I",$s);
		$s = ereg_replace("[ïíìî]","i",$s);
		$s = ereg_replace("[ëéèê]","e",$s);
		$s = ereg_replace("[ËÉÈÊ]","E",$s);
		$s = ereg_replace("[öóòôõº]","o",$s);
		$s = ereg_replace("[ÖÓÒÔÕ]","O",$s);
		$s = ereg_replace("[üúùû]","u",$s);
		$s = ereg_replace("[ÜÚÙÛ]","U",$s);
		$s = str_replace("ç","c",$s);
		$s = str_replace("Ç","C",$s);
		$s = str_replace("[ñ]","n",$s);
		$s = str_replace("[Ñ]","N",$s);	
		return $s;
	}
	
	
	
	$anio=Date("Y");$mes=Date("n");$dia=Date("j");			
	$fecha_hoy=date("j-n-Y", mktime(0, 0, 0,$mes,$dia, $anio));	
	//$fecha_ayer=date("j-n-Y",mktime(0,0,0,$mes,$dia-1,$anio));
	//echo "fecha hoy:".$fecha_hoy;
	$mm=$mes;$dd=$dia;$aa=$anio;
	$fechita_sql=date("Y-m-d",mktime(0,0,0,$mm,$dd,$aa));	
	$fecha_hoy=date("Y-m-d",mktime(0,0,0,$mm,$dd,$aa));
	list($aa,$mm,$dd) = split('[-./]', $fecha_hoy);	//quitamos barras, guiones etc... de las fechas 
	$fecha_confec_fich=$aa.$mm.$dd;//fecha confeccion del fichero.


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
    
	$query_cont_registros_per="(SELECT COUNT(*) as num from  pernocta where (pernocta.Fecha_Llegada='".$fechita_sql."'));";	
	$query_cont_registros_p="(SELECT COUNT(*) as num from  pernocta_p where (pernocta_p.Fecha_Llegada='".$fechita_sql."'))";	 
	$query_cont_registros_comp="(SELECT COUNT(*) as num FROM componentes_grupo where (componentes_grupo.fecha_llegada='".$fechita_sql."'));";
	$resul_per=mysql_query($query_cont_registros_per);
 	$resul_p=mysql_query($query_cont_registros_p);
 	$resul_comp=mysql_query($query_cont_registros_comp);
    for($i=0;$i<mysql_num_rows($resul_per);$i++)
	{
		$fila_per=mysql_fetch_array($resul_per);
		$cont_per=$fila_per['num'];		
		//valdrá 1 si ecuentra una fila coincidente(una como máximo ya que el campo usuario es clave primaria) y cero si el usuario no existe en la base de datos
	}
	//echo "contador pernoctas:".$cont_per;
	for($i=0;$i<mysql_num_rows($resul_p);$i++)
	{
		$fila_p=mysql_fetch_array($resul_p);
		$cont_p=$fila_p['num'];		
		//valdrá 1 si ecuentra una fila coincidente(una como máximo ya que el campo usuario es clave primaria) y cero si el usuario no existe en la base de datos
	}
	//echo "contador_p:".$cont_p;
	for($i=0;$i<mysql_num_rows($resul_comp);$i++)
	{
		$fila_comp=mysql_fetch_array($resul_comp);
		$cont_comp=$fila_comp['num'];		
		//valdrá 1 si ecuentra una fila coincidente(una como máximo ya que el campo usuario es clave primaria) y cero si el usuario no existe en la base de datos
	}
	//echo "contador_componentes:".$cont_comp;

	/*Fin SQL consulta cantidad de Registros*/

	$cont_registros=$cont_per+$cont_p+$cont_comp;//suma de resultados obtenidos de contabilizar registros.
	//datos centro albergue:
	$tipo_registro_alb=substr(trim($fila_albergue['Tipo_registro_Alb']),0, 1);//tipo de registro de los datos del albergue	
	$codigo_establecimiento=substr(trim($fila_albergue['Cod_Establecimiento']),0, 10);
	$nombre_establecimiento=substr(trim($fila_albergue['Nombre_Alb']),0, 40);
	
	$hora_confec_fich= date('Hi');//hora en q va a ser generado el documento.
	$dir_generados="C:\\SAM\Informes_Policiales\\";//Direcctorio que se quiere abrir
	$dir_generados2="C:\\AppServ\\www\\SAM\\Gestion_Interna\\paginas\\";//Direcctorio que se quiere abrir
	
	$directorio=opendir($dir_generados);  //se abre el direcctorio
	$directorio2=opendir($dir_generados2);//se abre el direcctorio

	while (false !==($archivo = readdir($directorio2))){  
		if((!is_dir($archivo))&&( ereg($codigo_establecimiento,$archivo ))){
		  
			/*inicio leer*/
			$handle = fopen("C:\\AppServ\\www\\SAM\\Gestion_Interna\\paginas\\".$archivo, 'rb');			
			$buffer = fgets($handle);
			$buffer=list($codigo,$nomb_fichero,$n_establecimiento,$fecha_creacion,$hora_creacion,$n_registros)=split('[|]',$buffer);
			flush();
			fclose($handle);	
		  /*fin leer*/	  
		 
			list($aa,$mm,$dd) = split('[-./]', $fechita_sql);	//quitamos barras, guiones etc... de las fechas 
			$fechita_sql=$aa.$mm.$dd;//fecha confeccion del fichero.

		  	if($fecha_creacion==$fechita_sql){//si la fecha en q ha sido creado el ultimo documento es igual a la de hoy($fechita_sql) conservamos la misma secuencia
				list($codigo,$secuencia) = split('[.]', $archivo);//saco del nombre del fichero el numero de secuencia
				$siguiente_secuencia=$secuencia;//se queda el mismo numero de secuencia, cuando se genere el documento de nuevo	
			
			}else{
				list($codigo,$secuencia) = split('[.]', $archivo);
				$siguiente_secuencia=digitos(3,$secuencia);//conseguir la siguiente secuencia							
			}
			$bandera=true;	//si a encontrado un documento con el formato correspondiente coge el valor true.	
		
		}
	}$docc_anterior=$codigo_establecimiento.".".$secuencia;//documento que habrá que eliminar
	
	if($bandera!=true){//si no tiene valor true, no se a encontrado ningun documento con ese formato, por lo tanto habrá que generar el primero.
		$siguiente_secuencia="001";//primer documento que se crea			    
	}else{		
//		unlink("paginas/".$docc_anterior);	  
		unlink("C:\\SAM\Informes_Policiales\\".$docc_anterior);	  
		unlink("C:\\AppServ\\www\\SAM\\Gestion_Interna\\paginas\\".$docc_anterior);		  
	}		
	$docc=$codigo_establecimiento.".".$siguiente_secuencia;//documento nuevo que se crea		

	$doc = fopen("C:\\SAM\Informes_Policiales\\".$docc,"w");
	$doc2 = fopen("C:\\AppServ\\www\SAM\\Gestion_Interna\\paginas\\".$docc,"w");

	/*Fin Fichero txt*/	

	$nombre_fichero=$codigo_establecimiento.".".$siguiente_secuencia;
	//$tipo_registro puede ser de tres tipo: 0: si es una agrupacion hotelera, 1:datos del establecimiento hotelero y de control, 2:datos del viajero.	
	$contenido  = $tipo_registro_alb."|".$codigo_establecimiento."|".$nombre_establecimiento."|".$fecha_confec_fich."|".$hora_confec_fich."|".$cont_registros."|\r\n";	
	$contenido=limpiar_acentos($contenido);//limpia todo el texto de tildes y  acentos	etc
	fputs($doc,strtoupper($contenido));	
	fputs($doc2,strtoupper($contenido));		
			
	/*INICIO SQLs*/
	$query_pernocta="SELECT 
		pernocta.DNI_Cl, 		
		pernocta.Fecha_Llegada,
		pernocta.Fecha_Salida,
		cliente.DNI_Cl,
		cliente.Nombre_cl,
		cliente.Apellido1_Cl,
		cliente.Apellido2_Cl,
		cliente.Fecha_Nacimiento_Cl,
		cliente.Sexo_Cl,
		cliente.Tipo_documentacion,
		cliente.Fecha_Expedicion,
		cliente.Id_Pais,
		pais.Id_Pais,
		pais.Nombre_Pais 
	FROM pernocta 
	INNER JOIN 
	(
		pais
		INNER JOIN cliente	
		ON pais.Id_Pais=cliente.Id_Pais
	)	
	ON pernocta.DNI_Cl=cliente.DNI_Cl where (pernocta.Fecha_Llegada='".$fechita_sql."');";
	
	//echo"query_pernocta:<br>".$query_pernocta."<br><br>";
	$result_pernocta = mysql_query($query_pernocta);
    $num_results_pernocta=mysql_num_rows($result_pernocta);  	      
    for($i=0;$i<$num_results_pernocta;$i++){
		$fila_pernocta=mysql_fetch_array($result_pernocta);	
		
		$tipo_registro_datos=$fila_albergue['Tipo_registro_Datos'];
		$dni_cl_pernocta=$fila_pernocta['DNI_Cl'];//si es  español 11 , si es extrangero 14 caracteres;
		$fecha_llegada_pernocta=$fila_pernocta['Fecha_Llegada'];		
		$nombre_cl_pernocta=$fila_pernocta['Nombre_cl'];
		$apellido1_cl_pernocta=$fila_pernocta['Apellido1_Cl'];
		$apellido2_cl_pernocta=$fila_pernocta['Apellido2_Cl'];
		$fecha_nacimiento_cl_pernocta=$fila_pernocta['Fecha_Nacimiento_Cl'];
		$sexo_cl_pernocta=$fila_pernocta['Sexo_Cl'];
		$tipo_documentacion_cl_pernocta=$fila_pernocta['Tipo_documentacion'];
		$fecha_expedicion_cl_pernocta=$fila_pernocta['Fecha_Expedicion'];
		$nombre_pais_cl_pernocta=$fila_pernocta['Nombre_Pais'];	
		
		list($a,$m,$d) = split('[-./]', $fecha_llegada_pernocta);	//quitamos barras, guiones etc... de las fechas 
		$fecha_llegada_pernocta=$a.$m.$d;
		list($a,$m,$d) = split('[-./]', $fecha_nacimiento_cl_pernocta);	
		$fecha_nacimiento_cl_pernocta=$a.$m.$d;	
		list($a,$m,$d) = split('[-./]', $fecha_expedicion_cl_pernocta);	
		$fecha_expedicion_cl_pernocta=$a.$m.$d;	
		$apellido1_cl_pernocta=ereg_replace("-", " ", $apellido1_cl_pernocta);
		$apellido2_cl_pernocta=ereg_replace("-", " ", $apellido2_cl_pernocta);
		
		
		if($nombre_pais_cl_pernocta=="España"){
		  $dni_cl_pernocta=substr(trim($dni_cl_pernocta), 0, 11);		  
		  $documento_cl_pernocta="";
		}
		else{ 
		  $documento_cl_pernocta=substr($dni_cl_pernocta, 0, 14);$dni_cl_pernocta="";
		}	
			
		$contenido= substr(trim($tipo_registro_datos),0, 1)."|".$dni_cl_pernocta."|".$documento_cl_pernocta."|".substr(trim($tipo_documentacion_cl_pernocta),0, 1)."|".substr(trim($fecha_expedicion_cl_pernocta),0, 8)."|".substr(trim($apellido1_cl_pernocta),0, 30)."|"
		.substr(trim($apellido2_cl_pernocta),0, 30)."|".substr(trim($nombre_cl_pernocta),0, 30)."|".substr(trim($sexo_cl_pernocta),0,1)."|".substr(trim($fecha_nacimiento_cl_pernocta),0, 8)."|"
		.substr(trim($nombre_pais_cl_pernocta),0, 21)."|".substr(trim($fecha_llegada_pernocta),0, 8)."|\r\n";	
		
		$contenido=limpiar_acentos($contenido);//limpia todo el texto de tildes y  acentos	etc
		
		fputs($doc,strtoupper($contenido));	
		fputs($doc2,strtoupper($contenido));
	
	}	
	
 	$query_pernocta_p="SELECT 
		pernocta_p.DNI_Cl,
		pernocta_p.Fecha_Llegada,
		pernocta_p.Fecha_Salida,
		cliente.DNI_Cl,
		cliente.Nombre_cl, 
		cliente.Apellido1_Cl,
		cliente.Apellido2_Cl,
		cliente.Fecha_Nacimiento_Cl,
		cliente.Sexo_Cl,
		cliente.Tipo_documentacion,
		cliente.Fecha_Expedicion,
		cliente.Id_Pais,
		pais.Id_Pais,
		pais.Nombre_Pais 
	FROM pernocta_p 
	INNER JOIN 
	(
		pais 
		INNER JOIN cliente 
		ON pais.Id_Pais=cliente.Id_Pais 
	) 
	ON pernocta_p.DNI_Cl=cliente.DNI_Cl where (pernocta_p.fecha_llegada='".$fechita_sql."');";
	//echo "fecha:".$fechita_sql;
	//echo"query_pernocta_p:<br>".$query_pernocta_p."<br><br>";
	$result_pernocta_p= mysql_query($query_pernocta_p);
    $num_results_pernocta_p=mysql_num_rows($result_pernocta_p);  	      
    for($i=0;$i<$num_results_pernocta_p;$i++){
		$fila_pernocta_p=mysql_fetch_array($result_pernocta_p);	
		
		$tipo_registro_datos=$fila_albergue['Tipo_registro_Datos'];	
		$dni_cl_pernocta_p=$fila_pernocta_p['DNI_Cl'];
		$fecha_llegada_pernocta_p=$fila_pernocta_p['Fecha_Llegada'];			
		$nombre_cl_pernocta_p=$fila_pernocta_p['Nombre_cl'];	
		$apellido1_cl_pernocta_p=$fila_pernocta_p['Apellido1_Cl'];
		$apellido2_cl_pernocta_p=$fila_pernocta_p['Apellido2_Cl'];	
		$fecha_nacimiento_cl_pernocta_p=$fila_pernocta_p['Fecha_Nacimiento_Cl'];
		$sexo_cl_pernocta_p=$fila_pernocta_p['Sexo_Cl'];	
		$tipo_documentacion_cl_pernocta_p=$fila_pernocta_p['Tipo_documentacion'];
		$fecha_expedicion_cl_pernocta_p=$fila_pernocta_p['Fecha_Expedicion'];	
		$nombre_pais_cl_pernocta_p=$fila_pernocta_p['Nombre_Pais'];			
		
		
		list($a,$m,$d) = split('[-./]', $fecha_llegada_pernocta_p);	//quitamos barras, guiones etc... de las fechas 
		$fecha_llegada_pernocta_p=$a.$m.$d;	
		list($a,$m,$d) = split('[-./]', $fecha_nacimiento_cl_pernocta_p);	
		$fecha_nacimiento_cl_pernocta_p=$a.$m.$d;	
		list($a,$m,$d) = split('[-./]', $fecha_expedicion_cl_pernocta_p);	
		$fecha_expedicion_cl_pernocta_p=$a.$m.$d;	
		$apellido1_cl_pernocta_p=ereg_replace("-", " ", $apellido1_cl_pernocta_p);
		$apellido2_cl_pernocta_p=ereg_replace("-", " ", $apellido2_cl_pernocta_p);

		if($nombre_pais_cl_pernocta_p=="España"){
			$dni_cl_pernocta_p=substr(trim($dni_cl_pernocta_p), 0, 11);
			$documento_cl_pernocta_p="";
		}
		else{ $documento_cl_pernocta_p=substr(trim($dni_cl_pernocta_p), 0, 14);$dni_cl_pernocta_p="";
		}
		
		$contenido = substr(trim($tipo_registro_datos),0, 1)."|".$dni_cl_pernocta_p."|".$documento_cl_pernocta_p."|".substr(trim($tipo_documentacion_cl_pernocta_p),0, 1)."|".substr(trim($fecha_expedicion_cl_pernocta_p),0, 8)."|".substr(trim($apellido1_cl_pernocta_p),0, 30)."|"
		.substr(trim($apellido2_cl_pernocta_p),0, 30)."|".substr(trim($nombre_cl_pernocta_p),0, 30)."|".substr(trim($sexo_cl_pernocta_p),0,1)."|".substr(trim($fecha_nacimiento_cl_pernocta_p),0, 8)."|"
		.substr(trim($nombre_pais_cl_pernocta_p),0, 21)."|".substr(trim($fecha_llegada_pernocta_p),0, 8)."|\r\n";	
		
		$contenido=limpiar_acentos($contenido);//limpia todo el texto de tildes y  acentos	etc
		
		fputs($doc,strtoupper($contenido)); 
		fputs($doc2,strtoupper($contenido)); 		
	}	
	
	$query_componentes="SELECT 
	componentes_grupo.DNI,
	componentes_grupo.Nombre_Gr,
	componentes_grupo.Fecha_Llegada,	
	componentes_grupo.Tipo_documentacion,
	componentes_grupo.Fecha_Expedicion,
	componentes_grupo.Nombre,
	componentes_grupo.Apellido1,
	componentes_grupo.Apellido2,
	componentes_grupo.Fecha_nacimiento,
	componentes_grupo.Id_Pais_nacionalidad,
	componentes_grupo.Sexo,
	estancia_gr.Fecha_Salida,
	estancia_gr.Nombre_Gr,
	pais.Id_Pais,
	pais.Nombre_Pais 
	FROM estancia_gr
	INNER JOIN 
	(
		pais 
		INNER JOIN componentes_grupo
		ON pais.Id_Pais=componentes_grupo.Id_Pais_nacionalidad
	) 
	ON componentes_grupo.Nombre_Gr=estancia_gr.Nombre_Gr where (componentes_grupo.fecha_llegada='".$fechita_sql."');";
	//echo"componentes_grupo:<br>".$query_componentes."<br><br>";
	
	$result_componentes= mysql_query($query_componentes);
    $num_results_componentes=mysql_num_rows($result_componentes);  	      
    for($i=0;$i<$num_results_componentes;$i++){
		$fila_componentes=mysql_fetch_array($result_componentes);		
		
		$tipo_registro_datos=$fila_albergue['Tipo_registro_Datos'];
		$dni_componentes=$fila_componentes['DNI'];	
		$tipo_documentacion_componentes=$fila_componentes['Tipo_documentacion'];
		$fecha_expedicion_componentes=$fila_componentes['Fecha_Expedicion'];
		$apellido1_componentes=$fila_componentes['Apellido1'];
		$apellido2_componentes=$fila_componentes['Apellido2'];
		$nombre_componentes=$fila_componentes['Nombre'];
		$sexo_componentes=$fila_componentes['Sexo'];
		$fecha_nacimiento_componentes=$fila_componentes['Fecha_nacimiento'];		
		$pais_componentes=$fila_componentes['Nombre_Pais'];		
		$fecha_llegada_componentes=$fila_componentes['Fecha_Llegada'];
		$apellido1_componentes=ereg_replace("-", " ", $apellido1_componentes);
		$apellido2_componentes=ereg_replace("-", " ", $apellido2_componentes);
		
		list($a,$m,$d) = split('[-./]', $fecha_expedicion_componentes);	//quitamos barras, guiones etc... de las fechas 
		$fecha_expedicion_componentes=$a.$m.$d;	
		list($a,$m,$d) = split('[-./]', $fecha_nacimiento_componentes);	
		$fecha_nacimiento_componentes=$a.$m.$d;	
		list($a,$m,$d) = split('[-./]', $fecha_llegada_componentes);	
		$fecha_llegada_componentes=$a.$m.$d;	
		
		if( $pais_componentes=="España"){
		  $dni_componentes=substr(trim($dni_componentes), 0, 11);
		  $documento_componentes="";		  
		}
		else{ $documento_componentes=substr(trim($dni_componentes), 0, 14);$dni_componentes="";
		}		
		$contenido = substr(trim($tipo_registro_datos),0, 1)."|".trim($dni_componentes)."|".trim($documento_componentes)."|".substr(trim($tipo_documentacion_componentes),0, 1)."|".substr(trim($fecha_expedicion_componentes),0, 8)."|"
		.substr(trim($apellido1_componentes),0, 30)."|".substr(trim($apellido2_componentes),0, 30)."|".substr(trim($nombre_componentes),0, 30)."|"
		.substr(trim($sexo_componentes),0, 1)."|".substr(trim($fecha_nacimiento_componentes),0, 8)."|".substr(trim($pais_componentes),0, 21)."|"
		.substr(trim($fecha_llegada_componentes),0, 8)."|\r\n";
		
		$contenido=limpiar_acentos($contenido);//limpia todo el texto de tildes y  acentos	etc
						
		fputs($doc,strtoupper($contenido));
		fputs($doc2,strtoupper($contenido)); 
			
	}
		
		
			
	/*FIN SQLs*/	
	
	/* INICIO MOSTRAR EN GESTION INTERNA TABLA---------------------muy provisional---borrar por completo o modificar*/
?>
	<div id='datos' style='margin-top:20px;margin-left:100;' >
		<table  border=0 cellspacing="0" background-color:'#f4fcff'>
			<form action='#' method="POST" name="datos">
            <thead>
				<tr id="titulo_tablas">
					<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
						<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:490px;'>
						Informe Generado
						</div>
						<div class='champi_derecha'>&nbsp;</div>
					</td>
				</tr>
			 </thead>
			 <tr>
				<td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                    <table style="border: 1px solid #3F7BCC;" class="tabla_detalles" border="0" width="550" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="80" style='background-color:#f4fcff'>
	                	<?$anio=Date("Y");$mes=Date("n");$dia=Date("j");			
						$fecha_hoy=date("j-n-Y", mktime(0, 0, 0,$mes,$dia, $anio));?>
						<tr height="45">							
		                    <td align="center"><br><label class="label_formulario">El Fichero ha sido generado con éxito</label></td>						
	                    </tr>						
						<tr height="45">		                    
							<td align="center"><br><label class="label_formulario"><?echo "C:\\SAM\Informes_Policiales\\".$docc."<br>"?></label></td>   
	                    </tr>  
	                    <tr height="45">		                    
							<td align="center"><br><br>
								<a href="#" onClick='window.open("paginas/imprimir_informe.php", "Informe_Policial","width=700px,height=500px,menubar=no,location=no,resizable=yes,scrollbars=yes,status=no");'> <img src='../imagenes/botones-texto/imprimir.jpg' border="0"></a>								
							</td>	
	                    </tr>
	                    <tr height="45">		                    
							<td width="45">&nbsp;</td>	
	                    </tr>							                  
					</table>
            	</td>
			</tr>
            </form>
		</table>			 
	</div>
<?	
  	/*INICIO MOSTRAR EN GESTION INTERNA TABLA */
	
	mysql_close($db);	
	closedir($directorio);//Cierro el primer directorio donde lo
	closedir($directorio2);//Cierro el primer directorio donde lo

?>
