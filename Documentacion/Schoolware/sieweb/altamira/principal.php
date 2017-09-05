<? session_start(); 
   $_SESSION['paso']=0;
   $_SESSION['alumno_inscripcion']=0;
   $_SESSION['recorrido']='0';
   $_SESSION['recorrido_aceptados']='0'; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<br /><br /><br /><br /><br />
<? include('connection.php');
$clave=$_SESSION['clave'];
$sqlQ="select * from alumnos, parametros where familia='$clave' and activo = 'A' and alumnos.plantel = parametros.sede";
$res=mysql_query($sqlQ,$link)or die(mysql_error());
if(mysql_affected_rows($link)>0){ ?>
<div align="center" style="color:#CC0000">
<input type="button" style="background:#CC0000; font-size:33px; color:#FFCCCC; text-decoration:blink; border-width:medium"  value=" I N S C R P C I O N E S " onClick="location.href='Familia/inscripciones.php';" /><br />Haz click aqu&iacute; para seguir el tr&aacute;mite de inscripci&oacute;n o reinscripci&oacute;n de las alumnas.<br />
</div> <? } ?>
</body>
</html>
