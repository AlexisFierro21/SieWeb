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

if (isset($_POST) && count($_POST)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
			
		$query=$db->query("INSERT INTO medico_administrador (id_personal, nombre) VALUES ('".$_POST["id_personal"]."','".$_POST["nombre"]."')");
		if ($query) echo "<span class='ok'>Valores modificados correctamente.</span>";
		else echo "<span class='ko'>".$db->error."</span>";
	}
}
?>