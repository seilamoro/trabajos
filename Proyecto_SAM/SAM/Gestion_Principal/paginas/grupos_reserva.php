<link rel="STYLESHEET" type="text/css" href="./css/dhtmlxcombo.css">
	<script>
		window.dhx_globalImgPath="./imgs/";
	</script>
	<script  src="./js/dhtmlxcommon.js"></script>
	<script  src="./js/dhtmlxcombo.js"></script>
<?
if(isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
		// Devuelve un booleano indicando si existe la tabla <<$table_name>> en la base de datos <<$base_datos>>.
        function table_exists($table_name,$base_datos)
        {
            $Table = mysql_query("show tables like '" .$table_name . "'",$base_datos);
            if(mysql_fetch_row($Table) == false)
            return(false);
            else
            return(true);
         }
	
// Variable booleana que almacena si la tabla servicios existe
$servicios_activados = table_exists(tipo_servicios,$db);
if($servicios_activados){

$db2 = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
}
	@ $db = mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']);
	if($servicios_activados){
	@mysql_select_db("information_schema",$db2);
	}
	mysql_select_db($_SESSION['conexion']['db']);

	
	$sql_tipo_hab ="select * from tipo_habitacion where Reservable='S'";
	$result_tipo_hab = mysql_query($sql_tipo_hab);
	for ($i=0;$i<mysql_num_rows($result_tipo_hab);$i++){
			$fila=mysql_fetch_array($result_tipo_hab);
			$reservables[$i]=$fila['Id_Tipo_Hab'];
	}
	$paginas_validas = array();		//guardo en un array los n�mero de las p�ginas que tiene alg�n tipo de habitaci�n que sea reservable
	for ($i = 0; $i < count($_SESSION['pag_hab']); $i++){
		if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$paginas_validas)&& isset($reservables)) {	//si la p�gina no esta en las p�ginas validas
			$poner = false;
			$comprobar = $_SESSION['pag_hab'][$i]['pagina'];
			for($p = 0; $p < count($_SESSION['pag_hab']); $p++){ //compruebo si la p�gina tiene alguna habitaci�n reservable	
				if($_SESSION['pag_hab'][$p]['pagina'] == $comprobar && IN_ARRAY($_SESSION['pag_hab'][$p]['Id_Tipo_Hab'],$reservables)){
					$poner = true;
				}
			}
			if($poner){	//si la p�gina tiene alguna habitaci�n reservable es una p�gina valida
				$paginas_validas[count($paginas_validas)] = $comprobar;
			}
		}
	}
	

	//Consulta para saber la pagina actual
	if (isset($_POST['dist_num_pag'])){
		$_SESSION['gdh']['dis_hab']['num_pag'] = $_POST['dist_num_pag'];

	}
	$pagina = $_SESSION['gdh']['dis_hab']['num_pag'];
	if(!IN_ARRAY($pagina,$paginas_validas)){ //si la p�gina no es una p�gina valida, se pone la 1� p�gina
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

?>
<link rel="stylesheet" type="text/css" href="css/habitaciones_online.css">
<link rel="stylesheet" type="text/css" href="css/habitacionesColores.css">
<link rel="stylesheet" type="text/css" href="css/formulario_grupos.css">
<script src="./paginas/capas.js" type="text/javascript"></script>
<script language="javascript">
function validar_fecha(Cadena,mensaje){
										var Fecha= new String(Cadena)	// Crea un string
										var RealFecha= new Date()	// Para sacar la fecha de hoy
										// Cadena A�o
										var Ano= new String(Fecha.substring(Fecha.lastIndexOf("-")+1,Fecha.length))
										// Cadena Mes
										var Mes= new String(Fecha.substring(Fecha.indexOf("-")+1,Fecha.lastIndexOf("-")))
										// Cadena D�a
										var Dia= new String(Fecha.substring(0,Fecha.indexOf("-")))
									estado=true;
										// Valido el a�o
										if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){
									       // 	alert('A�o inv�lido')
											estado = false;
										}
										// Valido el Mes
										if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){
										//	alert('Mes inv�lido')
											estado = false;
										}
										// Valido el Dia
										if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){
											//alert('D�a inv�lido')
											estado = false;
										}
										if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {
											if (Mes==2 && Dia > 28 || Dia>30) {
												//alert('D�a inv�lido')
												estado = false;
											}
										}
	
  
  if(estado==false)
  		alert(mensaje);
		
  return estado;	
}

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

	function cambiar_color(a) {
	  	a.style.backgroundColor = "#064C87";
	  	a.style.color = "#F0FAFF";	  	
	}
	
	function devolver_color(a) {
		a.style.backgroundColor = "#F0FAFF";
	  	a.style.color = "#064C87";  	
	}
	
	function ver_leyenda(act){
		if(act == '1')
			document.getElementById('leyenda').style.visibility='hidden';
		else
			document.getElementById('leyenda').style.visibility='visible';
	}
			
	function resaltar(x,col) {
	  	x.style.backgroundColor  = col;
	}
	
	function resaltar_celda(td) {
	  	document.getElementById(td.id).className = "cama_resaltada";
	}
	
	function desresaltar_celda(td) {
		ele = td.id;														//se toma el id de la celda
		nom = ele.substr(2).split("_");										//se corta por el gui�n bajo, para tener la habitaci�n y el n�mero de cama por separado
		ele = nom[0] + "-" + nom[1];										//para obtener el id del text correspondiente a la celda
		if(document.getElementById(ele).value=="1")							//si el valor del text es 1, es que la celda est� asignada a la reserva
			document.getElementById(td.id).className = "cama_asignada";		//entonces que pone la clase de 'cama_asignada'
		else{															//si el valor del text no es 1, no est� asignada a la reserva
			var exi = (document.getElementById("inc"+nom[0]+"-"+nom[1])) ? true:false; 
			if(exi)
				document.getElementById(td.id).className = "cama_temp";	//y se pone la clase ''
			else
				document.getElementById(td.id).className = "cama_libre_con";	//y se pone la clase 'cama_libre_con'
		}
	}

		
	// funci�n para saber cuantos d�as tiene cada mes
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
	
	//una vez que sabemos cuantos dias tiene cada mes asignamos dinamicamente este numero al combo de los dias dependiendo del mes que aparezca en el combo de los meses
		function asignaDias(dia,mes,anyo){
		
            comboDias = document.getElementById(dia);
            comboMeses = document.getElementById(mes);
            comboAnyos = document.getElementById(anyo);

            Month = comboMeses[comboMeses.selectedIndex].text;
            Year = comboAnyos[comboAnyos.selectedIndex].text;

            diasEnMes = cuantosDias(Month, Year);
            diasAhora = comboDias.length-1;

            if (diasAhora > diasEnMes){
                for (i=0; i<(diasAhora-diasEnMes); i++){
				
                    comboDias.options[comboDias.options.length - 1] = null
                }
            }
            if (diasEnMes > diasAhora){
                for (i=0; i<(diasEnMes-diasAhora); i++){
                    sumaOpcion = new Option(comboDias.options.length);
                    comboDias.options[comboDias.options.length]=sumaOpcion;
                }
            }
            if (comboDias.selectedIndex < 0)
              comboDias.selectedIndex = 0;
         }
		 function asignaDias2(){
		
            comboDias = document.getElementById("diaL");
            comboMeses = document.getElementById("mesL");
            comboAnyos = document.getElementById("annoL");

            Month = comboMeses[comboMeses.selectedIndex].text;
            Year = comboAnyos[comboAnyos.selectedIndex].text;

            diasEnMes = cuantosDias(Month, Year);
            diasAhora = comboDias.length;

            if (diasAhora > diasEnMes){
                for (i=0; i<(diasAhora-diasEnMes); i++){
				
                    comboDias.options[comboDias.options.length - 1] = null
                }
            }
            if (diasEnMes > diasAhora){
                for (i=0; i<(diasEnMes-diasAhora); i++){
                    sumaOpcion = new Option(comboDias.options.length+1);
                    comboDias.options[comboDias.options.length]=sumaOpcion;
                }
            }
            if (comboDias.selectedIndex < 0)
              comboDias.selectedIndex = 0;
         }
	//Funcion que mueve el dia de la distribuci�n de habitaciones poniendo la fecha seleccionada en el calendario	
	//Funcion que mueve el dia de la distribuci�n de habitaciones poniendo la fecha seleccionada en el calendario	
	function cambiar_dia(dia,mes,anio){
		if(eval(dia) < 10)			//si el dia es menor que 10 le a�ado el 0 del inicio
			dia = "0"+eval(dia);
		if(eval(mes) < 10)			//si el mes es menor que 10 le a�ado el 0 del inicio, para que quede en formato dd/mm/aaaa
			mes = "0"+eval(mes);
		fecha = dia + "/" + mes + "/" + anio;
		
		dia_sel = new Date(anio,mes,dia);
		dia_lle = new Date(document.getElementById("annoL").value,document.getElementById("mesL").value,document.getElementById("diaL").value);
		dia_tem = eval(document.getElementById("diaL").value) + eval(document.getElementById("nnoches").value) - 1;
		dia_sal = new Date(document.getElementById("annoL").value,document.getElementById("mesL").value,dia_tem);
		if(dia_sel > dia_sal || dia_sel < dia_lle){
			alert("La fecha seleccionada no est� entre la fecha de llegada y de salida");
		}
		else if(document.getElementById("f_camas").value != "0"){ //si faltan camas por asignar se indica al usuario
			alert("Faltan asignar " + document.getElementById("f_camas").value + " cama/s.");
			document.getElementById('capa_calendario').style.visibility='hidden';
		}
		else if(!comprobar_tablas_personas()){}
		else if(!comprobar_hab()){}
		else{
			mantener_cuadro_edades();
			camposTexto = document.getElementById("ingresar_estancia").elements; // recorro los hidden que hay en cada celda seleccionable del cuadro de habitaciones
			var hay = 0; //esta variable indica si se ha hecho alg�n cambio en el text de habitaciones seleccionadas
			//--con esto se quitan las habitaciones deseleccionadas de la ultima fecha seleccionada, cuando el usuario se mueve por las paginas de 'distribuci�n de habitaciones'			

			var cad = "";
			seleccionadas = Array();
			seleccionadas_dias = Array();
			fechas = document.getElementById("fec_selec").value.split("+");//fechas en un array que contiene todas las fechas por las que el usuario ha pasado en 'distribuci�n de habitaciones'
			if(document.getElementById("hab_selec").value != ""){//si el text de habitaciones seleccionadas no est� vacio
				seleccionadas_dias = document.getElementById("hab_selec").value.split("*");	//seleccionadas_dias es un array que contiene las camas seleccionadas separadas por d�as, la posici�n 0 de este array corresponde con las camas seleccionadas para la fecha 0 del array fechas y as� con las dem�s posicioens
				if(document.getElementById("fec_selec").value == "")
								pos = 0;
							else
								pos = fechas.length-1;
							if(seleccionadas_dias[pos] == "")
								pos --;
							var exi = (seleccionadas_dias[pos]) ? true:false;
							if(exi){
								seleccionadas = seleccionadas_dias[pos].split("-");
								for(e = 0; e < seleccionadas.length-1; e++){ //recorro todas las habitaciones seleccionadas
									nom1 = seleccionadas[e].split("&");
									nom = nom1[0] + "-" + nom1[1];
									var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
									if(exi){	//si existe y sigue estando seleccionada, se mantiene en la cadena
										if(document.getElementById(nom).value == "1")
											cad = cad + seleccionadas[e] + "-";
									}
									else
										cad = cad + seleccionadas[e] + "-"; //si no existe es un habitacion de otra pagina, por lo que tambien se mantiene
								}
							}
						}
			document.getElementById("hab_selec").value  = "";	///recompongo el valor del text de las habitaciones seleeccionadas, para ello primero lo vacio
			for(e = 0; e < seleccionadas_dias.length-1; e++){	//las habitaciones seleccionadas de todos los dias lo dejo igual, menos la �ltima
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
			}
			document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
			seleccionadas = Array();
			seleccionadas = cad.split("-"); //recojo las camas seleccionadas para esta fecha
			cad1 = "";
			//pongo las camas que se han seleccionado en la fecha de 'distribuci�n de habitaciones' en el text de habitaciones seleccionadas
			for(i = 0; i < camposTexto.length; i++){		//recorro todos los elementos del formulario
				trozo = camposTexto[i].name.split("-"); 	//por lo que recojo el n�mero de habitacion y de la cama	
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
						cad1 = cad1 + cama + "-"; // lo a�ado al valor de hidden que recoge el n�mero de las habitaciones seleccionadas
					hay = 1; //indico que se ha hecho un cambio en el text de habitaciones seleccionadas
				}
			}
			if(hay == 1){//si hay alguna habitaci�n seleccionada se pone la fecha en el text de fechas
					document.getElementById("fec_selec").value = document.getElementById("fec_selec").value + document.getElementById("fecha_ver").value + "+";
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad1 + "*"; //cierro las habitaciones seleccionadas para el dia, indicandolo con un asterisco
					fechas1 = document.getElementById("fec_selec").value.split("+"); //fechas1 es un array que contiene todas las fechas que el usuario ha visto en 'distribuci�n de habitaciones'
					enc = 0;
					for(e = 0; e < fechas1.length; e++){	//se busca la fecha que se va ha ver en el text de fechas seleccionadas
						if(fechas1[e] == fecha)
							enc = 1;
					}
					if(enc == 0){	//si no se encuentra la fecha que se va ha ver, se le pone las camas igual que el dia inferior, si existe no se hace nada
						seleccionadas_dias1 = document.getElementById("hab_selec").value.split("*");
						pos = seleccionadas_dias1.length - 2;	//tomo la posicion del ultimo elemento
						tem_dia = fecha.substr(0,2);			//recojo el dia, mes y a�o de la fecha
						tem_mes = eval(fecha.substr(3,2))+1		//se pone un mes m�s, ya que enero es el mes cero, no el uno
						tem_ayo = fecha.substr(6,4);
						fecha_act = new Date(tem_ayo,tem_mes,tem_dia);	//creo la fecha de del la ultima posici�n
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
			if(eval(cad_dia) < 10)			//si el dia es menor que 10 le a�ado el 0 del inicio
				cad_dia = "0"+eval(cad_dia);
			if(eval(cad_mes) < 10)			//si el mes es menor que 10 le a�ado el 0 del inicio, para que quede en formato dd/mm/aaaa
				cad_mes = "0"+eval(cad_mes);	
			fecha_cad = cad_dia + "/" + cad_mes + "/" + cad_ayo;		//creo la fecha que se va ha mostrar en 'distribuci�n de habitaciones'
			fecha_cad1 = cad_dia + "-" + cad_mes + "-" + cad_ayo;
			document.getElementById("fecha_ver").value = fecha_cad;		//y lo pongo en la fecha que se va ha ver
			document.getElementById("fecha_cal").value = fecha_cad1;	//y la que se envia al calendario
			document.getElementById("mov_cal").value='1'; 				// se indica que se esta moviendo el calendario
			document.getElementById("ingresar_estancia").submit();
		}
	}
	
	//mueve la pagina de la distribucion de habitaciones
	function cambiar_pagina_dis(num_pag){
		camposTexto = document.getElementById("ingresar_estancia").elements; // recorro los hidden que hay en cada celda seleccionable del cuadro de habitaciones
		var hay = 0;

		var cad = "";
		seleccionadas = Array();
		seleccionadas_dias = Array();
		fechas = document.getElementById("fec_selec").value.split("+");
		if(document.getElementById("hab_selec").value != ""){
			seleccionadas_dias = document.getElementById("hab_selec").value.split("*");	//con esto se quitan las habitaciones deseleccionadas, cuando el usuario se mueve por las paginas de las habitaciones
			if(document.getElementById("fec_selec").value == "")
				pos = 0;
			else
				pos = fechas.length-1;
			if(seleccionadas_dias[pos] == "")
				pos --;
			pos = seleccionadas_dias.length-1;
			var exi = (seleccionadas_dias[pos]) ? true:false;
			if(exi){
				seleccionadas = seleccionadas_dias[pos].split("-");
				for(e = 0; e < seleccionadas.length-1; e++){ //recorro todas las habitaciones seleccionadas
					nom1 = seleccionadas[e].split("&");
					nom = nom1[0] + "-" + nom1[1];
					var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
					if(exi){	//si existe y sigue estando seleccionada, se mantiene en la cadena
						if(document.getElementById(nom).value == "1")
							cad = cad + seleccionadas[e] + "-";
					}
					else
						cad = cad + seleccionadas[e] + "-"; //si no existe es un habitacion de otra pagina, por lo que tambien se mantiene
				}
			}
		}
		document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas
		for(e = 0; e < seleccionadas_dias.length-1; e++){	//todas menos la ultima la dejo igual
			document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
		}
		document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
		seleccionadas = Array();
		seleccionadas = cad.split("-");

		for(i = 0; i < camposTexto.length; i++){	//pongo las habitaciones que se han seleccionado en el text
			trozo = camposTexto[i].name.split("-"); // por lo que recogo el n�mero de habitacion
			nombre = "td" + trozo[0] + "_" + trozo[1];
			var exi = (document.getElementById(nombre)) ? true:false; //dice si existe o no un elemento
			if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// si su valor esta a uno, es que se ha seleccionado esa cama
				cama = trozo[0] + "&" + trozo[1];
				enc = 0;
				for(e = 0; e < seleccionadas.length; e++){
					if(seleccionadas[e] == cama)
						enc = 1;
				}
				if(enc == 0)
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cama + "-"; // y lo a�ado al valor de hidden que recoge el n�mero de las habitaciones seleccionadas
			}
		}
		document.getElementById("dist_num_pag").value=num_pag; // se indica que se esta la pagina
		document.getElementById("mov_cal").value='1'; // se indica que se esta moviendo el calendario
		document.getElementById("mover_pag").value='1'; // y la pagina

document.getElementById("abrir_caja").value = 3;	
			document.getElementById('comprobar_grupo').value='si';


		document.getElementById("ingresar_estancia").submit();
	}
	
	//Funcion que mueve el dia de la distribucion de habitaciones
	function mover_fecha(dia,llegada,salida,valor){
		if(document.getElementById("f_camas").value != "0"){ //si faltan camas por asignar se indica al usuario
			alert("Faltan asignar " + document.getElementById("f_camas").value + " cama/s.");
		}
		else{
			meses = Array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
			tem_dia = llegada.substr(0,2);		//creo las fechas necesarios para compararlas
			tem_mes = llegada.substr(3,2);
			tem_ayo = llegada.substr(6,4);

			dia_lle = new Date(tem_ayo,tem_mes,tem_dia);
			tem_dia = salida.substr(0,2);
			tem_mes = salida.substr(3,2);
			tem_ayo = salida.substr(6,4);
			dia_sal = new Date(tem_ayo,tem_mes,tem_dia);
			if(valor == "1")	//si el valor es 1 se esta aumentando la fecha
				tem_dia = eval(dia.substr(0,2))+1;
			else
				tem_dia = eval(dia.substr(0,2))-1;
			tem_mes = dia.substr(3,2);
			tem_ayo = dia.substr(6,4);
			if(tem_dia > cuantosDias(meses[tem_mes])){
				tem_dia = 1;
				tem_mes = eval(tem_mes)+1;
				if(eval(tem_mes) > 12){
					tem_mes = 1;
					tem_ayo = eval(tem_ayo)+1;
				}
			}
			else if(eval(tem_dia) < 1){
				tem_mes = eval(tem_mes)-1;
				tem_dia = cuantosDias(meses[tem_mes]);
				if(tem_mes < 1){
					tem_mes = 12;
					tem_ayo = eval(tem_ayo)-1;
				}
			}
			if(tem_dia < 10)
				tem_dia = "0"+tem_dia;
			if(tem_mes < 10)
				tem_mes = "0"+tem_mes;
			dia_sel = new Date(tem_ayo,tem_mes,tem_dia);
			if(dia_sel <= dia_sal && dia_sel >= dia_lle){ //si la fecha esta entre la llegada y la salida
				
				camposTexto = document.getElementById("ingresar_estancia").elements; // recorro los hidden que hay en cada celda s??j??????????eleccionable del cuadro de habitaciones
				var hay = 0;
				var cad = "";
				seleccionadas = Array();
				seleccionadas_dias = Array();
				fechas = document.getElementById("fec_selec").value.split("+");
				if(document.getElementById("hab_selec").value != ""){
					seleccionadas_dias = document.getElementById("hab_selec").value.split("*");	//con esto se quitan las habitaciones deseleccionadas, cuando el usuario se mueve por las paginas de las habitaciones
					pos=fechas.length-1;			
					seleccionadas = seleccionadas_dias[pos].split("-");
					for(e = 0; e < seleccionadas.length-1; e++){ //recorro todas las habitaciones seleccionadas
						nom1 = seleccionadas[e].split("&");
						nom = nom1[0] + "-" + nom1[1];
						var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
						if(exi){	//si existe y sigue estando seleccionada, se mantiene en la cadena
							if(document.getElementById(nom).value == "1")
								cad = cad + seleccionadas[e] + "-";
						}
						else
							cad = cad + seleccionadas[e] + "-"; //si no existe es un habitacion de otra pagina, por lo que tambien se mantiene
					}
				}
				document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas
				for(e = 0; e < seleccionadas_dias.length-1; e++){	//todas menos la ultima la dejo igual
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
				}
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
				seleccionadas = Array();
				seleccionadas = cad.split("-");
				cad1 = "";
				for(i = 0; i < camposTexto.length; i++){
					trozo = camposTexto[i].name.split("-"); // por lo que recogo el n�mero de habitacion
					nombre = "td" + trozo[0] + "_" + trozo[1];
					var exi = (document.getElementById(nombre)) ? true:false; //dice si existe o no un elemento
					if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// si su valor esta a uno, es que se ha seleccionado esa cama
						cama = trozo[0] + "&" + trozo[1];
						enc = 0;
						for(e = 0; e < seleccionadas.length; e++){
							if(seleccionadas[e] == cama)
								enc = 1;
						}
						if(enc == 0)
							cad1 = cad1 + cama + "-"; // y lo a�ado al valor de hidden que recoge el n�mero de las habitaciones seleccionadas
						hay = 1;
					}
				}
				fecha = tem_dia + "/" + tem_mes + "/" + tem_ayo;
				if(hay == 1){//si hay alguna habitaci�n seleccionada se pone la fecha en el text de fechas
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad1 + "*";
					fechas1 = document.getElementById("fec_selec").value.split("+");
					enc = 0;
					for(e = 0; e < fechas1.length; e++){
						if(fechas1[e] == fecha)
							enc = 1;
					}
					if(enc == 0){
						seleccionadas_dias1 = document.getElementById("hab_selec").value.split("*");
						pos = seleccionadas_dias1.length - 2;
						tem_dia = fecha.substr(0,2);
						tem_mes = eval(fecha.substr(3,2))+1;
						tem_ayo = fecha.substr(6,4);
						fecha_act = new Date(tem_ayo,tem_mes,tem_dia);
						fecha_tem = new Date(1900,1,1);
						for(e = 0; e < fechas1.length; e++){
							tem_dia = fechas1[e].substr(0,2);
							tem_mes = eval(fechas1[e].substr(3,2))+1;
							tem_ayo = fechas1[e].substr(6,4);
							fecha_ver = new Date(tem_ayo,tem_mes,tem_dia);
							if(fecha_ver < fecha_act && fecha_ver > fecha_tem){
								pos = e;
							}
						}
						document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias1[pos];
					}
					document.getElementById("fec_selec").value = document.getElementById("fec_selec").value + dia + "+";
				}
				cad_dia = dia_sel.getDate();
				cad_mes = dia_sel.getMonth();
				cad_ayo = dia_sel.getYear();				
				if(eval(cad_dia) < 10)
					cad_dia = "0"+eval(cad_dia);
				if(eval(cad_mes) < 10)
					cad_mes = "0"+eval(cad_mes);
				fecha_cad = cad_dia + "/" + cad_mes + "/" + cad_ayo;
				fecha_cad1 = cad_dia + "-" + cad_mes + "-" + cad_ayo;
				document.getElementById("fecha_ver").value = fecha_cad;
				document.getElementById("fecha_cal").value = fecha_cad1;
				document.getElementById("mov_cal").value='1'; // se indica que se esta moviendo el calendario
				document.getElementById("ingresar_estancia").submit();

			}
		}
	}

		
		// Esta funci�n comprueba que sea correcto el formato del email introducido
        function c_mail(texto){ 
			var mailres = true;             
			var cadena = "abcdefghijklmn�opqrstuvwxyzABCDEFGHIJKLMN�OPQRSTUVWXYZ1234567890@._-"; 
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

		
		// Esta funci�n asigna y desasigna camas, recibe la habitacion y la cama seleccionada, si se esta modificando o no,
		// el numero de resultado de la busqueda y si es una cama libre en todos los d�as o no
		function asignar_cama(hab, cam, td, mod, nume, com, ini, fin, tipo, cambio){
			if(mod != "si" && nume != "0"){ // si se est� modificando o no se ha encontrado la solicitud, no se deja asignar camas
				var n = hab+"-"+cam; // n recoge el nombre del hidden que se va ha usar
				if(document.getElementById(n).value != "0"){ // si el valor del hidden es distinto a cero es que se esta desasignando
					var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false; 
					if(exi)
						document.getElementById(td.id).className = "cama_temp";	//y se pone la clase 'cama_temp'
					else
						document.getElementById(td.id).className = "cama_libre_con";	//y se pone la clase 'cama_libre_con'
					document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) + 1; // se aumentan en 1 las camas individuales	que faltan por asignar
					if(com != '0')//si la cama esta ocupada para mas adelante de la fecha de llegada
						document.getElementById("es_inc").value = eval(document.getElementById("es_inc").value) - 1; //se quita una de las camas individuales incompletas
					document.getElementById(n).value = 0;
					var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false;
					if(exi){
						fecha_tem = document.getElementById("inc_fecha"+hab+"-"+cam).value; 
						quitar_dia(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4));
						if(cambio)		
							quitar_cambio_hab(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4),hab);
					}
					document.getElementById("camas_asignadas").value = eval(document.getElementById("camas_asignadas").value ) - 1;
				}else{ // si el valor del hidden es cero se esta asignando una cama
					if(document.getElementById("f_camas").value != "0"){ // y aun quedan camas compartidas por asignar
						hacer=0;
						if(com != '0'){ //si la cama esta ocupada para mas adelante de la fecha de llegada
							ini = ini.substr(8,2)+"/"+ini.substr(5,2)+"/"+ini.substr(0,4);
							fin = fin.substr(8,2)+"/"+fin.substr(5,2)+"/"+fin.substr(0,4);
							if(cambio)
								mens="La habitaci�n cambiar� de tipo a partir del "+ini+", �desea continuar?";
							else
								mens="La cama estar� ocupada a partir del " + ini + ", �desea continuar?";
							if(confirm(mens)){
								poner_dia(ini);
								if(cambio)	
									poner_cambio_hab(ini, hab, cambio, tipo); 
								document.getElementById("es_inc").value = eval(document.getElementById("es_inc").value ) + 1;//se aumenta el contador de camas compartidas incompletas se han asignado
							}else{
								hacer = 1;
							}
						}
						
						if(hacer == 0){
							document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) - 1; // se disminuye en 1 las camas compartidas que faltan por aginar
							document.getElementById(n).value = 1; // se cambia el valor de hidden a 1
							document.getElementById("camas_asignadas").value = eval(document.getElementById("camas_asignadas").value ) + 1;
							document.getElementById(td.id).className = "cama_asignada"; // y se cambia el color de la celda seleccionada
						}
					}
					if(document.getElementById("ir_a_dia").value != "" && document.getElementById("f_camas").value == "0" )
						llamar_dia();
				}
			}
		}		
				//Esta funci�n asigna todas las camas de la habitaci�n a la reserva
		function asignar_hab(hab, mod, nume, camas, tipo, cambio, tipo_hab){	
			if(mod != "si" && nume != "0"){ // si se est� modificando o no se ha encontrado la solicitud, no se deja asignar camas
				maxi = eval(document.getElementById("f_camas").value);
				asig = 0;
				con = 1;
				for(i = 1; i <= camas; i++){	//recorro las celdas de la habitaci�n cogiendo solo las camas libres todos los d�as de la reserva
					nom = "td"+hab+"_"+i;
					if(con <= maxi){
						var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
						if(exi && document.getElementById(hab+"-"+i).value=='0'){//si existe la celda correspondiente a la cama;
							asig = 1;
							con ++;
						}
					}
				}
				if(asig == 0){	
					for(i = 1; i <= camas; i++){	//recorro las celdas de la habitaci�n cogiendo solo las camas libres todos los d�as de la reserva
						nom = "td"+hab+"_"+i;
						var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
						if(exi && document.getElementById(hab+"-"+i).value=='1'){//si existe la celda correspondiente a la cama;
							document.getElementById(hab+"-"+i).value='0';	//y se pone su hidden a 1 para indicar que se ha seleccionado
							document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) + 1;
							document.getElementById("camas_asignadas").value = eval(document.getElementById("camas_asignadas").value ) - 1;
							var exi = (document.getElementById("inc"+hab+"-"+i)) ? true:false; 
							if(exi){
								document.getElementById(nom).className = "cama_temp";	//y se pone la clase 'cama_temp'
								fecha_tem = document.getElementById("inc_fecha"+hab+"-"+i).value; 
								quitar_dia(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4));
							    if(cambio)	
									quitar_cambio_hab(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4),hab);
							}
							else
								document.getElementById(nom).className = "cama_libre_con";	//y se pone la clase 'cama_libre_con'
							
						}
					}
				}else{	
					con = 1;
					for(i = 1; i <= camas; i++){	//recorro las celdas de la habitaci�n cogiendo solo las camas libres todos los d�as de la reserva
						nom = "td"+hab+"_"+i;
						if(con <= maxi){
							var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
							if(exi && document.getElementById(hab+"-"+i).value!='1'){//si existe la celda correspondiente a la cama;
								var exi = (document.getElementById("inc"+hab+"-"+i)) ? true:false; //dice si existe o no un elemento
								if(!exi){
									document.getElementById(nom).className = "cama_asignada"; //se le pone que color verde
									document.getElementById(hab+"-"+i).value='1';	//y se pone su hidden a 1 para indicar que se ha seleccionado
									document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) - 1;
									document.getElementById("camas_asignadas").value = eval(document.getElementById("camas_asignadas").value ) + 1;
									con++;
								}
							}
						}
					}
					if(eval(document.getElementById("f_camas").value) != 0){ //si faltan camas por asinar
						contador=0;
						mens = 0;
						for(i4 = 1; i4 <= camas; i4++){	// se recorre otra vez todas las celdas de la habitaci�n mirando las que no estan disponibles todos los d�as
							nom = "td"+hab+"_"+i4;
							if(con <= maxi){
								var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
								if(exi && document.getElementById(hab+"-"+i4).value!='1'){//si existe la celda correspondiente a la cama;
									var exi = (document.getElementById("inc"+hab+"-"+i4)) ? true:false; //dice si existe o no un elemento
									if(exi){
										document.getElementById("es_inc").value = eval(document.getElementById("es_inc").value) + 1;
									}
									document.getElementById(nom).className = "cama_asignada"; //se le pone que color verde
									document.getElementById(hab+"-"+i4).value='1';	//y se pone su hidden a 1 para indicar que se ha seleccionado
									document.getElementById("f_camas").value = eval(document.getElementById("f_camas").value) - 1;
									document.getElementById("camas_asignadas").value = eval(document.getElementById("camas_asignadas").value ) + 1;
									con++;
									contador++;
									fecha_tem = document.getElementById("inc_fecha"+hab+"-"+i4).value; 
									poner_dia(fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4));
									mens = 1;
								}
							}
						}
						if(mens == 1){
							alert(contador+" camas no estan disponibles todos los d�as"); //y se le indica al usuario
							
						}
					}
					if(document.getElementById("ir_a_dia").value != "" && document.getElementById("f_camas").value == "0" )
						llamar_dia();
				}		
			}		
		}	
		
		//Esta funci�n pone una fecha en el text que guarda las fechas de las camas incompletas,  el par�metro fecha indica la fecha a poner (dd/mm/aaaa)
	function poner_dia(fecha){
		dias_inc = document.getElementById("ir_a_dia").value.split("-");	//pongo todas las fecha que hay hasta el momento en un array
		exi = 0;			//esta variable dice si la fecha que se va ha poner ya esta puesta o no
		for(i = 0; i < dias_inc.length; i++){	//recorro todas las fechas
			if(fecha == dias_inc[i]){			//y si ya existe lo indico
				exi = 1;
			}
		}
		if(exi == 0)	//si la fecha ya esta en el text no se hace nada, y si aun no esta se pone, el gui�n sirve para separar una fecha de otra
			document.getElementById("ir_a_dia").value = document.getElementById("ir_a_dia").value + fecha + "-";
	}
	
	//Esta funci�n quita una fecha en el text que guarda las fechas de las camas incompletas, siempre que no se necesite, el par�metro fecha indica la fecha a quitar (dd/mm/aaaa)
	function quitar_dia(fecha){
		camposTexto = document.getElementById("ingresar_estancia").elements; //recojo los elementos que hay en el formulario
		enc = 0;	//esta variable dice si hay alguna cama seleccionada con la misma fecha que la que se busca
		for(i1 = 0; i1 < camposTexto.length; i1++){	//recorro todos los elementos del formulario
			trozo = camposTexto[i1].name.split("-"); //por lo que recojo el n�mero de habitacion y de la cama
			nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca
			var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda,
			var exi1 = (document.getElementById("inc"+trozo[0]+"-"+trozo[1])) ? true:false; //es una cama incompleta,
			if(exi && exi1 && camposTexto[i1].type=='hidden' && camposTexto[i1].value=='1'){	//y est� seleccionada
				fecha_tem = document.getElementById("inc_fecha"+trozo[0]+"-"+trozo[1]).value;	//compongo la fecha de la cama, ya que esta en formato aaaa-mm-dd
				fecha_tem1 = fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4);
				if(fecha_tem1 == fecha)	//compruebo si la fecha es la misma que la que se est� buscando y si es asi se indica
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
	
	//Esta funci�n busca el menor de los d�as en el text que guarda las fechas de las camas incompletas, y llama a cambiar dia pasandole la fecha con la que se tiene que actualizar la p�gina
	function llamar_dia(){
	if(confirm("Quedan d�as de la estancia por asignar. �Desea continuar?")){
		dias_inc = document.getElementById("ir_a_dia").value.split("-");//pongo todas las fecha que hay hasta el momento en un array
		menor = new Date(9999,11,31);	//esta variable guarda la fecha mas peque�a del array
		for(i = 0; i < dias_inc.length-1; i++){	//recorro todas la fechas
			fecha_act = new Date(dias_inc[i].substring(6,10),(dias_inc[i].substring(3,5)-1),dias_inc[i].substring(0,2));//creo la fecha actual para compararla con la fecha menor
			if(fecha_act < menor){	//si la fecha actual es menor que la mas peque�a encontrada hasta el momento
				menor = fecha_act;	//encontes la acutal ser� la menor
			}
		}
		cad_fecha = menor.getDate() + "/" + (menor.getMonth()+1) + "/" + menor.getYear(); //la fecha menor hay que quitarla del text que guarda las fechas de las camas incompletas
		cad = "";

		for(i = 0; i < dias_inc.length-1; i++){//recorro todas las fechas
			if(dias_inc[i] != cad_fecha){	//si es distinta a la que se quiere quitar se deja en la cadena
				cad = cad + dias_inc[i] + "-";
			}
		}
		document.getElementById("ir_a_dia").value = cad;	//y se cambia el valor del text
		cambiar_dia(menor.getDate(),(menor.getMonth()+1),menor.getYear()); //y se llama a cambiar d�a
	}
	}
	
	</script>


<script language="javascript">
//funcion para el listado
function desresaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#F4FCFF';
	  	tr.style.color = '#3F7BCC';
	}
	//funcion para el listado
	function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '#3F7BCC';
	  	tr.style.color = '#F4FCFF';
	}
