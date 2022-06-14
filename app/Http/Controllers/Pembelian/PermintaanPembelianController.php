<?php
namespace App\Http\Controllers\Pembelian;

use App\Helpers\MessageConstant;
use App\Http\Controllers\APIController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Auth;
use App\Repositories\MainRepository;


class PermintaanPembelianController extends APIController
{
	
	private $PermintaanBelumSelesaiRepository;
	private $PermintaanPembelianHeaderRepository;
	private $PermintaanPembelianDetailRepository;
	private $PesananPembelianDetailRepository;
	private $TransaksiSyaratPengirimanRepository;
	private $LogActivityRepository;
	private $PenggunaRepository;
	private $JadwalPengirimanRepository;
	private $PemasokRepository;
	private $HargaJasaRepository;


	public function initialize()
	{
		$this->PermintaanBelumSelesaiRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanBelumSelesaiInterface');
		$this->PermintaanPembelianHeaderRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianHeaderInterface');
		$this->PermintaanPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PermintaanPembelianDetailInterface');
		
		$this->PesananPembelianDetailRepository = \App::make('\App\Repositories\Contracts\Pembelian\PesananPembelianDetailInterface');
		$this->TransaksiSyaratPengirimanRepository = \App::make('\App\Repositories\Contracts\JasaEkspedisi\TransaksiSyaratPengirimanInterface');
		$this->LogActivityRepository = \App::make('\App\Repositories\Contracts\Pengaturan\LogActivityInterface');
		$this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
		$this->JadwalPengirimanRepository = \App::make('\App\Repositories\Contracts\MasterData\JadwalPengirimanInterface');
		$this->PemasokRepository = \App::make('\App\Repositories\Contracts\Pembelian\PemasokInterface');
		$this->HargaJasaRepository = \App::make('\App\Repositories\Contracts\MasterData\HargaJasaInterface');
	}

	public function list()
	{
		$relation = [
			'pemasok_id',
		];
		$result = $this->PermintaanPembelianHeaderRepository->relation($relation)->get();
		return $this->respond($result);
	}

