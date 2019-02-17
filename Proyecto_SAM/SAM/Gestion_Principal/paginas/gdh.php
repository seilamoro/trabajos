<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
<?PHP

	// Vienen a mi página
	
	include('paginas/gdh_get.php');
	
	// Fin vienen a mi página

	include('paginas/gdh_funciones.php');
	include('paginas/gdh_session.php');	
 
?>	

<script language="Javascript">

	// Funciones que cambian el color de los listados cuando situamos el ratón encima de una fila.	
	function resaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '<?PHP echo $_SESSION['gdh']['colores']['resaltado']; ?>';
	  	tr.style.color = '<?PHP echo $_SESSION['gdh']['colores']['letra_resaltado']; ?>';
	  	tr.style.cursor = 'pointer';
	}
	
	function desresaltar_seleccion(tr) {
	  	tr.style.backgroundColor = '<?PHP echo $_SESSION['gdh']['colores']['normal']; ?>';
	  	tr.style.color = '<?PHP echo $_SESSION['gdh']['colores']['letra_normal']; ?>';
	}
	
	function resaltar_alerta(tr) {
	  	tr.style.backgroundColor = '<?PHP echo $_SESSION['gdh']['colores']['alerta_resaltado']; ?>';
	  	tr.style.color = '<?PHP echo $_SESSION['gdh']['colores']['letra_alerta_resaltado']; ?>';
	  	tr.style.cursor = 'pointer';
	}
	
	function desresaltar_alerta(tr) {
	  	tr.style.backgroundColor = '<?PHP echo $_SESSION['gdh']['colores']['alerta']; ?>';
	  	tr.style.color = '<?PHP echo $_SESSION['gdh']['colores']['letra_alerta']; ?>';
	}
	
	// Función que actualiza la página gdh para ver
	// los detalles de ocupación de una de las taquillas
	// del albergue.
	function detalles_taquilla(valor) {
	  	formulario.gdh_dis.value = "T";
	  	formulario.gdh_taq.value = valor;
		formulario.submit();
	}
	
	// Función que actualiza la página gdh al modificar 
	// la fecha de la zona de Gestión de Habitaciones.
	function recargar_gdh_dis(valor) {
	  	formulario.gdh_dis.value = valor;
		formulario.submit();
	}
	
	// Función que actualiza la página gdh al indicar 
	// que nos tiene que mostrar los detalles de la
	// estancia de un alberguista, peregrino o grupo, 
	// o los detalles de una reserva.
	function detalles(tipo,clave,fecha) {
	  	formulario.gdh_det.value = tipo;
	  	formulario.gdh_dat.value = clave + "*" + fecha;
		formulario.submit();
	}
	
	// Función que actualiza la página gdh al indicar 
	// que un alberguista o un grupo pagan alguna noche
	// de estancia o que se modifica un ingreso de una
	// reserva.
	function detalles_pagar(tipo,dni,fecha,numeronoches,importe) {
	  	formulario.gdh_det.value = tipo;
	  	formulario.gdh_dat.value = dni + "*" + fecha;
	  	formulario.gdh_pag.value = numeronoches + "*" + importe;
		formulario.submit();
	}
		
	// Función que desocupa una taquilla, los datos
	// de la taquilla seleccionada están guardados
	// en dos variables de sesión.
	function desocupar_taquilla() {
	  	formulario.gdh_dis.value = "des_taq";
		formulario.submit();
	}
	
	// Función que ocupa una taquilla, los datos
	// de la taquilla seleccionada están guardados
	// en dos variables de sesión.
	function ocupar_taquilla() {
	  	formulario.gdh_dis.value = "oc_taq";
		formulario.submit();
	}
	
	// Función que recarga la página despues de 
	// modificar uno de los cuadros de texto de
	// la zona de estancias del albergue con el
	// fin de filtrar la busqueda de estancias.
	function cargar(){
	  	var tecla = event.keyCode;
	  	if (tecla == 13)
			document.formulario.submit();
	}
	
	// Función que asigna el foco al cuadro de
	// texto que se modifico por última vez
	function foco(i,s,e)	{
		if (i.setSelectionRange) {
			i.focus();
			i.setSelectionRange(s,e);
		}
		else if (i.createTextRange) {
			var range = i.createTextRange();
			range.collapse(true);
			range.moveEnd('character', e);
			range.moveStart('character', s);
			range.select();
		}
	}
	
	// Función que cambia el valor del importe
	// de las noches que va a pagar una persona.
	function cambiar_importe(noches,importe,tarifa,pagadas,ingreso)	{
		if (noches.value == "NULL") {
		  	importe.value = "";
		}
		else {
		  	importe.value = (noches.value * tarifa - (ingreso - tarifa * pagadas))+ " €";
		}
	}
	
	// Funcion que asigna los valores necesarios
	// para facturar la estancia que se le indica.	
	function facturar(tipo,clave,fecha) {
	  	if (tipo == "NO") {
		  	formulario.gdh_dis.value = "H";	    
		}
	  	else if (tipo == 'A') {
		    formulario.gdh_dis.value = 'F';
		    formulario.gdh_fac.value = tipo + '*' + clave + '*' + fecha;
		}
	  	else if (tipo == 'AN') { // Facturar el pago de varias noches y dividir una estancia en 2
		    formulario.gdh_dis.value = 'FN';
		    formulario.gdh_fac.value = tipo + '*' + clave + '*' + fecha;
		}
	  	else if (tipo == 'AM') {
		    formulario.gdh_dis.value = 'FFM';
		    formulario.gdh_fac.value = tipo + '*' + clave + '*' + fecha;
		}
	  	else if (tipo == 'P') {
		    formulario.gdh_dis.value = 'F';
		    formulario.gdh_fac.value = tipo + '*' + clave + '*' + fecha;
		}
	  	else if (tipo == 'R') {
		    formulario.gdh_dis.value = 'F';
		    formulario.gdh_fac.value = tipo + '*' + clave + '*' + fecha;
		}
	  	else if (tipo == 'F') { // Cuando se ha pulsado el botón de facturar después de escoger el tipo de factura de grupo.
		    if (formulario.tipo_factura[0].checked) {
				formulario.gdh_dis.value = formulario.tipo_factura[0].value;
			}
			else if (formulario.tipo_factura[1].checked){
				formulario.gdh_dis.value = formulario.tipo_factura[1].value;
			}
			formulario.gdh_fac.value = 'G' + '*' + clave + '*' + fecha;
		}
	  	else if (tipo == 'G') { // Cuando se ha pulsado el botón de facturar para ir a la ventana de escoger tipo de factura de grupo.
		    formulario.gdh_det.value = 'GF';
		  	formulario.gdh_dat.value = clave + "*" + fecha;
		}
	  	else if (tipo == 'GN') { // Cuando se ha pulsado el botón de facturar un número de noches pagadas de un grupo
		    formulario.gdh_dis.value = 'FGN';
		    formulario.gdh_fac.value = tipo + '*' + clave + '*' + fecha;
		}
		formulario.submit();
	}
	
	// Funcion que asigna los valores necesarios
	// para facturar la estancia que se le indica.	
	function tipo_factura(tipo) {
	    formulario.gdh_tipo_factura.value = tipo;
		formulario.submit();
	}
	
	// Comprueba que el campo del ingreso tiene un
	// valor correcto.
	function comprobar_ingreso(ing,val) {
	  	if ((ing.value >= 0)&&(ing.value <= val)) {
		    ing.style.color = "<?PHP echo $_SESSION['gdh']['colores']['letra_normal']; ?>";
		}
		else {
		    ing.style.color = "red";
		}
	}
	
	// Cambia el número de página en distribución
	// de habitaciones.
	function cambiar_pagina_dis(numero) {
	  	formulario.dis_num_pag.value = numero;
	  	formulario.submit();
	}
	
	// Funcion que sirve para ordenar las estacias por
	// el campo que desee el cliente.
	function ordenar_estancias(zona,orden,modo) {
	  	if (zona == 'estancias') {
		  	window.formulario.gdh_est.value = orden + "*" + modo;
		  	window.formulario.submit();
		}
		else if (zona == 'checkin') {
		  	window.formulario.gdh_cki.value = orden + "*" + modo;
		  	window.formulario.submit();
		}
		else if (zona == 'checkout') {
		  	window.formulario.gdh_cko.value = orden + "*" + modo;
		  	window.formulario.submit();
		}
	}
	
	// Función que selecciona una habitación para
	// cambiarla de tipo.
	function seleccionar_habitacion(habitacion) {
	  	window.formulario.dis_cambio_hab.value = habitacion;
	  	window.formulario.gdh_det.value = 'H';
	  	window.formulario.gdh_dat.value = habitacion;	  	
		window.formulario.submit();
	}
	
	// Función que cambia el dia del calendario
	// manteniendo los detalles de la habitación.
	function cambiar_dia_detalles(dia,mes,anio,habitacion) {
	  	window.formulario.dis_cambio_hab.value = '';
	  	window.formulario.gdh_det.value = 'H';
	  	window.formulario.gdh_dat.value = habitacion;	  	
		cambiar_dia(dia,mes,anio);
	}
	
	// Se podría implementar una funcion que comprobara los documentos, para ello deberíamos saber
	// si es dni, pasaporte, etc...
	function dni_valido(dni) {
		return true;
	}
	
	// Función que cambia una habitación de tipo
	function cambiar_habitacion(tipo) {
	  	if (window.formulario.dis_cambio_hab.value != "") {
		    window.formulario.dis_cambio_hab_fin.value = tipo;
			window.formulario.submit();
		}			  	
	}
	
	// Función que muestra una factura ya hecha
	function ver_factura(factura) {
	  	window.location.href = "paginas/factura.php?numf=" + factura;
	}
	
	// Función que muestra el listado de facturas de un grupo que ha hecho una facturación desglosada.
	function ver_factura_desglosada() {
	  	window.formulario.gdh_det.value = 'FGD';
		window.formulario.submit();
	}
	
	// Funcion que mira a ver si se han seleccionado varias
	// estancias para agrupar en una factura.
	function agrupar_estancias_factura() {
		formulario.gdh_dis.value = 'FF';
		formulario.submit();	
	}
	
	// Función que cambia el dia.
	function cambiar_dia(dia,mes,anio) {
	  	formulario.fecha_cal.value = dia + "-" + mes + "-" + anio;
	  	formulario.submit();
	}
	
	// Función que recarga la página si cambiamos
	// lo que queremos mostrar en la parte de estancias
	// en el albergue.
	function mostrar_estancias() { //La función solamente hace un submit, pero queda por si en el futuro se necesitan añadir instrucciones.
	  	formulario.submit();
	}
	
