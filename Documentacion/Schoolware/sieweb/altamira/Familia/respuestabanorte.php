<?php
include('../connection.php');
include('../config.php');


$Id_Afiliacion = $_REQUEST['ID_AFILIACION'];
$Referencia = $_REQUEST['REFERENCIA'];
$Numero_Control = $_REQUEST['NUMERO_CONTROL'];
$Fecha_Req_CTE = substr($_REQUEST['FECHA_REQ_CTE'],  0, 4).'-'.substr($_REQUEST['FECHA_REQ_CTE'],  4, 2).'-'.substr($_REQUEST['FECHA_REQ_CTE'],  6, 2).' '.substr($_REQUEST['FECHA_REQ_CTE'],  -12, 8);
$Fecha_Req_Aut = substr($_REQUEST['FECHA_REQ_AUT'],  0, 4).'-'.substr($_REQUEST['FECHA_REQ_AUT'],  4, 2).'-'.substr($_REQUEST['FECHA_REQ_AUT'],  6, 2).' '.substr($_REQUEST['FECHA_REQ_AUT'],  -12, 8);
$Fecha_Rsp_Aut = substr($_REQUEST['FECHA_RSP_AUT'],  0, 4).'-'.substr($_REQUEST['FECHA_RSP_AUT'],  4, 2).'-'.substr($_REQUEST['FECHA_RSP_AUT'],  6, 2).' '.substr($_REQUEST['FECHA_RSP_AUT'],  -12, 8);
$Fecha_Rsp_Cte = substr($_REQUEST['FECHA_RSP_CTE'],  0, 4).'-'.substr($_REQUEST['FECHA_RSP_CTE'],  4, 2).'-'.substr($_REQUEST['FECHA_RSP_CTE'],  6, 2).' '.substr($_REQUEST['FECHA_RSP_CTE'],  -12, 8);
$Resultado_PAYW = $_REQUEST['RESULTADO_PAYW'];
$Resultado_Aut = $_REQUEST['RESULTADO_AUT'];
$Codigo_Payw = $_REQUEST['CODIGO_PAYW'];
$Codigo_Aut = $_REQUEST['CODIGO_AUT'];
$Texto = $_REQUEST['TEXTO'];
$TarjetaHabiente = $_REQUEST['TARJETAHABIENTE'];
$Banco_Emisor = $_REQUEST['BANCO_EMISOR'];
$Marca_Tarjeta = $_REQUEST['MARCA_TARJETA'];
$Tipo_Tarjeta = $_REQUEST['TIPO_TARJETA'];
		
$termList = preg_split("/\s+/", trim($_REQUEST['RESULTADO_PAYW']));

foreach($termList as $term) { $texto_arreglo = htmlspecialchars($term)."<br />"; }		

echo '
<input type="button" name="imprimir" value="Imprimir" onclick="window.print();">
';

///Cabecera y traduccion de mensajes 	

if($Resultado_PAYW == 'A'){
	echo "
<center>Favor de imprimir esta respuesta para cualquier aclaraci&oacute;n.</center>
<center>Tu transacci&oacute;n se ha completado satisfactoriamente.</center>
<center>El movimiento de tu pago lo podr&aacute;s observar en 24 horas.</center>";
	$Resp_Resultado_Payworks = "(A)Aprobada";

}elseif($Resultado_PAYW == 'D'){
echo "
<center>Favor de imprimir esta respuesta para cualquier aclaraci&oacute;n.</center>
<center>Tu transacci&oacute;n fue Declinada por el banco.</center>
<center>Consulta los detalles.</center>";
	$Resp_Resultado_Payworks = "(D)Declinada";

}elseif($Resultado_PAYW == 'R'){

echo "
<center>Favor de imprimir esta respuesta para cualquier aclaraci&oacute;n.</center>
<center>Tu transacci&oacute;n fue Rechazada por el banco.</center>
<center>Consulta los detalles.</center>";
	$Resp_Resultado_Payworks = "(R)Rechazada";

	
}elseif($Resultado_PAYW == 'T'){

echo "
<center>Favor de imprimir esta respuesta para cualquier aclaraci&oacute;n.</center>
<center>Tu transacci&oacute;n No pudo ser completada por perida de conexi&oacute;n.</center>
<center>Consulta los detalles.</center>";
	$Resp_Resultado_Payworks = "(T)Sin Respuesta del autorizador";

}		

		

