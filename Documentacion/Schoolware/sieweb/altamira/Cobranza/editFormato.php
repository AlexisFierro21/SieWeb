<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
require_once("../config.php");

$Id = $_REQUEST['id'];
$Titulo = $_POST['titulo'];
$Entrada = $_POST['mensaje_entrada'];
$Salida = $_POST['mensaje_salida'];
$Remitente = $_POST['remitente'];

mysql_query("SET NAMES 'utf8'");

if($_REQUEST['datos'] == 1){

	mysql_query("INSERT INTO 
							estado_cuenta_formatos_cartas 
						SET 
							nombre = '$Titulo', 
							mensaje_entrada = '$Entrada',
							mensaje_salida = '$Salida',
							remitente = '$Remitente'",$link)or die(mysql_error());
	

	echo "<span id='hideMe' name='hideMe' class='hideMe'>Formato Insertado Correctamente</span>";
}elseif($_REQUEST['datos'] == 2){	
	
	$result_formato=mysql_query("SELECT * FROM estado_cuenta_formatos_cartas WHERE id= '$Id'   ",$link)or die(mysql_error());
	
	while($row = mysql_fetch_array($result_formato))
									{
	 									echo "
	 									<br />
	 										<p>Vista previa del formato</p>
	 										<fieldset>
	 											<input type='hidden' name='id' id='' value='".$row["id"]."' />
	 											<br />
	 											<label>Titulo de Formato:</label>&nbsp;&nbsp;&nbsp;".$row["nombre"]."
	 											<br />
	 											<label>Mensaje de Entrada:</label>&nbsp;&nbsp;&nbsp;".$row["mensaje_entrada"]."
	 											<br />
	 											<label>Mensaje de Salida:</label>&nbsp;&nbsp;&nbsp;".$row["mensaje_salida"]."
	 											<br />Remitente:</label>&nbsp;&nbsp;&nbsp;".$row["remitente"]."
	 											<br />
	 										</fieldset>
	 										  ";
									}
}elseif($_REQUEST['datos'] == 3){
		
		mysql_query("UPDATE estado_cuenta_formatos_cartas
						SET
							nombre = '$Titulo',
							mensaje_entrada = '$Entrada',
							mensaje_salida = '$Salida',
							remitente = '$Remitente'
						WHERE id = $Id",$link)or die(mysql_error());
	
	echo "<span id='hideMe' name='hideMe' class='hideMe'>Formato Actualizado Correctamente</span>";
		
	}	


?>