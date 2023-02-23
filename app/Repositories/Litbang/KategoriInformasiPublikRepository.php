<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class KategoriInformasiPublikRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nama',
            'tahun',
            'dokumen'
        ), [

            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'nama',
            'tahun',
            'dokumen'
        ), [


            ]
        );
        return $validator;
    }

}
