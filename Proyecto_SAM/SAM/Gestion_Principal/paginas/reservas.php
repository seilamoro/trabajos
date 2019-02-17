
<?php

SESSION_START();

//Comprobamos que se tienen los permisos necesarios para acceder a la pagina
if ($_SESSION['permisoReservas'] != true){
	echo "
		<div class='error'>
			NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
		</div>
	";
	exit();
}

//Realizamos la Conexión a la Base de Datos
@ $db=mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
mysql_select_db($_SESSION['conexion']['db']);


/////////////////////////////////////////////////////////////////////////////////////////
//******************************** Calculo de la Fecha ********************************//
/////////////////////////////////////////////////////////////////////////////////////////

/*
La fecha puede venir dada por la variable fecha_cal o por la variable de sesion [gdh]
Tambien hay que tener en cuenta si estamos asignando una reserva en varias partes, en
cuyo caso habrá que comprobar primero si existe la variable de session [reservas]
*/
if (isset($_SESSION['reservas']['inserts'][$cont_ins]['fecha_llegada'])){
	$fecha=$_SESSION['reservas']['inserts'][$cont_ins]['fecha_llegada'];
}else{
	if (isset($_GET['fecha_cal'])){
		$fecha_alreves=$_GET['fecha_cal'];
		$array_fecha=split("-",$fecha_alreves);
		if (intval($array_fecha[0])<10 && strlen($array_fecha[0]) == 1){$array_fecha[0]="0".$array_fecha[0];}
		if (intval($array_fecha[1])<10 && strlen($array_fecha[1]) == 1){$array_fecha[1]="0".$array_fecha[1];}
		$fecha=$array_fecha[2]."-".$array_fecha[1]."-".$array_fecha[0];
	}
	else{
		if (isset($_SESSION['gdh'])){
			$fecha = $_SESSION['gdh']['anio']."-";
			if (strlen($_SESSION['gdh']['mes']) == 1){$fecha = $fecha."0".$_SESSION['gdh']['mes']."-";}else{$fecha = $fecha.$_SESSION['gdh']['mes']."-";}
			if (strlen($_SESSION['gdh']['dia']) == 1){$fecha = $fecha."0".$_SESSION['gdh']['dia'];}else{$fecha = $fecha.$_SESSION['gdh']['dia'];}
		}else{
			$fecha=date('Y-m-d');
		}
	}
}
$array_temp=split("-",$fecha);
$fecha_dia=$array_temp[2];
$fecha_mes=$array_temp[1];
$fecha_anio=$array_temp[0];
$fecha_enviar=$fecha_dia."-".$fecha_mes."-".$fecha_anio;
if (isset($_SESSION['reservas']['inserts'][$cont_ins]['fecha_llegada'])){
	$split_temp=split("-",$_SESSION['reservas']['inserts'][$cont_ins]['fecha_llegada']);	
	$fecha_temp=mktime(0, 0, 0, $split_temp[1], $split_temp[2] + $_SESSION['reservas']['inserts'][$cont_ins]['pernoctas'], $split_temp[0]);
	$fecha_temp_total=strftime("%Y-%m-%d",$fecha_temp);
	$fecha_temp_dia=strftime("%d",$fecha_temp);
	$fecha_temp_mes=strftime("%m",$fecha_temp);
	$fecha_temp_anio=strftime("%Y",$fecha_temp);
	$fecha_dia=strftime("%d",$fecha_temp);
	$fecha_mes=strftime("%m",$fecha_temp);
	$fecha_anio=strftime("%Y",$fecha_temp);
	$fecha=strftime("%Y-%m-%d",$fecha_temp);
}


////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

$sql_tipo_hab ="select * from tipo_habitacion where Reservable='S'";
$result_tipo_hab = mysql_query($sql_tipo_hab);
 for ($i=0;$i<mysql_num_rows($result_tipo_hab);$i++){
	    $fila=mysql_fetch_array($result_tipo_hab);
	    $reservables[$i]=$fila['Id_Tipo_Hab'];
	    //print($reservables[$i]);
}




$numero_paginas = array();



$fila_tipo_hab = mysql_fetch_array($result_tipo_hab);

for ($i = 0; $i < COUNT($_SESSION['pag_hab']); $i++) {

	if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$numero_paginas)) {
	  for($j=0;$j<count($reservables);$j++){
	  if($reservables[$j]==$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']){
	  	
		$numero_paginas[] = $_SESSION['pag_hab'][$i]['pagina'];
	}
	}}}
//Consulta para saber la pagina actual
if (isset($_POST['res_num_pag'])){
	$_SESSION['gdh']['dis_hab']['num_pag'] = $_POST['res_num_pag'];//print("siempre");
	
}else{
  
  	if (IN_ARRAY($_SESSION['gdh']['dis_hab']['num_pag'],$numero_paginas)){
    
	$_POST['res_num_pag'] = $_SESSION['gdh']['dis_hab']['num_pag'];//print("bien");
	} else {
	  			$_POST['res_num_pag']=$numero_paginas[0];
				$_SESSION['gdh']['dis_hab']['num_pag']=$numero_paginas[0];//print("mal");}
			  
			}
			
}

//------------------------------------------------			
//Funcion que parte las strings
function truncar($texto, $long){
	$sustituir = false;
	$trozos = array();
	$resultado = '';
	$texto_partido = split(" ", $texto);
	for ($j=0;$j<count($texto_partido);$j++){
		$texto_guiones = split("-",$texto_partido[$j]);
		for ($k=0;$k<count($texto_guiones);$k++){
			if (strlen($texto_guiones[$k]) > $long){
				$orig=0;
				$cachos = intval(strlen($texto_guiones[$j])/$long);
				if ((strlen($texto_guiones[$k])%$long) > 0){$cachos++;}
				for ($i=1;$i<$cachos+1;$i++){
					$trozos[$i] = substr($texto_guiones[$k], $orig, $long);
					$orig = $orig + $long;
				}
		
				for ($i=1;$i<count($trozos);$i++){
					$resultado = $resultado.$trozos[$i]."-";
				}
				$resultado = $resultado.$trozos[$i];
				$sustituir = true;
			}else{
				$resultado = $resultado.$texto_partido[$j];
			}
			
			if ($k!=count($texto_guiones)-1){$resultado = $resultado."-";}
		}
		if ($j!=count($texto_partido)){$resultado = $resultado." ";}
	}
	if ($sustituir){
		return $resultado;
	}else{
		return $texto;
	}
}



if (isset($_SESSION['gdh']['dis_hab']['configuracion_numero_camas'])){//Variable donde guardo el numero maximo de camas por fila que aparecen en DIST.
	$max_camas_fila = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];
}else{
	$max_camas_fila = 8;
}
$habita=array();	//En este array guardamos TODA LA INFORMACION SOBRE LAS HABITACIONES
$max_camas=0;	//Numero de camas que tiene la habitación mas grande
$tipo_Hab=array();
$tempur=array();	// Array donde vamos a guarda que camas estan ocupadas que dias
$temporales=array(); //Array donde vamos a guardar los datos de las camas ocupadas temporalmente
$datos=array();	//Array donde gurdo los datos de nueva reserva
$ocupadas=array();	//Array donde almaceno la id de las habitaciones ocupadas
$cont_ins=count($_SESSION['reservas']['inserts']);//Contador de Inserts para la funcinalidad de insertar diferentes pernoctas

//////////////////////////////////////////////////////////////////////////////////////
//************************* Consulta de Habitaciones *******************************//
//////////////////////////////////////////////////////////////////////////////////////

/*
	Para despues trabajar con ellas, almacenaremos en el harray $habitas los datos de todas
	las habitaciones reales, es decir, aquellas que se correspondan con las habitaciones reales
	del albergue. Para esto primero consultaremos las habitaciones que se correspondan con la 
	paguina que estemos usando actualmente mediante $_SESSION['gdh']['dis_hab']['num_pag'] o 
	$_POST['res_num_pag'], y luego haremos la consulta a la base de datos
*/




$habitaciones_orden = array();






//------------------------



for ($i = 0; $i < COUNT($_SESSION['pag_hab']); $i++) {




if ($_SESSION['pag_hab'][$i]['pagina'] == $_SESSION['gdh']['dis_hab']['num_pag']) {
		$cont = COUNT($habitaciones_orden);
		$habitaciones_orden[$cont]['orden'] = $_SESSION['pag_hab'][$i]['orden'];
		$habitaciones_orden[$cont]['Id_Tipo_Hab'] = $_SESSION['pag_hab'][$i]['Id_Tipo_Hab'];
	}
}

//-----------------------------


foreach ($habitaciones_orden as $llave => $fila) {
   $orden[$llave]  = $fila['orden'];
}

if (COUNT($habitaciones_orden) > 0) {
	@ ARRAY_MULTISORT($orden, SORT_DESC, $habitaciones_orden);
}	

$habs_orden = " ";

