<?php
include('../../config.php');
require_once("funciones.php");
mysql_query("SET NAMES 'utf8'");
$queryAlumnos = mysql_query("SELECT 
										paciente, 
        								count(expediente_medico.id) as TOP
									FROM 
										expediente_medico
									WHERE
										tipo_de_paciente = 1
        								AND
        								fecha_y_hora_de_consulta between DATE_FORMAT(CURDATE(), '%Y-%m-01')  and curdate()
									GROUP BY 
										paciente
									ORDER BY 
										TOP desc
									LIMIT 10
								",$link) or die(mysql_error());
$queryProfesores = mysql_query("SELECT 
        						paciente, 
        						COUNT(id) as TOP
							FROM 
								expediente_medico
							WHERE 
								tipo_de_paciente = 2
        						AND
        						fecha_y_hora_de_consulta between DATE_FORMAT(CURDATE(), '%Y-%m-01') AND curdate()
							GROUP BY 
								paciente
							ORDER BY 
								TOP DESC
							LIMIT 10
								",$link) or die(mysql_error());

$queryPadres = mysql_query("SELECT 
        						paciente, 
        						COUNT(id) as TOP
							FROM 
								expediente_medico
							WHERE 
								tipo_de_paciente = 3
        						AND
        						fecha_y_hora_de_consulta between DATE_FORMAT(CURDATE(), '%Y-%m-01') AND curdate()
							GROUP BY 
								paciente
							ORDER BY 
								TOP DESC
							LIMIT 10
								",$link) or die(mysql_error());


$queryMedicamentos = mysql_query("SELECT 
        									paciente, 
        									COUNT(id) as TOP
										FROM 
											expediente_medico
										WHERE 
											tipo_de_paciente = 3
        									AND
        									fecha_y_hora_de_consulta between DATE_FORMAT(CURDATE(), '%Y-%m-01') AND curdate()
										GROUP BY 
											paciente
										ORDER BY TOP DESC
								",$link) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reportes M&eacute;dicos</title>
	
	<script src="../../../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../../../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
	
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<style>
*{padding:0;margin:0;}
	body{
		background: #dfe3ee;
		font-family:'Ubuntu',Trebuchet, Arial, Helvetica, sans-serif;
	}
	#top-bar{position:fixed;
	width:100%;
	height:45px;
	top:0; left:0;
	color:#FFFFFF;
	background: #dfe3ee;
	background: rgba(223,227,238);
	box-shadow:0px 2px 2px rgba(0,0,0,.3);
	font-size:90%;
	font-family: 'Ubuntu',Arial, Helvetica, sans-serif;
	text-align:center;
	line-height:45px;
	border:0px solid transparent;
	border-bottom:0px;
	}
	#top-bar p{
	font-size:large;
	font-weight:400;
	margin:0;
	}
	#top-bar .title a{
	float:left;
	position:relative;
	font-size:large;
	left:0;
	border-right:2px solid #fff;
	-moz-transition:all.6s;/*Firefox*/
	-webkit-transition:all .6s;/*Crome/Safari*/
	-ms-transition:all .6s;/*IE No funciona todavía*/
	-o-transition:all .6s;/*Opera*/
	transition:all .6s;	
	color:#FFFFFF;
	text-decoration:none;
	padding:0 50px 0 10px;
	}
	
	#top-bar .title a:hover{
	left:20px;
	font-size:x-large;
	text-decoration:none;
	}
	#top-bar .link a{
	float:right;
	position:relative;
	right:0;
	border-left:2px solid #fff;
	-moz-transition:all.6s;/*Firefox*/
	-webkit-transition:all .6s;/*Crome/Safari*/
	-ms-transition:all .6s;/*IE No funciona todavía*/
	-o-transition:all .6s;/*Opera*/
	transition:all .6s;	
	color:#FFFFFF;
	text-decoration:none;
	padding:0 10px 0 50px;
	font-size:15px;
	}
	#top-bar .link a:after{
	content: ' »';
}
	#top-bar .link a:hover{
	text-decoration:underline;
	color:#3b5998;
}
	#container{
	width: 100%;
	height: 100%;
	position: fixed;
	left: 0;
	top: 0;
	}
	#demo-nav-container{
		position:absolute;
		bottom:50px;
		width:100%;
		
	}
	#demo-nav{
		margin:0 auto;
		width: 170px;
		background: #3b5998;
		background: rgba(59,89,152);
		box-shadow:0px 2px 2px rgba(0,0,0,.3);
		border-radius:7px;
		overflow:hidden;/*Para que entre la lista*/
	}
	#demo-nav ul{
		float:right;
		margin-bottom:10px;
		margin-right:15px;
	}
	#demo-nav p{
		color:#fff;
		padding:0 0 5px 5px;
		margin:10px 0 3px 10px;
	}
	#demo-nav li.actual{background:#fff;}
	#demo-nav li.actual a{color:#2c2c2c;}
	#demo-nav li{
		display:inline-block;
		list-style:none;
		width:20px;
		height:20px;
		text-align:center;
		border-radius:10px;
		background:#666;
		box-shadow: 1px 2px 2px rgba(0,0,0,.6);
		line-height:20px;
		color:#fff;
		font-family:'Ubuntu', Arial, Helvetica, sans-serif;
		}
	
	#demo-nav li a{
		text-align:center;
		width:20px;
		height:20px;
		display:inline-block;
		color:#fff;
		line-height:20px;
		font-family:'Ubuntu', Arial, Helvetica, sans-serif;
		text-decoration:none;
		}
		
	#demo-container{
	position: relative;
	top: 10%;
	margin: 0 auto;
	}
	
	.pestana a:link{
	text-decoration:none;
	color: #0071DD;
	background: #DCECFB ;
	border: 1px solid #69AFEF;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .3); 
	-webkit-transition: all .6s;
	-moz-transition: all .6s;
	-o-transition: all .6s ;
	-ms-transition: all .6s;
	transition: all .6s;
}
	.pestana a:visited{text-decoration:none;color: #0071DD;}
   	.pestana a:link:hover{
	background:#AED3F6;
	color: #1364AB;
	text-decoration:none;
}

	.tab-wrapper{
		width:500px;/*Si lo vamos a usar en la sidebar, este es el mejor ancho. Si lo vas a usar aparte, puedes usar cualquier medida en "px" o "em"*/
		margin:0 auto;/*Para centrarlo*/
	}
	
	.tab-wrapper *{margin:0; padding:0;}/*Eliminamos todos los márgenes incómodos*/ 
		
		.tab{
			margin:5px auto;
			display:table;/*Para centrar sin tener que fijar un ancho*/
		}
		
		.tab li{
			float:left;
			list-style: none;
			/*Fuente, color...*/
			font-family:'Ubuntu', Trebuchet, Arial, Helvetica, sans-serif;
			font-size:90%;
			background: #d1d1d1;
			color:white;
			border-radius:4px;
			box-shadow:0 0 2px rgba(0,0,0,.4);
			/*Esto fijará el tamaño del botón*/
			padding:5px 7px;
			/*Distancia entre ellos*/
			margin:5px;
		}
		.tab li:hover, .tab li.activo{/*Al pasar el ratón por encima*/
			background:#eee;
		} 
		.tab li a,.tab li.activo a {/*Estilo de los links*/
			color:#2c2c2c;
			text-decoration:none;
		}
			
		.tabs-content{/*Donde irán los contenidos*/
			width: 480px;
			margin:0 auto;
			background:#eee;
			border-radius:5px; 
			padding:10px 3%;
			padding-right: 10px 5%;
			box-shadow:0 0 3px rgba(0,0,0,.4);
		}
		.pestana{/*El contenido propiamente dicho*/
			background:#eee;
			width: 479px;
		}
		.pestana li {
		display: block;
		margin: 10px 0;
		}
		
th{
	border: 1px;
	text-align:center;
	font-family:'Ubuntu', Trebuchet, Arial, Helvetica, sans-serif;
	font-size:90%;	
	font-size:11px;
	font: bold;
	}
	

	
</style>
</head>
<body>
	<script src="../../../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../../../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
	
 	<link rel="stylesheet" type="text/css" href="../../css/jquery-ui.theme.css" /><!-- Libreria DatetimePicker-->	
<script language="javascript" type="text/javascript">
function abrecalendario(field)
{ 
	open('../calendar.php?campo='+field,'','top=300,left=300,width=300,height=300') ; 
}

function imprSelec(nombre)
{ var ficha = document.getElementById(nombre);
  var ventimp = window.open(' ', 'popimpr');
  ventimp.document.write( ficha.innerHTML );
  ventimp.document.close();
  ventimp.print( );
  ventimp.close();
} 

-->


/* Funcion para el calendario */
$(document).ready(function() {
   $("#fecha_ini").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin").datepicker();
});


$(document).ready(function() {
   $("#fecha_ini_alumnos").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin_alumnos").datepicker();
});


$(document).ready(function() {
   $("#fecha_fin_profesor").datepicker();
});

$(document).ready(function() {
   $("#fecha_ini_profesor").datepicker();
});


$(document).ready(function() {
   $("#fecha_ini_padres").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin_padres").datepicker();
});


$(document).ready(function() {
   $("#fecha_ini_medicamento").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin_medicamento").datepicker();
});


$(document).ready(function() {
   $("#fecha_ini_otros").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin_otros").datepicker();
});


$(document).ready(function() {
   $("#fecha_ini_causas").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin_causas").datepicker();
});


$(document).ready(function() {
   $("#fecha_ini_consultas").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin_consultas").datepicker();
});


jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['D','L','M','M;','J','V','S'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: '',
		duration: 10
		};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});   
	
