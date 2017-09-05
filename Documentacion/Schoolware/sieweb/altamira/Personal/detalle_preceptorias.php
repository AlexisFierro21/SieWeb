<script Language="javascript">
        function TableToExcel() {
            var strCopy = document.getElementById("detailsTable").innerHTML;
            window.clipboardData.setData("Text", strCopy);
            var objExcel = new ActiveXObject("Excel.Application");
            objExcel.visible = true;

            var objWorkbook = objExcel.Workbooks.Add;
            var objWorksheet = objWorkbook.Worksheets(1);
            objWorksheet.Paste;
        }

        function exportToExcel() {
            var oExcel = new ActiveXObject("Excel.Application");
            var oBook = oExcel.Workbooks.Add;
            var oSheet = oBook.Worksheets(1);
            for (var y = 0; y < detailsTable.rows.length; y++)
            // detailsTable is the table where the content to be exported is
            {
                for (var x = 0; x < detailsTable.rows(y).cells.length; x++) {
                    oSheet.Cells(y + 1, x + 1) = detailsTable.rows(y).cells(x).innerText;
                }
            }
            oExcel.Visible = true;
            oExcel.UserControl = true;
        }
    </script>
<?php
include('../config.php');
include('../functions.php');

$cuerpo = '
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>

<!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
	
	
	
<title>Alumnos sin preceptoría</title>
</head>
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

	<title>Detalle de Alumnos sin preceptor&iacute;as</title>
	<body>
';


$cuerpo.="<center><img  src='https://ecolmenares.net/sieweb/altamira/im/logo.jpg' width='100%'></img></center>
<br>
<br>
<br>
<br>
<br>
<br>";

$fechai = new DateTime();
$fechai->modify('first day of this month');
$fec_repo_i=' '.$fechai->format('d/m/Y').' ';
$cuerpo.='Rango de fechas del: '.$fechai->format('d/m/Y').' ';
$fechaf = new DateTime();
$fec_repo_f=' '.$fechaf->format('d/m/Y').' ';
$cuerpo.='Al: '.$fechaf->format('d/m/Y').'<br>'; 

$cuerpo.="
<table id='datatable' cellspacing='0' style='width: 80%; border: solid 2px #000000; font-size: 10pt'>
  <tr>
  	<td colspan='5' bgcolor='$baner' align='center'><font color='#FFFFFF'><strong>Alumnos sin preceptorias realizadas (Mensual)</strong></font></td>
  </tr>
  <tr>
  	<th scope='col' bgcolor='$fondo_n' align='left'><font color='#FFFFFF'><strong>&nbsp;Alumno&nbsp;</strong></font></th>
    <th scope='col' bgcolor='$fondo_n' align='left'><font color='#FFFFFF'><strong>&nbsp;Nombre&nbsp;</strong></font></th>
	<th scope='col' bgcolor='$fondo_n' align='left'><font color='#FFFFFF'><strong>&nbsp;Secci&oacute;n&nbsp;</strong></font></th>
    <th scope='col' bgcolor='$fondo_n' align='left'><font color='#FFFFFF'><strong>&nbsp;Grado&nbsp;</strong></font></th>
	<th scope='col' bgcolor='$fondo_n' align='left'><font color='#FFFFFF'><strong>&nbsp;Preceptor&nbsp;</strong></font></th>
  </tr>";
  
///// Seleccionamos los diferentes tipos de secciones que existen en el grupo 

