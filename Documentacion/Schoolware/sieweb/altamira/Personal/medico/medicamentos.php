<?php
include('funciones.php');

$term = trim(strip_tags($_GET['term'])); 
$a_json = array();
$a_json_row = array();
if ($data = $mysqli->query("SELECT * FROM medicamentos WHERE LIKE '%$term%'")) {
	while($row = mysqli_fetch_array($data)) {
		$nombre = htmlentities(stripslashes($row['nombre ']));
		$id = htmlentities(stripslashes($row['id']));
		$a_json_row["id"] = $id;
		$a_json_row["nombre"] =' '.$nombre.' ';
		array_push($a_json, $a_json_row);
	}
}
// jQuery wants JSON data
echo json_encode($a_json);
?>