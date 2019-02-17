<?
	 function table_exists($table_name,$base_datos)
        {
            $Table = mysql_query("show tables like '" .$table_name . "'",$base_datos);
            if(mysql_fetch_row($Table) == false)
            return(false);
            else
            return(true);
         }
	function isNaN( $var ) {
		return !ereg ("^[-]?[0-9]+([\.][0-9]+)?$", $var);
	}
	function establecer_fecha($fecha_i, $pernocta){
		$split = split("-", $fecha_i);
		if($split[0]%4==0){
			$bisiesto = true;
		}else{
			$bisiesto = false;
		}		
		$año = intval($split[0],10);
		$mes = intval($split[1],10);
		$dia = intval($split[2])+intval($pernocta);
		while($dia > 31 || $dia > 28 && $mes == 2 ||$dia > 29 && $mes == 2 && $bisiesto=true){
			switch($mes){
				case 1:
				case 3:
				case 5:
				case 7:
				case 8:
				case 10:
				case 12:		
					if($dia > 31){
						$dia=$dia - 31;
						$mes++;
						if($mes>12){
							$mes = $mes - 12;
							$año++;
						}		
					}
				case 4:
				case 6:
				case 9:
				case 11:
					if($dia > 30){
						$dia = $dia - 30;
						$mes++;
					}
					break;
				case 2:
					if($bisiesto){
						if($dia >29){
							$dia = $dia - 29;
							$mes++;
						}
					}else{
						if($dia >28){
							$dia-=28;
							$mes++;
						}
					}
			}
		}
		$dia = strval($dia);
		if(strlen($dia)==1){
			$dia = "0".$dia;
		}
		if(strlen($mes)==1){
			$mes = "0".$mes;
		}	
		$fecha_salida = $año."-".$mes."-".$dia;
		return $fecha_salida;

	}
	function resta_fecha($fecha_inicio , $fecha_fin){	
		$f_inicio = split("-",$fecha_inicio);
		$f_fin = split("-",$fecha_fin);		
		$date1 = mktime(0,0,0,intval($f_inicio[1]), intval($f_inicio[2]), intval($f_inicio[0]));
		$date2 = mktime(0,0,0,intval($f_fin[1]), intval($f_fin[2]), intval($f_fin[0]));
		$resultado = round(($date2 - $date1) / 86400);
		return $resultado;
	}
?>