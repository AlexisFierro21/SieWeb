<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	 <script src="medico/jquery-1.10.2.min.js"></script>
     <script src="medico/jquery-ui.js"></script>
     <script type="text/javascript" src="medico/jquery-ui-1.8.1.custom.min.js"></script>
	 <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
</head>
<body>
	<style>
	
	table{
		border-style: solid;
    	border-width: 1px;
		border-radius: 3px;
		font: Arial, 11px;
	}
	
	th{
		background: #3b5998;
		color: #ffffff;
		padding-left: 10px;
		padding-right: 10px;
	}
	
	td{
		background: #dfe3ee;
		padding-left: 10px;
		padding-right: 10px;
	}
	</style>
<?
include('../config.php');

/// Modificar esta línea para cada colegio ///
$colegio = "liceo";

mysql_query("SET NAMES 'utf8'");
$surveys = mysql_query("SELECT 
								* 
							FROM 
								test".$colegio."_surveys, 
								test".$colegio."_surveys_languagesettings 
							WHERE 
								sid = surveyls_survey_id
								AND
								active = 'Y'".mysql_error());
								
								
	//echo "SELECT * FROM test".$colegio."_surveys, test".$colegio."_surveys_languagesettings WHERE sid = surveyls_survey_id AND active = 'Y'";							
?>

<select name="test" id="test">
		<option value=""></option>
<?
while($row_surveys = mysql_fetch_array($surveys)) {
			echo 	"<option value='".$row_surveys['sid']."'>".$row_surveys['surveyls_title']."</option>";
		}
?>
</select>
<br />
<br />
<table name="survey" id="survey">
	<tbody>
		<tr>
			<th>Alumno</th>
			<th>Nombre</th>
			<th>Grado Y Grupo</th>
			<th>Secci&oacute;n</th>
			<th>Token</th>
			<th>Link Encuesta</th>
			<th>Terminado</th>
		</tr>
	</tbody>
</table>
<script>
$("#test").on("change", buscarEncuestados);	
	
function buscarEncuestados(){
	
		$test = $("#test").val();
		$("#survey").find("tr:gt(0)").remove();
        $('survey').children( 'tr:not(:first)' ).remove();

	$.ajax({
			type: "POST",
			url: "datos.php",
			data: "test="+$test
		})
		.done(function(json) {
			json = $.parseJSON(json)			
			for(var i=0;i<json.length;i++)
			{
				$('#survey').append("<tr>"
											+"<td class='alumno' >"+json[i].alumno+"</td>"
											+"<td class='nombre' >"+json[i].nombre+"</td>"
											+"<td class='GradoGrupo'>"+json[i].gradogrupo+"</td>"
											+"<td class='seccion'>"+json[i].seccion+"</td>"
											+"<td class='token' >"+json[i].token+"</td>"
											+"<td class='encuesta' >"
												+"<a "
													+"href='http://ecolmenares.net/liceo/index.php/"+json[i].test+"?token="+json[i].token+"&lang=es-MX'" 
													+" target='_blank' > Ir a Encuesta "
												+"</a>"
											+"</td>"
											+"<td class='status' >"+json[i].status+"</td>"											
										+"</tr>");
				}
		});		
	}
	
</script>
</body>
</html>
