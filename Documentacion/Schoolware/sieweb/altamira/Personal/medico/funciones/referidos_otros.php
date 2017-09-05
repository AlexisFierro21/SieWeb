<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
   <meta charset="UTF-8">
   <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<html>
<head>

</head>
<body>
<?php
include('../../../config.php');
	
	mysql_query("SET NAMES 'utf8'");
	$referido =$_GET['referido'];
	
	if($referido == 1){
		
	$resultado = "SELECT 
							nombre_familia AS nombre 
						FROM 
							familias 
						WHERE 
							activo_web = 'A'
					";
		$result = mysql_query($resultado,$link);
			echo "<label>Referido por:</label>
					<BR>
					<select name='referido_otro' id='referido_otro'>
							<option value=''>- Seleccione quien refiere -</option>";
			while($row = mysql_fetch_array($result)) {
  					echo" 	<option value='".$row['nombre']."'>".$row['nombre']."</option>";
			}
			echo "</select>";
	
	}
	elseif($referido == 2){
	$resultado = "SELECT CONCAT(apellido_paterno,' ',apellido_materno,', ', nombre) AS nombre FROM personal WHERE status = 'A' ";
	
	$result = mysql_query($resultado,$link);
		echo "<label>Referido por:</label>
					<BR>
					<select name='referido_otro' id='referido_otro'>
							<option value=''>- Seleccione quien refiere -</option>";
			while($row = mysql_fetch_array($result)) {
  					echo" 	<option value='".$row['nombre']."'>".$row['nombre']."</option>";
			}
			echo "</select>";
	
	}
	elseif($referido == 3){
		echo "<label>Inserte Nombre de quien refiere al Paciente:</label>
				<br>
					<input type='text' name='referido_otro' id='referido_otro'>
				";
	}
	
?>
</body>
</html>
