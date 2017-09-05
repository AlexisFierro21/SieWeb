<? 
include('config.php');
$periodo_ini=$periodo_actual; if (!empty($_POST['periodo_ini'])) $periodo_ini=$_POST['periodo_ini'];
$periodo_fin=$periodo_actual; if (!empty($_POST['periodo_fin'])) $periodo_fin=$_POST['periodo_fin'];
$mes_ini=1;  if (!empty($_POST['mes_ini'])) $mes_ini=$_POST['mes_ini'];
$mes_fin=12; if (!empty($_POST['mes_fin'])) $mes_fin=$_POST['mes_fin'];
$x=1; if(!empty($_POST['message'])){ $familias = explode( ";", $_POST['familias'] ); $x=count($familias);}
$x--;
if($x>0)
{ if($_POST['save']=='S') $result=mysql_query("UPDATE parametros set mensaje_becas_canceladas='".$_POST['message']."'",$link)or die(mysql_error());
  require("phpMailer/class.phpmailer.php");
  $mail = new PHPMailer();
  $mail->PluginDir = "phpMailer/";
  $mail->Mailer = "smtp";
  $mail->IsMail();
  $mail->Host = "losaltos.edu.mx";
  $mail->From = ("".$_POST['mails_from']."");
  $mail->FromName = ("".$_POST['mails_from_name']."");
  $mail->Subject = $_POST['subject'];
  $mail->IsHTML(true);
  $m=$_POST['message'];
  $x=0;
  foreach($familias as $familia)
  { $mail_=$_POST["mail_$familia"]; $mail->ClearAddresses(); $mail->AddAddress($mail_);
    $msg=str_replace('nombre_familia',$_POST["n_fam_$familia"],$m);
    $msg=str_replace('detalle_cargos',$_POST["detalle_$familia"],$msg);
	//echo "$msg<br><br><br>";
    $mail->Body = $msg;
	if(!$mail->Send()) echo"Mailer Error: " . $mail->ErrorInfo;
	else $x++;
  }$x--;
  if($x>0) echo "<script language='JavaScript'> alert('Se han enviado $x mensajes correctamente'); </script> ";
}
$month_names = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<META HTTP-EQUIV="Cache-Control" CONTENT ="no-cache">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es" dir="ltr">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</HEAD>
<body>
<script language="javascript" type="text/javascript">
<!-- 
function llena_destinatarios()
{ var destinatarios="";
  var total_familias=document.getElementById('total_familias').value;
  var mail;
  var nombre;
  document.getElementById('message').value=nicEditors.findEditor('m').getContent();
  if(document.getElementById('ch_save').checked) document.getElementById('save').value='S';
  for(m=1;m<=total_familias;m++)
  { if(document.getElementById('fam_'+m).checked)
    { mail=document.getElementById('fam_'+m).value;
      if (destinatarios.indexOf(mail)==-1) destinatarios=destinatarios+";"+mail;				
	}
  }
  document.getElementById("familias").value=destinatarios;
  document.getElementById("envio_mails").submit();
}
function cambia(chk)
{ for(i=0;ele=chk.form.elements[i];i++) if(ele.type=='checkbox') ele.checked=chk.checked;  }

