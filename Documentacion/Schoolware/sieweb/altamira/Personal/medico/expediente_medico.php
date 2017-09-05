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
<!DOCTYPE html>
<html>
<head>
	 <script src="jquery-1.10.2.min.js"></script>
     <script src="jquery-ui.js"></script>
     <script type="text/javascript" src="jquery-ui-1.8.1.custom.min.js"></script>
     <script type="text/javascript" src="autocomplete.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>

		body {
scrollbar-face-color: #6685CA;
scrollbar-highlight-color: #6685CA;
scrollbar-shadow-color: #6685CA;
scrollbar-3dlight-color:#FFFFFF;
scrollbar-arrow-color:#FFFFFF;
scrollbar-track-color:#E5E5E5;
scrollbar-drakshadow-color:#000000;

background: #FFFFFF;    
}
		
 /* Especificamos la poscición del contenedor */
.contenedor{
    width: 600px;
    margin: auto;
    background: #dfe3ee; 
    color: #282828;/* Letras  */
    padding: 0px 0px 50px 20px;
	
    border-radius: 4px;
    box-shadow: 0 10px 10px 0px rgba(0, 0, 0, 0.8);
	/*text-shadow: 5px 5px 5px #282828;*/
}

/* Titulo para el contenedor o encabezado del documento */ 
 .contenedor .titulo{
    font-size: 3.2ex;
    font-weight: bold;
    margin-left: -10px;
    margin-bottom: 10px;
	color: white;
	text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
}

/* Tamaño de las pestañas y texto contenido */
#pestanas {
    float: top;
    font-size: 1.5ex;
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
    background: #cccccc; /* Pestaña seleccionada junto con contenedor principal*/
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


