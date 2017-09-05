<?php
session_start();
include('../config.php');
include('../functions.php');

$concepto_anterior=0;

echo "";
function pagado($p,$a,$m,$n,$i,$pe,$d,$g,$no,$s,$c,$x,$tipoValidacion,$tipoLinea,$mes,$axo){
	if($p=='N')
		return "javascript:MM_openBrWindow('linea.php?alumno=$a&mespago=$m&concepto=$n&importe=$x&periodopago=$pe&dia=$d&grupo=$g&nombre=$no&seccion=$s&id_concepto=$c&validacion=$tipoValidacion&linea=$tipoLinea&mes=$mes&axo=$axo','','width=750,height=450,scrollbars=1');";
}
function estilo($p)
{ if ($p=='N') return "cursor:hand;color:blue" ;
}

$clave = $_SESSION['clave'];
$sqlQ = "select * from alumnos, parametros where familia='$clave' and activo = 'A' and alumnos.plantel = parametros.sede";

$link = mysql_connect($server, $userName, $password);;
mySql_select_db($DB)or die("No se pudo seleccionar DB");
if (!$link) {
   die('Could not connect: ' . mysql_error());
}

//echo $sqlQ;
$result=mysql_query($sqlQ,$link)or die(mysql_error());
 ?> 

<html>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Estado de Cuenta</title></head>

<SCRIPT language='javascript' type="text/javascript">
<!--
function showHideTable(tb)
{ 
  if(document.getElementById('table'+tb).style.display=='none') 
  { document.getElementById('table'+tb).style.display='inline';
    document.getElementById('table'+tb).style.display='table'; 
  } 
  else document.getElementById('table'+tb).style.display='none';
}
var arr = new Array;
function mover(a,c,m,p,monto)
{
	var x; 
	while (a.length<5) a="0"+a;
	while (c.length<3) c="0"+c;
	while (m.length<2) m="0"+m;
	p=p.substring(2,4);
	var pago= a+c+p+m;
	for(x=0;x<arr.length;x++)
	{
		if(arr[x]==pago) 
		{
			arr.splice(x,2);
			var tcp=document.getElementById('tcpago').value;
			document.getElementById('tcpago').value=tcp-monto;
			x=99;
		}
	}
	if (x!=100) 
	{
		arr.push(pago,monto);
		document.getElementById('tcpago').value=parseFloat(document.getElementById('tcpago').value)+parseFloat(monto);		
	}
	//document.pasar.submit();
	
}
function pagar(){
	var importe= document.getElementById('tcpago').value;
	//alert(importe);
	if(importe!='0.00')
		MM_openBrWindow('pago.php?combopago='+arr+'&importe='+importe,'','width=750,height=450,scrollbars=1');
	else
		alert('Selecciona el concepto a pagar');
	//MM_openBrWindow('pago.php?combopago='+arr+'&importe='+tcpago.value,'','width=750,height=450,scrollbars=1')
}
function suma(checkbox)
{
	a=parseInt(checkbox.value);
	b=parseInt(document.all("tot_p_elec").value);
	if (checkbox.checked) document.all("tot_p_elec").value=a+b;
	else document.all("tot_p_elec").value=b-a;
}

function MM_openBrWindow(theURL,winName,features) { //v2.0  
window.open(theURL,winName,features);
  return false;
} 
--></script>


<body bgcolor="<?=$color_main; ?>" >


<?php
if ($pago_electronico=="1") { ?>

<center><font size="3" style="font-style:italic;">
Ahora puedes hacer tu pago por internet, s&oacute;lo tienes que seleccionar todos tus pagos y hacer click aqu&iacute;:<br></font><br> </center>



<center><font size="2" style="font-style:italic; color:FF0000;">
Si deseas que tu factura tenga tus datos fiscales,<br> favor de actualizar tus datos en la opci�n: Actualizar Datos Padres</font><br> </center><br>

<center><font size="3">
Total Pago Electr&oacute;nico: &nbsp; $
<input disabled name="tcpago" id='tcpago' value="0.00" size="8"><input type="button" value="Hacer Pago" onClick="pagar();"> &nbsp;&nbsp;

<a href="ayudapagoelectronico.pdf"><font size=2 color=red><b><u>?</u></b></font></a>
</font><br></center>

<br>

<font size="1" style="font-style:italic; color:FF0000;">
(NOTA: Si requiere hacer varios pagos en un d&iacute;a con la misma tarjeta,
recuerde seleccionarlos todos antes de hacer click en "Hacer Pago")
</font>

<left></left></strong> <? } ?> <br>

