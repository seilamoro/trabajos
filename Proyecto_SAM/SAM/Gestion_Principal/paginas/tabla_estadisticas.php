<?php
	session_start();
?>

	<script language="JavaScript">
	//esta funcion es para controlar que estadistica es la que se elige, gr contiene un string que indica el tipo de estadística y num un entero que si vale 1 indica que se debe mostrar el informe y si conriene 2 indica que se debe mostrar la gráfica
		function cambio_est(gr,num)
		{
			elegir_grafica.tipo.value=gr;
			elegir_grafica.num_oculto.value=num;
			elegir_grafica.submit();
		}
	
	</script>
	<form action="?pag=estadisticas.php" method="post" name="elegir_grafica">					
		<input type='hidden' name='num_oculto' value="">
		<table width="700px" border="0" align="left"  cellpadding="0" cellspacing="0">
				<tr >
				<!-- Esto es la cabecera de la tabla donde va el título de lo que contiene la tabla que se muestra debajo -->
					<td colspan="6" style="padding:0px;">
							<div class="champi_izquierda"></div>
							<div class="champi_centro">
							<?php
							if( (isset($_POST['tipo'])) && ($_POST['tipo']=='jcyl') )
							{
								$_SESSION['inf_jcyl']=1;
							}
							if( (isset($_SESSION['inf_jcyl'])) && ($_SESSION['inf_jcyl']==1) )
							{
								?>
								<div class="titulo" style="width:690px;text-align:center;">Estadísticas</div>
								<?php
							}
							else
							{
								?>
								<div class="titulo" style="width:635px;text-align:center;">Estadísticas</div>
								<?php
							}
							?>
							</div>
							<div class="champi_derecha"></div>
					<td>
				</tr >
				<tr style="padding:0px;">
				
<!--Aqui se elige el gráfico que se va mostrar en la página de estadísticas el cual cambiará según sea el botón del gráfico que se elija -->
					<?php
					//se controla que si se muestra el informe de la junta de Castilla Y León, el ancho del título y de los botones tiene que ser mayor
					if( (isset($_SESSION['inf_jcyl'])) && ($_SESSION['inf_jcyl']==1) )
					{
						?>
						<td><input type="button" name="edades" value="Edades" class="boton" style="width:121px;" onclick="cambio_est('edades',1)" ></td>
						<td><input type="button" name="ccaa" value="CC.AA." class="boton" style="width:121px;" onclick="cambio_est('ccaa',1)" ></td>
						<td><input type="button" name="t_alb" value="Tipo de Alberguista" class="boton" style="width:130px;" onclick="cambio_est('t_alb',1)"></td>
						<td><input type="button" name="pais" value="Países" class="boton" style="width:121px;" onclick="cambio_est('pais',1)"></td>
						<td><input type="button" name="prov" value="Provincias" class="boton" style="width:121px;" onclick="cambio_est('prov',1)"></td>
						<td><input type="button" name="jcyl" value="JCYL" class="boton" style="width:121px;" onclick="cambio_est('jcyl',1)"></td>
						<?php
					}
					else
					{
						?>
						<td><input type="button" name="edades" value="Edades" class="boton" style="width:110px;" onclick="cambio_est('edades',1)" ></td>
						<td><input type="button" name="ccaa" value="CC.AA." class="boton" style="width:110px;" onclick="cambio_est('ccaa',1)" ></td>
						<td><input type="button" name="t_alb" value="Tipo de Alberguista" class="boton" style="width:130px;" onclick="cambio_est('t_alb',1)"></td>
						<td><input type="button" name="pais" value="Países" class="boton" style="width:110px;" onclick="cambio_est('pais',1)"></td>
						<td><input type="button" name="prov" value="Provincias" class="boton" style="width:110px;" onclick="cambio_est('prov',1)"></td>
						<td><input type="button" name="jcyl" value="JCYL" class="boton" style="width:110px;" onclick="cambio_est('jcyl',1)"></td>
						<?php
					}
					?>
				</tr>
				
				<tr>
				
<!-- mediante este input oculto se hace saber cual es la gráfica que se ha elegido -->

					<td><input type="hidden" name="tipo" value=""></td>
				</tr>
<?php	
		
