<th colspan=3 align=left>
	<table>
		<th  class="titulo_fechas" style="text-align:left" >Fecha Inicio</th>
		<th colspan=2>
			<table height="40" >													
				<thead>
					<tr>										
						<th class="opcion_fechas">
							<SELECT name="dia_inicio_v" id="dia_inicio_v">
								<?php
									if($_SESSION['dia'] == ''){
										$_SESSION['dia'] = date('d');
									}
									for($r=1;$r<=31;$r++){
										if($r<10){
											$r1="0".$r;
										}else{
											$r1=$r;
										}
										
										if($_SESSION['dia']==$rl){
											echo "<option value='".$r1."' selected>".$r1."</option>";
										}else{
											echo "<option value='".$r1."'>".$r1."</option>";
										}
									}
								?>
							</SELECT>											
							<SELECT name="mes_inicio_v" id="mes_inicio_v" onchange="asignaDias('dia_inicio_v','mes_inicio_v','anio_inicio_v');">
								<?php 
									if($_SESSION['mes'] == ''){
										$_SESSION['mes']=date('m');
									}
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
							<SELECT name="anio_inicio_v" id="anio_inicio_v" onchange="asignaDias('dia_inicio_v','mes_inicio_v','anio_inicio_v');">
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
						<th  class="titulo_fechas" style="text-align:left" >Hora</th>
						<th class="opcion_fechas">
							<SELECT name="hora_inicio_v" >
								<?php
									for($i=0;$i<24;$i++){
										if($i<10){
											$il="0".$i;
										}else{
											$il=$i;
										}
										if($_SESSION['hora']==$il){
											echo "<option value='".$il."' selected>".$il."</option>";
										}else{
											echo "<option value='".$il."'>".$il."</option>";
										}
									}
								?>
							</SELECT>
							:
							<SELECT name="minuto_inicio_v" >
								<?php
									for($i=0;$i<60;$i++){
										if($i<10){
											$il="0".$i;
										}else{
											$il=$i;
										}
										if($_SESSION['minuto']==$il){
											echo "<option value='".$il."' selected>".$il."</option>";
										}else{
											echo "<option value='".$il."'>".$il."</option>";
										}
									}
								?>
							</SELECT>	
						</th>
					</tr>						
				</thead>
			</table>
		</th>
	</tr>
	<tr>
		<th class="titulo_fechas" style="text-align:left">Fecha Fin</th>	
		<th>
			<table border="0" height="40">			
				<thead>
					<tr>	
						<th class="opcion_fechas">
							<SELECT name="dia_fin_v" id="dia_fin_v">
								<?php
									if($_SESSION['dia_fin'] == ''){
										$_SESSION['dia_fin']=date('d');
									}
									for($i=1;$i<=31;$i++){
										if($i<10){
											$il="0".$i;
										}else{
											$il=$i;
										}
										if($_SESSION['dia_fin']==$il){
											echo "<option value='".$il."' selected>".$il."</option>";
										}else{
											echo "<option value='".$il."'>".$il."</option>";
										}
									}
								?>
							</SELECT>								
							<SELECT name="mes_fin_v" id="mes_fin_v" onchange="asignaDias('dia_fin_v','mes_fin_v','anio_fin_v')">
								<?php 
									if($_SESSION['mes_fin'] == ''){
										$_SESSION['mes_fin']=date('m');
									}
									if($_SESSION['mes_fin'] == '01'){
										echo '<option value="01" selected>Enero</option>';
									}else{
										echo '<option value="01" >Enero</option>';
									}
									if($_SESSION['mes_fin'] == '02'){
										echo '<option value="02" selected>Febrero</option>';
									}else{
										echo '<option value="02" >Febrero</option>';
									}
									if($_SESSION['mes_fin'] == '03'){
										echo '<option value="03" selected>Marzo</option>';
									}else{
										echo '<option value="03" >Marzo</option>';
									}
									if($_SESSION['mes_fin'] == '04'){
										echo '<option value="04" selected>Abril</option>';
									}else{
										echo '<option value="04" >Abril</option>';
									}
									if($_SESSION['mes_fin'] == '05'){
										echo '<option value="05" selected>Mayo</option>';
									}else{
										echo '<option value="05" >Mayo</option>';
									}
									if($_SESSION['mes_fin'] == '06'){
										echo '<option value="06" selected>Junio</option>';
									}else{
										echo '<option value="06" >Junio</option>';
									}
									if($_SESSION['mes_fin'] == '07'){
										echo '<option value="07" selected>Julio</option>';
									}else{
										echo '<option value="07" >Julio</option>';
									}
									if($_SESSION['mes_fin'] == '08'){
										echo '<option value="08" selected>Agosto</option>';
									}else{
										echo '<option value="08" >Agosto</option>';
									}
									if($_SESSION['mes_fin'] == '09'){
										echo '<option value="09" selected>Septiembre</option>';
									}else{
										echo '<option value="09" >Septiembre</option>';
									}
									if($_SESSION['mes_fin'] == '10'){
										echo '<option value="10" selected>Octubre</option>';
									}else{
										echo '<option value="10" >Octubre</option>';
									}
									if($_SESSION['mes_fin'] == '11'){
										echo '<option value="11" selected>Noviembre</option>';
									}else{
										echo '<option value="11" >Noviembre</option>';
									}
									if($_SESSION['mes_fin'] == '12'){
										echo '<option value="12" selected>Diciembre</option>';
									}else{
										echo '<option value="12" >Diciembre</option>';
									}
								?>
							</SELECT>										
							<SELECT name="anio_fin_v" id="anio_fin_v" onchange="asignaDias('dia_fin_v','mes_fin_v','anio_fin_v');">
								<option></option>
								<?php
									$sql_anio="SELECT DISTINCT ( SUBSTRING( Fecha, 1, 4)) as anio FROM lectura";
									$result = mysql_query($sql_anio);
									for ($i=0;$i<mysql_num_rows($result);$i++){
										$fila_anio = mysql_fetch_array($result);
										if($_SESSION['anio_fin']==$fila_anio['anio']){
											echo "<option value='".$fila_anio['anio']."' selected>".$fila_anio['anio']."</option>";
										}else{
											echo "<option value='".$fila_anio['anio']."'>".$fila_anio['anio']."</option>";
										}
									}
								?>
							</SELECT>
						</th>
						<th  class="titulo_fechas" style="text-align:left" >Hora</th>
						<th class="opcion_fechas">
							<SELECT name="hora_fin_v" >
								<?php
									for($i=0;$i<24;$i++){
										if($i<10){
											$il="0".$i;
										}else{
											$il=$i;
										}
										if($_SESSION['hora_fin']==$il){
											echo "<option value='".$il."' selected>".$il."</option>";
										}else{
											echo "<option value='".$il."'>".$il."</option>";
										}
									}
								?>
							</SELECT>
							:
							<SELECT name="minuto_fin_v" >
								<?php
									for($i=0;$i<60;$i++){
										if($i<10){
											$il="0".$i;
										}else{
											$il=$i;
										}
										if($_SESSION['minuto_fin']==$il){
											echo "<option value='".$il."' selected>".$il."</option>";
										}else{
											echo "<option value='".$il."'>".$il."</option>";
										}
									}
								?>
							</SELECT>			
						</th>
					</tr>						
				</thead>
			</table>		
		</th>
	</table>
</th>