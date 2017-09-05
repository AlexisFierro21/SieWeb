<? session_start();
include('../config.php');
include('../functions.php');

$precep=''; if(!empty($_GET['preceptoria'])) $precep=$_GET['preceptoria'];
$id_test=''; if(!empty($_GET['id_test'])) $id_test=$_GET['id_test'];
$id_prgnt=''; if(!empty($_GET['id_pregunta'])) $id_prgnt=$_GET['id_pregunta'];
$preceptor=''; if(!empty($_GET['preceptor'])) $preceptor="&preceptor=".$_GET['preceptor'];
$almno=''; if(!empty($_GET['alumno'])){ $almn_=$_GET['alumno']; $almno="&alumno=".$_GET['alumno'];} else $almn_=$_SESSION['clave'];

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=Latin1">
<!--<script language='javascript' type='text/javascript' src='alertas.js'></script>-->
</head>
<script language="javascript" type="text/javascript">
<!--
function editar(ID,agr_modif_borr){
	var confirma = true;
	if(editando.value=='0'){
		if(agr_modif_borr=='borrar')
			confirma = confirm(String.fromCharCode(191)+'Realmente deseas borrar el registro?');
		if(confirma){
			var tabla=document.getElementById('tabla').value;
			var parametros='';
			if(tabla=='test')
				parametros='&preceptoria=<?=$precep;?>';
			if(tabla=='test_preguntas')
				parametros='&id_test=<?=$id_test;?>';
			if(tabla=='test_respuestas')
				parametros='<?=$preceptor?><?=$almno?>';
			if(tabla=='test_publicacion')
				parametros='&id_test=<?=$id_test;?>';
			if(tabla=='test_opciones')
				parametros='&id_pregunta=<?=$id_prgnt;?>';
			document.getElementById('iframe_form').src='formularios.php?id='+ID+'&tabla='+tabla+'&agr_modif_borr='+agr_modif_borr+parametros;
			document.getElementById('tm_'+ID).style.display='none';
		}
		if(agr_modif_borr!='borrar'){
			editando.value=ID;
			document.getElementById('tabla_gral').style.display='none';
			document.getElementById('edit').style.display='inline';
			document.getElementById('edit').style.display='table-row';
			document.getElementById('nombreplantilla').style.display='none';
		}
	}
	else
		alert('Ya se esta modificando otro Registro');
}
function cancelar(){
	var ID=editando.value;
	editando.value='0';
	document.getElementById('iframe_form').src='formularios.php';
	document.getElementById('edit').style.display='none';
	document.getElementById('tm_'+ID).style.display='inline';
	document.getElementById('tabla_gral').style.display='inline';
	document.getElementById('nombreplantilla').style.display='inline';
	if(ID!=1)
		document.getElementById('tm_'+ID).style.display='table-row';
}
-->
</script>
<body>
<?
if($_POST['descripcion_plantilla']!=NULL){
	$descp=$_POST['descripcion_plantilla'];
	mysql_query("insert into plantillas (descripcion) values ('$descp')") or die(mysql_error());
	$idplantilla=mysql_result(mysql_query("select id_plantilla from plantillas where descripcion='$descp' ORDER BY descripcion ASC"),0) or die(mysql_error());
	$sqlquery=mysql_query("SELECT opcion,puntos FROM test_opciones WHERE id_pregunta=$_GET[id_pregunta]");
	//while($array=mysql_fetch_array($sqlquery)) echo $array[opcion];
	while($array=mysql_fetch_array($sqlquery))
		mysql_query("insert into plantillas_respuestas (id_plantilla, opcion, puntos) values ($idplantilla, '$array[opcion]',$array[puntos])");
	
}
if(!empty($_GET['exportar'])) echo"<form action='exportar.php' target='_blank' method='post'><input type='submit' value='EXPORTAR'><textarea id='contenido' name='contenido' style='display:none'></textarea><input type='hidden' name='nombre_def' value='tabla.xls'></form>";
$tabla=$_GET['tabla'];
if($tabla=="plantillas_respuestas" || $tabla=="test_opciones")
	echo "<font size=2>
Cargar una plantilla sobreescribe inmediatamente las respuestas actuales a la pregunta.<br>
Para guardar una nueva plantilla, primero crear todas las respuestas a la pregunta, y despu&eacute;s ingresar un nombre y dar clic en 'Guardar'.<br>
S&oacute;lo se pueden guardar plantillas para el tipo de respuesta 'Opciones en horizontal' y 'Opciones en vertical'.
</font><br>";
?>
<table border="1" bgcolor='#FFFFFF' id='tabla_gral'>
   <tr><?  $tabla=$_GET['tabla'];  if(($_GET['administra_test']=='S' && !empty($_GET['administra_test'])) || ($tabla!='test')) {?>
    <th colspan="2" bgcolor='#CCCCCC' onClick="editar(1,'agregar');"><img id="tm_1" alt="Agregar Nuevo" src="../im/new.png"></th><? } ?><input type='hidden' name='editando' id='editando' value='0' />
