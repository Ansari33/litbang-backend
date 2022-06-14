-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.19-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table accounting.acc_aktiva
DROP TABLE IF EXISTS `acc_aktiva`;
CREATE TABLE IF NOT EXISTS `acc_aktiva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` date NOT NULL,
  `nama` varchar(50) NOT NULL,
  `tarif` decimal(9,2) DEFAULT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `bagi` int(11) NOT NULL,
  `susut` decimal(15,2) NOT NULL,
  `tgl_akhir` date NOT NULL,
  `rekdebet` varchar(9) NOT NULL,
  `rekkredit` varchar(9) NOT NULL,
  `divisi` varchar(20) NOT NULL,
  `rek_d_bbsusut` varchar(9) NOT NULL,
  `rek_k_akmsusut` varchar(9) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rekdebet` (`rekdebet`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_aktiva_details
DROP TABLE IF EXISTS `acc_aktiva_details`;
CREATE TABLE IF NOT EXISTS `acc_aktiva_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posted` tinyint(4) NOT NULL,
  `akunbeban` varchar(50) NOT NULL DEFAULT '',
  `aktiva_id` int(11) NOT NULL,
  `mano_post` date NOT NULL,
  `nilai` decimal(9,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aktiva_id` (`aktiva_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_akun
DROP TABLE IF EXISTS `acc_akun`;
CREATE TABLE IF NOT EXISTS `acc_akun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) DEFAULT NULL,
  `no_akun` varchar(20) DEFAULT NULL,
  `tipe_akun_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_parent` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_acc_akun_acc_tipe_akun` (`tipe_akun_id`),
  CONSTRAINT `FK_acc_akun_acc_tipe_akun` FOREIGN KEY (`tipe_akun_id`) REFERENCES `acc_tipe_akun` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_groups
DROP TABLE IF EXISTS `acc_groups`;
CREATE TABLE IF NOT EXISTS `acc_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `definition` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_jurnal_detail
DROP TABLE IF EXISTS `acc_jurnal_detail`;
CREATE TABLE IF NOT EXISTS `acc_jurnal_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header_id` int(11) NOT NULL DEFAULT 0,
  `kode_akun` int(11) DEFAULT NULL,
  `debet` double DEFAULT NULL,
  `kredit` double DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `sub_ledger_pemasok` int(11) DEFAULT NULL,
  `sub_ledger_pelanggan` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_acc_jurnal_detail_acc_jurnal_header` (`header_id`),
  KEY `FK_acc_jurnal_detail_acc_akun` (`kode_akun`),
  CONSTRAINT `FK_acc_jurnal_detail_acc_akun` FOREIGN KEY (`kode_akun`) REFERENCES `acc_akun` (`id`),
  CONSTRAINT `FK_acc_jurnal_detail_acc_jurnal_header` FOREIGN KEY (`header_id`) REFERENCES `acc_jurnal_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11013 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_jurnal_header
DROP TABLE IF EXISTS `acc_jurnal_header`;
CREATE TABLE IF NOT EXISTS `acc_jurnal_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_jurnal` varchar(200) DEFAULT NULL,
  `tanggal_jurnal` datetime DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `is_manual` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4664 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_menu
DROP TABLE IF EXISTS `acc_menu`;
CREATE TABLE IF NOT EXISTS `acc_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(200) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `name_id` varchar(200) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_tipe_akun
DROP TABLE IF EXISTS `acc_tipe_akun`;
CREATE TABLE IF NOT EXISTS `acc_tipe_akun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.acc_users
DROP TABLE IF EXISTS `acc_users`;
CREATE TABLE IF NOT EXISTS `acc_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) DEFAULT '',
  `email` varchar(100) NOT NULL,
  `oauth_uid` text DEFAULT NULL,
  `oauth_provider` varchar(100) DEFAULT NULL,
  `password` varchar(64) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `avatar` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `forgot_exp` text DEFAULT NULL,
  `remember_time` datetime DEFAULT NULL,
  `remember_exp` text DEFAULT NULL,
  `verification_code` text DEFAULT NULL,
  `top_secret` varchar(16) DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.activity_log
DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_log_log_name_index` (`log_name`),
  KEY `subject` (`subject_id`,`subject_type`),
  KEY `causer` (`causer_id`,`causer_type`)
) ENGINE=InnoDB AUTO_INCREMENT=88423 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.akun_menu_list
DROP TABLE IF EXISTS `akun_menu_list`;
CREATE TABLE IF NOT EXISTS `akun_menu_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `menu_id` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_idx` (`user_id`),
  KEY `menu_idx` (`menu_id`),
  CONSTRAINT `menu` FOREIGN KEY (`menu_id`) REFERENCES `acc_menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `acc_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.alat_transportasi
DROP TABLE IF EXISTS `alat_transportasi`;
CREATE TABLE IF NOT EXISTS `alat_transportasi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `tipe_pengiriman_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `alat_transportasi_tipe_pengiriman_id_foreign` (`tipe_pengiriman_id`),
  CONSTRAINT `alat_transportasi_tipe_pengiriman_id_foreign` FOREIGN KEY (`tipe_pengiriman_id`) REFERENCES `tipe_pengiriman` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.

-- Dumping structure for table accounting.btx_produk
DROP TABLE IF EXISTS `btx_produk`;
CREATE TABLE IF NOT EXISTS `btx_produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `kode` (`kode`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.

-- Dumping structure for table accounting.daftar_fungsi
DROP TABLE IF EXISTS `daftar_fungsi`;
CREATE TABLE IF NOT EXISTS `daftar_fungsi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `route` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.daftar_route_user
DROP TABLE IF EXISTS `daftar_route_user`;
CREATE TABLE IF NOT EXISTS `daftar_route_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `route_id` int(11) DEFAULT NULL,
  `nama_route` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_idx` (`user_id`),
  KEY `FK_daftar_route_user_daftar_fungsi` (`route_id`),
  CONSTRAINT `FK_daftar_route_user_acc_users` FOREIGN KEY (`user_id`) REFERENCES `acc_users` (`id`),
  CONSTRAINT `FK_daftar_route_user_daftar_fungsi` FOREIGN KEY (`route_id`) REFERENCES `daftar_fungsi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13061 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.departemen
DROP TABLE IF EXISTS `departemen`;
CREATE TABLE IF NOT EXISTS `departemen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.

-- Dumping structure for table accounting.dokumen
DROP TABLE IF EXISTS `dokumen`;
CREATE TABLE IF NOT EXISTS `dokumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.

-- Dumping structure for table accounting.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_pembelian_beban
DROP TABLE IF EXISTS `faktur_pembelian_beban`;
CREATE TABLE IF NOT EXISTS `faktur_pembelian_beban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faktur_pembelian_header_id` int(11) DEFAULT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_pembelian_detail
DROP TABLE IF EXISTS `faktur_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `faktur_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pesanan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_header_id` int(11) DEFAULT NULL,
  `pesanan_pembelian_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_faktur_pembelian_detail_permintaan_pembelian_detail` (`permintaan_pembelian_detail_id`),
  KEY `FK_faktur_pembelian_detail_pesanan_pembelian_detail` (`pesanan_pembelian_detail_id`),
  KEY `FK_faktur_pembelian_detail_faktur_pembelian_header` (`faktur_pembelian_header_id`),
  KEY `FK_faktur_pembelian_detail_kode_pajak` (`kode_pajak_id`),
  KEY `FK_faktur_pembelian_detail_harga_jasa` (`harga_jasa_id`),
  KEY `FK_faktur_pembelian_detail_pengiriman_pembelian_detail` (`pengiriman_pembelian_detail_id`),
  KEY `FK_faktur_pembelian_detail_pesanan_pembelian_header` (`pesanan_pembelian_header_id`),
  CONSTRAINT `FK_faktur_pembelian_detail_faktur_pembelian_header` FOREIGN KEY (`faktur_pembelian_header_id`) REFERENCES `faktur_pembelian_header` (`id`),
  CONSTRAINT `FK_faktur_pembelian_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_faktur_pembelian_detail_kode_pajak` FOREIGN KEY (`kode_pajak_id`) REFERENCES `kode_pajak` (`id`),
  CONSTRAINT `FK_faktur_pembelian_detail_pengiriman_pembelian_detail` FOREIGN KEY (`pengiriman_pembelian_detail_id`) REFERENCES `pengiriman_pembelian_detail` (`id`),
  CONSTRAINT `FK_faktur_pembelian_detail_permintaan_pembelian_detail` FOREIGN KEY (`permintaan_pembelian_detail_id`) REFERENCES `permintaan_pembelian_detail` (`id`),
  CONSTRAINT `FK_faktur_pembelian_detail_pesanan_pembelian_detail` FOREIGN KEY (`pesanan_pembelian_detail_id`) REFERENCES `pesanan_pembelian_detail` (`id`),
  CONSTRAINT `FK_faktur_pembelian_detail_pesanan_pembelian_header` FOREIGN KEY (`pesanan_pembelian_header_id`) REFERENCES `pesanan_pembelian_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_pembelian_header
DROP TABLE IF EXISTS `faktur_pembelian_header`;
CREATE TABLE IF NOT EXISTS `faktur_pembelian_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(45) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pemasok_id` int(10) unsigned DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `kena_pajak` tinyint(4) DEFAULT NULL,
  `termasuk_pajak` tinyint(4) DEFAULT NULL,
  `is_uang_muka` tinyint(4) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `total_harga` bigint(20) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_faktur_pembelian_header_pemasok` (`pemasok_id`),
  KEY `FK_faktur_pembelian_header_syarat_pengiriman` (`jadwal_pengiriman_id`),
  CONSTRAINT `FK_faktur_pembelian_header_jadwal_pengiriman` FOREIGN KEY (`jadwal_pengiriman_id`) REFERENCES `jadwal_pengiriman` (`id`),
  CONSTRAINT `FK_faktur_pembelian_header_pemasok` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasok` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_pembelian_uang_muka_detail
DROP TABLE IF EXISTS `faktur_pembelian_uang_muka_detail`;
CREATE TABLE IF NOT EXISTS `faktur_pembelian_uang_muka_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faktur_pembelian_header_id` int(11) DEFAULT NULL,
  `faktur_pembelian_uang_muka_header_id` int(11) DEFAULT NULL,
  `uang_muka_terpakai` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_faktur_pembelian_uang_muka_faktur_pembelian_header` (`faktur_pembelian_header_id`),
  KEY `FK_faktur_pembelian_uang_muka_detail_faktur_pembelian_header` (`faktur_pembelian_uang_muka_header_id`),
  CONSTRAINT `FK_faktur_pembelian_uang_muka_detail_faktur_pembelian_header` FOREIGN KEY (`faktur_pembelian_uang_muka_header_id`) REFERENCES `faktur_pembelian_header` (`id`),
  CONSTRAINT `FK_faktur_pembelian_uang_muka_faktur_pembelian_header` FOREIGN KEY (`faktur_pembelian_header_id`) REFERENCES `faktur_pembelian_header` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_penjualan_beban
