<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="jquery-1.10.2.min.js"></script>
     <script src="jquery-ui.js"></script>
     <script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
     <script type="text/javascript" src="autocomplete.js"></script>
<title>Consultas M&eacute;dicas</title>
</head>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<script type="text/javascript" src="javascript.js"></script>
<?php
include('../../config.php');
require_once("funciones.php");
mysql_query("SET NAMES 'utf8'");
$query = mysql_query("SELECT
								* 
							FROM 
								expediente_medico 
							WHERE 
								tipo_de_paciente='1'
								",$link) or die(mysql_error());

?>
<body>
<table width="600">
  <tr><!--n Aquí me quedé-->
    <th colspan="5" align="left" class="class_css"> Todas las consultas:
     </th>
  </tr>
  <tr>
  	<td class="class_css">&nbsp;Paciente &nbsp;</td>
    <td class="class_css">&nbsp;Fecha de Colsulta&nbsp;</td>
    <td class="class_css">&nbsp;Exploración Física&nbsp;</td>
    <td class="class_css">&nbsp;Diagnostico&nbsp;</td>
    <td class="class_css">&nbsp;Receta M&eacute;dica&nbsp;</td>
  </tr>

<?  

						while($elemento_= mysql_fetch_array($query)){
							//echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";
							
							echo "<tr>";
							echo "		<td>&nbsp;".$elemento_['paciente']."</td>";
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
  
?>  
</table>
</body>
</html>



<script>
$("#reportes").on("change", buscarConsulta);


/*

function buscarConsulta(){
	
var	$reportes = $("#reportes").val();
	alert($reportes);

	$.ajax({
		dataType: "json",
		data: {"reportes": $reportes},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("div").html(respuesta.html);
		},
		error:	function(xhr,err){ 
			alert("Alumno readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
		}
	});	
  
}

function buscarMedico(str){
	$alumno = $("#paciente").val();
	
	
	if ($alumno == "") {
        document.getElementById("txtReporte").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtMedico").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","datos.php?paciente="+$alumno,true);
        xmlhttp.send();
    }
}


*/

$(funcion()
$.ajax({
	   url: 'buscar.php',
	   data: "#reportes",
	   
	   dataType: 'json',
	   success: function(data)
	   {
		   var id=data[0];
		   var vname = data[1];
		   $('#outpot').html("<b>id: </b>"+id+"<b> name: </b>)";
	   });



</script>
