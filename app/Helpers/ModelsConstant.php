<?php namespace App\Helpers;

class ModelsConstant
{

	/*
	|--------------------------------------------------------------------------
	| Accounting
	|--------------------------------------------------------------------------
	*/
	const TABEL_AKUN          = 'acc_akun';
	const TABEL_JURNAL_HEADER = 'acc_jurnal_header';
	const TABEL_JURNAL_DETAIL = 'acc_jurnal_detail';
	const TABEL_KODE_PAJAK    = 'kode_pajak';
	const VIEW_BUKU_BANK      = 'view_buku_bank';
	const VIEW_HISTORI_AKUN   = 'temp_history_akun';
	const VIEW_SALDO_AKUN     = 'v_saldo_akun';

	const TABEL_TRANSAKSI_SYARAT_PENGIRIMAN     = 'transaksi_syarat_pengiriman';
	const TABEL_TRANSAKSI_DETAIL_PENGIRIMAN     = 'transaksi_detail_pengiriman';

	/*
	|--------------------------------------------------------------------------
	| Master Data
	|--------------------------------------------------------------------------
	*/
	const TABEL_ALAT_TRANSPORTASI  = 'alat_transportasi';
	const TABEL_GROUP_JENIS_BARANG = 'group_jenis_barang';
	const TABEL_HARGA_JASA         = 'harga_jasa';
	const TABEL_JADWAL_PENGIRIMAN  = 'jadwal_pengiriman';
	const TABEL_JENIS_BARANG 	   = 'jenis_barang';
	const TABEL_KATEGORI 		   = 'kategori';
	const TABEL_LOKASI			   = 'lokasi';
	const TABEL_PAKET			   = 'paket';
	#const TABEL_PENGGUNA		   = 'acc_users';
	const TABEL_PRODUK_LAYANAN	   = 'produk_layanan';
	const TABEL_TIPE_PENGIRIMAN    = 'tipe_pengiriman';
	

	/*
	|--------------------------------------------------------------------------
	| Pembelian
	|--------------------------------------------------------------------------
	*/
	const TABEL_FAKTUR_PEMBELIAN_HEADER			  = 'faktur_pembelian_header';
	const TABEL_FAKTUR_PEMBELIAN_DETAIL			  = 'faktur_pembelian_detail';
    const TABEL_RETUR_PEMBELIAN_HEADER			  = 'retur_pembelian_header';
    const TABEL_RETUR_PEMBELIAN_DETAIL			  = 'retur_pembelian_detail';
	const TABEL_FAKTUR_PEMBELIAN_UANG_MUKA		  = 'faktur_pembelian_uang_muka_detail';
	const TABEL_HARGA_JASA_INFORMASI_PEMBELIAN	  = 'harga_jasa_informasi_pembelian';
	const TABEL_PEMASOK 						  = 'pemasok';
	const TABEL_PEMBAYARAN_PEMBELIAN_HEADER 	  = 'pembayaran_pembelian_header';
	const TABEL_PEMBAYARAN_PEMBELIAN_DETAIL		  = 'pembayaran_pembelian_detail';
	const TABEL_PENGIRIMAN_PEMBELIAN_HEADER		  = 'pengiriman_pembelian_header';
	const TABEL_PENGIRIMAN_PEMBELIAN_DETAIL		  = 'pengiriman_pembelian_detail';
	const TABEL_PERMINTAAN_PEMBELIAN_HEADER		  = 'permintaan_pembelian_header';
	const TABEL_PERMINTAAN_PEMBELIAN_DETAIL		  = 'permintaan_pembelian_detail';
	const TABEL_PESANAN_PEMBELIAN_HEADER		  = 'pesanan_pembelian_header';
	const TABEL_PESANAN_PEMBELIAN_DETAIL 		  = 'pesanan_pembelian_detail';
	const TABEL_STOK 							  = 'stok';
	const TABEL_STOK_DETAIL 					  = 'stok_detail';

	const VIEW_UANG_MUKA_PEMBELIAN_BELUM_SELESAI  = 'view_uang_muka_pembelian_belum_selesai';
	const VIEW_PENGIRIMAN_PEMBELIAN_BELUM_SELESAI = 'view_transaksi_pengiriman_pembelian_belum_selesai';
	const VIEW_PESANAN_PEMBELIAN_BELUM_SELESAI    = 'view_transaksi_pesanan_pembelian_belum_selesai';
	const VIEW_PERMINTAAN_PEMBELIAN_BELUM_SELESAI = 'view_transaksi_permintaan_pembelian_belum_selesai';
	const VIEW_HISTORI_STOK 					  = 'view_history_stok';
	const VIEW_STOK_TERSEDIA 					  = 'view_stok_tersedia';
	const VIEW_HUTANG 							  = 'view_hutang';

	