<table> 
 	<tr>
    	<td align="left">
    	  <strong><font size="2">Alumno </font></strong>
          <font size="1" style="font-style:italic;">(clic en el nombre del alumno(a) para ver detalle)</font>
        </td>
        
		<td align="Center"><strong><font size="2">Saldo</font></strong></td>
  	</tr> 

<?php
$tblCounter=1;
$today = getdate();
$dia=$today["mday"];
$mes=$today["mon"];
$total =0;
$mesActual=fMesRel($mes);
if($mesActual==12 and $dia >=21)
	$mesActual=0;


while($row = mysql_fetch_array($result)){
	$alumno=$row["alumno"];
	$grado=$row["grado"];
	$grupo=$row["grupo"];
	$grupo="$grado-$grupo";
	$nombre =$row["nombre"];
	$ap= $row["apellido_paterno"];
	$am=  $row["apellido_materno"];
	$seccion=$row["seccion"];
	$nombre="$nombre $ap $am";

	$sqlCargos ="select conceptos.nombre,conceptos.dia_recargo_1, cargos.mes, cargos.mes_pago, cargos.importe,conceptos.concepto,cargos.periodo_pago,cargos.periodo,cargos.pagado from  cargos, parametros, mes_relativo, conceptos where alumno = $alumno and (pagado = 'N' or cargos.periodo >= parametros.periodo) and mes_pago = mes_relativo.mes and cargos.periodo = conceptos.ciclo and cargos.concepto = conceptos.concepto order by cargos.periodo_pago, mes_relativo.mes_relativo";

/// Los cargos y abonos de ciclos futuros no estan considerados para el saldo
/// Ciclo actual es considerado en base a la tabla parametros
/// 2017-07-05

$sqlCSaldo="select 
                   sum(importe)as C 
                FROM
                    cargos c, 
                    parametros p, 
                    mes_relativo mr, 
                    conceptos cn
                WHERE
                    alumno = $alumno 
                    and pagado = 'N'
                    and mes_pago = mr.mes 
                    and c.periodo = cn.ciclo 
                    and c.concepto = cn.concepto 
                    and mr.mes_relativo <= $mesActual 
                    and c.periodo <= (SELECT periodo FROM parametros LIMIT 1)";


$sqlASaldo="select 
                     sum(a.importe)  A 
               from 
                    abonos a, 
                    cargos c, 
                    parametros p, 
                    mes_relativo mr, 
                    conceptos cn
                WHERE 
                    c.alumno = $alumno 
                    and pagado = 'N' 
                    and c.mes_pago = mr.mes	
                    and c.periodo = cn.ciclo 
                    and c.concepto = cn.concepto 
                    and c.concepto = a.concepto 
                    and c.periodo = a.periodo	
                    and c.alumno = a.alumno 
                    and c.mes = a.mes	
                    and mr.mes_relativo <= $mesActual
                    and c.periodo <= (SELECT periodo FROM parametros LIMIT 1)
                    ";

$resultCSaldo=mysql_query($sqlCSaldo,$link)or die(mysql_error());
$resultASaldo=mysql_query($sqlASaldo,$link)or die(mysql_error());
$rowCSaldo = mysql_fetch_array($resultCSaldo);
$Csaldo =  $rowCSaldo["C"];
$rowASaldo = mysql_fetch_array($resultASaldo);
$Asaldo =  $rowASaldo["A"];
$saldo=$Csaldo - $Asaldo;
$total = $total + $saldo;

if (is_null($saldo))
	$saldo="0.00";
$result2=mysql_query($sqlCargos,$link)or die(mysql_error());
    ?>
       <tr>
    	<td border=true bordercolor="black" align="left" onClick="javascript: showHideTable(<?=$tblCounter;?>);" style="cursor:hand;border-style:solid;border-width:1;">
 
    	    	  <font size="2"><u><?= $row["nombre"];?></u></font>
    	</td>
    	<td border=true bordercolor="black" align="center" style="border-style:solid;border-width:1">
    	  <font size="2">$<?=number_format($saldo,2) ?> </font>
    	</td>
    </tr>
        <tr>

    	<td rowspan=2>
    	  <div align="center">
            <center>
    	  <table name="table<?=$tblCounter;?>" border="2" id="table<?=$tblCounter;?>" cellspacing="2" border=true bordercolor="black" style=" display:none" valign="center">
    	  	<tr>
    	  		
<td align="center" ><font size="2"><strong>Concepto </strong></font></td>
			
<?php
if ($pago_electronico=="1")  echo"<td align='center' ><font size='2'> <strong>Pago Electr&oacute;nico </strong></font></td>"; ?>
    	  		
<td align="center"><font size="2"><strong>Mes    	</strong></font></td>
<td align="center"><font size="2"><strong>Periodo 	</strong></font></td>    	  		
<td align="center"><font size="2"><strong>Importe 	</strong></font></td>												
<td align="center"><font size="2"><strong>Recargo 	</strong></font></td>
<td align="center"><font size="2"><strong>Abono 		</strong></font></td> </td>
<td align="center"><font size="2"><strong>Saldo 		</strong></font></td>
<td align="center"><font size="2"><strong>Fecha de Pago </strong></font></td>
<td align="center"><font size="2"><strong>Recargo 	</strong></font></td>
<td align="center"><font size="2"><strong>Serie		</strong></font></td>
<td align="center"><font size="2"><strong>Folio FE 	</strong></font></td>
</tr>
<tr>

<?php
$countLine=0;
while($row2 = mysql_fetch_array($result2)){
	$concepto=$row2["concepto"];
	$periodo=$row2["periodo"];
	$mes=$row2["mes"];

	$sqlAbonos = "select concepto, mes, fecha, importe, recargos, periodo, serie, folio_fe,factura_o_recibo, comisiones from abonos where $alumno = abonos.alumno and $concepto = abonos.concepto and $mes = abonos.mes and $periodo = abonos.periodo";
	
	$sqlSumaAbonos = "select sum(importe) as cAbonos from abonos where $alumno = abonos.alumno and $concepto = abonos.concepto and $mes = abonos.mes and $periodo = abonos.periodo";

	//echo $sqlAbonos;
	$result3=mysql_query($sqlAbonos,$link)or die(mysql_error());
	$result4=mysql_query($sqlSumaAbonos,$link)or die(mysql_error());
			
	$cicloMostrar=$row2["periodo"];
	$c=$cicloMostrar++;
	$cicloMostrar=$c."-".$cicloMostrar;
	if ($row2["pagado"] == "S")
		$recargos = 0;
	else
		$abonos = 0;
	if (mysql_num_rows($result4)>0 )
		$row4 = mysql_fetch_array($result4);
	$abonos = $row4["cAbonos"];
	$recargos = fCalculaRecargos (getDate(), $concepto, $row2["importe"] - $abonos, $periodo, $row2["mes_pago"], $alumno, "", $row2["periodo_pago"] ) ;
		
	//aqui iria la suma de los recargos locales
	$impte=$row2["importe"]+$recargos;
	$apa=(round($row2["importe"]*100)/100)+(round($recargos*100)/100)-(round($abonos*100)/100);
	$pagoElectronicoReciente=mysql_num_rows(mysql_query("select * from pago_electronico where ccerrcode=1 and clave=$alumno and concepto=$row2[concepto] and periodo_mes='".substr($row2["periodo"],2).str_pad($mes,2,'0',STR_PAD_LEFT)."'"));
	if($pagoElectronicoReciente>0)
		$row2["pagado"]='S';
?>

<td align="center" valign="center" style="<?=estilo($row2[pagado]);?>" onClick="<?=pagado($row2[pagado],$alumno,$row2[mes_pago],$row2[nombre],$impte,$row2[periodo_pago],$row2[dia_recargo_1],$grupo,$nombre,$seccion,$row2[concepto],$apa,$row2[tipo_validacion_bnm],$row2[tipo_linea_bnm],$row2[mes],$row2[periodo]);?>">
<font size="1">&nbsp;<?= $row2["nombre"];?> &nbsp;</font>	  		
</td>

			<?php if($pago_electronico=="1"){ 
					echo"<td align='center' valign='center'><font size='2'> &nbsp; ";
				if($row2["pagado"]=="N" && $pagoElectronicoReciente<1){?>
					<input type="checkbox" onClick="javascript:mover('<?=$alumno; ?>','<?=$row2["concepto"] ?>','<?=$mes; ?>','<?=$row2["periodo"]; ?>','<?=$apa; ?>');">
					<?php }
				echo"</font></td>"; 
			} ?>
            
<td align="center" valign="center"><font size="1">&nbsp;<?= $row2["mes"];?>&nbsp;</font></td>
<td align="center" valign="center"><font size="1">&nbsp;<?= $cicloMostrar?>&nbsp;</font></td>
<td align="center" valign="center"><font size="1">&nbsp;<?= number_format($row2["importe"],2);?>&nbsp;</font></td>
<td align="center" valign="center"><font size="1">&nbsp;<?= number_format($recargos,2);?>&nbsp; </font></td>    	  		

<?php
    	  	$countAbonos =0;
if (mysql_num_rows($result3)>0 ){
	while($row3 = mysql_fetch_array($result3)){
		$countAbonos = $countAbonos + 1;
		if ($countAbonos > 1){
?>
</tr>

<tr>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<?php } 
?>
			   
<td align="center" valign="center"><font size="1">&nbsp;<?=number_format($row3["importe"],2);?>&nbsp;</font></td>

<?php
$abonos_=$row3["importe"];
$importe_=$row2["importe"];
$recargos_=$recargos;
$saldo_=(($importe_ +$recargos_ ) - $abonos);

if($concepto_anterior!=$concepto)
{
?>

<td align="center" valign="center"><font size="1">&nbsp;<?=number_format($saldo_,2);?>&nbsp;</font></td>
<?php
}
else
{
?>
<td align="center" valign="center"><font size="1">&nbsp;&nbsp;</font></td>

<?php
}
$concepto_anterior=$row2["concepto"];
?>
				
<td align="center" valign="center"><font size="1">&nbsp;<?=formatDate($row3["fecha"]);   

/*substr($row3["fecha"],0,10);*/?>&nbsp;</font>
	</td>
    	  		
<td align="center" valign="center"><font size="1">&nbsp;<?=number_format($row3["recargos"],2);?>&nbsp;</font></td>
<td align="center" valign="center"><font size="1">&nbsp;<?=($row3["serie"]); ?>&nbsp;</font></td>
<td align="center" valign="center"><font size="1">&nbsp;<?=($row3["folio_fe"]);
?>&nbsp;</font></td>
  </tr>
    	  	<?php }
    	  	}
			  else
			  {
			    
				?>
			        	  		
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
<td align="center" valign="center"><font size="1"></font></td>
</tr>
			
			
<?php    
			  
			}
		
               if ($countLine==7)
			   {
			    
?>
				
<tr>
    	  		<td align="center" >
    	  			<font size="2"><strong>Concepto </strong></font>
    	  		</td>
			
			<?php if ($pago_electronico=="1") echo"<td align='center' ><font size='2'><strong>Pago Electr&oacute;nico </strong></font></td>"; ?>
    	  		
<td align="center"><font size="2"><strong> Mes 		</strong></font></td>
<td align="center"><font size="2"><strong> Periodo 	</strong></font></td>
<td align="center"><font size="2"><strong> Importe 	</strong></font></td>
<td align="center"><font size="2"><strong> Recargo 	</strong></font></td>
<td align="center"><font size="2"><strong> Abono 	</strong></font></td>
<td align="center"><font size="2"><strong> Saldo 	</strong></font></td>
<td align="center"><font size="2"><strong> Fecha de Pago </strong></font></td>
<td align="center"><font size="2"><strong> Recargo </strong></font></td>
<td align="center"><font size="2"><strong> Serie </strong></font></td>
<td align="center"><font size="2"><strong> Folio FE </strong></font></td>
  </tr>
    	  					<?
    	  						$countLine=0;
								}		
							  $countLine=$countLine + 1;
							  } ?>
    	  
</table>
    	    </center>
          </div>
    	</td>
    </tr>
    
      <tr>
  <td></td>
  </tr> 
    
				<?php

				$tblCounter = $tblCounter + 1;
				  }
				 ?> 
   
    
    
    
  
 
    <tr>
  <td></td>
  </tr>
    <tr>
    	<td align="right">
        <font size="1" style="font-style:italic;">Nota: En el saldo aparece la cantidad pendiente</font><br>
        <font size="2">TOTAL:</font>
        </td>
        
  <td style="border-style:solid;border-width:1" bordercolor="black" align="center"><font size="2">$<?=number_format($total);  ?>.00</font>		</td>
    	<td colspan=4 align="right"><font size="1">   </font></td>
    	    
    </tr>
     
</table>

<P CLASS="breakhere">

</body>
<head>

<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

</head>
</html>