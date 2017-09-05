<?
include('../config.php');
include('../functions.php');


$alumnoN = '<script> $("#alumno").val(); </script>';
$alumno ='<script>  $("#alumno option:selected").html(); </script>';

//echo $alumno.$alumnoN;

/*1*/$datos_alumno="formularios.php?tabla=expediente_datos&tipo=alumnos&id=1&alumno=$alumno&agr_modif_borr=modificar";
/*2*/$datos_familia="formularios.php?tabla=expediente_datos&tipo=familias&id=1&alumno=$alumnoN&agr_modif_borr=modificar";
/*3*/$fip="familias_cursos.php?alumno='+value+'&alumnoN=$alumnoN";
/*4*/$boleta="../boletas.php?alumnoN=$alumnoN";
/*5*/$captura_preceptor="captura_preceptoria.php?alumno=$alumno";
/*6*/$acuerdos="acuerdos.php?alumno=$alumno";
/*7*/$expediente_medico="medico/funciones/detalle_hijo.php?alumno=$alumno&alumnoN=$alumnoN";


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Expediente Digital</title>
</head>
<style>

.tabs { /* es el rectángulo contenedor */
		margin: 20 auto;
		min-height: 500px;
		position: absolute;
		width: 915px;
	}

.tab { /* cada una de las pestañas */
		float: left;
	}

.tab label { /* la parte superior con el título de la pestaña */
		background-color: <? echo $baner; ?>;;
		border-radius: 2px 2px 0 0;
		box-shadow: -2px 2px 2px #678 inset;
		color: #DDD;
		cursor: pointer;
		left: 0;
		margin-right: 1px;
		padding: 5px 10px;
		position: relative;
		text-shadow: 1px 1px #000;
	}

/* el control input sólo lo necesitamos para que las pestañas permanezcan abiertas así que lo ocultamos */
.tab [type=radio] { display: none; }

/* el contenido de las pestañas */
.content {
		background-color: <? echo $fondo_n; ?>;
		bottom: 0;
		left: 0;
		overflow: hidden;
		padding: 1px;
		position: absolute;
		right: 0;
		top: 23px;
	}

/* y un poco de animación */
.content > * {
		opacity: 0;
		-moz-transform: translateX(-100%);
		-webkit-transform: translateX(-100%);
		-o-transform: translateX(-100%);
		-moz-transition: all 0.6s ease;
		-webkit-transition: all 0.6s ease;
		-o-transition: all 0.6s ease;
	}

/* controlamos la pestaña activa */
[type="radio"]:checked ~ label {
		background-color: <? echo $fondo_n; ?>;
		box-shadow: 0 3px 2px #89A inset;
		color: #FFF;
		z-index: 2;
	}	

[type=radio]:checked ~ label ~ .content { z-index: 1; }
[type=radio]:checked ~ label ~ .content > * {
		opacity: 1;
		-moz-transform: translateX(0);
		-webkit-transform: translateX(0);
		-o-transform: translateX(0);
		-ms-transform: translateX(0);
	}

</style>


</head>
<body>
	Alumno:  <select name='alumno' id='alumno' onchange="AjaxCargar(this.value);" >
				<option value=''></option>	
<?
$resul_t= mysql_query("SELECT CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) as n,alumno from alumnos  where activo='A' order by apellido_paterno,apellido_materno,nombre",$link) or die(mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'");
    while($ro_=mysql_fetch_array($resul_t))$echo.="<option value='".$ro_['alumno']."'>".utf8_encode($ro_['n'])."</option>";
	$echo.="</select> ";
echo $echo;
?>
	<!--
  <form id="fr" name="fr" action="expediente3.php" method="POST"  accept-charset="UTF-8">	
	<div class="tabs">
   		<div class="tab">
       		<input type="radio" id="tab-1" name="tab-group-1" checked>
       		<label for="tab-1">Datos Alumno</label>
       			<div id="d1" class="content">
					<iframe src="<?=$datos_alumno;?>" width="100%" height="100%" ></iframe>
				</div>
   		</div>
   		<div class="tab">
       		<input type="radio" id="tab-2" name="tab-group-1">
       		<label for="tab-2">Datos Familia</label>
       			<div id="d2" class="content"> 
					<iframe src="<?=$datos_familia;?>" width="100%" height="100%" ></iframe>
				</div>
   		</div>
    	<div class="tab">
       		<input type="radio" id="tab-3" name="tab-group-1">
       		<label for="tab-3">Fip</label>
       		<div class="content"> 
				<iframe src="<?=$fip;?>" width="100%" height="100%" ></iframe>
			</div>
   		</div>
   		<div class="tab">
       		<input type="radio" id="tab-4" name="tab-group-1">
       		<label for="tab-4">Calificaciones</label>
       			<div class="content">
					<iframe src="<?=$boleta;?>" width="100%" height="100%" ></iframe>
				</div>
   		</div>
   		<div class="tab">
       		<input type="radio" id="tab-5" name="tab-group-1">
       		<label for="tab-5">Captura de Preceptor</label>
       		<div class="content"> 
				<iframe src="<?=$captura_preceptor;?>" width="100%" height="100%" ></iframe>
			</div>
   		</div>
   		<div class="tab">
       		<input type="radio" id="tab-6" name="tab-group-1">
       		<label for="tab-6">Acuerdos</label>
       			<div class="content">
       				<iframe src="<?=$acuerdos;?>" width="100%" height="100%" ></iframe>
       			</div>
   		</div>
   		<!--<div class="tab">
       		<input type="radio" id="tab-7" name="tab-group-1">
       		<label for="tab-7">Expediente M&eacute;dico</label>
       			<div class="content">
       				<iframe src="<?=$expediente_medico;?>" width="100%" height="100%" ></iframe>
       			</div>
   		</div>-->
   		<div class="tab">
       		<input type="radio" id="tab-8" name="tab-group-1">
       		<label for="tab-8">Test</label>
       			<div class="content">
       			
       			</div>
   		</div>
	</div>
	
  </form>	
 -->
<div id='result' name='result'></div>
</body>
<?php

?>
	<script src="../../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
<script>

$("#alumno").on("change", valorAlumno);


function AjaxCargar(){
	
	$Alumno = $("#alumno").val();
	$AlumnoN = $("#alumno option:selected").html();
	
if($Alumno == "" || $AlumnoN == "" ){
	
	$("#result").html("");
	
	}
	else {
	$.ajax({
		dataType: "json",
		data: {"alumno": $Alumno ,
			   "alumnoN": $AlumnoN
				},
		url:   'expediente2.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("#result").html(respuesta.html);
		},
		error:	function(xhr,err){ 
			alert("Grupo readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
		}
	});	
  }
}



</script>
</html>

