#temp_buku_bank

DROP TABLE IF EXISTS temp_buku_bank;
DROP VIEW IF EXISTS temp_buku_bank;
CREATE VIEW temp_buku_bank as
select `acc_tipe_akun`.`id` AS `id_tipe_akun`,`acc_tipe_akun`.`nama` AS `nama_tipe_akun`,`acc_akun`.`id` AS `id_akun`,`acc_akun`.`nama` AS `nama_akun`,`acc_jurnal_detail`.`debet` AS `total_debet`,`acc_jurnal_detail`.`kredit` AS `total_kredit`,`acc_jurnal_detail`.`keterangan` AS `keterangan`,`acc_jurnal_detail`.`id` AS `id_jurnal_detail`,`acc_jurnal_detail`.`header_id` AS `header_id`,`acc_jurnal_header`.`tanggal_jurnal` AS `tanggal_jurnal`,`v_saldo_akun`.`saldo` AS `saldo` from ((((`acc_tipe_akun` left join `acc_akun` on((`acc_akun`.`tipe_akun_id` = `acc_tipe_akun`.`id`))) left join `acc_jurnal_detail` on((`acc_jurnal_detail`.`kode_akun` = `acc_akun`.`id`))) left join `v_saldo_akun` on((`v_saldo_akun`.`id` = `acc_akun`.`id`))) left join `acc_jurnal_header` on((`acc_jurnal_header`.`id` = `acc_jurnal_detail`.`header_id`))) where ((`acc_tipe_akun`.`nama` = 'Kas/Bank') and isnull(`acc_jurnal_detail`.`deleted_at`)); 

#temp_history_akun 
DROP TABLE IF EXISTS temp_history_akun;
DROP VIEW IF EXISTS temp_history_akun;
CREATE VIEW temp_history_akun as
select `acc_jurnal_header`.`id` AS `jurnal_header_id`,
`acc_jurnal_header`.`nomor_jurnal` AS `nomor_jurnal`,
`acc_jurnal_header`.`tanggal_jurnal` AS `tanggal_jurnal`,
`acc_jurnal_detail`.`keterangan` AS `keterangan`,
`acc_jurnal_detail`.`id` AS `jurnal_detail_id`,
`acc_jurnal_detail`.`kode_akun` AS `kode_akun`,
`acc_akun`.`no_akun` AS `nomor_akun`,
`acc_akun`.`nama` AS `akun`,
`acc_jurnal_detail`.`debet` AS `debet`,`acc_jurnal_detail`.`kredit` AS `kredit`,`v_saldo_akun`.`nama_tipe_akun` AS `nama_tipe`,ifnull((select `SaldoPerTransaksiAkun`(`acc_jurnal_detail`.`kode_akun`,(select `SaldoSebelumAkun`(`acc_jurnal_detail`.`kode_akun`,`acc_jurnal_detail`.`id`)),`acc_jurnal_detail`.`kredit`,`acc_jurnal_detail`.`debet`,`acc_jurnal_detail`.`id`)),0) AS `saldo`,ifnull((select `SaldoSebelumAkun`(`acc_jurnal_detail`.`kode_akun`,`acc_jurnal_detail`.`id`)),0) AS `saldo_awal`,if((`acc_jurnal_header`.`is_manual` = 1),'Manual','Otomatis') AS `tipe_jurnal`,(select `cekTipe`(`acc_jurnal_detail`.`kode_akun`)) AS `tipe_akun` from (((`acc_jurnal_header` join `acc_jurnal_detail`) join `acc_akun`) join `v_saldo_akun`) where ((`acc_jurnal_header`.`id` = `acc_jurnal_detail`.`header_id`) and (`acc_akun`.`id` = `acc_jurnal_detail`.`kode_akun`) and (`acc_jurnal_detail`.`kode_akun` = `v_saldo_akun`.`id`) and isnull(`acc_jurnal_detail`.`deleted_at`)) order by `acc_jurnal_header`.`id`,`acc_jurnal_detail`.`id`;


##temp_mutasi_barang
DROP TABLE IF EXISTS temp_mutasi_barang;
DROP VIEW IF EXISTS temp_mutasi_barang;
CREATE VIEW temp_mutasi_barang as
select `pengiriman_pembelian_detail`.`id` AS `id`,`pengiriman_pembelian_detail`.`harga_jasa_id` AS `harga_jasa_id`,`pengiriman_pembelian_detail`.`kuantitas` AS `kuantitas`,'Pengiriman Pembelian' AS `keterangan`,`pengiriman_pembelian_header`.`nomor` AS `nomor`,`pengiriman_pembelian_header`.`tanggal` AS `tanggal` from (`pengiriman_pembelian_detail` join `pengiriman_pembelian_header`) where (isnull(`pengiriman_pembelian_detail`.`deleted_at`) and (`pengiriman_pembelian_detail`.`pengiriman_pembelian_header_id` = `pengiriman_pembelian_header`.`id`) and `pengiriman_pembelian_detail`.`harga_jasa_id` in (select `harga_jasa`.`id` from `harga_jasa` where (`harga_jasa`.`tipe` = 1))) union select `faktur_pembelian_detail`.`id` AS `id`,`faktur_pembelian_detail`.`harga_jasa_id` AS `harga_jasa_id`,`faktur_pembelian_detail`.`kuantitas` AS `kuantitas`,'Faktur Pembelian' AS `keterangan`,`faktur_pembelian_header`.`nomor` AS `nomor`,`faktur_pembelian_header`.`tanggal` AS `tanggal` from (`faktur_pembelian_detail` join `faktur_pembelian_header`) where (isnull(`faktur_pembelian_detail`.`pengiriman_pembelian_detail_id`) and isnull(`faktur_pembelian_detail`.`deleted_at`) and (`faktur_pembelian_detail`.`faktur_pembelian_header_id` = `faktur_pembelian_header`.`id`) and `faktur_pembelian_detail`.`harga_jasa_id` in (select `harga_jasa`.`id` from `harga_jasa` where (`harga_jasa`.`tipe` = 1))) union select `pengiriman_penjualan_detail`.`id` AS `id`,`pengiriman_penjualan_detail`.`harga_jasa_id` AS `harga_jasa_id`,`pengiriman_penjualan_detail`.`kuantitas` AS `kuantitas`,'Pengiriman Penjualan' AS `keterangan`,`pengiriman_penjualan_header`.`nomor` AS `nomor`,`pengiriman_penjualan_header`.`tanggal` AS `tanggal` from (`pengiriman_penjualan_detail` join `pengiriman_penjualan_header`) where (isnull(`pengiriman_penjualan_detail`.`deleted_at`) and (`pengiriman_penjualan_detail`.`pengiriman_penjualan_header_id` = `pengiriman_penjualan_header`.`id`) and `pengiriman_penjualan_detail`.`harga_jasa_id` in (select `harga_jasa`.`id` from `harga_jasa` where (`harga_jasa`.`tipe` = 1))) union select `faktur_penjualan_detail`.`id` AS `id`,`faktur_penjualan_detail`.`harga_jasa_id` AS `harga_jasa_id`,`faktur_penjualan_detail`.`kuantitas` AS `kuantitas`,'Faktur Penjualan' AS `keterangan`,`faktur_penjualan_header`.`nomor` AS `nomor`,`faktur_penjualan_header`.`tanggal` AS `tanggal` from (`faktur_penjualan_detail` join `faktur_penjualan_header`) where (isnull(`faktur_penjualan_detail`.`pengiriman_penjualan_detail_id`) and isnull(`faktur_penjualan_detail`.`deleted_at`) and (`faktur_penjualan_detail`.`faktur_penjualan_header_id` = `faktur_penjualan_header`.`id`) and `faktur_penjualan_detail`.`harga_jasa_id` in (select `harga_jasa`.`id` from `harga_jasa` where (`harga_jasa`.`tipe` = 1))); 

##temp_uang_muka_pembelian
DROP TABLE IF EXISTS temp_uang_muka_pembelian;
DROP VIEW IF EXISTS temp_uang_muka_pembelian;
CREATE VIEW temp_uang_muka_pembelian as
select `faktur_pembelian_header`.`id` AS `id_faktur_pembelian`,`faktur_pembelian_header`.`pemasok_id` AS `id_pemasok`,(select sum(`faktur_pembelian_detail`.`harga`) from `faktur_pembelian_detail` where (`faktur_pembelian_detail`.`faktur_pembelian_header_id` = `faktur_pembelian_header`.`id`)) AS `uang_muka_awal`,(select `faktur_pembelian_uang_muka_detail`.`id` from `faktur_pembelian_uang_muka_detail` where (`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_header_id` = `faktur_pembelian_header`.`id`)) AS `id_uang_muka`,ifnull((select sum(`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`) from `faktur_pembelian_uang_muka_detail` where (`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_uang_muka_header_id` = `faktur_pembelian_header`.`id`)),0) AS `uang_muka_terpakai`,((select sum(`faktur_pembelian_detail`.`harga`) from `faktur_pembelian_detail` where (`faktur_pembelian_detail`.`faktur_pembelian_header_id` = `faktur_pembelian_header`.`id`)) - ifnull((select sum(`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`) from `faktur_pembelian_uang_muka_detail` where (`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_uang_muka_header_id` = `faktur_pembelian_header`.`id`)),0)) AS `sisa_uang_muka` from (`faktur_pembelian_uang_muka_detail` join `faktur_pembelian_header`) where (`faktur_pembelian_header`.`is_uang_muka` = 1) group by `id_faktur_pembelian`; 

