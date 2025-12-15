<?php
require __DIR__ . '/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$transaksi = [
  'id' => 0,
  'tanggal' => date('Y-m-d'),
  'jenis' => 'KELUAR',
  'kategori_id' => '',
  'nominal' => '',
  'keterangan' => ''
];

if ($id > 0) {
  $stmt = $pdo->prepare("SELECT * FROM transaksi WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $row = $stmt->fetch();
  if (!$row) exit("Data tidak ditemukan.");
  $transaksi = $row;
}

$kategori = $pdo->query("SELECT id, nama FROM kategori ORDER BY nama ASC")->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $id ? 'Edit' : 'Tambah' ?> Transaksi</title>
  <style>
    body{font-family:system-ui,Arial; margin:20px; max-width:700px}
    .row{margin-bottom:12px}
    label{display:block; margin-bottom:4px}
    input,select,textarea{width:100%; padding:8px; border:1px solid #ccc; border-radius:8px}
    .btn{display:inline-block; padding:8px 12px; border:1px solid #333; border-radius:8px; text-decoration:none; color:#111}
  </style>
</head>
<body>
  <h2><?= $id ? '✏️ Edit' : '➕ Tambah' ?> Transaksi</h2>

  <form method="post" action="transaksi_save.php">
    <input type="hidden" name="id" value="<?= (int)$transaksi['id'] ?>">

    <div class="row">
      <label>Tanggal</label>
      <input type="date" name="tanggal" required value="<?= e((string)$transaksi['tanggal']) ?>">
    </div>

    <div class="row">
      <label>Jenis</label>
      <select name="jenis" required>
        <option value="MASUK" <?= $transaksi['jenis']==='MASUK'?'selected':'' ?>>MASUK</option>
        <option value="KELUAR" <?= $transaksi['jenis']==='KELUAR'?'selected':'' ?>>KELUAR</option>
      </select>
    </div>

    <div class="row">
      <label>Kategori</label>
      <select name="kategori_id">
        <option value="">- (tanpa kategori) -</option>
        <?php foreach ($kategori as $k): ?>
          <option value="<?= (int)$k['id'] ?>"
            <?= ((string)$transaksi['kategori_id'] === (string)$k['id']) ? 'selected' : '' ?>>
            <?= e($k['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="row">
      <label>Nominal</label>
      <input type="number" name="nominal" step="0.01" min="0" required value="<?= e((string)$transaksi['nominal']) ?>">
    </div>

    <div class="row">
      <label>Keterangan</label>
      <textarea name="keterangan" rows="3"><?= e((string)$transaksi['keterangan']) ?></textarea>
    </div>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn" href="index.php">Kembali</a>
  </form>
</body>
</html>
