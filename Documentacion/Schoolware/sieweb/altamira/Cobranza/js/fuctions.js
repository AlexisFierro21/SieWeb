/**
 * @author econtreras
 */
$("#familia").on("change", buscarFamilia);
$("#formato").on("change", preview);

$(document).ready(function() {

			$("#pestanya2").prop('disabled', true);
			$("#pestanya2").hide();
			
			$("#pestanya3").prop('disabled', true);
			$("#pestanya3").hide();
			
			$("#pestanya4").prop('disabled', true);
			$("#pestanya4").hide();
			
			$("#pestanya5").prop('disabled', true);
			$("#pestanya5").hide();
	}
);


function buscarFamilia(){
	
	$familia = $("#familia").val();
	$("#variables").empty();
	$("#loading").empty();
	if( $('#adeudos').prop('checked') ) {
		$activos = "activos";
	}else{
		$activos = "";
	}
	
	
	$.ajax({
		dataType: "html",
		data: {"familia": $familia, "activos": $activos},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			},
        success: function(respuesta){
			$("#log").html(respuesta);
			$("#loading").empty();
		},
		error:	function(xhr,err){ 
			$("#log").html(xhr.responseText);
		}
	});	
}

function buscarTotal(){
	$("#variables").empty();
	$Total = "1";

	if( $('#adeudos').prop('checked') ) {
		$activos = "activos";
	}else{
		$activos = "";
	}
	
	$.ajax({
		dataType: "html",
		data: {"Total": $Total, "activos": $activos},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			},
        success: function(respuesta){
			$("#log").html(respuesta);
			$("#loading").empty();
		},
		error:	function(xhr,err){ 
			$("#log").html(xhr.responseText);
		}
	});	
}

$("input").on("click", function(){		
	
 if($("input:radio[name=filtro]:checked").val() == 'AdeudosTotal'){

 	/////Mostramos la pestaña 4
 	$("#pestanya4").prop('disabled', false);
	$("#pestanya4").show();

 	$("#loading").html("<img src='loading.gif'></img>")

 	$("#log").empty();
  	$("#familia").prop('disabled', true);
  	$("#variables").empty();
  	$Total = "1";
  	$("#loading").empty();
  	
  	if( $('#adeudos').prop('checked') ) {
		$activos = "activos";
	}else{
		$activos = "";
	}
  	
  	$.ajax({
		dataType: "html",
		data: {"Total": $Total, "activos": $activos},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			//$("#loading").html("<img src='loading.gif'></img>")
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("#log").html(respuesta);
			$("#loading").empty();
		},
		error:	function(xhr,err){ 
			$("#log").html(xhr.responseText);
			$("#loading").empty();
		}
	});	
 }
 if($("input:radio[name=filtro]:checked").val() == 'AdeudosFamilia'){
 	
 	////Mostramos la pestaña 4
 	$("#pestanya4").prop('disabled', false);
	$("#pestanya4").show();
 	
 	$("#loading").html("<img src='loading.gif'></img><p>Esperando Seleccione una familia</p>");
 	$("#log").empty();
 	$("#variables").empty();
  	$("#familia").prop('disabled', false);

 }
 if($("input:radio[name=filtro]:checked").val() == 'AdeudosMayor'){
 	$("#log").empty();
 	$("#variables").empty();
  	$("#familia").prop('disabled', true);
  	
  	$Acumulados = "1";
  	if( $('#adeudos').prop('checked') ) {
		$activos = "activos";
	}else{
		$activos = "";
	}
  	alert($activos);
  	$.ajax({
		dataType: "html",
		data: {"Acumulados": $Acumulados, "activos": $activos},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			//Lo que se hace antes de enviar el formulario
			$("#loading").html("<img src='loading.gif'></img>")
			},
        success: function(respuesta){
			//lo que se si el destino devuelve algo
			$("#log").html(html_entity_decode(respuesta));
			
			$("#loading").html("<p>&nbsp;</p>");
			$("#variables").empty();
		},
		error:	function(xhr,err){ 
			$("#log").html(xhr.responseText);
			$("#loading").html("<p>&nbsp;</p>");
			$("#variables").empty();
		}
	});	
 }
 if($("input:radio[name=filtro]:checked").val() == 'AdeudosFiltrado'){
 $("#familia").prop('disabled', true);
 if( $('#adeudos').prop('checked') ) {
		$activos = "activos";
	}else{
		$activos = "";
	}
 $.ajax({
		dataType: "json",
		data: {"filtro": $Total, "activos": $activos},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			$("#loading").html("<img src='loading.gif'></img>")
			},
        success: function(respuesta){
			$("#log").html(html_entity_decode(respuesta));
			$("#loading").empty();
		},
		error:	function(xhr,err){ 
			$("#log").html(xhr.responseText);
		}
	});	
 }

});

