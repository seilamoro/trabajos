<?php
					if(isset($_SESSION['permisoAlberguista']) && $_SESSION['permisoAlberguista']==true)
					//if($_SESSION['logged'] == "true")
						{
				?>
						<span class="subtitulo_boton" id="subtitulo_alberguista">Alberguistas</span>
				<?php
						}
					if(isset($_SESSION['permisoGrupos']) && $_SESSION['permisoGrupos']==true)
					//if($_SESSION['logged'] == "true")
						{
				?>
						<span class="subtitulo_boton" id="subtitulo_grupos">Grupos</span>
				<?php
						}
					if(isset($_SESSION['permisoPeregrino']) && $_SESSION['permisoPeregrino']==true)
					//if($_SESSION['logged'] == "true")
						{
				?>
						<span class="subtitulo_boton" id="subtitulo_peregrino">Peregrinos</span>
				<?php
						}
					if(isset($_SESSION['permisoReservas']) && $_SESSION['permisoReservas']==true)
					//if($_SESSION['logged'] == "true")
						{
				?>
						<span class="subtitulo_boton" id="subtitulo_reservas">Reservas</span>
				<?php
						}
					if(isset($_SESSION['permisoHabitaciones']) && $_SESSION['permisoHabitaciones']==true)
					//if($_SESSION['logged'] == "true")
						{
				?>
						<span class="subtitulo_boton" id="subtitulo_habitaciones">Habitaciones</span>
				<?php
						}
				?>