//que cuando la capa de componentes este activada el campo listado_componentes valga siempre si

var cadena = "";
	function documentacion(n,t,d){
	    var Num_Paises;
		var nacionalidad=n;
		var tipo_doc=t;
		var dni=d;
	
		//var selected_php = '<?php echo $_POST['nacionalidad']?>';
		if (document.getElementById(nacionalidad).length > 0){
		   var selected_js = document.getElementById(nacionalidad).options[document.getElementById(nacionalidad).selectedIndex].value;
		}else{
			 var selected_js = 0;
		}
	
	    <?			 
			  $res = mysql_query("SELECT * FROM `pais` ORDER BY Nombre_Pais");
			  // Array con los paises con es v�lido el tipo de documento <<I>>.
			  $num =mysql_num_rows($res);	 
			  echo"Num_Paises=".$num.";";
		?>
		if ( (document.getElementById(tipo_doc).value=="P") )
		{		   
		    
			document.getElementById(nacionalidad).options.length = 0;
		     //alert("tam "+document.forms[formu].nacionalidad.options.length);
			<?			 
			  $res = mysql_query("SELECT * FROM pais ORDER BY Nombre_Pais");
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
				?>
					
				if(dni=="dniR"){
				
				<?
				echo"document.ingresar_estancia.nacionalidad.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
				if ($fila['Id_Pais']=='ES')  echo "document.ingresar_estancia.nacionalidad.selectedIndex =".$i." ;";
				//echo "document.forms[formu].nacionalidad.options[".$i."] = null;";
					?> } else if(dni=="dni_componente"){ <?	 
						
				echo"document.ingresar_estancia.nacionalidad_componente.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
				if ($fila['Id_Pais']=='ES')  echo "document.ingresar_estancia.nacionalidad_componente.selectedIndex =".$i." ;";						?> } <?		 
			  }
			  ?>
			 					
		}
		
	    // sI el tipo de documento es Carnet de conducir o dni, bloqueamos el select de pais dejando predeterminado Espa�a.
	    if  ( (document.getElementById(tipo_doc).value=="C") || (document.getElementById(tipo_doc).value == "D") ) 
	    	{	    	
	    	  document.getElementById(dni).value = document.getElementById(dni).value.substr(0,9);
			  document.getElementById(dni).setAttribute('maxlength', 9);	    	
	    	 	   
	    	  document.getElementById(nacionalidad).options.length = 0;//alert("tam "+document.forms[formu].nacionalidad.options.length);
			 <?php		 
			  $res = mysql_query("SELECT * FROM pais WHERE Id_Pais='ES' ORDER BY Nombre_Pais");
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
				}?>
				
				if(dni=="dniR"){
				
				<?
					echo"document.ingresar_estancia.nacionalidad.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
						?> } else if(dni=="dni_componente"){ <?	 
						
					echo"document.ingresar_estancia.nacionalidad_componente.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
						?> } <?	 
			  }
			 			 
			 ?>	
			 			  
			}
		else 
		{
		    
		     document.getElementById(nacionalidad).setAttribute('maxlength', 30);			
		}	
		if (document.getElementById(tipo_doc).value=="I")
		{
		 	
		    document.getElementById(nacionalidad).options.length = 0;		     
			<?			 
			  $res = mysql_query("SELECT * FROM pais WHERE Carta_Europea ='S' AND Id_Pais<>'ES'  ORDER BY Nombre_Pais");
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
				?>
				if(dni=="dniR"){
				<?
					echo"document.ingresar_estancia.nacionalidad.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";
					?> }else if(dni=="dni_componente"){ <?	 
						
					echo"document.ingresar_estancia.nacionalidad_componente.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
						?> } <?	 								 
			  }
			
			?>	
			 
						
		}
		
		else if  ( (document.getElementById(tipo_doc).value=="N") || (document.getElementById(tipo_doc).value=="X")     )
		{	
		   
		    //en este caso el pais seleccionado no puede ser espa�a
		   document.getElementById(nacionalidad).options.length = 0;
		     //alert("tam "+document.forms[formu].nacionalidad.options.length);
			<?			 
			  $res = mysql_query("SELECT * FROM pais WHERE Id_Pais<>'ES' ORDER BY Nombre_Pais ");
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
				?>
				if(dni=="dniR"){
				<?
					echo "document.ingresar_estancia.nacionalidad.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";	
							 ?> } else if(dni=="dni_componente"){ <?	 
						
					echo"document.ingresar_estancia.nacionalidad_componente.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
						?> } <?	 
			  }
			  			  
			  
			?>  
			
			
		} 	
		if(dni=="dniR"){
		poner_pais_representante(selected_js);
		}else if(dni=="dni_componente"){
		poner_pais_componente(selected_js);
		}

	}
	//Funcion que selecciona el pais que estaba seleccionado
	function poner_pais_representante( valor){
		if (<?php if (isset($_POST['nacionalidad']) && $_POST['nacionalidad'] != ""){echo "true";}else{echo "false";}?>){
			var valor_final = '<?php echo $_POST['nacionalidad'];?>';
		}else{
			var valor_final = valor;
		}
		for (var i=0;i<document.ingresar_estancia.nacionalidad.length;i++){
			if (document.ingresar_estancia.nacionalidad.options[i].value == valor_final){
			   document.ingresar_estancia.nacionalidad.options[i].selected = true;
			}
		}
	}
	function poner_pais_componente( valor){
		if (<?php if (isset($_POST['nacionalidad_componente']) && $_POST['nacionalidad_componente'] != ""){echo "true";}else{echo "false";}?>){
			var valor_final = '<?php echo $_POST['nacionalidad_componente'];?>';
		}else{
			var valor_final = valor;
		}
		for (var i=0;i<document.ingresar_estancia.nacionalidad_componente.length;i++){
			if (document.ingresar_estancia.nacionalidad_componente.options[i].value == valor_final){
			   document.ingresar_estancia.nacionalidad_componente.options[i].selected = true;
			}
		}
	}

	
	// abre el popup de buscar grupos
	function ver_pop_2() {
		ventana = window.open("paginas/pop_grupos_reserva.php","BUSCADOR"," width=650px,height=650px,,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=150px,left=430px");
	}
	// abre el popup de buscar componentes
	function ver_pop_componentes(nombre,fecha){
document.ingresar_estancia.tipo_doc_componente.value="P";
	documentacion('nacionalidad_componente','tipo_doc_componente','dni_componente');
	
	ventana = window.open("paginas/pop_grupos_componentes_reserva.php?grupo_mo="+nombre+"&fecha_mo="+fecha,"_blank","width=650px,height=650px,,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=150px,left=430px");
	

	}
	//funcion para comprobar los datos del grupo
	function comprobar_datos_grupo(){
	estado = true;
	    if (document.getElementById('nombreG').value == "") 
				{alert("Debes rellenar todos los campos");
				estado = false;}
			else {
				
				if(document.getElementById('email').value != ""){
				if(document.getElementById('email').value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
					alert("La cuenta de correo no es v�lida");
					estado = false;}}
				if(document.ingresar_estancia.pais[document.ingresar_estancia.pais.selectedIndex].value=="ES"){
					if(document.ingresar_estancia.prov[document.ingresar_estancia.prov.selectedIndex].value==""){
						alert("Tiene que seleccionar una provincia");
						estado = false;
					}
				}
					
				} return estado;
	}
	function comprobar_existencia_grupo(){
	
		//if(document.getElementById('comprobar_grupo').value!="si"){
			document.getElementById('comprobar_grupo').value="si";
			document.getElementById("ingresar_estancia").submit();
		//}
	}
	// Comprueba si existe el elemento en el vector.
		function ExisteElemento(vector, elemento)//
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
		function calcletra(dni)//
		{
			var JuegoCaracteres="TRWAGMYFPDXBNJZSQVHLCKET";
			var Posicion= dni % 23;
			var Letra = JuegoCaracteres.charAt(Posicion);
			return Letra;
		}
	function validadni()
		{
			var dni = document.ingresar_estancia.dniR.value;
		    var tipo_documento = document.ingresar_estancia.tipo_doc.value;
		    var indice_naci = document.ingresar_estancia.nacionalidad.selectedIndex;
							var pais = document.ingresar_estancia.nacionalidad[indice_naci].value;
		   	// Obtenemos los paises que tenga Carta Europea de la base de datos.
								 
			<?			 
			  $res = mysql_query("SELECT * FROM `pais` WHERE Carta_Europea='S'");
			  // Array con los paises con es v�lido el tipo de documento <<I>>.
			  echo "var PaisesPermitidos = new Array(".mysql_num_rows($res).");";
			  for ($i=0;$i<mysql_num_rows($res);$i++)
			  {
			    $fila = mysql_fetch_array($res);	
				echo "\nPaisesPermitidos[".$i."] =\"".$fila['Id_Pais']."\";"; 		
				    
			  }
			?>	
		    
			//Comprueba que tenga una longitud entre 7 y 8 n�meros, y en caso de tener un car�cter(NIF) ser� una letra permitida. 
			//En caso de no cumplirse estas condiciones, se enviar� un mensaje y se colocar� el foco en el campo dni.
			if (dni=="") alert("Debe rellenar el campo DNI.");
			// En caso de tratarse del dni o del carnet de conducir...
			else if (  (tipo_documento == "D") || (tipo_documento == "C") )
			{
			   
				// Es solo v�lido para espa�oles.
				if (pais!='ES') 
				{
						alert("El pais no corresponde con el tipo de documento");					
						document.ingresar_estancia.nacionalidad.focus();
						return false;
				}
				else 
				{	
				    // Tiene que tener una longitud de 7 n�meros como m�nimo.
				    if (dni.length < 7)
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.ingresar_estancia.dniR.focus();
						return false;
					}
				    // Comprobamos si los siete primeros son n�meros.
					else if(isNaN(dni.substring(0,7)) )
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.ingresar_estancia.dniR.focus();
						return false;
					}
				
					else
					{
					    // Si tiene solo siete n�meros, le a�adimos un cero y la letra.
						if(dni.length == 7)
						{						 	
							document.ingresar_estancia.dniR.value = "0"+dni+calcletra(dni);
						} 			 
						
						// Si es igual a ocho...
						else if (dni.length == 8)
						{
						    // El pen�ltimo tiene que ser un n�mero y el �ltimo  car�cter solo puede ser un n�mero o una letra permitida.
							if ( ( (isNaN(dni.substring(7,8))) && (calcletra(dni.substring(0,7))!=dni.substring(7,8)) ) || 
							     ( (isNaN(dni.substring(8,9))) && (calcletra(dni.substring(0,8))!=dni.substring(8,9)) )    
								) 
							{
								alert("Debe rellenar correctamente la letra de control del DNI.");	
								document.ingresar_estancia.dniR.focus();
								return false;
							}			
							// Le a�adinos la letra si el �ltimo caracter es un n�mero
							if (!isNaN(dni.substring(7,8))) document.ingresar_estancia.dniR.value = dni+calcletra(dni);						 
						 }   
							// Si es igual a nueve..
						else if (dni.length == 9)
						{
						    
						 	// El pen�ltimo tiene que ser un n�mero y el �ltimo car�cter solo puede ser una letra permitida
							if ( (isNaN(dni.substring(7,8)) ) || (calcletra(dni.substring(0,8))!=dni.substring(8,9)) ) 
							{
								alert("Debe rellenar correctamente la letra de control del DNI.");					
								document.ingresar_estancia.dniR.focus();
								return false;
							}
									
						 }
						else if (dni.length > 9)
						{
							alert("Debe rellenar correctamente el campo DNI.");					
							document.ingresar_estancia.dniR.focus();
							return false;
						}
				      
				    }
				 }
			 } 
			 else if ( (tipo_documento == "N") && (pais == "ES") )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.ingresar_estancia.nacionalidad.focus();
				return false;
			 } 
			
			 else if ( (tipo_documento == "N") && (pais != "ES") )
			 {
				// Tiene que tener una longitud de 8 n�meros como m�nimo y 10 como m�ximo.
				if ( (dni.length<7) || (dni.length>10) )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.ingresar_estancia.dniR.focus();
					return false;
				}
				
				// Comprobamos si el primer car�cter es un car�cter X o un n�mero.
				else if( (isNaN(dni.substring(0,1))) && (dni.substring(0,1)!="X") )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.ingresar_estancia.dniR.focus();
					return false;
				}				
									
				//alert (dni.length);
				//alert (dni.substring(0,dni.length));
				
				// Si el primer elemento no tiene X se la a�adimos
			    if (!isNaN(dni.substring(0,1))) 
				{
					dni = "X"+dni;
					document.ingresar_estancia.dniR.value = dni;				
				}
			
			    // Si solo tiene 7 elemenos n�mericos, le a�adimos un cero.
				if ( (!isNaN(dni.substring(1,8))) && ( (dni.length == 8) || ((dni.length == 9) && (isNaN(dni.substring(8,9)))) ) )
				{				    
				    
					dni = "X0"+dni.substring(1,9);
					document.ingresar_estancia.dniR.value = dni;
				
				}
				//alert (dni.substring(1,9));
				// Le a�adinos la letra si el �ltimo caracter es un n�mero
				if (dni.length!=10) document.ingresar_estancia.dniR.value = dni+calcletra(dni.substring(1,9));	
				
				else if ( (dni.length==10) && ( (dni.substring(0,1)!="X") || (isNaN(dni.substring(1,9))) || 
											    ((dni.substring(9,10)) != calcletra(dni.substring(1,9)))    ) )
				{
					alert("Debe rellenar correctamente la letra de control del DNI.");					
					document.ingresar_estancia.dniR.focus();
					return false;
				}				
				
			 } 
			 else if ( (tipo_documento == "I") && (!ExisteElemento(PaisesPermitidos, pais) ) )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.ingresar_estancia.nacionalidad.focus();
				return false;
			 }
			else if  ( (tipo_documento == "P") && (pais == "ES") && (dni.length> 11) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					
				document.ingresar_estancia.dniR.focus();
				return false;
			}
			else if  ( (tipo_documento == "P") && (pais != "ES") && (dni.length>14) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					
				document.ingresar_estancia.dniR.focus();
				return false;
			}
			return true;
		}

	function abrir(caja) {
		if(document.getElementById("ocultar_aceptar").value=="si"){
	alert("Ya ha dado de alta la reserva del grupo");
	}else{
				if(document.ingresar_estancia.componente_representante.checked==true)
					document.ingresar_estancia.compo_representante.value = 'si';
				else
					document.ingresar_estancia.compo_representante.value = 'no';
	
		estado = true;
		document.getElementById('pestana').value=caja;
		for (i=1; i<=4;i++) {
			if(document.getElementById('lis'+i).style.display == "block")
			 caja2=i;
		}
		
		 if (caja == 3) {
		 estado=true;
				if(document.ingresar_estancia.componente_representante.checked==true){	  
						
				if ((document.ingresar_estancia.nombreR.value == "") || (document.ingresar_estancia.dniR.value == "") || (document.ingresar_estancia.ape1.value == "")||(annoNa.getComboText()=="")||(annoEx.getComboText()=="")||(mesEx.getComboText()=="")||(diaEx.getComboText()=="") ){
					alert("Debes rellenar todos los campos");
					estado = false;}
				else if((mesNa.getComboText()=="")||(diaNa.getComboText()=="")){
							if(document.ingresar_estancia.nacionalidad[document.ingresar_estancia.nacionalidad.selectedIndex].value!="ES"){
								estado= false;
								alert("Debes rellenar todos los campos");
							}
						}
						if(estado){	
							var meses = new Array ("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		var mesex;
				for(var i=0;i<12;i++){
				if(mesEx.getComboText() == meses[i]){
			  	if(i+1<10){
				    mesex="0"+(i);
				}else{
				mesex=i;
				}
				break;
				}
				}
				
				var mesna;
				for(var i=0;i<12;i++){
				if(mesNa.getComboText() == meses[i]){
			  	if(i+1<10){
				    mesna="0"+(i);
				}else{
				mesna=i;
				}
				break;
				}
				}
				
				var diana=diaNa.getComboText();
				if(diana=="")
					diana=1;
					if(mesna==00)
						mesna=1;
							
							var cadena=diaEx.getComboText()+'-'+mesex+'-'+annoEx.getComboText();
							if(estado==true)
								estado=validar_fecha(cadena,"Debe de rellenar una fecha de expedici�n v�lida");
							
							var cadena=diana+'-'+mesna+'-'+annoNa.getComboText();
							
							if(estado==true)
								estado=validar_fecha(cadena,"Debe de rellenar una fecha de nacimiento v�lida");
							
							ahora2 = new Date();
				ahoraDay2 = ahora2.getDate();
						ahoraMonth2 = ahora2.getMonth();
						ahoraYear2 = ahora2.getYear();
						fecha_hoy = new Date(ahoraYear2,ahoraMonth2,ahoraDay2);
					
							fecha_naci= new Date(annoNa.getComboText(),mesna-1,diana);
						fecha_expe= new Date(annoEx.getComboText(),mesex-1,diaEx.getComboText());
						if(fecha_expe<fecha_naci){
							alert("La fecha de expedici�n no puede ser menor que la fecha de nacimiento");
							estado = false;
						}else if(fecha_hoy<fecha_naci){
							alert("La fecha de nacimiento no puede ser mayor que la fecha de hoy");
							estado = false;
						}else if(fecha_hoy<fecha_expe){
							alert("La fecha de expedici�n no puede ser mayor que la fecha de hoy");
							estado = false;
						}
				//////////////
					var nacionalidad = document.ingresar_estancia.nacionalidad.value;
					var indice_doc = document.ingresar_estancia.tipo_doc.selectedIndex; 
						
						if(document.ingresar_estancia.telefono.value.length>0){		
					if(document.ingresar_estancia.telefono.value.length<9){
						alert("El tel�fono debe de contar de al menos nueve d�gitos");
								estado = false;
					}
				}
					
					
				if(document.ingresar_estancia.sexo_r[1].checked == false && document.ingresar_estancia.sexo_r[0].checked ==false){
		alert("Debe seleccionar una casilla de sexo.");
		estado = false;
	}
						
				if(estado == true)
					estado=validadni();
			}
		
		}
		}
		if(estado == true){
			if(caja2==1){	
				estado = comprobar_datos_grupo();
						
				if(estado==true){
					mantener_cuadro_edades();
			
					camposTexto = document.getElementById("ingresar_estancia").elements; // recorro los hidden que hay en cada celda seleccionable del cuadro de habitaciones
					
					fecha = document.getElementById("fecha_ver").value; 
					dia = document.getElementById("fecha_ver").value.substring(0,2);
					mes = document.getElementById("fecha_ver").value.substring(3,5);
					anio = document.getElementById("fecha_ver").value.substring(6,10);
					var hay = 0;
		
					var cad = "";
					seleccionadas = Array();
					seleccionadas_dias = Array();
					fechas = document.getElementById("fec_selec").value.split("+");
					if(document.getElementById("hab_selec").value != ""){
						seleccionadas_dias = document.getElementById("hab_selec").value.split("*");	//con esto se quitan las habitaciones deseleccionadas, cuando el usuario se mueve por las paginas de las habitaciones
						if(document.getElementById("fec_selec").value == "")
							pos = 0;
						else
							pos = fechas.length-1;
						if(seleccionadas_dias[pos] == "")
							pos --;
						var exi = (seleccionadas_dias[pos]) ? true:false;
						if(exi){
							seleccionadas = seleccionadas_dias[pos].split("-");
							for(e = 0; e < seleccionadas.length-1; e++){ //recorro todas las habitaciones seleccionadas
								nom1 = seleccionadas[e].split("&");
								nom = nom1[0] + "-" + nom1[1];
								var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
								if(exi){	//si existe y sigue estando seleccionada, se mantiene en la cadena
									if(document.getElementById(nom).value == "1")
										cad = cad + seleccionadas[e] + "-";
								}
								else
									cad = cad + seleccionadas[e] + "-"; //si no existe es un habitacion de otra pagina, por lo que tambien se mantiene
							}
						}
					}
					document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas
					for(e = 0; e < seleccionadas_dias.length-1; e++){	//todas menos la ultima la dejo igual
						document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
					}
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
					seleccionadas = Array();
					seleccionadas = cad.split("-");
					cad1 = "";
					for(i = 0; i < camposTexto.length; i++){
						trozo = camposTexto[i].name.split("-"); // por lo que recogo el n�mero de habitacion
						nombre = "td" + trozo[0] + "_" + trozo[1];
						var exi = (document.getElementById(nombre)) ? true:false; //dice si existe o no un elemento
						if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// si su valor esta a uno, es que se ha seleccionado esa cama
							cama = trozo[0] + "&" + trozo[1];
							enc = 0;
							for(e = 0; e < seleccionadas.length; e++){
								if(seleccionadas[e] == cama)
									enc = 1;
							}
							if(enc == 0)
								cad1 = cad1 + cama + "-"; // y lo a�ado al valor de hidden que recoge el n�mero de las habitaciones seleccionadas
							hay = 1;
						}
					}
					if(hay == 1){//si hay alguna habitaci�n seleccionada se pone la fecha en el text de fechas
							document.getElementById("fec_selec").value = document.getElementById("fec_selec").value + document.getElementById("fecha_ver").value + "+";
							document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad1 + "*"; //cierro las habitaciones seleccionadas para el dia
							fechas1 = document.getElementById("fec_selec").value.split("+");
							enc = 0;
							for(e = 0; e < fechas1.length; e++){
								if(fechas1[e] == fecha)
									enc = 1;
							}
							if(enc == 0){	//si no se encuentra la fecha que se va ha ver, se le pone las camas igual que el dia inferior, si existe no se hace nada
								seleccionadas_dias1 = document.getElementById("hab_selec").value.split("*");
								pos = seleccionadas_dias1.length - 2;	//tomo la posicion del ultimo elemento
								tem_dia = fecha.substr(0,2);
								tem_mes = eval(fecha.substr(3,2))+1;
								tem_ayo = fecha.substr(6,4);
								fecha_act = new Date(tem_ayo,tem_mes,tem_dia);
								fecha_tem = new Date(1900,1,1);
								for(e = 0; e < fechas1.length; e++){	//busco el dia inferior al dia que se va a ver
									tem_dia = fechas1[e].substr(0,2);
									tem_mes = eval(fechas1[e].substr(3,2))+1;
		
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
					if(eval(cad_dia) < 10)
						cad_dia = "0"+eval(cad_dia);
					if(eval(cad_mes) < 10)
						cad_mes = "0"+eval(cad_mes);
					fecha_cad = cad_dia + "/" + cad_mes + "/" + cad_ayo;
					fecha_cad1 = cad_dia + "-" + cad_mes + "-" + cad_ayo;
					document.getElementById("fecha_ver").value = fecha_cad;
					document.getElementById("fecha_cal").value = fecha_cad1;
					document.getElementById("mov_cal").value='5'; // se indica que se esta moviendo el calendario
							
					comprobar_existencia_grupo();
				}else 
						document.getElementById('comprobar_grupo').value="no";
					
			}else if(caja2==2){
			estado=true;
			if(document.ingresar_estancia.componente_representante.checked==true){	  
				if ((document.ingresar_estancia.nombreR.value == "") || (document.ingresar_estancia.dniR.value == "") || (document.ingresar_estancia.ape1.value == "")||(annoNa.getComboText()=="")||(annoEx.getComboText()=="")||(mesEx.getComboText()=="")||(diaEx.getComboText()=="") ){
					alert("Debes rellenar todos los campos");
					estado = false;
				}
				else if((mesNa.getComboText()=="")||(diaNa.getComboText()=="")){
							if(document.ingresar_estancia.nacionalidad[document.ingresar_estancia.nacionalidad.selectedIndex].value!="ES"){
								estado= false;
								alert("Debes rellenar todos los campos");
							}
						}
						if(estado){	
							var meses = new Array ("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		var mesex;
				for(var i=0;i<12;i++){
				if(mesEx.getComboText() == meses[i]){
			  	if(i+1<10){
				    mesex="0"+(i);
				}else{
				mesex=i;
				}
				break;
				}
				}
				
				var mesna;
				for(var i=0;i<12;i++){
				if(mesNa.getComboText() == meses[i]){
			  	if(i+1<10){
				    mesna="0"+(i);
				}else{
				mesna=i;
				}
				break;
				}
				}
				
				var diana=diaNa.getComboText();
				if(diana=="")
					diana=1;
					if(mesna==00)
						mesna=1;
							
							var cadena=diaEx.getComboText()+'-'+mesex+'-'+annoEx.getComboText();
							if(estado==true)
								estado=validar_fecha(cadena,"Debe de rellenar una fecha de expedici�n v�lida");
							
							var cadena=diana+'-'+mesna+'-'+annoNa.getComboText();
							if(estado==true)
								estado=validar_fecha(cadena,"Debe de rellenar una fecha de nacimiento v�lida");
							
							ahora2 = new Date();
				ahoraDay2 = ahora2.getDate();
						ahoraMonth2 = ahora2.getMonth();
						ahoraYear2 = ahora2.getYear();
						fecha_hoy = new Date(ahoraYear2,ahoraMonth2,ahoraDay2);
					
							fecha_naci= new Date(annoNa.getComboText(),mesna-1,diana);
						fecha_expe= new Date(annoEx.getComboText(),mesex-1,diaEx.getComboText());
						if(fecha_expe<fecha_naci){
							alert("La fecha de expedici�n no puede ser menor que la fecha de nacimiento");
							estado = false;
						}else if(fecha_hoy<fecha_naci){
							alert("La fecha de nacimiento no puede ser mayor que la fecha de hoy");
							estado = false;
						}else if(fecha_hoy<fecha_expe){
							alert("La fecha de expedici�n no puede ser mayor que la fecha de hoy");
							estado = false;
						}
			/////////////////////////////////////
					var nacionalidad = document.ingresar_estancia.nacionalidad.value;
					var indice_doc = document.ingresar_estancia.tipo_doc.selectedIndex; 
					
					if(document.ingresar_estancia.telefono.value.length>0){		
						if(document.ingresar_estancia.telefono.value.length<9){
							alert("El tel�fono debe de contar de al menos nueve d�gitos");
								estado = false;
						}
					}
					
					if(document.ingresar_estancia.sexo_r[1].checked == false && document.ingresar_estancia.sexo_r[0].checked ==false){
						alert("Debe seleccionar una casilla de sexo.");
						estado = false;
					}
					
					if(estado == true)
						estado=validadni();
				}
			}else if(caja2==3){
			
			}else if(caja2==4){
			
			}
			}
		}
		if(caja == 1){
			document.getElementById('comprobar_grupo').value="si";
		}else{
			document.getElementById('comprobar_grupo').value="no";
		}

		if (estado == true) {
		//document.ingresar_estancia.abrir_caja.value = caja;
		
		        if(caja==4 && document.getElementById('insertar_nuevo').value=="no"){
				
				for (i=1; i<=4;i++) 
					document.getElementById('lis'+i).style.display = "none";
				  document.getElementById('lis4').style.display = "block";
				  //alert(document.ingresar_estancia.listado_componentes.value);
				 	if(document.ingresar_estancia.listado_componentes.value != 'si'){
					document.ingresar_estancia.listado_componentes.value = 'si';
					document.getElementById("ingresar_estancia").submit();
					
					}
					}else if(document.getElementById('insertado').value!='si'){
					if(document.ingresar_estancia.listado_componentes.value == 'si'){
					document.ingresar_estancia.listado_componentes.value = 'no';
					
					
					document.getElementById("ingresar_estancia").submit();
					
					}
					if(caja==3){
						for (i=1; i<=4;i++) 
							document.getElementById('lis'+i).style.display = "none";
						document.getElementById('lis3').style.display = "block";
					}
					if(caja==2){
						for (i=1; i<=4;i++) 
							document.getElementById('lis'+i).style.display = "none";
						document.getElementById('lis2').style.display = "block";
					}
					if(caja==1){
						for (i=1; i<=4;i++) 
							document.getElementById('lis'+i).style.display = "none";
						document.getElementById('lis1').style.display = "block";
					}
		  }
			
		}
		 
	
	
	}}
	function oculta_habitaciones(){
			document.getElementById('caja_superior_derecha_a').style.display='none';
			document.getElementById('tabla_listado').style.height='400px';
		}
function valores_negativos(valor){
		if(eval(document.getElementById(valor).value/1)<0){
			alert("Ese valor no puede ser negativo");
			document.getElementById(valor).value=0;
		}
	}

	function cambiar_camas2(){
		valores_negativos("hombres");
	valores_negativos("mujeres");
		var mujer=eval(document.getElementById("mujeres").value);
		var hombre=eval(document.getElementById("hombres").value);
		if(isNaN(mujer)){
			mujer=0;
		}
		if(isNaN(hombre)){
			hombre=0;
		}
		document.getElementById("f_camas").value=eval(mujer)+eval(hombre)-eval(document.getElementById("camas_asignadas").value);
		if(document.getElementById("f_camas").value<0){
		document.getElementById("f_camas").value=0;
		}
	}
	
	function cambiar_noches(){
	if(eval(document.getElementById("nnoches").value/1)==0){
	document.getElementById("nnoches").value=1;
	
		}else if(eval(document.getElementById("nnoches").value/1)<0){
		alert("Las noches no pueden ser negativas");
		document.getElementById("nnoches").value=document.getElementById("noches_estancia").value;
		///////////
	}else{
				mantener_cuadro_edades();
			//pongo las habitaciones seleccionadas en el text de habitaciones
				camposTexto = document.getElementById("ingresar_estancia").elements; // recorro los hidden que hay en cada celda seleccionable del cuadro de habitaciones			
				var hay = 0;

				var cad = "";
				seleccionadas = Array();
				seleccionadas_dias = Array();
				fechas = document.getElementById("fec_selec").value.split("+");
				if(document.getElementById("hab_selec").value != ""){
					seleccionadas_dias = document.getElementById("hab_selec").value.split("*");	//con esto se quitan las habitaciones deseleccionadas, cuando el usuario se mueve por las paginas de las habitaciones
					if(document.getElementById("fec_selec").value == "")
						pos = 0;
					else
	//aaaaaaaaaaaaaaaaaaaaaaaaaaaa
						pos = fechas.length-1;
					if(seleccionadas_dias[pos] == "")
						pos --;
	//aaaaaaaaaaaaaaaaaaaaaaaaaaaa				
					var exi = (seleccionadas_dias[pos]) ? true:false;
					if(exi){
						seleccionadas = seleccionadas_dias[pos].split("-");
						for(e = 0; e < seleccionadas.length-1; e++){ //recorro todas las habitaciones seleccionadas
							nom1 = seleccionadas[e].split("&");
							nom = nom1[0] + "-" + nom1[1];
							var exi = (document.getElementById(nom)) ? true:false; //dice si existe o no un elemento
							if(exi){	//si existe y sigue estando seleccionada, se mantiene en la cadena
								if(document.getElementById(nom).value == "1")
									cad = cad + seleccionadas[e] + "-";
							}
							else
								cad = cad + seleccionadas[e] + "-"; //si no existe es un habitacion de otra pagina, por lo que tambien se mantiene
						}
					}
				}
				document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas
		//aaaaaaaaaaaaaaaaaaaaaaaaaaaa
				for(e = 0; e < seleccionadas_dias.length-2; e++){	//todas menos la ultima la dejo igual
		//aaaaaaaaaaaaaaaaaaaaaaaaaaaa
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
				}
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
				seleccionadas = Array();
				seleccionadas = cad.split("-");

				for(i = 0; i < camposTexto.length; i++){	//pongo las habitaciones que se han seleccionado en el text
					trozo = camposTexto[i].name.split("-"); // por lo que recogo el n�mero de habitacion
					nombre = "td" + trozo[0] + "_" + trozo[1];
					var exi = (document.getElementById(nombre)) ? true:false; //dice si existe o no un elemento
					if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// si su valor esta a uno, es que se ha seleccionado esa cama
						cama = trozo[0] + "&" + trozo[1];
						enc = 0;
						for(e = 0; e < seleccionadas.length; e++){
							if(seleccionadas[e] == cama)
								enc = 1;
						}
						if(enc == 0)
							document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cama + "-"; // y lo a�ado al valor de hidden que recoge el n�mero de las habitaciones seleccionadas
					}
				}
				
				
				document.getElementById("mov_cal").value = '1';
				
			if(document.getElementById("fec_selec").value != "" && document.getElementById("nnoches").value < numero_noches){	//si el numero de noches disminuye
				hab_selec = document.getElementById("hab_selec").value;
				fec_selec = document.getElementById("fec_selec").value;
				edad_selec = document.getElementById("cuadros_hab_selec").value;
				cad_dia = document.getElementById("diaL").value;
				cad_mes =  document.getElementById("mesL").value;
				cad_ayo =  document.getElementById("annoL").value;
				f_fec = new Date(cad_ayo,eval(cad_mes)-1,cad_dia);
				f_cont = new Date(cad_ayo,eval(cad_mes)-1,cad_dia);
				f_sal = new Date(cad_ayo,eval(cad_mes)-1,eval(cad_dia)+eval(document.getElementById("nnoches").value)-1);
				fechas_quedan = new Array();
				cad_fechas = "";
				cad_habitaciones = "";
				cad_edades = "";
				fechas_selec = fec_selec.split("+");
				habitaciones_selec = hab_selec.split("*");
				edades_selec = edad_selec.split("*");
				for(i = 0; i < fechas_selec.length; i++){
					if(fechas_selec[i] != ""){
						f_tem = new Date(fechas_selec[i].substring(6,10),fechas_selec[i].substring(3,5)-1,fechas_selec[i].substring(0,2));
						if(f_tem <= f_sal){
							cad_fechas = cad_fechas + fechas_selec[i] + "+";
							cad_habitaciones = cad_habitaciones + habitaciones_selec[i] + "*";
							if(edades_selec[i] != "")
								cad_edades = cad_edades  + edades_selec[i] + "*";
						}
					}
				}

				document.getElementById("hab_selec").value = cad_habitaciones;
				document.getElementById("fec_selec").value = cad_fechas;
				document.getElementById("cuadros_hab_selec").value = cad_edades;
				f_tem = new Date(document.getElementById("fecha_ver").value.substring(6,10),document.getElementById("fecha_ver").value.substring(3,5)-1,document.getElementById("fecha_ver").value.substring(0,2));
				if(f_tem > f_sal){ //si la fecha que se esta viendo es menor que la nueva fecha se salida se mueve a la fecha de salida
					cad_dia = f_sal.getDate();
					cad_mes = f_sal.getMonth()+1;
					cad_ayo = f_sal.getYear();
					if(eval(cad_dia) < 10)			//si el dia es menor que 10 le a�ado el 0 del inicio
						cad_dia = "0"+eval(cad_dia);
					if(eval(cad_mes) < 10)			//si el mes es menor que 10 le a�ado el 0 del inicio, para que quede en formato dd/mm/aaaa
						cad_mes = "0"+eval(cad_mes);	
					fecha_cad = cad_dia + "/" + cad_mes + "/" + cad_ayo;		//creo la fecha que se va ha mostrar en 'distribuci�n de habitaciones'
					fecha_cad1 = cad_dia + "-" + cad_mes + "-" + cad_ayo;
					document.getElementById("fecha_ver").value = fecha_cad;		//y lo pongo en la fecha que se va ha ver
					document.getElementById("fecha_cal").value = fecha_cad1;	//y la que se envia al calendario
					document.getElementById("mov_cal").value = '5';
					
				}
			}
			//alert(document.getElementById("fecha_ver").value + " * " + document.getElementById("fecha_cal").value);
//aaaaaaaaaaaaaaaaaaaaaaaaaaaa
			document.getElementById("abrir_caja").value = 3;	
			document.getElementById('comprobar_grupo').value='si';
//aaaaaaaaaaaaaaaaaaaaaaaaaaaa
			document.getElementById("ingresar_estancia").submit();
		}
	}
	function calcular_edad(){ 

    //calculo la fecha de hoy 
    hoy=new Date() 
  

    //compruebo que los ano, mes, dia son correctos 
    var ano 
     ano = annoNa.getComboText();
    

    var mes 
    mes = mesNa.getComboText();
    
   var dia 
    dia =diaNa.getComboText();
    if(dia.length==1)
		dia="0"+dia;


    

    //resto los a�os de las dos fechas 
    edad=hoy.getYear()- ano - 1; //-1 porque no se si ha cumplido a�os ya este a�o 

    //si resto los meses y me da menor que 0 entonces no ha cumplido a�os. Si da mayor si ha cumplido 
    if (hoy.getMonth() + 1 - mes < 0) //+ 1 porque los meses empiezan en 0 
       return edad 
    if (hoy.getMonth() + 1 - mes > 0) 
       return edad+1 

    //entonces es que eran iguales. miro los dias 
    //si resto los dias y me da menor que 0 entonces no ha cumplido a�os. Si da mayor o igual si ha cumplido 
    if (hoy.getUTCDate() - dia >= 0) 
       return edad + 1 

    return edad 
} 
	//funcion para modificar una estancia
	function insertar(){
 estado = true;

					 // si estan vacios les pongo a cero
					  if(document.getElementById("mujeres").value=="")
					    document.getElementById("mujeres").value=0;
						
					if(document.getElementById("hombres").value=="")
					    document.getElementById("hombres").value=0;
						////////////////////////////////////////////////
						for(x=0;x<eval(document.getElementById("contt").value);x++){
								if(document.getElementById("tpersonas"+x).value=="")
					    				document.getElementById("tpersonas"+x).value=0;
							
						}
						
									if(document.getElementById("menos10").value=="")
					    				document.getElementById("menos10").value=0;
									
									
									if(document.getElementById("entre10_19").value=="")
					    				document.getElementById("entre10_19").value=0;
								
									if(document.getElementById("entre20_25").value=="")
					    				document.getElementById("entre20_25").value=0;
									
									if(document.getElementById("entre26_29").value=="")
					    				document.getElementById("entre26_29").value=0;
									
									if(document.getElementById("entre30_39").value=="")
					    				document.getElementById("entre30_39").value=0;
								
									if(document.getElementById("entre40_49").value=="")
					    				document.getElementById("entre40_49").value=0;
							
									if(document.getElementById("entre50_59").value=="")
					    				document.getElementById("entre50_59").value=0;
									
									if(document.getElementById("entre60_69").value=="")
					    				document.getElementById("entre60_69").value=0;
									
								
									if(document.getElementById("mas69").value=="")
					    				document.getElementById("mas69").value=0;
									
								////////////////////////////////////////////////
										
						 var mujer=eval(document.getElementById("mujeres").value);
					   var hombre=eval(document.getElementById("hombres").value);
					   	
					   var total=mujer+hombre;
					  if(total<10){
							alert("No puede insertar un grupo con menos de 10 personas");
							estado=false;
						}
					   
					   var total_edad=eval(document.getElementById("menos10").value)+eval(document.getElementById("entre10_19").value)+eval(document.getElementById("entre20_25").value)+eval(document.getElementById("entre26_29").value)+eval(document.getElementById("entre30_39").value)+eval(document.getElementById("entre40_49").value)+eval(document.getElementById("entre50_59").value)+eval(document.getElementById("entre60_69").value)+eval(document.getElementById("mas69").value);
					
				
							var edad_repre=calcular_edad();
						fecha_lle = new Date(document.ingresar_estancia.annoL.value,document.ingresar_estancia.mesL.value-1,document.ingresar_estancia.diaL.value);
						ahora2 = new Date();
	
						ahoraDay2 = ahora2.getDate();
						ahoraMonth2 = ahora2.getMonth();
						ahoraYear2 = ahora2.getYear();
						fecha_hoy = new Date(ahoraYear2,ahoraMonth2,ahoraDay2);
						
						//******************************
						//calculo el grupo de edad del representante
					if(document.ingresar_estancia.componente_representante.checked==true){
						if(edad_repre<10&&eval(document.getElementById("menos10").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(edad_repre>=10&&edad_repre<=19&&eval(document.getElementById("entre10_19").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(eval(edad_repre)>=20&&eval(edad_repre)<=25&&eval(document.getElementById("entre20_25").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(edad_repre>=26&&edad_repre<=29&&eval(document.getElementById("entre26_29").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(edad_repre>=30&&edad_repre<=39&&eval(document.getElementById("entre30_39").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(edad_repre>=40&&edad_repre<=49&&eval(document.getElementById("entre40_49").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(edad_repre>=50&&edad_repre<=59&&eval(document.getElementById("entre50_59").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(edad_repre>=60&&edad_repre<=69&&eval(document.getElementById("entre60_69").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(edad_repre>69&&eval(document.getElementById("mas69").value)==0){
							alert("La edad del representante no pertenece a ning�n grupo de edad");
							estado = false;
						}else if(mujer==0 && document.ingresar_estancia.sexo_r[0].checked==true){
							alert("Tiene que haber al menos una mujer ya que el representante es mujer");
							estado = false;
						 }else if(hombre==0 && document.ingresar_estancia.sexo_r[1].checked==true){
							alert("Tiene que haber al menos un hombre ya que el representante es hombre");
							estado = false;
						 }
					}
					if(fecha_hoy < fecha_lle ){
								estado = false;
								alert("La fecha de llegada no puede ser mayor que la fecha de hoy");
							}
					if(estado){		
						if(total==0){
							estado = false;
							alert("Tiene que indicar cuantos hombres y mujeres hay en el grupo");
						}else{
								if(total!=total_edad){
									estado = false;
									alert("Tiene que rellenar bien los grupos de edades");
								}
							}
										if(document.getElementById("f_camas").value != 0){
										alert("Tiene que marcar todas las camas");
										
										estado = false;
										}else{
											if(document.getElementById("es_inc").value != 0){
											
											llamar_dia();
											estado = false;
											}
										}
								}
										
										if(estado){
											estado = comprobar_hab();			
											}
										if(estado){
											estado = comprobar_tablas_personas();
											}
										if(estado){
											tem_fechas = document.getElementById("fec_selec").value.split("+");
											tem_cuadro = document.getElementById("cuadros_hab_selec").value.split("*");
											if(!(((tem_fechas.length-1) == tem_cuadro.length) || (tem_fechas.length == tem_cuadro.length))){
												alert('Tiene que rellenar la tabla de edades en todos los d�as que cambien las habitaciones');
												estado = false;
											}
										}
										if(estado){	
								document.getElementById("insertar_nuevo").value = 'si';
								document.getElementById('comprobar_grupo').value="no";
								document.getElementById('abrir_caja').value=4;
								mantener_habitaciones();
								mantener_cuadro_edades();
								document.ingresar_estancia.submit();
	
	}}
	function comprobar_tablas_personas(){//funcion para comprobar que coincidan las personas y las edades de las dos tablas
	
	var cont=0;
	var contt=0;
	var num_per=0;
	var per=0;
	var estado=false;
	 var estado2=true;
			for(i=0;i<eval(document.getElementById("tipos_edad").value);i++){
				for(x=cont;x<eval(document.getElementById("contador_cuadros_hab").value)+cont;x++){
								if(document.getElementById("tpersonas"+x).value=="")
					    				document.getElementById("tpersonas"+x).value=0;
							per = per+eval(document.getElementById("tpersonas"+x).value);
							contt=contt+1;
						}
						cont=contt;
						
				if(eval(document.getElementById("min"+i).value) <= 0 && eval(document.getElementById("max"+i).value) >= 9){
					num_per += eval(document.getElementById("menos10").value);
					
				}
			if(eval(document.getElementById("min"+i).value) <= 10 && eval(document.getElementById("max"+i).value) >= 19){
					num_per += eval(document.getElementById("entre10_19").value);
			
				}
				if(eval(document.getElementById("min"+i).value) <= 20 && eval(document.getElementById("max"+i).value) >= 25){
					num_per += eval(document.getElementById("entre20_25").value);
					}
				if(eval(document.getElementById("min"+i).value) <= 26 && eval(document.getElementById("max"+i).value) >= 29){
					num_per += eval(document.getElementById("entre26_29").value);
					
				}
				if(eval(document.getElementById("min"+i).value) <= 30 && eval(document.getElementById("max"+i).value) >= 39){
					num_per += eval(document.getElementById("entre30_39").value);
					
				}
				if(eval(document.getElementById("min"+i).value) <= 40 && eval(document.getElementById("max"+i).value) >= 49){
					num_per += eval(document.getElementById("entre40_49").value);
					
				}
				if(eval(document.getElementById("min"+i).value) <= 50 && eval(document.getElementById("max"+i).value) >= 59){
					num_per += eval(document.getElementById("entre50_59").value);
					
				}
				if(eval(document.getElementById("min"+i).value) <= 60 && eval(document.getElementById("max"+i).value) >= 69){
					num_per += eval(document.getElementById("entre60_69").value);
					
				}
				if(eval(document.getElementById("max"+i).value) >= 70 ){
					num_per += eval(document.getElementById("mas69").value);
					
				}
				
						
						
				
				if(num_per==per){
					estado = true;
				}else{
					estado = false;
				}
				num_per=0;
				per=0;
					if(estado == false){
			estado2=false;
			
			}
			
			}
			if(estado2 == false){
		
				alert('Tiene que rellenar bien las tablas de las personas');
			}
			
			return estado2;
	}
	
		function mantener_cuadro_edades(){
		var cont = 0;
		var contt = 0;
		
		fechas_cuadro = document.getElementById("fec_selec").value.split("+");

		fechas_cuadro_tem = new Array();
		con1 = 0;
		for(con = 0; con < fechas_cuadro.length; con++){
			if(fechas_cuadro[con] != ""){
				fechas_cuadro_tem[con1] = fechas_cuadro[con];
				con1 ++;
			}
		}
		fechas_cuadro = new Array();
		fechas_cuadro = fechas_cuadro_tem;
		
		pos = -1;
		for(con = 0; con < fechas_cuadro.length; con++){
			if(fechas_cuadro[con] == document.getElementById("fecha_ver").value){
				pos = con;
			}
		}
		if(pos != -1){
			edades_cuadro = document.getElementById("cuadros_hab_selec").value.split("*");
			edades_cuadro_tem = new Array();
			con1 = 0;
			for(con = 0; con < edades_cuadro.length; con++){
				if(edades_cuadro[con] != ""){
					edades_cuadro_tem[con1] = edades_cuadro[con];
					con1 ++;
				}
			}
			edades_cuadro = new Array();
			edades_cuadro = edades_cuadro_tem;
			
			cont = 0;
			contt = 0;
			cad = "";
			for(i = 0; i < eval(document.getElementById("tipos_edad").value); i++){
				for(x = cont; x < eval(document.getElementById("contador_cuadros_hab").value) + cont; x++){
					cad = cad + document.getElementById("tpersonas"+x).value + "-";
					contt=contt+1;
				}
				cont=contt;
			}
			edades_cuadro[pos] = cad;
			
			document.getElementById("cuadros_hab_selec").value = "";
			for(con = 0; con < edades_cuadro.length; con++){
				if(edades_cuadro[con])
					document.getElementById("cuadros_hab_selec").value = document.getElementById("cuadros_hab_selec").value + edades_cuadro[con] + "*"; 
			}			
		}
		else{
			for(i = 0; i < eval(document.getElementById("tipos_edad").value); i++){
				for(x = cont; x < eval(document.getElementById("contador_cuadros_hab").value) + cont; x++){
					document.getElementById("cuadros_hab_selec").value = document.getElementById("cuadros_hab_selec").value + document.getElementById("tpersonas"+x).value + "-";
					contt=contt+1;
				}
				cont=contt;
			}
			document.getElementById("cuadros_hab_selec").value = document.getElementById("cuadros_hab_selec").value + "*";
			
		}
				
	}

	
	var tipos_habitacion=new Array();
	
	function comprobar_hab(){//funcion para comprobar que coincidan los tipos de habitacion y las camas de las dos tablas
	
					mens = 0;
					estado= true;
				camposTexto = document.getElementById("ingresar_estancia").elements;
				tipos_contador = new Array();
				for(j=0;j<tipos_habitacion.length;j++){
					tipos_contador[j]=0;
					for(i = 0; i < camposTexto.length; i++){
						trozo = camposTexto[i].name.split("-"); // por lo que recogo el n�mero de habitacion
						nombre = "td" + trozo[1] + "_" + trozo[2];
						var exi = (document.getElementById(nombre)) ? true:false; //dice si existe o no un elemento
						if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='tipo-'+tipos_habitacion[j] && document.getElementById(trozo[1] + "-" + trozo[2]).value=='1'){	// si su valor esta a uno, es que se ha seleccionado esa cama
							
							tipos_contador[j]++;
						}
					}
				}
				 
				 tipos_contador_cuadro = new Array();
				for(j=0;j<tipos_habitacion.length;j++){
					tipos_contador_cuadro[j]=0;
					for(x=0;x<eval(document.getElementById("contt").value);x++){
			                if( tipos_habitacion[j] == eval(document.getElementById("thab"+x).value) ){
								tipos_contador_cuadro[j]+=eval(document.getElementById("tpersonas"+x).value);
							}
			
						}
				}
				
				for(j=0;j<tipos_habitacion.length&&estado==true;j++){
				
				     if(tipos_contador[j]==tipos_contador_cuadro[j]){
					 	estado= true;
					 }else{
					 	estado= false;
						mens = 1;
					 }
				}
		if(mens == 1)
			alert("Tienen que coincidir la distribuci�n de habitaciones con la tabla de edades y tipos de habitaci�n");
		return estado;
		}
	//funcion para cuando recargo la pagina me mantenga las habitaciones de la distribucion de las habitaciones
	function mantener_habitaciones(){
	
	
	//pongo las habitaciones seleccionadas en el text de habitaciones
			camposTexto = document.getElementById("ingresar_estancia").elements; //recojo los elementos que hay en el formulario
			//--con esto se quitan las habitaciones deseleccionadas de la ultima fecha seleccionada, cuando el usuario se mueve por las paginas de 'distribuci�n de habitaciones'			
			var cad = "";
			seleccionadas = Array();
			seleccionadas_dias = Array();
			fechas = document.getElementById("fec_selec").value.split("+"); //fechas en un array que contiene todas las fechas por las que el usuario ha pasado en 'distribuci�n de habitaciones'
			if(document.getElementById("hab_selec").value != ""){ //si el text de habitaciones seleccionadas no est� vacio
				seleccionadas_dias = document.getElementById("hab_selec").value.split("*"); //seleccionadas_dias es un array que contiene las camas seleccionadas separadas por d�as, la posici�n 0 de este array corresponde con las camas seleccionadas para la fecha 0 del array fechas y as� con las dem�s posicioens
				if(document.getElementById("fec_selec").value == "") //si el text de fechas seleccionadas est� vacio se toma la posici�n 0
					pos = 0;
				else												//si no se toma la ultima posici�n del array de fechas
					pos = seleccionadas_dias.length-1;
				if(seleccionadas_dias[pos] == "")	//si la posici�n seleccionada del array fechas est� vacia se coge una posici�n menos
					pos --;
				seleccionadas = seleccionadas_dias[pos].split("-"); //seleccionadas es un array que tiene las camas seleccionadas de la fecha correspondiente, separadas
				for(cam = 0; cam < seleccionadas.length-1; cam++){ //recorro todas las camas seleccionadas
					nom1 = seleccionadas[cam].split("&");		//se corta por el & para obtener el n�mero de habitaci�n y de cama por separado
					nom = nom1[0] + "-" + nom1[1];			//se compone el nombre del text correspondiente a esa habitaci�n y a esa cama
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
			for(dia = 0; dia < seleccionadas_dias.length-1; dia++){	//las habitaciones seleccionadas de todos los dias lo dejo igual, menos la �ltima
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[dia] + "*";
			}
			document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
			seleccionadas = Array();
			seleccionadas = cad.split("-"); //recojo las camas seleccionadas para esta fecha
			cad1 = "";
			//pongo las camas que se han seleccionado en la fecha de 'distribuci�n de habitaciones' en el text de habitaciones seleccionadas
			for(i = 0; i < camposTexto.length; i++){	//recorro todos los elementos del formulario
				trozo = camposTexto[i].name.split("-"); // por lo que recojo el n�mero de habitacion y de la cama
				nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca
				var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda
				if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// y valor el text esta a 1, es que se ha seleccionado esa cama
					trozo = camposTexto[i].name.split("-"); // por lo que recojo el n�mero de habitacion y de la cama
					cama = trozo[0] + "&" + trozo[1]; 		//compongo el nombre de la cama para ponerlo en el text de habitaciones seleccionadas
					enc = 0;
					for(cam = 0; cam < seleccionadas.length; cam++){	//busco la cama en el array de las camas que ya estan en el text para esta fecha
						if(seleccionadas[cam] == cama)	//si se encuentra
							enc = 1;					//se pone la bandera a 1
					}	
					if(enc == 0) //si no se encuentra la cama 
						document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cama + "-"; //lo a�ado al valor del text que recoge el n�mero de las habitaciones seleccionadas
				}
			}
			//pongo la fecha seleccionada en el text de fechas
			document.getElementById("fec_selec").value = document.getElementById("fec_selec").value + document.getElementById("fecha_ver").value + "+";
											
	}
	
	//funcion para modificar elcomponente
	function modificar_componente(){
	estado=false;
		estado=comprobar_componentes_datos2();
	
		if(estado){
	document.getElementById("modificar_compo").value = 'si';

								document.ingresar_estancia.submit();
								
								}
	
	}
	//funcion para comprobar el componente del grupo
	function comprobar_componentes_datos2(){
				estado = true;
			
					  
						if ((document.ingresar_estancia.nombre_compo.value == "") || (document.ingresar_estancia.dni_compo.value == "") || (document.ingresar_estancia.ape1_compo.value == "") ){
							alert("Debes rellenar todos los campos");
							estado = false;}
						else{
						
					var mesex;
			for(var i=0;i<12;i++){
			if(mesEx_componente.getComboText() == meses[i]){
			  	if(i+1<10){
				    mesex="0"+(i+1);
				}else{
				mesex=i+1;
				}
				break;
			}
			}
			var mesal;
			for(var i=0;i<12;i++){
			if(mesNa_componente.getComboText() == meses[i]){
				if(i+1<10){
				    mesal="0"+(i+1);
				}else{
				mesal=i+1;
				}
			}}
			
						var diaex=diaEx_compo.getComboText();
			var diana=diaNa_compo.getComboText();
			if(diaex.length==1)
				diaex=0+diaex;
			if(diana.length==1)
				diana=0+diana;
				fecha_naci = Array(diana,mesal,annoNa_compo.getComboText());
				fecha_expe = Array(diaex,mesex,annoEx_compo.getComboText());
				
				//////////////////
					var mes_na=mesNa_compo.getSelectedValue();
				var mes_ex=mesEx_compo.getSelectedValue();
				if((eval(mes_na)/1)!=0)
					mes_na=mes_na-1;
				if((eval(mes_ex)/1)!=0)
					mes_na=mes_ex-1;
				fecha_naci_d = new Date(annoNa_compo.getSelectedValue(),mes_na,diaNa_compo.getSelectedValue());
						fecha_expe_d = new Date(annoEx_compo.getSelectedValue(),mes_ex,diaEx_compo.getSelectedValue());
						ahora2 = new Date();
	
						ahoraDay2 = ahora2.getDate();
						ahoraMonth2 = ahora2.getMonth();
						ahoraYear2 = ahora2.getYear();
						fecha_hoy = new Date(ahoraYear2,ahoraMonth2,ahoraDay2);
						if(fecha_hoy < fecha_naci_d ){
								estado = false;
								alert("La fecha de nacimiento no puede ser mayor que la fecha de hoy");
							}
							if(fecha_hoy < fecha_expe_d ){
								estado = false;
								alert("La fecha de expedici�n no puede ser mayor que la fecha de hoy");
							}
				
			var d = new Date();
		    var e = new Date();
		    var f_exp = (fecha_expe[0]+"/"+ fecha_expe[1] +"/"+ fecha_expe[2]);
			var f_nac = (fecha_naci[0]+"/"+ fecha_naci[1] +"/"+ fecha_naci[2]);
		
		
			d.setFullYear(f_exp.substring(6,10),
				f_exp.substring(3,5)-1,
					f_exp.substring(0,2))
					
			
			if(d.getMonth() != f_exp.substring(3,5)-1	|| d.getDate() != f_exp.substring(0,2))
			{
				alert("Debe introducir una fecha de expedici�n v�lida.")
				estado = false;
			}
			e.setFullYear(f_nac.substring(6,10),
				f_nac.substring(3,5)-1,
					f_nac.substring(0,2))
			
			 if (  e.getMonth() != f_nac.substring(3,5)-1	|| e.getDate() != f_nac.substring(0,2)) 
				{
					alert("Debe introducir una fecha de nacimiento v�lida.")
					estado = false;
				}
					
			// Se evalua que ha introducido una fecha de expedici�n y nacimiento.
			else if(fecha_expe[2] < fecha_naci[2]){
				alert("Debe introducir una fecha de expedici�n mayor o igual a la fecha de nacimiento");	
				estado = false;
			}else{
				if(fecha_expe[2] == fecha_naci[2]){
					if(fecha_expe[1] < fecha_naci[1]){
						alert("Debe introducir una fecha de expedici�n mayor o igual a la fecha de nacimiento");	
						estado = false;
					}else{
						if(fecha_expe[1] == fecha_naci[1]){
							if(fecha_expe[0] < fecha_naci[0]){
								alert("Debe introducir una fecha de expedici�n mayor o igual a la fecha de nacimiento");	
								estado = false;
							}
						}
					}
				}
			}
			//////////////////////////
					
					var indice_naci = document.ingresar_estancia.nacionalidad_compo.selectedIndex;
							var nacionalidad = document.ingresar_estancia.nacionalidad_compo[indice_naci].value;
							var indice_doc = document.ingresar_estancia.tipo_doc_compo.selectedIndex;
							
								
								
						
								
							if(document.ingresar_estancia.nacionalidad_compo[indice_naci].value=="ES"){
									
									if((document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="I")||(document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="N")||(document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="X")){
									alert("No puede ser ese tipo de documento con la nacionalidad Espa�a");
									estado = false;
									}
									if(document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="P"){
										if(document.ingresar_estancia.dni_compo.value.length>11){
											alert("No puede ser el pasaporte mayor de 11 caracteres");
											estado = false;
										}
									}
									if((document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="C")||(document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="D")){
												var id = document.ingresar_estancia.dni_compo.value;
									
												//comprueba que el �ltimo caracter del dni sea una letra, que los anteriores sean n�meros, y que la longitud total sea mayor o igual a 8. 
												//En caso de no cumplirse estas condiciones, se enviar� un mensaje y se colocar� el foco en el campo dni.
												if(!isNaN(id.substring(id.length-1,id.length)) ){
														alert("Debe rellenar correctamente el tipo de Documento.");
														estado = false;
													}else{
														if(isNaN(id.substring(0,id.length-1))){
															alert("Debe rellenar correctamente el tipo de Documento.");
															estado = false;	
														}else{
															if(id.length<8){
																alert("Debe rellenar correctamente el tipo de Documento.");
																estado = false;
															}else{
																if(id.length == 8){
																	document.ingresar_estancia.dni_compo.value = "0"+document.ingresar_estancia.dni_compo.value;
																}}
															}
														}
											
											
									}
							
							}
						else{
							if(document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="P"){
										if(document.ingresar_estancia.dni_compo.value.length>14){
											alert("No puede ser el pasaporte mayor de 14 caracteres");
											estado = false;
										}
							}
							if((nacionalidad=="AD")||(nacionalidad=="DE")||(nacionalidad=="AT")||(nacionalidad=="BE")||(nacionalidad=="CY")||(nacionalidad=="DK")||(nacionalidad=="SK")||(nacionalidad=="SI")||(nacionalidad=="EE")||(nacionalidad=="FI")||(nacionalidad=="FR")||(nacionalidad=="GR")||(nacionalidad=="NL")||(nacionalidad=="HU")||(nacionalidad=="IE")||(nacionalidad=="IT")||(nacionalidad=="IS")||(nacionalidad=="UK")||(nacionalidad=="LV")||(nacionalidad=="LT")||(nacionalidad=="LU")||(nacionalidad=="MT")||(nacionalidad=="MC")||(nacionalidad=="NO")||(nacionalidad=="PL")||(nacionalidad=="PT")||(nacionalidad=="SM")||(nacionalidad=="SE")||(nacionalidad=="CH")){
											if((document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="D")||(document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="C")||(document.ingresar_estancia.tipo_doc_compo[indice_doc].value=="N")){
											alert("No puede ser ese tipo de documento con esa nacionalidad ");
											estado = false;
											}
												}
								else{ 
												if(document.ingresar_estancia.tipo_doc_compo[indice_doc].value!="N"){
												alert("No puede ser ese tipo de documento con esa nacionalidad");
												estado = false;
												}
										
										}
							}
						
						
						}	
							return estado;
	
	
	}
	//funcion para volver a poner los datos del representante

	function cargar_datos_representante(){
		document.getElementById("nombreR").value=document.getElementById("cargar_nombre").value;
	document.getElementById("ape1").value=document.getElementById("cargar_ape1").value;
	document.getElementById("ape2").value=document.getElementById("cargar_ape2").value;
	document.getElementById("dniR").value=document.getElementById("cargar_dni").value;
	document.getElementById("telefono").value=document.getElementById("cargar_tf").value;
	
	}
	//funcion para limpiar los datos del representante
	function limpiar_representante(){
	document.getElementById("nombreR").value="";
	document.getElementById("ape1").value="";
	document.getElementById("ape2").value="";
	document.getElementById("dniR").value="";
	document.getElementById("telefono").value="";

					
	document.getElementById("cambiar_nacionalidad").value = "si";
	document.getElementById("diaNa").options[0].selected = true;
	document.getElementById("mesNa").options[0].selected = true;
	document.getElementById("annoNa").options[0].selected = true;
	document.getElementById("diaEx").options[0].selected = true;
	document.getElementById("mesEx").options[0].selected = true;
	document.getElementById("annoEx").options[0].selected = true;
	document.getElementById("tipo_doc").options[0].selected = true;
	}
	//funcion para si al poner un pais q no sea espa�a las provincias no se puedan ingresar
function cambiar_provincias(){
var indice_naci2 = document.ingresar_estancia.pais.selectedIndex;
var nacionalidad2 = document.ingresar_estancia.pais[indice_naci2].value;
		if(nacionalidad2!="ES"){
			document.ingresar_estancia.prov.disabled=true;
			document.ingresar_estancia.prov.selectedIndex="";
		}else
			document.ingresar_estancia.prov.disabled=false;
	}
	function comprobar_cif_grupo(){
		document.getElementById("comprobar_cif").value="si";
		
		mantener_cuadro_edades();
		var exi = (document.getElementById("hab_selec")) ? true:false; //dice si existe o no un elemento
		if(exi){
			mantener_cuadro_edades();
			//pongo las habitaciones seleccionadas en el text de habitaciones
			camposTexto = document.getElementById("ingresar_estancia").elements; // recorro los hidden que hay en cada celda seleccionable del cuadro de habitaciones			
			var hay = 0;
			for(i = 0; i < camposTexto.length; i++){
				trozo = camposTexto[i].name.split("-"); // por lo que recogo el n�mero de habitacion
				nombre = "td" + trozo[0] + "_" + trozo[1];
				var exi = (document.getElementById(nombre)) ? true:false; //dice si existe o no un elemento
				if(exi && camposTexto[i].type=='hidden' && camposTexto[i].value=='1'){	// si su valor esta a uno, es que se ha seleccionado esa cama
					trozo = camposTexto[i].name.split("-"); // por lo que recogo el n�mero de habitacion
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + trozo[0] + "&" + trozo[1] + "-"; // y lo a�ado al valor de hidden que recoge el n�mero de las habitaciones seleccionadas
					hay = 1;
				}
			}
			if(hay == 1)
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + "*";
			if(document.getElementById("hab_selec").value!=""){
				//pongo la fecha seleccionada en el text de fechas
				document.getElementById("fec_selec").value = document.getElementById("fec_selec").value + document.getElementById("fecha_ver").value + "+";
			}
		}
		document.getElementById("ingresar_estancia").submit();
	}
	function cambiar_mens(celda, hab, cam, ini, fin, camb){
		ini = ini.substr(8,2)+"/"+ini.substr(5,2)+"/"+ini.substr(0,4);	//compongo las fechas en formato dd/mm/aaaa
		fin = fin.substr(8,2)+"/"+fin.substr(5,2)+"/"+fin.substr(0,4);
		var exi = (document.getElementById("inc"+hab+"-"+cam)) ? true:false; 
		if(exi)	//si la cama no est� disponible todos los d�as
			if(camb)
				mens = "La habitaci�n cambir� de tipo a partir del d�a " + ini; 	//creo el mensaje a mostrar
			else
				mens = "Esta cama est� ocupada a partir del d�a " + ini; 	//creo el mensaje a mostrar
		window.status = mens;							//y lo pongo en la barra de estado
		document.getElementById(celda).title = mens;	//y en el alt de la celda
		return true;
	}
	function poner_cambio_hab(fecha, hab, tipo, indi){
		dias_cam = document.getElementById("cambio_tipo").value.split("*");	//pongo todas las fecha que hay hasta el momento en un array
		exi = 0;			//esta variable dice si la fecha que se va ha poner ya esta puesta o no
		for(i = 0; i < dias_cam.length; i++){	//recorro todas las fechas
			if((hab + "-" + fecha + "-" + tipo + "-" + indi) == dias_cam[i]){			//y si ya existe lo indico
				exi = 1;
			}
		}
		if(exi == 0)	//si la fecha ya esta en el text no se hace nada, y si aun no esta se pone, el gui�n sirve para separar una fecha de otra
			document.getElementById("cambio_tipo").value = document.getElementById("cambio_tipo").value + hab + "-" + fecha + "-" + tipo + "-" + indi + "*";
	}

	function quitar_cambio_hab(fecha, hab){
		camposTexto = document.getElementById("ingresar_estancia").elements; //recojo los elementos que hay en el formulario
		
		enc = 0;	//esta variable dice si hay alguna cama seleccionada con la misma fecha que la que se busca
		for(i1 = 0; i1 < camposTexto.length; i1++){	//recorro todos los elementos del formulario
			trozo = camposTexto[i1].name.split("-"); //por lo que recojo el n�mero de habitacion y de la cama
			nombre = "td" + trozo[0] + "_" + trozo[1];	//compongo el nombre de la celca
			var exi = (document.getElementById(nombre)) ? true:false; //si exite la celda,
			var exi1 = (document.getElementById("inc"+trozo[0]+"-"+trozo[1])) ? true:false; //es una cama incompleta,
			if(exi && exi1 && camposTexto[i1].type=='hidden' && camposTexto[i1].value=='1'){	//y est� seleccionada
				fecha_tem = document.getElementById("inc_fecha"+trozo[0]+"-"+trozo[1]).value;	//compongo la fecha de la cama, ya que esta en formato aaaa-mm-dd
				fecha_tem1 = fecha_tem.substring(8,10)+"/"+fecha_tem.substring(5,7)+"/"+fecha_tem.substring(0,4);
				if(fecha_tem1 == fecha && hab == "")	//compruebo si la fecha es la misma que la que se est� buscando y si es asi se indica
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
			document.getElementById("cambio_tipo").value = cad;	//y se cambia el valo????z?s??:?:????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????U?s??:?:???????????????????????????????????????????????????????????????????????????????????????????r del text
		}
	}
	function cargar_cuadros_nuevos(){//sirver para cargar los cuadros hombres, mujeres y grupo de edadea en los hidden
		if(document.getElementById("h_menos10").value=="")
				document.getElementById("h_menos10").value=0;
			 if(document.getElementById("m_menos10").value=="")
				document.getElementById("m_menos10").value=0;
			 if(document.getElementById("h_entre10_19").value=="")
				document.getElementById("h_entre10_19").value=0;
			 if(document.getElementById("m_entre10_19").value=="")
				document.getElementById("m_entre10_19").value=0;
			 if(document.getElementById("h_entre20_25").value=="")
				document.getElementById("h_entre20_25").value=0;
			 if(document.getElementById("h_entre20_25").value=="")
				document.getElementById("h_entre20_25").value=0;
			 if(document.getElementById("m_entre20_25").value=="")
				document.getElementById("m_entre20_25").value=0;
			 if(document.getElementById("h_entre26_29").value=="")
				document.getElementById("h_entre26_29").value=0;
			 if(document.getElementById("m_entre26_29").value=="")
				document.getElementById("m_entre26_29").value=0;
			 if(document.getElementById("h_entre30_39").value=="")
				document.getElementById("h_entre30_39").value=0;
			 if(document.getElementById("m_entre30_39").value=="")
				document.getElementById("m_entre30_39").value=0;
			 if(document.getElementById("h_entre40_49").value=="")
				document.getElementById("h_entre40_49").value=0;
			 if(document.getElementById("m_entre40_49").value=="")
				document.getElementById("m_entre40_49").value=0;
			 if(document.getElementById("h_entre50_59").value=="")
				document.getElementById("h_entre50_59").value=0;
			 if(document.getElementById("m_entre50_59").value=="")
				document.getElementById("m_entre50_59").value=0;
			 if(document.getElementById("h_entre60_69").value=="")
				document.getElementById("h_entre60_69").value=0;
			 if(document.getElementById("m_entre60_69").value=="")
				document.getElementById("m_entre60_69").value=0;
			if(document.getElementById("h_mas69").value=="")
				document.getElementById("h_mas69").value=0;
			 if(document.getElementById("m_mas69").value=="")
				document.getElementById("m_mas69").value=0;
				
				document.getElementById("h_menos10").value=document.getElementById("h_menos10").value/1;
				document.getElementById("m_menos10").value=document.getElementById("m_menos10").value/1;
				document.getElementById("h_entre10_19").value=document.getElementById("h_entre10_19").value/1;
				document.getElementById("m_entre10_19").value=document.getElementById("m_entre10_19").value/1;
				document.getElementById("h_entre20_25").value=document.getElementById("h_entre20_25").value/1;
				document.getElementById("m_entre20_25").value=document.getElementById("m_entre20_25").value/1;
				document.getElementById("h_entre26_29").value=document.getElementById("h_entre26_29").value/1;
				document.getElementById("m_entre26_29").value=document.getElementById("m_entre26_29").value/1;
				document.getElementById("h_entre30_39").value=document.getElementById("h_entre30_39").value/1;
				document.getElementById("m_entre30_39").value=document.getElementById("m_entre30_39").value/1;
				document.getElementById("h_entre40_49").value=document.getElementById("h_entre40_49").value/1;
				document.getElementById("m_entre40_49").value=document.getElementById("m_entre40_49").value/1;
				document.getElementById("h_entre50_59").value=document.getElementById("h_entre50_59").value/1;
				document.getElementById("m_entre50_59").value=document.getElementById("m_entre50_59").value/1;
				document.getElementById("h_entre60_69").value=document.getElementById("h_entre60_69").value/1;
				document.getElementById("m_entre60_69").value=document.getElementById("m_entre60_69").value/1;
				document.getElementById("h_mas69").value=document.getElementById("h_mas69").value/1;
				document.getElementById("m_mas69").value=document.getElementById("m_mas69").value/1;
				
				
				
			document.getElementById("menos10").value=eval(document.getElementById("h_menos10").value)+eval(document.getElementById("m_menos10").value);
			document.getElementById("entre10_19").value=eval(document.getElementById("h_entre10_19").value)+eval(document.getElementById("m_entre10_19").value);
			document.getElementById("entre20_25").value=eval(document.getElementById("h_entre20_25").value)+eval(document.getElementById("m_entre20_25").value);
			document.getElementById("entre26_29").value=eval(document.getElementById("h_entre26_29").value)+eval(document.getElementById("m_entre26_29").value);
			document.getElementById("entre30_39").value=eval(document.getElementById("h_entre30_39").value)+eval(document.getElementById("m_entre30_39").value);
			document.getElementById("entre40_49").value=eval(document.getElementById("h_entre40_49").value)+eval(document.getElementById("m_entre40_49").value);
			document.getElementById("entre50_59").value=eval(document.getElementById("h_entre50_59").value)+eval(document.getElementById("m_entre50_59").value);
			document.getElementById("entre60_69").value=eval(document.getElementById("h_entre60_69").value)+eval(document.getElementById("m_entre60_69").value);
			document.getElementById("mas69").value=eval(document.getElementById("h_mas69").value)+eval(document.getElementById("m_mas69").value);
			document.getElementById("mujeres").value =eval(document.getElementById("m_menos10").value)+eval(document.getElementById("m_entre10_19").value)+eval(document.getElementById("m_entre20_25").value)+eval(document.getElementById("m_entre26_29").value)+eval(document.getElementById("m_entre30_39").value)+eval(document.getElementById("m_entre40_49").value)+eval(document.getElementById("m_entre50_59").value)+eval(document.getElementById("m_entre60_69").value)+eval(document.getElementById("m_mas69").value);
			
			document.getElementById("hombres").value =eval(document.getElementById("h_menos10").value)+eval(document.getElementById("h_entre10_19").value)+eval(document.getElementById("h_entre20_25").value)+eval(document.getElementById("h_entre26_29").value)+eval(document.getElementById("h_entre30_39").value)+eval(document.getElementById("h_entre40_49").value)+eval(document.getElementById("h_entre50_59").value)+eval(document.getElementById("h_entre60_69").value)+eval(document.getElementById("h_mas69").value);
			cambiar_camas2();	
									
									
	}
</script>
<?PHP

function parteCadenas($cadena, $num) {
	$nom = $cadena;
	$nom_partido = "";
	
	//Separa las palabras distintas de la l�nea
	$array_nom = split(" ", $nom);
	for($u=0; $u<count($array_nom); $u++) {
		if(strlen($array_nom[$u]) > $num) {
			$palabras_largas = 'si';
		} else {
			$palabras_largas = 'no';
		}
	}

	if($palabras_largas == 'si') {
	for($b=0; $b<count($array_nom); $b++) {
		$cont_act = 0;
		//Separa por guiones
		$array_nom_i = split("-", $array_nom[$b]);
		if(count($array_nom_i) > 1) {
			$guiones = 'si';
		} else {
			$guiones = 'no';
		}
		for($j=0; $j<count($array_nom_i); $j++) {
			$cont_act = $cont_act +1;
					
			$resto = strlen($array_nom_i[$j]) % $num;
			$cociente = (strlen($array_nom_i[$j]) - $resto) / $num;
			
			
			if($guiones == 'no') {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					$nom_partido = $nom_partido. " ".$array_nom_i[$j];
				} 

			} else {
				if(strlen($array_nom_i[$j]) > $num) {
					$cont = 0;
					for($n=1; $n<=$cociente; $n++) {
						if($nom_partido == "") {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						} else {
							$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $num);
							$cont = $cont + $num;
						}
					}
					$nom_partido = $nom_partido."<br>".substr($array_nom_i[$j], $cont, $resto);
				} else {
					if($nom_partido == "") {
						$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
					} else {
						if($cont_act == count($array_nom_i)) {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j];
						} else {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j]."-";
						}
					}
				} 
			}
		}
	} 
	return $nom_partido;
	} else {
		return $cadena;
	}
} 
//recojo variables
if($_GET['dni_pra']){
$dni_pra=$_GET['dni_pra'];
$fecha_llegada=$_GET['fecha_llegada'];
$cargar=0;//variable para cargar los datos de la base de datos
}else{
$cargar=1;
$dni_pra=$_POST['dni_pra'];
$fecha_llegada=$_POST['fecha_llegada'];
}

if($_GET['listado_componentes']){
$listado_componentes=$_GET['listado_componentes'];

}else if($_POST['listado_componentes']){
$listado_componentes=$_POST['listado_componentes'];
}else{
$listado_componentes="no";
}
 

//variables para cargar datos
     $result_modi=mysql_query("select * from detalles,pra Where detalles.DNI_PRA = pra.DNI_PRA  and detalles.DNI_PRA='".$dni_pra."' and detalles.Fecha_Llegada='".$fecha_llegada."'");
	
		$row_modi=mysql_fetch_array($result_modi);
		$sql="select * from detalles Where detalles.DNI_PRA='".$dni_pra."'";
		$result_modi2=mysql_query($sql);
		$afechas=array();
		//busco si hay una reserva partida y mirola primera fecha de llegada
		$afechas[0]['llegada']=$row_modi['Fecha_Llegada'];
		$afechas[0]['salida']=$row_modi['Fecha_Salida'];
		$afechas[0]['pernoctas']=$row_modi['PerNocta'];
		$llegada_provisional=$row_modi['Fecha_Llegada'];
		$salida_provisional=$row_modi['Fecha_Salida'];
		$cont=1;
		for($i=0;$i<mysql_num_rows($result_modi2);$i++){
			$fila=mysql_fetch_array($result_modi2);
			if($salida_provisional==$fila['Fecha_Llegada']){
				$afechas[$cont]['llegada']=$fila['Fecha_Llegada'];
				$afechas[$cont]['salida']=$fila['Fecha_Salida'];
				$salida_provisional=$fila['Fecha_Salida'];
				$afechas[$cont]['pernoctas']=$fila['PerNocta'];
				$cont=$cont+1;
			}
			if($llegada_provisional==$fila['Fecha_Salida']){
				$afechas[$cont]['llegada']=$fila['Fecha_Llegada'];
				$afechas[$cont]['salida']=$fila['Fecha_Salida'];
				$llegada_provisional=$fila['Fecha_Llegada'];
				$afechas[$cont]['pernoctas']=$fila['PerNocta'];
				$cont=$cont+1;
			}
				
		}
		foreach ($afechas as $key => $row){
			$fecha_lleg_ord[$key] = $row['llegada'];
		}
		array_multisort ($fecha_lleg_ord, SORT_ASC, $afechas);
		 
		$crear_fechas=array();
		$crear_hab=array();
		$acumulador_pernoctas = 0;
		for($i=0;$i<count($afechas);$i++){
			$acumulador_pernoctas += $afechas[$i]['pernoctas'];
			$crear_fechas[$i]=substr($afechas[$i]['llegada'],8,2)."/".substr($afechas[$i]['llegada'],5,2)."/".substr($afechas[$i]['llegada'],0,4);
			$sql="select * from reserva where Fecha_Llegada='".$afechas[$i]['llegada']."' and DNI_PRA ='".$dni_pra."'";
			$result=mysql_query($sql);
			for($x=0;$x<mysql_num_rows($result);$x++){
				$fila=mysql_fetch_array($result);
				$crear_hab[$i]=$crear_hab[$i].$fila['Id_Hab']."-".$fila['Num_Camas'];
				if($x<mysql_num_rows($result)-1)
					$crear_hab[$i]=$crear_hab[$i]."*";
			}
			
		}
		$fecha_llegada=$afechas[0]['llegada'];
		
      $result_pais=mysql_query("select * from Pais WHERE Id_Pais='".$row_modi["Id_Pais"]."'");
			$row_pais=mysql_fetch_array($result_pais);
			$result_pais_repre=mysql_query("select * from Pais WHERE Id_Pais='".$row_modi["Id_Pais"]."'");
			$row_pais_repre=mysql_fetch_array($result_pais_repre);
			$result_prov=mysql_query("select * from provincia WHERE Id_Pro='".$row_modi["Id_Pro"]."'");
			$row_prov=mysql_fetch_array($result_prov);
			
//recojo variables estancia

$abrir_caja=$_POST['abrir_caja'];
		  
if($_POST['pais']){
$insertado=$_POST['insertado'];
$sexo_r=$_POST['sexo_r'];
$pasar_grupo=$_POST['pasar_grupo'];
			$localizador=$_POST['localizador'];
					$dir = $_POST['dir'];
						$pais = $_POST['pais'];
						$prov = $_POST['prov'];
						$localidad = $_POST['localidad'];
						$email = $_POST['email'];
						$nombre_grupo =  $_POST['nombreG'];
					
							$cambiar_nacionalidad=$_POST['cambiar_nacionalidad'];
							$tipo_doc=$_POST['tipo_doc'];
							$nacionalidad= $_POST['nacionalidad'];
							$cif =  $_POST['cif'];
						$nnoches=$_POST['nnoches'];
						$noches_estancia=$_POST['nnoches'];
						echo "<script>var numero_noches = '" . $nnoches . "'</script>";
							$diaEx=$_POST['diaEx'];
							$mesEx=$_POST['mesEx'];
							$annoEx=$_POST['annoEx'];
							
							$diaNa=$_POST['diaNa'];
							$mesNa=$_POST['mesNa'];
							$annoNa=$_POST['annoNa'];
							$dniR= $_POST['dniR'];
							$nombreR= $_POST['nombreR'];
						$ape1 = $_POST['ape1'];
						$ape2 = $_POST['ape2'];
						$telefono = $_POST['telefono'];
	
	$mujeres=$_POST['mujeres'];
		$hombres=$_POST['hombres'];
		$gente = $_POST['mujeres'] + $_POST['hombres'];
		$menos10= $_POST['menos10'];
		$entre10_19= $_POST['entre10_19'];
		$entre20_25= $_POST['entre20_25'];
		$entre26_29= $_POST['entre26_29'];
		$entre30_39= $_POST['entre30_39'];
		$entre40_49= $_POST['entre40_49'];
		$entre50_59= $_POST['entre50_59'];
		$entre60_69= $_POST['entre60_69'];
		$mas69= $_POST['mas69'];
		$h_menos10= $_POST['h_menos10'];
		$h_entre10_19= $_POST['h_entre10_19'];
		$h_entre20_25= $_POST['h_entre20_25'];
		$h_entre26_29= $_POST['h_entre26_29'];
		$h_entre30_39= $_POST['h_entre30_39'];
		$h_entre40_49= $_POST['h_entre40_49'];
		$h_entre50_59= $_POST['h_entre50_59'];
		$h_entre60_69= $_POST['h_entre60_69'];
		$h_mas69= $_POST['h_mas69'];
		$m_menos10= $_POST['m_menos10'];
		$m_entre10_19= $_POST['m_entre10_19'];
		$m_entre20_25= $_POST['m_entre20_25'];
		$m_entre26_29= $_POST['m_entre26_29'];
		$m_entre30_39= $_POST['m_entre30_39'];
		$m_entre40_49= $_POST['m_entre40_49'];
		$m_entre50_59= $_POST['m_entre50_59'];
		$m_entre60_69= $_POST['m_entre60_69'];
		$m_mas69= $_POST['m_mas69'];
		$servicio=$_POST['servicio'];
		
		//$npagadas=$_POST['npagadas'];
		$hllegada=$_POST['hllegada'];
		$ingreso=$_POST['ingreso'];
		//$fecha_llegada=$_POST['fecha_llegada'];
}

 if($listado_componentes=="no"&&$_POST['insertar_nuevo'] != "si"&&$cargar==0) {
				if($row_modi['Localizador_Reserva']=="")
					$dniR="";
				else
					$dniR=$dni_pra;
							
								$servicio=$row_modi['servicio'];
							$email=$row_modi['Email_PRA'];
							$nombreR = $row_modi['Nombre_PRA'];
						$ape1 = $row_modi['Apellido1_PRA'];
						$ape2 = $row_modi['Apellido2_PRA'];
						$telefono = $row_modi['Tfno_PRA'];
		
		$nnoches=$acumulador_pernoctas;
		echo "<script>var numero_noches = '" . $nnoches . "'</script>";
		$hllegada=$row_modi['Llegada_Tarde'];
		$ingreso=$row_modi['Ingreso'];
		


}
$diaL=substr($fecha_llegada,8,2);
		$mesL=substr($fecha_llegada,5,2);
		$annoL=substr($fecha_llegada,0,4);
		$fechal=$annoL."-".$mesL."-".$diaL;
$sql_eliminar2="delete from pra  where DNI_PRA ='".$dni_pra."'";
$sql_eliminar="delete from detalles  where DNI_PRA ='".$dni_pra."' and Fecha_Llegada='".$fecha_llegada."'";//mirar si va en cascada
		

if($_GET['eliminar_componente']=="si"){

$sql_eliminar_compo="delete from componentes_grupo  where DNI='".$_GET['eliminar_componente_dni']."' ";


        mysql_query($sql_eliminar_compo);
		/*echo "<script>alert('Se ha eliminado el componente del grupo');</script>";*/
		$eliminar_componente="no";
		
		$insertar_nuevo="no";
	$abrir_caja=4;

echo "<script>document.ingresar_estancia.submit();</script>";
}
if($_POST['modificar_compo']=="si"){



$sql_modificar_compo="UPDATE componentes_grupo SET Nombre_Gr='".$nombre_grupo."',Fecha_Llegada='". $fecha_llegada."' ,Tipo_documentacion='".$_POST['tipo_doc_compo']."',Fecha_Expedicion='".$_POST['annoEx_compo']."-".$_POST['mesEx_compo']."-".$_POST['diaEx_compo']."' ,Nombre='".$_POST['nombre_compo']."' ,Apellido1='".$_POST['ape1_compo']."' ,Apellido2='".$_POST['ape1_compo']."' ,Sexo='".$_POST['sexo_compo']."' ,Fecha_nacimiento='".$_POST['annoNa_compo']."-".$_POST['mesNa_compo']."-".$_POST['diaNa_compo']."' ,Id_Pais_nacionalidad='".$_POST['nacionalidad_compo']."' WHERE DNI = '".$_POST['dni_compo']."'";
      
		mysql_query($sql_modificar_compo);
		/*echo "<script>alert('Se ha modificado el componente del grupo');</script>";*/
		$modificar_compo="no";
		$listado_componentes="si";

		$abrir_caja=4;
echo "<script>document.ingresar_estancia.submit();</script>";
}

?>

<table border="0" width="1200px">

<tr> <td width="40%" valign="top">
			<form name="ingresar_estancia" id="ingresar_estancia" action="?pag=grupos_reserva.php" method="POST">
		  <div class="curved" style="display:block;">
		 <input type="hidden" name="listado_componentes" id="listado_componentes" value="<?PHP echo $listado_componentes; ?>">
		<input type="hidden" name="id_cama" value="0">
		<input type="hidden" name="modificar_compo" id="modificar_compo">
		<input type="hidden" name="abrir_caja"  id="abrir_caja" value="<? echo $abrir_caja; ?>">
		<input type="hidden" name="comprobar_grupo"  id="comprobar_grupo" value="<? echo $comprobar_grupo; ?>">
		<input type="hidden"  name="pestana" id="pestana" value="<? echo $_POST['pestana']; ?>">
		<input type="hidden" name="dni_pra"  id="dni_pra" value="<? echo $dni_pra; ?>">
		<input type="hidden" name="id_habitacion" value="">
		<input type="hidden" name="asignada" value="0">
		<input type="hidden" name="insertar_nuevo" id="insertar_nuevo" value="<? echo $insertar_nuevo; ?>">
		
		<input type="hidden" name="noches_estancia" id="noches_estancia" value="<? echo $noches_estancia; ?>">
		<input type="hidden" name="insertado" id="insertado" value="<? echo $insertado; ?>">
		<input type="hidden" name="nombre_grupo" id="nombre_grupo" value="<? echo $nombre_grupo; ?>">
		<input type="hidden" name="fecha_llegada" id="fecha_llegada" value="<? echo $fecha_llegada; ?>">
		<input type="hidden" name="cambiar_nacionalidad" id="cambiar_nacionalidad" value="<? echo $cambiar_nacionalidad; ?>">
		<input type="hidden" name="ocultar_aceptar" id="ocultar_aceptar" value="<?PHP echo $ocultar_aceptar; ?>">
		

		 <input type="hidden" name="cargar_dni" id="cargar_dni" value="<?PHP echo $dni_pra; ?>">
		 <input type="hidden" name="cargar_nombre" id="cargar_nombre" value="<?PHP echo $row_modi['Nombre_PRA']; ?>">
		 <input type="hidden" name="cargar_ape1" id="cargar_ape1" value="<?PHP echo $row_modi['Apellido1_PRA']; ?>">
		 <input type="hidden" name="cargar_ape2" id="cargar_ape2" value="<?PHP echo  $row_modi['Apellido2_PRA']; ?>">
		 <input type="hidden" name="cargar_tf" id="cargar_tf" value="<?PHP echo $row_modi['Tfno_PRA']; ?>">
		<b class="b1 c1"></b>
		 <b class="b2 c2"></b>
		 <b class="b3 c3"></b>
		 <b class="b4 c4"></b>
		  <div class="boxcontent">
		   <ul id="menu">
			<li>
			 <a href="#" onclick="abrir(1)">
			  <b class="h2"><font color="#064C87" face="verdana" size="3"><b>Datos del Grupo</b></font></b><br /></a>
			  
			  <span id="lis1" style="display:block">
			  <table border="0" align="center">
			   <tr><td class='label_formulario' >
			  
			    Nombre:
					</td><td>
					
					<input type="hidden" name="con_popup" class="input_formulario"  value="<? echo $con_popup; ?>">
				<input type="text" name="nombreG" id="nombreG" maxlength="50" class="input_formulario" value="<? echo $nombre_grupo; ?>" onBlur="this.value=this.value.toUpperCase()">
				
					
					</td><td><a href="#"><img src="../imagenes/botones/lupa.png" alt="Buscar Grupo" onclick="ver_pop_2()"  style="border:none;width:25px;height:25px;"></a><td>
				</tr>
				<tr><td class='label_formulario' >
			  
			    CIF:
					</td><td>
					
												<input type="hidden" name="comprobar_cif" id="comprobar_cif" class="input_formulario" value="<? echo $comprobar_cif; ?>"  maxlength="15" onBlur="this.value=this.value.toUpperCase()">

				<input type="text" name="cif" id="cif" maxlength="12" class="input_formulario" value="<? echo $cif; ?>" onBlur="this.value=this.value.toUpperCase();comprobar_cif_grupo()">
				
					
					</td><td><td>
				</tr>
				<tr>
					<td class='label_formulario'>Direcci�n:</td><td>
					
					<input type="text" name="dir"   maxlength="150" class="input_formulario" value="<? echo $dir; ?>">
					
					</td><td></td>
				</tr>
				<tr>
					<td class='label_formulario'>Pa�s:</td><td>
					<select name='pais'  id='pais' class='input_formulario' onChange="cambiar_provincias();">
					<?PHP
					
					
					$combo_pais=mysql_query("select * from pais ORDER BY Nombre_Pais ");
					while ($row_combo=mysql_fetch_array($combo_pais)) {
						if($_POST['pais']!=""){
							
						if ($row_combo['Id_Pais'] == $_POST['pais']) {
							?>
								<option value='<?echo $row_combo['Id_Pais']?>' selected><?echo substr($row_combo['Nombre_Pais'],0,22)?></option>
							<? } else { ?>
								<option value='<?echo $row_combo['Id_Pais']?>'><?echo substr($row_combo['Nombre_Pais'],0,22)?></option>
						<? }
						}else{
							if ($row_combo['Id_Pais'] != "ES") {
							?>
								<option value='<?echo $row_combo['Id_Pais']?>' ><? echo substr($row_combo['Nombre_Pais'],0,22); ?></option>
							<? } else { ?>
								<option value='<?echo $row_combo['Id_Pais']?>' selected><? echo substr($row_combo['Nombre_Pais'],0,22); ?></option>
						<? }
						
						}
					
					}
					?>
				  </select>
				   
				  </td><td></td>
				</tr>
				<tr>
					<td class='label_formulario'>Provincia:</td><td>
					
					<select name='prov'   id='prov' class='input_formulario'>
					<?PHP
					if($_POST['prov']){
					$condicion2=" where Id_Pro  = '".$_POST['prov']."'";
					}else{
					?>
					<option value=''>(S�lo Espa�a)</option><?}
					$combo_prov=mysql_query("select * from provincia ".$condicion2." ORDER BY Id_Pro");
					while ($row_prov=mysql_fetch_array($combo_prov)) {
					?>
						<option value='<? echo $row_prov['Id_Pro']; ?>'><? echo $row_prov['Nombre_Pro']; ?></option>
					<?}
					?>
					</select>
					</td><td></td>
				</tr>
				<tr>
					<td class='label_formulario'>Localidad:</td><td>
					
					
					<input type="text" name="localidad" id="localidad"  maxlength="150" class="input_formulario" value="<? echo $localidad; ?>">
					
					</td><td></td>
				</tr>
				<tr>
					<td class='label_formulario'>Email:</td><td>
					
										<input type="text" name="email" id="email"   maxlength="100" class="input_formulario" value="<? echo $email; ?>">
					
					</td><td></td>
				</tr>
				</table><br>
			  </span>
			 
			</li>
			<li>
			 
			  <b class="b1"></b>
			  <b class="b2"></b>
			  <b class="b3"></b>
			  <b class="b4"></b>
			  <a href="#" onclick="abrir(2)"><b class="h2"><font color="#064C87" face="verdana" size="3"><b>Datos del Representante</b></font></b><br /></a>
			  
			  <span id="lis2" style="display:none">
			   <br><table border="0" align="center">
			   
			   <tr>
					<td class='label_formulario'>Tipo:
					
					<select name="tipo_doc"  class='select_formulario' onChange="documentacion('nacionalidad','tipo_doc','dniR');" title="tipo documentacion">
					<option value="D" title="DNI"
						<?
							if(isset($_POST['tipo_doc']) && $_POST['tipo_doc'] =="D")	{
								echo " selected";
							}
						?>
						>D</option>						
						<option value="P" title="Pasaporte"
						<?
							if(isset($_POST['tipo_doc']) && $_POST['tipo_doc'] =="P")	{
								echo " selected";
							}
						?>
						>P</option>
						<option value="C" title="Permiso de Conducir"
						<?
							if(isset($_POST['tipo_doc']) && $_POST['tipo_doc'] =="C")	{
								echo " selected";
							}
						?>
						>C</option>
						<option value="I" title="Carta o Documento de Identidad"
						<?
							if(isset($_POST['tipo_doc']) && $_POST['tipo_doc'] =="I")	{
								echo " selected";
							}
						?>
						>I</option>
						<option value="N" title="Permiso de residencia Espa�ol"
						<?
							if(isset($_POST['tipo_doc']) && $_POST['tipo_doc'] =="N")	{
								echo " selected";
							}
						?>
						>N</option>
						<option value="X" title="Permiso de residencia UE"
						<?
							if(isset($_POST['tipo_doc']) && $_POST['tipo_doc'] =="X")	{
								echo " selected";
							}
						?>
						>X</option>
						
					</select></td>
					<td class='label_formulario'>
					
						D.N.I.:				<input type="text" name="dniR" id="dniR" maxlength="30" class="input_formulario" value="<? echo $dniR; ?>" onBlur="this.value=this.value.toUpperCase()">
					
					</td>
				</tr>
				<tr>
					<td class='label_formulario'>F.Expedici�n:</td><td colspan=2 class='label_formulario'>
				<table><tr><td>
					<select name='diaEx' id='diaEx' class='input_formulario'>
					<?PHP 
					for ($i=0;$i<=31;$i++) {
						if($i==$diaEx&&$diaEx){
							?>
					<option value='<?echo $i?>' selected><?echo $i?></option>
					<?PHP
							}else{
							if($i==0 &&!$diaEx){?>
							<option value='<?echo $i?>' ></option><?
						}else{
						?><option value='<?echo $i?>' ><?echo $i?></option><?
						}
						}
					}
					?>
					</select>
				</td><td>
					<select name='mesEx' id='mesEx' class='input_formulario' onchange="asignaDias('diaEx','mesEx','annoEx')">
					<?PHP 
					$meses = array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
					if(!$mesEx){ $i=0;
							?>
					<option value='<?echo $i?>' selected><?echo $meses[$i]?></option>
					<?PHP
						}
					for ($i=1;$i<=12;$i++) {
						if($i==$mesEx){
							?>
					<option value='<?echo $i?>' selected><?echo $meses[$i]?></option>
					<?PHP
							}else{
						?><option value='<?echo $i?>'><?echo $meses[$i]?></option><?
					}
					}
					?>	
					</select>
				</td><td>
				
					<select name="annoEx" id="annoEx" class="input_formulario" onchange="asignaDias('diaEx','mesEx','annoEx')"><?
					if(!$annoEx){?>
						<option value='' selected ></option>
					<? }
						for ($i=date("Y");$i>=1900;$i--) {
							if($i==$annoEx){
							?>
					<option value='<?echo $i?>' selected><?echo $i?></option>
					<?PHP
							}else{
							?><option value='<?echo $i?>'><?echo $i?></option><?
							}
						}
					?></select>
					</td></tr></table></td>
				</tr>
				<tr>
				
					<td class='label_formulario'>
					
					
					  Nacionalidad: 
					</td>
	<td class='label_formulario'>
					<select name='nacionalidad'  class='input_formulario'>
					<?PHP
				
					$result_paises = mysql_query("select * from pais ORDER BY Nombre_Pais ");
							while($fila_paises = mysql_fetch_array($result_paises)){
								echo "<option value=".$fila_paises['Id_Pais']." alt=".$fila_paises['Nombre_Pais']." title=".$fila_paises['Nombre_Pais'];
								if(isset($nacionalidad) && $nacionalidad != ""){ 
									if( $fila_paises['Id_Pais'] == $nacionalidad){
										echo " selected";	
									}
								}else{
									if( $fila_paises['Id_Pais'] == "ES"){
										echo " selected";	
									}
								}
								echo ">";
								if(strlen($fila_paises['Nombre_Pais'])<25){	
									echo $fila_paises['Nombre_Pais'];
								}else{
									echo substr($fila_paises['Nombre_Pais'],0,22)."...";
								}
							}
					
					 
					
					?>
				  </select>
				  </td>
				</tr>
				<tr>
					<td class='label_formulario'>F.Nacimiento:</td><td colspan=2 class='label_formulario'>
					<table>
					<tr><td>
					<select name="diaNa" id="diaNa" class="input_formulario">
					<?PHP 
					
						for ($i=0;$i<=31;$i++) {
						if($i==$diaNa&&$diaNa){
							?>
					<option value='<?echo $i?>' selected><?echo $i?></option>
					<?PHP
							}else{
							if($i==0 &&!$diaNa){?>
							<option value='<?echo $i?>' ></option><?
						}else{
						?><option value='<?echo $i?>' ><?echo $i?></option><?
						}
						}
					}					
					?>
					
					</select>
					</td><td><select name="mesNa" id="mesNa" class="input_formulario" onchange="asignaDias('diaNa','mesNa','annoNa')">
					<?PHP 
					$meses = array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
					if(!$mesNa){ $i=0;
							?>
					<option value='<?echo $i?>' selected><?echo $meses[$i]?></option>
					<?PHP
						}
					for ($i=1;$i<=12;$i++) {
						if($i==$mesNa){
							?>
					<option value='<?echo $i?>' selected><?echo $meses[$i]?></option>
					<?PHP
							}else{
						?><option value='<?echo $i?>'><?echo $meses[$i]?></option><?
					}
					}
					?>	
					</select>
					</td><td>
					<select name="annoNa" id="annoNa" class="input_formulario" onchange="asignaDias('diaNa','mesNa','annoNa')"><?PHP
					if(!$annoNa){?>
						<option value='' selected ></option>
					<? }
						for ($i=date("Y");$i>=1900;$i--) {
						if($i==$annoNa){
							?>
					<option value='<?echo $i?>' selected><?echo $i?></option>
					<?PHP
							}else{
							?><option value='<?echo $i?>'><?echo $i?></option><?
						}
						}
					?></select></td>
					</tr></table></td>
				</tr>
				
				
				<tr>
					<td class='label_formulario'>Nombre:</td><td>
					
					<input type="text" name="nombreR" maxlength="20" class="input_formulario" value="<? echo $nombreR; ?>" onBlur="this.value=this.value.toUpperCase()"></td><td></td>
				</tr>
				<tr>
					<td class='label_formulario'>Apellido 1:</td><td>
					
					<input type="text" name="ape1" maxlength="30" class="input_formulario" value="<? echo $ape1; ?>" onBlur="this.value=this.value.toUpperCase()"></td><td></td>
				</tr>
				<tr>
					<td class='label_formulario'>Apellido 2:</td><td>
					
					<input type="text" name="ape2" maxlength="30" class="input_formulario" value="<? echo $ape2; ?>" onBlur="this.value=this.value.toUpperCase()"></td><td></td>
				</tr>
				<tr>
					<td class='label_formulario'>Tel�fono:</td><td>
					
					<input type="text" name="telefono" maxlength="13" class="input_formulario" value="<? echo $telefono; ?>" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" /></td><td></td>
				</tr>
				<tr><td class='label_formulario'>Sexo:</td>
					<td class='label_formulario' colspan="2" align="left">
					<?PHP
					
					if($sexo_r=="F"){
					
					?>
					<input type="radio" name="sexo_r"  id="sexo_r" value="F" checked  >Mujer
					<input type="radio" name="sexo_r" id="sexo_r" value="M" >Hombre
					<?PHP
					}else if($sexo_r=="M"){
					?>
					<input type="radio" name="sexo_r"  id="sexo_r" value="F"   >Mujer
					<input type="radio" name="sexo_r" id="sexo_r" value="M" checked >Hombre
					<? }else{ ?>
					
					<input type="radio" name="sexo_r"  id="sexo_r" value="F"   >Mujer
					<input type="radio" name="sexo_r" id="sexo_r" value="M" >Hombre
					<? }?>
					</td>

				</tr>
				<tr>
					<td colspan="3" class='label_formulario'>
					<? 
						if($_POST['compo_representante']){
							if($_POST['compo_representante']=='si'){
							?><input type="checkbox" name="componente_representante" id="componente_representante" checked><?
							}else{
							?><input type="checkbox" name="componente_representante" id="componente_representante" ><?
							}
						}else{
					?>
					<input type="checkbox" name="componente_representante" id="componente_representante">
					<? } ?>
					<input type="hidden" name="compo_representante" id="compo_representante" value="<? echo $_POST['compo_representante']; ?>">
						<label for="componente_representante">A�adir como componente </label>
					
					</td>
				</tr>
				<tr>
				
					<td align="center" >
					<a href="#"><img src="../imagenes/botones-texto/limpiar_fondo_azul.jpg" border="0" onclick="limpiar_representante()" style="border:none" title="Limpiar datos del representante"></a>
					</td>
					<td align="center" >
					<table>
						<tr>
							<td><a href="#"><img src="../imagenes/botones-texto/restaurar1.jpg" border="0" onclick="cargar_datos_representante()" style="border:none" title="Cargar datos del representante"></a></td>
						</tr>
					</table>
					
					</td>
					
				</tr>
				</table>
				<script>documentacion('nacionalidad','tipo_doc','dniR');</script>
				<br>
			  </span>
			 
			</li>
			<li>
			 
			  <b class="b1"></b>
			  <b class="b2"></b>
			  <b class="b3"></b>
			  <b class="b4"></b>
			  <a href="#" onclick="abrir(3)"><b class="h2"><font color="#064C87" face="verdana" size="3"><b>Estancia</b></font></b><br /></a>
			  
			  <span id="lis3" style="display:none"><br>
			<table border="0" align="center" >
			   <tr>
					<td class='label_formulario' colspan="2"  >Fecha Llegada:</td>
					<td ><select name="diaL" id="diaL" class="input_formulario" disabled><?
					
					
				
					?>
					<option value='<? echo $diaL; ?>' ><? echo $diaL; ?></option>
					<?PHP
					
					?>
					</select></td>
					<td align="center"><?
					$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
					//echo $mesL;
					?><select name="mesL" id="mesL" class="input_formulario" disabled><?
					
					$i=$mesL;
					
					?>
					<option value='<?echo $i?>'  ><?echo $meses[$i-1]?></option>
					<?PHP 
					?>	
					</select></td>
					<td><select name="annoL" id="annoL" class="input_formulario" disabled><?
					
					$i=$annoL;
					?>
					<option value='<?echo $i?>' ><?echo $i?></option>
					<?PHP
					
					?></select>
					
					</td>
				</tr>
				<?PHP
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
			
				if ($servicios_activados){//si los servicios estan activados
					$sql_tarifas="select * from tipo_servicios";
					$servicio_defecto = servicio_defecto($db2);
					?>
					<tr class='label_formulario'><td colspan="2">Tipo de Servicio:</td><td colspan="4">
					<select name="servicio"  id="servicio" class="input_formulario">
					<?php
					$combo_ta=mysql_query($sql_tarifas);
					while ($row_ta=mysql_fetch_array($combo_ta)) {
					if($row_ta['Id_Servicios']==$servicio){
					?>
					<option value='<? echo $row_ta['Id_Servicios'];?>' selected><? echo $row_ta['Descripcion']; ?></option>
					<?php
					}else if($row_ta['Id_Servicios']==$servicio_defecto){
					?>
					<option value='<? echo $row_ta['Id_Servicios'];?>' selected><? echo $row_ta['Descripcion']; ?></option>
					<?php
					}else{
					?>
					<option value='<? echo $row_ta['Id_Servicios'];?>' ><? echo $row_ta['Descripcion']; ?></option>
					<?php
					}
					}
					?>
					</select>
					</td>
					</tr>
					<?php
					
					}
				
				?>
				<tr>
					<td class='label_formulario' colspan="2">Ingreso:</td><td colspan="3"><input type="text" name="ingreso" size="5" class="input_formulario" value="<? echo $ingreso; ?>"></td>
				</tr>
				<tr>
				
				<td class='label_formulario' colspan="2">N� Noches:</td><td>

				<? 
				if($nnoches == ""){ ?>
					<input type="text" name="nnoches" id="nnoches" size="3" value="1" maxlength="3" class="input_formulario" onKeyUp="cambiar_noches()" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<? }else{ ?>
					<input type="text" name="nnoches" id="nnoches" size="3" value="<? echo $nnoches; ?>" maxlength="3" class="input_formulario" onKeyUp="cambiar_noches()" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<? } ?>
					</td>
					<td class='label_formulario'> Hora Llegada:</td><td><input type="text" name="hllegada" size="5" maxlength="100" class="input_formulario" value="<? echo $hllegada; ?>"></td>
				</tr>
				
					
				
				
				<tr>
					<td colspan="5" align="center">
						<table border="0">
						<tr align="center">
							<td></td>
							<td class='label_formulario'>H</td>
							<td class='label_formulario'>M</td>
							<td width="13px"></td>
							<td></td>
							<td class='label_formulario'>H</td>
							<td class='label_formulario'>M</td>
						</tr>
						<tr align="center">
							<td class='label_formulario'>&lt;10</td>
							<td><?PHP
					if($h_menos10 == ""){
					?>
						<input type="text" id="h_menos10" name="h_menos10" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_menos10" name="h_menos10" size="2" value="<? echo $h_menos10; ?>" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><? if($m_menos10 == ""){
					?>
						<input type="text" id="m_menos10" name="m_menos10" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_menos10" name="m_menos10" size="2" value="<? echo $m_menos10; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
					<td width="13px"></td>
							<td class='label_formulario'>10_19</td>
							<td><?PHP
					if($h_entre10_19 == ""){
					?>
						<input type="text" id="h_entre10_19" name="h_entre10_19" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_entre10_19" name="h_entre10_19" size="2" value="<? echo $h_entre10_19; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_entre10_19 == ""){
					?>
						<input type="text" id="m_entre10_19" name="m_entre10_19" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_entre10_19" name="m_entre10_19" size="2" value="<? echo $m_entre10_19; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
						</tr>
						<tr align="center">
							<td class='label_formulario'>20_25</td>
							<td><?PHP
					if($h_entre20_25 == ""){
					?>
						<input type="text" id="h_entre20_25" name="h_entre20_25" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_entre20_25" name="h_entre20_25" size="2" value="<? echo $h_entre20_25; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_entre20_25 == ""){
					?>
						<input type="text" id="m_entre20_25" name="m_entre20_25" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_entre20_25" name="m_entre20_25" size="2" value="<? echo $m_entre20_25; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
					<td width="13px"></td>
							<td class='label_formulario'>26_29</td>
							<td><?PHP
					if($h_entre26_29 == ""){
					?>
						<input type="text" id="h_entre26_29" name="h_entre26_29" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_entre26_29" name="h_entre26_29" size="2" value="<? echo $h_entre26_29; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_entre26_29 == ""){
					?>
						<input type="text" id="m_entre26_29" name="m_entre26_29" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_entre26_29" name="m_entre26_29" size="2" value="<? echo $m_entre26_29; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
						</tr>
						<tr align="center">
							<td class='label_formulario'>30_39</td>
							<td><?PHP
					if($h_entre30_39 == ""){
					?>
						<input type="text" id="h_entre30_39" name="h_entre30_39" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_entre30_39" name="h_entre30_39" size="2" value="<? echo $h_entre30_39; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_entre30_39 == ""){
					?>
						<input type="text" id="m_entre30_39" name="m_entre30_39" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_entre30_39" name="m_entre30_39" size="2" value="<? echo $m_entre30_39; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
					<td width="13px"></td>
							<td class='label_formulario'>40_49</td>
							<td><?PHP
					if($h_entre40_49 == ""){
					?>
						<input type="text" id="h_entre40_49" name="h_entre40_49" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_entre40_49" name="h_entre40_49" size="2" value="<? echo $h_entre40_49; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_entre40_49 == ""){
					?>
						<input type="text" id="m_entre40_49" name="m_entre40_49" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_entre40_49" name="m_entre40_49" size="2" value="<? echo $m_entre40_49; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
						</tr>
						<tr align="center">
							<td class='label_formulario'>50_59</td>
							<td><?PHP
					if($h_entre50_59 == ""){
					?>
						<input type="text" id="h_entre50_59" name="h_entre50_59" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_entre50_59" name="h_entre50_59" size="2" value="<? echo $h_entre50_59; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_entre50_59 == ""){
					?>
						<input type="text" id="m_entre50_59" name="m_entre50_59" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_entre50_59" name="m_entre50_59" size="2" value="<? echo $m_entre50_59; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
					<td width="13px"></td>
							<td class='label_formulario'>60_69</td>
							<td><?PHP
					if($h_entre60_69 == ""){
					?>
						<input type="text" id="h_entre60_69" name="h_entre60_69" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_entre60_69" name="h_entre60_69" size="2" value="<? echo $h_entre60_69; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_entre60_69 == ""){
					?>
						<input type="text" id="m_entre60_69" name="m_entre60_69" size="2" value="0" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_entre60_69" name="m_entre60_69" size="2" value="<? echo $m_entre60_69; ?>" onKeyUp="cargar_cuadros_nuevos()" maxlength="3" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
						</tr>
						<tr align="center">
							<td class='label_formulario'>&gt;69</td>
							<td><?PHP
					if($h_mas69 == ""){
					?>
						<input type="text" id="h_mas69" name="h_mas69" size="2" value="0" maxlength="3" class="input_formulario"  onKeyUp="cargar_cuadros_nuevos()" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="h_mas69" name="h_mas69" size="2" value="<? echo $h_mas69; ?>" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td><?PHP
					if($m_mas69 == ""){
					?>
						<input type="text" id="m_mas69" name="m_mas69" size="2" value="0" maxlength="3" class="input_formulario" onKeyUp="cargar_cuadros_nuevos()" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" >
					<?PHP }else{ ?>
					 <input type="text" id="m_mas69" name="m_mas69" size="2" value="<? echo $m_mas69; ?>" maxlength="3" onKeyUp="cargar_cuadros_nuevos()" class="input_formulario"  onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
					<?PHP } ?></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</table>
				<input type="hidden" name="mujeres" id="mujeres" value="<? echo $mujeres; ?>"  >
				<input type="hidden" name="hombres" id="hombres"  value="<? echo $hombres; ?>"  >
				<input type="hidden" id="hmenos10" name="menos10" value="<? echo $menos10; ?>" >
				<input type="hidden" id="entre10_19" name="entre10_19" value="<? echo $entre10_19; ?>">
				<input type="hidden" id="entre20_25" name="entre20_25" value="<? echo $entre20_25; ?>">
				<input type="hidden" id="entre26_29" name="entre26_29" value="<? echo $entre26_29; ?>">
				<input type="hidden" id="entre30_39" name="entre30_39" value="<? echo $entre30_39; ?>">
				<input type="hidden" id="entre40_49" name="entre40_49" value="<? echo $entre40_49; ?>">
				<input type="hidden" id="entre50_59" name="entre50_59" value="<? echo $entre50_59; ?>" >
				<input type="hidden"  id="entre60_69" name="entre60_69" value="<? echo $entre60_69; ?>">
				<input type="hidden" id="mas69" name="mas69" value="<? echo $mas69; ?>" >
					</td>
					
				</tr>
				<tr>
				<td width="107"></td>
					<td   colspan="4">
					
						<a href="#"><img src="../imagenes/botones-texto/aceptar_fondo_azul.jpg" border="0" onclick="insertar()" style="border:none" title="Validar la reserva"></a>
					
					</td>
					
										
				</tr>
				
				</table><br>
			  
			   
			  </span>	
 </li>
			 <li>
			 
			  <b class="b1"></b>
			  <b class="b2"></b>
			  <b class="b3"></b>
			  <b class="b4"></b>
			  <a href="#" onclick="abrir(4)"><b class="h2"><font color="#064C87" face="verdana" size="3"><b>Componentes</b></font></b><br /></a>
			  
			  <span id="lis4" style="display:none"><br>
			  <?
			  //miro si estan insertados todos los componentes del grupo si es asi muestro un mensaje
			 if(isset($_GET['nombre']))
			 	$nombre_grupo=$_GET['nombre'];
			if(isset($_GET['fecha']))
			 	$fecha_llegada=$_GET['fecha'];	
			  $sql_componentes_grupo=mysql_query("select Count(*) AS total  from componentes_grupo where Nombre_Gr='".$nombre_grupo."' and Fecha_Llegada='".$fecha_llegada."'");//cuento los componentes q ya se han insertado del grupo

							$row_componentes= mysql_fetch_array($sql_componentes_grupo);
							if($row_componentes['total']==$mujeres+$hombres){ ?>
							<br>
							<br>
							<br>
							<br>
							<table border="0" width="100%">
								<tr>
									<td width="15%"></td>
									<td>
									<font color="#064C87" face="verdana" size="2">Todos los componentes del grupo ya est�n dados de alta</font>
									</td>
									<td width="15%"></td>
								</tr>
							</table>
						<?	}else{
			  ?>
			 <table border="0" align="center">
			   
			   <tr>
					<td class='label_formulario'>Tipo:
					
					<select name="tipo_doc_componente"  id="tipo_doc_componente" class='select_formulario' onChange="documentacion('nacionalidad_componente','tipo_doc_componente','dni_componente');" title="tipo documentacion">
					<option value="D" title="DNI" selected>D</option>						
						<option value="P" title="Pasaporte" >P</option>
						<option value="C" title="Permiso de Conducir">C
						<option value="I" title="Carta o Documento de Identidad">I
						<option value="N" title="Permiso de residencia Espa�ol">N
						<option value="X" title="Permiso de residencia UE">X
					</select></td>
					<td class='label_formulario'>
					
						D.N.I.:				<input type="text" name="dni_componente" id="dni_componente" maxlength="30" class="input_formulario" value="" onBlur="this.value=this.value.toUpperCase()">
					
					</td>
					<td><a href="#"><img src="../imagenes/botones\lupa.png" alt="Buscar Componente" onclick="ver_pop_componentes('<? echo $nombre_grupo; ?>','<? echo $fecha_llegada;?>')" style="border:none;width:25px;height:25px;"></a></td>
				</tr>
					<tr>
					<td class='label_formulario'>F.Expedici�n:</td><td colspan=2 class='label_formulario'>
					<table border="0" width="100%"><tr><td>
					<select name='diaEx_componente' id='diaEx_componente' class='input_formulario'>
					<?PHP 
					
						for ($i=0;$i<=31;$i++) {
							if($i==0){?>
					<option value='' ></option>
					<?PHP
							
							}else{
							?>
							
					<option value='<?echo $i?>' ><?echo $i?></option>
					<?PHP
						}	
					}
					?>
					</select>
					</td><td>
					<select name='mesEx_componente' id='mesEx_componente' class='input_formulario' onchange="asignaDias('diaEx_componente','mesEx_componente','annoEx_componente')">
					<?PHP 
					$meses = array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
					
					$meses = array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
							
			
					for ($i=0;$i<=12;$i++) {
							?>
					<option value='<?echo $i?>' ><?echo $meses[$i]?></option>
					<?PHP
							
					}
					?>	
					</select>
					</td><td>
					<select name="annoEx_componente" id="annoEx_componente" class="input_formulario">
						<option value='' selected ></option>
					<?
						for ($i=date("Y");$i>=1900;$i--) {
							?>
					<option value='<?echo $i?>' ><?echo $i?></option>
					<?PHP
							}
					?></select>
					</td></tr></table>
					</td>
				</tr>
				<tr>
				
					<td  class='label_formulario'>
					
					
					  Nacionalidad: 
					</td>
					<td>
					
					<select name='nacionalidad_componente' id='nacionalidad_componente' class='input_formulario'>
				
					<option value='ES' selected>Espa�a</option>
					<?PHP 
					
					 
					$result_paises = mysql_query("select * from pais ORDER BY Nombre_Pais ");
							while($fila_paises = mysql_fetch_array($result_paises)){
								echo "<option value=".$fila_paises['Id_Pais']." alt=".$fila_paises['Nombre_Pais']." title=".$fila_paises['Nombre_Pais'];
								
									if( $fila_paises['Id_Pais'] == "ES"){
										echo " selected";	
									}
								
								echo ">";
								if(strlen($fila_paises['Nombre_Pais'])<25){	
									echo $fila_paises['Nombre_Pais'];
								}else{
									echo substr($fila_paises['Nombre_Pais'],0,22)."...";
								}
							}
					?>
				  </select>
				  
				  </td>
				  
				</tr>
				<tr>
					<td class='label_formulario'>F.Nacimiento:</td><td colspan=2 class='label_formulario'>
					<table border="0" width="100%"><tr><td>
					<select name="diaNa_componente" id="diaNa_componente" class="input_formulario">
					<?PHP 
					$hayscript=true;
					for ($i=0;$i<=31;$i++) {
							if($i==0){?>
					<option value='0' ></option>
					<?PHP
							
							}else{
							?>
							
					<option value='<?echo $i?>' ><?echo $i?></option>
					<?PHP
						}	
					}
					?>
					</select>
					</td><td>
					<select name="mesNa_componente" id="mesNa_componente" class="input_formulario" onchange="asignaDias('diaNa_componente','mesNa_componente','annoNa_componente')">
					<?PHP 
					$meses = array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
					
					for ($i=0;$i<=12;$i++) {
							?>
					<option value='<?echo $i?>' ><?echo $meses[$i]?></option>
					<?PHP
							
					}
					?>
						
					</select>
					</td><td>
					<select name="annoNa_componente" id="annoNa_componente" class="input_formulario" onchange="asignaDias('diaNa_componente','mesNa_componente','annoNa_componente')"><?PHP
					?><option value=''></option><?
						for ($i=date("Y");$i>=1900;$i--) {
							?>
					<option value='<?echo $i?>' ><?echo $i?></option>
					<?PHP
							}
					?></select>
					</td></tr></table>
					</td>
				</tr>
			
				
				<tr>
					<td class='label_formulario' >Nombre:</td><td>
					<?
					$fecha_llegada1 = $_POST['fecha_llegada'];
					if($_POST['fecha_llegada'] == ""){
						$fecha_llegada1 = $_POST['fecha_llegada1'];
					}
					?>
					<input type="hidden" name="fecha_llegada1" id="fecha_llegada1" value="<?=$fecha_llegada1?>">
					<input type="text" name="nombre_componente" id="nombre_componente" maxlength="20" class="input_formulario" onBlur="this.value=this.value.toUpperCase()"></td><td></td>
				</tr>
				<tr>
					<td class='label_formulario' >Apellido 1:</td><td>
					
					<input type="text" name="ape1_componente" id="ape1_componente" maxlength="30" class="input_formulario" value="" onBlur="this.value=this.value.toUpperCase()"></td><td></td>
				</tr>
				<tr>
					<td class='label_formulario' >Apellido 2:</td><td>
					
					<input type="text" name="ape2_componente" id="ape2_componente" maxlength="30" class="input_formulario" value="" onBlur="this.value=this.value.toUpperCase()"></td><td></td>
				</tr>
				<tr>
					<td class='label_formulario' colspan="3" align="center">
					<input type="radio" name="sexo"  id="sexo" value="F">Mujer
					<input type="radio" name="sexo" id="sexo" value="M">Hombre
					</td>

				</tr>
				<tr>
					<td class='label_formulario' colspan="3" height="30px">
					
					</td>
				</tr><script>
				documentacion('nacionalidad_componente','tipo_doc_componente','dni_componente');
						function validadni_compo()//
		{
			var dni = document.ingresar_estancia.dni_componente.value;
		    var tipo_documento = document.ingresar_estancia.tipo_doc_componente.value;
		    var indice_naci = document.ingresar_estancia.nacionalidad_componente.selectedIndex;
							var pais = document.ingresar_estancia.nacionalidad_componente[indice_naci].value;
		   	// Obtenemos los paises que tenga Carta Europea de la base de datos.
							
   	// Obtenemos los paises que tenga Carta Europea de la base de datos.
								 
			<?			 
			  $res = mysql_query("SELECT * FROM `pais` WHERE Carta_Europea='S'");
			  // Array con los paises con es v�lido el tipo de documento <<I>>.
			  echo "var PaisesPermitidos = new Array(".mysql_num_rows($res).");";
			  for ($i=0;$i<mysql_num_rows($res);$i++)
			  {
			    $fila = mysql_fetch_array($res);	
				echo "\nPaisesPermitidos[".$i."] =\"".$fila['Id_Pais']."\";"; 		
				    
			  }
			?>	
		    
			//Comprueba que tenga una longitud entre 7 y 8 n�meros, y en caso de tener un car�cter(NIF) ser� una letra permitida. 
			//En caso de no cumplirse estas condiciones, se enviar� un mensaje y se colocar� el foco en el campo dni.
			if (dni=="") alert("Debe rellenar el campo DNI.");
			// En caso de tratarse del dni o del carnet de conducir...
			else if (  (tipo_documento == "D") || (tipo_documento == "C") )
			{
			   
				// Es solo v�lido para espa�oles.
				if (pais!='ES') 
				{
						alert("El pais no corresponde con el tipo de documento");					
						document.ingresar_estancia.nacionalidad_componente.focus();
						return false;
				}
				else 
				{	
				    // Tiene que tener una longitud de 7 n�meros como m�nimo.
				    if (dni.length < 7)
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.ingresar_estancia.dni_componente.focus();
						return false;
					}
				    // Comprobamos si los siete primeros son n�meros.
					else if(isNaN(dni.substring(0,7)) )
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.ingresar_estancia.dni_componente.focus();
						return false;
					}
				
					else
					{
					    // Si tiene solo siete n�meros, le a�adimos un cero y la letra.
						if(dni.length == 7)
						{						 	
							document.ingresar_estancia.dni_componente.value = "0"+dni+calcletra(dni);
						} 			 
						
						// Si es igual a ocho...
						else if (dni.length == 8)
						{
						    //alert(dni.substring(0,7?));
						    // El pen�ltimo tiene que ser un n�mero y el �ltimo  car�cter solo puede ser un n�mero o una letra permitida.
							if ( ( (isNaN(dni.substring(7,8))) && (calcletra(dni.substring(0,7))!=dni.substring(7,8)) ) || 
							     ( (isNaN(dni.substring(8,9))) && (calcletra(dni.substring(0,8))!=dni.substring(8,9)) )    
								) 
							{
								alert("Debe rellenar correctamente la letra de control del DNI.");	
								document.modificar.dni_componente.focus();
								return false;
							}			
							// Le a�adinos la letra si el �ltimo caracter es un n�mero
							if (!isNaN(dni.substring(7,8))) document.ingresar_estancia.dni_componente.value = dni+calcletra(dni);						 
						 }   
							// Si es igual a nueve..
						else if (dni.length == 9)
						{
						    
						 	// El pen�ltimo tiene que ser un n�mero y el �ltimo car�cter solo puede ser una letra permitida
							if ( (isNaN(dni.substring(7,8)) ) || (calcletra(dni.substring(0,8))!=dni.substring(8,9)) ) 
							{
								alert("Debe rellenar correctamente la letra de control del DNI.");					
								document.ingresar_estancia.dni_componente.focus();
								return false;
							}
									
						 }
						else if (dni.length > 9)
						{
							alert("Debe rellenar correctamente el campo DNI.");					
							document.ingresar_estancia.dni_componente.focus();
							return false;
						}
				      
				    }
				 }
			 } 
			 else if ( (tipo_documento == "N") && (pais == "ES") )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.ingresar_estancia.nacionalidad_componente.focus();
				return false;
			 } 
			
			 else if ( (tipo_documento == "N") && (pais != "ES") )
			 {
				// Tiene que tener una longitud de 8 n�meros como m�nimo y 10 como m�ximo.
				if ( (dni.length<7) || (dni.length>10) )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.ingresar_estancia.dni_componente.focus();
					return false;
				}
				
				// Comprobamos si el primer car�cte?r es un car�cter X o un n�mero.
				else if( (isNaN(dni.substring(0,1))) && (dni.substring(0,1)!="X") )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.ingresar_estancia.dni_componente.focus();
					return false;
				}
				// Si el primer elemento no tiene X se la a�adimos
			    if (!isNaN(dni.substring(0,1))) 
				{
					dni = "X"+dni;
					document.ingresar_estancia.dni_componente.value = dni;				
				}
			
			    // Si solo tiene 7 elemenos n�mericos, le a�adimos un cero.
				if ( (!isNaN(dni.substring(1,8))) && ( (dni.length == 8) || ((dni.length == 9) && (isNaN(dni.substring(8,9)))) ) )
				{				    
				    
					dni = "X0"+dni.substring(1,9);
					document.ingresar_estancia.dni_componente.value = dni;
				
				}
				//alert (dni.substring(1,9));
				// Le a�adinos la letra si el �ltimo caracter es un n�mero
				if (dni.length!=10) document.ingresar_estancia.dni_componente.value = dni+calcletra(dni.substring(1,9));	
				
				else if ( (dni.length==10) && ( (dni.substring(0,1)!="X") || (isNaN(dni.substring(1,9))) || 
											    ((dni.substring(9,10)) != calcletra(dni.substring(1,9)))    ) )
				{
					alert("Debe rellenar correctamente la letra de control del DNI.");					
					document.ingresar_estancia.dni_componente.focus();
					return false;
				}				
				
			 } 
			 else if ( (tipo_documento == "I") && (!ExisteElemento(PaisesPermitidos, pais) ) )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.ingresar_estancia.nacionalidad_componente.focus();
				return false;
			 }
			else if  ( (tipo_documento == "P") && (pais == "ES") && (dni.length>11) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					
				document.ingresar_estancia.dni_componente.focus();
				return false;
			}
			else if  ( (tipo_documento == "P") && (pais != "ES") && (dni.length>14) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					

				document.ingresar_estancia.dni_componente.focus();
				return false;
			}
			return true;
}
				function comprobar_componente(){
   
					document.getElementById("insertar_persona").value="si";
					 
					 var estado2 = comprobar_componentes_datos();
					
					if(estado2){
					document.getElementById("ingresar_estancia").submit();
					}
					}	
		function comprobar_componentes_datos(){
				estado = true;
				
					  
						if ((document.ingresar_estancia.nombre_componente.value == "") || (document.ingresar_estancia.dni_componente.value == "") || (document.ingresar_estancia.ape1_componente.value == "")||(annoNa_componente.getComboText()=="")||(annoEx_componente.getComboText()=="")||(mesEx_componente.getComboText()=="")||(diaEx_componente.getComboText()=="") ){
							alert("Debes rellenar todos los campos");
							estado = false;}
							else if((mesNa_componente.getComboText()=="")||(diaNa_componente.getComboText()=="")){
							if(document.ingresar_estancia.nacionalidad_componente[document.ingresar_estancia.nacionalidad_componente.selectedIndex].value!="ES"){
								estado= false;
								alert("Debes rellenar todos los campos");
							}
						}
						if(estado){	
							var meses = new Array ("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		var mesex;
				for(var i=0;i<12;i++){
				if(mesEx_componente.getComboText() == meses[i]){
			  	if(i+1<10){
				    mesex="0"+(i);
				}else{
				mesex=i;
				}
				break;
				}
				}
				
				var mesna;
				for(var i=0;i<12;i++){
				if(mesNa_componente.getComboText() == meses[i]){
			  	if(i+1<10){
				    mesna="0"+(i);
				}else{
				mesna=i;
				}
				break;
				}
				}
				
				var diana=diaNa_componente.getComboText();
				if(diana=="")
					diana=1;
					if(mesna==00)
						mesna=1;
							
							var cadena=diaEx_componente.getComboText()+'-'+mesex+'-'+annoEx_componente.getComboText();
							if(estado==true)
								estado=validar_fecha(cadena,"Debe de rellenar una fecha de expedici�n v�lida");
							
							var cadena=diana+'-'+mesna+'-'+annoNa_componente.getComboText();
							if(estado==true)
								estado=validar_fecha(cadena,"Debe de rellenar una fecha de nacimiento v�lida");
							
							ahora2 = new Date();
				ahoraDay2 = ahora2.getDate();
						ahoraMonth2 = ahora2.getMonth();
						ahoraYear2 = ahora2.getYear();
						fecha_hoy = new Date(ahoraYear2,ahoraMonth2,ahoraDay2);
					
							fecha_naci= new Date(annoNa_componente.getComboText(),mesna-1,diana);
						fecha_expe= new Date(annoEx_componente.getComboText(),mesex-1,diaEx_componente.getComboText());
						if(fecha_expe<fecha_naci){
							alert("La fecha de expedici�n no puede ser menor que la fecha de nacimiento");
							estado = false;
						}else if(fecha_hoy<fecha_naci){
							alert("La fecha de nacimiento no puede ser mayor que la fecha de hoy");
							estado = false;
						}else if(fecha_hoy<fecha_expe){
							alert("La fecha de expedici�n no puede ser mayor que la fecha de hoy");
							estado = false;
						}
					var indice_naci = document.ingresar_estancia.nacionalidad_componente.selectedIndex; 
							var nacionalidad = document.ingresar_estancia.nacionalidad_componente[indice_naci].value;
							var indice_doc = document.ingresar_estancia.tipo_doc_componente.selectedIndex;
							
							
								
							
						if(document.ingresar_estancia.sexo[1].checked == false && document.ingresar_estancia.sexo[0].checked ==false){
				alert("Debe seleccionar una casilla de sexo.");
				estado = false;
			}
								
					if(estado==true)
							estado=validadni_compo();
						
						
						}	
							return estado;
	
	
	}
					
				</script>
				
				<?PHP
				
		$total=$mujeres+$hombres;
		 if(isset($_GET['nombre']))
			 	$nombre_grupo=$_GET['nombre'];
			if(isset($_GET['fecha']))
			 	$fecha_llegada=$_GET['fecha'];
			if($fecha_llegada == ""){
				$fecha_llegada = $_POST['fecha_llegada1'];
			}
							$sql_componentes_grupo=mysql_query("select Count(*) AS total  from componentes_grupo where Nombre_Gr='".$nombre_grupo."' and Fecha_Llegada='".$fecha_llegada."'");
							$row_componentes= mysql_fetch_array($sql_componentes_grupo);
			if($_POST['insertar_persona']=="si"){
						
							$dni_comp=$_POST['dni_componente'];
							
							$total=$mujeres+$hombres;
							
							
							$sql_componentes_grupo=mysql_query("select Count(*) AS total  from componentes_grupo where Nombre_Gr='".$nombre_grupo."' and Fecha_Llegada='".$fecha_llegada."'");
							$row_componentes= mysql_fetch_array($sql_componentes_grupo);
								$sql_componentes_grupo_compro=mysql_query("select * from componentes_grupo where Nombre_Gr='".$nombre_grupo."' and Fecha_Llegada='".$fecha_llegada."' and dni='".$dni_comp."'");//cuento los componentes q ya se han insertado del grupo
							
							if($row_componentes['total']>=$total){
							 echo "<script>alert('Ya ha ingresado todos los componentes del grupo');</script>";
							 echo "<script>location.href='?pag=grupos_reserva.php&listado_componentes=si&nombre=$nombre_grupo&fecha=$fecha_llegada'</script>";
							 
							}else   if(mysql_num_rows($sql_componentes_grupo_compro)!=0){
									echo "<script>alert('Ese mienbro del grupo ya esta ingresado');</script>";
							 
							}else{
								$sql_comprobar_componente=mysql_query("select Count(*) AS total  from componentes_grupo where DNI ='".$dni_comp."' ");
							$row_comprobar_componente= mysql_fetch_array($sql_comprobar_componente);
									if($row_comprobar_componente['total']==1){
												$sql_componente="UPDATE componentes_grupo SET Nombre_Gr='".$nombre_grupo."',Fecha_Llegada='". $fecha_llegada."' ,Tipo_documentacion='".$_POST['tipo_doc_componente']."',Fecha_Expedicion='".$_POST['annoEx_componente']."-".$_POST['mesEx_componente']."-".$_POST['diaEx_componente']."' ,Nombre='".$_POST['nombre_componente']."' ,Apellido1='".$_POST['ape1_componente']."' ,Apellido2='".$_POST['ape1_componente']."' ,Sexo='".$_POST['sexo']."' ,Fecha_nacimiento='".$_POST['annoNa_componente']."-".$_POST['mesNa_componente']."-".$_POST['diaNa_componente']."' ,Id_Pais_nacionalidad='".$_POST['nacionalidad_componente']."' WHERE DNI = '".$dni_comp."'";
								
									}else{
										$sql_componente="INSERT INTO componentes_grupo (DNI,Nombre_Gr,Fecha_Llegada ,Tipo_documentacion,Fecha_Expedicion ,Nombre ,Apellido1 ,Apellido2 ,Sexo ,Fecha_nacimiento ,Id_Pais_nacionalidad ) VALUES ('". trim($dni_comp) ."','".$nombre_grupo."','". $fecha_llegada."','".$_POST['tipo_doc_componente']."','".$_POST['annoEx_componente']."-".$_POST['mesEx_componente']."-".$_POST['diaEx_componente']."','".$_POST['nombre_componente']."','".$_POST['ape1_componente']."','".$_POST['ape2_componente']."','".$_POST['sexo']."','".$_POST['annoNa_componente']."-".$_POST['mesNa_componente']."-".$_POST['diaNa_componente']."','".$_POST['nacionalidad_componente']."')";
									}
										
								// inserto el componente
									mysql_query($sql_componente);
									$sql_componentes_grupo_mujer=mysql_query("select Count(*) AS total2  from componentes_grupo where Nombre_Gr='".$nombre_grupo."' and Fecha_Llegada='".$fecha_llegada."' and Sexo='F'");//cuento los componentes q ya se han insertado del grupo
							$sql_componentes_grupo_hombre=mysql_query("select Count(*) AS total3  from componentes_grupo where Nombre_Gr='".$nombre_grupo."' and Fecha_Llegada='".$fecha_llegada."' and Sexo='M'");//cuento los componentes q ya se han insertado del grupo

								$row_componentes_mujer= mysql_fetch_array($sql_componentes_grupo_mujer);
							$row_componentes_hombre= mysql_fetch_array($sql_componentes_grupo_hombre);
							$mostrar="no";
									 if($row_componentes_mujer['total2']>$mujeres&&$_POST['sexo']=="F"){
									 $mostrar="si";
							}else if($row_componentes_hombre['total3']>$hombres&&$_POST['sexo']=="M"){
							$mostrar="si";
							}
							if($mostrar=="si"){
									echo "<script>alert('El n�mero de hombres y mujeres de la estancia no coinciden con el de componentes');</script>";
								$mostrar="no";
							
							}
									 ?>
									<script>//document.getElementById("ingresar_estancia").submit();</script>
									
							<?
							}
						$dni_comp=$_POST['dni_componente'];
						
					
			}	
			

 
				
				
				
				?>
				
					
				<tr><td></td>
					<td class='label_formulario'  align="center" >

					<input type="hidden" name="insertar_persona" id="insertar_persona" >

					<a href="#"><img src="../imagenes/botones-texto/nuevo_fondo_azul.jpg" border="0" onclick="comprobar_componente()" style="border:none" alt="Dar de alta un componente"></a>
					
					</td>
					
					<td></td>
				</tr>
				
				</table>
				<? } ?>
			 <br>
			  
			   
			  </span><br><br>	
			  </li>
		   </ul>
		  
		  </div>

		 <b class="b4 c4"></b>
		 <b class="b3 c3"></b>
		 <b class="b2 c2"></b>
		 <b class="b1 c1"></b>
			
		
	
		</div> 
		<?PHP 
		?>
		</td>
		 <td valign="top">
		 <script>
						
						
						</script>
			<?php
			if($listado_componentes=="si"){
			//$listado_componentes="no";
						?>
						<script>
						
						document.getElementById('lis4').style.display = "block";
						document.getElementById('lis2').style.display = "none";
						document.getElementById('lis1').style.display = "none";
						document.getElementById('lis3').style.display = "none";
						</script>
						<table border="0"  align="center" width="98%" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	 <thead id="titulo_tablas" >
            <td align="center" style="padding:0px 0px 0px 0px;"> <div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:640px;'>
              <div class="titulo" style="width:95.5%;text-align:center;">Listado de Componentes</div>
              </div>
              <div class='champi_derecha'>&nbsp;</div></td>
              </thead>
			<tbody class='tabla_detalles'><tr>
              <td align="left" style="padding:0px 0px 0px 0px;">
			  	<div class="tableContainer" style="height:545px;" id="tabla_listado">
               <table border="0" class='tabla_detalles' cellpadding="0" cellspacing="0" width="97%" >
                <thead class="fixedHeader">
	
	
            <tr align="center"> 
			<th style="padding:0px 0px 0px 0px;"><a href="?pag=grupos_reserva.php&orden2=DNI&listado_componentes=si&nombre=<?echo $nombre_grupo?>&fecha=<?echo $fecha_llegada?>" style="text-decoration:none"><font color="white">D.N.I.</font></a></th>
											<th><a href="?pag=grupos_reserva.php&orden2=Nombre&listado_componentes=si&nombre=<?echo $nombre_grupo?>&fecha=<?echo $fecha_llegada?>" style="text-decoration:none"><font color="white">Nombre</font></a></th>
											<th><a href="?pag=grupos_reserva.php&orden2=Apellido1&listado_componentes=si&nombre=<?echo $nombre_grupo?>&fecha=<?echo $fecha_llegada?>" style="text-decoration:none"><font color="white">Apellidos</font></a></th>
											<th><a href="?pag=grupos_reserva.php&orden2=Id_Pais_nacionalidad&listado_componentes=si&nombre=<?echo $nombre_grupo?>&fecha=<?echo $fecha_llegada?>" style="text-decoration:none"><font color="white">Pa�s</font></a></th>
											<th><a href="?pag=grupos_reserva.php&orden2=Sexo&listado_componentes=si&nombre=<?echo $nombre_grupo?>&fecha=<?echo $fecha_llegada?>" style="text-decoration:none"><font color="white">Sexo</font></a></th>
										
											
											<!--<th>�ltima<br>Estancia</th>-->
											
											
            </tr>
          </thead>
         <tbody class="scrollContent"><?
		  //recojo el criterio por el que quiero ordenar el listado y creo la session que me controla si es asc o desc
	if($_GET['orden2']=="DNI"||$_GET['orden2']=="Nombre"||$_GET['orden2']=="Id_Pais_nacionalidad"||$_GET['orden2']=="Sexo"){
	 	if(!$_SESSION['orden2']){$_SESSION['orden2']=1;}
		if($_SESSION['orden2']==1){
		$ordengrupo=" order by ".$_GET['orden2']." DESC";
		$_SESSION['orden2']=2;
		}else{
		$ordengrupo=" order by ".$_GET['orden2']." ASC";
		$_SESSION['orden2']=1;
	}
	
	}
	 if(isset($_GET['nombre']))
			 	$nombre_grupo=$_GET['nombre'];
			if(isset($_GET['fecha']))
			 	$fecha_llegada=$_GET['fecha'];
			else
				$fecha_llegada = $_POST['fecha_llegada'];
			if($fecha_llegada == "")
				$fecha_llegada = $_POST['fecha_llegada1'];
			$result_listado=mysql_query("select * from  componentes_grupo where Nombre_Gr ='".	$nombre_grupo."' and Fecha_Llegada ='".	$fecha_llegada."' ".$ordengrupo);
						?><script> if(document.ingresar_estancia.componente_representante.checked==true){
									<?PHP
									$c=0;
										if((mysql_num_rows($result_listado)==0) && ($c==0) ){
											?>
											//document.ingresar_estancia.componente_representante.checked=false;
											//document.ingresar_estancia.submit();
											<? $c=$c+1;
										}
										
										?>
										}</script>
										<?
										while ($row_listado=mysql_fetch_array($result_listado)) {
											$result2=mysql_query("select * from Pais WHERE Id_Pais='".$row_listado["Id_Pais_nacionalidad"]."'");
											$row2=mysql_fetch_array($result2);
											
											
											?><tr class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">
											
											<td width="100px">
											<? 
													$dni_li = $row_listado["DNI"];
													$dni_partido_li = parteCadenas($dni_li, 14);
															echo $dni_partido_li;
											 ?>
										
											</td>
											<td>
											<? 
													$nombre_li = $row_listado["Nombre"];
													$nombre_partido_li = parteCadenas($nombre_li, 15);
															echo $nombre_partido_li;
											 ?>
											
											 
											</td>
											<td><?
											$ape_li = $row_listado["Apellido1"]." ".$row_listado["Apellido2"];
													$ape_partido_li = parteCadenas($ape_li, 20);
															echo $ape_partido_li;
															?>
											
											 
											</td>
											<td><? 
													$pa_li = $row2["Nombre_Pais"];
													$pa_partido_li = parteCadenas($pa_li, 20);
															echo $pa_partido_li;
											 ?></td>
											 <td><? 
													if($row_listado["Sexo"]=="M")
														echo "Hombre";
													else if($row_listado["Sexo"]=="F")
														echo "Mujer";
															
											 ?></td>
											
											<!--<td>FECHA ESTANCIA</td>-->
											
											
										</tr>		
										<?
										}
									?>

										
								</tbody>
        </table>
		<?PHP
		//echo $sqlcon;
		
		?>
							</div>							
						</td>
					</tr>
						</tbody>
				</table>
						<?php
						}else{
						?>
			<div id="caja_habitaciones" style="display:block;">
		
						
						<?php
						?>
						<script>
						/*document.getElementById('lis3').style.display = "block";
						document.getElementById('lis2').style.display = "none";
						document.getElementById('lis1').style.display = "none";
						document.getElementById('lis4').style.display = "none";*/
						</script>
						
						
        <?
			//creo todas la fechas que se usaran mas adelante para hacer comparaciones
			if($_POST['mov_cal'] == "1"){
				$fecha_dia = substr($_POST['fecha_ver'],0,2);
				$fecha_mes = substr($_POST['fecha_ver'],3,2);
				$fecha_anio = substr($_POST['fecha_ver'],6,4);
			}else{
				$fecha_dia = substr($fecha_llegada,8,2);
				$fecha_mes = substr($fecha_llegada,5,2);
				$fecha_anio = substr($fecha_llegada,0,4);
			}
			if($fecha_dia == ""){
				$fecha_dia = strftime("%d",time());
				$fecha_mes = strftime("%m",time());
				$fecha_anio = strftime("%Y",time());
			}
			
			$fecha_sel = mktime(0, 0, 0, $fecha_mes, $fecha_dia, $fecha_anio);
			$fecha_lle = mktime(0, 0, 0, substr($fecha_llegada,5,2), substr($fecha_llegada,8,2), substr($fecha_llegada,0,4));
			$fecha_sal = mktime(0, 0, 0, substr($fecha_llegada,5,2), substr($fecha_llegada,8,2)+$nnoches-1, substr($fecha_llegada,0,4));
			$fec_lle_val = strftime("%d/%m/%Y",$fecha_lle);
			$fec_sal_val = strftime("%d/%m/%Y",$fecha_sal);
			
		?>
      <td width="62%" valign="top"> 
		<input name="cambio_tipo" id="cambio_tipo" type="hidden" value="<?=$_POST['cambio_tipo']?>">
      <table border="0" align="center" width="680px" cellpadding="0" cellspacing="0" style="padding:0px 0px 0px 0px;">
        	<tr id="titulo_tablas" style="padding:0px 0px 0px 0px;">
            <td align="center" > <div class="champi_izquierda">&nbsp;</div>
              <div class="champi_centro"> 
						<div style="height:25px;text-align:center;background-repeat:repeat-x;float:left;">	
               				<div class="titulo" style="width:420px;text-align:center;">
				  				Distribuci�n de Habitaciones
							</div>
				  		</div>
				  		<div style="height:25px;text-align:center;background-repeat:repeat-x;float:left;">	
						  <div class="titulo" style="width:200px;text-align:center;">
							 <select name="num_pag" id="num_pag" class="detalles_select" onchange="cambiar_pagina_dis(num_pag.value);">
								<?PHP	//ponjo las ventanas de 'distribuci�n de habitaciones'
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
							<input type="hidden" id="dist_num_pag" name="dist_num_pag" value="<?=$pagina?>"> <!-- Cambia el n�mero de pagina en distribucion de habitaciones -->
							  <input name="mov_cal" id="mov_cal" value="0" type="hidden">
							  <input type="hidden" name="fecha_ver" id="fecha_ver" value="<?=$fecha_dia."/".$fecha_mes."/".$fecha_anio;?>">
							  <input type="hidden" name="fecha_cal" id="fecha_cal" value="<?=$fecha_dia."-".$fecha_mes."-".$fecha_anio;?>">
							<script>
								dia_selec = '<?=$fecha_dia?>' / 1;			//cambio las variables del calendario y llamo a la funci�n pasando el dia, mes y a�o seleccionado
								mes_selec = ('<?=$fecha_mes?>' / 1) - 1;
								anio_selec = '<?=$fecha_anio?>';
								calendario(dia_selec, mes_selec, anio_selec);
							</script>
						</div>
					</div>
              </div>
              <div class="champi_derecha">&nbsp;</div></td>
            </tr>				<tr>
		<!--  ********* Calendario de Distribucion de Habitaciones ****************-->
					<td align="center" style="padding:0px 0px 0px 0px;">
					<table class='tabla_detalles' width="100%" height="100%" style="border: 1px solid #3F7BCC;">
					<tr>
					<td align="center">
					<table border="0"  width="" class="tabla_habitaciones" bordercolor="#FFFFFF" rules="groups" cellpadding="0" cellspacing="1">

<?php

	//Se Crean y Ejecutan las consultas para mostrar el LISTADO
	$reservas_dia=array();
	$pn = $nnoches;
	$f_sel = strftime("%Y-%m-%d",$fecha_sel);
	$f_lle = strftime("%Y-%m-%d",$fecha_lle);
	$f_sal = strftime("%Y-%m-%d",$fecha_sal);

	$ini = 0;
	/*Compruebo si hay estancias de grupos entre la fecha de llegada y salida*/
		$qry_sel_res = "SELECT *, colores.Color as Color, estancia_gr.PerNocta as pn, pernocta_gr.num_personas as num_per, estancia_gr.fecha_llegada as fecha, estancia_gr.fecha_salida as fecha_s ";
	$qry_sel_res = $qry_sel_res . "FROM pernocta_gr INNER JOIN estancia_gr ON ";
	$qry_sel_res = $qry_sel_res . "(pernocta_gr.nombre_gr = estancia_gr.nombre_gr AND pernocta_gr.fecha_llegada = estancia_gr.fecha_llegada) INNER JOIN colores ON (estancia_gr.Id_Color = colores.Id_Color) ";
	
	$res_sel_res=mysql_query($qry_sel_res);
	//Se Almacenan los datos para el d�a seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha']!=$tupla_res['fecha_s']){
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $nnoches && $puesto == 0; $j++){
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=$tupla_res['num_per'];
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['id_color']=$tupla_res['Color'];	
					$reservas_dia[$ini]['gru_enc'] = -1;
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

	//Se Almacenan los datos para el d�a seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha']!=$tupla_res['fecha_s']){
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $nnoches && $puesto == 0; $j++){
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=1;
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['gru_enc'] = -1;
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
	
	//Se Almacenan los datos para el d�a seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res);$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha']!=$tupla_res['fecha_s']){
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $nnoches && $puesto == 0; $j++){
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=1;
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['gru_enc'] = -1;
					$reservas_dia[$ini]['reserva']='n';
					$ini++;
					$puesto = 1;
				}
			}
			}
	}		

	$ini = count($reservas_dia);
	/*Compruebo si hay reservas entre la fecha de llegada y salida*/
	$qry_sel_res = "SELECT *, detalles.dni_pra as dni, detalles.fecha_llegada as fecha, detalles.fecha_salida as fecha_s,reserva.Num_Camas as camas ";
	$qry_sel_res = $qry_sel_res . "FROM detalles INNER JOIN reserva ON ";
	$qry_sel_res = $qry_sel_res . "(detalles.dni_pra = reserva.dni_pra AND detalles.fecha_llegada = reserva.fecha_llegada) ";
	$qry_sel_res = $qry_sel_res . "WHERE id_hab != 'PRA'";
	$res_sel_res=mysql_query($qry_sel_res);

	$componentes1 = 0;
	//Se Almacenan los datos para el d�a seleccionado en el array RESERVAS_DIA
	for ($i=0;$i<mysql_num_rows($res_sel_res)+$con;$i++){
		$tupla_res=mysql_fetch_array($res_sel_res);
		if($tupla_res['fecha']!=$tupla_res['fecha_s']){
			$fecha_lle_t = mktime(0, 0, 0, substr($tupla_res['fecha'],5,2), substr($tupla_res['fecha'],8,2), substr($tupla_res['fecha'],0,4));
			$fecha_sal_t = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2), substr($tupla_res['fecha_s'],0,4));
			$puesto = 0;
			for($j = 0; $j < $nnoches && $puesto == 0; $j++){
				$fecha_sel_t = mktime(0, 0, 0, strftime("%m",$fecha_sel), strftime("%d",$fecha_sel)+$j, strftime("%Y",$fecha_sel));
				if($fecha_sel_t >= $fecha_lle_t && $fecha_sel_t < $fecha_sal_t){
					$reservas_dia[$ini]['hab']=$tupla_res['Id_Hab'];
					$reservas_dia[$ini]['camas']=$tupla_res['Num_Camas'];
					$reservas_dia[$ini]['fecha']=$tupla_res['fecha'];
					$reservas_dia[$ini]['fecha_s']=$tupla_res['fecha_s'];
					$reservas_dia[$ini]['reserva']='s';
					if($dni_pra == $tupla_res['dni'] && $fecha_llegada == $tupla_res['fecha']){
						$reservas_dia[$ini]['gru_enc'] = $tupla_res['camas'];
						$fecha_sal_t1 = mktime(0, 0, 0, substr($tupla_res['fecha_s'],5,2), substr($tupla_res['fecha_s'],8,2)+$nnoches-1, substr($tupla_res['fecha_s'],0,4));
						$reservas_dia[$ini]['fecha_s']=strftime("%Y-%m-%d",$fecha_sal_t1);
						$componentes1 += $tupla_res['camas'];
					}else if($dni_pra == $tupla_res['dni'] && in_array(strftime("%d/%m/%Y",$fecha_lle_t),$crear_fechas)){
						$reservas_dia_tem = array();
						for($r = 0; $r < count($reservas_dia)-1; $r++){
							$reservas_dia_tem[$r] = $reservas_dia[$r];
						}
						$reservas_dia = array();
						$reservas_dia = $reservas_dia_tem;
						$ini--;
					}else{
						$reservas_dia[$ini]['gru_enc'] = -1;
						$ini++;
					}
					$ini++;
					$puesto = 1;
				}
			}
		}
	}

	echo "<script>habit_sel = new Array();</script>";	//creo un array en javascript para saber las habitaciones y su tipo
	for ($i=0;$i<count($reservas_dia);$i++){
		$sql = "select cambio_tipo_habitacion.id_tipo_hab as id_tipo_hab, habitacion.camas_hab as camas_hab, tipo_habitacion.compartida as ";
		$sql = $sql . "compartida from habitacion inner join cambio_tipo_habitacion on (habitacion.id_hab = cambio_tipo_habitacion.id_hab) inner join ";
		$sql = $sql . "tipo_habitacion on (cambio_tipo_habitacion.id_tipo_hab = tipo_habitacion.id_tipo_hab) where ";
		$sql = $sql . "habitacion.id_hab = '" . $reservas_dia[$i]['hab'] . "' and cambio_tipo_habitacion.fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' "; 
		$sql = $sql . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $reservas_dia[$i]['hab'] . "' and cambio_tipo_habitacion.fecha<='" . strftime("%Y-%m-%d",$fecha_sel) . "')";
	
		$res=mysql_query($sql);
		$fila=mysql_fetch_array($res);
		echo "<script>";
			echo "habit_sel[" . $i . "] = new Array();";
			echo "habit_sel[" . $i . "][0] = '" . $reservas_dia[$i]['hab'] . "';";
			echo "habit_sel[" . $i . "][1] = '" . $fila['id_tipo_hab'] . "';";
			echo "habit_sel[" . $i . "][2] = '" . $fila['compartida'] . "';";
		echo "</script>";
	}
	
//******************************************<<<DISTRIBUCI�N DE HABITACIONES>>>*****************************************
//Se Almacenan en un array los datos de todas las habitaciones existentes;
$habita=array();
$cont=0;
$tipo_Hab=array();
$cont=0;

	$max_camas = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];
$contador = 0;
$qry_dist="SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab where camas_hab <> -1";
//---------
echo "<script>habit_datos = new Array();</script>";	//creo un array en javascript para saber las habitaciones y su tipo
//-------
//*************
$contador_datos = 0;
//***********
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
		//---------------
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
		//-----------
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
					$habita[$contador]['ocupadas1'][$a]['fecha']=$reservas_dia[$j]['fecha'];
					$habita[$contador]['ocupadas1'][$a]['fecha_s']=$reservas_dia[$j]['fecha_s'];
					$habita[$contador]['ocupadas1'][$a]['reserva'] = $reservas_dia[$j]['reserva'];
					$habita[$contador]['ocupadas1'][$a]['gru_enc'] = $reservas_dia[$j]['gru_enc'];
					$habita[$contador]['ocupadas1'][$a]['id_color'] = $reservas_dia[$j]['id_color'];
				}

				$habita[$contador]['ocupadas']['c']+=$reservas_dia[$j]['camas']; //y aumento el numero de camas ocupadas de la habitacion
			}
		}

		//**********************************
//--------------------------------
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
//--------------------------------

		$orden_lleg = array();	//ordeno las camas ocupadas de cada habitacion para que las ocupadas aparezcan 1� y despues las reservadas, las incompletas, las del grupo y las libres
		$orden_sal = array();
		$orden_gru = array();
		$orden_res = array();
		for($o = 0; $o < count($habita[$contador]['ocupadas1']); $o++){
			$orden_lleg[$o] = $habita[$contador]['ocupadas1'][$o]['fecha'];
			$orden_sal[$o] = $habita[$contador]['ocupadas1'][$o]['fecha_s'];
			$orden_gru[$o] = $habita[$contador]['ocupadas1'][$o]['gru_enc'];
			$orden_res[$o] = $habita[$contador]['ocupadas1'][$o]['reserva'];
		}
		if(count($orden_lleg) != 0){
			array_multisort ($orden_gru, SORT_ASC, $orden_lleg, SORT_ASC, $orden_sal, SORT_ASC, $orden_res, SORT_ASC, $habita[$contador]['ocupadas1']);
		}

		do{	//si dos ocupaciones se solapan en el tiempo (la fecha de salida de una es menor que la fecha de llegada de otra), las uno y se trata como una sola
			$cambio = 0;	
			for($o = 0; $o < count($habita[$contador]['ocupadas1']); $o++){
				$cad_temp = $habita[$contador]['ocupadas1'][$o]['fecha_s'];
				$fecha_s_temp = mktime(0, 0, 0, substr($cad_temp,5,2), substr($cad_temp,8,2), substr($cad_temp,0,4));
				for($o1 = $o+1; $o1 < count($habita[$contador]['ocupadas1']) && $cambio == 0; $o1++){
					$cad_temp = $habita[$contador]['ocupadas1'][$o1]['fecha'];
					$fecha_l_temp = mktime(0, 0, 0, substr($cad_temp,5,2), substr($cad_temp,8,2), substr($cad_temp,0,4));
					if($fecha_s_temp <= $fecha_l_temp && ($habita[$contador]['ocupadas1'][$o]['gru_enc'] == -1 && $habita[$contador]['ocupadas1'][$o1]['gru_enc'] == -1)){
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
								$ocupadas_tem[$puestos]['gru_enc'] = $habita[$contador]['ocupadas1'][$t]['gru_enc'];
								$puestos++;
							}
						}
						
						$habita[$contador]['ocupadas1'] = array();
						$habita[$contador]['ocupadas1'] = $ocupadas_tem;	//restauro las ocupaciones
					}
				}

			}
		}while($cambio == 1);
		
		$orden_lleg = array();  //vuelvo a ordenar
		$orden_sal = array();
		$orden_gru = array();
		$orden_res = array();
		for($o = 0; $o < count($habita[$contador]['ocupadas1']); $o++){
			$orden_lleg[$o] = $habita[$contador]['ocupadas1'][$o]['fecha'];
			$orden_sal[$o] = $habita[$contador]['ocupadas1'][$o]['fecha_s'];
			$orden_gru[$o] = $habita[$contador]['ocupadas1'][$o]['gru_enc'];
			$orden_res[$o] = $habita[$contador]['ocupadas1'][$o]['reserva'];
		}
		if(count($orden_lleg) != 0){
			array_multisort ($orden_gru, SORT_ASC, $orden_lleg, SORT_ASC, $orden_sal, SORT_ASC, $orden_res, SORT_ASC, $habita[$contador]['ocupadas1']);
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
				$temporal[$b]['gru_enc'] = $habita[$actual]['ocupadas1'][$b]['gru_enc'];
				$temporal[$b]['id_color'] = $habita[$actual]['ocupadas1'][$b]['id_color'];
			}
			
			$actual_camas = $habita[$actual]['ocupadas']['c'];
			$habita[$actual]['ocupadas']['c']=0;

			
			$puestas = 0;	//pongo las camas ocupadas de columna en columna, para que queden de izq a der
			while($actual_camas > 0){	//mientras queden camas por poner
				for($busq = $actual; $busq <= $contador && $actual_camas != 0; $busq++){	//recorro de la 1� a la ultima columna de la habitacion
					if($habita[$busq]['camas'] != $habita[$busq]['ocupadas']['c']){	//si no se ha llegado al maximo de camas de la columna
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['fecha'] = $temporal[$puestas]['fecha'];	
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['fecha_s'] = $temporal[$puestas]['fecha_s'];
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['reserva'] = $temporal[$puestas]['reserva'];
						$habita[$busq]['ocupadas1'][$habita[$busq]['ocupadas']['c']]['gru_enc'] = $temporal[$puestas]['gru_enc'];
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

//-----------
for($a = 0; $a < count($habita); $a++){ //calculo cuantas camas ocupadas, reservadas y temporales tiene cada columna, sin tener encuenta las del grupo, para ordenarlo
	$habita[$a]['ocupadas']['c_o'] = 0;
	$habita[$a]['ocupadas']['c_r'] = 0;
	$habita[$a]['ocupadas']['c_t'] = 0;
	for($o = 0; $o < $habita[$a]['ocupadas']['c']; $o++){
		$temporal = $habita[$a]['ocupadas1'][$o]['fecha'];
		$fecha_lle_ocu = mktime(0, 0, 0, substr($temporal,5,2), substr($temporal,8,2), substr($temporal,0,4));
		$temporal = $habita[$a]['ocupadas1'][$o]['fecha_s'];
		$fecha_sal_ocu = mktime(0, 0, 0, substr($temporal,5,2), substr($temporal,8,2), substr($temporal,0,4));
		if($habita[$a]['ocupadas1'][$o]['gru_enc'] == -1){
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
	//echo $habita[$a]['id'] . " * res:" . $habita[$a]['ocupadas']['c_r'] . " *ocu:  " . $habita[$a]['ocupadas']['c_o'] . " * tem: " . $habita[$a]['ocupadas']['c_t'] . "<br>";
}
//-------------
if(mysql_num_rows($res_dist) != 0){ //comprueba que existen habitaciones;
$con_cols = 0;
//busco todos los tipos de habitacion y cuento cuantas habitaciones de cada tipo hay
$sql = "SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <= '" . strftime("%Y-%m-%d",$fecha_sel) . "' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab where camas_hab <> -1 AND tipo_habitacion.Reservable = 'S' GROUP BY tipo_habitacion.nombre_tipo_hab ORDER BY tipo_habitacion.Id_Tipo_Hab";													//quitar la 2� condici�n del where si se quiere mostrar todas las habitaciones

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

echo "<tr>";
//---- poner el alto a lo que se necesite para que quede bien cuando en una pagina no exista ninguna habitacion.
echo "<td height='292' rowspan='" . ($max_camas+3) . "'><font size='1'>&nbsp;</font></td>";
//----
$grupos_col = array();
for($d = 0; $d < count($tipo_habitacion); $d++){	//pongo la cabezera de la tabla
	if($pagina == $tipo_habitacion[$d]['pagina']){
		$columnas = 0;
		for ($cama=0;$cama<count($habita);$cama++){
			if($habita[$cama]['tipo'] == $tipo_habitacion[$d]['id']){
				$columnas++;
			}
		}
		$grupos_col[$tipo_habitacion[$d]['tipo']] = $columnas;
		$con_cols++;
		echo "<td colspan='" . ($columnas) . "' align='center'><font class='nom_tipo_hab' style='cursor:default;'>" .$tipo_habitacion[$d]['tipo'] . "</font></td>";
		if($d != $num-1)
		{
			echo "<td rowspan='" . ($max_camas+3) . "'>&nbsp;</td>";
			echo "<td rowspan='" . ($max_camas+3) . "' class='separar_hab' width='2px'></td>";
			echo "<td rowspan='" . ($max_camas+3) . "'>&nbsp;</td>";
			//echo "<td class='linea_blanca' rowspan='" . ($max_camas+3) . "' background='../imagenes/img_tablas/linea1.gif'>&nbsp;</td>";
		}
	}

}
echo "<td rowspan='" . ($max_camas+3) . "'><font size='1'>&nbsp;</font></td>";
echo "</tr>";
$con_cols ++;

foreach ($habita as $key => $row){	//ordeno el array por tipo de habitacion, por el id, y por el numero de camas
	$orden1[$key]  = $row['orden'];
	for($i = 0; $i < count($habitaciones_orden); $i++){
		if($row['tipo'] == $habitaciones_orden[$i]['Id_Tipo_Hab'])
			$valor = $habitaciones_orden[$i]['orden'];
	}
	$tipo[$key] = $valor;
	$camas_1[$key] = $row['camas'];
	$ocupadas[$key] = $row['ocupadas']['c'];
//----
	$c_temporales[$key] = $row['ocupadas']['c_t'];
	$c_ocupadas[$key] = $row['ocupadas']['c_o'];
	$c_reservadas[$key] = $row['ocupadas']['c_r'];
//----
}
array_multisort ($tipo, SORT_ASC, $orden1, SORT_ASC, SORT_NUMERIC, $camas_1, SORT_DESC, $ocupadas, SORT_DESC, $c_ocupadas, SORT_DESC, $c_reservadas, SORT_DESC, $c_temporales, SORT_DESC, $habita);

$nombres_habitacion = array(); //guardo los nombres de las habitaciones que se van poniendo
$num_text = 0;
//-----
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
		if($pagina == $pagina_cama && $habita[$cama]['puede_reservar'] == 'S'){
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
			else{//Para el resto de filas, metemos un estilo dependiendo del estado de la habitaci�n
				$contador=$contadores[$habita[$cama]['id']];
				$contadores[$habita[$cama]['id']]++;

				if ($habita[$cama]['ocupadas']['c']>0){
					$fecha_sal_b = mktime(0, 0, 0, strftime("%m",$fecha_sal), strftime("%d",$fecha_sal) + 1, strftime("%Y",$fecha_sal));
					$f_tem = $habita[$cama]['ocupadas1'][$fila-1]['fecha'];
					$fecha_tem_l = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2), substr($f_tem,0,4));
					$f_tem = $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'];
					$fecha_tem_s = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2)-1, substr($f_tem,0,4));
					if($habita[$cama]['ocupadas1'][$fila-1]['id_color'] != "")
						$estilo = "style='background-color:#".$habita[$cama]['ocupadas1'][$fila-1]['id_color']."'";
					else
						$estilo = "";
					if(isset($hab_quitar[$habita[$cama]['id']]) && $hab_quitar[$habita[$cama]['id']] >= $contador && $habita[$cama]['ocupadas1'][$habita[$cama]['total_camas']+$contador]['gru_enc'] == -1){
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
					if( $habita[$cama]['ocupadas1'][$fila-1]['gru_enc'] != -1){
						$f_tem = $habita[$cama]['ocupadas1'][$fila-1]['fecha'];
						$fecha_tem_s1 = mktime(0, 0, 0, substr($f_tem,5,2), substr($f_tem,8,2)+$nnoches-1, substr($f_tem,0,4));
					}
					if ($hab_quitar[$habita[$cama]['id']] < $contador && $fecha_tem_l1 == $fecha_lle && $fecha_tem_s1 == $fecha_sal && $habita[$cama]['ocupadas1'][$fila-1]['gru_enc'] != -1){ //las camas de la pernocta del grupo
												$fecha_sal_b = mktime(0, 0, 0, strftime("%m",$fecha_sal), strftime("%d",$fecha_sal) + 1, strftime("%Y",$fecha_sal));
						$fecha_tem = mktime(0, 0, 0, substr($habita[$cama]['cambio_tipo'],5,2), substr($habita[$cama]['cambio_tipo'],8,2), substr($habita[$cama]['cambio_tipo'],0,4));
						if($habita[$cama]['cambio_tipo'] != "" && $fecha_tem < $fecha_sal_b){
								echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
								$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
								echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['cambio_tipo'] . "\",\"0\",\"1\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
								echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','1','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['tipo'] . "')\">" . substr($habita[$cama]['cambio_tipo'],8,2) . "�" . substr($habita[$cama]['cambio_tipo'],5,2));
								echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
								echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['cambio_tipo'] . "' size='1'>");
								echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
								echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
								echo ("<input type='hidden' name='cam_tipo-".$habita[$cama]['id']."-".$contador."' id='cam-tipo-".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['tipo'] . "' size='1'>");								
//***********cammmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmbbbbbbbbbbbbbbbiiiiiiiiiiiiiiiiiiiiiiiioooooooooooooooooooo
								$fecha_cambio = substr($habita[$cama]['cambio_tipo'],8,2) . "/" . substr($habita[$cama]['cambio_tipo'],5,2) . "/" . substr($habita[$cama]['cambio_tipo'],0,4);
								echo ("<script>poner_cambio_hab('" . $fecha_cambio . "', '" . $habita[$cama]['id'] . "', '" . $habita[$cama]['tipo'] . "', '')</script>");
								echo "</td>";
								$num_text ++;
								$componentes ++;
						}else{
							echo ("<td class='cama_libre_con' id='td" . $habita[$cama]['id']  . "_" .  $contador . "'");
							$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
							echo (" OnMouseOver='resaltar_celda(this);'  OnMouseOut='desresaltar_celda(this);'");
							echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','0','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "','" . $habita[$cama]['compartida'] . "')\">&nbsp;");
							echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'></td>");
							$num_text ++;
							$componentes ++;
						
						}

					}
					else if($fecha_tem_l1 <= $fecha_lle && $fecha_tem_s1 >= $fecha_sal_b){ //la cama no esta disponible en ninguna fecha
						if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
							echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
							echo ("</td>");
						}else{
							echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
							echo ("</td>");
						}
					}
					else if($fecha_tem_l1 <= $fecha_lle && $fecha_tem_s1 < $fecha_sal_b){ //la cama no esta disponible ahora pero si mas adelante
						if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
							echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
							echo ("</td>");
						}
						else{
							echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
							echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
							echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
							echo ("</td>");
						}
					}else if($fecha_tem_l1 > $fecha_lle && $fecha_tem_s1 < $fecha_sal_b){ //la cama esta disponible ahora, no mas adelante, pero si luego
						if($habita[$cama]['puede_reservar'] == 'S'){
								if($fecha_tem_l1 <= $fecha_sel){
									if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
										echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
										echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
										echo ("</td>");
									}else{
										echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
										echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
										echo ("</td>");
									}
								}else{
									$fecha_tem = mktime(0, 0, 0, substr($habita[$cama]['cambio_tipo'],5,2), substr($habita[$cama]['cambio_tipo'],8,2), substr($habita[$cama]['cambio_tipo'],0,4));
									if($habita[$cama]['cambio_tipo'] != "" && $fecha_tem < $fecha_sal_b){
											echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
											$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
											echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['cambio_tipo'] . "\",\"0\",\"1\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
											echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','1','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['tipo'] . "')\">" . substr($habita[$cama]['cambio_tipo'],8,2) . "�" . substr($habita[$cama]['cambio_tipo'],5,2));
											echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
											echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['cambio_tipo'] . "' size='1'>");
											echo ("<input type='hidden' name='cam_tipo-".$habita[$cama]['id']."-".$contador."' id='cam-tipo-".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['tipo'] . "' size='1'>");											
											echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
											echo "</td>";
											$num_text ++;
									
									}
									else{
										echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
										$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
										echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
											echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','2','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "','" . $habita[$cama]['compartida'] . "')\">" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],8,2) . "�" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],5,2));
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
										echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "' size='1'>");
										echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
										echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
										echo "</td>";
										$num_text ++;
										}
								}
						}else
							echo ("<td class='cama_libre'>&nbsp;</td>");
					}
					else{	//la cama esta disponible ahora, pero no m�s delante
						if($fecha_tem_l1 <= $fecha_sel)
							if($habita[$cama]['ocupadas1'][$fila-1]['reserva'] == 'n'){
								echo ("<td id=\"cama_ocupada_online\" " . $estilo . ">&nbsp;");
								echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
								echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
								echo ("</td>");
							}else{
								echo ("<td id=\"cama_reservada_online\" " . $estilo . ">&nbsp;");
								echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
								echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
								echo ("</td>");
							}
						else{
							if($habita[$cama]['puede_reservar'] == 'S'){
								$fecha_tem = mktime(0, 0, 0, substr($habita[$cama]['cambio_tipo'],5,2), substr($habita[$cama]['cambio_tipo'],8,2), substr($habita[$cama]['cambio_tipo'],0,4));
								if($habita[$cama]['cambio_tipo'] != "" && $fecha_tem < $fecha_sal_b){
										echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
										$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
										echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['cambio_tipo'] . "\",\"0\",\"1\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
										echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','1','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['tipo'] . "')\">" . substr($habita[$cama]['cambio_tipo'],8,2) . "�" . substr($habita[$cama]['cambio_tipo'],5,2));
										echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
										echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['cambio_tipo'] . "' size='1'>");
										echo ("<input type='hidden' name='cam_tipo-".$habita[$cama]['id']."-".$contador."' id='cam-tipo-".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['tipo'] . "' size='1'>");						
										echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
										echo "</td>";
										$num_text ++;
								
								}
								else{
									echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
									$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
									echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "\",\"" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
										echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','2','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "','" . $habita[$cama]['compartida'] . "')\">" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],8,2) . "�" . substr($habita[$cama]['ocupadas1'][$fila-1]['fecha'],5,2));
									echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
									echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "' size='1'>");
									echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
									echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
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
					if ($habita[$cama]['camas']>0){ //la cama esta libre todos los d�as
						$fecha_sal_b = mktime(0, 0, 0, strftime("%m",$fecha_sal), strftime("%d",$fecha_sal) + 1, strftime("%Y",$fecha_sal));
						$fecha_tem = mktime(0, 0, 0, substr($habita[$cama]['cambio_tipo'],5,2), substr($habita[$cama]['cambio_tipo'],8,2), substr($habita[$cama]['cambio_tipo'],0,4));
						if($habita[$cama]['cambio_tipo'] != "" && $fecha_tem < $fecha_sal_b){
								echo ("<td class='cama_temp' id='td" . $habita[$cama]['id'] . "_" . $contador . "'");
								$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
								echo (" OnMouseOver='resaltar_celda(this);cambiar_mens(\"" . $nombre_td . "\",\"" . $habita[$cama]['id'] . "\",\"" . $contador . "\",\"" . $habita[$cama]['cambio_tipo'] . "\",\"0\",\"1\");'  OnMouseOut=\"desresaltar_celda(this);window.status='Listo' ; return true\"");
								echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','1','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['cambio_tipo'] . "','" . $habita[$cama]['compartida'] . "','" . $habita[$cama]['tipo'] . "')\">" . substr($habita[$cama]['cambio_tipo'],8,2) . "�" . substr($habita[$cama]['cambio_tipo'],5,2));
								echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='0' size='1'>");
								echo ("<input type='hidden' name='inc_fecha".$habita[$cama]['id']."-".$contador."' id='inc_fecha".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['cambio_tipo'] . "' size='1'>");
								echo ("<input type='hidden' name='cam_tipo-".$habita[$cama]['id']."-".$contador."' id='cam-tipo-".$habita[$cama]['id']."-".$contador."' value='" . $habita[$cama]['tipo'] . "' size='1'>");								
								echo ("<input type='hidden' name='inc".$habita[$cama]['id']."-".$contador."' id='inc".$habita[$cama]['id']."-".$contador."' value='si' size='1'>");
								echo "</td>";
								$num_text ++;
						
						}
						else if($habita[$cama]['puede_reservar'] == 'S'){			
							echo ("<td class='cama_libre_con' id='td" . $habita[$cama]['id']  . "_" .  $contador . "'");
							$nombre_td = "td" . $habita[$cama]['id']  . "_" .  $contador;
							echo (" OnMouseOver='resaltar_celda(this);'  OnMouseOut='desresaltar_celda(this);'");
							echo (" onClick=\"asignar_cama('".$habita[$cama]['id']."','".$contador."',this,'".$mod."','".$num."','0','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha'] . "','" . $habita[$cama]['ocupadas1'][$fila-1]['fecha_s'] . "','" . $habita[$cama]['compartida'] . "')\">&nbsp;");
							echo ("<input type='hidden' name='tipo-".$habita[$cama]['id']."-".$contador."' id='tipo-".$habita[$cama]['id']."-".$contador."' value='tipo-" . $habita[$cama]['tipo'] . "' size='1'>");
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
	}	echo "</TR>";
 
}