##temp_uang_muka_penjualan
DROP TABLE IF EXISTS temp_uang_muka_penjualan;
DROP VIEW IF EXISTS temp_uang_muka_penjualan;
CREATE VIEW temp_uang_muka_penjualan as
select `faktur_penjualan_header`.`id` AS `id_faktur_penjualan`,`faktur_penjualan_header`.`pelanggan_id` AS `id_pelanggan`,(select sum(`faktur_penjualan_detail`.`harga`) from `faktur_penjualan_detail` where (`faktur_penjualan_detail`.`faktur_penjualan_header_id` = `faktur_penjualan_header`.`id`)) AS `uang_muka_awal`,(select `faktur_penjualan_uang_muka_detail`.`id` from `faktur_penjualan_uang_muka_detail` where (`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_header_id` = `faktur_penjualan_header`.`id`)) AS `id_uang_muka`,ifnull((select sum(`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`) from `faktur_penjualan_uang_muka_detail` where (`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_uang_muka_header_id` = `faktur_penjualan_header`.`id`)),0) AS `uang_muka_terpakai`,((select sum(`faktur_penjualan_detail`.`harga`) from `faktur_penjualan_detail` where (`faktur_penjualan_detail`.`faktur_penjualan_header_id` = `faktur_penjualan_header`.`id`)) - ifnull((select sum(`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`) from `faktur_penjualan_uang_muka_detail` where (`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_uang_muka_header_id` = `faktur_penjualan_header`.`id`)),0)) AS `sisa_uang_muka` from (`faktur_penjualan_uang_muka_detail` join `faktur_penjualan_header`) where (`faktur_penjualan_header`.`is_uang_muka` = 1) group by `id_faktur_penjualan`; 

##view_barang_per_gudang
DROP TABLE IF EXISTS view_barang_per_gudang;
DROP VIEW IF EXISTS view_barang_per_gudang;
CREATE VIEW view_barang_per_gudang as
SELECT DISTINCT view_mutasi_stok.harga_jasa_id,view_mutasi_stok.gudang_tujuan_id,gudang.nama, 
(SELECT getBarangTiapGudang(harga_jasa_id,gudang_tujuan_id)) AS jumlah_barang 
from view_mutasi_stok,gudang WHERE view_mutasi_stok.gudang_tujuan_id = gudang.id; 

##view_buku_bank
DROP TABLE IF EXISTS view_buku_bank;
DROP VIEW IF EXISTS view_buku_bank;
CREATE VIEW view_buku_bank as
select `view_kas_bank`.`jurnal_header_id` AS `jurnal_header_id`,`view_kas_bank`.`nomor_jurnal` AS `nomor_jurnal`,`view_kas_bank`.`tanggal_jurnal` AS `tanggal_jurnal`,`view_kas_bank`.`keterangan` AS `keterangan`,`view_kas_bank`.`tipe_jurnal` AS `tipe_jurnal`,`view_kas_bank`.`jurnal_detail_id` AS `jurnal_detail_id`,`view_kas_bank`.`kode_akun` AS `kode_akun`,`view_kas_bank`.`akun` AS `akun`,`view_kas_bank`.`debet` AS `debet`,`view_kas_bank`.`kredit` AS `kredit`,(select `saldoPerTransaksiAkun`(`view_kas_bank`.`kode_akun`,`SaldoSebelumAkun`(`view_kas_bank`.`kode_akun`,`view_kas_bank`.`jurnal_detail_id`),`view_kas_bank`.`kredit`,`view_kas_bank`.`debet`,`view_kas_bank`.`jurnal_detail_id`)) AS `saldo`,((select `total_debet_sebelum`(`view_kas_bank`.`kode_akun`,`view_kas_bank`.`tanggal_jurnal`,`view_kas_bank`.`jurnal_detail_id`)) - (select `total_kredit_sebelum`(`view_kas_bank`.`kode_akun`,`view_kas_bank`.`tanggal_jurnal`,`view_kas_bank`.`jurnal_detail_id`))) AS `saldo_awal` from `view_kas_bank` order by `view_kas_bank`.`jurnal_detail_id`; 

##view_data_hutang
DROP TABLE IF EXISTS view_data_hutang;
DROP VIEW IF EXISTS view_data_hutang;
CREATE VIEW view_data_hutang as
select distinct `view_hutang`.`faktur_pembelian_pemasok_id` AS `pemasok_id`,
`pemasok`.`nama` AS `pemasok_nama`,
(SELECT SUM(sisa_hutang) AS sisa_hutang 
FROM view_hutang WHERE faktur_pembelian_pemasok_id = pemasok.id ) AS `total_hutang` 

from (`view_hutang` join `pemasok`) 
where (`view_hutang`.`faktur_pembelian_pemasok_id` = `pemasok`.`id`); 

##view_data_piutang
DROP TABLE IF EXISTS view_data_piutang;
DROP VIEW IF EXISTS view_data_piutang;
CREATE VIEW view_data_piutang as
select distinct `pelanggan`.`nama` AS `pelangga_nama`,`pelanggan`.`id` AS `pelanggan_id`,
(SELECT SUM(sisa_piutang) AS sisa_hutang 
FROM view_piutang WHERE faktur_penjualan_pelanggan_id = pelanggan.id ) AS `total_piutang`  from (`view_piutang` join `pelanggan`) where (`view_piutang`.`faktur_penjualan_pelanggan_id` = `pelanggan`.`id`); 

##view_harga_pesanan
DROP TABLE IF EXISTS view_harga_pesanan;
DROP VIEW IF EXISTS view_harga_pesanan;
CREATE VIEW view_harga_pesanan as
SELECT pesanan_pembelian_detail.harga_jasa_id,pesanan_pembelian_detail.harga,
pesanan_pembelian_header.tanggal,pesanan_pembelian_header.created_at 
FROM pesanan_pembelian_detail,pesanan_pembelian_header 
WHERE pesanan_pembelian_detail.pesanan_pembelian_header_id = pesanan_pembelian_header.id 
and ISNULL(pesanan_pembelian_detail.deleted_at); 

##view_history_stok
DROP TABLE IF EXISTS view_history_stok;
DROP VIEW IF EXISTS view_history_stok;
CREATE VIEW view_history_stok as
select `stok`.`id` AS `id`,`stok`.`tanggal` AS `tanggal`,`stok`.`harga_jasa_id` AS `harga_jasa_id`,`stok`.`harga` AS `harga`,`stok`.`nomor_seri` AS `nomor_seri`,`stok`.`gudang` AS `gudang`,`stok`.`created_at` AS `created_at`,`stok`.`created_by` AS `created_by`,`stok`.`updated_at` AS `updated_at`,`stok`.`updated_by` AS `updated_by`,`stok`.`deleted_at` AS `deleted_at`,`stok`.`deleted_by` AS `deleted_by`,if(`stok`.`id` in (select `view_stok_tersedia`.`id` from `view_stok_tersedia`),'Belum Terjual','Telah Terjual') AS `status_terjual`,if(`stok`.`id` in (select `stok_detail`.`stok_id` from `stok_detail` where ((`stok_detail`.`penerimaan_pembelian_detail_id` is not null) and isnull(`stok_detail`.`deleted_at`))),'Penerimaan Pembelian','Faktur Pembelian') AS `sumber`,(select `pengiriman_pembelian_detail`.`pengiriman_pembelian_header_id` from `pengiriman_pembelian_detail` where `pengiriman_pembelian_detail`.`id` in (select `stok_detail`.`penerimaan_pembelian_detail_id` from `stok_detail` where (`stok_detail`.`stok_id` = `stok`.`id`)) limit 1) AS `id_sumber_penerimaan`,(select `faktur_pembelian_detail`.`faktur_pembelian_header_id` from `faktur_pembelian_detail` where `faktur_pembelian_detail`.`id` in (select `stok_detail`.`faktur_pembelian_detail_id` from `stok_detail` where (`stok_detail`.`stok_id` = `stok`.`id`)) limit 1) AS `id_sumber_faktur`,(select `pengiriman_penjualan_detail`.`pengiriman_penjualan_header_id` from `pengiriman_penjualan_detail` where `pengiriman_penjualan_detail`.`id` in (select `stok_detail`.`pengiriman_penjualan_detail_id` from `stok_detail` where ((`stok_detail`.`stok_id` = `stok`.`id`) and isnull(`stok_detail`.`deleted_at`))) limit 1) AS `id_keluaran_pengiriman`,(select `faktur_penjualan_detail`.`faktur_penjualan_header_id` from `faktur_penjualan_detail` where `faktur_penjualan_detail`.`id` in (select `stok_detail`.`faktur_penjualan_detail_id` from `stok_detail` where ((`stok_detail`.`stok_id` = `stok`.`id`) and isnull(`stok_detail`.`deleted_at`))) limit 1) AS `id_keluaran_faktur` from `stok` where isnull(`stok`.`deleted_at`); 

##view_hutang
DROP TABLE IF EXISTS view_hutang;
DROP VIEW IF EXISTS view_hutang;
CREATE VIEW view_hutang as
select `accounting`.`faktur_pembelian_header`.`id` AS `faktur_pembelian_header_id`,`accounting`.`faktur_pembelian_header`.`nomor` AS `nomor`,`accounting`.`faktur_pembelian_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman`,`accounting`.`faktur_pembelian_header`.`pemasok_id` AS `faktur_pembelian_pemasok_id`,`accounting`.`faktur_pembelian_header`.`is_uang_muka` AS `is_uang_muka`,`accounting`.`faktur_pembelian_header`.`deleted_at` AS `deleted_at`,`accounting`.`faktur_pembelian_header`.`total_harga` AS `nominal_faktur_pembelian`,`faktur_pembelian_detail`.`deleted_by` AS `deleted_by`,ifnull(sum(`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`),0) AS `nominal_faktur_pembelian_uang_muka`,ifnull(sum(`pembayaran_pembelian_detail`.`nominal_pembayaran`),0) AS `nominal_pembayaran_pembelian`,((`accounting`.`faktur_pembelian_header`.`total_harga` - ifnull(sum(`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`),0)) - ifnull(sum(`pembayaran_pembelian_detail`.`nominal_pembayaran`),0)) AS `sisa_hutang` from (((`accounting`.`faktur_pembelian_header` left join (select sum(`accounting`.`faktur_pembelian_detail`.`harga`) AS `harga`,`accounting`.`faktur_pembelian_detail`.`faktur_pembelian_header_id` AS `faktur_pembelian_header_id`,`accounting`.`faktur_pembelian_detail`.`deleted_by` AS `deleted_by` from `accounting`.`faktur_pembelian_detail` where isnull(`accounting`.`faktur_pembelian_detail`.`deleted_by`) group by `accounting`.`faktur_pembelian_detail`.`faktur_pembelian_header_id`) `faktur_pembelian_detail` on((`accounting`.`faktur_pembelian_header`.`id` = `faktur_pembelian_detail`.`faktur_pembelian_header_id`))) left join (select sum(`accounting`.`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`) AS `uang_muka_terpakai`,`accounting`.`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_header_id` AS `faktur_pembelian_header_id` from `accounting`.`faktur_pembelian_uang_muka_detail` where isnull(`accounting`.`faktur_pembelian_uang_muka_detail`.`deleted_by`) group by `accounting`.`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_header_id`) `faktur_pembelian_uang_muka_detail` on((`accounting`.`faktur_pembelian_header`.`id` = `faktur_pembelian_uang_muka_detail`.`faktur_pembelian_header_id`))) left join (select sum(`accounting`.`pembayaran_pembelian_detail`.`nominal_pembayaran`) AS `nominal_pembayaran`,`accounting`.`pembayaran_pembelian_detail`.`faktur_pembelian_header_id` AS `faktur_pembelian_header_id` from `accounting`.`pembayaran_pembelian_detail` where isnull(`accounting`.`pembayaran_pembelian_detail`.`deleted_by`) group by `accounting`.`pembayaran_pembelian_detail`.`faktur_pembelian_header_id`) `pembayaran_pembelian_detail` on((`accounting`.`faktur_pembelian_header`.`id` = `pembayaran_pembelian_detail`.`faktur_pembelian_header_id`))) group by `accounting`.`faktur_pembelian_header`.`id` having isnull(`accounting`.`faktur_pembelian_header`.`deleted_at`) order by `accounting`.`faktur_pembelian_header`.`tanggal`; 

