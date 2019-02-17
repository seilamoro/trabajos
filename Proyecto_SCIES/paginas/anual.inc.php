<th colspan="3" align=left>
	<table height="40" >													
		<thead>
			<tr>					
				<th class="titulo_fechas">Año</th>
				<th class="opcion_fechas">
									<SELECT name="anio_inicio_v">
										<option></option>
										<?php
										$sql_anio="SELECT DISTINCT ( SUBSTRING( Fecha, 1, 4)) as anio FROM lectura";
										$result = mysql_query($sql_anio);
										for ($i=0;$i<mysql_num_rows($result);$i++){
											$fila_anio = mysql_fetch_array($result);
											if($_SESSION['anio']==$fila_anio['anio']){
												echo "<option value='".$fila_anio['anio']."' selected>".$fila_anio['anio']."</option>";
											}else{
												echo "<option value='".$fila_anio['anio']."'>".$fila_anio['anio']."</option>";
											}
										}
										?>
									</SELECT>
								</th>
			</tr>						
		</thead>
	</table>
</th>