if(count($grupos_col)!= 0){
	foreach ($grupos_col as $key => $row){
		echo ("<td colspan='" . $row . "'><font size='1'>&nbsp;</font></td>");
	}
	echo "</tr>";
}
}
else{
echo "<tr><td align='left><font class='nom_hab'>No se puede mostrar el mapa de habitaciondes, compruebe que hay habitaciones introducidas en el sistema.</font></td></tr>";
}
?>
		<input name="no_leer" id="no_leer" type="hidden" value="1">
		<input name="num_text" id="num_text" type="hidden" value="<?=$num_text?>">		<!-- guarda el numero de camas que se pueden asignar -->
		<input name="hab_selec" id="hab_selec" type="hidden" value="<?=$_POST['hab_selec']?>">	<!-- guarda las camas seleccionadas de los d�as que se han visto -->
		<input name="fec_selec"  id="fec_selec" type="hidden" value="<?=$_POST['fec_selec']?>">	<!-- guarda los d�as que se han visto -->
		<input name="es_inc" id="es_inc" type="hidden" value="0">			<!-- guarda el numero de camas individuales seleccionadas que son incompletas -->
		<input name="ir_a_dia" id="ir_a_dia" type="hidden" value="<?=$_POST['ir_a_dia']?>">	<!-- guarda las fechas de las camas incompletas, para ir a ellas automaticamente -->
		
	
