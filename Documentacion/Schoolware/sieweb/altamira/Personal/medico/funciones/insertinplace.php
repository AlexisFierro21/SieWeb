<?php
include("../../../connection.php");
//////////////////////
$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

//////////////////////////////////////////////////////
/* Aqu� empezamos llenando un nuevo registro, 		*/
/* previamente con el registro en CSS y javascript	*/
/* v�lidamos que fueran llenados correctamente		*/ 
//////////////////////////////////////////////////////

if (isset($_POST) && count($_POST)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		
		$query=$db->query("INSERT INTO medicamentos (nombre, descripcion) VALUES ('".$_POST["nombre"]."','".$_POST["descripcion"]."')");
		if ($query) echo "<span class='ok'>Valores modificados correctamente.</span>";
		else echo "<span class='ko'>".$db->error."</span>";
	}
}
?>