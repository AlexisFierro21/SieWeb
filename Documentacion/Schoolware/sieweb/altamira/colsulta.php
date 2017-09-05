<?php
include('../config.php');

$medicamento= array();
$cantidad= array();
$dosis= array();
$via_de_administracion= array();
$frecuencia= array();

$tipo_de_paciente = utf8_decode($_REQUEST['tipo_de_paciente']);
$padre_madre = " ";

if($tipo_de_paciente == 1){
	$paciente = $_REQUEST['paciente'];
	$referido = $_REQUEST['referido'];
	
	}
	elseif($tipo_de_paciente == 2){
		$paciente = $_REQUEST['idempleado'];
		
	}
	elseif($tipo_de_paciente == 3){
		$paciente = $_REQUEST['idfamilia'];
		$padre_madre = utf8_decode($_REQUEST['padre_madre']);
	}
	elseif($tipo_de_paciente == 4){
		$referido_otro = utf8_decode($_REQUEST['referido_otro']);
		$tipo_referido = utf8_decode($_REQUEST['tipo_referido']);
		$paciente = utf8_decode($_REQUEST['paciente']);
	}
	

$resultado = mysql_query("SELECT periodo FROM parametros");
if (!$resultado) {
    echo 'No se pudo ejecutar la consulta: ' . mysql_error();
    exit;
}
$fila = mysql_fetch_row($resultado);





$fecha_y_hora_de_consulta = date("Y-m-d H:i:s");
$motivo_consulta = utf8_decode($_REQUEST['motivo_consulta']);
$exploracion_fisica = utf8_decode($_REQUEST['exploracion_fisica']);
$diagnostico = utf8_decode($_REQUEST['diagnostico']);
$indicaciones = utf8_decode($_REQUEST['indicaciones']);
$motivo_de_la_consulta = utf8_decode($_REQUEST['motivo_consulta']);

if(isset($_POST['medicamento'])){
foreach($_REQUEST['medicamento'] as $key=>$value)
    $medicamento[]= utf8_decode($value);
	
}

if(isset($_POST['cantidad'])){
foreach($_REQUEST['cantidad'] as $key=>$value)
    $cantidad[]= utf8_decode($value);
	
}
 
if(isset($_POST['dosis'])){ 
foreach($_REQUEST['dosis'] as $key=>$value)
    $dosis[]= utf8_decode($value);

}

if(isset($_POST['via_administracion'])){
foreach($_REQUEST['via_administracion'] as $key=>$value)
    $via_de_administracion[]= utf8_decode($value);
	
}

if(isset($_POST['frecuencia'])){
foreach($_REQUEST['frecuencia'] as $key=>$value)
    $frecuencia[]= utf8_decode($value);
	
}

$cuerpo.="
<style>

table{
	border-style: solid;
    border-width: 1px;
	border-color: #00F;
	border-radius: 5px;
	color:#00F;
	border-spacing: 0px;
}
em{
	color:#000;
}

p.receta_encabezado{
	font-weight: bold;	
}

td.linea{
	border-collapse: collapse;
	border-bottom: 1px solid #00F;;
}


</style>
<table>
	<tr>
		<td colspan=5 align='center'><img src='http://schoolware.education/sieweb/altamira/im/logo.jpg'></img></td>
	</tr>
	<tr>
		<td colspan=4 align='center'><p class='receta_encabezado'>&nbsp;Consulta M&eacute;dica&nbsp;</p></td>
		<td>&nbsp;Fecha:&nbsp;<font size=2><em>".date("d-m-Y, g:ia")."</em></font>
	</tr>";

