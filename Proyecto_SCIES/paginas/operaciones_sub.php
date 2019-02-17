<HTML>
<HEAD>
	<TITLE> OPERACIONES MANTENIMIENTO </TITLE>
	<META NAME="Generator" CONTENT="EditPlus">

	<link rel="StyleSheet" href="./design_jai.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="../css/css_paginas.css">		
	<link rel="StyleSheet" href="../css/estadisticas.css" type="text/css">
		<link rel="StyleSheet" href="../css/listados.css" type="text/css">

<style type='text/css'>
	
</style>
<SCRIPT language="JavaScript">

function dropit(whichone) {
	if (window.themenu && themenu.id != whichone.id) {
		themenu.style.visibility = "hidden";
	}
	themenu = whichone;
	if (document.all) {
		themenu.style.left = document.body.scrollLeft + event.clientX - event.offsetX;
		themenu.style.top = document.body.scrollTop + event.clientY - event.offsetY - 1;
		if (themenu.style.visibility == "hidden") {
			themenu.style.visibility = "visible";
		}
		else {
			hidemenu();
		}
	}
}

function hidemenu(){
	if (window.themenu)
		themenu.style.visibility = "hidden";
	}

function hidemenu2(){
	themenu.visibility = "hide";
}

if (document.all) {
	document.body.onclick = hidemenu;
}
									
</SCRIPT>	
	
		
	</head>

	<body background="../imagenes/fondos/fondo_paginas.jpg" align='center'>
	 <?php
		@ $db = mysql_pconnect("localhost" , "root" , "");
		if (!$db){
		echo "Error : No se ha podido conectar a la base de datos";
		exit;
		}
		mysql_select_db("scies");
		//codigp para la paginacion     
		$num=$_GET['num'];//Numero de subcomponente
	   
		$sql_op="select * from operaciones where Id_Subcomponente ='".$num."';";
			$result_op=mysql_query($sql_op);
			$cont=mysql_num_rows($result_op); //nº de operaciones ?>	
			<?$t=$c+1?>
			
			
			<table align='center'  class='listados' cellspacing='0' cellpadding='0' style='width:580;'>
			<thead >
				<tr>
				<th class="titulo" id="borde_idb"><div class="borde" ></div><div class="centro">
				<a class="nombre" href="#">	<div>Operacion</div></a></div></th>	
				<th class="titulo" id="borde_idb"><div class="borde" ></div><div class="centro">
				<a class="nombre" href="#">	<div>Periodicidad</div></a></div></th>
				
			</tr>
			</thead>
			
	
			<tbody>
			<?for($c2=0;$c2<mysql_num_rows($result_op);$c2++){ //For de operaciones de subcomponentes
				$fila_op=mysql_fetch_array($result_op);									
				$sql6="select * from operacion_prev  where Id_operacion_prev ='".$fila_op['Id_operacion_prev']."';";
				$result6=mysql_query($sql6);
				$fila6=mysql_fetch_array($result6);
				$cont6=mysql_num_rows($result6);?>	
				 <a href="#">
				<tr>
					<td id="borde_idb" title='<? echo $fila6['Nombre'];?>'><? echo $fila6['Nombre'];?></td>
					<td align='center' id="borde_idb" title='<? echo $fila6['Periodicidad'];?>'><? echo $fila6['Periodicidad'];?></td>
				</tr> </a>
		   <?}?>
		   </tbody>
		 
		  </table>
			
	</body>
</html>



