DROP TABLE IF EXISTS `faktur_penjualan_beban`;
CREATE TABLE IF NOT EXISTS `faktur_penjualan_beban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faktur_penjualan_header_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `akun_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_penjualan_detail
DROP TABLE IF EXISTS `faktur_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `faktur_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penawaran_penjualan_detail_id` int(11) DEFAULT NULL,
  `pesanan_penjualan_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `pesanan_penjualan_header_id` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_faktur_penjualan_detail_penawaran_penjualan_detail` (`penawaran_penjualan_detail_id`),
  KEY `FK_faktur_penjualan_detail_faktur_penjualan_header` (`faktur_penjualan_header_id`),
  KEY `FK_faktur_penjualan_detail_kode_pajak` (`kode_pajak_id`),
  KEY `FK_faktur_penjualan_detail_pesanan_penjualan_detail` (`pesanan_penjualan_detail_id`),
  KEY `FK_faktur_penjualan_detail_pengiriman_penjualan_detail` (`pengiriman_penjualan_detail_id`),
  KEY `FK_faktur_penjualan_detail_harga_jasa` (`harga_jasa_id`),
  KEY `FK_faktur_penjualan_detail_pesanan_penjualan_header` (`pesanan_penjualan_header_id`),
  CONSTRAINT `FK_faktur_penjualan_detail_faktur_penjualan_header` FOREIGN KEY (`faktur_penjualan_header_id`) REFERENCES `faktur_penjualan_header` (`id`),
  CONSTRAINT `FK_faktur_penjualan_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_faktur_penjualan_detail_kode_pajak` FOREIGN KEY (`kode_pajak_id`) REFERENCES `kode_pajak` (`id`),
  CONSTRAINT `FK_faktur_penjualan_detail_penawaran_penjualan_detail` FOREIGN KEY (`penawaran_penjualan_detail_id`) REFERENCES `penawaran_penjualan_detail` (`id`),
  CONSTRAINT `FK_faktur_penjualan_detail_pengiriman_penjualan_detail` FOREIGN KEY (`pengiriman_penjualan_detail_id`) REFERENCES `pengiriman_penjualan_detail` (`id`),
  CONSTRAINT `FK_faktur_penjualan_detail_pesanan_penjualan_header` FOREIGN KEY (`pesanan_penjualan_header_id`) REFERENCES `pesanan_penjualan_header` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_penjualan_header
DROP TABLE IF EXISTS `faktur_penjualan_header`;
CREATE TABLE IF NOT EXISTS `faktur_penjualan_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `kena_pajak` int(11) DEFAULT NULL,
  `termasuk_pajak` int(11) DEFAULT NULL,
  `is_uang_muka` tinyint(4) DEFAULT NULL,
  `total_harga` bigint(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_faktur_penjualan_header_pelanggan` (`pelanggan_id`),
  KEY `FK_faktur_penjualan_header_jadwal_pengiriman` (`jadwal_pengiriman_id`),
  CONSTRAINT `FK_faktur_penjualan_header_jadwal_pengiriman` FOREIGN KEY (`jadwal_pengiriman_id`) REFERENCES `jadwal_pengiriman` (`id`),
  CONSTRAINT `FK_faktur_penjualan_header_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.faktur_penjualan_uang_muka_detail
DROP TABLE IF EXISTS `faktur_penjualan_uang_muka_detail`;
CREATE TABLE IF NOT EXISTS `faktur_penjualan_uang_muka_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faktur_penjualan_header_id` int(11) DEFAULT NULL,
  `faktur_penjualan_uang_muka_header_id` int(11) DEFAULT NULL,
  `uang_muka_terpakai` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_faktur_penjualan_uang_muka_faktur_penjualan_header` (`faktur_penjualan_header_id`),
  CONSTRAINT `FK_faktur_penjualan_uang_muka_faktur_penjualan_header` FOREIGN KEY (`faktur_penjualan_header_id`) REFERENCES `faktur_penjualan_header` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.group_jenis_barang
DROP TABLE IF EXISTS `group_jenis_barang`;
CREATE TABLE IF NOT EXISTS `group_jenis_barang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(200) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.gudang
DROP TABLE IF EXISTS `gudang`;
CREATE TABLE IF NOT EXISTS `gudang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) DEFAULT NULL,
  `keterangan` varchar(45) DEFAULT NULL,
  `alamat` varchar(45) DEFAULT NULL,
  `penanggung_jawab` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.harga_average
DROP TABLE IF EXISTS `harga_average`;
CREATE TABLE IF NOT EXISTS `harga_average` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` datetime DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `kuantitas_akhir` int(11) DEFAULT NULL,
  `harga_average` double DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `total_akhir` double DEFAULT NULL,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.harga_jasa
DROP TABLE IF EXISTS `harga_jasa`;
CREATE TABLE IF NOT EXISTS `harga_jasa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `produk_servis_id` int(10) unsigned DEFAULT NULL,
  `paket_id` int(10) unsigned DEFAULT NULL,
  `asal_id` int(10) unsigned DEFAULT NULL,
  `tujuan_id` int(10) unsigned DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `is_default` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `tipe` int(11) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `akun_persediaan` int(11) DEFAULT NULL,
  `akun_penjualan` int(11) DEFAULT NULL,
  `akun_retribusi_penjualan` int(11) DEFAULT NULL,
  `akun_diskon_penjualan` int(11) DEFAULT NULL,
  `akun_barang_terkirim` int(11) DEFAULT NULL,
  `akun_hpp` int(11) DEFAULT NULL,
  `akun_retribusi_pembelian` int(11) DEFAULT NULL,
  `akun_beban` int(11) DEFAULT NULL,
  `akun_belum_tertagih` int(11) DEFAULT NULL,
  `akun_hutang` int(11) DEFAULT NULL,
  `akun_piutang` int(11) DEFAULT NULL,
  `akun_uang_muka_pembelian` int(11) DEFAULT NULL,
  `akun_uang_muka_penjualan` int(11) DEFAULT NULL,
  `kolom_tambahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`kolom_tambahan`)),
  `kolom_tambahan_value` int(11) DEFAULT NULL,
  `menggunakan_nomor_seri` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `gambar` text DEFAULT NULL,
  `dimensi_lebar` int(11) DEFAULT NULL,
  `dimensi_panjang` int(11) DEFAULT NULL,
  `dimensi_tinggi` int(11) DEFAULT NULL,
  `berat` int(11) DEFAULT NULL,
  `lama_pengiriman` int(11) DEFAULT NULL,
  `saldo_awal_kuantitas` int(11) DEFAULT NULL,
  `saldo_awal_satuan` varchar(45) DEFAULT NULL,
  `saldo_awal_harga_satuan` bigint(20) DEFAULT NULL,
  `saldo_awal_gudang_id` int(11) DEFAULT NULL,
  `keaktifan` tinyint(4) DEFAULT NULL,
  `komisi_id` int(11) DEFAULT NULL,
  `wajib_nomor_seri_di_transaksi` tinyint(4) DEFAULT NULL,
  `dapat_menggunakan_nomor_seri_tanpa_stok` tinyint(4) DEFAULT NULL,
  `tipe_nomor_seri` varchar(45) DEFAULT NULL,
  `nomor_seri_menggunakan_tanggal_kadaluarsa` tinyint(4) DEFAULT NULL,
  `kode_pajak_pembelian_id` int(11) DEFAULT NULL,
  `kode_pajak_penjualan_id` int(11) DEFAULT NULL,
  `saldo_awal_tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_harga_jasa_produk_layanan` (`produk_servis_id`),
  KEY `FK_harga_jasa_paket` (`paket_id`),
  KEY `FK_harga_jasa_lokasi` (`asal_id`),
  KEY `FK_harga_jasa_lokasi_2` (`tujuan_id`),
  KEY `FK_harga_jasa_kode_pajak` (`kode_pajak_id`),
  CONSTRAINT `FK_harga_jasa_kode_pajak` FOREIGN KEY (`kode_pajak_id`) REFERENCES `kode_pajak` (`id`),
  CONSTRAINT `FK_harga_jasa_lokasi` FOREIGN KEY (`asal_id`) REFERENCES `lokasi` (`id`),
  CONSTRAINT `FK_harga_jasa_lokasi_2` FOREIGN KEY (`tujuan_id`) REFERENCES `lokasi` (`id`),
  CONSTRAINT `FK_harga_jasa_paket` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`),
  CONSTRAINT `FK_harga_jasa_produk_layanan` FOREIGN KEY (`produk_servis_id`) REFERENCES `produk_layanan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.harga_jasa_informasi_pembelian