#referido_style{
display: inline;		
}
referido_style{
display: inline;		
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

iframe{
	border:0px;
	}
	
</style>


    <script type="text/javascript">
	
    // Dadas la division que contiene todas las pestañas y la de la pestaña que se 
	// quiere mostrar, la funcion oculta todas las pestañas a excepcion de esa.
function cambiarPestanna(pestannas,pestanna) {
    
    // Obtiene los elementos con los identificadores pasados.
    pestanna = document.getElementById(pestanna.id);
    listaPestannas = document.getElementById(pestannas.id);
    
    // Obtiene las divisiones que tienen el contenido de las pestañas.
    cpestanna = document.getElementById('c'+pestanna.id);
    listacPestannas = document.getElementById('contenido'+pestannas.id);
    
    i=0;
    // Recorre la lista ocultando todas las pestañas y restaurando el fondo 
    // y el padding de las pestañas.
    while (typeof listacPestannas.getElementsByTagName('div')[i] != 'undefined'){
        $(document).ready(function(){
            $(listacPestannas.getElementsByTagName('div')[i]).css('display','none');
            $(listaPestannas.getElementsByTagName('li')[i]).css('background','');
            $(listaPestannas.getElementsByTagName('li')[i]).css('padding-bottom','');
        });
        i += 1;
    }
 
    $(document).ready(function(){
        // Muestra el contenido de la pestaña pasada como parametro a la funcion,
        // cambia el color de la pestaña y aumenta el padding para que tape el  
        // borde superior del contenido que esta juesto debajo y se vea de este 
        // modo que esta seleccionada.
        $(cpestanna).css('display','');
        $(pestanna).css('background','#dfe3ee');
        $(pestanna).css('padding-bottom','2px'); 
    });
 
}

function validarFormulario(){
		if (document.getElementById("diagnostico").value == "") //comprueba si el campo nombre esta vacío
			{
				alert ("No puedes dejarlo vacío");
				document.getElementById("diagnostico").focus();   //posicionarse en el campo vacío
				return false;
			}
		else
		return true;
}

<!-- Script para el menú desplegable -->
$(document).ready( function() {
	$('#divMedMuestra').click(function(){
		$('#cont1').slideDown('slow');
		$('#cont2').slideUp('slow');
	});
	$('#divMedOculta').click(function(){
		$('#cont2').slideUp('slow');
		$('#cont1').slideUp('slow');
	});
});

 jQuery(document).ready( function($){
		$( '.responsiveMenuSelect' ).change(function() {
			var loc = $(this).find( 'option:selected' ).val();
			if( loc != '' && loc != '#' ) window.location = loc;
		});
		//$( '.responsiveMenuSelect' ).val('');
	});

$(document).ready(function(){
			$("#mostrar").on( "click", function() {
				$('#target').show();
				$('.target').show();
		 	});
			$("#ocultar").on( "click", function() {
				$('#target').hide();
				$('.target').hide();
		 	});
			
			$("#tipo_referido").on( "click", function() {
				$('#referido_style').show();
				$('.referido_style').show();
		 	});
		});


$(document).ready(function($){
    $('#medicamento').autocomplete({
	source:'medicamentos.php', 
	minLength:1
    });
});

<!-- Script -->
          function addRow(tableID) {
			
               var table = document.getElementById(tableID);
               
			   var rowCount = table.rows.length;
			   var row = table.insertRow(rowCount);            
			   
			   <!-- Check box -->
			   var cell1 = row.insertCell(0);
               var element1 = document.createElement("input");
               element1.type = "checkbox";
               cell1.appendChild(element1);
			   
			   <!-- Input medicamento -->
               var cell2 = row.insertCell(1);
               var element2 = document.createElement("input");
               element2.type = "text";
			   element2.name = "medicamento";
			   element2.id = "medicamento";
			   element2.className = "medicamento";
			   element2.setAttribute("required", "true");  
               cell2.appendChild(element2);
			   
			     <!-- Input cantidad -->
			   var cell3 = row.insertCell(2);
			   var element3 = document.createElement("input");
               element3.type = "text";
			   element3.name = "dosis";
			   element3.id = "dosis";
			   element3.className = "medicamento";
               cell3.appendChild(element3);
			   
			   <!-- Input dosis -->
			   var cell4 = row.insertCell(3);
			   var element4 = document.createElement("input");
               element4.type = "text";
			   element4.name = "dosis";
			   element4.id = "dosis";
			   element4.className = "medicamento";
               cell4.appendChild(element4);
			   
			   <!-- Input via_administracion -->
				var cell5 = row.insertCell(4);
				var element5 = document.createElement("select");
					element5.id = "via_administracion";
					element5.name = "via_administracion";
					element5.className = "medicamento";
					cell5.appendChild(element5);
								
				var option = document.createElement("option");
					option.value = "0";
					option.appendChild(document.createTextNode('Selecciona...'));
					element5.appendChild(option);
									
					option = document.createElement('option');
					option.value = "1";
					option.appendChild(document.createTextNode(' Oral '));
					element5.appendChild(option);
									
					option = document.createElement('option');
					option.value = '2';
					option.appendChild(document.createTextNode(' Intramuscular '));
					element5.appendChild(option);
	
					option = document.createElement('option');
					option.value = '3';
					option.appendChild(document.createTextNode(' Subcutanea '));
					element5.appendChild(option);
									
					option = document.createElement('option');
					option.value = '4';
					option.appendChild(document.createTextNode(' Tópica '));
					element5.appendChild(option);
									
					option = document.createElement('option');
					option.value = '5';
					option.appendChild(document.createTextNode(' Ocular '));
					element5.appendChild(option);
														
			   <!-- frecuencia -->
				var cell6 = row.insertCell(5);
				var element6 = document.createElement("select");
					element6.id = "frecuencia";
					element6.name = "frecuencia";
					element6.className = "medicamento";
					cell6.appendChild(element6);
								
				var option = document.createElement("option");	
					option.value = "0";
					option.appendChild(document.createTextNode('Selecciona...'));
					element6.appendChild(option);
									
					option = document.createElement('option');
					option.value = '1';
					option.appendChild(document.createTextNode(' Dosis única '));
					element6.appendChild(option);
									
					option = document.createElement('option');
					option.value = '2';
					option.appendChild(document.createTextNode(' 1 Hora '));
					element6.appendChild(option);
	
					option = document.createElement('option');
					option.value = '3';
					option.appendChild(document.createTextNode(' 2 Horas '));
					element6.appendChild(option);
									
					option = document.createElement('option');
					option.value = '4';
					option.appendChild(document.createTextNode(' 4 Horas '));
					element6.appendChild(option);
									
					option = document.createElement('option');
					option.value = '5';
					option.appendChild(document.createTextNode(' 6 Horas '));
					element6.appendChild(option);
									
					option = document.createElement('option');
					option.value = '6';
					option.appendChild(document.createTextNode(' 8 Horas '));
					element6.appendChild(option);
									
					option = document.createElement('option');
					option.value = '7';
					option.appendChild(document.createTextNode(' 12 Horas '));
					element6.appendChild(option);
									
					option = document.createElement('option');
					option.value = '8';
					option.appendChild(document.createTextNode(' 24 Horas '));
					element6.appendChild(option);
					
					<!-- Refresh -->

$(function() {
    var availableTags = [
	<?php echo $collection; ?>
    ];
	console.log(availableTags);
    $( "[id*=medicamento]" ).autocomplete({
      source: availableTags
    });
  });
	

}//Termina Funcion Agregar
		 
/* Autocompletar id=medicamento */
  $(function() {
    var availableTags = [
	<?php echo $collection; ?>
    ];
	console.log(availableTags);
    $( "[id*=medicamento]" ).autocomplete({
      source: availableTags
    });
  });
/* Termina autocompletar */  
  
		  
  function deleteRow(tableID) {

               try {
               var table = document.getElementById(tableID);
               var rowCount = table.rows.length;
               for(var i=0; i<rowCount; i++) {
                    var row = table.rows[i];
                    var chkbox = row.cells[0].childNodes[0];
                    if(null != chkbox && true == chkbox.checked) {
                         table.deleteRow(i);
                         rowCount--;
                         i--;
                    }
               }
               }catch(e) {
                    alert(e);
               }
}// Termina Funcion Eliminar

<!-- Autocompletar-->
var posicionCampo = 1;

function agregarMedicamento(tableID) {

    nuevaFila = document.getElementById(tableID).insertRow(-1);
    nuevaFila.id = posicionCampo;

    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<tr><td><input type='text' size='15' name='medicamento[" + posicionCampo + "]' id='medicamento[" + posicionCampo + "]' required></td>";

    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td><input type='text' size='10' name='cantidad[" + posicionCampo + "]' id='cantidad[" + posicionCampo + "]' required></td>";

    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td><input type='text' size='10' name='dosis[" + posicionCampo + "]' id='dosis[" + posicionCampo + "]' required></td>";
	
	nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td>"+
								"<select  id='via_administracion[" + posicionCampo + "]' name='via_administracion[" + posicionCampo + "]' class='medicamento' />"+
									"<option value='0'>&nbsp;Selecciona...&nbsp;</option>"+
									"<option value='Oral'>&nbsp;Oral&nbsp;</option>"+
									"<option value='Intramuscular'>&nbsp;Intramuscular&nbsp;</option>"+
									"<option value='Subcutanea'>&nbsp;Subcutanea &nbsp;</option>"+
									"<option value='T&oacute;pica'> T&oacute;pica </option>"+
									"<option value='Ocular'> Ocular </option>"+
								"</select>"+
							"</td>";
	
	nuevaCelda = nuevaFila.insertCell(-1);
	nuevaCelda.innerHTML = "<td>"+
								"<select id='frecuencia["+posicionCampo +"]' name='frecuencia["+posicionCampo +"]' class='medicamento' />"+
               						"<option value='0'> Selecciona... </option>"+
                        			"<option value='Dosis &uacute;nica'> Dosis &uacute;nica </option>"+
                        			"<option value='1 Hora'> 1 Hora </option>"+
                       			 	"<option value='2 Horas'> 2 Horas </option>"+
                        			"<option value='4 Horas'> 4 Horas </option>"+
                        			"<option value='6 Horas'> 6 Horas </option>"+
                        			"<option value='8 Horas'> 8 Horas </option>"+
                        			"<option value='12 Horas'> 12 Horas </option>"+
                        			"<option value='24 Horas'> 24 Horas </option>"+
               					"</select>"+
							"</td>";
	
	
	
    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td><input type='button' value='-' onclick='eliminarMedicamento(this)'></td></tr>";

    posicionCampo++;

 var availableTags = [
	<?php echo $collection; ?>
    ];
	console.log(availableTags);
    $( "[id*=medicamento]" ).autocomplete({
      source: availableTags
    });
}


function eliminarMedicamento(obj) {

    var oTr = obj;

    while(oTr.nodeName.toLowerCase() != 'tr') {

        oTr=oTr.parentNode;

    }

    var root = oTr.parentNode;

    root.removeChild(oTr);

}
	
</script>

    <title>			</title>

</head>
    <div class="contenedor">
        <div class="titulo">Expediente M&eacute;dico</div>
        <div id="pestanas">
            <ul id=lista>
                <li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>&nbsp;&nbsp;Alumno(a)&nbsp;&nbsp;</a></li>
                <li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>&nbsp;&nbsp;Personal&nbsp;&nbsp;</a></li>
                <li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>&nbsp;&nbsp;Familiares&nbsp;&nbsp;</a></li>
                <li id="pestana4"><a href='javascript:cambiarPestanna(pestanas,pestana4);'>&nbsp;&nbsp;Otros&nbsp;&nbsp;</a></li>
                <li id="pestana5"><a href='javascript:cambiarPestanna(pestanas,pestana5);'>&nbsp;&nbsp;Reportes&nbsp;&nbsp;</a></li>
                <li id="pestana6"><a href='javascript:cambiarPestanna(pestanas,pestana6);'>&nbsp;&nbsp;Configuraci&oacute;n&nbsp;&nbsp;</a></li>
            </ul>
        </div>

        <body onLoad="javascript:cambiarPestanna(pestanas,pestana1);">
<!-- Aquí ponemos las opciones para capturar por Alumno(a) -->
        <div id="contenidopestanas">
            <div id="cpestana1">
 <!-- Insertamos la tabla -->

 <form name="formularioAlumnos" method="post" action="../consulta.php"  accept-charset="UTF-8" >
 
<table> 
	<tr>
		<td>
	<input type="hidden" value="1" name="tipo_de_paciente" id="tipo_de_paciente">
<br />
    		<label>Fecha de la consulta:</label>
            <br>
           <input name="fecha" type="text" disabled="disabled" id="fecha" value="<?php echo date("Y-m-d H:i:s"); ?>" size="25" readonly="readonly" >
		</td>
    	<td>
    	</td>
    	<th>
    	</th>
  	<tr>  
    	<td>
    	<label>Secci&oacute;n:</label>
    	<br>
		<select name="seccion" id="seccion">
				<option value="" selected>- Seleccione una Secci&oacute;n -</option>
					<?php
                    
						$seccion = dameSeccion();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['seccion']."'>".$registro['nombre']."</option>";
							}
						
					?>
		</select>
		</td>
   		<td>
		<label>Grado:</label>
        <br>
		<select name="grado" id="grado" >
				<option value="">- seleccione una secci&oacute;n-</option>
		</select>
		</td>
 	<tr>
      	<td>
    	<label>Grupo:</label>
		<br>
        <select name="grupo" id="grupo">
				<option value="">- seleccione un grado -</option>
		</select>
		</td>
      	<td>
    	<label>Alumno:</label>
        <br>
		<select name="alumno" id="alumno">
				<option value="">- seleccione un alumno -</option>
		</select>
		</td>
      </tr>
      <tr>
      	<td>
    	   <label>Matr&iacute;cula:</label>
           <br>
           <input type="text" name="paciente" id="paciente" align="middle" size="16" required >
		</td>
        <td>
        	<label>Referido Por:</label>
            <br>
            <select name="referido" id="referido">
            			<option value=''>- Seleccione quien refiere -</option>
            			<option value='Antes de la 08:00 hrs'>&nbsp;Antes de la 08:00 hrs&nbsp;</option>
						<option value='Despues de las 14:00 hrs'>&nbsp;Despues de las 14:00 hrs&nbsp;</option>
						<option value='Receso'>&nbsp;Receso&nbsp;</option>
						<option value='Deportes'>&nbsp;Deportes&nbsp;</option>
						<option value='Visitante'>&nbsp;Visitante&nbsp;</option>
						<option value='S&aacute;bado'>&nbsp;S&aacute;bado&nbsp;</option>
						<option value='Ajedrez'>&nbsp;Ajedrez&nbsp;</option>
						<option value='M&uacute;sica'>&nbsp;M&uacute;sica&nbsp;</option>
						<option value='Partido por la Tarde'>&nbsp;Partido por la Tarde&nbsp;</option>
	
				<?php
						$profesor = dameProfesor();
								
						foreach($profesor as $indice => $registro){
							echo "<option value='".$registro['nombre']."&nbsp;".$registro['apellido_paterno']."&nbsp;".$registro['apellido_materno']."'>&nbsp;".$registro['nombre']."&nbsp;".$registro['apellido_paterno']."&nbsp;".$registro['apellido_materno']."&nbsp;</option>";
							}
					?>
            </select>
        </td>
        <th>
        </th>
     </tr>
     <tr>
     	<td colspan="2">
