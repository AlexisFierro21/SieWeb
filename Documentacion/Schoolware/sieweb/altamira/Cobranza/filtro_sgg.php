<?
include("../config.php");

mysql_query("SET NAMES 'utf8'");

$id= $_REQUEST['id'];
$enunciado = $_REQUEST['enunciado'];

$busca = $_REQUEST['busca'];

echo "<select>";
/*
if($busca  == "GRADO"){
										
	$result=mysql_query("select secciones_n.nombre_n from secciones_n, secciones 
								where 
										secciones.ciclo = $periodo_actual 
		  							and 
										secciones_n.ciclo = secciones.ciclo 
		  							and 
										secciones_n.seccion= secciones.seccion
						",$link)or die(mysql_error());

while($row = mysql_fetch_array($result))
		{
	 		echo "<option value='".$row["nombre_n"]. "' align='center'> ".$row["nombre_n"]. "</option>";
		}
}
*/

if($busca  == "SECCION"){
										
	$result=mysql_query("select * from secciones_n
						where
							ciclo = $periodo_actual
		  					
						",$link)or die(mysql_error());

while($row = mysql_fetch_array($result))
		{
	 		echo "<option value='".$row["seccion"]. "' align='center'> ".$row["nombre_n"]. "</option>";
		}
}

if($busca  == "GRADO"){
										
	$result=mysql_query("select * from grados_n
						where
							ciclo = $periodo_actual
		  					
						",$link)or die(mysql_error());

while($row = mysql_fetch_array($result))
		{
	 		echo "<option value='".$row["grado"]. "' align='center'> ".$row["nombre"]. "</option>";
		}
}

if($busca  == "GRUPO"){
										
	$result=mysql_query("SELECT * FROM 
										grupos_sede, grupos_sede_n, grupos_n 
									WHERE
										grupos_sede_n.ciclo = grupos_sede.ciclo  
											AND
										grupos_sede_n.seccion = grupos_sede.seccion 
                							AND
            							grupos_sede_n.grado = grupos_sede.grado
                							AND
            							grupos_sede_n.grupo = grupos_sede.grupo
											AND
            							grupos_sede_n.ciclo = grupos_n.ciclo 
                							AND
            							grupos_sede_n.seccion= grupos_n.seccion 
                							AND
            							grupos_sede_n.grado = grupos_n.grado
                							AND
            							grupos_sede_n.grupo = grupos_n.grupo
                							AND
										grupos_sede.activo = 'S'
											AND
										grupos_sede.ciclo = $periodo_actual
											AND
										
									",$link)or die(mysql_error());

while($row = mysql_fetch_array($result))
		{
	 		echo "<option value='".$row["grado_n"]. "' align='center'> ".$row["grado_n"]. "</option>";
		}

}

echo "</select>";
?>