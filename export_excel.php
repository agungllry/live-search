<?php
// Memuat library dari Composer
require 'vendor/autoload.php';
// Memuat koneksi database (kita anggap sudah ada file db.php)
require 'db.php';

// Impor kelas yang akan kita pakai dari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ambil kata kunci dari URL
$keyword = isset($_GET['keyword']) ? $koneksi->real_escape_string($_GET['keyword']) : "";

// Query untuk mengambil data dari database
$sql = "SELECT nim, nama, jurusan FROM mahasiswa WHERE nim LIKE '%$keyword%' OR nama LIKE '%$keyword%'";
$result = $koneksi->query($sql);

// Buat file Excel baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tulis header kolom di baris pertama
$sheet->setCellValue('A1', 'NIM');
$sheet->setCellValue('B1', 'Nama');
$sheet->setCellValue('C1', 'Jurusan');

// Tulis data dari database, mulai dari baris kedua
$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['nim']);
    $sheet->setCellValue('B' . $row, $data['nama']);
    $sheet->setCellValue('C' . $row, $data['jurusan']);
    $row++;
}

// Tentukan nama file Excel
$filename = "data_mahasiswa.xlsx";

// Kirim file ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

// Simpan file Excel dan kirim ke pengguna
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;