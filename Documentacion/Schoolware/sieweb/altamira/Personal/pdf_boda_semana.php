<?php
require('../../repositorio/fpdf/fpdf.php');
//FPDF

class PDF extends FPDF
{
// Cargar los datos
function LoadData($file)
{
    // Leer las líneas del fichero
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

function Header()
   {
    //Logo
    $this->Image("../im/logo_.jpg" , 10 ,8, 28 , 28 , "JPG" ," ");
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    //Movernos a la derecha
    $this->Cell(15);
    //Título
   $this->Cell(230,20,utf8_decode('Bodas de la semana'),0,0,'C');
   $this->Ln(5);
   //$this->SetAligns(array('C','L','R'));
//  Agregamos las fechas al reporte 

$Fecha_ini = new DateTime();
date_add($Fecha_ini, date_interval_create_from_date_string('0 days'));
$dias_m = date("t");
$Fecha_fin = new DateTime();
date_add($Fecha_fin, date_interval_create_from_date_string('6 days'));
$rango_fechas = "Reporte de fechas del: ".$Fecha_ini->format('d/m/Y')." al: ".$Fecha_fin->format('d/m/Y')." ";

//
   $this->Cell(300,20,utf8_decode($rango_fechas),0,0,'C');
   $this->Ln(32);   
// Colores, ancho de línea y fuente en negrita

   $this->SetTextColor(0,0,0);
   $this->SetDrawColor(128,0,0);
   $this->SetFont('Arial','B',7);
   $this->SetFillColor(255,255,255);
  
  	$y1 = $this->GetY();
	$x1 = $this->GetX();
  
   $this->MultiCell(23,20,'Familia',1,'LR',true);
   // Obtengo la posición vertical donde termina la celda y así saco el tamaño de alto de celda para aplicarlo al resto de las celdas de la misma fila.
	$y2 = $this->GetY(32);
	$alto_de_fila = 32;
	// Aquí obtengo la coordenada X donde quiero comenzar la siguiente columna, que no es otra cosa que la posición original más el ancho de la primera celda, en el caso del ejemplo es 65.
	$posicionX = $x1 + 23;
	// Por último, me sitúo en las coordenadas correspondientes a la misma fila y siguiente columna.
	$this->SetXY($posicionX,$y1);
	// Y ahora a pintar el resto de celdas con el mismo tamaño de alto para toda la fila.
   $x1 = $this->GetX();
   $this->MultiCell(13,20,'Fecha',1,'LR',true);
   $posicionX = $x1 + 13;
	// Por último, me sitúo en las coordenadas correspondientes a la misma fila y siguiente columna.
   $this->SetXY($posicionX,$y1);
   
   //Hacemos el primer parrafo del multirow
  $this->Cell(102,10, 'Datos de la Madre',1,0,'C');
  $this->Cell(102,10, 'Datos del Padre',1,0,'C');
  $this->Cell(42,10, 'Datos del Alumno',1,0,'C');
   
   
   
   //Empezamos el segundo renglón
   $y1 = $y1 + 10;
   $this->SetXY($posicionX,$y1);
   //Hasta aquí volvemos a poner la posición del Multicell ;)
   $this->Cell(40,10, 'Nombre',1,0,'C');
   $this->Cell(17,10, 'Telefono',1,0,'C');
   $this->Cell(17,10, 'Celular',1,0,'C');
   $this->Cell(28,10,'Correo',1,0,'C');
   //Madre
   $this->Cell(40,10, 'Nombre',1,0,'C');
   $this->Cell(17,10, 'Telefono',1,0,'C');
   $this->Cell(17,10, 'Celular',1,0,'C');
   $this->Cell(28,10,'Correo',1,0,'C');
   //Alumno
   $this->Cell(25,10,'Alumno',1,0,'C');
   $this->Cell(17,10,'Escolaridad',1,0,'C');

   $this->Ln();
	//Salto de línea
	
	}
//Pie de página
   function Footer()
   {
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','',6);
    //Número de página
	$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
   }

// Tabla coloreada
function FancyTable($header, $data)
{

    // Cabecera
    $w = array(23, 13, 40, 17, 17, 28, 40, 17, 17, 28, 25, 17);
    for($i=0;$i<count($header);$i++)
//    $this->Cell($w[$i],7,$header[$i],1,0,'C',false);
//    $this->Ln(3);
    // Restauración de colores y fuentes
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',6);
    // Datos
    $fill = false;

    foreach($data as $row)
    {
		
		//$this->Cell($this->widths[$i],7,utf8_decode($this->titulos[$i]),'TBLR',0,'C','1');
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);//Familia
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);//Fecha
		$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);//Nombre madre
		$this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);//Telefono
        $this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);//Celular
		$this->SetTextColor(40,61,255);
		$this->Cell($w[5],6,$row[5],'LR',0,'FJ',$fill);//Correo
		$this->SetTextColor(0);
		$this->Cell($w[6],6,$row[6],'LR',0,'L',$fill);//Nombre padre
		$this->Cell($w[7],6,$row[7],'LR',0,'L',$fill);//Telefono
		$this->Cell($w[8],6,$row[8],'LR',0,'L',$fill);//Celular
		$this->SetTextColor(40,61,255);
		$this->Cell($w[9],6,$row[9],'LR',0,'LR',$fill);//Correo
		$this->SetTextColor(0);
		$this->Cell($w[10],6,$row[10],'LR',0,'FJ',$fill);//Nombre del Alumno
		$this->Cell($w[11],6,$row[11],'LR',0,'FJ',$fill);//Grado y grupo

		$this->Ln();
        $fill = !$fill;
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
  }
}

$pdf = new PDF('L');
// Títulos de las columnas
$header = array('Nombre', 'Fecha', 'E-Mail', 'Telefono', 'Celular', 'Alumno', utf8_decode('Escolaridad'));
// Carga de datos
$data = $pdf->LoadData('BodaSemana.txt');
$pdf->SetFont('Arial','',8);
//$pdf->AddPage();
//$pdf->BasicTable($header,$data);
//$pdf->AddPage();
//$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output(utf8_decode("BodaSemana.pdf"),"F");

?>