DROP TABLE IF EXISTS `harga_jasa_informasi_pembelian`;
CREATE TABLE IF NOT EXISTS `harga_jasa_informasi_pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `pemasok_id` int(10) unsigned DEFAULT NULL,
  `harga_pembelian` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_harga_jasa_informasi_pembelian_harga_jasa` (`harga_jasa_id`),
  KEY `FK_harga_jasa_informasi_pembelian_pemasok` (`pemasok_id`),
  KEY `FK_harga_jasa_informasi_pembelian_harga_pembelian_pengiriman` (`harga_pembelian`) USING BTREE,
  CONSTRAINT `FK_harga_jasa_informasi_pembelian_pemasok` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasok` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.harga_jasa_informasi_penjualan
DROP TABLE IF EXISTS `harga_jasa_informasi_penjualan`;
CREATE TABLE IF NOT EXISTS `harga_jasa_informasi_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `opsi` tinyint(4) DEFAULT NULL,
  `harga_penjualan` bigint(20) DEFAULT NULL,
  `jenis_barang_id` int(11) DEFAULT NULL,
  `tipe_pengiriman_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_harga_jasa_informasi_penjualan_pelanggan` (`pelanggan_id`),
  KEY `FK_harga_jasa_informasi_penjualan_harga` (`harga_penjualan`),
  KEY `FK_harga_jasa_informasi_penjualan_tipe_pengiriman` (`tipe_pengiriman_id`),
  KEY `FK_harga_jasa_informasi_penjualan_jenis_barang` (`jenis_barang_id`),
  KEY `FK_harga_jasa_informasi_penjualan_harga_jasa` (`harga_jasa_id`),
  CONSTRAINT `FK_harga_jasa_informasi_penjualan_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_harga_jasa_informasi_penjualan_jenis_barang` FOREIGN KEY (`jenis_barang_id`) REFERENCES `jenis_barang` (`id`),
  CONSTRAINT `FK_harga_jasa_informasi_penjualan_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`),
  CONSTRAINT `FK_harga_jasa_informasi_penjualan_tipe_pengiriman` FOREIGN KEY (`tipe_pengiriman_id`) REFERENCES `tipe_pengiriman` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=734 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.harga_jasa_informasi_penjualan_detail
DROP TABLE IF EXISTS `harga_jasa_informasi_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `harga_jasa_informasi_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `harga_jasa_informasi_penjualan_id` int(11) DEFAULT NULL,
  `harga_jasa_informasi_pembelian_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_harga_jasa_informasi_pembelian` (`harga_jasa_informasi_pembelian_id`),
  KEY `_penjualan` (`harga_jasa_informasi_penjualan_id`),
  CONSTRAINT `FK_harga_jasa_informasi_pembelian` FOREIGN KEY (`harga_jasa_informasi_pembelian_id`) REFERENCES `harga_jasa_informasi_pembelian` (`id`),
  CONSTRAINT `_penjualan` FOREIGN KEY (`harga_jasa_informasi_penjualan_id`) REFERENCES `harga_jasa_informasi_penjualan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1011 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.jadwal_pengiriman
DROP TABLE IF EXISTS `jadwal_pengiriman`;
CREATE TABLE IF NOT EXISTS `jadwal_pengiriman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(20) DEFAULT NULL,
  `alat_transportasi` int(10) unsigned DEFAULT NULL COMMENT 'FK',
  `kapal` varchar(50) DEFAULT NULL,
  `asal_id` int(10) unsigned DEFAULT NULL,
  `tujuan_id` int(10) unsigned DEFAULT NULL,
  `tanggal_pembukaan` date DEFAULT NULL,
  `tanggal_penutupan` date DEFAULT NULL,
  `eta_asal` date DEFAULT NULL,
  `etd_asal` date DEFAULT NULL,
  `eta_tujuan` date DEFAULT NULL,
  `voyage_flight_trip` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_pengiriman_alat_angkutan_Id_foreign` (`alat_transportasi`),
  CONSTRAINT `jadwal_pengiriman_alat_angkutan_Id_foreign` FOREIGN KEY (`alat_transportasi`) REFERENCES `alat_transportasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.jenis_barang
DROP TABLE IF EXISTS `jenis_barang`;
CREATE TABLE IF NOT EXISTS `jenis_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `pengelompokan_jenis_barang` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jenis_barang_group_jenis_barang_id` (`pengelompokan_jenis_barang`),
  CONSTRAINT `jenis_barang_group_jenis_barang_id` FOREIGN KEY (`pengelompokan_jenis_barang`) REFERENCES `group_jenis_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.kategori
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.ketentuan_pembayaran
DROP TABLE IF EXISTS `ketentuan_pembayaran`;
CREATE TABLE IF NOT EXISTS `ketentuan_pembayaran` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.

-- Dumping structure for table accounting.kode_pajak
DROP TABLE IF EXISTS `kode_pajak`;
CREATE TABLE IF NOT EXISTS `kode_pajak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) DEFAULT NULL,
  `nilai` varchar(50) DEFAULT NULL,
  `kode` varchar(10) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `akun_pajak_pembelian_id` int(11) DEFAULT NULL,
  `akun_pajak_penjualan_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_kode_pajak_acc_akun` (`akun_pajak_pembelian_id`),
  KEY `FK_kode_pajak_acc_akun_2` (`akun_pajak_penjualan_id`),
  CONSTRAINT `FK_kode_pajak_acc_akun` FOREIGN KEY (`akun_pajak_pembelian_id`) REFERENCES `acc_akun` (`id`),
  CONSTRAINT `FK_kode_pajak_acc_akun_2` FOREIGN KEY (`akun_pajak_penjualan_id`) REFERENCES `acc_akun` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.lokasi
