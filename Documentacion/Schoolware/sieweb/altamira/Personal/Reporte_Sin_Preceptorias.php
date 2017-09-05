<?php
include('../config.php');
//include('../functions.php');
////Sacamos las fechas así como el periodo actual para trabajar

//// Seleccionando rangos del reporte
$fecha1=date(d);
$fecha2=date(m);
$fecha3=date(Y); 

$rst_ = mysql_query ("select * from usuarios_encabezados where nivel_preceptoria='11'",$link) or die ("select * from usuarios_encabezados where nivel_preceptoria='11'".mysql_error());
  while($rs_=mysql_fetch_array($rst_))
  { 
  	$nivel=$rs_["nivel_preceptoria"];
  }


	$f_m_inicial='01-06-2014';
	

$dias_mes= cal_days_in_month(CAL_GREGORIAN, $fecha2, $fecha3); // 31

$Fecha_ini = date("d-m-Y");
$dias_m = $dias_mes;
$Fecha_fin = new DateTime();
date_add($Fecha_fin, date_interval_create_from_date_string(''.$dias_m.' days'));

$f_m_final= $dias_m.'-'.$fecha2.'-'.$fecha3;

$fecha_ini=$f_m_inicial;

$fecha_fin=$f_m_final;

$mes_ini= substr($fecha_ini, 3, 2);
$mes_fin= substr($fecha_fin, 3, 2);
$mes_fin_= substr($fecha_fin, 3, 2);

$y_ini=substr($fecha_ini, 6, 4);
$y_fin=substr($fecha_fin, 6, 4);
$dia_ini=substr($fecha_ini, 0, 2);
$dia_fin=substr($fecha_fin, 0,2);
$fecha_inicial = $y_ini."-".$mes_ini."-".$dia_ini;


$fecha_final= "2014-12-31";
switch($mes_ini)
{
   case 1: $pmes="Enero"; break;
   case 2: $pmes="Febrero"; break;
   case 3: $pmes="Marzo"; break;
   case 4: $pmes="Abril"; break;
   case 5: $pmes="Mayo"; break;
   case 6: $pmes="Junio"; break;
   case 7: $pmes="Julio"; break;
   case 8: $pmes="Agosto"; break;
   case 9: $pmes="Septiembre"; break;
   case 10: $pmes="Octubre"; break;
   case 11: $pmes="Noviembre"; break;
   case 12: $pmes="Diciembre"; break;
}

switch($mes_fin)
{
   case 1: $smes="Enero"; break;
   case 2: $smes="Febrero"; break;
   case 3: $smes="Marzo"; break;
   case 4: $smes="Abril"; break;
   case 5: $smes="Mayo"; break;
   case 6: $smes="Junio"; break;
   case 7: $smes="Julio"; break;
   case 8: $smes="Agosto"; break;
   case 9: $smes="Septiembre"; break;
   case 10: $smes="Octubre"; break;
   case 11: $smes="Noviembre"; break;
   case 12: $smes="Diciembre"; break;
}

		
if($fecha2<=6) {
	$ciclo_esc=$fecha3-1;
	}
else
	{
	$ciclo_esc=$fecha3;	
date_add($ciclo_esc, date_interval_create_from_date_string('-1 years'));
	}

$preceptoriaCiclo=substr($fecha_inicial,0,4);
if($mes_inicial_periodo_actual>substr($fecha_inicial,5,2))
	$preceptoriaCiclo--;
if($mes_fin<$mes_ini)
	$mes_fin+=12;

$mesesReporte=($mes_fin-$mes_ini)+1;



////// Preceptorías por alumno

$pre_ini = new DateTime();
$pre_ini->modify('first day of this month');
//echo $pre_ini->format('Y-m-d'); //2011-10-01

$pre_fin = new DateTime();
$pre_fin->modify('last day of this month');
//echo $pre_fin->format('Y-m-d'); //2011-10-01
$pre_ini="'".$pre_ini->format('Y-m-d')."'";
$pre_fin="'".$pre_fin->format('Y-m-d')."'";


$p_y_ini=substr($pre_ini, 1, 4);
$p_y_fin=substr($pre_fin, 1, 4);

$p_mes_ini = substr($pre_ini, 6, 2);
$p_mes_fin= substr($pre_fin, 6, 2);

$p_dia_ini=substr($pre_ini, 9, 2);
$p_dia_fin=substr($pre_fin, 9,2);


switch($p_mes_ini)
{
   case 1: $p_pmes="Enero"; break;
   case 2: $p_pmes="Febrero"; break;
   case 3: $p_pmes="Marzo"; break;
   case 4: $p_pmes="Abril"; break;
   case 5: $p_pmes="Mayo"; break;
   case 6: $p_pmes="Junio"; break;
   case 7: $p_pmes="Julio"; break;
   case 8: $p_pmes="Agosto"; break;
   case 9: $p_pmes="Septiembre"; break;
   case 10: $p_pmes="Octubre"; break;
   case 11: $p_pmes="Noviembre"; break;
   case 12: $p_pmes="Diciembre"; break;
}

