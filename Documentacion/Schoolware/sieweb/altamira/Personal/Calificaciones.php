<? session_start();

include('../config.php');
include('../functions.php');
$ciclo=$_SESSION['ciclo'];
$clave = $_SESSION['clave'];
$sede=$_SESSION['sede'];
$size="size='3'";
mysql_query("SET CHARACTER SET utf8 ");
//query to obtain the level of the user
$user=mysql_query("select * from usuarios_encabezados where empleado=$clave",$link)or die(mysql_error()."select * from usuarios_encabezados where empleado=$clave");
if (mysql_affected_rows($link)<=0 )
{ mysql_close($link);
  die("No existe en usuarios");
}

$rowU = mysql_fetch_array($user);
$nivelUsuario=$rowU["nivel_calificaciones"];
$seccionU=$rowU["seccion"];
$gradoU=$rowU["grado"];
$grupoU=$rowU["grupo"];
$seccion="";
$grado="";
$grupo="";
$periodo="";
$materia="";
$reporte="";
if(!empty($_POST['seccion'])) $seccion=$_POST["seccion_"];
if(!empty($_POST['grado'])) $grado=$_POST["grado_"];
if(!empty($_POST['grupo'])) $grupo=$_POST["grupo_"];
if(!empty($_POST['periodo'])) $periodo=$_POST["periodo_"];
if(!empty($_POST['materia'])) $materia=$_POST["materia_"];
if(!empty($_GET['reporte'])) $reporte=$_GET["reporte"];
if(!empty($_POST['reporte'])) $reporte=$_POST["reporte"];
$desc_reporte="";
switch($reporte)
{ case "concentrado": $desc_reporte="CONCENTRADO POR GRUPO"; break;
  case "lista":       $desc_reporte="LISTADO DE ALUMNOS"; break;
  case "tira":        $desc_reporte="TIRA DE CALIFICACIONES"; break;
}
?> 

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache"/>
<META HTTP-EQUIV="Expires" CONTENT="-1"/>
<title>Calificaciones</title>
</head>

<script language="javascript">
<!--
function submt(nivel)
{ var campo;
  for(x=nivel;x<6;x++)
  { switch(x)
    { case 1: campo="seccion"; break;
	  case 2: campo="grado"; break;
	  case 3: campo="grupo"; break;
	  case 4: campo="periodo"; break;
	  case 5: campo="materia"; break;
	}
	if(nivel==x) document.getElementById(campo+"_").value=document.getElementById(campo).value;
	else if(document.all(campo)!=null) document.getElementById(campo+"_").value="";
  }
  document.getElementById('frmSel').submit();
}
--></script> 

<body>
<form name="frmSel" id="frmSel" action="Calificaciones.php" method="post">
    <input type="hidden" name="reporte" id="reporte" value="<?=$reporte?>">
<table id ="div1" name="div1" bgcolor="<?=$color_parametros;?>" width="100%" style="font-size:8pt;">
<tr><th colspan="100%" style="font-size:12px" align="right">Selecciona los Parametros y haz click en el Boton "Cargar Forma"</th></tr>
<tr valign="bottom"><?

//Secci&oacute;n
    $sql=returnQuery($nivelUsuario,"seccion","","","",$seccionU,$gradoU,$grupoU);
    $result=mysql_query($sql,$link)or die(mysql_error().$sql);
    if (mysql_affected_rows($link)<=0) die("No hay secciones que pueda ver");
	else
	{ echo"<td>Secci&oacute;n<select onChange='submt(1)' style='font-size:8pt' name=seccion id=seccion tabindex=10>";
	  $numField=1;
	  while($row=mysql_fetch_array($result))
	  { switch($row["calificacion_maxima"])
        { case "D": $_SESSION["calMax"]=10;  break;
          case "C": $_SESSION["calMax"]=100; break;
		  case "G": $_SESSION["calMax"]="G"; break;
	    }
	    $_SESSION["calMin"] =$row["calif_minima_boleta"];
	    $selected="";
	    if(($numField==1)&&($seccion=="")) $seccion=$row["seccion"];
	    if($seccion==$row["seccion"]){ $selected="selected"; $desc_seccion=$row["nombre"]; }
	    echo"<option $selected value='".$row["seccion"]."'>".$row["nombre"]."</option>";
	    $numField++;
	  } echo"</select><input type='hidden' name='seccion_' id='seccion_' value='$seccion'></td>";
	}

//Grado
	$sql=returnQuery($nivelUsuario,"grado",$seccion,"","",$seccionU,$gradoU,$grupoU);
    $result=mysql_query($sql,$link)or die(mysql_error().$sql);
    if (mysql_affected_rows($link)<=0) die("No hay grados que pueda ver");
	else
	{ echo"<td>Grado<select onChange='submt(2)' style='font-size:8pt;' name='grado' id='grado' tabindex='20'>";
	  $numField=1;
	  while($row=mysql_fetch_array($result))
	  { $selected="";
	    if(($numField==1)&&($grado=="")) $grado=$row["grado"];
	    if($grado==$row["grado"]) $selected="selected"; 
	    echo"<option $selected value='".$row["grado"]."'>".$row["grado"]."</option>";
	    $numField++;
	  } echo"</select><input type='hidden' name='grado_' id='grado_' value='$grado'></td>";
	}

//Grupo
	$sql=returnQuery($nivelUsuario,"grupo",$seccion,$grado,"",$seccionU,$gradoU,$grupoU);
    $result=mysql_query($sql,$link)or die(mysql_error().$sql);
    if (mysql_affected_rows($link)<=0) die("No hay grupos que pueda ver");
	else
	{ echo"<td>Grupo<select onChange='submt(3)' style='font-size:8pt;' name='grupo' id='grupo' tabindex='30'>";
	  $numField=1;
	  while($row=mysql_fetch_array($result))
	  { $selected="";
	    if(($numField==1)&&($grupo=="")) $grupo=$row["grupo"];
	    if($grupo==$row["grupo"]) $selected="selected";
	    echo"<option $selected value='".$row["grupo"]."'>".$row["grupo"]."</option>";
	    $numField++;
	  } echo"</select><input type='hidden' name='grupo_' id='grupo_' value='$grupo'></td>";
	}

//Periodo
	$sql=returnQuery($nivelUsuario,"periodo",$seccion,"","",$seccionU,$gradoU,$grupoU);
    $result=mysql_query($sql,$link)or die(mysql_error().$sql);
    if (mysql_affected_rows($link)<=0) die("No hay periodos activos");
	else
	{ echo"<td>Periodo<select onChange='submt(4)' style='font-size:8pt;' name='periodo' id='periodo' tabindex='40'>";
	  $numperiodo=1;
	  while($row=mysql_fetch_array($result))
	  { $selected="";
	    
	    if($periodo==$row["periodo"]){ $selected="selected"; $desc_periodo=$row["descripcion"];}
	    $m_=true;
	    if($reporte=="") if($row["activo"]!="S") $m_=false;
          if ($m_){ if(($numperiodo==1)&&($periodo=="")) $periodo=$row["periodo"]; echo"<option $selected value='".$row["periodo"]."'>".$row["descripcion"]."</option>"; $numperiodo++; }
		  
	  }  
	  echo"</select><input type='hidden' name='periodo_' id='periodo_' value='$periodo'></td>";
	}?>
<tr/>
<tr><?

