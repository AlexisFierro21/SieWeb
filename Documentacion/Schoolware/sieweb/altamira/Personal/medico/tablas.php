<?php

include('../../config.php');
require_once("funciones.php");
mysql_query("SET NAMES 'utf8'");
$data_collector = array();
//Aquí pondremos el query para obtener los medicamentos disponibles
$stmt = "SELECT * FROM medicamentos";
$query = mysql_query($stmt);

while( $row = mysql_fetch_array($query) ) {
    $data_collector[] = '"'.$row['nombre'].'"';
}

// Implode para los datos
$collection = implode(", ", $data_collector);
//echo $collection;

?> 
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<script type="text/javascript" src="javascript.js"></script>

<form name="formularioAlumnos" method="post" action="untitled.php" >
<div id="wrapper">
<div id="content-box1">

 
	<input type="hidden" value="1" name="tipo_de_paciente" id="tipo_de_paciente">
<br />
    		<label>Fecha de la consulta:</label>
            <br>
           <input name="fecha" type="text" disabled="disabled" id="fecha" value="<?php echo date("Y-m-d H:i:s"); ?>" size="25" readonly="readonly" >
<br />
</div>
<div id="content">
<div id="content-box1">
    	<label>Secci&oacute;n:</label>
    	<br>
		<select name="seccion" id="seccion">
				<option value="" selected>- Seleccione una Secci&oacute;n -</option>
					<?php
                    /*	
						$seccion = dameSeccion();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";
							}
						*/
					?>
		</select>
<br /> 
</div>
<div id="content-box1">
		<label>Grado:</label>
        <br>
		<select name="grado" id="grado" >
				<option value="">- seleccione una secci&oacute;n-</option>
		</select>
<br />
</div>
</div>
    	<label>Grupo:</label>
		<br>
        <select name="grupo" id="grupo">
				<option value="">- seleccione un grado -</option>
		</select>
<br />
    	<label>Alumno:</label>
        <br>
		<select name="alumno" id="alumno">
				<option value="">- seleccione un alumno -</option>
		</select>
<br />
    	   <label>Matr&iacute;cula:</label>
           <input type="text" name="paciente" id="paciente" disabled="disabled" align="middle" required size="16">
<br />
<!-- Aquí empezamos los datos a llenar los datos del historial médico del alumno(a) mediante un frame automático -->    

<div id="txtMedico" class="target" >Cargando informaci&oacute;n, por favor espere un poco...</div>    
    	<input type="button" id="mostrar" name="boton1" value="Mostrar Datos del paciente" onClick="buscarMedico($('#paciente').val)">
	    <input type="button" id="ocultar" name="boton2" value="Ocultar Datos del paciente">
<br />
   		<textarea name="motivo_consulta" id="moltivo_consulta" rows="4" cols="65" required  ></textarea>
        <br>
        <label>
        	Motivo de la consulta
		</label>   
<br />
   		<textarea id="exploracion_fisica" name="exploracion_fisica"  rows="4" cols="65" required ></textarea>
        <br>
        <label>Exploración Física</label> 
<br />
  		<textarea name="diagnostico" id="diagnostico" rows="4" cols="65" required  ></textarea>
  		<br>
   		<label>Diagn&oacute;stico</label>
<br />   		<textarea id="indicaciones" name="indicaciones" rows="4" cols="65" required ></textarea>
        <br>
        <label>Indicaciones</label>
<br />



<table id="tablaMedicamentoAlumnos" name="tablaMedicamentoAlumnos">
        <tbody>
         <tr>
                <td align="left" colspan="6"><input onclick="agregarMedicamento()" type="button" value="Agregar Medicamento"></td>
         </tr>
         <tr>
                <td width="176" class="med_encabezado">Medicamento</td>
                <td width="149" class="med_encabezado">Cantidad</td>
                <td width="125" class="med_encabezado">Dosis</td>
                <td width="125" class="med_encabezado">V&iacute;a de administraci&oacute;n</td>
                <td width="125" class="med_encabezado">Frecuencia</td>
                <td width="125" class="med_encabezado">&nbsp;-&nbsp;</td>
         </tr>
        </tbody>
    </table>
		<input type="submit" class="btn" value="Grabar Consulta" >
   		<input type="reset"  value="Borrar datos" >
</form>

</div>
<div id="wrapper">
	<div id="header">Header</div>
	
	<div id="content-box2"><p>Box 2</p></div>
	<div id="content-box3"><p>Box 3</p></div>
	<div id="content">
		<div id="content-left">Left </div>
		<div id="content-main">main </div>
	</div>
	<div id="footer"></div>
	<div id="bottom"></div>
</div>