echo "
<style>
table {
	border: 1px solid #ddd;
	border-collapse: collapse;
}

tr:nth-child(even) 
		{
			background-color: #f2f2f2;
		}

</style>
<table width='605'  >
	<tr>
		<td colspan='2'><img src='../im/logo.jpg' width='600'/></td>
	</tr>
	<tr>
		<td colspan='2'><center><b>Datos de Respuesta de Pago por Tarjeta de Cr&eacute;dito</b><br />
			".$nombre_colegio."</center></td>
	</tr>		
	<tr>
		<td width='400'><b>Id Afiliaci&oacute;n : </b></td><td> ".$Id_Afiliacion." </td>
	</tr>
	<tr>
		<td><b>Referencia : </b></td><td> ".$Referencia." </td>
	</tr>
	<tr>
		<td><b>Numero de Control : </b></td><td> ".$Numero_Control." </td>
	</tr>
	<tr>
		<td><b>Fecha y Hora de Envi&oacute; Transacci&oacute;n : </b></td><td> ".$Fecha_Req_CTE." </td>
	</tr>
	<tr>
		<td><b>Fecha y Hora de Inicio Transacci&oacute;n : </b></td><td> ".$Fecha_Req_Aut." </td>
	</tr>
	<tr>
		<td><b>Fecha y Hora de Autorizaci&oacute;n Transacci&oacute;n : </b></td><td> ".$Fecha_Rsp_Aut." </td>
	</tr>
	<tr>
		<td><b>Fecha y Hora de Respuesta Transacci&oacute;n :</b></td><td> ".$Fecha_Rsp_Cte." </td>
	</tr>
	<tr>
		<td><b>Resultado del Proceso de Payworks : </b></td><td> ".$Resp_Resultado_Payworks." </td>
	</tr>
	<tr>
		<td><b>C&oacute;digo Payworks : </b></td><td> ".$Codigo_Payw." </td>
	</tr>
	<tr>
		<td><b>C&oacute;digo Autorizaci&oacute;n : </b></td><td> ".$Codigo_Aut." </td>
	</tr>
	<tr>
		<td><b>Texto : </b></td><td> ".utf8_decode($Texto)." </td>
	</tr>
	<tr>
		<td><b>Tarjetahabiente : </b></td><td> ".$TarjetaHabiente." </td>
	</tr>
	<tr>
		<td><b>Banco Emisor : </b></td><td> ".$Banco_Emisor." </td>
	</tr>
	<tr>
		<td><b>Marca de la Tarjeta : </b></td><td> ".$Marca_Tarjeta." </td>
	</tr>
	<tr>
		<td><b>Tipo de Tarjeta : </b></td><td> ".$Tipo_Tarjeta." </td>
	</tr>
	<tr>
		<td colspan='2' align='center' style=''><b>Detalle de Pago</b></td>
	</tr>
";

?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<?php

