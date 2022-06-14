<?php

namespace App\Models\Pembelian;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
//use JWTAuth;
use Auth;
use App\Helpers\ModelsConstant;

class PermintaanPembelianDetail extends Model
{
	//use LogsActivity;
	use SoftDeletes;

	#protected $table = 'permintaan_pembelian_detail';
	protected $table = ModelsConstant::TABEL_PERMINTAAN_PEMBELIAN_DETAIL;
	protected $dates = ['deleted_at'];
	protected $guarded = [];
	protected $hidden = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];

	protected static $logAttributes = ['*'];
	protected static $ignoreChangedAttributes = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];
	protected static $logOnlyDirty = true;
	protected static $submitEmptyLogs = false;
	protected static $logName = 'Permintaan Pembelian Detail';

	protected static function boot() {
		parent::boot();
		static::creating(function($model)  {
			$user = Auth::user();
			$model->created_by = $user->id;
			$model->updated_by = $user->id;
		});

		static::updating(function($model)  {
			$user = Auth::user();
			$model->updated_by = $user->id;
		});

		static::deleting(function($model) {
			$user = Auth::user();
			$model->deleted_by = $user->id;
			$model->syarat_pengiriman->each->delete();
			$model->save();
		});
	}


	 public function header() {
	 	return $this->belongsTo('App\Models\Pembelian\PermintaanPembelianHeader', 'permintaan_pembelian_header_id','id');
	 }

	public function kode_pajak() {
		return $this->hasOne('App\Models\Accounting\KodePajak', 'id', 'kode_pajak_id');

	}

	public function jadwal_pengiriman_id() {
		return $this->hasOne('App\Models\MasterData\JadwalPengiriman', 'id', 'jadwal_pengiriman_id');
	}

	public function harga_jasa_id() {
			return $this->belongsTo('App\Models\MasterData\HargaJasa','harga_jasa_id','id');
		}
	public function syarat_pengiriman() {
		return $this->hasMany('App\Models\JasaEkspedisi\TransaksiSyaratPengiriman','permintaan_pembelian_detail_id');
	}
}
