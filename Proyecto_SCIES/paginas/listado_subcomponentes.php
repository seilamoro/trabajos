<?PHP
if(($_GET['no_modificar_camp']=="") && isset($_GET['nomodif']))
{

	$_SESSION['no_modificar']="no";

}
else if($_GET['no_modificar_camp']!="")
{
	$_SESSION['no_modificar']=$_GET['no_modificar_camp'];
	//$_SESSION['no_modificar']='no';
}
else if(($_GET['no_modificar_camp'] == "") && !isset($_GET['nomodif'])){
	$_SESSION['no_modificar']="si";
}

if(($_GET['confirmar_eliminar']!="rs") && ($_GET['confirmar_eliminar']!=""))
{

	$_SESSION['mas_subcomp'][0]=$_GET['confirmar_eliminar'];
}

	if(isset($_GET['id_componente']))
	{
		$_SESSION['Id_comp_subc']=$_GET['id_componente'];
	}
/* BLOQUE DE CONFIGURACIÓN DE LA PÁGINA */

/*RECIBO LA VARIABLE PARA ACEPTAR O RECHAZAR EL CONFIRM*/
if((COUNT($_GET['checks']) == 1) || (($_GET['confirmar_eliminar']!="rs") && ($_GET['confirmar_eliminar']!="")))
{
$conf['texto_confirm']="Este subcomponente esta asignado a uno o varios componentes ¿Desea eliminarlo? ";
}
else
{
	$conf['texto_confirm']="Alguno de los subcomponentes que desea borrar esta asociado a uno o varios componentes ¿Desea eliminarlo?";
}


/*FIN DE LA VARIABLE PARA EL CONFIRM*/

// Datos generales de la página.
$conf['general']['nombre_formulario'] = 'formulario_listado';


// Queremos botón de volver (true o false)

if((isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc']!="todos")){

	$conf['general']['volver'] = true;
}
else{
	$conf['general']['volver'] = false;
}




// Datos referentes a la conexión a mysql.
$conf['database']['host'] = 'localhost';
$conf['database']['user'] = 'root';
$conf['database']['pass'] = '';

// Nombre de la base de datos,nombre de la tabla y array con la clave de la tabla.
$conf['database']['nombre_db'] = 'scies';
$conf['database']['nombre_tabla'] = 'Subcomponente';
$conf['database']['clave_tabla'][0] = 'Id_Subcomponente';

/* estas variables es para poder hacer el borrado de un subcomponente que este añadido a un componente 
sin borrar el subcomponente de la tabla de subcomponenentes*/

$conf['database']['nombre_tabla_borrar'] = 'comp_subcomp';
$conf['database']['clave_tabla_borrar'][0] = 'Id_Componente';
$conf['database']['clave_tabla_borrar'][1] = 'Id_Subcomponente';




$db_link2 = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link2) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");

if(($_GET['borrar_subcomponentes_check']==1))
{

	$s=count($_SESSION['mas_subcomp']);
	
	for($i=0;$i < $s;$i++)
	{
		$sql_confirmada="DELETE FROM comp_subcomp where Id_Subcomponente=".$_SESSION['mas_subcomp'][$i];
		$res_sql_confirmada=MYSQL_QUERY($sql_confirmada);

	}
}


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
// $conf['database']['campos_sql'][0]['logitud_max'] = '2';
// $conf['database']['campos_sql'][0]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][0]['nombre_db'];
// $conf['database']['campos_sql'][0]['logitud_intext'] = '5';
//
// $conf['database']['campos_sql'][1]['nombre_db'] = 'Nombre_Pais';
// $conf['database']['campos_sql'][1]['nombre_mostrar'] = 'Nombre País';
// $conf['database']['campos_sql'][1]['logitud_max'] = '50';
// $conf['database']['campos_sql'][1]['nombre_intext'] = 'intext'.$conf['database']['campos_sql'][1]['nombre_db'];
// $conf['database']['campos_sql'][1]['logitud_intext'] = '50';
//

//CAMPOS PARA HACER EL INSERT EN LA BASE DE DATOS DE UN NUEVO SUBCOMPONENTE ASOCIADO A UN COMPONENTE ELEGIDO

$conf['database']['campos_sql_insert']=array();

$conf['database']['campos_sql_insert'][0] = 'Id_Componente';
$conf['database']['campos_sql_insert'][1] = 'Id_Subcomponente';
$conf['database']['campos_sql_insert'][2] = 'cantidad';

$conf['database']['separador_clave'] = '*';
$conf['database']['campos_sql'] = array();

$conf['database']['campos_sql'][0]['nombre_db'] = 'Id_Subcomponente';
$conf['database']['campos_sql'][0]['nombre_mostrar'] = 'Código';
$conf['database']['campos_sql'][0]['logitud_max'] = '5';
$conf['database']['campos_sql'][0]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][0]['nombre_db'];
$conf['database']['campos_sql'][0]['logitud_intext'] ='5';


