<?php
session_start();
include('connection.php');
mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
mysql_select_db($DB)or die("No se pudo seleccionar DB");
///Función para saber sí es administrador del documento

if($_SESSION['letter']=='F'){
	$acepta=mysql_result(mysql_query("select acepta_tds from familias where familia=$_SESSION[clave]"),0);
	if($acepta!='S')
		header('Location: tds.php');
}
include('config.php');
include('functions.php');

// dependiendo de la hora asignamos el saludo
$localtime_assoc = localtime(time(), false);
$hora = intval(date('H'));
if($hora<12)	 $saludo = "Buenos D&iacute;as<br>";
elseif($hora<19) $saludo = "Buenas Tardes<br>";
else			 $saludo = "Buenas Noches<br>";

$total=0;
$externos="";
	$mensajes_becas_vencidas="";
    $administra_test="";

//dependiendo del usuario creamos las opciones
$clave = $_SESSION["clave"];
//Medico

$query_version_medico = "SHOW TABLES LIKE 'expediente_medico'";
	$result_existe = mysql_query($query_version_medico, $link);
  	$existente =mysql_num_rows($result_existe);
 
 if($existente != 0){  
   $query_medico_admin="SELECT * FROM medico_administrador WHERE id_personal = '".$clave."'";
		$result = mysql_query($query_medico_admin, $link);
		$admin_medico = mysql_num_rows($result);
}

//  Cobranza
$query_version_cobranza = "SHOW TABLES LIKE 'estado_cuenta_formatos_cartas'";
	$result_existe_cobranza = mysql_query($query_version_cobranza, $link);
  	$existente_cobranza =mysql_num_rows($result_existe_cobranza);
 
 if($existente_cobranza != 0){  
   $query_cobranza="SELECT * FROM personal WHERE status = 'A' AND costo = '1' AND empleado = '".$clave."'";
		$result_cobranza = mysql_query($query_cobranza, $link);
		$cobranza = mysql_num_rows($result_cobranza);
}


switch ($_SESSION["letter"]): 
		
						
case 'A':
	$sqlQ = "select nombre as c_nombre from alumnos where alumno = $clave";
	$options = array("Consulta de Calificaciones","Test Preceptor&iacute;a");
	$total=1;
	$links = array("boletas.php","Personal/tablas.php?tabla=test");
    break;

case 'P':
	$sqlQ = "select nombre as c_nombre from personal where empleado = $clave";
	$preceptor= $_SESSION["preceptor"];
	$mensajes_becas_vencidas=$_SESSION["mbv"];
    $administra_test=$_SESSION["at"];
	
	if($preceptor=="S")
		{

//Con Calificaciones			
//		$options = array(
//"Captura de Calificaciones",
//"Tira de Calificaciones",
//"Listado de Alumno",
//"Concentrado por Grupo",
//"Actualizar Datos Personales",
//"Consulta de Calificaciones",
//"Consultar Datos Padres",
//"Expediente (Preceptor)",
//"Reporte (Preceptoria)",
//"Reporte Ejecutivo");
//		$total=9;
//		$links = array(
//"Personal/Calificaciones.php",
//"Personal/Calificaciones.php?reporte=tira",
//"Personal/Calificaciones.php?reporte=lista",
//"Personal/Calificaciones.php?reporte=concentrado",
//"datos.php",
//"Personal/buscaAlumno.php?muestra=boleta",
//"Personal/buscaAlumno.php?muestra=familia",
//"Personal/expediente.php?preceptor=S",
//"Personal/reportes.php?preceptor=S",
//"Personal/reporte_ejecutivo.php");

//Sin Calificaciones
$options = array("Expediente (Preceptor)",
"Reporte (Preceptoria)",
"Reporte Ejecutivo",
"Actualizar Datos Personales",
"Listado de Alumno",
"Concentrado por Grupo",
"Consulta de Calificaciones",
"Consultar Datos Padres");
		$total=7;
		
		$links = array("Personal/expediente.php?preceptor=S",
		"Personal/reportes.php?preceptor=S",
		"Personal/reporte_ejecutivo.php",
		"datos.php",
		"Personal/Calificaciones.php?reporte=lista",
		"Personal/Calificaciones.php?reporte=concentrado",
		"Personal/buscaAlumno.php?muestra=boleta",
		"Personal/buscaAlumno.php?muestra=familia",
		);	
			
			if($admin_medico==1){
				array_push($options, "Expediente M&eacute;dico");
				array_push($links, "Personal/medico/expediente_medico.php");
				$total=$total+1;
			}
			
			if($cobranza==1){
				array_push($options, "Cartas Cobranza");
				array_push($links, "Cobranza/cobranza.php");
				$total=$total+1;
			}
		}
	else
	{

//Con Calificaciones
//		$options = array("Captura de Calificaciones",
//"Tira de Calificaciones",
//"Listado de Alumno",
//"Concentrado por Grupo",
//"Actualizar Datos Personales",
//"Consulta de Calificaciones",
//"Consultar Datos Padres");
//	$total=6;
//	$links = array("Personal/Calificaciones.php",
//"Personal/Calificaciones.php?reporte=tira",
//"Personal/Calificaciones.php?reporte=lista",
//"Personal/Calificaciones.php?reporte=concentrado",
//"datos.php",
//"Personal/buscaAlumno.php?muestra=boleta",
//"Personal/buscaAlumno.php?muestra=familia")
	
//Sin Calificaciones
	$options = array("Actualizar Datos Personales", "Listado de Alumno","Concentrado por Grupo","Consultar Datos Padres");
	$total=3;
	$links = array("datos.php", "Personal/Calificaciones.php?reporte=lista","Personal/Calificaciones.php?reporte=concentrado","Personal/buscaAlumno.php?muestra=familia")
	;
			if($admin_medico==1){
				array_push($options, "Expediente M&eacute;dico");
				array_push($links, "Personal/medico/expediente_medico.php");
				$total=$total+1;
			}
			if($cobranza==1){
				array_push($options, "Cartas Cobranza");
				array_push($links, "Cobranza/cobranza.php");
				$total=$total+1;
			}
	}	
	break;

