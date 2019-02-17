<?PHP

	//echo $_REQUEST['eliminar'];
	if(isset($_GET['Id_Centro']))
	{
		if($_GET['Id_Centro']=='todos')
		{
			$_SESSION['todos']=1;
		}
		else
		{
			$_SESSION['todos']=0;
		}
		$_SESSION['id_centro_comp']=$_GET['Id_Centro'];
	}
	else
	{
		$_SESSION['todos']=1;
	}

	
	
/* BLOQUE DE CONFIGURACIÓN DE LA PÁGINA */
?>
	
<?php
// Datos generales de la página.
$conf['general']['nombre_formulario'] = 'formulario_listado';

// Datos referentes a la conexión a mysql.
$conf['database']['host'] = 'localhost';
$conf['database']['user'] = 'root';
$conf['database']['pass'] = '';

// Nombre de la base de datos,nombre de la tabla y array con la clave de la tabla.
$conf['database']['nombre_db'] = 'scies';
$conf['database']['nombre_tabla'] = 'componente';
$conf['database']['clave_tabla'][0] = 'Id_Componente';

if($_REQUEST['eliminar']!="")
	{
		$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
		if (!$db_link) {
			die("Could not connect: ".MYSQL_ERROR());
		}
		$sql_eliminar="delete from componente where Id_Componente = " .$_REQUEST['eliminar_componente'];
		//echo $sql_eliminar;
		MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
		$result = MYSQL_QUERY($sql_eliminar,$db_link);	
	}

if($_REQUEST['eliminar_varios']!="")
	{
		$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
		if (!$db_link) {
			die("Could not connect: ".MYSQL_ERROR());
		}
		
			$query="delete from componente where Id_Componente = ";
			$j = COUNT($_SESSION['varios_comp']);
			for ($i = 0; $i < $j; $i++) 
			{
				if($i==0)
				{
					$query .= $_SESSION['varios_comp'][$i];
				}
				else
				{
					$query .= " OR Id_Componente = " .$_SESSION['varios_comp'][$i];
				}
			}
		//echo $query;
		MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");
		$result = MYSQL_QUERY($query,$db_link);	
	}

/*** /INICIO AÑADIR VOLVER 1/ ***/
// Queremos botón de volver (true o false)
$conf['general']['volver'] = true;
/*** /FIN AÑADIR VOLVER 1/ ***/

$db_link2 = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link2) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");

// Matriz que contiene datos sobre los campos que nos devuelve la consulta.
// Para cada campo se crea un elemento de la matriz con varios subelementos. Ej:
// 
// La consulta devuelve dos campos: 'dni_persona' que es un VARCHAR(20) y 'nombre_persona' también VARCHAR(30).
// Si queremos mostrar en el listado los campos 'D.N.I.' y 'Nombre', debemos hacer lo siguiente:
//
// $conf['database']['campos_sql'] = array();
//
// $conf['database']['campos_sql'][0]['nombre_db'] = 'Id_Pais';
// $conf['database']['campos_sql'][0]['nombre_mostrar'] = 'ID';
// $conf['database']['campos_sql'][0]['longitud_max'] = '2';
// $conf['database']['campos_sql'][0]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][0]['nombre_db'];
// $conf['database']['campos_sql'][0]['longitud_intext'] = '5';
//
// $conf['database']['campos_sql'][1]['nombre_db'] = 'Nombre_Pais';
// $conf['database']['campos_sql'][1]['nombre_mostrar'] = 'Nombre País';
// $conf['database']['campos_sql'][1]['longitud_max'] = '50';
// $conf['database']['campos_sql'][1]['nombre_intext'] = 'intext'.$conf['database']['campos_sql'][1]['nombre_db'];
// $conf['database']['campos_sql'][1]['longitud_intext'] = '50';
// $conf['database']['campos_sql'][0]['ancho'] ='32'; Esto se usa para truncar la longitud del valor que devuelva la base de datos haceindo un substr con la cantidad indicada en esta variable de sesión

$conf['database']['separador_clave'] = '*';
$conf['database']['campos_sql'] = array();

$conf['database']['campos_sql'][0]['nombre_db'] = 'Nombre';
$conf['database']['campos_sql'][0]['nombre_mostrar'] = 'Nombre';
$conf['database']['campos_sql'][0]['longitud_max'] = '50';
$conf['database']['campos_sql'][0]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][0]['nombre_db'];
$conf['database']['campos_sql'][0]['longitud_intext'] ='25';
$conf['database']['campos_sql'][0]['ancho'] ='22';
$conf['database']['campos_sql'][0]['foreign'] = FALSE;
$conf['database']['campos_sql'][0]['rango_valores'] = FALSE;

$conf['database']['campos_sql'][1]['nombre_db'] = 'Marca';
$conf['database']['campos_sql'][1]['nombre_mostrar'] = 'Marca';
$conf['database']['campos_sql'][1]['longitud_max'] = '50';
$conf['database']['campos_sql'][1]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][1]['nombre_db'];
$conf['database']['campos_sql'][1]['longitud_intext'] = '17';
$conf['database']['campos_sql'][1]['ancho'] ='16';
$conf['database']['campos_sql'][1]['foreign'] = FALSE;
$conf['database']['campos_sql'][1]['rango_valores'] = FALSE;

$conf['database']['campos_sql'][2]['nombre_db'] = 'Modelo';
$conf['database']['campos_sql'][2]['nombre_mostrar'] = 'Modelo';
$conf['database']['campos_sql'][2]['longitud_max'] = '50';
$conf['database']['campos_sql'][2]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][2]['nombre_db'];
$conf['database']['campos_sql'][2]['longitud_intext'] = '17';
$conf['database']['campos_sql'][2]['foreign'] = FALSE;
$conf['database']['campos_sql'][2]['ancho'] ='16';
$conf['database']['campos_sql'][2]['rango_valores'] = FALSE;

if( (  ($_GET['Id_Centro'] != '')&&($_GET['Id_Centro'] != 'todos') ) || (($_GET['id_centro2'] != '')&&($_GET['id_centro2'] != 'todos')) )
{
$conf['database']['campos_sql'][6]['nombre_db'] = 'Cantidad';
$conf['database']['campos_sql'][6]['nombre_mostrar'] = 'Cantidad';
$conf['database']['campos_sql'][6]['longitud_max'] = '3';
$conf['database']['campos_sql'][6]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][6]['nombre_db'];
$conf['database']['campos_sql'][6]['longitud_intext'] = '3';
$conf['database']['campos_sql'][6]['foreign'] = FALSE;
$conf['database']['campos_sql'][6]['ancho'] ='3';
$conf['database']['campos_sql'][6]['rango_valores'] = FALSE;
}

$conf['database']['campos_sql'][3]['nombre_db'] = 'Proveedor';
$conf['database']['campos_sql'][3]['nombre_mostrar'] = 'Proveedor';
$conf['database']['campos_sql'][3]['longitud_max'] = '50';
$conf['database']['campos_sql'][3]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][3]['nombre_db'];
$conf['database']['campos_sql'][3]['longitud_intext'] = '15';
$conf['database']['campos_sql'][3]['foreign'] = FALSE;
$conf['database']['campos_sql'][3]['ancho'] ='14';
$conf['database']['campos_sql'][3]['rango_valores'] = FALSE;

