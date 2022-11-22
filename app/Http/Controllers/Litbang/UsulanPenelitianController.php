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

class UsulanPenelitianController extends APIController
{
    private $UsulanPenelitianRepository;
    private $PelaksanaUsulanPenelitianRepository;
    private $AttachmentRepository;

    public function initialize()
    {
        $this->UsulanPenelitianRepository = \App::make('\App\Repositories\Contracts\Litbang\UsulanPenelitianInterface');
        //$this->PelaksanaUsulanPenelitianRepository = \App::make('\App\Repositories\Contracts\Litbang\PelaksanaInovasiInterface');
        $this->AttachmentRepository = \App::make('\App\Repositories\Contracts\Litbang\AttachmentInterface');
    }

    public function list(Request $request)
    {
        $relations = [
            'lingkup_data'
        ];
         $result = $this->UsulanPenelitianRepository
            ->relation($relations)

            ->get();
        return $this->respond($result);


        return $datatable = datatables()->of($this->UsulanPenelitianRepository
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
        //$result = $this->KelitbanganRepository->all();
        return $this->respond($result);
    }

    public function listExternal(Request $request)
    {
        $relations = [
//            'instansi_data'
        ];
        $result = $this->UsulanPenelitianRepository
            ->relation($relations)
            ->where('external',1)
            ->get();
        return $this->respond($result);

    }



    public function listWithDatatable(Request $request)
    {
        $relations = [
            'lingkup_data'
        ];
        return $datatable = datatables()->of($this->UsulanPenelitianRepository
            ->relation($relations)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return $list['tanggal'] == null ? '-' : $list['tanggal'];
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })
            ->editColumn('usulan', function ($list) {
                return $list['usulan'] == null ? '-' : $list['usulan'];
            })
            ->editColumn('pengusul', function ($list) {
                return $list['pengusul'] == null ? '-' : $list['pengusul'];
            })
            ->editColumn('tanggal', function ($list) {
                return $list['tanggal'] == null ? '-' : $list['tanggal'];
            })
            ->editColumn('status', function ($list) {
                return $list['status'] == null ? '-' : $list['status'];
            })
            ->addColumn('instansi', function ($list) {
                return $list['lingkup_data'] == null ? '-' : $list['lingkup_data']['nama'];
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
                                          <a href="/usulan-penelitian-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteUsulanPenelitian('.$data['id'].')">
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
            'lingkup_data'
        ];
        return $datatable = datatables()->of($this->UsulanPenelitianRepository
            ->relation($relations)
            ->where('tanggal','>=',$request->tanggal_awal)
            ->where('tanggal','<=',$request->tanggal_akhir)
            ->get())
//            ->editColumn('tanggal', function ($list) {
//                return $list['tanggal'] == null ? '-' : $list['tanggal'];
//                return '<span class="label  label-success label-inline " style="display: none"> '.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->timestamp.' </span>'.Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d M Y');
//                // return Carbon::createFromFormat('Y-m-d',$list['tanggal'])->format('d/m/Y');
//            })
            ->editColumn('usulan', function ($list) {
                return $list['usulan'] == null ? '-' : $list['usulan'];
            })
            ->editColumn('pengusul', function ($list) {
                return $list['pengusul'] == null ? '-' : $list['pengusul'];
            })
            ->editColumn('tanggal', function ($list) {
                return $list['tanggal'] == null ? '-' : $list['tanggal'];
            })
            ->editColumn('status', function ($list) {
                return $list['status'] == null ? '-' : $list['status'];
            })
            ->addColumn('instansi', function ($list) {
                return $list['lingkup_data'] == null ? '-' : $list['lingkup_data']['nama'];
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
                                          <a href="/usulan-penelitian-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteUsulanPenelitian('.$data['id'].')">
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
        $result = $this->UsulanPenelitianRepository->with([])->find($request->id);
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

        $validator = $this->UsulanPenelitianRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->UsulanPenelitianRepository->create(
                [
                'nomor'          => $this->getNumbering()['data'],
                'tanggal'        => $request->tanggal,
                'usulan'         => $request->usulan,
                'pengusul'       => $request->pengusul,
                'latar_belakang' => $request->latar_belakang,
                'tujuan'         => $request->tujuan,
                'status'         => $request->status,
                'instansi'       => $request->instansi,
                'email'          => $request->email,
                'lokasi'         => $request->lokasi,
                'faktor_pendukung'       => $request->faktor_pendukung,
                'faktor_penghambat'      => $request->faktor_penghambat,
                'external'               => $request->external

                ]
            );
            if ($result->count()) {
                if (isset($request->attachment)){
                    if (count($request->attachment) > 0){
                        foreach ($request->attachment as $item => $att) {
                            $this->AttachmentRepository->create([
                                'usulan_penelitian_id' => $result->id,
                                'nama'       => $att['nama'],
                                'url'        => $att['url'],
                                'tipe'       => $att['type']
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->respondCreated($result, MessageConstant::USULAN_PENELITIAN_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {

        $validator = $this->UsulanPenelitianRepository->validateUpdate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $result = $this->UsulanPenelitianRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'nomor'          => $request->nomor,
                        'tanggal'        => $request->tanggal,
                        'usulan'         => $request->usulan,
                        'pengusul'       => $request->pengusul,
                        'latar_belakang' => $request->latar_belakang,
                        'faktor_pendukung'  => $request->faktor_pendukung,
                        'faktor_penghambat' => $request->faktor_penghambat,
                        'tujuan'         => $request->tujuan,
                        'status'         => $request->status,
                        'instansi'       => $request->instansi,
                        'email'          => $request->email
                    ]
                );
            if ($result) {
                if(isset($request->attachment)){
                    if (count($request->attachment) > 0){
                        foreach ($request->attachment as $item => $att) {
                            $this->AttachmentRepository->create([
                                'usulan_penelitian_id' => $result->id,
                                'nama'       => $att['nama'],
                                'url'        => $att['url'],
                                'tipe'       => $att['type']
                            ]);
                        }
                    }
                }

                DB::commit();
                return $this->respondCreated($result, MessageConstant::USULAN_PENELITIAN_UPDATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->UsulanPenelitianRepository->delete($request->id);
        if ($result) {
            return $this->respondOk(MessageConstant::USULAN_PENELITIAN_DELETE_SUCCESS_MSG);
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_DELETE_FAILED_MSG);
        }
    }

    public function getNumbering()
    {
        $kode = MainRepository::numberKodeGenerator([
            'repo'  => $this->UsulanPenelitianRepository,
            'modul' => MessageConstant::PENOMORAN_PENELITIAN
        ]);
        return $this->respond($kode);
    }
}
