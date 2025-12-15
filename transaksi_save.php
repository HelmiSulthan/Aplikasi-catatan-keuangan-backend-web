<?php
require __DIR__ . '/config.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$tanggal = $_POST['tanggal'] ?? '';
$jenis = $_POST['jenis'] ?? '';
$kategori_id = $_POST['kategori_id'] ?? '';
$nominal = $_POST['nominal'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) exit("Tanggal tidak valid.");
if (!in_array($jenis, ['MASUK','KELUAR'], true)) exit("Jenis tidak valid.");
if ($nominal === '' || !is_numeric($nominal) || (float)$nominal < 0) exit("Nominal tidak valid.");

$kategori_id_db = ($kategori_id === '') ? null : (int)$kategori_id;

if ($id > 0) {
  $stmt = $pdo->prepare("
    UPDATE transaksi
    SET tanggal=:tanggal, jenis=:jenis, kategori_id=:kategori_id, nominal=:nominal, keterangan=:keterangan
    WHERE id=:id
  ");
  $stmt->execute([
    ':tanggal' => $tanggal,
    ':jenis' => $jenis,
    ':kategori_id' => $kategori_id_db,
    ':nominal' => $nominal,
    ':keterangan' => $keterangan,
    ':id' => $id
  ]);
} else {
  $stmt = $pdo->prepare("
    INSERT INTO transaksi (tanggal, jenis, kategori_id, nominal, keterangan)
    VALUES (:tanggal, :jenis, :kategori_id, :nominal, :keterangan)
  ");
  $stmt->execute([
    ':tanggal' => $tanggal,
    ':jenis' => $jenis,
    ':kategori_id' => $kategori_id_db,
    ':nominal' => $nominal,
    ':keterangan' => $keterangan,
  ]);
}

header("Location: index.php");
exit;
