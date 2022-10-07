<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class LaporanInovasiRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nama_skpd',
            'nama_unit',
            'nama_inovasi',
            'nomor_sk_inovasi',
            'tanggal_sk_inovasi',
            'jumlah_pelaksana_inovasi',
            'inisiator_daerah_inovasi',
            'urusan_inovasi_daerah',
            'waktu_uji_coba',
            'waktu_diterapkan',
            'rancang_bangun',
            'tujuan',
            'manfaat',
            'hasil',
            'jenis_inovasi',
            'bentuk_inovasi',
            'inovasi_tematik'
        ), [
                'nama_skpd' => 'required',
                'nama_unit' => 'required',
                'nama_inovasi' => 'required',
                'nomor_sk_inovasi'  => 'required',
                'tanggal_sk_inovasi'  => 'required',
                'jumlah_pelaksana_inovasi'  => 'required',
                'inisiator_daerah_inovasi'  => 'required',
                'urusan_inovasi_daerah' => 'required',
                'waktu_uji_coba' => 'required',
                'waktu_diterapkan' => 'required',
                'rancang_bangun' => 'required',
                'tujuan' => 'required',
                'manfaat' => 'required',
                'hasil' => 'required',
                'jenis_inovasi' => 'required',
                'bentuk_inovasi' => 'required',
                'inovasi_tematik' => 'required',
            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'nama_skpd',
            'nama_unit',
            'nama_inovasi',
            'nomor_sk_inovasi',
            'tanggal_sk_inovasi',
            'jumlah_pelaksana_inovasi',
            'inisiator_daerah_inovasi',
            'urusan_inovasi_daerah',
            'waktu_uji_coba',
            'waktu_diterapkan',
            'rancang_bangun',
            'tujuan',
            'manfaat',
            'hasil',
            'jenis_inovasi',
            'bentuk_inovasi',
            'inovasi_tematik'
        ), [
                'nomor' => 'required',
                'nama_skpd' => 'required',
                'nama_unit' => 'required',
                'nama_inovasi' => 'required',
                'nomor_sk_inovasi'  => 'required',
                'tanggal_sk_inovasi'  => 'required',
                'jumlah_pelaksana_inovasi'  => 'required',
                'inisiator_daerah_inovasi'  => 'required',
                'urusan_inovasi_daerah' => 'required',
                'waktu_uji_coba' => 'required',
                'waktu_diterapkan' => 'required',
                'rancang_bangun' => 'required',
                'tujuan' => 'required',
                'manfaat' => 'required',
                'hasil' => 'required',
                'jenis_inovasi' => 'required',
                'bentuk_inovasi' => 'required',
                'inovasi_tematik' => 'required',
            ]
        );
        return $validator;
    }

}