</script>
	
</head>
	
	<form action="index.php?pag=gdh.php" method="POST" name="formulario">
	
	<input type=hidden name="gdh_dis" value=""> 			<!-- Controla la zona de Distribución de Habitaciones o Distribución de Taquillas -->
	<input type=hidden name="gdh_taq" value=""> 			<!-- Sirve para almacenar los datos de la taquilla de la que queremos ver los detalles -->	
	<input type=hidden name="gdh_det" value=""> 			<!-- Controla la zona de Detalles -->
	<input type=hidden name="gdh_dat" value=""> 			<!-- Sirve para almacenar los datos de lo que queremos ver en la zona de Detalles -->
	<input type=hidden name="gdh_pag" value=""> 			<!-- Indicar datos sobre el pago de pernoctas, etc. -->
	<input type=hidden name="fecha_cal" value=""> 			<!-- Cambia la fecha cuando lo hacemos mediante el calendario -->
	<input type=hidden name="gdh_est" value=""> 			<!-- Controla la zona de las estancias, para ordenar por algún campo -->
	<input type=hidden name="gdh_cki" value=""> 			<!-- Controla la zona del checkin, para ordenar por algún campo -->
	<input type=hidden name="gdh_cko" value=""> 			<!-- Controla la zona del checkout, para ordenar por algún campo -->
	<input type=hidden name="gdh_fac" value=""> 			<!-- Sirve para almacenar datos sobre facturacion -->
	<input type=hidden name="gdh_tipo_factura" value=""> 	<!-- Sirve para almacenar datos sobre el tipo de factura que se realizara -->
	<input type=hidden name="dis_num_pag" value=""> 		<!-- Cambia el número de pagina en distribucion de habitaciones -->
	<input type=hidden name="dis_cambio_hab" value="<?PHP echo $_SESSION['gdh']['dis_hab']['hab_seleccionada'];?>">	<!-- Selecciona una habitacion -->
	<input type=hidden name="dis_cambio_hab_fin" value=""> 	<!-- Cambia el tipo de una habitación -->
	<input type="hidden" name="gdh_estancias_sin_facturar" value=""/> 	<!-- Cambia el valor cuando pulsamos la alerta de facturas en rojo -->

	<div style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_izquierda']; ?>;float:left;">