function Seleccionado() {
	var $variables = "";
	var $id = $("#formatoMailSeleccionado").val();

  //$("#Enviar").prop('disabled', true);
	$("#variables").html("<img src='loading.gif'></img> ");
	$("#loading").html("<img src='loading.gif'></img> ");
	
    $('#TablaAdeudos tr').each(function (i, row) {
        var $actualrow = $(row);
        $checkbox = $actualrow.find('input:checked').is(':checked');
        $idcheckbox =  $actualrow.find('input:checked').attr("id");
        if($checkbox == true){
        $variables= $variables.concat($idcheckbox);
        }
    });
    
    $.ajax({
		dataType: "html",
		data: {"variables": $variables, "id": $id},
		url:   'mail.php',
        type:  'post',
		beforeSend: function(){
			},
       success: function(respuesta){
			$("#enviados").append(respuesta);
			$("#loading").empty();
			$("#loading").html("<p>&nbsp;</p>");
			$("#variables").html("<p>Proceso finalizado, consulte los resultados.</p>");
			$("#variables").hide(3000);
			$("#result_enviado").html("Enviados Exitosamente.");
			//$("#Enviar").prop('disabled', false);
			//$("#Enviar").removeAttr("disabled");
			
		},
		error:	function(xhr,err){ 
			$("#enviados").append(respuesta);
			$("#loading").html("<p>&nbsp;</p>");
			$("#variables").html("<p>&nbsp;</p>");
			$("#result_enviado").html("Enviados Exitosamente.");	
			//$("#Enviar").prop('disabled', false);
			//$("#Enviar").removeAttr("disabled");
			
		}
	});	 
}

function Actualizar(){
	
	$("#actualizar_cargando").html("<img src='loading.gif'></img>");
	
	$("#shower1").prop('disabled', true);
	$("#Actualizar").prop('disabled', true);
	
	$Actualizar = "1";
	$("#update").html("<img src='loading.gif'></img>");
	$.ajax({
		dataType: "html",
		data: {"Actualizar": $Actualizar},
		url:   'buscar.php',
        type:  'post',
		beforeSend: function(){
			},
        success: function(respuesta){
			$("#update").html("&nbsp;");
			$("#p1").hide(200);
			$("#shower1").hide(200);
			
			$("#p2").show(200);
			$("#shower2").show(200);
			
			$("#pestanya2").prop('disabled', false);
			$("#pestanya2").show();
			$("#actualizar_cargando").html("<p id='continuar_frase' name='continuar_frase'>Continuar</p>");
			$("#continuar_frase").fadeOut(3000);
			
		},
		error:	function(xhr,err){ 
			$("#update").html("&nbsp;");
			$("input").prop('disabled', false);
			$("#p1").hide(200);
			$("#q1").hide(200);
			
			$("#p2").show(200);
			$("#q2").show(200);
			
			$("#actualizar_cargando").html("<p id='continuar_frase' name='continuar_frase'>Continuar</p>");
			$("#Actualizar").fadeOut(3000);
		}
	});	
}

function Ocultar(){
	$("#update").html("&nbsp;");
}

function SeleccionarTodo(){
        $("input[type=checkbox]").prop('checked', true);
        $("#Todo").attr("disabled", "disabled");
        $("#Ninguno").removeAttr("disabled");
}

