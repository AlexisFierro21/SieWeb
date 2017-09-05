<? session_start();
include('../config.php');
include('../functions.php');

$clave = $_SESSION['clave'];
$user=mysql_query("select * from usuarios_encabezados where empleado=$clave",$link)or die(mysql_error());
if (mysql_affected_rows($link)<=0 ){ mysql_close($link); die("No existe en usuarios"); }
$rowU = mysql_fetch_array($user);
$nivelUsuario=$rowU["nivel_preceptoria"];
$seccionU=$rowU["seccion_preceptoria"];

$guarda='N';  if(!empty($_POST['guarda'])) $guarda=$_POST['guarda']; 
$seccion=""; if(!empty($_POST['seccion'])) $seccion=$_POST["seccion"];
$grado=0; if(!empty($_POST['grado'])) $grado=$_POST["grado"];
$grupo=""; if(!empty($_POST['grupo'])) $grupo=$_POST["grupo"];
$meta=""; if(!empty($_POST['metaa'])) $meta=$_POST['metaa'];
$met=""; if(!empty($_POST['met'])) $met=$_POST['met'];
$preceptor=0; if(!empty($_POST['preceptor'])) $preceptor=$_POST['preceptor'];
$m=""; if(!empty($_POST['meta'])) $m=$_POST['meta'];
$mes=""; if(!empty($_POST['mes'])) $mes=$_POST['mes'];
$m2=""; if(!empty($_POST['meta2'])) $m2=$_POST['meta2'];

$sec=""; if(!empty($_POST['sec'])) $sec=$_POST["sec"];
$gra=0; if(!empty($_POST['gra'])) $gra=$_POST["gra"];
$gru=""; if(!empty($_POST['gru'])) $gru=$_POST["gru"];

if($guarda=='S'){
	if($meta!=""){		
		if($m2==0 or $m2=="") $sql="update preceptoria_grupos set meta='0' where id=$meta";
		else $sql="update preceptoria_grupos set meta=$m2 where id=$meta";
		$result = mysql_query ( $sql, $link ) or die ( mysql_error () );
		echo "<script language='JavaScript'>
		document.getElementById('guarda').value='N';
	  	</script>";
		echo "<script language='JavaScript'>alert('Datos actualizados correctamente');</script>";
	}

	if($seccion!='' and $grado!=0 and $grupo!=''){//////////////////////////////////
		if($m==0 or $m=="") 
		$sql="INSERT INTO preceptoria_grupos(ciclo,seccion,grado,grupo,meta,mes) VALUES($periodo_actual,'$seccion',$grado,'$grupo','0',$mes)";
		else
		$sql="INSERT INTO preceptoria_grupos(ciclo,seccion,grado,grupo,meta,mes) VALUES($periodo_actual,'$seccion',$grado,'$grupo',$m,$mes)";
		$result=mysql_query($sql,$link) or die(mysql_error());
		echo "<script language='JavaScript'>
		document.getElementById('guarda').value='N';
	  	</script>";
		echo "<script language='JavaScript'>alert('Registro correcto');</script>";
	}

}

if($meta!=""){
	
	
	$rst_=mysql_query("SELECT distinct(grupos.grupo), secciones.seccion, grados.grado, secciones.nombre, preceptoria_grupos.meta, preceptoria_grupos.mes FROM preceptoria_grupos, secciones, grados, grupos where secciones.seccion=preceptoria_grupos.seccion and preceptoria_grupos.grado=grados.grado and preceptoria_grupos.grupo=grupos.grupo and preceptoria_grupos.id='$meta' and preceptoria_grupos.ciclo='$periodo_actual' group by secciones.nombre",$link) or die("SELECT distinct(grupos.grupo), secciones.seccion, grados.grado, secciones.nombre, preceptoria_grupos.meta FROM preceptoria_grupos, secciones, grados, grupos where secciones.seccion=preceptoria_grupos.seccion and preceptoria_grupos.grado=grados.grado and preceptoria_grupos.grupo=grupos.grupo and preceptoria_grupos.id='$meta' and preceptoria_grupos.ciclo='$periodo_actual' group by secciones.nombre; ".mysql_error());
 		 while($rs_=mysql_fetch_array($rst_)){
		 	$sec=$rs_['nombre'];
			$gra=$rs_['grado'];
			$gru=$rs_['grupo'];
			$met=$rs_['meta'];
			$mess=$rs_['mes'];
 		 }
		 echo "<script language='JavaScript'>
		document.getElementById('parametros').submit();
	  </script>";
	}
	
	$mes_meta='<font size="3">Mes:<font size="3">&nbsp;&nbsp;<select name="mes">';
	$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","Enero","Febrero");
	for($i=1;$i<13;$i++){
		if($i<6)
			$mes_meta.="<option value=".$i.">".($meses[$i+7])."</option>";
		else
			$mes_meta.="<option value=".$i.">".($meses[$i-5])."</option>";
	}
	$mes_meta.="</select>";
	
