<?
$alumno ="";
if(!empty($_REQUEST['alumno'])) $alumno=$_REQUEST["alumno"];
if(!empty($_POST['alumno'])) $alumno=$_POST["alumno"]; 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>

<form name="parametros" id="parametros" method="post" action="boton_informe.php">

<input type=button id='envio' name='envio' value="Enviar Informe Integral" onclick="enviar_i('<?=$alumno; ?>');">
</form>
<script language="javascript" type="text/javascript"><!--

function enviar_i(valor)
{ 
	//MM_openBrWindow('enviar_informe.php?combopago='+valor,'','width=750,height=450,scrollbars=1');	
	window.open('enviar_informe_.php?alumno='+valor,'','width=450,height=250,scrollbars=1');
}
</script>
</body>

</html>
