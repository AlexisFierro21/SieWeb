<?
session_start();
include('../config.php');
include('../functions.php');
mysql_query('SET CHARACTER SET "UTF8"');
/// 19-01-2016
///Correción de reportes entrevistas por padres de familia, grupo, preceptorias por preceptor, grupo
///26-01-2015 Correxión libreria CSS para datetime picker
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head>

<body> 

	<script src="../../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>

 	<link rel="stylesheet" type="text/css" href="../../repositorio/css/jquery-ui.theme.css" /><!-- Libreria DatetimePicker-->
 	  
<script type="text/javascript">
$(document).ready(function() {
   $("#fecha_ini").datepicker();
});

$(document).ready(function() {
   $("#fecha_fin").datepicker();
});


jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&nbsp;&#x3c;Ant&nbsp;',
		nextText: '&nbsp;Sig&#x3e;&nbsp;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['D','L','M','M;','J','V','S'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: '',
		duration: 10
		};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});   

</script>
<?
///// Configuración de colores
 
?>
<style>
     
     	.menus tr{ background: #fff !important}
     	#menus tr{ background: #fff !important}
     	.menus{ 
     			border: 1px solid #fff !important;
     			background: #fff !important;
     			}
     	
     	
     	.reporte tr:nth-child(even) { 
     		background: <? echo $reporte_tabla ?>;
     		}
		.reporte tr:nth-child(odd) { 
			background: #fff;
			}
	
		.reporte tr:nth-child(1),
		.reporte tr:nth-child(2),
		.reporte tr:nth-child(3),
		.reporte tr:nth-child(4),
		.reporte tr:nth-child(5) { 
     		background: #fff;	
     		}	 
		
		.titulos_reporte th{
			border: 1px solid <? echo $border_consultas ?>;
			background: <? echo $reporte_tabla; ?>;
		}
		
		.titulo_reporte_encuestas th{
			border: 1px solid <? echo $border_consultas; ?>;
		}
		
		.kardex  td, .kardex th{
			border: 1px solid <? echo $border_consultas; ?>;
		}
	
		.secciones{
			background: <? echo $seccion_color; ?>
			
		}
		#secciones{
			background: <? echo $seccion_color; ?>
			
		}
</style>
<?php 
$clave = $_SESSION['clave'];
$ciclo=$_SESSION['ciclo'];
$user=mysql_query("select * from usuarios_encabezados where empleado=$clave",$link)or die(mysql_error());
if (mysql_affected_rows($link)<=0 ){ mysql_close($link); die("No existe en usuarios"); }
$rowU = mysql_fetch_array($user);
$nivelUsuario=$rowU["nivel_preceptoria"];


/*$use=mysql_query("select * from usuarios_encabezados where usuario=$clave",$link)or die(mysql_error());
if (mysql_affected_rows($link)<=0 ){ mysql_close($link); die("No existe en usuarios"); }
$row = mysql_fetch_array($use);
$nivel=$row["nivel_preceptoria"];*/

$rst_ = mysql_query ("select * from usuarios_encabezados where usuario=$clave",$link) or die ("select * from usuarios_encabezados where usuario=$clave".mysql_error());
  while($rs_=mysql_fetch_array($rst_))
  { 
  	$nivel=$rs_["nivel_preceptoria"];
  }


$seccionU=$rowU["seccion_preceptoria"];
$seccion=""; if(!empty($_POST['seccion_'])) $seccion=$_POST["seccion_"];

if($nivelUsuario==12 || $nivelUsuario==13){
	if(empty($_POST['seccion_'])){ 
		
			$seccion="N";
			}
}	
$grado=0; if(!empty($_POST['grado_'])) $grado=$_POST["grado_"];
$grupo=""; if(!empty($_POST['grupo_'])) $grupo=$_POST["grupo_"];


/////
$seccionf=""; if(!empty($_POST['seccionf'])) $seccionf=$_POST["seccionf"];

if($nivelUsuario==12 || $nivelUsuario==13){
	if(empty($_POST['seccionf'])){ 
		
			$seccionf="N";
			}
}	
$gradof=0; if(!empty($_POST['gradof'])) $gradof=$_POST["gradof"];
$grupof=""; if(!empty($_POST['grupof'])) $grupof=$_POST["grupof"];
/////

$preceptor=0; if(!empty($_POST['preceptor'])) $preceptor=$_POST['preceptor'];

$ver_reporte='N';  if(!empty($_POST['ver_reporte'])) $ver_reporte=$_POST['ver_reporte']; 
$fecha_ini=""; if(!empty($_POST['fecha_ini'])) $fecha_ini=$_POST['fecha_ini']; 
$fecha_fin=""; if(!empty($_POST['fecha_fin'])) $fecha_fin=$_POST['fecha_fin'];
$alumno=0; if(!empty($_POST['alumno_'])) $alumno=$_POST['alumno_'];

/*echo" <script language='javascript'>alert('Seccion: $seccion Grado: $grado  Grupo:$grupo');</script>";*/

$pregunta1=0; if(!empty($_POST['pregunta1_'])) $pregunta1=$_POST['pregunta1_'];
$pregunta2=0; if(!empty($_POST['pregunta2_'])) $pregunta2=$_POST['pregunta2_'];

//$test=0; if(!empty($_POST['test_'])) $test=$_POST['test_'];

$test=0; if(!empty($_POST['test'])) $test=$_POST['test'];
$test_grup=$test;
$al_kardex=0; if(!empty($_POST['al_kardex'])) $al_kardex=$_POST['al_kardex'];
$cruce_Pregunta1=0; if(!empty($_POST['pregunta1'])) $cruce_Pregunta1=$_POST['pregunta1'];
$cruce_Pregunta2=0; if(!empty($_POST['pregunta2'])) $cruce_Pregunta2=$_POST['pregunta2'];
$tipo=""; if(!empty($_POST['tipo_'])) $tipo=$_POST['tipo_']; 
$val=" "; if(!empty($_POST['val'])) $val=$_POST['val'];
$imprimir_color=" "; if(!empty($_POST['imprimir_color'])) $imprimir_color=$_POST['imprimir_color'];

$mesM=" "; if(!empty($_POST['mesM'])) $mesM=$_POST['mesM'];

$totEntrevistas=0;
$nombre_test="";
$totalFinal=0;
$imprimirMes="";
$cnt=0;   
$and="";
if($seccion!="" and $seccionf!="" and $grado!=0 and $gradof!=0 and $grupo!="" and $grupof!=""){
	$and= "and concat(alumnos.seccion,' ',alumnos.grado,' ',alumnos.grupo) between '$seccion $grado $grupo' and '$seccionf $gradof $grupof' ";
}
if($seccion!="" and $seccionf!="" and $grado!=0 and $gradof!=0 and $grupo=="" and $grupof==""){
	$and="and concat(alumnos.seccion,' ',alumnos.grado) between '$seccion $grado' and '$seccionf $gradof' ";
}
if($seccion!="" and $seccionf!="" and $grado==0 and $gradof==0 and $grupo=="" and $grupof==""){
	if($seccion=='N')
		$a_="";
	else
	$and="and (alumnos.seccion between '$seccion' and '$seccionf' )";
}

/*if($seccion!="" and $seccionf!="") $and.=" and alumnos.seccion between '$seccion' and '$seccionf' ";
if($grado!=0 and $gradof!=0) $and.=" and alumnos.grado between $grado and $gradof ";
if($grupo!="" and $grupof!="") $and.=" and alumnos.grupo between '$grupo' and '$grupof' ";*/
if($preceptor!=0) $and.=" and alumnos.preceptor=$preceptor ";
if($alumno!=0) $and.=" and alumnos.alumno=$alumno ";

//Secci?n
    $secciones="Secci&oacute;n: <select onChange='submt(1)' style='font-size:8pt' name='seccion' id='seccion'>";
	$nu=$nivelUsuario;
	//if($nivelUsuario==13) $nivelUsuario=12;
	if($nu==13){
		$sql="SELECT distinct(secciones.nombre), secciones.seccion from secciones, usuarios_encabezados where (secciones.seccion=usuarios_encabezados.seccion or nivel_preceptoria <> 12) and empleado=$clave  and secciones.ciclo=$ciclo";
	}
	else
	{
		$sql=returnQuery($nivelUsuario,"seccion","","","",$seccionU,0,"");
	}
    $result=mysql_query($sql,$link)or die("$sql".mysql_error());
    if (mysql_affected_rows($link)>0) 
	{ if($nivelUsuario==11) $secciones.="<option value=''>Todos</option>";
	  if($nivelUsuario==12 || $nivelUsuario==13) $secciones.="<option value='N'></option>";
	  $desc_seccion="";
	  while($row=mysql_fetch_array($result))
	  { 	  	
	  	$selected=""; if($seccion==$row["seccion"]){ $selected="selected"; $desc_seccion=$row["nombre"]; }
	    $secciones.="<option $selected value='".$row["seccion"]."'>".$row["nombre"]."</option>";
	  }
	} $secciones.="</select><input type='hidden' name='seccion_' id='seccion_' value='$seccion'> &nbsp; &nbsp; &nbsp; ";

//Grado
	$grados="Grado: <select onChange='submt(2)' style='font-size:8pt;' name='grado' id='grado'>";
	if($nu==13)
	{	
		if($seccion!='N'){
			$grados.="<option value='0'>Todos</option>";
			$sql="SELECT distinct(grado) FROM grados where seccion=$seccion and grados.ciclo=$ciclo";
			}
	}
	else
	{
		$grados.="<option value='0'>Todos</option>";
		$sql=returnQuery($nivelUsuario,"grado",$seccion,"","",$seccionU,0,"");
	}
	
	//echo "sql_grados=$sql<br>";
    
	$result=mysql_query($sql,$link)or die("$sql".mysql_error());
    if (mysql_affected_rows($link)>0) 
	{ while($row=mysql_fetch_array($result))
	  { $selected=""; if($grado==$row["grado"]) $selected="selected"; 
	    $grados.="<option $selected value='".$row["grado"]."'>".$row["grado"]."</option>";
	  }
	} $grados.="</select><input type='hidden' name='grado_' id='grado_' value='$grado'> &nbsp; &nbsp; &nbsp; ";

//Grupo
	$grupos="Grupo: <select onChange='submt(3)' style='font-size:8pt;' name='grupo' id='grupo'>";
	if($nu==13)
	{	
		if($seccion!='N'){
		$grupos.="<option value=''>Todos</option>";
		$sql="SELECT * FROM grupos where seccion=$seccion and grado=$grado and ciclo=$ciclo";
		}
	}
	else
	{
		$grupos.="<option value=''>Todos</option>";
		$sql=returnQuery($nivelUsuario,"grupo",$seccion,$grado,"",$seccionU,0,"");
	}
	
	//echo "sql_grupos=$sql<br>";
    
	$result=mysql_query($sql,$link)or die("$sql".mysql_error());
    if (mysql_affected_rows($link)>0)
	{ while($row=mysql_fetch_array($result))
	  { $selected=""; if($grupo==$row["grupo"]) $selected="selected";
	    $grupos.="<option $selected value='".$row["grupo"]."'>".$row["grupo"]."</option>";
	  }
	} $grupos.="</select><input type='hidden' name='grupo_' id='grupo_' value='$grupo'> &nbsp; &nbsp; &nbsp; ";

//////////SECCION, GRADO, GRUPO FINAL
//Secci?n
    $seccionesf="Secci&oacute;n Final: <select onChange='parametros.submit();' style='font-size:8pt' name='seccionf' id='seccionf'>";
	$nu=$nivelUsuario;
	//if($nivelUsuario==13) $nivelUsuario=12;
	if($nu==13){
		$sql="SELECT distinct(secciones.nombre), secciones.seccion from secciones, usuarios_encabezados where (secciones.seccion=usuarios_encabezados.seccion or nivel_preceptoria <> 12) and empleado=$clave  and secciones.ciclo=$ciclo";
	}
	else
	{
		$sql=returnQuery($nivelUsuario,"seccion","","","",$seccionU,0,"");
	}
    $result=mysql_query($sql,$link)or die("$sql".mysql_error());
    if (mysql_affected_rows($link)>0) 
	{ if($nivelUsuario==11) $seccionesf.="<option value=''>Todos</option>";
	  if($nivelUsuario==12 || $nivelUsuario==13) $seccionesf.="<option value='N'></option>";
	  $desc_seccion="";
	  while($row=mysql_fetch_array($result))
	  { 	  	
	  	$selected=""; if($seccionf==$row["seccion"]){ $selected="selected"; $desc_seccion=$row["nombre"]; }
	    $seccionesf.="<option $selected value='".$row["seccion"]."'>".$row["nombre"]."</option>";
	  }
	} $seccionesf.="</select><input type='hidden' name='seccionf_' id='seccionf_' value='$seccionf'> &nbsp; &nbsp; &nbsp; ";

//Grado
	$gradosf="Grado: <select onChange='parametros.submit();' style='font-size:8pt;' name='gradof' id='gradof'>";
	if($nu==13)
	{	
		if($seccionf!='N'){
			$gradosf.="<option value='0'>Todos</option>";
			$sql="SELECT distinct(grado) FROM grados where seccion=$seccionf and grados.ciclo=$ciclo";
			}
	}
	else
	{
		$gradosf.="<option value='0'>Todos</option>";
		$sql=returnQuery($nivelUsuario,"grado",$seccionf,"","",$seccionU,0,"");
	}
	
	//echo "sql_grados=$sql<br>";
    
	$result=mysql_query($sql,$link)or die("$sql".mysql_error());
    if (mysql_affected_rows($link)>0) 
	{ while($row=mysql_fetch_array($result))
	  { $selected=""; if($gradof==$row["grado"]) $selected="selected"; 
	    $gradosf.="<option $selected value='".$row["grado"]."'>".$row["grado"]."</option>";
	  }
	} $gradosf.="</select><input type='hidden' name='gradof_' id='gradof_' value='$gradof'> &nbsp; &nbsp; &nbsp; ";

//Grupo
	$gruposf="Grupo: <select onChange='parametros.submit();' style='font-size:8pt;' name='grupof' id='grupof'>";
	if($nu==13)
	{	
		if($seccionf!='N'){
		$gruposf.="<option value=''>Todos</option>";
		$sql="SELECT * FROM grupos where seccion=$seccionf and grado=$gradof and ciclo=$ciclo";
		}
	}
	else
	{
		$gruposf.="<option value=''>Todos</option>";
		$sql=returnQuery($nivelUsuario,"grupo",$seccionf,$gradof,"",$seccionU,0,"");
	}
	
	//echo "sql_grupos=$sql<br>";
    
	$result=mysql_query($sql,$link)or die("$sql".mysql_error());
    if (mysql_affected_rows($link)>0)
	{ while($row=mysql_fetch_array($result))
	  { $selected=""; if($grupof==$row["grupo"]) $selected="selected";
	    $gruposf.="<option $selected value='".$row["grupo"]."'>".$row["grupo"]."</option>";
	  }
	} $gruposf.="</select><input type='hidden' name='grupof_' id='grupof_' value='$grupof'> &nbsp; &nbsp; &nbsp; ";
///////////////////


//Preceptor
	$preceptores="Preceptor: <select OnChange='parametros.submit();' name='preceptor' id='preceptor' >";	
	if($nu==13)
	{	
		mysql_query("SET NAMES 'utf8'");
		$rs_=mysql_query("SELECT empleado, nombre, apellido_paterno, apellido_materno FROM personal where empleado=$clave order by apellido_paterno, apellido_MATERNO, nombre",$link) or die (mysql_error());
	}
	else
	{
	$preceptores.="<option value=0>Todos</option>";
	$rs_=mysql_query("SELECT empleado, nombre, apellido_paterno, apellido_materno FROM personal where preceptor='S' order by apellido_paterno, apellido_MATERNO, nombre",$link) or die (mysql_error());
	}
    if (mysql_affected_rows($link)>0) 
	{ while($row=mysql_fetch_array($rs_))
	  { $selected=""; if($preceptor==$row["empleado"]) $selected="selected";
	    $preceptores.="<option $selected value='".$row["empleado"]."'>".$row["apellido_paterno"]." ".$row["apellido_materno"]." ".$row["nombre"]."</option>";
	  }
	} $preceptores.="</select><input type='hidden' name='preceptor_' id='preceptor_' value='$preceptor'> &nbsp; &nbsp; &nbsp; ";


//Alumnos por preceptor
    $result=mysql_query("Select * from alumnos where activo='A' and preceptor=$preceptor order by apellido_paterno, apellido_materno, nombre",$link)or die("Select * from alumnos where activo='A' and preceptor=$preceptor order by apellido_paterno, apellido_materno, nombre".mysql_error());
    $alms_prcptr="Alumno: <select onChange='submt(5)' style='font-size:8pt;' name='alumno' id='alumno'><option value=0>Todos</option>";
	if (mysql_affected_rows($link)>0) 
	{ while($row=mysql_fetch_array($result))
	  { $selected=""; if($alumno==$row["alumno"]) $selected="selected";
	    $alms_prcptr.="<option $selected value='".$row['alumno']."'>".$row["apellido_paterno"]." ".$row["apellido_materno"]." ".$row["nombre"]."</option>";
	  } 
	} $alms_prcptr.="</select><input type='hidden' name='alumno_' id='alumno_' value='$alumno'> &nbsp; &nbsp; &nbsp; ";


