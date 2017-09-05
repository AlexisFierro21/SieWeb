<? session_start();
include("config.php");
include("functions.php");
$clave=$_SESSION["clave"];
$tipo=$_SESSION['tipo'];
$ciclo=$_SESSION['ciclo'];
//definimos de quien se van a actualizar los datos y si se puede guardar dependiendo del tipo de sesion abierta
$puede_guardar=true;
$actualizar_datos_de=0;
/*  1.- Alumnos
    2.- Familia
	3.- Personal
	4.- Exalumnos
	5.- Otros Hijos
*/

$quien="";
$slct="";
$submit="";
$alumnoN="";
$tabla="";
$where="";
if(!empty($_GET["dequien"])) $quien=$_GET["dequien"];
else $quien=$tipo;
if(!empty($_POST["dequien"])) $quien=$_POST["dequien"];
switch($quien)
{ case "alumno":   $actualizar_datos_de=1;
    if($tipo=="personal"){ $puede_guardar=false; if(!empty($_GET['alumnoN'])) $alumnoN=$_GET['alumnoN']; }
	if($tipo=="familia")
	{ if(!empty($_POST['alumnoN'])) $alumnoN=$_POST['alumnoN'];
	  $alumnos=mysql_query("select * from alumnos, parametros where familia='$clave' and activo = 'A' and alumnos.plantel = parametros.sede ",$link)or die(mysql_error());
      $slct="<form action='datos.php' method='post' name='fr' id='fr'><h3> Hij@ : 
    <select onChange='fr.submit();' name='alumnoN' id='alumnoN'>";
      while($row=mysql_fetch_array($alumnos))
      { if($alumnoN=="") $alumnoN=$row["alumno"];
        $slctd="";
        if($row["alumno"]==$alumnoN)$slctd="selected";
        $slct.="<option value=".$row["alumno"]." $slctd>".$row["nombre"]."</option>";
      }
      $slct.="<input type='hidden' name='dequien' id='dequien' value='$quien'></select></h3></form>";
	}
	$tabla="alumnos";
	//$where=" alumno='$alumnoN'";|
	$where=" alumno=".$alumnoN;
	
	$campos=array("curp","doctor","telefono_doctor","alergias","tipo_sangre","clinica","telefono_clinica","aseguradora","poliza","comentarios","usa_lentes","nombre_emer_1","parentesco_emer_1","tel_casa_emer_1","tel_ofna_emer_1","tel_cel_emer_1","nombre_emer_2","parentesco_emer_2","tel_casa_emer_2","tel_ofna_emer_2","tel_cel_emer_2","autoriza_medico");
		
	break;
  case "familia":  $actualizar_datos_de=2; $familia=$clave;
    if($tipo=="personal")
	{ $puede_guardar=false;
	  $alumnoN=$_GET["alumnoN"];
	  $familia=mysql_result(mysql_query("select familia from alumnos where alumno='$alumnoN'",$link),0,0);
	}
    $tabla="familias";
	$where=" familia='$familia'";
	$submit="onSubmit='dateformat(fecha_boda); dateformat(fecha_nac_padre); dateformat(fecha_nac_madre); '";
	$campos=array("nombre_familia","fecha_boda","direccion","cruce_calle_1","cruce_calle_2","colonia","facturar_a","cp","direccion_facturar_a","telefonos","ciudad","ciudad_facturar_a","rfc_facturar_a","apellido_paterno_padre","e_mail_padre","apellido_materno_padre","otras_actividades_padre","nombre_padre","membresias_padre","fecha_nac_padre","asociaciones_padre","nacionalidad_padre","titulo_padre","profesion_padre","empleo_padre","especialidad_padre","direccion_padre","religion_padre","giro_empresa_padre","curp_padre","puesto_padre","celular_padre","telefono_padre","apellido_paterno_madre","e_mail_madre","apellido_materno_madre","otras_actividades_madre","nombre_madre","membresias_madre","fecha_nac_madre","asociaciones_madre","nacionalidad_madre","titulo_madre","profesion_madre","empleo_madre","especialidad_madre","direccion_madre","religion_madre","giro_empresa_madre","curp_madre","puesto_madre","celular_madre","fecha_modificacion","telefono_madre","colonia_facturar_a","cp_facturar_a");
	break;
  case "personal": $actualizar_datos_de=3;
    $tabla="personal";
	$where=" empleado='$clave'";
	$submit="onSubmit='dateformat(fecha_boda); dateformat(fecha_nacimiento); dateformat(fecha_nacimiento_conyuge);'";
    $campos=array("iniciales","celular","estado_civil","e_mail","fecha_boda","religion","sexo","nacionalidad","direccion","profesion","colonia","otra_empresa","cp","direccion_empresa","ciudad","puesto_empresa","cruce_con_calle_1","giro_empresa","cruce_con_calle_2","telefonos_empresa","telefonos","imss","doctor","rfc","telefono_doctor","tipo_sangre","apellido_paterno_conyuge","clinica","apellido_materno_conyuge","telefono_clinica","nombre_conyuge","aseguradora","fecha_nacimiento_conyuge","poliza","comentarios","fecha_nacimiento","celular_conyuge","email_conyuge","religion_conyuge","nacionalidad_conyuge","profesion_conyuge","trabajo_conyuge","direccion_empresa_conyuge","puesto_conyuge","giro_empresa_conyuge","telefonos_empresa_conyuge");
	break;
  case "exalumno": $actualizar_datos_de=4;
    $tabla="exalumnos";
	$where=" exalumno='$clave'";
	$submit="onSubmit='dateformat(fecha_boda); dateformat(fecha_nacimiento_conyuge);'";
	$campos=array("telefono","telefono_2","celular","direccion","cruce_con_calle_1","cruce_con_calle_2","colonia","cp","ciudad","fecha_boda","email","tipo_actividad","lugar_estudia","carrera","empresa_trabaja","direccion_empresa","telefono_empresa","antiguedad","puesto_empresa","apellido_paterno_conyuge","apellido_materno_conyuge","nombre_conyuge","fecha_nacimiento_conyuge","hijos","estado","pais"); 
	break;
  case "otrohijo": $actualizar_datos_de=5;
    if($tipo=="familia") { $tabla="familia_otroshijos"; $campo="familia"; }
    if($tipo=="personal"){ $tabla="exalumnos_hijos";    $campo="exalumno"; }
	$where="$campo='$clave'";
	break;
}

if(!empty($_POST['guarda']))
{ $today = getdate();
  $ano=$today["year"];
  $mes=$today["mon"];
  $tipo_usuario=$_SESSION['letter'];
  actualizaEstadistico($tipo_usuario,$clave,$ano,$mes,2);
  if($actualizar_datos_de==5)
  { $borra=mysql_query("delete from $tabla where $where",$link)or die(mysql_error());
	$agregados=0;
    for ($i=1;$i<16;$i++) 
    { if(!empty($_POST[$i."_2"]))
	  { $agregados++;
	    $insert=mysql_query("INSERT INTO $tabla(id_hijo, nombre, apellido_paterno, apellido_materno, fecha_nacimiento, profesion, institucion, sexo, $campo, otra_institucion )  VALUES  ( '$agregados', '".$_POST[$i."_2"]."', '".$_POST[$i."_3"]."','".$_POST[$i."_4"]."',  '".$_POST[$i."_5"]."', '".$_POST[$i."_7"]."','".$_POST[$i."_9"]."', '".$_POST[$i."_8"]."', '$clave', '".$_POST[$i."_6"]."') ",$link)or die(mysql_error());
	  }
    }
  }
  else
  { $sql="Update $tabla set ";
    foreach ($campos as $campo) $sql.=" $campo='".$_POST[$campo]."',";
    $sql.=" fecha_modificacion=Now() where $where";
	if($version_sie_local=="colmenares")
	{
		if($actualizar_datos_de==2 and $hmlgcn==1)
		{ $link_gral=mysql_connect($server,$userName,$password)or die('No se pudo conectar db gral: ' . mysql_error());
		  mysql_select_db($DB_gral,$link_gral)or die("No se pudo seleccionar DB gral");
		  mysql_query($sql,$link_gral)or die(mysql_error());
		  mysql_query("update familias set fecha_web_padres=Now(), plantel_modificacion=$sede, usuario_modificacion='".$_SESSION['userName']."' where $where",$link_gral)or die(mysql_error());
		  $r_fam_sede=mysql_query("select base from familias_x_sedes,bases_x_sedes where $where and familias_x_sedes.sede=bases_x_sedes.sede",$link_gral)or die(mysql_error());
		  while($rw_fxs = mysql_fetch_array($r_fam_sede))
		  { $link_fam=mysql_connect($server,$userName,$password)or die('No se pudo conectar db fam: ' . mysql_error());
			mysql_select_db($rw_fxs['base'],$link_fam)or die("No se pudo seleccionar DB fam");
			mysql_query($sql,$link_fam)or die(mysql_error());
		  } 
		  mysql_close($link_gral);
		  $link = mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
		  mysql_select_db($DB)or die("No se pudo seleccionar DB");
		}
	} 
	mysql_query($sql,$link)or die(mysql_error());
  }
  echo" <script language='javascript'>alert('Datos actualizados correctamente');</script>";
}
$result=mysql_query("select * from $tabla where $where",$link)or die(mysql_error());
$vacio=true;
if(mysql_affected_rows($link)<=0) $vacio=false;
if($actualizar_datos_de<5) $row=mysql_fetch_array($result);
$bloqueado="";
 ?>
