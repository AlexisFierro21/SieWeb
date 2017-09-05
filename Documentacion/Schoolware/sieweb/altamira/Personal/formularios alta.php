<?
session_start();
include('../config.php');
include('../functions.php');
require('../phpMailer/class.phpmailer.php');

if(isset($_POST[done]) && strpos($_POST[parentframe],'encuesta.php')!=false)
	{include('mailer.php');}

function borra($tabla,$campo,$id){
	$tabla_ = "";
	$update = false;
	switch ($tabla) {
		case 'test' :
			borra ( 'test_preguntas', 'id_test', $id );
			borra ( 'test_publicacion', 'id_test', $id );
			break;
		case 'test_preguntas' :
			borra ( 'opciones', $campo, $id );
			$tabla_ = 'test_respuestas';
			$campo_id = 'id_pregunta';
			break;
		case 'test_publicacion' :
			$tabla_ = 'test_respuestas';
			$campo_id = 'id_publicacion';
			break;
		case 'test_opciones' :
			$tabla_ = 'test_respuestas';
			$campo_id = 'id_opcion';
			$update = true;
			break;
		case 'opciones' :
			$tabla = 'test_preguntas';
			$tabla_ = 'test_opciones';
			$campo_id = 'id_pregunta';
			break;
	}
	if($tabla_!=""){
		include ('../connection.php');
		mysql_query("SET CHARACTER SET 'Latin1'");
		//echo "SELECT $campo_id from $tabla where $campo=$id";
		$result_ = mysql_query ( "SELECT $campo_id from $tabla where $campo=$id", mysql_connect ( $server, $userName, $password ) ) or die ( mysql_error () );
		mysql_query("SET CHARACTER SET 'Latin1'");
		while ( $row_ = mysql_fetch_array ( $result_ ) ) {
			if($update){
				$r=mysql_query("UPDATE $tabla_ SET $campo_id='' WHERE $campo_id=".$row_[$campo_id],mysql_connect($server,$userName,$password)) or die(mysql_error());
				//mysql_query("SET CHARACTER SET 'Latin1'");
			}
			else
				borra($tabla_,$campo_id,$row_[$campo_id]);
		}
	}
	include('../connection.php');
	mysql_query("SET CHARACTER SET 'Latin1'");
	mysql_query("DELETE FROM $tabla WHERE $campo=$id",mysql_connect($server,$userName,$password)) or die(mysql_error () );
}

if(!empty($_POST[n_filas])){
	$area_2=explode(",",$_POST[aplica_2]);
	if($_POST[tabla]=='test_preguntas'){
		$_SESSION['id_area']=$area_2[0];
		$_SESSION['id_aspecto']=$area_2[1];
	}
	//echo "<script language='javascript'>alert('".$_SESSION['id_area']."');</script>";
	$myquery="id_area=".$area_2[0].", id_aspecto=".$area_2[1];
	for($n_filas=1;$n_filas<=$_POST[n_filas];$n_filas++){
		if($n_filas==1)
			$cont_='';
		else
			$cont_="_$n_filas";
		if($_POST['tabla'.$cont_]!=''){
			if($_POST['agr_modif_borr'.$cont_]=='borrar')
				borra($_POST['tabla'.$cont_],$_POST['campoid'.$cont_],$_POST['id'.$cont_]);
			else{
				$campos = explode ( ",", $_POST ['campos' . $cont_] );
				$area = explode(",", $_POST['aplica']); //Para Area Aspecto en Tests
				if ($_POST ['agr_modif_borr' . $cont_] == 'agregar') {
					$sql="INSERT INTO ".$_POST['tabla'.$cont_]." (".$_POST['campoid'.$cont_]." ";
					if($_POST['tabla'] == 'test')
						$sql.=",creo,fecha_creacion,id_area";
					if ($_POST ['tabla' . $cont] == 'test_preguntas')
						$sql .= ",id_area,id_aspecto";
					foreach($campos as $campo)
						$sql.=",$campo";
					$sql.=") VALUES (NULL";
					if(empty($area[1]))
						$area[1]=0;
					if($_POST['tabla']=='test')
						$sql.=",".$_SESSION[clave].",now(),".$area[0]." ";
					if ($_POST ['tabla' . $cont] == 'test_preguntas')
						$sql.=",".$area_2[0].",".$area_2[1];
					foreach($campos as $campo)
						$sql.=",'".$_POST[$campo.''.$cont_]."'";
					$sql.=")";
				}
				if ($_POST ['agr_modif_borr' . $cont_] == 'modificar') {
					$sql = "UPDATE " . $_POST ['tabla' . $cont_] . " SET " . $_POST ['campoid' . $cont_] . "=" . $_POST ['id' . $cont_] . " ";
					//Para grabar Area Aspecto de test en Administrador
					if ($_POST ['tabla' . $cont] == 'test')
						$sql .= ", id_area = ".$area[0]." "; 
					if ($_POST ['tabla' . $cont] == 'test_preguntas')
						$sql .= ", ".$myquery." ";
					foreach ( $campos as $campo )
						if ($campo != "")
							$sql .= ", $campo='" . $_POST [$campo . '' . $cont_] . "' ";
					$sql .= " where " . $_POST ['campoid' . $cont_] . "=" . $_POST ['id' . $cont_];
				}
				//debug ($sql);
				$result = mysql_query ( $sql, $link ) or die ( mysql_error () );
				$idt = $_POST ['id' . $cont];
				if ($_POST ['tabla'] == 'test' && $idt > 0) {
					for($i = 1; $i <= 3; $i ++) {
						$a = $i - 1;
						$ide = $_POST ["ide$i"];
						$m = $_POST ["max$i"];
						if ($m < 0)
							$m = 0;
						$c = $_POST ["com$i"];
						$sugerencia = $_POST ["sug$i"];
						$d = $_POST ["max$a"]+1;
						if ($d < 0)
							$d = 0;
						if ($ide > 0)
							$sqle="update test_evaluacion set id_test=$idt,min=$d,max=$m,comentario='$c',sugerencia='$sugerencia' where id=$ide;";
						else
							$sqle="insert into test_evaluacion (id_test,min,max,comentario,sugerencia) values ($idt,$d,$m,'$c','$sugerencia');";
						//debug($sqle);
						mysql_query($sqle);
					}
				}
				if($_POST[tabla]=='test'){
			echo '<br><br><br><center><img src="images/loading.gif"> <b>Procesando...</b></center>';
			mysql_query("delete from test_evaluacion_areas where id_test=$_POST[id]") or die(mysql_error());
			for($countdown=0;$countdown<$_POST['tres'];$countdown++){
				$comentario1=$_POST["comentario1_$countdown"];
				$comentario2=$_POST["comentario2_$countdown"];
				$comentario3=$_POST["comentario3_$countdown"];
				$sugerencia1=$_POST["sugerencia1_$countdown"];
				$sugerencia2=$_POST["sugerencia2_$countdown"];
				$sugerencia3=$_POST["sugerencia3_$countdown"];
				$idarea=$_POST["id_area$countdown"];
				$idaspecto=$_POST["id_aspecto$countdown"];
				$idd=$_POST["id"];
			mysql_query("insert into test_evaluacion_areas (id_area,id_aspecto,id_test, comentario1,comentario2,comentario3,sugerencia1,sugerencia2,sugerencia3) values ($idarea,$idaspecto,$idd,'$comentario1','$comentario2','$comentario3','$sugerencia1','$sugerencia2','$sugerencia3')") or die(mysql_error());
		}
				}
			} //fin del else
		} //fin del if
	} //fin del for
	if (($_POST ['tabla'] == 'expediente_datos') || ($_POST ['tabla'] == 'alumnos') || ($_POST ['tabla'] == 'familias') || ($_POST ['tabla'] == ''))
		echo "<script language='javascript'> alert('Cambios guardados exitosamente'); self.location.href='" . $_POST ['url'] . "';</script>";
	if ($_POST ['tabla'] == 'test_estatus')
		echo "<script language='javascript'> history.go(-2);</script>";
}

