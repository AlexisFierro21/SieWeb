<!DOCTYPE html>
<html>
<head>
<style>
body {
    background: #dfe3ee;    
		}
		
 /* Especificamos la poscición del contenedor */
.contenedor{
    width: 600px;
    margin: auto;
    background: #dfe3ee; 
    color: #282828;/* Letras  */
    padding: 0px 0px 50px 50px;
    border-radius: 4px;
    box-shadow: 0 10px 10px 0px rgba(0, 0, 0, 0.8);
	/*text-shadow: 5px 5px 5px #282828;*/
}

/* Titulo para el contenedor o encabezado del documento */ 
 .contenedor .titulo{
    font-size: 3.5ex;
    font-weight: bold;
    margin-left: -10px;
    margin-bottom: 10px;
	color: white;
	text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
}

/* Tamaño de las pestañas y texto contenido */ 
#pestanas {
    float: top;
    font-size: 2.3ex;
    font-weight: bold;
}

/* Margen en las pestañas con respecto al contenedor */
 #pestanas ul{
    margin-left: -37px;
}
 
 /*Orientación de las pestañas*/
#pestanas li{
    list-style-type: none;
    float: left;
    text-align: center;
    margin: 8px 2px -1px -3px;
    background: #cccccc;/* Pestaña seleccionada junto con contenedor principal*/
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    border: 1px solid #3b5998;
    border-bottom: #000000;
    padding: 100px -28px -10px 0px;
}
 
#pestanas a:link{
    text-decoration: none;
	font-family: serif;
	letter-spacing: 2px;
    color: white;
	text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
}
 
/* Aquí especificamos los margenes y propiedades de los contenidos dentro de cada pestaña */ 
/* Así mismo la posición del margen dentro de las pestañas */
#contenidopestanas{
    clear: both;  
    background: #dfe3ee;
    padding: 9px 10px 10px 20px;
    border-radius: 2px;
    border-top-left-radius: 0px;
    border: 1px solid #3b5998;
    width: 550px;
}

textarea {
    resize: none;
	border-radius: 2px;
	border: 1px;
	border-bottom-color:#dfe3ee;
	border:solid 1px #ccc;
}

select{
	width:200px;
	background: #eee url(arrow.png);
	background-position: 280px center;
    background-repeat: no-repeat;
   	padding: 6px;
   	font-size: 12px;
   	border: 0;
   	border-radius: 3px;
   -webkit-appearance: none;
   -webkit-transition: all 0.4s;
   -moz-transition: all 0.4s;
   transition: all 0.4s;
   -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

select:hover{
	background-color: #dfe3ee;
	}

    input { border: 0px; border-radius: 1px;}
    input:-moz-ui-invalid { box-shadow: solid; }
    .submitted input:invalid { background-color: rgba(255,0,0,0.25); }
    .submitted input:valid { background-color: rgba(0,255,0,0.25); }
    .submitted input:invalid + label::after { content: ' X'; }
    .submitted input:valid + label::after { content: ' ✓'; }

input:focus {
border:3px shadow #00BFFF;
background-color:#F0F8FF;
margin-right:10px;
}

submit{
	padding:5px 15px; background:#ccc; border:0 none;
	cursor:pointer;
	-webkit-border-radius: 5px;
	border-radius: 5px; 
	}

input[type*="button"],[type*="submit"],[type*="reset"] {
  border-radius: 3px;
  color: white;
  display: inline-block;
  font: bold 12px/12px HelveticaNeue, Arial;
  padding: 8px 11px;
  text-decoration: none;
  background: #3b5998;
  border-color: #dedede #d8d8d8 #d3d3d3;
  box-shadow: 0 1px 1px #eaeaea, inset 0 1px 0 #fbfbfb;
  cursor:pointer; 
  }

input[type*="button"]:active{
	top:4px;
    /*bajamos el tamaño de la sombra para conseguir el efecto de profundidad*/
    -moz-box-shadow: 0px 1px 0px 0px rgb(0, 105, 202);
    -webkit-box-shadow: 0px 1px 0px 0px rgb(0, 105, 202);
    box-shadow: 0px 1px 0px 0px rgb(0, 105, 202);	
}

p[class*="class_css"]{
	 	background-color: #f7f7f7;
 		max-width: 600px;
 		background-size: 600px 600px;
 		background-repeat: no-repeat; 
		background-position: 100%;
}

input[class*="medicamento"]{
	width:100px;
	resize: none;
	background-position: 180px center;
    background-repeat: no-repeat;
   	padding: 8px;
   	font-size: 12px;
   	border: 0;
   	border-radius: 3px;
   -webkit-appearance: none;
   -webkit-transition: all 0.4s;
   -moz-transition: all 0.4s;
   transition: all 0.4s;
   -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

select[class*="medicamento"]{
	width:100px;
	resize: none;
	background-position: 180px center;
    background-repeat: no-repeat;
   	padding: 8px;
   	font-size: 12px;
   	border: 0;
   	border-radius: 3px;
   -webkit-appearance: none;
   -webkit-transition: all 0.4s;
   -moz-transition: all 0.4s;
   transition: all 0.4s;
   -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

label{
 background-color: #f7f7f7;
 max-width: 600px;
 background-size: 600px 600px;
 background-repeat: no-repeat;
}

p a {
    display: inline-block;
    text-decoration: underline;
    position: relative;
    font-family: monospace;
    color: #f5f5f5;
    background: #999;
}

p a:hover {background: #444; color: #f5f5f5;}
p a img {display: none; }
p a:hover img {
    display: block;
    position: absolute;
    -moz-box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
    -webkit-box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
    -o-box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
    box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
}

/* Código para el DIV replegable */
 #divMedMuestra{
	background-color:#CCCCCC;
	width:85%;
	border:1px solid #AAAAAA;
	padding:0 10px;
}

#cont1{
	background-color:#EEEEEE;
	width:85%;
	border-bottom:1px solid #AAAAAA;
	border-left:1px solid #AAAAAA;
	border-right:1px solid #AAAAAA;
	padding: 10px;
	display:none;
}

#divMedOculta{
	background-color:#CCCCCC;
	width:85%;
	border:1px solid #AAAAAA;
	padding:0 10px;
}

#cont2{
	background-color:#EEEEEE;
	width:85%;
	border-bottom:1px solid #AAAAAA;
	border-left:1px solid #AAAAAA;
	border-right:1px solid #AAAAAA;
	padding: 10px;
	display:none;
}

