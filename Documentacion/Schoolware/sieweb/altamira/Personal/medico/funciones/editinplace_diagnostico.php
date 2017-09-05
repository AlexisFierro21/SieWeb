<?php
include("../../../connection.php");
//////////////////////
$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

if (!$db->set_charset("utf8")) {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $db->error);
    exit();
} else {
    printf("Conjunto de caracteres actual: %s\n", $db->character_set_name());
}

if (isset($_POST) && count($_POST)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		
		$query=$db->query("UPDATE medico_diagnostico SET ".$_POST["campo"]."='".$_POST["valor"]."' WHERE id='".intval($_POST["id"])."' LIMIT 1");
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
		$query=$db->query("SELECT * FROM medico_diagnostico ORDER BY id ASC");
		$datos=array();
		while ($usuarios=$query->fetch_array())
		{
			$datos[]=array(	"id"=>$usuarios["id"],
							"diagnostico"=>$usuarios["diagnostico"],
							"descripcion"=>$usuarios["descripcion"]
			);
		}
		echo json_encode($datos);
	}
}

?>