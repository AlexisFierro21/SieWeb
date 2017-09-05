<?php
//Genera c�digo alfanumerico
function RandomString($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE)
{
    $source = 'abcdefghijklmnopqrstuvwxyz';
    if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if($n==1) $source .= '1234567890';
    if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
    if($length>0){
        $rstr = "";
        $source = str_split($source,1);
        for($i=1; $i<=$length; $i++){
            mt_srand((double)microtime() * 1000000);
            $num = mt_rand(1,count($source));
            $rstr .= $source[$num-1];
        }
    }
    return $rstr;
}

function convertTildeToHTML($string){
	$array=array('�'=>'&Aacute;', '�'=>'&Eacute;', '�'=>'&Iacute;', '�'=>'&Oacute;', '�'=>'&Uacute;', '�'=>'&Uuml;', '�'=>'&Ntilde;', '�'=>'&aacute;', '�'=>'&eacute;', '�'=>'&iacute;', '�'=>'&oacute;', '�'=>'&uacute;', '�'=>'&uuml;', '�'=>'&ntilde;');
	$var=strtr($string,$array);
	if($var!=$string)
		echo "$var<br>";
	return $var;
}

function f_termina_test($id_publicacion, $alumno){
	//echo "<script language='javascript'>alert('entra f_termina_test');</script>";
	/*$date=getdate();
	if($date[mon]<10)
		$date[mon]='0'.$date[mon];
	if($date[mday]<10)
		$date[mday]='0'.$date[mday];
	$fecha=$date[year].'-'.$date[mon].'-'.$date[mday];*/
	$ld_maxima_total=0;
	$ld_calificacion_total=0;
	$ls_comentario="";
	$id_test=mysql_fetch_array(mysql_query("SELECT id_test FROM test_publicacion WHERE id_publicacion=$id_publicacion"));
	//para cada area-aspecto en test_evaluacion_areas
	$result2=mysql_query("SELECT DISTINCT id_area, id_aspecto FROM test_preguntas WHERE id_test=$id_test[id_test] AND id_area!=0 ORDER BY id_area");
	while($area_aspecto=mysql_fetch_array($result2)){
		$ld_maxima=f_maxima_calificacion_test($id_test[id_test],$area_aspecto[id_area],$area_aspecto[id_aspecto]);
		$ld_calificacion=f_calificacion_test($id_publicacion, $alumno, $area_aspecto[id_area], $area_aspecto[id_aspecto]);
		if($ld_maxima>0)
			$ld_resultado=($ld_calificacion/$ld_maxima)*100;
		if($area_aspecto[id_aspecto]!=0)
			$ls_comentario=f_comentario_test_area($id_test[id_test],$area_aspecto[id_area],$area_aspecto[id_aspecto],$ld_resultado);
		else
			$ls_comentario=f_comentario_test($id_publicacion, $ld_resultado);
		//agregar en tabla test_resultados_areas($id_publicacion, $alumno, $id_area, $id_aspecto, $resultado, $comentario)
		mysql_query("INSERT INTO test_resultados_areas(id_publicacion,alumno,id_area,id_aspecto,resultado,comentario,sugerencia) VALUES ($id_publicacion,$alumno,$area_aspecto[id_area],$area_aspecto[id_aspecto],$ld_resultado,'$ls_comentario[comentario]','$ls_comentario[sugerencia]')");
		$ld_maxima_total+=$ld_maxima;
		$ld_calificacion_total+=$ld_calificacion;
	}
	$ld_resultado_total=($ld_calificacion_total/$ld_maxima_total)*100;
	$comentario_test=f_comentario_test($id_publicacion, $ld_resultado_total);
	//echo "<script language='javascript'>alert('".$comentario_test[comentario].$comentario_test[sugerencia]."');</script>";
	//actualiza en test_estatus(id_publicacion, alumno), terminado, fechaterminado, calificacion, comentario.
	mysql_query("UPDATE test_estatus SET terminado='S',fechaterminado=curdate(),calificacion=$ld_resultado_total,comentario='$comentario_test[comentario]',sugerencia='$comentario_test[sugerencia]' WHERE id_publicacion=$id_publicacion AND responde=$alumno");
}

function f_maxima_calificacion_test($id_test, $id_area, $id_aspecto){
	//echo "<script language='javascript'>alert('entra f_maximo_calificacion');</script>";
	$total_puntos=0;
	$result3=mysql_query("SELECT id_pregunta FROM test_preguntas WHERE id_test=$id_test AND id_area=$id_area AND id_aspecto=$id_aspecto");
	while($id_pregunta=mysql_fetch_array($result3)){
		$max=mysql_fetch_array(mysql_query("SELECT MAX(puntos) AS puntos FROM test_opciones WHERE id_pregunta=$id_pregunta[id_pregunta]"));
		$total_puntos+=$max[puntos];
	}
	return $total_puntos;
}

function f_calificacion_test($id_publicacion, $alumno, $id_area, $id_aspecto){
	//echo "<script language='javascript'>alert('entra f_calificacion_test');</script>";
	$puntos=0;
	$id_test=mysql_fetch_array(mysql_query("SELECT id_test FROM test_publicacion WHERE id_publicacion=$id_publicacion"));
	$result4=mysql_query("SELECT id_pregunta FROM test_preguntas WHERE id_test=$id_test[id_test] AND id_area=$id_area AND id_aspecto=$id_aspecto");
	while($id_pregunta=mysql_fetch_array($result4)){
		$id_opcion=mysql_fetch_array(mysql_query("SELECT id_opcion FROM test_respuestas WHERE id_publicacion=$id_publicacion AND id_pregunta=$id_pregunta[id_pregunta] AND responde=$alumno"));
		$pts=mysql_fetch_array(mysql_query("SELECT puntos FROM test_opciones WHERE id_opcion=$id_opcion[id_opcion]"));
		$puntos+=$pts[puntos];
	}
	return $puntos;
}

function f_comentario_test_area($id_test, $id_area, $id_aspecto, $ld_resultado){
	//echo "<script language='javascript'>alert('entra f_comentario_test');</script>";
	//tomar comentario correspondiente al &aacute;rea-aspecto de acuerdo a los puntos, desde test_evaluacion_areas y hacia test_resultados_areas.
	$counter=1;
	$result5=mysql_query("SELECT min,max FROM test_evaluacion WHERE id_test=$id_test ORDER BY min");
	for($i=0;$i<3;$i++){
		$limits=mysql_fetch_array($result5);
		if($ld_resultado>=$limits[min] && $ld_resultado<=$limits[max])
			break;
		$counter++;
	}
	$comentario=mysql_fetch_array(mysql_query("SELECT comentario$counter AS comentario, sugerencia$counter AS sugerencia FROM test_evaluacion_areas WHERE id_test=$id_test AND id_area=$id_area AND id_aspecto=$id_aspecto"));
	return $comentario;
}

function f_comentario_test($id_publicacion, $ld_resultado_total){
	$id_test=mysql_result(mysql_query("SELECT id_test FROM test_publicacion WHERE id_publicacion=$id_publicacion"),0);
	$counter=1;
	$result6=mysql_query("SELECT * FROM test_evaluacion WHERE id_test=$id_test ORDER BY min");
	for($i=0;$i<3;$i++){
		$limits=mysql_fetch_array($result6);
		if($ld_resultado_total>=$limits[min] && $ld_resultado_total<=$limits[max])
			break;
		$counter++;
	}
	return $limits;
}

