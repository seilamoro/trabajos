<?	//_______________Crear array leyendo del fichero____________________________________________________________________________
	$var=file("../Gestion_Interna/paginas/habitaciones.txt"); //esto hace un fopen y un fclose implícitos y deja en un array cada línea del fichero	
	
		$pag_mayor=1;
		if(COUNT($var) >0)//si el fichero no tiene nada no lo leo para que no me cree un primer elemento del array vacío
		{
			for($i=0;$i < COUNT($var) ; $i++)
			{
				list($page,$order,$id_corte)=split(",",$var[$i]);
				$pag=$page +0; //le sumo cero para convertir el string en cadena y así poder compararlo con >
				if($pag > $pag_mayor )
				{
					$pag_mayor=$pag;
				}
				
				$_SESSION['pag_hab'][$i]['pagina']=$page;
				$_SESSION['pag_hab'][$i]['orden']=$order;
				$_SESSION['pag_hab'][$i]['Id_Tipo_Hab']=$id_corte;			
			}
			if(!isset($_SESSION['total_paginas']))
			{
				$_SESSION['total_paginas']=$pag_mayor;
			}
		}
		
	//__________________________ hasta aquí la parte de crear el array leyendo del fichero__________________________//
	
	//_______________________para calcular la página actual que se muestra__________________________________________//
?>