//Busca alumnos
$busca_alumnos="Alumno: <input name='val' id='val'><input type='submit' value='Buscar'><select style='font-size:8pt;' onChange='alumno_.value=this.value'><option value=''>Todos</option>";
$sede=mysql_result(mysql_query("select sede from parametros",$link),0,0); 
$r=mysql_query("select * from alumnos where plantel=$sede and CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) like '%$val%' order by apellido_paterno,apellido_materno,nombre",$link)or die("select * from alumnos where plantel=$sede and CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) like '%$val%' order by apellido_paterno,apellido_materno,nombre".mysql_error());
if(mysql_affected_rows($link)>0)
{ while($a=mysql_fetch_array($r))
  { $selected=""; if($alumno==$row["alumno"]) $selected="selected";
    $busca_alumnos.="<option $selected value='".$a[0]."'>".$a[0]."-".$a[22]." ".$a[23]." ".$a[1]."</option>";
  } $busca_alumnos.="</select><input type='hidden' name='alumno_' id='alumno_' value='$alumno'> &nbsp; &nbsp; &nbsp; ";
}


//Test
$tests="Test: <select OnChange='parametros.submit();' name='test' id='test'>";
$rs_=mysql_query("SELECT id_test, nombre FROM test order by nombre",$link) or die (mysql_error());
if(mysql_affected_rows($link)>0)
{ while($row=mysql_fetch_array($rs_))
  { $nombreTest=""; $selected=""; if($test==$row["id_test"]){ $selected="selected"; $nombreTest=$row['id_test']; }
    $tests.="<option $selected value='".$row['id_test']."'>".$row['nombre']."</option>";
  } $tests.="</select><input type='hidden' name='test_' id='test_' value='$test'> &nbsp; &nbsp; &nbsp; ";
}


//Preguntas por test1
    $result=mysql_query("SELECT id_pregunta, pregunta FROM test_preguntas, test where test.id_test=test_preguntas.id_test
								and test_preguntas.id_test = $test order by id_pregunta",$link)or die("SELECT id_pregunta, pregunta FROM test_preguntas, test where test.id_test=test_preguntas.id_test
								and test_preguntas.id_test = $test order by id_pregunta".mysql_error());
    $preg_test1="Pregunta 1: <select style='font-size:8pt;' name='pregunta1' id='pregunta1'><option value=0>Todos</option>";
	if (mysql_affected_rows($link)>0) 
	{ while($row=mysql_fetch_array($result))
	  { $selected=""; if($cruce_Pregunta1==$row["id_pregunta"]) $selected="selected";
	    $preg_test1.="<option $selected value='".$row['id_pregunta']."'>".$row['pregunta']."</option>";
	  } 
	} $preg_test1.="</select>";//<input type='hidden' name='pregunta1_' id='pregunta1_' value='$cruce_Pregunta1'> &nbsp; &nbsp; &nbsp; ";
	
	
//Preguntas por test2
    $result=mysql_query("SELECT id_pregunta, pregunta FROM test_preguntas, test where test.id_test=test_preguntas.id_test
								and test_preguntas.id_test = $test order by id_pregunta",$link)or die("SELECT id_pregunta, pregunta FROM test_preguntas, test where test.id_test=test_preguntas.id_test
								and test_preguntas.id_test = $test order by id_pregunta".mysql_error());
    $preg_test2="Pregunta 2: <select style='font-size:8pt;' name='pregunta2' id='pregunta2'><option value=0>Todos</option>";
	if (mysql_affected_rows($link)>0) 
	{ while($row=mysql_fetch_array($result))
	  { $selected=""; if($cruce_Pregunta2==$row["id_pregunta"]) $selected="selected='selected'";
	    $preg_test2.="<option $selected value='".$row['id_pregunta']."'>".$row['pregunta']."</option>";
	  } 
	} $preg_test2.="</select>";//<input type='hidden' name='pregunta2_' id='pregunta2_' value='$cruce_Pregunta2'> &nbsp; &nbsp; &nbsp; ";
	

	 ?>
     
	 

<form name="parametros" id="parametros" method="post" action="reportes.php">
<div>
<input type="hidden" name="ver_reporte" id="ver_reporte" value="<?=$ver_reporte;?>" />
<table class='menus' align="center">
<tr align="left">
<th width="586">Tipo de Reporte: <select name="tipo" id="tipo" onChange="tipo_.value=this.value; document.getElementById('parametros').submit();">

        <option selected="selected" value="-"> </option>
		<option value="entrevistasp">Total Entrevistas de Padres de Familia</option>		
		<?
		//if($nivel=='13'){
		if($nivel<>'13'){
		?>
			<option value="encuestas">Encuestas</option>
		<?
		}
		?>
		<option value="preceptoriasA">Preceptorias por Preceptor</option>
		<option value="preceptoriasG">Preceptorias por Grupo</option>
		<option value="entrevistas">Entrevistas con padres de familia</option>
		<option value="acuerdos">Acuerdos con Padres</option>
        <option value="preceptorias">Preceptorias Realizadas</option>
        <!--<option value="test">Test</option>-->
        <!--<option value="e_Detallado">Detallado de Tests </option>-->
        
		<!--<option value="test_comentarios_alumno">Comentarios por Test por Alumno</option>-->
		<!--<option value="e_Porcentaje">Porcentaje de Test</option>-->
		<!--<option value="grupal">Promedio Grupal por Test</option>-->
		
		<!-- <option value="test_comentarios">Comentarios por Test</option>
        <option value="preguntas">Cruce de Preguntas</option> -->
		<option value="kardex">Kardex</option>	
		
      </select><input type='hidden' name='tipo_' id='tipo_' value='<?=$tipo?>'>  <br />
	  

<?
	  if($tipo!='kardex' && $tipo!= 'preceptoriasG')
{
?>
  Inicio <input size='14' name='fecha_ini' id='fecha_ini' value='<?=$fecha_ini;?>' readonly="readonly">


	&nbsp;&nbsp;&nbsp;
   Fin:   <input size='14' name='fecha_fin' id='fecha_fin' value='<?=$fecha_fin;?>' readonly="readonly">


    &nbsp;&nbsp;&nbsp;

<?
}
   
   if($tipo== 'preceptoriasG'){?>
   	Mes inicial: <input size='14' name='fecha_ini' id='fecha_ini' value='<?=$fecha_ini;?>' readonly="readonly">

     &nbsp;&nbsp;&nbsp;
	Mes final:<input size='14' name='fecha_fin' id='fecha_fin' value='<?=$fecha_fin;?>' readonly="readonly">


     &nbsp;&nbsp;&nbsp;
   	
 <?
 }
 ?>

</th>
</tr>
<tr align="left">
<th>
<div id='imagen' style="display:none;"> <center><img src='images/loading.gif'> <b>Procesando...</b></center> </div>		

	 <? 
	 $loading="";
	 $loading="<center><img src='images/loading.gif'> <b>Procesando...</b></center> ";		
		
			if($tipo!='test_comentarios_alumno' && $tipo!= 'kardex' && $tipo!='grupal' && $tipo!='preceptoriasG' && $tipo!='encuestas'){ echo $secciones; $cnt=1;}
			if($tipo!='test_comentarios_alumno' && $tipo!= 'kardex' && $tipo!='grupal' && $tipo!='preceptoriasG' && $tipo!='encuestas'){ echo"$grados $grupos"; $cnt=4;	}		
			if($tipo!='test_comentarios_alumno' && $tipo!= 'kardex' && $tipo!='grupal' && $tipo!='preceptoriasG' && $tipo!='encuestas'){ echo '<br>'.$seccionesf; }
			if($tipo!='test_comentarios_alumno' && $tipo!= 'kardex' && $tipo!='grupal' && $tipo!='preceptoriasG' && $tipo!='encuestas'){ echo"$gradosf $gruposf"; }
			
			if($tipo=='grupal' || $tipo=='preceptoriasG'	)
			{
				echo $secciones; $cnt=1;	}
			
					
			if($tipo=='grupal' || $tipo=='preceptoriasG'	)
			{
				echo"$grados $grupos"; $cnt=3;	}
				
			if($tipo=='grupal' || $tipo=='preceptoriasG'	)
			{
				echo '<br>'.$seccionesf;	}
				
			if($tipo=='grupal' || $tipo=='preceptoriasG'	)
			{
				echo"$gradosf $gruposf"; }
			
			if($tipo!='grupal' && $tipo!= 'kardex' && $tipo!= 'preceptoriasG' && $tipo!='encuestas') {echo "<br>$preceptores";	}
			if($tipo=='acuerdos'){ echo $alms_prcptr; $cnt=5;	}	
			if($tipo=='preceptoriasA'){ echo $alms_prcptr; $cnt=5;	}	
			if($tipo=='test_comentarios_alumno'){ echo $alms_prcptr; $cnt=5;	}
			if($tipo=='e_Detallado' || $tipo=='e_Porcentaje' || $tipo=='grupal' || $tipo=='test_comentarios_alumno' || $tipo=='preguntas'){ echo"<br> $tests ";}
		
			if($tipo=='preguntas') {
				echo "<p><br>ELIGE LAS PREGUNTAS DEL TEST:<br>$preg_test1 <br></p>";
				echo $preg_test2;	}	
		
		
			if($tipo=='kardex') 
				{ ?>
				<select name="al_kardex" id="al_kardex" >
			      <? 
			$rs_ = mysql_query ( "SELECT alumno, concat(apellido_paterno,' ',apellido_materno,' ',nombre) as nombre FROM alumnos  order by nombre", $link ) or die ( mysql_error () );
		while ( $r_ = mysql_fetch_array ( $rs_ ) ) { 
		    $selected="";
			if ($r_ ['alumno'] == $al_kardex ){ $selected="selected='selected'"; }
			echo "<option $selected value='" . $r_ ['alumno'] . "'>" . $r_ ['nombre'] . "</option>";
			}
			
?>
		        
                </select>
<?
		}
		
		
		?>

	 </th></tr>
     
<tr align="left"><th><input type="button" value="Ver Reporte" onclick="document.getElementById('ver_reporte').value='S'; parametros.submit();" />  
  </th></tr>
</table>

</form>

<script language="javascript" type="text/javascript"><!--
 document.getElementById('tipo').value='<?=$tipo?>';
function abrecalendario(field)
{ window.open('calendar.php?campo='+field, 'calendar', 'width=400,height=300,status=yes'); }
function submt(nivel)
{ var campo; <?
 /* if($tipo=='e_Detallado' || $tipo=='e_Porcentaje'){ echo" document.getElementById('seccion_').value=document.getElementById('seccion').value;
  if(campo==1) document.getElementById('preceptor_').value=''; 
  else document.getElementById('preceptor_').value=document.getElementById('preceptor').value; "; } else { */
  
  ?>
  for(x=nivel;x<=<?=$cnt;?>;x++)
  { switch(x)
    { case 1: campo="seccion"; break;
	  case 2: campo="grado"; break;
	  case 3: campo="grupo"; break;
	  case 4: campo="preceptor"; break;
	  case 5: campo="alumno"; break;
	  case 6: campo="pregunta1"; break;
	  case 7: campo="seccionf"; break;
	  case 8: campo="gradof"; break;
	  case 9: campo="grupof"; break;
	} 
	if(nivel!=x) document.getElementById(campo+"_").value='';
	else if(document.all(campo)!=null){ document.getElementById(campo+"_").value=document.getElementById(campo).value; }
  } <? /*}*/ ?>
  document.getElementById('parametros').submit();  
  
}


function imprSelec(nombre)
{ var ficha = document.getElementById(nombre);
  var ventimp = window.open(' ', 'popimpr');
  ventimp.document.write( ficha.innerHTML );
  ventimp.document.close();
  ventimp.print( );
  ventimp.close();
} 

-->
</script>



<?


//PRECEPTORIAS REALIZADAS!!!!!!

if($tipo=='preceptorias')
{

$exportar="<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<table class='reporte' width='600' border='1'>
		<tr>
			<th style='font-size:24px' colspan='100%'> Reporte Preceptor&iacute;as Realizadas </th>

		</tr>";
		
$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);
switch($mes_ini)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);
switch($mes_fin)
{
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}

$exportar.="<tr>
				<td colspan='100%'><b>Rango de Fechas:</b> Del ".$dia_ini." de ".$pmes." de ".$y_ini." al ".$dia_fin." de ".$smes." de ".$y_fin." </td>
			</tr>";


if($ver_reporte=='S'){
/*$rst_ = mysql_query ("SELECT fecha, preceptor, familia, seccion, grado, grupo, nombre, familia, alumnos.alumno FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno $and group by fecha, alumno order by seccion, grado, grupo, familia",$link) or die ("SELECT fecha, preceptor, familia, seccion, grado, grupo, nombre, familia, alumnos.alumno FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno $and group by fecha, alumno order by seccion, grado, grupo, familia".mysql_error());*/
/*$rst_ = mysql_query ("SELECT fecha, preceptor, familia, seccion, grado, grupo, nombre, familia, alumnos.alumno FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno $and group by preceptoria, alumno order by seccion, grado, grupo, familia",$link) or die ("SELECT fecha, preceptor, familia, seccion, grado, grupo, nombre, familia, alumnos.alumno FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno $and group by preceptoria, alumno order by seccion, grado, grupo, familia".mysql_error());*/

$rst_ = mysql_query ("SELECT fecha, preceptor, familia, seccion, grado, grupo, nombre, familia, alumnos.alumno FROM preceptoria,alumnos where Convert(fecha, Char(10)) between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno $and group by preceptoria, alumno order by seccion, grado, grupo, familia",$link) or die ("SELECT fecha, preceptor, familia, seccion, grado, grupo, nombre, familia, alumnos.alumno FROM preceptoria,alumnos where Convert(fecha, Char(10)) between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno $and group by preceptoria, alumno order by seccion, grado, grupo, familia".mysql_error());

$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$grado_anterior=0;
$grupo_anterior="";
$c = 0;
$d = 0;
$f = 0;
$g=0;

  while($rs_=mysql_fetch_array($rst_)){
  $c = $c + 1;
  $d= $d+1;
  $f = $f + 1;
  if($c == 1){
    /*if($rs_['preceptor']!=$preceptor_anterior)
    {*/ 

	if($preceptor == 0){

		$exportar.="<tr><td colspan='100%'><b>Preceptor: </b>Todos</td></tr>"; 							

		}
	else
		{	
		$p=$rs_['preceptor'];
		$exportar.="<tr>
						<td colspan='100%' style='background: ".$border_consultas."; color: #fff; border: 1px solid ".$border_consultas.";'><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado= $p ",$link),0,0)."</td>
					</tr>"; 
		}
	
	/*$prec= $rs_['preceptor'];
	$exportar.="<tr><th colspan=4>Preceptor: ".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$prec ",$link),0,0)."</th></tr><tr>&nbsp;</tr>"; */	
	}
	$grupo_anterior=$seccion_anterior.' '.$grado_anterior.' '.$grupo_anterior;
	$grupo_actual=$rs_['seccion'].' '.$rs_['grado'].' '.$rs_['grupo'];
	$s=$rs_['seccion'];


	if($grupo_actual!=$grupo_anterior)
	{
		
		if($seccion == 0 and $c==1){
			//$exportar.="<tr><td colspan='100%'><b>Secci&oacute;n:  Todas</b> "; 
			$exportar.="
	<tr>
		<td colspan='100%' ><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
		}
		else
		{
			if($c==1)
			{

				$exportar.="<tr><td colspan='100%' class='secciones'><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
			}
			else
			{
				if($grado!=g){

					$exportar.="<tr><td colspan='100%' class='secciones'><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
				}
				else
				{
					if($seccion_anterior!=$rs_['seccion'])
					{

						$exportar.="<tr><td colspan='100%' class='secciones'><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
					}
				}
				
			}
		}	
			
if($grado!=$g)
{
	if($grado == 0){
		$exportar.="<b>  Grado: </b> Todos  "; 	}
	else
		{
		$exportar.="<b>  Grado: </b>".$rs_['grado']."  "; 	}
}
else
{
	if($c==1 and $grado==0)
		$exportar.="<b>  Grado: </b> Todos  ";

}

$g=grado;
if($grado!=0)
{
	if($grupo == ''){
		$exportar.="<b>  Grupo: </b> Todos</td></tr>"; 	}
	else
		{
		$exportar.="<b>  Grupo: </b>".$rs_['grupo']."</td></tr>"; 	}
	
	}

}
$gp=grupo;	
//	}
    	
    //if($rs_['fecha']!=$fecha_anterior)
   //{ 
     
	  $totEntrevistas= $totEntrevistas + 1;

	
	if($c == 1){
	  $exportar.=
	  " <tr>
	  		<td><b>ID</b></td>
			<td><b>Alumno</b></td>
			<td><b>Fechas de preceptorias</b></td>
			<td><b>Preceptorias</b></td>
		</tr>	
		";
		}
		
		
	  if($rs_['familia']!=$familia_anterior)
   
	{ $exportar.="
	<tr>
		<td>".mysql_result(mysql_query("select alumno from alumnos where familia=".$rs_['familia'],$link),0,0)." </td>
		<td>".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)." ";
	
	$exportar.=" ".$rs_['nombre']."</td><td > ";	}

/*	
		 if($f == 6)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
		 
		 if($f==12)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
		 
		 if($f==18)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
		 
		 if($f==24)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
		 
		 if($f==30)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
		 
		 if($f==36)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
	 
		 if($f==42)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
			
		 if($f==48)
		 {	$exportar.="<tr></tr><td></td><td></td><td></td>";	}
*/		 
	$fec_comp = $rs_['fecha'];
	$fecha_extra = substr($fec_comp, 0, 10);
	
	$exportar.=" ".formatDate($fecha_extra)." ";	
		 $nom=$rs_['alumno']; 

	$rstF_ = mysql_query ("SELECT count(distinct (fecha)) as tot FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and activo='A' and alumnos.alumno =preceptoria.alumno and alumnos.alumno=$nom ",$link) or die ("SELECT count(distinct (fecha)) FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno and alumnos.alumno=$nom ".mysql_error());
	while($rsF_=mysql_fetch_array($rstF_))
	  {
		$tot=$rsF_['tot'];

	  }
		
		if($d==$tot)		 
		{
			$exportar.= "</td><td><b>Total: ".$tot." </b></td></tr>
						"; 


			$d=0;
		}
//	}

$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['nombre'];
$fecha_anterior=$rs_['fecha']; 
$seccion_anterior=$rs_['seccion'];
$grado_anterior=$rs_['grado'];  
$grupo_anterior=$rs_['grupo'];
  } 
 
 

$totalFinal= $totalFinal + $totEntrevistas;
//  $exportar.= "<td>&nbsp;</td><td width='121'> ".$totEntrevistas." </td>"; 

  $exportar.= "<tr>
		<td> </td></tr>
  		<td colspan='100%' align='right'><b>Preceptorias:  ".$totalFinal." </b></td></tr>"; 
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
  echo"";
 $ver_reporte='N';  
 $seccion="";
 $grado= 0;
 $grupo= "";
 $preceptor= 0;
} 

echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}