if($_POST[done]=='S' && strpos($_POST[parentframe],'encuesta.php')==false)
	f_termina_test($_POST[id_publicacion],$_POST[responde]);
	//echo "<script language='javascript'>alert('".$_POST[id_publicacion].$_POST[responde]"');</script>";

//Si hay se colectan los datos de $_GET para llenar el formulario
if (! empty ( $_GET ['tabla'] )) {
	switch ($_GET ['tabla']) {
		case 'test' :
			$campoid = "id_test";
			$campos = "nombre,descripcion,preceptoria"; //,califica,creo,fecha_creacion
			break;
		case 'test_publicacion' :
			$campoid = "id_publicacion"; //			
			$campos = "id_test,ciclo,aplica_a,alumno,seccion,grado,grupo,nota,fecha_ini,fecha_fin,activo,todas_secciones,todos_grados,todos_grupos";
			break;
		case 'test_opciones' :
			$campoid = "id_opcion";
			$campos = "id_pregunta,opcion,puntos";
			break;
		case 'test_preguntas' :
			$campoid = "id_pregunta";
			$campos = "id_test,orden,pregunta,tipo";
			break;
		case 'test_respuestas' :
			$campoid = "id_respuesta";
			$campos = "id_publicacion,responde,id_pregunta,id_opcion,respuesta";
			break;
		case 'areas_valor' :
			$campoid = "id_area_valor";
			$campos = "ciclo,area,nombre";
			break;
		case 'areas_valor_detalle' :
			$campoid = "id_area_valor_detalle";
			$campos = "ciclo,alumno,id_area_valor,contenido";
			break;
		case 'expediente_datos' :
			$campoid = "id_campo";
			$campos = "consulta,edita";
			break;
		case 'plan_mejora' :
			$campoid = "id_plan";
			$campos = "ciclo,aspecto,nombre";
			break;
		case 'plan_mejora_descripcion' :
			$campoid = "id_plan_desc";
			$campos = "ciclo,alumno,id_plan,entrevista,contenido";
			break;
		case 'plantillas_respuestas':
			$campoid="id_pregunta";
			$_GET ['tabla']='test_opciones';
			$_GET['id']=$_GET['id_pregunta'];
			break;
	}
	echo "
<html xmlns='http://www.w3.org/1999/xhtml'>
 <head>
   <meta http-equiv='Content-Type' content='text/html; charset=Latin1'>

   <style type='text/css'>
     .1 { background-color:#000000; color:#FFFFFF;}
     .2 { background-color:#FF0000; color:#FFFFFF;}
     .3 { background-color:#FF3399; color:#FFFFFF;}
     .4 { background-color:#FF9933; color:#FFFFFF;}
     .5 { background-color:#FFFF00; color:#FFFFFF;}
     .6 { background-color:#00FF00; color:#FFFFFF;}
     .7 { background-color:#0000FF; color:#FFFFFF;}
     .8 { background-color:#00CCFF; color:#FFFFFF;}
     .9 { background-color:#CC00FF; color:#FFFFFF;}
   </style>
   <script language='javascript' type='text/javascript'><!--
function dateformat(txtfecha)
{ var fecha= document.getElementById(txtfecha).value;
  if(fecha!='')
  { var yr=fecha.substring(6,10);
    var mo=fecha.substring(3,5);
    var dy=fecha.substring(0,2);
    document.getElementById(txtfecha).value=''+yr+'-'+mo+'-'+dy+'';
  }
}
--></script>
 </head>
 <body marginheight='0' marginwidth='0' bgcolor='#CCCCCC'>
  <form id='formulario' name='formulario'  enctype='multipart/form-data' action='formularios.php' method='post'>
    <input type='hidden' id='tabla' name='tabla' value='" . $_GET ['tabla'] . "'>
    <input type='hidden' id='id' name='id' value='" . $_GET ['id'] . "'>
    <input type='hidden' id='agr_modif_borr' name='agr_modif_borr' value='" . $_GET ['agr_modif_borr'] . "'>
    <input type='hidden' id='campos' name='campos' value='$campos'>
    <input type='hidden' id='campoid' name='campoid' value='$campoid'>
    <input type='hidden' id='n_filas' name='n_filas' value='1'>
    <input type='hidden' id='url' name='url'>
	<script language='javascript'>document.getElementById('url').value=document.URL;</script>";
	if ($_GET ['agr_modif_borr'] == 'borrar')
		echo "<script language='javascript'>formulario.submit();</script>";
	/*if($_GET ['tabla']=='plantillas_respuestas'){
		$_GET ['tabla']='test_opciones';
		$campoid='id_pregunta';
		$_GET ['id']=$_POST[preguntaide];
	}*/
	//clave1
	//echo "SELECT * from ".$_GET['tabla']." WHERE $campoid=".$_GET['id']."";
	$result = mysql_query("SELECT * from " . $_GET ['tabla'] . " WHERE $campoid=" . $_GET ['id'] . "",$link) or die (mysql_error());
	$row = mysql_fetch_array($result);
	// Formulario de Preguntas
	if ($_GET ['tabla'] == 'test_preguntas') {
		if (($row ['tipo'] == 'opcionesvertical') || ($row ['tipo'] == 'opcioneshorizontal'))
			$dsp = 'inline';
		else
			$dsp = 'none';
		?>
<table>
	<tr>
		<th>Orden :</th>
		<td><input size="3" name="orden" id="orden" maxlength="3"
			value="<?=$row ['orden']?>"
			onKeyPress="if(event.keyCode<48 || event.keyCode>57) event.returnValue=false;" />
		<input type="hidden" name="id_test" id="id_test"
			value="<?=$_GET ['id_test'];?>"</td>
	</tr>
	<tr>
		<th align="left" valign="top">Pregunta :</th>
		<td><textarea rows="5" cols="70" name="pregunta" id="pregunta"><?=$row ['pregunta'];?></textarea><input
			type='hidden' name='id_test' id='id_test'
			value='<?=$_GET ['id_test'];?>'></td>
	</tr>
	<tr>
		<th align="left">Tipo:</th>
		<td><select name="tipo" id="tipo"
			onChange="if((this.value=='opcionesvertical') || (this.value=='opcioneshorizontal')) opc.style.display='inline'; else opc.style.display='none';">
			<option value="abiertacorta">Abierta (1 rengl&oacute;n)</option>
			<option value="abiertalarga">Abierta (varios renglones)</option>
			<option value="opcioneshorizontal">Opciones en Horizontal</option>
			<option value="opcionesvertical">Opciones en Vertical</option>
			<option value="fecha">Fecha</option>
			<option value="sino">Si o No</option>
		</select>&nbsp;&nbsp;&nbsp;
		<?	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($_GET[agr_modif_borr]!='agregar'){
				$resu=mysql_query("select * from plantillas");
				echo "
		<b>Cargar plantilla:</b>
		<select onChange=\"if(this.value!='none'){document.getElementById('subframe').src='tablas.php?tabla=plantillas_respuestas&id_pregunta=$_GET[id]&where= and id_plantilla='+this.value;tipo.options[2].selected=true;opc.style.display='inline';}\" id='select'>
			<option value=\"none\"></option>";
				while($opciones=mysql_fetch_array($resu))
					echo "<option value=$opciones[id_plantilla]>$opciones[descripcion]</option>";
			}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			?>
		</select>
		</td>
	</tr>
	<tr>
		<th align="left">Aplica:</th>
		<td><select name="aplica_2">
			<option value=0>(Elija)</option>
<?		$result_0=mysql_query("SELECT * from test_preguntas WHERE id_pregunta=$_GET[id]",$link) or die(mysql_error());
		$row_0=mysql_fetch_array($result_0);
		if($row_0[id_area]!=0 && $row_0[id_area]!=NULL)
			$Area=$row_0['id_area'].",".$row_0['id_aspecto'];
		if($row_0[id_area]==0 || $row_0[id_area]==NULL){
			if(isset($_SESSION[id_area]))
				$Area=$_SESSION[id_area].",".$_SESSION[id_aspecto];
			else
				$Area=mysql_result(mysql_query("SELECT id_area from test WHERE id_test=$_GET[id_test]"),0).',0';
		}
		$rm=mysql_fetch_array(mysql_query("select periodo from parametros;"));
		$Ciclo=$rm[0];
		$query="Select a.id_area_valor as area, s.id as asp, 
				concat(a.area, ' - ', a.nombre, ' [', s.aspecto, ' - ', s.nombre, ']') as nom, a.area, s.aspecto
				from areas_valor_aspectos s inner join areas_valor a on a.id_area_valor = s.id_area_valor 
				where a.ciclo=$Ciclo
				union all
				Select id_area_valor, 0, concat(area, ' - ', nombre, ' [General]'), area, 0 from areas_valor where ciclo=$Ciclo
				order by 4, 5;";
		$rm=mysql_query($query);
		$tr=mysql_num_rows($rm);
		for($i=0;$i<$tr;$i++){
			$d=mysql_fetch_array($rm);
			$id=$d[0].",".$d[1];
			$no=$d[2];
			if($id==$Area){
				$slc="selected";
				$s=1;
			}
			else
				$slc="";
			echo "<option value=$id $slc>$no</option>";
		}?>
		</select>
	</tr>
	<tr>
		<td colspan="2">
		<div style="display:<?=$dsp;?>" id="opc"><?
		if ($_GET ['agr_modif_borr'] == 'agregar')
			echo "Para agregar opciones se necesita guardar la pregunta primero";
		else {
			?> <iframe id="subframe"
			src="tablas.php?tabla=test_opciones&where= and id_pregunta=<?=$_GET ['id'];?>&id_pregunta=<?=$_GET ['id'];?>"
			width='710' height='200'></iframe><?
		}
		?> </div>
		<script language='javascript'> document.getElementById('tipo').value='<?=$row ['tipo'];?>';</script>
		</td>
	</tr>
</table>
<?
	}
	
	// Formulario de Test
	if ($_GET ['tabla'] == 'test') {
		$areap = $row[id_area];
		
		?>
<table width="100%">
	<tr bgcolor="#CCCCCC" name='pestanitas' id='pestanitas'>
		<th id="t14" onClick="pestana(14,11,14,1);"
			style="cursor: hand; cursor: pointer">Descripci&oacute;n</th>
		<th id="t11" onClick="pestana(11,11,14,1);"
			style="cursor: hand; cursor: pointer">Preguntas</th>
		<th id="t12" onClick="pestana(12,11,14,1);"
			style="cursor: hand; cursor: pointer">Publicaciones</th>
		<th id="t13" onClick="pestana(13,11,14,1);"
			style="cursor: hand; cursor: pointer">Comentarios</th>
	</tr>
	<tr>
		<td colspan="4">
		<div id="d11" style="display: none"><iframe
			src="tablas.php?tabla=test_preguntas&orderby=orden&where= and id_test=<?=$_GET ['id'];?>&id_test=<?=$_GET ['id'];?>"
			width='830' height='700'></iframe></div>
		<div id='d12' style='display: '><iframe
			src="tablas.php?tabla=test_publicacion&where= and id_test=<?=$_GET ['id'];?>&id_test=<?=$_GET ['id'];?>"
			width='800' height='700'></iframe></div>
		<div id='d13' style='display: '><table>
			<?
				$tres=0;
				$test=mysql_fetch_array(mysql_query("SELECT id_area FROM test WHERE id_test=$_GET[id]")) or die(mysql_error());
				$preguntas=mysql_query("select distinct id_area,id_aspecto from test_preguntas where id_test='$_GET[id]' and id_area!=0 order by id_area") or die(mysql_error());
				while($areas_aspectos=mysql_fetch_array($preguntas)){
					$row_area=mysql_fetch_array(mysql_query("select nombre from areas_valor where id_area_valor='$areas_aspectos[id_area]'"));
					$row_aspecto=mysql_fetch_array(mysql_query("select nombre from areas_valor_aspectos where id='$areas_aspectos[id_aspecto]'"));
					$comments=mysql_fetch_array(mysql_query("select * from test_evaluacion_areas where id_area='$areas_aspectos[id_area]' and id_aspecto='$areas_aspectos[id_aspecto]' and id_test='$_GET[id]'"));
					if($row_aspecto['nombre']==NULL)
						$row_aspecto['nombre']="General";
					if($areas_aspectos[id_area]!=$test[id_area] || ($areas_aspectos[id_aspecto]!=0)){
						echo '<tr><td>'.$row_area['nombre'].' ['.$row_aspecto['nombre'].']</td>';
						echo '<td>Comentarios:</td></tr>'; //Comentarios
						echo "<input type=hidden name='id_area$tres' id='id_area$tres' value='$areas_aspectos[id_area]'>";
						echo "<input type=hidden name='id_aspecto$tres' id='id_aspecto$tres' value='$areas_aspectos[id_aspecto]'>";
						echo "<tr><td></td><td bgcolor=orange><input name='comentario1_$tres' id='comentario1_$tres'size=80 maxlength=80 value='$comments[comentario1]'></td></tr>";
						echo "<tr><td></td><td bgcolor=cyan><input name='comentario2_$tres' id='comentario2_$tres' size=80 maxlength=80 value='$comments[comentario2]'></td></tr>";
						echo "<tr><td></td><td style='border-bottom: solid;' bgcolor=lime><input name='comentario3_$tres' id='comentario3_$tres' size=80 maxlength=80 value='$comments[comentario3]'></td></tr>";
						echo '<tr><td></td><td>Sugerencias:</td></tr>'; //Sugerencias
						echo "<tr><td></td><td bgcolor=orange><input name='sugerencia1_$tres' id='sugerencia1_$tres'size=80 maxlength=80 value='$comments[sugerencia1]'></td></tr>";
						echo "<tr><td></td><td bgcolor=cyan><input name='sugerencia2_$tres' id='sugerencia2_$tres' size=80 maxlength=80 value='$comments[sugerencia2]'></td></tr>";
						echo "<tr><td style='border-bottom: solid;'></td><td style='border-bottom: solid;' bgcolor=lime><input name='sugerencia3_$tres' id='sugerencia3_$tres' size=80 maxlength=80 value='$comments[sugerencia3]'></td></tr>";
						$tres++;
					}
				}
				echo "<input type=hidden id='tres' name='tres' value='$tres'>";
				echo "<input type=hidden id='id' value='$_GET[id]'>";
			?>
			</table>
		</div>
		<div name='d14' id='d14'>
<table>
	<tr>
		<th align="left">Nombre :</th>
		<td><input id="nombre" name="nombre" maxlength="20"
			value="<?=$row ["nombre"];?>" /> <input type="hidden"
			name="preceptoria" id="preceptoria"
			value="<?=$_GET ['preceptoria'];?>"></input>
	
	</tr>
	<tr>
		<th align="left" valign="top">Descripci&oacute;n :</th>
		<td><input id="descripcion" name="descripcion" size="80"
			maxlength="100" value="<?=$row ["descripcion"];?>" /></td>
	</tr>

	<tr>
		<th align="left">Aplica a:</th>
		<td><select name=aplica>
			<option value=0>(Elija)</option>
<?		$Area=$row['id_area'];
		$rm=mysql_fetch_array(mysql_query("select periodo from parametros;"));
		$Ciclo=$rm[0];
		$query="select id_area_valor,concat(area,' - ',nombre,' [General]'),area from areas_valor where ciclo=$Ciclo order by area";
		$rm=mysql_query($query);
		$tr=mysql_num_rows($rm);
		for($i=0;$i<$tr;$i++){
			$d=mysql_fetch_array($rm);
			$id=$d[0];
			$no=$d[1];
			if($id==$Area)
				$slc="selected";
			else
				$slc="";
			echo "<option value=$id $slc>".utf8_decode($no)."</option>";
		}
		echo "</select></tr>";
		$idt=$row['id_test'];
		//debug ($idt);
		if($idt>1){
			$txt="Evaluaci&oacute;n:";
			$clr[]="orange";
			$clr[]="cyan";
			$clr[]="limegreen";
			$rs=mysql_query("Select * from test_evaluacion where id_test=$idt;");
	//		$nr=mysql_num_rows($r);
			for($i=1;$i<=3;$i++){
				if($i>1)
					$txt="";
				$color=$clr[$i-1];
				$r=mysql_fetch_array($rs);
				$ide=$r['id'];
				$m=$r['max'];
				$c=$r['comentario'];
				$sugerencia=$r['sugerencia'];
				echo "		<tr><th align=left valign=top>$txt</th>
							<td bgcolor=$color>
	 						Porcentaje m&aacute;ximo: <input id=max$i name=max$i size=2 maxlength=3 value=$m><br>
	 						Comentario: <input id=com$i name=com$i size=80 maxlength=80 value=\"$c\"><br>
							Sugerencia: <input id=sug$i name=sug$i size=80 maxlength=80 value=\"$sugerencia\">
							<input type='hidden' id='ide$i' name='ide$i' value='$ide'>
	 						</td></tr>";
			}
		}
?>
</table></div>
		</td>
	</tr>
</table>
<script language='javascript'> pestana(14,11,14,1);</script><?
		if ($_GET ['agr_modif_borr'] == 'agregar') {
			echo "Para agregar Preguntas o publicaciones se necesita guardar el test primero
			 <script language='javascript' type='text/javascript'> document.getElementById('pestanitas').style.display='none'; </script>
			
			";
		} 
		
		?> 

<?
	}
	// Formulario de Respuestas
	if ($_GET ['tabla'] == 'test_respuestas') {
		$id_est = '1';
		$trmnd = 'N';
		$a_m_b = 'agregar';
		//echo "SELECT * FROM test_estatus WHERE id_publicacion=$_GET[id_publicacion] AND responde=$_GET[alumno]";
		$r_est=mysql_query("SELECT * FROM test_estatus WHERE id_publicacion=$_GET[id_publicacion] AND responde=$_GET[alumno]",$link) or die(mysql_error());
		while($r_e=mysql_fetch_array($r_est)){
			$id_est=$r_e['id_estatus'];
			if($r_e['terminado']=='S')
				$trmnd='S';
			$a_m_b='modificar';
		}
		echo "<input type='hidden' id='id_publicacion' name='id_publicacion' value='".$_GET['id_publicacion']."'>
       <input type='hidden' id='responde' name='responde' value='" . $_GET ['alumno'] . "'>
       <input type='hidden' id='terminado' name='terminado' value='$trmnd'>
	   <input type='hidden' id='parentframe' name='parentframe' value=''>
       <script language='javascript'>
		 document.getElementById('parentframe').value=parent.document.location.href;
         document.getElementById('tabla').value='test_estatus';
		 document.getElementById('id').value='$id_est';
		 document.getElementById('agr_modif_borr').value='$a_m_b';
		 document.getElementById('campos').value='id_publicacion,responde,terminado';
		 document.getElementById('campoid').value='id_estatus';</script><table>";
		$cnt = 1;
		//echo "SELECT * from test_preguntas WHERE id_test=$_GET[id] ORDER BY orden";
		mysql_query("SET CHARACTER SET 'utf8'");
		$result_p=mysql_query("SELECT * FROM test_preguntas WHERE id_test=".$_GET['id']." ORDER BY orden ASC",$link) or die(mysql_error());
		while($row_p=mysql_fetch_array($result_p)){
			$cnt++;
			echo "<tr><td><strong>".utf8_decode($row_p['orden']).".- ".utf8_decode($row_p[pregunta])."</strong>
    <input type='hidden' id='tabla_$cnt' name='tabla_$cnt' value='test_respuestas'>
    <input type='hidden' id='id_$cnt' name='id_$cnt' value=''>
    <input type='hidden' id='agr_modif_borr_$cnt' name='agr_modif_borr_$cnt' value='agregar'>
    <input type='hidden' id='campos_$cnt' name='campos_$cnt' value='$campos'>
    <input type='hidden' id='campoid_$cnt' name='campoid_$cnt' value='$campoid'>
	<input type='hidden' id='id_publicacion_$cnt' name='id_publicacion_$cnt' value='".utf8_decode($_GET['id_publicacion'])."'>
    <input type='hidden' id='responde_$cnt' name='responde_$cnt' value='".utf8_decode($_GET['alumno'])."'>
    <input type='hidden' id='id_pregunta_$cnt' name='id_pregunta_$cnt' value='".utf8_decode($row_p['id_pregunta'])."'>
    <input type='hidden' id='id_opcion_$cnt' name='id_opcion_$cnt'>";
			$resp = '';
			$valida_fechas = '';
			$resp_opc = '';
			$result_respuesta = mysql_query ( "SELECT * from test_respuestas WHERE id_publicacion=".$_GET['id_publicacion']." and responde=".$_GET['alumno']." and id_pregunta=".$row_p['id_pregunta'],$link )or die(mysql_error());
			while ( $r_resp = mysql_fetch_array ( $result_respuesta ) ) {
				$resp_opc = $r_resp ['id_opcion'];
				$resp = $r_resp ['respuesta'];
				$id_resp = $r_resp ['id_respuesta'];
				echo "<script language='javascript'>
	            document.getElementById('id_$cnt').value='$id_resp';
	            document.getElementById('agr_modif_borr_$cnt').value='modificar';
	            document.getElementById('id_opcion_$cnt').value='$resp_opc';</script>";
			}
			$input = "<input type='hidden' id='respuesta_$cnt' name='respuesta_$cnt' value='$resp'>";
			switch ($row_p ['tipo']) {
				case 'fecha' :
					$input = '';
					?>
<br>
<input size='10' maxlength='12' value='<?=
					$resp?>'
	name='respuesta_<?=
					$cnt;
					?>'
	id='respuesta_<?=
					$cnt;
					?>'
	onKeyDown="javascript:writeDate('respuesta_<?=
					$cnt;
					?>')"
	onBlur="if(formatoFechas('respuesta_<?=
					$cnt;
					?>',1)) fechaInsertar ('respuesta_<?=
					$cnt;
					?>');"><?
					break;
				case 'sino' :
					echo " &nbsp; &nbsp; &nbsp;";
					$chck = '';
					if ($resp == 'S')
						$chck = 'checked';
					?>
<input type='checkbox' <?=
					$chck?>
	onClick="if(this.checked) document.getElementById('respuesta_<?=
					$cnt;
					?>').value='S'; else document.getElementById('respuesta_<?=
					$cnt;
					?>').value='N';">
<?
					break;
				case 'opcionesvertical' :
					echo "<br>";
					$o=mysql_query("SELECT * FROM test_opciones WHERE id_pregunta=$row_p[id_pregunta]",$link) or die(mysql_error());
					$chck = '';
					while($row_opc=mysql_fetch_array($o)){
						if($resp_opc==$row_opc['id_opcion'])
							$chck='checked';
						else
							$chck='';
						echo "<input type='radio' $chck name='op_$cnt' onclick='id_opcion_$cnt.value=this.value;' value='" . $row_opc ['id_opcion'] . "'>" . $row_opc ['opcion'] . "<br>";
					}
					break;
				case 'opcioneshorizontal' :
					echo "<br>";
					$o=mysql_query("SELECT * FROM test_opciones WHERE id_pregunta=$row_p[id_pregunta]",$link) or die(mysql_error());
					$chck = '';
					while ( $row_opc = mysql_fetch_array ( $o ) ) {
						if ($resp_opc == $row_opc ['id_opcion']) $chck = 'checked'; else $chck = '';
							
						echo "<input type='radio' name='op_$cnt' $chck onclick='id_opcion_$cnt.value=this.value;' value='" . $row_opc ['id_opcion'] . "' >" . $row_opc ['opcion'] . " &nbsp; &nbsp; &nbsp; ";
					}
					break;
				case 'abiertacorta' :
					$input = "<br><input name='respuesta_$cnt' id='respuesta_$cnt'>";
					break;
				case 'abiertalarga' :
					$input = "<br><textarea name='respuesta_$cnt' id='respuesta_$cnt' rows='8' cols='95'></textarea>";
					break;
			} //switch($row_p['tipo'])
			echo "$input<script language='javascript'>document.getElementById('respuesta_$cnt').value='$resp';</script></td></tr>";
		} //while($row_p = mysql_fetch_array($result_p))
		echo "<tr><th>";
		if (! empty ( $_GET ['preceptor'] )) {
			$chck = '';
			if ($trmnd == 'S')
				$chck = 'checked="checked"';
			?>
<!--  Test Terminado:
<input
	onClick="if(this.checked) document.getElementById('terminado').value='S'; else document.getElementById('terminado').value='N';"
	type='checkbox' <?//=$chck?>>-->
&nbsp; &nbsp; &nbsp;
  <?
		}
		echo "<script language='javascript'>document.getElementById('n_filas').value='$cnt';</script>";
		$resultao=mysql_result(mysql_query("select nombre from test where id_test=$_GET[id]"),0);
		if(strtolower($resultao)=='encuesta')
			echo "<br>Direcciones de correo adicionales:<br>
			<input type='input' id='mail1' name='mail1' size='30'><br>
			<input type='input' id='mail2' name='mail2' size='30'><br><br>";
		?>
Terminado: <input type='checkbox' id='done' name='done' onClick="if(this.checked) document.getElementById('done').value='S';else document.getElementById('done').value='N';">
<!--onClick='if(this.checked)document.getElementById(done).value=S;else document.getElementById(done).value=N;'-->
<input type='submit' value='Guardar'>
</th>
</tr>
</table>
<?
	}
	
	// Formulario de Publicaciones
	if ($_GET ['tabla'] == 'test_publicacion') {
		?>
<input type="hidden" name="id_test" id="id_test"
	value="<?=$_GET ['id_test'];?>" />
<input type="hidden" name="ciclo" id="ciclo"
	value="<?=$periodo_actual;?>" />
<input type="hidden" name="grado" id="grado"
	value="<?=$row ['grado'];?>" />
<input type="hidden" name="grupo" id="grupo"
	value="<?=$row ['grupo'];?>" />
<input type="hidden" name="aplica_a" id="aplica_a"
	value="<?=$row ['aplica_a'];?>" />
<input type="hidden" name="activo" id="activo"
	value="<?=$row ['activo'];?>" />
<input type="hidden" name="todas_secciones" id="todas_secciones"
	value="<?=$row ['todas_secciones'];?>" />
<input type="hidden" name="todos_grados" id="todos_grados"
	value="<?=$row ['todos_grados'];?>" />
<input type="hidden" name="todos_grupos" id="todos_grupos"
	value="<?=$row ['todos_grupos'];?>" />
<table>
	<tr>
		<th align="left">Activo <input type="checkbox" name="ch_act"
			id="ch_act"
			onClick="if(this.checked)  document.getElementById('activo').value='S';  else  document.getElementById('activo').value='N'; " /></th>
	</tr>
	<tr>
		<th align="left">Rango de fechas</th>
		<td>Inicio:<input size='8' name='fecha_ini' id='fecha_ini'
			value='<?=formatDate ( $row ['fecha_ini'] );?>' readonly="readonly"><img
			onClick="abrecalendario('fecha_ini');" src='../im/calendario.jpg' />
		Fin:<input size='8' name='fecha_fin' id='fecha_fin'
			value='<?=formatDate ( $row ['fecha_fin'] );?>' readonly="readonly"><img
			onClick="abrecalendario('fecha_fin');" src='../im/calendario.jpg' /></td>
	</tr>
	<tr>
		<th align="left" valign="top">Aplicar a:</th>
		<td><input checked type="checkbox" name="ch_a" id="ch_a"
			onClick="if(this.checked) { document.getElementById('alm').style.display='none'; document.getElementById('aplica_a').value='Alumno'; } else { document.getElementById('alm').style.display='inline';
			document.getElementById('aplica_a').value='Todos';
			document.getElementById('alumno').value=0;
			document.getElementById('grupo').value=0;
			document.getElementById('todas_secciones').value='S';
			document.getElementById('todos_grados').value='S';
			document.getElementById('todos_grupos').value='S';
			}">
			S&oacute;lo a un alumno: <input name="alumno" id="alumno"
			value="<?=$row ['alumno']?>" /></td>
	</tr>
	<tr>
		<td colspan="2">
		<div name="alm" id="alm" style="display: none;">&nbsp;&nbsp; &nbsp;
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
		&nbsp; &nbsp; &nbsp; Todas las secciones <input checked
			type="checkbox" name="ch_s" id="ch_s"
			onClick="change(4,this,'sec','Todos'); "><br />
		<div name="sec" id="sec" style="display: none"><strong>Secci&oacute;n:
		&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong> <select
			name="seccion" id="seccion" onChange='change(1,this.value,0,0); '>
			<option value='0'></option><?
		$select_grados = "";
		$select_grupos = "";
		$cont = 0;
		$cont_ = 0;
		$rs_ = mysql_query ( "SELECT * FROM secciones WHERE ciclo='$periodo_actual' order by orden", $link ) or die ( mysql_error () );
		while ( $r_ = mysql_fetch_array ( $rs_ ) ) {
			if ($r_ ['seccion'] != '') {
				echo "<option value='" . $r_ ['seccion'] . "'>" . $r_ ['nombre'] . "</option>";
				$opc_grd = "";
				$rgd = mysql_query ( "SELECT * FROM grados WHERE ciclo='$periodo_actual' and seccion='" . $r_ ['seccion'] . "' order by grado", $link ) or die ( mysql_error () );
				while ( $rgd_ = mysql_fetch_array ( $rgd ) ) {
					$opc_grd .= "<option value='" . $rgd_ ['grado'] . "'>" . $rgd_ ['nombre'] . "</option>";
					$opc_grp = "";
					$rgp = mysql_query ( "SELECT * FROM grupos WHERE ciclo='$periodo_actual' and seccion='" . $r_ ['seccion'] . "' and grado ='" . $rgd_ ['grado'] . "' order by orden", $link ) or die ( mysql_error () );
					while ( $rgp_ = mysql_fetch_array ( $rgp ) )
						$opc_grp .= "
	 <option value='" . $rgp_ ['grupo'] . "'>" . $rgp_ ['nombre'] . "</option>";
					$n_select_grupos [$cont_] = "gp_" . $r_ ['seccion'] . "_" . $rgd_ ['grado'];
					$cont_ ++;
					$select_grupos .= "
	 <select style='display:none;' name='gp_" . $r_ ['seccion'] . "_" . $rgd_ ['grado'] . "' id='gp_" . $r_ ['seccion'] . "_" . $rgd_ ['grado'] . "' onChange='change(3," . $r_ ['seccion'] . "," . $rgd_ ['grado'] . ",this.value); '><option value='0'></option>$opc_grp</select>";
				}
				$n_select_grados [$cont] = "gd_" . $r_ ['seccion'];
				$cont ++;
				$select_grados .= "<select style='display:none;' name='gd_" . $r_ ['seccion'] . "' id='gd_" . $r_ ['seccion'] . "' onChange='change(2," . $r_ ['seccion'] . ",this.value,0); '><option value='0'></option>$opc_grd</select>";
			}
		}
		?>
 </select> &nbsp; &nbsp; Todos los grados <input checked type="checkbox"
			name="ch_gd" id="ch_gd" onClick="change(5,this,'grad','Todos'); "><br />
		<div name="grad" id="grad" style="display: none;"><br />
		<strong>Grado: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong><?=$select_grados;?> &nbsp; &nbsp; A todos los grupos
    <input checked type="checkbox" name="ch_gp" id="ch_gp"
			onClick="change(6,this,'grup','Todos'); "><br />
		<div name="grup" id="grup" style="display: none;"><br />
		<strong>Grupo: &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;
		&nbsp; &nbsp; &nbsp;</strong> 
	  <?=$select_grupos;?></div>
		</div>
		</div>
		</div>
		</td>
	</tr>
	<tr>
		<th align="left">Nota:</th>
		<td><input size="60" name="nota" id="nota" value="<?=$row ['nota'];?>" /></td>
	</tr>
</table>
<script language="javascript" type="text/javascript"><!--
function alertas(){
 alert(
  'seccion: '+document.getElementById('seccion').value+','+'\n'+
  'grado: '+document.getElementById('grado').value+','+'\n'+
  'grupo: '+document.getElementById('grupo').value+','+'\n'+
  'aplica_a: '+document.getElementById('aplica_a').value+','+'\n'+
  'todas_secciones: '+document.getElementById('todas_secciones').value+','+'\n'+
  'todos_grados: '+document.getElementById('todos_grados').value+','+'\n'+
  'todos_grupos: '+document.getElementById('todos_grupos').value+'.'
  );
}
function abrecalendario(field)
{ window.open('calendar.php?campo='+field, 'calendar', 'width=400,height=300,status=yes');}
function change(n,a,b,c){
  var id_campo="";
  var display='inline';
  var aplica_a='';
  var seccion=0;
  var grado=0;
  var grupo=0;
  var v='N';
  switch(n){
    case 1: id_campo='gd_'+a;
            aplica_a=a;
			seccion=a;
			break;
    case 2: id_campo='gp_'+a+'_'+b;
            aplica_a=a+';'+b;
            seccion=a;
			grado=b;
            break;
	case 3: aplica_a=a+';'+b+';'+c;
			id_campo='gp_'+a+'_'+b;
			seccion=a;
            grado=b;
			grupo=c;
            break;
	default: if(a.checked){
				display='none';
				aplica_a=c;
				v='S';
			 }
			 id_campo=b;
			 n=n-3;
			 switch(n){
			   case 1: document.getElementById('todas_secciones').value=v;
						break;
			   case 2: document.getElementById('todos_grados').value=v;
						break;
			   case 3: document.getElementById('todos_grupos').value=v;
						break;
			 }
			 if(n>1){
				seccion=document.getElementById('seccion').value;
				aplica_a=seccion;
			 }
			 if(n>2){
				grado=document.getElementById('grado').value;
				aplica_a=seccion+';'+grado;
			 }
             break;
  }
  document.getElementById('seccion').value=seccion;
  document.getElementById('grado').value=grado;
  document.getElementById('grupo').value=grupo;
  document.getElementById('aplica_a').value=aplica_a;
  if(n<2){<?
		foreach ( $n_select_grados as $n_s )
			echo "document.getElementById('$n_s').style.display='none';";
	   ?>}
  if(n<3){<?
		foreach ( $n_select_grupos as $n_s )
			echo "document.getElementById('$n_s').style.display='none';";
	   ?>}
  document.getElementById(id_campo).style.display=display;
}
 <?
		if($row['activo']=='S')
			echo "document.getElementById('ch_act').checked=true;";
		if($row['alumno']==0){
			echo "document.getElementById('ch_a').checked=false;
      document.getElementById('alm').style.display='inline';";
			if(($row['todas_secciones']!='S') && ($row['seccion']!=0)){
				echo "document.getElementById('ch_s').checked=false;  
	  change(4,'ch_s','sec','Todos'); change(1,".$row['seccion'].",0,0);";
				if(($row['todos_grados']!='S') && ($row['grado']!=0)){
					echo "document.getElementById('ch_gd').checked=false; 
	   change(5,'ch_gd','grad','Todos'); 
	    change(2,".$row['seccion'].",".$row['grado'].",0); 
	   document.getElementById('gd_".$row['seccion']."').value='".$row['grado']."';";
					if(($row['todos_grupos']!='S') && ($row['grupo']!=0)){
						echo "document.getElementById('ch_gp').checked=false; 
	     change(6,'ch_gp','grup','Todos');
		 change(3,".$row['seccion'].",".$row['grado'].",'".$row['grupo']."'); 
		 document.getElementById('gp_".$row['seccion']."_".$row['grado']."').value='".$row['grupo']."';
		 document.getElementById('grupo').value='".$row['grupo']."';";
					}
				}
			}
		}
		?>dateformat('fecha_ini'); dateformat('fecha_fin');
   --></script>
<?
	}
	
	// Formulario de Opciones
	if ($_GET ['tabla'] == 'test_opciones') {
		?><input type="hidden" name="id_pregunta" id="id_pregunta"
	value="<?=$_GET ['id_pregunta'];?>" />
<table>
	<tr>
		<th align="left">Opcion: <input size="60" name="opcion" id="opcion"
			value="<?=$row ['opcion']?>" /></th>
	</tr>
	<th align="left">Puntos: <input size="1" name="puntos" id="puntos"
		value="<?=$row ['puntos']?>"
		onKeyPress="if(event.keyCode<48 || event.keyCode>57) event.returnValue=false;" /></th>
	</tr>
	<tr><td><br></br></td></tr>
</table><?
	}
	
	// Formulario de Areas Valor
	if ($_GET ['tabla'] == 'areas_valor') {
		?><input type="hidden" name="ciclo" id="ciclo"
	value="<?=$periodo_actual;?>" />
<table>
	<tr>
		<th align="left">Area: <input size="6" maxlength="10" name="area"
			id="area" value="<?=$row ['area']?>" /></th>
	</tr>
	<tr>
		<th align="left">Nombre: <input size="60" name="nombre" id="nombre"
			value="<?=$row ['nombre']?>" /></th>
	</tr>
</table><?
	}
	
	// Formulario de Areas Valor Descripcion
	if ($_GET ['tabla'] == 'areas_valor_detalle') {
		echo "<script language='javascript'>document.getElementById('tabla').value='';</script><table>";
		$cnt = 1;
		$result_ = mysql_query ( "SELECT * FROM areas_valor WHERE ciclo=$periodo_actual", $link ) or die ( mysql_error () );
		while ( $row_ = mysql_fetch_array ( $result_ ) ) {
			$cnt ++;
			echo "<tr><th align='left' valign='top'>" . $row_ ['nombre'] . "</th><td>
    <input type='hidden' id='tabla_$cnt' name='tabla_$cnt' value='areas_valor_detalle'>
    <input type='hidden' id='id_$cnt' name='id_$cnt' value=''>
    <input type='hidden' id='agr_modif_borr_$cnt' name='agr_modif_borr_$cnt' value='agregar'>
    <input type='hidden' id='campos_$cnt' name='campos_$cnt' value='$campos'>
    <input type='hidden' id='campoid_$cnt' name='campoid_$cnt' value='$campoid'>
	<input type='hidden' id='ciclo_$cnt' name='ciclo_$cnt' value='$periodo_actual' />
    <input type='hidden' id='alumno_$cnt' name='alumno_$cnt' value='" . $_GET ['alumno'] . "' />
    <input type='hidden' id='id_area_valor_$cnt' name='id_area_valor_$cnt' value='" . $row_ ['id_area_valor'] . "' />
	<textarea id='contenido_$cnt' name='contenido_$cnt' rows='3' cols='80'></textarea></td></tr>";
			$result_existe = mysql_query ( "SELECT * FROM areas_valor_detalle WHERE ciclo=$periodo_actual AND alumno=" . $_GET ['alumno'] . " AND id_area_valor=" . $row_ ['id_area_valor'], $link ) or die ( mysql_error () );
			while ( $r_existe = mysql_fetch_array ( $result_existe ) )
				echo "<script language='javascript'>
	            document.getElementById('id_$cnt').value='" . $r_existe ['id_area_valor_detalle'] . "';
				document.getElementById('agr_modif_borr_$cnt').value='modificar';
				document.getElementById('contenido_$cnt').value='" . $r_existe ['contenido'] . "'; </script>";
		}
		echo "<tr><th colspan='100%'><input type='submit' value='Guardar'></th></tr></table><script language='javascript'>document.getElementById('n_filas').value='$cnt';</script>";
	}
	
	// Formulario de Plan de Mejora
	if ($_GET ['tabla'] == 'plan_mejora') {
		?><input type="hidden" name="ciclo" id="ciclo"
	value="<?=$periodo_actual;?>" />
<table>
	<tr>
		<th align="left">Aspecto: <input size="6" maxlength="10"
			name="aspecto" id="aspecto" value="<?=$row ['aspecto']?>" /></th>
	</tr>
	<tr>
		<th align="left">Nombre: <input size="60" name="nombre" id="nombre"
			value="<?=$row ['nombre']?>" /></th>
	</tr>
</table><?
	}
	
	// Formulario de Plan de Mejora Descripcion
	if ($_GET ['tabla'] == 'plan_mejora_descripcion') {
		echo "<script language='javascript'>document.getElementById('tabla').value='';</script><table>";
		$cnt = 1;
		$result_ = mysql_query ( "SELECT * FROM plan_mejora WHERE ciclo=$periodo_actual", $link ) or die ( mysql_error () );
		while ( $row_ = mysql_fetch_array ( $result_ ) ) {
			echo "<tr><th align='left' colspan=2>" . $row_ ['nombre'] . "</th></tr>
         <tr><td>Entrevista</td><td align='center'>Contenido</td></tr>";
			$existe_entrevista = true;
			$entrevista = 0;
			while ( $existe_entrevista ) {
				$cnt ++;
				$entrevista ++;
				echo "<tr><td align='center' valign='top'>$entrevista</td><td>
      <input type='hidden' id='tabla_$cnt' name='tabla_$cnt' value='plan_mejora_descripcion'>
      <input type='hidden' id='id_$cnt' name='id_$cnt' value=''>
      <input type='hidden' id='agr_modif_borr_$cnt' name='agr_modif_borr_$cnt' value='agregar'>
      <input type='hidden' id='campos_$cnt' name='campos_$cnt' value='$campos'>
      <input type='hidden' id='campoid_$cnt' name='campoid_$cnt' value='$campoid'>
	  <input type='hidden' id='ciclo_$cnt' name='ciclo_$cnt' value='$periodo_actual' />
      <input type='hidden' id='alumno_$cnt' name='alumno_$cnt' value='" . $_GET ['alumno'] . "' />
      <input type='hidden' id='id_plan_$cnt' name='id_plan_$cnt' value='" . $row_ ['id_plan'] . "' />
      <input type='hidden' id='entrevista_$cnt' name='entrevista_$cnt' value='$entrevista' />
	  <textarea id='contenido_$cnt' name='contenido_$cnt' rows='3' cols='80'></textarea>
	  </td></tr>";
				$existe_entrevista = false;
				echo "";
				$result_existe = mysql_query ( "SELECT * FROM plan_mejora_descripcion WHERE ciclo=$periodo_actual AND alumno=" . $_GET ['alumno'] . " AND id_plan=" . $row_ ['id_plan'] . " AND entrevista=$entrevista", $link ) or die ( mysql_error () );
				while ( $r_existe = mysql_fetch_array ( $result_existe ) ) {
					$existe_entrevista = true;
					echo "<script language='javascript'>
	            document.getElementById('id_$cnt').value='" . $r_existe ['id_plan_desc'] . "';
				document.getElementById('agr_modif_borr_$cnt').value='modificar';
				document.getElementById('contenido_$cnt').value='" . $r_existe ['contenido'] . "';</script>";
				}
			}
		}
		echo "<tr><th colspan='100%'><input type='submit' value='Guardar'></th></tr></table><script language='javascript'>document.getElementById('n_filas').value='$cnt';</script>";
	}
	
	// Formulario de Datos de Alumno o datos de Familia
	if ($_GET ['tabla'] == 'expediente_datos') {
		?><script></script>
<table><?
		$valida_fechas = "";
		$select = '';
		$campos = '';
		$campoid = '';
		$id_row = '';
		$result_ = mysql_query ( "Select * from expediente_datos where tablas='" . $_GET ['tipo'] . "'", $link ) or die ( mysql_error () );
		while ( $row_ = mysql_fetch_array ( $result_ ) ) {
			if ($row_ ['consulta'] == 'N')
				$select .= "<option value='" . $row_ ['id_campo'] . "'>" . $row_ ['nombre'] . "</option>";
			elseif (! empty ( $_GET ['administra_test'] )) {
				?>
  <tr align='left'>
		<th><input type='submit'
			onClick="document.getElementById('edita').value='N'; document.getElementById('consulta').value='N'; document.getElementById('id').value='<?=$row_ ['id_campo']?>';"
			value='Quitar' /><?=$row_ ['nombre']?> Edita:<?=$row_ ['edita']?></th>
	</tr> <?
			} else {
				echo "<tr align='left'><th>" . $row_ ['nombre'] . "</th><td>";
				$bloqueado = "";
				if ($_GET ['tipo'] == 'alumnos') {
					$campoid = 'alumno';
					$id_row = $_GET ['alumno'];
				}
				if ($_GET ['tipo'] == 'familias') {
					echo "<input type='hidden' id='alumno' name='alumno' value='" . $_GET ['alumno'] . "'>";
					$campoid = 'familia';
					$id_row = mysql_result ( mysql_query ( "select familia from alumnos where alumno=" . $_GET ['alumno'], $link ), 0, 0 );
				}
				$result = mysql_query ( "Select * from " . $_GET ['tipo'] . " where $campoid=$id_row", $link ) or die ( mysql_error () );
				$row = mysql_fetch_array ( $result );
				if ($row_ ['edita'] == 'N')
					$bloqueado .= " disabled='disabled' STYLE='background-color: LemonChiffon;'";
				if ($row_ ['nunca_cambia'] == 'S')
					$bloqueado = " disabled='disabled' STYLE='background-color: LemonChiffon;'";
				if ($bloqueado == "")
					$campos .= "," . $row_ ['campo'] . "";
				$campo = $row_ ['campo'];
				switch ($campo) {
					case 'seccion' :
						echo "<input size='20' value='" . mysql_result ( mysql_query ( "select nombre from secciones, parametros where seccion='" . $row ["seccion"] . "' and parametros.periodo=secciones.ciclo", $link ), 0, 0 ) . "' STYLE='background-color: #C0C0C0;' readonly>";
						break;
					case 'seccion_ingreso' :
						echo "<input size='20' value='" . mysql_result ( mysql_query ( "select nombre from secciones where seccion='" . $row ["seccion_ingreso"] . "' and ciclo=" . $row ['periodo_ingreso'], $link ), 0, 0 ) . "' STYLE='background-color: #C0C0C0;' readonly>";
						break;
					case 'beca' :
						echo "<input size='40' value='" . mysql_result ( mysql_query ( "select descripcion from becas where beca=" . $row ["beca"], $link ), 0, 0 ) . "' STYLE='background-color: #C0C0C0;' readonly>";
						break;
					case 'beca_2' :
						echo "<input size='40' value='" . mysql_result ( mysql_query ( "select descripcion from becas where beca=" . $row ["beca_2"], $link ), 0, 0 ) . "' STYLE='background-color: #C0C0C0;' readonly>";
						break;
					case 'ciudad_nacimiento' :
						echo "<input size='40' value='" . mysql_result ( mysql_query ( "select nombre from ciudades where ciudad=" . $row ["ciudad_nacimiento"], $link ), 0, 0 ) . "' $bloqueado >";
						break;
					case 'sexo' :
						$sexo = "Masculino";
						if (strtoupper ( $row ["sexo"] ) == "F")
							$sexo = "Femenino";
						echo "<input value='$sexo' size='10' STYLE='background-color: #C0C0C0;' readonly>";
						break;
					case 'activo' :
						switch (strtoupper ( $row ["activo"] )) {
							case "A" :
								$activo = "Activo";
								break;
							case "I" :
								$activo = "Inactivo";
								break;
							case "E" :
								$activo = "Exalumno";
								break;
							default :
								$activo = "Baja";
								break;
						}
						echo "<input value='$activo' size=7 STYLE='background-color: #C0C0C0;' readonly>";
						break;
					case '' :
						echo "";
						break;
					default :
						if ($row_ ['size'] == 'F') {
							$fecha = formatDate ( $row [$campo] );
							echo "<input value='$fecha' size='10' maxlength='12' name='$campo' id='$campo' $bloqueado ";
							if ($bloqueado == "") {
								$valida_fechas .= ',$campo';
								?>
			   onKeyDown="javascript:writeDate('<?=$campo;?>')" onBlur="if(formatoFechas('<?=$campo;?>',1)) fechaInsertar ('<?=$campo;?>');" <?
							}
							echo ">";
						} elseif ($row_ ['size'] == 'S') {
							$partes = explode ( ",", $row ['maxlenght'] );
							$campo_igual = $partes [0];
							$muestra = $partes [1];
							$tabla = $partes [2];
							echo "<select $bloqueado name='$campo' id='$campo'>";
							$rslt = mysql_query ( "select * from $tabla", $link ) or die ( mysql_error () );
							while ( $row_cd = mysql_fetch_array ( $rslt ) ) {
								$selected = '';
								if (strtoupper ( $row_cd [$campo_igual] ) == strtoupper ( $row [$campo] ))
									$selected = 'selected';
								echo "<option $selected value=" . $row_cd [$campo_igual] . ">" . $row_cd ['nombre'] . "</option>";
							}
							echo "</select>";
						} else
							echo "<input name='$campo' id='$campo' $bloqueado value='" . $row [$campo] . "' size='" . $row_ ['size'] . "'  maxlength='" . $row_ ['maxlength'] . "' >";
				} //cierre del switch if($puede_guardar){ }
				echo "</td></tr>";
			}
		}
		if (empty ( $_GET ['administra_test'] ))
			echo " <tr><th colspan='100%'><input type='submit' value='Guardar'></td></tr>
              <script language='javascript'>
	            document.getElementById('tabla').value='" . $_GET ['tipo'] . "';
				document.getElementById('id').value='$id_row';
				document.getElementById('campos').value='$campos';
				document.getElementById('campoid').value='$campoid';</script>";
		
		//HISTORIAL DE ALUMNOS

		if($_GET['tipo']=='alumnos' && $_GET[administra_test]!='S'){
			$queryHistorialAlumnos=mysql_query("select secciones.nombre, historial_alumnos.periodo, historial_alumnos.grado, historial_alumnos.grupo, CONCAT(titulares.nombre, ' ', titulares.apellido_paterno, ' ',  titulares.apellido_materno) as Nombre_de_titular, CONCAT(preceptores.nombre,' ',preceptores.apellido_paterno,' ',preceptores.apellido_materno) as Nombre_de_preceptor from historial_alumnos inner join secciones on secciones.seccion=historial_alumnos.seccion AND secciones.ciclo=historial_alumnos.periodo inner join personal as titulares on titulares.empleado=historial_alumnos.titular inner join personal as preceptores on preceptores.empleado=historial_alumnos.preceptor where alumno=".$_GET['alumno']);
			//SÓLO SI EL ALUMNO YA TIENE HISTORIAL -registros en historial_Alumnos- SE MUESTRA LA TABLA.
			if($rowsHistorial=mysql_fetch_array($queryHistorialAlumnos, MYSQL_NUM)){
				echo '<tr><td><p></p></td></tr><tr><td><b>Historial del alumno</b></td><td><table border="1" bgcolor="white">';
				echo utf8_decode("<tr>
					<th>Sección</th>
					<th>Ciclo</th>
					<th>Grado</th>
					<th>Grupo</th>
					<th>Nombre del titular</th>
					<th>Nombre del preceptor</th>
					</tr>
				");
				do{
					echo "<tr>
						   <td>$rowsHistorial[0]</td>
						   <td>$rowsHistorial[1]</td>
						   <td>$rowsHistorial[2]</td>
						   <td>$rowsHistorial[3]</td>
						   <td>$rowsHistorial[4]</td>
						   <td>$rowsHistorial[5]</td>
						   </tr>
					";
				}while($rowsHistorial=mysql_fetch_array($queryHistorialAlumnos, MYSQL_NUM));
			
				echo "</table></td></tr>";
			}
		}
		
		if (! empty ( $_GET ['administra_test'] )) {
			echo "
   <tr>
      <td>
         <input type='hidden' value='S' name='consulta' id='consulta'>
		 <input type='hidden' value='N' name='edita' id='edita'>";
			?> 
         Edita: <input type='checkbox'
		onClick="if(this.checked) document.getElementById('edita').value='S'; else document.getElementById('edita').value='N';">
	Muestra:
	<select name='campo' id='campo'
		onChange="document.getElementById('id').value=this.value;">
		<option></option><?=$select?>
                  </select>
	<input type='submit' value='guardar'><?
			echo "</td></tr>";
		}
		?>
</table><?
	}
}

if (! empty ( $_GET ['ficha'] ) and ! empty ( $_GET ['alumno'] )) {
	$r_a = mysql_fetch_array ( mysql_query ( "SELECT * FROM alumnos WHERE alumno=" . $_GET ['alumno'], $link ) );
	$r_f = mysql_fetch_array ( mysql_query ( "SELECT CONCAT_WS(' ',apellido_paterno_padre,apellido_materno_padre,nombre_padre) as padre, CONCAT_WS(' ',apellido_paterno_madre,apellido_materno_madre,nombre_madre) as madre  FROM familias WHERE familia=" . $r_a ['familia'], $link ) );
	?>
<html>
<head>
<meta http-equiv="Content-Type"	content="text/html; charset=Latin1">
</head>
<body>
<form>
<table width="700" align="center" border="1">
	<tr>
		<td align="right" colspan="2"><input
			style="border: double; text-align: center" readonly="readonly"
			name="fecha" id="fecha" size="45" />
		<h1>C O N T R O L &nbsp; &nbsp; D E &nbsp; &nbsp; E N T R E V I S T A
		S</h1>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<th>Secci&oacute;n: <input readonly="readonly"
			value="<?=mysql_result ( mysql_query ( "SELECT nombre FROM secciones WHERE ciclo='$periodo_actual' and seccion='" . $r_a ['seccion'] . "'", $link ), 0, 0 );?>" />
		&nbsp; &nbsp; Grado: <input readonly="readonly"
			value="<?=$r_a ['grado'];?>" size="4" /> &nbsp; &nbsp; Grupo<input
			readonly="readonly" value="<?=$r_a ['grupo']?>" size="4" /></th>
	</tr>
	<tr>
		<th align="left">Nombre de Alumno</th>
		<td><?=$r_a ['apellido_paterno']?> <?=$r_a ['apellido_materno']?> <?=$r_a ['nombre']?></td>
	</tr>
	<tr>
		<th align="left">Nombre del Pap&aacute;</th>
		<td><?=$r_f ['padre']?></td>
	</tr>
	<tr>
		<th align="left">Nombre de la Mam&aacute;</th>
		<td><?=$r_f ['madre']?></td>
	</tr>
	<tr>
		<th align="left">Asistieron a la Entrevista</th>
		<td><select>
			<option>SI
			
			
			<option>NO
		
		</select> &nbsp; &nbsp; &nbsp; Pap&aacute; <input type="checkbox" />
		&nbsp; Mam&aacute; <input type="checkbox" /> &nbsp; &nbsp; &nbsp;
		&nbsp; &nbsp; &nbsp; &nbsp; Entrevista No. <input size="5" /></td>
	</tr>
	<tr>
		<th align="left">Motivo de la Entrevista</th>
		<td><input size="77" /></td>
	</tr>
	<tr>
		<td colspan="2">
		<h3>PLAN PERSONAL DE MEJORA</h3><?
	$p_ant = 0;
	$rs_ = mysql_query ( "SELECT * FROM plan_mejora_descripcion WHERE ciclo='$periodo_actual' AND alumno=" . $_GET ['alumno'] . " ORDER BY id_plan,entrevista  ", $link ) or die ( mysql_error () );
	while ( $rw = mysql_fetch_array ( $rs_ ) ) {
		if ($p_ant != $rw ['id_plan']) {
			$p_ant = $rw ['id_plan'];
			echo "</td></tr><tr><th align='left'>" . mysql_result ( mysql_query ( "SELECT nombre FROM plan_mejora  WHERE ciclo='$periodo_actual' and id_plan='" . $rw ['id_plan'] . "'", $link ), 0, 0 ) . "</th><td>";
		}
		echo $rw ['contenido'] . " &nbsp; ";
	}
	?>   
   </td>
	</tr>
	<tr>
		<th colspan="2"><input onClick="window.print();" type="button"
			value="Imprimir" id="btn" name="btn"></th>
	</tr>
</table>
<script language="javascript">
var month_names = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var dt    = new Date();
var year  = dt.getFullYear();
var month = dt.getMonth();
var day   = dt.getDate();
document.getElementById('fecha').value=day+' de '+month_names[month]+' del '+year;
</script> <?
}
?> </form>
</body>
</html>