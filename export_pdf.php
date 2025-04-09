<?php
// Memuat library dari Composer
require 'vendor/autoload.php';
// Memuat koneksi database
require 'db.php';

// Impor kelas Dompdf
use Dompdf\Dompdf;

// Ambil kata kunci dari URL
$keyword = isset($_GET['keyword']) ? $koneksi->real_escape_string($_GET['keyword']) : "";

// Query untuk mengambil data
$sql = "SELECT nim, nama, jurusan FROM mahasiswa WHERE nim LIKE '%$keyword%' OR nama LIKE '%$keyword%'";
$result = $koneksi->query($sql);

// Buat HTML untuk PDF
$html = '<h3 style="text-align:center;">Data Mahasiswa</h3>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                </tr>
            </thead>
            <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                <td>' . $row['nim'] . '</td>
                <td>' . $row['nama'] . '</td>
                <td>' . $row['jurusan'] . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Kirim PDF ke browser
$dompdf->stream("data_mahasiswa.pdf", ["Attachment" => true]);
exit;