function SeleccionarNinguno(){
        $("input[type=checkbox]").prop('checked', false);
        $("#Todo").removeAttr("disabled");
        $("#Ninguno").attr("disabled", "disabled");
}

function seleccionarFormato(){
	
	$datos	= "";
	 $.ajax({
	 	   dataType: "html",
           type: "POST",
           url: 'formatos_carta.php',
           data:{"datos": $datos},
           success:function(html) {
             $("#Carta").html(html);
           }
      });
}

function agregarFormato(){
	
	$datos	= "1";
	$titulo = $("#titulo_nuevo").val();
	$mensaje_entrada = $("#mensaje_entrada_nuevo").val();
	$mensaje_salida  = $("#mensaje_salida_nuevo").val();
	$remitente = $("#remitente_nuevo").val();

	 $.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'editFormato.php',
           data:{"datos": $datos,
           		"titulo": $titulo,
           		"mensaje_entrada": $mensaje_entrada,
           		"mensaje_salida": $mensaje_salida,
           		"remitente": $remitente
           		},
           success:function(html) {
             $("#MensajeGuardado").html(html);
           	 $("#MensajeGuardado").hide(3000);	
           	 
           	$("#titulo_nuevo").prop('disabled', true);
			$("#mensaje_entrada_nuevo").prop('disabled', true);
			$("#mensaje_salida_nuevo").prop('disabled', true);
			$("#remitente_nuevo").prop('disabled', true);
           }
      });
}

function preview(){
	
	$formatoMailSeleccionado = $("#formato").val();
  	//alert();
	$("#formatoMailSeleccionado").val($formatoMailSeleccionado)
	
	$datos = "2";
	$id = $("#formato").val();
	
	if($id != ""){
		$("#pestanya3").prop('disabled', false);
		$("#pestanya3").show();
		
	}else{
		$("#pestanya3").prop('disabled', true);
		$("#pestanya3").hide();
	}

	 $.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'editFormato.php',
           data:{"datos": $datos,
           		"id": $id
           		},
           success:function(html) {
             $("#Preview").html(html);
           }
      }); 
      
      
      $.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'editFormato.php',
           data:{"datos": $datos,
           		"id": $id
           		},
           success:function(html) {
             $("#MensajeGuardado").html(html);
           }
      }); 
}
function MuestraResultados(){
	$("#pestanya5").prop('disabled', false);
	$("#pestanya5").show();
 
 	     //$("#tableToModify").find("tr:gt(0)").remove();
 	     //$("#Preview_Formato").html("&nbsp;");
 	     
     
	$("input:checkbox:checked").each(   
    function() {
      	var $tr    = $(this).closest('.tr_clone');
    		var $clone = $tr.clone();
    		$clone.find(':text').val('');
    		$tr.after($clone);
    	}
	);
	
	//Encabezado
	$id = $("#formatoMailSeleccionado").val();
	$encabezado_b = "0";
	$.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'vistaprevia.php',
           data:{"enunciado": $encabezado_b,
           		"id": $id
           		},
           success:function(html) {
             $("#baner").html(html);
           }
      }); 
	
	//
	/*  Mensaje Entrada VP  */
	$encabezado_e = "1";
	$.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'vistaprevia.php',
           data:{"enunciado": $encabezado_e,
           		"id": $id
           		},
           success:function(html) {
             $("#m_entrada_select").html(html);
           }
      }); 
	//	
	$('#TablaAdeudos tr').each(function (i, row) {
        var $actualrow = $(row);
        $checkbox = $actualrow.find('input:checked').is(':checked');
        $idcheckbox =  $actualrow.find('input:checked').attr("id");
        if($checkbox == true){
        
        
        /*
        var row = document.getElementById("TablaAdeudos"); // find row to copy
      	var table = document.getElementById("tableToModify"); // find table to append to
     	var clone = row.cloneNode(true); // copy children too
      	clone.id = "newID"; // change id or other attributes/contents
     	table.appendChild(clone); // add new row to end of table
     	
     	
     	
     	
     	*/
     //
     /* Mensaje Salida VP  */
    $encabezado_s = "2";
	$.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'vistaprevia.php',
           data:{"enunciado": $encabezado_s,
           		"id": $id
           		},
           success:function(html) {
             $("#m_salida_select").html(html);
           }
      }); 
    //
    /* Firma Remitente VP  */
   $encabezado_r = "3";
	$.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'vistaprevia.php',
           data:{"enunciado": $encabezado_r,
           		"id": $id
           		},
           success:function(html) {
             $("#m_firma").html(html);
           }
      });     
        }
    });
	
	
}



