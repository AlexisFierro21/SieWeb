<? session_start();
include('../config.php');
include('../functions.php');
if(!empty($_POST['ciclo_inicio']))
{ // inicializa todas las secciones en la tabla secciones_reinscripcion
  $ciclo_inicio=$_POST['ciclo_inicio'];
  $r_secciones=mysql_query("SELECT * from secciones where ciclo=$ciclo_inicio order by orden") or die (mysql_error());
  $grado_anterior=0;
  $seccion_anterior=0;
  while($s=mysql_fetch_array($r_secciones))
  { $insert_seccion=mysql_query("INSERT INTO secciones_reinscripcion (ciclo,seccion) values($ciclo_inicio,'".$s['seccion']."')",$link) or die(mysql_error());
    $r_grados=mysql_query("SELECT grado from grados where ciclo=$ciclo_inicio and seccion='".$s['seccion']."' order by grado") or die (mysql_error());
	if($seccion_anterior==0){ $seccion_anterior=$s['seccion'];}
    while($g=mysql_fetch_array($r_grados))
    { if($grado_anterior!=0)
	  { echo"seccion:".$s['seccion']." grado:".$g['grado']." anterior seccion:$seccion_anterior grado:$grado_anterior<br>";
	    $r_alumno=mysql_query("select * from alumnos where activo='A' and seccion='$seccion_anterior' and grado=$grado_anterior",$link) or die (mysql_error());
	    while($a=mysql_fetch_array($r_alumno))
		{ mysql_query("INSERT INTO inscripciones_alumnos (ciclo,familia,alumno,seccion,grado,estatus) VALUES($ciclo_inicio,".$a['familia'].",".$a['alumno'].",'".$s['seccion']."',".$g['grado'].",'SIN INICIAR')",$link) or die (mysql_error());
		}
	  }
	  $grado_anterior=$g['grado']; $seccion_anterior=$s['seccion'];
	} 	
	if($s['pasa_a_siguiente']=='N'){ $seccion_anterior=0; $grado_anterior=0;}
  }  
  }
  else
  {
  
  ?>
 
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head>
<script language="javascript" type="text/javascript">
<!--
var fr='formulario';
function editar(ID,agr_modif_borr)
{ var confirma = true;
  if(editando.value=='0')
  {	if(agr_modif_borr=='borrar') confirma = confirm(' ¿Realmente deseas borrar el registro?');
    if(confirma)
	{   var tabla=document.getElementById('tabla').value;
	    var parametros='';
		document.getElementById('iframe_form').src='formularios_inscripciones.php?id='+ID+'&tabla='+tabla+'&agr_modif_borr='+agr_modif_borr+parametros;
		document.getElementById('tm_'+ID).style.display='none';
		if(agr_modif_borr!='borrar')
		{  editando.value=ID;
		   document.getElementById('edit').style.display='inline';
		   document.getElementById('edit').style.display='table-row';
		}
  	}
  }
  else alert('Ya se esta modificando otro Registro');
}
function cancelar()
{ var ID=editando.value; 
  editando.value='0';
  document.getElementById('iframe_form').src='formularios_inscripciones.php';
  document.getElementById('edit').style.display='none';
  document.getElementById('tm_'+ID).style.display='inline';
  if(ID!=1) document.getElementById('tm_'+ID).style.display='table-row'; 
}
-->
</script>
<body>
<? 
if(!empty($_GET['tabla'])) $tabla=$_GET['tabla'];
else $tabla="inscripciones_pasos";
if(!empty($_GET['exportar'])) echo"<form action='exportar.php' target='_blank' method='post'><input type='submit' value='EXPORTAR'><textarea id='contenido' name='contenido' style='display:none'></textarea><input type='hidden' name='nombre_def' value='tabla.xls'></form>";
?>
<table border="1" bgcolor='#FFFFFF' name='tbl' id="tbl">
   <tr>
    <th colspan="2" bgcolor="#CCCCCC" onClick=" editar(1,'agregar'); ">
    <img id="tm_1" alt="Agregar Nuevo" src="../im/new.png" />
    <input type="hidden" name="editando" id="editando" value="0" />
    <input type="hidden" id="tabla" name="tabla" value="<?=$tabla?>" /></th>
<? 
//definimos que tabla vamos a mostrar y los $campos a mostrar
//se ordena por el primer campo a mostrar
//en $campos se agregar un prefijo seg&uacute;n el tipo de campo
//FE_ si es fecha
//SN_ si es un checkbox si tiene valor "S" lo selecciona 
//ES_ si es especial(crear un if en el switch que est&aacute; adentro del foreach s&oacute;lo hay un foreach en todo el c&oacute;digo)
//ID_ si es un id de otra tabla ID_id_campo;tabla;campo ej. id_ciudad;ciudades;nombre
//si se va a mostrar tal cual como est&aacute; en la tabla no se pone nada
//en la variable $echo van los encabezados de los campos con ortograf&iacute;a
//en $ancho y alto definimos las dimensiones del formulario para modificar los registros
switch($tabla)
{ case 'inscripciones_pasos': $campoid="id_paso"; $ancho=840; $alto=900;			
		 $campos="SN_al_ingreso,orden,titulo,tipo,ES_repetir,SN_necesita_aceptar";
		 $echo="<th>Al ingresar</th><th>Orden</th><th>T&iacute;tulo</th><th>Tipo</th><th>Repetir</th><th>Necesita Aceptar</th>";
		 break;
  case 'secciones_reinscripcion': $campoid="id_sec_ciclo"; $ancho=840; $alto=900;			
		 $campos="ID_seccion;secciones;nombre,FE_fecha_ini,FE_fecha_fin";
		 $echo="<th>Secci&oacute;n</th><th>Fecha Inicio</th><th>Fecha T&eacute;rmino</th>";
		 break;
}
if(!empty($_GET['id'])) echo"<td>&nbsp;</td>";
//la variable id es cuando se abre una tabla desde un formulario para seleccionar un registro y cambiar un campo($campo_cambia) del formulario
echo "$echo </tr>";
$exportar="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /><table border='1'><td></td>$echo</tr>";
$reg_ini=0;
$reg_fin=99999;
if(!empty($_GET['pag'])) { $reg_ini=($_GET['pag']-1)*$_GET['r_pag']; $reg_fin=$reg_ini+$_GET['r_pag'];}
//la variable pag es para mostrar los registros en varias p&aacute;ginas y r_pag define cuantos registro en cada p&aacute;gina
$reg=0;
$where="WHERE $campoid > 1";//" "
if(!empty($_GET['where'])) $where=" $where".$_GET['where'];
//la variable where se usa cuando se est&aacute; haciendo un filtro
if(!empty($_GET['bp'])) $where.=" and ".$_GET['bp']." like '%".$_GET['b']."%' ";
//b&uacute;squeda por: buscar b(texto) en bp(campo) se usa cuando se hace una b&uacute;squeda 
$orderby='';
$campo = explode( ",", $campos );
if(!empty($_GET['orderby'])) $orderby="order by ".$_GET['orderby'];
//la variable orderby es para ordenar los registros
//$orderby=" ORDER BY $orderby ".$campo[0];
$result=mysql_query("SELECT * from $tabla $where $orderby",$link)or die(mysql_error());
while($row = mysql_fetch_array($result))
{ $id=$row[$campoid];
  $reg++;
  if(($reg>$reg_ini) and ($reg<=$reg_fin))
  { ?> <tr id='tm_<?=$id;?>' valign="top">
   <td bgcolor='#CCCCCC' onClick="editar(<?=$id;?>,'modificar');"><img  alt='Modificar' src='../im/b_edit.png'></td>
   <td bgcolor='#CCCCCC' onClick="editar(<?=$id;?>,'borrar');"><img alt='Eliminar' src='../im/b_drop.png'></td> <?
   if(!empty($_GET['id']))
   { $chckd='';
     if($_GET['id']==$id) $chckd='checked';
	 $campo_cambia=$_GET['campo']; ?>
     <td><input type='radio' <?=$chckd;?> name='radio' value='<?=$id;?>' onClick="window.opener.document.getElementById('n_<?=$_GET['campo'];?>').value='<?=$row[2];?>'; window.opener.document.getElementById('<?=$campo_cambia;?>').value='<?=$id;?>'; window.close(); "></td> <?
   } 
//a partir de aqu&iacute; se define como se mostrar&aacute;n los registros de la tabla
   $campo = explode( ",", $campos );
   $exportar.="<tr><td>";
   foreach ($campo as $n_campo)
   { $tipo=substr($n_campo,0,3);
     $n=substr($n_campo,3);//".."
     switch($tipo)
	 { case 'FE_': $f=formatDate($row[$n]); $echo="<td>$f</td>"; break;
	   case 'SN_': if($row[$n]=='S') $echo="<th><input type='checkbox' checked disabled></th>"; 
	   			   else $echo="<th><input type='checkbox' disabled></th>"; break;
	   case 'ID_': $selec = explode( ";",$n);
				   $s=$selec[0];
				   $sl=$selec[1];
				   $slc=$selec[2];
	$echo="<td>&nbsp;".mysql_result(mysql_query("select $slc from $sl where $s='".$row[$s]."'",$link),0,0)."</td>";
				   break;
	   case 'ES_': $echo="<td>&nbsp;</td>";
	   //aqui es donde se agrega el if si es un caso especial
		 		   if($n=='repetir')
				   { switch($row[$n])
				     { case "S": $echo="<td>&nbsp; Siempre </td>"; break;
					   case "N": $echo="<td>&nbsp; Nunca </td>"; break;
					   case "F": $echo="<td>&nbsp; Una vez por Familia </td>"; break;
					   case "A": $echo="<td>&nbsp; Una vez por Alumno </td>"; break;
					 }
				   }break;
	    default: $echo="<td>&nbsp;".$row[$n_campo]."</td>";
	  }//fin del switch($tipo)
	  echo $echo;
	  $exportar.=$echo;
    }//fin del foreach
  }//fin del if(($reg>$reg_ini) and ($reg<=$reg_fin))
  $exportar.="</tr>";
}// fin del while($row = mysql_fetch_array($result))
//si existe la variable exportar guarda el archivo y abre exportar.php para gaurdarlo
if(!empty($_GET['exportar'])) echo"<script language='javascript'>document.getElementById('contenido').value='$exportar';</script>";
//si la tabla es secciones_reinscripcion  y no hay registros se da la opcion de generar el ciclo

?></form> <tr id='edit' style='display:none'>
  	<td colspan='100%' align='right'>
     <img onClick='cancelar();' alt='Cancelar' src='../im/cancel.png' /> &nbsp; 
     <img onClick=" iframe_form.document.all('texto').focus();   setTimeout('iframe_form.document.getElementById(fr).submit()',200); setTimeout('location.reload()',1900);" alt='Guardar' src='../im/ok.png' /><br />
     <iframe width='<?=$ancho;?>' height='<?=$alto;?>' id='iframe_form' name='iframe_form'></iframe></td>
   </tr>
</table>
</body>
</html><?
if($tabla=='secciones_reinscripcion' and $reg==0)
{ $des_ciclo=mysql_result(mysql_query("select descripcion from ciclos where ciclo=".$_GET['ciclo'],$link),0,0);
  echo"<script language='javascript'>document.getElementById('tbl').style.display = 'none';</script><br><br><br><br><br><form action='inscripciones.php' method='post'><input  type='hidden' name='ciclo_inicio' id='ciclo_inicio' value='".$_GET['ciclo']."'><input type='submit' style='font-size:18px; color:#FFFFFF; background-color:#CC3300;' value='Iniciar el proceso de Inscripciones y Reinscripciones del ciclo $des_ciclo'></form>";
}
} ?>