<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class RegulasiRepository extends BaseRepository
{
    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nama',
            'file',
            'tanggal'

        //'judul'
        ), [

            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'nama',
            'file'
        ), [

            ]
        );
        return $validator;
    }

}