<!-- Aquí empezamos los datos a llenar los datos del historial médico del alumno(a) mediante un frame automático -->    

<div id="txtMedico" class="target" >Cargando informaci&oacute;n, por favor espere un poco...</div>    
    	<input type="button" id="mostrar" name="boton1" value="Mostrar Datos del paciente" onClick="buscarMedico($('#paciente').val)">
	    <input type="button" id="ocultar" name="boton2" value="Ocultar Datos del paciente">
		</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
   		<!--<textarea name="motivo_consulta" id="moltivo_consulta" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="motivo_consulta" id="motivo_consulta">
				<option value="" selected>- Seleccione un motivo -</option>
					<?php
                    
						$seccion = dameMotivos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['motivo']."'>".$registro['motivo']."</option>";
							}
						
					?>
		</select>
        <br>
        <label>
        	Motivo de la consulta
		</label>   
		</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
   		<textarea id="exploracion_fisica" name="exploracion_fisica"  rows="4" cols="65" required ></textarea>
        <br>
        <label>Exploración Física</label> 
		</td>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
  		<!--<textarea name="diagnostico" id="diagnostico" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="diagnostico" id="diagnostico">
				<option value="" selected>- Seleccione un diagn&oacute;stico -</option>
					<?php
                    
						$seccion = dameDiagnosticos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['diagnostico']."'>".$registro['diagnostico']."</option>";
							}
					?>
		</select>
  		<br>
   		<label>Diagn&oacute;stico</label>
        </td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
        <td colspan="2"> 		
		<textarea id="indicaciones" name="indicaciones" rows="4" cols="65" required ></textarea>
        <br>
        <label>Indicaciones</label>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>    
	<tr>
	<td colspan="2">
