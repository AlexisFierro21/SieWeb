<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<? 
$nota_tipo=" ";
if(!empty($_GET['nota_tipo'])) $nota_tipo=$_GET['nota_tipo']; 
?>
<script language="javascript" type="text/javascript">
<!--
function editar(ID,agr_modif_borr)
{ var confirma = true;
  if(editando.value=='0')
  {	if(agr_modif_borr=='borrar') confirma = confirm(' ¿Realmente deseas borrar el registro?');
    if(confirma)
	{   var tabla=document.getElementById('tabla').value;
		if(tabla=='notas')  tabla=tabla+'&nota_tipo=<?=$nota_tipo;?>';
		document.getElementById('iframe_'+ID).src='formularios.php?id='+ID+'&tabla='+tabla+'&agr_modif_borr='+agr_modif_borr;
		document.getElementById('tm_'+ID).style.display='none';
		if(agr_modif_borr!='borrar')
		{  editando.value='1';
		   document.getElementById('edit_'+ID).style.display='inline';
		   document.getElementById('edit_'+ID).style.display='table-row';
		}
  	}
  }
  else alert('Ya se esta modificando otro Registro');
}
function cancelar(ID)
{ editando.value='0';
  document.getElementById('edit_'+ID).style.display='none';
  document.getElementById('tm_'+ID).style.display='inline';
  document.getElementById('iframe_'+ID).src='formularios.php';
  if(ID!=1) document.getElementById('tm_'+ID).style.display='table-row';  
}
-->
</script>
<title>LIMA</title>
<table border="1" bgcolor='#FFFFFF'>
   <tr>
    <th colspan="2" bgcolor='#CCCCCC' onClick="editar(1,'agregar');"><img id="tm_1" alt="Agregar Nuevo" src="im/new.png"></th>
<? session_start();
include('config.php');
if($_GET['tabla']!='textos_materiales') echo"<td><a href='tabla.xls'>Exportar</a></td>";
switch($_GET['tabla'])
{ case 'textos_materiales': $campoid="ID"; 			
			$campos="ISBN_Clave,Titulo_Material,Autor,Editorial,LibPap,Estatus,Tipo,Cantidad,Academia,Grupo,Materia,Seccion,Grado,Listas,Comentarios,Precio,MaterialApoyo,Grupo2,Nota";
			$echo="<th>ISBN/Clave</th><th>Apoyo</th><th width='300'>T&iacute;tulo/Material</th><th width='300'>Nota</th><th>Autor</th><th>Editorial</th><th>Librer&iacute;a/Papeler&iacute;a</th><th>Academia</th><th>Secci&oacute;n</th><th>Grado</th><th><em>Level</em></th><th>Materia</th><th>Cantidad</th><th>Precio</th><th>Estatus</th><th>Listas</th><th>Tipo</th><th width='300'>Comentarios</th>";
			break;
  case 'editoriales': $campoid="EditorialID"; 			
			$campos="Nombre,Direccion,Telefono,Fax,OtroTel,PaginaWeb,RepPrimaria,CelRepPrim,CorreoRepPrim,RepSec,CelRepSec,CorreoRepSec,RepPrepa,CelRepPrepa,CorreoRepPrepa,RepresentanteIngles,CelRepIngles,CorreoRepIngles";
			$echo="<th>Nombre</th><th>Direcci&oacute;n</th><th>Tel&eacute;fono</th><th>Fax</th><th>Otro Tel</th><th>P&aacute;gina Web</th><th>Rep. Primaria</th><th>Celular</th><th>Correo</th><th>Rep. Secundaria</th><th>Celular</th><th>Correo</th><th>Rep. Prepa</th><th>Celular</th><th>Correo</th><th>Rep Ingl&eacute;s</th><th>Celular</th><th>Correo</th>";
			break;
  case 'libpap': $campoid="LibPapId"; 			
			$campos="Nombre,DireccionMatriz,TelefonoMatriz,FaxdeMatriz,OtroTelMatriz,PaginaWeb,Sucursal,DireccionSucursal,TelefonoSucursal,FaxdeSucursal,OtroTelSucursal,Representante,CelRep,CorreoRep";
			$echo="<th>Nombre</th><th>Direcci&oacute;n</th><th>Tel&eacute;fono</th><th>Fax</th><th>Otro Tel.</th><th>P&aacute;gina Web</th><th>Sucursal</th><th>Direcci&oacute;n</th><th>Tel&eacute;fono</th><th>Fax</th><th>Otro Tel.</th><th>Representante</th><th>Celular</th><th>Correo</th>";
			break;
  case 'academias': $campoid="AcademiaID";   $campos="Academia";			$echo="<th>Academia</th>"; break;
  case 'estatus': 	$campoid="EstatusID"; 	 $campos="Estatus,Descripcion"; $echo="<th>Estatus</th><th>Descripci&oacute;n</th>"; break;
  case 'estatuslistas': $campoid="ListasID"; $campos="Listas,Descripcion,compra"; 	$echo="<th>Listas</th><th>Descripci&oacute;n</th><th>Se Compra</th>"; break;
  case 'grados': 	$campoid="GradoID"; 	 $campos="Grado"; 				$echo="<th>Grado</th>"; break;
  case 'grupos': 	$campoid="GrupoID"; 	 $campos="Grupo"; 				$echo="<th><em>Level</em></th>"; break;
  case 'materias': 	$campoid="MateriaID"; 	 $campos="Materia,Descripcion";	$echo="<th>Materia</th><th>Descripci&oacute;n</th>"; break;
  case 'secciones': $campoid="SeccionID"; 	 $campos="Seccion,levels";	    $echo="<th>Seccion</th><th>Usa Levels</th>"; break;
  case 'tipos': 	$campoid="TipoID"; 		 $campos="Tipo,Descripcion,Orden";	$echo="<th>Tipo</th><th>Descripci&oacute;n</th><th>Orden</th>"; break;
  case 'usuarios': 	$campoid="UserID";		 $campos="Usuario,A_S,Passw";	$echo="<th>Usuario</th><th>Contrase&ntilde;a</th>"; break;
  case 'notas': 	$campoid="NotaID";		 $campos="Seccion,Grado,Nota";	$echo="<th>Secci&oacute;n</th><th>Grado</th><th>Nota</th>"; break;
  case 'pdfs': 		$campoid="pdfID";		 $campos="Ciclo,Seccion,Grado";	$echo="<th>Ciclo</th><th>Secci&oacute;n</th><th>Grado</th><th>Archivo</th>"; break;
  }
  echo $echo;
  $exportar="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /><table border='1'><td></td>$echo</tr>";
