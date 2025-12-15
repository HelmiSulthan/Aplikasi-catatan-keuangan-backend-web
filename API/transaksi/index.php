<?php
require_once "../config.php";
require_once "../response.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$method = $_SERVER['REQUEST_METHOD'];

/* ===================== GET ===================== */
if ($method === 'GET') {

  $start = $_GET['start'] ?? null;
  $end   = $_GET['end'] ?? null;

  $where = [];
  $params = [];

  if ($start) {
    $where[] = "t.tanggal >= :start";
    $params[':start'] = $start;
  }
  if ($end) {
    $where[] = "t.tanggal <= :end";
    $params[':end'] = $end;
  }

  $sql = "
    SELECT t.*, k.nama AS kategori
    FROM transaksi t
    LEFT JOIN kategori k ON k.id = t.kategori_id
  ";

  if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
  }

  $sql .= " ORDER BY t.tanggal DESC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  jsonResponse(true, "List transaksi", $stmt->fetchAll());
}

/* ===================== CREATE ===================== */
if ($method === 'POST') {
  $body = json_decode(file_get_contents("php://input"), true);

  if (!$body) {
    jsonResponse(false, "Invalid JSON", null, 400);
  }

  // ===== VALIDASI WAJIB =====
  if (empty($body['tanggal'])) {
    jsonResponse(false, "Tanggal wajib diisi", null, 400);
  }

  if (!in_array($body['jenis'], ['MASUK', 'KELUAR'])) {
    jsonResponse(false, "Jenis transaksi tidak valid", null, 400);
  }

  if (!isset($body['nominal']) || !is_numeric($body['nominal'])) {
    jsonResponse(false, "Nominal tidak valid", null, 400);
  }

  // ===== NORMALISASI =====
  $kategoriId = array_key_exists('kategori_id', $body)
    ? $body['kategori_id']
    : null;

  try {
    $stmt = $pdo->prepare("
      INSERT INTO transaksi (tanggal, jenis, kategori_id, nominal, keterangan)
      VALUES (:tanggal, :jenis, :kategori_id, :nominal, :keterangan)
    ");

    $stmt->execute([
      ":tanggal" => $body['tanggal'],
      ":jenis" => $body['jenis'],
      ":kategori_id" => $kategoriId,
      ":nominal" => $body['nominal'],
      ":keterangan" => $body['keterangan'] ?? null,
    ]);

    jsonResponse(true, "Transaksi berhasil ditambahkan");
  } catch (PDOException $e) {
    jsonResponse(false, $e->getMessage(), null, 500);
  }
}

jsonResponse(false, "Method not allowed", null, 405);
