<?php

namespace App\Repositories\Pembelian;

use Validator;
use App\Repositories\BaseRepository;

class PermintaanPembelianHeaderRepository extends BaseRepository{

    public function validate($request) {
      $validator = Validator::make($request->only(

      'nomor',
      'tanggal',
      'pemasok_id'
      ), [
		  'nomor' => 'required|unique:permintaan_pembelian_header,nomor,NULL,id,deleted_at,NULL',
          'tanggal' => 'required',
          'pemasok_id' => 'required'
          ],
				[
					#'nomor.unique' => ':attribute permintaan pembelian sudah ada sebelumnya',
					'required' => ':attribute wajib diisikan'
				]
      );
      return $validator;
    }

    public function validateNonJadwal($request) {
      $validator = Validator::make($request->only(

      'nomor',
      'tanggal',
      'pemasok_id'
      ), [
		  'nomor' => 'required|unique:permintaan_pembelian_header,nomor,NULL,id,deleted_at,NULL',
          'tanggal' => 'required',
          'pemasok_id' => 'required'
          ],
				[
					#'nomor.unique' => ':attribute permintaan pembelian sudah ada sebelumnya',
					'required' => ':attribute wajib diisikan'
				]
      );
      return $validator;
    }

	public function validate_update($request) {
		$validator = Validator::make($request->only(

			'nomor',
			'tanggal',
			'pemasok_id'
			//'kena_pajak',
			//'jadwal_pengiriman_id'
		), [
				'nomor' => 'required:permintaan_pembelian_header,nomor,'.$request->id.',id,deleted_at,NULL',
				'tanggal' => 'required',
				'pemasok_id' => 'required'
				//'kena_pajak' => 'required',
				//'jadwal_pengiriman_id' => 'required'
			],
			[
				'nomor.unique' => ':attribute permintaan pembelian sudah ada sebelumnya',
				'required' => ':attribute wajib diisi'
			]
		);
		return $validator;
	}

	public function validate_updateNonJadwal($request) {
		$validator = Validator::make($request->only(

			'nomor',
			'tanggal',
			'pemasok_id'
		), [
				'nomor' => 'required:permintaan_pembelian_header,nomor,'.$request->id.',id,deleted_at,NULL',
				'tanggal' => 'required',
				'pemasok_id' => 'required'
			],
			[
				'nomor.unique' => ':attribute permintaan pembelian sudah ada sebelumnya',
				'required' => ':attribute wajib diisi'
			]
		);
		return $validator;
	}
}
