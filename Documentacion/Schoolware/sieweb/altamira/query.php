<html>
 
<head>
 
<title>Acuerdos de Preceptorias</title>
 
<script src="../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../repositorio/css/jquery-ui.theme.css" /><!-- Libreria DatetimePicker-->	
<script>
function realizaProceso(fecha_ini, fecha_fin){
        var parametros = {
                "fecha_ini" : fecha_ini,
                "fecha_fin" : fecha_fin
        };
        $.ajax({
                data:  parametros,
                url:   'calcular.php',
                type:  'post',
                beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
                        $("#resultado").html(response);
                }
        });
}

$(document).ready(function() {
   $("#fecha_ini").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin").datepicker();
});

jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant&nbsp;',
		nextText: '&nbsp;Sig&#x3e;',
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

$("prevText").css({"cursor":"hand"});
$("#prevText").css({"cursor":"pointer"});

</script>
 
</head>
 
<body>
 
 
<?php
   include("config.php");
 
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
   <form>
   	<table border='1'>
   		<tr>
   		 <td colspan='100%' align='center'>Acuerdos de Preceptorias</td>
   		 </tr>
   		<tr>
   		 <td>&nbsp;&nbsp;Fecha Inicial:&nbsp;&nbsp;<input type='text' name='fecha_ini' id='fecha_ini' value='<? echo $fecha;?>' readonly="readonly">&nbsp;&nbsp;</td> 
   		 <td>&nbsp;&nbsp;Fecha Final:&nbsp;&nbsp;<input type='text' name='fecha_fin' id='fecha_fin' value='<? echo $fecha;?>' readonly="readonly">&nbsp;&nbsp;</td>
   		</tr>
   		<tr>
   		 <td colspan='100%' align='center'><input value='Consultar' type='button' name='Consultar' id='Consultar' href="javascript:;" onclick="realizaProceso($('#fecha_ini').val(), $('#fecha_fin').val());return false;" ></td>
   		</tr>
    </table>
   </form>
   
   Resultado: <span id="resultado"></span>
      
</body>
 
</html>
 