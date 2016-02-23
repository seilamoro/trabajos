<form name="salir" action="?pag=listado.php" method="POST">
</form>
<center>
<?php
    session_start();
    if (isset($_SESSION['password'])){
        $_SESSION = array();
        session_destroy();
		?>
        <script>
		document.getElementById("salir").submit();
		</script>
            <?
    }
    
?>
</center>
