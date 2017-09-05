<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alumnos sin preceptoría</title>
</head>

<body>
<?php
include('../config.php');
include('../functions.php');
include('adjunto.php');
require('../phpMailer/class.phpmailer.php');

echo "<center><img  src='../im/logo.jpg'></img></center>";
echo '<h1> <center>Alumnos sin preceptorias realizadas</center></h1>';
echo "<br>";

$cuerpo="<center><img  src='https://ecolmenares.net/sieweb/altamira/im/logo.jpg'></img></center>";
$cuerpo.= 
' 	<html> 
	<head> <title>Respaldo de la base de datos '.$name.'.</title> </head> 
	<body>
	<br>
	<p>&nbsp;</p>
	<h1> <center>Alumnos sin preceptorias realizadas</center></h1>
	';

$fechai = new DateTime();
$fechai->modify('first day of this month');
echo 'Rango de fechas del: '.$fechai->format('d/m/Y').' '; // imprime por ejemplo: 01/01/2014
$fec_repo_i=' '.$fechai->format('d/m/Y').' ';
$cuerpo.='Rango de fechas del: '.$fechai->format('d/m/Y').' ';
$fechaf = new DateTime();
echo 'Al: '.$fechaf->format('d/m/Y').'<br>'; // imprime por ejemplo: 31/01/2014
$fec_repo_f=' '.$fechaf->format('d/m/Y').' ';
$cuerpo.='Al: '.$fechaf->format('d/m/Y').'<br>'; 

//////// Empezamos con el total de alumnos sin preceptorias