function lineaCaptura2($concepto,$alumno,$mesPago,$axoPago,$importe,$tipoValidacion,$tipoLinea,$mes,$axo,$diaRecargo){
	//FORMAR CLAVE EMPRESA
	//$claveLineaBNM=mysql_result(mysql_query("select clave_linea_bnm from parametros"),0);
	$manejaPlantelBNM='N';
	//if(mysql_result(mysql_query("select maneja_plantel_bnm from parametros"),0)!=null)
		//$manejaPlantelBNM=mysql_result(mysql_query("select maneja_plantel_bnm from parametros"),0);
	// AGREGA DATO A LA CADENA (clave colegio): CCCC
	//if($claveLineaBNM>9999)
		//11111 - clave colegio
		//$temporal=str_pad($claveLineaBNM,5,'0',STR_PAD_LEFT).' ';
	//else
		//1111 - clave colegio
		//$temporal=str_pad($claveLineaBNM,4,'0',STR_PAD_LEFT).' ';
	// AGREGA DATO A LA CADENA ('T'ipo de l�nea, 'A'lumno, 'C'oncepto, 'P'er�odo, 'M'es): TT AAAA ACCC PPPP MM
	//22 - tipo l�nea captura
	//$temporal.=str_pad($tipoLinea,2,'0',STR_PAD_LEFT).' ';
	//if($manejaPlantelBNM!='S'){
		//3333 3 - clave alumno
		$temporal.=substr(str_pad($alumno,5,'0',STR_PAD_LEFT),0,4).substr($alumno,-1);
		//444 - clave concepto
		$temporal.=str_pad($concepto,3,'0',STR_PAD_LEFT).' ';
		//5555 - periodo del cargo
		$temporal.=str_pad(substr($axo,-3),3,'0',STR_PAD_LEFT).' ';
		//66 - mes del cargo
		$temporal.=str_pad($mes,2,'0',STR_PAD_LEFT).' ';
	//}
	/*else{
		$_sede=mysql_result(mysql_query("select sede from parametros"));
		//PP - plantel
		$temporal.=str_pad($_sede,2,'0',STR_PAD_LEFT);
		//33 333 - clave alumno
		$temporal.=substr(str_pad($alumno,5,'0',STR_PAD_LEFT),0,2).' '.substr($alumno,-3);
		//4 44 - clave concepto
		$temporal.=substr(str_pad($concepto,3,'0',STR_PAD_LEFT),0,1).' '.substr($concepto,-2);
		//55 55 - periodo del cargo
		$temporal.=substr(str_pad($axo,4,'0',STR_PAD_LEFT),1,1).' '.substr($axo,-2);
		//66 - mes del cargo
		$temporal.=str_pad($mes,2,'0',STR_PAD_LEFT).' ';
	}*/
	// SI VALIDA FECHA ->
	// FORMA FECHA DE ACUERDO A LOS DATOS DEL CARGO
	//if($tipoValidacion==2 or $tipoValidacion==3){
		// CALCULA RECARGOS
		$periodoPago=$axoPago;
		$_mesInicialPeriodoActual=mysql_result(mysql_query("select mes_inicial_periodo_actual from parametros"),0);
		if($mesPago<$_mesInicialPeriodoActual)
			$axoPago++;
		if($diaRecargo!=31)
			$dia=$diaRecargo-1;
		else{
			$arrayFecha=fFechaFinalDelMes($mesPago,$axoPago);
			$dia=$arrayFecha[mday];
		}
		$dateArray[year]=$axoPago;
		$dateArray[mon]=$mesPago;
		$dateArray[mday]=$dia;
		//$recargos=fCalculaRecargos(getdate(),$concepto,$importe,$axo,$mesPago,$alumno,'',$periodoPago);
		//if($recargos==null)
			//$recargos=0;
		//$importe+=$recargos;
		$periodo=($axoPago-1988)*372;
		$mesNuevo=($mesPago-1)*31;
		$fecha=$periodo+$mesNuevo+$dia;
		// Agrega fecha a la cadena
		//77 77 - fecha condensada
		$temporal.=substr(str_pad($fecha,4,'0',STR_PAD_LEFT),0,2).substr(str_pad($fecha,4,'0',STR_PAD_LEFT),-2).' ';
	//}
	// SI SE VALIDA EL IMPORTE
	//if($tipoValidacion==1 or $tipoValidacion==2){
		$entra=1;
		$totalCantidad=0;
		// CONSIDERA CENTAVOS
		$importeNuevo=$importe*100;
		for($x=strlen($importeNuevo)-1;$x>=0;$x--){
			$intTemp=substr($importeNuevo,$x,1);
			if($entra==1){
				$totalCantidad+=($intTemp*7);
				$entra=2;
			}
			elseif($entra==2){
				$totalCantidad+=($intTemp*3);
				$entra=3;
			}
			else{
				$totalCantidad+=($intTemp*1);
				$entra=1;
			}
		}
		$importe/=100;
		// EXTRAE EL RESIDUO DE ll_total_cantidad / 10, EL RESULTADO
		// LA FORMA CONDENSADA DEL IMPORTE
		$importeAbreviado=$totalCantidad%10;
		// FORMAR PARTE DE LA LINEA DE CAPTURA
		//8 - importe condensado
		$temporal.=$importeAbreviado;
	//}
	// TIPO DE VALIDACI�N
	//9 - tipo de validaci�n
	//$temporal.=$tipoValidacion;
	// DIGITOS DE VALIDACI�N
	//AA - d�gitos verificadores
	// FORMA DIGITOS GLOBALES DE VALIDACION
	// ELIMINA ESPACIOS INTERMEDIOS EN LA CADENA
	$linea=str_replace(' ', '', $temporal);
	$entra=1;
	$totalCantidad=0;
	for($x=strlen($linea)-1;$x>=0;$x--){
		$char=substr($linea,$x,1);
		if(is_numeric($char)){
			$intTemp=intval($char);
			if($entra==1){
				$totalCantidad+=($intTemp*11);
				$entra=2;
			}
			elseif($entra==2){
				$totalCantidad+=($intTemp*13);
				$entra=3;
			}
			elseif($entra==3){
				$totalCantidad+=($intTemp*17);
				$entra=4;
			}
			elseif($entra==4){
				$totalCantidad+=($intTemp*19);
				$entra=5;
			}
			elseif($entra==5){
				$totalCantidad+=($intTemp*23);
				$entra=1;
			}
		}
	}
	// CHECAR RESIDUO
	$formaCondensada=($totalCantidad%97)+1;
	// FORMAR PARTE DE LA LINEA DE CAPTURA
	$temporal.=' '.str_pad($formaCondensada,2,'0',STR_PAD_LEFT);
	//return 'B: '.$temporal;
	return $temporal;
}

