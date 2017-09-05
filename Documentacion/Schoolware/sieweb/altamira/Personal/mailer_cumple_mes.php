<?
include('../config.php');
//include('../functions.php');
require ('../../repositorio/phpMailer/PHPMailerAutoload.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Colegio Altamira Moctezuma</title>
<style> 
		*{
            margin:5;
            padding:5;
        }
		
table, table td, table tr {
	padding:0px;
	border-spacing: 0px;
}

table {
	border:1px black solid;
	border-radius:5px;
	min-width:400px;
	font-family: Helvetica,Arial;
}

table td {
	padding:6px;
}

       
        #content{
            background-color:#fff;
            width:750px;
            padding:40px;
            margin:0 auto;
            border-left:30px solid #dddddd;
            border-right:1px solid #ddd;
            -moz-box-shadow:0px 0px 16px #aaa;
        }
        .head{
            font-family:Helvetica,Arial;
            text-transform:uppercase;
            font-weight:bold;
            font-size:12px;
            font-style:normal;
            letter-spacing:3px;
            color:#888;
            border-bottom:3px solid #f0f0f0;
            padding-bottom:10px;
            margin-bottom:10px;
        }
        .head a{
            color:#1D81B6;
            text-decoration:none;
            float:right;
            text-shadow:1px 1px 1px #888;
        }
        .head a:hover{
            color:#f0f0f0;
        }
        #content h1{
            font-family:Helvetica,Arial;
            color:#1D81B6;
            font-weight:normal;
            font-style:normal;
            font-size:56px;
            text-shadow:1px 1px 1px #aaa;
        }
        #content h2{
            font-family:Helvetica,Arial;
            font-size:34px;
            font-style:normal;
            background-color:#f0f0f0;
            margin:40px 0px 30px -40px;
            padding:0px 40px;
            clear:both;
            float:left;
            width:100%;
            color:#aaa;
            text-shadow:1px 1px 1px #fff;
        }
			table th{
	font-weight: bold;
	color:#fff;
	background-color: #888;
	border-bottom:1px #000 solid;
	text-align:center;
	border-radius:5px;
			}
			table td.a1{
	font-weight: bold;
	color:#fff;
	background-color: #888;
	border-bottom:0px #000 solid;
	text-align:left;			
	border:1px black solid;
	border-radius:5px;
			}

				}
			table td.a2{
	font-weight: bold;
	color:#000;
	background-color: #e5e5e5;
	border-bottom:0px #000 solid;
	text-align:left;			
	border:1px black solid;
	border-radius:5px;
			}
    </style>
</head>

<body>
<?php

//// Mailer Cumpleaños Padres
$cuerpo="";
$cuerpo='<style> 
		*{
            margin:5;
            padding:5;
        }
		
table, table td, table tr {
	padding:0px;
	border-spacing: 0px;
}

table {
	border:1px black solid;
	border-radius:5px;
	min-width:400px;
	font-family: Helvetica,Arial;
}

table td {
	padding:6px;
}
       
        #content{
            background-color:#fff;
            width:750px;
            padding:40px;
            margin:0 auto;
            border-left:30px solid #1D81B6;
            border-right:1px solid #ddd;
            -moz-box-shadow:0px 0px 16px #aaa;
        }
        .head{
            font-family:Helvetica,Arial;
            text-transform:uppercase;
            font-weight:bold;
            font-size:12px;
            font-style:normal;
            letter-spacing:3px;
            color:#888;
            border-bottom:3px solid #f0f0f0;
            padding-bottom:10px;
            margin-bottom:10px;
        }
        .head a{
            color:#1D81B6;
            text-decoration:none;
            float:right;
            text-shadow:1px 1px 1px #888;
        }
        .head a:hover{
            color:#f0f0f0;
        }
        #content h1{
            font-family:Helvetica,Arial;
            color:#1D81B6;
            font-weight:normal;
            font-style:normal;
            font-size:56px;
            text-shadow:1px 1px 1px #aaa;
        }
        #content h2{
            font-family:Helvetica,Arial;
            font-size:34px;
            font-style:normal;
            background-color:#f0f0f0;
            margin:40px 0px 30px -40px;
            padding:0px 40px;
            clear:both;
            float:left;
            width:100%;
            color:#aaa;
            text-shadow:1px 1px 1px #fff;
        }
			table th{
	font-weight: bold;
	color:#fff;
	background-color: #888;
	border-bottom:1px #000 solid;
	text-align:center;
	border-radius:5px;
	min-width:400px;
			}
			table td.a1{
	font-weight: bold;
	color:#fff;
	background-color: #888;
	border-bottom:1px #000 solid;
	text-align:left;			
	border:1px black solid;
	border-radius:5px;
	min-width:400px;
			}

    </style>';

