<?php

namespace App\Repositories;

use App\Helpers\MessageConstant;
use App\Repositories\BaseRepository;
use Exception;
use DB;

class MainRepository extends BaseRepository
{   
    private $PermintaanPembelianHeaderRepository;
    private $PermintaanPembelianDetailRepository;

    private $PesananPembelianDetailRepository;
	private $PesananPembelianHeaderRepository;
	private $PengirimanPembelianDetailRepository;
	private $FakturPembelianHeaderRepository;
	private $FakturPembelianDetailRepository;
	private $FakturPembelianUangMukaRepository;
	private $TransaksiSyaratPengirimanRepository;
	private $TransaksiRepository;
	private $JurnalHeaderRepository;
	private $JurnalDetailRepository;
	private $UangMukaBelumSelesaiRepository;
	private $PreferensiPerusahaanRepository;
	private $KodePajakRepository;
	private $PenggunaRepository;
	private $JadwalPengirimanRepository;
	private $PemasokRepository;
	private $HargaJasaRepository;
	private $HargaAverageRepository;
	private $HistoryStokRepository;
	private $StokRepository;
	private $StokDetailRepository;
	private $StokFifoAverageRepository;
	private $StokDetailFifoAverageRepository;
	private $ViewStokFifoAverageRepository;
	private $NomorSeriTransaksiRepository;
    private $StokTersediaRepository;
	
	private $data_jurnal = [];
	#private $Pajak = [];

	private $TransaksiDetailPengirimanRepository;
	private $AlatTransportasiDetailPengirimanRepository;
	private $JenisBarangDetailPengirimanRepository;

	private $permintaan = null;
	private $pesanan = null;
	private $pengiriman = null;
	private $detail_id = null;
	private $tanggal_stok;
	private $fakturpenjualandetailrepository;
	private $PembayaranPembelianDetailRepository;
	private $jurnal_header;
	private $preferensi;
	private $PengirimanBelumSelesaiRepository;

	private $detail;
	private $header;

	private $total_per_detail = null;
	
	public function initialize()
	{
        
		$this->PermintaanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianHeaderInterface');
		$this->PermintaanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianDetailInterface');
		$this->PermintaanBelumSelesaiRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanBelumSelesaiInterface');

		$this->PesananPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianDetailInterface');
		$this->PesananPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianHeaderInterface');
        $this->PesananPembelianBelumSelesaiRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananBelumSelesaiInterface');
		
		$this->PengirimanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianDetailInterface');
		$this->PengirimanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianHeaderInterface');
        $this->PengirimanPembelianBelumSelesaiRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanBelumSelesaiInterface');

		$this->PenawaranPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PenawaranPenjualanHeaderInterface');
		$this->PenawaranPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PenawaranPenjualanDetailInterface');
		
		$this->JurnalHeaderRepository = \App::make('\App\Repositories\Contracts\Accounting\JurnalHeaderInterface');
		$this->JurnalDetailRepository = \App::make('\App\Repositories\Contracts\Accounting\JurnalDetailInterface');
        $this->TransaksiRepository = \App::make('\App\Repositories\Contracts\Accounting\TransaksiJurnalOtomatisInterface');

		$this->FakturPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\FakturPembelianHeaderInterface');
		$this->FakturPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\FakturPembelianDetailInterface');
		$this->FakturPembelianUangMukaRepository = \App::make('\App\Repositories\Contracts\Pembelian\FakturPembelianUangMukaInterface');
        $this->UangMukaPembelianBelumSelesaiRepository = \App::make('\App\Repositories\Contracts\Pembelian\UangMukaBelumSelesaiInterface');

        $this->PembayaranPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PembayaranPembelianHeaderInterface');
		$this->PembayaranPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PembayaranPembelianDetailInterface');

		$this->TransaksiSyaratPengirimanRepository = \App::make('\App\Repositories\Contracts\JasaEkspedisi\TransaksiSyaratPengirimanInterface');
		
		$this->UangMukaPembelianBelumSelesaiRepository = \App::make('\App\Repositories\Contracts\Pembelian\UangMukaBelumSelesaiInterface');
		$this->PreferensiPerusahaanRepository = \App::make('\App\Repositories\Contracts\Pengaturan\PreferensiPerusahaanInterface');
		$this->KodePajakRepository = \App::make('\App\Repositories\Contracts\Accounting\KodePajakInterface');
		$this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
		$this->JadwalPengirimanRepository = \App::make('\App\Repositories\Contracts\MasterData\JadwalPengirimanInterface');
		$this->PemasokRepository = \App::make('\App\Repositories\Contracts\Pembelian\PemasokInterface');
		$this->HargaJasaRepository = \App::make('\App\Repositories\Contracts\MasterData\HargaJasaInterface');
		$this->HargaAverageRepository = \App::make('\App\Repositories\Contracts\Barang\HargaAverageInterface');

		#$this->StokMasukRepository = \App::make('\App\Repositories\Contracts\Pembelian\StokMasukInterface');
		$this->HistoryStokRepository = \App::make('\App\Repositories\Contracts\Barang\HistoriStokInterface');
		$this->StokRepository = \App::make('\App\Repositories\Contracts\Barang\StokInterface');
		$this->StokDetailRepository = \App::make('\App\Repositories\Contracts\Barang\StokDetailInterface');

		$this->StokFifoAverageRepository = \App::make('\App\Repositories\Contracts\Barang\StokFifoAverageInterface');
		$this->StokDetailFifoAverageRepository = \App::make('\App\Repositories\Contracts\Barang\StokDetailFifoAverageInterface');
		$this->ViewStokFifoAverageRepository = \App::make('\App\Repositories\Contracts\Barang\ViewStokFifoAvgInterface');
        $this->NomorSeriTransaksiRepository = \App::make('\App\Repositories\Contracts\Barang\NomorSeriTransaksiInterface');
        $this->StokTersediaRepository = \App::make('\App\Repositories\Contracts\Barang\StokTersediaInterface');

        $this->TransaksiDetailPengirimanRepository = \App::make('\App\Repositories\Contracts\JasaEkspedisi\TransaksiDetailPengirimanInterface');
		$this->AlatTransportasiDetailPengirimanRepository = \App::make('\App\Repositories\Contracts\Penjualan\AlatTransportasiDetailPengirimanInterface');
		$this->JenisBarangDetailPengirimanRepository = \App::make('\App\Repositories\Contracts\Penjualan\JenisBarangDetailPengirimanInterface');
		$this->FakturPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\NewFakturPenjualanDetailInterface');
		$this->PengirimanPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PengirimanPenjualanDetailInterface');

		
	}

