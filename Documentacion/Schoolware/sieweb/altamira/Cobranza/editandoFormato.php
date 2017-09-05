<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
require_once("funciones.php");
include('../functions.php');
require_once("../config.php");
mysql_query("SET NAMES 'utf8'");

$Id = $_REQUEST['id'];
echo "<br />";

$result_formato=mysql_query("SELECT * FROM estado_cuenta_formatos_cartas WHERE id= '$Id'   ",$link)or die(mysql_error());
	
	while($row = mysql_fetch_array($result_formato))
									{
	 									echo "<fieldset>
	 													<input type='hidden' name='editID' id='editID' value='".$row["id"]."' />
	 													<input name='editNombre' id='editNombre' value='".$row["nombre"]."' disabled />
	 												<br />
	 													<label>Titulo de Formato</label>
	 												<br />
	 												<br />
	 													<textarea name='editMensajeEntrada' id='editMensajeEntrada' value='".$row["mensaje_entrada"]."' style='color: black; font-size: 14px; width: 500px;'>".$row["mensaje_entrada"]."</textarea>
	 												<br />
	 													<label>Mensaje de Entrada</label>
	 												<br />
	 												<br />
	 													<textarea name='editMensajeSalida' id='editMensajeSalida' value='".$row["mensaje_salida"]."'  style='color: black; font-size: 14px; width: 500px;'>".$row["mensaje_salida"]."</textarea>
	 												<br />
	 													<label>Mensaje de Salida:</label>
	 												<br />
	 												<br />
	 													<textarea name='editRemitente' id='editRemitente' value='".$row["remitente"]."'  style='color: black; font-size: 14px; width: 500px;  text-align: center;'>".$row["remitente"]."</textarea>
	 												<br />
	 													<label>Remitente:</label>
	 												<br />
	 												<br />
	 											<button id='GuardarCambios_btn' name='GuardarCambios_btn' onclick='grabarEditandoFormato();'>Guardar Cambios</button>
	 										 	<br />
	 										 	<div id='StatusGuardado' name='StatusGuardado'></div>
	 										 </fieldset>
	 										  ";
									}


?>