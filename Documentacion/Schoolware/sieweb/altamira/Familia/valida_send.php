<?
include('../connection.php');
include('../config.php');
/////Fecha de última modificación 11-02-2016

$Total = $_REQUEST['Total'];
$Card = $_REQUEST['Card'];
$Expires = $_REQUEST['Expires'];
$CardType = $_REQUEST['CardType'];
$Cert3D = $_REQUEST['Cerdt3D'];
$Reference3D = $_REQUEST['Reference3D'];
$MerchantId = $_REQUEST['MerchantId'];
$MerchantName = $_REQUEST['MerchantName'];
$MerchantCity = $_REQUEST['MerchantCity'];
$ForwardPath = $_REQUEST['ForwardPath'];
$Codigo_Seguridad = $_REQUEST['Codigo_Seguridad'];
$Titular_Tarjeta = $_REQUEST['Titular_Tarjeta'];
$familia = $_REQUEST['familia'];
echo $familia;
$cargos=explode(",", $_REQUEST['combopago']);	
	$cp=count($cargos);	
	$cargo=0;	  
	$orid=0;
	for($i=0;$i<$cp;$i++)
	{
		if($orid == 0)
		{
			$orid=$cargos[$i];
		}
		else
		{
			$cargo=$cargos[$i];
			$sqlpago="INSERT INTO 
								payworks 
									(	
										Card,
										orderid,
										clave,
										concepto,
										periodo_mes,
										total,
										Expires,
										CardType,
										Cert3D,
										Reference3D,
										Titular_Tarjeta,
										Codigo_Seguridad,
										fecha_modificacion,
										familia										
										) 
								VALUES 
									(
										
										'$Card',
										'$orid',
										'".substr($orid,0,5)."',
										'".substr($orid,5,3)."',
										'".substr($orid,8)."',
										'$cargo',
										'$Expires',
										'$CardType',
										'03',
										'$Reference3D',
										'$Titular_Tarjeta',
										'$Codigo_Seguridad',
										now(),
										'$_SESSION[clave]'
										) ";
			//echo $sqlpago."<br />";
			mysql_query($sqlpago,$link)or die(mysql_error());
			$orid=0;
		}
	}
$parametros_payworks = mysql_query ("SELECT 
												*
										FROM
												payworks_parametros", $link) or die ("SELECT 
												*
										FROM
												payworks_parametros".mysql_error());
while ($parametros_p = mysql_fetch_array($parametros_payworks)){
		$MerchantId = $parametros_p['MerchantId'];
		$MerchantName = $parametros_p['MerchantName'];
		$MerchantCity = $parametros_p['MerchantCity'];
		$Afiliation = $parametros_p['Afiliation'];
		$URL_3DSecure = $parametros_p['url_3DSecure'];
}
?>

<img src='loading.gif' />
<body onLoad="document.form1.submit();">
	<form action='https://eps.banorte.com/secure3d/Solucion3DSecure.htm' method='post' name='form1'>
		<input name="Total"			id="Total" 			type="hidden" value="<?=$Total;?>">
		<input name="Card" 			id="Card" 			type="hidden" value="<?=$Card;?>">
		<input name="Expires" 		id="Expires" 		type="hidden" value="<?=$Expires;?>">
		<input name="CardType" 		id="CardType" 		type="hidden" value="<?=$CardType;?>">
		<input name="Cert3D" 		id="Cert3D" 		type="hidden" value="03">
		<input name="Reference3D" 	id="Reference3D" 	type="hidden" value="<?=$Reference3D;?>">
		<input name="MerchantId" 	id="MerchantId"		type="hidden" value="<?=$Afiliation;?>">
		<input name="MerchantName" 	id="MerchantName" 	type="hidden" value="<?=$MerchantName;?>">
		<input name="MerchantCity" 	id="MerchantCity"	type="hidden" value="<?=$MerchantCity;?>">
		<input name="ForwardPath" 	id="ForwardPath" 	type="hidden" value="<?=$URL_3DSecure;?>">
  </form>
</body>
