<?php
include("../../../../../altamira/connection.php");
//Le decimos a PHP que vamos a devolver objetos JSON
header('Content-type: application/json');

//Importamos la libreria de ActiveRecord
require_once 'php-activerecord/ActiveRecord.php';

//Configuracion de ActiveRecord
ActiveRecord\Config::initialize(function($cfg)
{
	//Ruta de una carpeta que contiene los modelos de la BD (tablas)
	$cfg->set_model_directory('models');
	//Creamos la conexion
	$cfg->set_connections(array(
		'development' => 'mysql://'.$userName.':'.$password.'localhost/schoolwa_Altamira'));
});


//Peticion para devolver las secciones 
if(isset($_REQUEST['getSeccion'])){
	//Hacemos la consulta
	$grado = Grado::find_by_sql('SELECT DISTINCT * FROM secciones');
	//Devolvemos los registros de la BD en un formato JSON
	echo json_encode(datosJSON($seccion));
}

//Peticion para devolver los grados por secciones
if(isset($_REQUEST['getGrado'])){
	$seccion = $_REQUEST['seccion'];
	$grado = Grado::find_by_sql("SELECT * FROM grados WHERE seccion = '$seccion' ");
	echo json_encode(datosJSON($grado));
}

//Peticion para devolver los grupos por grados
if(isset($_REQUEST['getGrupo'])){
	$seccion = $_REQUEST['seccion'];
	$grado = $_REQUEST['grado'];
	$grupo = Grupo::find_by_sql("SELECT seccion,nombre FROM grupos WHERE grado = '$grado' AND seccion = '$seccion' ");
	echo json_encode(datosJSON($grupo));
}

//Peticion para devolver los alumnos por grados, grupos, sección 
if(isset($_REQUEST['getAlumno'])){
	$seccion = $_REQUEST['seccion'];
	$grado = $_REQUEST['grado'];
	$grupo = $_REQUEST['grupo'];
	$alumno = Alumno::find_by_sql("SELECT alumno, CONCAT(apellido_paterno,' ',apellido_materno,', ',nombre) AS nombre FROM alumnos WHERE grado = '$grado' AND seccion = '$seccion' AND grupo =  '$grupo'");
	echo json_encode(datosJSON($alumno));
}

//funcion que convierte objetos regresados por la BD a JSON
function datosJSON($data, $options = null) {
	$out = "[";
	foreach( $data as $row) { 
		if ($options != null)
			$out .= $row->to_json($options);
		else 
			$out .= $row->to_json();
		$out .= ",";
	}
	$out = rtrim($out, ',');
	$out .= "]";
	return $out;
}


?>