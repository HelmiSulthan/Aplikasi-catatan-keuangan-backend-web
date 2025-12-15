<?php
require __DIR__ . '/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) exit("ID tidak valid.");

$stmt = $pdo->prepare("DELETE FROM transaksi WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: index.php");
exit;
