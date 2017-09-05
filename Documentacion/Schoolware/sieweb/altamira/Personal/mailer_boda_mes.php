<?
include('../config.php');
require ('../../repositorio/phpMailer/PHPMailerAutoload.php');
$exportar="";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Colegio Altamira Moctezuma</title>
</head>
<style> 

table, table td, table tr {
	padding:0px;
	border-spacing: 0px;
}

table {
	border:1px black solid;
	border-radius:5px;
	font-family: Helvetica,Arial;
}

table td {
	padding:0px;
}
        #content{
            background-color:#fff;
            width:750px;
            padding:0px;
            margin:0 auto;
            border-left:30px solid #1D81B6;
            border-right:1px solid #ddd;
            -moz-box-shadow:0px 0px 16px #aaa;
        }
        .head{
            font-family:Helvetica,Arial;
            text-transform:uppercase;
            font-weight:bold;
            font-size:14px;
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
            padding:0px 40px;
            clear:both;
            float:left;
            width:100%;
            color:#aaa;
            text-shadow:1px 1px 1px #fff;
        }
			table th{
				 margin-left: 2em;
	font-weight: bold;
	color:#fff;
	background-color: #888;
	font-size:12px;
	border-bottom:1px #000 solid;
	text-align:center;
	border-radius:0px;
			}
			table td{
				 margin-left: 2em;
	font-size:11px;
	font-color: #888;
	border-bottom:1px #000 solid;
	text-align:left;			
	border:1px black solid;
	border-radius:0px;
			}


    </style>
<body>
<?php


//// Mailer Cumpleaños Padres
$cuerpo.='<style> 

		
table, table td, table tr {
	padding:0px;
	border-spacing: 0px;
}

table {
	border:1px black solid;
	border-radius:5px;
	font-family: Helvetica,Arial;
}

table td {
	padding:0px;
}

       
        #content{
            background-color:#fff;
            width:750px;
            padding:0px;
            margin:0 auto;
            border-left:30px solid #1D81B6;
            border-right:1px solid #ddd;
            -moz-box-shadow:0px 0px 16px #aaa;
        }
        .head{
            font-family:Helvetica,Arial;
            text-transform:uppercase;
            font-weight:bold;
            font-size:14px;
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
            padding:0px 40px;
            clear:both;
            float:left;
            width:100%;
            color:#aaa;
            text-shadow:1px 1px 1px #fff;
        }
			table th{
				 margin-left: 2em;
	font-weight: bold;
	color:#fff;
	background-color: #888;
	font-size:12px;
	border-bottom:1px #000 solid;
	text-align:center;
	border-radius:0px;
			}
			table td{
				 margin-left: 2em;
	font-size:11px;
	font-color: #888;
	border-bottom:1px #000 solid;
	text-align:left;			
	border:1px black solid;
	border-radius:0px;
			}

    </style>';

///Cambiar la ruta!!!!
$cuerpo= "<center><img  src='http://ecolmenares.net/sieweb/altamira/im/logo.jpg'></img></center>";

$cuerpo.= '<h1> <center>Aniversarios de bodas del mes.</center></h1>';

$fecha1=date(d);
$fecha2=date(m);
$fecha3=date(Y); 

$dias_mes= cal_days_in_month(CAL_GREGORIAN, $fecha2, $fecha3); // 31

$Fecha_ini = date("d/m/Y");
$dias_m = $dias_mes;
$Fecha_fin = new DateTime();
date_add($Fecha_fin, date_interval_create_from_date_string(''.$dias_m.' days'));

$cuerpo.= "<h2>Reporte de fechas del: ".$Fecha_ini." al: ".$Fecha_fin->format('d/m/Y')."</h2>";
$rango_fechas = "Reporte de fechas del: ".$Fecha_ini." al: ".$Fecha_fin->format('d/m/Y');