<table id="tablaMedicamentoAlumnos" name="tablaMedicamentoAlumnos">
        <tbody>
         <tr>
                <td align="left" colspan="6"><input onClick="agregarMedicamento('tablaMedicamentoAlumnos')" type="button" value="Agregar Medicamento"></td>
         </tr>
         <tr>
                <td class="med_encabezado">Medicamento</td>
                <td class="med_encabezado">Cantidad</td>
                <td class="med_encabezado">Dosis</td>
                <td class="med_encabezado">V&iacute;a de administraci&oacute;n</td>
                <td class="med_encabezado">Frecuencia</td>
                <td class="med_encabezado">&nbsp;-&nbsp;</td>
         </tr>
        </tbody>
    </table>
    	</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
      	<td colspan="2">
		<input type="submit" class="btn" value="Grabar Consulta" >
   		<input type="reset"  value="Borrar datos" >
</form>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>
</table>
            </div>
<!-- Aquí cargamos Personal -->            
            <div id="cpestana2">

<form name="formularioProfesores" method="post" action="../consulta.php"  accept-charset="UTF-8">
<input type="hidden" value="2" name="tipo_de_paciente" id="tipo_de_paciente">

<table>
	<tr>
		<td colspan="2">
        	<br/>
			<label>Fecha de la consulta:</label>
			<br/>
        	<input name="fecha" type="text" disabled="disabled" id="fecha" value="<?php echo date("Y-m-d H:i:s"); ?>" size="25" readonly="readonly" >
		</td>
	</tr>
	<tr>
    	<td>		
			<label>Personal:</label>
            <br>
			<select name="profesor" id="profesor" text="30">
				<option value="" selected>- Seleccione Personal -</option>
					<?php
						$profesor = dameProfesor();
		
						foreach($profesor as $indice => $registro){
							echo "<option value='".$registro['empleado']."'>&nbsp;".$registro['nombre']."&nbsp;".$registro['apellido_paterno']."&nbsp;".$registro['apellido_materno']."&nbsp;</option>";
							}
					?>
			</select>
        </td>
     </tr>
     <tr>
     	<td>
        <br />
        <br />
        <br />
        ID Empleado:&nbsp;
        <br>
        <input id="idempleado" name="idempleado" type="text" align="middle" size="17" required>
     	</td>
     </tr>    