$conf['database']['campos_sql'][4]['nombre_db'] = 'Contacto';
$conf['database']['campos_sql'][4]['nombre_mostrar'] = 'Contacto';
$conf['database']['campos_sql'][4]['longitud_max'] = '50';
$conf['database']['campos_sql'][4]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][4]['nombre_db'];
$conf['database']['campos_sql'][4]['longitud_intext'] = '11';
$conf['database']['campos_sql'][4]['ancho'] ='10';
$conf['database']['campos_sql'][4]['foreign'] = FALSE;
$conf['database']['campos_sql'][4]['rango_valores'] = FALSE;

$conf['database']['campos_sql'][5]['nombre_db'] = 'Telefono';
$conf['database']['campos_sql'][5]['nombre_mostrar'] = 'Teléfono';
$conf['database']['campos_sql'][5]['longitud_max'] = '21';
$conf['database']['campos_sql'][5]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][5]['nombre_db'];
$conf['database']['campos_sql'][5]['longitud_intext'] = '10';
$conf['database']['campos_sql'][5]['ancho'] ='10';
$conf['database']['campos_sql'][5]['foreign'] = FALSE;
$conf['database']['campos_sql'][5]['rango_valores'] = FALSE;

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
/*if( (isset($_SESSION['id_centro_comp'])) && ($_SESSION['id_centro_comp']!="todos"))
{
	$query_name="Select Nombre from centro where Id_Centro=" .$_SESSION['id_centro_comp'];
	$resul_name=mysql_query($query_name,$db_link2);
	$total_name=mysql_num_rows($resul_name);
	for($y=0;$y<$total_name;$y++)
	{
		$resultado_name=mysql_fetch_array($resul_name);
		$nombre_centro=$resultado_name['Nombre'];
	}*/
$conf['texto']['titulo_tabla'] = 'Listado de Componentes';
/*}
else
{
	$conf['texto']['titulo_tabla'] = 'Listado de Componentes   -   Centro: Todos';
}*/
$conf['texto']['boton_limpiar'] = 'Limpiar<br>Campos';
$conf['texto']['boton_seleccionar'] = 'Selec.<br>Todo';
$conf['texto']['boton_nuevo'] = 'Nuevo';
$conf['texto']['confirmar_eliminar_seleccionado'] = '¿Seguro que desea eliminar los registros seleccionados?';
$conf['texto']['numero_pagina'] = 'Página';
$conf['texto']['palabra_clave'] = 'Clave';

// Configuración del menú contextual
if(isset($_GET['Id_Centro']))
	$_SESSION['centro']='todos';
if($_SESSION['centro']=='todos')
{
	$conf['menu_contextual'][0]['texto'] = 'Modificar';	
	$conf['menu_contextual'][0]['accion'] = 'modify';
	$conf['menu_contextual'][1]['texto'] = 'Eliminar';
	$conf['menu_contextual'][1]['accion'] = 'remove';
	$conf['menu_contextual'][2]['texto'] = 'Subcomponentes';
	$conf['menu_contextual'][2]['link'] = 'listado_subcomponentes.php';
}
else
{
	$conf['menu_contextual'][0]['texto'] = 'Modificar';
	$conf['menu_contextual'][0]['accion'] = 'modify';
	$conf['menu_contextual'][1]['texto'] = 'Eliminar';
	$conf['menu_contextual'][1]['accion'] = 'remove';
	$conf['menu_contextual'][2]['texto'] = 'Subcomponentes';
	$conf['menu_contextual'][2]['link'] = 'listado_subcomponentes.php';
	//$conf['menu_contextual'][1]['texto'] = 'Subcomponentes';
	//$conf['menu_contextual'][1]['link'] = 'listado_subcomponentes.php';
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

$conf['database']['consulta_sql'] = 'SELECT Id_Componente,';

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
?>
<form name="<?PHP echo $conf['general']['nombre_formulario']; ?>" action="?pag=listado_componentes.php">
	<input type='hidden' name='eliminar' value=''>
	<input type='hidden' name='eliminar_componente' value=''>
	<input type='hidden' name='eliminar_varios' value=''>
<?php

if ($_GET['Id_Centro'] != "") {
?>
	<input type="hidden" name="centro" value="<? echo $_GET['Id_Centro']; ?>">	
<?
}

if( (isset($_SESSION['id_centro_comp'])) && ($_SESSION['id_centro_comp'])!="todos")
{
	$centro=$_SESSION['id_centro_comp'];
	$query_previa='Select Id_Componente from centro_comp where Id_Centro = ' .$centro;
	$resul_previa=mysql_query($query_previa,$db_link2);
	$total_comp=mysql_num_rows($resul_previa);
	$conf['database']['consulta_sql'] .= " AND Id_Componente IN(Select Id_Componente from Centro_Comp where Id_Centro = " .$centro .")";
	$signal=0;
	//echo $query_previa ."---" .$centro ."---";
	
}
$j = COUNT($conf['database']['campos_sql']);
for ($i = 0; $i < $j; $i++) {
	if ($_GET[$conf['database']['campos_sql'][$i]['nombre_intext']] != '') {
		$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%"';
	}
}

$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' ORDER BY '.$conf['resultado_sql']['orden_campo'].' '.$conf['resultado_sql']['orden_tipo'];

if(isset($_GET['Id_Centro']))
	$_SESSION['centro']=$_GET['Id_Centro'];
if(isset($_GET['Id_Centro2']))
	$_SESSION['centro']=$_GET['Id_Centro2'];
if($_SESSION['centro'] != 'todos' )
{
	$conf['database']['consulta_sql'] = "SELECT * FROM (SELECT Id_Componente, Nombre,Marca,Modelo,Proveedor,Contacto,Telefono,CONCAT(Id_Componente) 
	AS Clave FROM componente WHERE 1";
	$j = COUNT($conf['database']['campos_sql']);
	for ($i = 0; $i < $j; $i++) 
	{
		if ($_GET[$conf['database']['campos_sql'][$i]['nombre_intext']] != '') 
		{
			if($i<6)
			{
				$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%"';
			}
		}
	}
	

	if( (isset($_GET['intext_Cantidad'])) && ($_GET['intext_Cantidad'] != '') )
	{
		$conf['database']['consulta_sql'] .=" AND Id_Componente IN (SELECT Id_Componente FROM centro_comp WHERE Id_Centro LIKE '" .$centro ."') ) AS consulta INNER JOIN centro_comp ON consulta.Id_Componente LIKE centro_comp.Id_Componente WHERE Id_Centro LIKE '" .$centro ."' AND Cantidad LIKE '" .$_GET['intext_Cantidad']  ."' ORDER BY " .$conf['resultado_sql']['orden_campo'] ." " .$conf['resultado_sql']['orden_tipo'];	
	}
	else
	{
		$conf['database']['consulta_sql'] .=" AND Id_Componente IN (SELECT Id_Componente FROM centro_comp WHERE Id_Centro LIKE '" .$centro ."') ) AS consulta INNER JOIN centro_comp ON consulta.Id_Componente LIKE centro_comp.Id_Componente WHERE Id_Centro LIKE '" .$centro ."' ORDER BY " .$conf['resultado_sql']['orden_campo'] ." " .$conf['resultado_sql']['orden_tipo'];	
	}
}

$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].';';

