<?php
include('../../config.php');
mysql_query("SET NAMES 'utf8'");

$medicamento= array();
$cantidad= array();
$dosis= array();
$via_de_administracion= array();
$frecuencia= array();


$fecha_y_hora_de_consulta = date("Y-m-d H:i:s");
$tipo_de_paciente = $_REQUEST['tipo_de_paciente'];
$paciente = $_REQUEST['paciente'];
$motivo_consulta = $_REQUEST['motivo_consulta'];
$exploracion_fisica = $_REQUEST['exploracion_fisica'];
$diagnostico = $_REQUEST['diagnostico'];
$indicaciones = $_REQUEST['indicaciones'];

foreach($_REQUEST['medicamento'] as $key=>$value)
    $medicamento[]= $value;
	
foreach($_REQUEST['cantidad'] as $key=>$value)
    $cantidad[]= $value;
 
foreach($_REQUEST['dosis'] as $key=>$value)
    $dosis[]= $value;
	
foreach($_REQUEST['via_de_administracion'] as $key=>$value)
    $via_de_administracion[]= $value;
	
foreach($_REQUEST['frecuencia'] as $key=>$value)
    $frecuencia[]= $value;	


$sql = "INSERT INTO expediente_medico 
									(fecha_y_hora_de_consulta, 
									 tipo_de_paciente, 
									 paciente, 
									 motivo_de_la_consulta, 
									 exploracion_fisica, 
									 diagnostico,
									 indicaciones) 
								VALUES 
									( 
									 '$fecha_y_hora_de_consulta', 
									 '$tipo_de_paciente', 
									 '$paciente', 
									 '$motivo_de_la_consulta', 
									 '$exploracion_fisica', 
									 '$diagnostico',
									 '$indicaciones'
									)";
   
$result = mysql_query($sql)or die("Existe un error al procesar la información ".mysql_error());

for($i=0; $i<count($medicamento); $i++) 
{
   mysql_query("INSERT INTO medicamento_recetado
													(
														medicamento,
														cantidad,
														dosis,
														via_de_administracion,
														frecuencia) 
											VALUES(
												   		'{$medicamento[$i]}', 
														'{$cantidad[$i]}', 
														'{$dosis[$i]}', 
														'{$via_de_administracion[$i]}', 
														'{$frecuencia[$i]}'
														)",$link)or die(mysql_error());
   
}


?>