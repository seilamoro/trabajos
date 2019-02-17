

<script language="javascript">
 	
	 
	 function mostrar_calendario(dia,mes,ano,ins){
	  
   if(ins==1){
  		 window.open("calendarios.php?dia="+dia+"&mes="+mes+"&ano="+ano+"&ins=1","ventana2", "width=270px,height=260px,resizable=yes,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");
   }
   if(ins==2){
  		 window.open("calendarios.php?dia="+dia+"&mes="+mes+"&ano="+ano+"&ins=2","ventana2", "width=270px,height=260px,resizable=yes,toolbar=no,channelmode=no,directories=no,location=no,status=no,titlebar=no,top=200px,left=550px");
   }
   
	}
	function cambiar(centro,accion){
	  
	  	//alert('hola');
	  
	
	    if(accion="m"){
	    var fechainicio=document.formulario.fechainicio.value;
	    var fechafin=document.formulario.fechafin.value;
	    window.location.href="fechas_operativas.php?accion=mod&fechainicio="+fechainicio+"&fechafin="+fechafin+"&centro="+centro;
 		}
 		
 		if(accion="n"){
		var fechainicio=document.formulario.fechainicio.value;
	    var fechafin=document.formulario.fechafin.value;
		//window.opener.document.formulario_listado.fecha_ini.value=fechainicio;
		
		//window.opener.document.formulario_listado.fecha_fin.value=fechafin;
				
				window.opener.document.formulario_listado.listado_accion.value = 'new';
				window.opener.document.formulario_listado.fecha1.value = fechainicio;
				window.opener.document.formulario_listado.fecha2.value = fechafin;
				window.opener.document.formulario_listado.submit();
		
		
		
		
		window.close(); 
		}
	
	}
	
	

</script>

<HTML>
	<HEAD>
		<link rel="StyleSheet" href="../css/principal.css" type="text/css">
		<link rel="StyleSheet" href="../css/listados.css" type="text/css">
		<link rel="stylesheet" type="../text/css" href="css/menu.css">
		<link rel="StyleSheet" href="../css/pestañas.css" type="text/css">
	
	</HEAD>


<BODY background="imagenes/fondos/fondot.jpg" >



<?
		function formatearFecha($fechaOriginal)
		{
		$fechaNueva=split("-",$fechaOriginal);
		$fechaNueva=$fechaNueva[2]."-".$fechaNueva[1]."-".$fechaNueva[0];
		return $fechaNueva;
		}


	//conexión
	
	@$db=mysql_pconnect('localhost','root','');
    if(!$db)
		{
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
	    }

     mysql_select_db('SCIES');




	$accion=$_GET['accion'];
	$acciones="'".$_GET['accion']."'";
	//print($acciones);
	
	
	if($accion=="n"){
	 
	$tiempo_actual = time();
	$mes = date("n", $tiempo_actual);
	$ano = date("Y", $tiempo_actual);
	$dia=date("d");
	$fecha_inicio=$ano . "-" . $mes . "-" . $dia;
	$fecha_final=$ano . "-" . $mes . "-" . $dia;
	$_GET['Id_Centro']=0;
	
	} else {
	
	
	
	  
	$fecha_inicio=$_GET['fecha_ini'];
	$fecha_final=$_GET['fecha_fin'];
	
	
	

	
	$fechaNuevai=split("-",$fecha_inicio);
	$diai=$fechaNuevai[2];
	$mesi=$fechaNuevai[1];
	$anoi=$fechaNuevai[0];
	$fechaNuevaf=split("-",$fecha_final);
	$diaf=$fechaNuevaf[2];
	$mesf=$fechaNuevaf[1];
	$anof=$fechaNuevaf[0];
		
	  
	}
	
	//Si quiero modificar
	
	if ($_GET['accion']=='mod'){
	  
		$sql="UPDATE centro SET Fecha_Inicio='".formatearFecha($_GET['fechainicio'])."',Fecha_Fin='".formatearFecha($_GET['fechafin'])."' WHERE Id_Centro=".$_GET['centro'];
		//print($sql);
		$resul = mysql_query($sql);
		echo "
                <script>
                window.close();
                window.opener.document.formulario_listado.submit();

                </script>";
	 
	
	  
	  
	  
	  
	}
	
	
	
	

?>
<div class="titulo1" width="100%">	
<FORM Name="formulario" method="POST" action="#">
Meses Operativos
<TABLE>
	<?if(isset($diai)){?>
	<TR>

		<TD class="titulo2" width='160px' style="text-align:center;">
			Fecha Inicio <br>
			<input type=text  size='10' id='fechainicio' value=<?print(formatearFecha($fecha_inicio));?>>
			<input type='button' title="Abrir Calendario" class='boton_small' style="cursor:pointer;" value="[...]" onclick="mostrar_calendario(<?print($diai.",".$mesi.",".$anoi.",1");?>);">
		</TD>
		<TD class="titulo2" width='160px' style="text-align:center;">
			Fecha Fin <br>
			<input type=text  size='10' id='fechafin' value=<?print(formatearFecha($fecha_final));?>>
			<input type='button' title="Abrir Calendario" class='boton_small' style="cursor:pointer;" value="[...]" onclick="mostrar_calendario(<?print($diaf.",".$mesf.",".$anof).",2";?>);">
		</TD>
	</TR>
	<TR>
		<TD colspan="2" align="center">
			<input type='button' style="cursor:pointer;" title="Modificar la fecha operativa del centro" class='boton_big' onClick="cambiar(<?print($_GET['Id_Centro'])?>,<?print($acciones)?>);" value="Modificar" >
			
		</TD>
	</TR>
	<?}else {?>
	<TR>

		<TD class="titulo2" width='160px' style="text-align:center;">
			Fecha Inicio <br>
			<input type=text  size='10' id='fechainicio' value=<?print(formatearFecha($fecha_inicio));?>>
			<input type='button' title="Abrir Calendario" class='boton_small' style="cursor:pointer;" value="[...]" onclick="mostrar_calendario(<?print($dia.",".$mes.",".$ano.",1");?>);">
		</TD>
		<TD class="titulo2" title="Introducir Fecha Final" width='160px' style="text-align:center;">
			Fecha Fin <br>
			<input type=text  size='10' id='fechafin' value=<?print(formatearFecha($fecha_final));?>>
			<input type='button' title="Abrir Calendario" class='boton_small' style="cursor:pointer;" value="[...]" onclick="mostrar_calendario(<?print($dia.",".$mes.",".$ano).",2";?>);">
		</TD>
	</TR>
	<TR>
		<TD colspan="2" align="center">
			<input type='button' style="cursor:pointer;" title="Aceptar" class='boton_big' onClick="cambiar(<?print($_GET['Id_Centro'])?>,<?print($acciones)?>);" value="Aceptar" >
			
		</TD>
	</TR>
	
	<?}?>
</TABLE>

</form>
</div>

</body>
</HTML>