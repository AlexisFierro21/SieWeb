<?php
include('modelo.php');

$colegio=$_POST['colegio'];
//$this->conectar($colegio);
$data=['name'=>'alexis','status'=>true];
header('Content-type: application/json');
echo json_encode( $data );
?>