function linea_captura($alumno, $concepto, $periodo, $mes) 
{
  	include('../connection.php');
  	$link = mysql_connect($server, $userName, $password);
	//mysql_query("SET NAMES utf8");/// AGREGU� 22-07-2011

	mySql_select_db($DB)or die("No se pudo seleccionar DB");
	if (!$link) die('No se pudo conectar: ' . mysql_error());
	// LEE DATOS DE PAR�METROS
	$sql = "select * from parametros";
	$resultP=mysql_query($sql,$link)or die(mysql_error());
	$parametros = mysql_fetch_array($resultP);
	// LEE DATOS DE CONCEPTOS
	$sql2 = "select * from conceptos where concepto=$concepto and ciclo=$periodo";
	$resultCO=mysql_query($sql2,$link)or die(mysql_error());
	$conceptos = mysql_fetch_array($resultCO);
	// LEE DATOS DE CARGOS
	$sql3 = "select * from cargos where concepto=$concepto and mes=$mes";
	$resultCA=mysql_query($sql3,$link)or die(mysql_error());
	$cargos = mysql_fetch_array($resultCA);
	
// Agrega dato a la cadena ('A'lumno, 'C'oncepto, 'P'er�odo, 'M'es
//										TT AAAA ACCC PPPP MM
	$alumno=str_pad((string)$alumno,5,"0",STR_PAD_LEFT);
	$concepto=str_pad((string)$concepto,3,"0",STR_PAD_LEFT);
	$periodo=str_pad(((string)$periodo%1000),3,"0",STR_PAD_LEFT);
	$mes=str_pad((string)$mes,2,"0",STR_PAD_LEFT);
	$ls_temporal = $alumno.$concepto." ".$periodo." ".$mes;
	
// FORMA FECHA DE ACUERDO A LOS DATOS DEL CARGO
$mes_pago=$cargos["mes_pago"];
if ($cargos["mes_pago"] < $parametros["mes_inicial_periodo_actual"]) $axo_pago=$cargos["periodo_pago"]+1;
// Al a�o se le resta el n�mero 1988 y se multiplica por el n�mero 372.
$li_periodo = ($axo_pago - 1988 ) * 372;
// Al mes se le resta la unidad (1) y se multiplica por el n�mero 31.
$li_mes = ( $cargos["mes_pago"] - 1 ) * 31;
// Al d�a se le resta la unidad (1).
$li_dia = $conceptos["dia_recargo_1"] - 1;
// Se suman los resultados de los puntos 1, 2 y 3, y el resultado es la fecha condensada
$li_Fecha   = $li_periodo + $li_Mes + $li_Dia;
// Agrega fecha a la cadena
$li_Fecha=str_pad((string)$li_Fecha,4,"0",STR_PAD_LEFT);
$ls_temporal .=" ".$li_Fecha;

// FORMA EL IMPORTE CONDENSADO
//
$li_entra = 7;
$li_contador = 0;
// considera centavos
$ll_importe = $cargos["importe"] * 100;
for($x=strlen((string)$ll_importe);$x>=1;$x--){
	$li_Temp = substr((string)$ll_importe, $x, 1);
	$li_array[$x] = (int)$li_temp * $li_entra;
	if($li_entra == 7) $li_entra=3;
	elseif ($li_entra == 3) $li_entra = 1;
	else $li_entra = 7;
	$li_contador++; }
//ad_importe = ad_importe / 100
$ll_total_cantidad = 0;
// HACE SUMA DEL CONTENIDO DE LI_ARRAY
for($x=1;$x<=$li_contador;$x++){
	$ll_total_cantidad += $li_array[$x]; }
// EXTRAE EL RESIDUO DE ll_total_cantidad / 10, EL RESULTADO
// LA FORMA CONDENSADA DEL IMPORTE
$li_importe_abreviado =  $ll_total_cantidad%10;	//CHECAR RESIDUO

// FORMAR PARTE DE LA LINEA DE CAPTURA
//
$ls_temporal.=" ".(string)$li_importe_abreviado; 

// DIGITOS DE VALIDACI�N
//
// FORMA DIGITOS GLOBALES DE VALIDACION
//
$ls_linea=str_replace(" ","",$ls_temporal);
$li_entra = 11;
$li_contador = 0;
for($x=strlen((STRING)$ls_linea);$x>=1;$x--){
	$ls_char = substr((string)$ls_linea, $x, 1);
	if (is_numeric($ls_char)){
		$li_Temp = (integer)$ls_char;
		$li_arrays[$x] = $li_temp * $li_entra;
		if ($li_entra == 11) $li_entra = 13;
		elseif ($li_entra == 13) $li_entra = 17;
		elseif ($li_entra == 17) $li_entra = 19;
		elseif ($li_entra == 19) $li_entra = 23;
		else $li_entra = 11;
		$li_contador++; } }

$ll_total_cantidad = 0;
// HACE SUMA DE ARRAY
for($x=1;$x<=$li_contador;$x++){
	$ll_total_cantidad += $li_arrays[$x]; }

// EXTRAE EL RESIDUO DE ll_total_cantidad / 97, EL RESULTADO
// LA FORMA CONDENSADA DE LA LINEA DE CAPTURA
//
$li_forma_condensada = ($ll_total_cantidad%97) +1;	//CHECAR RESIDUO

//
// FORMAR PARTE DE LA LINEA DE CAPTURA
//
$li_forma_condensada=str_pad((string)$li_forma_condensada,2,"0",STR_PAD_LEFT);
$ls_temporal.= " ".(string)$li_forma_condensada;

return $ls_temporal;
}
//functions pablo
function fCalculaRecargos ($adFecha, $aiConcepto, $adImporte, $aiPeriodo, $aiMesPago, $alAlumno, $asBeca, $aiPeriodoPago ) {
  	include('connection.php');
  	$link = mysql_connect($server, $userName, $password);
	mySql_select_db($DB)or die("No se pudo seleccionar DB");
	if (!$link) die('No se pudo conectar: ' . mysql_error());
	// LEE DATOS DE PAR�METROS
	$sSqlQ = "select mes_inicial_periodo_actual, recargos_sab_dom, factor_redondeo from parametros";
	$resultP=mysql_query($sSqlQ,$link)or die(mysql_error());
	$rowP = mysql_fetch_array($resultP);
	$iMesInicialPeriodoActual=$rowP["mes_inicial_periodo_actual"];
	$cRecargosSabDom=$rowP["recargos_sab_dom"];
	$dFactorRedondeo=$rowP["factor_redondeo"];
	// LEE DATOS DE CONCEPTOS
	$sSqlQ = "select * from conceptos where concepto = ' $aiConcepto ' and ciclo = ' $aiPeriodo '";
	$resultP=mysql_query($sSqlQ,$link)or die(mysql_error());
	$rowP = mysql_fetch_array($resultP);
	$iDiaRecargo1=$rowP["dia_recargo_1"];
	$iDiaRecargo2=$rowP["dia_recargo_2"];
	$iDiaRecargo3=$rowP["dia_recargo_3"];
	$cCantOPorc1=$rowP["cant_o_porc_1"];
	$cCantOPorc2=$rowP["cant_o_porc_2"];
	$cCantOPorc3=$rowP["cant_o_porc_3"];
	$dRecargo1=$rowP["recargo_1"];
	$dRecargo2=$rowP["recargo_2"];
	$dRecargo3=$rowP["recargo_3"];
	$cMesCompleto=$rowP["mes_completo"];
	$cCantOPorcMes=$rowP["cant_o_porc_mes"];
	$dRecargoMes=$rowP["recargo_mes"];
	$cAcumularMes=$rowP["acumular_mes"];
	$cInscripColegOtros=$rowP["inscrip_coleg_otros"];
	// CHECA SI EL ALUMNO TIENE VENCIMIENTO ESPECIAL O NO APLICA RECARGOS
	if ($cInscripColegOtros=='C'){
		$sSqlQ = "select dia_vencimiento_colegiaturas, aplica_recargos from alumnos where alumno = ' $alAlumno '";
		$resultP=mysql_query($sSqlQ,$link)or die(mysql_error());
		$rowP = mysql_fetch_array($resultP);
		$iDiaVencimientoColegiaturas=$rowP["dia_vencimiento_colegiaturas"];
		$cAplicaRecargos=$rowP["aplica_recargos"];
		if ($iDiaVencimientoColegiaturas > 0) $iDiaRecargo1 = $iDiaVencimientoColegiaturas;
		if ($cAplicaRecargos=='N') return 0;
	} 
	
		
 //	mysql_close($link);

	// SI EL IMPORTE ES 0 -> NO CALCULA RECARGOS
	if ($adImporte == 0) return 0;
	// CALCULA PERIODO DE LA FECHA
	$iPeriodoActual = $adFecha["year"];

	if ($adFecha["mon"] < $iMesInicialPeriodoActual) $iPeriodoActual = $iPeriodoActual - 1;
	// CALCULA A�O DE VENCIMIENTO DEL CARGO (PARA EFECTOS DE NO COBRO DE 
	//		RECARGOS EN S�BADO O DOMINGO)
	$iAnoVencimiento = $aiPeriodo;
	if ($aiMesPago < $iMesInicialPeriodoActual)	$iAnoVencimiento = $iAnoVencimiento + 1;

	// SI NO SE CALCULAN RECARGOS A PARTIR DE S�BADO O DOMINGO -> AJUSTA D�AS DE INICIO EN CASO NECESARIO
	if ($cRecargosSabDom=='N'){
		if ($iDiaRecargo1 > 0){
			$dFechaInicioRecargos = getDate(mktime(0,0,0,$aiMesPago,$iDiaRecargo1 - 1,$iAnoVencimiento));
			$iDiaSemana = $dFechaInicioRecargos["wday"];
			while ( $iDiaSemana == 0 OR $iDiaSemana == 6) {
				$iDiaRecargo1 = $iDiaRecargo1  + 1;
				$dFechaInicioRecargos = getDate(mktime(0,0,0,$aiMesPago,$iDiaRecargo1 - 1,$iAnoVencimiento));
				$iDiaSemana = $dFechaInicioRecargos["wday"];
			}
		}
		if ($iDiaRecargo2 > 0){
			$dFechaInicioRecargos = getDate(mktime(0,0,0,$aiMesPago,$iDiaRecargo2 - 1,$iAnoVencimiento));
			$iDiaSemana = $dFechaInicioRecargos["wday"];
			while ( $iDiaSemana == 0 OR $iDiaSemana == 6) {
				$iDiaRecargo2 = $iDiaRecargo2  + 1;
				$dFechaInicioRecargos = getDate(mktime(0,0,0,$aiMesPago,$iDiaRecargo2 - 1,$iAnoVencimiento));
				$iDiaSemana = $dFechaInicioRecargos["wday"];
			}
		}
		if ($iDiaRecargo3 > 0){
			$dFechaInicioRecargos = getDate(mktime(0,0,0,$aiMesPago,$iDiaRecargo3 - 1,$iAnoVencimiento));
			$iDiaSemana = $dFechaInicioRecargos["wday"];
			while ( $iDiaSemana == 0 OR $iDiaSemana == 6) {
				$iDiaRecargo3 = $iDiaRecargo3  + 1;
				$dFechaInicioRecargos = getDate(mktime(0,0,0,$aiMesPago,$iDiaRecargo3 - 1,$iAnoVencimiento));
				$iDiaSemana = $dFechaInicioRecargos["wday"];
			}
		}
	}
	//	SI SE TRATA DE PROXIMO PERIODO O EL MES ES POSTERIOR O ES EL MISMO MES Y
	//	(NO HAY RECARGOS POR DIA O TODAVIA NO ES EL DIA DE RECARGOS O EL ALUMNO
	//	 CUENTA CON BECA ESPECIAL (NO RECARGOS EN EL MES))
	IF ($aiPeriodoPago >= $iPeriodoActual){
		IF (fMesRel($aiMesPago) > fMesRel($adFecha["mon"]) OR $aiMesPago == $adFecha["mon"] AND ($iDiaRecargo1 == 0 OR $iDiaRecargo1 > $adFecha["mday"] ) OR $aiPeriodo > $iPeriodoActual) RETURN 0;
		}

	// ls_cant_o_porc:  'P' = Porcentaje fijo
	//						  'C' = Cantidad Fija
	//						  'D' = Porcentaje por D�a aplicando desde el d�a primero para los atrasados
	//						  'E' = (Especial), Cantidad por D�a
	//						  'F' = Porcentaje por D�a aplicando desde dia_recargo_1 para los atrasados
	// CALCULA RECARGOS DEL MISMO MES
	
	$iDiaHoy = $adFecha["mday"];
	// SI EL MES ES MENOR AL DE HOY (Y NO ES RECARGOS POR D�A), ENTONCES li_dia_hoy SE 
	//		CONVIERTE EN EL �LTIMO DEL MES PARA QUE LE COBRE TODOS LOS RECARGOS DEL MES INICIAL
	IF ($aiMesPago < $adFecha["mon"] AND $aiPeriodo <= $iPeriodoActual AND ($cCantOPorc1 == 'P' OR $cCantOPorc1 == 'C')){ ///////
	  	$dFechaFinal = fFechaFinalDelMes( $adFecha["mon"], $adFecha["year"] );
		$iDiaHoy = $dFechaFinal["mday"];
	}
	// RANGO 1 DE RECARGOS O 'D' O 'E'
	IF ($iDiaHoy >= $iDiaRecargo1 AND ($iDiaHoy < $iDiaRecargo2 OR $iDiaRecargo2 == 0) OR ($aiMesPago <> $adFecha["mon"] OR $aiPeriodoPago < $iPeriodoActual)){
		IF ($cCantOPorc1 == 'C'){ //////////
			// CHECA LA SUMA DE LOS RECARgOS DE LOS ABONOS PREVIOS
			$iAbonosPrevios = 0;
			$dRecargosPrevios = 0;
			$dRecargosTotales = 0;
			$sSqlQ = "select count(*) as abonos_previos, sum(recargos) as recargoa_previos from abonos where alumno = ' $alAlumno ' and periodo = ' $aiPeriodo ' and concepto = ' $aiConcepto ' and mes = ' $aiMesPago '";
			$resultP=mysql_query($sSqlQ,$link)or die(mysql_error());
			$rowP = mysql_fetch_array($resultP);
			$iAbonosPrevios=$rowP["abonos_previos"];
			$dRecargosPrevios=$rowP["recargoa_previos"];
			//	si no tiene abonos previos o tiene recargos_previos calcula recargo
			IF ($iAbonosPrevios == 0 OR $dRecargosPrevios > 0) $dRecargosTotales = $dRecargo1 - $dRecargosPrevios;
			return $dRecargo1;
		}
		ELSEIF ($cCantOPorc1 == 'P')
		{
		   $dRecargosTotales = fredondeo($adImporte * $dRecargo1 / 100,$dFactorRedondeo);
		}
		ELSE
		{
			//  importe * % reca     *   dias transcurridos   / (dias del mes - 10)
			$iAnoCargo = $aiPeriodoPago;
			IF ($aiMesPago < $iMesInicialPeriodoActual) $iAnoCargo += 1;
			// LA LINEA EN COMENTARIOS ES TIPO PEREYRA (RECARGOS A PARTIR DEL DIA 10)
			// TIPO PEREYRA QUED� COMO 'F' IGUAL QUE AMERICAN SCHOOL
			// F Y G SON A PARTIR DEL VENCIMIENTO
			IF ($cCantOPorc1 == 'F' OR $cCantOPorc1 == 'G') 
			{
				// OJO: DIA_RECARGO_1 = 31 SIGNIFICA RECARGOS A PARTIR DEL D�A 1ERO DEL SIG. MES
				IF ($iDiaRecargo1 == 31)
				{
					$aiMesPago ++;
					IF ($aiMesPago > 12)
					{
						$aiMesPago = 1;
						$iAnoCargo = $iAnoCargo + 1;
					}
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
				}
				ELSE
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,$iDiaRecargo1,$iAnoCargo)), $adFecha) + 1;
			}
			ELSE
			{
				IF ($iDiaRecargo1 == 31)
				{
					$aiMesPago ++;
					IF ($aiMesPago > 12)
					{
					 	$aiMesPago = 1;
						$iAnoCargo = $iAnoCargo + 1;
					}
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
				}
				ELSE
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
			}
			// D Y F= DIAS TRANSCURRIDOS * RECARGO%
				// IMPORTE * % RECA * DIAS TRANSCURRIDOS DESDE EL $iDiaRecargo1 DEL MES DEL CARGO
				// AL DIA DE HOY
			IF ($cCantOPorc1 == 'D' OR $cCantOPorc1 == 'F')
			{
//			  return $dRecargo1;
				$dRecargosTotales = fRedondeo(floor($adImporte * $dRecargo1 * $iDias ) / 100, $dFactorRedondeo);
			}
			// E Y G = DIAS TRANSCURRIDOS * RECARGO$
				//$ RECA * DIAS TRANSCURRIDOS DESDE EL...
			ELSE
				$dRecargosTotales = fRedondeo($dRecargo1 * $iDias, $dFactorRedondeo);
		}
	}
	// RANGO 2
