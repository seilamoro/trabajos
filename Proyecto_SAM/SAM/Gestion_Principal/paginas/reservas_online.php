<?php
	if(isset($_SESSION['permisoOnLine']) && $_SESSION['permisoOnLine']==true) //Comprobando que se tiene permiso para acceder a la pagina
	{
	@ $db = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'],  $_SESSION['conexion']['pass']);
	if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db($_SESSION['conexion']['db']);
	
	$sql_tipo_hab ="select * from tipo_habitacion where Reservable='S'";
	$result_tipo_hab = mysql_query($sql_tipo_hab);
	for ($i=0;$i<mysql_num_rows($result_tipo_hab);$i++){
			$fila=mysql_fetch_array($result_tipo_hab);
			$reservables[$i]=$fila['Id_Tipo_Hab'];
	}
	$paginas_validas = array();		//guardo en un array los número de las páginas que tiene algún tipo de habitación que sea reservable
	for ($i = 0; $i < count($_SESSION['pag_hab']); $i++){
		if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$paginas_validas) && isset($reservables)) {	//si la página no esta en las páginas validas
			$poner = false;
			$comprobar = $_SESSION['pag_hab'][$i]['pagina'];
			for($p = 0; $p < count($_SESSION['pag_hab']); $p++){ //compruebo si la página tiene alguna habitación reservable	
				if($_SESSION['pag_hab'][$p]['pagina'] == $comprobar && IN_ARRAY($_SESSION['pag_hab'][$p]['Id_Tipo_Hab'],$reservables)){
					$poner = true;
				}
			}
			if($poner){	//si la página tiene alguna habitación reservable es una página valida
				$paginas_validas[count($paginas_validas)] = $comprobar;
			}
		}
	}
	
	//Consulta para saber la pagina actual
	if (isset($_POST['dist_num_pag'])){
		$_SESSION['gdh']['dis_hab']['num_pag'] = $_POST['dist_num_pag'];
	}
	$pagina = $_SESSION['gdh']['dis_hab']['num_pag'];

	if(!IN_ARRAY($pagina,$paginas_validas)){ //si la página no es una página valida, se pone la 1º página
		$pagina = 1;
		$_SESSION['gdh']['dis_hab']['num_pag'] = 1;
	}		
	$habitaciones_orden = array();
	$numero_paginas = array();

	for ($i = 0; $i < count($_SESSION['pag_hab']); $i++) {
		if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$numero_paginas) && IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$paginas_validas)) {
		    $numero_paginas[] = $_SESSION['pag_hab'][$i]['pagina'];
		}
	  	if ($_SESSION['pag_hab'][$i]['pagina'] == $pagina) {
	  	  	$cont = count($habitaciones_orden);
		    $habitaciones_orden[$cont]['orden'] = $_SESSION['pag_hab'][$i]['orden'];
		    $habitaciones_orden[$cont]['Id_Tipo_Hab'] = $_SESSION['pag_hab'][$i]['Id_Tipo_Hab'];
			$habitaciones_orden[$cont]['pagina'] = $_SESSION['pag_hab'][$i]['pagina'];
		}
	}	

	foreach ($habitaciones_orden as $llave => $fila) {
	   $orden[$llave]  = $fila['orden'];
	}

	if (count($habitaciones_orden) > 0) {
		@ ARRAY_MULTISORT($orden, SORT_DESC, $habitaciones_orden);
	}
	

?><head>
<META NAME="Author" CONTENT="Javier Mateos Sabel">


<link rel="stylesheet" type="text/css" href="css/habitaciones_online.css">
<link rel="stylesheet" type="text/css" href="css/habitacionesColores.css">
</head>

<script src="./paginas/capas.js" type="text/javascript"></script>

