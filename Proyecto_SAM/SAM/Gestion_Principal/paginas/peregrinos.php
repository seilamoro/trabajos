<?
	//Se comprueba que el usuario tenga permisos para acceder a esta seccion
	if(isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']==true) {
?>

<script language="javascript">
	//Cambia  el valor de los campos ocultos "existe" (a la hora de realizar el alta en la base de datos segun el valor que tenga "existe" hará un insert o un update sobre la tabla cliente) , "sub" (Indica si se ha de mostrar el mapa de habitaciones, o si se debe realizar el alta), test_dni (Indica que se va a buscar en la base de datos un registro con dni similar para rellenar el formulario de lata) y finalmente envia el formulario 
	function dni_change(){

		if(document.forms[0].existe.value == "true"){
			document.forms[0].existe.value == "false";
		}
		if(document.forms[0].sub.value == "false"){
			document.forms[0].sub.value = "true";		
		}
		document.forms[0].test_dni.value = "1";
		if (validadni(0))	
			document.forms[0].submit();		
	}
	//función que abre la ventana de búsqueda de peregrinos (Enlace de la lupa)
	function abrir_busqueda(){
		window.open( "paginas/per_busq_dni.php?form=0", "_blank", "width=650px,height=650px,resizable=no,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=150px,left=430px");
	}

	function ver_leyenda(act){
		if(act == '1')
			document.getElementById('leyenda').style.visibility='hidden';
		else
			document.getElementById('leyenda').style.visibility='visible';
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

	//muestra la caja de habitaciones, en caso de que no esté mostrada , y  disminuye la altura del listado de peregrinos
	//En caso de que se este mostrando, se oculta y el listado de peregrinos vuelve a su estado normal
	function nuevo_habitaciones(){
		if(document.getElementById('caja_superior_derecha_a').style.display=='none'){
			document.getElementById('caja_superior_derecha_a').style.display='block';
			document.getElementById('tabla_listado').style.height='220px';
			document.getElementById('caja').style.height='240px';				
		}else{
			oculta_habitaciones();	
		}
	}

	//oculta la caja de habitaciones y vuelve a su estado normal el listado de peregrinos
	function oculta_habitaciones(){
		document.getElementById('caja_superior_derecha_a').style.display='none';
		document.getElementById('tabla_listado').style.height='auto';
		document.getElementById('caja').style.height='auto';
	}
	
	//función que selecciona una cama de una habitación. Los argumentos forman el id de la cama en la distribución de habitaciones
	function asigna_habitacion(hab,i,col,fila){
			//Se evalua el color de fondo de la celda de la tabla. En función de su color de fondo se sabrá si se habrá intentado asignar o no.			
			if(document.forms[0].asignada.value == 1){
					//En este caso estará ocupada, por tanto se le devolverá el color de habitación libre, o habitación temporal, según corresponda.
					if(document.forms[0].id_cama.value == hab+"-"+i+"-"+col+"-"+fila){						
						if(document.forms[0].id_habitacion.value == ""){
							alert("Debe seleccionar una habitación para el primer dia de estancia.");
						}else{
							document.getElementById(hab+"-"+i+"-"+col+"-"+fila).className = "cama_libre";
							document.forms[0].id_habitacion.value = "";					
							document.forms[0].id_cama.value = "";
							document.forms[0].asignada.value = 0;
						}
					}else{
						document.getElementById(document.forms[0].id_cama.value).className ="cama_libre";
						document.getElementById(hab+"-"+i+"-"+col+"-"+fila).className = "cama_asignada";					
						document.forms[0].id_habitacion.value = hab;
						document.forms[0].id_cama.value = hab+"-"+i+"-"+col+"-"+fila;
						document.forms[0].asignada.value = 1;
					}				
			}else{
				//En este bloque se comprueba que no le pueda asignar más de una habitación
				if(document.forms[0].id_habitacion.value != ""){							document.getElementById(document.forms[0].id_cama.value).className="cama_libre";
					document.forms[0].id_cama.value = "";
					document.forms[0].id_habitacion.value = "";

				}
				//se le da apariencia de asignada a la cama en que se ha hecho click
				document.getElementById(hab+"-"+i+"-"+col+"-"+fila).className = "cama_asignada";
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
		    var dni = document.forms[formu].dni_peregrino.value;
		    var tipo_documento = document.forms[formu].tipo_documentacion.value;
		    var pais = document.forms[formu].pais_peregrino[document.forms[formu].pais_peregrino.selectedIndex].value; 	
		    
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
						document.forms[formu].pais_peregrino.focus();
						return false;
				}
				else 
				{	
				    // Tiene que tener una longitud de 7 números como mínimo.
				    if (dni.length < 7)
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.forms[formu].dni_peregrino.focus();
						return false;
					}
				    // Comprobamos si los siete primeros son números.
					else if(isNaN(dni.substring(0,7)) )
					{
						alert("Debe rellenar correctamente el campo DNI.");					
						document.forms[formu].dni_peregrino.focus();
						return false;
					}
				
					else
					{
					    // Si tiene solo siete números, le añadimos un cero y la letra.
						if(dni.length == 7)
						{						 	
							document.forms[formu].dni_peregrino.value = "0"+dni+calcletra(dni);						
							
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
								document.forms[formu].dni_peregrino.focus();
								return false;
							}			
							// Le añadinos la letra si el último caracter es un número
							if (!isNaN(dni.substring(7,8))) document.forms[formu].dni_peregrino.value = dni+calcletra(dni);						 
						 }   
							// Si es igual a nueve..
						else if (dni.length == 9)
						{
						    
						 	// El penúltimo tiene que ser un número y el último carácter solo puede ser una letra permitida
							if ( (isNaN(dni.substring(7,8)) ) || (calcletra(dni.substring(0,8))!=dni.substring(8,9)) ) 
							{
								alert("Debe rellenar correctamente la letra de control del DNI.");					
								document.forms[formu].dni_peregrino.focus();
								return false;
							}
									
						 }
						else if (dni.length > 9)
						{
							alert("Debe rellenar correctamente el campo DNI.");					
							document.forms[formu].dni_peregrino.focus();
							return false;
						}
				      
				    }
				 }
			 } 
			 else if ( (tipo_documento == "N") && (pais == "ES") )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.forms[formu].pais_peregrino.focus();
				return false;
			 } 
			
			 else if ( (tipo_documento == "N") && (pais != "ES") )
			 {
				// Tiene que tener una longitud de 8 números como mínimo y 10 como máximo.
				if ( (dni.length<7) || (dni.length>10) )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.forms[formu].dni_peregrino.focus();
					return false;
				}
				
				// Comprobamos si el primer carácter es un carácter X o un número.
				else if( (isNaN(dni.substring(0,1))) && (dni.substring(0,1)!="X") )
				{
					alert("Debe rellenar correctamente el campo DNI.");					
					document.forms[formu].dni_peregrino.focus();
					return false;
				}				
									
				//alert (dni.length);
				//alert (dni.substring(0,dni.length));
				
				// Si el primer elemento no tiene X se la añadimos
			    if (!isNaN(dni.substring(0,1))) 
				{
					dni = "X"+dni;
					document.forms[formu].dni_peregrino.value = dni;				
				}
			
			    // Si solo tiene 7 elemenos númericos, le añadimos un cero.
				if ( (!isNaN(dni.substring(1,8))) && ( (dni.length == 8) || ((dni.length == 9) && (isNaN(dni.substring(8,9)))) ) )
				{				    
				    
					dni = "X0"+dni.substring(1,9);
					document.forms[formu].dni_peregrino.value = dni;
				
				}
				//alert (dni.substring(1,9));
				// Le añadinos la letra si el último caracter es un número
				if (dni.length!=10) document.forms[formu].dni_peregrino.value = dni+calcletra(dni.substring(1,9));	
				
				else if ( (dni.length==10) && ( (dni.substring(0,1)!="X") || (isNaN(dni.substring(1,9))) || 
											    ((dni.substring(9,10)) != calcletra(dni.substring(1,9)))    ) )
				{
					alert("Debe rellenar correctamente la letra de control del DNI.");					
					document.forms[formu].dni_peregrino.focus();
					return false;
				}				
				
			 } 
			 else if ( (tipo_documento == "I") && (!ExisteElemento(PaisesPermitidos, pais) ) )
			 {
				alert("El pais no corresponde con el tipo de documento");					
				document.forms[formu].pais_peregrino.focus();
				return false;
			 }
			else if  ( (tipo_documento == "P") && (pais == "ES") && (dni.length>11) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					
				document.forms[formu].dni_peregrino.focus();
				return false;
			}
			else if  ( (tipo_documento == "P") && (pais != "ES") && (dni.length>14) )
			{
				alert("Debe rellenar correctamente el campo DNI.");					
				document.forms[formu].dni_peregrino.focus();
				return false;
			}
			
			return true;
		}