	/*
	|--------------------------------------------------------------------------
	| Pengaturan
	|--------------------------------------------------------------------------
	*/
	const TABEL_MENU		  			= 'acc_menu';
	const TABEL_PREFERENSI_PERUSAHAAN	= 'preferensi_perusahaan';
	const TABEL_ROUTES		  			= 'daftar_fungsi';
	const TABEL_ROUTES_USER	  			= 'daftar_route_user';
	const VIEW_ROUTES_USER 				= 'view_menu_pengguna';

	/*
	|--------------------------------------------------------------------------
	| Pengguna
	|--------------------------------------------------------------------------
	*/
	const TABEL_MENU_PENGGUNA		  	= 'akun_menu_list';
	const TABEL_PENGGUNA				= 'acc_users';

	/*
	|--------------------------------------------------------------------------
	| Penjualan
	|--------------------------------------------------------------------------
	*/
	const TABEL_FAKTUR_PENJUALAN_HEADER			  	  = 'faktur_penjualan_header';
	const TABEL_FAKTUR_PENJUALAN_DETAIL			  	  = 'faktur_penjualan_detail';
    const TABEL_RETUR_PENJUALAN_HEADER			      = 'retur_penjualan_header';
    const TABEL_RETUR_PENJUALAN_DETAIL			      = 'retur_penjualan_detail';
	const TABEL_FAKTUR_PENJUALAN_UANG_MUKA_DETAIL	  = 'faktur_penjualan_uang_muka_detail';
	const TABEL_HARGA_JASA_INFORMASI_PENJUALAN	  	  = 'harga_jasa_informasi_penjualan';
	const TABEL_HARGA_JASA_INFORMASI_PENJUALAN_DETAIL = 'harga_jasa_informasi_penjualan_detail';
	const TABEL_PELANGGAN 						  	  = 'pelanggan';
	const TABEL_PENERIMAAN_PENJUALAN_HEADER 	  	  = 'penerimaan_penjualan_header';
	const TABEL_PENERIMAAN_PENJUALAN_DETAIL		  	  = 'penerimaan_penjualan_detail';
	const TABEL_PENGIRIMAN_PENJUALAN_HEADER		  	  = 'pengiriman_penjualan_header';
	const TABEL_PENGIRIMAN_PENJUALAN_DETAIL		  	  = 'pengiriman_penjualan_detail';
	const TABEL_PENAWARAN_PENJUALAN_HEADER		  	  = 'penawaran_penjualan_header';
	const TABEL_PENAWARAN_PENJUALAN_DETAIL	   	  	  = 'penawaran_penjualan_detail';
	const TABEL_PESANAN_PENJUALAN_HEADER		 	  = 'pesanan_penjualan_header';
	const TABEL_PESANAN_PENJUALAN_DETAIL 		  	  = 'pesanan_penjualan_detail';
	const TABEL_PETUNJUK_PENGIRIMAN_HEADER		 	  = 'petunjuk_pengiriman_header';
	const TABEL_PETUNJUK_PENGIRIMAN_DETAIL 		  	  = 'petunjuk_pengiriman_detail';
	const TABEL_ALAT_TRANSPORTASI_DETAIL_PENGIRIMAN   = 'transaksi_detail_pengiriman_alat_transportasi';
	const TABEL_JENIS_BARANG_DETAIL_PENGIRIMAN 	      = 'transaksi_detail_pengiriman_jenis_barang';

	const VIEW_UANG_MUKA_PENJUALAN_BELUM_SELESAI 	  = 'view_uang_muka_penjualan_belum_selesai';
	const VIEW_PENGIRIMAN_PENJUALAN_BELUM_SELESAI 	  = 'view_transaksi_pengiriman_penjualan_belum_selesai';
	const VIEW_PESANAN_PENJUALAN_BELUM_SELESAI    	  = 'view_transaksi_pesanan_penjualan_belum_selesai';
	const VIEW_PENAWARAN_PENJUALAN_BELUM_SELESAI  	  = 'view_transaksi_penawaran_penjualan_belum_selesai';
	const VIEW_LAPORAN_PENJUALAN 					  = 'view_laporan_penjualan';
	const VIEW_PIUTANG 							  	  = 'view_piutang';

	/*
	|--------------------------------------------------------------------------
	| Perusahaan
	|--------------------------------------------------------------------------
	*/
	const TABEL_DEPARTEMEN		  		= 'departemen';
	const TABEL_DOKUMEN					= 'dokumen';
	const TABEL_GUDANG		  			= 'gudang';
	const TABEL_KETENTUAN_PEMBAYARAN	= 'ketentuan_pembayaran';
	const TABEL_LOKASI_KANTOR		  	= 'lokasi_kantor';
	const TABEL_SYARAT_PENGIRIMAN		= 'syarat_pengiriman';

}
