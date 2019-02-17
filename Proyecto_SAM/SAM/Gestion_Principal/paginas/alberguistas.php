<link rel="stylesheet" type="text/css" href="css/formulario_alb_per.css">
<?php
//Comprobamos que el usuario tiene permisos para acceder a esta sección
if(isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']==true) {

//Si no existe $_POST['sub'] habrá realizado un alta,modificacion o baja. Por tanto vaciamos las variables de sesion
if(!isset($_POST['sub'])){
	$_SESSION['alb'] = Array();
	$_POST['sub'] = "";
}
//Incluimos paginas necesarias para el correcto funcionamiento

//Página que asigna variables de sesion para presentar las habitaciones
include('paginas/gdh_session.php');
//Página que contiene las funciones resta_fecha() y establecer_fecha()
include('paginas/funciones_alb_per.php');

/*
echo "<br> miremos SESSION['reserva']: ";
if (isset($_SESSION['reserva'])){
	echo " TURE!!!";
}else{
	echo "false...";
}
*/
$meses = array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

$habitaciones_orden = array();
$numero_paginas = array();

for ($i = 0; $i < COUNT($_SESSION['pag_hab']); $i++) {
	if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$numero_paginas)) {
		$numero_paginas[] = $_SESSION['pag_hab'][$i]['pagina'];
	}
	if ($_SESSION['pag_hab'][$i]['pagina'] == $_SESSION['gdh']['dis_hab']['num_pag']) {
		//echo "la pagina actual en la session es ".$_SESSION['gdh']['dis_hab']['num_pag'];
		$cont = COUNT($habitaciones_orden);
		$habitaciones_orden[$cont]['orden'] = $_SESSION['pag_hab'][$i]['orden'];
		$habitaciones_orden[$cont]['Id_Tipo_Hab'] = $_SESSION['pag_hab'][$i]['Id_Tipo_Hab'];
	}
}	

foreach ($habitaciones_orden as $llave => $fila) {
   $orden[$llave]  = $fila['orden'];
}

if (COUNT($habitaciones_orden) > 0) {
	@ ARRAY_MULTISORT($orden, SORT_DESC, $habitaciones_orden);
}

//Asignamos la página actual de la distribución de habitaciones
if(isset($_POST['pagina_habitaciones']) && trim($_POST['pagina_habitaciones']) != ""){
	$pagina_habitaciones = $_POST['pagina_habitaciones'];
}else{
	$pagina_habitaciones = $_SESSION['gdh']['dis_hab']['num_pag'];
}	


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//(Validar reserva) Se asignan los datos de la reserva a variables de sesion
//echo "<br> accion = ".$_GET['accion'].'continuar_reserva, session[dni]='.$_SESSION['reserva']['dni']." FECHA_ANYO_ALBERGUISTA = ".$_SESSION['reserva']['anyo_fecha_alberguista'];
if(isset($_GET['accion']) && trim($_GET['accion'])!= "" && trim(strtolower($_GET['accion'])) == "reserva"){
		//alberto
		
		$qry_res_pra = "SELECT * FROM pra WHERE DNI_PRA = '".$_GET['DNI_PRA']."' ";
		$res_res_pra = mysql_query($qry_res_pra);
		if (!$res_res_pra){echo "<script> alert('Se ha producido un error en la reserva. Verifique la reserva'); window.location.href='?pag=gdh.php' </script>"; exit();}
		$tupla_res_pra = mysql_fetch_array($res_res_pra);
		//if ($_SESSION['reserva']['sw_reserva'] != "true"){
			$_SESSION['reserva']['sw_reserva'] = "true";
			$_SESSION['reserva']['dni'] = $tupla_res_pra['DNI_PRA'];
			$_SESSION['reserva']['nombre'] = $tupla_res_pra['Nombre_PRA'];
			$_SESSION['reserva']['apellido1'] = $tupla_res_pra['Apellido1_PRA'];
			$_SESSION['reserva']['apellido2'] = $tupla_res_pra['Apellido2_PRA'];
			$_SESSION['reserva']['telefono'] = $tupla_res_pra['Tfno_PRA'];
			$_SESSION['reserva']['email'] = $tupla_res_pra['Email_PRA'];
		//}

		$qry_res_det = "SELECT * FROM detalles WHERE DNI_PRA = '".$_GET['DNI_PRA']."' AND Fecha_Llegada = '".$_GET['Fecha_Llegada']."' ";
		$res_res_det = mysql_query($qry_res_det);
		$tupla_res_det = mysql_fetch_array($res_res_det);
		$_SESSION['reserva']['llegada_tarde'] = $tupla_res_det['Llegada_Tarde'];
		$_SESSION['reserva']['fecha_llegada'] = $tupla_res_det['Fecha_Llegada'];
		$_SESSION['reserva']['ingreso_total'] = $tupla_res_det['Ingreso'];
		$_SESSION['reserva']['empleado'] = $tupla_res_det['Nombre_Empleado'];
		$_SESSION['reserva']['fecha_reserva'] = $tupla_res_det['Fecha_Reserva'];
		$_SESSION['reserva']['observaciones'] = $tupla_res_det['Observaciones_PRA'];
		$_SESSION['reserva']['pernocta_total'] = 0;

		//Meto en el array los datos de todas las noches que tengo la reserva
		$noche = 0;
		while (mysql_num_rows($res_res_det) > 0){
			$_SESSION['reserva']['noche'][$noche]['fecha_llegada'] = $tupla_res_det['Fecha_Llegada'];
			$_SESSION['reserva']['noche'][$noche]['fecha_salida'] = $tupla_res_det['Fecha_Salida'];
			$_SESSION['reserva']['noche'][$noche]['pernocta'] = $tupla_res_det['PerNocta'];
			$_SESSION['reserva']['pernocta_total'] += $tupla_res_det['PerNocta'];

			$qry_res_res = "SELECT * FROM reserva WHERE DNI_PRA = '".$_GET['DNI_PRA']."' AND Fecha_Llegada = '".$tupla_res_det['Fecha_Llegada']."'";
			if ($noche == 0){$qry_res_res = $qry_res_res." AND Id_Hab = '".$_GET['Id_Hab']."' ";}
			$res_res_res = mysql_query($qry_res_res);
			$tup_res_res = mysql_fetch_array($res_res_res);
			$_SESSION['reserva']['noche'][$noche]['id_hab'] = $tup_res_res['Id_Hab'];
			$_SESSION['reserva']['noche'][$noche]['camas'] = $tup_res_res['Num_Camas'];
			$qry_res_det = "SELECT * FROM detalles WHERE DNI_PRA = '".$_GET['DNI_PRA']."' AND Fecha_Llegada = '".$tupla_res_det['Fecha_Salida']."' ";
			$res_res_det = mysql_query($qry_res_det);
			$tupla_res_det = mysql_fetch_array($res_res_det);
			$noche++;
		}

		//Compruevo si es la ultima verificacion de la reserva
		$qry_comp_res = "SELECT * FROM reserva WHERE DNI_PRA = '".$_GET['DNI_PRA']."' AND Fecha_Llegada = '".$_GET['Fecha_Llegada']."'";
		$res_comp_res = mysql_query($qry_comp_res);
		if (mysql_num_rows($res_comp_res) <= 1 && $_SESSION['reserva']['noche'][0]['camas'] <= 1){$_SESSION['reserva']['salir'] = true;}

		
		$_SESSION['reserva']['fecha_salida'] = $_SESSION['reserva']['noche'][$noche-1]['fecha_salida'];

		
		//Como voy a recibir la habitacion que quiero validar via URL, almaceno los datos
		if (isset($_GET['Id_Hab']) && $_GET['Id_Hab'] != ""){$_SESSION['reserva']['id_hab'] = $_GET['Id_Hab'];}
		if (isset($_GET['Ingreso']) && $_GET['Ingreso'] != ""){$_SESSION['reserva']['ingreso'] = $_GET['Ingreso'];}

		//Compruevo si los datos del pra de la reserva han sido ya introducidos 
		if ($_GET['Datos_Reserva'] == "true"){
			$_SESSION['reserva']['mostrar'] = true;
		}else{
			$_SESSION['reserva']['mostrar'] = false;
		}
}else{
	////// Para controlar si se esta trabajando con la validacion de reserva o no, utilizo GET['accion'] = continuar reserva ///////
	if ($_GET['accion'] != 'continuar_reserva'){$_SESSION['reserva']['sw_reserva'] = false; unset($_SESSION['reserva']);}
	
}


// Si se trata de la validación de una reserva, pasamos los datos de la reserva  a la variable de sesión que registra la estancia o estancias del alberguista
if($_SESSION['reserva']['sw_reserva'] == "true"){//OJOJOJOJOJOJOOJOJOJOJO***************************************************************
	$_SESSION['alb']['estancia']['habitaciones'][0]['fecha'] = $_SESSION['reserva']['fecha_llegada'];
	$_SESSION['alb']['estancia']['habitaciones'][0]['id'] = $_SESSION['reserva']['noche'][0]['id_hab'];
	$_SESSION['reserva']['hab_actual'] = $_SESSION['reserva']['id_hab'];
	$fecha_calendario = $_SESSION['reserva']['fecha_llegada'];
	$split_fecha_reserva = split("-",$_SESSION['reserva']['fecha_llegada']);
	if(intval(trim($split_fecha_reserva[1]))  <10 && strlen($split_fecha_reserva[1]) == 1){
		$split_fecha_reserva[1]= "0".trim($split_fecha_reserva[1]);
	}
	if(intval(trim($split_fecha_reserva[2]))  <10 && strlen($split_fecha_reserva[2]) == 1){
		$split_fecha_reserva[2]= "0".trim($split_fecha_reserva[2]);
	}
}



?>

<!-- Comienzo de las funciones Javascript -->
<script language="JavaScript">
	//función que abre la ventana de búsqueda de alberguistas (Enlace de la lupa)
	function abrir_busqueda(){
		window.open( "paginas/alb_busq_dni.php?form=0", "_blank", "width=650px,height=650px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");
	}
	
	//Cambia el color de fondo. el color de letra, y le el icono de una mano al pasar el puntero por encima de la fila de tabla enviada como argumento
	function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#569CD7';
	  	tr.style.color = '#F4FCFF';
	  	tr.style.cursor = 'pointer';
	}
	
	//Al contrario que resaltar_seleccion , devuelve a su estado normal a la fila de tabla enviada como argumento
	function desresaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#F4FCFF';
	  	tr.style.color = '#3F7BCC';
	}
	
	//muestra la caja de habitaciones, en caso de que no esté mostrada , y  disminuye la altura del listado de alberguistas
	//En caso de que se este mostrando, se oculta y el listado de alberguistas vuelve a su estado normal
	function nuevo_habitaciones(){
		if(document.getElementById('caja_superior_derecha_a').style.display=='none'){
			document.getElementById('caja_superior_derecha_a').style.display='block';
			document.getElementById('caja_superior_derecha_b').style.height='240px';
			document.getElementById('tabla_listado').style.height='220px';
		}else{
			oculta_habitaciones();	
		}
	}

	//oculta la caja de habitaciones y vuelve a su estado normal el listado de alberguistas
	function oculta_habitaciones(){
		document.getElementById('caja_superior_derecha_a').style.display='none';
		document.getElementById('tabla_listado').style.height='400px';
	}
		//función que selecciona una cama de una habitación. Los argumentos forman el id de la cama en la distribución de habitaciones
		function asigna_habitacion(hab,i,col,fila,parcial){

			//Se evalua el color de fondo de la celda de la tabla. En función de su color de fondo se sabrá si se habrá intentado asignar o no.			
			var fecha_cal = document.forms[0].fecha_cal.value.split("-");
			if(document.forms[0].asignada.value == 1){
					//En este caso estará ocupada, por tanto se le devolverá el color de habitación libre, o habitación temporal, según corresponda.		
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
						//El valor del campo id_cama del formulario no es igual que los argumentos recibidos.
						//Se devolverá a su estado normal a la cama que estuviera asignada en ese momento, y se le dará apariencia de asignada a la cama recibida
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
					document.forms[0].asignada.value = 0;

				}
				//se le da apariencia de asignada a la cama en que se ha hecho click
				document.getElementById(hab+"-"+i+"-"+col+"-"+fila).className = "cama_asignada";
				//Comprobamos si se trata de una cama ocupada temporalmente y registramos el resultado en el campo estancia_parcial del formulario
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
		}

		
	
		// Comprueba si existe el elemento en el vector.
		function ExisteElemento(vector, elemento)
		{
			var i,existe;
			existe = false;
			for(i=0; i<vector.length; i++)
		    {
		        
		    	if (vector[i] == elemento)
		    	{
		    		existe = true;	
		        	break;
		        }
		    }
		
			return (existe);
		} 
		
		// Calcula la letra del dni.
		function calcletra(dni)
		{
			var JuegoCaracteres="TRWAGMYFPDXBNJZSQVHLCKET";
			var Posicion= dni % 23;
			var Letra = JuegoCaracteres.charAt(Posicion);
			return Letra;
		}
		
		function validadni(formu)
		{
			var dni = document.forms[formu].dni_alberguista.value;
		    var tipo_documento = document.forms[formu].tipo_documentacion.value;
		    var pais = document.forms[formu].pais_alberguista[document.forms[formu].pais_alberguista.selectedIndex].value; 	
		    
		   	// Obtenemos los paises que tenga Carta Europea de la base de datos.
								 
			<?			 
			  $res = mysql_query("SELECT * FROM `pais` WHERE Carta_Europea='S'");
			  // Array con los paises con es válido el tipo de documento <<I>>.
			  echo "var PaisesPermitidos = new Array(".mysql_num_rows($res).");";
			  for ($i=0;$i<mysql_num_rows($res);$i++)
			  {
			    $fila = mysql_fetch_array($res);	
				echo "\nPaisesPermitidos[".$i."] =\"".$fila['Id_Pais']."\";"; 		
				    
			  }
			?>	
		    
			//Comprueba que tenga una longitud entre 7 y 8 números, y en caso de tener un carácter(NIF) será una letra permitida. 
			//En caso de no cumplirse estas condiciones, se enviará un mensaje y se colocará el foco en el campo dni.
			if (dni=="") alert("Debe rellenar el campo DNI.");
			// En caso de tratarse del dni o del carnet de conducir...
			else if (  (tipo_documento == "D") || (tipo_documento == "C") )
			{
			   
				// Es solo válido para españoles.
				if (pais!='ES') 
				{
					alert("El pais no corresponde con el tipo de documento");					
					document.forms[formu].pais_alberguista.focus();
					return false;
				}
				
				else 
				{	
				    // Tiene que tener una longitud de 7 números como mínimo.
				    if (dni.length < 7)
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.forms[formu].dni_alberguista.focus();
						return false;
					}
				    // Comprobamos si los siete primeros son números.
					else if(isNaN(dni.substring(0,7)) )
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.forms[formu].dni_alberguista.focus();
						return false;
					}
				
					else
					{
					    // Si tiene solo siete números, le añadimos un cero y la letra.
						
						if(dni.length == 7)
						{						 	
							document.forms[formu].dni_alberguista.value = "0"+dni+calcletra(dni);
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
								document.forms[formu].dni_alberguista.focus();
								return false;
							}			
							// Le añadinos la letra si el último caracter es un número
							if (!isNaN(dni.substring(7,8))) document.forms[formu].dni_alberguista.value = dni+calcletra(dni);						 
						 }   
							// Si es igual a nueve..
						else if (dni.length == 9)
						{
						    
						 	// El penúltimo tiene que ser un número y el último carácter solo puede ser una letra permitida
							if ( (isNaN(dni.substring(7,8)) ) || (calcletra(dni.substring(0,8))!=dni.substring(8,9)) ) 
							{
								alert("Debe rellenar correctamente la letra de control del DNI.");					
								document.forms[formu].dni_alberguista.focus();
								return false;
							}
									
						 }
						else if (dni.length > 9)
						{
							alert("Debe rellenar correctamente el campo DNI.");					
							document.forms[formu].dni_alberguista.focus();
							return false;
						}
				      
				    }
				 }
			 } 
			 else if ( (tipo_documento == "N") && (pais == "ES") )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.forms[formu].pais_alberguista.focus();
				return false;
			 } 
			
			 else if ( (tipo_documento == "N") && (pais != "ES") )
			 {
				// Tiene que tener una longitud de 8 números como mínimo y 10 como máximo.
				if ( (dni.length<7) || (dni.length>10) )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.forms[formu].dni_alberguista.focus();
					return false;
				}
				
				// Comprobamos si el primer carácter es un carácter X o un número.
				else if( (isNaN(dni.substring(0,1))) && (dni.substring(0,1)!="X") )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.forms[formu].dni_alberguista.focus();
					return false;
				}				
									
				//alert (dni.length);
				//alert (dni.substring(0,dni.length));
				
				// Si el primer elemento no tiene X se la añadimos
			    if (!isNaN(dni.substring(0,1))) 
				{
					dni = "X"+dni;
					document.forms[formu].dni_alberguista.value = dni;				
				}
			
			    // Si solo tiene 7 elemenos númericos, le añadimos un cero.
				if ( (!isNaN(dni.substring(1,8))) && ( (dni.length == 8) || ((dni.length == 9) && (isNaN(dni.substring(8,9)))) ) )
				{				    
				    
					dni = "X0"+dni.substring(1,9);
					document.forms[formu].dni_alberguista.value = dni;
				
				}
				//alert (dni.substring(1,9));
				// Le añadinos la letra si el último caracter es un número
				if (dni.length!=10) document.forms[formu].dni_alberguista.value = dni+calcletra(dni.substring(1,9));	
				
				else if ( (dni.length==10) && ( (dni.substring(0,1)!="X") || (isNaN(dni.substring(1,9))) || 
											    ((dni.substring(9,10)) != calcletra(dni.substring(1,9)))    ) )
				{
					alert("Debe rellenar correctamente la letra de control del DNI.");					
					document.forms[formu].dni_alberguista.focus();
					return false;
				}				
				
			 } 
			 else if ( (tipo_documento == "I") && (!ExisteElemento(PaisesPermitidos, pais) ) )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.forms[formu].pais_alberguista.focus();
				return false;
			 }
			else if  ( (tipo_documento == "P") && (pais == "ES") && (dni.length>11) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					
				document.forms[formu].dni_alberguista.focus();
				return false;
			}
			else if  ( (tipo_documento == "P") && (pais != "ES") && (dni.length>14) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					
				document.forms[formu].dni_alberguista.focus();
				return false;
			}
			return true;
		}