##view_jurnal_akun
DROP TABLE IF EXISTS view_jurnal_akun;
DROP VIEW IF EXISTS view_jurnal_akun;
CREATE VIEW view_jurnal_akun as
select if((`acc_jurnal_header`.`is_manual` = 1),'Manual','Otomatis') AS `tipe_jurnal`,0 AS `saldo`,`acc_jurnal_detail`.`kredit` AS `kredit`,`acc_jurnal_header`.`id` AS `jurnal_header_id`,`acc_jurnal_header`.`tanggal_jurnal` AS `tanggal_jurnal`,`acc_jurnal_header`.`is_manual` AS `is_manual`,`acc_jurnal_header`.`nomor_jurnal` AS `nomor_jurnal`,`acc_jurnal_detail`.`id` AS `jurnal_detail_id`,`acc_jurnal_detail`.`debet` AS `debet`,`acc_jurnal_detail`.`keterangan` AS `keterangan`,`acc_jurnal_detail`.`kode_akun` AS `kode_akun`,`acc_tipe_akun`.`tipe` AS `tipe_akun`,`acc_tipe_akun`.`nama` AS `nama_tipe`,`acc_akun`.`nama` AS `akun`,`acc_akun`.`no_akun` AS `nomor_akun` from (((`acc_jurnal_header` join `acc_jurnal_detail`) join `acc_tipe_akun`) join `acc_akun`) where ((`acc_jurnal_detail`.`kode_akun` = `acc_akun`.`id`) and (`acc_akun`.`tipe_akun_id` = `acc_tipe_akun`.`id`) and (`acc_jurnal_header`.`id` = `acc_jurnal_detail`.`header_id`) and (`acc_jurnal_detail`.`deleted_at` is null)); 

##view_kas_bank
DROP TABLE IF EXISTS view_kas_bank;
DROP VIEW IF EXISTS view_kas_bank;
CREATE VIEW view_kas_bank as
select `acc_jurnal_header`.`id` AS `jurnal_header_id`,`acc_jurnal_header`.`nomor_jurnal` AS `nomor_jurnal`,`acc_jurnal_header`.`tanggal_jurnal` AS `tanggal_jurnal`,`acc_jurnal_header`.`keterangan` AS `keterangan`,`acc_jurnal_detail`.`id` AS `jurnal_detail_id`,`acc_jurnal_detail`.`kode_akun` AS `kode_akun`,`acc_akun`.`nama` AS `akun`,`acc_jurnal_detail`.`debet` AS `debet`,`acc_jurnal_detail`.`kredit` AS `kredit`,`acc_jurnal_detail`.`created_at` AS `created_at`,`acc_jurnal_detail`.`updated_at` AS `updated_at`,if((`acc_jurnal_header`.`is_manual` = 1),'Manual','Otomatis') AS `tipe_jurnal` from ((`acc_jurnal_header` join `acc_jurnal_detail`) join `acc_akun`) where ((`acc_jurnal_header`.`id` = `acc_jurnal_detail`.`header_id`) and (`acc_akun`.`id` = `acc_jurnal_detail`.`kode_akun`) and isnull(`acc_jurnal_detail`.`deleted_at`) and `acc_jurnal_detail`.`kode_akun` in (select `acc_akun`.`id` from `acc_akun` where `acc_akun`.`parent_id` in (select `acc_tipe_akun`.`id` from `acc_tipe_akun` where (`acc_tipe_akun`.`nama` = 'Kas/Bank')))) group by `jurnal_detail_id`; 

##view_laporan_penjualan
DROP TABLE IF EXISTS view_laporan_penjualan;
DROP VIEW IF EXISTS view_laporan_penjualan;
CREATE VIEW view_laporan_penjualan as
select `faktur_penjualan_header`.`pelanggan_id` AS `pelanggan`,`pelanggan`.`nama` AS `nama_pelanggan`,`faktur_penjualan_header`.`nomor` AS `nomor`,`faktur_penjualan_header`.`tanggal` AS `tanggal`,`faktur_penjualan_detail`.`harga_jasa_id` AS `barang_jasa`,`faktur_penjualan_detail`.`kuantitas` AS `kuantitas`,`faktur_penjualan_detail`.`harga` AS `harga`,`harga_jasa`.`kode` AS `kode`,`harga_jasa`.`keterangan` AS `keterangan` from (((`faktur_penjualan_header` join `pelanggan`) join `faktur_penjualan_detail`) join `harga_jasa`) where ((`faktur_penjualan_header`.`id` = `faktur_penjualan_detail`.`faktur_penjualan_header_id`) and (`faktur_penjualan_detail`.`harga_jasa_id` = `harga_jasa`.`id`) and isnull(`faktur_penjualan_detail`.`deleted_at`) and (`faktur_penjualan_header`.`pelanggan_id` = `pelanggan`.`id`) and (`faktur_penjualan_header`.`is_uang_muka` = 0)); 

##view_menu_pengguna
DROP TABLE IF EXISTS view_menu_pengguna;
DROP VIEW IF EXISTS view_menu_pengguna;
CREATE VIEW view_menu_pengguna as
select `acc_users`.`id` AS `id_pengguna`,`daftar_fungsi`.`id` AS `id`,`daftar_fungsi`.`nama` AS `nama`,`daftar_fungsi`.`route` AS `route`,`daftar_fungsi`.`parent_id` AS `parent_id` from ((`acc_users` join `daftar_fungsi`) join `daftar_route_user`) where ((`acc_users`.`id` = `daftar_route_user`.`user_id`) and (`daftar_fungsi`.`id` = `daftar_route_user`.`route_id`) and isnull(`daftar_fungsi`.`deleted_at`)); 

##view_mutasi_sn
DROP TABLE IF EXISTS view_mutasi_sn;
DROP VIEW IF EXISTS view_mutasi_sn;
CREATE VIEW view_mutasi_sn as
SELECT *,(SELECT cekBarang(penerimaan_pembelian_detail_id,
faktur_pembelian_detail_id,
pengiriman_penjualan_detail_id,
faktur_penjualan_detail_id,transfer_barang_detail_id,retur_pembelian_detail_id,retur_penjualan_detail_id))AS harga_jasa_id,
(SELECT cekGudang(
penerimaan_pembelian_detail_id,
faktur_pembelian_detail_id,
pengiriman_penjualan_detail_id,
faktur_penjualan_detail_id,
transfer_barang_detail_id,
retur_pembelian_detail_id,
retur_penjualan_detail_id))AS gudang_id,
(SELECT getTanggal(penerimaan_pembelian_detail_id,
faktur_pembelian_detail_id,
pengiriman_penjualan_detail_id,
faktur_penjualan_detail_id,
transfer_barang_detail_id,retur_pembelian_detail_id,retur_penjualan_detail_id)) AS tanggal 
FROM nomor_seri_transaksi WHERE ISNULL(deleted_at); 

##view_mutasi_stok
DROP TABLE IF EXISTS view_mutasi_stok;
DROP VIEW IF EXISTS view_mutasi_stok;
CREATE VIEW view_mutasi_stok as
SELECT  kuantitas, harga_jasa_id, 		
id AS pengiriman_pembelian_detail_id,	
null as faktur_pembelian_detail_id,	
null as retur_pembelian_detail_id,	
null as pengiriman_penjualan_detail_id,	
null as faktur_penjualan_detail_id,	
null as retur_penjualan_detail_id,	
null as transfer_barang_detail_id,	
null as gudang_asal_id,	
(SELECT cekGudang(id,NULL,NULL,NULL,NULL,NULL,null)) as gudang_tujuan_id 
FROM pengiriman_pembelian_detail
WHERE ISNULL(deleted_at)
UNION
select kuantitas, harga_jasa_id, 	
	
null as pengiriman_detail_id,	
id as faktur_pembelian_detail_id,	
null as retur_pembelian_detail_id,	
null as pengiriman_penjualan_detail_id,	
null as faktur_penjualan_detail_id,	
null as retur_penjualan_detail_id,	
null as transfer_barang_detail_id,	
null as gudang_asal_id,	
(SELECT cekGudang(NULL,id,NULL,NULL,NULL,NULL,null)) as gudang_tujuan_id 
FROM faktur_pembelian_detail
WHERE ISNULL(deleted_at) AND isnull(pengiriman_pembelian_detail_id)
UNION
SELECT kuantitas, harga_jasa_id, 	
	
null as pengiriman_detail_id,	
null as faktur_pembelian_detail_id,	
null as retur_pembelian_detail_id,	
id as pengiriman_penjualan_detail_id,	
null as faktur_penjualan_detail_id,	
null as retur_penjualan_detail_id,	
null as transfer_barang_detail_id,	
null as gudang_asal_id,	
(SELECT cekGudang(NULL,null,id,NULL,NULL,NULL,null)) as gudang_tujuan_id 
FROM pengiriman_penjualan_detail
WHERE ISNULL(deleted_at)
UNION
select kuantitas, harga_jasa_id, 	
	