<?

if(!isset($_POST['no_leer'])){

?>

	<script>
	
	var fec_selec = "";
	var hab_selec = "";
	<?
	for($a = 0; $a < count($crear_fechas); $a++){
		?>
		fec_selec = fec_selec + "<?=$crear_fechas[$a]?>+";
		<?
		$hab_selec_tem = split("\*",$crear_hab[$a]);
		for($h = 0; $h < count($hab_selec_tem); $h++){
			$hab_cama = split("-",$hab_selec_tem[$h]);
			$cont_hab = $hab_cama[1]/1;
			
			?>
			cont_hab = <?=$cont_hab?>;
			for(x = 1; x <= cont_hab; x++){
				exi = (document.getElementById("td<?=$hab_cama[0]?>_"+x)) ? true:false;
				if(!exi){
					cont_hab++;
				}else{
					hab_selec = hab_selec + "<?=$hab_cama[0]?>&" + x + "-";
				}
			}
			<?
		}
		?>
		hab_selec = hab_selec + "*";
	
	<?
	}
	
	$hab_selec_tem = split("\*",$crear_hab[0]);
		for($h = 0; $h < count($hab_selec_tem); $h++){
			$hab_cama = split("-",$hab_selec_tem[$h]);
			$cont_hab = $hab_cama[1]/1;
			
			?>
			cont_hab = <?=$cont_hab?>;
			for(x = 1; x <= cont_hab; x++){
				exi = (document.getElementById("td<?=$hab_cama[0]?>_"+x)) ? true:false;
				if(!exi){
					cont_hab++;
				}else{
					hab_selec = hab_selec + "<?=$hab_cama[0]?>&" + x + "-";
				}
			}
			<?
		}
		
	?>
	//hab_selec = hab_selec + "*";
	document.getElementById('hab_selec').value=hab_selec;
	document.getElementById('fec_selec').value=fec_selec;
	document.getElementById('fecha_ver').value='<?=$crear_fechas[0];?>';
	document.getElementById('fecha_cal').value='<?=substr($crear_fechas[0],0,2)."-".substr($crear_fechas[0],3,2)."-".substr($crear_fechas[0],6,4);?>';
	document.getElementById('mov_cal').value=4;
	document.getElementById('ingresar_estancia').submit();
	</script>
<?
}
?>		
	
		<script>

			var faltan = 0; //indica el n�mero de camas asignadas anteriormente y que ahora no se pueden asignar
			var cambi = 0; //indica si alguno de los dos valores anteriores cambia de valor
			cambiar_camas = 0;
			quitar_cambio_hab('<?=strftime("%d/%m/%Y",$fecha_sel)?>','');
			cambio_tipo_hab = "<?=$_POST['cambio_tipo']?>";	//---------------------------------------------------------
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
		
		if($enc == 1 && $_POST['mov_cal'] != 4){ //si la ultima fecha esta repetida
			$cad = "";
						//recorro los arrays de fechas y habitaciones dejando la cadena de forma que las demas fechas quedan igual, 
						// pero la que se repite se pone la ultima posicion, y esta se quita
			if($_POST['mov_cal'] == "1" || $_POST['mov_cal'] == "5")
				$restar = 1;
			else
				$restar = 2;
			for($i = 0; $i < count($habita)-$restar; $i++){
				if($i != $pos)
					$cad = $cad . $habita[$i];
				else
					$cad = $cad . $habita[count($habita)-$restar];
				if($cad != "")
					$cad = $cad . "*";
			}
			if($_POST['mov_cal'] != 5)
			echo "<script>document.getElementById('hab_selec').value='" . $cad . "'</script>";
			else{
			?>
				<script>
				document.getElementById('hab_selec').value = document.getElementById('hab_selec').value.substr(0,document.getElementById('hab_selec').value.length-1);
				</script>
			<?
			}
			
			
			$habita = array();
			$habita = split("\*",$cad); //array con todas las fechas que se han visto
			$cad="";
			for($i = 0; $i < count($fechas)-2; $i++){
				if($i != $pos)
					$cad = $cad . $fechas[$i];
				else
					$cad = $cad . $fechas[count($fechas)-2];
				$cad = $cad . "+";
			}
			
			echo "<script>document.getElementById('fec_selec').value='" . $cad . "'</script>";
			$fechas = array();
			$fechas = split("\+",$cad); //array con todas las fechas que se han visto
			$fechas1 = array();
			$r1 = 0;
			for($r = 0; $r < count($fechas); $r++){
				if($fechas[$r] != ""){
					$fechas1[$r1] = $fechas[$r];
					$r1++;
				}
			}
			$fechas = array();
			$fechas = $fechas1;
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
			$enc = 0;
			$fecha_tem = mktime(substr($tem,3,2),substr($tem,0,2),substr($tem,6,4));
			$fecha_men = mktime(12,31,9999);
			for($i = 0; $i < count($fechas); $i++){
				$fecha_tem1 = mktime(substr($fechas[$i],3,2),substr($fechas[$i],0,2),substr($fechas[$i],6,4));
				if($fecha_tem1 < $fecha_tem && fecha_men < $fecha_tem1){
					$ele = $i;
					$enc = 1;
				}
			}
			if($enc == 0)
				$ele = count($fechas); // si no se toma la posicion del ultimo elemento
			if($habita[$ele] == ""){
				do{
					$ele -= 1;
				}while($habita[$ele] == "" && $ele > 0);
			}
		}
		if(!isset($_POST["mov_cal"]) || $_POST["mov_cal"] == "2"){
			$ele = 0;
		}
		if(!isset($_POST['no_leer'])){
			$habita = split("\*",$hab_selec);
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
			if($_POST["mov_cal"] < 4){
			?>

			<script>
				cad1 = document.getElementById('hab_selec').value.split("*");
				pos1 = cad1.length-1;
				
				if(cad1[pos1] == "")
					pos1--;
				cad = "";
				for(c1 = 0; c1 < pos1; c1++){
					cad = cad + cad1[c1] + "*";
				}
				if(cad == ""){
					cad = cad1[0] + "*";
				}
				document.getElementById('hab_selec').value = cad;
				cad = cad1[pos1];
				cad_cor = cad.split("-");
			</script>			
			
			<?
			}
			for($i = 0; $i < count($c_ante)-1; $i++){
				$nom = split("&",$c_ante[$i]); //corto el nombre de la cama
		?>				
				<script>
				cambi = 1; //si llega aqui es que las variables de camas incompletas asignadas anteriormente cambia
				cambiar_camas = 0;
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
				$sql = $sql . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $nom[0] . "' and cambio_tipo_habitacion.fecha<='" . strftime("%Y-%m-%d",$fecha_sel) . "')";
				$res=mysql_query($sql);
				$fila=mysql_fetch_array($res);
				$tipo_actual = $fila['id_tipo_hab'] ;

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
					//----------------------------
					var exi1 = (document.getElementById("inc"+habit+"-"+cama)) ? true:false;
					if(exi1){
						document.getElementById('es_inc').value = eval(document.getElementById('es_inc').value) + 1;
						ini = document.getElementById('inc_fecha'+habit+"-"+cama).value;
						ini = ini.substr(8,2)+"/"+ini.substr(5,2)+"/"+ini.substr(0,4);
						poner_dia(ini);
						var exi2 = (document.getElementById("cam_tipo-"+habit+"-"+cama)) ? true:false;
						if(exi2){
							poner_cambio_hab(ini, habit, document.getElementById("cam_tipo-"+habit+"-"+cama).value , '');

						}
					}
					document.getElementById("td"+habit+"_"+cama).className = "cama_asignada"; 
					document.getElementById(habit+"-"+cama).value='1';	//se pone su hidden a 1 para indicar que se ha seleccionado
					//----------------------------
				}

				else{ //si todas las camas de la habitaci�n estan ocupada o ya est�n en asignadas se indica que falta asignar una cama
					enc = 0;
					i = 0;
				
				//-------------------------------
					contador = 0;
					for(i=0;i<habit_datos.length;i++){
						if(habit_datos[i][0] == habit){
							contador = habit_datos[i][2];
						}
					}
					if(contador != 0 && contador >= eval(cama)){
						enc = 1;
						faltan++;
						cambiar_camas = 1;
						cambi = 1; //si llega aqui es que las variables de camas incompletas asignadas anteriormente cambia
					}else{
						
					//------------------------
						var exi = (document.getElementById(habit+"-"+cama)) ? true:false; //dice si existe o no un elemento
							//echo ("<input type='hidden' name='".$habita[$cama]['id']."-".$contador."' id='".$habita[$cama]['id']."-".$contador."' value='ocupada' size='1'>");
						//cammmmmmbia
						if(exi && buscar == 1){	
								enc = 1;
									faltan++;
									cambiar_camas = 1;
									cambi = 1; //si llega aqui es que las variables de camas incompletas asignadas anteriormente cambia
									<?
									if($_POST["mov_cal"] < 4){
									?>
									cad2 = "";
									for(c = 0; c < cad_cor.length; c++){
										if(habit+"&"+cama != cad_cor[c])
											cad2 = cad2 + cad_cor[c] + "-";
									}
									cad = cad2;
									cad = cad.substring(0,cad.length-1);
									cad_cor = cad.split("-");
									<?
									}
									?>
						}
						
						else if(exi && document.getElementById(habit+"-"+cama).value == "ocupada"){
							for(i; i < habit_sel.length && enc == 0; i++){//recorro el array de habitaciones y su tipo
								if(habit_sel[i][0] == '<?=$nom[0]?>'){
									enc = 1;
									faltan++;
									cambiar_camas = 1;
									cambi = 1; //si llega aqui es que las variables de camas incompletas asignadas anteriormente cambia
									<?
									if($_POST["mov_cal"] < 4){
									?>
									cad2 = "";
									for(c = 0; c < cad_cor.length; c++){
										if(habit+"&"+cama != cad_cor[c])
											cad2 = cad2 + cad_cor[c] + "-";
									}
									cad = cad2;
									cad = cad.substring(0,cad.length-1);
									cad_cor = cad.split("-");
									<?
									}
									?>
								}
							}
						}
					//-------------------
						else if(!exi && buscar == 1){
							enc = 1;
							faltan++;
							cambiar_camas = 1;
							cambi = 1; //si llega aqui es que las variables de camas incompletas asignadas anteriormente cambia
							<?
							if($_POST["mov_cal"] < 4){
							?>
							cad2 = "";
							for(c = 0; c < cad_cor.length; c++){
								if(habit+"&"+cama != cad_cor[c])
									cad2 = cad2 + cad_cor[c] + "-";
							}
							cad = cad2;
							cad = cad.substring(0,cad.length-1);
							cad_cor = cad.split("-");
							<?
							}
							?>
						}
						

					}
					//-----------------
				}
				</script>
		<?
			}
			
			if($_POST["mov_cal"] < 4){
		?>
		<script>
		document.getElementById('hab_selec').value = document.getElementById('hab_selec').value + cad;
		</script>		
		<?
			}
			//----------------
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
				$sql = $sql . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $nom[0] . "' and cambio_tipo_habitacion.fecha<='" . strftime("%Y-%m-%d",$fecha_sel) . "')";
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
						pos_cama = 0;	//busco la 1� cama libre de la habitaci�n
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
							
							document.getElementById('es_inc').value = eval(document.getElementById('es_inc').value) - 1;
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
			for($i = 0; $i < count($c_ante)-1; $i++){ //cambio las posiciones de las camas asignadas, para que aparezcan las 1� de la habitaci�n
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
								var exi1 = (document.getElementById("td"+habit+"_"+bus)) ? true:false; //dice si existe o no un elemento
								var exi2 = (document.getElementById("td"+habit+"_"+(bus-1))) ? true:false; //dice si existe o no un elemento
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
			
			//---------------------------
		}
		//--aqui acaba el codigo para mantener las camas seleccionadas
			
		
		?>
			</table>
                    <table width="" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td width="50" align="left" class='label_formulario'>Faltan:</td>
						<td width="20" align="left" class='label_formulario'> 
                          <input type="text" name="f_camas" id="f_camas" value="0" size="2" readonly="true" class="input_formulario">
						  <? if($_POST['camas_asignadas']==""){ ?>
						  	<input type="hidden" name="camas_asignadas" id="camas_asignadas" value="0">
						  <? }else{ ?>
						  	<input type="hidden" name="camas_asignadas" id="camas_asignadas" value="<?=$_POST['camas_asignadas']?>">
						  <? } ?>
						  
						  <script>
						  document.getElementById('f_camas').value = eval(document.getElementById('f_camas').value)-eval('<?=$componentes1?>');
						  if(eval(document.getElementById('f_camas').value) < 0)
						  	document.getElementById('f_camas').value = 0;
						if(eval('<?=$componentes1?>') > eval(document.getElementById('camas_asignadas').value))
						  document.getElementById('camas_asignadas').value = eval(document.getElementById('camas_asignadas').value)+eval('<?=$componentes1?>');
						  	if(document.getElementById("hab_selec").value == ""){
								//pongo la fecha seleccionada en el text de fechas
								document.getElementById("fec_selec").value = "";
							}
							
							
							var mujer=eval(document.getElementById("mujeres").value);
							var hombre=eval(document.getElementById("hombres").value);
							if(isNaN(mujer)){
								mujer=0;
							}
							if(isNaN(hombre)){
								hombre=0;
							}
							document.getElementById("f_camas").value=eval(mujer)+eval(hombre)-eval(document.getElementById("camas_asignadas").value);
							 if(eval(document.getElementById('f_camas').value) < 0)
						  		document.getElementById('f_camas').value = 0;	
			//aaaaaaaaaaaaaaaaaaaaaaaa
								<?
								if(isset($_POST['f_camas']) && $_POST['f_camas'] != "0"){										
								?>
									document.getElementById('f_camas').value = <?=$_POST['f_camas']?>;	
								<?
								}	
								?>
			//aaaaaaaaaaaaaaaaaaaaaaaa
						  </script>
                        </td>
                        <td width="" align="left" class='label_formulario'>Camas </td>
                        
									
                        <td width="135" align="right" class='label_formulario'> 
							<div id="leyenda" style="position:absolute;  background-color: #FFFFFF; border: 1px none #000000;  visibility: hidden;z-index: 1;font-size:10px;margin-left:-170px;margin-top:-14px;width:400px;color:#064C87" > 
							<table width="100%" border="1" bordercolor="#000000" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0">
								<tr>
									<td colspan="6">
									<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                              		<tr> 
                                      <td align="center" style="font-size:12px"><b>Leyenda</b></td>
                                      <td width="20" align="center"><a href="#" onClick="ver_leyenda('1')" title="Cierra la Leyenda de la Distribuci�n de Habitaciones" onMouseOver="window.status='Cierra la Leyenda de la Distribuci�n de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true">X</a></td>
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
                                	<td colspan="3"  align="left" >No disponible todos los d�as</td>
                                	<!--td colspan="2">&nbsp;</td-->
                              	</tr>
                              	
                            </table>
                       	</div></td>
                        			
                        <td height="20" class='label_formulario' align="right">
						
						<a href="#" onClick="ver_leyenda('2')" title="Ver la Leyenda de la Distribuci�n de Habitaciones" OnMouseOver="window.status='Ver la Leyenda de la Distribuci�n de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true"> 
                        Leyenda</a></td>
								  </tr>
								</table>
							</td>
						</tr>
					</table>
			</td></tr></table>
			<br>
			<?
			$sql_tipos=mysql_query("select * from tipo_habitacion where reservable ='S'");
			$sql_edad=mysql_query("select * from edad");
			if(mysql_num_rows($sql_tipos)==0){?>
			<table>
				<tr>
					<td align='center' ><font color='#336699'><b>No hay habitaciones reservables.</b></font></td>
				</tr>
			</table>
			<? }else if(mysql_num_rows($sql_edad)==0){
			?>
			<table>
				<tr>
					<td align='center' ><font color='#336699'><b>No hay grupos de edad.</b></font></td>
				</tr>
			</table>
			<?
			
			}else{
			?>
			<? /////tabla para el cuadro de las edades-habitaciones y personas?>
			<table    id="tabla_detalles" cellspacing="0" bordercolor="#3F7BCC"  border="2">
			<tr align="center" class="titulo1_tabla_grupos" >
			<td>&nbsp;</td>
				<? //saco la informacion de la tabla de la base de datos
				$cont=0;
				$sql_tipos=mysql_query("select * from tipo_habitacion where reservable ='S'");
				for($i=0;$i<mysql_num_rows($sql_tipos);$i++){
				$fila = mysql_fetch_array($sql_tipos);
				$tipos[$i]=$fila['Id_Tipo_Hab']; 
				?>
				<td>
				<script>
					tipos_habitacion[tipos_habitacion.length]="<?PHP echo $fila['Id_Tipo_Hab']; ?>";
				</script>
				&nbsp;<?PHP echo $fila['Nombre_Tipo_Hab']; ?>&nbsp;
				</td>
				
				<?PHP
				}
				echo "</tr>";
				$sql_edad=mysql_query("select * from edad");
				for($i=0;$i<mysql_num_rows($sql_edad);$i++){
			
			$fila2 = mysql_fetch_array($sql_edad); ?>
			<tr >
			<td align="left" class="titulo1_tabla_grupos">
			<input type="hidden" name="tipos_edad" id="tipos_edad" value="<?PHP echo mysql_num_rows($sql_edad); ?>">
			<input type="hidden" name="<?PHP echo "min".$i; ?>" id="<?PHP echo "min".$i; ?>" value="<?PHP echo $fila2['Edad_Min']; ?>">
			<input type="hidden" name="<?PHP echo "max".$i; ?>" id="<?PHP echo "max".$i; ?>" value="<?PHP echo $fila2['Edad_Max']; ?>">
			<?PHP echo $fila2['Nombre_Edad']; ?>
			
			</td>
			
			<?PHP
			
				for($f=0;$f<count($tipos);$f++){
				$per=0;
				$ressultt=mysql_query("select * from pernocta_gr where Nombre_Gr ='".$nombre_grupo."' and Id_Edad ='".$fila2['Id_Edad']."' and Fecha_Llegada='".$fecha_llegada."'");
			
				for ($e=0;$e<mysql_num_rows($ressultt);$e++){
						$tupla=mysql_fetch_array($ressultt);
					$sql_hab = "select cambio_tipo_habitacion.id_tipo_hab as Id_Tipo_Hab from habitacion inner join ";
							$sql_hab = $sql_hab . "cambio_tipo_habitacion on (habitacion.id_hab = cambio_tipo_habitacion.id_hab) inner join ";
							$sql_hab = $sql_hab . "tipo_habitacion on (cambio_tipo_habitacion.id_tipo_hab = tipo_habitacion.id_tipo_hab) where ";
							$sql_hab = $sql_hab . "habitacion.id_hab = '" . $tupla['Id_Hab'] . "' and cambio_tipo_habitacion.fecha <= '" . $fecha_llegada. "' "; 
							$sql_hab = $sql_hab . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $tupla['Id_Hab'] . "')";
					@$tupla_2=mysql_fetch_array($sql_hab);
					if($tupla_2['Id_Tipo_Hab']==$tipos[$f]){
					$per=$per+$tupla['Num_Personas'];
						}
						}
			?>
			<td><?PHP
			if($_POST["tpersonas".$cont]){
			?>
			<input type="text" name="<?PHP echo "tpersonas".$cont; ?>" id="<?PHP echo "tpersonas".$cont; ?>"  value="<?PHP echo $_POST["tpersonas".$cont]; ?>" size="2"  maxlength="3" class="input_formulario" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
			<?PHP
			}else{
			?><input type="text" name="<?PHP echo "tpersonas".$cont; ?>" id="<?PHP echo "tpersonas".$cont; ?>"  value="0" size="2"  maxlength="3" class="input_formulario" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;">
			<?PHP
			}
			?>
			
			<input type="hidden" name="<?PHP echo "tedad".$cont; ?>" id="<?PHP echo "tedad".$cont; ?>"  value="<?PHP echo $fila2['Id_Edad']; ?>" >
				<input type="hidden" name="<?PHP echo "thab".$cont; ?>" id="<?PHP echo "thab".$cont; ?>"  value="<?PHP echo $tipos[$f]; ?>" >
			</td>
			<?PHP $cont=$cont+1;} ?>
			</tr>
			<?PHP 
			}
				 ?>
			
			</table>
			<input type="hidden" name="contt" id="contt" value="<? echo $cont; //variable para saber cuantos cuadros de texto me han salido para meter las edades?>">
			<input type="hidden" name="contador_cuadros_hab" id="contador_cuadros_hab" value="<? echo count($tipos); //variable para saber cuantos cuadros de texto me han salido para meter las edades?>">
			<input type="hidden" name="cuadros_hab_selec" id="cuadros_hab_selec" value="<?=$_POST['cuadros_hab_selec']?>">
		
			<script>
				if(document.getElementById("cuadros_hab_selec").value == "0-0-0-0-*")
					document.getElementById("cuadros_hab_selec").value = "";
				if(document.getElementById("cuadros_hab_selec").value != ""){
				fechas_cuadro = document.getElementById("fec_selec").value.split("+");

				fechas_cuadro_tem = new Array();
				con1 = 0;
				for(con = 0; con < fechas_cuadro.length; con++){
					if(fechas_cuadro[con] != ""){
						fechas_cuadro_tem[con1] = fechas_cuadro[con];
						con1 ++;
					}
				}
				fechas_cuadro = new Array();
				fechas_cuadro = fechas_cuadro_tem;
				
				pos = -1;
				for(con = 0; con < fechas_cuadro.length; con++){
					if(fechas_cuadro[con] == document.getElementById("fecha_ver").value){
						pos = con;
					}
				}
				if(pos == -1){
					fecha_ver = new Date(document.getElementById("fecha_ver").value.substr(6,4),document.getElementById("fecha_ver").value.substr(3,2)-1,document.getElementById("fecha_ver").value.substr(0,2));
					fecha_menor_cuadro = new Date(0,0,0);
					
					for(con = 0; con < fechas_cuadro.length; con++){
						fecha_comp = new Date(fechas_cuadro[con].substr(6,4),fechas_cuadro[con].substr(3,2)-1,fechas_cuadro[con].substr(0,2));
						if(fecha_comp > fecha_menor_cuadro && fecha_comp < fecha_ver){
							pos = con;
							fecha_menor_cuadro = fecha_comp;
						}
					}
				}
				if(pos != -1){
					edades_cuadro = document.getElementById("cuadros_hab_selec").value.split("*");
					edades_cuadro_tem = new Array();
					con1 = 0;
					for(con = 0; con < edades_cuadro.length; con++){
						if(edades_cuadro[con] != ""){
							edades_cuadro_tem[con1] = edades_cuadro[con];
							con1 ++;
						}
					}
					edades_cuadro = new Array();
					edades_cuadro = edades_cuadro_tem;
					if(edades_cuadro[pos]){
						edades_cuadro_pos = edades_cuadro[pos].split("-");
						
						var cont = 0;
						var contt = 0;
						for(i = 0; i < eval(document.getElementById("tipos_edad").value); i++){
							for(x = cont; x < eval(document.getElementById("contador_cuadros_hab").value) + cont; x++){
								document.getElementById("tpersonas"+x).value = edades_cuadro_pos[x];
								contt=contt+1;
							}
							cont=contt;
						}
					}
				}
				}
			</script>
		<?
			}	
		?>
		<? if($_POST['mover_pag'] == "0"){ ?>
		<script>
			if(cambi == 1){ // si se ha cambiado el valor de las camas incompletas asignadas anteriormente 
				document.getElementById("f_camas").value = faltan;
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
	
	//---------------------------------
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

//------------------------------------
					}
				}
				document.getElementById("hab_selec").value  = "";	//recompongo el valor del text de las habitaciones seleeccionadas
				for(e = 0; e < seleccionadas_dias.length-1; e++){	//tAodas menos la ultima la dejo igual
					document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + seleccionadas_dias[e] + "*";
				}
				
				document.getElementById("hab_selec").value = document.getElementById("hab_selec").value + cad; //y la ultima se pone como queda la cadena
			}
			
		</script>
		<? } ?>
							
	
							</div>	
			<?PHP
			}
			$comprobar_grupo=$_POST['comprobar_grupo'];
			if ($_POST['insertar_nuevo'] == "si" ) {
			$comprobar_grupo="no";
//cojer un color que este libre
/*echo "<script>alert('entra');</script>";*/
		$operacion_color = mysql_query("select * from colores WHERE estado='0' order by Id_Color");
		$row_color= mysql_fetch_array($operacion_color);
		$color_inser = $row_color['Id_Color'];
		if(mysql_num_rows($operacion_color)==0)
			{
				$operacion_color = mysql_query("select * from colores WHERE estado='1' order by Id_Color");
				$row_color= mysql_fetch_array($operacion_color);
		$color_inser = $row_color['Id_Color'];
			echo "<script>alert('Todos los colores del grupo ya entan cojidos se repetir�n los colores');</script>";
			}
		//haber si existe el grupo
		$sql_comprobar_grupo="select * from grupo where Nombre_Gr='".$nombre_grupo."'";
		$result_comprobar_grupo=mysql_query($sql_comprobar_grupo);
		if(mysql_num_rows($result_comprobar_grupo)!=0){//si ya existe el grupo lo modifico
			if($prov==""){//si no tengo provincia lo inserto sin provincia
			 	$operacion_inserta=mysql_query("update grupo SET Direccion_Gr='".$dir."', Localidad_Gr='".$localidad."', Id_Pais='".$pais."', Email_Gr='".$email."',CIF='".$cif."' where Nombre_Gr='".$nombre_grupo."'");
			}else{
				 $operacion_inserta=mysql_query("update grupo SET Direccion_Gr='".$dir."', Localidad_Gr='".$localidad."', Id_Pais='".$pais."', Email_Gr='".$email."' ,Id_Pro='".$prov."',CIF='".$cif."' where Nombre_Gr='".$nombre_grupo."'");
			}
		}else{//y sino lo inserto
			if($prov==""){//si no tengo provincia lo inserto sin provincia
			 $operacion_inserta=mysql_query("INSERT INTO grupo (Nombre_Gr,CIF, Direccion_Gr, Localidad_Gr, Id_Pais, Email_Gr) VALUES ('". trim($nombre_grupo) ."','". trim($cif) ."','". trim($dir) ."','". trim($localidad) ."','". trim($pais) ."','". trim($email) ."')");
			}else{
					$operacion_inserta=mysql_query("INSERT INTO grupo (Nombre_Gr,CIF, Direccion_Gr, Localidad_Gr, Id_Pro, Id_Pais, Email_Gr) VALUES ('". trim($nombre_grupo) ."','". trim($cif) ."','". trim($dir) ."','". trim($localidad) ."','". trim($prov) ."','". trim($pais) ."','". trim($email) ."')");
			}
		}/////
		
		//cambiar el estado del color
		$cambiar_estado_color=mysql_query("UPDATE colores SET Estado ='1' where Id_Color='".$color_inser."'");

		$fechas = split("\+",$_POST['fec_selec']); //array con todas las fechas que se han visto
		$habita = split("\*",$_POST['hab_selec']); //array con todos las camas que se han asignado
		$cuadro = split("\*",$_POST['cuadros_hab_selec']); 
		$tem = strftime("%d/%m/%Y",$fecha_sel);
		$enc = -1;
		$pos = -1;
		/*
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
			
			
			$restar = 2;
			for($i = 0; $i < count($cuadro); $i++){
				if($i != $pos)
					$cad = $cad . $cuadro[$i];
				else
					$cad = $cad . $cuadro[count($cuadro)];
				if($cuadro[count($cuadro)] != "")
					$cad = $cad . "*";
			}
			$_POST['cuadros_hab_selec'] = $cad;
			
			
			$cad = "";
			for($i = 0; $i < count($habita); $i++){
				if($i != $pos)
					$cad = $cad . $habita[$i];
				else
					$cad = $cad . $habita[count($habita)];
				if($habita[count($habita)] != "")
					$cad = $cad . "*";
			}
			$hab_selec = $cad;
			$habita = array();
			$habita = split("\*",$cad); //array con todas las fechas que se han visto
			$cad="";
			for($i = 0; $i < count($fechas)-2; $i++){
				if($i != $pos)
					$cad = $cad . $fechas[$i];
				else
					$cad = $cad . $fechas[count($fechas)-2];
				$cad = $cad . "+";
			}
			$fec_selec = $cad;
			$fechas = array();
			$fechas = split("\+",$cad); //array con todas las fechas que se han visto
			$fechas1 = array();
			$r1 = 0;
			for($r = 0; $r < count($fechas); $r++){
				if($fechas[$r] != ""){
					$fechas1[$r1] = $fechas[$r];
					$r1++;
				}
			}
			$fechas = array();
			$fechas = $fechas1;
		}*/
			
			$t_fecha = split("\+",$fec_selec);
			$t_habit = split("\*",$hab_selec);
			//**********************
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
			$fecha = array();
			$habit = array();
			$fecha[0] = $t_fecha[0];
			$habit[0] = $t_habit[0];
			$con = 0;
			for($i = 1; $i < count($t_habit); $i++){
				if($t_habit[$i] != $habit[$con]){
					$con++;
					$habit[$con] = $t_habit[$i];
					$fecha[$con] = $t_fecha[$i];
				}
			}$hab = array(); // en este array se guardan las habitaciones a asingar y el n�mero de camas en cada una
			for($h = 0; $h < count($habit); $h++){
				$hab[$h] = array();
				$hab_sel = split("-",$habit[$h]);
				asort($hab_sel);	//ordeno el array
				$tem = "";
				for($i = 0; $i < count($hab_sel); $i++){ // recorro el array de habitaciones selecionadas
					$enc = 0;
					$nom = split("&",$hab_sel[$i]); 
					for($x = 0; $x < count($hab[$h]["hab"]); $x++){ // miro a ver si la habitaci�n correspondiente ya est� en el otro array
						if($hab[$h]["hab"][$x] == $nom[0]){	// si esta se aumentan el 1 el numero de camas en esa habitaci�n
							$hab[$h]["cam"][$x] ++;
							$enc = 1;
						}
					}
					if($enc == 0){ // si no se ha encontrado la habitaci�n en el array de habitaciones a asignar se le a�ade
						$hab[$h]["hab"][count($hab[$h]["hab"])] = $nom[0];
						$hab[$h]["cam"][count($hab[$h]["cam"])] = 1;
					}
				}	
			}
						
			//creo los array de la tabla habitaciones-edades-personas
			
			$cuadro_edades = split("\*",$_POST['cuadros_hab_selec']);
			if($_POST['contt']){
			
				for($con = 0; $con < count($cuadro_edades)-1; $con++){
				   $cuadro_edades_valores = split("-",$cuadro_edades[$con]);
				   for($i=0;$i<$_POST['contt'];$i++){
						//if($cuadro_edades_valores[$i]!="0"){
							$tgrupo_1[$con][$i]['tipo_hab']=$_POST['thab'.$i];
							$tgrupo_1[$con][$i]['tipo_edad']=$_POST['tedad'.$i];
							$tgrupo_1[$con][$i]['personas']= $cuadro_edades_valores[$i];
						//}
				   }
				   if(count($tgrupo_1[$con])!=0){
						$tgrupo1=array();
						foreach ($tgrupo_1[$con] as $key => $row){	//ordeno el array por tipo de habitacion
							$tgrupo1[$key] = $row['tipo_hab'];
						}
						array_multisort ($tgrupo1, SORT_ASC, $tgrupo_1[$con]);
					}			
			   }
		
			}
		$tgrupo=array();
			for($h = 0; $h < count($hab); $h++){ //el array hab contiene las reservas que hay que poner el la BD
			
				$tgrupo = $tgrupo_1[$h];
				$lleg_b =  substr($fecha[$h],6,4) . "/" . substr($fecha[$h],3,2). "/" . substr($fecha[$h],0,2); //cadena que contiene la fecha de llegada de la reserva
				//-- calculo los d�as de pernocta
				$d_lle = mktime(0, 0, 0, substr($fecha[$h],3,2), substr($fecha[$h],0,2), substr($fecha[$h],6,4));
				if($h != count($hab)-1){ //si no es la ultima reserva, la fecha de salida ser� la fecha de llegada de la siguiente reserva
					$d_sal = mktime(0, 0, 0, substr($fecha[$h+1],3,2), substr($fecha[$h+1],0,2), substr($fecha[$h+1],6,4));
				}	//si es la ultima reserva, la fecha de salida ser� la fecha de salida
				else{
					$d_sal = mktime(0, 0, 0, substr($fecha[0],3,2), substr($fecha[0],0,2)+$nnoches, substr($fecha[0],6,4));
				}
			
				$pn = round(($d_sal - $d_lle)/86400); //me da la diferencia en segundos, por lo que lo divido entre los segundos de un d�a
				//--fin de el calculo de d�as de pernocta
				$sal_n = strftime("%Y/%m/%d",$d_sal);
				
				if($h != 0)//el ingreso se le pone a la primera reserva
					$ingreso = 0;
						
				if ($servicios_activados)
						$sql ="INSERT INTO estancia_gr (Nombre_Gr,Fecha_Llegada,Fecha_Salida ,DNI_Repres,Nombre_Repres,Apellido1_Repres,Apellido2_Repres,Tfno_Repres,Num_Personas,Num_Mujeres,Num_Hombres,PerNocta,Llegada_Tarde,HGr0_9,HGr10_19,HGr20_25,HGr26_29,HGr30_39,HGr40_49,HGr50_59,HGr60_69,HGrOtras,FGr0_9,FGr10_19,FGr20_25,FGr26_29,FGr30_39,FGr40_49,FGr50_59,FGr60_69,FGrOtras,Id_Color,Tipo_documentacion,Fecha_Expedicion,Fecha_nacimiento,Id_Pais_nacionalidad,Id_Servicios) VALUES ('".$nombre_grupo."','".trim($lleg_b)."','".$sal_n."','".$dniR."','".$nombreR."','".$ape1."','".$ape2."','".$telefono."','".$gente."','".$mujeres."','".$hombres."','".$pn."','".$hllegada."','".$h_menos10."','".$h_entre10_19."','".$h_entre20_25."','".$h_entre26_29."','".$h_entre30_39."','".$h_entre40_49."','".$h_entre50_59."','".$h_entre60_69."','".$h_mas69."','".$m_menos10."','".$m_entre10_19."','".$m_entre20_25."','".$m_entre26_29."','".$m_entre30_39."','".$m_entre40_49."','".$m_entre50_59."','".$m_entre60_69."','".$m_mas69."','".$color_inser."','".$tipo_doc."','".$annoEx."-".$mesEx."-".$diaEx."','".$annoNa."-".$mesNa."-".$diaNa."','".$nacionalidad."','".$servicio."')";
					else	
						$sql ="INSERT INTO estancia_gr (Nombre_Gr,Fecha_Llegada,Fecha_Salida ,DNI_Repres,Nombre_Repres,Apellido1_Repres,Apellido2_Repres,Tfno_Repres,Num_Personas,Num_Mujeres,Num_Hombres,PerNocta,Llegada_Tarde,HGr0_9,HGr10_19,HGr20_25,HGr26_29,HGr30_39,HGr40_49,HGr50_59,HGr60_69,HGrOtras,FGr0_9,FGr10_19,FGr20_25,FGr26_29,FGr30_39,FGr40_49,FGr50_59,FGr60_69,FGrOtras,Id_Color,Tipo_documentacion,Fecha_Expedicion,Fecha_nacimiento,Id_Pais_nacionalidad) VALUES ('".$nombre_grupo."','".trim($lleg_b)."','".$sal_n."','".$dniR."','".$nombreR."','".$ape1."','".$ape2."','".$telefono."','".$gente."','".$mujeres."','".$hombres."','".$pn."','".$hllegada."','".$h_menos10."','".$h_entre10_19."','".$h_entre20_25."','".$h_entre26_29."','".$h_entre30_39."','".$h_entre40_49."','".$h_entre50_59."','".$h_entre60_69."','".$h_mas69."','".$m_menos10."','".$m_entre10_19."','".$m_entre20_25."','".$m_entre26_29."','".$m_entre30_39."','".$m_entre40_49."','".$m_entre50_59."','".$m_entre60_69."','".$m_mas69."','".$color_inser."','".$tipo_doc."','".$annoEx."-".$mesEx."-".$diaEx."','".$annoNa."-".$mesNa."-".$diaNa."','".$nacionalidad."')";
						//inserto la estancia
						//echo $sql."<br>";
				$insertar=mysql_query($sql);
				for($i = 0; $i < count($hab[$h]["hab"]) && $hab[$h]["hab"][$i] != ""; $i++){
					$tem_camas=$hab[$h]["cam"][$i];
					
					do{
					
						for($x=0;$x<=count($tgrupo);$x++){	
						if($tgrupo[$x]['personas'] != "0"){
							$sql_hab = "select cambio_tipo_habitacion.id_tipo_hab as Id_Tipo_Hab from habitacion inner join ";
							$sql_hab = $sql_hab . "cambio_tipo_habitacion on (habitacion.id_hab = cambio_tipo_habitacion.id_hab) inner join ";
							$sql_hab = $sql_hab . "tipo_habitacion on (cambio_tipo_habitacion.id_tipo_hab = tipo_habitacion.id_tipo_hab) where ";
							$sql_hab = $sql_hab . "habitacion.id_hab = '" . $hab[$h]["hab"][$i] . "' and cambio_tipo_habitacion.fecha <= '" . $lleg_b . "' "; 
							$sql_hab = $sql_hab . "and fecha = (select max(fecha) from cambio_tipo_habitacion where id_hab = '" . $hab[$h]["hab"][$i] . "' and cambio_tipo_habitacion.fecha <='" . $lleg_b . "')";
							
							$res_tipo_hab = mysql_query($sql_hab);
							$fila_tipo_hab = mysql_fetch_array($res_tipo_hab);
							if($tgrupo[$x]['tipo_hab']==$fila_tipo_hab['Id_Tipo_Hab'] && $tgrupo[$x]['personas'] != "0"){
							
							
								if($tem_camas<$tgrupo[$x]['personas']){
									$tem_personas = $tem_camas;
									$tgrupo[$x]['personas'] = $tgrupo[$x]['personas'] - $tem_personas;
								}
								else{
									$tem_personas = $tgrupo[$x]['personas'];
									$tgrupo[$x]['personas'] = $tgrupo[$x]['personas'] - $tem_personas;
									}
								if($tem_personas!=0){
									
										$sql_2="INSERT INTO pernocta_gr (Nombre_Gr,Id_Hab,Id_Edad,Fecha_Llegada,Num_Personas) VALUES ('".$nombre_grupo."','" . $hab[$h]["hab"][$i] . "','".$tgrupo[$x]['tipo_edad']."','".$lleg_b."','".$tem_personas."')";
									mysql_query($sql_2);//ingreso las pernoctas
									$tem_camas=$tem_camas-$tem_personas;
								}
							}
							
						}
						}
					}while($tem_camas>0);
					
				
					
					
				}
			
			//elimino la reserva
				
		//$operacion_elim = mysql_query($sql_eliminar);
		
		
		$lleg_b =  substr($fecha[0],6,4) . "/" . substr($fecha[0],3,2). "/" . substr($fecha[0],0,2); 
		$result_modi=mysql_query("select * from detalles,pra Where detalles.DNI_PRA = pra.DNI_PRA  and detalles.DNI_PRA='".$dni_pra."' and detalles.Fecha_Llegada='".$lleg_b."'");
	
		$row_modi=mysql_fetch_array($result_modi);
		$sql="select * from detalles Where detalles.DNI_PRA='".$dni_pra."'";
		$result_modi2=mysql_query($sql);
		$afechas=array();
		//busco si hay una reserva partida y mirola primera fecha de llegada
		$afechas[0]['llegada']=$row_modi['Fecha_Llegada'];
		$afechas[0]['salida']=$row_modi['Fecha_Salida'];
		$afechas[0]['pernoctas']=$row_modi['PerNocta'];
		$llegada_provisional=$row_modi['Fecha_Llegada'];
		$salida_provisional=$row_modi['Fecha_Salida'];
		$cont=1;
		for($i=0;$i<mysql_num_rows($result_modi2);$i++){
			$fila=mysql_fetch_array($result_modi2);
			if($salida_provisional==$fila['Fecha_Llegada']){
				$afechas[$cont]['llegada']=$fila['Fecha_Llegada'];
				$afechas[$cont]['salida']=$fila['Fecha_Salida'];
				$salida_provisional=$fila['Fecha_Salida'];
				$afechas[$cont]['pernoctas']=$fila['PerNocta'];
				$cont=$cont+1;
			}
			if($llegada_provisional==$fila['Fecha_Salida']){
				$afechas[$cont]['llegada']=$fila['Fecha_Llegada'];
				$afechas[$cont]['salida']=$fila['Fecha_Salida'];
				$llegada_provisional=$fila['Fecha_Llegada'];
				$afechas[$cont]['pernoctas']=$fila['PerNocta'];
				$cont=$cont+1;
			}
				
		}
		$fecha_lleg_ord = array();
		foreach ($afechas as $key => $row){
			$fecha_lleg_ord[$key] = $row['llegada'];
		}
		array_multisort ($fecha_lleg_ord, SORT_ASC, $afechas);
		
		for($f = 0; $f < count($afechas); $f++){
			$sql_eliminar="delete from detalles  where DNI_PRA ='".$dni_pra."' and Fecha_Llegada='".$afechas[$f]['llegada']."'";//mirar si va en cascada
			mysql_query($sql_eliminar);
		}
		
		

		//si el pra no tiene mas reservas lo elimino
		$sql = "select count(dni_pra) as num from reserva where dni_pra ='" . trim($dni_pra) . "'";
		$res = mysql_query($sql);
		$fila = mysql_fetch_array($res);
		if($fila['num'] == 0){
			$sql = "DELETE FROM pra WHERE dni_pra = '" . trim($dni) . "'";
			mysql_query($sql);
		}
			
			}
			//inserto el representante como componente del grupo
			if($_POST['compo_representante']== 'si'){
				$sql_comprobar_componente=mysql_query("select Count(*) AS total  from componentes_grupo where DNI ='".$dniR."' ");
							$row_comprobar_componente= mysql_fetch_array($sql_comprobar_componente);
									if($row_comprobar_componente['total']==1){//si ya existe el miembro se modifica
												$sql_componente="UPDATE componentes_grupo SET Nombre_Gr='".$nombre_grupo."',Fecha_Llegada='".$fechal."' ,Tipo_documentacion='".$tipo_doc."',Fecha_Expedicion='".$annoEx."-".$mesEx."-".$diaEx."' ,Nombre='".$nombreR."' ,Apellido1='".$ape1."' ,Apellido2='".$ape2."' ,Fecha_nacimiento='".$annoNa."-".$mesNa."-".$diaNa."' ,Id_Pais_nacionalidad='".$nacionalidad."',Sexo='".$sexo_r."' WHERE DNI = '".$dniR."'";
									}else{//y si no se inserta
										$sql_componente="INSERT INTO componentes_grupo (DNI,Nombre_Gr,Fecha_Llegada ,Tipo_documentacion,Fecha_Expedicion ,Nombre ,Apellido1 ,Apellido2 ,Sexo ,Fecha_nacimiento ,Id_Pais_nacionalidad ) VALUES ('". trim($dniR) ."','".$nombre_grupo."','".$fechal."','".$tipo_doc."','".$annoEx."-".$mesEx."-".$diaEx."','".$nombreR."','".$ape1."','".$ape2."','".$sexo_r."','".$annoNa."-".$mesNa."-".$diaNa."','".$nacionalidad."')";
									}
									mysql_query($sql_componente);
				}					
			//$abrir_caja=4;
			
			//$insertar_nuevo="no";insertado
			echo "<script>document.getElementById('insertado').value='si'</script>";
			echo "<script>document.getElementById('insertar_nuevo').value='no'</script>";
			echo "<script>document.getElementById('ocultar_aceptar').value='si'</script>";
			echo "<script>document.getElementById('listado_componentes').value='si'</script>";
			echo "<script>document.getElementById('comprobar_grupo').value='no'</script>";
			/*echo "<script>alert(document.getElementById('insertar_nuevo').value);</script>";*/
			
			echo "<script>document.getElementById('ingresar_estancia').submit()</script>";
			?>
			
			
		
			
			<?
				
		
		
		
}
		//para saber si el grupo ya esta realizando la estancia
