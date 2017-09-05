<?
include("../config.php");
require('../../repositorio/phpMailer/PHPMailerAutoload.php');
mysql_query("SET NAMES 'utf8'");


mysql_query("TRUNCATE TABLE estado_cuenta_mail", $link) or die(mysql_error());

//////////////////////////////////
/* Variables 					*/
/////////////////////////////////
$registro = explode(";", $_REQUEST['variables']);
$variables = $_REQUEST['variables'];
$id = $_REQUEST['id'];

		foreach ($registro as $valor) {
					$valores = explode("|", $valor);

					$familia = $valores[0];
					$alumno = $valores[1];
					$concepto = $valores[2];
					$mes = $valores[3];
					$periodo = $valores[4];
					$importe = $valores[5];
					$intereses = $valores[6];
					

				if($importe <=  "0" && $intereses <= "0"){
					}else{
						
		$result__=mysql_query("select * from alumnos WHERE alumno = '$alumno' ",$link)or die(mysql_error());
							while($row_ = mysql_fetch_array($result__)){
	 																	$Nalumno = $row_['nombre'] . ' ' . $row_['apellido_paterno']." ".$row_['apellido_materno'];
																	}											
						mysql_query("INSERT INTO 
													estado_cuenta_mail
																	(familia,
																	alumno,
																	NombreAlumno,
																	Concepto,
																	Mes,
																	periodo,
																	importe,
																	intereses)
																VALUES
																	('$familia',
																	'$alumno',
																	'$Nalumno',
																	'$concepto',
																	'$mes',
																	'$periodo',
																	'$importe',
																	'$intereses');",$link)or die(mysql_error());
					
					}
			}//Del Registro que contiene el explode de "|"//     

//$registro = explode(";", $_REQUEST['variables']);

////////////Temporales
		
	$mes_ = date("m");
	
	switch ($mes_) {
    case 0:
        $mes_ = "";
        break;
    case 1:
        $mes_ = "de enero";
        break;
    case 2:
        $mes_ = "de febrero";
        break;
    case 3:
        $mes_ = "de marzo";
        break;
	case 4:
        $mes_ = "de abril";
        break;
	case 5:
        $mes_ = "de mayo";
        break;
	case 6:
        $mes_ = "de junio";
        break;
    case 7:
        $mes_ = "de julio";
        break;
	case 8:
        $mes_ = "de agosto";
        break;
	case 9:
        $mes_ = "de septiembre";
        break;
	case 10:
        $mes_ = "de octubre";
        break;
	case 11:
        $mes_ = "de noviembre";
        break;
	case 12:
        $mes_ = "de diciembre";
        break;
}
			

	$Fecha_Actual = date("d").' '.$mes_;
	
	//////////////////////
	 $día_actual = date('N');

	$fecha = date('Y-m-j');
	$fecha_emision = date('j M Y');

$agregar = "";
///Día de baja
if($día_actual == 1){
	$agregar = 4;
}elseif($día_actual == 2){
	$agregar = 6;
}elseif($día_actual == 3){
	$agregar = 6;
}elseif($día_actual == 4){
	$agregar = 6;
}elseif($día_actual == 5){
	$agregar = 6;
}


$nuevafecha = strtotime ( '+'.$agregar.'day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
	
	$localidad = "Zapopan, Jal. a :fecha_actual";

$datos_adeudo = "";	
	
/////////////////////////////////////////
/// Función para convertir fecha a letras
/////////////////////////////////////////
 
function dater($x) {
   $year = substr($x, 0, 4);
   $mon = substr($x, 5, 2);
   $day_n = substr($x, 8, 2);
   
   switch (date(N,$x)) {
       case '6':
           $day_n = "lunes";
           break;
       case '5':
           $day_n = "martes";
           break;
	   case '4':
           $day_n = "miércoles";
           break;
	   case '3':
           $day_n = "jueves";
           break;
	   case '2':
           $day_n = "viernes";
           break;  
	   case '1':
           $day_n = "sábado";
           break;
	  case '7':
           $day_n = "domingo";
           break;
       default:
           
           break;
   }
   
   switch($mon) {
      case "01":
         $month = "de enero";
         break;
      case "02":
         $month = "de febrero";
         break;
      case "03":
         $month = "de marzo";
         break;
      case "04":
         $month = "de abril";
         break;
      case "05":
         $month = "de mayo";
         break;
      case "06":
         $month = "de junio";
         break;
      case "07":
         $month = "de julio";
         break;
      case "08":
         $month = "de agosto";
         break;
      case "09":
         $month = "de septiembre";
         break;
      case "10":
         $month = "de octubre";
         break;
      case "11":
         $month = "de noviembre";
         break;
      case "12":
         $month = "de diciembre";
         break;
   }
   $day = substr($x, 8, 2);
   return " ".$day_n." ".$day." de ".$month." del ".$year;
} 


/////////////////////////////////////////	

$resultF = mysql_query("SELECT distinct(familia), familia FROM estado_cuenta_mail", $link) or die(mysql_error());
while($rowF = mysql_fetch_array($resultF)){
	$FamiliaMail = $rowF['familia'];
$html="";

$familia_adeudo_carta = "";
/////////Obtenemos los datos de la familia//////////////
$resultFamilia = mysql_query("SELECT * FROM familias WHERE familia = $FamiliaMail", $link) or die(mysql_error());
		while($rowFamilia = mysql_fetch_array($resultFamilia)){
			$Nombre_Familia = $rowFamilia['nombre_familia'].' '.$rowFamilia['apellido_materno'];
			$Correo_Padre = $rowFamilia['e_mail_padre'];
			$Correo_Madre = $rowFamilia['e_mail_madre'];;	

		}

////////Obtenemos el formato de la carta a enviar////////////
$resultMensajes = mysql_query("SELECT * FROM estado_cuenta_formatos_cartas WHERE id = '$id'", $link) or die(mysql_error());
		while($rowMensaje = mysql_fetch_array($resultMensajes)){
			$mensaje_entrada = $rowMensaje['mensaje_entrada'];
			$mensaje_salida = $rowMensaje['mensaje_salida'];
			$coordinador_administrativo = $rowMensaje['remitente'];
			$titulo_mensaje= $rowMensaje['nombre'];

		}
	$mensaje_entrada = str_replace(":familia_nombre", $Nombre_Familia, $mensaje_entrada);
	$mensaje_entrada = str_replace(":fecha_actual", $Fecha_Actual, $mensaje_entrada);
	//$mensaje_entrada = str_replace(":fecha_baja", $nuevafecha, $mensaje_entrada);
	$mensaje_entrada = str_replace(":fecha_baja", dater($nuevafecha), $mensaje_entrada);
	
	$localidad = str_replace(":fecha_actual", $fecha_emision, $localidad);
	$mensaje_salida = str_replace(":fecha_baja", dater($nuevafecha), $mensaje_salida);
	


$html.= '<!DOCTYPE html>
<html>
  <head>
    <meta  content="text/html; charset=UTF-8"  http-equiv="content-type">
 <style>
th, td{
	/*border-bottom: 1px solid #ddd;*/
} 

tr:nth-child(even) {background: #f2f2f2} 
tr:nth-child(odd) {background: #FFF}
</style>
 </head>
    <body>
    <p  align="center"><img  src="http://ecolmenares.net/sieweb/'.$ruta_colegio.'/im/logo.jpg"></p>
    <p  align="right">'.$localidad.'</p>
    <p  align="center">Estado de cuenta '.$nombre_colegio.'
    	<br />
    	<br /></p>
    <p align="left"> Familia: '.$Nombre_Familia.' </p>					
    <p align="left"> '.$mensaje_entrada.'</p>
    <br />
	<br />
';

$result_alumnos=mysql_query("select distinct(alumno) from estado_cuenta_mail where familia = '$FamiliaMail'", $link)or die(mysql_error());
while($rowA = mysql_fetch_array($result_alumnos)){
							$AlumnoAdeudo = $rowA['alumno'];

$HijoAdeudos = "";
$total_intereses = "";
$total_adeudo = "";										

$result=mysql_query("select * from estado_cuenta_mail where familia = '$FamiliaMail' and alumno = '$AlumnoAdeudo ' ", $link)or die(mysql_error());
	
							while($row = mysql_fetch_array($result)){
							
										$total_intereses = $total_intereses + $row['intereses'];
										$total_adeudo = $total_adeudo + $row['importe'];
										$hijo_name = $row['NombreAlumno'];
										
								
$header_alumno = "<tr><td><br /></td><td>".$row['NombreAlumno']."</td>";

		$HijoEncabezado = '
<br />		
<p>'.$row['NombreAlumno'].':</p>
<table  style="border-collapse: collapse; color: #000;"  width="100%">
      <tbody>
        <tr style="background-color: #b3b3b3; color: #262626; font-weight: bold; text-align: center; border: 1px; box-shadow: 0px 2px 2px grey;">
          <td style="width: 25.3333px;"><br>
          </td>
          <td>Concepto<br>
          </td>
          <td>Mes<br>
          </td>
          <td>Importe<br>
          </td>
          <td>Recargos</td>
          <td style="width: 25.3333px;"><br>
          </td>
        </tr>';
							
$HijoAdeudos.= '
 <tr style="color: #000; text-align: center;">
          <td><br>
          </td>
          <td>'.$row['Concepto'].'
          </td>
          <td>
          	'.$row['Mes'].'
          </td>
          <td style="text-align: left;">$&nbsp;&nbsp;'.number_format($row['importe'],2).'
          </td>
          <td style="text-align: left;">$&nbsp;&nbsp;'.number_format($row['intereses'],2).'
          </td>
          <td><br>
          </td>          
        </tr>
';					


}/////////Del Alumno


$html.= $HijoEncabezado.$HijoAdeudos;

$html.= '
        <tr style="color: #000; text-align: center; font-weight: bold; border-top: 1px solid #000;">
          <td><br>
          </td>
          <td colspan="2" align="right">
          		Subtotal:&nbsp;
          </td>
          <td style="text-align: left;">
          	$&nbsp;&nbsp;'.number_format($total_adeudo,2).'
          </td>
          <td style="text-align: left;">
          	$&nbsp;&nbsp;'.number_format($total_intereses,2).'
          </td>
          <td>
          </td>
        </tr>
        <tr style="color: #000; text-align: center; font-weight: bold; border-top: 1px solid #000;">
          <td style="border-top: 1px solid #000;"><br>
          </td>
          <td colspan="3" align="right" style="border-top: 1px solid #000;">
				Total:&nbsp;&nbsp;
          </td >
          <td style="text-align: left; border-top: 1px solid #000;">
                 	$&nbsp;&nbsp; '.number_format(($total_intereses+$total_adeudo),2).'
          </td>
          <td>
          </td>
        </tr>
      </tbody>
    </table>
';

$datos_adeudo.=$header_alumno."
		<td>
			$&nbsp;&nbsp;&nbsp;".number_format($total_adeudo,2)."
		</td>
		<td>
			$&nbsp;&nbsp;&nbsp;".number_format($total_intereses,2)."
		</td>
		<td>
			<br />
		</td>
	</tr>		
		";
		
							$familia_adeudo_carta.="
											<tr>
												<td>&nbsp;</td>
												<td>".$hijo_name."</td>
												<td>$&nbsp;&nbsp;&nbsp;".number_format($total_adeudo,2)."</td>
												<td>$&nbsp;&nbsp;&nbsp;".number_format($total_intereses,2)."</td>
												<td>&nbsp;</td>
											</tr>";	
			
		
} ///Cargo por Alumno

$gran_total = "";
$result_Total=mysql_query("select (sum(importe) + sum(intereses)) AS GTotal from estado_cuenta_mail where familia = '$FamiliaMail' ", $link)or die(mysql_error());
	
							while($rowT = mysql_fetch_array($result_Total)){
							$gran_total = $rowT['GTotal'];

}

$html.= '
<br />
<br />
<table>
	<thead>
	<tr style="background-color: #b3b3b3; color: #262626; font-weight: bold; text-align: center; border: 1px; box-shadow: 0px 2px 2px grey;">
		<th>
			.
		</th>
		<th>
			<br/>
		</th>
		<th>
			Importe
		</th>
		<th>
			Recargos
		</th>
		<th>
			.
		</th>
	</tr>
	</thead>
	<tbody>
	'.$familia_adeudo_carta.'</tbody>
	<tr style="border-top: 1px #000 solid; border-top: 1px solid #000;">
		<td colspan="100%" align="right">
		<b>Gran Total:&nbsp;$&nbsp;&nbsp;'.number_format($gran_total,2).'&nbsp;&nbsp;</b>
		</td>
	</tr>
</table>	
<br />
<br />
<br />
<p>'.$mensaje_salida.'</p>
<br />
<p align="center">'.$coordinador_administrativo.'</p>
';

//////////////////////PHP Mailer

//// PHP Mailer
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailPass="AltamiraMoctezuma";
$mailSender="Estado de cuenta, $Nombre_Familia";

/// Datos Sistemas
$mailUser="cobranza@e-altamira.edu.mx";
$mailFrom="cobranza@e-altamira.edu.mx";
$mailBCC="sistemas@colmenares.org.mx";
$mailBCC1="ebolanos@colmenares.org.mx";
$mailBCC2="evillanueva@altamira.edu.mx";
$mailBCC3="gmoreno@e-altamira.edu.mx";
$mailBCC4="jhernandez@altamira.edu.mx";
$mailBCC5="fernando.amezcua@altamira.edu.mx";
$mailBCC6="econtreras@colmenares.org.mx";

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
		$mail->Timeout=300;

		$mail->AddBCC($mailBCC);
		$mail->AddBCC($mailBCC1);
		//$mail->AddBCC($mailBCC2);
		$mail->AddBCC($mailBCC3);
		//$mail->AddBCC($mailBCC4);
		//$mail->AddBCC($mailBCC5);
		$mail->AddBCC($mailBCC6);
		$mail->AddReplyTo($mailBCC3, 'Gloria Moreno');

		if($Correo_Padre != ""){
		$mail->AddBCC($Correo_Padre);
		}
		
		if($Correo_Madre != ""){
		$mail->AddBCC($Correo_Madre);
		}

		$mail->Subject="Estado de cuenta, $n_escuela";
		$mail->Body=$html;



$exito = $mail->Send();

  $intentos=1; 
  
  while ((!$exito) && ($intentos < 5)) {
	sleep(5);
     	//echo $mail->ErrorInfo;
     	$exito = $mail->Send();
     	$intentos=$intentos+1;	
	
   }
 
		
   if(!$exito)
   {
	echo "<option style='color: red;' >Error: ".$Nombre_Familia."</option>";
	//echo "<br/>".$mail->ErrorInfo;	
   }
   else
   {
	echo "<option>".$Nombre_Familia."</option>";
   } 


		$mail->ClearAddresses();

	
}//////////Un Mail por Familia

?>