null as pengiriman_detail_id,	
null as faktur_pembelian_detail_id,	
null as retur_pembelian_detail_id,	
null as pengiriman_penjualan_detail_id,	
id as faktur_penjualan_detail_id,	
null as retur_penjualan_detail_id,	
null as transfer_barang_detail_id,	
null as gudang_asal_id,	
(SELECT cekGudang(NULL,NULL,NULL,id,NULL,NULL,null))
as gudang_tujuan_id FROM faktur_penjualan_detail
WHERE ISNULL(deleted_at) AND ISNULL(pengiriman_penjualan_detail_id)
UNION
select kuantitas, harga_jasa_id, 	
	
null as pengiriman_detail_id,	
null as faktur_pembelian_detail_id,	
null as retur_pembelian_detail_id,	
null as pengiriman_penjualan_detail_id,	
null as faktur_penjualan_detail_id,	
null as retur_penjualan_detail_id,	
id as transfer_barang_detail_id,	
(SELECT gudang_asal_id FROM transfer_barang_header 
WHERE id = transfer_barang_detail.transfer_barang_header_id) as gudang_asal_id,	
(SELECT cekGudang(NULL,NULL,NULL,NULL,id,NULL,null))
as gudang_tujuan_id FROM transfer_barang_detail
WHERE ISNULL(deleted_at)
UNION
SELECT kuantitas,
(SELECT getBarangReturPembelian(pengiriman_pembelian_detail_id,faktur_pembelian_detail_id)),
	
null as pengiriman_detail_id,	
null as faktur_pembelian_detail_id,	
id as retur_pembelian_detail_id,	
null as pengiriman_penjualan_detail_id,	
null as faktur_penjualan_detail_id,	
null as retur_penjualan_detail_id,	
null as transfer_barang_detail_id,	 
null as gudang_asal_id,	
(SELECT cekGudang(NULL,NULL,NULL,NULL,null,id,null))
as gudang_tujuan_id FROM retur_pembelian_detail
WHERE ISNULL(deleted_at)
UNION
SELECT kuantitas,
(SELECT getBarangReturPenjualan(pengiriman_penjualan_detail_id,faktur_penjualan_detail_id)),
	
null as pengiriman_detail_id,	
null as faktur_pembelian_detail_id,	
null as retur_pembelian_detail_id,	
null as pengiriman_penjualan_detail_id,	
null as faktur_penjualan_detail_id,	
id as retur_penjualan_detail_id,	
null as transfer_barang_detail_id,	 
null as gudang_asal_id,	
(SELECT cekGudang(NULL,NULL,NULL,NULL,null,null,id))
as gudang_tujuan_id FROM retur_penjualan_detail
WHERE ISNULL(deleted_at); 

##view_pembelian_barang
DROP TABLE IF EXISTS view_pembelian_barang;
DROP VIEW IF EXISTS view_pembelian_barang;
CREATE VIEW view_pembelian_barang as
SELECT 
harga_jasa_id,
(SELECT kode FROM harga_jasa WHERE id = faktur_pembelian_detail.harga_jasa_id) AS harga_jasa,
kuantitas,
harga, 
(SELECT tanggal FROM faktur_pembelian_header 
WHERE id = faktur_pembelian_detail.faktur_pembelian_header_id) AS tanggal
 FROM faktur_pembelian_detail
WHERE isnull(faktur_pembelian_detail.deleted_at); 

