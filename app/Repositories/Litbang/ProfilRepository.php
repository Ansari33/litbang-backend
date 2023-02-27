<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class ProfilRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(

        ), [

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


            ]
        );
        return $validator;
    }

}