mysql_query("UPDATE 
					payworks 
				SET
					Fecha_Req_Cte='$Fecha_Req_CTE',
					Fecha_Req_Aut='$Fecha_Req_Aut',
					Fecha_Rsp_Aut='$Fecha_Rsp_Aut',
					Fecha_Rsp_Cte='$Fecha_Rsp_Cte',
					Resultado_Payw='$Resultado_PAYW',
					Resultado_Aut='$Resultado_Aut',
					Codigo_Payw='$Codigo_Payw',
					Codigo_Aut='$Codigo_Aut',
					Texto='$Texto',
					TarjetaHabiente='$TarjetaHabiente',
					Banco_Emisor='$Banco_Emisor',
					Marca_Tarjeta='$Marca_Tarjeta',
					Tipo_Tarjeta='$Tipo_Tarjeta',
					Referencia = '$Referencia'
				WHERE
					Reference3D='$Numero_Control'
				",$link)or die ("UPDATE 
					payworks 
				SET
					Fecha_Req_Cte='$Fecha_Req_CTE',
					Fecha_Req_Aut='$Fecha_Req_Aut',
					Fecha_Rsp_Aut='$Fecha_Rsp_Aut',
					Fecha_Rsp_Cte='$Fecha_Rsp_Cte',
					Resultado_Payw='$Resultado_PAYW',
					Resultado_Aut='$Resultado_Aut',
					Codigo_Payw='$Codigo_Payw',
					Codigo_Aut='$Codigo_Aut',
					Texto='$Texto',
					TarjetaHabiente='$TarjetaHabiente',
					Banco_Emisor='$Banco_Emisor',
					Marca_Tarjeta='$Marca_Tarjeta',
					Tipo_Tarjeta='$Tipo_Tarjeta',
					Referencia = '$Referencia'
				WHERE
					Reference3D='$Numero_Control'".mysql_error()); 



/// Seleccionaremos todos los registros que sean en el Reference3D y los pondremos en STATUS de acuerdo a el resultado de el $_REQUEST
	$rst_ = mysql_query("SELECT 
									* 
								FROM
									payworks, alumnos
								WHERE
									Reference3D = '$Numero_Control'	
								  AND
								  	payworks.clave = alumnos.alumno 								
								", $link) or die("
							SELECT 
									* 
								FROM
									payworks, alumnos
								WHERE
									Reference3D = '$Numero_Control'	
								  AND
								  	payworks.clave = alumnos.alumno ".mysql_error());

												

	while ($rst_a = mysql_fetch_array($rst_ ))	{

	/// Actualizamos los conceptos que fueron pagados con este movimiento
	/// Proceso de válidacion de respuesta		
	if($Resultado_PAYW == 'A'){
			$pagado='S';
	}else{
		$pagado='N';
	}
	$alumno = $rst_a['clave'];
	$cargo = $rst_a['concepto'];
	$concepto = $rst_a['concepto'];
	$New_Card = substr($rst_a['Card'], -4);
	$mes = substr($rst_a['periodo_mes'], -2, 2);
	$periodo = "20".substr($rst_a['periodo_mes'], 0, 2);
	$familia = $rst_a['familia'];


	mysql_query("
		UPDATE
				payworks
			SET
				Card = '$New_Card',
				familia = '$familia'
			WHERE
				Reference3D = '$Numero_Control'		
	", $link) or die ("
UPDATE
				payworks
			SET
				Card = '$New_Card',
				familia = '$familia'
			WHERE
				Reference3D = '$Numero_Control'	
	".mysql_error());

			

			mysql_query("UPDATE 
								cargos 
							SET
								pagado='$pagado'
							WHERE
								alumno = $alumno
							AND
								concepto = $concepto
							AND
								mes = $mes
							AND
								periodo = $periodo
				 ",$link)or die ("
						UPDATE 
								cargos 
							SET
								pagado='$pagado'
							WHERE
								alumno = $alumno
							AND
								concepto = $concepto
							AND
								mes = $mes
							AND
								periodo = $periodo
				".mysql_error()); 

			
echo "
	<tr>
		<td colspan='2' align='center'><b>Clave:</b> $rst_a[alumno]</td>
	</tr>
	<tr>
		<td colspan='2' align='center'><b>Alumno:</b> $rst_a[nombre] $rst_a[apellido_paterno] $rst_a[apellido_materno]</td>
	</tr>
	<tr>	
		<td colspan='2' align='center'><b>Concepto:</b> $rst_a[concepto]</td>
	</tr>
	<tr>
		<td colspan='2' align='center'><b>Periodo Mes:</b> $rst_a[periodo_mes]</td>
	</tr>
	<tr>
		<td colspan='2' align='center'><b>Total:</b> $rst_a[total]</td>
	</tr>
	";			
	}	
	

	echo "
	<tr>
		<td colspan=2>&nbsp;</td>
	</tr>	
</table>"	;								

?>