    public static function createData($e)
    {

		## Model 
		## Part Data
		## Request Data

        $PermintaanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianHeaderInterface');
		$PermintaanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianDetailInterface');

        $PesananPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianDetailInterface');
		$PesananPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianHeaderInterface');

		$PengirimanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianDetailInterface');
		$PengirimanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianHeaderInterface');

		$FakturPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\FakturPembelianHeaderInterface');
		$FakturPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\FakturPembelianDetailInterface');
		
		$PembayaranPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PembayaranPembelianHeaderInterface');
		$PembayaranPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PembayaranPembelianDetailInterface');

		$ReturPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\ReturPembelianDetailInterface');
        $ReturPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\ReturPembelianHeaderInterface');
        
		$PenawaranPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PenawaranPenjualanHeaderInterface');
		$PenawaranPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PenawaranPenjualanDetailInterface');
		
		$PesananPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PesananPenjualanHeaderInterface');
		$PesananPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PesananPenjualanDetailInterface');

		$PengirimanPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PengirimanPenjualanHeaderInterface');
		$PengirimanPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PengirimanPenjualanDetailInterface');
		
		$FakturPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\FakturPenjualanHeaderInterface');
        $FakturPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\NewFakturPenjualanDetailInterface');
		

        if ($e['model'] == 'permintaan pembelian') {
            if ($e['part'] == 'header') {
                $result = $PermintaanPembelianHeaderRepository->create([
					'nomor' 				=> $e['data']->nomor,
					'tanggal' 				=> $e['data']->tanggal,
					'pemasok_id' 			=> $e['data']->pemasok_id,
					'jadwal_pengiriman_id' 	=> $e['data']->jadwal_pengiriman_id,
					'keterangan' 			=> $e['data']->keterangan,
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $PermintaanPembelianDetailRepository->create([
                    'permintaan_pembelian_header_id' => $e['data']['header_id'],
                    'harga_jasa_id' 				 => $e['data']['harga_jasa_id'],
                    'kuantitas' 					 => $e['data']['kuantitas'],
                    'jadwal_pengiriman_id'			 => $e['data']['jadwal_pengiriman_id']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'pesanan pembelian') {
            if ($e['part'] == 'header') {
                $result = $PesananPembelianHeaderRepository->create([
					'nomor' 				=> $e['data']->nomor,
					'tanggal' 				=> $e['data']->tanggal,
					'pemasok_id' 			=> $e['data']->pemasok_id,
					'keterangan' 			=> $e['data']->keterangan,
                    'kena_pajak' 			=> $e['data']->kena_pajak,
                    'termasuk_pajak' 		=> $e['data']->termasuk_pajak,
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $PesananPembelianDetailRepository->create([
                    'pesanan_pembelian_header_id'    => $e['data']['header_id'],
                    'permintaan_pembelian_detail_id' => $e['data']['permintaan_pembelian_detail_id'],
                    'harga_jasa_id' 				 => $e['data']['harga_jasa_id'],
                    'kuantitas' 					 => $e['data']['kuantitas'],
                    'harga'      					 => $e['data']['harga'],
                    'jadwal_pengiriman_id'			 => $e['data']['jadwal_pengiriman_id'],
                    'kode_pajak_id' 				 => $e['data']['kode_pajak_id']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'pengiriman pembelian') {
            if ($e['part'] == 'header') {
                $result = $PengirimanPembelianHeaderRepository->create([
					'nomor' 				=> $e['data']->nomor,
					'tanggal' 				=> $e['data']->tanggal,
					'pemasok_id' 			=> $e['data']->pemasok_id,
					'keterangan' 			=> $e['data']->keterangan,
					'nomor_release_order'   => $e['data']->nomor_release_order,
					'gudang_id'				=> $e['data']->gudang
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $PengirimanPembelianDetailRepository->create([
                    'pengiriman_pembelian_header_id' => $e['data']['header_id'],
                    'permintaan_pembelian_detail_id' => $e['data']['permintaan_pembelian_detail_id'],
					'pesanan_pembelian_detail_id'    => $e['data']['pesanan_pembelian_detail_id'],
                    'harga_jasa_id' 				 => $e['data']['harga_jasa_id'],
                    'kuantitas' 					 => $e['data']['kuantitas'],
                    'jadwal_pengiriman_id'			 => $e['data']['jadwal_pengiriman_id'],
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'faktur pembelian') {
            if ($e['part'] == 'header') {
                $result = $FakturPembelianHeaderRepository->create([
					'nomor' 				=> $e['data']->nomor,
					'tanggal' 				=> $e['data']->tanggal,
					'pemasok_id' 			=> $e['data']->pemasok_id,
					'keterangan' 			=> $e['data']->keterangan,
                    'kena_pajak' 			=> $e['data']->kena_pajak,
                    'termasuk_pajak' 		=> $e['data']->termasuk_pajak,
					'gudang_id' 		    => $e['data']->gudang,
					'is_uang_muka' 			=> $e['data']->is_uang_muka
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $FakturPembelianDetailRepository->create([
                    'faktur_pembelian_header_id'     => $e['data']['header_id'],
                    'permintaan_pembelian_detail_id' => $e['data']['permintaan_pembelian_detail_id'],
					'pesanan_pembelian_detail_id'    => $e['data']['pesanan_pembelian_detail_id'],
					'pengiriman_pembelian_detail_id' => $e['data']['pengiriman_pembelian_detail_id'],
                    'harga_jasa_id' 				 => $e['data']['harga_jasa_id'],
                    'kuantitas' 					 => $e['data']['kuantitas'],
                    'harga'      					 => $e['data']['harga'],
                    'jadwal_pengiriman_id'			 => $e['data']['jadwal_pengiriman_id'],
                    'kode_pajak_id' 				 => $e['data']['kode_pajak_id']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'pembayaran pembelian') {
            if ($e['part'] == 'header') {
                $result = $PembayaranPembelianHeaderRepository->create([
					'nomor'     			=> $e['data']->nomor,
					'pemasok_id' 			=> $e['data']->pemasok_id,
					'akun_kredit_id' 		=> $e['data']->akun_kredit_id,
					'nomor_cek' 			=> $e['data']->nomor_cek,
					'tanggal'				=> $e['data']->tanggal,
					'tanggal_cek'			=> $e['data']->tanggal_cek,
					'tanggal_pembayaran'	=> $e['data']->tanggal_pembayaran,
					'nominal_cek'			=> $e['data']->nominal_cek,
					'keterangan'			=> $e['data']->keterangan,
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $PembayaranPembelianDetailRepository->create([
                    'pembayaran_pembelian_header_id' => $e['data']['header_id'],
                    'faktur_pembelian_header_id'     => $e['data']['faktur_pembelian_header_id'],
					'nominal_pembayaran'			 => $e['data']['nominal_pembayaran']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'retur pembelian') {
            if ($e['part'] == 'header') {
                $result = $ReturPembelianHeaderRepository->create([
					'nomor' 							=> $e['data']->nomor,
                    'tanggal' 							=> $e['data']->tanggal,
                    'pemasok_id' 						=> $e['data']->pemasok_id,
                    'pengiriman_pembelian_header_id'    => $e['data']->pengiriman_pembelian_header_id,
                    'faktur_pembelian_header_id'        => $e['data']->faktur_pembelian_header_id,
                    'gudang_id'							=> $e['data']->gudang_id,
                    'keterangan'						=> $e['data']->keterangan,
                    'kena_pajak'                        => $e['data']->kena_pajak,
                    'termasuk_pajak'                    => $e['data']->termasuk_pajak,
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $ReturPembelianDetailRepository->create([
                    'retur_pembelian_header_id'     => $e['data']['header_id'],
					'pengiriman_pembelian_detail_id'=> $e['data']['pengiriman_pembelian_detail_id'],
					'faktur_pembelian_detail_id'    => $e['data']['faktur_pembelian_detail_id'],
					'kuantitas' 					=> $e['data']['kuantitas'],
					'kode_pajak_id' 				=> $e['data']['kode_pajak_id'],
					'harga' 						=> $e['data']['harga']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'penawaran penjualan') {
            if ($e['part'] == 'header') {
                $result = $PenawaranPenjualanHeaderRepository->create([
					'nomor' 				=> $e['data']->nomor,
					'tanggal' 				=> $e['data']->tanggal,
					'pelanggan_id' 			=> $e['data']->pelanggan_id,
					'keterangan' 			=> $e['data']->keterangan,
                    'kena_pajak' 			=> $e['data']->kena_pajak,
                    'termasuk_pajak' 		=> $e['data']->termasuk_pajak,
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $PenawaranPenjualanDetailRepository->create([
                    'penawaran_penjualan_header_id' => $e['data']['header_id'],
					'harga_jasa_id' 				=> $e['data']['harga_jasa_id'],
					'kuantitas'						=> $e['data']['kuantitas'],
					'harga'							=> $e['data']['harga'],
					'kode_pajak_id'					=> $e['data']['kode_pajak_id'],
					'jadwal_pengiriman_id'			=> $e['data']['jadwal_pengiriman_id']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'pesanan penjualan') {
            if ($e['part'] == 'header') {
                $result = $PesananPenjualanHeaderRepository->create([
					'nomor' 				=> $e['data']->nomor,
					'tanggal' 				=> $e['data']->tanggal,
					'pelanggan_id' 			=> $e['data']->pelanggan_id,
					'keterangan' 			=> $e['data']->keterangan,
                    'kena_pajak' 			=> $e['data']->kena_pajak,
                    'termasuk_pajak' 		=> $e['data']->termasuk_pajak,
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $PesananPenjualanDetailRepository->create([
                    'pesanan_penjualan_header_id'   => $e['data']['header_id'],
					'penawaran_penjualan_detail_id' => $e['data']['penawaran_penjualan_detail_id'],
					'harga_jasa_id' 				=> $e['data']['harga_jasa_id'],
					'kuantitas'						=> $e['data']['kuantitas'],
					'harga'							=> $e['data']['harga'],
					'kode_pajak_id'					=> $e['data']['kode_pajak_id'],
					'jadwal_pengiriman_id'			=> $e['data']['jadwal_pengiriman_id']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'pengiriman penjualan') {
            if ($e['part'] == 'header') {
                $result = $PengirimanPenjualanHeaderRepository->create([
					'nomor' 				=> $e['data']->nomor,
					'tanggal' 				=> $e['data']->tanggal,
					'pelanggan_id' 			=> $e['data']->pelanggan_id,
					'keterangan' 			=> $e['data']->keterangan,
					'gudang_id' 			=> $e['data']->gudang,
                    #'nomor_release_order' 	=> $e['data']->nomor_release_order,
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $PengirimanPenjualanDetailRepository->create([
                    'pengiriman_penjualan_header_id'   => $e['data']['header_id'],
					'penawaran_penjualan_detail_id'    => $e['data']['penawaran_penjualan_detail_id'],
					'pesanan_penjualan_detail_id'      => $e['data']['pesanan_penjualan_detail_id'],
					'harga_jasa_id' 				   => $e['data']['harga_jasa_id'],
					'kuantitas'						   => $e['data']['kuantitas'],
					'jadwal_pengiriman_id'			   => $e['data']['jadwal_pengiriman_id']
                ]);
                return $result;
            }
        }
		elseif ($e['model'] == 'faktur penjualan') {
            if ($e['part'] == 'header') {
                $result = $FakturPenjualanHeaderRepository->create([
					'nomor'                       => $e['data']->nomor,
                    'tanggal'                     => $e['data']->tanggal,
                    'pelanggan_id'                => $e['data']->pelanggan_id,
                    'kena_pajak'                  => $e['data']->kena_pajak,
                    'termasuk_pajak'              => $e['data']->termasuk_pajak,
                    'jadwal_pengiriman_id'        => $e['data']->jadwal_pengiriman_id,
                    'is_uang_muka'                => $e['data']->is_uang_muka,
                    'gudang_id'                   => $e['data']->gudang,
                    'keterangan'                  => $e['data']->keterangan
				]);
                return $result;
            }elseif($e['part'] == 'detail'){
                $result = $FakturPenjualanDetailRepository->create([
                    'penawaran_penjualan_detail_id' => $e['data']['penawaran_penjualan_detail_id'],
					'pesanan_penjualan_detail_id'   => $e['data']['pesanan_penjualan_detail_id'],
					'pengiriman_penjualan_detail_id'=> $e['data']['pengiriman_penjualan_detail_id'],
					'faktur_penjualan_header_id'    => $e['data']['header_id'],
					'harga_jasa_id'                 => $e['data']['harga_jasa_id'],
					'kode_pajak_id'                 => $e['data']['kode_pajak_id'],
					'kuantitas'                     => $e['data']['kuantitas'],
					'harga'                         => $e['data']['harga'],
					'jadwal_pengiriman_id'          => $e['data']['jadwal_pengiriman_id']
                ]);
                return $result;
            }
        }
    }

	public static function createDetailExpedisi($data)
    {
		$TransaksiDetailPengirimanRepository = \App::make('\App\Repositories\Contracts\JasaEkspedisi\TransaksiDetailPengirimanInterface');
		$AlatTransportasiDetailPengirimanRepository = \App::make('\App\Repositories\Contracts\Penjualan\AlatTransportasiDetailPengirimanInterface');
		$JenisBarangDetailPengirimanRepository = \App::make('\App\Repositories\Contracts\Penjualan\JenisBarangDetailPengirimanInterface');

		try {
		    $barang = [];
			for ($i=0; $i < $data['transaksi']->kuantitas; $i++) {
				if (isset($data['barang']['nomor'][$i])) {
					$nomor = $data['barang']['nomor'][$i];
				}else{
					$nomor = null;
				}
				$barang[] = $nomor;
				if (isset($data['barang']['tanda_kemasan'][$i])) {
					$tanda_kemasan = $data['barang']['tanda_kemasan'][$i];
				}else{
					$tanda_kemasan = null;
				}
                $barang[] = $tanda_kemasan;
				if (isset($data['barang']['nomor_segel'][$i])) {
					$nomor_segel = $data['barang']['nomor_segel'][$i];
				}else{
					$nomor_segel = null;
				}
                $barang[] = $nomor_segel;
				if (isset($data['barang']['asal_barang'][$i])) {
                    if (is_array($data['barang']['asal_barang'][$i])){ $asal_barang = $data['barang']['asal_barang'][$i]['id']; }
                    else{ $asal_barang = $data['barang']['asal_barang'][$i]; }

				}else{
					$asal_barang = null;
				}
                $barang[] = $asal_barang;
				if (isset($data['barang']['tujuan_barang'][$i])) {
				    if (is_array($data['barang']['tujuan_barang'][$i])){  $tujuan_barang = $data['barang']['tujuan_barang'][$i]['id']; }
				    else { $tujuan_barang = $data['barang']['tujuan_barang'][$i]; }

				}else{
					$tujuan_barang = null;
				}
                $barang[] = $tujuan_barang;
				if (isset($data['barang']['paket'][$i])) {
				    if (is_array($data['barang']['paket'][$i])){ $paket = $data['barang']['paket'][$i]['id'];}
				    else{ $paket = $data['barang']['paket'][$i]; }
				}else{
					$paket = null;
				}
                $barang[] = $paket;
                if (isset($data['barang']['penerima'][$i])) {
                    if (is_array($data['barang']['penerima'][$i])){ $paket = $data['barang']['penerima'][$i]['id'];}
                    else{ $penerima = $data['barang']['penerima'][$i]; }
                }else{
                    $penerima = null;
                }
                if (isset($data['barang']['pembayar'][$i])) {
                    $pembayar = $data['barang']['pembayar'][$i];
                }else{
                    $pembayar = null;
                }

				$detail_transaksi = $TransaksiDetailPengirimanRepository->create([
					$data['tipe']				 	 => $data['transaksi']->id,
					'nomor'                  		 => $nomor,
					'tanda_kemasan'                  => $tanda_kemasan,
					'nomor_segel'                    => $nomor_segel,
					'asal_barang'                    => $asal_barang,
					'tujuan_barang'                  => $tujuan_barang,
					'paket'                          => $paket,
                    'pembayar'                       => $pembayar,
                    'penerima'                       => $penerima,
				]);
				if (isset($data['barang']['jenis_barang']) and (count($data['barang']['jenis_barang']) > 0)) {
					if( array_key_exists($i,$data['barang']['jenis_barang']['id'])){
						if ($data['barang']['jenis_barang']['id'][$i] != null) {
							for ($j=0; $j < count($data['barang']['jenis_barang']['id'][$i]) ; $j++) {
								if (isset($data['barang']['jenis_barang']['id'][$i][$j]) ) {
									$JenisBarangDetailPengirimanRepository->create([
										'transaksi_detail_pengiriman_id' => $detail_transaksi->id,
										'jenis_barang_id'                => $data['barang']['jenis_barang']['id'][$i][$j],
										'jumlah_barang'                  => $data['barang']['jenis_barang']['jumlah_barang'][$i][$j],
										'berat_bersih'                   => $data['barang']['jenis_barang']['berat_bersih'][$i][$j],
										'berat_kotor'                    => $data['barang']['jenis_barang']['berat_kotor'][$i][$j],
										'keterangan'                     => $data['barang']['jenis_barang']['keterangan'][$i][$j]
									]);
								}
							}
						}
					}
				}
		
				if (isset($data['barang']['alat_transportasi']) and (count($data['barang']['alat_transportasi']) > 0)) {
					if (array_key_exists($i,$data['barang']['alat_transportasi']['id'])) {
						if ($data['barang']['alat_transportasi']['id'][$i] != null) {
							for ($j=0; $j < count($data['barang']['alat_transportasi']['id'][$i]) ; $j++) { 
								if (isset($data['barang']['alat_transportasi']['id'][$i][$j]) ) {
									$AlatTransportasiDetailPengirimanRepository->create([
										'transaksi_detail_pengiriman_id' => $detail_transaksi->id,
										'alat_transportasi_id'           => $data['barang']['alat_transportasi']['id'][$i][$j],
										'kurir'                          => $data['barang']['alat_transportasi']['kurir'][$i][$j],
										'keterangan'                     => $data['barang']['alat_transportasi']['keterangan'][$i][$j]
									]);
								}
							}
						}
					}		
				}   
				##End Create Detail of Detail
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
        return true;
    }

	public static function resetHargaAverage($data)
    {
		## Header Transaksi
		## Detail Transaksi
		## Nama Transaksi
		## Mode Data
		## Jurnal Transaksi

		$PreferensiPerusahaanRepository = \App::make('\App\Repositories\Contracts\Pengaturan\PreferensiPerusahaanInterface');
		$HargaJasaRepository = \App::make('\App\Repositories\Contracts\MasterData\HargaJasaInterface');
		$StokFifoAverageRepository = \App::make('\App\Repositories\Contracts\Barang\StokFifoAverageInterface');
		$StokDetailFifoAverageRepository = \App::make('\App\Repositories\Contracts\Barang\StokDetailFifoAverageInterface');
		$ViewStokFifoAverageRepository = \App::make('\App\Repositories\Contracts\Barang\ViewStokFifoAvgInterface');
        $JurnalHeaderRepository = \App::make('\App\Repositories\Contracts\Accounting\JurnalHeaderInterface');
		$JurnalDetailRepository = \App::make('\App\Repositories\Contracts\Accounting\JurnalDetailInterface');
        $TransaksiRepository = \App::make('\App\Repositories\Contracts\Accounting\TransaksiJurnalOtomatisInterface');

        $PesananPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianDetailInterface');
        $PesananPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianHeaderInterface');

        $PengirimanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianDetailInterface');
        $PengirimanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianHeaderInterface');


        try {
    	    $preferensi = $PreferensiPerusahaanRepository->find(1);
    	    $metode = $preferensi->metode_akuntansi;
    	    $hargaJasa = $HargaJasaRepository->find($data['detail']->harga_jasa_id);
            $tanggal = $data['header']->tanggal;
                if ($data['mode'] == 'add'){
                    $stok_baru      				 = $StokFifoAverageRepository->create([
                        'barang_id' 				 => $data['detail']->harga_jasa_id,
                        'kuantitas' 				 => $data['detail']->kuantitas,
                        'harga'     				 => $data['detail']->harga,
                        'tanggal'   				 => $data['header']->tanggal,
                        'total'     				 => $data['detail']->harga * $data['detail']->kuantitas,
                        $data['transaksi'] 			 => $data['detail']->id,
                        'created_at' 				 => $data['header']->created_at
                    ]);
                }
                elseif ($data['mode'] == 'delete'){
                    $stok_baru = $StokFifoAverageRepository
						->where($data['transaksi'],$data['detail']->id)
                        ->where('barang_id',$data['detail']->harga_jasa_id)
						->first();
                    $StokFifoAverageRepository
						->where($data['transaksi'],$data['detail']->id)
                        ->where('barang_id',$data['detail']->harga_jasa_id)
						->forceDelete();
                }
                elseif ($data['mode'] == 'update_harga'){
                    $StokFifoAverageRepository
					->where('pengiriman_pembelian_detail_id',$data['detail']->pengiriman_pembelian_detail_id)
					->where('barang_id',$data['detail']->harga_jasa_id)
					->update([
						'harga' => $data['detail']->harga,
					]);

					$stok_baru = $StokFifoAverageRepository
					->where('pengiriman_pembelian_detail_id',$data['detail']->pengiriman_pembelian_detail_id)
					->where('barang_id',$data['detail']->harga_jasa_id)
					->first();
                   $tanggal = $stok_baru->tanggal;
                }
                elseif ($data['mode'] == 'reset_harga'){
					$stok_baru = $StokFifoAverageRepository
						->where('pengiriman_pembelian_detail_id',$data['detail']->pengiriman_pembelian_detail_id)
						->where('barang_id',$data['detail']->harga_jasa_id)->first();
					if ($stok_baru){
                        $harga_pesanan = $PesananPembelianDetailRepository->find($data['detail']->pesanan_pembelian_detail_id);
                        if ($harga_pesanan){
                            $stok_baru->harga = $harga_pesanan->harga;
                        }
                        else{
                            $harga_terakhir = $ViewStokFifoAverageRepository
                                ->where('barang_id',$data['detail']->harga_jasa_id)
                                ->whereNull('pengiriman_penjualan_detail_id')
                                ->whereNull('faktur_penjualan_detail_id')
                                ->whereNull('retur_pembelian_detail_id')
                                ->orderBy('tanggal','desc')
                                ->orderBy('created_at','desc')
                                ->first();

                            if($harga_terakhir){
                                $stok_baru->harga = $harga_terakhir->harga;
                            }else{
                                $stok_baru->harga = 0;
                            }

                        }
                        $stok_baru->save();
                        $tanggal = $stok_baru->tanggal;
                    }else{
					    return true;
                    }
            	}
                elseif ($data['mode'] == 'update_faktur'){
                    $stok_baru = $StokFifoAverageRepository
                     ->where('pengiriman_penjualan_detail_id',$data['detail']->pengiriman_penjualan_detail_id)
                     ->where('barang_id',$data['detail']->harga_jasa_id)->first();;
                    $stok_baru->faktur_penjualan_detail_id = $data['detail']->id;
                    $stok_baru->harga = $data['detail']->harga;
                    $stok_baru->save();
                }
                elseif ($data['mode'] == 'reset_faktur'){
                    $stok_baru = $StokFifoAverageRepository
                        ->where('pengiriman_penjualan_detail_id',$data['detail']->pengiriman_penjualan_detail_id)
                        ->where('barang_id',$data['detail']->harga_jasa_id)->first();
                    $stok_baru->faktur_penjualan_detail_id = null;
                    $stok_baru->save();
                }

                if ($stok_baru){
                    $reset_jurnal = $HargaJasaRepository->resetJurnalPersediaan([
                        'tanggal'						 => $tanggal,
                        'repo_harga_avg'				 => $StokFifoAverageRepository,
                        'repo_harga_avg_detail'			 => $StokDetailFifoAverageRepository,
                        'repo_view_stok_fifo_avg'		 => $ViewStokFifoAverageRepository,
                        'repo_jurnal_detail'			 => $JurnalDetailRepository,
                        'repo_transaksi_jurnal_otomatis' => $TransaksiRepository,
                        'barang_id' 					 => $data['detail']->harga_jasa_id,
                        'barang' 						 => $hargaJasa,
						'created_at'    				 => $stok_baru->created_at,
						'metode'    					 => $metode,
						'id_stok'   					 => $stok_baru->id,
						#'reset'     					 => false,
                        #'jurnal_header_id'               => $this->jurnal_header->id,
                        
                    ]);
                    if ($reset_jurnal !== true) {
                        return $reset_jurnal;
                    }
                }
	    	return true;
    	} catch (Exception $e) {
    		return $e->getMessage();
    	}
    	return true;
    }

	public static function createJurnalHeader($transaksi)
	{
		
	}

    public static function numberKodeGenerator($data)
    {
        ## Modul
        ## Repo

        $prefix = '';
        $PenomoranRepository = \App::make('\App\Repositories\Contracts\Pengaturan\PenomoranInterface');
        $penomoran = $PenomoranRepository->where('modul',$data['modul'])->first();
        if($penomoran){
            $prefix = $penomoran->prefix;
        }
        $list = $data['repo']->withTrashed()->get();
        return  self::generateCode($list,$prefix);
	}

    public static function generateCode($data,$prefix){
        $kodes = [];
        $temp_end = 1;
        foreach ($data as $key => $value) {
            if (isset($value['nomor'])){
                $kodes[] =  $value['nomor'];
            }elseif (isset($value['kode'])){
                $kodes[] =  $value['kode'];
            }elseif (isset($value['nomor_jurnal'])){
                $kodes[] =  $value['nomor_jurnal'];
            }
        }

        foreach ($kodes as $key => $value) {
            $kode = self::prefixCode($prefix,$temp_end);
            $available = in_array($kode,$kodes);
            if (!$available) {
                return $kode;
            }
            $temp_end++;
        }
        return self::prefixCode($prefix,$temp_end);
    }

    public static function prefixCode($prefix,$last)
    {
        ## Last Kode
        ## prefix
        $kode = '';
        if (strlen($last) == 1) {
            $kode = $prefix.'00000'.$last;
            return $kode;
        }elseif (strlen($last) == 2) {
            $kode = $prefix.'0000'.$last;
            return $kode;
        }
        elseif (strlen($last) == 3) {
            $kode = $prefix.'000'.$last;
            return $kode;
        }
        elseif (strlen($last) == 4) {
            $kode = $prefix.'00'.$last;
            return $kode;
        }
        elseif (strlen($last) == 5) {
            $kode = $prefix.'0'.$last;
            return $kode;
        }
        elseif (strlen($last) >5 ) {
            $kode = $prefix.''.$last;
            return $kode;
        }
    }

    public static function getBarangPerGudang($data){

	    ## Id barang
        ## Id Gudang

        $jumlahBarang = 0;
        $stok = DB::table('view_mutasi_stok')
            ->where('harga_jasa_id',$data['barang'])
            ->get();
        //return ($stok);
        foreach ($stok as $X => $x){
            if($x->gudang_tujuan_id == $data['gudang'] ){
                if($x->pengiriman_pembelian_detail_id != null or $x->faktur_pembelian_detail_id != null or $x->retur_penjualan_detail_id != null){
                    $jumlahBarang += $x->kuantitas;
                }
                elseif($x->pengiriman_penjualan_detail_id != null or $x->faktur_penjualan_detail_id != null or $x->retur_pembelian_detail_id != null){
                    $jumlahBarang -= $x->kuantitas;
                }

            }
            if ($x->transfer_barang_detail_id != null){
                if ($x->gudang_tujuan_id == $data['gudang']){
                    $jumlahBarang += $x->kuantitas;
                }else{
                    $jumlahBarang -= $x->kuantitas;
                }
            }
        }
        return $jumlahBarang;
    }

    public static function cekPembelian($data)
    {
        ## Barang
        ## Gudang
        ## Kuantitas

        $ViewStokFifoAverageRepository = \App::make('\App\Repositories\Contracts\Barang\ViewStokFifoAvgInterface');

        ## Cek Ketersediaan Stok
        $cek_stok = $ViewStokFifoAverageRepository
            ->where('barang_id',$data['barang'])
            ->whereNull('pengiriman_penjualan_detail_id')
            ->whereNull('faktur_penjualan_detail_id')
            ->whereNull('retur_pembelian_detail_id')
            ->first();

        if ($cek_stok) {
            $dataCek = ['barang' => $data['barang'], 'gudang' => $data['gudang']];
            //return self::getBarangPerGudang($dataCek);
            if ($data['kuantitas'] > self::getBarangPerGudang($dataCek)) {
                //return 'jumlah Barang Di Gudang = '. self::getBarangPerGudang($dataCek);
                return MessageConstant::LESS_QUANTITY_MSG;
            }
        }else{
            return MessageConstant::ZERO_QUANTITY_MSG;
        }
        return true;
    }

    public static function setDesimal($value,$desimal)
    {
        if ($desimal == 0){
            return floor($value);
        }
        return $value;
    }

    public static function isUsedJadwal($id)
    {
        $PermintaanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianDetailInterface');
        $PesananPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianDetailInterface');
        $PengirimanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianDetailInterface');
        $FakturPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\FakturPembelianDetailInterface');
        $PenawaranPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PenawaranPenjualanDetailInterface');
        $PesananPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PesananPenjualanDetailInterface');
        $PengirimanPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\PengirimanPenjualanDetailInterface');
        $FakturPenjualanDetailRepository = \App::make('\App\Repositories\Contracts\Penjualan\NewFakturPenjualanDetailInterface');

        $cek_permintaan_pembelian = $PermintaanPembelianDetailRepository->where('jadwal_pengiriman_id',$id)->count();
        $cek_pesanan_pembelian    = $PesananPembelianDetailRepository->where('jadwal_pengiriman_id',$id)->count();
        $cek_pengiriman_pembelian = $PengirimanPembelianDetailRepository->where('jadwal_pengiriman_id',$id)->count();
        $cek_faktur_pembelian     = $FakturPembelianDetailRepository->where('jadwal_pengiriman_id',$id)->count();

        $cek_penawaran_penjualan  = $PenawaranPenjualanDetailRepository->where('jadwal_pengiriman_id',$id)->count();
        $cek_pesanan_penjualan    = $PesananPenjualanDetailRepository->where('jadwal_pengiriman_id',$id)->count();
        $cek_pengiriman_penjualan = $PengirimanPenjualanDetailRepository->where('jadwal_pengiriman_id',$id)->count();
        $cek_faktur_penjualan     = $FakturPenjualanDetailRepository->where('jadwal_pengiriman_id',$id)->count();

        if ($cek_permintaan_pembelian > 0 )  {
            return 'Jadwal Pengiriman Telah Terpakai Pada Permintaan Pembelian, Tidak Dapat Dihapus!';
                //$this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PERMINTAAN_PEMBELIAN_MSG);
        }elseif ($cek_pesanan_pembelian > 0 ) {
             return 'Jadwal Pengiriman Telah Terpakai Pada Pesanan Pembelian, Tidak Dapat Dihapus!';
                //$this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PESANAN_PEMBELIAN_MSG);
        }elseif ($cek_pengiriman_pembelian > 0) {
            return 'Jadwal Pengiriman Telah Terpakai Pada Pengiriman Pembelian, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PENGIRIMAN_PEMBELIAN_MSG);
        }elseif ($cek_faktur_pembelian > 0) {
            return 'Jadwal Pengiriman Telah Terpakai Pada Faktur Pembelian, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_FAKTUR_PEMBELIAN_MSG);
        }elseif ($cek_penawaran_penjualan > 0) {
            return 'Jadwal Pengiriman Telah Terpakai Pada Penawaran Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PENAWARAN_PENJUALAN_MSG);
        }elseif ($cek_pesanan_penjualan > 0 ) {
            return 'Jadwal Pengiriman Telah Terpakai Pada Pesanan Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PESANAN_PENJUALAN_MSG);
        }elseif ($cek_pengiriman_penjualan > 0) {
            return 'Jadwal Pengiriman Telah Terpakai Pada Pengiriman Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PENGIRIMAN_PENJUALAN_MSG);
        }elseif ($cek_faktur_penjualan > 0 ) {
            return 'Jadwal Pengiriman Telah Terpakai Pada Faktur Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_FAKTUR_PENJUALAN_MSG);
        }
        return true;
    }

    public static function isUsedPemasok($id)
    {
        $PermintaanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianHeaderInterface');
        $PesananPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianHeaderInterface');
        $PengirimanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PengirimanPembelianHeaderInterface');
        $FakturPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\FakturPembelianHeaderInterface');
        $PembayaranPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PembayaranPembelianHeaderInterface');

        $cek_permintaan_pembelian = $PermintaanPembelianHeaderRepository->where('pemasok_id',$id)->count();
        $cek_pesanan_pembelian    = $PesananPembelianHeaderRepository->where('pemasok_id',$id)->count();
        $cek_pengiriman_pembelian = $PengirimanPembelianHeaderRepository->where('pemasok_id',$id)->count();
        $cek_faktur_pembelian     = $FakturPembelianHeaderRepository->where('pemasok_id',$id)->count();
        $cek_pembayaran_pembelian = $PembayaranPembelianHeaderRepository->where('pemasok_id',$id)->count();


        if ($cek_permintaan_pembelian > 0 )  {
            return 'Pemasok Telah Terpakai Pada Permintaan Pembelian, Tidak Dapat Dihapus!';
            //$this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PERMINTAAN_PEMBELIAN_MSG);
        }elseif ($cek_pesanan_pembelian > 0 ) {
            return 'Pemasok Telah Terpakai Pada Pesanan Pembelian, Tidak Dapat Dihapus!';
            //$this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PESANAN_PEMBELIAN_MSG);
        }elseif ($cek_pengiriman_pembelian > 0) {
            return 'Pemasok Telah Terpakai Pada Pengiriman Pembelian, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PENGIRIMAN_PEMBELIAN_MSG);
        }elseif ($cek_faktur_pembelian > 0) {
            return 'Pemasok Telah Terpakai Pada Faktur Pembelian, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_FAKTUR_PEMBELIAN_MSG);
        }elseif ($cek_pembayaran_pembelian > 0) {
            return 'Pemasok Telah Terpakai Pada Pembayaran Pembelian, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_FAKTUR_PEMBELIAN_MSG);
        }

        return true;
    }

    public static function isUsedPelanggan($id)
    {
        $PenawaranPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PenawaranPenjualanHeaderInterface');
        $PesananPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PesananPenjualanHeaderInterface');
        $PengirimanPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PengirimanPenjualanHeaderInterface');
        $FakturPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\FakturPenjualanHeaderInterface');
        $PenerimaanPenjualanHeaderRepository = \App::make('\App\Repositories\Contracts\Penjualan\PenerimaanPenjualanHeaderInterface');

        $cek_penawaran_penjualan  = $PenawaranPenjualanHeaderRepository->where('pelanggan_id',$id)->count();
        $cek_pesanan_penjualan    = $PesananPenjualanHeaderRepository->where('pelanggan_id',$id)->count();
        $cek_pengiriman_penjualan = $PengirimanPenjualanHeaderRepository->where('pelanggan_id',$id)->count();
        $cek_faktur_penjualan     = $FakturPenjualanHeaderRepository->where('pelanggan_id',$id)->count();
        $cek_penerimaan_penjualan = $PenerimaanPenjualanHeaderRepository->where('pelanggan_id',$id)->count();

        if ($cek_penawaran_penjualan > 0) {
            return 'Pelanggan Telah Terpakai Pada Penawaran Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PENAWARAN_PENJUALAN_MSG);
        }
        elseif ($cek_pesanan_penjualan > 0 ) {
            return 'Pelanggan Telah Terpakai Pada Pesanan Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PESANAN_PENJUALAN_MSG);
        }
        elseif ($cek_pengiriman_penjualan > 0) {
            return 'Pelanggan Telah Terpakai Pada Pengiriman Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_PENGIRIMAN_PENJUALAN_MSG);
        }
        elseif ($cek_faktur_penjualan > 0 ) {
            return 'Pelanggan Telah Terpakai Pada Faktur Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_FAKTUR_PENJUALAN_MSG);
        }
        elseif ($cek_penerimaan_penjualan > 0 ) {
            return 'Pelanggan Telah Terpakai Pada Penerimaan Penjualan, Tidak Dapat Dihapus!';
            //return $this->respondInternalError($errors = null, $message = MessageConstant::HARGA_JASA_ON_FAKTUR_PENJUALAN_MSG);
        }
        return true;
    }

    public static function isUsedAkun($id)
    {
        $JurnalDetailRepository = \App::make('\App\Repositories\Contracts\Accounting\JurnalDetailInterface');

        $cek_jurnal = $JurnalDetailRepository->where('kode_akun',$id)->count();

        if ($cek_jurnal > 0) {
            return 'Akun Telah Terpakai Pada Jurnal, Tidak Dapat Dihapus!';
        }
        return true;
    }
}