.responsiveSelectContainer select.responsiveMenuSelect, select.responsiveMenuSelect{display:none;}
		@media (max-width: 960px) 
			{
			.responsiveSelectContainer{border:none !important;background:none !important;box-shadow:none !important;}
			.responsiveSelectContainer ul, ul.responsiveSelectFullMenu, #megaMenu ul.megaMenu.responsiveSelectFullMenu{display: none !important;}
			.responsiveSelectContainer select.responsiveMenuSelect, select.responsiveMenuSelect { display: inline-block; width:100%;}
			}	
			
/* El Autocomplete le especificamos el fondo de color */			
.ui-state-hover, 
.ui-widget-content 
.ui-state-hover, 
.ui-widget-header, 
.ui-state-hover, 
.ui-state-focus, 
.ui-widget-content, 
.ui-state-focus, 
.ui-widget-header, 
.ui-state-focus 
     { 
       border: 1px solid #999999/*{borderColorHover}*/; 
       background: #dadada /*{bgColorHover}*//*{bgImgUrlHover}*/ 10%/*{bgHoverXPos}*/ 10%/*{bgHoverYPos}*/ repeat-x/*{bgHoverRepeat}*/;   
       font-weight: normal/*{fwDefault}*/; color: #212121/*{fcHover}*/;
	   max-width: 200px;
     }

.ui-state-hover a, 
.ui-state-hover a:hover, 
.ui-state-hover a:link, 
.ui-state-hover a:visited 
     { 
       color: #212121/*{fcHover}*/; 
       text-decoration: none; 
     }
	 
table { 
  border-collapse: collapse;
  border-spacing:  3px;
}

tbody { 
  border-collapse: collapse;
  border-spacing:  3px;
}

td {
  	background: #f7f7f7;
  	font-family:Arial, Helvetica, sans-serif;
  	font-size: 12px;
  	border-radius: 2px;
	border-bottom-color:#dfe3ee;
	border:solid 1px #ccc; 
}

td[class*="med_encabezado"]{
  background: #dfe3ee;
  font-family:Arial, Helvetica, sans-serif;
  font-size: 12px;
  border-radius: 2px;
  text-align: center;
}

th{
	width:50px;
		
	
}



iframe{
	border:0px;
	}
	
