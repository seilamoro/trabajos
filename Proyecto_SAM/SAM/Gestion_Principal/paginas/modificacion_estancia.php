
<div id="caja_superior">

	<div id="caja_superior_izquierda" >
	<div id="modificacion_estancia" style="display:block;">
<?

$sw_calendario_inicial =  false;
if(!isset($_SESSION['mod_estancia']['fecha']) || $_SESSION['mod_estancia']['fecha'] == ""){
	$_SESSION['mod_estancia']['fecha'] = date("Y-m-d");
}

//Inicialicar arrays
if(!isset($_POST['sub']) || ($_POST['sub'] !="true" && $_POST['sub'] !="false")){
	$_SESSION['alb'] = Array();
	$_SESSION['per'] = Array();
	
}
?>
<script language="javascript">
	function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#569CD7';
	  	tr.style.color = '#F4FCFF';
	  	tr.style.cursor = 'pointer';

	
	}
	
	function desresaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#F4FCFF';
	  	tr.style.color = '#3F7BCC';
	}
		//Esta función muestra o oculta el la capa de la leyenda de 'distribución de habitaciones' segun el parámetro 'act'
		function ver_leyenda(act){
		if(act == '1')
			document.getElementById('leyenda').style.visibility='hidden';
		else
			document.getElementById('leyenda').style.visibility='visible';
	}
	function asignacion_parcial(fecha,id){
		var id_split = id.split("-");
		var fecha_split = fecha.split("-");
		document.forms[0].action=document.forms[0].action+'&estancia_parcial=1';
		document.forms[0].sub.value="true";
		asigna_habitacion(id_split[0],id_split[1],id_split[2],id_split[3],'s',1);
		cambiar_dia(fecha_split[0],fecha_split[1],fecha_split[2]);
	}
	function asigna_habitacion(hab,i,col,fila,cambio,parcial){
				//Se evalua el color de fondo de la celda de la tabla. En función de su color de fondo se sabrá si se habrá intentado asignar o no.
			var fecha_llegada_sp = document.forms[0].fecha_llegada.value.split("-");
			var fecha_cal = document.forms[0].fecha_cal.value.split("-");
			if(document.forms[0].asignada.value == 1){
					//En este caso estará ocupada, por tanto se le devuelve el color de habitación libre.				
					if(document.forms[0].id_cama.value == hab+"-"+i+"-"+col+"-"+fila){
						if(document.forms[0].id_habitacion.value == ""){
							alert("Debe seleccionar una habitación para el primer dia de estancia.");
						}else{
							if(parcial == 0){
								document.getElementById(document.forms[0].id_cama.value).className="cama_libre";
							}else{
								document.getElementById(document.forms[0].id_cama.value).className="cama_temp";
							}							
							document.forms[0].id_habitacion.value = "";					
							document.forms[0].id_cama.value = "";
							document.forms[0].asignada.value = 0;
						}
					}else{
						if(parcial == 0){
							document.getElementById(document.forms[0].id_cama.value).className="cama_libre";
						}else{
							document.getElementById(document.forms[0].id_cama.value).className="cama_temp";
							if(document.forms[0].estancia_parcial.value ==0){
								document.forms[0].estancia_parcial.value = 1;
							}else{
								document.forms[0].estancia_parcial.value = 0;
							}
						}
						document.getElementById(hab+"-"+i+"-"+col+"-"+fila).className = "cama_asignada";					
						document.forms[0].id_habitacion.value = hab;
						document.forms[0].id_cama.value = hab+"-"+i+"-"+col+"-"+fila;
						document.forms[0].asignada.value = 1;
						
					}
				
			}else{
					//En este bloque se comprueba que no le pueda asignar más de una habitación
				if(document.forms[0].id_habitacion.value != ""){			
					if(parcial == 0){
						document.getElementById(document.forms[0].id_cama.value).className="cama_libre";
					}else{
						document.getElementById(document.forms[0].id_cama.value).className="cama_temp";
					}
					document.forms[0].id_cama.value = "";
					document.forms[0].id_habitacion.value = "";

				}
					//Se le da el color de asignada a la celda en que se ha hecho click
				document.getElementById(hab+"-"+i+"-"+col+"-"+fila).className = "cama_asignada";
				if(parcial == 1){
					if(document.forms[0].estancia_parcial.value ==0){
						document.forms[0].estancia_parcial.value = 1;
					}else{
						document.forms[0].estancia_parcial.value = 0;
					}	
				}
				document.forms[0].id_habitacion.value = hab;
				document.forms[0].id_cama.value = hab+"-"+i+"-"+col+"-"+fila;
				document.forms[0].asignada.value = 1;
				
			}	
			if(cambio == 's'){
				document.forms[0].cambio_habitacion.value = "true";
				if(document.forms[0].avisa_seleccion_habitacion.value == "true"){
					document.forms[0].avisa_seleccion_habitacion.value = "";
				}
			}
	}

	function cambio_camas(cambio){	//Variación del numero de camas (Funcionalidad de las Felchitas)		
		var camas = parseInt(document.forms[0].pernocta.value);
		var oper="camas=camas"+cambio;
		eval(oper);
		if(parseInt(cambio) < 0 && document.forms[0].avisa_seleccion_habitacion.value=='true'){
			document.forms[0].avisa_seleccion_habitacion.value='';
		}
		if (camas<=1){
			camas=1;
		}		
		document.forms[0].pernocta.value=camas;
		document.forms[0].submit();
	}

	function cambiar_dia(dia,mes, anio){		
		if(document.forms[0].avisa_seleccion_habitacion.value =="true"){
			if(document.forms[0].id_habitacion.value == ""){
				alert("Debe seleccionar una habitacion");
				return;
			}
		 }		
		var fecha_llegada = document.forms[0].fecha_ant.value.split("-");
		var fecha_salida = document.forms[0].fecha_salida.value.split("-");

		var fecha_calen =  dia+"-"+mes+"-"+anio;		
		document.forms[0].sub.value = "false";
		document.forms[0].fecha_cal.value = fecha_calen;			
		document.forms[0].action =  document.forms[0].action+"&tipo="+document.forms[0].tipo_auxiliar.value+"&dni="+document.forms[0].dni_ant.value+"&fecha_llegada="+document.forms[0].fecha_ant.value+"&fecha_salida="+document.forms[0].fecha_salida.value;
		document.forms[0].tipo.value = "";
		document.forms[0].submit();
	}
	function cambio_pernocta(){
		if(evalua_estancia()){
			document.forms[0].sub.value = "false";
			document.forms[0].action =  document.forms[0].action+"&tipo="+document.forms[0].tipo_auxiliar.value+"&dni="+document.forms[0].dni_ant.value+"&fecha_llegada="+document.forms[0].fecha_llegada.value+"&fecha_salida="+document.forms[0].fecha_salida.value;
			document.forms[0].tipo.value = "";
			document.forms[0].submit();
		}
	}
	function evalua_estancia(){
		if(document.forms[0].pernocta.value > 127){
			alert("El número máximo de pernoctas es 127.");
			document.forms[0].pernocta.focus();
			return false;
		}else if(document.forms[0].pernocta.value <= 0){
			alert("Ha introducido  un número de pernoctas no válido.");
			document.forms[0].pernocta.focus();
			return false;
		}else{
			return true;
		}
	}
	function prepara_cambiar_dia(){
		var fecha_llegada_split = document.forms[0].fecha_ant.value.split("-");
		document.forms[0].ant_dia_fecha_llegada.value = parseInt(fecha_llegada_split[2])-1;
		document.forms[0].ant_mes_fecha_llegada.value = parseInt(fecha_llegada_split[1])-1;
		document.forms[0].ant_anyo_fecha_llegada.value = parseInt(fecha_llegada_split[0])-1;
	}

</script>

<?
include('paginas/gdh_session.php');
include('paginas/funciones_alb_per.php');
$avisa_seleccion_hab = "";
$sw_calendario =false;

$habitaciones_orden = array();
$numero_paginas = array();

for ($i = 0; $i < COUNT($_SESSION['pag_hab']); $i++) {
	if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$numero_paginas)) {
		$numero_paginas[] = $_SESSION['pag_hab'][$i]['pagina'];
		//print("numero1=".$numero_paginas[i]);
	}
	if ($_SESSION['pag_hab'][$i]['pagina'] == $_SESSION['gdh']['dis_hab']['num_pag']) {
		$cont = COUNT($habitaciones_orden);
		$habitaciones_orden[$cont]['orden'] = $_SESSION['pag_hab'][$i]['orden'];
		$habitaciones_orden[$cont]['Id_Tipo_Hab'] = $_SESSION['pag_hab'][$i]['Id_Tipo_Hab'];
		$habitaciones_orden[$cont]['pagina'] = $_SESSION['pag_hab'][$i]['pagina'];
		//print("tipo=".$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']);
	}
}	

foreach ($habitaciones_orden as $llave => $fila) {
   $orden[$llave]  = $fila['orden'];
}

if (COUNT($habitaciones_orden) > 0) {
	@ ARRAY_MULTISORT($orden, SORT_DESC, $habitaciones_orden);
}

if(isset($_POST['pagina_habitaciones']) && $_POST['pagina_habitaciones'] != ""){
	$pagina_habitaciones = $_POST['pagina_habitaciones'];
	 $_SESSION['gdh']['dis_hab']['num_pag']=$_POST['pagina_habitaciones'];
}else{
	$pagina_habitaciones = $_SESSION['gdh']['dis_hab']['num_pag'];
}
//print($_SESSION['gdh']['dis_hab']['num_pag']);

