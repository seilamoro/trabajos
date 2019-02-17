<HTML>
<HEAD>
    <TITLE>Seleccione una fecha</TITLE>
	<META NAME="Author" CONTENT="Javier Mateos Sabel">
    <SCRIPT>
		// Esta funcion recibe una fecha y llama a la función correspondiente de la pagina que habrió esta
        function seleccionar(fecha){
          window.opener.cambiarFecha(fecha);
          window.close();
        }
    </SCRIPT>
    <?php
        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        //saco la fecha actual y la pongo en variables
		$dia_act = date("d");
        $mes_act = date("m");
        $mes_act = $mes_act / 1;
        $ayo_act = date("Y");
        //si se ha seleccionado otra fecha se recoge y se cambian las variables
        if($envia){
            $mes_ver = $_GET['mes'];
            $ayo_ver = $_GET['ayo'];
            if($ayo_ver < $ayo_act)
                $ayo_ver = $ayo_act;
            if($ayo_ver > 2020)
                $ayo_ver = 2020;
            if($mes_ver == 0){
                $mes_ver = 12;
                $ayo_ver = $ayo_ver - 1;
            }
            else if($mes_ver == 13){
                $mes_ver = 1;
                $ayo_ver = $ayo_ver + 1;
            }
        }
        else{
            $mes_ver = date("m");
            $mes_ver = $mes_ver / 1;
            $ayo_ver = date("Y");
        }
    ?>
    <style type="text/css">
	<!--
	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
	}
	
	.mi_tabla {
    	border-top-width: thin;
    	border-right-width: thin;
    	border-bottom-width: thin;
    	border-left-width: thin;
    	border-top-style: solid;
    	border-right-style: solid;
    	border-bottom-style: none;
    	border-left-style: solid;
    	border-top-color: #000000;
    	border-left-color: #000000;
    	border-right-color: #000000;
    }
	.seleccionado{
        color: blue;
    }
	</style>