##view_pengiriman_untuk_petunjuk_penjualan
DROP TABLE IF EXISTS view_pengiriman_untuk_petunjuk_penjualan;
DROP VIEW IF EXISTS view_pengiriman_untuk_petunjuk_penjualan;
CREATE VIEW view_pengiriman_untuk_petunjuk_penjualan as
select `view_transaksi_pengiriman_penjualan_belum_selesai`.`gudang_id` AS `gudang_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`pengiriman_penjualan_header_id` AS `pengiriman_penjualan_header_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`id` AS `id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`nomor` AS `nomor`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`tanggal` AS `tanggal`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`pelanggan_id` AS `pelanggan_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`vendor` AS `vendor`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`jadwal_pengiriman_id` AS `jadwal_pengiriman_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`nomor_pengangkutan_barang` AS `nomor_pengangkutan_barang`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`pengiriman_penjualan_detail_id` AS `pengiriman_penjualan_detail_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`pesanan_penjualan_detail_id` AS `pesanan_penjualan_detail_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`id_detail` AS `id_detail`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`pesanan_penjualan_header_id` AS `pesanan_penjualan_header_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`harga` AS `harga`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`harga_jasa_id` AS `harga_jasa_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`jadwal` AS `jadwal`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`transaksi_id` AS `transaksi_id`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`kuantitas` AS `kuantitas`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`kuantitas_terpakai` AS `kuantitas_terpakai`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`sisa_kuantitas` AS `sisa_kuantitas`,`view_transaksi_pengiriman_penjualan_belum_selesai`.`deleted_at` AS `deleted_at` from `accounting`.`view_transaksi_pengiriman_penjualan_belum_selesai` where (not(`view_transaksi_pengiriman_penjualan_belum_selesai`.`id_detail` in (select `accounting`.`petunjuk_pengiriman_detail`.`pengiriman_penjualan_detail_id` from `accounting`.`petunjuk_pengiriman_detail` where isnull(`accounting`.`petunjuk_pengiriman_detail`.`deleted_at`)))); 

##view_piutang
DROP TABLE IF EXISTS view_piutang;
DROP VIEW IF EXISTS view_piutang;
CREATE VIEW view_piutang as
select `accounting`.`faktur_penjualan_header`.`id` AS `faktur_penjualan_header_id`,`accounting`.`faktur_penjualan_header`.`nomor` AS `nomor`,`accounting`.`faktur_penjualan_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman`,`accounting`.`faktur_penjualan_header`.`pelanggan_id` AS `faktur_penjualan_pelanggan_id`,`accounting`.`faktur_penjualan_header`.`is_uang_muka` AS `is_uang_muka`,ifnull(sum(`accounting`.`faktur_penjualan_header`.`total_harga`),0) AS `nominal_faktur_penjualan`,ifnull(sum(`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`),0) AS `nominal_faktur_penjualan_uang_muka`,ifnull(sum(`penerimaan_penjualan_detail`.`nominal_penjualan`),0) AS `nominal_penerimaan_penjualan`,((ifnull(sum(`accounting`.`faktur_penjualan_header`.`total_harga`),0) - ifnull(sum(`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`),0)) - ifnull(sum(`penerimaan_penjualan_detail`.`nominal_penjualan`),0)) AS `sisa_piutang`,`accounting`.`faktur_penjualan_header`.`deleted_at` AS `deleted_at` from (((`accounting`.`faktur_penjualan_header` left join (select (sum(`accounting`.`faktur_penjualan_detail`.`harga`) * `accounting`.`faktur_penjualan_detail`.`kuantitas`) AS `harga`,`accounting`.`faktur_penjualan_detail`.`faktur_penjualan_header_id` AS `faktur_penjualan_header_id` from `accounting`.`faktur_penjualan_detail` where isnull(`accounting`.`faktur_penjualan_detail`.`deleted_by`) group by `accounting`.`faktur_penjualan_detail`.`faktur_penjualan_header_id`) `faktur_penjualan_detail` on((`accounting`.`faktur_penjualan_header`.`id` = `faktur_penjualan_detail`.`faktur_penjualan_header_id`))) left join (select sum(`accounting`.`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`) AS `uang_muka_terpakai`,`accounting`.`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_header_id` AS `faktur_penjualan_header_id` from `accounting`.`faktur_penjualan_uang_muka_detail` where isnull(`accounting`.`faktur_penjualan_uang_muka_detail`.`deleted_by`) group by `accounting`.`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_header_id`) `faktur_penjualan_uang_muka_detail` on((`accounting`.`faktur_penjualan_header`.`id` = `faktur_penjualan_uang_muka_detail`.`faktur_penjualan_header_id`))) left join (select sum(`accounting`.`penerimaan_penjualan_detail`.`nominal_penjualan`) AS `nominal_penjualan`,`accounting`.`penerimaan_penjualan_detail`.`faktur_penjualan_header_id` AS `faktur_penjualan_header_id` from `accounting`.`penerimaan_penjualan_detail` where isnull(`accounting`.`penerimaan_penjualan_detail`.`deleted_at`) group by `accounting`.`penerimaan_penjualan_detail`.`faktur_penjualan_header_id`) `penerimaan_penjualan_detail` on((`accounting`.`faktur_penjualan_header`.`id` = `penerimaan_penjualan_detail`.`faktur_penjualan_header_id`))) group by `accounting`.`faktur_penjualan_header`.`id` having isnull(`accounting`.`faktur_penjualan_header`.`deleted_at`); 

##view_status_sn
DROP TABLE IF EXISTS view_status_sn;
DROP VIEW IF EXISTS view_status_sn;
CREATE VIEW view_status_sn as
SELECT harga_jasa_id,nomor_seri,
(SELECT cekStatus(view_mutasi_sn.harga_jasa_id,view_mutasi_sn.nomor_seri)) AS status,
(SELECT getGudangAkhir(view_mutasi_sn.harga_jasa_id,view_mutasi_sn.nomor_seri)) AS gudang

FROM view_mutasi_sn GROUP BY harga_jasa_id,nomor_seri; 

## view_stok_fifo_avg
DROP TABLE IF EXISTS view_stok_fifo_avg;
DROP VIEW IF EXISTS view_stok_fifo_avg;
CREATE VIEW view_stok_fifo_avg as
SELECT *,

(SELECT cekGudang(

stok_fifo_average.pengiriman_pembelian_detail_id,
stok_fifo_average.faktur_pembelian_detail_id,
stok_fifo_average.pengiriman_penjualan_detail_id,
stok_fifo_average.faktur_penjualan_detail_id,
NULL,NULL,null

) )AS gudang_id,

kuantitas-IFNULL((SELECT SUM(kuantitas)  FROM stok_detail_fifo_average 
WHERE stok_id = stok_fifo_average.id AND ISNULL(deleted_at)),0)
AS kuantitas_butuh,

IFNULL((SELECT SUM(kuantitas)  FROM stok_detail_fifo_average 
WHERE stok_asal_id = stok_fifo_average.id AND ISNULL(deleted_at)),0)
AS kuantitas_terpakai,

if(kuantitas > IFNULL((SELECT SUM(kuantitas)  FROM stok_detail_fifo_average 
WHERE stok_id = stok_fifo_average.id AND ISNULL(deleted_at)),0) ,1,0)
AS minus,

kuantitas - IFNULL((SELECT SUM(kuantitas)  FROM stok_detail_fifo_average 
WHERE stok_asal_id = stok_fifo_average.id and ISNULL(deleted_at)),0)
AS kuantitas_tersedia,

if(kuantitas-IFNULL((SELECT SUM(kuantitas)  FROM stok_detail_fifo_average 
WHERE stok_id = stok_fifo_average.id AND ISNULL(deleted_at)),0) < kuantitas ,1,0)
AS terdetail,

IFNULL((SELECT SUM(kuantitas)  FROM stok_fifo_average WHERE  ISNULL(deleted_at) 
AND ISNULL(pengiriman_penjualan_detail_id) 
AND ISNULL(faktur_penjualan_detail_id)),0) -
IFNULL((SELECT SUM(kuantitas)  FROM stok_fifo_average WHERE  ISNULL(deleted_at) 
AND ISNULL(pengiriman_pembelian_detail_id) 
AND ISNULL(faktur_pembelian_detail_id)),0) AS posisi_stok,

(SELECT average FROM stok_fifo_average WHERE  ISNULL(deleted_at) 
AND ISNULL(pengiriman_penjualan_detail_id) 
AND ISNULL(faktur_penjualan_detail_id)
ORDER BY tanggal desc,created_at desc LIMIT 1) AS average_akhir

FROM stok_fifo_average 
WHERE ISNULL(deleted_at) 

ORDER BY tanggal,created_at asc; 

##view_stok_per_gudang
DROP TABLE IF EXISTS view_stok_per_gudang;
DROP VIEW IF EXISTS view_stok_per_gudang;
CREATE VIEW view_stok_per_gudang as
SELECT *,
(SELECT getBarangTiapGudang(view_mutasi_stok.harga_jasa_id,view_mutasi_stok.gudang_tujuan_id)) AS jumlah
FROM view_mutasi_stok 
GROUP BY  harga_jasa_id,gudang_tujuan_id; 

##view_stok_tersedia
DROP TABLE IF EXISTS view_stok_tersedia;
DROP VIEW IF EXISTS view_stok_tersedia;
CREATE VIEW view_stok_tersedia as
select `stok`.`id` AS `id`,
`stok`.`tanggal` AS `tanggal`,
`stok`.`harga_jasa_id` AS `harga_jasa_id`,
`stok`.`harga` AS `harga`,
stok.created_at,
stok.created_by,
stok.updated_at,
stok.updated_by,
stok.deleted_at,
stok.deleted_by,
(SELECT AVG(harga) FROM stok WHERE stok.id NOT IN((select `stok_detail`.`stok_id` 
from `stok_detail` 
where (isnull(`stok_detail`.`deleted_at`) 
and ((`stok_detail`.`pengiriman_penjualan_detail_id` is not NULL) 
or (`stok_detail`.`faktur_penjualan_detail_id` is not NULL)))))) AS harga_average,
`stok`.`nomor_seri` AS `nomor_seri`,
`stok`.`gudang` AS `gudang`,
`gudang`.`nama` AS `nama_gudang`
from (`stok` join `gudang`) 
where ((not(`stok`.`id` IN 
(select `stok_detail`.`stok_id` 
from `stok_detail` 
where (isnull(`stok_detail`.`deleted_at`) 
and ((`stok_detail`.`pengiriman_penjualan_detail_id` is not NULL) 
or (`stok_detail`.`faktur_penjualan_detail_id` is not NULL))))
)) 
and isnull(`stok`.`deleted_at`) 
and (`gudang`.`id` = `stok`.`gudang`)); 

##view_transaksi_penawaran_penjualan_belum_selesai
DROP TABLE IF EXISTS view_transaksi_penawaran_penjualan_belum_selesai;
DROP VIEW IF EXISTS view_transaksi_penawaran_penjualan_belum_selesai;
CREATE VIEW view_transaksi_penawaran_penjualan_belum_selesai as
select `accounting`.`penawaran_penjualan_header`.`id` AS `penawaran_penjualan_header_id`,`accounting`.`penawaran_penjualan_header`.`id` AS `id`,`accounting`.`penawaran_penjualan_header`.`nomor` AS `nomor`,`accounting`.`penawaran_penjualan_header`.`pelanggan_id` AS `pelanggan_id`,`accounting`.`penawaran_penjualan_header`.`pelanggan_id` AS `vendor`,`accounting`.`penawaran_penjualan_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman_id`,`accounting`.`penawaran_penjualan_header`.`kena_pajak` AS `kena_pajak`,`accounting`.`penawaran_penjualan_header`.`termasuk_pajak` AS `termasuk_pajak`,`accounting`.`penawaran_penjualan_header`.`total_harga` AS `total_harga`,`accounting`.`penawaran_penjualan_detail`.`id` AS `penawaran_penjualan_detail_id`,`accounting`.`penawaran_penjualan_detail`.`id` AS `id_detail`,`accounting`.`penawaran_penjualan_detail`.`harga_jasa_id` AS `harga_jasa_id`,`accounting`.`penawaran_penjualan_detail`.`kuantitas` AS `kuantitas`,`accounting`.`penawaran_penjualan_detail`.`kode_pajak_id` AS `kode_pajak-id`,`accounting`.`penawaran_penjualan_detail`.`jadwal_pengiriman_id` AS `jadwal`,`transaksi_syarat_pengiriman`.`id` AS `transaksi_id`,`accounting`.`penawaran_penjualan_detail`.`harga` AS `harga`,((ifnull(sum(`pesanan_penjualan_detail`.`kuantitas`),0) + ifnull(sum(`pengiriman_penjualan_detail`.`kuantitas`),0)) + ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0)) AS `kuantitas_terpakai`,(((`accounting`.`penawaran_penjualan_detail`.`kuantitas` - ifnull(sum(`pesanan_penjualan_detail`.`kuantitas`),0)) - ifnull(sum(`pengiriman_penjualan_detail`.`kuantitas`),0)) - ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0)) AS `sisa_kuantitas` from (((((`accounting`.`penawaran_penjualan_detail` left join `accounting`.`penawaran_penjualan_header` on(((`accounting`.`penawaran_penjualan_detail`.`penawaran_penjualan_header_id` = `accounting`.`penawaran_penjualan_header`.`id`) and isnull(`accounting`.`penawaran_penjualan_header`.`deleted_at`)))) left join (select sum(`accounting`.`pesanan_penjualan_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`pesanan_penjualan_detail`.`penawaran_penjualan_detail_id` AS `penawaran_penjualan_detail_id` from `accounting`.`pesanan_penjualan_detail` where isnull(`accounting`.`pesanan_penjualan_detail`.`deleted_at`) group by `accounting`.`pesanan_penjualan_detail`.`penawaran_penjualan_detail_id`) `pesanan_penjualan_detail` on((`pesanan_penjualan_detail`.`penawaran_penjualan_detail_id` = `accounting`.`penawaran_penjualan_detail`.`id`))) left join (select sum(`accounting`.`pengiriman_penjualan_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`pengiriman_penjualan_detail`.`penawaran_penjualan_detail_id` AS `penawaran_penjualan_detail_id` from `accounting`.`pengiriman_penjualan_detail` where isnull(`accounting`.`pengiriman_penjualan_detail`.`deleted_at`) group by `accounting`.`pengiriman_penjualan_detail`.`penawaran_penjualan_detail_id`) `pengiriman_penjualan_detail` on((`pengiriman_penjualan_detail`.`penawaran_penjualan_detail_id` = `accounting`.`penawaran_penjualan_detail`.`id`))) left join (select sum(`accounting`.`faktur_penjualan_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`faktur_penjualan_detail`.`penawaran_penjualan_detail_id` AS `penawaran_penjualan_detail_id` from `accounting`.`faktur_penjualan_detail` where isnull(`accounting`.`faktur_penjualan_detail`.`deleted_at`) group by `accounting`.`faktur_penjualan_detail`.`penawaran_penjualan_detail_id`) `faktur_penjualan_detail` on((`faktur_penjualan_detail`.`penawaran_penjualan_detail_id` = `accounting`.`penawaran_penjualan_detail`.`id`))) left join (select `accounting`.`transaksi_syarat_pengiriman`.`id` AS `id`,`accounting`.`transaksi_syarat_pengiriman`.`penawaran_penjualan_detail_id` AS `penawaran_penjualan_detail_id` from `accounting`.`transaksi_syarat_pengiriman`) `transaksi_syarat_pengiriman` on((`accounting`.`penawaran_penjualan_detail`.`id` = `transaksi_syarat_pengiriman`.`penawaran_penjualan_detail_id`))) where isnull(`accounting`.`penawaran_penjualan_detail`.`deleted_at`) group by `accounting`.`penawaran_penjualan_detail`.`id` having (((ifnull(sum(`pesanan_penjualan_detail`.`kuantitas`),0) + ifnull(sum(`pengiriman_penjualan_detail`.`kuantitas`),0)) + ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0)) < `accounting`.`penawaran_penjualan_detail`.`kuantitas`); 