switch($p_mes_fin)
{
   case 1: $p_smes="Enero"; break;
   case 2: $p_smes="Febrero"; break;
   case 3: $p_smes="Marzo"; break;
   case 4: $p_smes="Abril"; break;
   case 5: $p_smes="Mayo"; break;
   case 6: $p_smes="Junio"; break;
   case 7: $p_smes="Julio"; break;
   case 8: $p_smes="Agosto"; break;
   case 9: $p_smes="Septiembre"; break;
   case 10: $p_smes="Octubre"; break;
   case 11: $p_smes="Noviembre"; break;
   case 12: $p_smes="Diciembre"; break;
}

$pre_f_inicial = $p_dia_ini."-".$p_pmes."-".$p_y_ini;
$pre_f_final = $p_dia_fin."-".$p_smes."-".$p_y_fin;


/////// Aquí terminamos de seleccionar nuestros parametros

/////// Fechas para el reporte, ordenamiento

$fec_inicial = "'".$y_ini."-".$mes_ini."-".$dia_ini."'";
$fec_final= "'".$y_fin."-".$mes_fin."-".$dia_fin."'";

///Pantalla
$exportar="
<img src='https://ecolmenares.net/sieweb/altamira/im/logo.jpg' width='100%' ></img>


<style>

body {
    margin-left: 2em
}
table {
    margin-left: auto;
    margin-right: auto
}
caption {
    caption-side: left;
    margin-left: -8em;
    width: 8em;
    text-align: right;
    vertical-align: bottom
}


</style>
<page orientation='paysage' >
    <bookmark title='Document' level='0' ></bookmark>
    <a name='document_reprise'></a>
 
    <table  cellspacing='0' style='width: 100%;'>
        <tr>
            <td style='width: 55% '>
        <!-- Aquí empieza primer frame --->";
			  
///PDF 
 $fpdf_exportar="
<img src='https://ecolmenares.net/sieweb/altamira/im/logo.jpg' width='100%' ></img>


<style>

body {
    margin-left: 8em
}
table {
    margin-left: auto;
    margin-right: auto
}
caption {
    caption-side: left;
    margin-left: -8em;
    width: 8em;
    text-align: right;
    vertical-align: bottom
}


</style>
<page orientation='paysage' >
    <bookmark title='Document' level='0' ></bookmark>
    <a name='document_reprise'></a>
 
    <table  cellspacing='0' style='width: 100%;'>
        <tr>
            <td style='width: 55% '>
";
 
 
 
/////Familias sin Entrevista

///Pantalla
$exportar.="
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; font-size: 10pt'>
	<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; font-size: 10pt'>		
		<tr>
    		<td colspan='25' align='center'><strong><b>Familias sin Entrevista</b></strong></td>
  		</tr>
		<tr>
			<td colspan=8><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$dia_ini."-".$pmes."-".$y_ini."&nbsp;&nbsp;&nbsp;al&nbsp; ".$dia_fin."-".$smes."-".$y_fin."</td>
		</tr>
		<tr>
			<td colspan='8'><b>Ciclo:</b> $preceptoriaCiclo</td>
		</tr>
		<tr>
			<td colspan='8'><b>Meses:</b> $mesesReporte</td>
		</tr>
		<tr>
   			<td align='center' bgcolor='#00658A'><b><font color='#FFFFFF'>Secci&oacute;n</font></b></td>
    		<td align='center' bgcolor='#00658A'><b><font color='#FFFFFF'>No. Padres sin Entrevistas</font></b></td>
    		<td align='center' bgcolor='#00658A'><b><font color='#FFFFFF'>% Secci&oacute;n</font></b></td>
  		</tr>";
					
///PDF					
$fpdf_exportar.="
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; font-size: 10pt'>		
		<tr>
    		<td colspan='25' align='center'><strong><b>Familias sin Entrevista</b></strong></td>
  		</tr>
		<tr>
			<td colspan=8><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$dia_ini."-".$pmes."-".$y_ini."&nbsp;&nbsp;&nbsp;al&nbsp; ".$dia_fin."-".$smes."-".$y_fin."</td>
		</tr>
		<tr>
			<td colspan='8'><b>Ciclo:</b> $preceptoriaCiclo</td>
		</tr>
		<tr>
			<td colspan='8'><b>Meses:</b> $mesesReporte</td>
		</tr>
		<tr>
   			<td align='center' bgcolor='#00658A'><b><font color='#FFFFFF'>Secci&oacute;n</font></b></td>
    		<td align='center' bgcolor='#00658A'><b><font color='#FFFFFF'>No. Padres sin Entrevistas</font></b></td>
    		<td align='center' bgcolor='#00658A'><b><font color='#FFFFFF'>% Secci&oacute;n</font></b></td>
  		</tr>";					
					
