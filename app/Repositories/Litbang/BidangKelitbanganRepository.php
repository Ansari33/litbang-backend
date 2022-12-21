<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class BidangKelitbanganRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nama'
            //'judul'
        ), [
                'nama' => 'required|unique:bidang_kelitbangan,nama',
            ]
        );
        return $validator;
    }
}
