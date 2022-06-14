<?php

namespace App\Http\Controllers\Perusahaan;

use App\Helpers\CodeConstant;
use App\Helpers\MessageConstant;
use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Validator;
use Spatie\Activitylog\Models\Activity;


class DepartemenController extends APIController
{
	private $DepartemenRepository;
	private $PenggunaRepository;


	public function initialize()
	{
		$this->DepartemenRepository = \App::make('\App\Repositories\Contracts\Perusahaan\DepartemenInterface');
		$this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\AkunInterface');
	}

	public function list(Request $request)
	{
		$result = $this->DepartemenRepository->all();
		return $this->respond($result);
	}

	public function getById(Request $request)
	{
		$result = $this->DepartemenRepository->find($request->id);
		if ($result) {
			return $this->respond($result);
		} else {
			return $this->respondNotFound(MessageConstant::DEPARTEMEN_GET_FAILED_MSG);
		}
	}

	public function getActivity(Request $request)
    {
        $log_detail = [];
        $result['log_detail'] = [];
        $result = $this->DepartemenRepository->find($request->id);
        $result['log'] = Activity::where('log_name','Departemen')
        ->where('subject_id',$request->id)->orderBy('id','desc')->get();

        $properti_baru = [];
        $new_detail = [];
        $log_detail_baru = [];
       // return $result;
        foreach ($result['log'] as $key => $value) {
            $result['log'][$key]['oleh'] = $this->PenggunaRepository
            ->find($result['log'][$key]['causer_id'])->full_name;
            $properti_baru = [];
            if ($value['description'] == 'updated') {
                // Old Attributes
                $properti_baru = [];

                if (isset($result['log'][$key]['properties']['old']['kode'])) {
                	$properti_baru['Kode'] = $result['log'][$key]['properties']['old']['kode'];
                }
        		if (isset($result['log'][$key]['properties']['old']['keterangan'])) {
        			$properti_baru['Keterangan']   = $result['log'][$key]['properties']['old']['keterangan'];
        		}
        
                $result['log'][$key]['old'] = $properti_baru;
                // End Old

                // New Attributes

                if (isset($result['log'][$key]['properties']['attributes']['kode'])) {
                	$properti_baru['Kode'] = $result['log'][$key]['properties']['attributes']['kode'];
                }
        		if (isset($result['log'][$key]['properties']['attributes']['keterangan'])) {
        			$properti_baru['Keterangan']   = $result['log'][$key]['properties']['attributes']['keterangan'];
        		}
        
                $result['log'][$key]['new'] = $properti_baru;
                // End New

            }else {

                // New Attributes
                $properti_baru = [];
               
                $properti_baru['Kode'] = $result['log'][$key]['properties']['attributes']['kode'];
                $properti_baru['Keterangan']   = $result['log'][$key]['properties']['attributes']['keterangan'];
        
                $result['log'][$key]['new'] = $properti_baru;
                // End New
            }    
        }
        
        $result['show_properties'] = ['Harga Jasa','kuantitas','Harga','Kode Pajak'];
        
        return $this->respond($result);
    }

	public function create(Request $request)
	{
		$validator = $this->DepartemenRepository->validate($request);
		if ($validator->fails()) {
			return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
		} else {
			$result = $this->DepartemenRepository->create($request->all());
			if ($result->count()) {
				return $this->respondCreated($result, MessageConstant::DEPARTEMEN_CREATE_SUCCESS_MSG);
			} else {
				return $this->respondConflict();
			}
		}
	}

	public function update(Request $request)
	{
		$validator = $this->DepartemenRepository->validate_update($request);
		if ($validator->fails()) {
			return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
		} else {
			$result = $this->DepartemenRepository->find($request->id);

			if ($result) {
				$result->kode = $request->input('kode');
				$result->keterangan = $request->input('keterangan');
				$result->save();
				return $this->respond($result, MessageConstant::DEPARTEMEN_UPDATE_SUCCESS_MSG);
			} else {
				return $this->respondNotFound();
			}
		}
	}

	public function delete(Request $request)
	{
		$result = $this->DepartemenRepository->delete($request->id);
		if ($result) {
			return $this->respondOk(MessageConstant::DEPARTEMEN_DELETE_SUCCESS_MSG);
		} else {
			return $this->respondNotFound(MessageConstant::DEPARTEMEN_DELETE_FAILED_MSG);
		}
	}

}

