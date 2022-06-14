<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class InovasiRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nomor',

            'tanggal',
            'nama',
            'instansi'
        ), [
                'nomor' => 'required|unique:kelitbangan,nomor,NULL,id,deleted_at,NULL',

                'tanggal' => 'required',
                'instansi' => 'required',
                'nama' => 'required'
            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'nomor',
            'tanggal',
            'instansi',
            'nama'
        ), [
                'nomor' => 'required',
                'nama' => 'required',
                'tanggal' => 'required',
                'instansi' => 'required',

            ]
        );
        return $validator;
    }

}