if ($comprobar_grupo == "si") {

$sql_comprobar_grupo="select * from estancia_gr ,pernocta_gr,grupo where grupo.Nombre_Gr=estancia_gr.Nombre_Gr and grupo.Nombre_Gr=pernocta_gr.Nombre_Gr and grupo.Nombre_Gr='".$nombre_grupo."' and estancia_gr.Fecha_Llegada<='".$fechal."' and estancia_gr.Fecha_Salida>'".$fechal."'";
//echo $sql_comprobar_grupo;
$result=mysql_query($sql_comprobar_grupo);

	if(mysql_num_rows($result)!=0){
		echo "<script>alert('Este grupo ya est� realizando una pernocta');</script>";
		echo "<script>document.getElementById('comprobar_grupo').value='no'</script>";
		
		}
	else{ ?>
	<script>
	
		for (i=1; i<=4;i++) 
				document.getElementById('lis'+i).style.display = "none";
			document.getElementById('lis'+<?=$_POST['pestana']?>).style.display = "block";	
	</script>
	
	<? }
									
} 
			//para controlar las capas
			if($abrir_caja!=0&&$abrir_caja!=4){
?>
<script>

if((document.getElementById('lis1').style.display != "block"&&<? echo $abrir_caja; ?>==1)||(document.getElementById('lis2').style.display != "block"&&<? echo $abrir_caja; ?>==2)||(document.getElementById('lis3').style.display != "block"&&<? echo $abrir_caja; ?>==3) ){
var a_caja=<? echo $abrir_caja; ?>;
			abrir(a_caja);
			
			
			}
</script>
<?PHP

} ?>	
		</form>
		
	
		</td></tr></table>
		<?PHP
    mysql_close($db);
	}else{?>

	<div class="error">
	
	NO TIENE PERMISOS PARA ACCEDER A ESTA P�GINA
	
	</div>
	
	<? }
