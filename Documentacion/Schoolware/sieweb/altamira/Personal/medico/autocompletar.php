<?php
include('funciones.php');

	$consulta = "SELECT * FROM medicamentos ";
 	$result = mysql_query($consulta) or die(mysql_error());
    $medicamentos = mysql_fetch_array($result);
    echo json_encode($medicamentos);
?>

