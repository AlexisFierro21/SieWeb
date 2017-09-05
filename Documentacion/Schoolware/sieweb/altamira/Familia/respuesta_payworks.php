<?
include('../connection.php');
include('../config.php');
/////Fecha de última modificación 11-02-2016

$ECI = $_REQUEST['ECI'];
$CardType = $_REQUEST['CardType'];
$XID = $_REQUEST['XID'];
$CAVV = $_REQUEST['CAVV'];
$Status = $_REQUEST['Status'];
$Reference3D = $_REQUEST['Reference3D'];

$codigo_respuesta = mysql_query("SELECT * FROM payworks_codigos WHERE code = '$Status'",$link) or die ("SELECT * FROM payworks_codigos WHERE code = '$Status'".mysql_error());

while($c_resp = mysql_fetch_array($codigo_respuesta)){
	$respuesta_payworks_ = $c_resp['descripcion'];
}

$parametros_payworks = mysql_query ("SELECT 
												*
										FROM
												payworks_parametros", $link) or die ("SELECT 
												*
										FROM
												payworks_parametros".mysql_error());
while ($parametros_p = mysql_fetch_array($parametros_payworks)){
		$Nombre = $parametros_p['Nombre'];
		$MerchantId = 'a'.$parametros_p['MerchantId'];
		$MerchantName = $parametros_p['MerchantName'];
		$MerchantCity = $parametros_p['MerchantCity'];
		$NumeroControl = $parametros_p['NumeroControl'];
		$User = $parametros_p['User'];
		$Password = $parametros_p['Password'];
		$Afiliation = $parametros_p['Afiliation'];
		$TerminalId = $parametros_p['TerminalId'];
		$URL_Payw2 = $parametros_p['url_payw2'];
}
if($Status==200){
	if($Status==200){
$sqlpago="UPDATE
				payworks	
	  				SET
						status='$Status',
						ECI='$ECI',
						CardType='$CardType',
						XID='$XID',
						CAVV='$CAVV'
					WHERE
						Reference3D = '$Reference3D'";
			mysql_query($sqlpago,$link)or die(mysql_error());
$rst_ = mysql_query ("SELECT 
								Card,
                            	CardType,
                            	Expires,
                            	Codigo_Seguridad,
                            	ECI,
                            	XID,
                            	CAVV,
                            	Reference3D,
                            	Cert3D,
                            	Familia,
                            	Titular_Tarjeta,
                            	sum(total) as total,
                            	status
							FROM 
								payworks 
							WHERE
								Reference3D = '$Reference3D'
							",$link) or die ("
					SELECT 
							Card,
                            CardType,
                            Expires,
                            Codigo_Seguridad,
                            ECI,
                            XID,
                            CAVV,
                            Reference3D,
                            Cert3D,
                            Familia,
                            Titular_Tarjeta,
                            sum(total) as total,
                            status
						FROM 
							payworks 
						WHERE
							Reference3D = '$Reference3D'
								".mysql_error());
								
$html.= "<body onLoad='document.formulario_banorte.submit();'>
					<form name='formulario_banorte' id='formulario_banorte' action='https://via.banorte.com/payw2' method='post' >"	;	
		
	while($rs_=mysql_fetch_array($rst_))
	  {
		$html.= "
		<input name='ID_AFILIACION' 	id='ID_AFILIACION'	type='hidden' value='".$Afiliation."' />
		<input name='USUARIO' 			id='USUARIO'		type='hidden' value='".$User."' />
		<input name='CLAVE_USR' 		id='CLAVE_USR'		type='hidden' value='".$Password."' />
		<input name='CMD_TRANS' 		id='CMD_TRANS'		type='hidden' value='VENTA' />
		<input name='ID_TERMINAL' 		id='ID_TERMINAL'	type='hidden' value='".$TerminalId."' />
		<input name='MONTO' 			id='MONTO'			type='hidden' value='".$rs_['total']."' />
		<input name='MODO' 				id='MODO'			type='hidden' value='PRD' />
		<input name='NUMERO_TARJETA' 	id='NUMERO_TARJETA'	type='hidden' value='".$rs_['Card']."' />
		<input name='FECHA_EXP' 		id='FECHA_EXP'		type='hidden' value='".substr($rs_['Expires'], 0, -3).substr($rs_['Expires'], 3)."' />
		<input name='CODIGO_SEGURIDAD' 	id='CODIGO_SEGURIDAD' type='hidden' value='".$rs_['Codigo_Seguridad']."' />
		<input name='MODO_ENTRADA' 		id='MODO_ENTRADA'	type='hidden' value='MANUAL' />
		<input name='STATUS_3D' 		id='STATUS_3D'		type='hidden' value='".$rs_['status']."' />
		<input name='URL_RESPUESTA' 	id='URL_RESPUESTA' 	type='hidden' value='".$URL_Payw2."' />
		<input name='IDIOMA_RESPUESTA' 	id='IDIOMA_RESPUESTA' type='hidden' value='ES' />
		<input name='ECI'				id='ECI' 			type='hidden' value='".$rs_['ECI']."' />
		<input name='NUMERO_CONTROL'	id='NUMERO_CONTROL'	type='hidden' value='".$Reference3D."' />
		" ;
		if($rs_['XID']<> ""){
			$html.= "	
		<input name='XID' id='XID' type='hidden' value='".$rs_['XID']."' />
				";
		}
		if($rs_['CAVV']<> ""){
			$html.= "
		<input name='CAVV' id='CAVV' type='hidden' value='".$rs_['CAVV']."' />
				";
		}
	  }
	$html.="
	</form>";
	echo $html;
}else{
		echo "Ocurrio un error al procesar la solicitud, verifique que sus datos de tarjeta sean correctos.";
		echo "<br />Error: ".$Status." ".$respuesta_payworks_;
		
		
	}
}else{
	$sqlpago="UPDATE
				payworks	
	  				SET
						status='$Status',
						ECI='$ECI',
						CardType='$CardType',
						XID='$XID',
						CAVV='$CAVV'
					WHERE
						Reference3D = '$Reference3D'";
			mysql_query($sqlpago,$link)or die(mysql_error());
	echo "Ocurrio un error al procesar la solicitud, verifique que sus datos de tarjeta sean correctos.";
	echo "<br />Error: ".$Status." ".$respuesta_payworks_;
}

?>
</body>	