@$db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
mysql_select_db($_SESSION['conexion']['db']);
	if(!$db){
		echo "<script>Alert('No se ha podido realizar la conexion con la base de datos')</script>";
	}else{	
			//Control de habitaciones y pernoctas en caso de que sean varios*/
			if(isset($_GET['fecha_cal']) && trim($_GET['fecha_cal']) != ""){
              //print("con get");
				$split_cal = split("-",$_GET['fecha_cal']);
				if(strlen($split_cal[0]) == 1 && intval($split_cal[0])<10){					
					$split_cal[0] = "0".$split_cal[0];
				}
				if(strlen($split_cal[1]) == 1 && intval($split_cal[1])<10){
					$split_cal[1] = "0".$split_cal[1];
				}
				$fecha_calendario = trim($split_cal[2])."-".trim($split_cal[1])."-".trim($split_cal[0]);	 
			}else if(isset($_POST['fecha_cal']) && trim($_POST['fecha_cal']) != ""){
                //print("con posp");
				$split_cal = split("-",$_POST['fecha_cal']);				
				if(strlen($split_cal[0]) == 1 && intval($split_cal[0])<10){
						$split_cal[0] = "0".$split_cal[0];
				}
				if(strlen($split_cal[1]) == 1 && intval($split_cal[1])<10){
						$split_cal[1] = "0".$split_cal[1];
				}
				$fecha_calendario = trim($split_cal[2])."-".trim($split_cal[1])."-".trim($split_cal[0]);	 
			}else if(isset($_SESSION['gdh']['dia']) && trim($_SESSION['gdh']['dia']) && isset($_SESSION['gdh']['mes']) && trim($_SESSION['gdh']['mes']) && isset($_SESSION['gdh']['anio']) && trim($_SESSION['gdh']['anio'])){
                //print("con gdh");
				$fecha_calendario  = trim($_SESSION['gdh']['anio'])."-".trim($_SESSION['gdh']['mes'])."-".trim($_SESSION['gdh']['dia']);
			}else if(isset($_GET['fecha_llegada']) && trim($_GET['fecha_llegada'])!= ""){
					$fecha_calendario = trim($_GET['fecha_llegada']);
					 // print("con fechallegada");
			}else{
				$fecha_calendario = date("Y-m-d");
			}							
			$_SESSION['alb']['estancia']['fecha_salida'] = establecer_fecha($_GET['fecha_llegada'],$_POST['pernocta']);
			//$_SESSION['alb']['estancia']['fecha_salida'] = $_GET['fecha_salida'];
			if (isset($fecha_calendario) && $fecha_calendario != "" ){					
				$resto = resta_fecha($_SESSION['alb']['estancia']['fecha_llegada'] , $fecha_calendario);
			}else{
				$resto = 0;
			}			
		}
		
		//print($fecha_calendario);
		$num_pernoctas = count($_SESSION['alb']['estancia']['pernoctas']);
      


	//Control de habitaciones y pernoctas en caso de que sean varios*/
	if(count($_SESSION['alb']['estancia']['pernoctas']) > 0){
		$_POST['id_habitacion'] = trim($_POST['id_habitacion']);				
		if(isset($_POST['id_habitacion']) && $_POST['id_habitacion'] != "" && $_POST['cambio_habitacion'] == "true"){
			for($i = 0;$i<count($_SESSION['alb']['estancia']['pernoctas']);$i++){					
				if(trim($_POST['ant_fecha_calendario']) >= $_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada'] && trim($_POST['ant_fecha_calendario']) < $_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida']){										
					if($_GET['estancia_parcial'] == 1){										
						for($j=count($_SESSION['alb']['estancia']['pernoctas']);$j>$i;$j--){
							$_SESSION['alb']['estancia']['pernoctas'][$j]['id_hab'] = $_SESSION['alb']['estancia']['pernoctas'][$j-1]['id_hab'];
							$_SESSION['alb']['estancia']['pernoctas'][$j]['fecha_llegada'] = $_SESSION['alb']['estancia']['pernoctas'][$j-1]['fecha_llegada'];
							$_SESSION['alb']['estancia']['pernoctas'][$j]['fecha_salida'] = $_SESSION['alb']['estancia']['pernoctas'][$j-1]['fecha_salida'];
							$_SESSION['alb']['estancia']['pernoctas'][$j]['parcial'] = $_SESSION['alb']['estancia']['pernoctas'][$j-1]['parcial'];
						}						
						$_SESSION['alb']['estancia']['pernoctas'][$i+1]['fecha_llegada'] = $fecha_calendario;
						$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada'] = $_POST['ant_fecha_calendario'];	
						$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida'] = $fecha_calendario;	
						$_SESSION['alb']['estancia']['pernoctas'][$i]['id_hab'] = $_POST['id_habitacion'];
						$_SESSION['alb']['estancia']['pernoctas'][$i]['parcial'] = 1;
						break;
//						$_SESSION['alb']['estancia']['pernoctas'][$i]
					}else{
						$_SESSION['alb']['estancia']['pernoctas'][$i]['id_hab'] = $_POST['id_habitacion'];
						$_SESSION['alb']['estancia']['pernoctas'][$i]['parcial'] = 0;
					}
				}	
			}									
		}
		if(!isset($_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['id_hab']) || $_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['id_hab'] == ""){
			$avisa_seleccion_hab = "true";
		}			
		if(!isset($_POST['tipo']) || $_POST['tipo'] == ""){
		//Si ha cambiado el numero de pernoctas : 
			if(resta_fecha($_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_saiida'],establecer_fecha($_GET['fecha_llegada'],intval($_POST['pernocta']))) > 0 && $_SESSION['alc']['estancia']['pernoctas'][intval(count($_SESSION['alb']['estancia']['pernoctas']))-1]['id_hab'] != ""){			
				
				//consulta a la base de datos para comprobar que pueda realizar la estancia en la misma habitación

				$sql_pernocta_mayor = "select * from pernocta where DNI_Cl <> '".$_POST['dni_ant']."' and Id_Hab like '".$_SESSION['alb']['estancia']['pernoctas'][intval(count($_SESSION['alb']['estancia']['pernoctas']))-1]['id_hab']."' and Fecha_Llegada <= '".establecer_fecha($_GET['fecha_llegada'], $_POST['pernocta']-1)."' and Fecha_Salida >'".establecer_fecha($_GET['fecha_llegada'], $_POST['pernocta'])."'";							
				$result_pernocta_mayor = mysql_query($sql_pernocta_mayor);
				$sql_num_camas = "select Camas_Hab from habitacion where Id_Hab like '".$_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['id_hab']."'";				
				$result_num_camas = mysql_query($sql_num_camas);				
				$fila_num_camas = mysql_fetch_array($result_num_camas);
				if(mysql_num_rows($result_pernocta_mayor) >= $fila_num_camas['Camas_Hab']){					
					$fila_pernocta_mayor = mysql_fetch_array($result_pernocta_mayor);
					$indice_sesion  = intval(count($_SESSION['alb']['estancia']['pernoctas']))-1;
					$_SESSION['alb']['estancia']['pernoctas'][$indice_sesion]['fecha_llegada'] = $_SESSION['alb']['estancia']['pernoctas'][intval($indice_sesion)-1]['fecha_salida'];
					$_SESSION['alb']['estancia']['pernoctas'][$indice_sesion]['fecha_salida'] = establecer_fecha($_SESSION['alb']['estancia']['pernoctas'][$indice_sesion]['fecha_llegada'],1);
					echo "<script>alert('Debe seleccionar otra habitacion para poder continuar la estancia');</script>";	
					$avisa_seleccion_hab = "";
					$fecha_calendario = $_SESSION['alb']['estancia']['pernoctas'][$indice_sesion]['fecha_llegada'];
					$sw_calendario_inicial = true;
				}else{								
					$_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_salida'] = establecer_fecha($_SESSION['alb']['estancia']['pernoctas'][intval(count($_SESSION['alb']['estancia']['pernoctas'])-1)]['fecha_salida'],1);	
				}
			}else{									
				$_SESSION['alb']['estancia']['pernoctas'][intval(count($_SESSION['alb']['estancia']['pernoctas']))-1]['fecha_salida'] = establecer_fecha($_GET['fecha_llegada'],$_POST['pernocta']);
				if(resta_fecha($_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_llegada'],establecer_fecha($_GET['fecha_llegada'],intval($_POST['pernocta']))) <= 0 ){					
					for($i=0;$i<count($_SESSION['alb']['estancia']['pernoctas']);$i++){
						if($_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada'] == $_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida'] || resta_fecha($_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada'],$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida']) <=0){								
								array_splice($_SESSION['alb']['estancia']['pernoctas'],$i);								if(resta_fecha($fecha_calendario,$_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_llegada'])!= 0){
									$fecha_calendario = $_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_llegada'];		
									$sw_calendario_inicial = true;
								}
								break;
						}
					}	
				}
			}
		}
	}				

	//Construccion y ejecución de consultas

		if(isset($_POST['tipo']) && $_POST['tipo']!=""){
			if(isset($_POST['sub']) && $_POST['sub'] == "true"){
				$fecha_llegada = $_POST['fecha_ant'];
				$fecha_salida = establecer_fecha($fecha_llegada , $_POST['pernocta']);
				if(isset($_GET['action']) && $_GET['action'] == "baja"){
					$sql_baja = "delete from ";
					 //print("dentro de aki delete");
					switch(strtolower($_POST['tipo'])){
                                        
						case "a":
							$sql_baja .= " pernocta where DNI_Cl like '".$_POST['dni_ant']."' and Fecha_Llegada = '".$_GET['fecha_llegada']."' ";
							break;
						case "p":
							$sql_baja .= "pernocta_p where DNI_Cl like '".$_POST['dni_ant']."' and Fecha_Llegada like '".$_GET['fecha_llegada']."'";
					}				 
					if(mysql_query($sql_baja)){
						$_SESSION['alb'] = Array();
						//echo "<script>alert('Se ha eliminado la estancia correctamente.');location.href='index.php';</script>";		
						echo "<script>location.href='index.php';</script>";						
					}else{
						$_SESSION['alb'] = Array();
						echo "<script>alert('La estancia no se ha podido eliminar con éxito.');location.href='index.php';</script>";						
					}
				}else{ 
					switch(strtolower($_POST['tipo'])){
						case "a":
							if(isset($_GET['action']) && $_GET['action'] == "modificar"){			
								if (isset($_POST['pernocta'])){$per=$_POST['pernocta'];}else {$per=$_POST['pernoctamodif'];}
								
								//print($per);
								if(count($_SESSION['alb']['estancia']['pernoctas']) < count($_SESSION['alb']['estancia']['original'])){
										
									$sql_update = "update pernocta set PerNocta ='".$per."' , Id_Hab='".$_POST['id_habitacion']."' , Fecha_Salida = '".$fecha_salida."'";
									if(isset($_POST['servicio']) && trim($_POST['servicio'])!=""){
										$sql_update .= ", Id_Servicios = '".$_POST['servicio']."'";
									}
									$sql_update .= " where DNI_Cl like '".$_POST['dni_ant']."' and Id_Hab like '".$_POST['id_habitacion_ant']."'  and Fecha_Llegada like '".$_POST['fecha_ant']."'";
								}else{
									$sql_update = "update pernocta set PerNocta = ".$per." , Id_Hab='".$_POST['id_habitacion']."' , Fecha_Salida = '".$fecha_salida."'";
									if(isset($_POST['servicio']) && trim($_POST['servicio'])!=""){
										$sql_update .= ", Id_Servicios = '".$_POST['servicio']."'";
									}
									$sql_update .= " where DNI_Cl like '".$_POST['dni_ant']."' and Id_Hab like '".$_POST['id_habitacion_ant']."'  and Fecha_Llegada like '".$_POST['fecha_ant']."'";									
								}
							break;}
						case "p":
								$sql_update = "update pernocta_p set PerNocta = ".$_POST['pernocta']." , Id_Hab='".$_POST['id_habitacion']."' , Fecha_Salida = '".$fecha_salida."' , Lesion = '".$_POST['lesionado']."' , M_P = '".$_POST['modo_peregrinacion']."'";
								if(isset($_POST['servicio']) && trim($_POST['servicio'])!=""){
									$sql_update .= ", Id_Servicios = '".$_POST['servicio']."'";
								}
								$sql_update.= " where DNI_Cl like '".$_POST['dni_ant']."' and Id_Hab like '".$_POST['id_habitacion_ant']."'  and Fecha_Llegada like '".$_POST['fecha_ant']."' ";								
						}
					//ALBERTO!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------!!!!-------------------------------------------------------
					
					if ($_POST['tipo'] == 'a'){
						$sql_al = "SELECT * FROM pernocta WHERE DNI_Cl = '".$_POST['dni_ant']."' AND Id_Hab = '".$_POST['id_habitacion']."' AND (Fecha_Salida = '".$fecha_llegada."' OR Fecha_Llegada = '".$fecha_salida."')";
						//echo "<br>sql al = ".$sql_al;
						$res_al = mysql_query($sql_al);
						// Si hay resultados, hemos de unir pernoctas
						if (mysql_num_rows($res_al) > 0){
							$fecha_llegada_al = $fecha_llegada;
							$fecha_salida_al = $fecha_salida;
							$sql_al_del = Array();
							$fecha_llegada_al = $fecha_llegada;
							$fecha_salida_al = $fecha_salida;
							while ($fila_al = mysql_fetch_array($res_al)){
								if ($fila_al['Fecha_Llegada'] == $fecha_salida){
									//echo "<br>Cambio fecha_salida = ".$fila_al['Fecha_Salida'];
									$pernocta_tot_al = $per + $fila_al['PerNocta'];
									$fecha_salida_al = $fila_al['Fecha_Salida'];
									$sql_al_del[count($sql_al_del)] = "DELETE FROM pernocta WHERE DNI_Cl = '".$fila_al['DNI_Cl']."' AND Fecha_Llegada = '".$fila_al['Fecha_Llegada']."' AND Id_Hab = '".$fila_al['Id_Hab']."'";
								}
								if ($fila_al['Fecha_Salida'] == $fecha_llegada){
									//echo "<br>Cambio fecha_llegada = ".$fila_al['Fecha_Llegada'];
									$pernocta_tot_al = $per + $fila_al['PerNocta'];
									$fecha_llegada_al = $fila_al['Fecha_Llegada'];
									$sql_al_del[count($sql_al_del)] = "DELETE FROM pernocta WHERE DNI_Cl = '".$fila_al['DNI_Cl']."' AND Fecha_Llegada = '".$fila_al['Fecha_Llegada']."' AND Id_Hab = '".$fila_al['Id_Hab']."'";
								}
							}
							
							//Elimino todas las pernoctas que voy a sobreescribir
							for ($i=0; $i<count($sql_al_del);$i++){
								//echo "<br> delete $i : ".$sql_al_del[$i];
								if (!mysql_query($sql_al_del[$i])){echo "<script>alert('Fallo al Actualizar'); window.location.href='?pag=gdh.php'</script>";}
							}
							//Hago el update con las nuevas fechas de salida 
							$sql_update_al = "UPDATE pernocta SET Id_Hab = '".$_POST['id_habitacion']."', Fecha_Llegada='".$fecha_llegada_al."', Fecha_Salida='".$fecha_salida_al."', PerNocta='".$pernocta_tot_al."' WHERE  DNI_Cl like '".$_POST['dni_ant']."' and Id_Hab like '".$_POST['id_habitacion_ant']."'  and Fecha_Llegada like '".$_POST['fecha_ant']."'";
							//echo "<BR> - QRY-Update = ".$sql_update_al;
							if (!mysql_query($sql_update_al)){echo "<script>alert('Fallo al Actualizar'); window.location.href='?pag=gdh.php'</script>";}
							echo "<script> window.location.href='?pag=gdh.php';</sctipt>";
							exit();
						}
					}

					//FIN ALBERTO!!!!___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________

					
					if(is_array($sql_update)){
						if(isset($sql_update_delete) || count($sql_update_delete) > 0){
							for($i=0;$i<count($sql_update_delete);$i++){
								mysql_query($sql_update_delete[$i]);
							}
						}
						for($i=0;$i<count($sql_update);$i++){
							if(mysql_query($sql_update[$i]) == false){								
									$_SESSION['alb'] = Array();
									$_SESSION['per'] = Array();
									$_SESSION['gdh']['dia'] = trim(date("d"));
									$_SESSION['gdh']['mes'] = trim(date("m"));
									$_SESSION['gdh']['anio'] = trim(date("Y"));
									echo "<script>alert('La modificación no se ha podido realizar correctamente.');location.href='index.php';</script>";
									break;
							}							
						}
					}else{
						if(isset($sql_update_delete) || count($sql_update_delete) > 0){
							for($i=0;$i<count($sql_update_delete);$i++){
								mysql_query($sql_update_delete[$i]);
							}
						}
						if(mysql_query($sql_update) == false){
							$_SESSION['alb'] = Array();
							$_SESSION['per'] = Array();						
							$_SESSION['gdh']['dia'] = trim(date("d"));
							$_SESSION['gdh']['mes'] = trim(date("m"));
							$_SESSION['gdh']['anio'] = trim(date("Y"));
							echo "<script>alert('La modificación no se ha podido realizar correctamente.');location.href='index.php';</script>";
						}	
					}
					$_SESSION['gdh']['dia'] = trim(date("d"));
					$_SESSION['gdh']['mes'] = trim(date("m"));
					$_SESSION['gdh']['anio'] = trim(date("Y"));
					//echo "<script>alert('La modificacion se ha realizado correctamente.');location.href='index.php';</script>";
					echo "<script>location.href='index.php';</script>";
				}
			}
			$_SESSION['alb'] = Array();
		}else{



			//Formulario de modificación de estancia
			
	  $hreff="?pag=modificacion_estancia.php&tipo=".$_GET['tipo']."&dni=".$_GET['dni']."&fecha_llegada=".$_GET['fecha_llegada']."&fecha_salida=".$_GET['fecha_salida'];
			
			
?>

<form name="formu_mod_estancia" action="<?print($hreff)?>" method="POST">
	<input type="hidden" name="avisa_seleccion_habitacion" value="<?echo $avisa_seleccion_hab;?>">
	<input type="hidden" name="tipo_auxiliar" value="<?echo $_GET['tipo'];?>">
	<input type="hidden" name="ant_fecha_calendario" value="<?
							if(isset($fecha_calendario) && $fecha_calendario != ""){
								echo $fecha_calendario;
							}else{
								if(isset($_POST['fecha_llegada'])){
									echo $_POST['fecha_llegada'];		
								}else{
									echo date("Y-m-d");
								}
							}								   
						?>">
	<input type="hidden" name="sub"  
	<?
			if(isset($_POST['sub']) && $_POST['sub'] == "true"){
				echo "value=\"false\"";
			}else{
				echo "value=\"true\"";
			}
		 ?>
	>	
	<input type="hidden" name="sub_sesion_inicio" value="
	<?
		if(isset($_POST['sub_sesion_inicio']) && $_POST['sub_sesion_inicio'] != ""){
			echo "true";
		}else{
			echo "";
		}
	?>	">	
	<input type="hidden" name="fecha_cal" value="<?
			if(isset($_GET['fecha_cal']) && $_GET['fecha_cal']!=""){
				echo $_GET['fecha_cal'];
			}else if(isset($_POST['fecha_cal']) && $_POST['fecha_cal']!=""){
				echo $_POST['fecha_cal'];
			}
		?>">
	<input type="hidden" name="tipo" value="">
	<input type="hidden" name="id_cama">
	<input type="hidden" name="asignada" value="0">
	<input type="hidden" name="id_habitacion" value="">
	<input type="hidden" name="cambio_habitacion" value="">
	<input type="hidden" name="fecha_salida" value="<? echo $_GET['fecha_salida'];?>">
	<input type="hidden" name="estancia_parcial" value="<?
			if(isset($_GET['estancia_parcial']) && $_GET['estancia_parcial']!= ""){
				echo trim($_GET['estancia_parcial']);
			}else{
				echo 0;
			}
			?>">




            		
	<?
		//MODIFICACIÓN Estancia alberguista
		
		
		
		
		
		$sw_pernocta = false;
		if(strtolower($_GET['tipo']) == "a"){
			if(isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']==true){
				
				//Por si hay mas de una pernocta, primero encuentro cual seria la fecha de llegada total. Con ella luego alamacenaremos en el array (session['alb']) los datos de las pernoctas
				$salir_al = false;
				$fecha_legada_temp = $_GET['fecha_llegada'];
				while (!$salir_al){
					$qry_al_temp = "SELECT * FROM pernocta WHERE Fecha_Salida = '".$fecha_legada_temp."' AND DNI_Cl = '".$_GET['dni']."'";
					$res_al_temp = mysql_query($qry_al_temp);
					if (mysql_num_rows($res_al_temp) > 0){
						$fila_al_temp = mysql_fetch_array($res_al_temp);
						$fecha_legada_temp = $fila_al_temp['Fecha_Llegada'];
					}else{
						$salir_al = true;
					}
				}

				$salir_al = false;
				$_SESSION['alb']=Array();
				//En el array 'alb' guardaremos todas las partes de la pernocta total del cliente
				while (!$salir_al){
					$qry_al_temp = "SELECT * FROM pernocta WHERE Fecha_Llegada = '".$fecha_legada_temp."' AND DNI_Cl = '".$_GET['dni']."'";
					$res_al_temp = mysql_query($qry_al_temp);
					$i = count($_SESSION['alb']['estancia']['pernoctas']);
					if (mysql_num_rows($res_al_temp) > 0){
						$fila_al_temp = mysql_fetch_array($res_al_temp);
						$fecha_legada_temp = $fila_al_temp['Fecha_Salida'];
						if (!isset($_SESSION['alb']['estancia']['dni'])){
							$_SESSION['alb']['estancia']['dni'] = trim($fila_al_temp['DNI_Cl']);
						}
						$_SESSION['alb']['estancia']['original'][$i]['fecha_llegada'] = trim($fila_al_temp['Fecha_Llegada']);
						$_SESSION['alb']['estancia']['original'][$i]['fecha_salida'] = trim($fila_al_temp['Fecha_Salida']);
						$_SESSION['alb']['estancia']['original'][$i]['id_hab'] = trim($fila_al_temp['Id_Hab']);
						$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada'] = trim($fila_al_temp['Fecha_Llegada']);
						$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida'] = trim($fila_al_temp['Fecha_Salida']);
						$_SESSION['alb']['estancia']['pernoctas'][$i]['id_hab'] = trim($fila_al_temp['Id_Hab']);
						//echo "<br> Registro $i = ".$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada']." <-> ".$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida']." ||=-> ".$_SESSION['alb']['estancia']['pernoctas'][$i]['id_hab'];
					}else{
						$salir_al = true;
					}
				}


				$sql_inicial = "select * from pernocta where DNI_Cl like '".$_GET['dni']."' and Fecha_Llegada = '".$_GET['fecha_llegada']."'  order by Fecha_Llegada";
				$result_inicial = mysql_query($sql_inicial);
				$fila_inicial = mysql_fetch_array($result_inicial);
				//print($sql_inicial);
				
				
				$sql_anterior = "select * from pernocta where DNI_Cl like '".$_GET['dni']."' and Fecha_Salida = '".$_GET['fecha_llegada']."'  order by Fecha_Llegada";
				$result_anterior = mysql_query($sql_anterior);
				$fila_anterior = mysql_fetch_array($result_anterior);
				//print($sql_anterior);
				
				$sql_posterior = "select * from pernocta where DNI_Cl like '".$_GET['dni']."' and Fecha_Llegada = '".$_GET['fecha_salida']."'  order by Fecha_Llegada";
				$result_posterior = mysql_query($sql_posterior);
				$fila_posterior = mysql_fetch_array($result_posterior);
				//print($sql_posterior);
				
				$sql_pernoctas = "select SUM(PerNocta) as \"suma_pernocta\" from pernocta where DNI_Cl like '".$_GET['dni']."' and Fecha_Llegada = '".$_GET['fecha_llegada']."' ";
				
					if(!$result_inicial = mysql_query($sql_inicial)){
						echo "<script>alert('La estancia no existe en el sistema.');location.href=''</script>";
					}else{
						if(mysql_num_rows($result_inicial) < 1){
							echo "<script>alert('La estancia no existe en el sistema.');location.href=''</script>";
						}
						$result_pernoctas = mysql_query($sql_pernoctas);
						$fila_pernoctas = mysql_fetch_array($result_pernoctas);
		
			//Listado de estancias
				?>
		<table border='0' class='tabla_detalles' cellspacing="0" cellpadding="0" width="390px" style="background-color: #F4FCFF;">
			<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
						<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
						<div style="width:331px;;height:25px;text-align:center;float:left;" class="champi_centro">
						<div class="titulo" style="text-align:center;">Cambios de Habitación</div>
						</div>
						<div style="height:25px;width:30px;" class="champi_derecha">&nbsp;</div>
					</td>
				</thead>
				<tbody>
				<tr><td style="padding:0px 0px 0px 0px;">
					<div id="tabla_listado" class="tableContainer" style='width:389px;height:200px;'>
							<table border="0" cellpadding="0" cellspacing="0" class="scrollTable" width="372px" >
									<thead class="fixedHeader" cellspacing="0" width="100%"  class="scrollTable" >
											<th style="font-size:12px;">Habitación</th>
											<th style="font-size:12px;">Fecha Llegada</th>
											<th style="font-size:12px;">Fecha Salida</th>
											<th style="font-size:12px;">Pernocta</th>
									</thead>
									<tbody class="scrollContent" style="font-size:12px;">
									<?
                                    
								/*	if(!isset($_SESSION['alb']['estancia']['dni'])){
                                      //print("ha entrado ".count($_SESSION['alb']['estancia']['pernoctas']));
										for($i=0;$i<mysql_num_rows($result_inicial);$i++)	{
                                            if(!isset($_SESSION['alb']['estancia']['pernoctas'][$i]) || ($_SESSION['alb']['estancia']['pernoctas'][$i]['id_hab']== "")){
												$_SESSION['alb']['estancia']['dni'] = trim($fila_inicial['DNI_Cl']);
												$_SESSION['alb']['estancia']['original'][$i]['fecha_llegada'] = trim($fila_inicial['Fecha_Llegada']);
												$_SESSION['alb']['estancia']['original'][$i]['fecha_salida'] = trim($fila_inicial['Fecha_Salida']);
												$_SESSION['alb']['estancia']['original'][$i]['id_hab'] = trim($fila_inicial['Id_Hab']);
												$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada'] = trim($fila_inicial['Fecha_Llegada']);
												$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida'] = trim($fila_inicial['Fecha_Salida']);
												$_SESSION['alb']['estancia']['pernoctas'][$i]['id_hab'] = trim($fila_inicial['Id_Hab']);
											}
											
											$c=count($_SESSION['alb']['estancia']['pernoctas']);
                                            for($i=0;$i<mysql_num_rows($result_anterior);$i++){		
												$_SESSION['alb']['estancia']['original'][$c]['fecha_llegada'] = trim($fila_anterior['Fecha_Llegada']);
												$_SESSION['alb']['estancia']['original'][$c]['fecha_salida'] = trim($fila_anterior['Fecha_Salida']);
												$_SESSION['alb']['estancia']['original'][$c]['id_hab'] = trim($fila_anterior['Id_Hab']);
												$_SESSION['alb']['estancia']['pernoctas'][$c]['fecha_llegada'] = trim($fila_anterior['Fecha_Llegada']);
												$_SESSION['alb']['estancia']['pernoctas'][$c]['fecha_salida'] = trim($fila_anterior['Fecha_Salida']);
												$_SESSION['alb']['estancia']['pernoctas'][$c]['id_hab'] = trim($fila_anterior['Id_Hab']);
												$c++;
                                    		}
											$c=count($_SESSION['alb']['estancia']['pernoctas']);
											for($i=0;$i<mysql_num_rows($result_posterior);$i++){
												$_SESSION['alb']['estancia']['original'][$c]['fecha_llegada'] = trim($fila_posterior['Fecha_Llegada']);
												$_SESSION['alb']['estancia']['original'][$c]['fecha_salida'] = trim($fila_posterior['Fecha_Salida']);
												$_SESSION['alb']['estancia']['original'][$c]['id_hab'] = trim($fila_posterior['Id_Hab']);
												$_SESSION['alb']['estancia']['pernoctas'][$c]['fecha_llegada'] = trim($fila_posterior['Fecha_Llegada']);
												$_SESSION['alb']['estancia']['pernoctas'][$c]['fecha_salida'] = trim($fila_posterior['Fecha_Salida']);
												$_SESSION['alb']['estancia']['pernoctas'][$c]['id_hab'] = trim($fila_posterior['Id_Hab']);
                                    		}
									 	}
									}
                                    //print("no dentro ".count($_SESSION['alb']['estancia']['pernoctas']));
*/

									
									for($i=0;$i<count($_SESSION['alb']['estancia']['pernoctas']);$i++)	{
									  
										$fecha_des=split("-",$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada']);

										$dialleg=$fecha_des[2];
										$meslleg=$fecha_des[1];
										$aniolleg=$fecha_des[0];

										$fecha_des=split("-",$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida']);

										$diasal=$fecha_des[2];
										$messal=$fecha_des[1];
										$aniosal=$fecha_des[0];


										$hrefm="?pag=modificacion_estancia.php&tipo=a&dni=".$_SESSION['alb']['estancia']['dni']."&fecha_llegada=".$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada']."&fecha_salida=".$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida'];
										// print($hrefm);
										echo "<tr class='texto_listados'  onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'>";
										echo "<td onclick=window.location.href('".$hrefm."');>".$_SESSION['alb']['estancia']['pernoctas'][$i]['id_hab']."</a></td>";
										echo "<td onclick=window.location.href('".$hrefm."');>".$dialleg."-".$meslleg."-".$aniolleg."</a></td>";
										echo "<td onclick=window.location.href('".$hrefm."');>".$diasal."-".$messal."-".$aniosal."</a></td>";
										echo "<td onclick=window.location.href('".$hrefm."');>".resta_fecha($_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_llegada'],$_SESSION['alb']['estancia']['pernoctas'][$i]['fecha_salida'])."</a></td>";
										echo "</tr>";

									}
										
									?>
						</tbody>
					</table>					
					</div>
					</td></tr>
				</tbody>
		</table>
		<br>		
		<?
			//Fin listado de estancias


			$sql_doc = "select Tipo_documentacion from cliente where DNI_Cl like '".$_GET['dni']."'";
			$res_doc  = mysql_query($sql_doc);
			$fila_doc = mysql_fetch_array($res_doc);			
			$fecha_ant_split = split("-",$_POST['fecha_ant']);

		?>
		<input type="hidden" name="id_habitacion_ant" value="<?echo $fila_inicial['Id_Hab'];?>">		
		<input type="hidden" name="fecha_ant" value="<?echo $_GET['fecha_llegada'];?>">
		<input type="hidden" name="ant_dia_fecha_llegada" value="<?
			if(isset($_POST['fecha_ant']) && $_POST['fecha_ant'] !=""){
				echo $fecha_ant_split[2];
			}else{
				echo date("d");
			}
		?>">
		<input type="hidden" name="ant_mes_fecha_llegada" value="<?
			if(isset($_POST['fecha_ant']) && $_POST['fecha_ant'] !=""){
				echo $fecha_ant_split[1];
			}else{
				echo date("m");
			}
		?>">
		<input type="hidden" name="ant_anyo_fecha_llegada" value="<?
			if(isset($_POST['fecha_ant']) && $_POST['fecha_ant'] !=""){
				echo $fecha_ant_split[0];
			}else{
				echo date("Y");
			}
		?>">		
		<table border='0' class='tabla_detalles' cellspacing="0" cellpadding="0" width="390px">
		
			
		
        <input type="hidden" name="pernoctamodif" value="<?
									if(isset($_POST['pernocta']) && $_POST['pernocta']!=""){
										echo $_POST['pernocta'];
									}else{
										echo $fila_pernoctas['suma_pernocta'];
									}
								?>">
							
            
        <input type="hidden" name="dni_ant" value='<?echo $_GET['dni'];?>'>
        <input type="hidden" name="fecha_llegada" value='<?echo $_GET['fecha_llegada'];?>'>
				<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
						<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
						<div style="width:330px;;height:25px;text-align:center;float:left;" class="champi_centro">
						<div class="titulo" style="text-align:center;">Modificar Estancia</div>
						</div>
						<div style="height:25px;width:30px;float:left;" class="champi_derecha">&nbsp;</div>
					</td>
				</thead>
				<tr>
				<td style="border-left:solid 1px #3F7BCC;border-bottom:solid 1px #3F7BCC;border-right:solid 1px #3F7BCC;background-color:#F4FCFF;">
				
				
				
				
				
				  <table border="0" width="375">
                    <tr height='25'>
				      <td>&nbsp;</td>
                      <td colspan="3" align="left">
					       
						<table border=0 cellspacing="0" cellpadding="0"><tr>
						<td align='left' class='label_formulario'>Tipo :</td>
						<td align='left' class="texto_detalles" width="72"><?echo $fila_doc['Tipo_documentacion'];?></td>
						<td class="label_formulario" style="padding-left:23px;">D.N.I. :</td>
						<td align="right" class="texto_detalles"><?echo $_GET['dni'];?></td>
						</tr></table>
					</td>
				    </tr>
				    <tr height='25'>
					   <td>&nbsp;</td>
                        <td align='left' width="140" class='label_formulario'>Fecha de Llegada:</td>

					
						
							<td class="texto_detalles" align ="left" width="110">
								<?
									$fecha_des=split("-",$_GET['fecha_llegada']);

               						$dialleg=$fecha_des[2];
               						$meslleg=$fecha_des[1];
               						$aniolleg=$fecha_des[0];
								
								
								
								
								
								
								print($dialleg."-".$meslleg."-".$aniolleg);?>
							</td>
						</tr>						
					
					</td>
				</tr>				
				<tr>
					<td>&nbsp;</td>
					<td align="left">
						<label class='label_formulario' >Pernoctas : </label>
					</td>
					<td align="left">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
                                   

                                  	<?
                                  		 //Si es la última pernocta dejo modificar el número de noches con las flechas
                                        if(    ((mysql_num_rows($result_inicial))>0) and ((mysql_num_rows($result_posterior))<=0)){

                                    ?>

                                <td align="left">
								<input type="text" align="left" class='input_formulario' name="pernocta" value="<?
									if(isset($_POST['pernocta']) && $_POST['pernocta']!=""){
										echo $_POST['pernocta'];
									}else{
										echo $fila_pernoctas['suma_pernocta'];
									}
								?>" size="3" readonly >
							    </td>
							    <td align="left">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr height="12" width="12">
										<td style="padding:0px 0px 0px 0px;" valign="bottom"><img src="../imagenes/botones/flecha_up.jpg" alt="Aumentar el número de pernoctas en 1" border="0"
										<?
											if(!$sw_pernocta){
												echo "onclick=\"if(document.forms[0].avisa_seleccion_habitacion.value != 'true'){cambio_camas('+1');}else{alert('Debe seleccionar una habitación');}\"";
											}

										?>  </></td>
                                                </tr>
									<tr height="12" width="12">
										<td style="padding:0px 0px 0px 0px;" valign="top"><img src="../imagenes/botones/flecha_down.jpg" alt="Disminuir el número de pernoctas en 1" border="0"
										<?
											if(!$sw_pernocta){
												echo "onclick=\"if(document.forms[0].avisa_seleccion_habitacion.value != 'true'){cambio_camas('-1');}else{alert('Debe seleccionar una habitación');}\"";
											}
										?>


                                      /></td>
									</tr>
        	                      </table>



                                        <?


										}else {?>
										<td align="left" class="texto_detalles">
											<input type='text' name='pernocta' class='input_formulario' size='3' readonly value='<? if(isset($_POST['pernocta']) && $_POST['pernocta']!=""){
										       echo $_POST['pernocta'];
									        }else{
									         	echo $fila_pernoctas['suma_pernocta'];
									     }
                                        ?>'><?
										}

                                        ?>









				
						</td>
					</tr>					
					</table>
					</td>
				</tr>

				<?
					//Comprueba que estén activados los servicios. Si están activados muestra un campo de selección con los servicios disponibles, y selecciona el que esté registrado en la pernocta
					$servicios_activados = table_exists(Tipo_Servicios,$db);
					if($servicios_activados){
						$sql_servicios = "select Id_Servicios , Descripcion from Tipo_Servicios";
						$result_servicios = mysql_query($sql_servicios);
					?>
				<tr height='25'>
					<td>&nbsp;</td>
					<td colspan="3" align="left">
						<table border=0 cellspacing="0" cellpadding="0">
						<tr>
						<td class="label_formulario" style="padding:0px 0px 0px 0px;";>Servicios :</td>
						<td align="right" style="padding-left:93px;">
						<select name="servicio">
						<?
							$sw_selected = false;
							while($fila_servicios = mysql_fetch_array($result_servicios)){
								echo "<option value=\"".$fila_servicios['Id_Servicios']."\"";
								if(!$sw_selected && isset($_POST['servicio']) && trim($_POST['servicio']) != ""){
									if(trim($_POST['servicio']) == trim($fila_servicios['Id_Servicios'])){
										echo " selected ";
										$sw_selected = true;
									}
								}else{
									if(!$sw_selected && trim($fila_servicios['Id_Servicios']) == trim($fila_inicial['Id_Servicios'])){
										echo " selected ";
										$sw_selected = true;
									}else if(!$sw_selected && trim($fila_servicios['Id_Servicios']) == "sa"){
										echo " selected ";
										$sw_selected = true;
									}
								}
								echo ">".trim($fila_servicios['Descripcion'])."</option>";
							}
						?>
						</select>
						</td>
						</tr></table>
					</td>
				</tr>
				<?}?>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>				
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
				<td >&nbsp;</td>
					<td colspan="4" align="center" >
						<div >
							<a href="#" onClick="if(document.forms[0].avisa_seleccion_habitacion.value != 'true'){document.forms[0].action += '&tipo=<?echo $_GET['tipo'];?>&action=modificar&dni=<?echo $_GET['dni'];?>&fecha_llegada=<?echo $_GET['fecha_llegada'];?>&fecha_salida=<?print($_GET['fecha_salida']);?>';document.forms[0].tipo.value ='<?echo $_GET['tipo']?>';document.forms[0].sub.value = 'true';document.forms[0].submit();}else{alert('Debe seleccionar una habitación');}"><img src="../imagenes/botones-texto/modificar.jpg" alt="Realizar Modificación de la estancia" border="0"/></a>&nbsp;
							<a href="#" onClick="location.href='index.php'"><img src="../imagenes/botones-texto/cancelar.jpg" border="0"  alt="Cancelar Modificación de Estancia" /></a>&nbsp;	
							
							<?
                                if(    ((mysql_num_rows($result_inicial))>0) and ((mysql_num_rows($result_posterior))<=0)){

                            ?>
							
							
							
							
							
							<a href="#" onClick="document.forms[0].tipo.value ='<?echo $_GET['tipo']?>';document.forms[0].action += '&tipo=<?echo $_GET['tipo'];?>&dni=<?echo $_GET['dni'];?>&fecha_llegada=<?echo $_GET['fecha_llegada'];?>&action=baja';document.forms[0].sub.value = 'true';document.forms[0].submit();"><img src="../imagenes/botones-texto/eliminar.jpg" border="0" alt="Eliminar la Estancia" /></a>	
							
							<?
                                }
                            ?>
						</div>
					</td>
			</tr>
			</table>	
			</td>
			</tr>			
		</table>

		<?			
			//FIN MODIFICACIÓN Estancia alberguista 
			
			
			$qry_dist = "SELECT * FROM habitacion inner join tipo_habitacion WHERE (habitacion.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab AND tipo_habitacion.reservable = 's') ORDER BY Id_Hab";
			}
			}else{	//Muestro una ventana de error de permisos de acceso a la pagina	
				 echo "<div class='error'>
						NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
					</div></div></div></div>";
					return;
			}
		}else{
			if(strtolower($_GET['tipo']) == "p"){
			  //print($fecha_calendario."holaaaaaaa952");

				//MODIFICACIÓN Estancia peregrino
				//Se comprueba que tenga permisos para acceder a la sección
				if(isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']==true) {					
				$sql_inicial = "select * from pernocta_p where DNI_Cl like '".$_GET['dni']."' && Fecha_Llegada like '".$_GET['fecha_llegada']."'";
				if($result_inicial = mysql_query($sql_inicial)){
					if(mysql_num_rows($result_inicial) < 1){
						echo "<script>alert('La estancia no existe en el sistema.');location.href=''</script>";
					}
					$fila_inicial = mysql_fetch_array($result_inicial);
				}else{
					echo "<script>alert('La estancia no existe en el sistema.');location.href=''</script>";
				}				
				$sql_doc = "select Tipo_documentacion from cliente where DNI_Cl like '".$fila_inicial['DNI_Cl']."'";
				$res_doc  = mysql_query($sql_doc);
				$fila_doc = mysql_fetch_array($res_doc);	
				if(!isset($_SESSION['per']['estancia']['pernoctas'][0]) || ($_SESSION['per']['estancia']['pernoctas'][0]['id_hab']== "")){
					$_SESSION['per']['estancia']['dni'] = trim($fila_inicial['DNI_Cl']);
					$_SESSION['per']['estancia']['original'][0]['fecha_llegada'] = trim($fila_inicial['Fecha_Llegada']);
					$_SESSION['per']['estancia']['original'][0]['fecha_salida'] = trim($fila_inicial['Fecha_Salida']);
					$_SESSION['per']['estancia']['original'][0]['id_hab'] = trim($fila_inicial['Id_Hab']);
					$_SESSION['per']['estancia']['pernoctas'][0]['fecha_llegada'] = trim($fila_inicial['Fecha_Llegada']);
					$_SESSION['per']['estancia']['pernoctas'][0]['fecha_salida'] = trim($fila_inicial['Fecha_Salida']);
					$_SESSION['per']['estancia']['pernoctas'][0]['id_hab'] = trim($fila_inicial['Id_Hab']);
				}	
		?>
			<input type="hidden" name="id_habitacion_ant" value="<?echo $fila_inicial['Id_Hab'];?>">
			<input type="hidden" name="fecha_ant" value="<?if(isset($_POST['fecha_ant']) && $_POST['fecha_ant']!=""){
																				echo $_POST['fecha_ant'];
																			}else{
																				echo $_GET['fecha_llegada'];
																			}?>">
																			
		<input type="hidden" name="pernocta" value="<?echo $fila_inicial['PerNocta'];?>">
			<table border='0' class='tabla_detalles' cellspacing="0" cellpadding="0" width="390px">
				<thead>
					<td colspan='9' align='center' style="padding:0px 0px 0px 0px;">
					
						<div class="champi_izquierda">&nbsp;</div>
					
						<div class="champi_centro" style="width:330px;">Modificar Estancia</div>
						</div>
						<div class='champi_derecha'>&nbsp;</div>
					</td>
				</thead>
				<tr>
				<td style="border-left:solid 1px #3F7BCC;border-bottom:solid 1px #3F7BCC;border-right:solid 1px #3F7BCC;background-color:#F4FCFF;">
				<table border="0">
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Documentación :</td>
					<td align="left">
					<input type='text' size='1' readonly class='input_formulario' name='tipo_documentacion' value='<?echo $fila_doc['Tipo_documentacion'];?>' maxlength="1">	
					<input type='text' size='16' readonly class='input_formulario' name='dni_ant' value='<?echo $fila_inicial['DNI_Cl'];?>' maxlength="20"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Fecha de Llegada : </td>
					<td align="left">
					
					<?
					
					$fecha_des=split("-",$_GET['fecha_llegada']);

               						$dialleg=$fecha_des[2];
               						$meslleg=$fecha_des[1];
               						$aniolleg=$fecha_des[0];
								
								
								
								
								
								
							
					
					
					
					
					?>
								<input type='text' size='16' readonly class='input_formulario' name='fecha_llegada' value="<?	print($dialleg."-".$meslleg."-".$aniolleg);?>" maxlength="20">
					</td>
				</tr>
				<tr height="25">
					<td>&nbsp;</td><td align="left"><label class='label_formulario' >Modo de peregrinación : </label></td>
					<td align='left'>
						<select class='select_formulario' name="modo_peregrinacion">
								<option value="P"
								<?if($fila_inicial['M_P'] == "P"){echo " selected ";}?>
								>A Pie
								<option value="C"
								<?if($fila_inicial['M_P'] == "C"){echo " selected ";}?>
								>A Caballo
								<option value="B"
								<?if($fila_inicial['M_P'] == "B"){echo " selected ";}?>
								>En bicicleta
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align='left'><label class='label_formulario' >Lesionado : </label></td>
					<td align='left'><input type="checkbox" name="lesionado" value="S" 
					<?
							if(isset($_POST['lesionado']) && $_POST['lesionado'] == "S"){
								echo "checked";
							}else{
								if($fila_inicial['Lesion'] == "S"){
									echo "checked";
								}

							}
						?>
					>	
					</td>
				</tr>	
				<?
					//Si tiene los servicios activados se muestran los existentes y se selecciona el que esté registrado en la pernocta
					$servicios_activados = table_exists(Tipo_Servicios,$db);
					if($servicios_activados){
						$sql_servicios = "select Id_Servicios , Descripcion from Tipo_Servicios";
						$result_servicios = mysql_query($sql_servicios);
					?>
				<tr height='25'>
					<td>&nbsp;</td>
					<td colspan="3" align="left">
						<table border=0 cellspacing="0" cellpadding="0">
						<tr>
						<td class="label_formulario" style="padding:0px 0px 0px 0px;">Servicios :</td>
						<td align="right" style="padding-left:93px;">
						<select name="servicio">
						<?
							$sw_selected = false;
							while($fila_servicios = mysql_fetch_array($result_servicios)){
								echo "<option value=\"".$fila_servicios['Id_Servicios']."\"";
								if(!$sw_selected && isset($_POST['servicio']) && trim($_POST['servicio']) != ""){
									if(trim($_POST['servicio']) == trim($fila_servicios['Id_Servicios'])){
										echo " selected ";
										$sw_selected = true;
									}
								}else{
									if(!$sw_selected &&  trim($fila_servicios['Id_Servicios']) == trim($fila_inicial['Id_Servicios'])){
										echo " selected ";
										$sw_selected = true;
									}else	if(!$sw_selected && trim($fila_servicios['Id_Servicios']) == "sa"){
										echo " selected ";
										$sw_selected = true;
									}
								}
								echo ">".trim($fila_servicios['Descripcion'])."</option>";
							}
						?>
						</select>
						</td>
						</tr></table>
					</td>
				</tr>
				<?}?>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" >
						<div >
							<a href="#" onClick="if(document.forms[0].avisa_seleccion_habitacion.value != 'true'){document.forms[0].action += '&tipo=<?echo $_GET['tipo'];?>&action=modificar&dni=<?echo $_GET['dni'];?>&fecha_llegada=<?echo $_GET['fecha_llegada'];?>&fecha_salida=<?print($_GET['fecha_salida']);?>';document.forms[0].tipo.value ='<?echo $_GET['tipo']?>';document.forms[0].sub.value = 'true';document.forms[0].submit();}else{alert('Debe seleccionar una habitación');}"><img src="../imagenes/botones-texto/modificar.jpg"alt="Realizar Modificación de la estancia"  border="0"/></a>&nbsp;
							<a href="#" onClick="location.href='index.php'"><img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar Modificación de Estancia" border="0"/></a>&nbsp;	
							<a href="#" onClick="document.forms[0].tipo.value ='<?echo $_GET['tipo']?>';document.forms[0].action += '&tipo=<?echo $_GET['tipo'];?>&dni=<?echo $_GET['dni'];?>&fecha_llegada=<?echo $_GET['fecha_llegada'];?>&action=baja';document.forms[0].sub.value = 'true';document.forms[0].submit();"><img src="../imagenes/botones-texto/eliminar.jpg" alt="Eliminar la Estancia" border="0"/></a>	
						</div>
					</td>
			</tr>
			</table>	
			</td>
			</tr>
			</table>
<?
	//FIN MODIFICACIÓN Estancia peregrino

			}else{	//Muestro una ventana de error de permisos de acceso a la pagina	
				 echo "<div class='error'>
						NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
					</div></div></div></div>";
					return;
			}
			}
			
		}
		}
		if(isset($_POST['pernocta']) && $_POST['pernocta']!=""){
			$pernoctas = intval($_POST['pernocta']);
		}else{
			if(strtolower($_GET['tipo']) == "a"){
				$pernoctas = intval($fila_pernoctas['suma_pernocta']);
			}else if(strtolower($_GET['tipo']) == "p"){
				$pernoctas = intval($fila_inicial['PerNocta']);
			}
		}		
		
		if(!$sw_calendario && !$sw_calendario_inicial && (!isset($_POST['pernocta']) || $_POST['pernocta'] == "")){
			if(intval(resta_fecha(trim($fecha_calendario), trim($fila_inicial['Fecha_Llegada']))) > 0  || intval(resta_fecha(trim($fecha_calendario), trim($fila_inicial['Fecha_Salida']))) <= 0){
				$sw_calendario_inicial = true;
				$fecha_calendario = trim($fila_inicial['Fecha_Llegada']);						
			}
			$fecha_fin = split("-",establecer_fecha($_GET['fecha_llegada'],$pernoctas));
		}else{
			if($_GET['tipo'] == "a"&&!$sw_calendario && !$sw_calendario_inicial && (intval(resta_fecha(trim($fecha_calendario), trim($_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_salida']))) <= 0 || intval(resta_fecha(trim($fecha_calendario), trim($_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_llegada']))) > 0 || resta_fecha($fecha_calendario,establecer_fecha($_GET['fecha_llegada'],$_POST['pernocta'])) < 0)){
				$sw_calendario = true;								
				$fecha_calendario = $_SESSION['alb']['estancia']['pernoctas'][count($_SESSION['alb']['estancia']['pernoctas'])-1]['fecha_llegada'];
			}
			
		}		
		$split_fecha_cal = split("-",$fecha_calendario);

		//Si la fecha de calendario es incorrecta (Menor que la fecha de llegada, mayor que la de salida, etc) Se cambia el dia
