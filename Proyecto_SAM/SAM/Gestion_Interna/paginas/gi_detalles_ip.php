<?php

	if(isset($_SESSION['permisoInfPolicial']) && $_SESSION['permisoInfPolicial']==true) //Comprobando que se tiene permiso para acceder a la pagina
		{
?>
<? /*Inicio Detalles Informe Policial  */?>
<table border="0" id="tabla_detalles" width="662px" cellpadding="0" cellspacing="0">
	<tr id="titulo_tablas" style="padding-bottom:0px">
		<td colspan="2">
			<div style="height:25px;width:30px;background-image: url(../imagenes/img_tablas/esquina_arriba_izquierda.jpg);background-repeat:no-repeat;float:left;" id="alerta_esquina_izquierda">&nbsp;</div>
			<div style="height:25px;text-align:center;background-image: url(../imagenes/img_tablas/linea_horizontal.jpg);background-repeat:repeat-x;float:left;">
				<div class="titulo" style="width:600px;text-align:center;">Detalles Informe Policial</div>									
			</div>								
			<div style="height:25px;width:30px;background-image: url(../imagenes/img_tablas/esquina_arriba_derecha.jpg);float:left;">&nbsp;</div>
		</td>
	</tr>
	<? 	
	$dni_cl=$_GET['dni'];
	$tipo_documentacion_cl=$_GET['tipo_documentacion_cl'];
	$fecha_expedicion_cl=$_GET['fecha_expedicion_cl'];	
	@$db=mysql_pconnect("localhost","root","");
    if(!$db){
    	echo "Error : No se ha podido conectar a la base de datos";
    	exit;
    }
    mysql_select_db("sam");	
	$query_datos="select * from cliente where DNI_Cl='".$dni_cl."';"; 
	$result_datos = mysql_query($query_datos); 	    	
    $num_results_datos=mysql_num_rows($result_datos);
	$fila_datos=mysql_fetch_array($result_datos);
		
	?>
	<tr id="titulo_tablas" style="padding-top:0px" >
		<td align="center" id="titulo_tablas">					
			<div style="border: 1px solid #3F7BCC;" style="width:660px;height:400px;">
				<table border="0">
					<tr height="25">
						<td align='left'>
							<span style='margin-left:6px;' class='label_formulario'> D.N.I <span>
						</td>
						<td align='left'style='margin-left:6px;' id='texto_detalles'>
							<?echo $dni_cl;?>
						</td>
					</tr>					
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Tipo Documento <span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo $tipo_documentacion_cl;?>	
						</td>
					</tr>
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Fecha Expedición del Documento<span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo $fecha_expedicion_cl;?>
						</td>
					</tr>
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Primer Apellido <span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo $_GET['ap1'];?>
						</td>
					</tr>
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Segundo Apellido <span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo $_GET['ap2'];?>
						</td>
					</tr>		
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Nombre<span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo $_GET['nombre'];?>
						</td>
					</tr>
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Sexo<span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo $fila_datos['Sexo_Cl'];?>
						</td>
					</tr>
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Fecha de Nacimiento<span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo$_GET['fecha_nac'];?>
						</td>
					</tr>
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>País de Nacionalidad<span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<?echo $pais=$_GET['pais'];?>
						</td>
					</tr>
					<tr height="25">
						<td>
							<span style='margin-left:6px;' class='label_formulario'>Fecha de Entrada<span>
						</td>
						<td style='margin-left:6px;' id='texto_detalles'>
							<? echo $_GET['fecha_llegada'];?>
						</td>
					</tr>
					<tr height="60" >
						<td align="center" colspan="2">
							<a href='principal_gi.php?pag=gi_informe_policial.php'><img align="center" type='image' src='../imagenes/botones-texto/cancelar.jpg' alt="Cancelar" border="0"></a>												
						</td>						
					</tr>					
				</table>	
		</td>		
	</tr>	
	</div>
</table>		
<?/*Fin Detalles Informe Policial*/?>

<?php
		} //Fin del IF de comprobacion de acceso a la pagina
	else
		 echo "<div>
				<table border='0' id='tabla_detalles'>
					<tr id='titulo_tablas'>
						<td colspan='2'>
							<div style='width: 30px; height: 25px; background-image: url(img_tablas/esquina_arriba_izquierda.jpg); background-repeat: no-repeat; float: left;' id='alerta_esquina_izquierda'>&nbsp;</div>
							<div style='width: 290px; height: 25px; text-align: center; background-image: url(img_tablas/linea_horizontal.jpg); background-repeat: repeat-x; float: left;'>
								<div class='titulo' style='text-align: center;'>¡ERROR!</div>
							</div>
							<div style='width: 30px; height: 25px; background-image: url(img_tablas/esquina_arriba_derecha.jpg); float: left;'>&nbsp;</div>
						</td>
					</tr>
					<tr>
						<td width='350' align='center'>
							<label id='texto_detalles' style='color:red;'>NO TIENES PERMITIDO ACCEDER A ESTA SECCIÓN</label>
						</td>
					</tr>
				</table>
			</div>";
?>
