<?
session_start();
include('config.php');
include('functions.php');
$passwordw="";
$pass="";
$cl="1207";
$rstF_ = mysql_query ("select password_web from personal where empleado=$cl ",$link) or die ("select password_web from personal where empleado=$cl ".mysql_error());
		 
		while($rsF_=mysql_fetch_array($rstF_)){
	
			$passwordw=$rsF_['password_web'];
			}
			//$valor='00'.$cl;
			$valor=$cl;
			
$pass = f_encripta ($valor, $passwordw, "D") ;


echo 'Pass'.$pass;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
</body>
</html>
