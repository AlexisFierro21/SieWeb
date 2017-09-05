<?
include("../config.php");
require('../../repositorio/phpMailer/PHPMailerAutoload.php');


$html.= '<!DOCTYPE html>
<html>
  <head>
    <meta  content="text/html; charset=UTF-8"  http-equiv="content-type">
 <style>
th, td{
	border-bottom: 1px solid #ddd;
} 

tr:nth-child(even) {background: #f2f2f2} 
tr:nth-child(odd) {background: #FFF}
</style>
 </head>
    <body>
		    <p>Prueba</>
    </body>
</html>  
	
';


//// PHP Mailer
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailPass="AltamiraMoctezuma";
$mailSender="Prueba";

/// Datos Sistemas
$mailUser="cobranza@e-altamira.edu.mx";
$mailFrom="cobranza@e-altamira.edu.mx";
$mailBCC="emmanuel.contreras@colmenares.org.mx";


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
		
		$mail->Subject="Prueba";
		$mail->Body=$html;
				
		if(!$mail->Send()){
			echo"Mailer Error: ".$mail->ErrorInfo;
		}else{
			echo "Correcto";
		}
			


		$mail->ClearAddresses();


?>
