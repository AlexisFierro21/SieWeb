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
/*
// Tabla simple
function BasicTable($header, $data)
{
    // Cabecera
    foreach($header as $col)
        $this->Cell(80,7,$col,1);
    $this->Ln();
    // Datos
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(50,6,$col,1);
        $this->Ln();
    }
}*/

function Header()
   {
    //Logo
    $this->Image("../im/logo_.jpg" , 10 ,8, 28 , 28 , "JPG" ," ‎");
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    //Movernos a la derecha
    $this->Cell(30);
    //Título
   $this->Cell(230,20,utf8_decode('Cumpleaños Padres de Familia'),0,0,'C');
   $this->Ln(5);
   
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
   $this->SetFont('Arial','B',8);
   $this->SetFillColor(255,0,0);
   $this->Cell(60,10,'Nombre',1,0,'C');
   $this->Cell(17,10,'Fecha',1,0,'C');
   $this->Cell(58,10, 'E-Mail',1,0,'C');
   $this->Cell(30,10, 'Telefono',1,0,'C');
   $this->Cell(30,10, 'Celular',1,0,'C');
   $this->Cell(55,10,'Alumno',1,0,'C');
   $this->Cell(20,10,utf8_decode('Sección'),1,0,'C');
   $this->Cell(10,10,'Grado',1,0,'C');
   $this->Ln();
	//Salto de línea
	
	}
//Pie de página
   function Footer()
   {
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
	$this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
   }

// Tabla coloreada
function FancyTable($header, $data)
{

    // Cabecera
    $w = array(60, 17, 58, 30, 30, 55, 20, 10);
    for($i=0;$i<count($header);$i++)
//        $this->Cell($w[$i],7,$header[$i],1,0,'C',false);
//    $this->Ln(3);
    // Restauración de colores y fuentes
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Datos
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'M',$fill);
		$this->SetTextColor(40,61,255);
        $this->Cell($w[2],6,$row[2],'LR',0,'M',$fill);
		$this->SetTextColor(0);
        $this->Cell($w[3],6,$row[3],'LR',0,'M',$fill);
        $this->Cell($w[4],6,$row[4],'LR',0,'M',$fill);
		$this->Cell($w[5],6,$row[5],'LR',0,'L',$fill);
		$this->Cell($w[6],6,$row[6],'LR',0,'L',$fill);
		$this->Cell($w[7],6,$row[7],'LR',0,'L',$fill);
		$this->Ln();
        $fill = !$fill;
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf = new PDF('L');
// Títulos de las columnas
$header = array('Nombre', 'Fecha', 'E-Mail', 'Telefono', 'Celular', 'Alumno', utf8_decode('Sección'), 'Grado');
// Carga de datos
$data = $pdf->LoadData('CumpleSemana.txt');
$pdf->SetFont('Arial','',8);
//$pdf->AddPage();
//$pdf->BasicTable($header,$data);
//$pdf->AddPage();
//$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output(utf8_decode("CumpleSemana.pdf"),"F");

?>