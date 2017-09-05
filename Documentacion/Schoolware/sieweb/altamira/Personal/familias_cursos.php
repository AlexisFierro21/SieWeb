<? session_start();
include('../config.php');
include('../functions.php');

$alumnoN="";
if(!empty($_POST['alumnoN'])) $alumnoN=$_POST['alumnoN'];
if(!empty($_GET['alumnoN'])) $alumnoN=$_GET['alumnoN'];

$aal=$_GET['alumno'];
	if($aal=="" or $aal==0)
		$aal=0; if(!empty($_POST['alumno'])) $aal=$_POST['alumno'];
//echo $alumnoN.$aal."REsultado";

$rs_ = mysql_query ( "SELECT familias.familia AS Familia, familias.nombre_familia AS Nombre_Familia
						FROM familias INNER JOIN
                         	 alumnos ON familias.familia = alumnos.familia
						WHERE (alumnos.alumno = '".$alumnoN."')", $link ) or die ( mysql_error () );	

while($row = mysql_fetch_array($rs_))
  {
	  $familia_=$row['Familia'];
  		$nom_fam =utf8_encode($row['Nombre_Familia']);
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

</head>
<style>

table, th, td {
   border: 1px solid <?=$border_consultas;?>;
}

table{
	 border-collapse: collapse;
} 
	td{
		background-color: <?=$tr_par;?>;
		font-size: 12px;
		margin-right: 15px;
    	margin-left: 15px;
	}
	
	th{
		background-color: <?=$fondo_n;?>;
		font-size: 16px;
		color: #fff;
		margin-right: 15px;
    	margin-left: 15px;
	}
</style>	
<?

$Var='
<table width="600">
  <tr>
    <td width="590">&nbsp;
    </td>
  </tr>
  <tr>
    <td>
    <table width="589">
 <tr>
    <th>&nbsp;&nbsp;Familia&nbsp;&nbsp;</th>
    <th>&nbsp;&nbsp;&nbsp;Nombre&nbsp;de&nbsp;la&nbsp;Familia&nbsp;&nbsp;&nbsp;</th>
    <th>&nbsp;&nbsp;&nbsp;Plantel&nbsp;&nbsp;&nbsp;</th>
    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Curso&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>Ciclo</th>
    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Descripci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>Asistencia del Padre</th>
    <th>Asistencia de la Madre</th>
    <th>Fecha del Curso</th>
  </tr>';
  
$consulta="SELECT 	
			data_bases.sede AS Plantel,
			fip_cursos_publicacion.ciclo,			
			fip_cursos.sede,
			fip_publicacion_familias.familia, 
            fip_cursos.nombre, fip_cursos.descripcion, 
            SUM((CASE fip_asistencia.asistencia_padre WHEN 'S' THEN 1 WHEN 'R' THEN 1 ELSE '0' END)) AS asistenciaPadre, 
            SUM((CASE fip_asistencia.asistencia_madre WHEN 'S' THEN 1 WHEN 'R' THEN 1 ELSE '0' END)) AS asistenciaMadre, 
			CONCAT(DATE_FORMAT(fip_cursos_publicacion.fecha_ini,'%d/%m/%Y'),' al ',
            DATE_FORMAT(fip_cursos_publicacion.fecha_fin,'%d/%m/%Y')) As Fecha_Del_Curso,
            IFNULL(fip_cursos_publicacion.numero_de_sesiones, 0) AS numero_de_sesiones
		FROM 
					fip_asistencia
					LEFT JOIN
                    fip_publicacion_familias
					ON
                    fip_asistencia.id_publicacion_familia = fip_publicacion_familias.id_publicacion_familia
                    AND 
                    fip_asistencia.sede = fip_publicacion_familias.sede
                    LEFT JOIN
					fip_cursos_publicacion
                    ON 
                    fip_publicacion_familias.id_publicacion = fip_cursos_publicacion.id_publicacion
                    AND
                    fip_publicacion_familias.sede = fip_cursos_publicacion.sede
					LEFT JOIN
                    fip_cursos
                    ON 
                    fip_cursos_publicacion.id_curso = fip_cursos.id_curso
                    AND
                    fip_cursos_publicacion.sede = fip_cursos.sede
                    LEFT JOIN
                    data_bases
                    ON
                    fip_cursos_publicacion.sede = data_bases.id
		WHERE 
			fip_publicacion_familias.familia = '{$familia_}'
		GROUP BY  
            fip_cursos.nombre, 
            fip_cursos.descripcion, 
            fip_cursos_publicacion.fecha_ini, 
            fip_cursos_publicacion.fecha_fin
            ORDER by 1";

$result = $pdo_fip->query($consulta);
    		if (!$result) {
        print "  <p>Error en la consulta.</p>\n";
    }else{
        foreach ($result as $row) {
	 									 $Var=$Var.'
 													 <tr>
    													<td>'.$row['familia'].'</td>
    													<td>'.$nom_fam.'</td>
    													<td>'.$sede_nom.$row['Plantel'].'</td>
    													<td><p align=center>'.$row['nombre'].'</p></td>
    													<td>'.$row['ciclo'].'</td>
    													<td>'.$row['descripcion'].'</td>
    													<td><p align=center>'.$row['asistenciaPadre'].' / '.$row['numero_de_sesiones'].'</p></td>
    													<td><p align=center>'.$row['asistenciaMadre'].' / '.$row['numero_de_sesiones'].'</p></td>
    													<td><p align=center>'.$row['Fecha_Del_Curso'].'</p></td>
  													</tr>';
  								}

$Var=$Var.'
</table>  
    </td>
  </tr>
</table>
';
}

echo $Var;

?>
</body>
</html>