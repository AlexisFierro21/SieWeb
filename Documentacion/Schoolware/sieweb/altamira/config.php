<?
include('connection.php');
//Conectar a la base de datos
$link = mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
mySql_select_db($DB)or die("No se pudo seleccionar DB");

//Obtenemos en nombre de la BD
$result=mysql_query("select * from parametros",$link)or die(mysql_error());
$row = mysql_fetch_array($result);
$nombre_colegio=$row["nombre_colegio"];
$sede=$row["sede"];
$periodo_actual=$row["periodo"];
$mq=mysql_query("select ciclo from ciclos where ciclo>$periodo_actual",$link) or die (mysql_error());
$ft=mysql_fetch_array($mq);
$periodo_siguiente=$ft[0];
$mensaje_becas_canceladas=$row["mensaje_becas_canceladas"];
$pago_electronico=$row["pago_electronico"];

//$version_sie_local=$row["version_sie_local"];
$mes_inicial_periodo_actual=$row["mes_inicial_periodo_actual"];

//Enviar correos electronicos
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailUser="entrevistas@e-altamira.edu.mx";
$mailFrom="entrevistas@e-altamira.edu.mx";
$mailBCC="entrevistas@e-altamira.edu.mx";
$mailPass="2a3l0t1a1m4!";
$mailSender="Entrevistas Colegio Altamira - Moctezuma A.C.";

//LOGOS
$escudo="im/logo_.jpg";
$escudo_ancho="90";
$imagen_header="im/banner.jpg";

//tipo de letra general
$fuente="Arial, Helvetica, sans-serif"; 

//fondo de la p�gina
$fondo="#e5ecf3"; 

//Color del texto general
$texto="#000000"; 

//Color de fondo de opciones
$color_main="#FFFFFF"; 

//al ingresar par�metros de calificaciones
$color_parametros="#FFFFFF"; 

//tama�o de texto de las boletas
$styleFont=" style='font-size:8pt' ";

//colores para pestanas seleccionadas
$fondo_s="#FFFFFF";
$texto_s="#00458A";

//colores para pestanas  no seleccionadas
$fondo_n="#99b6d2";
$texto_n="#00458A";

//color del encabezado del baner y color de texto del baner
$baner = "#004990";
$texto_b = "#FFFFFF";

$drop_list_border ="#dadada";
$drop_list_background = "#dfe3ee";

$tr_par ="#dfe3ee";
$tr_non ="#ffffff";

$reporte_tabla = "#e5ecf3";
$seccion_color ="#4c7fb1";

///Días para fecha máxima de 
$tiempo_max_preceptorias = "30";

//Ruta nombre Colegio 
$ruta_colegio = "altamira";

if(!isset($_SESSION[periodo_seleccionado]) || $_SESSION[periodo_seleccionado]=='' || $_SESSION[periodo_seleccionado]==null)
	$_SESSION[periodo_seleccionado]=$periodo_actual;

function noCache() {
  header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
}

echo"
<style type='text/css'>
html { overflow: auto; }  
body{
	font-family:$fuente
}

a{
	 text-decoration:none; 
}

body{
	font-family: $fuente;
} 

table, th, td {
   border-collapse: collapse;
   border-spacing:  1px;
   border-color: #dfe3ee #dfe3ee #dfe3ee #dfe3ee;
}

.encabezado{
	background-color: $baner;
	-moz-box-shadow: 2 4px 4px #999;
    -webkit-box-shadow: 2 4px 4px #999;
    box-shadow: 2 4px 4px #999;
	color: $texto_b;
	border-radius: 4px; 
}

.contenedor{
		box-shadow: 2 4px 2px -2px #999;
		border-top-left-radius: 4px;
   		border-top-right-radius: 4px;
		border-radius: 4px; 
}

/*  Drop List  */
select {
  border: 1px solid $drop_list_border;
  overflow: hidden;
  background-color: $drop_list_background;
}

  select:before {
    content: '';
    position: absolute;
    right: 5px;
    top: 7px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 7px 5px 0 5px;
    border-color: #dfe3ee #dfe3ee #dfe3ee #dfe3ee;
    z-index: 5;
    pointer-events: none;
	border-radius: 4px;
	background-color: #8b9dc3;
  }
 
  select {
  	left: 6px;
    padding: 0px 0px;
    border: 1px solid #dadada;
    border-radius: 4px;
    box-shadow: none;
    background-color: #fff;
    background-image: none;
    appearance: none;
  }
  
