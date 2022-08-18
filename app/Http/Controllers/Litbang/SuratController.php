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
use Response;

class SuratController extends APIController
{
    private $SuratKeluarRepository;
    private $SuratMasukRepository;


    public function initialize()
    {
        $this->SuratKeluarRepository = \App::make('\App\Repositories\Contracts\Litbang\SuratKeluarInterface');
        $this->SuratMasukRepository = \App::make('\App\Repositories\Contracts\Litbang\SuratMasukInterface');

    }

    public function list(Request $request)
    {
        $relations = [
//            'instansi_data'
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

    public function listWithDatatable(Request $request)
    {
        $relations = [
            'lingkup_data'
        ];
        return $datatable = datatables()->of($this->UsulanPenelitianRepository
            ->relation($relations)
            ->get())
            ->addColumn('instansi', function ($list) {
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

    public function listSuratKeluarWithDatatable(Request $request)
    {
        $relations = [

        ];
        return $datatable = datatables()->of($this->SuratKeluarRepository
            ->relation($relations)
            ->get())
            ->editColumn('file_surat', function ($data) {
                $asset = $data['nomor_urut'].'.pdf';
                return '<a href="/open-file/'.$asset.'"><i class="flaticon2-file"></i></a>';
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
                                          <a href="/surat-keluar-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteSuratKeluar('.$data['id'].')">
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
            ->rawColumns(['tanggal','file_surat','action'])
            ->toJson();
    }

    public function listSuratMasukWithDatatable(Request $request)
    {
        $relations = [

        ];
        return $datatable = datatables()->of($this->SuratMasukRepository
            ->relation($relations)
            ->get())
            ->editColumn('file_surat', function ($data) {
                $asset = $data['surat_masuk'];
                return '<a href="/download-surat-masuk/'.$asset.'"><i class="flaticon2-file"></i></a>';
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
                                          <a href="/surat-masuk-edit/'.$data['id'].'" target="_blank" class="navi-link">
                                                  <span class="navi-icon"><i class="flaticon2-edit"></i></span>
                                                  <span class="navi-text">Edit</span>
                                          </a>
                                  </li>
                                  <li class="navi-item" onclick="deleteSuratMasuk('.$data['id'].')">
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
            ->rawColumns(['tanggal','file_surat','action'])
            ->toJson();
    }

    public function getByIdSuratKeluar(Request $request)
    {
        $result = $this->SuratKeluarRepository->with([])->find($request->id);
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_GET_FAILED_MSG);
        }
    }

    public function getByIdSuratMasuk(Request $request)
    {
        $result = $this->SuratMasukRepository->with([])->find($request->id);
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_GET_FAILED_MSG);
        }
    }

    public function createSuratKeluar(Request $request)
    {

        $validator = $this->SuratKeluarRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->SuratKeluarRepository->create(
                [
                    'nomor_urut'           => $request->nomor_urut,#$this->getNumbering()['data'],
                    'tanggal_surat'        => $request->tanggal_surat,
                    'nomor_surat'          => $request->nomor_surat,
                    'klasifikasi_surat_ID' => $request->klasifikasi,
                    'surat_keluar'         => $request->surat_keluar,
                    'tujuan'               => $request->tujuan,
                    'isi_perihal_singkat'  => $request->isi_perihal_singkat,
                ]
            );
            if ($result->count()) {

                DB::commit();
                return $this->respondCreated($result, MessageConstant::USULAN_PENELITIAN_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function createSuratMasuk(Request $request)
    {

        $validator = $this->SuratMasukRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->SuratMasukRepository->create(
                [
                    'nomor_urut'           => $request->nomor_urut,#$this->getNumbering()['data'],
                    'tanggal_surat'        => $request->tanggal_surat,
                    'nomor_surat'          => $request->nomor_surat,
                    'klasifikasi_surat_id' => $request->klasifikasi,
                    'surat_masuk'         => $request->surat_masuk,
                    'tujuan'               => $request->tujuan,
                    'isi_perihal_singkat'  => $request->isi_perihal_singkat,
                    'tanggal_penerimaan' => $request->tanggal_penerimaan,
                    'pengirim'         => $request->pengirim,
                    'isi_disposisi'               => $request->isi_disposisi,
                    'disposisi_kepada'  => $request->disposisi_kepada,
                ]
            );
            if ($result->count()) {

                DB::commit();
                return $this->respondCreated($result, MessageConstant::USULAN_PENELITIAN_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function updateSuratKeluar(Request $request)
    {

        $validator = $this->SuratKeluarRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->SuratKeluarRepository
                ->where('id',$request->id)
                ->update(
                [
                    'nomor_urut'           => $request->nomor_urut,#$this->getNumbering()['data'],
                    'tanggal_surat'        => $request->tanggal_surat,
                    'nomor_surat'          => $request->nomor_surat,
                    'klasifikasi_surat_ID' => $request->klasifikasi,
                    'surat_keluar'         => $request->surat_keluar,
                    'tujuan'               => $request->tujuan,
                    'isi_perihal_singkat'  => $request->isi_perihal_singkat,
                ]
            );
            if ($result) {

                DB::commit();
                return $this->respondCreated($result, MessageConstant::USULAN_PENELITIAN_CREATE_SUCCESS_MSG);
            } else {
                DB::rollBack();
                return $this->respondConflict();
            }
        }
    }

    public function updateSuratMasuk(Request $request)
    {

        $validator = $this->SuratMasukRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();

            $result = $this->SuratMasukRepository
                ->where('id',$request->id)
                ->update(
                    [
                        'nomor_urut'           => $request->nomor_urut,#$this->getNumbering()['data'],
                        'tanggal_surat'        => $request->tanggal_surat,
                        'nomor_surat'          => $request->nomor_surat,
                        'klasifikasi_surat_id' => $request->klasifikasi,
                        'surat_masuk'          => $request->surat_masuk,
                        'tujuan'               => $request->tujuan,
                        'isi_perihal_singkat'  => $request->isi_perihal_singkat,
                        'pengirim'             => $request->pengirim,
                        'tanggal_penerimaan'         => $request->tanggal_penerimaan,
                        'isi_disposisi'               => $request->isi_disposisi,
                        'disposisi_kepada'  => $request->disposisi_kepada,
                    ]
                );
            if ($result) {
                DB::commit();
                return $this->respondCreated($result, 'Surat Masuk Berhasil Diupdate!');
            } else {
                DB::rollBack();
                return $this->respondNotFound();
            }
        }
    }

    public function deleteSuratKeluar(Request $request)
    {
        $result = $this->SuratKeluarRepository->delete($request->id);
        if ($result) {
            return $this->respondOk('Surat Berhasil Terhapus!');
        } else {
            return $this->respondNotFound(MessageConstant::INOVASI_DELETE_FAILED_MSG);
        }
    }

    public function deleteSuratMasuk(Request $request)
    {
        $result = $this->SuratMasukRepository->delete($request->id);
        if ($result) {
            return $this->respondOk('Surat Berhasil Terhapus!');
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