</style>
</head>
<body>
<?php
include('../../config.php');
$tipo_reporte='';	
$fecha_ini=$_REQUEST['fecha_ini'];
$fecha_fin=$_REQUEST['fecha_fin'];
$tipo_reporte=$_REQUEST['tipo_reporte'];
//echo $tipo_reporte.$fecha_ini.$fecha_fin;

mysql_query("SET NAMES 'utf8'");

///Aquí es para poner los medicamentos que los haga de manera dinámicos
if(isset($_REQUEST[''])){


}


if( isset($_REQUEST['tipo_reporte']) ) {
	//// Alumno
	if($tipo_reporte == '1'){

echo "<br />
								<table id='Top10Alumno' name='Top10Alumno'>
									  <tr>
  										<th >&nbsp;Consultas&nbsp;</th>
    									<th>&nbsp;Paciente&nbsp;</th>
    									<th >&nbsp;Nombre del Paciente&nbsp;</th>
    									<th>&nbsp;Grado y Grupo&nbsp;</th>
    									<th>&nbsp;Secci&oacute;n&nbsp;</th>
  									</tr>
		";
$sql="SELECT 
										paciente, 
        								count(expediente_medico.id) as TOP
									FROM 
										expediente_medico
									WHERE
										tipo_de_paciente = 1
        								AND
        								fecha_y_hora_de_consulta between '".$fecha_ini."' and '".$fecha_fin."'
									GROUP BY 
										paciente
									ORDER BY 
										TOP desc
									LIMIT 10
									";	
$result = mysql_query($sql,$link);

while($elemento_= mysql_fetch_array($result)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";

							echo "<tr>";
							echo "		<td>&nbsp;".$elemento_['TOP']."&nbsp;</td>";
							echo "		<td>&nbsp;".$elemento_['paciente']."&nbsp;</td>";
							
							//Aquí ponemos los datos de los alumnos
								$query_ = mysql_query("SELECT * FROM
												 				alumnos 
															WHERE
																alumno = '{$elemento_['paciente']}'
																",$link) or die(mysql_error());
							
								while($elemento= mysql_fetch_array($query_)){
									
										echo "		<td>&nbsp;".$elemento['apellido_paterno']." ".$elemento['apellido_materno'].", ".$elemento['nombre']."&nbsp;</td>";
										echo "		<td>&nbsp;".$elemento['grado']." - ".$elemento['grupo']."&nbsp;</td>";
										
										
										$query_seccion = mysql_query("SELECT distinct
																	 			nombre 
																			FROM 
																				secciones 
																			WHERE 
																				seccion = '{$elemento['seccion']}'
																			",$link) or die(mysql_error());
													while($elemento_seccion=mysql_fetch_array($query_seccion)){
													echo "		<td>&nbsp;".$elemento_seccion['nombre']."&nbsp;</td>";
													}
								}
							
							echo "</tr>
							";
						}
							echo "</table>
							";
	}
	/// Personal
	elseif($tipo_reporte == '2'){
		echo "<br />
								<table id='Top10Personal' name='Top10Personal'>
									  <tr>
  										<th >&nbsp;Consultas&nbsp;</th>
    									<th>&nbsp;Paciente&nbsp;</th>
    									<th >&nbsp;Nombre del Paciente&nbsp;</th>
  									   </tr>
		";
$sql="SELECT 
										paciente, 
        								count(expediente_medico.id) as TOP
									FROM 
										expediente_medico
									WHERE
										tipo_de_paciente = 2
        								AND
        								fecha_y_hora_de_consulta between '".$fecha_ini."' and '".$fecha_fin."'
									GROUP BY 
										paciente
									ORDER BY 
										TOP desc
									LIMIT 10
									";	
$result = mysql_query($sql,$link);

while($elemento_= mysql_fetch_array($result)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";

							echo "<tr>";
							echo "		<td>&nbsp;".$elemento_['TOP']."&nbsp;</td>";
							echo "		<td>&nbsp;".$elemento_['paciente']."&nbsp;</td>";
							
							//Aquí ponemos los datos de los alumnos
								$query_ = mysql_query("SELECT * FROM
												 				personal 
															WHERE
																empleado = '{$elemento_['paciente']}'
																",$link) or die(mysql_error());
							
								while($elemento= mysql_fetch_array($query_)){
									
										echo "		<td>&nbsp;".$elemento['apellido_paterno']." ".$elemento['apellido_materno'].", ".$elemento['nombre']."&nbsp;</td>";

										
							
										echo "</tr>
										";
									}
							}
									echo "</table>
							";
							
	}
/// Padre o Madre
elseif($tipo_reporte == '3'){
		echo "<br />
								<table id='Top10Familiares' name='Top10Familiares'>
									  <tr>
  										<th >&nbsp;Consultas&nbsp;</th>
    									<th>&nbsp;Familia&nbsp;</th>
										<th>&nbsp;Nombre de la Familia&nbsp;</th>
										<th >&nbsp;Nombre del Paciente&nbsp;</th>
										<th>&nbsp;Alumno&nbsp;</th>    									
    									<th>&nbsp;Grado y Grupo&nbsp;</th>
    									<th>&nbsp;Secci&oacute;n&nbsp;</th>
  									</tr>
		";
		
		
$sql="SELECT 
										paciente, 
        								count(expediente_medico.id) as TOP
									FROM 
										expediente_medico
									WHERE
										tipo_de_paciente = 3
        								AND
        								fecha_y_hora_de_consulta between '".$fecha_ini."' and '".$fecha_fin."'
									GROUP BY 
										paciente
									ORDER BY 
										TOP desc
									LIMIT 10
									";	
$result = mysql_query($sql,$link);

while($elemento_= mysql_fetch_array($result)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";

							echo "<tr>";
							echo "		<td>&nbsp;".$elemento_['TOP']."&nbsp;</td>";
							echo "		<td>&nbsp;".$elemento_['paciente']."&nbsp;</td>";
							$hijo_s = "<td>&nbsp;";
							$nombre_familia = "";
							$paciente = "";
							$grado_grupo = "<td>&nbsp;";
							$seccion = "<td>&nbsp;";
								//echo "		<td>&nbsp;".$elemento['familia']."&nbsp;</td>";
							
							//Aquí ponemos los datos de los alumnos
								$query_ = mysql_query("SELECT DISTINCT * FROM
												 				alumnos 
															WHERE
																familia = '{$elemento_['paciente']}'
																",$link) or die(mysql_error());
							
								
								while($elemento= mysql_fetch_array($query_)){
									
										
										$nombre_familia = "<td>&nbsp;".$elemento['apellido_paterno']." ".$elemento['apellido_materno']."&nbsp;</td>";
										$paciente = "		<td>								&nbsp;</td>";
										$hijo_s.= "&nbsp;".$elemento['apellido_paterno']." ".$elemento['apellido_materno'].", ".$elemento['nombre']."&nbsp;";
										$grado_grupo.= "&nbsp;".$elemento['grado']." - ".$elemento['grupo']."&nbsp;";
										
										$query_seccion = mysql_query("SELECT distinct
																	 			nombre 
																			FROM 
																				secciones 
																			WHERE 
																				seccion = '{$elemento['seccion']}'
																			",$link) or die(mysql_error());
													while($elemento_seccion=mysql_fetch_array($query_seccion)){
													$seccion.= "		&nbsp;".$elemento_seccion['nombre']."&nbsp;";
													
													}
													
													
													
								}
							//$hijo_s.="</td>&nbsp;"; 
													//$nombre_familia = "";
													$hijo_s.= "&nbsp;</td>";
													//$paciente 
													$grado_grupo.= "&nbsp;</td>";
													echo $nombre_familia.$paciente.$hijo_s.$grado_grupo;
													echo "	<td>
																&nbsp;$seccion&nbsp;
															</td>
														</tr>
													";
									}
						echo "</table>
							";	
						}
									
// Top 10 Médicamentos
elseif($tipo_reporte == '4'){
		echo "<br />
								<table id='Top10Medicamentos' name='Top10Medicamentos'>
									  <tr>
  										<th >&nbsp;Veces Recetado&nbsp;</th>
    									<th>&nbsp;M&eacute;dicamento&nbsp;</th>
  									</tr>
		";


$result=mysql_query("SELECT 
        			 		medicamento_recetado.medicamento AS med_recetado,
        					count(expediente_medico.id) as TOP
   						FROM 
							medicamento_recetado left join expediente_medico 
							on medicamento_recetado.id_expediente_medico= expediente_medico.id
   						WHERE
							expediente_medico.fecha_y_hora_de_consulta BETWEEN '{$fecha_ini}' and '{$fecha_fin}'
   						GROUP BY 
							medicamento_recetado.medicamento
						ORDER BY 
							TOP desc
						LIMIT 10
						",$link) or die(mysql_error());

while($elemento_medicamentos=mysql_fetch_array($result)){
							echo "<tr>	";
							echo "		<td>&nbsp;".$elemento_medicamentos['TOP']."&nbsp;</td>";
							echo "		<td>&nbsp;".$elemento_medicamentos['med_recetado']."&nbsp;</td>";
							echo "</tr>	";
						}
			echo "</table>
			<input type='button' onclick='tableToExcel('Top10Medicamentos', 'Top10Medicamentos')' value='Exportar a Excel'>";
	}


//// Top 10 Otros Pacientes
elseif($tipo_reporte == '5'){

		echo "<br />
								<table id='Top10Otros' name='Top10Otros'>
									  <tr>
  										<th >&nbsp;Consultas&nbsp;</th>
    									<th>&nbsp;Nombre&nbsp;</th>
										<th>&nbsp;Referido Por:&nbsp;</th>
										<th>&nbsp;Tipo de Referido</th>
  									</tr>
		";
		
$sql="SELECT 
										paciente, 
        								count(expediente_medico.id) as TOP
									FROM 
										expediente_medico
									WHERE
        								fecha_y_hora_de_consulta between '".$fecha_ini."' and '".$fecha_fin."'
										and tipo_de_paciente = '4'
									GROUP BY 
										paciente
									ORDER BY 
										TOP desc
									LIMIT 10
									";	
$result = mysql_query($sql,$link);

while($elemento_= mysql_fetch_array($result)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";

							echo "<tr>";
							echo "		<td>&nbsp;".$elemento_['TOP']."&nbsp;</td>";	
							echo "		<td>&nbsp;".$elemento_['paciente']."&nbsp;</td>";	
							
							$sql_query_ref ="SELECT *
																	 		FROM 
																				medico_consulta_otros
																			WHERE 
																				nombre = '".$elemento_['paciente']."'";
								//echo $sql_query_ref;		
								
							$query_otros = mysql_query($sql_query_ref,$link) or die(mysql_error());
							
													while($elemento_otros=mysql_fetch_array($query_otros)){
													
													echo  "<td>&nbsp;".$elemento_otros['referido']."&nbsp;</td>";
														
														if($elemento_otros['tipo_referido']== '1'){
															echo  "<td>&nbspFamiliar&nbsp;</td>";
														}
														elseif($elemento_otros['tipo_referido']== '2'){
															echo  "<td>&nbspPersonal&nbsp;</td>";
														}
														else{
															echo  "<td>&nbspExterno&nbsp;</td>";
														}	
													echo "</tr>";
													}
							
									echo "</table>
			";
						}
			
	}	

//// Top 10 Pacientes
elseif($tipo_reporte == '6'){
		echo "<br />
								<table id='Top10Causas' name='Top10Causas'>
									  <tr>
  										<th >&nbsp;Consultas&nbsp;</th>
    									<th>&nbsp;Diagn&oacute;stico&nbsp;</th>
    	 							  </tr>
		";
$sql="SELECT 
										diagnostico, 
        								count(expediente_medico.id) as TOP
									FROM 
										expediente_medico
									WHERE
        								fecha_y_hora_de_consulta between '".$fecha_ini."' and '".$fecha_fin."'
									GROUP BY 
										diagnostico
									ORDER BY 
										TOP desc
									LIMIT 10
									";	
$result = mysql_query($sql,$link);

while($elemento_= mysql_fetch_array($result)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";

							echo "<tr>";
							echo "		<td>&nbsp;".$elemento_['TOP']."&nbsp;</td>";
							echo "		<td>&nbsp;".$elemento_['diagnostico']."&nbsp;</td>";							
							echo "</tr>";
						}

						echo "</table>
			";
}

//// Top 10 Consultas
elseif($tipo_reporte == '7'){
		echo "<br />
								<table id='Consultas' name='Consultas' width='480'>
									  <tr>
									  	<th class='med_encabezado'>&nbsp; TP &nbsp;</th>
  										<th class='med_encabezado'>&nbsp; Paciente &nbsp;</th>
    									<th class='med_encabezado'>&nbsp; Datos de Paciente &nbsp;</th>
										<th class='med_encabezado'>&nbsp; Motivo de la consulta &nbsp;</th>
										<th class='med_encabezado'>&nbsp; Exploración Física &nbsp;</th>
										<th class='med_encabezado'>&nbsp; Diagnostico &nbsp;</th>
										<th class='med_encabezado'>&nbsp; Fecha de la Consulta &nbsp;</th>
										
    	 							  </tr>
		";
$sql="SELECT 
										*
									FROM 
										expediente_medico
									WHERE
        								fecha_y_hora_de_consulta between '".$fecha_ini."' and '".$fecha_fin."'
									";	
$result = mysql_query($sql,$link);

while($elemento_= mysql_fetch_array($result)){//Consultas


							
								///Nombre Paciente Tipo Alumno
								if($elemento_['tipo_de_paciente']=='1'){//Tipo Nombre Paciente
										echo "		<td><font size=1>&nbsp;A&nbsp;</font></td>";
										//Nombre Paciente

									
									///Nombre Paciente Tipo Familia
									}elseif($elemento_['tipo_de_paciente']=='3'){//Tipo Familia
										echo "		<td><font size=1>&nbsp;F&nbsp;</font></td>";
									
									///Nombre Paciente Tipo Personal
									}elseif($elemento_['tipo_de_paciente']=='2'){
										echo "		<td><font size=1>&nbsp;P&nbsp;</font></td>";

									/// Nombre Paciente Tipo Otros
									}elseif($elemento_['tipo_de_paciente']=='4'){
										echo "		<td><font size=1>&nbsp;O&nbsp;</font></td>";
									}
									
									
							echo "		<td><font size=1>&nbsp;".$elemento_['paciente']."&nbsp;</font></td>";
							//// Aquí hacemos la busqueda del tipo del paciente en cada caso... ///
							
				
							//// Nombre del paciente
							if($elemento_['tipo_de_paciente']=='1'){//Tipo Alumno
							
								$query_nombre_paciente= mysql_query("SELECT * FROM alumnos WHERE alumno = '".$elemento_['paciente']."'");
									while($query_nombre_p=mysql_fetch_array($query_nombre_paciente)){//Nombre Paciente
										echo "<td><font size=1>&nbsp;".$query_nombre_p['nombre']." ".$query_nombre_p['apellido_paterno']." ".$query_nombre_p['apellido_materno']."</font></td>";
									}
							}elseif($elemento_['tipo_de_paciente']=='3'){//Tipo Familia
							$rest = substr($elemento_['padre_madre'], -1, 1); 
										if($rest == '1'){
											$pm='p';
										}
										else{
											$pm='m';
										}

$query_nombre_paciente= mysql_query("SELECT nombre_".$pm."adre AS nombre, apellido_paterno_".$pm."adre AS Apellido_Paterno, apellido_materno_".$pm."adre AS Apellido_Materno FROM familias WHERE familia = '".$elemento_['paciente']."'");
									while($query_nombre_p=mysql_fetch_array($query_nombre_paciente)){//Nombre Paciente
										echo "<td><font size=1>&nbsp;".$query_nombre_p['nombre']." ".$query_nombre_p['Apellido_Paterno']." ".$query_nombre_p['Apellido_Materno']."</font></td>";
							
							}
				
							}elseif($elemento_['tipo_de_paciente']=='2'){//Tipo Personal
									$query_nombre_paciente= mysql_query("SELECT * FROM personal WHERE empleado = '".$elemento_['paciente']."'");
									
									while($query_nombre_p=mysql_fetch_array($query_nombre_paciente)){
																				
										echo "<td><font size=1>&nbsp;".$query_nombre_p['nombre']." ".$query_nombre_p['apellido_paterno']." ".$query_nombre_p['apellido_materno']."&nbsp;</font></td>";
										
									}
									
							}elseif($elemento_['tipo_de_paciente']=='4'){
								
								$query_nombre_paciente= mysql_query("SELECT * FROM medico_consulta_otros WHERE id = '".$elemento_['id']."'");
									
									while($query_nombre_p=mysql_fetch_array($query_nombre_paciente)){
																				
										echo  "<td>&nbsp;".$query_nombre_p['nombre']."&nbsp;</td>";
									}
							}
							
							
							
							echo "		<td><font size=1>&nbsp;".$elemento_['exploracion_fisica']."&nbsp;</font></td>";
							echo "		<td><font size=1>&nbsp;".$elemento_['diagnostico']."&nbsp;</font></td>";
							echo "		<td><font size=1>&nbsp;".$elemento_['indicaciones']."&nbsp;</font></td>";
							echo "		<td><font size=1>&nbsp;".$elemento_['fecha_y_hora_de_consulta']."&nbsp;</font></td>";							
							echo "</tr>";
						}

						echo "</table>
							 ";
	}
}
?>




</body>
</html>