<html>
<style>

 td{
 border:0px solid #ccc;
 height: 10px;
 font: 150% sans-serif;
 border-spacing:0px 0px;
 border-collapse:collapse;
  padding-top: 1px;
  padding-right: 1px;
  padding-bottom: 1px;
  padding-left: 1px;
 }

</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Datos</title></head>
<script language="" type="text/javascript"><!--
function dateformat(txtfecha)
{ var fecha=txtfecha.value;
  if(fecha!='')
  { var yr=fecha.substring(6,10);
    var mo=fecha.substring(3,5);
    var dy=fecha.substring(0,2);
    txtfecha.value=""+yr+"-"+mo+"-"+dy+"";
  }
}
--></script>
<body bgcolor="<?=$color_main;?>">
<? echo $slct;
$frm="<form name='formu' id='formu' action='datos.php' method='post' $submit>"; 
//primer formulario
if($actualizar_datos_de==1)
{  
  $sec=$row['seccion'];
  $grad=$row['grado'];
  $rst_ = mysql_query ("SELECT nombre from grados where seccion='$sec' and ciclo=$ciclo and grado=$grad ",$link) or die ("SELECT nombre from grados where seccion='$sec' and ciclo=$ciclo and grado=$grad".mysql_error());
	while($rs_=mysql_fetch_array($rst_))
	  {
	  	$ngrado= $rs_['nombre'];
	  }
  $sexo="Masculino"; 
  if(strtoupper($row["sexo"])=="F") $sexo="Femenino";
  if($tipo=="personal") $bloqueado=" STYLE='background-color: #C0C0C0;' readonly";
  switch(strtoupper($row["activo"]))
  { case "A": $activo="Activo"; break;
    case "I": $activo="Inactivo"; break;
    case "E": $activo="Exalumno"; break;
    default:  $activo="Baja"; break;
  } ?> 
<?=$frm;?> 
<table border="0">
<tr> 

<td><font size="2">Alumno</font></td><td width="230"><input type="text" name='alumno' id='alumno' value="<?=$row["alumno"];?>" size="8" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size='2'>Fecha Ultima Actualizacion</font></td><td><input type='text' name='fecha_modificacion' id='fecha_modificacion' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['fecha_modificacion'];?>' size='30' maxlength=30 tabindex='10'></td>
</tr>
<tr>
<td><font size="2">Apellido Paterno</font></td><td><input type="text" value="<?=$row["apellido_paterno"];?>" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size="2">CURP</font></td><td>            <input type="text" value="<?=$row["curp"]?>" size="30" STYLE="background-color: #C0C0C0;" readonly></td>
</tr>
<tr>
<td><font size="2">Apellido Materno</font></td><td><input type="text" value="<?=$row["apellido_materno"];?>" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size="2">Autoriza consulta</font></td>
<td> 
			<select name='autoriza_medico' id='autoriza_medico'>
            	<option value="S">SI</option>
                <option value="N">NO</option>
            </select>
</td>
</tr>
<tr>
<td><font size="2">Nombre</font></td><td><input type="text" name='nombre' id='nombre' value="<?=$row["nombre"];?>" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size="2">Doctor</font></td><td><input type="text" <?=$bloqueado;?> name='doctor' id='doctor' value="<?=$row["doctor"];?>" size="40" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Secci&oacute;n</font></td><td><input type="text" value="<?=mysql_result(mysql_query("select nombre from secciones, parametros where seccion='".$row["seccion"]."' and parametros.periodo=secciones.ciclo",$link),0,0);?>" size="20" STYLE="background-color: #C0C0C0;" readonly ></td>
<td><font size="2">Celular Doctor</font></td><td><input type="text" <?=$bloqueado;?> name='telefono_doctor' id='telefono_doctor' value="<?=$row["telefono_doctor"];?>" size="20" maxlength=10>
  <font size="1"><em>   Solamente 10 digitos</em></font></td>
</tr>
<tr>
<td><font size="2">Grado</font></td><td><input type="text" value="<?=$row["grado"]."  ".$ngrado;?>" size="16" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size="2">Alergias</font></td>
<td width="432" height="26"><input type="text" <?=$bloqueado;?> name='alergias' id='alergias' value="<?=$row["alergias"];?>" size="50" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Grupo</font></td><td><input type="text" value="<?=$row["grupo"];?>" size="2" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size="2">Tipo de Sangre</font></td><td><input type="text" <?=$bloqueado;?> name='tipo_sangre' id='tipo_sangre' value="<?=$row["tipo_sangre"];?>" size="3" maxlength=3 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Matr&iacute;cula</font></td><td><input type="text" value="<?=$row["matricula"];?>" size="8" STYLE="background-color: #C0C0C0;" readonly ></td>
<td><font size="2">Cl&iacute;nica</font></td><td><input type="text" <?=$bloqueado;?> name='clinica' id='clinica' value="<?=$row["clinica"];?>" size="50" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Fecha de Nacimiento</font></td><td><input type="text" name="fecha_nacimiento" id="fecha_nacimiento" value="<?=formatDate($row["fecha_nacimiento"]) ;?>" size="15" STYLE="background-color: #C0C0C0;" readonly> <font size="1"><em>   dd/mm/aaaa</em></font></td>
<td><font size="2">Tel&eacute;fono Cl&iacute;nica</font></td><td><input type="text" <?=$bloqueado;?> name='telefono_clinica' id='telefono_clinica' value="<?=$row["telefono_clinica"];?>" size="20" maxlength=10 onBlur="this.value=this.value.toUpperCase();"><font size="1"><em>   Solamente 10 digitos</em></font></td>
</tr>
<tr>
<td><font size="2">Sexo</font></td><td><input type="text" value="<?=$sexo; ?>" size="10" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size="2">Aseguradora</font></td><td><input type="text" <?=$bloqueado;?> name='aseguradora' id='aseguradora' value="<?=$row["aseguradora"];?>" size="50" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Estatus</font></td><td><input type="text" value="<?=$activo;?>" size="7" STYLE="background-color: #C0C0C0;" readonly></td>
<td><font size="2">P&oacute;liza</font></td><td><input type="text" <?=$bloqueado;?> name='poliza' id='poliza' value="<?=$row["poliza"];?>" size="15" maxlength=15 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Comentarios</font></td><td><input type="text" <?=$bloqueado;?> name='comentarios' id='comentarios' value="<?=$row["comentarios"];?>" size="30" maxlength=60 onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size="2">Usa Lentes</font></td>
		<td>
        	<!--<input type="text" <?=$bloqueado;?> name='usa_lentes' id='usa_lentes' value="<?=$row["usa_lentes"];?>" size="50" maxlength=60 onBlur="this.value=this.value.toUpperCase();">-->
            <select name='usa_lentes' id='usa_lentes'>
            	<option value="S">SI</option>
                <option value="N">NO</option>
            </select>
            </td>
</tr> <? if($puede_guardar){ ?>
	<input type='hidden' name='alumnoN' id='alumnoN' value='<?=$alumnoN?>'>
 <tr>
 	<td colspan="4" align="center">
        <font size="2">Telefonos En caso de emergencias<font size="2">  
    </td>
</tr>
<tr>
	<td colspan="2" align="center">
    	 <font size="2">Primer contacto</font>
    </td>
    <td colspan="2" align="center">
    	 <font size="2">Segundo contacto</font>
    </td>
</tr>
<tr>
 	<td><font size="2">Nombre:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='nombre_emer_1' id='nombre_emer_1' value="<?=$row["nombre_emer_1"];?>" size="40" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
    <td><font size="2">Nombre:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='nombre_emer_2' id='nombre_emer_2' value="<?=$row["nombre_emer_2"];?>" size="40" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
    <td><font size="2">Parentesco:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='parentesco_emer_1' id='parentesco_emer_1' value="<?=$row["parentesco_emer_1"];?>" size="40" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
    <td><font size="2">Parentesco:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='parentesco_emer_2' id='parentesco_emer_2' value="<?=$row["parentesco_emer_2"];?>" size="40" maxlength=40 onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
    <td><font size="2">Telefono de casa:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='tel_casa_emer_1' id='tel_casa_emer_1' value="<?=$row["tel_casa_emer_1"];?>" size="10" maxlength=10 onBlur="this.value=this.value.toUpperCase();"><font size="1"><em>   Solamente 10 digitos</em></font></td>
    <td><font size="2">Telefono de casa:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='tel_casa_emer_2' id='tel_casa_emer_2' value="<?=$row["tel_casa_emer_2"];?>" size="10" maxlength=10 onBlur="this.value=this.value.toUpperCase();"><font size="1"><em>   Solamente 10 digitos</em></font></td>
</tr>
<tr>
    <td><font size="2">Telefono de oficina:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='tel_ofna_emer_1' id='tel_ofna_emer_1' value="<?=$row["tel_ofna_emer_1"];?>" size="10" maxlength=10 onBlur="this.value=this.value.toUpperCase();"><font size="1"><em>   Solamente 10 digitos</em></font></td>
    <td><font size="2">Telefono de oficina:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='tel_ofna_emer_2' id='tel_ofna_emer_2' value="<?=$row["tel_ofna_emer_2"];?>" size="10" maxlength=10 onBlur="this.value=this.value.toUpperCase();"><font size="1"><em>   Solamente 10 digitos</em></font></td>
</tr>
<tr>
    <td><font size="2">Telefono m&oacute;vil:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='tel_cel_emer_1' id='tel_cel_emer_1' value="<?=$row["tel_cel_emer_1"];?>" size="10" maxlength=10 onBlur="this.value=this.value.toUpperCase();"><font size="1"><em>   Solamente 10 digitos</em></font></td>
    <td><font size="2">Telefono  m&oacute;vil:</font></td>
    <td><input type="text" <?=$bloqueado;?> name='tel_cel_emer_2' id='tel_cel_emer_2' value="<?=$row["tel_cel_emer_2"];?>" size="10" onBlur="this.value=this.value.toUpperCase();" maxlength=10>
  <font size="1"><em>   Solamente 10 digitos</em></font></td>
</tr>
 
<tr><th colspan=4 valign="bottom" height="37"><input type='submit' value="Guardar"></td></tr> <? } ?>
</table> <?
} //fin del if $actualizar_datos_de==1;