$cuerpo.= "<table width='800' border='2' cellspacing='2'>
      			<tr align='center' valign='middle'>
       	 			<th colspan='14'>Aniversarios de Bodas del Mes</th>
      			</tr>
      			<tr align='center' valign='middle'>
        			<th rowspan='2'>Familia</th>
        			<th rowspan='2'>Fecha de boda</th>
        			<th colspan='4'>Datos de la Madre</th>
        			<th colspan='4'>Datos del Padre</th>
        			<th colspan='4'>Datos del Alumno</th>
      			</tr>
      			<tr align='center' valign='middle'>
        			<th>Nombre</th>
        			<th>Telefono</th>
        			<th>Celular</th>
        			<th>Correo</th>
        			<th>Nombre</th>
        			<th>Telefono</th>
        			<th>Celular</th>
        			<th>Correo</th>
        			<th>Alumno</th>
        			<th>Secci&oacute;n</th>
        			<th>Grado</th>
        			<th>Grupo</th>
      			</tr>
      			<tr align='center' valign='middle'>";

$todayDate = date("d-m-Y");

$i=date(d);

$fecha_boda=mysql_query("SELECT DISTINCT 
									nombre_familia,
						 			CONCAT(familias.titulo_madre,'. ', familias.nombre_madre,' ', familias.apellido_paterno_madre,' ', 					familias.apellido_materno_madre) AS Nombre_Madre,
									CONCAT(familias.titulo_padre,'. ', familias.nombre_padre,' ', familias.apellido_paterno_padre,' ', 		familias.apellido_materno_padre) AS Nombre_Padre,
									CONCAT(alumnos.nombre) AS alumno, 
									DATE_FORMAT(fecha_boda, '%d/%m/%Y') AS fecha_boda,
									secciones.nombre AS seccion, 
									alumnos.grado, alumnos.grupo, 
									IFNULL(familias.e_mail_madre,' No tiene ') AS e_mail_madre, 
									IFNULL(familias.telefono_madre,' No tiene ') AS telefono_madre, 
									IFNULL(familias.celular_madre,' No tiene ') AS celular_madre,
									IFNULL(familias.e_mail_padre,' No tiene ') AS e_mail_padre, 
									IFNULL(familias.telefono_padre,' No tiene ') AS telefono_padre, 
									IFNULL(familias.celular_padre,' No tiene ') AS celular_padre
						FROM   
 					  				familias INNER JOIN
                      				alumnos ON familias.familia = alumnos.familia INNER JOIN
                      				secciones ON alumnos.seccion = secciones.seccion
	 					WHERE  
									finado_madre ='N' AND
									finado_padre='N' AND
									casados_iglesia='S' AND
									alumnos.activo='A' AND
									TIMESTAMPDIFF(year, fecha_boda,  CURDATE())
										<
									TIMESTAMPDIFF(year, fecha_boda,  LAST_DAY(CURRENT_DATE) )
						ORDER BY 
									DATE_FORMAT(fecha_boda,'%m %d')",$link)
			or die("SELECT DISTINCT 
				   					nombre_familia,
						 			CONCAT(familias.titulo_madre,'. ', familias.nombre_madre,' ', familias.apellido_paterno_madre,' ', 					familias.apellido_materno_madre) AS Nombre_Madre,
									CONCAT(familias.titulo_padre,'. ', familias.nombre_padre,' ', familias.apellido_paterno_padre,' ', 		familias.apellido_materno_padre) AS Nombre_Padre,
									CONCAT(alumnos.nombre,' ', alumnos.apellido_paterno,' ', alumnos.apellido_materno) AS alumno, 
									DATE_FORMAT(fecha_boda, '%d/%m/%Y') AS fecha_boda,
									secciones.nombre AS seccion, 
									alumnos.grado, alumnos.grupo, 
									IFNULL(familias.e_mail_madre,' No tiene ') AS e_mail_madre, 
									IFNULL(familias.telefono_madre,' No tiene ') AS telefono_madre, 
									IFNULL(familias.celular_madre,' No tiene ') AS celular_madre,
									IFNULL(familias.e_mail_padre,' No tiene ') AS e_mail_padre, 
									IFNULL(familias.telefono_padre,' No tiene ') AS telefono_padre, 
									IFNULL(familias.celular_padre,' No tiene ') AS celular_padre
						FROM   
 					  				familias INNER JOIN
                      				alumnos ON familias.familia = alumnos.familia INNER JOIN
                      				secciones ON alumnos.seccion = secciones.seccion
	 					WHERE  
									finado_madre = 'N' AND
									finado_padre= 'N' AND
									casados_iglesia= 'S' AND
									alumnos.activo= 'A' AND
									TIMESTAMPDIFF(year, fecha_boda,  CURDATE() -1 DAY)
										<
									TIMESTAMPDIFF(year, fecha_boda,  LAST_DAY(CURRENT_DATE) )
						ORDER BY 
									DATE_FORMAT(fecha_boda,'%m %d')
										".mysql_error());

while($row = mysql_fetch_array($fecha_boda))
                    {
					  ///Cuerpo de la tabla HTML mostrada en pantalla
                      
		$cuerpo.= '
				<tr align="center" valign="middle">
        				<td>&nbsp;'.utf8_encode($row['nombre_familia']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['fecha_boda']).'&nbsp;</td>

				        <td>&nbsp;'.utf8_encode($row['Nombre_Madre']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['telefono_madre']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['celular_madre']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['e_mail_madre']).'&nbsp;</td>

				        <td>&nbsp;'.utf8_encode($row['Nombre_Padre']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['telefono_padre']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['celular_padre']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['e_mail_padre']).'&nbsp;</td>

 				        <td>&nbsp;'.utf8_encode($row['alumno']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['seccion']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['grado']).'&nbsp;</td>
				        <td>&nbsp;'.utf8_encode($row['grupo']).'&nbsp;</td>
				</tr>';
					  	  
				$exportar.=utf8_encode($row['nombre_familia']).";";
				$exportar.=utf8_encode($row['fecha_boda']).";";
				$exportar.=utf8_encode($row['Nombre_Madre']).";";
				$exportar.=utf8_encode($row['telefono_madre']).";";
				$exportar.=utf8_encode($row['celular_madre']).";";
				$exportar.=utf8_encode($row['e_mail_madre']).";";
				$exportar.=utf8_encode($row['Nombre_Padre']).";";
				$exportar.=utf8_encode($row['telefono_padre']).";";
				$exportar.=utf8_encode($row['celular_padre']).";";
				$exportar.=utf8_encode($row['e_mail_padre']).";";
				$exportar.=utf8_encode($row['alumno']).";";
				$exportar.=utf8_encode($row['seccion'])." ".utf8_encode($row['grado'])." ".utf8_encode($row['grupo']).";";
				$exportar.="\n";
				
                    } 

    $cuerpo.= '</table>';
	
echo $cuerpo;

/// Obtenemos nombre del colegio
$escuela = mysql_result(mysql_query("SELECT nombre_colegio FROM parametros",$link),0,0);
$n_escuela = mysql_result(mysql_query("SELECT nombre_colegio FROM parametros",$link),0,0);


////Hacemos el XLS y el txt para el pdf
file_put_contents('BodaMes.xls',utf8_decode($exportar));
file_put_contents('BodaMes.txt',utf8_decode($exportar));


//// Ejecutamos el archivo de PHP para crear el pdf
include('pdf_boda_mes.php');  
//include("../../repositorio/phpMailer/class.phpmailer.php");

//// PHP Mailer
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailSender="Aniversarios de la Mes, $n_escuela";

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
		$mail->AddAttachment('BodaMes.xls', 'BodaMes.xls');
		$mail->AddAttachment('BodaMes.pdf', 'BodaMes.pdf');
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
		
		$mail->Subject="Aniversarios de bodas del mes, $n_escuela";
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
$dir='BodaMes.pdf'; //puedes usar dobles comillas si quieres
if(file_exists($dir))
{
if(unlink($dir))
print "<br>El archivo ".$dir." fue borrado";
}
else
print "<br>Este archivo no existe";
	
////Borramos el archivo TXT
$dir='BodaMes.txt'; //puedes usar dobles comillas si quieres
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