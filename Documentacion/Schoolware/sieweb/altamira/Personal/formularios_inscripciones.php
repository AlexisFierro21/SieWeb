<? session_start();
include('../config.php');
include('../functions.php');
//Si hay se colectan los datos de $_POST para generar el query
if(!empty($_POST['tabla']))
{ if($_POST['agr_modif_borr']=='borrar') $sql="DELETE FROM ".$_POST['tabla']." WHERE ".$_POST['campoid']."= ".$_POST['id']." ";
  else
  {   $campos = explode( ",", $_POST['campos'] );
      if($_POST['agr_modif_borr']=='agregar')
	  { $sql="INSERT INTO ".$_POST['tabla']." (".$_POST['campoid']." ";
	    foreach($campos as $campo) $sql.=",$campo";
	    $sql.=") VALUES (NULL";
	    foreach($campos as $campo) $sql.=",'".$_POST[$campo]."'";
	    $sql.=")";
	  }
  	  if($_POST['agr_modif_borr']=='modificar')
	  { $sql="UPDATE ".$_POST['tabla']." SET ".$_POST['campoid']."=".$_POST['id']." ";
	    foreach($campos as $campo) if($campo!="") $sql.=", $campo='".$_POST[$campo]."'";
	    $sql.=" where ".$_POST['campoid']."=".$_POST['id'];
	  }
  }
  $result=mysql_query($sql,$link)or die(mysql_error());
}//Si hay se colectan los datos de $_GET para llenar el formulario
if(!empty($_GET['tabla']))
{ $action='formularios_inscripciones.php';
  $t_m_campo='';
  switch($_GET['tabla'])
  { case 'inscripciones_pasos': $campoid="id_paso";
		 $campos="orden,tipo,titulo,repetir,al_ingreso,necesita_aceptar,texto_aceptacion,texto_rechazo,texto"; break;
    case 'secciones_reinscripcion': $campoid="id_sec_ciclo";
		 $campos="ciclo,seccion,fecha_ini,fecha_fin"; break;
    case 'inscripciones_alumnos': $campoid="id_inscripcion";
		 $campos="comentario"; break;
  }
  echo"<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
</head><script language='javascript' type='text/javascript'><!--
function abrecalendario(field)
{ window.open('calendar.php?campo='+field, 'calendar', 'width=400,height=300,status=yes');}
function enviar_datos()
{ //if(document.getElementById('Nombre').value!='' && document.getElementById('mail').value!='' && (document.getElementById('tel').value!='' || document.getElementById('cel').value!='') && document.getElementById('dom').value!='')
  //{ 
    //document.getElementById('enviar_pedido').submit(); 
  //}
  //else alert('Todos los campos son necesarios.');
}
--></script>
  <body marginheight='0' marginwidth='0' bgcolor='#CCCCCC'>
  <form id='formulario' name='formulario'  enctype='multipart/form-data' action='$action' method='post'>
    <input type='hidden' name='tabla' value='".$_GET['tabla']."'>
    <input type='hidden' name='id' value='".$_GET['id']."'>
    <input type='hidden' name='agr_modif_borr' value='".$_GET['agr_modif_borr']."'>
    <input type='hidden' name='campos' value='$campos'>
    <input type='hidden' name='campoid' value='$campoid'>
    <input type='hidden' name='t_m_campo' value='$t_m_campo'>";
  
  if($_GET['agr_modif_borr']=='borrar') echo"<script language='javascript'>formulario.submit();</script>";
  $result=mysql_query("SELECT * from ".$_GET['tabla']." WHERE $campoid=".$_GET['id']."",$link)or die(mysql_error());
  $row = mysql_fetch_array($result); 


// Formulario de Inscripciones
if($_GET['tabla']=='inscripciones_pasos')
{$chckd=''; if($row["necesita_aceptar"]=='S') $chckd='checked';
 $chckd_=''; if($row["al_ingreso"]=='S') $chckd_='checked'; ?>   
	
    <input id="id_paso" name="id_paso" type="hidden" value="<?=$row['id_paso'];?>">
 <table>
  <tr align="left">
 	<th>Orden</th><td><input id="orden" name="orden" size="4" maxlength="3" value="<?=$row['orden'];?>"></td>
  </tr>
  <tr align="left">
 	<th>T&iacute;tulo</th><td><input id="titulo" name="titulo" size="40" maxlength="50" value="<?=$row['titulo'];?>"></td>
  </tr>
  <tr align="left">
 	<th>Tipo</th><td><select name="tipo" id="tipo">
    <option value="NINGUNO">Ninguno</option>
    <option value="GENERICO">Gen&eacute;rico</option>
    <option value="FORMA_PAGO">Formas de Pago</option>
    <option value="LISTA_ALUMOS">Listado de Alumnos a Inscribir o Reinscribir</option>
    <option value="DATOS_FAMILIA">Datos de Familia</option>
    <option value="DATOS_ALUMNO">Datos de Alumno</option>
    <option value="DATOS_ALUMNO_M">Datos de Alumno(m&eacute;dicos)</option></select></td>
  </tr>
  <tr align="left">
 	<th>Repetir</th><td><select name="repetir" id="repetir">
    <option value="N">Nunca</option>
    <option value="S">Siempre</option>
    <option value="F">Una vez por Familia</option>
    <option value="A">Una vez por Alumno</option></select></td>
  </tr>
  <tr align="left"><th>Mostrar al Ingresar</th><td><input type='checkbox' <?=$chckd_?> onClick="if(this.checked) document.getElementById('al_ingreso').value='S'; else document.getElementById('al_ingreso').value='N';"><input type='hidden' name='al_ingreso' id='al_ingreso' value='<?=$row["al_ingreso"]?>'>
  </tr>
  <tr align="left"><th>Necesita Aceptar</th><td><input type='checkbox' <?=$chckd?> onClick="if(this.checked) document.getElementById('necesita_aceptar').value='S'; else document.getElementById('necesita_aceptar').value='N';"><input type='hidden' name='necesita_aceptar' id='necesita_aceptar' value='<?=$row["necesita_aceptar"]?>'>
  </tr>
  <tr align="left"><th>Texto de Aceptaci&oacute;n</th><td><input name='texto_aceptacion' id='texto_aceptacion' value='<?=$row["texto_aceptacion"]?>' maxlength="200" size="40">
  </tr>
  <tr align="left"><th>Texto de No Aceptaci&oacute;n</th><td><input name='texto_rechazo' id='texto_rechazo' value='<?=$row["texto_rechazo"]?>' maxlength="200" size="40">
  </tr>
  <tr align="left"><th colspan="2">Texto<input onFocus="this.value=nicEditors.findEditor('m').getContent();" size="1" name="texto" id="texto" style='font-size:1px; background-color: #CCCCCC; color:#CCCCCC; border: #CCCCCC;'></th></tr>
  <th colspan="2" bgcolor="#FFFFFF"><div id="sample">
  <script type="text/javascript" src="nicEdit.js"></script>
  <script type="text/javascript"> bkLib.onDomLoaded(function(){ new nicEditor().panelInstance('m'); }); </script>
  <textarea rows="30" cols="90" name="m" id="m"><?=$row['texto'];?></textarea></div></th>
  </tr>
 </table> <script language="javascript">  
		document.getElementById('tipo').value="<?=$row['tipo'];?>"; 
		document.getElementById('repetir').value="<?=$row['repetir'];?>";
		 </script><?
}



//formulario de secciones_reinscripcion
if($_GET['tabla']=='secciones_reinscripcion')
{?><input size="1" name="texto" id="texto" style='font-size:1px; background-color: #CCCCCC; color:#CCCCCC; border: #CCCCCC;'>
<table>
  <tr><th align="left">Ciclo</th><td><?=mysql_result(mysql_query("select descripcion from ciclos where ciclo=".$row['ciclo'],$link),0,0);?><input type="hidden" name="ciclo" id="ciclo" value="<?=$row['ciclo'];?>"</td></tr>
  <tr><th align="left">Secci&oacute;n</th><td><?=mysql_result(mysql_query("select nombre from secciones where seccion='".$row['seccion']."' and ciclo=".$row['ciclo'],$link),0,0);?><input type="hidden" name="seccion" id="seccion" value="<?=$row['seccion'];?>"</td></tr>
  <tr><th align="left">Rango de fechas</th><td>Inicio:<input size='10' name='fecha_ini' id='fecha_ini' value='<?=$row['fecha_ini']?>' readonly="readonly"><img onClick="abrecalendario('fecha_ini');" src='../im/calendario.jpg' /> Fin:<input size='10' name='fecha_fin' id='fecha_fin' value='<?=$row['fecha_fin'];?>' readonly="readonly"><img onClick="abrecalendario('fecha_fin');" src='../im/calendario.jpg' /></td>
 </tr></table>
<?
}



//formulario de secciones_reinscripcion
if($_GET['tabla']=='inscripciones_alumnos')
{?>
<table>
  <tr><th align="left" colspan="2"><?=mysql_result(mysql_query("SELECT CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) as n FROM alumnos WHERE alumno=".$row['alumno'],$link),0,0);?> &nbsp; &nbsp; ESTATUS: <?=$row['estatus']?></th></tr>
  <tr><td>Comentario:</td><td><input name="comentario" id="comentario" size="120" value="<?=$row['comentario'];?>" /></td></tr>
</table>
<?
}


}
?> </form>
</body>
</html>