</script>
<div id="demo-container">
	<div id="tabs1" class="tab-wrapper">
		<!-- Primer contenedor de opciones -->
        <div class="tab">
			<ul>
				<li class="activo"><a href="#pestana1">Top 10 Alumnos</a></li>
				<li><a href="#pestana2">Top 10 Personal</a></li>
				<li><a href="#pestana3">Top 10 Familiares</a></li>
        		<li><a href="#pestana4">Top 10 M&eacute;dicamentos</a></li>
			</ul>
					<div style="clear:both;"></div>
		</div>
        <!-- Segundo contenedor de opciones -->
        <div class="tab">
        	<ul>
            	<li><a href="#pestana5">Top 10 Otros Pacientes</a></li>
                <li><a href="#pestana6">Top 10 Causas</a></li>
                <li><a href="#pestana7">Reporte de Consultas</a></li>
            </ul>
        </div>
					<div class="tabs-content">
					<div class='pestana' id='pestana1'>
<!-- Top 10 Alumnos --> 
<?
			$todayh=getdate();
			$d = $todayh['mday'];
			$m = $todayh['mon'];
			if($m<9){
				$m='0'.$m;
			}
			if($d<9){
				$d='0'.$d;
			}
		$y = $todayh['year'];
			$fecha=$y.'-'.$m.'-'.$d;
	?>
   		<label>Inicio:  </label><input size='14' name='fecha_ini_alumnos' id='fecha_ini_alumnos' value='<? echo $fecha;?>' readonly="readonly">
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		<label>Fin:  </label><input size='14' name='fecha_fin_alumnos' id='fecha_fin_alumnos' value='<? echo $fecha;?>' readonly="readonly">
	 	<? 
	 		$loading="";
	 		$loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
			$cnt=0;	
		?>                   
