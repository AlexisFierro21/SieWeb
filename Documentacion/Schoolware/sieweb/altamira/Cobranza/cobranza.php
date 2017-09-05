<?php
include('../config.php');
include('../functions.php');
mysql_query("SET NAMES 'utf8'");
$result=mysql_query("select DISTINCT familia, CONCAT(apellido_paterno,' ', apellido_materno) AS nombre_familia from alumnos  WHERE activo = 'A' and nuevo_ingreso != 'P' ORDER BY 2;",$link)or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Estado de cuenta</title>
		<!-- Date: 2016-02-10 -->
    	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>	
    	  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  			<script src="//code.jquery.com/jquery-1.12.3.js"></script>
  			<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  			
<link rel='stylesheet' href='style.css' />
	</head>
	<body>
		<p> Bienvenido al sistema de envi&oacute; de estados de cuenta web, siga las instrucciones para poder enviar sus recordatorios a los padres de familia.</p>
		<br />
<p id="result_envio" name="result_envio"></p>					
						
						<input name="formatoMailSeleccionado" id="formatoMailSeleccionado" type="hidden" value="" />    


            
<div id="tabs">
  <ul>
    <li id="pestanya1" name="pestanya1"><a href="#tabs-1" style="font-size: 12px;">1.- Actualizar Datos</a></li>
    <li id="pestanya2" name="pestanya2"><a href="#tabs-2" style="font-size: 12px;" onclick="seleccionarFormato();" name="shower2" id="shower2">2.- Formatos</a></li>
    <li id="pestanya3" name="pestanya3"><a href="#tabs-3" style="font-size: 12px;">3.- Selecci&oacute;n de Adeudos</a></li>
    <li id="pestanya4" name="pestanya4"><a href="#tabs-4" style="font-size: 12px;" onclick="MuestraResultados()">4.- Vista previa del recordatorio</a></li>
    <li id="pestanya5" name="pestanya5"><a href="#tabs-5" style="font-size: 12px;">5.- Resultados</a></li>
  </ul>
  <div id="tabs-1">
    <p>Haga click en el bot&oacute;n siguiente para poder continuar</p>
    <br />
    <button name="Actualizar" id="Actualizar" onclick="Actualizar()"> Actualizar Adeudos </button>
    <div id="actualizar_cargando" name="actualizar_cargando"></div>
  </div>
  <div id="tabs-2">

  		  		<!--  Div que muestra el formato para agregar una nueva carta -->
				<div id="Carta" name="Carta"><img src='loading.gif'></img></div>		
				<!-- Div que muestra la presentación previa de un formato guardado -->
				<div id='MensajeGuardado' name='MensajeGuardado' class='MensajeGuardado'></div>
  </div>
  <div id="tabs-3">
  			Selecciona la manera que deseas ver los adeudos
					<br />
				
				<table>
				  <tr>
				  	<td colspan="100%" >
				  		<br />
				  		<p style="text-color:black; color: black; text-align: left;">Solo Adeudos Vencidos: <input type="checkbox" id="adeudos" name="adeudos"  checked="checked"/></p>
				  	</td>
				  </tr> 
				  <tr>
				  	<td>	
					&nbsp;&nbsp;<p style="color:black;"><input type="radio" name="filtro" value="AdeudosTotal" checked="checked" checked>&nbsp;Total Adeudos.&nbsp;&nbsp;</p>  
					<br />
					<br />
					</td>
					<td>&nbsp;</td>
					<td>	        
          			&nbsp;&nbsp;<p style="color:black;"><input type="radio" name="filtro" value="AdeudosFamilia">&nbsp;Selecci&oacute;n por familia.&nbsp;&nbsp;</p>
          	<br />
					<select name='familia' id='familia' class='familia'>
						<option value=""></option>
							<?
								while($row_familias = mysql_fetch_array($result))
									{
	 									echo "<option value='".$row_familias["familia"]."'>".$row_familias["nombre_familia"]."</option>";
									}
							?>
					</select>
					
					</td>
					<td>&nbsp;</td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;<p style="color:black;"><input type="radio" name="filtro" value="AdeudosFiltrado">&nbsp;Filtrado.&nbsp;&nbsp;</p>
						<br />
						<table>
							<thead>
								<tr style="color: #000;">
									<th>
										Secci&oacute;n
									</th>
									<th>
										Grado
									</th>
									<th>
										Grupo
									</th>
								</tr>
							</thead>
							<tbody>
								<td>
									<select id='seccion_n' name='seccion_n' >
										<option value=""></option>
											<?
												$result=mysql_query("Select distinct seccion, nombre  from secciones where ciclo = '$periodo_actual';
												",$link)or die("Este error".mysql_error());

													while($row = mysql_fetch_array($result))
														{
	 														echo '<option value="'.$row['seccion'].'" >&nbsp;&nbsp;'.$row['nombre'].'&nbsp;&nbsp;</option>';
														}
											?>
										
									</select>
								</td>
								<td>
									
										<select id='grado_n' name='grado_n'>
											<option value=""></option>
											<?
												$result=mysql_query("Select distinct grado, nombre  from grados where ciclo = '$periodo_actual';
												",$link)or die("Este error".mysql_error());

													while($row = mysql_fetch_array($result))
														{
	 														echo '<option value="'.$row['grado'].'" >&nbsp;&nbsp;'.$row['nombre'].'&nbsp;&nbsp;</option>';
														}
											?>
										</select>
									<!--<div id="grado_n" name="grado_n"></div>-->
								</td>
								<td>
									<div >
										<select id='grupo_n' name='grupo_n'>
											<option value=""></option>
											<?
												$result=mysql_query("Select distinct grupo  from grupos where ciclo = '$periodo_actual';
												",$link)or die("Este error".mysql_error());

													while($row = mysql_fetch_array($result))
														{
	 														echo '<option value="'.$row['grupo'].'" >&nbsp;&nbsp;'.$row['grupo'].'&nbsp;&nbsp;</option>';
														}
											?>
										</select>
									</div>
								</td>
								<tr>
									<td colspan="100%">
										<button onclick="buscarFiltro();">Buscar</button>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				  </tr>	 
				</table>
				
					<br />
					<br />
					<br />
					<div id="loading" name="loading" class="loading"></div>
          			<div id="log" name="log" class="log"></div>  
  </div>
  <div id="tabs-4">
  				
  
          <?
          
          $M_actual_e = date("n");
		  
		  switch ($M_actual_e) {
    			case 0:
        			echo "Error";
        		break;
    		case 1:
        			$M_actual_e = "Enero";
        		break;
    		case 2:
        			$M_actual_e = "Febrero";
        	break;
			case 3:
        			$M_actual_e = "Marzo";
        	break;
			case 4:
        			$M_actual_e = "Abril";
        	break;
			case 5:
        			$M_actual_e = "Mayo";
        	break;
			case 6:
        			$M_actual_e = "Junio";
        	break;
			case 7:
        			$M_actual_e = "Julio";
        	break;
			case 8:
        			$M_actual_e = "Agosto";
        	break;
			case 9:
        			$M_actual_e = "Septiembre";
        	break;
			case 10:
        			$M_actual_e = "Octubre";
        	break;
			case 11:
        			$M_actual_e = "Noviembre";
        	break;
			case 12:
        			$M_actual_e = "Diciembre";
        	break;
}
		  
          
          ?>
  				<br />	
  				<br />
  				<button name="Enviar" id="Enviar" value="Enviar" onclick="Seleccionado()"> Enviar Recordatorio </button>
  				<br />
  				<div id="variables" name="variables" class="variables"></div>
  				
 <!-- Demo --> 				
 <div id="baner" name="baner"><p>Baner</p></div>
  <br>
    <p align="right">Fecha Actual</p>
    <br>
    <p>Familia</p>
    <p align="left"><div id="m_entrada_select" name="m_entrada_select"></div></p>
    <p><div name="Preview_Formato" id="Preview_Formato"></div></p>
    <br>
    <br>
    <p>Nombre Alumno</p>
    <br>
    <table  style="border-collapse: collapse; color: #000;"  width="100%">
      <tbody>
        <tr style="background-color: #b3b3b3; color: #262626; font-weight: bold; text-align: center; border: 1px;">
          <td style="width: 25.3333px;"><br>
          </td>
          <td>Concepto<br>
          </td>
          <td>Mes<br>
          </td>
          <td>Importe<br>
          </td>
          <td>Recargos</td>
          <td style="width: 25.3333px;"><br>
          </td>
        </tr>
        <tr style="color: #000; text-align: center;">
          <td><br>
          </td>
          <td>Concepto A Pagar 15-16
          </td>
          <?
          
          $M_actual_e = date("n");
		  
		  switch ($M_actual_e) {
    			case 0:
        			echo "Error";
        		break;
    		case 1:
        			$M_actual_e = "Enero";
        		break;
    		case 2:
        			$M_actual_e = "Febrero";
        	break;
			case 3:
        			$M_actual_e = "Marzo";
        	break;
			case 4:
        			$M_actual_e = "Abril";
        	break;
			case 5:
        			$M_actual_e = "Mayo";
        	break;
			case 6:
        			$M_actual_e = "Junio";
        	break;
			case 7:
        			$M_actual_e = "Julio";
        	break;
			case 8:
        			$M_actual_e = "Agosto";
        	break;
			case 9:
        			$M_actual_e = "Septiembre";
        	break;
			case 10:
        			$M_actual_e = "Octubre";
        	break;
			case 11:
        			$M_actual_e = "Noviembre";
        	break;
			case 12:
        			$M_actual_e = "Diciembre";
        	break;
}
		  
          
          ?>
          <td>
          	&nbsp;&nbsp;<? echo $M_actual_e; ?>&nbsp;&nbsp;
          </td>
          <td style="text-align: left;">$&nbsp;&nbsp;0.00
          </td>
          <td style="text-align: left;">$&nbsp;&nbsp;0.00
          </td>
          <td><br>
          </td>          
        </tr>
        <tr style="color: #000; text-align: center;">
          <td><br>
          </td>
          <td>Concepto A Pagar 15-16
          </td>
          <td>
			&nbsp;&nbsp;<? echo $M_actual_e; ?>&nbsp;&nbsp;
          </td>
          <td style="text-align: left;">$&nbsp;&nbsp;0.00
          </td>
          <td style="text-align: left;">$&nbsp;&nbsp;0.00
          </td>
          <td><br>
          </td>   
        </tr>
        <tr style="color: #000; text-align: center; font-weight: bold;">
          <td><br>
          </td>
          <td colspan="2"><br>
          </td>
          <td style="text-align: left;">
          	Subtotal: $&nbsp;&nbsp;0.00
          </td>
          <td style="text-align: left;">
          	$&nbsp;&nbsp;0.00
          </td>
          <td>
          </td>
        </tr>
        <tr style="color: #000; text-align: center; font-weight: bold; border-top: 1px #000 solid;">
          <td><br>
          </td>
          <td><br>
          </td >
          <td><br>
          </td >
          <td><br>
          </td >
          <td style="text-align: left;">
          	Total: $&nbsp;&nbsp;0.00
          </td>
          <td>
          </td>
        </tr>
      </tbody>
    </table>
    <br />
	<br />    
    <p style="text-align: right;  border-top: 1px #ddd solid;">Gran Total: $&nbsp;&nbsp;0.00</p>
    <br>
    <br>
    <p><div id="m_salida_select" name="m_salida_select"><p>Salida</p></div></p>
    <br>
    <p align="center"><div id="m_firma" name="m_firma"><p>Firma</p></div></p> 				
 <!-- Demo --> 				
  </div>
  <div id="tabs-5">
  		<p>Resultados:</p>
  		<select multiple='multiple' id='enviados' name='enviados' ></select>
  </div>
</div>

<script type="text/javascript" src="js/fuctions.js"></script>
</body>
</html>
