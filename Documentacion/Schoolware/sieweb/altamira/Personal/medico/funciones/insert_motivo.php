<?php
include("../../../connection.php");
//////////////////////
$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

//////////////////////////////////////////////////////
/* Aquí empezamos llenando un nuevo registro, 		*/
/* previamente con el registro en CSS y javascript	*/
/* válidamos que fueran llenados correctamente		*/ 
//////////////////////////////////////////////////////

if (!$db->set_charset("utf8")) {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $db->error);
    exit();
} else {
  $db->character_set("utf8");
}


if (isset($_POST) && count($_POST)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		$query=$db->query("INSERT INTO medico_motivos_de_consulta (motivo, descripcion) VALUES ('".encode_utf8($_POST["nombre"])."','".encode_utf8($_POST["descripcion"])."')");
		if ($query) echo "<span class='ok'>Valores modificados correctamente.</span>";
		else echo "<span class='ko'>".$db->error."</span>";
	}
}

?>