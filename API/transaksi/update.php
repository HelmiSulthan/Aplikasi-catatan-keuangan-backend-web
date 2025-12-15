<?php
require_once "../config.php";
require_once "../response.php";

$body = json_decode(file_get_contents("php://input"), true);
if (!$body || empty($body['id'])) {
  jsonResponse(false, "ID wajib diisi", null, 400);
}

$stmt = $pdo->prepare("
  UPDATE transaksi SET
    tanggal = :tanggal,
    jenis = :jenis,
    kategori_id = :kategori_id,
    nominal = :nominal,
    keterangan = :keterangan
  WHERE id = :id
");

$stmt->execute([
  ":tanggal" => $body['tanggal'],
  ":jenis" => $body['jenis'],
  ":kategori_id" => $body['kategori_id'] ?? null,
  ":nominal" => $body['nominal'],
  ":keterangan" => $body['keterangan'] ?? null,
  ":id" => $body['id']
]);

jsonResponse(true, "Transaksi berhasil diupdate");
