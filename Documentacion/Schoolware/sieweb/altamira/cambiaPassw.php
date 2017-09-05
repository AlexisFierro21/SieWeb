<? session_start();
include('config.php');
include('functions.php');

if(!empty($_POST['npassw']))
{ $clave = $_SESSION['clave'];
  $letter= $_SESSION['letter'];
  switch($letter)
  { case "P": $tbl="personal";  $key="empleado"; break;
    case "A": $tbl="alumnos";   $key="alumno"; break;
	case "F": $tbl="familias";  $key="familia"; break;
	case "E": $tbl="exalumnos"; $key="exalumno"; break;
  }
  $oldPassw=$_POST["opassw_"];
  $newPassw=$_POST['npassw']; 
  $newPassw=f_encripta($clave, $newPassw, 'E');
  $oldPassw=f_encripta($clave, $oldPassw, 'E');
  $pw=mysql_result(mysql_query("select password_web from $tbl where $key='$clave'",$link),0,0);
  if($pw==$oldPassw)
  { $today = getdate();
    $ano=$today["year"];
    $mes=$today["mon"];
    actualizaEstadistico($letter,$clave,$ano,$mes,2);
	$sql="UPDATE $tbl SET password_web='$newPassw', fecha_modificacion= Now() where $key='$clave'";
	
	if($tbl=="familias" and $hmlgcn==1)
	{ $link_gral=mysql_connect($server,$userName,$password)or die('No se pudo conectar db gral: ' . mysql_error());
      mysql_select_db($DB_gral,$link_gral)or die("No se pudo seleccionar DB gral");
	  mysql_query($sql,$link_gral)or die(mysql_error());
	  mysql_query("update familias set fecha_web_padres=Now(), plantel_modificacion=$sede, usuario_modificacion='".$_SESSION['userName']."' where $key='$clave'",$link_gral)or die(mysql_error());
	  $r_fam_sede=mysql_query("select base from familias_x_sedes,bases_x_sedes where $key='$clave' and familias_x_sedes.sede=bases_x_sedes.sede",$link_gral)or die(mysql_error());
	  while($rw_fxs = mysql_fetch_array($r_fam_sede))
	  { $link_fam=mysql_connect($server,$userName,$password)or die('No se pudo conectar db fam: ' . mysql_error());
        mysql_select_db($rw_fxs['base'],$link_fam)or die("No se pudo seleccionar DB fam");
	    mysql_query($sql,$link_fam)or die(mysql_error());
		mysql_close($link_fam);
	  } 
	  $link = mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
      mysql_select_db($DB)or die("No se pudo seleccionar DB");
	}
	else mysql_query($sql,$link)or die(mysql_error());
	
	echo" <script language='javascript'>alert('Actualizaci&oacute;n realizada con &eacute;xito');</script> ";
  }
  else echo" <script language='javascript'>alert('Password Anterior Incorrecto');</script> ";
}
?>
<html><meta http-equiv='Content-Type' content='text/html; charset=windows-1252' />
<script language='javascript' type="text/javascript"><!--
function submitf()
{ if(document.getElementById('npassw').value.length>=6)
  { var newps=document.getElementById('npassw').value;
    var newps2=document.getElementById('npassw2').value;
    if(newps==newps2){ document.getElementById('form_').submit();}
   	else alert("La nueva Clave de Acceso es diferente en la confirmaci&oacute;n");
  }
  else alert("La nueva Clave de Acceso es menor a 6 caracteres");
}
--></script>
<body bgcolor="<?=$color_main; ?>">
<form action="cambiaPassw.php" method="post" name="form_" id="form_"><table align="center">
  <tr> 
    <td><font size="2">Clave de Acceso Anterior:</font></td>
    <td><input type="password" name="opassw_" id="opassw_" size="13" maxlength=8></td>
  </tr>
  <tr> 
    <td><font size="2">Nueva Clave de Acceso:</font></td>
    <td><input type="password" name="npassw" id="npassw" size="13" maxlength=8></td>
  </tr>
  <tr> 
    <td><font size="2">Confirmar Nueva Clave de Acceso:</font></td>
    <td><input type="password" name="opassw" id="npassw2" size="13" maxlength=8></td>
  </tr>
  <tr>
    <td align="center" colspan=2><input type="button" value="Guardar" onClick="submitf()">
  </tr>
</table></form>
</body>
</html>