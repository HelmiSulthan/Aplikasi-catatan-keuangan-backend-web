<?php
require __DIR__ . '/config.php';

$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

$where = [];
$params = [];

if ($start !== '') { $where[] = "t.tanggal >= :start"; $params[':start'] = $start; }
if ($end !== '')   { $where[] = "t.tanggal <= :end";   $params[':end']   = $end;   }

$sql = "
  SELECT t.*, k.nama AS kategori
  FROM transaksi t
  LEFT JOIN kategori k ON k.id = t.kategori_id
";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY t.tanggal DESC, t.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// ringkasan
$totalMasuk = 0.0;
$totalKeluar = 0.0;
foreach ($rows as $r) {
  if ($r['jenis'] === 'MASUK') $totalMasuk += (float)$r['nominal'];
  else $totalKeluar += (float)$r['nominal'];
}
$saldo = $totalMasuk - $totalKeluar;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Keuangan Pribadi</title>
  <style>
    body{font-family:system-ui,Arial; margin:20px; max-width:1000px}
    table{border-collapse:collapse; width:100%}
    th,td{border:1px solid #ddd; padding:8px}
    th{background:#f5f5f5; text-align:left}
    .row{display:flex; gap:12px; flex-wrap:wrap; align-items:end}
    .card{padding:12px; border:1px solid #ddd; border-radius:10px; min-width:200px}
    .btn{display:inline-block; padding:8px 12px; border:1px solid #333; border-radius:8px; text-decoration:none; color:#111}
    .btn:hover{background:#f3f3f3}
    .muted{color:#666}
  </style>
</head>
<body>
  <h1>📒 Pencatatan Keuangan Pribadi</h1>

  <div class="row">
    <form method="get" class="row">
      <div>
        <label class="muted">Dari</label><br>
        <input type="date" name="start" value="<?= e($start) ?>">
      </div>
      <div>
        <label class="muted">Sampai</label><br>
        <input type="date" name="end" value="<?= e($end) ?>">
      </div>
      <div>
        <button class="btn" type="submit">Filter</button>
        <a class="btn" href="index.php">Reset</a>
      </div>
    </form>

    <div style="margin-left:auto" class="row">
      <a class="btn" href="transaksi_form.php">+ Tambah Transaksi</a>
      <a class="btn" href="kategori.php">Kelola Kategori</a>
    </div>
  </div>

  <div class="row" style="margin-top:14px">
    <div class="card"><div class="muted">Total Masuk</div><b>Rp <?= number_format($totalMasuk, 0, ',', '.') ?></b></div>
    <div class="card"><div class="muted">Total Keluar</div><b>Rp <?= number_format($totalKeluar, 0, ',', '.') ?></b></div>
    <div class="card"><div class="muted">Saldo</div><b>Rp <?= number_format($saldo, 0, ',', '.') ?></b></div>
  </div>

  <h3 style="margin-top:18px">Daftar Transaksi</h3>
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Jenis</th>
        <th>Kategori</th>
        <th>Nominal</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="6" class="muted">Belum ada data.</td></tr>
      <?php endif; ?>

      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= e($r['tanggal']) ?></td>
          <td><?= e($r['jenis']) ?></td>
          <td><?= e($r['kategori'] ?? '-') ?></td>
          <td>Rp <?= number_format((float)$r['nominal'], 0, ',', '.') ?></td>
          <td><?= e($r['keterangan'] ?? '') ?></td>
          <td>
            <a class="btn" href="transaksi_form.php?id=<?= (int)$r['id'] ?>">Edit</a>
            <a class="btn" href="transaksi_delete.php?id=<?= (int)$r['id'] ?>"
               onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
