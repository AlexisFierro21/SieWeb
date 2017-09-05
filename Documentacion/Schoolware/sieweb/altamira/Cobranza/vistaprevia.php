<?
include('../config.php');
include('../functions.php');

$id= $_REQUEST['id'];
$enunciado = $_REQUEST['enunciado'];

if($enunciado == "0"){
echo "<img src='../im/logo.jpg'></img><br/>";
}
if($enunciado == "1"){
$result=mysql_query("SELECT mensaje_entrada FROM estado_cuenta_formatos_cartas WHERE id = $id",$link)or die(mysql_error());

mysql_query("SET NAMES 'utf8'");


while($row = mysql_fetch_array($result))
		{
	 		echo $row["mensaje_entrada"]. " <br  />";
		}
}


/*
 $row["id"]." ".$row["nombre"]." ".." ".$row["mensaje_salida"]." ".$row["remitente"]." <br />"
  */
  
  if($enunciado == "2"){
$result=mysql_query("SELECT mensaje_salida FROM estado_cuenta_formatos_cartas WHERE id = $id",$link)or die(mysql_error());

mysql_query("SET NAMES 'utf8'");


while($row = mysql_fetch_array($result))
		{
	 		echo $row["mensaje_salida"]. " <br  />";
		}
}
  
  
   if($enunciado == "3"){
$result=mysql_query("SELECT remitente FROM estado_cuenta_formatos_cartas WHERE id = $id",$link)or die(mysql_error());

//mysql_query("SET NAMES 'utf8'");


while($row = mysql_fetch_array($result))
		{
	 		echo utf8_encode($row["remitente"]). " <br  />";
		}
} 
 
if(isset($_REQUEST['seccion'])){
	$seccion = $_REQUEST['seccion'];
	//echo $seccion;
$html="";		
				$result=mysql_query("Select distinct(grado_n), seccion_n from grupos_n where seccion_n = '$seccion'",$link)or die(mysql_error());


	$html = "<select id='grado_n' name='grado_n'><option value=''>&nbsp;</option>";
	
while($row = mysql_fetch_array($result))
		{
	 		$html.= '<option value="'.$row['grado_n'].'" >&nbsp;&nbsp;'.$row['grado_n'].'&nbsp;&nbsp;</option>';
		}
			$html.="</select>";
			
		echo $html;		

}  
?>