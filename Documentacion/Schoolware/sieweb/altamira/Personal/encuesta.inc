<?php
include('../connection.php');
$link = mysql_connect($server, $userName, $password) or die('No se pudo conectar: '.mysql_error());
mysql_select_db($DB)or die("No se pudo seleccionar DB");
$opc = $_REQUEST['opc'];
$alumno = $_REQUEST['alumno'];
$today = date("Y-m-d");
$sqlt= "select distinct(c.alumno),(select concat(apellido_paterno,' ',apellido_materno,', ',nombre) from alumnos where alumno=c.alumno) from preceptoria_acuerdos c where st=1";
$rst = mysql_query($sqlt);
$lista = "<input type=button value=\"En espera\" onClick=\"rfrsh())\";><br>";
while ($row=mysql_fetch_array($rst)){
	$alm = $row[0];
	$nom = $row[1];
	$lista .= "<input type=button value=\"$nom\" width=200 onClick=\"Action('fr',$alm)\";><br>";
}
?>