for ($i=0;$i<count($habitaciones_orden);$i++){
	if ($i>0){$habs_orden = $habs_orden." OR";}
	$habs_orden = $habs_orden." tipo_habitacion.Id_Tipo_Hab LIKE '".$habitaciones_orden[$i]['Id_Tipo_Hab']."'";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////// Consulta para habitas (Cortesia de Jaime 'The Crack' TM) ///////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////


$qry_habs_1 = "SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fecha."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab";

$qry_habs_2 = "SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fecha."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab";

$qry_habs_3 = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fecha."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab WHERE tipo_habitacion.reservable LIKE 'S' ";


///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////
////// Consulta a la base de datos sobre la tabla habitaciones e inserción de datos en $habita ////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////

$qry_dist= $qry_habs_3." AND (".$habs_orden.")";;

$res_dist=mysql_query($qry_dist);
$cont=0;
for ($i=0;$i<@mysql_num_rows($res_dist);$i++){
	$tupla_dist=mysql_fetch_array($res_dist);
	if ($tupla_dist['Camas_Hab']>0){
		$habita[$cont]['orden']=intval($tupla_dist['Id_Hab']);
		$habita[$cont]['id']=$tupla_dist['Id_Hab'];
		$habita[$cont]['tipo']=$tupla_dist['Id_Tipo_Hab'];
		$habita[$cont]['camas']=$tupla_dist['Camas_Hab'];
		$habita[$cont]['camas_totales']=$tupla_dist['Camas_Hab'];
		$habita[$cont]['ocupadas']=0;
		$habita[$cont]['pernoctas']=0;
		$habita[$cont]['temps']=0;
		$habita[$cont]['temp']=array();
		$cont_cols=0;//Contador de columnas
		if ($habita[$cont]['camas_totales'] > $max_camas_fila){
			for ($cont_cols=0;$cont_cols<($habita[$cont]['camas_totales']/$max_camas_fila)-1;$cont_cols++){
				$habita[$cont]['col'][$cont_cols]['camas']=$max_camas_fila;
			}
			$habita[$cont]['col'][$cont_cols]['camas']=($habita[$cont]['camas_totales']%$max_camas_fila);
		}else{
			$habita[$cont]['col'][$cont_cols]['camas']=$tupla_dist['Camas_Hab'];
		}	

		//Comparamos el numero maximo de camas
		if ($tupla_dist['Camas_Hab']>$max_camas){
			$max_camas=$tupla_dist['Camas_Hab'];
		}
		for ($s=0;$s<count($tipo_Hab);$s){
			if($tupla_dist['Id_Tipo_Hab']!=$tipo_Hab[$s]){$tipo_Hab[$cont]=$tupla_dist['Id_Tipo_Hab'];$cont++;}
		}
		$cont++;
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
//************************  En caso de que asignemos las pernoctas partidas  ***************************
////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
	En caso de que estemos realizando una reserva fraccionada en diferentes pernoctas,
	lo indicaremos enviando la variable temp en la url. Con esto, tenemos que si temp
	es igual a si, estaremos almacenando un nuevo 'trozo' de la reserva en el array 
	de session $_SESSION['reservas'], y si recibimos temp igual a fin, se completa la 
	reserva y se almacenan todos los datos recibidos en la base de datos. En caso de 
	que no recibamos temp, se borrará el array $_SESSION['reservas'].
*/

if (isset($_GET['temp'])){

	if ($_GET['temp']=='si'){
		if(!isset($_SESSION['reservas'])){
			$_SESSION['reservas']  = Array();
			$_SESSION['reservas']['inserts'] = Array();
		}
		$cont_ins = count($_SESSION['reservas']['inserts']);
				
		//Hay que modificar los formatos de las fechas para poder trabajar con ellas
		$fecha_ent_split = split("-",$_GET['fecha_entrada']);
		$fecha_entrada=mktime(0,0,0,$fecha_ent_split[1],$fecha_ent_split[0],$fecha_ent_split[2]);
		$fecha_sal_split = split("-",$_GET['fecha_salida']);
		$fecha_salida=mktime(0,0,0,$fecha_sal_split[1],$fecha_sal_split[0],$fecha_sal_split[2]);
		$pernoctas = (($fecha_salida - $fecha_entrada)/86400);
		$fecha_entrada = strftime("%Y-%m-%d",$fecha_entrada);
		$fecha_salida = strftime("%Y-%m-%d",$fecha_salida);

		$_SESSION['reservas']['noches'] = $_GET['noches'] - $pernoctas;
		$_SESSION['reservas']['inserts'][$cont_ins]['fecha_llegada'] = $fecha_entrada;
		$_SESSION['reservas']['inserts'][$cont_ins]['pernoctas'] = $pernoctas;

		$cont_habs = 0;
		for($i=0;$i<count($habita);$i++){//Miro las habs asignadas
			if($_POST['camas_hab_'.$habita[$i]['id']]>0){
				$_SESSION['reservas']['inserts'][$cont_ins]['habs'][$cont_habs]['id'] = $habita[$i]['id'];
				$_SESSION['reservas']['inserts'][$cont_ins]['habs'][$cont_habs]['camas'] = $_POST['camas_hab_'.$habita[$i]['id']];
				$cont_habs++;
			}
		}
	}


	//Si temp es igual a fin, introducimos todos los datos de la reserva en la base de datos 
	if ($_GET['temp'] == 'fin'){
		if(!isset($_SESSION['reservas'])){
			echo "<script> alert('Ha ocurrido un error al realizar la reserva'); window.location.href='?pag=reservas.php' </script>";
			exit();
		}
		$cont_ins = count($_SESSION['reservas']['inserts']);

		$fecha_ent_split = split("-",$_GET['fecha_entrada']);
		$fecha_entrada=mktime(0,0,0,$fecha_ent_split[1],$fecha_ent_split[0],$fecha_ent_split[2]);
		$pernoctas = $_GET['noches'];
		$fecha_entrada = strftime("%Y-%m-%d",$fecha_entrada);
		
		$_SESSION['reservas']['noches'] = $_GET['noches'] - $pernoctas;
		$_SESSION['reservas']['inserts'][$cont_ins]['fecha_llegada'] = $fecha_entrada;
		$_SESSION['reservas']['inserts'][$cont_ins]['pernoctas'] = $pernoctas;

		$cont_habs = 0;
		for($i=0;$i<count($habita);$i++){//Miro las habs asignadas
			if($_POST['camas_hab_'.$habita[$i]['id']]>0){
				$_SESSION['reservas']['inserts'][$cont_ins]['habs'][$cont_habs]['id'] = $habita[$i]['id'];
				$_SESSION['reservas']['inserts'][$cont_ins]['habs'][$cont_habs]['camas'] = $_POST['camas_hab_'.$habita[$i]['id']];
				$cont_habs++;
			}
		}

		//Comprobamos que todas las noches han sido asignadas, y si es asi procedemos a introducir los datos
		if ($_SESSION['reservas']['noches'] == 0){
			$dni=$_POST['dni_new_reserva'];
			$nombre=$_POST['nombre_new_reserva'];
			$apellido1=$_POST['apellido1_new_reserva'];
			$apellido2=$_POST['apellido2_new_reserva'];
			$telefono=$_POST['telefono_new_reserva'];
			$email=$_POST['email_new_reserva'];
			$ingreso=$_POST['ingreso_new_reserva'];
			$empleado=$_POST['empleado_new_reserva'];
			$llega_tarde=$_POST['llega_tarde_new_reserva'];
			$observaciones=$_POST['obs_new_reserva'];
			$fecha_reserva=date("Y-m-d");
			
			//Comprovación de PRA
			$qry_temp="SELECT * FROM pra WHERE DNI_PRA = '".$dni."' ;";
			$res_temp=mysql_query($qry_temp);
			if (mysql_num_rows($res_temp)){	//Si existe un registro con ese dni, creamos un instruccion UPDATE
				$tupla_temp=mysql_fetch_array($res_temp);
				$cambiar=false;
				$qry_temp="UPDATE pra SET ";
				if ($tupla_temp['Nombre_PRA']!=$nombre){
					$qry_temp=$qry_temp." Nombre_PRA = '".$nombre."',";
					$cambiar=true;
				}
				if ($tupla_temp['Apellido1_PRA']!=$apellido1){
					$qry_temp=$qry_temp." Apellido1_PRA = '".$apellido1."',";
					$cambiar=true;
				}
				if ($tupla_temp['Apellido2_PRA']!=$apellido2){
					$qry_temp=$qry_temp." Apellido2_PRA = '".$apellido2."',";
					$cambiar=true;
				}
				if ($tupla_temp['Tlno_PRA']!=$telefono){
					$qry_temp=$qry_temp." Tfno_PRA = '".$telefono."',";
					$cambiar=true;
				}
				if ($tupla_temp['Email_PRA']!=$email){
					$qry_temp=$qry_temp." Email_PRA = '".$email."',";
					$cambiar=true;
				}
				//Si el cliente tiene alguna observación
				if ($cambiar){
					$qry_new_pra=substr($qry_temp,0,strlen($qry_temp)-1)." WHERE DNI_PRA = '".$dni."'";
				}else{$qry_new_pra='';}
			}else{
				//Si no existe registro con ese dni, creamos una instrucion INSERT
				$qry_new_pra="INSERT INTO pra (DNI_PRA, Nombre_PRA, Apellido1_PRA, Apellido2_PRA, Tfno_PRA, Email_PRA) VALUES ('".$dni."', '".$nombre."', '".$apellido1."', '".$apellido2."', '".$telefono."', '".$email."')";
			}
	
			//Insert en la tabla PRA
			$res_new_pra=mysql_query($qry_new_pra);

			//INSERTS EN LAS TABLAS DETALLES Y RESERVA
			for ($i=0;$i<count($_SESSION['reservas']['inserts']);$i++){
				//qrys
				$fecha_split=split("-",$_SESSION['reservas']['inserts'][$i]['fecha_llegada']);
				$fecha_salida=mktime (0,0,0,$fecha_split[1],$fecha_split[2]+$_SESSION['reservas']['inserts'][$i]['pernoctas'],$fecha_split[0]);
				$fecha_salida=strftime("%Y-%m-%d",$fecha_salida);
				$qry_ins_det="INSERT INTO detalles (DNI_PRA, Fecha_Llegada, Fecha_Salida, PerNocta, Llegada_Tarde, Ingreso, Nombre_Empleado, Fecha_Reserva, Localizador_Reserva,Observaciones_PRA) VALUES ('".$dni."', '".$_SESSION['reservas']['inserts'][$i]['fecha_llegada']."', '".$fecha_salida."', '".$_SESSION['reservas']['inserts'][$i]['pernoctas']."', '".$llega_tarde."', '".$ingreso."', '".$empleado."', '".$fecha_reserva."', '','".$observaciones."')";
				//INSERT EN DETALLES
				if ($i == 0){$ingreso = 0;/*El ingreso solo se introducirá en los detalles de la primera reserva*/}
				$res_temp=mysql_query($qry_ins_det);
				//echo "<br>qry_det=".$qry_ins_det;
				//if ($res_temp){echo "<br>det:ok";}
				for ($j=0;$j<count($_SESSION['reservas']['inserts'][$i]['habs']);$j++){
					$qry_ins_res="INSERT INTO reserva (DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas, Num_Camas_Indiv) VALUES('".$dni."', '".$_SESSION['reservas']['inserts'][$i]['fecha_llegada']."', '".$_SESSION['reservas']['inserts'][$i]['habs'][$j]['id']."',  '".$_SESSION['reservas']['inserts'][$i]['habs'][$j]['camas']."', '')";
					//echo ("<BR>qry_Reservas".$j.":: ".$qry_ins_res);
					//INSERT EN RESERVAS
					$res_temp=mysql_query($qry_ins_res);
					//if ($res_temp){echo "<br>res:ok";}
				}
			}

			//Una ver realizado la insercion de datos, eliminamos la variable de sesion y volvemos a nueva reserva
			unset($_SESSION['reservas']);
			echo "
				<SCRIPT>
					window.location.href='?pag=reservas.php';
				</SCRIPT>
			";
			
		}
	}
}else{
	unset($_SESSION['reservas']);
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//****************** Creación del INSERT de ALTA para una NUEVA RESERVA *****************************//
///////////////////////////////////////////////////////////////////////////////////////////////////////

/*
	Si se recibe alta igual a si, qiere decir que queremos dar de alta
	una nueva reserva, por lo que recogeremos los datos del formulario
	de alta y los introduciremos en la base de datos. En caso de que el
	cliente que realiza la reserva ya esté dado de alta, sus datos se 
	modificarán si han variado.
*/
if(isset($_GET['alta']) && $_GET['alta'] == 'si'){
	$correcto='';
	$dni=$_POST['dni_new_reserva'];
	$nombre=$_POST['nombre_new_reserva'];
	$apellido1=$_POST['apellido1_new_reserva'];
	$apellido2=$_POST['apellido2_new_reserva'];
	$telefono=$_POST['telefono_new_reserva'];
	$email=$_POST['email_new_reserva'];
	$ingreso=$_POST['ingreso_new_reserva'];
	$fecha_llegada=$_POST['anio_new_reserva']."-".$_POST['mes_new_reserva']."-".$_POST['dia_new_reserva'];
	$pernocta=$_POST['pernoctas_new_reserva'];
	$camas=$_POST['camas_new_reserva'];
	$empleado=$_POST['empleado_new_reserva'];
	$llega_tarde=$_POST['llega_tarde_new_reserva'];
	$observaciones=$_POST['obs_new_reserva'];
	$fecha_reserva=date("Y-m-d");
	$fecha_salida = mktime(0,0,0,$_POST['mes_new_reserva'],$_POST['dia_new_reserva']+$pernocta,$_POST['anio_new_reserva']);
	$fecha_salida=strftime("%Y-%m-%d",$fecha_salida);
	
	
	//Verifico que el cliente no tenga ninguna resreva para los dias seleccionados
	$qry_det_ver=mysql_query("SELECT * FROM detalles WHERE (DNI_PRA = '".$dni."')");
	if ($qry_det_ver){
		for ($i=0;$i<mysql_num_rows($qry_det_ver);$i++){
			$tup_det_ver = mysql_fetch_array($qry_det_ver);
			if (($fecha_llegada >= $tup_det_ver['Fecha_Llegada'] && $fecha_llegada < $tup_det_ver['Fecha_Salida']) || ($fecha_salida > $tup_det_ver['Fecha_Llegada'] && $fecha_salida < $tup_det_ver['Fecha_Salida'])){
				$split_llegada=split("-",$tup_det_ver['Fecha_Llegada']);
				$split_salida=split("-",$tup_det_ver['Fecha_Salida']);
				echo '
					<SCRIPT>
						alert ("No se ha podido dar de alta la reserva:\n\r El cliente ya tiene una reserva del '.$split_llegada[2].' del '.$split_llegada[1].' al '.$split_salida[2].' del '.$split_salida[1].'");
						window.location.href="?pag=reservas.php";
					</SCRIPT>
				';
				exit();				
			}
		}
	}

	//Comprovación de PRA: Buscamos en la Base de Datos si este cliente ya existe.
	//Si es así, modificamos sus datos. Si no, lo dejamos como estaba
	$qry_temp="SELECT * FROM pra WHERE DNI_PRA = '".$dni."' ;";
	$res_temp=mysql_query($qry_temp);
	if (mysql_num_rows($res_temp)){	//Si existe un registro con ese dni, creamos un instruccion UPDATE
		$tupla_temp=mysql_fetch_array($res_temp);
		$cambiar=false;
		$qry_temp="UPDATE pra SET ";
		if ($tupla_temp['Nombre_PRA']!=$nombre){
			$qry_temp=$qry_temp." Nombre_PRA = '".$nombre."',";
			$cambiar=true;
		}
		if ($tupla_temp['Apellido1_PRA']!=$apellido1){
			$qry_temp=$qry_temp." Apellido1_PRA = '".$apellido1."',";
			$cambiar=true;
		}
		if ($tupla_temp['Apellido2_PRA']!=$apellido2){
			$qry_temp=$qry_temp." Apellido2_PRA = '".$apellido2."',";
			$cambiar=true;
		}
		if ($tupla_temp['Tlno_PRA']!=$telefono){
			$qry_temp=$qry_temp." Tfno_PRA = '".$telefono."',";
			$cambiar=true;
		}
		if ($tupla_temp['Email_PRA']!=$email){
			$qry_temp=$qry_temp." Email_PRA = '".$email."',";
			$cambiar=true;
		}
		if ($cambiar){
			$qry_new_pra=substr($qry_temp,0,strlen($qry_temp)-1)." WHERE DNI_PRA = '".$dni."'";
		}else{$qry_new_pra='';}
	}else{
		//Si no existe registro con ese dni, creamos una instrucion INSERT
		$qry_new_pra="INSERT INTO pra (DNI_PRA, Nombre_PRA, Apellido1_PRA, Apellido2_PRA, Tfno_PRA, Email_PRA) VALUES ('".$dni."', '".$nombre."', '".$apellido1."', '".$apellido2."', '".$telefono."', '".$email."')";
	}
	

	//Insert en la tabla PRA
	if ($qry_new_pra != ''){
		$res_new_pra=mysql_query($qry_new_pra);
		if (!$res_new_pra){
			$correcto += '\n - Datos del cliente';
		}
	}

	
	//Insert en la tabla DETALLES
	$qry_new_det="INSERT INTO detalles (DNI_PRA, Fecha_Llegada, Fecha_Salida, PerNocta, Llegada_Tarde, Ingreso, Nombre_Empleado, Fecha_Reserva, Localizador_Reserva, Observaciones_PRA) VALUES ('".$dni."', '".$fecha_llegada."', '".$fecha_salida."', '".$pernocta."', '".$llega_tarde."', '".$ingreso."', '".$empleado."', '".$fecha_reserva."', '', '".$observaciones."')";
	$res_new_det=mysql_query($qry_new_det);
	if (!$res_new_det){
		$correcto += '\n - Datos de la reserva';
	}

	$ing=0;	//en esta variable guardamos el ingreso que se deberia realizar, PERO NO ME APETECE, ASI KE LO DEJO PARA MAS TARDE

	//Inserción en la tabla RESERVA
	$insert_reserva = true;
	for($i=0;$i<count($habita);$i++){
		if($_POST['camas_hab_'.$habita[$i]['id']]>0){
			$qry_new_res="INSERT INTO reserva (DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas, Num_Camas_Indiv) VALUES('".$dni."', '".$fecha_llegada."', '".$habita[$i]['id']."',  '".$_POST['camas_hab_'.$habita[$i]['id']]."', null)";
			$result_res=mysql_query($qry_new_res);
			if (!$result_res){$insert_reserva = false;}
		}
	}
	if ($insert_reserva == false){
		$correcto += '\n - Asignación de habitaciones';
	}
	
	if ($correcto != ''){
		echo '
			<SCRIPT>
				alert ("No se ha podido dar de alta la reserva:\n Ha ocurrido un fallo en:'.$correcto.'");
				window.location.href="?pag=reservas.php";
			</SCRIPT>
		';
		exit();
	}else{
		echo "
			<SCRIPT> 
				window.location.href='?pag=reservas.php';
			</SCRIPT>
		";
	}
}else{

///////////////////////////////////////////////////////////////////////////////////////////
//******************Busqueda del cliente al introducir el DNI ***************************//
///////////////////////////////////////////////////////////////////////////////////////////

/*
Al cambiar los datos del campo dni_new_reserva automaticamente se busca en la base de datos
si existen datos para dicho dni en la tabla cliente. Si existe, se introducen los resultados 
en el formulario y en el array $datos. Sino, se conservan los datos anteriormente introducidos
*/

	if (isset($_GET['buscar'])){
		//echo "entro en buscar<BR>";
		$qry_buscar_dni="SELECT * FROM cliente WHERE DNI_Cl = '".$_GET['buscar']."'";
		$res_buscar_dni=mysql_query($qry_buscar_dni);
		if (mysql_num_rows($res_buscar_dni)>0){
			//echo "hay resultados <br>";
			$tupla_buscar=mysql_fetch_array($res_buscar_dni);
			$datos['dni']=$tupla_buscar['DNI_Cl'];
			$datos['nombre']=$tupla_buscar['Nombre_Cl'];
			$datos['apellido1']=$tupla_buscar['Apellido1_Cl'];
			$datos['apellido2']=$tupla_buscar['Apellido2_Cl'];
			$datos['telefono']=$tupla_buscar['Tfno_Cl'];
			$datos['email']=$tupla_buscar['Email_Cl'];
		}
		else{
			if (isset($_POST['dni_new_reserva'])){$datos['dni']=$_POST['dni_new_reserva'];}
			if (isset($_POST['nombre_new_reserva'])){$datos['nombre']=$_POST['nombre_new_reserva'];}
			if (isset($_POST['apellido1_new_reserva'])){$datos['apellido1']=$_POST['apellido1_new_reserva'];}
			if (isset($_POST['apellido2_new_reserva'])){$datos['apellido2']=$_POST['apellido2_new_reserva'];}
			if (isset($_POST['telefono_new_reserva'])){$datos['telefono']=$_POST['telefono_new_reserva'];}
			if (isset($_POST['email_new_reserva'])){$datos['email']=$_POST['email_new_reserva'];}
			if (isset($_POST['ingreso_new_reserva'])){$datos['ingreso']=$_POST['ingreso_new_reserva'];}
			if (isset($_POST['empleado_new_reserva'])){$datos['empleado']=$_POST['empleado_new_reserva'];}
			if (isset($_POST['tarde_new_reserva'])){$datos['llegada_tarde']=$_POST['tarde_new_reserva'];}
			if (isset($_POST['camas_new_reserva'])){$datos['camas']=$_POST['camas_new_reserva'];}
			if (isset($_POST['obs_new_reserva'])){$datos['observaciones']=$_POST['obs_new_reserva'];}
		}
		
	}
	else{
//******************Guardamos los datos de la Reserva que han sido introducidos**********
		if (isset($_POST['dni_new_reserva'])){$datos['dni']=$_POST['dni_new_reserva'];}
		if (isset($_POST['nombre_new_reserva'])){$datos['nombre']=$_POST['nombre_new_reserva'];}
		if (isset($_POST['apellido1_new_reserva'])){$datos['apellido1']=$_POST['apellido1_new_reserva'];}
		if (isset($_POST['apellido2_new_reserva'])){$datos['apellido2']=$_POST['apellido2_new_reserva'];}
		if (isset($_POST['telefono_new_reserva'])){$datos['telefono']=$_POST['telefono_new_reserva'];}
		if (isset($_POST['email_new_reserva'])){$datos['email']=$_POST['email_new_reserva'];}
		if (isset($_POST['ingreso_new_reserva'])){$datos['ingreso']=$_POST['ingreso_new_reserva'];}
		if (isset($_POST['empleado_new_reserva'])){$datos['empleado']=$_POST['empleado_new_reserva'];}
		if (isset($_POST['tarde_new_reserva'])){$datos['llegada_tarde']=$_POST['tarde_new_reserva'];}
		if (isset($_POST['camas_new_reserva'])){$datos['camas']=$_POST['camas_new_reserva'];}
		if (isset($_POST['obs_new_reserva'])){$datos['observaciones']=$_POST['obs_new_reserva'];}
	}

}


////////////////////////////////////////////////////////////////////////////////////////////////////
//*************************** Ejecucion de BAJA para ELIMINAR PERNOCTA ***************************//
////////////////////////////////////////////////////////////////////////////////////////////////////

/*
Para eliminar pernoctas, recibiremos la variable baja
Con esto se procederá a eliminar los datos de dicha reserva de la base de datos
*/
if (isset($_GET['baja'])){
	$qry_eliminar_det=("DELETE FROM detalles WHERE (DNI_PRA = '".$_GET['dni']."' AND Fecha_Llegada = '".$_GET['eliminar']."');");
	$qry_eliminar_res=("DELETE FROM reserva WHERE (DNI_PRA = '".$_GET['dni']."' AND Fecha_Llegada = '".$_GET['eliminar']."');");
	$res_eliminar_det=mysql_query($qry_eliminar_det);
	$res_eliminar_res=mysql_query($qry_eliminar_res);
	if ($res_eliminar_det && $res_eliminar_res){}
	$sel_temp="SELECT * FROM reserva WHERE DNI_PRA = '".$_GET['dni']."'";
	$res_temp=mysql_query($sel_temp);
	if (mysql_num_rows($res_temp)<=0){
		//Si no hay ninguna reserva mas con ese DNI, se eliminan tambien los datos de la tabla PRA
		$qry_eliminar_pra="DELETE FROM pra WHERE (DNI_PRA = '".$_GET['dni']."')";
		$res_eliminar_pra=mysql_query($qry_eliminar_pra);
		if ($res_eliminar_pra){}
	}
	
// Comprovamos desde donde se ha accedido a la baja de reservas y se envia al usuario a la pagina de origen
	if (isset($_POST['busqueda_baja']) && $_POST['busqueda_baja']=='busqueda'){
		echo "
			<SCRIPT> 
				window.location.href='?pag=busq.php';
			</SCRIPT>
		";
	}else{
		if (isset($_POST['busqueda_baja']) && $_POST['busqueda_baja']=='habitaciones'){
			echo "
				<SCRIPT> 
					window.location.href='?pag=gdh.php';
				</SCRIPT>
			";
		}else{
			echo "
				<SCRIPT> 
					window.location.href='?pag=reservas.php';
				</SCRIPT>
			";
		}
	}
}


////////////////////////////////////////////////////////////////////////////////////////////////////
//************************ Ejecucion de MODIFICAR para MODIFICAR PERNOCTA ************************//
////////////////////////////////////////////////////////////////////////////////////////////////////

/*
Para modificar la reserva
*/
if (isset($_GET['mod'])){
	$dni=$_POST['dni_mod_reserva'];
	$nombre=$_POST['nombre_mod_reserva'];
	$apellido1=$_POST['apellido1_mod_reserva'];
	$apellido2=$_POST['apellido2_mod_reserva'];
	$telefono=$_POST['telefono_mod_reserva'];
	$email=$_POST['email_mod_reserva'];
	$fecha_llegada=$_POST['anio_mod_reserva']."-".$_POST['mes_mod_reserva']."-".$_POST['dia_mod_reserva'];
	$pernocta=$_POST['pernoctas_mod_reserva'];
	$camas=$_POST['camas_mod_reserva'];
	$ingreso=$_POST['ingreso_mod_reserva'];
	$empleado=$_POST['empleado_mod_reserva'];
	$llega_tarde=$_POST['llega_tarde_mod_reserva'];
	$fecha_llegada_orig=$_GET['llegada'];
	$observaciones=$_POST['obs_mod_reserva'];
	$fecha_salida=mktime(0,0,0,$_POST['mes_mod_reserva'],$_POST['dia_mod_reserva']+$pernocta,$_POST['anio_mod_reserva']);
	$fecha_salida=strftime("%Y-%m-%d",$fecha_salida);

	$qry_mod_pra="UPDATE pra SET DNI_PRA='".$dni."', Nombre_PRA='".$nombre."', Apellido1_PRA='".$apellido1."', Apellido2_PRA='".$apellido2."', Tfno_PRA='".$telefono."', Email_PRA='".$email."' WHERE (DNI_PRA = '".$_GET['dni']."')";
	//echo "<BR> pra: ".$qry_mod_pra;
	$qry_mod_det="UPDATE detalles SET DNI_PRA='".$dni."', Fecha_Llegada='".$fecha_llegada."', Fecha_Salida='".$fecha_salida."', PerNocta='".$pernocta."', Llegada_Tarde='".$llega_tarde."', Ingreso='".$ingreso."', Nombre_Empleado='".$empleado."', Observaciones_PRA='".$observaciones."' WHERE (DNI_PRA='".$dni."' AND Fecha_Llegada='".$fecha_llegada_orig."')";
	//echo "<BR> det: ".$qry_mod_det;
	$mod_res=false;
	$qry_mod_res_sel="SELECT * FROM reserva WHERE DNI_PRA = '".$dni."' AND Fecha_Llegada='".$_GET['llegada']."'";
	//echo "<br>".$qry_mod_res_sel;
	$res_mod_res_sel=mysql_query($qry_mod_res_sel);
	for ($i=0;$i<mysql_num_rows($res_mod_res_sel);$i++){
		$tupla_mod_sel=mysql_fetch_array($res_mod_res_sel);
		for ($j=0;$j<count($habita);$j++){
			if ($_POST['camas_hab_'.$habita[$j]['id']]>0){
				if ($habita[$j]['id']==$tupla_mod_sel['Id_Hab']){
					if ($tupla_mod_sel['Num_Camas']!=$_POST['camas_hab_'.$habita[$j]['id']]){
						$mod_res=true;
					}
				}else{
					$mod_res=true;
				}
			}
		}	
	}
	
	$res_mod_pra=mysql_query($qry_mod_pra);
	$res_mod_det=mysql_query($qry_mod_det);
	//if ($res_mod_det){echo "--> TRUE!";}else{echo "--> FALSE!";}

	if ($mod_res==true){
		$res_mod_res_del=mysql_query("DELETE FROM reserva WHERE (DNI_PRA = '".$dni."' AND Fecha_Llegada='".$fecha_llegada_orig."')");
		for ($j=0;$j<count($habita);$j++){
			if ($_POST['camas_hab_'.$habita[$j]['id']]>0){
				$ins_mod_res=mysql_query("INSERT INTO reserva (DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas, Num_Camas_Indiv) VALUES ('".$dni."', '".$fecha_llegada."', '".$habita[$j]['id']."',  '".$_POST['camas_hab_'.$habita[$j]['id']]."', '')");
				//echo "<br>INSERT INTO reserva (DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas, Num_Camas_Indiv) VALUES ('".$dni."', '".$fecha_llegada."', '".$habita[$j]['id']."',  '".$_POST['camas_hab_'.$habita[$j]['id']]."', '')";
			}
		}
	}else{
		$res_mod_res_up=mysql_query("UPDATE reserva SET Fecha_Llegada = '".$fecha_llegada."' and DNI_PRA = '".$dni."' WHERE (DNI_PRA = '".$_GET['dni']."' and Fecha_Llegada='".$fecha_llegada_orig."')");
	}
	if (isset($_POST['origen_mod'])){
		if ($_POST['origen_mod']=='habitaciones'){
			echo "
				<SCRIPT> 
					window.location.href='?pag=gdh.php';
				</SCRIPT>
			";
		}
		if ($_POST['origen_mod']=='busquedas'){
			echo "
				<SCRIPT> 
					window.location.href='?pag=busq.php';
				</SCRIPT>
			";
		}
	}else{
		echo "
			<SCRIPT> 
				window.location.href='?pag=reservas.php';
			</SCRIPT>
		";
	}
}else{
	if (isset($_POST['dni_mod_reserva'])){$datos['dni']=$_POST['dni_mod_reserva'];}
	if (isset($_POST['nombre_mod_reserva'])){$datos['nombre']=$_POST['nombre_mod_reserva'];}
	if (isset($_POST['apellido1_mod_reserva'])){$datos['apellido1']=$_POST['apellido1_mod_reserva'];}
	if (isset($_POST['apellido2_mod_reserva'])){$datos['apellido2']=$_POST['apellido2_mod_reserva'];}
	if (isset($_POST['telefono_mod_reserva'])){$datos['telefono']=$_POST['telefono_mod_reserva'];}
	if (isset($_POST['email_mod_reserva'])){$datos['email']=$_POST['email_mod_reserva'];}
	if (isset($_POST['ingreso_mod_reserva'])){$datos['ingreso']=$_POST['ingreso_mod_reserva'];}
	if (isset($_POST['empleado_mod_reserva'])){$datos['empleado']=$_POST['empleado_mod_reserva'];}
	if (isset($_POST['tarde_mod_reserva'])){$datos['llegada_tarde']=$_POST['tarde_mod_reserva'];}
	if (isset($_POST['camas_mod_reserva'])){$datos['camas']=$_POST['camas_mod_reserva'];}
	if (isset($_POST['obs_mod_reserva'])){$datos['observaciones']=$_POST['obs_mod_reserva'];}
}



$cliente=array();




/////////////////////////////////////////////////////////////////////////////////////////////////
//******************** Almacenaje de los datos de las reservas y pernoctas ********************//
/////////////////////////////////////////////////////////////////////////////////////////////////

/*
Aqui vamos a almacenar en sus respecivos arrays los datos necesarios para reflejar el estado del
albergue en la fecha seleccionada y toda la estancia en la que se quiera reservar. En el array
reservas_dia se guardarán los datos de las reservas, y en el de pernoctas los datos de las per-
noctas de peregrinos, alberguistas y grupos. Ademas guardaremos en el array cliente los datos de
el cliente seleccionado en detalles, eliminar o modificar.
*/
$reservas_dia=array();	//En esta variable vamos a guardar TODOS LOS DATOS DE LAS RESERVAS
$pernoctas=array();		//En este array vamos a guardar los datos de las pernoctas que hay

if (isset($_GET['temp'])){
	$noches=$_SESSION['reservas']['noches'];
	$fecha_futura=mktime(0,0,0,$fecha_mes,$fecha_dia+$noches,$fecha_anio);
	$fecha_futura=strftime("%Y-%m-%d",$fecha_futura);
}else{
	if (isset($_GET['noches'])){	//Si tenemos mas de una noche:
		$noches=$_GET['noches'];
		$fecha_futura=mktime(0,0,0,$fecha_mes,$fecha_dia+$_GET['noches'],$fecha_anio);
		$fecha_futura=strftime("%Y-%m-%d",$fecha_futura);
	}else{
		$fecha_futura=mktime(0,0,0,$fecha_mes,$fecha_dia+1,$fecha_anio);
		$fecha_futura=strftime("%Y-%m-%d",$fecha_futura);
	}
 }

$qry_futura="
	SELECT * FROM  detalles inner join pra WHERE (pra.DNI_PRA = detalles.DNI_PRA AND 
		(detalles.Fecha_Llegada >= '".$fecha."' OR detalles.Fecha_Salida > '".$fecha."') AND 
		(detalles.Fecha_Llegada < '".$fecha_futura."' OR detalles.Fecha_Salida < '".$fecha_futura."') AND
		(detalles.Fecha_Llegada != detalles.Fecha_Salida))
	
";

$res_futura=mysql_query($qry_futura);

for ($i=0;$i<mysql_num_rows($res_futura);$i++){
	$tupla_futura=mysql_fetch_array($res_futura);
	$res_temp=mysql_query("SELECT * FROM reserva WHERE DNI_PRA ='".$tupla_futura['DNI_PRA']."' AND Fecha_Llegada='".$tupla_futura['Fecha_Llegada']."'");
	for ($j=0;$j<mysql_num_rows($res_temp);$j++){
		$tupla_fut_habs=mysql_fetch_array($res_temp);
		$reservas_dia[$i]['hab'][$j]=$tupla_fut_habs['Id_Hab'];
		$reservas_dia[$i]['camas'][$j]=$tupla_fut_habs['Num_Camas'];
	}
	$reservas_dia[$i]['dni']=$tupla_futura['DNI_PRA'];
	$reservas_dia[$i]['nombre']=$tupla_futura['Nombre_PRA'];
	$reservas_dia[$i]['apellido1']=$tupla_futura['Apellido1_PRA'];
	$reservas_dia[$i]['apellido2']=$tupla_futura['Apellido2_PRA'];
	$reservas_dia[$i]['tfno']=$tupla_futura['Tfno_PRA'];
	$reservas_dia[$i]['email']=$tupla_futura['Email_PRA'];
	$reservas_dia[$i]['observaciones']=$tupla_futura['Observaciones_PRA'];
	$reservas_dia[$i]['fecha_llegada']=$tupla_futura['Fecha_Llegada'];
	$reservas_dia[$i]['fecha_salida']=$tupla_futura['Fecha_Salida'];
	$reservas_dia[$i]['pernocta']=$tupla_futura['PerNocta'];
	$reservas_dia[$i]['llegada_tarde']=$tupla_futura['Llegada_Tarde'];
	$reservas_dia[$i]['ingreso']=$tupla_futura['Ingreso'];
	$reservas_dia[$i]['empleado']=$tupla_futura['Nombre_Empleado'];
	$reservas_dia[$i]['fecha_reserva']=$tupla_futura['Fecha_Reserva'];
}

// Hago la consulta para sacar los datos del cliente seleccionado

if (isset($_GET['dni']) && isset($_GET['llegada'])){
	
	$qry_cliente="SELECT * FROM detalles inner join pra WHERE (detalles.DNI_PRA = pra.DNI_PRA AND pra.DNI_PRA = '".$_GET['dni']."' AND detalles.Fecha_Llegada = '".$_GET['llegada']."')";
	
	$res_cliente=mysql_query($qry_cliente);

	for ($i=0;$i<mysql_num_rows($res_cliente);$i++){
		$fila_cliente=mysql_fetch_array($res_cliente);
		$cliente['dni']=$fila_cliente['DNI_PRA'];
		$cliente['nombre']=$fila_cliente['Nombre_PRA'];
		$cliente['apellido1']=$fila_cliente['Apellido1_PRA'];
		$cliente['apellido2']=$fila_cliente['Apellido2_PRA'];
		$cliente['email']=$fila_cliente['Email_PRA'];
		$cliente['telefono']=$fila_cliente['Tfno_PRA'];
		$cliente['observaciones']=$fila_cliente['Observaciones_PRA'];
		$cliente['llegada']=$fila_cliente['Fecha_Llegada'];
		$cliente['salida']=$fila_cliente['Fecha_Salida'];
		$cliente['pernocta']=$fila_cliente['PerNocta'];
		$cliente['llegada_tarde']=$fila_cliente['Llegada_Tarde'];
		$cliente['ingreso']=$fila_cliente['Ingreso'];
		$cliente['empleado']=$fila_cliente['Nombre_Empleado'];
		$cliente['fecha_reserva']=$fila_cliente['Fecha_Reserva'];
		$cliente['localizador']=$fila_cliente['Localizador_Reserva'];

		$res_habs_cliente = mysql_query("SELECT * FROM reserva WHERE (DNI_PRA = '".$_GET['dni']."' AND Fecha_Llegada = '".$_GET['llegada']."')");
		for ($j=0;$j<mysql_num_rows($res_habs_cliente);$j++){
			$habs_cliente=mysql_fetch_array($res_habs_cliente);
			$cliente['hab'][$j]=$habs_cliente['Id_Hab'];
			$cliente['camas'][$j]=$habs_cliente['Num_Camas'];
		}
	}
}


$cont=0;	//Contador para las pernoctas

$qry_peregrinos="
		SELECT * FROM pernocta_p WHERE (
		pernocta_p.Fecha_Llegada >= '".$fecha."' OR pernocta_p.Fecha_Salida > '".$fecha."') AND 
		(pernocta_p.Fecha_Llegada < '".$fecha_futura."' OR pernocta_p.Fecha_Salida < '".$fecha_futura."')
	";

$res_peregrinos=mysql_query($qry_peregrinos);
$i=0;
while ($i<mysql_num_rows($res_peregrinos)){
	$tupla_per=mysql_fetch_array($res_peregrinos);
	$pernoctas[$cont]['dni']=$tupla_per['DNI_Cl'];
	$pernoctas[$cont]['fecha_llegada']=$tupla_per['Fecha_Llegada'];
	$pernoctas[$cont]['fecha_salida']=$tupla_per['Fecha_Salida'];
	$pernoctas[$cont]['habs'][0]['camas']=1;
	$pernoctas[$cont]['habs'][0]['id_hab']=$tupla_per['Id_Hab'];
	$i++; $cont++;
}

$qry_alberguista="
		SELECT * FROM pernocta WHERE (
		pernocta.Fecha_Llegada >= '".$fecha."' OR pernocta.Fecha_Salida > '".$fecha."') AND 
		(pernocta.Fecha_Llegada < '".$fecha_futura."' OR pernocta.Fecha_Salida < '".$fecha_futura."'
	)";

$res_alberguista=mysql_query($qry_alberguista);
$i=0;
while ($i<mysql_num_rows($res_alberguista)){
	$tupla_alb=mysql_fetch_array($res_alberguista);
	$pernoctas[$cont]['dni']=$tupla_alb['DNI_Cl'];
	$pernoctas[$cont]['habs'][0]['id_hab']=$tupla_alb['Id_Hab'];
	$pernoctas[$cont]['fecha_llegada']=$tupla_alb['Fecha_Llegada'];
	$pernoctas[$cont]['fecha_salida']=$tupla_alb['Fecha_Salida'];
	$pernoctas[$cont]['habs'][0]['camas']=1;
	$i++; $cont++;
}

$qry_grupos="
		SELECT * FROM estancia_gr INNER JOIN colores WHERE 
		(estancia_gr.Id_Color = colores.Id_Color) AND 
		(estancia_gr.Fecha_Llegada >= '".$fecha."' OR estancia_gr.Fecha_Salida > '".$fecha."') AND 
		(estancia_gr.Fecha_Llegada < '".$fecha_futura."' OR estancia_gr.Fecha_Salida < '".$fecha_futura."') ORDER BY estancia_gr.Fecha_Llegada
	";

$res_grupos=mysql_query($qry_grupos);
$i=0;

while ($i<mysql_num_rows($res_grupos)){
	$tupla_gru=mysql_fetch_array($res_grupos);
	$pernoctas[$cont]['dni']=$tupla_gru['DNI_Repres'];
	$pernoctas[$cont]['fecha_llegada']=$tupla_gru['Fecha_Llegada'];
	$pernoctas[$cont]['fecha_salida']=$tupla_gru['Fecha_Salida'];
	$pernoctas[$cont]['color']=$tupla_gru['Color'];
	$qry_gru_temp="SELECT * FROM pernocta_gr WHERE (Nombre_Gr = '".$tupla_gru['Nombre_Gr']."' and Fecha_Llegada = '".$tupla_gru['Fecha_Llegada']."') GROUP BY Id_Hab";
	$res_gru_temp=mysql_query($qry_gru_temp);
	for ($p=0;$p<mysql_num_rows($res_gru_temp);$p++){
		$tupla_gru_temp = mysql_fetch_array($res_gru_temp);
		$pernoctas[$cont]['habs'][$p]['camas']=$tupla_gru_temp['Num_Personas'];
		$pernoctas[$cont]['habs'][$p]['id_hab']=$tupla_gru_temp['Id_Hab'];
	}
	$cont++;
	$i++;
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//******************************** Estado de las habitaciones *********************************//
/////////////////////////////////////////////////////////////////////////////////////////////////


/*
Ahora necesito guardar los datos del estado de cada habitacion, por lo que habra que compararé 
las fechas de entrada y salida de los datos que tengo y guardaré en el array $habita las camas:
ocupadas (aquellas ocupadas por una reserva), temps (aquellas ocupadas a partir de cierta fecha 
-solo cuando la pernocta es de mas de una noche-) y pernoctas(aquellas ocupadas por una pernocta
de peregrino, alberguista o grupo)
*/
if (isset($_GET['noches'])){
	//Ahora almaceno las camas ocupadas todos los dias, esto es, todas
	//aquellas que estaban ocupadas desde el primer dia de la estancia
	for ($hab=0;$hab<count($habita);$hab++){
		//Primero almacenamos las reservas
		for ($i=0;$i<count($reservas_dia);$i++){
			if ($reservas_dia[$i]['fecha_llegada'] <= $fecha){
				for ($j=0;$j<count($reservas_dia[$i]['hab']);$j++){
					if ($reservas_dia[$i]['hab'][$j] == $habita[$hab]['id'] && $reservas_dia[$i]['camas'][$j] > 0){
						if ($_GET['op']==2 && $_GET['dni']==$reservas_dia[$i]['dni']){}else{
							$habita[$hab]['ocupadas'] += $reservas_dia[$i]['camas'][$j];
						}
					}
				}
			}
		}
		//Almacenamos las pernoctas
		for ($i=0;$i<count($pernoctas);$i++){
			if ($pernoctas[$i]['fecha_llegada'] <= $fecha){
				for ($j=0;$j<count($pernoctas[$i]['habs']);$j++){
					if ($pernoctas[$i]['habs'][$j]['id_hab'] == $habita[$hab]['id'] && $pernoctas[$i]['habs'][$j]['camas'] > 0){
						if (isset($pernoctas[$i]['color'])){
							$cont_gr = count($habita[$hab]['grupos']);
							$habita[$hab]['pernoctas_gr'] = $habita[$hab]['pernoctas_gr'] + $pernoctas[$i]['habs'][$j]['camas'];
							$habita[$hab]['grupos'][$cont_gr]['color'] = $pernoctas[$i]['color'];
							$habita[$hab]['grupos'][$cont_gr]['camas'] = $pernoctas[$i]['habs'][$j]['camas'];
						}else{
							$habita[$hab]['pernoctas'] += $pernoctas[$i]['habs'][$j]['camas'];
						}
					}
				}
			}
		}
	}
	//Si se reciben noches, hay que contemplar el caso de que las camas puedan estar ocupadas temporalmente
	//Para esto, recorremos las noches e introducimos en el array $habita los datos pertinentes
	for ($hab=0;$hab<count($habita);$hab++){
		//Recorremos las habitaciones
		//En estas variables metemos la suma de todas las camas ocupadas ese día para luego comparar
		$max_camas = $habita[$hab]['pernoctas'] + $habita[$hab]['ocupadas'];
		$cont_temps = $max_camas;
		$habita[$hab]['temps'] = 0;
		for ($n=1;$n<=(intval($noches)-1);$n++){
			//Usaremos una fecha temporal para recorrer cada noche y comprovar el nº de camas ocupadas
			$fecha_temp=mktime(0,0,0,$fecha_mes,$fecha_dia+$n,$fecha_anio);
			$fecha_temp=strftime("%Y-%m-%d",$fecha_temp);
			//Y par cada habitacion recorremos las reservas y las pernoctas y miramos cuantas camas estan ocupadas
			for ($res=0;$res<count($reservas_dia);$res++){
				//Miramos las reservas. Si llegan, se suman, si salen se restan (ojo si estamos modificando, esas kedan fuera)
				if ($reservas_dia[$res]['fecha_llegada'] == $fecha_temp && $reservas_dia[$res]['fecha_llegada']!=$_GET['llegada'] && $reservas_dia[$res]['dni']!=$_GET['dni']){
					//Recorremos las reservas para ver si la habitacion coincide
					for ($f=0;$f<count($reservas_dia[$res]['hab']);$f++){
						if ($reservas_dia[$res]['hab'][$f] == $habita[$hab]['id'] && $reservas_dia[$res]['fecha_llegada'] == $fecha_temp){
							$cont_temps += $reservas_dia[$res]['camas'][$f];
						}
					}
				}
				if ($reservas_dia[$res]['fecha_salida'] == $fecha_temp && $reservas_dia[$res]['fecha_llegada']!=$_GET['llegada'] && $reservas_dia[$res]['dni']!=$_GET['dni']){
					//Recorremos las reservas para ver si la habitacion coincide
					for ($f=0;$f<count($reservas_dia[$res]['hab']);$f++){
						if ($reservas_dia[$res]['hab'][$f]['id_hab'] == $habita[$hab]['id']){
							$cont_temps -= $reservas_dia[$res]['camas'][$f];
						}
					}
				}
			}
			for ($res=0;$res<count($pernoctas);$res++){
			//Al igual que las reservas, descartamos las que tienen 
			//fecha_llegada anterior a la fecha actual, 
			//esas se almacenan luego como ocupadas
				if ($pernoctas[$res]['fecha_llegada'] == $fecha_temp){
					//Ahora comprovamos la fecha de la reserva
					//Recorremos las habitaciones de la reserva, si coinciden se añaden
					for ($i=0;$i<count($pernoctas[$res]['habs']);$i++){
						if ($pernoctas[$res]['habs'][$i]['id_hab'] == $habita[$hab]['id']){
							$cont_temps += $pernoctas[$res]['habs'][$i]['camas'];
						}
					}
				}
				if ($pernoctas[$res]['fecha_salida'] == $fecha_temp){
					//Ahora comprovamos la fecha de la reserva
					//Recorremos las habitaciones de la reserva, si coinciden se añaden
					for ($i=0;$i<count($pernoctas[$res]['habs']);$i++){
						if ($pernoctas[$res]['habs'][$i]['id_hab'] == $habita[$hab]['id']){
							$cont_temps -= $pernoctas[$res]['habs'][$i]['camas'];
						}
					}
				}
			}
			if ($cont_temps > $max_camas){
				//Si el numero de camas temporales es mayor al maximo de camas temporales 
				//globales de dicha habitacion, se almacena la diferencia con la fecha de
				//entrada que corresponda
				$pos = count($habita[$hab]['temp']);
				$dif = ($cont_temps - $max_camas);
				$habita[$hab]['temp'][$pos]['fecha_llegada'] = $fecha_temp;
				$habita[$hab]['temp'][$pos]['camas'] = $dif;
				$habita[$hab]['temps'] += $dif;
				$max_camas = $cont_temps;
			}
		}
	}
	//////////// Miramos a ver si las habitaciones cambian de tipo dentro de la fecha seleccionada ////////////

	$qry_habs_temps = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MIN(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha > '".$fecha."' AND Fecha < '".$fecha_futura."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab ";

	$res_habs_temps = mysql_query($qry_habs_temps);

	while ($fila_habs_temps = mysql_fetch_array($res_habs_temps)){
		for ($i=0;$i<count($habita);$i++){	
			if ($habita[$i]['id'] == $fila_habs_temps['Id_Hab']){
				$habita[$i]['cambio_tipo'] = Array();
				$habita[$i]['cambio_tipo']['fecha'] = $fila_habs_temps['Fecha'];
				$habita[$i]['cambio_tipo']['reservable'] = $fila_habs_temps['Reservable'];
			}
		}
	}
}else{
	for ($cont=0;$cont<count($habita);$cont++){
		//Si no se reciben noches, no hay camas temporalmente ocupadas
		for ($j=0;$j<count($reservas_dia);$j++){
			for ($k=0;$k<count($reservas_dia[$j]['hab']);$k++){
				if($habita[$cont]['id']==$reservas_dia[$j]['hab'][$k]){
					if ($_GET['op']==2 && $_GET['dni']==$reservas_dia[$j]['dni']){}else{
						$habita[$cont]['ocupadas']+=$reservas_dia[$j]['camas'][$k];
					}
				}
			}
		}
		for ($z=0;$z<count($pernoctas);$z++){
			for ($y=0;$y<count($pernoctas[$z]['habs']);$y++){
				if ($pernoctas[$z]['habs'][$y]['id_hab']==$habita[$cont]['id']){
					if (isset($pernoctas[$z]['color'])){
						$cont_gr = count($habita[$cont]['grupos']);
						$habita[$cont]['pernoctas_gr'] = $habita[$cont]['pernoctas_gr'] + $pernoctas[$z]['habs'][$y]['camas'];
						$habita[$cont]['grupos'][$cont_gr]['color'] = $pernoctas[$z]['color'];
						$habita[$cont]['grupos'][$cont_gr]['camas'] = $pernoctas[$z]['habs'][$y]['camas'];
					}else{
						$habita[$cont]['pernoctas']+=$pernoctas[$z]['habs'][$y]['camas'];
					}
				}
			}
		}
	}
}




?>
	

<div id="caja_superior">
	<div id="caja_superior_izquierda">
		
	<!-- ******************************************************************************************* -->
	<!-- *****************************<<<BLOQUE DE MODIFICAR_RESERVA>>>***************************** -->
	<!-- ******************************************************************************************* -->

		<div id='modificar_reserva' style='display:none; '>

		<FORM name='form_mod' ACTION='?pag=reservas.php&mod=<?php echo($cliente['llegada']);?>&fecha_cal=<?php echo($fecha_enviar);?>&dni=<?php echo($_GET['dni']);?>' METHOD='post'>
		<input type='hidden' name='res_num_pag' value='<?php echo $_SESSION['gdh']['dis_hab']['num_pag'];?>'>
			<?php
			//fechas para comprobaciones
			
			  $actuald=date(d);
              $actualm=date(m);
              $actualy=date(Y);


              
			
			// Si se viene desde otra pagina, lo almacenamos en un Hidden
			if ($_GET['accion']=='reserva'){
				echo "<input type='hidden' value='habitaciones' name='origen_mod'>";
			}else{
				if ($_GET['buscar']=='si'){
					echo "<input type='hidden' value='busquedas' name='origen_mod'>";
				}
			}
			?>
			 <input type="hidden"  name="dias" value="<?print($actuald);?>">
               <input type="hidden"  name="meses" value="<?print($actualm);?>">
               <input type="hidden"  name="anios" value="<?print($actualy);?>">
			<table border='0' class='tabla_general'>
				<thead>
					<td colspan='9' align='center'>
						<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:314px;'>
						Modificar Reserva
						</div>
						<div class='champi_derecha'>&nbsp;</div>
					</td>
				</thead>
				<tr><td>
				<table class='table_container'>
				<tr valign='center'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>D.N.I.:</td>
					<td align='left'><input type='text' size='15' class='input_formulario' name='dni_mod_reserva' onChange="this.value=this.value.toUpperCase();validadni(this.value,'modificar')" value='<?php 
					if (isset($datos['dni'])){echo $datos['dni'];}else{echo $cliente['dni'];}
					?>'></td>
				</tr>
				<tr >
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre:</td>
					<td align='left'><input type='text' name='nombre_mod_reserva' maxlength='20' class='input_formulario' value='<?php 
					if (isset($datos['nombre'])){echo $datos['nombre'];}else{echo $cliente['nombre'];}
					?>'onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr >
					<td>&nbsp;</td><td align='left' class='label_formulario'>Primer Apellido:</td>
					<td align='left'><input type='text' name='apellido1_mod_reserva' maxlength='30' class='input_formulario' value='<?php 
					if (isset($datos['apellido1'])){echo $datos['apellido1'];}else{echo $cliente['apellido1'];}
					?>'onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr >
					<td>&nbsp;</td><td align='left' class='label_formulario'>Segundo Apellido:</td>
					<td align='left'><input type='text' name='apellido2_mod_reserva' maxlength='30' class='input_formulario' value='<?php 
					if (isset($datos['apellido2'])){echo $datos['apellido2'];}else{echo $cliente['apellido2'];}
					?>'onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr >
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:</td>
					<td align='left'><input type='text' name='telefono_mod_reserva' class='input_formulario' value='<?php 
					if (isset($datos['telefono'])){echo $datos['telefono'];}else{echo $cliente['telefono'];}
					?>'></td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario'>E-mail:</td>
					<td align='left'><input type='text' name='email_mod_reserva' class='input_formulario' value='<?php 
					if (isset($datos['email'])){echo $datos['email'];}else{echo $cliente['email'];}
					?>'></td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario' colspan='2'>Llegada: &nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;

						<SELECT class='select_formulario' name='dia_mod_reserva' onChange='select_fecha_mod(this.value,this.name)'>

<?php

$fecha_temp=split("-",$cliente['llegada']);
if (isset($fecha_dia)){$mod_dia=$fecha_dia;}
else{$mod_dia=$fecha_temp[2];}
if (isset($fecha_mes)){$mod_mes=$fecha_mes;}
else{$mod_mes=$fecha_temp[1];}
if (isset($fecha_anio)){$mod_anio=$fecha_anio;}
else{$mod_anio=$fecha_temp[0];}

if ($mod_mes=="04" || $mod_mes=="06" || $mod_mes=="09" || $mod_mes=="11"){
	for ($i=1;$i<31;$i++){
		if(intval($mod_dia)==$i){
			if ($i<10){
				echo("<OPTION value='0".$i."' selected>0".$i."</OPTION>");
			}else{
				echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
			}
		}else{
			if ($i<10){
				echo("<OPTION value='0".$i."'>0".$i."</OPTION>");
			}else{
				echo("<OPTION value='".$i."'>".$i."</OPTION>");
			}
		}
	}
}else{
	if ($mod_mes=="02"){
		for ($i=1;$i<30;$i++){
			if(intval($mod_dia)==$i){
				if ($i<10){
					echo("<OPTION value='0".$i."' selected>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
				}
			}else{
				if ($i<10){
					echo("<OPTION value='0".$i."'>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."'>".$i."</OPTION>");
				}
			}
		}
	}else{
		for ($i=1;$i<32;$i++){
			if(intval($mod_dia)==$i){
				if ($i<10){
					echo("<OPTION value='0".$i."' selected>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
				}
			}else{
				if ($i<10){
					echo("<OPTION value='0".$i."'>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."'>".$i."</OPTION>");
				}
			}
		}
	}
}

?>
						</SELECT>
						<SELECT class='select_formulario' name='mes_mod_reserva' onChange='select_fecha_mod(this.value,this.name)'>
							<OPTION value='1' <?php if(intval($fecha_mes==1)){echo "selected";}?>> Enero </OPTION>
							<OPTION value='2' <?php if(intval($fecha_mes==2)){echo "selected";}?>> Febrero </OPTION>
							<OPTION value='3' <?php if(intval($fecha_mes==3)){echo "selected";}?>> Marzo </OPTION>
							<OPTION value='4' <?php if(intval($fecha_mes==4)){echo "selected";}?>> Abril </OPTION>
							<OPTION value='5' <?php if(intval($fecha_mes==5)){echo "selected";}?>> Mayo </OPTION>
							<OPTION value='6' <?php if(intval($fecha_mes==6)){echo "selected";}?>> Junio </OPTION>
							<OPTION value='7' <?php if(intval($fecha_mes==7)){echo "selected";}?>> Julio </OPTION>
							<OPTION value='8' <?php if(intval($fecha_mes==8)){echo "selected";}?>> Agosto </OPTION>
							<OPTION value='9' <?php if(intval($fecha_mes==9)){echo "selected";}?>> Septiembre </OPTION>
							<OPTION value='10' <?php if(intval($fecha_mes==10)){echo "selected";}?>> Octubre </OPTION>
							<OPTION value='11' <?php if(intval($fecha_mes==11)){echo "selected";}?>> Noviembre </OPTION>
							<OPTION value='12' <?php if(intval($fecha_mes==12)){echo "selected";}?>> Diciembre </OPTION>
						</SELECT>
						<SELECT class='select_formulario' name='anio_mod_reserva' onChange='select_fecha_mod(this.value,this.name)'>
<?php

for ($i=$fecha_anio;$i<($fecha_anio+2);$i++){
	if(intval($mod_anio)==$i){
		echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
	}else{
		echo("<OPTION value='".$i."'>".$i."</OPTION>");
	}
}

?>
						</SELECT>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario'> 		Noches:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type='text' size='3' name='pernoctas_mod_reserva' class='input_formulario' value='<?php 
					if (isset($_GET['noches']) && $_GET['op']==2){echo($_GET['noches']);}else{echo $cliente['pernocta'];}
					?>' onChange='poner_noches_mod(this.value)'>
						<span style='position:relative;top:5px;'><img src='../imagenes/botones/flecha.jpg' usemap='#cambio_noches_mod' border=0 ></span>
					</td>
					<td align='left' class='label_formulario'>Camas:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type='text' size='3' name='camas_mod_reserva' class='input_formulario' onChange='poner_camas(this.value)' value='<?php
						if (isset($datos['camas'])){echo $datos['camas'];}
						else{
							$camas=0;
							for ($i=0;$i<count($cliente['camas']);$i++){
								$camas+=intval($cliente['camas'][$i]);
							}
							echo $camas;
						}
					?>'>
						<span style='position:relative;top:5px;'><img src='../imagenes/botones/flecha.jpg' usemap='#cambio_camas_mod' border=0></span>	
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Ingreso:</td>
					<td align='left' class='label_formulario'><input type='text' size='4' name='ingreso_mod_reserva' class='input_formulario' value='<?php 
					if (isset($datos['ingreso'])){echo $datos['ingreso'];}else{echo $cliente['ingreso'];}
					?>' onChange='comp_ingreso("mod")'> (en euros)</td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre del Empleado:</td>
					<td align='left'><input type='text' name='empleado_mod_reserva' class='input_formulario' value='<?php 
					if (isset($datos['empleado'])){echo $datos['empleado'];}else{echo $cliente['empleado'];}
					?>'></td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario' valign='top' style='padding-top:15px;'>Llegada Tarde:</td>
					<td align='left'><textarea cols='19' rows='2' class='text_area_formulario' name='llega_tarde_mod_reserva'><?php 
					if (isset($datos['llegada_tarde'])){echo $datos['llegada_tarde'];}else{echo $cliente['llegada_tarde'];}
					?></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario' valign='top' style='padding-top:15px;'>Observaciones:</td>
					<td align='left'><textarea cols='19' rows='4' class='text_area_formulario' name='obs_mod_reserva'><?php 
					if (isset($datos['observaciones'])){echo $datos['observaciones'];}else{echo $cliente['observaciones'];}
					?></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align='center' height='50'> <a href='#' onClick='submit_formulario("mod")'><img src='../imagenes/botones-texto/aceptar.jpg' border=0 alt='Realizar la Modificación'></a></td>
					<td align='center' height='50'> <a href='#' onClick='cambiar("4")'><img src='../imagenes/botones-texto/cancelar.jpg' border=0 alt='Cancelar la Modificación'></a></td>
				</tr>
			</table>
			</td></tr></table>
			<?php
				//Se consultan las habitaciones y se crean Inputs Hidden para almacenar y enviar los datos del alta
				for($i=0;$i<count($habita);$i++){
					echo("<input type='hidden' id='".$habita[$i]['id']."' name='camas_hab_".$habita[$i]['id']."' value=0>");
				}

			?>
			</FORM>
		</div>
	
	<!-- ******************************************************************************************* -->
	<!-- ******************************<<< BLOQUE DE DETALLES_RESERVA >>>*************************** -->
	<!-- ******************************************************************************************* -->

		<div id='detalles_reserva' style='display:none;'>
			<table border=0 align='center' class='tabla_general'>
				<thead>
					<td colspan="9" align="center">
						<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:314px;'>
						Detalles de la Reserva
						</div>
						<div class='champi_derecha'>&nbsp;</div>
					</td>
				</thead>
				<tr><td>
				<table class='table_container' border=0>
				<tr height='25'>
					<td align='left' width='165px'><span style='margin-left:6px;' class='label_formulario'>D.N.I.:</span></td>
					<td align='left'> <span style='margin-left:6px;margin-right:4px;' class='texto_detalles'><?php echo $cliente['dni'];?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Nombre:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo truncar($cliente['nombre'],15);;?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Apellidos:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo truncar($cliente['apellido1'],15)." ".truncar($cliente['apellido2'],15);?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Teléfono:</span></td>
					<td align='left'> <span style='margin-left:6px;' class='texto_detalles'><?php echo $cliente['telefono'];?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>E-mail:</span></td>
					<td align='left'> <span style='margin-left:6px;'><input class='texto_detalles' name='email_detalles' style='border:0px;background-color:#f4fcff' value='<?php echo $cliente['email'];?>' readonly></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Llegada:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'>
					<?php
					$f_temp=split("-",$cliente['llegada']);
					echo $f_temp[2]."-".$f_temp[1]."-".$f_temp[0];
					?>
					</span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Noches:</span>&nbsp;&nbsp;&nbsp;<span class='texto_detalles'><?php echo $cliente['pernocta'];?></span></td>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Camas:</span>&nbsp;&nbsp;&nbsp;<span class='texto_detalles'>
					<?php
						$camas=0;
						for ($i=0;$i<count($cliente['camas']);$i++){
							$camas+=intval($cliente['camas'][$i]);
						}
						echo $camas;
					?>
					</span>	</td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Ingreso:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo $cliente['ingreso'];?> euros</span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Nombre del Empleado:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo truncar($cliente['empleado'],15);?></span></td>
				</tr>
				<?php if ($cliente['llegada_tarde']!=''){ ?>
				<tr height='25'>
					<td align='left'><span class='label_formulario' style='margin-left:6px;'>Llegada Tarde:</span></td>
					<td align='left'><textarea style='margin-left:6px;' class='text_area_formulario' cols='20' rows='3'><?php echo $cliente['llegada_tarde'];?></textarea></td>
				</tr>
				<?php } 
					
					 if ($cliente['observaciones']!=''){ ?>
				<tr height='25'>
					<td align='left' valign='top'><span class='label_formulario' style='margin-left:6px;'>Observaciones:</span></td>
					<td align='left'><textarea style='margin-left:6px;' class='text_area_formulario' cols='20' rows='3'><?php echo $cliente['observaciones'];?></textarea></td>
				</tr>
				<?php } 
				
					if (isset($_GET['busqueda']) && $_GET['busqueda']=='si'){
				?>
						<tr height='65px'>
							 <td colspan='2' align='center'><a href='#' onClick="window.location.href='?pag=busq.php'"><img 	src='../imagenes/botones-texto/aceptar.jpg' border=0 alt='Volver a Busquedas'></a>
						</tr>
				<?php 
					} else {
				?>
						<tr height='65px'>
							 <td colspan='2' align='center'><a href='#' onClick="cambiar('4')"><img src='../imagenes/botones-texto/aceptar.jpg' border=0 alt='Nueva Reserva'></a>
						</tr>
				<?php } ?>
				</table>
				</td></tr>
			</table>
		</div>
		
	<!-- ******************************************************************************************* -->
	<!-- ***********************************<<< BLOQUE DE ALTA_RESERVA >>>************************** -->
	<!-- ******************************************************************************************* -->
		
	
			<div id='alta_reserva' style='display:block;'>
			<FORM name='form_alta' ACTION='?pag=reservas.php&alta=si&fecha_cal=<?php echo($fecha_enviar);?>' METHOD='post'>
			
		
 			<?	
			 	// Fechas para comprobaciones
			 $actuald=date(d);
              $actualm=date(m);
              $actualy=date(Y);?>


               <input type="hidden"  name="dias" value="<?print($actuald);?>">
               <input type="hidden"  name="meses" value="<?print($actualm);?>">
               <input type="hidden"  name="anios" value="<?print($actualy);?>">

			<input type='hidden' name='res_num_pag' value='<?php echo $_SESSION['gdh']['dis_hab']['num_pag'];?>'>
			<table border='0' class='tabla_general'>
				<thead>
					<td colspan='9' align='center'>
						<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:314px;'>
						Nueva Reserva
						</div>
						<div class='champi_derecha'>&nbsp;</div>
					</td>
				</thead>
				<tr><td>
				<table class='table_container'>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario' valign='center'>D.N.I.:</td>
					<td align='left' valign='center'><input type='text' size='14' class='input_formulario' name='dni_new_reserva' onChange="this.value=this.value.toUpperCase();validadni(this.value,'alta')"<?php 
					if (isset($datos['dni'])){echo " value='".$datos['dni']."' ";}
					?>>&nbsp;&nbsp;<A href='#' onClick='abrir_busqueda()'><IMG src='../imagenes/botones/lupa.png' border=0 style='position:relative;top:5px;' alt='Buscar Clientes'></A></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre:</td>
					<td align='left'><input type='text' name='nombre_new_reserva' maxlength='20' class='input_formulario' <?php 
					if (isset($datos['nombre'])){echo " value='".$datos['nombre']."' ";}
					?>onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Primer Apellido:</td>
					<td align='left'><input type='text' name='apellido1_new_reserva' maxlength='30' class='input_formulario' <?php 
					if (isset($datos['apellido1'])){echo " value='".$datos['apellido1']."' ";}
					?>onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Segundo Apellido:</td>
					<td align='left'><input type='text' name='apellido2_new_reserva' maxlength='30' class='input_formulario' <?php 
					if (isset($datos['apellido2'])){echo " value='".$datos['apellido2']."' ";}
					?>onBlur="this.value=this.value.toUpperCase();"></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Teléfono:</td>
					<td align='left'><input type='text' name='telefono_new_reserva' class='input_formulario' <?php 
					if (isset($datos['telefono'])){echo " value='".$datos['telefono']."' ";}
					?>></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>E-mail:</td>
					<td align='left'><input type='text' name='email_new_reserva' class='input_formulario' <?php 
					if (isset($datos['email'])){echo " value='".$datos['email']."' ";}
					?>></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario' colspan='2'> Llegada:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<SELECT class='select_formulario' name='dia_new_reserva' onChange='select_fecha_alta(this.value,this.name)'>

<?
if ($fecha_mes=="04" || $fecha_mes=="06" || $fecha_mes=="09" || $fecha_mes=="11"){
	for ($i=1;$i<31;$i++){
		if(intval($fecha_dia)==$i){
			if ($i<10){
				echo("<OPTION value='0".$i."' selected>0".$i."</OPTION>");
			}else{
				echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
			}
		}else{
			if ($i<10){
				echo("<OPTION value='0".$i."'>0".$i."</OPTION>");
			}else{
				echo("<OPTION value='".$i."'>".$i."</OPTION>");
			}
		}
	}
}else{
	if ($fecha_mes=="02"){
		for ($i=1;$i<30;$i++){
			if(intval($fecha_dia)==$i){
				if ($i<10){
					echo("<OPTION value='0".$i."' selected>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
				}
			}else{
				if ($i<10){
					echo("<OPTION value='0".$i."'>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."'>".$i."</OPTION>");
				}
			}
		}
	}else{
		for ($i=1;$i<32;$i++){
			if(intval($fecha_dia)==$i){
				if ($i<10){
					echo("<OPTION value='0".$i."' selected>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
				}
			}else{
				if ($i<10){
					echo("<OPTION value='0".$i."'>0".$i."</OPTION>");
				}else{
					echo("<OPTION value='".$i."'>".$i."</OPTION>");
				}
			}
		}
	}
}

?>
						</SELECT>
						<SELECT class='select_formulario' name='mes_new_reserva' onChange='select_fecha_alta(this.value,this.name)'>
					<OPTION value='1' <?php if(intval($fecha_mes==1)){echo "selected";}?>> Enero </OPTION>
					<OPTION value='2' <?php if(intval($fecha_mes==2)){echo "selected";}?>> Febrero </OPTION>
					<OPTION value='3' <?php if(intval($fecha_mes==3)){echo "selected";}?>> Marzo </OPTION>
					<OPTION value='4' <?php if(intval($fecha_mes==4)){echo "selected";}?>> Abril </OPTION>
					<OPTION value='5' <?php if(intval($fecha_mes==5)){echo "selected";}?>> Mayo </OPTION>
					<OPTION value='6' <?php if(intval($fecha_mes==6)){echo "selected";}?>> Junio </OPTION>
					<OPTION value='7' <?php if(intval($fecha_mes==7)){echo "selected";}?>> Julio </OPTION>
					<OPTION value='8' <?php if(intval($fecha_mes==8)){echo "selected";}?>> Agosto </OPTION>
					<OPTION value='9' <?php if(intval($fecha_mes==9)){echo "selected";}?>> Septiembre </OPTION>
					<OPTION value='10' <?php if(intval($fecha_mes==10)){echo "selected";}?>> Octubre </OPTION>
					<OPTION value='11' <?php if(intval($fecha_mes==11)){echo "selected";}?>> Noviembre </OPTION>
					<OPTION value='12' <?php if(intval($fecha_mes==12)){echo "selected";}?>> Diciembre </OPTION>
											</SELECT>
						<SELECT class='select_formulario' name='anio_new_reserva' onChange='select_fecha_alta(this.value,this.name)'>
<?php

for ($i=$fecha_anio;$i<($fecha_anio+2);$i++){
	if(intval($fecha_anio)==$i){
		echo("<OPTION value='".$i."' selected>".$i."</OPTION>");
	}else{
		echo("<OPTION value='".$i."'>".$i."</OPTION>");
	}
}

?>
						</SELECT>
					</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td>
					<td align='left' class='label_formulario'> 	Noches:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type='text' size='3' name='pernoctas_new_reserva' class='input_formulario' value='<?php
						if ($noches){echo $noches;}else{echo "1";}
						?>' onChange='poner_noches_alta(this.value)'>
						<span style='position:relative;top:5px;'><img src='../imagenes/botones/flecha.jpg' usemap='#cambio_noches_alta' border=0></span>	
					</td>
					<td align='left' class='label_formulario'>Camas:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type='text' size='3' name='camas_new_reserva' class='input_formulario' <?php 
					if (isset($datos['camas'])){echo " value='".$datos['camas']."' ";}else{echo "value='1'";}
					if (isset($_GET['temp']) && $_GET['temp'] != ""){echo " readonly ";}
					?> onChange="poner_camas(this.value)">
						<span style='position:relative;top:5px;'><img src='../imagenes/botones/flecha.jpg' <?php 
						if (!isset($_GET['temp'])){ echo " usemap='#cambio_camas_alta' ";} ?>
						border=0></span>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Ingreso:</td>
					<td align='left' class='label_formulario'><input type='text' size='4' name='ingreso_new_reserva' class='input_formulario' <?php 
					if (isset($datos['ingreso'])){echo " value='".$datos['ingreso']."' ";}
					?> onChange='comp_ingreso("alta")'> (en euros)</td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario'>Nombre del Empleado:</td>
					<td align='left'><input type='text' name='empleado_new_reserva' class='input_formulario' <?php 
					if (isset($datos['empleado'])){echo " value='".$datos['empleado']."' ";}
					?>></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario' valign='top' style='padding-top:5px;'>Llegada Tarde:</td>
					<td align='left'><textarea cols='19' rows='2' class='text_area_formulario' name='llega_tarde_new_reserva'><?php 
					if (isset($datos['llegada_tarde'])){echo $datos['llegada_tarde'];}
					else {if(isset($_POST['llega_tarde_new_reserva'])){echo $_POST['llega_tarde_new_reserva'];}}
					?></textarea></td>
				</tr>
				<tr height='25'>
					<td>&nbsp;</td><td align='left' class='label_formulario' valign='top' style='padding-top:15px;'>Observaciones:</td>
					<td align='left'><textarea cols='19' rows='4' class='text_area_formulario' name='obs_new_reserva'><?php 
					if (isset($datos['observaciones'])){echo $datos['observaciones'];}
					else {if(isset($_POST['obs_new_reserva'])){echo $_POST['obs_new_reserva'];}}
					?></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td><td colspan='9' align='center' height='65'><a href='#' onClick="submit_formulario('alta')"> <img src='../imagenes/botones-texto/aceptar.jpg' border=0 alt='Dar de Alta la Reserva'></a></td>
				</tr>
				<?php
				//Se consultan las habitaciones y se crean Inputs Hidden para almacenar y enviar los datos del alta
				for($i=0;$i<count($habita);$i++){
					echo("<input type='hidden' id='".$habita[$i]['id']."' name='camas_hab_".$habita[$i]['id']."' value=0>");
				}
				
				?>
			</td></tr>
			</table>
			</table>
			</FORM>


		</div>

	<!-- ******************************************************************************************* -->
	<!-- ******************************<<< BLOQUE DE BAJA_RESERVA >>>******************************* -->
	<!-- ******************************************************************************************* -->

		<div id='eliminar_reserva' style='display:none;'>
		<FORM name='form_baja' ACTION='?pag=reservas.php&baja=si&fecha_cal=<?php echo($fecha_enviar);?>' METHOD='post'>
		<?php
			// Si se viene desde otra pagina, lo almacenamos en un Hidden
			if ($_GET['accion']=='reserva'){
				echo "<input type='hidden' value='habitaciones' name='origen_mod'>";
			}else{
				if ($_GET['buscar']=='si'){
					echo "<input type='hidden' value='busquedas' name='origen_mod'>";
				}
			}
			?>
		<input type='hidden' name='res_num_pag' value='<?php echo $_SESSION['gdh']['dis_hab']['num_pag'];?>'>
			<table border='0' class='tabla_general'>
				<thead>
					<td colspan='9' align='center'>
						<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:314px;'>
						Eliminar Reserva
						</div>
						<div class='champi_derecha'>&nbsp;</div>
					</td>
				</thead>
				<tr><td>
				<table class='table_container'>
				<tr height='25'>
					<td align='left' width='165px'><span style='margin-left:6px;' class='label_formulario'>D.N.I.:</span></td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo $cliente['dni'];?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Nombre:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles' ><?php echo truncar($cliente['nombre'],15);?> </span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Apellidos:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo truncar($cliente['apellido1'],15)." ".truncar($cliente['apellido2'],15);?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Teléfono:</span></td>
					<td align='left'> <span class='texto_detalles' style='margin-left:6px;'><?php echo $cliente['telefono'];?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>E-mail:</span></td>
					<td align='left'> <span style='margin-left:6px;'>
						<input style='border:0px;background-color:#f4fcff' type='text' value='<?php echo $cliente['email'];?>'  class='texto_detalles' readonly></span>
					</td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Fecha de Llegada:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'>
					<?php
					$f_temp=split("-",$cliente['llegada']);
					echo $f_temp[2]."-".$f_temp[1]."-".$f_temp[0];
					?>
					</span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Noches:</span>&nbsp;&nbsp;&nbsp;<span class='texto_detalles'><?php echo $cliente['pernocta'];?></span></td>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Camas:</span>&nbsp;&nbsp;&nbsp;<span class='texto_detalles'>
					<?php
						$camas=0;
						for ($i=0;$i<count($cliente['camas']);$i++){
							$camas+=intval($cliente['camas'][$i]);
						}
						echo $camas;
					?></span>	</td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Ingreso:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo $cliente['ingreso'];?> euros</span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span style='margin-left:6px;' class='label_formulario'>Nombre del Empleado:</span> </td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo truncar($cliente['empleado'],15);?></span></td>
				</tr>
				<tr height='25'>
					<td align='left'><span class='label_formulario' style='margin-left:6px;'>Llegada Tarde:</span></td>
					<td align='left'><span style='margin-left:6px;' class='texto_detalles'><?php echo truncar($cliente['llegada_tarde'],15);?></span></td>
				</tr>
				<tr height='65'>
					<td colspan='2' class='label_formulario' style='text-align:center;margin-left:2px;'> ¿Está seguro de que desea eliminar la reserva?</td>
				</tr>
				<tr height='45px'>
					<td align='center'> <a href='#' onClick='verifico_baja()'><img src='../imagenes/botones-texto/aceptar.jpg' border=0 alt='Eliminar la Reserva'></a></td>
					<td align='center'> <a href='#' onClick='cambiar("1")'><img src='../imagenes/botones-texto/cancelar.jpg' border=0 alt='Cancelar la Eliminación'></a></td>
				</tr>
			</table>
			</td></tr>
			</table>
			<?php
				//Si recibo un origen de eliminar, lo almaceno en un input hidden
				if (isset($_GET['busqueda']) && $_GET['busqueda']=='si'){
					echo "<INPUT type='hidden' name='origen_baja' value='busqueda'>";
				}else{
					if (isset($_GET['origen']) && $_GET['origen']){
						echo "<INPUT type='hidden' name='origen_baja' value='habitaciones'>";
					}
				}
			?>
			</FORM>
		</div>
	</div>

<!-- *************************************************************************************************** -->
<!-- *************************************************************************************************** -->
<!-- **************************** Bloque de Distribucion de Habitaciones ******************************* -->
<!-- *************************************************************************************************** -->
<!-- *************************************************************************************************** -->

<?php/*
//Codigo de prueva que me muestra por pantalla los datos de el array habita
echo "<table border=1>";
for ($i=0;$i<count($habita);$i++){
	echo " <tr><td rowspan=3>Habitacion ".$habita[$i]['id']."</td><td> Grupos: ".$habita[$i]['pernoctas_gr']."(".count($habita[$i]['grupos']).")</td><td>
			";
	for ($j=0;$j<count($habita[$i]['grupos']);$j++){
		echo " Grupo $j : Color=".$habita[$i]['grupos'][$j]['color']." Camas= ".$habita[$i]['grupos'][$j]['camas']."|";
	}
	echo "</td></tr><tr><td> Pernoctas:".$habita[$i]['pernoctas']."</td></tr><tr><td> Resevas:".$habita[$i]['ocupadas']."</td></tr>";
}
echo "</table>";
*/?>


	<div id="caja_superior_derecha">
		<div id="caja_superior_derecha_a">
			<div id="caja_habitaciones" style="display:block;" >
				<table border="0" cellpadding='0' cellspacing='0'>
						<thead>
							<td colspan="9" align="center">
								<div class='champi_izquierda'>&nbsp;</div>
								<div class='champi_centro'  style='width:621px;'>
							Distribución de Habitaciones
						
								&nbsp;&nbsp;&nbsp;

							<SELECT name="num_pag" class="detalles_select" onchange="cambiar_pagina_det(this.value);" style="position:relative;margin-left:40px;">
<?PHP

	for ($i = 0; $i < COUNT($numero_paginas); $i++) {
	  	if ($numero_paginas[$i] == $_SESSION['gdh']['dis_hab']['num_pag']) {
	  		echo '<option value="'.$numero_paginas[$i].'" selected>Ventana '.$numero_paginas[$i];
	  	}
	  	else {
	  		echo '<option value="'.$numero_paginas[$i].'">Ventana '.$numero_paginas[$i];
		}
	}

?>
							</SELECT>
								</div>
								
								<div class='champi_derecha'>&nbsp;</div>
							</td>
						</thead>
						<tbody>
						<tr>
						<td>
						<table class='tabla_detalles'  cellpadding='0' cellspacing='0'  style='position:relative;top:-4px;left:-0px;width:680px;height:315px;border:solid 1px;border-color:#569CD7;'>
							<tr>

							<td align="center">
								
							</td>
						</tr>
						<tr>
							<td align="center">
							<table border="0" class="tabla_habitaciones" cellpadding='0' cellspacing='1'>
							<FORM NAME='form_dist' METHOD='post'>

<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//*********************************** DISTRIBUCIÓN DE HABITACIONES *************************************//
//////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
En la Distribucion de Habitaciones vamos a ordenar las habitaciones y mostrar todas las habitaciones y 
camas, asi como su estado actual usando los datos almacenados anteriormente en el array $habita. Hay que 
tener en cuenta que a lo mejor una habitacion puede tener mas camas que las que queremos tener en una sola 
fila, por lo que tendremos que representarla en diferentes columnas. Por cada habitación que se muestra se 
creará un input hidden en el que se introduciran las camas asignadas para la misma
*/


if (count($habita>0)){
	$orden=Array();
	$tipo=Array();
	foreach ($habita as $key => $row){
		$orden[$key]  = $row['orden'];
		$tipo[$key] = $row['tipo'];
	}

	array_multisort ($tipo, SORT_ASC ,$orden, SORT_ASC, $habita);
}

//Recorremos cada una de las filas posibles
//Primero ponemos el nombre del tipo de habitacion
$tipo=$habita[0]['tipo'];
$cont_tipo=0;
echo "<tr>";
echo "<td rowspan='".($max_camas_fila+3)."' width='3px'></td>";
//Meto los nombres de los tipos de las habitaciones
for ($i=0;$i<count($habita);$i++){
	if 	($habita[$i]['tipo']==$tipo){
		$cont_tipo += count($habita[$i]['col']);
	}else{
		$qry_tonta=mysql_query("SELECT * FROM tipo_habitacion WHERE Id_Tipo_Hab=".$habita[$i-1]['tipo']." ");
		$tupla_tonta=mysql_fetch_array($qry_tonta);
		echo "<td class='nom_tipo_hab' colspan='".$cont_tipo."'> ".$tupla_tonta['Nombre_Tipo_Hab']." </td>";
		echo "<td rowspan='".($max_camas_fila+3)."' width='3px'></td>";
		echo "<td rowspan='".($max_camas_fila+3)."' class='separar_hab' width='2px'></td>";
		echo "<td rowspan='".($max_camas_fila+3)."' width='3px'></td>";
		$tipo=$habita[$i]['tipo'];
		$cont_tipo=count($habita[$i]['col']);
	}
	if (($i==count($habita)-1)){
		if (count($habita)==1){
			$qry_tonta=mysql_query("SELECT * FROM tipo_habitacion WHERE Id_Tipo_Hab='".$habita[$i]['tipo']."' ");
		}else{
			$qry_tonta=mysql_query("SELECT * FROM tipo_habitacion WHERE Id_Tipo_Hab='".$habita[$i]['tipo']."' ");
		}
		$tupla_tonta=mysql_fetch_array($qry_tonta);
		echo "<td class='nom_tipo_hab' colspan='".$cont_tipo."'> ".$tupla_tonta['Nombre_Tipo_Hab']." </td>";
	}
}
	
echo "<td rowspan='".($max_camas_fila+3)."' width='3px'></td>";
echo "</tr>";

//Ahora dibujamos las habitaciones
for ($fila=0;$fila<=$max_camas_fila;$fila++){

	$tipo=$habita[0]['tipo'];
	if($fila==0){echo '
					<tr align="center" valign="middle">'
		;}else{ echo("
					<tr>
			");}

	//Recorremos las camas de cada fila
	for ($cama=0;$cama<count($habita);$cama++){
		$columnas=0;//Numero de Columnas que van a usar cada habitacion
		//Si cambia el tipo, se introduce un espacio en blanco
		if($tipo!=$habita[$cama]['tipo']){
			$tipo=$habita[$cama]['tipo'];
		}


		//Funcionalidad para controlar las columnas en que se parten las habitaciones
		$filas_habita=intval($habita[$cama]['camas_totales'])/intval($max_camas_fila);
		if ($filas_habita>1){
			if (($habita[$cama]['camas_totales']%$max_camas_fila)>=$fila){
				$columnas=1;
			}else{
				$columnas=0;
			}
		}else{
			$filas_habita=1;
		}
		$columnas+=intval($filas_habita);
		
		//echo "<br> habita (".$habita[$cama]['id'].") count = ".count($habita[$cama]['col'])." ||columnas = ".$columnas;
		//Fin de partir Habitaciones (aplico)


		if($fila==0){//Para la primera fila, metemos en Id_Hab
			
			echo ('<td colspan="'.count($habita[$cama]['col']).'" class="nom_hab" id="'.$habita[$cama]['id'].'-'.$fila.'" onClick=asignar_habitacion("'.$habita[$cama]['id'].'") style=\'cursor:pointer;\'>'.$habita[$cama]['id'].'</td>');
		}
		else{//Para el resto de filas, metemos un estilo dependiendo del estado de la habitación
			for ($col=0;$col<$columnas;$col++){
				//Cambio para mostrar los colores de los gruypos (26-03-07)
				//Mostramos primero las estancias de grupos
				if ($habita[$cama]['pernoctas']>0){//Ahora pintamos las pernoctas de alberguistas y peregrinos
					echo "<td class='cama_ocupada' id='".$habita[$cama]['id']."-".$fila."-".$col."'>&nbsp;</td>";
					$habita[$cama]['pernoctas']--;
					$habita[$cama]['camas']--;
					$ocupadas[count($ocupadas)] = $habita[$cama]['id']."-".$fila."-".$col;
				
				}else if ($habita[$cama]['pernoctas_gr'] > 0){
					$salir = false;
					$cont_gr = 0;
					while (!$salir){
						if ($habita[$cama]['grupos'][$cont_gr]['camas'] > 0){
							echo "<td class='cama_ocupada' id='".$habita[$cama]['id']."-".$fila."-".$col."' style='background-color:#".$habita[$cama]['grupos'][$cont_gr]['color'].";'>&nbsp;</td>";
							$habita[$cama]['grupos'][$cont_gr]['camas']--;
							$salir = true;
						}else{
							if($cont_gr > count($habita[$cama]['grupos'])){
								$salir = true;
								echo "<h2>Error al mostrar los grupos</h2>";
							}
						}
						$cont_gr++;
					}
					$habita[$cama]['pernoctas_gr']--;
					$habita[$cama]['camas']--;
					$ocupadas[count($ocupadas)] = $habita[$cama]['id']."-".$fila."-".$col;

				}else{//Despues pintamos las reservas
					if ($habita[$cama]['ocupadas']>0){
						echo ("<td ");
						$habita[$cama]['ocupadas']--;
						$habita[$cama]['camas']--;
						$salir=false;
						$d=0;
						while($salir==false && $d<count($reservas_dia)){
							//Con esto Miramos si la reserva es la selecionada para detalles, modificar o eliminar
							for ($x=0;$x<count($reservas_dia[$d]['hab']);$x++){
								if ($reservas_dia[$d]['hab'][$x]==$habita[$cama]['id']){
									if ($reservas_dia[$d]['camas'][$x]>0){
										echo " name=".$reservas_dia[$d]['dni']." id='".$habita[$cama]['id']."-".$fila."-".$col."' ";
										$reservas_dia[$d]['camas'][$x]--;
										$salir=true;
										$ocupadas[count($ocupadas)] = $habita[$cama]['id']."-".$fila."-".$col;
										if ($reservas_dia[$d]['dni']==$_GET['dni'] && $reservas_dia[$d]['fecha_llegada']==$_GET['llegada'] && $_GET['op']!=2){
											//Si estamos viendo los detalles de la reserva
											echo "class=\"cama_reserva_resaltada\" OnMouseOver=\"resaltar_tabla('".$reservas_dia[$d]['dni']."');resaltar_habs('".$reservas_dia[$d]['dni']."');\" OnMouseOut=\"reponer_tabla('".$reservas_dia[$d]['dni']."');desresaltar_habs('".$reservas_dia[$d]['dni']."');\" ";
										}else{

											//Si es una reserva normal
											echo "class=\"cama_reservada\" id='".$habita[$cama]['id']."-".$fila."-".$col."' onClick=\"comprovar('".$reservas_dia[$d]['dni']."','1','".$reservas_dia[$d]['fecha_llegada']."');\" OnMouseOver=\"resaltar_tabla('".$reservas_dia[$d]['dni']."');resaltar_habs('".$reservas_dia[$d]['dni']."');\" OnMouseOut=\"reponer_tabla('".$reservas_dia[$d]['dni']."');desresaltar_habs('".$reservas_dia[$d]['dni']."');\" ";
										}
									}
								}
							}
							$d++;
						}
						echo "> &nbsp;</td>";
					}else{
						// Si la habitacion tiene camas temporales, las dibujamos
						if ($habita[$cama]['temps']>0){
							$salir=false;
							$d=0;
							$ocupadas[count($ocupadas)] = $habita[$cama]['id']."-".$fila."-".$col;
							while($salir==false && $d<count($habita[$cama]['temp'])){
								if ($habita[$cama]['temp'][$d]['camas'] > 0){
									if ($habita[$cama]['camas'] > 0){
										$f_split_temp = split("-",$habita[$cama]['temp'][$d]['fecha_llegada']);
										$f_split_temp_salida = split("-",$habita[$cama]['temp'][$d]['fecha_salida']);
										echo ("
										<td name='".$habita[$cama]['id']."' id='".$habita[$cama]['id']."-".$fila."-".$col."' class=\"cama_temp\" onClick=\"asignar_temp('".$habita[$cama]['temp'][$d]['fecha_llegada']."',this.id,'ocupada')\" style='cursor:pointer;' title='Cama ocupada  a partir del ".$f_split_temp[2]."-".$f_split_temp[1]."-".$f_split_temp[0]."' align='center' valign='center'>".$f_split_temp[2]."·".$f_split_temp[1]."</td>
										");
										$habita[$cama]['temps']--;
										$habita[$cama]['camas']--;
										$habita[$cama]['temp'][$d]['camas']--;
										$salir=true;
									}else{
										echo ("<td class=\"no_cama\" id='".$habita[$cama]['id']."-".$fila."-".$col."'>&nbsp;</td>");
									}
								}
								$d++;
							}
						}else{
							if ($habita[$cama]['camas']>0){
								if (isset($habita[$cama]['cambio_tipo'])){
									$f_split_temp = split("-",$habita[$cama]['cambio_tipo']['fecha']);
									
										echo ("
										<td name='".$habita[$cama]['id']."' id='".$habita[$cama]['id']."-".$fila."-".$col."' class=\"cama_temp\" onClick=\"asignar_temp('".$habita[$cama]['cambio_tipo']['fecha']."',this.id,'cambio_tipo')\" style='cursor:pointer;' title='La habitación cambiará de tipo a partir del día ".$f_split_temp[2]."-".$f_split_temp[1]."-".$f_split_temp[0]."' align='center' valign='center'>".$f_split_temp[2]."·".$f_split_temp[1]."</td>
										");
									/*}else{
										echo ("
										<td name='".$habita[$cama]['id']."' id='".$habita[$cama]['id']."-".$fila."-".$col."' class=\"cama_libre\" onMouseOver=\"resaltar_libre('".$habita[$cama]['id']."-".$fila."-".$col."')\" onMouseOut=\"reponer_libre('".$habita[$cama]['id']."-".$fila."-".$col."')\" onClick=\"if(confirm('La habitación cambiará de tarifa, ¿Desea partir la estancia?')){asignar_temp('".$habita[$cama]['cambio_tipo']['fecha']."',this.id,'cambio_tipo');}else{ asignar_cama('".$habita[$cama]['id']."','".$fila."','".$col."')}\" style='cursor:pointer;' title='La habitación cambiará de tarifa a partir del día ".$f_split_temp[2]."-".$f_split_temp[1]."-".$f_split_temp[0]."' align='center' valign='center'></td>
										");
									}*/
									$habita[$cama]['camas']--;
								}else{
									echo ("<td id='".$habita[$cama]['id']."-".$fila."-".$col."' class=\"cama_libre\" onMouseOver=\"resaltar_libre('".$habita[$cama]['id']."-".$fila."-".$col."')\" onMouseOut=\"reponer_libre('".$habita[$cama]['id']."-".$fila."-".$col."')\"  onClick=\"asignar_cama('".$habita[$cama]['id']."','".$fila."','".$col."')\" style='cursor:pointer;'>&nbsp;</td>");
									$habita[$cama]['camas']--;
								}
							}else{
								echo ("<td class=\"no_cama\" id='".$habita[$cama]['id']."-".$fila."-".$col."'>&nbsp;</td>");
							}
						}
					}		
				}
				if ($filas_habita>1){
					//echo "<br>totales/max=".($habita[$cama]['camas_totales']%$max_camas_fila)." & fila=".$fila." & col=".$col." & cama=".$cama."";
					if (($habita[$cama]['camas_totales']%$max_camas_fila)<$fila && $col==($columnas-1) && $cama!=(count($habita)-1) && ($habita[$cama]['camas_totales']%$max_camas_fila)>0){
						echo "<td class='no_cama' id='".$habita[$cama]['id']."-".$fila."-".($col+1)."'>&nbsp;</td>";
					}
				}	
			}
		}
	}
	echo "</TR>";
}
echo "<TR height='5px;'></TR>";

?>

							</table>
						</td>
					</tr>
					
				</td></tr><tr><td colspan='10' valign='middle' height='30px;'>
				
							<div id="leyenda" style="position:absolute;  background-color: #FFFFFF; border: 1px none #000000;  visibility: hidden;z-index:2;font-size:10px;margin-left:-230px;margin-top:-11px;width:400px;color:#064C87" > 
							<table width="100%" border="1" bordercolor="#000000" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0">
								<tr>
									<td colspan="6">
									<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                              		<tr> 
                                      <td align="center" style="font-size:12px"><b>Leyenda</b></td>
                                      <td width="20" align="center"><a href="#" onClick="ver_leyenda('1')" title="Cierra la Leyenda de la Distribución de Habitaciones" onMouseOver="window.status='Cierra la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true">X</a></td>
                                    </tr>
                                </table></td>
								</tr>
								<tr > 
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

					<div id='asignacion'  style='width:340px;height:20px;border:solid 0px;border-color:#91D5E8;background-color:#F4FCFF;font-size:14px;visibility:hidden;float:left;margin-left:80px;'>
								<span class='label_formulario'>Camas Restantes:
							
							<INPUT class='label_formulario' style='background-color:#F4FCFF;text-align:center;' type='text' size='2' name='camas_restantes' readonly <?php if(isset($_POST['camas_new_reserva'])){echo 'value='.$_POST['camas_new_reserva'];}?>>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							
							  
							</span>
						</div>
						<span height="20" align="right">
						
						<a href="#" class='label_formulario' onClick="ver_leyenda('2')" title="Ver la Leyenda de la Distribución de Habitaciones" OnMouseOver="window.status='Ver la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true"> 
                        Leyenda</a></span>
					</td></tr>
					</tbody>
					</table>
				</table>
			</div>
		</div>
</FORM>
<!-- ****************************************************************************************************** -->
<!-- ************************************	Listado de Reservas	 *******************************************-->
<!-- ****************************************************************************************************** -->

		<div id="caja_superior_derecha_b">
			<div id='listado_reservas'>
				<FORM name='form_listado'>
				<table border="0" id="tabla_detalles" align="center" style='width:690px;border:solid 0px;' cellpadding=0 cellspacing=0 >
					<tr id="titulo_tablas">
						<td colspan="9" align="center" style="padding:0px 0px 0px 0px;margin:0px 0px 0px 0px;border:solid 0px;">
							<div class='champi_izquierda'>&nbsp;</div>
							<div class='champi_centro' style='width:628px;'>
							Listado de Reservas
							</div>
							<div class='champi_derecha'>&nbsp;</div>
						</td>					
					</tr>
					<tr>
						<td>
							<div id="tableContainer_res" class="tableContainer_res" style='width:686px;height:216px;background-color:#F4FCFF;padding:0px;margin-top:-3px;margin-left:-2px;'>
								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable" style='width:668px;padding 0px;margin:0px;'>
									<thead class="fixedHeader" cellspacing="0" width="100%" style='position:relative;z-index:0;'>
											<th width="70px" align="center" style='cursor:pointer;text-align:center;' onClick="ordenar_listado('dni')"> D.N.I.</th>
											<th width="150px" align="center" style='cursor:pointer;text-align:center;' onClick="ordenar_listado('nombre')" > Nombre</th>
											<th width="230px" align="center" style='cursor:pointer;text-align:center;' onClick="ordenar_listado('apellido')"> Apellidos</th>
											<th width="55px" align="center">Hab</th>
											<th width="20px" align="center">D</th>
											<th width="20px" align="center">M</th>
											<th width="20px" align="center">E</th>
									</thead>
									<tbody class="scrollContent">
<?php

/*
Para el listado de reservas, haremos una nueva consulta para mostrar todas las reservas que existen en la 
base de datos. Luego simplemente hemos de mostrarlas por pantalla. Hay que tener en cuenta si se recibe 
algn criterio de ordenacion
*/

$qry_listado="SELECT * FROM  detalles inner join pra WHERE (pra.DNI_PRA = detalles.DNI_PRA AND detalles.Fecha_Llegada != detalles.Fecha_Salida)";

//	Si se recibe algun criterio de Ordenación:
if (isset($_GET['order'])){
	$partes=split("_",$_GET['order']);
	if ($partes[0]=='dni'){
		$qry_listado=$qry_listado." ORDER BY detalles.DNI_PRA";
	}
	if ($partes[0]=='nombre'){
		$qry_listado=$qry_listado." ORDER BY pra.Nombre_PRA";
	}
	if ($partes[0]=='apellido'){
		$qry_listado=$qry_listado." ORDER BY pra.Apellido1_PRA";
	}
	if ($partes[1]=='dec'){
		$qry_listado=$qry_listado." DESC";
	}else{
		$qry_listado=$qry_listado." ASC";
	}	
}

$res_listado=mysql_query($qry_listado);

for ($i=0;$i<mysql_num_rows($res_listado);$i++){
	$fila_lista=mysql_fetch_array($res_listado);

	// Comprobamos que no se trata de una reserva OnLine sin tratar, y si es asi almacenamos las habitaciones;
	$habs_fila=mysql_query("SELECT * FROM reserva WHERE (DNI_PRA = '".$fila_lista['DNI_PRA']."' AND Fecha_Llegada = '".$fila_lista['Fecha_Llegada']."')");
	$habitas_listado="";
	$online = false;
	for ($j=0;$j<mysql_num_rows($habs_fila);$j++){
		$habs=mysql_fetch_array($habs_fila);
		if ($habs['Id_Hab'] == "PRA"){$online = true; $habitas_listado = "(OnLine)";}
		else{
			if($j>0){$habitas_listado = $habitas_listado.", ";} $habitas_listado = $habitas_listado.$habs['Id_Hab'];
		}
	}
	if ($online == true){$asdasd = " style='background-color:red;' ";}else{$asdasd = "";}
	if ($online == false){
	
		if ($fila_lista['DNI_PRA'] == $_GET['dni'] && $fila_lista['Fecha_Llegada'] == $_GET['llegada']){
			echo "
				<tr class='texto_resaltado' id='tabla_".$fila_lista['DNI_PRA']."' height='100%'>
				<td align='left'>".$fila_lista['DNI_PRA']."</td>
				<td align='left'>".truncar($fila_lista['Nombre_PRA'],12)."</td>
				<td align='left'>".truncar($fila_lista['Apellido1_PRA'],20)." ".truncar($fila_lista['Apellido2_PRA'],20)."</td>
				<td align='left'>
			";
		}else{
			echo "
				<tr class='texto_listados' id='tabla_".$fila_lista['DNI_PRA']."' height='100%' onMouseOver=\"resaltar_habs('".$fila_lista['DNI_PRA']."');resaltar_seleccion(this);\" onMouseOut=\"desresaltar_seleccion(this);desresaltar_habs('".$fila_lista['DNI_PRA']."');\" $asdasd>
				<td align='left' onClick=\"comprovar('".$fila_lista['DNI_PRA']."','1','".$fila_lista['Fecha_Llegada']."');\">".$fila_lista['DNI_PRA']."</td>
				<td align='left' onClick=\"comprovar('".$fila_lista['DNI_PRA']."','1','".$fila_lista['Fecha_Llegada']."');\">".truncar($fila_lista['Nombre_PRA'],12)."</td>
				<td align='left' onClick=\"comprovar('".$fila_lista['DNI_PRA']."','1','".$fila_lista['Fecha_Llegada']."');\">".truncar($fila_lista['Apellido1_PRA'],20)." ".truncar($fila_lista['Apellido2_PRA'],20)."</td>
				<td align='left' onClick=\"comprovar('".$fila_lista['DNI_PRA']."','1','".$fila_lista['Fecha_Llegada']."');\">
			";
		}
		
		echo $habitas_listado;

		echo "
				</td>
				<td><a href='#' onClick=\"comprovar('".$fila_lista['DNI_PRA']."','1','".$fila_lista['Fecha_Llegada']."');\"><IMG alt='Detalles' src='../imagenes/botones/detalles.gif' border=0></a></td>
				<td><a href='#' onClick=\"comprovar('".$fila_lista['DNI_PRA']."','2','".$fila_lista['Fecha_Llegada']."');\"><IMG alt='Modificar' src='../imagenes/botones/modificar.gif' border=0></a></td>
				<td><a href='#' onClick=\"comprovar('".$fila_lista['DNI_PRA']."','3','".$fila_lista['Fecha_Llegada']."');\"><IMG alt='Eliminar' src='../imagenes/botones/eliminar.gif' border=0></a></td>
			</tr>
			<input type='hidden' id='noches_".$fila_lista['DNI_PRA']."_".$fila_lista['Fecha_Llegada']."' value='".$fila_lista['PerNocta']."'>
			
		";
	}
}

?>

									</tbody>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
</FORM>
<!-- Mapas para la funcionalidad de las flechas de camas y noches de Alta y Modificar -->
<MAP name='cambio_camas_alta'>
	<AREA SHAPE='rect' href='#' coords='0,0,12,11' onclick="vario_camas('1','+','alta')" alt='Subir Camas'>
	<AREA SHAPE='rect' href='#' coords='0,12,12,22' onclick="vario_camas('1','-','alta')" alt='Bajar Camas'>
</MAP>
<MAP name='cambio_camas_mod'>
	<AREA SHAPE='rect' href='#' coords='0,0,12,11' onclick="vario_camas('1','+','modificar')" alt='Subir Camas'>
	<AREA SHAPE='rect' href='#' coords='0,12,12,22' onclick="vario_camas('1','-','modificar')" alt='Bajar Camas'>
</MAP>
<MAP name='cambio_noches_alta'>
	<AREA SHAPE='rect' href='#' coords='0,0,12,11' onclick="cambio_noches_alta('+1')" alt='Subir Noches'>
	<AREA SHAPE='rect' href='#' coords='0,12,12,22' onclick="cambio_noches_alta('-1')" alt='Bajar Noches'>
</MAP>
<MAP name='cambio_noches_mod'>
	<AREA SHAPE='rect' href='#' coords='0,0,12,11' onclick="cambio_noches_mod('+1')" alt='Subir Noches'>
	<AREA SHAPE='rect' href='#' coords='0,12,12,22' onclick="cambio_noches_mod('-1')" alt='Bajar Noches'>
</MAP>

<script language='javascript'>
	
var habitas=new Array();	//	[0] = Hab_Id, [1] = Num_Camas, [2] = Columnas
var reservas=new Array();
var camas=parseInt(document.form_dist.camas_restantes.value);
var camas_tot=parseInt(document.form_alta.camas_new_reserva.value);
var max_camas=<?php echo $max_camas_fila; ?>;
var dias=<?php if (isset($noches)){echo $noches;}else{echo "1";}?>; 
var asignadas=new Array();//Array donde se van a guradar las camas libres asignadas
var asignadas_temp=new Array();//Array donde se van a guardar las camas temps asignadas	
var totales_temp=0;//Numero de camas temporales que han sido asignadas
var fecha_hoy='<?php echo($fecha); ?>';
var fecha_enviar='<?php echo($fecha_enviar); ?>';
var dia_hoy=<?php echo intval($fecha_dia); ?>;
var mes_hoy=<?php echo intval($fecha_mes); ?>;
var anio_hoy=<?php echo intval($fecha_anio); ?>;
var buffer_reservas=new Array();
var estado<?php if (!isset($_GET['op'])){echo "='alta'";}?>;//Almacena la accionque se esta haciendo (alta, baja,mod o detalles)
var asignado_temp=false;
var entrada_def='0-0-0';
var salida_def='2099-12-31';


if (document.form_alta.dni_new_reserva.value.length>0 && estado=='alta'){
	document.getElementById('asignacion').style.visibility='visible';
}else{
	document.getElementById('asignacion').style.visibility="hidden";
}


<?php
	//Comandos para JAVASCRIPT desde PHP
	if ($_GET['dni']){
		echo "var dni_activo = '".$cliente['dni']."';";
	}else{ echo "var dni_activo = 0;"; }

	//vamos a meter en un array js de 3 dimensiones los valores de id_hab(0), num_camas(1) y columnas que ocupa(2)
	for ($i=0;$i<count($habita);$i++){
		echo "habitas[".$i."]=new Array();";
		echo "habitas[".$i."][0]='".$habita[$i]['id']."';";
		echo "habitas[".$i."][1]='".$habita[$i]['camas_totales']."';";
		echo "habitas[".$i."][2]='".count($habita[$i]['col'])."';";
	}
	if (isset($_GET['buscar'])){
		echo "document.form_alta.nombre_new_reserva.focus();";
		
	}

?>

/* a ver que pasa....
function resaltar(x,col) {
	x.style.backgroundColor  = col;
}
	
function detalles(a,b,c,d,e,f,g,h,i,j,k,l,m,o) {
	dni.value = a;
	edad.value = b;
	nombre.value = c;
	apellidos.value = d;
	habitacion.value = e;
	tipocliente.value = f;
	fechallegada.value = g;
	fechasalida.value = h;
	pernocta.value = i;
	nochespagadas.value = j;
	importepagado.value = k;
	facturado.value = l;
	exento.value = m;
	taquillas.value = o;
}
*/
//	Funciones para resaltar los datos de una fila del listado cuando pasemos el raton por encima
//	de alguna de las habitaciones que correspondan a dicha reserva o sobre la fila misma
function resaltar_seleccion(tr){
	//tr.className='texto_resaltado_temp';
	tr.style.color = 'white';
	tr.style.backgroundColor = '#6586C2';
}

function desresaltar_seleccion(tr){
	//tr.className='texto_listados';
	tr.style.backgroundColor = '#F4FCFF';
	tr.style.color = '#3F7BCC';
}

//	Función que se encarga de enviar los submits de los formularios
function submit_formulario(coso){
	if (coso=='alta'){
		if(camas>0 || document.form_alta.dni_new_reserva.value==''){
			alert ("Debe asignar todas las camas antes de continuar");
		}else{
			if (estado == 'temp'){
				
					salida_def=salida_def.split("-");
					document.form_alta.action = "?pag=reservas.php&fecha_entrada=" + fecha_enviar + "&fecha_salida=" + salida_def[2] + "-" + salida_def[1] + "-" + salida_def[0] + "&noches=" + document.form_alta.pernoctas_new_reserva.value + "&temp=si";
					document.form_alta.submit();
				
			}else{

				<?php if(isset($_GET['temp'])){	?>
					if (valida_campos_alta('form_alta')){
						document.form_alta.action = "?pag=reservas.php&fecha_entrada=" + fecha_enviar + "&noches=" + document.form_alta.pernoctas_new_reserva.value + "&temp=fin";
						document.form_alta.submit();
					}
				<?php }else{ ?>
					if (valida_campos_alta('form_alta')){
						document.form_alta.submit();
					}
				<?php } ?>
			}
		}
	}
	if (coso=='mod'){
		if (valida_campos_mod('form_mod')){
			var referencia = window.location.href.replace(/#/g,"");
			document.form_mod.action = referencia + "&mod=si"
			document.form_mod.submit();
		}
	}
}

//	Función para actualizar la pagina si es necesario cuando queremos ver los
//	datos, eliminar o modificar una reserva, asi como para dar de alta una reserva
function comprovar(dni,op,fecha_busco){
	var per_mod=document.getElementById("noches_"+dni+"_"+fecha_busco).value;
	var parto=fecha_busco.split("-");
	var parto_noches=per_mod.split("_");
	if (op==2){
		dia_mod=parto[2]+"-"+parto[1]+"-"+parto[0];
		window.location.href = "?pag=reservas.php&fecha_cal="+dia_mod+"&noches="+per_mod+"&dni="+dni+"&op="+op+"&llegada="+fecha_busco;
	}else{
		if ((dni != dni_activo) || (fecha_busco != '<?php echo $cliente['llegada'];?>')){
			window.location.href="?pag=reservas.php&fecha_cal=<?php
				echo $fecha_enviar; 
				?>&dni="+dni+"&op="+op+"&llegada="+fecha_busco;
		}else{
			cambiar(op);
		}
	}
	
}

//  Funcion que controla la visibilidad de los bloques que representan alta, baja, modificación y baja
function cambiar(op){
	if (op==1){		//Muestra Detalles
		document.getElementById('alta_reserva').style.display='none';
		document.getElementById('detalles_reserva').style.display='block';
		document.getElementById('modificar_reserva').style.display='none';
		document.getElementById('eliminar_reserva').style.display='none';
		document.getElementById('asignacion').style.visibility='hidden';
		estado='detalles';
		
		
	}
	if (op==2){		//Muestra Modificar Reserva
		document.getElementById('detalles_reserva').style.display='none';
		document.getElementById('alta_reserva').style.display='none';
		document.getElementById('modificar_reserva').style.display='block';
		document.getElementById('eliminar_reserva').style.display='none';
		document.getElementById('asignacion').style.visibility='visible';
		estado="modificar";
		
	}
	if (op==3){		//Muestra Eliminar Reserva
		document.getElementById('detalles_reserva').style.display='none';
		document.getElementById('alta_reserva').style.display='none';
		document.getElementById('modificar_reserva').style.display='none';
		document.getElementById('eliminar_reserva').style.display='block';
		document.getElementById('asignacion').style.visibility='hidden';
		estado='baja';
	}
	if (op==4){		//Muestra Alta Reserva
		document.getElementById('detalles_reserva').style.display='none';
		document.getElementById('alta_reserva').style.display='block';
		document.getElementById('modificar_reserva').style.display='none';
		document.getElementById('eliminar_reserva').style.display='none';
		window.location.href='?pag=reservas.php&fecha_cal=<?php
if ($fecha<date("Y-m-d")){
	echo date("d-m-Y");
}else{
	echo $fecha_enviar;
}
?>';
		estado='alta';
	}
	document.form_alta.camas_new_reserva.value=camas_tot;
	document.form_dist.camas_restantes.value=camas;

}

 
//  Funcion que cambia la clase de las camas que asignamos o desasignamos, 
//  y que asigna valores a los Inputs hidden de asigacion de habitaciones.
function asignar_cama(hab,cama,col){
	if (estado=='alta'){
		if (document.form_alta.dni_new_reserva.value == ""){
			alert ('Por favor, Introduzca el DNI para continuar');
			return;
		}
		if (asignadas[hab+"-"+cama+"-"+col]=="ocupada"){
			asignadas[hab+"-"+cama+"-"+col]="libre";
			camas++;
			var operacion = "document.form_alta.camas_hab_"+hab+".value=parseInt(document.form_alta.camas_hab_"+hab+".value)-1";
			eval(operacion);
			document.getElementById(hab+"-"+cama+"-"+col).className='cama_libre';
		}else{
			if (camas<=0){
				alert ("No hay camas que asignar");
			}else{
				asignadas[hab+"-"+cama+"-"+col]="ocupada";
				camas--;
				var operacion = "document.form_alta.camas_hab_"+hab+".value=parseInt(document.form_alta.camas_hab_"+hab+".value)+1";
				eval(operacion);
				document.getElementById(hab+"-"+cama+"-"+col).className='cama_asignada';
			}
		}
	}
	if (estado=='modificar'){
		if (asignadas[hab+"-"+cama+"-"+col]=="ocupada"){
			asignadas[hab+"-"+cama+"-"+col]="libre";
			camas++;
			var operacion = "document.form_mod.camas_hab_"+hab+".value=parseInt(document.form_mod.camas_hab_"+hab+".value)-1";
			eval(operacion);
			document.getElementById(hab+"-"+cama+"-"+col).className='cama_libre';
		}else{
			if (camas<=0){
				alert ("No hay camas que asignar");
			}else{
				asignadas[hab+"-"+cama+"-"+col]="ocupada";
				camas--;
				var operacion = "document.form_mod.camas_hab_"+hab+".value=parseInt(document.form_mod.camas_hab_"+hab+".value)+1";
				eval(operacion);
				document.getElementById(hab+"-"+cama+"-"+col).className='cama_reserva_resaltada';
			}
		}
	}
	if (estado=='temp'){
		
		if (asignadas_temp[hab+"-"+cama+"-"+col]=="ocupada"){
			asignadas_temp[hab+"-"+cama+"-"+col]="libre"
			camas++;
			totales_temp--;
			var operacion = "document.form_alta.camas_hab_"+hab+".value=parseInt(document.form_alta.camas_hab_"+hab+".value)-1";
			eval(operacion);
			document.getElementById(hab+"-"+cama+"-"+col).className='cama_temp';
		}else{
			if (camas<=0){
				alert ("No hay camas que asignar");
			}else{
				asignadas_temp[hab+"-"+cama+"-"+col]="ocupada";
				camas--;
				var operacion = "document.form_alta.camas_hab_"+hab+".value=parseInt(document.form_alta.camas_hab_"+hab+".value)+1";
				eval(operacion);
				totales_temp++;
				document.getElementById(hab+"-"+cama+"-"+col).className='cama_asignada';
				var tema;
				var operacion = "tema=parseInt(document.form_alta.camas_hab_"+hab+".value)";
				eval(operacion);
			}
		}
		
		if (totales_temp<=0){
			estado = 'alta';
			salida_def='2099-12-31';
		}

		// Si el usuario asigna todas las camas, le sistema pide confirmacion, y
		// si acepta, le envia a la fecha desde la que debe continuar asignando.
		if (camas<=0){
			var confirmo=confirm("Quedan dias de la estancia por asignar \n          ¿Desea Continuar?");
			if (confirmo){					
				submit_formulario('alta');
			}
		}
		
	}
	document.form_dist.camas_restantes.value=camas;
}

//Esta funcion se encarga de la asignacion (y viceversa) de las habitaciones al pinchar en su nombre
function asignar_habitacion(id_hab){
	var libre=false;
	for (var i=0;i<habitas.length;i++){
		if (habitas[i][0]==id_hab){
			if (camas>0){
				//Si hay camas por asignar, comprovamos si queda alguna cama libre
				for (var fila=1;fila<=max_camas;fila++){
					for (var columna=0;columna<habitas[i][2];columna++){
						if (document.getElementById(habitas[i][0]+"-"+fila+"-"+columna).className=='cama_libre' && asignadas[id_hab+"-"+fila+"-"+columna]!="ocupada"){
							libre=true;
						}
					}
				}
            }
			if (libre==true){
				//Si hay camas libres, las asignamos
				for (var fila=1;fila<=max_camas;fila++){
					for (var columna=0;columna<habitas[i][2];columna++){
						if (document.getElementById(habitas[i][0]+"-"+fila+"-"+columna).className=='cama_libre' && asignadas[id_hab+"-"+fila+"-"+columna]!="ocupada"){
							if (camas>0){
								asignar_cama(id_hab,fila,columna);
							}
						}
					}
				}
			}else{
				//Si no hay camas libres, desasignamos las habitaciones asignadas
				for (var fila=1;fila<=max_camas;fila++){
					for (var columna=0;columna<habitas[i][2];columna++){
						if ((document.getElementById(habitas[i][0]+"-"+fila+"-"+columna).className=='cama_asignada' || document.getElementById(habitas[i][0]+"-"+fila+"-"+columna).className=='cama_reserva_resaltada') && asignadas[habitas[i][0]+"-"+fila+"-"+columna]=="ocupada"){
							asignar_cama(id_hab,fila,columna);
						}
					}
				}
			}
		}
	}
}

// Esta funcion se encarga de desasignar todas las camas asignadas de todas las habitaciones
function limpiar_habitas(){
	for (var fila=(max_camas);fila>0;fila--){
		for (var i=(habitas.length)-1;i>=0;i--){
			for (var columna=0;columna<habitas[i][2];columna++){
				if(asignadas[habitas[i][0]+"-"+fila+"-"+columna]=='ocupada'){
					asignadas[habitas[i][0]+"-"+fila+"-"+columna]='libre';
					document.getElementById(habitas[i][0]).value=0;
					if (document.getElementById(habitas[i][0]+"-"+fila+"-"+columna).className=='cama_asignada'){
						document.getElementById(habitas[i][0]+"-"+fila+"-"+columna).className='cama_libre';
					}
				}
			}
		}
	}
	camas=camas_tot;
	document.form_dist.camas_restantes.value=camas;
}

//Variación del numero total de camas al editar el campo camas_new_reserva del formulario de alta
function cambio_camas_tot(cambio){
	oper="camas_tot=camas_tot"+cambio;
	eval(oper);
	var oper="camas=camas"+cambio;
	eval(oper);
	if (camas_tot<=0){
		camas_tot=1;
		camas_restantes=1;
		limpiar_habitas();
	}
	if (camas<0){
		var total=-1;
	}
	if (estado == 'mod'){
		document.form_mod.camas_mod_reserva.value=camas_tot;
	}else{
		document.form_alta.camas_new_reserva.value=camas_tot;
	}
	document.form_dist.camas_restantes.value=camas;
}

//	Funcionalidad de las flechas de sumar o restar camas del formulario
function vario_camas(camas_restantes, oper, dest){
	vario=parseInt(camas_restantes);
	if (dest == "modificar"){
		var form_dest = "form_mod";
	}else{
		var form_dest = "form_"+dest;
	}
	var exp;
	var num;
	var sw_cont = true;
	if (estado=='alta' && document.form_alta.dni_new_reserva.value.length<1){
		alert ('Por favor, introduzca un DNI para continuar');
		document.form_alta.camas_new_reserva.value='1';
		document.form_dist.camas_restantes.value='1';
		return;
	}
	//Restamos las camas
	if (oper == '-'){
		if (camas >= camas_restantes){
			//Si hay mas camas sin asignar de las que quiero borrar, se las resto y punto
			camas = camas - camas_restantes;
			camas_tot -= camas_restantes;

		}else{
			camas_restantes = camas_restantes - camas;
			camas = 0;
			for (cont_hab = 0; cont_hab < habitas.length; cont_hab++){
				for (cont_camas = 1; cont_camas<=max_camas; cont_camas++){
					for (cont_col = 0; cont_col<habitas[cont_hab][2]; cont_col++){
						if (asignadas[habitas[cont_hab][0]+"-"+cont_camas+"-"+cont_col]=='ocupada' && sw_cont == true){
							exp="document."+form_dest+".camas_hab_"+habitas[cont_hab][0]+".value--;";
							eval(exp);
							asignadas[habitas[cont_hab][0]+"-"+cont_camas+"-"+cont_col]='libre';
							document.getElementById(habitas[cont_hab][0]+"-"+cont_camas+"-"+cont_col).className='cama_libre';
							camas_restantes--;
							camas_tot--;
							if (camas_restantes == 0){sw_cont = false}
						}
					}
				}
			}
		}
	}
	if (oper == '+'){
		camas_tot += parseInt(camas_restantes);
		camas += parseInt(camas_restantes);
	}
	if (dest == 'modificar'){
		document.form_mod.camas_mod_reserva.value = camas_tot;
	}else{
		document.form_alta.camas_new_reserva.value = camas_tot;
	}
	document.form_dist.camas_restantes.value = camas;
}

// Funcion para cuando se edita el campo de camas del formulario de alta o modificación
function poner_camas(valor){
	var camas_restantes = 0;
	if (estado=='alta' && document.form_alta.dni_new_reserva.value.length<1){
		document.form_alta.camas_new_reserva.value='1';
		document.form_dist.camas_restantes.value='1';
		return;
	}
	valor=parseInt(valor);
	if (isNaN(parseInt(valor))||parseInt(valor)<=0){
		vario_camas(camas_tot,'-',estado);
		camas=1;
		camas_tot=1;
	}else{
		if (valor >= camas_tot){
			//Si el valor recibido es mayor que el antiguo, se suma y a otra cosa mariposa
			camas_restantes = (valor - camas_tot);
			vario_camas(camas_restantes,"+",estado);
		}else{
			//Si el valor es menor, Calculo cuantas he de restar
			camas_restantes = Math.abs(valor - camas_tot);
			vario_camas(camas_restantes,"-",estado);		
		}
	}
}

//Funcionalidad para el cambio de la Fecha del alta mediate el Select del formulario
function select_fecha_alta(valor,opcion){	
	
	if (opcion=="dia_new_reserva"){
		document.form_alta.action = "?pag=reservas.php&noches="+dias+"&fecha_cal="+valor+"-"+mes_hoy+"-"+anio_hoy;
		document.form_alta.submit();
	}
	if (opcion=="mes_new_reserva"){
		document.form_alta.action="?pag=reservas.php&noches="+dias+"&fecha_cal="+dia_hoy+"-"+valor+"-"+anio_hoy;
		document.form_alta.submit();
	}
	if (opcion=="anio_new_reserva"){
		document.form_alta.action="?pag=reservas.php&noches="+dias+"&fecha_cal="+dia_hoy+"-"+mes_hoy+"-"+valor;
		document.form_alta.submit();
	}
}

//Funcionalidad para el cambio de la Fecha de la modificacion mediate el Select del formulario
function select_fecha_mod(valor,opcion){
	if (opcion=="dia_mod_reserva"){
		document.form_mod.action = "?pag=reservas.php&op=2&noches="+dias+"&fecha_cal="+valor+"-"+mes_hoy+"-"+anio_hoy+"&op=2&dni=<?php echo $_GET['dni'];?>&llegada=<?php echo $_GET['llegada'];?>";
		document.form_mod.submit();
	}
	if (opcion=="mes_mod_reserva"){
		document.form_mod.action = "?pag=reservas.php&op=2&noches="+dias+"&fecha_cal="+dia_hoy+"-"+valor+"-"+anio_hoy+"&op=2&dni=<?php echo $_GET['dni'];?>&llegada=<?php echo $_GET['llegada'];?>";
		document.form_mod.submit();
	}
	if (opcion=="anio_mod_reserva"){
		document.form_mod.action = "?pag=reservas.php&op=2&noches="+dias+"&fecha_cal="+dia_hoy+"-"+mes_hoy+"-"+valor+"&op=2&dni=<?php echo $_GET['dni'];?>&llegada=<?php echo $_GET['llegada'];?>";
		document.form_mod.submit();
	}
}

/*       EN DESUSO
function cambio_fecha(inc, coso){	// Cotrola la funcionalidad del calendario de DISTRIBUCION de Habs
	var fecha_temp=new Date(anio_hoy,mes_hoy,dia_hoy);
	//fecha_temp.setFullYear(anio_hoy,mes_hoy,dia_hoy);
	if (coso == "dia"){	
		var exp="fecha_temp=fecha_temp.setDate(fecha_temp.getDate()"+inc+");";
		//alert (exp);
		//var exp='dia_hoy=parseInt(dia_hoy)'+inc+';';;
		eval (exp);
	}
	if (coso == "mes"){
		var exp="mes_hoy=parseInt(mes_hoy)"+inc+";";
		eval (exp);
	}
	if (coso == "anio"){
		var exp="anio_hoy=parseInt(anio_hoy)"+inc+";";
		eval (exp);
	}
	
	var dia=fecha_temp.getDay();
	var mes=fecha_temp.getMounth();
	var anio=fecha_temp.getFullYear();
	//alert (fecha_temp+" "+dia+" "+mes+" "+anio);
}
*/

// Cambio del campo noches del formulario de alta
function poner_noches_alta(valor){
	dias=parseInt(valor);
	if (dias<=0){alert ("Debe introducir un valor mayor a 0");dias=1;}
	document.form_alta.action="?pag=reservas.php&fecha_cal=<?php echo $fecha_enviar;?>&noches="+dias;
	document.form_alta.submit();
}

// Funcionalidad de las flechas de sumar y restar noches del formulario de alta
function cambio_noches_alta(inc){
	var operacion="dias=dias"+inc;
	eval(operacion);
	if (dias<=0){
		dias=1;
	}
	document.form_alta.action="?pag=reservas.php&fecha_cal=<?php echo $fecha_enviar;?>&noches="+dias;
	document.form_alta.submit();
	
}

// Cambio del campo noches del formulario de modificar
function poner_noches_mod(valor){
	dias=parseInt(valor);
	if (dias<=0){alert ("Debe introducir un valor mayor a 0");dias=1;exit();}
	document.form_mod.action="?pag=reservas.php&fecha_cal=<?php echo $fecha_enviar;?>&noches="+dias+"&op=2&llegada=<? echo $_GET['llegada']; ?>&dni=<? echo $_GET['dni']?>";
	document.form_mod.submit();
}

// Funcionalidad de las flechas de sumar y restar noches del formulario de modificar
function cambio_noches_mod(inc){
	var operacion="dias=dias"+inc;
	eval(operacion);
	if (dias<=0){
		dias=1;
	}
	document.form_mod.action="?pag=reservas.php&op=2&fecha_cal=<?php echo $fecha_enviar;?>&dni=<? echo $_GET['dni'];?>&llegada=<?echo $_GET['llegada']?>&noches="+dias;
	document.form_mod.submit();
	
}

// Funcion para confirmar una baja
function verifico_baja(){
	document.form_baja.action = "?pag=reservas.php&baja=si&fecha_cal="+dia_hoy+"-"+mes_hoy+"-"+anio_hoy+"<?php echo "&dni=".$cliente['dni']."&eliminar=".$cliente['llegada'];?>";
	document.form_baja.submit();
}

//	Funciones que resaltan y desresaltan las Habitaciones de una Reserva cuando 
//  pasamos el raton por encima de una fila del Listado (solo moz)
function resaltar_habs(dni_hab){	
	/*if (estado=='alta'){
		for (var i=0;i<document.getElementsByName(dni_hab).length;i++){
			if (document.getElementsByName(dni_hab)[i].className=='cama_reservada'){
				document.getElementsByName(dni_hab)[i].className='cama_reserva_resaltada';
			}
		}	
	}*/
}

function desresaltar_habs(dni_hab){
	/*if (estado=='alta'){
		for (var i=0;i<document.getElementsByName(dni_hab).length;i++){
			if (document.getElementsByName(dni_hab)[i].className=='cama_reserva_resaltada'){
				document.getElementsByName(dni_hab)[i].className='cama_reservada';
			}
		}
	}*/
}

//	Funciones que ponen y quitan el color cuando pasamos el raton por encima de una cama libre
function resaltar_libre(celda){	
	if (asignadas[celda]!='ocupada' && camas>0){
		if (estado=='alta'){
			document.getElementById(celda).className='cama_resaltada';
		}
		if (estado=='modificar'){
			document.getElementById(celda).className='cama_reserva_resaltada';
		}
	}
}

function reponer_libre(celda){
	if (asignadas[celda]!='ocupada' && camas>0){
		document.getElementById(celda).className='cama_libre';
	}
}

// Funciones que resaltan y reponen una fila del listado cuando pasamos el ratón por encima
function resaltar_tabla(dni_t){
	if(document.getElementById('tabla_'+dni_t).className=='texto_listados'){
		
		document.getElementById('tabla_'+dni_t).className='texto_resaltado_temp';
	}
}

function reponer_tabla(dni_t){
	if(document.getElementById('tabla_'+dni_t).className=='texto_resaltado_temp'){
		document.getElementById('tabla_'+dni_t).className='texto_listados';
	}
}

// Funcionalidad de la lupa de Busqueda de Clientes de Nueva Reserva
function abrir_busqueda(){
	window.open("paginas/res_busq_dni.php?form=form_alta", "_blank", "width=650px,height=650px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");
	if (isNaN(camas)){
		camas=1;
		document.form_dist.camas_restantes.value = camas;
	}
}

// Funcion que hace la busqueda de un DNI al editar el campo DNI del formulario de alta y lo valida
function calcletra(dni)
{
	var JuegoCaracteres="TRWAGMYFPDXBNJZSQVHLCKET";
	var Posicion= dni % 23;
	var Letra = JuegoCaracteres.charAt(Posicion);
	return Letra;
}

function validadni(valor_dni,estado)
{
	/* No se desea comprobar dni, asi ke solo buscamos en la bd si el cliente esta en la bd
	
	var dni = valor_dni;
	var bien=false;
	//Comprueba que tenga una longitud entre 7 y 8 números, y en caso de tener un carácter(NIF) será una letra permitida. 
	//En caso de no cumplirse estas condiciones, se enviará un mensaje y se colocará el foco en el campo dni.
	if (dni=="") alert("Debe rellenar el campo DNI.");
	// En caso de tratarse del dni o del carnet de conducir...
	else 
	{
   
	
		
		// Tiene que tener una longitud de 7 números como mínimo.
		if (dni.length < 7)
		{
			alert("Debe rellenar correctamente el campo DNI.");
			if (estado == 'alta')
			{
				document.form_alta.dni_new_reserva.focus();
			}
			else if (estado == "modificar")
			{
				document.form_mod.dni_mod_reserva.focus();
			}
			document.formu.nombre.focus();
			bien=false;
		}
		// Comprobamos si los siete primeros son números.
		else if(isNaN(dni.substring(0,7)) )
		{
			alert("Debe rellenar correctamente el campo DNI.");					
			if (estado == 'alta')
			{
				document.form_alta.dni_new_reserva.focus();
			}
			else if (estado == "modificar")
			{
				document.form_mod.dni_mod_reserva.focus();
			}
			bien=false;
		}
	
		else
		{
			// Si tiene solo siete números, le añadimos un cero y la letra.
			if(dni.length == 7)
			{
			if(estado=="alta")
			{
				document.form_alta.dni_new_reserva.value = "0"+dni+calcletra(dni);
			}
			else if (estado == "modificar")
			{
				document.form_mod.dni_mod_reserva.value = "0"+dni+calcletra(dni);
			}						 	
				
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
					alert("Debe rellenar correctamente la letra de control del DNI.");
						if(estado=="alta")
						{
							document.form_alta.dni_new_reserva.focus();
						}
						else if (estado == "modificar")
						{
							document.form_mod.dni_mod_reserva.focus();
						}	
					
					bien=false;
				}			
				// Le añadinos la letra si el último caracter es un número
				if (!isNaN(dni.substring(7,8))) document.form_alta.dni_new_reserva.value = dni+calcletra(dni);						 
			 }   
				// Si es ig????5?ual a nueve..
			else if (dni.length == 9)
			{
				
				// El penúltimo tiene que ser un número y el último carácter solo puede ser una letra permitida
				if ( (isNaN(dni.substring(7,8)) ) || (calcletra(dni.substring(0,8))!=dni.substring(8,9)) ) 
				{
					alert("Debe rellenar correctamente la letra de control del DNI.");
						if(estado=="alta")
						{
							document.form_alta.dni_new_reserva.focus();
						}
						else if (estado == "modificar")
						{
							document.form_mod.dni_mod_reserva.focus();
						}					
					
					bien=false;
				}
						
			 }
			else if (dni.length > 9)
			{
				alert("Debe rellenar correctamente el campo DNI.");
					if(estado=="alta")
					{
						document.form_alta.dni_new_reserva.focus();
					}
					else if (estado == "modificar")
					{
						document.form_mod.dni_mod_reserva.focus();
					}					
				
				bien=false;
			}
		} 
	 } 
	bien=true;
	
	if(bien==true)
	{*/
	if(estado=="alta"){
		document.form_alta.action='?pag=reservas.php&buscar='+document.form_alta.dni_new_reserva.value+'<?php if (isset($_GET['temp'])){echo "&temp";}?>&noches='+dias;
		document.form_alta.submit();
	}
	else if (estado == "modificar"){
		document.form_mod.action='?pag=reservas.php&op=2&buscar='+document.form_mod.dni_mod_reserva.value+'<?php if (isset($_GET['temp'])){echo "&temp";}?>&noches='+dias+'&dni=<?php echo $_GET['dni'];?>&llegada=<?php echo $_GET['llegada'];?>';
		document.form_mod.submit();
	}
}


// Función que comprueva los campos del alta introducidos antes de enviarlos a que sean introducidos en 
// la base de datos. Si son correctos, esta funcion devuelve true, y sino, devuleve false
function valida_campos_alta(formu){

	if (camas_tot <= 0){
		alert ("La reserva tiene que tener al menos una cama");
		return false;
	}

	if (camas > 0){
		alert ("Ha de asignar todas las camas para continuar");
		return false;
	}
/*
	//Se comprueba que contengan algo los campos nombre y primer apellido
	var dni = document.forms[formu].dni_new_reserva.value;

	// Comprueba que el último caracter del dni sea una letra, que los anteriores sean números, y que la longitud total sea mayor o igual a 8. 
	//En caso de no cumplirse estas condiciones, se enviará un mensaje y se colocará el foco en el campo dni.
	if(!isNaN(dni.substring(dni.length-1,dni.length)) ){
		alert("El dni debe constar de una letra.");
		document.forms[formu].dni_new_reserva.focus();
		return false;
	}else{
		if(isNaN(dni.substring(0,dni.length-1))){
			alert("El dni solo debe contener una letra al final.");
			document.forms[formu].dni_new_reserva.focus();
			return false;	
		}else{
			if(dni.length<8){
				alert("El dni debe constar de 8 números y una letra.");
				document.forms[formu].dni_new_reserva.focus();
				return false;
			}else{
				if(dni.length == 8){
					document.forms[formu].dni_new_reserva.value = "0"+document.forms[formu].dni_new_reserva.value;
				}
			}
		}
	}
*/

	if(document.forms[formu].nombre_new_reserva.value ==""){
		alert("Debe introducir un nombre para la reserva");
		document.forms[formu].nombre_new_reserva.focus();
		return false;
	}
	if(document.forms[formu].apellido1_new_reserva.value ==""){
		alert("Debe introducir el primer apellido para la reserva");
		document.forms[formu].apellido1_new_reserva.focus();
		return false;
	}
	
	/*var diar=document.forms[formu].dia_new_reserva.value;
	var mesr=document.forms[formu].mes_new_reserva.value;
	var anior=document.forms[formu].anio_new_reserva.value;
	var g=new Date(anior+"/"+ mesr +"/"+ diar);
    var f=(diar+"/"+ mesr +"/"+ anior);
   
     

	 var dias=document.forms[formu].dias.value;
	 var mess=document.forms[formu].meses.value;
	 var anios=document.forms[formu].anios.value;
	 var h=new Date(anios+"/"+ mess +"/"+ dias);
	
	var d = new Date();
	 d.setFullYear(f.substring(6,10),
		f.substring(3,5)-1,
			f.substring(0,2))
			
			
	if(d.getMonth() != f.substring(3,5)-1
		|| d.getDate() != f.substring(0,2))
	{
		alert("Fecha no válida.");
		return;
	}else {
           if(g<h){
		     	alert("Fecha no válida,menor que la actual.");
		     
		   	}}*/
	
	var telefono = document.forms[formu].telefono_new_reserva.value;
	//Se comprueba que el campo telefono tenga exactamente 9 caracteres
	//En caso de no cumplirse, se mostrará un mensaje y se devolverá al usuario al form_altalario
	if(telefono.length < 9){
		alert("El teléfono debe constar de al menos 9 dígitos");	
		document.forms[formu].telefono_new_reserva.focus();
		return false;
	}else{
		//Una vez que el campo telefono conste de 9 caracteres, se comprueba que cada uno de ellos sea un dígito.
		//En caso de no cumplirse , se mostrará un mensaje y se devolverá al usuario al form_altalario
		for(i=0;i<telefono.length-1;i++){
			if(isNaN(telefono.substring(i,i+1))){
				alert("El teléfono debe constar de al menos 9 DÍGITOS");	
				document.forms[formu].telefono_new_reserva.focus();
				return false;
			}
		}
	}
	
	//Comprobamos que se haya intronucido el nombre del empleado que realiza la reserva
	if(document.forms[formu].empleado_new_reserva.value ==""){
		alert("Debe introducir el nombre del empleado que realiza la reserva");
		document.forms[formu].empleado_new_reserva.focus();
		return false;
	}

	//Solo se hace la validación en caso de que haya algo escrito en el campo mail, puesto que puede ser nulo
	if(document.forms[formu].email_new_reserva.value != ""){
		if(document.forms[formu].email_new_reserva.value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
			alert("Introduzca el E-mail con el el formato correcto (\"xxxx@xxx.xxx\")");
			document.forms[formu].email_new_reserva.focus();
			return false;
		}
	}
		
	return true;
}
// Calcula la letra del dni.
		function calcletra(dni)
		{
			var JuegoCaracteres="TRWAGMYFPDXBNJZSQVHLCKET";
			var Posicion= dni % 23;
			var Letra = JuegoCaracteres.charAt(Posicion);
			return Letra;
		}


// Función que comprueva los campos dela modificacion introducidos antes de enviarlos a que sean 
// introducidos en la base de datos. Si son correctos, esta funcion devuelve true, y sino, false
function valida_campos_mod(formu){

	if (camas_tot <= 0){
		alert ("La reserva tiene que tener al menos una cama");
		return false;
	}

	if (camas > 0){
		alert ("Ha de asignar todas las camas para continuar");
		return false;
	}

	//Se comprueba que contengan algo los campos nombre y primer apellido
/*	var dni = document.forms[formu].dni_mod_reserva.value;

	//comprueba que el último caracter del dni sea una letra, que los anteriores sean números, y que la longitud total sea mayor o igual a 8. 
	//En caso de no cumplirse estas condiciones, se enviará un mensaje y se colocará el foco en el campo dni.
	if(!isNaN(dni.substring(dni.length-1,dni.length)) ){
		alert("El dni debe constar de una letra.");
		document.forms[formu].dni_mod_reserva.focus();
		return false;
	}else{
		if(isNaN(dni.substring(0,dni.length-1))){
			alert("El dni solo debe contener una letra al final.");
			document.forms[formu].dni_mod_reserva.focus();
			return false;	
		}else{
			if(dni.length<8){
				alert("El dni debe constar de 8 números y una letra.");
				document.forms[formu].dni_mod_reserva.focus();
				return false;
			}else{
				if(dni.length == 8){
					document.forms[formu].dni_mod_reserva.value = "0"+document.forms[formu].dni_mod_reserva.value;
				}
			}
		}
	}

*/
	if(document.forms[formu].nombre_mod_reserva.value ==""){
		alert("Debe introducir un nombre para la reserva");
		document.forms[formu].nombre_mod_reserva.focus();
		return false;
	}
	if(document.forms[formu].apellido1_mod_reserva.value ==""){
		alert("Debe introducir el primer apellido para la reserva");
		document.forms[formu].apellido1_mod_reserva.focus();
		return false;
	}
	//Comprobación fecha válida
	/*var diar=document.forms[formu].dia_mod_reserva.value;
	 var mesr=document.forms[formu].mes_mod_reserva.value;
	 var anior=document.forms[formu].anio_mod_reserva.value;
	 var g=new Date(anior+"/"+ mesr +"/"+ diar);
     var f=(diar+"/"+ mesr +"/"+ anior);

	  var dias=document.forms[formu].dias.value;
	 var mess=document.forms[formu].meses.value;
	 var anios=document.forms[formu].anios.value;
	 var h=new Date(anios+"/"+ mess +"/"+ dias);
	 alert(h);
	var d = new Date();
	 d.setFullYear(f.substring(6,10),
		f.substring(3,5)-1,
			f.substring(0,2))
			
			
	if(d.getMonth() != f.substring(3,5)-1
		|| d.getDate() != f.substring(0,2))
	{
		alert("Fecha no válida.")
		return false;
	}else {
           if(g<h){
		     	alert("Fecha no válida,menor que la actual.");
		   		return false;}}*/
	//Se comprueba que el campo telefono tenga exactamente 9 caracteres o más
	//En caso de no cumplirse, se mostrará un mensaje y se devolverá al usuario al form_altalario
	var telefono = document.forms[formu].telefono_mod_reserva.value;

	if(telefono == ''){
		alert("El teléfono debe constar de al menos 9 dígitos");	
		document.forms[formu].telefono_mod_reserva.focus();
		return false;
	}else{
		//Una vez que el campo telefono conste de 9 caracteres, se comprueba que cada uno de ellos sea un dígito.
		//En caso de no cumplirse , se mostrará un mensaje y se devolverá al usuario al form_altalario
		for(i=0;i<telefono.length-1;i++){
			if(isNaN(telefono.substring(i,i+1))){
				alert("El teléfono debe constar de al menos 9  DÍGITOS");	
				document.forms[formu].telefono_mod_reserva.focus();
				return false;
			}
		}
	}
	
	//Comprobamos que se haya intronucido el nombre del empleado que realiza la reserva
	if(document.forms[formu].empleado_mod_reserva.value ==""){
		alert("Debe introducir el nombre del empleado que realiza la reserva");
		document.forms[formu].empleado_mod_reserva.focus();
		return false;
	}

	//Solo se hace la validación en caso de que haya algo escrito en el campo mail, puesto que puede ser nulo
	if(document.forms[formu].email_mod_reserva.value != ""){
		if(document.forms[formu].email_mod_reserva.value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
			alert("Introduzca el E-mail con el el formato correcto (\"xxxx@xxx.xxx\")");
			document.forms[formu].email_mod_reserva.focus();
			return false;
		}
	}
		
	return true;
}

/*Esta funcion se encarga de asignar camas que solo van a estar disponibles un numero determinado de dias*/
function asignar_temp(entrada,id,motivo){
	
	if (estado == 'alta'){
	
		if (document.form_alta.dni_new_reserva.value == ''){
			alert ('Por favor, introduzca un DNI para continuar');
			return;
		}
	}

	if (estado == 'modificar'){
		if (document.form_mod.dni_mod_reserva.value == ''){
			alert ('Por favor, introduzca un DNI para continuar');
			return;
		}
	}
	
	if (parseInt(entrada.replace(/-/g,""))<parseInt(salida_def.replace(/-/g,""))){
		//Si la fecha de salida es posterior a la de la cama que queremos asignar, pedimos confirmacion
		entrada_partida=entrada.split("-");
		if (motivo == 'ocupada'){
			veri=confirm("La cama estrá ocupada a partir del  "+entrada_partida[2]+"-"+entrada_partida[1]+"-"+entrada_partida[0]+"\n                   ¿Desea continuar?");
		}else{
			veri=confirm("La habitación cambia de tipo el día  "+entrada_partida[2]+"-"+entrada_partida[1]+"-"+entrada_partida[0]+"\n                   ¿Desea continuar?");
		}
		if (veri){
			//Si se confirma la asignacion, asignamos la cama y guardamos las fechas
			entrada_def=entrada;
			salida_def=entrada;
			trozos=id.split("-");
			estado='temp';
			asignar_cama(trozos[0],trozos[1],trozos[2]);
		}
	}else{
		trozos=id.split("-");
		estado='temp';
		asignar_cama(trozos[0],trozos[1],trozos[2]);
	}
}

// Funcion que ordena el Listado de Reservas al hacer clic en los campos disponibles en el mismo
function ordenar_listado(criterio){
	var orden;
	if (criterio=='<?php echo $partes[0]; ?>'){
		if ('<?php echo $partes[1]; ?>'=='dec'){orden='crec';}else{orden='dec';}
	}
	if (estado=='alta'){
		document.form_alta.action = "?pag=reservas.php&noches="+document.form_alta.pernoctas_new_reserva.value+"&order="+criterio+"_"+orden;
		document.form_alta.submit();
	}
	if (estado=='detalles'){
		document.location.href="?pag=reservas.php&op=1&dni=<?php echo $_GET['dni'];?>&order="+criterio+"_"+orden+"&llegada=<?php echo $cliente['llegada'];?>";
	}
	if (estado=='mod'){
		document.form_mod.action = "?pag=reservas.php&op=2&dni=<?php echo $_GET['dni'];?>&noches="+document.form_alta.pernoctas_mod_reserva.value+"&order="+criterio+"_"+orden+"&llegada=<?php echo $cliente['llegada'];?>";
		document.form_alta.submit();
	}
	if (estado=='baja'){
		document.location.href="?pag=reservas.php&op=3&dni=<?php echo $_GET['dni'];?>&order="+criterio+"_"+orden+"&llegada=<?php echo $cliente['llegada'];?>";
	}
}

// Funcion de cambiar de ventana en la Distribución de Habitaciones
function cambiar_pagina_det(valor){
	if (estado == 'alta'){
		document.form_alta.res_num_pag.value = valor;
		document.form_alta.action='?pag=reservas.php&dni='+dni_activo+'&noches='+dias;
		document.form_alta.submit();
	}
	if (estado == 'baja'){
		document.form_baja.res_num_pag.value = valor;
		document.form_alta.action='?pag=reservas.php&op=3&dni='+dni_activo+'&llegada=<?php echo $cliente['llegada'];?>';
		document.form_baja.submit();
	}
	if (estado == 'detalles'){
		document.form_alta.res_num_pag.value = valor;
		document.form_alta.action='?pag=reservas.php&op=1&dni='+dni_activo+'&llegada=<?php echo $cliente['llegada'];?>';
		document.form_alta.submit();
	}
	if (estado == 'modificar'){
		document.form_mod.res_num_pag.value = valor;
		document.form_alta.action='?pag=reservas.php&op=2&dni='+dni_activo+'&llegada=<?php echo $cliente['llegada'];?>&noches='+dias+'<?php if(isset($_GET['temp'])){echo "&temp=".$_GET['temp'];}?>';
		document.form_mod.submit();
	}
}


//Esta función muestra o oculta el la capa de la leyenda de 'distribución de habitaciones' segun el parámetro 'act'
function ver_leyenda(act){
	if(act == '1')
		document.getElementById('leyenda').style.visibility='hidden';
	else
		document.getElementById('leyenda').style.visibility='visible';
}

function comp_ingreso(formu){
	if (formu == "alta"){
		if (document.form_alta.ingreso_new_reserva.value.length > 0){
			var ingreso = parseInt(document.form_alta.ingreso_new_reserva.value);
			if (isNaN(ingreso) || ingreso < 0){
				alert ("El ingreso ha de ser un numero mayor que cero");
				document.form_alta.ingreso_new_reserva.value = "0";
			}
		}
	}
	if (formu == "mod"){
		if (document.form_mod.ingreso_mod_reserva.value.length > 0){
			var ingreso = parseInt(document.form_mod.ingreso_mod_reserva.value);
			if (isNaN(ingreso) || ingreso < 0){
				alert ("El ingreso ha de ser un numero mayor que cero");
				document.form_mod.ingreso_mod_reserva.value = "0";
			}
		}	
	}
}
/* De momento, no
function ver_temp(esto){
	//document.getElementById("temp_"+esto).style.display='block';
	document.getElementById(esto).className='cama_temp_over';
}

function no_ver_temp(esto){
	//document.getElementById("temp_"+esto).style.display='none';
	document.getElementById(esto).className='cama_temp';
}*/

<?php


	
	if ($_GET['op']){
		echo ("cambiar(".$_GET['op'].");");
	}


///////////////////////////////////////////////////////////////////////////////////////////////
//********************** Conservamos las camas asignadas en alta ****************************//
///////////////////////////////////////////////////////////////////////////////////////////////

	if ($_GET['op']==2){
		if (isset($_POST['camas_mod_reserva'])){
			echo "camas_tot=".$_POST['camas_mod_reserva']."; ";
			echo "camas=".$_POST['camas_mod_reserva']."; ";
			echo "document.form_dist.camas_restantes.value = camas; ";
		}
	}else{
		if (isset($_POST['camas_new_reserva'])){
			echo "camas_tot=".$_POST['camas_new_reserva']."; ";
		}
	}

	if (isset($_POST['camas_restantes'])){
		echo "camas=".$_POST['camas_new_reserva']."; ";
	}

	//Si habia camas asignadas, las vuelvo a asignar
	for ($i=0;$i<count($habita);$i++){
		if ($_POST['camas_hab_'.$habita[$i]['id']]>0 && !isset($_GET['alta']) && !isset($_GET['mod'])){
			$pera = $_POST['camas_hab_'.$habita[$i]['id']];
			for ($j=0;$j<$_POST['camas_hab_'.$habita[$i]['id']];$j++){
				for ($k=1;$k<=$habita[$i]['camas_totales'];$k++){
					for ($col = 0;$col<count($habita[$i]['col']);$col++){
						if ($habita[$i]['col'][$col]['camas']>=$k){
							$hab_disp = true;
							//Compruebo que la cama no esté ocupada antes de asignarla
							for ($ocu = 0;$ocu < count($ocupadas);$ocu++){
								if ($ocupadas[$ocu] == ($habita[$i]['id']."-".$k."-".$col)){
									$hab_disp = false;
								}
							}
							if ($pera>0 && $hab_disp){			
								//Si todo va benne, asigno
								echo ("
									asignar_cama('".$habita[$i]['id']."','".$k."','".$col."'); 
									");
								$pera--;
							}
								
						}
					}
				}
			}
		}
	}


	//Si estamos en MODIFICAR, asignamos las camas de la reserva a modificar por si se quieren cambiar
	if ($_GET['op']==2 && !isset($_POST['camas_mod_reserva'])){
		$camas=0;
		for ($i=0;$i<count($cliente['camas']);$i++){
			$camas+=intval($cliente['camas'][$i]);
		}

		echo "camas_tot=".$camas.";";
		echo "camas=".$camas.";";
	
		for ($i=0;$i<count($cliente['camas']);$i++){
			for ($j=0;$j<count($habita);$j++){
				if ($cliente['hab'][$i]==$habita[$j]['id']){
					$k=1;
					$asignadas=0;
					$cont = true;
					while ($asignadas<$cliente['camas'][$i] && $cont){
						for ($col=0;$col<count($habita[$j]['col']);$col++){
							$ocu = false;
							if ($habita[$j]['col'][$col]['camas'] >= $k){
								for ($p=0;$p<count($ocupadas);$p++){
									if ($ocupadas[$p]==($habita[$j]['id']."-".$k."-".$col)){
										$ocu = true;
									}
								}
								if (!$ocu){
									if ($asignadas < $cliente['camas'][$i]){
										echo "
											asignar_cama('".$habita[$j]['id']."','".$k."','".$col."');
										";
										$asignadas++;
									}
								}
							}
						}
						$k++;
					}
				}
			}
		}
	}
	
	if (isset($_GET['temp']) && $_GET['temp'] == si && $_SESSION['reservas']['noches'] != 0){
		echo "
			//alert ('Quedan ".$_SESSION['reservas']['noches']." noches por asignar');
			document.form_alta.action = '?pag=reservas.php&fecha_cal=".$_GET['fecha_salida']."&noches=".$_SESSION['reservas']['noches']."&temp=cont';
			document.form_alta.submit();
		";
	}

	//******Cierro la conexion a la base de datos*******
	mysql_close($db);

?>
	//	Funcion para cambiar el dia en el calendario
	function cambiar_dia(dia_index,mes_index,anio_index){	
		if (estado == 'alta'){
			document.form_alta.action = "?pag=reservas.php&fecha_cal="+dia_index+"-"+mes_index+"-"+anio_index<?php
			if (isset($_GET['noches'])){echo "+\"&noches=".$_GET['noches']."\"";}	
			?>;
			document.form_alta.submit();
		}else{
			if (estado == 'modificar'){
				document.form_mod.action="?pag=reservas.php&noches=<?php echo $_GET['noches'];?>&fecha_cal="+dia_index+"-"+mes_index+"-"+anio_index+"&op=2&dni=<?php echo $_GET['dni'];?>&llegada=<?php echo $_GET['llegada'];?>";
				document.form_mod.submit();
			}
			if (estado == 'detalles'){
				window.location.href = '?pag=reservas.php&fecha_cal='+dia_index+'-'+mes_index+'-'+anio_index+"&dni="+dni_activo+"&llegada=<?php echo $_GET['llegada'];?>&op=1";
			}
			if (estado == 'baja'){
				window.location.href='?pag=reservas.php&fecha_cal='+dia_index+'-'+mes_index+'-'+anio_index+"&dni="+dni_activo+"&llegada=<?php echo $_GET['llegada'];?>&op=3";
			}
		}
	}

</script>