//Materia
    $sql=returnQuery($nivelUsuario,"materia",$seccion,$grado,$grupo,$seccionU,$gradoU,$grupoU);
    $result=mysql_query($sql,$link)or die(mysql_error().$sql);
    if (mysql_affected_rows($link)<=0) die("$grupo No hay materias que pueda ver");
	else
	{ $tdmat="<td>Materia<select onChange='submt(5)' style='font-size:8pt;' name=materia id=materia tabindex=50>";
	  $numField=1;
	  $materia2="";
	  while($row=mysql_fetch_array($result))
	  { $selected="";
	    if(($numField==1)&&($materia=="")) $materia=$row["materia"];
	    if($materia==$row["materia"])
		{ $selected="selected"; 
	  	  $materia=$row["materia"];
		  $desc_materia=$row["nombre"];
    	  $tareas=$row["tareas"];
    	  $conducta=$row["conducta"];
    	  $asistencia=$row["asistencia"];
    	  $materia2= $row["materia"];
    	  $apreciativas=$row["evalua_apreciativa"];
    	  $isSel=true;
		}
	    $tdmat.="<option $selected value='".$row['materia']."'>".$row["nombre"]."</option>";
	    $numField++;
      } $tdmat.="</select><input type='hidden' name='materia_' id='materia_' value='$materia'></td>";
	}
	if($reporte!="concentrado") echo $tdmat;
	$esOptativa=0;
	$sql="select optativa from materias_ciclos where materia='$materia2' and ciclo=$ciclo and seccion='$seccion'";
	$result=mysql_query($sql,$link)or die(mysql_error().$sql);
	$rowoptativa=mysql_fetch_array($result);
	if(($rowoptativa["optativa"]!=0) and ($rowoptativa["optativa"]!=""))
	{ $esOptativa=1;
	  $alumopt=mysql_query("select * from alumnos_optativas where seccion='$seccion' and grado='$grado' and ciclo=$ciclo and materia='$materia2'",$link)or die(mysql_error()."select * from alumnos_optativas where seccion='$seccion' and grado='$grado' and ciclo=$ciclo and materia='$materia2'");
	  $cadenaAlOpt="";
	  while($rowalumopt=mysql_fetch_array($alumopt)) $cadenaAlOpt.=",".$rowalumopt["alumno"];
	  if ($cadenaAlOpt!="")
	  { $cadenaAlOpt=substr($cadenaAlOpt,1);
	    $sqlQ="select * from alumnos where seccion='$seccion' and grado='$grado' and activo='A' and plantel=$sede and nuevo_ingreso <> 'P' and alumno in ($cadenaAlOpt)order by apellido_paterno, apellido_materno, nombre ";
	    $sqlQVal="select tareas, asistencia, circulares from tareas_periodo where materia='$materia2' and grado='$grado' and periodo='$periodo' and ciclo='$ciclo' and seccion='$seccion'";
	  }
	  else die("no hay alumnos para esta materia");
	}
	else
	{ $sqlQ = "select * from alumnos where seccion='$seccion' and grado='$grado' and grupo='$grupo' and activo='A' and plantel=$sede and nuevo_ingreso <> 'P' order by apellido_paterno, apellido_materno, nombre ";
	  $sqlQVal="select tareas, asistencia, circulares from tareas_periodo where materia='$materia2' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and ciclo='$ciclo' and seccion='$seccion'";
	}
	$_SESSION['salvaTareas']=0;
	$result=mysql_query($sqlQVal,$link)or die(mysql_error().$sqlQVal);
	$row = mysql_fetch_array($result);
	if (mysql_affected_rows($link)<=0)
	{ $numTareas=0;
	  $numAsistencia=0;
	  $numCirculares=0;
	}
	else
	{ $numTareas=$row["tareas"];
	  $numAsistencia=$row["asistencia"];
	  $numCirculares=$row["circulares"];
	  $_SESSION['salvaTareas']=2;
	}
	if($tareas=='S')
	{ if($_SESSION['salvaTareas']!=2) $_SESSION['salvaTareas']=1;
	  if($reporte=="") echo"<td>Tareas <input style='font-size:8pt;' name='tareas' id='tareas' $size maxlength=2 value='$numTareas' tabindex='60'></td>";
	}
	else echo"<input type='hidden' name='tareas' id='tareas' value='$numTareas'>";
	if($asistencia=='S')
	{ if($_SESSION['salvaTareas']!=2) $_SESSION['salvaTareas']=1;
	  if($reporte=="") echo"<td>Asistencia <input style='font-size:8pt' name='asistencia' id='asistencia' maxlength=2 $size value='$numAsistencia' tabindex='80'></td>";
	}
	else echo"<input type='hidden' name='asistencia' id='asistencia' value='$numAsistencia'>";
	$sqlQCirculares="select comentario from asignacion_materias where materia='$materia2' and grado='$grado' and grupo='$grupo' and ciclo='$ciclo' and seccion='$seccion'";
	$result=mysql_query($sqlQCirculares,$link)or die(mysql_error().$sqlQCirculares);
	$row = mysql_fetch_array($result);
	$circulares=$row["comentario"];
	if ($circulares=='S')
	{ if ($_SESSION['salvaTareas']!=2) $_SESSION['salvaTareas']=1;
	  if($reporte=="") echo"<td>Circulares <input style='font-size:8pt' type='text' name='circulares' id='circulares' $size value='$numCirculares' maxlength=2 tabindex='70'></td>";
	}
	else echo"<input type='hidden' name='circulares' id='circulares' value='$numCirculares'>";

