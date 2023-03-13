<?php

namespace App\Http\Controllers\Litbang;

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

class LayananIncubatorController extends APIController
{
    private $LayananIncubatorRepository;
    private $JenisLayananIncubatorRepository;
    private $PelaksanaLayananIncubatorRepository;

    private $IndikatorAwalInkubatorRepository;
    private $IndikatorAkhirInkubatorRepository;
    private $FileIndikatorInkubatorRepository;
    //private $PenggunaRepository;

    public function initialize()
    {
        $this->LayananIncubatorRepository = \App::make('\App\Repositories\Contracts\Litbang\LayananIncubatorInterface');
        $this->JenisLayananIncubatorRepository = \App::make('\App\Repositories\Contracts\Litbang\JenisLayananIncubatorInterface');

        $this->IndikatorAwalInkubatorRepository = \App::make('\App\Repositories\Contracts\Litbang\IndikatorAwalInkubatorInterface');
        $this->IndikatorAkhirInkubatorRepository = \App::make('\App\Repositories\Contracts\Litbang\IndikatorAkhirInkubatorInterface');
        $this->FileIndikatorInkubatorRepository = \App::make('\App\Repositories\Contracts\Litbang\FileIndikatorInkubatorInterface');
    }

    public function list(Request $request)
    {
        $relations = [
        ];
        $result = $this->LayananIncubatorRepository
            ->relation($relations)
            ->get();
        return $this->respond($result);

    }

    public function listWithDatatable(Request $request)
    {
        $relations = [

        ];
        return $datatable = datatables()->of($this->LayananIncubatorRepository
            ->relation($relations)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })

            ->addColumn('action', function ($data) {
                $btn_edit   =  '#';
                    //"add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = '#';
                    //"destroy(".$data['id'].", '".$data['nomor']."','pembelian/faktur-pembelian','tbl_pembelian_faktur_pembelian')";

                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="/layanan-incubator-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteLayananIncubator('.$data['id'].')">
                                          <a href="javascript:;" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->toJson();
    }

    public function listWithDatatableByTanggal(Request $request)
    {
        $relations = [

        ];
        return $datatable = datatables()->of($this->LayananIncubatorRepository
            ->relation($relations)
            ->where('tanggal','>=',$request->tanggal_awal)
            ->where('tanggal','<=',$request->tanggal_akhir)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })

            ->addColumn('action', function ($data) {
                $btn_edit   =  '#';
                //"add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = '#';
                //"destroy(".$data['id'].", '".$data['nomor']."','pembelian/faktur-pembelian','tbl_pembelian_faktur_pembelian')";

                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="/layanan-incubator-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteLayananIncubator('.$data['id'].')">
                                          <a href="javascript:;" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-trash"></i></span>
                                                  <span class="navi-text">Hapus</span>
                                          </a>
                                  </li>
                          </ul>
                          </div>
                      </div>
                    ';

            })
            ->toJson();
    }

