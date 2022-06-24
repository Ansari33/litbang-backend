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

class KelitbanganController extends APIController
{
    private $KelitabanganRepository;
    private $PelaksanaKelitbanganRepository;
    private $AttachmentRepository;
    //private $PenggunaRepository;

    public function initialize()
    {
        $this->KelitbanganRepository = \App::make('\App\Repositories\Contracts\Litbang\KelitbanganInterface');
        $this->PelaksanaKelitbanganRepository = \App::make('\App\Repositories\Contracts\Litbang\PelaksanaKelitbanganInterface');
        $this->AttachmentRepository = \App::make('\App\Repositories\Contracts\Litbang\AttachmentInterface');
       // $this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
    }

    public function list(Request $request)
    {
        $result = $this->KelitbanganRepository->with([
            'lingkup_data',
            'pelaksana'
        ])->get();
        return $this->respond($result);
        $relations = [

        ];
        return $datatable = datatables()->of($this->KelitabanganRepository
            ->relation($relations)
            ->get())
            ->editColumn('tanggal', function ($list) {
                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
            })
            ->addColumn('action', function ($data) {
                $btn_edit   = "add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = "destroy(".$data['id'].", '".$data['nomor']."','pembelian/faktur-pembelian','tbl_pembelian_faktur_pembelian')";
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
            ->toJson();
        $result = $this->KelitbanganRepository->all();
        return $this->respond($result);
    }

    public function listWithDatatable(Request $request)
    {
        $relations = [
            'lingkup_data'
        ];
//        return $this->KelitbanganRepository->relation($relations)
//            ->get();
        return $datatable = datatables()->of($this->KelitbanganRepository
            ->relation($relations)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })
            ->editColumn('lingkup', function ($list) {
                return $list['lingkup_data']['nama'];
            })
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
                                          <a href="/kelitbangan-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteKelitbangan('.$data['id'].')">
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
            ->toJson();
    }

    public function getById(Request $request)
    {
        $result = $this->KelitbanganRepository->with(['pelaksana','attachment'])->find($request->id);
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound(MessageConstant::KELITBANGAN_GET_FAILED_MSG);
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

        $validator = $this->KelitbanganRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->KelitbanganRepository->create(
                [
                    'nomor' =>  $request->nomor,
                    'tanggal' => $request->tanggal,
                    'judul'   => $request->judul,
                    'lingkup' =>  $request->lingkup,
                    'abstrak' => $request->abstrak,
                    'tindak_lanjut'   => $request->tindak_lanjut,
                ]
            );
            if ($result->count()) {
                if (count($request->pelaksana) > 0){
                    foreach ($request->pelaksana as $item => $nama) {
                        $this->PelaksanaKelitbanganRepository->create([
                            'kelitbangan_id' => $result->id,
                            'nama'       => $nama,
                        ]);
                    }
                }else{
                    return $this->respondInternalError($rr= null,'Pelaksana Dibutuhkan!');
                }
                if (count($request->attachment) > 0){
                    foreach ($request->attachment as $item => $att) {
                        $this->AttachmentRepository->create([
                            'kelitbangan_id' => $result->id,
                            'nama'       => $att['nama'],
                            'url'        => $att['url']
                        ]);
                    }
                }
                DB::commit();
                return $this->respondCreated($result, MessageConstant::KELITBANGAN_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {
        //return $request->all();
        $validator = $this->KelitbanganRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $deletePelaksana = $this->PelaksanaKelitbanganRepository
                ->where('kelitbangan_id',$request->id)
                ->delete();
            $deleteAttch = $this->AttachmentRepository
                ->where('kelitbangan_id',$request->id)
                ->delete();
            $result = $this->KelitbanganRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'nomor' =>  $request->nomor,
                        'tanggal' => $request->tanggal,
                        'judul'   => $request->judul,
                        'lingkup' =>  $request->lingkup,
                        'abstrak' => $request->abstrak,
                        'tindak_lanjut'   => $request->tindak_lanjut,
                    ]
                );
            if ($result) {
                if (count($request->pelaksana) > 0){
                    foreach ($request->pelaksana as $item => $nama) {
                        $this->PelaksanaKelitbanganRepository->create([
                            'kelitbangan_id' => $request->id,
                            'nama'       => $nama,
                        ]);
                    }
                }else{
                    return $this->respondInternalError($rr= null,'Pelaksana Dibutuhkan!');
                }
                if (count($request->attachment) > 0){
                    foreach ($request->attachment as $item => $att) {
                        $this->AttachmentRepository->create([
                            'kelitbangan_id' => $request->id,
                            'nama'       => $att['nama'],
                            'url'        => $att['url']
                        ]);
                    }
                }
                DB::commit();
                return $this->respondCreated($result, MessageConstant::KELITBANGAN_UPDATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->KelitbanganRepository->delete($request->id);
        if ($result) {
            return $this->respondOk(MessageConstant::KELITBANGAN_DELETE_SUCCESS_MSG);
        } else {
            return $this->respondNotFound(MessageConstant::KELITBANGAN_DELETE_FAILED_MSG);
        }
    }

    public function terkini(){
        $result = $this->KelitbanganRepository->limit(4)->get();
        return $this->respond($result);
    }
}
