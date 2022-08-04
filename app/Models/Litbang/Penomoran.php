<?php

namespace App\Models\Litbang;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
// use JWTAuth;
use Auth;
use App\Helpers\ModelsConstant;

class Penomoran extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'penomoran';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
//            $user = Auth::user();
//            $model->created_by = $user->id;
//            $model->updated_by = $user->id;
        });

        static::updating(function ($model) {
//            $user = Auth::user();
//            $model->updated_by = $user->id;
        });

        static::deleting(function ($model) {
//            $user = Auth::user();
//            $model->deleted_by = $user->id;
//            $model->save();
        });
    }
}

