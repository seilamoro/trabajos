<?PHP


if( ($_GET['id_subcomponente']) && ($_GET['id_componente']) ) {
	$sub__ = $_GET['id_subcomponente'];
	$cen__ = $_GET['Id_Centro'];
	$com__ = $_GET['id_componente'];
} else if($_GET['sub__']) {
	$sub__ = $_GET['sub__'];
	$cen__ = $_GET['cen__'];
	$com__ = $_GET['com__'];
} 


session_destroy();


if(isset($_GET['id_subcomponente'])) {
	$_SESSION['Id_subc_prev'] = $_GET['id_subcomponente'];
}



/* BLOQUE DE CONFIGURACIÓN DE LA PÁGINA */

// Datos generales de la página.
$conf['general']['nombre_formulario'] = 'formulario_listado';
$conf['general']['nombre_pagina'] = 'listado_op_preventivas.php';
$conf['general']['nombre_principal'] = 'principal.php';

/*** /INICIO AÑADIR VOLVER 1/ ***/
// Queremos botón de volver (true o false)
$conf['general']['volver'] = true;
/*** /FIN AÑADIR VOLVER 1/ ***/

// Datos referentes a la conexión a mysql.
$conf['database']['host'] = 'localhost';
$conf['database']['user'] = 'root';
$conf['database']['pass'] = '';

// Nombre de la base de datos,nombre de la tabla y array con la clave de la tabla.
$conf['database']['nombre_db'] = 'scies';
$conf['database']['nombre_tabla'] = 'operacion_prev';
$conf['database']['clave_tabla'][0] = 'Id_operacion_prev';

$conf['database']['nombre_tabla_borrar'] = 'operaciones';
$conf['database']['clave_tabla_borrar'][0] = 'Id_Subomponente';
$conf['database']['clave_tabla_borrar'][1] = 'Id_operacion_prev';

$db_link2 = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link2) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");


if($_GET['insert_prev'] == 'si') {
	
	$prev_op = $_GET['insert_prev_op'];
	$sql = "SELECT * FROM operacion_prev WHERE Nombre = '".$prev_op."'";
	$result = mysql_query($sql, $db_link2);
	$total = mysql_num_rows($result);
	for($y=0; $y<$total; $y++) {
		$resultado = mysql_fetch_array($result);
		$id_p = $resultado['Id_operacion_prev'];
	}
	$id_s = $_SESSION['Id_subc_prev'];
	$sql = "INSERT INTO operaciones (Id_Subcomponente, Id_operacion_prev) VALUES (".$id_s.", ".$id_p.")";
	//echo "<br><br><br><br>".$sql;
	$result = mysql_query($sql, $db_link2);
} 


$conf['database']['separador_clave'] = '*';
$conf['database']['campos_sql'] = array();

$conf['database']['campos_sql'][0]['nombre_db'] = 'Nombre';
$conf['database']['campos_sql'][0]['nombre_mostrar'] = 'Nombre';
$conf['database']['campos_sql'][0]['logitud_max'] = '150';
$conf['database']['campos_sql'][0]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][0]['nombre_db'];
$conf['database']['campos_sql'][0]['logitud_intext'] ='70';

$conf['database']['campos_sql'][1]['nombre_db'] = 'Periodicidad';
$conf['database']['campos_sql'][1]['nombre_mostrar'] = 'Per.';
$conf['database']['campos_sql'][1]['logitud_max'] = '4';
$conf['database']['campos_sql'][1]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][1]['nombre_db'];
$conf['database']['campos_sql'][1]['logitud_intext'] = '3';


// Configuración del orden por defecto de la consulta
$conf['resultado_sql']['orden_campo'] = $conf['database']['campos_sql'][0]['nombre_db'];
$conf['resultado_sql']['orden_tipo'] = 'ASC'; //'ASC' para ascendente o 'DESC' para descendente

// Configuración del número de filas que se muestran en pantalla sobre el resultado.
$conf['resultado_sql']['numero_filas'] = 15;
$conf['resultado_sql']['nombre_intext_numero_filas'] = 'numero_filas';
$conf['resultado_sql']['mostrar_pagina'] = 1;
$conf['resultado_sql']['tope_paginas'] = 10;
$conf['resultado_sql']['mostrar_ventana_paginas'] = 1;



// Variables que guardan cadenas que se muestran en el listado.
if (isset($_SESSION['Id_subc_prev'])) {
	$query_name = "SELECT * from operaciones where Id_Subcomponente = '" .$_SESSION['Id_subc_prev']."'";
	$resul_name = mysql_query($query_name, $db_link2);
	$total_name = mysql_num_rows($resul_name);
	for($y=0; $y<$total_name; $y++) {
		$resultado_name = mysql_fetch_array($resul_name);
		$ids_prev[] = $resultado_name['Id_operacion_prev'];
	}
	
	$query_name = "SELECT * from subcomponente where Id_Subcomponente = '" .$_SESSION['Id_subc_prev']."'";
	$resul_name = mysql_query($query_name, $db_link2);
	$total_name = mysql_num_rows($resul_name);
	for($y=0; $y<$total_name; $y++) {
		$resultado_name = mysql_fetch_array($resul_name);
		$nombre = $resultado_name['Nombre'];
	}
	
	$conf['texto']['titulo_tabla'] = "Listado de Operaciones Preventivas<br>Subcomponente: " .$nombre."";
} else {
	$conf['texto']['titulo_tabla'] = "Listado de Operaciones Preventivas";
}

