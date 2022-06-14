<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class BaseRepository
{

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public $Pajak = [];
	private $data_jurnal = [];

	public $jurnal_persediaan = [];
	public $jurnal_hpp = [];
	public $jurnal_barang_terkirim = [];
	public $semua_pajak = 0;

	/**
	 * @param $data
	 * @return static
	 */
	public function create($data)
	{
		return $this->model->create($data);
	}

	/**
	 * @param $record
	 * @param $data
	 * @return mixed
	 */
	public function update($record, $data)
	{
		if (is_int($record)) {
			$this->model->find($record);
			$id = $record;
		} else {
			$this->model = $record;
			$id = $record->id;
		}
		return $this->model->where('id', $id)->update($data);
	}

	/**
	 * @param $id
	 * @return boolean
	 */
	public function delete($id)
	{
		return $this->model->destroy($id);
	}

	/**
	 * @param $id
	 * @return boolean
	 */
	public function forceDelete($id)
	{
		return $this->model->where('id', $id)->forcedelete();
	}

	/**
	 * @param $id
	 * @return \Illuminate\Support\Collection
	 */
	public function find($id)
	{
		return $this->model->find($id);
	}

	/**
	 * @param $data
	 * @return \Illuminate\Support\Collection
	 */
	public function distict($data)
	{
		return $this->model->distict($data);
	}

	// public function distinct($data)
	// {
	// 	return $this->model->distinct($data);
	// }

	/**
	 * @param $data
	 * @return \Illuminate\Support\Collection
	 */
	public function where($key,$data)
	{
		return $this->model->where($key,$data);
	}

	/**
	 * @param $data
	 * @return \Illuminate\Support\Collection
	 */
	public function whereOpt($key,$opt,$data)
	{
		return $this->model->where($key,$opt,$data);
	}

	/**
	 * @param $data
	 * @return \Illuminate\Support\Collection
	 */
	public function whereIn($key,$data)
	{
		return $this->model->whereIn($key,$data);
	}

	/**
	 * @param $data
	 * @return \Illuminate\Support\Collection
	 */
	public function whereNotIn($key,$data)
	{
		return $this->model->whereNotIn($key,$data);
	}

	/**
	 * @param $data
	 * @return \Illuminate\Support\Collection
	 */
	public function whereDate($field,$operator,$value)
	{
		return $this->model->whereDate($field,$operator,$value);
	}

	/**
	 * @return Collection
	 */
	public function all()
	{
		return $this->model->all();
	}

	/**
	 * @return Collection
	 */
	public function with($table)
	{
		return $this->model->with($table);
	}

	/**
	 * @return Collection
	 */
	public function relation($data)
	{
		return $this->model->with($data);
	}

	/**
	 * @return Collection
	 */
	public function get_relation($data, $id)
	{
		return $this->model->with($data)->where('id', $id);
	}

	/**
	 * @return Collection
	 */
	public function min($field)
	{
		return $this->model->min($field);
	}
	/**
	 * @return Collection
	 */
	public function max($field)
	{
		return $this->model->max($field);
	}

	public function truncate()
	{
		return $this->model->truncate();
	}

    public function withTrashed()
    {
        return $this->model->withTrashed();
    }


	/**
	 * We can receive a single id, or an array of ids
	 * Based on the input we return a collection or an object
	 *
	 *
	 * @param int $id
	 * @param int $user_id
	 * @param int $with
	 * @return mixed
	 */
	//public function getById($id, $user_id = null, $with = null)
	public function getById($id, $user_id = null, $with = null)
	{
		if (is_array($id)) {
			$result = $this->model->whereIn('id', $id);
		} else {
			$result = $this->model->where('id', $id);
		}

		if ($user_id)
			$result->where('user_id', $user_id);

		if ($with)
			$result->with($with);

		if (is_array($id)) {
			return $result->get();
		} else {
			return $result->firstOrFail();
		}
	}

	public function last()
	{
		return $this->model->all()->last();
	}

    public function get_jurnal_child($value)
    {
        return $this->model->where('header_id',$value)->get();
    }

    public function jurnal_header_check($value)
    {
        $exist = $this->model->where('nomor_jurnal',$value)->first();
        if ($exist == null) {
            return true;
        }else{
            return false;
        }

    }

    public function jurnal_header_get_id($value)
    {
        $exist = $this->model->where('nomor_jurnal',$value)->first();
        return $exist->id;

    }

    public function Akun_get_parent()
    {
        return $this->model->where('parent_id',0)->get();
    }

    public function Akun_get_child($parent)
    {
        return $this->model->where('parent_id',$parent)->get();
    }

    public function delete_jurnal_detail($value)
    {
        return $this->model->where('header_id',$value)->delete();
    }

    public function getbyheader($value)
    {
    	return $this->model->where('harga_penjualan_pengiriman_header_id',$value)->get();
    }
    public function delete_hpp_detail($value)
    {
    	return $this->model->where('harga_penjualan_pengiriman_header_id',$value)->forceDelete();
    }

    public function calculate_kena_pajak($data)
    {
    	$hasil =[];
    	$hasil['total_pajak'] = $data['total_harga']*($data['nilai_pajak']*0.01);
    	return $hasil;
    }

    public function calculate_termasuk_pajak($data)
    {
    	$hasil =[];
    		$hasil['total_pajak'] = $data['total_harga']-$data['total_harga']/(($data['nilai_pajak']+100)*0.01);
    	return $hasil;
    }

    public function create_stok_masuk($params)
    {	
    	$data_stok = [];
    	if ($params['harga_jasa']->tipe == 1) {
			// $cek_harga_jasa = $params['stok_masuk']
			// ->where('harga_jasa_id',$params['harga_jasa_id'])
			// ->first();

			##Create Detail Barang and Looping Detail Stok
			for ($i=0; $i < $params['kuantitas'] ; $i++) { 
				##Jika Barang Menggunakan Nomor Seri
				if($params['harga_jasa']->menggunakan_nomor_seri == 1){
					if (!array_key_exists($i, $params['sn'])) {
						return 'Barang Menggunakan Nomor Seri, Silahkan Menginput Nomor Seri!';
					}
					if (count($params['sn']) != $params['kuantitas']) {
						return 'Jumlah SN Tidak Sama Dengan Kuantitas Barang!';
					}
					$sn = $params['sn'][$i];
					##Cek Nomor seri Telah ada
					$sn_cek_params = [
						'stok'          => $params['stok'],
						'sn'            => $sn,
						'harga_jasa_id' => $params['harga_jasa_id']
					];
				    $cek_sn = $this->cekSNTerpakai($sn_cek_params); 
					if ($cek_sn == 'Y') {
						return 'Nomor Seri '.$sn.' Telah Ada!';
					}
				## Jika Tidak Menggunakan Nomor Seri
				}else{
					$sn = '';
				}
				if ($params['gudang'] == null) {
					return 'Gudang Wajib Diisi!';
				}
				## Membuat Detail Menjadi Array
				$data_stok [$sn] = [
					'tanggal'   	  => $params['tanggal'],
					'harga_jasa_id'   => $params['harga_jasa_id'],
					'harga'     	  => $params['harga'],
					'nomor_seri'      => $sn,
					'gudang'    	  => $params['gudang'],
				];
			    
			    $stok = $params['stok']->create($data_stok[$sn]);
			    $stok_detail = $params['stok_detail']->create(
					[
						'stok_id'            => $stok->id,
						$params['transaksi'] => $params['id_transaksi'] 
					]
				);
			}
		}
		
		return true;
    }

    public function keluarkan_stok_masuk($params)
    {
    	if ($params['harga_jasa']->tipe == 1 ) {
    		##Get Id Stok Dari Detail Stok Per Transaksi
			$delete_stok_detail = $params['stok_detail']->where($params['transaksi'],$params['id_transaksi'])->get();
			$id_stok = [];
			
			if (count($delete_stok_detail) > 0) {
				foreach ($delete_stok_detail as $key => $value) {
					$params['stok']->where('id',$value->stok_id)->delete();
				}
				##Menghapus Nomor Seri Dari Daftar Transaksi
				$params['stok_detail']->where($params['transaksi'],$params['id_transaksi'])->delete();
			}
		}
		return true;
    }

    public function create_stok_keluar($params)
    {
    	$barang = $params['harga_jasa']->find($params['barang_id']); 
    	// Get Tipe Harga Jasa
    	if ($barang->tipe == 1) {
    		if ($params['metode'] == 'FIFO') {
    			$last_stok = $params['stok_tersedia']->where('harga_jasa_id',$params['barang_id'])
    			->orderBy('tanggal','asc')->limit($params['kuantitas'])->get();

    			foreach ($last_stok as $key => $value) {
    				$data_stok [$value->nomor_seri] = [
						//'uid'		=> bin2hex(random_bytes(16)),
						'tanggal'   	  => $params['tanggal'],
						'harga_jasa_id'   => $params['barang_id'],
						'harga'     	  => $params['harga'],
						'nomor_seri'      => $value->nomor_seri,
						'gudang'    	  => $params['gudang'],
						// 'transaksi' 	  => $params['transaksi'],
						// 'id_transaksi' 	  => $params['id_transaksi'],
					];

					//$stok = $params['stok']->create($data_stok[$value->nomor_seri]);
					$stok_detail = $params['stok_detail']->create(
						[
							'stok_id'            => $value->id,
							$params['transaksi'] => $params['id_transaksi'] 
						]);

					if($params['harga_jasa']->menggunakan_nomor_seri == 1){
						$sn = $value->nomor_seri;
						// Menginput Nomor Seri
						$params['nomor_seri']->create([
							'nomor_seri'         => $sn,
							$params['transaksi'] => $params['id_transaksi'],
						]);
						// Jika Tidak Menggunakan Nomor Seri
					}
    			}
    		// End Jika Metode FIFO
    		}
    		elseif($params['metode'] == 'Average'){
    			// Create Detail Barang and Looping Detail Stok
    			if ($params['harga_jasa']->menggunakan_nomor_seri == 1) {
    		// 		for ($i=0; $i < $params['kuantitas'] ; $i++) {
    		// 			if (!isset($params['sn'][$i])) {
    		// 				return 'Tidak Ada Nomor Seri Di Input';
    		// 			}
    		// 			$sn = $params['sn'][$i];
    							
    		// 			// Menginput Nomor Seri
						// // $params['nomor_seri']->create([
						// // 	'nomor_seri'         => $sn,
						// // 	$params['transaksi'] => $params['id_transaksi'],
						// // ]);
						// $id_stok = $params['stok_tersedia']->where('nomor_seri',$sn)
						// ->where('harga_jasa_id',$barang->id)->first();
						// //return $id_stok;
					
						// if ($id_stok != null) {
						// 	if($id_stok->gudang != $params['gudang']){
						// 		return 'Stok SN '.$id_stok->nomor_seri.'Tidak Ada Pada Gudang yang Dipilih!';
						// 	}
						// 	$params['stok_detail']->create([
						// 		'stok_id'            => $id_stok->id,
						// 		$params['transaksi'] => $params['id_transaksi'],
						// 	]);
						// }else{
						// 	//return $id_stok;
						// 	return 'Nomor Seri '.$sn .' Pada Barang '.$barang->kode .' id '.$barang->id.' Sudah Tidak Tersedia / Terjual!';
						// }
    		// 		}
    				$sns = $params['stok_tersedia']->whereIn('nomor_seri',$params['sn'])->get();
    				if (count($sns) > 0 ) {
    					foreach ($sns as $key => $value) {
	    					if($value->gudang != $params['gudang']){
								return 'Stok SN '.$value->nomor_seri.'Tidak Ada Pada Gudang yang Dipilih! id Gudang '.$params['gudang'].' SN Berada Pada Gudang '.$value->gudang;
							}
							$params['stok_detail']->create([
								'stok_id'            => $value->id,
								$params['transaksi'] => $params['id_transaksi'],
							]);
	    				}
    				}
    				
						// ->where('harga_jasa_id',$barang->id)->first();

    			}else{
    				$stok_tersedia_data = $params['stok_tersedia']->where('harga_jasa_id',$barang->id)
    				->limit($params['kuantitas'])->orderBy('tanggal','asc')->get();
    				foreach ($stok_tersedia_data as $key => $value) {
    					$stok_detail = $params['stok_detail']->create(
							[
								'stok_id'            => $value->id,
								$params['transaksi'] => $params['id_transaksi'] 
							]
						);
    				}
    			}
			// End Jika Metode Average	
    		}
    	}
    	//return 'salah';
    	return 'Ok';	

	}

	public function batal_stok_keluar($params)
	{
		$barang = $params['harga_jasa']->find($params['barang_id']);
		if ($barang->tipe == 1) {
			$params['stok_detail']->where($params['transaksi'],$params['id_transaksi'])->delete();
			//$params['nomor_seri']->where($params['transaksi'],$params['id_transaksi'])->delete();
		}
	}
	
	public function getHargaAverage($stok)
	{	
		$total_harga  = 0;
		// Looping Tiap Stok Yang di Kirim
		foreach ($stok as $key => $value) {
			// JIka Stok Berbentu Object	
			if (isset($value->harga)) {
				// Menambah Total Harga
				$total_harga += $value->harga;
			// Jika Stok Berbentuk Array
			}elseif(isset($value['harga'])){
				// Menambah Total Harga
				$total_harga += $value['harga'];
			}		
		}
		// Jika Total Harga Yang Didapat Nol
		if ($total_harga == 0) {
			return 0;
		// Jika Total Harga Lebih Dari Nol
		}else{
			return $total_harga/count($stok);
		}
	}

	public function getHargaAverageBarang($repo_stok,$id_barang)
	{	
		return $repo_stok->where('harga_jasa_id',$id_barang)->avg('harga');
	}

	public function checkUsedTransaksi($params)
	{	
		$status = false;
		$terpakai = $params['terpakai']->where($params['header_id'],$params['id'])->get();
		foreach ($terpakai as $tpk => $used) {
			$used_tr = $params['pemakai']->where($params['used_id'],$used['id'])->get();
			if (count($used_tr) > 0) {
				$status = true;
			}
		}
		return $status;
	}

	public function updateHargaStok($params)
	{
		$stok_transaksi = $params['stok_detail']->where($params['transaksi'],$params['id_transaksi'])->get();
		foreach ($stok_transaksi as $key => $value) {
			$update_harga = $params['stok']->where('id',$value->stok_id)->update(['harga' => $params['harga']]);
		}	

	}

	public function resetPajakJurnal()
	{
		$this->Pajak       = [];
		$this->data_jurnal = [];
		$this->semua_pajak = 0;
	}

	public function hitungTotalPerDetail($harga,$kuantitas,$kena_pajak,$termasuk_pajak,$kode_pajak,$repo_pajak)
	{
		$harga_awal = $total_detail = $harga * $kuantitas;
		if ($kena_pajak == 1 and $termasuk_pajak == 0) {
			$pajak = $repo_pajak->find($kode_pajak);
			$total_pajak = $harga_awal*(floatval($pajak->nilai)*0.01);
			$total_detail = $harga_awal ;		
    	}
    	elseif ($kena_pajak == 1 and $termasuk_pajak == 1) {
    		$pajak = $repo_pajak->find($kode_pajak);
    		$total_pajak = $harga_awal-($harga_awal/((floatval($pajak->nilai)+100)*0.01));
    		//return $total_pajak;
			$total_detail = $harga_awal - $total_pajak;	
    	}
    	return $total_detail;
	}

	public function hitungPajak($harga,$kuantitas,$kena_pajak,$termasuk_pajak,$kode_pajak,$repo_pajak,$tipe_pajak)
	{
		$new_total_detail = 0;
		$harga_awal = $total_detail = $harga * $kuantitas;
		$total_pajak = 0;
		$akun_pajak = null;

		if ($kena_pajak == 1 and $termasuk_pajak == 0) {
			$pajak = $repo_pajak->find($kode_pajak);
			$total_pajak = $harga_awal*(floatval($pajak->nilai)*0.01);
			$new_total_detail = $harga_awal + $total_pajak; 
			$total_detail = $harga_awal + $total_pajak;
			if ($tipe_pajak == 'pembelian') {
				if (array_key_exists($pajak->akun_pajak_pembelian_id, $this->Pajak)) {
					$this->Pajak[$pajak->akun_pajak_pembelian_id] += $total_pajak;

				}else{
					$this->Pajak[$pajak->akun_pajak_pembelian_id] = $total_pajak;
				}
                $akun_pajak = $pajak->akun_pajak_pembelian_id;
			}
			elseif ($tipe_pajak == 'penjualan') {
				if (array_key_exists($pajak->akun_pajak_penjualan_id, $this->Pajak)) {
					$this->Pajak[$pajak->akun_pajak_penjualan_id] += $total_pajak;
				}else{
					$this->Pajak[$pajak->akun_pajak_penjualan_id] = $total_pajak;
				}
                $akun_pajak = $pajak->akun_pajak_penjualan_id;
			}	
				
    	}
    	elseif ($kena_pajak == 1 and $termasuk_pajak == 1) {
    		
    		$pajak = $repo_pajak->find($kode_pajak);
    		$total_pajak = $harga_awal-($harga_awal/(  ( floatval($pajak->nilai) +100 ) *0.01 ) );
    		$new_total_detail = $harga_awal - $total_pajak;
			//$total_detail = $harga_awal - $total_pajak;	
			if ($tipe_pajak == 'pembelian') {
				if (array_key_exists($pajak->akun_pajak_pembelian_id, $this->Pajak)) {
					$this->Pajak[$pajak->akun_pajak_pembelian_id] += $total_pajak;
				}else{
					$this->Pajak[$pajak->akun_pajak_pembelian_id] = $total_pajak;
				}
                $akun_pajak = $pajak->akun_pajak_pembelian_id;
			}
			elseif ($tipe_pajak == 'penjualan') {
				if (array_key_exists($pajak->akun_pajak_penjualan_id, $this->Pajak)) {
					$this->Pajak[$pajak->akun_pajak_penjualan_id] += $total_pajak;
				}else{
					$this->Pajak[$pajak->akun_pajak_penjualan_id] = $total_pajak;

				}
                $akun_pajak = $pajak->akun_pajak_penjualan_id;
			}
    	}
		return [
		    'akun' => $akun_pajak,
		    'nilai' =>$total_pajak
        ];
    	$this->semua_pajak += $total_pajak;
    	return true;
    	#return 'saya';
    	#return $new_total_detail;

	}

	public function hitungGrandTotal($kena_pajak,$termasuk_pajak,$detail,$repo_pajak)
	{
		$grand_total = 0;
		foreach ($detail as $dt => $dts) {
			 $harga_awal = $dts['kuantitas']* $dts['harga'];
			if ($kena_pajak == 1 and $termasuk_pajak == 0) {
				$pajak = $repo_pajak->find($dts['kode_pajak_id']);
				$total_pajak = $harga_awal*(floatval($pajak->nilai)*0.01);
				//$new_total_detail = $harga_awal + $total_pajak; 
				//$total_detail = $harga_awal + $total_pajak;	
				$grand_total += ($harga_awal + $total_pajak);
	    	}
			else{
	    		$grand_total += $harga_awal;
	    	}
		}

    	return $grand_total;

	}

	public function buatDataJurnal($kode_akun,$tipe,$nilai,$keterangan)
	{
			if ($tipe == 'kredit') {
				$this->data_jurnal [] =
				[ 
					'kode_akun' => $kode_akun,
					'debet'     => 0,
					'kredit'	=> $nilai,
					'keterangan'=> $keterangan
				];	
			}
			elseif ($tipe == 'debet') {
				$this->data_jurnal [] =
				[ 
					'kode_akun' => $kode_akun,
					'debet'     => $nilai,
					'kredit'	=> 0,
					'keterangan'=> $keterangan
				];		
			}		
	}

	public function createJurnal($id,$nomor,$tanggal,$repo_jurnal_header,$repo_jurnal_detail,$repo_transaksi,$transaksi,$transksi_field,$tipe_transaksi,$akun_transaksi)
	{
		//membuat jurnal otomatis baru
		$header_jurnal = $repo_jurnal_header->create([
			'nomor_jurnal' 	=> $nomor. ' - '.$transaksi,
			'tanggal_jurnal'=> $tanggal.' '.date('h:m:s'),
			'keterangan' 	=> 'Jurnal Otomatis '.$transaksi,
			'is_manual'     => 0
		]);
		foreach ($this->data_jurnal as $key => $value) {
			$repo_jurnal_detail->create([
				'header_id' => $header_jurnal->id,
				'kode_akun' => $value['kode_akun'],
				'debet'     => $value['debet'],
				'kredit'    => $value['kredit'],
				'keterangan'=> $value['keterangan']
			]);
		}

		foreach ($this->Pajak as $key2 => $value2) {
			if ($tipe_transaksi == 'pembelian') {
				$repo_jurnal_detail->create([
					'header_id' => $header_jurnal->id,
					'kode_akun' => $key2,
					'debet'     => $value2,
					'kredit'    => 0,
					'keterangan'=> 'Pajak'
				]);

				$repo_jurnal_detail->create([
					'header_id' => $header_jurnal->id,
					'kode_akun' => $akun_transaksi,
					'debet'     => 0,
					'kredit'    => $value2,
					'keterangan'=> 'Hutang'
				]);
			}elseif($tipe_transaksi == 'penjualan'){
				$repo_jurnal_detail->create([
					'header_id' => $header_jurnal->id,
					'kode_akun' => $key2,
					'debet'     => 0,
					'kredit'    => $value2,
					'keterangan'=> 'Pajak'
				]);

				// $repo_jurnal_detail->create([
				// 	'header_id' => $header_jurnal->id,
				// 	'kode_akun' => $akun_transaksi,
				// 	'debet'     => $value2,
				// 	'kredit'    => 0,
				// 	'keterangan'=> 'Piutang'
				// ]);
			}
			
		}

		//Mencatat Data Ke Tabel Transaksi
		$transaksi = $repo_transaksi->create([
			'jurnal_header_id' => $header_jurnal->id,
			$transksi_field    => $id
		]);
	}

	public function createSyaratPengiriman($syarat_pengiriman,$transaksi_tipe,$transaksi_id,$repo_transaksi_syarat)
	{
		foreach ($syarat_pengiriman as $key => $value) {
			$repo_transaksi_syarat->create([
				'syarat_pengiriman_id' => $value,
				$transaksi_tipe		   => $transaksi_id
			]);
		}
	}

	public function cekSNTerpakai($params)
	{
		$result = $params['stok']
		->where('nomor_seri',$params['sn'])
		->where('harga_jasa_id',$params['harga_jasa_id'])
		->get();

		return count($result) > 0 ? 'Y' : 'N';

		//$this->respondInternalError($errors = null, $message = 'Nomor Seri Telah Terpakai!') : $this->respondOk('Nomor Seri Belum Terdaftar!') ;
	}

	public function resetJurnalPersediaan($params)
	{
		#return true;
		
		try {
			$hasil = true;
			$kuantitas_akhir = $average_akhir = $id_akhir = 0 ;
			$kuantitas_stok = $average = $jurnal_persediaan = 0;
            $tanggal_mulai = $params['tanggal'];
            $kuantitas_sebelum = $average_penjualan = 0;
            $tanggal_penjualan_akhir = null;
            $created_at_akhir = $params['created_at']->toDateTimeString();
            $tanggal_filter_pembelian = $params['tanggal'];
            $created_at_akhir_pembelian = $params['created_at']->toDateTimeString();
            $xx = null;
            $waktu_patokan = $params['created_at'];

            ## Mencari Semua Stok Yang Terpakai Di Detail
            $id_stok_setelah = $params['repo_harga_avg']
                ->where('barang_id',$params['barang_id'])
                ->where('tanggal','>=',$tanggal_mulai)
                ->get()->pluck(['id'])->toArray();
            ## Menghapus Semua Detail Dari ID Stok Yang Setelah Tanggal Reset
            $params['repo_harga_avg_detail']
                ->whereIn('stok_asal_id',$id_stok_setelah)->forceDelete();
            $params['repo_harga_avg_detail']
                ->whereIn('stok_id',$id_stok_setelah)->forceDelete();

			$cek_minus =  $params['repo_view_stok_fifo_avg']
			->where('barang_id',$params['barang_id'])
			->where('minus',1)
			->whereNull('pengiriman_pembelian_detail_id')
			->whereNull('faktur_pembelian_detail_id')
            ->whereNull('retur_penjualan_detail_id')
			->first();

			if ($cek_minus) {
				#return $cek_minus;
				## Jika Tanggal Minus kurang dari Tanggal Transaksi
				if ($cek_minus->tanggal < $tanggal_mulai) {
					$tanggal_mulai = $cek_minus->tanggal;
					$created_at_akhir = $cek_minus->created_at;
					$waktu_patokan = $cek_minus->created_at;
				}
			}
            ## Mencari Semua Stok Yang Terpakai Di Detail
            $id_stok_setelah = $params['repo_harga_avg']
            ->where('barang_id',$params['barang_id'])
            ->where('tanggal','>=',$tanggal_mulai)
            ->get()->pluck(['id'])->toArray();
            ## Menghapus Semua Detail Dari ID Stok Yang Setelah Tanggal Reset
            $params['repo_harga_avg_detail']
            ->whereIn('stok_asal_id',$id_stok_setelah)->forceDelete();
            $params['repo_harga_avg_detail']
            ->whereIn('stok_id',$id_stok_setelah)->forceDelete();

			# Mengumpulkan Data Pembelian yang akan Direset
            $list_pembelian =  $params['repo_view_stok_fifo_avg']
            ->where('barang_id',$params['barang_id'])
            ->where('kuantitas_tersedia' ,'>',0)
            ->whereNull('pengiriman_penjualan_detail_id')
            ->whereNull('faktur_penjualan_detail_id')

            ->whereNull('retur_pembelian_detail_id')

            ->orderBy('tanggal','asc')
            ->orderBy('created_at','asc')
            ->get()->toArray();

			#return $list_pembelian;

            $total_pembelian = count($list_pembelian);

            # Mengumpulkan Data Penjualan yang akan Direset
			$list_penjualan =  $params['repo_view_stok_fifo_avg']
			->where('barang_id',$params['barang_id'])
			->where('tanggal' ,'>=', $tanggal_mulai )
			->whereNull('pengiriman_pembelian_detail_id')
			->whereNull('faktur_pembelian_detail_id')
            ##retur
            ->whereNull('retur_penjualan_detail_id')
            ##retur
			->orderBy('tanggal','asc')
			->orderBy('created_at','asc')
			->get()->toArray();
            $total_penjualan = count($list_penjualan);

            ## Jika Metode Akuntansi Average
            if($params['metode'] == 'Average'){
				
                $stok_awal = 0;
                $average_awal = 0;

                ## Menetukan Average Awal
                $sebelum = $params['repo_view_stok_fifo_avg']
                ->where('barang_id',$params['barang_id'])
                ->where('id','!=',$params['id_stok'])
                ->whereNull('pengiriman_penjualan_detail_id')
                ->whereNull('faktur_penjualan_detail_id')
                ## Retur
                ->whereNull('retur_pembelian_detail_id')
                ## Retur
                ->where('tanggal','<',$tanggal_mulai)
                ->orderBy('tanggal','desc')
                ->orderBy('created_at','desc')
                ->get();

                if (count($sebelum) > 0){

                    $sama = $params['repo_view_stok_fifo_avg'] #DB::table('view_stok_fifo_avg') #
                    ->where('barang_id',$params['barang_id'])
                    ->where('id','!=',$params['id_stok'])
                    ->where('tanggal',$tanggal_mulai)
                    ->where('created_at' ,'<', $created_at_akhir)
                    ->whereNull('pengiriman_penjualan_detail_id')
                    ->whereNull('faktur_penjualan_detail_id')
                    ## Retur
                    ->whereNull('retur_pembelian_detail_id')
                    ## Retur
                    ->orderBy('tanggal','desc')
                    ->orderBy('created_at','desc')
                    ->get();

                    $average_awal = $sebelum->union($sama)->first();
                    $stok_awal = $sebelum->union($sama)->sum('kuantitas_tersedia');

                    if ($average_awal){
                        $tanggal_filter_pembelian = $average_awal->tanggal;
                        $created_at_akhir_pembelian = $average_awal->created_at->timestamp;

                        $waktu_pembelian_terakhir = $average_awal->created_at;
                        $waktu_patokan = $waktu_pembelian_terakhir->toISOString();
                        $xx = $waktu_patokan;

                        $average_awal = $average_awal->average;
                    }
                }else{
					#return 'tidak ada sebelum';
                    $average_awal = $params['repo_view_stok_fifo_avg']
                    ->where('barang_id',$params['barang_id'])
                    ->where('id','!=',$params['id_stok'])
                    ->where('tanggal',$tanggal_mulai)
                    ->where('created_at', '<', $created_at_akhir)
					->whereNull('pengiriman_penjualan_detail_id')
                    ->whereNull('faktur_penjualan_detail_id')
                    ## Retur
                    ->whereNull('retur_pembelian_detail_id')
                    ## Retur
                    ->orderBy('tanggal','desc')
                    ->orderBy('created_at','desc')
                    ->first();

					$stok_awal = $params['repo_view_stok_fifo_avg'] #DB::table('view_stok_fifo_avg') #
                    ->where('barang_id',$params['barang_id'])
                    ->where('id','!=',$params['id_stok'])
                    ->where('tanggal',$tanggal_mulai)
                    ->where('created_at' ,'<', $created_at_akhir)
                    ->whereNull('pengiriman_penjualan_detail_id')
                    ->whereNull('faktur_penjualan_detail_id')
                    ## Retur
                    ->whereNull('retur_pembelian_detail_id')
                    ## Retur
                    ->orderBy('tanggal','desc')
                    ->orderBy('created_at','desc')
                    ->sum('kuantitas_tersedia');

					#return $average_awal;
                    if ($average_awal){
                        $tanggal_filter_pembelian = $average_awal->tanggal;
                        $created_at_akhir_pembelian = $average_awal->created_at->timestamp;

                        $waktu_pembelian_terakhir = $average_awal->created_at;
                        $waktu_patokan = $waktu_pembelian_terakhir->toISOString();
                        $xx = $waktu_patokan ;

                        $average_awal = $average_awal->average;

                    }
                    else{$tanggal_filter_pembelian = null;
                        $created_at_akhir_pembelian = null;

                        $waktu_pembelian_terakhir = null;
                        $waktu_patokan = null;
                        $xx = $waktu_patokan;

                    }
                }

                if (!$cek_minus){
					
                    $y = 0;
                    while ($y < $total_pembelian){
                        $pembelian = $list_pembelian[$y];
                        ## Filter Tanggal Untuk Average
                        $z = $pembelian['created_at'];
                        #$created_at_pembelian = \Carbon\Carbon::parse($pembelian['created_at'])->timestamp;
                        if ($pembelian['tanggal'] > $tanggal_filter_pembelian) {

                            $average_awal = $average = (($average_awal * $stok_awal) + ($pembelian['kuantitas'] * $pembelian['harga'])) /
                                ($stok_awal + $pembelian['kuantitas']);

                            $params['repo_harga_avg']
                            ->where('id', $pembelian['id'])
                            ->update([
                                'average' => $average,
                                'kuantitas_stok' => $stok_awal + $pembelian['kuantitas'],
                                'jurnal_persediaan' => $pembelian['kuantitas'] * $pembelian['harga']
                            ]);
                            $stok_awal += $pembelian['kuantitas'];
                            $tanggal_filter_pembelian = $pembelian['tanggal'];
                            $waktu_patokan = $pembelian['created_at'];
                            $xx = $z;

                        }
                        elseif ($tanggal_filter_pembelian == $pembelian['tanggal']){
                            Log::info(' Waktu Patokan / x = '.$xx.' Waktu Sekarang z = '.$z);

                            if ($z > $xx ){
                                $average_awal = $average = (($average_awal * $stok_awal) + ($pembelian['kuantitas'] * $pembelian['harga'])) /
                                    ($stok_awal + $pembelian['kuantitas']);

                                $params['repo_harga_avg']
                                    ->where('id', $pembelian['id'])
                                    ->update([
                                        'average' => $average,
                                        'kuantitas_stok' => $stok_awal + $pembelian['kuantitas'],
                                        'jurnal_persediaan' => $pembelian['kuantitas'] * $pembelian['harga']
                                    ]);
                                $stok_awal += $pembelian['kuantitas'];
                                $tanggal_filter_pembelian = $pembelian['tanggal'];
                                $waktu_patokan = $pembelian['created_at'];

                                Log::info('z / waktu Sekarang Lebih Besar , Ganti Average');
                                $xx = $z;
                            }else{
                                Log::info('z / waktu Sekarang Lebih Kecil , Average Lama');
                            }
                        }
                        $y++;
                    }
                }

				
                ## inisiasi index awal untuk while
                $x = $y = 0;
                $id_terpakai = [];
                $total_kuantitas = $kuantitas_akhir;
                $total_kuantitas = 0;
                $kuantitas_penjualan = $total_kuantitas;

                ## Mulai Looping Penjualan
                while ($x < count($list_penjualan)) {
					
                    $stok_jurnal = 0;
                    $penjualan = $list_penjualan[$x];
                    $kuantitas_butuh = $penjualan['kuantitas_butuh'];
                    $average = $average_awal;
                    $kuantitas_sebelum_cukup = 0;
                    ## Apakah Jumlah Kuantitas Butuh sudah cukup
                    if ($kuantitas_akhir < $kuantitas_butuh) {
                        $catat_detail = true;

                        ## Mulai Looping Pembelian
                        $y = 0;
                        while ($y < $total_pembelian) {
							
                            if (array_key_exists($y, $list_pembelian)) {
                                $pembelian = $list_pembelian[$y];
                                $pembelian['kuantitas_terpakai_sekarang'] = 0;

                                $z = $pembelian['created_at'];
                                $zz = $penjualan['created_at'];
                                $zb = $z;

                                $sbreak = false;
                                ## Jika Kebutuhan Penjualan Mencukupi Dan Tanggal Pembelian Lebih Dari Tanggal Penjualan
                                if ($pembelian['tanggal'] == $penjualan['tanggal']){
                                    if ($zb > $zz){
                                        $sbreak = true;
                                    }
                                }elseif ($pembelian['tanggal'] > $penjualan['tanggal']){
                                    $sbreak = true;
                                }
                                if ($catat_detail == false and $sbreak == true){
                                    break;
                                }

                                ## Filter Tanggal Untuk Average
                                $created_at_pembelian = \Carbon\Carbon::parse($pembelian['created_at'])->timestamp;

                                if ($pembelian['tanggal'] > $tanggal_filter_pembelian) {
									#return 'Loop Pembelian Cek Tanggal';
                                    $average_awal = $average = (($average_awal * $stok_awal) + ($pembelian['kuantitas'] * $pembelian['harga'])) /
                                        ($stok_awal + $pembelian['kuantitas']);

                                    $params['repo_harga_avg']
                                        ->where('id', $pembelian['id'])
                                        ->update([
                                            'average' => $average,
                                            'kuantitas_stok' => $stok_awal + $pembelian['kuantitas'],
                                            'jurnal_persediaan' => $pembelian['kuantitas'] * $pembelian['harga']
                                        ]);
                                    $stok_awal += $pembelian['kuantitas'];
                                    $tanggal_filter_pembelian = $pembelian['tanggal'];
                                    $waktu_patokan = $pembelian['created_at'];
                                    $xx = $z;
                                }elseif ($tanggal_filter_pembelian == $pembelian['tanggal']){
									#return 'Loop Pembelian Cek Tanggal 2';
                                   #return (' Waktu Patokan / x = '.$xx.' Waktu Sekarang z = '.$z);
									Log::info(' Waktu Patokan / x = '.$xx.' Waktu Sekarang z = '.$z);
									#return $stok_awal;
                                    if ($z > $xx ){
										#return $stok_awal + $pembelian['kuantitas'];
                                        $average_awal = $average = (($average_awal * $stok_awal) + ($pembelian['kuantitas'] * $pembelian['harga'])) /
                                            ($stok_awal + $pembelian['kuantitas']);

                                        $params['repo_harga_avg']
                                            ->where('id', $pembelian['id'])
                                            ->update([
                                                'average' => $average,
                                                'kuantitas_stok' => $stok_awal + $pembelian['kuantitas'],
                                                'jurnal_persediaan' => $pembelian['kuantitas'] * $pembelian['harga']
                                            ]);
                                        $stok_awal += $pembelian['kuantitas'];
                                        $tanggal_filter_pembelian = $pembelian['tanggal'];
                                        $waktu_patokan = $pembelian['created_at'];

                                        Log::info('z / waktu Sekarang Lebih Besar , Ganti Average');
                                        $xx = $z;
                                    }else{
										
                                        Log::info('z / waktu Sekarang Lebih Kecil , Average Lama');
                                    }
                                }
								
                                $kuantitas_akhir += $pembelian['kuantitas_tersedia'];
                                if ($catat_detail == true){
                                    ## Apakah Kuantitas Dari Pembelian Memenuhi Kebutuhan Penjualan
                                    if ($kuantitas_akhir < $kuantitas_butuh) {
                                        $kuantitas_sebelum_cukup = $kuantitas_akhir;
                                        $stok_di_pakai = $pembelian['kuantitas_tersedia'];

                                    }
                                    elseif ($kuantitas_akhir >= $kuantitas_butuh) {
                                        $catat_detail = false;
                                        $stok_di_pakai =  $kuantitas_butuh - $kuantitas_sebelum_cukup;
                                        $list_pembelian[$y]['kuantitas_tersedia'] = $pembelian['kuantitas_tersedia'] - $stok_di_pakai;
                                        #$kuantitas_sebelum_cukup = $pembelian['kuantitas_tersedia'];

                                    }
                                    $stok_tersisa = $pembelian['kuantitas_tersedia'] - $stok_di_pakai;
                                    $list_pembelian[$y]['kuantitas_terpakai'] = $stok_di_pakai;
                                    ## Apakah Kuantitas Tersedia Sudah 0
                                    if ($stok_tersisa == 0){
                                        unset($list_pembelian[$y]);
                                    }

                                    ## Catat Detail Penjualan
                                    $params['repo_harga_avg_detail']->create([
                                        'stok_id' => $penjualan['id'],
                                        'stok_asal_id' => $pembelian['id'],
                                        'kuantitas' => $stok_di_pakai
                                    ]);
                                }
                                elseif ($catat_detail == false){
                                    $stok_di_pakai = 0;
                                }

                                ## jumlah Stok Untuk Jurnal Pesediaan Penjualan
                                $stok_jurnal += $stok_di_pakai;

                            }
                            $y++;
                            $id_terpakai[] = $pembelian['id'];
                        }
                    }else{
                        $kuantitas_akhir = 0;
                        $x = -1;
                    }

                    $list_pembelian = array_values($list_pembelian);
                    $total_pembelian = count($list_pembelian);

                    $average_penjualan = $average;

					#return 'Sebelum Update Jurnal';
                    ## Update JUrnal Persediaan Penjualan
                    $params['repo_harga_avg']
                        ->where('id',$penjualan['id'])
                        ->update([
                            'average'			 => $average,
                            'kuantitas_stok'	 => $stok_awal - $kuantitas_butuh,#$stok_penjualan,
                            'jurnal_persediaan'	 => $stok_jurnal*$average_penjualan,
                        ]);
                    $nilai_jurnal = $stok_jurnal*$average_penjualan;
                    ## Jika Dari Pengiriman dan Pengiriman dipakai pada Faktur
                    if ($penjualan['pengiriman_penjualan_detail_id'] != null
                        and $penjualan['faktur_penjualan_detail_id'] != null ){

                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('pengiriman_penjualan_detail_id',$penjualan['pengiriman_penjualan_detail_id'])
                            ->where('is_inventory',1)->get()->pluck(['jurnal_detail_id'])->toArray();
                        $data_jurnal_persediaan = $params['repo_jurnal_detail']->whereIn('id',$id_jurnal)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            if ($djp->kode_akun == $params['barang']->akun_barang_terkirim){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['debet' => $stok_jurnal*$average_penjualan ]);
                            }elseif ($djp->kode_akun == $params['barang']->akun_persediaan){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['kredit' => $stok_jurnal*$average_penjualan ]);
                            }
                        }

                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('faktur_penjualan_detail_id',$penjualan['faktur_penjualan_detail_id'])
                            ->where('is_inventory',1)->get()->pluck(['jurnal_detail_id'])->toArray();

                        $data_jurnal_persediaan = $params['repo_jurnal_detail']->whereIn('id',$id_jurnal)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            if ($djp->kode_akun == $params['barang']->akun_hpp){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['debet' => $stok_jurnal*$average_penjualan ]);
                            }elseif ($djp->kode_akun == $params['barang']->akun_barang_terkirim){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['kredit' => $stok_jurnal*$average_penjualan ]);
                            }
                        }

                    }## Jika Dari Pengiriman dan Pengiriman Memiliki Pesanan dan Pengiriman Tidak di Pakai DiFaktur
                    elseif ($penjualan['pengiriman_penjualan_detail_id'] != null
                        and $penjualan['faktur_penjualan_detail_id'] == null ){
                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('pengiriman_penjualan_detail_id',$penjualan['pengiriman_penjualan_detail_id'])
                            #->where('is_inventory',1)
							->get()->pluck(['jurnal_detail_id'])->toArray();

                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('pengiriman_penjualan_detail_id',$penjualan['pengiriman_penjualan_detail_id'])
                            #->where('is_inventory',1)
							->get()->pluck(['jurnal_detail_id'])->toArray();

                        $data_jurnal_persediaan = $params['repo_jurnal_detail']->whereIn('id',$id_jurnal)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            if ($djp->kode_akun == $params['barang']->akun_barang_terkirim){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['debet' => $stok_jurnal*$average_penjualan ]);
                            }elseif ($djp->kode_akun == $params['barang']->akun_persediaan){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['kredit' => $stok_jurnal*$average_penjualan ]);
                            }
                        }
                    }## Jika Dari Faktur dan Tidak Memiliki Pengiriman
                    elseif ($penjualan['pengiriman_penjualan_detail_id'] == null
                        and $penjualan['faktur_penjualan_detail_id'] != null ){

                        $data_jurnal_persediaan = $params['repo_transaksi_jurnal_otomatis']
                            ->where('faktur_penjualan_detail_id',$penjualan['faktur_penjualan_detail_id'])
                            ->where('is_inventory',1)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            $isi_jurnal = $params['repo_jurnal_detail']->find($djp->jurnal_detail_id);
                            if ($isi_jurnal->kode_akun == $params['barang']->akun_hpp){
                                $isi_jurnal->debet = $nilai_jurnal;
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['debet' => $stok_jurnal*$average_penjualan ]);
                            }elseif ($isi_jurnal->kode_akun == $params['barang']->akun_persediaan){
                                $isi_jurnal->kredit = $nilai_jurnal;
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['kredit' => $stok_jurnal*$average_penjualan ]);
                            }
                            $isi_jurnal->save();
                        }
                    }
					## jika Dari Retur Pembelian
                    elseif ($penjualan['retur_pembelian_detail_id'] == null){

                        $data_jurnal_persediaan = $params['repo_transaksi_jurnal_otomatis']
                        ->where('retur_pembelian_detail_id',$penjualan['retur_pembelian_detail_id'])
                        ->where('is_inventory',1)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            $isi_jurnal = $params['repo_jurnal_detail']->find($djp->jurnal_detail_id);
                            if ($isi_jurnal->kode_akun == $params['barang']->akun_hpp){
                                $isi_jurnal->debet = abs($penjualan['total'] - $nilai_jurnal );
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['debet' => $stok_jurnal*$average_penjualan ]);
                            }elseif ($isi_jurnal->kode_akun == $params['barang']->akun_persediaan){
                                $isi_jurnal->kredit = $nilai_jurnal;
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['kredit' => $stok_jurnal*$average_penjualan ]);
                            }
                            $isi_jurnal->save();
                        }
                    }

                    $stok_awal = $stok_awal - $penjualan['kuantitas_butuh'];
                    $kuantitas_akhir   = 0;
                    $tanggal_penjualan_akhir = $penjualan['tanggal'];

                    $x++;

                }
				
                if ($cek_minus == true and $total_pembelian > 0){
					
                    $y = 0;
                    while ($y < $total_pembelian){
						
                        $pembelian = $list_pembelian[$y];
                        ## Filter Tanggal Untuk Average
                        if (!in_array($pembelian['id'],$id_terpakai)){
							#return ('stok_awal = '.$stok_awal .' ditambah pembelian = '. $pembelian['kuantitas']);
                            $average_awal = $average = (($average_awal * $stok_awal) + ($pembelian['kuantitas'] * $pembelian['harga'])) /
                                ($stok_awal + $pembelian['kuantitas']);
                            #return $stok_awal += $pembelian['kuantitas'];

                            $params['repo_harga_avg']
                                ->where('id', $pembelian['id'])
                                ->update([
                                    'average'           => $average,
                                    'kuantitas_stok'    => $stok_awal,
                                    'jurnal_persediaan' => $pembelian['kuantitas']*$pembelian['harga']
                                ]);
                        }
						#return $list_pembelian;
                        $y++;
                    }
					#return 'Loop Pembelian Cek Tanggal Waktu vvv';
                }
            }
            ## Jika Metode Akuntansi FIFO
            elseif ($params['metode'] == 'FIFO'){
                $x = 0;
                while ($x < count($list_penjualan)) {
                    $stok_jurnal = 0;
                    $penjualan = $list_penjualan[$x];
                    $kuantitas_butuh = $penjualan['kuantitas_butuh'];
                    $kuantitas_sebelum_cukup = 0;
                    $jurnal_persediaan = 0;

                    ## Apakah Jumlah Kuantitas Butuh sudah cukup
                    if ($kuantitas_akhir < $kuantitas_butuh) {
                        $catat_detail = true;

                        ## Mulai Looping Pembelian
                        $y = 0;
                        while ($y < $total_pembelian) {
                            if (array_key_exists($y, $list_pembelian)) {
                                $pembelian = $list_pembelian[$y];

                                ## Jika Kebutuhan Penjualan Mencukupi Dan Tanggal Pembelian Lebih Dari Tanggal Penjualan
                                if ($catat_detail == false and $pembelian['tanggal'] >= $penjualan['tanggal']){
                                    break;
                                }

                                $kuantitas_akhir += $pembelian['kuantitas_tersedia'];
                                if ($catat_detail == true){
                                    ## Apakah Kuantitas Dari Pembelian Memenuhi Kebutuhan Penjualan
                                    if ($kuantitas_akhir < $kuantitas_butuh) {
                                        $kuantitas_sebelum_cukup = $kuantitas_akhir;
                                        $stok_di_pakai = $pembelian['kuantitas_tersedia'];

                                    }
                                    elseif ($kuantitas_akhir >= $kuantitas_butuh) {
                                        $catat_detail = false;
                                        $stok_di_pakai =  $kuantitas_butuh - $kuantitas_sebelum_cukup;
                                        $list_pembelian[$y]['kuantitas_tersedia'] = $pembelian['kuantitas_tersedia'] - $stok_di_pakai;
                                    }

                                    $stok_tersisa = $pembelian['kuantitas_tersedia'] - $stok_di_pakai;
                                    $list_pembelian[$y]['kuantitas_terpakai'] = $stok_di_pakai;

                                    ## Apakah Kuantitas Tersedia Sudah 0
                                    if ($stok_tersisa == 0){
                                        unset($list_pembelian[$y]);
                                    }

                                    ## Catat Detail Penjualan
                                    $params['repo_harga_avg_detail']->create([
                                        'stok_id' => $penjualan['id'],
                                        'stok_asal_id' => $pembelian['id'],
                                        'kuantitas' => $stok_di_pakai
                                    ]);
                                }
                                elseif ($catat_detail == false){
                                    $stok_di_pakai = 0;
                                }

                                ## jumlah Stok Untuk Jurnal Pesediaan Penjualan
                                $stok_jurnal += $stok_di_pakai;
                                $jurnal_persediaan += $stok_di_pakai * $pembelian['harga'];

                            }
                            $y++;
                            $id_terpakai[] = $pembelian['id'];
                        }
                    }else{
                        $kuantitas_akhir = 0;
                        $x = -1;
                    }

                    $list_pembelian = array_values($list_pembelian);
                    $total_pembelian = count($list_pembelian);

                    ## Update JUrnal Persediaan Penjualan
                    $params['repo_harga_avg']
                    ->where('id',$penjualan['id'])
                    ->update([
                        'average'			 => $average,
                        'kuantitas_stok'	 => $kuantitas_butuh,#$stok_penjualan,
                        'jurnal_persediaan'	 => $jurnal_persediaan,
                    ]);

                    if ($penjualan['pengiriman_penjualan_detail_id'] != null
                        #and $penjualan['pesanan_penjualan_detail_id'] != null
                        and $penjualan['faktur_penjualan_detail_id'] != null ){

                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('pengiriman_penjualan_detail_id',$penjualan['pengiriman_penjualan_detail_id'])
                            ->where('is_inventory',1)->get()->pluck(['jurnal_detail_id'])->toArray();
                        $data_jurnal_persediaan = $params['repo_jurnal_detail']->whereIn('id',$id_jurnal)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            if ($djp->kode_akun == $params['barang']->akun_barang_terkirim){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['debet' => $jurnal_persediaan ]);
                            }elseif ($djp->kode_akun == $params['barang']->akun_persediaan){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['kredit' => $jurnal_persediaan ]);
                            }
                        }

                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('faktur_penjualan_detail_id',$penjualan['faktur_penjualan_detail_id'])
                            ->where('is_inventory',1)->get()->pluck(['jurnal_detail_id'])->toArray();

                        $data_jurnal_persediaan = $params['repo_jurnal_detail']->whereIn('id',$id_jurnal)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            if ($djp->kode_akun == $params['barang']->akun_hpp){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['debet' => $jurnal_persediaan ]);
                            }elseif ($djp->kode_akun == $params['barang']->akun_barang_terkirim){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['kredit' => $jurnal_persediaan ]);
                            }
                        }

                    }## Jika Dari Pengiriman dan Pengiriman Memiliki Pesanan dan Pengiriman Tidak di Pakai DiFaktur
                    elseif ($penjualan['pengiriman_penjualan_detail_id'] != null
                        #and $penjualan['pesanan_penjualan_detail_id'] != null
                        and $penjualan['faktur_penjualan_detail_id'] == null ){
                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('pengiriman_penjualan_detail_id',$penjualan['pengiriman_penjualan_detail_id'])
                            ->where('is_inventory',1)->get()->pluck(['jurnal_detail_id'])->toArray();

                        $id_jurnal = $params['repo_transaksi_jurnal_otomatis']
                            ->where('pengiriman_penjualan_detail_id',$penjualan['pengiriman_penjualan_detail_id'])
                            ->where('is_inventory',1)->get()->pluck(['jurnal_detail_id'])->toArray();

                        $data_jurnal_persediaan = $params['repo_jurnal_detail']->whereIn('id',$id_jurnal)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            if ($djp->kode_akun == $params['barang']->akun_barang_terkirim){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['debet' => $jurnal_persediaan ]);
                            }elseif ($djp->kode_akun == $params['barang']->akun_persediaan){
                                $params['repo_jurnal_detail']->where('id',$djp->id)
                                    ->update(['kredit' => $jurnal_persediaan ]);
                            }
                        }
                    }## Jika Dari Faktur dan Tidak Memiliki Pengiriman
                    elseif ($penjualan['pengiriman_penjualan_detail_id'] == null
                        and $penjualan['faktur_penjualan_detail_id'] != null ){

                        $data_jurnal_persediaan = $params['repo_transaksi_jurnal_otomatis']
                            ->where('faktur_penjualan_detail_id',$penjualan['faktur_penjualan_detail_id'])
                            ->where('is_inventory',1)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            $isi_jurnal = $params['repo_jurnal_detail']->find($djp->jurnal_detail_id);
                            if ($isi_jurnal->kode_akun == $params['barang']->akun_hpp){
                                $isi_jurnal->debet = $jurnal_persediaan;
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['debet' => $stok_jurnal*$average_penjualan ]);
                            }elseif ($isi_jurnal->kode_akun == $params['barang']->akun_persediaan){
                                $isi_jurnal->kredit = $jurnal_persediaan;
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['kredit' => $stok_jurnal*$average_penjualan ]);
                            }
                            $isi_jurnal->save();
                        }
                    }
                    elseif ($penjualan['retur_pembelian_detail_id'] == null){

                        $data_jurnal_persediaan = $params['repo_transaksi_jurnal_otomatis']
                            ->where('retur_pembelian_detail_id',$penjualan['retur_pembelian_detail_id'])
                            ->where('is_inventory',1)->get();

                        foreach ($data_jurnal_persediaan as $item => $djp) {
                            $isi_jurnal = $params['repo_jurnal_detail']->find($djp->jurnal_detail_id);
                            if ($isi_jurnal->kode_akun == $params['barang']->akun_hpp){
                                $isi_jurnal->debet = abs($penjualan['total'] - $jurnal_persediaan );
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['debet' => $stok_jurnal*$average_penjualan ]);
                            }elseif ($isi_jurnal->kode_akun == $params['barang']->akun_persediaan){
                                $isi_jurnal->kredit = $jurnal_persediaan;
                                #$params['repo_jurnal_detail']->where('id',$djp->id)
                                #->update(['kredit' => $stok_jurnal*$average_penjualan ]);
                            }
                            $isi_jurnal->save();
                        }
                    }

                    #$stok_awal = $stok_awal - $penjualan['kuantitas_butuh'];
                    $kuantitas_akhir   = 0;

                    $x++;

                }
            }

		} catch (Exception $e) {
			return $e->getMessage();
		}
		#return 'Sampe akhir';
        return $hasil;
	}

	public function cekAverageSetelah()
	{
		
	}

    public function buatJurnalPersediaan($parameter)
    {
        foreach ($parameter['data'] as $datum => $data) {
            if ($data['tipe'] == 'debet'){
                $lawan = 'kredit';
            }elseif ($data['tipe'] == 'kredit'){
                $lawan = 'debet';
            }
            $jurnal_detail = $parameter['utama']['repo_jurnal_detail']->create([
                'header_id' => $parameter['utama']['jurnal_header_id'],
                'kode_akun' => $data['akun'],
                $data['tipe'] => $parameter['nilai'],
                $lawan        => 0
            ]);
            ## Catat Transaksi Jurnal
            $parameter['utama']['repo_transaksi_jurnal_otomatis']->create([
                'jurnal_header_id'               => $parameter['utama']['jurnal_header_id'],
                'jurnal_detail_id'               => $jurnal_detail->id,
                $data['transaksi']               => $data['id_transaksi'],
                'is_inventory'                   => 1

            ]);
        }
    }

}