case 'F':
	$saludo="$saludo Familia<br>";
	$sqlQ = "select nombre_familia as c_nombre from familias where familia = $clave";
	
//Con Calificaciones
//	$options = array("Consulta de Calificaciones","Estado de Cuenta","Actualizar Datos Hijos","Actualizar Datos Padres","Descargar Facturas");
//	$total=5;
//	$links = array("boletas.php","Familia/estadoCuenta.php","datos.php?dequien=alumno","datos.php","facturas.php");

//Sin Calificaciones
	$options = array("Estado de Cuenta","Actualizar Datos Hijos","Actualizar Datos Padres","Descargar Facturas");
	$total=3;
	$links = array("Familia/estadoCuenta.php","datos.php?dequien=alumno","datos.php","facturas.php");
	
	
	$externos="
	<th id='t6' class='menu'><a href='https://sites.google.com/a/colmenares.org.mx/solicitud-de-becas/' target='_blank'>Becas</a></th>
			</tr><tr><th width='7'></th></tr>
			<tr><th width='7' bgcolor='#FFFFFF'></th></tr>
			<th id='t7' class='menu'><a href='https://sites.google.com/a/colmenares.org.mx/manual-administrativo/' target='_blank'>Manual Administrativo</a></th></tr>
			<tr><th width='7' bgcolor='#FFFFFF'></th></tr>";
	
	break;


case 'E':
	$sqlQ = "select nombre as c_nombre from alumnos where alumno = $clave";
	$options = array("Actualizar Datos Personales","Actualizar Datos Hijos");
	$total=2;
	$links = array("datos.php","datos.php?dequien=otrohijo");
	break;
default:
endswitch;

switch ($version_sie_local):
case 'colmenares': 
    if($mensajes_becas_vencidas=="S")
	{ $total++; array_push($options, "Becas Vencidas"); array_push($links, "mensajes_beca_vencida.php"); }
	
	if($administra_test=="S")
	{ $total++; 
	array_push($options,"Expediente (administrador)","Metas (preceptorias)"); 
	array_push($links,"Personal/expediente.php?administra_test=S", "Personal/metas.php?preceptor=S"); 
	}
	break;

