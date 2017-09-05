<?
session_start();
include('../config.php');
include('../functions.php');
$idConcepto=$_GET['id_concepto'];
$clave= $_GET['alumno'];
$mesPago= $_GET['mespago'];
$periodo= $_GET['periodopago'];
$importe= $_GET['importe'];
$tipoValidacion= $_GET['validacion'];
$tipoLinea=$_GET['linea'];
$mes= $_GET['mes'];
$axo= $_GET['axo'];
$diaRecargo= $_GET['dia'];
$concept= $_GET['concepto'];
$concepto= $_GET['conc'];
//$importe= number_format($_GET['importe'],2);
$dia= $_GET['dia'];
$grupo= $_GET['grupo'];
$nombre= $_GET['nombre'];
$seccion= $_GET['seccion'];
$periodo2 = $periodo + 1;
$linea_captura=lineaCaptura2($idConcepto,$clave,$mesPago,$periodo,$importe,$tipoValidacion,$tipoLinea,$mes,$axo,$diaRecargo);
$today = getdate();
$dia=$today["mday"];
$me=$today["mon"];
$ano=$today["year"];
switch($mes){
	case 1:  $mes_="Enero/".$periodo2; break;
	case 2:  $mes_="Febrero/".$periodo2; break;
	case 3:  $mes_="Marzo/".$periodo2; break;
	case 4:  $mes_="Abril/".$periodo2; break;
	case 5:  $mes_="Mayo/".$periodo2; break;
	case 6:  $mes_="Junio/".$periodo2; break;
	case 7:  $mes_="Julio/".$periodo2; break;
	case 8:  $mes_="Agosto/".$periodo; break;
	case 9:  $mes_="Septiembre/".$periodo; break;
	case 10: $mes_="Octubre/".$periodo; break;
	case 11: $mes_="Noviembre/".$periodo; break;
	case 12: $mes_="Diciembre/".$periodo; break;
}
 ?> 

<html>

   <head> 
      <title>Linea De Captura</title>
   </head>

   <body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
		<br><br><table align="center" cellspacing="8">
<tr align="center">
         <td rowspan="2" colspan="2" align="center"><img src="../<?=$escudo;?>" width="<?=$escudo_ancho; ?>" height="130"></td>  
                 
         <td rowspan="1" colspan="4" align="left"><img src="https://ecolmenares.net/sieweb/banorte.jpg"></td>
         <td><div align="center"><b>Fecha </b><br><?="$dia/$me/$ano"  ?>
         </div></td></tr>
         <td height="98" colspan="4" rowspan="1" align="Center"><p><b>JARDIN DE NI&Ntilde;OS TZITLACALLI, A.C.</b><br>
Modena 1121<br>
C.P. 44620, Guadalajara, Jal.<td><div align="center"><b>MONTEVIDEO <br>
No. Empresa:</b><br>
70501</div></td></tr>
<tr align="center">
         <td></td>
         <td><b>Mes</b><br><?=$mes_ ?></td>
         <td colspan="3"><b>Alumno: </b><br><?=$clave ?> <?=$nombre  ?></td>
         <td>&nbsp;</td>
         <td><b>Grado - Grupo: </b><br><?=$grupo?></td></b>
</tr>
<tr align="center">
         
         <td><b>Ciclo</b><br><?="$periodo/$periodo2";  ?></td>
         
         <td><b>Concepto: </b><br><?=$concept ?></td>
         
         <td></td>
         <td><b>Referencia a Capturar</b><br><?=$linea_captura ?></td>
         <td></td>
         <td><b>Importe</b><br>$<?=$importe  ?></td>
         <td><b>Fecha de Vencimiento</b><br><?="10/$mes_";  ?></td>
</tr>
<tr align="center">
  <td align="Left" colspan="7"><FONT SIZE=1>
 * Pago con cheque de otro banco, presentarse en un s&oacute;lo documento.<br>
 * El plazo para el pago oportuno es el d&iacute;a 10 de cada mes.<br>
 * Conserve su comprobante de pago para cualquier aclaraci&oacute;n.<br>
 * Todo pago efectuado posterior a la fecha de vencimiento, causará recargo del 2.4% mensual por concepto de intereses moratorios.<br></font>
    
    <? $link = mysql_connect($server, $userName, $password);
mySql_select_db($DB)or die("No se pudo seleccionar DB");

if (!$link) {
   die('No se pudo conectar: ' . mysql_error());
}
$s="select * from parametros";
$pago_e=mysql_query($s,$link)or die(mysql_error());
$datos_pago=mysql_fetch_array($pago_e); 
 ?>
  </p>
  <form name="form1" method="post" action="linea2.php">
    <p>
      <input name="Name" id="Name" type="hidden"
 value="<?=$datos_pago["name_pago"];?>">
      <input name="Password" id="Password" type="hidden" value="<?=$datos_pago["password_pago"];?>">
      <input name="ClientId" id="ClientId" type="hidden" value="<?=$datos_pago["client_id_pago"];?>">
      <input name="Mode" id="Mode" value="P"  type="hidden">
      <input name="AffiliationCount" type="hidden" value="1">
      <input name="ResponsePath" type="hidden" value="<?=$datos_pago["url_pago"]?>">
      <input name="OrderId" type="hidden" value="<?=$linea_captura ?>">
      <input name="CustomerName" id="BillToFirstName" type="hidden" value="<?=$nombre  ?>">
      <input name="Amount" type="hidden" value="<?=$importe  ?>">
      <input name="Concept" type="hidden" value="<?="$concept pago de  $mes_"  ?>">
      <input name="E1" type="hidden" value="<?=$clave  ?>">
      <input name="E2" type="hidden" value="<?=$concepto  ?>">
      <input name="E3" type="hidden" value="<?="$periodo/$mes"  ?>">
     
  &nbsp;&nbsp;&nbsp;&nbsp;  <input onClick="javascript:window.print()" type="button" value="Imprimir" name="btnImprimir" />
  </p>
    <p>&nbsp;</p>
</form></td></tr></table>
 
</html>