<script language="javascript">
	// definimos las variables que almacenaran los componentes de la fecha actual
	ahora = new Date();
	ahora.setDate(ahora.getDate()+7);
	ahoraDay = ahora.getDate();
	ahoraMonth = ahora.getMonth();
	ahoraYear = ahora.getYear();

	// Nestcape Navigator 4x cuenta el anyo a partir de 1900, por lo que es necesario 
	// sumarle esa cantidad para obtener el anyo actual adecuadamente
	if (ahoraYear < 2000)
		ahoraYear += 1900;
	
	//Esta función resalta la fila del 'listado de reservas on-line' que recibe por parámetro
	function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#3F7BCC';
	  	tr.style.color = '#F4FCFF';
	}
	
	//Esta función vuelve a poner el color anterior a la fila del 'listado de reservas on-line' que recibe por parámetro
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
		
	// Esta función cambia el estado del textarea de 'llega tarde'
	function estado_tarde(){
		if(document.getElementById("check_tarde").checked == true){	//si el check está seleccionado
			document.getElementById("tarde").disabled = false;		//se habilita el textarea
			document.getElementById("tarde").value = "";			//se pone vacio
			document.getElementById("tarde").focus();				//y se le da el foco
		}
		else{																					//si el check no está seleccionado
			document.getElementById("tarde").disabled = true;									//se deshabilita el textarea
			document.getElementById("tarde").value = "(Indique la hora aproximada de llegada)"; //y se pone un texto por defecto
		}
    }
	
	//Esta función cambia la clase de la celda, que recibe por parámetro, de 'distribución de habitaciones' por la clase de 'cama resaltada'.
	//Se llama cuando el ratón está encima de la celda
	function resaltar_celda(td) {
	  	document.getElementById(td.id).className = "cama_resaltada";
	}
	
	//Esta función cambia la clase de la celda, que recibe por parámetro, de 'distribución de habitaciones' por la clase correspondiente, dependiendo si estaba 
	//asignada a la reserva o no. Se llama cuando el ratón sale de la celda
	function desresaltar_celda(td) {
		ele = td.id;														//se toma el id de la celda
		nom = ele.substr(2).split("_");										//se corta por el guión bajo, para tener la habitación y el número de cama por separado
		ele = nom[0] + "-" + nom[1];										//para obtener el id del text correspondiente a la celda
		if(document.getElementById(ele).value=="1")							//si el valor del text es 1, es que la celda está asignada a la reserva
			document.getElementById(td.id).className = "cama_asignada";		//entonces que pone la clase de 'cama_asignada'
		else{															//si el valor del text no es 1, no está asignada a la reserva
			var exi = (document.getElementById("inc"+nom[0]+"-"+nom[1])) ? true:false; 
			if(exi)
				document.getElementById(td.id).className = "cama_temp";	//y se pone la clase ''
			else
				document.getElementById(td.id).className = "cama_libre_con";	//y se pone la clase 'cama_libre_con'
		}
	}
		
	// función para saber cuantos días tiene cada mes
	function cuantosDias(mes, anyo){
		var cuantosDias = 31;
		if (mes == "Abril" || mes == "Junio" || mes == "Septiembre" || mes == "Noviembre")
	  cuantosDias = 30;
		if (mes == "Febrero" && (anyo/4) != Math.floor(anyo/4))
	  cuantosDias = 28;
		if (mes == "Febrero" && (anyo/4) == Math.floor(anyo/4))
	  cuantosDias = 29;
		return cuantosDias;
	}
	
	// Esta función comprueba que el formulario se ha rellenado correctamente cuando se trata de un alta y lo envia, si no da un error. El parámetro busqueda indica si se tiene que volver a la página de busquedas o no
	function comprobar(busqueda){
		form = document.getElementById("form1");
		if(form.f_camas.value != "0"){
			alert("Faltan asignar " + form.f_camas.value + " cama/s compartida/s.");
		}
		else if(form.f_indi.value != "0"){
			alert("Faltan asignar " + form.f_indi.value + " cama/s individual/es.");
		}
		else if(form.es_inc_i.value != "0" || form.es_inc_c.value != "0"){
			llamar_dia();
		}
		else if(form.empleado_reserva.value == ""){
			alert("Rellene el nombre del empleado.");
			form.empleado_reserva.focus();
		}
		else{
			//pongo las habitaciones seleccionadas en el text de habitaciones
			camposTexto = form.elements; //recojo los elementos que hay en el formulario
			//--con esto se quitan las habitaciones deseleccionadas de la ultima fecha seleccionada, cuando el usuario se mueve por las paginas de 'distribución de habitaciones'			
			var cad = "";
			seleccionadas = Array();
			seleccionadas_dias = Array();
			fechas = document.getElementById("fec_selec").value.split("+"); //fechas en un array que contiene todas las fechas por las que el usuario ha pasado en 'distribución de habitaciones'
			if(document.getElementById("hab_selec").value != ""){ //si el text de habitaciones seleccionadas no está vacio
				seleccionadas_dias = document.getElementById("hab_selec").value.split("*"); //seleccionadas_dias es un array que contiene las camas seleccionadas separadas por días, la posición 0 de este array corresponde con las camas seleccionadas para la fecha 0 del array fechas y así con las demás posicioens
				if(document.getElementById("fec_selec").value == "") //si el text de fechas seleccionadas está vacio se toma la posición 0
					pos = 0;
				else												//si no se toma la ultima posición del array de fechas
					pos = fechas.length-1;
				if(seleccionadas_dias[pos] == "")	//si la posición seleccionada del array fechas está vacia se coge una posición menos
					pos --;
				seleccionadas = seleccionadas_dias[pos].split("-"); //seleccionadas es un array que tiene las camas seleccionadas de la fecha correspondiente, separadas
				for(cam = 0; cam < seleccionadas.length-1; cam++){ //recorro todas las camas seleccionadas
					nom1 = seleccionadas[cam].split("&");		//se corta por el & para obtener el número de habitación y de cama por separado
					nom = nom1[0] + "-" + nom1[1];			//se compone el nombre del text correspondiente a esa habitación y a esa cama
					var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento, en este caso el text de la celda
					if(exi){	//si existe y sigue estando seleccionada (el text de la celda vale 1), se mantiene en la cadena
						if(document.getElementById(nom) == "1")
							cad = cad + seleccionadas[cam] + "-";
					}
					else
						cad = cad + seleccionadas[cam] + "-"; //si no existe es un habitacion de otra ventana, por lo que tambien se mantiene
				}
			}
			document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas, para ello primero lo vacio
			for(dia = 0; dia < seleccionadas_dias.length-1; dia++){	//las habitaciones seleccionadas de todos los dias lo dejo igual, menos la última
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[dia] + "*";
			}
			document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
			seleccionadas = Array();
			seleccionadas = cad.split("-"); //recojo las camas seleccionadas para esta fecha
			cad1 = "";
			//pongo las camas que se han seleccionado en la fecha de 'distribución de habitaciones' en el text de habitaciones seleccionadas
			for(i = 0; i < camposTexto.length; i++){	//recorro todos los elementos del formulario
				trozo = camposTexto[i].name.split("-"); // por lo que recojo el número de habitacion y de la cama
				nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca
				var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda
				if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// y valor el text esta a 1, es que se ha seleccionado esa cama
					trozo = camposTexto[i].name.split("-"); // por lo que recojo el número de habitacion y de la cama
					cama = trozo[0] + "&" + trozo[1]; 		//compongo el nombre de la cama para ponerlo en el text de habitaciones seleccionadas
					enc = 0;
					for(cam = 0; cam < seleccionadas.length; cam++){	//busco la cama en el array de las camas que ya estan en el text para esta fecha
						if(seleccionadas[cam] == cama)	//si se encuentra
							enc = 1;					//se pone la bandera a 1
					}	
					if(enc == 0) //si no se encuentra la cama 
						form.hab_selec.value = form.hab_selec.value + cama + "-"; //lo añado al valor del text que recoge el número de las habitaciones seleccionadas
				}
			}
			//pongo la fecha seleccionada en el text de fechas
			form.fec_selec.value = form.fec_selec.value + form.fecha_ver.value + "+";
			if(busqueda == "si")	//si hay que volver a la página de busqueda
				form.busqueda.value = "si";	//se indica en el text
			form.submit();	//y se envia el formulario
		}
	}
	
	//Funcion que mueve el dia de la distribución de habitaciones poniendo la fecha seleccionada en el calendario, recibe por parámetro el dia, el mes y el año	
	function cambiar_dia(dia,mes,anio){
		if(eval(dia) < 10)			//si el dia es menor que 10 le añado el 0 del inicio
			dia = "0"+eval(dia);
		if(eval(mes) < 10)			//si el mes es menor que 10 le añado el 0 del inicio, para que quede en formato dd/mm/aaaa
			mes = "0"+eval(mes);
		fecha = dia + "/" + mes + "/" + anio;
		
		var exi = (document.getElementById("llega")) ? true:false; //si no existe este elemento es que no hay reservas online, por lo que no hay que hacer comprobaciones
		if(!exi){	
			fecha_cad = dia + "/" + mes + "/" + anio;		//creo la fecha que se va ha mostrar
			fecha_cad1 = dia + "-" + mes + "-" + anio;
			document.getElementById("fecha_ver").value = fecha_cad;		//y lo pongo en la fecha que se va ha ver
			document.getElementById("fecha_cal").value = fecha_cad1;	//y la que se envia al calendario
			document.getElementById("mov_cal").value='1'; 				// se indica que se esta moviendo el calendario
			document.getElementById("form1").submit();					//se envia el formulario
		}
		//si el elemento existe continua, haciendo las comprobaciones
		
		dia_sel = new Date(anio,mes-1,dia); //se crea la fecha seleccionada, la fecha de llegada y la fecha de salida
		dia_lle = new Date(document.getElementById("llega").value.substr(6,4),document.getElementById("llega").value.substr(3,2)-1,document.getElementById("llega").value.substr(0,2));
		dia_tem = eval(document.getElementById("llega").value.substr(0,2)) + eval(document.getElementById("pernocta").value) -1;
		dia_sal = new Date(document.getElementById("llega").value.substr(6,4),document.getElementById("llega").value.substr(3,2)-1,dia_tem);
		if(dia_sel > dia_sal || dia_sel < dia_lle){ // si la fecha seleccionada no está entre la fecha de llegada y de salida se da un error al usuario
			alert("La fecha seleccionada no está entre la fecha de llegada y de salida");
		}
		else if(document.getElementById("f_camas").value != "0"){ //si faltan camas por asignar se indica al usuario
			alert("Faltan asignar " + document.getElementById("f_camas").value + " cama/s compartida/s.");
		}
		else if(document.getElementById("f_indi").value != "0"){
			alert("Faltan asignar " + document.getElementById("f_indi").value + " cama/s individual/es.");
		}
		else{
			//pongo las habitaciones seleccionadas en el text de habitaciones
			camposTexto = document.getElementById("form1").elements; //recojo los elementos que hay en el formulario
			var hay = 0; //esta variable indica si se ha hecho algún cambio en el text de habitaciones seleccionadas
			//--con esto se quitan las habitaciones deseleccionadas de la ultima fecha seleccionada, cuando el usuario se mueve por las paginas de 'distribución de habitaciones'			

			var cad = "";
			seleccionadas = Array();
			seleccionadas_dias = Array();
			fechas = document.getElementById("fec_selec").value.split("+");//fechas en un array que contiene todas las fechas por las que el usuario ha pasado en 'distribución de habitaciones'
			if(document.getElementById("hab_selec").value != ""){//si el text de habitaciones seleccionadas no está vacio
				seleccionadas_dias = document.getElementById("hab_selec").value.split("*");	//seleccionadas_dias es un array que contiene las camas seleccionadas separadas por días, la posición 0 de este array corresponde con las camas seleccionadas para la fecha 0 del array fechas y así con las demás posicioens
				pos=fechas.length-1;	//se coge la ultima posición, que es la longuitud del array fechas
				seleccionadas = seleccionadas_dias[pos].split("-");//seleccionadas es un array que tiene las camas seleccionadas de la fecha correspondiente, separadas
				for(cam = 0; cam < seleccionadas.length-1; cam++){ //recorro todas las camas seleccionadas
					nom1 = seleccionadas[cam].split("&");		//se corta por el & para obtener el número de habitación y de cama por separado
					nom = nom1[0] + "-" + nom1[1];			//se compone el nombre del text correspondiente a esa habitación y a esa cama
					var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento, en este caso el text de la celda
					if(exi){	//si existe y sigue estando seleccionada (el text de la celda vale 1), se mantiene en la cadena
						if(document.getElementById(nom).value == "1")
							cad = cad + seleccionadas[cam] + "-";
					}
					else
						cad = cad + seleccionadas[cam] + "-"; //si no existe es un habitacion de otra ventana, por lo que tambien se mantiene
				}
			}
			
			document.getElementById("hab_selec").value  = "";	///recompongo el valor del text de las habitaciones seleeccionadas, para ello primero lo vacio
			for(e = 0; e < seleccionadas_dias.length-1; e++){	//las habitaciones seleccionadas de todos los dias lo dejo igual, menos la última
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
			}
			document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
			seleccionadas = Array();
			seleccionadas = cad.split("-"); //recojo las camas seleccionadas para esta fecha
			cad1 = "";
			//pongo las camas que se han seleccionado en la fecha de 'distribución de habitaciones' en el text de habitaciones seleccionadas
			for(i = 0; i < camposTexto.length; i++){		//recorro todos los elementos del formulario
				trozo = camposTexto[i].name.split("-"); 	//por lo que recojo el número de habitacion y de la cama	
				nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca	
				var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda
				if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// y valor el text esta a 1, es que se ha seleccionado esa cama
					cama = trozo[0] + "&" + trozo[1]; 	//compongo el nombre de la cama para ponerlo en el text de habitaciones seleccionadas
					enc = 0;
					for(e = 0; e < seleccionadas.length; e++){ //busco la cama en el array de las camas que ya estan en el text para esta fecha
						if(seleccionadas[e] == cama)	//si se encuentra
							enc = 1;					//se pone la bandera a 1
					}
					if(enc == 0)//si no se encuentra la cama 
						cad1 = cad1 + cama + "-"; // lo añado al valor de hidden que recoge el número de las habitaciones seleccionadas
					hay = 1; //indico que se ha hecho un cambio en el text de habitaciones seleccionadas
				}
			}
			if(hay == 1){//si hay alguna habitación seleccionada se pone la fecha en el text de fechas
					document.getElementById("fec_selec").value = document.getElementById("fec_selec").value + document.getElementById("fecha_ver").value + "+";
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad1 + "*"; //cierro las habitaciones seleccionadas para el dia, indicandolo con un asterisco
					fechas1 = document.getElementById("fec_selec").value.split("+"); //fechas1 es un array que contiene todas las fechas que el usuario ha visto en 'distribución de habitaciones'
					enc = 0;
					for(e = 0; e < fechas1.length; e++){	//se busca la fecha que se va ha ver en el text de fechas seleccionadas
						if(fechas1[e] == fecha)
							enc = 1;
					}
					if(enc == 0){	//si no se encuentra la fecha que se va ha ver, se le pone las camas igual que el dia inferior, si existe no se hace nada
						seleccionadas_dias1 = document.getElementById("hab_selec").value.split("*");
						pos = seleccionadas_dias1.length - 2;	//tomo la posicion del ultimo elemento
						tem_dia = fecha.substr(0,2);			//recojo el dia, mes y año de la fecha
						tem_mes = eval(fecha.substr(3,2))+1		//se pone un mes más, ya que enero es el mes cero, no el uno
						tem_ayo = fecha.substr(6,4);
						fecha_act = new Date(tem_ayo,tem_mes,tem_dia);	//creo la fecha de del la ultima posición
						fecha_tem = new Date(1900,1,1);
						for(e = 0; e < fechas1.length; e++){	//busco el dia inferior al dia que se va a ver
							tem_dia = fechas1[e].substr(0,2);
							tem_mes = eval(fechas1[e].substr(3,2))+1;	//creo la fecha para la posicion actual
							tem_ayo = fechas1[e].substr(6,4);
							fecha_ver = new Date(tem_ayo,tem_mes,tem_dia);							
							if(fecha_ver < fecha_act && fecha_ver > fecha_tem){	//si la fecha se menor que la fecha que se va ha ver e mayor a la ultima fecha encontrada
								pos = e;
								fecha_tem = fecha_ver;
							}
						}	//si se encuentra el dia inferior se pone al dia que se va a ver las mismas camas, si no se ponen las de la ultima fecha
						document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias1[pos];
					}
			}
			cad_dia = dia;
			cad_mes = mes;
			cad_ayo = anio;
			if(eval(cad_dia) < 10)			//si el dia es menor que 10 le añado el 0 del inicio
				cad_dia = "0"+eval(cad_dia);
			if(eval(cad_mes) < 10)			//si el mes es menor que 10 le añado el 0 del inicio, para que quede en formato dd/mm/aaaa
				cad_mes = "0"+eval(cad_mes);	
			fecha_cad = cad_dia + "/" + cad_mes + "/" + cad_ayo;		//creo la fecha que se va ha mostrar
			fecha_cad1 = cad_dia + "-" + cad_mes + "-" + cad_ayo;
			document.getElementById("fecha_ver").value = fecha_cad;		//y lo pongo en la fecha que se va ha ver
			document.getElementById("fecha_cal").value = fecha_cad1;	//y la que se envia al calendario
			document.getElementById("mov_cal").value='1'; 				// se indica que se esta moviendo el calendario
			document.getElementById("form1").submit();					//se envia el formulario
		}
	}
	
	//mueve la pagina de la distribucion de habitaciones, recibe por parámetro el número de página a la que se va ha mover
	function cambiar_pagina_dis(num_pag){
		fechas = document.getElementById("fec_selec").value.split("+");//fechas en un array que contiene todas las fechas por las que el usuario ha pasado en 'distribución de habitaciones'
		camposTexto = document.getElementById("form1").elements; //recojo los elementos que hay en el formulario
		var hay = 0;
		var cad = "";
		seleccionadas = Array();
		seleccionadas_dias = Array();
		fechas = document.getElementById("fec_selec").value.split("+");//fechas en un array que contiene todas las fechas por las que el usuario ha pasado en 'distribución de habitaciones'
		if(document.getElementById("hab_selec").value != ""){//si el text de habitaciones seleccionadas no está vacio
			seleccionadas_dias = document.getElementById("hab_selec").value.split("*");		//seleccionadas_dias es un array que contiene las camas seleccionadas separadas por días, la posición 0 de este array corresponde con las camas seleccionadas para la fecha 0 del array fechas y así con las demás posicioens
			if(document.getElementById("fec_selec").value == "")//si el text de fechas seleccionadas está vacio se toma la posición 0
				pos = 0;
			else												//si no se toma la ultima posición del array de fechas
				pos = fechas.length-1;
			if(seleccionadas_dias[pos] == "")
				pos --;
			pos = seleccionadas_dias.length-1;
			var exi = (seleccionadas_dias[pos]) ? true:false;
			if(exi){
				seleccionadas = seleccionadas_dias[pos].split("-");//seleccionadas es un array que tiene las camas seleccionadas de la fecha correspondiente, separadas
				for(cam = 0; cam < seleccionadas.length-1; cam++){ //recorro todas las camas seleccionadas
					nom1 = seleccionadas[cam].split("&");		//se corta por el & para obtener el número de habitación y de cama por separado
					nom = nom1[0] + "-" + nom1[1];			//se compone el nombre del text correspondiente a esa habitación y a esa cama
					var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento, en este caso el text de la celda
					if(exi){	//si existe y sigue estando seleccionada (el text de la celda vale 1), se mantiene en la cadena
						if(document.getElementById(nom).value == "1")
							cad = cad + seleccionadas[cam] + "-";
					}
					else
						cad = cad + seleccionadas[cam] + "-"; //si no existe es un habitacion de otra ventana, por lo que tambien se mantiene
				}
			}
		}
		document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas
		for(e = 0; e < seleccionadas_dias.length-1; e++){	//todas menos la ultima la dejo igual
			document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
		}
		document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
		seleccionadas = Array();
		seleccionadas = cad.split("-");//recojo las camas seleccionadas para esta fecha
		for(i = 0; i < camposTexto.length; i++){	//recorro todos los elementos del formulario
			trozo = camposTexto[i].name.split("-"); //por lo que recojo el número de habitacion y de la cama
			nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca
			var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda
			if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// y valor el text esta a 1, es que se ha seleccionado esa cama
				cama = trozo[0] + "&" + trozo[1];		//compongo el nombre de la cama para ponerlo en el text de habitaciones seleccionadas
				enc = 0;
				for(e = 0; e < seleccionadas.length; e++){ //busco la cama en el array de las camas que ya estan en el text para esta fecha
					if(seleccionadas[e] == cama)	//si se encuentra
						enc = 1;
				}
				if(enc == 0)//si no se encuentra la cama 
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cama + "-"; // lo añado al valor de hidden que recoge el número de las habitaciones seleccionadas
			}
		}
		document.getElementById("dist_num_pag").value=num_pag; // se indica que se esta la pagina
		document.getElementById("mov_cal").value='1'; // se indica que se esta moviendo el calendario
		document.getElementById("mover_pag").value='1'; // y la pagina
		document.getElementById("form1").submit();
	}
	
	//Esta función prepara la página antes de ordendar el 'listado de reservas on-line', para mantener las camas seleccionadas, recibe por parámetro el criterio de ordenación
	function ordenar_listado(valor){
			camposTexto = document.getElementById("form1").elements; // recojo los elementos del formulario
			var hay = 0;
			for(i = 0; i < camposTexto.length; i++){	//recorro todos los elementos del formulario
				trozo = camposTexto[i].name.split("-"); //por lo que recojo el número de habitacion y de la cama
				nombre = "td" + trozo[0] + "_" + trozo[1];//compongo el nombre de la celca
				var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda
				if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// y valor el text esta a 1, es que se ha seleccionado esa cama
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + trozo[0] + "&" + trozo[1] + "-"; 		//compongo el nombre de la cama para ponerlo en el text de habitaciones seleccionadas
					hay = 1; //indico que se ha hecho un cambio
				}
			}
			if(hay == 1){//si hay alguna cama seleccionada se cierra las camas de este dia y pone la fecha en el text de fechas
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + "*";
				document.getElementById("fec_selec").value = document.getElementById("fec_selec").value + document.getElementById("fecha_ver").value + "+";
			}
			document.getElementById("ordenar").value=valor; // se indica que orden tiene que tener el listado
			document.getElementById("cambiar_orden").value='1'; // y que se ha cambiado el orden del listado
			document.getElementById("mov_cal").value='1'; // se indica que se esta moviendo el calendario
			
			document.getElementById("form1").submit();
	}
	
	// Esta función comprueba que el formulario se ha rellenado correctamente cuando se trata de una modificación 
	// y lo envia, si no da un error, el parámetro busqueda indica si se tiene que ir a la pagina de busqueda o no
	function comprobar_mod(busqueda){
		form = document.form1;
		if(form.nombre.value == ""){
			alert("Falta rellenar el nombre");
			form.nombre.focus();
		}
		else if(form.apellido1.value == ""){
			alert("Falta rellenar el primer apellido.");
			form.apellido1.focus();
		}
		else if(form.telefono.value == "" || form.telefono.value.length < 9){
			alert("Falta rellenar el teléfono.");
			form.telefono.focus();
		}
		else if(form.email.value == ""){
			alert("Falta rellenar el E-mail.");
			form.email.focus();
		}
		else if(c_mail(form.email.value) == false){
			alert("Rellene correctamente el E-mail.");
			form.email.focus();
			form.email.select(); 
		}
		else if(isNaN(form.ing.value) || eval(form.ing.value < 0)){
			alert("Rellene correctamente la cantidad ingresada.");
			form.ing.focus();
			form.ing.select(); 
		}			
		else if (isNaN(form.dias.value) || eval(form.dias.value < 1)){
			alert("El número de días debe ser un número mayor de cero.");
			form.dias.focus();
			form.dias.select(); 
		}
		else if (isNaN(form.camas.value) || eval(form.camas.value < 1)){
			alert("El número de camas debe ser un número mayor de cero.");
			form.camas.focus();
			form.camas.select(); 
		}
		else if (isNaN(form.compartidas.value) || eval(form.compartidas.value < 0)){
			alert("El número de camas compartidas debe ser un número mayor de cero.");
			form.compartidas.focus();
			form.compartidas.select(); 
		}
		else if (isNaN(form.individuales.value) || eval(form.individuales.value < 0)){
			alert("El número de días debe ser un número mayor de cero.");
			form.individuales.focus();
			form.individuales.select(); 
		}
		else if(eval(form.camas.value) != eval(form.individuales.value) + eval(form.compartidas.value)){
			alert("El número de camas es incorrecto.");
			form.camas.focus();
			form.camas.select(); 
		}
		else if(form.check_tarde.checked == true && form.tarde.value == ""){
			alert("Falta rellenar la hora de llegada.");
			form.tarde.focus();
		}
		else{
			if(busqueda == "si")
				form.busqueda.value = "si";	//se indica que hay de ir a la página de busqueda si el parámetro vale 'si'
			form.submit();
		}
	}
	
	// Esta función comprueba que sea correcto el formato del email introducido
	function c_mail(texto){ 
		var mailres = true;             
		var cadena = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890@._-"; 
		var arroba = texto.indexOf("@",0); 
		if ((texto.lastIndexOf("@")) != arroba) arroba = -1; 
			var punto = texto.lastIndexOf("."); 	 
			for (var contador = 0 ; contador < texto.length ; contador++){ 
				if (cadena.indexOf(texto.substr(contador, 1),0) == -1){ 
					mailres = false; 
					break; 
				} 
			} 
		if ((arroba > 1) && (arroba + 1 < punto) && (punto + 1 < (texto.length)) && (mailres == true) && (texto.indexOf("..",0) == -1)) 
		 mailres = true; 
		else 
		 mailres = false; 			 
		return mailres; 
	}
	
	// Esta función pide confirmación de la anulación de la solicitud, si el usuario acepta llama a la misma pagina indicando que
	// se debe cancelar la solicitud indicada, el parámetro busqueda indica si se tiene que ir a la página de busqueda o no
	function confirmar(dni,llega,busqueda){
		if(confirm("¿Está seguro de que desea eliminar la solicitud de reserva On-Line?")){
			if(busqueda == "si")	//si ha confirmado la cancelación se llama a la página indicando que reserva on-line tiene que eliminar, y si el busqueda es 'si' que tiene que volver a la página de busqueda
				location.href="?pag=reservas_online.php&dni=" + dni + "&llega=" + llega + "&canc=True&busqueda=si";
			else
				location.href="?pag=reservas_online.php&dni=" + dni + "&llega=" + llega + "&canc=True";
		}
		else{
			if(busqueda == "si")	//si no se ha confirmado la cancelación se va ha la página de busqueda, si el parámetro vale 'si', si no, no se hace nada
				location.href="?pag=listado_criterio_reservas_online.php";
		}
	}
	
	//Esta funcion se llama cuando se pulsa el boton cancelar en tratar reserva online
	function cancelar(busqueda){ //si el parámetro busqueda vale 'si' se vuelve a la página de busqueda y si no se queda en reservas online
		if(busqueda == "si")
			location.href="?pag=listado_criterio_reservas_online.php";
		else
			location.href="?pag=reservas_online.php";
	}
	
	// Si se cancela una modificación se llama a la pagina anterior
	function cancelar_mod(loca,bus){ //si el parámetro busqueda vale 'si' se vuelve a la página de busqueda y si no se queda en reservas online
		if(bus != "si")
			location.href="?pag=reservas_online.php&ver=" + loca;
		else
			location.href="?pag=listado_criterio_reservas_online.php";
	}

	function poner_cambio_hab(fecha, hab, tipo, indi){
		dias_cam = document.getElementById("cambio_tipo").value.split("*");	//pongo todas las fecha que hay hasta el momento en un array
		exi = 0;			//esta variable dice si la fecha que se va ha poner ya esta puesta o no
		for(i = 0; i < dias_cam.length; i++){	//recorro todas las fechas
			if((hab + "-" + fecha + "-" + tipo + "-" + indi) == dias_cam[i]){			//y si ya existe lo indico
				exi = 1;
			}
		}
		if(exi == 0)	//si la fecha ya esta en el text no se hace nada, y si aun no esta se pone, el guión sirve para separar una fecha de otra
			document.getElementById("cambio_tipo").value = document.getElementById("cambio_tipo").value + hab + "-" + fecha + "-" + tipo + "-" + indi + "*";
	}

	function quitar_cambio_hab(fecha, hab){
		camposTexto = document.getElementById("form1").elements; //recojo los elementos que hay en el formulario
		enc = 0;	//esta variable dice si hay alguna cama seleccionada con la misma fecha que la que se busca
		for(i1 = 0; i1 < camposTexto.length; i1++){	//recorro todos los elementos del formulario
			trozo = camposTexto[i1].name.split("-"); //por lo que recojo el número de habitacion y de la cama
			nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca
			var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda,
			var exi1 = (document.getElementById("inc"+trozo[0]+"-"+trozo[1])) ? true:false; //es una cama incompleta,
			if(exi && exi1 && camposTexto[i1].type=='hidden' && camposTexto[i1].value=='1'){	//y está seleccionada
				fecha_tem = document.getElementById("inc_fecha"+trozo[0]+"-"+trozo[1]).value;	//compongo la fecha de la cama, ya que esta en formato aaaa-mm-dd
				fecha_tem1 = fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4);
				if(fecha_tem1 == fecha && hab == "")	//compruebo si la fecha es la misma que la que se está buscando y si es asi se indica
					enc = 1;
				else if(fecha_tem1 == fecha && hab == trozo[0])
					enc = 1;
			}
		}
		if(enc == 0){	//si no se ha encontrado ninguna cama incompleta seleccionada con la misma fecha que la que se buscaba, entonces se quita del text de fecha de las camas incompletas
			cad = "";
			dias_cam = document.getElementById("cambio_tipo").value.split("*");//pongo todas las fecha que hay hasta el momento en un array
			for(i2 = 0; i2 < dias_cam.length-1; i2++){	//recorro todas las fechas
				dias_cam1 = dias_cam[i2].split("-");
				if(hab != "" && ((hab+"-"+fecha) != (dias_cam1[0]+"-"+dias_cam1[1]))){	//si es distinta a la que se quiere quitar se deja en la cadena
					cad = cad + dias_cam[i2] + "*";
				}else if(fecha != dias_cam1[1]){
					cad = cad + dias_cam[i2] + "*";
				}
			}
			document.getElementById("cambio_tipo").value = cad;	//y se cambia el valor del text
		}
	}
		
	//Esta función pone una fecha en el text que guarda las fechas de las camas incompletas,  el parámetro fecha indica la fecha a poner (dd/mm/aaaa)
	function poner_dia(fecha){
		dias_inc = document.getElementById("ir_a_dia").value.split("-");	//pongo todas las fecha que hay hasta el momento en un array
		exi = 0;			//esta variable dice si la fecha que se va ha poner ya esta puesta o no
		for(i = 0; i < dias_inc.length; i++){	//recorro todas las fechas
			if(fecha == dias_inc[i]){			//y si ya existe lo indico
				exi = 1;
			}
		}
		if(exi == 0)	//si la fecha ya esta en el text no se hace nada, y si aun no esta se pone, el guión sirve para separar una fecha de otra
			document.getElementById("ir_a_dia").value = document.getElementById("ir_a_dia").value + fecha + "-";
	}
	
	//Esta función quita una fecha en el text que guarda las fechas de las camas incompletas, siempre que no se necesite, el parámetro fecha indica la fecha a quitar (dd/mm/aaaa)
	function quitar_dia(fecha){
		camposTexto = document.getElementById("form1").elements; //recojo los elementos que hay en el formulario
		enc = 0;	//esta variable dice si hay alguna cama seleccionada con la misma fecha que la que se busca
		for(i1 = 0; i1 < camposTexto.length; i1++){	//recorro todos los elementos del formulario
			trozo = camposTexto[i1].name.split("-"); //por lo que recojo el número de habitacion y de la cama
			nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca
			var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda,
			var exi1 = (document.getElementById("inc"+trozo[0]+"-"+trozo[1])) ? true:false; //es una cama incompleta,
			if(exi && exi1 && camposTexto[i1].type=='hidden' && camposTexto[i1].value=='1'){	//y está seleccionada
				fecha_tem = document.getElementById("inc_fecha"+trozo[0]+"-"+trozo[1]).value;	//compongo la fecha de la cama, ya que esta en formato aaaa-mm-dd
				fecha_tem1 = fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4);
				if(fecha_tem1 == fecha)	//compruebo si la fecha es la misma que la que se está buscando y si es asi se indica
					enc = 1;
			}
		}
		if(enc == 0){	//si no se ha encontrado ninguna cama incompleta seleccionada con la misma fecha que la que se buscaba, entonces se quita del text de fecha de las camas incompletas
			cad = "";
			dias_inc = document.getElementById("ir_a_dia").value.split("-");//pongo todas las fecha que hay hasta el momento en un array
			for(i2 = 0; i2 < dias_inc.length-1; i2++){	//recorro todas las fechas
				if(dias_inc[i2] != fecha){	//si es distinta a la que se quiere quitar se deja en la cadena
					cad = cad + dias_inc[i2] + "-";
				}
			}
			document.getElementById("ir_a_dia").value = cad;	//y se cambia el valor del text
		}
	}
	
	//Esta función busca el menor de los días en el text que guarda las fechas de las camas incompletas, y llama a cambiar dia pasandole la fecha con la que se tiene que actualizar la página
	function llamar_dia(){
		if(confirm("Quedan días de la estancia por asignar, ¿desea continuar?")){
			dias_inc = document.getElementById("ir_a_dia").value.split("-");//pongo todas las fecha que hay hasta el momento en un array
			menor = new Date(9999,12,31);	//esta variable guarda la fecha mas pequeña del array
			for(i = 0; i < dias_inc.length-1; i++){	//recorro todas la fechas
				fecha_act = new Date(dias_inc[i].substring(6,10),dias_inc[i].substring(3,5),dias_inc[i].substring(0,2));//creo la fecha actual para compararla con la fecha menor
				if(fecha_act < menor){	//si la fecha actual es menor que la mas pequeña encontrada hasta el momento
					menor = fecha_act;	//encontes la acutal será la menor
				}
			}
			cad_fecha = menor.getDate() + "/" + menor.getMonth() + "/" + menor.getYear(); //la fecha menor hay que quitarla del text que guarda las fechas de las camas incompletas
			cad = "";
			for(i = 0; i < dias_inc.length-1; i++){//recorro todas las fechas
				if(dias_inc[i] != cad_fecha){	//si es distinta a la que se quiere quitar se deja en la cadena
					cad = cad + dias_inc[i] + "-";
				}
			}
			document.getElementById("ir_a_dia").value = cad;	//y se cambia el valor del text
			cambiar_dia(menor.getDate(),menor.getMonth(),menor.getYear()); //y se llama a cambiar día
		}
	}
	
	// Esta función asigna y desasigna camas, recibe la habitacion y la cama seleccionada, si se esta modificando o no,
	// el numero de resultado de la busqueda y si es una cama libre en todos los días o no y los dias que esta ocupada
	function asignar_cama(hab, cam, td, mod, nume, com, ini, fin, tipo, cambio){	
		if(mod != "si" && nume != "0"){ // si se está modificando o no se ha encontrado la solicitud, no se deja asignar camas
			var n = hab+"-"+cam; // n recoge el nombre del hidden que se va ha usar
			if(document.getElementById(n).value != "0"){ // si el valor del hidden es distinto a cero es que se esta desasignando
				var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false; 
				if(exi)
					document.getElementById(td.id).className = "cama_temp";	//y se pone la clase 'cama_temp'
				else
					document.getElementById(td.id).className = "cama_libre_con";	//y se pone la clase 'cama_libre_con'
				if(tipo == 'N'){	// si la habitacion es de tipo individual
					document.getElementById("f_indi").value = eval(document.getElementById("f_indi").value) + 1; // se aumentan en 1 las camas individuales	que faltan por asignar
					if(com != '0') //si la cama esta ocupada para mas adelante de la fecha de llegada
						document.getElementById("es_inc_i").value = eval(document.getElementById("es_inc_i").value) - 1; //se quita una de las camas individuales incompletas
				}
				else{	// si no se aumentan en 1 las camas compartidas que faltan por asignar
					document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) + 1;
					if(com != '0')//si la cama esta ocupada para mas adelante de la fecha de llegada
						document.getElementById("es_inc_c").value = eval(document.getElementById("es_inc_c").value ) - 1;//se quita una de las camas compartidas incompletas
				}
				document.getElementById(n).value = 0;
				var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false;
				if(exi){
					fecha_tem = document.getElementById("inc_fecha"+hab+"-"+cam).value; 
					quitar_dia(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4));
					if(cambio)																	
						quitar_cambio_hab(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4), hab);
				}
			}else{ // si el valor del hidden es cero se esta asignando una cama
				if(tipo == 'N'){ // si es de tipo indidivual
					if(document.getElementById("f_indi").value != "0"){ // y aun quedan camas indidivuales por asignar
						hacer = 0
						var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false;
						if(exi && com != '0'){ //si la cama esta ocupada para mas adelante de la fecha de llegada
							ini = ini.substr(8,2)+"/"+ini.substr(5,2)+"/"+ini.substr(0,4);
							fin = fin.substr(8,2)+"/"+fin.substr(5,2)+"/"+fin.substr(0,4);
							if(cambio){
								mens = "La habitación cambiará de tipo a partir del " + ini + ", ¿desea continuar?"
							}else{
								mens = "La cama estará ocupada a partir del " + ini + ", ¿desea continuar?"
							}
							if(confirm(mens)){
								poner_dia(ini);
								if(cambio)										
									poner_cambio_hab(ini, hab, cambio, tipo); 
								document.getElementById("es_inc_c").value = eval(document.getElementById("es_inc_c").value ) + 1;//se aumenta el contador de camas compartidas incompletas se han asignado
							}else{
								hacer = 1;
							}
						}
						if(hacer == 0){
							document.getElementById("f_indi").value = eval(document.getElementById("f_indi").value) - 1; // se disminuye en 1 las camas individuales que faltan por aginar
							document.getElementById(n).value = 1; // se cambia el valor de hidden a 1
							document.getElementById("td"+hab+"_"+cam).className = "cama_asignada"; // y se cambia el color de la celda seleccionada
						}
					}
				}
				else{ // si es de tipo compartida
					if(document.getElementById("f_camas").value != "0"){ // y aun quedan camas compartidas por asignar
						hacer = 0
						var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false;
						if(exi && com != '0'){ //si la cama esta ocupada para mas adelante de la fecha de llegada
							ini = ini.substr(8,2)+"/"+ini.substr(5,2)+"/"+ini.substr(0,4);
							fin = fin.substr(8,2)+"/"+fin.substr(5,2)+"/"+fin.substr(0,4);
							if(cambio){
								mens = "La habitación cambiará de tipo a partir del " + ini + ", ¿desea continuar?"
							}else{
								mens = "La cama estará ocupada a partir del " + ini + ", ¿desea continuar?"
							}
							if(confirm(mens)){
								poner_dia(ini);
								if(cambio)											
									poner_cambio_hab(ini, hab, cambio, tipo); 
								document.getElementById("es_inc_c").value = eval(document.getElementById("es_inc_c").value ) + 1;//se aumenta el contador de camas compartidas incompletas se han asignado
							}else{
								hacer = 1;
							}
						}
						if(hacer == 0){
							document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) - 1; // se disminuye en 1 las camas compartidas que faltan por aginar
							document.getElementById(n).value = 1; // se cambia el valor de hidden a 1
							document.getElementById("td"+hab+"_"+cam).className = "cama_asignada"; // y se cambia el color de la celda seleccionada
						}
					}
				}
				if(document.getElementById("ir_a_dia").value != "" && document.getElementById("f_camas").value == "0" && document.getElementById("f_indi").value == "0")
					llamar_dia();
			}
		}
	}
	
	//Esta función asigna o desasigna todas las camas de la habitación a la reserva, recibe la habitación seleccionada, si se esta modificando o no,
	// el numero de resultado de la busqueda, el número de camas de la habitación, y el tipo de habitación
	function asignar_hab(hab, mod, nume, camas, tipo, cambio, tipo_hab){	
		if(mod != "si" && nume != "0"){ // si se está modificando o no se ha encontrado la solicitud, no se deja asignar camas
			if(tipo == 'N') 		//si el tipo en 'N' es que es una habitación de tipo individual
				maxi = eval(document.getElementById("f_indi").value);	//maxi es el número de camas que se asignan como máximo, que será el numero de camas que faltan por asignar de este tipo
			else					//si no es una habitación de tipo compartida
				maxi = eval(document.getElementById("f_camas").value);
			asig = 0;	//asig dice se pueden o no asignar camas
			con = 1;	//cuenta el número de camas asignadas
			for(i = 1; i <= camas; i++){	//recorro las celdas de la habitación cogiendo
				nom = "td"+hab+"_"+i;		//compongo el nombre de la celda
				if(con <= maxi){			//si no he llegado al maximo de camas por asignar
					var exi = (document.getElementById(nom)) ? true:false; // y
					if(exi && document.getElementById(hab+"-"+i).value=='0'){//si existe la celda correspondiente a la cama y si text vale 0 (la cama no está seleccionada);
						asig = 1;	//indico que se ha asignado una cama libre
						con ++;		//y aumento en 1 el número de camas asignadas
					}
				}
			}
			if(asig == 0){ //si no se pueden asignar camas, entonces desasigno las camas de la habitación	
				for(i = 1; i <= camas; i++){	//recorro las celdas de la habitación
					nom = "td"+hab+"_"+i;		//compongo el nombre de la celda
					var exi = (document.getElementById(nom)) ? true:false;
					if(exi && document.getElementById(hab+"-"+i).value=='1'){//si existe la celda correspondiente a la cama y su text vale 1 (cama seleccionada)
						document.getElementById(hab+"-"+i).value='0';	//se pone su text a 0 para indicar que esta libre
						var exi = (document.getElementById("inc"+hab+"-"+i)) ? true:false; 
						if(exi){
							document.getElementById(nom).className = "cama_temp";	//y se pone la clase 'cama_temp'
							fecha_tem = document.getElementById("inc_fecha"+hab+"-"+i).value; 
							quitar_dia(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4));
							if(cambio)																	
								quitar_cambio_hab(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4), hab);	
						}else
							document.getElementById(nom).className = "cama_libre_con";	//y se pone la clase 'cama_libre_con'
						if(tipo == 'N')	//si el tipo de habitacion es 'N'es que es una habitación de tipo individual
							document.getElementById("f_indi").value = eval(document.getElementById("f_indi").value) + 1; //se aumenta en 1 el número de camas por asignar en el text correspondiente
						else		//si no es una habitación de tipo compartida
							document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) + 1;
					}
				}
			}else{ //si se pueden asignar camas, entonces...	
				con = 1;
				for(i = 1; i <= camas; i++){	//recorro las celdas de la habitación cogiendo solo las camas libres todos los días de la reserva
					nom = "td"+hab+"_"+i;		//compongo el nombre de la celda
					if(con <= maxi){		// si no he llegado al maximo de camas por asignar
						var exi = (document.getElementById(nom)) ? true:false;
						if(exi && document.getElementById(hab+"-"+i).value!='1'){//si existe la celda correspondiente a la cama y su text no vale 1 (cama no seleccionada)
							var exi = (document.getElementById("inc"+hab+"-"+i)) ? true:false;  //si existe el text 'inc-num hab-nun cam' es una cama no disponible todos los días
							if(!exi){	//si no es incompleta
								document.getElementById(nom).className = "cama_asignada"; //se le pone la clase 'cama asignada'
								document.getElementById(hab+"-"+i).value='1';	//y se pone su hidden a 1 para indicar que se ha seleccionado
								if(tipo == 'N')	//si el tipo de habitacion es 'N'es que es una habitación de tipo individual
									document.getElementById("f_indi").value = eval(document.getElementById("f_indi").value) - 1; //se quita una cama por asignar en el text correspondiente
								else		//si no es una habitación de tipo compartida
									document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) - 1;
								con++;
							}
						}
					}
				}
				if(eval(document.getElementById("f_indi").value) != 0 || eval(document.getElementById("f_camas").value) != 0){ //si faltan camas por asinar
					mens = 0;
					contador=0;
					for(i4 = 1; i4 <= camas; i4++){	// se recorre otra vez todas las celdas de la habitación mirando las que no estan disponibles todos los días
						nom = "td"+hab+"_"+i4;
						if(con <= maxi){						//Funciona igual que el buque anterior
							var exi = (document.getElementById(nom)) ? true:false; 
							if(exi && document.getElementById(hab+"-"+i4).value!='1'){
								var exi = (document.getElementById("inc"+hab+"-"+i4)) ? true:false;
								if(exi){
									if(tipo == 'N')
										document.getElementById("es_inc_i").value = eval(document.getElementById("es_inc_i").value) + 1;
									else
										document.getElementById("es_inc_c").value = eval(document.getElementById("es_inc_c").value) + 1;
								}
								document.getElementById(nom).className = "cama_asignada"; 
								document.getElementById(hab+"-"+i4).value='1';
								if(tipo == 'N')
									document.getElementById("f_indi").value = eval(document.getElementById("f_indi").value) - 1;
								else
									document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) - 1;
								con++;
								contador++;
								mens = 1;
								fecha_tem = document.getElementById("inc_fecha"+hab+"-"+i4).value; 
								poner_dia(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4));
								ini = cambio.substr(8,2)+"/"+cambio.substr(5,2)+"/"+cambio.substr(0,4);	
								if(cambio)											
									poner_cambio_hab(ini, hab, tipo_hab, tipo);
							}
						}
					}
					if(mens == 1){
						alert(contador + " camas no están disponibles todos los días."); //y se le indica al usuario
					}
				}
				if(document.getElementById("ir_a_dia").value != "" && document.getElementById("f_camas").value == "0" && document.getElementById("f_indi").value == "0")
					llamar_dia();
			}		
		}		
	}		
	
	//cambia el mensaje de la barra de estado dependiendo de si la cama está seleccionada o no, recibe la celda, la habitación, la cama, la fecha de inicio y de fin en la que está ocupada
	function cambiar_mens(celda, hab, cam, ini, fin, camb){
		ini = ini.substr(8,2)+"/"+ini.substr(5,2)+"/"+ini.substr(0,4);	//compongo las fechas en formato dd/mm/aaaa
		fin = fin.substr(8,2)+"/"+fin.substr(5,2)+"/"+fin.substr(0,4);
		var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false; 
		if(exi)	//si la cama no está disponible todos los días
			if(camb)
				mens = "La habitación cambirá de tipo a partir del día " + ini; 	//creo el mensaje a mostrar
			else
				mens = "Esta cama está ocupada a partir del día " + ini; 	//creo el mensaje a mostrar
		window.status = mens;							//y lo pongo en la barra de estado
		document.getElementById(celda).title = mens;	//y en el alt de la celda
		return true;
	}
	
	var repetir;
	var tiempo = 500;	//indica el tiempo de repetión para las funciones recursivas
	
	function parar(){	//Esta función para el intervalo
		clearInterval(repetir);
	}
	
	function aumentar_noch(){	//Esta función aumenta en 1 el número de días
		document.getElementById("dias").value = (document.getElementById("dias").value/1) + 1;
	}
	function disminuir_noch(){	//Esta función disminuye en 1 el número de días
		if(document.getElementById("dias").value != "1")	//si el número de días en distinto a 1
			document.getElementById("dias").value = (document.getElementById("dias").value/1) - 1;
	}
	
	function aumentar_noch_c(){	//Esta función llama a la función que aumenta el número de días cada cierto tiempo
		repetir = setInterval("aumentar_noch()",tiempo);
	}
	
	function disminuir_noch_c(){//Esta función llama a la función que disminuye el número de días cada cierto tiempo
		repetir = setInterval("disminuir_noch()",tiempo);
	}
	
	function aumentar_pers(){	//Esta función aumenta en 1 el número de personas
		document.getElementById("camas").value = (document.getElementById("camas").value/1) + 1;
	}
	function disminuir_pers(){	//Esta función disminuye en 1 el número de personas
		if(document.getElementById("camas").value != "1")	//si el número de personas en distinto a 1
			document.getElementById("camas").value = (document.getElementById("camas").value/1) - 1;
	}
	
	function aumentar_pers_c(){		//Esta función llama a la función que aumenta el número de personas cada cierto tiempo
		repetir = setInterval("aumentar_pers()",tiempo);
	}
	
	function disminuir_pers_c(){	//Esta función llama a la función que disminuye el número de personas cada cierto tiempo
		repetir = setInterval("disminuir_pers()",tiempo);
	}
	
	function aumentar_camas(){		//Esta función aumenta en 1 el número de camas compartidas
		document.getElementById("compartidas").value = (document.getElementById("compartidas").value/1) + 1;
	}
	function disminuir_camas(){		//Esta función disminuye en 1 el número de camas compartidas
		if(document.getElementById("compartidas").value != "0")	//si el número de camas compartidas en distinto a 0
			document.getElementById("compartidas").value = (document.getElementById("compartidas").value/1) - 1;
	}
	
	function aumentar_camas_c(){	//Esta función llama a la función que aumenta el número de camas compartidas cada cierto tiempo
		repetir = setInterval("aumentar_camas()",tiempo);
	}
	
	function disminuir_camas_c(){	//Esta función llama a la función que diminuye el número de camas compartidas cada cierto tiempo
		repetir = setInterval("disminuir_camas()",tiempo);
	}
	
	function aumentar_indi(){			//Esta función aumenta en 1 el número de camas individuales
		document.getElementById("individuales").value = (document.getElementById("individuales").value/1) + 1;
	}
	function disminuir_indi(){			//Esta función disminuye en 1 el número de camas individuales
		if(document.getElementById("individuales").value != "0")	//si el número de camas individuales en distinto a 0
			document.getElementById("individuales").value = (document.getElementById("individuales").value/1) - 1;
	}
	
	function aumentar_indi_c(){		//Esta función llama a la función que aumenta el número de camas individuales cada cierto tiempo
		repetir = setInterval("aumentar_indi()",tiempo);
	}
	
	function disminuir_indi_c(){	//Esta función llama a la función que diminuye el número de camas individuales cada cierto tiempo
		repetir = setInterval("disminuir_indi()",tiempo);
	}
	
	</script>