    public function listWithDatatable(Request $request)
    {
        $relation = [
            'pemasok_id',
        ];
        return $datatable = datatables()->of($this->PermintaanPembelianHeaderRepository->relation($relation)->get())
            ->editColumn('tanggal', function ($list) {
                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
            })
            ->editColumn('pemasok', function ($list) {
                return $list['pemasok'] ? $list['pemasok']['nama'] : NULL;
            })
            ->editColumn('keterangan', function ($list) {
                return $list['keterangan'] ? '<span class="label  label-success label-inline " style="display: none"> '.$list['keterangan']. '</span>'.substr($list['keterangan'],0,50): NULL;
            })
            ->addColumn('action', function ($data) {
                $btn_edit   = "add_content_tab('pembelian_permintaan_pembelian','edit_data_".$data['id']."','pembelian/permintaan-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = "destroy(".$data['id'].", '".$data['nomor']."','pembelian/permintaan-pembelian','tbl_pembelian_permintaan_pembelian')";
                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="'.$btn_delete.'">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->removeColumn('pemasok_id')
            ->removeColumn('jadwal_pengiriman_id')
            ->removeColumn('jadwal_pengiriman')
            ->removeColumn('detail')
            ->removeColumn('details_data')
            ->rawColumns(['tanggal','keterangan','action'])
            ->toJson();

    }

	public function listByTanggal(Request $request)
	{
		$relation = [
			//'detail.harga_pembelian_pengiriman',
			'pemasok_id',
			'jadwal_pengiriman_id',
			//'transaksi_syarat_pengiriman'
		];
		$result = $this->PermintaanPembelianHeaderRepository
		->relation($relation)
		->where('tanggal','>=',$request->tgl_awal)
		->where('tanggal','<=',$request->tgl_akhir)
		->get();
		return $this->respond($result);
	}

    public function listByTanggalWithDatatable(Request $request)
    {
        $relation = [
            'pemasok_id',
        ];
        return $datatable = datatables()->of($this->PermintaanPembelianHeaderRepository
            ->relation($relation)
            ->where('tanggal','>=',$request->tgl_awal)
            ->where('tanggal','<=',$request->tgl_akhir)
            ->get())
            ->editColumn('tanggal', function ($list) {
                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
            })
            ->editColumn('pemasok', function ($list) {
                return $list['pemasok'] ? $list['pemasok']['nama'] : NULL;
            })
            ->editColumn('keterangan', function ($list) {
                return $list['keterangan'] ? '<span class="label  label-success label-inline " style="display: none"> '.$list['keterangan']. '</span>'.substr($list['keterangan'],0,50): NULL;
            })
            ->addColumn('action', function ($data) {
                $btn_edit   = "add_content_tab('pembelian_permintaan_pembelian','edit_data_".$data['id']."','pembelian/permintaan-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = "destroy(".$data['id'].", '".$data['nomor']."','pembelian/permintaan-pembelian','tbl_pembelian_permintaan_pembelian')";
                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="'.$btn_delete.'">
                                          <a href="#" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->removeColumn('pemasok_id')
//            ->removeColumn('jadwal_pengiriman_id')
//            ->removeColumn('jadwal_pengiriman')
//            ->removeColumn('detail')
//            ->removeColumn('details_data')
            ->rawColumns(['tanggal','keterangan','action'])
            ->toJson();

    }

	public function getActivity(Request $request)
	{
		$relation = [
			//'detail.harga_pembelian_pengiriman',
			'pemasok_id',
			'jadwal_pengiriman_id',
			//'transaksi_syarat_pengiriman'
		];
		$log_detail = [];
		$result['log_detail'] = [];
		$result = $this->PermintaanPembelianHeaderRepository->relation($relation)->find($request->id);
		$result['log_header'] = Activity::where('log_name','Permintaan Pembelian '.$request->id)
		->orderBy('id','desc')->get();
		$properti_baru = [];
		$new_detail = [];
		$log_detail_baru = [];
		
		#return $result;
		foreach ($result['log_header'] as $key => $value) {
			$result['log_header'][$key]['oleh'] = $this->PenggunaRepository->find($result['log_header'][$key]['causer_id'])->full_name;
			$properti_baru = [];
			if ($value['description'] == 'updated') {

				$result['log_header'][$key]['pemasok'] = $this->PemasokRepository
				->find($result['log_header'][$key]['properties']['old']['pemasok_id'])->nama;
				$properti_baru['pemasok'] = $result['log_header'][$key]['pemasok'];
				$properti_baru['tanggal'] = $result['log_header'][$key]['properties']['old']['tanggal'];
				$properti_baru['nomor']   = $result['log_header'][$key]['properties']['old']['nomor'];
				$result['log_header'][$key]['old'] = $properti_baru;
				// End Header Old

				#return $result;

				// Details Old Attributes
				$data_detail = [];
				#return $result['log_header'][$key]['properties']['old']['detail'];
				foreach ($result['log_header'][$key]['properties']['old']['detail'] as $key_detail => $detail) {
					$detail['Harga Jasa'] = $this->HargaJasaRepository->find($detail['harga_jasa_id'])->kode;		
					if (isset($detail['jadwal_pengiriman_id']) and $detail['jadwal_pengiriman_id'] != null) {
                        $detail['Jadwal'] = $this->JadwalPengirimanRepository->find($detail['jadwal_pengiriman_id'])->nomor;
                    }
					$data_detail[] = $detail;
				}
				#return 'xxx';
				$result['log_header'][$key]['old_detail'] =$data_detail;
				// End Old Detail

				// New Attributes
				
				$result['log_header'][$key]['pemasok'] = $this->PemasokRepository
				->find($result['log_header'][$key]['properties']['attributes']['pemasok_id'])->nama;
				$properti_baru['pemasok'] = $result['log_header'][$key]['pemasok'];
				$properti_baru['tanggal'] = $result['log_header'][$key]['properties']['attributes']['tanggal'];
				$properti_baru['nomor']   = $result['log_header'][$key]['properties']['attributes']['nomor'];
				$result['log_header'][$key]['new'] = $properti_baru;
				// End New

				//New Detail Attributes
				$data_detail = [];
				$index = 0;
				foreach ($result['log_header'][$key]['properties']['attributes']['detail'] as $key_detail => $detail) {
				 	$detail['Harga Jasa'] = $this->HargaJasaRepository->find($detail['harga_jasa_id'])->kode;	
				 	if (isset($detail['jadwal_pengiriman_id']) and $detail['jadwal_pengiriman_id'] != null) {
                        $detail['Jadwal'] = $this->JadwalPengirimanRepository->find($detail['jadwal_pengiriman_id'])->nomor;
                    }
					$data_detail[] = $detail;
				}

				$result['log_header'][$key]['new_detail'] =$data_detail;

				//End Detail Attributes

			}else {

				//Old
				$properti_baru['jadwal']  = '';
				$properti_baru['pemasok'] = '';
				$properti_baru['tanggal'] = '';
				$properti_baru['nomor']   = '';
				$result['log_header'][$key]['old'] = $properti_baru;
				// End Old

				// New Attributes
				// if ($result['log_header'][$key]['properties']['attributes']['jadwal_pengiriman_id']) {
				// 	$result['log_header'][$key]['jadwal'] = $this->JadwalPengirimanRepository
				// 	->find($result['log_header'][$key]['properties']['attributes']['jadwal_pengiriman_id']);
				// 	$properti_baru['jadwal']  = $result['log_header'][$key]['jadwal']->nomor;
				// }
				$result['log_header'][$key]['pemasok'] = $this->PemasokRepository
				->find($result['log_header'][$key]['properties']['attributes']['pemasok_id'])->nama;
				$properti_baru['pemasok'] = $result['log_header'][$key]['pemasok'];
				$properti_baru['tanggal'] = $result['log_header'][$key]['properties']['attributes']['tanggal'];
				$properti_baru['nomor']   = $result['log_header'][$key]['properties']['attributes']['nomor'];
				$result['log_header'][$key]['new'] = $properti_baru;
				// End New

				//New Detail Attributes
				$data_detail = [];
				$index = 0;
				foreach ($result['log_header'][$key]['properties']['attributes']['detail'] as $key_detail => $detail) {
				 	$detail['Harga Jasa'] = $this->HargaJasaRepository->find($detail['harga_jasa_id'])->kode;		
					$data_detail[] = $detail;
					if (isset($detail['jadwal_pengiriman_id']) and $detail['jadwal_pengiriman_id'] != null) {
                        $detail['Jadwal'] = $this->JadwalPengirimanRepository->find($detail['jadwal_pengiriman_id'])->nomor;
                    }
				}

				$result['log_header'][$key]['new_detail'] =$data_detail;

				//End Detail Attributes
			}	 
		}
		
		$result['show_properties'] = ['Harga Jasa','kuantitas','Jadwal'];
		
		return $this->respond($result);
	}

	public function list_by(Request $request)
	{
		$relation = [
			//'detail.harga_pembelian_pengiriman',
			'pemasok_id',
			'jadwal_pengiriman_id',
			//'transaksi_syarat_pengiriman'
		];
		$result = $this->PermintaanPembelianHeaderRepository->relation($relation)->where($request['type'],$request['id'])->get();
		return $this->respond($result);
	}

	public function permintaanPembelianBelumSelesai(Request $request)
	{
		$data = [];
		$result = $this->PermintaanBelumSelesaiRepository
		->where('pemasok_id',$request['id'])->groupBy('id')
		->with([
		    'jadwal_pengiriman_id',
            'harga_jasa_id',
            ])
		->get();
		return $this->respond($result);
	}

	public function permintaanPembelianBelumSelesaiDetail(Request $request)
	{
		ini_set('max_execution_time', 500000);
		$data = [];
		$result = $this->PermintaanBelumSelesaiRepository
		->with([
		    'syarat_pengiriman',
            'jadwal_pengiriman_id',
            'detail_pajak',
            'harga_jasa_id.paket_id',
            'harga_jasa_id.produk_servis_id',
            'harga_jasa_id.asal_id',
            'harga_jasa_id.tujuan_id',
        ])
		->where('id',$request['id'])->get();
		// foreach ($result as $key => $value) {
		// 	$result->detail[$value->id] = $this->PermintaanBelumSelesaiRepository
		// ->where('pemasok_id',$request['id'])->where('id',$value->id)->get();
		// }
		return $this->respond($result);
	}

	public function getById(Request $request)
	{
		$relation = [
			'pemasok_id:id,kode,nama,ketentuan_pembayaran_id',
			'pemasok_id.ketentuan_pembayaran',
			//'transaksi_syarat_pengiriman',
			'detail.jadwal_pengiriman_id',
			'detail.syarat_pengiriman',
			'detail.harga_jasa_id.paket_id',
            'detail.harga_jasa_id.produk_servis_id',
            'detail.harga_jasa_id.asal_id',
            'detail.harga_jasa_id.tujuan_id',
		];
		$result = $this->PermintaanPembelianHeaderRepository->get_relation($relation, $request->id)->first();
		if ($result) {
			return $this->respond($result);
		} else {
			return $this->respondNotFound(MessageConstant::PERMINTAAN_PEMBELIAN_GET_FAILED_MSG);
		}
	}

	public function getByPemasok(Request $request)
	{
		$relation = [
			'pemasok_id:id,kode,nama,ketentuan_pembayaran_id',
			'pemasok_id.ketentuan_pembayaran',
			//'transaksi_syarat_pengiriman'
		];
		$result = $this->PermintaanPembelianHeaderRepository->relation($relation)
		->where('pemasok_id',$request->pemasok_id)
		->get();
		if ($result) {
			return $this->respond($result);
		} else {
			return $this->respondNotFound(MessageConstant::PERMINTAAN_PEMBELIAN_GET_FAILED_MSG);
		}
	}

	public function create(Request $request)
	{	
		$details_id = [];
		DB::beginTransaction();
		try{

            #return $this->respondInternalError($err = null, $request->nomor);
			$validator_header = null;
			if ($this->cekValidType($request->detail) === true) {
				$validator_header = $this->PermintaanPembelianHeaderRepository->validate($request);
			}else{
				$validator_header = $this->PermintaanPembelianHeaderRepository->validateNonJadwal($request);
			}
			$messages = '';
			if ($validator_header->fails()) {
				return $this->respondInternalError(null,implode(',',array_keys($validator_header->messages()->get('*'))).' '.MessageConstant::VALIDATION_REQUIRED_MSG);
			} else {

				$result = MainRepository::createData([
					'model' => 'permintaan pembelian',
					'part' => 'header',
					'data' => $request
				]);
				
				if(isset($request->detail)) {
					foreach ($request->detail as $key => $details) {
						$data_detail = new Request($details);
						$harga_jasa = $this->HargaJasaRepository->find($details['harga_jasa_id']);
						if ($harga_jasa->tipe == 3) {
							$validator_detail = $this->PermintaanPembelianDetailRepository->validateWithJadwal($data_detail);
						}else{
							$validator_detail = $this->PermintaanPembelianDetailRepository->validate($data_detail);
						}
						if ($validator_detail->fails()) {
							return $this->respondInternalError(null,implode(',',array_keys($validator_detail->messages()->get('*'))).' '.MessageConstant::VALIDATION_REQUIRED_MSG);
							return $this->respondWithValidationErrors($validator_detail->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
						} else {
							$details['header_id'] = $result->id;
							$detail_permintaan = MainRepository::createData([
								'model' => 'permintaan pembelian',
								'part' => 'detail',
								'data' => $details
							]);
							
							$details_id[] = $detail_permintaan->id;
							if ($harga_jasa->tipe == 3) {
								if (isset($request->syarat_pengiriman[$harga_jasa->id])) {
									$this->PermintaanPembelianHeaderRepository->createSyaratPengiriman($request->syarat_pengiriman[$harga_jasa->id],'permintaan_pembelian_detail_id',$detail_permintaan->id,$this->TransaksiSyaratPengirimanRepository);
								}
							}
								
						// End Create Detail
						}
					// End Looping Detail
					}
				// End Jika Ada Detail
				}
			// End Create Permintaan Pembelian
			}

			$activity = activity()
			->useLog('Permintaan Pembelian '.$result->id)
			#->causedBy(Auth::user()->id)
			->performedOn(new \App\Models\Pembelian\PermintaanPembelianHeader)
			->withProperties(['attributes' => $request])
			->log('created');

			DB::commit();
			return $this->respondCreated($result, MessageConstant::PERMINTAAN_PEMBELIAN_CREATE_SUCCESS_MSG);
		} catch (\Exception $e) {
			DB::rollback();
			return $this->respondInternalError($e->getMessage(), MessageConstant::PERMINTAAN_PEMBELIAN_CREATE_FAILED_MSG);
		}
	}

	public function update(Request $request)
	{
		DB::beginTransaction();
		try{
			$validator_header = null;
			if ($this->cekValidType($request->detail) === true) {
				$validator_header = $this->PermintaanPembelianHeaderRepository->validate_update($request);
			}else{
				$validator_header = $this->PermintaanPembelianHeaderRepository->validate_updateNonJadwal($request);
			}
			if ($validator_header->fails()) {
				return $this->respondInternalError(null,implode(',',array_keys($validator_header->messages()->get('*'))).' '.MessageConstant::VALIDATION_REQUIRED_MSG);
			} else {
				$result = $this->PermintaanPembelianHeaderRepository->find($request['id']);
				if ($this->checkUsed($request->id)) {
					return $this->respondInternalError($errors = null, $message = MessageConstant::PERMINTAAN_PEMBELIAN_USED_UPDATE_MSG);
			 	}
			 	$old = $result;
					$details_id = [];
				if ($result) {
					$result->nomor						  = $request->nomor;
					$result->tanggal				 	  = $request->tanggal;
					$result->pemasok_id         		  = $request->pemasok_id;
					$result->jadwal_pengiriman_id         = $request->jadwal_pengiriman_id;
					$result->keterangan         = $request->keterangan;
					$result->save();
					//activity()->disableLogging();
					//$result->transaksi_syarat_pengiriman->each->delete();
					//activity()->enableLogging();
					if(isset($request->detail)) {
						//activity()->disableLogging();
						$result->detail->each->delete();
						//activity()->enableLogging();
						foreach ($request->detail as $key => $details) {
							$data_detail = new Request($details);
							$harga_jasa = $this->HargaJasaRepository->find($details['harga_jasa_id']);
							if ($harga_jasa->tipe == 3) {
								$validator_detail = $this->PermintaanPembelianDetailRepository->validateWithJadwal($data_detail);
							}else{
								$validator_detail = $this->PermintaanPembelianDetailRepository->validate($data_detail);
							}
							
							if ($validator_detail->fails()) {
								return $this->respondInternalError(null,implode(',',array_keys($validator_detail->messages()->get('*'))).' '.MessageConstant::VALIDATION_REQUIRED_MSG);
								return $this->respondWithValidationErrors($validator_detail->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
							} else {
								
								$details['header_id'] = $result->id;
								$detail_permintaan = MainRepository::createData([
									'model' => 'permintaan pembelian',
									'part' => 'detail',
									'data' => $details
								]);
								
								$details_id [] = $detail_permintaan->id;
								if ($harga_jasa->tipe == 3) {
									if (isset($request->syarat_pengiriman[$harga_jasa->id])) {
										$this->PermintaanPembelianHeaderRepository->createSyaratPengiriman($request->syarat_pengiriman[$harga_jasa->id],'permintaan_pembelian_detail_id',$detail_permintaan->id,$this->TransaksiSyaratPengirimanRepository);
									}
								}
								
							}
						}
						
					} else {
						//activity()->disableLogging();
						$result->detail->each->delete();
						///activity()->enableLogging();
					}

					$activity = activity()
					//->app($this)
					//->causedBy(JWTAuth::parseToken()->authenticate()->id)
					->useLog('Permintaan Pembelian '.$result->id)
					//->performedOn(new \App\Models\Accounting\Activa)
					->withProperties(['old'=>$old,'attributes'=>$request])
					->log('updated');
				} else {
					return $this->respondNotFound();
				}
			}
			DB::commit();
			return $this->respondCreated($result, MessageConstant::PERMINTAAN_PEMBELIAN_UPDATE_SUCCESS_MSG);
		} catch (\Exception $e) {
			DB::rollback();
			return $this->respondInternalError($e->getMessage(), MessageConstant::PERMINTAAN_PEMBELIAN_UPDATE_FAILED_MSG);
		}
	}

	public function delete(Request $request)
	{	
	 	if ($this->checkUsed($request->id)) {
			return $this->respondInternalError($errors = null, $message = MessageConstant::PERMINTAAN_PEMBELIAN_USED_UPDATE_MSG);
	 	}
		$result = $this->PermintaanPembelianHeaderRepository->delete($request->id);
		if ($result) {	
			return $this->respondOk(MessageConstant::PERMINTAAN_PEMBELIAN_DELETE_SUCCESS_MSG);
		} else {
			return $this->respondNotFound(MessageConstant::PERMINTAAN_PEMBELIAN_DELETE_FAILED_MSG);
		}
	}

	public function checkUsed($id)
	{
		$status_used = false;
		$result = $this->PermintaanPembelianDetailRepository
		->where('permintaan_pembelian_header_id',$id)
		->get();
		foreach ($result as $key => $value) {
			$used = $this->PesananPembelianDetailRepository
			->where('permintaan_pembelian_detail_id',$value['id'])
			->get();
			if (count($used) > 0) {
				$status_used = true;
			}
		}
		return $status_used;
	}

	public function cekValidType($detail)
	{
		$with_jadwal = false;
		foreach ($detail as $key => $value) {
			$tipe = $this->HargaJasaRepository->find($value['harga_jasa_id'])->tipe;
			if ($tipe == 3) {
				$with_jadwal = true;
			}
		}
		return $with_jadwal;
	}

    public function getNumbering()
    {
        $kode = MainRepository::numberKodeGenerator([
            'repo'  => $this->PermintaanPembelianHeaderRepository,
            'modul' => MessageConstant::PENOMORAN_PERMINTAAN_PEMBELIAN
        ]);
        return $this->respond($kode);
    }
}