//echo "<BR><BR><BR><bR>" .$conf['database']['consulta_sql'] ."--" .$_SESSION['id_centro_comp'] ."////";

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
	$sql_ini = "INSERT INTO ".$conf['database']['nombre_tabla']."(";
	$tope = COUNT($conf['database']['campos_sql']);
	for ($i = 0; $i < $tope; $i++) {
		$sql_ini = $sql_ini.$conf['database']['campos_sql'][$i]['nombre_db'].',';
	}
	$sql_ini = SUBSTR($sql_ini,0,-1).') VALUES (';
	for ($i = 0; $i < $tope; $i++) {
		if ($_GET['n'.$conf['database']['campos_sql'][$i]['nombre_intext']] == '') {
			$sql_ini = $sql_ini.'NULL,';
		}
		else {
			$sql_ini = $sql_ini.'"'.$_GET['n'.$conf['database']['campos_sql'][$i]['nombre_intext']].'",';
		}
	}	
	$sql_ini = SUBSTR($sql_ini,0,-1).');';
	
	/* Connecting, selecting database */
	$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
	/* Performing SQL query */
	$_SESSION['nombre_nuevo']=$_GET['nintext_Nombre'];
	$_SESSION['marca_nuevo']=$_GET['nintext_Marca'];
	$_SESSION['modelo_nuevo']=$_GET['nintext_Modelo'];
	$_SESSION['proveedor_nuevo']=$_GET['nintext_Proveedor'];
	$_SESSION['contacto_nuevo']=$_GET['nintext_Contacto'];
	$_SESSION['telefono_nuevo']=$_GET['nintext_Telefono'];

	$_SESSION['cargar_valores']=1;
	if($_GET['nintext_Nombre']=="")
	{
		?>
		<script language='javascript'>
			alert("Debe rellenar el campo Nombre del Componente");
		</script>
		<?php
	}
	else if($_GET['nintext_Marca']=="")
	{
		?>
		<script language='javascript'>
			alert("Debe rellenar el campo Marca del Componente");
		</script>
		<?php
	}
	else if($_GET['nintext_Modelo']=="")
	{
		?>
		<script language='javascript'>
			alert("Debe rellenar el campo Modelo del Componente");
		</script>
		<?php
	}
	else if(intval($_GET['nintext_Telefono'])==0)
	{
		?>
		<script language='javascript'>
			alert("El número de teléfono es erróneo.");
		</script>
		<?php
	}
	else
	{
		$_SESSION['cargar_valores']=0;
		if($_GET['nintext_Proveedor']!='')
		{
			$query_select="Select count(*) as num from componente where Nombre ='" .$_GET['nintext_Nombre'] ."' and Marca ='" .$_GET['nintext_Marca'] ."' and Modelo ='" .$_GET['nintext_Modelo'] ."' and Proveedor = '" .$_GET['nintext_Proveedor'] ."'";
		}
		else
		{
			$query_select="Select count(*) as num from componente where Nombre ='" .$_GET['nintext_Nombre'] ."' and Marca ='" .$_GET['nintext_Marca'] ."' and Modelo ='" .$_GET['nintext_Modelo'] ."' and Proveedor IS NULL";
		}
		//echo $query_select;
		$resul_select=mysql_query($query_select);
		for($h=0;$h<mysql_num_rows($resul_select);$h++)
		{
			$fila_select=mysql_fetch_array($resul_select);
		}
		if($fila_select['num'] >= 1)
		{
			?>
			<script language='javascript'>
				alert("No se ha dado de alta porque ya existe un componente con el mismo nombre, marca, modelo y proveedor");
			</script>
			<?php
		}
		else
		{
			$result = MYSQL_QUERY($sql_ini,$db_link);
			//echo $result;
			?>
			<script language='javascript'>
				location.href="principal.php?pag=listado_componentes.php&Id_Centro=todos";
			</script>
			<?php
			/* Closing connection */
				MYSQL_CLOSE($db_link);
		}
	}
	?>
	
	<?php
}

// Número de filas a mostrar en pantalla

if ((isset($_GET['numero_filas']))&&(floor($_GET['numero_filas']) > 0)) {
	$conf['resultado_sql']['numero_filas'] = $_GET['numero_filas'];
}

/* SI HEMOS ESCOGIDO UNA OPCIÓN EN EL MENÚ CONTEXTUAL */

if ($_GET['menu_accion'] != '') 
{
	$conf['menu_accion'] = $_GET['menu_accion'];
	$conf['menu_valor'] = $_GET['menu_valor'];
	if ($conf['menu_accion'] == 'rs') 
	{ // Remove selected - Eliminar campos selecionados
		if (count($_GET['checks']) > 0) 
		{
			$conf['remove_selected'] = TRUE;
		}
	}
	else 
	{
		$conf['remove_selected'] = FALSE;
	}
}
else 
{
	$conf['remove_selected'] = FALSE;
	$conf['menu_accion'] = NULL;
	$conf['menu_valor'] = NULL;
}

if ($_GET['confirmar_modificar'] != '') 
{
	if( (isset($_GET['mintext_Cantidad'])) && ($_GET['mintext_Cantidad']!="") )
	{
		if( ($_GET['mintext_Cantidad']=='') || ($_GET['mintext_Cantidad']<1) )
		{
			$_GET['mintext_Cantidad']=1;
		}
		$sql="UPDATE centro_comp SET cantidad = " .$_GET['mintext_Cantidad'] ." where Id_Centro = " .$_GET['id_centro2'] ." and Id_Componente = " .$_GET['confirmar_modificar'];
		$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
		if (!$db_link) {
		die("Could not connect: ".MYSQL_ERROR());
		}
		MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
		/* Performing SQL query */
		$result = MYSQL_QUERY($sql,$db_link);
		if($result < 1)
		{
			$sql="update centro_comp set cantidad = 1 where Id_Centro = " .$_GET['id_centro2'] ." and Id_Componente =" .$_GET['confirmar_modificar'];
			//echo "uaa" .$sql;
			$result = MYSQL_QUERY($sql,$db_link);
		}
		//echo $sql;
		//echo $result;
		/* Closing connection */
		MYSQL_CLOSE($db_link);
	}
	else
	{
		if (($_GET['m'.$conf['database']['campos_sql'][0]['nombre_intext']] == "") || ($_GET['m'.$conf['database']['campos_sql'][1]['nombre_intext']] == "") || ($_GET['m'.$conf['database']['campos_sql'][2]['nombre_intext']] == "")) {
			echo "<script language='Javascript'> alert('Los campos Nombre, Marca y Modelo no pueden ser vacíos.');</script>";
		}
		else if (INTVAL($_GET['m'.$conf['database']['campos_sql'][5]['nombre_intext']]) == 0) {
			echo "<script language='Javascript'> alert('El número de teléfono es erróneo.');</script>";
		}
		else {
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
			//echo $sql;
			//echo $result;
			/* Closing connection */
			MYSQL_CLOSE($db_link);
		}
	}
}

