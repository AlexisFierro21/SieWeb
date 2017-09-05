<!DOCTYPE html>
<html>
<head>

</head>
<body>
<?php
include('../../config.php');
	
	$resultado = mysql_query("SELECT periodo FROM parametros");
		if (!$resultado) {
   		 echo 'No se pudo ejecutar la consulta: ' . mysql_error();
    	exit;
		}
			$fila = mysql_fetch_row($resultado);

			//echo $fila[0]; 

	
	
	
$q = intval($_GET['paciente']);

$sql="SELECT 
						alumno, 
						doctor,
						telefono_doctor,
						tipo_sangre,
						alergias,
						clinica,
						telefono_clinica,
						aseguradora,
						poliza,
						usa_lentes,
						nombre_emer_1,
						parentesco_emer_1,
						tel_casa_emer_1,
						tel_ofna_emer_1
						tel_cel_emer_1,
						nombre_emer_2,
						parentesco_emer_2,
						tel_casa_emer_2,
						tel_ofna_emer_2,
						tel_cel_emer_2
					FROM 
						alumnos
					WHERE alumno = '".$q."'";	
					
					//,aut_medico,aut_medicamento
$result = mysql_query($sql,$link);

echo "<table class='target' width='500'>
			<!-- Mandamos a llamar desde el PHP aquí nuestra función para mostrar los valores de la tabla -->
		";
while($row = mysql_fetch_array($result)) {
  echo" 
 <tr>
    <td scope='col' colspan='2' >
    		&nbsp;<b>M&eacute;dico:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>" . $row['doctor'] . "
            <br>
    		&nbsp;<b>Telefono M&eacute;dico:&nbsp;</b>" . $row['telefono_doctor'] . " 
          </td>
  </tr>
  <tr>
    <td scope='row' colspan='2'>
    		&nbsp;<b>Tipo sangre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>" . $row['tipo_sangre'] . "
            <br>
            &nbsp;<b>Alergias:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>" . $row['alergias'] . "
            <br>
            &nbsp;<b>Usa Lentes:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>". $row['usa_lentes'] ."
            <br>
            &nbsp;<b>Clinica:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>" . $row['clinica'] . "
            <br>
            &nbsp;<b>Telefono clinica:&nbsp;&nbsp;&nbsp;</b>" . $row['telefono_clinica'] . "
    </td>
  </tr>
  <tr>
    <td scope='row' colspan='2' >
    		&nbsp;<b>Aseguradora:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>" . $row['aseguradora'] . "
            <br>
            &nbsp;<b>Poliza:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>" . $row['poliza'] . "
    </td>
  </tr>
  <tr>
    <td scope='row' colspan='2' align='center'>
    		&nbsp;<b>Emergencias</b>
            <br>&nbsp;           
    </td>
  </tr>
  <tr>
    <td scope='row'>
           &nbsp;<b>Nombre:&nbsp;</b>" . $row['nombre_emer_1'] . "
           <br>
           &nbsp;<b>Parentesco:&nbsp;</b>" . $row['parentesco_emer_1'] . "
           <br>
           &nbsp;<b>Tel Casa:&nbsp;</b>" . $row['tel_casa_emer_1'] . "
           <br>
           &nbsp;<b>Tel Oficina:&nbsp;</b>" . $row['tel_ofna_emer_1'] . "
           <br>
           &nbsp;<b>Tel Celular:&nbsp;</b>" . $row['tel_cel_emer_1'] . "
    </td>
    <td >
    	    &nbsp;<b>Nombre:&nbsp;</b>" . $row['nombre_emer_2'] . "
           <br>
           &nbsp;<b>Parentesco:&nbsp;</b>" . $row['parentesco_emer_2'] . "
           <br>
           &nbsp;<b>Tel Casa:&nbsp;</b>" . $row['tel_casa_emer_2'] . "
           <br>
           &nbsp;<b>Tel Oficina:&nbsp;</b>" . $row['tel_ofna_emer_2'] . "
           <br>
           &nbsp;<b>Tel Celular:&nbsp;</b>" . $row['tel_cel_emer_2'] . "
    </td>
  </tr>
  <tr>
  	<td>
			&nbsp;<b>Autoriza M&eacute;dico:&nbsp;</b>" . $row['aut_medico'] . "
	</td>		
	<td>
			&nbsp;<b>Autoriza Medicamento:&nbsp;</b>" . $row['aut_medicamento'] . "
	</td>		
  </tr>
</table>";

}
echo "</table>";

//////////////////////////////////////////////
// Aquí mostramos todas las consultas 		//
// que tuvo el paciente en su historial		//
//////////////////////////////////////////////

$sql_consultas= "SELECT 
							* 
						FROM
							expediente_medico
						WHERE 
							paciente = '".$q."' 
							AND
							ciclo = '".$fila[0]."'
						";

echo "	<br>
			<label>Historial de consultas</label>
		<br>
		<br>
";
$result_ = mysql_query($sql_consultas,$link);
echo "<table class='target' width='500'>
			<!-- Mandamos a llamar desde el PHP aquí nuestra función para mostrar los valores de la tabla -->
			<tr>
				<td><b>Exploraci&oacute;n f&iacute;sica</b></td>
				<td><b>Motivo de la Consulta</b></td>
				<td><b>Diagnostico</b></td>
				<td><b>Indicaciones</b></td>
				<td><b>Referido</b></td>
				<td><b>Fecha de Consulta.</b></td>
				
			</tr>
		";

while($row_consultas = mysql_fetch_array($result_)) {
  echo"<tr> 
  			<td>".utf8_encode($row_consultas['exploracion_fisica'])."</td>
			<td>".utf8_encode($row_consultas['motivo_de_la_consulta'])."</td>
			<td>".utf8_encode($row_consultas['diagnostico'])."</td>
			<td>".utf8_encode($row_consultas['indicaciones'])."</td>
			<td>".utf8_encode($row_consultas['referido'])."</td>
			<td>".utf8_encode($row_consultas['fecha_y_hora_de_consulta'])."</td>
			<td>".utf8_encode($row_consultas['ciclo'])."</td>
	   </tr>	
  		";

}

echo "
	</table>
";


?>
</body>
</html>