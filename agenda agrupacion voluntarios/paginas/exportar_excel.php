<?php

$template_1 = "template_cabecera.inc";
$template_2 = "template_pie.inc";

// Se abre y extrae la cabecera del XML
if ($gestor = fopen($template_1, "r")){
	$header = fread($gestor, filesize($template_1));
	fclose($gestor);
}

// Se genera el contenido agregando 10 filas
$rows = "";
for ($i = 1; $i <= 10; $i++) {
	$rows .= "<Row>\n";
	$rows .= "<Cell><Data ss:Type=\"String\">C1 - Row $i</Data></Cell>";
	$rows .= "<Cell><Data ss:Type=\"String\">C2 - Row $i</Data></Cell>";
	$rows .= "<Cell><Data ss:Type=\"String\">C3 - Row $i</Data></Cell>";
	$rows .= "</Row>";
}

// Se abre y extrae el pie del XML
if ($gestor = fopen($template_2, "r")){
	$footer = fread($gestor, filesize($template_2));
	fclose($gestor);
}

// Se juntan las partes resultantes
$content = $header . $rows . $footer;

// Se envia el archivo al navegador
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"excel_generated.xls\"" );
print $content;

?>