ELSE
{
	IF ($iDiaHoy >= $iDiaRecargo2 AND ($iDiaHoy < $iDiaRecargo3 OR $iDiaRecargo3 == 0))
 {
	IF ($cCantOPorc2 == 'C')
	{
		// CHECA LA SUMA DE LOS RECARgOS DE LOS ABONOS PREVIOS
		$iAbonosPrevios = 0;
			$dRecargosPrevios = 0;
			$dRecargosTotales = 0;
			$sSqlQ = "select count(*) as abonos_previos, sum(recargos) as recargoa_previos from abonos where alumno = ' $alAlumno ' and periodo = ' $aiPeriodo ' and concepto = ' $aiConcepto ' and mes = ' $aiMesPago '";
			$resultP=mysql_query($sSqlQ,$link)or die(mysql_error());
			$rowP = mysql_fetch_array($resultP);
			$iAbonosPrevios=$rowP["abonos_previos"];
			$dRecargosPrevios=$rowP["recargoa_previos"];
			//	si no tiene abonos previos o tiene recargos_previos calcula recargo
			IF ($iAbonosPrevios == 0 OR $dRecargosPrevios > 0) $dRecargosTotales = $dRecargo2 - $dRecargosPrevios;
			return $dRecargo2;
			
	}
	ELSE
	{
		IF ($cCantOPorc2 == 'P')
		{
			$dRecargosTotales = fredondeo($adImporte * $dRecargo2 / 100,$dFactorRedondeo);    ///////////////////////////////////
		}
		ELSE
		{
			//  importe * % reca     *   dias transcurridos   / (dias del mes - 10)
			$iAnoCargo = $aiPeriodoPago;
			IF ($aiMesPago < $iMesInicialPeriodoActual) $iAnoCargo += 1;
		
		// LA LINEA EN COMENTARIOS ES TIPO PEREYRA (RECARGOS A PARTIR DEL DIA 10)
		// TIPO PEREYRA QUED� COMO 'F' IGUAL QUE AMERICAN SCHOOL
		// F Y G SON A PARTIR DEL VENCIMIENTO
		}
		IF ($cCantOPorc2 == 'F' OR $cCantOPorc2 == 'G' OR $cCantOPorc2 == 'S')
		{
		// OJO: DIA_RECARGO_2 = 31 SIGNIFICA RECARGOS A PARTIR DEL D�A 1ERO DEL SIG. MES
			IF ($iDiaRecargo2 == 31)
			{
				$aiMesPago ++;
				IF ($aiMesPago > 12 ){
					$aiMesPago = 1;
					$iAnoCargo = $iAnoCargo + 1;
				}
				$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
			}
			ELSE
			{			
				$iDiaRecargo2original = $iDiaRecargo2;			
				IF ($cRecargosSabDom == 'V')
						$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,$iDiaRecargo2original,$iAnoCargo)), $adFecha) + 1;									
				ELSE
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,$iDiaRecargo2,$iAnoCargo)), $adFecha) + 1;	
			}																		
		}
		ELSE
		{
			IF ($iDiaRecargo2 == 31)
			{
				$aiMesPago ++;
				IF ($aiMesPago > 12)
				{
					$aiMesPago = 1;
					$iAnoCargo = $iAnoCargo + 1;
				}
				$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
			}
			ELSE
			{
				$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
			}
		}
		// D Y F= DIAS TRANSCURRIDOS * RECARGO%
		IF ($cCantOPorc2 == 'D' OR $cCantOPorc2 == 'F')
		{
			// IMPORTE * % RECA * DIAS TRANSCURRIDOS DESDE EL li_dia_recargo_2 DEL MES DEL CARGO
			// AL DIA DE HOY
			// OJO: REDONDEA PRIMERO LOS RECARGOS POR D�A PORQUE AS� SE HACE EN LOS BANCOS.
			$dRecargosTotales = fRedondeo(floor($adImporte * $dRecargo2 ) / 100 * $iDias, $dFactorRedondeo);
		// E Y G = DIAS TRANSCURRIDOS * RECARGO$
		}
		ELSE
		{
			IF ($cCantOPorc2 == 'E' OR $cCantOPorc2 == 'G')
			{
			//$ RECA * DIAS TRANSCURRIDOS DESDE EL...
			$dRecargosTotales = fRedondeo($dRecargo2 * $iDias, $dFactorRedondeo);			
			}
			ELSE
			{
			//$ RECA * SEMANAS TRANSCURRIDAS DESDE EL...
			$dRecargosTotales = fRedondeo($dRecargo2 * ($iDias+6)/7, $dFactorRedondeo);			
			}
	 	}
// RANGO 3
}}