<!-- Aquí acabamos el frame que se carga automático -->   
<tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
   		<!--<textarea name="motivo_consulta" id="moltivo_consulta" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="motivo_consulta" id="motivo_consulta">
				<option value="" selected>- Seleccione un motivo -</option>
					<?php
                    
						$seccion = dameMotivos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['motivo']."'>".$registro['motivo']."</option>";
							}
						
					?>
		</select>
        <br>
        <label>
        	Motivo de la consulta
		</label>   
		</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
   		<textarea id="exploracion_fisica" name="exploracion_fisica"  rows="4" cols="65" required ></textarea>
        <br>
        <label>Exploración Física</label> 
		</td>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
  		<!--<textarea name="diagnostico" id="diagnostico" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="diagnostico" id="diagnostico">
				<option value="" selected>- Seleccione un diagn&oacute;stico -</option>
					<?php
                    
						$seccion = dameDiagnosticos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['diagnostico']."'>".$registro['diagnostico']."</option>";
							}
					?>
		</select>
  		<br>
   		<label>Diagn&oacute;stico</label>
        </td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
        <td colspan="2"> 		
		<textarea id="indicaciones" name="indicaciones" rows="4" cols="65" required ></textarea>
        <br>
        <label>Indicaciones</label>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>    
	<tr>
	<td colspan="2">