// Variables que guardan cadenas que se muestran en el listado.
//$conf['texto']['titulo_tabla'] = 'Listado de Operaciones Preventivas';
$conf['texto']['mostrar_1'] = 'Mostrar';
$conf['texto']['mostrar_2'] = 'filas';
$conf['texto']['boton_limpiar'] = 'Limpiar<br>Campos';
$conf['texto']['boton_seleccionar'] = 'Selec.<br>Todo';
$conf['texto']['boton_nuevo'] = 'Nuevo';
$conf['texto']['boton_agregar'] = 'Añadir';
$conf['texto']['confirmar_eliminar_seleccionado'] = '¿Seguro que desea eliminar los registros seleccionados?';
$conf['texto']['numero_pagina'] = 'Página';
$conf['texto']['palabra_clave'] = 'Clave';


if($_SESSION['Id_subc_prev']) {
	$conf['menu_contextual'][0]['texto'] = 'Eliminar';
	$conf['menu_contextual'][0]['accion'] = 'quit';
} else {
	$conf['menu_contextual'][0]['texto'] = 'Modificar';
	$conf['menu_contextual'][0]['accion'] = 'modify';
	$conf['menu_contextual'][1]['texto'] = 'Eliminar';
	$conf['menu_contextual'][1]['accion'] = 'remove';
}


/* FIN BLOQUE DE CONFIGURACIÓN DE LA PÁGINA */


/////////////////////////////////////////////////////
/* BLOQUE DE CONFIGURACION DE VARIABLES DE ENTRADA */
/////////////////////////////////////////////////////

// Creamos la consulta con los posibles filtros aplicados

// Orden de la consulta
if (isset($_GET['listado_orden_campo'])) {
	if ($_GET['listado_orden_campo'] != "") {
		$conf['resultado_sql']['orden_campo'] = $_GET['listado_orden_campo'];
	}
}
if (isset($_GET['listado_orden_tipo'])) {
	if ($_GET['listado_orden_tipo'] != "") {
		$conf['resultado_sql']['orden_tipo'] = $_GET['listado_orden_tipo'];
	}
}

$conf['database']['consulta_sql'] = 'SELECT ';

$j = COUNT($conf['database']['campos_sql']);
for ($i = 0; $i < $j; $i++) {
	$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].$conf['database']['campos_sql'][$i]['nombre_db'].',';
}

$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].'CONCAT(';

$j = COUNT($conf['database']['clave_tabla']);
for ($i = 0; $i < $j; $i++) {
	$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].$conf['database']['clave_tabla'][$i].',"'.$conf['database']['separador_clave'].'",';
}

$conf['database']['consulta_sql'] = SUBSTR($conf['database']['consulta_sql'],0,-5).') AS '.$conf['texto']['palabra_clave'];

$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' FROM '.$conf['database']['nombre_tabla'].' WHERE 1';



$j = COUNT($conf['database']['campos_sql']);
for ($i = 0; $i < $j; $i++) {
	if ($_GET[$conf['database']['campos_sql'][$i]['nombre_intext']] != '') {
		$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%"';
	}
}

$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' ORDER BY '.$conf['resultado_sql']['orden_campo'].' '.$conf['resultado_sql']['orden_tipo'];

$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].';';


//SQL
$ids_prev = array();
if (isset($_SESSION['Id_subc_prev'])) {
	$sql = "SELECT * from operaciones where Id_Subcomponente = '" .$_SESSION['Id_subc_prev']."'";
	$resul_name = mysql_query($sql, $db_link2);
	$total_name = mysql_num_rows($resul_name);
	for($y=0; $y<$total_name; $y++) {
		$resultado_name = mysql_fetch_array($resul_name);
		$ids_prev[] = $resultado_name['Id_operacion_prev'];
	}
	$link = "SELECT Id_operacion_prev as Clave,Nombre,Periodicidad FROM operacion_prev WHERE (";
	$q = 0;
	for($p=0; $p<count($ids_prev)-1; $p++) {
		$link = $link." Id_operacion_prev = '".$ids_prev[$p]."' OR ";
		$q++;
	}
	$link = $link." Id_operacion_prev = '".$ids_prev[$q]."')";


	for($g=0; $g<count($conf['database']['campos_sql']); $g++) {
		$link = $link." AND ".$conf['database']['campos_sql'][$g]['nombre_db']." LIKE '".$_GET[$conf['database']['campos_sql'][$g]['nombre_intext']]."%'";
	}
	
	$conf['database']['consulta_sql'] = $link."";
}



// Si se ha pulsado el número de otra página
if (isset($_GET['listado_pagina'])) {
	if ($_GET['listado_pagina'] != "") {
		$conf['resultado_sql']['mostrar_pagina'] = INTVAL($_GET['listado_pagina']);
	}
}
// Si se ha pulsado el número de otra ventana de páginas
if (isset($_GET['listado_ventana'])) {
	if ($_GET['listado_ventana'] != "") {
		$conf['resultado_sql']['mostrar_ventana_paginas'] = INTVAL($_GET['listado_ventana']);
	}
}

