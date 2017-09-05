<? session_start();
include('../config.php');
include('../functions.php');

$alumnoN = '<script> $("#alumno").val(); </script>';
$alumno ='<script>  $("#alumno option:selected").html(); </script>';

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Expediente Digital</title>
</head>
<style>

.tabs { /* es el rectángulo contenedor */
		margin: 10 auto;
		min-height: 450px;
		position: absolute;
		width: 930px;
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
		background-color: <? echo $drop_list_background; ?>;
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
		background-color: <? echo $drop_list_background; ?>;
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
	
<?php
if(!empty($_REQUEST['administra_test'])){
	$tipo = "A";
	echo "<input type='hidden' name='tipo' id='tipo' value='$tipo' />
			<input type='hidden' name='alumno' id='alumno' value='A' />
			
			";
	
	
}else if(!empty($_REQUEST['preceptor'])){
	$tipo = "P";
	
echo "<input type='hidden' name='tipo' id='tipo' value='$tipo' />";

echo "Alumno:  <select name='alumno' id='alumno' onchange='AjaxCargar(this.value);' >
				<option value=''></option>	";
				
				
$resul_t= mysql_query("SELECT CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) as n,alumno from alumnos where preceptor=".$_SESSION["clave"]." and activo='A' order by apellido_paterno,apellido_materno,nombre",$link) or die(mysql_error());
	$echoPropios = "";
    while($ro_=mysql_fetch_array($resul_t)){
        $echoPropios.="<option value='".$ro_['alumno']."'>".utf8_encode($ro_['n'])."</option>";
    }
	
    
    /*Nuevo c�digo!*/

$nivel_preceptoria = "";
$seccion_preceptoria = "";
$grado_preceptoria = "";
$grupo_preceptoria = "";
$and_ = "";
 
$result_add = mysql_query("SELECT 
                                    nivel_preceptoria, 
                                    seccion_preceptoria,
                                    grado_preceptoria, 
                                    grupo_preceptoria 
                                FROM 
                                    usuarios_encabezados 
                                WHERE 
                                    empleado = '".$_SESSION["clave"]."';",$link) or die (mysql_error());
     
while($roNP_=mysql_fetch_array($result_add)){
          $nivel_preceptoria = $roNP_['nivel_preceptoria'];
          
          $seccion_preceptoria = " AND seccion = '".$roNP_['seccion_preceptoria']."'";
          $grado_preceptoria = " AND grado = '".$roNP_['grado_preceptoria']."'";
          $grupo_preceptoria = " AND grupo = '".$roNP_['grupo_preceptoria']."'";
     
          if($nivel_preceptoria == '11'){
              $and_ = "";
          }elseif($nivel_preceptoria == '12'){
              $and_=$seccion_preceptoria;
          }elseif($nivel_preceptoria == '13'){
              $and_=$seccion_preceptoria.$grado_preceptoria;
          }elseif($nivel_preceptoria == '14'){
              $and_=$seccion_preceptoria.$grado_preceptoria.$grupo_preceptoria;
          }elseif($nivel_preceptoria == '15'){
          } elseif($nivel_preceptoria == '16') {
              $echoPropios='';
          }
         
      }   
      if($nivel_preceptoria < 15){
           $resul_t= mysql_query("SELECT 
                                        CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) as n,
                                        alumno 
                                    FROM 
                                        alumnos 
                                    WHERE 
                                        activo='A' 
                                        ".$and_."
                                    ORDER BY 
                                        apellido_paterno,
                                        apellido_materno,
                                        nombre",$link) or die(mysql_error());
 
            while($ro_=mysql_fetch_array($resul_t)){
                $echoNivel.="<option value='".$ro_['alumno']."'>".utf8_encode($ro_['n'])."</option>";
            }	
          }
    $echo.=$echoPropios.$echoNivel."</select> ";
echo $echo;
}

?>
<div id='result' name='result'></div>
</body>
	<script src="../../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
<script>



function Administrador(){
	$tipo = $("#tipo").val();
	if($tipo == 'A'){
		AjaxCargar()
	}
	
}



function AjaxCargar(){
	
	$Alumno = $("#alumno").val();
	$AlumnoN = document.getElementById("alumno").value;
	$tipo = $("#tipo").val();
	
if($Alumno == "" || $AlumnoN == "" ){
	
	$("#result").html("");
	}
	else {
	$.ajax({
		dataType: "json",
		data: {"alumno": $Alumno ,
			   "alumnoN": $AlumnoN,
			   "tipo": $tipo
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

window.onload = Administrador;

</script>
</html>

