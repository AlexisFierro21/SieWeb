<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<header>
<h1>Registrando sus datos</h1>
</header>
<?php
//include("config.php");
include("config.php");


$servidor = $server;
$usuario = $userName;
$pass = $password;
$db = $DB;
$cone = mysql_connect($servidor, $usuario, $pass, $bd) or die (mysql_error());
mysql_query("SET NAMES 'utf8'");
//echo phpinfo(); 
$e_mail = $_REQUEST['e_mail'];
$password = $_REQUEST['password'];
$describe= $_REQUEST['describe'];



//Encriptamos la clave ingresada y la almacenamos en $clavex
$secret = "encripta";
$clavex = hash_hmac("sha512", $password, $secret);
//Conectando a la BD

$cadena_sql = sprintf("INSERT INTO cuentas_email (descripcion, cuenta, password) VALUES('$describe', 'e_mail', '$clavex'");
$x = mysql_query("INSERT INTO cuentas_email
													(	descripcion,
														cuenta,
														password
													) 
											VALUES(
												   		'{$describe}',	
												   		'{$e_mail}', 
														'{$clavex}'
														)",$link)or die(mysql_error());

if($x > 0){
echo "</br>Se registro correctamente la cuenta de correo ";
}else{
echo "</br>¡Oops! Hubieron problemas, sus datos no pudieron registrarse.";
}
mysql_close($cone);
?>
<footer>
<p><a href="mail_data.php">Volver</a></p>
</footer>
</body>
</html>
