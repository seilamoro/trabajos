<?PHP
	if (!isset($_SESSION['aspecto'])) {//si aun no esta establecido el aspecto de la pagina.

	?>
	<link rel="stylesheet" type="text/css" href="css/hoja_principal_azul.css">
	<link rel="stylesheet" type="text/css" href="css/estilos_tablas.css">
	<link rel="stylesheet" type="text/css" href="css/habitaciones.css">
	<link rel="stylesheet" type="text/css" href="css/hoja_formularios.css">
	<link rel="stylesheet" type="text/css" href="css/estructura_alb_per.css">
	<?
	$_SESSION['aspecto'] = "azul";
	}
	else {
		if (isset($theme)) {
			$_SESSION['aspecto'] = $theme;
		}

		?>
		<link rel="stylesheet" type="text/css" href="css/hoja_principal_<?echo $_SESSION['aspecto']?>.css">
		<link rel="stylesheet" type="text/css" href="css/estilos_tablas.css">
		<link rel="stylesheet" type="text/css" href="css/habitaciones.css">
		<link rel="stylesheet" type="text/css" href="css/hoja_formularios.css">
		<link rel="stylesheet" type="text/css" href="css/estructura_alb_per.css">
		<link rel="stylesheet" type="text/css" href="css/habitacionesColores.css">

		<?
	}
	?>