ELSE
{
	IF ($cCantOPorc3 == 'C')
	{
		// CHECA LA SUMA DE LOS RECARgOS DE LOS ABONOS PREVIOS
		$iAbonosPrevios = 0;
		$dRecargosPrevios = 0;
		$dRecargosTotales = 0;
		$sSqlQ = "select count(*) as abonos_previos, sum(recargos) as recargoa_previos from abonos where alumno = ' $alAlumno ' and periodo = ' $aiPeriodo ' and concepto = ' $aiConcepto ' and mes = ' $aiMesPago '";
			$resultP=mysql_query($sSqlQ,$link)or die(mysql_error());
			$rowP = mysql_fetch_array($resultP);
			$iAbonosPrevios=$rowP["abonos_previos"];
			$dRecargosPrevios=$rowP["recargoa_previos"];
			//	si no tiene abonos previos o tiene recargos_previos calcula recargo
			IF ($iAbonosPrevios == 0 OR $dRecargosPrevios > 0) 
			$dRecargosTotales = $dRecargo3 - $dRecargosPrevios;
	}
	ELSE
	{
		IF ($cCantOPorc3 == 'P')
			$dRecargosTotales = fRedondeo(adImporte + $dRecargo3/100 , $dFactorRedondeo);					
		ELSE
		{
			//  importe * % reca     *   dias transcurridos   / (dias del mes - 10)
			$iAnoCargo = $aiPeriodoPago;
			IF ($aiMesPago < $iMesInicialPeriodoActual)
			{
				$iAnoCargo += 1;
			}
		// LA LINEA EN COMENTARIOS ES TIPO PEREYRA (RECARGOS A PARTIR DEL DIA 10)
		// TIPO PEREYRA QUED� COMO 'F' IGUAL QUE AMERICAN SCHOOL
		// F Y G SON A PARTIR DEL VENCIMIENTO
			IF ($cCantOPorc3 == 'F' OR $cCantOPorc3 == 'G' OR $cCantOPorc3 == 'S')
			{
			// OJO: DIA_RECARGO_3 = 31 SIGNIFICA RECARGOS A PARTIR DEL D�A 1ERO DEL SIG. MES
				IF ($dRecargo3 == 31)
				{
					$aiMesPago ++;
					IF ($aiMesPago > 12)
					{
						$aiMesPago = 1;
						$iAnoCargo = $iAnoCargo + 1;
					}
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,$iAnoCargo)), $adFecha) + 1;
				}ELSE
				{
					$iDiaRecargo3original = $iDiaRecargo3;			
					IF ($cRecargosSabDom == 'V')
						$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,$iDiaRecargo3original,$iAnoCargo)), $adFecha) + 1;									
					ELSE
						$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,$iDiaRecargo3,$iAnoCargo)), $adFecha) + 1;				
				}
			}ELSE
			{
				IF ($iDiaRecargo3 == 31)
				{
					$aiMesPago ++;
					IF ($aiMesPago > 12)
					{
						$aiMesPago = 1;
						$iAnoCargo = $iAnoCargo + 1;
					}
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
				}ELSE
				{
					$iDias = fDiasEntre(getdate(mktime(0,0,0,$aiMesPago,1,$iAnoCargo)), $adFecha) + 1;
				}
			}
			// D Y F= DIAS TRANSCURRIDOS * RECARGO%
			IF ($cCantOPorc3 == 'D' OR $cCantOPorc3 == 'F')
			{
				// IMPORTE * % RECA * DIAS TRANSCURRIDOS DESDE EL li_dia_recargo_3 DEL MES DEL CARGO
				// AL DIA DE HOY
				// OJO: REDONDEA PRIMERO LOS RECARGOS POR D�A PORQUE AS� SE HACE EN LOS BANCOS.
				$dRecargosTotales = fRedondeo(floor($adImporte * $dRecargo3 ) / 100 * $iDias, $dFactorRedondeo);
			// E Y G = DIAS TRANSCURRIDOS * RECARGO$
			}
			ELSE
			{ 
				IF ($cCantOPorc3 == 'E' OR $cCantOPorc3 == 'G')
				{
					//$ RECA * DIAS TRANSCURRIDOS DESDE EL...
					$dRecargosTotales = fRedondeo($dRecargo3 * $iDias, $dFactorRedondeo);
				}
				ELSE
				{
					//$ RECA * SEMANAS TRANSCURRIDAS DESDE EL...
					$dRecargosTotales = fRedondeo($dRecargo3 * ($iDias+6)/7, $dFactorRedondeo);
				}
			}
		}
		}		
			// SI NO SON POR D�A, ACUMULA ADEM�S LOS RECARGOS X MES
	IF	(! ($cCantOPorc1 == 'D' OR $cCantOPorc1 == 'E' OR $cCantOPorc1 == 'F' OR $cCantOPorc1 == 'G' OR	$cCantOPorc2 == 'D' OR $cCantOPorc2 == 'E' OR $cCantOPorc2 == 'F' OR $cCantOPorc2 == 'G' OR $cCantOPorc3 == 'D' OR $cCantOPorc3 == 'E' OR $cCantOPorc3 == 'F' OR $cCantOPorc3 == 'G' ))
	{
		$iMesesAnteriores = 0;
		$iMeses = 0;
		IF ($aiPeriodoPago < $iPeriodoActual)
		{
			// NUMERO DE MESES TRANSCURRIDOS DESDE EL INICIO DEL PERIODO DEL CARGO
			$iMesesAnteriores = $aiMesPago - $iMesInicialPeriodoActual;
			IF ($iMesesAnteriores < 0)
			{
				$iMesesAnteriores = $iMesesAnteriores + 12;
			}
			$iMesesAnteriores = (12 * ($iPeriodoActual - $aiPeriodoPago )) - $iMesesAnteriores;
		}
		IF ($aiPeriodoPago < $iPeriodoActual)
			$iMesesActual = MONTH($adFecha) - $iMesInicialPeriodoActual;
		ELSE
			$iMesesActual = MONTH($adfecha) - $aiMesPago;	
		IF ($iMesesActual < 0 )
		{
			$iMesesActual = $iMesesActual + 12;
		}	
		$iMesesActual ++;
		// MES COMPLETO = 'N' CALCULA A PARTIR DEL D�A EN QUE VENCE EL CARGO
		//					 = 'S' CALCULA A PARTIR DEL D�A 1ERO
		//					 = 'F' CALCULA DESPU�S DE FIN DE MES
		//				OJO: CUANDO ES FIN DE MES, CALCULA LOS RECARGOS MULTIPLICANDO POR
		//					EL N�MERO DE MESES, EN 'N' Y 'S', LOS CALCULA COMO LA SUMA
		//					DE LOS DEL MES M�S LOS DE LOS OTROS MESES
		IF ($cMesCompleto =='N')
		{
			IF (DAY($adfecha) < $iDiaRecargo1);
			{
				$iMesesActual = $iMesesActual - 1;
			}
		}
		ELSE
		{
			IF ($cMesCompleto == 'F')
			{
				$iMesesActual = $iMesesActual - 1;
			}
			$iMeses = $iMesesAnteriores + $iMesesActual;
		// mmm, PERO COMO EL 1ER MES YA NO CUENTA PORQUE AHORA SE SUMAN LOS RECARGOS CALCULADOS
		//		DEL PRIMER MES -> SE RESTA 1
		//		MMMM, SI ES HASTA FIN DE MES SIEMPRE SI CUENTA ;)
		}
		IF ($cMesCompleto <> 'F')
			$iMeses = $iMeses - 1;	
			// POR SI LAS DUDAS. PARA NO REVISARLO TODO
		IF ($iMeses < 0)
			$iMeses = 0;
		// SI NO SE ACUMULAN LOS RECARGOS POR MES -> S�LO APLICA UN MES
		IF ($cAcumularMes == 'N' AND $iMeses > 1)
			$iMeses = 1;
		// SI SE ACUMULAN S�LO 'lc_acumular_mes' MESES, ENTONCES ESE ES EL M�XIMO DE MESES QUE
		//		SE PUEDEN COBRAR
		ELSE
		{
			IF ($cAcumularMes <> 0 )
			{
				$iMeses = MIN(iMeses, INTEGER($cAcumularMes));//////////////////////////
			}
		$recargos = 0;
		}
		IF ($cCantOPorcMes == 'C' )
			$recargos = $iMeses * $dRecargoMes;
		ELSE
		{
			$recargos = $iMeses * $dRecargoMes * $adImporte / 100;
		}	
		//  * RETURN INT(ld_recargos / Paramet->FctRed + .5) * Paramet->FctRed
		// SI ES HASTA FIN DE MES -> NO SE INCLUYEN LOS DEL 1ER MES
		IF ($cMesCompleto <> 'F' )
			$dRecargosTotales = $dRecargosTotales + fredondeo($recargos, $dFactorRedondeo);
		ELSE
		{
			IF ($recargos > 0 )
				$dRecargosTotales = f_redondeo($recargos, gd_factor_redondeo);		
		}	
	}
}}
RETURN $dRecargosTotales;
}

function fMesRel($aiMes)
{
  	include('connection.php');
  	$linkFMesRel = mysql_connect($server, $userName, $password);
	mySql_select_db($DB)or die("No se pudo seleccionar DB");
	if (!$linkFMesRel) die('No se pudo conectar: ' . mysql_error());
	
	// LEE DATOS DE PAR�METROS
	$sSqlQ = "select mes_relativo from mes_relativo where mes = '$aiMes'";
	$resultP=mysql_query($sSqlQ,$linkFMesRel)or die(mysql_error());
	$rowP = mysql_fetch_array($resultP);
	$iMesRelativo=$rowP["mes_relativo"];
 //	mysql_close($linkFMesRel);
  
	return $iMesRelativo;
}

function fRedondeo($adImporte, $adFactor)
{
RETURN IntVal($adImporte / $adFactor + .5, 0) * $adFactor;
}

function fFechaFinalDelMes($aiMes, $aiAno)
{
	if ($aiMes == 12)
	{
		$aiAno ++;
		$aiMes = 1;
	}
	ELSE
	{
		$aiMes ++;
	}

RETURN getDate(mkTime(0,0,0,$aiMes, 0, $aiAno ));
}

