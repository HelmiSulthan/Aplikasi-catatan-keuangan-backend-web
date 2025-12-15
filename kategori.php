<?php
require __DIR__ . '/config.php';

// tambah
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama'] ?? '');
  if ($nama !== '') {
    $stmt = $pdo->prepare("INSERT IGNORE INTO kategori (nama) VALUES (:nama)");
    $stmt->execute([':nama' => $nama]);
  }
  header("Location: kategori.php");
  exit;
}

// hapus
if (isset($_GET['del'])) {
  $del = (int)$_GET['del'];
  if ($del > 0) {
    $stmt = $pdo->prepare("DELETE FROM kategori WHERE id = :id");
    $stmt->execute([':id' => $del]);
  }
  header("Location: kategori.php");
  exit;
}

$rows = $pdo->query("SELECT * FROM kategori ORDER BY nama ASC")->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Kategori</title>
  <style>
    body{font-family:system-ui,Arial; margin:20px; max-width:700px}
    table{border-collapse:collapse; width:100%}
    th,td{border:1px solid #ddd; padding:8px}
    th{background:#f5f5f5; text-align:left}
    .btn{display:inline-block; padding:8px 12px; border:1px solid #333; border-radius:8px; text-decoration:none; color:#111}
  </style>
</head>
<body>
  <h2>🏷️ Kelola Kategori</h2>

  <form method="post" style="display:flex; gap:8px; margin-bottom:12px">
    <input name="nama" placeholder="Nama kategori baru" required style="flex:1; padding:8px; border-radius:8px; border:1px solid #ccc">
    <button class="btn" type="submit">Tambah</button>
    <a class="btn" href="index.php">Kembali</a>
  </form>

  <table>
    <thead><tr><th>Nama</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= e($r['nama']) ?></td>
          <td>
            <a class="btn" href="kategori.php?del=<?= (int)$r['id'] ?>"
               onclick="return confirm('Hapus kategori ini?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
