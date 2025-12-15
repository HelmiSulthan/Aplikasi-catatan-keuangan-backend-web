<?php
require_once "../config.php";
require_once "../response.php";

$id = $_GET['id'] ?? null;
if (!$id) jsonResponse(false, "ID wajib diisi", null, 400);

$stmt = $pdo->prepare("DELETE FROM transaksi WHERE id = :id");
$stmt->execute([":id" => $id]);

jsonResponse(true, "Transaksi berhasil dihapus");