<?
	$cancelar = $_GET['canc'];
	if($_POST['mov_cal'] == "0" || $cancelar == "True"){
		if($cancelar == "True"){ // si se va ha hacer una cancelación de una solicitud
			$dni = $_GET['dni'];
			$llega = $_GET['llega'];	//recojo la clave de la reserva
			$sql = "SELECT * FROM pra WHERE dni_pra = '" . trim($dni) . "'"; //busco los datos del cliente
			$res = mysql_query($sql);
			$fila = mysql_fetch_array($res);
			$email = $fila['Email_PRA'];			//y los pongo en variables
			$nom = $fila['Nombre_PRA'];
			$ape1 = $fila['Apellido1_PRA'];
			$ape2 = $fila['Apellido2_PRA'];
			$sql = "DELETE FROM detalles WHERE dni_pra = '" . trim($dni) . "' AND fecha_llegada = '" . trim($llega) . "'";	//borro la reserva on-line correspondiente
			mysql_query($sql);
			$sql = "select count(dni_pra) as num from reserva where dni_pra ='" . trim($dni) . "'";
			$res = mysql_query($sql);		// se mira si el 'pra' tiene mas reservas
			$fila = mysql_fetch_array($res);
			if($fila['num'] == 0){		// si no se borra de la tabla pra
				$sql = "DELETE FROM pra WHERE dni_pra = '" . trim($dni) . "'";
				mysql_query($sql);
			}
			//llamo a la pagina manda el email
			echo "<script>window.open('paginas/email_online/email_cancelar.php?email=".$email."&nom=" . $nom . "&ape1=" . $ape1 . "&ape2=" . $ape2 . "')</script>";

			if($_GET['busqueda'] != "si")	//si busqueda vale 'si' se vuelve a la página de busqueda, si no se recargar reservas on-line
				echo "<script>location.href='?pag=reservas_online.php'</script>";
			else
				echo "<script>location.href='?pag=listado_criterio_reservas_online.php'</script>";
		}
		else if($modi){ // si se va ha hacer una modificación
			$dni = $_POST['dni'];
			$nom = $_POST['nombre'];
			$ape1 = $_POST['apellido1'];
			$ape2 = $_POST['apellido2'];	//se recogen los datos del formulario
			$tel = $_POST['telefono'];
			$email = $_POST['email'];
			$obs = $_POST['obs'];
			$lleg = $_POST['llega'];
			$ing = $_POST['ing'];
			$dias = $_POST['dias'];
			//calculo la fecha de salida, en formato de fecha
			$sal = mktime(0, 0, 0, substr($lleg,3,2), substr($lleg,0,2) + $dias, substr($lleg,6,4));
			//pongo las fecha en el formato correcto para introducirlo en mysql
			$lleg_b =  substr($lleg,6,4) . "/" . substr($lleg,3,2). "/" . substr($lleg,0,2);
			$sal_b = strftime("%Y/%m/%d",$sal);
			$hoy = strftime("%Y/%m/%d",time());
			$cam = $_POST['camas'];
			$comp = $_POST['compartidas'];
			$ind = $_POST['individuales'];
			$tar = $_POST['tarde'];
			//actualizo todos los registros de la reserva on-line
			$sql = "UPDATE pra SET Nombre_PRA = '" . trim(strtoupper($nom)) . "', Apellido1_PRA = '" . trim(strtoupper($ape1)) . "', Apellido2_PRA = '" . trim(strtoupper($ape2)) . "', ";
			$sql = $sql . "Tfno_PRA = '" . trim($tel) . "', Email_PRA = '" . trim($email) . "' WHERE DNI_PRA = '" . trim($dni) . "'";
			mysql_query($sql);
			$sql = "UPDATE detalles SET Fecha_Salida = '" . trim($sal_b) . "', PerNocta = " . trim($dias) . ", Llegada_Tarde = '" . trim($tar) . "', ";
			$sql = $sql . "ingreso = '" . $ing . "', observaciones_pra = '" . trim($obs) . "' WHERE DNI_PRA = '" . trim($dni) . "' and Fecha_Llegada = '" . trim($lleg_b) . "'";
			mysql_query($sql);
			$sql = "UPDATE reserva SET Num_Camas = " . trim($cam) . ", Num_Camas_Indiv = " . trim($ind) . " ";
			$sql = $sql . "WHERE DNI_PRA = '" . trim($dni) . "' and Fecha_Llegada = '" . trim($lleg_b) . "' and id_hab = 'PRA'";
			mysql_query($sql);
			if($_POST['busqueda'] == "no")
				echo "<script>location.href='?pag=reservas_online.php'</script>";
			else
				echo "<script>location.href='?pag=listado_criterio_reservas_online.php'</script>";
		}
		else if($_GET['alta'] == "True"){ // si se va ha hacer un alta
			
			$dni = $_POST['dni'];
			$lleg = $_POST['llega'];
			$lleg_b =  substr($lleg,6,4) . "/" . substr($lleg,3,2). "/" . substr($lleg,0,2); //pongo la fecha en formato dd/mm/aaaa
			$emp = $_POST['empleado_reserva'];	//recojo el nombre del empleado
			$sql = "select * from detalles where dni_pra = '" . $dni . "' AND fecha_llegada = '" . $lleg_b . "'";
			$res = mysql_query($sql);
			$fila = mysql_fetch_array($res);
			// recojo todos los detalles de la reserva y los pongo en variables
			$sal = $fila['Fecha_Salida'];
			$pn = $fila['PerNocta'];
			$tar = $fila['Llegada_Tarde'];
			$ing = $fila['Ingreso'];
			$f_res = $fila['Fecha_Reserva'];
			$sql = "select * from reserva where dni_pra = '" . $dni . "' AND fecha_llegada = '" . $lleg_b . "' AND id_hab ='PRA'";
			$res = mysql_query($sql);
			$fila = mysql_fetch_array($res);
			$cam = $fila['Num_Camas'];
			$ind = $fila['Num_Camas_Indiv'];
			$comp = $cam - $ind;
			$t_fecha = split("\+",$_POST['fec_selec']);	//t_fecha es un array con todas la fechas que el usuario ha visto en 'distribución de habitaciones'
			$t_habit = split("\*",$_POST['hab_selec']);	//t_habit es un array con todas las camas que el usuario ha seleccionado en 'distribución de habitaciones'
			//la posición 0 del array t_habit corresponde con las camas seleccionadas en la fecha que esté en la posición 0 del array t_fecha, y así consecutivamente	
			
			$cambiar = 0;
			$t_fecha_tem = $t_fecha[count($t_fecha)-2];
			for($i = 0; $i < count($t_fecha)-2; $i++){
				if($t_fecha_tem == $t_fecha[$i]){
					$cambiar = 1;
				}
			}
			if($cambiar == 1){
				$fecha_tem = array();
				$habit_tem = array();
				for($i = 0; $i < count($t_fecha)-2; $i++){
					$fecha_tem[$i] = $t_fecha[$i];
					if($t_fecha_tem == $t_fecha[$i]){
						$habit_tem[$i] = $t_habit[count($t_fecha)-2];
					}else{
						$habit_tem[$i] = $t_habit[$i];
					}
				}
				$t_habit = array();
				$t_fecha = array();
				$t_habit = $habit_tem;
				$t_fecha = $fecha_tem;

			}

			//compruebo si hay dos fechas consecutivas con las mismas camas seleccionadas
			$fecha = array();	
			$habit = array();
			$fecha[0] = $t_fecha[0];
			$habit[0] = $t_habit[0];
			$con = 0;
			for($i = 1; $i < count($t_habit); $i++){ //si las camas de la fecha 'i' y igual al de las fecha anterior no se tiene en cuenta
				if($t_habit[$i] != $habit[$con]){
					$con++;
					$habit[$con] = $t_habit[$i];
					$fecha[$con] = $t_fecha[$i];
				}
			}
			$hab = array(); // en este array se guardan las habitaciones a asingar y el número de camas en cada una
			for($h = 0; $h < count($habit); $h++){
				$hab[$h] = array();
				$hab_sel = split("-",$habit[$h]);
				asort($hab_sel);	//ordeno el array
				$tem = "";
				for($i = 0; $i < count($hab_sel); $i++){ // recorro el array de habitaciones selecionadas
					$enc = 0;
					$nom = split("&",$hab_sel[$i]); 
					for($x = 0; $x < count($hab[$h]["hab"]); $x++){ // miro a ver si la habitación correspondiente ya está en el otro array
						if($hab[$h]["hab"][$x] == $nom[0]){	// si esta se aumentan el 1 el numero de camas en esa habitación
							$hab[$h]["cam"][$x] ++;
							$enc = 1;
						}
					}
					if($enc == 0){ // si no se ha encontrado la habitación en el array de habitaciones a asignar se le añade
						$hab[$h]["hab"][count($hab[$h]["hab"])] = $nom[0];
						$hab[$h]["cam"][count($hab[$h]["cam"])] = 1;
					}
				}
			}
			$sql = "DELETE FROM detalles WHERE dni_pra = '" . trim($dni) . "' AND fecha_llegada = '" . trim($lleg_b) . "'";
			mysql_query($sql);
			
			$sql = "select count(id_hab) as num from reserva where id_hab = 'PRA'"; //creo el localizador de la reserva
			$res = mysql_query($sql);
			$fila = mysql_fetch_array($res);
			$num = $fila['num'] + 1;
			$loc = $num . $dni . substr($lleg,0,2) . substr($lleg,3,2) . substr($lleg,6,4);
			
			for($h = 0; $h < count($hab); $h++){ //el array hab contiene las reservas que hay que poner el la BD
				$lleg_b =  substr($fecha[$h],6,4) . "/" . substr($fecha[$h],3,2). "/" . substr($fecha[$h],0,2); //cadena que contiene la fecha de llegada de la reserva
				//-- calculo los días de pernocta
				$d_lle = mktime(0, 0, 0, substr($fecha[$h],3,2), substr($fecha[$h],0,2), substr($fecha[$h],6,4));
				if($h != count($hab)-1){ //si no es la ultima reserva, la fecha de salida será la fecha de llegada de la siguiente reserva
					$d_sal = mktime(0, 0, 0, substr($fecha[$h+1],3,2), substr($fecha[$h+1],0,2), substr($fecha[$h+1],6,4));
				}	//si es la ultima reserva, la fecha de salida será la fecha de salida
				else{
					$d_sal = mktime(0, 0, 0, substr($sal,5,2), substr($sal,8,2), substr($sal,0,4));
				}
				
				$pn = round(($d_sal - $d_lle)/86400); //me da la diferencia en segundos, por lo que lo divido entre los segundos de un día
				//--fin de el calculo de días de pernocta
				$sal_n = strftime("%Y/%m/%d",$d_sal);
				if($h == 0)//el ingreso se le pone a la primera reserva
					$ingreso = $ing;
				else
					$ingreso = 0;
				$sql = "INSERT INTO detalles (DNI_PRA, Fecha_Llegada, Fecha_Salida, PerNocta, Llegada_Tarde, Ingreso, Nombre_Empleado, ";
				$sql = $sql . "Fecha_Reserva, Localizador_Reserva) values ('" . trim($dni) . "','" . trim($lleg_b) . "','" . trim($sal_n) . "','" . trim($pn) ."', ";
				$sql = $sql . "'" . trim($tar) . "'," . trim($ingreso) . ",'" . trim($emp) . "','" . trim($f_res) . "','" . trim($loc) . "')";
				mysql_query($sql);
				for($i = 0; $i < count($hab[$h]["hab"]) && $hab[$h]["hab"][$i] != ""; $i++){
					$sql = "insert into reserva (DNI_PRA, Fecha_Llegada, Id_Hab, Num_Camas, Num_Camas_Indiv) ";
					$sql = $sql . "values ('" . trim($dni) . "','" . trim($lleg_b) . "','" . trim($hab[$h]["hab"][$i]) . "','" . trim($hab[$h]["cam"][$i]) . "','0')";
					mysql_query($sql);
				}
			}
			
			$sql = "SELECT * FROM pra WHERE dni_pra = '" . trim($dni) . "'"; //busco el email del cliente para enviarle un correo
			$res = mysql_query($sql);
			$fila1 = mysql_fetch_array($res);
			$email = $fila1['Email_PRA'];
			$sal_b =  substr($sal,8,2) . "/" . substr($sal,5,2). "/" . substr($sal,0,4);
			
			$hoy = mktime(0, 0, 0, strftime("%m",time()), strftime("%d",time()), strftime("%Y",time()));
			$lle_com = mktime(0, 0, 0, substr($lleg_b,5,2), substr($lleg_b,8,2), substr($lleg_b,0,4));
			$segundos = $lle_com - $hoy;
			
			$dif = round($segundos/86400);
			//llamo a la pagina que genera el pdf y manda el email
			echo "<script>window.open('paginas/email_online/email_online.php?nom=".$fila1['Nombre_PRA']."&ape1=".$fila1['Apellido1_PRA']."&ape2=".$fila1['Apellido2_PRA']."&dni=".$dni."&tel=".$fila1['Tfno_PRA']."&email=".$email."&lleg=".$lleg."&sal=".$sal_b."&cam=".$cam."&ind=".$ind."&comp=".$comp."&dif=".$dif."&loc=".$loc."')</script>";
			
			if($_POST['busqueda'] == "no")
				echo "<script>location.href='?pag=reservas_online.php'</script>";
			else
				echo "<script>location.href='?pag=listado_criterio_reservas_online.php'</script>";
		}
	}
	// si se esta modificando una reserva on-line se muestran unos elementos, si no otros
	if($_GET['dni_ver'] == ""){
		$dni_ver = $_POST['dni'];
		$llegada_ver = substr($_POST['llega'],6,4) . "/" . substr($_POST['llega'],3,2). "/" . substr($_POST['llega'],0,2);
	}else{
		$dni_ver = $_GET['dni_ver'];
		$llegada_ver = $_GET['llegada_ver'];
	}
	//busco la reserva on-line a mostrar/modificar, si no se recibe ninguna, se recoge la 1º
	$sql = "select detalles.localizador_reserva as loca, reserva.dni_pra as dni, reserva.fecha_llegada as llega, ";
	$sql = $sql . "detalles.pernocta as pn, detalles.ingreso as ing, pra.nombre_pra as nom, pra.apellido1_pra as ape1, pra.apellido2_pra as ape2, ";
	$sql = $sql . "pra.tfno_pra as tele, email_pra as email, detalles.observaciones_pra as obs, reserva.num_camas as camas, reserva.num_camas_indiv as indi, ";
	$sql = $sql . "detalles.llegada_tarde as tarde, detalles.nombre_empleado as empleado from reserva INNER JOIN detalles ON ";
	$sql = $sql . "(reserva.dni_pra = detalles.dni_pra and reserva.fecha_llegada = detalles.fecha_llegada) ";
	$sql = $sql . "INNER JOIN pra ON (reserva.dni_pra = pra.dni_pra) where reserva.id_hab = 'PRA'";
	if($dni_ver != ""){
		$sql = $sql . " AND detalles.dni_pra = '" . $dni_ver . "' AND detalles.fecha_llegada = '" . $llegada_ver . "'";
	}
	$res = mysql_query($sql);
	$fila = mysql_fetch_array($res);
	$mod = "no";
	$mod = $_GET['mod'];
