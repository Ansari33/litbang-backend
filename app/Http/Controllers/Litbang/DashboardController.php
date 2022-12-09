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

class DashboardController extends APIController
{
    private $AgendaRepository;
    private $BeritaRepository;
    private $KelitbanganRepository;
    private $InovasiRepository;
    private $UsulanPenelitianRepository;
    private $UsulanInovasiRepository;
    private $SurveyRepository;
    private $SuratMasukRepository;
    private $SuratKeluarRepository;
    private $JenisSuratRepository;
    private $RegulasiRepository;
    private $SuratRekomendasiRepository;

    public function initialize()
    {
        $this->AgendaRepository = \App::make('\App\Repositories\Contracts\Litbang\AgendaInterface');
        $this->BeritaRepository = \App::make('\App\Repositories\Contracts\Litbang\BeritaInterface');
        $this->InovasiRepository = \App::make('\App\Repositories\Contracts\Litbang\InovasiInterface');
        $this->KelitbanganRepository = \App::make('\App\Repositories\Contracts\Litbang\KelitbanganInterface');
        $this->RegulasiRepository = \App::make('\App\Repositories\Contracts\Litbang\RegulasiInterface');
        $this->SuratKeluarRepository = \App::make('\App\Repositories\Contracts\Litbang\SuratKeluarInterface');
        $this->SuratMasukRepository = \App::make('\App\Repositories\Contracts\Litbang\SuratMasukInterface');
        $this->JenisSuratRepository = \App::make('\App\Repositories\Contracts\Litbang\JenisSuratInterface');
        $this->SuratRekomendasiRepository = \App::make('\App\Repositories\Contracts\Litbang\SuratRekomendasiInterface');
        $this->SurveyRepository = \App::make('\App\Repositories\Contracts\Litbang\SurveyInterface');
        $this->UsulanInovasiRepository = \App::make('\App\Repositories\Contracts\Litbang\UsulanInovasiInterface');
        $this->UsulanPenelitianRepository = \App::make('\App\Repositories\Contracts\Litbang\UsulanPenelitianInterface');

    }

    public function list(Request $request)
    {
        $relations = [
        ];
        $result = [];
        $result['agenda'] = $this->AgendaRepository->relation($relations)->get()->count();
        $result['berita'] = $this->BeritaRepository->relation($relations)->get()->count();
        $result['inovasi'] = $this->InovasiRepository->relation($relations)->get()->count();
        $result['kelitbangan'] = $this->KelitbanganRepository->relation($relations)->get()->count();
        $result['regulasi'] = $this->RegulasiRepository->relation($relations)->get()->count();
        $result['surat_masuk'] = $this->SuratMasukRepository->relation($relations)->get()->count();
        $result['surat_keluar'] = $this->SuratKeluarRepository->relation($relations)->get()->count();
        $result['jenis_surat'] = $this->JenisSuratRepository->relation($relations)->get()->count();
        $result['usulan_inovasi'] = $this->UsulanInovasiRepository->relation($relations)->get()->count();
        $result['penelitian'] = $this->UsulanPenelitianRepository->relation($relations)->get()->count();
        $result['survey'] = $this->SurveyRepository->relation($relations)->get()->count();
        $result['surat_rekomendasi'] = $this->SuratRekomendasiRepository->relation($relations)->get()->count();

        return $this->respond($result);

    }

    public function listWithDatatable(Request $request)
    {
        $relations = [

        ];
        return $datatable = datatables()->of($this->AgendaRepository
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
                                          <a href="/agenda-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteAgenda('.$data['id'].')">
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
        return $datatable = datatables()->of($this->AgendaRepository
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
                                          <a href="/agenda-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteAgenda('.$data['id'].')">
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
        $result = $this->AgendaRepository->with(['attachment'])->find($request->id);
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
        $result = $this->DepartemenRepository->find($request->id);
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

        $validator = $this->AgendaRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->AgendaRepository->create(
                [
                    'nama'    =>  $request->nama,
                    'tanggal' => $request->tanggal,
                    'waktu'   => $request->waktu,
                    'tempat'  =>  $request->tempat,
                ]
            );
            if ($result->count()) {
//                return $this->respondInternalError($rr= null,$request->pelaksana);
                if (isset($request->attachment)){
                    if (count($request->attachment) > 0){
                        foreach ($request->attachment as $item => $att) {
                            $this->AttachmentRepository->create([
                                'agenda_id' => $request->id,
                                'nama'       => $att['nama'],
                                'url'        => $att['url']
                            ]);
                        }
                    }
                }
                //else{
//                    return $this->respondInternalError($rr= null,'Pelaksana Dibutuhkan!');
//                }
                DB::commit();
                return $this->respondCreated($result, MessageConstant::AGENDA_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {

        $validator = $this->AgendaRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $deleteAttachment = $this->AttachmentRepository
                ->where('agenda_id',$request->id)
                ->delete();
            $result = $this->AgendaRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'waktu' =>  $request->waktu,
                        'tanggal' => $request->tanggal,
                        'nama'   => $request->nama,
                        'tempat' =>  $request->tempat,
                    ]
                );
            if ($result) {
                if (isset($request->attachment)){
                    if (count($request->attachment) > 0){
                        foreach ($request->attachment as $item => $att) {
                            $this->AttachmentRepository->create([
                                'agenda_id' => $request->id,
                                'nama'       => $att['nama'],
                                'url'        => $att['url']
                            ]);
                        }
                    }
                }

//else{
//                    return $this->respondInternalError($rr= null,'Pelaksana Dibutuhkan!');
//                }
                DB::commit();
                return $this->respondCreated($result, MessageConstant::AGENDA_UPDATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->AgendaRepository->delete($request->id);
        if ($result) {
            return $this->respondOk(MessageConstant::AGENDA_DELETE_SUCCESS_MSG);
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_DELETE_FAILED_MSG);
        }
    }
}