<table border='1'>
  <tr>
    <th colspan="5"  align="left" > 
    
		</th>	
	</tr>
	<tr>
		<th>
			<input type="submit" name="buscarTop10Alumnos" value="Generar Reporte" onClick="buscarTop10Alumnos()">
			<div id="txtTop10Alumno" >Seleccione un rango de fechas para poder generar el reporte</div>
            <input type="button" onClick="tableToExcel('Top10Alumno', 'Top10Alumno')" value="Exportar a Excel">
	
		</th>
	</tr>    
</table>
	</div>	
    <div class="pestana" style="display: none;"id="pestana2">
<!-- Top 10 Profesor -->
<label>Inicio:  </label><input size='14' name='fecha_ini_profesor' id='fecha_ini_profesor' value='<? echo $fecha;?>' readonly="readonly">
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		<label>Fin:  </label><input size='14' name='fecha_fin_profesor' id='fecha_fin_profesor' value='<? echo $fecha;?>' readonly="readonly">
	 	<? 
	 		$loading="";
	 		$loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
			$cnt=0;	
		?>
<table border='1'>
  <tr>
    <th colspan="5"  align="left" > 
   		
		</th>	
	</tr>
	<tr>
		<th>
			<input type="submit" name="buscarTop10Profesor" value="Generar Reporte" onClick="buscarTop10Profesor()">
			<div id="txtTop10Profesor" >Seleccione un rango de fechas para poder generar el reporte</div>
			<input type="button" onClick="tableToExcel('Top10Personal', 'Top10Personal')" value="Exportar a Excel">
		</th>
	</tr>    
