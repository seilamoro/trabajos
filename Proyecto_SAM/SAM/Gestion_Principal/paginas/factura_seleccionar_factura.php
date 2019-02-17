<script language="javascript">
		
	// Funcion que activa o desactiva los campos correspondientes
	// al dni de la persona que factura.
	function desactivar_campos(c1,c2,valor) {
	  	//alert(campo_desactivado.value);
	  	if (valor == 0) {
		  	c1.disabled = false;
		  	c2.disabled = true;
		}
		else {
		  	c1.disabled = false;
		  	c2.disabled = true;
		}		  	
	}
	
	// Funcion que modifica el resto de la factura en función
	// de los alberguistas que marquemos con exentos de pago.
	function modificar_resto_factura_exentos(campo_resto,campo_total,campo_exento_alberguista,importe_pagado,importe_resto,desperfecto) {
		if (campo_exento_alberguista.value == 'S') {
		  	campo_resto.value = (parseFloat(campo_resto.value) - importe_pagado - importe_resto);
		}
		else {
		  	campo_resto.value = (parseFloat(campo_resto.value) + importe_pagado + importe_resto);
		}
		if (desperfecto.value == '') {
		  	campo_total.value = parseFloat(campo_resto.value) + ' €';
		}
		else if (!isNaN(parseFloat(desperfecto.value))) {
		  	campo_total.value = (parseFloat(campo_resto.value) + parseFloat(desperfecto.value)) + ' €';
		}
	}
	
	// Funcion que modifica el resto de la factura en función
	// de los alberguistas que marquemos con exentos de pago.
	function modificar_resto_factura_desperfecto(campo_resto,campo_total,desperfecto) {
		if (desperfecto.value == '') {
		  	campo_total.value = parseFloat(campo_resto.value) + ' €';
		}
		else if (!isNaN(parseFloat(desperfecto.value))) {
		  	campo_total.value = (parseFloat(campo_resto.value) + parseFloat(desperfecto.value)) + ' €';
		}
	}

</script>

<?PHP
/* Agrupada de Alberguistas */
if (isset($_POST['estancias'])) {
  	if (COUNT($_POST['estancias']) == 1) {
  	  	//echo $_POST['estancias'][0];
		$datos_estancia = SPLIT("\*",$_POST['estancias'][0]);
		echo "<script language='javascript'> facturar('".$datos_estancia[0]."','".$datos_estancia[1]."','".$datos_estancia[2]."'); </script>";
	}
	else {
	  	include('paginas/factura_agrupada_varios_alberguistas.php');
	}
}

/* Agrupada de varias estancias del mismo alberguista en distintas habitaciones en días consecutivos. */

/* Agrupada de Peregrinos */
else if (isset($_POST['estancias_p'])) {
  	if (COUNT($_POST['estancias_p']) == 1) {
  	  	//echo $_POST['estancias_p'][0];
		$datos_estancia = SPLIT("\*",$_POST['estancias_p'][0]);
		echo "<script language='javascript'> facturar('".$datos_estancia[0]."','".$datos_estancia[1]."','".$datos_estancia[2]."'); </script>";
	}
	else {
	  	include('paginas/factura_agrupada_varios_peregrinos.php');
	}		
}

/* Como no existe agrupada de grupos, el código queda: */
else if (isset($_POST['estancias_gr'])) {
  	if (COUNT($_POST['estancias_gr']) == 1) {
  	  	//echo $_POST['estancias_gr'][0];
		$datos_estancia = SPLIT("\*",$_POST['estancias_gr'][0]);
		echo "<script language='javascript'> facturar('".$datos_estancia[0]."','".$datos_estancia[1]."','".$datos_estancia[2]."'); </script>";
	}
	else {
	  	//Aqui se debería crear el código si hubiera la posibilidad de agrupar estancias de distintos grupos en una sola factura.
	    for ($i = 0; $i < COUNT($_POST['estancias_gr']); $i++) {
  			echo $_POST['estancias_gr'][$i].'<br>';
  		}
	}		
}
?>