<?php
header('Content-Type: application/json');
include 'db.php';
$keyword = isset($_GET['keyword']) ? $koneksi->real_escape_string($_GET['keyword']) : "";
$sql = "SELECT nim, nama, jurusan FROM mahasiswa WHERE nim LIKE '%$keyword%' OR nama LIKE '%$keyword%'";
$result = $koneksi->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>