?>
<br>
 <? if ($mod == "si"){ ?>
 	<FORM name="form1" id="form1" action="?pag=reservas_online.php&modi=True" method="POST"> 
   <? }else{ ?>
	
<FORM name="form1" id="form1" action="?pag=reservas_online.php&alta=True" method="POST">
	<? } ?>   
	<input type="hidden" id="busqueda" name="busqueda" value="no">
	<input type="hidden" id="ver" name="ver" value="<?=$ver?>">
	<table width="1200px" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
    <td rowspan="2" valign="top" width="39%"> 
		<table border="0" align="center" width="450px" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<thead id="titulo_tablas">
			<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > <div class="champi_izquierda">&nbsp;</div>
              <div class="champi_centro" style='width:390px;'> 
					<?
						if ($mod == "si")
							echo "Modificar ";
						else
							echo "Tratar ";
						
					?>
					Reserva On-Line
				</div>
              </div>
              <div class="champi_derecha">&nbsp;</div></td>
            </tr>
			</thead>
			<tbody class='tabla_detalles'>
			<tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			<?
		  	$num = mysql_num_rows($res);
			if($num == 0){
			?>
			<table width="100%" height="100%" style="border: 1px solid #3F7BCC;" id="tabla_detalles">
			<tr><td valign="center" align="center" height="510">
				 <font class='label_formulario'> 
					  <? if($ver == ""){ ?>
					  No existe ninguna Reserva On-Line 
					  <? }else{ ?>
					  No existe ninguna Reserva On-Line con <br>
					  <br> el Localizador</font> <?=$ver?>
					  <? } ?>
                  </font>
			</td></tr>
			</table>
			</td></tr>
			<?
			}else{
			?>
			<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="5" style="border: 1px solid #3F7BCC;" id="tabla_detalles">
                <tr> 
                  <td width="1" height="22">&nbsp;</td>
                  <td width="170" align="left" valign="top" class='label_formulario'>D.N.I.:</td>
                  <td align="left" valign="top" id="texto_detalles"> 
                    <? if ($mod == "si"){ ?>
                    <input name="dni1" id="dni1" type="text" disabled class="input_formulario" value="<?=$fila['dni']?>" size="35" maxlength="9"> 
                    <? }else{
					echo $fila['dni'];
				} ?>
                    <input name="dni" id="dni" type="hidden" class="input_formulario" value="<?=$fila['dni']?>" maxlength="9">
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" class='label_formulario'>Nombre:</td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"> 
                    <? if ($mod == "si"){ ?>
                    <input name="nombre" id="nombre" type="text" class="input_formulario" value="<?=$fila['nom']?>" size="35" maxlength="20" onBlur="this.value=this.value.toUpperCase();"> 
                    <? }else{
					echo $fila['nom'];
				} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" class='label_formulario'>Primer 
                    Apellido:</td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"> 
                    <? if ($mod == "si"){ ?>
                    <input name="apellido1" id="apellido1" type="text" class="input_formulario" value="<?=$fila['ape1']?>" size="35" maxlength="30" onBlur="this.value=this.value.toUpperCase();"> 
                    <? }else{
					echo $fila['ape1'];
				} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" class='label_formulario'>Segundo 
                    Apellido:</td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"> 
                    <? if ($mod == "si"){ ?>
                    <input name="apellido2" id="apellido2" type="text" class="input_formulario" value="<?=$fila['ape2']?>" size="35" maxlength="30" onBlur="this.value=this.value.toUpperCase();"> 
                    <? }else{
					echo $fila['ape2'];
				} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" class='label_formulario'>Teléfono:</td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"> 
                    <? if ($mod == "si"){ ?>
                    <input name="telefono" id="telefono" type="text" class="input_formulario" value="<?=$fila['tele']?>" size="35" maxlength="12" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"> 
                    <? }else{
					echo $fila['tele'];
				} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" class='label_formulario'>E-mail:</td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"> 
                    <? if ($mod == "si"){ ?>
                    <input name="email" id="email" type="text" class="input_formulario" value="<?=$fila['email']?>" size="35" maxlength="100"> 
                    <? }else{?>
						<textarea style='font-weight:bold;' readonly name="email" id="email" cols="37" rows="2" class="input_formulario"><?=$fila['email']?></textarea>
					<?} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="50">&nbsp;</td>
                  <td align="left" valign="top" class='label_formulario'> 
                    Obsers:</td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"> 
                    <? if ($mod == "si"){ ?>
                    <textarea name="obs" id="obs" cols="34" rows="3" class="input_formulario" maxlength='250'><?=$fila['obs']?></textarea> 
                    <? }else{
				 	if($fila['obs'] == "")
						echo "----";
					else{
					?>
						<textarea readonly style='font-weight:bold;' name="obs" id="obs" cols="37" rows="3" class="input_formulario"><?=$fila['obs']?></textarea>
					<?
					}
				} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" id='texto_detalles'><font class='label_formulario'>Llegada: 
                    </font> 
                    <?
					$fecha = substr($fila['llega'],8,2) . "/" . substr($fila['llega'],5,2) . "/" . substr($fila['llega'],0,4); 
				?>
                    <? if ($mod == "si"){ ?>
                    <input name="llega1" id="llega1" type="text" value="<?=$fecha?>" class="input_formulario" disabled size="10"> 
                    <? }else{
					echo $fecha;
				} ?>
                    <input name="llega" id="llega" type="hidden" value="<?=$fecha?>" class="input_formulario"> 
                  </td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"><font class='label_formulario'>Ingreso:&nbsp;</font> 
                    <? if ($mod == "si"){ ?>
                    <input name="ing" type="text" class="input_formulario" id="ing" value="<?=$fila['ing']?>" size="6">
                    <font class='label_formulario'>&euro;uros </font> 
                    <? }else{
							echo $fila['ing'] . "&nbsp;&euro;uros";
						} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" id="texto_detalles"><font class='label_formulario'>Noches:&nbsp;</font> 
                    <? if ($mod == "si"){ ?>
                    <input name="dias" id="dias" type="text" class="input_formulario" value="<?=$fila['pn']?>" size="2"><img src="../imagenes/botones/flecha1.jpg" border="0" align="absmiddle" usemap="#mapa_noch">
                    <? }else{
					echo $fila['pn'];
				} ?>
				<input name="pernocta" id="pernocta" type="hidden" value="<?=$fila['pn']?>" size="2">
                  </td>
                  <td align="left" valign="top" id="texto_detalles"><font class='label_formulario'>Personas:&nbsp;</font>&nbsp;&nbsp; 
                    <? if ($mod == "si"){ ?>
                    <input name="camas" id="camas" type="text" class="input_formulario" value="<?=$fila['camas']?>" size="2"><img src="../imagenes/botones/flecha1.jpg" border="0" align="absmiddle" usemap="#mapa_pers"> 
                    <? }else{
					echo $fila['camas'];
				} 
				$camas_com = $fila['camas'];
			  ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="top" id="texto_detalles"><font class='label_formulario'>Camas:&nbsp;&nbsp;</font> 
                    <? $comp = $fila['camas'] - $fila['indi']; ?>
                    <? if ($mod == "si"){ ?>
                    <input name="compartidas" id="compartidas" size="2" type="text" value="<?=$comp?>" class="input_formulario"><img src="../imagenes/botones/flecha1.jpg" border="0" align="absmiddle" usemap="#mapa_camas"> 
                    <? }else{
					echo $comp;
				} ?>
                  </td>
                  <td align="left" valign="top" id="texto_detalles"><font class='label_formulario'>Camas 
                    Ind.:</font> 
                    <? if ($mod == "si"){ ?>
                    <input name="individuales" id="individuales" size="2" type="text" value="<?=$fila['indi']?>" class="input_formulario"><img src="../imagenes/botones/flecha1.jpg" border="0" align="absmiddle" usemap="#mapa_indi">
                    <? }else{
					echo $fila['indi'];
				} ?>
					
                  </td>
                </tr>
                <tr> 
                  <td height="50">&nbsp;</td>
                  <td align="left" valign="top" class='label_formulario'> 
                    <? if ($mod == "si"){ 
			  		if($fila['tarde'] == ""){
					?>
                    <INPUT type="checkbox" onclick="estado_tarde()" name="check_tarde" id="check_tarde"> 
                    <?   }else{  ?>
                    <INPUT type="checkbox" onclick="estado_tarde()" name="check_tarde" id="check_tarde" checked>	
                    <? 		}
					} ?>
                    Llegada Tarde:</td>
                  <td colspan="2" align="left" valign="top" id="texto_detalles"> 
                <? if ($mod == "si"){ 
			  		if($fila['tarde'] == ""){
				?>
                    <textarea name="tarde" id="tarde" cols="34" rows="3" class="input_formulario" disabled maxlength='100'>(Indique la hora aproximada de llegada)</textarea> 
                    <?   }else{  ?>
                    <textarea name="tarde" id="tarde" cols="37" rows="3" class="input_formulario" maxlength='100'><?=$fila['tarde']?></textarea> 
                    <?	} 
			  	}else{
					if($fila['tarde'] == "")
						echo "No";
					else{
				?>
                    <textarea readonly style='font-weight:bold;' name="tarde" id="tarde" cols="34" rows="3" class="input_formulario"><?=$fila['tarde']?></textarea> 
                    <?
					}
				} ?>
                  </td>
                </tr>
                <tr> 
                  <td height="22">&nbsp;</td>
                  <td align="left" valign="middle" class='label_formulario'>Nombre 
                    del Empleado:</td>
                  <td colspan="2" align="left" valign="top"> 
                    <? if ($mod == "si"){ ?>
                    <input name='empleado_reserva' id='empleado_reserva' type='text' style='font-weight:bold;' disabled class="input_formulario" size="35"> 
                    <? }else{ ?>

                    <input name='empleado_reserva' id='empleado_reserva' type='text' class="input_formulario" value="<?=$_POST['empleado_reserva']?>" size="38" maxlength="50"> 
                    <? } ?>
                  </td>
                </tr>
                  <td colspan='13' align='center'> <br>
                <?php
				if ($mod == "si"){
				?>
                    <a href="#" onClick="comprobar_mod('<?=$busqueda?>')" OnMouseOver="window.status='Modificar los datos de la solicitud de reserva On-Line' ; return true" onMouseOut="window.status='Listo' ; return true">
						<img src="../imagenes/botones-texto/modificar.jpg" alt="Modificar los datos de la solicitud de reserva On-Line" width="110" height="30" border="0"></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
					<a href="#" onClick="cancelar_mod('<?=$ver?>','<?=$busqueda?>')" OnMouseOver="window.status='Cancelar la modificaci&oacute;n de los datos' ; return true" onMouseOut="window.status='Listo' ; return true">
						<img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar la modificaci&oacute;n de los datos" width="110" height="30" border="0"></a> 
                    <?php
				}else{
					$dni = $fila['dni'];
					$llega = $fila['llega'];
				?>
                    <a href="#" onClick="comprobar('<?=$busqueda?>')" OnMouseOver="window.status='Aceptar la solicitud de reserva On-Line' ; return true" onMouseOut="window.status='Listo' ; return true">
						<img src="../imagenes/botones-texto/aceptar.jpg" alt="Aceptar la solicitud de reserva On-Line" width="110" height="30" border="0"></a> 
                    &nbsp;&nbsp;&nbsp;
					<a href="#" onClick="confirmar('<?=$dni?>','<?=$llega?>','<?=$busqueda?>')" OnMouseOver="window.status='Anular la solicitud de reserva On-Line' ; return true" onMouseOut="window.status='Listo' ; return true">
						<img src="../imagenes/botones-texto/eliminar.jpg" alt="Anular la solicitud de reserva On-Line" width="110" height="30" border="0"></a> 
					&nbsp;&nbsp;&nbsp; 
					<a href="#" onClick="cancelar('<?=$busqueda?>')" OnMouseOver="window.status='Cancelar' ; return true" onMouseOut="window.status='Listo' ; return true">
						<img src="../imagenes/botones-texto/cancelar.jpg" alt="Cancelar" width="110" height="30" border="0"></a> 
                    <?php
				}
			?><br><br>
                  </td>
                </tr>
              </table>
			  <MAP name="mapa_noch">
               <AREA shape="rect" coords="0,3,9,10" href="#" onMouseDown="window.status='Aumenta el número de noches';aumentar_noch_c()" onMouseOut="parar();window.status='Listo'" onClick="aumentar_noch()" onMouseUp="parar()">
               <AREA shape="rect" coords="0,12,9,18" href="#" onMouseDown="window.status='Reduce el número de noches';disminuir_noch_c()" onMouseOut="parar();window.status='Listo'" onClick="disminuir_noch()" onMouseUp="parar()">
              </MAP>
			  <MAP name="mapa_pers">
               <AREA shape="rect" coords="0,3,9,10" href="#" onMouseDown="window.status='Aumenta el número de personas';aumentar_pers_c()" onMouseOut="parar();window.status='Listo'" onClick="aumentar_pers()" onMouseUp="parar()">
               <AREA shape="rect" coords="0,12,9,18" href="#" onMouseDown="window.status='Reduce el número de personas';disminuir_pers_c()" onMouseOut="parar();window.status='Listo'" onClick="disminuir_pers()" onMouseUp="parar()">
              </MAP>
			  <MAP name="mapa_camas">
               <AREA shape="rect" coords="0,3,9,10" href="#" onMouseDown="window.status='Aumenta el número de camas compartidas';aumentar_camas_c()" onMouseOut="parar();window.status='Listo'" onClick="aumentar_camas()" onMouseUp="parar()">
               <AREA shape="rect" coords="0,12,9,18" href="#" onMouseDown="window.status='Reduce el número de camas compartidas';disminuir_camas_c()" onMouseOut="parar();window.status='Listo'" onClick="disminuir_camas()" onMouseUp="parar()">
              </MAP>
			  <MAP name="mapa_indi">
               <AREA shape="rect" coords="0,3,9,10" href="#" onMouseDown="window.status='Aumenta el número de camas individuales';aumentar_indi_c()" onMouseOut="parar();window.status='Listo'" onClick="aumentar_indi()" onMouseUp="parar()">
               <AREA shape="rect" coords="0,12,9,18" href="#" onMouseDown="window.status='Reduce el número de camas individuales';disminuir_indi_c()" onMouseOut="parar();window.status='Listo'" onClick="disminuir_indi()" onMouseUp="parar()">
              </MAP>		  
		  </td></tr>
			<?
			}
			?>
			</tbody>
        </table>
    </td>
        <?
			//creo todas la fechas que se usaran mas adelante para hacer comparaciones
			if($_POST['mov_cal'] == "1"){	//si se esta moviendo el calendario la fecha a ver en 'distribución de habitaciones' es la seleccionada 
				$fecha_dia = substr($_POST['fecha_ver'],0,2);
				$fecha_mes = substr($_POST['fecha_ver'],3,2);
				$fecha_anio = substr($_POST['fecha_ver'],6,4);
			}else{							//si no es la fecha de llegada de la reserva on-line
				$fecha_dia = substr($fecha,0,2);
				$fecha_mes = substr($fecha,3,2);
				$fecha_anio = substr($fecha,6,4);
			}
			if($fecha_dia == ""){	//si no hay fechas, se coge la fecha de hoy
				$fecha_dia = strftime("%d",time());
				$fecha_mes = strftime("%m",time());
				$fecha_anio = strftime("%Y",time());
			}
			$fecha_sel = mktime(0, 0, 0, $fecha_mes, $fecha_dia, $fecha_anio);	//se compone la fecha seleccionada, la de salida y la de llegada
			$fecha_sal = mktime(0, 0, 0, substr($fila['llega'],5,2), substr($fila['llega'],8,2) + ($fila['pn']-1), substr($fila['llega'],0,4));
			$fecha_lle = mktime(0, 0, 0, substr($fila['llega'],5,2), substr($fila['llega'],8,2), substr($fila['llega'],0,4));
			if($fila['llega'] == ""){	//si no hay fecha de llegada, no hay reserva, por lo que la fecha de llegada es hoy y la de salida mañana
				$fecha_lle = mktime(0, 0, 0, $fecha_mes, $fecha_dia, $fecha_anio);
				$fecha_sal = mktime(0, 0, 0, $fecha_mes, $fecha_dia+1, $fecha_anio);
			}
			$fec_lle_val = strftime("%d/%m/%Y",$fecha_lle);
			$fec_sal_val = strftime("%d/%m/%Y",$fecha_sal);
		?>

      <td width="62%" valign="top"> 
	  
	  
      <table border="0" align="center" width="700px" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > <div class="champi_izquierda">&nbsp;</div>
              <div class="champi_centro"> 
						<div style="height:25px;text-align:center;background-repeat:repeat-x;float:left;">	
               				<div class="titulo" style="width:440px;text-align:center;">
				  				Distribución de Habitaciones
							</div>
				  		</div>
				  		<div style="height:25px;text-align:center;background-repeat:repeat-x;float:left;">	
						  <div class="titulo" style="width:200px;text-align:center;">
							 <select name="num_pag" id="num_pag" class="detalles_select" onchange="cambiar_pagina_dis(num_pag.value);">
								<?PHP	//ponjo las ventanas de 'distribución de habitaciones'
									for ($i = 0; $i < count($numero_paginas); $i++) {
										if ($pagina == $numero_paginas[$i]) {
											echo '<option value="'.$numero_paginas[$i].'" selected>Ventana '.$numero_paginas[$i].'</option>';
										}
										else {
											echo '<option value="'.$numero_paginas[$i].'">Ventana '.$numero_paginas[$i].'</option>';
										}
									}
								?>									
							</select>
							<input type="hidden" id="mover_pag" name="mover_pag" value="0">
							<input type="hidden" id="dist_num_pag" name="dist_num_pag" value="<?=$pagina?>"> <!-- Cambia el número de pagina en distribucion de habitaciones -->
							  <input name="mov_cal" id="mov_cal" value="0" type="hidden">
							  <input type="hidden" name="fecha_ver" id="fecha_ver" value="<?=$fecha_dia."/".$fecha_mes."/".$fecha_anio;?>">
							  <input type="hidden" name="fecha_cal" id="fecha_cal" value="<?=$fecha_dia."-".$fecha_mes."-".$fecha_anio;?>">
							<script>
								dia_selec = '<?=$fecha_dia?>' / 1;			//cambio las variables del calendario y llamo a la función pasando el dia, mes y año seleccionado
								mes_selec = ('<?=$fecha_mes?>' / 1) - 1;
								anio_selec = '<?=$fecha_anio?>';
								calendario(dia_selec, mes_selec, anio_selec);
							</script>
						</div>
					</div>
              </div>
              <div class="champi_derecha">&nbsp;</div></td>
            </tr><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
				<table width="100%" height="100%" style="border: 1px solid #3F7BCC;" id="tabla_detalles">
					<tr>
					<td align="center">
					<table border="0" width="" class="tabla_habitaciones" bordercolor="#FFFFFF" rules="groups" cellpadding="0" cellspacing="1">