##view_transaksi_pengiriman_pembelian_belum_selesai
DROP TABLE IF EXISTS view_transaksi_pengiriman_pembelian_belum_selesai;
DROP VIEW IF EXISTS view_transaksi_pengiriman_pembelian_belum_selesai;
CREATE VIEW view_transaksi_pengiriman_pembelian_belum_selesai as
select `accounting`.`pengiriman_pembelian_header`.`gudang_id` AS `gudang_id`,`accounting`.`pengiriman_pembelian_header`.`id` AS `pengiriman_pembelian_header_id`,`accounting`.`pengiriman_pembelian_header`.`id` AS `id`,`accounting`.`pengiriman_pembelian_header`.`nomor` AS `nomor`,`accounting`.`pengiriman_pembelian_header`.`tanggal` AS `tanggal`,`accounting`.`pengiriman_pembelian_header`.`pemasok_id` AS `pemasok_id`,`accounting`.`pengiriman_pembelian_header`.`pemasok_id` AS `vendor`,`accounting`.`pengiriman_pembelian_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman_id`,`accounting`.`pengiriman_pembelian_header`.`nomor_release_order` AS `nomor_release_order`,`accounting`.`pengiriman_pembelian_detail`.`id` AS `pengiriman_pembelian_detail_id`,`accounting`.`pengiriman_pembelian_detail`.`id` AS `id_detail`,`accounting`.`pengiriman_pembelian_detail`.`pesanan_pembelian_detail_id` AS `pesanan_pembelian_detail_id`,
`accounting`.`pesanan_pembelian_detail`.`pesanan_pembelian_header_id` AS `pesanan_pembelian_header_id`,
`accounting`.`pesanan_pembelian_detail`.`harga` AS `harga`,
`accounting`.`pesanan_pembelian_detail`.`permintaan_pembelian_detail_id` AS `permintaan_pembelian_detail_id`,
`accounting`.`pengiriman_pembelian_detail`.`harga_jasa_id` AS `harga_jasa_id`,
`accounting`.`pengiriman_pembelian_detail`.`jadwal_pengiriman_id` AS `jadwal`,
`transaksi_syarat_pengiriman`.`id` AS `transaksi_id`,
`accounting`.`pengiriman_pembelian_detail`.`kuantitas` AS `kuantitas`,
ifnull(sum(`faktur_pembelian_detail`.`kuantitas`),0) + IFNULL(SUM(`retur_pembelian_detail`.`kuantitas`),0) AS `kuantitas_terpakai`,
(`accounting`.`pengiriman_pembelian_detail`.`kuantitas` - ifnull(sum(`faktur_pembelian_detail`.`kuantitas`),0) -
ifnull(sum(`retur_pembelian_detail`.`kuantitas`),0))
AS `sisa_kuantitas` 
from ((((`accounting`.`pengiriman_pembelian_detail` 
left join `accounting`.`pengiriman_pembelian_header` 
on(((`accounting`.`pengiriman_pembelian_detail`.`pengiriman_pembelian_header_id` = 
`accounting`.`pengiriman_pembelian_header`.`id`) 
and isnull(`accounting`.`pengiriman_pembelian_header`.`deleted_at`)))) 
left join `accounting`.`pesanan_pembelian_detail` 
on(((`accounting`.`pengiriman_pembelian_detail`.`pesanan_pembelian_detail_id` = 
`accounting`.`pesanan_pembelian_detail`.`id`) 
and isnull(`accounting`.`pesanan_pembelian_detail`.`deleted_at`)))) 
left join (select `accounting`.`transaksi_syarat_pengiriman`.`id` AS `id`,`accounting`.
`transaksi_syarat_pengiriman`.`pengiriman_pembelian_detail_id` AS `pengiriman_pembelian_detail_id` 
from `accounting`.`transaksi_syarat_pengiriman`) `transaksi_syarat_pengiriman` 
on((`accounting`.`pengiriman_pembelian_detail`.`id` = `transaksi_syarat_pengiriman`.`pengiriman_pembelian_detail_id`))) 
left join (select sum(`accounting`.`faktur_pembelian_detail`.`kuantitas`) AS `kuantitas`,
`accounting`.`faktur_pembelian_detail`.`pengiriman_pembelian_detail_id` AS `pengiriman_pembelian_detail_id` 
from `accounting`.`faktur_pembelian_detail` 
where isnull(`accounting`.`faktur_pembelian_detail`.`deleted_at`) 
group by `accounting`.`faktur_pembelian_detail`.`pengiriman_pembelian_detail_id`) `faktur_pembelian_detail` 
on((`faktur_pembelian_detail`.`pengiriman_pembelian_detail_id` = `accounting`.`pengiriman_pembelian_detail`.`id`))) 
##
left join (select sum(`accounting`.`retur_pembelian_detail`.`kuantitas`) AS `kuantitas`,
`accounting`.`retur_pembelian_detail`.`pengiriman_pembelian_detail_id` AS `pengiriman_pembelian_detail_id` 
from `accounting`.`retur_pembelian_detail` 
where isnull(`accounting`.`retur_pembelian_detail`.`deleted_at`) 
group by `accounting`.`retur_pembelian_detail`.`pengiriman_pembelian_detail_id`) `retur_pembelian_detail` 
on((`retur_pembelian_detail`.`pengiriman_pembelian_detail_id` = `accounting`.`pengiriman_pembelian_detail`.`id`))
##
WHERE isnull(`accounting`.`pengiriman_pembelian_detail`.`deleted_at`) 
group by `accounting`.`pengiriman_pembelian_detail`.`id` 
having (ifnull(sum(`faktur_pembelian_detail`.`kuantitas`),0) < `accounting`.`pengiriman_pembelian_detail`.`kuantitas`); 

##view_transaksi_pengiriman_penjualan_belum_selesai
##Pengiriman Penjualan Belum Selesai Lama

DROP TABLE IF EXISTS view_transaksi_pengiriman_penjualan_belum_selesai;
DROP VIEW IF EXISTS view_transaksi_pengiriman_penjualan_belum_selesai;
CREATE VIEW view_transaksi_pengiriman_penjualan_belum_selesai as

select `accounting`.`pengiriman_penjualan_header`.`gudang_id` AS `gudang_id`,
`accounting`.`pengiriman_penjualan_header`.`id` AS `pengiriman_penjualan_header_id`,
`accounting`.`pengiriman_penjualan_header`.`id` AS `id`,
`accounting`.`pengiriman_penjualan_header`.`nomor` AS `nomor`,
`accounting`.`pengiriman_penjualan_header`.`tanggal` AS `tanggal`,
`accounting`.`pengiriman_penjualan_header`.`pelanggan_id` AS `pelanggan_id`,
`accounting`.`pengiriman_penjualan_header`.`pelanggan_id` AS `vendor`,
`accounting`.`pengiriman_penjualan_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman_id`,
`accounting`.`pengiriman_penjualan_header`.`nomor_pengangkutan_barang` AS `nomor_pengangkutan_barang`,
`accounting`.`pengiriman_penjualan_detail`.`id` AS `pengiriman_penjualan_detail_id`,
`accounting`.`pengiriman_penjualan_detail`.`pesanan_penjualan_detail_id` AS `pesanan_penjualan_detail_id`,
`accounting`.`pengiriman_penjualan_detail`.`id` AS `id_detail`,
`accounting`.`pesanan_penjualan_detail`.`pesanan_penjualan_header_id` AS `pesanan_penjualan_header_id`,
`accounting`.`pesanan_penjualan_detail`.`harga` AS `harga`,
`accounting`.`pesanan_penjualan_detail`.`penawaran_penjualan_detail_id` AS `penawaran_penjualan_detail_id`,
`accounting`.`pengiriman_penjualan_detail`.`harga_jasa_id` AS `harga_jasa_id`,
`accounting`.`pengiriman_penjualan_detail`.`jadwal_pengiriman_id` AS `jadwal`,
`transaksi_syarat_pengiriman`.`id` AS `transaksi_id`,
`accounting`.`pengiriman_penjualan_detail`.`kuantitas` AS `kuantitas`,
ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0) + IFNULL(SUM(`retur_penjualan_detail`.`kuantitas`),0) 
AS `kuantitas_terpakai`,
(`accounting`.`pengiriman_penjualan_detail`.`kuantitas` - 
ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0) - IFNULL(SUM(`retur_penjualan_detail`.`kuantitas`),0)) AS `sisa_kuantitas`,
`accounting`.`pengiriman_penjualan_header`.`deleted_at` AS `deleted_at` 
from ((((`accounting`.`pengiriman_penjualan_detail` 
left join `accounting`.`pengiriman_penjualan_header` 
on(((`accounting`.`pengiriman_penjualan_detail`.`pengiriman_penjualan_header_id` = 
`accounting`.`pengiriman_penjualan_header`.`id`) AND
 isnull(`accounting`.`pengiriman_penjualan_header`.`deleted_at`)))) 
 left join `accounting`.`pesanan_penjualan_detail` 
 on(((`accounting`.`pengiriman_penjualan_detail`.`pesanan_penjualan_detail_id` = 
 `accounting`.`pesanan_penjualan_detail`.`id`) and 
 isnull(`accounting`.`pesanan_penjualan_detail`.`deleted_at`)))) 
 left join (select `accounting`.`transaksi_syarat_pengiriman`.`id` AS `id`,
 `accounting`.`transaksi_syarat_pengiriman`.`pengiriman_penjualan_detail_id` AS `pengiriman_penjualan_detail_id` 
 from `accounting`.`transaksi_syarat_pengiriman`) `transaksi_syarat_pengiriman` 
 on((`accounting`.`pengiriman_penjualan_detail`.`id` = 
 `transaksi_syarat_pengiriman`.`pengiriman_penjualan_detail_id`)))
  
##
left join (select sum(`accounting`.`retur_penjualan_detail`.`kuantitas`) AS `kuantitas`,
`accounting`.`retur_penjualan_detail`.`pengiriman_penjualan_detail_id` AS `pengiriman_penjualan_detail_id` 
from `accounting`.`retur_penjualan_detail` 
where isnull(`accounting`.`retur_penjualan_detail`.`deleted_at`) 
group by `accounting`.`retur_penjualan_detail`.`pengiriman_penjualan_detail_id`) `retur_penjualan_detail` 
on((`retur_penjualan_detail`.`pengiriman_penjualan_detail_id` = `accounting`.`pengiriman_penjualan_detail`.`id`))
##
 left join (select sum(`accounting`.`faktur_penjualan_detail`.`kuantitas`) AS `kuantitas`,
 `accounting`.`faktur_penjualan_detail`.`pengiriman_penjualan_detail_id` AS `pengiriman_penjualan_detail_id` 
 from `accounting`.`faktur_penjualan_detail` where isnull(`accounting`.`faktur_penjualan_detail`.`deleted_at`) 
 group by `accounting`.`faktur_penjualan_detail`.`pengiriman_penjualan_detail_id`) `faktur_penjualan_detail` 
 on((`faktur_penjualan_detail`.`pengiriman_penjualan_detail_id` = `accounting`.`pengiriman_penjualan_detail`.`id`))) 
 where isnull(`accounting`.`pengiriman_penjualan_detail`.`deleted_at`) 
 group by `accounting`.`pengiriman_penjualan_detail`.`id` 
 having (ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0) < `accounting`.`pengiriman_penjualan_detail`.`kuantitas`) 

 ##view_transaksi_permintaan_pembelian_belum_selesai
 DROP TABLE IF EXISTS view_transaksi_permintaan_pembelian_belum_selesai;
