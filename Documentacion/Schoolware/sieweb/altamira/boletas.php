<?php
session_start();
include('config.php');
$alumnoN="";
if(!empty($_POST['alumnoN'])) $alumnoN=$_POST['alumnoN'];
if(!empty($_GET['alumnoN'])) $alumnoN=$_GET['alumnoN'];

$clave=$_SESSION['clave'];
$tipo=$_SESSION['tipo'];

$slct="";
if($tipo=="alumno") $alumnoN=$clave;
if($tipo=="familia") 
{ $alumnos=mysql_query("select * from alumnos, parametros where familia='$clave' and activo = 'A' and alumnos.plantel = parametros.sede ",$link)or die(mysql_error());
  $slct="<form action='boletas.php' method='post' name='frm' id='frm'><h3 align='center'> Hij@ : 
    <select onChange='frm.submit();' name='alumnoN' id='alumnoN'>";
  while($row=mysql_fetch_array($alumnos))
  { if($alumnoN=="") $alumnoN=$row["alumno"];
    $slctd="";
    if($row["alumno"]==$alumnoN)$slctd="selected";
    $slct.="<option value=".$row["alumno"]." $slctd>".$row["nombre"]."</option>";
  }
  $slct.="</select></h3></form>";
}

 $result=mysql_query("select * from alumnos,parametros where alumno='$alumnoN' and activo = 'A' and nuevo_ingreso <> 'P'",$link)or die(mysql_error());

if (mysql_affected_rows($link)<=0) die("No se encontr&oacute; el alumno");
$row = mysql_fetch_array($result);
$seccion = $row["seccion"];
$grado = $row["grado"];
$sexo = $row["sexo"];
$nombre=$row["nombre"];
$apellidop= $row["apellido_paterno"];
$apellidom= $row["apellido_materno"];
$grado="".$row["grado"]."-".$row["grupo"]."";
if ($sexo=='F') $alumn="Alumna";
else $alumn="Alumno";

$nombre_seccion=mysql_result(mysql_query("select nombre from secciones where seccion ='$seccion' and ciclo = '$periodo_actual'",$link),0,0);

$sql_titulos=mysql_query("select * from boletas_web_2 where seccion = '$seccion' and grado = '$grado'",$link)or die(mysql_error());
$row_titulos = mysql_fetch_array($sql_titulos);
$numCols=$row_titulos["ultima_columna"];
//
$consulta_periodo = "
SELECT ciclo FROM calificaciones WHERE alumno = '{$alumnoN}' ORDER BY ciclo desc LIMIT 1
";
$result_periodo = mysql_query($consulta_periodo);
$row_per = mysql_fetch_array($result_periodo);
if(isset($row_per["ciclo"])){
    if($periodo < $row_per["ciclo"]){
        $periodo_ = $row_per["ciclo"];
    }else{
        $periodo_ = $periodo;
    }
}

//$periodo_=$row_per["ciclo"];

//echo $consulta_periodo;
//
$columnas_sombreadas=$row_titulos["columnas_sombreadas"];;
$renglones_sombreados=$row_titulos["renglones_sombreados"];
$consigna=$row_titulos["consigna"];
$titulo= array(array($row_titulos["titulo_1"],$row_titulos["posicion_inicial_1"],$row_titulos["posicion_final_1"]),
				array($row_titulos["titulo_2"],$row_titulos["posicion_inicial_2"],$row_titulos["posicion_final_2"]),
				array($row_titulos["titulo_3"],$row_titulos["posicion_inicial_3"],$row_titulos["posicion_final_3"]),
				array($row_titulos["titulo_4"],$row_titulos["posicion_inicial_4"],$row_titulos["posicion_final_4"]),
				array($row_titulos["titulo_5"],$row_titulos["posicion_inicial_5"],$row_titulos["posicion_final_5"]));
$pie1=$row_titulos["pie_pagina_1"];
$pie2=$row_titulos["pie_pagina_2"];
$pie3=$row_titulos["pie_pagina_3"];

$today = getdate();
     $dia=$today["mday"];
     $mes=$today["mon"];
     $ano=$today["year"];
$width=700;
echo"
<html>
 <head><title>Calificaciones</title> 
   <meta http-equiv='Content-Type' content='text/html; charset=utf-' /></head>
 <body bgcolor='#FFFFFF' text='#000000' link='#0000FF' vlink='#800080' alink='#FF0000'>$slct
<table align='CENTER' width='$width'>
<tr>
  <td rowspan='4' width='$escudo_ancho'><img src='$escudo' width='$escudo_ancho' height='90'></td>
  <td colspan='6' height='33' valign='top'><font size='+1'>$nombre_colegio</font></td>
</tr>
<tr>
  <td width='33'></td>
  <th $styleFont width='280'>Boleta De Calificaciones</th>
  <th $styleFont align='right' width='70'>$alumn:</th>
  <td $styleFont colspan='3'>$nombre $apellidop $apellidom</td>
</tr>
<tr>
  <td></td>
  <td $styleFont align='center'>$nombre_seccion</td>
  <th $styleFont align='right'>Grado:</th>
  <td $styleFont>$grado</td>
  <th $styleFont align='right' width='100'>Ciclo:</th>
  <td $styleFont>".mysql_result(mysql_query("SELECT 
  CASE  
    WHEN (SELECT periodo FROM parametros ORDER BY periodo DESC LIMIT 1) >
(
SELECT 
    ciclo 
  FROM calificaciones WHERE alumno = '{$alumno}' ORDER BY ciclo desc LIMIT 1) THEN 
  (
SELECT 
    ciclo 
  FROM calificaciones WHERE alumno = '{$alumno}' ORDER BY ciclo desc LIMIT 1)
  ELSE (SELECT periodo FROM parametros ORDER BY periodo DESC LIMIT 1)
  END
  ciclo