<?php

	//Se Crean y Ejecutan las consultas para mostrar el lisado
	$reservas_dia=array(); //este array contiene todas la pernoctas y reservas que están en alguna de las fechas de la reserva on-line
	$pn = $fila['pn'];
	if($pn == "")	//si pn no tiene valor, no hay reserva on-line, por lo que vale 1
		$pn = 1;
	$f_sel = strftime("%Y-%m-%d",$fecha_sel);	//se ponen las fecha en formato aaaa-mm-dd para compararlas con las fechas de las pernoctas y reservas existentes
	$f_lle = strftime("%Y-%m-%d",$fecha_lle);
	$f_sal = strftime("%Y-%m-%d",$fecha_sal);

	/*Compruebo si hay reservas entre la fecha de llegada y salida (menos las reservas on-line)*/
	$qry_sel_res = "SELECT *, detalles.fecha_llegada as fecha, detalles.fecha_salida as fecha_s ";
	$qry_sel_res = $qry_sel_res . "FROM detalles INNER JOIN reserva ON ";
	$qry_sel_res = $qry_sel_res . "(detalles.dni_pra = reserva.dni_pra AND detalles.fecha_llegada = reserva.fecha_llegada) ";
	$qry_sel_res = $qry_sel_res . "WHERE id_hab != 'PRA'";
	$res_sel_res=mysql_query($qry_sel_res);

	$ini = 0; //esta variable indica la posición en la que se pone un elemento en el array reservas_dia
	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res)+$con;$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s']){ //si la fecha de llegada y salida de la reserva no son iguales 
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));	//creo las fechas de llegada y salida de la reserva
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++){	//recorro todos los dias de la reserva on-line, hasta que acabe o se ponga en el array
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){	//si las fechas de la reserva actual está entre las de la reserva on-line
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];			//se pone en el array de reservas_dia
					$reservas_dia[$ini]['camas']=$tupla_res['Num_Camas'];
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['reserva']='s';
					$ini++;
					$puesto = 1;	//y se indica que ya se ha puesto en el array
				}
			}
		}
	}
	//el funcionamiento en los 3 buques siguientes es igual a este
	$ini = count($reservas_dia);

	/*Compruebo si hay estancias de grupos entre la fecha de llegada y salida*/
	$qry_sel_res = "SELECT *, colores.Color as Color, estancia_gr.PerNocta as pn, pernocta_gr.num_personas as num_per, estancia_gr.fecha_llegada as fecha, estancia_gr.fecha_salida as fecha_s ";
	$qry_sel_res = $qry_sel_res . "FROM pernocta_gr INNER JOIN estancia_gr ON ";
	$qry_sel_res = $qry_sel_res . "(pernocta_gr.nombre_gr = estancia_gr.nombre_gr AND pernocta_gr.fecha_llegada = estancia_gr.fecha_llegada) INNER JOIN colores ON (estancia_gr.Id_Color = colores.Id_Color) ";
	$res_sel_res=mysql_query($qry_sel_res);

	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s']){
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++){
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=$tupla_res['num_per'];
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['id_color']=$tupla_res['Color'];	//++++++++++
					$reservas_dia[$ini]['reserva']='n';
					$ini++;
					$puesto = 1;
				}
			}
		}
	}
	$ini = count($reservas_dia);
	
	/*Compruebo si hay estancias de peregrinos entre la fecha de llegada y salida*/
	$qry_sel_res = "SELECT *, fecha_llegada as fecha, fecha_salida as fecha_s FROM pernocta_p";
	$res_sel_res=mysql_query($qry_sel_res);

	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s']){
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++){
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=1;
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['reserva']='n';
					$ini++;
					$puesto = 1;
				}
			}
		}
	}
	$ini = count($reservas_dia);

	/*Compruebo si hay estancias de alberguista entre la fecha de llegada y salida*/
	$qry_sel_res = "SELECT *, fecha_llegada as fecha, fecha_salida as fecha_s FROM pernocta";
	$res_sel_res=mysql_query($qry_sel_res);
	
	//Se Almacenan los datos para el día seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha'] != $tupla_res['fecha_s']){
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $pn && $puesto == 0; $j++){
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=1;
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['reserva']='n';
					$ini++;
					$puesto = 1;
				}
			}
		}
	}		
	
	if(count($reservas_dia) != 0){ //si hay alguna reserva 
		for ($a = 0;$a < count($reservas_dia); $a++){
			$fec[$a] = $reservas_dia[$a]['fecha'];
		}
		array_multisort ($fec, SORT_ASC, $reservas_dia);//las ordeno por la fecha de llegada
	}
	echo "<script>habit_sel = new Array();</script>";	//creo un array en javascript para guardar en un array las habitaciones, su tipo y si es compartida o no
	
	for ($i=0;$i<count($reservas_dia);$i++){
		$sql = "select cambio_tipo_habitacion.id_tipo_hab as id_tipo_hab, habitacion.camas_hab as camas_hab, tipo_habitacion.compartida as ";
		$sql = $sql . "compartida from habitacion inner join cambio_tipo_habitacion on (habitacion.id_hab = cambio_tipo_habitacion.id_hab) inner join ";
		$sql = $sql . "tipo_habitacion on (cambio_tipo_habitacion.id_tipo_hab = tipo_habitacion.id_tipo_hab) where ";
		$sql = $sql . "habitacion.id_hab = '" . $reservas_dia[$i]['hab'] . "' and cambio_tipo_habitacion.fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' "; 
		$sql = $sql . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $reservas_dia[$i]['hab'] . "' and cambio_tipo_habitacion.fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "')";
		
		$res=mysql_query($sql);
		$fila=mysql_fetch_array($res);
		echo "<script>";
			echo "habit_sel[" . $i . "] = new Array();";
			echo "habit_sel[" . $i . "][0] = '" . $reservas_dia[$i]['hab'] . "';";
			echo "habit_sel[" . $i . "][1] = '" . $fila['id_tipo_hab'] . "';";
			echo "habit_sel[" . $i . "][2] = '" . $fila['compartida'] . "';";
		echo "</script>";
	}
		
//******************************************<<<DISTRIBUCIÓN DE HABITACIONES>>>*****************************************
//Se Almacenan en un array los datos de todas las habitaciones existentes;
$habita=array();
$cont=0;
$tipo_Hab=array();
$cont=0;
$max_camas = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];	//numero maximo de camas por columna
$contador = 0;

$qry_dist="SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab where camas_hab <> -1";

