<?php
//include('../../../config.php');
//require_once("../funciones.php");
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="es"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="es"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="es"> <![endif]-->	
<!--[if (gte IE 9)|!(IE)]><!--><html lang="es"> <!--<![endif]-->
<head>
	 <script src="jquery-1.10.2.min.js"></script>
     <script src="jquery-ui.js"></script>
     <script type="text/javascript" src="../jquery-ui-1.8.1.custom.min.js"></script>
     <script type="text/javascript" src="../autocomplete.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">	
<style>
*{padding:0;margin:0;}
	.contenedor{margin:60px auto;width:400px;font-family:sans-serif;font-size:15px;}
	table {width:100%;box-shadow:0 0 10px #ddd;text-align:left;}
	th {padding:5px;background:#3b5998;color:#fff; border-radius: 2px;}
	td {padding:5px;border:solid #ddd;border-width:0 0 1px;}
		.editable span{display:block;}
		.editable span:hover {background:url(edit.png) 90% 50% no-repeat;cursor:pointer; z-index: -1;}
		.delete span{display:block;}
		.delete span:hover { no-repeat;cursor:pointer;
		box-shadow: 2px 2px 5px #999;
		}

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
					width:65px;
					height:20px;
					margin:0 0 0 12px;
					overflow:hidden;
					/*text-indent:-999em;*/
					vertical-align:middle;
    				left: 0px;
   			 		top: 0px;
    				z-index: -1;
					size: auto;
				}
				
			.guardar{
					border:#BBB 5px solid; 
					color:#5f5e5e; 
					display:block; 
					float:left; 
					padding:9px 28px; 
					margin:1px 10px 1px 10px;
					alignment-adjust:auto;
					cursor:pointer;
					-webkit-border-radius:6px;
					-moz-border-radius:6px;
					border-radius:6px;
					-khtml-border-radius: 6px;
						background-image:  url(images/guardar.png);
						z-index: -1;
					}
					
			.cancelar{
					border:#BBB 5px solid; 
					color:#5f5e5e; 
					display:block; 
					float:left; 
					padding:9px 28px; 
					margin:1px 10px 1px 10px;
					alignment-adjust:auto;
					cursor:pointer;
					-webkit-border-radius:6px;
					-moz-border-radius:6px;
					border-radius:6px;
					-khtml-border-radius: 6px;
						background-image:  url(images/cancel.png);
						z-index: -1;
					}
					
			.insertar{
					border:#BBB 5px solid; 
					color:#5f5e5e; 
					display:block; 
					float:left; 
					padding:9px 28px; 
					margin:1px 10px 1px 10px;
					alignment-adjust:auto;
					cursor:pointer;
					-webkit-border-radius:6px;
					-moz-border-radius:6px;
					border-radius:6px;
					-khtml-border-radius: 6px;
						background-image:  url(images/guardar.png);
						z-index: -1;
					}
					
		.insertarmotivo{
					border:#BBB 5px solid; 
					color:#5f5e5e; 
					display:block; 
					float:left; 
					padding:9px 28px; 
					margin:1px 10px 1px 10px;
					alignment-adjust:auto;
					cursor:pointer;
					-webkit-border-radius:6px;
					-moz-border-radius:6px;
					border-radius:6px;
					-khtml-border-radius: 6px;
						background-image:  url(images/guardar.png);
						z-index: -1;
					}
					
		.insertar_diagnostico{
					border:#BBB 5px solid; 
					color:#5f5e5e; 
					display:block; 
					float:left; 
					padding:9px 28px; 
					margin:1px 10px 1px 10px;
					alignment-adjust:auto;
					cursor:pointer;
					-webkit-border-radius:6px;
					-moz-border-radius:6px;
					border-radius:6px;
					-khtml-border-radius: 6px;
						background-image:  url(images/guardar.png);
						z-index: -1;
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
	
    
    /*  Empezamos el contenedor */
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
	#demo-nav li.actual{background:#fff; background-image: none;}
	#demo-nav li.actual a{color:#2c2c2c; background-image: none;}
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
		/*text-decoration:none;*/
		}
		
	#demo-container{
	position: relative;
	top: 0%;
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
			margin:0 auto;
			background:#eee;
			border-radius:5px; 
			padding:20px 10%;
			box-shadow:0 0 3px rgba(0,0,0,.4);
		}
		.pestana{/*El contenido propiamente dicho*/
			background:#eee;
		}
		.pestana li {
		display: block;
		margin: 10px 0;
		}
		iframe{
			border: 0px;
			   
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
</style>
</head>
<body>

<!--- Aquí empezamos el conteiner --->
<div id="demo-container"><!-- Div demo-container -->
<input type="hidden" id="selected_div" name="selected_div" value="">
	<div id="tabs1" class="tab-wrapper">
        <div class="tab">
			<ul>
				<li class="activo"><a href="#pestana1">Medicamentos</a></li>
				<li><a href="#pestana2">Motivos de consulta</a></li>
				<li><a href="#pestana3">Diagn&oacute;stico</a></li>
                <li><a href="#pestana4">Administrador</a></li>
			</ul>
					<div style="clear:both;"></div>
		</div>
			<div class="tabs-content">
				<div class='pestana' id='pestana1' name="pestana1"><!-- Div abre contenedor -->

<!-- Aquí terminamos el conteiner -->
                    <form id="registro" name="registro" action="">
                    <table>
                    	<tr>
							<td colspan="2"><label>Agregar m&eacute;dicamento</label></td>
                        </tr>
                        <tr>
                        	<td><label>Nombre:&nbsp;&nbsp;&nbsp;</label></td>
                            <td><input type="text" id="nuevo_nombre_medicamento" name="nuevo_nombre_medicamento"></td>
						</tr>
                        <tr>
                        	<td><label>Descripci&oacute;n</label></td>
                        	<td><input type="text" name="nuevo_descripcion_medicamento" id="nuevo_descripcion_medicamento"></td>
                        </tr>
                        <tr>
                        	<td colspan="2">
                            <a class='enlace insertar' href='#'>&nbsp;Guardar&nbsp;</a>
                            </td>
                        </tr>
                     </table>
                     </form>                       
   				<div id="result" name="result"></div>
                        <br>
                        	<div class="mensaje"></div>
								<table class="editinplace" id="medicamentos" name="medicamentos">
									<tr>
										<th>ID.</th>
										<th>Medicamento</th>
										<th>Descripción</th>
									</tr>
								</table>
				</div>
                <div class="pestana" style="display: none;"id="pestana2" name="pestana2">
                 
                     	<iframe src="motivos_consulta.php" width="400" height="500"></iframe>
                
                </div>
                <div class="pestana" style="display: none;"id="pestana3" name="pestana3">

                  		<iframe src="diagnosticos_consulta.php" width="400" height="500"></iframe>
                  
                </div> 
                 <div class="pestana" style="display: none;"id="pestana4" name="pestana4">

                 	<form id="registro" name="registro" action="">
                    <table>
                    	<tr>
							<td colspan="2"><label>Agregar Administrador</label></td>
                        </tr>
                        <tr>
                        	<td><label>Nombre:&nbsp;&nbsp;&nbsp;</label></td>
                            <td>
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
                        	<td colspan="2">
                            <a class='enlace admin' href='#'>&nbsp;Guardar&nbsp;</a>
                            </td>
                        </tr>
                     </table>
                     </form> 
                     
                     <div id="result_admin" name="result_admin"></div>
                        <br>
                        	<div class="mensaje"></div>
								<table class="eliminar" id="admin" name="admin">
									<tr>
										<th>ID.</th>
                                        <th>ID empleado</th>
										<th>Nombre</th>
                                        <th>&nbsp;</th>
									</tr>
								</table>
							</div>
                 </div>
      
			</div><!-- Contenedor de las pestañas -->
		</div><!-- contenedor del wraper -->
</div><!-- Cierra mis contenedores -->
  
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script>

	$(document).ready(function() 
	{
		/* OBTENEMOS TABLA */
		$.ajax({
			type: "GET",
			url: "editinplace.php?tabla=1"
		})
		.done(function(json) {
			json = $.parseJSON(json)
			for(var i=0;i<json.length;i++)
			{
				$('.editinplace').append("<tr>"
											+"<td class='id'>"+json[i].id+"</td>"
											+"<td class='editable' data-campo='nombre'><span>"+json[i].nombre+"</span></td>"
											+"<td class='editable' data-campo='descripcion'><span>"+json[i].descripcion+"</span></td>"
										+"</tr>");
			}
		});
		
		$.ajax({
			type: "GET",
			url: "administrador.php?tabla=1"
		})
		.done(function(json) {
			json = $.parseJSON(json)
			for(var i=0;i<json.length;i++)
			{
				$('.eliminar').append("<tr>"
											+"<td class='id'>"+json[i].id+"</td>"
											+"<td data-campo='id_personal'><span>"+json[i].id_personal+"</span></td>"
											+"<td data-campo='nombre'><span>"+json[i].nombre+"</span></td>"
											+"<td class='delete' data-campo='id' align='center'><span><img src='delete.png'></img></span></td>"
										+"</tr>");
			}
		});
		
		
		
		/*  TABLA DE MOTIVOS DE CONSULTA */
		$.ajax({
			type: "GET",
			url: "motivos_de_consulta.php?tabla=1"
		})
		.done(function(json) {
			json = $.parseJSON(json)
			for(var i=0;i<json.length;i++)
			{
				$('.editinplace_motivos').append("<tr>"
											+"<td class='id'>"+json[i].id+"</td>"
											+"<td class='editable' data-campo='nombre'><span>"+json[i].nombre+"</span></td>"
											+"<td class='editable' data-campo='descripcion'><span>"+json[i].descripcion+"</span></td>"
										+"</tr>");
			}
		});
		
		
		$.ajax({
			type: "POST",
			url: "motivos_de_consulta.php?empleado=1"
		})
		.done(function(json) {
			json = $.parseJSON(json)
			for(var i=0;i<json.length;i++)
			{
				$('.profesor').append("<option value='"+json[i].id+"'>"+json[i].nombre+"<option>");
			}
		});
		
		
		
		
		
		$(document).on("click","td.delete span",function(e)
		{
		alert("seleccionado");
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
			if (consulta == 1){
				consulta = 	"guardar";				
			}
			else if (consulta == 2){
				consulta = "guardar_";
			}
			else{
				consulta = "guardar_";
			}
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
					url: "editinplace.php",
					data: { campo: campo, valor: nuevovalor, id:id, consulta: consulta }
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
		$(document).on("click",".guardar_consulta",function(e)
		{
			$(".mensaje").html("<img src='loading.gif'>");
			e.preventDefault();
			nuevovalor=$(this).closest("td").find("input").val();
			if(nuevovalor.trim()!="")
			{
				$.ajax({
					type: "POST",
					url: "motivos_de_consulta.php",
					data: { campo: campo, valor: nuevovalor, id:id, consulta: consulta }
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
		  var $nombre = $("#nuevo_nombre_medicamento").val();
		  var $descripcion = $("#nuevo_descripcion_medicamento").val();
		  var $nombre_largo = $("#nuevo_nombre_medicamento").val().length;
		  var $descripcion_largo = $("#nuevo_descripcion_medicamento").val().length;
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
					url: "insertinplace.php",
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
		$(document).on("click",".insertarmotivo",function(e)
		{
		  var $nombre = $("#nuevo_motivo_consulta").val();
		  var $descripcion = $("#nuevo_descripcion_motivo").val();
		  var $nombre_largo = $("#nuevo_motivo_consulta").val().length;
		  var $descripcion_largo = $("#nuevo_descripcion_motivo").val().length;
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
					url: "insert_motivo.php",
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
	
    window.onload = buscarPersonal;
function buscarPersonal(){

	$("#profesor").html("<option value=''>- primero seleccione un Grado! -</option>");
	
		$profesor = "profesor";

					$.ajax({
					dataType: "json",
					data: {"personal": $profesor},
					url:   '../buscar.php',
					type:  'post',
					beforeSend: function(){
						//Lo que se hace antes de enviar el formulario
					},
						success: function(respuesta){
						//lo que se si el destino devuelve algo
						$("#profesor").html(respuesta.html);
						
				  },
					error:	function(xhr,err){ 
					alert("profesor readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n \n responseText: "+xhr.responseText);
			}
		});
}
	
	
	
	
	
</script>