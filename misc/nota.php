<?php
require('../fpdf/fpdf.php');
include_once'../db/connect_db.php';

$id = $_GET['id'];
$select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE invoice_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

$pdf = new FPDF('P','mm', array(80,200));

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(60,10,'Babies Heaven',0,1,'C');

$pdf->Line(10,18,72,18);
$pdf->Line(10,19,72,19);

$pdf->SetFont('Arial','',8);
$pdf->Cell(60,3,'Physical Location: Tabora - Nunber 4',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(63,3,'Digital Address: GC-3464-56',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(63,4,'Call/Whatsapp: +233 27 416 1599',0,1,'C');

$pdf->Line(10,30,72,30);
$pdf->Line(10,31,72,31);

$pdf->SetY(31);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,6 ,'Receipt',0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'No. Note     :',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,$row->invoice_id,0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'Sales Person :',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,$row->cashier_name,0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'Date & Time  :',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(21,4 ,$row->order_date,0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,$row->time_order,0,1,'C');
//////////////////////////////////////////////
$pdf->SetY(55);

$pdf->SetX(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(27,8 ,'Service',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(7,8 ,'Qty',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(18,8 ,'Price',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(18,8 ,'Total',1,1,'C');

$select = $pdo->prepare("SELECT * FROM tbl_invoice_detail WHERE invoice_id=$id");
$select->execute();
while($item = $select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(6);
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(27,5,$item->product_name,1,0,'L');
    $pdf->Cell(7,5,$item->qty,1,0,'C');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(18,5,'GHS '.number_format($item->price),1,0,'R');
    $pdf->Cell(18,5,'GHS '.number_format($item->total),1,1,'R');
}

//////////////////////////////////////////////
$pdf->SetX(43);
$pdf->SetFont('Arial','Bi',8);
$pdf->Cell(25,8 ,'Total  :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,8 ,'GHS ' . number_format($row->total),0,1,'C');

$pdf->SetX(43);
$pdf->SetFont('Arial','BI',7);
$pdf->Cell(25,4 ,'Paid   :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,4 ,'GHS '. number_format($row->paid),0,1,'C');

$pdf->SetX(43);
$pdf->SetFont('Arial','BI',8);
$pdf->Cell(25,8 ,'Change    :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,8 ,'GHS '. number_format($row->due),0,1,'C');

//////////////////////////////////////////////
$pdf->SetY(120);
$pdf->SetX(7);
$pdf->SetFont('Arial','BU',7);
$pdf->Cell(75,4 ,'Thank you for your patronage',0,1,'L');

$pdf->SetFont('Arial','BU',7);
$pdf->Cell(45,4 ,'We look forward to seeing you again.',0,0,'C');



$pdf->Output();