<?
$tabla=$_GET['tabla'];
echo"<input type='hidden' id='tabla' name='tabla' value='$tabla'>";
//definimos que tabla vamos a mostrar y los $campos a mostrar
//se ordena por el primer campo a mostrar
//en $campos se agregar un prefijo según el tipo de campo
//FE_ si es fecha
//SN_ si es un checkbox si tiene valor "S" lo selecciona
//ES_ si es especial(crear un if en el switch que está adentro del foreach sólo hay un foreach en todo el código)
//ID_ si es un id de otra tabla ID_id_campo;tabla;campo ej. id_ciudad;ciudades;nombre
//si se va a mostrar tal cual como está en la tabla no se pone nada
//en la variable $echo van los encabezados de los campos con ortografía
//en $ancho y alto definimos las dimensiones del formulario para modificar los registros
switch($_GET['tabla'])
{ case 'test': $campoid="id_test"; $ancho=840; $alto=900;
		 $campos="nombre,descripcion,creo,FE_fecha_creacion";
		 $echo="<th>Nombre</th><th>Descripci&oacute;n</th>";
	     if(!empty($_GET['administra_test'])) $echo.="<th>Cre&oacute;</th><th>Fecha</th>";
	     else $echo.="<th>Estatus</th>";
		 break;
  case 'test_publicacion': $campoid="id_publicacion"; $ancho=770; $alto=500;
		 $campos="aplica_a,ID_ciclo;ciclos;descripcion,ES_rangopublicacion,FE_fecha_ini,FE_fecha_fin,SN_activo";
		 $echo="<th>Aplicar a</th><th>Ciclo</th><th>Descripci&oacute;n</th><th>Inicia</th><th>Termina</th><th>Activo</th>";
		 break;
  case 'test_opciones': $campoid="id_opcion";  $ancho=650; $alto=70;
		 $campos="opcion,puntos";
		 $echo="<th>Opci&oacute;n</th><th>Puntos</th>";
		 break;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////		
  case 'test_preguntas':
		 $campoid="id_pregunta";
		 $ancho=800;
		 $alto=400;
		 $campos="orden,pregunta,tipo,id_area,id_aspecto";
		 $echo="<th>Orden</th><th>Pregunta</th><th>Tipo</th><th>&Aacute;rea</th><th>Aspecto</th>";
		 break;
  case 'plantillas_respuestas':
		 $campoid="id_plantilla";
		 $ancho=800;
		 $alto=400;
		 $campos="opcion,puntos";
		 $echo="<th>Opci&oacute;n</th><th>Puntos</th>";
		 mysql_query("delete from test_opciones where id_pregunta=$_GET[id_pregunta]") or die(mysql_error());
		 break;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
  case 'areas_valor': $campoid="id_area_valor";  $ancho=800; $alto=400;
		 $campos="area,nombre";
		 $echo="<th>&Aacute;rea</th><th>Nombre</th>";
		 break;
  case 'plan_mejora': $campoid="id_plan"; $ancho=650; $alto=70;
		 $campos="aspecto,nombre";
		 $echo="<th>Aspecto</th><th>Nombre</th>";
		 break;
		 break;
}
if(!empty($_GET['id'])) echo"<td>&nbsp;</td>";
//la variable id es cuando se abre una tabla desde un formulario para seleccionar un registro y cambiar un campo($campo_cambia) del formulario
echo "$echo </tr>";
if($tabla=="plantillas_respuestas" || $tabla=="test_opciones")
echo "<div id='nombreplantilla' name='nombreplantilla' style='float:right;'>
<form action='".curPageURL()."' method='post' id='guardarPlantilla' name='guardarPlantilla'>
	<b>Nombre de plantilla:</b>
	<input type='text' id='descripcion_plantilla'  name='descripcion_plantilla'>
	<input type='hidden' id='preguntaide' name='preguntaide' value=".$_GET['id_pregunta'].">
	<input type='submit' value='Guardar' onClick='javascript:iframe_form.document.getElementById('guardarPlantilla').submit(); setTimeout('parent.location.reload()',5000)'>
