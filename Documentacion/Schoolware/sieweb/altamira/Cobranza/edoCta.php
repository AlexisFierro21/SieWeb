<?php
include('../config.php');
include('../functions.php');
mysql_query("SET NAMES 'utf8'");

$concepto_anterior=0;

$clave = $_REQUEST['familia'];

if ($clave != ''){
$sqlQ = "select * from alumnos, parametros where familia='$clave' and activo = 'A' and alumnos.plantel = parametros.sede";
}else{
	$sqlQ = "select * from alumnos, parametros where activo = 'A' and alumnos.plantel = parametros.sede order by familia";
}
$link = mysql_connect($server, $userName, $password);;
mySql_select_db($DB)or die("No se pudo seleccionar DB");
if (!$link) {
   die('Could not connect: ' . mysql_error());
}


	//echo $clave;
$result=mysql_query($sqlQ,$link)or die(mysql_error());

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

	$sqlCargos ="select conceptos.nombre,conceptos.dia_recargo_1, cargos.mes, cargos.mes_pago, cargos.importe,conceptos.concepto,cargos.periodo_pago,cargos.periodo,cargos.pagado from  cargos, parametros, mes_relativo, conceptos where alumno = $alumno and (pagado = 'N' or cargos.periodo >= parametros.periodo) and mes_pago = mes_relativo.mes and cargos.periodo = conceptos.ciclo and cargos.concepto = conceptos.concepto and pagado='N' order by cargos.periodo_pago, mes_relativo.mes_relativo";

$sqlCSaldo="select sum(importe)as C from cargos, parametros, mes_relativo mr, conceptos where alumno = $alumno and pagado = 'N' and mes_pago = mr.mes and cargos.periodo = conceptos.ciclo and cargos.concepto = conceptos.concepto and mr.mes_relativo <= $mesActual";

$sqlASaldo="select sum(abonos.importe) as A from abonos, cargos, parametros, mes_relativo mr, conceptos where cargos.alumno = $alumno and pagado = 'N' and cargos.mes_pago = mr.mes	and cargos.periodo = conceptos.ciclo and cargos.concepto = conceptos.concepto and cargos.concepto = abonos.concepto and cargos.periodo = abonos.periodo	and cargos.alumno = abonos.alumno and cargos.mes = abonos.mes	and mr.mes_relativo <= $mesActual";

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

$countLine=0;
while($row2 = mysql_fetch_array($result2)){
	$concepto=$row2["concepto"];
	$periodo=$row2["periodo"];
	$mes=$row2["mes"];

	$sqlAbonos = "select concepto, mes, fecha, importe, recargos, periodo, serie, folio_fe,factura_o_recibo, comisiones from abonos where $alumno = abonos.alumno and $concepto = abonos.concepto and $mes = abonos.mes and $periodo = abonos.periodo ";
	
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

echo"
<table width='798'>
<tr>
	<td width='2%' align='center' valign='center'><font size='2'>";
	
				if($row2["pagado"]=="N" && $pagoElectronicoReciente<1){
					echo "<input type='checkbox' ></font></td>";
					 /*$alumno.$row2["concepto"].$mes.$row2["periodo"].$apa; */
				}

  echo '
	<td width="8.5%" ><font size="1">&nbsp;&nbsp;'.$row["familia"].'</font></td>	
	<td width="9%"><font size="1">&nbsp;&nbsp;'.$row["alumno"].'</font></td>
	<td width="27%"><font size="2">&nbsp;&nbsp;'.$row["nombre"].' '.$row["apellido_paterno"].' '.$row["apellido_materno"].'&nbsp;&nbsp;</font></td>
	<td width="20%"><font size="1">&nbsp;&nbsp;'.$row2["nombre"].'&nbsp;&nbsp;</font></td>
	<td width="5%" align="center" valign="center"><font size="1">&nbsp;'.$row2["mes"].'&nbsp;</font></td>
	<td width="10%" align="center" valign="center"><font size="1">&nbsp;'.$cicloMostrar.'&nbsp;</font></td>
	<td width="10%" align="center" valign="center"><font size="1">&nbsp;'.number_format($row2["importe"],2).'&nbsp;</font></td>
	<td width="10%" align="center" valign="center"><font size="1">&nbsp;'.number_format($recargos,2).'&nbsp; </font></td>
</tr>
		';
    	  	$countAbonos =0;
if (mysql_num_rows($result3)>0 ){
	while($row3 = mysql_fetch_array($result3)){
		$countAbonos = $countAbonos + 1;
		if ($countAbonos > 1){

 
		} 

$abonos_=$row3["importe"];
$importe_=$row2["importe"];
$recargos_=$recargos;
$saldo_=(($importe_ +$recargos_ ) - $abonos);

if($concepto_anterior!=$concepto)

$concepto_anterior=$row2["concepto"];

 }
    	  	}
			  else
			  {
echo "</tr>";			    
			  
			}
		
               if ($countLine==7)
			   {
			    
echo "<tr>";			    

		 if ($pago_electronico=="1") 
    	  						$countLine=0;
								}		
							  $countLine=$countLine + 1;
							  } 
echo "</tr>";			    

				$tblCounter = $tblCounter + 1;
				  }
echo "</table>";
				 ?>