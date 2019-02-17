<?
//Revisa las longitudes de los nombres demasiado largos para que aparezcan	
			//adecuadamente en el listado.
			$nom = $fila['Nombre_Cl'];
			$nom_partido = "";
			$array_nom = split(" ", $nom);
			for($b=0; $b<count($array_nom); $b++) {
				$array_nom_i = split("-", $array_nom[$b]);
				if(count($array_nom_i) > 1) {
					$guiones = 'si';
				} else {
					$guiones = 'no';
				}
				for($j=0; $j<count($array_nom_i); $j++) {
					
					if($guiones == 'no') {
						if(strlen($array_nom_i[$j]) > 10) {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], 0, 10)."-<br>".substr($array_nom_i[$j], 10, 10);
						} else {
							$nom_partido = $nom_partido. " ".$array_nom_i[$j];
						} 
					} else {
						if(strlen($array_nom_i[$j]) > 10) {
							$nom_partido = $nom_partido." ".substr($array_nom_i[$j], 0, 10)."-<br>".substr($array_nom_i[$j], 10, 10);
						} else {
							if($nom_partido == "") {
								$nom_partido = $nom_partido. " ".$array_nom_i[$j];
							} else {
								$nom_partido = $nom_partido. "-".$array_nom_i[$j];
							}
						}
					}
				}
			} 
			echo "<td width='24%' align='left' class='discrete_text'><font color='gray'>".$nom_partido."</font></td>";