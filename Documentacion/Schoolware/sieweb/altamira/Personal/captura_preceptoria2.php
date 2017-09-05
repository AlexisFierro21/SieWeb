<?php session_start();
include('../config.php');
include('../functions.php');
mysql_query('SET CHARACTER SET "UTF8"');

/////Fecha de última modificación 08-01-2016
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Expediente</title>
	
	<style type="text/css">
		*{ font-family: sans-serif; margin: 0;}
		dl{ margin: 0px auto; width: 900px; }
		dt, dd{ padding: 3px; }
		dt{ background: #333333; color: white; border-bottom: 1px solid #141414; border-top: 1px solid #4E4E4E; cursor: pointer; }
		dd{  display: none; line-height: 1.6em; }
		dt.activo, dt:hover{ background: #424242; }

		dt:before{ content: "▸"; margin-right: 5px; }
		dt.activo:before{ content: "▾"; }

.input {
    position: relative;
}		
		
	.tooltip {
    display: none;
    padding: 10px;
}

.input:hover .tooltip {
    background: #fff;
    border-radius: 3px;
    border: 1px solid #000;
    bottom: -35px;
    color: #000;
    display: inline;
    height: 10px;
    left: 0;
    line-height: 10px;
    position: absolute;
}

.input:hover .tooltip:before {
    display: block;
    content: "";
    position: absolute;
    top: 0px;
    width: 0; 
	height: 0; 
	border-left: 2px solid #000;
	border-right: 2px solid #000;
	
	border-bottom: 2px solid #fff;
}
		
	hr {
    border: 0;
    height: 1px;
    margin:0px 0;
    position:relative;
    background: -moz-linear-gradient(left, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 10%, rgba(0,0,0,0.65) 50%, rgba(0,0,0,0) 90%, rgba(0,0,0,0) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(0,0,0,0)), color-stop(10%,rgba(0,0,0,0)), color-stop(50%,rgba(0,0,0,0.65)), color-stop(90%,rgba(0,0,0,0)), color-stop(100%,rgba(0,0,0,0))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(left, rgba(0,0,0,0) 0%,rgba(0,0,0,0) 10%,rgba(0,0,0,0.65) 50%,rgba(0,0,0,0) 90%,rgba(0,0,0,0) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(left, rgba(0,0,0,0) 0%,rgba(0,0,0,0) 10%,rgba(0,0,0,0.65) 50%,rgba(0,0,0,0) 90%,rgba(0,0,0,0) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(left, rgba(0,0,0,0) 0%,rgba(0,0,0,0) 10%,rgba(0,0,0,0.65) 50%,rgba(0,0,0,0) 90%,rgba(0,0,0,0) 100%); /* IE10+ */
    background: linear-gradient(left, rgba(0,0,0,0) 0%,rgba(0,0,0,0) 10%,rgba(0,0,0,0.65) 50%,rgba(0,0,0,0) 90%,rgba(0,0,0,0) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#00000000',GradientType=1 ); /* IE6-9 */
    transform:rotate(90deg);
     -o-transform:rotate(90deg);
     -moz-transform:rotate(90deg);
     -webkit-transform:rotate(90deg);
}

hr:before {
    content: "";
    display: block;
    border-top: solid 1px #f9f9f9;
    width: 100%;
    height: 1px;
    position: relative;
    top: 0%;
    z-index: 1;
}

.hr {
    border: 0;
    height: 1px;
    margin:0px 0;
    position:relative;
    background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(215,215,215,0.75), rgba(0,0,0,0)); 
    background-image: -moz-linear-gradient(left, rgba(0,0,0,0), rgba(215,215,215,0.75), rgba(0,0,0,0)); 
    background-image: -ms-linear-gradient(left, rgba(0,0,0,0), rgba(215,215,215,0.75), rgba(0,0,0,0)); 
    background-image: -o-linear-gradient(left, rgba(0,0,0,0), rgba(215,215,215,0.75), rgba(0,0,0,0)); 
    box-shadow: 0px -2px 4px rgba(136,136,136,0.75);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#00000000',GradientType=1 ); /* IE6-9 */
    transform:rotate(180deg);
     -o-transform:rotate(180deg);
     -moz-transform:rotate(180deg);
     -webkit-transform:rotate(180deg);
}

.hr:before {
    content: "";
    display: block;
    border-top: solid 1px #f9f9f9;
    width: 100%;
    height: 1px;
    position: relative;
    top: 0%;
    z-index: 1;
}

		.ok{display:block;padding:10px;text-align:center;background:green;color:#fff;}
		.ko{display:block;padding:10px;text-align:center;background:red;color:#fff;}

	</style>	
</head>
<body>
<?
$fecha = date('Y-m-j');
$tiempo_max_preceptorias_ = '-'.$tiempo_max_preceptorias;
$nuevafecha = strtotime ( $tiempo_max_preceptorias_." day" , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );


//// Fecha limite a capturar
$fech_u = "SELECT DATE_SUB(curdate(), INTERVAL ".$tiempo_max_preceptorias." DAY ) AS ultima ";
//echo $fec_u;
	$u_fech = mysql_fetch_array(mysql_query($fech_u));
	$ultima_fec_captura = $u_fech['ultima'];

/// Fecha Actual
	$fechh_ = "select curdate() as hoy";
	$fh_ = mysql_fetch_array(mysql_query($fechh_));
	$fechoy = $fh_['hoy'];
	
//Alumno
$alumno_id= $_REQUEST['alumno'];	
	
/// Ultima preceptoria
	$ultima = "SELECT
					id, 
					alumno, 
					ciclo, 
					preceptoria, 
					observaciones, 
					metas, 
					fin, 
					fecha_captura,
                    date_format(fecha, '%Y-%m-%d') AS fecha
				FROM 
					preceptoria
				WHERE
					alumno=$_REQUEST[alumno]
				ORDER BY 
					id  desc LIMIT 0,1";
	$ul_p = mysql_fetch_array(mysql_query($ultima));
		$ultima_id=$ul_cant['cant'];
		$ultima_id=$ul_p['id'];
		$ultima_alumno=$ul_p['alumno'];
		$ultima_ciclo=$ul_p['ciclo'];
		$ultima_preceptoria=$ul_p['preceptoria'];
		$ultima_observaciones=$ul_p['observaciones'];
		$ultima_metas=$ul_p['metas'];
		$ultima_fin=$ul_p['fin'];
		$ultima_fecha_captura=$ul_p['fecha_captura'];
		$ultima_fecha=$ul_p['fecha'];

if(mysql_num_rows(mysql_query($ultima))>0){
	 ///Numrows
		if($ultima_fin=='1'){
			$id_preceptoria_c = "SELECT
										 ifnull(count(alumno) ,0)+1 as tot 
									FROM
										 preceptoria 
									WHERE
										ciclo = '{$periodo_actual}' 
										 AND 
										alumno = '{$_REQUEST[alumno]}'";
										
			$id_ul_p = mysql_fetch_array(mysql_query($id_preceptoria_c));
			$id_ = $id_ul_p['tot'];
			//$id_ = $ultima_preceptoria +1;
			$preceptoria = "<dl>
		<form name='myForm' id='myForm'>
		<dt>Nueva Preceptoria</dt>
			<dd>
				<table width='890px'>
					<tr>
						<td>
						  <div class='input'>
							<input name='eliminar' id='eliminar' type='image' ;='' onclick='formReset()' src='../im/trash-empty-35.png' > 
								<span class='tooltip'>De click aqu&iacute; para eliminar preceptoria</span>
						  </div>
						</td>
						<td rowspan='100%' width='5'>
							<hr width='200'> 
						</td>
						<td >
							<b>Ciclo</b> $ultima_ciclo <br>
							<b>Preceptoria</b> $ultima_preceptoria
						</td>
					</tr>
					<tr>
						<td>
							<input type='hidden' id='preceptoria' name='preceptoria' value='$id_'>
							<input type='hidden' id='ciclo' name='ciclo' value='$periodo_actual'>
							<input type='hidden' id='alumno' name='alumno' value='$alumno_id'>
							Observaciones Preceptor&iacute;a Actual:<br>
							<textarea id='obs' cols='40' name='obs'></textarea>
						</td>
						
						<td>
							Observaciones Preceptor&iacute;a Anterior:<br>
							<textarea id='obs_' cols='40' name='obs_' readonly='readonly' disabled='disabled' >$ultima_observaciones
							</textarea>
						</td>
					</tr>
					<tr>
						<td>
							Metas Preceptoría Actual:<br>
							<textarea id='metas' cols='40' name='metas' ></textarea>
						</td>
						
						<td>
							Metas Preceptoría Anterior:<br>
							<textarea id='metas_' cols='40' name='metas_' readonly='readonly' disabled='disabled'>$ultima_metas
							</textarea>
						</td>
					</tr>
					<tr>
						<td>
							Fecha de la preceptoría Actual:<br>
							<input id='fecha' name='fecha' readonly='readonly' value='$fechoy'></input>
						</td>
						
						<td>
							Fecha de la preceptoría Anterior:<br>
							<input id='fecha_' name='fecha_' readonly='readonly' disabled='disabled' value='$ultima_fecha' ></input>
						</td>
					</tr>
					<tr>
						<td>
							Finalizar preceptoría actual:&nbsp;
							<input id='fin' name='fin' type='checkbox'></input>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<input type='button'  value='   Grabar   ' name='grabar' id='grabar' onClick='grabar_registro(1)'></input><br>
							<span id='res' name='res'></span>
						</td>
					</tr>
				</table>
			</dd>
			</form>
			<script>
			
			function formReset()
					{
						document.getElementById('myForm').reset();
					}
			
			
			</script>		
			";
			
		}elseif($ultima_fin=='0'){	
			$preceptoria_anterior_f	= "SELECT
												id, 
												alumno, 
												ciclo, 
												preceptoria, 
												observaciones, 
												metas, 
												fin, 
												fecha_captura,
                   								date_format(fecha, '%Y-%m-%d') AS fecha
											FROM 
												preceptoria
											WHERE
												alumno=$_REQUEST[alumno]
											ORDER BY 
												id  desc LIMIT 1,1";
			$ul_p_f = mysql_fetch_array(mysql_query($preceptoria_anterior_f));
				$ultimaf_id=$ul_p_f['id'];
				$ultimaf_alumno=$ul_p_f['alumno'];
				$ultimaf_ciclo=$ul_p_f['ciclo'];
				$ultimaf_preceptoria=$ul_p_f['preceptoria'];
				$ultimaf_observaciones=$ul_p_f['observaciones'];
				$ultimaf_metas=$ul_p_f['metas'];
				$ultimaf_fin=$ul_p_f['fin'];
				$ultimaf_fecha_captura=$ul_p_f['fecha_captura'];
				$ultimaf_fecha=$ul_p_f['fecha'];			
			
			
			if(	$ultimaf_id==""){
				$ultimaf_id=1;
			}	
			
			$preceptoria = "<dl>
		<form name='' id='' action='' onclick=''>
		<dt>Nueva Preceptoria</dt>
			<dd>
				<table width='890px'>
					<tr>
						<td>
						  <div class='input'>
							<input name='eliminar' id='eliminar' type='image' ;='' onclick='formReset()' src='../im/trash-empty-35.png' > 
								<span class='tooltip'>De click aqu&iacute; para eliminar preceptoria</span>
						  </div>
						</td>
						<td rowspan='100%' width='5'>
							<hr width='200'> 
						</td>
						<td >
							<b>Ciclo</b> $ultimaf_ciclo<br>
							<b>Preceptoria</b> $ultimaf_preceptoria
						</td>
					</tr>
					<tr>
						<td>
							<input type='hidden' id='ciclo' name='ciclo' value='$periodo_actual'>
							<input type='hidden' id='id' name='id' value='$ultima_id'>
							<input type='hidden' id='preceptoria' name='preceptoria' value='$ultima_preceptoria'>
							<input type='hidden' id='alumno' name='alumno' value='$alumno_id'>
							Observaciones Preceptor&iacute;a Actual:<br>
							<textarea id='obs' cols='40' name='obs'>$ultima_observaciones</textarea>
						</td>
						
						<td>
							Observaciones Preceptor&iacute;a Anterior:<br>
							<textarea id='obs_' cols='40' name='obs_' readonly='readonly' disabled='disabled' >$ultimaf_observaciones
							</textarea>
						</td>
					</tr>
										<tr>
						<td>
							Metas Preceptoría Actual:<br>
							<textarea id='metas' cols='40' name='metas' >$ultima_metas</textarea>
						</td>
						
						<td>
							Metas Preceptoría Anterior:<br>
							<textarea id='metas_' cols='40' name='metas_' readonly='readonly' disabled='disabled'>$ultimaf_metas
							</textarea>
						</td>
					</tr>
					<tr>
						<td>
							Fecha de la preceptoría Actual:<br>
							<input id='fecha' name='fecha' readonly='readonly' value='$fechoy'></input>
						</td>
						
						<td>
							Fecha de la preceptoría Anterior:<br>
							<input id='fecha_' name='fecha_' readonly='readonly' disabled='disabled' value='$ultimaf_fecha' ></input>
						</td>
					</tr>
					<tr>
						<td>
							Finalizar preceptoría actual:&nbsp;
							<input id='fin' name='fin' type='checkbox'></input>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<input type='button'  value='   Grabar   ' name='grabar' id='grabar' onclick='grabar_registro(2)' ></input><br>
							<span id='res' name='res' ></span>
						</td>
					</tr>
				</table>
			</dd>
			</form>
			
			<!--Aquí empezamos el código javascript para el uptade-->

			";
			
			
			
		}


echo "<dl>
		<form name='' id='' action='' onclick=''>	
			<input type='hidden' id='fecha_menor' name='fecha_menor' value='$ultima_fec_captura'>
$preceptoria		
			
					<!-- Aquí empezamos a mandar a llamar las preceptorias pasadas organizadas por los ciclos  -->";

$ciclos = "SELECT  
					DISTINCT ciclo
				FROM 
					preceptoria
				WHERE
					alumno=$_REQUEST[alumno]
				ORDER BY
					ciclo DESC";
					
  $alumno = $_REQUEST['alumno'];
	$ciclos_alumno = mysql_query("SELECT  
										distinct ciclo
									FROM 
										preceptoria
									WHERE
										alumno=$alumno
									ORDER BY 
										ciclo DESC",$link) or die ("SELECT  
										ciclo
									FROM 
										preceptoria
									WHERE
										alumno=$alumno
									ORDER BY
										ciclo DESC".mysql_error());
		
		
		while($c_alumno = mysql_fetch_array($ciclos_alumno))
		{
			
	echo '<dt>Preceptoria ciclo '.$c_alumno['ciclo'].'</dt>
			<dd>
					<table width="100%" >'; 
$detalle_preceptoria = mysql_query ("SELECT 
										*
									FROM 
										preceptoria
									WHERE
										ciclo=".$c_alumno['ciclo']."
										AND
										alumno=$alumno
									ORDER BY 
										preceptoria DESC"
									,$link) or die ("SELECT 
										*
									FROM 
										preceptoria
									WHERE
										ciclo=".$c_alumno['ciclo']."
										AND
										alumno=$alumno
									ORDER BY 
										preceptoria DESC".mysql_error()); 	
		
			
	while($d_precep = mysql_fetch_array($detalle_preceptoria))
		   {
			$p_fecha = date("Y-m-d", strtotime($d_precep['fecha']));
			$p_observaciones = $d_precep['observaciones'];
			$p_metas = $d_precep['metas'];
			$p_num = $d_precep['preceptoria'];
			$p_status = $d_precep['fin'];
			if($p_status==1){
				$pstatus="checked";
			}else{
				$pstatus="";
			}
			 
			echo '	<tr>
						<td>
							<b>Preceptor&iacute;a '.$p_num.'</b><br>
							Observaciones<br> 
							<textarea id="obs" cols="100" name="obs" readonly="readonly" disabled="disabled">'.$p_observaciones.'</textarea>
							<br>
							Metas Preceptoría:<br>
							<textarea id="metas" cols="100" name="metas" readonly="readonly" disabled="disabled">'.$p_metas.'</textarea>
							<br>
							Fecha de la preceptoría:<br>
							<input id="fecha" name="fecha" readonly="readonly" disabled="disabled" value="'.$p_fecha.'"></input>
							<br>
							Estatus Preceptor&iacute;a:&nbsp;
							<input id="fin" name="fin" type="checkbox" readonly="readonly" disabled="disabled" '.$pstatus.'></input>
							<br>
							<hr class="hr">
							<br>
						</td>
					</tr>
				';
				}
		echo'
			</table>
		</dd>
		';
		}

echo '</dl>	
	</body>';
 
}else{
	echo "<dl>
		<form name='myForm' id='myForm'>
		<dt>Nueva Preceptoria</dt>
			<dd>
				<table width='890px'>
					<tr>
						<td>
						  <div class='input'>
							<input name='eliminar' id='eliminar' type='image' ;='' onclick='formReset()' src='../im/trash-empty-35.png' > 
								<span class='tooltip'>De click aqu&iacute; para eliminar preceptoria</span>
						  </div>
						</td>
						<td rowspan='100%' width='5'>
							<hr width='200'> 
						</td>
						<td >
							<b>Ciclo</b> $periodo_actual <br>
							<b>Preceptoria</b> 0
						</td>
					</tr>
					<tr>
						<td>
							<input type='hidden' id='preceptoria' name='preceptoria' value='1'>
							<input type='hidden' id='ciclo' name='ciclo' value='$periodo_actual'>
							<input type='hidden' id='alumno' name='alumno' value='$alumno_id'>
							Observaciones Preceptor&iacute;a Actual:<br>
							<textarea id='obs' cols='40' name='obs'></textarea>
						</td>
						
						<td>
							Observaciones Preceptor&iacute;a Anterior:<br>
							<textarea id='obs_' cols='40' name='obs_' readonly='readonly' disabled='disabled' >$ultima_observaciones
							</textarea>
						</td>
					</tr>
					<tr>
						<td>
							Metas Preceptoría Actual:<br>
							<textarea id='metas' cols='40' name='metas' ></textarea>
						</td>
						
						<td>
							Metas Preceptoría Anterior:<br>
							<textarea id='metas_' cols='40' name='metas_' readonly='readonly' disabled='disabled'>$ultima_metas
							</textarea>
						</td>
					</tr>
					<tr>
						<td>
							Fecha de la preceptoría Actual:<br>
							<input id='fecha' name='fecha' readonly='readonly' value='$fechoy'></input>
						</td>
						
						<td>
							Fecha de la preceptoría Anterior:<br>
							<input id='fecha_' name='fecha_' readonly='readonly' disabled='disabled' value='' ></input>
						</td>
					</tr>
					<tr>
						<td>
							Finalizar preceptoría actual:&nbsp;
							<input id='fin' name='fin' type='checkbox'></input>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<input type='button'  value='   Grabar   ' name='grabar' id='grabar' onClick='grabar_registro(1)'></input><br>
							<span id='res' name='res'></span>
						</td>
					</tr>
				</table>
			</dd>
			</form>
			<script>
			
			function formReset()
					{
						document.getElementById('myForm').reset();
					}
			
			
			</script>		
			";
			
	 }
?>					

	<script src="../../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../repositorio/css/jquery-ui.theme.css" /><!-- Libreria DatetimePicker-->
	
	<script type="text/javascript">
	
	   $('dl dd').hide();
       $('dl dt').click(function(){
          if ($(this).hasClass('activo')) {
               $(this).removeClass('activo');
               $(this).next().slideUp();
          } else {
               $('dl dt').removeClass('activo');
               $(this).addClass('activo');
               $('dl dd').slideUp();
               $(this).next().slideDown();
          }
       });
       
       
       $(document).ready(function() {
   $("#fecha").datepicker();
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



$("#fecha").on("change", validar);
    function validar() {  	
            var inicio =  $("#fecha").val();
             var fecha_menor =  $("#fecha_menor").val();     
if(inicio<fecha_menor){
		alert('Fecha Fecha es menor a las fechas permitidas, no debe ser menor a '+<?=$tiempo_max_preceptorias;?>+' días.');
		document.getElementById("fecha").value="<?=$fechoy;?>";
	}
}

var id = $('#id').val();	
var alumno = $('#alumno').val();
var ciclo = $('#ciclo').val();
var preceptoria = $('#preceptoria').val();
var observaciones = $('#obs').val();
var fin = $('#fin').val();
var fecha =$('#fecha').val();
var metas =	$('#metas').val();

	function grabar_registro(tipo)
			{					
				//alert(tipo)
		if($('#metas').val() =="" || $('#obs').val() == ""){
			$("#res").html("<span class='ko'>Asegurese de llenar todos los datos para poder continuar</span>");
			setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
		}else{		
				$('#obs').prop('disabled', true);
				$('#metas').prop('disabled', true);
				$('#fecha').prop('disabled', true);
				$('#fin').prop('disabled', true);
				$('#grabar').prop('disabled', true);
				setTimeout(function() {$('#grabar').fadeOut('fast');}, 0);
				
				if (tipo==1)
					{
						var id = $('#id').val();	
						var alumno = $('#alumno').val();
						var ciclo = $('#ciclo').val();
						var preceptoria = $('#preceptoria').val();
						var observaciones = $('#obs').val();
						var fecha =$('#fecha').val();
						var metas =	$('#metas').val();
						var command = '1';
						
if(document.getElementsByName("fin")[0].checked){
	var fin = '1';
	}else{
	var fin = '0';
	}	
					$.ajax({
					dataType: "json",
					data: {id:id,
							alumno:alumno,
							ciclo:ciclo,
							preceptoria:preceptoria,
							observaciones:observaciones,
							fin:fin,
							fecha:fecha,
							metas:metas,
							command: command
					},
					url:   'captura_preceptoria_reg.php',
					type:  'post',
					beforeSend: function(){
						//Lo que se hace antes de enviar el formulario
					},
						success: function(respuesta){
						//lo que se si el destino devuelve algo
						$("#res").html(respuesta.html);
						setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
						//alert(alumno+' '+ciclo+' '+preceptoria+' '+observaciones+' '+fin+' '+fecha+' '+metas+' '+command)
				  },
					error:	function(xhr,err){ 
								//$("#res").html(xhr.responseText);
								setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
								$("#res").html("Error readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);	
								//alert(alumno+' '+ciclo+' '+preceptoria+' '+observaciones+' '+fin+' '+fecha+' '+metas+' '+command);
			}
		});		
}
else if (tipo==2)
{
						var id = $('#id').val();	
						var alumno = $('#alumno').val();
						var ciclo = $('#ciclo').val();
						var preceptoria = $('#preceptoria').val();
						var observaciones = $('#obs').val();
						var fecha =$('#fecha').val();
						var metas =	$('#metas').val();
						var command = '2';
						
if(document.getElementsByName("fin")[0].checked){
	var fin = '1';
	}else{
	var fin = '0';
	}				
					$.ajax({
					dataType: "json",
					data: {id:id,
						   	alumno:alumno,
							ciclo:ciclo,
							preceptoria:preceptoria,
							observaciones:observaciones,
							fin:fin,
							fecha:fecha,
							metas:metas,
							command: command
					},
					url:   'captura_preceptoria_reg.php',
					type:  'post',
					beforeSend: function(){
						//Lo que se hace antes de enviar el formulario
					},
						success: function(respuesta){
						//lo que se si el destino devuelve algo
						$("#res").html(respuesta.html);		
						setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
						//lert(alumno+' '+ciclo+' '+preceptoria+' '+observaciones+' '+fin+' '+fecha+' '+metas+' '+command)			
				  },
					error:	function(xhr,err){ 
								//$("#res").html(xhr.responseText);
								setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
								$("#res").html(xhr.responseText);
								//alert(alumno+' '+ciclo+' '+preceptoria+' '+observaciones+' '+fin+' '+fecha+' '+metas+' '+command);
			}
		});	
	  }
	}
}
				
	

	</script>
</html>