<?php
require_once("funciones.php");
include('../functions.php');
include("../config.php");

//mysql_query("SET lc_time_names = 'es_MX';");

$acumCargos = 0;
$acumRecargos = 0;

///Aqui obtenemos los adeudos por familias a detalle
if(isset($_POST['familia'])){
	
	$familia = AdeudosFamilia($_POST['familia']);

	$html= "
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'></script>
<link rel='stylesheet' href='style.css' />
<!--<button name='Enviar' id='Enviar' value='Enviar' onclick='Seleccionado()'> Enviar Recordatorio </button>-->
<br />
<br />
<button name='Todo' id='Todo' value='Todo' onclick='SeleccionarTodo()'> Marcar Todo </button>
<button name='Ninguno' id='Ninguno' value='Ninguno' onclick='SeleccionarNinguno()' disabled> Marcar Ninguno </button>
<br/ >
<br/ >
<style>
 #TablaAdeudos tr:nth-child(even) {background: #f2f2f2} 
 #TablaAdeudos tr:nth-child(odd) {background: #FFF}
</style>
	<table border='1' name='TablaAdeudos' id='TablaAdeudos'>
				<thead>
					<tr style='background-color: #d9d9d9; color: #6e6e6e;'>
						<th class='nosort'>.</th>
						<th >Nombre</th>
						<th >Concepto</th>
						<th>Mes</th>
						<th>Ciclo</th>
						<th >Importe</th>
						<th >Intereses</th>
					</tr>
				</thead>
				<tbody>	";
	foreach($familia as $indice => $registro){
		
		$Concepto = $registro['Concepto'];
		$Cargos = $registro['cargos'];
		$Abonos = $registro['abonos'];
		$Periodo = $registro['periodo'];
		$M_Relativo = $registro['mes'];
		$Alumno = $registro['alumno'];
		$periodo_pago = $registro['periodo'];
		$familia_ = $registro['familia'];
		$alumno = $registro['alumno'];
		$NombreAlumno = $registro['NombreAlumno'];
		$Mes = $registro['Mes'];
		$Periodo_P = $registro['periodo']."-".($registro['periodo']+1);
		$Importe = number_format( $Cargos - $Abonos,2);		
		$Intereses = number_format($registro['Intereses'],2);
		
		if ($Mes == "Jan") {
    			$Mes_c = "1";
			} elseif ($Mes == "Feb") {
    			$Mes_c = "2";
			} elseif ($Mes == "Mar") {
    			$Mes_c = "3";
			} elseif ($Mes == "Apr") {
    			$Mes_c = "3";
			} elseif ($Mes == "Mar") {
    			$Mes_c = "4";
			} elseif ($Mes == "May") {
    			$Mes_c = "5";
			} elseif ($Mes == "Jun") {
    			$Mes_c = "6";
			} elseif ($Mes == "Jul") {
    			$Mes_c = "7";
			} elseif ($Mes == "Aug") {
    			$Mes_c = "8";
			} elseif ($Mes == "Sep") {
    			$Mes_c = "9";
			} elseif ($Mes == "Oct") {
    			$Mes_c = "10";
			} elseif ($Mes == "Nov") {
    			$Mes_c = "11";
			} elseif ($Mes == "Dec") {
    			$Mes_c = "12";
			}
		 
		 
		 
		$conceptoID = mysql_query("select concepto from conceptos where nombre = '$Concepto'", $link)or die(mysql_error());;
			$row = mysql_fetch_array($conceptoID);
	
    						$conceptoIDF = $row["concepto"];
		
		//echo $conceptoIDF."|"; 
		
		$Importe_Enviar = $Cargos - $Abonos;
		$Intereses_Enviar = $registro['Intereses'];
		
	  //$recargos = fCalculaRecargos (getDate(), $conceptoIDF, $Cargos - $Abonos, "periodo", 	$Mes_c, 	$alumno, "", $registro['periodo'] ) ;
		//echo "Concepto ".$conceptoIDF." Importe ".$Cargos." Abonos ".$Abonos." Periodo ".$periodo." MesPago ".$Mes_c." alumno ".$alumno." periodo_pago ".$registro['periodo']."<br /";
		//$recargos = fCalculaRecargos (getDate(), $Concepto, $Cargos - $Abonos, $Periodo, $M_Relativo, $Alumno, "", $periodo_pago ) ;
		//					<td>&nbsp;&nbsp;$recargos&nbsp&nbsp;</td>
		
		$html .= "<tr style='color: #000; font-style: normal;  font-weight: normal;'>
					<td><input type='checkbox' id='$familia_|$alumno|$Concepto|$Mes|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' name='$familia_|$alumno|$Concepto|$Mes|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' /></td>
					<td>&nbsp;&nbsp;$NombreAlumno&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Concepto&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Mes&nbsp;&nbsp;</td>
					<td style='text-align: center'>&nbsp;&nbsp;$periodo_pago&nbsp;&nbsp;</td>
					<td>$&nbsp;&nbsp;$Importe&nbsp;&nbsp;</td>
					<td>$&nbsp;&nbsp;$Intereses&nbsp;&nbsp;</td>
				</tr>";
				$acumCargos =$acumCargos + $Importe_Enviar;
				$acumRecargos = $acumRecargos + $Intereses_Enviar;
	}
	$html.="</tbody>
			<tr style='color: #000;'>
				<td colspan='4'>&nbsp;</td>
				<td colspan='2' align='right'>&nbsp;&nbsp;<b>Subtotal:</b>$&nbsp;&nbsp;".number_format($acumCargos,2)."&nbsp;&nbsp;</td>
				<td>$&nbsp;&nbsp;".number_format($acumRecargos,2)."</td>
			</tr>
			<tr style='color: #000;'>
				<td colspan='7' align='right'>
					<b>Total:</b>&nbsp;$&nbsp;&nbsp;".number_format($acumRecargos+$acumCargos,2)."
				</td>
			</tr>
	</table>
	<script type='text/javascript'>
var st1 = new SortableTable(document.getElementById('table-1'));

</script>
"; 
	//$respuesta = array("html" => $html);
	echo $html;
}