$x="";
$num_results="";
for ($x=1; $x<=3;$x++)
{	

$sprecep=mysql_query("SELECT DISTINCT
                      	alumnos.alumno, 
					  	CONCAT (alumnos.nombre,' ', 
					  	alumnos.apellido_paterno,' ', 
					  	alumnos.apellido_materno) AS nombre,
					  	alumnos.seccion,
					 	alumnos.grado, 
                      	alumnos.grupo, 
					  	alumnos.preceptor,
						CONCAT (personal.nombre,' ',personal.apellido_paterno,' ',personal.apellido_materno) AS preceptor_nombre
					  FROM
         
						alumnos INNER JOIN
						personal ON alumnos.preceptor = personal.empleado 
					WHERE     
						(alumnos.activo = 'A') 
						AND alumnos.seccion = $x
						AND (NOT EXISTS
                          		(SELECT     
								 	preceptoria.alumno
                            	FROM          
									preceptoria 
                            	WHERE      
									(fecha BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()) 
									 AND alumnos.alumno = alumno ))
								ORDER BY 
									alumnos.seccion,
									alumnos.grado, 
									alumnos.grupo, 
									alumnos.apellido_paterno, 
									alumnos.apellido_materno, 
									alumnos.nombre;
					",$link) 
or die 
					("SELECT DISTINCT
                      	alumnos.alumno, 
					  	CONCAT (alumnos.nombre,' ', 
					  	alumnos.apellido_paterno,' ', 
					  	alumnos.apellido_materno) AS nombre,
					  	secciones.nombre AS seccion,
					 	alumnos.grado, 
                      	alumnos.grupo, 
					  	alumnos.preceptor,
						CONCAT (personal.nombre,' ',personal.apellido_paterno,' ',personal.apellido_materno) AS preceptor_nombre
					  FROM
         
						alumnos INNER JOIN
						personal ON alumnos.preceptor = personal.empleado INNER JOIN
						secciones ON alumnos.seccion = secciones.seccion
					WHERE     
						(alumnos.activo = 'A') 

						AND (NOT EXISTS
                          		(SELECT     
								 	preceptoria.alumno
                            	FROM          
									preceptoria 
                            	WHERE      
									(fecha BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()) 
									 AND alumnos.alumno = alumno ))
								ORDER BY 
									alumnos.seccion,
									alumnos.grado, 
									alumnos.grupo, 
									alumnos.apellido_paterno, 
									alumnos.apellido_materno, 
									alumnos.nombre;
".mysql_error());					


///// Empieza el contador de los subtotales
	$num_seccion=mysql_num_rows($sprecep);
while($row = mysql_fetch_array($sprecep))
                    {
					  $cuerpo.='<tr>
					  <td>&nbsp;'.$row['alumno'].'&nbsp;</td>
					  <td>&nbsp;'.utf8_encode($row['nombre']).'&nbsp;</td>
					  ';
					  /*echo '<td>&nbsp;'.utf8_encode($row['seccion']).'&nbsp;</td>';
					  $cuerpo.='<td>&nbsp;'.utf8_encode($row['seccion']).'&nbsp;</td>';*/
						
						switch ($row['seccion']){
							case "1":
							$cuerpo.="<td bgcolor='$tr_par'>&nbsp;Primaria&nbsp;</td>";
							break;
							case "2":
							$cuerpo.="<td bgcolor='$tr_par'>&nbsp;Secundaria&nbsp;</td>";
							break;
							case "3":
							$cuerpo.="<td bgcolor='$tr_par'>&nbsp;Preparatoria&nbsp;</td>";
							break;
						}
						//$cuerpo.='<td>&nbsp;'.$row['seccion'].'&nbsp;</td>';
								 
					  $cuerpo.='
					  <td>&nbsp;'.$row['grado'].'&nbsp;'.$row['grupo'].'</td>
					  <td>&nbsp;'.utf8_encode($row['preceptor_nombre']).'&nbsp;</td>';
					  }				
					  
	$cuerpo.="<tr><td colspan='5' bgcolor='$baner'><font color='#FFFFFF'><strong>Sin preceptorias:</strong>&nbsp;&nbsp;$num_seccion&nbsp;</font></td></tr>";
	$num_results=$num_results+$num_seccion;
}
	$cuerpo.='<tr><td>Total sin preceptorias:&nbsp;</td><td>&nbsp;'.$num_results.'&nbsp;</td></tr>
	</table>';


	$cuerpo.='	</body> 
	</html>';	

$cuerpo= utf8_decode($cuerpo);

?>

<script src='excellentexport.js'></script>
<script type='application/javascript'>

function Download(){
  window.open('somedata.xls');
}

</script>
<!--
<a download="AlumnosSinPreceptorias.xls" href="#" onclick="return ExcellentExport.excel(this, 'datatable', 'Alumnos Sin Preceptorías');">Exportar a Excel</a>
<br />-->
<a href="DetalleAlumnos_SinPreceptorias.pdf" target="_blank">Exportar a PDF</a>
<br />
<a href="Detalle_Preceptorias.xls" target="_blank">Exportar a Excel </a>
<br />
<?


echo $cuerpo;
file_put_contents('Detalle_Preceptorias.xls',$cuerpo);



//// Mandamos llamar las librerias del HTML2PDF 

include_once ('../../repositorio/html2pdf/html2fpdf.php');

$pdf = new HTML2FPDF('P','mm','Letter'); // Creamos una instancia de la clase HTML2FPDF
$pdf -> AddPage(); // Creamos una página
$pdf -> SetFont('Courier','I',3);
$pdf -> SetDisplayMode('fullpage');
$pdf -> WriteHTML($cuerpo);//Volcamos el HTML contenido en la variable $html para crear el contenido del PDF
$pdf -> Output('DetalleAlumnos_SinPreceptorias.pdf');//Volcamos el pdf generado con nombre ‘doc.pdf’. En este caso con el parametro ‘D’ forzamos la descarga del mismo.
?>