<?PHP

	// Caso excepcional para mostrar los datos de cada componente de un grupo en vez de distribucion de habitaciones, taquillas o facturas, 
		
	if (($_SESSION['gdh']['detalles']['de_tipo'] == "G") && ($_SESSION['gdh']['detalles']['de_pagina'] == "E")) {
	  	include('paginas/detalles_componentes_grupo.php');
	}
	
	// Fin caso excepcional
	
	// Páginas a cargar en Distribución de Habitaciones	
	
	else if (isset($_SESSION['gdh']['gdh_dis']['tipo'])) {
	  	if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'T') {
		    include('paginas/distribucion_taquillas.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'H'){
		  	include('paginas/distribucion_habitaciones.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'F'){
		  	if ($_SESSION['gdh']['detalles']['de_tipo'] == 'A') {
		  		include('paginas/factura_alberguista.php');	  	
			}
		  	else if ($_SESSION['gdh']['detalles']['de_tipo'] == 'P') {
		  		include('paginas/factura_peregrino.php');
			}
		  	else if ($_SESSION['gdh']['detalles']['de_tipo'] == 'R') {
		  		include('paginas/factura_reserva.php');
			}
			else {
		  		include('paginas/distribucion_habitaciones.php');
		  	}
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FF'){
		  	include('paginas/factura_seleccionar_factura.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FFM'){
		  	include('paginas/factura_agrupada_mismo_alberguista.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FN'){
		  	include('paginas/factura_alberguista_noches.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FG'){
		  	include('paginas/factura_grupo.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FGN'){
		  	include('paginas/factura_grupo_noches.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FD'){
		  	include('paginas/factura_grupo_desglosada.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FDR'){
		  	include('paginas/factura_grupo_desglosada_resto.php');
		}
		else if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'FDRM'){
		  	include('paginas/factura_grupo_desglosada_resumen.php');
		}
		else {
		  //ERROR DE VARIABLE SESSION;
		  echo "ERROR DE SESIÓN";
		}
	}
	else {
        echo 'Error de Inicio de Sesión';
	}
	
	// Fin páginas a cargar en Distribución de Habitaciones	

?>

	</div>
	<div style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_derecha']; ?>;float:right;">

<?PHP

	// Páginas a cargar en Detalles Estancia

	if (isset($_SESSION['gdh']['detalles']['de_tipo'])) {
	  	//echo $_SESSION['gdh']['detalles']['de_clave'].'<br>',$_SESSION['gdh']['detalles']['de_fecha_llegada'];
	  	if ($_SESSION['gdh']['detalles']['de_tipo'] == "A") {
	  		if (comprobar_existe_estancia($_SESSION['gdh']['detalles']['de_tipo'],$_SESSION['gdh']['detalles']['de_clave'],$_SESSION['gdh']['detalles']['de_fecha_llegada']) == true) {
		  	  	if ($_SESSION['gdh']['detalles']['de_pagina'] == "D") {
					include('paginas/detalles_alberguista.php');
				}
				else if ($_SESSION['gdh']['detalles']['de_pagina'] == "P") {
				  	include('paginas/detalles_alberguista_pagarnoche.php');
				}
				else {
				  	include('paginas/detalles_vacio.php');
				}
			}
			else {
				include('paginas/detalles_vacio.php');
			}
		}
		else if ($_SESSION['gdh']['detalles']['de_tipo'] == "P") {
	  	  	if (comprobar_existe_estancia($_SESSION['gdh']['detalles']['de_tipo'],$_SESSION['gdh']['detalles']['de_clave'],$_SESSION['gdh']['detalles']['de_fecha_llegada']) == true) {
			  	if ($_SESSION['gdh']['detalles']['de_pagina'] == "D") {
					include('paginas/detalles_peregrino.php');
				}
				else {
				  	include('paginas/detalles_vacio.php');
				}
			}
			else {
				include('paginas/detalles_vacio.php');
			}
		}
		else if ($_SESSION['gdh']['detalles']['de_tipo'] == "G") {
	  	  	if (comprobar_existe_estancia($_SESSION['gdh']['detalles']['de_tipo'],$_SESSION['gdh']['detalles']['de_clave'],$_SESSION['gdh']['detalles']['de_fecha_llegada']) == true) {
				if ($_SESSION['gdh']['detalles']['de_pagina'] == "D") {
					include('paginas/detalles_grupo.php');
				}
		  	  	else if ($_SESSION['gdh']['detalles']['de_pagina'] == "E") {
					include('paginas/detalles_grupo_habitaciones.php');
				}
		  	  	else if ($_SESSION['gdh']['detalles']['de_pagina'] == "P") {
					include('paginas/detalles_grupo_pagarnoche.php');
				}
		  	  	else if ($_SESSION['gdh']['detalles']['de_pagina'] == "F") {
					include('paginas/detalles_grupo_facturar.php');
				}
		  	  	else if ($_SESSION['gdh']['detalles']['de_pagina'] == "FGD") {
					include('paginas/detalles_grupo_listado_desglosada.php');
				}
				else {
				  	include('paginas/detalles_vacio.php');
				}
			}
			else {
				include('paginas/detalles_vacio.php');
			}
		}
		else if ($_SESSION['gdh']['detalles']['de_tipo'] == "R") {
	  	  	if (comprobar_existe_estancia($_SESSION['gdh']['detalles']['de_tipo'],$_SESSION['gdh']['detalles']['de_clave'],$_SESSION['gdh']['detalles']['de_fecha_llegada']) == true) {
				if ($_SESSION['gdh']['detalles']['de_pagina'] == "D") {
					include('paginas/detalles_reserva.php');
				}
				else if ($_SESSION['gdh']['detalles']['de_pagina'] == "I") {
					include('paginas/detalles_reserva_modificaringreso.php');
				}
				else if ($_SESSION['gdh']['detalles']['de_pagina'] == "V") {
					include('paginas/detalles_reserva_validarcama.php');
				}
				else {
				  	include('paginas/detalles_vacio.php');
				}
			}
			else {
				include('paginas/detalles_vacio.php');
			}			
		}
		else if ($_SESSION['gdh']['detalles']['de_tipo'] == "H") {
		  	if ($_SESSION['gdh']['dis_hab']['hab_seleccionada'] == "") {
				include('paginas/detalles_vacio.php');
			}
			else {
	  	  		include('paginas/detalles_habitacion.php');
	  	  	}
		}
		else {
			include('paginas/detalles_vacio.php');
		}
	}
	else {
		include('paginas/detalles_vacio.php');
	}
	
	// Fin páginas a cargar en Distribución de Habitaciones	
	
	//Para no mostrar la pagina de factura cuando se va a algún otro sitio y se vuelve a gdh.php
	if (SUBSTR($_SESSION['gdh']['gdh_dis']['tipo'],0,1) == 'F') {
	  	$_SESSION['gdh']['gdh_dis']['tipo'] = 'H';
	}
	//
	
?>

	</div>
	<div style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_izquierda']; ?>;position:relative;top:10px;float:left;">
		
<?PHP

	// Estancias en el Albergue:
	include('paginas/gdh_estancias.php');

?>

	</div>
	<div>
		<div style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_derecha']; ?>;position:relative;top:10px;">

<?PHP

	// Check - In:
	include('paginas/gdh_checkin.php');

?>
	
		</div>
		<div style="width:<?PHP echo $_SESSION['gdh']['tamanios']['gdh_derecha']; ?>;position:relative;top:21px;">
		
<?PHP

	// Check - Out:
	include('paginas/gdh_checkout.php');

?>
		
		</div>
	</div>			
	
<?PHP

	// Devolvemos el foco de la página a los campos de filtrado en Estancias en el Albergue
	echo '<SCRIPT language"JavaScript">';
	  	if ($_SESSION['gdh']['foco'] ==  "es_tipo") {
		    echo 'foco(formulario.es_tipo,formulario.es_tipo.value.length);';
	  	  	unset($_SESSION['gdh']['foco']);
		}
		else if ($_SESSION['gdh']['foco'] ==  "es_dni") {
		    echo 'foco(formulario.es_dni,formulario.es_dni.value.length);';
	  	  	unset($_SESSION['gdh']['foco']);
		}
		else if ($_SESSION['gdh']['foco'] ==  "es_name") {
			echo 'foco(formulario.es_name,formulario.es_name.value.length);';
	  	  	unset($_SESSION['gdh']['foco']);
		}
		else if ($_SESSION['gdh']['foco'] ==  "es_habitacion") {
			echo 'foco(formulario.es_habitacion,formulario.es_habitacion.value.length);';
	  	  	unset($_SESSION['gdh']['foco']);
		}
		
		//Si hemos creado una factura, la tenemos que guardar o mostrar:
		if (isset($_SESSION['gdh']['mostrar_factura'])) {
		 	echo 'window.location.href = "paginas/factura.php?numf='.$_SESSION['gdh']['mostrar_factura'].'";';
		 	unset($_SESSION['gdh']['mostrar_factura']);
		}	
		
	echo '</SCRIPT>';
	
?>
	
	</form>
