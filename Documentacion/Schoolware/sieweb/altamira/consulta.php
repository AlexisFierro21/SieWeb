<? session_start();
include("config.php");
include("functions.php");
$clave=$_SESSION["clave"];
$tipo=$_SESSION['tipo'];

$puede_guardar=true;
$actualizar_datos_de=0;

$quien="";
$slct="";
$submit="";
$alumnoN="";
if(!empty($_GET["dequien"])) $quien=$_GET["dequien"];
else $quien=$tipo;
if(!empty($_POST["dequien"])) $quien=$_POST["dequien"];
switch($quien)
{ 
  case "familia":  $actualizar_datos_de=2; $familia=$clave;
    if($tipo=="personal")
	{ $puede_guardar=false;
	  $alumnoN=$_GET["alumnoN"];
	  $familia=mysql_result(mysql_query("select familia from alumnos where alumno='$alumnoN'",$link),0,0);
	}
    $tabla="familias";
	$where=" familia='$familia'";
	//$submit="onSubmit='dateformat(fecha_boda); dateformat(fecha_nac_padre); dateformat(fecha_nac_madre); '";
	$campos=array("nombre_familia","direccion","colonia","cp","ciudad","apellido_paterno_padre","e_mail_padre","apellido_materno_padre","nombre_padre","celular_padre","telefono_padre","apellido_paterno_madre","e_mail_madre","apellido_materno_madre","nombre_madre","celular_madre","telefono_madre");
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
	/*if($actualizar_datos_de==2 and $hmlgcn==1)
	{ $link_gral=mysql_connect($server,$userName,$password)or die('No se pudo conectar db gral: ' . mysql_error());
      mysql_select_db($DB_gral,$link_gral)or die("No se pudo seleccionar DB gral");
	  mysql_query($sql,$link_gral)or die(mysql_error());
	  mysql_query("update familias set fecha_web_padres=Now(), plantel_modificacion=$sede, usuario_modificacion='".$_SESSION['userName']."' where $where",$link_gral)or die(mysql_error());
	  $r_fam_sede=mysql_query("select base from familias_x_sedes,bases_x_sedes where $where and familias_x_sedes.sede=bases_x_sedes.sede",$link_gral)or die(mysql_error());
	  while($rw_fxs = mysql_fetch_array($r_fam_sede))
	  { $link_fam=mysql_connect($server,$userName,$password)or die('No se pudo conectar db fam: ' . mysql_error());
        mysql_select_db($rw_fxs['base'],$link_fam)or die("No se pudo seleccionar DB fam");
	    mysql_query($sql,$link_fam)or die(mysql_error());
		mysql_close($link_fam);
	  } mysql_close($link_gral);
	  $link = mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
      mysql_select_db($DB)or die("No se pudo seleccionar DB");
	}
	else*/ mysql_query($sql,$link)or die(mysql_error());
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
</tr>
<tr>
<td><font size='2'>Nombre Familia</font></td><td><input type='text' name='nombre_familia' id='nombre_familia' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['nombre_familia'];?>' size='30' maxlength=30 tabindex='10'></td>
</tr>
<tr>
<td><font size='2'>Direcci&oacute;n</font></td><td><input type='text' <?=$bloqueado;?> name='direccion' id='direccion' value='<?=$row['direccion'];?>' size='40' maxlength=45 tabindex='20'> </td>
</tr>

<tr>
<td><font size='2'>Colonia</font></td><td><input type='text' <?=$bloqueado;?> name='colonia' id='colonia' value='<?=$row['colonia'];?>' size='40' maxlength=40 tabindex='50'></td>
</tr>
<tr>
<td><font size='2'>C.P.</font></td><td><input type='text' <?=$bloqueado;?> name='cp' id='cp' value='<?=$row['cp'];?>' size='6' maxlength=5 tabindex='60'></td>
</tr>
<tr>
<td><font size='2'>Ciudad</font></td><td><select <?=$bloqueado;?> name='ciudad' id='ciudad' tabindex='70'>
<? $ciudades=mysql_query("select * from ciudades",$link)or die(mysql_error());
   while($row_cd=mysql_fetch_array($ciudades))
   { $selected='';
     if (strtoupper($row_cd['ciudad'])==strtoupper($row['ciudad'])) $selected='selected';
     echo"<option $selected value=".$row_cd['ciudad'].">".$row_cd['nombre']."</option>";
   } ?> </select></td>
</tr>
<!--
<tr>
<td><font size='2'>Tel&eacute;fono</font></td><td><input type='text' <?=$bloqueado;?> name='telefonos' id='telefonos' value='<?=$row['telefonos'];?>' size='20' maxlength=20 tabindex='80'></td>

</tr> -->
<tr><td><font size='2'>.</font></td><td><br><b><u>Datos Padre</u></b></td></tr>
<tr>
<td><font size='2'>Apellido Paterno</font></td><td><input type='text' name='apellido_paterno_padre' id='apellido_paterno_padre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['apellido_paterno_padre'];?>' size='30' maxlength=30 tabindex='140'></td>
<td><font size='2'>e-Mail</font></td><td><input type='text' <?=$bloqueado;?> name='e_mail_padre' id='e_mail_padre' value='<?=$row['e_mail_padre'];?>' size='50' maxlength=70  tabindex='240'></td>
</tr>
<tr>
<td><font size='2'>Apellido Materno</font></td><td><input type='text' name='apellido_materno_padre' id='apellido_materno_padre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['apellido_materno_padre'];?>' size='30' maxlength=30  tabindex='150'></td>
</tr>
<tr>
<td><font size='2'>Nombre</font></td><td><input type='text' name='nombre_padre' id='nombre_padre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['nombre_padre'];?>' size='30' maxlength=35  tabindex='150'></td>
</tr>

<tr>
<td><font size='2'>Celular</font></td><td><input type='text' <?=$bloqueado;?> name='celular_padre' id='celular_padre' value='<?=$row['celular_padre'];?>' size='20' maxlength=20 tabindex='230'></td>
<td><font size='2'>Tel&eacute;fono</font></td><td><input type='text' <?=$bloqueado;?> name='telefono_padre' id='telefono_padre' value='<?=$row['telefono_padre'];?>' size='20' maxlength=20  tabindex='320'></td>
</tr>
<tr><td><font size='2'>.</font></td><td><br><b><u>Datos Madre</u></b></td></tr>
<tr>
<td><font size='2'>Apellido Paterno</font></td><td><input type='text' STYLE='background-color: #C0C0C0;' readonly name='apellido_paterno_madre' id='apellido_paterno_madre' value='<?=$row['apellido_paterno_madre'];?>' size='30' maxlength=30  tabindex='330'></td>
<td><font size='2'>e-Mail</font></td><td><input type='text' <?=$bloqueado;?> name='e_mail_madre' id='e_mail_madre' value='<?=$row['e_mail_madre'];?>' size='50' maxlength=70  tabindex='440'></td>
</tr>
<tr>
<td><font size='2'>Apellido Materno</font></td><td><input type='text' name='apellido_materno_madre' id='apellido_materno_madre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['apellido_materno_madre'];?>' size='30' maxlength=30  tabindex='340'></td>
</tr>
<tr>
<td><font size='2'>Nombre</font></td><td><input type='text' name='nombre_padre' id='nombre_padre' STYLE='background-color: #C0C0C0;' readonly value='<?=$row['nombre_madre'];?>' size='30' maxlength=35  tabindex='150'></td>
</tr>
<tr>
<td><font size='2'>Celular</font></td><td><input type='text' <?=$bloqueado;?> name='celular_madre' id='celular_madre' value='<?=$row['celular_madre'];?>' size='20' maxlength=20  tabindex='430'></td>
<td><font size='2'>Tel&eacute;fono</font></td><td><input type='text' <?=$bloqueado;?> name='telefono_madre' id='telefono_madre' value='<?=$row['telefono_madre'];?>' size='20' maxlength=20  tabindex='520'></td>
</tr><? if($puede_guardar){ ?>
<tr><th valign='bottom' height='37' colspan=4><input type='submit' value='Enviar'></td></tr><? } ?>
</table><?
} //fin del if $actualizar_datos_de==2;


?>
<input type="hidden" name="dequien" id="dequien" value="<?=$quien;?>">
<input type="hidden" name="guarda" id="guarda" value="S">
<input type="hidden" name="alumnoN" id="alumnoN" value="<?=$alumnoN;?>"
</form>
</body>
</html>