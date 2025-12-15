<?php
require_once "../config.php";
require_once "../response.php";

$id = $_GET['id'] ?? null;
if (!$id) jsonResponse(false, "ID wajib diisi", null, 400);

$stmt = $pdo->prepare("
  SELECT t.*, k.nama AS kategori
  FROM transaksi t
  LEFT JOIN kategori k ON k.id = t.kategori_id
  WHERE t.id = :id
");
$stmt->execute([":id" => $id]);

$data = $stmt->fetch();
if (!$data) jsonResponse(false, "Data tidak ditemukan", null, 404);

jsonResponse(true, "Detail transaksi", $data);
