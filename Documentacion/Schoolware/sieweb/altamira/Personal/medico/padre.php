<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Consultas M&eacute;dicas</title>
</head>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<script type="text/javascript" src="javascript.js"></script>
<script lenguage="javascript" src="js/jquery.js"></script>

<?php
include('../../config.php');
require_once("funciones.php");
mysql_query("SET NAMES 'utf8'");
$familia = 11205;
//$familia = $_REQUEST['familia'];

$query_hijo = mysql_query("SELECT * FROM alumnos 
						 		WHERE 
									familia = '{$familia}'
											",$link) or die (mysql_error());

?>
<body>

<select id="hijo" name="hijo" onchange="load(this.value)">
	<option value="">Seleccione un hijo(a)</option>
    
    <?
		while($hijo_= mysql_fetch_array($query_hijo)){
			echo "<option value=".$hijo_['alumno'].">".$hijo_['nombre']." ".$hijo_['apellido_paterno']." ".$hijo_['apellido_materno']."</option>";
		}
?>
</select>

<div name="resp" id="resp">&nbsp;</div>
</table>

</body>
</html>
<script type="text/javascript">
$("#hijo").on("change", buscarHijo);

function buscarHijo(){
	
	$hijo = $("#hijo").val();
	
	if($hijo == ""){
		$('#resp').html("<p>Primero seleccione un hijo!</p>");
	}
	else{
	$('#resp').html('<td colspan="5">Cargando datos... espere un momento</td>'); //realizo la call via jquery ajax 
	$.ajax({ 
		   url: 'funciones/detalle_hijo.php', 
		   data: 'hijo='+$hijo, 
		   success: function(resp){ 
		   							$('#resp').html(resp) 
									} 
					}); 
		}
	} 
</script>