</table>
	</div>
	<div class="pestana" style="display: none;"id="pestana3">
<!-- Top 10 Padre -->
<label>Inicio:  </label><input size='14' name='fecha_ini_padres' id='fecha_ini_padres' value='<? echo $fecha;?>' readonly="readonly">
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		<label>Fin:  </label><input size='14' name='fecha_fin_padres' id='fecha_fin_padres' value='<? echo $fecha;?>' readonly="readonly">
	 	<? 
	 		$loading="";
	 		$loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
			$cnt=0;	
		?>
<table border='1' id="Top10Padres" name="Top10Padres">
  <tr>
    <th colspan="5"  align="left" > 
   		
		</th>	
	</tr>
	<tr>
		<th>
			<input type="submit" name="buscarTop10Padres" value="Generar Reporte" onClick="buscarTop10Padres()">
			<div id="txtTop10Padres" >Seleccione un rango de fechas para poder generar el reporte</div>
			<input type="button" onClick="tableToExcel('Top10Familiares', 'Top10Familiares')" value="Exportar a Excel">
		</th>
	</tr>    
</table>

	</div>
    <div class="pestana" style="display: none;"id="pestana4">
<!-- Top 10 Medicamento -->
<label>Inicio:  </label><input size='14' name='fecha_ini_medicamento' id='fecha_ini_medicamento' value='<? echo $fecha;?>' readonly="readonly">
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		<label>Fin:  </label><input size='14' name='fecha_fin_medicamento' id='fecha_fin_medicamento' value='<? echo $fecha;?>' readonly="readonly">
	 	<? 
	 		$loading="";
	 		$loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
			$cnt=0;	
		?>
<table border='1'>
  <tr>
    <th colspan="5"  align="left" > 
   		
		</th>	
	</tr>
	<tr>
		<th>
			<input type="submit" name="buscarTop10Medicamentos" value="Generar Reporte" onClick="buscarTop10Medicamentos()">
			<div id="txtTop10Medicamentos" name="txtTop10Medicamentos" >Seleccione un rango de fechas para poder generar el reporte</div>
			<input type="button" onClick="tableToExcel('Top10Otros', 'Top10Otros')" value="Exportar a Excel">
		</th>
	</tr>    
</table>	
	</div>
    <div class="pestana" style="display: none;"id="pestana5">
    <label>Inicio:  </label><input size='14' name='fecha_ini_otros' id='fecha_ini_otros' value='<? echo $fecha;?>' readonly="readonly">
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		<label>Fin:  </label><input size='14' name='fecha_fin_otros' id='fecha_fin_otros' value='<? echo $fecha;?>' readonly="readonly">
	 	<? 
	 		$loading="";
	 		$loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
			$cnt=0;	
		?>
<table border='1' style='table-layout:fixed;'>
  <tr>
    <th colspan="8"  align="left" style='width: 100px;'> 
   		
		</th>	
	</tr>
	<tr>
		<th>
			<input type="submit" name="buscarTop10Otros" value="Generar Reporte" onClick="buscarTop10Otros()">
			<div id="txtTop10Otros" name="txtTop10Otros" >Seleccione un rango de fechas para poder generar el reporte</div>
			<input type="button" onClick="tableToExcel('Top10Pacientes', 'Top10Pacientes')" value="Exportar a Excel">
		</th>
	</tr>    
