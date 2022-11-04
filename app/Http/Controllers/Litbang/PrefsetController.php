<?php

namespace App\Http\Controllers\Litbang;

use App\Helpers\MessageConstant;
use App\Http\Controllers\APIController;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Auth;
use App\Repositories\MainRepository;

class PrefsetController extends APIController
{
    private $PrefsetRepository;

    public function initialize()
    {
        $this->PrefsetRepository = \App::make('\App\Repositories\Contracts\Litbang\PrefsetInterface');
    }

    public function get(){
        $result = $this->PrefsetRepository->all();
        return $this->respond($result);
    }

    public function update(Request $request){
        $pref = $request->all();
        foreach ($pref as $item => $value) {
            $this->PrefsetRepository->whereOpt($item,$item)->update(
                [
                    $item => $value,
                    ]
            );
        }
        return $this->respondOk('Pengaturan Tersimpan');
    }
}