DROP VIEW IF EXISTS view_transaksi_permintaan_pembelian_belum_selesai;
CREATE VIEW view_transaksi_permintaan_pembelian_belum_selesai as
select `accounting`.`permintaan_pembelian_header`.`id` AS `id`,`accounting`.`permintaan_pembelian_header`.`id` AS `permintaan_pembelian_header_id`,`accounting`.`permintaan_pembelian_header`.`nomor` AS `nomor`,`accounting`.`permintaan_pembelian_header`.`tanggal` AS `tanggal`,`accounting`.`permintaan_pembelian_header`.`pemasok_id` AS `pemasok_id`,`accounting`.`permintaan_pembelian_header`.`pemasok_id` AS `vendor`,`accounting`.`permintaan_pembelian_header`.`kena_pajak` AS `kena_pajak`,`accounting`.`permintaan_pembelian_header`.`kode_pajak` AS `kode_pajak`,`accounting`.`permintaan_pembelian_header`.`termasuk_pajak` AS `termasuk_pajak`,`accounting`.`permintaan_pembelian_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman`,`accounting`.`permintaan_pembelian_detail`.`id` AS `permintaan_pembelian_detail_id`,`accounting`.`permintaan_pembelian_detail`.`id` AS `id_detail`,`accounting`.`permintaan_pembelian_detail`.`harga_jasa_id` AS `harga_jasa_id`,`accounting`.`permintaan_pembelian_detail`.`jadwal_pengiriman_id` AS `jadwal`,`transaksi_syarat_pengiriman`.`id` AS `transaksi_id`,`accounting`.`permintaan_pembelian_detail`.`kuantitas` AS `kuantitas`,ifnull(sum(`pesanan_pembelian_detail`.`kuantitas`),0) AS `kuantitas_terpakai`,(`accounting`.`permintaan_pembelian_detail`.`kuantitas` - ifnull(sum(`pesanan_pembelian_detail`.`kuantitas`),0)) AS `sisa_kuantitas` from (((`accounting`.`permintaan_pembelian_detail` left join `accounting`.`permintaan_pembelian_header` on(((`accounting`.`permintaan_pembelian_detail`.`permintaan_pembelian_header_id` = `accounting`.`permintaan_pembelian_header`.`id`) and isnull(`accounting`.`permintaan_pembelian_header`.`deleted_at`)))) left join (select `accounting`.`transaksi_syarat_pengiriman`.`id` AS `id`,`accounting`.`transaksi_syarat_pengiriman`.`permintaan_pembelian_detail_id` AS `permintaan_pembelian_detail_id` from `accounting`.`transaksi_syarat_pengiriman`) `transaksi_syarat_pengiriman` on((`accounting`.`permintaan_pembelian_detail`.`id` = `transaksi_syarat_pengiriman`.`permintaan_pembelian_detail_id`))) left join (select sum(`accounting`.`pesanan_pembelian_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`pesanan_pembelian_detail`.`permintaan_pembelian_detail_id` AS `permintaan_pembelian_detail_id` from `accounting`.`pesanan_pembelian_detail` where isnull(`accounting`.`pesanan_pembelian_detail`.`deleted_at`) group by `accounting`.`pesanan_pembelian_detail`.`permintaan_pembelian_detail_id`) `pesanan_pembelian_detail` on((`pesanan_pembelian_detail`.`permintaan_pembelian_detail_id` = `accounting`.`permintaan_pembelian_detail`.`id`))) where isnull(`accounting`.`permintaan_pembelian_detail`.`deleted_at`) group by `accounting`.`permintaan_pembelian_detail`.`id` having (ifnull(sum(`pesanan_pembelian_detail`.`kuantitas`),0) < `accounting`.`permintaan_pembelian_detail`.`kuantitas`); 

 ##view_transaksi_pesanan_pembelian_belum_selesai
DROP TABLE IF EXISTS view_transaksi_pesanan_pembelian_belum_selesai;
DROP VIEW IF EXISTS view_transaksi_pesanan_pembelian_belum_selesai;
CREATE VIEW view_transaksi_pesanan_pembelian_belum_selesai as

 select `accounting`.`pesanan_pembelian_header`.`id` AS `pesanan_pembelian_header_id`,`accounting`.`pesanan_pembelian_header`.`id` AS `id`,`accounting`.`pesanan_pembelian_header`.`nomor` AS `nomor`,`accounting`.`pesanan_pembelian_header`.`tanggal` AS `tanggal`,`accounting`.`pesanan_pembelian_header`.`pemasok_id` AS `pemasok_id`,`accounting`.`pesanan_pembelian_header`.`pemasok_id` AS `vendor`,`accounting`.`pesanan_pembelian_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman_id`,`accounting`.`pesanan_pembelian_header`.`kena_pajak` AS `kena_pajak`,`accounting`.`pesanan_pembelian_header`.`termasuk_pajak` AS `termasuk_pajak`,`accounting`.`pesanan_pembelian_header`.`total_harga` AS `total_harga`,`accounting`.`pesanan_pembelian_detail`.`id` AS `pesanan_pembelian_detail_id`,`accounting`.`pesanan_pembelian_detail`.`id` AS `id_detail`,`accounting`.`pesanan_pembelian_detail`.`harga_jasa_id` AS `harga_jasa_id`,`accounting`.`pesanan_pembelian_detail`.`jadwal_pengiriman_id` AS `jadwal`,`transaksi_syarat_pengiriman`.`id` AS `transaksi_id`,`accounting`.`pesanan_pembelian_detail`.`kuantitas` AS `kuantitas`,`accounting`.`pesanan_pembelian_detail`.`harga` AS `detail_harga`,`accounting`.`pesanan_pembelian_detail`.`kode_pajak_id` AS `kode_pajak_id`,`accounting`.`pesanan_pembelian_detail`.`harga` AS `harga`,(ifnull(sum(`pengiriman_pembelian_detail`.`kuantitas`),0) + ifnull(sum(`faktur_pembelian_detail`.`kuantitas`),0)) AS `kuantitas_terpakai`,((`accounting`.`pesanan_pembelian_detail`.`kuantitas` - ifnull(sum(`pengiriman_pembelian_detail`.`kuantitas`),0)) - ifnull(sum(`faktur_pembelian_detail`.`kuantitas`),0)) AS `sisa_kuantitas` from ((((`accounting`.`pesanan_pembelian_detail` left join `accounting`.`pesanan_pembelian_header` on(((`accounting`.`pesanan_pembelian_detail`.`pesanan_pembelian_header_id` = `accounting`.`pesanan_pembelian_header`.`id`) and isnull(`accounting`.`pesanan_pembelian_header`.`deleted_at`)))) left join (select `accounting`.`transaksi_syarat_pengiriman`.`id` AS `id`,`accounting`.`transaksi_syarat_pengiriman`.`pesanan_pembelian_detail_id` AS `pesanan_pembelian_detail_id` from `accounting`.`transaksi_syarat_pengiriman`) `transaksi_syarat_pengiriman` on((`accounting`.`pesanan_pembelian_detail`.`id` = `transaksi_syarat_pengiriman`.`pesanan_pembelian_detail_id`))) left join (select sum(`accounting`.`pengiriman_pembelian_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`pengiriman_pembelian_detail`.`pesanan_pembelian_detail_id` AS `pesanan_pembelian_detail_id` from `accounting`.`pengiriman_pembelian_detail` where isnull(`accounting`.`pengiriman_pembelian_detail`.`deleted_at`) group by `accounting`.`pengiriman_pembelian_detail`.`pesanan_pembelian_detail_id`) `pengiriman_pembelian_detail` on((`pengiriman_pembelian_detail`.`pesanan_pembelian_detail_id` = `accounting`.`pesanan_pembelian_detail`.`id`))) left join (select sum(`accounting`.`faktur_pembelian_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`faktur_pembelian_detail`.`pesanan_pembelian_detail_id` AS `pesanan_pembelian_detail_id` from `accounting`.`faktur_pembelian_detail` where isnull(`accounting`.`faktur_pembelian_detail`.`deleted_at`) group by `accounting`.`faktur_pembelian_detail`.`pesanan_pembelian_detail_id`) `faktur_pembelian_detail` on((`faktur_pembelian_detail`.`pesanan_pembelian_detail_id` = `accounting`.`pesanan_pembelian_detail`.`id`))) where isnull(`accounting`.`pesanan_pembelian_detail`.`deleted_at`) group by `accounting`.`pesanan_pembelian_detail`.`id` having ((ifnull(sum(`pengiriman_pembelian_detail`.`kuantitas`),0) + ifnull(sum(`faktur_pembelian_detail`.`kuantitas`),0)) < `accounting`.`pesanan_pembelian_detail`.`kuantitas`); 

 ##view_transaksi_pesanan_penjualan_belum_selesai

 DROP TABLE IF EXISTS view_transaksi_pesanan_penjualan_belum_selesai;
DROP VIEW IF EXISTS view_transaksi_pesanan_penjualan_belum_selesai;
CREATE VIEW view_transaksi_pesanan_penjualan_belum_selesai as

 select `accounting`.`pesanan_penjualan_header`.`id` AS `pesanan_penjualan_header_id`,`accounting`.`pesanan_penjualan_header`.`id` AS `id`,`accounting`.`pesanan_penjualan_header`.`nomor` AS `nomor`,`accounting`.`pesanan_penjualan_header`.`tanggal` AS `tanggal`,`accounting`.`pesanan_penjualan_header`.`pelanggan_id` AS `pelanggan_id`,`accounting`.`pesanan_penjualan_header`.`pelanggan_id` AS `vendor`,`accounting`.`pesanan_penjualan_header`.`jadwal_pengiriman_id` AS `jadwal_pengiriman_id`,`accounting`.`pesanan_penjualan_header`.`kena_pajak` AS `kena_pajak`,`accounting`.`pesanan_penjualan_header`.`termasuk_pajak` AS `termasuk_pajak`,`accounting`.`pesanan_penjualan_header`.`total_harga` AS `total_harga`,`accounting`.`pesanan_penjualan_detail`.`id` AS `pesanan_penjualan_detail_id`,`accounting`.`pesanan_penjualan_detail`.`id` AS `id_detail`,`accounting`.`pesanan_penjualan_detail`.`harga_jasa_id` AS `harga_jasa_id`,`accounting`.`pesanan_penjualan_detail`.`jadwal_pengiriman_id` AS `jadwal`,`accounting`.`pesanan_penjualan_detail`.`kuantitas` AS `kuantitas`,`accounting`.`pesanan_penjualan_detail`.`kode_pajak_id` AS `kode_pajak`,`transaksi_syarat_pengiriman`.`id` AS `transaksi_id`,`accounting`.`pesanan_penjualan_detail`.`harga` AS `harga`,(ifnull(sum(`pengiriman_penjualan_detail`.`kuantitas`),0) + ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0)) AS `kuantitas_terpakai`,((`accounting`.`pesanan_penjualan_detail`.`kuantitas` - ifnull(sum(`pengiriman_penjualan_detail`.`kuantitas`),0)) - ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0)) AS `sisa_kuantitas` from ((((`accounting`.`pesanan_penjualan_detail` left join `accounting`.`pesanan_penjualan_header` on(((`accounting`.`pesanan_penjualan_detail`.`pesanan_penjualan_header_id` = `accounting`.`pesanan_penjualan_header`.`id`) and isnull(`accounting`.`pesanan_penjualan_header`.`deleted_by`)))) left join (select sum(`accounting`.`pengiriman_penjualan_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`pengiriman_penjualan_detail`.`pesanan_penjualan_detail_id` AS `pesanan_penjualan_detail_id` from `accounting`.`pengiriman_penjualan_detail` where isnull(`accounting`.`pengiriman_penjualan_detail`.`deleted_by`) group by `accounting`.`pengiriman_penjualan_detail`.`pesanan_penjualan_detail_id`) `pengiriman_penjualan_detail` on((`pengiriman_penjualan_detail`.`pesanan_penjualan_detail_id` = `accounting`.`pesanan_penjualan_detail`.`id`))) left join (select sum(`accounting`.`faktur_penjualan_detail`.`kuantitas`) AS `kuantitas`,`accounting`.`faktur_penjualan_detail`.`pesanan_penjualan_detail_id` AS `pesanan_penjualan_detail_id` from `accounting`.`faktur_penjualan_detail` where isnull(`accounting`.`faktur_penjualan_detail`.`deleted_by`) group by `accounting`.`faktur_penjualan_detail`.`pesanan_penjualan_detail_id`) `faktur_penjualan_detail` on((`faktur_penjualan_detail`.`pesanan_penjualan_detail_id` = `accounting`.`pesanan_penjualan_detail`.`id`))) left join (select `accounting`.`transaksi_syarat_pengiriman`.`id` AS `id`,`accounting`.`transaksi_syarat_pengiriman`.`pesanan_penjualan_detail_id` AS `pesanan_penjualan_detail_id` from `accounting`.`transaksi_syarat_pengiriman`) `transaksi_syarat_pengiriman` on((`accounting`.`pesanan_penjualan_detail`.`id` = `transaksi_syarat_pengiriman`.`pesanan_penjualan_detail_id`))) where isnull(`accounting`.`pesanan_penjualan_detail`.`deleted_by`) group by `accounting`.`pesanan_penjualan_detail`.`id` having ((ifnull(sum(`pengiriman_penjualan_detail`.`kuantitas`),0) + ifnull(sum(`faktur_penjualan_detail`.`kuantitas`),0)) < `accounting`.`pesanan_penjualan_detail`.`kuantitas`) 

 ##view_uang_muka_pembelian_belum_selesai
  DROP TABLE IF EXISTS view_uang_muka_pembelian_belum_selesai;
