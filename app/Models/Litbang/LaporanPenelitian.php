<?php

namespace App\Models\Litbang;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
// use JWTAuth;
use Auth;
use App\Helpers\ModelsConstant;

class LaporanPenelitian extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'laporan_penelitian';
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $hidden = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];

    protected static $logAttributes = ['*'];
    // protected static $ignoreChangedAttributes = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];
    protected static $logOnlyDirty = false;
    protected static $submitEmptyLogs = true;
    protected static $logName = 'Laporan Penelitian';

    protected static function boot() {
        parent::boot();
        static::creating(function($model)  {
//            $user = Auth::user();
//            $model->created_by = $user->id;
//            $model->updated_by = $user->id;
        });

        static::updating(function($model)  {
//            $user = Auth::user();
//            $model->updated_by = $user->id;
        });

        static::deleting(function($model) {
            $model->pelaksana->each->delete();
            $model->attachment->each->delete();
//            $user = Auth::user();
//            $model->deleted_by = $user->id;
//            $model->save();
        });
    }


}