<table id="tablaMedicamentoProfesor" name="tablaMedicamentoProfesor">
        <tbody>
         <tr>
                <td align="left" colspan="6"><input onClick="agregarMedicamento('tablaMedicamentoProfesor')" type="button" value="Agregar Medicamento"></td>
         </tr>
         <tr>
                <td class="med_encabezado">Medicamento</td>
                <td class="med_encabezado">Cantidad</td>
                <td class="med_encabezado">Dosis</td>
                <td class="med_encabezado">V&iacute;a de administraci&oacute;n</td>
                <td class="med_encabezado">Frecuencia</td>
                <td class="med_encabezado">&nbsp;-&nbsp;</td>
         </tr>
        </tbody>
    </table>
    	</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
      	<td colspan="2">
		<input type="submit" class="btn" value="Grabar Consulta" >
   		<input type="reset"  value="Borrar datos" >
</form>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>
</table>
            </div>
            <div id="cpestana3">
<!-- Aquí cargamos Padre/Madre -->            
  <form name="formularioPadreMadre" method="post" action="../consulta.php"  accept-charset="UTF-8">            
	<input type="hidden" value="3" name="tipo_de_paciente" id="tipo_de_paciente">
<table>
  <tr>
    <td>
    		<br>
    		<label>Fecha de la consulta:</label>
            <br>
           <input name="fecha" type="text" disabled="disabled" id="fecha" value="<?php echo date("Y-m-d H:i:s"); ?>" size="25" readonly="readonly" >
    </td>

  </tr>
  <tr>
    <td>
    		<label>Familia:&nbsp;</label>
            <br>
    		<select name="familia" id="familia">
            	<option value="">- Seleccione una familia  - </option>
                <?php
						$familia = dameFamilia();
		
						foreach($familia as $indice => $registro){
							echo "<option value='".$registro['familia']."'>&nbsp;".$registro['nombre_familia']."&nbsp;</option>";
							}
					?>
                
            </select>
    </td>
  </tr>
  <tr>
    <td>
    		<label>Familiares</label>
            <br>
            <select name="padre_madre"  id="padre_madre">
            	<option value="">-Seleccione Padre / Madre </option>
  
            </select>
     </td>
  	</tr>
   	<tr>
     	<td>
        ID Familia:&nbsp; 
        <br>
         <input type="text" name="idfamilia" id="idfamilia" align="middle" size="16" required/>
     	</td>
    </tr>
<!-- Aquí acabamos el frame que se carga automático -->   
	<tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
   		<!--<textarea name="motivo_consulta" id="moltivo_consulta" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="motivo_consulta" id="motivo_consulta">
				<option value="" selected>- Seleccione un motivo -</option>
					<?php
                    
						$seccion = dameMotivos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['motivo']."'>".$registro['motivo']."</option>";
							}
						
					?>
		</select>
        <br>
        <label>
        	Motivo de la consulta
		</label>   
		</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
   		<textarea id="exploracion_fisica" name="exploracion_fisica"  rows="4" cols="65" required ></textarea>
        <br>
        <label>Exploración Física</label> 
		</td>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
  		<!--<textarea name="diagnostico" id="diagnostico" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="diagnostico" id="diagnostico">
				<option value="" selected>- Seleccione un diagn&oacute;stico -</option>
					<?php
                    
						$seccion = dameDiagnosticos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['diagnostico']."'>".$registro['diagnostico']."</option>";
							}
					?>
		</select>
  		<br>
   		<label>Diagn&oacute;stico</label>
        </td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
        <td colspan="2"> 		
		<textarea id="indicaciones" name="indicaciones" rows="4" cols="65" required ></textarea>
        <br>
        <label>Indicaciones</label>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>    
	<tr>
	<td colspan="2">
<table id="tablaMedicamentoPadreMadre" name="tablaMedicamentoPadreMadre">
        <tbody>
         <tr>
                <td align="left" colspan="6"><input onClick="agregarMedicamento('tablaMedicamentoPadreMadre')" type="button" value="Agregar Medicamento"></td>
         </tr>
         <tr>
                <td class="med_encabezado">Medicamento</td>
                <td class="med_encabezado">Cantidad</td>
                <td class="med_encabezado">Dosis</td>
                <td class="med_encabezado">V&iacute;a de administraci&oacute;n</td>
                <td class="med_encabezado">Frecuencia</td>
                <td class="med_encabezado">&nbsp;-&nbsp;</td>
         </tr>
        </tbody>
    </table>
    	</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
      	<td colspan="2">
		<input type="submit" class="btn" value="Grabar Consulta" >
   		<input type="reset"  value="Borrar datos" >
