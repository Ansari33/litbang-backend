<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class IndikatorAwalInkubatorRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nomor',

            'tanggal',
            'nama',
            'instansi'
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
