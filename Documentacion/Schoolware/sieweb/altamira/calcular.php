<?php
include("config.php");
$fecha_ini = $_POST['fecha_ini'];
$fecha_fin = $_POST['fecha_fin'];

$result = mysql_query("SELECT 
								count(*) AS total 
							FROM 
								preceptoria_acuerdos, alumnos
							WHERE
								alumnos.alumno = preceptoria_acuerdos.alumno
                                AND
								fec between '$fecha_ini' and '$fecha_fin'
                                AND
                                alumnos.activo = 'A'
								AND
								concat(preceptoria_acuerdos.padre, preceptoria_acuerdos.madre) <>'00'
								", $link);

while($row = mysql_fetch_array($result)) {
  echo $row['total'];
}

?>