echo "<script>habit_datos = new Array();</script>";	//creo un array en javascript para saber las habitaciones y su tipo
$contador_datos = 0;
$res_dist=mysql_query($qry_dist);
for ($i=0;$i<mysql_num_rows($res_dist);$i++){
	$tupla_dist=mysql_fetch_array($res_dist);
	//if ($tupla_dist['Camas_Hab']>0){//con este if se quitan las habitaciones que no tiene camas
		$habita[$contador]['orden']=$tupla_dist['Id_Hab'];
		$habita[$contador]['id']=$tupla_dist['Id_Hab'];
		$habita[$contador]['tipo']=$tupla_dist['Id_Tipo_Hab'];
		$habita[$contador]['nombre_tipo']=$tupla_dist['Nombre_Tipo_Hab'];
		$habita[$contador]['puede_reservar']=$tupla_dist['Reservable'];
		$habita[$contador]['compartida']=$tupla_dist['Compartida'];
		$habita[$contador]['camas']=$tupla_dist['Camas_Hab'];
		$habita[$contador]['total_camas']=$tupla_dist['Camas_Hab'];
		echo "<script>";
			echo "habit_datos[" . $contador_datos . "] = new Array();";
			echo "habit_datos[" . $contador_datos . "][0] = '" . $habita[$contador]['id'] . "';";
			echo "habit_datos[" . $contador_datos . "][1] = '" . $habita[$contador]['total_camas'] . "';";
		echo "</script>";

		$sql = "SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MIN(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha > '" . strftime("%Y-%m-%d",$fecha_sel) . "' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab where consulta.id_hab = '" . $tupla_dist['Id_Hab'] . "'";

		$res_camb_tipo = mysql_query($sql);
		if(mysql_num_rows($res_camb_tipo) == 0)
			$habita[$contador]['cambio_tipo'] = "";
		else{
			$tupla_cambio_hab = mysql_fetch_array($res_camb_tipo);
			$habita[$contador]['cambio_tipo'] = $tupla_cambio_hab['Fecha'];

			echo "<script>";
				echo "var i = habit_sel.length;";
				echo "habit_sel[i] = new Array();";
				echo "habit_sel[i][0] = '" . $habita[$contador]['id'] . "';";
				echo "habit_sel[i][1] = '" . $habita[$contador]['tipo'] . "';";
				echo "habit_sel[i][2] = '" . $habita[$contador]['compartida'] . "';";
			echo "</script>";
		}

		$habita[$contador]['ocupadas']=array();
		$habita[$contador]['ocupadas']['c'] = 0;
		$habita[$contador]['columnas'] = 1;
		$habita[$contador]['pagina'] = $valor;
		
		//Utilizo el array reservas_dia (donde almaceno las reservas para el dia deseado) para ver que camas tengo ocupadas
		for ($j=0;$j<count($reservas_dia);$j++){
			if($habita[$contador]['id']==$reservas_dia[$j]['hab']){
				$camas_ant = $habita[$contador]['ocupadas']['c'];
				if($camas_ant == "")
					$camas_ant = 0;
				$camas_aho = $camas_ant + $reservas_dia[$j]['camas'];
				$habita[$contador]['ocupadas']['hab']=$reservas_dia[$j]['hab'];
				for($a = $camas_ant; $a < $camas_aho; $a++){	//para cada cama de esta estancia/reserva le pongo la fecha de llegada y salida correspondiente
					$habita[$contador]['ocupadas1'][$a]['fecha'] = $reservas_dia[$j]['fecha'];
					$habita[$contador]['ocupadas1'][$a]['fecha_s'] = $reservas_dia[$j]['fecha_s'];
					$habita[$contador]['ocupadas1'][$a]['reserva'] = $reservas_dia[$j]['reserva'];
					$habita[$contador]['ocupadas1'][$a]['id_color'] = $reservas_dia[$j]['id_color'];	

				}
				$habita[$contador]['ocupadas']['c']+=$reservas_dia[$j]['camas']; //y aumento el numero de camas ocupadas de la habitacion
				
			}
		}
		$contador_ocupadas = 0;
		for ($j=0;$j<count($habita[$contador]['ocupadas1']);$j++){
			$fecha_tem1 = $habita[$contador]['ocupadas1'][$j]['fecha'];
			$fecha_tem = mktime(0, 0, 0, substr($fecha_tem1,5,2), substr($fecha_tem1,8,2), substr($fecha_tem1,0,4));
			if($fecha_tem <= $fecha_sel)
				$contador_ocupadas++;
		}

		echo "<script>";
		echo "habit_datos[" . $contador_datos . "][2] = '" . $contador_ocupadas . "';";
		echo "</script>";
		$contador_datos++;

		$orden_lleg = array();	//ordeno las camas ocupadas de cada habitacion para que las ocupadas aparezcan 1º y despues las reservadas, las incompletas, las del grupo y las libres
		$orden_sal = array();
		$orden_gru = array();
		$orden_res = array();
		for($o = 0; $o < count($habita[$contador]['ocupadas1']); $o++){
			$orden_lleg[$o] = $habita[$contador]['ocupadas1'][$o]['fecha'];
			$orden_sal[$o] = $habita[$contador]['ocupadas1'][$o]['fecha_s'];
			$orden_res[$o] = $habita[$contador]['ocupadas1'][$o]['reserva'];
		}
		if(count($orden_lleg) != 0){
			array_multisort ($orden_lleg, SORT_ASC, $orden_sal, SORT_ASC, $orden_res, SORT_ASC, $habita[$contador]['ocupadas1']);
		}

		do{	//si dos ocupaciones se solapan en el tiempo (la fecha de salida de una es menor que la fecha de llegada de otra), las uno y se trata como una sola
			$cambio = 0;	
			for($o = 0; $o < count($habita[$contador]['ocupadas1']); $o++){
				$cad_temp = $habita[$contador]['ocupadas1'][$o]['fecha_s'];
				$fecha_s_temp = mktime(0, 0, 0, substr($cad_temp,5,2), substr($cad_temp,8,2), substr($cad_temp,0,4));
				for($o1 = $o+1; $o1 < count($habita[$contador]['ocupadas1']) && $cambio == 0; $o1++){
					$cad_temp = $habita[$contador]['ocupadas1'][$o1]['fecha'];
					$fecha_l_temp = mktime(0, 0, 0, substr($cad_temp,5,2), substr($cad_temp,8,2), substr($cad_temp,0,4));
					if($fecha_s_temp <= $fecha_l_temp){
						$habita[$contador]['ocupadas1'][$o]['fecha_s'] = $habita[$contador]['ocupadas1'][$o1]['fecha_s']; //pongo la fecha de salida superior
					
						$habita[$contador]['ocupadas']['c']--;	//las ocupadas son una menos
						$cambio = 1;	//indico que se ha realizado un cambio
						
						$ocupadas_tem = array();	//guardo en un array temporal todas las ocupaciones menos la actual
						$puestos = 0;
						for($t = 0; $t < count($habita[$contador]['ocupadas1']); $t++){
							if($t != $o1){
								$ocupadas_tem[$puestos]['fecha'] = $habita[$contador]['ocupadas1'][$t]['fecha'];
								$ocupadas_tem[$puestos]['fecha_s'] = $habita[$contador]['ocupadas1'][$t]['fecha_s'];
								$ocupadas_tem[$puestos]['reserva'] = $habita[$contador]['ocupadas1'][$t]['reserva'];
								$ocupadas_tem[$puestos]['id_color'] = $habita[$contador]['ocupadas1'][$t]['id_color'];
								$puestos++;
							}
						}
						$habita[$contador]['ocupadas1'] = array();
						$habita[$contador]['ocupadas1'] = $ocupadas_tem;	//restauro las ocupaciones
					}
				}

			}
		}while($cambio == 1);

		$orden_lleg = array();	//lo vuelvo a ordenar
		$orden_sal = array();
		$orden_gru = array();
		$orden_res = array();
		for($o = 0; $o < count($habita[$contador]['ocupadas1']); $o++){
			$orden_lleg[$o] = $habita[$contador]['ocupadas1'][$o]['fecha'];
			$orden_sal[$o] = $habita[$contador]['ocupadas1'][$o]['fecha_s'];
			$orden_res[$o] = $habita[$contador]['ocupadas1'][$o]['reserva'];
		}
		if(count($orden_lleg) != 0){
			array_multisort ($orden_lleg, SORT_ASC, $orden_sal, SORT_ASC, $orden_res, SORT_ASC, $habita[$contador]['ocupadas1']);
		}

		if($habita[$contador]['camas'] > $max_camas){	//si la habitacion tiene mas camas del maximo
			$num_camas = $habita[$actual]['camas'];
			$actual = $contador;
			
			$columnas = 1;
			while($habita[$actual]['camas'] > $max_camas){ //creo tantas habitaciones extra como sean necesarias, pero con el mismo id
				$contador++;
				$habita[$contador]['orden']=$tupla_dist['Id_Hab'];
				$habita[$contador]['id']=$tupla_dist['Id_Hab'];
				$habita[$contador]['tipo']=$tupla_dist['Id_Tipo_Hab'];
				$habita[$contador]['nombre_tipo']=$tupla_dist['Nombre_Tipo_Hab'];
				$habita[$contador]['puede_reservar']=$tupla_dist['Reservable'];
				$habita[$contador]['compartida']=$tupla_dist['Compartida'];
				$habita[$contador]['camas']=$max_camas;
				$habita[$contador]['total_camas']=$tupla_dist['Camas_Hab'];
				$habita[$contador]['ocupadas']=array();
				$habita[$contador]['ocupadas']['c'] = 0;
				$habita[$contador]['columnas'] = 1;
				$columnas++;
				$habita[$contador]['cambio_tipo'] = $habita[$actual]['cambio_tipo'];
				$habita[$actual]['camas'] -= $max_camas;
			}
			
			$ult = $contador;
			for($busq = $actual; $busq <= $contador; $busq++){
				$temp[$busq]['camas'] = $habita[$busq]['camas'];
			}
			for($busq = $actual; $busq <= $contador; $busq++){
				$habita[$busq]['camas'] = $temp[$ult]['camas'];
				$ult--;
			}
			
			for($b = 0; $b <= $a; $b++){	//guardo las fechas en un array temporal
				$temporal[$b]['fecha'] = $habita[$actual]['ocupadas1'][$b]['fecha'];
				$temporal[$b]['fecha_s'] = $habita[$actual]['ocupadas1'][$b]['fecha_s'];
				$temporal[$b]['reserva'] = $habita[$actual]['ocupadas1'][$b]['reserva'];
				$temporal[$b]['id_color'] = $habita[$actual]['ocupadas1'][$b]['id_color'];	
			}
			
			$actual_camas = $habita[$actual]['ocupadas']['c'];
			$habita[$actual]['ocupadas']['c']=0;
			
			$puestas = 0;	//pongo las camas ocupadas de columna en columna, para que queden de izq a der
			while($actual_camas > 0){	//mientras queden camas por poner
				for($busq = $actual; $busq <= $contador && $actual_camas != 0; $busq++){	//recorro de la 1º a la ultima columna de la habitacion
					if($habita[$busq]['camas'] != $habita[$busq]['ocupadas']['c']){	//si no se ha llegado al maximo de camas de la columna
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['fecha'] = $temporal[$puestas]['fecha'];	
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['fecha_s'] = $temporal[$puestas]['fecha_s'];
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['reserva'] = $temporal[$puestas]['reserva'];
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['id_color'] = $temporal[$puestas]['id_color'];	
						$habita[$busq]['ocupadas']['c']++;	//le pongo una cama mas
						$puestas++;
						$actual_camas --;
					}
				}
			};
			for($busq = $actual; $busq <= $contador; $busq++){ //le pongo el numero de columnas a todas las columnas de la habitacion
				$habita[$busq]['columnas'] = $columnas;
			}
		}
		for ($s=0;$s<count($tipo_Hab);$s){
			if($tupla_dist['Id_Tipo_Hab']!=$tipo_Hab[$s]){
				$tipo_Hab[$cont]=$tupla_dist['Id_Tipo_Hab'];
				$cont++;
			}
		}
		$cont++;
	//}
	$contador++;
}
for($a = 1; $a < count($habita); $a++){
	if($habita[$a-1]['id'] == $habita[$a]['id']){
		$habita[$a-1]['columnas']=$habita[$a]['columnas'];
	}
}

for($a = 0; $a < count($habita); $a++){ //calculo cuantas camas ocupadas, reservadas y temporales tiene cada columna para ordenarlo
	$habita[$a]['ocupadas']['c_o'] = 0;
	$habita[$a]['ocupadas']['c_r'] = 0;
	$habita[$a]['ocupadas']['c_t'] = 0;
	for($o = 0; $o < $habita[$a]['ocupadas']['c']; $o++){
		$temporal = $habita[$a]['ocupadas1'][$o]['fecha'];
		$fecha_lle_ocu = mktime(0, 0, 0, substr($temporal,5,2), substr($temporal,8,2), substr($temporal,0,4));
		$temporal = $habita[$a]['ocupadas1'][$o]['fecha_s'];
		$fecha_sal_ocu = mktime(0, 0, 0, substr($temporal,5,2), substr($temporal,8,2), substr($temporal,0,4));
		if($fecha_lle_ocu <= $fecha_sel && $fecha_sal_ocu >= $fecha_sel){
			if($habita[$a]['ocupadas1'][$o]['reserva'] == 's'){
				$habita[$a]['ocupadas']['c_r'] ++;
			}
			else{
				$habita[$a]['ocupadas']['c_o'] ++;
			}
		}else{
			$habita[$a]['ocupadas']['c_t']++;
		}
	}
}

