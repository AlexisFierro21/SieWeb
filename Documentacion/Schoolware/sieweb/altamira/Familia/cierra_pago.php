<?php
include('../connection.php');
/////Fecha de última modificación 11-02-2016
$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

$query=$db->query("SET NAMES 'utf8'");
$query=$db->query("SET CHARACTER SET utf8 ");
					$Reference3D=$_REQUEST['Reference3D'];					
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		$query=$db->query("SELECT
									*
								FROM
									payworks
								WHERE
									Reference3D = '".$Reference3D."'");
		$result_ = "";			 
			
				while ($row = $query->fetch_assoc()) {
					if($row['Resultado_Payw'] == 'A'){
						$result_.= "<span class='ok'>Pago realizado satisfactoriamente.</span>";
					}elseif($row['Resultado_Payw'] == 'R'){
						$result_.= "<span class='ok'>Pago rechazado por su banco, consulte detalles.</span>";
					}elseif($row['Resultado_Payw'] == 'D'){
						$result_.= "<span class='ok'>Pago rechazado por su banco, consulte detalles.</span>";
					}elseif($row['Resultado_Payw'] == 'T'){
						$result_.= "<span class='ok'>Existe un problema con la conexión, cierre esta ventana e intente nuevamente.</span>";
					}else{
						$result_.= "<span class='ok'>La conexión se a perdido, cierre esta ventana e intente nuevamente.</span>";
					}
			}	
	}
	echo $result_;
?>