// Insertar un nuevo registro en la tabla
if ($_GET['listado_accion'] == 'new') {
	

	
	/***** CÓDIGO DE JOSE *****/
	//Calcula el número de operación preventiva que corresponde.
	//Conexión a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("information_schema");
	$sql2 = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_NAME = 'operacion_prev'";
	$result = mysql_query($sql2);
	for ($m=0;$m<mysql_num_rows($result);$m++){
		$fila = mysql_fetch_array($result);
		$num_op_p = $fila['AUTO_INCREMENT'];
	}
	
	
	$nom = $_GET['nintext_Nombre'];
	$per = $_GET['nintext_Periodicidad'];

	if(($nom != "") && ($per != "")) {	
	$nom = "'".$nom."'";
	//Conexión a la base de datos. 
	@ $db = mysql_pconnect("localhost" , "root" , "");
	if (!$db){
		echo "Error: No se ha podido conectar a la base de datos";
		exit;
	}
	mysql_select_db("scies");
	
		$sql = "INSERT INTO operacion_prev (Id_operacion_prev, Nombre, Periodicidad) VALUES (".$num_op_p.", ".$nom.", ".$per.")";
		//echo "<br><br><br><br>".$sql;
		$result = mysql_query($sql);
	}


	/***** FIN DE CÓDIGO DE JOSE *****/
	

	
	/* Connecting, selecting database */
	$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
	/* Performing SQL query */
	$result = MYSQL_QUERY($sql,$db_link);
	/* Closing connection */
	MYSQL_CLOSE($db_link);
	//}
}

// Número de filas a mostrar en pantalla

if ((isset($_GET['numero_filas']))&&(floor($_GET['numero_filas']) > 0)) {
	$conf['resultado_sql']['numero_filas'] = $_GET['numero_filas'];
}

/* SI HEMOS ESCOGIDO UNA OPCIÓN EN EL MENÚ CONTEXTUAL */

if ($_GET['menu_accion'] != '') {
	$conf['menu_accion'] = $_GET['menu_accion'];
	$conf['menu_valor'] = $_GET['menu_valor'];
	if ($conf['menu_accion'] == 'rs') { // Remove selected - Eliminar campos selecionados
		if (count($_GET['checks']) > 0) {
			$conf['remove_selected'] = TRUE;
		}
	}
	else {
		$conf['remove_selected'] = FALSE;
	}
}
else {
	$conf['remove_selected'] = FALSE;
	$conf['menu_accion'] = NULL;
	$conf['menu_valor'] = NULL;
}

//echo '<br><br><br>'.$conf['menu_accion'].' - '.$conf['menu_valor'];
if( $conf['menu_accion'] == 'quit' ) {
	$sql = "DELETE FROM operaciones WHERE Id_Subcomponente = '".$_GET['id_subcomponente']."' AND Id_operacion_prev = '".$_GET['menu_valor']."'";
	$result = mysql_query($sql); 
	$_SESSION['insertar_nueva_opcion'] = "si";
	$op_a_insertar = $_GET['menu_valor'];
}



if (($_GET['confirmar_modificar'] != '') && ($_GET['mintext_Periodicidad'] != '') && ($_GET['mintext_Nombre'] != '')) {
	$sql = "UPDATE ".$conf['database']['nombre_tabla'].' SET ';
	$tope = count($conf['database']['campos_sql']);
	for ($i = 0; $i < $tope; $i++) {
		$sql = $sql.$conf['database']['campos_sql'][$i]['nombre_db'].'="'.$_GET['m'.$conf['database']['campos_sql'][$i]['nombre_intext']].'",';		
	}
	$sql = SUBSTR($sql,0,-1).' WHERE ';
	$tope = count($conf['database']['clave_tabla']);
	// $conf['database']['clave_tabla'][0] = 'Id_Pais';
	$aux = split('\\'.$conf['database']['separador_clave'],$_GET['confirmar_modificar']);
	for ($i = 0; $i < $tope; $i++) {
		$sql = $sql.$conf['database']['clave_tabla'][$i].' LIKE "'.$aux[$i].'" AND ';		
	}
	$sql = SUBSTR($sql,0,-5).';';
	$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
	/* Performing SQL query */
	$result = MYSQL_QUERY($sql,$db_link);
	/* Closing connection */
	MYSQL_CLOSE($db_link);
}

if ($_GET['confirmar_eliminar'] != '') {
	if ($_GET['confirmar_eliminar'] == 'rs') {
		$j = COUNT($_GET['checks']);
		for ($i = 0; $i < $j; $i++) {
			$sql = 'DELETE FROM '.$conf['database']['nombre_tabla'].' WHERE ';
			$tope = count($conf['database']['clave_tabla']);
			// $conf['database']['clave_tabla'][0] = 'Id_Pais';
			$aux = split('\\'.$conf['database']['separador_clave'],$_GET['checks'][$i]);
			for ($k = 0; $k < $tope; $k++) {
				$sql = $sql.$conf['database']['clave_tabla'][$k].' LIKE "'.$aux[$k].'" AND ';		
			}
			$sql = SUBSTR($sql,0,-5).';';

			$sql_2 = "DELETE FROM operaciones WHERE Id_operacion_prev = '".$_GET['confirmar_eliminar']."'";

			$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
			if (!$db_link) {
			   die("Could not connect: ".MYSQL_ERROR());
			}
		
			
			
			MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
			/* Performing SQL query */
			$result = MYSQL_QUERY($sql, $db_link);
			$result_2 = MYSQL_QUERY($sql_2, $db_link);
			/* Closing connection */
			//MYSQL_CLOSE($db_link);
		}
	}
	else {
		$sql = 'DELETE FROM '.$conf['database']['nombre_tabla'].' WHERE ';
		$tope = count($conf['database']['clave_tabla']);
		// $conf['database']['clave_tabla'][0] = 'Id_Pais';
		$aux = split('\\'.$conf['database']['separador_clave'],$_GET['confirmar_eliminar']);
		for ($i = 0; $i < $tope; $i++) {
			$sql = $sql.$conf['database']['clave_tabla'][$i].' LIKE "'.$aux[$i].'" AND ';		
		}
		$sql = SUBSTR($sql,0,-5).';';
		$sql_2 = "DELETE FROM operaciones WHERE Id_operacion_prev LIKE '".$_GET['confirmar_eliminar']."';";
		
		$db_link = 
		MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
		if (!$db_link) {
		   die("Could not connect: ".MYSQL_ERROR());
		}
		MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
		/* Performing SQL query */
		$result = MYSQL_QUERY($sql, $db_link);
		$result_2 = MYSQL_QUERY($sql_2, $db_link);
		/* Closing connection */
		MYSQL_CLOSE($db_link);
	}
}