if(mysql_num_rows($res_dist) != 0){ //comprueba que existen habitaciones;
$con_cols = 0;
//busco todos los tipos de habitacion y cuento cuantas habitaciones de cada tipo hay																			

$sql = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab where camas_hab <> -1 AND tipo_habitacion.Reservable = 'S' GROUP BY tipo_habitacion.nombre_tipo_hab ORDER BY tipo_habitacion.Id_Tipo_Hab";													//quitar la 2º condición del where si se quiere mostrar todas las habitaciones

$res_tipo=mysql_query($sql);
$num = mysql_num_rows($res_tipo);

$tipo_habitacion = array();
for($d = 0; $d < $num; $d++){	//lo paso a un array para poder ordenarlo
	$fila_res_tipo=mysql_fetch_array($res_tipo);
	$tipo_habitacion[$d]['tipo'] = $fila_res_tipo['Nombre_Tipo_Hab'];
	$tipo_habitacion[$d]['id'] = $fila_res_tipo['Id_Tipo_Hab'];
	for($h = 0; $h < count($habitaciones_orden); $h++){ 	//busco la pagina que le corresponde al tipo de habitacion
		if($fila_res_tipo['Id_Tipo_Hab'] == $habitaciones_orden[$h]['Id_Tipo_Hab']){
			$tipo_habitacion[$d]['pagina'] = $habitaciones_orden[$h]['pagina'];
		}
	}
}

foreach ($tipo_habitacion as $key => $row){	//ordeno el array por tipo de habitacion
	for($i = 0; $i < count($habitaciones_orden); $i++){
		if($row['id'] == $habitaciones_orden[$i]['Id_Tipo_Hab'])
			$valor = $habitaciones_orden[$i]['orden'];
	}
	$tipo_hab[$key] = $valor;
}
array_multisort ($tipo_hab, SORT_ASC, $tipo_habitacion);

echo "<tr>";	//la 1º fila es el nombre de los tipos de habitación
echo "<td height='292' rowspan='" . ($max_camas+3) . "'><font size='1'>&nbsp;</font></td>";	//pongo una columna vacia al principio
$grupos_col = array(); //grupos_col es un array que contiene cuantas columnas tiene cada tipo de habitación
for($d = 0; $d < count($tipo_habitacion); $d++){	//pongo la cabezera de la tabla
	if($pagina == $tipo_habitacion[$d]['pagina']){	//si el tipo de habitación esta en la ventana
		$columnas = 0;	
		for ($cama=0;$cama<count($habita);$cama++){
			if($habita[$cama]['tipo'] == $tipo_habitacion[$d]['id']){	//cuento cuantas habitaciones hay de este tipo
				$columnas++;
			}
		}
		$grupos_col[$tipo_habitacion[$d]['tipo']] = $columnas; // lo que es el número de columnas de este tipo de habitación
		$con_cols++;							//pongo una celda con el nombre del tipo de habitación
		echo "<td colspan='" . ($columnas) . "' align='center'><font class='nom_tipo_hab' style='cursor:default;'>" .$tipo_habitacion[$d]['tipo'] . "</font></td>";
		if($d != $num-1)	//y una celda con la linea blanca, si no es el ultimo tipo de habitación a poner
		{
			echo "<td rowspan='" . ($max_camas+3) . "'>&nbsp;</td>";
			echo "<td rowspan='" . ($max_camas+3) . "' class='separar_hab' width='2px'></td>";
			echo "<td rowspan='" . ($max_camas+3) . "'>&nbsp;</td>";
			//echo "<td class='linea_blanca' rowspan='" . ($max_camas+3) . "' background='../imagenes/img_tablas/linea1.gif'>&nbsp;</td>";
		}
	}
}
echo "<td rowspan='" . ($max_camas+3) . "'><font size='1'>&nbsp;</font></td>"; //pongo una columna vacia al final
echo "</tr>";
$con_cols ++;

foreach ($habita as $key => $row){	//ordeno el array por tipo de habitacion, por el id, por el número de camas, el el número de camas ocupadas
	$orden1[$key]  = $row['orden'];
	for($i = 0; $i < count($habitaciones_orden); $i++){
		if($row['tipo'] == $habitaciones_orden[$i]['Id_Tipo_Hab'])
			$valor = $habitaciones_orden[$i]['orden'];
	}
	$tipo[$key] = $valor;
	$camas_1[$key] = $row['camas'];
	$ocupadas[$key] = $row['ocupadas']['c'];
	$c_temporales[$key] = $row['ocupadas']['c_t'];
	$c_ocupadas[$key] = $row['ocupadas']['c_o'];
	$c_reservadas[$key] = $row['ocupadas']['c_r'];
}
array_multisort ($tipo, SORT_ASC, $orden1, SORT_ASC, SORT_NUMERIC, $camas_1, SORT_DESC, $ocupadas, SORT_DESC, $c_ocupadas, SORT_DESC, $c_reservadas, SORT_DESC, $c_temporales, SORT_DESC, $habita);

$nombres_habitacion = array(); //guardo los nombres de las habitaciones que se van poniendo
$num_text = 0;
$contadores = array();
$hab_quitar = array();
$componentes = 0;
$contadores_maximo = array(count($habita)); //esta variable se usa cuando se calculan las habitaciones y las camas del grupo seleccionado
for ($cama = 0; $cama < count($habita) && $habita[$cama]['id'] != ""; $cama++){ //creo un contador para cada habitacion
	$contadores[$habita[$cama]['id']]=1;
	$contadores_maximo[$cama]=$habita[$cama]['ocupadas']['c'];
	if($habita[$cama]['ocupadas']['c'] != 0 && $habita[$cama]['total_camas'] < $habita[$cama]['ocupadas']['c']){
		$hab_quitar[$habita[$cama]['id']] = $habita[$cama]['ocupadas']['c'] - $habita[$cama]['total_camas'];
	}
}
//Recorremos cada una de las filas posibles
for ($fila=0;$fila<=$max_camas;$fila++){
	
	//Recorremos las camas de cada fila
	for ($cama=0;$cama<count($habita) && $habita[$cama]['id'] != "";$cama++){
		for($h = 0; $h < count($tipo_habitacion); $h++){ 	//busco la pagina que le corresponde al tipo de cama
			if($tipo_habitacion[$h]['id'] == $habita[$cama]['tipo']){
				$pagina_cama = $tipo_habitacion[$h]['pagina'];
			}
		}
		if($pagina == $pagina_cama && $habita[$cama]['puede_reservar'] == 'S'){ //si se quiere mostrar todas las habitaciones quitar la 2º condición
			if($fila==0){//Para la primera fila, metemos en Id_Hab
				if(!IN_ARRAY($habita[$cama]['id'],$nombres_habitacion)){ //si no se ha puesto todavia el nombre de la habitacion
					$nombres_habitacion[count($nombres_habitacion)] = $habita[$cama]['id'];
					if($habita[$cama]['puede_reservar'] == 'N'){
						echo ('<td class="nom_hab" align="center" colspan="'.$habita[$cama]['columnas'].'">'.$habita[$cama]['id'].'</td>');
					}
					else{
						
						echo ('<td class="nom_hab_online" align="center" colspan="'.$habita[$cama]['columnas'].'" id="' . $habita[$cama]['id'] .  '"');
						echo (" onClick=\"asignar_hab('".$habita[$cama]['id']."','".$mod."','".$num."','" . $habita[$cama]['total_camas'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['tipo'] . "')\">");
						echo ($habita[$cama]['id'].'</td>');
					}
				}
			}
			else{//Para el resto de filas, metemos un estilo dependiendo del estado de la habitación
				$contador=$contadores[$habita[$cama]['id']];
				$contadores[$habita[$cama]['id']]++;
				if ($habita[$cama]['ocupadas']['c']>0){
					$fecha_sal_b = mktime(0, 0, 0, strftime("%m",$fecha_sal), strftime("%d",$fecha_sal) + 1, strftime("%Y",$fecha_sal));
					$f_tem = $habita[$cama]['ocupadas1'][$fila-1]['fecha'];
					$fecha_tem_l = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2), substr($f_tem,0,4));
					$f_tem = $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'];
					$fecha_tem_s = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2)-1, substr($f_tem,0,4));
					if(isset($hab_quitar[$habita[$cama]['id']]) && $hab_quitar[$habita[$cama]['id']] >= $contador){
						$f_tem = $habita[$cama]['ocupadas1'][$habita[$cama]['total_camas']+$contador]['fecha_s'];
						$fecha_tem_s_q = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2), substr($f_tem,0,4));
						if($fecha_tem_s_q <= $fecha_sal_b){
							$habita[$cama]['ocupadas1'][$fila-1]['fecha'] = $habita[$cama]['ocupadas1'][$habita[$cama]['total_camas']+$contador]['fecha'];
							$habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] = $habita[$cama]['ocupadas1'][$habita[$cama]['total_camas']+$contador]['fecha_s'];
						}
					}
					$f_tem = $habita[$cama]['ocupadas1'][$fila-1]['fecha'];
					$fecha_tem_l1 = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2), substr($f_tem,0,4));
					$f_tem = $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'];
					$fecha_tem_s1 = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2)-1, substr($f_tem,0,4));
					if($habita[$cama]['ocupadas1'][$fila-1]['id_color'] != "")
						$estilo = "style='background-color:#".$habita[$cama]['ocupadas1'][$fila-1]['id_color']."'";
					else
						$estilo = "";
					if($fecha_tem_l1 <= $fecha_lle && $fecha_tem_s1 >= $fecha_sal_b){ //la cama no esta disponible en ninguna fecha
						if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
							echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("</td>");
						}else{
							echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("</td>");
						}
					}
					else if($fecha_tem_l1 <= $fecha_lle && $fecha_tem_s1 < $fecha_sal_b){ //la cama no esta disponible ahora pero si mas adelante
						if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
							echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("</td>");
						}
						else{
							echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("</td>");
						}
					}
					else if($fecha_tem_l1 > $fecha_lle && $fecha_tem_s1 < $fecha_sal_b){ //la cama esta disponible ahora, no mas adelante, pero si luego
						if($habita[$cama]['puede_reservar'] == 'S'){
								if($fecha_tem_l1 <= $fecha_sel){
									if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
										echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
										echo ("</td>");
									}else{
										echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
										echo ("</td>");
									}
								}
								else{
									$fecha_tem = mktime(0, 0, 0, substr($habita[$cama]['cambio_tipo'],5,2), substr($habita[$cama]['cambio_tipo'],8,2), substr($habita[$cama]['cambio_tipo'],0,4));
									if($habita[$cama]['cambio_tipo'] != "" && $fecha_tem < $fecha_sal_b){
											echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
											$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
											echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['cambio_tipo'] . "\",\"0\",\"1\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
											echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','1','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['tipo'] . "')\">" . substr($habita[$cama]['cambio_tipo'],8,2) . "·" . substr($habita[$cama]['cambio_tipo'],5,2));
											echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
											echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['cambio_tipo'] . "' size='1'>");
											echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
											echo "</td>";
											$num_text ++;
									
									}
									else{
										echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
										$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
										echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
										echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','2','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "','" . $habita[$cama]['compartida'] . "')\">" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],8,2) . "·" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],5,2));
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
										echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "' size='1'>");
										echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
										echo "</td>";
										$num_text ++;
									}
								}
						}else
							echo ("<td class='cama_libre'>&nbsp;</td>");
					}
					else{	//la cama esta disponible ahora, pero no más delante
						if($fecha_tem_l1 <= $fecha_sel)
							if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
								echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
								echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
								echo ("</td>");
							}else{
								echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
								echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
								echo ("</td>");
							}
						else{
							if($habita[$cama]['puede_reservar'] == 'S'){
								$fecha_tem = mktime(0, 0, 0, substr($habita[$cama]['cambio_tipo'],5,2), substr($habita[$cama]['cambio_tipo'],8,2), substr($habita[$cama]['cambio_tipo'],0,4));
								if($habita[$cama]['cambio_tipo'] != "" && $fecha_tem < $fecha_sal_b){
										echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
										$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
										echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['cambio_tipo'] . "\",\"0\",\"1\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
										echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','1','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['tipo'] . "')\">" . substr($habita[$cama]['cambio_tipo'],8,2) . "·" . substr($habita[$cama]['cambio_tipo'],5,2));
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
										echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['cambio_tipo'] . "' size='1'>");
										echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
										echo "</td>";
										$num_text ++;
								
								}
								else{
									echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
									$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
									echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
									echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','2','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "','" . $habita[$cama]['compartida'] . "')\">" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],8,2) . "·" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],5,2));
									echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
									echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "' size='1'>");
									echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
									echo "</td>";
									$num_text ++;
								}
							}
							else
								echo ("<td class='cama_libre'>&nbsp;</td>");
						}
					}
					$habita[$cama]['ocupadas']['c']--;
					$habita[$cama]['camas']--;
					
				}else{
					if ($habita[$cama]['camas']>0){ //la cama esta libre todos los días
						$fecha_sal_b = mktime(0, 0, 0, strftime("%m",$fecha_sal), strftime("%d",$fecha_sal) + 1, strftime("%Y",$fecha_sal));
						$fecha_tem = mktime(0, 0, 0, substr($habita[$cama]['cambio_tipo'],5,2), substr($habita[$cama]['cambio_tipo'],8,2), substr($habita[$cama]['cambio_tipo'],0,4));
						if($habita[$cama]['cambio_tipo'] != "" && $fecha_tem < $fecha_sal_b){
								echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
								$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
								echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['cambio_tipo'] . "\",\"0\",\"1\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
								echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','1','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['tipo'] . "')\">" . substr($habita[$cama]['cambio_tipo'],8,2) . "·" . substr($habita[$cama]['cambio_tipo'],5,2));
								echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
								echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['cambio_tipo'] . "' size='1'>");
								echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
								echo "</td>";
								$num_text ++;
						
						}
						else if($habita[$cama]['puede_reservar'] == 'S'){	
							echo ("<td class='cama_libre_con' id='td" . $habita[$cama]['id']  . "_" .  $contador . "'");
							$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
							echo (" OnMouseOver='resaltar_celda(this);'  OnMouseOut='desresaltar_celda(this);'");
							echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','0','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "','" . $habita[$cama]['compartida'] . "')\">");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'></td>");
							$num_text ++;
						}
						else
							echo ("<td class='cama_libre'>&nbsp;</td>");
						$habita[$cama]['camas']--;
						
					}else{
						echo ("<td id=\"no_cama\">&nbsp;</td>");
						$contadores[$habita[$cama]['id']]--;
					}
				}
			}
		}
	}
	echo "</TR>";

}

if(count($grupos_col)!= 0){
	foreach ($grupos_col as $key => $row){
		echo ("<td colspan='" . $row . "'><font size='1'>&nbsp;</font></td>");
	}
	echo "</tr>";
}
}
else{	//si no se encuentran habitaciones se muestra un mensaje al usuario
echo "<tr><td align='center' height='292px'><font color='#FFFFFF'><b>No se puede mostrar el mapa de habitaciondes, compruebe que hay habitaciones introducidas en el sistema.</b></font></td></tr>";
}