//Parametros de lista de alumnos 
	if($reporte=="lista")
	{ $meses=array("s","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
      $slcton="selected";
	  $slctoc="";
	  $chkdtd="checked";
	  $chkdtm="";
	  $totc=0;
	  $cuadros=0;
	  $slctdmes=1;
	  $nombre_mes="";
	  if(!empty($_POST['muestracalifs']))
	  { if($_POST['o']=='n'){ $slcton="selected"; $slctoc=""; }
		else { $slcton=""; $slctoc="selected"; }
		if($_POST['t']=='d') { $chkdtd="checked"; $chkdtm=""; $totc=$_POST['totc']; $cuadros=$totc; }
		else
		{ $chkdtd="";
		  $chkdtm="checked";
		  $slctdmes=$_POST['mes'];
		  $nombre_mes=$meses[$slctdmes];
		  
		  switch($slctdmes)
		  { case 2: $cuadros=28; break;
		    case 4: $cuadros=30; break;
		    case 6: $cuadros=30; break;
		    case 9: $cuadros=30; break;
		    case 11: $cuadros=30; break;
			default: $cuadros=31; break;
		  }
		}
	  }
	  echo"<th colspan='100%'> Ordenar Alumnos Por: <select name='o' id='o'>
           <option $slcton; value='n'>Nombre</option>
           <option $slctoc; value='c'>N&uacute;mero</option>
        </select> &nbsp; &nbsp; &nbsp; &nbsp;
      Total de cuadros: <input type='radio' $chkdtd name='t' id='t' value='d'>Definir<input size=2 name='totc' id'totc' value='$totc'>&nbsp; <input type='radio' $chkdtm  name='t' id='t' value='m'>D&iacute;as del mes
       <select name='mes' id='mes'>";
      for($m=1;!empty($meses[$m]);$m++)
	  { $selected="";
	    if($slctdmes==$m) $selected="selected";
		echo"<option $selected value='$m'>".$meses[$m]."</option>";
	  }
      echo"</select></th></tr><tr><th colspan='100%'>";
	}
//Parametros del concentrado por grupo
	if($reporte=="concentrado")
	{ $chbx=array("<th colspan='100%'>S&oacute;lo materias SEP",
	    " &nbsp; &nbsp; &nbsp; S&oacute;lo materias con Calificaci&oacute;n",
		"<br><table align='center'><tr><td>Materias</td><td>",
		"</td><td>Tareas</td><td>","</td></tr><tr><td>Promedio</td><td>",
		"</td><td>Circulares</td><td>","</td></tr><tr><td>Reprobadas</td><td>",
		"</td><td>Conducta</td><td>","</td></tr><tr><td>Prom. Apreciativa</td><td>",
		"</td><td>Lugar</td><td>","</td></tr><tr><td>% Asistencia</td><td>",
		"</td><td>Percentil</td><td>","</td></tr><tr><td>Faltas</td><td>");
	  $nchbx=array("sep","mccalif","mmat","mtar","mprom","mcirc","mrep","mcndct","mpaprec","mlgr","mpasist","mper","mflts");
	  for($chk=0;$chk<13;$chk++)
	  { $name=$nchbx[$chk];
	    $checked="checked";
	    if($chk==0 or $chk==5 or $chk==7 or $chk==9 or $chk==11) $checked="";
	    if(!empty($_POST['muestracalifs']))
		{ if(!empty($_POST[$name])) $checked=$_POST[$name];
		  else $checked="";
		} 
		echo $chbx[$chk]."<input type='checkbox' $checked name='$name' id='$name' value='checked'>";
	  }
	  echo"</td></tr></table></th></tr><tr><th colspan='100%'>";
	}?>
	<th>
    <input type="hidden" name="capFaltas" id="capFaltas" value="<?=$asistencia?>">
    <input type="hidden" name="capTareas" id="capTareas" value="<?=$tareas?>">
    <input type="hidden" name="capCirculares" id="capCirculares" value="<?=$circulares?>">
    <input type="hidden" name="capConducta" id="capConducta" value="<?=$conducta?>">
    <input type="hidden" name="evApreciativas" id="evApreciativas" value="<?=$apreciativas?>">
    <input type="hidden" name="muestracalifs" id="muestracalifs" value="">
    <?  if($numperiodo>1) { ?>
    <input type="submit" value="Cargar Forma" onClick="document.getElementById('muestracalifs').value='S';"><? } ?></th>
<tr/> 
</table>
</form><?

if(!empty($_POST['muestracalifs']))
{ $_SESSION['seccion']=$seccion;
  $_SESSION['grado']=$grado;
  $_SESSION['grupo']=$grupo;
  $_SESSION['materia']=$materia;
  $_SESSION['capFaltas']=$asistencia;
  $_SESSION['capConducta']=$conducta;
  $_SESSION['capTareas']=$tareas;
  $_SESSION['capCirculares']=$circulares;
  $_SESSION['evApreciativas']=$apreciativas;
  $_SESSION['periodoEv']=$periodo;
  $_SESSION['hayObservaciones']='N';
  $_SESSION['grupoPond']=$grupo;
?>

<SCRIPT language="javascript">
   <!--
//(event.keyCode<48 || event.keyCode>57) event.returnValue = false;
//<a href="javascript:imprSelec('seleccion')" >Imprime la ficha</a>

function imprSelec(nombre)
{ var ficha = document.getElementById(nombre);
  var ventimp = window.open(' ', 'popimpr');
  ventimp.document.write( ficha.innerHTML );
  ventimp.document.close();
  ventimp.print( );
  ventimp.close();
} 

function arrowMove(e,izq, der, arr,aba)
{  var key= (document.all) ? e.keyCode : e.which;
  switch(key)
  { case 38: if(arr!="noexiste") document.all(arr).focus(); break;
    case 13: if(aba!="noexiste") document.all(aba).focus(); break;
	case 40: if(aba!="noexiste") document.all(aba).focus(); break;
  }
} 

function checkNumber(e,objeto,num,x){
   var key= (document.all) ? e.keyCode : e.which;
   var tecla=String.fromCharCode(key);
   if (num==1){ if (isNaN(tecla)&&key!=190&&key!=8&&key!=16&&key!=18&&key!=46&&key!=37&&key!=39&&(key<96||key>105)&&key!=110) return false; }
   else{ if (isNaN(tecla)&&key!=8&&key!=16&&key!=18&&key!=46&&key!=37&&key!=39&&(key<96||key>105)&&key!=110) return false; }
   if(x<999&&key!=38&&key!=13&&key!=40&&key!=9&&key!=16&&key!=18&&key!=8){if(document.all('C'+x).value==0) return false; }
}

function checa_porcentaje(x){
	var pondSuma=0;
	var donde=document.all("dondeAspectos").value;
	var dondeS=document.all("dondeSub").value;
	var k; 
	//dondeS=4;
	for(k=parseInt(dondeS);k<parseInt(donde);k++)
		pondSuma=pondSuma+parseInt(document.all("C"+k).value);
	if(pondSuma!=100){
		k=parseInt(dondeS);
		document.all("C"+k).focus();
		alert("El total de los porcentajes de los aspectos debe ser 100");
	}
	else
		if(x==1)
			submitForm();
}

function calAux(casilla)
{ var pondSuma=0;
  var suma=0;
  var pondValor="";
  var donde=document.all("dondeAspectos").value;
  var dondeS=document.all("dondeSub").value;
  var clave=casilla.name;
  var quita=document.all(clave+"_").value;
  clave=clave.substring(quita);
  var k;
  for(k=parseInt(dondeS);k<parseInt(donde);k++)
  { if(document.all("C"+k).value=="") document.all("C"+k).value=0; 
    pondSuma=pondSuma*1;
	temp=(document.all("C"+k).value)/1;
	
	pondSuma=pondSuma+temp;
  }
  for(k=parseInt(dondeS);k<parseInt(donde);k++)
  { pondValor=document.all("C"+k).value/pondSuma;
	aspectoValor=document.all("C"+k+""+clave).value;
	//alert (pondValor);
 	if((aspectoValor!="") && (pondValor != "")) 
 	{ t=aspectoValor*pondValor;
	 // t=(t.toFixed(2))/1;
      suma=suma+t;
    }
  }
  suma=suma+.000001;
  if ((document.all("redondeo").value)==1) document.all("C"+donde+""+clave).value=suma.toFixed(0);
  else
  { if((document.all("dec").value)==1) document.all("C"+donde+""+clave).value= (parseInt(suma*10)/10);
	 else document.all("C"+donde+""+clave).value=suma.toFixed(dec);
  }
  return (2);
}

function calSub(casilla)
{ var pondSuma=0;
  var suma=0;
  var pondValor="";
  var clave=casilla.name;
  var donde=document.all("dondeSub").value;
  var dondeEx=document.all("dondeEx").value;
  var quita=document.all(clave+"_").value;

  clave=clave.substring(quita);
  for(j=0;j<donde;j++)
  { if(document.all("C"+j).value=="") document.all("C"+j).value=0; 
    pondSuma=pondSuma*1;
	temp=(document.all("C"+j).value)/1;
	pondSuma=pondSuma+temp;
  }
 for(j=0;j<donde;j++)
  { pondValor=document.all("C"+j).value/pondSuma;
	aspectoValor=document.all("C"+j+clave).value;
 	if((aspectoValor!="") && (pondValor != "")) suma=suma+(aspectoValor*pondValor);
  }
//   alert(suma);
  if ((document.all("redondeo").value)==1) document.all("C"+dondeEx+clave).value=suma.toFixed(0);
  else
  { if((document.all("dec").value)==1) document.all("C"+dondeEx+clave).value=parseInt((suma*100)/10)/10;
	else document.all("C"+dondeEx+clave).value=suma.toFixed(dec);
  }
  //suma = suma + .0001;
  document.all("C"+dondeEx+clave).value=parseInt((suma*100)/10)/10;
  return (2);
}

function calculaCalificacion(casilla,tipo,parCal){
	if(tipo==2){
		if(parCal==2)
			calSub(casilla);
		else
			document.all("dondeSub").value=0;
		calAux(casilla);
	}
	if(tipo==1){
		var totrows=parseInt(document.getElementById("totrows").value);
		var almn;
		for(i=0;i<totrows;i++){
			almn=document.getElementById("a_"+i).value;
			casillaA=casilla.name+"A"+almn;
			casillaA=document.all(casillaA);
			if(parCal==2)
				calSub(casillaA);
			else
				document.all("dondeSub").value=0;
			calAux(casillaA);
			alumnoModificado(casillaA);
		}
	}
}

function checkNumber2(objeto,num)
{ if (num==2) var re5digit=/^\d{1,}\.\d{2}$/ //regular expression defining a 5 digit number
  if (num==1) var re5digit=/^\d{1,}\.\d{1}$/ //regular expression defining a 5 digit number
  valorAnterior=objeto.value;
  valorAnterior=valorAnterior/1;
  valorAnterior=valorAnterior.toFixed(num);
  if ((objeto.value!="") && (objeto.value.search(re5digit)==-1)) objeto.value=valorAnterior;
}

function MaxMin(val,num)
{ var max=document.all("calMax").value;
  var min=document.all("calMin").value;
  valor=parseFloat(val.value);
  if (val.value!="")
  {	if (valor < (parseInt(min))) 
    { alert( "Valor menor al permitido " + min);
   	  val.value="";
	}
  }
  if (val.value!="")
  { if (valor > (parseInt(max)))
    { alert( "Valor mayor al permitido " + max);
   	  val.value="";
	}
  }
  checkNumber2(val,num);
}

function checaTareas(cas)
{ casillaV=cas.value;
  tar=document.getElementById("tareas").value;
  if(parseInt(casillaV) > parseInt(tar))
  { alert("Tienes m�s tareas registradas que las que pusiste en par�metros");
    cas.value="";
  }
  alumnoModificado (cas);
}

function checaFaltas(val)
{ casillaV=val.value;
  asis=document.getElementById("asistencia").value;
  if(parseInt(casillaV) > parseInt(asis))
  {  alert( "Tienes m�s faltas registradas que asistencias en par�metros");
	 val.value="";
  }
  alumnoModificado (val);
}

function checaCirculares(cas)
{ casillaV=cas.value
  tar=document.getElementById("circulares").value;
  if(parseInt(casillaV) > parseInt(tar))
  { alert("Tienes m�s circulares registradas que las que pusiste en par�metros");
    cas.value="";
  }
  alumnoModificado (cas);
}

function alumnoModificado(clave)
{ var claveS=clave.name;
  var donde=claveS.indexOf("A");
  claveS=claveS.substring(donde);
  var modS=document.getElementById("modificados").value;
  if (modS.indexOf(claveS)==-1) document.getElementById("modificados").value=modS+","+claveS;
}

function submitForm()
{
 	document.getElementById("parTareas").value=document.getElementById('tareas').value;
 	document.getElementById("parFaltas").value=document.getElementById('asistencia').value;
 	document.getElementById("parCirculares").value=document.getElementById('circulares').value;
 	document.getElementById('frmS').submit();
}
--></SCRIPT><?

	$sqlQBef = $sqlQ;
    $result=mysql_query($sqlQ,$link)or die(mysql_error().$sqlQ);
	$totalalumnos=mysql_num_rows($result);
	if(mysql_affected_rows($link)==0) die('No hay alumnos con estos par&aacute;metros');
    $sqlQgrado="select * from grados where seccion='$seccion' and ciclo=$ciclo and grado='$grado'";
    $resultgrados=mysql_query($sqlQgrado,$link)or die(mysql_error().$sqlQgrado);
    $rowgrado = mysql_fetch_array($resultgrados);
    if( $_SESSION["calMax"] =="G")
    { if ($rowgrado["calificacion_maxima"]=="D") $_SESSION["calMax"] =10;
	  if ($rowgrado["calificacion_maxima"]=="C") $_SESSION["calMax"] =100;
    }
    $dec=1;
    if ($rowgrado["dos_decimales"]=='S') $dec=2;
	
    $sqlQ2 = "select * from aspectos where seccion='$seccion' and ciclo=$ciclo order by aspecto ";
    $sqlQ3 = "select * from materias_ciclos where seccion='$seccion' and ciclo=$ciclo and apreciativa='S' order by orden ";
    $sqlQ4 = "select * from materias_ciclos where seccion='$seccion' and ciclo=$ciclo and materia='$materia' order by orden ";
    $sqlQSub = "select * from sub_materias where seccion='$seccion' and ciclo=$ciclo and materia='$materia' ";
    $result2=mysql_query($sqlQ2,$link)or die(mysql_error().$sqlQ2);
    $result4=mysql_query($sqlQ4,$link)or die(mysql_error().$sqlQ4);
	$resultSub=mysql_query($sqlQSub,$link)or die(mysql_error().$sqlQSub);
    $row4=mysql_fetch_array($result4);
    $parCal=1;
    $redondeo=0;
	// en submaterias se puso el cálculo con redondeo 0 pero en aspectos debe ser con 1
    if ((mysql_num_rows($result2)>0) && (mysql_num_rows($resultSub)>0))
    //{ if($row4["acumular_submaterias"]=='N')
	  { $parCal=2;
		//if($row4["redondeo_submaterias"]=='S')
	//	$redondeo=1;
	  }
    //}
    else
    //{ if($row4["redondeo_submaterias"]=='S')
	  $redondeo=0;	
    //}
	$header="";
    $calMax=$_SESSION["calMax"];
    $calMin=$_SESSION["calMin"];
	$table_border="";
	$cspan=3;

	if($reporte!="") $table_border="border='1'";
	else $cspan=1;
	$prof_="";
?>
    <input name="calMax" id="calMax" type="hidden" value="<?=$calMax?>" />
    <input name="calMin" id="calMin" type="hidden" value="<?=$calMin?>" />
    <form name="frmS" id='frmS' action="salvaCalificaciones.php" method="post">
    <input name="modificados" id="modificados" type="hidden" value="" />
<div name='' id='seleccion'>   

<? if($reporte!=""){ 

if($reporte!="concentrado") $prof_=mysql_result(mysql_query("select CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) as n from personal where empleado = ".mysql_result(mysql_query("select profesor from asignacion_materias where ciclo=$ciclo and seccion='$seccion' and grado=$grado and grupo='$grupo' and materia='$materia'",$link),0,0),$link),0,0); 
else $prof_=mysql_result(mysql_query("select CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) as n from personal where empleado = ".mysql_result(mysql_query("select titular from grupos where ciclo=$ciclo and seccion='$seccion' and grado=$grado and grupo='$grupo'",$link),0,0),$link),0,0); 
$anch_=$escudo_ancho/2; 
?>

<table><tr>
<td rowspan="2" valign="top"><img src="../<?=$escudo;?>" width="<?=$anch_; ?>" height="40"></td>
<th colspan="3" style="font-size:14pt;"><?=$nombre_colegio;?></th></tr>
<tr><td><? echo date("j/n/Y"); ?></td>
    <th><?=$desc_reporte;?></th>
<td align="right">Ciclo: <u><?=$periodo_actual;?></u></td></tr>
<tr><td colspan="4" align="center">Secci&oacute;n: <u><?=$desc_seccion;?></u>
        &nbsp; Per&iacute;odo: <u><?=$desc_periodo;?> </u>
        &nbsp; Grado: <u><?=$grado;?></u> 
        &nbsp; Grupo: <u><?=$grupo;?></u> 
        &nbsp; <? if($reporte!="concentrado") { echo "Materia: <u>$desc_materia</u> "; } ?> 
        &nbsp; Prof: <u><?=$prof_;?></u></td></tr>
</table>

<? }
  echo"<table style='font-size:8pt;' cellpadding='0' cellspacing='0' $table_border>";
  $headerP="<tr><td style='border-width:0' colspan=$cspan></td>";
  $colCounter=0;
  $numAs=0;
while ($rowSub = mysql_fetch_array($resultSub)){
	$name=$rowSub['submateria'] ;
    $gpo_pon_p_s="";
	if($esOptativa==0)
		$gpo_pon_p_s="and grupo='$grupo'";
	$selectPor="select * from pond_periodo_submat WHERE ciclo='$ciclo' and seccion='$seccion' and grado='$grado' $gpo_pon_p_s and periodo='$periodo' and materia='$materia'and submateria='$name'";
    $resultTemp= mysql_query($selectPor,$link)or die(mysql_error().$selectPor);
    if(mysql_affected_rows($link)==0)
		$val=$rowSub['ponderacion'] ;
    else{
		$rowTemp = mysql_fetch_array($resultTemp);
		$val=$rowTemp['ponderacion'] ;
		$_SESSION['grupoPond']=$rowTemp['grupo'];
    }
    $name="C$numAs";
    $onStr="onchange='javascript:calculaCalificacion($name,1,$parCal)'";
    if($reporte=="")
		$headerP.="<th style='border-width:0'><input $onStr style='font-size:8pt;' type='text' name='$name' id='$name' $size value='$val' maxlength='3'/></th>";
    if($reporte=="tira")
		$headerP.="<td>$val</td>";
    $numAs++;
}
 while($row2=mysql_fetch_array($result2)){
	$name=$row2['aspecto'];
	$gpo_pon_p_a="";
	if($esOptativa==0)
		$gpo_pon_p_a=" and grupo='$grupo'";
	$selectPor="select * from pond_periodo_aspecto WHERE ciclo='$ciclo' and seccion='$seccion' and grado='$grado' $gpo_pon_p_a  and periodo='$periodo' and materia='$materia'and aspecto='$name'";
	$resultTemp=mysql_query($selectPor,$link)or die(mysql_error().$selectPor);
	if(mysql_affected_rows($link)==0)
		$val=$row2['ponderacion'];
	else{
		$rowTemp = mysql_fetch_array($resultTemp);
		$val=$rowTemp['ponderacion'] ;
		$_SESSION['grupoPond']=$rowTemp['grupo'];
	}
	$name="C$numAs";
    $onStr="onchange='javascript:calculaCalificacion($name,1,$parCal)'";
	$arreglo[$numAs]=$name;
    if($reporte=="")
		$headerP.="<th style='border-width:0'><input $onStr style='font-size:8pt;' type='text' name='$name' id='$name' $size value='$val' maxlength='3' /></th>";
	if($reporte=="tira")
		$headerP.="<td>$val</td>";
    $numAs++;
}

  $headerP.="</tr>";
  echo "<script>function reCalcular(){";
	foreach($arreglo as $i)
		echo "calculaCalificacion($i,1,$parCal);
//		alert('ejecut� $i');
		";
  echo "}</script>";
  if($reporte!="lista" && $reporte!="concentrado") echo $headerP;
  $resultSub=mysql_query($sqlQSub,$link)or die(mysql_error().$sqlQSub);
  $colCounter=0;
  while ($rowSub = mysql_fetch_array($resultSub))
  { $cols[$colCounter]=$rowSub["submateria"];
    $header.="<th style='border-width:0' width=35>".$rowSub["submateria"]."</th>";
	$colCounter++;
  } 
  $_SESSION['dondeSub']=$colCounter;
  $result2=mysql_query($sqlQ2,$link)or die(mysql_error().$sqlQ2);
  while ($row2 = mysql_fetch_array($result2))
  { $cols[$colCounter]=$row2["aspecto"];
    $header.="<th style='border-width:0' width='35'>".$row2["abreviatura"]."</th>";
    if($row2["es_examen"]=='S') $_SESSION['dondeEx']=$colCounter;
	$colCounter++;
  }
  $_SESSION['dondeAspectos']=$colCounter;
  $header.="<th style='border-width:0' width='35'>Calif</th>";
  $cols[$colCounter]="Calif";
  $colCounter++; 
  $_SESSION['dondeCalif']=$colCounter;
  $result3=mysql_query("select * from materias_ciclos where seccion='$seccion' and ciclo=$ciclo and apreciativa='S' order by orden ",$link)or die(mysql_error()."select * from materias_ciclos where seccion='$seccion' and ciclo=$ciclo and apreciativa='S' order by orden ");
  if ($apreciativas=='S')
  { while($row3=mysql_fetch_array($result3))
    { $header.="<th style='border-width:0' width='35'>".$row3["materia"]."</th>";
	  $cols[$colCounter]=$row3["materia"];
	  $colCounter++;
    }
	$_SESSION['dondeApreciativa']=$colCounter;
   }
   else $_SESSION['dondeApreciativa']=-1;
  if ($asistencia=='S')
  { $header.="<th style='border-width:0' width='35'>Faltas</th>";
    $cols[$colCounter]="faltas";
    $colCounter++; 
    $_SESSION['dondeFaltas']=$colCounter;
  }
  else $_SESSION['dondeFaltas']=-1;
  if ($tareas=='S')
  { $header.="<th style='border-width:0' width='35'>Tareas</th>";
    $cols[$colCounter]="tareas";
    $colCounter++; 
    $_SESSION['dondeTareas']=$colCounter;
  }
  else $_SESSION['dondeTareas']=-1;
  if ($conducta=='S')
  { $header.="<th style='border-width:0' width='35'>Cndcta</th>";
    $cols[$colCounter]="conducta";
    $colCounter++;
    $_SESSION['dondeConducta']=$colCounter;
  }
  else $_SESSION['dondeConducta']=-1;
  if ($circulares=='S')
  { $header.="<th style='border-width:0' width='35'>Crclrs</th>";
    $cols[$colCounter]="circulares";
    $colCounter++; 
    $_SESSION['dondeCirculares']=$colCounter;
  }
  else $_SESSION['dondeCirculares']=-1;
  if ($row4["observaciones"]=='S')
  { $cols[$colCounter]="observaciones";
    $colCounter++; 
    $header.="<th style='border-width:0' width='35'>Observaciones</th>";
  }
  if ($circulares=='S')
  { $header.="<th style='border-width:0' width='35'>Comentarios</th>";
    $cols[$colCounter]="comentarios";
    $colCounter++;
  }
  echo "";
  $tr="";
  if($reporte=="lista")
  { $header="";
    for($num_cuadro=1;$num_cuadro<=$cuadros;$num_cuadro++)
	{ $titulo="";
	  if($nombre_mes!="") $titulo=$num_cuadro;
	  $header.="<th style='border-width:2'>&nbsp;$titulo </th>";
	  $tr.="<td width=15></td>";
	}
  }
  if($reporte=="concentrado")
  { $header="";
    $tr1="<tr><td colspan='100%' style='border-width:0'>&nbsp;</td></tr>";
	$tr2="";
	$tr3="";
	$tr4="";
	$tr5="";
    $tr6="";
	$tr7="";
	$tr8="";
	$tr9="";
	$tr10="";

// Busca las variables de redondeo de la seccion
    $sql_rdndeo="SELECT calif_minima_aprobar, redondeo_cma_cmb, redondeo_cma_sing, redondeo_mayor_sig, calificacion_maxima FROM secciones where ciclo='$ciclo' and seccion='$seccion'";
    $resul_rdndeo=mysql_query($sql_rdndeo,$link)or die(mysql_error().$sql_rdndeo);
    $id_calif_minima_aprobar=mysql_result($resul_rdndeo,0,0);
    $ld_redondeo_cmb=mysql_result($resul_rdndeo,0,1);
    $ld_redondeo_sig=mysql_result($resul_rdndeo,0,2);
    $ld_redondeo_mayor=mysql_result($resul_rdndeo,0,3);
    $is_tipo_mask=mysql_result($resul_rdndeo,0,4);

// Lee datos por grado
    $sql_d="SELECT calificacion_maxima, dos_decimales FROM grados where ciclo='$ciclo' and seccion='$seccion' and grado='$grado'";
    $resul_d=mysql_query($sql_d,$link)or die(mysql_error().$sql_d);
    $lc_base_grado=mysql_result($resul_d,0,0);
    $ic_dos_decimales=mysql_result($resul_d,0,1);

// Si la calificaci&oacute;n m&aacute;xima es por grado -> lo lee del grado.
    if($is_tipo_mask=='G')
    { $is_tipo_mask=$lc_base_grado;
      if(($is_tipo_mask='C') && ($id_calif_minima_aprobar<10)) $id_calif_minima_aprobar=$id_calif_minima_aprobar*10;
      elseif(($is_tipo_mask='D') && ($id_calif_minima_aprobar>10)) $id_calif_minima_aprobar=$id_calif_minima_aprobar/10;
    }
    if($ic_dos_decimales=='S')
    { $li_redondeo=2;
      if($ld_redondeo_cmb>0) $ld_redondeo_cmb=(1-$ld_redondeo_cmb)*0.01;
      if($ld_redondeo_sig>0) $ld_redondeo_sig=(1-$ld_redondeo_sig)*0.01;
      if($ld_redondeo_mayor>0) $ld_redondeo_mayor=(1-$ld_redondeo_mayor)*0.01;
    }
    if($is_tipo_mask='C')
    { $ld_calif_minima=$id_calif_minima_aprobar-10;
      if($ic_dos_decimales!='S')
      { $li_redondeo=0;
        if($ld_redondeo_cmb>0) $ld_redondeo_cmb=(1-$ld_redondeo_cmb);
        if($ld_redondeo_sig>0) $ld_redondeo_sig=(1-$ld_redondeo_sig);
        if($ld_redondeo_mayor>0) $ld_redondeo_mayor=(1-$ld_redondeo_mayor);
      }
    }
    else
    { $ld_calif_minima=$id_calif_minima_aprobar-1;
      if(($ic_dos_decimales!='S') or ($ic_dos_decimales==''))
      { $li_redondeo=1;
        if($ld_redondeo_cmb>0) $ld_redondeo_cmb=(1-$ld_redondeo_cmb)*0.1;
        if($ld_redondeo_sig>0) $ld_redondeo_sig=(1-$ld_redondeo_sig)*0.1;
        if($ld_redondeo_mayor>0) $ld_redondeo_mayor=(1-$ld_redondeo_mayor)*0.1;
      }
    }

// Obtiene el total de tareas, circulares y asistencia del periodo
    $sq_l="SELECT sum(tareas), sum(circulares), sum(asistencia) FROM tareas_periodo where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo'";
    $resul_t=mysql_query($sq_l,$link)or die(mysql_error().$sql_l);
    $tareas=mysql_result($resul_t,0,0);
    $circulares=mysql_result($resul_t,0,1);
    $asistencias=mysql_result($resul_t,0,2);
	$count_reprbds=0;
	$count_mat=0;
    $solo_sep="";
	if(!empty($_POST["sep"])) $solo_sep="and materias_ciclos.sep = 'S'";
	$sqlam="
      SELECT  asignacion_materias.materia, apreciativa
      FROM    asignacion_materias, materias_ciclos
      WHERE   asignacion_materias.ciclo   = materias_ciclos.ciclo
      and     asignacion_materias.seccion = materias_ciclos.seccion
      and     asignacion_materias.materia = materias_ciclos.materia $solo_sep
      and     asignacion_materias.ciclo = '$ciclo' AND asignacion_materias.seccion = '$seccion'
      and     asignacion_materias.grado = '$grado' AND asignacion_materias.grupo   = '$grupo'
      order by apreciativa, orden" ;
    $resultam=mysql_query($sqlam,$link)or die(mysql_error().$sqlam);
    while ($rowam = mysql_fetch_array($resultam))
    { $lb_continua = true;
      if(!empty($_POST["mccalif"]))
      { if($rowam['apreciativa']=='N')
        { // Verifica que se haya calificado esa materia
          $li_regs=mysql_result(mysql_query("select count(*) from calificaciones where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and materia='".$rowam['materia']."' and calificacion>0",$link),0,0);
	      if(empty($li_regs) or $li_regs==0) $lb_continua = false;
	    }
	    else
	    { $li_regs=mysql_result(mysql_query("select count(*) from calif_apreciativas where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and materia_apreciativa='".$rowam['materia']."' and calificacion>0",$link),0,0);
	      if(empty($li_regs) or $li_regs==0) $lb_continua = false;
	    }
      }
      if($lb_continua)
      { if(!empty($_POST["mmat"]))
		{ $header.="<th>".$rowam['materia']."</th>";
		  $sql_tr2="Select tareas from tareas_periodo where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and materia='".$rowam['materia']."'";
		  $r_tr2=mysql_query($sql_tr2,$link)or die(mysql_error().$sql_tr2);
		  $vl="";
	      if(mysql_affected_rows($link)>0){ $vl=mysql_result($r_tr2,0,0); if($vl!="") $vl=number_format($vl,$dec); }
		  $tr2.="<td>$vl &nbsp;</td>";

// Calcula el # y porcentaje de materias reprobadas
		  $sql_tr3="SELECT count(*) FROM calificaciones where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and materia='".$rowam['materia']."' and calificacion<$id_calif_minima_aprobar";
		  $r_tr3=mysql_query($sql_tr3,$link)or die(mysql_error().$sql_tr3);
		  $vl="";
		  $value="";
	      if(mysql_affected_rows($link)>0)
		  { $vl=mysql_result($r_tr3,0,0); 
		    if($vl!="")
			{ $count_reprbds=$count_reprbds+$vl;
			  $vl=number_format($vl,$dec);
			  $value=$vl/$totalalumnos*100;
			  $value=number_format($value,$dec);
			  
			} 
		  }
		  $tr3.="<td>$vl &nbsp;</td>";
		  $tr4.="<td>$value &nbsp;</td>";

// Calcula el porcentaje de examenes reprobados
		  $sql_tr5="SELECT count(*) FROM calif_aspectos, secciones, aspectos
		  WHERE calif_aspectos.ciclo   = aspectos.ciclo    and  
				calif_aspectos.seccion = aspectos.seccion  and  
				calif_aspectos.aspecto = aspectos.aspecto  and  
				calif_aspectos.ciclo   = secciones.ciclo   and  
				calif_aspectos.seccion = secciones.seccion and  
				aspectos.es_examen     = 'S' and
				calif_aspectos.ciclo   = '$ciclo'   AND  
				calif_aspectos.seccion = '$seccion' AND  
				calif_aspectos.grado   = '$grado'   AND  
				calif_aspectos.grupo   = '$grupo'   AND  
				calif_aspectos.periodo = '$periodo' AND  
				calif_aspectos.materia = '".$rowam['materia']."' AND  
				calif_aspectos.calificacion < $id_calif_minima_aprobar";
		  $r_tr5=mysql_query($sql_tr5,$link)or die(mysql_error().$sql_tr5);
		  $vl="";
		  $value="";
	      if(mysql_affected_rows($link)>0)
		  { $vl=mysql_result($r_tr5,0,0); 
		    if($vl!="")
			{ $vl=number_format($vl,$dec);
			  $value=$vl/$totalalumnos*100.00;
			  $value=number_format($value,$dec);
			} 
		  }
		  $tr5.="<td>$vl &nbsp;</td>";
		  $count_reprbds=$count_reprbds+$vl;
		  $count_mat++;
		  $tr6.="<td>$value &nbsp;</td>";
          $sqlsubm="SELECT submateria FROM sub_materias WHERE ciclo='$ciclo' and seccion='$seccion' AND materia='".$rowam['materia']."'";
	      $resultsubm=mysql_query($sqlsubm,$link) or die(mysql_error().$sqlsubm);
	      while($rsm=mysql_fetch_array($resultsubm))
		  { $header.="<th>".$rsm['submateria']."</th>";
		    $tr2.="<td>&nbsp;</td>";
		    $tr3.="<td>&nbsp;</td>";
		    $tr4.="<td>&nbsp;</td>";
		    $tr5.="<td>&nbsp;</td>";
		    $tr6.="<td>&nbsp;</td>";
		  }
	    }
      }
    }
	$ta=number_format($totalalumnos,$dec);
	if(!empty($_POST["mtar"]))
	{ $header.="<th>Tar.Entrg</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mcndct"]))
	{ $header.="<th>Conduc</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mlgr"]))
	{ $header.="<th>Lugar</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mper"]))
	{ $header.="<th>Percen</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mprom"]))
	{ $header.="<th>Prom</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mrep"]))
	{ $header.="<th># R</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>$count_reprbds</td>";
	  $value="100";
	  $tmaterias=$count_mat*$totalalumnos;
      if($count_reprbds<$tmaterias) $value=($count_reprbds*100/$tmaterias);
	  $value=number_format($value,$dec);
	  $tr6.="<td>$value</td>";
	}
    if(!empty($_POST["mpaprec"]))
	{ $header.="<th>PApre</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mtar"]))
	{ $header.="<th>%Tar</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	  
	}
    if(!empty($_POST["mpasist"]))
	{ $header.="<th>%Asist</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mflts"]))
	{ $header.="<th>Faltas</th>";
	  $tr2.="<td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td>";
	}
    if(!empty($_POST["mcirc"]))
	{ $header.="<th>Circ</th><th>%Circ</th>";
	  $tr2.="<td>&nbsp;</td><td>&nbsp;</td>";
	  $tr3.="<td>&nbsp;</td><td>&nbsp;</td>";
	  $tr4.="<td>&nbsp;</td><td>&nbsp;</td>";
	  $tr5.="<td>&nbsp;</td><td>&nbsp;</td>";
	  $tr6.="<td>&nbsp;</td><td>&nbsp;</td>";
	}
  }

  if($reporte!="") $header="<tr><th style='border-width:0'>NL</th><th style='border-width:0'>Alumno</th><td style='border-width:0'><b>Nombre</b></td>$header</tr> ";
  else $header="<tr><td><b>Nombre</b></td>$header</tr>";
  echo $header;