</form>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>
</table>
</div>
   	 <div id="cpestana4">
<form name="formularioOtros" method="post" action="../consulta.php"  accept-charset="UTF-8">
<input type="hidden" value="4" name="tipo_de_paciente" id="tipo_de_paciente">

<table>
	<tr>
		<td colspan="2">
        	<br/>
			<label>Fecha de la consulta:</label>
			<br/>
        	<input name="fecha" type="text" disabled="disabled" id="fecha" value="<?php echo date("Y-m-d H:i:s"); ?>" size="25" readonly="readonly" >
		</td>
	</tr>
	<tr>
      	
        <td>
        	<br>
            <label>Nombre del Paciente:</label>
            <br>
            <input name="paciente" type="text" id="paciente" required> 
        </td>
        <td>
        	<input type="radio" name="tipo_referido" id="tipo_referido" value="1" onClick="buscarReferido(1)"><label>Familias</label>
            <br>
            <input type="radio" name="tipo_referido" id="tipo_referido" value="2" onClick="buscarReferido(2)"><label>Personal</label>
            <br>
            <input type="radio" name="tipo_referido" id="tipo_referido" value="3" onClick="buscarReferido(3)"><label>Externos</label>
        </td>
        </tr>
        <td colspan="2">
        	<div id="txtReferido" class="referido_style" >Espere un momento por favor...</div> 
        </td>
        <tr>
     </tr>
     <tr> 
      	<th>
        </th>
      </tr>      
      </tr>
      <tr>
        <td colspan="2">
   		<!--<textarea name="motivo_consulta" id="moltivo_consulta" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="motivo_consulta" id="motivo_consulta">
				<option value="" selected>- Seleccione un motivo -</option>
					<?php
                    
						$seccion = dameMotivos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['motivo']."'>".$registro['motivo']."</option>";
							}
						
					?>
		</select>
        <br>
        <label>
        	Motivo de la consulta
		</label>   
		</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
   		<textarea id="exploracion_fisica" name="exploracion_fisica"  rows="4" cols="65" required ></textarea>
        <br>
        <label>Exploración Física</label> 
		</td>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
        <td colspan="2">
  		<!--<textarea name="diagnostico" id="diagnostico" rows="4" cols="65" required  ></textarea>-->
        <!--<br>-->
        <select name="diagnostico" id="diagnostico">
				<option value="" selected>- Seleccione un diagn&oacute;stico -</option>
					<?php
                    
						$seccion = dameDiagnosticos();
		
						foreach($seccion as $indice => $registro){
							echo "<option value='".$registro['diagnostico']."'>".$registro['diagnostico']."</option>";
							}
					?>
		</select>
  		<br>
   		<label>Diagn&oacute;stico</label>
        </td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
        <td colspan="2"> 		
		<textarea id="indicaciones" name="indicaciones" rows="4" cols="65" required ></textarea>
        <br>
        <label>Indicaciones</label>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>    
	<tr>
	<td colspan="2">
<table id="tablaMedicamentoOtros" name="tablaMedicamentoOtros">
        <tbody>
         <tr>
                <td align="left" colspan="6"><input onClick="agregarMedicamento('tablaMedicamentoOtros')" type="button" value="Agregar Medicamento"></td>
         </tr>
         <tr>
                <td class="med_encabezado">Medicamento</td>
                <td class="med_encabezado">Cantidad</td>
                <td class="med_encabezado">Dosis</td>
                <td class="med_encabezado">V&iacute;a de administraci&oacute;n</td>
                <td class="med_encabezado">Frecuencia</td>
                <td class="med_encabezado">&nbsp;-&nbsp;</td>
         </tr>
        </tbody>
    </table>
    	</td>
      </tr>
      <tr>
      	<th>
        </th>
      </tr>
      <tr>
      	<td colspan="2">
		<input type="submit" class="btn" value="Grabar Consulta" >
   		<input type="reset"  value="Borrar datos" >
</form>
		</td>
	</tr>
    <tr>
    	<th>
        </th>
    </tr>
</table>        
      </div>
      <div id="cpestana5">
  
         <iframe src="reportes.php" width="550" height="400"></iframe>
       
      </div>
      <div id="cpestana6">
      			<title> Edici&oacute;n de medicamentos, motivos de consulta & diagn&oacute;stico </title>
                <iframe src="funciones/configuracion.html" width="550" height="400"></iframe>
      </div>
