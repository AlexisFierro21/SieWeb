<?php
require_once("funciones.php");

///Aquí obtenemos los grados en base a la sección previamente seleccionados
if(isset($_POST['seccion'])){
	
	$grado = dameGrado($_POST['seccion']);
	
	$html = "<option value=''>- Seleccione un Grado -</option>";
	foreach($grado as $indice => $registro){
		$html .= "<option value='".$registro['grado']."'>".$registro['nombre']."</option>";
	}
	
	$respuesta = array("html"=>$html);
	echo json_encode($respuesta);
}

if(isset($_POST['grado'])){
	
	$grupo= dameGrupo($_POST['grado']);
	
	$html = "<option value=''>- Seleccione un Grupo -</option>";
	foreach($grupo as $indice => $registro){
		$html .= "<option value='".$registro['grupo']."'>".$registro['nombre']."</option>";
	}
	
	$respuesta = array("html"=>$html);
	echo json_encode($respuesta);
}


if(isset($_POST['grupo'])){
	
	$alumno= dameAlumno($_POST['grupo']);
	
	$html = "<option value=''>- Seleccione un Alumno -</option>";
	foreach($alumno as $indice => $registro){
		$html .= "<option value='".$registro['alumno']."'>".$registro['nombre']."&nbsp;".$registro['apellido_paterno']."&nbsp;".$registro['apellido_materno']."</option>";
	}
	
	$respuesta = array("html"=>$html);
	echo json_encode($respuesta);
}


if(isset($_POST['paciente'])){
		$paciente = intval($_GET['paciente']);
		
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
					WHERE alumno = '".$paciente."'";
					
$result = mysqli_query($conexion,$sql);

echo "<table class='target' width='500'>
			<!-- Mandamos a llamar desde el PHP aquí nuestra función para mostrar los valores de la tabla -->
		";
while($row = mysql_fetch_array($result)) {
  echo" 
		

 <tr>
    <td scope='col' colspan='2' >
    		&nbsp;<b>M&eacute;dico:&nbsp;</b><input type='text' disabled id='doctor' name='doctor' size='65' value='" . utf8_encode($row['doctor']) . ">
            <br>
    		&nbsp;<b>Telefono M&eacute;dico:&nbsp;</b><input type='text' disabled id='telefono_doctor' name='telefono_doctor' value='" . $row['telefono_doctor'] . "> 
          </td>
  </tr>
  <tr>
    <td scope='row' colspan='2'>
    		&nbsp;<b>Tipo sangre:&nbsp;</b><input disabled id='tipo_sangre' name='tipo_sangre' size='26' value='" . $row['tipo_sangre'] . ">
            <br>
            &nbsp;<b>Alergias:&nbsp;</b>
            <br>
            <textarea type='text' disabled id='alergias' name='alergias' rows='3' cols='33' value='" . utf8_encode($row['alergias']) . "></textarea>
            <br>
            &nbsp;<b>Usa Lentes:&nbsp;</b> <input type='checkbox' name='usa_lentes' id='usa_lentes'  <? echo 'checked'; // Aquí pondremos la opción como checked en caso de que sea true la respuesta ?> disabled> 
            <br>
            &nbsp;<b>Clinica:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><input type='text' disabled id='clinica' name='clinica' size='26' value='" . $row['clinica'] . ">
            <br>
            &nbsp;<b>Telefono clinica:&nbsp;&nbsp;&nbsp;&nbsp;</b><input type='text' disabled id='telefono_clinica' name='telefono_clinica' value='" . $row['telefono_clinica'] . ">
    </td>
  </tr>
  <tr>
    <td scope='row' colspan='2' >
    		&nbsp;<b>Aseguradora:&nbsp;</b><input type='text' disabled id='aseguradora' name='aseguradora' size='25' value='" . $row['aseguradora'] . ">
            <br>
            &nbsp;<b>Poliza:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><input type='text' disabled id='poliza' name='poliza' size='25' value='" . $row['poliza'] . ">
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
           &nbsp;<b>Nombre:&nbsp;</b>
           <br>
           <input type='text' disabled id='nombre_emer_1' name='nombre_emer_1' size='35' value='" . $row['nombre_emer_1'] . ">
           <br>
           &nbsp;<b>Parentesco:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><input type='text' disabled id='parentesco_emer_1' name='parentesco_emer_1' size='15' value='" . $row['parentesco_emer_1'] . ">
           <br>
           &nbsp;<b>Tel Casa:&nbsp;&nbsp;&nbsp;&nbsp;</b><input type='text' disabled id='tel_casa_emer_1' name='tel_casa_emer_1' value='" . $row['tel_casa_emer_1'] . ">
           <br>
           &nbsp;<b>Tel Oficina:&nbsp;</b><input type='text' disabled id='tel_ofna_emer_1' name='tel_ofna_emer_1' value='" . $row['tel_ofna_emer_1'] . ">
           <br>
           &nbsp;<b>Tel Celular:&nbsp;</b><input type='text' disabled id='tel_cel_emer_1' name='tel_cel_emer_1' value='" . $row['tel_cel_emer_1'] . ">
    </td>
    <td >
    	    &nbsp;<b>Nombre:&nbsp;</b>
            <br>
            <input type='text' disabled id='nombre_emer_2' name='nombre_emer_2' size='35' value='" . $row['nombre_emer_2'] . ">
           <br>
           &nbsp;<b>Parentesco:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><input type='text' disabled id='parentesco_emer_2' name='parentesco_emer_2' size='15' value='" . $row['parentesco_emer_2'] . ">
           <br>
           &nbsp;<b>Tel Casa:&nbsp;&nbsp;&nbsp;&nbsp;</b><input type='text' disabled id='tel_casa_emer_2' name='tel_casa_emer_2' value='" . $row['tel_casa_emer_2'] . ">
           <br>
           &nbsp;<b>Tel Oficina:&nbsp;</b><input type='text' disabled id='tel_ofna_emer_2' name='tel_ofna_emer_2' value='" . $row['tel_ofna_emer_2'] . ">
           <br>
           &nbsp;<b>Tel Celular:&nbsp;</b><input type='text' disabled id='tel_cel_emer_2' name='tel_cel_emer_2' value='" . $row['tel_cel_emer_2'] . ">
    </td>
  </tr>
</table>";

}
echo "</table>";

}


