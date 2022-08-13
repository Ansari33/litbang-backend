<?php

namespace App\Repositories\Litbang;

use App\Repositories\BaseRepository;
use Validator;

class SurveyRepository extends BaseRepository
{
    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'nama',
            'form_id'
        ), [
                'nama' => 'required',
                'form_id' => 'required'
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
