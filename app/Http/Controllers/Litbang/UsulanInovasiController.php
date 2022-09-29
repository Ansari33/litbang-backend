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

class UsulanInovasiController extends APIController
{
    private $UsulanInovasiRepository;
    private $PelaksanaUsulanInovasiRepository;
    private $AttachmentRepository;

    public function initialize()
    {
        $this->UsulanInovasiRepository = \App::make('\App\Repositories\Contracts\Litbang\UsulanInovasiInterface');
        //$this->PelaksanaUsulanInovasiRepository = \App::make('\App\Repositories\Contracts\Litbang\PelaksanaInovasiInterface');
        $this->AttachmentRepository = \App::make('\App\Repositories\Contracts\Litbang\AttachmentInterface');
    }

    public function list(Request $request)
    {
        $relations = [
            'instansi_data'
        ];
        $result = $this->UsulanInovasiRepository
            ->relation($relations)
            ->get();

        return $this->respond($result);

    }

    public function listWithDatatable(Request $request)
    {
        $relations = [
            'instansi_data'
        ];
        return $datatable = datatables()->of($this->UsulanInovasiRepository
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
                                          <a href="/usulan-inovasi-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteUsulanInovasi('.$data['id'].')">
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
        $result = $this->UsulanInovasiRepository->with(['instansi_data'])->find($request->id);
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

        $validator = $this->UsulanInovasiRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->UsulanInovasiRepository->create(
                [
                    'nomor'    =>  $request->nomor,
                    'tanggal' => $request->tanggal,
                    'usulan'   => $request->usulan,
                    'pengusul'  =>  $request->pengusul,
                    'latar_belakang'    =>  $request->latar_belakang,
                    'tujuan' => $request->tujuan,
                    'status'   => 'Mengajukan',
                    'instansi'  =>  $request->instansi,
                    'nomor_kontak' => $request->nomor_kontak
                ]
            );
            if ($result->count()) {
                if(isset($request->attachment)){
                    if (count($request->attachment) > 0){
                        foreach ($request->attachment as $item => $it) {
                            $this->AttachmentRepository->create([
                                'usulan_inovasi_id' => $result->id,
                                'nama'      => $it['nama'],
                                'url'       => $it['url'],
                                'tipe'      => $it['type'],
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->respondCreated($result, MessageConstant::USULAN_INOVASI_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {

        $validator = $this->UsulanInovasiRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
//            $deletePelaksana = $this->PelaksanaUsulanInovasiRepository
//                ->where('inovasi_id',$request->id)
//                ->delete();
            $result = $this->UsulanInovasiRepository
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
                if(isset($request->attachment)){
                    if (count($request->attachment) > 0){
                        foreach ($request->attachment as $item => $it) {
                            $this->AttachmentRepository->create([
                                'usulan_inovasi_id' => $request->id,
                                'nama'      => $it['nama'],
                                'url'       => $it['url'],
                                'tipe'      => $it['type'],
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->respondCreated($result, MessageConstant::USULAN_INOVASI_UPDATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->UsulanInovasiRepository->delete($request->id);
        if ($result) {
            return $this->respondOk(MessageConstant::USULAN_INOVASI_DELETE_SUCCESS_MSG);
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_DELETE_FAILED_MSG);
        }
    }

    public function terkini()
    {
        $result = $this->UsulanInovasiRepository->limit(3)->get();
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound(MessageConstant::USULAN_INOVASI_GET_FAILED_MSG);
        }
    }
}
