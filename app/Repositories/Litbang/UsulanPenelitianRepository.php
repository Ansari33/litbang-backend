<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class UsulanPenelitianRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nomor',
            'tanggal',
            'usulan',
            'pengusul'
        ), [
                'usulan' => 'required',
                'pengusul' => 'required',
            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'nomor',
            'tanggal',
            'usulan',
            'pengusul'
        ), [
                'usulan' => 'required',
                'pengusul' => 'required',

            ]
        );
        return $validator;
    }

}