if ($_GET['confirmar_eliminar'] != '') 
{
	if ($_GET['confirmar_eliminar'] == 'rs') 
	{
		
		if($_SESSION['centro']=='todos')
		{
			
			//se crea la consulta y posteriormente se evalua si ejecutarla o no
			for ($i = 0; $i < $j; $i++) 
			{
				$sql = 'DELETE FROM '.$conf['database']['nombre_tabla'].' WHERE ';
				$tope = count($conf['database']['clave_tabla']);
				// $conf['database']['clave_tabla'][0] = 'Id_Pais';
				$aux = split('\\'.$conf['database']['separador_clave'],$_GET['checks'][$i]);
				for ($k = 0; $k < $tope; $k++) {
					$sql = $sql.$conf['database']['clave_tabla'][$k].' LIKE "'.$aux[$k].'" AND ';		
				}
				$sql = SUBSTR($sql,0,-5).';';
				$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
				if (!$db_link) {
				die("Could not connect: ".MYSQL_ERROR());
				}
				MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
				/* Performing SQL query */
				//$result = MYSQL_QUERY($sql,$db_link);
				//echo $sql;
				//echo $result;
				/* Closing connection */
				MYSQL_CLOSE($db_link);
			}
			
			$j = COUNT($_GET['checks']);
			$sql_select='Select count(*) as num from centro_comp where Id_Componente = ';
			$sql_nombres='Select Id_Componente from centro_comp where Id_Componente = ';
			for ($i = 0; $i < $j; $i++) 
			{
				if($i==0)
				{
					$sql_select .= $_GET['checks'][$i] ." ";
					$sql_nombres .= $_GET['checks'][$i] ." ";
				}
				else
				{
					$sql_select .= " OR Id_Componente = " .$_GET['checks'][$i];
					$sql_nombres .= " OR Id_Componente = " .$_GET['checks'][$i];
				}
				$_SESSION['varios_comp'][$i]=$_GET['checks'][$i];
			}
			
			//echo $sql_select;
			//echo "<br>" .$sql_nombres;
			$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
				if (!$db_link) {
				die("Could not connect: ".MYSQL_ERROR());
				}
				MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");	
			$resul_select=mysql_query($sql_select, $db_link);
			for($f=0;$f<mysql_num_rows($resul_select);$f++)
			{
				$fila=mysql_fetch_array($resul_select);
			}
			//echo "II" .$fila['num'];
			if($fila['num'] >= 1)
			{
				$sql_obtain="Select Nombre from componente where Id_Componente IN(" .$sql_nombres .")";
				//echo $sql_obtain;
				?>
					<script language='javascript'>
						var respuesta=confirm("Alguno de los componentes que quiere eliminar están asignados a una o varias instalaciones. ¿Desea eliminarlos?");
						if(respuesta)
						{
							location.href='?pag=listado_componentes.php&eliminar_varios=1';
						}
					</script>
				<?php
			}	
			else
			{
				$result = MYSQL_QUERY($sql,$db_link);
				//echo $sql;
				//echo $result;
				/* Closing connection */
				MYSQL_CLOSE($db_link);
			}
			
			
		}
		else
		{
			$sql='delete from centro_comp where Id_Centro = ' .$_SESSION['centro'] ." AND Id_Componente =";
			$j = COUNT($_GET['checks']);
			for ($i = 0; $i < $j; $i++) 
			{
				if($i==0)
				{
					$sql .= $_GET['checks'][$i];
				}
				else
				{
					$sql .= " OR Id_Componente= " .$_GET['checks'][$i];
				}
			}
			$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
				if (!$db_link) {
				die("Could not connect: ".MYSQL_ERROR());
				}
				MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
				/* Performing SQL query */
				$result = MYSQL_QUERY($sql,$db_link);
				//echo $sql;
				//echo $result;
				/* Closing connection */
				MYSQL_CLOSE($db_link);
		}
	}
	else 
	{
		if( (isset($_GET['id_centro2'])) && ($_GET['id_centro2']!='') )
		{
			$sql="DELETE FROM centro_comp where Id_Centro = " .$_GET['id_centro2'] ." and Id_Componente = " .$_GET['confirmar_eliminar'];
			$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
			if (!$db_link) {
			die("Could not connect: ".MYSQL_ERROR());
			}
			MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
			/* Performing SQL query */
			$result = MYSQL_QUERY($sql,$db_link);
			//echo $sql;
			//echo $result;
			/* Closing connection */
			MYSQL_CLOSE($db_link);
		}
		else
		{
			$sql = 'DELETE FROM '.$conf['database']['nombre_tabla'].' WHERE ';
			$tope = count($conf['database']['clave_tabla']);
			// $conf['database']['clave_tabla'][0] = 'Id_Pais';
			$aux = split('\\'.$conf['database']['separador_clave'],$_GET['confirmar_eliminar']);
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
			
			
			$query_check="Select count(*) as num from centro_comp where Id_Componente = " .$_GET['confirmar_eliminar'];
			//echo $query_check ."<BR>";
			$resul_check=mysql_query($query_check);
			for($r=0;$r<mysql_num_rows($resul_check);$r++)
			{
				$fila_check=mysql_fetch_array($resul_check);
			}
			//echo $fila_check['num'];
			
			if($fila_check['num'] >= 1)
			{
				?>
					<script language='javascript'>
						var respuesta=confirm("Este componente está asignado a una o varias instalaciones. ¿Desea eliminarlo?");
						if(respuesta)
						{
							location.href='?pag=listado_componentes.php&eliminar=1&eliminar_componente=<?php echo $_GET['confirmar_eliminar']; ?>';
						}
					</script>
				<?php
			}
			else
			{
				$result = MYSQL_QUERY($sql,$db_link);
				MYSQL_CLOSE($db_link);
			}
		}
	}
}

/* FIN MENÚ CONTEXTUAL */

/////////////////////////////////////////////////////////
/* FIN BLOQUE DE CONFIGURACION DE VARIABLES DE ENTRADA */
/////////////////////////////////////////////////////////

