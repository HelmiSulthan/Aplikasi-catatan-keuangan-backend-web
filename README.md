#BACK END DARI APLIKASI KEUANGAN
# 💾 Skema Database Keuangan Pribadi

Dokumen ini menjelaskan struktur database SQL untuk aplikasi pencatatan keuangan pribadi (`keuangan_pribadi`).

## 🛠️ Kode SQL Lengkap

Berikut adalah kode SQL lengkap yang mencakup pembuatan database, skema tabel, dan data awal (`seeder`).

```sql
-- 1. PEMBUATAN DATABASE
CREATE DATABASE IF NOT EXISTS keuangan_pribadi
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE keuangan_pribadi;

-- 2. PEMBUATAN TABEL KATEGORI
CREATE TABLE IF NOT EXISTS kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL UNIQUE
);

-- 3. PEMBUATAN TABEL TRANSAKSI
CREATE TABLE IF NOT EXISTS transaksi (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  jenis ENUM('MASUK','KELUAR') NOT NULL,
  kategori_id INT NULL,
  nominal DECIMAL(14,2) NOT NULL,
  keterangan VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  
  -- Kunci Asing (Foreign Key)
  CONSTRAINT fk_kategori FOREIGN KEY (kategori_id) REFERENCES kategori(id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  
  -- Indeks untuk efisiensi pencarian
  INDEX idx_tanggal (tanggal),
  INDEX idx_jenis (jenis),
  INDEX idx_kategori (kategori_id)
);

-- 4. DATA AWAL (SEEDER) UNTUK KATEGORI
INSERT IGNORE INTO kategori (nama) VALUES
('Gaji'), 
('Makan'), 
('Transport'), 
('Belanja'), 
('Tagihan'), 
('Hiburan'), 
('Lainnya');