",$link),0,0)."</td>
</tr>
<tr>
  <th $styleFont colspan='3' align='right'>Evaluaci&oacute;n:</th>
  <td $styleFont colspan='3'>$periodo_</td>
</tr>
</table><br>
<table align='CENTER' style='font:normal 7pt Arial' border=1 bordercolor='#BOBOBO' cellspacing=0 cellpading=0 width='$width'>
";/*<tr>
$titRows=0;
 for($pos=0;$pos<5;$pos++)
 { if ($titulo[$pos][1]!="")
   { $colspan=$titulo[$pos][2]-$titulo[$pos][1];
     if ($titulo[$pos][1]>$titRows)
	 { $colspan2=$titulo[$pos][1]-$titRows;
	   echo "<td colspan=$colspan2 border=true bordercolor='black'></td>";
	   $titRows=$titRows + $titulo[$pos][1];
	 }
	 echo "<th colspan=$colspan border=true bordercolor='black'>n".$titulo[$pos][0]."n</th>";
	 $titRows=$titRows + $colspan;
   }   
 }
echo "</tr>"; */
$result2=mysql_query("select * from boletas_web where alumno='$alumnoN' ",$link)or die(mysql_error());
$vacio="";
if (mysql_affected_rows($link)<=0) $vacio="S";
$countRow=0;
$comentario="";
$col1v=true;
$col2v=true;
$col3v=true;
while($califs = mysql_fetch_array($result2))
{ $comentario=$califs["comentario"];
  $countRow=$countRow + 1;
  if(strstr($renglones_sombreados,",$countRow,")!=FALSE) echo"<tr align='left' style='background-color:#C0C0C0;'>";
  else echo "<tr align='left'>";
  for($i=1;$i<=$numCols;$i++)
  { $value=$califs["columna_$i"];
    $alin="align='right'";
	if($value!="")
	{ switch ($i)
	  { case 1: $col1v=false; $col2v=false; $alin="align='left'"; $col3v=false; break;
	    case 2: $col2v=false; $col3v=false; break;
	    case 3: $col3v=false; break;
	  }
	}
	if(substr($value,-2,1)=='@'){
		//echo '<script language="javascript" type="text/javascript">alert("'.substr($value,-2).'");</script>';
		switch(substr($value,-1,1)){
						case 1: $bgcolor="orange";
							break;
						case 2: $bgcolor="cyan";
							break;
						case 3: $bgcolor="lime";
							break;}
		echo"<td id=$countRow$i $alin border=true style='background-color:$bgcolor;'>&nbsp;".substr($value,0,strlen($value)-2)."</td>";
	}
    else{
		if(strstr($columnas_sombreadas,",$i,")!=FALSE)
			echo"<td id=$countRow$i $alin border=true style='background-color: #C0C0C0;'>&nbsp;$value</td>";
		else
			echo "<td id=$countRow$i $alin border=true>&nbsp;$value</td>";
	}
  }
  echo "</tr>";
}
echo"<script language='javascript'></script>";
/* if (($comentario!="") && ($comentario<>0)){ $comentario=mysql_result(mysql_query("select descripcion from comentarios_calif where comentario='$comentario' ",$link),0,0);
$comentario="Comentarios: $comentario<br>"; }
else $comentario=""; */
//if ($consigna!="") $consigna="Trampol&iacute;n: $consigna";
$pie_="";
//Pie Pagina
if ($pie1!="") $pie_="<tr><td colspan='$numCols'>$pie1</td></tr>";
if ($pie2!="") $pie_.="<tr><td colspan='$numCols'>$pie2</td></tr>";
if ($pie3!="") $pie_.="<tr><td colspan='$numCols'>$pie3</td></tr>";
echo "</table><br>";
//echo "<table align='center' border='1' width='$width'><tr><td $styleFont width='%100' colspan=19>$comentario$consigna</td></tr></table>";
echo"
<table $styleFont align='center' width='$width'>$pie_</table><br><br>
<table border='1' cellspacing=0 cellpadding=0 align='center' width='$width'><tr><td>
  <table $styleFont border='0' width='100%' cellspacing=0 cellpadding=0>
   <tr rowspan=2>
    <th align='right'>$alumn:</td><td colspan=15>$nombre $apellidop $apellidom</td>
    <th align='right'>Fecha:</th><td>$dia/$mes/$ano</td>
   </tr>
   <tr rowspan=2>
    <th align='right'>Grado:</th><td colspan=7>$grado</td>
    <th align='right'>Ciclo:</td><td colspan=7>".mysql_result(mysql_query("SELECT 
  CASE  
    WHEN (SELECT periodo FROM parametros ORDER BY periodo DESC LIMIT 1) >
(
SELECT 
    ciclo 
  FROM calificaciones WHERE alumno = '{$alumno}' ORDER BY ciclo desc LIMIT 1) THEN 
  (
SELECT 
    ciclo 
  FROM calificaciones WHERE alumno = '{$alumno}' ORDER BY ciclo desc LIMIT 1)
  ELSE (SELECT periodo FROM parametros ORDER BY periodo DESC LIMIT 1)
  END
  ciclo
 ",$link),0,0)."</td>
    <th colspan=3 style='background-color: #C0C0C0;'>Firma Del Padre o Tutor:</td>
   </tr>
   <tr rowspan=2>
    <th align='right'>Evaluaci&oacute;n:</th><td colspan=15>$periodo_</td>
    <td colspan=3 style='background-color: #C0C0C0;'></td>
   </tr>
  </table>
</td></tr></table>
<div align='center'><input type='button' value='Imprimir' onClick='javascript:window.print()'></div>
</body>
</html>";
?> 