    public function getById(Request $request)
    {
        $result = $this->LayananIncubatorRepository->with(['attachment','indikator_awal','indikator_akhir','file_indikator'])->find($request->id);
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_GET_FAILED_MSG);
        }
    }

    public function getActivity(Request $request)
    {
        $log_detail = [];
        $result['log_detail'] = [];
        $result = $this->LayananIncubatorRepository->find($request->id);
        $result['log'] = Activity::where('log_name','Departemen')
            ->where('subject_id',$request->id)->orderBy('id','desc')->get();

        $properti_baru = [];
        $new_detail = [];
        $log_detail_baru = [];
        // return $result;
        foreach ($result['log'] as $key => $value) {
            $result['log'][$key]['oleh'] = $this->PenggunaRepository
                ->find($result['log'][$key]['causer_id'])->full_name;
            $properti_baru = [];
            if ($value['description'] == 'updated') {
                // Old Attributes
                $properti_baru = [];

                if (isset($result['log'][$key]['properties']['old']['kode'])) {
                    $properti_baru['Kode'] = $result['log'][$key]['properties']['old']['kode'];
                }
                if (isset($result['log'][$key]['properties']['old']['keterangan'])) {
                    $properti_baru['Keterangan']   = $result['log'][$key]['properties']['old']['keterangan'];
                }

                $result['log'][$key]['old'] = $properti_baru;
                // End Old

                // New Attributes

                if (isset($result['log'][$key]['properties']['attributes']['kode'])) {
                    $properti_baru['Kode'] = $result['log'][$key]['properties']['attributes']['kode'];
                }
                if (isset($result['log'][$key]['properties']['attributes']['keterangan'])) {
                    $properti_baru['Keterangan']   = $result['log'][$key]['properties']['attributes']['keterangan'];
                }

                $result['log'][$key]['new'] = $properti_baru;
                // End New

            }else {

                // New Attributes
                $properti_baru = [];

                $properti_baru['Kode'] = $result['log'][$key]['properties']['attributes']['kode'];
                $properti_baru['Keterangan']   = $result['log'][$key]['properties']['attributes']['keterangan'];

                $result['log'][$key]['new'] = $properti_baru;
                // End New
            }
        }

        $result['show_properties'] = ['Harga Jasa','kuantitas','Harga','Kode Pajak'];

        return $this->respond($result);
    }

    public function create(Request $request)
    {

        $validator = $this->LayananIncubatorRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->LayananIncubatorRepository->create(
                [
                    'nomor'    =>  $request->nama,
                    'tanggal'  => $request->tanggal,
                    'nomor_surat' => $request->nomor_surat,
                    'file_surat'   => $request->file_surat,
                    'layanan'  =>  $request->layanan,
                    'pengguna_layanan'    =>  $request->pengguna_layanan,
                    'instansi' => $request->instansi,
                    'nomor_pengaju'   => $request->nomor_pengaju,
                    'email_pengaju'  =>  $request->email_pengaju,
                    'ide_gagasan'  =>  $request->ide_gagasan,
                    'latar_belakang' => $request->latar_belakang,

                    ## Profil
                    'dasar_hukum'    =>  $request->dasar_hukum,
                    'permasalahan'  => $request->permasalahan,
                    'isu_strategis' => $request->isu_strategis,
                    'metode'   => $request->metode,
                    'keunggulan'  =>  $request->keunggulan,
                    'cara_kerja'    =>  $request->cara_kerja,
                    'tujuan' => $request->tujuan,
                    'manfaat'   => $request->manfaat,
                    'sdgs'  =>  $request->kaitan_dengan_sdgs,
                    'proses'  =>  $request->proses,
                    'kecepatan'  =>  $request->kecepatan,
                    'hasil'  =>  $request->hasil,
                ]
            );
            if ($result->count()) {

                if($request->nindikator == 1){
                    $this->IndikatorAwalInkubatorRepository->create([
                        'layanan_incubator_id' => $result->id,
                        'regulasi' => $request->regulasi,
                        'sdm' => $request->ketersediaan_sdm,
                        'anggaran' => $request->dukungan_anggaran,
                        'bimtek' => $request->bimtek,
                        'program' => $request->program_kegiatan,
                        'aktor' => $request->keterlibatan_aktor,
                        'pelaksana' => $request->pelaksana,
                        'jejaring' => $request->jejaring,
                        'sosialisasi' => $request->sosialisasi,
                        'pedoman' => $request->pedoman_teknis,
                        'informasi' => $request->kemudahan_informasi,
                        'penciptaan' => $request->kecepatan_penciptaan,
                        'proses' => $request->proses,
                        'layanan' => $request->penyelesaian_layanan,
                        'online' => $request->online_sistem,
                        'replikasi' => $request->replikasi,
                        'it' => $request->penggunaan_it,
                        'kemanfaatan' => $request->kemanfaatan,
                        'monitoring' => $request->monitoring_evaluasi,
                        'kualitas' => $request->kualitas
                    ]);

                    $this->IndikatorAkhirInkubatorRepository->create([
                        'layanan_incubator_id' => $result->id,
                        'regulasi' => 0,
                        'sdm' => 0,
                        'anggaran' => 0,
                        'bimtek' => 0,
                        'program' => 0,
                        'aktor' => 0,
                        'pelaksana' => 0,
                        'jejaring' => 0,
                        'sosialisasi' => 0,
                        'pedoman' => 0,
                        'informasi' => 0,
                        'penciptaan' => 0,
                        'proses' => 0,
                        'layanan' => 0,
                        'online' => 0,
                        'replikasi' => 0,
                        'it' => 0,
                        'kemanfaatan' => 0,
                        'monitoring' => 0,
                        'kualitas' => 0
                    ]);

                    $this->FileIndikatorInkubatorRepository->create([
                        'layanan_incubator_id' => $result->id,
                        'regulasi' => $request->indikator['regulasi'],
                        'sdm' => $request->indikator['sdm'],
                        'anggaran' => $request->indikator['anggaran'],
                        'bimtek' => $request->indikator['bimtek'],
                        'program' => $request->indikator['program'],
                        'aktor' => $request->indikator['aktor'],
                        'pelaksana' => $request->indikator['pelaksana'],
                        'jejaring' => $request->indikator['jejaring'],
                        'sosialisasi' => $request->indikator['sosialisasi'],
                        'pedoman' => $request->indikator['pedoman'],
                        'informasi' => $request->indikator['informasi'],
                        'penciptaan' => $request->indikator['penciptaan'],
                        'proses' => $request->indikator['proses'],
                        'layanan' => $request->indikator['penyelesaian'],
                        'online' => $request->indikator['online'],
                        'replikasi' => $request->indikator['replikasi'],
                        'it' => $request->indikator['it'],
                        'kemanfaatan' => $request->indikator['kemanfaatan'],
                        'monitoring' => $request->indikator['monitoring'],
                        'kualitas' => $request->indikator['kualitas']
                    ]);
                }
                DB::commit();
                return $this->respondCreated($result, 'Pengajuan Layanan Berhasil');
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {

        $validator = $this->LayananIncubatorRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->LayananIncubatorRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'status' =>  $request->status,
                    ]
                );
            if ($result) {
                DB::commit();
                return $this->respondCreated($result, 'Pengajuan Layanan Diupdate!');
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->LayananIncubatorRepository->delete($request->id);
        if ($result) {
            return $this->respondOk(MessageConstant::AGENDA_DELETE_SUCCESS_MSG);
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_DELETE_FAILED_MSG);
        }
    }
}