function editFormato(){
	
	$formatoMailSeleccionado = $("#formato").val();
	$("#formatoMailSeleccionado").val($formatoMailSeleccionado)
	
	$datos = "3";
	$id = $("#formato").val();
	$titulo = $("#formato").val();
	$mensaje_entrada = "";
	$mensaje_salida = "";
	$remitente = "";
	$titulo = "";	
	
	 $.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'editFormato.php',
           data:{"datos": $datos,
           		"id": $id
           		},
           success:function(html) {
             $("#formato_editando").html(html);
           }
      }); 
}

function seleccionaEditar(){
	
	$id = $("#seleccionaEditar").val();	
		
	$.ajax({
	 	   dataType: "html",
	 	   encoding:"UTF-8",
	 	   type: "post",
           url: 'editandoFormato.php',
           data:{"id": $id},
           success:function(html) {
             $("#formato_editando").html(html);
           }
      }); 
}

function grabarEditandoFormato(){
	
	$id = $("#editID").val();	
	$Nombre =  $("#editNombre").val();
	$Mensaje_Entrada = $("#editMensajeEntrada").val();	
	$Mensaje_Salida = $("#editMensajeSalida").val();
	$Remitente = $("#editRemitente").val();	
	$Tipo = "3";	
	
	$.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'editFormato.php',
           data:{"id": $id,
           		 "titulo": $Nombre,
           		 "mensaje_entrada": $Mensaje_Entrada,
           		 "mensaje_salida": $Mensaje_Salida,
           		 "remitente": $Remitente,
           		 "datos": $Tipo
           },
           success:function(html) {
             $("#StatusGuardado").html(html);
             $("#StatusGuardado").fadeOut("slow");
             
             $("#editID").prop('disabled', true);
             $("#editNombre").prop('disabled', true);
             $("#editMensajeEntrada").prop('disabled', true);
             $("#editMensajeSalida").prop('disabled', true);
             $("#editRemitente").prop('disabled', true);
             
             $("#GuardarCambios_btn").prop('disabled', true);
           }
      }); 
}

/*
function callGrado(){
	$seccion = $("#seccion_n").val();
	
	$("#pestanya4").prop('disabled', false);
	$("#pestanya4").show();
 	
 	$("#loading").html("<img src='loading.gif'></img><p>Esperando Seleccione una familia</p>");
 	$("#log").empty();
 	$("#variables").empty();
  	$("#familia").prop('disabled', false);

/*
       $.ajax({
          type: "POST",
           url: "vistaprevia.php",
           data: {"seccion": $seccion},
          success: function(options){
            //options = "<option value='1'>accoutn1</option><option value='2'>accoutn2</option>"
            $('#grado_n').empty().append(options);
          }
        });
       

}
 */

function buscarFiltro(){
	
$seccion = $("#seccion_n").val();	
$grado = $("#grado_n").val();
$grupo = $("#grupo_n").val();

	$("#pestanya4").prop('disabled', false);
	$("#pestanya4").show();
	$("#familia").prop('disabled', true);

$("#log").empty();
 
$datos = $seccion+$grado+$grupo;
 //alert($datos);
	$.ajax({
	 	   dataType: "html",
	 	   type: "post",
           url: 'buscar.php',
           data:{"filtro": $datos
           		},
           success:function(respuesta) {
            $("#log").html(respuesta);
           }
      }); 
	
}
  $(function() {
    $( "#tabs" ).tabs();
  });