<?PHP session_start();?>

<style type="text/css">
form.cmxform fieldset {
	margin-bottom: 10px;
		
}
form.cmxform legend {
  padding: 0 2px;
  font-weight: bold;
}
form.cmxform label {
  display: inline-block;
  line-height: 1.8;
  vertical-align: top;
}


form.cmxform em {
  font-weight: bold;
  font-style: normal;
  color: #f00;
}
form.cmxform label {
  width: 120px; /* Width of labels */
}

</style>

<form name="ok" action="?pag=listado.php" method="POST">
</form>
<form name="cmxform" action="?pag=login.php" method="POST">
<?
if($enviar){
		
        $pas = $_POST['contra'];
        @ $db = mysql_pconnect("localhost","root","");
        if (!$db){
            echo "Error: no se puede acceder a la base de datos";
            exit;
        }
        mysql_select_db("agenda");
        $query = "select * from contra where password='" . $pas . "'";
        $res = mysql_query($query);
        $num = mysql_num_rows($res);
        $err = 0;
        if($num == 1){
           
                
                $_SESSION['password'] = $pas;
            	?>
                 <script>
		
			
			
			document.getElementById("ok").submit();
		
			</script>
                <?
        }
        else
            $err = 1;
        mysql_close($db);
}

?>
         <div style="width:250px; height:150px">
                <fieldset >
<legend>CONTRASE&#209;A</legend>
<br />
 <table  border="0">
                        <TR>
        
                        <TD colspan="1">
                            <?php
                                if ($enviar){
                                    if($err == 1)
                                        echo "contraseña son incorrectos";
                                }
                                
                            ?>
                        </TD>
                    </TR>
                	<tr>
                    	
                        <td><input type="password" name="contra" size="20" /></td>
                    </tr>
                    <tr>
                    	<td colspan="2" align="center"><input type="submit" value="enviar" class="boton"  name="enviar" /></td>
                    </tr>
                </table>
                <br />
</fieldset>
 <div>
</form>