//ACUERDOS!!!!!!

if($tipo=='acuerdos')
{

//$totEntrevistas=0; //if(!empty($_POST['totEntrevistas'])) $totEntrevistas=$_POST['totEntrevistas']; 

$exportar="<table width='600' class='reporte'>
				<tr>
					<th style='font-size:24px' colspan='100%'> Reporte Acuerdos con Padres </th>
				</tr>";



$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);
switch($mes_ini)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);
switch($mes_fin)
{
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}


$exportar.="
	<tr>
		<td colspan='100%'><b>Rango de Fechas:</b> Del ".$dia_ini."-".$pmes."-".$y_ini." al ".$dia_fin."-".$smes."-".$y_fin." </td>
	</tr>
	<tr>
		<td> </td>
	</tr>";


if($ver_reporte=='S')
{ $rst_ = mysql_query ("SELECT DISTINCT alumnos.preceptor, alumnos.grado,alumnos.grupo,alumnos.seccion,alumnos.familia,alumnos.alumno,acuerdo, fec FROM preceptoria_acuerdos,alumnos, areas_valor where fec between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria_acuerdos.alumno $and order by seccion, grado, grupo, preceptor,familia,fec ",$link) or die ("SELECT * FROM preceptoria_acuerdos,alumnos where fec between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria_acuerdos.alumno $and order by seccion, grado, grupo, preceptor,familia,fec ".mysql_error());
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$grado_anterior=0;
$grupo_anterior="";
$areas_anterior="";
$c = 0;
$g=0;
  while($rs_=mysql_fetch_array($rst_))
  { 
  
  $c = $c + 1;
  
    if($rs_['preceptor']!=$preceptor_anterior)
    { 
	$prec= $rs_['preceptor'];
	$exportar.="<tr><td width='1200' colspan='100%'><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$prec ",$link),0,0)."</td></tr>"; 
	}
	
	
	$grupo_anterior=$seccion_anterior.' '.$grado_anterior.' '.$grupo_anterior;
	$grupo_actual=$rs_['seccion'].' '.$rs_['grado'].' '.$rs_['grupo'];
	$s=$rs_['seccion'];
	if($grupo_actual!=$grupo_anterior)
	{
		
		if($seccion == 0 and $c==1){
			
			$exportar.="<tr><td colspan='100%'><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
		}
		else
		{
			if($c==1)
			{
				$exportar.="<tr><td colspan='100%'><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
			}
			else
			{
				if($grado!=g){
					$exportar.="<tr><td colspan='100%'><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
				}
				else
				{
					if($seccion_anterior!=$rs_['seccion'])
					{
						$exportar.="<tr><td colspan='100%'><b>Secci&oacute;n:  </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)." "; 
					}
				}
				
			}
		}	
			
if($grado!=$g)
{
	if($grado == 0){
		$exportar.="<b>  Grado: </b> Todos  "; 	}
	else
		{
		$exportar.="<b>  Grado: </b>".$rs_['grado']."  "; 	}
}
else
{
	if($c==1 and $grado==0)
		$exportar.="<b>  Grado: </b> Todos  ";

}

$g=grado;
if($grado!=0)
{
	if($grupo == ''){
		$exportar.="<b>  Grupo: </b> Todos</td></tr>"; 	}
	else
		{
		$exportar.="<b>  Grupo: </b>".$rs_['grupo']."</td></tr>"; 	}
	
	}
}
$gp=grupo;	
	
	
	/*
	if($rs_['seccion']!=$seccion_anterior)
	{
	
		if($seccion == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n: Todas </b>   "; 
		}
		else
		
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."   "; 
		}	
		
		if($grado == 0){
		$exportar.="<b> Grado: </b>Todos    "; 
		}
	else
		{
		
		$exportar.="<b> Grado: </b>".$rs_['grado']."    "; 
		}
	if($grupo == ''){
		
		$exportar.="<b> Grupo: </b> Todos</td></tr><tr><td> </td></tr>"; 
		}
	else
		{
		$exportar.="<b> Grupo: </b>".$rs_['grupo']."</td></tr><tr><td> </td></tr><tr><td> </td></tr>"; 
		}
	}*/
		
	
		
	
	
	/*if($rs_['seccion']!=$seccion_anterior)
	{
	$exportar.="<tr><td><b>Secci?n: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."</td></tr><tr><td>&nbsp;</td></tr>"; 
	}
    /*if($rs_['familia']!=$familia_anterior)
    { $exportar.="<td>&nbsp;</td><th colspan=2>Alumno: ".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)." ";
	}
	if($rs_['nombre']!=$alumno_anterior)
    { $exportar.=" " .$rs_['nombre']."</th>";
	}*/
	
	
  //  if($rs_['fec']!=$fecha_anterior)
   //{ 

   $fecha= $rs_['fec'];
  
   $mes= substr($fecha, 5, 2);
 
switch($mes)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

   $imprimirMes.= $nmes;
   
	  $totEntrevistas= $totEntrevistas + 1;
//	  $exportar.="<tr>&nbsp;</tr> <tr>&nbsp;</tr> <tr>&nbsp;</tr>";
	
	if($c == 1){
	  $exportar.=
	  " <tr>	  	

		<td width='50'><b></b></td>
		<td> </td>
		<td width='50'><b>Grado</b></td> 
		<td> </td>
		<td width='50'><b>Fecha Acuerdo</b></td> 				
		<td> </td>
		<td> </td> 
		<td width='200'><b>Area</b></td>
		<td></td>
		<td width='1200'><b>Acuerdo</b></td>	
		
		</tr>";
		}		
		
	
	  if($rs_['familia']!=$familia_anterior)
    { 
		$fam = mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0);
		$exportar.="<tr><td colspan='100%'><b>Familia: </b>".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)."</td></tr>"; 
	}
	else
	{
		$fam = "";
	}
	
	
	if($rs_['alumno']!=$alumno_anterior)
    { 
		$alum= "Alumno: </b>".mysql_result(mysql_query("select nombre from alumnos where alumno=".$rs_['alumno'],$link),0,0);
	}
	else
	{
		$alum = "";
	}
				
		
	if($rs_['grado']!=$grado_anterior)
	{ 	
		$grad = mysql_result(mysql_query("select grado from grados where grado=".$rs_['grado'],$link),0,0)." ".$rs_['grupo'];	
	}
	else
	{
		$grad= "";
	}
	
	if($rs_['fec']!=$fecha_anterior)
	{ 
		$fec_comp = $rs_['fec'];
		$fecha_extra = substr($fec_comp, 0, 10);
		$fech= formatDate($fecha_extra);	
	}
	else
	{
		$fech="";
	}
		
/*		
	if($rs_['id_area']!=$areas_anterior)
    { 
		
		$areaa= mysql_result(mysql_query("select nombre from areas_valor where id_area_valor=".$rs_['id_area'],$link),0,0);
	}
	else
	{
		$areaa  IS NULL;
	}
*/	
	$acuerdo= $rs_['acuerdo'];
	$exportar.=
	  " <tr>	  	
		<td><b>$alum</b></td>
		<td> </td>
		<td>$grad</td> 
		<td> </td>
		<td>$fech</td> 				
		<td> </td>
		<td> </td> 
		<td>$areaa</td>
		<td> </td>
		<td>$acuerdo</td>	
		
		</tr>	
		";
		
	
		 
	//}

$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['alumno'];
$fecha_anterior=$rs_['fec']; 
$seccion_anterior=$rs_['seccion'];
$grado_anterior=$rs_['grado'];
$grupo_anterior=$rs_['grupo'];
//$areas_anterior=$rs_['id_area'];

  } 
  
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N';
} 

echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
 
}




//PORCENTAJE TEST!!!!!!