?>

<input name="dondeAspectos" id="dondeAspectos" type="hidden" value="<?=$_SESSION['dondeAspectos']?>" />
<input name="dondeSub" id="dondeSub" type="hidden" value="<?=$_SESSION['dondeSub']?>" />
<input name="dondeEx" id="dondeEx" type="hidden" value="<?=$_SESSION['dondeEx']?>" />
<input name="redondeo" id="redondeo" type="hidden" value="<?=$redondeo?>" />
<input name="dec" id="dec" type="hidden" value="<?=$dec?>" />
<?
$resultBef=mysql_query($sqlQBef,$link)or die(mysql_error().$sqlQBef);

//CALIF BOXES
$rowCounter=0;
$rowCounterTotal=0;
$rowCounterTemp=0;
$rowcount=0;
$contador=0;
while ($row = mysql_fetch_array($resultBef))
{ $alumno=$row['alumno'];
  $aalumno="A$alumno";
  $arrayAlumnos[$rowCounterTotal]=$aalumno;
  $rowCounterTotal=$rowCounterTotal+1;    
}
while($row=mysql_fetch_array($result))
{ $iniciaCol=0;
  $alumno=$row['alumno'];
  $aalumno="A$alumno";
  $grupo=$row["grupo"];
  $rowCounter++;
  $rowcount++;
  $contador++;
  if ($rowCounter==15){ $rowCounter=0; $rowcount=0; echo $header; } 
  if ($rowcount==5){ $rowcount=0; echo"<tr height='1'><th bgcolor='#000000' colspan='100%'></th></tr>"; }?> 
<tr align="center"><?
if($reporte!=""){ ?>
  <th style="border-width:0" align="right"><?=$contador;?></th>
  <td style='border-width:0'>&nbsp; <?=$alumno;?></td><? } ?>
  <td style="border-width:0" align="left"> <?=$row["apellido_paterno"];?> <?=$row["apellido_materno"];?> <?=$row["nombre"];?>
<input type="hidden" name="a_<?=$rowCounterTemp;?>" id="a_<?=$rowCounterTemp;?>" value="<?=$row["alumno"];?>"></td><?
 if($reporte=="lista") echo $tr;
 elseif($reporte=="concentrado")
 {  $ld_tareas=0;
    $ld_circulares=0;
    $ld_faltas=0;
    $suma_prom=0;
    $count_mat=0;
    $suma_aprecia=0;
    $count_aprecia=0;
	$count_reprbds=0;
    $solo_sep="";
	if(!empty($_POST["sep"])) $solo_sep="and materias_ciclos.sep = 'S'";
	$sqlam="
      SELECT  asignacion_materias.materia, apreciativa
      FROM    asignacion_materias, materias_ciclos
      WHERE   asignacion_materias.ciclo   = materias_ciclos.ciclo
      and     asignacion_materias.seccion = materias_ciclos.seccion
      and     asignacion_materias.materia = materias_ciclos.materia $solo_sep
      and     asignacion_materias.ciclo = '$ciclo' AND asignacion_materias.seccion = '$seccion'
      and     asignacion_materias.grado = '$grado' AND asignacion_materias.grupo   = '$grupo'
      order by apreciativa, orden" ;
    $resultam=mysql_query($sqlam,$link)or die(mysql_error().$sqlam);
    while ($rowam = mysql_fetch_array($resultam))
    { $lb_continua = true;
      if(!empty($_POST["mccalif"]))
      { if($rowam['apreciativa']=='N')
        { // Verifica que se haya calificado esa materia
          $li_regs=mysql_result(mysql_query("select count(*) from calificaciones where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and materia='".$rowam['materia']."' and calificacion>0",$link),0,0);
	      if(empty($li_regs) or $li_regs==0) $lb_continua = false;
	    }
	    else
	    { $li_regs=mysql_result(mysql_query("select count(*) from calif_apreciativas where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and materia_apreciativa='".$rowam['materia']."' and calificacion>0",$link),0,0);
	      if(empty($li_regs) or $li_regs==0) $lb_continua = false;
	    }
      }
      if($lb_continua)
      { if(!empty($_POST["mmat"]))
		{ $vl="";
		  if($rowam['apreciativa']=='N')
          { $sq="select calificacion, promedia, tareas_entregadas, circulares_entregadas, faltas
			from   calificaciones, materias_ciclos
			where  calificaciones.ciclo   = materias_ciclos.ciclo
			and    calificaciones.seccion = materias_ciclos.seccion
			and    calificaciones.materia = materias_ciclos.materia
			and    calificaciones.ciclo   = '$ciclo'
			and	   calificaciones.seccion = '$seccion'
			and    calificaciones.materia = '".$rowam['materia']."'
			and      grado='$grado' and grupo='$grupo'
			and      periodo='$periodo' and alumno='$alumno'
			and    calificacion > 0";
		    $r_sq=mysql_query($sq,$link)or die(mysql_error().$sq);
	        if(mysql_affected_rows($link)>0)
	        { $rma = mysql_fetch_array($r_sq);
	          $vl=$rma["calificacion"];
			  $vl=number_format($vl,$dec);
		      // Acumula las faltas, circulares y tareas
			  if($rma["tareas_entregadas"]!="") $ld_tareas=$ld_tareas+$rma["tareas_entregadas"];
			  if($rma["circulares_entregadas"]!="") $ld_circulares=$ld_circulares+$rma["circulares_entregadas"];
			  if($rma["faltas"]!="") $ld_faltas=$ld_faltas+$rma["faltas"];
			  // Si la materia se promedia
			  if($rma["promedia"]=='S')
			  { $suma_prom=$suma_prom+$vl;
			    $count_mat++;
				// Determina si el alumno reprobo la materia
				if($rma["calificacion"]<$id_calif_minima_aprobar) $count_reprbds++;
			  }
		    }
	      }
	      else
          { $sql_apc="select sum(calificacion) / count(*) from calif_apreciativas where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and alumno='$alumno' and materia_apreciativa='".$rowam['materia']."' and calificacion>0";
		    $r_aprc=mysql_query($sql_apc,$link)or die(mysql_error().$sql_apc);
	        if(mysql_affected_rows($link)>0)
			{ $vl=mysql_result($r_aprc,0,0); 
			  $vl=number_format($vl,$dec);
			  $suma_aprecia=$suma_aprecia+$vl;
			  $count_aprecia++;
			}
	      }
		  echo"<td>&nbsp;$vl</td>";
		  $materia=$rowam['materia'];
		  if(empty($id_suma[$materia])) $id_suma[$materia]="0";
		  $id_suma[$materia].=",$vl";
          $sqlsubm="SELECT submateria FROM sub_materias WHERE ciclo='$ciclo' and seccion='$seccion' AND materia='".$rowam['materia']."'";
	      $resultsubm=mysql_query($sqlsubm,$link) or die(mysql_error().$sqlsubm);
	      while($rsm=mysql_fetch_array($resultsubm))
		  { $submateria=$rsm['submateria'];
		    if(empty($id_suma[$submateria])) $id_suma[$submateria]="0";
		    $sql_csub="select calificacion from calif_submaterias where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and alumno='$alumno' and submateria='".$rsm['submateria']."'";
		    $r_csub=mysql_query($sql_csub,$link)or die(mysql_error().$sql_csub);
		    $vl="";
	        if(mysql_affected_rows($link)>0)
		    { $vl=mysql_result($r_csub,0,0); 
		      if($vl!=""){ $vl=number_format($vl,$dec); $id_suma[$submateria].=",$vl"; } 
		    }
		    echo"<td>&nbsp;$vl</td>";
		  }
	    }
      }
    }
	if(!empty($_POST["mtar"]))
	{ if(empty($id_suma['mtar'])) $id_suma['mtar']="0";
	  $id_suma['mtar'].=",$ld_tareas";
	  if($ld_tareas==0) $ld_tareas=""; 
	  echo"<td>&nbsp;$ld_tareas</td>";
	}
    if(!empty($_POST["mcndct"]))
	{ $vl=mysql_result(mysql_query("select sum(conducta)/count(*) from calificaciones where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and alumno='$alumno' and conducta>0",$link),0,0);
	  if(empty($id_suma['mcndct'])) $id_suma['mcndct']="0";
	  $id_suma['mcndct'].=",$vl";
	  if($vl==0) $vl=""; 
	  echo"<td>&nbsp;$vl</td>";
	}
	if(!empty($_POST["mlgr"]))
	{ $vl=mysql_result(mysql_query("select lugar from calif_promedios where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and alumno='$alumno'",$link),0,0);
	  if(empty($id_suma['mlgr'])) $id_suma['mlgr']="0";
	  $id_suma['mlgr'].=",$vl";
	  if($vl==0) $vl=""; 
	  echo"<td>&nbsp;$vl</td>";
	}
    if(!empty($_POST["mper"]))
	{ $vl=mysql_result(mysql_query("select percentil from calif_promedios where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodo' and alumno='$alumno'",$link),0,0);
	  if(empty($id_suma['mper'])) $id_suma['mper']="0";
	  $id_suma['mper'].=",$vl";
	  if($vl==0) $vl=""; 
	  echo"<td>&nbsp;$vl</td>";
	}
    if(!empty($_POST["mprom"]))
	{ $vl=0.00;
	  if($suma_prom>0 and $count_mat>0) $vl=intval(($suma_prom*100)/$count_mat)/100;
	  if(empty($id_suma['mprom'])) $id_suma['mprom']="0";
	  $id_suma['mprom'].=",$vl";
	  if($vl==0.00) $vl=""; 
	  echo"<td>&nbsp;$vl</td>";
	}
    if(!empty($_POST["mrep"]))
	{ if(empty($id_suma['mrep'])) $id_suma['mrep']="0";
	  $id_suma['mrep'].=",$count_reprbds";
	  if($count_reprbds==0) $count_reprbds=""; 
	  echo"<td>&nbsp;$count_reprbds</td>";
	}
    if(!empty($_POST["mpaprec"]))
	{ $vl=0.00;
	  if($suma_aprecia>0 and $count_aprecia>0) $vl=intval(($suma_aprecia*100)/$count_aprecia)/100;
	  if(empty($id_suma['mpaprec'])) $id_suma['mpaprec']="0";
	  $id_suma['mpaprec'].=",$vl";
	  if($vl==0.00) $vl=""; 
	  echo"<td>&nbsp;$vl</td>";
	}
    if(!empty($_POST["mtar"]))
	{ if(empty($id_suma['mptar'])) $id_suma['mptar']="0";
	  $vl="";
	  if($ld_tareas!="")
	  { if($ld_tareas>$tareas) $vl=100;
	    else{ $vl=($ld_tareas*100/$tareas); $vl=number_format($vl,0); $id_suma['mptar'].=",$vl"; }
	  }
	  echo"<td>$vl%</td>";
	}
    if(!empty($_POST["mpasist"]))
	{ if(empty($id_suma['mpasist'])) $id_suma['mpasist']="0";
	  $vl="";
	  if($ld_faltas==0) $ld_faltas="";
	  elseif($ld_faltas>$asistencias) $vl=100;
	  else{ $vl=(($asistencias-$ld_faltas)/$asistencias*100); $vl=number_format($vl,0); $id_suma['mpasist'].=",$vl"; }
	  echo"<td>$vl%</td>";
	}
    if(!empty($_POST["mflts"]))
	{ if(empty($id_suma['mflts'])) $id_suma['mflts']="0";
	  if($ld_faltas!="") $id_suma['mflts'].=",$ld_faltas";
	  echo"<td>&nbsp;$ld_faltas</td>";
	}
    if(!empty($_POST["mcirc"]))
	{ $vl="";
	  if(empty($id_suma['mcirc'])){ $id_suma['mcirc']="0"; $id_suma['mpcirc']="0"; }
	  if($ld_circulares==0) $ld_circulares="";
	  elseif($ld_circulares>$ld_circulares) $vl=100;
	  else
	  { $vl=$ld_circulares*100/$circulares;
	    $vl=number_format($vl,0);
	    $id_suma['mcirc'].=",$ld_circulares";
	    $id_suma['mpcirc'].=",$vl";
	  }
	  echo"<td>&nbsp;$ld_circulares</td><td>$vl%</td>";
	}
 }
 else
 {

//SUBMATERIAS
  for ($i=$iniciaCol; $i<$_SESSION['dondeSub'];$i++) 
  { $submateria=$cols[$i];
    $sqlQRe="SELECT calificacion FROM calif_submaterias WHERE ciclo=$ciclo and seccion='$seccion' and grado=$grado and grupo='$grupo' and periodo='$periodo' and materia='$materia' and submateria='$submateria' and alumno=$alumno";
    $nombreCol="C$i$aalumno";
	$quita=2;
	if($i>10) $quita=3;
	if($i>100) $quita=4;
	$result2=mysql_query($sqlQRe,$link)or die(mysql_error().$sqlQRe);
	$value_subm="";
	if (mysql_affected_rows($link)>0){ $value=mysql_result($result2,0,0); $value_subm=number_format($value,$dec); }
	if($reporte==""){?>	<td style='border-width:0' align="left"><input style="font-size:8pt;" onChange="javascript:calculaCalificacion (<?="$nombreCol"?>,2,<?=$parCal?>); alumnoModificado(<?="$nombreCol"?>);" onKeyDown="javascript:arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>');  return checkNumber(event,<?="$nombreCol"?>,1,999);" onBlur="javascript:MaxMin (<?=$nombreCol?>,<?=$dec?>)" type="text" name="<?=$nombreCol?>" id="<?=$nombreCol?>" value="<?=$value_subm;?>" <?=$size;?> maxlength="5" /><input type="hidden" name="<?=$nombreCol?>_" id="<?=$nombreCol?>_" value="<?=$quita;?>"></td><?
	}
	if($reporte=="tira") echo"<td>$value_subm &nbsp;</td>";
	 $iniciaCol++;
  }

//Aspectos
  for ($i=$iniciaCol;$i<$_SESSION['dondeAspectos'];$i++) 
  { $aspecto=$cols[$i] ;
	$sqlQRe="SELECT calificacion FROM calif_aspectos WHERE ciclo=$ciclo and seccion='$seccion' and grado=$grado and grupo='$grupo' and periodo='$periodo' and materia='$materia' and aspecto='$aspecto' and alumno=$alumno";
	$nombreCol="C$i$aalumno";
	$quita=2;
	if($i>10) $quita=3;
	if($i>100) $quita=4;
	$result2=mysql_query($sqlQRe,$link)or die(mysql_error().$sqlQRe);
	$value_aspct=""; //////////////////////////////////////AQUIIIIIIIIIIIIIIIIIII

	if (mysql_affected_rows($link)>0){ $value=mysql_result($result2,0,0); $value_aspct=number_format($value,$dec); }
	if($reporte==""){?><td style='border-width:0' align="left">
<input style="font-size:8pt;" onChange="javascript:calculaCalificacion (<?="$nombreCol"?>,2,<?=$parCal?>); alumnoModificado(<?="$nombreCol"?>);" onKeyDown="javascript: arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>'); return checkNumber(event,<?="$nombreCol"?>,1,<?="$i"?>);" onBlur="javascript:MaxMin(<?=$nombreCol?>,<?=$dec?>);" type="text" name="<?=$nombreCol?>" id="<?=$nombreCol?>" value="<?=$value_aspct;?>" align="MIDDLE" <?=$size;?> maxlength="5" />
<input type="hidden" name="<?=$nombreCol?>_" id="<?=$nombreCol?>_" value="<?=$quita;?>"></td><?
	}
	if($reporte=="tira") echo"<td>$value_aspct &nbsp;</td>";
	$iniciaCol++;
  }

//Calif
  for ($i=$iniciaCol; $i<$_SESSION['dondeCalif'];$i++) 
  { $aspecto=$cols[$i] ;
    $nombreCol="C$i$aalumno"; 
	$tareaval= "";
	$circularesval="";
	$faltasval="";
	$conductaval="";
	$sqlQRe="SELECT * FROM calificaciones WHERE ciclo=$ciclo and seccion='$seccion' and grado=$grado and grupo='$grupo' and periodo='$periodo' and materia='$materia' and alumno=$alumno";
	$result2=mysql_query($sqlQRe,$link)or die(mysql_error().$sqlQRe);
	$value_calif="";
	if (mysql_affected_rows($link)>0 )
	{ $rowCal = mysql_fetch_array($result2);
	  $value=$rowCal["calificacion"];
	  $value_calif=number_format($value,$dec);
	  $tareaval= $rowCal["tareas_entregadas"];
	  $circularesval=$rowCal["circulares_entregadas"];
	  $faltasval=$rowCal["FALTAS"];
	  $conductaval= $rowCal["conducta"];
	  $observal= $rowCal["observacion"];
	  $comval= $rowCal["comentario"];
    }
    if($reporte==""){?><td style='border-width:0' align="left"><input style="font-size:8pt;" type="text" readonly = "readonly" onChange="javascript:alumnoModificado(<?="$nombreCol"?>);" onKeyDown="javascript:arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>'); return checkNumber(event,<?="$nombreCol"?>,1,999);" onBlur="javascript:MaxMin (<?=$nombreCol?>,<?=$dec?>)" name="<?=$nombreCol?>" id="<?=$nombreCol?>" align="MIDDLE" value="<?=$value_calif;?>" <?=$size;?> maxlength="3" /></td><?
	}
	if($reporte=="tira") echo"<td>$value_calif &nbsp;</td>";
	$iniciaCol++;
  }

//Materias Apreciativas
  for ($i=$iniciaCol; $i<$_SESSION['dondeApreciativa'];$i++) 
  { $matapre=$cols[$i] ;
	$nombreCol="C$i$aalumno";
	$sqlQRe="SELECT calificacion from calif_apreciativas where ciclo=$ciclo and seccion='$seccion' and grado=$grado and grupo='$grupo' and periodo='$periodo' and materia='$materia' and materia_apreciativa='$matapre' and alumno=$alumno";
	$result2=mysql_query($sqlQRe,$link)or die(mysql_error().$sqlQRe);
	$value_aprec="";
	if (mysql_affected_rows($link)>0){ $value=mysql_result($result2,0,0); $value_aprec=number_format($value,$dec); }
	if($reporte==""){?><td style='border-width:0' align="left"><input style="font-size:8pt;" type="text" onChange="javascript:alumnoModificado(<?="$nombreCol"?>);" onKeyDown="javascript:arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>'); return checkNumber(event,<?="$nombreCol"?>,1,999);" onBlur="javascript:MaxMin (<?=$nombreCol?>,<?=$dec?>)" value="<?=$value_aprec;?>" name="<?=$nombreCol?>" id="<?=$nombreCol?>" align="MIDDLE" <?=$size;?> maxlength="5" /></td><?
	}
	if($reporte=="tira") echo"<td>$value_aprec &nbsp;</td>";
	$iniciaCol++;
  }

//faltas
  for ($i=$iniciaCol; $i<$_SESSION['dondeFaltas'];$i++) 
  { $aspecto=$cols[$i] ;
	$nombreCol="C$i$aalumno";
    $value=$faltasval;
	if($reporte==""){?><td style='border-width:0' align="left"><input onBlur="checaFaltas(<?=$nombreCol;?>);" type="text" style="font-size:8pt;" onKeyDown="javascript:arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>'); return checkNumber(event,<?="$nombreCol"?>,2,999);" value="<?=$value?>" name="<?=$nombreCol?>" id="<?=$nombreCol?>" align="MIDDLE" <?=$size;?> maxlength="3" /></td><?
	}
	if($reporte=="tira") echo"<td>$value &nbsp;</td>";
	$iniciaCol++;
  }

//Tareas
  for ($i=$iniciaCol; $i<$_SESSION['dondeTareas'];$i++) 
  { $aspecto=$cols[$i] ;
	$nombreCol="C$i$aalumno";
	$value=$tareaval;
	if($reporte==""){?><td style='border-width:0' align="left"><input style="font-size:8pt;" onBlur="javascript:checaTareas (<?=$nombreCol?>)"  type="text" value="<?=$value?>" onKeyDown="javascript:arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>'); return checkNumber(event,<?="$nombreCol"?>,2,999);" name="<?=$nombreCol?>" id="<?=$nombreCol?>" align="MIDDLE" <?=$size;?> maxlength="3" /></td> <?
	}
	if($reporte=="tira") echo"<td>$value &nbsp;</td>";
	$iniciaCol++;
  }

//Conducta
  for ($i=$iniciaCol; $i<$_SESSION['dondeConducta'];$i++) 
  { $aspecto=$cols[$i] ;
	$nombreCol="C$i$aalumno";
	$value=$conductaval;
	if($reporte==""){?><td style='border-width:0' align="left"><input type="text" style="font-size:8pt;" onBlur="javascript:MaxMin (<?=$nombreCol?>,<?=$dec?>)" onKeyDown="javascript:arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>'); return checkNumber(event,<?="$nombreCol"?>,1,999);" name="<?=$nombreCol?>" id="<?=$nombreCol?>" align="MIDDLE" value="<?=$value?>" <?=$size;?> maxlength="3"  onchange="javascript:alumnoModificado(<?="$nombreCol"?>);"/></td><?
	}
	if($reporte=="tira") echo"<td>$value &nbsp;</td>";
	$iniciaCol++;
  }   

//Circulares	
  for ($i=$iniciaCol; $i<$_SESSION['dondeCirculares'];$i++) 
  { $aspecto=$cols[$i] ;
	$nombreCol="C$i$aalumno";
	$value=$circularesval;
	if($reporte==""){?><td style='border-width:0' align="left"><input style="font-size:8pt;" type="text" onBlur="javascript:checaCirculares (<?=$nombreCol?>)" onChange="javascript:alumnoModificado(<?="$nombreCol"?>);" onKeyDown="javascript:arrowMove(event,0,0,'<?=retAround(3,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>','<?=retAround(4,$nombreCol,$arrayAlumnos,$rowCounterTemp,$rowCounterTotal)?>'); return checkNumber(event,<?="$nombreCol"?>,2,999);" name="<?=$nombreCol?>" id="<?=$nombreCol?>" align="MIDDLE" <?=$size;?> maxlength="3" value="<?=substr($value,0,3)?>" /></td><?
	}
	if($reporte=="tira") echo"<td>$value &nbsp;</td>";
	$iniciaCol++;

//Comentarios
	if($reporte=="")
	{ $nombreCol="C$iniciaCol$aalumno";
      $sqlObs="select * from comentarios_calif where seccion='$seccion' and ciclo=$ciclo";
	  $result5=mysql_query($sqlObs,$link)or die(mysql_error().$sqlObs);
      echo "<td style='border-width:0'><select tabindex='50' style='width:70' onchange='javascript:alumnoModificado($nombreCol);' height='1' name='$nombreCol'  title='comentarios'>";
	  while ($row5 = mysql_fetch_array($result5))
 	  { $comentario=$row5["comentario"];
	    $comDesc=$row5["descripcion"];
	    $selected="";
	    if($comval==$comentario) $selected="selected";
	    $truncated="$comDesc";
	    $truncated=substr($truncated,0,30);
	    echo"<option $selected value='$comentario'><b>$comentario</b> $truncated</option>";
 	  }
	  echo "</select></td>";
	}
	if($reporte=="tira") echo"<td>$comval &nbsp;</td>";
	$iniciaCol++;
  }

//Observaciones
  if ($row4["observaciones"]=='S')
  { $_SESSION['hayObservaciones']='S';
    if($reporte=="")
	{ $nombreCol="C$iniciaCol$aalumno";
 	  $sqlObs="select * from observaciones_calif where seccion='$seccion' and ciclo=$ciclo";
	  $result5=mysql_query($sqlObs,$link)or die(mysql_error().$sqlObs);
	  echo "<td style='border-width:0'><select tabindex='50' onchange='javascript:alumnoModificado($nombreCol);' height='1' name='$nombreCol'  title='observaciones'>";
 	  while($row5 = mysql_fetch_array($result5))
 	  { $observacion=$row5["observacion"];
	    $obsDesc=$row5["descripcion"];
	    $selected="";
	    if($observal==$observacion) $selected="selected";
	    echo"<option $selected value='$observacion'>$obsDesc</option>";
 	  }
	  echo "</select></td>";
	}
	if($reporte=="tira") echo"<td>$observal &nbsp;</td>";
	$iniciaCol++;
  }
 }

//if($reporte=="" or $reporte=="tira")
  ?></tr><? 
  $rowCounterTemp++;
}//while 
$_SESSION['cols']=$cols;
if($reporte=="concentrado")
{  

//se suman todas las calificaciones por columna
	foreach($id_suma as $key => $val)
	{ $promedio[$key]=0.00; 
	  $desv[$key]=0.00;
	  $vcalifs=explode( ",",$val);
	  $suma=0;
	  foreach($vcalifs as $vcalif) $suma=$suma+floatval($vcalif);
   
//se calcula el promedio de las columnas
	  $promedio[$key]=intval(($suma*100)/$ta)/100;
	  $tr7.="<td>".$promedio[$key]."</td>";
   
//se obtiene la desviaci&oacute;n est&aacute;ndar
	  foreach($vcalifs as $vcalif) $desv[$key]=$desv[$key]+abs($promedio[$key]-$vcalif);	
	}
   
//se obtiene los rangos inferior y superior
	foreach($desv as $key => $val)
	{ $rango_s=$promedio[$key]+$val;
	  $rango_s=number_format($rango_s,$dec);
	  $rango_i=$promedio[$key]-$val;
	  $rango_i=number_format($rango_i,$dec);
	  $tr8.="<td>$val</td>";
	  $tr9.="<td>$rango_s</td>";
	  $tr10.="<td>$rango_i</td>";
	}
echo $tr1;
echo "<tr><th colspan=3>Tareas</th>$tr2</tr>";
echo "<tr><th colspan=3># Reprobados Materia</th>$tr3</tr>";
echo "<tr><th colspan=3>% Reprobados Materia</th>$tr4</tr>";
echo "<tr><th colspan=3># Reprobados en Examen</th>$tr5</tr>";
echo "<tr><th colspan=3>% Reprobados en Examen</th>$tr6</tr>";
echo "<tr><th colspan=3>Promedio</th>$tr7</tr>";
echo "<tr><th colspan=3>Desviaci&oacute;n Est&aacute;ndar</th>$tr8";
echo "<tr><th colspan=3>Rango Superior</th>$tr9";
echo "<tr><th colspan=3>Rango Inferior</th>$tr10";

}
if($reporte=="")
{
?> 
<tr>
  <th style='border-width:0' colspan="100%"><input type="button" value="Guardar" name="btnSubmit" onClick="javascript:checa_porcentaje(1);reCalcular();" />
<input type="hidden" name="totrows" id="totrows" value="<?=$rowCounterTemp;?>">
<input type="hidden" name="parFaltas" id="parFaltas" value=0>
<input type="hidden" name="parTareas" id="parTareas" value=0 >
<input type="hidden" name="parCirculares" id="parCirculares" value=0>
<input type="hidden" name="parConducta" id="parConducta" value=0></th>
</tr><? 
}

?>
</table></div>
</form><?
if($reporte!="") { ?>
<div align="center"><input type="button" onClick="imprSelec('seleccion')" value="Imprimir"></div><? 
}
  //echo "<script>alert('entra 4 nuevo')</script>";

}//if($_POST['muestracalifs']=="S") 
?>
</body>
</html>