//Aquí obtenemos los adeudos por familias TOTALES
if(isset($_POST['Total'])){
	
	$Total = AdeudosTotal($_POST['Total']);

	$html= "
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'></script>	
<script src='sortable.js'></script>	
<link rel='stylesheet' href='style.css' />
<!--<button name='Enviar' id='Enviar' value='Enviar' onclick='Seleccionado()'> Enviar Recordatorio </button>-->
<br />
<br />
<button name='Todo' id='Todo' value='Todo' onclick='SeleccionarTodo()'> Marcar Todo </button>
<button name='Ninguno' id='Ninguno' value='Ninguno' onclick='SeleccionarNinguno()' disabled> Marcar Ninguno </button>
<br/ >
<br/ >
<style>
 #TablaAdeudos tr:nth-child(even) {background: #f2f2f2} 
 #TablaAdeudos tr:nth-child(odd) {background: #FFF}
</style>
	<table border='1' name='TablaAdeudos' id='TablaAdeudos'>
				<thead>
					<tr style='background-color: #d9d9d9; color: #6e6e6e;'>
						<th class='nosort'>.</th>
						<th >Nombre</th>
						<th >Concepto</th>
						<th>Mes</th>
						<th>Ciclo</th>
						<th >Importe</th>
						<th >Intereses</th>
					</tr>
				</thead>	
				<tbody>	
					";
	foreach($Total as $indice => $registro){
		
		$Concepto= $registro['Concepto'];
		$Cargos = $registro['cargos'];
		$Abonos = $registro['abonos'];
		$Periodo = $registro['periodo'];
		$M_Relativo = $registro['mes'];
		$Alumno = $registro['alumno'];
		$periodo_pago = $registro['periodo'];
		$familia_ = $registro['familia'];
		$alumno = $registro['alumno'];
		$NombreAlumno = $registro['NombreAlumno'];
		$Mes = $registro['Mes'];
		$Periodo_P = $registro['periodo']."-".($registro['periodo']+1);
		$Importe = number_format($Cargos - $Abonos ,2);
		$Intereses = number_format($registro['Intereses'],2);
		
		/////
		
		
		/////
		$Importe_Enviar = $Cargos - $Abonos;
		$Intereses_Enviar = $registro['Intereses'];
	
		//$recargos = fCalculaRecargos (getDate(), $Concepto, $Cargos - $Abonos, $Periodo, $M_Relativo, $Alumno, "", $periodo_pago ) ;
		
		$html .= "<tr style='color: #000; font-style: normal;  font-weight: normal;'>
					<td><input type='checkbox' id='$familia_|$alumno|$Concepto|$Mes|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' name='$familia_|$alumno|$Concepto|$Mes|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' /></td>
					<td>&nbsp;&nbsp;$NombreAlumno&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Concepto&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Mes&nbsp;&nbsp;</td>
					<td style='text-align: center'>&nbsp;&nbsp;$periodo_pago&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$&nbsp;$Importe&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$&nbsp;$Intereses&nbsp;&nbsp;</td>
				</tr>";
				$acumCargos =$acumCargos + $registro['cargos'];
				$acumRecargos = $acumRecargos + $registro['Intereses'];
	}
	$html.="
		</tbody>
			<tr style='color: #000;'>
				<td colspan='4'>&nbsp;</td>
				<td colspan='2' align='right'>&nbsp;&nbsp;<b>Total:</b>$&nbsp;&nbsp;".number_format($acumCargos,2)."&nbsp;&nbsp;</td>
				<td>$&nbsp;&nbsp;".number_format($acumRecargos,2)."</td>
			</tr>
			<tr style='color: #000;'>
				<td colspan='7' align='right'>
					<b>Total:</b>&nbsp;$&nbsp;&nbsp;".number_format($acumRecargos+$acumCargos,2)."
				</td>
			</tr>
	</table>
	"; 
	//$respuesta = array("html" => $html);
	echo $html;
}