echo '<h1> <center>Cumpleaños de padres de familia del mes.</center></h1>';

///Cambiar la ruta!!!!
$cuerpo= "<center><img  src='http://ecolmenares.net/sieweb/altamira/im/logo.jpg'></img></center>";

$cuerpo.='<h1> <center>Cumpleaños de padres de familia del mes.</center></h1>';
$cuerpo.= "<html> 
	<head> <title>Cumpleaños del mes</title></head> 
	<body>
	<br>
	<p>&nbsp;</p>
";

$fecha1=date(d);
$fecha2=date(m);
$fecha3=date(Y); 

$dias_mes= cal_days_in_month(CAL_GREGORIAN, $fecha2, $fecha3); // 31
//echo $dias_mes;
$Fecha_ini = date("d/m/Y");
$dias_m = $dias_mes;
$Fecha_fin = new DateTime();
date_add($Fecha_fin, date_interval_create_from_date_string(''.$dias_m.' days'));
//echo $Fecha_fin->format('d/m/Y');

echo "<h2>Reporte de fechas del: ".$Fecha_ini." al: ".$Fecha_fin->format('d/m/Y')."</h2>";
$cuerpo.= "<h2>Reporte de fechas del: ".$Fecha_ini." al: ".$Fecha_fin->format('d/m/Y')."</h2>";
$rango_fechas = "Reporte de fechas del: ".$Fecha_ini." al: ".$Fecha_fin->format('d/m/Y');


echo "<table width='900' border='1' align='left' class='table1'>";
$cuerpo.="<table width='900' border='1' class='table1'>";

