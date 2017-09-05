<?php 
session_start();
include('../config.php');
mysql_query("update preceptoria_acuerdos set st=3 where st=1 and fec<CURDATE()") or die(mysql_error("No se pudo limpiar la lista de entrevistas."));
?>
<script>
	function Action(f, o){
		with (document.forms[f]){
			alumno.value = o;
			submit();
		}
	}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Encuesta</title>

<script language="JavaScript">
<!--Script courtesy of http://www.web-source.net - Your Guide to Professional Web Site Design and Development
var time = null
function rfrsh(){window.location.reload();}
//-->
</script>

</head>
 <?php
	include "encuesta.inc";
	
	$alumno = $_REQUEST['alumno'];
	$idt=0;
	$idp=0;
	$rstF_ = mysql_query ("Select id_test from test where nombre like 'Encuesta%' ",$link) or die ("Select id_test from test where nombre like 'Encuesta%' ".mysql_error());
	while($rsF_=mysql_fetch_array($rstF_)){$idt=$rsF_['id_test'];}
	
/*	$sql = "Select id_test from test where nombre like 'Encuesta%';";
	$r = mysql_fetch_array(mysql_query($sql));
	$idt = $r[0];*/
	
	
	$rstF_ = mysql_query ("Select id_publicacion from test_publicacion where id_test = $idt and aplica_a  = 'Todos' ",$link) or die ("Select id_publicacion from test_publicacion where id_test = $idt and aplica_a  = 'Todos' ".mysql_error());
	while($rsF_=mysql_fetch_array($rstF_)){$idp=$rsF_['id_publicacion'];}
	
	/*	$sql = "Select id_publicacion from test_publicacion where id_test = $idt and aplica_a  = 'Todos';";
	$r = mysql_fetch_array(mysql_query($sql));
	$idp = $r[0];*/
/*	echo" <script language='javascript'>alert('idtest: $idt idpublicacion $idp');</script>";*/
	
	$t = $_SERVER['PHP_SELF'];
 	echo "<form id=\"fr\" name=\"fr\" action=\"$t\" method=POST>";
	
 	//$today = date("F j, Y, g:i a");
	$fecha_mes=date("F");
	switch($fecha_mes){
		case("January"): $fecha_mes="Enero"; break;
		case("February"): $fecha_mes="Febrero"; break;
		case("March"): $fecha_mes="Marzo"; break;
		case("April"): $fecha_mes="Abril"; break;
		case("May"): $fecha_mes="Mayo"; break;
		case("June"): $fecha_mes="Junio"; break;
		case("July"): $fecha_mes="Julio"; break;
		case("August"): $fecha_mes="Agosto"; break;
		case("September"): $fecha_mes="Septiembre"; break;
		case("October"): $fecha_mes="Octubre"; break;
		case("November"): $fecha_mes="Noviembre"; break;
		case("December"): $fecha_mes="Diciembre"; break;
		default: break;
	}
	$fecha_dia=date("j");
	$fecha_anio=date("Y");
	$fecha_hora=date("g:i a");
 	$today=$fecha_dia.' de '.$fecha_mes.', '.$fecha_anio.'. '.$fecha_hora;

 	if (!$alumno)
		echo "<body onload=\"Action('fr',$_GET[alumno]);timer=setTimeout('rfrsh()',10000)\" bgcolor=LemonChiffon alink=black vlink=blue>";
	else
		echo "<body bgcolor=LemonChiffon alink=black vlink=blue>";
	
	echo "	<img src=\"../im/logo.jpg\" style=\"width:100%;\">
			
			<table valign=top border=1 cellpadding=0 cellspacing=0 width=100% style=\"font-family: Arial; font-size: 12pt;\">
			<tr>
				<td valign=top width=2% rowspan=2>
					$lista
				</td>
				<td>Encuesta de salida $today</td>
					<input type=hidden name=alumno value=$alumno>
				</td>					
			</tr>
	</form>
			<tr>
  				<th valign=top align=left colspan=100%>";

	if ($alumno > 0 and $idt!=0 and $idp!=0)
		echo " <iframe src='formularios.php?id=$idt&id_publicacion=$idp&tabla=test_respuestas&agr_modif_borr=a&alumno=$alumno' id=ifrm1 name=ifrm1 width=910 height=427></iframe>";
	
	echo "	</th>
		</tr>
	</table>";	
?>

</body>
</html>