//segundo formulario 
if($actualizar_datos_de==2)
{ $fechaNacimientoM=formatDate($row["fecha_nac_madre"]); 
  if(($fechaNacimientoM=="00/00/0000")||($fechaNacimientoM=="//")) $fechaNacimientoM="";
  $fechaBoda=formatDate($row["fecha_boda"]) ;
  if(($fechaBoda=="00/00/0000")||($fechaBoda=="//")) $fechaBoda="";
  $fechaNacimientoP=formatDate($row["fecha_nac_padre"]);
  if(($fechaNacimientoP=="00/00/0000")||($fechaNacimientoP=="//")) $fechaNacimientoP="";
  if($tipo=="personal") $bloqueado=" STYLE='background-color: #C0C0C0;' readonly";
?>
<?=$frm;?>
<table>
<tr> 
<td width="130"><font size='2'>Familia</font></td><td width='300'><input type='text'name='familia' id='familia' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['familia'];?>' size='6' tabindex='5'></td>
<td><font size='2'>Fecha Ultima Actualizacion</font></td><td><input type='text' name='fecha_modificacion' id='fecha_modificacion' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['fecha_modificacion'];?>' size='30' maxlength=30 tabindex='10'></td>
</tr>

<tr>
<td><font size='2'>Nombre Familia</font></td><td><input type='text' name='nombre_familia' id='nombre_familia' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['nombre_familia'];?>' size='30' maxlength=30 tabindex='10'></td>
<td width='87'><font size='2'>Fecha Boda</font></td><td width='420'><input type='text' <?=$bloqueado;?> onKeyDown="javascript:writeDate('fecha_boda')" onBlur="if(formatoFechas('fecha_boda',1)) fechaInsertar ('fecha_boda');" name='fecha_boda' id='fecha_boda' value='<?=$fechaBoda;?>' size='15' maxlength=10 tabindex='90'><font size="1"><em>   dd/mm/aaaa</em></font></td>
</tr>
<tr>
<td><font size='2'>Direcci&oacute;n</font></td><td><input type='text' <?=$bloqueado;?> name='direccion' id='direccion' value='<?=$row['direccion'];?>' size='40' maxlength=40 tabindex='20' onBlur="this.value=this.value.toUpperCase();"> </td>
<td><u><b><font size='2'>Datos Factura:</font></b></u></td>
</tr>
<tr>
<td><font size='2'>Entre Calle 1</font></td><td><input type='text' <?=$bloqueado;?> name='cruce_calle_1' id='cruce_calle_1' value='<?=$row['cruce_calle_1'];?>' size='40' maxlength=40 tabindex='30' onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size='2'>Nombre</font></td>
<td><input type='text' <?=$bloqueado;?> name='facturar_a' id='facturar_a' value='<?=$row['facturar_a'];?>' size='50' maxlength=50 tabindex='100' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Entre Calle 2</font></td><td><input type='text' <?=$bloqueado;?> name='cruce_calle_2' id='cruce_calle_2' value='<?=$row['cruce_calle_2'];?>' size='40' maxlength=40 tabindex='40' onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size='2'>Direcci&oacute;n</font></td>
<td><input type='text' <?=$bloqueado;?> name='direccion_facturar_a' id='direccion_facturar_a' value='<?=$row['direccion_facturar_a'];?>' size='50' maxlength=40  tabindex='110' onBlur="this.value=this.value.toUpperCase();"> </td>
</tr>
<tr>
<td><font size='2'>Colonia</font></td><td><input type='text' <?=$bloqueado;?> name='colonia' id='colonia' value='<?=$row['colonia'];?>' size='40' maxlength=40 tabindex='40' onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size='2'>Colonia:</font></td><td><input type='text' <?=$bloqueado;?> name='colonia_facturar_a' id='colonia_facturar_a' value='<?=$row['colonia_facturar_a'];?>' size='50' maxlength=40  tabindex='111' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>C.P.</font></td><td><input type='text' <?=$bloqueado;?> name='cp' id='cp' value='<?=$row['cp'];?>' size='7' maxlength=5 tabindex='60'></td>
<td><font size='2'>C.P.</font></td><td><input type='text' <?=$bloqueado;?> name='cp_facturar_a' id='cp_facturar_a' value='<?=$row['cp_facturar_a'];?>' size='7' maxlength=5  tabindex='112'></td>
</tr>

<tr>
<td><font size='2'>Ciudad</font></td><td><select <?=$bloqueado;?> name='ciudad' id='ciudad' tabindex='70'>
<? $ciudades=mysql_query("select * from ciudades",$link)or die(mysql_error());
   while($row_cd=mysql_fetch_array($ciudades))
   { $selected='';
     if (strtoupper($row_cd['ciudad'])==strtoupper($row['ciudad'])) $selected='selected';
     echo"<option $selected value=".$row_cd['ciudad'].">".$row_cd['nombre']."</option>";
   } ?> </select></td>
<td><font size='2'>Ciudad</font></td><td><select <?=$bloqueado;?> name='ciudad_facturar_a' id='ciudad_facturar_a' tabindex='120'>
<? $ciudades=mysql_query("select * from ciudades",$link)or die(mysql_error());
   while($row_cd=mysql_fetch_array($ciudades))
   { $selected='';
     if (strtoupper($row_cd['ciudad'])==strtoupper($row['ciudad_facturar_a'])) $selected='selected';
     echo"<option $selected value=".$row_cd['ciudad'].">".$row_cd['nombre']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size='2'>Tel&eacute;fono Casa</font></td><td><input type='text' <?=$bloqueado;?> name='telefonos' id='telefonos' value='<?=$row['telefonos'];?>' size='10' maxlength=8 tabindex='80'> <font size="1"><em>   Solamente 8 digitos</em></font></td>
<td><font size='2'>R.F.C.</font></td><td><input type='text' <?=$bloqueado;?> name='rfc_facturar_a' id='rfc_facturar_a' value='<?=$row['rfc_facturar_a'];?>' size='18' maxlength=13 tabindex='130' onBlur="this.value=this.value.toUpperCase();"> <font size="1"><em>   No Incluir Guiones</em></font></td>
</tr>

<!--<tr>
<td><font size='2'>&nbsp;</font></td><td>&nbsp;</td>
<td><font size='2'>CURP.</font></td><td><input type='text' <?=$bloqueado;?> name='curp_facturar_a' id='curp_facturar_a' value='<?=$row['curp_facturar_a'];?>' size='15' maxlength=13 tabindex='130'></td>
</tr> -->

<tr><td><font size='2'>.</font></td><td><br><b><u>Datos Padre</u></b></td></tr>
<tr>
<td><font size='2'>Apellido Paterno</font></td><td><input type='text' name='apellido_paterno_padre' id='apellido_paterno_padre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['apellido_paterno_padre'];?>' size='30' maxlength=30 tabindex='140'></td>
<td><font size='2'>e-Mail</font></td><td><input type='text' <?=$bloqueado;?> name='e_mail_padre' id='e_mail_padre' value='<?=$row['e_mail_padre'];?>' size='50' maxlength=70  tabindex='240'></td>
</tr>
<tr>
<td><font size='2'>Apellido Materno</font></td><td><input type='text' name='apellido_materno_padre' id='apellido_materno_padre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['apellido_materno_padre'];?>' size='30' maxlength=30  tabindex='150'></td>
<td><font size='2'>Otras Actividades</font></td><td><input type='text' <?=$bloqueado;?> name='otras_actividades_padre' id='otras_actividades_padre' value='<?=$row['otras_actividades_padre'];?>' size='50' maxlength=70  tabindex='250' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Nombre</font></td><td><input type='text' name='nombre_padre' id='nombre_padre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['nombre_padre'];?>' size='30' maxlength=35  tabindex='150'></td>
<td><font size='2'>Membres&iacute;as</font></td><td><input type='text' <?=$bloqueado;?> name='membresias_padre' id='membresias_padre' value='<?=$row['membresias_padre'];?>' size='50' maxlength=70  tabindex='260'  onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Fecha Nacimiento</font></td><td><input onKeyDown="javascript:writeDate('fecha_nac_padre')" type='text' <?=$bloqueado;?> name='fecha_nac_padre' id='fecha_nac_padre' value='<?=$fechaNacimientoP ;?>' size='15' maxlength=15 tabindex='160' onBlur="if(formatoFechas('fecha_nac_padre',1)) fechaInsertar ('fecha_nac_padre');"> <font size="1"><em>   dd/mm/aaaa</em></font></td>
<td><font size='2'>Asociaciones</font></td><td><input type='text' <?=$bloqueado;?> name='asociaciones_padre' id='asociaciones_padre' value='<?=$row['asociaciones_padre'];?>' size='50' maxlength=70  tabindex='270' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Nacionalidad</font></td><td><select <?=$bloqueado;?> name='nacionalidad_padre' id='nacionalidad_padre' tabindex='170'>
<? $paises=mysql_query("select * from paises",$link)or die(mysql_error());
   while($row_pais=mysql_fetch_array($paises))
   { $selected='';
     if (strtoupper($row_pais['pais'])==strtoupper($row['nacionalidad_padre'])) $selected='selected';
     echo"<option $selected value=".$row_pais['pais'].">".$row_pais['nacionalidad']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size='2'>T&iacute;tulo</font></td><td><input type='text' <?=$bloqueado;?> name='titulo_padre' id='titulo_padre' value='<?=$row['titulo_padre'];?>' size='6' maxlength=6 tabindex='180' onBlur="this.value=this.value.toUpperCase();"></td>
<td><u><b><font size='2'>Ocupaci&oacute;n:</font></b></u></td>
</tr>
<tr>
<td><font size='2'>Profesi&oacute;n</font></td><td><select <?=$bloqueado;?> name='profesion_padre' id='profesion_padre' tabindex='190'>
<? $profesiones=mysql_query("select * from profesiones",$link)or die(mysql_error());
   while($row_prf=mysql_fetch_array($profesiones))
   { $selected='';
     if (strtoupper($row_prf['profesion'])==strtoupper($row['profesion_padre'])) $selected='selected';
     echo"<option $selected value=".$row_prf['profesion'].">".$row_prf['nombre']."</option>";
   } ?> </select></td>
<td><font size='2'>Empleo</font></td><td><input type='text' <?=$bloqueado;?> name='empleo_padre' id='empleo_padre' value='<?=$row['empleo_padre'];?>' size='50' maxlength=40 tabindex='280' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Especialidad</font></td><td><input type='text' <?=$bloqueado;?> name='especialidad_padre' id='especialidad_padre' value='<?=$row['especialidad_padre'];?>' size='40' maxlength=40  tabindex='200' onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size='2'>Direcci&oacute;n</font></td><td><input type='text' <?=$bloqueado;?> name='direccion_padre' id='direccion_padre' value='<?=$row['direccion_padre'];?>' size='50' maxlength=40 tabindex='290' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Religi&oacute;n</font></td><td><select <?=$bloqueado;?> name='religion_padre' id='religion_padre' tabindex='210'>
<? $religiones=mysql_query("select * from religiones",$link)or die(mysql_error());
   while($row_rlgn=mysql_fetch_array($religiones))
   { $selected='';
     if (strtoupper($row_rlgn['religion'])==strtoupper($row['religion_padre'])) $selected='selected';
     echo"<option $selected value=".$row_rlgn['religion'].">".$row_rlgn['nombre']."</option>";
   } ?> </select></td>
<td><font size='2'>Giro Empresa</font></td><td><select <?=$bloqueado;?> name='giro_empresa_padre' id='giro_empresa_padre' tabindex='300'>
<? $gempresas=mysql_query("select * from giros_empresas",$link)or die(mysql_error());
   while($row_gem=mysql_fetch_array($gempresas))
   { $selected='';
     if (strtoupper($row_gem['giro'])==strtoupper($row['giro_empresa_padre'])) $selected='selected';
     echo"<option $selected value=".$row_gem['giro'].">".$row_gem['descripcion']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size='2'>CURP</font></td><td><input type='text' <?=$bloqueado;?> name='curp_padre' id='curp_padre' value='<?=$row['curp_padre'];?>' size='20' maxlength=18  tabindex='220' onBlur="this.value=this.value.toUpperCase();"> <font size="1"><em>   No Incluir Guiones</em></font></td>
<td><font size='2'>Puesto</font></td><td><input type='text' <?=$bloqueado;?> name='puesto_padre' id='puesto_padre' value='<?=$row['puesto_padre'];?>' size='25' maxlength=20  tabindex='310'></td>
</tr>
<tr>
<td><font size='2'>Celular</font></td><td><input type='text' <?=$bloqueado;?> name='celular_padre' id='celular_padre' value='<?=$row['celular_padre'];?>' size='20' maxlength=10 tabindex='230'><font size="1"><em>   Solamente 10 digitos</em></font></td>
<td><font size='2'>Tel&eacute;fono Oficina</font></td><td><input type='text' <?=$bloqueado;?> name='telefono_padre' id='telefono_padre' value='<?=$row['telefono_padre'];?>' size='20' maxlength=8  tabindex='320'> 
<font size="1"><em>   Incluir la extensi�n (EXT.)</em></font> </td>
</tr>
<tr><td><font size='2'>.</font></td><td><br><b><u>Datos Madre</u></b></td></tr>
<tr>
<td><font size='2'>Apellido Paterno</font></td><td><input type='text' STYLE='background-color: #C0C0C0;' readonly name='apellido_paterno_madre' id='apellido_paterno_madre' value='<?=$row['apellido_paterno_madre'];?>' size='30' maxlength=30  tabindex='330'></td>
<td><font size='2'>e-Mail</font></td><td><input type='text' <?=$bloqueado;?> name='e_mail_madre' id='e_mail_madre' value='<?=$row['e_mail_madre'];?>' size='50' maxlength=70  tabindex='440'></td>
</tr>
<tr>
<td><font size='2'>Apellido Materno</font></td><td><input type='text' name='apellido_materno_madre' id='apellido_materno_madre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['apellido_materno_madre'];?>' size='30' maxlength=30  tabindex='340'></td>
<td><font size='2'>Otras Actividades</font></td><td><input type='text' <?=$bloqueado;?> name='otras_actividades_madre' id='otras_actividades_madre' value='<?=$row['otras_actividades_madre'];?>' size='50' maxlength=70  tabindex='450' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Nombre</font></td><td><input type='text' name='nombre_madre' id='nombre_madre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['nombre_madre'];?>' size='30' maxlength=35  tabindex='350'></td>
<td><font size='2'>Membres&iacute;as</font></td><td><input type='text' <?=$bloqueado;?> name='membresias_madre' id='membresias_madre' value='<?=$row['membresias_madre'];?>' size='50' maxlength=70  tabindex='460' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Fecha Nacimiento</font></td><td><input  onkeydown="javascript:writeDate('fecha_nac_madre')" type='text' <?=$bloqueado;?> name='fecha_nac_madre' id='fecha_nac_madre' value='<?=$fechaNacimientoM?>' size='15' maxlength=15 tabindex='360' onBlur="if(formatoFechas('fecha_nac_madre',1)) fechaInsertar ('fecha_nac_madre');"> <font size="1"><em>   dd/mm/aaaa</em></font></td>
<td><font size='2'>Asociaciones</font></td><td><input type='text' <?=$bloqueado;?> name='asociaciones_madre' id='asociaciones_madre' value='<?=$row['asociaciones_madre'];?>' size='50' maxlength=70  tabindex='470' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Nacionalidad</font></td><td><select <?=$bloqueado;?> name='nacionalidad_madre' id='nacionalidad_madre' tabindex='370'>
<? $paises=mysql_query("select * from paises",$link)or die(mysql_error());
   while($row_pais=mysql_fetch_array($paises))
   { $selected='';
     if (strtoupper($row_pais['pais'])==strtoupper($row['nacionalidad_madre'])) $selected='selected';
     echo"<option $selected value=".$row_pais['pais'].">".$row_pais['nacionalidad']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size='2'>T&iacute;tulo</font></td><td><input type='text' <?=$bloqueado;?> name='titulo_madre' id='titulo_madre' value='<?=$row['titulo_madre'];?>' size='6' maxlength=6  tabindex='380' onBlur="this.value=this.value.toUpperCase();"></td>
<td><u><b><font size='2'>Ocupaci&oacute;n:</font></b></u></td>
</tr>
<tr>
<td><font size='2'>Profesi&oacute;n</font></td><td><select <?=$bloqueado;?> name='profesion_madre' id='profesion_madre' tabindex='390'>
<? $profesiones=mysql_query("select * from profesiones",$link)or die(mysql_error());
   while($row_prf=mysql_fetch_array($profesiones))
   { $selected='';
     if (strtoupper($row_prf['profesion'])==strtoupper($row['profesion_madre'])) $selected='selected';
     echo"<option $selected value=".$row_prf['profesion'].">".$row_prf['nombre']."</option>";
   } ?> </select></td>
<td><font size='2'>Empleo</font></td><td><input type='text' <?=$bloqueado;?> name='empleo_madre' id='empleo_madre' value='<?=$row['empleo_madre'];?>' size='50' maxlength=40  tabindex='480' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Especialidad</font></td><td><input type='text' <?=$bloqueado;?> name='especialidad_madre' id='especialidad_madre' value='<?=$row['especialidad_madre'];?>' size='40' maxlength=50 tabindex='400'  onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size='2'>Direcci&oacute;n</font></td><td><input type='text' <?=$bloqueado;?> name='direccion_madre' id='direccion_madre' value='<?=$row['direccion_madre'];?>' size='50' maxlength=40  tabindex='490' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Religi&oacute;n</font></td><td><select <?=$bloqueado;?> name='religion_madre' id='religion_madre' tabindex='410'>
<? $religiones=mysql_query("select * from religiones",$link)or die(mysql_error());
   while($row_rlgn=mysql_fetch_array($religiones))
   { $selected='';
     if (strtoupper($row_rlgn['religion'])==strtoupper($row['religion_madre'])) $selected='selected';
     echo"<option $selected value=".$row_rlgn['religion'].">".$row_rlgn['nombre']."</option>";
   } ?> </select></td>
<td><font size='2'>Giro Empresa</font></td><td><select <?=$bloqueado;?> name='giro_empresa_madre' id='giro_empresa_madre' tabindex='500'>
<? $gempresas=mysql_query("select * from giros_empresas",$link)or die(mysql_error());
   while($row_gem=mysql_fetch_array($gempresas))
   { $selected='';
     if (strtoupper($row_gem['giro'])==strtoupper($row['giro_empresa_madre'])) $selected='selected';
     echo"<option $selected value=".$row_gem['giro'].">".$row_gem['descripcion']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size='2'>CURP</font></td><td><input type='text' <?=$bloqueado;?> name='curp_madre' id='curp_madre' value='<?=$row['curp_madre'];?>' size='20' maxlength=18 tabindex='420' onBlur="this.value=this.value.toUpperCase();"> <font size="1"><em>   No Incluir Guiones</em></font></td>
<td><font size='2'>Puesto</font></td><td><input type='text' <?=$bloqueado;?> name='puesto_madre' id='puesto_madre' value='<?=$row['puesto_madre'];?>' size='25' maxlength=20  tabindex='510' onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size='2'>Celular</font></td><td><input type='text' <?=$bloqueado;?> name='celular_madre' id='celular_madre' value='<?=$row['celular_madre'];?>' size='20' maxlength=10  tabindex='430'> <font size="1"><em>   Solamente 10 digitos</em></font></td>
<td><font size='2'>Tel&eacute;fono Oficina</font></td><td><input type='text' <?=$bloqueado;?> name='telefono_madre' id='telefono_madre' value='<?=$row['telefono_madre'];?>' size='25' maxlength=20  tabindex='520'> <font size="1"><em>   Incluir la extensi�n (EXT.)</em></font></td>
</tr><? if($puede_guardar){ ?>
<tr><th valign='bottom' height='37' colspan=4><input type='submit' value='Enviar'></td></tr><? } ?>
</table>
<?
} //fin del if $actualizar_datos_de==2;


//tercer formulario 
if($actualizar_datos_de==3)
{ $fechaNacimiento=formatDate($row["fecha_nacimiento"]); 
  if(($fechaNacimiento=="00/00/0000")||($fechaNacimiento=="//"))
  $fechaNacimiento="";
  $fechaBoda=formatDate($row["fecha_boda"]) ;
  if(($fechaBoda=="00/00/0000")||($fechaBoda=="//"))
  $fechaBoda="";
  $fechaConyuge=formatDate($row["fecha_nacimiento_conyuge"]);
  if(($fechaConyuge=="00/00/0000")||($fechaConyuge=="//"))
  $fechaConyuge="";
?>
<?=$frm;?>
<table>
<tr> 
	<td width="100">Empleado</td><td width="350"><input type="text" name='empleado' id='empleado' value="<?=$row["empleado"];?>" size="7" STYLE="background-color: #C0C0C0;" readonly></td>
</tr>
<tr> 
	<td><font size="2">Apellido Paterno</font></td><td><input type="text" STYLE="background-color: #C0C0C0;" readonly value="<?=$row["apellido_paterno"];?>" size="30"></td>
	<td width="87"><font size="2">Colonia</font></td><td width="370"><input type="text" name='colonia' id='colonia' value="<?=$row["colonia"];?>" size="30" maxlength=30 tabindex="130" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr> 
	<td><font size="2">Apellido Materno</font></td><td><input type="text" STYLE="background-color: #C0C0C0;" readonly value="<?=$row["apellido_materno"];?>" size="30"></td>
	<td><font size="2">C.P.</font></td>
	<td><input type="text" name='cp' id='cp' value="<?=$row["cp"];?>" size="7" maxlength=5 tabindex="140" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr> 
	<td><font size="2">Nombre</font></td><td><input type="text" STYLE="background-color: #C0C0C0;" readonly value="<?=$row["nombre"];?>" size="30"></td>
	<td><font size="2">Ciudad</font></td><td><select name='ciudad' id='ciudad' tabindex="150">
<? $ciudades=mysql_query("select * from ciudades",$link)or die(mysql_error());
   while($row_cd=mysql_fetch_array($ciudades))
   { $selected='';
     if (strtoupper($row_cd['ciudad'])==strtoupper($row['ciudad'])) $selected='selected';
     echo"<option $selected value=".$row_cd['ciudad'].">".$row_cd['nombre']."</option>";
   } ?> </select></td>
</tr>
<tr> 
	<td><font size="2">Iniciales</font></td><td><input type="text" name='iniciales' id='iniciales' value="<?=$row["iniciales"];?>" size="6" maxlength=6 tabindex="50" onBlur="this.value=this.value.toUpperCase();"></td>
	<td><font size="2">Tel&eacute;fono Casa</font></td><td><input type="text" name='telefonos' id='telefonos' value="<?=$row["telefonos"];?>" size="10" maxlength=8 tabindex="160"><font size="1"><em>   Solamente 8 digitos</em></font></td>
</tr>
<tr>
	<td><font size="2">Fecha Nacimiento</font></td><td><input name='fecha_nacimiento' id='fecha_nacimiento' size="15" onkeydown='javascript:writeDate("fecha_nacimiento")' value="<?=$fechaNacimiento?>" maxlength=10 tabindex="60" onBlur="if(formatoFechas('fecha_nacimiento',0)) fechaInsertar ('fecha_nacimiento');"> <font size="1"><em>   dd/mm/aaaa</em></font></td>
	<td><font size="2">Celular</font></td><td><input type="text" name='celular' id='celular' value="<?=$row["celular"];?>" size="20" maxlength=10 tabindex="170"> <font size="1"><em>   Solamente 10 digitos</em></font></td>
</tr>
<tr>
	<td><font size="2">Sexo</font></td><td><select name='sexo' id='sexo' tabindex="70">
<?  $selected="";
    if(strtoupper($row["sexo"])=="F") $selected="selected";
	echo"<option $selected value='F'>Femenino</option>";
	$selected="";
    if(strtoupper($row["sexo"])=="M")$selected="selected";
    echo"<option $selected value='M'>Masculino</option>"; ?> </select></td>
    <td><font size="2">e-mail</font></td><td><input type="text" name='e_mail' id='e_mail' value="<?=$row["e_mail"]?>" size="40" maxlength=70 tabindex="180"></td>
</tr>
<tr>
	<td><font size="2">Estado Civil</font></td><td><select name='estado_civil' id='estado_civil' tabindex="80">
<?  $selected="";
    if (strtoupper($row["estado_civil"])=="S") $selected="selected";
	echo"<option $selected value='S'>Soltero</option>";
	$selected="";
	if (strtoupper($row["estado_civil"])=="C") $selected="selected";
	echo"<option $selected value='C'>Casado</option>";
	$selected="";
	if (strtoupper($row["estado_civil"])=="D") $selected="selected";
	echo"<option $selected value='D'>Divorciado</option>";
	$selected="";
	if (strtoupper($row["estado_civil"])=="V") $selected="selected";
	echo"<option $selected value='V'>Viudo</option>";
	$selected="";
	if (strtoupper($row["estado_civil"])=="U") $selected="selected";
	echo"<option $selected value='U'>Uni&oacute;n Libre</option>"; ?></select></td>
   <td><font size="2">Religi&oacute;n</font></td><td><select name='religion' id='religion' tabindex="190">
<? $religiones=mysql_query("select * from religiones",$link)or die(mysql_error());
   while($row_rlgn=mysql_fetch_array($religiones))
   { $selected='';
     if (strtoupper($row_rlgn['religion'])==strtoupper($row['religion'])) $selected='selected';
     echo"<option $selected value=".$row_rlgn['religion'].">".$row_rlgn['nombre']."</option>";
   } ?> </select></td>
</tr>
<tr>
	<td><font size="2">Fecha Boda</font></td><td><input type="text" name='fecha_boda' id='fecha_boda' size="15" onkeydown='javascript:writeDate("fecha_boda")' value="<?=$fechaBoda?>" maxlength=10 tabindex="90" onBlur="if(formatoFechas('fecha_boda',1)||(document.all('fecha_boda'.value)==''))) fechaInsertar ('fecha_boda');"> <font size="1"><em>   dd/mm/aaaa</em></font></td>
	<td><font size="2">Nacionalidad</font></td><td><select  name='nacionalidad' id='nacionalidad' tabindex="200">
<? $paises=mysql_query("select * from paises",$link)or die(mysql_error());
   while($row_pais=mysql_fetch_array($paises))
   { $selected='';
     if (strtoupper($row_pais['pais'])==strtoupper($row['nacionalidad'])) $selected='selected';
     echo"<option $selected value=".$row_pais['pais'].">".$row_pais['nacionalidad']."</option>";
   } ?> </select></td>
</tr>
<tr>
	<td><font size="2">Direcci&oacute;n </font></td><td><input type="text" name='direccion' id='direccion' value="<?=$row["direccion"];?>" size="50" maxlength=50 tabindex="100" onBlur="this.value=this.value.toUpperCase();"></td>
	<td><font size="2">Profesi&oacute;n</font></td><td><select name='profesion' id='profesion' tabindex="210">
<? $profesiones=mysql_query("select * from profesiones",$link)or die(mysql_error());
   while($row_prf=mysql_fetch_array($profesiones))
   { $selected='';
     if (strtoupper($row_prf['profesion'])==strtoupper($row['profesion'])) $selected='selected';
     echo"<option $selected value=".$row_prf['profesion'].">".$row_prf['nombre']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size="2">Entre Calle 1</font></td><td><input type="text" name='cruce_con_calle_1' id='cruce_con_calle_1' value="<?=$row["cruce_con_calle_1"] ;?>" size="20" maxlength=20 tabindex="110" onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size="2">REG IMSS</font></td><td><input type="text" name='imss' id='imss' value="<?=$row["imss"];?>" size="15" maxlength=12 tabindex="220" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Entre Calle 2</font></td><td><input type="text" name='cruce_con_calle_2' id='cruce_con_calle_2' value="<?=$row["cruce_con_calle_2"] ;?>" size="20" maxlength=20 tabindex="120" onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size="2">R.F.C.</font></td><td><input type="text" name='rfc' id='rfc' value="<?=$row["rfc"];?>" size="18" maxlength=18 tabindex="230" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr valign="bottom" height="52">
<td colspan="2"><font size="2"><b><u>Ficha M&eacute;dica</u></b></font></td>
<td><font size="2"><b><u>Otra Ocupaci&oacute;n</u></b></font></td>
</tr>
<tr>
<td><font size="2">Doctor</font></td><td><input type="text" name='doctor' id='doctor' value="<?=$row["doctor"];?>" size="40" maxlength=40 tabindex="240" onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size="2">Empresa</font></td><td><input type="text" name='otra_empresa' id='otra_empresa' value="<?=$row["otra_empresa"];?>" size="50" maxlength=50 tabindex="310" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">T&eacute;lefono Doctor</font></td><td><input type="text" name='telefono_doctor' id='telefono_doctor' value="<?=$row["telefono_doctor"];?>" size="20" maxlength=20  tabindex="250"></td>
<td><font size="2">Direcci&oacute;n</font></td><td><input type="text" name='direccion_empresa' id='direccion_empresa' value="<?=$row["direccion_empresa"];?>" size="40" maxlength=40  tabindex="320" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
	<td><font size="2">Tipo Sangre</font></td><td><input type="text" name='tipo_sangre' id='tipo_sangre' value="<?=$row["tipo_sangre"];?>" size="3" maxlength=3 tabindex="260"></td>
	<td><font size="2">Puesto</font></td><td><input type="text" name='puesto_empresa' id='puesto_empresa' value="<?=$row["puesto_empresa"];?>" size="25" maxlength=20 tabindex="330" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
	<td><font size="2">Cl&iacute;nica</font></td><td><input type="text" name='clinica' id='clinica' value="<?=$row["clinica"];?>" size="40" maxlength=10 tabindex="270" onBlur="this.value=this.value.toUpperCase();"></td>
	<td><font size="2">Giro Empresa</font></td><td><select name='giro_empresa' id='giro_empresa' tabindex="340">
<? $gempresas=mysql_query("select * from giros_empresas",$link)or die(mysql_error());
   while($row_gem=mysql_fetch_array($gempresas))
   { $selected='';
     if (strtoupper($row_gem['giro'])==strtoupper($row['giro_empresa'])) $selected='selected';
     echo"<option $selected value=".$row_gem['giro'].">".$row_gem['descripcion']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size="2">T&eacute;lefono Cl&iacute;nica</font></td><td><input type="text" name='telefono_clinica' id='telefono_clinica' value="<?=$row["telefono_clinica"];?>" size="20" maxlength=20  tabindex="280"></td>
<td><font size="2">Tel&eacute;fonos</font></td><td><input type="text" name='telefonos_empresa' id='telefonos_empresa' value="<?=$row["telefonos_empresa"];?>" size="25" maxlength=20  tabindex="350"> <font size="1"><em>   Incluir la extensi&oacute;n (EXT.)</em></font></td>
</tr>
<tr>
<td><font size="2">Aseguradora</font></td><td><input type="text" name='aseguradora' id='aseguradora' value="<?=$row["aseguradora"];?>" size="40" maxlength=40  tabindex="290" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
	<td>
		<font size="2">P&oacute;liza</font>
	</td>
	<td>
		<input type="text" name='poliza' id='poliza' value='<?=$row["poliza"];?>' size="15" maxlength=15  tabindex="300" onBlur="this.value=this.value.toUpperCase();">
	</td>
	<td>
		<font size="2">Comentarios</font>
	</td>
	<td>
		<textarea name='comentarios' id='comentarios' value="<?=$row["comentarios"];?>"  size="15" maxlength="60"  tabindex="50" onBlur="this.value=this.value.toUpperCase();"></textarea>
	</td>
</tr>
<tr>
	<td colspan="100%">
		<font size="2"><b>Datos C&oacute;nyuge</b></font>
	</td>
</tr>
<tr>
	<td><font size="2">Apellido Paterno</font></td><td><input type="text" name='apellido_paterno_conyuge' id='apellido_paterno_conyuge' value="<?=$row["apellido_paterno_conyuge"];?>" size="40" maxlength=30  tabindex="370" onBlur="this.value=this.value.toUpperCase();"></td>
	<td><font size="2">Nacionalidad</font></td><td><select name='nacionalidad_conyuge' id='nacionalidad_conyuge' tabindex="440">
<? $paises=mysql_query("select * from paises",$link)or die(mysql_error());
   while($row_pais=mysql_fetch_array($paises))
   { $selected='';
     if (strtoupper($row_pais['pais'])==strtoupper($row['nacionalidad_conyuge'])) $selected='selected';
     echo"<option $selected value=".$row_pais['pais'].">".$row_pais['nacionalidad']."</option>";
   } ?> </select></td>
</tr>
<tr>
	<td><font size="2">Apellido Materno</font></td><td><input type="text" name='apellido_materno_conyuge' id='apellido_materno_conyuge' value="<?=$row["apellido_materno_conyuge"];?>" size="40" maxlength=30  tabindex="380" onBlur="this.value=this.value.toUpperCase();"></td>
	<td><font size="2">Profesi&oacute;n</font></td><td><select name='profesion_conyuge' id='profesion_conyuge' tabindex="450">
<? $profesiones=mysql_query("select * from profesiones",$link)or die(mysql_error());
   while($row_prf=mysql_fetch_array($profesiones))
   { $selected='';
     if (strtoupper($row_prf['profesion'])==strtoupper($row['profesion_conyuge'])) $selected='selected';
     echo"<option $selected value=".$row_prf['profesion'].">".$row_prf['nombre']."</option>";
   } ?> </select></td>
</tr>
<tr>
<td><font size="2">Nombre</font></td><td><input type="text" name='nombre_conyuge' id='nombre_conyuge' value="<?=$row["nombre_conyuge"];?>" size="40" maxlength=25  tabindex="390" onBlur="this.value=this.value.toUpperCase();"></td>
<td><font size="2">Empresa</font></td><td><input type="text" name='trabajo_conyuge' id='trabajo_conyuge' value="<?=$row["trabajo_conyuge"];?>" size="40" maxlength=50 tabindex="460" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Fecha Nacimiento</font></td><td><input size="15" tabindex="400" name='fecha_nacimiento_conyuge' id='fecha_nacimiento_conyuge' onkeydown='javascript:writeDate("fecha_nacimiento_conyuge")' value="<?=$fechaConyuge?>" maxlength=10 onBlur="if(formatoFechas('fecha_nacimiento_conyuge',1)) fechaInsertar('fecha_nacimiento_conyuge');"> <font size="1"><em>   dd/mm/aaaa</em></font> </td>
<td><font size="2">Direcci&oacute;n</font></td><td><input type="text" name='direccion_empresa_conyuge' id='direccion_empresa_conyuge' value="<?=$row["direccion_empresa_conyuge"];?>" size="40" maxlength=40  tabindex="470" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
<td><font size="2">Celular</font></td><td><input type="text" name='celular_conyuge' id='celular_conyuge' value="<?=$row["celular_conyuge"];?>" size="20" maxlength=10  tabindex="410"> <font size="1"><em>   Solamente 10 digitos</em></font></td>
<td><font size="2">Puesto</font></td><td><input type="text" name='puesto_conyuge' id='puesto_conyuge' value="<?=$row["puesto_conyuge"];?>" size="25" maxlength=20  tabindex="480" onBlur="this.value=this.value.toUpperCase();"></td>
</tr>
<tr>
	<td><font size="2">e-Mail</font></td><td><input type="text" name='email_conyuge' id='email_conyuge' value="<?=$row["email_conyuge"];?>" size="40" maxlength=70  tabindex="420"></td>
	<td><font size="2">Giro Empresa</font></td><td><select name='giro_empresa_conyuge' id='giro_empresa_conyuge' tabindex="490">
<? $gempresas=mysql_query("select * from giros_empresas",$link)or die(mysql_error());
   while($row_gem=mysql_fetch_array($gempresas))
   { $selected='';
     if (strtoupper($row_gem['giro'])==strtoupper($row['giro_empresa_conyuge'])) $selected='selected';
     echo"<option $selected value=".$row_gem['giro'].">".$row_gem['descripcion']."</option>";
   } ?> </select></td>
</tr>
<tr>
	<td><font size="2">Religi&oacute;n</font></td><td><select name='religion_conyuge' id='religion_conyuge' tabindex="430">
<? $religiones=mysql_query("select * from religiones",$link)or die(mysql_error());
   while($row_rlgn=mysql_fetch_array($religiones))
   { $selected='';
     if (strtoupper($row_rlgn['religion'])==strtoupper($row['religion_conyuge'])) $selected='selected';
     echo"<option $selected value=".$row_rlgn['religion'].">".$row_rlgn['nombre']."</option>";
   } ?> </select></td>
	<td><font size="2">Tel&eacute;fono</font></td><td><input type="text" name='telefonos_empresa_conyuge' id='telefonos_empresa_conyuge' value="<?=$row["telefonos_empresa_conyuge"];?>" size="25" maxlength=20  tabindex="500"> <font size="1"><em>   Incluir la extensi&oacute;n (EXT.)</em></font></td>
</tr>
<tr><th colspan=4><input type='submit' value="Guardar" tabindex="510"></th></tr>
</table> <?
} //fin del if $actualizar_datos_de==3;


//cuarto formulario 
if($actualizar_datos_de==4)
{ $resul=mysql_query("select * from alumnos where alumno='$clave'",$link)or die(mysql_error());
  $ro=mysql_fetch_array($resul);
  $fechaNacimiento=formatDate($ro["fecha_nacimiento"]); 
  if(($fechaNacimiento=="00/00/0000")||($fechaNacimiento=="//"))
  $fechaNacimiento="";
  $fechaBoda=formatDate($row["fecha_boda"]) ;
  if(($fechaBoda=="00/00/0000")||($fechaBoda=="//"))
  $fechaBoda="";
  $fechaConyuge=formatDate($row["fecha_nacimiento_conyuge"]);
  if(($fechaConyuge=="00/00/0000")||($fechaConyuge=="//"))
  $fechaConyuge="";
?>
<?=$frm;?>
<table cellpading=2 cellspacing=2 >
  <TR>
	<TD align=right width="333">Nombre: </TD>
	<TD width="333"><input STYLE="background-color: #C0C0C0;" readonly value="<?=$ro["nombre"];?>" size="25"></td>
  </TR>
  <TR>
    <TD align=right>Apellido Paterno: </TD>
	<TD><input STYLE="background-color: #C0C0C0;" readonly value="<?=$ro["apellido_paterno"];?>" size="30"></TD>
  </TR>
  <TR>
    <TD align=right>Apellido Materno: </TD>
	<TD><input STYLE="background-color: #C0C0C0;" readonly value="<?=$ro["apellido_materno"];?>" size="30"></TD>      
  </TR>
  <TR>
  	<TD><BR></TD>
  </TR>
  <TR>
  	<TD align=right>Fecha de Nacimiento: </TD>
	<TD><input STYLE="background-color: #C0C0C0;" readonly value="<?=$fechaNacimiento?>" size="10" ></TD>
  </TR>
  <TR>
  	<TD align=right>Generaci&oacute;n: </TD>
  	<td><input STYLE="background-color: #C0C0C0;" readonly value="<?=$ro["generacion"];?>" size="10"></td>
  </TR>
  <TR>
  	<TD><BR></TD>
  </TR>
  <TR>
  	<TD align=right>Direcci&oacute;n: </TD>
	<TD><input type="text" name="direccion" id="direccion" value="<?=$row["direccion"];?>" size="35" maxlength=35 tabindex="10" onBlur="this.value=this.value.toUpperCase();"></TD>
  </TR>
  <TR>
  	<TD align=right>Colonia: </TD>
	<td><input type="text" name="colonia" id="colonia" value="<?=$row["colonia"];?>" size="40" maxlength=40 tabindex="20" onBlur="this.value=this.value.toUpperCase();"></td>
  </TR>
  <TR>
  	<TD align=right>C&oacute;digo Postal: </TD>
  	<td><input type="text" name="cp" id="cp" value="<?=$row["cp"];?>" size="6" maxlength=5 tabindex="30"></td>
  </TR>
  <TR>
  	<TD align=right>Entre la Calle: </TD>
  	<td><input type="text" name="cruce_con_calle_1" id="cruce_con_calle_1" value="<?=$row["cruce_con_calle_1"];?>" size="20" maxlength=20 tabindex="40" onBlur="this.value=this.value.toUpperCase();"></td>
  </TR>
  <TR>
  	<TD align=right>Y la Calle: </TD>
  	<td><input type="text" name="cruce_con_calle_2" name="cruce_con_calle_2" value="<?=$row['cruce_con_calle_2'];?>" size="20" maxlength=20 tabindex="50" onBlur="this.value=this.value.toUpperCase();"></td>
  </TR>
  <TR>
  	<TD align=right>Ciudad: </TD>
  	<td><input type="text" name="ciudad" id="ciudad" value="<?=$row["ciudad"] ;?>" size="30" tabindex="60" onBlur="this.value=this.value.toUpperCase();"></td>
  </TR>
  <TR>
  	<TD align=right>Estado/Provincia: </TD>
  	<td><input type="text" name="estado" id="estado" value="<?=$row["estado"] ;?>" size="30" tabindex="70" onBlur="this.value=this.value.toUpperCase();"></td>
  </TR>
  <TR>
  	<TD align=right>Pais: </TD>
  	<td><input type="text" name="pais" id="pais" value="<?=$row["pais"] ;?>" size="30" tabindex="80" onBlur="this.value=this.value.toUpperCase();"></td>
  </TR>
  <TR>
  	<TD><BR></TD>
  </TR>
  <TR>
  	<TD align=right>Tel&eacute;fono Particular: </TD>
  	<td><input type="text" name="telefono" id="telefono" value="<?=$row["telefono"];?>" size="20" maxlength=20 tabindex="90"  ></td>	
  </TR>
  <TR>
  	<TD align=right>Otro Tel&eacute;fono: </TD>
  	<td><input type="text" name="telefono_2" id="telefono_2" value="<?=$row["telefono_2"];?>" size="20" maxlength=20 tabindex="100"  ></td>
  </TR>
  <TR>
  	<TD align=right>Tel&eacute;fono Celular: </TD>
  	<td><input type="text" name="celular" id="celular" value="<?=$row["celular"];?>" size="20" maxlength=20 tabindex="110"  ></td>
  </TR>
  <TR>
  	<TD align=right>Correo Electr&oacute;nico: </TD>
  	<td><input type="text" name="email" name="email" value="<?=$row['email']?>" size="40" tabindex="120"></td>
  </TR>
  <TR>
  	<TD><BR></TD>
  </TR>
  <TR>
  	<TD align=center colspan="2"><h4><u>* CONTESTA DEPENDIENDO DE TU CASO</u></h4></TD>
  </TR>
  <TR>
  	<TD align=center colspan="2"><B>Si eres Estudiante</B></TD>
  </TR>
  <TR>
  	<TD align=right>Universidad: </TD>
  	<td><input type="text" name="lugar_estudia" id="lugar_estudia" value="<?=$row["lugar_estudia"];?>" size="40"  tabindex="130" ></td>
  </TR>
  <TR>
  	<TD align=right>Carrera: </TD>
  	<td><input type="text" name="carrera" name="carrera" value="<?=$row["carrera"];?>" size="30" tabindex="140"></td>
  </TR>
  <TR>
  	<TD><BR></TD>
  </TR>
  <TR>
  	<TD align=center colspan="2"><B>Si eres Profesionista</B></TD>
  </TR>
  <TR>
  	<TD align=right>Empresa: </TD>
  	<td><input type="text" name="empresa_trabaja" id="empresa_trabaja" value="<?=$row["empresa_trabaja"];?>" size="40" tabindex="150"></td>
  </TR>
  <TR>
  	<TD align=right>Antig&uuml;edad: </TD>
  	<td><input type="text" name="antiguedad" id="antiguedad" value="<?=$row['antiguedad'];?>" size="7" maxlength=2 tabindex="160" ></td>
  </TR>
  <TR>
  	<TD align=right>Puesto: </TD>
  	<td><input type="text" name="puesto_empresa" id="puesto_empresa" value="<?=$row["puesto_empresa"];?>" size="20" tabindex="170"></td>
  </TR>
  <TR>
  	<TD align=right>Direcci&oacute;n: </TD>
  	<td><input type="text" name="direccion_empresa" id="direccion_empresa" value="<?=$row["direccion_empresa"];?>" size="40"  tabindex="180" ></td>
  </TR>
  <TR>
  	<TD align=right>Tel&eacute;fono: </TD>
  	<td><input type="text" name="telefono_empresa" id="telefono_empresa" value="<?=$row["telefono_empresa"];?>" size="20" maxlength=20  tabindex="190"></td>
  </TR>
  <TR>
  	<TD><BR></TD>
  </TR>
  <TR>
  	<TD align=center colspan="2"><B>Datos del Conyuge (si te has casado)</B></TD>
  </TR>
  <TR>
  	<TD align=right>Nombre: </TD>
  	<td><input type="text" name="nombre_conyuge" id="nombre_conyuge" value="<?=$row["nombre_conyuge"];?>" size="25" tabindex="200"></td>
  </TR>
  <TR>
  	<TD align=right>Apellido Paterno: </TD>
  	<td><input type="text" name="apellido_paterno_conyuge" id="apellido_paterno_conyuge" value="<?=$row["apellido_paterno_conyuge"];?>" size="30" tabindex="210"></td>
  </TR>
  <TR>
  	<TD align=right>Apellido Materno: </TD>
  	<td><input type="text" name="apellido_materno_conyuge" id="apellido_materno_conyuge" value="<?=$row["apellido_materno_conyuge"];?>" size="30" tabindex="220"></td>
  <TR>
  	<TD align=right>Fecha Nacimiento: </TD>
  	<td><input type="text" name="fecha_nacimiento_conyuge" id="fecha_nacimiento_conyuge" size="10" maxlength=10  onkeydown='javascript:writeDate("fecha_nacimiento_conyuge")' value="<?=$fechaConyuge?>" tabindex="230" onBlur="if(formatoFechas('fecha_nacimiento_conyuge',1)) fechaInsertar ('fecha_nacimiento_conyuge');"></td>
  </TR>
  </TR>
  <TR>
  	<TD align=right>Fecha de Aniverario de Bodas<br> (d&iacute;a/mes/a&ntilde;o): </TD>
	<td><input type="text" name="fecha_boda" size="10" id="fecha_boda" onkeydown='javascript:writeDate("fecha_boda")' value="<?=$fechaBoda?>" onBlur="if((formatoFechas('fecha_boda',1))||(document.all('fecha_boda'.value)==''))) fechaInsertar ('fecha_boda');" maxlength=10 tabindex="240"></td>
  </TR>
  <TR>
  	<TD align=right>N&uacute;mero de Hijos: </TD>
	<td><input type="text" name="hijos" id="hijos" value="<?=$row["hijos"];?>" size="7" maxlength=2  tabindex="250" ></td>
  </TR>
<tr><th colspan=2><br><BR><BR><input type='submit' value="Guardar" tabindex="510"></td></tr>
</table><?
} //fin del if $actualizar_datos_de==4;


//quinto formulario 
if($actualizar_datos_de==5)
{ ?>
<SCRIPT LANGUAGE="javascript"><!--
function otroHijo()
{ var valor=((document.all("hijos").value)*1)+1;
  document.all("hijos").value=valor;
  var v=1;
  var n;
  for(m=1;m<=15;m++)
  { if(document.getElementById("R"+m).style.display!="none") v++;
    if(v==valor)
	{ n=m;
	  if(valor!=1) n=m+1;
  //    buttonToHide="BR"+m;
  //    if (document.all(buttonToHide)) document.all(buttonToHide).style.display="none";
	  break;
	}
  }
  document.getElementById("R"+n).style.display="inline";
  document.getElementById("R"+n).style.display="table-row";
}
function esconde(i)
{ document.getElementById("hijos").value=((document.getElementById("hijos").value)*1)-1;
  for(m=2;m<7;m++) document.getElementById(i+"_"+m).value="";
  document.getElementById("R"+i).style.display="none";
//  buttonToShow="BR"+(valor-2);
//  if (document.all(buttonToShow)) document.all(buttonToShow).style.display="inline";
}
function checaValores()
{ var valor=document.all("hijos").value;
  var fl=true;
  if(valor==0) fl=false;
  else
  {	for (i=1;i<16;i++) 
	 if(document.getElementById("R"+i).style.display!="none")
	  for(m=2;m<6;m++) if(document.getElementById(i+"_"+m).value=="") fl=false;
    if(fl)
    { for (i=1;i<16;i++) 
       if(document.getElementById("R"+i).style.display!="none") dateformat(document.getElementById(i+"_5"));
	   document.getElementById('formu').submit();
    }
  else alert("Nombre, Apellido Paterno, Apellido Materno y Fecha de nacimiento son obligatorios");
  }	
}
function elimina(i)
{ document.all("formu").action="datos.php?quien="+i;
  document.all("formu").submit();
}
--></SCRIPT>
<?=$frm;?>
<input type="button" value="Agregar Hijo" name="OtroHijo" onClick="javascript:otroHijo()" title="Otro Hijo" />
<table align="MIDLE" cellpadding="3" cellspacing="3">
<tr>
 <th style="display:none">ID</th>
 <th>Nombre</th>
 <th>Apellido Paterno</th>
 <th>Apellido Materno</th>
 <th>Fecha Nacimiento</th>
 <th>Instituci&oacute;n</th>
 <th>Otra Instituci&oacute;n<br>(Si no existe en la lista)</th>
 <th>Profesi&oacute;n</th>
 <th>Sexo</th>
</tr>
<?
$i=0;
while(($row=mysql_fetch_array($result)) && ($vacio))
{ $i++; ?>
<tr name="<?="R$i"?>" id="<?="R$i"?>">
 <td style="display:none"><input  value=<?=$i?> name='<?=$i;?>_1' id='<?=$i;?>_1' type="text"  /></td>
 <td><input name='<?=$i;?>_2' id='<?=$i;?>_2' type="text" value='<?=$row["nombre"]?>' /></td>
 <td><input name='<?=$i;?>_3' id='<?=$i;?>_3' type="text" value='<?=$row["apellido_paterno"]?>' /></td>
 <td><input name='<?=$i;?>_4' id='<?=$i;?>_4' type="text" value='<?=$row["apellido_materno"]?>'/></td>
 <td><input name='<?=$i;?>_5' id='<?=$i;?>_5' onblur='formatoFechas("<?=$i;?>_5")' onkeydown='writeDate("<?=$i;?>_5")' type="text" value="<?=formatDate($row["fecha_nacimiento"]);?>" size="10" maxlength=10 /></td>
 <td><select style="font-size:8pt;" name='<?=$i;?>_9' id='<?=$i;?>_9'> <? 
  $resultInstitucion=mysql_query("select * from institucion_educativa order by nombre",$link)or die(mysql_error());
  while($row6 = mysql_fetch_array($resultInstitucion))
  { $selected="";
    if($row6["institucion"]==$row["institucion"]) $selected="selected";
    echo"<option $selected value=".$row6["institucion"].">".$row6["nombre"]."</option>";
  } ?> </select></td>
 <td><input name='<?=$i;?>_6' id='<?=$i;?>_6' type="text" size="30" value='<?=$row["otra_institucion"]?>'/></td>
 <td><select style="font-size:8pt;" name='<?=$i;?>_7' id='<?=$i;?>_7'> <?
  $resultProfesion=mysql_query("select * from profesiones order by nombre",$link)or die(mysql_error());
  while($row6 = mysql_fetch_array($resultProfesion))
  { $selected="";
    if($row6["profesion"]==$row["profesion"]) $selected="selected";
    echo"<option $selected value=".$row6["profesion"].">".$row6["nombre"]."</option>";
  } ?> </select></td>
 <td><select name='<?=$i;?>_8' id='<?=$i;?>_8'> <?
   $selected=""; 
   if (strtoupper($row["sexo"])=="M") $selected="selected";
   echo"<option $selected value='M'>Masculino</option>";
   $selected="";
   if (strtoupper($row["sexo"])=="F") $selected="selected";
   echo"<option $selected value='F'>Femenino</option>"; ?> </select></td>
 <td><input name="<?="BR$i"?>" id="<?="BR$i"?>" type="button" onClick="esconde(<?=$i?>)" value="Eliminar"></td>
</tr><? 
} ?>
    <input type="hidden" value=<?=$i?> name="hijos" id="hijos" />
<? $i++;
for (;$i<=15;$i++) 
{ ?>
<tr name="<?="R$i"?>" id="<?="R$i"?>" style="display:none">
 <td style="display:none"><input  value=<?=$i?> name='<?=$i;?>_1' id='<?=$i;?>_1' type="text"  /></td>
 <td><input name='<?=$i;?>_2' id='<?=$i;?>_2' type="text"></td>
 <td><input name='<?=$i;?>_3' id='<?=$i;?>_3' type="text"></td>
 <td><input name='<?=$i;?>_4' id='<?=$i;?>_4' type="text"></td>
 <td><input name='<?=$i;?>_5' id='<?=$i;?>_5' onblur='formatoFechas("<?=$i;?>_5")' onkeydown='writeDate("<?=$i;?>_5")' type="text" size="10" maxlength=10 /></td>
 <td><select style="font-size:8pt;" name='<?=$i;?>_9' id='<?=$i;?>_9'> <? 
  $Ins=mysql_query("select * from institucion_educativa order by nombre",$link)or die(mysql_error());
  while($row6 = mysql_fetch_array($Ins)) echo"<option value=".$row6["institucion"].">".$row6["nombre"]."</option>";
  ?> </select></td>
 <td><input type="text" size="30" name='<?=$i;?>_6' id='<?=$i;?>_6'></td>
 <td><select style="font-size:8pt;"  name='<?=$i;?>_7' id='<?=$i;?>_7'> <?
  $Prof=mysql_query("select * from profesiones order by nombre",$link)or die(mysql_error());
  while($row6=mysql_fetch_array($Prof)) echo"<option value=".$row6["profesion"].">".$row6["nombre"]."</option>";
  ?> </select></td>
 <td><select name='<?=$i;?>_8' id='<?=$i;?>_8'>
   <option value='M'>Masculino</option>
   <option value='F'>Femenino</option></select></td>
 <td><input name="<?="BR$i"?>" id="<?="BR$i"?>" type="button" onClick="esconde(<?=$i?>)" value="Eliminar"></td>
</tr> <?
} ?>
<tr><th colspan=8>
<input onClick="checaValores();" type="button"  value="guardar"></th></tr>
</table> <?
} //fin del if $actualizar_datos_de==5; ?>
<input type="hidden" name="dequien" id="dequien" value="<?=$quien;?>">
<input type="hidden" name="guarda" id="guarda" value="S">
<input type="hidden" name="alumnoN" id="alumnoN" value="<?=$alumnoN;?>"
</form>
</body>
</html>