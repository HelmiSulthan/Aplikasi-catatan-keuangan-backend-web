<?php
require_once "../config.php";
require_once "../response.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
  $masuk = $pdo->query("
    SELECT COALESCE(SUM(nominal), 0)
    FROM transaksi
    WHERE jenis = 'MASUK'
  ")->fetchColumn();

  $keluar = $pdo->query("
    SELECT COALESCE(SUM(nominal), 0)
    FROM transaksi
    WHERE jenis = 'KELUAR'
  ")->fetchColumn();

  $saldo = $masuk - $keluar;

  jsonResponse(true, "Dashboard summary", [
    "total_masuk" => (float)$masuk,
    "total_keluar" => (float)$keluar,
    "saldo" => (float)$saldo,
  ]);
} catch (PDOException $e) {
  jsonResponse(false, $e->getMessage(), null, 500);
}