function fDiasEntre($adInicial, $adFinal)
{
  
RETURN IntVal((mktime(0,0,0,$adFinal["mon"],$adFinal["mday"],$adFinal["year"]) - mktime(0,0,0,$adInicial["mon"],$adInicial["mday"],$adInicial["year"])) / 86400 + .5, 0);
}

///////////////


function retAround($cual,$origen,$arrayAlumnos,$rowCounter,$ultimo) //**
{
 	
	$temp1=substr($origen,2,1);
	if($temp1=="A") 	$tempO=substr($origen,0,2);
	else $tempO=substr($origen,0,3);
	if($cual==3)
	{
		if ($rowCounter==0)
		{
			return("noexiste");
		}
		else
		{
		 	$tempArriba=$arrayAlumnos[$rowCounter-1];
			return("$tempO"."$tempArriba");
		}
	}
	
		if($cual==4)
	{
		if ($rowCounter==($ultimo-1))
		{
			return("noexiste");
		}
		else
		{
		 	$tempAbajo=$arrayAlumnos[$rowCounter+1];
			return("$tempO"."$tempAbajo");
		}
	}
	
}


function returnQuery($nivelUsuario,$necesito,$selectedSeccion,$selectedGrado,$selectedGrupo,$seccionU,$gradoU,$grupoU) 
{
  
  $clave = $_SESSION['clave'];
$ciclo=$_SESSION['ciclo'];
//$materia="";

/*if ($necesito=="materia")
{
  	include('../connection.php');
	$sqlQ = "select materia from asignacion_materias where ciclo=$ciclo and grado=$selectedGrado and grupo='$selectedGrupo' and seccion='$selectedSeccion'";
//echo $sqlQ;

	$link = mysql_connect($server, $userName, $password);
	mySql_select_db($DB)or die("No se pudo seleccionar DB");
	if (!$link) {
   die('Could not connect: ' . mysql_error());
	}


	$result=mysql_query($sqlQ,$link)or die(mysql_error());
	$row = mysql_fetch_array($result);
	$materia=$row["materia"];
}*/




	switch ($nivelUsuario): 
case 1:
$sqlQSeccion="select seccion,nombre,calificacion_maxima,calif_minima_boleta from secciones where ciclo=$ciclo";
$sqlQGrado= "select distinct(grado) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion'";
$sqlQGrupo ="select distinct(grupo) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$selectedGrado" ;
$sqlQPeriodos="select * from periodos where ciclo=$ciclo and  seccion='$selectedSeccion'" ;
$sqlQMaterias = "select max(nombre) as nombre, max(asistencia) as asistencia, max(tareas) as tareas, materias_ciclos.materia,max(evalua_apreciativa) as evalua_apreciativa, conducta from materias_ciclos, asignacion_materias where materias_ciclos.ciclo=$ciclo and materias_ciclos.seccion='$selectedSeccion'  and materias_ciclos.seccion = asignacion_materias.seccion and materias_ciclos.ciclo = asignacion_materias.ciclo and materias_ciclos.materia = asignacion_materias.materia and asignacion_materias.grado=$selectedGrado and asignacion_materias.grupo='$selectedGrupo' and apreciativa='N'group by materias_ciclos.materia";
//$sqlQMaterias = "select nombre, asistencia, tareas, materia,evalua_apreciativa from materias_ciclos where ciclo=$ciclo and seccion='$selectedSeccion'  and materia in (select materia from asignacion_materias where ciclo=$ciclo and grado=$selectedGrado and grupo='$selectedGrupo' and seccion='$selectedSeccion') and apreciativa='N'";
//echo "$sqlQMaterias";
break;

case 2:
$sqlQSeccion="select seccion,nombre,calificacion_maxima,calif_minima_boleta from secciones where ciclo=$ciclo and seccion='$seccionU'";
$sqlQGrado= "select distinct(grado) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion'";
$sqlQGrupo ="select distinct(grupo) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$selectedGrado" ;
$sqlQPeriodos="select * from periodos where ciclo=$ciclo and  seccion='$selectedSeccion'" ;
$sqlQMaterias = "select max(nombre) as nombre, max(asistencia) as asistencia, max(tareas) as tareas, materias_ciclos.materia,max(evalua_apreciativa) as evalua_apreciativa, conducta from materias_ciclos, asignacion_materias where materias_ciclos.ciclo=$ciclo and materias_ciclos.seccion='$selectedSeccion'  and materias_ciclos.seccion = asignacion_materias.seccion and materias_ciclos.ciclo = asignacion_materias.ciclo and materias_ciclos.materia = asignacion_materias.materia and asignacion_materias.grado=$selectedGrado and asignacion_materias.grupo='$selectedGrupo' and apreciativa='N'group by materias_ciclos.materia";
//$sqlQMaterias = "select nombre, asistencia, tareas, materia,evalua_apreciativa from materias_ciclos where ciclo=$ciclo and seccion='$selectedSeccion' and materia in (select materia from asignacion_materias where ciclo=$ciclo and grado=$selectedGrado and grupo='$selectedGrupo' and seccion='$selectedSeccion') and apreciativa='N'";

    break;
case 3:
$sqlQSeccion="select seccion,nombre,calificacion_maxima,calif_minima_boleta from secciones where ciclo=$ciclo and seccion='$seccionU'";
$sqlQGrado= "select distinct(grado) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$gradoU";
$sqlQGrupo ="select distinct(grupo) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$selectedGrado" ;
$sqlQPeriodos="select * from periodos where ciclo=$ciclo and  seccion='$selectedSeccion'" ;
$sqlQMaterias = "select max(nombre) as nombre, max(asistencia) as asistencia, max(tareas) as tareas, materias_ciclos.materia,max(evalua_apreciativa) as evalua_apreciativa, conducta from materias_ciclos, asignacion_materias where materias_ciclos.ciclo=$ciclo and materias_ciclos.seccion='$selectedSeccion'  and materias_ciclos.seccion = asignacion_materias.seccion and materias_ciclos.ciclo = asignacion_materias.ciclo and materias_ciclos.materia = asignacion_materias.materia and asignacion_materias.grado=$selectedGrado and asignacion_materias.grupo='$selectedGrupo' and apreciativa='N'group by materias_ciclos.materia";
//$sqlQMaterias = "select nombre, asistencia, tareas, materia,evalua_apreciativa from materias_ciclos where ciclo=$ciclo and seccion='$selectedSeccion' and materia in (select materia from asignacion_materias where ciclo=$ciclo and grado=$selectedGrado and grupo='$selectedGrupo' and seccion='$selectedSeccion') and apreciativa='N'";
//echo $sqlQMaterias;
    break;
case 4:
$sqlQSeccion="select seccion,nombre,calificacion_maxima,calif_minima_boleta from secciones where ciclo=$ciclo and seccion='$seccionU'";
$sqlQGrado= "select distinct(grado) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$gradoU";
$sqlQGrupo ="select distinct(grupo) from  asignacion_materias where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$selectedGrado and grupo='$grupoU'" ;
$sqlQPeriodos="select * from periodos where ciclo=$ciclo and  seccion='$selectedSeccion'" ;
//$sqlQMaterias = "select nombre, asistencia, tareas, materia,evalua_apreciativa from materias_ciclos where ciclo=$ciclo and seccion='$selectedSeccion' and materia in (select materia from asignacion_materias where ciclo=$ciclo and grado=$selectedGrado and grupo='$selectedGrupo' and seccion='$selectedSeccion') and apreciativa='N'";
$sqlQMaterias = "select max(nombre) as nombre, max(asistencia) as asistencia, max(tareas) as tareas, materias_ciclos.materia,max(evalua_apreciativa) as evalua_apreciativa, conducta from materias_ciclos, asignacion_materias where materias_ciclos.ciclo=$ciclo and materias_ciclos.seccion='$selectedSeccion'  and materias_ciclos.seccion = asignacion_materias.seccion and materias_ciclos.ciclo = asignacion_materias.ciclo and materias_ciclos.materia = asignacion_materias.materia and asignacion_materias.grado=$selectedGrado and asignacion_materias.grupo='$selectedGrupo' and apreciativa='N'group by materias_ciclos.materia";

    break;
case 5:
$sqlQSeccion="select secciones.seccion,max(secciones.nombre) as nombre, max(secciones.calificacion_maxima) as calificacion_maxima, max(secciones.calif_minima_boleta) as calif_minima_boleta from secciones, asignacion_materias where secciones.seccion = asignacion_materias.seccion and secciones.ciclo = asignacion_materias.ciclo and asignacion_materias.ciclo=$ciclo and profesor=$clave group by secciones.seccion";

$sqlQGrado= "select distinct(grado) from  asignacion_materias where ciclo=$ciclo and profesor=$clave and seccion='$selectedSeccion'";
$sqlQGrupo ="select distinct(grupo) from  asignacion_materias where ciclo=$ciclo and profesor=$clave and seccion='$selectedSeccion' and grado=$selectedGrado" ;
$sqlQPeriodos="select * from periodos where ciclo=$ciclo and  seccion='$selectedSeccion'" ;
//$sqlQMaterias = "select nombre, asistencia, tareas, materia,evalua_apreciativa from materias_ciclos where ciclo=$ciclo and seccion='$selectedSeccion' and materia in (select materia from asignacion_materias where ciclo=$ciclo and profesor=$clave and grado=$selectedGrado and grupo='$selectedGrupo' and seccion='$selectedSeccion')and apreciativa='N'";
$sqlQMaterias = "select max(nombre) as nombre, max(asistencia) as asistencia, max(tareas) as tareas, materias_ciclos.materia,max(evalua_apreciativa) as evalua_apreciativa, conducta from materias_ciclos, asignacion_materias where materias_ciclos.ciclo=$ciclo and materias_ciclos.seccion='$selectedSeccion'  and materias_ciclos.seccion = asignacion_materias.seccion and materias_ciclos.ciclo = asignacion_materias.ciclo and materias_ciclos.materia = asignacion_materias.materia and asignacion_materias.profesor=$clave and asignacion_materias.grado=$selectedGrado and asignacion_materias.grupo='$selectedGrupo' and apreciativa='N'group by materias_ciclos.materia";

    break;
case 6:
	 die("Usuario sin permisos de capturar");	

    break;

case 11:
$sqlQSeccion="select seccion,nombre from secciones where ciclo=$ciclo and seccion in(select seccion from alumnos where activo='A')";
$sqlQGrado= "select distinct(grado) from  grados where ciclo=$ciclo and seccion='$selectedSeccion' and grado in(select grado from alumnos where activo='A' and seccion='$selectedSeccion')";
$sqlQGrupo ="select distinct(grupo) from  grupos where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$selectedGrado and grupo in(select grupo from alumnos where activo='A' and seccion='$selectedSeccion' and grado=$selectedGrado)" ;
$filtro_preceptor="";
if($selectedSeccion!="") $filtro_preceptor.=" and seccion='$selectedSeccion' ";
if($selectedGrado>0)  $filtro_preceptor.=" and grado=$selectedGrado ";
if($selectedGrupo!="")  $filtro_preceptor.=" and grupo='$selectedGrupo' ";
$sqlQPreceptor="select * from personal where empleado in( select preceptor from alumnos where activo='A' $filtro_preceptor )";
break;

case 12:
$sqlQSeccion="select seccion,nombre from secciones where ciclo=$ciclo and seccion in(select seccion from alumnos where activo='A' and seccion='$seccionU')";
$sqlQGrado= "select distinct(grado) from  grados where ciclo=$ciclo and seccion='$selectedSeccion' and grado in(select grado from alumnos where activo='A' and seccion='$selectedSeccion')";
$sqlQGrupo ="select distinct(grupo) from  grupos where ciclo=$ciclo and seccion='$selectedSeccion' and grado=$selectedGrado and grupo in(select grupo from alumnos where activo='A' and seccion='$selectedSeccion' and grado=$selectedGrado)" ;
$filtro_preceptor="";
if($selectedSeccion!="") $filtro_preceptor.=" and seccion='$selectedSeccion' ";
if($selectedGrado>0)  $filtro_preceptor.=" and grado=$selectedGrado ";
if($selectedGrupo!="")  $filtro_preceptor.=" and grado='$selectedGrado' ";
$sqlQPreceptor="select * from personal where empleado in( select preceptor from alumnos where activo='A' $filtro_preceptor ) order by apellido_paterno, apellido_materno,nombre";
break;

default:
		 die("Usuario sin permisos definidos");

endswitch; 


switch ($necesito): 
case "seccion":
	return($sqlQSeccion);
    break;
case "grado":
	return($sqlQGrado);
    break;
case "grupo":
	return($sqlQGrupo);
    break;
case "periodo":
	return($sqlQPeriodos);
    break;
case "materia":
	return($sqlQMaterias);
    break;
case "preceptor":
	return($sqlQPreceptor);
    break;
default:

endswitch; 



} 

