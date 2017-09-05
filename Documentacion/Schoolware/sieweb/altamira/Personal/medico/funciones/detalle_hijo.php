<link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
<?php
include('../../../config.php');
//require_once("../funciones.php");
mysql_query("SET NAMES 'utf8'");


//$familia = 11205;
$alumno=$_REQUEST['alumno'];
//echo $alumno;


$query = mysql_query("SELECT 
								* 
							FROM 
								expediente_medico LEFT JOIN alumnos ON expediente_medico.paciente = alumnos.alumno
							WHERE
								tipo_de_paciente='1'
								AND
								paciente = '{$alumno}'
							ORDER BY fecha_y_hora_de_consulta DESC
								",$link) or die(mysql_error());
 
echo '
<table width="895">
  <tr>
  	<td class="id_tabla">&nbsp;Paciente &nbsp;</td>
    <td class="id_tabla">&nbsp;Fecha de Colsulta&nbsp;</td>
    <td class="id_tabla">&nbsp;Exploraci&oacute;n F&iacute;sica&nbsp;</td>
    <td class="id_tabla">&nbsp;Diagnostico&nbsp;</td>
    <td class="id_tabla">&nbsp;Receta M&eacute;dica&nbsp;</td>
  </tr>';
						while($elemento_= mysql_fetch_array($query)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";
							
							
							//echo "		<td>&nbsp;".$elemento_['paciente']."</td>";



  
  
  
							$query_alumno = mysql_query("SELECT * FROM
																	alumnos
																WHERE 
																	alumno ='{$alumno}'",$link) or die(mysql_error());
							
							while($alumno_nombre = mysql_fetch_array($query_alumno)){
								echo "<tr>";
								echo "		<td>&nbsp;&nbsp;".$alumno_nombre['nombre']."&nbsp;".$alumno_nombre['apellido_paterno']."&nbsp;".$alumno_nombre['apellido_materno']."&nbsp;&nbsp;</td>";
								//echo "		<td>&nbsp;".$alumno_nombre['familia']."&nbsp;</td>";

							}
							
							echo "		<td>&nbsp;".$elemento_['fecha_y_hora_de_consulta']."</td>";
							echo "		<td>&nbsp;".$elemento_['exploracion_fisica']."</td>";
							echo "		<td>&nbsp;".$elemento_['diagnostico']."</td>";
							////Query para sacar los médicamentos que el alumno recibió ese día
							echo "		<td>&nbsp;";
								$query_ = mysql_query("SELECT * FROM
												 				medicamento_recetado 
															WHERE
																id_expediente_medico = '{$elemento_['id']}'
																",$link) or die(mysql_error());
							
								while($elemento= mysql_fetch_array($query_)){
									
										echo	"&nbsp;M&eacute;dicamento&nbsp;recetado:&nbsp;".$elemento['medicamento'].",&nbsp;Cantidad:&nbsp;".$elemento['cantidad'].",&nbsp;Dosis sugerida:&nbsp;".$elemento['dosis'].",&nbsp;Frecuencia:&nbsp;".$elemento['frecuencia']."<br />";
								
								
								}
							echo "	</td>";
							echo "		</tr>";
						
  

						while($elemento_= mysql_fetch_array($query)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";
							
							echo "<tr>";
							//echo "		<td>&nbsp;".$elemento_['paciente']."</td>";
							
							$query_alumno = mysql_query("SELECT * FROM
																	alumnos
																WHERE 
																	alumno = '{$elemento_['paciente']}'
																	AND
																	familia ='{$familia}'
															",$link) or die(mysql_error());
							
							while($alumno_nombre = mysql_fetch_array($query_alumno)){
								echo "		<td>&nbsp;".$alumno_nombre['nombre']."&nbsp;".$alumno_nombre['apellido_paterno']."&nbsp;".$alumno_nombre['apellido_materno']."</td>";
								//echo "		<td>&nbsp;".$alumno_nombre['familia']."&nbsp;</td>";

							}
							
							echo "		<td>&nbsp;".$elemento_['fecha_y_hora_de_consulta']."</td>";
							echo "		<td>&nbsp;".$elemento_['exploracion_fisica']."</td>";
							echo "		<td>&nbsp;".$elemento_['diagnostico']."</td>";
							////Query para sacar los médicamentos que el alumno recibió ese día
							echo "		<td>&nbsp;";
								$query_ = mysql_query("SELECT * FROM
												 				medicamento_recetado 
															WHERE
																id_expediente_medico = '{$elemento_['id']}'
																",$link) or die(mysql_error());
							
								while($elemento= mysql_fetch_array($query_)){
									
										echo	"&nbsp;M&eacute;dicamento&nbsp;recetado:&nbsp;".$elemento['medicamento'].",&nbsp;Cantidad:&nbsp;".$elemento['cantidad'].",&nbsp;Dosis sugerida:&nbsp;".$elemento['dosis'].",&nbsp;Frecuencia:&nbsp;".$elemento['frecuencia']."<br />";
								
								
								}
							echo "	</td>";
							echo "		</tr>";
						}
						}
?>  