for ($i=0; $i<=$dias_mes;$i++)
{
$cumple_papa=mysql_query("SELECT DISTINCT 
						 			CONCAT(familias.titulo_padre,'. ', familias.nombre_padre,' ', familias.apellido_paterno_padre,' ', 		familias.apellido_materno_padre) AS Nombre,
									DATE_FORMAT(familias.fecha_nac_padre, '%d/%m/%Y') AS fecha_nac_padre,
									CONCAT(alumnos.nombre,' ', alumnos.apellido_paterno,' ', alumnos.apellido_materno) AS alumno, 
									secciones.nombre AS seccion, 
									alumnos.grado, alumnos.grupo, 
									IFNULL(familias.e_mail_padre,' No tiene ') AS e_mail_padre, 
									IFNULL(familias.telefono_padre,' No tiene ') AS telefono_padre, 
									IFNULL(familias.celular_padre,' No tiene ') AS celular_padre
						FROM   
 					  				familias INNER JOIN
                      				alumnos ON familias.familia = alumnos.familia INNER JOIN
                      				secciones ON alumnos.seccion = secciones.seccion
	 					WHERE  
									finado_padre ='N' AND
									alumnos.activo='A' AND
									TIMESTAMPDIFF(YEAR, fecha_nac_padre, CURRENT_DATE + INTERVAL($i-1) DAY)
										<
									TIMESTAMPDIFF(YEAR, fecha_nac_padre, CURRENT_DATE + INTERVAL $i DAY)

						ORDER BY 
									DAYOFYEAR(fecha_nac_padre)",$link)or die("SELECT DISTINCT 
						 			CONCAT(familias.titulo_padre,'. ', familias.nombre_padre,' ', familias.apellido_paterno_padre,' ', 		familias.apellido_materno_padre) AS Nombre,
									DATE_FORMAT(familias.fecha_nac_padre, '%d/%m/%Y') AS fecha_nac_padre,
									CONCAT(alumnos.nombre,' ', alumnos.apellido_paterno,' ', alumnos.apellido_materno) AS alumno, 
									secciones.nombre AS seccion, 
									alumnos.grado, alumnos.grupo, 
									IFNULL(familias.e_mail_padre,' No tiene ') AS e_mail_padre, 
									IFNULL(familias.telefono_padre,' No tiene ') AS telefono_padre, 
									IFNULL(familias.celular_padre,' No tiene ') AS celular_padre
						FROM   
 					  				familias INNER JOIN
                      				alumnos ON familias.familia = alumnos.familia INNER JOIN
                      				secciones ON alumnos.seccion = secciones.seccion
	 					WHERE  
									finado_padre ='N' AND
									alumnos.activo='A' AND 
									TIMESTAMPDIFF(YEAR, fecha_nac_padre, CURRENT_DATE + INTERVAL($i-1) DAY)
										<
									TIMESTAMPDIFF(YEAR, fecha_nac_padre, CURRENT_DATE + INTERVAL $i DAY)

						ORDER BY 
									DAYOFYEAR(fecha_nac_padre)".mysql_error());

$todayDate = date("d-m-Y");// current date

$fecha = new DateTime();
date_add($fecha, date_interval_create_from_date_string(''.$i.' days'));

echo '<tr><td></td></tr><tr><td></td></tr><tr><td class="a1"><strong>&nbsp;Cumpleaños del día: </strong>'.$fecha->format('d/m/Y').'</td></tr>'; // imprime por ejemplo: 01/01/2014
$cuerpo.= '<tr><td></td></tr><tr><td></td></tr><tr><td class="a1"><strong>&nbsp;Cumpleaños del día: </strong>'.$fecha->format('d/m/Y').'</td></tr>'; 

$exportar.=";;;;;;;;;";
$exportar.="\n";
$exportar.="Cumpleaños del día: ".$fecha->format('d/m/Y').";;;;";
$exportar.="\n";
$exportar.=";;;;;;;;;";
$exportar.="\n";

echo "
<tr></td><td></td></tr>
  <tr>
    <th width='514' height='54' scope='col' class='encabezado'>Nombre</th>
    <th scope='col' class='encabezado'>Fecha de Nacimiento</th>
    <th scope='col' class='encabezado'>E-Mail</th>
    <th scope='col' class='encabezado'>Telefono</th>
    <th scope='col' class='encabezado'>Celular</th>
	<th scope='col' class='encabezado'>Alumno</th>
	<th scope='col' class='encabezado'>Secci&oacute;n</th>
	<th scope='col' class='encabezado'>Grado</th>
	</tr>";

$cuerpo.="
<tr></td><td></td></tr>
  <tr>
    <th width='514' height='54' scope='col' class='a2'>Nombre</th>
    <th scope='col' class='a2'>Fecha de Nacimiento</th>
    <th scope='col' class='a2'>E-Mail</th>
    <th scope='col' class='a2'>Telefono</th>
    <th scope='col' class='a2'>Celular</th>
	<th scope='col' class='a2'>Alumno</th>
	<th scope='col' class='a2'>Secci&oacute;n</th>
	<th scope='col' class='a2'>Grado</th>
	</tr>";
  
  
while($row = mysql_fetch_array($cumple_papa))
                    {						
							///Cuerpo de la tabla HTML mostrada en pantalla
                      echo '<tr><td>&nbsp;'.utf8_encode($row['Nombre']).'&nbsp;</td>
                      		<td><center>&nbsp;'.$row['fecha_nac_padre'].'&nbsp;</center></td> 
                      		<td><center></strong>&nbsp;'.$row['e_mail_padre'].'&nbsp;</center></td>
					 		<td><center>&nbsp;'.$row['telefono_padre'].'&nbsp;</center></td>
					  		<td><center>&nbsp;'.$row['celular_padre'].'&nbsp;</center></td>
							<td><center>&nbsp;'.utf8_encode($row['alumno']).'&nbsp;</center></td>
							<td><center>&nbsp;'.utf8_encode($row['seccion']).'&nbsp;</center></td>
	  				        <td><center>&nbsp;'.$row['grado'].'-'.$row['grupo'].'</center></td>
							</tr>';  
							
							////Cuerpo de la tabla HTML enviada en el correo							
					  $cuerpo.='<tr><td>&nbsp;'.utf8_encode($row['Nombre']).'&nbsp;</td>
                      		<td><center>&nbsp;'.$row['fecha_nac_padre'].'&nbsp;</center></td> 
                      		<td><center>&nbsp;'.$row['e_mail_padre'].'&nbsp;</center></td>
					  		<td><center>&nbsp;'.$row['telefono_padre'].'&nbsp;</center></td>
					  		<td><center>&nbsp;'.$row['celular_padre'].'&nbsp;</center></td>
							<td><center>&nbsp;'.utf8_encode($row['alumno']).'&nbsp;</center></td>
							<td><center>&nbsp;'.utf8_encode($row['seccion']).'&nbsp;</center></td>							
	    					<td><center>&nbsp;'.$row['grado'].'-'.$row['grupo'].'</center></td>
							</tr>';
							
						  	////Cuerpo que se envia al TXT						  
 					  $exportar.=
					  utf8_encode($row['Nombre']).";".$row['fecha_nac_padre'].";".$row['e_mail_padre'].";".$row['telefono_padre'].";".$row['celular_padre'].";".utf8_encode($row['alumno']).";".utf8_encode($row['seccion']).";".$row['grado']."-".$row['grupo'];
					  $exportar.="\n";
					  
                    } 

$cumple_mapa=mysql_query("SELECT DISTINCT 
						 			CONCAT(familias.titulo_madre,'. ', familias.nombre_madre,' ', familias.apellido_paterno_madre,' ', 		familias.apellido_materno_madre) AS Nombre,
									DATE_FORMAT(familias.fecha_nac_madre, '%d/%m/%Y') AS fecha_nac_madre,
									CONCAT(alumnos.nombre,' ', alumnos.apellido_paterno,' ', alumnos.apellido_materno) AS alumno, 
									secciones.nombre AS seccion, 
									alumnos.grado, alumnos.grupo, 
									IFNULL(familias.e_mail_madre,' No tiene ') AS e_mail_madre, 
									IFNULL(familias.telefono_madre,' No tiene ') AS telefono_madre, 
									IFNULL(familias.celular_madre,' No tiene ') AS celular_madre
						FROM   
 					  				familias INNER JOIN
                      				alumnos ON familias.familia = alumnos.familia INNER JOIN
                      				secciones ON alumnos.seccion = secciones.seccion
	 					WHERE  
									finado_madre ='N' AND
									alumnos.activo='A' AND
									TIMESTAMPDIFF(YEAR, fecha_nac_madre, CURRENT_DATE + INTERVAL($i-1) DAY)
										<
									TIMESTAMPDIFF(YEAR, fecha_nac_madre, CURRENT_DATE + INTERVAL $i DAY)

						ORDER BY 
									DAYOFYEAR(fecha_nac_madre)",$link)or die("SELECT DISTINCT 
						 			CONCAT(familias.titulo_madre,'. ', familias.nombre_madre,' ', familias.apellido_paterno_madre,' ', 		familias.apellido_materno_madre) AS Nombre,
									DATE_FORMAT(familias.fecha_nac_madre, '%d/%m/%Y') AS fecha_nac_madre,
									CONCAT(alumnos.nombre,' ', alumnos.apellido_paterno,' ', alumnos.apellido_materno) AS alumno, 
									secciones.nombre AS seccion, 
									alumnos.grado, alumnos.grupo, 
									IFNULL(familias.e_mail_madre,' No tiene ') AS e_mail_madre, 
									IFNULL(familias.telefono_madre,' No tiene ') AS telefono_madre, 
									IFNULL(familias.celular_madre,' No tiene ') AS celular_madre
						FROM   
 					  				familias INNER JOIN
                      				alumnos ON familias.familia = alumnos.familia INNER JOIN
                      				secciones ON alumnos.seccion = secciones.seccion
	 					WHERE  
									finado_madre ='N' AND
									alumnos.activo='A' AND
									TIMESTAMPDIFF(YEAR, fecha_nac_madre, CURRENT_DATE + INTERVAL($i-1) DAY)
										<
									TIMESTAMPDIFF(YEAR, fecha_nac_madre, CURRENT_DATE + INTERVAL $i DAY)

						ORDER BY 
									DAYOFYEAR(fecha_nac_madre)".mysql_error());

while($row = mysql_fetch_array($cumple_mapa))
                    {						
					  ///Cuerpo de la tabla HTML mostrada en pantalla
					  echo '<tr><td>&nbsp;'.utf8_encode($row['Nombre']).'&nbsp;</td>
					  <td><center>&nbsp;'.$row['fecha_nac_madre'].'&nbsp;</center></td>
					  <td><center>&nbsp;'.$row['e_mail_madre'].'&nbsp;</center></td>
					  <td><center>&nbsp;'.$row['telefono_madre'].'&nbsp;</center></td>
					  <td><center>&nbsp;'.$row['celular_madre'].'&nbsp;</center></td>
					  <td><center>&nbsp;'.utf8_encode($row['alumno']).'&nbsp;</center></td>
					  <td><center>&nbsp;'.utf8_encode($row['seccion']).'&nbsp;</center></td>							
					  <td><center>&nbsp;'.$row['grado'].'-'.$row['grupo'].'</center></td>
					  </tr>';
					  
					  ////Cuerpo de la tabla HTML enviada en el correo
					  $cuerpo.='<tr><td>&nbsp;'.utf8_encode($row['Nombre']).'&nbsp;</td>
                      <td><center>&nbsp;'.$row['fecha_nac_madre'].'&nbsp;</center></td> 
                      <td><center>&nbsp;'.$row['e_mail_madre'].'&nbsp;</center></td>
					  <td><center>&nbsp;'.$row['telefono_madre'].'&nbsp;</center></td>
					  <td><center>&nbsp;'.$row['celular_madre'].'&nbsp;</center></td>
 					  <td><center>&nbsp;'.utf8_encode($row['alumno']).'&nbsp;</center></td>
					  <td><center>&nbsp;'.utf8_encode($row['seccion']).'&nbsp;</center></td>												  
					  <td><center>&nbsp;'.$row['grado'].'-'.$row['grupo'].'</center></td>
					  </tr>'; 
					  
					  ////Cuerpo que se envia al TXT
					  $exportar.=utf8_encode($row['Nombre']).";".$row['fecha_nac_madre'].";".$row['e_mail_madre'].";".$row['telefono_madre'].";".$row['celular_madre'].";".utf8_encode($row['alumno']).";".utf8_encode($row['seccion']).";".$row['grado']."-".$row['grupo'];
					  $exportar.="\n";						  
                    }
}
    echo '</table>';
	$cuerpo.='</table>';
	$cuerpo.='	</body> 
	</html>';

/// Obtenemos nombre del colegio
$escuela = mysql_result(mysql_query("SELECT nombre_colegio FROM parametros",$link),0,0);
$n_escuela = mysql_result(mysql_query("SELECT nombre_colegio FROM parametros",$link),0,0);


////Hacemos el XLS y el txt para el pdf
file_put_contents('CumpleMes.xls',utf8_decode($cuerpo));
file_put_contents('CumpleMes.txt',utf8_decode($exportar));


//// Ejecutamos el archivo de PHP para crear el pdf
include_once('pdf_cumple_mes.php');  


//// PHP Mailer
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailSender="Cumpleaños del Mes, $n_escuela";

/// Datos Sistemas
$mailUser="sistemas@colmenares.org.mx";
$mailFrom="sistemas@colmenares.org.mx";
$mailBCC="sistemas@colmenares.org.mx";
//$mailBCC1="emmanuel.contreras@colmenares.org.mx";
$mailBCC2="schavez@colmenares.org.mx";

// Personal del colegio
$mailBCC3="miguel.solorio@altamira.edu.mx";
$mailBCC4="jesus.castaneda@altamira.edu.mx";
$mailBCC5="gloria.moreno@altamira.edu.mx";
$mailBCC6="eduardo.villanueva@altamira.edu.mx";
$mailBCC7="socorro.perez@altamira.edu.mx";
$mailBCC8="lily.vazquez@altamira.edu.mx";
$mailBCC9="victor.cervantes@altamira.edu.mx";
$mailBCC10="angel.segura@altamira.edu.mx";
$mailBCC11="julio.hernandez@altamira.edu.mx";

$mailPass="U926US<5gG";

		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->PluginDir="../../repositorio/phpMailer/";
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
		$mail->AddAttachment('CumpleMes.xls', 'CumpleMes.xls');
		$mail->AddAttachment('CumpleMes.pdf', 'CumpleMes.pdf.pdf');
		$mail->Timeout=300;

		$mail->AddBCC($mailBCC);
		//$mail->AddBCC($mailBCC1);
		$mail->AddBCC($mailBCC2);
		
		$mail->AddBCC($mailBCC3);
		$mail->AddBCC($mailBCC4);
		$mail->AddBCC($mailBCC5);
		$mail->AddBCC($mailBCC6);
		$mail->AddBCC($mailBCC7);
		$mail->AddBCC($mailBCC8);	
		$mail->AddBCC($mailBCC9);
		$mail->AddBCC($mailBCC10);
		$mail->AddBCC($mailBCC11);

		$mail->Subject="Cumpleaños del Mes, $n_escuela";
		$mail->Body=$cuerpo;
				
		if(!$mail->Send())
			echo"Mailer Error: ".$mail->ErrorInfo;
		$mail->ClearAddresses();

////Borramos el archivo XLS que creamos
$dir='CumpleMes.xls'; //puedes usar dobles comillas si quieres
if(file_exists($dir))
{
if(unlink($dir))
print "El archivo ".$dir." fue borrado";
}
else
print "Este archivo no existe";

///Borramos el archivo PDF
$dir='CumpleMes.pdf'; //puedes usar dobles comillas si quieres
if(file_exists($dir))
{
if(unlink($dir))
print "<br>El archivo ".$dir." fue borrado";
}
else
print "<br>Este archivo no existe";
	
////Borramos el archivo TXT
$dir='CumpleMes.txt'; //puedes usar dobles comillas si quieres
if(file_exists($dir))
{
if(unlink($dir))
print "<br>El archivo ".$dir." fue borrado";
}
else
print "<br>Este archivo no existe";
?>  
</body>
</html>