/* FIN MENÚ CONTEXTUAL */

/////////////////////////////////////////////////////////
/* FIN BLOQUE DE CONFIGURACION DE VARIABLES DE ENTRADA */
/////////////////////////////////////////////////////////

?>

<html>

<head>
	<link rel="StyleSheet" href="./css/listados.css" type="text/css">
</head>

<body>


<script language="JavaScript">

	function volver_param() {
		location.href='principal.php?pag=listado_subcomponentes.php&id_componente='+document.getElementById('com__').value+'&id_subcomponente='+document.getElementById('sub__').value+'&id_Centro='+document.getElementById('cen__').value;
	}



	function insertar_prev() {
		var op_pr = document.getElementById('op_preve').value;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.insert_prev.value = "si";
		<?PHP echo $conf['general']['nombre_formulario']; ?>.insert_prev_op.value = op_pr;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}
	

	function cambiar_pagina(valor) {		
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_pagina.value = valor;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}

	function cambiar_ventana_paginas(valor) {
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_ventana.value = valor;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}

	function nuevo() {		
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_accion.value = 'new';
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}
	
	function filtrar(foco) {
		var tecla = event.keyCode;
	  	if (tecla == 13) {
			<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_foco.value = foco;
			if (foco != -1) {
				<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_pagina.value = 1;
			}
			document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
		}
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
	
	// Función que recarga la página con el fin
	// de ordenar por algún campo las filas.
	function ordenar(campo,tipo) {
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_orden_campo.value = campo;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_orden_tipo.value = tipo;
		document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}
	
</script>

<?PHP
	/* Connecting, selecting database */
	$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
	/* Performing SQL query */
		  	
  	$result = MYSQL_QUERY($conf['database']['consulta_sql'],$db_link);
	/* Closing connection */
	MYSQL_CLOSE($db_link);
  	
  	if (MYSQL_NUM_ROWS($result) >= 0) {
?>

<form name="<?PHP echo $conf['general']['nombre_formulario']; ?>" action="#">

<input type='hidden' name='insert_prev' value='no'>
<input type='hidden' name='insert_prev_op' value=''>


<input type="hidden" name="listado_accion" value="">

<input type="hidden" name="pag" value="<?PHP echo $conf['general']['nombre_pagina']; ?>">

<input type="hidden" name="listado_pagina" value="<?PHP echo $conf['resultado_sql']['mostrar_pagina']; ?>">
<input type="hidden" name="listado_ventana" value="<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas']; ?>">

<input type="hidden" name="listado_orden_campo" value="<?PHP echo $conf['resultado_sql']['orden_campo']; ?>">
<input type="hidden" name="listado_orden_tipo" value="<?PHP echo $conf['resultado_sql']['orden_tipo']; ?>">

<input type="hidden" name="listado_foco" value="">

<input type="hidden" name="menu_valor" value="">
<input type="hidden" name="menu_accion" value="">

<input type="hidden" name="confirmar_modificar" value="">
<input type="hidden" name="confirmar_eliminar" value="">

<input type="hidden" name="sub__" id="sub__" value="<?php echo $sub__; ?>">
<input type="hidden" name="cen__" id="cen__" value="<?php echo $cen__; ?>">
<input type="hidden" name="com__" id="com__" value="<?php echo $com__; ?>">

<?PHP
		if (isset($_SESSION['Id_subc_prev'])) {
?>

<input type="hidden" name="id_subcomponente" value="<?PHP echo $_GET['id_subcomponente']; ?>">

<?PHP
		}
?>

	<table class="listados" border='0' width='80%'>
		<caption>
<?PHP
		echo $conf['texto']['titulo_tabla'];
?>
		</caption>
		<thead>
			<tr>
<?PHP
		$script = '<SCRIPT language="JavaScript">'; // Construiremos el script para limpiar los campos de texto
		$script = $script.'function limpiar_campos() {';
		
		$tope = COUNT($conf['database']['campos_sql']);
?>

				
				<!--FILA 1. COLUMNA "SELECCIONAR TODO". -->
				<th class="boton" id="borde_idb" width='10%'>
					<a class="boton" href="#">
						<div class="boton" <?PHP if ($conf['remove_selected'] == FALSE) { ?> onclick="seleccionar_todo();" <?PHP } ?> >
<?PHP
		echo $conf['texto']['boton_seleccionar'];
?>
						</div>
					</a>
				</th>
				
<?PHP		
		for ($i = 0; $i < $tope; $i++) {
				
				if($i==0) {
					$anchura = '70%';
				} else if($i==1) {
					$anchura = '10%';
				}
?>
				
				<!--FILA 1. COLUMNAS RELATIVAS A CAMPOS. -->
				<th class="titulo" id="borde_db" width='<? echo $anchura; ?>'>
					<div class="borde"></div>
					<div class="centro">
<?PHP		
			if ($conf['resultado_sql']['orden_campo'] == $conf['database']['campos_sql'][$i]['nombre_db']) {
				if ($conf['resultado_sql']['orden_tipo'] == 'ASC') {
?>
						<a class="nombre" href="#" onclick="ordenar('<?PHP echo $conf['database']['campos_sql'][$i]['nombre_db']; ?>','DESC');">
<?PHP
				}
				else {
?>
						<a class="nombre" href="#" onclick="ordenar('<?PHP echo $conf['database']['campos_sql'][$i]['nombre_db']; ?>','ASC');">
<?PHP
				}
			}
			else {
?>
						<a class="nombre" href="#" onclick="ordenar('<?PHP echo $conf['database']['campos_sql'][$i]['nombre_db']; ?>','ASC');">
<?PHP
			}
?>						
							<div>
<?PHP
			echo $conf['database']['campos_sql'][$i]['nombre_mostrar'];
?>
							</div>
						</a>
						<div class="intexto">
							<input type="text" name="<?PHP echo $conf['database']['campos_sql'][$i]['nombre_intext']; ?>" value="<?PHP echo $_GET[$conf['database']['campos_sql'][$i]['nombre_intext']]; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_max']; ?>" onkeyup="filtrar(<?PHP echo $i; ?>);">
<?PHP
			// Continuación del script de limpiar campos
			$script = $script.$conf['general']['nombre_formulario'].'.'.$conf['database']['campos_sql'][$i]['nombre_intext'].'.value = "";';
?>
						</div>
					</div>
				</th>
<?PHP
		}
		
		// Terminamos de construir el script
		$script = $script.'}</script>';
		echo $script;
?>
				
				
				<!-- FILA 1. LIMPIAR TODO. -->
				<th colspan="2" rowspan="2" class="boton" id="borde_idb" width='10%'>
					<a class="boton" href="#" onclick="limpiar_campos();">
						<div class="boton">
<?PHP
		echo $conf['texto']['boton_limpiar'];
?>
						</div>
					</a>
				</th>
			</tr>
		</thead>


<tfoot>

<?php
	
	if ($conf['remove_selected'] == TRUE) {

?>
		<tr style="height:34px;">
			<th colspan="<? echo COUNT($conf['database']['campos_sql']) + 1; ?>" id="borde_i">
			<div class="intexto">

		<?php

			echo $conf['texto']['confirmar_eliminar_seleccionado'];
		?>
			</div>
			</th>
				
			<a href="#" class="confirmar_aceptar">
			<th onclick="confirmarEliminar('rs');" title="Eliminar los Registros">
			</th>
			</a>
				
			<a href="#" class="confirmar_cancelar">
			<th onclick="confirmarEliminar('NULL');" title="Cancelar" id="borde_d">
			</th>
			</a>
			
		</tr>

<?php
	
	} else {						

?>	
		<tr>
			<th id="borde_i" align='center'>
			<a href="#" align='center' class="papelera">
			<div style="float:left; width:16px;">
			</div>
			<div class="papelera" title="Borrar entradas seleccionadas" onclick="enviarAccion('rs','sel');">
			</div>
			</a>
			</th>

		<?php
	
			$tope = COUNT($conf['database']['campos_sql']);
			for ($i = 0; $i < $tope; $i++) {
				if ($conf['database']['campos_sql'][$i]['rango_valores'] == TRUE) {
					if ($conf['database']['campos_sql'][$i]['foreign'] == TRUE) {

		?>
						<th>
						<div class="intexto">
							<select name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>">

						<?php
							
							$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
							if (!$db_link) {
							   die("Could not connect: ".MYSQL_ERROR());
							}
							MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
							/* Performing SQL query */
							$result_select = MYSQL_QUERY("SELECT ".$conf['database']['campos_sql'][$i]['campo_id'].",".$conf['database']['campos_sql'][$i]['campo_texto']." FROM ".$conf['database']['campos_sql'][$i]['tabla']." WHERE 1;", $db_link);
							//echo $result;
							/* Closing connection */
							MYSQL_CLOSE($db_link);
							for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) {
								$option = MYSQL_FETCH_ARRAY($result_select);

						?>
							<OPTION value="<?PHP echo $option[$conf['database']['campos_sql'][$i]['campo_id']]?>"><?PHP echo $option[$conf['database']['campos_sql'][$i]['campo_texto']];?>

				<?php

					}
			?>
						</select>
					</div>
				</th>
<?PHP
					}
					else {		
					
?>
				<th>
					<div class="intexto">
						<select name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>">
<?PHP
							for ($m = 0; $m < COUNT($conf['database']['campos_sql'][$i]['valores_id']); $m++) {
?>
								<OPTION value="<?PHP echo $conf['database']['campos_sql'][$i]['valores_id'][$m]?>"><?PHP echo $conf['database']['campos_sql'][$i]['valores_texto'][$m];?>
<?PHP
							}
?>
						</select>
					</div>
				</th>
<?PHP
					}
				}
				else {			
?>
				
				
				
							
<th>
<div class="intexto">
					
<?php
						
	if($_SESSION['Id_subc_prev']) {
		
		if($i == 0) { 
		
?>
				
			<select id='op_preve' name='op_preve'> 
			
		<?php
			$db_link2 = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
			if (!$db_link2) {
			 die("Could not connect: ".MYSQL_ERROR());
			}
			MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");

			$array_op = array();
			$sql1 = "SELECT * from operacion_prev";
			$result1 = mysql_query($sql1, $db_link2);
			$total1 = mysql_num_rows($result1);
			$primer = 1;
			for($y=0; $y<$total1; $y++) {
				$resultado1 = mysql_fetch_array($result1);
				$nom = $resultado1['Nombre'];
				$nom_trunc = substr($nom, 0,63);
				$esta = 'si';
				for($r=0; $r<count($ids_prev); $r++) {
					if($ids_prev[$r] == $resultado1['Id_operacion_prev']) {
						$esta = 'no';
					} 
				}
				if($esta == 'si') {
					echo "<option value='".$nom."' title='".$nom."'>".$nom_trunc;
					$esta = 'no';
					$array_op[] = $resultado1['Id_operacion_prev'];
				}
			}
			$anadir = 'si';
			for($f=0; $f<count($array_op); $f++) {
				if($array_op[$f] == $op_a_insertar) {
					$anadir = 'no';
				}
			}
			if($anadir == 'si') {
				if($_SESSION['insertar_nueva_opcion'] == "si") {  
					$s = "SELECT * from operacion_prev WHERE Id_operacion_prev ='".$op_a_insertar."'";
					$re = mysql_query($s);
					$tot = mysql_num_rows($re);
					for($n=0; $n<$tot; $n++) {
						$re1 = mysql_fetch_array($re);
						$nom_a_insertar = $re1['Nombre'];
					} ?>
					<option value='<? echo $nom_a_insertar; ?>' title='<? echo $nom_a_insertar; ?>'><? echo substr($nom_a_insertar,0,63); 
				}
			}
		?>
					
			</select>
								
	<?php		
			
		}
	} else {
					
	?>
						
		<input type="text" name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_max']; ?>">
					
	<?php 
		
	} 
	
	?>
					
	</div>
</th>




<?PHP
				}
			}
?>
				<th colspan="2" id="borde_d">
					<a class="boton2" href="#">
						
<?PHP
			if($_SESSION['Id_subc_prev']) { ?>
				<div class="boton" onclick="insertar_prev();">
			<?
				echo $conf['texto']['boton_agregar'];
			} else { ?>
				<div class="boton" onclick="nuevo();">
			<?
				echo $conf['texto']['boton_nuevo'];
			}
?>
						</div>
					</a>
				</th>
			</tr>
<?PHP
		}
?>
			<tr>
				<th colspan="<?PHP echo COUNT($conf['database']['campos_sql']) + 3; ?>" class="paginas" id="borde_idb">
					<div id="fleft">
<?PHP
		/*** /INICIO AÑADIR VOLVER 2/ ***/
			if($_SESSION['Id_subc_prev']) {
?>
					<a href="#" align='center' class="volver">
						<div style="float: left; width:4px;">
						</div>
						<div class="volver" title="Página Anterior" onclick="volver_param();">
						</div>
						<div style="float: left; width:20px;">
						</div>
					</a>
			
<?PHP
		} else {

			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}	
		/*** /FIN AÑADIR VOLVER 2/ ***/

		echo $conf['texto']['numero_pagina'];
		
		$resto = INTVAL(MYSQL_NUM_ROWS($result) / $conf['resultado_sql']['numero_filas']);
		if (MYSQL_NUM_ROWS($result) % $conf['resultado_sql']['numero_filas'] > 0) {
			$resto++;
		}
		
		if ($conf['resultado_sql']['mostrar_pagina'] > $resto) {
			$conf['resultado_sql']['mostrar_pagina'] = 1;
		}
		
		$ventana_paginas = INTVAL($resto / $conf['resultado_sql']['tope_paginas']);
		if ($resto % $conf['resultado_sql']['tope_paginas'] > 0) {
			$ventana_paginas++;
		}
		
		if ($conf['resultado_sql']['mostrar_ventana_paginas'] > $ventana_paginas) {
			$conf['resultado_sql']['mostrar_ventana_paginas'] = 1;
		}
		
		if (($ventana_paginas > 1) && ($conf['resultado_sql']['mostrar_ventana_paginas'] > 1)) {
?>
					<a class="noseleccionada" href="#" onclick="cambiar_ventana_paginas(<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas'] - 1; ?>);"><...</a>
<?PHP
		}
		
		for ($i = ($conf['resultado_sql']['mostrar_ventana_paginas'] - 1) * $conf['resultado_sql']['tope_paginas'] + 1; $i <= $conf['resultado_sql']['mostrar_ventana_paginas'] * $conf['resultado_sql']['tope_paginas']; $i++) {
			if ($i <= $resto) {
				if ($i == $conf['resultado_sql']['mostrar_pagina']) {
?>
					<a class="seleccionada"><?PHP echo $i; ?></a>
<?PHP
				}
				else {
?>
					<a class="noseleccionada" href="#" onclick="cambiar_pagina(<?PHP echo $i; ?>);"><?PHP echo $i; ?></a>
<?PHP
				}
			}
			else {
				$i = $conf['resultado_sql']['mostrar_ventana_paginas'] * $conf['resultado_sql']['tope_paginas'] + 1;
			}
		}

		if (($ventana_paginas > 1) && ($conf['resultado_sql']['mostrar_ventana_paginas'] < $ventana_paginas)) {
?>
					<a class="noseleccionada" href="#" onclick="cambiar_ventana_paginas(<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas'] + 1; ?>);">...></a>
<?PHP
		}
?>

					</div>

					


					<? if($_SESSION['Id_subc_prev']) { ?>
				
					
					<div class="intexto" id="fright">
						<!--<a href="principal.php?pag=listado_op_preventivas.php" title="Ver todas las operaciones preventivas" class='intexto'>Ver Todas</a>-->
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						Mostrar
						<input type="text" name="<?PHP echo $conf['resultado_sql']['nombre_intext_numero_filas']; ?>" class="tresultados" size="1" value="<?PHP echo $conf['resultado_sql']['numero_filas']; ?>" onkeyup="filtrar(-1);">
						filas
					</div>


					<? } else { ?>


					<div class="intexto" id="fright">
<?PHP
			echo $conf['texto']['mostrar_1'];
?>
						<input type="text" name="<?PHP echo $conf['resultado_sql']['nombre_intext_numero_filas']; ?>" class="tresultados" size="1" value="<?PHP echo $conf['resultado_sql']['numero_filas']; ?>" onkeyup="filtrar(-1);">
<?PHP
			echo $conf['texto']['mostrar_2'];
?>
					</div>

			<? } ?>
				</th>
			</tr>
		</tfoot>




<?PHP
		// Buscar las filas requeridas dependiendo de la página seleccionada:
		$inicio = ($conf['resultado_sql']['mostrar_pagina'] - 1) * $conf['resultado_sql']['numero_filas'];		
		
		for ($i = 0; $i < $inicio; $i++) {
			$fila = MYSQL_FETCH_ARRAY($result,MYSQL_BOTH);
		}
		// Fin Buscar...
		
?>
		<tbody>
			<tr class="blanco">
			</tr>

<?PHP
		$pasa = MYSQL_NUM_ROWS($result) - $conf['resultado_sql']['mostrar_pagina'] * $conf['resultado_sql']['numero_filas'];
		if ($pasa < 0) {
			$tope = $conf['resultado_sql']['numero_filas'] + $pasa;
		}
		else {
			$tope = $conf['resultado_sql']['numero_filas'];
		}

		if ($conf['remove_selected'] == TRUE) {
			$script_seleccionar = "<script>";
		}
		else {
			$script_seleccionar = "<script> function seleccionar_todo() {";
		}
		
		for ($i = 0; $i < $tope; $i++) {
			$fila = MYSQL_FETCH_ARRAY($result,MYSQL_BOTH);

			if ($conf['menu_valor'] == $fila['Clave']) {
				//echo '<br>'.$conf['menu_valor'].' . '.$fila['Clave'].'<br>';
				if ($conf['menu_accion'] == 'modify') {
				
				
				/* MODIFICAR REGISTRO */
?>
				<tr id="<?PHP echo $fila['Clave']; ?>" class="accion">
					<td id="borde_i" align="center" class="accion">						
					</td>
<?PHP	
					$tope2 = COUNT($conf['database']['campos_sql']);
					for ($j = 0; $j < $tope2; $j++) {				
?>
					<td class="accion">
						<div class="intexto">
							<input type="text" name="<?PHP echo 'm'.$conf['database']['campos_sql'][$j]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_max']; ?>" value="<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
						</div>
					</td>
<?PHP	
					}
?>
					<a href="#" class="confirmar_aceptar">
						<td onclick="confirmarModificar('<?PHP echo $conf['menu_valor']; ?>');" title="Aceptar Modificación">
						</td>
					</a>
					<a href="#" class="confirmar_cancelar">
						<td onclick="confirmarModificar('NULL');" title="Cancelar" id="borde_d">
						</td>
					</a>
				</tr>
<?PHP
				/* FIN MODIFICAR REGISTRO */
				}					
				else if ($conf['menu_accion'] == 'remove') {
				/* ELIMINAR REGISTRO */
?>
				<tr id="<?PHP echo $fila['Clave']; ?>" class="accion">
					<td id="borde_i" align="center" class="accion">
					</td>
<?PHP	
				$tope2 = COUNT($conf['database']['campos_sql']);
				for ($j = 0; $j < $tope2; $j++) {				
?>
					<td class="accion">
<?PHP
					echo substr($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,70);
?>
					</td>
<?PHP	
				}
?>					<a href="#" class="confirmar_aceptar">
						<td onclick="confirmarEliminar('<?PHP echo $conf['menu_valor']; ?>');" title="Eliminar el Registro">
						</td>
					</a>
					<a href="#" class="confirmar_cancelar">
						<td onclick="confirmarEliminar('NULL');" title="Cancelar" id="borde_d">
						</td>
					</a>
				</tr>
<?PHP
				/* FIN ELIMINAR REGISTRO */
				}					
				//else if ... {
					/* Preparado para otra función del menú contextual */
				//}
			}
			else {
?>
			<a href="#">
				<tr id="<?PHP echo $fila['Clave']; ?>" oncontextmenu="mostrarMenu('<?PHP echo $fila['Clave']; ?>');">
					
					<!-- FILA 2. CUADRADO. -->
					<td id="borde_i" align="center" style="padding:0 0 0 0;">
						<div class="checkbox">
<?PHP
				if ($conf['remove_selected'] == TRUE) {
					if (IN_ARRAY($fila['Clave'],$_GET['checks'])) {
						$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
?>
							<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>">
								<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
							</div>
<?PHP
					}
				}
				else {
					$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
?>							
							<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>" onclick="pulsarCheckbox('checkbox_<?PHP echo $fila['Clave']; ?>', this.id);">
								<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
							</div>
<?PHP
				}
?>
						</div>
					</td>
<?PHP	
				$tope2 = COUNT($conf['database']['campos_sql']);
				for ($j = 0; $j < $tope2; $j++) {				
?>
					<td title='<? echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>'>
<?PHP
					if (strlen($fila[$conf['database']['campos_sql'][$j]['nombre_db']]) < 70) {
						echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']];
					} else {
						echo substr($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,70);
					}
?>
					</td>
<?PHP	
				}
?>
					<td>
						<!--<input type="image" src="./modificar">-->
					</td>
					<td id="borde_d">
						<!--<input type="image" src="./eliminar">-->
					</td>
				</tr>
			</a>
<?PHP
			}
		}
		if ($conf['remove_selected'] == TRUE) {
			$script_seleccionar = $script_seleccionar." </script>";
		}
		else {
			$script_seleccionar = $script_seleccionar."} </script>";
		}
?>
		</tbody>
	</table>


<div id="menu" style="position:absolute;visibility:hidden;padding:0 0 0 0;margin:0px;">
	<div class="item_esquina"></div>
	<table cellpadding=0 cellspacing=0>
	
	
<?PHP
		$tope = count($conf['menu_contextual']);
		for ($i = 0; $i < $tope; $i++) {
?>
		<tr>
			<td style="padding: 0 0 0 0;">
				<div class="item_izq"></div>
<?PHP
			if ($conf['menu_contextual'][$i]['link'] != '') {
?>
				<div class="item" align="center" style="width:100px;" onclick="irLink('<?PHP echo $conf['menu_contextual'][$i]['link']; ?>');">
<?PHP
			}
			else { 
?>
				<div class="item" align="center" style="width:100px;" onclick="enviarAccion('<?PHP echo $conf['menu_contextual'][$i]['accion']; ?>');">
<?PHP
			}
			echo $conf['menu_contextual'][$i]['texto'];
?>
				</div>
				<div class="item_der"></div>
			</td>
		</tr>
<?PHP
		}
?>
	</table>
</div>
			
<script language="javascript">

function irLink(pag) {
	window.location.href('<? echo $conf['general']['nombre_principal']; ?>?pag=' + pag + '&clave=' + document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_valor.value);
}

function pulsarCheckbox(hiddenChkBox,skinnedCheckBoxId) {
	if(!document.getElementById(hiddenChkBox).disabled)	{
		if(document.getElementById(hiddenChkBox).checked) {
			document.getElementById(skinnedCheckBoxId).className = 'unchecked';
			document.getElementById(hiddenChkBox).checked = false;
		}
		else {
			document.getElementById(skinnedCheckBoxId).className = 'checked';
			document.getElementById(hiddenChkBox).checked = true;
		}
	}
}

function confirmarModificar(clave) {
	if (clave != 'NULL') {
		document.<?PHP echo $conf['general']['nombre_formulario']; ?>.confirmar_modificar.value = clave;
	}
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
}

function confirmarEliminar(clave) {
	if (clave != 'NULL') {
		document.<?PHP echo $conf['general']['nombre_formulario']; ?>.confirmar_eliminar.value = clave;
	}
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
}

function enviarAccion(accion) {
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_accion.value = accion;
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
}

function mostrarMenu(valor) {
	var rightedge = document.body.clientWidth - event.clientX;
	var bottomedge = document.body.clientHeight - event.clientY;
	if (rightedge < menu.offsetWidth)
		menu.style.left = document.body.scrollLeft + event.clientX - menu.offsetWidth;
	else
		menu.style.left = document.body.scrollLeft + event.clientX;
	if (bottomedge < menu.offsetHeight)
		menu.style.top = document.body.scrollTop + event.clientY - menu.offsetHeight;
	else
		menu.style.top = document.body.scrollTop + event.clientY;
	document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_valor.value = valor;
	menu.style.visibility = "visible";
}

function mostrarMenuNormal() {
	if (menu.style.visibility == "visible") {
		return false;
	}
	else {
		return true;
	}
}

function ocultarMenu() {
	menu.style.visibility = "hidden";
}

document.body.oncontextmenu = mostrarMenuNormal;
document.body.onclick = ocultarMenu;

</script>

</form>

<?PHP
		echo $script_seleccionar;		
	}
?>

</body>

<?PHP

// Devolvemos el foco de la página a los campos de filtrado
echo '<SCRIPT language"JavaScript">';
if ($_GET['listado_foco'] != '') {
	if ($_GET['listado_foco'] == '-1') { // Para el campo de mostrar filas.
		echo 'foco('.$conf['general']['nombre_formulario'].'.'.$conf['resultado_sql']['nombre_intext_numero_filas'].','.STRLEN($conf['general']['nombre_formulario'].'.'.$conf['resultado_sql']['nombre_intext_numero_filas'].'value').');';
	}
	else {
	    echo 'foco('.$conf['general']['nombre_formulario'].'.'.'intext_'.$conf['database']['campos_sql'][INTVAL($_GET['listado_foco'])]['nombre_db'].','.STRLEN($_GET[$conf['database']['campos_sql'][INTVAL($_GET['listado_foco'])]['nombre_intext']]).');';
	}
  	unset($_GET['listado_foco']);
}	
echo '</SCRIPT>';


	
?>

</html>