</HEAD>
<BODY">
    <TABLE border="0" width="400" height="300" cellspacing="0" cellpadding="0">
        <TR><TD height="50">
            <TABLE border="0" width="100%" height="100%" cellspacing="0" cellpadding="0" class="mi_tabla" background="../imagenes/calendario.png">
                <TR align="center">
                    <TD width="50%">
						<? // llama a la misma pagina enviando un mes menos al que se está viendo y el mismo año ?>
                        <INPUT type="image" src="../imagenes/flechas/flecha_atras.gif" onclick="location.href('calendario.php?mes=<?php echo ($mes_ver - 1) ?>&ayo=<?php echo $ayo_ver; ?>&envia=true');" alt="Mes atras" align="absmiddle">
						<? // llama a la misma pagina enviando el mes que se seleccione y el mismo año ?>
                        <SELECT name="s_meses" onChange="location.href('calendario.php?mes=' + this.value + '&ayo=<?php echo $ayo_ver; ?>&envia=true');">
                            <?php
                                for($i = 1; $i <= 12; $i++){
                                  if($i == $mes_ver)
                                    echo "<OPTION selected class='seleccionado' value=" . $i . ">" . $meses[$i] . "</OPTION>";
                                  else
                                    echo "<OPTION value=" . $i . ">" . $meses[$i] . "</OPTION>";
                                }
                            ?>
                        </SELECT>
						<? // llama a la misma pagina enviando un mes más al que se está viendo y el mismo año ?>
                        <INPUT type="image" src="../imagenes/flechas/flecha_alante.gif" onclick="location.href('calendario.php?mes=<?php echo ($mes_ver + 1) ?>&ayo=<?php echo $ayo_ver; ?>&envia=true');" alt="Mes adelante" align="absmiddle">
                    </TD>
                    <TD>
						<? // llama a la misma pagina enviando un año menos al que se está viendo y el mismo mes ?>
                        <INPUT type="image" src="../imagenes/flechas/flecha_atras.gif" onclick="location.href('calendario.php?mes=<?php echo $mes_ver ?>&ayo=<?php echo $ayo_ver - 1; ?>&envia=true');" alt="Año atras" align="absmiddle">
						<? // llama a la misma pagina enviando el año que se seleccione y el mismo mes ?>
                        <SELECT name="s_ayo" onChange="location.href('calendario.php?mes=<?php echo $mes_ver; ?>&ayo=' + this.value + '&envia=true');">
                            <?php
                                for($i = $ayo_act; $i <= 2020; $i++){
                                  if($i == $ayo_ver)
                                    echo "<OPTION selected class='seleccionado' value=" . $i . ">" . $i . "</OPTION>";
                                  else
                                    echo "<OPTION value=" . $i . ">" . $i . "</OPTION>";
                                }
                            ?>
                        </SELECT>
						<? // llama a la misma pagina enviando un año mas al que se está viendo y el mismo mes ?>
                        <INPUT type="image" src="../imagenes/flechas/flecha_alante.gif" onclick="location.href('calendario.php?mes=<?php echo $mes_ver ?>&ayo=<?php echo $ayo_ver + 1; ?>&envia=true');" alt="Año adelante" align="absmiddle">
                    </TD>
                </TR>
            </TABLE>
        </TD></TR>
        <TR><TD>
            <TABLE border="1" width="100%" height="100%" bordercolor="black" cellspacing="0" cellpadding="0">
                <TR align="center" bgcolor="#69730E" height="40">
				<? // la cabezera de los dias de la semana ?>
                    <TD><B>L</B></TD><TD><B>M</B></TD><TD><B>X</B></TD>
                    <TD><B>J</B></TD><TD><B>V</B></TD><TD><B>S</B></TD><TD><B>D</B></TD>
                </TR>
                <TR>
                <?php            //para saber cuantos dias tiene el mes
                    $dias_mes = date("t", mktime("0","0","0",$mes_ver,1,$ayo_ver));
                    $dia_sem = date("w", mktime("0","0","0",$mes_ver,1,$ayo_ver));
					// deja tantas celdas en blanco como el dia en que empieze el mes
                    if($dia_sem == 0){ // si es 0, el mes empieza en domingo y se dejan 6 celdas
                        for($i = 1; $i < 7; $i++){
                            echo "<TD>&nbsp</TD>";
                        }
                    }
                    else{ // si no se dejan tantas celdas como sean necesarios
                        for($i = 1; $i < $dia_sem; $i++){
                            echo "<TD>&nbsp</TD>";
                        }
                    }
                    for($i = 1; $i <= $dias_mes; $i++){ // recorro todos los dias del mes
                        $dia_sem = date("w", mktime("0","0","0",$mes_ver,$i,$ayo_ver)); // recogo el dia de la semana que es
                        $fecha_ver = date("d/m/Y", mktime("0","0","0",$mes_ver,$i,$ayo_ver)); // creo la fecha de ese dia 
                        echo "<TD align='center' bgcolor='#F0FAD3'>";		//si la fecha que estoy poniendo es menor que la fecha actual
                        if(($ayo_ver < $ayo_act) || ($ayo_ver == $ayo_act && $mes_ver < $mes_act) || ($ayo_ver == $ayo_act && $mes_ver == $mes_act && $i < $dia_act)){
                            echo "<B>" . $i . "</B></TD>"; // se pone normal
                        }
                        else{	// si no se pone con vinculo, ya que se puede elegir
                            echo "<B><a href=javascript:seleccionar('" . $fecha_ver . "')>" . $i . "</a></B></TD>"; 
                        }

                        if($dia_sem == 0){ // si el dia de la semana es domingo, se cierra la fila y se abre la siguiente
                            echo "</TR><TR>";
                        }
                    }
                    if($dia_sem != 0){ // si la ultima semana del mes no acaba en domingo se añaden tantas celdas en blanco como sean necesarias
                        for($i = $dia_sem; $i < 7; $i++){
                            echo "<TD>&nbsp</TD>";
                        }
                    }
                ?>
                </TR>
            </TABLE>
        </TD></TR>
    </TABLE>
</BODY>
</HTML>
