<?php

namespace App\Models\Pembelian;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
//use JWTAuth;
use Auth;
use App\Helpers\ModelsConstant;

class PermintaanPembelianHeader extends Model
{
	//use LogsActivity;
	use SoftDeletes;

	#protected $table = 'permintaan_pembelian_header';
	protected $table = ModelsConstant::TABEL_PERMINTAAN_PEMBELIAN_HEADER;
	protected $dates = ['deleted_at'];
	protected $guarded = [];
	#protected $with = ['detail'];
	protected $hidden = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];

	protected static $logAttributes = ['*'];
	protected static $ignoreChangedAttributes = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];
	//protected static $logOnlyDirty = true;
	protected static $submitEmptyLogs = true;
	protected static $logName = 'Permintaan Pembelian Header';

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
			$model->detail->each->delete();
			#$model->syarat_pengiriman()->delete();
			$user = Auth::user();
			$model->deleted_by = $user->id;
			$model->save();
		});
	}

	public function detail() {
		return $this->hasMany('App\Models\Pembelian\PermintaanPembelianDetail', 'permintaan_pembelian_header_id', 'id');
	}
	public function pemasok_id() {
		return $this->hasOne('App\Models\Pembelian\Pemasok', 'id', 'pemasok_id');
	}
    public function pemasok() {
        return $this->hasOne('App\Models\Pembelian\Pemasok', 'id', 'pemasok_id');
    }
	public function jadwal_pengiriman_id() {
		return $this->hasOne('App\Models\MasterData\JadwalPengiriman', 'id', 'jadwal_pengiriman_id');
	}
	
	public function transaksi_syarat_pengiriman() {
		return $this->hasMany('App\Models\JasaEkspedisi\TransaksiSyaratPengiriman');
	}
	// public function acti() {
	// 	return $this->hasMany('App\Models\Pembelian\PermintaanPembelianDetail', 'permintaan_pembelian_header_id', 'id');
	// }
	
}