function formatDate ($fechaAnterior) 
{

  	$fechaTemp=substr($fechaAnterior,0,10);
	$yr=substr($fechaTemp,0,4);
	$mo=substr($fechaTemp,5,2);
	$dy=substr($fechaTemp,8,2);
	$fechaNueva="$dy/$mo/$yr";
	return $fechaNueva;

} 

function f_encripta ($clave, $password, $encripta_desencripta) 
{
	$iSumandos = fMod($clave * 7, 3);

	switch ($iSumandos):
	case 0:
		$iSumando[1] = -3;
		$iSumando[2] = -5;
		$iSumando[3] = -7;
		$iSumando[4] = 11;
		$iSumando[5] = 13;
		$iSumando[6] = 17;
		break;
	case 1:
		$iSumando[1] = 13;
		$iSumando[2] = -7;
		$iSumando[3] = 17;
		$iSumando[4] = -3;
		$iSumando[5] = 11;
		$iSumando[6] = -5;
		break;
	case 2:
		$iSumando[1] = -7;
		$iSumando[2] = 13;
		$iSumando[3] = -3;
		$iSumando[4] = 17;
		$iSumando[5] = -5;
		$iSumando[6] = 11;
		break;
	endswitch; 

	if ($encripta_desencripta == "D")
		{
		echo $encripta_desencripta;
		for ($iSumandos = 1; $iSumandos <= 6; $iSumandos++ )
		{
			$iSumando[$iSumandos] = - $iSumando[$iSumandos];
		} 
		} 

	$sEncriptado = '';
	$iSumandos = 1;
	do
	{
		$sEncriptado = $sEncriptado . chr(ord($password) + $iSumando[$iSumandos]);
		$iSumandos = $iSumandos + 1;
		if ($iSumandos > 6)	$iSumandos = 1;
		$password = subStr($password, 1);
	} 
	while (strLen($password) > 0) ;
	RETURN $sEncriptado;

} 

function fechaInsertar($fechaAnterior){ //**
	$fechaTemp=substr($fechaAnterior,0,10);
	$yr=substr($fechaTemp,6,4);
	$mo=substr($fechaTemp,3,2);
	$dy=substr($fechaTemp,0,2);
	$fechaNueva="$dy/$mo/$yr";
	return $fechaNueva;
}



function actualizaEstadistico($tipo_usuario,$usuario,$ciclo,$mes,$fun){
  	include('connection.php');
  	$field="";
  	if ($fun==1)
  	{
	    $field="total_accesos";
	}
	else
	{
	  $field="total_modificaciones";
	}
  	
  	
  	$link = mysql_connect($server, $userName, $password);
	mySql_select_db($DB)or die("No se pudo seleccionar DB");
	if (!$link) {
   		die('No se pudo conectar: ' . mysql_error());
		}
	
	$sqlQ = "select * from estadistico_web where usuario=$usuario";

	$resultU=mysql_query($sqlQ,$link)or die(mysql_error());

	if (mysql_affected_rows($link)<=0 )
	{
	$sqlQ2="INSERT INTO estadistico_web( tipo_usuario,usuario,ciclo,mes,$field)  VALUES  ( '$tipo_usuario',$usuario,$ciclo,$mes,1) " ;	
  	}
	else
	{
	  $rowU = mysql_fetch_array($resultU);
	  $count=$rowU[$field]+1;
	  $sqlQ2="UPDATE estadistico_web SET  $field = $count WHERE  usuario=$usuario";
    }
   // echo $sqlQ2;
	mysql_query($sqlQ2,$link)or die(mysql_error());
 	//mysql_close($link);
  	//die("No existe en usuarios");	
}
 
 ?> 
 