$conf['database']['campos_sql'][1]['nombre_db'] = 'Nombre';
$conf['database']['campos_sql'][1]['nombre_mostrar'] = 'Nombre';
$conf['database']['campos_sql'][1]['logitud_max'] = '100';
$conf['database']['campos_sql'][1]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][1]['nombre_db'];
$conf['database']['campos_sql'][1]['logitud_intext'] = '100';


if( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc']!="todos"))
{
	$conf['database']['campos_sql'][2]['nombre_db'] = 'cantidad';
	$conf['database']['campos_sql'][2]['nombre_mostrar'] = 'Cantidad';
	$conf['database']['campos_sql'][2]['logitud_max'] = '3';
	$conf['database']['campos_sql'][2]['nombre_intext'] = 'intext_'.$conf['database']['campos_sql'][2]['nombre_db'];
	$conf['database']['campos_sql'][2]['logitud_intext'] = '6';
}


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
if( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc']!="todos"))
{
	$query_name="Select Nombre from Componente where Id_Componente=" .$_SESSION['Id_comp_subc'];
	$resul_name=mysql_query($query_name,$db_link2);
	$total_name=mysql_num_rows($resul_name);
	for($y=0;$y<$total_name;$y++)
	{
		$resultado_name=mysql_fetch_array($resul_name);
		$nombre_componente=$resultado_name['Nombre'];
	}
	$conf['texto']['titulo_tabla'] = 'Listado de Subcomponentes  de ' .$nombre_componente; 
}
else
{
	$conf['texto']['titulo_tabla'] = 'Listado de Subcomponentes';
}
$conf['texto']['boton_limpiar'] = 'Limpiar<br>Campos';
$conf['texto']['boton_seleccionar'] = 'Selec.<br>Todo';
//if($_SESSION['Id_comp_subc']=='todos'){
	$conf['texto']['boton_nuevo'] = 'Nuevo';
/*}
else{
	$conf['texto']['boton_nuevo'] = 'Agre';
}*/
$conf['texto']['confirmar_eliminar_seleccionado'] = '¿Seguro que desea eliminar los registros seleccionados?';
$conf['texto']['numero_pagina'] = 'Página';
$conf['texto']['palabra_clave'] = 'Clave';

// Configuración del menú contextual
if( ( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc'])!="todos" ) && $_SESSION['no_modificar']=="no" )
{
	$conf['menu_contextual'][0]['texto'] = 'Eliminar';
	$conf['menu_contextual'][0]['accion'] = 'remove_sub';
	$conf['menu_contextual'][1]['texto'] = 'Operaciones';
	$conf['menu_contextual'][1]['link'] = 'listado_op_preventivas.php';
}
else if(( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc'])!="todos" ) && $_SESSION['no_modificar']=="si" )
{
	$conf['menu_contextual'][0]['texto'] = 'Modificar';
	$conf['menu_contextual'][0]['accion'] = 'modify';
	$conf['menu_contextual'][1]['texto'] = 'Eliminar';
	$conf['menu_contextual'][1]['accion'] = 'remove';
	$conf['menu_contextual'][2]['texto'] = 'Operaciones';
	$conf['menu_contextual'][2]['link'] = 'listado_op_preventivas.php';
}
else
{
	$conf['menu_contextual'][0]['texto'] = 'Modificar';
	$conf['menu_contextual'][0]['accion'] = 'modify';
	$conf['menu_contextual'][1]['texto'] = 'Eliminar';
	$conf['menu_contextual'][1]['accion'] = 'remove';
	$conf['menu_contextual'][2]['texto'] = 'Operaciones';
	$conf['menu_contextual'][2]['link'] = 'listado_op_preventivas.php';

}
// Si quieres modificar el menú contextual preguntar a Jaime


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

if ($_GET['confirmar_eliminar'] != '') {


	if(($_GET['confirmar_eliminar'] != 'rs_sub') && ($_GET['confirmar_eliminar'] != 'rs') && (isset($_GET['confirmar_eliminar'])))
	{
		
			$sql_confir="SELECT * FROM comp_subcomp WHERE Id_Subcomponente=".$_GET['confirmar_eliminar'];
			$res_sql_confir=MYSQL_QUERY($sql_confir);

			$n_res_sql_confir=MYSQL_NUM_ROWS($res_sql_confir);

			if(($n_res_sql_confir >= 1) && ($_SESSION['Id_comp_subc']=="todos"))
			{
				
?>
		<script language="Javascript">
				var borrar_check=confirm("El subcomponente esta asignado a más de un componente ¿Desea eliminarlo?");

				if(borrar_check)
					{
						location.href="?pag=listado_subcomponentes.php&borrar_subcomponentes_check=1<?PHP if ($_GET['no_modificar_camp']=='no'){echo "&no_modificar_camp=".$_GET['no_modificar_camp'];} ?>";
					}
		</script>
<?php			
				
			}
			else
			{

			$sql = 'DELETE FROM '.$conf['database']['nombre_tabla_borrar'].' WHERE ';
			$tope = count($conf['database']['clave_tabla_borrar']);
			// $conf['database']['clave_tabla'][0] = 'Id_Pais';
			for ($k = 0; $k < $tope; $k++) {
				if($k==0){
					if($_SESSION['Id_comp_subc']=='todos'){
						$sql= $sql.$conf['database']['clave_tabla_borrar'][$k].' = "" AND ';
					}
					else{
						$sql= $sql.$conf['database']['clave_tabla_borrar'][$k].' = '.$_SESSION['Id_comp_subc'].' AND ';
					}
				}
				else{
				$sql = $sql.$conf['database']['clave_tabla_borrar'][$k].' = '.$_GET['confirmar_eliminar'].' AND ';		
				}
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
	
	if($_GET['confirmar_eliminar'] == 'rs_sub')
	{


		/* AQUI TENGO QUE METER ALGO PARA HACER EL BORRADO 
		DE LOS SUBCOMPONENTES SELECCIONADOS DE LA TABLA DE COMP_SUBCOMP*/

		$j = COUNT($_GET['checks']);
		for ($i = 0; $i < $j; $i++) {
			$sql = 'DELETE FROM '.$conf['database']['nombre_tabla_borrar'].' WHERE ';
			$tope = count($conf['database']['clave_tabla_borrar']);
			// $conf['database']['clave_tabla'][0] = 'Id_Pais';
			for ($k = 0; $k < $tope; $k++) {
				if($k==0){
				$sql= $sql.$conf['database']['clave_tabla_borrar'][$k].' = '.$_SESSION['Id_comp_subc'].' AND ';
				}
				else{
				$sql = $sql.$conf['database']['clave_tabla_borrar'][$k].' = '.$_GET['checks'][$i].' AND ';		
				}
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
	
 	if ($_GET['confirmar_eliminar'] == 'rs') {
		$j = COUNT($_GET['checks']);
		$contador_check=0;
		
$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
			if (!$db_link) {
			   die("Could not connect: ".MYSQL_ERROR());
			}
			MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
			/* Performing SQL query */
			
			/*AQUI BORRO LA VARIABLE DE SESSION DONDE GUARDABA LOS VALORES QUE HABIA EN LOS CHIECK*/
			
			UNSET($_SESSION['mas_subcomp']);
			
			
		for ($i = 0; $i < $j; $i++) {
			$sql_confirmar="Select count(*) AS numero from comp_subcomp where Id_Subcomponente = ".$_GET['checks'][$i];
			
			$res_confirmar=MYSQL_QUERY($sql_confirmar,$db_link);

			$n_res_conf=MYSQL_NUM_ROWS($res_confirmar);
			$fila_checks=MYSQL_FETCH_ARRAY($res_confirmar);
			for($e=0;$e < $n_res_conf;$e++)
			{
				$_SESSION['mas_subcomp'][$i]=$_GET['checks'][$i];

				if($fila_checks['numero'] >= 1)
					{
						$contador_check++;
						
					}
			}
			
			if($contador_check==0)
				{
					$sql = 'DELETE FROM '.$conf['database']['nombre_tabla'].' WHERE ';
					$tope = count($conf['database']['clave_tabla']);
					// $conf['database']['clave_tabla'][0] = 'Id_Pais';
					$aux = split('\\'.$conf['database']['separador_clave'],$_GET['checks'][$i]);
					for ($k = 0; $k < $tope; $k++) {
						$sql = $sql.$conf['database']['clave_tabla'][$k].' LIKE "'.$aux[$k].'" AND ';		
				
				$sql = SUBSTR($sql,0,-5).';';
					
				/*$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
				if (!$db_link) {
				   die("Could not connect: ".MYSQL_ERROR());
				}*/
				//MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");		
				/* Performing SQL query */
				$result = MYSQL_QUERY($sql,$db_link);
				//echo $sql;
				//echo $result;
				/* Closing connection */
				
				}
			}

		}MYSQL_CLOSE($db_link);
		if($contador_check >= 1)
			{
?>
	<script language="Javascript">
			var borrar_check=confirm("Alguno de los subcomponentes que desea borrar esta asociado a un componente ¿Desea eliminarlo?");

			if(borrar_check)
				{
					location.href="?pag=listado_subcomponentes.php&borrar_subcomponentes_check=1";
				}
	</script>
<?php
			}
	}
	else if($_SESSION['Id_comp_subc'] == 'todos'){

		

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
		$result = MYSQL_QUERY($sql,$db_link);
		$sql;
		//echo $result;
		/* Closing connection */
		MYSQL_CLOSE($db_link);
	}
}

// Insertar un nuevo registro en la tabla
if($_GET['listado_accion'] == 'agregar'){

	if(($_GET['seleccion_subcomponente_componente']== "") || ($_GET['ins_cantidad'] == ""))
	{
		?>
		<script language="Javascript">alert("El campo de cantidad o nombre no estan rellenos");</script>
		<?php
	}
	else
	{
		$sql_ini = "INSERT INTO ".$conf['database']['nombre_tabla_borrar']."(";
		
		$tope = COUNT($conf['database']['campos_sql_insert']);
		
		for ($i = 0; $i < $tope; $i++) {
			$sql_ini = $sql_ini.$conf['database']['campos_sql_insert'][$i].',';
		}
		$sql_ini = SUBSTR($sql_ini,0,-1).') VALUES (';
		for ($i = 0; $i < $tope; $i++) {
			if ($i == 0) {
				$sql_ini = $sql_ini.''.$_SESSION['Id_comp_subc'].',';
			}
			else if($i == 1){
				$sql_ini = $sql_ini.''.$_GET['seleccion_subcomponente_componente'].',';
			}
			else if($i == 2){
				$sql_ini = $sql_ini.''.$_GET['ins_cantidad'].',';
			}
		}	
	}
		//echo "<br>algo aqui pasa".
			$sql_ini = SUBSTR($sql_ini,0,-1).');';
	
	/* Connecting, selecting database */	
	$db_link = MYSQL_CONNECT($conf['database']['host'],$conf['database']['user'],$conf['database']['pass']);
	if (!$db_link) {
	   die("Could not connect: ".MYSQL_ERROR());
	}
	MYSQL_SELECT_DB($conf['database']['nombre_db']) or die("Could not select database");	
	/* Performing SQL query */
	$result = MYSQL_QUERY($sql_ini,$db_link);
	//echo $result;
	/* Closing connection */
	MYSQL_CLOSE($db_link);
}

if ($_GET['listado_accion'] == 'new') {
	
	if( isset($_GET['nintext_Nombre']) && ($_GET['nintext_Nombre'] =="")){
?>
	<script language="Javascript">
		alert("Falta rellenar el campo del nombre del subcomponente");
	</script>
<?PHP		
	}
	else
	{
	
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
		 
		$result = MYSQL_QUERY($sql_ini,$db_link);
		//echo $result;
		/* Closing connection */
		MYSQL_CLOSE($db_link);
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

$sql_select="Select Nombre,Id_Subcomponente from Subcomponente where Id_Subcomponente <> '' AND Id_Subcomponente NOT IN (SELECT Id_Subcomponente
																			FROM comp_subcomp
																			WHERE Id_Componente = ".$_SESSION['Id_comp_subc'].")";

if( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc'])!="todos"){

$j = COUNT($conf['database']['campos_sql']);
//echo "<br>numero de filas".$j;
/*for ($i = 0; $i < $j; $i++) {
	if ($_GET[$conf['database']['campos_sql'][$i]['nombre_intext']] != '') {
		$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%"';
	}
}*/

if(($_GET['id_componente'] !='') || ($_GET['menu_accion']=='remove_sub') || ($_GET['confirmar_eliminar']!='')){
	
		$conf['database']['consulta_sql'] = 'SELECT * FROM comp_subcomp INNER JOIN (
														SELECT Id_Subcomponente, Nombre, CONCAT( Id_Subcomponente ) AS Clave
														FROM Subcomponente
														WHERE 1 AND Id_Subcomponente IN (
																				SELECT Id_Subcomponente
																				FROM comp_subcomp
																				WHERE Id_Componente =' .$_SESSION['Id_comp_subc'].')
														) AS CONSULTA ON CONSULTA.Id_Subcomponente LIKE comp_subcomp.Id_Subcomponente										WHERE Id_Componente='.$_SESSION['Id_comp_subc'].'
															ORDER BY CONSULTA.';

		if((($_GET['confirmar_eliminar']!='') ||($conf['resultado_sql']['orden_campo']=='cantidad'))&& (($_GET['listado_orden_campo']=='cantidad') || ($conf['resultado_sql']['orden_campo']!= 'Id_Subcomponente')))
	{
	$conf['database']['consulta_sql']=SUBSTR($conf['database']['consulta_sql'],0,-9);
	}
	
	}
	else{
		$conf['database']['consulta_sql'] = 'SELECT * FROM comp_subcomp INNER JOIN (
													SELECT Id_Subcomponente, Nombre, CONCAT( Id_Subcomponente ) AS Clave
													FROM Subcomponente
													WHERE 1 AND Id_Subcomponente IN (
																			SELECT Id_Subcomponente
																			FROM comp_subcomp
																			WHERE Id_Componente =' .$_SESSION['Id_comp_subc'].')';

for ($i = 0; $i < $j; $i++) {
if(($_GET[$conf['database']['campos_sql'][$i]['nombre_intext']] != '') && ($conf['database']['campos_sql'][$i]['nombre_db'] == 'Nombre'))
		{
			$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%"';
		}
}

			$conf['database']['consulta_sql']=$conf['database']['consulta_sql'].') AS CONSULTA ON CONSULTA.Id_Subcomponente LIKE comp_subcomp.Id_Subcomponente  ';

for ($i = 0; $i < $j; $i++) {
if(($_GET[$conf['database']['campos_sql'][$i]['nombre_intext']] != '') && ($conf['database']['campos_sql'][$i]['nombre_db'] != 'Nombre'))
		{
			$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%" ';
		}
}
			$conf['database']['consulta_sql']=$conf['database']['consulta_sql'].' WHERE Id_Componente='.$_SESSION['Id_comp_subc'].' ORDER BY ';
	}
}

if(($_GET['id_componente'] !='') || ($_GET['menu_accion']=='remove_sub') || ($_GET['confirmar_eliminar']!=''))
{
if($_SESSION['Id_comp_subc'] != 'todos'){
	
	$conf['database']['consulta_sql']=$conf['database']['consulta_sql'].'';
	$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' '.$conf['resultado_sql']['orden_campo'].' '.$conf['resultado_sql']['orden_tipo'];
	}
	/*else{
	$conf['database']['consulta_sql']=$conf['database']['consulta_sql'].' ORDER BY ';
	$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' '.$conf['resultado_sql']['orden_campo'].' '.$conf['resultado_sql']['orden_tipo'];
	}*/
}


if ( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc']!="todos") )
{

if($conf['database']['campos_sql'][$i]['nombre_db'] !='Id_Subcomponente')
	{
		//$conf['database']['consulta_sql']=SUBSTR($conf['database']['consulta_sql'],0,-9);
	}

		if($_GET['listado_orden_campo'] == 'cantidad' ){
			
			$conf['database']['consulta_sql']=' '.$conf['database']['consulta_sql'].' ';
		}
		if($_GET['listado_orden_campo'] == 'Nombre'){
			
		$conf['database']['consulta_sql']=$conf['database']['consulta_sql'].' CONSULTA.';
		}
		if(($conf['resultado_sql']['orden_campo']== 'Id_Subcomponente')&& !(($_GET['id_componente'] !='') || ($_GET['menu_accion']=='remove_sub') || ($_GET['confirmar_eliminar']!=''))){
			
		$conf['database']['consulta_sql']=$conf['database']['consulta_sql'].' CONSULTA.';
		}

	If (!(($_GET['id_componente'] !='') || ($_GET['menu_accion']=='remove_sub') || ($_GET['confirmar_eliminar']!='')))$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' '.$conf['resultado_sql']['orden_campo'].' '.$conf['resultado_sql']['orden_tipo'];
}
else if($_SESSION['Id_comp_subc']=="todos")
{
	
	$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%" ORDER BY '.$conf['resultado_sql']['orden_campo'].' '.$conf['resultado_sql']['orden_tipo'];
}
/*else
{
			$conf['database']['consulta_sql']=$conf['database']['consulta_sql'].' ORDER BY ';
			echo "<br>".$conf['database']['consulta_sql'] = $conf['database']['consulta_sql'].' AND '.$conf['database']['campos_sql'][$i]['nombre_db'].' LIKE "'.$_GET[$conf['database']['campos_sql'][$i]['nombre_intext']].'%"';
}*/
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



// Número de filas a mostrar en pantalla

if ((isset($_GET['numero_filas']))&&(floor($_GET['numero_filas']) > 0)) {
	$conf['resultado_sql']['numero_filas'] = $_GET['numero_filas'];
}

/* SI HEMOS ESCOGIDO UNA OPCIÓN EN EL MENÚ CONTEXTUAL */

if ($_GET['menu_accion'] != '') 
{
	$conf['menu_accion'] = $_GET['menu_accion'];
	$conf['menu_valor'] = $_GET['menu_valor'];
	
	if ($conf['menu_accion'] == 'rs_sub')
	{// Remove selected - Eliminar campos selecionados
		if (count($_GET['checks']) > 0) 
		{
			$conf['remove_selected'] = TRUE;
		}
	}
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

/*if ($conf['remove_selected'] == TRUE) {
	
	for ($i = 0; $i < count($_GET['checks']); $i++) {
		echo $_GET['checks'][$i].'<br>';
	}	
}*/

if (($_GET['confirmar_modificar'] != '') && ($_GET['mintext_cantidad'] == "")) {
	$sql = "UPDATE ".$conf['database']['nombre_tabla'].' SET ';
	$tope = count($conf['database']['campos_sql']);
	$aux = split('\\'.$conf['database']['separador_clave'],$_GET['confirmar_modificar']);
	for ($i = 0; $i < $tope; $i++) {
		if($conf['database']['campos_sql'][$i]['nombre_db']=='Id_Subcomponente')
		{
			$sql = $sql.$conf['database']['campos_sql'][$i]['nombre_db'].'="'.$aux[$i].'",';
		}
		else{
			$sql = $sql.$conf['database']['campos_sql'][$i]['nombre_db'].'="'.$_GET['m'.$conf['database']['campos_sql'][$i]['nombre_intext']].'",';		
		}
	}
	$sql = SUBSTR($sql,0,-1).' WHERE ';
	$tope = count($conf['database']['clave_tabla']);
	// $conf['database']['clave_tabla'][0] = 'Id_Pais';
	
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
else if(($_GET['confirmar_modificar'] != '') && ($_GET['mintext_cantidad'] != ""))
{
	
	$sql = "UPDATE ".$conf['database']['nombre_tabla_borrar'].' SET ';
	$tope = count($conf['database']['campos_sql']);
	$aux = split('\\'.$conf['database']['separador_clave'],$_GET['confirmar_modificar']);
	for ($i = 0; $i < $tope; $i++) {
		if($conf['database']['campos_sql'][$i]['nombre_db']=='cantidad')
		{
			$sql = $sql.$conf['database']['campos_sql'][$i]['nombre_db'].'="'.$_GET['mintext_cantidad'].'",';
		}
		/*else{
			$sql = $sql.$conf['database']['campos_sql'][$i]['nombre_db'].'="'.$_GET['m'.$conf['database']['campos_sql'][$i]['nombre_intext']].'",';		
		}*/
	}
	$sql = SUBSTR($sql,0,-1).' WHERE ';
	$tope = count($conf['database']['clave_tabla']);
	// $conf['database']['clave_tabla'][0] = 'Id_Pais';
	
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

	function nuevo(valor) {		
		<?PHP echo $conf['general']['nombre_formulario']; ?>.listado_accion.value = valor;
		
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
  	//echo "<br><br>esta es la consulta que se hace a la base de datazos  ".$conf['database']['consulta_sql'];
	
	 if (MYSQL_NUM_ROWS($result) >= 0) {
		 
?>

<form name="<?PHP echo $conf['general']['nombre_formulario']; ?>" action="?pag=listado_subcomponentes.php">

<input type="hidden" name="no_modificar_camp" value="<?php echo $_SESSION['no_modificar'];?>">
<input type="hidden" name="listado_accion" value="">
<input type="hidden" name="pag" value="listado_subcomponentes.php">
<input type="hidden" name="listado_pagina" value="<?PHP echo $conf['resultado_sql']['mostrar_pagina']; ?>">
<input type="hidden" name="listado_ventana" value="<?PHP echo $conf['resultado_sql']['mostrar_ventana_paginas']; ?>">

<input type="hidden" name="listado_orden_campo" value="<?PHP echo $conf['resultado_sql']['orden_campo']; ?>">
<input type="hidden" name="listado_orden_tipo" value="<?PHP echo $conf['resultado_sql']['orden_tipo']; ?>">

<input type="hidden" name="listado_foco" value="">

<input type="hidden" name="menu_valor" value="">
<input type="hidden" name="menu_accion" value="">

<input type="hidden" name="confirmar_modificar" value="">
<input type="hidden" name="confirmar_eliminar" value="">

	<table class="listados" style="width:900 px;">
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
	if($i==0){}
	else{
?>
				<th class="titulo" id="borde_db" >
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
						<div class="intexto" >
						
							<input type="text" name="<?PHP echo $conf['database']['campos_sql'][$i]['nombre_intext']; ?>" value="<?PHP echo  $_GET[$conf['database']['campos_sql'][$i]['nombre_intext']]; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_max']; ?>" onkeyup="filtrar(<?PHP echo $i; ?>);">
<?PHP
			// Continuación del script de limpiar campos
			$script = $script.$conf['general']['nombre_formulario'].'.'.$conf['database']['campos_sql'][$i]['nombre_intext'].'.value = "";';
?>
						</div>
					</div>
				</th>
<?PHP
	}
		}
		
		// Terminamos de construir el script
		$script = $script.'}</script>';
		echo $script;
?>
				<th colspan="2" rowspan="2" class="boton" id="borde_idb" style="width:68px;">
					<a class="boton" href="#" onclick="limpiar_campos();">
						<div class="boton" style="left:40px;">
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
				<th colspan="<? echo COUNT($conf['database']['campos_sql']); ?>" id="borde_i">
					<div class="intexto">
<?PHP
			echo $conf['texto']['confirmar_eliminar_seleccionado'];
?>
					</div>
				</th>
<?php
				if( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc'])!="todos")
	{
?>
				<a href="#" class="confirmar_aceptar" >
					<th onclick="confirmarEliminar('rs_sub');" title="Eliminar los Registros" >
					</th>
					
				</a>
<?php
	}
	else
	{
?>
				<a href="#" class="confirmar_aceptar" >
					<th onclick="confirmarEliminar('rs');" title="Eliminar los Registros" >
					</th>
				</a>
<?php
	}
?>
				<a href="#" class="confirmar_cancelar" >
					<th onclick="confirmarEliminar('NULL');" title="Cancelar" id="borde_d" >
					</th>
				</a>
			</tr>
<?PHP	
		}
		else {						
?>
			<tr>
				<th id="borde_i" align="center">
					<a href="#" align="center" class="papelera">
						<div style="float: left; width:4px;">
						</div>
<?php
						if((isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc'])!="todos")
						{
?>
							<div class="papelera" title="Borrar entradas seleccionadas" onclick="enviarAccion('rs','sel');">
							</div>
<?php
						}
						else
						{
?>
							<div class="papelera" title="Borrar entradas seleccionadas" onclick="enviarAccion('rs','sel');">
							</div>
<?php
						}
?>
					</a>
				</th>
<?PHP	
			$tope = COUNT($conf['database']['campos_sql']);
			for ($i = 0; $i < $tope; $i++) {
				if($i==0){}
				else
				{
?>
				<th>
					<div class="intexto">
<?php
					if( (isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc'])!="todos")
					{
					if($i==1){
?>
			<select name="seleccion_subcomponente_componente">
			<option></option>
<?php
				//esta consulta es para rellenar el select que sale al venir del listado de componentes con un componente seleccionado

					
					$resul_select_sub=mysql_query($sql_select,$db_link);
					$num_resultados_sub=mysql_num_rows($resul_select_sub);

					if($num_resultados_sub!=0)
					{
					for($z=0;$z < $num_resultados_sub;$z++)
						{
							$fila_subcomponente=mysql_fetch_array($resul_select_sub);
?>
							<option value="<?php echo $fila_subcomponente['Id_Subcomponente'];?>" title="<?php echo $fila_subcomponente['Nombre']?>"><?php echo SUBSTR($fila_subcomponente['Nombre'],0,40); ?></option>
<?php	
						}
					}
					?>
			</select>
			</div>
				</th>
<?php
				}
					if($i==2){
?>
				
				<div>
					<input type="text" name="ins_cantidad" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>">
					
				</div>
				
<?php				
					}
					}
					else
					{
?>
						<input type="text" name="<?PHP echo 'n'.$conf['database']['campos_sql'][$i]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$i]['logitud_max']; ?>">
<?php
					}
?>
					</div>
				</th>
<?php
				}
			}
?>
				<th colspan="2" id="borde_d">
					<a class="boton2" href="#" >
<?php
	if((isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc'])!="todos"){
?>
						<div class="boton" onclick="nuevo('agregar');" >
<?php
	}
	else
			{
?>
						<div class="boton" onclick="nuevo('new');">
<?PHP
			}
			echo $conf['texto']['boton_nuevo'];
?>
						</div>
					</a>
				</th>
			</tr>
<?PHP
		}
?>
			<tr>
				<th colspan="<?PHP echo COUNT($conf['database']['campos_sql']) + 2; ?>" class="paginas" id="borde_idb">
					<div id="fleft" >
<?PHP
		
		if ($conf['general']['volver'] == true) {
?>
					<a href="?pag=listado_componentes.php&Id_Centro=todos" align=center class="volver">
						<div style="float: left; width:4px;">
						</div>
						<div class="volver" title="Página Anterior" >
						</div>
						<div style="float: left; width:20px;">
						</div>
						
					</a>
<?PHP
		}
		
		

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
						registros
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
			<!--tr class="blanco">
			</tr-->

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
				if ($conf['menu_accion'] == 'modify') {
				/* MODIFICAR REGISTRO */
?>
				<tr id="<?PHP echo $fila['Clave']; ?>" class="accion" >
					<td id="borde_i" align="center" class="accion">						
					</td>
<?PHP	
					$tope2 = COUNT($conf['database']['campos_sql']);
					for ($j = 0; $j < $tope2; $j++) {
						if($j==0){}
						else{
							if( ($conf['database']['campos_sql'][$j]['nombre_intext']=='intext_Nombre') && ($_SESSION['no_modificar']=="si") && $_SESSION['Id_comp_subc'] == "todos")
							{
?>
					<td class="accion" >
						<div class="intexto">
							<input type="text" name="<?PHP echo 'm'.$conf['database']['campos_sql'][$j]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_max']; ?>" value="<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
						</div>
					</td>
<?PHP	
							}
							else if( ($conf['database']['campos_sql'][$j]['nombre_intext']!='intext_Nombre'))
							{
?>

					<td class="accion" >
						<div class="intexto">
							<input type="text" name="<?PHP echo 'm'.$conf['database']['campos_sql'][$j]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_max']; ?>" value="<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
						</div>
					</td>
					<?
							}
							else
							{
?>
			<td class="accion" >
						<div class="intexto">
							<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>

							<input type="hidden" name="<?PHP echo 'm'.$conf['database']['campos_sql'][$j]['nombre_intext']; ?>" size="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_intext']; ?>" maxLength="<?PHP echo $conf['database']['campos_sql'][$j]['logitud_max']; ?>" value="<?PHP echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>">
						</div>
					</td>
<?php
							}
						}
					}
?>

			
						<a href="#" class="confirmar_aceptar" >
							<td onclick="confirmarModificar('<?PHP echo $conf['menu_valor']; ?>');" title="Aceptar Modificación" >
							</td>
						</a>
						 <!--style="background-repeat:no-repeat;"-->
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
					if($j==0){}
					else{
?>
					<td class="accion" title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>" >
<?PHP
					echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,45);
?>
					</td>
					
<?PHP	
					}
				}
?>					<a href="#" class="confirmar_aceptar" >
						<td class=""  onclick="confirmarEliminar('<?PHP echo $conf['menu_valor']; ?>');" title="Eliminar el Registro" >
						</td>
					</a>
					<a href="#" class="confirmar_cancelar">
						<td onclick="confirmarEliminar('NULL');" title="Cancelar" id="borde_d" >
						</td>
						
					</a>
				</tr>
<?PHP
				/* FIN ELIMINAR REGISTRO */
				}
				else if ($conf['menu_accion'] == 'remove_sub'){
					/*ELIMINAR UN SUBCOMPONENTE DEL LISTADO ASOCIADO A UN COMPONENTE*/
?>
				<tr id="<?PHP echo $fila['Clave']; ?>" class="accion">
					<td id="borde_i" align="center" class="accion">
					</td>
<?PHP	
				$tope2 = COUNT($conf['database']['campos_sql']);
				for ($j = 0; $j < $tope2; $j++) {
					if($j==0){}
					else{
?>
					<td class="accion" title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>" >
<?PHP
					echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,40);
?>
					</td>
<?PHP	
					}
				}
?>					<a href="#" class="confirmar_aceptar" >
						<td onclick="confirmarEliminar('<?PHP echo $conf['menu_valor']; ?>');" title="Eliminar el Registro" >
						</td>
					</a>
					
					<a href="#" class="confirmar_cancelar">
						<td onclick="confirmarEliminar('NULL');" title="Cancelar" id="borde_d" >
						</td>
					</a>
					
				</tr>
<?PHP
				}
				//else if {
					/* Preparado para otra función del menú contextual */
				//}
			}
			else {
?>
			<a href="#">
				<tr id="<?PHP echo $fila['Clave']; ?>" oncontextmenu="mostrarMenu('<?PHP echo $fila['Clave']; ?>');">
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
					if($j==0){}
					else{
?>
					<td title="<?php echo $fila[$conf['database']['campos_sql'][$j]['nombre_db']]; ?>" >
<?PHP
					echo SUBSTR($fila[$conf['database']['campos_sql'][$j]['nombre_db']],0,40);
?>
					</td>
					
					
<?PHP	
					}
				}
?>
					<td style="width:23px;">
					</td>
					<td id="borde_d" style="width:25px;">	
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
				<div class="item" align="center" style="width:140px;" onclick="irLink('<?PHP echo $conf['menu_contextual'][$i]['link']; ?>');">
<?PHP
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

function irLink(pag) {
	window.location.href('<? echo $conf['general']['nombre_principal']; ?>?pag=' + pag + '&id_subcomponente=' + document.<?PHP echo $conf['general']['nombre_formulario']; ?>.menu_valor.value);
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
	MYSQL_CLOSE($db_link);
?>


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
  	
}
echo '</SCRIPT>';
if(((isset($_SESSION['Id_comp_subc'])) && ($_SESSION['Id_comp_subc']=="todos")) && ((isset($_GET['listado_foco'])) &&($_GET['listado_foco']==''))){
?>
	<script language="Javascript">
		<?php echo $conf['general']['nombre_formulario'].'.n'.$conf['database']['campos_sql'][1]['nombre_intext'];?>.focus();
	</script>
<?php 
}
?>