DROP TABLE IF EXISTS `lokasi`;
CREATE TABLE IF NOT EXISTS `lokasi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kodepos` varchar(5) NOT NULL DEFAULT '',
  `kelurahan` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kabupaten` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ixkodepos` (`kodepos`)
) ENGINE=InnoDB AUTO_INCREMENT=81263 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.lokasi_kantor
DROP TABLE IF EXISTS `lokasi_kantor`;
CREATE TABLE IF NOT EXISTS `lokasi_kantor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.

-- Dumping structure for table accounting.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.nomor_seri_transaksi
DROP TABLE IF EXISTS `nomor_seri_transaksi`;
CREATE TABLE IF NOT EXISTS `nomor_seri_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penerimaan_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `retur_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `retur_penjualan_detail_id` int(11) DEFAULT NULL,
  `transfer_barang_detail_id` int(11) DEFAULT NULL,
  `nomor_seri` varchar(254) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_nomor_seri_transaksi_faktur_pembelian_detail` (`faktur_pembelian_detail_id`),
  KEY `FK_nomor_seri_transaksi_pengiriman_pembelian_detail` (`penerimaan_pembelian_detail_id`),
  KEY `FK_nomor_seri_transaksi_pengiriman_penjualan_detail` (`pengiriman_penjualan_detail_id`),
  CONSTRAINT `FK_nomor_seri_transaksi_faktur_pembelian_detail` FOREIGN KEY (`faktur_pembelian_detail_id`) REFERENCES `faktur_pembelian_detail` (`id`),
  CONSTRAINT `FK_nomor_seri_transaksi_pengiriman_pembelian_detail` FOREIGN KEY (`penerimaan_pembelian_detail_id`) REFERENCES `pengiriman_pembelian_detail` (`id`),
  CONSTRAINT `FK_nomor_seri_transaksi_pengiriman_penjualan_detail` FOREIGN KEY (`pengiriman_penjualan_detail_id`) REFERENCES `pengiriman_penjualan_detail` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.oauth_access_tokens
DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table accounting.oauth_auth_codes
DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table accounting.oauth_clients
DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table accounting.oauth_personal_access_clients
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table accounting.oauth_refresh_tokens
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table accounting.paket
DROP TABLE IF EXISTS `paket`;
CREATE TABLE IF NOT EXISTS `paket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL DEFAULT '0',
  `keterangan` varchar(200) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pelanggan
DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(8) DEFAULT NULL COMMENT 'must 8 digit',
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(15) DEFAULT NULL COMMENT 'number only',
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `ktp` varchar(50) DEFAULT NULL,
  `pic` varchar(20) DEFAULT NULL,
  `ketentuan_pembayaran_id` int(10) unsigned DEFAULT NULL COMMENT 'Select Combo Box',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pelanggan_ketentuan_pembayaran_id_foreign` (`ketentuan_pembayaran_id`),
  CONSTRAINT `pelanggan_ketentuan_pembayaran_id_foreign` FOREIGN KEY (`ketentuan_pembayaran_id`) REFERENCES `ketentuan_pembayaran` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pemasok
DROP TABLE IF EXISTS `pemasok`;
CREATE TABLE IF NOT EXISTS `pemasok` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(8) DEFAULT NULL COMMENT 'harus 8 digit',
  `nama` varchar(150) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ktp` varchar(20) DEFAULT NULL COMMENT 'hanya angka',
  `npwp` varchar(20) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `bank_rekening_01` varchar(20) DEFAULT NULL,
  `nomor_rekening_01` varchar(20) DEFAULT NULL,
  `bank_rekening_02` varchar(20) DEFAULT NULL,
  `nomor_rekening_02` varchar(20) DEFAULT NULL,
  `ketentuan_pembayaran_id` int(10) unsigned DEFAULT NULL COMMENT 'combo select',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pemasok_ketentuan_pembayaran_id_foreign` (`ketentuan_pembayaran_id`),
  CONSTRAINT `pemasok_ketentuan_pembayaran_id_foreign` FOREIGN KEY (`ketentuan_pembayaran_id`) REFERENCES `ketentuan_pembayaran` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pembayaran_pembelian_detail
DROP TABLE IF EXISTS `pembayaran_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `pembayaran_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pembayaran_pembelian_header_id` int(11) DEFAULT NULL,
  `faktur_pembelian_header_id` int(11) DEFAULT NULL,
  `nominal_pembayaran` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pembayaran_pembelian_detail_pembayaran_pembelian_header` (`pembayaran_pembelian_header_id`),
  KEY `FK_pembayaran_pembelian_detail_faktur_penjualan_header` (`faktur_pembelian_header_id`) USING BTREE,
  CONSTRAINT `FK_pembayaran_pembelian_detail_faktur_pembelian_header` FOREIGN KEY (`faktur_pembelian_header_id`) REFERENCES `faktur_pembelian_header` (`id`),
  CONSTRAINT `FK_pembayaran_pembelian_detail_pembayaran_pembelian_header` FOREIGN KEY (`pembayaran_pembelian_header_id`) REFERENCES `pembayaran_pembelian_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pembayaran_pembelian_header
DROP TABLE IF EXISTS `pembayaran_pembelian_header`;
CREATE TABLE IF NOT EXISTS `pembayaran_pembelian_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `akun_kredit_id` int(11) DEFAULT NULL COMMENT 'fk',
  `pemasok_id` int(10) unsigned DEFAULT NULL,
  `nomor_cek` varchar(20) DEFAULT NULL,
  `tanggal_cek` date DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `nominal_cek` int(11) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `nominal_total_pembayaran` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pembayaran_pembelian_pemasok` (`pemasok_id`),
  CONSTRAINT `FK_pembayaran_pembelian_pemasok` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasok` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pembayaran_tanda_terima_pengiriman_penjualan
DROP TABLE IF EXISTS `pembayaran_tanda_terima_pengiriman_penjualan`;
CREATE TABLE IF NOT EXISTS `pembayaran_tanda_terima_pengiriman_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `akun_debet` int(11) DEFAULT NULL,
  `nomor_cek` varchar(50) DEFAULT NULL,
  `tanggal_cek` date DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `nominal_cek` bigint(20) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `faktur_penjualan_header_id` int(11) DEFAULT NULL,
  `nominal` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__header` (`faktur_penjualan_header_id`),
  KEY `FK_pembayaran_tanda_terima_pengiriman_penjualan_pelanggan` (`pelanggan_id`),
  KEY `FK_pembayaran_tanda_terima_pengiriman_penjualan_acc_akun` (`akun_debet`),
  CONSTRAINT `FK__header` FOREIGN KEY (`faktur_penjualan_header_id`) REFERENCES `faktur_penjualan_header` (`id`),
  CONSTRAINT `FK_pembayaran_tanda_terima_pengiriman_penjualan_acc_akun` FOREIGN KEY (`akun_debet`) REFERENCES `acc_akun` (`id`),
  CONSTRAINT `FK_pembayaran_tanda_terima_pengiriman_penjualan_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.penawaran_penjualan_detail
DROP TABLE IF EXISTS `penawaran_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `penawaran_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penawaran_penjualan_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_penawaran_penjualan_detail_penawaran_penjualan_header` (`penawaran_penjualan_header_id`),
  KEY `kode_pajak_idx` (`kode_pajak_id`),
  KEY `FK_penawaran_penjualan_detail_harga_jasa` (`harga_jasa_id`),
  CONSTRAINT `FK_penawaran_penjualan_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_penawaran_penjualan_detail_kode_pajak` FOREIGN KEY (`kode_pajak_id`) REFERENCES `kode_pajak` (`id`),
  CONSTRAINT `FK_penawaran_penjualan_detail_penawaran_penjualan_header` FOREIGN KEY (`penawaran_penjualan_header_id`) REFERENCES `penawaran_penjualan_header` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.penawaran_penjualan_header
DROP TABLE IF EXISTS `penawaran_penjualan_header`;
CREATE TABLE IF NOT EXISTS `penawaran_penjualan_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(45) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `kena_pajak` int(11) DEFAULT NULL,
  `termasuk_pajak` int(11) DEFAULT NULL,
  `total_harga` bigint(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pelanggan_idx` (`pelanggan_id`),
  KEY `FK_penawaran_penjualan_header_jadwal_pengiriman` (`jadwal_pengiriman_id`),
  CONSTRAINT `FK_penawaran_penjualan_header_jadwal_pengiriman` FOREIGN KEY (`jadwal_pengiriman_id`) REFERENCES `jadwal_pengiriman` (`id`),
  CONSTRAINT `FK_penawaran_penjualan_header_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.penerimaan_penjualan_detail
DROP TABLE IF EXISTS `penerimaan_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `penerimaan_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penerimaan_penjualan_header_id` int(11) DEFAULT NULL,
  `faktur_penjualan_header_id` int(11) DEFAULT NULL,
  `nominal_penjualan` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_penerimaan_penjualan_detail_penerimaan_penjualan_header` (`penerimaan_penjualan_header_id`),
  KEY `FK_penerimaan_penjualan_detail_faktur_penjualan_header` (`faktur_penjualan_header_id`),
  CONSTRAINT `FK_penerimaan_penjualan_detail_faktur_penjualan_header` FOREIGN KEY (`faktur_penjualan_header_id`) REFERENCES `faktur_penjualan_header` (`id`),
  CONSTRAINT `FK_penerimaan_penjualan_detail_penerimaan_penjualan_header` FOREIGN KEY (`penerimaan_penjualan_header_id`) REFERENCES `penerimaan_penjualan_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.penerimaan_penjualan_header
