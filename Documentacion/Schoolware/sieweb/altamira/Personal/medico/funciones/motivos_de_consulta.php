<?php
include("../../../connection.php");
//////////////////////
$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

if (isset($_POST) && count($_POST)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		$query=$db->query("UPDATE medico_motivos_de_consulta SET ".$_POST["campo"]."='".$_POST["valor"]."' WHERE id='".intval($_POST["id"])."' LIMIT 1");
		if ($query) echo "<span class='ok'>Valores modificados correctamente.</span>";
		else echo "<span class='ko'>".$db->error."</span>";
	}
}

if (isset($_GET) && count($_GET)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		$query=$db->query("SELECT * FROM medico_motivos_de_consulta ORDER BY id ASC");
		$datos=array();
		while ($usuarios=$query->fetch_array())
		{
			$datos[]=array(	"id"=>$usuarios["id"],
							"motivo"=>$usuarios["motivo"],
							"descripcion"=>$usuarios["descripcion"]
			);
		}
		echo json_encode($datos);
	}
}

?>