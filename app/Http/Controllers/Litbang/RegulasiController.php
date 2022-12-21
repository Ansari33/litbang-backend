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

class RegulasiController extends APIController
{

    private $RegulasiRepository;
    //private $PenggunaRepository;

    public function initialize()
    {
        $this->RegulasiRepository = \App::make('\App\Repositories\Contracts\Litbang\RegulasiInterface');
       // $this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
    }

    public function list(Request $request)
    {
        $result = $this->RegulasiRepository->with([

        ])->get();
        return $this->respond($result);

    }

    public function listWithDatatable(Request $request)
    {
        $relations = [

        ];
        return $datatable = datatables()->of($this->RegulasiRepository
            ->relation($relations)
            ->get())
            ->editColumn('file', function ($data) {
                return '<a href="/files-attachment/regulasi/'.$data['file'].'" style="color:inherit;">'.$data['file'].'</a>';
            })
            ->addColumn('action', function ($data) {
                $btn_edit   =  '#';
                    //"add_content_tab('pembelian_faktur_pembelian','edit_data_".$data['id']."','pembelian/faktur-pembelian/edit/".$data['id']."', 'Edit Data', '".$data['nomor']."')";
                $btn_delete = '#';
                return '
                      <div class="dropdown dropdown-inline">
                          <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">
                              <i class="flaticon2-layers-1 text-muted"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                              <ul class="navi flex-column navi-hover py-2">
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="/regulasi-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteRegulasi('.$data['id'].')">
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
            ->rawColumns(['file','action'])
            ->toJson();
    }

    public function getById(Request $request)
    {
        $result = $this->RegulasiRepository->with([])->find($request->id);
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

        $validator = $this->RegulasiRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->RegulasiRepository->create(
                [
                    'nama' =>  $request->nama,
                    'file' => $request->file,
                    'tanggal' => $request->tanggal,
                ]
            );
            if ($result->count()) {

                DB::commit();
                return $this->respondCreated($result, 'Regulasi Tersimpan!');
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {
        //return $this->respondInternalError($err = null, $request->all());
        $validator = $this->RegulasiRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->RegulasiRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'nama' =>  $request->nama,
                        'tanggal' => $request->tanggal,
                        'file'   => $request->file,
                    ]
                );
            if ($result) {

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
        $result = $this->RegulasiRepository->delete($request->id);
        if ($result) {
            return $this->respondOk('Data Berhasil Terhapus');
        } else {
            return $this->respondNotFound('Data Tidak Ditemukan!');
        }
    }


}