/*
		if($sw_calendario_inicial){
			echo "<script>cambiar_dia(".$split_fecha_cal[2].",".$split_fecha_cal[1].",".$split_fecha_cal[0].");</script>";			
		}
		if($sw_calendario){						
			echo "<script>;cambiar_dia(".$split_fecha_cal[2].",".$split_fecha_cal[1].",".$split_fecha_cal[0].");</script>";			
		}*/
	?>

	</div>
	</div>
	<div id="caja_superior_derecha">
		<div id="caja_superior_derecha_a" style="display:block;">
			<div id="caja_habitaciones" style="display:block;">
<?php
											$fecha_llegada_original = $_GET['fecha_llegada'];
											if(intval($_POST['pernocta'] > 0)){
												$fecha_salida = establecer_fecha($_GET['fecha_llegada'],$_POST['pernocta']);
											}else{
												$fecha_salida = $_GET['fecha_salida'];
											}

										$qry_dist = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fecha_llegada."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab WHERE habitacion.Camas_Hab > 0) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab WHERE tipo_habitacion.reservable LIKE 'S' ";


										///DISTRIBUCIÓN DE HABITACIONES
										//Se Almacenan en un array los datos de todas las habitaciones existentes;
										//print($fecha_calendario."holaaaaaaa1181");
										$habita=Array();
										$max_camas=0;//Numero de camas que tiene la habitación mas grande
										$cont=0;
										//print($qry_dist);
										//Consultas para conocer qué habitaciones están ocupadas y de qué tipo son esos clientes
										$sql_hab_reserva = "select reserva.DNI_PRA as DNI_PRA , reserva.Fecha_Llegada as Fecha_Llegada, reserva.Id_Hab as Id_Hab, reserva.Num_Camas as Num_Camas, detalles.Fecha_Salida as Fecha_Salida from reserva LEFT JOIN detalles on detalles.DNI_PRA = reserva.DNI_PRA where reserva.DNI_PRA in (select DNI_PRA from detalles where !(detalles.Fecha_Llegada < '".$fecha_calendario."' and detalles.Fecha_Salida < '".$fecha_calendario."') and !(detalles.Fecha_Llegada > '".$fecha_salida."' and detalles.Fecha_Salida > '".$fecha_salida."')) and detalles.Fecha_Llegada in (select Fecha_Llegada from detalles where !(detalles.Fecha_Llegada < '".$fecha_calendario."' and detalles.Fecha_Salida < '".$fecha_calendario."') and !(detalles.Fecha_Llegada >= '".$fecha_salida."' and detalles.Fecha_Salida >= '".$fecha_salida."')) order by Id_Hab ";
										//print($sql_hab_reserva);
										
										$sql_hab_pernocta = "select * from pernocta where !(Fecha_Llegada < '".$fecha_calendario."' and Fecha_Salida < '".$fecha_calendario."') and !(Fecha_Llegada >= '".$fecha_salida."' and Fecha_Salida >= '".$fecha_salida."') and Fecha_Salida <> '".$fecha_calendario."' order by Id_Hab";
										//print($sql_hab_pernocta);
										

										$sql_hab_pernocta_p = "select * from pernocta_p where !(Fecha_Llegada < '".$fecha_calendario."' and Fecha_Salida < '".$fecha_calendario."') and !(Fecha_Llegada > '".$fecha_calendario."' and Fecha_Salida > '".$fecha_calendario."') and Fecha_Salida <> '".$fecha_calendario."' order by Id_Hab";
                                        //print($sql_hab_pernocta_p);

										$sql_hab_pernocta_gr = "SELECT pernocta_gr.Nombre_Gr,pernocta_gr.Id_Hab as Id_Hab ,pernocta_gr.Fecha_Llegada as Fecha_Llegada ,pernocta_gr.Num_Personas AS Num_Personas,estancia_gr.Fecha_Salida as Fecha_Salida,estancia_gr.Id_Color FROM pernocta_gr LEFT JOIN estancia_gr ON pernocta_gr.Fecha_Llegada=estancia_gr.Fecha_Llegada AND pernocta_gr.Nombre_Gr=estancia_gr.Nombre_Gr WHERE !(estancia_gr.Fecha_Llegada < '".$fecha_calendario."' and estancia_gr.Fecha_Salida < '".$fecha_calendario."') and  !(estancia_gr.Fecha_Llegada >= '".$fecha_salida."' and estancia_gr.Fecha_Salida >= '".$fecha_salida."') order by estancia_gr.Fecha_Llegada" ;
										// print($sql_hab_pernocta_gr);

			$colores = Array();
			$sql_colores = "SELECT * FROM colores";
			$res_colores = mysql_query($sql_colores);
			for($c=0;$c<mysql_num_rows($res_colores);$c++){
				$fila_colores = mysql_fetch_array($res_colores);
				$colores[$c]['id'] = $fila_colores['Id_Color'];
				$colores[$c]['color'] = $fila_colores['Color'];
			}

									if($res_dist=mysql_query($qry_dist)){
											
										for ($i=0;$i<mysql_num_rows($res_dist);$i++){
											$indice_salida['gr'][$i] = 0;
											$indice_salida['alb'][$i] = 0;
											$indice_salida['per'][$i] = 0;
											$indice_salida['reserva'][$i] = 0;
											$tupla_dist=mysql_fetch_array($res_dist);
											if($tupla_dist['Camas_Hab']>0){
												$lenght = count($habita);
												$habita[$lenght]['orden']=intval($tupla_dist['Id_Hab']);
												$habita[$lenght]['id']=$tupla_dist['Id_Hab'];
												$_SESSION['dist_hab'][$i]['camas_restantes'] = $habita[$lenght]['camas'];
												$habita[$lenght]['tipo']=$tupla_dist['Id_Tipo_Hab'];
												if($habita[$lenght]['tipo'] > $max_tipo){
													$max_tipo = $habita[$lenght]['tipo'];
												}
												$habita[$lenght]['camas']=$tupla_dist['Camas_Hab'];
												$habita[$lenght]['ocupadas']=0;
												
												//print("paghab".count($_SESSION['pag_hab'])."paghab");
												//print("habita".count($habita)."habita");
												for($j=0;$j<count($_SESSION['pag_hab']);$j++){
														if($pagina_habitaciones == $_SESSION['pag_hab'][$j]['pagina'] && $habita[$lenght]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab'] ){																
																$habita[$lenght]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];
																if ($tupla_dist['Camas_Hab']>$max_camas){
																	$max_camas=$tupla_dist['Camas_Hab'];
																}
																break;
														}else{
															if($habita[$lenght]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab']){
																$habita[$lenght]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];
																break;
															}
														}
													}
												if ($tupla_dist['Camas_Hab'] > $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']) {
													$resto = $tupla_dist['Camas_Hab'] % $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];
													$habita[$lenght]['Columnas_Necesarias'] = intval($tupla_dist['Camas_Hab'] / $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']);		

													/*******************************************************************************/
													/*

													Aquí estaba el error, si al dividir el numero de camas entre el numero de camas establecido por columna no ocupaba 2 columnas enteras, lo tomaba como si solo ocupara una columna
													
													*/
													/*******************************************************************************/

													if ($resto > 0) {
														$habita[$lenght]['Columnas_Necesarias']++;
													}
													
													if($habita[$lenght]['Columnas_Necesarias'] > 1){				
														for($col=0;$col<=$habita[$lenght]['Columnas_Necesarias'];$col++){
															//$habita[$i]['camas_col']  ----> Numero de camas por columna

															/****************************************************************************/
															/*
															Aquí había otro error. antes el if era:
																if($col == $habita[$i]['Columnas_Necesarias']){

															Lo que hacía que $col pudiera ser 0, y el número de camas en las dos columnas fuera el máximo establecido en la configuración
																	*/
															/****************************************************************************/
															if($col+1 == $habita[$lenght]['Columnas_Necesarias']){
																$habita[$lenght]['camas_col'][$col] = intval($habita[$lenght]['camas'] - ($_SESSION['gdh']['dis_hab']['configuracion_numero_camas']*$col));//printf("h=".$habita[$i]['camas_col'][$col]);
															}else{
																$habita[$lenght]['camas_col'][$col] = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];//printf("h=".$habita[$i]['camas_col'][$col]);
															}
														}
													}
												}else{
													$habita[$lenght]['Columnas_Necesarias'] = 1;
													$habita[$lenght]['camas_col'][0] = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];//printf("hprim=".$habita[$length]['camas_col'][0]);
												}

												//printf("colum=".$habita[$lenght]['Columnas_Necesarias']);
											
												//Se guarda en $habita las otras estancias que se estan realizando durante la estancia que se va a modificar
												/* AL EL DESTRUCTOR HA ESTADO AKI!!!
												$result_reserva = mysql_query($sql_hab_reserva);
												while($fila = mysql_fetch_array($result_reserva)){													
													if($fila['Id_Hab'] == $habita[$i]['id']){
														$habita[$i]['reservas']['cantidad'] += $fila['Num_Camas'];
														if(resta_fecha($fila['Fecha_Llegada'],trim($fecha_salida)) > 0 && resta_fecha(trim($fila['Fecha_Llegada']), trim($fecha_calendario)) < 0){															
															if(!isset($habita[$i]['reservas']['temporal']['cantidad']) || $habita[$i]['reservas']['temporal']['cantidad'] <= 0){
																$habita[$i]['reservas']['temporal']['cantidad']=0;
															}
															$indice = count($habita[$i]['reservas']['temporal']['fecha']);
															$habita[$i]['reservas']['temporal']['cantidad'] += $fila['Num_Camas'];
															$habita[$i]['reservas']['temporal']['fecha'][$indice]['fecha'] = $fila['Fecha_Llegada'];
															$habita[$i]['reservas']['temporal']['fecha'][$indice]['num_personas'] = $fila['Num_Camas'];
														}
													}											
												}														
												$result_pernocta = mysql_query($sql_hab_pernocta);
												while($fila = mysql_fetch_array($result_pernocta)){
													if(trim($fila['Id_Hab']) == trim($habita[$i]['id'])){														
														$habita[$i]['alb']['cantidad']++;
														if(resta_fecha(trim($fila['Fecha_Llegada']), trim($fecha_calendario)) < 0 && resta_fecha(trim($fila['Fecha_Llegada']),trim($fecha_salida)) > 0){
															if(!isset($habita[$i]['alb']['temporal']['cantidad']) || $habita[$i]['alb']['temporal']['cantidad'] < 0){
																$habita[$i]['alb']['temporal']['cantidad']=0;
															}								
															$indice = count($habita[$i]['alb']['temporal']['fecha']);
															$habita[$i]['alb']['temporal']['fecha'][$indice]['fecha_llegada'] = $fila['Fecha_Llegada'];							
															$habita[$i]['alb']['temporal']['cantidad']++;
															
														}
													}
												}
												

												$result_pernocta_p = mysql_query($sql_hab_pernocta_p);
												while($fila = mysql_fetch_array($result_pernocta_p)){
													if(trim($fila['Id_Hab']) == trim($habita[$i]['id'])){														
														$habita[$i]['per']['cantidad']++;
														if(resta_fecha(trim($fila['Fecha_Llegada']), trim($fecha_calendario)) < 0 && resta_fecha(trim($fila['Fecha_Llegada']),trim($fecha_salida)) > 0){
															if(!isset($habita[$i]['per']['temporal']['cantidad']) || $habita[$i]['per']['temporal']['cantidad'] < 0){
																$habita[$i]['per']['temporal']['cantidad']=0;
															}								
															$indice = count($habita[$i]['alb']['temporal']['fecha']);
															$habita[$i]['per']['temporal']['fecha_llegada'] = $fila['Fecha_Llegada'];		
															$habita[$i]['per']['temporal']['cantidad']++;
														}
													}
												}
												$result_pernocta_gr = mysql_query($sql_hab_pernocta_gr);
												while($fila = mysql_fetch_array($result_pernocta_gr)){
													if($fila['Id_Hab'] == $habita[$i]['id'] && resta_fecha($fila['Fecha_Salida'],$fecha_calendario) < 0 && resta_fecha($fila['Fecha_Llegada'],$fecha_salida) > 0){
														$habita[$i]['gr']['cantidad'] += $fila['Num_Personas'];
														$habita[$i]['color'] = $fila['Id_Color'];
														if(resta_fecha($fila['Fecha_Llegada'],$fecha_calendario) < 0 && resta_fecha($fila['Fecha_Llegada'],$fecha_salida) > 0){							
															if(!isset($habita[$i]['gr']['temporal']['cantidad']) || $habita[$i]['gr']['temporal']['cantidad'] < 0){
																$habita[$i]['gr']['temporal']['cantidad']=0;
															}
															$habita[$i]['gr']['temporal']['cantidad'] += $fila['Num_Personas'];
															$indice = count($habita[$i]['gr']['temporal']['fecha']);
															$habita[$i]['gr']['temporal']['fecha'][$indice]['fecha_llegada'] = $fila['Fecha_Llegada'];
															$habita[$i]['gr']['temporal']['fecha'][$indice]['num_personas'] = $fila['Num_Personas'];
														}
													}											
												}
														*/							
											//for ($s=0;$s<count($tipo_Hab);$s){
											//	if($tupla_dist['Id_Tipo_Hab']!=$tipo_Hab[$s]){
											//				$tipo_Hab[$cont]=$tupla_dist['Id_Tipo_Hab'];$cont++;
											//}	}
											
											}
										}

										////////////// Miramos los posibles cambios de tipo de habitacion  //////////////////

	$qry_habs_temps = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MIN(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha > '".$fecha_llegada."' AND Fecha < '".$fecha_salida."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab";

	$res_habs_temps = mysql_query($qry_habs_temps);

	while ($fila_habs_temps = mysql_fetch_array($res_habs_temps)){
		for ($i=0;$i<count($habita);$i++){	
			if ($habita[$i]['id'] == $fila_habs_temps['Id_Hab']){
				$habita[$i]['cambio_tipo'] = $fila_habs_temps['Fecha'];
			}
		}
	}

										$sql_tipo_hab ="select * from tipo_habitacion";
										$result_tipo_hab = mysql_query($sql_tipo_hab);
										while($fila_tipo_hab = mysql_fetch_array($result_tipo_hab)){
											$tipo_habitacion[$fila_tipo_hab['Id_Tipo_Hab']] = $fila_tipo_hab['Nombre_Tipo_Hab'];
											//print($tipo_habitacion[$fila_tipo_hab['Id_Tipo_Hab']] );
										}										
										$tipo = Array();
										foreach ($habita as $key => $row){
											$orden_al[$key]  = $row['orden'];
											$tipo[$key] = $row['tipo'];
										}
											if((!is_null($habita) || count($habita) > 0) && !is_null($orden_al) && !is_null($tipo)){
												if(array_multisort ($tipo, SORT_ASC ,$orden_al, SORT_ASC,$habita)){		
													$numero_paginas_aux=Array();
													for($i_pag=0;$i_pag<count($habita);$i_pag++){
														$sw_pag = true;
														for($b_pag=0;$b_pag<count($numero_paginas_aux);$b_pag++) {
															if(intval($habita[$i_pag]['pagina']) == intval($numero_paginas_aux[$b_pag])){
																$sw_pag = false;//print("dentro");
															}
														}
														if($sw_pag){
															$numero_paginas_aux[] = intval($habita[$i_pag]['pagina']);//print ("fuera");
														}	
													}
									
											
											
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// Ahora saco todo esto de ahi y meto los datos de todas las pernoctas y reservas en habita ////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////// Hola, soy al y voy a crear unos arrays temporales donde meter los datos de todas las reservas y pernoctas


$temp_reserva = array();
$temp_pernocta = array();
$temp_pernocta_p = array();
$temp_pernocta_gr = array();
								
$result_reserva = mysql_query($sql_hab_reserva);
$reservas_temp=array();
while($fila = mysql_fetch_array($result_reserva)){	
	for ($i=0;$i<count($habita);$i++){
		if($fila['Id_Hab'] == $habita[$i]['id'] && $fila['Fecha_Llegada'] != $fila['Fecha_Salida']){
			if ($fila['Fecha_Llegada'] <= $fecha_llegada){
				$habita[$i]['reservas']['cantidad'] += $fila['Num_Camas'];
			}
				$pos = count($temp_reserva);
				$temp_reserva[$pos]['fecha_llegada'] = $fila['Fecha_Llegada'];
				$temp_reserva[$pos]['fecha_salida'] = $fila['Fecha_Salida'];
				$temp_reserva[$pos]['num_camas'] = $fila['Num_Camas'];
				$temp_reserva[$pos]['id_hab'] = $fila['Id_Hab'];
			
		}		
	}
}
$result_pernocta = mysql_query($sql_hab_pernocta);
while($fila = mysql_fetch_array($result_pernocta)){
	for ($i=0;$i<count($habita);$i++){
		if(trim($fila['Id_Hab']) == trim($habita[$i]['id'])){
			if ($fila['Fecha_Llegada'] <= $fecha_llegada){
				$habita[$i]['alb']['cantidad']++;
			}
				$pos = count($temp_pernocta);
				$temp_pernocta[$pos]['fecha_llegada'] = $fila['Fecha_Llegada'];
				$temp_pernocta[$pos]['fecha_salida'] = $fila['Fecha_Salida'];
				$temp_pernocta[$pos]['id_hab'] = $fila['Id_Hab'];
			
		}
	}
}


$result_pernocta_p = mysql_query($sql_hab_pernocta_p);
while($fila = mysql_fetch_array($result_pernocta_p)){	
	for ($i=0;$i<count($habita);$i++){
		if($fila['Id_Hab'] == $habita[$i]['id'] && resta_fecha($fila['Fecha_Llegada'],$fecha_calendario) >= 0 && resta_fecha($fila['Fecha_Salida'], $fecha_calendario) < 0){
			if ($fila['Fecha_Llegada'] <= $fecha_llegada){
				$habita[$i]['per']['cantidad']++;
			}
				$pos = count($temp_pernocta_p);
				$temp_pernocta_p[$pos]['fecha_llegada'] = $fila['Fecha_Llegada'];
				$temp_pernocta_p[$pos]['fecha_salida'] = $fila['Fecha_Salida'];
				$temp_pernocta_p[$pos]['id_hab'] = $fila['Id_Hab'];
				
		}	
	}
}								
$result_pernocta_gr = mysql_query($sql_hab_pernocta_gr);
$indice = 0;
while($fila = mysql_fetch_array($result_pernocta_gr)){
	for ($i=0;$i<count($habita);$i++){
		if($fila['Id_Hab'] == $habita[$i]['id'] && resta_fecha($fila['Fecha_Salida'],$fecha_calendario) < 0 && resta_fecha($fila['Fecha_Llegada'],$fecha_salida) > 0){
			//OJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJ
			//OJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJ
			//OJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJOJ
			if ($fila['Fecha_Llegada'] <= $fecha_llegada){
				//$habita[$i]['gr']['cantidad'] += $fila['Num_Personas'];
				//$habita[$i]['color'] = $fila['Id_Color'];
				$cont_gr = count($habita[$i]['gr']);
				$habita[$i]['gr']['cantidad'] += $fila['Num_Personas'];
				$habita[$i]['gr'][$cont_gr]['camas'] = $fila['Num_Personas'];
				for ($c=0;$c<count($colores);$c++){
					if ($colores[$c]['id'] == $fila['Id_Color']){
						$habita[$i]['gr'][$cont_gr]['color'] = $colores[$c]['color'];
					}
				}
			}
			
		
			$pos = count($temp_pernocta_gr);
			$temp_pernocta_gr[$pos]['fecha_llegada'] = $fila['Fecha_Llegada'];
			$temp_pernocta_gr[$pos]['fecha_salida'] = $fila['Fecha_Salida'];
			$temp_pernocta_gr[$pos]['num_camas'] = $fila['Num_Personas'];
			$temp_pernocta_gr[$pos]['id_hab'] = $fila['Id_Hab'];
				
		}	
	}
}


//////////////// Ahora recorro los arrays temps para sacar las camas temps. Como? Pues voy noche por noche mirando las fehcas de llegada. Segun van llegando
//////////////// mas pernoctas o reservas lo almaceno

$parto_fecha = split("-",$fecha_llegada);
if (resta_fecha($_GET['fecha_llegada'],$_GET['fecha_salida']) > 1){
	for ($h=0;$h<count($habita);$h++){
		// Para cada habitacion recorro dia a dia durante toda la estancia
		$max_camas = 0;
		// Sumo las que ya hay 
		$max_camas += ($habita[$h]['gr']['cantidad'] + $habita[$h]['per']['cantidad'] + $habita[$h]['alb']['cantidad'] + $habita[$h]['reservas']['cantidad']);
		$temps_temp = $max_camas;
		for ($i=1;$i<resta_fecha($_GET['fecha_llegada'],$_GET['fecha_salida']);$i++){
			$fecha_temp=mktime(0,0,0,$parto_fecha[1],$parto_fecha[2]+$i,$parto_fecha[0]);
			$fecha_temp=strftime("%Y-%m-%d",$fecha_temp);
			//Ahora recorro los arrays de las pernoctas y comparo fechas.
			for ($res = 0; $res <count($temp_reserva);$res++){
				if ($temp_reserva[$res]['fecha_llegada'] == $fecha_temp && $temp_reserva[$res]['id_hab'] == $habita[$h]['id']){
					$temps_temp+=$temp_reserva[$res]['num_camas'];
				}
				if ($temp_reserva[$res]['fecha_salida'] == $fecha_temp && $temp_reserva[$res]['id_hab'] == $habita[$h]['id']){
					$temps_temp-=$temp_reserva[$res]['num_camas'];
				}
			}
			for ($per = 0; $per <count($temp_pernocta);$per++){
				if ($temp_pernocta[$per]['fecha_llegada'] == $fecha_temp && $temp_pernocta[$per]['id_hab'] == $habita[$h]['id']){
					$temps_temp++;
				}
				if ($temp_pernocta[$per]['fecha_salida'] == $fecha_temp && $temp_pernocta[$per]['id_hab'] == $habita[$h]['id']){
					$temps_temp--;
				}
			}
			for ($perp = 0; $perp <count($temp_pernocta_p);$perp++){
				if ($temp_pernocta_p[$perp]['fecha_llegada'] == $fecha_temp && $temp_pernocta_p[$perp]['id_hab'] == $habita[$h]['id']){
					$temps_temp++;
				}
				if ($temp_pernocta_p[$perp]['fecha_salida'] == $fecha_temp && $temp_pernocta_p[$perp]['id_hab'] == $habita[$h]['id']){
					$temps_temp--;
				}
			}
			for ($gru = 0; $gru <count($temp_pernocta_gr);$gru++){
				if ($temp_pernocta_gr[$gru]['fecha_llegada'] == $fecha_temp && $temp_pernocta_gr[$gru]['id_hab'] == $habita[$h]['id']){
					$temps_temp+=$temp_pernocta_gr[$gru]['num_camas'];
				}
				if ($temp_pernocta_gr[$gru]['fecha_salida'] == $fecha_temp && $temp_pernocta_gr[$gru]['id_hab'] == $habita[$h]['id']){
					$temps_temp-=$temp_pernocta_gr[$gru]['num_camas'];
				}
			}
			if ($temps_temp > $max_camas){
				$posicion = count($habita[$h]['temporal']);
				$habita[$h]['temporal'][$posicion]['fecha_llegada'] = $fecha_temp;
				$habita[$h]['temporal'][$posicion]['num_camas'] = ($temps_temp - $max_camas);
				$max_camas=$temps_temp;
			}
		}
	}
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

											
											?>			
								
								<table border="0" id="tabla_detalles" style="width:700px;" cellspacing="0" cellpadding="0">
								<thead>
									<td colspan="9" align="center" style="padding:0px 0px 0px 0px;">
										<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
										<?$hrefp="?pag=modificacion_estancia.php&tipo=p&dni=".$_GET['dni']."&fecha_llegada=".$_GET['fecha_llegada']."&fecha_salida=".$_GET['fecha_salida'];?>
										<div style="width:640px;height:25px;text-align:center;float:left;" class="champi_centro">
										<div class="titulo" style="width:638px;text-align:center;">Distribución de Habitaciones
										
										
									
										
										
										
										
										<select name="pagina_habitaciones" class="select_formulario" onChange="cambiasesion();" style="margin-left:50px;">
										<?
											for($i_pag = 0;$i_pag<count($numero_paginas_aux);$i_pag++){
												echo "<option value=\"".$numero_paginas_aux[$i_pag]."\"";
												if(intval($_SESSION['gdh']['dis_hab']['num_pag']) == intval($numero_paginas_aux[$i_pag])){
													echo " selected";
												}										
												echo ">Ventana ".$numero_paginas_aux[$i_pag];
											}
										?>
										</select>
										</div>
										</div>
										<div style="height:25px;width:30px;float:left;" class="champi_derecha">&nbsp;</div>								
									</td>
								</thead>
							<tbody>
						<tr>
						<td class="tabla_detalles" width="100%" align="center" style="border: 1px solid #3F7BCC;padding-top:5px;padding-bottom:5px;padding:0px 0px 0px 0px;">
						<br>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td align="center" style="padding:0px 0px 0px 0px;">							
								<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1">
												<tr align="center" valign="middle">
												<td width="2px"></td>

	<?PHP
		/*
			$tipo_actual = $habita[0]['tipo'];
			$total_colspan=0;
			$sw_titulo = false;
			
			for ($i = $total_colspan; $i <= count($habita); $i = $total_colspan) {
				$colspan = 0;				
				$cont = 0;
				if($pagina_habitaciones == $habita[$i]['pagina']){	
					if($tipo_actual != $habita[$total_colspan]['tipo']){
						if($sw_titulo){
							echo "<td width=\"2px\"   class=\"separar_hab\" rowspan=\"".($_SESSION['gdh']['dis_hab']['configuracion_numero_camas']+3)."\" ></td>";	
							$sw_titulo = false;
						}
						$tipo_actual = $habita[$total_colspan]['tipo'];
					
					}else{
						$colspan = 0;	
						for($j=0;$j<count($habita);$j++){
							if($tipo_actual == $habita[$j]['tipo']){
								$colspan += $habita[$j]['Columnas_Necesarias'];
								$cont = $habita[$j]['Columnas_Necesarias']-1;
							}
						}
						echo "<td colspan=".$colspan." style=\"color:#F6F5F0;font-weight:bold;\" >".$tipo_habitacion[$habita[$i]['tipo']]."</td>";
						$sw_titulo = true;				
						$total_colspan+=$colspan;
						$total_colspan -= $cont;
					}
				}else{
					$total_colspan++;
				}//print($total_colspan);
			}			
			*/
//AL PEGAR MUCHO!!!!!!!!!!!!!!!!!!!!!!!!!!!
			$cont_cols = 0;
			$sw_primero = true;
			for ($i =0; $i < count($habita); $i++){
				if($pagina_habitaciones == $habita[$i]['pagina']){	
					if ($sw_primero){$tipo_actual = $habita[$i]['tipo']; $sw_primero = false;}
					if($tipo_actual != $habita[$i]['tipo'] && $sw_primero == false){
						echo "
							<td colspan='".$cont_cols."' class='nom_tipo_hab'>".$tipo_habitacion[$tipo_actual]."</td>
							<td rowspan=\"".($_SESSION['gdh']['dis_hab']['configuracion_numero_camas']+3)."\"></td>
							<td class=\"separar_hab\" rowspan=\"".($_SESSION['gdh']['dis_hab']['configuracion_numero_camas']+3)."\"></td>
							<td rowspan=\"".($_SESSION['gdh']['dis_hab']['configuracion_numero_camas']+3)."\"></td>
						";
						$cont_cols = $habita[$i]['Columnas_Necesarias'];
						$tipo_actual = $habita[$i]['tipo'];
					}else{
						$cont_cols += $habita[$i]['Columnas_Necesarias'];
					}
				}
			}
			//Pinto el tipo del ultimo grupo de habs.
			echo "<td colspan='".$cont_cols."' class='nom_tipo_hab'>".$tipo_habitacion[$tipo_actual]."</td>";
	/////////////////////////////////////
	?>
													<td width="2px"></td>
												</tr>		
												
											<?	
													
											$sw_ocupada= false; //boolean que indica si hay una habitacion seleccionada					
											$habita2 = $habita;		
											$sw_hab_anterior = false;
											for($fila=0;$fila<=$_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];$fila++){
												$tipo2 = $habita[0]['tipo'];															
													echo "<tr><td width=\"2px\"></td>";
													$sw_first = false;
													for($i=0;$i<count($habita);$i++){
														for($hab = 0;$hab<count($_SESSION['alb']['estancia']['original']);$hab++){
															if(trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['original'][$hab]['id_hab']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['original'][$hab]['fecha_llegada'])) <=0 && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['original'][$hab]['fecha_salida'])) >0){
																$ocupada_original['id'] = $habita[$i]['id'];
																//print(	$ocupada_original['id']);
																
																$ocupada_original['indice'] = $hab;
																break;
															}
														}
														for($hab = 0;$hab<count($_SESSION['per']['estancia']['original']);$hab++){
															if(trim($habita[$i]['id']) == trim($_SESSION['per']['estancia']['original'][$hab]['id_hab']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['per']['estancia']['original'][$hab]['fecha_llegada'])) <=0 && resta_fecha(trim($fecha_calendario),trim($_SESSION['per']['estancia']['original'][$hab]['fecha_salida'])) >0){											
																$ocupada_original['id'] = $habita[$i]['id'];
																$ocupada_original['indice'] = $hab;
																break;
															}
														}	
														$sw_tipo_hab = false;
														if($tipo2 !=$habita[$i]['tipo'] && $habita[$i]['tipo']!= "" || !$sw_first){
															for($j=0;$j<count($_SESSION['pag_hab']);$j++){
																if($pagina_habitaciones == $_SESSION['pag_hab'][$j]['pagina'] && $habita[$i]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab']){	
																	$habita[$i]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];
																	$tipo2 = $habita[$i]['tipo'];
																	$sw_tipo_hab = true;
																	$sw_first = true;		
																	break;
																}
															}															
														}else{
															if($sw_first){
																$sw_tipo_hab = true;
															}
														}
														
														if($sw_tipo_hab){	
															
														if($fila==0){		
																//Se muestra el nombre de las habitaciones
																echo "<td class=\"nom_hab\" colspan=\"".$habita[$i]['Columnas_Necesarias']."\">".$habita[$i]['id']."</td>";
														}else{
															for($columnas=0;$columnas<$habita[$i]['Columnas_Necesarias'];$columnas++){
																$sw_cama = false;
																if(!$sw_cama && $habita[$i]['camas_col'][$columnas] <= 0 || $habita[$i]['camas_col'][$columnas]<=0 && $habita[$i]['camas']<1){	
																	echo "<td id=\"no_cama\">&nbsp;</td>";
																	$sw_cama = true;
																																		
																}


																//Cama ocupada por un alberguista
																

																if(!$sw_cama && $habita[$i]['alb']['cantidad'] > 0 && $habita[$i]['alb']['cantidad'] != $habita[$i]['alb']['temporal']['cantidad']){																		
																	for($hab=0;$hab<count($_SESSION['alb']['estancia']['pernoctas']);$hab++){																											if(resta_fecha(trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha_llegada']),trim($fecha_calendario)) >= 0 && resta_fecha(trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha_salida']),trim($fecha_calendario)) < 0 ){
																			break;
																		}
																	}
																	if(!$sw_ocupada && trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['id_hab']) && resta_fecha(trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha_llegada']), trim($fecha_calendario)) >= 0 && resta_fecha(trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha_salida']),trim($fecha_calendario)) < 0){
																		echo ("<td class=\"cama_libre\" onMouseOver=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_resaltada';}\"	onMouseOut=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_libre';}\" id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','s',0)\">");
																		echo "&nbsp;</td>";	
																
																		echo "<script>asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','n',0);</script>";
																		$sw_ocupada = true;
																	}else{					
																		if(!$sw_hab_anterior && trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['original'][$hab]['id_hab']) && trim($_SESSION['alb']['estancia']['original'][$hab]['id_hab']) != trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['id_hab']) && resta_fecha(trim($_SESSION['alb']['estancia']['original'][$hab]['fecha_llegada']),trim($fecha_calendario)) >= 0 && resta_fecha(trim($_SESSION['alb']['estancia']['original'][$hab]['fecha_salida']),trim($fecha_calendario)) < 0){
																			echo ("<td class=\"cama_libre\" onMouseOver=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_resaltada';}\"	onMouseOut=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_libre';}\" id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','s',0)\">");
																			echo "&nbsp;</td>";	
																	
																			$sw_hab_anterior =true;
																		}else{
																			echo "<td class=\"cama_ocupada\" >&nbsp;</td>";	
																		
																		}
																	}
																	$sw_cama = true;															
																	$habita[$i]['alb']['cantidad']--;
																}
																
																
															
																
															//	print(trim($habita[$i]['id']));
															//	print(trim($_SESSION['per']['estancia']['pernoctas'][0]['id_hab']));
																
																
																

																//Cama ocupada por un peregrino

																if(!$sw_cama && $habita[$i]['per']['cantidad'] > 0 && $habita[$i]['per']['cantidad'] != $habita[$i]['per']['temporal']['cantidad']){
																	if(!$sw_ocupada && trim($habita[$i]['id']) == trim($_SESSION['per']['estancia']['pernoctas'][0]['id_hab']) && resta_fecha(trim($_SESSION['per']['estancia']['pernoctas'][$hab]['fecha_llegada']),trim($fecha_calendario)) >= 0 ) {
																		echo ("<td class=\"cama_libre\" onMouseOver=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_resaltada';}\"	onMouseOut=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_libre';}\" id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','s',0)\">");

															
																		echo "&nbsp;</td>";		
																		echo "<script>asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','n',0);</script>";//print("amarilla".$habita[$i]['id']);
																		$sw_ocupada = true;
																	}else{
																		if(trim($habita[$i]['id']) == trim($_SESSION['per']['estancia']['original'][0]['id_hab']) && resta_fecha(trim($_SESSION['per']['estancia']['original'][$hab]['fecha_llegada']),trim($fecha_calendario)) >= 0 && resta_fecha(trim($_SESSION['per']['estancia']['original'][$hab]['fecha_salida']),trim($fecha_calendario)) < 0){
																			echo ("<td class=\"cama_ocupada\" onMouseOver=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_ocupada';}\"	onMouseOut=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_ocupada';}\" id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','s',0)\">");
																			echo "&nbsp;</td>";
//print("rojo?".$habita[$i]['id']);

																		}else{//print("rojo".$habita[$i]['id']);
																			echo "<td class=\"cama_ocupada\" >&nbsp;</td>";
                                                                            
																		}			
																	}
																	$habita[$i]['per']['cantidad']--;																	
																	$sw_cama = true;
																}
																

																//Cama ocupada por un miembro de grupo

																if(!$sw_cama && $habita[$i]['gr']['cantidad'] > 0 && $habita[$i]['gr']['cantidad'] != $habita[$i]['gr']['temporal']['cantidad']){																	
																	echo ("<td class=\"cama_ocupada\" ");
																	$salir = false;
																	$cont_gr = 0;
																	while (!$salir){
																		if ($habita[$i]['gr'][$cont_gr]['camas'] > 0){
																			echo " style='background-color:#".$habita[$i]['gr'][$cont_gr]['color'].";'";
																			$habita[$i]['gr'][$cont_gr]['camas']--;
																			$salir = true;
																		}else{
																			if($cont_gr > count($habita[$i]['gr'])){
																				$salir = true;
																			}
																		}
																		$cont_gr++;
																	}
																	echo (">&nbsp;</td>");
																	$habita[$i]['gr']['cantidad']--;
																	$sw_cama = true;
																}


																//Cama ocupada por una reserva

																if(!$sw_cama && $habita[$i]['reservas'] >0 && $habita[$i]['reservas']['cantidad'] != $habita[$i]['reservas']['temporal']['cantidad'] ){
																	echo ("<td class=\"cama_reservada\">&nbsp;</td>");			
																	$habita[$i]['reservas']['cantidad']--;
																	$sw_cama = true;
																}
																

																//Cama ocupada temporalmente por una reserva

																if(!$sw_cama && $habita[$i]['reservas']['cantidad'] > 0 && $habita[$i]['reservas']['cantidad'] == 	$habita[$i]['reservas']['temporal']['cantidad']){
																	$fecha_hab_split = split("-",$habita[$i]['reservas']['temporal']['fecha'][$indice_salida['reserva'][$i]]['fecha']);
																	echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\">&nbsp;</td>";
																	if(!$sw_ocupada){
																		for($hab = 0;$hab<count($_SESSION['alb']['estancia']['pernoctas']);$hab++){
																			if(trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['id']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha'])) >= 0 && $_SESSION['alb']['estancia']['pernoctas'][$hab]['parcial'] == 1 ){	
																				echo "<script>asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','n',1);</script>";
																				$sw_ocupada = true;
																				break;
																			}
																		}
																	}
																	if($habita[$i]['reservas']['temporal']['fecha'][$indice_salida['reserva'][$i]]['num_personas'] == 1){	
																		$indice_salida['reserva'][$i]++;
																	}else{
																		$habita[$i]['reservas']['temporal']['fecha'][$indice_salida['reserva'][$i]]['num_personas']--;	
																	}
																	$habita[$i]['reservas']['temporal']['cantidad']--;
																	$habita[$i]['reservas']['cantidad']--;
																	$sw_cama = true;
																}

																//Cama ocupada temporalmente por un miembro de grupo
																
																if(!$sw_cama && $habita[$i]['gr']['cantidad'] > 0 && $habita[$i]['gr']['cantidad'] == $habita[$i]['gr']['temporal']['cantidad']){
																	$fecha_hab_split = split("-",$habita[$i]['gr']['temporal']['fecha'][$indice_salida['gr'][$i]]['fecha_llegada']);
																	echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\">&nbsp;</td>";
																	if(!$sw_ocupada){
																		for($hab = 0;$hab<count($_SESSION['alb']['estancia']['pernoctas']);$hab++){
																			if(trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['id']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha'])) >= 0 && $_SESSION['alb']['estancia']['pernoctas'][$hab]['parcial'] == 1 ){	
																				echo "<script>asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','n',1);</script>";
																				$sw_ocupada = true;
																				break;
																			}
																		}
																	}
																	if($habita[$i]['gr']['temporal']['fecha'][$indice_salida['gr'][$i]]['num_personas'] == 1){	
																		$indice_salida['gr'][$i]++;
																	}else{
																		$habita[$i]['gr']['temporal']['fecha'][$indice_salida['gr'][$i]]['num_personas']--;					
																	}
																	$habita[$i]['gr']['temporal']['cantidad']--;
																	$habita[$i]['gr']['cantidad']--;																	
																	$sw_cama = true;
																}

																//Cama ocupada temporalmente por un alberguista

																if(!$sw_cama && $habita[$i]['alb']['cantidad'] > 0 && $habita[$i]['alb']['cantidad'] == $habita[$i]['alb']['temporal']['cantidad']){//print($habita[$i]['alb']['cantidad'] );//print($habita[$i]['alb']['temporal']['cantidad']);
																	$fecha_hab_split = split("-",$habita[$i]['alb']['temporal']['fecha'][$indice_salida['alb'][$i]]['fecha_llegada']);
																	echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\">&nbsp;</td>";
																	if(!$sw_ocupada){
																		for($hab = 0;$hab<count($_SESSION['alb']['estancia']['pernoctas']);$hab++){
																			if(trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['id']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha_llegada'])) >=0 && $_SESSION['alb']['estancia']['pernoctas'][$hab]['parcial'] == 1 ){
																				echo "<asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','n',1);</script>";
																				$sw_ocupada = true;
																				break;
																			}
																		}
																	}
																	$indice_salida['alb'][$i]++;
																	$habita[$i]['alb']['temporal']['cantidad']--;
																	$habita[$i]['alb']['cantidad']--;
																	$sw_cama = true;
																}

																//Cama ocupada temporalmente por un peregrino
	
																if(!$sw_cama && $habita[$i]['per']['cantidad'] > 0 && $habita[$i]['per']['cantidad'] == $habita[$i]['per']['temporal']['cantidad']){
																	$fecha_hab_split = split("-",$habita[$i]['per']['temporal']['Fecha_Llegada']);
																	echo "<td style=\"background-color:#5C8DBE;\" class=\"cama_temp\"  id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\"if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\">&nbsp;</td>";
																	$habita[$i]['per']['temporal']['cantidad']--;
																	$habita[$i]['per']['cantidad']--;
																	$sw_cama = true;
																}