</table>	        
        
    </div>
    <div class="pestana" style="display: none;"id="pestana6">
    	
    	<!-- Top 10 Causas -->
        <label>Inicio:  </label><input size='14' name='fecha_ini_causas' id='fecha_ini_causas' value='<? echo $fecha;?>' readonly="readonly">
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		<label>Fin:  </label><input size='14' name='fecha_fin_causas' id='fecha_fin_causas' value='<? echo $fecha;?>' readonly="readonly">
	 	<? 
	 		$loading="";
	 		$loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
			$cnt=0;	
		?>
<table border='1'>
  <tr>
    <th colspan="5"  align="left" > 
   		
		</th>	
	</tr>
	<tr>
		<th>
			<input type="submit" name="buscarTop10Causas" value="Generar Reporte" onClick="buscarTop10Causas()">
			<div id="txtTop10Causas" >Seleccione un rango de fechas para poder generar el reporte</div>
			<input type="button" onClick="tableToExcel('Top10Causas', 'Top10Causas')" value="Exportar a Excel">
		</th>
	</tr>    
</table>	        
        
        
    </div><div class="pestana" style="display: none;" id="pestana7">        
            	<!-- Reporte de Consultas -->
                <label>Inicio:  </label><input size='14' name='fecha_ini_consultas' id='fecha_ini_consultas' value='<? echo $fecha;?>' readonly="readonly">
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		&nbsp;&nbsp;&nbsp;&nbsp;
   		<label>Fin:  </label><input size='14' name='fecha_fin_consultas' id='fecha_fin_consultas' value='<? echo $fecha;?>' readonly="readonly">
	 	<? 
	 		$loading="";
	 		$loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
			$cnt=0;	
		?>
<table border='1' style='table-layout:fixed;'>
  <tr>
    <th colspan="5"  align="left" > 
   		
		</th>	
	</tr>
	<tr>
		<th>
			<input type="submit" name="buscarConsultas" value="Generar Reporte" onClick="buscarConsultas()">
			<div id="txtConsultas" >Seleccione un rango de fechas para poder generar el reporte</div>
			<input type="button" onClick="tableToExcel('Consultas', 'Consultas')" value="Exportar a Excel">
		</th>
	</tr>    
</table>	        
        
    </div>
</div>
</div>

</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		
		$('#tabs1 .tab > ul > li').click(function(){
		var clicktab=$(this);
		var IDlistaActual=$('.tab > ul').find('li.activo').find('a').attr("href");
		var IDlistaNueva=clicktab.find('a').attr("href");
		var contenedor=$('#tabs1 .tabs-content');
		var alturaActual=contenedor.height();
		
		
		if (IDlistaActual != IDlistaNueva){
	
		//fijo la altura actual
		contenedor.height(alturaActual);
		$(IDlistaActual).fadeOut(200, function(){
			$(IDlistaNueva).fadeIn(300);
			var nuevaAltura=$(IDlistaNueva).height(); 
			contenedor.animate({height: nuevaAltura});
		});	
		//botones
		$('.tab > ul > li').removeClass('activo');
		clicktab.addClass('activo');
		contenedor.click(function(){
			contenedor.height('auto');
		});
		}		
		return false;
		});
		});
		
//Buscar Alumno
function buscarTop10Alumnos(){			   
			   $fecha_ini = $('input:text[name=fecha_ini_alumnos]').val();
			   $fecha_fin = $('input:text[name=fecha_fin_alumnos]').val();
			   
	if ($fecha_ini == "" || $fecha_fin == "") {
        document.getElementById("txtTop10Alumno").innerHTML = "Seleccione Fecha de inicio y Fecha Fin para generar el reporte";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code para IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code para IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtTop10Alumno").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","reporte.php?"
					 					+"fecha_ini="+encodeURIComponent($fecha_ini)
										+"&fecha_fin="+encodeURIComponent($fecha_fin)
										+"&tipo_reporte=1",true);
        xmlhttp.send();
    }
}
////Buscar Personal
function buscarTop10Profesor(){			   
			   $fecha_ini = $('input:text[name=fecha_ini_profesor]').val();
			   $fecha_fin = $('input:text[name=fecha_fin_profesor]').val();
			   
	if ($fecha_ini == "" || $fecha_fin == "") {
        document.getElementById("txtTop10Profesor").innerHTML = "Seleccione Fecha de inicio y Fecha Fin para generar el reporte";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code para IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code para IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtTop10Profesor").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","reporte.php?"
					 					+"fecha_ini="+encodeURIComponent($fecha_ini)
										+"&fecha_fin="+encodeURIComponent($fecha_fin)
										+"&tipo_reporte=2",true);
        xmlhttp.send();
    }
}

