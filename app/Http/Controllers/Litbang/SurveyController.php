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

class SurveyController extends APIController
{

    private $SurveyRepository;
    //private $PenggunaRepository;

    public function initialize()
    {
        $this->SurveyRepository = \App::make('\App\Repositories\Contracts\Litbang\SurveyInterface');
       // $this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
    }

    public function list(Request $request)
    {
        $result = $this->SurveyRepository->with([

        ])->get();
        return $this->respond($result);

    }

    public function listWithDatatable(Request $request)
    {
        $relations = [

        ];
        return $datatable = datatables()->of($this->SurveyRepository
            ->relation($relations)
            ->get())
            ->addColumn('link', function ($data) {
                return '<a href="https://docs.google.com/forms/d/'. $data['form_id'] .'/edit" target="_blank" class="btn btn-primary btn-sm ml-2">Buka Form</a>';
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
                                          <a href="/survey-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="https://docs.google.com/forms/d/'. $data['form_id'] .'/edit" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-file"></i></span>
                                                  <span class="navi-text">Buka Form</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="'.$btn_edit.'">
                                          <a href="/survey-hasil/'. $data['form_id'] .'/" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-chart"></i></span>
                                                  <span class="navi-text">Hasil</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteSurvey('.$data['id'].')">
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
            ->rawColumns(['link','action'])
            ->toJson();
    }

    public function getById(Request $request)
    {
        $result = $this->SurveyRepository->with([])->find($request->id);
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

        $validator = $this->SurveyRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->SurveyRepository->create(
                [
                    'nama' =>  $request->nama,
                    'form_id' => $request->form_id,
                    'link' =>  $request->link,
                    'keterangan' => $request->keterangan,
                    //'tanggal' => $request->tanggal,
                ]
            );
            if ($result->count()) {

                DB::commit();
                return $this->respondCreated($result, 'Data Survey Tersimpan!');
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {
        //return $this->respondInternalError($err = null, $request->all());
        $validator = $this->SurveyRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->SurveyRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'nama' =>  $request->nama,
                        'form_id' => $request->form_id,
                        'link' =>  $request->link,
                        'keterangan' => $request->keterangan,
                    ]
                );
            if ($result) {

                DB::commit();
                return $this->respondCreated($result, 'Data Survey Berhasil Diupdate');
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->SurveyRepository->delete($request->id);
        if ($result) {
            return $this->respondOk('Data Berhasil Terhapus');
        } else {
            return $this->respondNotFound('Data Tidak Ditemukan!');
        }
    }


}