//Sección
    $secciones="<font size='3'>Secci&oacute;n:</font><select  OnChange='parametros.submit();' style='font-size:8pt' name='seccion' id='seccion'>";
	$rs_=mysql_query("SELECT seccion, nombre FROM secciones where ciclo='$periodo_actual' order by seccion",$link) or die (mysql_error());
    if(mysql_affected_rows($link)>0){
		while($row=mysql_fetch_array($rs_)){
			$selected="";
			if($seccion==$row["seccion"])
				$selected="selected";
			$secciones.="<option $selected value='".$row["seccion"]."'>".$row["nombre"]."</option>";
		}
	}
	$secciones.="</select><input type='hidden' name='seccion_' id='seccion_' value='$seccion'> ";
	
//Grado
	$grados="<font size='3'>Grado: </font><select OnChange='parametros.submit();' style='font-size:8pt;' name='grado' id='grado'><option value='0'>-</option>";
	$rs_=mysql_query("SELECT grado, nombre FROM grados where seccion='$seccion' and ciclo='$periodo_actual' order by grado",$link) or die (mysql_error());
    if(mysql_affected_rows($link)>0){
		while($row=mysql_fetch_array($rs_)){
			$selected="";
			if($grado==$row["grado"])
				$selected="selected";
			$grados.="<option $selected value='".$row["grado"]."'>".$row["grado"]."</option>";
		}
	}
	$grados.="</select><input type='hidden' name='grado_' id='grado_' value='$grado'> ";

//Grupo
	$grupos="<font size='3'>Grupo: </font><select style='font-size:8pt;' name='grupo' id='grupo'><option value=''>-</option>";
	$rs_=mysql_query("SELECT grupo, nombre FROM grupos where seccion='$seccion' and grado=$grado and ciclo='$periodo_actual' order by grupo",$link) or die (mysql_error());
    if(mysql_affected_rows($link)>0){
		while($row=mysql_fetch_array($rs_)){
			$selected="";
			if($grupo==$row["grupo"])
				$selected="selected";
			$grupos.="<option $selected value='".$row["grupo"]."'>".$row["grupo"]."</option>";
		}
	}
	$grupos.="</select><input type='hidden' name='grupo_' id='grupo_' value='$grupo'> ";
	
	$mes_meta_readonly="<font size='3'>Mes: </font><input type='text' name='mes' id='mes' value='".$meses[fMesRel($mess)+2]."' size='15' style='background-color: #C0C0C0;' readonly><input type='hidden' name='mes' value='$mess'>";
	
	//Secci�n
    $seccioness="<font size='3'>Secci&oacute;n: </font><input type='text' name='sec' id='sec' value='".$sec."' size='15' style='background-color: #C0C0C0;' readonly>";
	
	//Grado
	$gradoss="<font size='3'>Grado: </font><input type='text' name='gra' id='gra' value='".$gra."' size='5' style='background-color: #C0C0C0;' readonly>";

	//Grupo
	$gruposs="<font size='3'>Grupo: </font><input type='text' name='gru' id='gru' value='".$gru."' size='5' style='background-color: #C0C0C0;' readonly> ";

	$meta1="<input name='meta' type='text' id='meta' size='3' maxlength='5' />&nbsp;<font size='3'>preceptorias.</font></td>";
	$meta2="<input name='meta2' type='text' id='meta2' size='3' maxlength='5' value='".$met."'/>&nbsp;<font size='3'>preceptorias.</font></td>";
	/////