$tsprecep=mysql_query("SELECT DISTINCT
                      	alumnos.alumno, 
					  	CONCAT (alumnos.nombre,' ', 
					  	alumnos.apellido_paterno,' ', 
					  	alumnos.apellido_materno) AS nombre,
					  	alumnos.seccion,
					 	alumnos.grado, 
                      	alumnos.grupo, 
					  	alumnos.preceptor,
						CONCAT (personal.nombre,' ',personal.apellido_paterno,' ',personal.apellido_materno) AS preceptor_nombre
					  FROM
         
						alumnos INNER JOIN
						personal ON alumnos.preceptor = personal.empleado 
					WHERE     
						(alumnos.activo = 'A') 
						AND (NOT EXISTS
                          		(SELECT     
								 	preceptoria.alumno
                            	FROM          
									preceptoria 
                            	WHERE      
									(fecha BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()) 
									 AND alumnos.alumno = alumno ))
								ORDER BY 
									alumnos.seccion,
									alumnos.grado, 
									alumnos.grupo, 
									alumnos.apellido_paterno, 
									alumnos.apellido_materno, 
									alumnos.nombre;
					",$link) 
or die 
					("SELECT DISTINCT
                      	alumnos.alumno, 
					  	CONCAT (alumnos.nombre,' ', 
					  	alumnos.apellido_paterno,' ', 
					  	alumnos.apellido_materno) AS nombre,
					  	alumnos.seccion,
					 	alumnos.grado, 
                      	alumnos.grupo, 
					  	alumnos.preceptor,
						CONCAT (personal.nombre,' ',personal.apellido_paterno,' ',personal.apellido_materno) AS preceptor_nombre
					  FROM
         
						alumnos INNER JOIN
						personal ON alumnos.preceptor = personal.empleado 
					WHERE     
						(alumnos.activo = 'A') 
						AND (NOT EXISTS
                          		(SELECT     
								 	preceptoria.alumno
                            	FROM          
									preceptoria 
                            	WHERE      
									(fecha BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()) 
									 AND alumnos.alumno = alumno ))
								ORDER BY 
									alumnos.seccion,
									alumnos.grado, 
									alumnos.grupo, 
									alumnos.apellido_paterno, 
									alumnos.apellido_materno, 
									alumnos.nombre;
".mysql_error());					

$total_sp=mysql_num_rows($tsprecep);

//// Terminamos con el total de alumnos sin preceptorias

$cuerpo.="Total sin preceptorias: ".$total_sp."<br>";
echo "Total sin preceptorias: ".$total_sp."
<table width='900' border='1'>
  <tr>
  	<th scope='col'>Alumno</th>
    <th scope='col'>Nombre</th>
	<th scope='col'>Sección</th>
    <th scope='col'>Grado</th>
	<th scope='col'>Grupo</th>
	<th scope='col'>Preceptor</th>
  </tr>";
$cuerpo.="
<table width='900' border='1'>
  <tr>
  	<th scope='col'>Alumno</th>
    <th scope='col'>Nombre</th>
	<th scope='col'>Sección</th>
    <th scope='col'>Grado</th>
	<th scope='col'>Grupo</th>
	<th scope='col'>Preceptor</th>
  </tr>";
  
///// Seleccionamos los diferentes tipos de secciones que existen en el grupo 

$x="";
$num_results="";
for ($x=1; $x<=3;$x++)
{	

$sprecep=mysql_query("SELECT DISTINCT
                      	alumnos.alumno, 
					  	CONCAT (alumnos.nombre,' ', 
					  	alumnos.apellido_paterno,' ', 
					  	alumnos.apellido_materno) AS nombre,
					  	alumnos.seccion,
					 	alumnos.grado, 
                      	alumnos.grupo, 
					  	alumnos.preceptor,
						CONCAT (personal.nombre,' ',personal.apellido_paterno,' ',personal.apellido_materno) AS preceptor_nombre
					  FROM
         
						alumnos INNER JOIN
						personal ON alumnos.preceptor = personal.empleado 
					WHERE     
						(alumnos.activo = 'A') 
						AND alumnos.seccion = $x
						AND (NOT EXISTS
                          		(SELECT     
								 	preceptoria.alumno
                            	FROM          
									preceptoria 
                            	WHERE      
									(fecha BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()) 
									 AND alumnos.alumno = alumno ))
								ORDER BY 
									alumnos.seccion,
									alumnos.grado, 
									alumnos.grupo, 
									alumnos.apellido_paterno, 
									alumnos.apellido_materno, 
									alumnos.nombre;
					",$link) 
or die 
					("SELECT DISTINCT
                      	alumnos.alumno, 
					  	CONCAT (alumnos.nombre,' ', 
					  	alumnos.apellido_paterno,' ', 
					  	alumnos.apellido_materno) AS nombre,
					  	alumnos.seccion,
					 	alumnos.grado, 
                      	alumnos.grupo, 
					  	alumnos.preceptor,
						CONCAT (personal.nombre,' ',personal.apellido_paterno,' ',personal.apellido_materno) AS preceptor_nombre
					  FROM
         
						alumnos INNER JOIN
						personal ON alumnos.preceptor = personal.empleado 
					WHERE     
						(alumnos.activo = 'A') 
						AND alumnos.seccion = $x
						AND (NOT EXISTS
                          		(SELECT     
								 	preceptoria.alumno
                            	FROM          
									preceptoria 
                            	WHERE      
									(fecha BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()) 
									 AND alumnos.alumno = alumno ))
								ORDER BY 
									alumnos.seccion,
									alumnos.grado, 
									alumnos.grupo, 
									alumnos.apellido_paterno, 
									alumnos.apellido_materno, 
									alumnos.nombre;
".mysql_error());					


///// Empieza el contador de los subtotales
	$num_seccion=mysql_num_rows($sprecep);
while($row = mysql_fetch_array($sprecep))
                    {
                      echo '<tr>
					  <td>&nbsp;'.$row['alumno'].'&nbsp;</td>
					  <td>&nbsp;'.utf8_encode($row['nombre']).'&nbsp;</td>
					  ';
					  $cuerpo.='<tr>
					  <td>&nbsp;'.$row['alumno'].'&nbsp;</td>
					  <td>&nbsp;'.utf8_encode($row['nombre']).'&nbsp;</td>
					  ';
						
						switch ($row['seccion']){
							case "1":
							echo '<td>&nbsp;Primaria&nbsp;</td>';
							$cuerpo.='<td>&nbsp;Primaria&nbsp;</td>';
							break;
							case "2":
							echo '<td>&nbsp;Secundaria&nbsp;</td>';
							$cuerpo.='<td>&nbsp;Secundaria&nbsp;</td>';
							break;
							case "3":
							echo '<td>&nbsp;Preparatoria&nbsp;</td>';
							$cuerpo.='<td>&nbsp;Preparatoria&nbsp;</td>';
							break;
						}
								 
					  echo'
					  <td>&nbsp;'.$row['grado'].'&nbsp;</td>
					  <td>&nbsp;'.$row['grupo'].'&nbsp;</td>
					  <td>&nbsp;'.utf8_encode($row['preceptor_nombre']).'&nbsp;</td>';
					  $cuerpo.='
					  <td>&nbsp;'.$row['grado'].'&nbsp;</td>
					  <td>&nbsp;'.$row['grupo'].'&nbsp;</td>
					  <td>&nbsp;'.utf8_encode($row['preceptor_nombre']).'&nbsp;</td>';
					  }				
					  
	echo '<tr><td>Sin preceptorias:&nbsp;</td><td>&nbsp;'.$num_seccion.'&nbsp;</td></tr>';
	$cuerpo.='<tr><td>Sin preceptorias:&nbsp;</td><td>&nbsp;'.$num_seccion.'&nbsp;</td></tr>';
	$num_results=$num_results+$num_seccion;
}
	echo '<tr><td>Total sin preceptorias:&nbsp;</td><td>&nbsp;'.$num_results.'&nbsp;</td></tr>';
	$cuerpo.='<tr><td>Total sin preceptorias:&nbsp;</td><td>&nbsp;'.$num_results.'&nbsp;</td></tr>';
	$cuerpo.='	</body> 
	</html>';
	
////Hacemos el XLS

file_put_contents('AlumnosSinPreceptoria.xls',utf8_decode($cuerpo));

//// PHP Mailer

$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;

$mailUser="entrevistas@e-altamira.edu.mx";
$mailFrom="entrevistas@e-altamira.edu.mx";
$mailBCC="entrevistas@e-altamira.edu.mx";
//$mailBCC="emmanuel.contreras@colmenares.org.mx";
$mailBCC="emmanuelcs@live.com";
//$mailBCC="schavez@colmenares.org.mx";
$mailPass="2a3l0t1a1m4!";
$mailSender="Entrevistas Colegio Altamira - Moctezuma A.C.";

		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->PluginDir="../phpMailer/";
		$mail->CharSet="UTF-8";
		$mail->Mailer="smtp";
		$mail->Host=$hostSMTP;
		$mail->SMTPAuth=true;
		$mail->SMTPSecure=$smtpPrefix;
		$mail->Port=$smtpPort;
		$mail->Username=$mailUser;
		$mail->Password=$mailPass;
		$mail->From=$mailFrom;
		$mail->FromName=$mailSender;
		$mail->IsHTML(true);
		$mail->AddAttachment('AlumnosSinPreceptoria.xls', 'AlumnosSinPreceptoria.xls');
				
		//$mail->AddAddress($mail_);	

		$mail->AddBCC($mailBCC);
		$mail->Subject="Reporte de Preceptorias";
		$mail->Body=$cuerpo;
				
		if(!$mail->Send())
			echo"Mailer Error: ".$mail->ErrorInfo;
		/*echo "<script language='JavaScript'>alert('Los correos del alumno $alumno han sido enviados.');</script>";*/
		$mail->ClearAddresses();

////Borramos el archivo XLS que creamos


$dir='AlumnosSinPreceptoria.xls'; //puedes usar dobles comillas si quieres
if(file_exists($dir))
{
if(unlink($dir))
print "<br>El archivo fue borrado<br>";
}
else
print "<br>Este archivo no existe<br>";


?>
</body>
</html>