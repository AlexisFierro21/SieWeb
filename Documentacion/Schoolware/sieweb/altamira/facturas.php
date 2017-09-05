<? session_start();
$user = $_SESSION["userName"];
$pass = $_SESSION["password"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script language="JavaScript">
	function resize(){window.resizeTo(700,500)}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:Colmenares:.</title>
<link href="css/index.css" rel="stylesheet" type="text/css" />
<!-- <head> <meta http-equiv="Refresh" content="0;url=http://ideasys.com.mx/servicios/ingreso.aspx?id=9"> </head> -->
<!-- <head> <meta http-equiv="Refresh" content="0;url=http://www.ideasys.com.mx/servicios/consultapanel.aspx?id=9&NICK='$user'&CLAVE='$pass'"> </head> -->
<meta http-equiv="Refresh" content="0;url=https://www.ideasys.com.mx/servicios/consultapanel.aspx?id=9&NICK=<? echo $user; ?>&CLAVE=<? echo $pass; ?>">



<style type="text/css">
<!--
a:link {
	color: #000066;
}
a:visited {
	color: #000066;
}
a:hover {
	color: #000066;
}
a:active {
	color: #000066;
}
.TEXTO {
	text-align: center;
	color: #FFF;
	font-weight: bold;
	font-size: 12px;
}
-->
</style>
</head>
<body onLoad=resize()>
</body>

</html>
