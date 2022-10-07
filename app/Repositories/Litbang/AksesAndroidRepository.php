<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class AksesAndroidRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'user_id',
            'menu'
        ), [

            ]
        );
        return $validator;
    }
    public function validateUpdate($request)
    {
        $validator = Validator::make($request->only(
            'user_id',
            'menu'
        ), [


            ]
        );
        return $validator;
    }

}