if($tipo_de_paciente==1){
	$Tipo_Paciente_Es="Alumno";
	$sql_alumno = mysql_query("SELECT nombre, apellido_paterno, apellido_materno, grado, grupo, seccion FROM alumnos WHERE alumno='$paciente'",$link);
	$sql_nombre=  mysql_result($sql_alumno, 0);
	$sql_apellido_paterno= mysql_result($sql_alumno, 0,1);
	$sql_apellido_materno= mysql_result($sql_alumno, 0,2);
	$sql_grado=  mysql_result($sql_alumno, 0,3);
	$sql_grupo=  mysql_result($sql_alumno, 0,4);
	$sql_seccion=  mysql_result($sql_alumno, 0,5);

/////Cuerpo para alumno
$cuerpo_paciente.= "
		<td colspan=2 class='linea' style='linea'><em>&nbsp;".$sql_apellido_paterno." ".$sql_apellido_materno.", ".$sql_nombre."</em>&nbsp;</td>
		<td class='linea' style='linea'><em>&nbsp;".$sql_grado." - ".$sql_grupo." ".$sql_seccion."</em>&nbsp;</td>
		<td class='linea' style='linea'></td>
	</tr>
";
	
}
elseif($tipo_de_paciente==2){
	$Tipo_Paciente_Es="Personal";
	
/////Cuerpo para Personal
$cuerpo_paciente.= "
		<td colspan=2 class='linea' style='linea'><em>&nbsp;".$sql_apellido_paterno." ".$sql_apellido_materno.", ".$sql_nombre."</em>&nbsp;</td>
		<td class='linea' style='linea'><em>&nbsp;".$sql_grado." - ".$sql_grupo." ".$sql_seccion."</em>&nbsp;</td>
		<td class='linea' style='linea'></td>
	</tr>
";	
	
	
}
elseif($tipo_de_paciente==3){
	$Tipo_Paciente_Es="Personal";
	
/////Cuerpo para Personal
$cuerpo_paciente.= "
		<td colspan=2 class='linea' style='linea'><em>&nbsp;".$sql_apellido_paterno." ".$sql_apellido_materno.", ".$sql_nombre."</em>&nbsp;</td>
		<td class='linea' style='linea'><em>&nbsp;".$sql_grado." - ".$sql_grupo." ".$sql_seccion."</em>&nbsp;</td>
		<td class='linea' style='linea'></td>
	</tr>
";	
		
	
}

$cuerpo.= "

	<tr>
		<td class='linea' style='linea'>&nbsp;".$Tipo_Paciente_Es.":<font size=2><em> ".$paciente."&nbsp;<em></font></td> ";
		
		
		
$cuerpo.=$cuerpo_paciente;

$sql = "INSERT INTO expediente_medico 
									(fecha_y_hora_de_consulta, 
									 tipo_de_paciente, 
									 paciente, 
									 motivo_de_la_consulta, 
									 exploracion_fisica, 
									 diagnostico,
									 indicaciones,
									 padre_madre,
									 referido,
									 ciclo) 
								VALUES 
									( 
									 '$fecha_y_hora_de_consulta', 
									 '$tipo_de_paciente', 
									 '$paciente', 
									 '$motivo_de_la_consulta', 
									 '$exploracion_fisica', 
									 '$diagnostico',
									 '$indicaciones',
									 '$padre_madre',
									 '$referido',
									 '$fila[0]'
									)";
   
$result = mysql_query($sql)or die("Existe un error al procesar la informaci&oacute;n ".mysql_error());


//Last ID
$lastID= mysql_insert_id($link);
///Mostramos lo que la consulta nos indica
$cuerpo.= "
<tr>
	<td colspan=5>Motivo de la Consulta:&nbsp;<font size=2><em>
		</br>&nbsp;".$motivo_de_la_consulta."</font></em></br>&nbsp;</td>
</tr>
<tr>
	<td colspan=5>Exploraci&oacute;n F&iacute;sica:&nbsp;<font size=2><em>
	</br>&nbsp;".$exploracion_fisica."</font></em></br>&nbsp;</td>
</tr>
<tr>
	<td colspan=5>Diagnostico:&nbsp;<font size=2><em>
	</br>&nbsp;".$diagnostico."</font></em></br>&nbsp;</td>
</tr>
<tr>
	<td colspan=5 class='linea' style='linea'>Indicaciones:&nbsp;<font size=2><em>
	</br>&nbsp;".$indicaciones."</font></em></br>&nbsp;</td>
</tr>

";

