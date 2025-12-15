<?php
require_once "../config.php";
require_once "../response.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $stmt = $pdo->query("SELECT * FROM kategori ORDER BY nama ASC");
  jsonResponse(true, "List kategori", $stmt->fetchAll());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $body = json_decode(file_get_contents("php://input"), true);
  if (!$body || empty($body['nama'])) {
    jsonResponse(false, "Nama kategori wajib", null, 400);
  }

  $stmt = $pdo->prepare("INSERT INTO kategori (nama) VALUES (:nama)");
  $stmt->execute([":nama" => $body['nama']]);

  jsonResponse(true, "Kategori berhasil ditambahkan");
}

jsonResponse(false, "Method not allowed", null, 405);
