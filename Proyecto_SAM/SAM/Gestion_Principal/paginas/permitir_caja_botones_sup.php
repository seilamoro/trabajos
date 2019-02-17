
<?php

	
	  	if ($_GET['tipo_distribucion'] == 'T') {
		    $estamos = 'taquillas';
			$atras = 'habitas';
		}
		else{
			if ($_GET['tipo_distribucion'] == 'H'){
				$estamos = 'habitas';
				$atras = 'taquillas';
			}
			else{
				if ($_SESSION['gdh']['gdh_dis']['tipo'] == 'T'){
					$estamos = 'taquillas';
					$atras = 'habitas';
				}
				else {
					$estamos = 'habitas';
					$atras = 'taquillas';
				}
			}
		}
	

					if(isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']==true)
						{
				?>
						<a href=".?pag=alberguistas.php" class="botones_superiores" id="boton_a" alt="Gestión de Alberguistas" title="Gestión de Alberguistas"><div id="boton_alberguista" class="boton_superior" style="background-image:url('../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_alberguista.gif');background-repeat:no-repeat;">
						</div></a>
				<?php
						}else{
							echo "<div class='boton_superior_in'></div>";
						}
					if(isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']==true)
						{
				?>
						<a href=".?pag=grupos.php" class="botones_superiores" id="boton_g" alt="Gestión de Grupos" title="Gestión de Grupos"><div class="boton_superior"  style="background-image:url('../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_grupos.gif');background-repeat:no-repeat;">
						</div></a>
				<?php
						}else{
							echo "<div class='boton_superior_in'></div>";
						}
					if(isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']==true)
						{
				?>
						<a href=".?pag=peregrinos.php" class="botones_superiores" id="boton_p" alt="Gestión de Peregrinos" title="Gestión de Peregrinos"><div class="boton_superior" style="background-image:url('../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_peregrino.gif');background-repeat:no-repeat;">
						</div></a>
				<?php
						}else{
							echo "<div class='boton_superior_in'></div>";
						}
					if(isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']==true)
						{
				?>
						<a href=".?pag=reservas.php" class="botones_superiores" id="boton_s" alt="Gestión de Reservas" title="Gestión de Reservas"><div class="boton_superior" style="background-image:url('../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_reservas.gif');background-repeat:no-repeat;">
						</div></a>
				<?php
						}else{
							echo "<div class='boton_superior_in'></div>";
						}
						
					if((isset($_SESSION['permisoHabitaciones']) && $_SESSION['permisoHabitaciones']==true) && (isset($_SESSION['permisoTaquillas']) && $_SESSION['permisoTaquillas']==true))
						{
				?>
						<a href="?pag=gdh.php&tipo_distribucion=<?php if($estamos=='taquillas'){echo 'T';}else{echo 'H';}?>" class="botones_superiores" id="boton_h" alt="Gestión de Habitaciones" title="Gestión de Habitaciones">
<div class="boton_superior" style="z-index:3;position:relative;background-image:url('../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_<?php echo $estamos;?>.gif');background-repeat:no-repeat;"> 
</div>
</a>
						<div class='boton_dentro'><a href='#' onMouseOver='sacar(this)' onMouseOut='meter(this)' onClick="cambiar_pag('<?php if($estamos=='taquillas'){echo 'H';}else{echo 'T';}?>')">
						<IMG alt='<?php if($estamos=='taquillas'){echo 'Ver Habitaciones';}else{echo 'Ver Taquillas';}?>' src='../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_atras_<?php echo $atras;?>.gif' border=0>
						</A>
						</div>

						<SCRIPT>
						function sacar(cosa){
							cosa.className='boton_sacar';
						}

						function meter(cosa){
							cosa.className='boton_meter';
						}

						function cambiar_pag(valor){
								/*formulario.gdh_dis.value = valor;
								formulario.submit();*/
								window.location.href='?pag=gdh.php&tipo_distribucion='+valor;
						}

						</SCRIPT>
				<?php
						}else{
							if (isset($_SESSION['permisoHabitaciones']) && $_SESSION['permisoHabitaciones']==true){
				?>

						<a href="?pag=gdh.php&tipo_distribucion=H" class="botones_superiores" id="boton_h" alt="Gestión de Habitaciones" title="Gestión de Habitaciones">
<div class="boton_superior" style="z-index:3;position:relative;background-image:url('../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_habitas.gif');background-repeat:no-repeat;"> 
</div>
</a>
				<?php
							}else{
								if (isset($_SESSION['permisoTaquillas']) && $_SESSION['permisoTaquillas']==true){
				?>
						<a href="?pag=gdh.php&tipo_distribucion=T" class="botones_superiores" id="boton_h" alt="Gestión de Habitaciones" title="Gestión de Habitaciones">
<div class="boton_superior" style="z-index:3;position:relative;background-image:url('../imagenes/skins-<?echo $_SESSION['aspecto']?>/boton_taquillas.gif');background-repeat:no-repeat;"> 
</div>
</a>
				<?php
								}else{
									echo "<div class='boton_superior_in'></div>";
								}
							}
						}


				?>