//Cama Temporal de Al
						
						if (!$sw_cama && count($habita[$i]['temporal'])>0){
							for ($tempal = 0;$tempal < count($habita[$i]['temporal']);$tempal++){
								if ($habita[$i]['temporal'][$tempal]['num_camas'] > 0 && !$sw_cama){
									/// copypaste de iv
									$fecha_hab_split = split("-",$habita[$i]['temporal'][$tempal]['fecha_llegada']);
									echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\"";
									if($_SESSION['reserva']['sw_reserva'] != "true"){
										echo "title='Cama ocupada a partir del ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."' onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Esta cama estrá ocupada a partir del ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\"";
									}
									echo ">".$fecha_hab_split[2]."·".$fecha_hab_split[1]."</td>";
									$habita[$i]['temporal'][$tempal]['num_camas']--;
									$sw_cama = true;
								}
							}
							
						}


																//Cama libre

																if(!$sw_cama && $habita2[$i]['camas'] >= $fila){
																	if (isset($habita2[$i]['cambio_tipo'])){
																		$fecha_hab_split = split("-",$habita[$i]['cambio_tipo']);

																		echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\"  title='La cama cabiará de tipo a partir del día  ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."'";
																		echo "onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\"";
																		
																		echo ">".$fecha_hab_split[2]."·".$fecha_hab_split[1]."</td>";
																	}else{
																		echo ("<td class=\"cama_libre\" onMouseOver=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_resaltada';}\"	onMouseOut=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_libre';}\" id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','s',0)\">");
																		echo "&nbsp;</td>";		
																	}
																	for($hab=0;$hab<count($_SESSION['alb']['estancia']['pernoctas']);$hab++){
																			if(resta_fecha(trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha_llegada']),trim($fecha_calendario)) >= 0 && resta_fecha(trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['fecha_salida']),trim($fecha_calendario)) < 0){
																			break;
																		}
																	}
																	if(!$sw_ocupada && trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['pernoctas'][$hab]['id_hab'])){
																		echo "<script>asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','n',0);</script>";
																		$sw_ocupada = true;
																	}
																	$sw_cama = true;
																}else{
																	if(!$sw_cama){
																		echo ("<td class=\"no_cama\"></td>");
																		$sw_cama = true;
																	}
																}
																	//print($habita[$i]['camas_col'][$columnas])	;
																//print($habita[$i]['camas'])	;
																//print(	$total_camas[$i]);															
																$habita[$i]['camas_col'][$columnas]--;
																//$habita[$i]['camas']--;
																$total_camas[$i]++;	
																
																											
															}//for columnas
														}//if (fila = 0)
													}//if(sw tipo hab)
												}//for (count habita)
												echo "<td width=\"2px\"></td></tr>";
											}//for(fila)											
										?>	
										<tr><td height='4px'></td></tr>
																	
											</table>
											</td></tr>
											<tr><td align="right">
								<div id="leyenda" style="position:absolute;  background-color: #FFFFFF; border: 1px none #000000;  visibility: hidden;z-index: 1;font-size:10px;margin-left:-342px;margin-top:-3px;width:400px;color:#064C87;float:left;" > 
								<table width="100%" border="1" bordercolor="#000000" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="6">
											<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
												<tr> 
													<td align="center" style="font-size:12px"><b>Leyenda</b></td>
													<td width="20" align="center"><a href="#" onClick="ver_leyenda('1')" title="Cierra la Leyenda de la Distribución de Habitaciones" onMouseOver="window.status='Cierra la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true"  style="font-family:Verdana;font-size:12px;color:#467DB5;font-weight:bold;">X</a></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr> 
										<td width="19" height="19" class="cama_libre_con">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td  align="left">Disponible</td>
									
										<td width="19" height="19" id="cama_reservada_online">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td width="80" align="left">Reservada</td>
									
										<td width="19" height="19" id="cama_ocupada_online">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td  width="60" align="left">Ocupada</td>
									</tr><tr>    
										<td height="19" class="cama_asignada">&nbsp;</td>
										<td width="130" align="left">Asignada a la reserva</td>
										<td width="19" height="19" class="cama_temp">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td colspan="3"  align="left" >No disponible todos los días</td>
										<!--td colspan="2">&nbsp;</td-->
									</tr>
								</table>
                       		</div>
										<a href="#" onClick="ver_leyenda('2')" title="Ver la Leyenda de la Distribución de Habitaciones" OnMouseOver="window.status='Ver la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true" style="font-family:Verdana;font-size:12px;color:#467DB5;font-weight:bold;text-decoration:underline;"> 
										Leyenda</a>

								</td>
							</tr>
							
							
							
							
						
						
						
						
						
						
						</tbody>
					</table>
					</td>
						</tr>
						
						<tr><td>
							
						
							
							<div id="leyenda" style="position:absolute; height:25px; width:250px; background-color: #FFFFFF; border: 1px none #000000; z-index: 2; visibility: hidden;"> 
							<table width="100%" border="1" bordercolor="#000000" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0">
								<tr><td colspan="3">
								<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                              		<tr> 
                                      <td align="center">Leyenda</td>
                                      <td width="20" align="center"><a href="#" onClick="ver_leyenda('1')" title="Cierra la Leyenda de la Distribución de Habitaciones" onMouseOver="window.status='Cierra la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true">X</a></td>
                                    </tr>
                                </table>
								</td></tr>
								<tr> 
                                	<td width="20" height="20" class="cama_libre_con">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                	<td width="957" colspan="2" align="left">Cama disponible</td>
								</tr>
                              	<tr> 
                                	<td width="20" height="20" id="cama_reservada_online">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                	<td width="957" colspan="2" align="left">Cama reservada</td>
                              	</tr>
							  	<tr> 
                                	<td width="20" height="20" id="cama_ocupada_online">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                	<td width="957" colspan="2" align="left">Cama ocupada</td>
                              	</tr>
                              	<tr> 
                                	<td width="20" height="20" class="cama_temp">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                	<td width="957" colspan="2" align="left">Cama no disponible todos los días</td>
                              	</tr>
								<tr> 
                                	<td height="20" class="cama_asignada">&nbsp;</td>
                                	<td colspan="2" align="left">Cama asignada a la reserva</td>
                              	</tr>
                            </table>
                       	</div>
							
							
							
							
							
							
							</td></tr>
						
						
						
					</table>


					</form>
				<?					
					}else{
						echo "<br><br><br><span class=\"label_formulario\">No se puede mostrar el mapa de habitaciondes, compruebe que hay habitaciones introducidas en el sistema.</span><br><br>";					
						$habitaciones_exist = "false";
					}
				}else{
					echo "<br><br><br><span class=\"label_formulario\">No se puede mostrar el mapa de habitaciondes, compruebe que hay habitaciones introducidas en el sistema.</span><br><br>";					
					$habitaciones_exist = "false";					
				}
			}		
 mysql_close($db);

 ?>
			

	</div>
			</div>
		</div>
	</div>
	
<SCRIPT language="JavaScript">

	function cambiasesion(){
	
		document.formu_mod_estancia.sub.value='true';
		document.formu_mod_estancia.action='<?print($hreff)?>';
		document.formu_mod_estancia.submit();
		
		
	}

</script>

