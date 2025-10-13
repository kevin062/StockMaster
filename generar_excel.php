<?php
error_reporting(0);
ini_set('display_errors', 0);

require 'vendor/autoload.php';
require_once 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Inventario');

$headers = ['ID', 'Código', 'Nombre', 'Categoría', 'Proveedor', 'Precio', 'Stock', 'Ubicación', 'Estado', 'Fecha Inventario'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

$sheet->getStyle('A1:J1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
]);

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

$sheet->getStyle("A1:J" . ($row - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
]);

foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$filename = 'inventario.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filename);
header("Location: $filename");
exit;
