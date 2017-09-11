<?php
require_once '../models/modelo.php';
require_once 'functions.php';
session_start();
$obj= new modelo();
$colegio=$_POST['colegio'];
$obj->setConect($colegio);
$usuario=$_POST['user'];
$password=$_POST['pass'];
$letra=substr($usuario,0,1);
$pass=f_encripta($usuario,$password,"E");
switch($letra){
	case "P":  $tbl="personal";  $key = "empleado"; $_SESSION['tipo']="personal"; break;
	case "A":  $tbl="alumnos";   $key = "alumno";   $_SESSION['tipo']="alumno"; break;
	case "F":  $tbl="familias";  $key = "familia";  $_SESSION['tipo']="familia"; break;
	case "E":  $tbl="exalumnos"; $key = "exalumno"; $_SESSION['tipo']="exalumno"; break;
}
$user=intval(substr($usuario,1));
echo($user);
$res=$obj->iniciar_Sesion($user,$pass,$tbl,$key);
$data=['name'=>$colegio,'status'=>true];
header('Content-type: application/json');
echo json_encode($data);
?>