<script>
$("#seccion").on("change", buscarGrado);
$("#grado").on("change", buscarGrupo);
$("#grupo").on("change", buscarAlumno);
$("#alumno").on("change", buscarMatricula);
$("#familia").on("change", buscarPadreMadre);
$("#profesor").on("change", buscarIDEmpleado);
//$('input[type="radio"]').on("checked", inputRadio);

//$("#medicamento").on("change", jsCalculaTotal);

 $("#paciente").keydown(function(e){
        e.preventDefault();
    });

 $("#idempleado").keydown(function(e){
        e.preventDefault();
    });

$("#idfamilia").keydown(function(e){
        e.preventDefault();
    });


function buscarGrado(){

	$("#paciente").html("value=''");
	paciente.value = "";
	$("#grupo").html("<option value=''>- primero seleccione un Grado! -</option>");
	$("#alumno").html("<option value=''>- primero seleccione un Grupo! -</option>");
	
		$seccion = $("#seccion").val();

			if($seccion == ""){
				$("#grado").html("<option value=''>- primero seleccione una secci&oacute;n! -</option>");
					 }
				else {
					$.ajax({
					dataType: "json",
					data: {"seccion": $seccion},
					url:   'buscar.php',
					type:  'post',
					beforeSend: function(){
						//Lo que se hace antes de enviar el formulario
					},
						success: function(respuesta){
						//lo que se si el destino devuelve algo
						$("#grado").html(respuesta.html);
						
				  },
					error:	function(xhr,err){ 
					alert("Grado readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
			}
		});
	}
}

function buscarGrupo(){
	
	$grado = $("#grado").val();
	$seccion = $("#seccion").val();
	
	$grado_seccion = $grado.concat($seccion);
	
	$("#alumno").html("<option value=''>- primero seleccione un Grupo! -</option>");
	//alert($grado);

if($grado == ""){
			$("#grupo").html("<option value=''>- primero seleccione un grado! -</option>");
	}
	else {
	$.ajax({
		dataType: "json",
		data: {"grado": $grado},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("#grupo").html(respuesta.html);
		},
		error:	function(xhr,err){ 
			alert("Grupo readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
		}
	});	
  }
}
function buscarAlumno(){
	
	$grupo = $("#grupo").val();
	//alert($grupo);
if($grupo == ""){
			$("#alumno").html("<option value=''>- primero seleccione un grupo! -</option>");
	}
	else {
	$.ajax({
		dataType: "json",
		data: {"grupo": $grupo},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("#alumno").html(respuesta.html);
		},
		error:	function(xhr,err){ 
			alert("Alumno readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
		}
	});	
  }
}

function buscarMatricula(str){
	$alumno = $("#alumno").val();
	//alert($alumno);
	document.getElementById("paciente").value = $alumno;

}


function buscarIDEmpleado(){
	$profesor = $("#profesor").val();
	document.getElementById("idempleado").value = $profesor;
}

function buscarMedico(str){
	$alumno = $("#paciente").val();
	if ($alumno == "") {
        document.getElementById("txtMedico").innerHTML = "";
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

function buscarPadreMadre(){
$familia = $("#familia").val();
$padre_madre = 	$("#padre_madre").val();
	document.getElementById("idfamilia").value = $familia;

if($familia == ""){
			$("#padre_madre").html("<option value=''>- Primero seleccione una familia! -</option>");
	}
	else {
	$.ajax({
		dataType: "json",
		data: {"familia": $familia ,
			   "padre_madre": $padre_madre
				},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("#padre_madre").html(respuesta.html);
		},
		error:	function(xhr,err){ 
			alert("Grupo readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
		}
	});	
  }
}

var tabla = document.getElementById('tablaMedicamentoAlumno');
var numFilas = tabla.rows.length;
var numColumnas = tabla.rows[0].cells.length;
var texto = tabla.rows[1].cells[2].innerHTML;

$( "#paciente" ).click(function() {
  alert( "Handler for .click() called." );
});

function buscarReferido(valor){
	
	//document.getElementById("r_tipo").value = valor;
	$referido = valor;
	document.getElementById("txtReferido").style.display = "inline";
	
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtReferido").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","funciones/referidos_otros.php?referido="+$referido,true);
        xmlhttp.send();
    
}
//document.getElementById("txtReferido").style.display = "inline";
</script>
</body>
</html>