function valida_campos(formu){

			
		
			var pais = document.forms[formu].pais_peregrino[document.forms[formu].pais_peregrino.selectedIndex].value; 	
			var tipo_documento = document.forms[0].tipo_documentacion.value;
			//alert("pais "+document.forms[formu].pais_peregrino[document.forms[formu].pais_peregrino.selectedIndex].value);
			
			if (!validadni(formu)) return false;

			//Evaluamos que la fecha de expedición del documento sea como mínimo igual a la fecha de nacimiento
			var fecha_exp = Array(document.forms[0].dia_fecha_expedicion.value,document.forms[0].mes_fecha_expedicion.value, document.forms[0].anyo_fecha_expedicion.value);
			var fecha_nac = Array(document.forms[0].dia_fecha_peregrino.value,document.forms[0].mes_fecha_peregrino.value, document.forms[0].anyo_fecha_peregrino.value);
			
			
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
			/*if ( (fecha_exp[0]=='') || (fecha_exp[1]=='') || (fecha_exp[2]=='' ) )
			{
				alert("Debe introducir una fecha de expedición");
				if (fecha_exp[0]=='') document.forms[0].dia_fecha_expedicion.focus();
				else if (fecha_exp[1]=='') document.forms[0].mes_fecha_expedicion.focus();
					 else  document.forms[0].anyo_fecha_expedicion.focus();	
				return false;	
			}		
		    */
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


			if(document.forms[formu].nombre_peregrino.value ==""){
				alert("Debe rellenar el campo nombre.");
				document.forms[formu].nombre_peregrino.focus();
				return false;
			}
			if(document.forms[formu].apellido1_peregrino.value ==""){
				alert("Debe rellenar el campo primer apellido.");
				document.forms[formu].apellido1_peregrino.focus();
				return false;
			}
			if((document.forms[formu].apellido2_peregrino.value =="") && (pais=='ES') )
			{
				alert("Debe rellenar el campo segundo apellido.");
				document.forms[formu].apellido2_peregrino.focus();
				return false;
			}
			// Se comprueva que se halla introducido una provinvia
			if (document.forms[formu].provincia_peregrino.options[document.forms[formu].provincia_peregrino.selectedIndex].value == '' && pais == "ES"){
				alert("Debe introducir una provincia válida");					
				document.forms[formu].provincia_peregrino.focus();
				return false;
			}
			var telefono = document.forms[formu].telefono_peregrino.value;
			//Se comprueba que el campo telefono tenga exactamente 9 caracteres
			//En caso de no cumplirse, se mostrará un mensaje y se devolverá al usuario al formulario
			if ( (telefono != "") && (pais == 'ES') ){
				if(telefono.length != 9){
					alert("Debe rellenar correctamente el campo teléfono.");	
					document.forms[formu].telefono_peregrino.focus();
					return false;
				}else{
					/*Una vez que el campo telefono conste de 9 caracteres, se comprueba que cada uno de ellos sea un dígito.
					En caso de no cumplirse , se mostrará un mensaje y se devolverá al usuario al formulario*/
					for(i=0;i<telefono.length-1;i++){
						if(isNaN(telefono.substring(i,i+1))){
							alert("Debe rellenar correctamente el campo teléfono.");	
							document.forms[formu].telefono_peregrino.focus();
							return false;
						}
					}
				}
			}			
			var cp = document.forms[formu].cp_peregrino.value;
			/*Se comprueba que el codigo postal conste de 5 caracteres.
			  De no ser así, se mostrará un mensaje indicándolo, y se devolverá al usuario al formulario*/
			if(cp!=""){
				if(cp.length!=5){
					alert('Debe rellenar el campo código postal.');
					document.forms[formu].cp_peregrino.focus();
					return false;
				}else{
					/*Teniendo 5 caracteres, se comprueba que cada uno de ellos sean números.
					En caso de no cumplirse, se muestra un mensaje y se devuelve el foco al campo codigo postal.
					*/
					for(i=0;i<cp.length-1;i++){
						if(isNaN(cp.substring(i,i+1))){
							alert('Debe rellenar el campo código postal.');
							document.forms[formu].cp_peregrino.focus();
							return false;
						}
					}
				}
			}
			//Se evalua que haya seleccionado una casilla de sexo
			if(document.forms[formu].sexo_peregrino[1].checked == false && document.forms[formu].sexo_peregrino[0].checked ==false){
				alert("Debe seleccionar una casilla de sexo.");
				return false;
			}
			//Solo se hace la validación en caso de que haya algo escrito en el campo mail, puesto que puede ser nulo
			if(document.forms[formu].email_peregrino.value != ""){
				if(document.forms[formu].email_peregrino.value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
					//se informa de que ha introducido el email con formato incorrecto
					alert("Debe rellenar el campo E-mail con el el formato correcto (\"xxxx@xxx.xxx\")");
					//se coloca el foco en el campo email
					document.forms[formu].email_peregrino.focus();
					return false;
				}
			}
		return true;
	}
	//Función que activa o desactiva los campos provincia, localidad . dirección y código postal, en función del pais que esté seleccionado.
	//Si el pais es España, se activarán, en caso contrario se desactivarán
	function paises(formu){
			if(document.forms[formu].pais_peregrino[document.forms[formu].pais_peregrino.selectedIndex].value != "ES"){
				document.forms[formu].cp_peregrino.value = "";
				document.forms[formu].localidad_peregrino.value = "";
				document.forms[formu].provincia_peregrino.value = "";
				document.forms[formu].direccion_peregrino.value = "";
				document.forms[formu].cp_peregrino.disabled = true;
				document.forms[formu].localidad_peregrino.disabled = true;
				document.forms[formu].provincia_peregrino.disabled = true;
				document.forms[formu].direccion_peregrino.disabled = true;	
						
			}else{
				  if(document.forms[formu].cp_peregrino.disabled == true){
					document.forms[formu].cp_peregrino.disabled = false;
					document.forms[formu].localidad_peregrino.disabled = false;
					document.forms[formu].provincia_peregrino.disabled = false;
					document.forms[formu].direccion_peregrino.disabled = false;	
					document.forms[formu].provincia_peregrino.options[0].selected = true;
				
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
			document.forms[0].submit();
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
			}else{
				return false;
			}	
		}else{
			for(var i=1;i<3;i++){
				document.getElementById("lis"+i).style.display="none";
			}
			document.getElementById("lis"+num).style.display="block";
		}
	}

	//Funcion que desactiva el boton aceptar(para enviar formulario) y activa el botón  de recargar el mapa de habitaciones
	function cambio_botones_habitaciones(){
		document.getElementById('boton_nuevo_habitacion2').style.display="none";
		document.getElementById('boton_ver_habitaciones').style.display="block";
	}

	//Se utiliza al cambiar el campo DNI, se busca en la base de datos algun registro con DNI similar, y en caso de encontrarlo se rellenan los datos personales con los datos de la base de datos
	function rellena_alta(t_dni, dni , nom , a1 , a2 ,pais , prov , loc ,  dir , cp , f_nac, f_exp , sexo , tel , email , obs ){
		for(var i=0;i<document.forms[0].tipo_documentacion.length;i++){
			if(document.forms[0].tipo_documentacion[i].value == t_dni){
				document.forms[0].tipo_documentacion[i].selected = true;
				break;
			}
		}
		document.forms[0].dni_peregrino.value = dni;
		document.forms[0].nombre_peregrino.value = nom;
		document.forms[0].apellido1_peregrino.value = a1;
		document.forms[0].apellido2_peregrino.value = a2;
		document.forms[0].direccion_peregrino.value = dir;
		document.forms[0].cp_peregrino.value = cp;
		document.forms[0].localidad_peregrino.value = loc;
		document.forms[0].telefono_peregrino.value = tel;
		document.forms[0].email_peregrino.value = email;
		document.forms[0].observaciones_peregrino.value = obs;

		for(var i=0;i<document.forms[0].pais_peregrino.length;i++){
			if(document.forms[0].pais_peregrino[i].value == pais){
				document.forms[0].pais_peregrino[i].selected = true;
				break;
			}
		}
		if(document.forms[0].pais_peregrino.value == "ES"){
			for(var i=0;i<document.forms[0].provincia_peregrino.length;i++){
				if(document.forms[0].provincia_peregrino[i].value == prov){
					document.forms[0].provincia_peregrino[i].selected = true;
					break;
				}
			}
		}

		for(var i=0;i<document.forms[0].sexo_peregrino.length;i++){
			if(document.forms[0].sexo_peregrino[i].value == sexo	){
				document.forms[0].sexo_peregrino[i].checked = true;
				break;
			}
		}
		var fecha = f_nac.split("-");
		for(var i=0;i<document.forms[0].dia_fecha_peregrino.length;i++){
			if(document.forms[0].dia_fecha_peregrino[i].value == fecha[2]){
				document.forms[0].dia_fecha_peregrino[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].mes_fecha_peregrino.length;i++){
			if(document.forms[0].mes_fecha_peregrino[i].value == fecha[1]){
				document.forms[0].mes_fecha_peregrino[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].anyo_fecha_peregrino.length;i++){
			if(document.forms[0].anyo_fecha_peregrino[i].value == fecha[0]){
				document.forms[0].anyo_fecha_peregrino[i].selected = true;
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
	function limpia_formulario(){
	    
		document.forms[0].tipo_documentacion[0].selicted = true;
		document.forms[0].nombre_peregrino.value = "";
		document.forms[0].apellido1_peregrino.value = "";
		document.forms[0].apellido2_peregrino.value = "";
		document.forms[0].direccion_peregrino.value = "";
		document.forms[0].cp_peregrino.value = "";
		document.forms[0].localidad_peregrino.value = "";
		document.forms[0].telefono_peregrino.value = "";
		document.forms[0].email_peregrino.value = "";
		document.forms[0].observaciones_peregrino.value = "";
		for(var i=0;i<document.forms[0].pais_peregrino.length;i++){
			if(document.forms[0].pais_peregrino[i].value == "ES"){
				document.forms[0].pais_peregrino[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].sexo_peregrino.length;i++){
			document.forms[0].sexo_peregrino[i].checked = false;		
		}
		for(var i=0;i<document.forms[0].dia_fecha_peregrino.length;i++){
			if(document.forms[0].dia_fecha_peregrino[i].value == 1900){
				document.forms[0].dia_fecha_peregrino[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].mes_fecha_peregrino.length;i++){
			if(document.forms[0].mes_fecha_peregrino[i].value == 01){
				document.forms[0].mes_fecha_peregrino[i].selected = true;
				break;
			}
		}
		for(var i=0;i<document.forms[0].anyo_fecha_peregrino.length;i++){
			if(document.forms[0].anyo_fecha_peregrino[i].value == 01){
				document.forms[0].anyo_fecha_peregrino[i].selected = true;
				break;
			}
		}
	}
	
	
//funcion que establece la longitud máxima del campo dni en función del tipo de documento que se seleccione
	function documentacion(formu)
	{
	   var Num_Paises;
		var selected_php = '<?php echo $_POST['pais_peregrino']?>';
		if (document.forms[formu].pais_peregrino.length > 0){
		   var selected_js = document.forms[formu].pais_peregrino.options[document.forms[formu].pais_peregrino.selectedIndex].value;
		}else{
			 var selected_js = 0;
		}
	  
	  
	  
	  
	  
		if ( (document.forms[formu].tipo_documentacion.value=="P") )
		{
		    
			document.forms[formu].pais_peregrino.options.length = 0;
		     //alert("tam "+document.forms[formu].pais_peregrino.options.length);
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
				echo"document.forms[formu].pais_peregrino.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
				if ($fila['Id_Pais']=='ES')  echo "document.forms[formu].pais_peregrino.selectedIndex =".$i." ;";
				//echo "document.forms[formu].pais_peregrino.options[".$i."] = null;";
							 
			  }			  
			  
			?>						    
			//procedemos a activar los campos relacionados
			if ( document.forms[formu].cp_peregrino.disabled)
			{
				document.forms[formu].cp_peregrino.disabled = false;
				document.forms[formu].localidad_peregrino.disabled = false;
				document.forms[formu].provincia_peregrino.disabled = false;
				document.forms[formu].direccion_peregrino.disabled = false;
			}   				
		}
		
	    // si el tipo de documento es Carnet de conducir o dni, bloqueamos el select de pais dejando predeterminado España.
	    if  ( (document.forms[formu].tipo_documentacion.value=="C") || (document.forms[formu].tipo_documentacion.value == "D") ) 
	    	{
	    	  //alert('esp');
	    	  document.forms[formu].dni_peregrino.value = document.forms[formu].dni_peregrino.value.substr(0,9);
			  document.forms[formu].dni_peregrino.setAttribute('maxlength', 9);	    	
	    	 	   
	    	  document.forms[formu].pais_peregrino.options.length = 0;
		     //alert("tam "+document.forms[formu].pais_peregrino.options.length);
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
				echo"document.forms[formu].pais_peregrino.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";			
							 
			  }
			 			 
			  ?>	
			 			 			    
			  //procedemos a activar los campos relacionados
			  
			  
		 	  	if ( document.forms[formu].cp_peregrino.disabled)
			 	{
				  document.forms[formu].cp_peregrino.disabled = false;
				  document.forms[formu].localidad_peregrino.disabled = false;
				  document.forms[formu].provincia_peregrino.disabled = false;
				  document.forms[formu].direccion_peregrino.disabled = false;
				}
			  
			 		  
			}
		else 
		{		    
			document.forms[formu].dni_peregrino.setAttribute('maxlength', 30);			
		}	
		if (document.forms[formu].tipo_documentacion.value=="I")
		{
		 	
		    document.forms[formu].pais_peregrino.options.length = 0;
		     //alert("tam "+document.forms[formu].pais_peregrino.options.length);
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
				echo "document.forms[formu].pais_peregrino.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";								 
			  }
			
			?>	
			 
		
			
				//procedemos a vaciar los campos relacionados
				document.forms[formu].cp_peregrino.value = "";
				document.forms[formu].localidad_peregrino.value = "";
				document.forms[formu].provincia_peregrino.value = "";
				document.forms[formu].direccion_peregrino.value = "";
				//procedemos a desactivar los campos relacionados
				document.forms[formu].cp_peregrino.disabled = true;
				document.forms[formu].localidad_peregrino.disabled = true;
				document.forms[formu].provincia_peregrino.disabled = true;
				document.forms[formu].direccion_peregrino.disabled = true;
						
		}
		
		else if  ( (document.forms[formu].tipo_documentacion.value=="N") || (document.forms[formu].tipo_documentacion.value=="X")     )
		{		 
		   
		    //en este caso el pais seleccionado no puede ser españa
		    document.forms[formu].pais_peregrino.options.length = 0;
		     //alert("tam "+document.forms[formu].pais_peregrino.options.length);
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
				echo"document.forms[formu].pais_peregrino.options[".$i."] = new Option('".$pais."','".$fila['Id_Pais']."');";	
							 
			  }			  			  
			  
			?>
		  		    
		  	
				//procedemos a vaciar los campos relacionados
				document.forms[formu].cp_peregrino.value = "";
				document.forms[formu].localidad_peregrino.value = "";
				document.forms[formu].provincia_peregrino.value = "";
				document.forms[formu].direccion_peregrino.value = "";
				//procedemos a desactivar los campos relacionados
				document.forms[formu].cp_peregrino.disabled = true;
				document.forms[formu].localidad_peregrino.disabled = true;
				document.forms[formu].provincia_peregrino.disabled = true;
				document.forms[formu].direccion_peregrino.disabled = true;
			
			
		} 	
		
				poner_pais(formu,selected_js);	
	}
	
		//AL: Funcion que selecciona el pais que estaba seleccionado
	function poner_pais(formu, valor){
		if (<?php if (isset($_POST['pais_peregrino']) && $_POST['pais_peregrino'] != ""){echo "true";}else{echo "false";}?>){
			var valor_final = '<?php echo $_POST['pais_peregrino'];?>';
		}else{
			var valor_final = valor;
		}
		for (var i=0;i<document.forms[formu].pais_peregrino.length;i++){
			if (document.forms[formu].pais_peregrino.options[i].value == valor_final){
			   document.forms[formu].pais_peregrino.options[i].selected = true;
			}
		}
	}

	//Función que cambia el dia del calendario de la cabecera.
	function cambiar_dia(dia,mes,anio){
		if(document.forms[0].dia_fecha_llegada_peregrino.value != document.forms[0].ant_dia_fecha_llegada.value || document.forms[0].mes_fecha_llegada_peregrino.value != document.forms[0].ant_mes_fecha_llegada.value || document.forms[0].anyo_fecha_llegada_peregrino.value != document.forms[0].ant_anyo_fecha_llegada.value){
			if(document.forms[0].sub.value == "false"){
				document.forms[0].sub.value = "true";
			}
			var fecha_calen =  dia+"-"+mes+"-"+anio;
			document.forms[0].fecha_cal.value = fecha_calen;			
			document.forms[0].submit();
		}
	}

	function prepara_cambiar_dia(){
		document.forms[0].ant_dia_fecha_llegada.value = parseInt(document.forms[0].dia_fecha_llegada_peregrino.value)-1;
		document.forms[0].ant_mes_fecha_llegada.value = parseInt(document.forms[0].mes_fecha_llegada_peregrino.value)-1;
		document.forms[0].ant_anyo_fecha_llegada.value = parseInt(document.forms[0].anyo_fecha_llegada_peregrino.value)-1;
	}
</script>

<link rel="stylesheet" type="text/css" href="css/formulario_alb_per.css">
<?
//Array de meses en Texto para utilizar en las fechas
$meses = array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

//Incluimos una pagina que asigna las variables de sesion y otra con funciones que van a ser utilizadas en esta seccion
include('paginas/gdh_session.php');
include('paginas/funciones_alb_per.php');

//-----------------------------------------
$sql_tipo_hab ="select * from tipo_habitacion where Reservable='N'";
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
		//print("mostrar".$numero_paginas[$j]);
		
	}
	}}}//print(count($numero_paginas));