//Aquí obtenemos los adeudos por familias Mas deudoras de mayor a menor
if(isset($_POST['Acumulados'])){
	
	$Acumulados = AdeudosTotal($_POST['Acumulados']);

	$html= "
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'></script>	
<script src='sortable.js'></script>	
<link rel='stylesheet' href='style.css' />
<!--<button name='Enviar' id='Enviar' value='Enviar' onclick='Seleccionado()'> Enviar Recordatorio </button>-->
<br />
<br />
<button name='Todo' id='Todo' value='Todo' onclick='SeleccionarTodo()'> Marcar Todo </button>
<button name='Ninguno' id='Ninguno' value='Ninguno' onclick='SeleccionarNinguno()' disabled> Marcar Ninguno </button>
<br/ >
<br/ >
	<table border='1' name='TablaAdeudos' id='TablaAdeudos'>
				<thead>
					<tr>
						<th class='nosort'>.</th>
						<th >Nombre</th>
						<th >Concepto</th>
						<th>Mes</th>
						<th>Ciclo</th>
						<th >Importe</th>
						<th >Intereses</th>
					</tr>
				</thead>	
				<tbody>
";
	foreach($Acumulados as $indice => $registro){
		
		$Concepto= $registro['Concepto'];
		$Cargos = $registro['cargos'];
		$Abonos = $registro['abonos'];
		$Periodo = $registro['periodo'];
		$M_Relativo = $registro['mes'];
		$Alumno = $registro['alumno'];
		$periodo_pago = $registro['periodo'];
		$familia_ = $registro['familia'];
		$alumno = $registro['alumno'];
		$NombreAlumno = $registro['NombreAlumno'];
		$Mes = $registro['Mes'];
		$Periodo_P = $registro['periodo']."-".($registro['periodo']+1);
		$Importe = number_format($Cargos - $Abonos,2);
		$Intereses = number_format($registro['Intereses'],2);
		
		$Importe_Enviar = $Cargos - $Abonos;
		$Intereses_Enviar = $registro['Intereses'];
		
		$recargos = fCalculaRecargos (getDate(), $Concepto, $Cargos - $Abonos, $Periodo, $M_Relativo, $Alumno, "", $periodo_pago ) ;
		
		$html .= "<tr style='color: #000; font-style: normal;  font-weight: normal;'>
					<td><input type='checkbox' id='$familia_|$alumno|$Concepto|$Mes|$M_Relativo|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' name='$familia_|$alumno|$Concepto|$Mes|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' /></td>
					<td>&nbsp;&nbsp;$NombreAlumno&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Concepto&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Mes&nbsp;&nbsp;</td>
					<td style='text-align: center'>&nbsp;&nbsp;$periodo_pago&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Importe&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$recargos&nbsp;&nbsp;</td>
				</tr>";
				$acumCargos =$acumCargos + $registro['cargos'];
				$acumRecargos = $acumRecargos + $recargos;
	}
	$html.="
		</tbody>	
			<tr>
				<td colspan='7'>&nbsp;</td>
				<td colspan='2' align='right'>&nbsp;&nbsp;<b>Total:</b>&nbsp;&nbsp;".number_format($acumCargos,2)."&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;$acumRecargos</td>
			</tr>
			<tr style='color: #000;'>
				<td colspan='7' align='right'>
					<b>Total:</b>&nbsp;$&nbsp;&nbsp;".number_format($acumRecargos+$acumCargos,2)."
				</td>
			</tr>
		</table>"; 
	//$respuesta = array("html" => $html);
	echo $html;
}