function valida_campos(formu){

			
			var dni = document.forms[formu].dni_alberguista.value;
			var pais = document.forms[formu].pais_alberguista[document.forms[formu].pais_alberguista.selectedIndex].value; 	
			var tipo_documento = document.forms[0].tipo_documentacion.value;
			//alert("pais "+document.forms[formu].pais_alberguista[document.forms[formu].pais_alberguista.selectedIndex].value);
			
			if (!validadni(formu)) return false;		
			
			//Evaluamos que la fecha de expedición del documento sea como mínimo igual a la fecha de nacimiento
			var fecha_exp = Array(document.forms[0].dia_fecha_expedicion.value,document.forms[0].mes_fecha_expedicion.value, document.forms[0].anyo_fecha_expedicion.value);
			var fecha_nac = Array(document.forms[0].dia_fecha_alberguista.value,document.forms[0].mes_fecha_alberguista.value, document.forms[0].anyo_fecha_alberguista.value);
			
			
		    // Evaluamos si las fechas son correctas
		    var d = new Date();
		    var e = new Date();
		    var f_exp = (fecha_exp[0]+"/"+ fecha_exp[1] +"/"+ fecha_exp[2]);
			var f_nac = (fecha_nac[0]+"/"+ fecha_nac[1] +"/"+ fecha_nac[2]);
		
			d.setFullYear(f_exp.substring(6,10),
				f_exp.substring(3,5)-1,
					f_exp.substring(0,2))
			
			if(d.getMonth() != f_exp.substring(3,5)-1	|| d.getDate() != f_exp.substring(0,2))
			{
				alert("Debe introducir una fecha de expedición válida.")
				return false;
			}
			
			e.setFullYear(f_nac.substring(6,10),
				f_nac.substring(3,5)-1,
					f_nac.substring(0,2))
			
			// En caso de ser de España no puede tener el año y el mes igual a cero.
			if ( ((fecha_nac[0]=='0' ) && ( fecha_nac[1]=='0')) ) 
			{
				if (pais == "ES")
				{
					alert("Debe introducir una fecha de nacimiento válida.")
					return false;
				}
				else if (fecha_exp[2] < fecha_nac[2])
				{
					alert("Debe introducir una fecha de expedición mayor o igual a la fecha de nacimiento");	
					return false;
				}
			}
			else if (  e.getMonth() != f_nac.substring(3,5)-1	|| e.getDate() != f_nac.substring(0,2)) 
				{
					alert("Debe introducir una fecha de nacimiento válida.")
					return false;
				}
					
			// Se evalua que ha introducido una fecha de expedición y nacimiento.
			else if(fecha_exp[2] < fecha_nac[2]){
				alert("Debe introducir una fecha de expedición mayor o igual a la fecha de nacimiento");	
				return false;
			}else{
				if(fecha_exp[2] == fecha_nac[2]){
					if(fecha_exp[1] < fecha_nac[1]){
						alert("Debe introducir una fecha de expedición mayor o igual a la fecha de nacimiento");	
						return false;
					}else{
						if(fecha_exp[1] == fecha_nac[1]){
							if(fecha_exp[0] < fecha_nac[0]){
								alert("Debe introducir una fecha de expedición mayor o igual a la fecha de nacimiento");	
								return false;
							}
						}
					}
				}
			}


			if(document.forms[formu].nombre_alberguista.value ==""){
				alert("Debe rellenar el campo nombre.");
				document.forms[formu].nombre_alberguista.focus();
				return false;
			}
			if(document.forms[formu].apellido1_alberguista.value ==""){
				alert("Debe rellenar el campo primer apellido.");
				document.forms[formu].apellido1_alberguista.focus();
				return false;
			}
			if((document.forms[formu].apellido2_alberguista.value =="") && (pais=='ES') )
			{
				alert("Debe rellenar el campo segundo apellido.");
				document.forms[formu].apellido2_alberguista.focus();
				return false;
			}
			// Se comprueva que se halla introducido una provinvia
			if (document.forms[formu].provincia_alberguista.options[document.forms[formu].provincia_alberguista.selectedIndex].value == '' && pais == 'ES'){
				alert("Debe introducir una provincia válida");					
				document.forms[formu].provincia_alberguista.focus();
				return false;
			}
			var telefono = document.forms[formu].telefono_alberguista.value;
			//Se comprueba que el campo telefono tenga exactamente 9 caracteres
			//En caso de no cumplirse, se mostrará un mensaje y se devolverá al usuario al formulario
			if ( (telefono != "") && (pais == 'ES') ){
				if(telefono.length != 9){
					alert("Debe rellenar correctamente el campo teléfono.");	
					document.forms[formu].telefono_alberguista.focus();
					return false;
				}else{
					/*Una vez que el campo telefono conste de 9 caracteres, se comprueba que cada uno de ellos sea un dígito.
					En caso de no cumplirse , se mostrará un mensaje y se devolverá al usuario al formulario*/
					for(i=0;i<telefono.length-1;i++){
						if(isNaN(telefono.substring(i,i+1))){
							alert("Debe rellenar correctamente el campo teléfono.");	
							document.forms[formu].telefono_alberguista.focus();
							return false;
						}
					}
				}
			}			
			var cp = document.forms[formu].cp_alberguista.value;
			/*Se comprueba que el codigo postal conste de 5 caracteres.
			*De no ser así, se mostrará un mensaje indicándolo, y se devolverá al usuario al formulario*/
			if(cp!=""){
				if(cp.length!=5){
					alert('Debe rellenar el campo código postal.');
					document.forms[formu].cp_alberguista.focus();
					return false;
				}else{
					/*Teniendo 5 caracteres, se comprueba que cada uno de ellos sean números.
					En caso de no cumplirse, se muestra un mensaje y se devuelve el foco al campo codigo postal.
					*/
					for(i=0;i<cp.length-1;i++){
						if(isNaN(cp.substring(i,i+1))){
							alert('Debe rellenar el campo código postal.');
							document.forms[formu].cp_alberguista.focus();
							return false;
						}
					}
				}
			}
			//Se evalua que haya seleccionado una casilla de sexo
			if(document.forms[formu].sexo_alberguista[1].checked == false && document.forms[formu].sexo_alberguista[0].checked ==false){
				alert("Debe seleccionar una casilla de sexo.");
				return false;
			}
			//Solo se hace la validación en caso de que haya algo escrito en el campo mail, puesto que puede ser nulo
			if(document.forms[formu].email_alberguista.value != ""){
				if(document.forms[formu].email_alberguista.value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
					//se informa de que ha introducido el email con formato incorrecto
					alert("Debe rellenar el campo E-mail con el el formato correcto (\"xxxx@xxx.xxx\")");
					//se coloca el foco en el campo email
					document.forms[formu].email_alberguista.focus();
					return false;
				}
			}
		return true;
	}

		//Función que activa o desactiva los campos provincia, localidad . dirección y código postal, en función del pais que esté seleccionado.
		//Si el pais es España, se activarán, en caso contrario se desactivarán
	function paises(formu){
		
		if(document.forms[formu].pais_alberguista[document.forms[formu].pais_alberguista.selectedIndex].value != "ES"){
			//en este caso el pais seleccionado no es españa
			//procedemos a vaciar los campos relacionados
			document.forms[formu].cp_alberguista.value = "";
			document.forms[formu].localidad_alberguista.value = "";
			document.forms[formu].provincia_alberguista.value = "";
			document.forms[formu].direccion_alberguista.value = "";
			//procedemos a desactivar los campos relacionados
			document.forms[formu].cp_alberguista.disabled = true;
			document.forms[formu].localidad_alberguista.disabled = true;
			document.forms[formu].provincia_alberguista.disabled = true;
			document.forms[formu].direccion_alberguista.disabled = true;
		}else{
			if(document.forms[formu].cp_alberguista.disabled == true){
				
				//En este caso el pais seleccionado es españa, por tanto : 
				//procedemos a activar los campos relacionados
				document.forms[formu].cp_alberguista.disabled = false;
				document.forms[formu].localidad_alberguista.disabled = false;
				document.forms[formu].provincia_alberguista.disabled = false;
				//Colocamos el campo provincias con el valor "A" correspondiente a Alicante
				for(var i=0;i<document.forms[formu].provincia_alberguista.length;i++){
						if(document.forms[formu].provincia_alberguista.options[i].value == "A"){
							document.forms[formu].provincia_alberguista.options[i].selected=true;
							break;
						}
					}
				//continuamos activando campos
				document.forms[formu].direccion_alberguista.disabled = false;
				document.forms[formu].provincia_alberguista.options[0].selected = true;
			}else{
				if (document.forms[formu].provincia_alberguista.disabled == true){
					document.forms[formu].provincia_alberguista.disabled = false;
				}
			}
		}
	}

	//Comprueba que los datos de estancia sean correctos (evalua_estancia()) y que se haya seleccionado una habitación del mapa de habitaciones
	function evalua_habitacion(){
		if(document.forms[0].id_habitacion.value ==""){
			alert("Debe seleccionar una habitación.");
			return false;
		}else{
			//en caso de que se haya seleccionado una habitación, se pasa a evaluar la estancia
			evalua_estancia();
		}
	}
	//comprueba que los datos de la estancia sean correctos
	function evalua_estancia(){
		//comprueba que el campo pernocta sea numérico,  no esté vacio, sea distinto de 0, y que sea menor de 127
		if(!isNaN(document.forms[0].pernocta_alberguista.value) && document.forms[0].pernocta_alberguista.value != "" && 	document.forms[0].pernocta_alberguista.value != 0 && document.forms[0].pernocta_alberguista.value <= 127){
			//se envia el formulario
			if(document.forms[0].accion.value=="reserva"){
				document.forms[0].sub.value="false";
			}
			document.forms[0].submit();
		}else{
			//Ha rellenado el campo pernocta de forma incorrecta, se coloca el foco en dicho campo ,se lanza un aviso(alert) comunicándolo al usuario y se sale de la función
			document.forms[0].pernocta_alberguista.focus();
			alert("Debe rellenar correctamente el campo pernoctas, el maximo es 127");
			return false;
		}
	}

	//Da funcionalidad a las pestañas del formulario de alta, para mostrar la parte de datos personales o la de estancia
	function abrir(num,formu){
		if(num==2){
			if(valida_campos(formu)){
				for(var i=1;i<3;i++){
					document.getElementById("lis"+i).style.display="none";
				}
				document.getElementById("lis"+num).style.display="block";
			}
		}else{
			for(var i=1;i<3;i++){
				document.getElementById("lis"+i).style.display="none";
			}
			document.getElementById("lis"+num).style.display="block";
		}
	}
	
	function cambio_camas(cambio){	//Variación del numero de pernoctas (Funcionalidad de las Flechas de pernocta)
		var camas = parseInt(document.forms[0].pernocta_alberguista.value);
		var oper="camas=camas"+cambio;
		eval(oper);
		if (camas<=1){
			camas=1;
		}
		
		document.forms[0].pernocta_alberguista.value=camas;
		cambio_botones_habitaciones();
	}


	//Funcion que desactiva el boton aceptar(para enviar formulario) y activa el botón  de recargar el mapa de habitaciones
	function cambio_botones_habitaciones(){
		document.getElementById('boton_nuevo_habitacion2').style.display="none";
		document.getElementById('boton_ver_habitaciones').style.display="block";
	}	
	
	//Se utiliza al cambiar el campo DNI, se busca en la base de datos algun registro con DNI similar, y en caso de encontrarlo se rellenan los datos personales con los datos de la base de datos
	function rellena_alta(t_dni , dni , nom , a1 , a2 ,pais , prov , loc ,  dir , cp , f_nac , f_exp , sexo , tel , email , obs ){		
		for(var i=0;i<document.forms[0].tipo_documentacion.length;i++){
			if(document.forms[0].tipo_documentacion[i].value == t_dni){
				document.forms[0].tipo_documentacion[i].selected = true;
				break;
			}
		}
		document.forms[0].dni_alberguista.value = dni;
		document.forms[0].dni_ant.value = dni;
		document.forms[0].test_dni.value = "1";
		document.forms[0].nombre_alberguista.value = nom;
		document.forms[0].apellido1_alberguista.value = a1;
		document.forms[0].apellido2_alberguista.value = a2;
		document.forms[0].direccion_alberguista.value = dir;
		document.forms[0].cp_alberguista.value = cp;
		document.forms[0].localidad_alberguista.value = loc;
		document.forms[0].telefono_alberguista.value = tel;
		document.forms[0].email_alberguista.value = email;
		document.forms[0].observaciones_alberguista.value = obs;
		for(var i=0;i<document.forms[0].pais_alberguista.length;i++){
			if(document.forms[0].pais_alberguista[i].value == pais){
				document.forms[0].pais_alberguista[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].provincia_alberguista.length;i++){
			if(document.forms[0].provincia_alberguista[i].value == prov){
				document.forms[0].provincia_alberguista[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].sexo_alberguista.length;i++){
			if(document.forms[0].sexo_alberguista[i].value == sexo	){
				document.forms[0].sexo_alberguista[i].checked = true;
				break;
			}
		}
		var fecha = f_nac.split("-");
		for(var i=0;i<document.forms[0].dia_fecha_alberguista.length;i++){
			if(document.forms[0].dia_fecha_alberguista[i].value == fecha[2]){
				document.forms[0].dia_fecha_alberguista[i].selected = true;
				break;
			}
		}		
		for(var i=0;i<document.forms[0].mes_fecha_alberguista.length;i++){
			if(document.forms[0].mes_fecha_alberguista[i].value == fecha[1]){
				document.forms[0].mes_fecha_alberguista[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].anyo_fecha_alberguista.length;i++){
			if(document.forms[0].anyo_fecha_alberguista[i].value == fecha[0]){
				document.forms[0].anyo_fecha_alberguista[i].selected = true;
				break;
			}
		}
		var fecha_exp = f_exp.split("-");
		for(var i=0;i<document.forms[0].dia_fecha_expedicion.length;i++){
			if(document.forms[0].dia_fecha_expedicion[i].value == fecha_exp[2]){
				document.forms[0].dia_fecha_expedicion[i].selected = true;
				break;
			}
		}		
		for(var i=0;i<document.forms[0].mes_fecha_expedicion.length;i++){
			if(document.forms[0].mes_fecha_expedicion[i].value == fecha_exp[1]){
				document.forms[0].mes_fecha_expedicion[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].anyo_fecha_expedicion.length;i++){
			if(document.forms[0].anyo_fecha_expedicion[i].value == fecha_exp[0]){
				document.forms[0].anyo_fecha_expedicion[i].selected = true;
				break;
			}
		}
	}

//funcion que establece la longitud máxima del campo dni en función del tipo de documento que se seleccione
	function documentacion(formu){
	    var Num_Paises;
		var selected_php = '<?php echo $_POST['pais_alberguista']?>';
		if (document.forms[formu].pais_alberguista.length > 0){
		   var selected_js = document.forms[formu].pais_alberguista.options[document.forms[formu].pais_alberguista.selectedIndex].value;
		}else{
			 var selected_js = 0;
		}
	
	    <?			 
			  $res = mysql_query("SELECT * FROM `pais` order by Nombre_Pais");
			  // Array con los paises con es válido el tipo de documento <<I>>.
			  $num =mysql_num_rows($res);	 
			  echo"Num_Paises=".$num.";";
		?>
		if ( (document.forms[formu].tipo_documentacion.value=="P") )
		{		   
		    
			document.forms[formu].pais_alberguista.options.length = 0;
		     //alert("tam "+document.forms[formu].pais_alberguista.options.length);
			<?			 
			  $res = mysql_query("SELECT * FROM pais order by Nombre_Pais asc");
			  $num = mysql_num_rows($res);
			  
			  for ($i=0;$i<$num;$i++)
			  {
			    $fila = mysql_fetch_array($res);	
				//if ($fila['Carta_Europea']=="N")	
				if(strlen($fila['Nombre_Pais'])<25)
				{	
				    $pais = $fila['Nombre_Pais'];					
				}
				else 
				{
					$pais = substr($fila['Nombre_Pais'],0,22)."...";				
				}
				echo"document.forms[formu].pais_alberguista.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
				if ($fila['Id_Pais']=='ES')  echo "document.forms[formu].pais_alberguista.selectedIndex =".$i." ;";
				//echo "document.forms[formu].pais_alberguista.options[".$i."] = null;";
							 
			  }
			  ?>
			  if (document.forms[formu].cp_alberguista.disabled)
			  {
				//procedemos a activar los campos relacionados
				document.forms[formu].cp_alberguista.disabled = false;
				document.forms[formu].localidad_alberguista.disabled = false;
				document.forms[formu].provincia_alberguista.disabled = false;
				document.forms[formu].direccion_alberguista.disabled = false;
			  }					
		}
		
	    // sI el tipo de documento es Carnet de conducir o dni, bloqueamos el select de pais dejando predeterminado España.
	    if  ( (document.forms[formu].tipo_documentacion.value=="C") || (document.forms[formu].tipo_documentacion.value == "D") ) 
	    	{	    	
	    	  document.forms[formu].dni_alberguista.value = document.forms[formu].dni_alberguista.value.substr(0,9);
			  document.forms[formu].dni_alberguista.setAttribute('maxlength', 9);	    	
	    	 	   
	    	  document.forms[formu].pais_alberguista.options.length = 0;//alert("tam "+document.forms[formu].pais_alberguista.options.length);
			 <?php		 
			  $res = mysql_query("SELECT * FROM pais WHERE Id_Pais='ES' order by Nombre_Pais asc");
			  $num = mysql_num_rows($res);
			  
			  for ($i=0;$i<$num;$i++)
			  {
			    $fila = mysql_fetch_array($res);	
				//if ($fila['Carta_Europea']=="N")	
				if(strlen($fila['Nombre_Pais'])<25)
				{	
				    $pais = $fila['Nombre_Pais'];					
				}
				else 
				{
					$pais = substr($fila['Nombre_Pais'],0,22)."...";				
				}
				echo"document.forms[formu].pais_alberguista.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
							 
			  }
			 			 
			 ?>	
			 
			 if (document.forms[formu].cp_alberguista.disabled)
			 {
				//procedemos a activar los campos relacionados
				document.forms[formu].cp_alberguista.disabled = false;
				document.forms[formu].localidad_alberguista.disabled = false;
				document.forms[formu].provincia_alberguista.disabled = false;
				document.forms[formu].direccion_alberguista.disabled = false;
			 }			  
			}
		else 
		{
		    
		     document.forms[formu].dni_alberguista.setAttribute('maxlength', 30);			
		}	
		if (document.forms[formu].tipo_documentacion.value=="I")
		{
		 	
		    document.forms[formu].pais_alberguista.options.length = 0;		     
			<?			 
			  $res = mysql_query("SELECT * FROM pais WHERE Carta_Europea ='S' AND Id_Pais<>'ES' order by Nombre_Pais asc ");
			  $num = mysql_num_rows($res);
			  
			  for ($i=0;$i<$num;$i++)
			  {
			    $fila = mysql_fetch_array($res);	
				if(strlen($fila['Nombre_Pais'])<25)
				{	
				    $pais = $fila['Nombre_Pais'];					
				}
				else 
				{
					$pais = substr($fila['Nombre_Pais'],0,22)."...";				
				}
				echo"document.forms[formu].pais_alberguista.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";								 
			  }
			
			?>	
			 
			//procedemos a vaciar los campos relacionados
			document.forms[formu].cp_alberguista.value = "";
			document.forms[formu].localidad_alberguista.value = "";
			document.forms[formu].provincia_alberguista.value = "";
			document.forms[formu].direccion_alberguista.value = "";
			//procedemos a desactivar los campos relacionados
			document.forms[formu].cp_alberguista.disabled = true;
			document.forms[formu].localidad_alberguista.disabled = true;
			document.forms[formu].provincia_alberguista.disabled = true;
			document.forms[formu].direccion_alberguista.disabled = true;			
		}
		
		else if  ( (document.forms[formu].tipo_documentacion.value=="N") || (document.forms[formu].tipo_documentacion.value=="X")     )
		{	
		   
		    //en este caso el pais seleccionado no puede ser españa
		    document.forms[formu].pais_alberguista.options.length = 0;
		     //alert("tam "+document.forms[formu].pais_alberguista.options.length);
			<?			 
			  $res = mysql_query("SELECT * FROM pais WHERE Id_Pais<>'ES' order by Nombre_Pais asc ");
			  $num = mysql_num_rows($res);
			  
			  for ($i=0;$i<$num;$i++)
			  {
			    $fila = mysql_fetch_array($res);	
				if(strlen($fila['Nombre_Pais'])<25)
				{	
				    $pais = $fila['Nombre_Pais'];					
				}
				else 
				{
					$pais = substr($fila['Nombre_Pais'],0,22)."...";				
				}
				echo"document.forms[formu].pais_alberguista.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";	
							 
			  }
			  			  
			  
			?>  
			//procedemos a vaciar los campos relacionados
			document.forms[formu].cp_alberguista.value = "";
			document.forms[formu].localidad_alberguista.value = "";
			document.forms[formu].provincia_alberguista.value = "";
			document.forms[formu].direccion_alberguista.value = "";
			//procedemos a desactivar los campos relacionados
			document.forms[formu].cp_alberguista.disabled = true;
			document.forms[formu].localidad_alberguista.disabled = true;
			document.forms[formu].provincia_alberguista.disabled = true;
			document.forms[formu].direccion_alberguista.disabled = true;
			
		} 	
		
		poner_pais(formu,selected_js);

	}

	//AL: Funcion que selecciona el pais que estaba seleccionado
	function poner_pais(formu, valor){
		if (<?php if (isset($_POST['pais_alberguista']) && $_POST['pais_alberguista'] != ""){echo "true";}else{echo "false";}?>){
			var valor_final = '<?php echo $_POST['pais_alberguista'];?>';
		}else{
			var valor_final = valor;
		}
		for (var i=0;i<document.forms[formu].pais_alberguista.length;i++){
			if (document.forms[formu].pais_alberguista.options[i].value == valor_final){
			   document.forms[formu].pais_alberguista.options[i].selected = true;
			}
		}
	}
		
	function prepara_cambiar_dia(){
		document.forms[0].ant_dia_fecha_llegada.value = parseInt(document.forms[0].dia_fecha_llegada_alberguista.value)-1;
		document.forms[0].ant_mes_fecha_llegada.value = parseInt(document.forms[0].mes_fecha_llegada_alberguista.value)-1;
		document.forms[0].ant_anyo_fecha_llegada.value = parseInt(document.forms[0].anyo_fecha_llegada_alberguista.value)-1;
	}

	//Función que cambia el dia del calendario de la cabecera.
	function cambiar_dia(dia,mes, anio,op){		
		var fecha_calen =  dia+"-"+mes+"-"+anio;
		if(op != 0){
			if(document.forms[0].sub.value == "true" || document.forms[0].sub.value == "false"){
				if(document.forms[0].estancia_parcial.value != 1){
					if(document.forms[0].dia_fecha_llegada_alberguista.value+"-"+document.forms[0].mes_fecha_llegada_alberguista.value+"-"+document.forms[0].anyo_fecha_llegada_alberguista.value == document.forms[0].fecha_cal.value){
						if(document.forms[0].id_habitacion.value == ""){
							alert('Debe asignar una habitacion para el primer dia');
							return;
						}
					}
					var fecha_calen =  dia+"-"+mes+"-"+anio;
					document.forms[0].sub_habitaciones.value="true";
					document.forms[0].sub.value = "true";
					document.forms[0].fecha_cal.value = fecha_calen;
					document.forms[0].submit();
				}else{
					if(document.forms[0].id_habitacion.value == ""){
						alert('Debe asignar una habitacion');
					}else{
						var fecha_calen =  dia+"-"+mes+"-"+anio;
						document.forms[0].sub_habitaciones.value="true";
						document.forms[0].sub.value = "true";
						document.forms[0].fecha_cal.value = fecha_calen;
						document.forms[0].submit();
					}
				}
			}
			if(document.forms[0].ant_dia_fecha_llegada.value != document.forms[0].dia_fecha_llegada_alberguista.value || document.forms[0].ant_mes_fecha_llegada.value != document.forms[0].mes_fecha_llegada_alberguista.value || document.forms[0].ant_anyo_fecha_llegada.value != document.forms[0].anyo_fecha_llegada_alberguista.value){	
				//Parche nº 14123512351435345 para ke el calendario no parta las estancias
				if (op == 1){
					document.forms[0].sub_habitaciones.value='';
				}
				document.forms[0].fecha_cal.value = fecha_calen;
				document.forms[0].submit();
			}
		}else{
			var fecha_calen =  dia+"-"+mes+"-"+anio;
			document.forms[0].sub_habitaciones.value="true";
			document.forms[0].sub.value = "true";
			document.forms[0].fecha_cal.value = fecha_calen;
			document.forms[0].submit();
		}
	}

	//asignación de una cama ocupada temporalmente. Asigna esa habitación y cambia la fecha de calendario al día en que esa habitacion está ocupada
	function asignacion_parcial(fecha,id){
		var id_split = id.split("-");
		var fecha_split = fecha.split("-");
		document.forms[0].action=document.forms[0].action+'&estancia_parcial=1';
		document.forms[0].sub.value="true";
		asigna_habitacion(id_split[0],id_split[1],id_split[2],id_split[3],1);
		cambiar_dia(fecha_split[0],fecha_split[1],fecha_split[2],0);
	}

	function limpiar_formulario_existe(){
		document.forms[0].existe.value="false";
		document.forms[0].tipo_documentacion[0].selected = true;
		document.forms[0].nombre_alberguista.value="";
		document.forms[0].apellido1_alberguista.value="";
		document.forms[0].apellido2_alberguista.value="";
		paises(0);
		documentacion(0);
		document.forms[0].dia_fecha_alberguista[0].selected = true;
		document.forms[0].mes_fecha_alberguista[0].selected = true;
		document.forms[0].anyo_fecha_alberguista[0].selected = true;
		document.forms[0].dia_fecha_expedicion[0].selected = true;
		document.forms[0].mes_fecha_expedicion[0].selected = true;
		document.forms[0].anyo_fecha_expedicion[0].selected = true;
		document.forms[0].localidad_alberguista.value="";
		document.forms[0].direccion_alberguista.value="";
		document.forms[0].cp_alberguista.value="";
		document.forms[0].telefono_alberguista.value="";
		document.forms[0].email_alberguista.value="";
		document.forms[0].telefono_alberguista.value="";
		document.forms[0].observaciones_alberguista.value="";
	}
	//(Validar reserva) Completa el formulario con los datos de la reserva
	function completa_alta_reserva(fecha_l,hab,ingreso,pernocta){
		var fecha_llegada = fecha_l.split("-");
		//document.forms[0].ingreso_alberguista.value=ingreso;
		document.forms[0].pernocta_alberguista.value=pernocta;
	}
	
	//Esta función muestra o oculta el la capa de la leyenda de 'distribución de habitaciones' segun el parámetro 'act'
	function ver_leyenda(act){
		if(act == '1')
			document.getElementById('leyenda').style.visibility='hidden';
		else
			document.getElementById('leyenda').style.visibility='visible';
	}

	</script>
	<?php
		//inicializamos variable boolean a false. Esta variable en caso de tener valor true hará cambiar la fecha del calendario
		$sw_calendario =false;

		//conexión a la base de datos	
		// Cuidado con el orden!!.
		@$db2 = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
		@$db  = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);

		// Base de datos con metadata de todas las bases de datos, de aqui obtendremos el default de algunas tablas.
		@mysql_select_db("information_schema",$db2);
		@mysql_select_db($_SESSION['conexion']['db'],$db);		
		//En caso de no poder realizar la conexión se muestra un mensaje y se vuelve a la página principal
		if ( (!$db) || (!$db2) ){
			echo "<script>Alert('No se ha podido realizar la conexion con la base de datos');location.href=\"index.php\";</script>";
		}else{	
			//Una vez realizada la conexión, se selecciona la base de datos sam
			mysql_select_db($_SESSION['conexion']['db']);			


			//Asignacion de fecha de calendario 
			//En caso de que sea enviada por URL	
			if(isset($_GET['fecha_cal']) && trim($_GET['fecha_cal']) != ""){
				//echo "recibo GET[fecha_cal]";
				$split_cal = split("-",$_GET['fecha_cal']);
				if(strlen($split_cal[0]) == 1 && intval($split_cal[0])<10){					
					$split_cal[0] = "0".$split_cal[0]; 
				}
				if(strlen($split_cal[1]) == 1 && intval($split_cal[1])<10){
					$split_cal[1] = "0".$split_cal[1];
				}
				$fecha_calendario = trim($split_cal[2])."-".trim($split_cal[1])."-".trim($split_cal[0]);	 
				//en caso de que sea enviada mediante formulario
			}else if(isset($_POST['fecha_cal']) && trim($_POST['fecha_cal']) != ""){
				$split_cal = split("-",$_POST['fecha_cal']);				
				if(strlen($split_cal[0]) == 1 && intval($split_cal[0])<10){
						$split_cal[0] = "0".$split_cal[0];
				}
				if(strlen($split_cal[1]) == 1 && intval($split_cal[1])<10){
						$split_cal[1] = "0".$split_cal[1];
				}
				$fecha_calendario = trim($split_cal[2])."-".trim($split_cal[1])."-".trim($split_cal[0]);	 
				//Si no se ha enviado de ninguna de las dos maneras anteriores, y en caso de que se hayan enviado por formulario los valores de la fecha de llegada, se usará ésta como fecha de calendario.
			}else if(isset($_POST['dia_fecha_llegada_alberguista']) && $_POST['dia_fecha_llegada_alberguista']!="" && isset($_POST['mes_fecha_llegada_alberguista']) && $_POST['mes_fecha_llegada_alberguista']!="" && isset($_POST['anyo_fecha_llegada_alberguista']) && $_POST['anyo_fecha_llegada_alberguista']!=""){
					//echo "recibo _POST['anyo_fecha_llegada_alberguista']";
					$fecha_calendario = trim($_POST['anyo_fecha_llegada_alberguista'])."-".trim($_POST['mes_fecha_llegada_alberguista'])."-".trim($_POST['dia_fecha_llegada_alberguista']);					
			}else{
				//Si no se diera ninguno de los casos anteriores, se le asignará la fecha de sistema

				if (!isset($_SESSION['reserva']) && $_SESSION['reserva']['sw_reserva']!=true){$fecha_calendario = date("Y-m-d");}
			}
			//Se comprueba que la fecha de calendario no sea menor que la fecha de llegada de la pernocta, si es asi, activaremos $sw_calendario
			if(resta_fecha(trim($_POST['anyo_fecha_llegada_alberguista'])."-".trim($_POST['mes_fecha_llegada_alberguista'])."-".trim($_POST['dia_fecha_llegada_alberguista']),$fecha_calendario) <0 ){
				$sw_calendario = true;
			}

			//Fin Asignacion de fecha de calendario 

			//Se asignan valores a distintas variables de sesión
				
			$sw_session_asigna  = false;
			if(isset($_POST['sub_habitaciones']) && $_POST['sub_habitaciones'] == "true"){				
				if(count($_SESSION['alb']['estancia']['habitaciones']) > 0 && (trim($_SESSION['alb']['estancia']['fecha_llegada']) != trim($_POST['anyo_fecha_llegada_alberguista'])."-".trim($_POST['mes_fecha_llegada_alberguista'])."-".trim($_POST['dia_fecha_llegada_alberguista']) || intval($_POST['pernocta_alberguista']) != intval(resta_fecha(trim($_SESSION['alb']['estancia']['fecha_llegada']),trim($_SESSION['alb']['estancia']['fecha_salida']))))){					
					$_SESSION['alb'] = Array();
					$_POST['id_habitacion'] ="";

				}
				//se guarda la fecha de llegada en una variable de sesion
				$_SESSION['alb']['estancia']['fecha_llegada'] = $_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'];
				if((isset($_GET['fecha_cal']) && $_GET['fecha_cal'] != "") || (isset($_POST['fecha_cal']) && $_POST['fecha_cal'] != "")){
					$resta_pernocta = intval(resta_fecha($_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'],$fecha_calendario));					
					if(intval(trim($_POST['pernocta_alberguista'])) <= $resta_pernocta || $resta_pernocta < 0){	
						$fecha_fin = split("-",establecer_fecha($_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'],$_POST['pernocta_alberguista']+1));												
						$fecha_calendario = trim($_POST['anyo_fecha_llegada_alberguista'])."-".trim($_POST['mes_fecha_llegada_alberguista'])."-".trim($_POST['dia_fecha_llegada_alberguista']);
						$sw_calendario = true;
					}
				}

				if (isset($fecha_calendario) && $fecha_calendario != "" ){					
					$resto = resta_fecha($_SESSION['alb']['estancia']['fecha_llegada'] , $fecha_calendario);
				}else{
					$resto = 0;
				}
				$_SESSION['alb']['estancia']['fecha_salida'] = establecer_fecha($_SESSION['alb']['estancia']['fecha_llegada'],$_POST['pernocta_alberguista']);
				$resta = resta_fecha($_SESSION['alb']['estancia']['fecha_llegada'] , $_POST['ant_fecha_calendario']);
				
				$sw_indice = false;
				$_POST['id_habitacion'] = trim($_POST['id_habitacion']);		
				//Se guardan las distintas pernoctas  provisionales en variables de sesion
				if($_POST['id_habitacion'] != ""){					
					if(count($_SESSION['alb']['estancia']['habitaciones']) == 0){
						if(!$sw_session_asigna){
							$_SESSION['alb']['estancia']['indice']= 0;
							$_SESSION['alb']['estancia']['habitaciones'][$_SESSION['alb']['estancia']['indice']]['fecha'] = trim($_POST['ant_fecha_calendario']);
							$_SESSION['alb']['estancia']['habitaciones'][$_SESSION['alb']['estancia']['indice']]['id'] = trim($_POST['id_habitacion']);
							$sw_session_asigna = true;
						}
					}else{ 
						for($indice = 0;$indice < count($_SESSION['alb']['estancia']['habitaciones']);$indice++){
							if(trim($_SESSION['alb']['estancia']['habitaciones'][$indice]['fecha']) == trim($_POST['ant_fecha_calendario'])){
								$sw_indice = true;								
								break;
							}
						}
					}
					if($sw_indice){
						if(!$sw_session_asigna){
							$_SESSION['alb']['estancia']['habitaciones'][$indice]['fecha'] = trim($_POST['ant_fecha_calendario']);
							$_SESSION['alb']['estancia']['habitaciones'][$indice]['id'] = trim($_POST['id_habitacion']);
							if($_GET['estancia_parcial'] == 1){
								$_SESSION['alb']['estancia']['habitaciones'][$indice]['parcial'] = 1;
							}else{
								$_SESSION['alb']['estancia']['habitaciones'][$indice]['parcial'] = 0;
							}
						}
					}else{
						if(count($_SESSION['alb']['estancia']['habitaciones'])>$_SESSION['alb']['estancia']['indice']){
							$_SESSION['alb']['estancia']['indice']++;
						}	
						$sw_orden = false;
						if(!$sw_session_asigna){
							for($orden_fecha=0;$orden_fecha<count($_SESSION['alb']['estancia']['habitaciones']);$orden_fecha++){
								if(resta_fecha($_SESSION['alb']['estancia']['habitaciones'][$orden_fecha]['fecha'],$_POST['ant_fecha_calendario'])<0){
									for($ordena=$_SESSION['alb']['estancia']['indice'];$ordena > $orden_fecha ;$ordena--){
										$_SESSION['alb']['estancia']['habitaciones'][$ordena]['fecha'] = trim($_SESSION['alb']['estancia']['habitaciones'][$ordena-1]['fecha']);			
										$_SESSION['alb']['estancia']['habitaciones'][$ordena]['id'] = trim($_SESSION['alb']['estancia']['habitaciones'][$ordena-1]['id']);
										$_SESSION['alb']['estancia']['habitaciones'][$ordena]['parcial'] = trim($_SESSION['alb']['estancia']['habitaciones'][$ordena-1]['parcial']);
									}
									$sw_orden = true;
									$_SESSION['alb']['estancia']['habitaciones'][$orden_fecha]['fecha'] = trim($_POST['ant_fecha_calendario']);
									$_SESSION['alb']['estancia']['habitaciones'][$orden_fecha]['id'] = trim($_POST['id_habitacion']);
									$sw_session_asigna = true;
									break;
								}
							}
							if(!$sw_orden){
								$_SESSION['alb']['estancia']['habitaciones'][$_SESSION['alb']['estancia']['indice']]['fecha'] = $_POST['ant_fecha_calendario'];
								$_SESSION['alb']['estancia']['habitaciones'][$_SESSION['alb']['estancia']['indice']]['id'] = $_POST['id_habitacion'];
								if($_GET['estancia_parcial'] == 1){
									$_SESSION['alb']['estancia']['habitaciones'][$_SESSION['alb']['estancia']['indice']]['parcial'] = 1;
								}
								$sw_session_asigna = true;
							}				
						}
					}													
				}else{			
					if(resta_fecha(trim($_POST['ant_fecha_calendario']),trim($_SESSION['alb']['estancia']['fecha_llegada'])) <=0 && resta_fecha(trim($_POST['ant_fecha_calendario']),trim($_SESSION['alb']['estancia']['fecha_salida'])) > 0){
						for($indice = 0;$indice<count($_SESSION['alb']['estancia']['habitaciones']);$indice++){
							if(trim($_SESSION['alb']['estancia']['habitaciones'][$indice]['fecha']) == trim($_POST['ant_fecha_calendario'])){
								$sw_indice = true;								
								break;
							}
						}
						if($sw_indice){							
							array_splice($_SESSION['alb']['estancia']['habitaciones'],$indice,1);
						}
					}
				}				
			}
		}
		$num_pernoctas = count($_SESSION['alb']['estancia']['habitaciones']);
		$split_fecha_cal = split("-",$fecha_calendario);

		//Si la estancia anterior ha sido una estancia parcial, se muestra un mensaje indicando que debe seleccionar otra habitación
		if(isset($_GET['estancia_parcial']) && intval($_GET['estancia_parcial']) == 1){
			echo "<script>alert('Debe seleccionar otra habitacion para poder continuar la estancia');</script>";
		}
			//Altas / Bajas / Modificaciones en la base de datos     Construccion y ejecución de Consultas

		////////////////////////////////////////////////////////////////////////////////
		//Se comprueba que se haya realizado una petición de alta, modificación o baja//
		////////////////////////////////////////////////////////////////////////////////
		if(isset($_POST['accion']) && $_POST['accion']!=""){
			//Se prepara la fecha en el formato adecuado para introducirla en la base de datos	
			
			$dni = trim($_POST['dni_alberguista']);
			if((strtolower(trim($_POST['accion'])) == "alta" || strtolower(trim($_POST['accion']))=="reserva") && isset($_POST['sub']) && strtolower(trim($_POST['sub'])) == "false") {
				$fecha_llegada_al = trim($_POST['anyo_fecha_llegada_alberguista'])."-".trim($_POST['mes_fecha_llegada_alberguista'])."-".trim($_POST['dia_fecha_llegada_alberguista']);
				$fecha_salida_al = mktime(0,0,0,trim($_POST['mes_fecha_llegada_alberguista']),trim($_POST['dia_fecha_llegada_alberguista'])+$_POST['pernocta_alberguista'],trim($_POST['anyo_fecha_llegada_alberguista']));
				$fecha_salida_al = strftime("%Y-%m-%d",$fecha_salida_al);

				//Pequeña comprobación de que el alberguista no este haciendo ya una pernocta (AL)
				$sql_comp_al = "
					SELECT DNI_Cl, Fecha_Llegada, Fecha_Salida FROM pernocta WHERE
						(pernocta.DNI_Cl='".$_POST['dni_alberguista']."')	 AND (
							(pernocta.Fecha_Llegada >= '".$fecha_llegada_al."' AND pernocta.Fecha_Llegada < '".$fecha_salida_al."') 
							OR
							(pernocta.Fecha_Salida > '".$fecha_llegada_al."' AND pernocta.Fecha_Salida <= '".$fecha_salida_al."') 
							OR 
							(pernocta.Fecha_Llegada >= '".$fecha_llegada_al."' AND pernocta.Fecha_Salida <= '".$fecha_salida_al."')
						)
					UNION
					SELECT DNI_Cl, Fecha_Llegada, Fecha_Salida FROM pernocta_p WHERE
						(pernocta_p.DNI_Cl='".$_POST['dni_alberguista']."' AND pernocta_p.Fecha_Llegada='".$fecha_llegada_al."')
					
				";
				$res_comp_al = mysql_query($sql_comp_al);
				if ($fila_comp_al = mysql_fetch_array($res_comp_al)){
					echo "<script>alert (\"No se ha podido realizar la pernocta.El cliente introducido ya está realizando una pernocta en la fecha seleccionada\");window.location.href='?pag=alberguistas.php';</script>";	
					exit();
				}

				// ALTA DE ALBERGUISTA --- ALTA DE ALBERGUISTA 
				// ALTA DE ALBERGUISTA --- ALTA DE ALBERGUISTA
				if (strtolower(trim($_POST['accion'])) == "alta"){
					
					$fecha_nacimiento = $_POST['anyo_fecha_alberguista']."-".$_POST['mes_fecha_alberguista']."-".$_POST['dia_fecha_alberguista'];
					$fecha_llegada = $_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'];
					$fecha_salida = establecer_fecha($fecha_llegada, $_POST['pernocta_alberguista']);
					$_POST['pernocta_alberguista'] = intval($_POST['pernocta_alberguista']);	
					//Se comprueba si el alberguista existía en la base de datos	
					if(isset($_POST['existe']) && $_POST['existe'] == "true" && $_SESSION['reserva']['sw_reserva'] != "true"){	
						//echo "existe?¿? anda ya! $_POST[existe] = ".$_POST['existe'];
						//Se realiza una modificación del registro con los datos del formulario, por si ha realizado modificaciones en sus datos.
						$sql_cliente = "update cliente set Nombre_Cl = '".$_POST['nombre_alberguista']."' , Apellido1_Cl = '".$_POST['apellido1_alberguista']."' , Apellido2_Cl = '".$_POST['apellido2_alberguista']."' , Direccion_Cl = '".$_POST['direccion_alberguista']."' , CP_Cl ='".$_POST['cp_alberguista']."' , Localidad_Cl = '".$_POST['localidad_alberguista']."' , Id_Pro = ";
						if($_POST['provincia_alberguista'] == ""){
							$sql_cliente .= "NULL , ";
						}else{
							$sql_cliente .= "'".$_POST['provincia_alberguista']."' , ";
						}
						$sql_cliente .= "Id_Pais = '".$_POST['pais_alberguista']."' , Fecha_Nacimiento_Cl = '".$fecha_nacimiento."' , Sexo_Cl = '".$_POST['sexo_alberguista']."' , Tfno_Cl = '".$_POST['telefono_alberguista']."' , Observaciones_Cl = '".$_POST['observaciones_alberguista']."' , Email_Cl = '".$_POST['email_alberguista']."', Tipo_documentacion='".$_POST['tipo_documentacion']."', Fecha_Expedicion='".$_POST['anyo_fecha_expedicion']."-".$_POST['mes_fecha_expedicion']."-".$_POST['dia_fecha_expedicion']."'  where DNI_Cl like '".$_POST['dni_ant']."'";							
					}else{
						$sql_cliente = "insert into cliente (DNI_Cl , Nombre_Cl, Apellido1_Cl , Apellido2_Cl , Direccion_Cl , CP_Cl , Localidad_Cl , Id_Pro , Id_Pais , Fecha_Nacimiento_Cl , Sexo_Cl , Tfno_Cl , Observaciones_Cl , Email_Cl,Tipo_documentacion , Fecha_Expedicion) values ('".$dni."' ,'".$_POST['nombre_alberguista']."' , '".$_POST['apellido1_alberguista']."' , '".$_POST['apellido2_alberguista']."' ,'".$_POST['direccion_alberguista']."' , '".$_POST['cp_alberguista']."' ,'".$_POST['localidad_alberguista']."' , ";
						if($_POST['provincia_alberguista'] == ""){
							$sql_cliente .= "NULL , ";
						}else{
							$sql_cliente .= "'".$_POST['provincia_alberguista']."' , ";
						}
						$sql_cliente .= "'".$_POST['pais_alberguista']."' , '".$fecha_nacimiento."' ,  '".$_POST['sexo_alberguista']."' , '".$_POST['telefono_alberguista']."' , '".$_POST['observaciones_alberguista']."' , '".$_POST['email_alberguista']."' , '".$_POST['tipo_documentacion']."' ,  '".$_POST['anyo_fecha_expedicion']."-".$_POST['mes_fecha_expedicion']."-".$_POST['dia_fecha_expedicion']."');";						
					}
				}
				
				if(mysql_query($sql_cliente) && $_SESSION['reserva']['sw_reserva'] != "true"){
					//Si el número de pernoctas es mayor que 1, Deberá realizarse por cada pernocta una inserción
					if($num_pernoctas >1){													
						for($i=0;$i<$num_pernoctas;$i++){
							$fecha_llegada_p = $_SESSION['alb']['estancia']['habitaciones'][$i]['fecha'];
							if($i<($num_pernoctas-1)){
								$_SESSION['alb']['estancia']['habitaciones'][$i]['pernocta'] = resta_fecha($_SESSION['alb']['estancia']['habitaciones'][$i]['fecha'],$_SESSION['alb']['estancia']['habitaciones'][$i+1]['fecha']);
							}else{
								$_SESSION['alb']['estancia']['habitaciones'][$i]['pernocta'] = resta_fecha($_SESSION['alb']['estancia']['habitaciones'][$i]['fecha'],$fecha_salida);
							}
							$fecha_salida_pernocta = establecer_fecha($_SESSION['alb']['estancia']['habitaciones'][$i]['fecha'],$_SESSION['alb']['estancia']['habitaciones'][$i]['pernocta']); 

							$sql_estancia[$i]= "insert into pernocta (DNI_Cl , Id_Hab , Fecha_Llegada , Fecha_Salida , PerNocta , Noches_Pagadas , Ingreso";
							if(isset($_POST['servicio_alberguista']) && trim($_POST['servicio_alberguista']) != ""){
								$sql_estancia[$i].= ", Id_Servicios";
							}								
							$sql_estancia[$i].=") values ('".$dni."' , '".$_SESSION['alb']['estancia']['habitaciones'][$i]['id']."', '".$fecha_llegada_p."' , '".$fecha_salida_pernocta."' , ".$_SESSION['alb']['estancia']['habitaciones'][$i]['pernocta']." , ".$_POST['noches_pagadas'].", ".$_POST['ingreso_alberguista']."";
							if(isset($_POST['servicio_alberguista']) && trim($_POST['servicio_alberguista']) != ""){
								$sql_estancia[$i] .= ", '".trim($_POST['servicio_alberguista'])."'";
							}
							 $sql_estancia[$i] .= ");";								
							if(!mysql_query($sql_estancia[$i])){
								echo "<script>alert('No se ha podido registrar la estancia con éxito. Compruebe que los datos son correctos o que el cliente no se encuentra realizando una estancia actualmente.');</script>";
								exit;
							}								
						}
					}else{
						$sql_estancia = "insert into pernocta (DNI_Cl , Id_Hab , Fecha_Llegada , Fecha_Salida , PerNocta , Noches_Pagadas , Ingreso";
						if(isset($_POST['servicio_alberguista']) && trim($_POST['servicio_alberguista']) != ""){
							$sql_estancia.= ", Id_Servicios";
						}																						
						$sql_estancia.= ") values ('".$dni."' , '".$_POST['id_habitacion']."', '".$fecha_llegada."' , '".$fecha_salida."' , ".$_POST['pernocta_alberguista']." , ".$_POST['noches_pagadas'].", ".$_POST['ingreso_alberguista']."";
						if(isset($_POST['servicio_alberguista']) && trim($_POST['servicio_alberguista']) != ""){
							$sql_estancia .= ", '".trim($_POST['servicio_alberguista'])."'";
						}
						$sql_estancia .= ");";	
						
						if(!mysql_query($sql_estancia)){
							echo "<script>alert('No se ha podido registrar la estancia con éxito. Compruebe que los datos son correctos o que el cliente no se encuentra realizando una estancia actualmente.');</script>";
							exit;
						}else{
							echo "<script> window.location.href='?pag=alberguistas.php'; </script>";
						}
					}
				}
				//echo "reserva: ".$_SESSION['reserva']['sw_reserva']."<br>";
				
				if (($_SESSION['reserva']['sw_reserva'] == "true") && (trim($_POST['accion']) == "reserva")){
					//ALBERTO!!**************************************************************************************
					/*//////////////////////////////////////////////////////////
					Voy a realizar la validacion de la reserva a mi manera :P //
					*///////////////////////////////////////////////////////////

					//echo "HOLA!!!!!!!!!!!! NO TENGO SENTIDO PERO ME LO PASO TETA CON LA BRAGETA<br> Es decir, entro en aplicacion de validacion de reserva (sw_reserva=true y POST[accion] == reserva)";
					$dni = $_POST['dni_alberguista'];
					$tipo_docu = $_POST['tipo_documentacion'];
					$fecha_expedicion = $_POST['anyo_fecha_expedicion']."-".$_POST['mes_fecha_expedicion']."-".$_POST['dia_fecha_expedicion'];
					$nombre = $_POST['nombre_alberguista'];
					$apellido1 = $_POST['apellido1_alberguista'];
					$apellido2 = $_POST['apellido2_alberguista'];
					$pais = $_POST['pais_alberguista'];
					$provincia = $_POST['provincia_alberguista'];
					$localidad = $_POST['localidad_alberguista'];
					$direccion = $_POST['direccion_alberguista'];
					$codigo_postal = $_POST['cp_alberguista'];
					$fecha_nacimiento = $_POST['anyo_fecha_alberguista']."-".$_POST['mes_fecha_alberguista']."-".$_POST['dia_fecha_alberguista'];
					$telefono = $_POST['telefono_alberguista'];
					$email = $_POST['email_alberguista'];
					$observaciones = $_POST['observaciones_alberguista'];
					$id_hab = $_POST['id_habitacion'];
					$pernocta = $_POST['pernocta_alberguista'];
					$fecha_llegada = $_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'];
					$fecha_salida = mktime(0, 0, 0, $_POST['mes_fecha_llegada_alberguista'], ($_POST['dia_fecha_llegada_alberguista']+$pernocta), $_POST['anyo_fecha_llegada_alberguista']);
					$fecha_salida = strftime("%Y-%m-%d", $fecha_salida);
					$nochea_pagadas = $_POST['noches_pagadas'];
					$ingreso = $_POST['ingreso_alberguista'];
					$servicio = $_POST['servicio_alberguista'];
					$sexo = $_POST['sexo_alberguista'];

					$verify_cli=mysql_query("SELECT * FROM cliente WHERE DNI_Cl = '".$dni."'");

					if (mysql_num_rows($verify_cli) > 0){
						$resultado_temp = mysql_fetch_array($verify_cli);
						$modificaciones = '';
						if ($resultado_temp['Nombre_Cl'] != $nombre){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Nombre_Cl = '".$nombre."'";
						}
						if ($resultado_temp['Apellido1_Cl'] != $apellido1){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Apellido1_Cl = '".$apellido1."'";
						}
						if ($resultado_temp['Apellido2_Cl'] != $apellido2){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Apellido2_Cl = '".$apellido2."'";
						}
						if ($resultado_temp['Direccion_Cl'] != $direccion){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Direccion_Cl = '".$direccion."'";
						}
						if ($resultado_temp['CP_Cl'] != $codigo_postal){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "CP_Cl = '".$codigo_postal."'";
						}
						if ($resultado_temp['Localidad_Cl'] != $localidad){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Localidad_Cl = '".$localidad."'";
						}
						if ($resultado_temp['Id_Pro'] != $provincia){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Id_Pro = '".$provincia."'";
						}
						if ($resultado_temp['Id_Pais'] != $pais){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Id_Pais = '".$pais."'";
						}
						if ($resultado_temp['Fecha_Nacimiento_Cl'] != $fecha_nacimiento){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Fecha_Nacimiento_Cl = '".$fecha_nacimiento."'";
						}
						if ($resultado_temp['Sexo_Cl'] != $sexo){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Sexo_Cl = '".$sexo."'";
						}
						if ($resultado_temp['Tfno_Cl'] != $telefono){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Tfno_Cl = '".$telefono."'";
						}
						if ($resultado_temp['Observaciones_Cl'] != $observaciones){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Observaciones_Cl = '".$observaciones."'";
						}
						if ($resultado_temp['Email_Cl'] != $email){
							if ($modificaciones != ''){$modificaciones += ', ';}
							$modificaciones += "Email_Cl = '".$email."'";
						}
						if ($modificaciones != ''){
							$sql_insert_cliente = "UPDATE cliente SET (".$modificaciones.") WHERE (DNI_Cl = '".$dni."' and Tipo_documentacion = '".$tipo_docu."')";
						}else{
							$sql_insert_cliente = '';
						}
					}else{
						$sql_insert_cliente = "INSERT INTO cliente (DNI_Cl, Nombre_Cl, Apellido1_Cl, Apellido2_Cl, Direccion_Cl, CP_Cl, Localidad_Cl, Id_Pro, Id_Pais, Fecha_Nacimiento_Cl, Sexo_Cl, Tfno_Cl, Observaciones_Cl, Email_Cl, Tipo_documentacion, Fecha_Expedicion) VALUES ('$dni', '$nombre', '$apellido1', '$apellido2', '$direccion', '$codigo_postal', '$localidad', '$provincia', '$pais', '$fecha_nacimiento', '$sexo', '$telefono', '$observaciones', '$email', '$tipo_docu', '$fecha_expedicion')";
						//echo "<br>No hay clientes con dni $dni <br> ";
					}
					
					//$sql_insert_pernocta = "INSERT INTO pernocta (DNI_Cl, Id_Hab, Fecha_Llegada, Fecha_Salida, PerNocta, Noches_Pagadas, Ingreso) VALUES ('$dni', '$id_hab', '$fecha_llegada', '$fecha_salida', '$pernocta', '$noches_pagadas', '$ingreso')";

					//echo "<br>Insert en pernocta: ".$sql_insert_pernocta;

					//INSERTS
					
					if ($sql_insert_cliente != ''){
						$res_insert_cliente = mysql_query($sql_insert_cliente);
					}
					$res_insert_pernocta = mysql_query($sql_insert_pernocta);
					
					for ($noches = 0; $noches < count($_SESSION['reserva']['noche']); $noches++){
						$sql_ins_det = "INSERT INTO pernocta (DNI_Cl, Id_Hab, Fecha_Llegada, Fecha_Salida, PerNocta, Noches_Pagadas, Ingreso) VALUES ('$dni', '".$_SESSION['reserva']['noche'][$noches]['id_hab']."', '".$_SESSION['reserva']['noche'][$noches]['fecha_llegada']."', '".$_SESSION['reserva']['noche'][$noches]['fecha_salida']."', '".$_SESSION['reserva']['noche'][$noches]['pernocta']."', '$noches_pagadas', '$ingreso')";
						$res_ins_det = mysql_query($sql_ins_det);
						$_SESSION['reserva']['noche'][$noches]['camas']--;
						if ($_SESSION['reserva']['noche'][$noches]['camas'] == 0){
							//Si las camas llegan a 0, borro el registro de la bd
							$sql_del_res = "DELETE FROM reserva WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['noche'][$noches]['fecha_llegada']."' AND Id_Hab = '".$_SESSION['reserva']['noche'][$noches]['id_hab']."') ";
						}else{
							//Si quedan camas, resto 1 y sigo
							$sql_del_res = "UPDATE reserva SET Num_Camas = '".$_SESSION['reserva']['noche'][$noches]['camas']."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['noche'][$noches]['fecha_llegada']."' AND Id_Hab = '".$_SESSION['reserva']['noche'][$noches]['id_hab']."')";
						}
						$res_del_res = mysql_query($sql_del_res);
						//para la primera noche, actualizamos el ingreso en ´detalles´
						if ($noches == 0){
							if ($ingreso >= $_SESSION['reserva']['ingreso_total']){$ingreso_temp = 0;$ingreso -= $_SESSION['reserva']['ingreso_total'];}
							else{$ingreso_temp = $_SESSION['reserva']['ingreso_total'] - $ingreso;}
							$sql_upd_det = "UPDATE detalles SET Ingreso = '".$ingreso_temp."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['noche'][$noches]['fecha_llegada']."')";
							$res_upd_det = mysql_query($sql_upd_det);
						}else{
							if ($ingreso >= $_SESSION['reserva']['ingreso_total']){$ingreso_temp = 0;$ingreso -= $_SESSION['reserva']['ingreso_total'];}
							else{$ingreso_temp = $_SESSION['reserva']['ingreso_total'] - $ingreso;}
							$sql_upd_det = "UPDATE detalles SET Ingreso = '".$ingreso_temp."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['noche'][$noches]['fecha_llegada']."')";
							$res_upd_det = mysql_query($sql_upd_det);
						}
						
						if ($_SESSION['reserva']['salir']){
							//Si entrar sigue siendo true, quiere decir que se ha validado la reserva entera, asi ke elimino 
							//los detalles de todas las reservas validadas
							$sql_del_det = " DELETE FROM detalles WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['noche'][$noches]['fecha_llegada']."')";
							$res_del_det = mysql_query($sql_del_det);
						}
					}
					if ($_SESSION['reserva']['salir']){
						//Si entrar sigue siendo true, quiere decir que se ha validado la reserva entera, asi ke elimino pra
						$sql_del_pra = " DELETE FROM pra WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."')";
						$res_del_pra = mysql_query($sql_del_pra);
						$_SESSION['reserva']['sw_reserva'] = false;
						unset($_SESSION['reserva']);
						//echo "<br> Se ha completado la validacion-> sw_reserva = ".$_SESSION['reserva']." dni_reserva=".$_SESSION['reserva']['dni'];
						$_SESSION['gdh']['detalles']['de_tipo'] = 'V';

					}
					unset ($_POST['sub']);
					echo "<script> window.location.href='?pag=gdh.php'; </script>";
					
/*
					for ($f=0;$f<count($_SESSION['reserva']['habs']);$f++){
						if ($_SESSION['reserva']['habs'][$f]['id_hab'] == $_SESSION['reserva']['hab_actual'] && $salir != true){
							$_SESSION['reserva']['habs'][$f]['camas']--;
							$salir = true;
						}
					}

					
					//Compruevo si se han realizado todas las altas
					$camas_restantes = 0;
					for ($i=0; $i<count($_SESSION['reserva']['habs']);$i++){
						if ($_SESSION['reserva']['habs'][$i]['camas'] > 0){$camas_restantes += $_SESSION['reserva']['habs'][$i]['camas'];}
						//echo "<BR> SESSION[reserva][id_hab] = ".$_SESSION['reserva']['habs'][$i]['id_hab'].", SESSION[reserva][camas] = ".$_SESSION['reserva']['habs'][$i]['camas'];
					}
					//echo "<br>Camas_restantes = ".$camas_restantes;
					if ($camas_restantes == 0){
						//si se han realizado, compruebo que no haya reservas seguidas
						$sql_veri_temp = "SELECT * FROM detalles WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_salida']."')";
						$res_veri_temp = mysql_query($sql_veri_temp);
						//echo "<br>".$sql_veri_temp." ===> ".mysql_num_rows($res_veri_temp);
						//Se eliminan los datos de la reserva en cuestion
						$del_det = mysql_query("DELETE FROM detalles WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."')");
						$del_res = mysql_query("DELETE FROM reserva WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."')");
						//echo "<br>DET = $del_det <br> RES = $del_res ";
						if (mysql_num_rows($res_veri_temp) > 0){
							//Si hay mas reservas seguidas, se sustituyen los datos de $_session['reserva']
							$tupla_veri_temp = mysql_fetch_array($res_veri_temp);
							$_SESSION['reserva']['fecha_llegada'] = $tupla_veri_temp['Fecha_Llegada'];
							$_SESSION['reserva']['fecha_salida'] = $tupla_veri_temp['Fecha_Salida'];
							$_SESSION['reserva']['pernocta'] = $tupla_veri_temp['PerNocta'];
							$sql_res_temp = mysql_query("SELECT * FROM reserva WHERE (DNI_PRA = '".$tupla_veri_temp['DNI_PRA']."' AND Fecha_Legada = '".$tupla_veri_temp['Fecha_Llegada']."')");
							//echo "SELECT * FROM reserva WHERE (DNI_PRA = '".$tupla_veri_temp['Fecha_Llegada']."' AND Fecha_Legada = '".$tupla_veri_temp['Fecha_Llegada']."')";
							unset($_SESSION['reserva']['habs']);
							for ($j=0;$j<mysql_num_rows($sql_res_temp);$j++){
								$tupla_res_temp = mysql_fetch_array($sql_res_temp);
								$_SESSION['reserva']['habs'][$j]['id_hab'] = $tupla_res_temp['Id_Hab'];
								$_SESSION['reserva']['habs'][$j]['camas'] = $tupla_res_temp['Num_Camas'];
								$_SESSION['reserva']['habs'][$j]['camas_tot'] = $tupla_res_temp['Num_Camas'];
							}

							// Como vamos a necesitar dar mas noches de alta, almaceno los datos que no esten en la base de datos de reservas
							$_SESSION['reserva']['tipo_documentacion'] = $_POST['tipo_documentacion'];
							$_SESSION['reserva']['dia_fecha_expedicion'] = $_POST['dia_fecha_expedicion'];
							$_SESSION['reserva']['mes_fecha_expedicion'] = $_POST['mes_fecha_expedicion'];
							$_SESSION['reserva']['anyo_fecha_expedicion'] = $_POST['anyo_fecha_expedicion'];
							$_SESSION['reserva']['dia_fecha_alberguista'] = $_POST['dia_fecha_alberguista'];
							$_SESSION['reserva']['mes_fecha_alberguista'] = $_POST['mes_fecha_alberguista'];
							$_SESSION['reserva']['anyo_fecha_alberguista'] = $_POST['anyo_fecha_alberguista'];
							$_SESSION['reserva']['pais_alberguista'] = $_POST['pais_alberguista'];
							$_SESSION['reserva']['localidad_alberguista'] = $_POST['localidad_alberguista'];
							$_SESSION['reserva']['direccion_alberguista'] = $_POST['direccion_alberguista'];
							$_SESSION['reserva']['cp_alberguista'] = $_POST['cp_alberguista'];
							$_SESSION['reserva']['sexo_alberguista'] = $_POST['sexo_alberguista'];
							$_SESSION['reserva']['observaciones_alberguista'] = $_POST['observaciones_alberguista'];
							$_SESSION['reserva']['servicio_alberguista'] = $_POST['servicio_alberguista'];
							$_SESSION['reserva']['dni'] = $_POST['dni_alberguista'];
							$_SESSION['reserva']['nombre'] = $_POST['nombre_alberguista'];
							$_SESSION['reserva']['apellido1'] = $_POST['apellido1_alberguista'];
							$_SESSION['reserva']['apellido2'] = $_POST['apellido2_alberguista'];
							$_SESSION['reserva']['telefono'] = $_POST['telefono_alberguista'];
							$_SESSION['reserva']['email'] = $_POST['email_alberguista'];


	// Introduzco en las variables de sesion de Jaime los datos necesarios para que el sistema salte a la validacion de la continuacion de la reserva
							$_SESSION['gdh']['detalles']['de_tipo'] = "R";
							$_SESSION['gdh']['detalles']['de_pagina'] = "V";
							$_SESSION['gdh']['detalles']['de_fecha_llegada'] = $_SESSION['reserva']['fecha_llegada'];
							//echo "<br>Cambios Realizados: fecha_llegada = ".$_SESSION['reserva']['fecha_llegada'];
							echo "<script>alert('Quedan noches por asignar');window.location.href='?pag=gdh.php';</script>";
							
						}else{
							//Si no hay mas reservas, hemos terminado. Eliminamos el registro de pra, la variable de sesion y enviamos al usuario a gdh
							$del_pra = mysql_query("DELETE FROM pra WHERE (DNI_PRA = '".$dni."')");
							unset($_SESSION['reserva']);
							//echo "<br> Se ha completado la validacion-> sw_reserva = ".$_SESSION['reserva']." dni_reserva=".$_SESSION['reserva']['dni'];
							$_SESSION['gdh']['detalles']['de_tipo'] = 'V';
							//echo "<script> alert('Se ha completado la validacion'); window.location.href='?pag=gdh.php';</script>";
							echo "<script> window.location.href='?pag=gdh.php';</script>";
						}	
					}else{
						//si quedan reservas por dar de alta, volvemos al formulario
						$salir = false;
						//echo "<br>Quedan camas por asignar: Numero de Habs de la reserva = ".count($_SESSION['reserva']['habs']);
						for ($z=0;$z<count($_SESSION['reserva']['habs']);$z++){
							//echo "<br>IF:".$_SESSION['reserva']['habs'][$z]['id_hab']." == ".$_SESSION['reserva']['hab_actual']." && ".$salir." != true";
							if (($_SESSION['reserva']['habs'][$z]['id_hab'] == $_SESSION['reserva']['hab_actual']) && $salir != true){
								//echo "<br> HAB_ACTUAL= ".$_SESSION['reserva']['hab_actual'];
								//echo "<br> SQL Resta de reservas: UPDATE reserva SET Num_Camas = '".$_SESSION['reserva']['habs'][$z]['camas']."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."' AND Id_Hab = '".$_SESSION['reserva']['hab_actual']."')";
								$ingreso_temp = intval($_SESSION['reserva']['ingreso_total']) - intval($_POST['ingreso_alberguista']);
								if ($_SESSION['reserva']['habs'][$z]['camas'] == 0){
									$qry_temp_res = mysql_query("DELETE FROM reserva WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."' AND Id_Hab = '".$_SESSION['reserva']['hab_actual']."')");
								}else{
									$qry_temp_res = mysql_query("UPDATE reserva SET Num_Camas = '".$_SESSION['reserva']['habs'][$z]['camas']."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."' AND Id_Hab = '".$_SESSION['reserva']['hab_actual']."')");
								}
								//echo "UPDATE reserva SET Num_Camas = '".$_SESSION['reserva']['habs'][$z]['camas']."', Ingreso = '".$ingreso_temp."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."' AND Id_Hab = '".$_SESSION['reserva']['hab_actual']."')";
								$qry_temp_det = mysql_query("UPDATE detalles SET Ingreso = '".$ingreso_temp."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."')");
								//echo "UPDATE detalles SET Ingreso = '".$ingreso_temp."' WHERE (DNI_PRA = '".$_SESSION['reserva']['dni']."' AND Fecha_Llegada = '".$_SESSION['reserva']['fecha_llegada']."')";
								$salir = true;
							}
						}
						if ($camas_restantes == 1){
							echo "<SCRIPT> alert ('Queda ".$camas_restantes." habitación por validar'); window.location.href='?pag=gdh.php'; </SCRIPT>";
						}else{
							echo "<script> alert ('Quedan ".$camas_restantes." habitaciones por validar'); window.location.href='?pag=gdh.php'; </script>";
						}
					}
*/
				}
				//Realizadas con éxito las operaciones, se envía a la página principal de alberguistas.
				echo "<script>location.href='?pag=alberguistas.php';</script>";
			}else{
				if($_POST['accion'] == "mod"){
					

				//  MODIFICACIÓN DE ALBERGUISTA
					$fecha = $_POST['anyo_fecha_alberguista']."-".$_POST['mes_fecha_alberguista']."-".$_POST['dia_fecha_alberguista'];
					$sql_update = "update cliente set DNI_Cl = '".$dni."' , Nombre_Cl = '".$_POST['nombre_alberguista']."', Apellido1_Cl='".$_POST['apellido1_alberguista']."',Apellido2_Cl='".$_POST['apellido2_alberguista']."',";
					if($_POST['direccion_alberguista']==""){
						$sql_update.="Direccion_Cl =NULL,";	
					}else{
						$sql_update.="Direccion_Cl ='".$_POST['direccion_alberguista']."',";	
					}
					if($_POST['cp_alberguista']==""){
						$sql_update.="CP_Cl=NULL,";
					}else{
						$sql_update.="CP_Cl='".$_POST['cp_alberguista']."',";	
					}
					if($_POST['localidad_alberguista']==""){
						$sql_update.="Localidad_Cl=NULL, ";
					}else{
						$sql_update.="Localidad_Cl='".$_POST['localidad_alberguista']."', ";	
					}
					if($_POST['provincia_alberguista']==""){
						$sql_update.="Id_Pro=NULL,";
					}else{
						$sql_update.="Id_Pro='".$_POST['provincia_alberguista']."',";
					}
					  $sql_update.="Id_Pais ='".$_POST['pais_alberguista']."', Fecha_Nacimiento_Cl='".$fecha."',Sexo_Cl ='".$_POST['sexo_alberguista']."', Tfno_Cl='".$_POST['telefono_alberguista']."',Observaciones_Cl='".$_POST['observaciones_alberguista']."',Email_Cl='".$_POST['email_alberguista']."' , Tipo_Documentacion='".$_POST['tipo_documentacion']."', Fecha_Expedicion='".$_POST['anyo_fecha_expedicion']."-".$_POST['mes_fecha_expedicion']."-".$_POST['dia_fecha_expedicion']."' where DNI_Cl like '".$_POST['dni_ant']."'";
					if(!mysql_query($sql_update)){
						if($_POST['dni_ant'] != $dni){
							$sql_pernocta = "update pernocta set DNI_Cl = '".$dni."' where DNI_Cl like '".$_POST['dni_ant']."'";
							mysql_query($sql_pernocta);
						}
						echo "<script>alert('La modificación no se ha podido realizar correctamente.')</script>";
					}
					if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
						echo "<script>location.href='index.php?pag=listado_criterio_alberguistas.php'</script>";
					}
					$_POST = Array();
				}else{

					// BAJA DE alberguista
					// BAJA DE alberguista
					// BAJA DE alberguista

					if($_POST['accion'] == "baja"){
						$sql_comprueba = "select * from pernocta where DNI_Cl like '".$_POST['dni_alberguista']."'";
						$result_pernocta = mysql_query($sql_comprueba);
						$result_count = mysql_query("select * from pernocta where DNI_Cl like '".$_POST['dni_alberguista']."'");
						if(mysql_num_rows($result_count)>1){
							$mensaje="No se puede eliminar al alberguista, consta en mas de una estancia en el albergue";
						}else{
							$fila_pernocta = mysql_fetch_array($result_pernocta);
							if($fila_pernocta['Fecha_Llegada'] == date("Y-m-d")){
								$sql_baja_pernocta = "delete from pernocta where DNI_Cl like '".$_POST['dni_alberguista']."'";					
								$sql_baja = "delete from cliente where DNI_Cl like '".$_POST['dni_alberguista']."'";
								if(mysql_query($sql_baja_pernocta)){
									if(!mysql_query($sql_baja)){
										$mensaje = "No se ha podido eliminar el alberguista.";
									}
								}else{
									$mensaje = "No se ha podido eliminar la estancia, tampoco el alberguista.";
								}
							}else{
								$mensaje="No se puede eliminar al alberguista, actualmente no se encuentra realizando una estancia.";
							}								
						}
						if (strlen($mensaje) > 0){
							echo "<script>alert('".$mensaje."')</script>";
						}
						if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
							echo "<script>location.href='index.php?pag=listado_criterio_alberguistas.php'</script>";
						}
						$_POST = Array();																	
					}
				}
			}
		
		}
			
			//Consulta para el listado de alberguistas
			$sql_inicial = "select * from cliente where dni_cl in (select dni_cl from pernocta)";
			if(isset($_GET['orden_listado']) && $_GET['orden_listado']!=""){
				$sql_inicial.=" order by ".$_GET['orden_listado'];
				if($_GET['orden_listado'] == $_SESSION['orden_listado']['anterior']){
					if($_SESSION['orden2'] == "asc"){
						$orden2 = "desc";
					}else{
						$orden2 = "asc";
					}
				}else{
					$orden2 = "asc";
				}
				$sql_inicial.=" ".$orden2;
				$_SESSION['orden_listado']['anterior'] = $_GET['orden_listado'];
				$_SESSION['orden2'] = $orden2;
			}
			if(!$result = mysql_query($sql_inicial)){
				echo "<br><br><label class=\"label_formulario\">No se ha podido conectar a la base de datos.</label>";
			}else{				
				$sw_listado = true;			
				$sql_provincias = "select * from provincia order by Nombre_Pro asc";
				if(@$result_provincias = mysql_query($sql_provincias)){
					while($fila_provincias = mysql_fetch_array($result_provincias)){
						$provincias[$fila_provincias['Id_Pro']] = $fila_provincias['Nombre_Pro'];
					}
				}
				$sql_paises = "select * from pais order by Nombre_Pais asc";				
				if(@$result_paises = mysql_query($sql_paises)){
					while($fila_paises = mysql_fetch_array($result_paises)){
						$paises[$fila_paises['Id_Pais']] = $fila_paises['Nombre_Pais'];
					}
				}
			}
?>
<div id="caja_superior">
	<div id="caja_superior_izquierda" style="margin-left:0px;width:350px;margin-top:3px;">
	<?php
	if(!isset( $_GET['dni']) || trim($_GET['dni']) ==""){
		include("alberguista_alta.php");
	}else{
		if(isset($_GET['modificar']) && trim(strtolower($_GET['modificar'])) == "si" ){
			include("alberguista_modificacion.php")	;		
		}else 	if(isset($_GET['detalles']) && trim(strtolower($_GET['detalles']))=="si" ){
			include("alberguista_detalles.php");
		}else	if(isset($_GET['eliminar']) && trim(strtolower($_GET['eliminar']))=="si"){
			include("alberguista_baja.php");			
		}
	}
	$sw_reserva = false;	
	if(isset($_GET['accion']) && trim($_GET['accion'])!= "" && trim(strtolower($_GET['accion'])) == "reserva"){
		$sw_reserva = true;
	}else{	
			if($_POST['sub'] == ""){
				//Si está en un dia incorrecto se cambia la fecha de calendario
				if(isset($_POST['dia_fecha_llegada_alberguista']) && $_POST['dia_fecha_llegada_alberguista'] != ""){
					
					if (($_POST['anyo_fecha_llegada_alberguista'] != $_SESSION['gdh']['anio']) && ($_POST['mes_fecha_llegada_alberguista'] != $_SESSION['gdh']['mes']) && ($_POST['dia_fecha_llegada_alberguista'] != $_SESSION['gdh']['dia'])){
						echo "<script>cambiar_dia(".$_POST['dia_fecha_llegada_alberguista'].",".$_POST['mes_fecha_llegada_alberguista'].",".$_POST['anyo_fecha_llegada_alberguista'].",0);</script>";
						
					}
				}else{					
					if(trim($_SESSION['gdh']['anio'])."-".trim($_SESSION['gdh']['mes'])."-".trim($_SESSION['gdh']['dia']) != trim(date("Y-m-d"))){	
						echo "<script>document.forms[0].fecha_cal.value = '".trim(date("d"))."-".trim(date("m"))."-".trim(date("Y"))."';prepara_cambiar_dia();cambiar_dia(".trim(date("d")).",".trim(date("m")).",".trim(date("Y")).");</script>";
					}
				}
				if($sw_calendario){	
					echo "<script>alert('Intenta acceder a una fecha incorrecta.Su estancia transcurre de ".$_POST['dia_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['anyo_fecha_llegada_alberguista']." a ".establecer_fecha($_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'],$_POST['pernocta_alberguista'])."')</script>";
					echo "<script>cambiar_dia(".$split_fecha_cal[2].",".$split_fecha_cal[1].",".$split_fecha_cal[0].",0);</script>";
				}		
			}
		}	
		
		?>
	</div>
	<div id="caja_superior_derecha" >
		<div id="caja_superior_derecha_a" style="display:none;">
			<div id="caja_habitaciones" style="display:block;">				
						<?php
									if(isset($_POST['sub']) && $_POST['sub'] == "true"){										
											$fecha_llegada = $_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'];
										}else{
											$fecha_llegada = $fecha_calendario;
										}
										if(isset($_SESSION['reserva']['sw_reserva']) && trim($_SESSION['reserva']['sw_reserva']) =="true"){
											$fecha_salida = $_SESSION['reserva']['noche'][0]['fecha_salida'];
										}else{
											$fecha_salida = establecer_fecha($fecha_llegada, $_POST['pernocta_alberguista']);
										}
										//DISTRIBUCIÓN DE HABITACIONES
										//DISTRIBUCIÓN DE HABITACIONES
										//DISTRIBUCIÓN DE HABITACIONES



										//Se Almacenan en un array los datos de todas las habitaciones existentes;
										$habita=array();
										$max_camas=0;//Numero de camas que tiene la habitación mas grande										
										$sw_qry_dist = false;
										/*$qry_dist = "select * from habitacion";
										for($i=0;$i<count($_SESSION['pag_hab']);$i++){
												if($_SESSION['pag_hab'][$i]['pagina'] == $pagina_habitaciones){
													if($sw_qry_dist){
														$qry_dist .=" or Id_Tipo_Hab = ".$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']." ";
													}else{
														$qry_dist.=" where Id_Tipo_Hab = ".$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']." ";
														$sw_qry_dist = true;
													}
												}
											}
										$qry_dist .= " order by Id_Hab ";		
										$qry_dist = "SELECT * FROM habitacion inner join tipo_habitacion WHERE (habitacion.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab AND tipo_habitacion.reservable = 's' AND Camas_Hab > 0) ORDER BY Id_Hab";*/


										$qry_dist = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '".$fecha_llegada."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab WHERE habitacion.Camas_Hab > 0) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab WHERE tipo_habitacion.reservable LIKE 'S' ";
										
										$res_dist=mysql_query($qry_dist);
										$cont=0;
										$tipo_Hab=array();
										$tipo_Hab=array();
										//$max_tipo = 0;
										
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										
										//Consultas para recoger las habitaciones ocupadas
										//Consultas para recoger las habitaciones ocupadas
										//Consultas para recoger las habitaciones ocupadas


										$sql_hab_reserva = "select * from reserva LEFT JOIN detalles on detalles.DNI_PRA = reserva.DNI_PRA where 
										(detalles.Fecha_Llegada >= '".$fecha_calendario."' OR detalles.Fecha_Salida > '".$fecha_calendario."') AND 
										(detalles.Fecha_Llegada < '".$fecha_salida."' OR detalles.Fecha_Salida < '".$fecha_salida."') AND
										(detalles.Fecha_Llegada != detalles.Fecha_Salida) and reserva.DNI_PRA = detalles.DNI_PRA order by Id_Hab ";
										

										$sql_hab_pernocta = "select * from pernocta where !(Fecha_Llegada < '".$fecha_calendario."' and Fecha_Salida < '".$fecha_calendario."') and !(Fecha_Llegada >= '".$fecha_salida."' and Fecha_Salida >= '".$fecha_salida."') and Fecha_Salida <> '".$fecha_calendario."' order by Id_Hab";
										

										$sql_hab_pernocta_p = "select * from pernocta_p where !(Fecha_Llegada < '".$fecha_calendario."' and Fecha_Salida < '".$fecha_calendario."') and !(Fecha_Llegada > '".$fecha_calendario."' and Fecha_Salida > '".$fecha_calendario."') and Fecha_Salida <> '".$fecha_calendario."' order by Id_Hab";


										$sql_hab_pernocta_gr = "SELECT pernocta_gr.Nombre_Gr,pernocta_gr.Id_Hab as Id_Hab ,pernocta_gr.Fecha_Llegada as Fecha_Llegada ,pernocta_gr.Num_Personas AS Num_Personas,estancia_gr.Fecha_Salida as Fecha_Salida,estancia_gr.Id_Color as Id_Color FROM pernocta_gr LEFT JOIN estancia_gr ON pernocta_gr.Fecha_Llegada=estancia_gr.Fecha_Llegada AND pernocta_gr.Nombre_Gr=estancia_gr.Nombre_Gr WHERE !(estancia_gr.Fecha_Llegada < '".$fecha_llegada."' and estancia_gr.Fecha_Salida < '".$fecha_llegada."') and  !(estancia_gr.Fecha_Llegada >= '".$fecha_salida."' and estancia_gr.Fecha_Salida >= '".$fecha_salida."') order by estancia_gr.Fecha_Llegada" ;
$colores = Array();
$sql_colores = "SELECT * FROM colores";
$res_colores = mysql_query($sql_colores);
for($c=0;$c<mysql_num_rows($res_colores);$c++){
	$fila_colores = mysql_fetch_array($res_colores);
	$colores[$c]['id'] = $fila_colores['Id_Color'];
	$colores[$c]['color'] = $fila_colores['Color'];
}

										for ($i=0;$i<mysql_num_rows($res_dist);$i++){
											$indice_salida['gr'][$i] = 0;
											$indice_salida['alb'][$i] = 0;
											$indice_salida['per'][$i] = 0;
											$indice_salida['reserva'][$i] = 0;
											$tupla_dist=mysql_fetch_array($res_dist);
											$lenght = count($habita);
											$habita[$i]['orden']=intval($tupla_dist['Id_Hab']);
											$habita[$i]['id']=$tupla_dist['Id_Hab'];
											$_SESSION['dist_hab'][$i]['camas_restantes'] = $habita[$i]['camas'];
											$habita[$i]['tipo']=$tupla_dist['Id_Tipo_Hab'];
											/*if($habita[$i]['tipo'] > $max_tipo){
												$max_tipo = $habita[$i]['tipo'];
											}*/
											$habita[$i]['camas']=$tupla_dist['Camas_Hab'];
											$habita[$i]['ocupadas']=0;
											for($j=0;$j<count($_SESSION['pag_hab']);$j++){
												if($pagina_habitaciones == $_SESSION['pag_hab'][$j]['pagina'] && $habita[$i]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab'] ){																
													$habita[$i]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];
													if ($tupla_dist['Camas_Hab'] > $max_camas){
														$max_camas=$tupla_dist['Camas_Hab'];
													}
													break;
												}else{
													if($habita[$i]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab']){
														$habita[$i]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];
														break;
													}
												}
											}

											if ($tupla_dist['Camas_Hab'] > $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']) {
												
												$habita[$lenght]['Columnas_Necesarias']=0;
												$col=intval($tupla_dist['Camas_Hab']/$_SESSION['gdh']['dis_hab']['configuracion_numero_camas']);
												for ($al=0;$al<($col);$al++){
													$habita[$lenght]['Columnas_Necesarias']++;
													$habita[$lenght]['camas_col'][$al] = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];
												}
												if (($tupla_dist['Camas_Hab'] % $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']) > 0){
													$habita[$lenght]['Columnas_Necesarias']++;
													$habita[$lenght]['camas_col'][$al] = ($tupla_dist['Camas_Hab'] % $_SESSION['gdh']['dis_hab']['configuracion_numero_camas']);
												}
											}else{
												$habita[$lenght]['Columnas_Necesarias'] = 1;
												$habita[$lenght]['camas_col'][0] = $tupla_dist['Camas_Hab'];
											}
											
										//Comparamos el numero maximo de camas	

										// [borrado]

										}

	////////////// Miramos los posibles cambios de tipo de habitacion  //////////////////

	$qry_habs_temps = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MIN(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha > '".$fecha_llegada."' AND Fecha < '".$fecha_salida."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab ";


	$res_habs_temps = mysql_query($qry_habs_temps);

	while ($fila_habs_temps = mysql_fetch_array($res_habs_temps)){
		for ($i=0;$i<count($habita);$i++){	
			if ($habita[$i]['id'] == $fila_habs_temps['Id_Hab']){
				$habita[$i]['cambio_tipo']['fecha'] = $fila_habs_temps['Fecha'];
				$habita[$i]['cambio_tipo']['reservable'] = $fila_habs_temps['Reservable'];
			}
		}
	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////// Ahora saco todo esto de ahi y meto los datos de todas las pernoctas y reservas en habita

//////////////////// Hola, soy al y voy a crear unos arrays temporales donde meter los datos de todas las reservas y pernoctas

$temp_reserva = array();
$temp_pernocta = array();
$temp_pernocta_p = array();
$temp_pernocta_gr = array();

				
//Esto es una prueva, no se si petara la pagina por otro lado
if (!isset($fecha_llegada) || $fecha_llegada == ""){$fecha_llegada = $fecha_calendario;}


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
				//echo "ADD-alb--";
			
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
			if ($fila['Fecha_Llegada'] <= $fecha_llegada){
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
if ($_POST['pernocta_alberguista'] > 1){
	for ($h=0;$h<count($habita);$h++){
		// Para cada habitacion recorro dia a dia durante toda la estancia
		$max_camas = 0;
		// Sumo las que ya hay 
		$max_camas += ($habita[$h]['gr']['cantidad'] + $habita[$h]['per']['cantidad'] + $habita[$h]['alb']['cantidad'] + $habita[$h]['reservas']['cantidad']);
		$temps_temp = $max_camas;
		for ($i=1;$i<$_POST['pernocta_alberguista'];$i++){
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
				//echo "<br>Sumo temps para la Hab. ".$habita[$h]['id']." : Día ".$fecha_temp." -> ".$max_camas." camas";
			}
		}
	}
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


										$sql_tipo_hab ="select * from tipo_habitacion";
										$result_tipo_hab = mysql_query($sql_tipo_hab);
										while($fila_tipo_hab = mysql_fetch_array($result_tipo_hab)){
											$tipo_habitacion[$fila_tipo_hab['Id_Tipo_Hab']] = $fila_tipo_hab['Nombre_Tipo_Hab'];
											
										}								
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
															$sw_pag = false;	
														}
													}
													if($sw_pag){
														$numero_paginas_aux[] = intval($habita[$i_pag]['pagina']);
													}	
												}
						?>
					<table cellpadding="0" cellspacing="0" style="width:700px;margin-bottom:20px;" border="0">
						<thead>
							<td colspan="9" align="left" style="padding:0px 0px 0px 0px;">
								<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
								<div style="height:25px;text-align:center;float:left;" class="champi_centro">
								<div class="titulo" style="width:638px;text-align:center;">Distribución de Habitaciones 
								<select name="pagina_habitaciones" class="select_formulario" onChange="document.forms[0].sub.value='true';document.forms[0].submit();" style="margin-left:40px;">
									<?php
										for($i_pag = 0;$i_pag<count($numero_paginas_aux);$i_pag++){
											echo "<option value=\"".$numero_paginas_aux[$i_pag]."\"";
											if(intval($_POST['pagina_habitaciones']) == intval($numero_paginas_aux[$i_pag]) || intval($_SESSION['gdh']['dis_hab']['num_pag']) == intval($numero_paginas_aux[$i_pag])){
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
								<td class="tabla_detalles" align="center" style="padding:0px 0px 0px 0px;border: 1px solid #3F7BCC;" width="100%" >
									<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">				
										<tr>
											<td align="center" style="padding:0px 0px 0px 0px;">
											</td>
										</tr>
										<tr>
											<td>
												<table border="0" class="tabla_habitaciones" cellpadding="0" cellspacing="1" align="center">
													<tr align="center" valign="middle">
														<td width="2px"></td>
										
												
	<?PHP			
		//comienza la distribucion de habitaciones 
		//comienza la distribucion de habitaciones 
		//comienza la distribucion de habitaciones 

			$separar = $habita[0]['Id_Tipo_Hab'];
			//echo "<br> Veamos habita:<br>";
			for ($i = 0; $i < count($habitassssssss); $i++) {	
			//	echo "<br>Habitacion ".$habita[$i]['id'].": Tipo=".$habita[$i]['tipo']." Columnas=".$habita[$i]['Columnas_Necesarias'];
				$colspan = 0;
				if ($separar != $habita[$i]['tipo']) {
					$separar = $habita[$i]['tipo'];			
	?>
													<td width="2px" rowspan="<?PHP echo $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'] + 3; ?>" align="center" class="separar_hab">	</td>
	<?
				}
	?>
													<td class="nom_tipo_hab" colspan="<?PHP echo $colspan; ?>">
														<?PHP echo $tipo_habitacion[$habita[$i]['tipo']]; ?>
													</td>
	<?php	
				$i = $i + $habita[$i]['tipo'] - 1;													
			}	
	?>
													<td width="2px"></td>
												</tr>
												<tr align="center" valign="middle">
												<td width="2px"></td>
	<?php
			
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

	?>

													<td width="2px"></td>
												</tr>
<?php		

	$sw_ocupada= false; //boolean que indica si hay una habitacion seleccionada
	$habita2 = $habita;
	for($fila=0;$fila<=$_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];$fila++){												
		$tipo2 = $habita[0]['tipo'];															
			echo "<tr><td width=\"2px\"></td>";
			$sw_first = false;
			for($i=0;$i<count($habita);$i++){														
				$sw_tipo_hab = false;
				if($tipo2 !=$habita[$i]['tipo'] && $habita[$i]['tipo']!= "" || !$sw_first){
					for($j=0;$j<count($_SESSION['pag_hab']);$j++){
						if($pagina_habitaciones == $_SESSION['pag_hab'][$j]['pagina'] && $habita[$i]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab'] ){	
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
						echo "<td class=\"nom_hab\" colspan=\"".$habita[$i]['Columnas_Necesarias']."\">".$habita[$i]['id']."</td>";
				}else{	
					for($columnas=0;$columnas<$habita[$i]['Columnas_Necesarias'];$columnas++){
						$sw_cama = false;
//echo " hab(".$habita[$i]['id']."):if(!".$sw_cama." && (camas_col) ".$habita[$i]['camas_col'][$columnas]." <= 0 || (camas_col) ".$habita[$i]['camas_col'][$columnas]."<=0 && (camas) ".$habita[$i]['camas']."<1)";
						if(!$sw_cama && $habita[$i]['camas_col'][$columnas] <= 0 || $habita[$i]['camas_col'][$columnas]<=0 && $habita[$i]['camas']<1){	
							echo "<td id=\"no_cama\">&nbsp;</td>";
							$sw_cama = true;																	
						}

						//Cama ocupada  por un alberguista

						if(!$sw_cama && $habita[$i]['alb']['cantidad'] > 0 && $habita[$i]['alb']['cantidad'] != $habita[$i]['alb']['temporal']['cantidad']){																	
							echo "<td class=\"cama_ocupada\" >&nbsp;</td>";		
							$sw_cama = true;
							$habita[$i]['alb']['cantidad']--;
						}
																					
						
						
						//Cama ocupada  por un alberguista

						if(!$sw_cama && $habita[$i]['per']['cantidad'] > 0 && $habita[$i]['per']['cantidad'] != $habita[$i]['per']['temporal']['cantidad']){
							echo "<td class=\"cama_ocupada\" >&nbsp;</td>";			
							$habita[$i]['per']['cantidad']--;																	
							$sw_cama = true;
						}

						//Cama ocupada temporalmente por una reserva

						if(!$sw_cama && $habita[$i]['reservas']['cantidad'] > 0 && $habita[$i]['reservas']['cantidad'] == 	$habita[$i]['reservas']['temporal']['cantidad']){
							$fecha_hab_split = split("-",$habita[$i]['reservas']['temporal']['fecha'][$indice_salida['reserva'][$i]]['fecha']);
							echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\"";
							if($_SESSION['reserva']['sw_reserva'] !="true"){
								echo 	"onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\"";
							}
							echo ">&nbsp;</td>";
							if(!$sw_ocupada){
								for($hab = 0;$hab<count($_SESSION['alb']['estancia']['habitaciones']);$hab++){
									if(trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['id']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['fecha'])) >= 0 && $_SESSION['alb']['estancia']['habitaciones'][$hab]['parcial'] == 1 ){	
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
						
						//Cama ocupada por un miembro de un grupo

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

						//Cama ocupada temporalmente por un miembro de un grupo

						if(!$sw_cama && $habita[$i]['gr']['cantidad'] > 0 && $habita[$i]['gr']['cantidad'] == $habita[$i]['gr']['temporal']['cantidad']){
							
							$fecha_hab_split = split("-",$habita[$i]['gr']['temporal']['fecha'][$indice_salida['gr'][$i]]['fecha_llegada']);
							echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\"";
							if($_SESSION['reserva']['sw_reserva'] != "true"){
								echo "onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\"";
							}
							echo ">&nbsp;</td>";
							if(!$sw_ocupada){
								for($hab = 0;$hab<count($_SESSION['alb']['estancia']['pernoctas']);$hab++){
									if(trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['id']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['fecha'])) >= 0 && $_SESSION['alb']['estancia']['habitaciones'][$hab]['parcial'] == 1 ){	
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

						if(!$sw_cama && $habita[$i]['alb']['cantidad'] > 0 && $habita[$i]['alb']['cantidad'] == $habita[$i]['alb']['temporal']['cantidad']){
							$fecha_hab_split = split("-",$habita[$i]['alb']['temporal']['fecha'][$indice_salida['alb'][$i]]['fecha_llegada']);
							echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\"";
							if($_SESSION['reserva']['sw_reserva'] != "true"){
								echo "onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\"";
							}
							echo ">&nbsp;</td>";
							if(!$sw_ocupada){
								for($hab = 0;$hab<count($_SESSION['alb']['estancia']['habitaciones']);$hab++){
									if(trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['id']) && resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['fecha_llegada'])) >=0 && $_SESSION['alb']['estancia']['habitaciones'][$hab]['parcial'] == 1 ){
										echo "<script>asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','n',1);</script>";
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

						//Cama ocupada temporalmente por un alberguista

						if(!$sw_cama && $habita[$i]['per']['cantidad'] > 0 && $habita[$i]['per']['cantidad'] == $habita[$i]['per']['temporal']['cantidad']){
							echo "<td style=\"background-color:#5C8DBE;\" class=\"cama_temp\"  id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\"";
							if($_SESSION['reserva']['sw_reserva'] != "true"){
								$fecha_hab_split = split("-",$habita[$i]['per']['temporal']['fecha'][$habita[$i]['per']['temporal']['cantidad']]);
								echo "onClick=\"if(confirm('Estará ocupada a partir de ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();\"";			
							}
							echo ">&nbsp;</td>";
							$habita[$i]['per']['temporal']['cantidad']--;
							$habita[$i]['per']['cantidad']--;
							$sw_cama = true;
						}
						
						//Cama ocupada  por una reserva

						if(!$sw_cama && $habita[$i]['reservas']['cantidad'] >0 && $habita[$i]['reservas']['cantidad'] != $habita[$i]['reservas']['temporal']['cantidad'] ){
							if($_SESSION['reserva']['sw_reserva'] == "true"){	
								
								
								//echo "if(!$sw_ocupada && ".trim($habita[$i]['id'])." == ".trim($_SESSION['reserva']['id_hab'])." && ".resta_fecha($_POST['anyo_fecha_llegada_alberguista']."-".$_POST['mes_fecha_llegada_alberguista']."-".$_POST['dia_fecha_llegada_alberguista'],$_SESSION['reserva']['fecha_llegada'])." ==0 && ".resta_fecha($fecha_calendario,$_SESSION['reserva']['fecha_salida'])." > 0)";


									if(!$sw_ocupada && trim($habita[$i]['id']) == trim($_SESSION['reserva']['id_hab']) && resta_fecha($fecha_calendario,$_SESSION['reserva']['fecha_salida']) > 0){
										echo "<td class=\"cama_libre\" id=\"".trim($habita[$i]['id'])."-".trim($i)."-".trim($columnas)."-".trim($fila)."\" >&nbsp;</td>";
										echo "<script>asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."',0);</script>";
										$sw_ocupada = true;
									}else{
										echo ("<td class=\"cama_reservada\">&nbsp;</td>");	
									}		
							}else{																	
								echo ("<td class=\"cama_reservada\">&nbsp;</td>");								
							}
							$sw_cama = true;			
							//echo "cantidad = ".$habita[$i]['reservas']['cantidad']." -> '--' ";
							$habita[$i]['reservas']['cantidad']--;
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

						if(!$sw_cama && $habita[$i]['camas'] >= $fila){
							if (isset($habita[$i]['cambio_tipo'])){
								$fecha_hab_split = split("-",$habita[$i]['cambio_tipo']['fecha']);
								//if ($habita[$i]['cambio_tipo']['reservable'] == 'N'){
									echo "<td class=\"cama_temp\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" title='La cama cabiará de tipo a partir del día  ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."' onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('La cama cabiará de tipo a partir del día  ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea continuar?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\"";
									if($_SESSION['reserva']['sw_reserva'] != "true"){
										echo "onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."',0)\"";
									}
									echo ">".$fecha_hab_split[2]."·".$fecha_hab_split[1]."</td>";
								/*}else{
									echo "<td class=\"cama_libre\"   id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" title='La cama cabiará de tarifa a partir del día  ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."' onClick=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){if(confirm('La cama cabiará de tarifa a partir del día  ".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0].", ¿Desea partir la estancia?')){prepara_cambiar_dia();asignacion_parcial('".$fecha_hab_split[2]."-".$fecha_hab_split[1]."-".$fecha_hab_split[0]."','".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."');}}else{asigna_habitacion('".trim($habita[$i]['id'])."','".trim($i)."','".trim($columnas)."','".trim($fila)."','s',1);}\"";
									if($_SESSION['reserva']['sw_reserva'] != "true"){
										echo "onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."',0)\"";
									}
									echo "></td>";

								}*/
							}else{
								echo "<td class=\"cama_libre\" onMouseOver=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_resaltada';}\"	onMouseOut=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."'){document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_libre';}\"  id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" ";
								if($_SESSION['reserva']['sw_reserva'] != "true"){
									echo "onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."',0)\"";
								}
								echo ">$nbsp</td>";
							}
							
									
							for($hab=0;$hab<count($_SESSION['alb']['estancia']['habitaciones']);$hab++){
									if(resta_fecha(trim($fecha_calendario),trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['fecha'])) == 0){
									break;
								}
							}
							if(!$sw_ocupada && trim($habita[$i]['id']) == trim($_SESSION['alb']['estancia']['habitaciones'][$hab]['id'])){
								echo "<script>asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."','n',0);</script>";
								$sw_ocupada = true;
							}
							$sw_cama = true;
						}else{
							if(!$sw_cama){
								echo ("<td class=\"no_cama\">&nbsp;</td>");
								$sw_cama = true;
							}
						}														
						$habita[$i]['camas_col'][$columnas]--;
						$total_camas[$i]++;
					}//for columnas
				}//if (fila = 0)
			}//if(sw tipo hab)
		}//for (count habita)
		echo "<td width=\"2px\"></td></tr>";
	}//for(fila)											
?>										
								</table>	
							</td>
						</tr>
						
						<tr>
							<td>
							<div id="leyenda" style="position:absolute;  background-color: #FFFFFF; border: 1px none #000000;  visibility: hidden;z-index: 1;font-size:10px;margin-left:25px;margin-top:-5px;width:400px;color:#064C87;float:left;" > 
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
							<span style='margin-right:100px;postion:relative;float:right;'>
							<a href="#" style="font-family:Verdana;font-size:12px;color:#467DB5;font-weight:bold;text-decoration:underline;" onClick="ver_leyenda('2')" title="Ver la Leyenda de la Distribución de Habitaciones" OnMouseOver="window.status='Ver la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true"> Leyenda </a>
							</span>
							</td>
						</tr>		
						</tbody>
					</table>
					</td>
				</tr>

					</table>
					
					<?php
					}else{
						echo "<br><br><br><br><br><span class=\"label_formulario\">No se puede mostrar el mapa de habitaciondes, compruebe que existen habitaciones en el sistema.</span><br><br><br><br><br>";					
						$habitaciones_exist = "false";					
					}
				}else{
					echo "<br><br><br><br><br><span class=\"label_formulario\">No se puede mostrar el mapa de habitaciondes, compruebe que existen habitaciones en el sistema.</span><br><br><br><br><br>";					
					$habitaciones_exist = "false";					
				}
				?>	
		</div>						
	</div>
	
	<div id="caja_superior_derecha_b" style="display:block;">
		<table border="0" id="tabla_detalles" width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%;">
			<tr id="titulo_tablas">
				<td colspan="4" align="center" style="padding:0px 0px 0px 0px;">
					<div style="height:25px;width:30px;float:left;" class="champi_izquierda" id="alerta_esquina_izquierda">&nbsp;</div>
					<div style="height:25px;text-align:center;float:left;" class="champi_centro">
					<div class="titulo" style="width:640px;text-align:center;">Listado de Alberguistas</div>
					</div>
					<div style="height:25px;width:30px;float:left;" class="champi_derecha">&nbsp;</div>
				</td>					
			</tr>					
			<tr>					
				<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
					<div id="tabla_listado" class="tableContainer" style='width:680px;height:550px;background-color:#F4FCFF;'>
					<?php
						//Listado de datos de alberguistas
						
						if(!$sw_listado){
							echo "<br><br>";
							echo "<span class=\"label_formulario\">No se ha podido conectar con la base de datos</span>";
						}else{		
							
						?>
						<table border="0" cellpadding="0" cellspacing="0" class="scrollTable" width="100%">
							<thead class="fixedHeader" cellspacing="0" width="100%"  class="scrollTable" >
									<!--<th style="padding:0px 0px 0px 0px;" align="center"><a href="?pag=alberguistas.php&orden_listado=Tipo_documentacion">Tipo</a></th>	-->
									<th><a href="?pag=alberguistas.php&orden_listado=DNI_Cl">DNI</a></th>
									<th><a href="?pag=alberguistas.php&orden_listado=Nombre_cl">Nombre</a></th>
									<th><a href="?pag=alberguistas.php&orden_listado=Apellido1_cl">Primer Apellido</a></th>
									<th><a href="?pag=alberguistas.php&orden_listado=Apellido2_cl">Segundo Apellido</a></th>
									<!--<th><a href="?pag=alberguistas.php&orden_listado=Fecha_Nacimiento_Cl">Fecha Nacimiento</a></th>
									<th><a href="?pag=alberguistas.php&orden_listado=Id_Pais">País</a></th>-->
									<th>D</th>
									<th>M</th>
									<th>E</th>
							</thead>
							<tbody class="scrollContent">
							
									
							<?php
							    // Variables para los textArea
							    $cols_dni = 13;
							    $div_dni = 13;
							    $cols_nom = 14;
							    $div_nom = 10;
							    $cols_ape = 30;
							    $div_ape = 22;
								for($i=0;$i<mysql_num_rows($result);$i++){
									$fila_listado = mysql_fetch_array($result); 
								?>
									<tr class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
										<!--<td align="center" valign='top'>
											<?php echo $fila_listado['Tipo_documentacion']?>
										</td>-->
										<td valign='top'>
										<?php 
													$num_dni = intval(strlen($fila_listado['DNI_Cl'])/$div_dni) + 1;
													echo "<TEXTAREA class='areatexto'  Name=\"DNI_C1".$i."\" readonly
													rows=\"".$num_dni."\" cols=\"".$cols_dni."\"  >".$fila_listado['DNI_Cl']."</TEXTAREA>	
													";	
										?>
										</td>
										<td valign='top'>
										<?php
													$num_nom = intval(strlen($fila_listado['Nombre_Cl'])/$div_nom) + 1;
													echo "<TEXTAREA class='areatexto' Name=\"Nombre_C1".$i."\" readonly
													rows=\"".$num_nom."\" cols=\"".$cols_nom."\"  >".$fila_listado['Nombre_Cl']."</TEXTAREA>	
													";
									    ?>				
										</td>
										<td valign='top'>
										<?php
										 			$num_ape1 = intval(strlen($fila_listado['Apellido1_Cl'])/$div_ape) + 1;
													echo "<TEXTAREA class='areatexto' Name=\"Apellido1_C1".$i."\" readonly
													rows=\"".$num_ape1."\" cols=\"".$cols_ape."'\" >".$fila_listado['Apellido1_Cl']."</TEXTAREA>";
										?>
										</td>
										<td valign='top'>
										<?php
													//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
													$num_ape2 = intval(strlen($fila_listado['Apellido2_Cl'])/$div_ape) + 1;
													echo "<TEXTAREA class='areatexto' Name=\"Apellido2_C1".$i."\" readonly
													rows=\"".$num_ape1."\" cols=\"".$cols_ape."\" >".$fila_listado['Apellido2_Cl']."</TEXTAREA>";
										 ?></td>
											<!--<td>
											<?
												$fecha_listado = split("-",$fila_listado['Fecha_Nacimiento_Cl']);
												echo $fecha_listado[2]."-".$meses[intval($fecha_listado[1])-1]."-".$fecha_listado[0];
											?></td>
										<td><?
												if(strlen($paises[$fila_listado['Id_Pais']]) > 10){
													echo substr($paises[$fila_listado['Id_Pais']],0,7)."...";
												}else{
													echo $paises[$fila_listado['Id_Pais']];
												}	
												?>
												</td>-->
								
							
								
								<td><a href="?pag=alberguistas.php&detalles=si&dni=<?echo $fila_listado['DNI_Cl'];?>&tipo_doc=<?echo $fila_listado['Tipo_documentacion']?>"><IMG alt='Ver Detalles de Alberguista' src='../imagenes/botones/detalles.gif' border=0></a></td>
								<td><a href="?pag=alberguistas.php&modificar=si&dni=<?echo $fila_listado['DNI_Cl'];?>&tipo_doc=<?echo $fila_listado['Tipo_documentacion']?>"><IMG alt='Modificar Datos de Alberguista' src='../imagenes/botones/modificar.gif' border=0></a></td>
								<td><a href="?pag=alberguistas.php&eliminar=si&dni=<?echo $fila_listado['DNI_Cl'];?>&tipo_doc=<?echo $fila_listado['Tipo_documentacion'];?>"><IMG alt='Eliminar Alberguista' src='../imagenes/botones/eliminar.gif' border=0></a></td>
							</tr>
							<?
								}
							?>	

							</tbody>
						</table>
						<?
						}
						?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
</form>
<?php 
	//Comprueba si existe un cliente en el sistema con el mismo dni y tipo de documentación.
	//Si actualmente se encuentra realizando una estancia, mostrará un mensaje. De lo contrario rellenará los campos conlos datos del cliente
	if(isset($_POST['dni_alberguista']) && $_POST['test_dni'] == "1" && (!isset($_GET['accion']) ||  $_GET['accion'] =="")){

		//La funcion origuinal esta comentada, asi ke la reescribo para que compruebe si existe el dni en el sistema
		
		$dni_completo = trim($_POST['dni_peregrino']);
		$sql_incidencias = "select * from incidencias where DNI_Inc like '".$dni_completo."' and DNI_Inc in (select DNI_Cl from cliente where DNI_Cl like '".$dni_completo."' and Tipo_documentacion like '".$_POST['tipo_documentacion']."')";
		$result_incidencias = mysql_query($sql_incidencias);
		if(mysql_num_rows($result_incidencias) > 0){
			echo "<script>alert('El peregrino que va a introducir, tiene incidencias registradas.');document.forms[0].nombre_peregrino.focus();</script>";
		}

		$sql_test_dni = "SELECT * FROM cliente WHERE DNI_Cl = '".trim($_POST['dni_alberguista'])."' ";

		$res_test_dni = mysql_query($sql_test_dni);
		if (mysql_num_rows($res_test_dni) > 0){
			$fila_test_dni = mysql_fetch_array($res_test_dni);
			
			echo "<script>rellena_alta('".$fila_test_dni['Tipo_documentacion']."','".$fila_test_dni['DNI_Cl']."','".$fila_test_dni['Nombre_Cl']."','".$fila_test_dni['Apellido1_Cl']."','".$fila_test_dni['Apellido2_Cl']."','".$fila_test_dni['Id_Pais']."','".$fila_test_dni['Id_Pro']."','".$fila_test_dni['Localidad_Cl']."','".$fila_test_dni['Direccion_Cl']."','".$fila_test_dni['CP_Cl']."','".$fila_test_dni['Fecha_Nacimiento_Cl']."','".$fila_test_dni['Fecha_Expedicion']."','".$fila_test_dni['Sexo_Cl']."','".$fila_test_dni['Tfno_Cl']."','".$fila_test_dni['Email_Cl']."','".str_replace("\r\n"," ",$fila_test_dni['Observaciones_Cl'])."');document.forms[0].existe.value = 'true';document.forms[0].nombre_alberguista.focus();</script>";
			
		}
		




		/*if(!isset($fecha_llegada) || $fecha_llegada==""){
			if(isset($_POST['dia_fecha_llegada_alberguista']) && $_POST['dia_fecha_llegada_alberguista'] != "" && isset($_POST['mes_fecha_llegada_alberguista']) && $_POST['mes_fecha_llegada_alberguista'] != "" && isset($_POST['anyo_fecha_llegada_alberguista']) && $_POST['anyo_fecha_llegada_alberguista'] != ""){
				$fecha_llegada = trim($_POST['anyo_fecha_llegada_alberguista'])."-".trim($_POST['mes_fecha_llegada_alberguista'])."-".trim($_POST['dia_fecha_llegada_alberguista']);
			}else{
				$fecha_llegada = date("Y-m-d");
			}
		}
		$sql_comprueba = "select * from cliente where DNI_Cl like '".trim($_POST['dni_alberguista'])."%' and Tipo_Documentacion like '".trim($_POST['tipo_documentacion'])."' and (DNI_Cl in (select DNI_Cl from pernocta where !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida < '".$fecha_llegada."') and !(Fecha_Llegada > '".establecer_fecha($fecha_llegada,$_POST['pernocta_alberguista'])."' and Fecha_Salida > '".establecer_fecha($fecha_llegada,$_POST['pernocta_alberguista'])."') and Fecha_Salida > '".$fecha_llegada."') or DNI_Cl in (select DNI_Cl from pernocta_p where !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida < '".$fecha_llegada."') and !(Fecha_Llegada > '".establecer_fecha($fecha_llegada,$_POST['pernocta_alberguista'])."' and Fecha_Salida > '".$fecha_llegada."'  and Fecha_Salida > '".establecer_fecha($fecha_llegada,$_POST['pernocta_alberguista'])."')))";
		if($result_comprueba = mysql_query($sql_comprueba)){
			if(mysql_num_rows($result_comprueba) <= 0){
				$sql_comprueba = "select * from cliente where DNI_Cl like '".trim($_POST['dni_alberguista'])."%' and Tipo_Documentacion like '".trim($_POST['tipo_documentacion'])."'";
				if(mysql_query($sql_comprueba)){
					$result_comprueba = mysql_query($sql_comprueba);
					if(mysql_num_rows($result_comprueba) > 0){
						$sql_completa = "select * from cliente where DNI_Cl like '".trim($_POST['dni_alberguista'])."%' and Tipo_Documentacion like '".trim($_POST['tipo_documentacion'])."' ";
						$result_completa = mysql_query($sql_completa);
						if(mysql_num_rows($result_completa) > 0){
							$fila_completo = mysql_fetch_array($result_completa);
							$tipo_doc = $fila_completo['Tipo_documentacion'];				
							echo "<script>rellena_alta('".$tipo_doc."','".$fila_completo['DNI_Cl']."','".$fila_completo['Nombre_Cl']."','".$fila_completo['Apellido1_Cl']."','".$fila_completo['Apellido2_Cl']."','".$fila_completo['Id_Pais']."','".$fila_completo['Id_Pro']."','".$fila_completo['Localidad_Cl']."','".$fila_completo['Direccion_Cl']."','".$fila_completo['CP_Cl']."','".$fila_completo['Fecha_Nacimiento_Cl']."','".$fila_completo['Fecha_Expedicion']."','".$fila_completo['Sexo_Cl']."','".$fila_completo['Tfno_Cl']."','".$fila_completo['Email_Cl']."','".str_replace("\r\n"," ",$fila_completo['Observaciones_Cl'])."');document.forms[0].existe.value = 'true';document.forms[0].nombre_alberguista.focus();</script>";
						}else{
							echo "<script>limpiar_formulario_existe();</script>";
						}
					}
				}
			}else{
				echo "<script>alert('El alberguista con dni : ".$_POST['dni_alberguista']."  ya está realizando una estancia actualmente');document.forms[0].dni_alberguista.value='';limpiar_formulario_existe();</script>";
			}
		}*/
		echo "<script>documentacion(0);</script>";
	}
	//Si el valor enviado de sub es "true"
	if(isset($_POST['sub']) && $_POST['sub'] == "true"){
		//se muestra el mapa de habitaciones y la pestaña de estancia en el formulario de alta
		if(!isset($_SESSION['reserva']['sw_reserva']) || trim($_SESSION['reserva']['sw_reserva']) != "true"){
			echo "<script>nuevo_habitaciones();abrir(2,0);document.forms[0].sub.value='false';</script>";			
			if($habitaciones_exist != "false"){
				//en caso de que existan habitaciones : se muestra el boton aceptar
				echo "<script>	document.getElementById('boton_ver_habitaciones').style.display='none';document.getElementById('boton_nuevo_habitacion2').style.display='block'</script>";
			}else{
				echo "<script>	document.getElementById('boton_ver_habitaciones').style.display='none';document.getElementById('boton_nuevo_habitacion2').style.display='none'</script>";
			}
		}
	}else{
		//si sub tiene valor vacio se inicializa la variable de sesion alb
		if($_POST['sub'] != "true" && $_POST['sub']!="false" && $_POST['sub'] == ""){
			$_SESSION['alb']=Array();
		}
		//
		if(!isset($_POST['sub'])){
			$_SESSION['alb']=Array();
		}
	}
//En caso de realizarse una modificacion, se llama a la funcion paises
if(isset($_GET['modificar']) && $_GET['modificar'] == "si" && $_GET['dni'] != ""){
	echo "<script>paises(0);</script>";
	//echo "<script>documentacion(0);</script>";
}
?>
<script>
	
	//Cambia  el valor de los campos ocultos "existe" (a la hora de realizar el alta en la base de datos segun el valor que tenga hará un insert o un update sobre la tabla cliente) , "sub" (Indica si se ha de mostrar el mapa de habitaciones, o si se debe realizar el alta), test_dni (Indica que se va a buscar en la base de datos un registro con dni similar para rellenar el formulario de lata) y finalmente envia el formulario 
	function dni_change(){
		if(document.forms[0].existe.value == "true"){
			document.forms[0].existe.value = "false";
		}
		if(document.forms[0].sub.value == "false"){
			document.forms[0].sub.value = "true";		
		}
		document.forms[0].test_dni.value = "1";
		if (validadni(0))
			document.forms[0].submit();
		
	}
</script>
<?php mysql_close($db);
}else{ //Muestro una ventana de error de permisos de acceso a la pagina	
	 echo "<div>
			<table border='0' id='tabla_detalles'>
				<tr id='titulo_tablas'>
					<td colspan='2'>
						<div style='width: 30px; height: 25px; background-image: url(../imagenes/img_tablas/esquina_arriba_izquierda.jpg); background-repeat: no-repeat; float: left;' id='alerta_esquina_izquierda'>&nbsp;</div>
						<div style='width: 290px; height: 25px; text-align: center; background-image: url(../imagenes/img_tablas/linea_horizontal.jpg); background-repeat: repeat-x; float: left;'>
							<div class='titulo' style='text-align: center;'>¡ERROR!</div>
						</div>
						<div style='width: 30px; height: 25px; background-image: url(../imagenes/img_tablas/esquina_arriba_derecha.jpg); float: left;'>&nbsp;</div>
					</td>
				</tr>
				<tr>
					<td width='350' align='center'>
						<label id='texto_detalles' style='color:red;'>NO TIENES PERMITIDO ACCEDER A ESTA SECCIÓN</label>
					</td>
				</tr>
			</table>
		</div>";
}
if (isset($_SESSION['reserva']) || trim($_GET['accion']) == 'reserva'){
	echo "<script> nuevo_habitaciones();</script>";
}
if ( trim($_GET['accion']) == 'reserva' && trim($_GET['Datos_Reserva']) == "true"){
	echo "<script> dni_change(); </script>";
}

?>