?>

<script>

						
							dhx_globalImgPath="../imgs/";
							var diaEx=dhtmlXComboFromSelect("diaEx");
	  						diaEx.enableFilteringMode(true);
	  						var mesEx=dhtmlXComboFromSelect("mesEx");
	  						mesEx.enableFilteringMode(true);
	  						var annoEx=dhtmlXComboFromSelect("annoEx");
	  						annoEx.enableFilteringMode(true);
	  					
	  						var diaNa=dhtmlXComboFromSelect("diaNa");
	  						diaNa.enableFilteringMode(true);
	  						var mesNa=dhtmlXComboFromSelect("mesNa");
	  						mesNa.enableFilteringMode(true);
	  						var annoNa=dhtmlXComboFromSelect("annoNa");
	  						annoNa.enableFilteringMode(true);
	  						
	  						
	  					<?	if (isset($hayscript)){?>
							var diaNa_componente=dhtmlXComboFromSelect("diaNa_componente");
	  						diaNa_componente.enableFilteringMode(true);
	  						var mesNa_componente=dhtmlXComboFromSelect("mesNa_componente");
	  						mesNa_componente.enableFilteringMode(true);
	  						var annoNa_componente=dhtmlXComboFromSelect("annoNa_componente");
	  						annoNa_componente.enableFilteringMode(true);
	  						
	  				
	  						var diaEx_componente=dhtmlXComboFromSelect("diaEx_componente");
	  						diaEx_componente.enableFilteringMode(true);
	  						var mesEx_componente=dhtmlXComboFromSelect("mesEx_componente");
	  						mesEx_componente.enableFilteringMode(true);
	  						var annoEx_componente=dhtmlXComboFromSelect("annoEx_componente");
	  						annoEx_componente.enableFilteringMode(true);
	  						
	  				<?}?>
	  					
	  					
	  				
						
</script>