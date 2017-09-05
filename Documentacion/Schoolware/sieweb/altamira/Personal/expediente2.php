<?php
$alumnoN = $_REQUEST['alumnoN'];
$alumno = $_REQUEST['alumno'];
$tipo = $_REQUEST['tipo'];

/// Creamos las funciones en base a la vista del preceptor/administrador
if($tipo == 'P'){
		$datos_alumno='formularios.php?tabla=expediente_datos&tipo=alumnos&id=1&alumno='.$alumno.'&agr_modif_borr=modificar';
		$datos_familia='formularios.php?tabla=expediente_datos&tipo=familias&id=1&alumno='.$alumnoN.'&agr_modif_borr=modificar';
		$fip='familias_cursos.php?alumno='.$alumno.'&alumnoN='.$alumnoN;
		$boleta='../boletas.php?alumnoN='.$alumnoN;
		$captura_preceptor='captura_preceptoria2.php?alumno='.$alumno;
		$acuerdos='acuerdos.php?alumno='.$alumno;
		$expediente_medico='medico/funciones/detalle_hijo.php?alumno='.$alumno.'&alumnoN='.$alumnoN;
	}else if($tipo == 'A'){
		$datos_alumno='formularios.php?tabla=expediente_datos&tipo=alumnos&administra_test=S&id=1&agr_modif_borr=modificar';
		$datos_familia='formularios.php?tabla=expediente_datos&tipo=familias&administra_test=S&id=1&agr_modif_borr=modificar';
		$fip='familias_cursos.php';
		$boleta='buscaAlumno.php?muestra=boleta';
		$captura_preceptor='';
		$acuerdos='';
		$expediente_medico='medico/funciones/detalle_hijo.php';
}  

$html="
<form id='fr' name='fr' action='expediente3.php' method='POST'  accept-charset='UTF-8'>	
	<div class='tabs'>
   		<div class='tab'>
       		<input type='radio' id='tab-1' name='tab-group-1' checked>
       		<label for='tab-1'>Datos Alumno</label>
       			<div id='d1' class='content'>
					<iframe src='$datos_alumno' width='100%' height='100%' ></iframe>
				</div>
   		</div>
   		<div class='tab'>
       		<input type='radio' id='tab-2' name='tab-group-1'>
       		<label for='tab-2'>Datos Familia</label>
       			<div id='d2' class='content'> 
					<iframe src='$datos_familia' width='100%' height='100%' ></iframe>
				</div>
   		</div>
    	<div class='tab'>
       		<input type='radio' id='tab-3' name='tab-group-1'>
       		<label for='tab-3'>Fip</label>
       		<div class='content'> 
				<iframe src='$fip' width='100%' height='100%' ></iframe>
			</div>
   		</div>
   		<div class='tab'>
       		<input type='radio' id='tab-4' name='tab-group-1'>
       		<label for='tab-4'>Calificaciones</label>
       			<div class='content'>
					<iframe src='$boleta' width='100%' height='100%' ></iframe>
				</div>
   		</div>
   		<div class='tab'>
       		<input type='radio' id='tab-5' name='tab-group-1'>
       		<label for='tab-5'>Captura de Preceptor</label>
       		<div class='content'> 
				<iframe src='$captura_preceptor' width='100%' height='100%' ></iframe>
			</div>
   		</div>
   		<div class='tab'>
       		<input type='radio' id='tab-6' name='tab-group-1'>
       		<label for='tab-6'>Acuerdos</label>
       			<div class='content'>
       				<iframe src='$acuerdos' width='100%' height='100%' ></iframe>
       			</div>
   		</div>
   		<!--<div class='tab'>
       		<input type='radio' id='tab-7' name='tab-group-1'>
       		<label for='tab-7'>Expediente M&eacute;dico</label>
       			<div class='content'>
       				<iframe src='$expediente_medico' width='100%' height='100%' ></iframe>
       			</div>
   		</div>-->
   		<div class='tab'>
       		<input type='radio' id='tab-8' name='tab-group-1'>
       		<label for='tab-8'>Test</label>
       			<div class='content'>
       			
       			</div>
   		</div>
	</div>
	
</form>	"; 
  
  $respuesta = array("html"=>$html);
	echo json_encode($respuesta);
	?>
 