<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class SuratKeluarRepository extends BaseRepository
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


}
