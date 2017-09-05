<?php
//$server="ecolmenares.net";
$server="schoolware.com.mx";
$userName="schoolwa_econtre";
$password="si@pTs%FQ9GI";
$DB="schoolwa_Altamira";
$DB_gral="schoolwa_general";

///////////////////////////////////////////
/////////// 	FIP		 //////////////////
///////////////////////////////////////////

$server_fip = "ecolmenares.net";
$DB_fip = "ecolmena_fip";
$usuario_fip = "ecolmena_sieweb";
$contra_fip = "Sc:)p01&8/16";

try{
$dsn_fip = "mysql:host=$server_fip;dbname=$DB_fip";

$pdo_fip = new PDO($dsn_fip, $usuario_fip, $contra_fip, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//$pdo_fip = new PDO($dsn_fip, $usuario_fip, $contra_fip);
 } catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
  }
/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////

?>
