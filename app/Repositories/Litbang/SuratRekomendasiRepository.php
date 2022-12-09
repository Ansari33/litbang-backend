<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class SuratRekomendasiRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'judul',
            'pengusul',
            'institusi'
        ), [
                'judul' => 'required',
                'pengusul' => 'required',
                'institusi' => 'required',
            ]
        );
        return $validator;
    }


}