if( (isset($_POST['ver_grafica'])) && ($_POST['ver_grafica']=='prov') )
{
		echo'<tr>';
		echo'<td colspan="7" align="center">';
			include('paginas/informe_prov.php');
			echo'</td>
			</tr>';
			//$_SESSION['inf_graf']['prov']=1;
}
else if( (isset($_POST['ver_grafica'])) && ($_POST['ver_grafica']=='pais'))
{
		echo'<tr>';
		echo'<td colspan="7" align="center">';
			include('paginas/informe_pais.php');
			echo'</td>
			</tr>';
			//$_SESSION['inf_graf']['pais']=1;
}
else if( (isset($_POST['ver_grafica'])) && ($_POST['ver_grafica']=='ccaa'))
{
		echo'<tr>';
		echo'<td colspan="7" align="center">';
			include('paginas/informe_ccaa.php');
			echo'</td>
			</tr>';
			//$_SESSION['inf_graf']['ccaa']=1;
}
else if( (isset($_POST['ver_grafica'])) && ($_POST['ver_grafica']=='edades'))
{
		echo'<tr>';
		echo'<td colspan="7" align="center">';
			include('paginas/informe_edades.php');
			echo'</td>
			</tr>';
			//$_SESSION['inf_graf']['edades']=1;
}
else
{
// en este caso sería la estadística de grupos de edades
			
			if($_SESSION['tipo_estadisticas']=="edades")
			{				
				if( (isset($_POST['num_oculto'])) && ($_POST['num_oculto']==1) )
					$_SESSION['inf_graf']['edades']=1;
				if($_SESSION['inf_graf']['edades']==2)
				{
					echo'<tr><td colspan="7" align="center">';
					if( (!isset($_SESSION['grafico'])) || ($_SESSION['grafico']==1) )
						{
							//por sectores
							echo '<img src="paginas/grafico_edades_sectores.php" ></td></tr>';
						}
					else if($_SESSION['grafico'] == 2)
						{
							//por barras
							echo '<img src="paginas/grafico_edades_barras.php" ></td></tr>';
						}
					else
						{	
							//en poligonos
							echo '<img src="paginas/grafico_edades_poligono.php" ></td></tr>';
						}
					//$_SESSION['inf_graf']['edades']=1;
				}
				else
				{
					echo'<tr>';
					echo'<td colspan="7" align="center">';
						include('paginas/informe_edades.php');
					echo'</td>
					</tr>';
					//$_SESSION['inf_graf']['edades']=2;
				}
			}
			
// en este caso sería la estadística de modo de peregrinación
			
			if($_SESSION['tipo_estadisticas']=="m_pere")
			{
				// en este caso la elección del tipo de gráfico se realiza dentro de la página de gráfica_mp.php
				echo'<tr>';
					echo'<td colspan="7" align="center"> <img src="paginas/grafica_mp.php" ></td>';
				echo'</tr>';
			}
			
// en este caso sería la estadística de tipo de alberguista
			
			if($_SESSION['tipo_estadisticas']=="t_alb")
			{
				echo'<tr>';
					echo'<td colspan="7" align="center">';
					
					if( (!isset($_SESSION['grafico'])) || ($_SESSION['grafico']==1) || ($_SESSION['grafico']==3) )
						{
							//por sectores
							echo '<img src="paginas/grafico_tipo_alberguista_sectores.php" ></td>';
						}
					else
						{
							//por barras
							echo '<img src="paginas/grafico_tipo_alberguista_barras.php"></td>';
						}
				echo'</tr>';
			}
			
// en este caso sería la estadística de país de procedencia
			
			if($_SESSION['tipo_estadisticas']=="pais")
			{
				if( (isset($_POST['num_oculto'])) && ($_POST['num_oculto']==1) )
					$_SESSION['inf_graf']['pais']=1;
				if($_SESSION['inf_graf']['pais']==2)
				{
				// este caso es como el de modo de peregrinación donde la elección del gráfico se realiza dentro de la página de graficos_provincias.php
				echo'<tr>';
					echo'<td colspan="7" align="center">'; 
					echo '<img src="paginas/graficos_paises.php" border="0"></td>';
					//$_SESSION['inf_graf']['pais']=1;
				echo'</tr>';
				}
				else
				{
					echo'<tr>';
					echo'<td colspan="7" align="center">';
						include('paginas/informe_pais.php');
					echo'</td>
					</tr>';
					//$_SESSION['inf_graf']['pais']=2;
				}
			}
			
// en este caso sería la estadística de provincia de procedencia
			
			if($_SESSION['tipo_estadisticas']=="prov")
			{
				if( (isset($_POST['num_oculto'])) && ($_POST['num_oculto']==1) )
					$_SESSION['inf_graf']['prov']=1;
				if($_SESSION['inf_graf']['prov']==2)
				{
				// este caso es como el de modo de peregrinación donde la elección del gráfico se realiza dentro de la página de graficos_provincias.php
				echo'<tr>';
					echo'<td colspan="7" align="center">'; 
					echo '<img src="paginas/graficos_provincias.php" border="0"></td>';
					//$_SESSION['inf_graf']['prov']=1;
				echo'</tr>';
				}
				else
				{
					echo'<tr>';
					echo'<td colspan="7" align="center">';
						include('paginas/informe_prov.php');
					echo'</td>
					</tr>';
					//$_SESSION['inf_graf']['prov']=2;
				}
			}
			
			if($_SESSION['tipo_estadisticas']=="ccaa")
			{
				if( (isset($_POST['num_oculto'])) && ($_POST['num_oculto']==1) )
					$_SESSION['inf_graf']['ccaa']=1;
				if($_SESSION['inf_graf']['ccaa']==2)
				{
				// este caso es como el de modo de peregrinación donde la elección del gráfico se realiza dentro de la página de graficos_provincias.php
				echo'<tr>';
					echo'<td colspan="7" align="center">'; 
					echo '<img src="paginas/graficos_ccaa.php" border="0"></td>';
					//$_SESSION['inf_graf']['prov']=1;
				echo'</tr>';
				}
				else
				{
					echo'<tr>';
					echo'<td colspan="7" align="center">';
						include('paginas/informe_ccaa.php');
					echo'</td>
					</tr>';
					//$_SESSION['inf_graf']['prov']=2;
				}
			}
// en este caso sería la estadística que la junta de castilla y león va a recibir
			
			if($_SESSION['tipo_estadisticas']=="jcyl")
			{
				/* en este caso lo qeu se hace es un include de la pagina de estadística_junta.php ya que la estadística de la junta es una tabla
				en vez de un gráfico*/
				
				echo'<tr>';
					echo'<td colspan="7" align="center">';
						include('paginas/estadistica_junta.php');
					echo'</td>
					</tr>';
			}
	}
	if($_SESSION['inf_graf'][$_SESSION['tipo_estadisticas']] ==1)
	{
				?>
				
				<tr>
					<td colspan='7' align='center' height='60'>
						<a href="#" onclick="window.open('paginas/imprimir_estadistica.php?pulsado_imprimir=yes') "><img src="./../imagenes/botones-texto/imprimir.jpg" border=0 alt="Imprimir Estadística"></a>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php
						if($_SESSION['tipo_cliente']==1)
						{
							if($_SESSION['tipo_estadisticas']=="prov")
							{
								?>
								<img src='../imagenes/botones-texto/grafica.jpg' style='cursor:pointer;' alt='Generar Gráfica' title='Generar Gráfica' onclick="cambio_est('prov',2)">
								<?php
							}
							elseif($_SESSION['tipo_estadisticas']=="pais")
							{
								?>
								<img src='../imagenes/botones-texto/grafica.jpg' style='cursor:pointer;' alt='Generar Gráfica' title='Generar Gráfica' onclick="cambio_est('pais',2)">
								<?php
							}
							elseif($_SESSION['tipo_estadisticas']=="ccaa")
							{
								?>
								<img src='../imagenes/botones-texto/grafica.jpg' style='cursor:pointer;' alt='Generar Gráfica' title='Generar Gráfica' onclick="cambio_est('ccaa',2)">
								<?php
							}
							else
							{
								?>
								<img src='../imagenes/botones-texto/grafica.jpg' style='cursor:pointer;' alt='Generar Gráfica' title='Generar Gráfica' onclick="cambio_est('edades',2)">
								<?php
							}
						}
						?>
					</td>
				<?php
	}
	if( (isset($_SESSION['inf_jcyl'])) && ($_SESSION['inf_jcyl']==1) )
	{
		?>
				
				<tr>
					<td colspan='7' align='center' height='60'>
						<a href="#" onclick="window.open('paginas/imprimir_estadistica.php?pulsado_imprimir=yes') "><img src="./../imagenes/botones-texto/imprimir.jpg" border=0 alt="Imprimir Estadística">
						</a>
					</td>
				</tr>
		<?php
	}
		?>
		</table>
	</form>





