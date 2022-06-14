<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class KelitbanganRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nomor',
            'judul',
            'tanggal',
            'lingkup',
            'abstrak'
            //'judul'
        ), [
                'nomor' => 'required|unique:kelitbangan,nomor,NULL,id,deleted_at,NULL',
                'judul' => 'required',
                'tanggal' => 'required',
                'lingkup' => 'required',
                'abstrak' => 'required'
            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'nomor',
            'judul',
            'tanggal',
            'lingkup',
            'abstrak'
        //'judul'
        ), [
                'nomor' => 'required',
                'judul' => 'required',
                'tanggal' => 'required',
                'lingkup' => 'required',
                'abstrak' => 'required'
            ]
        );
        return $validator;
    }

    public function validate_update($request)
    {
        $validator = Validator::make($request->only(
            'kode',
            'keterangan'
        ), [
                'kode' => 'required|unique:departemen,kode,'.$request->id.',id,deleted_at,NULL',
                'keterangan' => 'required',
            ]
        );
        return $validator;
    }
}
