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

class InstansiController extends APIController
{
    private $InstansiRepository;
    //private $PenggunaRepository;

    public function initialize()
    {
        $this->InstansiRepository = \App::make('\App\Repositories\Contracts\Litbang\InstansiInterface');
       // $this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
    }

    public function list(Request $request)
    {
        $relations = [

        ];
        $result = $this->InstansiRepository
            ->relation($relations)
            ->get();
        return $this->respond($result);

    }

    public function listWithDatatable(Request $request)
    {
        $relations = [
            ''
        ];
        return $datatable = datatables()->of($this->InstansiRepository
            ->relation($relations)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })
            ->editColumn('instansi', function ($list) {
                return $list['instansi_data']['nama'];
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
                                          <a href="/Instansi-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteInstansi('.$data['id'].')">
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
        $result = $this->InstansiRepository->find($request->id);
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound(MessageConstant::INSTANSI_GET_FAILED_MSG);
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

        $validator = $this->InstansiRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            $result = $this->InstansiRepository->create(
                [
                    'nomor' =>  $request->nomor,
                    'tanggal' => $request->tanggal,
                    'nama'   => $request->nama,
                    'tujuan' =>  $request->tujuan,
                    'manfaat' => $request->manfaat,
                    'hasil'   => $request->hasil,
                    'deskripsi' =>  $request->deskripsi,
                    'kelengkapan' => $request->kelengkapan,
                    'instansi'   => $request->instansi,
                ]
            );
            if ($result->count()) {
                return $this->respondCreated($result, MessageConstant::INSTANSI_CREATE_SUCCESS_MSG);
            } else {
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {

        $validator = $this->InstansiRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            $result = $this->InstansiRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'nomor' =>  $request->nomor,
                        'tanggal' => $request->tanggal,
                        'nama'   => $request->nama,
                        'tujuan' =>  $request->tujuan,
                        'manfaat' => $request->manfaat,
                        'hasil'   => $request->hasil,
                        'deskripsi' =>  $request->deskripsi,
                        'kelengkapan' => $request->kelengkapan,
                        'instansi'   => $request->instansi,
                    ]
                );
            if ($result) {
                return $this->respondCreated($result, MessageConstant::INSTANSI_UPDATE_SUCCESS_MSG);
            } else {
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->InstansiRepository->delete($request->id);
        if ($result) {
            return $this->respondOk(MessageConstant::INSTANSI_DELETE_SUCCESS_MSG);
        } else {
            return $this->respondNotFound(MessageConstant::INSTANSI_DELETE_FAILED_MSG);
        }
    }
}