if($tipo=='e_Porcentaje')
{
$idT= $nombre_test; 
//$idTest=0; if($test!=0) $idTest="and test_publicacion.id_test = $test";
//$idTest=""; if($test!=0) 

$idTest="and test_publicacion.id_test = $test";
$rstT_ = mysql_query ("
SELECT count(id_estatus) as total FROM test_estatus, alumnos, test_publicacion
where test_estatus.responde = alumnos.alumno
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin'
$and and test_estatus.id_publicacion = test_publicacion.id_publicacion $idTest
",$link) 
		or die ("
SELECT count(id_estatus) as total FROM test_estatus, alumnos, test_publicacion
where test_estatus.responde = alumnos.alumno
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin'
$and and test_estatus.id_publicacion = test_publicacion.id_publicacion $idTest".mysql_error());
while($rsT_=mysql_fetch_array($rstT_))
  {   
  	
    $totalPreguntas=0;
	$totalPreguntas= $rsT_['total'];
	}

$exportar="<table><tr><th style='font-size:24px' colspan=3> Reporte Porcentaje de Test </th></tr>
</td></tr>";

$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);
switch($mes_ini)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);
switch($mes_fin)
{
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}


$exportar.="<tr><td colspan=3><b>Rango de Fechas:</b> Del ".$dia_ini."-".$pmes."-".$y_ini." al ".$dia_fin."-".$smes."-".$y_fin." </td></tr>";


if($ver_reporte=='S')
{ 
$idtest_=""; if($test!=0) $idtest_="and test.id_test = $test"; 
	$rst_ = mysql_query ("SELECT pregunta, opcion, tipo, seccion, grado, grupo, preceptor, fechaterminado, alumnos.nombre, alumno, familia, respuesta FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and  $idtest_ order by test_preguntas.orden,
test_opciones.opcion, test_preguntas.tipo, alumnos.alumno",$link) 
		or die ("SELECT pregunta, opcion, tipo, seccion, grado, grupo, preceptor, fechaterminado, alumnos.nombre, alumno, familia, respuesta FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and  $idtest_ order by test_preguntas.orden,
test_opciones.opcion, test_preguntas.tipo, alumnos.alumno".mysql_error());
		
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$grado_anterior=0;
$grupo_anterior="";
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";

$g=0;
$c = 0;


  while($rs_=mysql_fetch_array($rst_))
  { 
  	$c = $c + 1;
 	$preceptor_=$rs_['preceptor'];	
	if($c == 1){	
				
				$exportar.="<tr><td colspan=3><b>Nombre de Test: </b>".mysql_result(mysql_query("select nombre from test where id_test=$test ",$link),0,0)."</td></tr><tr> </tr>"; 
			
	
		if($preceptor == 0){
		$exportar.="<tr><td colspan=3><b>Preceptor: Todos</b></td></tr>"; 
		}
		else
		{	$exportar.="<tr><td colspan=3><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$preceptor_ ",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 	}
	}

		
	if($rs_['seccion']!=$seccion_anterior and $c==1)

	{	if($seccion == 0){ $exportar.="<tr><td colspan=3><b>Secci&oacute;n Inicial: Todas </b> "; }
		else

		{ $exportar.="<tr><td colspan=3><b>Secci&oacute;n Inicial: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)." "; 	}	
		if($grado == 0){	$exportar.="<b>Grado: </b>Todos  "; }
	else
		{
		$exportar.="<b>  Grado: </b>".$rs_['grado']."  "; 
		}
	if($grupo == ''){
		$exportar.="<b>  Grupo: </b> Todos</td></tr><tr><td> </td></tr><tr><td> </td></tr>"; 


		}
	else
		{
		$exportar.="<b>  Grupo: </b>".$rs_['grupo']."</td></tr><tr><td> </td></tr><tr><td> </td></tr>"; 


		}		
		
		//////////
		
		if($seccionf == 0){ $exportar.="<tr><td colspan=3><b>Secci&oacute;n Final: Todas </b> "; }
		else
		{ $exportar.="<tr><td colspan=3><b>Secci&oacute;n Final: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccionf ",$link),0,0)." "; 	}	
		if($grado == 0){	$exportar.="<b>Grado: </b>Todos  "; }
	else
		{
		$exportar.="<b>  Grado: </b>".$gradof."  "; 
		}
	if($grupo == ''){
		$exportar.="<b>  Grupo: </b> Todos</td></tr><tr><td> </td></tr><tr><td> </td></tr>"; 


		}
	else
		{
		$exportar.="<b>  Grupo: </b>".$grupof."</td></tr><tr><td> </td></tr><tr><td> </td></tr>"; 


		}
		
	}

  	 	$fecha= $rs_['fechaterminado'];  
   	 	$mes= substr($fecha, 5, 2);
 
	    switch($mes)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

	   	$imprimirMes.= $nmes;
/*
	 	 if($rs_['familia']!=$familia_anterior)
    	{ 
			$exportar.="<td>&nbsp;</td><td colspan=2>".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)." ";
		}
		if($rs_['nombre']!=$alumno_anterior)
    	{ 
			$exportar.=" " .$rs_['nombre']."</td>";
		}	*/
		
			
		if($rs_['pregunta']!=$pregunta_anterior)
		{					
		

			$exportar.="<tr><th>Pregunta: ".$rs_['pregunta']."</th></tr><tr></tr>";



			$exportar.="<tr><td><b>Opci&oacute;n</b></td><td><b>N&uacute;mero</b></td><td><b>Porcentaje</b></td></tr><tr> </tr>";

			
			$preguntaO=$rs_['pregunta'];
			$rstO_ = mysql_query ("SELECT test_preguntas.id_pregunta as idpregunta, pregunta, test_opciones.id_opcion as idOpcion, opcion FROM test_preguntas,test_opciones where test_preguntas.id_pregunta=test_opciones.id_pregunta and pregunta='$preguntaO' order by orden",$link) 
		or die ("SELECT test_preguntas.id_pregunta as idpregunta, pregunta, test_opciones.id_opcion, opcion FROM test_preguntas,test_opciones where test_preguntas.id_pregunta=test_opciones.id_pregunta and pregunta='$preguntaO' order by orden".mysql_error());
			$d=0;
			while($rsO_=mysql_fetch_array($rstO_))
			  {
			  $d=$d+1;
			  $totalOpciones=0;
			  	if($d==1)
				{
			   		$exportar.="<tr><td>".$rsO_['opcion']."&nbsp;".$rs_['respuesta']."</td>";
					$id_op=$rsO_['idOpcion'];
					$opcionO=$rsO_['opcion'];
					$id_pregunta= $rsO_['idpregunta'];
					
					$rstOp = mysql_query ("SELECT count(alumno) as entre FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and
test_preguntas.id_test=test.id_test and test_respuestas.responde = alumnos.alumno
and test.id_test = $test and test_preguntas.id_pregunta=$id_pregunta $and order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT count(alumno) as entre FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and
test_preguntas.id_test=test.id_test and test_respuestas.responde = alumnos.alumno
and test.id_test = $test and test_preguntas.id_pregunta=$id_pregunta $and order by test_preguntas.id_pregunta".mysql_error());
					while($r=mysql_fetch_array($rstOp)){
						$t_resp=$r['entre'];						
					}

										
					
					$rstOp_ = mysql_query ("SELECT count(test_opciones.id_opcion) as suma FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and
and test.id_test=$test and test_opciones.id_opcion=$id_op and test_preguntas.id_pregunta= $id_pregunta order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT count(test_opciones.id_opcion) as suma FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and
and test.id_test=$test and test_opciones.id_opcion=$id_op and test_preguntas.id_pregunta= $id_pregunta order by test_preguntas.id_pregunta".mysql_error());
					
					while($r_=mysql_fetch_array($rstOp_))
			  		{												
						$porcentaje=0;
						$total=0;
						$total=$r_['suma'];
						//$porcentaje =($total * 100) / $totalPreguntas; 
						//$porcentaje =round((($total * 100) /$t_resp),2); 
						$totalOpciones=$totalOpciones + 1;
						
						$exportar.="<td> # ".$total."</td><td> %".round((($total * 100) / $t_resp),2)."</td></tr>";

			  		}					
				}					
				
				else
				{
					$exportar.="<tr><td>".$rsO_['opcion']."</td>";
					$id_op=$rsO_['idOpcion'];
					$opcionO=$rsO_['opcion'];
					$id_pregunta= $rsO_['idpregunta'];
					
					$rstOp = mysql_query ("SELECT count(alumno) as entre FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and
test_preguntas.id_test=test.id_test and test_respuestas.responde = alumnos.alumno
and test.id_test = $test and test_preguntas.id_pregunta=$id_pregunta $and order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT count(alumno) as entre FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and
test_preguntas.id_test=test.id_test and test_respuestas.responde = alumnos.alumno
and test.id_test = $test and test_preguntas.id_pregunta=$id_pregunta $and order by test_preguntas.id_pregunta".mysql_error());
					while($r=mysql_fetch_array($rstOp)){
						$t_resp=$r['entre'];						
					}

					$rstOp_ = mysql_query ("SELECT count(test_opciones.id_opcion) as suma FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and
and test.id_test=$test and test_opciones.id_opcion=$id_op and test_preguntas.id_pregunta= $id_pregunta order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT count(test_opciones.id_opcion) as suma FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and

test_respuestas.responde = alumnos.alumno $and
and test.id_test=$test and test_opciones.id_opcion=$id_op and test_preguntas.id_pregunta= $id_pregunta order by test_preguntas.id_pregunta".mysql_error());
					
					while($r_=mysql_fetch_array($rstOp_))
			  		{
						$porcentaje=0;
						$total=0;
						$total=$r_['suma'];
						$porcentaje =$total * 100 / $totalPreguntas; 
						$totalOpciones=$totalOpciones + 1;
			  			$exportar.="<td> # ".$r_['suma']."</td><td> %".round((($total * 100) / $t_resp),2)."</td></tr>";
			  		}		
				}			
			  }

			  $exportar.="<tr><th>Total preguntas </th><td>".$totalPreguntas."</td></tr><tr><td> </td></tr>";




			
			} 

$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['nombre'];
$fecha_anterior=$rs_['fechaterminado']; 
$seccion_anterior=$rs_['seccion'];
$grado_anterior=$rs_['grado'];
$grupo_anterior=$rs_['grupo'];
$pregunta_anterior=$rs_['pregunta'];  
$opcion_anterior=$rs_['opcion'];

  } 
  $totalFinal= $totalFinal + $totEntrevistas;
  
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
  $ver_reporte='N'; 
}
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
 
}





//DETALLADO DE TEST

if($tipo=='e_Detallado')
{
$idT= $nombre_test; 
$idTest="and test_publicacion.id_test = $test";
$totalPreguntas=0;
$rstT_ = mysql_query ("SELECT count(id_estatus) as total FROM test_estatus, alumnos, test_publicacion
where test_estatus.responde = alumnos.alumno
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' $and
and test_estatus.id_publicacion = test_publicacion.id_publicacion $idTest ",$link) 
		or die ("SELECT count(id_estatus) as total FROM test_estatus, alumnos, test_publicacion
where test_estatus.responde = alumnos.alumno
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' $and 
and test_estatus.id_publicacion = test_publicacion.id_publicacion $idTest ".mysql_error());
while($rsT_=mysql_fetch_array($rstT_))
  {       
	$totalPreguntas= $rsT_['total'];
  }

$exportar="<table><tr><th style='font-size:24px' colspan=10> Reporte Detallado de Test </th></tr><tr><td></td></tr>";

$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);
switch($mes_ini)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);
switch($mes_fin)
{
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}


$exportar.="<tr><td><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$dia_ini."-".$pmes."-".$y_ini."&nbsp;&nbsp;&nbsp;al&nbsp; ".$dia_fin."-".$smes."-".$y_fin." </td></tr><tr></tr><tr></tr><tr></tr>";


$idtest_=""; if($test!=0) $idtest_="and test.id_test = $test";
/*echo" <script language='javascript'>alert('FI $fecha_ini FF $fecha_fin');</script>";*/
	$rst_ = mysql_query ("SELECT test.nombre as ntest, test_preguntas.id_pregunta, pregunta, opcion, test_opciones.id_opcion, tipo, seccion, grado, grupo, preceptor, fechaterminado, alumnos.nombre, alumno, familia, respuesta FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and  $idtest_ order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT test.nombre as ntest, test_preguntas.id_pregunta, pregunta, opcion, test_opciones.id_opcion, tipo, seccion, preceptor, grado, grupo, fechaterminado, alumnos.nombre, alumno, familia, respuesta FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and $idtest_ order by test_preguntas.id_pregunta".mysql_error());
		
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$grado_anterior=0;
$grupo_anterior="";
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";
$test_anterior="";

$g=0;
$c = 0;

  while($rs_=mysql_fetch_array($rst_))
  { 
  
  	$c = $c + 1;
	if($c == 1)
	{
	
	if($rs_['ntest']!=$test_anterior)
    { 	
		$ntest= $rs_['ntest'];
		$exportar.="<tr><td><b>Nombre de Test: </b>".$rs_['ntest']."</td></tr><tr>&nbsp;</tr>"; 
	}
  
  	if($preceptor == 0){
		$exportar.="<tr><td><b>Preceptor: Todos</b></td></tr><tr>&nbsp;</tr>"; 
	}
	else
	{	
		if($rs_['preceptor']!=$preceptor_anterior)
    	{ 	
		$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$preceptor ",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
		}
	}


/*    if($rs_['preceptor']!=$preceptor_anterior)
    { 	
		$prec= $rs_['preceptor'];
		$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$prec ",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
	}*/
	
	
	/*if($seccion == 0){
	
		$exportar.="<tr><td><b>Secci&oacute;n: Todas</b>&nbsp;&nbsp;&nbsp;"; 
	
		}
		else
		{
			if($rs_['seccion']!=$seccion_anterior)
			{
				$exportar.="<tr><td><b>Secci?n: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
			}
		}*/
		
	if($rs_['seccion']!=$seccion_anterior)
	{
	
		if($seccion == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Inicial:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Inicial: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($grado == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$grado." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupo == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupo."</td></tr>"; 
		}
		
		//////
		if($seccionf == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Final:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Final: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccionf ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($gradof == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$gradof." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupof == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupof."</td></tr>"; 
		}
		
		
	}
		
			
	}
	
	 	$fecha= $rs_['fechaterminado'];  
   	 	$mes= substr($fecha, 5, 2);
 
	    switch($mes)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

	   	$imprimirMes.= $nmes;

	 	 /*if($rs_['familia']!=$familia_anterior)
    	{ 
			$exportar.="<td>&nbsp;</td><td colspan=2>".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)." ";
		}*/
		
/*		if($rs_['nombre']!=$alumno_anterior)
    	{ 
			$exportar.=" " .$rs_['nombre']."</td>";
		}		*/
		
		if($rs_['pregunta']!=$pregunta_anterior)
		{					
		
		      $exportar.="<tr><td>&nbsp;</td></tr><tr><td>--------</td></tr><tr><td><b>PREGUNTA: </b>".$rs_['pregunta']."</td></tr><tr>&nbsp;</tr>";	
		} 
			 
			 $opcionO=$rs_['id_opcion'];
			 $id_pregunta= $rs_['id_pregunta'];
			 
			 
			 $rstOp_ = mysql_query ("SELECT count(alumno) as entre FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and
test_preguntas.id_test=test.id_test and test_respuestas.responde = alumnos.alumno
and test.id_test = $test and test_preguntas.id_pregunta=$id_pregunta $and order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT count(alumno) as entre FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion
and test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and
test_preguntas.id_test=test.id_test and test_respuestas.responde = alumnos.alumno
and test.id_test = $test and test_preguntas.id_pregunta=$id_pregunta $and order by test_preguntas.id_pregunta".mysql_error());
					$t_resp=0;
					while($r_=mysql_fetch_array($rstOp_)){
						$t_resp=$r_['entre'];
					}
			 
			 
			 $rstOp_ = mysql_query ("SELECT count(test_opciones.id_opcion) as suma FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and
$idtest_ and test_opciones.id_opcion=$opcionO and test_preguntas.id_pregunta= $id_pregunta order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT count(test_opciones.id_opcion) as suma FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and
$idtest_ and test_opciones.id_opcion=$opcionO and test_preguntas.id_pregunta= $id_pregunta order by test_preguntas.id_pregunta".mysql_error());
					$totalOpcionesD=0;
					while($r_=mysql_fetch_array($rstOp_)){
						$porcentaje=0;
						$total=0;
						$total=$r_['suma'];
						$porcentaje =round(($total * 100) / $t_resp,2); 
						$totalOpcionesD=$totalOpcionesD + 1;
			 	if($rs_['opcion']!= $opcion_anterior){
			  $exportar.="<tr><td>&nbsp;</td></tr><tr><td><b>Respuesta:  </b>".$rs_['opcion'].$rs_['respuesta']."</td> <td>#".$r_['suma']."</td><td>%".$porcentaje."</td></tr>"; 
			 	 }
			  }
			  $exportar.="<tr><td><b>Alumno: </b>".$rs_['alumno']." &nbsp;&nbsp;".$rs_['nombre']."</td>";
			  $exportar.="<td><b>Familia: </b>".$rs_['familia']."</td>";
			  $nfamilia=$rs_['familia'];
			  $exportar.="<td>&nbsp;&nbsp;</td><td>".mysql_result(mysql_query("select nombre_familia from familias where familia=$nfamilia ",$link),0,0)."</td></tr>";
$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['nombre'];
$fecha_anterior=$rs_['fechaterminado']; 
$seccion_anterior=$rs_['seccion'];
$grado_anterior=$rs_['grado'];
$grupo_anterior=$rs_['grupo'];
$pregunta_anterior=$rs_['pregunta'];  
$opcion_anterior=$rs_['opcion'];
$test_anterior=$rs_['ntest'];

  } 
  $totalFinal= $totalFinal + $totEntrevistas;
  
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N';
}
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";




//PROMEDIO GRUPAL POR TEST

if($tipo=='grupal')
{
$idT= $nombre_test; 
$narMin=0;
$narMax=0;
$azMin=0;
$azMax=0;
$verMin=0;
$verMax=0;
$cc=0;
$rstT_ = mysql_query ("SELECT * from test_evaluacion where id_test=$test order by min",$link) 
		or die ("SELECT * from test_evaluacion where id_test=$idT order by min".mysql_error());
while($rsT_=mysql_fetch_array($rstT_))
  {
  	$cc=$cc + 1;
	if($cc == 1 ){
  	 $narMin= $rsT_['min'];
	 $narMax= $rsT_['max'];
   }
   if($cc == 2 ){
  	 $azMin= $rsT_['min'];
	 $azMax= $rsT_['max'];
   }
   if($cc == 3 ){
  	 $verMin= $rsT_['min'];
	 $verMax= $rsT_['max'];
   }
  }
 
$idTest="and test_publicacion.id_test = $test";

$exportar="<table><tr><th style='font-size:24px' colspan=10> Reporte Test Grupal </th></tr>";

if($ver_reporte=='S')

{ 
$idtest_="and test_publicacion.id_test='$test'"; 
	$rst_ = mysql_query ("SELECT     test_publicacion.id_test, test.nombre as curso, alumnos.seccion, alumnos.preceptor, test_estatus.fechaterminado, alumnos.nombre, alumnos.alumno, alumnos.familia, alumnos.grado, alumnos.grupo, test_estatus.calificacion













FROM         test_estatus INNER JOIN
                      alumnos ON test_estatus.responde=alumnos.alumno
 INNER JOIN
                      test_publicacion ON test_estatus.id_publicacion = test_publicacion.id_publicacion
 INNER JOIN
                    test ON test_publicacion.id_test = test.id_test and 
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin and' $idtest_ order by alumnos.alumno",$link) 





		or die ("SELECT     test_publicacion.id_test, test.nombre as curso, alumnos.seccion, alumnos.preceptor, test_estatus.fechaterminado, alumnos.nombre, alumnos.alumno, alumnos.familia, alumnos.grado, alumnos.grupo, test_estatus.calificacion
FROM         test_estatus INNER JOIN
                      alumnos ON test_estatus.responde=alumnos.alumno
 INNER JOIN
                      test_publicacion ON test_estatus.id_publicacion = test_publicacion.id_publicacion
 INNER JOIN
                    test ON test_publicacion.id_test = test.id_test and 
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin and' $idtest_ order by alumnos.alumno".mysql_error());
		
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";
$test_anterior="";

$c = 0;
$naranja=0;
$azul=0;
$verde=0;
$colorPromedio="";
$sumaP=0;
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$grado_anterior=0;
$grupo_anterior="";
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";


  while($rs_=mysql_fetch_array($rst_))
  { 
  
  	$c = $c + 1;
	if($c==1)
	{
	/*if($preceptor == 0){
		$exportar.="<tr><td><b>Preceptor: Todos</b></td></tr><tr>&nbsp;</tr>"; 
		}
		else
		{	
		$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$preceptor",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
		}
*/
    /*if($rs_['preceptor']!=$preceptor_anterior)
    { 	
		$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) as n from personal where empleado=".$rs_['preceptor'],$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
	}*/
	
	if($rs_['seccion']!=$seccion_anterior)
	{
	
		if($seccion == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Inicial:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Inicial: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($grado == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$grado." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupo == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupo."</td></tr>"; 
		}
		
		//////
		if($seccionf == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Final:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Final: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccionf ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($gradof == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$gradof." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupof == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupof."</td></tr>"; 
		}
		
		

	}
	/*if($seccion == 0){
		$exportar.="<tr><td><b>Secci&oacute;n: </b>Todas</td></tr>"; 
		}
	else
		{
		$exportar.="<tr><td><b>Secci�n: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."</td> &nbsp;&nbsp;"; 
		}
		
	if($grado == 0){
		$exportar.="<td><b>Grado: </b>Todos</td> &nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<td><b>Grado: </b>".$rs_['grado']."</td> &nbsp;&nbsp;"; 
		}
	if($grupo == ''){
		$exportar.="<td><b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<td><b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$rs_['grupo']."</td></tr>"; 
		}*/

	
	
	

	$exportar.="<tr><td><b>Nombre de test: </b>".$rs_['test']."</td></tr><tr>&nbsp;</tr>"; 


	}

  	 	$fecha= $rs_['fechaterminado'];  
   	 	$mes= substr($fecha, 5, 2);
 
	    switch($mes)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

	   	$imprimirMes.= $nmes;

		 if($c==1)
		 {
		 	$exportar.="
			<tr><td>&nbsp;</td></tr>

			<tr><td><b>Alumno</b></td>
			<td>&nbsp;</td>
			<td><b>Calificaci&oacute;n</b></td>
			<td>&nbsp;</td>
			<td><b>Color</b></td>








			</tr>";
		 	
		 }
		 
	 	 if($rs_['familia']!=$familia_anterior)
    	{ 
			$fam=mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0);
		}
		
	if($rs_['alumno']!=$alumno_anterior)
    	{ 			
			$alum=$rs_['nombre'];
			
		}
			
		$calif= $rs_['calificacion'];
		//$imagen="";
			if($calif >= $narMin and $calif <= $narMax)
			{
				$imagen="<img src='images/naranja.png'/> &nbsp; Naranja";
				$naranja= $naranja+1;
			}
			
			if($calif >= $azMin and $calif <= $azMax)
			{
				$imagen="<img src='images/azul.png'/> &nbsp; Azul";
				$azul= $azul+1;
			}
			
			if($calif >= $verMin and $calif <= $verMax or $calif==$verMin or $calif==verMax )
			{
				
				$imagen="<img src='images/verde.png'/> &nbsp; Verde";
				$verde= $verde+1;
			}
		$sumaP=$sumaP + $rs_['calificacion'];
		
			$exportar.="

			<tr><td>".$alum."&nbsp;".$fam."</td>
			<td>&nbsp;</td>
			<td>".$calif."</td>
			<td>&nbsp;</td>
			<td>".$imagen."</td>

			</tr>";
	

$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['alumno'];
$fecha_anterior=$rs_['fechaterminado']; 
$seccion_anterior=$rs_['seccion'];
$test_anterior=$rs_['test'];


  } 

		
		$promedioC= $sumaP/$c;
		
		if($promedioC >= $narMin and $promedioC <= $narMax)
		{
			$colorPromedio="<img src='images/naranja.png'/>  &nbsp; Naranja";
		}
		if($promedioC >= $azMin and $promedioC <= $azMax)
		{
			$colorPromedio="<img src='images/azul.png'/>  &nbsp; Azul";
		}
		if($promedioC >= $verMin and $promedioC <= $verMax)
		{
			$colorPromedio="<img src='images/verde.png'/>  &nbsp; Verde";
		}

			$exportar.="<tr><td>&nbsp;</td></tr><td colspan=1></td><td><b>Promedio: </b></td><td>" .$promedioC."</td><td>&nbsp;</td><td>".$colorPromedio."</td></tr>";








  $totalFinal= $totalFinal + $totEntrevistas;
  
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N'; 
}
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
 
}






if($tipo=='test')
{

$exportar="<table><tr><th style='font-size:24px' colspan=10> Reporte Total de Test por Grupo </th></tr>";

if($ver_reporte=='S')
{ 
$idtest_="and test_publicacion.id_test = $test"; 

	$rst_ = mysql_query ("SELECT terminado, fechaterminado, id_test, alumnos.seccion, alumnos.grado, alumnos.grupo, alumnos.nombre, alumnos.familia, alumnos.alumno, alumnos.preceptor  FROM test_estatus, test_publicacion, alumnos where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and alumnos.alumno=test_estatus.responde and test_estatus.id_publicacion=test_publicacion.id_publicacion $and order by familia",$link) 
		or die ("SELECT terminado, fechaterminado, id_test, alumnos.seccion, alumnos.grado, alumnos.grupo, alumnos.nombre, alumnos.familia, alumnos.alumno, alumnos.preceptor  FROM test_estatus, test_publicacion, alumnos where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and alumnos.alumno=test_estatus.responde and test_estatus.id_publicacion=test_publicacion.id_publicacion $and order by familia".mysql_error());
		
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";
$test_anterior=0;

$c = 0;
$contador=0;

  while($rs_=mysql_fetch_array($rst_))
  { 
  
  	$c = $c + 1;
	$contador=$contador + 1;
	if($c == 1)
			{
	if($preceptor == 0){
		$exportar.="<tr><td><b>Preceptor: Todos</b></td></tr><tr>&nbsp;</tr>"; 
		}
		else
		{	
		$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$preceptor",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
		}

  /*
    if($rs_['preceptor']!=$preceptor_anterior)
    { 			
		$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) as n from personal where empleado=".$rs_['preceptor'],$link),0,0)."</td></tr>"; 		
	}*/
	
	if($rs_['seccion']!=$seccion_anterior)
	{
	
		if($seccion == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Inicial:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Inicial: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($grado == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$grado." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupo == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupo."</td></tr>"; 
		}
		
		//////
		if($seccionf == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Final:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n Final: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccionf ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($gradof == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$gradof." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupof == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupof."</td></tr>"; 
		}
		
		
	}
	
/*	if($seccion == 0){
		$exportar.="<tr><td><b>Secci&oacute;n:</b> Todas</td>"; 
		}
		else
		{
		$exportar.="<tr><td><b>Secci?n: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."</td>"; 
		}
		
		if($grado == 0){
		$exportar.="<td><b>Grado: </b>Todos</td>"; 
		}
	else
		{
		$exportar.="<td><b>Grado: </b>".$rs_['grado']."</td>"; 
		}
	if($grupo == ''){
		$exportar.="<td><b>Grupo:</b> Todos</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>"; 
		}
	else
		{
		$exportar.="<td><b>Grupo: </b>".$rs_['grupo']."</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>"; 
		}*/

	
		$exportar.="
		<tr>

		<td><b>&nbsp;&nbsp;&nbsp;&nbsp;TEST</b></td>
		<td><b>NOMBRE</b></td>
		<td><b>ESTATUS</b></td>
		<td><b>FECHA TERMINADO</b></td>
		</tr>";
		}
							 	
	 	 if($rs_['familia']!=$familia_anterior)
    	{ 

			$exportar.="<tr><td><b>Alumno: </b>".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)."&nbsp;".$rs_['nombre']."</tr> ";

		}
		

			$idalumno=0;
			$idalumno=$rs_['alumno'];

  			
			if($contador == 1)
			{
				$exportar.= "<tr>
				<td>".$contador."&nbsp;&nbsp;&nbsp;".mysql_result(mysql_query("select nombre from test where id_test=".$rs_['id_test'],$link),0,0)."</td>";
				$exportar.= "<td>".mysql_result(mysql_query("select descripcion from test where id_test=".$rs_['id_test'],$link),0,0)."&nbsp;&nbsp;&nbsp;</td>";
				if($rs_['terminado']=='N')
				{
					$exportar.= "<td>Sin terminar&nbsp;&nbsp;&nbsp;</td>";
				}
				if($rs_['terminado']== 'S')
				{
					$exportar.= "<td>Terminado&nbsp;&nbsp;&nbsp;</td>";
				}
			
				$exportar.= "<td>".formatDate($rs_['fechaterminado'])."</td></tr><tr><td>&nbsp;</td></tr>";
			}
			else
			{
    			$exportar.= "<tr><td>".$contador."&nbsp;&nbsp;&nbsp;".mysql_result(mysql_query("select nombre from test where id_test=".$rs_['id_test'],$link),0,0)."</td>";
				$exportar.= "<td>".mysql_result(mysql_query("select descripcion from test where id_test=".$rs_['id_test'],$link),0,0)."&nbsp;&nbsp;&nbsp;</td>";
				if($rs_['terminado']=='N')
				{
					$exportar.= "<td>Sin terminar&nbsp;&nbsp;&nbsp;</td>";
				}
				if($rs_['terminado']== 'S')
				{
					$exportar.= "<td>Terminado&nbsp;&nbsp;&nbsp;</td>";
				}
			
				$exportar.= "<td>".formatDate($rs_['fechaterminado'])."</td></tr><tr><td>&nbsp;</td></tr>";




			}


$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['nombre'];
$fecha_anterior=$rs_['fechaterminado']; 
$seccion_anterior=$rs_['seccion'];
$test_anterior=$rs_['id_test'];

  } 
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N';
}
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}








if($tipo=='test_comentarios')
{
$idTest= $nombre_test; 



$exportar="<table><tr><th style='font-size:24px' colspan=10> Reporte Comentarios por Test </th></tr>";

if($ver_reporte=='S')
{ 

$idTest_="and test.id_test = $test"; 

	$rst_ = mysql_query ("SELECT id_test, test.nombre as ntest, seccion, preceptor, fechaterminado, alumnos.nombre, alumno, familia, alumnos.grado, alumnos.grupo, calificacion, comentario FROM
test_estatus, alumnos, test where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and test_estatus.responde = alumnos.alumno $and $idTest_ order by alumnos.alumno",$link) 
		or die ("SELECT id_test, test.nombre as ntest, seccion, preceptor, fechaterminado, alumnos.nombre, alumno, familia, alumnos.grado, alumnos.grupo, calificacion, comentario FROM
test_estatus, alumnos, test where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and test_estatus.responde = alumnos.alumno $and $idTest_ order by alumnos.alumno".mysql_error());
		
	
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";
$test_anterior=0;

$c = 0;

  while($rs_=mysql_fetch_array($rst_))
  { 
  
  	$c = $c + 1;
  
    if($rs_['preceptor']!=$preceptor_anterior)
    { 	
		$prec=$rs_['preceptor'];
		$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$preceptor ",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
	}
	
	if($rs_['seccion']!=$seccion_anterior) 
	{
		$secc=$rs_['seccion'];
		$exportar.="<tr><td><b>Secci?n: ".mysql_result(mysql_query("select nombre from secciones where seccion=$secc ",$link),0,0)."</b></td><td><b>Grado: </b>".$rs_['grado']."</td><td>&nbsp;&nbsp;<b>Grupo: </b>".$rs_['grupo']."</td></tr><tr>&nbsp;</tr><tr><td>&nbsp;</td></tr>"; 
	}
	
	if($rs_['ntest']!=$test_anterior) 
	{
		$exportar.="<tr><td><b>Test: ".$rs_['ntest']."</td></td></tr>"; 
	}

  	 	$fecha= $rs_['fechaterminado'];  
   	 	$mes= substr($fecha, 5, 2);
 
	    switch($mes)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

	   	$imprimirMes.= $nmes;
		if($c==1)
		{
			$exportar.="<tr><td>Alumno</td><td>&nbsp;</td><td>Calificaci&oacute;n</td><td>&nbsp;</td><td>Comentario</td></tr>";
		}
	 	 if($rs_['familia']!=$familia_anterior)
    	{ 
			$exportar.="<td>".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)." ";
		}
		
	if($rs_['nombre']!=$alumno_anterior)
    	{ 
			$exportar.=" " .$rs_['nombre']."</td><td>&nbsp;</td><td>" .$rs_['calificacion']."</td><td>&nbsp;</td><td>" .$rs_['comentario']."</td>";
		}	

$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['nombre'];
$fecha_anterior=$rs_['fechaterminado']; 
$seccion_anterior=$rs_['seccion'];
$test_anterior=$rs_['ntest'];


  } 
  $rstP_ = mysql_query ("SELECT avg(calificacion) as promedio FROM
test_estatus, alumnos, test where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and test_estatus.responde = alumnos.alumno $and $idTest_ order by alumnos.alumno",$link) 
		or die ("SELECT avg(calificacion) as promedio FROM
test_estatus, alumnos, test where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and test_estatus.responde = alumnos.alumno $and $idTest_ order by alumnos.alumno".mysql_error());
		
		while($rsP_=mysql_fetch_array($rstP_))
		{
			$exportar.="<tr><td><b>Promedio: </b></td><td colspan=1></td><td>" .$rsP_['promedio']."</td></tr>";
		} 
  $totalFinal= $totalFinal + $totEntrevistas;
  
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N';  
}
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}


//TEST POR ALUMNO
if($tipo=='test_comentarios_alumno')
{
$idTest= $nombre_test; 



$exportar="<table><tr><th style='font-size:24px' colspan=10> Reporte Comentarios Test por Alumno </th></tr>";

if($ver_reporte=='S')
{ 

$idtest_="and test.id_test = $test"; 

	$rst_ = mysql_query ("select sum(test_opciones.puntos) as puntos, alumnos.alumno, alumnos.nombre as nombre, alumnos.seccion, alumnos.preceptor, alumnos.grado, alumnos.grupo, alumnos.familia,
test.nombre as test, test.id_test, fechaterminado from test_estatus, test, test_publicacion,
test_preguntas, test_opciones, test_respuestas, alumnos
where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
      test_opciones.id_pregunta=test_preguntas.id_pregunta and
      test_preguntas.id_test=test.id_test and
      test.id_test=test_publicacion.id_test and
      test_publicacion.id_publicacion=test_respuestas.id_publicacion and
      test_publicacion.id_publicacion=test_estatus.id_publicacion and
      test_estatus.responde=test_respuestas.responde and
      test_respuestas.responde=alumnos.alumno and
	  test_respuestas.id_opcion=test_opciones.id_opcion
	  $and $idtest_ order by alumnos.alumno",$link) 
		or die ("select sum(test_opciones.puntos) as puntos, alumnos.alumno, alumnos.nombre as nombre, alumnos.seccion, alumnos.preceptor, alumnos.grado, alumnos.grupo, alumnos.familia,
test.nombre as test, test.id_test, fechaterminado from test_estatus, test, test_publicacion,
test_preguntas, test_opciones, test_respuestas, alumnos
where test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
      test_opciones.id_pregunta=test_preguntas.id_pregunta and
      test_preguntas.id_test=test.id_test and
      test.id_test=test_publicacion.id_test and
      test_publicacion.id_publicacion=test_respuestas.id_publicacion and
      test_publicacion.id_publicacion=test_estatus.id_publicacion and
      test_estatus.responde=test_respuestas.responde and
      test_respuestas.responde=alumnos.alumno and
	  test_respuestas.id_opcion=test_opciones.id_opcion	  
	  $and $idtest_ order by alumnos.alumno".mysql_error());
		
		
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";
$test_anterior="";

$c = 0;

  while($rs_=mysql_fetch_array($rst_))
  { 
  	$c = $c + 1;
  
    if($rs_['preceptor']!=$preceptor_anterior)
    { 	
		$prec=$rs_['preceptor'];
		$exportar.="<tr><td>&nbsp;</td></tr><tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$prec ",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
	}
	
	if($rs_['seccion']!=$seccion_anterior)
	{
		$secc= $rs_['seccion'];
		$exportar.="<tr><td><b>Secci&oacute;n: ".mysql_result(mysql_query("select nombre from secciones where seccion=$secc ",$link),0,0)."</b></td><td><b>Grado: </b>".$rs_['grado']."</td><td>&nbsp;&nbsp;<b>Grupo: </b>".$rs_['grupo']."</td></tr><tr>&nbsp;</tr><tr><td>&nbsp;</td></tr>"; 
	}

  	 	$fecha= $rs_['fechaterminado'];  
   	 	$mes= substr($fecha, 5, 2);
 
	    switch($mes)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

	   	$imprimirMes.= $nmes;
		if($rs_['test']!=$test_anterior)
    	{ 
			$exportar.=" <tr><td>&nbsp;</td></tr><td><b>Test: </b>" .$rs_['test']."</td><td>&nbsp;</td><tr></tr>";
					
		}
			/*if($c==1)
			{
			$exportar.="<tr><td><b>Alumno</b></td><td>&nbsp;</td><td><b>Test</b></td><td><b>Calificaci&oacute;n</b></td><td>&nbsp;</td><td><b>Comentario</b></td></tr>";
			}*/

	 	 if($rs_['familia']!=$familia_anterior)
    	{ 
			$exportar.="<td>".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)." ";
		}
		
	$color=0;
	$imprimircolor=""; if(!empty($_POST['imprimircolor'])) $imprimircolor=$_POST['imprimircolor']; 
	$sugerencia="";
	$vueltas=0;
	
	if($rs_['nombre']!=$alumno_anterior)
    	{ 
			$test_n=$rs_['id_test'];
			$exportar.=" " .$rs_['nombre']."</td><td>&nbsp;</td><tr></tr>";
			
			
			$rstP_ = mysql_query ("select id_test, min, max, comentario, sugerencia from test_evaluacion
where id_test=$test_n",$link) 
		or die ("select id_test, min, max, comentario, sugerencia from test_evaluacion
where id_test=$test_n".mysql_error());
			
			while($rsP_=mysql_fetch_array($rstP_))
  			{
				$vueltas=$vueltas+1;
				$rango1=$rsP_['min'];
				$rango2=$rsP_['max'];
				if($rs_['puntos'] >= $rango1 and $rs_['puntos'] <= $rango2)
				{
					$color=$vueltas;
					$sugerencia=$rsP_['comentario'];
					$sugerenciaN=$rsP_['sugerencia'];
				}
			
			}
			if($color==1)
			{
				$imprimir_color="Naranja &nbsp; <img src='images/naranja.png'/>";
			}
			if($color==2)
			{
				$imprimir_color="Azul &nbsp; <img src='images/azul.png'/>";
			}
			if($color==3)
			{
				$imprimir_color="Verde &nbsp; <img src='images/verde.png'/>";
			}
			
					
		}	
		
		
		
		$exportar.="<td colspan=1></td><td><b>Puntos: </b>" .$rs_['puntos']."</td>
					<tr>
					<td colspan=1></td><td><b>Color: </b>" .$imprimir_color."</td>
					</tr>
					<tr>
					<td colspan=1></td><td colspan = 2><b>Comentario: </b>" .$sugerencia."</td>
					</tr>
					<tr>
					<td colspan=1></td><td colspan = 2><b>Sugerencia: </b>" .$sugerenciaN."</td>
					</tr>
					";


$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['nombre'];
$fecha_anterior=$rs_['fechaterminado']; 
$seccion_anterior=$rs_['seccion'];
$test_anterior=$rs_['test'];


  } 
  
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N';
}
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}

//ENTREVISTAS CON PADRES DE FAMILIA

if($tipo=='entrevistas'){

$exportar="<table width='600' class='reporte'><tr><th style='font-size:24px' colspan='100%'> Reporte Entrevistas con Padres de Familia </th></tr><tr><td></td></tr>";

$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);
switch($mes_ini)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);
switch($mes_fin){
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}


$exportar.="<tr><td colspan='100%'><b>Rango de Fechas : </b> Del ".$dia_ini."-".$pmes."-".$y_ini." al ".$dia_fin."-".$smes."-".$y_fin." </td></tr>";

$exportar.="<tr><td colspan='100%'>&nbsp;</td></tr>";



if($ver_reporte=='S'){
		$rst_ = mysql_query ("SELECT 
									* 
								FROM 
									preceptoria_acuerdos,
									alumnos 
								WHERE
									fec between '$fecha_ini' and '$fecha_fin' 
									and alumnos.alumno =preceptoria_acuerdos.alumno 
									and alumnos.activo='A' 
									$and 
								GROUP BY 
									seccion, grado, grupo, alumnos.alumno",$link) or die ("SELECT * FROM preceptoria_acuerdos,alumnos where fec between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria_acuerdos.alumno $and  group by seccion, grado, grupo, alumnos.alumno".mysql_error());
$preceptor_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$sec_anterior=0;
$c = 0;
$d = 0;
$f = 0;
$totalP=0;
while($rs_=mysql_fetch_array($rst_)){
	$c = $c + 1;
	$d= $d+1;
	$f = $f + 1;
	if($c == 1){ // mod 02 sept
		$exportar.=
	  " <tr>

		<td align='center' valign='top'><b>Nombre de alumnos </b></td>
		<td align='center' valign='top'><b>Grupo </b></td>
		<td align='center' valign='top'><b>Preceptor </b></td>
		<!--<td align='center' valign='top'><b>Fecha &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>-->
		<td align='center' valign='top'  colspan='2'><b>Fecha </b></td>
		<td align='center' valign='top'><b>Padre &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' valign='top'><b>Madre &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' valign='top'><b>TOTAL de Entrevistas</b></td>
		</tr>
		
		<!--<tr><td colspan='100%'>&nbsp;</td></tr>-->";

	}
	
	if($rs_['alumno']!=$alumno_anterior){
		//$exportar.="<tr><td align='left' valign='top'>".$rs_['alumno']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";

		if($sec_anterior!=$rs_['seccion'])
		{
			$exportar.="<tr>";	
		$s=$rs_['seccion'];
		$exportar.="<td align='center' valign='top' colspan='100%' class='secciones' ><b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)."</b></td></tr>"; //Sacar el nombre de la seccion

			
		}	
		$exportar.="<tr>";	
		$exportar.="<td align='left' valign='top'>".$rs_['nombre'].' '.$rs_['apellido_paterno'].' '.$rs_['apellido_materno']." </td>";		
		$exportar.="<td align='left' valign='top'>".$rs_['grado']." &nbsp; ".$rs_['grupo']." </td>";
		$prec=mysql_result(mysql_query("select 
												concat(nombre,' ',apellido_paterno,' ',apellido_materno) 
											FROM
												personal where empleado=".$rs_['preceptor']." ",$link),0,0);
//		if($rs_['preceptor']!= $preceptor_anterior)

			$exportar.="<td align='left' valign='top'>".$prec."</td>";
			$fechaP=$rs_['fec'];
			$fechaP= substr($fechaP, 0, 10);
			$fechaP= formatDate($fechaP);
			$exportar.="<td align='center' valign='top' colspan='2'>".$fechaP."</td>";
			$aPadre=$rs_['padre'];
			$aMadre=$rs_['madre'];
			if($aPadre==1)
				$aPadre='Si';
			else
				$aPadre='No';
			if($aMadre==1)
				$aMadre='Si';
			else
				$aMadre='No';
			$exportar.="<td align='center' valign='top'>".$aPadre."</td>";
			$exportar.="<td align='center' valign='top'>".$aMadre."</td>";
		//else
			//$exportar.="<td align='left' valign='top'>&nbsp;</td>";

		
		$nom=$rs_['alumno'];
	$rstF_=mysql_query("SELECT 
							count(fec) as tot 
						FROM 
							preceptoria_acuerdos,
							alumnos 
						WHERE
							fec between '$fecha_ini' and '$fecha_fin' 
							and alumnos.alumno=preceptoria_acuerdos.alumno 
							and alumnos.alumno=$nom  
							and alumnos.activo='A' 
							and alumnos.nuevo_ingreso!='P'
							and concat(padre,madre)<>'00' ",$link) or die ("SELECT count(distinct(fec)) as tot FROM preceptoria_acuerdos,alumnos where fec between '$fecha_ini' and '$fecha_fin' and alumnos.alumno=preceptoria_acuerdos.alumno and alumnos.alumno=$nom  and alumnos.activo='A'".mysql_error());
		while($rsF_=mysql_fetch_array($rstF_)){
			$tot=$rsF_['tot'];
			$totalP=$totalP+$tot;
	 	}
		$exportar.="<td align='center' valign='top'>".$tot." </td>";		


	}
//}
$preceptor_anterior=$rs_['preceptor'];
$alumno_anterior=$rs_['nombre'];
$sec_anterior=$rs_[seccion];


}


$exportar.="<tr><td align='right' colspan='100%'><b>Total: </b>".$totalP."&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
$exportar.="</table>";

?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
echo "";
$ver_reporte='N';  
$seccion="";
$grado= 0;
$grupo= "";
$preceptor= 0;
} 
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}
/*$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);

switch($mes_ini){
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);

switch($mes_fin){
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}

$exportar.="<tr><td colspan=20><b>Rango de Fechas: </b> Del ".$dia_ini."-".$pmes."-".$y_ini."  ".$dia_fin."-".$smes."-".$y_fin." </td></tr>
<tr></tr>
<tr></tr>
<tr></tr>";

if($ver_reporte=='S')
{
$rst_ = mysql_query ("SELECT * FROM preceptoria_acuerdos,alumnos where fec between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria_acuerdos.alumno $and  order by alumnos.alumno",$link) or die ("SELECT * FROM preceptoria_acuerdos,alumnos where fec between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria_acuerdos.alumno $and  order by alumnos.alumno ".mysql_error());

$preceptor_anterior=0;
$familia_anterior=0;
$fecha_anterior="";
$alumno_anterior=0;
$asPadre_anterior="";
$asMadre_anterior="";
$c = 0;
$totAsistenciaP=0;
$totAsistenciaM=0;
$totAmbos=0;

  while($rs_=mysql_fetch_array($rst_)){
  $c = $c + 1;
  if($c == 1){
		if($preceptor == 0)
			$exportar.="<tr><td colspan=6><b>Preceptor: Todos</b></td></tr>";
		else
			$exportar.="<tr><td colspan=6><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$preceptor ",$link),0,0)."</td></tr>";
			
		if($rs_['seccion']!=$seccion_anterior)
	{
	
		if($seccion == 0){
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Inicial:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Inicial: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($grado == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$grado." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupo == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupo."</td></tr>"; 
		}
		
		//////
		if($seccionf == 0){
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Final:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Final: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccionf ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($gradof == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$gradof." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupof == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupof."</td></tr>"; 
		}
		
		
	}
		
	  
	  $exportar.=
	  "<tr>	  
		<th>Fecha</th>
		<td> </td>
		<th>Alumno</th>
		<td> </td>
		<th>Asisti&oacute; Padre</th> 
		<td> </td>		
		<th>Observaciones</th>
		<td> </td>
		<th>Asisti&oacute; Madre</th>
		<td> </td>		
		<th>Observaciones</th>			
		<td> </td>		
		</tr>
		";

}


    if($rs_['familia']!=$familia_anterior)
	
	$Asistio_Padre = 'No';
	$Asistio_Madre = 'No';
	
	if($rs_['padre']==1)
	 	$Asistio_Padre = 'Si';
	
	if($rs_['madre']==1)
	 	$Asistio_Madre = 'Si';
	
	$hora= substr($rs_['fec'], 10, 18);
	
	if($rs_['alumno']!=$alumno_anterior)
		
		$nalumno=$rs_['nombre'];
	
	else
		$nalumno=$rs_['nombre'];
	
	if($rs_['fec']!=$fecha_anterior || $rs_['alumno']!=$alumno_anterior){
		$totEntrevistas++;
		$nfecha=formatDate($rs_['fec']);
		if($rs_['padre']==1 && $rs_['madre']==0)
			$totAsistenciaP++;
		if($rs_['padre']==0 && $rs_['madre']==1)
			$totAsistenciaM++;
		if($rs_['padre']==1 && $rs_['madre']==1)
			$totAmbos++;
	}
	
	else{
		if($rs_['alumno']!=$alumno_anterior){
			$totEntrevistas++;
			$nfecha=formatDate($rs_['fec']);			
		}
		
		else
			$nfecha="";
	}
	
	if($rs_['padre'] == $asPadre_anterior)
	 	$Asistio_Padre="";
	
	if($rs_['madre'] == $asMadre_anterior)
	 	$Asistio_Madre="";
	
	if($rs_['fec']!=$fecha_anterior || $rs_['alumno']!=$alumno_anterior) // mod 02 sept ----- 
	
	$exportar.=
	  " <tr>
		<td width='121'> ".$nfecha." </td>
		<td width='1'></td>
		<td width='123'> ".$nalumno." ".mysql_result(mysql_query("select nombre_familia from familias where familia=".$rs_['familia'],$link),0,0)."</td>
		<td width='1'></td>
		<td width='121'>".$Asistio_Padre."</td> 		
		<td>&nbsp;</td>
		<td>".str_replace('\"','\'',addslashes($rs_['obs_padre']))." </td>
		<td>&nbsp;</td>
		<td>".$Asistio_Madre." </td>		
		<td>&nbsp;</td>
		<td>".str_replace('\"','\'',addslashes($rs_['obs_madre']))."</td>				
		
		</tr>";
		
$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['alumno'];
$fecha_anterior=$rs_['fec'];
$asPadre_anterior=$rs_['padre'];
$asMadre_anterior=$rs_['madre'];   
}

$exportar.= "
  		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr>
  		<th width='121'>Total Asistencia</th>
		<td></td>
		<td> <b>Padre:</b>".$totAsistenciaP."</td> 
		<td></td>
		<td width='128'><b> Madre: </b>".$totAsistenciaM."</td>
		<td></td>
		<td width='128'><b>Ambos: </b>".$totAmbos."</td>
		<!--<td>&nbsp;</td>
		<td width='128'><b>Ninguno: </b>".($totEntrevistas-($totAsistenciaP+$totAsistenciaM+$totAmbos))."</td>-->
		<td>&nbsp;</td>
		<td><b>Entrevistas: </b>".$totEntrevistas."</td></tr>
		";
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N';
   $seccion="";
	$grado= 0;
	$grupo= "";
	$preceptor= 0;
} 
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}
*/


//KARDEX!!!!!!
if($tipo=='kardex')
{

$exportark="<table align='center' width='550' border='1' bordercolor='1'>
<tr><th style='font-size:24px' colspan=2> Kardex  <td colspan=1><p align='CENTER'><img src='../im/logo__.jpg'/></p></th></tr>";





if($ver_reporte=='S')
{ $rst_ = mysql_query ("SELECT kardex.alumno, kardex.ciclo, kardex.seccion, kardex.grado, kardex.grupo, kardex.materia, materias_ciclos.nombre as mat,
kardex.calificacion, kardex.vuelta, concat(apellido_paterno,' ',apellido_materno,' ',alumnos.nombre) as n_alumno
FROM kardex, alumnos, materias_ciclos where kardex.alumno=alumnos.alumno and kardex.materia=materias_ciclos.materia and kardex.alumno=$al_kardex  order by kardex.alumno, kardex.ciclo, materias_ciclos.nombre",$link) or die ("SELECT kardex.alumno, kardex.ciclo, kardex.seccion, kardex.grado, kardex.grupo, kardex.materia, materias_ciclos.nombre as mat,
kardex.calificacion, kardex.vuelta, concat(apellido_paterno,' ',apellido_materno,' ',alumnos.nombre) as n_alumno
FROM kardex, alumnos, materias_ciclos where kardex.alumno=alumnos.alumno and kardex.materia=materias_ciclos.materia and kardex.alumno=$al_kardex  order by kardex.alumno, kardex.ciclo, materias_ciclos.nombre".mysql_error());

$alumno_anterior=0;
$ciclo_anterior=0;
$seccion_anterior="";
$grado_anterior=0;
$grupo_anterior="";
$materia_anterior="";
$c = 0;
  while($rs_=mysql_fetch_array($rst_))
  { 
  
  $c = $c + 1;
		$exportark.="<tr><td colspan='3'>";
		if( $c==1 )
					
		if($rs_['alumno']!=$alumno_anterior)
		{ 		
			$exportark.="<p><b>  Alumno: </b>" .$rs_['n_alumno']."</p>"; 		
		}
	
		if($rs_['seccion']!=$seccion_anterior)
		{ 		
			$exportark.="<p><b>  Secci&oacute;n: </b>" .mysql_result(mysql_query("select nombre from secciones where seccion=".$rs_['seccion'],$link),0,0)." "; 		
		}
		
		if($rs_['grado']!=$grado_anterior)
		{ 		
			$exportark.="<b>  Grado: </b>".$rs_['grado']." ".$rs_['grupo']."</p>"; 
		}
		
		 
		if($rs_['ciclo']!=$ciclo_anterior)
		{ 
		$exportark.="<b> Ciclo: </b>".$rs_['ciclo'].""; 

		$exportark.="<tr> 	<td colspan='2' width='200'><b>Materia</b></td> <td width='100'><p align='CENTER'><b>Calificaci&oacute;n</b></td></tr></p>"; 
		}
		
		if($rs_['mat']!=$materia_anterior)
		{ 	
		$exportark.="<tr>	<td colspan='2' width='200'>".$rs_['mat']."</td> <td width='100'><p align='CENTER'>".$rs_['calificacion']."</td></tr></p>"; 
		}	
	
$alumno_anterior=$rs_['alumno'];
$ciclo_anterior=$rs_['ciclo'];
$seccion_anterior=$rs_['seccion'];
$grado_anterior=$rs_['grado'];
$grupo_anterior=$rs_['grupo'];
$materia_anterior=$rs_['mat'];

  } 

  $exportark.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportark;
  ?>
  </div>
  <?
   echo"";
 
} 
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}



//PREGUNTAS!!!!!!
if($tipo=='preguntas')
{
$idT= $nombre_test; 

$exportar="<table><tr><th style='font-size:24px' colspan=10> Reporte Cruce de Preguntas </th></tr><tr><td>&nbsp;</td></tr>";

$idTest="and test_publicacion.id_test = $test";
$totalPreguntas=0;

$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);
switch($mes_ini)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);
switch($mes_fin)
{
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}


$exportar.="<tr><td><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$dia_ini."-".$pmes."-".$y_ini."&nbsp;&nbsp;&nbsp;al&nbsp; ".$dia_fin."-".$smes."-".$y_fin." </td></tr><tr></tr><tr></tr><tr></tr>";


if($ver_reporte=='S')
{ $idtest_=""; if($test!=0) $idtest_="and test.id_test = $test";

	$rst_ = mysql_query ("SELECT test.nombre as ntest, test_preguntas.id_pregunta, pregunta, opcion, test_opciones.id_opcion, tipo, seccion, grado, grupo, preceptor, fechaterminado, alumnos.nombre, alumno, familia, respuesta FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and  $idtest_ order by test_preguntas.id_pregunta",$link) 
		or die ("SELECT test.nombre as ntest, test_preguntas.id_pregunta, pregunta, opcion, test_opciones.id_opcion, tipo, seccion, preceptor, grado, grupo, fechaterminado, alumnos.nombre, alumno, familia, respuesta FROM test_respuestas,
test_estatus,  test_opciones,  test_preguntas, test, alumnos where test_respuestas.id_publicacion = test_estatus.id_publicacion and
test_estatus.fechaterminado between '$fecha_ini' and '$fecha_fin' and
test_respuestas.id_opcion = test_opciones.id_opcion and test_respuestas.id_pregunta = test_preguntas.id_pregunta and test_preguntas.id_test=test.id_test and
test_respuestas.responde = alumnos.alumno $and $idtest_ order by test_preguntas.id_pregunta".mysql_error());
		
$preceptor_anterior=0;
$familia_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$seccion_anterior=0;
$pregunta_anterior="";
$opcion_anterior="";
$tipo_anterior="";
$test_anterior="";

$c = 0;

  while($rs_=mysql_fetch_array($rst_))
  { 
  
  	$c = $c + 1;
	if($c == 1)
	{
	
		if($rs_['ntest']!=$test_anterior)
		{ 	
			$ntest= $rs_['ntest'];
			$exportar.="<tr><td><b>Nombre de Test: </b>".$rs_['ntest']."</td></tr><tr>&nbsp;</tr>"; 
		}
  
		if($preceptor == 0)
		{
			$exportar.="<tr><td><b>Preceptor: Todos</b></td></tr><tr>&nbsp;</tr>"; 
		}
		else
		{	
			if($rs_['preceptor']!=$preceptor_anterior)
			{ 	
			$exportar.="<tr><td><b>Preceptor: </b>".mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=$preceptor ",$link),0,0)."</td></tr><tr>&nbsp;</tr>"; 
			}
		}
	
	
	if($rs_['seccion']!=$seccion_anterior)
	{
	
		if($seccion == 0){
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Inicial:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Inicial: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($grado == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$grado." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupo == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupo."</td></tr>"; 
		}
		
		//////
		if($seccionf == 0){
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Final:</b> Todas&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=7><b>Secci&oacute;n Final: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccionf ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($gradof == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$gradof." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupof == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$grupof."</td></tr>"; 
		}
		
		
	}
		/*if($rs_['seccion']!=$seccion_anterior)
	{
	
		if($seccion == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n: Todas</b>&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}	
		if($grado == 0){
		$exportar.="<b>Grado: </b>Todos &nbsp;&nbsp;&nbsp;"; 
		}
	else
		{
		$exportar.="<b>Grado: </b>".$rs_['grado']." &nbsp;&nbsp;&nbsp;"; 
		}
	if($grupo == ''){
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo:</b> Todos</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>"; 
		}
	else
		{
		$exportar.="<b>&nbsp;&nbsp;&nbsp;Grupo: </b>".$rs_['grupo']."</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>"; 
		}
	}*/
		
		
		
		
		$exportar.="<tr><td colspan=1>&nbsp;</td><td colspan=4><b>Pregunta 1: </b>".mysql_result(mysql_query("select pregunta from test_preguntas where id_pregunta=$cruce_Pregunta1 ",$link),0,0)."</td></tr>";
		$p2="";
		$p2="<b>Pregunta 2: </b>".mysql_result(mysql_query("select pregunta from test_preguntas where id_pregunta=$cruce_Pregunta2 ",$link),0,0)."";
	
				
		$rstT_ = mysql_query ("SELECT id_opcion, opcion FROM test_opciones where id_pregunta=$cruce_Pregunta1",$link) 
		or die ("SELECT id_opcion, opcion FROM test_opciones where id_pregunta=$cruce_Pregunta1".mysql_error());
		$d=0;
		$arr_opcion[]=0;
		$exportar.="<tr><td>".$p2."</td>";
while($rsT_=mysql_fetch_array($rstT_))
  {    
  	  
  	$d= $d + 1; 

	$arr_opcion[$d]=$rsT_['id_opcion'];
	$exportar.= "<td><b>".$rsT_['opcion']."</b></td>";
  }
	$exportar.="</tr>";
	
	$arr2[]=0;
	$nombre2[]="";
	$a=0;
		$rstP_ = mysql_query ("SELECT id_opcion, opcion as opcionU FROM test_opciones where id_pregunta=$cruce_Pregunta2",$link) 
			or die ("SELECT id_opcion, opcion as opcionU FROM test_opciones where id_pregunta=$cruce_Pregunta2".mysql_error());
		while($rss_=mysql_fetch_array($rstP_))
	    {    $opcion=0;
			$a=$a+1;
			$exportar.="<tr>";
			$arr2[$a]= $rss_['id_opcion'];
			$nombre2[$a]=$rss_['opcionU'];
		
   }
   
   for($i=1; $i<=$d; $i++)
			{	
				$exportar.="<td><b>".$nombre2[$i]."</b></td>";
				for($j=1; $j<=$d; $j++){				
					$rstP2_ = mysql_query ("SELECT count(responde) as cuantos FROM test_respuestas, alumnos where
				alumnos.alumno=test_respuestas.responde $and and id_pregunta=$cruce_Pregunta1 and id_opcion =$arr_opcion[$i]
        and test_respuestas.responde in (select responde FROM test_respuestas, alumnos where
				alumnos.alumno=test_respuestas.responde $and and id_pregunta=$cruce_Pregunta2 and id_opcion =$arr2[$j] )",$link) 
					or die ("SELECT count(responde) as cuantos FROM test_respuestas, alumnos where
				alumnos.alumno=test_respuestas.responde $and and id_pregunta=$cruce_Pregunta1 and id_opcion =$arr_opcion[$i]
        and test_respuestas.responde in (select responde FROM test_respuestas, alumnos where
				alumnos.alumno=test_respuestas.responde $and and id_pregunta=$cruce_Pregunta2 and id_opcion =$arr2[$j] )".mysql_error());				
					while($rs2_=mysql_fetch_array($rstP2_))
					{ 
						$exportar.="<td>".$rs2_['cuantos']."</td>";
					}	
				}
				$exportar.="</tr>";	
			}	
   
	$exportar.="</tr>";	
			 
	

$preceptor_anterior=$rs_['preceptor'];
$familia_anterior=$rs_['familia'];
$alumno_anterior=$rs_['nombre'];
$fecha_anterior=$rs_['fechaterminado']; 
$seccion_anterior=$rs_['seccion'];
$pregunta_anterior=$rs_['pregunta'];  
$opcion_anterior=$rss_['opcionU'];
$test_anterior=$rs_['ntest'];
	}
  } 
  
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
   echo"";
   $ver_reporte='N';
}
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}


//PRECEPTORIAS POR PRECEPTOR
if($tipo=='preceptoriasA'){
$exportar="
<table class='reporte' border='1' width='600'>
	<tr>
		<th style='font-size:24px' colspan='100%'> Reporte Preceptorias Por Preceptor </th>
	</tr>";
$mes_ini= substr($fecha_ini, 5, 2);
$dia_ini= substr($fecha_ini, 8, 2);
$y_ini= substr($fecha_ini, 0, 4);
switch($mes_ini)
{
   case 01: $pmes="Enero"; break;
   case 02: $pmes="Febrero"; break;
   case 03: $pmes="Marzo"; break;
   case 04: $pmes="Abril"; break;
   case 05: $pmes="Mayo"; break;
   case 06: $pmes="Junio"; break;
   case 07: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

$mes_fin= substr($fecha_fin, 5, 2);
$dia_fin= substr($fecha_fin, 8, 2);
$y_fin= substr($fecha_fin, 0, 4);
switch($mes_fin){
   case 01: $smes="Enero"; break;
   case 02: $smes="Febrero"; break;
   case 03: $smes="Marzo"; break;
   case 04: $smes="Abril"; break;
   case 05: $smes="Mayo"; break;
   case 06: $smes="Junio"; break;
   case 07: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}

$exportar.="<tr>
				<td colspan='100%'><b>Rango de Fechas : </b> Del ".$dia_ini."-".$pmes."-".$y_ini." al ".$dia_fin."-".$smes."-".$y_fin." </td>
			</tr>";
$exportar.="<tr>
				<td colspan='100%'>&nbsp;</td>
			</tr>";

if($ver_reporte=='S'){

$rst_ = mysql_query ("SELECT distinct(alumnos.alumno), concat(alumnos.nombre, ' ', alumnos.apellido_paterno, ' ', alumnos.apellido_materno) as nombre, seccion, grado, grupo, preceptor FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno and activo='A' $and group by fecha, alumno order by seccion, grado, grupo, alumno",$link) or die ("SELECT distinct(alumnos.alumno), concat(alumnos.nombre, ' ', alumnos.apellido_paterno, ' ', alumnos.apellido_materno) as nombre, seccion, grado, grupo, preceptor FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno =preceptoria.alumno $and group by fecha, alumno order by seccion, grado, grupo, alumno".mysql_error());
$preceptor_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$sec_anterior=0;
$c = 0;
$d = 0;
$f = 0;
$totalP=0;
while($rs_=mysql_fetch_array($rst_)){
	$c = $c + 1;
	$d= $d+1;
	$f = $f + 1;
	if($c == 1){ // mod 02 sept
		$exportar.=

	  " 
<tr class='titulos_reporte' style='titulos_reporte'>	
	<td align='center' valign='top'><b>Nombre de alumnos </b></td>
	<td align='center' valign='top'><b>Grupo </b></td>
	<td align='center' valign='top'><b>Preceptor </b></td>
	<td align='center' valign='top'><b>TOTAL de preceptorias</b></td>
</tr>
<tr>
	<td colspan='100%'>&nbsp;</td>
</tr>";



	}
	
	if($rs_['alumno']!=$alumno_anterior){
		
		if($sec_anterior!=$rs_['seccion'])
		{
			$exportar.="<tr>";	
		$s=$rs_['seccion'];
		$exportar.="<td align='center' valign='top' colspan='100%' class='secciones' style='secciones'><b>".mysql_result(mysql_query("select nombre from secciones where seccion=$s ",$link),0,0)."</b></td>
				</tr>"; //Sacar el nombre de la seccion
			
		}
		
		$exportar.="<tr>";	
		$exportar.="<td align='left' valign='top'>".$rs_['nombre']." </td>";		
		$exportar.="<td align='left' valign='top'>".$rs_['grado']." &nbsp; ".$rs_['grupo']." </td>";
		
			if( $rs_['preceptor'] <> NULL) 
		{
		$prec=mysql_result(mysql_query("select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from personal where empleado=".$rs_['preceptor']." ",$link),0,0);
		}
		else
		$precep="Todos";
		$exportar.="<td align='left' valign='top'>".$prec."</td>";
		
		$nom=$rs_['alumno'];

		$rstF_=mysql_query("SELECT count(distinct(preceptoria)) as tot FROM preceptoria,alumnos, preceptoria_acuerdos WHERE fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno=preceptoria.alumno and alumnos.alumno=$nom and alumnos.activo='A' and fin = '1'",$link) or die ("SELECT count(distinct(preceptoria)) FROM preceptoria,alumnos where fecha between '$fecha_ini' and '$fecha_fin' and alumnos.alumno=preceptoria.alumno and alumnos.alumno=$nom  and alumnos.activo='A' and fin = '1'".mysql_error());
		while($rsF_=mysql_fetch_array($rstF_)){
			$tot=$rsF_['tot'];
			$totalP=$totalP+$tot;
	 	}
		$exportar.="<td align='center' valign='top'>".$tot." </td>";		
	
	}

$preceptor_anterior=$rs_['preceptor'];
$alumno_anterior=$rs_['nombre'];


$sec_anterior=$rs_[seccion];   
}
$exportar.="<tr><td align='right' colspan='100%'><b>Total: </b>".$totalP."</td></tr>";
$exportar.="</table>";
?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
echo "";
$ver_reporte='N';  
$seccion="";
$grado= 0;
$grupo= "";
$preceptor= 0;
} 
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}

//PRECEPTORIA POR GRUPO
if($tipo=='preceptoriasG'){
$exportar="
<table width='600' class='reporte'>
	<tr>
		<th style='font-size:24px' colspan='100%'> Reporte Preceptorias Por Grupo </th></tr>";
$mes_ini= substr($fecha_ini, 5, 2);
$mes_fin= substr($fecha_fin, 5, 2);

$mes_fin_= substr($fecha_fin, 5, 2);
//$yearA = mysql_result(mysql_query("select now(); ",$link),0,0);

$y_ini=substr($fecha_ini, 0, 4);
$y_fin=substr($fecha_fin, 0, 4);
$dia_ini=substr($fecha_ini, 8);
$dia_fin=substr($fecha_fin, 8);
//$diaI='01';
$fecha_inicial = $y_ini."-".$mes_ini."-".$dia_ini;

/*echo" <script language='javascript'>alert($mes_fin);</script>";*/
$fecha_final= $y_fin."-".$mes_fin."-".$dia_fin;
switch($mes_ini)
{
   case 1: $pmes="Enero"; break;
   case 2: $pmes="Febrero"; break;
   case 3: $pmes="Marzo"; break;
   case 4: $pmes="Abril"; break;
   case 5: $pmes="Mayo"; break;
   case 6: $pmes="Junio"; break;
   case 7: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

switch($mes_fin)
{
   case 1: $smes="Enero"; break;
   case 2: $smes="Febrero"; break;
   case 3: $smes="Marzo"; break;
   case 4: $smes="Abril"; break;
   case 5: $smes="Mayo"; break;
   case 6: $smes="Junio"; break;
   case 7: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}

$preceptoriaCiclo=substr($fecha_inicial,0,4);
if($mes_inicial_periodo_actual>substr($fecha_inicial,5,2))
	$preceptoriaCiclo--;
if($mes_fin<$mes_ini)
	$mes_fin+=12;
$mesesReporte=($mes_fin-$mes_ini)+1;

$exportar.="<tr><td colspan='100%'><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$dia_ini."-".$pmes."-".$y_ini."&nbsp;&nbsp;&nbsp;al&nbsp; ".$dia_fin."-".$smes."-".$y_fin." </td></tr>


<tr><td><b>Ciclo:</b> $preceptoriaCiclo</td></tr><tr><td><b>Meses:</b> $mesesReporte</td></tr>";
/*
if($seccion == 0){
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n: Todas</b>&nbsp;&nbsp;&nbsp;"; 
		}
		else
		{
		$exportar.="<tr><td colspan=6><b>Secci&oacute;n: </b>".mysql_result(mysql_query("select nombre from secciones where seccion=$seccion ",$link),0,0)."&nbsp;&nbsp;&nbsp;"; 
		}*/


$exportar.="<tr><td colspan=colspan='100%'>&nbsp;</td></tr>";
if($ver_reporte=='S'){
$rst_ = mysql_query ("SELECT 
							distinct(alumnos.alumno), concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) as nombre,
							concat(grado, ' ', grupo) as grupoC, 
							seccion, 
							grado, 
							grupo, 
							preceptor 
						FROM 
							alumnos 
						WHERE 
							activo='A' 
							$and 
						GROUP BY 
							alumno 
						ORDER BY
							seccion, grado, grupo, alumno",$link) or die ("SELECT distinct(alumnos.alumno), concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) as nombre,
concat(grado, ' ', grupo) as grupoC, seccion, grado, grupo, preceptor FROM alumnos where activo='A' $and 
group by alumno order by seccion, grado, grupo, alumno".mysql_error());
$preceptor_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$grupo_anterior="";
$seccion_anterior="";
$c = 0;
$d = 0;
$f = 0;
$totalP=0;
$totalTotal=0;
  while($rs_=mysql_fetch_array($rst_)){
  $c = $c + 1;
  $d= $d+1;
  $f = $f + 1;
	if($c == 1){
	  $exportar.=
	  " <tr>
		<td align='center' rowspan='2' valign='bottom'><b>Grupo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' colspan='5' bgcolor='$baner'><b><font color='#FFFFFF'>".$pmes." &nbsp;- &nbsp;" .$smes." </font> </b> </td></tr>
		<tr>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>Total</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>Meta </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<!--<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>%</font></b></td>-->
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>No. Alumnos </td>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>% Avance&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		</tr>
		";
		}
if($rs_['seccion']!=$seccion_anterior){
	$s=$rs_['seccion'];
	$exportar.="<tr>
				<td align='center' valign='top' bgcolor='$seccion_color'colspan='100%' ><b><font color='#FFFFFF'>".mysql_result(mysql_query("select nombre from secciones where seccion=".$rs_['seccion']." ",$link),0,0)."</font></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
				</tr>";
}
	  if($rs_['grupoC']!=$grupo_anterior){
		$exportar.="<tr>
					<td align='left' valign='top' bgcolor='$fondo_n'>".$rs_['grupoC']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";	
		$grupoC=$rs_['grupoC'];	
		
		/////////////////
		$rstG = mysql_query ("SELECT 
									count(distinct(alumnos.alumno)) as total 
								FROM 
									alumnos, 
									grupos
								WHERE
									alumnos.seccion=grupos.seccion 
									and alumnos.grado=grupos.grado 
									and alumnos.grupo=grupos.grupo 
									and alumnos.seccion=$s 
									and concat(alumnos.grado,' ',alumnos.grupo)='$grupoC' 
									and grupos.ciclo=$ciclo 
									and alumnos.activo='A' 
									and alumnos.nuevo_ingreso!='P'
									",$link) or die ("SELECT count(distinct(alumnos.alumno)) as total FROM alumnos, grupos
where alumnos.seccion=grupos.seccion and alumnos.grado=grupos.grado and alumnos.grupo=grupos.grupo and alumnos.seccion=$s and concat(alumnos.grado,' ',alumnos.grupo)='$grupoC' and grupos.ciclo=$ciclo and alumnos.activo='A' and nuevo_ingreso!='P' ".mysql_error());
			$totalAlumnos=0;
			while($rsG_=mysql_fetch_array($rstG)){
				$totalAlumnos=$rsG_['total'];
			}
		///////////////
		
		
		$nom= $rs_['alumno'];
		$rstF_ = mysql_query ("SELECT 
										distinct(concat(preceptoria.alumno,' ',preceptoria)), 
										preceptoria.alumno, 
										alumnos.grado, 
										alumnos.grupo 
									FROM 
										preceptoria, 
										alumnos 
									WHERE
										preceptoria.alumno=alumnos.alumno 
										and fin = '1'
										and preceptoria.ciclo=$preceptoriaCiclo 
										and alumnos.seccion=$rs_[seccion] and concat(grado, ' ', grupo)='$grupoC' 
										and fecha between '$fecha_inicial' 
										and '$fecha_final' 
										and activo='A'
									ORDER BY
										fecha ",$link) or die ("SELECT distinct(concat(preceptoria.alumno,' ',preceptoria)), preceptoria.alumno, alumnos.grado, alumnos.grupo FROM preceptoria, alumnos where where preceptoria.alumno=alumnos.alumno and preceptoria.ciclo=$preceptoriaCiclo and alumnos.seccion=$rs_[seccion] and concat(grado, ' ', grupo)='$grupoC' and fecha between '$fecha_inicial' and '$fecha_final' order by fecha ".mysql_error());
			$totalPrec=0;
			while($rsF_=mysql_fetch_array($rstF_)){
				$totalPrec=$totalPrec+1;
				$totalTotal++;
			}
			$exportar.="<td align='center' valign='top'>".$totalPrec." </td>";
			$mes_ini_rel=fMesRel($mes_ini);
			$mes_fin_rel=fMesRel($mes_fin_);
			
			$between1=$preceptoriaCiclo*100+$mes_ini_rel;
			$between2=$preceptoriaCiclo*100+$mes_fin_rel;
			$se=$rs_[seccion];
			$gra=$rs_[grado];
			$gru=$rs_[grupo];

			//echo "select sum(meta) from preceptoria_grupos, mes_relativo where ciclo=$preceptoriaCiclo and seccion=$rs_[seccion] and grado=$rs_[grado] and grupo='$rs_[grupo]' and ciclo*100+mes_relativo.mes_relativo between $preceptoriaCiclo*100+".fMesRel($mes_ini)." and $preceptoriaCiclo*100+".fMesRel($mes_fin)." and preceptoria_grupos.mes=mes_relativo.mes<br>";
			$rstM_=mysql_query ("select sum(meta) as meta from preceptoria_grupos, mes_relativo where ciclo=$preceptoriaCiclo and seccion=$se and grado=$gra and grupo='$gru' and preceptoria_grupos.mes between $mes_ini_rel and $mes_fin_rel
and preceptoria_grupos.mes=mes_relativo.mes",$link) or die ("select sum(meta) as meta from preceptoria_grupos, mes_relativo where ciclo=$preceptoriaCiclo and seccion=$se and grado=$gra and grupo='$gru' and preceptoria_grupos.mes between $mes_ini_rel and $mes_fin_rel
and preceptoria_grupos.mes=mes_relativo.mes".mysql_error());
			while($rsM_=mysql_fetch_array($rstM_)){
				$metass=$rsM_[meta];
			}
			if($metass=="") $metass=0;
			//$totalM=0;
			//while($rsM_=mysql_fetch_array($rstM_))
				//$totalM=$rsM_['meta']*$mesesReporte;			
			$exportar.="<td align='center' valign='top'>".$totalAlumnos*$metass." </td>";		
			$porc=($totalPrec*100);
			if($metass==0) $porcentaje= 0;
			else $porcentaje=($porc/$metass);
			if($porcentaje=="")
				$porcentaje=0;
			//$p=round(($porcentaje * 100) / 100);
			$p=round((($porcentaje * 100) / 100),2);
			//$exportar.="<td align='center' valign='top'>".$p." </td>";	
			$exportar.="<td align='center' valign='top'>".$totalAlumnos." </td>";
			$tmetass=$totalAlumnos*$metass;
			//$avance=($totalPrec*100)/$tmetass;
			
			if(empty($totalPrec) || empty($totalAlumnos))
	  		{
    			$avance = 0; 
			}
			else
			{
			if($metass==0) $avance = 0;
			else $avance=round(($totalPrec*100)/($totalAlumnos*$metass),2);
			}
			$exportar.="<td align='center' valign='top'>".$avance." </td>";
				
			//$exportar.="<tr><td colspan=6>&nbsp;</td></tr>";
	}
//	}
$preceptor_anterior=$rs_['preceptor'];
$alumno_anterior=$rs_['nombre'];
//$fecha_anterior=$rsA_['fecha']; 
$grupo_anterior=$rs_['grupoC'];   
$seccion_anterior=$rs_['seccion'];   
  }
  $exportar.="<tr><td colspan=6>&nbsp;</td></tr>";
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
  echo"";
 $ver_reporte='N';  
 $seccion="";
 $grado= 0;
 $grupo= "";
 $preceptor= 0;
}
//echo $totalTotal;
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}


////TOTAL ENTREVISTAS DE PADRES

if($tipo=='entrevistasp'){
$exportar="
<table width='600' class='reporte'>
	<tr>
		<th style='font-size:24px' colspan='100%'> Reporte Total Entrevistas de Padres </th>
	</tr>";
$mes_ini= substr($fecha_ini, 5, 2);
$mes_fin= substr($fecha_fin, 5, 2);

$mes_fin_= substr($fecha_fin, 5, 2);
//$yearA = mysql_result(mysql_query("select now(); ",$link),0,0);

$y_ini=substr($fecha_ini, 0, 4);
$y_fin=substr($fecha_fin, 0, 4);
$dia_ini=substr($fecha_ini, 8);
$dia_fin=substr($fecha_fin, 8);
//$diaI='01';
$fecha_inicial = $y_ini."-".$mes_ini."-".$dia_ini;

/*echo" <script language='javascript'>alert($mes_fin);</script>";*/

$fecha_final= $y_fin."-".$mes_fin."-".$dia_fin;
switch($mes_ini)
{
   case 1: $pmes="Enero"; break;
   case 2: $pmes="Febrero"; break;
   case 3: $pmes="Marzo"; break;
   case 4: $pmes="Abril"; break;
   case 5: $pmes="Mayo"; break;
   case 6: $pmes="Junio"; break;
   case 7: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

switch($mes_fin)
{
   case 1: $smes="Enero"; break;
   case 2: $smes="Febrero"; break;
   case 3: $smes="Marzo"; break;
   case 4: $smes="Abril"; break;
   case 5: $smes="Mayo"; break;
   case 6: $smes="Junio"; break;
   case 7: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}

$preceptoriaCiclo=substr($fecha_inicial,0,4);
if($mes_inicial_periodo_actual>substr($fecha_inicial,5,2))
	$preceptoriaCiclo--;
if($mes_fin<$mes_ini)
	$mes_fin+=12;
$mesesReporte=($mes_fin-$mes_ini)+1;
$exportar.="
	<tr>
		<td colspan='100%'><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$dia_ini."-".$pmes."-".$y_ini."&nbsp;&nbsp;&nbsp;al&nbsp; ".$dia_fin."-".$smes."-".$y_fin." </td>
	</tr>
	<tr>
		<td colspan='2'><b>Ciclo:</b> $preceptoriaCiclo</td>
	</tr>
	<tr>
		<td colspan='2'><b>Meses:</b> $mesesReporte</td>
	</tr>";


$exportar.="<tr><td colspan='100%'>&nbsp;</td></tr>";
if($ver_reporte=='S'){



$rst_ = mysql_query ("SELECT 
							distinct(alumnos.alumno), 
							concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) as nombre,
							concat(grado, ' ', grupo) as grupoC, 
							seccion, 
							grado, 
							grupo, 
							preceptor 
						FROM 
							alumnos 
						WHERE 
							activo='A'
							and nuevo_ingreso != 'P'
							$and 
						GROUP BY
							alumno 
						ORDER BY
							seccion, 
							grado, 
							grupo, 
							alumno",$link) or die ("SELECT 
							distinct(alumnos.alumno), 
							concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) as nombre,
							concat(grado, ' ', grupo) as grupoC, 
							seccion, 
							grado, 
							grupo, 
							preceptor 
						FROM 
							alumnos 
						WHERE 
							activo='A'
							and nuevo_ingreso != 'P'
							$and 
						GROUP BY
							alumno 
						ORDER BY
							seccion, 
							grado, 
							grupo, 
							alumno".mysql_error());
$preceptor_anterior=0;
$alumno_anterior="";
$fecha_anterior="";
$grupo_anterior="";
$seccion_anterior="";
$c = 0;
$d = 0;
$f = 0;
$totalP=0;
$totalTotal=0;
  while($rs_=mysql_fetch_array($rst_)){
  $c = $c + 1;
  $d= $d+1;
  $f = $f + 1;
	if($c == 1){
		
	  $exportar.=
	  " 
	 <tr>
		<td align='center' rowspan='2' valign='bottom'><b>Grupo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' colspan='4' bgcolor='$baner'><b><font color='#FFFFFF'>".$pmes." &nbsp;- &nbsp;" .$smes." </font> </b> </td>
	</tr>
	<tr>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>No. Alumnos por grupo </td>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>Asistencias Ambos&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>Asistencias Padre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>Asistencias Madre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td align='center' valign='top' bgcolor='$baner'><b><font color='#FFFFFF'>Total Entrevistas&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
	</tr>
		";
		}
if($rs_['seccion']!=$seccion_anterior){
	$s=$rs_['seccion'];
	$exportar.="<tr>
					<td align='center' valign='top' bgcolor='$seccion_color' colspan='100%' ><b><font color='#FFFFFF'>".mysql_result(mysql_query("select nombre from secciones where seccion=".$rs_['seccion']." ",$link),0,0)."</font></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
				</tr>";
}
	  if($rs_['grupoC']!=$grupo_anterior){
		$exportar.="
	<tr>
		<td align='left' valign='top' bgcolor='$fondo_n'>".$rs_['grupoC']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>";	
		$grupoC=$rs_['grupoC'];	
		
		///////////////// 

		$rstG = mysql_query ("
		SELECT 
				count(distinct(alumnos.alumno)) as total 
			FROM 
				alumnos, grupos
			WHERE 
				alumnos.seccion=grupos.seccion 
				and 
				alumnos.grado=grupos.grado 
				and 
				alumnos.grupo=grupos.grupo 
				and 
				alumnos.seccion=$s 
				and 
				concat(alumnos.grado,' ',alumnos.grupo)='$grupoC' 
				and 
				grupos.ciclo=$ciclo 
				and 
				alumnos.activo='A' 
				and alumnos.nuevo_ingreso!='P' ",$link) or die ("SELECT 
				count(distinct(alumnos.alumno)) as total 
			FROM 
				alumnos, grupos
			WHERE 
				alumnos.seccion=grupos.seccion 
				and 
				alumnos.grado=grupos.grado 
				and 
				alumnos.grupo=grupos.grupo 
				and 
				alumnos.seccion=$s 
				and 
				concat(alumnos.grado,' ',alumnos.grupo)='$grupoC' 
				and 
				grupos.ciclo=$ciclo 
				and 
				alumnos.activo='A' 
				and alumnos.nuevo_ingreso!='P'".mysql_error());
			$totalAlumnos=0;
			while($rsG_=mysql_fetch_array($rstG)){
				$totalAlumnos=$rsG_['total'];
			}
		///////////////
		
		
		$nom= $rs_['alumno'];
		
			$mes_ini_rel=fMesRel($mes_ini);
			$mes_fin_rel=fMesRel($mes_fin_);			
			
			$se=$rs_[seccion];
			$gra=$rs_[grado];
			$gru=$rs_[grupo];
								
			$exportar.="<td align='center' valign='top'>".$totalAlumnos." </td>";
			//		
			$rstF_ = mysql_query ("SELECT 
											count(fec) as totalA 
										FROM 
											preceptoria_acuerdos,
											alumnos 
										WHERE
											fec between '$fecha_inicial' and '$fecha_final'
											and alumnos.alumno =preceptoria_acuerdos.alumno 
											and concat(grado,' ',grupo)='$grupoC' 
											and alumnos.seccion=$s 
											and padre=1 and madre=1
											and activo='A'
											and alumnos.nuevo_ingreso!='P'",$link) or die ("SELECT 
											count(fec) as totalA 
										FROM 
											preceptoria_acuerdos,
											alumnos 
										WHERE
											fec between '$fecha_inicial' and '$fecha_final'
											and alumnos.alumno =preceptoria_acuerdos.alumno 
											and concat(grado,' ',grupo)='$grupoC' 
											and alumnos.seccion=$s 
											and padre=1 and madre=1
											and activo='A'
											and alumnos.nuevo_ingreso!='P'".mysql_error());
			$totalAmbos=0;
			while($rsF_=mysql_fetch_array($rstF_)){
				$totalAmbos=$rsF_['totalA'];				
			}	
			
			//
			$rstF_ = mysql_query ("SELECT 
										count(fec) as totalA 
									FROM 
										preceptoria_acuerdos,
										alumnos 
									WHERE
										fec between '$fecha_inicial' and '$fecha_final'
										and alumnos.alumno =preceptoria_acuerdos.alumno 
										and concat(grado,' ',grupo)='$grupoC' 
										and alumnos.seccion=$s 
										and padre=1 and madre=0
										and activo='A'
										and alumnos.nuevo_ingreso!='P'",$link) or die ("SSELECT 
										count(fec) as totalA 
									FROM 
										preceptoria_acuerdos,
										alumnos 
									WHERE
										fec between '$fecha_inicial' and '$fecha_final'
										and alumnos.alumno =preceptoria_acuerdos.alumno 
										and concat(grado,' ',grupo)='$grupoC' 
										and alumnos.seccion=$s 
										and padre=1 and madre=0
										and activo='A'
										and alumnos.nuevo_ingreso!='P'".mysql_error());
			$totalP=0;
			while($rsF_=mysql_fetch_array($rstF_)){
				$totalP=$rsF_['totalA'];				
			}
						
			//
			$rstF_ = mysql_query ("SELECT 
										count(fec) as totalA 
									FROM 
										preceptoria_acuerdos,
										alumnos 
									where fec between '$fecha_inicial' and '$fecha_final'
										and alumnos.alumno =preceptoria_acuerdos.alumno 
										and concat(grado,' ',grupo)='$grupoC' 
										and alumnos.seccion=$s and padre=0 and madre=1 
										and activo='A'
										and alumnos.nuevo_ingreso!='P'",$link) or die ("SELECT 
										count(fec) as totalA 
									FROM 
										preceptoria_acuerdos,
										alumnos 
									where fec between '$fecha_inicial' and '$fecha_final'
										and alumnos.alumno =preceptoria_acuerdos.alumno 
										and concat(grado,' ',grupo)='$grupoC' 
										and alumnos.seccion=$s and padre=0 and madre=1 
										and activo='A'
										and alumnos.nuevo_ingreso!='P'".mysql_error());
			$totalM=0;
			while($rsF_=mysql_fetch_array($rstF_)){
				$totalM=$rsF_['totalA'];				
			}
			
			$exportar.="<td align='center' valign='top'>".$totalAmbos." </td>";
			
			$exportar.="<td align='center' valign='top'>".$totalP." </td>";
			
			$exportar.="<td align='center' valign='top'>".$totalM." </td>";
			$totalEn=$totalAmbos+$totalP+$totalM;
			$exportar.="<td align='center' valign='top' bgcolor='$fondo_n'><b>".$totalEn." </b></td></tr>";

	}
$preceptor_anterior=$rs_['preceptor'];
$alumno_anterior=$rs_['nombre'];
//$fecha_anterior=$rsA_['fecha']; 
$grupo_anterior=$rs_['grupoC'];   
$seccion_anterior=$rs_['seccion'];   
  }
  //$exportar.="<tr><td colspan=4>&nbsp;</td></tr>";
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
  echo"";
 $ver_reporte='N';  
 $seccion="";
 $grado= 0;
 $grupo= "";
 $preceptor= 0;
}
//echo $totalTotal;
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}



////PREGUNTAS ENCUESTAS

if($tipo=='encuestas'){




$exportar="
<table	class='reporte'>
	<tr>
		<th style='font-size:24px' colspan=14> Reporte Encuestas </th>
	</tr>";


$mes_ini= substr($fecha_ini, 5, 2);
$mes_fin= substr($fecha_fin, 5, 2);

$mes_fin_= substr($fecha_fin, 5, 2);

$y_ini=substr($fecha_ini, 0, 4);
$y_fin=substr($fecha_fin, 0, 4);
$dia_ini=substr($fecha_ini, 8);
$dia_fin=substr($fecha_fin, 8);
$fecha_inicial = $y_ini."-".$mes_ini."-01";

$fecha_final= $y_fin."-".$mes_fin."-31";
switch($mes_ini)
{
   case 1: $pmes="Enero"; break;
   case 2: $pmes="Febrero"; break;
   case 3: $pmes="Marzo"; break;
   case 4: $pmes="Abril"; break;
   case 5: $pmes="Mayo"; break;
   case 6: $pmes="Junio"; break;
   case 7: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

switch($mes_fin)
{
   case 1: $smes="Enero"; break;
   case 2: $smes="Febrero"; break;
   case 3: $smes="Marzo"; break;
   case 4: $smes="Abril"; break;
   case 5: $smes="Mayo"; break;
   case 6: $smes="Junio"; break;
   case 7: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}

$preceptoriaCiclo=substr($fecha_inicial,0,4);
if($mes_inicial_periodo_actual>substr($fecha_inicial,5,2))
	$preceptoriaCiclo--;
if($mes_fin<$mes_ini)
	$mes_fin+=12;
$mesesReporte=($mes_fin-$mes_ini)+1;

$exportar.="<tr><td colspan=14><b>Rango de Fechas:</b> ".$pmes." - ".$smes." </td></tr>";

$exportar.="<tr><td colspan=14>&nbsp;</td></tr>";

if($ver_reporte=='S'){
$rst_ = mysql_query ("SELECT id_test FROM test where nombre = 'Encuesta'",$link) or die ("SELECT id_test FROM test where nombre = 'Encuesta'".mysql_error());
$id_Encuesta=0;
 	while($rs_=mysql_fetch_array($rst_))
	{
  		$id_Encuesta=$rs_['id_test'];
	}

	$rst = mysql_query ("SELECT pregunta FROM test_preguntas where id_test=$id_Encuesta order by orden",$link) or die ("SELECT pregunta FROM test_preguntas where id_test=$id_Encuesta order by orden".mysql_error());
	$exportar.=" <tr>";
	$c=0;
 	while($rs=mysql_fetch_array($rst))
	{
		$c=$c+1;
  		$exportar.=" <td align='center' valign='bottom' bgcolor='$fondo_n'  style='font-size:14px'><b>".$rs['pregunta']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>";
	}
	$exportar.=" </tr>";
			
	
	
		

		/////////////////
		$responde="";
		$orden="";
		$respuesta="";
		$idOpcion="";
		$pregunta="";
		
		$rstG = mysql_query ("SELECT DISTINCT id_respuesta, responde, orden, pregunta, respuesta, test_respuestas.id_opcion FROM
test_preguntas, test_publicacion, test_respuestas where test_preguntas.id_test=test_preguntas.id_test and test_preguntas.id_pregunta=test_respuestas.id_pregunta and test_preguntas.id_test=$id_Encuesta and (test_publicacion.ciclo=$periodo_actual or test_publicacion.ciclo=$periodo_actual-1 or test_publicacion.ciclo=$periodo_actual-2) and fecha between '$fecha_inicial' and '$fecha_final' order by id_respuesta, responde, orden ",$link) or die ("SELECT DISTINCT responde, orden, pregunta, respuesta, test_respuestas.id_opcion FROM
test_preguntas, test_publicacion, test_respuestas where test_preguntas.id_test=test_preguntas.id_test and test_preguntas.id_pregunta=test_respuestas.id_pregunta and test_preguntas.id_test=$id_Encuesta and (test_publicacion.ciclo=$periodo_actual or test_publicacion.ciclo=$periodo_actual-1 or test_publicacion.ciclo=$periodo_actual-2) and fecha between '$fecha_inicial' and '$fecha_final' order by responde, orden  ".mysql_error());
			$responde_ant="";
			$d=0;
			$e=0;
			
			while($rsG_=mysql_fetch_array($rstG))
			{
				$d=$d+1;
				$e=$e+1;
				$responde=$rsG_['responde'];
				$orden=$rsG_['orden'];
				$respuesta=$rsG_['respuesta'];
				$idOpcion=$rsG_['id_opcion'];
				$pregunta=$rsG_['pregunta'];
				if($idOpcion>0)
				{
					$rst = mysql_query ("SELECT opcion FROM test_opciones where id_opcion=$idOpcion ",$link) or die ("SELECT opcion FROM test_opciones where id_opcion=$idOpcion ".mysql_error());
					while($rs_=mysql_fetch_array($rst))
					{
						$respuesta=$rs_['opcion'];
					}					
				}
				
				if($d=0)		
				{	
					$exportar.=" <tr>";
				}
				
		  	//$exportar.="<td align='left' valign='bottom' >&nbsp;&nbsp;&nbsp; $respuesta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			$respuesta_=$respuesta;
			$exportar.="<td align='left' valign='bottom' style='font-size:12px'> $respuesta_ </td>";
			
			$registro['campo'];
				if($e==$c)	
				{
					$exportar.=" </tr>";
					$e=0;
					$d=0;
				}
			}
		///////////////
		

  $exportar.="<tr><td colspan=14>&nbsp;</td></tr>";
  $exportar.="</table>";
  ?>
  <div name='' id='seleccion'>  <?
  echo $exportar;
  ?>
  </div>
  <?
}
//echo $totalTotal;
echo "<script language='JavaScript'>
		document.getElementById('ver_reporte').value='N';
	  </script>";
}







?>
<form action='exportar.php' target='_blank' method='post'>
<p>&nbsp;</p><p>&nbsp;</p>
  
  <div align="left">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <?
   	if($tipo!='kardex')
	{
   ?>
    <input type='submit' value='EXPORTAR A EXCEL'>
    <input id='contenido' name='contenido' type='hidden' value="<?=$exportar;?>">
    <input type='hidden' name='nombre_def' value='reporte.xls'>
	<?
	}
	else
	{
	?>
	
	<input type='submit' value='EXPORTAR A EXCEL'>
    <input id='contenido' name='contenido' type='hidden' value="<?=$exportark;?>">
    <input type='hidden' name='nombre_def' value='kardex.doc'>
	<?
	}
	?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="button" type="button" onclick="imprSelec('seleccion')" value="  IMPRIMIR  " />
  </div>

  <div align="center"></div>
</form>


<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript">
function LimpiarCombo(combo){
	while(combo.length > 0){
		combo.remove(combo.length-1);
	}
}
function LlenarCombo(json, combo){
	combo.options[0] = new Option('Selecciona un item', '');
	for(var i=0;i<json.length;i++){
		combo.options[combo.length] = new Option(json[i].data, json[i].id);
	}
}
function SeleccionandoCombo(combo1, combo2){
	combo2 = document.getElementById(combo2); //con jquery: $("#"+combo2)[0];
	LimpiarCombo(combo2);
	if(combo1.options[combo1.selectedIndex].value != ""){
		combo1.disabled = true;
		combo2.disabled = true;
		alert(combo1.options[combo1.selectedIndex].value);
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'ajax.php',
			data: {valor: combo1.options[combo1.selectedIndex].value},
			success: function(json){
				LlenarCombo(json, combo2);
				combo1.disabled = false;
				combo2.disabled = false;
				
 
			}
		});
	}
}
</script>
</body>
</html>