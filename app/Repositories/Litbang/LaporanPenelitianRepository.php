<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class LaporanPenelitianRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'penulis',
            'judul',
            'tahun'
        ), [
            'penulis' => 'required',
            'judul' => 'required',
            'tahun' => 'required',
            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'penulis',
            'judul',
            'tahun'
        ), [
                'penulis' => 'required',
                'judul' => 'required',
                'tahun' => 'required',
            ]
        );
        return $validator;
    }

}
