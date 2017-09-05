<? session_start();
include('../config.php');
mysql_query("SET NAMES 'utf8'");
if(!empty($_POST['muestra'])) $muestra=$_POST['muestra'];
if(!empty($_GET['muestra'])) $muestra=$_GET['muestra'];
{ switch($muestra)
  { case "boleta":   $url="../boletas.php?a=a";               $act="and activo='A'";break;
    case "alumno":   $url="../datos.php?dequien=alumno";  $act="and activo in ('A','I')"; break;
    case "exalumno": $url="../datos.php?dequien=alumno";  $act="and activo in ('B','E')"; break;
	case "familia":  $url="../datos.php?dequien=familia"; $act=""; break;
  }
}
echo"
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>
<title>Selecciona Alumna</title>
</head><script language='javascript' type='text/javascript'><!--
function ver()
{ var alumnoN=document.getElementById('alumnoN').value;
  document.getElementById('iframe1').style.display='inline';
  document.getElementById('iframe1').src='$url&alumnoN='+alumnoN;
}
--></script>"; ?>
<body  bgcolor="<?=$color_main; ?>">
<form name="frmm"  method="post" action="buscaAlumno.php">
<input type="hidden" name="muestra" id="muestra" value="<?=$muestra;?>">
<table>
<tr>
  <td>Clave:</td><td><input onKeyPress="if(event.keyCode<48 || event.keyCode>57) event.returnValue=false;" id="clavea" name="clavea"><input type='submit' value="Buscar" onClick="document.getElementById('nombre').value='';"></td><? 
$val="";
if(!empty($_POST['clavea'])){ $val=$_POST['clavea']; $and="and alumno like '%$val%'"; $order="order by alumno";}
if(!empty($_POST['nombre'])){ $val=$_POST['nombre']; $and="and (nombre like '%$val%' or apellido_paterno like '%$val%' or apellido_materno like '%$val%' )"; $order="order by apellido_paterno,apellido_materno,nombre ";}
$sede=mysql_result(mysql_query("select sede from parametros",$link),0,0); 
if($val!="")
{$r=mysql_query("select * from alumnos where plantel=$sede $and $act $order",$link)or die(mysql_error());
 if (mysql_affected_rows($link)<>0 )
 {echo"<td rowspan=2><select style='font-size:8pt;' id='alumnoN' name='alumnoN'>";
  while($a=mysql_fetch_array($r)) echo"<option value='".$a[0]."'>".$a[0]."-".$a[22]." ".$a[23]." ".$a[1]."</option>";
  echo"</select><input type='button' onClick='ver()' value='Aceptar'></td>";
 }
} ?> 
</tr>
<tr>
  <td>Nombre:</td><td><input name="nombre" id="nombre"><input type='submit' value="Buscar" onClick="document.getElementById('clavea').value='';"></td>
</tr>
</table>
</form>
<iframe width="95%" height="420" name="iframe1" id="iframe1" style="display:none" frameborder="0"></iframe>
</body>
</html>
