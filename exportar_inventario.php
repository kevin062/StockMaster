<?php

require 'vendor/autoload.php';
require_once 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Inventario');

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Código');
$sheet->setCellValue('C1', 'Nombre');
$sheet->setCellValue('D1', 'Categoría');
$sheet->setCellValue('E1', 'Proveedor');
$sheet->setCellValue('F1', 'Precio');
$sheet->setCellValue('G1', 'Stock');
$sheet->setCellValue('H1', 'Ubicación');
$sheet->setCellValue('I1', 'Estado');
$sheet->setCellValue('J1', 'Fecha Inventario');

$stmt = $conn->query("SELECT * FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$row = 2;
foreach ($productos as $p) {
    $sheet->setCellValue("A$row", $p['id']);
    $sheet->setCellValue("B$row", $p['codigo']);
    $sheet->setCellValue("C$row", $p['nombre']);
    $sheet->setCellValue("D$row", $p['categoria']);
    $sheet->setCellValue("E$row", $p['proveedor']);
    $sheet->setCellValue("F$row", $p['precio']);
    $sheet->setCellValue("G$row", $p['stock']);
    $sheet->setCellValue("H$row", $p['ubicacion']);
    $sheet->setCellValue("I$row", $p['estado']);
    $sheet->setCellValue("J$row", $p['fecha_inventario']);
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="inventario.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