select:focus{
	outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;
}  
  select option{
  	box-shadow: 1px 1px 1px 1px #3b5998;
  }

select option:nth-child(even) { background: #dfe3ee }
select option:nth-child(odd) { background: #fff}
 
input{
	border: 1px solid $fondo_n;
    border-radius: 4px;
} 
 
input:focus{
	outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;
}

textarea{
	border: 2px solid #dadada;
    border-radius: 4px;
} 
 
textarea:focus{
	outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;
}
 
input[type='submit'], input[type='button'] {
	-moz-box-shadow: 0px 10px 14px -7px #276873;
	-webkit-box-shadow: 0px 10px 14px -7px #276873;
	box-shadow: 0px 10px 14px -7px #276873;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #8b9dc3), color-stop(1, #3b5898));
	background:-moz-linear-gradient(top, #8b9dc3 5%, #3b5898 100%);
	background:-webkit-linear-gradient(top, #8b9dc3 5%, #3b5898 100%);
	background:-o-linear-gradient(top, #8b9dc3 5%, #3b5898 100%);
	background:-ms-linear-gradient(top, #8b9dc3 5%, #3b5898 100%);
	background:linear-gradient(to bottom, #8b9dc3 5%, #3b5898 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8b9dc3', endColorstr='#3b5898',GradientType=0);
	background-color:#8b9dc3;
	-moz-border-radius:8px;
	-webkit-border-radius:8px;
	border-radius:8px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:12px;
	font-weight:bold;
	padding:3px 15px;
	text-decoration:none;
	text-shadow:0px 1px 0px #3d768a;
}

input[type='submit']:hover, input[type='button']:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #3b5898), color-stop(1, #8b9dc3));
	background:-moz-linear-gradient(top, #3b5898 5%, #8b9dc3 100%);
	background:-webkit-linear-gradient(top, #3b5898 5%, #8b9dc3 100%);
	background:-o-linear-gradient(top, #3b5898 5%, #8b9dc3 100%);
	background:-ms-linear-gradient(top, #3b5898 5%, #8b9dc3 100%);
	background:linear-gradient(to bottom, #3b5898 5%, #8b9dc3 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3b5898', endColorstr='#8b9dc3',GradientType=0);
	background-color:#3b5898;
}

input[type='submit']:active, input[type='button']:active {
	position:relative;
	top:1px;
}

img.escudo { 
  float: left; 
  width: 80px; 
}

p.sistema{
	text-align: center; 
	height: 80px;
 	width: 520px; 
	margin: auto;
	margin-top: 15px;
}

.menu{
	background-color: $fondo_n;
}
.menu:hover{
	outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;
}  


iframe{
	border: 0px;
}


ui-datepicker-next ui-corner-all:hover{
	cursor:hand;
}

.ui-datepicker-next ui-corner-all:hover{
	cursor:hand;
}

#ui-datepicker-next ui-corner-all:hover{
	cursor:hand;
}

ui-icon ui-icon-circle-triangle-w:hover{
	cursor:hand;
}
.ui-icon ui-icon-circle-triangle-w:hover{
	cursor:hand;
}
#ui-icon ui-icon-circle-triangle-w:hover{
	cursor:hand;
}


.escudo_reporte {
       width: 50px; height: 50px;
      

       /* OKAY */
       background-size: 25px 25px;
       //background-size: 50% 50%;
}


</style>

<script language='JavaScript' type='text/JavaScript'><!--
function pestana(activo,ini,fin,esconde)
	{
		for(x=ini;x<=fin;x++){
			if (x==activo)
			{
			 document.getElementById('t'+x).style.background='$fondo_s';
			 document.getElementById('t'+x).style.color='$texto_s';
			 if(esconde==1) document.getElementById('d'+x).style.display = 'inline';
			}	
			else
			{
			 document.getElementById('t'+x).style.background='$fondo_n';
			 document.getElementById('t'+x).style.color='$texto_n';
			 if(esconde==1) document.getElementById('d'+x).style.display = 'none';
			}	
		}	
	}	
function stopRKey(evt) {
var evt = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
if ((evt.keyCode == 13) && (node.type=='text')) {return false;}
}
document.onkeypress = stopRKey;

--></script>"; $hmlgcn=1; $ps=0; 
$version_sie_local="colmenares";
?>