DROP TABLE IF EXISTS `penerimaan_penjualan_header`;
CREATE TABLE IF NOT EXISTS `penerimaan_penjualan_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `akun_debet` int(11) DEFAULT NULL,
  `nomor_cek` int(11) DEFAULT NULL,
  `tanggal_cek` date DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `nominal_cek` int(11) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `nominal_total_penjualan` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_penerimaan_penjualan_pelanggan` (`pelanggan_id`),
  CONSTRAINT `FK_penerimaan_penjualan_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pengiriman_pembelian_detail
DROP TABLE IF EXISTS `pengiriman_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `pengiriman_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pesanan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_pembelian_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pengiriman_pembelian_detail_pesanan_pembelian_detail` (`pesanan_pembelian_detail_id`),
  KEY `FK_pengiriman_pembelian_detail_pengiriman_pembelian_header` (`pengiriman_pembelian_header_id`),
  KEY `FK_pengiriman_pembelian_detail_harga_jasa` (`harga_jasa_id`),
  CONSTRAINT `FK_pengiriman_pembelian_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_pengiriman_pembelian_detail_pengiriman_pembelian_header` FOREIGN KEY (`pengiriman_pembelian_header_id`) REFERENCES `pengiriman_pembelian_header` (`id`),
  CONSTRAINT `FK_pengiriman_pembelian_detail_pesanan_pembelian_detail` FOREIGN KEY (`pesanan_pembelian_detail_id`) REFERENCES `pesanan_pembelian_detail` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pengiriman_pembelian_header
DROP TABLE IF EXISTS `pengiriman_pembelian_header`;
CREATE TABLE IF NOT EXISTS `pengiriman_pembelian_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pemasok_id` int(10) unsigned DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `nomor_release_order` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `details_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_data`)),
  `gudang_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pengiriman_penjualan_detail
DROP TABLE IF EXISTS `pengiriman_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `pengiriman_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penawaran_penjualan_detail_id` int(11) DEFAULT NULL,
  `pesanan_penjualan_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pengiriman_penjualan_detail_penawaran_penjualan_detail` (`penawaran_penjualan_detail_id`),
  KEY `FK_pengiriman_penjualan_detail_pesanan_penjualan_detail` (`pesanan_penjualan_detail_id`),
  KEY `FK_pengiriman_penjualan_detail_pengiriman_penjualan_header` (`pengiriman_penjualan_header_id`),
  KEY `FK_pengiriman_penjualan_detail_harga_jasa` (`harga_jasa_id`),
  CONSTRAINT `FK_pengiriman_penjualan_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_pengiriman_penjualan_detail_penawaran_penjualan_detail` FOREIGN KEY (`penawaran_penjualan_detail_id`) REFERENCES `penawaran_penjualan_detail` (`id`),
  CONSTRAINT `FK_pengiriman_penjualan_detail_pengiriman_penjualan_header` FOREIGN KEY (`pengiriman_penjualan_header_id`) REFERENCES `pengiriman_penjualan_header` (`id`),
  CONSTRAINT `FK_pengiriman_penjualan_detail_pesanan_penjualan_detail` FOREIGN KEY (`pesanan_penjualan_detail_id`) REFERENCES `pesanan_penjualan_detail` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pengiriman_penjualan_header
DROP TABLE IF EXISTS `pengiriman_penjualan_header`;
CREATE TABLE IF NOT EXISTS `pengiriman_penjualan_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `nomor_pengangkutan_barang` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pengiriman_penjualan_header_pelanggan` (`pelanggan_id`),
  CONSTRAINT `FK_pengiriman_penjualan_header_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.penomoran
DROP TABLE IF EXISTS `penomoran`;
CREATE TABLE IF NOT EXISTS `penomoran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modul` varchar(50) DEFAULT NULL,
  `prefix` text DEFAULT NULL,
  `tabel` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `upated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.permintaan_pembelian_detail
DROP TABLE IF EXISTS `permintaan_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `permintaan_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_pembelian_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_permintaan_pembelian_detail_permintaan_pembelian_header` (`permintaan_pembelian_header_id`),
  KEY `FK_permintaan_pembelian_detail_harga_jasa` (`harga_jasa_id`),
  CONSTRAINT `FK_permintaan_pembelian_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_permintaan_pembelian_detail_permintaan_pembelian_header` FOREIGN KEY (`permintaan_pembelian_header_id`) REFERENCES `permintaan_pembelian_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.permintaan_pembelian_header
DROP TABLE IF EXISTS `permintaan_pembelian_header`;
CREATE TABLE IF NOT EXISTS `permintaan_pembelian_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pemasok_id` int(10) unsigned DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `kena_pajak` int(11) DEFAULT NULL,
  `kode_pajak` int(11) DEFAULT NULL,
  `termasuk_pajak` int(11) DEFAULT NULL,
  `details_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_data`)),
  PRIMARY KEY (`id`),
  KEY `pemasok_idx` (`pemasok_id`),
  KEY `FK_permintaan_pembelian_header_jadwal_pengiriman` (`jadwal_pengiriman_id`),
  CONSTRAINT `FK_permintaan_pembelian_header_jadwal_pengiriman` FOREIGN KEY (`jadwal_pengiriman_id`) REFERENCES `jadwal_pengiriman` (`id`),
  CONSTRAINT `FK_permintaan_pembelian_header_pemasok` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasok` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pesanan_pembelian_detail
DROP TABLE IF EXISTS `pesanan_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `pesanan_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_pembelian_detail_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `pesanan_pembelian_header_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pesanan_pembelian_detail_pesanan_pembelian_header` (`pesanan_pembelian_header_id`),
  KEY `FK_pesanan_pembelian_detail_kode_pajak` (`kode_pajak_id`),
  KEY `FK_pesanan_pembelian_detail_permintaan_pembelian_detail` (`permintaan_pembelian_detail_id`),
  KEY `FK_pesanan_pembelian_detail_harga_jasa` (`harga_jasa_id`),
  CONSTRAINT `FK_pesanan_pembelian_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_pesanan_pembelian_detail_kode_pajak` FOREIGN KEY (`kode_pajak_id`) REFERENCES `kode_pajak` (`id`),
  CONSTRAINT `FK_pesanan_pembelian_detail_permintaan_pembelian_detail` FOREIGN KEY (`permintaan_pembelian_detail_id`) REFERENCES `permintaan_pembelian_detail` (`id`),
  CONSTRAINT `FK_pesanan_pembelian_detail_pesanan_pembelian_header` FOREIGN KEY (`pesanan_pembelian_header_id`) REFERENCES `pesanan_pembelian_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pesanan_pembelian_header
