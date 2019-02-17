<th colspan="3" align=left>
	<table height="40" >													
		<thead>
			<tr>					
				<th class="titulo_fechas" >Mes</th>
				<th class="opcion_fechas">
									<SELECT name="mes_inicio_v"  onchange=mes(this.form.mes_inicio_v.value);>
										<?php 
											if($_SESSION['mes'] == '01'){
												echo '<option value="01" selected>Enero</option>';
											}else{
												echo '<option value="01" >Enero</option>';
											}
											if($_SESSION['mes'] == '02'){
												echo '<option value="02" selected>Febrero</option>';
											}else{
												echo '<option value="02" >Febrero</option>';
											}
											if($_SESSION['mes'] == '03'){
												echo '<option value="03" selected>Marzo</option>';
											}else{
												echo '<option value="03" >Marzo</option>';
											}
											if($_SESSION['mes'] == '04'){
												echo '<option value="04" selected>Abril</option>';
											}else{
												echo '<option value="04" >Abril</option>';
											}
											if($_SESSION['mes'] == '05'){
												echo '<option value="05" selected>Mayo</option>';
											}else{
												echo '<option value="05" >Mayo</option>';
											}
											if($_SESSION['mes'] == '06'){
												echo '<option value="06" selected>Junio</option>';
											}else{
												echo '<option value="06" >Junio</option>';
											}
											if($_SESSION['mes'] == '07'){
												echo '<option value="07" selected>Julio</option>';
											}else{
												echo '<option value="07" >Julio</option>';
											}
											if($_SESSION['mes'] == '08'){
												echo '<option value="08" selected>Agosto</option>';
											}else{
												echo '<option value="08" >Agosto</option>';
											}
											if($_SESSION['mes'] == '09'){
												echo '<option value="09" selected>Septiembre</option>';
											}else{
												echo '<option value="09" >Septiembre</option>';
											}
											if($_SESSION['mes'] == '10'){
												echo '<option value="10" selected>Octubre</option>';
											}else{
												echo '<option value="10" >Octubre</option>';
											}
											if($_SESSION['mes'] == '11'){
												echo '<option value="11" selected>Noviembre</option>';
											}else{
												echo '<option value="11" >Noviembre</option>';
											}
											if($_SESSION['mes'] == '12'){
												echo '<option value="12" selected>Diciembre</option>';
											}else{
												echo '<option value="12" >Diciembre</option>';
											}
                								?>
									</SELECT>
								
									<SELECT name="anio_inicio_v" onchange=ayo(this.form.anio_inicio_v.value);>
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
		