<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class InstansiRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nama'
        ), [
                'nama' => 'required|unique:instansi,nama,NULL,id,deleted_at,NULL',
            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'nama'
        ), [
                'nama' => 'required'
            ]
        );
        return $validator;
    }

}
