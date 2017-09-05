<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="es"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="es"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="es"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="es"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">	
<style>
	table {width:100%;box-shadow:0 0 10px #f7f7f7;text-align:left;height: 3px; }
	th {padding:5px;background:#3b5998;color:#fff; border-radius: 2px;height: 3px; }
	td {padding:5px;border:solid #ddd;border-width:0 0 1px;height: 3px;}
		.editable span{display:block;}
		.editable span:hover {background:url(edit.png) 90% 50% no-repeat;cursor:pointer; z-index: -1;}
		
		td input{
				height:24px;
				width:200px;
				border:1px solid #ddd;
				padding:0 5px;
				margin:0;
				border-radius:6px;
				vertical-align:middle;
			}
		
a.enlace{
					display:inline-block;
					width:60px;
					height:22px;
					margin:0 0 0 0px;
					overflow:hidden;
					vertical-align: middle;
					alignment-adjust:auto;
    				left: 0px;
   			 		top: 0px;
    				z-index: -1;
					size: auto;
					-webkit-border-radius:6px;
					-moz-border-radius:6px;
					border-radius:6px;
					border:#69AFEF 1px solid;
					color:#0071DD;
					background: #DCECFB;
					alignment-adjust:auto;
					margin:0 0 0 12px;
					size: auto;
					text-decoration:none;
					box-shadow:  0px 1px 2px rgba(0, 0, 0, 0.3);

				}
		
	a:hover {
				background: #8b9dc3; color: #3b5998;
				-webkit-transition: background 0.2s linear;
  				-moz-transition: background 0.2s linear;
  				-o-transition: background 0.2s linear;
  				transition: background 0.2s linear;
  }	
	a img {display: none; }
	a:hover img {
    display: block;
    position: absolute;
    -moz-box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
    -webkit-box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
    -o-box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
    box-shadow: 3px 3px 8px #888888, -3px -3px 3px #CDCDCD;
}

	
	.mensaje{display:block;text-align:center;margin:0 0 20px 0;}
		.ok{display:block;padding:10px;text-align:center;background:green;color:#fff;}
		.ko{display:block;padding:10px;text-align:center;background:red;color:#fff;}
	.mensaje_motivos{display:block;text-align:center;margin:0 0 20px 0;}
		.ok{display:block;padding:10px;text-align:center;background:green;color:#fff;}
		.ko{display:block;padding:10px;text-align:center;background:red;color:#fff;}
	.mensaje_diagnostico{display:block;text-align:center;margin:0 0 20px 0;}
		.ok{display:block;padding:10px;text-align:center;background:green;color:#fff;}
		.ko{display:block;padding:10px;text-align:center;background:red;color:#fff;}	
	
 

</style>
</head>
<body>

<form id="registro" name="registro" action="">
                    <table>
                    	<tr>
							<td colspan="2"><label>Agregar Diagn&oacute;scos</label></td>
                        </tr>
                        <tr>
                        	<td><label>Diagn&oacute;stico:&nbsp;&nbsp;&nbsp;</label></td>
                            <td><input type="text" id="nuevo_diagnostico" name="nuevo_diagnostico"></td>
						</tr>
                        <tr>
                        	<td><label>Descripci&oacute;n:</label></td>
                        	<td><input type="text" name="nuevo_descripcion_diagnostico" id="nuevo_descripcion_diagnostico"></td>
                        </tr>
                        <tr>
                        	<td colspan="2">
                            <a class='enlace insertardiagnostico' href='#'>&nbsp;Guardar&nbsp;</a>
                            </td>
                        </tr>
                     </table>
                     </form>
                     <div id="result_motivos" name="result_motivos"></div>
                        <br>
                        	<div class="mensaje_motivos"></div>
								<table class="editinplace_diagnostico" id="motivos" name="motivos">
									<tr>
										<th>ID.</th>
										<th>Diagn&oacute;stico</th>
										<th>Descripción</th>
									</tr>
								</table>

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script>
	$(document).ready(function() 
	{
		/*  TABLA DE MOTIVOS DE CONSULTA */
		$.ajax({
			type: "GET",
			url: "editinplace_diagnostico.php?tabla=1"
		})
		.done(function(json) {
			json = $.parseJSON(json)
			for(var i=0;i<json.length;i++)
			{
				$('.editinplace_diagnostico').append("<tr>"
											+"<td class='id'>"+json[i].id+"</td>"
											+"<td class='editable' data-campo='diagnostico'><span>"+json[i].diagnostico+"</span></td>"
											+"<td class='editable' data-campo='descripcion'><span>"+json[i].descripcion+"</span></td>"
										+"</tr>");
			}
		});
		
		var td,campo,valor,id,consulta;
		$(document).on("click","td.editable span",function(e)
		{
			e.preventDefault();
			$("td:not(.id)").removeClass("editable");
			td=$(this).closest("td");
			campo=$(this).closest("td").data("campo");
			valor=$(this).text();
			id=$(this).closest("tr").find(".id").text();
			consulta= $("#selected_div").val();
			td.text("").html("<input type='text' name='"+campo+"' value='"+valor+"'>"
							 														+"<br/><a class='enlace guardar' href='#'>&nbsp;Guardar&nbsp;</a>"
																					+"<a class='enlace cancelar' href='#'>&nbsp;Cancelar&nbsp;</a>"
							  +"</input>");
		});
		
		$(document).on("click",".cancelar",function(e)
		{
			e.preventDefault();
			td.html("<span>"+valor+"</span>");
			$("td:not(.id)").addClass("editable");
		});
		
		$(document).on("click",".guardar",function(e)
		{
			$(".mensaje").html("<img src='loading.gif'>");
			e.preventDefault();
			nuevovalor=$(this).closest("td").find("input").val();
			if(nuevovalor.trim()!="")
			{
				$.ajax({
					type: "POST",
					url: "editinplace_diagnostico.php",
					data: { campo: campo, valor: nuevovalor, id:id }
				})
				.done(function( msg ) {
					$(".mensaje").html(msg);
					td.html("<span>"+nuevovalor+"</span>");
					$("td:not(.id)").addClass("editable");
					setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
				});
			}
			else $(".mensaje").html("<p class='ko'>Debes ingresar un valor</p>");
		
		});
		
		/// Guardar pero en consulta ///
		$(document).on("click",".guardar_diagnostico",function(e)
		{
			$(".mensaje").html("<img src='loading.gif'>");
			e.preventDefault();
			nuevovalor=$(this).closest("td").find("input").val();
			if(nuevovalor.trim()!="")
			{
				$.ajax({
					type: "POST",
					url: "motivos_de_consulta.php",
					data: { campo: campo, valor: nuevovalor, id:id, diagnostico: diagnostico}
				})
				.done(function( msg ) {
					$(".mensaje").html(msg);
					td.html("<span>"+nuevovalor+"</span>");
					$("td:not(.id)").addClass("editable");
					setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
				});
			}
			else $(".mensaje").html("<p class='ko'>Debes ingresar un valor</p>");
		
		});
		/*insertar medicamentos*/
		///Aquí manejamos cuando insertamos un nuevo valor
		$(document).on("click",".insertar",function(e)
		{
		  var $nombre = $("#nuevo_diagnostico").val();
		  var $descripcion = $("#nuevo_descripcion_diagnostico").val();
		  var $nombre_largo = $("#nuevo_diagnostico").val().length;
		  var $descripcion_largo = $("#nuevo_descripcion_diagnostico").val().length;
		  ///alert($descripcion_largo+$nombre_largo);
		  if($nombre_largo < 1 ){
			  $nombre_largo = 0;
		  }
		  else{
			$nombre_largo=1;  
		  }
		  if($descripcion_largo < 1 ){
			  $descripcion_largo = 0;
		  }
		  else{
			$descripcion_largo=1;  
		  }
		  var $valida_campos_vacios = $descripcion_largo + $nombre_largo;
		  
			$(".mensaje").html("<img src='loading.gif'>");
			e.preventDefault();
			if( $valida_campos_vacios > 1 )
			{
				$.ajax({
					type: "POST",
					url: "insert_diagnostico.php",
					data: { nombre:  $nombre, descripcion: $descripcion }
				})
				.done(function( msg ) {
					$(".mensaje").html(msg);
					setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
				});
			}
			else $(".mensaje").html("<p class='ko'>Hace falta capturar valores antes de guardar</p>");
		});
		
		//Insertar Motivos de consulta//
		$(document).on("click",".insertardiagnostico",function(e)
		{
		  var $nombre = $("#nuevo_diagnostico").val();
		  var $descripcion = $("#nuevo_descripcion_diagnostico").val();
		  var $nombre_largo = $("#nuevo_diagnostico").val().length;
		  var $descripcion_largo = $("#nuevo_descripcion_diagnostico").val().length;
		  //alert($descripcion_largo);
		  
		  if($nombre_largo < 1 ){
			  $nombre_largo = 0;
		  }
		  else{
			$nombre_largo=1;  
		  }
		  if($descripcion_largo < 1 ){
			  $descripcion_largo = 0;
		  }
		  else{
			$descripcion_largo=1;  
		  }
		  var $valida_campos_vacios = $descripcion_largo + $nombre_largo;
		  
			$(".mensaje_motivos").html("<img src='loading.gif'>");
			e.preventDefault();
			if( $valida_campos_vacios > 1 )
			{
				$.ajax({
					type: "POST",
					url: "insert_diagnostico.php",
					data: { nombre:  $nombre, descripcion: $descripcion }
				})
				.done(function( msg ) {
					$(".mensaje_motivos").html(msg);
					setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 3000);
				});
			}
			else $(".mensaje_motivos").html("<p class='ko'>Hace falta capturar valores antes de guardar</p>");
		});
	});
	
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
</script>