<html>
<script language="javascript">
<!--
var MONTH_NAMES=new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');var DAY_NAMES=new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sun','Mon','Tue','Wed','Thu','Fri','Sat');
function LZ(x){return(x<0||x>9?"":"0")+x}
function isDate(val,format){var date=getDateFromFormat(val,format);if(date==0){return false;}return true;}
function compareDates(date1,dateformat1,date2,dateformat2){var d1=getDateFromFormat(date1,dateformat1);var d2=getDateFromFormat(date2,dateformat2);if(d1==0 || d2==0){return -1;}else if(d1 > d2){return 1;}return 0;}
function formatDate(date,format){format=format+"";var result="";var i_format=0;var c="";var token="";var y=date.getYear()+"";var M=date.getMonth()+1;var d=date.getDate();var E=date.getDay();var H=date.getHours();var m=date.getMinutes();var s=date.getSeconds();var yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,H,KK,K,kk,k;var value=new Object();if(y.length < 4){y=""+(y-0+1900);}value["y"]=""+y;value["yyyy"]=y;value["yy"]=y.substring(2,4);value["M"]=M;value["MM"]=LZ(M);value["MMM"]=MONTH_NAMES[M-1];value["NNN"]=MONTH_NAMES[M+11];value["d"]=d;value["dd"]=LZ(d);value["E"]=DAY_NAMES[E+7];value["EE"]=DAY_NAMES[E];value["H"]=H;value["HH"]=LZ(H);if(H==0){value["h"]=12;}else if(H>12){value["h"]=H-12;}else{value["h"]=H;}value["hh"]=LZ(value["h"]);if(H>11){value["K"]=H-12;}else{value["K"]=H;}value["k"]=H+1;value["KK"]=LZ(value["K"]);value["kk"]=LZ(value["k"]);if(H > 11){value["a"]="PM";}else{value["a"]="AM";}value["m"]=m;value["mm"]=LZ(m);value["s"]=s;value["ss"]=LZ(s);while(i_format < format.length){c=format.charAt(i_format);token="";while((format.charAt(i_format)==c) &&(i_format < format.length)){token += format.charAt(i_format++);}if(value[token] != null){result=result + value[token];}else{result=result + token;}}return result;}
function _isInteger(val){var digits="1234567890";for(var i=0;i < val.length;i++){if(digits.indexOf(val.charAt(i))==-1){return false;}}return true;}
function _getInt(str,i,minlength,maxlength){for(var x=maxlength;x>=minlength;x--){var token=str.substring(i,i+x);if(token.length < minlength){return null;}if(_isInteger(token)){return token;}}return null;}
function getDateFromFormat(val,format){val=val+"";format=format+"";var i_val=0;var i_format=0;var c="";var token="";var token2="";var x,y;var now=new Date();var year=now.getYear();var month=now.getMonth()+1;var date=1;var hh=now.getHours();var mm=now.getMinutes();var ss=now.getSeconds();var ampm="";while(i_format < format.length){c=format.charAt(i_format);token="";while((format.charAt(i_format)==c) &&(i_format < format.length)){token += format.charAt(i_format++);}if(token=="yyyy" || token=="yy" || token=="y"){if(token=="yyyy"){x=4;y=4;}if(token=="yy"){x=2;y=2;}if(token=="y"){x=2;y=4;}year=_getInt(val,i_val,x,y);if(year==null){return 0;}i_val += year.length;if(year.length==2){if(year > 70){year=1900+(year-0);}else{year=2000+(year-0);}}}else if(token=="MMM"||token=="NNN"){month=0;for(var i=0;i<MONTH_NAMES.length;i++){var month_name=MONTH_NAMES[i];if(val.substring(i_val,i_val+month_name.length).toLowerCase()==month_name.toLowerCase()){if(token=="MMM"||(token=="NNN"&&i>11)){month=i+1;if(month>12){month -= 12;}i_val += month_name.length;break;}}}if((month < 1)||(month>12)){return 0;}}else if(token=="EE"||token=="E"){for(var i=0;i<DAY_NAMES.length;i++){var day_name=DAY_NAMES[i];if(val.substring(i_val,i_val+day_name.length).toLowerCase()==day_name.toLowerCase()){i_val += day_name.length;break;}}}else if(token=="MM"||token=="M"){month=_getInt(val,i_val,token.length,2);if(month==null||(month<1)||(month>12)){return 0;}i_val+=month.length;}else if(token=="dd"||token=="d"){date=_getInt(val,i_val,token.length,2);if(date==null||(date<1)||(date>31)){return 0;}i_val+=date.length;}else if(token=="hh"||token=="h"){hh=_getInt(val,i_val,token.length,2);if(hh==null||(hh<1)||(hh>12)){return 0;}i_val+=hh.length;}else if(token=="HH"||token=="H"){hh=_getInt(val,i_val,token.length,2);if(hh==null||(hh<0)||(hh>23)){return 0;}i_val+=hh.length;}else if(token=="KK"||token=="K"){hh=_getInt(val,i_val,token.length,2);if(hh==null||(hh<0)||(hh>11)){return 0;}i_val+=hh.length;}else if(token=="kk"||token=="k"){hh=_getInt(val,i_val,token.length,2);if(hh==null||(hh<1)||(hh>24)){return 0;}i_val+=hh.length;hh--;}else if(token=="mm"||token=="m"){mm=_getInt(val,i_val,token.length,2);if(mm==null||(mm<0)||(mm>59)){return 0;}i_val+=mm.length;}else if(token=="ss"||token=="s"){ss=_getInt(val,i_val,token.length,2);if(ss==null||(ss<0)||(ss>59)){return 0;}i_val+=ss.length;}else if(token=="a"){if(val.substring(i_val,i_val+2).toLowerCase()=="am"){ampm="AM";}else if(val.substring(i_val,i_val+2).toLowerCase()=="pm"){ampm="PM";}else{return 0;}i_val+=2;}else{if(val.substring(i_val,i_val+token.length)!=token){return 0;}else{i_val+=token.length;}}}if(i_val != val.length){return 0;}if(month==2){if( ((year%4==0)&&(year%100 != 0) ) ||(year%400==0) ){if(date > 29){return 0;}}else{if(date > 28){return 0;}}}if((month==4)||(month==6)||(month==9)||(month==11)){if(date > 30){return 0;}}if(hh<12 && ampm=="PM"){hh=hh-0+12;}else if(hh>11 && ampm=="AM"){hh-=12;}var newdate=new Date(year,month-1,date,hh,mm,ss);return newdate.getTime();}
function parseDate(val){var preferEuro=(arguments.length==2)?arguments[1]:false;generalFormats=new Array('y-M-d','MMM d, y','MMM d,y','y-MMM-d','d-MMM-y','MMM d');monthFirst=new Array('M/d/y','M-d-y','M.d.y','MMM-d','M/d','M-d');dateFirst =new Array('d/M/y','d-M-y','d.M.y','d-MMM','d/M','d-M');var checkList=new Array('generalFormats',preferEuro?'dateFirst':'monthFirst',preferEuro?'monthFirst':'dateFirst');var d=null;for(var i=0;i<checkList.length;i++){var l=window[checkList[i]];for(var j=0;j<l.length;j++){d=getDateFromFormat(val,l[j]);if(d!=0){return new Date(d);}}}return null;}




function checaDiaMes(campo,format) {
var val=document.all(campo).value;
// alert(val);
 //alert(format);
	var date=getDateFromFormat(val,format);
	if (date==0) { return 0; }
	return 9999;
	}





 function writeDate (objeto){
   strDate=document.all(objeto).value;
   
   letras= strDate.length;
   
   var tecla;
   tecla=String.fromCharCode(event.keyCode);
   
   switch (letras)
   {
   case 0: 
   case 1:
   case 3:
   case 4:
   case 6:
   case 7:
   case 8:
   case 9:
   case 10:
   		//	alert(letras);
   			 if (isNaN(tecla) && ((event.keyCode) != 8)&& ((event.keyCode) != 37)&& 
((event.keyCode) != 39)&& ((event.keyCode) != 46)&& ((event.keyCode) != 
97)&& ((event.keyCode) != 98)&& ((event.keyCode) != 99)&& ((event.keyCode) 
!= 100)&& ((event.keyCode) != 101)&& ((event.keyCode) != 102)&& 
((event.keyCode) != 103)&& ((event.keyCode) != 104)&& ((event.keyCode) != 
105)&& ((event.keyCode) != 96))

   		{
     
    	 event.returnValue=false;
		}
		break;
   case 2:
   case 5:
        
	if (isNaN(tecla) && ((event.keyCode) != 8)&& ((event.keyCode) != 37)&& 
((event.keyCode) != 39)&& ((event.keyCode) != 46)&& ((event.keyCode) != 
97)&& ((event.keyCode) != 98)&& ((event.keyCode) != 99)&& ((event.keyCode) 
!= 100)&& ((event.keyCode) != 101)&& ((event.keyCode) != 102)&& 
((event.keyCode) != 103)&& ((event.keyCode) != 104)&& ((event.keyCode) != 
105)&& ((event.keyCode) != 96))

   		{
      
    	 event.returnValue=false;
		}
		else
		{
		 if (((event.keyCode) != 8)&& ((event.keyCode) != 37)&& 
((event.keyCode) != 39)&& ((event.keyCode) != 46))

		    {
			document.all(objeto).value=document.all(objeto).value + "/";
			}
			
		}
   		break;
   	default:
   	  
   	     	      if (((event.keyCode) != 8)&& ((event.keyCode) != 37)&& 
((event.keyCode) != 39)&& ((event.keyCode) != 46)&& ((event.keyCode) != 
97)&& ((event.keyCode) != 98)&& ((event.keyCode) != 99)&& ((event.keyCode) 
!= 100)&& ((event.keyCode) != 101)&& ((event.keyCode) != 102)&& 
((event.keyCode) != 103)&& ((event.keyCode) != 104)&& ((event.keyCode) != 
105)&& ((event.keyCode) != 96))

   		{
     
    	 event.returnValue=false;
		}
   	
   }
	
}
  -->
 </script>
 
<script language="javascript">
<!--

fuction validaValores(sino,opcionesvertical,opcioneshorizontal){
if(document.all(sino).value=="")
	alert('Faltan llenar campos!');
if(document.all(opcionesvertical).value=="")
	alert('Faltan llenar campos!');
if(document.all(opcioneshorizontal).value=="")
	alert('Faltan llenar campos!');
}

function formatoFechas(campoFecha,perVacio){
    if(perVacio==1)
		if(document.all(campoFecha).value=="")
			return 1;
	re=/\d{2}\/\d{2}\/\d{4}/; 
    if((document.all(campoFecha).value.search(re)==-1)){
		alert("El formato de la fecha es incorrecto (dd/mm/yyyy) � fecha obligatoria");
		document.all(campoFecha).focus();
		document.all(campoFecha).select();
		return 0;
	}
	else{
		wr1=checaDiaMes (campoFecha,"dd/MM/yyyy");
		if (wr1==0){
			alert("Fecha inv�lida");
			document.all(campoFecha).focus();
			document.all(campoFecha).select();
			return 0;
		}
	}
	return 1;
}
-->
</script>