///Aquí buscamos los datos de familia
if(isset($_POST['familia'])){
	
	$familia= damePadreMadre($_POST['familia']);
	
	$html = "<option value=''>- Seleccione Padre / Madre -</option>";
	foreach($familia as $indice => $registro){
		$html .= "<option value='".$registro['familia']."-2'>".$registro['apellido_paterno_madre']."&nbsp;".$registro['apellido_materno_madre'].",&nbsp;".$registro['nombre_madre']."</option>";
		$html .= "<option value='".$registro['familia']."-1'>".$registro['apellido_paterno_padre']."&nbsp;".$registro['apellido_materno_padre'].",&nbsp;".$registro['nombre_padre']."</option>";		
	}
	
	$respuesta = array("html"=>$html);
	echo json_encode($respuesta);
}



if(isset($_POST['Top10Alumnos'])){
	$html = '';
	
	
}

/*
if(isset($_POST['referido'])){
	$referido_tipo = $_POST['referido'];
	$referido = dameReferido($referido_tipo);
	
	if ($referido_tipo == '3'){
		$html = "<label>Referido por:</label><BR><input name='referido_otro' id='referido_otro'>";
	echo json_encode($html);
	}
	else{
		$html = "<label>Referido por:</label><BR><select name='referido_otro' id='referido_otro'><option value=''>- Seleccione quien refiere -</option>";
		foreach($referido as $indice => $registro){
			$html .="<option value='".$registro['nombre']."'>".$registro['nombre']."</option>";
			}
		$respuesta = array("html"=>$html);
		echo json_encode($respuesta);
		}
}
*/

if(isset($_POST['personal'])){		
$referido = damePersonal($_POST['personal']);

$html = "<option value=''>- Seleccione un empleado -</option>";
	foreach($referido as $indice => $registro){
			$html.="	<option value='".$registro['id']."'>".$registro['nombre']."</option>";
		}
		$respuesta = array("html"=>$html);
		echo json_encode($respuesta);
}


?>