case 'cosmos':
    if($administra_test=="S")
	{ $total++; array_push($options,"Procesos Inscripci&oacute;n"); 
	  array_push($links,"Personal/inscripciones.php?orderby=orden");
	  $total++; array_push($options,"Estatus Inscripci&oacute;n"); 
	  array_push($links,"Personal/estatus_inscripciones.php"); }
	if($_SESSION["letter"]=='F')
	{ $total++; array_unshift($options,"Inscripciones"); 
	array_unshift($links,"Familia/inscripciones.php?principal=S");
	  $total++; array_unshift($options,"Principal"); 
	array_unshift($links,"im/blanco.jpg");}
	break;

default:
endswitch;

$saludo.=mysql_result(mysql_query($sqlQ,$link),0,0);
echo utf8_encode("
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<title>.:Colmenares:.</title>

</head>
<SCRIPT language='JavaScript' type='text/javascript'>
<!--
  function abrecalendario(field) {
    window.open('calendar.php?campo='+field, 'calendar', 'width=400,height=300,status=yes');
  }
  function twoinone(nr){ 
   if(nr==1) nr='cambiaPassw.php';
   if(nr==2) nr='circulares.php';
   document.getElementById('main').src='a.php?url='+nr; }
-->
</SCRIPT>


<body bgcolor='$fondo' text='$texto' link='$texto_n' vlink='$texto_s' alink='$texto_s'> 
  <a name='inicio'> </a>
  
<!--[if IE]>

	<style>
		
#iframe{
	position: relative;
 	width: 900px;
 	height: 430px;
 	left: 0px;
 	top: 0px;
}
		
	</style>
        
<![endif]-->

<table width='1100' align='center' bgcolor='#FFFFFF' class='contenedor'>
  <tr>
    <th width='151' class='encabezado'>$saludo<br></th>
	<th class='encabezado'><font size='+3'><img class='escudo' src='im/escudo.png'><p class='sistema'>Sistema de Informaci&oacute;n Escolar $nombre_colegio</p></font></th>
  </tr>
  <tr>
  	<td valign='top'>
	
<table width='151' bgcolor='$fondo_n'>
  <tr>");
  
$total++;
if($ps==1) $total++;
$i=0;?>

<?php
foreach ($options as $option) 
{ ?> 
<tr>
<th id="t<?=$i;?>" onClick="pestana(<?=$i;?>,0,<?=$total;?>,0); twoinone('<?=$links[$i];?>');"  class='menu'>
<a href="#inicio"><?=$option;?></a></th></tr><tr><th width='7' bgcolor='#FFFFFF'></th>
</tr> 

<?php $i++;}
if($ps==1)
{
echo"<tr>
<th id='t$i' onClick='pestana($i,0,$total,0); twoinone(1);' class='menu'><a href='#inicio'>Cambiar Clave de Acceso</a></th></tr>
	<tr><th width='7' bgcolor='#FFFFFF'></th></tr>"; $i++;}
	if($preceptor=="F"){
		$i=$i++;
		echo "<th id='t$i' class='menu'><a href='https://sites.google.com/a/colmenares.org.mx/solicitud-de-becas/' target='_blank'>Becas</a></th>
			</tr><tr><th width='7'></th></tr>"; 
		$i=$i++;
		echo"<th id='t$i' class='menu' ><a href='https://sites.google.com/a/colmenares.org.mx/manual-administrativo/' target='_blank'>Manual Administrativo</a></th></tr>";
	}

if($preceptor=="S")
	{ 
	$i=$i++;
	//echo "<th id='t$i' class='menu'><a href='ayuda_expediente.pdf' target='_blank'>Ayuda Expediente Digital</a></th>
	//		</tr><tr><th width='7' bgcolor='#FFFFFF'></th></tr>";
	//$i++;
	}
	 
echo $externos;

echo"<th id='t$i' class='menu'><a href='login.php'>Salir</a></th></tr><tr><th width='7'></th></tr>
	<tr>
		<th width='7' bgcolor='#FFFFFF'></th>
	</tr>
	<tr>
		<th class='menu'>
			<p style='color:#000099; font-size: 9px;'> <b>&#174;Grupo Colmenares. </b><br />
				Todos los derechos Reservados</p>
		</th>
	<tr>
</table></td>
  	<th valign='top'>
	 <iframe width='100%' height='530' id='main' name='main' ></iframe></th>
  </tr>
</table> 
<script language='javascript'>twoinone('".$links[0]."'); pestana(0,0,$total,0); </script>
</body>
</html>";
?>