//Consulta para saber la pagina actual
if (isset($_POST['pagina_habitaciones'])){
  		//print("siempre");
  		//print("paginah=".$_POST['pagina_habitaciones']);
		$_SESSION['gdh']['dis_hab']['num_pag'] = $_POST['pagina_habitaciones'];
		$pagina_habitaciones = $_POST['pagina_habitaciones'];
	
	
}else{
  
  	if (IN_ARRAY($_SESSION['gdh']['dis_hab']['num_pag'],$numero_paginas)){
    
	$pagina_habitaciones = $_SESSION['gdh']['dis_hab']['num_pag'];//print("bien");
	} else {
	  			$pagina_habitaciones=$numero_paginas[0];
				$_SESSION['gdh']['dis_hab']['num_pag']=$numero_paginas[0];//print("mal");
			  
			}
			
}

//----------------------
$habitaciones_orden = array();






//------------------------










for ($i = 0; $i < COUNT($_SESSION['pag_hab']); $i++) {

	if ($_SESSION['pag_hab'][$i]['pagina'] == $_SESSION['gdh']['dis_hab']['num_pag']) {
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


?>
	<?
		//conexión a la base de datos	
		// Cuidado con el orden!!.
		@$db2 =  @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']); 
		@$db  =  @$db=mysql_pconnect($_SESSION['conexion']['host'],$_SESSION['conexion']['user'],$_SESSION['conexion']['pass']); 

		


		// Base de datos con metadata de todas las bases de datos, de aqui obtendremos el default de algunas tablas.
		@mysql_select_db("information_schema",$db2);
		mysql_select_db($_SESSION['conexion']['db']);		
		//En caso de no poder realizar la conexión se muestra un mensaje y se vuelve a la página principal
		if ( (!$db) || (!$db2) ){
			echo "<script>Alert('No se ha podido realizar la conexion con la base de datos')</script>";
		}else{
			//Se comprueba que se haya realizado una petición de alta, modificación o baja
			if(isset($_POST['accion']) && $_POST['accion']!=""){		
				$dni = $_POST['dni_peregrino'];				
				//Se prepara la fecha en el formato adecuado para introducirla en la base de datos	
				$fecha = $_POST['anyo_fecha_peregrino']."-".$_POST['mes_fecha_peregrino']."-".$_POST['dia_fecha_peregrino'];
				if($_POST['accion'] == "alta" && isset($_POST['sub']) && $_POST['sub'] == "false"){			
					
					// ALTA DE peregrino --- ALTA DE peregrino     Construcción y ejecución de consultas

					$fecha_nacimiento = $_POST['anyo_fecha_peregrino']."-".$_POST['mes_fecha_peregrino']."-".$_POST['dia_fecha_peregrino'];			
					$fecha_llegada = $_POST['anyo_fecha_llegada_peregrino']."-".$_POST['mes_fecha_llegada_peregrino']."-".$_POST['dia_fecha_llegada_peregrino'];
					$fecha_salida = establecer_fecha($fecha_llegada, $_POST['pernocta_peregrino']);
					if(isset($_POST['existe']) && $_POST['existe'] == "true"){
						$sql_cliente = "update cliente set ";
						if($dni != $_POST['dni_ant_peregrino']){
							$sql_pernocta = "update pernocta_p set DNI_Cl = '".$dni."' where DNI_Cl like '".$_POST['dni_ant_peregrino']."' ";
							mysql_query($sql_pernocta);
							$sql_cliente .= "DNI_Cl = '".$dni."' , ";
						}
						$sql_cliente .= " Nombre_Cl = '".$_POST['nombre_peregrino']."' , Apellido1_Cl = '".$_POST['apellido1_peregrino']."' , Apellido2_Cl = '".$_POST['apellido2_peregrino']."' , Direccion_Cl = '".$_POST['direccion_peregrino']."' , CP_Cl ='".$_POST['cp_peregrino']."' , Localidad_Cl = '".$_POST['localidad_peregrino']."' , Id_Pro = ";						
						if($_POST['provincia_peregrino'] == ""){
							$sql_cliente .= "NULL , ";
						}else{
							$sql_cliente .= "'".$_POST['provincia_peregrino']."' , ";
						}
						$sql_cliente .= "Id_Pais = '".$_POST['pais_peregrino']."' , Fecha_Nacimiento_Cl = '".$fecha."' , Sexo_Cl = '".$_POST['sexo_peregrino']."' , Tfno_Cl = '".$_POST['telefono_peregrino']."' , Observaciones_Cl = '".$_POST['observaciones_peregrino']."' , Email_Cl = '".$_POST['email_peregrino']."', Tipo_documentacion='".$_POST['tipo_documentacion']."', Fecha_Expedicion='".$_POST['anyo_fecha_expedicion']."-".$_POST['mes_fecha_expedicion']."-".$_POST['dia_fecha_expedicion']."' where DNI_Cl like '".$_POST['dni_ant_peregrino']."'";

						$sql_estancia = "insert into pernocta_p (DNI_Cl,Id_Hab,Fecha_Llegada,Fecha_Salida,PerNocta,Noches_Pagadas,M_P,Lesion";
						if(isset($_POST['servicio_peregrino']) && trim($_POST['servicio_peregrino'])!=""){
							$sql_estancia.=", Id_Servicios ";	
						}
						$sql_estancia.=") values('".$dni."' , '".$_POST['id_habitacion']."', '".$fecha_llegada."' , '".$fecha_salida."' , ".$_POST['pernocta_peregrino']." , ".$_POST['noches_pagadas']." ,  '".$_POST['modo_peregrinacion']."' , ";
						if($_POST['lesionado'] == ""){
							$sql_estancia .= " NULL ";
						}else{
							$sql_estancia .= "'".$_POST['lesionado']."'";
						}
						if(isset($_POST['servicio_peregrino']) && trim($_POST['servicio_peregrino'])!=""){
							$sql_estancia.=", '".$_POST['servicio_peregrino']."'";	
						}
						$sql_estancia.=");";
					}else{
						$sql_cliente = "insert into cliente (DNI_Cl ,Nombre_Cl,Apellido1_Cl,Apellido2_Cl,Direccion_Cl,CP_Cl,Localidad_Cl,Id_Pro,Id_Pais,Fecha_Nacimiento_Cl, Sexo_Cl,Tfno_Cl,Observaciones_Cl,Email_Cl,Tipo_documentacion,Fecha_Expedicion) values('".$dni."' ,'".$_POST['nombre_peregrino']."' , '".$_POST['apellido1_peregrino']."' , '".$_POST['apellido2_peregrino']."' ,'".$_POST['direccion_peregrino']."' , '".$_POST['cp_peregrino']."' ,'".$_POST['localidad_peregrino']."' , ";						
						if($_POST['provincia_peregrino'] == ""){
							$sql_cliente .= "NULL , ";
						}else{
							$sql_cliente .= "'".$_POST['provincia_peregrino']."' ,";
						}
						$sql_cliente .= "'".$_POST['pais_peregrino']."' , '".$fecha."' ,  '".$_POST['sexo_peregrino']."' , '".$_POST['telefono_peregrino']."' , '".$_POST['observaciones_peregrino']."' , '".$_POST['email_peregrino']."' , '".$_POST['tipo_documentacion']."', '".$_POST['anyo_fecha_expedicion']."-".$_POST['mes_fecha_expedicion']."-".$_POST['dia_fecha_expedicion']."')";

						$sql_estancia = "insert into pernocta_p (DNI_Cl,Id_Hab,Fecha_Llegada,Fecha_Salida,PerNocta,Noches_Pagadas,M_P,Lesion";
						if(isset($_POST['servicio_peregrino']) && trim($_POST['servicio_peregrino'])!=""){
							$sql_estancia.=", Id_Servicios ";	
						}
						$sql_estancia.=") values('".$dni."' , '".$_POST['id_habitacion']."', '".$fecha_llegada."' , '".$fecha_salida."' , ".$_POST['pernocta_peregrino']." , ".$_POST['noches_pagadas']." , '".$_POST['modo_peregrinacion']."' , ";
						if($_POST['lesionado'] == ""){
							$sql_estancia .= " NULL ";
						}else{
							$sql_estancia .= " '".$_POST['lesionado']."' ";
						}
						if(isset($_POST['servicio_peregrino']) && trim($_POST['servicio_peregrino'])!=""){
							$sql_estancia.=", '".$_POST['servicio_peregrino']."'";	
						}
						$sql_estancia .= ");";
					}
					if(mysql_query($sql_cliente) && mysql_query($sql_estancia)){
						$direccion = "?pag=gdh.php&facturar=true&dni=".$dni."&fecha_llegada=".$fecha_llegada;												
						echo "<script>;location.href='".$direccion."';</script>";
					}else{
						echo "<script>alert(\"No se ha podido registrar la estancia. Compruebe que el cliente no está realizando otra estancia actualmente.\");</script>";
					}
					$_POST = Array();
				}else{	
					if($_POST['accion'] == "mod"){

						// Modificación de  peregrino -- Construcción y ejecución de consultas

						$sql_update = "update cliente set DNI_Cl='".$dni."' , Nombre_Cl = '".$_POST['nombre_peregrino']."', Apellido1_Cl='".$_POST['apellido1_peregrino']."',Apellido2_Cl='".$_POST['apellido2_peregrino']."',";
						if($_POST['direccion_peregrino']==""){
							$sql_update.="Direccion_Cl =NULL,";	
						}else{
							$sql_update.="Direccion_Cl ='".$_POST['direccion_peregrino']."',";	
						}
						if($_POST['cp_peregrino']==""){
							$sql_update.="CP_Cl=NULL,";
						}else{
							$sql_update.="CP_Cl='".$_POST['cp_peregrino']."',";	
						}
						if($_POST['localidad_peregrino']==""){
							$sql_update.="Localidad_Cl=NULL, ";
						}else{
							$sql_update.="Localidad_Cl='".$_POST['localidad_peregrino']."', ";	
						}
						if($_POST['provincia_peregrino']==""){
							$sql_update.="Id_Pro=NULL,";
						}else{
							$sql_update.="Id_Pro='".$_POST['provincia_peregrino']."',";
						}
						  $sql_update.="Id_Pais ='".$_POST['pais_peregrino']."', Tipo_documentacion = '".$_POST['tipo_documentacion']."', Fecha_Nacimiento_Cl='".$fecha."',Sexo_Cl ='".$_POST['sexo_peregrino']."', Tfno_Cl='".$_POST['telefono_peregrino']."',Observaciones_Cl='".$_POST['observaciones_peregrino']."',Email_Cl='".$_POST['email_peregrino']."', Tipo_Documentacion='".$_POST['tipo_documentacion']."', Fecha_Expedicion='".$_POST['anyo_fecha_expedicion']."-".$_POST['mes_fecha_expedicion']."-".$_POST['dia_fecha_expedicion']."' where DNI_Cl like '".$_POST['dni_ant_peregrino']."'";
						if(mysql_query($sql_update)){
						
						}else{
							echo "<script>alert('La modificación no se ha podido realizar correctamente.')</script>";
						}
						if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
							echo "<script>location.href='index.php?pag=listado_criterio_peregrinos.php'</script>";
						}
						$_POST = Array();
					}else{

						// Baja de peregrino   --  Construcción y ejecución de consultas

						if($_POST['accion'] == "baja"){
							$sql_comprueba = "select * from pernocta_p where DNI_Cl like '".$_POST['dni_peregrino']."' and Fecha_Salida > '".date("Y-m-d")."'";
							$result_pernocta = mysql_query($sql_comprueba);
							$result_count = mysql_query("select * from pernocta_p where DNI_Cl like '".$_POST['dni_peregrino']."'");
							if(mysql_num_rows($result_count)>1){
								$mensaje="No se puede eliminar al peregrino, consta en mas de una estancia en el albergue";
							}else{							
								$fila_pernocta = mysql_fetch_array($result_pernocta);
								if($fila_pernocta['Fecha_Salida']>=date("Y-m-d")){
									$sql_baja_pernocta = "delete from pernocta_p where DNI_Cl like '".$_POST['dni_peregrino']."'";					
									$sql_baja = "delete from cliente where DNI_Cl like '".$_POST['dni_peregrino']."'";
									if(mysql_query($sql_baja_pernocta))									{
										if(mysql_query($sql_baja)){
											$mensaje = "";
										}else{
											$mensaje = "No se ha podido eliminar el peregrino.";
										}
									}else{
										$mensaje = "No se ha podido eliminar la estancia, por tanto tampoco se ha podido eliminar el peregrino.";
									}
								}else{
									$mensaje="No se puede eliminar al peregrino, actualmente no se encuentra realizando una estancia .";
								}								
							}
							if ($mensaje!="") echo "<script>alert('".$mensaje."')</script>";
							if(isset($_POST['busqueda']) && $_POST['busqueda'] == "si"){
								echo "<script>location.href='index.php?pag=listado_criterio_peregrinos.php'</script>";
							}		
							$_POST = Array();
						}
					}
				}
			}
			
			//Se establece la fecha de calendario
			if(isset($_GET['fecha_cal']) && trim($_GET['fecha_cal']) != ""){
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
				//print($fecha_calendario);	 
				//Si no se ha enviado de ninguna de las dos maneras anteriores, y en caso de que se hayan enviado por formulario los valores de la fecha de llegada, se usará ésta como fecha de calendario.
			}else if(isset($_POST['dia_fecha_llegada_peregrino']) && $_POST['dia_fecha_llegada_peregrino']!="" && isset($_POST['mes_fecha_llegada_peregrino']) && $_POST['mes_fecha_llegada_peregrino']!="" && isset($_POST['anyo_fecha_llegada_peregrino']) && $_POST['anyo_fecha_llegada_peregrino']!=""){
					$fecha_calendario = trim($_POST['anyo_fecha_llegada_peregrino'])."-".trim($_POST['mes_fecha_llegada_peregrino'])."-".trim($_POST['dia_fecha_llegada_peregrino']);					
			}else{
				//Si no se diera ninguno de los casos anteriores, se le asignará la fecha de sistema
				$fecha_calendario = date("Y-m-d");
			}
			
			//Construcción de consulta para el listado de peregrino
			$sql_inicial = "select * from cliente where dni_cl in (select dni_cl from pernocta_p)";
			
			//Se comprueba que se haya pedido orden por algún campo del listado, ascendente o descendente
			if(isset($_GET['ord']) && $_GET['ord']!=""){
					if($_SESSION['ord']['anterior'] == $_GET['ord']){
						if(isset($_GET['orden2'])){
							$orden2 = $_GET['orden2'];
						}else{
							if($_SESSION['orden2'] == "desc"){
								$orden2 = "asc";
							}else{
								$orden2 = "desc";
							}						
						}
					}else{
						$orden2 = "asc";	
					}
				$sql_inicial .= " order by ".$_GET['ord']." ".$orden2.";";
			}else{
				$orden2 = "1";
			}
			if(!$result = mysql_query($sql_inicial)){
				$sw_listado = false;
				echo "<br><br><label class=\"label_formulario\">No se ha podido conectar a la base de datos.</label>";
			}else{
				$sw_listado = true;
				$_SESSION['ord']['anterior'] = $_GET['ord'];
				$_SESSION['orden2'] = $orden2;
				$sql_provincias = "select * from provincia ORDER BY Nombre_Pro";
				$result_provincias = mysql_query($sql_provincias);
				while($fila_provincias = mysql_fetch_array($result_provincias)){					
					$provincias[$fila_provincias['Id_Pro']] = $fila_provincias['Nombre_Pro'];
				}
				$sql_paises = "select * from pais order by Nombre_Pais asc";
				$result_paises = mysql_query($sql_paises);			
				while($fila_paises = mysql_fetch_array($result_paises)){				
					$id = $fila_paises['Id_Pais'];
					$paises[$id] = $fila_paises['Nombre_Pais'];
				}

	?>
<div id="caja_superior">
	<div id="caja_superior_izquierda">

			<?
			//Se incluye la  página correspondiente a la acción solicitada
			if(!isset( $_GET['dni']) || $_GET['dni'] ==""){
				include("peregrinos_alta.php");
			}else{
				if(isset($_GET['modificar']) && $_GET['modificar'] == "si" ){
					include("peregrinos_modificacion.php")	;		
				}else 	if(isset($_GET['detalles']) && $_GET['detalles']=="si" ){
					include("peregrinos_detalles.php");
				}else	if(isset($_GET['eliminar']) && $_GET['eliminar']=="si"){
					include("peregrinos_baja.php");			
				}
			}
			if(isset($_POST['dia_fecha_llegada_peregrino']) && $_POST['dia_fecha_llegada_peregrino'] != ""){
				if(trim($_POST['anyo_fecha_llegada_peregrino'])."-".trim($_POST['mes_fecha_llegada_peregrino'])."-".trim($_POST['dia_fecha_llegada_peregrino']) != $fecha_calendario){
					
					echo "<script>prepara_cambiar_dia();cambiar_dia(".$_POST['dia_fecha_llegada_peregrino'].",".$_POST['mes_fecha_llegada_peregrino'].",".$_POST['anyo_fecha_llegada_peregrino'].");</script>";
					$_SESSION['gdh']['anio'] = trim($_POST['anyo_fecha_llegada_peregrino']);
					$_SESSION['gdh']['mes'] = trim($_POST['mes_fecha_llegada_peregrino']);
					$_SESSION['gdh']['dia'] = trim($_POST['dia_fecha_llegada_peregrino']);
					$_SESSION['gdh']['anio'] = trim($_POST['anyo_fecha_llegada_peregrino']);
					$_SESSION['gdh']['mes'] = trim($_POST['mes_fecha_llegada_peregrino']);
					$_SESSION['gdh']['dia'] = trim($_POST['dia_fecha_llegada_peregrino']);
				}
			}else{
				if(trim($_SESSION['gdh']['anio'])."-".trim($_SESSION['gdh']['mes'])."-".trim($_SESSION['gdh']['dia']) != date("Y-m-d")){
					$_SESSION['gdh']['anio'] = trim(date("Y"));
					$_SESSION['gdh']['mes'] = trim(date("m"));
					$_SESSION['gdh']['dia'] = trim(date("d"));
					echo "<script>prepara_cambiar_dia();cambiar_dia(".$_SESSION['gdh']['dia'].",".$_SESSION['gdh']['mes'].",".$_SESSION['gdh']['anio'].");</script>";
				}
			}
		?>
	</div>
	<?
	
?>
	<div id="caja_superior_derecha">		
	<div id="caja_superior_derecha_a" style="display:none;">
								<div id="caja_habitaciones" style="display:block;">
										<?php
									if(isset($_POST['sub']) && $_POST['sub'] == "true"){
										$fecha_llegada = $_POST['anyo_fecha_llegada_peregrino']."-".$_POST['mes_fecha_llegada_peregrino']."-".$_POST['dia_fecha_llegada_peregrino'];
										$fecha_salida = establecer_fecha($fecha_llegada, $_POST['pernocta_peregrino']);

										//DISTRIBUCIÓN DE HABITACIONES

										//Se Almacenan en un array los datos de todas las habitaciones existentes;										
										$habita=array();
																			
										//Numero de camas que tiene la habitación mas grande									
										$max_camas = $_SESSION['gdh']['dis_hab']['configuracion_numero_camas'];	
										//$max_camas = 8;								
										$qry_dist = "select * from habitacion";
										// recorre la matriz de sesión que contiene las distintas páginas.											
										$primer = false;							
										for($i=0;$i<count($_SESSION['pag_hab']);$i++)
										{
										 		// Si la página actual coincide con la de la fila...
												if($_SESSION['pag_hab'][$i]['pagina'] == $pagina_habitaciones)
												{
												   
													if($primer)
													{
														$qry_dist .=" or Id_Tipo_Hab = ".$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']." ";
														
													}
													else
													{
														$qry_dist .=" where Id_Tipo_Hab = ".$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']." ";
														$primer = true;
													}
												}
										}
									
										$qry_dist .= " order by Id_Hab ";		
										$qry_dist = "SELECT * FROM habitacion inner join tipo_habitacion 
												     WHERE (habitacion.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab 
										             AND tipo_habitacion.reservable = 'n') ORDER BY Id_Hab";
										 //print($fecha_calendario);           
										$qry_dist="SELECT * FROM tipo_habitacion INNER JOIN (SELECT consulta2.Fecha,consulta2.Id_Hab,consulta2.Id_Tipo_Hab,habitacion.Camas_Hab FROM habitacion INNER JOIN (SELECT cambio_tipo_habitacion.Id_Tipo_Hab,consulta.Id_Hab,consulta.Fecha FROM cambio_tipo_habitacion INNER JOIN (SELECT Id_Hab, MAX(Fecha) as Fecha FROM cambio_tipo_habitacion WHERE Fecha <='".$fecha_calendario."' GROUP BY Id_Hab) AS consulta ON cambio_tipo_habitacion.Fecha=consulta.Fecha AND cambio_tipo_habitacion.Id_Hab=consulta.Id_Hab) AS consulta2 ON habitacion.Id_Hab=consulta2.Id_Hab) AS consulta3 ON consulta3.Id_Tipo_Hab=tipo_habitacion.Id_Tipo_Hab WHERE tipo_habitacion.reservable LIKE 'N'";
										
										if($res_dist=mysql_query($qry_dist)){
											$cont=0;
											$tipo_Hab=array();
											$max_tipo = 0;
											
											// Consultas para obtener la totalidad de estancias que se realizan en el mismo periodo de tiempo que
											// la estancia

											$sql_hab_reserva = "select * from reserva where DNI_PRA in (select DNI_PRA from detalles where !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida < '".$fecha_llegada."') and !(Fecha_Llegada > '".$fecha_salida."' and Fecha_Salida > '".$fecha_salida."')) and Fecha_Llegada in (select Fecha_Llegada from detalles where !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida < '".$fecha_llegada."') and !(Fecha_Llegada > '".$fecha_salida."' and Fecha_Salida > '".$fecha_salida."')) order by Id_Hab ";

											$sql_hab_pernocta = "select * from pernocta where !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida <= '".$fecha_llegada."') and !(Fecha_Llegada > '".$fecha_salida."' and Fecha_Salida > '".$fecha_salida."') order by Id_Hab";
											
											$sql_hab_pernocta_p = "select * from pernocta_p where !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida <= '".$fecha_llegada."') and !(Fecha_Llegada > '".$fecha_llegada."' and Fecha_Salida > '".$fecha_salida."') order by Id_Hab";
											
											$sql_hab_pernocta_gr = "select pernocta_gr.Nombre_gr,  Id_Hab,pernocta_gr.Num_Personas,estancia_gr.Id_Color, pernocta_gr.fecha_llegada from pernocta_gr,estancia_gr where pernocta_gr.Nombre_gr like estancia_gr.Nombre_gr and pernocta_gr.Nombre_gr in (select Nombre_gr from Estancia_gr where  !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida < '".$fecha_llegada."') and !(Fecha_Llegada > '".$fecha_salida."' and Fecha_Salida > '".$fecha_salida."')) and estancia_gr.Fecha_Llegada in (select Fecha_Llegada  from Estancia_gr where !(Fecha_Llegada < '".$fecha_llegada."' and Fecha_Salida < '".$fecha_llegada."') and !(Fecha_Llegada > '".$fecha_salida."' and Fecha_Salida > '".$fecha_salida."') and Fecha_Salida <> '".$fecha_llegada."') order by Id_Hab";
																						
											$cont = 0;
											$tipo_Hab = array();	
											for ($i=0;$i<mysql_num_rows($res_dist);$i++)
											{											
												$tupla_dist=mysql_fetch_array($res_dist);
												if($tupla_dist['Camas_Hab']>0)
												{
													
													$lenght = $i;
													$habita[$i]['orden']    = intval($tupla_dist['Id_Hab']);
													$habita[$i]['id']       = $tupla_dist['Id_Hab'];												
													$habita[$i]['tipo']     = $tupla_dist['Id_Tipo_Hab'];												
													$habita[$i]['camas']    = $tupla_dist['Camas_Hab'];
													// Camas que faltán para la habitación.
													$habita[$i]['camas_res'] = $tupla_dist['Camas_Hab'];
													$habita[$i]['ocupadas'] = 0;
													// Añado el nombre.
													$habita[$i]['nombre'] =  $tupla_dist['Nombre_Tipo_Hab'];													
												
													//echo "-".$habita[$i]['nombre']." ".$habita[$i]['id']." ".$habita[$i]['camas']."<br>";
													
													$_SESSION['dist_hab'][$i]['camas_restantes'] = $habita[$i]['camas'];
													if($habita[$i]['tipo'] > $max_tipo){
														$max_tipo = $habita[$i]['tipo'];
													}
													
													for($j=0;$j<count($_SESSION['pag_hab']);$j++)
													{														
															if($pagina_habitaciones == $_SESSION['pag_hab'][$j]['pagina'] && 
															   $habita[$i]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab'] )
															   {	
																	$habita[$i]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];
																	/*
																	if ($tupla_dist['Camas_Hab']>$max_camas)
																	{
																		$max_camas=$tupla_dist['Camas_Hab'];
																	}
																	*/
																	break;
															   }
															   else
															   {
																	if($habita[$i]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab'])
																	{
																		$habita[$i]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];
																		break;
																	}
															    }
													}												
													if ($tupla_dist['Camas_Hab'] > $max_camas) 
													{													 												
													
														$resto = $tupla_dist['Camas_Hab'] % $max_camas;
														$habita[$i]['Columnas_Necesarias'] = 
															intval($tupla_dist['Camas_Hab'] / $max_camas);	
	
														//Se comprueba si la habitación precisa ser mostrada en varias columnas
														
														if($habita[$lenght]['Columnas_Necesarias'] > 1)
														{
															for($col=0;$col<=$habita[$lenght]['Columnas_Necesarias'];$col++)
															{															
																if($col == $habita[$lenght]['Columnas_Necesarias'])
																{
																	$habita[$lenght]['camas_col'][$col] = 
																		intval($habita[$lenght]['camas'] - ($max_camas*$col));
																}
																else
																{
																	$habita[$lenght]['camas_col'][$col] = $max_camas;
																}
															}
															//echo "@".$habita[$lenght]['camas_col'][$col];
														}
														
														if ($resto > 0) 
														{		
														    											    
															$habita[$lenght]['Columnas_Necesarias']++;																										
														}
														//echo "@".$habita[$lenght]['camas_col'][$col];
														//echo $habita[$i]['Columnas_Necesarias']." ";
												        //echo "-".$habita[$i]['nombre']." ".$habita[$i]['id']." ".$habita[$i]['camas']."<br>";
												        
													}
													else
													{
														$habita[$lenght]['Columnas_Necesarias'] = 1;
														$habita[$lenght]['camas_col'][0] = $max_camas;
														
														
													}

												//Se introduce en $habita la totalidad de estancias que se realizan en el mismo periodo de tiempo que va a durar la estancia
												$result_reserva = mysql_query($sql_hab_reserva);			
												for($j=0;$j<mysql_num_rows($result_reserva);$j++){
													$fila = mysql_fetch_array($result_reserva);
													if($fila['Id_Hab'] == $habita[$i]['id']){
														$habita[$i]['reservas'] = $habita[$i]['reservas'] + $fila['Num_Camas'];
													}											
												}										
												$result_pernocta = mysql_query($sql_hab_pernocta);
												while($fila = mysql_fetch_array($result_pernocta)){
													if($fila['Id_Hab'] == $habita[$i]['id']){
														$habita[$i]['alb']['cantidad']++;
														if($fila['Fecha_Llegada'] > $fecha_llegada && $fila['Fecha_Llegada'] < $fecha_salida){
															if(!isset($habita[$i]['alb']['temporal']['cantidad']) || $habita[$i]['alb']['temporal']['cantidad'] <= 0){$habita[$i]['alb']['temporal']['cantidad']=0;}
															$habita[$i]['alb']['temporal']['cantidad']++;
															$habita[$i]['alb']['temporal']['fecha'][$habita[$i]['alb']['temporal']['cantidad']] = $fila['Fecha_Llegada'];
															
														}
													}											
												}
												$result_pernocta_p = mysql_query($sql_hab_pernocta_p);
												while($fila = mysql_fetch_array($result_pernocta_p)){													
													if($fila['Id_Hab'] == $habita[$i]['id']){
														$habita[$i]['per']['cantidad']++;													
													}											
												}
												$result_pernocta_gr = mysql_query($sql_hab_pernocta_gr);
												while($fila = mysql_fetch_array($result_pernocta_gr)){													
													if($fila['Id_Hab'] == $habita[$i]['id']){
														$habita[$i]['gr']['cantidad'] = $habita[$i]['gr']['cantidad'] + $fila['Num_Personas'];
														$habita[$i]['color'] = $fila['Id_Color'];
														if($fila['Fecha_Llegada'] > $fecha_llegada){
															if(!isset($habita[$i]['gr']['temporal']['cantidad']) || $habita[$i]['gr']['temporal']['cantidad'] < 0){$habita[$i]['gr']['temporal']['cantidad']=0;}
															$habita[$i]['gr']['temporal']['cantidad'] = $fila['Num_Personas'];
															for($f=0;$f < $fila['Num_Personas'];$f++){
																$habita[$i]['gr']['temporal']['fecha'][$f] = $fila['Fecha_Llegada'];							
															}
														}
													}											
												}
													for ($s=0;$s<count($tipo_Hab);$s){
														if($tupla_dist['Id_Tipo_Hab']!=$tipo_Hab[$s]){
																$tipo_Hab[$cont]=$tupla_dist['Id_Tipo_Hab'];$cont++;
														}
													}
												}
											}
											
											$sql_tipo_hab ="select * from tipo_habitacion";
											$result_tipo_hab = mysql_query($sql_tipo_hab);
											while($fila_tipo_hab = mysql_fetch_array($result_tipo_hab)){
												$tipo_habitacion[$fila_tipo_hab['Id_Tipo_Hab']] = $fila_tipo_hab['Nombre_Tipo_Hab'];
											}
											foreach ($habita as $key => $row){
												$orden_al[$key]  = $row['orden'];
												$tipo[$key] = $row['tipo'];
											}
											
											// Comprobamos que existen habitaciones en la base de datos.
											if((!is_null($habita) || count($habita) > 0) && !is_null($orden_al) && !is_null($tipo))
											{											   
												if(array_multisort ($tipo, SORT_ASC ,$orden_al, SORT_ASC,$habita))
												{	
													$numero_paginas_aux=Array();
												
													for($i_pag=0;$i_pag<count($habita);$i_pag++)
													{
														$sw_pag = true;
														for($b_pag=0;$b_pag<count($numero_paginas_aux);$b_pag++) 
														{
															if(intval($habita[$i_pag]['pagina']) == intval($numero_paginas_aux[$b_pag]))
															{
																$sw_pag = false;	
															}
														}
														if($sw_pag)
														{
															$numero_paginas_aux[] = intval($habita[$i_pag]['pagina']);															
														}	
													}
										
													//echo "<br>en 1463 habita = ".count($habita);
													/*for ($i = 0; $i < COUNT($_SESSION['pag_hab']); $i++) 
													{
													  	if (!IN_ARRAY($_SESSION['pag_hab'][$i]['pagina'],$numero_paginas_aux)) 
														{
														    $numero_paginas_aux[] = $_SESSION['pag_hab'][$i]['pagina'];
														}
													  	if ($_SESSION['pag_hab'][$i]['pagina'] == $_SESSION['gdh']['dis_hab']['num_pag']) 
														{

													  	  	$cont = COUNT($habitaciones_orden);
														    $habita[$cont]['orden'] = $_SESSION['pag_hab'][$i]['orden'];
														    $habita[$cont]['Id_Tipo_Hab'] = $_SESSION['pag_hab'][$i]['Id_Tipo_Hab'];
														}
													}
													echo "<br>en 1478 habita = ".count($habita);
													if ($_SESSION['gdh']['dis_hab']['num_pag'] > COUNT($numero_paginas))
													{														 
														//$_SESSION['gdh']['dis_hab']['num_pag'] = 1;
													}
													
													foreach ($habita as $llave => $fila) 
													{
													   $orden[$llave]  = $fila['orden'];
													}
														
													if (COUNT($habita) > 0) 
													{
														@ ARRAY_MULTISORT($orden, SORT_DESC, $habita);
													}*/	
												
														
									?>	
									<?//print(count($numero_paginas));
										for ($i = 0; $i < COUNT($numero_paginas); $i++) {
											  //print("pagina num ".$numero_paginas[$i]);
											  }
											  
									?>
									<table border="0"  align="center" width="680px" cellpadding="0" cellspacing="0" 
									        style="padding:0px 0px 0px 0px;">
									
									<tr style="padding:0px 0px 0px 0px;">
							
									<td colspan="9" align="center" style="padding:0px 0px 0px 0px;">
										<div class='champi_izquierda'>&nbsp;</div>
										<div class='champi_centro' style="width:620px;background-repeat:repeat-x;float:left;"> 
										<div class="titulo" style="width:620px;text-align:center;">Distribución de Habitaciones
										
										<!-- ####    Selección de Ventana #### -->										
										<select name="pagina_habitaciones" class="select_formulario" style="margin-left:50px;" 
											    onChange="document.forms[0].sub.value='true';document.forms[0].submit();" >
										<?
										
										
										//print(count($numero_paginas));
											for ($i = 0; $i < COUNT($numero_paginas); $i++) {
											 
	  	if ($numero_paginas[$i] == $_SESSION['gdh']['dis_hab']['num_pag']) {
	  		echo '<option value="'.$numero_paginas[$i].'" selected>Ventana '.$numero_paginas[$i];
	  	}
	  	else {
	  		echo '<option value="'.$numero_paginas[$i].'">Ventana '.$numero_paginas[$i];
		}
	}
										?>
									</select>
									</div>
										</div>
										<div class='champi_derecha'></div>
									<!--  Fin champis  -->							
									</td>								
							
									<tr>	
									<!-- Color azul de fondo -->
									<td class="tabla_detalles" align="center" style="padding:0px 0px 0px 0px;border: 1px solid #3F7BCC;" width="100%"> <!--  id 1  -->
									<!--tabla 1-->
									<table width="100%" height="100%"  align="center" border='0' >
									
									<tr>								
										<td align="center">
					<table  class="tabla_habitaciones" bordercolor="#FFFFFF"  cellpadding="0" cellspacing="1">			
												<tr>
												<td align="center" style="padding:0px 0px 0px 0px;">							
												<table border="0" cellpadding="0" cellspacing="1"  align="center">
													<tr align="center" valign="middle">
																	<td width="2px">
																	</td>

									
												
	<?PHP			

		//Hola, soy al y voy a echar un vistazo a el array habita
		//for ($i=0;$i<count($habita);$i++){
		//	echo "<br> Habita $i: Tipo=".$habita[$i]['tipo'].", Camas=".$habita[$i]['camas'].", Columnas=".$habita[$i]['Columnas_Necesarias'];
		//}

	//Comienza la distribucion de habitaciones	
	
			$separar = $habita[0]['Id_Tipo_Hab'];

			for ($i = 0; $i< count($habitacionesssssss); $i++) {				
				$colspan = 0;
				if ($separar != $habita[$i]['tipo']) {
					$separar = $habita[$i]['tipo'];			
	?>
													<td width="2px" 
													    rowspan="<?PHP echo $max_camas + 3; ?>"
														align="center" class="separar_hab">	
													</td>
	<?PHP
				}
?>
													<td class="nom_tipo_hab" colspan="<?PHP echo $colspan; ?>">
														<?PHP echo $tipo_habitacion[$habita[$i]['tipo']]; ?>
													</td>
	<?PHP					
													$i = $i + $habita[$i]['tipo'] - 1;													
			}	
	?>
													<td width="2px">
													</td>													
												</tr>
												
												<tr align="center" valign="middle">
													<td width="2px">
													</td>

												<!--##    CABEZERA     ##-->
	<?PHP
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

		/*
			$tipo_actual = $habita[0]['tipo'];
			$total_colspan = 0;		
			$sw_titulo = false;
			for ($i = $total_colspan; $i <= count($habita); $i = $total_colspan){	
				$colspan = 0;				
				$cont = 0;
				echo "<br>tipo actual: $tipo_actual, tipo hab($total_colspan)= ".$habita[$total_colspan]['tipo'];
				//echo $pagina_habitaciones." = ".$habita[$i]['pagina']."<br>";
				if($pagina_habitaciones == $habita[$i]['pagina']){
					if($tipo_actual != $habita[$total_colspan]['tipo']){	
					    // separar habitaciones			 
						if($sw_titulo){
							echo "<td rowspan=\"".($_SESSION['gdh']['dis_hab']['configuracion_numero_camas']+3)."\"></td><td width=\"2px\"  class=\"separar_hab\" rowspan=\"".($max_camas+3)."\" >";
							echo "</td><td rowspan=\"".($_SESSION['gdh']['dis_hab']['configuracion_numero_camas']+3)."\"></td>";
								
							$sw_titulo = false;
						}
						$tipo_actual = $habita[$total_colspan]['tipo'];
					}
					else{
						$colspan = 0;	
						for($j=0;$j<count($habita);$j++){
							if($tipo_actual == $habita[$j]['tipo']){
								$colspan += $habita[$j]['Columnas_Necesarias'];								
								$cont = $habita[$j]['Columnas_Necesarias']-1;
							}
						}						
						// TIPO DE HABITACIÓN.
						//echo $colspan;
						echo "<td colspan=".$colspan." class='nom_tipo_hab' >";
						  	  echo $tipo_habitacion[$habita[$i]['tipo']];
					    echo "</td>";
						$sw_titulo = true;				
						$total_colspan += $colspan;
						$total_colspan -= $cont;					
					}
				}
				else{
					$total_colspan++;
				}
			}
			//Muestro el tipo de las ultimas camas
			echo "";
			*/
						
	?>
	 												  <td width="2px"></td>
												  </tr>
									<?		
									        //boolean que indica si hay una habitacion seleccionada.									
											$sw_ocupada= false; 
										    // Puede que tenga que guardarse en una sesión.
											
											// FILAS
											// el número de filas es igual a la habitación con más camas.
											for($fila=0;$fila<=$max_camas;$fila++)
											{
													$tipo2 = $habita[0]['tipo'];	
													// Primera columna.
													echo "<tr>
															<td width=\"2px\">
															</td>";
													$sw_first = false;	
															
													// COLUMNAS	
													
													// valor para sumar a cada columna, utilizado en caso de exceder el máximo de camas
													// establecido.
													$columna_act = 0;								
													
													for($i=0;$i<count($habita);$i++)
													//for($i=0;$i<($habita[$i]['Columnas_Necesarias']*$max_tipo);$i++)
													{		
													
														$sw_tipo_hab = false;
														if($tipo2 !=$habita[$i]['tipo'] && $habita[$i]['tipo']!= "" || !$sw_first)
														{														 
															for($j=0;$j<count($_SESSION['pag_hab']);$j++)
															{
																if($pagina_habitaciones == $_SESSION['pag_hab'][$j]['pagina'] &&
																   $habita[$i]['tipo'] == $_SESSION['pag_hab'][$j]['Id_Tipo_Hab'] )
																   {	
																		$habita[$i]['pagina'] = $_SESSION['pag_hab'][$j]['pagina'];				
																		$tipo2 = $habita[$i]['tipo'];
																		$sw_tipo_hab = true;
																		$sw_first = true;		
																		break;
																   }
															}															
														}
														else
														{
															if($sw_first)
															{
																$sw_tipo_hab = true;
															}
														}
														 
														if($sw_tipo_hab)
														{		
														    	
															if($fila==0)
															{
																 if ($habita[$i]['Columnas_Necesarias']>1)
																 {																	 
																	  echo "<td colspan=\"".$habita[$i]['Columnas_Necesarias']."\"  
																			      class=\"nom_hab\">".$habita[$i]['id']."</td>";																	 
																 }
																 else
																 {
																       
																		echo "<td class=\"nom_hab\">".$habita[$i]['id']."</td>";																		
																 }		    
															}
															else
															{								
															    						 																
																for($columnas=0;$columnas<$habita[$i]['Columnas_Necesarias'];$columnas++)
																{																
																 
																	/*
																	if($habita[$i]['camas_col'][$columnas] == 0) 
																	{				
																		echo "<td id=\"no_cama\">&nbsp;".$habita[$i]['id']."</td>";
																		break;
																	}	
																	*/	
																	
																	// Variables necesarias en caso de exceder el máximo de camas
																	$mod = (($habita[$i]['Columnas_Necesarias'])*$max_camas);																	
																	$num_hab = $columna_act+$fila;
																	$columna_act = ($columna_act + $max_camas) % $mod;
																	
																	// En caso de exceder el máximo de camas...
																	if ( ($habita[$i]['Columnas_Necesarias'] > 1) and ($num_hab>$habita[$i]['camas']) )
																	{																	    
																		echo "<td id=\"no_cama\">";
																		echo "&nbsp;";
																		echo "</td>";
																		
																	}								
																	elseif ($habita[$i]['camas_res'] <= 0) 
																	{				
																		echo "<td id=\"no_cama\">&nbsp;</td>";
																	
																	}
																	elseif ($habita[$i]['reservas'] > 0)
																	{																	 
																		echo ("<td class=\"cama_reservada\">&nbsp;<td>");			
																		$habita[$i]['reservas']--;
																		
																	}
																	else
																	{
																			//Cama ocupada por miembro de un grupo
																			if($habita[$i]['gr']['cantidad'] > 0)
																			{
																				echo ("<td ");
																				if($habita[$i]['color']!="")
																				{
																					echo "style=\"background-color:#".$habita[$i]['color'].";\"";
																				}
																				echo (">&nbsp;</td>");
																				$habita[$i]['gr']['cantidad']--;
																			}
																			else
																			{
																				//Cama ocupada por peregrino
																				if($habita[$i]['alb']['cantidad'] > 0)
																				{																				 
																					echo ("<td class=\"cama_ocupada\" >&nbsp;</td>");
																					$habita[$i]['alb']['cantidad']--;
																				}
																				else
																				{
																					//Cama ocupada por peregrino
																					if($habita[$i]['per']['cantidad'] > 0)
																					{
																						echo ("<td class=\"cama_ocupada\" >&nbsp;</td>");
																						$habita[$i]['per']['cantidad']--;
																					}	
																					else
																					{
																					//Cama libre
																						if($habita[$i]['camas'] >= $fila)
																						{
																						 //echo "<td>".count($habita[$i])."</td>";
																						 
																				 
					echo "<td class=\"cama_libre\" ";
					echo "onMouseOver=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."')";
						echo "{
						      document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_resaltada';
						      document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').value='true';
							  }\"";
					echo "onMouseOut=\"if(document.forms[0].id_cama.value != '".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."') ";
			            echo "{
							   document.getElementById('".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."').className='cama_libre';
							  }\" ";
					echo "id=\"".$habita[$i]['id']."-".$i."-".$columnas."-".$fila."\" ";
					echo "onClick=\"asigna_habitacion('".$habita[$i]['id']."','".$i."','".$columnas."','".$fila."')\"> ";
						echo "&nbsp;";
					
					echo "</td>";	
					// Camas que faltán para la habitación.					
					$habita[$i]['camas_res']--;	

															    						}
																					    else
																						{
																							echo ("<td id=\"no_cama\">&nbsp;</td>");
																						}
																					}//if(habita per cantidad)
																				}//if(habita ALB cantidad																			
																			}//IF(HABITA GR CANTIDAD									
																	     }//if(habita reservas >0)																	
																	
																	$habita[$i]['camas_col'][$columnas]--;
																}// FIN while(habita columnas)																
															}//FIN if(fila=0)														
														}// FIN if(sw_tipo_hab)														
													}// FIN for(habita)																	
													echo "<td width=\"2px\">
													      </td>
													</tr>";
												}// FIN for(fila)												
										?>									
								</table>	
								</td>
								</tr>
								
								<tr>
							
								
								</td>
						</tr>
						</table>

						   </td>
					    </tr>
						<tr>
							<td> 
							<!-- Tabla Leyenda -->

                   <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td width="50" align="left" class='label_formulario'></td>
						<td width="20" align="left" class='label_formulario'></td>
                        <td width="130" align="left" class='label_formulario'></td>
                        
									
                        <td width="135" align="right" class='label_formulario'> 
						<div id="leyenda" style="position:absolute;  background-color: #FFFFFF; border: 1px none #000000;  visibility: hidden;z-index: 1;font-size:10px;margin-left:-110px;margin-top:-15px;width:400px;color:#064C87;float:left;" > 
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
                       		</div> </td>
                        			
                        <td height="20" class='label_formulario' align="right">
						
						<a href="#" onClick="ver_leyenda('2')" title="Ver la Leyenda de la Distribución de Habitaciones" OnMouseOver="window.status='Ver la Leyenda de la Distribución de Habitaciones' ; return true" onMouseOut="window.status='Listo' ; return true"> 
                        <font color="blue"><u>Leyenda</u></font></a></td>
								  </tr>
								</table> <!-- Fin tabla leyenda -->
							</td>
						</tr>

					</table> <!-- FIN TABLA 1 -->		
					</td>
					</tr>

					</td>
					</tr>					
					</table>
					</form>
				<?					
				}
					else
					{
						echo "<br><br><br>
							  <span class=\"label_formulario\">
							  	No se puede mostrar el mapa de habitaciones, compruebe que hay habitaciones introducidas en el sistema.
							  </span>
							  <br><br>";					
						$habitaciones_exist = "false";
					}
				}
				else
				{
					echo "<br><br><br>
					  	  <span class=\"label_formulario\">
						  	No se puede mostrar el mapa de habitaciondes, compruebe que hay habitaciones introducidas en el sistema.
						  </span>
						  <br><br>";					
					$habitaciones_exist = "false";					
				}
			}		
	}
				?>		
				</div>
				</div>
		<div  id="caja" style="display:block;">
			<table border="0"  width="100%" align="center" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;height:100%; ">
					<tr id="titulo_tablas">
						<td colspan="4" align="center" style="padding:0px 0px 0px 0px;margin:0px 0px 0px 0px;">
							<div class="champi_izquierda">&nbsp;</div>
							<div class="champi_centro" style="width:640px;">Listado de Peregrinos</div>
							<div  class="champi_derecha">&nbsp;</div>
						</td>					
					</tr>					
					<tr>					
						<td align="left" style="padding:0px 0px 0px 0px;" width="100%">
							<div id="tabla_listado" class="tableContainer" style='width:697px;height:550px;padding:0px 0px 0px 0px;background-color:#F4FCFF;'>
							<?
								if(!$sw_listado){
									echo "<br><br>";
									echo "<span class=\"label_formulario\">No se ha podido conectar con la base de datos</span>";
								}else{
							?>
								<table border="0" cellpadding="0" cellspacing="0" class="scrollTable" width="680px" style="padding:0px 0px 0px 0px;">
									<thead class="fixedHeader" cellspacing="0" width="100%"  class="tabla_detalles" >
											
											<th><a href="?pag=peregrinos.php&ord=DNI_Cl">DNI</a></th>
											<th><a href="?pag=peregrinos.php&ord=Nombre_cl">Nombre</th>
											<th><a href="?pag=peregrinos.php&ord=Apellido1_Cl">Primer Apellido</th>	
											<th><a href="?pag=peregrinos.php&ord=Apellido2_Cl">Segundo Apellido</th>										
											<!--<th><a href="?pag=peregrinos.php&ord=Id_Pais">País</a></th>-->
											<th>D</th>
											<th>M</th>
											<th>E</th>
									</thead>
									<tbody class="scrollContent">
									<?
									 // Variables para los textArea
								    $cols_dni = 14;
								    $div_dni = 14;
								    $cols_nom = 14;
								    $div_nom = 10;
								    $cols_ape = 30;
								    $div_ape = 22;
									for($i=0;$i<mysql_num_rows($result);$i++){
											$fila_listado = mysql_fetch_array($result);
									?>
									<tr class="texto_listados" onmouseover="resaltar_seleccion(this);" onmouseout="desresaltar_seleccion(this);">									
										<td valign='top'>
													<?
														$num_dni = intval(strlen($fila_listado['DNI_Cl'])/$div_dni) + 1;
														echo "<TEXTAREA class='areatexto'  Name=\"DNI_C1".$i."\" readonly
														rows=\"".$num_dni."\" cols=\"".$cols_dni."\"  >".$fila_listado['DNI_Cl']."</TEXTAREA>	
														";															
													?>															
												</td>
												<td valign='top'><?
												 		$num_nom = intval(strlen($fila_listado['Nombre_Cl'])/$div_nom) + 1;
												    	echo "<TEXTAREA class='areatexto' Name=\"Nombre_C1".$i."\" readonly
													    rows=\"".$num_nom."\" cols=\"".$cols_nom."\"  >".$fila_listado['Nombre_Cl']."</TEXTAREA>	
													    ";
													?>	
												<td valign='top'>
												    <?
														$num_ape1 = intval(strlen($fila_listado['Apellido1_Cl'])/$div_ape) + 1;
														echo "<TEXTAREA class='areatexto' Name=\"Apellido1_C1".$i."\" readonly
														rows=\"".$num_ape1."\" cols=\"".$cols_ape."'\" >".$fila_listado['Apellido1_Cl']."</TEXTAREA>";
													?>
												</td>	
												<td valign='top'>
												    <?
														$num_ape2 = intval(strlen($fila_listado['Apellido2_Cl'])/$div_ape) + 1;
														echo "<TEXTAREA class='areatexto' Name=\"Apellido2_C1".$i."\" readonly
														rows=\"".$num_ape1."\" cols=\"".$cols_ape."'\" >".$fila_listado['Apellido2_Cl']."</TEXTAREA>";
													?>
												</td>
												<!--											
												<td><?
														if(strlen($paises[$fila_listado['Id_Pais']]) > 10){
															echo substr($paises[$fila_listado['Id_Pais']],0,7)."...";
														}else{
															echo $paises[$fila_listado['Id_Pais']];
														}	
														?>
												</td>
												-->
										<td><a href="?pag=peregrinos.php&detalles=si&dni=<?echo $fila_listado['DNI_Cl'];?>&tipo_doc=<?echo $fila_listado['Tipo_documentacion'];?>"><IMG alt='Ver Detalles de Peregrino' src='../imagenes/botones/detalles.gif' border=0></a></td>
										<td><a href="?pag=peregrinos.php&modificar=si&dni=<?echo $fila_listado['DNI_Cl'];?>&tipo_doc=<?echo $fila_listado['Tipo_documentacion'];?>"><IMG alt='Modificar Datos de Peregrino' src='../imagenes/botones/modificar.gif' border=0></a></td>
										<td><a href="?pag=peregrinos.php&eliminar=si&dni=<?echo $fila_listado['DNI_Cl'];?>&tipo_doc=<?echo $fila_listado['Tipo_documentacion'];?>"><IMG alt='Eliminar Peregrino' src='../imagenes/botones/eliminar.gif' border=0></a></td>
									</tr>
									<?}?>							
									</tbody>
								</table>
								<?
								} //llave de cierre de if($sw_listado)								
								?>
							</td>
						</tr>
				</table>
		</div>
	</div>
</div>

<?

	if(isset($_POST['dni_peregrino']) && $_POST['test_dni'] == "1" && (!isset($_GET['accion']) ||  $_GET['accion'] =="")){
		
		$dni_completo = trim($_POST['dni_peregrino']);
		$sql_incidencias = "select * from incidencias where DNI_Inc like '".$dni_completo."' and DNI_Inc in (select DNI_Cl from cliente where DNI_Cl like '".$dni_completo."' and Tipo_documentacion like '".$_POST['tipo_documentacion']."')";
		$result_incidencias = mysql_query($sql_incidencias);
		if(mysql_num_rows($result_incidencias) > 0){
			echo "<script>alert('El peregrino que va a introducir, tiene incidencias registradas.');document.forms[0].nombre_peregrino.focus();</script>";
		}

		$sql_test_dni = "SELECT * FROM cliente WHERE DNI_Cl = '".trim($_POST['dni_peregrino'])."' ";

		$res_test_dni = mysql_query($sql_test_dni);
		if (mysql_num_rows($res_test_dni) > 0){
			$fila_test_dni = mysql_fetch_array($res_test_dni);
			
			echo "<script>rellena_alta('".$fila_test_dni['Tipo_documentacion']."','".$fila_test_dni['DNI_Cl']."','".$fila_test_dni['Nombre_Cl']."','".$fila_test_dni['Apellido1_Cl']."','".$fila_test_dni['Apellido2_Cl']."','".$fila_test_dni['Id_Pais']."','".$fila_test_dni['Id_Pro']."','".$fila_test_dni['Localidad_Cl']."','".$fila_test_dni['Direccion_Cl']."','".$fila_test_dni['CP_Cl']."','".$fila_test_dni['Fecha_Nacimiento_Cl']."','".$fila_test_dni['Fecha_Expedicion']."','".$fila_test_dni['Sexo_Cl']."','".$fila_test_dni['Tfno_Cl']."','".$fila_test_dni['Email_Cl']."','".str_replace("\r\n"," ",$fila_test_dni['Observaciones_Cl'])."');document.forms[0].existe.value = 'true';document.forms[0].nombre_peregrino.focus();</script>";
			
		}

		/*$sql_alb = "select DNI_Cl from pernocta where DNI_Cl like '".$dni_completo."'";
		$result_alb  = mysql_query($sql_alb);
		if(mysql_num_rows($result_alb)){
			$sql_comprueba = "select * from pernocta where DNI_Cl like '".$dni_completo."' and Fecha_Salida > '".date("Y-m-d")."' and fecha_Llegada <= '".date("Y-m-d")."'";
			$result_comprueba = mysql_query($sql_comprueba);
			if(mysql_num_rows($result_comprueba) > 0){
				echo "<script>alert('No puede darse de alta el peregrino. Ya está realizando una estancia.');window.location.href='.?pag=peregrinos.php';</script>";	
			}else{
				$sql_completa = "select * from cliente where DNI_Cl like '".$dni_completo."%' and DNI_Cl IN (SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida <=  '".date("Y-m-d")."' ) AND DNI_Cl NOT IN (SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida >  '".date("Y-m-d")."' ) and Tipo_documentacion like '".$_POST['tipo_documentacion']."' ";			
				$result_completa = mysql_query($sql_completa);
				if(mysql_num_rows($result_completa) > 0){
					$fila_completo = mysql_fetch_array($result_completa);
					$resto_dni = $fila_completo['DNI_Cl'];
					echo "<script>rellena_alta('".$fila_completo['Tipo_documentacion']."','".$resto_dni."','".$fila_completo['Nombre_Cl']."','".$fila_completo['Apellido1_Cl']."','".$fila_completo['Apellido2_Cl']."','".$fila_completo['Id_Pais']."','".$fila_completo['Id_Pro']."','".$fila_completo['Localidad_Cl']."','".$fila_completo['Direccion_Cl']."','".$fila_completo['CP_Cl']."','".$fila_completo['Fecha_Nacimiento_Cl']."', '".$fila_completo['Fecha_Expedicion']."' , '".$fila_completo['Sexo_Cl']."','".$fila_completo['Tfno_Cl']."','".$fila_completo['Email_Cl']."','".str_replace("\r\n"," ",$fila_completo['Observaciones_Cl'])."');document.forms[0].existe.value = 'true'</script>";
				}
			}
		}else{
			$sql_comprueba2 = "select * from pernocta_p where DNI_Cl like '".$dni_completo."' and Fecha_Salida > '".date("Y-m-d")."' and fecha_Llegada <= '".date("Y-m-d")."'";
			$result_comprueba2 = mysql_query($sql_comprueba2);
			if(mysql_num_rows($result_comprueba2) > 0){
				echo "<script>alert('No puede darse de alta el peregrino. Ya está realizando una estancia.');window.location.href='.?pag=peregrinos.php';</script>";	
			}else{
				$sql_completa = "select * from cliente where DNI_Cl like '".$dni_completo."%' and DNI_Cl IN (SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida <=  '".date("Y-m-d")."' ) AND DNI_Cl NOT IN (SELECT DNI_Cl FROM pernocta_p WHERE fecha_Salida >  '".date("Y-m-d")."' ) and Tipo_documentacion like '".$_POST['tipo_documentacion']."' ";			
				$result_completa = mysql_query($sql_completa);
				if(mysql_num_rows($result_completa) > 0){
					$fila_completo = mysql_fetch_array($result_completa);
					$resto_dni = $fila_completo['DNI_Cl'];
					echo "<script>rellena_alta('".$fila_completo['Tipo_documentacion']."','".$resto_dni."','".$fila_completo['Nombre_Cl']."','".$fila_completo['Apellido1_Cl']."','".$fila_completo['Apellido2_Cl']."','".$fila_completo['Id_Pais']."','".$fila_completo['Id_Pro']."','".$fila_completo['Localidad_Cl']."','".$fila_completo['Direccion_Cl']."','".$fila_completo['CP_Cl']."','".$fila_completo['Fecha_Nacimiento_Cl']."', '".$fila_completo['Fecha_Expedicion']."' ,'".$fila_completo['Sexo_Cl']."','".$fila_completo['Tfno_Cl']."','".$fila_completo['Email_Cl']."','".$fila_completo['Observaciones_Cl']."');document.forms[0].existe.value = 'true'</script>";
				}
			}
		}*/
				
		
		echo "<script>;documentacion(0)</script>";
		echo "<script>document.forms[0].test_dni.value='0'</script>";
	}				
	
	// Cambio de página			  
	// las ventanas ya están mostradas, lo único que hacemos es ocultar una u otra.
	if(isset($_POST['sub']) && $_POST['sub'] == "true"){
		echo "<script>nuevo_habitaciones();abrir(2,0);document.forms[0].sub.value='false';</script>";					
		if($habitaciones_exist!="false")
		{
			echo "<script>
					document.getElementById('boton_ver_habitaciones').style.display='none';
					document.getElementById('boton_nuevo_habitacion2').style.display='block'
				  </script>";
		}else{
			echo "<script>
					document.getElementById('boton_ver_habitaciones').style.display='none';
					document.getElementById('boton_nuevo_habitacion2').style.display='none';
				  </script>";
		}	
	}
}
}
mysql_close($db);
}else{	//Muestro una ventana de error de permisos de acceso a la pagina	
	 echo "<div class='error'>
		        <br><br><br><br>
					<div style='color:red;'>
							NO TIENE PERMISOS PARA ACCEDER A ESTA PÁGINA
					</div>
			</div>";
}
?>