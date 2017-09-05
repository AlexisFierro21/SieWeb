<? session_start();
include('../config.php');
include('../functions.php');
  ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252"> 
<title>Expediente Digital</title>
</head>
<script language='JavaScript' type='text/JavaScript'><!--
function pesta_na(activo,ini,fin)
	{
		for(x=ini;x<=fin;x++){
			if (x==activo)
			{
			 document.getElementById('t'+x).style.background='#FFFFFF';
			 document.getElementById('t'+x).style.color='#000000';
			 document.getElementById('d'+x).style.display = 'inline';
			}	
			else
			{
			 document.getElementById('t'+x).style.background='#000000';
			 document.getElementById('t'+x).style.color='#FFFFFF';
			 document.getElementById('d'+x).style.display= 'none';
			}	
		}	
	}
 var almn=0;
function cambia_alumno(value)
	{ almn=value;
  <?
    $datos_alumno='';
    $datos_familia='';
	$boleta='';
	$test='';
	$preceptoria='';
	//$areas_valor='';
	//$plan_mejora='';
	$display='';
	$echo='';
	$echo_='';
  if(!empty($_GET['administra_test']))
  { $datos_alumno=" src='formularios.php?tabla=expediente_datos&tipo=alumnos&administra_test=S&id=1&agr_modif_borr=modificar' ";
    $datos_familia=" src='formularios.php?tabla=expediente_datos&tipo=familias&administra_test=S&id=1&agr_modif_borr=modificar' ";
	$boleta=" src='buscaAlumno.php?muestra=boleta' ";
	$test=" src='tablas.php?tabla=test&administra_test=S&preceptoria=S&administra_test=S' ";
	$preceptoria=" src='' "; //tablas.php?tabla=areas_valor&administra_test=S
	//$plan_mejora=" src='tablas.php?tabla=plan_mejora&administra_test=S' ";
	$display="";
	$echo_="<script language='javascript'> pesta_na(1,1,5); </script>";
  }
  elseif(!empty($_GET['preceptor']))
  { $echo="Alumno : <select onChange='cambia_alumno(this.value);'><option></option>";
    $resul_t= mysql_query("SELECT CONCAT_WS(' ',nombre,apellido_paterno,apellido_materno) as n,alumno from alumnos where preceptor=".$_SESSION["clave"]." and activo='A'",$link) or die(mysql_error());
    while($ro_=mysql_fetch_array($resul_t))$echo.="<option value='".$ro_['alumno']."'>".$ro_['n']."</option>";
	$echo.="</select> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";
	$display="style='display:none'";
	$echo_="<script language='javascript'> pesta_na(1,1,6);  document.getElementById('encabezado').style.display='none'; document.getElementById('d1').style.display='none'; </script>";
	echo "
		document.getElementById('encabezado').style.display='inline'; 
		document.getElementById('btn').style.display='inline';  
		pesta_na(1,1,6); document.getElementById('d1').style.display='inline';
		document.getElementById('ifrm1').src='formularios.php?tabla=expediente_datos&tipo=alumnos&id=1&alumno='+value+'&agr_modif_borr=modificar';
		document.getElementById('ifrm2').src='formularios.php?tabla=expediente_datos&tipo=familias&id=1&alumno='+value+'&agr_modif_borr=modificar'; 
		document.getElementById('ifrm3').src='../boletas.php?alumnoN='+value;
		document.getElementById('ifrm4').src='tablas.php?tabla=test&preceptor=S&alumno='+value; 
		document.getElementById('ifrm5').src='captura_preceptoria.php?alumno='+value; 
		document.getElementById('t_abre').style.display='inline';";
//document.getElementById('ifrm3').src='formularios.php?tabla=areas_valor_detalle&id=1&alumno='+value+'&agr_modif_borr=modificar';
//formularios.php?tabla=plan_mejora_descripcion&id=1&alumno='+value+'&agr_modif_borr=modificar 
  }
  else
  { $echo_="<script language='javascript'>document.getElementById('encabezado').style.display='none'; document.getElementById('d5').style.display='inline'; 
document.getElementById('ifrm5').src='tablas.php?tabla=test';</script>";
  }?>	
	}
--></script>

<body><?=$echo;?><input type='button' id="btn" name="btn" value='Ficha de Impresi&oacute;n' style="display:none;" onClick="document.getElementById('ifrm7').src='formularios.php?ficha=s&alumno='+almn; pesta_na(6,1,6);">
<table width="915" align="center">
  <tr id="encabezado" name="encabezado">
  	<th id="t1" onClick="pesta_na(1,1,5);" style="cursor:hand; cursor:pointer">Datos Alumno</th>
  	<th id="t2" onClick="pesta_na(2,1,5);" style="cursor:hand; cursor:pointer">Datos Familia</th>
  	<th id="t3" onClick="pesta_na(3,1,5);" style="cursor:hand; cursor:pointer">Boleta de Calificaciones</th>
    <th id="t4" onClick="pesta_na(4,1,5);" style="cursor:hand; cursor:pointer">Test</th>
    <th id="t5" onClick="pesta_na(5,1,5);" style="cursor:hand; cursor:pointer">Captura de Preceptor&iacute;a</th>
  </tr> 
  <tr>
  	<th valign="middle" colspan="100%"> 
     <div id="d1" style="display:none"><iframe id="ifrm1" name="ifrm1" width="910" height="427" <?=$datos_alumno;?>></iframe></div>
    <div id="d2" style="display:none"><iframe id="ifrm2" name="ifrm2" width="910" height="427" <?=$datos_familia;?>></iframe></div>
     <div id="d3" style="display:none"><iframe id="ifrm3" name="ifrm3" width="910" height="427" <?=$boleta;?>></iframe></div>
     <div id="d4" style="display:none"><iframe id="ifrm4" name="ifrm4" width="910" height="427" <?=$test;?>></iframe></div>
     <div id="d5" style="display:none"><iframe id="ifrm5" name="ifrm5" width="910" height="427" <?=$preceptoria;?>></iframe></div>
     <div id="d6" style="display:none"><iframe id="ifrm6" name="ifrm6" width="910" height="427"></iframe></div></th>
   </tr>
</table><div id="t7"></div>
<?=$echo_;?>
</body>
</html>
