<?php

require APP_ROOT . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "ukk_kasir");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil semua data penjualan
$query = "
    SELECT 
        settings.Business_name AS nama_usaha,
        settings.phone AS no_telepon,
        settings.email AS email,
        penjualan.id_penjualan,
        penjualan.tanggal_penjualan AS tanggal_penjualan,
        user.nama AS nama_kasir,
        penjualan.total_harga,
        MONTHNAME(penjualan.tanggal_penjualan) AS bulan
    FROM 
        penjualan
    JOIN 
        user 
        ON penjualan.id_kasir = user.id_user
    CROSS JOIN 
        settings
    ORDER BY 
        MONTH(penjualan.tanggal_penjualan), penjualan.tanggal_penjualan ASC;
";

$result = $conn->query($query);

// Query untuk mengambil data jumlah penjualan dan total harga per kasir
$queryKasir = "
    SELECT 
        user.nama AS nama_kasir,
        COUNT(penjualan.id_penjualan) AS jumlah_penjualan,
        SUM(penjualan.total_harga) AS total_harga
    FROM 
        penjualan
    JOIN 
        user 
        ON penjualan.id_kasir = user.id_user
    GROUP BY 
        user.id_user
    ORDER BY 
        user.nama ASC;
";

$resultKasir = $conn->query($queryKasir);

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title and basic information
$sheet->setCellValue('A1', 'Laporan Penjualan');
$sheet->mergeCells('A1:E1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('1F4E79');
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getRowDimension(1)->setRowHeight(30);

// Add usaha information below the title
$info = $result->fetch_assoc(); // Ambil informasi usaha dari baris pertama
$sheet->setCellValue('A2', 'Nama Usaha: ' . $info['nama_usaha']);
$sheet->setCellValue('A3', 'No Telepon: ' . $info['no_telepon']);
$sheet->setCellValue('A4', 'Email: ' . $info['email']);

// Style for usaha info
$sheet->getStyle('A2:A4')->getFont()->setBold(true);
$sheet->getStyle('A2:A4')->getFont()->setSize(12);

// Add border for usaha info
$sheet->getStyle('A2:A4')->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

// Table headers for penjualan
$sheet->setCellValue('A6', 'ID Penjualan');
$sheet->setCellValue('B6', 'Tanggal Penjualan');
$sheet->setCellValue('C6', 'Nama Kasir');
$sheet->setCellValue('D6', 'Total Harga');
$sheet->setCellValue('E6', 'Bulan');

// Style for headers
$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 12,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4F81BD'], // Blue color
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A6:E6')->applyFromArray($headerStyle);

// Data for penjualan
$sheet->getStyle('A6:E6')->getFont()->setBold(true);
$row = 7; // Data starts from row 7
$totalPerBulan = [];
$result->data_seek(0); // Reset pointer

if ($result->num_rows > 0) {
    while ($info = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $info['id_penjualan']);
        $sheet->setCellValue('B' . $row, $info['tanggal_penjualan']);
        $sheet->setCellValue('C' . $row, $info['nama_kasir']);
        $sheet->setCellValue('D' . $row, $info['total_harga']);
        $sheet->setCellValue('E' . $row, $info['bulan']);
        
        // Calculate total price per month
        if (!isset($totalPerBulan[$info['bulan']])) {
            $totalPerBulan[$info['bulan']] = 0;
        }
        $totalPerBulan[$info['bulan']] += $info['total_harga'];

        $row++;
    }

    // Add total price per month
    $sheet->setCellValue('C' . $row, 'Total Harga Per Bulan');
    $sheet->getStyle('C' . $row)->getFont()->setBold(true);
    $sheet->mergeCells('C' . $row . ':D' . $row);
    $row++;

    foreach ($totalPerBulan as $bulan => $total) {
        $sheet->setCellValue('E' . $row, $bulan);
        $sheet->setCellValue('D' . $row, $total);
        $sheet->getStyle('C' . $row . ':E' . $row)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $row++;
    }

    // Add borders to the penjualan table
    $dataRange = 'A6:E' . ($row - 1);
    $sheet->getStyle($dataRange)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ]);
} else {
    echo "Tidak ada data yang ditemukan.";
    exit;
}

// Second Table: Kasir Statistics
$row += 2; // Leave empty row before next section
$sheet->setCellValue('A' . $row, 'Laporan Penjualan per Kasir');
$sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
$sheet->mergeCells('A' . $row . ':C' . $row);
$sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$row++;

$sheet->setCellValue('A' . $row, 'Nama Kasir');
$sheet->setCellValue('B' . $row, 'Jumlah Penjualan');
$sheet->setCellValue('C' . $row, 'Total Harga');
$sheet->getStyle('A' . $row . ':C' . $row)->applyFromArray($headerStyle);
$row++;

if ($resultKasir->num_rows > 0) {
    while ($kasir = $resultKasir->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $kasir['nama_kasir']);
        $sheet->setCellValue('B' . $row, $kasir['jumlah_penjualan']);
        $sheet->setCellValue('C' . $row, $kasir['total_harga']);
        $row++;
    }
}

// Add border for kasir statistics table
$kasirRange = 'A' . ($row - ($resultKasir->num_rows + 1)) . ':C' . ($row - 1);
$sheet->getStyle($kasirRange)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
]);

// Atur header HTTP agar file dapat diunduh
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Penjualan.xlsx"');
header('Cache-Control: max-age=0');

// Simpan file Excel dan unduh
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

?>