?>

		<input name="num_text" id="num_text" type="hidden" value="<?=$num_text?>">		<!-- guarda el numero de camas que se pueden asignar -->
		<input name="hab_selec" id="hab_selec" type="hidden" value="<?=$_POST['hab_selec']?>">	<!-- guarda las camas seleccionadas de los días que se han visto -->
		<input name="fec_selec" id="fec_selec" type="hidden" value="<?=$_POST['fec_selec']?>">	<!-- guarda los días que se han visto -->
		<input name="es_inc_i" id="es_inc_i" type="hidden" value="0">			<!-- guarda el numero de camas individuales seleccionadas que son incompletas -->
		<input name="es_inc_c" id="es_inc_c" type="hidden" value="0">			<!-- guarda el numero de camas compartidas seleccionadas que son incompletas -->
		<input name="ir_a_dia" id="ir_a_dia" type="hidden" value="<?=$_POST['ir_a_dia']?>">	<!-- guarda las fechas de las camas incompletas, para ir a ellas automaticamente -->
		<input name="cambio_tipo" id="cambio_tipo" type="hidden" value="<?=$_POST['cambio_tipo']?>">

		<script>
			var fal_in = 0; //indica el número de camas individuales asignadas anteriormente y que ahora no se pueden asignar
			var fal_co = 0; //indica el número de camas compartidas asignadas anteriormente y que ahora no se pueden asignar
			var cambi = 0; //indica si alguno de los dos valores anteriores cambia de valor
			cambiar_camas = 0;
			quitar_cambio_hab('<?=strftime("%d/%m/%Y",$fecha_sel)?>','');
			cambio_tipo_hab = "<?=$_POST['cambio_tipo']?>";	
			quitar_dia('<?=strftime("%d/%m/%Y",$fecha_sel)?>');	

		</script>
		<?	
		$fechas = split("\+",$_POST['fec_selec']); //array con todas las fechas que se han visto
		$habita = split("\*",$_POST['hab_selec']); //array con todos las camas que se han asignado
		$tem = strftime("%d/%m/%Y",$fecha_sel);
		$enc = -1;
		$pos = -1;
		
		$ult = $fechas[count($fechas)-2];
		for($i = 0; $i <= count($fechas)-1; $i++){ //recorro el array de fechas mirando si la ultima fecha esta repetida
			if($fechas[$i] == $ult){
				$enc ++;
				if($pos == -1)
					$pos = $i;
			}
		}
		if($enc == 1){ //si la ultima fecha esta repetida
			$cad = "";
						//recorro los arrays de fechas y habitaciones dejando la cadena de forma que las demas fechas quedan igual, 
						// pero la que se repite se pone la ultima posicion, y esta se quita
			for($i = 0; $i < count($habita)-2; $i++){
				if($i != $pos)
					$cad = $cad . $habita[$i];
				else
					$cad = $cad . $habita[count($habita)-2];
				$cad = $cad . "*";
			}
			echo "<script>document.getElementById('hab_selec').value='" . $cad . "'</script>";
			$cad="";
			for($i = 0; $i < count($fechas)-2; $i++){
				if($i != $pos)
					$cad = $cad . $fechas[$i];
				else
					$cad = $cad . $fechas[count($fechas)-2];
				$cad = $cad . "+";
			}
			echo "<script>document.getElementById('fec_selec').value='" . $cad . "'</script>";
		}
		//--esto sirve para mantener las camas seleccionadas anteriormente--
		$enc = -1;		//compruebo si la fecha que se esta viendo esta en el array de fechas
		for($i = 0; $i < count($fechas); $i++){
			if($fechas[$i] == $tem){
				$enc = $i;
			}
		}
		if($enc != -1) // si se encuentra se toma su posicion
			$ele = $enc;
		else{
			$ele = count($fechas); // si no se toma la posicion del ultimo elemento
			if($habita[$ele] == "")
				$ele -= 1;
		}

		$habitaciones_cambio = array();
		$cambio_hab = split("\*",$_POST['cambio_tipo']);
		$cont = 0;
		for($i = 0; $i < count($cambio_hab)-1; $i++){
			$datos_cambio = split("\-",$cambio_hab[$i]);
			if($tem == $datos_cambio[1]){
				$habitaciones_cambio[$cont] = array();
				$habitaciones_cambio[$cont]['id_hab'] = $datos_cambio[0];
				$habitaciones_cambio[$cont]['tipo_ant'] = $datos_cambio[2];
				$habitaciones_cambio[$cont]['indi'] = $datos_cambio[3];
				$cont ++;
			}
		}
		
		if($ele >= 0){
			$c_ante = split("-",$habita[$ele]);	//array que contiene todas las camas seleccionadas de la posicion
			for($i = 0; $i < count($c_ante)-1; $i++){
				$nom = split("&",$c_ante[$i]); //corto el nombre de la cama
		?>				
				<script>
				cambi = 1; //si llega aqui es que las variables de camas incompletas asignadas anteriormente cambia
				maxi = <?=$max_camas;?>; //maximo de camas
				habit = '<?=$nom[0]?>';	
				cama = <?=$nom[1]?>;
				buscar = 0;
				habitaciones_indi = "";
				<?
					
				$sql = "select cambio_tipo_habitacion.id_tipo_hab as id_tipo_hab, habitacion.camas_hab as camas_hab, tipo_habitacion.compartida as ";
				$sql = $sql . "compartida from habitacion inner join cambio_tipo_habitacion on (habitacion.id_hab = cambio_tipo_habitacion.id_hab) inner join ";
				$sql = $sql . "tipo_habitacion on (cambio_tipo_habitacion.id_tipo_hab = tipo_habitacion.id_tipo_hab) where ";
				$sql = $sql . "habitacion.id_hab = '" . $nom[0] . "' and cambio_tipo_habitacion.fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' "; 
				$sql = $sql . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $nom[0] . "' and cambio_tipo_habitacion.fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "')";
								
				$res=mysql_query($sql);
				$fila=mysql_fetch_array($res);
				$tipo_actual = $fila['id_tipo_hab'];

				for($d = 0; $d < count($habitaciones_cambio); $d++){
					if($nom[0] == $habitaciones_cambio[$d]['id_hab'] && $tipo_actual != $habitaciones_cambio[$d]['tipo_ant']){
						?>
						buscar = 1;
						habitaciones_indi = "<?=$habitaciones_cambio[$d]['indi']?>";
						<?
					}
				}
				?>
				var exi = (document.getElementById("td"+habit+"_"+cama)) ? true:false; //dice si existe o no un elemento
				if(exi && buscar == 0){//si existe la celda correspondiente a la cama		
					enc = 0;
					puesto = 1; //se indica que se va a poner		
					for(i = 0; i < habit_sel.length && enc == 0; i++){ //recorro el array de habitaciones y su tipo
						var exi = (document.getElementById("inc"+habit+"-"+cama)) ? true:false;
						
						if(exi && habit_sel[i][0] == habit){
							enc = 1;
							if(habit_sel[i][2] == "N") //si el tipo en individual se aumenta el uno el contador de camas indidivuales incompletas asignadas
								document.getElementById('es_inc_i').value = eval(document.getElementById('es_inc_i').value) + 1;
							else //si no se aumenta el uno el contador de camas compartidas incompletas asignadas
								document.getElementById('es_inc_c').value = eval(document.getElementById('es_inc_c').value) + 1;
							fecha_tem = document.getElementById('inc_fecha'+habit+"-"+cama).value;
							fecha_tem = fecha_tem.substr(8,2)+"/"+fecha_tem.substr(5,2)+"/"+fecha_tem.substr(0,4);
							poner_dia(fecha_tem);
						}
					}
					document.getElementById("td"+habit+"_"+cama).className = "cama_asignada"; //se le pone que color verde
					document.getElementById(habit+"-"+cama).value='1';	//y se pone su hidden a 1 para indicar que se ha seleccionado
				}
				else{ //si todas las camas de la habitación estan ocupada o son temporales se indica que falta asignar una cama
					enc = 0;
					i = 0;
					contador = 0;
					for(i=0;i<habit_datos.length;i++){
						if(habit_datos[i][0] == habit){
							contador = habit_datos[i][2];
						}
					}
					if(contador != 0 && contador >= eval(cama)){
						enc = 1;
							if(habitaciones_indi == "N") //si es de tipo individual se indica que falta una cama individual mas por asignar
								fal_in ++;
							else	//si no se indica que falta una cama compartidas mas por asignar
								fal_co ++;
							cambiar_camas = 1;
					}else{
						var exi = (document.getElementById(habit+"-"+cama)) ? true:false; //dice si existe o no un elemento
						if(exi && buscar == 1){	
								enc = 1;
								if(habitaciones_indi == "N") //si es de tipo individual se indica que falta una cama individual mas por asignar
									fal_in ++;
								else	//si no se indica que falta una cama compartidas mas por asignar
									fal_co ++;
								cambiar_camas = 1;
						}
						else if(exi && document.getElementById(habit+"-"+cama).value == "ocupada"){	
							for(i; i < habit_sel.length && enc == 0; i++){//recorro el array de habitaciones y su tipo
								if(habit_sel[i][0] == '<?=$nom[0]?>'){
									enc = 1;
									if(habit_sel[i][2] == "N") //si es de tipo individual se indica que falta una cama individual mas por asignar
										fal_in ++;
									else	//si no se indica que falta una cama compartidas mas por asignar
										fal_co ++;
									
									cambiar_camas = 1;
								}
							}
						}
						else if(!exi && buscar == 1){
							enc = 1;
							if(habitaciones_indi == "N") //si es de tipo individual se indica que falta una cama individual mas por asignar
								fal_in ++;
							else	//si no se indica que falta una cama compartidas mas por asignar
								fal_co ++;
							cambiar_camas = 1;
						}
					}
				}
				</script>
		<?
			}
			//cambio de lugar las camas asignadas en temporales, si hay sitio en la habitacion
			for($i = 0; $i < count($c_ante)-1; $i++){
				$nom = split("&",$c_ante[$i]); //corto el nombre de la cama
				?>
					<script>
					habit = '<?=$nom[0]?>';	
					cama = <?=$nom[1]?>;
					buscar = 0;
					habitaciones_indi = "";
					<?
						
					$sql = "select cambio_tipo_habitacion.id_tipo_hab as id_tipo_hab, habitacion.camas_hab as camas_hab, tipo_habitacion.compartida as ";
					$sql = $sql . "compartida from habitacion inner join cambio_tipo_habitacion on (habitacion.id_hab = cambio_tipo_habitacion.id_hab) inner join ";
					$sql = $sql . "tipo_habitacion on (cambio_tipo_habitacion.id_tipo_hab = tipo_habitacion.id_tipo_hab) where ";
					$sql = $sql . "habitacion.id_hab = '" . $nom[0] . "' and cambio_tipo_habitacion.fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' "; 
					$sql = $sql . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $nom[0] . "' and cambio_tipo_habitacion.fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "')";
					$res=mysql_query($sql);
					$fila=mysql_fetch_array($res);
					$tipo_actual = $fila['id_tipo_hab'];

					for($d = 0; $d < count($habitaciones_cambio); $d++){
						if($nom[0] == $habitaciones_cambio[$d]['id_hab'] && $tipo_actual != $habitaciones_cambio[$d]['tipo_ant']){
							?>
							buscar = 1;
							habitaciones_indi = "<?=$habitaciones_cambio[$d]['indi']?>";
							<?
						}
					}
					?>
					var exi = (document.getElementById("inc"+habit+"-"+cama)) ? true:false; //dice si existe o no un elemento
					if(exi && buscar == 0){//si es una cama incompleta	
						pos_hab = -1; //busco cuantas camas tiene la habitacion
						for(bus = 0; bus < habit_datos.length && pos_hab == -1; bus++){
							if(habit_datos[bus][0] == habit)
								pos_hab = bus;
						}
						enc = 0;
						pos_cama = 0;	//busco la 1º cama libre de la habitación
						for(bus = 0; bus <= habit_datos[pos_hab][1] && enc == 0; bus++){
							var exi1 = (document.getElementById("td"+habit+"_"+bus)) ? true:false; //dice si existe o no un elemento
							if(exi1 && document.getElementById("td"+habit+"_"+bus).className == "cama_libre_con"){
								enc = 1;
								pos_cama = bus;
							}
						}
						if(enc == 1){	//si se encuentra una cama libre
							document.getElementById("td"+habit+"_"+cama).className = "cama_temp"; //restauro la cama actual
							document.getElementById(habit+"-"+cama).value='0';
							enc = 0;
							for(h = 0; h < habit_sel.length && enc == 0; h++){ //recorro el array de habitaciones y su tipo
								if(habit_sel[h][0] == habit){
									enc = 1;
									if(habit_sel[h][2] == "N") //si el tipo en individual se reduce el uno el contador de camas indidivuales incompletas asignadas
										document.getElementById('es_inc_i').value = eval(document.getElementById('es_inc_i').value) - 1;
									else //si no se aumenta el uno el contador de camas compartidas incompletas asignadas
										document.getElementById('es_inc_c').value = eval(document.getElementById('es_inc_c').value) - 1;
								}
							}
							ini = document.getElementById('inc_fecha'+habit+"-"+cama).value;
							ini = ini.substr(8,2)+"/"+ini.substr(5,2)+"/"+ini.substr(0,4);
							quitar_dia(ini);
							
							document.getElementById("td"+habit+"_"+pos_cama).className = "cama_asignada";  //y selecciono la nueva
							document.getElementById(habit+"-"+pos_cama).value='1';
						}
					}
				</script>
				<?
			}
			
			$hab_compro = array();
			for($i = 0; $i < count($c_ante)-1; $i++){ //cambio las posiciones de las camas asignadas, para que aparezcan las 1º de la habitación
				$nom = split("&",$c_ante[$i]); //corto el nombre de la cama
				
				if(!in_array($nom[0],$hab_compro)){
					$hab_compro[count($hab_compro)] = $nom[0];
					?>
					<script>
						habit = '<?=$nom[0]?>';
						pos_hab = -1; //busco cuantas camas tiene la habitacion
						for(bus = 0; bus < habit_datos.length && pos_hab == -1; bus++){
							if(habit_datos[bus][0] == habit){
								pos_hab = bus;
							}
						}
						do{
							cambio = 0;
							for(bus = 1; bus <= habit_datos[pos_hab][1]; bus++){
								var exi1 = (document.getElementById("td"+habit+"_"+bus)) ? true:false; 
								var exi2 = (document.getElementById("td"+habit+"_"+(bus-1))) ? true:false; 
								if(exi1 && exi2 && document.getElementById("td"+habit+"_"+bus).className == "cama_asignada" && document.getElementById("td"+habit+"_"+(bus-1)).className == "cama_libre_con"){
									document.getElementById("td"+habit+"_"+(bus-1)).className = "cama_asignada"; 
									document.getElementById(habit+"-"+(bus-1)).value='1';
									document.getElementById("td"+habit+"_"+bus).className = "cama_libre_con"; 
									document.getElementById(habit+"-"+bus).value='0';
									cambio = 1;								
								}
							}
						}while(cambio == 1);
					</script>
					<?
				}
			}	

		}
		//--aqui acaba el codigo para mantener las camas seleccionadas
		?>
			</table>		
                    <table width="" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td width="50" align="left" class='label_formulario'>Faltan:</td>
                        <td width="130" align="left" class='label_formulario'>Camas Individuales 
						<div id="leyenda" style="position:absolute;  background-color: #FFFFFF; border: 1px none #000000;  visibility: hidden;z-index: 1;font-size:10px;margin-left:3px;margin-top:-7px;width:400px;color:#064C87" > 
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
						</td>
						</td>
                        <td width="20" align="left" class='label_formulario'> 
                          <? 
							  if(isset($_POST['f_indi']))
								$tem = $_POST['f_indi'];
							  else
								$tem = $camas_com - $comp ;
							  ?>
                          <input type="text" name="f_indi" id="f_indi" value="<?=$tem?>" size="2" readonly="true" class="input_formulario">
                        </td>
									
                        <td width="135" align="right" class='label_formulario'>Camas 
						Compartidas 
						</td>
                        <td width="20" align="left" class='label_formulario'> 
                          <?
										if(isset($_POST['f_camas']))
											$tem = $_POST['f_camas'];
										else
											$tem = $comp;
										if($tem == ""){
											$tem = 0;
										}
									?>
                          <input type="text" name="f_camas" id="f_camas" value="<?=$tem?>" size="2" readonly="true" class="input_formulario">
						</td>			
                        <td height="20" class='label_formulario' align="right">
						
						<a href="#" onClick="ver_leyenda('2')" title="Ver la Leyenda de la Distribución de Habitaciones" OnMouseOver="window.status='Ver la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true"> 
                        Leyenda</a></td>
								  </tr>
								</table>
							</td>
						</tr>
					</table>
			</td></tr></table>
		<? if($_POST['mover_pag'] == "0"){ ?>
		<script>
			if(cambi == 1){ // si se ha cambiado el valor de las camas incompletas asignadas anteriormente 
				document.getElementById("f_indi").value = fal_in;		//se cambia el valor de los text que indican el numero de camas que faltan por asignar
				document.getElementById("f_camas").value = fal_co;
			}
			if(cambiar_camas == 1){	//quito de las habitaciones seleccionadas de la fecha las que estan ocupada en la fecha actual

				var cad = "";
				seleccionadas = Array();
				seleccionadas_dias = Array();
				fechas = document.getElementById("fec_selec").value.split("+");
				if(document.getElementById("hab_selec").value != ""){
					seleccionadas_dias = document.getElementById("hab_selec").value.split("*");	//con esto se quitan las habitaciones deseleccionadas, cuando el usuario se mueve por las paginas de las habitaciones
					pos=0;
					if(document.getElementById("fec_selec").value == "")
						pos = 0;
					else{
						enc = 0;
						for(e = 0; e < fechas.length; e++){
							if(fechas[e] == document.getElementById("fecha_ver").value){
								pos = e;
								enc = 1;
							}
						}
						if(enc == 0)
							pos = seleccionadas_dias.length-1;
					}
					seleccionadas = seleccionadas_dias[pos].split("-");
					array_cambio_tipo_hab = cambio_tipo_hab.split("*");
					
					var mydate=new Date('<?=strftime("%Y",$fecha_sel)?>',eval('<?=strftime("%m",$fecha_sel)?>'-1),'<?=strftime("%d",$fecha_sel)?>',0,0,0);
					var year=mydate.getYear();
					if (year < 1000)
					year+=1900;										//creo la fecha seleccionada en javascript
					var day=mydate.getDay();
					var month=mydate.getMonth()+1;
					if (month<10)
					month="0"+month;
					var daym=mydate.getDate();
					if (daym<10)
					daym="0"+daym;
					dia_actual = daym+"/"+month+"/"+year;

					for(e = 0; e < seleccionadas.length-1; e++){ //recorro todas las habitaciones seleccionadas
						nom1 = seleccionadas[e].split("&");
						nom = nom1[0] + "-" + nom1[1];
						contador = 0;
						for(i=0;i<habit_datos.length;i++){
							if(habit_datos[i][0] == nom1[0]){
								contador = habit_datos[i][2];
							}
						}
						if(contador != 0 && contador >= eval(nom1[1])){
							nada = 1;
						}else{
							hacer = 0;
							for(c = 0; c < array_cambio_tipo_hab.length; c++){
								datos_cambio = array_cambio_tipo_hab[c].split("-");
								if(datos_cambio[0] == nom1[0] && datos_cambio[1] == dia_actual){
									hacer = 1;
								}
							}
							if(hacer == 1)
								nada = 1;
							else{
								var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
								if(exi && buscar == 0){	//si existe y sigue estando seleccionada, se mantiene en la cadena
									if(document.getElementById(nom).value == "1")
										cad = cad + seleccionadas[e] + "-";
								}
								else{
									//document.write(nom + " - " + buscar + "<br>");
									exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
									if(exi && document.getElementById(nom).value == "ocupada")
										nada = 1;	//si el text de la cama tiene valor ocupada no se tiene en cuenta
									else
										cad = cad + seleccionadas[e] + "-"; //si no existe es un habitacion de otra pagina, por lo que tambien se mantiene
								}
							}
						}
					}
				}
				document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas
				for(e = 0; e < seleccionadas_dias.length-1; e++){	//todas menos la ultima la dejo igual
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
				}
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
			}
			
			
		</script>
		<? } ?>
	</td>
      </tr>
      <tr> 
    <td valign="top" height="180">
	<br><br>
      <table border="0" align="center" width="700px" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<thead id="titulo_tablas">
			<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > <div class="champi_izquierda">&nbsp;</div>
              <div class="champi_centro" style="width:640px"> 
              	Listado de Reservas On-Line
              </div>
              <div class="champi_derecha">&nbsp;</div></td>
            </tr>
			</thead>
				<tbody class='tabla_detalles'>
			<tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:121px;" id="tabla_listado">
				<?
					if($_POST['ordenar'] == '')
						$ordenar_lis = $_GET['ordenar'];
					else
						$ordenar_lis = $_POST['ordenar'];
				?>
				<input type="hidden" id="ordenar" name="ordenar" value="<?=$ordenar_lis?>"> <!-- guarda el criterio de ordenación del listado -->
				<input type="hidden" id="cambiar_orden" name="cambiar_orden" value="0">
               <table border="0" cellpadding="0" cellspacing="0" width="681px" class="scrollTable">
                <thead class="fixedHeader">
                  <tr> 
                    <th width="100" align="center" style="cursor:pointer;" onClick="ordenar_listado('dni')" title="Ordenar el listado por el localizador" onmouseover='window.status="Ordenar el listado por el localizador" ; return true' onmouseout='window.status="Listo" ; return true'>
							D.N.I.
					</th>
                    <th align="center" style="cursor:pointer;" onClick="ordenar_listado('nombre')" title="Ordenar el listado por el nombre" onmouseover='window.status="Ordenar el listado por el nombre" ; return true' onmouseout='window.status="Listo" ; return true'>
							Nombre
					</th>
                    <th width="80px" align="center" style="cursor:pointer;" onClick="ordenar_listado('fecha')" title="Ordenar el listado por la fecha" onmouseover='window.status="Ordenar el listado por la fecha" ; return true' onmouseout='window.status="Listo" ; return true'>
							Fecha
					</th>
                    <th width="20px" align="center" style="cursor:pointer;" onClick="ordenar_listado('pn')" title="Ordenar el listado por el número de noches" onmouseover='window.status="Ordenar el listado por el número de noches" ; return true' onmouseout='window.status="Listo" ; return true'>
							PN
					</th>
					<th width="20px" align="center">T</th>
                    <th width="20px" align="center">M</th>
                  </tr>
                </thead>
				<?
					$sql = "select detalles.localizador_reserva as loca, reserva.dni_pra as dni, reserva.fecha_llegada as llega, ";
					$sql = $sql . "detalles.pernocta as pn, pra.nombre_pra as nom, pra.apellido1_pra as ape1, pra.apellido2_pra as ape2 ";
					$sql = $sql . "from reserva INNER JOIN detalles ON ";
					$sql = $sql . "(reserva.dni_pra = detalles.dni_pra and reserva.fecha_llegada = detalles.fecha_llegada) ";
					$sql = $sql . "INNER JOIN pra ON (reserva.dni_pra = pra.dni_pra) ";
					$sql = $sql . "where reserva.id_hab = 'PRA'";	//selecciono todas la reservas on-line
					if($ordenar_lis == "dni"){	//si que quiere ordenar el listado por el dni 
						if($_POST['cambiar_orden'] == '1'){
							$_SESSION['orden_hab_online']['dni']++;
							$_SESSION['orden_hab_online']['nombre'] = 1;	//se ponen las ordenaciones de los demás tipos al punto inicial
							$_SESSION['orden_hab_online']['fecha'] = 1;
							$_SESSION['orden_hab_online']['pn'] = 1;
							$_SESSION['orden_hab_online']['orden'] = 'dni';
						}
						$sql = $sql . " ORDER BY dni";
						if($_SESSION['orden_hab_online']['dni'] % 2 == 0)	//si la ordenación por el dni es impar se ordena de forma descendente, si no es ascendente
							$sql = $sql . " DESC";
					}		//se hace de la misma forma para los demás criterios de ordenación
					else if($ordenar_lis == "nombre"){
						if($_POST['cambiar_orden'] == '1'){
							$_SESSION['orden_hab_online']['nombre']++;
							$_SESSION['orden_hab_online']['dni'] = 1;
							$_SESSION['orden_hab_online']['fecha'] = 1;
							$_SESSION['orden_hab_online']['pn'] = 1;
							$_SESSION['orden_hab_online']['orden'] = 'nom, ape1, ape2';
						}
						if($_SESSION['orden_hab_online']['nombre'] % 2 == 0)
							$sql = $sql . " ORDER BY nom DESC, ape1 DESC, ape2 DESC";
						else
							$sql = $sql . " ORDER BY nom, ape1, ape2";
					}
					else if($ordenar_lis == "fecha"){
						if($_POST['cambiar_orden'] == '1'){
							$_SESSION['orden_hab_online']['fecha']++;
							$_SESSION['orden_hab_online']['dni'] = 1;
							$_SESSION['orden_hab_online']['nombre'] = 1;
							$_SESSION['orden_hab_online']['pn'] = 1;
							$_SESSION['orden_hab_online']['orden'] = 'llega';
						}
						$sql = $sql . " ORDER BY llega";
						if($_SESSION['orden_hab_online']['fecha'] % 2 == 0)
							$sql = $sql . " DESC";
					}
					else if($ordenar_lis == "pn"){
						if($_POST['cambiar_orden'] == '1'){
							$_SESSION['orden_hab_online']['pn']++;
							$_SESSION['orden_hab_online']['dni'] = 1;
							$_SESSION['orden_hab_online']['nombre'] = 1;
							$_SESSION['orden_hab_online']['fecha'] = 1;
							$_SESSION['orden_hab_online']['orden'] = 'pn';
						}
						$sql = $sql . " ORDER BY pn";
						if($_SESSION['orden_hab_online']['pn'] % 2 == 0)
							$sql = $sql . " DESC";
					}
					
					$res = mysql_query($sql);
					$num = mysql_num_rows($res);
					if($num != 0){
						for ($i = 0; $i < $num; $i++){
							$fila = mysql_fetch_array($res);
							$fecha = substr($fila['llega'],8,2) . "/" . substr($fila['llega'],5,2) . "/" . substr($fila['llega'],0,4);	//compongo la fecha en formato dd/mm/aaaa
			  	?>
                <tbody class="scrollContent">
                  <tr id='texto_listados' onmouseover='resaltar_seleccion(this);' onmouseout='desresaltar_seleccion(this);'> 
					<td align="center" title='<?=$fila['dni']?>'><?=substr($fila['dni'],0,9)?></td>
                    <td align="left"><?=$fila['nom'] . " " . $fila['ape1'] . " " . $fila['ape2']?></td>
                    <td align="center"><?=$fecha?></td>
                    <td align="center"><?=$fila['pn']?></td>
                    <td align="center"><a href='.?pag=reservas_online.php&dni_ver=<?=$fila["dni"]?>&llegada_ver=<?=$fila["llega"]?>&ordenar=<?=$ordenar_lis?>' onmouseover='window.status="Tratar la Solicitud de Reserva On-Line" ; return true' onmouseout='window.status="Listo" ; return true'>
						<img src="../imagenes/botones/detalles.gif" border="0" align="absmiddle" alt="Tratar la Solicitud de Reserva On-Line">
					</a></td>
                    <td align="center"><a href='.?pag=reservas_online.php&dni_ver=<?=$fila["dni"]?>&llegada_ver=<?=$fila["llega"]?>&ordenar=<?=$ordenar_lis?>&mod=si' onmouseover='window.status="Modificar la Solicitud de Reserva On-Line" ; return true' onmouseout='window.status="Listo" ; return true'>
						<img src="../imagenes/botones/modificar.gif" width="20" height="24" border="0" align="absmiddle" alt="Modificar la Solicitud de Reserva On-Line">
					</a></td>
				  </tr>
                </tbody>
				<?
						}
					}
				?>
              </table>  
              </div>
                </td>
            </tr>
          </table>
    </td>
      </tr></tbody>
    </table>
</form>

<?php 	
		mysql_close($db);
	} //Fin del IF de comprobacion de acceso a la pagina
	else
	 echo "<div class='error'><center><label id='texto_detalles' style='color:red;'>NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA</label></center></div>";
?>