-->
</script>
<?
  $p_i=$periodo_ini-1;
  $p_f=$periodo_fin+1;
  $m_i=$mes_ini-1;
  $m_f=$mes_fin+1;
  $sql="select * from becas_canceladas where periodo>$p_i and periodo<$p_f and mes>$m_i and mes<$m_f order by familia, alumno";
  $result=mysql_query($sql,$link)or die(mysql_error());
  $tabla_destinatarios="";
  $t_fams=0;
  $fam_anterior="";
  $detalle=""; $e=""; $mail=""; $nombre_fam="";
  while($row=mysql_fetch_array($result))
  { if($fam_anterior!=$row['familia']) 
	{ $e.="<input type='hidden' name='mail_$fam_anterior' id='mail_$fam_anterior' value='$mail'><input type='hidden' name='n_fam_$fam_anterior' id='n_fam_$fam_anterior' value='$nombre_fam'><input type='hidden' name='detalle_$fam_anterior' id='detalle_$fam_anterior' value='$detalle'>";
	  $nombre_fam=mysql_result(mysql_query("select nombre_familia from familias where familia='".$row['familia']."'",$link),0,0);
	  $mail=mysql_result(mysql_query("SELECT e_mail_padre FROM familias WHERE familia='".$row['familia']."'",$link),0,0);  $fam_anterior=$row['familia']; $muestra='N'; $detalle="";
      if($mail=="") $mail=mysql_result(mysql_query("SELECT e_mail_madre FROM familias WHERE familia='".$row['familia']."'",$link),0,0);
	  if($mail!="")
	  { $muestra='S'; $t_fams++; $tabla_destinatarios.="
  <tr><th align='left'><input type='checkbox' id='fam_$t_fams' value='".$row['familia']."'> ".$row['familia'].".- $nombre_fam</th></tr>";
      }
	}
	if($muestra=='S')
	{ $detail=$row['alumno']." ".mysql_result(mysql_query("SELECT CONCAT_WS(' ',nombre,apellido_paterno, apellido_materno) as n FROM alumnos where alumno=".$row['alumno'],$link),0,0)."
  &nbsp; &nbsp; CONCEPTO: ".mysql_result(mysql_query("SELECT alias FROM conceptos where concepto=".$row['concepto'],$link),0,0)."
  &nbsp; &nbsp; ".$month_names[$row['mes']]."/".$row['periodo']."
  &nbsp; &nbsp; ANTERIOR: $ ".$row['importe_beca']."
  &nbsp; &nbsp; ACTUAL: $ ".$row['importe_actual'];
	  $tabla_destinatarios.="<tr><td>$detail</td></tr>";
	  $detalle.="$detail<br>";
    }
  }
  $e.="<input type='hidden' name='mail_$fam_anterior' id='mail_$fam_anterior' value='$mail'><input type='hidden' name='n_fam_$fam_anterior' id='n_fam_$fam_anterior' value='$nombre_fam'><input type='hidden' name='detalle_$fam_anterior' id='detalle_$fam_anterior' value='$detalle'>";
  echo"<input type='hidden' name='total_familias' id='total_familias' value='$t_fams'>";
echo"<table align='center'><tr><th colspan=2><form name='rango' id='rango' action='mensajes_beca_vencida.php' method='post'>
RANGOS DE PER&Iacute;ODO Y MES DE CONCEPTOS</th></tr><tr><th>Inicial:</th><td>
 Periodo: <input name='periodo_ini' id='periodo_ini' value='$periodo_ini' size=5 onchange='rango.submit();'>  &nbsp; &nbsp; Mes:<input name='mes_ini' id='mes_ini' value='$mes_ini' size=3 onchange='rango.submit();'></td></tr>
<tr><th valign='top'>Final:</th><td>
 Periodo: <input name='periodo_fin' id='periodo_fin' value='$periodo_fin' size=5 onchange='rango.submit();'>  &nbsp; &nbsp; Mes:<input name='mes_fin' id='mes_fin' value='$mes_fin' size=3 onchange='rango.submit();'></form></td></tr></table><br><br>
<table border='1' align='center'><tr><th colspan=100%><form><input type='checkbox' onClick='cambia(this);'> Seleccionar/Quitar todos<br>$tabla_destinatarios</form></th></tr></table><br><br><br><br>
<table align='center'><tr><td><form name='envio_mails' id='envio_mails' action='mensajes_beca_vencida.php' method='post'>
<tr><th align='left'>Env&iacute;a : </th><td><input name='mails_from_name' id='mails_from_name' size='94'></td></tr>
<tr><th align='left'>Mail respuesta : </th><td><input name='mails_from' id='mails_from' size='94'></td></tr>
<tr><th align='left'>Asunto :</th><td><input name='subject' id='subject' size='94' maxlength='100' value='Cancelaci&oacute;n Beca '></td></tr>
<tr><th align='left' valign='top' colspan='2'>Mensaje : </th></tr>
<tr><td colspan='2'><div id='sample'><script type='text/javascript' src='nicEdit.js'></script>
  <script type='text/javascript'> bkLib.onDomLoaded(function(){ new nicEditor().panelInstance('m'); }); </script>
  <textarea rows='10' cols='88' name='m' id='m'></textarea><input type='hidden' name='message' id='message' value='$mensaje_becas_canceladas'></div><br> 
  <script language='javascript'>document.getElementById('m').value=document.getElementById('message').value;</script>
  <textarea name='familias' id='familias' rows='70' cols='70' style='display: none '></textarea>
  <input type='checkbox' name='ch_save' id='ch_save'><input type='hidden' name='save' id='save' value='N'>Guardar Texto como predeterminado. $e
</td></tr>
<tr><td></td><th><input type='button' value='Enviar' onClick='llena_destinatarios();'></th></tr></form></table>";
?>
</body>G