DROP VIEW IF EXISTS view_uang_muka_pembelian_belum_selesai;
CREATE VIEW view_uang_muka_pembelian_belum_selesai as
select `accounting`.`faktur_pembelian_header`.`id` AS `faktur_pembelian_header_id`,`accounting`.`faktur_pembelian_header`.`id` AS `faktur_header_id`,`accounting`.`faktur_pembelian_header`.`nomor` AS `nomor`,`accounting`.`faktur_pembelian_header`.`tanggal` AS `tanggal`,`accounting`.`faktur_pembelian_header`.`pemasok_id` AS `pemasok_id`,`faktur_pembelian_detail`.`harga` AS `uang_muka`,`faktur_pembelian_detail`.`pesanan_pembelian_header_id` AS `pesanan_pembelian_header_id`,ifnull(sum(`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`),0) AS `uang_muka_terpakai`,(`faktur_pembelian_detail`.`harga` - ifnull(sum(`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`),0)) AS `sisa_uang_muka` from ((`accounting`.`faktur_pembelian_header` left join (select `accounting`.`faktur_pembelian_detail`.`faktur_pembelian_header_id` AS `faktur_pembelian_header_id`,`accounting`.`faktur_pembelian_detail`.`pesanan_pembelian_header_id` AS `pesanan_pembelian_header_id`,ifnull(sum(`accounting`.`faktur_pembelian_detail`.`harga`),0) AS `harga` from `accounting`.`faktur_pembelian_detail` where isnull(`accounting`.`faktur_pembelian_detail`.`deleted_by`) group by `accounting`.`faktur_pembelian_detail`.`faktur_pembelian_header_id`) `faktur_pembelian_detail` on((`accounting`.`faktur_pembelian_header`.`id` = `faktur_pembelian_detail`.`faktur_pembelian_header_id`))) left join (select `accounting`.`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_uang_muka_header_id` AS `faktur_pembelian_uang_muka_header_id`,sum(`accounting`.`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`) AS `uang_muka_terpakai` from `accounting`.`faktur_pembelian_uang_muka_detail` where isnull(`accounting`.`faktur_pembelian_uang_muka_detail`.`deleted_by`) group by `accounting`.`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_uang_muka_header_id`) `faktur_pembelian_uang_muka_detail` on((`faktur_pembelian_uang_muka_detail`.`faktur_pembelian_uang_muka_header_id` = `accounting`.`faktur_pembelian_header`.`id`))) where (`accounting`.`faktur_pembelian_header`.`is_uang_muka` = 1) group by `accounting`.`faktur_pembelian_header`.`id` having (`faktur_pembelian_detail`.`harga` >= ifnull(sum(`faktur_pembelian_uang_muka_detail`.`uang_muka_terpakai`),0)); 

 ## view_uang_muka_penjualan_belum_selesai
 DROP TABLE IF EXISTS view_uang_muka_penjualan_belum_selesai;
DROP VIEW IF EXISTS view_uang_muka_penjualan_belum_selesai;
CREATE VIEW view_uang_muka_penjualan_belum_selesai as
 select `accounting`.`faktur_penjualan_header`.`id` AS `faktur_penjualan_header_id`,`accounting`.`faktur_penjualan_header`.`id` AS `faktur_header_id`,`accounting`.`faktur_penjualan_header`.`nomor` AS `nomor`,`accounting`.`faktur_penjualan_header`.`tanggal` AS `tanggal`,`accounting`.`faktur_penjualan_header`.`pelanggan_id` AS `pelanggan_id`,`faktur_penjualan_detail`.`pesanan_penjualan_header_id` AS `pesanan_penjualan_header_id`,`faktur_penjualan_detail`.`harga` AS `uang_muka`,ifnull(sum(`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`),0) AS `uang_muka_terpakai`,(`faktur_penjualan_detail`.`harga` - ifnull(sum(`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`),0)) AS `sisa_uang_muka` from ((`accounting`.`faktur_penjualan_header` left join (select `accounting`.`faktur_penjualan_detail`.`faktur_penjualan_header_id` AS `faktur_penjualan_header_id`,ifnull(sum(`accounting`.`faktur_penjualan_detail`.`harga`),0) AS `harga`,`accounting`.`faktur_penjualan_detail`.`pesanan_penjualan_header_id` AS `pesanan_penjualan_header_id` from `accounting`.`faktur_penjualan_detail` where isnull(`accounting`.`faktur_penjualan_detail`.`deleted_by`) group by `accounting`.`faktur_penjualan_detail`.`faktur_penjualan_header_id`) `faktur_penjualan_detail` on((`accounting`.`faktur_penjualan_header`.`id` = `faktur_penjualan_detail`.`faktur_penjualan_header_id`))) left join (select `accounting`.`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_uang_muka_header_id` AS `faktur_penjualan_uang_muka_header_id`,sum(`accounting`.`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`) AS `uang_muka_terpakai` from `accounting`.`faktur_penjualan_uang_muka_detail` where isnull(`accounting`.`faktur_penjualan_uang_muka_detail`.`deleted_by`) group by `accounting`.`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_uang_muka_header_id`) `faktur_penjualan_uang_muka_detail` on((`faktur_penjualan_uang_muka_detail`.`faktur_penjualan_uang_muka_header_id` = `accounting`.`faktur_penjualan_header`.`id`))) where (`accounting`.`faktur_penjualan_header`.`is_uang_muka` = 1) group by `accounting`.`faktur_penjualan_header`.`id` having (`faktur_penjualan_detail`.`harga` >= ifnull(sum(`faktur_penjualan_uang_muka_detail`.`uang_muka_terpakai`),0)); 

##v_saldo_akun
DROP TABLE IF EXISTS v_saldo_akun;
DROP VIEW IF EXISTS v_saldo_akun;
CREATE VIEW v_saldo_akun as
select `A`.`id` AS `id`,`A`.`no_akun` AS `no_akun`,`A`.`nama` AS `nama`,`A`.`tipe_akun_id` AS `tipe_akun`,`A`.`parent_id` AS `parent_id`,`A`.`is_parent` AS `is_parent`,(select `acc_tipe_akun`.`tipe` from `acc_tipe_akun` where (`acc_tipe_akun`.`id` = `A`.`tipe_akun_id`)) AS `tipe_tipe_akun`,(select `acc_tipe_akun`.`nama` from `acc_tipe_akun` where (`acc_tipe_akun`.`id` = `A`.`tipe_akun_id`)) AS `nama_tipe_akun`,ifnull((select `cekSaldo`(`A`.`id`)),0) AS `saldo` from `acc_akun` `A` where isnull(`A`.`deleted_at`) order by `A`.`id`,`A`.`parent_id`; 