////Buscar Familia
function buscarTop10Padres(){			   
			   $fecha_ini = $('input:text[name=fecha_ini_padres]').val();
			   $fecha_fin = $('input:text[name=fecha_fin_padres]').val();
			   
	if ($fecha_ini == "" || $fecha_fin == "") {
        document.getElementById("txtTop10Padres").innerHTML = "Seleccione Fecha de inicio y Fecha Fin para generar el reporte";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code para IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code para IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtTop10Padres").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","reporte.php?"
					 					+"fecha_ini="+encodeURIComponent($fecha_ini)
										+"&fecha_fin="+encodeURIComponent($fecha_fin)
										+"&tipo_reporte=3",true);
        xmlhttp.send();
    }
}

function buscarTop10Medicamentos(){			   
			   $fecha_ini = $('input:text[name=fecha_ini_medicamento]').val();
			   $fecha_fin = $('input:text[name=fecha_fin_medicamento]').val();
			   
	if ($fecha_ini == "" || $fecha_fin == "") {
        document.getElementById("txtTop10Medicamentos").innerHTML = "Seleccione Fecha de inicio y Fecha Fin para generar el reporte";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code para IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code para IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtTop10Medicamentos").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","reporte.php?"
					 					+"fecha_ini="+encodeURIComponent($fecha_ini)
										+"&fecha_fin="+encodeURIComponent($fecha_fin)
										+"&tipo_reporte=4",true);
        xmlhttp.send();
    }
}

function buscarTop10Causas(){			   
			   $fecha_ini = $('input:text[name=fecha_ini_causas]').val();
			   $fecha_fin = $('input:text[name=fecha_fin_causas]').val();

	if ($fecha_ini == "" || $fecha_fin == "") {
        document.getElementById("txtTop10Causas").innerHTML = "Seleccione Fecha de inicio y Fecha Fin para generar el reporte";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code para IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code para IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtTop10Causas").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","reporte.php?"
					 					+"fecha_ini="+encodeURIComponent($fecha_ini)
										+"&fecha_fin="+encodeURIComponent($fecha_fin)
										+"&tipo_reporte=6",true);
        xmlhttp.send();
    }
}

function buscarTop10Otros(){			   
			   $fecha_ini = $('input:text[name=fecha_ini_otros]').val();
			   $fecha_fin = $('input:text[name=fecha_fin_otros]').val();

	if ($fecha_ini == "" || $fecha_fin == "") {
        document.getElementById("txtTop10Otros").innerHTML = "Seleccione Fecha de inicio y Fecha Fin para generar el reporte";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code para IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code para IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtTop10Otros").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","reporte.php?"
					 					+"fecha_ini="+encodeURIComponent($fecha_ini)
										+"&fecha_fin="+encodeURIComponent($fecha_fin)
										+"&tipo_reporte=5",true);
        xmlhttp.send();
    }
}

/////Reporte de Consultas
function buscarConsultas(){			   
			   $fecha_ini = $('input:text[name=fecha_ini_consultas]').val();
			   $fecha_fin = $('input:text[name=fecha_fin_consultas]').val();

	if ($fecha_ini == "" || $fecha_fin == "") {
        document.getElementById("txtConsultas").innerHTML = "Seleccione Fecha de inicio y Fecha Fin para generar el reporte";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code para IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code para IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtConsultas").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","reporte.php?"
					 					+"fecha_ini="+encodeURIComponent($fecha_ini)
										+"&fecha_fin="+encodeURIComponent($fecha_fin)
										+"&tipo_reporte=7",true);
        xmlhttp.send();
    }
}





///Export a excel/////
var tableToExcel = (function() {
var uri = 'data:application/vnd.ms-excel;base64,'
, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
, base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
, format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
return function(table, name) {
if (!table.nodeType) table = document.getElementById(table)
var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
window.location.href = uri + base64(format(template, ctx))
}
})()



</script>
</body>
</html>