?>
<script language="JavaScript">

	function cambiar_pagina(valor) {		
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_pagina.value = valor;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}

	function cambiar_ventana_paginas(valor) {
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_ventana.value = valor;
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
	}
	
	function nuevo_centro_comp() {		
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

	
	function nuevo() {		
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_accion.value = 'new';
		//document.write("<br>" +formulario_listado.intext_Nombre.value);
		<?PHP echo $conf['general']['nombre_formulario']; ?>.submit();
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
		else
		{
			<?php
				if($_SESSION['todos']==0)
				{
					?>
					document.formulario_listado.intext_nombre.focus();
					<?php
				}
			?>
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
	//si existe es que se ha hecho click en el botón de añadir nuevo componente a un centro
	
	
	
	/*if(isset($_REQUEST['oculto_nuevo_comp']))
	{*/
		?>
			<!--<script language='javascript'>
				alert(" <?php echo $_REQUEST['intext_Nombre']; ?> ");
				alert(" <?php echo $_REQUEST['intext_Marca']; ?> ");
				alert(" <?php echo $_REQUEST['intext_Modelo']; ?> ");
			</script>-->
		<?php
	//}
	
	
	
	if(isset($_REQUEST['oculto_insertar']))
	{
		//echo "<bR><BR><br><br><br>:" .$_REQUEST['nuevo_comp_centro'];
		//echo "<bR><BR><br><br><br>:" .$_REQUEST['marca_comp'];
		//echo "<bR><BR><br><br><br>:" .$_REQUEST['modelo_comp'];
		//echo "<bR><BR><br><br><br>:" .$_REQUEST['id_centro2'];
		$query_insertar="select Id_Componente from componente where Nombre='" .$_REQUEST['nuevo_comp_centro'] ."' AND Marca = '" .$_REQUEST['marca_comp'] ."' AND Modelo ='" .$_REQUEST['modelo_comp'] ."'";
		//echo "<BR><BR><br><br>" .$query_insertar;
		$resul_insertar=mysql_query($query_insertar);
		for($k=0;$k<mysql_num_rows($resul_insertar);$k++)
		{
			$fila_insertar=mysql_fetch_array($resul_insertar);
			$id_insertar=$fila_insertar['Id_Componente'];
		}
		$query_insertar2="Insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(" .$_REQUEST['id_centro2'] .",$id_insertar,null," .$_REQUEST['alta_cantidad'] .")";
		//echo "<BR><br><br><br>" .$query_insertar2;
		$result_insertar=mysql_query($query_insertar2);
		if($result_insertar != 1)
		{
			$query_insertar2="Insert into centro_comp(Id_Centro,Id_Componente,Fecha_Instalacion,Cantidad) values(" .$_REQUEST['id_centro2'] .",$id_insertar,null,1)";
			mysql_query($query_insertar2);
		}
		?>
			<script language='javascript'>
				location.href='principal.php?pag=listado_componentes.php&Id_Centro=<?php echo $_REQUEST['id_centro2']; ?>';
			</script>
		<?php
	}
	//echo $conf['database']['consulta_sql'];
  	$result = MYSQL_QUERY($conf['database']['consulta_sql'],$db_link);
  	
  	if (MYSQL_NUM_ROWS($result) >= 0) {
?>


<input type="hidden" name="listado_accion" value="">
<input type="hidden" name="pag" value="listado_componentes.php">
<input type="hidden" name="listado_pagina" value="<?PHP echo $conf['resultado_sql']['mostrar_pagina']; ?>">
<input type="hidden" name="listado_ventana" value="<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas']; ?>">

<input type="hidden" name="listado_orden_campo" value="<?PHP echo $conf['resultado_sql']['orden_campo']; ?>">
<input type="hidden" name="listado_orden_tipo" value="<?PHP echo $conf['resultado_sql']['orden_tipo']; ?>">

<input type="hidden" name="listado_foco" value="">

<input type="hidden" name="menu_valor" value="">
<input type="hidden" name="menu_accion" value="">

<input type="hidden" name="confirmar_modificar" value="">
<input type="hidden" name="confirmar_eliminar" value="">
	<table class="listados">
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

				<th class="boton" id="borde_idb">
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
?>
				<th class="titulo" id="borde_db">
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
							<input type="text" name="<?PHP echo $conf['database']['campos_sql'][$i]['nombre_intext']; ?>" value="<?PHP echo $_GET[$conf['database']['campos_sql'][$i]['nombre_intext']]; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['longitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$i]['longitud_max']; ?>" onkeyup="filtrar(<?PHP echo $i; ?>);">
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
				<th colspan="2" rowspan="2" class="boton" id="borde_idb" style="width:68px;">
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
<?PHP	
		if ($conf['remove_selected'] == TRUE) {
?>
			<tr style="height:34px;">
				<th colspan="<? echo COUNT($conf['database']['campos_sql']) + 1; ?>" id="borde_i">
					<div class="intexto">
<?PHP
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
<?PHP	
		}
		else {						
?>
			<tr>
				<th id="borde_i" align=center>
					<a href="#" align=center class="papelera">
						<div style="float: left; width:4px;">
						</div>
						<div class="papelera" title="Borrar entradas seleccionadas" onclick="enviarAccion('rs','sel');">
						</div>
					</a>
				</th><?PHP	
			$tope = COUNT($conf['database']['campos_sql']);
			//-----------------------------------------------------------//
			
			
			for ($i = 0; $i < $tope; $i++) 
			{
				if ($conf['database']['campos_sql'][$i]['rango_valores'] == TRUE) 
				{
					if ($conf['database']['campos_sql'][$i]['foreign'] == TRUE) 
					{
						?>
						<?PHP
					}
					else 
					{		
						?>
						<th>
							<div class="intexto">
								<?php
								if( (($_GET['Id_Centro']=='') && ($_GET['id_centro2']=='')) || ($_GET['Id_Centro']=='todos') )
								{
								?>
									<select name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['longitud_intext']; ?>">
								<?PHP
								}
								for ($m = 0; $m < COUNT($conf['database']['campos_sql'][$i]['valores_id']); $m++) 
								{
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
				else 
				{			
					if($_SESSION['todos']==1)
					{
						if(( ($_GET['Id_Centro']=='') && ($_GET['id_centro2']=='') ) || ($_GET['Id_Centro']=='todos') )
						{
							if($_SESSION['cargar_valores']==0)
							{
								?>
								<th>
									<div class="intexto">
										<input type="text" name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['longitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$i]['longitud_max']; ?>">
									</div>
								</th>
								<?php
							}
						}
					}
				}
			}

			if( ($_SESSION['cargar_valores']==1) && ($_SESSION['todos']==1) )
							{
								?>
								<th>
									<div class="intexto">
									<input type="text" name="nintext_Nombre" size="32" maxLength="50" value="<?php echo $_SESSION['nombre_nuevo']; 	?>">
									</div>
								</th>
								<th>
									<div class="intexto">
									<input type="text" name="nintext_Marca" size="25" maxLength="50" value="<?php echo $_SESSION['marca_nuevo']; ?>">	</div>
								</th>
								<th>
									<div class="intexto">
									<input type="text" name="nintext_Modelo" size="25" maxLength="50" value="<?php echo $_SESSION['modelo_nuevo']; ?>">
									</div>
								</th>
								<th>
									<div class="intexto">
									<input type="text" name="nintext_Proveedor" size="25" maxLength="50" value="<?php echo $_SESSION['proveedor_nuevo']; ?>">
									</div>
								</th>
								<th>
									<div class="intexto">
									<input type="text" name="nintext_Contacto" size="18" maxLength="50" value="<?php echo $_SESSION['contacto_nuevo'] ?>">
									</div>
								</th>
								<th>
									<div class="intexto">
									<input type="text" name="nintext_Telefono" size="9" maxLength="21" value="<?php echo $_SESSION['telefono_nuevo']; ?>">
									</div>
								</th>
								<?php
							}
			
	
			if( (isset($_GET['Id_Centro'])) && ($_GET['Id_Centro'] != "todos") )
			{
				?>
				<input type='hidden' name='id_centro2' value='<?php echo $_GET['Id_Centro']; ?>'>
				<th>
					<div class="intexto">
					<select name="nuevo_comp_centro" onchange='formulario_listado.submit()' style='z-index:0;'>
						<?PHP
						$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
						if (!$db_link) 
						{
							die("Could not connect: ".MYSQL_ERROR());
						}
						MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
						/* Performing SQL query */
						//tipo de sentencia, para mostrar todos los componentes dados de alta pero no asinados aún a este centro
						//SELECT * FROM `componente` WHERE Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro !=1)
						$query="SELECT Id_Componente,Nombre from componente WHERE Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro =" .$_GET['Id_Centro'] .")";
						$result_select = MYSQL_QUERY($query);
									
						/* Closing connection */
						MYSQL_CLOSE($db_link);
						echo
						"<option value=''>";
						for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) 
						{
							$option = MYSQL_FETCH_ARRAY($result_select);
							echo "
							<OPTION value='" .$option['Nombre'] ."' title='" .$option['Nombre'] ."'>" .SUBSTR($option['Nombre'],0,30);
						}
				?>
					</select>
					</div>
				</th>
				<th>
					<select disabled>
						<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</select>
				</th>
				<th>
					<select disabled>
						<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</select>
				</th>
				<th id="borde_d" colspan='6'>
					&nbsp;
				</th>
				
				<?php
			}
			//--------_________---------_____--------- CASO 2 -----------__________--------___________---------//
			else if( (isset($_REQUEST['id_centro2'])) && (!isset($_REQUEST['oculto_seleccion_marca'])) )
			{
				//echo $_REQUEST['nuevo_comp_centro'];
				//echo $_REQUEST['Id_Centro'];
				?>
				<input type='hidden' name='id_centro2' value='<?php echo $_REQUEST['id_centro2']; ?>'>
				<input type='hidden' name='oculto_seleccion_marca' value=''>
				<th>
					<div class="intexto">
					<select name="nuevo_comp_centro" onchange='formulario_listado.submit()' style='z-index:0;'>
						<?PHP
						$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
						if (!$db_link) 
						{
							die("Could not connect: ".MYSQL_ERROR());
						}
						MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
						/* Performing SQL query */
						//tipo de sentencia, para mostrar todos los componentes dados de alta pero no asinados aún a este centro
						//SELECT * FROM `componente` WHERE Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro !=1)
						$query="SELECT DISTINCT Nombre from componente WHERE Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro =" .$_REQUEST['id_centro2'] .")";
						$result_select = MYSQL_QUERY($query);
								
						for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) 
						{
							$option = MYSQL_FETCH_ARRAY($result_select);
							if($_REQUEST['nuevo_comp_centro']==$option['Nombre'])
							{
								echo "
								<OPTION value='" .$option['Nombre'] ."' selected>" .SUBSTR($option['Nombre'],0,30);
							}
							else
							{
								echo "
								<OPTION value='" .$option['Nombre'] ."'>" .SUBSTR($option['Nombre'],0,30);
							}
						}
				?>
					</select>
					</div>
				</th>
				<th>
					<?php
						$query2="SELECT Distinct Marca from componente WHERE Nombre = '" .$_REQUEST['nuevo_comp_centro'] ."' and Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro =" .$_REQUEST['id_centro2'] .")";
						$result_select = MYSQL_QUERY($query2);
					?>					
						<select name='marca_comp' onChange='formulario_listado.submit()' style='z-index:0;'>
						<option value='' selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php
						for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) 
						{
							$option = MYSQL_FETCH_ARRAY($result_select);
							echo "
							<OPTION value='" .$option['Marca'] ."'>" .SUBSTR($option['Marca'],0,16);
						}
						?>
						</select>
				</th>
				<th>
					<select disabled style='z-index:0;'>
						<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</select>
				</th>
				<th id="borde_d" colspan='6'>
					&nbsp;
				</th>
				<?php
				/* Closing connection */
				MYSQL_CLOSE($db_link);
			}
			//------___________-----------_______ CASO 3 _________---------_________-----------________//
			else if (isset($_REQUEST['oculto_seleccion_marca']))
			{
				//echo "<br>" .$_REQUEST['marca_comp'];
				//echo "<br>" .$_REQUEST['id_centro2'];
				//echo "<br>" .$_REQUEST['nuevo_comp_centro'];
				?>
				<input type='hidden' name='id_centro2' value='<?php echo $_REQUEST['id_centro2']; ?>'>
				<input type='hidden' name='oculto_seleccion_marca' value=''>
				<th>
					<div class="intexto">
					<select name="nuevo_comp_centro" onchange='formulario_listado.submit()' style='z-index:0;'>
						<?PHP
						$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
						if (!$db_link) 
						{
							die("Could not connect: ".MYSQL_ERROR());
						}
						MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
						/* Performing SQL query */
						//tipo de sentencia, para mostrar todos los componentes dados de alta pero no asinados aún a este centro
						//SELECT * FROM `componente` WHERE Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro !=1)
						$query="SELECT DISTINCT Nombre from componente WHERE Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro =" .$_REQUEST['id_centro2'] .")";
						$result_select = MYSQL_QUERY($query);
								
						for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) 
						{
							$option = MYSQL_FETCH_ARRAY($result_select);
							if($_REQUEST['nuevo_comp_centro']==$option['Nombre'])
							{
								echo "
								<OPTION value='" .$option['Nombre'] ."' selected>" .SUBSTR($option['Nombre'],0,30);
							}
							else
							{
								echo "
								<OPTION value='" .$option['Nombre'] ."'>" .SUBSTR($option['Nombre'],0,30);
							}
						}
				?>
					</select>
					</div>
				</th>
				<th>
					<?php
						$query2="SELECT Distinct Marca from componente WHERE Nombre = '" .$_REQUEST['nuevo_comp_centro'] ."' and Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro =" .$_REQUEST['id_centro2'] .")";
						$result_select = MYSQL_QUERY($query2);
					?>
					<select name='marca_comp' onchange='formulario_listado.submit()' style='z-index:0;'>
						<option value='' selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php
						for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) 
						{
							$option = MYSQL_FETCH_ARRAY($result_select);
							if($_REQUEST['marca_comp'] == $option['Marca'])
							{
								echo "
								<OPTION value='" .$option['Marca'] ."' selected>" .SUBSTR($option['Marca'],0,16);
							}
							else
							{
								echo "
								<OPTION value='" .$option['Marca'] ."'>" .SUBSTR($option['Marca'],0,16);
							}
						}
						?>
					</select>
				</th>
				<th>
					<?php
						$query3="SELECT Distinct Modelo from componente WHERE Nombre = '" .$_REQUEST['nuevo_comp_centro'] ."' and Marca = '" .$_REQUEST['marca_comp'] ."' and Id_Componente NOT IN (SELECT Id_Componente from centro_comp where Id_Centro =" .$_REQUEST['id_centro2'] .")";
						$result_select = MYSQL_QUERY($query3);
						//echo "<br>query3:" .$query3;
					?>
					<select name='modelo_comp' onchange='formulario_listado.submit()' style='z-index:0;'>
						<option value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php
						for ($m = 0; $m < @ MYSQL_NUM_ROWS($result_select); $m++) 
						{
							$option = MYSQL_FETCH_ARRAY($result_select);
							if($option['Modelo'] == $_REQUEST['modelo_comp'] )
							{
								echo "<OPTION value='" .$option['Modelo'] ."' selected>" .SUBSTR($option['Modelo'],0,16);
							}
							else
							{
								echo "<OPTION value='" .$option['Modelo'] ."'>" .SUBSTR($option['Modelo'],0,16);
							}
						}
						?>
					</select>
				</th>
				<?php
					if( ($_REQUEST['marca_comp'] != "") && ($_REQUEST['modelo_comp']!="") )
					{
						?>
						
						<input type='hidden' name='oculto_insertar' value=''>
						<th colspan='3'>
							&nbsp;
						</th>
						<th colspan='' align='right'>
							&nbsp;&nbsp;<input type='text' name='alta_cantidad' size='3' maxlength='3' value='1'>
						</th>
						<th align='right' colspan="2" id="borde_d">
							<a class="boton2" href="#">
							<div class="boton" onclick="nuevo_centro_comp();">
							<?PHP
								echo "WE" .$conf['texto']['boton_nuevo'];
							?>
							</div>
							</a>
						</th>
						<?php
					}
					else
					{
						?>
						<th align='right' id="borde_d" colspan='6'>
							&nbsp;
						</th>
						<?php
					}
				/* Closing connection */
				MYSQL_CLOSE($db_link);
			}
			else
			{
				?>
						<th colspan='2' id="borde_d">
							<a class="boton2" href="#">
							<div class="boton" onclick="nuevo();">
							<?PHP
								echo $conf['texto']['boton_nuevo'];
							?>
							</div>
							</a>
						</th>
					</tr>
			<?php
			}
	
					?>
					</tr>
<?PHP
		}
?>
			<tr>
				<th colspan="<?PHP echo COUNT($conf['database']['campos_sql']) + 3; ?>" class="paginas" id="borde_idb">
					<div id="fleft">
<?PHP
				/*** /INICIO AÑADIR VOLVER 2/ ***/
				//if ( ($conf['general']['volver'] == true) && ($_GET['Id_Centro'] != 'todos') ) 
				if($_SESSION['todos']==0)
				{
					?>
					<a href="#" align=center class="volver">
						<div style="float: left; width:4px;">
						</div>
						<div class="volver" title="Página Anterior" onclick="location.href='principal.php?pag=listado_centros.php'">
						</div>
						<div style="float: left; width:20px;">
						</div>
					</a>
					<?PHP
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
					<div class="intexto" id="fright">
						Mostrar
						<input type="text" name="<?PHP echo $conf['resultado_sql']['nombre_intext_numero_filas']; ?>" class="tresultados" size="1" value="<?PHP echo $conf['resultado_sql']['numero_filas']; ?>" onkeyup="filtrar(-1);">
						filas
					</div>
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
//____________________________eiquí escomienza'l telar--------//		
		for ($i = 0; $i < $tope; $i++) 
		{
			if($_SESSION['centro']=='todos')
			{
				$fila = MYSQL_FETCH_ARRAY($result,MYSQL_BOTH);
				if ($conf['menu_valor'] == $fila['Clave']) 
				{
					if ($conf['menu_accion'] == 'modify') 
					{
						/* MODIFICAR REGISTRO */
						?>
						<tr id="<?PHP echo $fila['Clave']; ?>" class="accion">
							<td id="borde_i" align="center" class="accion">						
							</td>
							<?PHP	
							$tope2 = COUNT($conf['database']['campos_sql']);
							for ($j = 0; $j < $tope2; $j++) 
							{				
								?>
								<td class="accion">
									<div class="intexto">
										<input type="text" name="<?PHP echo 'm'.$conf['database']['campos_sql'][$j]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$j]['longitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$j]['longitud_max']; ?>" value="<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
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
					else if ($conf['menu_accion'] == 'remove') 
					{
						/* ELIMINAR REGISTRO */
						?>
						<tr id="<?PHP echo $fila['Clave']; ?>" class="accion">
							<td id="borde_i" align="center" class="accion">
							</td>
						<?PHP	
						$tope2 = COUNT($conf['database']['campos_sql']);
						for ($j = 0; $j < $tope2; $j++) 
						{				
							?>
							<td class="accion">
							<?PHP
								echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
							?>
							</td>
							<?PHP	
						}
						?>					
						<a href="#" class="confirmar_aceptar">
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
				else 
				{
					?>
					<a href="#">
					<tr id="<?PHP echo $fila['Clave']; ?>" oncontextmenu="mostrarMenu('<?PHP echo $fila['Clave']; ?>');">
						<td id="borde_i" align="center" style="padding:0 0 0 0;">
							<div class="checkbox">
							<?PHP
							if ($conf['remove_selected'] == TRUE) 
							{
								if (IN_ARRAY($fila['Clave'],$_GET['checks'])) 
								{
									$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
									?>
									<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>">
										<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
									</div>
									<?PHP
								}
							}
							else 
							{
								$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
								?>							
								<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>" onclick="pulsarCheckbox('checkbox_<?PHP echo	$fila['Clave']; ?>', this.id);">
									<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
								</div>
								<?PHP
							}
							?>
							</div>
						</td>
						<?PHP	
						$tope2 = COUNT($conf['database']['campos_sql']);
						for ($j = 0; $j < $tope2; $j++) 
						{	
							if(($j==6))//ojo, es el campo cantidad y hay que obtenerlo de la tabla centro_comp, no de componente
							{
								?>
								<td title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
									<?PHP
									//echo "E" . SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									//echo $conf['database']['campos_sql'][$j]['ancho'];
									?>
								</td>
								<?PHP	
							}
							else
							{
								?>
								<td title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
									<?PHP
									//echo "E" . SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									//echo $conf['database']['campos_sql'][$j]['ancho'];
									?>
								</td>
								<?PHP
							}
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
				}//FIN DEL IF
			}//fin del if dolar session['centro'] == todos
			//_________________________________________________________________________________________________________//
			else //ahora es un centro en concreto
			{
				$fila = MYSQL_FETCH_ARRAY($result,MYSQL_BOTH);
				if ($conf['menu_valor'] == $fila['Clave']) 
				{
					if ($conf['menu_accion'] == 'modify') 
					{
						/* MODIFICAR REGISTRO */
						?>
						<a href="#">
						<tr id="<?PHP echo $fila['Clave']; ?>" oncontextmenu="mostrarMenu('<?PHP echo $fila['Clave']; ?>');" class="accion">
							<td id="borde_i" align="center" style="padding:0 0 0 0;">
								&nbsp;
							</td>
							<?PHP
							$tope2 = COUNT($conf['database']['campos_sql']);
							for ($j = 0; $j < $tope2; $j++) 
							{	
								if($j<6)
								{
									?>
									<td title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">	
										<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>
									</td>
									<?PHP
								}
								else
								{
									?>
									<td title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>" class="accion">	
										<div class="intexto">
											<input type="text" onclick="this.focus();" onMouseOver="this.focus();" onMouseOut="this.focus();" name="<?PHP echo 'm'.$conf['database']['campos_sql'][$j]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$j]['longitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$j]['longitud_max']; ?>" value="<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
										</div>
									</td>
								<?PHP
								}
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
						</a>
						<?PHP
						/* FIN MODIFICAR REGISTRO */
					}					
					else if ($conf['menu_accion'] == 'remove') 
					{
						/* ELIMINAR REGISTRO */
						?>
						<tr id="<?PHP echo $fila['Clave']; ?>" class="accion">
							<td id="borde_i" align="center" class="accion">
							</td>
						<?PHP	
						$tope2 = COUNT($conf['database']['campos_sql']);
						for ($j = 0; $j < $tope2; $j++) 
						{				
							?>
							<td class="accion">
							<?PHP
								echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
							?>
							</td>
							<?PHP	
						}
						?>					
						<a href="#" class="confirmar_aceptar">
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
				else 
				{
					?>
					<a href="#">
					<tr id="<?PHP echo $fila['Clave']; ?>" oncontextmenu="mostrarMenu('<?PHP echo $fila['Clave']; ?>');">
						<td id="borde_i" align="center" style="padding:0 0 0 0;">
							<div class="checkbox">
							<?PHP
							if ($conf['remove_selected'] == TRUE) 
							{
								if (IN_ARRAY($fila['Clave'],$_GET['checks'])) 
								{
									$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
									?>
									<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>">
										<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
									</div>
									<?PHP
								}
							}
							else 
							{
								$script_seleccionar =  $script_seleccionar.'pulsarCheckbox("checkbox_'.$fila['Clave'].'","fondo_checkbox_'.$fila['Clave'].'");';
								?>							
								<div class="unchecked" id="fondo_checkbox_<?PHP echo $fila['Clave']; ?>" onclick="pulsarCheckbox('checkbox_<?PHP echo	$fila['Clave']; ?>', this.id);">
									<input type="checkbox" name="checks[]" value="<?PHP echo $fila['Clave']; ?>" id="checkbox_<?PHP echo $fila['Clave']; ?>"/>&nbsp;
								</div>
								<?PHP
							}
							?>
							</div>
						</td>
						<?PHP	
						$tope2 = COUNT($conf['database']['campos_sql']);
						for ($j = 0; $j < $tope2; $j++) 
						{	
							if(($j==6))//ojo, es el campo cantidad y hay que obtenerlo de la tabla centro_comp, no de componente
							{
								?>
								<td title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
									<?PHP
									//echo "E" . SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									//echo $conf['database']['campos_sql'][$j]['ancho'];
									?>
								</td>
								<?PHP	
							}
							else
							{
								?>
								<td title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
									<?PHP
									//echo "E" . SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,$conf['database']['campos_sql'][$j]['ancho']);
									//echo $conf['database']['campos_sql'][$j]['ancho'];
									?>
								</td>
								<?PHP
							}
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
				}//FIN DEL IF
			}
		}//FIN DEL FOR


		//________________AKI SE ACABA EL TELAR_________________//
		if ($conf['remove_selected'] == TRUE) {
			$script_seleccionar = $script_seleccionar." </script>";
		}
		else {
			$script_seleccionar = $script_seleccionar."} </script>";
		}
?>
		</tbody>
	</table>


<!--<div id="menu" style="position:absolute;visibility:hidden;padding:0 0 0 0;margin:0px;">
	<div class="item_esquina"></div>
	<table cellpadding=0 cellspacing=0>
	
	
<?PHP
		$tope = count($conf['menu_contextual']);
		for ($i = 0; $i < $tope; $i++) {
?>
	<tr><td style="padding: 0 0 0 0;">
	<div class="item_izq"></div>
	<div class="item" align="center" style="width:140px;" onclick="enviarAccion('<?PHP echo $conf['menu_contextual'][$i]['accion']; ?>');"><?PHP echo $conf['menu_contextual'][$i]['texto']; ?></div>
	<div class="item_der"></div>
	</td></tr>
<?PHP
		}
?>
	</table>
</div>-->

<div id="menu" style="position:absolute;visibility:hidden;padding:0 0 0 0;margin:0px;z-index:1;">
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
			if ($conf['menu_contextual'][$i]['link'] != '') 
			{
				if($_SESSION['centro'] =='todos')
				{
					?>
					<div class="item" align="center" style="width:140px;" onclick="irLink('<?PHP echo $conf['menu_contextual'][$i]['link']; ?>',1);">
					<?php
				}
				else
				{
					?>
					<div class="item" align="center" style="width:140px;" onclick="irLink('<?PHP echo $conf['menu_contextual'][$i]['link']; ?>',0);">
					<?php
				}
			}
			else {
?>
				<div class="item" align="center" style="width:140px;" onclick="enviarAccion('<?PHP echo $conf['menu_contextual'][$i]['accion']; ?>');">
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
	function foco(){
		<?php
			if($_SESSION['todos']==1)
			{
				echo "
				document.formulario_listado.nintext_Nombre.focus();";
			}
			else
			{
				echo "
				document.formulario_listado.intext_Nombre.focus();";
			}
		?>	
		}
		</script>
		
		

<script language='javascript'>
function irLink(pag,todos) {
	if(todos==1)
	{
		window.location.href('<? echo $conf['general']['nombre_principal']; ?>?pag=' + pag + '&id_componente=' + document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_valor.value);
	}
	else
	{
		window.location.href('<? echo $conf['general']['nombre_principal']; ?>?pag=' + pag + '&id_componente=' + document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_valor.value +'&nomodif=1');
	}
	//window.location.href('<? echo $conf['general']['nombre_principal']; ?>?pag=' + pag);
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
	
	/* Closing connection */
	//MYSQL_CLOSE($db_link);

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
if( (isset($_GET['Id_Centro'])) && ($_GET['Id_Centro']=='todos') && (!isset($nombre_vacio)) )
{
?>
<script language='javascript'>
		document.formulario_listado.nintext_Nombre.focus();
</script>
<?php
}
if(isset($nombre_vacio))
{
	if($nombre_vacio=="")
	{
		?>
		<script language='javascript'>
			document.formulario_listado.nintext_Nombre.focus();
		</script>
		<?php
	}
	else if($marca_vacio=="")
	{
		?>
		<script language='javascript'>
			document.formulario_listado.nintext_Marca.focus();
		</script>
		<?php
	}
	else
	{
		?>
		<script language='javascript'>
			document.formulario_listado.nintext_Modelo.focus();
		</script>
		<?php
	}
}
else
{
if($_GET['intext_Nombre']!='')
{
	?>
		<script language='javascript'>
			document.formulario_listado.intext_Nombre.focus();
		</script>
	<?php
}
if($_GET['intext_Marca']!='')
{
	?>
		<script language='javascript'>
			document.formulario_listado.intext_Marca.focus();
		</script>
	<?php
}
if($_GET['intext_Modelo']!='')
{
	?>
		<script language='javascript'>
			document.formulario_listado.intext_Modelo.focus();
		</script>
	<?php
}
if($_GET['intext_Proveedor']!='')
{
	?>
		<script language='javascript'>
			document.formulario_listado.intext_Proveedor.focus();
		</script>
	<?php
}
if($_GET['intext_Contacto']!='')
{
	?>
		<script language='javascript'>
			document.formulario_listado.intext_Contacto.focus();
		</script>
	<?php
}
if($_GET['intext_Telefono']!='')
{
	?>
		<script language='javascript'>
			document.formulario_listado.intext_Telefono.focus();
		</script>
	<?php
}
}
?>