DROP TABLE IF EXISTS `pesanan_pembelian_header`;
CREATE TABLE IF NOT EXISTS `pesanan_pembelian_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pemasok_id` int(10) unsigned DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `kena_pajak` int(11) DEFAULT NULL,
  `termasuk_pajak` int(11) DEFAULT NULL,
  `total_harga` bigint(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `details_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details_data`)),
  PRIMARY KEY (`id`),
  KEY `FK_pesanan_pembelian_header_pemasok` (`pemasok_id`),
  KEY `FK_pesanan_pembelian_header_jadwal_pengiriman` (`jadwal_pengiriman_id`),
  CONSTRAINT `FK_pesanan_pembelian_header_jadwal_pengiriman` FOREIGN KEY (`jadwal_pengiriman_id`) REFERENCES `jadwal_pengiriman` (`id`),
  CONSTRAINT `FK_pesanan_pembelian_header_pemasok` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasok` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pesanan_penjualan_detail
DROP TABLE IF EXISTS `pesanan_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `pesanan_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `penawaran_penjualan_detail_id` int(11) DEFAULT NULL,
  `pesanan_penjualan_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pesanan_penjualan_detail_penawaran_penjualan_detail` (`penawaran_penjualan_detail_id`),
  KEY `FK_pesanan_penjualan_detail_pesanan_penjualan_header` (`pesanan_penjualan_header_id`),
  KEY `FK_pesanan_penjualan_detail_kode_pajak` (`kode_pajak_id`),
  KEY `FK_pesanan_penjualan_detail_harga_jasa` (`harga_jasa_id`),
  CONSTRAINT `FK_pesanan_penjualan_detail_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`),
  CONSTRAINT `FK_pesanan_penjualan_detail_kode_pajak` FOREIGN KEY (`kode_pajak_id`) REFERENCES `kode_pajak` (`id`),
  CONSTRAINT `FK_pesanan_penjualan_detail_penawaran_penjualan_detail` FOREIGN KEY (`penawaran_penjualan_detail_id`) REFERENCES `penawaran_penjualan_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pesanan_penjualan_detail_pesanan_penjualan_header` FOREIGN KEY (`pesanan_penjualan_header_id`) REFERENCES `pesanan_penjualan_header` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pesanan_penjualan_header
DROP TABLE IF EXISTS `pesanan_penjualan_header`;
CREATE TABLE IF NOT EXISTS `pesanan_penjualan_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `kena_pajak` int(11) DEFAULT NULL,
  `termasuk_pajak` int(11) DEFAULT NULL,
  `total_harga` bigint(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pesanan_penjualan_header_pelanggan` (`pelanggan_id`),
  KEY `FK_pesanan_penjualan_header_jadwal_pengiriman` (`jadwal_pengiriman_id`),
  CONSTRAINT `FK_pesanan_penjualan_header_jadwal_pengiriman` FOREIGN KEY (`jadwal_pengiriman_id`) REFERENCES `jadwal_pengiriman` (`id`),
  CONSTRAINT `FK_pesanan_penjualan_header_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.petunjuk_pengiriman_detail
DROP TABLE IF EXISTS `petunjuk_pengiriman_detail`;
CREATE TABLE IF NOT EXISTS `petunjuk_pengiriman_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `petunjuk_pengiriman_header_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_petunjuk_pengiriman_detail_petunjuk_pengiriman_header` (`petunjuk_pengiriman_header_id`),
  KEY `FK_petunjuk_pengiriman_detail_pengiriman_penjualan_detail` (`pengiriman_penjualan_detail_id`),
  CONSTRAINT `FK_petunjuk_pengiriman_detail_pengiriman_penjualan_detail` FOREIGN KEY (`pengiriman_penjualan_detail_id`) REFERENCES `pengiriman_penjualan_detail` (`id`),
  CONSTRAINT `FK_petunjuk_pengiriman_detail_petunjuk_pengiriman_header` FOREIGN KEY (`petunjuk_pengiriman_header_id`) REFERENCES `petunjuk_pengiriman_header` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.petunjuk_pengiriman_header
DROP TABLE IF EXISTS `petunjuk_pengiriman_header`;
CREATE TABLE IF NOT EXISTS `petunjuk_pengiriman_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `jadwal_pengiriman_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pihak_ketiga_01_id` int(11) DEFAULT NULL,
  `pihak_ketiga_02_id` int(11) DEFAULT NULL,
  `pihak_ketiga_03_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_petunjuk_pengiriman_header_jadwal_pengiriman` (`jadwal_pengiriman_id`),
  CONSTRAINT `FK_petunjuk_pengiriman_header_jadwal_pengiriman` FOREIGN KEY (`jadwal_pengiriman_id`) REFERENCES `jadwal_pengiriman` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.pihak_ketiga
DROP TABLE IF EXISTS `pihak_ketiga`;
CREATE TABLE IF NOT EXISTS `pihak_ketiga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) DEFAULT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(15) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `ktp` varchar(45) DEFAULT NULL,
  `npwp` varchar(45) DEFAULT NULL,
  `pic` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.preferensi_perusahaan
DROP TABLE IF EXISTS `preferensi_perusahaan`;
CREATE TABLE IF NOT EXISTS `preferensi_perusahaan` (
  `id` int(11) NOT NULL,
  `akun_perkiraan_barang_jasa_persediaan` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_beban_atas_pembelian_jasa` int(11) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_penjualan` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_penjualan_belum_terealisasi` int(11) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_retur_penjualan` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_diskon_penjualan` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_barang_terkirim` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_retur_pembelian` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_beban_pokok_penjualan` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_beban` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_barang_jasa_pembelian_belum_tertagih` varchar(1000) DEFAULT NULL,
  `akun_perkiraan_hutang_pembelian_belum_ditagih` int(11) DEFAULT NULL,
  `akun_perkiraan_piutang_penjualan_belum_ditagih` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_hutang` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_piutang` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_uang_muka_pembelian` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_uang_muka_penjualan` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_ekuitas_saldo_awal` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_laba_ditahan` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_pajak_penghasilan` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_hutang_pph21` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_hutang_premi_pensiun` int(11) DEFAULT NULL,
  `akun_perkiraan_perusahaan_premi_kesehatan` int(11) DEFAULT NULL,
  `akun_perkiraan_penjualan_pembelian_akun_diskon` int(11) DEFAULT NULL,
  `akun_perkiraan_penjualan_pembelian_akun_pembulatan` int(11) DEFAULT NULL,
  `akun_perkiraan_persediaan_akun_penyesuaian` int(11) DEFAULT NULL,
  `akun_perkiraan_persediaan_beban_selisih_stok` int(11) DEFAULT NULL,
  `akun_perkiraan_persediaan_akun_pekerjaan` int(11) DEFAULT NULL,
  `akun_perkiraan_persediaan_selisih_biaya` int(11) DEFAULT NULL,
  `format_desimal` tinyint(4) DEFAULT NULL,
  `format_opsi_desimal` tinyint(4) DEFAULT NULL,
  `format_opsi_desimal_view` tinyint(4) DEFAULT NULL,
  `hutang_piutang_rentang_umur` int(11) DEFAULT NULL,
  `hutang_piutang_umur_dihitung_dari` int(11) DEFAULT NULL,
  `umur_persediaan_rentang_umur` int(11) DEFAULT NULL,
  `komisi_penjual_komisi_dihitung_dari` tinyint(4) DEFAULT NULL,
  `info_perusahaan_nama` varchar(100) DEFAULT NULL,
  `info_perusahaan_level_distribusi` int(11) DEFAULT NULL,
  `info_perusahaan_bidang_usaha` varchar(100) DEFAULT NULL,
  `info_perusahaan_telepon` varchar(100) DEFAULT NULL,
  `info_perusahaan_faksimili` varchar(100) DEFAULT NULL,
  `info_perusahaan_email` varchar(100) DEFAULT NULL,
  `info_perusahaan_tanggal_mulai_data` date DEFAULT NULL,
  `info_perusahaan_periode_akuntansi` date DEFAULT NULL,
  `info_perusahaan_mata_uang` varchar(100) DEFAULT NULL,
  `info_perusahaan_alamat` varchar(100) DEFAULT NULL,
  `info_perusahaan_lokasi` varchar(100) DEFAULT NULL,
  `akun_perkiraan_pendapatan` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `akun_perkiraan_hutang_lancar` int(11) DEFAULT NULL,
  `metode_akuntansi` varchar(50) DEFAULT NULL,
  `tes` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_preferensi_perusahaan_acc_akun_10` (`akun_perkiraan_hutang_pembelian_belum_ditagih`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_27` (`akun_perkiraan_piutang_penjualan_belum_ditagih`),
  KEY `FK_preferensi_perusahaan_acc_akun_28` (`akun_perkiraan_penjualan_belum_terealisasi`),
  KEY `FK_preferensi_perusahaan_acc_akun_29` (`akun_perkiraan_pendapatan`),
  KEY `FK_preferensi_perusahaan_acc_akun` (`akun_perkiraan_barang_jasa_persediaan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_3` (`akun_perkiraan_barang_jasa_penjualan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_5` (`akun_perkiraan_barang_jasa_diskon_penjualan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_6` (`akun_perkiraan_barang_jasa_barang_terkirim`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_7` (`akun_perkiraan_barang_jasa_beban_pokok_penjualan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_8` (`akun_perkiraan_barang_jasa_retur_penjualan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_4` (`akun_perkiraan_barang_jasa_retur_pembelian`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_9` (`akun_perkiraan_barang_jasa_beban`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_11` (`akun_perkiraan_perusahaan_ekuitas_saldo_awal`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_12` (`akun_perkiraan_perusahaan_laba_ditahan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_13` (`akun_perkiraan_perusahaan_pajak_penghasilan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_14` (`akun_perkiraan_perusahaan_hutang_pph21`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_15` (`akun_perkiraan_perusahaan_hutang_premi_pensiun`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_16` (`akun_perkiraan_perusahaan_premi_kesehatan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_17` (`akun_perkiraan_penjualan_pembelian_akun_diskon`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_18` (`akun_perkiraan_persediaan_selisih_biaya`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_19` (`akun_perkiraan_persediaan_akun_pekerjaan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_20` (`akun_perkiraan_persediaan_beban_selisih_stok`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_21` (`akun_perkiraan_persediaan_akun_penyesuaian`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_22` (`akun_perkiraan_penjualan_pembelian_akun_pembulatan`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_2` (`akun_perkiraan_beban_atas_pembelian_jasa`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_23` (`akun_perkiraan_perusahaan_hutang`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_24` (`akun_perkiraan_perusahaan_piutang`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_25` (`akun_perkiraan_perusahaan_uang_muka_pembelian`) USING BTREE,
  KEY `FK_preferensi_perusahaan_acc_akun_26` (`akun_perkiraan_perusahaan_uang_muka_penjualan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.produk_layanan
DROP TABLE IF EXISTS `produk_layanan`;
CREATE TABLE IF NOT EXISTS `produk_layanan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL DEFAULT '0',
  `keterangan` varchar(220) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.retur_pembelian_detail
DROP TABLE IF EXISTS `retur_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `retur_pembelian_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retur_pembelian_header_id` int(11) DEFAULT NULL,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.retur_pembelian_header
DROP TABLE IF EXISTS `retur_pembelian_header`;
CREATE TABLE IF NOT EXISTS `retur_pembelian_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pengiriman_pembelian_header_id` int(11) DEFAULT NULL,
  `faktur_pembelian_header_id` int(11) DEFAULT NULL,
  `pemasok_id` int(11) DEFAULT NULL,
  `kena_pajak` int(11) DEFAULT NULL,
  `termasuk_pajak` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `total_harga` bigint(20) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.retur_penjualan_detail
DROP TABLE IF EXISTS `retur_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `retur_penjualan_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `retur_penjualan_header_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `kode_pajak_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.retur_penjualan_header
DROP TABLE IF EXISTS `retur_penjualan_header`;
CREATE TABLE IF NOT EXISTS `retur_penjualan_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_header_id` int(11) DEFAULT NULL,
  `faktur_penjualan_header_id` int(11) DEFAULT NULL,
  `kena_pajak` int(11) DEFAULT NULL,
  `termasuk_pajak` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.sistem_variabel
DROP TABLE IF EXISTS `sistem_variabel`;
CREATE TABLE IF NOT EXISTS `sistem_variabel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `nilai` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.stok
DROP TABLE IF EXISTS `stok`;
CREATE TABLE IF NOT EXISTS `stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` varchar(45) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `nomor_seri` varchar(45) DEFAULT NULL,
  `gudang` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_stok_harga_jasa` (`harga_jasa_id`),
  KEY `FK_stok_gudang` (`gudang`),
  CONSTRAINT `FK_stok_gudang` FOREIGN KEY (`gudang`) REFERENCES `gudang` (`id`),
  CONSTRAINT `FK_stok_harga_jasa` FOREIGN KEY (`harga_jasa_id`) REFERENCES `harga_jasa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.stok_detail
DROP TABLE IF EXISTS `stok_detail`;
CREATE TABLE IF NOT EXISTS `stok_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stok_id` int(11) DEFAULT NULL,
  `penerimaan_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_stok_detail_stok` (`stok_id`),
  KEY `FK_stok_detail_pengiriman_pembelian_detail` (`penerimaan_pembelian_detail_id`),
  KEY `FK_stok_detail_faktur_pembelian_detail` (`faktur_pembelian_detail_id`),
  KEY `FK_stok_detail_pengiriman_penjualan_detail` (`pengiriman_penjualan_detail_id`),
  KEY `FK_stok_detail_faktur_penjualan_detail` (`faktur_penjualan_detail_id`),
  CONSTRAINT `FK_stok_detail_faktur_pembelian_detail` FOREIGN KEY (`faktur_pembelian_detail_id`) REFERENCES `faktur_pembelian_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_stok_detail_faktur_penjualan_detail` FOREIGN KEY (`faktur_penjualan_detail_id`) REFERENCES `faktur_penjualan_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_stok_detail_pengiriman_pembelian_detail` FOREIGN KEY (`penerimaan_pembelian_detail_id`) REFERENCES `pengiriman_pembelian_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_stok_detail_pengiriman_penjualan_detail` FOREIGN KEY (`pengiriman_penjualan_detail_id`) REFERENCES `pengiriman_penjualan_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_stok_detail_stok` FOREIGN KEY (`stok_id`) REFERENCES `stok` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.stok_detail_fifo_average
DROP TABLE IF EXISTS `stok_detail_fifo_average`;
CREATE TABLE IF NOT EXISTS `stok_detail_fifo_average` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stok_id` int(11) DEFAULT NULL,
  `stok_asal_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_stok_detail_fifo_average_stok_fifo_average_2` (`stok_id`),
  KEY `FK_stok_detail_fifo_average_stok_fifo_average` (`stok_asal_id`),
  CONSTRAINT `FK_stok_detail_fifo_average_stok_fifo_average` FOREIGN KEY (`stok_asal_id`) REFERENCES `stok_fifo_average` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_stok_detail_fifo_average_stok_fifo_average_2` FOREIGN KEY (`stok_id`) REFERENCES `stok_fifo_average` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.stok_fifo_average
DROP TABLE IF EXISTS `stok_fifo_average`;
CREATE TABLE IF NOT EXISTS `stok_fifo_average` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `retur_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `retur_penjualan_detail_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `total` bigint(20) DEFAULT NULL,
  `kuantitas_stok` int(11) DEFAULT NULL,
  `average` double DEFAULT NULL,
  `saldo_stok` double DEFAULT NULL,
  `jurnal_persediaan` double DEFAULT NULL,
  `sisa_stok` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.stok_keluar
DROP TABLE IF EXISTS `stok_keluar`;
CREATE TABLE IF NOT EXISTS `stok_keluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detail`)),
  `harga_average` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.stok_masuk
DROP TABLE IF EXISTS `stok_masuk`;
CREATE TABLE IF NOT EXISTS `stok_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`detail`)),
  `harga_average` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.syarat_pengiriman
DROP TABLE IF EXISTS `syarat_pengiriman`;
CREATE TABLE IF NOT EXISTS `syarat_pengiriman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table accounting.tanda_terima_pengiriman_penjualan
DROP TABLE IF EXISTS `tanda_terima_pengiriman_penjualan`;
CREATE TABLE IF NOT EXISTS `tanda_terima_pengiriman_penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` varchar(10) DEFAULT NULL,
  `pengiriman_penjualan_header_id` int(11) DEFAULT NULL,
  `faktur_penjualan_header_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `nominal_ganti_rugi` bigint(20) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tanda_terima_pengiriman_penjualan_pengiriman_penjualan_header` (`pengiriman_penjualan_header_id`),
  KEY `FK_tanda_terima_pengiriman_penjualan_faktur_penjualan_header` (`faktur_penjualan_header_id`),
  KEY `FK_tanda_terima_pengiriman_penjualan_pelanggan` (`pelanggan_id`),
  CONSTRAINT `FK_tanda_terima_pengiriman_penjualan_faktur_penjualan_header` FOREIGN KEY (`faktur_penjualan_header_id`) REFERENCES `faktur_penjualan_header` (`id`),
  CONSTRAINT `FK_tanda_terima_pengiriman_penjualan_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`),
  CONSTRAINT `FK_tanda_terima_pengiriman_penjualan_pengiriman_penjualan_header` FOREIGN KEY (`pengiriman_penjualan_header_id`) REFERENCES `pengiriman_penjualan_header` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.tipe_pengiriman
DROP TABLE IF EXISTS `tipe_pengiriman`;
CREATE TABLE IF NOT EXISTS `tipe_pengiriman` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transaksi_detail_pengiriman
DROP TABLE IF EXISTS `transaksi_detail_pengiriman`;
CREATE TABLE IF NOT EXISTS `transaksi_detail_pengiriman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `tanda_kemasan` varchar(50) DEFAULT NULL,
  `nomor_segel` varchar(50) DEFAULT NULL,
  `asal_barang` varchar(200) DEFAULT NULL,
  `tujuan_barang` varchar(200) DEFAULT NULL,
  `paket` int(10) unsigned DEFAULT NULL,
  `pembayar` int(10) unsigned DEFAULT NULL,
  `penerima` varchar(300) DEFAULT NULL,
  `jenis_barang` int(11) DEFAULT NULL,
  `jumlah_barang` int(11) DEFAULT NULL,
  `tipe_pengiriman` int(11) DEFAULT NULL,
  `alat_transportasi_01_id` int(10) unsigned DEFAULT NULL,
  `alat_transportasi_02_id` int(11) DEFAULT NULL,
  `kurir` text DEFAULT NULL,
  `deskripsi_barang` text DEFAULT NULL,
  `berat_bersih` int(11) DEFAULT NULL,
  `berat_kotor` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `nomor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_transaksi_detail_pengiriman_pengiriman_penjualan_detail` (`pengiriman_penjualan_detail_id`),
  KEY `FK_transaksi_detail_pengiriman_faktur_penjualan_detail` (`faktur_penjualan_detail_id`),
  KEY `FK_transaksi_detail_pengiriman_paket` (`paket`),
  KEY `FK_transaksi_detail_pengiriman_alat_transportasi` (`alat_transportasi_01_id`),
  KEY `FK_transaksi_detail_pengiriman_jenis_barang` (`jenis_barang`),
  KEY `FK_transaksi_detail_pengiriman_pengiriman_pembelian_detail` (`pengiriman_pembelian_detail_id`),
  KEY `FK_transaksi_detail_pengiriman_faktur_pembelian_detail` (`faktur_pembelian_detail_id`),
  CONSTRAINT `FK_transaksi_detail_pengiriman_alat_transportasi` FOREIGN KEY (`alat_transportasi_01_id`) REFERENCES `alat_transportasi` (`id`),
  CONSTRAINT `FK_transaksi_detail_pengiriman_faktur_pembelian_detail` FOREIGN KEY (`faktur_pembelian_detail_id`) REFERENCES `faktur_pembelian_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_detail_pengiriman_faktur_penjualan_detail` FOREIGN KEY (`faktur_penjualan_detail_id`) REFERENCES `faktur_penjualan_detail` (`id`),
  CONSTRAINT `FK_transaksi_detail_pengiriman_jenis_barang` FOREIGN KEY (`jenis_barang`) REFERENCES `jenis_barang` (`id`),
  CONSTRAINT `FK_transaksi_detail_pengiriman_paket` FOREIGN KEY (`paket`) REFERENCES `paket` (`id`),
  CONSTRAINT `FK_transaksi_detail_pengiriman_pengiriman_pembelian_detail` FOREIGN KEY (`pengiriman_pembelian_detail_id`) REFERENCES `pengiriman_pembelian_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_detail_pengiriman_pengiriman_penjualan_detail` FOREIGN KEY (`pengiriman_penjualan_detail_id`) REFERENCES `pengiriman_penjualan_detail` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transaksi_detail_pengiriman_alat_transportasi
DROP TABLE IF EXISTS `transaksi_detail_pengiriman_alat_transportasi`;
CREATE TABLE IF NOT EXISTS `transaksi_detail_pengiriman_alat_transportasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaksi_detail_pengiriman_id` int(11) DEFAULT NULL,
  `alat_transportasi_id` int(11) DEFAULT NULL,
  `kurir` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transaksi_detail_pengiriman_jenis_barang
DROP TABLE IF EXISTS `transaksi_detail_pengiriman_jenis_barang`;
CREATE TABLE IF NOT EXISTS `transaksi_detail_pengiriman_jenis_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaksi_detail_pengiriman_id` int(11) DEFAULT NULL,
  `jenis_barang_id` int(11) DEFAULT NULL,
  `jumlah_barang` int(11) DEFAULT NULL,
  `berat_bersih` int(11) DEFAULT NULL,
  `berat_kotor` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transaksi_jurnal_otomatis
DROP TABLE IF EXISTS `transaksi_jurnal_otomatis`;
CREATE TABLE IF NOT EXISTS `transaksi_jurnal_otomatis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jurnal_header_id` int(11) DEFAULT NULL,
  `jurnal_detail_id` int(11) DEFAULT NULL,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `retur_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_uang_muka_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_beban_id` int(11) DEFAULT NULL,
  `pembayaran_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `retur_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_uang_muka_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_beban_id` int(11) DEFAULT NULL,
  `tanda_terima_pengiriman_penjualan_id` int(11) DEFAULT NULL,
  `penerimaan_penjualan_detail_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `is_inventory` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_transaksi_acc_jurnal_header` (`jurnal_header_id`),
  KEY `FK_transaksi_tanda_terima_pengiriman_penjualan` (`tanda_terima_pengiriman_penjualan_id`),
  KEY `FK_transaksi_pengiriman_pembelian_header` (`pengiriman_pembelian_detail_id`) USING BTREE,
  KEY `FK_transaksi_faktur_pembelian_header` (`faktur_pembelian_detail_id`) USING BTREE,
  KEY `FK_transaksi_pembayaran_pembelian` (`pembayaran_pembelian_detail_id`) USING BTREE,
  KEY `FK_transaksi_pengiriman_penjualan_header` (`pengiriman_penjualan_detail_id`) USING BTREE,
  KEY `FK_transaksi_faktur_penjualan_header` (`faktur_penjualan_detail_id`) USING BTREE,
  KEY `FK_transaksi_penerimaan_penjualan` (`penerimaan_penjualan_detail_id`) USING BTREE,
  KEY `FK_transaksi_jurnal_otomatis_faktur_pembelian_uang_muka_detail` (`faktur_pembelian_uang_muka_detail_id`),
  KEY `FK_transaksi_jurnal_otomatis_faktur_penjualan_uang_muka_detail` (`faktur_penjualan_uang_muka_detail_id`),
  CONSTRAINT `FK_transaksi_jurnal_otomatis_faktur_pembelian_detail` FOREIGN KEY (`faktur_pembelian_detail_id`) REFERENCES `faktur_pembelian_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_jurnal_otomatis_faktur_pembelian_uang_muka_detail` FOREIGN KEY (`faktur_pembelian_uang_muka_detail_id`) REFERENCES `faktur_pembelian_uang_muka_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_jurnal_otomatis_faktur_penjualan_detail` FOREIGN KEY (`faktur_penjualan_detail_id`) REFERENCES `faktur_penjualan_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_jurnal_otomatis_faktur_penjualan_uang_muka_detail` FOREIGN KEY (`faktur_penjualan_uang_muka_detail_id`) REFERENCES `faktur_penjualan_uang_muka_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_jurnal_otomatis_pembayaran_pembelian_detail` FOREIGN KEY (`pembayaran_pembelian_detail_id`) REFERENCES `pembayaran_pembelian_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_jurnal_otomatis_penerimaan_penjualan_detail` FOREIGN KEY (`penerimaan_penjualan_detail_id`) REFERENCES `penerimaan_penjualan_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_jurnal_otomatis_pengiriman_pembelian_detail` FOREIGN KEY (`pengiriman_pembelian_detail_id`) REFERENCES `pengiriman_pembelian_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_transaksi_jurnal_otomatis_pengiriman_penjualan_detail` FOREIGN KEY (`pengiriman_penjualan_detail_id`) REFERENCES `pengiriman_penjualan_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transaksi_syarat_pengiriman
DROP TABLE IF EXISTS `transaksi_syarat_pengiriman`;
CREATE TABLE IF NOT EXISTS `transaksi_syarat_pengiriman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `syarat_pengiriman_id` int(11) DEFAULT NULL,
  `permintaan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pesanan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `penawaran_penjualan_detail_id` int(11) DEFAULT NULL,
  `pesanan_penjualan_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_transaksi_syarat_pengiriman_syarat_pengiriman` (`syarat_pengiriman_id`),
  KEY `FK_transaksi_syarat_pengiriman_permintaan_pembelian_header` (`permintaan_pembelian_detail_id`) USING BTREE,
  KEY `FK_transaksi_syarat_pengiriman_pesanan_pembelian_header` (`pesanan_pembelian_detail_id`) USING BTREE,
  KEY `FK_transaksi_syarat_pengiriman_pengiriman_pembelian_header` (`pengiriman_pembelian_detail_id`) USING BTREE,
  KEY `FK_transaksi_syarat_pengiriman_faktur_pembelian_header` (`faktur_pembelian_detail_id`) USING BTREE,
  KEY `FK_transaksi_syarat_pengiriman_penawaran_penjualan_header` (`penawaran_penjualan_detail_id`) USING BTREE,
  KEY `FK_transaksi_syarat_pengiriman_pesanan_penjualan_header` (`pesanan_penjualan_detail_id`) USING BTREE,
  KEY `FK_transaksi_syarat_pengiriman_faktur_penjualan_header_2` (`faktur_penjualan_detail_id`) USING BTREE,
  KEY `FK_transaksi_syarat_pengiriman_pengiriman_penjualan_header` (`pengiriman_penjualan_detail_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transaksi_syarat_pengiriman_new
DROP TABLE IF EXISTS `transaksi_syarat_pengiriman_new`;
CREATE TABLE IF NOT EXISTS `transaksi_syarat_pengiriman_new` (
  `id` int(11) DEFAULT NULL,
  `syarat_pengiriman_id` int(11) DEFAULT NULL,
  `permintaan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pesanan_pembelian_detail_id` int(11) DEFAULT NULL,
  `pengiriman_pembelian_detail_id` int(11) DEFAULT NULL,
  `faktur_pembelian_detail_id` int(11) DEFAULT NULL,
  `penawaran_penjualan_detail_id` int(11) DEFAULT NULL,
  `pesanan_penjualan_detail_id` int(11) DEFAULT NULL,
  `pengiriman_penjualan_detail_id` int(11) DEFAULT NULL,
  `faktur_penjualan_detail_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transfer_barang_detail
DROP TABLE IF EXISTS `transfer_barang_detail`;
CREATE TABLE IF NOT EXISTS `transfer_barang_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_barang_header_id` int(11) DEFAULT NULL,
  `harga_jasa_id` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.transfer_barang_header
DROP TABLE IF EXISTS `transfer_barang_header`;
CREATE TABLE IF NOT EXISTS `transfer_barang_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `gudang_asal_id` int(11) DEFAULT NULL,
  `gudang_tujuan_id` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table accounting.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