for($y=1; $y<=3; $y++){						
	
	$seccion_=mysql_query("select nombre from secciones where ciclo=$ciclo_esc and seccion=$y",$link);

	while($seccion_nombre=mysql_fetch_array($seccion_)){
	
		///Pantalla
    	$exportar.="<tr>
  						<td align='left' valign='center' bgcolor='#FFFFCC'>".$seccion_nombre['nombre']."</td>";
		////PDF
		$fpdf_exportar.="<tr>
  						<td align='left' valign='center' bgcolor='#FFFFCC'>".$seccion_nombre['nombre']."</td>";
				
	}
		
		///// Sacamos el total de Alumnos/Familias
		$f_t_=mysql_query("select count(alumno) as tot_alumnos from alumnos where seccion=$y and activo='A'",$link);
				
	while($t_f_t_=mysql_fetch_array($f_t_)){
		
		$total_alumnos=$t_f_t_['tot_alumnos'];
	}
	
		///// Sacamos el total de familias que si tuvieron preceptorias por sección
		
		
		$con_precep=mysql_query("SELECT count(distinct(concat(alumnos.alumno,fec))) as totalA 
															FROM preceptoria_acuerdos,alumnos, secciones 
															WHERE fec between $fec_inicial and $fec_final
																	AND alumnos.alumno =preceptoria_acuerdos.alumno
																	AND secciones.seccion=alumnos.seccion
																	AND alumnos.activo='A'
																	AND secciones.ciclo=$ciclo_esc
																	AND alumnos.seccion=$y"
																	,$link);
		
	while($con_precep_res=mysql_fetch_array($con_precep)){
		
		$con_precep_=$con_precep_res['totalA'];		
	}
	
		///// Restamos las familias menos las preceptorías realizadas para saber el faltante de familias de preceptorías
		
	$total_familias_sin=$total_alumnos-$con_precep_;	
	
	
	$porcentaje_f_sin=100-round(($con_precep_/$total_alumnos)*100,2);	
		///Pantalla
		$exportar.="	<td align='center'>".$total_familias_sin."</td>
    					<td align='center'>".$porcentaje_f_sin."</td>  
  					</tr>";
		///PDF
		$fpdf_exportar.="	<td align='center'>".$total_familias_sin."</td>
    					<td align='center'>".$porcentaje_f_sin."</td>  
  					</tr>";				
}
		///Pantalla				
		$exportar.="
					<tr>
                        <td colspan='5' align='center'>&nbsp;</td>
   					</tr>
					<tr>
    					<td align='center' colspan='10'><a href='detalle_entrevistas.php' target='_blank'>Detalle reporte Familias sin Entrevista</a></td>
  					</tr>
	<!--			<tr>
                        <td colspan='5' align='center'>&nbsp;</td>
   					</tr>-->
		</table>";
		
		///PDF
		$fpdf_exportar.="

					<tr>
    					<td align='center' colspan='10'><a href='http://html2pdf.fr/' target='_blank'>Detalle reporte Familias sin Entrevista</a></td>
  					</tr>
					<tr>
                        <td colspan='5' align='center'>&nbsp;</td>
   					</tr>
	<!--			<tr>
                        <td colspan='5' align='center'>&nbsp;</td>
   					</tr>-->
		</table>";
		
         $exportar.="<!-- Aquí acaba primer frame -->
                
            </td>
            <td style='width: 4%'></td>
            <td>
            <!-- Aquí empieza segundo frame -->";
   
	$exportar.="
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; font-size: 10pt '>
		<tr>	
    		<th colspan='10' align='center'><strong><b>Alumnos sin preceptor&iacute;a</b></strong></th>
  		</tr>
		<tr>
			<td colspan='11'><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$pre_f_inicial." al ".$pre_f_final."</td>
		</tr>
		<tr>
			<td colspan='10'><b>Ciclo:</b> $preceptoriaCiclo</td>
		</tr>
		<tr>
			<td colspan='10'><b>Meses:</b> 1 </td>
		</tr>
		<tr>				
			<td align='center' bgcolor='#00658A' colspan='2'><b><font color='#FFFFFF'>Secci&oacute;n</font></b></td>
    		<td align='center' bgcolor='#00658A' colspan='6'><b><font color='#FFFFFF'>No. Alumnos sin Entrevistas</font></b></td>
    		<td align='center' bgcolor='#00658A' colspan='2'><b><font color='#FFFFFF'>% Secci&oacute;n</font></b></td>
  		</tr>
					";
	////PDF
	$fpdf_exportar.="<br><br><br><br><br><br><br><br>
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; font-size: 10pt '>
		<tr>	
    		<td colspan='10' align='center'><strong><b>Alumnos sin preceptor&iacute;a</b></strong></td>
  		</tr>
		<tr>
			<td colspan='11'><b>Rango de Fechas:</b> Del&nbsp;&nbsp;".$pre_f_inicial." al ".$pre_f_final."</td>
		</tr>
		<tr>
			<td colspan='10'><b>Ciclo:</b> $preceptoriaCiclo</td>
		</tr>
		<tr>
			<td colspan='10'><b>Meses:</b> 1 </td>
		</tr>
		<tr>				
			<td align='center' bgcolor='#00658A' colspan='2'><b><font color='#FFFFFF'>Secci&oacute;n</font></b></td>
    		<td align='center' bgcolor='#00658A' colspan='6'><b><font color='#FFFFFF'>No. Alumnos sin Entrevistas</font></b></td>
    		<td align='center' bgcolor='#00658A' colspan='2'><b><font color='#FFFFFF'>% Secci&oacute;n</font></b></td>
  		</tr>
					";				

 for($x=1; $x <= 3; $x++){/////Empezamos el for de las secciones escolares			
										
	////Sacamos el total de alumnos con preceptorias				
    $cprecep=mysql_query("SELECT  COUNT(DISTINCT(preceptoria.alumno)) AS total
								FROM         
									preceptoria INNER JOIN
                      				alumnos ON preceptoria.alumno = alumnos.alumno
								WHERE     
										(preceptoria.ciclo = $ciclo_esc) 
										AND (preceptoria.fecha BETWEEN $pre_ini AND $pre_fin) 
										AND (alumnos.seccion = $x)",$link);
	//// Numero total de alumnos con preceptorias					 
$total_con_precep=mysql_fetch_assoc($cprecep);

	////Sacamos el total de alumnos por seccion
	$total_alumn=mysql_query("SELECT alumno from alumnos where activo='A' and seccion=$x",$link);
	////Obtenemos el total de alumnos por sección
$total_alum__=mysql_num_rows($total_alumn);

	////Procedemos a hacer la resta y sacar el total sin preceptorias
		
$total_pre__=$total_alum__-$total_con_precep['total'];

/////Porcentaje de alumnos sin preceptorías
$porcent_sin=100-round(($total_con_precep['total']/$total_alum__)*100,2);

$exportar.=" <tr>";
$fpdf_exportar.=" <tr>";
    				
					$seccion__=mysql_query("select nombre from secciones where ciclo=$ciclo_esc and seccion=$x",$link);
			
while($seccion_nombre__=mysql_fetch_array($seccion__)){				
////Pantalla
$exportar.="		<td colspan='2' align='left' valign='center' bgcolor='#FFFFCC'>".$seccion_nombre__['nombre']."</td>";
///PDF
$fpdf_exportar.="		<td colspan='2' align='left' valign='center' bgcolor='#FFFFCC'>".$seccion_nombre__['nombre']."</td>";

	   }
	///Pantalla
	$exportar.="<td align='center' colspan='6'>".$total_pre__."</td>";	
    ///PDF
	$fpdf_exportar.="<td align='center' colspan='6'>".$total_pre__."</td>";	
    
	///Pantalla
	$exportar.="<td align='center'>".$porcent_sin."</td>
  			</tr>
			";
	////PDF
    $fpdf_exportar.="<td align='center'>".$porcent_sin."</td>
  			</tr>
			";
  
}  
  ///Pantalla
  $exportar.="
  		<!--<tr>
				<td align='center' colspan='10'>&nbsp;</td><br><br>
			</tr>-->
			<tr>
                <td colspan='10' align='center'>&nbsp;</td>
   			</tr>
			<tr>
    			<td align='center' colspan='10'><a href='https://ecolmenares.net/sieweb/altamira/Personal/detalle_preceptorias.php' target='_blank'>Detalle reporte Alumnos sin preceptor&iacute;a </a></td>
  			</tr>
	</table>";    
	
	/////PDF
	$fpdf_exportar.="
  		<!--<tr>
				<td align='center' colspan='10'>&nbsp;</td><br><br>
			</tr>-->
			<tr>
                <td colspan='10' align='center'>&nbsp;</td>
   			</tr>
			<tr>
    			<td align='center' colspan='10'><a href='https://ecolmenares.net/sieweb/altamira/Personal/preceptorias2.php' >Detalle reporte Alumnos sin preceptor&iacute;a </a></td>
  			</tr>
	</table>";    
	
      $exportar.="<!-- Aquí finaliza segundo frame -->
         	</td>
            <td style='width: 4%'></td>
        </tr>
        <tr>
            <td style='width:55%;'>
                <!-- Aquí empieza tercer frame -->";
								
///PDF Para pasar a la siguiente página				
$fpdf_exportar.="<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
";				



////Pantalla
$exportar.="
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; text-align: left; font-size: 10pt'>
		<tr>
			<td colspan=8 align='center'><strong><b>Reporte Total Entrevistas de Padres</b></strong></td>
		</tr>
		<tr>
			<td align='center' rowspan='2' valign='bottom'><b>&nbsp;&nbsp;Grupo&nbsp;&nbsp;</b></td>
			<td align='center' colspan='5' bgcolor='#00658A'><b><font color='#FFFFFF'>".$pmes." &nbsp;- &nbsp;" .$smes." </font> </b> </td>
			<td align='center' colspan='5' bgcolor='#FFFFFF'><b><font color='#00658A'>Semestre Actual</font> </b> </td>
		</tr>
		<tr>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;No. Alumnos por grupo&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Asistencias Ambos&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Asistencias Padre&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Asistencias Madre&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;No Asisten Padres&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Total Entrevistas&nbsp;</font></b></td>
			<td align='center' valing='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;% Avance&nbsp;</font></b></td> 
		</tr>";
		
		
		
/////PDF
$fpdf_exportar.="
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; text-align: left; font-size: 10pt'>
		<tr>
			<td colspan=8 align='center'><strong><b>Reporte Total Entrevistas de Padres</b></strong></td>
		</tr>
		<tr>
			<td align='center' rowspan='2' valign='bottom'><b>&nbsp;&nbsp;Grupo&nbsp;&nbsp;</b></td>
			<td align='center' colspan='5' bgcolor='#00658A'><b><font color='#FFFFFF'>".$pmes." &nbsp;- &nbsp;" .$smes." </font> </b> </td>
			<td align='center' colspan='5' bgcolor='#FFFFFF'><b><font color='#00658A'>Semestre Actual</font> </b> </td>
		</tr>
		<tr>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;No. Alumnos por grupo&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Asistencias Ambos&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Asistencias Padre&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Asistencias Madre&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;No Asisten Padres&nbsp;</font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;Total Entrevistas&nbsp;</font></b></td>
			<td align='center' valing='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;% Avance&nbsp;</font></b></td> 
		</tr>";

////Sacamos los niveles de secciones
$secc=mysql_query("select distinct(grupos_sede.seccion) AS seccion,nombre from grupos_sede INNER JOIN
                      secciones ON grupos_sede.seccion = secciones.seccion where grupos_sede.ciclo=$preceptoriaCiclo",$link);

while($secc_=mysql_fetch_array($secc)){
	$seccion="'".$secc_['seccion']."'";
	$seccion_nombre=$secc_['nombre'];
		///Pantalla
		$exportar.="<tr><td colspan=8 align='center' valign='center' bgcolor='#D9B57B'><font color='#FFFFFF'>".$seccion_nombre."</font></td></tr>";
		////PDF
		$fpdf_exportar.="<tr><td colspan=8 align='center' valign='center' bgcolor='#D9B57B'><font color='#FFFFFF'>".$seccion_nombre."</font></td></tr>";
	////
	$sgg = mysql_query("select concat(seccion,grado,grupo) as sgg from grupos_sede where  ciclo=$preceptoriaCiclo and activo='S' and seccion=$seccion",$link);

		while($sgg_=mysql_fetch_array($sgg)){	
		//// Aquí establecemos como mostraremos nuestra variable sgg para que sirva en todos los querys
		$sgg__="'".$sgg_['sgg']."'";
		/////Empezamos el query donde obtendremos el total de grupos y de alumnos por grupo vigentes (traemos la variable sgg)
		 $grup_grad=mysql_query("SELECT CONCAT(alumnos.grado,' ',alumnos.grupo) AS gg,						
																	count(distinct(alumnos.alumno)) as total
															FROM alumnos INNER JOIN grupos_sede ON
             														alumnos.seccion = grupos_sede.seccion 
             														AND alumnos.grado = grupos_sede.grado 
             														AND alumnos.grupo = grupos_sede.grupo
															WHERE
																	CONCAT(alumnos.seccion,alumnos.grado,alumnos.grupo)=$sgg__
																	and alumnos.activo='A'
																	and nuevo_ingreso!='P'",$link);
				////Empezamos a pintar el grado-grupo y el total de alumnos por grupo				  
				while($gg_=mysql_fetch_array($grup_grad)){									  
				////Pantalla
				$exportar.="<tr><td align='left' valign='top' bgcolor='#FFFFCC'><font color='#000000'>".$gg_['gg']."</font></td><td align='center'>".$gg_['total']."</td>";
				////PDF
				$fpdf_exportar.="<tr><td align='left' valign='top' bgcolor='#FFFFCC'><font color='#000000'>".$gg_['gg']."</font></td><td align='center'>".$gg_['total']."</td>";
				$a=$gg_['total'];
				
				}/////While de Grupo_Grado y Total Alumnos

					/////Aquí seleccionamos que se presentan ambos padres a las entrevistas
									$amb_p=mysql_query("SELECT count(distinct(concat(alumnos.alumno,fec))) as totalA 
															FROM preceptoria_acuerdos,alumnos 
															WHERE fec between $fec_inicial and $fec_final
																	AND alumnos.activo='A'
																	AND alumnos.alumno =preceptoria_acuerdos.alumno 
																	AND concat(seccion,grado,grupo)=$sgg__
																	AND padre=1 and madre=1",$link);
									while($amb_p_=mysql_fetch_array($amb_p)){
										////Pantalla
										$exportar.="<td align='center' valign='top'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
										////PDF
										$fpdf_exportar.="<td align='center' valign='top'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
										
									}////While Asistencias padres y madres

									/////Aquí seleccionamos que se presentan solo padres a las entrevistas
									$amb_p=mysql_query("SELECT count(distinct(concat(alumnos.alumno,fec))) as totalA 
															FROM preceptoria_acuerdos,alumnos 
															WHERE fec between $fec_inicial and $fec_final
																	AND alumnos.activo='A'
																	AND alumnos.alumno =preceptoria_acuerdos.alumno 
																	AND concat(seccion,grado,grupo)=$sgg__
																	AND padre=1 and madre=0",$link);

										while($amb_p_=mysql_fetch_array($amb_p)){
											////Pantalla
											$exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";						
											////PDF
											$fpdf_exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
										}////While que se presentan solo padres a las entrevistas	
										
										/////Aquí seleccionamos que no se presentan los padres a las entrevistas
									$amb_p=mysql_query("SELECT count(distinct(concat(alumnos.alumno,fec))) as totalA 
															FROM preceptoria_acuerdos,alumnos 
															WHERE fec between $fec_inicial and $fec_final
																	AND alumnos.activo='A'
																	AND alumnos.alumno =preceptoria_acuerdos.alumno 
																	AND concat(seccion,grado,grupo)=$sgg__
																	AND padre=0 and madre=0",$link);

										while($amb_p_=mysql_fetch_array($amb_p)){
											////Pantalla
											$exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
											////PDF
											$fpdf_exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
										}////While que se no se presentan los padres a las entrevistas
									
										/////Aquí seleccionamos que se presentan solo madres a las entrevistas
									$amb_p=mysql_query("SELECT count(distinct(concat(alumnos.alumno,fec))) as totalA 
															FROM preceptoria_acuerdos,alumnos 
															WHERE fec between $fec_inicial and $fec_final
																	AND alumnos.activo='A'
																	AND alumnos.alumno =preceptoria_acuerdos.alumno 
																	AND concat(seccion,grado,grupo)=$sgg__
																	AND padre=0 and madre=1",$link);
										while($amb_p_=mysql_fetch_array($amb_p)){
											///Pantalla
											$exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
											///PDF
											$fpdf_exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
										}////While que se presentan solo madres a las entrevistas								
									
									/////Aquí seleccionamos el total de las entrevistas
									$amb_p=mysql_query("SELECT count(distinct(concat(alumnos.alumno,fec))) as totalA 
															FROM preceptoria_acuerdos,alumnos 
															WHERE fec between $fec_inicial and $fec_final
																	AND alumnos.activo='A'
																	AND alumnos.alumno =preceptoria_acuerdos.alumno 
																	AND concat(seccion,grado,grupo)=$sgg__
																	",$link);
										while($amb_p_=mysql_fetch_array($amb_p)){
											///Pantalla
											$exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
											////PDF
											$fpdf_exportar.="<td align='center'><font color='#000000'>".$amb_p_['totalA']."</font></td>";
											$b=$amb_p_['totalA'];
										}////While del total de las entrevistas		
			
										if($b<>0) {
											
										$resultado_mostrar= ((($b*100)/$a)/100)*100;
										
										///Pantalla
										$exportar.= "<td><font color='#000000'>".round($resultado_mostrar,2). "</font></td>";
										///PDF
										$fpdf_exportar.= "<td><font color='#000000'>".round($resultado_mostrar,2). "</font></td>";
										}
										else
										///Pantalla
										{
										$exportar.= "<td><font color='#000000'>0.00</font></td>";
										////PDF
										$fpdf_exportar.= "<td><font color='#000000'>0.00</font></td>";
										}
///Pantalla
$exportar.="</tr>";////Brinco de renglon
///PDF
$fpdf_exportar.="</tr>";////Brinco de renglon

			}////While de $sgg
}////While del ciclo vigente
///Pantalla
$exportar.="<tr><td colspan='12'></td></tr></table>";
///PDF
$fpdf_exportar.="<tr><td colspan='12'></td></tr></table>";

					
					
$exportar.="<!-- Aquí finaliza tercer frame -->
            </td>
            <td style='width: 4%'></td>
            <td style='width: 37%;'>

                <!-- Aquí empieza cuarto frame -->";
               
                //////////
///Pantalla                               
$exportar.="
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; text-align: left; font-size: 10pt'>	
		<tr>
			<th colspan='10' width='250' align='center'><strong><b>Reporte Preceptor&iacute;as Por Grupo</b></strong></th>
		</tr>
		<tr>
			<td colspan='10' width='250' align='center'><strong><b> Mes Actual </b></strong></td>
		</tr>	
		<tr>
			<td align='center' rowspan='2' valign='bottom'><b>Grupo</b></td>
			<td align='center' colspan='7' bgcolor='#00658A'><b><font color='#FFFFFF'>".$p_pmes." </font> </b> </td>
			<!--<td align='center' colspan='6' bgcolor='#FFFFFF'><b><font color='#00658A'>Mes Actual </font> </b> </td>-->

		</tr>
		<tr>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font></b></td> 
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Meta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'> No. Alumnos</font></b></td>
			<td colspan='3' align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>% Avance</font></b></td>
			<td colspan='6' align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;</font></b></td>
		</tr>";
		
///PDF		
$fpdf_exportar.="<br><br><br><br>
<table  cellspacing='0' style='width: 100%; border: solid 2px #000000; text-align: left; font-size: 10pt'>	
		<tr>
			<th colspan='10' width='250' align='center'><strong><b>Reporte Preceptor&iacute;as Por Grupo</b></strong></th>
		</tr>
		<tr>
			<td colspan='10' width='250' align='center'><strong><b> Mes Actual </b></strong></td>
		</tr>	
		<tr>
			<td align='center' rowspan='2' valign='bottom'><b>Grupo</b></td>
			<td align='center' colspan='7' bgcolor='#00658A'><b><font color='#FFFFFF'>".$p_pmes." </font> </b> </td>
			<!--<td align='center' colspan='6' bgcolor='#FFFFFF'><b><font color='#00658A'>Mes Actual </font> </b> </td>-->

		</tr>
		<tr>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font></b></td> 
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Meta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font></b></td>
			<td align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'> No. Alumnos</font></b></td>
			<td colspan='3' align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>% Avance</font></b></td>
			<td colspan='6' align='center' valign='top' bgcolor='#00658A'><b><font color='#FFFFFF'>&nbsp;</font></b></td>
		</tr>";
//////


////Sacamos los niveles de secciones
$secc=mysql_query("select distinct(grupos_sede.seccion) AS seccion,nombre from grupos_sede INNER JOIN
                      secciones ON grupos_sede.seccion = secciones.seccion where grupos_sede.ciclo=$preceptoriaCiclo",$link);

while($secc_=mysql_fetch_array($secc)){
	$seccion="'".$secc_['seccion']."'";
	$seccion_nombre=$secc_['nombre'];
		///Pantalla
		$exportar.="<tr><td colspan='13' align='center' valign='center' bgcolor='#D9B57B'><font color='#FFFFFF'>".$seccion_nombre."</font></td></tr>";
		///PDF
		$fpdf_exportar.="<tr><td colspan='13' align='center' valign='center' bgcolor='#D9B57B'><font color='#FFFFFF'>".$seccion_nombre."</font></td></tr>";
	////
	$sgg = mysql_query("select concat(seccion,grado,grupo) as sgg from grupos_sede where  ciclo=$preceptoriaCiclo and activo='S' and seccion=$seccion",$link);

		while($sgg_=mysql_fetch_array($sgg)){	
		//// Aquí establecemos como mostraremos nuestra variable sgg para que sirva en todos los querys
		$sgg__="'".$sgg_['sgg']."'";
		/////Empezamos el query donde obtendremos el total de grupos y de alumnos por grupo vigentes (traemos la variable sgg)
		 $grup_grad=mysql_query("SELECT CONCAT(alumnos.grado,' ',alumnos.grupo) AS gg,						
																	count(distinct(alumnos.alumno)) AS total
															FROM alumnos INNER JOIN grupos_sede ON
             														alumnos.seccion = grupos_sede.seccion 
             														AND alumnos.grado = grupos_sede.grado 
             														AND alumnos.grupo = grupos_sede.grupo
															WHERE
																	CONCAT(alumnos.seccion,alumnos.grado,alumnos.grupo)=$sgg__
																	AND alumnos.activo='A'
																	AND nuevo_ingreso!='P'",$link);
				////Empezamos a pintar el grado-grupo y el total de alumnos por grupo				  
				while($gg_=mysql_fetch_array($grup_grad)){							
				///Pantalla
				$exportar.="<tr><td align='left' valign='top' bgcolor='#FFFFCC'><font color='#000000'>".$gg_['gg']."</font></td>";
				///PDF
				$fpdf_exportar.="<tr><td align='left' valign='top' bgcolor='#FFFFCC'><font color='#000000'>".$gg_['gg']."</font></td>";
				$a=$gg_['total'];
				$totalAlumnos=$gg_['total'];
				}/////While de Grupo_Grado y Total Alumnos


///// Ajustamos los meses a enteros

switch($mes_ini)
{
   case 1: $pmes=1; break;
   case 2: $pmes=2; break;
   case 3: $pmes=3; break;
   case 4: $pmes=4; break;
   case 5: $pmes=5; break;
   case 6: $pmes=6; break;
   case 7: $pmes=7; break;
   case 8: $pmes=8; break;
   case 9: $pmes=9; break;
   case 10: $pmes=10; break;
   case 11: $pmes=11; break;
   case 12: $pmes=12; break;
}

switch($mes_fin)
{
   case 1: $smes=1; break;
   case 2: $smes=2; break;
   case 3: $smes=3; break;
   case 4: $smes=4; break;
   case 5: $smes=5; break;
   case 6: $smes=6; break;
   case 7: $smes=7; break;
   case 8: $smes=8; break;
   case 9: $smes=9; break;
   case 10: $smes=10; break;
   case 11: $smes=11; break;
   case 12: $smes=12; break;
}

///////

$rstM_=mysql_query ("select sum(meta) as meta from preceptoria_grupos where ciclo=$ciclo_esc and mes=$pmes and concat(seccion,grado,grupo)=$sgg__",$link) or die ("select sum(meta) as meta from preceptoria_grupos where ciclo=$ciclo_esc and mes=$pmes and concat(seccion,grado,grupo)=$sgg__".mysql_error());
			while($rsM_=mysql_fetch_array($rstM_)){
				$metass=$rsM_['meta'];
			}
			if($metass=="") $metass=0;


$rstT_=mysql_query("SELECT COUNT(DISTINCT(CONCAT(preceptoria.alumno,fecha))) AS total
						FROM preceptoria, alumnos 
						WHERE preceptoria.alumno=alumnos.alumno
							AND alumnos.activo='A'
							AND preceptoria.ciclo=$ciclo_esc
							AND concat(seccion, alumnos.grado, alumnos.grupo)=$sgg__
							AND fecha BETWEEN $pre_ini AND $pre_fin 
						ORDER BY fecha",$link);

			while($rstT=mysql_fetch_array($rstT_)){
				$totalPrec=$rstT['total'];	
			}

$meta__=$totalAlumnos*$metass;

if($meta__<>0){

$avance=round(($totalPrec*100)/$meta__,2);
}
else
$avance='100.00';

///Pantalla
$exportar.="
<td align='center' valign='top'><font color='#000000'>".$totalPrec."</font></td>
<td align='center' valign='top'><font color='#000000'>".$totalAlumnos*$metass."</font></td>
<td align='center' valign='top'><font color='#000000'>".$totalAlumnos."</font></td>
<td align='center' valign='top' colspan='3'><font color='#000000'>".$avance."</font></td>
</tr>
";
///PDF
$fpdf_exportar.="

<td align='center' valign='top'><font color='#000000'>".$totalPrec."</font></td>
<td align='center' valign='top'><font color='#000000'>".$totalAlumnos*$metass."</font></td>
<td align='center' valign='top'><font color='#000000'>".$totalAlumnos."</font></td>
<td align='center' valign='top' colspan='3'><font color='#000000'>".$avance."</font></td>
</tr>
";		
		}
}
////Pantalla
$exportar.="<tr><td colspan='12'></td></tr>
</table>";
///PDF
$fpdf_exportar.="<tr><td colspan='12'></td></tr>
</table>";            
			
			
			
$exportar.="<!-- Aquí finaliza cuarto frame -->
         </td>
            <td style='width: 4%'>&nbsp;</td>
        </tr>
    </table>
</page>";


	  ////// Exportamos nuestras tablas
	  echo $exportar;
	//echo $fpdf_exportar;

//// Mandamos llamar las librerias del HTML2PDF 

include_once ('../../repositorio/html2pdf/html2fpdf.php');


$pdf = new HTML2FPDF('P','mm','Letter'); // Creamos una instancia de la clase HTML2FPDF

$pdf -> AddPage(); // Creamos una página


$pdf -> SetFont('Courier','I',3);


$pdf -> SetDisplayMode('fullpage');

$pdf -> WriteHTML($exportar);//Volcamos el HTML contenido en la variable $html para crear el contenido del PDF

$pdf -> Output('doc.pdf');//Volcamos el pdf generado con nombre ‘doc.pdf’. En este caso con el parametro ‘D’ forzamos la descarga del mismo.

/////////////////////////////


/// Nuevo PDF
	  

$pdf = new HTML2FPDF('P','mm','Letter'); // Creamos una instancia de la clase HTML2FPDF

$pdf -> AddPage(); // Creamos una página


$pdf -> SetFont('Courier','I',3);


$pdf -> SetDisplayMode('fullpage');

$pdf -> WriteHTML($fpdf_exportar);//Volcamos el HTML contenido en la variable $html para crear el contenido del PDF

$pdf -> Output('doc2.pdf');//Volcamos el pdf generado con nombre ‘doc.pdf’. En este caso con el parametro ‘D’ forzamos la descarga del mismo.




?> 