$reg_ini=0;
$reg_fin=150;
if(!empty($_GET['pag'])) { $reg_ini=($_GET['pag']-1)*30; $reg_fin=$reg_ini+30;}
$reg=0;
$where="WHERE $campoid > 1 ";
if(!empty($_GET['where'])) $where=" $where".$_GET['where'];
if(!empty($_GET['bp'])) $where.=" and ".$_GET['bp']." like '%".$_GET['b']."%' ";
$orderby='';
$campo = explode( ",", $campos );
if(!empty($_GET['orderby'])) $orderby=$_GET['orderby'];
$orderby="$orderby ".$campo[0];
$result=mysql_query("SELECT * from ".$_GET['tabla']." $where ORDER BY $orderby",$link)or die(mysql_error());
?> </tr>
   <tr id='edit_1' style='display:none'><input type="hidden" id="tabla" name="tabla" value="<?=$_GET['tabla'];?>" />
  	<td bgcolor='#CCCCCC' onClick="cancelar('1');"><img alt="Cancelar" src='im/cancel.png'></td>	
  	<td bgcolor='#CCCCCC' onClick="javascript:iframe_1.document.getElementById('formulario').submit(); setTimeout('location.reload()',1000)"><img alt="Guardar" src='im/ok.png'></td>
    <td colspan='<?=mysql_num_fields($result);?>'><iframe width='100%' height='210' id="iframe_1"  name="iframe_1" scrolling='no'></iframe></td>
   </tr>
