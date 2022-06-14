<?php

namespace App\Repositories\Perusahaan;

use App\Repositories\BaseRepository;
use Validator;

class DepartemenRepository extends BaseRepository
{

	public function validate($request)
	{
		$validator = Validator::make($request->only(
			'kode',
			'keterangan'
		), [
				'kode' => 'required|unique:departemen,kode,NULL,id,deleted_at,NULL',
				'keterangan' => 'required'
			]
		);
		return $validator;
	}

	public function validate_update($request)
	{
		$validator = Validator::make($request->only(
			'kode',
			'keterangan'
		), [
				'kode' => 'required|unique:departemen,kode,'.$request->id.',id,deleted_at,NULL',
				'keterangan' => 'required',
			]
		);
		return $validator;
	}
}