if(isset($_POST['medicamento'])){
$cuerpo.= "
		<tr>
			<td colspan=5 align='center'><p class='receta_encabezado'>Medicamento(s)</p></td>
		</tr>
		";
for($i=0; $i<count($medicamento); $i++) 
{
	
	
   mysql_query("INSERT INTO medicamento_recetado
													(	id_paciente,
														medicamento,
														cantidad,
														dosis,
														via_de_administracion,
														frecuencia,
														id_expediente_medico) 
											VALUES(
												   		'{$paciente}',	
												   		'{$medicamento[$i]}', 
														'{$cantidad[$i]}', 
														'{$dosis[$i]}', 
														'{$via_de_administracion[$i]}', 
														'{$frecuencia[$i]}',
														'{$lastID}'
														)",$link)or die(mysql_error());
   
   
   
   $cuerpo.= "
   		<tr>
			<td>&nbsp;Medicamento:<font size=2><em> ".$medicamento[$i]."</em></font>&nbsp;</td>
			<td>&nbsp;Cantidad:<font size=2><em> ".$cantidad[$i]."</em></font>&nbsp;</td>
			<td>&nbsp;Dosis:<font size=2><em>".$dosis[$i]."</em></font>&nbsp;</td>
			<td>&nbsp;Via de administracion:<font size=2><em> ".$via_de_administracion[$i]."</em></font>&nbsp;</td>
			<td>&nbsp;Frecuencia:<font size=2><em> ".$frecuencia[$i]."</em></font>&nbsp;</td>
		</tr>";
		}
}


if($_REQUEST['tipo_de_paciente']==4){
	 mysql_query("INSERT INTO medico_consulta_otros
													(	id,
														nombre,
														referido,
														tipo_referido) 
											VALUES(
												   		'{$lastID}',
														'{$paciente}',
														'{$referido_otro}',
														'{$tipo_referido}'
													)",$link)or die(mysql_error());
	
}

$cuerpo.= "
</table>
<br>";



$cuerpo.="
				<a href='javascript:window.print()'>Imprimir Consulta</a>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href='javascript:window.location='/medico/expediente_medico.php'>Salir</a>
				
";

echo $cuerpo;

echo "<br>
		<img src='funciones/loading.gif' />
		<p>Enviando correo...</p>";

include("../phpMailer/class.phpmailer.php");
include('../phpMailer/class.smtp.php');

$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailPass="syst3m5_C0l";

/// Datos Sistemas
$mailUser="sistemas@colmenares.org.mx";
$mailFrom="sistemas@colmenares.org.mx";
$mailBCC="sistemas@colmenares.org.mx";
$mailBCC1="emmanuel.contreras@colmenares.org.mx";

$mailSender="Consulta M&eacute;dica";

		$mail=new PHPMailer();
		$mail->SetLanguage("en","PhpMailer/language/");
		$mail->IsSMTP();
		$mail->PluginDir="../phpMailer/";
		$mail->CharSet="UTF-8";
		$mail->Mailer="smtp";
		$mail->Host=$hostSMTP;
		$mail->SMTPAuth=true;
		$mail->SMTPSecure=$smtpPrefix;
		$mail->Port=$smtpPort;
		$mail->Username=$mailUser;
		$mail->Password=$mailPass;
		$mail->From=$mailFrom;
		$mail->FromName=$mailSender;
		$mail->IsHTML(true);

		$mail->AddBCC($mailBCC);
		$mail->AddBCC($mailBCC1);
		
		$mail->Subject="Consulta M&eacute;dica";
		$mail->AddEmbeddedImage('../im/logo.jpg', 'imagen');
		$mail->Body=$cuerpo;
		
	
		if(!$mail->Send())
			echo"Mailer Error: ".$mail->ErrorInfo;


		$mail->ClearAddresses();
				
if(!$mail->Send()) {
  echo "Error: " . $mail->ErrorInfo;
} else {
  echo "Mensaje enviado correctamente";
}


?>
<script language="JavaScript" type="text/javascript">
/*
var pagina="/medico/expediente_medico.php"
function redireccionar() 
						{
							location.href=pagina
						} 
setTimeout ("redireccionar()", 3000);
*/
</script>