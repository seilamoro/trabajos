
<?
if((isset($_SESSION['permisoInfPolicial']))&& ($_SESSION['permisoInfPolicial']==true)){
  

@$db  = mysql_pconnect($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);

mysql_select_db($_SESSION['conexion']['db'],$db);	

 $sql="SELECT * FROM albergue ";

               $resul = mysql_query($sql);
               $fila = mysql_fetch_array($resul);
               $idpais=$fila['Id_Pais'];
               $idpro=$fila['Id_Pro'];
          

if (isset($_POST['cif'])){

	$sqlmod="UPDATE albergue SET CIF = '".$_POST['cif']. "',    
		Tipo_registro_Alb ='".$_POST['tipo_registro_alb']. "',
		Tipo_registro_Datos ='".$_POST['tipo_registro_datos']. "',
		Cod_Establecimiento ='".$_POST['cod_establecimiento']. "' WHERE `CIF` = '".$_POST['cif']. "'";
  
        $resulmod = mysql_query($sqlmod);
        $sql="SELECT * FROM albergue ";

        $resul = mysql_query($sql);
        $fila = mysql_fetch_array($resul);
        $idpais=$fila['Id_Pais'];
        $idpro=$fila['Id_Pro'];
}
?>
<table border="0">
<tr>
<td>
	<div id='datos' style='margin-top:20px;'>
            <table border=0 cellspacing="0" background-color:'#f4fcff'>
			<form action='#' method="POST" name="datos">
            <thead>
				<tr id="titulo_tablas">
					<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">						
						<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:390px;'>
						Datos del Informe Policial
						</div>
						<div class='champi_derecha'>&nbsp;</div>
					</td>
				</tr>
			 </thead>
             	<tr>
                	<td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                    	<table class="tabla_detalles" style="border: 1px solid #3F7BCC;" border="0" width="450" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="300" style='background-color:#f4fcff'>
		                    <input type="hidden" value="<?print($fila['CIF']);?>" name="cif">		                    
	    	                <tr height="45">
								<td width="40">&nbsp;</td>
	                         	<td align="left"><br><label class="label_formulario">Código Establecimiento:</label></td>
	                         	<td align="left"><br><input class="input_formulario" type="text"  name="cod_establecimiento" value="<?print($fila['Cod_Establecimiento']);?>" size="15"></td>	                          					                         
							</tr>
	                     	<tr height="45">
	                     		<td width="40">&nbsp;</td>
	            	            <td align="left"><br><label class="label_formulario">Tipo Registro Datos Albergue:</label></td>
	                        	<td align="left"><br><input class="input_formulario" type="text"  name="tipo_registro_alb" value="<?print($fila['Tipo_registro_Alb']);?>" size="2"></td>	                        	
	                        </tr>	                      	
	                     	<tr height="45">
								<td width="40">&nbsp;</td>
		                    	<td align="left"><br><label class="label_formulario">Tipo Registro Datos de Viajeros:</label></td>
	                        	<td align="left"><br><input class="input_formulario" type="text"  name="tipo_registro_datos" value="<?print($fila['Tipo_registro_Datos']);?>" size="2"></td>                         		                        	
	                     	</tr>	                      	
	                     	<tr>
	                        	<td colspan='3' align='center'>                       
	                           		<br><br><a href='#' onclick='submit();'> <img src='../imagenes/botones-texto/modificar.jpg' border="0"></a>
	                          	</td>
							</tr>			
						</table>
            		</td>
				</tr>
            </form>
			</table>				
	</div>
</td>
<td valign="top">
	<div id='datos' style='margin-top:20px;margin-left:100;' >
		<table class="tabla_detalles" border=0 cellspacing="0" background-color:'#f4fcff'>
			<form action='Informes_Policiales.php' method="POST" name="datos">
            <thead>
				<tr id="titulo_tablas">
					<td colspan="2" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">					
						<div class='champi_izquierda'>&nbsp;</div>
						<div class='champi_centro'  style='width:190px;'>
						Generar Informe
						</div>
						<div class='champi_derecha'>&nbsp;</div>												
					</td>
				</tr>
			 </thead>
			 <tr>
				<td cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;">
                    <table style="border: 1px solid #3F7BCC;" border="0" width="250" cellspacing="0" cellpadding="0" style="padding:0px 0px 0px 0px;" height="80" style='background-color:#f4fcff'>
	                	<?$anio=Date("Y");$mes=Date("n");$dia=Date("j");			
						$fecha_hoy=date("j-n-Y", mktime(0, 0, 0,$mes,$dia, $anio));?>
						<tr height="45">							
		                    <td align="center"><br><label class="label_formulario">Fecha:</label></td>								  
							<td align="left"><br><label class="label_formulario"><?echo $fecha_hoy?></label></td>                 	
	                    </tr>
						<tr>							
	                        <td align='center'colspan="2" >                       
	                           	<br><br><a href="?pag=Informes_Policiales.php" onClick='submit();'> <img src='../imagenes/botones-texto/generar.jpg' border="0"></a>	                           	                           								
	                        </td>
						</tr>
						<tr height="45">
							<td width="40">&nbsp;</td>
		                    <td width="40">&nbsp;</td>		                    	
	                    </tr>                    
					</table>
            	</td>
			</tr>
            </form>
		</table>			 
	</div>
</td>
</tr>
</table>

<?php
}//cierra if de permisos de informe policial
else{
  
  echo "<div class='error'>NO TIENE PERMISO PARA ACCEDER A ESTA PÁGINA</div>";  
  
}
?>

