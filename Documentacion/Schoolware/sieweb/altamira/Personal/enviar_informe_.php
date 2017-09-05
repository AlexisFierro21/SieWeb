<?
$alumno ="";
if(!empty($_REQUEST['alumno'])) $alumno=$_REQUEST["alumno"];
if(!empty($_POST['alumno'])) $alumno=$_POST["alumno"]; 

include('enviar_informe.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<div align="center"><strong>INFORME ENVIADO.</strong>
<BODY onLoad="setTimeout(window.close, 3000)">
</div>
</body>

</html>