</form>
</div>";
//$exportar="<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /><table border='1'><td></td>$echo</tr>";
$exportar="<meta http-equiv='Content-Type' content='text/html; charset=Latin1' /><table border='1'><td></td>$echo</tr>";
$reg_ini=0;
$reg_fin=99999;
if(!empty($_GET['pag'])) { $reg_ini=($_GET['pag']-1)*$_GET['r_pag']; $reg_fin=$reg_ini+$_GET['r_pag'];}
//la variable pag es para mostrar los registros en varias páginas y r_pag define cuantos registros en cada página
$reg=0;
$where="WHERE $campoid > 1";//" "
if(!empty($_GET['where'])) $where=" $where".$_GET['where'];
//la variable where se usa cuando se está haciendo un filtro
if(!empty($_GET['bp'])) $where.=" and ".$_GET['bp']." like '%".$_GET['b']."%' ";
//búsqueda por: buscar b(texto) en bp(campo) se usa cuando se hace una búsqueda
$orderby='';
$campo=explode(",",$campos);
if(!empty($_GET['orderby']))
	$orderby="order by ".$_GET['orderby'];
//la variable orderby es para ordenar los registros
//$orderby=" ORDER BY $orderby ".$campo[0];
$result=mysql_query("SELECT * from $tabla $where $orderby",$link)or die(mysql_error());
//echo "SELECT * from $tabla $where $orderby";
while($row = mysql_fetch_array($result)){
	$id=$row[$campoid];
	//echo $id.' ';
	$precep=true;
	if(!empty($_GET['preceptor'])) $precep=false;
	if((!empty($_GET['administra_test']) or ($tabla!='test')) and $precep){
		$reg++;
		if(($reg>$reg_ini) and ($reg<=$reg_fin)){?>
	<tr  id='tm_<?=$id;?>'>
	<td bgcolor='#CCCCCC' onClick="editar(<?=$id;?>,'modificar');"><img  alt='Modificar' src='../im/b_edit.png'></td>
	<td bgcolor='#CCCCCC' onClick="editar(<?=$id;?>,'borrar');"><img alt='Eliminar' src='../im/b_drop.png'></td><?
		if(!empty($_GET['id'])){
			$chckd='';
			if($_GET['id']==$id)
				$chckd='checked';
			$campo_cambia=$_GET['campo'];?>
     <td><input type='radio' <?=$chckd;?> name='radio' value='<?=$id;?>' onClick="window.opener.document.getElementById('n_<?=$_GET['campo'];?>').value='<?=$row[2];?>'; window.opener.document.getElementById('<?=$campo_cambia;?>').value='<?=$id;?>'; window.close(); "></td> <?
		}
//a partir de aquí se define como se mostrarán los registros de la tabla
//si se necesita un diseño especial se crea un case en este switch
   switch($tabla){
		case 'ejemplo':
			break;
		default:
			$campo=explode(",",$campos);
			$exportar.="<tr><td>";
         foreach ($campo as $n_campo){
			$tipo=substr($n_campo,0,3);
			$n=substr($n_campo,3);//".."
			switch($tipo){
				case 'FE_': $f=formatDate($row[$n]); $echo="<td>$f</td>"; break;
				case 'SN_': if($row[$n]=='S') $echo="<th><input type='checkbox' checked disabled></th>";
	   			     else $echo="<th><input type='checkbox' disabled></th>"; break;
				case 'ID_': $selec=explode(";",$n);
				     $s=$selec[0];
				     $sl=$selec[1];
				     $slc=$selec[2];
				     $echo="<td>&nbsp;".mysql_result(mysql_query("select $slc from $sl where $s='".$row[$s]."'",$link),0,0)."</td>";
				     break;
				case 'ES_': $echo="<td>&nbsp;</td>";
		 		     if($n=='rangopublicacion'){
						$sec='';
						$grd='';
						$grp='';
						if($row[alumno]==0){
							if($row[seccion]!=0)
								$sec=mysql_result(mysql_query("select nombre from secciones where seccion='".$row["seccion"]."' and ciclo='".$row["ciclo"]."'",$link),0,0);
							if($row[grado]!=0)
								$grd=mysql_result(mysql_query("select grado from grados where seccion='".$row["seccion"]."' and ciclo='".$row["ciclo"]."' and grado='".$row["grado"]."'",$link),0,0);
							if($row[grupo]!=0)
								$grp=mysql_result(mysql_query("select grupo from grupos where seccion='".$row["seccion"]."' and ciclo='".$row["ciclo"]."' and grado='".$row["grado"]."' and grupo='".$row["grupo"]."'",$link),0,0);
							$echo="<td>&nbsp; $sec $grd $grp </td>";
						}
						else
							$echo="<td>&nbsp; $row[alumno]</td>";
				     }
				     break;
	         default: $echo="<td>&nbsp;".$row[$n_campo]."</td>";
					  if($tabla=='plantillas_respuestas' && $n_campo=='opcion')
						mysql_query("insert into test_opciones(id_pregunta,opcion,puntos) values($_GET[id_pregunta],'$row[opcion]',$row[puntos])") or die (mysql_error());
	       }//fin del switch($tipo)
	       echo $echo;
	       $exportar.=$echo;
         }//fin del foreach
   }//fin del switch ($tabla)
  }//fin del if(($reg>$reg_ini) and ($reg<=$reg_fin))
  $exportar.="</tr>";
  }//fin del if(!empty($_GET['administra_test']))
  else{
	/*$hoy=date("Y/n/j");
	echo "SELECT * FROM test_publicacion WHERE (activo='S' or (DATE_FORMAT(fecha_ini,%Y/%m/%d)>=$hoy and DATE_FORMAT(fecha_fin,%Y/%m/%d)<=$hoy and fecha_ini<=fecha_fin)) and id_test=$row[id_test]<br><br>";
    $rs_p=mysql_query("SELECT * FROM test_publicacion WHERE (activo='S' or (DATE_FORMAT(fecha_ini,'%Y/%m/%d')>='$hoy' and DATE_FORMAT(fecha_fin,'%Y/%m/%d')<='$hoy' and fecha_ini<=fecha_fin)) and id_test=".$row['id_test'],$link) or die(mysql_error());*/
	$hoy=date("Y-m-d");
	//echo "SELECT * FROM test_publicacion WHERE (activo='S' or (fecha_ini>=$hoy and fecha_fin<=$hoy and fecha_ini<=fecha_fin)) and id_test=$row[id_test] and ciclo=$periodo_actual";
    $rs_p=mysql_query("SELECT * FROM test_publicacion WHERE (activo='S' or (fecha_ini>=$hoy and fecha_fin<=$hoy)) and id_test=$row[id_test] and ciclo=$periodo_actual",$link) or die(mysql_error());
	while($rw_p=mysql_fetch_array($rs_p)){
		$le_toca=false;
		if($rw_p['alumno']==$almn_)
			$le_toca=true;
		else
			if($rw_p['alumno']==0){
				$rs_almno=mysql_fetch_array(mysql_query("SELECT * FROM alumnos WHERE alumno=$almn_",$link));
				if($rw_p['todas_secciones']=='S')
					$le_toca=true;
				else
					if($rw_p['seccion']==$rs_almno['seccion']){
						if($rw_p['todos_grados']=='S')
							$le_toca=true;
						else
							if($rw_p['grado']==$rs_almno['grado']){
								if($rw_p['todos_grupos']=='S')
									$le_toca=true;
								else
									if($rw_p['grupo']==$rs_almno['grupo'])
										$le_toca=true;
						}
					}
			}
	  if($le_toca){
		$trmnd='';
		$id_est='';
	    $r_est=mysql_query("SELECT * FROM test_estatus WHERE id_publicacion=".$rw_p['id_publicacion']." AND responde=$almn_",$link) or die(mysql_error());
        while($r_e=mysql_fetch_array($r_est)){
			$id_est=$r_e['id_estatus'];
			$trmnd=$r_e['terminado'];
		}
		if(strtolower($row[descripcion])!='encuesta de salida'){
			echo "<tr><td>".$row['nombre']."</t><td>".$row['descripcion']."</td><td align='center'><a href=";
			$src="'formularios.php?id=".$row['id_test']."&id_publicacion=".$rw_p['id_publicacion']."&tabla=test_respuestas&agr_modif_borr=a&alumno=$almn_$preceptor'";
			switch($trmnd){
				case '':
					echo "$src>Sin Empezar</a></td></tr>";
					break;
				case 'N':
					echo "$src>Sin Terminar</a></td></tr>";
					break;
				case 'S':
					if(!empty($_GET['preceptor']))
						echo "$src>Terminado</a></td></tr>";
					else
						echo "''>Terminado</a></td></tr>";
					break;
			}
		}
	  }
	}//while
  }
}// fin del while($row = mysql_fetch_array($result))
if(!empty($_GET['exportar']))
	echo"<script language='javascript'>document.getElementById('contenido').value='$exportar';</script>";
//si existe la variable exportar guarda el archivo y abre exportar.php para gaurdarlo
?>
</table>
<table id='edit' style="display:none">
<tr>
  	<td colspan='100%' align='right'>
     <img onClick='cancelar();' alt='Cancelar' src='../im/cancel.png' />&nbsp;
     <img onClick="javascript:iframe_form.document.getElementById('formulario').submit(); setTimeout('location.reload()',5000)" alt='Guardar' src='../im/ok.png' /><br />
     <iframe width='<?=$ancho;?>' height='<?=$alto;?>' id='iframe_form' name='iframe_form'></iframe></td>
   </tr>
</table>
</body>
</html>