////////////////Este es 

if(isset($_POST['Actualizar'])){
	
	$Actualizar = Actualizar($_POST['Actualizar']);
	foreach($Actualizar as $indice => $registro){
	$Update= $registro['Mensaje'];	
		if($Update == 1){
			$html = "<p>Actualizado correctamente.</p>";
		}else{
			$html = "<p>Ocurrio un error, intente nuevamente.</p>";
		}
	}
	echo $html;
}

if(isset($_POST['Enviar'])){
	
	$Enviar = Enviar($_POST['Enviar']);
	
	$html= "";
	
	foreach($Enviar as $indice => $registro){

$html.= "
<!DOCTYPE html>
<html>
  <head>
    <meta  content='text/html; charset=UTF-8'  http-equiv='content-type'>
    <style>
thead {
    background-color:#eee;
    color:#666666;
    font-weight: bold;
    cursor: default;
	border-top-left-radius: 2px;
	border-top-right-radius: 2px;
}

th{
	border-top-left-radius: 2px;
	border-top-right-radius: 2px;
}
      
 tr:nth-child(even) {background: #f2f2f2} 
 tr:nth-child(odd) {background: #FFF}
    </style>
  </head>
  <body>
    <p  align='center'><img  src=''></p>
    <p  align='center'>Estado de cuenta de la familia
      <!--?=$familia;?-->.</p>
    <br>
    <br>
    <br>
    <table  style='width: 100%'  cellpading='0'  border='1'  cellspacing='0'>
      <thead>
        <tr>
          <td  style='width: 24px;'><br>
          </td>
          <td  align='center'>&nbsp;&nbsp;Alumno&nbsp;&nbsp; </td>
          <td  align='center'>&nbsp;&nbsp;Nombre&nbsp;&nbsp; </td>
          <td  align='center'>&nbsp;&nbsp;Concepto&nbsp;&nbsp; </td>
          <td  align='center'>&nbsp;&nbsp;Mes&nbsp;&nbsp; </td>
          <td  align='center'>&nbsp;&nbsp;Periodo&nbsp;&nbsp; </td>
          <td  align='center'>&nbsp;&nbsp;Importe&nbsp;&nbsp; </td>
          <td  align='center'>&nbsp;&nbsp;Intereses&nbsp;&nbsp; </td>
          <td  style='width: 24px;'><br>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
        </tr>
        <tr>
          <td><br>
          </td>
          <td  colspan='5'><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
          <td><br>
          </td>
        </tr>
      </tbody>
    </table>
    <p> Estado de cuenta de la familia
      <!--?=$familia;?-->, en caso de haber realizado su pago, haga caso omiso a
      este correo. </p>
    
  </body>
</html>
";
		}
	echo $html;
}




if(isset($_POST['filtro'])){
	
	$filtro = AdeudosFiltro($_POST['filtro']);
//echo $_POST['filtro'];
	$html= "
	
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js'></script>
<link rel='stylesheet' href='style.css' />
<!--<button name='Enviar' id='Enviar' value='Enviar' onclick='Seleccionado()'> Enviar Recordatorio </button>-->
<br />
<br />
<button name='Todo' id='Todo' value='Todo' onclick='SeleccionarTodo()'> Marcar Todo </button>
<button name='Ninguno' id='Ninguno' value='Ninguno' onclick='SeleccionarNinguno()' disabled> Marcar Ninguno </button>
<br/ >
<br/ >
<style>
 #TablaAdeudos tr:nth-child(even) {background: #f2f2f2} 
 #TablaAdeudos tr:nth-child(odd) {background: #FFF}
</style>
	<table border='1' name='TablaAdeudos' id='TablaAdeudos'>
				<thead>
					<tr style='background-color: #d9d9d9; color: #6e6e6e;'>
						<th class='nosort'>.</th>
						<th >Nombre</th>
						<th >Concepto</th>
						<th >Mes</th>
						<th >Importe</th>
						<th >Intereses</th>
					</tr>
				</thead>
				<tbody>	";
	foreach($filtro as $indice => $registro){
		
		$Concepto= $registro['Concepto'];
		$Cargos = $registro['cargos'];
		$Abonos = $registro['abonos'];
		$Periodo = $registro['periodo'];
		$M_Relativo = $registro['mes'];
		$Alumno = $registro['alumno'];
		$periodo_pago = $registro['periodo'];
		$familia_ = $registro['familia'];
		$alumno = $registro['alumno'];
		$NombreAlumno = $registro['NombreAlumno'];
		$Mes = $registro['Mes'];
		$Periodo_P = $registro['periodo']."-".($registro['periodo']+1);
		$Importe = number_format( $Cargos - $Abonos,2);		
		$Intereses = number_format($registro['Intereses'],2);
		
		$Importe_Enviar = $Cargos - $Abonos;
		$Intereses_Enviar = $registro['Intereses'];
		
		
		//$recargos = fCalculaRecargos (getDate(), $Concepto, $Cargos - $Abonos, $Periodo, $M_Relativo, $Alumno, "", $periodo_pago ) ;
		
		$html .= "<tr style='color: #000; font-style: normal;  font-weight: normal;'>
					<td><input type='checkbox' id='$familia_|$alumno|$Concepto|$Mes|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' name='$familia_|$alumno|$Concepto|$Mes|$Periodo_P|$Importe_Enviar|$Intereses_Enviar;' /></td>
					<td>&nbsp;&nbsp;$NombreAlumno&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Concepto&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;$Mes&nbsp;&nbsp;</td>
					<td>$&nbsp;&nbsp;$Importe&nbsp;&nbsp;</td>
					<td>$&nbsp;&nbsp;$Intereses&nbsp;&nbsp;</td>
				</tr>";
				$acumCargos =$acumCargos + $Importe_Enviar;
				$acumRecargos = $acumRecargos + $Intereses_Enviar;
	}
	$html.="</tbody>
			<tr style='color: #000;'>
				<td colspan='4'>&nbsp;</td>
				<td colspan='2' align='right'>&nbsp;&nbsp;<b>Subtotal:</b>$&nbsp;&nbsp;".number_format($acumCargos,2)."&nbsp;&nbsp;</td>
				<td>$&nbsp;&nbsp;".number_format($acumRecargos,2)."</td>
			</tr>
			<tr style='color: #000;'>
				<td colspan='7' align='right'>
					<b>Total:</b>&nbsp;$&nbsp;&nbsp;".number_format($acumRecargos+$acumCargos,2)."
				</td>
			</tr>
	</table>
"; 
	//$respuesta = array("html" => $html);
	echo $html;
}

?>