//Meta	
	 $metas="<b><font size='3'>META POR GRUPO:</b></font><select OnChange='parametros.submit();' style='font-size:8pt' name='metaa' id='metaa'><option value=''>Nueva</option>";
 $rs_=mysql_query("SELECT 
					 	distinct(preceptoria_grupos.id) as id, 
						concat(secciones.nombre, ' ', grado, ' ', grupo, ' ',' - ',' ', 
						date_format(concat('0000-',mes_relativo.mes_relativo ,'-00'), '%b'),' ', preceptoria_grupos.ciclo) as grupo,
						mes_relativo,
						mes_relativo.mes 
					FROM preceptoria_grupos, secciones, mes_relativo
					WHERE 
						secciones.seccion=preceptoria_grupos.seccion 
						and secciones.ciclo='$periodo_actual' 
						and preceptoria_grupos.ciclo='$periodo_actual' 
						and mes_relativo.mes = preceptoria_grupos.mes
					ORDER BY 
						preceptoria_grupos.seccion, preceptoria_grupos.grado, preceptoria_grupos.grupo, mes_relativo.mes",$link) or die (mysql_error());
    if(mysql_affected_rows($link)>0){
		while($row=mysql_fetch_array($rs_)){
			$selected="";
			if($meta==$row["id"])
				$selected="selected";
			$metas.="<option $selected value='".$row["id"]."'>".$row["grupo"]."</option>";
		}
	}
	$metas.="<br> ";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
.Estilo1 {color: #fff}
-->
</style>
</head>

<body>
<form name="parametros" id="parametros" method="post" action="metas.php">

<table width="507" height="289" border="0" align="center">
<tr>
<td bgcolor="<?=$baner;?>">
<br><font size='3'>
<center>
  <b><span class="Estilo1">PRECEPTORIAS POR GRUPO</span>    </b>
</center>
<BR><BR>
</td>
</tr>
  <tr>
    <td width="501" height="37" valign="middle">
	<? echo $metas;?>   <br /> </td>
    </tr>  
  <tr>
        <td height="33" valign='top' bgcolor="<?=$tr_par;?>"><font size="3">Ciclo:</font>&nbsp;&nbsp;
      <input type="text" name="ciclo" size="6" style="background-color: #C0C0C0;" readonly value="<?=$periodo_actual; ?>"/></td>
    </tr>
	<tr>
    
    <td height="33" valign='37' bgcolor="<?=$tr_par;?>">
	<? 
		if($meta==0 and $meta=="")
			echo $secciones;
		else
			echo $seccioness;
		?>
    <? 
		if($meta==0 and $meta=="")
			echo $grados;
		else
			echo $gradoss;
	?>
    <? 
		if($meta==0 and $meta=="")
			echo $grupos;
		else
			echo $gruposs;
	 ?></td></tr>
  <tr>
    <td height="25" valign="top" bgcolor="<?=$tr_par;?>"><font size='3'>Meta :</font>&nbsp;&nbsp;
      <?
		if($meta==0 and $meta=="")
			echo $meta1;
		else
			echo $meta2;
	  ?></td>
    </tr>
  <tr>
    <td height="37" valign='top' bgcolor="<?=$tr_par;?>"><font size='3'><?
		if($meta==0 and $meta=="")
			echo $mes_meta;
		else
			echo $mes_meta_readonly;
	?></td>
    </tr>
  <tr>
    <td bgcolor="<?=$tr_par;?>">
	<input name="guarda" type="hidden" id="guarda" value="N">
      <input type="submit" name="Submit" value="Enviar" onClick="javascript: guarda.value='S';" />   </td>
    </tr>
</table>
</form>
<script language="javascript" type="text/javascript">
function guarda(x)
{	alert("Entra");
	var guardar='S';
	document.parametros.guarda.value='S';
	document.parametros.submit(); 	
}
</script>
</body>
</html>