<? 
while($row = mysql_fetch_array($result))
{ $id=$row[0];
  $reg++; 
  if(($reg>$reg_ini) and ($reg<=$reg_fin))
  {
?>
  <tr  id='tm_<?=$id;?>'>
   <td bgcolor='#CCCCCC' onClick="editar(<?=$id;?>,'modificar');"><img  alt='Modificar' src='im/b_edit.png'></td>
   <td bgcolor='#CCCCCC' onClick="editar(<?=$id;?>,'borrar');"><img alt='Eliminar' src='im/b_drop.png'></td> <?
   $chckd='';
   if($_GET['id']!='')
   {	if($_GET['id']==$id) $chckd='checked';
   		if(($_GET['campo']=='A') || ($_GET['campo']=='S')) $campo_cambia='A_S';
		else $campo_cambia=$_GET['campo'];
   		?>
		<td><input type='radio' <?=$chckd;?> name='radio' value='<?=$id;?>' onClick="window.opener.document.getElementById('n_<?=$_GET['campo'];?>').value='<?=$row[2];?>'; window.opener.document.getElementById('<?=$campo_cambia;?>').value='<?=$id;?>'; window.close(); "></td> <?
		$x=0;
		$campo = explode( ",", $campos );
	    $exportar.="<tr><td>";
   		while(!empty($campo[$x])) 
			{	$echo="<td>&nbsp;".$row[$x+2]."</td>";
			    echo $echo;
				$exportar.=$echo;
				$x++;
			}
	    $exportar.="</tr>";
   }
   elseif($_GET['tabla']=='textos_materiales')
   { $x=18;
  if($row["MaterialApoyo"]==1) $chckd='checked'; 
  echo"
	<td>".$row["ISBN_Clave"]."&nbsp;</td>
	<th><input type='checkbox' $chckd disabled></th>
	<td>".$row["Titulo_Material"]."&nbsp;</td>
	<td>".$row["Nota"]."&nbsp;</td>
	<td>".$row["Autor"]."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Nombre from editoriales where EditorialID='".$row["Editorial"]."'",$link),0,0)."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Nombre from libpap where LibPapId='".$row["LibPap"]."'",$link),0,0)."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Academia from academias where AcademiaID='".$row["Academia"]."'",$link),0,0)."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Seccion from secciones where SeccionID='".$row["Seccion"]."'",$link),0,0)."&nbsp</td>
	<td>".mysql_result(mysql_query("select Grado from grados where GradoID='".$row["Grado"]."'",$link),0,0)."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Grupo from grupos where GrupoID='".$row["Grupo"]."'",$link),0,0)."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Materia from materias where MateriaID='".$row["Materia"]."'",$link),0,0)."&nbsp;</td>
	<td>".$row["Cantidad"]."&nbsp;</td>
	<td>".$row["Precio"]."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Estatus from estatus where EstatusID='".$row["Estatus"]."'",$link),0,0)."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Listas from estatuslistas where ListasID='".$row["Listas"]."'",$link),0,0)."&nbsp;</td>
	<td>".mysql_result(mysql_query("select Tipo from tipos where TipoID='".$row["Tipo"]."'",$link),0,0)."&nbsp;</td>
	<td>".$row["Comentarios"]."&nbsp;</td>
  </tr>";
   } 
   elseif($_GET['tabla']=='usuarios')
   {   $user=$row["Usuario"];
   	   if($row["Usuario"]=='Academia') $user="Academia ".mysql_result(mysql_query("select Academia from academias where AcademiaID='".$row["A_S"]."'",$link),0,0);
   	   if($row["Usuario"]=='Seccion') $user="Secci&oacute;n ".mysql_result(mysql_query("select Seccion from secciones where SeccionID='".$row["A_S"]."'",$link),0,0);
  	$echo="<td></td><td>&nbsp;$user</td><td>&nbsp;".$row["Passw"]."</td></tr>";
	echo $echo;
	$exportar.=$echo;
	$x=1;
	}
	elseif($_GET['tabla']=='pdfs')
	{ $x=5;
	  $nombre_pdf=mysql_result(mysql_query("select Clave from grados where GradoID='".$row['Grado']."'",$link),0,0)."".mysql_result(mysql_query("select Clave from secciones where SeccionID='".$row['Seccion']."'",$link),0,0)."_".$row['Ciclo'].".pdf";
	  $echo="
	  	<td></td><td>&nbsp;".$row["Ciclo"]."</td>
		<td>&nbsp;".mysql_result(mysql_query("select Seccion from secciones where SeccionID='".$row["Seccion"]."'",$link),0,0)."</td>
		<td>&nbsp;".mysql_result(mysql_query("select Grado from grados where GradoID='".$row["Grado"]."'",$link),0,0)."</td>
		<td><a href='listas/$nombre_pdf' target='_blank'>$nombre_pdf</a></td></tr>";
	   echo $echo;
	   $exportar.=$echo;
	}
	else
	{ $x=2;
	  $nota_tipo++;
	  $echo="
	  	<td></td><td>&nbsp;".mysql_result(mysql_query("select Seccion from secciones where SeccionID='".$row["Seccion"]."'",$link),0,0)."</td>
		<td>&nbsp;".mysql_result(mysql_query("select Grado from grados where GradoID='".$row["Grado"]."'",$link),0,0)."</td>
		<td>&nbsp;".$row["Nota"]."</td></tr>";
	   echo $echo;
	   $exportar.=$echo;
	}
	 $x++;
  	  echo"
  <tr id='edit_".$row[0]."' style='display:none'>
  	<td bgcolor='#CCCCCC' onclick='cancelar(".$row[0].");'><img alt='Cancelar' src='im/cancel.png'></td>"; ?>
	
  	<td bgcolor='#CCCCCC' onClick="javascript:iframe_<?=$row[0];?>.document.getElementById('formulario').submit(); setTimeout('location.reload()',1000)"><img alt="Guardar" src='im/ok.png'></td> <?
	
	echo"<td colspan='".mysql_num_fields($result)."'><iframe width='100%' height='210' id='iframe_".$row[0]."' scrolling='no'>
</iframe></td>
  </tr>";
  }//if $reg..
}//while
  $exportar.="</table>";
  $f = fopen('tabla.xls','w'); 
  fwrite($f,$exportar); 
  fclose($f);
  
?><input type="hidden" name="editando" id="editando" value="0" ></table>
</body>
</html>